# 👥 User Roles & Permissions - Maroof Project

**Version:** 1.0  
**Last Updated:** February 7, 2026  
**Managed By:** Claude Desktop

---

## Role Hierarchy

```
┌─────────────────────────────────────┐
│         ADMIN (Super User)          │ - System control
└──────────────────┬──────────────────┘
                   │
        ┌──────────┼──────────┐
        │          │          │
    ┌───▼────┐ ┌──▼───┐  ┌──▼──────┐
    │CUSTOMER │ │RESELLER│ │DESIGNER │
    └────────┘ └──────┘  └────────┘
        │         │         │
        │         └─────────┴─────────────┐
        │                                  │
    ┌───▼───────────┐  ┌──────────────┐  │
    │PRINT PARTNER  │  │AFFILIATE     │  │
    └───────────────┘  └──────────────┘  │
                                          │
                                    ┌─────▼───────┐
                                    │BUSINESS ACCT│
                                    │(Team Member)│
                                    └─────────────┘
```

---

## 1. ADMIN (Super User)

### Identity
- Full system access
- Can be multiple people (team)
- Manually assigned by database

### Permissions

**User Management:**
- ✅ Create/edit/delete users
- ✅ Suspend/ban users
- ✅ Reset passwords
- ✅ View all user data

**Financial:**
- ✅ View all transactions
- ✅ Issue refunds
- ✅ Adjust commission rates
- ✅ Generate financial reports
- ✅ Process payouts
- ✅ Manage promo codes

**Content:**
- ✅ Moderate templates
- ✅ Feature/unfeature templates
- ✅ Moderate reviews
- ✅ Delete inappropriate content

**Partners:**
- ✅ Verify print partners
- ✅ Approve/reject partners
- ✅ Manage partner tiers

**System:**
- ✅ View system logs
- ✅ Access analytics dashboard
- ✅ Manage platform settings
- ✅ Access admin panel
- ✅ View support tickets (all)

---

## 2. CUSTOMER (Regular User)

### Identity
- Default role after signup
- Can perform platform functions
- May upgrade to Business Account

### Permissions

**Profile:**
- ✅ Create/edit own profile
- ✅ Upload photo and logo
- ✅ Update social links
- ✅ View own analytics
- ✅ Manage own cards

**Purchasing:**
- ✅ Purchase NFC cards
- ✅ Purchase add-ons (templates, design services)
- ✅ View order history
- ✅ Request refunds (within 14 days)
- ✅ Use promo codes
- ✅ Apply for affiliate/reseller programs

**Interaction:**
- ✅ View other public profiles
- ✅ Leave reviews on templates/partners
- ✅ Submit lead forms
- ✅ Contact support
- ✅ Share cards

**Restrictions:**
- ❌ Cannot see other users' private data
- ❌ Cannot create premium templates
- ❌ Cannot moderate content
- ❌ Cannot view detailed analytics (basic only)

---

## 3. RESELLER

### Identity
- Applies and is approved by admin
- Individuals who sell Maroof cards
- Can program NFC cards via mobile app (Phase 2)
- Earns 20% commission per sale

### Permissions

**Sales:**
- ✅ Get unique reseller code
- ✅ Create custom referral links
- ✅ Track sales in dashboard
- ✅ View commission earned
- ✅ Request payouts
- ✅ See conversion metrics

**Marketing:**
- ✅ Access marketing materials
- ✅ Create promo campaigns
- ✅ Track campaign performance
- ✅ Get priority WhatsApp support

**Cards:**
- ✅ Program NFC cards (via app)
- ✅ Customize card templates
- ✅ Bulk card orders

**Restrictions:**
- ❌ Cannot modify platform settings
- ❌ Cannot approve other resellers
- ❌ Cannot change commission rate
- ❌ Cannot access other resellers' data

---

## 4. DESIGNER

### Identity
- Creates and sells custom templates
- Earns 70% commission on template sales
- Community-driven business model
- Requires verification

### Permissions

