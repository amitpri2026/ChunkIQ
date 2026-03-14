<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Support\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function __construct(private TenantManager $manager) {}

    // Main portal dashboard — shows user's tenants
    public function index(Request $request): View
    {
        $tenants = $request->user()->tenants()->withPivot('role')->get();
        $ownsOne = $request->user()->ownedTenants()->exists();

        return view('tenant.index', compact('tenants', 'ownsOne'));
    }

    // Create tenant form
    public function create(): View
    {
        return view('tenant.create');
    }

    // Store new tenant
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->ownedTenants()->exists()) {
            return redirect()->route('dashboard')
                ->withErrors(['tenant' => 'You can only own one workspace.']);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'required', 'string', 'max:63',
                'regex:/^[a-z0-9\-]+$/',
                'not_in:' . implode(',', Tenant::RESERVED_SLUGS),
                'unique:tenants,slug',
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $tenant = Tenant::create([
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'owner_id'    => $request->user()->id,
            'description' => $data['description'] ?? null,
        ]);

        // Creator automatically joins as admin
        $tenant->users()->attach($request->user()->id, ['role' => 'admin']);

        return redirect()->away($tenant->url('dashboard'))
            ->with('success', 'Workspace created successfully.');
    }

    // Generate a handoff token and redirect user to their tenant subdomain
    public function open(Request $request, Tenant $tenant): RedirectResponse
    {
        abort_unless($tenant->users()->where('user_id', $request->user()->id)->exists(), 403);

        $token = Str::random(40);

        Cache::put('handoff:' . $token, [
            'user_id'     => $request->user()->id,
            'tenant_slug' => $tenant->slug,
        ], now()->addSeconds(60));

        return redirect()->away($tenant->url('auth/handoff') . '?token=' . $token);
    }

    // Tenant subdomain dashboard
    public function dashboard(Request $request): View
    {
        $tenant = $this->manager->get();
        $role   = $tenant->userRole($request->user());

        return view('tenant.dashboard', compact('tenant', 'role'));
    }
}
