<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New User Registration — ' . $this->user->name,
            cc: [new Address(config('app.super_admin_email', 'admin@chunkiq.com'), 'ChunkIQ Admin')],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.user-registered');
    }
}
