@extends('layouts.app')
@section('title', 'هوية العلامة التجارية')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">هوية العلامة التجارية</h1>
            <p class="text-gray-500 mt-1">إدارة ألوان وشعارات وخطوط علامتك التجارية</p>
        </div>
        <a href="{{ route('customer.brand-kit.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
            + إنشاء هوية جديدة
        </a>
    </div>

    @if($brandKits->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">لا توجد هويات بصرية</h3>
        <p class="text-gray-500 mb-6">أنشئ هوية علامتك التجارية لتطبيقها على جميع بطاقاتك</p>
        <a href="{{ route('customer.brand-kit.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">إنشاء هوية جديدة</a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($brandKits as $kit)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition">
            <!-- Color Preview Bar -->
            <div class="h-3 flex">
                <div class="flex-1" style="background-color: {{ $kit->primary_color }}"></div>
                <div class="flex-1" style="background-color: {{ $kit->secondary_color }}"></div>
                <div class="flex-1" style="background-color: {{ $kit->accent_color }}"></div>
            </div>

            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">{{ $kit->name }}</h3>
                        @if($kit->is_default)
                        <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full font-semibold">الافتراضية</span>
                        @endif
                    </div>
                    @if($kit->logo_path)
                    <img src="{{ asset('storage/' . $kit->logo_path) }}" alt="Logo" class="w-12 h-12 rounded-lg object-contain border">
                    @endif
                </div>

                <!-- Colors -->
                <div class="flex gap-2 mb-4">
                    <div class="w-8 h-8 rounded-full border-2 border-white shadow" style="background-color: {{ $kit->primary_color }}" title="أساسي"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white shadow" style="background-color: {{ $kit->secondary_color }}" title="ثانوي"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white shadow" style="background-color: {{ $kit->accent_color }}" title="تمييز"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white shadow" style="background-color: {{ $kit->text_color }}" title="نص"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-gray-200 shadow" style="background-color: {{ $kit->background_color }}" title="خلفية"></div>
                </div>

                <!-- Font -->
                <p class="text-sm text-gray-500 mb-4">
                    <span class="font-semibold">الخط:</span> {{ $kit->font_family }}
                    @if($kit->default_company)
                    | <span class="font-semibold">الشركة:</span> {{ $kit->default_company }}
                    @endif
                </p>

                <!-- Actions -->
                <div class="flex gap-2">
                    <a href="{{ route('customer.brand-kit.edit', $kit) }}" class="flex-1 text-center bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-semibold">تعديل</a>
                    <form method="POST" action="{{ route('customer.brand-kit.destroy', $kit) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الهوية؟')" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition text-sm font-semibold">حذف</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
