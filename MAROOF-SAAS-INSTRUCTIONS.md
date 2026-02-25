# 📋 Maroof SaaS - ملف تعليمات البناء
# Maroof SaaS Platform - Development Instructions

**الإصدار:** 2.0
**التاريخ:** 10 فبراير 2026
**الغرض:** تعليمات تنفيذية لبناء منصة معروف SaaS من الصفر
**يُقرأ مع:** MAROOF-MASTER-DOCUMENT.md (وصف المشروع)
**مسار المشروع:** `C:\Users\Moha4\OneDrive\Desktop\VS COOD\Datropix\maroof_id`

---

# القسم صفر: قواعد ذهبية غير قابلة للنقاش

> كل AI يقرأ هذا القسم أولاً. مخالفة أي قاعدة = العمل مرفوض.

## 0.1 قاعدة الملفات الحرجة

الملفات التالية **لا تُعاد كتابتها بالكامل أبداً** - فقط إضافة أو تعديل محدد:

```
routes/web.php        → APPEND ONLY
routes/api.php        → APPEND ONLY
config/app.php        → MODIFY SPECIFIC KEYS ONLY
.env                  → MODIFY SPECIFIC KEYS ONLY
composer.json         → MODIFY SPECIFIC KEYS ONLY
package.json          → MODIFY SPECIFIC KEYS ONLY
database/migrations/* → NEVER MODIFY EXISTING - create new only
app/Models/User.php   → APPEND traits/relationships ONLY
```

**الإجراء الإلزامي:**
```
1. اقرأ الملف الحالي بالكامل
2. حدد بالضبط ما ستضيفه/تعدله (سطر بسطر)
3. نفّذ التعديل المحدد فقط
4. اقرأ الملف مرة ثانية وتأكد أن كل شيء القديم موجود + الجديد أُضيف
```

## 0.2 قاعدة التحقق قبل الكتابة

```
قبل إنشاء أي ملف:
→ تحقق: هل الملف موجود؟
→ إذا نعم: اقرأه، ثم عدّل - لا تعيد كتابته
→ إذا لا: أنشئه

قبل تعديل أي ملف:
→ اقرأ الملف الحالي بالكامل
→ افهم علاقاته مع ملفات أخرى
→ عدّل فقط الجزء المطلوب
→ تحقق من النتيجة
```

## 0.3 قاعدة اللغة

```
الكود + التعليقات + المتغيرات: English ONLY
لا نص عربي ولا emojis في أي ملف كود

التواصل مع المطور: بالعربية
محتوى الموقع الظاهر للمستخدم: عربي + إنجليزي (RTL/LTR)
```

## 0.4 قاعدة المسارات الكاملة

```
✅ resources/views/auth/login.blade.php
✅ app/Http/Controllers/Auth/LoginController.php
❌ login.blade.php
❌ LoginController.php
```

## 0.5 قاعدة التقرير

بعد كل جلسة عمل يُكتب تقرير في `ai-workspace/reports/daily/YYYY-MM-DD.md` يشمل:
1. الملفات المُنشأة (مسارات كاملة)
2. الملفات المُعدّلة (وصف دقيق)
3. الأوامر المُنفذة
4. المشاكل والحلول
5. الخطوات التالية

---

# القسم الأول: ما هو مشروع معروف؟

## 1.1 وصف مختصر

**معروف** منصة سعودية لبطاقات الأعمال الذكية. العميل يدفع 99 ريال مرة واحدة ويحصل على:
- بطاقة NFC فيزيائية (بلاستيك + chip ذكي)
- صفحة رقمية مخصصة (maroof-id.com/username)
- تحديثات مجانية مدى الحياة
- تحليلات (من شاف بطاقتك)

## 1.2 أنواع المستخدمين (7 أنواع)

| النوع | الدور | الأولوية |
|-------|-------|----------|
| **Customer** | يشتري بطاقة ذكية | Phase 1 (MVP) |
| **Admin** | يدير المنصة بالكامل | Phase 1 (MVP) |
| **Print Partner** | يطبع ويشحن البطاقات | Phase 1 (MVP) |
| **Reseller** | يبيع البطاقات بجواله (NFC Writer) | Phase 2 |
| **Designer** | يصمم ويبيع قوالب | Phase 2 |
| **Affiliate** | يسوّق ويكسب عمولة | Phase 2 |
| **Business** | يشتري بطاقات لفريق كامل | Phase 3 |

## 1.3 كيف يعمل النظام؟

