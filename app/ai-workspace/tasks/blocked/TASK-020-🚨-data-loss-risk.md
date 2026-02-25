# TASK-020: 🚨 User Roles Migration - DATA LOSS RISK

**Status:** 🔴 BLOCKED - URGENT
**Severity:** CRITICAL

---

## 🚨 PROBLEM

Migration will add `role` column to `users` table.

**DANGER:** 
- Table has 1,547 existing users
- No default value specified
- Existing users will get NULL role
- App will break!

---

## Required from PM (URGENT)

What should default role be for existing 1,547 users?

A) "customer" (safe default)
B) "admin" (risky)  
C) Leave null (app breaks)

**YOUR DECISION:** _______

---

**STATUS:** 🛑 STOPPED - Cannot proceed without decision