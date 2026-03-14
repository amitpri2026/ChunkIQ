<?php

namespace App\Http\Middleware;

use App\Support\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTenantMember
{
    public function __construct(private TenantManager $manager) {}

    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->manager->get();

        if (!$tenant) {
            abort(403, 'No tenant context.');
        }

        if (!$tenant->hasUser($request->user())) {
            return response()->view('errors.not-tenant-member', ['tenant' => $tenant], 403);
        }

        return $next($request);
    }
}