```
العميل يطلب بطاقة (99 ريال)
    ↓
النظام ينشئ الصفحة الرقمية فوراً
    ↓
النظام يختار أقرب شريك طباعة
    ↓
الشريك يطبع + يبرمج NFC + يشحن
    ↓
العميل يستلم (3-7 أيام)
    ↓
العميل يستخدم البطاقة (NFC / QR / Link)
    ↓
النظام يتتبع المشاهدات والإحصائيات
```

---

# القسم الثاني: Technology Stack

## 2.1 الاختيارات النهائية

### Backend

| التقنية | الإصدار | السبب |
|---------|---------|-------|
| **Laravel** | **12.x** | آخر إصدار مستقر، دعم حتى أغسطس 2027، PHP 8.2+ |
| **PHP** | **8.2+** (يفضل 8.3) | متطلب Laravel 12 |
| **MySQL** | **8.0+** | مستقر ومدعوم |
| **Filament** | **5.x** | آخر إصدار، يدعم Laravel 12 + Livewire 4 + Tailwind 4 |
| **Livewire** | **4.x** | يأتي مع Filament 5 |
| **Laravel Sanctum** | **4.x** | API auth للتطبيق والموزعين |
| **Spatie Permission** | **6.x** | Roles & Permissions |
| **Spatie Media Library** | **11.x** | إدارة الصور والملفات |

### Frontend

| التقنية | الإصدار | السبب |
|---------|---------|-------|
| **Tailwind CSS** | **4.1.x** | متوافق مع Filament 5، أسرع 5x، CSS-first |
| **Alpine.js** | **3.x** | خفيف، يتكامل مع Livewire |
| **Vite** | **6.x** | Build tool رسمي من Laravel 12 |

### لماذا هذه الاختيارات؟

**Filament 5 (وليس 3 أو 4):** Filament 3 لا يدعم Tailwind 4. Filament 5 صدر يناير 2026 ويدعم Livewire 4 + Tailwind 4 بشكل native.

**Tailwind 4 (وليس 3):** أسرع 5x في البناء، CSS-first configuration أبسط، دعم RTL أفضل مع logical properties، متوافق مع Filament 5.

**Alpine.js (وليس Vue/React):** يتكامل مع Livewire مثالياً، لا يحتاج build step منفصل، Filament يستخدمه داخلياً.

## 2.2 الحزم المطلوبة

### composer.json

```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "filament/filament": "^5.0",
        "laravel/sanctum": "^4.0",
        "spatie/laravel-permission": "^6.0",
        "spatie/laravel-medialibrary": "^11.0",
        "spatie/laravel-activitylog": "^4.0",
        "simplesoftwareio/simple-qrcode": "^4.0",
        "intervention/image": "^3.0"
    },
    "require-dev": {
        "laravel/pint": "^1.0",
        "laravel/telescope": "^5.0",
        "pestphp/pest": "^3.0",
        "pestphp/pest-plugin-laravel": "^3.0"
    }
}
```

### package.json

```json
{
    "devDependencies": {
        "@tailwindcss/vite": "^4.1",
        "laravel-vite-plugin": "^1.2",
        "tailwindcss": "^4.1",
        "vite": "^6.0"
    },
    "dependencies": {
        "alpinejs": "^3.14"
    }
}
```

## 2.3 بيئة التطوير

```
المسار: C:\Users\Moha4\OneDrive\Desktop\VS COOD\Datropix\maroof_id

PHP 8.2+  |  MySQL 8.0+  |  Node.js 20+  |  npm 10+  |  Composer 2.7+
```

---

# القسم الثالث: بنية المشروع

## 3.1 هيكل المجلدات

