@extends('layouts.app')

@section('title', 'إتمام الدفع')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">إتمام الدفع</h1>
            <p class="text-gray-600 mt-1">الطلب #{{ $order->order_number }}</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Payment Form -->
            <div class="lg:col-span-3">
                <form method="POST" action="{{ route('customer.payment.process', $order) }}" class="space-y-6">
                    @csrf

                    <!-- Payment Methods -->
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">اختر طريقة الدفع</h2>

                        <div class="space-y-3">
                            @foreach($paymentMethods as $key => $label)
                            <label class="flex items-center gap-4 border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition hover:border-blue-300 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="{{ $key }}" class="w-5 h-5 text-blue-600" {{ $loop->first ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $label }}</p>
                                    <p class="text-gray-500 text-xs">
                                        @switch($key)
                                            @case('tap_sa') بطاقة ائتمان / مدى / Apple Pay @break
                                            @case('stripe') Visa / Mastercard @break
                                            @case('wallet') الدفع من رصيد المحفظة @break
                                            @case('bank_transfer') تحويل بنكي يدوي @break
                                        @endswitch
                                    </p>
                                </div>
                                <div class="text-2xl">
                                    @switch($key)
                                        @case('tap_sa') 💳 @break
                                        @case('stripe') 💳 @break
                                        @case('wallet') 👛 @break
                                        @case('bank_transfer') 🏦 @break
                                    @endswitch
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('payment_method') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Coupon -->
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">كوبون خصم</h2>
                        <div class="flex gap-3">
                            <input type="text" id="couponInput" placeholder="أدخل كود الخصم"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                            <button type="button" onclick="applyCoupon()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold text-sm transition">
                                تطبيق
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-bold text-lg transition shadow-sm">
                        ادفع {{ number_format($order->total, 2) }} ر.س
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">ملخص الطلب</h2>

                    @if($order->card)
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 font-bold">
                            {{ mb_substr($order->card->title ?? '', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-sm">{{ $order->card->title }}</p>
                            <p class="text-gray-500 text-xs">{{ $order->type?->label() ?? 'بطاقة' }} × {{ $order->quantity }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">المجموع الفرعي</span>
                            <span class="font-semibold">{{ number_format($order->subtotal, 2) }} ر.س</span>
                        </div>
                        @if($order->discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>الخصم</span>
                            <span class="font-semibold">-{{ number_format($order->discount, 2) }} ر.س</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">الضريبة (15%)</span>
                            <span class="font-semibold">{{ number_format($order->tax, 2) }} ر.س</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">الشحن</span>
                            <span class="font-semibold">{{ number_format($order->shipping_fee, 2) }} ر.س</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="text-gray-900 font-bold text-base">الإجمالي</span>
                            <span class="text-blue-600 font-bold text-xl">{{ number_format($order->total, 2) }} ر.س</span>
                        </div>
                    </div>

                    <!-- Shipping Details -->
                    @if($order->shipping_city)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-gray-500 text-xs mb-1">يُشحن إلى</p>
                        <p class="text-gray-900 text-sm font-medium">{{ $order->shipping_city }}، {{ $order->shipping_address }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function applyCoupon() {
    const code = document.getElementById('couponInput').value;
    if (!code) return;

    fetch('{{ route("customer.orders.apply-coupon", $order) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ coupon_code: code })
    }).then(() => {
        window.location.reload();
    });
}
</script>
@endsection
