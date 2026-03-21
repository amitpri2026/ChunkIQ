<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareImpersonationState
{
    public function handle(Request $request, Closure $next): Response
    {
        View::share('isImpersonating', Session::has('impersonator_id'));
        return $next($request);
    }
}
