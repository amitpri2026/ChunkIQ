<?php
namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserRegisteredNotification extends Notification
{
    use Queueable;

    public function __construct(public User $user) {}

    public function via(object $notifiable): array { return ['database']; }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New user registered',
            'body'  => $this->user->name . ' (' . $this->user->email . ') signed up',
            'url'   => '/admin/users',
            'icon'  => '👤',
        ];
    }
}
