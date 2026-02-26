# Maroof ID - Improvement Plan & Status Report
# Date: 2026-02-23 (Session 5 Update)

---

## Current Tech Stack
- Laravel 12.51.0 | PHP 8.3.0 | SQLite
- Filament 5.x | Livewire 4.x | Tailwind 4.x | Alpine.js 3.x
- Spatie Permission 6.x | FilamentShield

## Login Credentials
- Admin: admin@maroof.local / admin123
- Demo Admin: admin@maroof.sa / password
- Server: http://127.0.0.1:8000/admin

---

## PART 1: Current Status by Phase

### Phase 1: Foundation [100%] ✅
| Item | Status | Notes |
|------|--------|-------|
| Laravel 12 project | DONE | |
| Filament 5 Admin Panel | DONE | |
| Spatie Permission + Shield | DONE | |
| Auth (login/register/forgot) | DONE | Custom views |
| Layouts (app/auth/public) | DONE | Role-aware navigation |
| Public pages (home/pricing/templates/about/contact) | DONE | |
| Template Categories seeder | DONE | |
| Basic Templates seeder | DONE | |
| Roles seeder | DONE | 7 roles: customer/super_admin/print_partner/reseller/designer/affiliate/business |
| Enums (OrderStatus/UserRole/etc) | DONE | 6 enums |
| Role switching system | DONE | Role selector + session-based active role |

### Phase 2: Core Product [90%] ✅
| Item | Status | Notes |
|------|--------|-------|
| Cards migration + model + CRUD | DONE | Full 6-step form (template/personal/contact/social/images/settings) |
| Card public page (/card/{slug}) | DONE | Mobile-first standalone page + analytics tracking |
| Orders migration + model + CRUD | DONE | With shipping info + Saudi cities + price calculator |
| Templates migration + model + CRUD | DONE | |
| Customer Dashboard | DONE | Stats + quick actions |
| Template gallery with filters | DONE | |
| QR Code generation | DONE | External API (api.qrserver.com) |
| Email templates (4) | DONE | welcome/order/payment/payout |
| Filament Resources (all) | DONE | Card/Order/Template/Partner/Reseller/Designer/Affiliate/Coupon/Payout/Role/User |
| Payment integration (Tap/STC Pay) | NOT DONE | Only mock payment flow - views ready |
| vCard download | DONE | |
| Payment pages (checkout/success/failed) | DONE | Views created, need gateway SDK |

### Phase 3: Partners [95%] ✅
| Item | Status | Notes |
|------|--------|-------|
| Partners migration + model | DONE | |
| Partner Filament Resource | DONE | |
| Partner registration + verification | PARTIAL | Via admin role assignment |
| Partner Dashboard (frontend) | DONE | Dashboard + Orders list + Order detail |
| Partner order status updates | DONE | Processing/Shipped/Delivered with tracking number |
| Partner matching (nearest city) | DONE | Haversine formula + city regions + load balancing |
| Commission calculation (5 levels) | DONE | Multi-role: partner/designer/affiliate/reseller |
| Partner payouts page | DONE | Shared PayoutController with period filters |

### Phase 4: Resellers [90%] ✅
| Item | Status | Notes |
|------|--------|-------|
| Resellers migration + model | DONE | |
| Reseller Filament Resource | DONE | |
| Reseller Inventory model | DONE | |
| Reseller Sales model | DONE | |
| Reseller Dashboard (frontend) | DONE | Stats + inventory + sales |
| Record sales + auto commission | DONE | Commission auto-calculated from rate |
| NFC Writer API | NOT DONE | |
| Payout system | DONE | Full logic: create/process/history + payouts page |
| Reseller payouts page | DONE | |

### Phase 5: Designers & Affiliates [95%] ✅
| Item | Status | Notes |
|------|--------|-------|
| Designers migration + model | DONE | |
| Designer Filament Resource | DONE | |
| Designer Dashboard (frontend) | DONE | Stats + templates grid |
| Designer Template CRUD | DONE | Create/edit with preview image upload |
| Template approval workflow | PARTIAL | Created as inactive, admin approves via Filament |
| Designer royalties | DONE | 20% auto-calculated on order payment |
| Affiliates migration + model | DONE | |
| Affiliate Filament Resource | DONE | |
| Affiliate Dashboard (frontend) | DONE | Referral link + stats + countries + clicks |
| Affiliate Clicks tracking page | DONE | With filters (all/converted/not converted) |
| Affiliate commission on orders | DONE | Auto-converts clicks + generates payouts |
| Designer/Affiliate payouts page | DONE | |

