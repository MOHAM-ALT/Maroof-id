@extends('layouts.app')

@section('title', 'معروف - طلبات الشريك')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">الطلبات</h1>
            <p class="text-gray-500 mt-1">إدارة جميع طلبات الطباعة</p>
        </div>
        <a href="{{ route('partner.dashboard') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            العودة للوحة التحكم
        </a>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2">
        <a href="{{ route('partner.orders.index') }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ !request('status') ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            الكل
        </a>
        <a href="{{ route('partner.orders.index', ['status' => 'pending']) }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ request('status') === 'pending' ? 'bg-yellow-500 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            معلق
        </a>
        <a href="{{ route('partner.orders.index', ['status' => 'confirmed']) }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ request('status') === 'confirmed' ? 'bg-sky-500 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            مؤكد
        </a>
        <a href="{{ route('partner.orders.index', ['status' => 'processing']) }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ request('status') === 'processing' ? 'bg-blue-500 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            قيد المعالجة
        </a>
        <a href="{{ route('partner.orders.index', ['status' => 'completed']) }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ request('status') === 'completed' ? 'bg-green-500 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            مكتمل
        </a>
        <a href="{{ route('partner.orders.index', ['status' => 'cancelled']) }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ request('status') === 'cancelled' ? 'bg-red-500 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            ملغي
        </a>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($orders->count() > 0)
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
                        <th class="text-right px-6 py-4 font-semibold text-gray-600">الدفع</th>
                        <th class="text-right px-6 py-4 font-semibold text-gray-600">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($orders as $order)
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
                        <td class="px-6 py-4">
                            @php
                                $paymentColors = [
                                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'paid' => 'bg-green-50 text-green-700 border-green-200',
                                    'failed' => 'bg-red-50 text-red-700 border-red-200',
                                    'refunded' => 'bg-sky-50 text-sky-700 border-sky-200',
                                ];
                                $paymentColorClass = $paymentColors[$order->payment_status->value] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold border {{ $paymentColorClass }}">
                                {{ $order->payment_status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('partner.orders.show', $order) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-sm font-medium transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                عرض
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $orders->withQueryString()->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-gray-500 text-lg">لا توجد طلبات</p>
            @if(request('status'))
                <a href="{{ route('partner.orders.index') }}" class="text-blue-600 hover:text-blue-700 text-sm mt-2 inline-block">عرض جميع الطلبات</a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
