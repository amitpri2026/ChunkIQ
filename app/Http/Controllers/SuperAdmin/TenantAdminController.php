<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PipelineJob;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantAdminController extends Controller
{
    public function index(): View
    {
        $tenants = Tenant::with(['owner', 'users'])
            ->withCount(['users', 'connectors', 'pipelineJobs'])
            ->latest()
            ->paginate(25);

        return view('admin.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load(['owner', 'users', 'connectors']);

        $jobs = $tenant->pipelineJobs()
            ->with(['connector', 'triggeredBy'])
            ->latest()
            ->paginate(20);

        return view('admin.tenants.show', compact('tenant', 'jobs'));
    }

    public function users(): View
    {
        $users = User::withCount('tenants')
            ->latest()
            ->paginate(25);

        return view('admin.users', compact('users'));
    }

    public function cancelJob(PipelineJob $job): RedirectResponse
    {
        if (in_array($job->status, ['pending', 'running'])) {
            $job->markFinished('cancelled', 'Cancelled by super admin.');
        }

        return back()->with('success', 'Job cancelled.');
    }

    public function toggleSuperAdmin(Request $request, User $user): RedirectResponse
    {
        // Prevent removing own super admin status
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['toggle' => 'You cannot modify your own super admin status.']);
        }

        $user->update(['is_super_admin' => !$user->is_super_admin]);

        return back()->with('success', $user->name . ' super admin status updated.');
    }
}
