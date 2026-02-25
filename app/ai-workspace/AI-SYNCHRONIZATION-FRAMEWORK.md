# 📋 AI Synchronization Framework

**Version:** 1.0  
**Created:** February 7, 2026  
**Purpose:** Ensure all AIs work in sync across conversations

---

## 🎯 Core Principle

> **"Every decision must be documented so the next conversation understands why it was made"**

---

## 📂 System Structure

```
ai-workspace/
├── decisions/              ← Architecture decisions (READ FIRST!)
│   ├── ADR-001-*.md
│   ├── ADR-002-*.md
│   └── ...
│
├── conversations/          ← Inter-AI communication logs
│   ├── 2026-02-07-*.md
│   └── ...
│
├── knowledge/             ← Shared rules & standards
│   ├── coding-standards.md
│   ├── api-design-guide.md
│   ├── laravel-conventions.md
│   └── database-conventions.md
│
└── context/              ← Current project state
    ├── tech-stack.md
    ├── database-schema.md
    ├── project-structure.md
    └── user-roles.md
```

---

## 🔄 How It Works

### Before Each Conversation Starts (MANDATORY)

**ALL AIs must read these files in this order:**

1. **`decisions/` (Architecture Decisions)**
   - Why we chose Laravel
   - Why we chose these models
   - Previous rejections and why
   - Non-negotiable choices

2. **`knowledge/` (Standards)**
   - Coding patterns we follow
   - API design rules
   - Database naming conventions
   - What's NOT allowed

3. **`context/` (Current State)**
   - What's been built
   - What's next
   - Current database state
   - Current progress

4. **`tasks/active/` (Current Work)**
   - What's being worked on NOW
   - Who's assigned to what
   - Blockers to be aware of

---

## 🤝 Qwen ↔ Claude Communication

### Scenario 1: Qwen generates code alone
```
1. Qwen reads all decision files
2. Qwen writes code following standards
3. Qwen puts notes in conversation with:
   - What was created
   - What assumptions were made
   - What needs Claude review
4. Claude reviews and approves
5. Decision/conversation logged
```

### Scenario 2: New conversation starts
```
1. Claude/Qwen reads all decisions
2. Knows previous context
3. Continues work seamlessly
4. No "why?" questions - it's documented!
```

### Scenario 3: Code conflict discovered
```
1. Check decisions/ first
2. Is it documented? If yes → follow it
3. If no → mark as blocker for PM
4. Log why conflict happened
```

---

## 📝 Decision Document Template

**File:** `decisions/ADR-XXX-[topic].md`

```markdown
# ADR-XXX: [Architecture Decision]

**Status:** Decided / Pending / Rejected  
**Date:** 2026-02-07  
**Decided By:** Claude Desktop  
**Context:** Why this decision needed  

## Decision
[What we decided]

## Reasoning
[Why this is best]

## Alternatives Considered
- Option A: Why rejected
- Option B: Why rejected

## Constraints
[What forced this decision]

## Consequences
[Good and bad effects]

## Examples
[Code examples of how to follow this]

## For Qwen
[Specific instructions for implementation]
```

---

## 💬 Conversation Log Template

**File:** `conversations/YYYY-MM-DD-[topic].md`

```markdown
# Conversation: [Topic]

**Date:** 2026-02-07, 15:30 PM  
**Participants:** Claude → Qwen → Claude  
**Topic:** [What was discussed]

---

## Summary
[2-3 sentences of what happened]

---

## Decisions Made
1. [Decision 1] → See `decisions/ADR-XXX`
2. [Decision 2] → See `decisions/ADR-YYY`

---

## Code Generated
- [File 1](../../app/path.php) - lines X-Y
- [File 2](../../app/path2.php) - lines X-Y

---

## Blockers Found
- [ ] [Issue 1] - PM action needed
- [ ] [Issue 2] - Claude decision needed

---

## Approved By
- [x] Claude Desktop: Reviewed on 2026-02-07
- [ ] Mohammad (PM): Needs approval
```

---

## 🚀 Immediate Actions

### Step 1: Create Decision Files (TODAY)
```
ADR-001: Why Laravel 11
ADR-002: Why MySQL (not Postgres)
ADR-003: Why Spatie Permissions
ADR-004: Why REST API (not GraphQL)
ADR-005: Naming conventions for tables/columns
ADR-006: Naming conventions for classes/functions
ADR-007: Frontend: Blade + Alpine (not Vue/React)
ADR-008: Payment processors: HyperPay + Stripe
ADR-009: Authentication: Sanctum (not JWT)
ADR-010: Soft deletes policy
```

