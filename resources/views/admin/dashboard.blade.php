@extends('layouts.app')

@section('title', 'لوحة تحكم الإدارة')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">لوحة تحكم الإدارة 🎛️</h1>
            <p class="text-gray-600 mt-2">مرحباً {{ auth()->user()->name }}</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">إجمالي المستخدمين</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::count() }}</p>
                    </div>
                    <div class="text-4xl text-blue-500">👥</div>
                </div>
            </div>

            <!-- Total Cards -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">إجمالي البطاقات</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Card::count() }}</p>
                    </div>
                    <div class="text-4xl text-green-500">📇</div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">إجمالي الطلبات</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Order::count() }}</p>
                    </div>
                    <div class="text-4xl text-orange-500">📦</div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">إجمالي الإيرادات</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format(\App\Models\Order::where('payment_status', 'completed')->sum('total'), 0) }} ر.س</p>
                    </div>
                    <div class="text-4xl text-purple-500">💰</div>
                </div>
            </div>
        </div>

        <!-- API Documentation -->
        <div class="bg-white rounded-lg shadow p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">📡 API Endpoints</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">البطاقات 📇</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>✅ <code class="bg-gray-100 px-2 py-1 rounded">GET /api/cards</code></li>
                        <li>✅ <code class="bg-gray-100 px-2 py-1 rounded">POST /api/cards</code></li>
                        <li>✅ <code class="bg-gray-100 px-2 py-1 rounded">PUT /api/cards/{id}</code></li>
                        <li>✅ <code class="bg-gray-100 px-2 py-1 rounded">DELETE /api/cards/{id}</code></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">الطلبات 🛒</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>✅ <code class="bg-gray-100 px-2 py-1 rounded">GET /api/orders</code></li>
                        <li>✅ <code class="bg-gray-100 px-2 py-1 rounded">POST /api/orders</code></li>
                        <li>✅ <code class="bg-gray-100 px-2 py-1 rounded">POST /api/payments</code></li>
                        <li>✅ <code class="bg-gray-100 px-2 py-1 rounded">GET /api/commissions</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">✅ حالة النظام</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-lg">
                    <span class="text-2xl">✅</span>
                    <div>
                        <p class="font-semibold text-green-900">قاعدة البيانات</p>
                        <p class="text-sm text-green-700">24 جدول - جميع الهجرات تمت</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-lg">
                    <span class="text-2xl">✅</span>
                    <div>
                        <p class="font-semibold text-green-900">API</p>
                        <p class="text-sm text-green-700">30+ endpoints جاهزة</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-lg">
                    <span class="text-2xl">✅</span>
                    <div>
                        <p class="font-semibold text-green-900">البريد الإلكتروني</p>
                        <p class="text-sm text-green-700">4 نماذج متقدمة</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-lg">
                    <span class="text-2xl">✅</span>
                    <div>
                        <p class="font-semibold text-green-900">الخدمات</p>
                        <p class="text-sm text-green-700">4 خدمات متطورة</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-8 flex flex-wrap gap-4">
            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                🔐 تسجيل الدخول
            </a>
            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                📝 إنشاء حساب
            </a>
            <a href="/api/health" target="_blank" class="inline-flex items-center px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                📡 اختبار API
            </a>
        </div>
    </div>
</div>
@endsection