```
maroof_id/
├── app/
│   ├── Enums/
│   │   ├── OrderStatus.php
│   │   ├── UserRole.php
│   │   ├── PaymentMethod.php
│   │   └── CardStatus.php
│   │
│   ├── Filament/
│   │   ├── Resources/
│   │   │   ├── UserResource.php
│   │   │   ├── CardResource.php
│   │   │   ├── OrderResource.php
│   │   │   ├── TemplateResource.php
│   │   │   ├── PartnerResource.php
│   │   │   ├── ResellerResource.php
│   │   │   ├── DesignerResource.php
│   │   │   ├── AffiliateResource.php
│   │   │   ├── TransactionResource.php
│   │   │   └── CouponResource.php
│   │   ├── Pages/
│   │   │   ├── Dashboard.php
│   │   │   ├── Analytics.php
│   │   │   └── Settings.php
│   │   └── Widgets/
│   │       ├── StatsOverview.php
│   │       ├── RevenueChart.php
│   │       └── OrdersChart.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   └── ResetPasswordController.php
│   │   │   ├── Public/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── PricingController.php
│   │   │   │   ├── TemplateGalleryController.php
│   │   │   │   └── CardViewController.php
│   │   │   ├── Customer/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── CardController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   ├── AnalyticsController.php
│   │   │   │   └── OrderController.php
│   │   │   ├── Reseller/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── InventoryController.php
│   │   │   │   ├── SaleController.php
│   │   │   │   └── EarningsController.php
│   │   │   ├── Partner/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   └── EarningsController.php
│   │   │   ├── Designer/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── TemplateController.php
│   │   │   │   └── EarningsController.php
│   │   │   └── Api/V1/
│   │   │       ├── CardApiController.php
│   │   │       ├── NfcWriterApiController.php
│   │   │       └── WebhookController.php
│   │   ├── Middleware/
│   │   │   ├── EnsureUserIsCustomer.php
│   │   │   ├── EnsureUserIsReseller.php
│   │   │   ├── EnsureUserIsPartner.php
│   │   │   ├── EnsureUserIsDesigner.php
│   │   │   ├── SetLocale.php
│   │   │   └── TrackCardView.php
│   │   └── Requests/
│   │       ├── Auth/
│   │       ├── Card/
│   │       ├── Order/
│   │       └── Template/
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Card.php
│   │   ├── CardView.php
│   │   ├── Order.php
│   │   ├── Transaction.php
│   │   ├── Template.php
│   │   ├── TemplateCategory.php
│   │   ├── Partner.php
│   │   ├── Reseller.php
│   │   ├── ResellerInventory.php
│   │   ├── ResellerSale.php
│   │   ├── Designer.php
│   │   ├── Affiliate.php
│   │   ├── AffiliateClick.php
│   │   ├── Coupon.php
│   │   ├── Payout.php
│   │   ├── BusinessAccount.php
│   │   └── Notification.php
│   │
│   ├── Services/
│   │   ├── CardService.php
│   │   ├── OrderService.php
│   │   ├── PaymentService.php
│   │   ├── NfcService.php
│   │   ├── PartnerMatchingService.php
│   │   ├── CommissionService.php
│   │   ├── AnalyticsService.php
│   │   ├── ShippingService.php
│   │   └── NotificationService.php
│   │
│   ├── Jobs/
│   │   ├── ProcessOrder.php
│   │   ├── AssignPartner.php
│   │   ├── SendOrderNotification.php
│   │   ├── CalculateMonthlyPayouts.php
│   │   └── GenerateAnalyticsReport.php
│   │
│   ├── Events/
│   │   ├── OrderPlaced.php
│   │   ├── OrderCompleted.php
│   │   ├── CardViewed.php
│   │   └── TemplatePublished.php
│   │
│   ├── Listeners/
│   │   ├── SendOrderConfirmation.php
│   │   ├── NotifyPartner.php
│   │   └── TrackCardAnalytics.php
│   │
│   └── Policies/
│       ├── CardPolicy.php
│       ├── OrderPolicy.php
│       └── TemplatePolicy.php
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── RoleSeeder.php
│   │   ├── AdminSeeder.php
│   │   ├── TemplateCategorySeeder.php
│   │   └── TemplateSeeder.php
│   └── factories/
│
├── resources/
│   ├── css/app.css
│   ├── js/app.js
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── auth.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── reseller.blade.php
│       │   └── card.blade.php
│       ├── public/
│       │   ├── home.blade.php
│       │   ├── pricing.blade.php
│       │   ├── templates.blade.php
│       │   ├── about.blade.php
│       │   ├── contact.blade.php
│       │   └── faq.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── forgot-password.blade.php
│       │   ├── reset-password.blade.php
│       │   └── verify-email.blade.php
│       ├── customer/
│       │   ├── dashboard.blade.php
│       │   ├── cards/ (index, create, edit, analytics)
│       │   ├── orders/ (index, show)
│       │   └── profile/edit.blade.php
│       ├── reseller/
│       │   ├── dashboard.blade.php
│       │   ├── inventory.blade.php
│       │   ├── sales.blade.php
│       │   └── earnings.blade.php
│       ├── card/
│       │   └── show.blade.php
│       ├── components/
│       │   ├── ui/ (button, card, modal, alert, input)
│       │   └── sections/ (hero, features, pricing-table, testimonials, footer)
│       └── emails/
│           ├── order-confirmed.blade.php
│           ├── order-shipped.blade.php
│           ├── welcome.blade.php
│           └── partner-new-order.blade.php
│
├── routes/
│   ├── web.php          # APPEND ONLY!
│   ├── api.php          # APPEND ONLY!
│   ├── auth.php         # Auth routes (separate)
│   ├── customer.php     # Customer routes (separate)
│   ├── reseller.php     # Reseller routes (separate)
│   ├── partner.php      # Partner routes (separate)
│   └── designer.php     # Designer routes (separate)
│
├── config/
│   ├── maroof.php
│   ├── commission.php
│   └── shipping.php
│
├── ai-workspace/
│   ├── reports/daily/
│   ├── reports/features/
│   ├── reports/issues/
│   └── context/
│
├── .env
├── composer.json
├── package.json
└── vite.config.js
```

