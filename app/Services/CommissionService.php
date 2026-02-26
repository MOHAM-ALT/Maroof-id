<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateClick;
use App\Models\Reseller;
use App\Models\Designer;
use App\Models\Partner;
use App\Models\Payout;
use App\Models\Order;
use App\Models\Template;
use App\Enums\PayoutStatus;
use App\Enums\GeneralStatus;

class CommissionService
{
    /**
     * Calculate affiliate commission for a converted click
     */
    public function calculateAffiliateCommission(Affiliate $affiliate, Order $order): float
    {
        return $this->calculatePercentage($order->total, $affiliate->commission_rate);
    }

    /**
     * Calculate reseller commission
     */
    public function calculateResellerCommission(Reseller $reseller, float $saleAmount): float
    {
        return $this->calculatePercentage($saleAmount, $reseller->commission_rate);
    }

    /**
     * Calculate designer earnings (royalty)
     */
    public function calculateDesignerEarnings(Designer $designer, float $templateSalesAmount, float $royaltyRate = 20): float
    {
        return $this->calculatePercentage($templateSalesAmount, $royaltyRate);
    }

    /**
     * Calculate partner commission for printing/fulfillment
     */
    public function calculatePartnerCommission(Partner $partner, float $orderAmount): float
    {
        return $this->calculatePercentage($orderAmount, $partner->commission_rate);
    }

    /**
     * Generate all commissions for a completed order
     * Called when order status changes to completed/paid
     */
    public function generateCommissionsForOrder(Order $order): array
    {
        $commissions = [];

        // 1. Partner commission (printing/fulfillment)
        if ($order->partner_id && $order->partner) {
            $amount = $this->calculatePartnerCommission($order->partner, $order->total);
            if ($amount > 0) {
                $payout = $this->createPayoutForPartner($order->partner, $amount, $order);
                if ($payout) {
                    $commissions[] = [
                        'type' => 'partner',
                        'name' => $order->partner->name,
                        'amount' => $amount,
                        'payout_id' => $payout->id,
                    ];
                }
            }
        }

        // 2. Designer royalty (if template has a designer)
        $template = $order->card?->template;
        if ($template && $template->designer_id) {
            $designer = $template->designer;
            if ($designer && $designer->status->value === 'active') {
                $royalty = $this->calculateDesignerEarnings($designer, $order->subtotal);
                if ($royalty > 0) {
                    $payout = $this->createPayoutForUser($designer->user_id, $royalty, $order, 'designer_royalty');
                    $designer->increment('earnings', $royalty);
                    $commissions[] = [
                        'type' => 'designer',
                        'name' => $designer->user->name ?? 'Designer #' . $designer->id,
                        'amount' => $royalty,
                        'payout_id' => $payout->id,
                    ];
                }
            }
        }

        // 3. Affiliate commission (if order came from a referral)
        $affiliateCommission = $this->processAffiliateForOrder($order);
        if ($affiliateCommission) {
            $commissions[] = $affiliateCommission;
        }

        return $commissions;
    }

    /**
     * Process affiliate commission if order user came through a referral
     */
    private function processAffiliateForOrder(Order $order): ?array
    {
        // Check if there's an unconverted click from this user's IP (within last 30 days)
        $click = AffiliateClick::where('converted', false)
            ->where('clicked_at', '>=', now()->subDays(30))
            ->whereHas('affiliate', function ($q) {
                $q->where('status', GeneralStatus::Active);
            })
            ->latest('clicked_at')
            ->first();

        if (!$click) {
            return null;
        }

        $affiliate = $click->affiliate;
        $amount = $this->calculateAffiliateCommission($affiliate, $order);

        if ($amount <= 0) {
            return null;
        }

        // Mark click as converted
        $click->update([
            'converted' => true,
            'conversion_id' => $order->id,
        ]);

        // Update affiliate stats
        $affiliate->increment('conversions_count');
        $affiliate->increment('earnings', $amount);

        // Create payout
        $payout = $this->createPayoutForUser($affiliate->user_id, $amount, $order, 'affiliate_commission');

        return [
            'type' => 'affiliate',
            'name' => $affiliate->user->name ?? 'Affiliate #' . $affiliate->id,
            'amount' => $amount,
            'payout_id' => $payout->id,
        ];
    }

    /**
     * Create a payout record for a partner (no user_id)
     */
    private function createPayoutForPartner(Partner $partner, float $amount, Order $order): ?Payout
    {
        // Partner doesn't have user_id, find user by email
        $userId = \App\Models\User::where('email', $partner->email)->value('id');

        if (!$userId) {
            return null;
        }

        return Payout::create([
            'user_id' => $userId,
            'amount' => $amount,
            'method' => 'bank_transfer',
            'status' => PayoutStatus::Pending,
            'reference_number' => 'PRT-' . $order->order_number,
            'notes' => 'عمولة شريك طباعة: ' . $partner->name . ' | طلب: ' . $order->order_number,
        ]);
    }

    /**
     * Create a payout record for a user
     */
    private function createPayoutForUser(int $userId, float $amount, Order $order, string $type): Payout
    {
        $typeLabels = [
            'designer_royalty' => 'عائد تصميم قالب',
            'affiliate_commission' => 'عمولة تسويق بالعمولة',
            'reseller_commission' => 'عمولة موزع',
        ];

        return Payout::create([
            'user_id' => $userId,
            'amount' => $amount,
            'method' => 'bank_transfer',
            'status' => PayoutStatus::Pending,
            'reference_number' => strtoupper(substr($type, 0, 3)) . '-' . $order->order_number,
            'notes' => ($typeLabels[$type] ?? $type) . ' | طلب: ' . $order->order_number,
        ]);
    }

