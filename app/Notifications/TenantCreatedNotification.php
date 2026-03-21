<?php
namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TenantCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Tenant $tenant) {}

    public function via(object $notifiable): array { return ['database']; }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New workspace created',
            'body'  => '"' . $this->tenant->name . '" workspace was created',
            'url'   => '/admin/tenants/' . $this->tenant->id,
            'icon'  => '🏢',
        ];
    }
}
