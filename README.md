# Maroof ID - منصة البطاقات الرقمية الذكية

منصة متكاملة لإنشاء وإدارة بطاقات الأعمال الرقمية (NFC) مع نظام شركاء ومُوزعين ومصممين وتسويق بالعمولة.

## التقنيات المستخدمة

| التقنية | الإصدار |
|---------|---------|
| Laravel | 12.x |
| PHP | 8.3+ |
| Filament | 5.x |
| Livewire | 4.x |
| Tailwind CSS | 4.x |
| Alpine.js | 3.x |
| SQLite | 3.x |
| Spatie Permission | 6.x |
| FilamentShield | - |

## التثبيت

```bash
# استنساخ المشروع
git clone <repository-url>
cd maroof_id

# تثبيت الحزم
composer install
npm install

# إعداد البيئة
cp .env.example .env
php artisan key:generate

# إنشاء قاعدة البيانات (SQLite)
touch database/database.sqlite
php artisan migrate

# تشغيل البيانات الأساسية
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=TemplateCategorySeeder
php artisan db:seed --class=TemplateSeeder

# بيانات وهمية للتطوير (اختياري)
php artisan db:seed --class=DemoDataSeeder

# بناء الواجهات
npm run build

# تشغيل السيرفر
php artisan serve
```

## بيانات الدخول

| الدور | البريد | كلمة المرور |
|-------|--------|-------------|
| المدير | admin@maroof.local | admin123 |
| مدير (بديل) | admin@maroof.sa | password |

**رابط لوحة التحكم:** http://127.0.0.1:8000/admin

## الأدوار والصلاحيات

النظام يدعم 7 أدوار:

| الدور | المسار | الوصف |
|-------|--------|-------|
| `super_admin` | `/admin` | لوحة Filament الكاملة |
| `customer` | `/customer/dashboard` | إنشاء بطاقات وطلب طباعة |
| `print_partner` | `/partner/dashboard` | استلام وتنفيذ طلبات الطباعة |
| `reseller` | `/reseller/dashboard` | بيع بطاقات NFC وتتبع المبيعات |
| `designer` | `/designer/dashboard` | تصميم قوالب والحصول على عوائد |
| `affiliate` | `/affiliate/dashboard` | التسويق بالعمولة وتتبع التحويلات |
| `business` | - | حسابات الشركات (قريبا) |

## الهيكل العام

```
app/
├── Enums/                    # 6 enums (OrderStatus, PaymentStatus, etc)
├── Filament/
│   ├── Pages/                # Dashboard, Analytics, Settings
│   ├── Resources/            # 11 Filament resources (CRUD)
│   └── Widgets/              # 6 dashboard widgets
├── Http/Controllers/
│   ├── Affiliate/            # Dashboard + Clicks
│   ├── Auth/                 # Login, Register, Password Reset
│   ├── Customer/             # Dashboard, Cards, Orders, Analytics, Payment
│   ├── Designer/             # Dashboard + Templates CRUD
│   ├── Partner/              # Dashboard + Orders management
│   ├── Public/               # Home, Pricing, Templates, Card View
│   └── Reseller/             # Dashboard + Sales
├── Models/                   # 15+ Eloquent models
├── Policies/                 # Card & Order authorization
└── Services/
    ├── CardService.php       # QR, vCard, social links
    ├── CommissionService.php # Multi-role commission calculation
    ├── OrderService.php      # Order creation + partner matching
    └── PartnerMatchingService.php # Nearest city algorithm
```

## الميزات الرئيسية

### العملاء
- إنشاء بطاقات رقمية (نموذج من 6 خطوات)
- صفحة بطاقة عامة (`/card/{slug}`) مع تتبع الزيارات
- طلب طباعة بطاقات (فيزيائية / رقمية / مخصصة / جملة)
- تحميل vCard وQR Code
- تطبيق كوبونات خصم

