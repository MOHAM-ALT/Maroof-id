@extends('layouts.app')

@section('title', 'الإشعارات - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">الإشعارات</h1>
                @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                @if($unreadCount > 0)
                <p class="text-gray-600 mt-1">لديك <span class="font-semibold text-blue-600">{{ $unreadCount }}</span> إشعار غير مقروء</p>
                @endif
            </div>
            <div class="flex gap-2">
                @if($unreadCount > 0)
                <form method="POST" action="{{ route('customer.notifications.mark-all-as-read') }}">
                    @csrf
                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">تعليم الكل كمقروء</button>
                </form>
                @endif
                @if($notifications->count() > 0)
                <form method="POST" action="{{ route('customer.notifications.destroy-all') }}" onsubmit="return confirm('هل أنت متأكد من حذف جميع الإشعارات؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">حذف الكل</button>
                </form>
                @endif
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
        @endif

        <!-- Notifications List -->
        <div class="space-y-3">
            @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $type = $data['type'] ?? 'system';
                $typeConfig = match($type) {
                    'order_status' => ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'blue', 'label' => 'طلب'],
                    'payment' => ['icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'green', 'label' => 'دفع'],
                    'milestone' => ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'yellow', 'label' => 'إنجاز'],
                    default => ['icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'color' => 'gray', 'label' => 'نظام'],
                };
                $bgClass = !$notification->read_at ? 'bg-white border-r-4 border-r-blue-500' : 'bg-white';
            @endphp
            <div class="{{ $bgClass }} rounded-xl shadow-sm border p-5 hover:shadow-md transition">
                <div class="flex gap-4">
                    <!-- Icon -->
                    <div class="w-10 h-10 bg-{{ $typeConfig['color'] }}-100 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-{{ $typeConfig['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typeConfig['icon'] }}"/>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="font-semibold text-gray-900 {{ !$notification->read_at ? '' : 'text-gray-700' }}">
                                    {{ $data['title'] ?? 'إشعار' }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">{{ $data['message'] ?? '' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $typeConfig['color'] }}-100 text-{{ $typeConfig['color'] }}-700 shrink-0">
                                {{ $typeConfig['label'] }}
                            </span>
                        </div>

                        <div class="flex items-center gap-4 mt-3">
                            <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>

                            @if(!empty($data['url']))
                            <a href="{{ $data['url'] }}" class="text-xs text-blue-600 hover:text-blue-800">عرض التفاصيل</a>
                            @endif

                            @if(!$notification->read_at)
                            <form method="POST" action="{{ route('customer.notifications.mark-as-read', $notification->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-gray-500 hover:text-blue-600">تعليم كمقروء</button>
                            </form>
                            @endif

                            <form method="POST" action="{{ route('customer.notifications.destroy', $notification->id) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-gray-400 hover:text-red-600">حذف</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">لا توجد إشعارات</h3>
                <p class="text-gray-500">ستظهر هنا الإشعارات عند وجود تحديثات جديدة</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
