@extends('layouts.app')

@section('title', 'تم الدفع بنجاح')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">تم الدفع بنجاح!</h1>
            <p class="text-gray-600 mb-6">شكراً لك، تم استلام الدفع بنجاح وسيتم معالجة طلبك قريباً</p>

            <!-- Transaction Details -->
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-right mb-6">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">رقم الطلب</span>
                        <span class="font-semibold">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">رقم العملية</span>
                        <span class="font-semibold font-mono text-xs" dir="ltr">{{ $transaction->transaction_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">المبلغ</span>
                        <span class="font-bold text-green-600">{{ number_format($order->total, 2) }} ر.س</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">التاريخ</span>
                        <span class="font-semibold">{{ now()->format('Y/m/d H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('customer.orders.show', $order) }}"
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold block transition">
                    عرض تفاصيل الطلب
                </a>
                <a href="{{ route('customer.dashboard') }}"
                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold block transition">
                    العودة للوحة التحكم
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
