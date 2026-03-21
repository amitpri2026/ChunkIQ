<?php
namespace App\Notifications;

use App\Models\PipelineJob;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JobCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(public PipelineJob $job) {}

    public function via(object $notifiable): array { return ['database']; }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pipeline job completed',
            'body'  => '"' . $this->job->name . '" finished successfully',
            'url'   => '/admin/tenants/' . $this->job->tenant_id,
            'icon'  => '✅',
        ];
    }
}