### Phase 6: Analytics & Admin [90%] ✅
| Item | Status | Notes |
|------|--------|-------|
| card_views migration + model | DONE | Uses viewed_at (not created_at) |
| Dashboard StatsOverview widget | DONE | Users/Cards/Orders/Revenue |
| Dashboard Revenue chart | DONE | Line chart (30 days) |
| Dashboard Orders chart | DONE | Doughnut (statuses) |
| Dashboard Device stats | DONE | Doughnut (mobile/desktop/tablet) |
| Dashboard User growth | DONE | Line chart (12 months) |
| Dashboard World map | DONE | jsvectormap - improved script loading |
| Analytics page (separate) | DONE | 5 widgets (fixed SQLite compatibility) |
| Settings page (admin) | DONE | Fixed Filament 5 Tabs/Schema imports |
| Demo data seeder | DONE | 50 users + 12 templates + 80 cards + 500 views + 60 orders |
| README.md | DONE | Full project documentation in Arabic |
| SMS integration | NOT DONE | |
| Performance optimization | NOT DONE | |
| Security audit | NOT DONE | |

---

## PART 2: Bugs Fixed (All Sessions)

### Session 1-3 Bugs (20 bugs)
| # | Bug | Status |
|---|-----|--------|
| 1 | World map not rendering | DEFERRED (jsvectormap CDN loaded) |
| 2 | BadgeColumn deprecated in 6 resources | **FIXED** |
| 3 | Partner model missing SoftDeletes | **FIXED** |
| 4 | Designer model missing SoftDeletes | **FIXED** |
| 5 | Order model missing relationships | **FIXED** |
| 6 | Template model missing designer() | **FIXED** |
| 7 | User model has no relationships | **FIXED** |
| 8 | UserForm password required on edit | **FIXED** |
| 9 | Duplicate RoleResource | **FIXED** |
| 10 | Orphaned widgets | **FIXED** |
| 11 | Unused BadgeColumn import | **FIXED** |
| 12 | SQLite DAY() function error (Analytics) | **FIXED** - strftime('%d') |
| 13 | Settings page Form vs Schema type | **FIXED** - Filament\Schemas\Schema |
| 14 | Settings page Tabs import wrong | **FIXED** - Filament\Schemas\Components\Tabs |
| 15 | HourlyTrafficWidget created_at vs viewed_at | **FIXED** |
| 16 | AnalyticsStatsWidget created_at vs viewed_at | **FIXED** |
| 17 | CardService wrong relation names | **FIXED** - views()/socialLinks() |
| 18 | CardService broken QR route | **FIXED** - url('/nfc/') |
| 19 | Policies blocking customers | **FIXED** - ownership checks added |
| 20 | Dead links in dashboard/templates | **FIXED** |
| 21 | Role selector wrong role names | **FIXED** - print_partner/super_admin |

### Session 5 Bugs - Filament 5 Type Errors (CRITICAL)
| # | Bug | Status |
|---|-----|--------|
| 22 | UserResource $navigationGroup type mismatch (CRASH) | **FIXED** - `\UnitEnum\|string\|null` |
| 23 | All 11 Resources $navigationIcon wrong type `?string` | **FIXED** - `string\|\BackedEnum\|null` |
| 24 | All 11 Resources $navigationGroup wrong type `?string` | **FIXED** - `\UnitEnum\|string\|null` |
| 25 | Dashboard $navigationIcon wrong type | **FIXED** |
| 26 | Dashboard getColumns() wrong return type (has `string`) | **FIXED** - `array\|int` |
| 27 | Settings.php $navigationGroup mangled by sed | **FIXED** - restored proper types |

