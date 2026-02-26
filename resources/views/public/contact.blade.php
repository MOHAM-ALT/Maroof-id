@extends('layouts.public')

@section('title', 'تواصل معنا - معروف')
@section('description', 'تواصل مع فريق معروف. نحن هنا للإجابة على استفساراتك ومساعدتك في أي وقت.')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/maroof-contact.css') }}">
@endpush

@section('content')

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="hero-dots"></div>
        <div class="container-custom relative z-10">
            <div class="sec-pre text-gold">نحن هنا لأجلك</div>
            <h1 class="hero-h1 text-white">لديك استفسار؟ <br><span class="gold">يسعدنا تواصلك معنا</span></h1>
            <p class="hero-sub mx-auto" style="color:rgba(255,255,255,0.8)">
                فريقنا مستعد دائماً للإجابة على أسئلتك ومساعدتك في تطوير هويتك الرقمية.
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="features" style="background:#FFF">
        <div class="container-custom">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="price-main" style="text-align:right; padding:40px">
                        <h2 class="text-2xl font-black mb-8">أرسل لنا رسالة</h2>

                        @if(session('success'))
                            <div class="fc vis"
                                style="opacity:1; transform:none; background:var(--greenlt); border-color:var(--green); margin-bottom:24px">
                                <p class="font-bold text-green-800">تم الإرسال بنجاح!</p>
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold mb-2">الاسم الكامل</label>
                                    <input type="text" name="name"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gold outline-none transition"
                                        value="{{ old('name') }}" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-2">البريد الإلكتروني</label>
                                    <input type="email" name="email"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gold outline-none transition"
                                        value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">الموضوع</label>
                                <input type="text" name="subject"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gold outline-none transition"
                                    value="{{ old('subject') }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">الرسالة</label>
                                <textarea name="message" rows="5"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-gold outline-none transition"
                                    required>{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class="btn-gold w-full text-center">إرسال الرسالة</button>
                        </form>
                    </div>
                </div>

                <!-- Contact Info Cards -->
                <div class="space-y-6">
                    <div class="fc vis" style="opacity:1; transform:none">
                        <div class="fc-ico">✉️</div>
                        <h3>البريد الإلكتروني</h3>
                        <p>info@maroof.id</p>
                        <p class="text-xs text-gray-400 mt-2">الرد خلال 24 ساعة</p>
                    </div>
                    <div class="fc vis" style="opacity:1; transform:none">
                        <div class="fc-ico">📞</div>
                        <h3>الهاتف</h3>
                        <p>+966 50 123 4567</p>
                        <p class="text-xs text-gray-400 mt-2">السبت - الخميس: 9ص - 6م</p>
                    </div>
                    <div class="fc vis" style="opacity:1; transform:none">
                        <div class="fc-ico">📍</div>
                        <h3>المقر الرئيسي</h3>
                        <p>الرياض، المملكة العربية السعودية</p>
                        <p class="text-xs text-gray-400 mt-2">حي الملقا، طريق الملك فهد</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FAQ Preview -->
    <section class="cmp" style="background:#F8F5EF">
        <div class="cmp-w text-center">
            <h2 class="sec-h mx-auto">هل لديك استفسار سريع؟</h2>
            <p class="text-gray-500 mb-8">راجع صفحة <a href="{{ route('pricing') }}#faq" class="text-gold font-bold">الأسئلة
                    الشائعة</a> أو تواصل معنا مباشرة.</p>
        </div>
    </section>

@endsection