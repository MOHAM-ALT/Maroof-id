<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Services\AnalyticsService;
use App\Enums\PaymentStatus;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct(private AnalyticsService $analyticsService)
    {
    }

    /**
     * Display general analytics.
     */
    public function index(): View
    {
        $user = auth()->user();

        $stats = [
            'total_views' => $user->cards()->sum('views_count'),
            'total_cards' => $user->cards()->count(),
            'active_cards' => $user->cards()->where('is_active', true)->count(),
            'total_downloads' => $user->orders()->where('payment_status', PaymentStatus::Paid)->count(),
        ];

        // Get charts data
        $chartsData = $this->analyticsService->getUsersReport();

        return view('customer.analytics.index', compact('stats', 'chartsData'));
    }

    /**
     * Display analytics for a specific card.
     */
    public function card(Card $card): View
    {
        $this->authorize('view', $card);

        $period = request('period', '30days');
        $analytics = $this->analyticsService->getCardAnalytics($card);

        return view('customer.analytics.card', compact('card', 'analytics', 'period'));
    }

    /**
     * Get sales report.
     */
    public function salesReport(): View
    {
        $period = request('period', 'month');
        $report = $this->analyticsService->getSalesReport($period);

        return view('customer.analytics.sales-report', compact('report', 'period'));
    }
}
