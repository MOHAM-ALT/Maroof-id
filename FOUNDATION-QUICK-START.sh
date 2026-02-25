# 🚀 Maroof Foundation - Quick Commands
# نسخ/لصق للتنفيذ السريع

echo "🎉 Foundation Complete! جاهز للـ Migration..."
echo ""

# ============================================
# Step 1: Migration (5 ثوانٍ)
# ============================================

echo "📦 Step 1: Running Migrations..."
php artisan migrate

echo "✅ Migrations completed!"
echo ""

# ============================================
# Step 2: Seeding (2 ثوانٍ)
# ============================================

echo "🌱 Step 2: Running Seeders..."
php artisan db:seed

echo "✅ Seeders completed!"
echo ""

# ============================================
# Step 3: Verification
# ============================================

echo "🔍 Step 3: Verifying..."
echo ""
echo "Checking migrations..."
php artisan migrate:status
echo ""

echo "Checking routes..."
php artisan route:list --path=admin | head -20
echo ""

# ============================================
# Step 4: Test in Browser
# ============================================

echo "🌐 Step 4: Open Admin Panel"
echo ""
echo "URL: http://localhost:8000/admin"
echo ""
echo "You should see:"
echo "  ✅ Cards Resource"
echo "  ✅ Templates Resource (1 template)"
echo "  ✅ Template Categories Resource (3 categories)"
echo "  ✅ Orders Resource"
echo ""

# ============================================
# Optional: Clear Cache
# ============================================

echo "🧹 Clearing cache..."
php artisan optimize:clear

echo ""
echo "✅ All done! Foundation is ready!"
echo ""
echo "📝 Next steps:"
echo "  1. Open http://localhost:8000/admin"
echo "  2. Create your first card"
echo "  3. Review resources"
echo ""
