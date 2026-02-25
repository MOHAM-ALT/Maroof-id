<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Order $order,
        private string $status,
        private string $message = ''
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التوصيل',
        ];

        return [
            'title' => 'تحديث حالة الطلب #' . $this->order->order_number,
            'message' => $this->message ?: 'تم تحديث حالة طلبك إلى: ' . ($statusLabels[$this->status] ?? $this->status),
            'type' => 'order_status',
            'order_id' => $this->order->id,
            'status' => $this->status,
            'url' => route('customer.orders.show', $this->order),
        ];
    }
}
