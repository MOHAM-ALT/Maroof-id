@extends('layouts.app')

@section('title', 'فشل الدفع')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <!-- Error Icon -->
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">فشل عملية الدفع</h1>
            <p class="text-gray-600 mb-6">عذراً، لم تتم عملية الدفع بنجاح. يمكنك المحاولة مرة أخرى أو اختيار طريقة دفع مختلفة.</p>

            @if($transaction->notes)
            <div class="bg-red-50 rounded-xl p-4 text-sm text-right mb-6">
                <p class="text-red-700">{{ $transaction->notes }}</p>
            </div>
            @endif

            <!-- Order Details -->
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-right mb-6">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">رقم الطلب</span>
                        <span class="font-semibold">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">المبلغ</span>
                        <span class="font-bold">{{ number_format($order->total, 2) }} ر.س</span>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('customer.payment.checkout', $order) }}"
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold block transition">
                    إعادة المحاولة
                </a>
                <a href="{{ route('customer.orders.show', $order) }}"
                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold block transition">
                    عرض الطلب
                </a>
                <a href="{{ route('customer.dashboard') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm block mt-2">
                    العودة للوحة التحكم
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
