@extends('layouts.app')

@section('title', $card->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
            <div>
                <a href="{{ route('customer.cards.index') }}" class="text-blue-600 hover:text-blue-700 text-sm mb-2 inline-block">→ العودة للبطاقات</a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $card->title }}</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $card->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $card->is_active ? 'مفعّلة' : 'معطّلة' }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $card->is_public ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $card->is_public ? 'منشورة' : 'مسودة' }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.cards.edit', $card) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition">
                    تعديل البطاقة
                </a>
                <a href="{{ route('customer.cards.share', $card) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition">
                    مشاركة
                </a>
                <form method="POST" action="{{ route('customer.cards.duplicate', $card) }}" class="inline">
                    @csrf
                    <button type="submit" class="border-2 border-gray-300 text-gray-600 hover:bg-gray-50 px-5 py-2 rounded-lg font-semibold text-sm transition">
                        نسخ البطاقة
                    </button>
                </form>
                <form method="POST" action="{{ route('customer.cards.toggle-publish', $card) }}" class="inline">
                    @csrf
                    <button type="submit" class="border-2 {{ $card->is_public ? 'border-orange-500 text-orange-600 hover:bg-orange-50' : 'border-green-500 text-green-600 hover:bg-green-50' }} px-5 py-2 rounded-lg font-semibold text-sm transition">
                        {{ $card->is_public ? 'إلغاء النشر' : 'نشر البطاقة' }}
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Card Preview -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <!-- Cover -->
                    <div class="h-40 bg-gradient-to-br from-blue-500 to-blue-700 relative">
                        @if($card->cover_image)
                            <img src="{{ asset('storage/' . $card->cover_image) }}" alt="غلاف" class="w-full h-full object-cover">
                        @endif

                        <!-- Profile Image -->
                        <div class="absolute -bottom-12 right-6">
                            @if($card->profile_image)
                                <img src="{{ asset('storage/' . $card->profile_image) }}" alt="{{ $card->full_name }}"
                                     class="w-24 h-24 rounded-full border-4 border-white object-cover shadow-lg">
                            @else
                                <div class="w-24 h-24 rounded-full border-4 border-white bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold shadow-lg">
                                    {{ mb_substr($card->full_name ?? $card->title, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-16 px-6 pb-6">
                        <!-- Name & Title -->
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $card->full_name ?? $card->title }}</h2>
                            @if($card->job_title)
                                <p class="text-blue-600 font-medium mt-1">{{ $card->job_title }}</p>
                            @endif
                            @if($card->company)
                                <p class="text-gray-500 text-sm">{{ $card->company }}</p>
                            @endif
                            @if($card->logo)
                                <img src="{{ asset('storage/' . $card->logo) }}" alt="Logo" class="h-8 mt-2 object-contain">
                            @endif
                        </div>

                        <!-- Bio -->
                        @if($card->bio)
                        <div class="mb-6 bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $card->bio }}</p>
                        </div>
                        @endif

                        <!-- Contact Info -->
                        <div class="mb-6">
                            <h3 class="font-bold text-gray-900 mb-3">معلومات التواصل</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @if($card->email)
                                <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-3">
                                    <span class="text-gray-400 text-lg">✉</span>
                                    <div>
                                        <p class="text-gray-500 text-xs">البريد الإلكتروني</p>
                                        <p class="text-gray-900 text-sm font-medium" dir="ltr">{{ $card->email }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($card->phone)
                                <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-3">
                                    <span class="text-gray-400 text-lg">📱</span>
                                    <div>
                                        <p class="text-gray-500 text-xs">الجوال</p>
                                        <p class="text-gray-900 text-sm font-medium" dir="ltr">{{ $card->phone }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($card->whatsapp)
                                <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-3">
                                    <span class="text-gray-400 text-lg">💬</span>
                                    <div>
                                        <p class="text-gray-500 text-xs">واتساب</p>
                                        <p class="text-gray-900 text-sm font-medium" dir="ltr">{{ $card->whatsapp }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($card->website)
                                <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-3">
                                    <span class="text-gray-400 text-lg">🌐</span>
                                    <div>
                                        <p class="text-gray-500 text-xs">الموقع</p>
                                        <p class="text-gray-900 text-sm font-medium" dir="ltr">{{ $card->website }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($card->address)
                                <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-3 md:col-span-2">
                                    <span class="text-gray-400 text-lg">📍</span>
                                    <div>
                                        <p class="text-gray-500 text-xs">العنوان</p>
                                        <p class="text-gray-900 text-sm font-medium">{{ $card->address }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Social Links -->
                        @if($card->socialLinks->count() > 0)
                        <div>
                            <h3 class="font-bold text-gray-900 mb-3">التواصل الاجتماعي</h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($card->socialLinks as $link)
                                <a href="{{ $link->url }}" target="_blank"
                                   class="bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition">
                                    {{ ucfirst($link->platform) }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Stats -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4">الإحصائيات</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">إجمالي المشاهدات</span>
                            <span class="text-2xl font-bold text-blue-600">{{ $analytics['total_views'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">مشاهدات فريدة</span>
                            <span class="text-2xl font-bold text-green-600">{{ $analytics['unique_views'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">نقرات الروابط</span>
                            <span class="text-2xl font-bold text-purple-600">{{ $analytics['social_clicks'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4">إجراءات سريعة</h3>
                    <div class="space-y-3">
                        @if($card->is_public && $card->slug)
                        <a href="{{ route('public.cards.show', $card->slug) }}" target="_blank"
                           class="w-full bg-blue-50 hover:bg-blue-100 text-blue-600 px-4 py-3 rounded-lg text-sm font-semibold block text-center transition">
                            فتح الرابط العام
                        </a>
                        @endif

                        <a href="{{ route('customer.orders.create') }}?card_id={{ $card->id }}"
                           class="w-full bg-green-50 hover:bg-green-100 text-green-600 px-4 py-3 rounded-lg text-sm font-semibold block text-center transition">
                            طلب طباعة
                        </a>

                        <a href="{{ route('customer.cards.download-qr', $card) }}"
                           class="w-full bg-purple-50 hover:bg-purple-100 text-purple-600 px-4 py-3 rounded-lg text-sm font-semibold block text-center transition">
                            تحميل QR Code
                        </a>

                        @if($card->slug)
                        <div class="bg-gray-50 rounded-lg p-3 mt-4">
                            <p class="text-gray-500 text-xs mb-1">رابط البطاقة</p>
                            <p class="text-gray-900 text-xs font-mono break-all" dir="ltr">{{ url('/card/' . $card->slug) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Card Info -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-bold text-gray-900 mb-4">معلومات البطاقة</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">تاريخ الإنشاء</span>
                            <span class="text-gray-900">{{ $card->created_at->format('Y/m/d') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">آخر تعديل</span>
                            <span class="text-gray-900">{{ $card->updated_at->format('Y/m/d') }}</span>
                        </div>
                        @if($card->template)
                        <div class="flex justify-between">
                            <span class="text-gray-500">القالب</span>
                            <span class="text-gray-900">{{ $card->template->name }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-500">NFC ID</span>
                            <span class="text-gray-900 font-mono text-xs">{{ $card->nfc_id }}</span>
                        </div>
                    </div>
                </div>

                <!-- Delete -->
                <form method="POST" action="{{ route('customer.cards.destroy', $card) }}"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذه البطاقة؟ لا يمكن التراجع عن هذا الإجراء.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-red-600 hover:text-red-700 hover:bg-red-50 px-4 py-2.5 rounded-lg text-sm font-medium transition border border-red-200">
                        حذف البطاقة
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