### Session 5 Bugs - Enum String Comparisons (15+ files)
| # | Bug | Status |
|---|-----|--------|
| 28 | CommissionService: missing PayoutStatus/GeneralStatus imports | **FIXED** |
| 29 | CommissionService: user_id => 0 FK violation | **FIXED** - null check + return null |
| 30 | CommissionService: string 'pending'/'completed' vs enum | **FIXED** - PayoutStatus enum |
| 31 | CommissionService: 'active' vs GeneralStatus::Active | **FIXED** |
| 32 | CommissionService: SQLite whereMonth/whereYear | **FIXED** - where('>=', startOfMonth()) |
| 33 | OrderService: cancelOrder() non-existent columns | **FIXED** - removed cancellation_reason/cancelled_at |
| 34 | OrderService: 'paid'/'cancelled' strings vs enum | **FIXED** - PaymentStatus/OrderStatus enums |
| 35 | AnalyticsService: 'completed' vs PaymentStatus::Paid (6x) | **FIXED** |
| 36 | AnalyticsService: template_id column doesn't exist | **FIXED** - card_id |
| 37 | AnalyticsService: visitor_ip column doesn't exist | **FIXED** - ip_address |
| 38 | AnalyticsService: created_at vs viewed_at in card_views | **FIXED** |
| 39 | AnalyticsService: clicks vs clicks_count column | **FIXED** |
| 40 | AnalyticsService: users.role groupBy (no role column) | **FIXED** - Spatie model_has_roles join |
| 41 | StatsOverviewWidget: SQLite + enum fixes | **FIXED** |
| 42 | OrdersChartWidget: string statuses vs enums | **FIXED** - OrderStatus enum |
| 43 | RevenueChartWidget: 'paid' vs PaymentStatus::Paid | **FIXED** |
| 44 | AnalyticsStatsWidget: 'paid' vs PaymentStatus::Paid | **FIXED** |
| 45 | UserGrowthWidget: SQLite whereYear/whereMonth | **FIXED** - whereBetween |
| 46 | MonthlyRevenueComparisonWidget: SQLite + enum | **FIXED** |
| 47 | PaymentController: webhook blocked by auth middleware | **FIXED** - except('webhook') |
| 48 | PaymentController: 'completed' vs PaymentStatus::Paid | **FIXED** |
| 49 | AnalyticsController: 'completed' vs PaymentStatus::Paid | **FIXED** |
| 50 | PartnerMatchingService: 'active' vs GeneralStatus::Active | **FIXED** |
| 51 | ReportService: 'completed' vs PaymentStatus::Paid | **FIXED** |
| 52 | PaymentService: 'completed'/'pending' vs enums | **FIXED** |
| 53 | HomeController: 'completed' vs PaymentStatus::Paid | **FIXED** |
| 54 | Api/PaymentController: 'completed' vs PaymentStatus::Paid | **FIXED** |
| 55 | Api/OrderController: 'completed' vs PaymentStatus::Paid | **FIXED** |
| 56 | Api/CommissionController: 'completed' vs PaymentStatus::Paid | **FIXED** |

**Total Bugs Fixed: 55/56** (only world map deferred)

---

## PART 3: All Files Created/Modified

### Customer Journey (Session 2)
```
app/Http/Controllers/Customer/CardController.php     - REWRITTEN: Full CRUD + images + social links
app/Http/Controllers/Customer/OrderController.php    - REWRITTEN: Shipping + prices + coupons
app/Http/Controllers/Public/CardViewController.php   - REWRITTEN: Analytics + vCard
app/Services/CardService.php                         - FIXED: relations + QR
app/Policies/CardPolicy.php                          - FIXED: ownership checks
app/Policies/OrderPolicy.php                         - FIXED: ownership checks
resources/views/customer/cards/create.blade.php      - REWRITTEN: 6-step form
resources/views/customer/cards/edit.blade.php        - NEW
resources/views/customer/cards/show.blade.php        - NEW
resources/views/customer/cards/index.blade.php       - REWRITTEN: card grid
resources/views/customer/orders/create.blade.php     - REWRITTEN: full order form
resources/views/customer/orders/show.blade.php       - NEW
resources/views/customer/payment/checkout.blade.php  - NEW
resources/views/customer/payment/success.blade.php   - NEW
resources/views/customer/payment/failed.blade.php    - NEW
resources/views/customer/dashboard.blade.php         - FIXED: dead links
resources/views/public/card.blade.php                - REWRITTEN: standalone mobile page
resources/views/public/template-detail.blade.php     - FIXED: dead links
resources/views/layouts/app.blade.php                - REWRITTEN: role-aware nav
```