## 3.2 حل مشكلة web.php (Route Splitting)

**المشكلة:** كل AI يعيد كتابة web.php ويحذف routes سابقة.
**الحل:** routes مقسمة في ملفات منفصلة. كل AI يعمل على ملفه فقط.

**routes/web.php (ثابت - لا يُعاد كتابته):**

```php
<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CRITICAL: DO NOT REWRITE THIS FILE
| Each user type has its own route file
|--------------------------------------------------------------------------
*/

// Public pages
Route::get('/', [App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');
Route::get('/pricing', [App\Http\Controllers\Public\PricingController::class, 'index'])->name('pricing');
Route::get('/templates', [App\Http\Controllers\Public\TemplateGalleryController::class, 'index'])->name('templates');
Route::get('/about', [App\Http\Controllers\Public\HomeController::class, 'about'])->name('about');
Route::get('/contact', [App\Http\Controllers\Public\HomeController::class, 'contact'])->name('contact');
Route::get('/faq', [App\Http\Controllers\Public\HomeController::class, 'faq'])->name('faq');

// Card public view (MUST BE LAST)
Route::get('/{username}', [App\Http\Controllers\Public\CardViewController::class, 'show'])
    ->name('card.public')
    ->where('username', '[a-z0-9\-]+');

// Separated route files
require __DIR__.'/auth.php';
require __DIR__.'/customer.php';
require __DIR__.'/reseller.php';
require __DIR__.'/partner.php';
require __DIR__.'/designer.php';
```

---

# القسم الرابع: قاعدة البيانات

## 4.1 مراحل الـ Migrations (بالترتيب)

```
لا تبدأ المرحلة التالية قبل اكتمال السابقة.
لا تعدّل migration موجود. أنشئ جديد لأي تعديل.
```

**Phase 1 - Core:** users (Laravel default), roles/permissions (Spatie), template_categories, templates

**Phase 2 - Cards & Commerce:** cards, card_social_links, orders, transactions, coupons, coupon_usages

**Phase 3 - Partners & Resellers:** partners, partner_applications, resellers, reseller_inventories, reseller_sales, designers, affiliates, affiliate_clicks, payouts

**Phase 4 - Analytics & B2B:** card_views, card_contacts_saved, business_accounts, business_employees, notifications

## 4.2 الجداول الرئيسية

### users
```
id, name, email (unique), phone (unique nullable), password,
avatar, locale (ar/en), email_verified_at, phone_verified_at,
is_active, remember_token, timestamps, soft_deletes
```

### cards
```
id, user_id (FK), template_id (FK nullable),
username (unique), full_name, job_title, company, email, phone,
website, bio, avatar, cover_image, custom_colors (JSON),
nfc_serial (unique nullable), qr_code,
is_active, is_primary, views_count, contacts_saved_count,
timestamps, soft_deletes
INDEXES: username, user_id, nfc_serial
```

### orders
```
id, order_number (unique), user_id (FK), card_id (FK),
partner_id (FK nullable), reseller_id (FK nullable),
affiliate_id (FK nullable), coupon_id (FK nullable),
status (enum: pending/processing/printing/shipped/delivered/cancelled/refunded),
subtotal, discount, tax, shipping_cost, total,
payment_method (enum: mada/visa/mastercard/stc_pay/apple_pay/cash),
payment_status (enum: pending/paid/failed/refunded),
shipping_name, shipping_phone, shipping_city, shipping_district,
shipping_street, shipping_building, shipping_postal_code,
tracking_number, shipping_provider,
partner_commission, reseller_profit, affiliate_commission,
notes, shipped_at, delivered_at, timestamps, soft_deletes
INDEXES: order_number, status, user_id
```

