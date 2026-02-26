@extends('layouts.app')

@section('title', 'بطاقاتي')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">بطاقاتي</h1>
                <p class="text-gray-600 mt-1">إدارة بطاقاتك الرقمية</p>
            </div>
            <a href="{{ route('customer.cards.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold transition">
                + إنشاء بطاقة جديدة
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($cards as $card)
                <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition overflow-hidden">
                    <div class="h-24 bg-gradient-to-br from-blue-500 to-blue-700 relative">
                        @if($card->cover_image)
                            <img src="{{ asset('storage/' . $card->cover_image) }}" alt="" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute -bottom-8 right-4">
                            @if($card->profile_image)
                                <img src="{{ asset('storage/' . $card->profile_image) }}" alt=""
                                     class="w-16 h-16 rounded-full border-3 border-white object-cover shadow">
                            @else
                                <div class="w-16 h-16 rounded-full border-3 border-white bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl shadow">
                                    {{ mb_substr($card->full_name ?? $card->title, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="absolute top-2 left-2 flex gap-1">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $card->is_public ? 'bg-green-500 text-white' : 'bg-gray-700 text-white' }}">
                                {{ $card->is_public ? 'منشورة' : 'مسودة' }}
                            </span>
                            @if($card->isPasswordProtected())
                            <span class="text-xs px-2 py-0.5 rounded-full bg-amber-500 text-white" title="محمية بكلمة مرور">
                                محمية
                            </span>
                            @endif
                            @if($card->isExpired())
                            <span class="text-xs px-2 py-0.5 rounded-full bg-red-500 text-white" title="منتهية الصلاحية">
                                منتهية
                            </span>
                            @elseif($card->expires_at)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-blue-500 text-white" title="تنتهي {{ $card->expires_at->format('d/m/Y') }}">
                                {{ $card->expires_at->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="pt-10 px-4 pb-4">
                        <h3 class="font-bold text-gray-900">{{ $card->full_name ?? $card->title }}</h3>
                        @if($card->job_title)
                            <p class="text-blue-600 text-sm">{{ $card->job_title }}</p>
                        @endif
                        @if($card->company)
                            <p class="text-gray-500 text-xs">{{ $card->company }}</p>
                        @endif

                        <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                            <span>{{ $card->views_count ?? 0 }} مشاهدة</span>
                            @if($card->template)
                                <span>{{ $card->template->name }}</span>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t flex items-center gap-2 flex-wrap">
                            <a href="{{ route('customer.cards.show', $card) }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">عرض</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('customer.builder.edit', $card) }}" class="text-orange-600 hover:text-orange-700 text-sm font-semibold">تعديل</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('customer.cards.share', $card) }}" class="text-purple-600 hover:text-purple-700 text-sm font-semibold">مشاركة</a>
                            <span class="text-gray-300">|</span>
                            <form method="POST" action="{{ route('customer.cards.duplicate', $card) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-teal-600 hover:text-teal-700 text-sm font-semibold">نسخ</button>
                            </form>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('customer.orders.create') }}?card_id={{ $card->id }}" class="text-green-600 hover:text-green-700 text-sm font-semibold">طلب طباعة</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">💳</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">لم تقم بإنشاء أي بطاقات بعد</h3>
                    <p class="text-gray-500 mb-6">أنشئ بطاقتك الرقمية الأولى الآن</p>
                    <a href="{{ route('customer.cards.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold transition inline-block">
                        إنشاء بطاقة جديدة
                    </a>
                </div>
            @endforelse
        </div>

        @if($cards->hasPages())
        <div class="mt-8">
            {{ $cards->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
