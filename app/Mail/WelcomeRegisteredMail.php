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
            from: new \Illuminate\Mail\Mailables\Address('ruslanbek.tolametov@narxoz.kz', config('app.name')),
            subject: __('site.mail_welcome_subject'),
        );
    }
#тут
    public function content(): Content
    {
        return new Content(
            html: 'mails.welcome_registered',
            text: 'mails.welcome_registered_plain',
            with: [
                'user' => $this->user,
            ],
        );
    }
}
