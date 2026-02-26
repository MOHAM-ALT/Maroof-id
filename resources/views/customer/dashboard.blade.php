@extends('layouts.app')

@section('title', 'لوحة التحكم - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">مرحباً، {{ Auth::user()->name }}</h1>
                <p class="text-gray-600 mt-1">إدارة بطاقاتك الذكية ومتابعة أداءها</p>
            </div>
            <div class="flex items-center gap-3">
                @if($unreadNotifications > 0)
                <a href="{{ route('customer.notifications.index') }}" class="relative bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-semibold transition">
                    الإشعارات
                    <span class="absolute -top-2 -left-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">{{ $unreadNotifications }}</span>
                </a>
                @endif
                <a href="{{ route('customer.builder.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition">
                    + إنشاء بطاقة جديدة
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">إجمالي البطاقات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $cardsCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">بطاقات منشورة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $activeCardsCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">إجمالي المشاهدات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($viewsCount) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">إجمالي الطلبات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ordersCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Cards -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h2 class="font-bold text-gray-900">آخر البطاقات</h2>
                        <a href="{{ route('customer.cards.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">عرض الكل</a>
                    </div>

                    @if($recentCards->count() > 0)
                    <div class="divide-y">
                        @foreach($recentCards as $card)
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex-shrink-0">
                                @if($card->profile_image)
                                    <img src="{{ asset('storage/' . $card->profile_image) }}" alt="" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                        {{ mb_substr($card->full_name ?? $card->title, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $card->full_name ?? $card->title }}</h3>
                                <p class="text-gray-500 text-xs">
                                    {{ $card->job_title ?? '' }}
                                    @if($card->job_title && $card->company) - @endif
                                    {{ $card->company ?? '' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <span class="text-xs {{ $card->is_public ? 'text-green-600 bg-green-50' : 'text-gray-500 bg-gray-100' }} px-2 py-1 rounded-full">
                                    {{ $card->is_public ? 'منشورة' : 'مسودة' }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $card->views_count ?? 0 }} مشاهدة</span>
                                <a href="{{ route('customer.cards.show', $card) }}" class="text-blue-600 hover:text-blue-700 text-xs font-semibold">عرض</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12 px-6">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">لم تنشئ أي بطاقات بعد</h3>
                        <p class="text-gray-500 text-sm mb-4">ابدأ بإنشاء بطاقتك الرقمية الأولى</p>
                        <a href="{{ route('customer.builder.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition inline-block">
                            إنشاء بطاقة جديدة
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Recent Orders -->
                @if($recentOrders->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border mt-6">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h2 class="font-bold text-gray-900">آخر الطلبات</h2>
                        <a href="{{ route('customer.orders.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">عرض الكل</a>
                    </div>
                    <div class="divide-y">
                        @foreach($recentOrders as $order)
                        <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">طلب #{{ $order->id }}</p>
                                <p class="text-gray-500 text-xs">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-gray-900">{{ number_format($order->total, 2) }} ر.س</span>
                                <a href="{{ route('customer.orders.show', $order) }}" class="text-blue-600 hover:text-blue-700 text-xs font-semibold">تفاصيل</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="font-bold text-gray-900 mb-4">إجراءات سريعة</h2>
                    <div class="space-y-3">
                        <a href="{{ route('customer.builder.create') }}" class="flex items-center gap-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg px-4 py-3 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-sm font-semibold">إنشاء بطاقة (الاستوديو)</span>
                        </a>
                        <a href="{{ route('customer.cards.create') }}" class="flex items-center gap-3 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg px-4 py-3 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-sm font-semibold">إنشاء بطاقة (نموذج)</span>
                        </a>
                        <a href="{{ route('customer.analytics.index') }}" class="flex items-center gap-3 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg px-4 py-3 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span class="text-sm font-semibold">الإحصائيات</span>
                        </a>
                        <a href="{{ route('customer.profile.edit') }}" class="flex items-center gap-3 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg px-4 py-3 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="text-sm font-semibold">تعديل الملف الشخصي</span>
                        </a>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl shadow-sm p-6 text-white">
                    <h2 class="font-bold mb-2">نصيحة</h2>
                    <p class="text-blue-100 text-sm leading-relaxed">استخدم الاستوديو لتصميم بطاقات احترافية مع معاينة مباشرة وتحكم كامل بالألوان والخطوط والتخطيط.</p>
                    <a href="{{ route('customer.builder.create') }}" class="mt-3 inline-block bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        جرّب الاستوديو
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
