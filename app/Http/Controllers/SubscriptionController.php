<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\SubscriptionRequestNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function show(Request $request): View
    {
        $plan = $request->query('plan', 'starter');

        if (!in_array($plan, ['starter', 'enterprise'])) {
            $plan = 'starter';
        }

        return view('subscribe.request', compact('plan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'plan'    => ['required', 'in:starter,enterprise'],
            'name'    => ['required', 'string', 'max:150'],
            'email'   => ['required', 'email', 'max:200'],
            'company' => ['nullable', 'string', 'max:200'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $sub = SubscriptionRequest::create([
            'plan'      => $data['plan'],
            'name'      => $data['name'],
            'email'     => $data['email'],
            'company'   => $data['company'] ?? null,
            'message'   => $data['message'] ?? null,
            'user_id'   => auth()->id(),
            'tenant_id' => null,
            'status'    => 'pending',
        ]);

        // Notify all super admins
        User::where('is_super_admin', true)->each(
            fn($admin) => $admin->notify(new SubscriptionRequestNotification($sub))
        );

        return redirect()->route('subscribe.thanks');
    }
}
