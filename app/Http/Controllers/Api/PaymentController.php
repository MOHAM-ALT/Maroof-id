<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Enums\PaymentStatus;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * POST /api/v1/payments
     * Process payment for order
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'required|in:tap_sa,stripe,wallet,bank_transfer',
            'amount' => 'required|numeric|min:0',
            // For Tap.sa
            'tap_token' => 'nullable|string',
            // For Stripe
            'stripe_token' => 'nullable|string',
            // For Bank Transfer
            'bank_name' => 'nullable|string',
            'account_holder' => 'nullable|string',
        ]);

        $order = Order::find($validated['order_id']);

        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول',
                'status' => 'error'
            ], 403);
        }

        if ($order->payment_status === PaymentStatus::Paid) {
            return response()->json([
                'message' => 'الطلب مكتمل بالفعل',
                'status' => 'error'
            ], 400);
        }

        try {
            // Create transaction
            $transaction = Transaction::create([
                'order_id' => $order->id,
                'transaction_id' => $this->paymentService->generateTransactionId(),
                'amount' => $validated['amount'],
                'status' => 'pending',
                'payment_method' => $validated['method'],
            ]);

            // Process payment based on method
            $result = $this->paymentService->processPayment($order, $validated['method'], $validated);

            if ($result['success']) {
                $transaction->update(['status' => 'completed']);
                $this->paymentService->calculateCommissions($order);

                return response()->json([
                    'message' => 'تم معالجة الدفع بنجاح',
                    'data' => [
                        'transaction' => $transaction,
                        'order' => $order->fresh(),
                    ],
                    'status' => 'success'
                ], 200);
            } else {
                $transaction->update(['status' => 'failed']);

                return response()->json([
                    'message' => 'فشل الدفع: ' . $result['message'],
                    'status' => 'error'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'خطأ في معالجة الدفع: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * GET /api/v1/transactions/{id}
     * Get transaction details
     */
    public function show(Transaction $transaction): JsonResponse
    {
        if ($transaction->order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول',
                'status' => 'error'
            ], 403);
        }

        return response()->json([
            'message' => 'تم جلب العملية بنجاح',
            'data' => $transaction->load('order'),
            'status' => 'success'
        ], 200);
    }

    /**
     * GET /api/v1/my-transactions
     * Get user's transactions
     */
    public function myTransactions(): JsonResponse
    {
        $transactions = auth()->user()
            ->orders()
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->flatten()
            ->sortByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'message' => 'تم جلب عملياتك بنجاح',
            'data' => $transactions,
            'status' => 'success'
        ], 200);
    }

    /**
     * POST /api/v1/payments/{id}/refund
     * Refund payment
     */
    public function refund(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول',
                'status' => 'error'
            ], 403);
        }

        if ($transaction->status !== 'completed') {
            return response()->json([
                'message' => 'الدفع لم يكتمل بعد',
                'status' => 'error'
            ], 400);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        try {
            $this->paymentService->refundPayment($transaction, $validated['reason']);

            return response()->json([
                'message' => 'تم استرجاع المبلغ بنجاح',
                'data' => $transaction->fresh(),
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل الاسترجاع: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * GET /api/v1/payment-methods
     * Get available payment methods
     */
    public function paymentMethods(): JsonResponse
    {
        return response()->json([
            'message' => 'تم جلب طرق الدفع بنجاح',
            'data' => [
                [
                    'id' => 'tap_sa',
                    'name' => 'Tap.sa',
                    'description' => 'بطاقات الائتمان والحساب الجاري',
                    'icon' => 'credit-card'
                ],
                [
                    'id' => 'stripe',
                    'name' => 'Stripe',
                    'description' => 'بطاقات الائتمان الدولية',
                    'icon' => 'credit-card'
                ],
                [
                    'id' => 'wallet',
                    'name' => 'المحفظة الرقمية',
                    'description' => 'استخدم رصيدك',
                    'icon' => 'wallet'
                ],
                [
                    'id' => 'bank_transfer',
                    'name' => 'تحويل بنكي',
                    'description' => 'تحويل مباشر من البنك',
                    'icon' => 'bank'
                ],
            ],
            'status' => 'success'
        ], 200);
    }
}
