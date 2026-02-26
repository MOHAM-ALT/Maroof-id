# 🌐 Phase 5: Frontend & Public Pages - الخطة العملية

**التاريخ:** 16 فبراير 2026
**الوقت المتوقع:** 12-15 ساعة
**الحالة:** 🔄 قيد العمل
**الأولوية:** 🔴 عالية جداً

---

## 📊 نظرة عامة

### الهدف:
بناء واجهة المستخدم العامة (Public Frontend) للمنصة باستخدام Laravel Blade + Tailwind CSS + Alpine.js.

### ما سبق:
- ✅ Phase 1-3: Database, Models, Seeders (100%)
- ✅ Phase 4: Filament Admin Dashboard (100%)

### ما نحتاج:
- ❌ Frontend Layout (Header, Footer, Navigation)
- ❌ Home Page
- ❌ Browse Templates Page
- ❌ Template Details Page
- ❌ About/Contact/Pricing Pages
- ❌ Authentication Pages (Register, Login)

---

## 🎯 خطة العمل - 5 مراحل

### المرحلة 1: Frontend Setup (2 ساعات) 🔴
### المرحلة 2: Layouts & Components (2 ساعات) 🔴
### المرحلة 3: Home & Templates Pages (4 ساعات) 🟡
### المرحلة 4: Static Pages (2 ساعات) 🟡
### المرحلة 5: Authentication Pages (3 ساعات) 🟢

---

# 📋 المرحلة 1: Frontend Setup

**⏰ الوقت:** 2 ساعات
**الأولوية:** 🔴 عالية جداً

## 1.1 - Tailwind Configuration

**الملف:** `tailwind.config.js`

### التكوين المطلوب:

```javascript
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e',
        },
        secondary: {
          50: '#fdf4ff',
          100: '#fae8ff',
          200: '#f5d0fe',
          300: '#f0abfc',
          400: '#e879f9',
          500: '#d946ef',
          600: '#c026d3',
          700: '#a21caf',
          800: '#86198f',
          900: '#701a75',
        },
      },
      fontFamily: {
        sans: ['Cairo', 'system-ui', 'sans-serif'],
        display: ['Tajawal', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

---

## 1.2 - App Layout Setup

**الملف:** `resources/css/app.css`

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  body {
    @apply font-sans antialiased bg-gray-50 text-gray-900;
    direction: rtl;
  }

  h1, h2, h3, h4, h5, h6 {
    @apply font-display font-bold;
  }
}

@layer components {
  .btn {
    @apply px-4 py-2 rounded-lg font-medium transition-colors;
  }

  .btn-primary {
    @apply bg-primary-600 text-white hover:bg-primary-700;
  }

  .btn-secondary {
    @apply bg-gray-200 text-gray-900 hover:bg-gray-300;
  }

  .container-custom {
    @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8;
  }
}
```

---

# 📋 المرحلة 2: Layouts & Components

**⏰ الوقت:** 2 ساعات
**الأولوية:** 🔴 عالية

## 2.1 - Main Layout

**الملف:** `resources/views/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'معروف - منصة البطاقات الذكية')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Tajawal:wght@700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
    <!-- Header -->
    @include('components.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    @stack('scripts')
</body>
</html>
```

---

## 2.2 - Header Component

**الملف:** `resources/views/components/header.blade.php`

```blade
<header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <nav class="container-custom">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-display font-bold text-primary-600">
                    معروف
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8 space-x-reverse">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary-600">الرئيسية</a>
                <a href="{{ route('templates.index') }}" class="text-gray-700 hover:text-primary-600">القوالب</a>
                <a href="{{ route('pricing') }}" class="text-gray-700 hover:text-primary-600">الأسعار</a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-primary-600">من نحن</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-primary-600">تواصل معنا</a>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4 space-x-reverse">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">إنشاء حساب</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">لوحة التحكم</a>
                @endguest
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden py-4 space-y-2">
            <a href="{{ route('home') }}" class="block py-2 text-gray-700">الرئيسية</a>
            <a href="{{ route('templates.index') }}" class="block py-2 text-gray-700">القوالب</a>
            <a href="{{ route('pricing') }}" class="block py-2 text-gray-700">الأسعار</a>
            <a href="{{ route('about') }}" class="block py-2 text-gray-700">من نحن</a>
            <a href="{{ route('contact') }}" class="block py-2 text-gray-700">تواصل معنا</a>
        </div>
    </nav>
</header>
```

