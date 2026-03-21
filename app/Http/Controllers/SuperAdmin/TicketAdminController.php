<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = SupportTicket::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(30)->withQueryString();
        $counts  = [
            'all'         => SupportTicket::count(),
            'open'        => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'closed'      => SupportTicket::where('status', 'closed')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'counts'));
    }

    public function show(SupportTicket $ticket): View
    {
        $ticket->load('user', 'replies.user');
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $request->validate(['message' => 'required|string|max:5000']);

        TicketReply::create([
            'ticket_id'      => $ticket->id,
            'user_id'        => Auth::id(),
            'message'        => $request->message,
            'is_admin_reply' => true,
        ]);

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Reply sent.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $request->validate(['status' => 'required|in:open,in_progress,closed']);
        $ticket->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }
}
