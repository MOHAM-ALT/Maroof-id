<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enums\PaymentStatus;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected PaymentService $paymentService;

    public function __construct(OrderService $orderService, PaymentService $paymentService)
    {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    /**
     * GET /api/v1/orders
     * List user's orders
     */
    public function index(): JsonResponse
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->with('transactions', 'coupon', 'partner')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'message' => 'تم جلب الطلبات بنجاح',
            'data' => $orders,
            'status' => 'success'
        ], 200);
    }

    /**
     * GET /api/v1/orders/{id}
     * Show single order
     */
    public function show(Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول',
                'status' => 'error'
            ], 403);
        }

        return response()->json([
            'message' => 'تم جلب الطلب بنجاح',
            'data' => $order->load('transactions', 'coupon', 'partner'),
            'status' => 'success'
        ], 200);
    }

    /**
     * POST /api/v1/orders
     * Create new order
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'type' => 'required|in:standard,premium,custom',
            'quantity' => 'required|integer|min:1|max:1000',
            'price_per_unit' => 'required|numeric|min:0',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        try {
            $order = $this->orderService->createOrder(auth()->user(), $validated);

            if ($request->has('coupon_code')) {
                $this->orderService->applyCoupon($order, $request->coupon_code);
            }

            return response()->json([
                'message' => 'تم إنشاء الطلب بنجاح',
                'data' => $order->load('transactions'),
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
     * PUT /api/v1/orders/{id}
     * Update order
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالتعديل',
                'status' => 'error'
            ], 403);
        }

        if ($order->payment_status === PaymentStatus::Paid) {
            return response()->json([
                'message' => 'لا يمكن تعديل طلب مكتمل',
                'status' => 'error'
            ], 400);
        }

        $validated = $request->validate([
            'quantity' => 'integer|min:1|max:1000',
            'price_per_unit' => 'numeric|min:0',
        ]);

        try {
            $updated = $this->orderService->updateOrder($order, $validated);

            return response()->json([
                'message' => 'تم تحديث الطلب بنجاح',
                'data' => $updated,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل التحديث: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * POST /api/v1/orders/{id}/cancel
     * Cancel order
     */
    public function cancel(Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالإلغاء',
                'status' => 'error'
            ], 403);
        }

        if ($order->payment_status === PaymentStatus::Paid) {
            return response()->json([
                'message' => 'لا يمكن إلغاء طلب مكتمل',
                'status' => 'error'
            ], 400);
        }

        try {
            $this->orderService->cancelOrder($order, 'ألغاه المستخدم');

            return response()->json([
                'message' => 'تم إلغاء الطلب بنجاح',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل الإلغاء: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * POST /api/v1/orders/{id}/apply-coupon
     * Apply coupon to order
     */
    public function applyCoupon(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول',
                'status' => 'error'
            ], 403);
        }

        $validated = $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        try {
            $this->orderService->applyCoupon($order, $validated['coupon_code']);

            return response()->json([
                'message' => 'تم تطبيق الكوبون بنجاح',
                'data' => $order->fresh(),
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل تطبيق الكوبون: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * GET /api/v1/my-orders
     * Get current user's orders
     */
    public function myOrders(): JsonResponse
    {
        $orders = auth()->user()->orders()
            ->with('transactions', 'coupon')
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'message' => 'تم جلب طلباتك بنجاح',
            'data' => $orders,
            'status' => 'success'
        ], 200);
    }
}
