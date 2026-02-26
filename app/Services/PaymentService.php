<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\Payout;
use App\Enums\PaymentStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process a payment for an order
     *
     * @param Order $order
     * @param string $method Payment method (tap_sa, stripe, wallet, bank_transfer)
     * @param array $details Additional payment details
     * @return Transaction|null
     */
    public function processPayment(Order $order, string $method, array $details = []): ?Transaction
    {
        // Verify order can be paid
        if ($order->payment_status === PaymentStatus::Paid) {
            return null;
        }
        
        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'amount' => $order->total,
            'currency' => 'SAR',
            'status' => 'pending',
            'gateway' => $this->mapMethodToGateway($method),
            'metadata' => $details,
        ]);
        
        // Process based on payment method
        switch ($method) {
            case 'tap_sa':
                return $this->processTapSa($transaction, $order, $details);
            case 'stripe':
                return $this->processStripe($transaction, $order, $details);
            case 'wallet':
                return $this->processWallet($transaction, $order, $details);
            case 'bank_transfer':
                return $this->processBankTransfer($transaction, $order, $details);
            default:
                return null;
        }
    }

    /**
     * Map payment method to gateway name.
     */
    private function mapMethodToGateway(string $method): string
    {
        return match($method) {
            'tap_sa' => 'tap',
            'manual', 'bank_transfer' => 'manual',
            default => 'manual',
        };
    }

    /**
     * Process Tap.sa payment
     *
     * @param Transaction $transaction
     * @param Order $order
     * @param array $details
     * @return Transaction
     */
    private function processTapSa(Transaction $transaction, Order $order, array $details): Transaction
    {
        $secretKey = config('services.tap.secret_key');

        if (!$secretKey) {
            Log::error('Tap.sa secret key not set.');
            $transaction->update(['status' => 'failed']);
            return $transaction;
        }

        try {
            $response = Http::withToken($secretKey)
                ->post('https://api.tap.company/v2/charges', [
                    'amount' => $order->total,
                    'currency' => 'SAR',
                    'customer' => [
                        'first_name' => $order->user->name,
                        'email' => $order->user->email,
                        'phone' => [
                            'country_code' => '966',
                            'number' => $order->user->phone ?? '500000000',
                        ]
                    ],
                    'source' => ['id' => 'src_all'],
                    'redirect' => [
                        'url' => route('customer.payments.callback', ['order' => $order->id, 'method' => 'tap_sa'])
                    ],
                    'reference' => [
                        'transaction' => $transaction->transaction_id,
                        'order' => $order->order_number,
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Store Tap ID and Redirect URL in metadata
                $details['tap_id'] = $data['id'];
                $details['redirect_url'] = $data['transaction']['url'];

                $transaction->update([
                    'metadata' => $details,
                    'status' => 'pending',
                ]);

                return $transaction;
            }

            Log::error('Tap.sa API Error: ' . $response->body());
            $transaction->update(['status' => 'failed']);
            return $transaction;

        } catch (\Exception $e) {
            Log::error('Tap.sa Exception: ' . $e->getMessage());
            $transaction->update(['status' => 'failed']);
            return $transaction;
        }
    }

    /**
     * Process Stripe payment
     *
     * @param Transaction $transaction
     * @param Order $order
     * @param array $details
     * @return Transaction
     */
    private function processStripe(Transaction $transaction, Order $order, array $details): Transaction
    {
        // This would integrate with Stripe SDK
        
        $transaction->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $order->update([
            'payment_status' => PaymentStatus::Paid,
            'payment_method' => 'stripe',
            'paid_at' => now(),
        ]);
        
        $this->calculateCommissions($order);
        
        return $transaction;
    }

    /**
     * Process wallet payment
     *
     * @param Transaction $transaction
     * @param Order $order
     * @param array $details
     * @return Transaction
     */
    private function processWallet(Transaction $transaction, Order $order, array $details): Transaction
    {
        // Deduct from user's wallet balance
        $user = $order->user;
        
        if (!$user->wallet_balance || $user->wallet_balance < $order->total) {
            $transaction->update(['status' => 'failed']);
            return $transaction;
        }
        
        $user->decrement('wallet_balance', $order->total);
        
        $transaction->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $order->update([
            'payment_status' => PaymentStatus::Paid,
            'payment_method' => 'wallet',
            'paid_at' => now(),
        ]);
        
        $this->calculateCommissions($order);
        
        return $transaction;
    }

    /**
     * Process bank transfer payment
     *
     * @param Transaction $transaction
     * @param Order $order
     * @param array $details Bank account details
     * @return Transaction
     */
    private function processBankTransfer(Transaction $transaction, Order $order, array $details): Transaction
    {
        // Bank transfer requires manual verification
        $transaction->update(['status' => 'pending_verification']);

        $order->update(['payment_status' => PaymentStatus::Pending]);

        return $transaction;
    }

    /**
     * Calculate and distribute commissions for an order
     *
     * @param Order $order
     * @return void
     */
    private function calculateCommissions(Order $order): void
    {
        $totalAmount = $order->total;
        $commissions = [];
        
        // Partner commission (printing)
        if ($order->partner_id) {
            $partner = $order->partner;
            $commission = $this->calculatePercentage($totalAmount, $partner->commission_rate);
            $commissions['partner'] = [
                'user_id' => $partner->id,
                'amount' => $commission,
                'type' => 'partner',
                'order_id' => $order->id,
            ];
        }
        
        // Check for coupon with affiliate tracking
        if ($order->coupon_id) {
            $coupon = $order->coupon;
            // Could check if coupon was created by an affiliate
        }
        
        // Create payout records for each commission
        foreach ($commissions as $commission) {
            Payout::create([
                'user_id' => $commission['user_id'],
                'amount' => $commission['amount'],
                'method' => 'bank_transfer',
                'status' => 'pending',
                'reference_number' => $order->order_number,
                'type' => $commission['type'],
            ]);
        }
    }

    /**
     * Refund a completed payment
     *
     * @param Transaction $transaction
     * @param string $reason
     * @return bool
     */
    public function refundPayment(Transaction $transaction, string $reason = ''): bool
    {
        $order = $transaction->order;
        
        if ($transaction->status !== 'completed') {
            return false;
        }
        
        // Process refund based on method
        switch ($transaction->method) {
            case 'wallet':
                $order->user->increment('wallet_balance', $transaction->amount);
                break;
            case 'tap_sa':
            case 'stripe':
                // Call payment provider API to refund
                break;
        }
        
        // Update transaction
        $transaction->update([
            'status' => 'refunded',
            'refund_reason' => $reason,
            'refunded_at' => now(),
        ]);
        
        // Update order
        $order->update([
            'payment_status' => 'refunded',
            'refunded_at' => now(),
        ]);
        
        return true;
    }

    /**
     * Generate a unique transaction ID
     *
     * @return string
     */
    private function generateTransactionId(): string
    {
        do {
            $transactionId = 'TXN-' . date('Ymd') . '-' . Str::random(8);
        } while (Transaction::where('transaction_id', $transactionId)->exists());
        
        return $transactionId;
    }

    /**
     * Calculate percentage of an amount
     *
     * @param float $amount
     * @param float $percentage
     * @return float
     */
    private function calculatePercentage(float $amount, float $percentage): float
    {
        return ($amount * $percentage) / 100;
    }

    /**
     * Get available payment methods.
     */
    public function getAvailablePaymentMethods(): array
    {
        return [
            'tap_sa' => [
                'key' => 'tap_sa',
                'name' => 'Tap.sa',
                'label' => 'بطاقات الائتمان والخصم',
            ],
            'stripe' => [
                'key' => 'stripe',
                'name' => 'Stripe',
                'label' => 'بطاقات دولية',
            ],
            'wallet' => [
                'key' => 'wallet',
                'name' => 'المحفظة الرقمية',
                'label' => 'الدفع من الرصيد',
            ],
            'bank_transfer' => [
                'key' => 'bank_transfer',
                'name' => 'تحويل بنكي',
                'label' => 'تحويل بنكي مباشر',
            ],
        ];
    }

    /**
     * Issue a refund for an order.
     */
    public function issueRefund(Order $order): array
    {
        $transaction = Transaction::where('order_id', $order->id)
            ->where('status', 'completed')
            ->latest()
            ->first();

        if (!$transaction) {
            return ['success' => false, 'message' => 'لا توجد معاملة مكتملة'];
        }

        $result = $this->refundPayment($transaction, 'طلب استرجاع من العميل');

        return [
            'success' => $result,
            'message' => $result ? 'تم الاسترجاع بنجاح' : 'فشل الاسترجاع',
        ];
    }
}
