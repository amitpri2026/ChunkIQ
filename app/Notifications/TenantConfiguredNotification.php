<?php
namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TenantConfiguredNotification extends Notification
{
    use Queueable;

    public function __construct(public Tenant $tenant) {}

    public function via(object $notifiable): array { return ['database']; }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Workspace configured',
            'body'  => '"' . $this->tenant->name . '" completed Azure setup',
            'url'   => '/admin/tenants/' . $this->tenant->id,
            'icon'  => '⚙️',
        ];
    }
}
