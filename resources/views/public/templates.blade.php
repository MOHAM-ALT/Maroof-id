@extends('layouts.public')

@section('title', 'تصفح القوالب - معروف')
@section('description', 'اكتشف مجموعة واسعة من قوالب بطاقات التعريف الرقمية الاحترافية. اختر القالب المناسب لك وابدأ في إنشاء بطاقتك الذكية.')

    @push('head')
        <link rel="stylesheet" href="{{ asset('css/maroof-templates.css') }}">
    @endpush

    @section('content')

        <!-- Hero Section (Subtle) -->
        <section class="about-hero" style="padding: 60px 28px; background: linear-gradient(160deg, #1A1208, #2C1F08);">
            <div class="hero-dots"></div>
            <div class="container-custom relative z-10">
                <div class="sec-pre text-gold">المعرض الرقمي</div>
                <h1 class="hero-h1 text-white" style="font-size: 2.5rem;">اختر <span class="gold">قالبك المثالي</span></h1>
                <p class="hero-sub mx-auto" style="color:rgba(255,255,255,0.7); max-width: 600px;">
                    مجموعة من التصاميم العصرية التي تعكس شخصيتك المهنية وتسهل تواصلك مع العالم.
                </p>
            </div>
        </section>

        <!-- Search and Filter Bar -->
        <section class="features" style="padding: 40px 28px; background: #FFF; border-bottom: 1px solid var(--bd);">
            <div class="container-custom">
                <div class="max-w-4xl mx-auto">
                    <form method="GET" action="{{ route('templates.index') }}" class="space-y-4"
                        x-data="{ showFilters: false }">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن اسم القالب..."
                                class="w-full pr-12 pl-4 py-4 rounded-2xl border border-gray-200 focus:border-gold outline-none transition shadow-sm">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</div>
                        </div>

                        <div class="flex items-center justify-between px-2">
                            <button type="button" @click="showFilters = !showFilters"
                                class="text-sm font-bold text-gray-500 hover:text-gold transition flex items-center gap-2">
                                <span>⚡ الفلاتر المتقدمة</span>
                                <span :class="showFilters ? 'rotate-180' : ''"
                                    class="transition-transform inline-block">▾</span>
                            </button>
                            @if(request()->anyFilled(['search', 'category', 'price', 'sort']))
                                <a href="{{ route('templates.index') }}" class="text-xs text-red-500 font-bold">إلغاء التصفية ✕</a>
                            @endif
                        </div>

                        <div x-show="showFilters" x-transition
                            class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6 bg-gray-50 rounded-2xl border border-gray-100 mt-4">
                            <div>
                                <label
                                    class="block text-xs font-black mb-2 uppercase tracking-wider text-gray-400">الفئة</label>
                                <select name="category"
                                    class="w-full bg-white border border-gray-200 rounded-lg p-2 outline-none">
                                    <option value="">الكل</option>
                                    <option value="personal" {{ request('category') == 'personal' ? 'selected' : '' }}>شخصي
                                    </option>
                                    <option value="business" {{ request('category') == 'business' ? 'selected' : '' }}>أعمال
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-black mb-2 uppercase tracking-wider text-gray-400">السعر</label>
                                <select name="price" class="w-full bg-white border border-gray-200 rounded-lg p-2 outline-none">
                                    <option value="">الكل</option>
                                    <option value="free" {{ request('price') == 'free' ? 'selected' : '' }}>مجاني</option>
                                    <option value="paid" {{ request('price') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="btn-gold w-full text-center py-2">تطبيق</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Templates Grid -->
        <section class="features" style="background:#FEFCF8">
            <div class="container-custom">
                @if($templates->total() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($templates as $template)
                            <a href="{{ route('templates.show', $template) }}" class="fc vis"
                                style="opacity:1; transform:none; padding:12px; border-radius:24px">
                                <div class="relative overflow-hidden rounded-2xl mb-4"
                                    style="aspect-ratio: 4/5; background: var(--dark2);">
                                    @if($template->preview_image)
                                        <img src="{{ Storage::url($template->preview_image) }}" alt="{{ $template->name }}"
                                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                                    @else
                                        <!-- Visual placeholder for premium look -->
                                        <div
                                            style="width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; background:linear-gradient(145deg, #F8F5EF, #E8E0D0); position:relative">
                                            <div
                                                style="width:140px; height:220px; background:#FFF; border-radius:12px; box-shadow:0 12px 32px rgba(0,0,0,0.06); border:1px solid rgba(160,114,10,0.1); display:flex; flex-direction:column; overflow:hidden">
                                                <div
                                                    style="height:60px; background:var(--gold); display:flex; align-items:center; justify-content:center; color:#FFF; font-weight:800; font-size:12px">
                                                    معروف.ID</div>
                                                <div style="padding:12px">
                                                    <div
                                                        style="width:40px; height:40px; border-radius:50%; background:#F0EBE0; margin:-32px auto 8px; border:3px solid #FFF">
                                                    </div>
                                                    <div
                                                        style="width:100%; height:4px; background:#F0EBE0; border-radius:10px; margin-bottom:4px">
                                                    </div>
                                                    <div
                                                        style="width:60%; height:4px; background:#F0EBE0; border-radius:10px; margin:0 auto 12px">
                                                    </div>
                                                    <div style="display:flex; gap:4px; justify-content:center">
                                                        <div style="width:16px; height:16px; border-radius:4px; background:var(--goldlt)">
                                                        </div>
                                                        <div style="width:16px; height:16px; border-radius:4px; background:var(--goldlt)">
                                                        </div>
                                                        <div style="width:16px; height:16px; border-radius:4px; background:var(--goldlt)">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span
                                                style="position:absolute; bottom:20px; font-size:10px; font-weight:900; color:var(--gold); letter-spacing:1px; text-transform:uppercase">Premium
                                                Design</span>
                                        </div>
                                    @endif

                                    @if($template->price == 0)
                                        <div
                                            style="position:absolute; top:12px; left:12px; background:var(--green); color:#FFF; font-size:10px; font-weight:900; padding:4px 12px; border-radius:100px; text-transform:uppercase">
                                            مجاني</div>
                                    @endif
                                </div>

                                <div class="px-3 pb-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-lg font-black">{{ $template->name }}</h3>
                                        <span
                                            style="font-size:10px; background:var(--goldlt); color:var(--gold); padding:2px 8px; border-radius:100px; font-weight:800">{{ $template->category?->name ?? 'عام' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gold font-bold">
                                            @if($template->price > 0)
                                                {{ number_format($template->price, 2) }} ر.س
                                            @else
                                                مجاني
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-400">تخصيص كامل ⚡</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-20">
                        {{ $templates->links('vendor.pagination.simple-tailwind') }}
                    </div>
                @else
                    <div class="text-center py-20">
                        <div style="font-size: 60px; margin-bottom: 20px;">🏜️</div>
                        <h3 class="text-2xl font-black">لم نجد ما تبحث عنه</h3>
                        <p class="text-gray-400 mb-8">جرب كلمات بحث مختلفة أو تصفح كافة القوالب.</p>
                        <a href="{{ route('templates.index') }}" class="btn-gold">عرض جميع القوالب</a>
                    </div>
                @endif
            </div>
        </section>

        <!-- CTA Section -->
        <section class="pricing" style="background:#FFF">
            <div class="price-w">
                <div class="price-main">
                    <h2 class="sec-h text-gold" style="max-width:none">هل تريد قالباً مخصصاً لعلامتك التجارية؟</h2>
                    <p class="text-xl mb-8 text-gray-600 max-w-2xl mx-auto">
                        يمكن لفريق المصممين لدينا بناء قالب فريد يتماشى تماماً مع هويتك البصرية.
                    </p>
                    <div class="price-cta">
                        <a href="{{ route('contact') }}" class="btn-gold">اطلب القالب الخاص</a>
                        <a href="{{ route('register') }}" class="btn-ghost">ابدأ بالقوالب المجانية</a>
                    </div>
                </div>
            </div>
        </section>

    @endsection


@endsection