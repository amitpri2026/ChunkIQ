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
        abort_unless(auth()->user()->is_super_admin, 403, 'Only super admins can create workspaces.');
        return view('tenant.create');
    }

    // Store new tenant
    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->is_super_admin, 403, 'Only super admins can create workspaces.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'required', 'string', 'max:63',
                'regex:/^[a-z0-9\-]+$/',
                \Illuminate\Validation\Rule::notIn(Tenant::RESERVED_SLUGS),
                'unique:tenants,slug',
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'slug.not_in'  => 'This slug is not available. Please choose a unique identifier.',
            'slug.unique'  => 'This slug is already taken. Please choose another.',
            'slug.regex'   => 'Only lowercase letters, numbers and hyphens are allowed.',
        ]);

        $tenant = Tenant::create([
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'owner_id'    => $request->user()->id,
            'description' => $data['description'] ?? null,
        ]);

        // Creator automatically joins as admin
        $tenant->users()->attach($request->user()->id, ['role' => 'admin']);

        // Notify super admins
        \App\Models\User::where('is_super_admin', true)->each(
            fn($admin) => $admin->notify(new \App\Notifications\TenantCreatedNotification($tenant))
        );
        try {
            \Illuminate\Support\Facades\Mail::to(auth()->user())->send(new \App\Mail\TenantCreatedMail($tenant));
        } catch (\Exception $e) {}

        return redirect()->away($tenant->url('dashboard'))
            ->with('success', 'Workspace created successfully.');
    }

    // Generate a handoff token and redirect user to their tenant subdomain
    public function open(Request $request, Tenant $tenant): RedirectResponse
    {
        $isSuperAdmin = $request->user()->is_super_admin;
        abort_unless($isSuperAdmin || $tenant->users()->where('user_id', $request->user()->id)->exists(), 403);

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
        $role   = $tenant->userRole($request->user()) ?? ($request->user()->is_super_admin ? 'admin' : null);

        // Status data for Admin Actions cards
        $configSteps = 0;
        if ($tenant->getConfig('azure_tenant_id') && $tenant->getConfig('azure_client_id') && $tenant->getConfig('azure_client_secret')) $configSteps++;
        if ($tenant->getConfig('adls_account_name') && $tenant->getConfig('adls_key')) $configSteps++;
        if ($tenant->getConfig('ai_search_endpoint') && $tenant->getConfig('ai_search_key')) $configSteps++;

        $memberCount    = $tenant->users()->count();
        $adminCount     = $tenant->admins()->count();
        $connectorCount = $tenant->connectors()->count();
        $jobCount       = $tenant->pipelineJobs()->count();
        $lastJob        = $tenant->pipelineJobs()->latest()->first();

        return view('tenant.dashboard', compact(
            'tenant', 'role',
            'configSteps', 'memberCount', 'adminCount', 'connectorCount', 'jobCount', 'lastJob'
        ));
    }
}
