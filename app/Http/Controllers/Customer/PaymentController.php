<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\PaymentReceiptMail;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\PaymentService;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
    }

    /**
     * Show checkout page for an order.
     */
    public function checkout(Order $order): View
    {
        $this->authorize('view', $order);

        if ($order->payment_status === PaymentStatus::Paid) {
            return redirect()->route('customer.orders.show', $order)
                ->with('info', 'تم دفع هذا الطلب بالفعل');
        }

        // Get available payment methods
        $paymentMethods = [
            'tap_sa' => 'Tap.sa',
            'stripe' => 'Stripe',
            'wallet' => 'المحفظة الرقمية',
            'bank_transfer' => 'تحويل بنكي',
        ];

        return view('customer.payment.checkout', compact('order', 'paymentMethods'));
    }

    /**
     * Process payment.
     */
    public function process(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('view', $order);

        $validated = $request->validate([
            'payment_method' => 'required|in:tap_sa,stripe,wallet,bank_transfer',
        ]);

        // Process payment using service
        $transaction = $this->paymentService->processPayment(
            $order,
            $validated['payment_method'],
            $request->all()
        );

        if (!$transaction) {
            return back()->with('error', 'فشل معالجة الدفع');
        }

        // Redirect to success page or payment gateway
        if ($validated['payment_method'] === 'tap_sa' || $validated['payment_method'] === 'stripe') {
            // Redirect to payment gateway
            return redirect()->to($transaction->payment_url ?? '/')
                ->with('success', 'يتم توجيهك لصفحة الدفع');
        } else {
            return redirect()->route('customer.payment.success', $transaction);
        }
    }

    /**
     * Handle payment success.
     */
    public function success(Transaction $transaction): View
    {
        if ($transaction->status !== 'completed') {
            return redirect()->route('customer.payment.failed', $transaction)
                ->with('error', 'لم يتم اكتمال الدفع');
        }

        $order = $transaction->order;

        return view('customer.payment.success', compact('order', 'transaction'));
    }

    /**
     * Handle payment failure.
     */
    public function failed(Transaction $transaction): View
    {
        $order = $transaction->order;

        return view('customer.payment.failed', compact('order', 'transaction'));
    }

    /**
     * Handle payment webhook callback from Tap.sa/Stripe.
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        // Verify webhook signature
        if (!$this->verifyWebhookSignature($payload)) {
            return response('Unauthorized', 401);
        }

        // Find transaction by external ID
        $transaction = Transaction::where('external_id', $payload['id'] ?? null)->first();

        if (!$transaction) {
            return response('Transaction not found', 404);
        }

        // Update transaction status based on webhook
        $status = $payload['status'] ?? 'pending';

        if ($status === 'completed' || $status === 'charged') {
            $transaction->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Update order payment status
            $transaction->order->update([
                'payment_status' => PaymentStatus::Paid,
                'paid_at' => now(),
            ]);

            // Send payment receipt email
            $user = $transaction->order->user;
            if ($user && $user->email) {
                Mail::to($user->email)->queue(new PaymentReceiptMail($transaction));
            }
        } elseif ($status === 'failed' || $status === 'declined') {
            $transaction->update([
                'status' => 'failed',
                'completed_at' => now(),
            ]);
        }

        return response('OK', 200);
    }

    /**
     * Verify webhook signature.
     */
    private function verifyWebhookSignature(array $payload): bool
    {
        // Implement signature verification based on your payment provider
        // This is a placeholder - implement based on Tap.sa or Stripe documentation
        
        return true;
    }

    /**
     * Refund a payment.
     */
    public function refund(Transaction $transaction): RedirectResponse
    {
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'لا يمكن استرجاع دفعة غير مكتملة');
        }

        $result = $this->paymentService->issueRefund($transaction->order);

        if ($result['success']) {
            return back()->with('success', 'تم طلب استرجاع المبلغ بنجاح');
        }

        return back()->with('error', 'فشل طلب استرجاع المبلغ');
    }

    /**
     * Get payment methods.
     */
    public function paymentMethods(): View
    {
        $methods = $this->paymentService->getAvailablePaymentMethods();

        return view('customer.payment.methods', compact('methods'));
    }
}
