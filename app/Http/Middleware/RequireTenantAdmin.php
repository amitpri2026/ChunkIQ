<?php

namespace App\Http\Middleware;

use App\Support\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTenantAdmin
{
    public function __construct(private TenantManager $manager) {}

    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->manager->get();

        if (!$tenant) {
            abort(403, 'No tenant context.');
        }

        if (!$request->user()?->is_super_admin && !$tenant->isAdmin($request->user())) {
            abort(403, 'Admin access required.');
        }

        return $next($request);
    }
}
