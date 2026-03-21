<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{
    public function impersonate(User $user): RedirectResponse
    {
        abort_if($user->is_super_admin, 403, 'Cannot impersonate a super admin.');
        Session::put('impersonator_id', Auth::id());
        Auth::loginUsingId($user->id);
        return redirect()->route('dashboard')->with('success', 'Now logged in as ' . $user->name);
    }

    public function stop(): RedirectResponse
    {
        $originalId = Session::pull('impersonator_id');
        abort_unless($originalId, 403);
        Auth::loginUsingId($originalId);
        return redirect()->route('admin.users')->with('success', 'Returned to super admin account.');
    }
}
