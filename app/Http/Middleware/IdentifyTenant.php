<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function __construct(private TenantManager $manager) {}

    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $appHost = config('app.tenant_domain', 'chunkiq.com');

        // Check if this is a subdomain request (e.g. acme.chunkiq.com)
        if (str_ends_with($host, '.' . $appHost)) {
            $slug = str($host)->before('.' . $appHost)->value();

            $tenant = Tenant::where('slug', $slug)->first();

            if (!$tenant) {
                abort(404, 'Tenant not found.');
            }

            $this->manager->set($tenant);
        }

        return $next($request);
    }
}