---

## 2.3 - Footer Component

**الملف:** `resources/views/components/footer.blade.php`

```blade
<footer class="bg-gray-900 text-white mt-20">
    <div class="container-custom py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-xl font-bold mb-4">معروف</h3>
                <p class="text-gray-400">منصة البطاقات الذكية الأولى في المملكة</p>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-bold mb-4">روابط سريعة</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('templates.index') }}" class="hover:text-white">القوالب</a></li>
                    <li><a href="{{ route('pricing') }}" class="hover:text-white">الأسعار</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white">من نحن</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="font-bold mb-4">الدعم</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('contact') }}" class="hover:text-white">تواصل معنا</a></li>
                    <li><a href="#" class="hover:text-white">الأسئلة الشائعة</a></li>
                    <li><a href="#" class="hover:text-white">مركز المساعدة</a></li>
                </ul>
            </div>

            <!-- Social -->
            <div>
                <h4 class="font-bold mb-4">تابعنا</h4>
                <div class="flex space-x-4 space-x-reverse">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2026 معروف. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</footer>
```

---

# 📋 المرحلة 3: Home & Templates Pages

**⏰ الوقت:** 4 ساعات
**الأولوية:** 🟡 متوسطة-عالية

## 3.1 - Home Page

**الملف:** `resources/views/home.blade.php`

```blade
@extends('layouts.app')

@section('title', 'معروف - منصة البطاقات الذكية')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-20">
    <div class="container-custom">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl font-display font-bold mb-6">
                    بطاقتك الذكية<br>في دقائق
                </h1>
                <p class="text-xl mb-8 text-primary-100">
                    أنشئ بطاقة تعريف رقمية احترافية، شاركها مع الجميع، وتتبع من شاهدها
                </p>
                <div class="flex space-x-4 space-x-reverse">
                    <a href="{{ route('register') }}" class="btn bg-white text-primary-600 hover:bg-gray-100">
                        ابدأ الآن مجاناً
                    </a>
                    <a href="{{ route('templates.index') }}" class="btn border-2 border-white text-white hover:bg-white hover:text-primary-600">
                        تصفح القوالب
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <img src="/images/hero-illustration.svg" alt="Hero" class="w-full">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20">
    <div class="container-custom">
        <h2 class="text-3xl font-display font-bold text-center mb-12">لماذا معروف؟</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">سريع وسهل</h3>
                <p class="text-gray-600">أنشئ بطاقتك في أقل من 5 دقائق</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">قوالب احترافية</h3>
                <p class="text-gray-600">اختر من بين عشرات القوالب الجاهزة</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">تحليلات متقدمة</h3>
                <p class="text-gray-600">تتبع من شاهد بطاقتك ومتى</p>
            </div>
        </div>
    </div>
</section>

<!-- Templates Preview -->
<section class="bg-gray-50 py-20">
    <div class="container-custom">
        <div class="flex justify-between items-center mb-12">
            <h2 class="text-3xl font-display font-bold">القوالب الشائعة</h2>
            <a href="{{ route('templates.index') }}" class="text-primary-600 hover:text-primary-700">
                عرض الكل ←
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($featuredTemplates as $template)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <img src="{{ $template->preview_image }}" alt="{{ $template->name_ar }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-2">{{ $template->name_ar }}</h3>
                    <p class="text-gray-600 mb-4">{{ $template->description_ar }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary-600 font-bold">{{ $template->price }} ر.س</span>
                        <a href="{{ route('templates.show', $template) }}" class="btn btn-primary btn-sm">
                            معاينة
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20">
    <div class="container-custom text-center">
        <h2 class="text-4xl font-display font-bold mb-6">جاهز للبدء؟</h2>
        <p class="text-xl text-gray-600 mb-8">أنشئ بطاقتك الآن مجاناً</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
            ابدأ الآن
        </a>
    </div>
</section>
@endsection
```

