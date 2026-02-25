@extends('layouts.public')

@section('title', 'من نحن - معروف')
@section('description', 'تعرف على معروف - منصة سعودية رائدة في مجال بطاقات التعريف الرقمية الذكية. رؤيتنا، رسالتنا، وفريق العمل.')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/maroof-about.css') }}">
@endpush

@section('content')

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="hero-dots"></div>
        <div class="container-custom relative z-10">
            <div class="sec-pre text-gold">قصتنا وهويتنا</div>
            <h1 class="hero-h1 text-white">من نحن، وكيف بدأنا الثورة <br><span class="gold">بالهوية الرقمية</span></h1>
            <p class="hero-sub mx-auto" style="color:rgba(255,255,255,0.8)">
                نحن منصة سعودية رائدة في مجال بطاقات التعريف الرقمية الذكية. نسعى لتسهيل مشاركة المعلومات بطريقة عصرية
                وآمنة.
            </p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="features" style="background:#FFF">
        <div class="container-custom">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="sec-pre">بداية الرحلة</p>
                    <h2 class="sec-h" style="max-width:none">قصتنا: من فكرة بسيطة إلى<br><em>أكثر من 5,000 مستخدم</em></h2>
                    <div class="space-y-6 text-gray-700 leading-relaxed text-lg" style="margin-top:24px">
                        <p>
                            بدأت معروف من فكرة بسيطة: كيف يمكننا تسهيل مشاركة معلومات التواصل في العصر الرقمي؟ في عام 2024،
                            قررنا تحويل هذه الفكرة إلى واقع.
                        </p>
                        <p>
                            اليوم، نخدم آلاف المستخدمين في المملكة العربية السعودية ودول الخليج، ونوفر حلولاً مبتكرة للأفراد
                            والشركات لإنشاء بطاقات تعريف رقمية احترافية.
                        </p>
                        <p>
                            نؤمن بأن التكنولوجيا يجب أن تكون في خدمة الناس، لذلك نحرص على توفير منصة سهلة الاستخدام، آمنة،
                            وبأسعار معقولة للجميع.
                        </p>
                    </div>
                </div>
                <div class="hero-vis">
                    <div class="card-3d" style="height:400px">
                        <!-- Visual element like a 3D image or mockup -->
                        <div class="card-inner" style="width:300px; height:450px; margin:auto">
                            <div class="card-pattern"></div>
                            <div class="card-logo">معروف.ID</div>
                            <div class="card-name" style="font-size:24px; bottom:120px">نصنع المستقبل</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="cmp" style="background:#F8F5EF">
        <div class="container-custom">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Vision -->
                <div class="fc vis" style="opacity:1; transform:none">
                    <div class="fc-ico" style="background:var(--goldlt)">👁️</div>
                    <h3 class="text-2xl font-bold mb-4">رؤيتنا</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        أن نكون المنصة الأولى في الشرق الأوسط لبطاقات التعريف الرقمية، ونساهم في التحول الرقمي للمجتمع.
                    </p>
                </div>

                <!-- Mission -->
                <div class="fc vis" style="opacity:1; transform:none">
                    <div class="fc-ico" style="background:rgba(45,122,79,0.1)">🚀</div>
                    <h3 class="text-2xl font-bold mb-4">رسالتنا</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        نوفر حلولاً مبتكرة وسهلة الاستخدام تمكّن الأفراد والشركات من مشاركة معلوماتهم بطريقة عصرية وآمنة.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="features">
        <div class="feat-w">
            <div class="text-center mb-16">
                <p class="sec-pre">المبادئ</p>
                <h2 class="sec-h mx-auto">قيمنا التي<br><em>تحدد هويتنا</em></h2>
            </div>

            <div class="feat-grid">
                <div class="fc">
                    <div class="fc-ico">🛡️</div>
                    <h3>الأمان والخصوصية</h3>
                    <p>نحمي بيانات مستخدمينا بأعلى معايير الأمان العالمية.</p>
                </div>
                <div class="fc">
                    <div class="fc-ico">💡</div>
                    <h3>الابتكار المستمر</h3>
                    <p>نطور منتجاتنا باستمرار لتلبية تطلعات السوق السعودي.</p>
                </div>
                <div class="fc">
                    <h3>التركيز على العميل</h3>
                    <p>رضا عملائنا هو البوصلة التي توجه قراراتنا الفنية.</p>
                </div>
                <div class="fc">
                    <h3>الجودة والإتقان</h3>
                    <p>نلتزم بتقديم أفضل تجربة مستخدم في كل تفصيله.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="pricing" style="background:#FFF">
        <div class="price-w">
            <div class="price-main">
                <h2 class="sec-h text-gold" style="max-width:none">هل أنت مستعد لتكون جزءاً من القصة؟</h2>
                <p class="text-xl mb-8 text-gray-600 max-w-2xl mx-auto">
                    انضم اليوم لشبكة المحترفين الأسرع نمواً في المملكة.
                </p>
                <div class="price-cta">
                    <a href="{{ route('register') }}" class="btn-gold">ابدأ الآن مجاناً</a>
                    <a href="{{ route('contact') }}" class="btn-ghost">تواصل معنا</a>
                </div>
            </div>
        </div>
    </section>

@endsection