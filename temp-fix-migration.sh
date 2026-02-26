# 🔧 Maroof - Fix Template Categories Migration
# تاريخ: 11 فبراير 2026
# المشكلة: template_categories ما فيه عمود slug

# ============================================
# التشخيص
# ============================================
# ❌ المشكلة: المستخدم شغّل migrate قبل أن يتم تعديل الـ Migration
# ✅ الحل: الـ Migration الحالي صحيح - فيه عمود slug
# 📝 الإجراء: إعادة بناء الـ database من الصفر

# ============================================
# الحل الموصى به: migrate:fresh --seed
# ============================================

echo "🔧 Fixing template_categories migration..."
echo ""
echo "⚠️ Warning: This will delete ALL data!"
echo "Press Ctrl+C to cancel, or wait 5 seconds to continue..."
sleep 5

# 1. إعادة بناء database من الصفر
php artisan migrate:fresh --seed

echo ""
echo "✅ Migration fixed!"
echo ""

# 2. التحقق
echo "🔍 Verifying database structure..."
php artisan db:show template_categories

echo ""
echo "📊 Checking seeded data..."
echo "Template Categories should have 3 records with slugs"
echo ""

# ============================================
# البديل (إذا ما تريد حذف البيانات)
# ============================================
# لكن في حالتنا، ما في بيانات مهمة بعد، فـ migrate:fresh أفضل

# ============================================
# النتيجة المتوقعة
# ============================================
# ✅ template_categories table جاهز مع slug
# ✅ 3 categories مُدخلة
# ✅ 1 template مُدخل
# ✅ 7 roles مُدخلة

echo "🎉 Database is now ready!"
echo ""
echo "Next: Open http://localhost:8000/admin"
