<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PipelineJob;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'tenants'   => Tenant::count(),
            'users'     => User::count(),
            'jobs'      => PipelineJob::count(),
            'running'   => PipelineJob::where('status', 'running')->count(),
            'failed'    => PipelineJob::where('status', 'failed')->count(),
        ];

        $recentJobs = PipelineJob::with(['tenant', 'connector', 'triggeredBy'])
            ->latest()
            ->limit(20)
            ->get();

        $recentTenants = Tenant::with('owner')->latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentJobs', 'recentTenants'));
    }
}
