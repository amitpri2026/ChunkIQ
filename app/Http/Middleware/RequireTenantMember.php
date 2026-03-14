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
            abort(403, 'You are not a member of this workspace.');
        }

        return $next($request);
    }
}
