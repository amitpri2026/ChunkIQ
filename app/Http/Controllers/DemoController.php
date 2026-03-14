<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DemoController extends Controller
{
    public function show(): View
    {
        return view('demo');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:200'],
            'company'    => ['required', 'string', 'max:200'],
            'role'       => ['nullable', 'string', 'max:100'],
            'message'    => ['nullable', 'string', 'max:2000'],
        ]);

        // Log the request so it's always captured
        Log::info('Demo request received', $data);

        // Send notification email to the ChunkIQ team
        $to   = config('mail.demo_notify', env('DEMO_NOTIFY_EMAIL', 'amitpri@gmail.com'));
        $name = $data['first_name'] . ' ' . $data['last_name'];
        $body = implode("\n", [
            "New demo request from ChunkIQ website",
            "",
            "Name:    {$name}",
            "Email:   {$data['email']}",
            "Company: {$data['company']}",
            "Role:    " . ($data['role'] ?? '—'),
            "",
            "Message:",
            $data['message'] ?? '(none)',
        ]);

        try {
            Mail::raw($body, function ($msg) use ($to, $name, $data) {
                $msg->to($to)
                    ->subject("Demo request: {$name} @ {$data['company']}")
                    ->replyTo($data['email'], $name);
            });
        } catch (\Exception $e) {
            Log::error('Demo notification email failed', ['error' => $e->getMessage()]);
        }

        return redirect('/demo')
            ->with('demo_submitted', true)
            ->with('demo_name', $data['first_name']);
    }
}
