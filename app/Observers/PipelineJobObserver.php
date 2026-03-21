<?php
namespace App\Observers;

use App\Mail\JobFailedMail;
use App\Models\PipelineJob;
use App\Models\User;
use App\Notifications\JobCompletedNotification;
use App\Notifications\JobFailedNotification;
use Illuminate\Support\Facades\Mail;

class PipelineJobObserver
{
    public function updated(PipelineJob $job): void
    {
        if (! $job->wasChanged('status')) {
            return;
        }

        $superAdmins = User::where('is_super_admin', true)->get();

        if ($job->status === 'succeeded') {
            $superAdmins->each(fn($admin) => $admin->notify(new JobCompletedNotification($job)));
            $job->triggeredBy?->notify(new JobCompletedNotification($job));
        }

        if ($job->status === 'failed') {
            $superAdmins->each(fn($admin) => $admin->notify(new JobFailedNotification($job)));
            $job->triggeredBy?->notify(new JobFailedNotification($job));
            if ($job->triggeredBy) {
                Mail::to($job->triggeredBy)->send(new JobFailedMail($job));
            }
        }
    }
}