### templates
```
id, category_id (FK nullable), designer_id (FK nullable),
name, slug (unique), description, thumbnail, preview_url,
html_content, css_content, js_content,
price, is_free, is_rtl, is_responsive, is_active, is_featured,
sales_count, rating, timestamps, soft_deletes
INDEXES: slug, price, is_featured
```

### card_views
```
id, card_id (FK), viewer_ip, viewer_country, viewer_city,
viewer_device, viewer_browser, viewer_os,
source (enum: nfc/qr/link/direct), referrer,
contact_saved, created_at
INDEXES: card_id, created_at
```

### partners
```
id, user_id (FK), business_name, commercial_register,
city, address, latitude, longitude, phone, email,
level (enum: new/active/excellent/gold/platinum),
commission_rate (default 15%), total_orders,
avg_response_hours, rating, is_active, is_verified,
verified_at, timestamps, soft_deletes
```

### resellers
```
id, user_id (FK), city, total_sales, total_revenue,
total_profit, current_inventory, is_active,
timestamps, soft_deletes
```

---

# القسم الخامس: مراحل التنفيذ

## 5.1 نظرة عامة

```
Phase 1: Foundation         أسبوع 1-2    ← نبدأ هنا
Phase 2: Core Product       أسبوع 3-4
Phase 3: Partners           أسبوع 5-6
Phase 4: Resellers          أسبوع 7-8
Phase 5: Designers & Aff.   أسبوع 9-10
Phase 6: Analytics & Polish أسبوع 11-12
```

## 5.2 Phase 1: Foundation

```
1.1  إنشاء مشروع Laravel 12 جديد
1.2  تثبيت كل الحزم (composer + npm)
1.3  إعداد Tailwind 4 + Vite + Alpine.js
1.4  إعداد Filament 5 (Admin Panel)
1.5  إعداد Spatie Permission (Roles)
1.6  Migrations: template_categories, templates
1.7  Seeders: Roles, Admin, Categories, 5 Templates
1.8  Auth system (login, register, forgot password) - custom views
1.9  Layouts (app, auth, dashboard, card)
1.10 Public pages (home, pricing, templates, about, contact, faq)
1.11 Route splitting (web.php + auth.php + customer.php + ...)
1.12 Blade components (button, card, modal, alert, input)
```

## 5.3 Phase 2: Core Product

```
2.1  Migrations: cards, card_social_links, orders, transactions, coupons
2.2  صفحة البطاقة العامة /{username}
2.3  Customer Dashboard
2.4  Card CRUD (create, edit, view, analytics)
2.5  Order flow (طلب → قالب → معلومات → دفع → تأكيد)
2.6  Template gallery مع فلترة
2.7  QR Code generation
2.8  Emails: order confirmed, shipped, welcome
2.9  Filament Resources: Card, Order, Template
```

## 5.4 Phase 3: Partners

```
3.1  Migrations: partners, partner_applications
3.2  Partner registration + verification
3.3  Partner Dashboard (طلبات، حالة، tracking)
3.4  Partner matching (أقرب شريك بالمدينة + التقييم)
3.5  Commission calculation (5 levels)
3.6  Shipping tracking
3.7  Filament: Partner, PartnerApplication Resources
```

## 5.5 Phase 4: Resellers

```
4.1  Migrations: resellers, reseller_inventories, reseller_sales
4.2  Reseller registration + dashboard
4.3  Inventory management
4.4  NFC Writer API (for mobile app)
4.5  Sale recording
4.6  Payout system
4.7  Filament: Reseller, ResellerSale Resources
```

## 5.6 Phase 5: Designers & Affiliates

```
5.1  Migrations: designers, affiliates, affiliate_clicks
5.2  Designer portal (upload → review → publish)
5.3  Template approval workflow in Filament
5.4  Affiliate system (referral link, coupon, tracking)
5.5  Payout integration
5.6  Filament: Designer, Affiliate Resources
```

## 5.7 Phase 6: Analytics & Polish

```
6.1  Migrations: card_views, card_contacts_saved, business_accounts
6.2  Card view analytics
6.3  Dashboard widgets in Filament
6.4  SMS integration
6.5  Performance optimization
6.6  Security audit
6.7  B2B basic features
```

---

