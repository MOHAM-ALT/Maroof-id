<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\User;
use App\Enums\PaymentStatus;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommissionController extends Controller
{
    protected CommissionService $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * GET /api/v1/commissions/dashboard
     * Get commission dashboard for authenticated user
     */
    public function dashboard(): JsonResponse
    {
        $user = auth()->user();

        // Get monthly earnings
        $monthlyEarnings = Payout::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('paid_at', '>=', now()->startOfMonth())
            ->sum('amount');

        // Get total earnings
        $totalEarnings = Payout::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount');

        // Get pending payouts
        $pendingPayouts = Payout::query()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        // Count completed payouts
        $completedPayouts = Payout::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return response()->json([
            'message' => 'تم جلب لوحة تحكم العمولات بنجاح',
            'data' => [
                'monthly_earnings' => $monthlyEarnings,
                'total_earnings' => $totalEarnings,
                'pending_payouts' => $pendingPayouts,
                'completed_payouts' => $completedPayouts,
            ],
            'status' => 'success'
        ], 200);
    }

    /**
     * GET /api/v1/commissions/history
     * Get payout history
     */
    public function history(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'period' => 'nullable|in:week,month,year',
            'status' => 'nullable|in:pending,completed,failed',
        ]);

        $query = Payout::query()
            ->where('user_id', auth()->id());

        if ($request->has('period')) {
            $period = $validated['period'];
            $startDate = match($period) {
                'week' => now()->startOfWeek(),
                'month' => now()->startOfMonth(),
                'year' => now()->startOfYear(),
            };
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($request->has('status')) {
            $query->where('status', $validated['status']);
        }

        $payouts = $query->orderByDesc('created_at')->paginate(20);

        return response()->json([
            'message' => 'تم جلب السجل بنجاح',
            'data' => $payouts,
            'status' => 'success'
        ], 200);
    }

    /**
     * GET /api/v1/commissions/payouts
     * Get payouts for authenticated user
     */
    public function payouts(): JsonResponse
    {
        $payouts = Payout::query()
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'message' => 'تم جلب التحويلات بنجاح',
            'data' => $payouts,
            'status' => 'success'
        ], 200);
    }

    /**
     * POST /api/v1/commissions/request-payout
     * Request payout
     */
    public function requestPayout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'method' => 'required|in:bank_transfer,wallet,stripe',
            'bank_name' => 'nullable|string',
            'account_holder' => 'nullable|string',
            'iban' => 'nullable|string',
        ]);

        // Check if user has enough balance
        $availableBalance = Payout::query()
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->sum('amount')
            -
            Payout::query()
                ->where('user_id', auth()->id())
                ->where('status', 'completed')
                ->where('created_at', '<', now()->subMonth())
                ->sum('amount');

        if ($validated['amount'] > $availableBalance) {
            return response()->json([
                'message' => 'الرصيد غير كافي. الرصيد المتاح: ' . $availableBalance,
                'status' => 'error'
            ], 400);
        }

        try {
            $payout = Payout::create([
                'user_id' => auth()->id(),
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'status' => 'pending',
                'reference_number' => 'PYT-' . time(),
                'notes' => $request->description ?? '',
            ]);

            return response()->json([
                'message' => 'تم إنشاء طلب التحويل بنجاح',
                'data' => $payout,
                'status' => 'success'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل إنشاء الطلب: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * GET /api/v1/commissions/levels
     * Get performance levels and bonuses
     */
    public function levels(): JsonResponse
    {
        return response()->json([
            'message' => 'تم جلب مستويات الأداء بنجاح',
            'data' => [
                [
                    'level' => 1,
                    'name' => 'ناشئ',
                    'min_revenue' => 0,
                    'max_revenue' => 500000,
                    'bonus_percentage' => 5,
                    'description' => 'مستوى البداية للشركاء الجدد'
                ],
                [
                    'level' => 2,
                    'name' => 'متقدم',
                    'min_revenue' => 500000,
                    'max_revenue' => 1000000,
                    'bonus_percentage' => 10,
                    'description' => 'مستوى متقدم مع عمولة أعلى'
                ],
                [
                    'level' => 3,
                    'name' => 'محترف',
                    'min_revenue' => 1000000,
                    'max_revenue' => 1500000,
                    'bonus_percentage' => 15,
                    'description' => 'مستوى محترف مع مزايا إضافية'
                ],
                [
                    'level' => 4,
                    'name' => 'نجم',
                    'min_revenue' => 1500000,
                    'max_revenue' => 2000000,
                    'bonus_percentage' => 20,
                    'description' => 'مستوى نجم مع أولويات عالية'
                ],
                [
                    'level' => 5,
                    'name' => 'رائد',
                    'min_revenue' => 2000000,
                    'max_revenue' => 999999999,
                    'bonus_percentage' => 25,
                    'description' => 'مستوى رائد مع أقصى مزايا'
                ],
            ],
            'status' => 'success'
        ], 200);
    }

    /**
     * GET /api/v1/commissions/performance
     * Get user's current performance level
     */
    public function performance(): JsonResponse
    {
        $monthlyRevenue = auth()->user()
            ->orders()
            ->where('payment_status', PaymentStatus::Paid)
            ->whereDate('created_at', '>=', now()->startOfMonth())
            ->sum('total');

        $level = $this->commissionService->calculatePerformanceBonus($monthlyRevenue);

        return response()->json([
            'message' => 'تم جلب مستوى الأداء بنجاح',
            'data' => [
                'monthly_revenue' => $monthlyRevenue,
                'level' => $level['level'],
                'bonus_percentage' => $level['bonus_percentage'],
                'next_level_target' => $this->getNextLevelTarget($level['level']),
            ],
            'status' => 'success'
        ], 200);
    }

    private function getNextLevelTarget($currentLevel): int
    {
        return match($currentLevel) {
            1 => 500000,
            2 => 1000000,
            3 => 1500000,
            4 => 2000000,
            5 => 2000000,
            default => 0,
        };
    }
}
