<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Card;
use App\Models\CardView;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Track an event
     *
     * @param string $event
     * @param array $data
     * @return void
     */
    public function trackEvent(string $event, array $data = []): void
    {
        // Log event to database or external service
        // For now, we'll store in database (you can extend this with Mixpanel, GA4, etc.)
        
        \Log::channel('analytics')->info("Event: {$event}", $data);
        
        // You could also store in a events table for later analysis
        // DB::table('analytics_events')->insert([
        //     'event' => $event,
        //     'data' => json_encode($data),
        //     'created_at' => now(),
        // ]);
    }

    /**
     * Get platform statistics
     *
     * @return array
     */
    public function getPlatformStats(): array
    {
        $totalUsers = User::count();
        $totalCards = Card::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', PaymentStatus::Paid)->sum('total');
        
        // Calculate growth rates (comparing with last month)
        $lastMonthUsers = User::where('created_at', '<', now()->subMonth())->count();
        $lastMonthOrders = Order::where('created_at', '<', now()->subMonth())->count();
        $lastMonthRevenue = Order::where('payment_status', PaymentStatus::Paid)
            ->where('created_at', '<', now()->subMonth())
            ->sum('total');
        
        $userGrowth = $lastMonthUsers > 0 ? (($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100 : 0;
        $orderGrowth = $lastMonthOrders > 0 ? (($totalOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 0;
        $revenueGrowth = $lastMonthRevenue > 0 ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
        
        return [
            'total_users' => $totalUsers,
            'total_cards' => $totalCards,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'user_growth' => round($userGrowth, 2),
            'order_growth' => round($orderGrowth, 2),
            'revenue_growth' => round($revenueGrowth, 2),
            'timestamp' => now(),
        ];
    }

    /**
     * Get sales report for a specific period
     *
     * @param string $period (today, week, month, year, custom)
     * @param ?\DateTime $startDate
     * @param ?\DateTime $endDate
     * @return array
     */
    public function getSalesReport(string $period = 'month', ?\DateTime $startDate = null, ?\DateTime $endDate = null): array
    {
        // Determine date range
        [$start, $end] = $this->getDateRange($period, $startDate, $endDate);
        
        // Get orders by date
        $ordersByDate = Order::where('payment_status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$start, $end])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->toArray();
        
        // Get top selling cards
        $topTemplates = Order::where('payment_status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('card_id')
            ->select('card_id', DB::raw('COUNT(*) as sales_count'), DB::raw('SUM(total) as total_revenue'))
            ->groupBy('card_id')
            ->orderBy('sales_count', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
        
        // Calculate average order value
        $stats = Order::where('payment_status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('COUNT(*) as total_orders, AVG(total) as avg_value, SUM(total) as total_revenue')
            ->first();
        
        return [
            'period' => $period,
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'orders_by_date' => $ordersByDate,
            'top_templates' => $topTemplates,
            'total_orders' => $stats->total_orders ?? 0,
            'average_order_value' => round($stats->avg_value ?? 0, 2),
            'total_revenue' => round($stats->total_revenue ?? 0, 2),
        ];
    }

    /**
     * Get users report
     *
     * @return array
     */
    public function getUsersReport(): array
    {
        // New users by date (last 30 days)
        $newUsersByDate = User::where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->toArray();
        
        // Active users (users with orders in last 30 days)
        $activeUsers = User::whereHas('orders', function ($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        })->count();
        
        // Users by role (using Spatie Permission)
        $usersByRole = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_type', User::class)
            ->select('roles.name as role', DB::raw('COUNT(*) as count'))
            ->groupBy('roles.name')
            ->get()
            ->toArray();
        
        // User retention (users who made purchases)
        $totalUsers = User::count();
        $purchasingUsers = User::whereHas('orders')->count();
        $retentionRate = $totalUsers > 0 ? ($purchasingUsers / $totalUsers) * 100 : 0;
        
        return [
            'new_users_by_date' => $newUsersByDate,
            'active_users_last_30_days' => $activeUsers,
            'users_by_role' => $usersByRole,
            'total_users' => $totalUsers,
            'purchasing_users' => $purchasingUsers,
            'retention_rate' => round($retentionRate, 2),
        ];
    }

    /**
     * Get card analytics for a specific card
     *
     * @param Card $card
     * @return array
     */
    public function getCardAnalytics(Card $card): array
    {
        $totalViews = CardView::where('card_id', $card->id)->count();
        $uniqueViews = CardView::where('card_id', $card->id)->distinct('ip_address')->count();
        $downloads = Order::where('card_id', $card->id)->where('payment_status', PaymentStatus::Paid)->count();

        // Views by date (last 30 days)
        $viewsByDate = CardView::where('card_id', $card->id)
            ->where('viewed_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(viewed_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->toArray();
        
        // Most used social links
        $socialClicks = DB::table('card_social_links')
            ->where('card_id', $card->id)
            ->select('platform', DB::raw('SUM(clicks_count) as total_clicks'))
            ->groupBy('platform')
            ->orderBy('total_clicks', 'desc')
            ->get()
            ->toArray();
        
        $conversionRate = $totalViews > 0 ? ($downloads / $totalViews) * 100 : 0;

        // Device breakdown
        $devices = CardView::where('card_id', $card->id)
            ->whereNotNull('device_type')
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->groupBy('device_type')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();

        // Country breakdown
        $countries = CardView::where('card_id', $card->id)
            ->whereNotNull('country')
            ->select('country', DB::raw('COUNT(*) as count'))
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->limit(20)
            ->get()
            ->toArray();

        return [
            'card_id' => $card->id,
            'total_views' => $totalViews,
            'unique_views' => $uniqueViews,
            'downloads' => $downloads,
            'conversion_rate' => round($conversionRate, 2),
            'views_by_date' => $viewsByDate,
            'social_clicks' => $socialClicks,
            'devices' => $devices,
            'countries' => $countries,
            'created_at' => $card->created_at,
            'published_at' => null,
        ];
    }

    /**
     * Get helper method to calculate date ranges
     *
     * @param string $period
     * @param ?\DateTime $startDate
     * @param ?\DateTime $endDate
     * @return array
     */
    private function getDateRange(string $period, ?\DateTime $startDate = null, ?\DateTime $endDate = null): array
    {
        $end = $endDate ?? now()->endOfDay();
        
        $start = match($period) {
            'today' => now()->startOfDay(),
            'week' => now()->subWeeks(1)->startOfDay(),
            'month' => now()->subMonth()->startOfDay(),
            'year' => now()->subYear()->startOfDay(),
            'custom' => $startDate ?? now()->subMonth()->startOfDay(),
            default => now()->subMonth()->startOfDay(),
        };
        
        return [$start, $end];
    }
}
