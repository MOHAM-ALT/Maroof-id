@extends('layouts.app')

@section('title', 'لوحة المصمم - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8 flex flex-wrap justify-between items-start gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">مرحباً، {{ Auth::user()->name }}</h1>
                <p class="text-gray-600 mt-1">{{ $designer->bio ?? 'بوابة المصمم' }}</p>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-xs px-3 py-1 rounded-full {{ $designer->status->value === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $designer->status->value === 'active' ? 'نشط' : 'غير نشط' }}
                    </span>
                    @if($designer->rating > 0)
                    <span class="text-yellow-500 text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $designer->rating ? '★' : '☆' }}
                        @endfor
                        <span class="text-gray-500 mr-1">({{ $designer->rating }})</span>
                    </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('designer.templates.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-lg font-semibold transition">
                + رفع قالب جديد
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">إجمالي القوالب</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $totalTemplates }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">القوالب النشطة</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $activeTemplates }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">مميزة</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $featuredCount }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">مرات الاستخدام</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $totalUsage }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">الأرباح</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalEarnings, 0) }} <span class="text-xs">ر.س</span></p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <p class="text-gray-500 text-xs">التقييم</p>
                <p class="text-2xl font-bold text-yellow-500 mt-1">{{ $designer->rating ?? '-' }} ★</p>
            </div>
        </div>

        <!-- Recent Templates -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">آخر القوالب</h2>
                <a href="{{ route('designer.templates.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-semibold">عرض الكل</a>
            </div>

            @if($recentTemplates->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recentTemplates as $template)
                <div class="border rounded-lg overflow-hidden hover:shadow-md transition">
                    <div class="h-32 bg-gradient-to-br from-purple-100 to-blue-100 flex items-center justify-center">
                        @if($template->preview_image)
                            <img src="{{ asset('storage/' . $template->preview_image) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl">🎨</span>
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-gray-900 text-sm">{{ $template->name_ar }}</h3>
                        <p class="text-gray-500 text-xs">{{ $template->name_en }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs {{ $template->is_active ? 'text-green-600' : 'text-orange-500' }}">
                                {{ $template->is_active ? 'منشور' : 'قيد المراجعة' }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $template->usage_count }} استخدام</span>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-sm font-bold text-purple-600">{{ $template->price > 0 ? number_format($template->price, 0) . ' ر.س' : 'مجاني' }}</span>
                            <a href="{{ route('designer.templates.edit', $template) }}" class="text-xs text-blue-600 hover:text-blue-700">تعديل</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <p class="text-4xl mb-3">🎨</p>
                <h3 class="text-lg font-bold text-gray-900 mb-2">لم تقم برفع أي قوالب بعد</h3>
                <p class="text-gray-500 mb-4">ابدأ برفع أول قالب لك</p>
                <a href="{{ route('designer.templates.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-lg font-semibold transition inline-block">رفع قالب جديد</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
