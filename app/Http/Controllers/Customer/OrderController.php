<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function index(): View
    {
        $orders = auth()->user()
            ->orders()
            ->with('card')
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function create(): View
    {
        $cards = auth()->user()->cards()->with('template')->get();

        return view('customer.orders.create', compact('cards'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'type' => 'required|in:physical_card,custom_design,bulk,digital_only',
            'quantity' => 'required|integer|min:1|max:1000',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:10',
            'shipping_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        // Verify card belongs to user
        $card = auth()->user()->cards()->findOrFail($validated['card_id']);

        // Calculate prices
        $prices = ['physical_card' => 99, 'custom_design' => 199, 'bulk' => 79, 'digital_only' => 0];
        $unitPrice = $prices[$validated['type']] ?? 99;
        $subtotal = $unitPrice * $validated['quantity'];
        $tax = round($subtotal * 0.15, 2);
        $shippingFee = 25;

        $validated['subtotal'] = $subtotal;
        $validated['tax'] = $tax;
        $validated['shipping_fee'] = $shippingFee;
        $validated['template_id'] = $card->template_id;

        $couponCode = $validated['coupon_code'] ?? null;
        unset($validated['coupon_code']);

        $order = $this->orderService->createOrder(auth()->user(), $validated);

        if ($couponCode) {
            $this->orderService->applyCoupon($order, $couponCode);
            $order->refresh();
        }

        // Send order confirmation email
        Mail::to(auth()->user()->email)->queue(new OrderConfirmationMail($order));

        return redirect()
            ->route('customer.payment.checkout', $order)
            ->with('success', 'تم إنشاء الطلب، يرجى إكمال الدفع');
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['card', 'transactions', 'coupon']);

        return view('customer.orders.show', compact('order'));
    }

    public function cancel(Order $order): RedirectResponse
    {
        $this->authorize('update', $order);

        if ($order->isCancelled()) {
            return back()->with('error', 'الطلب ملغى بالفعل');
        }

        if ($order->isPaid()) {
            return back()->with('error', 'لا يمكن إلغاء طلب مدفوع');
        }

        $this->orderService->cancelOrder($order, 'ملغى بناءً على طلب العميل');

        return back()->with('success', 'تم إلغاء الطلب بنجاح');
    }

    public function applyCoupon(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('update', $order);

        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $updatedOrder = $this->orderService->applyCoupon($order, $request->coupon_code);

        if (!$updatedOrder) {
            return back()->with('error', 'رمز الكوبون غير صحيح أو منتهي الصلاحية');
        }

        return back()->with('success', 'تم تطبيق الكوبون بنجاح');
    }
}
