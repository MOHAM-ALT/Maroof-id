@extends('layouts.app')

@section('title', 'طرق الدفع - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">طرق الدفع</h1>
                <p class="text-gray-600 mt-1">طرق الدفع المتاحة في المنصة</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; لوحة التحكم</a>
        </div>

        <!-- Payment Methods -->
        <div class="space-y-4">
            @forelse($methods ?? [] as $key => $method)
            <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center gap-6">
                <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
                    @if($key === 'tap_sa' || (is_array($method) && ($method['key'] ?? '') === 'tap_sa'))
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    @elseif($key === 'stripe' || (is_array($method) && ($method['key'] ?? '') === 'stripe'))
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    @elseif($key === 'wallet' || (is_array($method) && ($method['key'] ?? '') === 'wallet'))
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @else
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">
                        @if(is_array($method))
                            {{ $method['name'] ?? $method['label'] ?? $key }}
                        @else
                            {{ $method }}
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($key === 'tap_sa')
                            بطاقات الائتمان والخصم (Visa, Mastercard, mada)
                        @elseif($key === 'stripe')
                            بطاقات دولية وApple Pay
                        @elseif($key === 'wallet')
                            الدفع من رصيد المحفظة الرقمية
                        @elseif($key === 'bank_transfer')
                            تحويل بنكي مباشر
                        @else
                            طريقة دفع متاحة
                        @endif
                    </p>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">متاح</span>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">لا توجد طرق دفع</h3>
                <p class="text-gray-500">سيتم إضافة طرق الدفع قريباً</p>
            </div>
            @endforelse
        </div>

        <!-- Payment Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">معلومات هامة</h3>
            <ul class="text-sm text-blue-800 space-y-2">
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    جميع المعاملات المالية مشفرة ومحمية بأعلى معايير الأمان (PCI DSS)
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    يتم معالجة المدفوعات فورياً والتأكيد خلال ثوانٍ
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    يمكن استرجاع المبلغ خلال 7 أيام من الشراء
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
