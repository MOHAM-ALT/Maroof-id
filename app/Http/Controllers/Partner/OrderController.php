<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusMail;
use App\Models\Partner;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    private function getPartner(): ?Partner
    {
        return Partner::where('email', Auth::user()->email)->first();
    }

    public function index(Request $request)
    {
        $partner = $this->getPartner();
        if (!$partner) {
            return redirect()->route('partner.dashboard');
        }

        $query = $partner->orders()->with(['user', 'card']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);
        return view('partner.orders.index', compact('orders', 'partner'));
    }

    public function show(Order $order)
    {
        $partner = $this->getPartner();
        if (!$partner || $order->partner_id !== $partner->id) {
            abort(403);
        }

        $order->load(['user', 'card', 'transactions']);
        return view('partner.orders.show', compact('order', 'partner'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $partner = $this->getPartner();
        if (!$partner || $order->partner_id !== $partner->id) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:processing,shipped,delivered']);

        $action = $request->status;

        if ($action === 'processing') {
            $order->update(['status' => OrderStatus::Processing]);
        } elseif ($action === 'shipped') {
            $request->validate(['tracking_number' => 'nullable|string|max:100']);
            $order->markAsShipped($request->tracking_number);
        } elseif ($action === 'delivered') {
            $order->markAsDelivered();
        }

        // Send order status email to customer
        $statusLabels = [
            'processing' => 'قيد المعالجة',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التوصيل',
        ];
        $user = $order->user;
        if ($user && $user->email) {
            Mail::to($user->email)->queue(new OrderStatusMail(
                $order,
                $action,
                $statusLabels[$action] ?? $action
            ));
        }

        return back()->with('success', 'تم تحديث حالة الطلب');
    }
}
