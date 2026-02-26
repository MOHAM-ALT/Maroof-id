<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $reseller = $user->reseller;

        if (!$reseller) {
            return view('reseller.no-profile');
        }

        $totalSales = $reseller->sales()->count();
        $totalRevenue = $reseller->sales()->sum('amount');
        $totalCommission = $reseller->sales()->sum('commission_earned');
        $currentStock = $reseller->inventory()->sum('card_quantity');
        $monthlySales = $reseller->sales()->whereBetween('sale_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount');
        $monthlyCommission = $reseller->sales()->whereBetween('sale_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('commission_earned');

        $recentSales = $reseller->sales()->latest('sale_date')->take(10)->get();
        $inventory = $reseller->inventory()->get();

        return view('reseller.dashboard', compact(
            'reseller', 'totalSales', 'totalRevenue', 'totalCommission',
            'currentStock', 'monthlySales', 'monthlyCommission', 'recentSales', 'inventory'
        ));
    }
}
