<?php

namespace App\Notifications;

use App\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CardViewMilestoneNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Card $card,
        private int $milestone
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'إنجاز جديد! ' . number_format($this->milestone) . ' مشاهدة',
            'message' => 'بطاقتك "' . ($this->card->full_name ?? $this->card->title) . '" وصلت إلى ' . number_format($this->milestone) . ' مشاهدة!',
            'type' => 'milestone',
            'card_id' => $this->card->id,
            'milestone' => $this->milestone,
            'url' => route('customer.analytics.card', $this->card),
        ];
    }
}
