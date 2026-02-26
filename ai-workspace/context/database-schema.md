# 📊 Database Schema - Maroof Project

**Version:** 1.0  
**Last Updated:** February 7, 2026  
**Status:** 23 Migrations Created ✅

---

## Core Tables (Priority 1) - COMPLETED ✅

### users
```
id (bigint)
name (string)
email (string, unique)
email_verified_at (timestamp, nullable)
password (string, hashed)
phone (string, unique, nullable)
avatar (string, nullable) - URL or path
bio (string, nullable)
role (enum: admin, customer, reseller, partner, designer, affiliate)
status (enum: active, inactive, suspended)
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Has many: cards, orders, payments, reviews
- Has one: business_account
- Belongs to many: roles (via spatie/roles), permissions

---

### cards
```
id (bigint)
user_id (FK: users)
template_id (FK: templates)
nfc_uid (string, unique) - NFC chip serial
name (string) - Card title
bio (string, nullable)
photo_url (string, nullable)
logo_url (string, nullable)
phone (string, nullable)
email (string, nullable)
website (string, nullable)
address (string, nullable)
linkedin_url (string, nullable)
twitter_url (string, nullable)
instagram_url (string, nullable)
tiktok_url (string, nullable)
whatsapp_number (string, nullable)
custom_fields (json) - extensible
status (enum: active, inactive, archived)
views_count (int) - analytics
shared_count (int) - times shared
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: user, template
- Has many: leads, analytics, versions

---

### templates
```
id (bigint)
designer_id (FK: users) - who designed it
name (string)
description (string, nullable)
thumbnail_url (string) - preview image
design_data (json) - layout, colors, fonts
is_premium (boolean)
is_public (boolean) - available for purchase
category (string) - business, creative, minimal, corporate
price (decimal, nullable) - if premium
sales_count (int)
rating (decimal) - average rating
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: user (designer)
- Has many: cards, reviews

---

### orders
```
id (bigint)
customer_id (FK: users)
order_number (string, unique)
total_amount (decimal)
currency (string) - SAR, USD, etc
status (enum: pending, processing, ready, shipped, delivered, cancelled)
payment_status (enum: unpaid, paid, refunded)
payment_method (string) - hyper_pay, stripe, apple_pay, etc
shipping_address (string, nullable)
notes (string, nullable)
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: user (customer)
- Has many: transactions, order_items, shipments

---

### transactions
```
id (bigint)
order_id (FK: orders)
user_id (FK: users)
amount (decimal)
currency (string)
type (enum: charge, refund)
status (enum: pending, success, failed)
gateway_response (json) - API response
gateway_transaction_id (string) - external reference
created_at, updated_at
```

**Relationships:**
- Belongs to: order, user

---

## Partner Tables (Priority 2) - CREATED ✅

### print_partners
```
id (bigint)
user_id (FK: users) - partner account owner
shop_name (string)
shop_phone (string)
location (string)
city (string)
country (string)
latitude (decimal, nullable)
longitude (decimal, nullable)
address (string)
capability (string) - what they can print
hourly_rate (decimal) - printing cost per card
delivery_time_days (int) - how long to deliver
is_verified (boolean)
verification_date (timestamp, nullable)
total_orders (int)
avg_rating (decimal)
status (enum: active, inactive)
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: user
- Has many: orders (as print partner), reviews

---

### resellers
```
id (bigint)
user_id (FK: users)
reseller_code (string, unique)
commission_rate (decimal) - 20% default
total_sales (int)
total_revenue (decimal)
status (enum: active, inactive, suspended)
tier (enum: bronze, silver, gold) - based on sales
performance_score (decimal)
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: user
- Has many: sales (orders created by this reseller)

---

### designers
```
id (bigint)
user_id (FK: users)
portfolio_url (string, nullable)
specialty (string) - design style/niche
commission_rate (decimal) - 70% default for template sales
total_sales (int)
total_earnings (decimal)
is_featured (boolean)
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: user
- Has many: templates

---

### affiliates
```
id (bigint)
user_id (FK: users)
affiliate_code (string, unique)
commission_rate (decimal) - 20% default
total_referrals (int)
total_earnings (decimal)
tracking_pixel (string, nullable)
status (enum: active, inactive)
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: user
- Has many: referrals

---

### business_accounts
```
id (bigint)
user_id (FK: users) - owner/admin
company_name (string)
registration_number (string, nullable) - business registration
tax_id (string, nullable) - VAT/tax ID
industry (string) - what industry
employees_count (int, nullable)
billing_address (string)
billing_email (string)
subscription_plan (enum: basic, pro, enterprise)
subscription_starts_at (timestamp)
subscription_ends_at (timestamp, nullable)
max_team_members (int) - based on plan
max_cards (int) - based on plan
status (enum: active, inactive, trial, cancelled)
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: user
- Has many: business_team_members, team_cards

---

### business_team_members
```
id (bigint)
business_account_id (FK: business_accounts)
user_id (FK: users)
role (enum: admin, manager, editor, viewer)
joined_at (timestamp)
last_active_at (timestamp, nullable)
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: business_account, user

