<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Order $order,
        private string $status,
        private float $amount = 0
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $title = match ($this->status) {
            'paid' => 'تم الدفع بنجاح',
            'failed' => 'فشل الدفع',
            'refunded' => 'تم استرجاع المبلغ',
            default => 'تحديث الدفع',
        };

        $message = match ($this->status) {
            'paid' => 'تم دفع مبلغ ' . number_format($this->amount, 2) . ' ر.س للطلب #' . $this->order->order_number,
            'failed' => 'فشلت عملية الدفع للطلب #' . $this->order->order_number . '. يرجى المحاولة مرة أخرى.',
            'refunded' => 'تم استرجاع مبلغ ' . number_format($this->amount, 2) . ' ر.س للطلب #' . $this->order->order_number,
            default => 'تحديث على الدفع للطلب #' . $this->order->order_number,
        };

        return [
            'title' => $title,
            'message' => $message,
            'type' => 'payment',
            'order_id' => $this->order->id,
            'status' => $this->status,
            'amount' => $this->amount,
            'url' => route('customer.orders.show', $this->order),
        ];
    }
}
