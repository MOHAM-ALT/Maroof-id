<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Coupon;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private ?PartnerMatchingService $partnerMatching = null,
        private ?CommissionService $commissionService = null,
    ) {
        $this->partnerMatching ??= new PartnerMatchingService();
        $this->commissionService ??= new CommissionService();
    }

    /**
     * Create a new order
     */
    public function createOrder(User $user, array $data): Order
    {
        $data['user_id'] = $user->id;
        $data['order_number'] = $this->generateOrderNumber();
        $data['subtotal'] = $data['subtotal'] ?? 0;

        // Calculate totals
        $data = $this->calculateTotals($data);

        $order = Order::create($data);

        // Auto-assign partner for physical orders
        if (in_array($order->type?->value ?? $data['type'] ?? '', ['physical_card', 'custom_design', 'bulk'])) {
            $this->partnerMatching->assignPartnerToOrder($order);
        }

        return $order;
    }

    /**
     * Update an order
     *
     * @param Order $order
     * @param array $data
     * @return Order
     */
    public function updateOrder(Order $order, array $data): Order
    {
        // Recalculate totals if relevant fields changed
        if (isset($data['subtotal']) || isset($data['coupon_id']) || isset($data['shipping_fee']) || isset($data['tax'])) {
            $data = $this->calculateTotals(array_merge($order->toArray(), $data));
        }
        
        $order->update($data);
        return $order->fresh();
    }

    /**
     * Apply a coupon to an order
     *
     * @param Order $order
     * @param string $couponCode
     * @return Order|null
     */
    public function applyCoupon(Order $order, string $couponCode): ?Order
    {
        $coupon = Coupon::where('code', $couponCode)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->first();
        
        if (!$coupon) {
            return null;
        }
        
        // Check if coupon can be used
        if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
            return null;
        }
        
        // Check minimum order amount
        if ($coupon->min_order_amount && $order->subtotal < $coupon->min_order_amount) {
            return null;
        }
        
        $order->coupon_id = $coupon->id;
        $order = $this->updateOrder($order, ['coupon_id' => $coupon->id]);
        
        // Increment coupon usage
        $coupon->increment('used_count');
        
        return $order;
    }

    /**
     * Calculate order totals (subtotal, tax, shipping, discount, total)
     *
     * @param array $data
     * @return array
     */
    private function calculateTotals(array $data): array
    {
        $subtotal = $data['subtotal'] ?? 0;
        $tax = $data['tax'] ?? 0;
        $shipping_fee = $data['shipping_fee'] ?? 0;
        $discount = 0;
        
        // Check for coupon discount
        if (isset($data['coupon_id']) && $data['coupon_id']) {
            $coupon = Coupon::find($data['coupon_id']);
            if ($coupon) {
                if ($coupon->discount_type === 'percentage') {
                    $discount = ($subtotal * $coupon->discount_value) / 100;
                } else {
                    $discount = $coupon->discount_value;
                }
            }
        }
        
        // Ensure discount doesn't exceed subtotal
        $discount = min($discount, $subtotal);
        
        $total = $subtotal + $tax + $shipping_fee - $discount;
        
        return array_merge($data, [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_fee' => $shipping_fee,
            'discount' => $discount,
            'total' => max(0, $total),
        ]);
    }

    /**
     * Mark order as paid
     *
     * @param Order $order
     * @param string $paymentMethod
     * @param string $transactionId
     * @return Order
     */
    public function markAsPaid(Order $order, string $paymentMethod, string $transactionId): Order
    {
        $order = $this->updateOrder($order, [
            'payment_status' => PaymentStatus::Paid,
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
        ]);

        // Generate commissions for all roles when order is paid
        $order->load(['partner', 'card.template.designer']);
        $this->commissionService->generateCommissionsForOrder($order);

        return $order;
    }

    /**
     * Cancel an order
     *
     * @param Order $order
     * @param string $reason
     * @return Order
     */
    public function cancelOrder(Order $order, string $reason = ''): Order
    {
        return $this->updateOrder($order, [
            'status' => OrderStatus::Cancelled,
            'payment_status' => PaymentStatus::Failed,
            'notes' => $reason ? "سبب الإلغاء: {$reason}" : null,
        ]);
    }

    /**
     * Generate a unique order number
     *
     * @return string
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . Str::random(6);
        } while (Order::where('order_number', $orderNumber)->exists());
        
        return $orderNumber;
    }
}
