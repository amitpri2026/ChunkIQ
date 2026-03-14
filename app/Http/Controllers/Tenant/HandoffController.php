<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HandoffController extends Controller
{
    /**
     * Called on the subdomain with a short-lived token issued by the main domain.
     * Validates the token, logs the user in on this subdomain session, then
     * redirects to the tenant dashboard.
     */
    public function handle(Request $request, string $tenantSlug): RedirectResponse
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login');
        }

        $data = Cache::pull('handoff:' . $token);

        if (!$data || $data['tenant_slug'] !== $tenantSlug) {
            abort(403, 'Invalid or expired handoff token. Please try opening the workspace again.');
        }

        $user = User::find($data['user_id']);

        if (!$user) {
            abort(403);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard');
    }
}