### Step 2: Document Current State (TODAY)
- What's done ✅
- What's in progress 🔄
- What's blocked 🚨
- What's pending ⏳

### Step 3: Create Conversation Logs (ONGOING)
- Every time Qwen & Claude interact
- Log what was decided
- Link to decision files
- Note any blockers

---

## ✅ Checklist for Qwen (Continue)

**Every time you start coding, verify:**

```
☐ Read all `decisions/ADR-*.md` files?
☐ Checked `knowledge/coding-standards.md`?
☐ Verified against `context/project-structure.md`?
☐ Checked `tasks/active/` for current priority?
☐ All code follows naming conventions from ADR-005 & 006?
☐ All code matches Laravel conventions from ADR-007?
☐ No conflicts with previous decisions?
```

---

## ✅ Checklist for Claude (Desktop)

**Before approving any code:**

```
☐ Does it follow all ADR decisions?
☐ Does it match coding standards?
☐ Are relationships correct?
☐ Is the code performant?
☐ Are there security issues?
☐ Is it properly commented?
☐ Update conversation log
☐ Update relevant decision if new info
```

---

## 🔐 Critical: Unchangeable Decisions

These have been decided and won't change:

1. **Backend:** Laravel 11 (Sanctum auth, Spatie roles)
2. **Database:** MySQL 8.0+ (Eloquent ORM)
3. **Frontend:** Blade + Tailwind + Alpine.js
4. **API:** RESTful JSON
5. **Auth:** Laravel Sanctum tokens
6. **Payments:** HyperPay (Phase 1) + Stripe (Phase 2)
7. **Deployment:** TBD (but Docker ready)

**If Qwen suggests alternatives to these: IGNORE and follow ADR**

---

## 🎯 How Qwen Knows What to Do

### In First Conversation
```
PM: "Qwen, create models"
Qwen reads:
  - decisions/ (knows architecture)
  - knowledge/ (knows coding style)
  - context/database-schema.md (knows structure)
→ Creates 23 models correctly
```

### In Second Conversation (Week Later)
```
PM: "Qwen, create controllers"
Qwen reads:
  - decisions/ (knows architecture unchanged)
  - conversations/ (knows what models were created)
  - context/ (updated with current state)
→ Creates controllers that match models
```

### In Third Conversation (Month Later)
```
PM: "Qwen, add payment feature"
Qwen reads:
  - ADR-008 (knows HyperPay + Stripe chosen)
  - ADR-009 (knows Sanctum authentication)
  - conversations/ (knows what's been built)
→ Creates payment integration correctly
```

---

## ⚠️ What Happens Without This

**Bad scenario:**

Conversation 1 (Feb 7):
```
PM → Qwen: "Create models"
Qwen creates 23 models ✅
Claude reviews ✅
```

Conversation 2 (Feb 14):
```
PM → Qwen: "Create controllers"
Qwen: "Should I use Service Layer or put logic in controller?"
PM: "Check the decisions"
Qwen: "No decisions documented"
Qwen guesses wrong → Claude rejects → Time wasted
```

**Good scenario:**

Conversation 2 (Feb 14):
```
PM → Qwen: "Create controllers"
Qwen reads ADR-011: "Service Layer for business logic"
Qwen: "Understood, creating controllers with service layer"
Creates correctly first time ✅
```

---

## 📊 Status

- [ ] ADR files created
- [ ] Conversation logs started
- [ ] Standards documented
- [ ] Next conversation reads all this
- [ ] Qwen/Claude work in sync

---

## 🎯 Success Metric

**If Qwen can create correct code without Claude asking "why did you...?" questions → System works!**

---

## For Mohammed (PM)

**You don't need to understand the technical details.** Just know:

1. **Every decision gets written down** (in `decisions/`)
2. **Every conversation gets logged** (in `conversations/`)
3. **AIs read this before working** (startup checklist)
4. **Prevents repeating the same mistakes** twice
5. **Faster development** (less back-and-forth)

Think of it like:
- **decisions/** = Business rules that won't change
- **conversations/** = Meeting minutes to remember what happened
- **knowledge/** = How we do things around here

---

**Created by:** Claude Desktop  
**For:** Qwen, Claude, and Mohammed  
**Updated:** February 7, 2026
