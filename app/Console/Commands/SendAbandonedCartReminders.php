<?php

namespace App\Console\Commands;

use App\Mail\AbandonedCartMail;
use App\Models\Order;
use App\Enums\PaymentStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAbandonedCartReminders extends Command
{
    protected $signature = 'mail:abandoned-carts';
    protected $description = 'Send reminder emails for unpaid orders older than 24 hours';

    public function handle(): int
    {
        $orders = Order::where('payment_status', PaymentStatus::Pending)
            ->whereBetween('created_at', [now()->subDays(3), now()->subHours(24)])
            ->with('user')
            ->get();

        $sent = 0;
        foreach ($orders as $order) {
            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)->queue(new AbandonedCartMail($order));
                $sent++;
            }
        }

        $this->info("Sent {$sent} abandoned cart reminders.");

        return self::SUCCESS;
    }
}
