# Maroof ID - Master Development Plan
# Date: 2026-02-23 | Session 6
# Status: Current + New Features Roadmap

---

## Current State Summary

| Metric | Value |
|--------|-------|
| Overall Progress | ~93% (Core Platform) |
| Bugs Fixed | 55/56 |
| Routes | 160 |
| Sessions | 5 completed |
| Tech Stack | Laravel 12 + Filament 5 + Livewire 4 + SQLite |

---

## PHASE A: Immediate Tasks (يمكن تنفيذها الآن)

### A1. Missing Customer Views (5 views) - Priority: HIGH
```
Status: NOT DONE
Files needed:
├─ resources/views/customer/analytics/index.blade.php    - إحصائيات عامة
├─ resources/views/customer/analytics/card.blade.php     - إحصائيات بطاقة محددة
├─ resources/views/customer/analytics/sales-report.blade.php - تقرير المبيعات
├─ resources/views/customer/payment/methods.blade.php    - طرق الدفع
└─ resources/views/customer/profile/edit.blade.php       - تعديل الملف الشخصي

Estimated: 1-2 hours
```

### A2. Card Features Enhancement - Priority: HIGH
```
Status: NOT DONE
Features:
├─ Card duplication (clone) - نسخ البطاقة
├─ Card sharing options (embed code, iframe, direct link)
├─ Card expiration date
├─ Card password protection
└─ SEO optimization per card (meta tags)

Estimated: 3-4 hours
```

### A3. Notifications System (Basic) - Priority: HIGH
```
Status: NOT DONE
Features:
├─ Database notifications (Laravel Notifications)
├─ Notification center (inbox page)
├─ Badge counter (unread count in nav)
├─ Mark as read/unread
├─ Categories: order, payment, system
└─ Auto-notify on: order status change, payment, card views milestone

Technology: Laravel Notifications + Database driver
Estimated: 2-3 hours
```

---

## PHASE B: Core Improvements (ميزات أساسية)

### B1. Visual Card Builder Studio - Priority: CRITICAL (Killer Feature)
```
Status: NOT DONE
Description: محرر بصري للبطاقات بالسحب والإفلات

Core Features:
├─ Canvas Editor (Fabric.js)
│  ├─ Drag & drop elements
│  ├─ Layers panel
│  ├─ Undo/Redo
│  └─ Zoom & Pan
├─ Elements Library
│  ├─ Text (Arabic/English fonts)
│  ├─ Shapes (circle, rect, triangle)
│  ├─ Icons (Heroicons library)
│  ├─ Images (upload)
│  └─ Social icons (auto-linked)
├─ Styling Panel
│  ├─ Colors (picker + palettes)
│  ├─ Fonts (Google Fonts)
│  ├─ Borders, shadows, opacity
│  └─ Alignment tools
├─ Templates (100+ pre-made)
├─ Responsive Preview (desktop/tablet/mobile)
└─ Export: PNG, PDF, publish live

Technology: Fabric.js + Alpine.js + Tailwind
Estimated: 2-3 days
```

### B2. Brand Kit Manager (B2B) - Priority: HIGH
```
Status: NOT DONE
Features:
├─ Company brand assets (logo, colors, fonts)
├─ Bulk card creation (CSV upload → 50-500 cards)
├─ Team management (admin/editor/viewer roles)
├─ Brand consistency (locked colors/logo)
└─ Auto-generated brand guidelines PDF

Estimated: 1-2 days
```

### B3. Invoice/Receipt Generation - Priority: HIGH
```
Status: NOT DONE
Features:
├─ PDF invoice generation (Arabic + VAT)
├─ Auto-send receipt email on payment
├─ Invoice history page
└─ Download invoice button on order detail

Technology: DomPDF / Laravel Snappy
Estimated: 3-4 hours
```

---

## PHASE C: Marketing & Growth Engine

### C1. Email Marketing System - Priority: MEDIUM
```
Status: NOT DONE
Features:
├─ Welcome email series (automated)
├─ Order confirmation emails (already have templates)
├─ Abandoned cart reminders
├─ Re-engagement emails (inactive users)
└─ Bulk email campaigns (admin panel)

Technology: Laravel Queues + Mailables
Estimated: 1-2 days
```

### C2. SMS Integration - Priority: MEDIUM (needs API keys)
```
Status: NOT DONE
Features:
├─ OTP verification
├─ Order status SMS
├─ Payment reminders
└─ Promotional campaigns

Technology: Unifonic (Saudi) or Twilio
Blocker: API keys needed
```

### C3. Live Chat Widget - Priority: LOW
```
Status: NOT DONE
Options:
├─ Tawk.to (free, embed script)
├─ Crisp (paid, better features)
└─ Custom (Laravel + Pusher)

Recommendation: Start with Tawk.to (free, 5 min setup)
```

---

## PHASE D: Advanced Features (Future)

### D1. 3D Model Integration
```
Technology: Google Model Viewer / Three.js
Features: 3D product showcase on cards
Estimated: 5-7 days
```

### D2. AR Card Scanner
```
Technology: WebXR + AR.js / 8th Wall
Features: Scan QR → 3D content appears
Estimated: 7-10 days
```

### D3. Photo-to-3D Avatar
```
Technology: Ready Player Me API
Features: Upload selfie → realistic 3D avatar
Estimated: 5-7 days
```

### D4. Custom HTML Embed
```
Technology: Monaco Editor + Sandboxed iframe
Features: Custom code blocks in cards
Estimated: 3-5 days
```

### D5. Animation Builder (Basic)
```
Technology: CSS animations + Tailwind
Features: Entrance animations, hover effects
Estimated: 1-2 days
```

### D6. Audio/Video in Cards
```
Features: Upload MP3/MP4, simple player
Estimated: 1-2 days
```

---

## PHASE E: Infrastructure (Before Launch)

### E1. Payment Gateway - Priority: CRITICAL (needs API keys)
```
├─ Tap.sa integration
├─ STC Pay integration
└─ Webhook handling + verification
Blocker: API keys from client
```

### E2. Performance Optimization
```
├─ Database indexes
├─ Query caching (Redis)
├─ Image optimization (WebP)
├─ Lazy loading
└─ CDN setup
```

### E3. Security Audit
```
├─ CSRF/XSS verification
├─ SQL injection testing
├─ File upload validation
├─ Rate limiting
└─ Security headers
```

### E4. Backups
```
├─ spatie/laravel-backup
├─ Daily automated backups
├─ S3 storage
```

---

## Execution Order (ترتيب التنفيذ)

### Now (Session 6):
1. A1: Missing customer views (5 views)
2. A2: Card features (duplication, sharing)
3. A3: Notifications system
4. B3: Invoice generation

### Next Session:
5. B1: Visual Card Builder Studio (Fabric.js)
6. B2: Brand Kit Manager

### Later:
7. C1: Email marketing
8. D5: Animation builder
9. D6: Audio/Video in cards
10. D1-D4: Advanced features (3D, AR, etc.)

### Needs Client Input:
- E1: Payment API keys (Tap.sa / STC Pay)
- C2: SMS API keys (Unifonic)
- Domain + SSL setup

---

## Notes
- All new features must maintain backward compatibility
- Use SQLite-compatible queries (no MySQL-specific functions)
- Use enums (NOT string comparisons) for all statuses
- Use Filament 5.x correct property types
- card_views: viewed_at (NOT created_at), ip_address (NOT visitor_ip)
- orders: card_id (NOT template_id)
