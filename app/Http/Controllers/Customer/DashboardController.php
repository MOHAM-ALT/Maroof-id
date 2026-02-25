<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $cardsCount = $user->cards()->count();
        $ordersCount = $user->orders()->count();
        $viewsCount = $user->cards()->sum('views_count');
        $activeCardsCount = $user->cards()->where('is_active', true)->where('is_public', true)->count();
        $unreadNotifications = $user->notifications()->whereNull('read_at')->count();

        $recentCards = $user->cards()
            ->with('template')
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = $user->orders()
            ->latest()
            ->take(3)
            ->get();

        return view('customer.dashboard', compact(
            'cardsCount',
            'ordersCount',
            'viewsCount',
            'activeCardsCount',
            'unreadNotifications',
            'recentCards',
            'recentOrders'
        ));
    }
}