---

## Engagement Tables (Priority 3) - CREATED ✅

### analytics
```
id (bigint)
card_id (FK: cards)
date (date)
views (int) - daily views
clicks (int) - clicks on links
shares (int) - times shared
source (string) - where views came from
device (enum: mobile, desktop, tablet)
country (string)
city (string)
created_at, updated_at
```

**Relationships:**
- Belongs to: card

---

### leads
```
id (bigint)
card_id (FK: cards)
visitor_name (string, nullable)
visitor_email (string, nullable)
visitor_phone (string, nullable)
message (text, nullable) - inquiry message
source (string) - organic, social, affiliate, etc
ip_address (string, nullable)
user_agent (string, nullable) - browser info
converted (boolean) - if became customer
created_at, updated_at
```

**Relationships:**
- Belongs to: card

---

### reviews
```
id (bigint)
reviewable_type (string) - card, template, partner
reviewable_id (bigint)
user_id (FK: users) - reviewer
rating (int) - 1-5 stars
title (string, nullable)
content (text, nullable)
is_verified_purchase (boolean)
helpful_count (int) - helpful votes
created_at, updated_at
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: user
- Polymorphic: reviewable

---

### notifications
```
id (bigint)
user_id (FK: users)
type (string) - UserCreated, OrderShipped, etc
data (json) - payload
read_at (timestamp, nullable)
created_at
```

**Relationships:**
- Belongs to: user

---

## Transaction Tables (Priority 4) - CREATED ✅

### payouts
```
id (bigint)
user_id (FK: users)
amount (decimal)
currency (string)
status (enum: pending, processing, completed, failed)
payout_method (enum: bank_transfer, wallet, check)
bank_details (json, nullable)
gateway_payout_id (string, nullable)
requested_at (timestamp)
processed_at (timestamp, nullable)
created_at, updated_at
```

**Relationships:**
- Belongs to: user

---

### promo_codes
```
id (bigint)
code (string, unique)
discount_type (enum: percentage, fixed)
discount_value (decimal)
max_uses (int, nullable)
current_uses (int)
valid_from (timestamp)
valid_until (timestamp)
minimum_order_value (decimal, nullable)
applicable_to (json) - who can use it
created_by (FK: users)
status (enum: active, inactive)
created_at, updated_at
```

**Relationships:**
- Belongs to: user (created by)

---

### referrals
```
id (bigint)
affiliate_id (FK: affiliates)
referrer_id (FK: users)
referred_user_id (FK: users)
order_id (FK: orders, nullable)
commission_earned (decimal)
status (enum: pending, confirmed, paid)
referred_at (timestamp)
converted_at (timestamp, nullable)
created_at, updated_at
```

**Relationships:**
- Belongs to: affiliate, user (referrer), user (referred), order

---

## Support Tables (Priority 5) - CREATED ✅

### support_tickets
```
id (bigint)
user_id (FK: users)
title (string)
description (text)
category (enum: billing, technical, general, partnership)
priority (enum: low, medium, high, urgent)
status (enum: open, in_progress, resolved, closed)
assigned_to (FK: users, nullable) - support staff
created_at, updated_at
resolved_at (timestamp, nullable)
deleted_at (soft delete)
```

**Relationships:**
- Belongs to: user, user (assigned to)
- Has many: ticket_replies

---

### ticket_replies
```
id (bigint)
ticket_id (FK: support_tickets)
user_id (FK: users) - replier
message (text)
attachments (json, nullable) - file URLs
created_at, updated_at
```

**Relationships:**
- Belongs to: ticket, user

---

## System Tables (Priority 6) - CREATED ✅

### card_versions
```
id (bigint)
card_id (FK: cards)
version_number (int)
data (json) - full card state snapshot
changed_by (FK: users)
change_reason (string, nullable)
created_at
```

**Relationships:**
- Belongs to: card, user

---

### activity_log
```
id (bigint)
causer_id (FK: users, nullable)
causer_type (string)
subject_type (string) - what was changed
subject_id (bigint)
description (string) - what happened
properties (json) - before/after
created_at
```

**Relationships:**
- Belongs to: user (causer)

---

### settings
```
id (bigint)
key (string, unique)
value (json)
type (enum: system, user, feature_flag)
user_id (FK: users, nullable) - if user-specific
updated_at
```

**Relationships:**
- Belongs to: user (nullable)

---

## Current Status

✅ **23/23 migrations created and executed**
✅ **32 tables created**
✅ **All relationships defined**
✅ **All indexes created**
✅ **Ready for Model creation**
