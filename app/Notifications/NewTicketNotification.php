<?php
namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTicketNotification extends Notification
{
    use Queueable;

    public function __construct(public SupportTicket $ticket) {}

    public function via(object $notifiable): array { return ['database']; }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New support ticket',
            'body'  => $this->ticket->user->name . ': "' . $this->ticket->subject . '"',
            'url'   => '/admin/tickets/' . $this->ticket->id,
            'icon'  => '🎫',
        ];
    }
}