### لوحة الإدارة (Filament)
- إحصائيات شاملة (مستخدمين / بطاقات / طلبات / إيرادات)
- رسم بياني للإيرادات (30 يوم)
- توزيع الطلبات حسب الحالة
- خريطة تفاعلية للدول الأكثر زيارة
- إحصائيات الأجهزة ونمو المستخدمين
- صفحة تحليلات مفصلة
- صفحة إعدادات النظام
- إدارة كاملة لجميع الموارد (CRUD)

### شركاء الطباعة
- لوحة تحكم مع إحصائيات الطلبات
- استلام طلبات تلقائي (خوارزمية أقرب مدينة)
- تحديث حالة الطلبات (معالجة -> شحن -> تسليم)
- تتبع العمولات والمدفوعات

### الموزعون
- تتبع المخزون والمبيعات
- تسجيل مبيعات مع حساب عمولة تلقائي
- تنبيهات نفاد المخزون

### المصممون
- إنشاء وتعديل القوالب
- نظام موافقة (ينشأ غير مفعل ثم يوافق المدير)
- عوائد تلقائية 20% على مبيعات القوالب

### التسويق بالعمولة
- رابط إحالة فريد
- تتبع النقرات والتحويلات
- إحصائيات حسب الدول
- عمولات تلقائية على الطلبات المحالة

### نظام العمولات (5 مستويات)
| المستوى | الإيرادات الشهرية | مكافأة |
|---------|-------------------|--------|
| 1 | أقل من 500,000 ر.س | 0% |
| 2 | 500,000+ ر.س | 5% |
| 3 | 1,000,000+ ر.س | 10% |
| 4 | 1,500,000+ ر.س | 15% |
| 5 | 2,000,000+ ر.س | 20% |

### خوارزمية مطابقة الشركاء
1. مطابقة نفس المدينة (الأقل طلبات أولا)
2. أقرب مدينة بالمسافة (Haversine formula)
3. نفس المنطقة (وسطى / غربية / شرقية / جنوبية / شمالية)
4. أي شريك نشط (توزيع متوازن)

## المسارات (Routes)

| المجموعة | العدد | الوصف |
|----------|-------|-------|
| Public | 7 | الرئيسية، الأسعار، القوالب، المعلومات |
| Auth | 8 | تسجيل دخول/خروج، استعادة كلمة مرور |
| Customer | 21 | لوحة تحكم، بطاقات، طلبات، تحليلات |
| Partner | 5 | لوحة تحكم، طلبات، مدفوعات |
| Reseller | 4 | لوحة تحكم، مبيعات، مدفوعات |
| Designer | 7 | لوحة تحكم، قوالب CRUD، مدفوعات |
| Affiliate | 3 | لوحة تحكم، نقرات، مدفوعات |
| Admin | ~45 | Filament auto-generated |
| API | ~25 | REST API |

## ملاحظات تقنية

### توافق SQLite
```php
// استخدم strftime بدل دوال MySQL
strftime('%d', column)  // بدل DAY(column)
strftime('%H', column)  // بدل HOUR(column)
strftime('%m', column)  // بدل MONTH(column)
```

### Filament 5.x Namespaces
```php
// تم نقلها في v5
Filament\Schemas\Components\Section   // (كانت Forms\Components)
Filament\Schemas\Components\Tabs      // (كانت Forms\Components)
Filament\Schemas\Schema               // (كانت Forms\Form)
Filament\Actions\Action               // (كانت Tables\Actions)
```

### جدول card_views
```php
// لا يستخدم timestamps التقليدية
$timestamps = false;
// يستخدم viewed_at بدل created_at
CardView::whereDate('viewed_at', today());
```

## المتبقي

- [ ] تكامل بوابة الدفع (Tap.sa / STC Pay) - يحتاج API keys
- [ ] إرسال SMS (OTP / إشعارات)
- [ ] NFC Writer API للموزعين
- [ ] تحسين الأداء (caching / indexing)
- [ ] مراجعة أمنية شاملة
- [ ] ميزات B2B (حسابات الشركات)

## الترخيص

خاص - Datropix
