# 📁 Project Structure - Maroof

**Version:** 1.0  
**Last Updated:** February 7, 2026  
**Location:** `maroof-app/`

---

## Directory Tree

```
maroof-app/
├── .ai-instructions/          # AI Operating Instructions
│   ├── README.md
│   ├── claude-desktop.md
│   ├── continue.md
│   ├── laravel-boost.md
│   ├── collaboration-rules.md
│   └── mohammad-pm.md
│
├── ai-workspace/              # AI Coordination Workspace
│   ├── context/              # Project state & info
│   │   ├── api-endpoints.md
│   │   ├── database-schema.md ✅ COMPLETED
│   │   ├── project-structure.md ✅ COMPLETED
│   │   ├── tech-stack.md ✅ COMPLETED
│   │   └── user-roles.md ✅ COMPLETED
│   ├── tasks/                # Task tracking
│   │   ├── active/          # Currently working on
│   │   ├── pending/         # Not started yet
│   │   ├── blocked/         # Waiting for decision
│   │   └── completed/       # Finished tasks
│   ├── reports/             # Daily/weekly progress
│   │   ├── daily/
│   │   ├── weekly/
│   │   ├── features/
│   │   └── analysis/
│   ├── progress/            # Sprint tracking
│   │   ├── current-sprint.md
│   │   ├── roadmap.md
│   │   └── metrics.md
│   ├── decisions/           # Architecture decisions
│   ├── conversations/       # AI communication logs
│   ├── knowledge/           # Shared documentation
│   │   ├── api-design-guide.md
│   │   ├── coding-standards.md
│   │   ├── database-conventions.md
│   │   ├── laravel-conventions.md
│   │   └── security-checklist.md
│   └── templates/           # Ready-to-use templates
│       ├── task-template.md
│       ├── conversation-template.md
│       ├── decision-template.md
│       ├── daily-report-template.md
│       └── report-template.md
│
├── app/                       # Application Logic
│   ├── Http/
│   │   ├── Controllers/      # API & Web controllers
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── CardController.php
│   │   │   │   ├── TemplateController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   └── ...
│   │   │   └── Web/
│   │   │       ├── DashboardController.php
│   │   │       ├── ProfileController.php
│   │   │       └── ...
│   │   ├── Middleware/      # Custom middleware
│   │   │   ├── RoleMiddleware.php
│   │   │   ├── PermissionMiddleware.php
│   │   │   └── ...
│   │   └── Requests/        # Form validation
│   │       ├── LoginRequest.php
│   │       ├── CreateCardRequest.php
│   │       └── ...
│   │
│   ├── Models/              # Eloquent Models (23 total)
│   │   ├── User.php         # Core model (extends Authenticatable)
│   │   ├── Card.php
│   │   ├── Template.php
│   │   ├── Order.php
│   │   ├── Transaction.php
│   │   ├── PrintPartner.php
│   │   ├── Reseller.php
│   │   ├── Designer.php
│   │   ├── Affiliate.php
│   │   ├── BusinessAccount.php
│   │   ├── BusinessTeamMember.php
│   │   ├── Lead.php
│   │   ├── Review.php
│   │   ├── Analytics.php
│   │   ├── Notification.php
│   │   ├── SupportTicket.php
│   │   ├── Payout.php
│   │   ├── PromoCode.php
│   │   └── ...
│   │
│   ├── Services/            # Business Logic
│   │   ├── CardService.php
│   │   ├── OrderService.php
│   │   ├── PaymentService.php
│   │   ├── NFCService.php
│   │   ├── AnalyticsService.php
│   │   └── ...
│   │
│   ├── Events/              # Event classes
│   │   ├── CardCreated.php
│   │   ├── OrderPlaced.php
│   │   ├── PaymentProcessed.php
│   │   └── ...
│   │
│   ├── Listeners/           # Event handlers
│   │   ├── SendOrderConfirmation.php
│   │   ├── UpdateAnalytics.php
│   │   └── ...
│   │
│   ├── Jobs/                # Queue jobs
│   │   ├── ProcessPayment.php
│   │   ├── SendEmail.php
│   │   ├── ProcessNFCCard.php
│   │   └── ...
│   │
│   ├── Rules/               # Custom validation rules
│   │   ├── ValidNFCUID.php
│   │   ├── UniqueEmail.php
│   │   └── ...
│   │
│   ├── Providers/           # Service Providers
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── RouteServiceProvider.php
│   │
│   ├── View/                # View Composers, Creators
│   │   └── ...
│   │
│   └── Traits/              # Reusable traits
│       ├── HasAnalytics.php
│       ├── HasNFCData.php
│       └── ...
│
├── bootstrap/
│   ├── app.php             # App bootstrapping
│   ├── providers.php       # Provider registration
│   └── cache/              # Cached bootstrap files
│
├── config/                 # Configuration files
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── permission.php      # Spatie permissions config
│   ├── queue.php
│   ├── services.php        # Third-party service keys
│   ├── session.php
│   └── ...
│
├── database/
│   ├── migrations/         # ✅ 23 migrations created & executed
│   │   └── 2025_01_xx_*.php (All files)
│   │
│   ├── factories/          # Model factories for testing
│   │   ├── UserFactory.php
│   │   ├── CardFactory.php
│   │   └── ...
│   │
│   └── seeders/           # ✅ 5 seeders created
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── TemplateSeeder.php
│       ├── CardSeeder.php
│       └── OrderSeeder.php
│
├── docs/                   # Project Documentation
│   ├── 01-project-overview/
│   ├── 02-user-journey/
│   ├── 03-database/
│   ├── 10-ai-instructions/
│   └── MAROOF-COMPLETE-PROJECT-SPEC.md (5264 lines!)
│
├── public/                 # Web root
│   ├── index.php          # Application entry point
│   ├── robots.txt
│   └── build/             # Vite compiled assets
│
├── resources/              # Frontend assets & views
│   ├── css/               # Tailwind CSS
│   │   └── app.css
│   ├── js/                # Alpine.js / Vue
│   │   ├── app.js
│   │   └── bootstrap.js
│   ├── views/             # Blade templates
│   │   ├── layouts/
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── cards/
│   │   └── ...
│   └── docs/              # User guides
│
├── routes/                # Route definitions
│   ├── web.php           # Web routes
│   ├── api.php           # API routes
│   ├── auth.php          # Auth routes
│   └── console.php       # Console routes
│
├── storage/              # Runtime storage
│   ├── app/              # App files (uploads)
│   ├── framework/        # Cache, sessions
│   └── logs/            # Application logs
│
├── tests/               # Test suites
│   ├── Feature/        # Feature tests
│   ├── Unit/           # Unit tests
│   └── TestCase.php   # Base test class
│
├── vendor/              # Composer dependencies
│
└── Configuration Files
    ├── .env.example    
    ├── .env            
    ├── .gitignore
    ├── artisan         
    ├── composer.json   
    ├── package.json    
    ├── phpunit.xml    
    ├── tailwind.config.js
    ├── vite.config.js 
    └── postcss.config.js
```

---

## Database Status

✅ **23/23 migrations executed successfully**
✅ **32 tables created**
✅ **All relationships defined**
✅ **All foreign keys created**
✅ **All indexes created**

---

## Current Phase

**Phase 1: Database Foundation** - 50% Complete

**Completed:**
- [x] Create migrations (23 files)
- [x] Run migrations (all successful)
- [x] Verify database tables (32 tables)
- [x] Create Models (23 files)
- [x] Create Seeders (5 files)

**Next:**
- [ ] Test relationships
- [ ] Create API controllers
- [ ] Create web controllers
- [ ] Create routes
- [ ] Create views
- [ ] Authentication system
- [ ] Dashboard
- [ ] Payment integration

---

## Next Phase: Phase 2 - Authentication (⏳ Pending)

- User registration
- Email verification
- Login/logout
- Password reset
- Role-based access control (Spatie permissions)
- 2FA (optional)

---

## Ready for Development

All context files are now properly documented:
✅ Tech Stack defined
✅ Database Schema complete
✅ User Roles & Permissions mapped
✅ Project Structure documented
✅ Database created and ready
