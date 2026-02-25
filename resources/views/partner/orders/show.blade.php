@extends('layouts.app')

@section('title', 'معروف - تفاصيل الطلب ' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-800">الطلب {{ $order->order_number }}</h1>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'confirmed' => 'bg-sky-50 text-sky-700 border-sky-200',
                        'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'completed' => 'bg-green-50 text-green-700 border-green-200',
                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                    ];
                    $colorClass = $statusColors[$order->status->value] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                @endphp
                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold border {{ $colorClass }}">
                    {{ $order->status->label() }}
                </span>
            </div>
            <p class="text-gray-500">تاريخ الطلب: {{ $order->created_at->format('Y/m/d - H:i') }}</p>
        </div>
        <a href="{{ route('partner.orders.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            العودة للطلبات
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">معلومات الطلب</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">رقم الطلب</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">تاريخ الطلب</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->created_at->format('Y/m/d') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">حالة الطلب</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->status->label() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">النوع</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->type?->label() ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">الكمية</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->quantity }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">حالة الدفع</p>
                        @php
                            $paymentColors = [
                                'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'paid' => 'bg-green-50 text-green-700 border-green-200',
                                'failed' => 'bg-red-50 text-red-700 border-red-200',
                                'refunded' => 'bg-sky-50 text-sky-700 border-sky-200',
                            ];
                            $paymentColorClass = $paymentColors[$order->payment_status->value] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                        @endphp
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold border mt-1 {{ $paymentColorClass }}">
                            {{ $order->payment_status->label() }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">معلومات العميل</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">الاسم</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->user?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">البريد الإلكتروني</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->user?->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Shipping Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">معلومات الشحن</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">العنوان</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->shipping_address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">المدينة</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->shipping_city ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">الرمز البريدي</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->shipping_postal_code ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">رقم الهاتف</p>
                        <p class="font-semibold text-gray-800 mt-1" dir="ltr">{{ $order->shipping_phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">رقم التتبع</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->tracking_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">حالة الشحن</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->shipping_status ?? '-' }}</p>
                    </div>
                    @if($order->shipped_at)
                    <div>
                        <p class="text-sm text-gray-500">تاريخ الشحن</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->shipped_at->format('Y/m/d - H:i') }}</p>
                    </div>
                    @endif
                    @if($order->delivered_at)
                    <div>
                        <p class="text-sm text-gray-500">تاريخ التسليم</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->delivered_at->format('Y/m/d - H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Pricing --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">التكلفة</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">المجموع الفرعي</span>
                        <span class="text-gray-800 font-medium">{{ number_format($order->subtotal, 2) }} ر.س</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">الضريبة</span>
                        <span class="text-gray-800 font-medium">{{ number_format($order->tax, 2) }} ر.س</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">رسوم الشحن</span>
                        <span class="text-gray-800 font-medium">{{ number_format($order->shipping_fee, 2) }} ر.س</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">الخصم</span>
                        <span class="text-green-600 font-medium">- {{ number_format($order->discount, 2) }} ر.س</span>
                    </div>
                    @endif
                    <hr class="border-gray-100">
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-800">الإجمالي</span>
                        <span class="font-bold text-gray-800 text-lg">{{ number_format($order->total, 2) }} ر.س</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            @if(!in_array($order->status->value, ['completed', 'cancelled']))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">الإجراءات</h2>
                <div class="space-y-3">

                    {{-- Mark as Processing --}}
                    @if(in_array($order->status->value, ['pending', 'confirmed']))
                    <form action="{{ route('partner.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="processing">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-xl font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            بدء المعالجة
                        </button>
                    </form>
                    @endif

                    {{-- Mark as Shipped --}}
                    @if($order->status->value === 'processing')
                    <form action="{{ route('partner.orders.update-status', $order) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="shipped">
                        <div>
                            <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-1">رقم التتبع (اختياري)</label>
                            <input type="text" name="tracking_number" id="tracking_number" placeholder="أدخل رقم التتبع..."
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   value="{{ $order->tracking_number }}">
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            تم الشحن
                        </button>
                    </form>

                    {{-- Mark as Delivered --}}
                    <form action="{{ route('partner.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-3 rounded-xl font-semibold hover:bg-green-700 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            تم التسليم
                        </button>
                    </form>
                    @endif

                </div>
            </div>
            @endif

            {{-- Card Info --}}
            @if($order->card)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">البطاقة</h2>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-gray-500">اسم البطاقة</p>
                        <p class="font-semibold text-gray-800 mt-1">{{ $order->card->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Notes --}}
            @if($order->notes || $order->admin_notes)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">ملاحظات</h2>
                @if($order->notes)
                <div class="mb-3">
                    <p class="text-sm text-gray-500 mb-1">ملاحظات العميل</p>
                    <p class="text-gray-700 text-sm bg-gray-50 rounded-lg p-3">{{ $order->notes }}</p>
                </div>
                @endif
                @if($order->admin_notes)
                <div>
                    <p class="text-sm text-gray-500 mb-1">ملاحظات الإدارة</p>
                    <p class="text-gray-700 text-sm bg-gray-50 rounded-lg p-3">{{ $order->admin_notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