# القسم السادس: أول أوامر التنفيذ (Phase 1)

```bash
cd "C:\Users\Moha4\OneDrive\Desktop\VS COOD\Datropix"

# 1. Create project
composer create-project laravel/laravel maroof_id "12.*"
cd maroof_id

# 2. PHP packages
composer require filament/filament:"^5.0"
composer require laravel/sanctum
composer require spatie/laravel-permission
composer require spatie/laravel-medialibrary
composer require spatie/laravel-activitylog
composer require simplesoftwareio/simple-qrcode
composer require intervention/image
composer require --dev laravel/pint
composer require --dev pestphp/pest pestphp/pest-plugin-laravel

# 3. Frontend packages
npm install
npm install alpinejs
npm install -D tailwindcss @tailwindcss/vite

# 4. Filament setup
php artisan filament:install --panels

# 5. Spatie setup
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# 6. Configure .env → DB_DATABASE=maroof_id

# 7. Migrate + Seed
php artisan migrate
php artisan db:seed

# 8. Create admin
php artisan make:filament-user

# 9. Build assets
npm run build

# 10. Start
php artisan serve
```

**Tailwind 4 (resources/css/app.css):**
```css
@import "tailwindcss";

@theme {
    --color-maroof-primary: #1a365d;
    --color-maroof-secondary: #2b6cb0;
    --color-maroof-accent: #ed8936;
    --color-maroof-gold: #d69e2e;
    --color-maroof-dark: #1a202c;
    --color-maroof-gray: #718096;
    --color-maroof-light: #f7fafc;
    --color-maroof-success: #38a169;
    --color-maroof-warning: #dd6b20;
    --color-maroof-danger: #e53e3e;
    --color-maroof-info: #3182ce;
    --font-sans: "IBM Plex Sans Arabic", "Inter", sans-serif;
    --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --radius-card: 0.75rem;
    --radius-button: 0.5rem;
}

[dir="rtl"] { text-align: right; }
```

**Vite (vite.config.js):**
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

**Alpine.js (resources/js/app.js):**
```javascript
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

---

# القسم السابع: معايير الكود

```
Models:       Singular PascalCase      → Card.php
Controllers:  PascalCase + Controller  → CardController.php
Migrations:   date_sequence_name       → 2026_02_10_000001_create_cards_table.php
Views:        kebab-case               → card-create.blade.php
Routes:       kebab-case URLs          → /dashboard/my-cards
DB Tables:    plural snake_case        → cards, reseller_sales
DB Columns:   snake_case               → full_name, job_title
Enums:        PascalCase               → OrderStatus.php
Services:     PascalCase + Service     → CardService.php
```

**أنماط إلزامية:**
- Enums للـ statuses (ليس strings)
- Form Requests للـ validation (ليس inline)
- Services للـ business logic (ليس fat controllers)
- Anonymous Blade Components للـ UI
- Policies للـ authorization

---

# القسم الثامن: تعليمات الـ AI

## قبل العمل
```
1. اقرأ هذا الملف + MAROOF-MASTER-DOCUMENT.md
2. اقرأ آخر تقرير يومي
3. حدد Phase + المهمة بالضبط
4. تحقق من الملفات الموجودة
```

## أثناء العمل
```
1. القواعد الذهبية (القسم صفر) دائماً
2. مهمة واحدة في كل مرة
3. لا تلمس ملفات خارج نطاق مهمتك
4. مشكلة في كود سابق؟ سجلها ولا تصلحها إلا إذا طُلب
```

## بعد العمل
```
1. تقرير يومي (ai-workspace/reports/daily/)
2. سجل كل ملف أنشأته/عدّلته
3. حدد المهام التالية
```

## ممنوعات
```
- حذف/إعادة كتابة web.php
- تعديل migration موجود
- نص عربي أو emojis في الكود
- مسارات ملفات جزئية
- العمل بدون قراءة الملفات الموجودة أولاً
```

---

# القسم التاسع: أوامر مرجعية

```bash
npm run dev              # Vite dev
php artisan serve        # Laravel server
npm run build            # Production build
php artisan migrate      # Migrations
php artisan db:seed      # Seeders
php artisan route:list   # List routes
php artisan route:clear  # Clear route cache
php artisan config:clear # Clear config cache
php artisan cache:clear  # Clear all cache
./vendor/bin/pint        # Code formatting
php artisan test         # Tests
```

---

**نهاية الملف**
**آخر تحديث:** 10 فبراير 2026
