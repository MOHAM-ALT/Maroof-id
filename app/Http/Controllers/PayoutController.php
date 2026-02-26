<?php

namespace App\Http\Controllers;

use App\Services\CommissionService;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PayoutController extends Controller
{
    public function __construct(private CommissionService $commissionService)
    {
    }

    /**
     * Show payout history for current user
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $period = $request->get('period', 'all');
        $history = $this->commissionService->getPayoutHistory($user, $period);

        $activeRole = session('active_role', 'customer');

        return view('payouts.index', compact('history', 'period', 'activeRole'));
    }

    /**
     * Request a new payout (withdraw pending earnings)
     */
    public function requestPayout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $pendingAmount = Payout::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        if ($pendingAmount <= 0) {
            return back()->with('error', 'لا يوجد رصيد متاح للسحب');
        }

        return back()->with('success', 'تم تقديم طلب السحب بنجاح. سيتم معالجته خلال 3-5 أيام عمل');
    }
}
