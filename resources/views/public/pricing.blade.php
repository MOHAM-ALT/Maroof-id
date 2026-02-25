@extends('layouts.public')

@section('title', 'الأسعار - معروف')
@section('description', 'اختر الخطة المناسبة لك من بين خططنا المرنة. ابدأ مجاناً أو احصل على مميزات إضافية مع الخطط المدفوعة.')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/maroof-pricing.css') }}">
@endpush

@section('content')

    <!-- Hero Section -->
    <section class="pricing-hero">
        <div class="hero-dots"></div>
        <div class="container-custom relative z-10">
            <div class="sec-pre text-gold">خطط عادلة</div>
            <h1 class="hero-h1 text-white">استثمر في هويتك، <br><span class="gold">بسعر يدفع لمرة واحدة</span></h1>
            <p class="hero-sub mx-auto" style="color:rgba(255,255,255,0.8)">
                اختر الباقة التي تناسب طموحك. لا توجد اشتراكات شهرية مملة، ادفع مرة واحدة واستمتع بالخدمة للأبد.
            </p>
        </div>
    </section>

    <!-- Pricing Section (Zapier Style Cards) -->
    <section class="features" id="plans" style="background:#FFF">
        <div class="container-custom">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $index => $plan)
                    <div class="fc {{ $index === 1 ? 'vis popular' : 'vis' }}"
                        style="opacity:1; transform:none; {{ $index === 1 ? 'border-color:var(--gold2); background:#FFFBF2' : '' }}">
                        @if($index === 1)
                            <div
                                style="position:absolute; top:12px; left:12px; background:var(--gold); color:#FFF; font-size:10px; font-weight:800; padding:4px 10px; border-radius:100px; text-transform:uppercase">
                                الأكثر طلباً</div>
                        @endif

                        <div class="fc-ico" style="background:var(--goldlt)">
                            {{ $index === 0 ? '🌱' : ($index === 1 ? '⭐' : '👑') }}</div>
                        <h3 class="text-2xl font-black">{{ $plan['name'] }}</h3>
                        <p class="text-gray-500 mb-6">{{ $plan['description'] }}</p>

                        <div class="mb-8">
                            <span class="text-4xl font-black text-gold">{{ $plan['price'] }}</span>
                            @if(isset($plan['period']))
                                <span class="text-gray-400 text-sm">/ {{ $plan['period'] }}</span>
                            @endif
                        </div>

                        <ul class="space-y-4 mb-8 text-right">
                            @foreach($plan['features'] as $feature)
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <span class="text-gold">✓</span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('register') }}"
                            class="{{ $index === 1 ? 'btn-gold' : 'btn-ghost' }} w-full text-center">ابدأ الآن</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Detailed Comparison -->
    <section class="cmp" style="background:#F8F5EF">
        <div class="cmp-w">
            <div class="text-center mb-12">
                <p class="sec-pre">جدول المقارنة</p>
                <h2 class="sec-h mx-auto">قارن بين الباقات<br><em>بكل شفافية</em></h2>
            </div>

            <div class="cmp-tbl">
                <div class="cmp-hdr">
                    <div class="cmp-col">الميزة</div>
                    @foreach($plans as $plan)
                        <div class="cmp-col {{ $loop->index === 1 ? 'hi' : '' }}">
                            {{ $plan['name'] }}
                            @if($loop->index === 1)<span class="cmp-badge">PRO</span>@endif
                        </div>
                    @endforeach
                </div>

                <div class="cmp-row">
                    <div class="cmp-feat">عدد البطاقات الرقمية</div>
                    <div class="cmp-val">1</div>
                    <div class="cmp-val">5</div>
                    <div class="cmp-val">غير محدود</div>
                </div>

                <div class="cmp-row">
                    <div class="cmp-feat">تعديل البيانات (Real-time)</div>
                    <div class="cmp-val yes">✓</div>
                    <div class="cmp-val yes">✓</div>
                    <div class="cmp-val yes">✓</div>
                </div>

                <div class="cmp-row">
                    <div class="cmp-feat">تحليلات الزوار والمسح</div>
                    <div class="cmp-val no">✕</div>
                    <div class="cmp-val yes">✓</div>
                    <div class="cmp-val yes">✓</div>
                </div>

                <div class="cmp-row">
                    <div class="cmp-feat">دعم فني مخصص</div>
                    <div class="cmp-val">إيميل</div>
                    <div class="cmp-val hi">واتساب</div>
                    <div class="cmp-val">أولوية 24/7</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="pricing" style="background:#FFF">
        <div class="price-w">
            <div class="price-main">
                <h2 class="sec-h text-gold" style="max-width:none">هل تحتاج لمميزات خاصة بشركتك؟</h2>
                <p class="text-xl mb-8 text-gray-600 max-w-2xl mx-auto">
                    نقدم حلولاً مخصصة للشركات والمؤسسات والفرق الكبيرة بأسعار تنافسية.
                </p>
                <div class="price-cta">
                    <a href="{{ route('contact') }}" class="btn-gold">تواصل مع مبيعات الشركات</a>
                    <a href="{{ route('register') }}" class="btn-ghost">جرب الباقة المجانية</a>
                </div>
            </div>
        </div>
    </section>

@endsection