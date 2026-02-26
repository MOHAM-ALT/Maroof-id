# Claude → Continue: Add Routes Request

**Date:** 2026-02-01 14:30
**From:** Claude Desktop  
**To:** Continue (via PM)

---

## Task for Continue

Add these routes to `routes/web.php`:
```php
Route::post('/ratings', [RatingController::class, 'store']);
Route::put('/ratings/{rating}', [RatingController::class, 'update']);
Route::delete('/ratings/{rating}', [RatingController::class, 'destroy']);
```

## Important Instructions

**Method:** Append ONLY (do not replace file)
**Location:** Add after line 25  
**Preserve:** All existing routes

---

## For PM

Please copy the routes above and tell Continue:
"Add these to web.php using str_replace - append only"

---

**Status:** ⏳ Waiting for PM to relay
```

**أنت:**
1. تقرأ الملف
2. تفتح Continue
3. تكتب:
```
From Claude:

Add these routes to routes/web.php (append only, line 25):

[تلصق الـ routes]

Method: str_replace - add after existing routes
Do NOT replace the file
```

4. Continue يرد
5. تنسخ رد Continue
6. ترجع لـ Claude وتلصقه

---

## ✅ الخلاصة النهائية

### **دورك في 4 نقاط:**

1. **صباحاً:**
   - اقرأ `reports/daily/YYYY-MM-DD.md`
   - شوف `tasks/blocked/` (لو فيه ملفات → قرر)

2. **أثناء اليوم:**
   - لو شفت `🚨` → تدخل فوراً
   - لو AI محتاج AI ثاني → انقل الطلب (copy-paste من `conversations/`)

3. **مساءً:**
   - اقرأ التقرير اليومي (اطمئن)

4. **أسبوعياً:**
   - اقرأ `reports/weekly/` (نظرة شاملة)

---

### **الملفات الرئيسية:**
```
MAROOF-COMPLETE-PROJECT-SPEC.md    ← في root (مواصفات المشروع)
ai-workspace/
  ├── .ai-instructions/            ← قواعد الـ AIs
  │     ├── claude-desktop.md
  │     ├── continue.md
  │     ├── laravel-boost.md
  │     └── mohammad-pm.md         ← دورك! (سأكتبه الآن)
  ├── reports/daily/               ← تقارير يومية
  ├── tasks/blocked/               ← محتاجة قرارك
  └── conversations/               ← طلبات بين AIs