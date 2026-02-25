# 🚀 Phase 2: Quick Start Guide

## 📋 الخطوات (5 دقائق فقط!)

### 1. نسخ/لصق هذه الأوامر (دقيقة واحدة):

```bash
php artisan make:model Card -m && \
php artisan make:model Template -m && \
php artisan make:model TemplateCategory -m && \
php artisan make:model Order -m && \
php artisan make:model CardView -m && \
php artisan make:model CardSocialLink -m && \
php artisan make:model Transaction -m
```

✅ **النتيجة:** 7 Models + 7 Migrations

---

### 2. عدّل Migrations (30-60 دقيقة)

افتح: `database/migrations/`

راجع المستند: `MAROOF-MASTER-DOCUMENT.md` (Section 5.1)

---

### 3. شغّل Migrations (دقيقة واحدة):

```bash
php artisan migrate
```

أو لإعادة البناء:

```bash
php artisan migrate:fresh
```

---

### 4. نسخ/لصق هذه الأوامر (دقيقة واحدة):

```bash
php artisan make:filament-resource Card --generate && \
php artisan make:filament-resource Template --generate && \
php artisan make:filament-resource TemplateCategory --generate && \
php artisan make:filament-resource Order --generate
```

✅ **النتيجة:** 4 Resources (16 ملف)

---

### 5. نسخ/لصق هذه الأوامر (دقيقة واحدة):

```bash
php artisan make:seeder RoleSeeder && \
php artisan make:seeder TemplateCategorySeeder && \
php artisan make:seeder BasicTemplateSeeder
```

✅ **النتيجة:** 3 Seeders

---

### 6. اكتب محتوى Seeders (60 دقيقة)

افتح: `database/seeders/`

راجع الأمثلة في التقرير

---

### 7. شغّل Seeders (دقيقة واحدة):

```bash
php artisan db:seed
```

أو مع migrate:fresh:

```bash
php artisan migrate:fresh --seed
```

---

## ⏱️ الوقت الإجمالي

- إنشاء ملفات: **5 دقائق** ✅
- تعديل Migrations: **30-60 دقيقة** 🔴
- كتابة Seeders: **60 دقيقة** 🟡
- **الإجمالي: 2-3 ساعات**

---

## 🎯 اختبار سريع

بعد الانتهاء:

```bash
php artisan route:list --path=admin
php artisan migrate:status
```

افتح: http://localhost:8000/admin

✅ يجب أن تشاهد Resources في القائمة!

---

## 📁 الملفات الموجودة

- `temp-model-commands.txt` - أوامر Models منفصلة
- `temp-resource-commands.txt` - أوامر Resources منفصلة
- `temp-seeder-commands.txt` - أوامر Seeders منفصلة
- `temp-phase2-all-commands.txt` - جميع الأوامر معاً
- `ai-workspace/reports/daily/2026-02-11-phase2.md` - التقرير الشامل

---

## 💡 نصيحة

إذا تريد سرعة:
1. شغّل أوامر Models (1 دقيقة)
2. عدّل migration واحد فقط (Card) (5 دقائق)
3. migrate
4. شغّل CardResource فقط (1 دقيقة)
5. اختبر في Admin Panel

ثم أكمل الباقي لاحقاً! 🚀
