@extends('layouts.app')

@section('title', 'قوالبي - معروف')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">قوالبي</h1>
                <p class="text-gray-600 mt-1">إدارة القوالب التي قمت بتصميمها</p>
            </div>
            <a href="{{ route('designer.templates.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-lg font-semibold transition">
                + رفع قالب جديد
            </a>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border p-4 text-center">
                <p class="text-2xl font-bold text-purple-600">{{ $templates->total() }}</p>
                <p class="text-gray-500 text-xs">إجمالي القوالب</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ $designer->templates()->where('is_active', true)->count() }}</p>
                <p class="text-gray-500 text-xs">نشطة</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border p-4 text-center">
                <p class="text-2xl font-bold text-orange-500">{{ $designer->templates()->where('is_active', false)->count() }}</p>
                <p class="text-gray-500 text-xs">قيد المراجعة</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif

        <!-- Templates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($templates as $template)
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-md transition">
                <div class="h-36 bg-gradient-to-br from-purple-100 to-blue-100 flex items-center justify-center relative">
                    @if($template->preview_image)
                        <img src="{{ asset('storage/' . $template->preview_image) }}" alt="" class="w-full h-full object-cover">
                    @else
                        <span class="text-5xl">🎨</span>
                    @endif
                    <div class="absolute top-2 left-2 flex gap-1">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $template->is_active ? 'bg-green-500 text-white' : 'bg-orange-500 text-white' }}">
                            {{ $template->is_active ? 'منشور' : 'قيد المراجعة' }}
                        </span>
                        @if($template->is_featured)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-500 text-white">مميز</span>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900">{{ $template->name_ar }}</h3>
                    <p class="text-gray-500 text-xs">{{ $template->name_en }}</p>
                    @if($template->category)
                        <span class="text-xs text-purple-600 bg-purple-50 px-2 py-0.5 rounded mt-1 inline-block">{{ $template->category->name_ar }}</span>
                    @endif
                    <div class="flex items-center justify-between mt-3">
                        <span class="font-bold text-purple-600">{{ $template->price > 0 ? number_format($template->price, 0) . ' ر.س' : 'مجاني' }}</span>
                        <span class="text-xs text-gray-500">{{ $template->usage_count }} استخدام</span>
                    </div>
                    <div class="mt-3 pt-3 border-t">
                        <a href="{{ route('designer.templates.edit', $template) }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">تعديل</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-16">
                <p class="text-5xl mb-4">🎨</p>
                <h3 class="text-xl font-bold text-gray-900 mb-2">لم تقم برفع أي قوالب بعد</h3>
                <a href="{{ route('designer.templates.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-lg font-semibold transition inline-block mt-4">رفع قالب جديد</a>
            </div>
            @endforelse
        </div>

        @if($templates->hasPages())
        <div class="mt-8">{{ $templates->links() }}</div>
        @endif
    </div>
</div>
@endsection
