@extends('layouts.app')

@section('title', 'لوحة تحكم الموزع - معروف')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">مرحبا، {{ $reseller->store_name }}</h1>
                <p class="text-gray-500 mt-1">{{ $reseller->city }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $reseller->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $reseller->status->value === 'active' ? 'نشط' : 'غير نشط' }}
                </span>
                <span class="text-sm text-gray-500">نسبة العمولة: {{ $reseller->commission_rate }}%</span>
            </div>
        </div>
    </div>

    {{-- Stock Alert --}}
    @if($currentStock <= $reseller->stock_alert_level)
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center gap-3">
        <svg class="w-6 h-6 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <div>
            <p class="font-semibold text-amber-800">تنبيه المخزون</p>
            <p class="text-amber-700 text-sm">المخزون الحالي ({{ $currentStock }} بطاقة) اقل من حد التنبيه ({{ $reseller->stock_alert_level }} بطاقة). يرجى طلب اعادة تعبئة المخزون.</p>
        </div>
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Total Sales --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">اجمالي المبيعات</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalSales) }}</p>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">اجمالي الايرادات</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenue, 2) }} <span class="text-sm font-normal text-gray-500">ر.س</span></p>
        </div>

        {{-- Total Commission --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">اجمالي العمولة</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalCommission, 2) }} <span class="text-sm font-normal text-gray-500">ر.س</span></p>
        </div>

        {{-- Current Stock --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">المخزون الحالي</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($currentStock) }} <span class="text-sm font-normal text-gray-500">بطاقة</span></p>
        </div>

        {{-- Monthly Sales --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">مبيعات الشهر</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($monthlySales, 2) }} <span class="text-sm font-normal text-gray-500">ر.س</span></p>
        </div>

        {{-- Monthly Commission --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">عمولة الشهر</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($monthlyCommission, 2) }} <span class="text-sm font-normal text-gray-500">ر.س</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Recent Sales --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">اخر المبيعات</h2>
                    <a href="{{ route('reseller.sales.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">عرض الكل</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الكمية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العمولة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentSales as $sale)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $sale->sale_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($sale->quantity) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($sale->amount, 2) }} ر.س</td>
                                <td class="px-6 py-4 text-sm font-medium text-green-600">{{ number_format($sale->commission_earned, 2) }} ر.س</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">لا توجد مبيعات حتى الان</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Inventory Status --}}
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">حالة المخزون</h2>
                </div>
                <div class="p-6">
                    @forelse($inventory as $item)
                    <div class="mb-4 last:mb-0">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">البطاقات</span>
                            <span class="text-sm font-bold {{ $item->card_quantity <= $item->stock_alert_level ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($item->card_quantity) }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            @php
                                $percentage = $item->stock_alert_level > 0 ? min(($item->card_quantity / ($item->stock_alert_level * 3)) * 100, 100) : 100;
                            @endphp
                            <div class="h-2.5 rounded-full {{ $item->card_quantity <= $item->stock_alert_level ? 'bg-red-500' : 'bg-green-500' }}"
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-400">حد التنبيه: {{ $item->stock_alert_level }}</span>
                            @if($item->last_restocked_at)
                            <span class="text-xs text-gray-400">اخر تعبئة: {{ $item->last_restocked_at->format('Y-m-d') }}</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-400 py-4">لا يوجد مخزون مسجل</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
