@extends('layouts.app')

@section('title', get_defined_vars()['template']->name ?? 'تفاصيل القالب')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-2 gap-8 mb-8">
        <div>
            <img src="{{ $template->preview_image }}" alt="{{ $template->name }}" class="w-full rounded-lg shadow-lg">
        </div>
        <div>
            <h1 class="text-3xl font-bold mb-4">{{ $template->name }}</h1>
            <p class="text-gray-600 mb-6">{{ $template->description }}</p>
            
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <p class="text-4xl font-bold text-blue-600">{{ $template->price }} ر.س</p>
            </div>

            @if($template->designer)
                <div class="mb-6 pb-6 border-b">
                    <p class="text-gray-600">المصمم</p>
                    <p class="text-lg font-semibold">{{ $template->designer->user->name }}</p>
                </div>
            @endif

            <div class="space-y-2 mb-6">
                <p><strong>الفئة:</strong> {{ $template->category }}</p>
                <p><strong>النوع:</strong> بطاقة احترافية</p>
            </div>

            @auth
                <a href="{{ route('customer.cards.create') }}?template_id={{ $template->id }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 w-full block text-center">
                    استخدم هذا القالب
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 w-full block text-center">
                    سجّل واستخدم هذا القالب
                </a>
            @endauth
        </div>
    </div>

    @if($relatedTemplates->count() > 0)
        <div class="mt-12 pt-8 border-t">
            <h3 class="text-2xl font-bold mb-6">قوالب مشابهة</h3>
            <div class="grid grid-cols-3 gap-6">
                @foreach($relatedTemplates as $related)
                    <div class="bg-white rounded-lg shadow">
                        <img src="{{ $related->preview_image }}" alt="{{ $related->name }}" class="w-full h-32 object-cover rounded-t-lg">
                        <div class="p-4">
                            <h4 class="font-bold">{{ $related->name }}</h4>
                            <p class="text-blue-600 font-bold">{{ $related->price }} ر.س</p>
                            <a href="{{ route('templates.show', $related) }}" class="text-blue-600 block mt-2">عرض أكثر</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
