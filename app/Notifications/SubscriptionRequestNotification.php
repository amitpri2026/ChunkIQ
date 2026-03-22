<?php

namespace App\Notifications;

use App\Models\SubscriptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public SubscriptionRequest $request) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New subscription request',
            'body'  => $this->request->name . ' requested the ' . $this->request->planLabel() . ' plan',
            'url'   => '/admin/subscriptions/' . $this->request->id,
            'icon'  => '💳',
        ];
    }
}