### Role Dashboards (Session 3)
```
app/Http/Controllers/Partner/DashboardController.php   - NEW
app/Http/Controllers/Partner/OrderController.php       - NEW
app/Http/Controllers/Reseller/DashboardController.php  - NEW
app/Http/Controllers/Reseller/SalesController.php      - NEW
app/Http/Controllers/Designer/DashboardController.php  - NEW
app/Http/Controllers/Designer/TemplateController.php   - NEW
app/Http/Controllers/Affiliate/DashboardController.php - NEW
app/Http/Controllers/Affiliate/ClickController.php     - NEW
resources/views/partner/dashboard.blade.php            - NEW
resources/views/partner/no-profile.blade.php           - NEW
resources/views/partner/orders/index.blade.php         - NEW
resources/views/partner/orders/show.blade.php          - NEW
resources/views/reseller/dashboard.blade.php           - NEW
resources/views/reseller/no-profile.blade.php          - NEW
resources/views/reseller/sales/index.blade.php         - NEW
resources/views/designer/dashboard.blade.php           - NEW
resources/views/designer/no-profile.blade.php          - NEW
resources/views/designer/templates/index.blade.php     - NEW
resources/views/designer/templates/create.blade.php    - NEW
resources/views/designer/templates/edit.blade.php      - NEW
resources/views/affiliate/dashboard.blade.php          - NEW
resources/views/affiliate/no-profile.blade.php         - NEW
resources/views/affiliate/clicks/index.blade.php       - NEW
```

### Session 4: Services & Infrastructure
```
app/Services/PartnerMatchingService.php    - NEW: Haversine + city regions
app/Services/CommissionService.php         - NEW: Multi-level commissions
app/Http/Controllers/Shared/PayoutController.php - NEW: Shared payout management
resources/views/shared/payouts/index.blade.php   - NEW
routes/web.php                             - UPDATED: payout routes for all roles
```

### Session 5: Critical Bug Fixes (35+ files)
```
# Filament Resource Type Fixes (11 files)
app/Filament/Resources/Users/UserResource.php    - FIXED: $navigationGroup + $navigationIcon types
app/Filament/Resources/CardResource.php          - FIXED: property types
app/Filament/Resources/OrderResource.php         - FIXED: property types
app/Filament/Resources/TemplateResource.php      - FIXED: property types
app/Filament/Resources/CouponResource.php        - FIXED: property types
app/Filament/Resources/PartnerResource.php       - FIXED: property types
app/Filament/Resources/ResellerResource.php      - FIXED: property types
app/Filament/Resources/DesignerResource.php      - FIXED: property types
app/Filament/Resources/AffiliateResource.php     - FIXED: property types
app/Filament/Resources/PayoutResource.php        - FIXED: property types
app/Filament/Resources/RoleResource.php          - FIXED: property types

# Filament Pages
app/Filament/Pages/Dashboard.php                 - FIXED: icon type + getColumns() return type
app/Filament/Pages/Settings.php                  - FIXED: sed-mangled $navigationGroup restored

# Services (enum + SQLite + column fixes)
app/Services/CommissionService.php               - FIXED: FK violation + enums + SQLite
app/Services/OrderService.php                    - FIXED: cancelOrder() + enums
app/Services/AnalyticsService.php                - FIXED: 10+ column/enum errors
app/Services/PartnerMatchingService.php          - FIXED: enum
app/Services/ReportService.php                   - FIXED: enum
app/Services/PaymentService.php                  - FIXED: enum

# Widgets (enum + SQLite)
app/Filament/Widgets/StatsOverviewWidget.php     - FIXED: SQLite + enums
app/Filament/Widgets/OrdersChartWidget.php       - FIXED: enums
app/Filament/Widgets/RevenueChartWidget.php      - FIXED: enum
app/Filament/Widgets/AnalyticsStatsWidget.php    - FIXED: enums
app/Filament/Widgets/UserGrowthWidget.php        - FIXED: SQLite
app/Filament/Widgets/MonthlyRevenueComparisonWidget.php - FIXED: SQLite + enum

# Controllers (enum + middleware)
app/Http/Controllers/Customer/PaymentController.php  - FIXED: webhook middleware + enums
app/Http/Controllers/Customer/AnalyticsController.php - FIXED: enum
app/Http/Controllers/Public/HomeController.php       - FIXED: enum
app/Http/Controllers/Api/PaymentController.php       - FIXED: enum
app/Http/Controllers/Api/OrderController.php         - FIXED: enum
app/Http/Controllers/Api/CommissionController.php    - FIXED: enum
```

