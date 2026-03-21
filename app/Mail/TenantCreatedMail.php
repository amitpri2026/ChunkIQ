<?php
namespace App\Mail;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class TenantCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Tenant $tenant) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Workspace Created — ' . $this->tenant->name,
            cc: [new Address(config('app.super_admin_email', 'admin@chunkiq.com'), 'ChunkIQ Admin')],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.tenant-created');
    }
}
