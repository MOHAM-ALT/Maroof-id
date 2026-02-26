<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'معروف')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-600">معروف</a>

                @auth
                @php $activeRole = session('active_role', Auth::user()->roles->first()?->name ?? 'customer'); @endphp

                @if($activeRole === 'print_partner')
                <div class="hidden md:flex items-center gap-4 text-sm">
                    <a href="{{ route('partner.dashboard') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('partner.dashboard') ? 'text-blue-600 font-semibold' : '' }}">لوحة التحكم</a>
                    <a href="{{ route('partner.orders.index') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('partner.orders.*') ? 'text-blue-600 font-semibold' : '' }}">الطلبات</a>
                </div>
                @elseif($activeRole === 'reseller')
                <div class="hidden md:flex items-center gap-4 text-sm">
                    <a href="{{ route('reseller.dashboard') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('reseller.dashboard') ? 'text-blue-600 font-semibold' : '' }}">لوحة التحكم</a>
                    <a href="{{ route('reseller.sales.index') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('reseller.sales.*') ? 'text-blue-600 font-semibold' : '' }}">المبيعات</a>
                </div>
                @elseif($activeRole === 'designer')
                <div class="hidden md:flex items-center gap-4 text-sm">
                    <a href="{{ route('designer.dashboard') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('designer.dashboard') ? 'text-blue-600 font-semibold' : '' }}">لوحة التحكم</a>
                    <a href="{{ route('designer.templates.index') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('designer.templates.*') ? 'text-blue-600 font-semibold' : '' }}">قوالبي</a>
                    <a href="{{ route('designer.templates.create') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('designer.templates.create') ? 'text-blue-600 font-semibold' : '' }}">رفع قالب</a>
                </div>
                @elseif($activeRole === 'affiliate')
                <div class="hidden md:flex items-center gap-4 text-sm">
                    <a href="{{ route('affiliate.dashboard') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('affiliate.dashboard') ? 'text-blue-600 font-semibold' : '' }}">لوحة التحكم</a>
                    <a href="{{ route('affiliate.clicks.index') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('affiliate.clicks.*') ? 'text-blue-600 font-semibold' : '' }}">النقرات</a>
                </div>
                @else
                <div class="hidden md:flex items-center gap-4 text-sm">
                    <a href="{{ route('customer.dashboard') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('customer.dashboard') ? 'text-blue-600 font-semibold' : '' }}">الرئيسية</a>
                    <a href="{{ route('customer.cards.index') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('customer.cards.*') ? 'text-blue-600 font-semibold' : '' }}">بطاقاتي</a>
                    <a href="{{ route('customer.builder.create') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('customer.builder.*') ? 'text-blue-600 font-semibold' : '' }}">استوديو التصميم</a>
                    <a href="{{ route('customer.orders.index') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('customer.orders.*') ? 'text-blue-600 font-semibold' : '' }}">طلباتي</a>
                    <a href="{{ route('customer.brand-kit.index') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('customer.brand-kit.*') ? 'text-blue-600 font-semibold' : '' }}">الهوية</a>
                    <a href="{{ route('templates.index') }}" class="text-gray-600 hover:text-blue-600 transition {{ request()->routeIs('templates.*') ? 'text-blue-600 font-semibold' : '' }}">القوالب</a>
                </div>
                @endif
                @else
                <div class="hidden md:flex items-center gap-4 text-sm">
                    <a href="{{ route('templates.index') }}" class="text-gray-600 hover:text-blue-600 transition">القوالب</a>
                    <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-blue-600 transition">الأسعار</a>
                    <a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-600 transition">من نحن</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-blue-600 transition">تواصل معنا</a>
                </div>
                @endauth
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <!-- Notifications Bell -->
                    <a href="{{ route('customer.notifications.index') }}" class="relative text-gray-600 hover:text-blue-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @php $unreadNotifications = auth()->user()->notifications()->whereNull('read_at')->count(); @endphp
                        @if($unreadNotifications > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">{{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}</span>
                        @endif
                    </a>

                    @include('components.role-selector')

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 transition text-sm">
                            خروج
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 text-sm">دخول</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">تسجيل</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-4">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">{{ session('error') }}</div>
        </div>
    @endif

    @yield('content')
</body>
</html>
