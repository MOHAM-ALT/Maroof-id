<?php

namespace App\Mail;

use App\Models\Payout;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayoutNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payout $payout
    ) {}

    public function envelope(): Envelope
    {
        $statusArabic = match($this->payout->status) {
            'completed' => 'مكتمل ✅',
            'pending' => 'قيد الانتظار ⏳',
            'processing' => 'قيد المعالجة 🔄',
            'failed' => 'فشل ❌',
            default => $this->payout->status,
        };

        return new Envelope(
            subject: "إشعار التحويل المالي - {$statusArabic} 💰",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payout-notification',
            with: [
                'payout' => $this->payout,
                'payoutHistoryUrl' => route('customer.dashboard'),
                'supportEmail' => config('mail.from.address'),
            ],
        );
    }
}
