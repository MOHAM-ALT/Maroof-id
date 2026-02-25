-- Maroof Database Check Queries
-- تاريخ: 11 فبراير 2026

-- ============================================
-- فحص Users
-- ============================================

-- 1. عرض جميع المستخدمين
SELECT * FROM users;

-- 2. البحث عن super admin
SELECT * FROM users WHERE email = 'mohammed.qahtani.n@gmail.com';

-- 3. عد المستخدمين
SELECT COUNT(*) as total_users FROM users;

-- ============================================
-- فحص Roles (من Spatie Permission)
-- ============================================

-- 4. عرض جميع الأدوار
SELECT * FROM roles;

-- 5. البحث عن super_admin role
SELECT * FROM roles WHERE name = 'super_admin';

-- 6. البحث عن panel_user role
SELECT * FROM roles WHERE name = 'panel_user';

-- ============================================
-- فحص Model Has Roles
-- ============================================

-- 7. عرض جميع User-Role mappings
SELECT 
    u.id, 
    u.name, 
    u.email, 
    r.name as role_name
FROM users u
LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id
LEFT JOIN roles r ON mhr.role_id = r.id
WHERE mhr.model_type = 'App\\Models\\User';

-- 8. فحص المستخدم الأول
SELECT * FROM model_has_roles WHERE model_id = 1;

-- ============================================
-- فحص Permissions
-- ============================================

-- 9. عد الصلاحيات
SELECT COUNT(*) as total_permissions FROM permissions;

-- 10. عرض أول 20 صلاحية
SELECT * FROM permissions LIMIT 20;

-- ============================================
-- ملاحظات
-- ============================================
-- استخدم هذه الـ queries في:
-- 1. SQLite Browser (إذا تستخدم SQLite)
-- 2. php artisan tinker (مع DB::select())
-- 3. MySQL Workbench (إذا تستخدم MySQL)
