<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReEngagementMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public int $daysSinceLastLogin
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'اشتقنالك! عد وشوف الجديد 🚀',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.re-engagement',
            with: [
                'user' => $this->user,
                'daysSinceLastLogin' => $this->daysSinceLastLogin,
                'loginUrl' => route('login'),
                'templatesUrl' => route('templates.index'),
            ],
        );
    }
}