---

## 3.2 - Templates Index Page

**الملف:** `resources/views/templates/index.blade.php`

```blade
@extends('layouts.app')

@section('title', 'القوالب - معروف')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container-custom">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-display font-bold mb-4">تصفح القوالب</h1>
            <p class="text-gray-600">اختر من بين مئات القوالب الاحترافية</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="grid md:grid-cols-4 gap-4">
                <select class="form-select rounded-lg">
                    <option>جميع الفئات</option>
                    <option>أعمال</option>
                    <option>شخصي</option>
                    <option>احترافي</option>
                </select>

                <select class="form-select rounded-lg">
                    <option>الأحدث</option>
                    <option>الأكثر مبيعاً</option>
                    <option>الأعلى تقييماً</option>
                </select>

                <select class="form-select rounded-lg">
                    <option>جميع الأسعار</option>
                    <option>مجاني</option>
                    <option>مدفوع</option>
                </select>

                <input type="search" placeholder="ابحث..." class="form-input rounded-lg">
            </div>
        </div>

        <!-- Templates Grid -->
        <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($templates as $template)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <img src="{{ $template->preview_image }}" alt="{{ $template->name_ar }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <span class="text-xs bg-primary-100 text-primary-600 px-2 py-1 rounded">{{ $template->category->name_ar }}</span>
                    <h3 class="font-bold mt-2 mb-1">{{ $template->name_ar }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($template->description_ar, 60) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-primary-600">
                            @if($template->price == 0)
                                مجاني
                            @else
                                {{ $template->price }} ر.س
                            @endif
                        </span>
                        <a href="{{ route('templates.show', $template) }}" class="text-primary-600 hover:text-primary-700">
                            معاينة →
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">لا توجد قوالب متاحة حالياً</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $templates->links() }}
        </div>
    </div>
</div>
@endsection
```

---

## ملخص المرحلة 3:
- ✅ Home Page مع Hero, Features, Templates Preview, CTA
- ✅ Templates Index مع Filters, Grid, Pagination
- ⏳ Template Details Page (التالي)

---

# 📋 المرحلة 4: Static Pages

**⏰ الوقت:** 2 ساعات
**الأولوية:** 🟡 متوسطة

## الصفحات المطلوبة:
1. About Us - من نحن
2. Contact - تواصل معنا
3. Pricing - الأسعار
4. Privacy Policy - سياسة الخصوصية
5. Terms of Service - شروط الاستخدام

---

# 📋 المرحلة 5: Authentication Pages

**⏰ الوقت:** 3 ساعات
**الأولوية:** 🟢 متوسطة-منخفضة

## الصفحات المطلوبة:
1. Register - التسجيل
2. Login - تسجيل الدخول
3. Forgot Password - نسيت كلمة المرور
4. Email Verification - تأكيد البريد

---

## 📊 نسبة الإنجاز

| المرحلة | الوقت | الحالة |
|---------|-------|--------|
| 1. Frontend Setup | 2 ساعات | ⏳ |
| 2. Layouts & Components | 2 ساعات | ⏳ |
| 3. Home & Templates | 4 ساعات | ⏳ |
| 4. Static Pages | 2 ساعات | ⏳ |
| 5. Auth Pages | 3 ساعات | ⏳ |
| **الإجمالي** | **13 ساعة** | **0%** |

---

## 🎯 الخطوات التالية

بعد إكمال Phase 5:
1. Phase 6: Customer Dashboard & Card Builder
2. Phase 7: Payment Integration (Tap)
3. Phase 8: Testing & Deployment

---

**التقرير بواسطة:** Claude Sonnet 4.5
**التاريخ:** 16 فبراير 2026
