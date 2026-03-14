<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantMemberController extends Controller
{
    public function __construct(private TenantManager $manager) {}

    public function index(Request $request): View
    {
        $tenant  = $this->manager->get();
        $members = $tenant->users()->withPivot('role')->get();

        return view('tenant.members', compact('tenant', 'members'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => ['required', 'in:admin,user']]);

        $tenant = $this->manager->get();

        // Prevent demoting the last admin
        if ($request->role === 'user' && $tenant->owner_id === $user->id) {
            return back()->withErrors(['role' => 'The workspace owner cannot be demoted.']);
        }

        $tenant->users()->updateExistingPivot($user->id, ['role' => $request->role]);

        return back()->with('success', $user->name . ' is now ' . $request->role . '.');
    }

    public function remove(Request $request, User $user): RedirectResponse
    {
        $tenant = $this->manager->get();

        if ($tenant->owner_id === $user->id) {
            return back()->withErrors(['remove' => 'The workspace owner cannot be removed.']);
        }

        $tenant->users()->detach($user->id);

        return back()->with('success', $user->name . ' has been removed from the workspace.');
    }
}
