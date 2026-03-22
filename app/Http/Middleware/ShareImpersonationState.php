<?php
namespace App\Http\Middleware;

use App\Support\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareImpersonationState
{
    public function __construct(private TenantManager $manager) {}

    public function handle(Request $request, Closure $next): Response
    {
        View::share('isImpersonating', Session::has('impersonator_id'));

        $user   = $request->user();
        $tenant = $this->manager->get();

        // Super admin browsing a workspace directly (not via user impersonation)
        $superAdminWorkspace = ($user?->is_super_admin && !Session::has('impersonator_id') && $tenant)
            ? $tenant
            : null;

        View::share('superAdminWorkspace', $superAdminWorkspace);

        return $next($request);
    }
}
