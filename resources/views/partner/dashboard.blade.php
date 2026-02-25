@extends('layouts.app')

@section('title', 'معروف - لوحة تحكم الشريك')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Welcome Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">مرحباً، {{ $partner->name }}</h1>
                <p class="text-gray-500 mt-1">لوحة تحكم شريك الطباعة</p>
            </div>
            <div class="flex items-center gap-3">
                @if($partner->status->value === 'active')
                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-4 py-2 rounded-full text-sm font-semibold border border-green-200">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        نشط
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-4 py-2 rounded-full text-sm font-semibold border border-red-200">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        معطل
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Total Orders --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalOrders }}</p>
            <p class="text-sm text-gray-500 mt-1">إجمالي الطلبات</p>
        </div>

        {{-- Pending Orders --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $pendingOrders }}</p>
            <p class="text-sm text-gray-500 mt-1">طلبات معلقة</p>
        </div>

        {{-- Processing Orders --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $processingOrders }}</p>
            <p class="text-sm text-gray-500 mt-1">قيد المعالجة</p>
        </div>

        {{-- Completed Orders --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $completedOrders }}</p>
            <p class="text-sm text-gray-500 mt-1">طلبات مكتملة</p>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalRevenue, 2) }} <span class="text-lg text-gray-400">ر.س</span></p>
            <p class="text-sm text-gray-500 mt-1">إجمالي الإيرادات</p>
        </div>

        {{-- Commission --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($commission, 2) }} <span class="text-lg text-gray-400">ر.س</span></p>
            <p class="text-sm text-gray-500 mt-1">العمولة المستحقة ({{ $partner->commission_rate }}%)</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">آخر الطلبات</h2>
        <a href="{{ route('partner.orders.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-semibold transition">
            عرض جميع الطلبات
            <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- Recent Orders Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($recentOrders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-right px-6 py-4 font-semibold text-gray-600">رقم الطلب</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-600">العميل</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-600">المدينة</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-600">النوع</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-600">الكمية</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-600">المجموع</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-600">الحالة</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-600">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <a href="{{ route('partner.orders.show', $order) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $order->user?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $order->shipping_city ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $order->type?->label() ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $order->quantity }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ number_format($order->total, 2) }} ر.س</td>
                        <td class="px-6 py-4">
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
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold border {{ $colorClass }}">
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('Y/m/d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-gray-500 text-lg">لا توجد طلبات حتى الآن</p>
        </div>
        @endif
    </div>
</div>
@endsection
