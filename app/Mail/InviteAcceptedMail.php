<?php
namespace App\Mail;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class InviteAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Tenant $tenant) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invite Accepted — ' . $this->user->name . ' joined ' . $this->tenant->name,
            cc: [new Address(config('app.super_admin_email', 'admin@chunkiq.com'), 'ChunkIQ Admin')],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.invite-accepted');
    }
}
