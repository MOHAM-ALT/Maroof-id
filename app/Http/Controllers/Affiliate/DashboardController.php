<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;

        if (!$affiliate) {
            return view('affiliate.no-profile');
        }

        $totalClicks = $affiliate->clicks_count;
        $totalConversions = $affiliate->conversions_count;
        $conversionRate = $totalClicks > 0 ? round(($totalConversions / $totalClicks) * 100, 1) : 0;
        $totalEarnings = $affiliate->earnings;
        $commissionRate = $affiliate->commission_rate;

        $monthlyClicks = $affiliate->clicks()->whereBetween('clicked_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $monthlyConversions = $affiliate->clicks()->whereBetween('clicked_at', [now()->startOfMonth(), now()->endOfMonth()])->where('converted', true)->count();

        $recentClicks = $affiliate->clicks()->latest('clicked_at')->take(20)->get();

        $topCountries = $affiliate->clicks()
            ->selectRaw('visitor_country, COUNT(*) as count')
            ->whereNotNull('visitor_country')
            ->groupBy('visitor_country')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        $referralLink = url('/?ref=' . $affiliate->tracking_id);

        return view('affiliate.dashboard', compact(
            'affiliate', 'totalClicks', 'totalConversions', 'conversionRate',
            'totalEarnings', 'commissionRate', 'monthlyClicks', 'monthlyConversions',
            'recentClicks', 'topCountries', 'referralLink'
        ));
    }
}
