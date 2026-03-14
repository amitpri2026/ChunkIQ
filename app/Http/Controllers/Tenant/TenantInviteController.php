<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TenantInvite;
use App\Support\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantInviteController extends Controller
{
    public function __construct(private TenantManager $manager) {}

    // Generate a new invite link (admin only)
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'in:admin,user'],
        ]);

        $tenant = $this->manager->get();
        $invite = TenantInvite::generate($tenant, $request->user(), $request->role);

        $link = route('invite.show', $invite->token);

        return back()->with('invite_link', $link)->with('invite_role', $invite->role);
    }

    // Show the accept invite page (public — user may not be logged in yet)
    public function show(string $token): View|RedirectResponse
    {
        $invite = TenantInvite::with('tenant')
            ->where('token', $token)
            ->firstOrFail();

        if (!$invite->isValid()) {
            return redirect()->route('login')
                ->withErrors(['invite' => 'This invite link has expired or already been used.']);
        }

        return view('tenant.invite', compact('invite'));
    }

    // Accept the invite
    public function accept(Request $request, string $token): RedirectResponse
    {
        $invite = TenantInvite::with('tenant')
            ->where('token', $token)
            ->firstOrFail();

        if (!$invite->isValid()) {
            return redirect()->route('dashboard')
                ->withErrors(['invite' => 'This invite link has expired or already been used.']);
        }

        $user   = $request->user();
        $tenant = $invite->tenant;

        // Already a member — just redirect
        if ($tenant->hasUser($user)) {
            return redirect()->away($tenant->url('dashboard'))
                ->with('info', 'You are already a member of this workspace.');
        }

        $tenant->users()->attach($user->id, ['role' => $invite->role]);

        $invite->update(['used_at' => now()]);

        return redirect()->away($tenant->url('dashboard'))
            ->with('success', 'Welcome to ' . $tenant->name . '!');
    }
}
