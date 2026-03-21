<?php
namespace App\Mail;

use App\Models\PipelineJob;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class JobFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PipelineJob $job) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pipeline Job Failed — ' . $this->job->name,
            cc: [new Address(config('app.super_admin_email', 'admin@chunkiq.com'), 'ChunkIQ Admin')],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.job-failed');
    }
}
