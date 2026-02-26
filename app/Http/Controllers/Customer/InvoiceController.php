<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enums\PaymentStatus;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Download invoice PDF for an order.
     */
    public function download(Order $order)
    {
        $this->authorize('view', $order);

        if ($order->payment_status !== PaymentStatus::Paid) {
            return back()->with('error', 'لا يمكن تحميل فاتورة لطلب غير مدفوع');
        }

        $data = [
            'order' => $order,
            'user' => $order->user,
            'card' => $order->card,
            'invoiceNumber' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'invoiceDate' => $order->paid_at ?? $order->created_at,
            'vatRate' => 15,
            'vatAmount' => round($order->total * 0.15 / 1.15, 2),
            'subtotal' => round($order->total / 1.15, 2),
        ];

        $pdf = Pdf::loadView('customer.invoices.pdf', $data);
        $pdf->setPaper('a4');

        return $pdf->download("invoice-{$data['invoiceNumber']}.pdf");
    }

    /**
     * View invoice in browser.
     */
    public function view(Order $order)
    {
        $this->authorize('view', $order);

        if ($order->payment_status !== PaymentStatus::Paid) {
            return back()->with('error', 'لا يمكن عرض فاتورة لطلب غير مدفوع');
        }

        $data = [
            'order' => $order,
            'user' => $order->user,
            'card' => $order->card,
            'invoiceNumber' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'invoiceDate' => $order->paid_at ?? $order->created_at,
            'vatRate' => 15,
            'vatAmount' => round($order->total * 0.15 / 1.15, 2),
            'subtotal' => round($order->total / 1.15, 2),
        ];

        $pdf = Pdf::loadView('customer.invoices.pdf', $data);
        $pdf->setPaper('a4');

        return $pdf->stream("invoice-{$data['invoiceNumber']}.pdf");
    }
}
