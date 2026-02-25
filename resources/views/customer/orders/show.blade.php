@extends('layouts.app')

@section('title', 'تفاصيل الطلب #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-8">
            <a href="{{ route('customer.orders.index') }}" class="text-blue-600 hover:text-blue-700 text-sm mb-2 inline-block">→ العودة للطلبات</a>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">الطلب #{{ $order->order_number }}</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $order->status?->value === 'completed' ? 'bg-green-100 text-green-800' :
                       ($order->status?->value === 'cancelled' ? 'bg-red-100 text-red-800' :
                       ($order->status?->value === 'processing' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                    {{ $order->status?->label() ?? 'معلق' }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card Info -->
                @if($order->card)
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">البطاقة</h2>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 font-bold text-lg">
                            {{ mb_substr($order->card->full_name ?? $order->card->title, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $order->card->title }}</h3>
                            <p class="text-gray-500 text-sm">{{ $order->card->full_name }}</p>
                        </div>
                        <a href="{{ route('customer.cards.show', $order->card) }}" class="mr-auto text-blue-600 text-sm hover:underline">عرض البطاقة</a>
                    </div>
                </div>
                @endif

                <!-- Order Items -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">تفاصيل الطلب</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-gray-600">نوع الطلب</span><span class="font-semibold">{{ $order->type?->label() ?? $order->type }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">الكمية</span><span class="font-semibold">{{ $order->quantity }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">المجموع الفرعي</span><span class="font-semibold">{{ number_format($order->subtotal, 2) }} ر.س</span></div>
                        @if($order->discount > 0)
                        <div class="flex justify-between text-green-600"><span>الخصم</span><span class="font-semibold">-{{ number_format($order->discount, 2) }} ر.س</span></div>
                        @endif
                        <div class="flex justify-between"><span class="text-gray-600">الضريبة</span><span class="font-semibold">{{ number_format($order->tax, 2) }} ر.س</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">الشحن</span><span class="font-semibold">{{ number_format($order->shipping_fee, 2) }} ر.س</span></div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="text-gray-900 font-bold">الإجمالي</span>
                            <span class="text-blue-600 font-bold text-lg">{{ number_format($order->total, 2) }} ر.س</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">معلومات الشحن</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">العنوان</p>
                            <p class="font-medium text-gray-900">{{ $order->shipping_address ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">المدينة</p>
                            <p class="font-medium text-gray-900">{{ $order->shipping_city ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">الرمز البريدي</p>
                            <p class="font-medium text-gray-900">{{ $order->shipping_postal_code ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">رقم الجوال</p>
                            <p class="font-medium text-gray-900" dir="ltr">{{ $order->shipping_phone ?? '-' }}</p>
                        </div>
                        @if($order->tracking_number)
                        <div class="col-span-2">
                            <p class="text-gray-500">رقم التتبع</p>
                            <p class="font-medium text-blue-600" dir="ltr">{{ $order->tracking_number }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Payment Status -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4">حالة الدفع</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $order->payment_status?->value === 'paid' ? 'bg-green-100 text-green-800' :
                           ($order->payment_status?->value === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ $order->payment_status?->label() ?? 'معلق' }}
                    </span>

                    @if($order->paid_at)
                        <p class="text-gray-500 text-sm mt-2">تاريخ الدفع: {{ $order->paid_at->format('Y/m/d H:i') }}</p>
                    @endif

                    @if($order->payment_method)
                        <p class="text-gray-500 text-sm mt-1">طريقة الدفع: {{ $order->payment_method }}</p>
                    @endif
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4">الإجراءات</h3>
                    <div class="space-y-3">
                        @if(!$order->isPaid() && !$order->isCancelled())
                        <a href="{{ route('customer.payment.checkout', $order) }}"
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold text-sm block text-center transition">
                            إكمال الدفع
                        </a>

                        <form method="POST" action="{{ route('customer.orders.cancel', $order) }}"
                              onsubmit="return confirm('هل تريد إلغاء هذا الطلب؟')">
                            @csrf
                            <button type="submit" class="w-full border border-red-300 text-red-600 hover:bg-red-50 px-4 py-2.5 rounded-lg text-sm font-medium transition">
                                إلغاء الطلب
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4">التاريخ</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">تاريخ الطلب</span>
                            <span>{{ $order->created_at->format('Y/m/d') }}</span>
                        </div>
                        @if($order->paid_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">تاريخ الدفع</span>
                            <span>{{ $order->paid_at->format('Y/m/d') }}</span>
                        </div>
                        @endif
                        @if($order->shipped_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">تاريخ الشحن</span>
                            <span>{{ $order->shipped_at->format('Y/m/d') }}</span>
                        </div>
                        @endif
                        @if($order->delivered_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">تاريخ التسليم</span>
                            <span>{{ $order->delivered_at->format('Y/m/d') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
