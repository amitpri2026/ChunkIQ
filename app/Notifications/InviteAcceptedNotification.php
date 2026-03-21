<?php
namespace App\Notifications;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InviteAcceptedNotification extends Notification
{
    use Queueable;

    public function __construct(public User $user, public Tenant $tenant) {}

    public function via(object $notifiable): array { return ['database']; }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Invite accepted',
            'body'  => $this->user->name . ' joined "' . $this->tenant->name . '"',
            'url'   => '/admin/tenants/' . $this->tenant->id,
            'icon'  => '🤝',
        ];
    }
}