---

## PART 4: What Remains (Priority Order)

### HIGH Priority
| # | Task | Complexity | Notes |
|---|------|-----------|-------|
| 1 | Payment Gateway (Tap.sa) | High | Needs API keys + merchant account |
| 2 | Payment Gateway (STC Pay) | High | Alternative payment method |
| 3 | Missing Customer Views | Medium | analytics/index, analytics/card, analytics/sales-report, payment/methods, profile/edit |

### MEDIUM Priority
| # | Task | Complexity | Notes |
|---|------|-----------|-------|
| 4 | SMS integration | Medium | OTP/notifications |
| 5 | NFC Writer API | Medium | For resellers |
| 6 | Business role dashboard | Medium | /business/dashboard route doesn't exist yet |
| 7 | Partner self-registration | Medium | Currently only via admin |

### LOW Priority
| # | Task | Complexity | Notes |
|---|------|-----------|-------|
| 8 | World map rendering fix | Low | jsvectormap loaded but not rendering properly |
| 9 | Performance optimization | Low | Caching/indexing |
| 10 | Security audit | Low | Input sanitization review |
| 11 | B2B features | Low | Business accounts |
| 12 | Test coverage | Low | Unit + Feature tests |

---

## PART 5: Routes Summary (160 total)

```
Public:         7 routes  (home, pricing, templates, about, contact, card view, vcard)
Auth:           8 routes  (login, register, forgot, reset, verify, logout)
Customer:      21 routes  (dashboard, cards CRUD, orders, analytics, profile, notifications, payment)
Partner:        4 routes  (dashboard, orders list, order detail, update status)
Reseller:       3 routes  (dashboard, sales list, record sale)
Designer:       6 routes  (dashboard, templates CRUD)
Affiliate:      2 routes  (dashboard, clicks list)
Admin:        ~45 routes  (Filament auto-generated)
API:          ~25 routes  (REST API)
System:        ~35 routes (Livewire, webhooks, role switch, etc)
```

---

## PART 6: Key Technical Notes

### Filament 5.x Namespace Changes
```
Section:    Filament\Forms\Components\Section    -> Filament\Schemas\Components\Section
Grid:       Filament\Forms\Components\Grid       -> Filament\Schemas\Components\Grid
Tabs:       Filament\Forms\Components\Tabs       -> Filament\Schemas\Components\Tabs
Form:       Filament\Forms\Form                  -> Filament\Schemas\Schema
Action:     Filament\Tables\Actions\Action       -> Filament\Actions\Action
BulkAction: Filament\Tables\Actions\BulkAction   -> Filament\Actions\BulkAction
```

### Filament 5.x Property Types (CRITICAL)
```
$navigationIcon:  string|\BackedEnum|null  (NOT ?string)
$navigationGroup: \UnitEnum|string|null    (NOT ?string)
getColumns():     array|int                (NOT array|string|int)
```

### SQLite Compatibility
```
- Use strftime('%d', col) instead of DAY(col)
- Use strftime('%H', col) instead of HOUR(col)
- Use strftime('%m', col) instead of MONTH(col)
- Use whereBetween() instead of whereMonth()/whereYear()
- card_views uses viewed_at (NOT created_at, timestamps=false)
```

### Enum Usage (CRITICAL)
```
ALWAYS use enums, NEVER string comparisons:
- PaymentStatus::Pending / Paid / Failed / Refunded  (NOT 'pending'/'completed'/'paid')
- OrderStatus::Pending / Processing / Completed / Cancelled
- PayoutStatus::Pending / Processing / Completed / Failed
- GeneralStatus::Active / Inactive  (NOT 'active'/'inactive')
```

### Database Column Names
```
card_views: ip_address (NOT visitor_ip), viewed_at (NOT created_at)
card_social_links: clicks_count (NOT clicks)
orders: card_id (NOT template_id), NO cancellation_reason/cancelled_at columns
users: NO role column (uses Spatie model_has_roles table)
```

---

## Overall Progress: ~93%
## Bugs Fixed: 55/56
## Sessions: 5
## Next Priority: Missing customer views → Payment integration (needs API keys)
