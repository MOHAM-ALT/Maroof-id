@extends('layouts.app')

@section('title', 'المدفوعات والعمولات')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">المدفوعات والعمولات</h1>
            <p class="text-gray-500 mt-1">تتبع أرباحك ومدفوعاتك</p>
        </div>
        <a href="{{ route($activeRole === 'print_partner' ? 'partner.dashboard' : ($activeRole === 'reseller' ? 'reseller.dashboard' : ($activeRole === 'designer' ? 'designer.dashboard' : ($activeRole === 'affiliate' ? 'affiliate.dashboard' : 'customer.dashboard')))) }}"
           class="text-indigo-600 hover:text-indigo-800 text-sm">
            &rarr; العودة للوحة التحكم
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-sm text-gray-500 mb-1">إجمالي الأرباح</div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($history['total_amount'], 2) }} <span class="text-sm font-normal">ر.س</span></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-sm text-gray-500 mb-1">قيد الانتظار</div>
            <div class="text-2xl font-bold text-amber-600">{{ number_format($history['pending_amount'], 2) }} <span class="text-sm font-normal">ر.س</span></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-sm text-gray-500 mb-1">تم الدفع</div>
            <div class="text-2xl font-bold text-green-600">{{ number_format($history['paid_amount'], 2) }} <span class="text-sm font-normal">ر.س</span></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-sm text-gray-500 mb-1">عدد المعاملات</div>
            <div class="text-2xl font-bold text-indigo-600">{{ $history['total_payouts'] }}</div>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="bg-white rounded-xl shadow-sm border mb-6">
        <div class="p-4 border-b flex items-center gap-4">
            <span class="text-sm text-gray-500">الفترة:</span>
            <a href="{{ request()->fullUrlWithQuery(['period' => 'all']) }}"
               class="px-3 py-1 rounded-lg text-sm {{ $period === 'all' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                الكل
            </a>
            <a href="{{ request()->fullUrlWithQuery(['period' => 'month']) }}"
               class="px-3 py-1 rounded-lg text-sm {{ $period === 'month' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                هذا الشهر
            </a>
            <a href="{{ request()->fullUrlWithQuery(['period' => 'year']) }}"
               class="px-3 py-1 rounded-lg text-sm {{ $period === 'year' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                هذه السنة
            </a>
        </div>
    </div>

    <!-- Payouts Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">المرجع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">المبلغ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">الطريقة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">ملاحظات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($history['payouts'] as $payout)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-mono text-gray-700">{{ $payout->reference_number ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ number_format($payout->amount, 2) }} ر.س</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @switch($payout->method)
                            @case('bank_transfer') تحويل بنكي @break
                            @case('stc_pay') STC Pay @break
                            @default {{ $payout->method }}
                        @endswitch
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusEnum = $payout->status;
                            $statusValue = is_object($statusEnum) ? $statusEnum->value : $statusEnum;
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $statusValue === 'completed' ? 'bg-green-100 text-green-700' :
                               ($statusValue === 'pending' ? 'bg-amber-100 text-amber-700' :
                               ($statusValue === 'processing' ? 'bg-blue-100 text-blue-700' :
                               'bg-red-100 text-red-700')) }}">
                            @switch($statusValue)
                                @case('pending') قيد الانتظار @break
                                @case('processing') قيد المعالجة @break
                                @case('completed') مكتمل @break
                                @case('failed') فشل @break
                                @default {{ $statusValue }}
                            @endswitch
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $payout->paid_at ? $payout->paid_at->format('Y-m-d') : $payout->created_at->format('Y-m-d') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $payout->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>لا توجد مدفوعات حتى الآن</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