    /**
     * Record a reseller sale and generate commission
     */
    public function recordResellerSale(Reseller $reseller, float $saleAmount, int $quantity): array
    {
        $commission = $this->calculateResellerCommission($reseller, $saleAmount);

        // Create reseller sale record
        $sale = \App\Models\ResellerSale::create([
            'reseller_id' => $reseller->id,
            'quantity' => $quantity,
            'amount' => $saleAmount,
            'commission_earned' => $commission,
            'sale_date' => now(),
        ]);

        // Create payout
        $payout = Payout::create([
            'user_id' => $reseller->user_id,
            'amount' => $commission,
            'method' => 'bank_transfer',
            'status' => PayoutStatus::Pending,
            'reference_number' => 'RSL-' . $sale->id . '-' . now()->format('Ymd'),
            'notes' => 'عمولة موزع | مبيعات: ' . $quantity . ' بطاقة بمبلغ ' . $saleAmount . ' ر.س',
        ]);

        // Update inventory
        $inventory = $reseller->inventory;
        if ($inventory && $inventory->card_quantity >= $quantity) {
            $inventory->decrement('card_quantity', $quantity);
        }

        return [
            'sale' => $sale,
            'commission' => $commission,
            'payout' => $payout,
        ];
    }

    /**
     * Calculate performance level bonus
     * Level 1: 0% | Level 2: 5% | Level 3: 10% | Level 4: 15% | Level 5: 20%
     */
    public function calculatePerformanceBonus(float $monthlyRevenue): array
    {
        $baselineRevenue = 500000; // SAR

        if ($monthlyRevenue >= $baselineRevenue * 4) {
            $level = 5;
            $bonusPercentage = 20;
        } elseif ($monthlyRevenue >= $baselineRevenue * 3) {
            $level = 4;
            $bonusPercentage = 15;
        } elseif ($monthlyRevenue >= $baselineRevenue * 2) {
            $level = 3;
            $bonusPercentage = 10;
        } elseif ($monthlyRevenue >= $baselineRevenue) {
            $level = 2;
            $bonusPercentage = 5;
        } else {
            $level = 1;
            $bonusPercentage = 0;
        }

        return [
            'level' => $level,
            'bonus_percentage' => $bonusPercentage,
            'estimated_earnings' => $this->calculatePercentage($monthlyRevenue, $bonusPercentage),
            'next_level_revenue' => $level < 5 ? $baselineRevenue * $level : null,
        ];
    }

    /**
     * Get payout history for a user
     */
    public function getPayoutHistory($userOrId, string $period = 'all'): array
    {
        $userId = is_object($userOrId) ? $userOrId->id : $userOrId;

        $query = Payout::where('user_id', $userId);

        switch ($period) {
            case 'month':
                $query->where('created_at', '>=', now()->startOfMonth());
                break;
            case 'year':
                $query->where('created_at', '>=', now()->startOfYear());
                break;
        }

        $payouts = $query->latest()->get();

        return [
            'total_payouts' => $payouts->count(),
            'total_amount' => $payouts->sum('amount'),
            'pending_amount' => $payouts->where('status', PayoutStatus::Pending)->sum('amount'),
            'paid_amount' => $payouts->where('status', PayoutStatus::Completed)->sum('amount'),
            'failed_amount' => $payouts->where('status', PayoutStatus::Failed)->sum('amount'),
            'payouts' => $payouts,
        ];
    }

    /**
     * Bulk process pending payouts (admin action)
     */
    public function processPendingPayouts(int $limit = 100): int
    {
        $payouts = Payout::where('status', PayoutStatus::Pending)
            ->oldest()
            ->limit($limit)
            ->get();

        $processed = 0;
        foreach ($payouts as $payout) {
            if ($this->processPayout($payout)) {
                $processed++;
            }
        }

        return $processed;
    }

    /**
     * Process a single payout
     * In production: integrate with bank transfer API / Tap Payout / STC Pay
     */
    public function processPayout(Payout $payout): bool
    {
        $payout->update([
            'status' => PayoutStatus::Processing,
        ]);

        // Simulated processing - in production replace with actual bank API
        $payout->update([
            'status' => PayoutStatus::Completed,
            'transaction_id' => 'TXN-' . strtoupper(\Illuminate\Support\Str::random(10)),
            'paid_at' => now(),
        ]);

        return true;
    }

    /**
     * Get commission summary for dashboard
     */
    public function getCommissionSummary(): array
    {
        $pendingPayouts = Payout::where('status', PayoutStatus::Pending);
        $completedPayouts = Payout::where('status', PayoutStatus::Completed);

        return [
            'total_pending' => $pendingPayouts->sum('amount'),
            'total_pending_count' => $pendingPayouts->count(),
            'total_paid' => $completedPayouts->sum('amount'),
            'total_paid_count' => $completedPayouts->count(),
            'this_month_paid' => Payout::where('status', PayoutStatus::Completed)
                ->where('paid_at', '>=', now()->startOfMonth())
                ->sum('amount'),
        ];
    }

    /**
     * Calculate percentage of an amount
     */
    private function calculatePercentage(float $amount, float $percentage): float
    {
        return round(($amount * $percentage) / 100, 2);
    }
}