**Template Management:**
- ✅ Create templates
- ✅ Upload template designs
- ✅ Set template pricing
- ✅ Edit own templates
- ✅ View template sales
- ✅ Get sales analytics

**Monetization:**
- ✅ Set commission expectation
- ✅ View earnings
- ✅ Request payouts
- ✅ See design trends/requests

**Community:**
- ✅ View reviews on own templates
- ✅ Respond to customer inquiries
- ✅ Featured templates (if verified)
- ✅ Access designer community

**Restrictions:**
- ❌ Cannot create templates for other designers
- ❌ Cannot moderate platform
- ❌ Cannot approve designs
- ❌ Cannot see other designers' revenue

---

## 5. PRINT PARTNER

### Identity
- Local physical printing shop
- Verification required
- Fulfills card printing orders
- Earns per card printed

### Permissions

**Order Management:**
- ✅ View assigned orders
- ✅ Update order status
- ✅ Set delivery time
- ✅ Provide tracking info
- ✅ Process shipments

**Ratings:**
- ✅ View own ratings/reviews
- ✅ Respond to feedback
- ✅ Track performance metrics

**Shop Management:**
- ✅ Update shop details (location, hours, phone)
- ✅ Set hourly printing rate
- ✅ Set delivery time estimate
- ✅ Manage shop profile

**Restrictions:**
- ❌ Cannot modify card designs
- ❌ Cannot access customer personal data
- ❌ Cannot see other partners' details
- ❌ Cannot approve other partners

---

## 6. AFFILIATE

### Identity
- Online marketers promoting Maroof
- No inventory or shipping
- Earns 20% commission per referral
- Uses tracking links

### Permissions

**Marketing:**
- ✅ Create affiliate campaigns
- ✅ Get unique affiliate link/code
- ✅ Create promotional materials
- ✅ Use affiliate tracking pixel

**Analytics:**
- ✅ View referral links clicks
- ✅ View conversions
- ✅ Track commission earned
- ✅ View monthly earnings

**Payouts:**
- ✅ Request payouts
- ✅ View payout history
- ✅ Update bank details

**Restrictions:**
- ❌ Cannot edit commission rate
- ❌ Cannot see other affiliates' data
- ❌ Cannot directly access products
- ❌ Cannot place orders for others

---

## 7. BUSINESS ACCOUNT (Team)

### Identity
- Organization/Company account
- Multiple team members
- Bulk cards and features
- Tiered subscriptions

### Team Roles

#### Business Admin
- ✅ All business permissions
- ✅ Add/remove team members
- ✅ Change subscription plan
- ✅ Manage billing
- ✅ View all team activity
- ✅ Set team policies

#### Business Manager
- ✅ Manage cards
- ✅ View team activity
- ✅ Cannot change subscription/billing
- ✅ Can add limited team members

#### Business Editor
- ✅ Create/edit cards
- ✅ View shared cards
- ✅ Cannot manage users
- ✅ Cannot change settings

#### Business Viewer
- ✅ View cards
- ✅ View analytics
- ✅ Cannot edit/create

### Permissions

**Card Management:**
- ✅ Create multiple cards for company
- ✅ Share cards with team
- ✅ Apply company branding
- ✅ Bulk operations

**Team:**
- ✅ Invite team members
- ✅ Set role-based permissions
- ✅ View team activity logs
- ✅ Remove members

**Billing:**
- ✅ View invoices
- ✅ Update payment method
- ✅ View usage metrics
- ✅ Manage add-ons

**Features:**
- ✅ Access priority support
- ✅ Advanced analytics
- ✅ Custom branding
- ✅ API access (if tier allows)
- ✅ Bulk importing

**Restrictions:**
- ❌ Cannot see other companies' data
- ❌ Cannot change role of admin
- ❌ Cannot access admin panel

---

## Current Status

✅ **Roles defined**
✅ **Permissions mapped**
✅ **Hierarchy established**
✅ **Ready for middleware implementation**
