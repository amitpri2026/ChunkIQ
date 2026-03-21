<?php
namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(): View
    {
        $tickets = Auth::user()->supportTickets()->latest()->paginate(20);
        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('tickets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject'  => 'required|string|max:150',
            'message'  => 'required|string|max:5000',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = SupportTicket::create([
            'user_id'  => Auth::id(),
            'subject'  => $data['subject'],
            'message'  => $data['message'],
            'priority' => $data['priority'],
        ]);

        // Notify all super admins
        User::where('is_super_admin', true)->each(
            fn($admin) => $admin->notify(new NewTicketNotification($ticket->load('user')))
        );

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created. Our team will respond shortly.');
    }

    public function show(SupportTicket $ticket): View
    {
        abort_unless($ticket->user_id === Auth::id() || Auth::user()->is_super_admin, 403);
        $ticket->load('replies.user');
        return view('tickets.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        abort_unless($ticket->user_id === Auth::id(), 403);
        $request->validate(['message' => 'required|string|max:5000']);

        TicketReply::create([
            'ticket_id'      => $ticket->id,
            'user_id'        => Auth::id(),
            'message'        => $request->message,
            'is_admin_reply' => false,
        ]);

        return back()->with('success', 'Reply sent.');
    }

    public function close(SupportTicket $ticket): RedirectResponse
    {
        abort_unless($ticket->user_id === Auth::id(), 403);
        $ticket->update(['status' => 'closed']);
        return back()->with('success', 'Ticket closed.');
    }
}
