<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        // Find partner by email match
        $partner = Partner::where('email', $user->email)->first();

        if (!$partner) {
            return view('partner.no-profile');
        }

        $totalOrders = $partner->orders()->count();
        $pendingOrders = $partner->orders()->where('status', OrderStatus::Pending)->count();
        $processingOrders = $partner->orders()->where('status', OrderStatus::Processing)->count();
        $completedOrders = $partner->orders()->where('status', OrderStatus::Completed)->count();
        $totalRevenue = $partner->orders()->where('status', OrderStatus::Completed)->sum('total');
        $commission = $totalRevenue * ($partner->commission_rate / 100);

        $recentOrders = $partner->orders()
            ->with(['user', 'card'])
            ->latest()
            ->take(10)
            ->get();

        return view('partner.dashboard', compact(
            'partner', 'totalOrders', 'pendingOrders', 'processingOrders',
            'completedOrders', 'totalRevenue', 'commission', 'recentOrders'
        ));
    }
}
