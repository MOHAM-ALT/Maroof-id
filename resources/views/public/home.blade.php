@extends('layouts.public')

@section('title', 'معروف - بطاقة التعريف الرقمية الذكية')
@section('description', 'أنشئ بطاقة تعريفك الرقمية الذكية في دقائق. شارك معلوماتك بسهولة وأمان مع NFC و QR Code.')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/maroof-home.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/maroof-home.js') }}" defer></script>
@endpush

@section('content')
    <section class="hero">
        <div class="hero-dots"></div>
        <div class="hero-w">
            <div>
                <div class="hero-kicker"><span class="hero-kicker-dot"></span>منصة سعودية 100% · مصممة للمحترف العربي</div>
                <h1 class="hero-h1">بطاقتك الذكية،<br><span class="gold">هويتك الرقمية</span><br>مرة واحدة، للأبد</h1>
                <p class="hero-sub">بطاقة NFC فاخرة + صفحة رقمية احترافية + خريطة مدعومة بالواقع المعزز. ادفع مرة واحدة 99
                    ريال
                    فقط، وعدّل معلوماتك متى ما بغيت — بدون اشتراكات، بدون مفاجآت.</p>
                <div class="hero-btns">
                    <a href="{{ route('register') }}" class="hero-price">
                        <span>ابدأ الآن · فقط</span>
                        <strong>99 ريال</strong>
                        <span class="hero-price-old">vs 450 ريال/سنة</span>
                    </a>
                    <a href="#how" class="hero-demo">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3" />
                            <path d="M6.5 5.5l4 2.5-4 2.5V5.5z" fill="currentColor" />
                        </svg>
                        شاهد كيف تعمل
                    </a>
                </div>
                <div class="hero-proof">
                    <div class="avs">
                        <div class="av" style="background:var(--gold)">أ</div>
                        <div class="av" style="background:#2D7A4F">م</div>
                        <div class="av" style="background:#2C5F9E">س</div>
                        <div class="av" style="background:#8B4513">ف</div>
                        <div class="av" style="background:var(--gold2)">ع</div>
                    </div>
                    <p class="proof-t"><strong>+5,200 محترف سعودي</strong> يستخدمون معروف ID اليوم</p>
                </div>
            </div>
            <div class="hero-vis">
                <div class="card-3d">
                    <div class="phone-mock">
                        <div class="phone-screen">
                            <div class="ph-avatar">أ</div>
                            <div class="ph-name">أحمد العتيبي</div>
                            <div class="ph-role">مهندس برمجيات · أرامكو</div>
                            <div class="ph-links">
                                <div class="ph-link">in</div>
                                <div class="ph-link">tw</div>
                                <div class="ph-link">ig</div>
                                <div class="ph-link">gh</div>
                            </div>
                            <div class="ph-ar-badge">📍 AR خريطة مباشرة</div>
                        </div>
                    </div>
                    <div class="card-inner">
                        <div class="card-shine"></div>
                        <div class="card-pattern"></div>
                        <div class="card-logo">معروف.ID</div>
                        <div class="card-nfc">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                <path d="M3 7c0-2.2 1.8-4 4-4" stroke="rgba(200,151,58,0.8)" stroke-width="1.3"
                                    stroke-linecap="round" />
                                <path d="M5 7c0-1.1.9-2 2-2" stroke="rgba(200,151,58,0.6)" stroke-width="1.3"
                                    stroke-linecap="round" />
                                <circle cx="7" cy="7" r="1" fill="rgba(200,151,58,0.8)" />
                            </svg>
                        </div>
                        <div class="card-name">أحمد العتيبي</div>
                        <div class="card-title">مهندس برمجيات · أرامكو السعودية</div>
                        <div class="card-url">maroof-id.com/ahmed-alotaibi</div>
                        <div class="card-chip"></div>
                    </div>
                    <div class="fl-card">
                        <div class="fl-l">مشاهدات هذا الأسبوع</div>
                        <div class="fl-v">247</div>
                        <div class="fl-s">↑ 23% مقارنة بالأسبوع الماضي</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="logos">
        <div class="logos-w">
            <p class="logos-l">محترفون من</p>
            <div class="logos-row">
                <span class="lco">أرامكو</span><span class="lco">STC</span><span class="lco">الراجحي</span><span
                    class="lco">NEOM</span><span class="lco">stc pay</span><span class="lco">مدى</span>
            </div>
        </div>
    </div>

    <div class="mq-wrap">
        <div class="mq-inner" id="mq1">
            <span class="mqi"><svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <rect x="1" y="1" width="10" height="10" rx="2" stroke="currentColor" stroke-width="1.2" />
                </svg>بطاقة NFC فاخرة</span>
            <span class="mqi"><svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.2" />
                </svg>صفحة رقمية احترافية</span>
            <span class="mqi"><svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                </svg>خريطة AR مدمجة</span>
            <span class="mqi"><svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M2 2l4 4-4 4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>35+ قالب احترافي</span>
            <span class="mqi"><svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <rect x="1" y="1" width="10" height="10" rx="2" stroke="currentColor" stroke-width="1.2" />
                    <path d="M4 6h4M4 8h2" stroke="currentColor" stroke-width="1" stroke-linecap="round" />
                </svg>تحليلات ذكية</span>
            <span class="mqi"><svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                </svg>بدون اشتراكات</span>
            <span class="mqi"><svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.2" />
                    <path d="M4 6l1.5 1.5L8.5 4" stroke="currentColor" stroke-width="1.1" stroke-linecap="round" />
                </svg>دفع بمدى و STC Pay</span>
            <span class="mqi"><svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                </svg>شحن 3-5 أيام</span>
        </div>
    </div>

    <section class="features" id="features">
        <div class="feat-w">
            <p class="sec-pre">لماذا معروف ID</p>
            <h2 class="sec-h">كل ما يحتاجه المحترف<br><em>في بطاقة واحدة</em></h2>
            <p class="sec-sub">بطاقة واحدة تجمع هويتك المهنية، معلوماتك، روابطك، وموقعك على الخريطة — كلها تتحدث تلقائياً
                عند التعديل.</p>
            <div class="feat-grid">
                <div class="fc">
                    <div class="fc-ico" style="background:rgba(200,151,58,0.12);border-color:rgba(200,151,58,0.2)">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                            <rect x="2" y="5" width="18" height="14" rx="2.5" stroke="#C8973A" stroke-width="1.6" />
                            <path d="M7 5V4a4 4 0 018 0v1" stroke="#C8973A" stroke-width="1.6" stroke-linecap="round" />
                            <circle cx="11" cy="12" r="2.5" stroke="#C8973A" stroke-width="1.4" />
                        </svg>
                    </div>
                    <h3>بطاقة NFC ذكية</h3>
                    <p>بطاقة بلاستيكية فاخرة بـ chip NTAG215 موثوق. قرّبها من أي جوال حديث ويظهر ملفك فوراً — بدون تطبيق ولا
                        إنترنت.</p>
                    <span class="fc-tag" style="background:rgba(200,151,58,0.1);color:var(--gold)">مميز</span>
                </div>
                <div class="fc">
                    <div class="fc-ico" style="background:rgba(45,122,79,0.12);border-color:rgba(45,122,79,0.2)">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                            <rect x="2" y="2" width="18" height="18" rx="3" stroke="#2D7A4F" stroke-width="1.6" />
                            <path d="M7 11h8M7 8h5M7 14h6" stroke="#2D7A4F" stroke-width="1.4" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3>صفحة رقمية احترافية</h3>
                    <p>ملفك الشخصي على رابط ثابت (maroof-id.com/اسمك). أضف صورتك، مسماك، روابطك، نبذتك — كل شيء في صفحة
                        واحدة أنيقة.</p>
                    <span class="fc-tag" style="background:rgba(45,122,79,0.1);color:#4ade80">جاهزة فوراً</span>
                </div>
                <div class="fc">
                    <div class="fc-ico" style="background:rgba(200,151,58,0.12);border-color:rgba(200,151,58,0.2)">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                            <circle cx="11" cy="10" r="4" stroke="#C8973A" stroke-width="1.6" />
                            <path d="M11 14v4M8 18h6" stroke="#C8973A" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M3 6l3 2M19 6l-3 2" stroke="#C8973A" stroke-width="1.3" stroke-linecap="round"
                                opacity=".5" />
                        </svg>
                    </div>
                    <h3>خريطة مدعومة بـ AR</h3>
                    <p>أضف موقع مكتبك أو محلك — يظهر على خريطة تفاعلية مع تجربة واقع معزز خاصة. عميلك يجد مكانك بسهولة تامة.
                    </p>
                    <span class="fc-tag" style="background:rgba(200,151,58,0.1);color:var(--gold)">AR Web</span>
                </div>
                <div class="fc">
                    <div class="fc-ico" style="background:rgba(44,95,158,0.12);border-color:rgba(44,95,158,0.2)">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                            <path d="M4 16l4-8 4 8M13 10l5 6" stroke="#6096D4" stroke-width="1.6" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <circle cx="16" cy="7" r="2" stroke="#6096D4" stroke-width="1.4" />
                        </svg>
                    </div>
                    <h3>35+ قالب احترافي</h3>
                    <p>قوالب مصممة لكل مجال: أطباء، مهندسون، محامون، مسوّقون، صناع محتوى. اختر ما يعبّر عن شخصيتك وتخصصك.
                    </p>
                    <span class="fc-tag" style="background:rgba(44,95,158,0.1);color:#60a5fa">مميزة</span>
                </div>
                <div class="fc">
                    <div class="fc-ico" style="background:rgba(200,151,58,0.12);border-color:rgba(200,151,58,0.2)">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                            <path d="M4 16l3-6 3 6M13 10l2 6 2-4 2 4" stroke="#C8973A" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <rect x="2" y="4" width="18" height="14" rx="2" stroke="#C8973A" stroke-width="1.5" fill="none"
                                opacity=".3" />
                        </svg>
                    </div>
                    <h3>تحليلات ذكية</h3>
                    <p>اعرف من شاف ملفك، من مسح البطاقة، من حفظ رقمك — إحصائيات دقيقة في Dashboard بسيط وواضح.</p>
                    <span class="fc-tag" style="background:rgba(200,151,58,0.1);color:var(--gold)">لحظة بلحظة</span>
                </div>
                <div class="fc">
                    <div class="fc-ico" style="background:rgba(45,122,79,0.12);border-color:rgba(45,122,79,0.2)">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                            <path d="M4 12l5 5L18 7" stroke="#2D7A4F" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3>تحديثات مجانية للأبد</h3>
                    <p>غيّر رقمك، شركتك، لقبك، صورتك — عدّل من Dashboard والبطاقة تتحدث فوراً. لا طباعة جديدة، لا تكاليف
                        إضافية.</p>
                    <span class="fc-tag" style="background:rgba(45,122,79,0.1);color:#4ade80">مجاني</span>
                </div>
            </div>
        </div>
    </section>

    <section class="counter" id="cntSec">
        <div class="cnt-w">
            <div>
                <p class="sec-pre">نتائج حقيقية</p>
                <h2 class="sec-h" style="max-width:none">أرقام<br><em>تتحدث بنفسها</em></h2>
                <p style="font-size:15px;color:var(--t2);line-height:1.7;margin-bottom:32px;max-width:420px">منذ الإطلاق،
                    والمحترفون السعوديون يختارون معروف ID الأسرع في النمو والأوفر تكلفة.</p>
                <div style="display:flex;align-items:baseline;margin-bottom:8px">
                    <div class="d-slot">
                        <div class="d-reel" id="r0">0</div>
                    </div>
                    <div class="d-slot">
                        <div class="d-reel" id="r1">0</div>
                    </div>
                    <div class="d-sep">,</div>
                    <div class="d-slot">
                        <div class="d-reel" id="r2">0</div>
                    </div>
                    <div class="d-slot">
                        <div class="d-reel" id="r3">0</div>
                    </div>
                    <div class="d-slot">
                        <div class="d-reel" id="r4">0</div>
                    </div>
                </div>
                <div style="font-size:12px;color:var(--t4);margin-bottom:28px">بطاقة ذكية تم تفعيلها حتى الآن</div>
                <div class="tl">
                    <div class="tl-dot"></div>
                    <div class="tl-bar">
                        <div class="tl-fill" id="tlFill"></div><span class="tl-lbl s">يناير 2025</span><span
                            class="tl-lbl e">اليوم</span>
                    </div>
                    <div class="tl-dot"></div>
                </div>
            </div>
            <div class="cnt-feats">
                <div class="cnt-f">
                    <div class="cnt-f-ico"><svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M2 8l4 4 8-8" stroke="#C8973A" stroke-width="1.6" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg></div>
                    <div>
                        <h4>بدون اشتراكات شهرية</h4>
                        <p>ادفع 99 ريال مرة واحدة، والبطاقة معك للأبد</p>
                    </div>
                </div>
                <div class="cnt-f">
                    <div class="cnt-f-ico"><svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <circle cx="8" cy="8" r="6" stroke="#C8973A" stroke-width="1.4" />
                            <path d="M8 5v3l2 2" stroke="#C8973A" stroke-width="1.4" stroke-linecap="round" />
                        </svg></div>
                    <div>
                        <h4>تحديث في أقل من 60 ثانية</h4>
                        <p>غيّر بياناتك وستظهر فوراً لكل من يمسح البطاقة</p>
                    </div>
                </div>
                <div class="cnt-f">
                    <div class="cnt-f-ico"><svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8 2l1.8 3.6H14l-3.5 2.7 1.3 4L8 10l-3.8 2.3 1.3-4L2 5.6h4.2L8 2z" stroke="#C8973A"
                                stroke-width="1.3" fill="none" stroke-linejoin="round" />
                        </svg></div>
                    <div>
                        <h4>تقييم 4.9 نجوم من المستخدمين</h4>
                        <p>أعلى تقييم في السوق السعودي لمنتجات مماثلة</p>
                    </div>
                </div>
                <div class="cnt-f">
                    <div class="cnt-f-ico"><svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3 8h10M3 5h7M3 11h5" stroke="#C8973A" stroke-width="1.4" stroke-linecap="round" />
                        </svg></div>
                    <div>
                        <h4>دعم عربي بواتساب</h4>
                        <p>فريق سعودي يرد خلال ساعة في أوقات العمل</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="tags-wrap">
        <div class="tag-row" style="animation-duration:20s">
            <span class="ctag"><span class="ctag-dot" style="background:var(--gold)"></span>مهندس برمجيات</span>
            <span class="ctag"><span class="ctag-dot" style="background:#2D7A4F"></span>وكيل عقاري</span>
            <span class="ctag"><span class="ctag-dot" style="background:#2C5F9E"></span>طبيب أسنان</span>
            <span class="ctag"><span class="ctag-dot" style="background:var(--gold2)"></span>مصمم جرافيك</span>
            <span class="ctag"><span class="ctag-dot" style="background:#8B4513"></span>محاسب قانوني</span>
            <span class="ctag"><span class="ctag-dot" style="background:var(--gold)"></span>مدير مبيعات</span>
            <span class="ctag"><span class="ctag-dot" style="background:#2D7A4F"></span>مستشار مالي</span>
            <span class="ctag"><span class="ctag-dot" style="background:#6929C4"></span>صانع محتوى</span>
        </div>
        <div class="tag-row" style="animation-duration:24s">
            <span class="ctag"><span class="ctag-dot" style="background:#9E2C2C"></span>محامي</span>
            <span class="ctag"><span class="ctag-dot" style="background:var(--gold2)"></span>مؤثر رقمي</span>
            <span class="ctag"><span class="ctag-dot" style="background:#2C5F9E"></span>صيدلاني</span>
            <span class="ctag"><span class="ctag-dot" style="background:var(--gold)"></span>مدير تسويق</span>
            <span class="ctag"><span class="ctag-dot" style="background:#2D7A4F"></span>مهندس مدني</span>
            <span class="ctag"><span class="ctag-dot" style="background:#8B4513"></span>كاتب محتوى</span>
            <span class="ctag"><span class="ctag-dot" style="background:var(--gold)"></span>معالج نفسي</span>
            <span class="ctag"><span class="ctag-dot" style="background:#9E2C2C"></span>مدير HR</span>
        </div>
        <div class="tag-row" style="animation-duration:18s">
            <span class="ctag"><span class="ctag-dot" style="background:var(--gold)"></span>خبير ضرائب</span>
            <span class="ctag"><span class="ctag-dot" style="background:#2D7A4F"></span>مدرب رياضي</span>
            <span class="ctag"><span class="ctag-dot" style="background:#6929C4"></span>مستشار تقني</span>
            <span class="ctag"><span class="ctag-dot" style="background:var(--gold2)"></span>رائد أعمال</span>
            <span class="ctag"><span class="ctag-dot" style="background:#2C5F9E"></span>مصور فوتوغرافي</span>
            <span class="ctag"><span class="ctag-dot" style="background:var(--gold)"></span>مهندس معماري</span>
            <span class="ctag"><span class="ctag-dot" style="background:#8B4513"></span>مدير مشاريع</span>
        </div>
    </div>

    <section class="showcase" id="how">
        <div class="show-w">
            <div class="sc-l">
                <p class="sec-pre">كيف يعمل</p>
                <h2 class="sec-h" style="max-width:none">ملفك الرقمي<br>بكل تفصيله</h2>
                <div class="sc-steps">
                    <div class="ss on" data-step="0">
                        <div class="ss-n">1</div>
                        <div>
                            <h4>معلوماتك الشخصية والمهنية</h4>
                            <p>اسمك، لقبك، شركتك، صورتك، نبذتك — كلها في صفحة واحدة أنيقة تعكس شخصيتك المهنية.</p>
                        </div>
                    </div>
                    <div class="ss" data-step="1">
                        <div class="ss-n">2</div>
                        <div>
                            <h4>روابطك ووسائل تواصلك</h4>
                            <p>LinkedIn، Twitter، Instagram، GitHub، YouTube، واتساب — ربطها كلها في مكان واحد يسهّل
                                التواصل.</p>
                        </div>
                    </div>
                    <div class="ss" data-step="2">
                        <div class="ss-n">3</div>
                        <div>
                            <h4>خريطة AR لموقعك</h4>
                            <p>موقع مكتبك أو محلك بخريطة تفاعلية مع تجربة واقع معزز خاصة — عميلك يصلك بسهولة.</p>
                        </div>
                    </div>
                    <div class="ss" data-step="3">
                        <div class="ss-n">4</div>
                        <div>
                            <h4>تحليلات ومتابعة</h4>
                            <p>اعرف عدد المشاهدات، عمليات مسح NFC، حفظ جهات الاتصال — كل شيء في لوحة تحكم بسيطة.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sc-r">
                <div class="sc-p" data-panel="0">
                    <div class="ph-bar"><span class="pt">الملف الشخصي</span><span class="pb"><span
                                class="ldot"></span>مباشر</span></div>
                    <div class="profile-card">
                        <div class="profile-hdr"></div>
                        <div class="profile-body">
                            <div class="profile-av">أ</div>
                            <div class="profile-nm">أحمد العتيبي</div>
                            <div class="profile-ro">مهندس برمجيات · أرامكو السعودية</div>
                            <div class="profile-bio">متخصص في تطوير الأنظمة الذكية وحلول السحابة. أكثر من 8 سنوات خبرة في
                                تقنية المعلومات.</div>
                            <div class="profile-links">
                                <a href="#" class="plink">📱 Instagram</a>
                                <a href="#" class="plink">💼 LinkedIn</a>
                                <a href="#" class="plink">🐦 Twitter</a>
                                <a href="#" class="plink">💻 GitHub</a>
                                <a href="#" class="plink">📞 واتساب</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sc-p" data-panel="1">
                    <div class="ph-bar"><span class="pt">الروابط والتواصل</span><span class="pb"><span
                                class="ldot"></span>مباشر</span></div>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <div
                            style="padding:12px 14px;background:#F8F5EF;border:1px solid var(--bd);border-radius:10px;display:flex;align-items:center;justify-content:space-between">
                            <div style="display:flex;align-items:center;gap:10px">
                                <div
                                    style="width:32px;height:32px;border-radius:8px;background:rgba(200,151,58,0.15);display:flex;align-items:center;justify-content:center;font-size:13px">
                                    in</div>
                                <div>
                                    <div style="font-size:13px;font-weight:700;color:var(--t1)">LinkedIn</div>
                                    <div style="font-size:11.5px;color:var(--t3)">linkedin.com/in/ahmed-alotaibi</div>
                                </div>
                            </div>
                            <div
                                style="font-size:11px;color:var(--gold);background:var(--goldlt);padding:3px 9px;border-radius:6px;font-weight:600">
                                342 زيارة</div>
                        </div>
                        <div
                            style="padding:12px 14px;background:#F8F5EF;border:1px solid var(--bd);border-radius:10px;display:flex;align-items:center;justify-content:space-between">
                            <div style="display:flex;align-items:center;gap:10px">
                                <div
                                    style="width:32px;height:32px;border-radius:8px;background:rgba(45,122,79,0.15);display:flex;align-items:center;justify-content:center;font-size:13px">
                                    🐦</div>
                                <div>
                                    <div style="font-size:13px;font-weight:700;color:var(--t1)">Twitter / X</div>
                                    <div style="font-size:11.5px;color:var(--t3)">@ahmed_alotaibi_sa</div>
                                </div>
                            </div>
                            <div
                                style="font-size:11px;color:var(--gold);background:var(--goldlt);padding:3px 9px;border-radius:6px;font-weight:600">
                                218 زيارة</div>
                        </div>
                        <div
                            style="padding:12px 14px;background:#F8F5EF;border:1px solid var(--bd2);border-radius:10px;display:flex;align-items:center;gap:10px">
                            <div
                                style="width:32px;height:32px;border-radius:8px;background:rgba(200,151,58,0.15);display:flex;align-items:center;justify-content:center;font-size:13px">
                                ＋</div>
                            <div style="font-size:13px;color:var(--gold);font-weight:600">أضف رابطاً جديداً...</div>
                        </div>
                    </div>
                </div>
                <div class="sc-p" data-panel="2">
                    <div class="ph-bar"><span class="pt">خريطة AR</span><span class="pb"><span
                                class="ldot"></span>مفعّلة</span></div>
                    <div class="profile-ar" style="margin-bottom:10px">
                        <div class="ar-lbl">تجربة الواقع المعزز</div>
                        <div class="ar-badge">
                            <div class="ar-dot"></div>AR Web نشط
                        </div>
                    </div>
                    <div class="map-preview">
                        <div class="map-grid"></div>
                        <div class="map-pin"></div>
                        <div class="map-pulse"></div>
                        <div class="map-ar-lbl">📍 مكتبي — حي العليا، الرياض</div>
                    </div>
                </div>
                <div class="sc-p" data-panel="3">
                    <div class="ph-bar"><span class="pt">لوحة التحليلات</span><span class="pb"><span
                                class="ldot"></span>لحظي</span></div>
                    <div class="analytics-card">
                        <div class="an-row" style="margin-bottom:10px">
                            <div class="an-stat">
                                <div class="an-n">247</div>
                                <div class="an-l">مشاهدة</div>
                            </div>
                            <div class="an-stat">
                                <div class="an-n">89</div>
                                <div class="an-l">مسح NFC</div>
                            </div>
                            <div class="an-stat">
                                <div class="an-n">62</div>
                                <div class="an-l">جهة اتصال</div>
                            </div>
                        </div>
                        <div class="an-ch">
                            <div class="an-bar" style="height:30%"></div>
                            <div class="an-bar" style="height:50%"></div>
                            <div class="an-bar" style="height:45%"></div>
                            <div class="an-bar hi" style="height:80%"></div>
                            <div class="an-bar" style="height:60%"></div>
                            <div class="an-bar hi" style="height:100%"></div>
                            <div class="an-bar" style="height:70%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="split">
        <div class="split-w">
            <p class="sec-pre" style="text-align:center">اتخذ قرارك</p>
            <h2 class="sec-h" style="text-align:center;max-width:100%">بطاقتك الورقية القديمة<br><em>أم هويتك الرقمية
                    الدائمة؟</em></h2>
            <div class="split-grid">
                <div class="sc2 bad">
                    <div class="sc2-inner">
                        <div class="s2-ey">قبل معروف</div>
                        <h3 class="s2-heading">تحمّل العائق المهني</h3>
                        <div class="s2-items">
                            <div class="s2-item">بطاقات ورقية تُفقد وتُتلف</div>
                            <div class="s2-item">تغيير البيانات يعني طباعة جديدة وتكاليف</div>
                            <div class="s2-item">حلول رقمية بـ 360-550 ريال/سنة</div>
                            <div class="s2-item">واجهات معقدة وإنجليزية بحتة</div>
                        </div>
                    </div>
                    <div class="s2-bot">تكلفة عالية + جهد مضاعف + نتائج محدودة</div>
                </div>
                <div class="sc2 good">
                    <div class="sc2-inner">
                        <div class="s2-ey">مع معروف ID</div>
                        <h3 class="s2-heading">هويتك الرقمية الدائمة</h3>
                        <div class="s2-items">
                            <div class="s2-item">بطاقة فاخرة تدوم للأبد مع تحديثات مجانية</div>
                            <div class="s2-item">99 ريال مرة واحدة للأبد — لا اشتراكات</div>
                            <div class="s2-item">واجهة عربية كاملة، دعم واتساب فوري</div>
                            <div class="s2-item">خريطة AR + تحليلات + روابط غير محدودة</div>
                        </div>
                    </div>
                    <div class="s2-bot-g">
                        <div style="font-size:12.5px;color:rgba(200,151,58,0.6);font-weight:500">بدون بطاقة ائتمانية · جاهز
                            خلال دقائق</div>
                        <a href="{{ route('register') }}"
                            style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:8px;background:var(--gold);color:var(--dark);font-size:13.5px;font-weight:800">احصل
                            على بطاقتك</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cmp">
        <div class="cmp-w">
            <p class="sec-pre" style="text-align:center">مقارنة</p>
            <h2 class="sec-h" style="text-align:center;max-width:100%">لماذا تختار معروف ID؟</h2>
            <div class="cmp-tbl">
                <div class="cmp-hdr">
                    <div></div>
                    <div class="cmp-col">Popl</div>
                    <div class="cmp-col">Blinq</div>
                    <div class="cmp-col hi">
                        <div class="cmp-badge">الأفضل</div>معروف ID
                    </div>
                </div>
                <div class="cmp-row">
                    <div class="cmp-feat">السعر</div>
                    <div class="cmp-val no">450 ريال/سنة</div>
                    <div class="cmp-val no">270 ريال/سنة</div>
                    <div class="cmp-val yes"><strong>99 ريال فقط</strong></div>
                </div>
                <div class="cmp-row">
                    <div class="cmp-feat">اشتراك شهري</div>
                    <div class="cmp-val no">✗</div>
                    <div class="cmp-val no">✗</div>
                    <div class="cmp-val yes">✓</div>
                </div>
                <div class="cmp-row">
                    <div class="cmp-feat">دعم عربي</div>
                    <div class="cmp-val no">✗</div>
                    <div class="cmp-val no">✗</div>
                    <div class="cmp-val yes">✓</div>
                </div>
                <div class="cmp-row">
                    <div class="cmp-feat">خريطة AR</div>
                    <div class="cmp-val no">✗</div>
                    <div class="cmp-val no">✗</div>
                    <div class="cmp-val yes">✓</div>
                </div>
            </div>
        </div>
    </section>

    <section class="test">
        <div class="test-w">
            <p class="sec-pre" style="text-align:center">آراء العملاء</p>
            <h2 class="sec-h" style="text-align:center;max-width:100%">محترفون يثقون بمعروف</h2>
            <div class="msnry">
                <div class="msnry-item">
                    <div class="tcard feat">
                        <div class="tc-stars">★★★★★</div>
                        <p class="tc-quote">"أفضل استثمار في مسيرتي المهنية. الكل في اجتماعات العمل يسألني عن البطاقة،
                            وأتباهى بإنها سعودية وبسعر معقول جداً."</p>
                        <div class="tc-auth">
                            <div class="tc-av" style="background:var(--gold)">أ</div>
                            <div>
                                <div class="tc-nm">أحمد القحطاني</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="msnry-item">
                    <div class="tcard">
                        <div class="tc-stars">★★★★★</div>
                        <p class="tc-quote">"جربت Popl وBlinq وكلهم غاليين ومعقدين. معروف الأسهل والأوفر والدعم باللغة
                            العربية ممتاز."</p>
                        <div class="tc-auth">
                            <div class="tc-av" style="background:#2D7A4F">م</div>
                            <div>
                                <div class="tc-nm">محمد الشمري</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="msnry-item">
                    <div class="tcard">
                        <div class="tc-stars">★★★★★</div>
                        <p class="tc-quote">"خاصية الخريطة AR صدمتني! عملائي ما يضيعون لإيجاد عيادتي. تجربة فريدة ومميزة
                            جداً."</p>
                        <div class="tc-auth">
                            <div class="tc-av" style="background:#9E2C2C">ف</div>
                            <div>
                                <div class="tc-nm">د. فهد الدوسري</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pricing">
        <div class="price-w">
            <p class="sec-pre" style="text-align:center">الأسعار</p>
            <h2 class="sec-h" style="text-align:center;max-width:100%">سعر واحد.<br><em>للأبد.</em></h2>
            <p
                style="font-size:16px;color:var(--t2);line-height:1.7;text-align:center;margin-bottom:0;max-width:480px;margin-left:auto;margin-right:auto">
                لا تحتاج تقارن باقات أو تحسب اشتراكات. سعر واحد شامل كل شيء، إلى الأبد.</p>
            <div class="price-main">
                <div style="text-align:center;position:relative;z-index:1">
                    <div style="display:inline-flex;align-items:baseline;gap:4px">
                        <span class="price-unit">ر.س</span>
                        <span class="price-big">99</span>
                    </div>
                    <div class="price-once">دفعة واحدة · بدون اشتراكات</div>
                </div>
                <div class="price-feats">
                    <div class="pf">
                        <div class="pf-ico">✓</div>
                        <div>
                            <div class="pf-t">بطاقة NFC فاخرة</div>
                        </div>
                    </div>
                    <div class="pf">
                        <div class="pf-ico">✓</div>
                        <div>
                            <div class="pf-t">صفحة رقمية احترافية</div>
                        </div>
                    </div>
                    <div class="pf">
                        <div class="pf-ico">✓</div>
                        <div>
                            <div class="pf-t">خريطة AR مدمجة</div>
                        </div>
                    </div>
                    <div class="pf">
                        <div class="pf-ico">✓</div>
                        <div>
                            <div class="pf-t">تحليلات ذكية</div>
                        </div>
                    </div>
                    <div class="pf">
                        <div class="pf-ico">✓</div>
                        <div>
                            <div class="pf-t">35+ قالب احترافي</div>
                        </div>
                    </div>
                    <div class="pf">
                        <div class="pf-ico">✓</div>
                        <div>
                            <div class="pf-t">تحديثات مجانية للأبد</div>
                        </div>
                    </div>
                </div>
                <div class="price-cta">
                    <a href="{{ route('register') }}" class="btn-gold" style="font-size:16px;padding:14px 32px">احصل على
                        بطاقتك الآن</a>
                    <a href="{{ route('templates.index') }}" class="btn-ghost">شاهد نموذج حي</a>
                </div>
            </div>
        </div>
    </section>

    <section class="orbit">
        <div class="orbit-w">
            <div>
                <p class="sec-pre">ما تحتويه صفحتك</p>
                <h2 class="sec-h">هويتك كاملة<br><em>في رابط واحد</em></h2>
                <p class="sec-sub" style="margin-bottom:22px">كل ما يحتاج يعرفه من يريد التواصل معك، في مكان واحد أنيق ومنظم
                    — من معلوماتك الشخصية إلى خريطتك ومواقع تواصلك.</p>
                <div class="int-chips">
                    <div class="ic">📱 LinkedIn</div>
                    <div class="ic">🐦 Twitter</div>
                    <div class="ic">📸 Instagram</div>
                    <div class="ic">💬 واتساب</div>
                    <div class="ic">📍 خريطة AR</div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="cta-w">
            <p class="sec-pre" style="color:rgba(200,151,58,0.5)">ابدأ اليوم</p>
            <h2 class="cta-h">هويتك المهنية،<br><span class="gold">مرة واحدة للأبد</span></h2>
            <p class="cta-sub">انضم لأكثر من 5,200 محترف سعودي اختاروا معروف ID. بطاقتك في يدك خلال 3-5 أيام.</p>
            <div class="cta-btns">
                <a href="{{ route('register') }}" class="btn-gold" style="font-size:16px;padding:15px 36px">احصل على بطاقتك
                    — 99 ريال فقط</a>
            </div>
        </div>
    </section>
@endsection