<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('site.mail_welcome_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.welcome-registered',
            text: 'emails.welcome-registered-text',
            with: [
                'user' => $this->user,
            ],
        );
    }
}
