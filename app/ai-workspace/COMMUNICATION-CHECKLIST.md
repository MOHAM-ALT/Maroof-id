# 🎯 Communication & Reporting Checklist - Mandatory for All AIs

**Version:** 1.0  
**Created:** February 8, 2026  
**Purpose:** Ensure every AI files reports and maintains communication

---

## 🔐 THE RULE

> **"No work ends without a report. No conversation starts without reading yesterday's reports."**

---

## ✅ Daily Reporting Checklist

### For EVERY AI (Claude, Qwen, Laravel Boost)

**At END of every work session:**

```
☐ Daily report filed: ai-workspace/reports/daily/YYYY-MM-DD.md
   Location: /reports/daily/
   Format: See REPORTING-SYSTEM.md template
   Contains:
     ☐ What was completed
     ☐ What failed or blocked
     ☐ Files created/modified
     ☐ Time spent
     ☐ Next steps
     ☐ Issues found
     ☐ Sign-off

☐ Handoff report filed (if handing off to another AI)
   Format: /reports/ai-handoff/[from]-to-[to]/YYYY-MM-DD-HH-MM.md
   Contains:
     ☐ Task description
     ☐ Requirements
     ☐ Deadline
     ☐ Success criteria
     ☐ References to read

☐ Issue report filed (if problems found)
   Format: /reports/issues/YYYY-MM-DD-[issue-name].md
   Contains:
     ☐ Issue description
     ☐ Severity level
     ☐ How to reproduce
     ☐ Suggested fix
     ☐ Who should fix it

☐ Feature progress updated (if working on feature)
   Format: /reports/features/FEATURE-XXX-[name].md
   Contains:
     ☐ Current progress %
     ☐ Completed items
     ☐ In-progress items
     ☐ Blocked items
     ☐ ETA for completion

☐ All report links verified
   ☐ Cross-links work
   ☐ References are correct
   ☐ File paths are accurate

☐ Signed off on report
   ☐ Your name/role added
   ☐ Timestamp added
   ☐ Quality verified
```

---

## 🔄 Start-of-Day Checklist

### For EVERY AI (Claude, Qwen, Laravel Boost)

**At START of every conversation:**

```
READING PHASE (Order matters!):

☐ 1. Read yesterday's daily report
   File: ai-workspace/reports/daily/YYYY-MM-DD.md
   Purpose: Know what happened, what's blocked, what's next

☐ 2. Read any handoff reports directed to you
   Files: ai-workspace/reports/ai-handoff/X-to-me/
   Purpose: Know what tasks were assigned to you

☐ 3. Read any issue reports
   Files: ai-workspace/reports/issues/YYYY-MM-DD-*.md
   Purpose: Know what problems exist and need fixing

☐ 4. Read current task (if assigned)
   File: ai-workspace/tasks/active/TASK-XXX-*.md
   Purpose: Know exactly what to build today

☐ 5. Read decisions if relevant
   Files: ai-workspace/decisions/ADR-*.md
   Purpose: Know architectural constraints

STARTUP CONFIRMATION:

☐ I understand what happened yesterday
☐ I understand what tasks are assigned to me
☐ I understand current blockers
☐ I understand architectural decisions
☐ I'm ready to work
```

---

## 📋 What Each AI Must Report On

### Claude Desktop (Coordinator)

**Daily Report Must Include:**
```
☐ Tasks assigned to other AIs
☐ Code reviews completed
☐ Decisions made
☐ Issues encountered
☐ Plan for tomorrow
☐ Any blockers for other AIs
```

**Handoff Reports (Claude → Qwen):**
```
☐ Clear task description
☐ Success criteria
☐ Deadline
☐ Related files/documentation
☐ Quality expectations
```

**Issue Reports (Claude → Team):**
```
☐ What broke
☐ How serious (severity level)
☐ Who should fix it
☐ Deadline for fix
```

---

### Continue - Qwen (Code Generator)

**Daily Report Must Include:**
```
☐ Code created (file names, line counts)
☐ Code quality (any issues)
☐ Time spent coding
☐ What's left to do
☐ Any blockers
☐ Assumptions made
```

**Handoff Reports (Qwen → Claude):**
```
☐ What was completed
☐ Quality of work
☐ Testing done
☐ Issues found
☐ Ready for review? YES/NO
```

**Issue Reports (Qwen → Claude):**
```
☐ What error occurred
☐ When it happened
☐ How to reproduce
☐ Temporary workaround (if any)
```

---

### Laravel Boost (Inspector/Tester)

**Daily Report Must Include:**
```
☐ Tests run
☐ Tests passed/failed
☐ Issues found
☐ Performance metrics
☐ Data integrity checks
☐ Next items to test
```

**Test Reports (Laravel Boost → Claude):**
```
☐ Test name
☐ Command used
☐ Result (✅ pass / ❌ fail)
☐ Output/logs
☐ Performance notes
```

**Issue Reports (Laravel Boost → Claude):**
```
☐ Bug description
☐ How critical
☐ How to reproduce
☐ Stack trace/logs
☐ Suggested fix
```

---

## 📊 File Structure Verification

**Run this check DAILY:**

```
ai-workspace/
├── reports/
│   ├── daily/
│   │   ├── 2026-02-07.md ✅
│   │   ├── 2026-02-08.md ✅
│   │   └── [TODAY].md    ✅ MUST EXIST by end of day
│   │
│   ├── issues/
│   │   ├── 2026-02-08-order-relationship.md ✅
│   │   └── [New issues today].md
│   │
│   ├── ai-handoff/
│   │   ├── claude-to-qwen/
│   │   │   ├── 2026-02-07-15-30.md ✅
│   │   │   └── [New handoffs today].md
│   │   │
│   │   └── qwen-to-claude/
│   │       ├── 2026-02-07-18-00.md ✅
│   │       └── [New returns today].md
│   │
│   └── features/
│       ├── FEATURE-001-auth.md ✅
│       └── [Feature progress].md

✅ If file is missing = PROBLEM = File it NOW
```

---

## 🚨 Enforcement Rules

### Rule 1: Report or Stop

```
If report is not filed by end of day:
❌ Cannot close the conversation
❌ Must file report before stopping
❌ No exceptions
```

### Rule 2: Read or Restart

```
If report is not read at start of day:
❌ Cannot start work
❌ Must read reports first
❌ No exceptions
```

### Rule 3: Accuracy Matters

```
If report is inaccurate:
❌ Other AIs get confused
❌ Team doesn't know real status
❌ Problems fester
❌ Timeline breaks
```

### Rule 4: Communication Logs Everything

```
Every interaction between AIs documented:
- Claude → Qwen = Handoff report
- Qwen → Claude = Return report
- Claude → Laravel Boost = Test request
- Laravel Boost → Claude = Test results
```

---

## 📈 Quality Metrics

### Report Quality Scoring

**5 Stars (Perfect):**
```
☑ Detailed description of work
☑ Clear metrics (lines of code, time spent)
☑ Issues documented with solutions
☑ Next steps clear
☑ All cross-links working
☑ Professional formatting
```

**4 Stars (Good):**
```
☑ Good description
☑ Most metrics present
☑ Issues documented
☑ Next steps clear
⚠️ Some links might be wrong
```

**3 Stars (Acceptable):**
```
☑ Basic information present
⚠️ Some metrics missing
⚠️ Issues briefly noted
⚠️ Next steps vague
❌ Some links broken
```

**Below 3 Stars:**
```
❌ UNACCEPTABLE
Needs to be rewritten to standards
```

---

## 🎯 Communication Flow Example

### Day 1: Monday 10:00 AM

**Claude:**
```
1. Read weekend reports (if any)
2. Create task: TASK-001
3. File handoff report: claude-to-qwen
   → "Qwen, create models"
4. File daily report: Mon AM

Waits for Qwen...
```

**Qwen (Receives handoff):**
```
1. Read handoff report from Claude
2. Read ADRs and standards
3. Read current context
4. Start coding

After 4 hours...

5. File handoff report: qwen-to-claude
   → "Models created, review please"
6. File daily report (partial)

Waits for Claude...
```

**Claude (Receives return):**
```
1. Read Qwen's handoff report
2. Review code
3. Either approve or request changes
4. If approve:
   - File approval in handoff report
   - Create next task
   - File handoff report: claude-to-laravel
      → "Laravel, test these models"
5. File daily report (update)

Waits for Laravel Boost...
```

**Laravel Boost (Receives request):**
```
1. Read Claude's handoff report
2. Run tests
3. Report results

If issues found:
4. File issue report: 2026-02-10-model-error.md
5. File handoff: laravel-to-claude
   → "Found issue, needs fix"

Waits for Claude...
```

**Claude (Receives test report):**
```
1. Read test results
2. Read issue report
3. Decide: Fix or investigate further
4. Assign fix to Qwen
5. File next handoff
6. File daily report (end of day)

All reports filed. Day complete.
```

### Day 2: Tuesday 10:00 AM

**Qwen (New conversation, starts fresh):**
```
1. Read yesterday's daily report (Mon)
   → Knows: models created, issues found
2. Read issue report (model error)
   → Knows: what to fix
3. Read handoff report from Claude
   → Knows: fix this specific issue
4. Start fixing

No context lost! Continues seamlessly.
```

---

## ✅ Master Checklist (For Mohammed - PM)

**Check this DAILY at end of day:**

```
☐ Day's daily report exists: reports/daily/YYYY-MM-DD.md
☐ Report has section: Completed, In Progress, Issues
☐ Handoff reports filed:
  ☐ Any Claude → Qwen reports
  ☐ Any Qwen → Claude reports
  ☐ Any test requests/results
☐ Issue reports filed (if any issues found)
☐ All cross-links verified
☐ Report is signed off
☐ Next day's plan is clear
☐ No ambiguity about next steps

If ANY checkbox is ❌:
→ Ask the AI to file the missing report immediately
→ Don't accept incomplete reporting
```

---

## 🏆 Success Looks Like

**After 1 Week:**
```
✅ 7 daily reports filed (one per day)
✅ All reports complete and accurate
✅ No miscommunication between AIs
✅ Blockers identified and resolved
✅ Clear progress visible
✅ Nothing falls through cracks
✅ New conversation reads old reports and continues seamlessly
```

**After 1 Month:**
```
✅ 20+ daily reports showing progress
✅ AI can pick up where they left off
✅ Complete audit trail of all decisions
✅ Easy to find any file or decision
✅ Mohammed always knows status
✅ No repeated mistakes
✅ Project momentum maintained
```

---

## 🔗 Quick Links

**Report Templates:**
- Daily Report: See REPORTING-SYSTEM.md
- Handoff Report: See REPORTING-SYSTEM.md
- Issue Report: See REPORTING-SYSTEM.md
- Feature Report: See REPORTING-SYSTEM.md

**Where Reports Go:**
- Daily: `reports/daily/YYYY-MM-DD.md`
- Handoff: `reports/ai-handoff/[from]-to-[to]/YYYY-MM-DD-HH-MM.md`
- Issues: `reports/issues/YYYY-MM-DD-[name].md`
- Features: `reports/features/FEATURE-XXX-[name].md`

**Standards:**
- Read first: `decisions/ADR-*.md`
- Writing code: `knowledge/coding-standards.md`
- Database: `knowledge/database-conventions.md`
- Laravel: `knowledge/laravel-conventions.md`

---

## 🎯 THE PROMISE

**If every AI follows this checklist:**

✅ Zero lost context  
✅ Zero miscommunication  
✅ Zero repeated work  
✅ 100% project visibility  
✅ Complete audit trail  
✅ Seamless handoffs  
✅ Mohammed always informed  
✅ Progress accelerates  

---

**Created By:** Claude Desktop  
**For:** All AIs + Mohammed (PM)  
**Status:** MANDATORY COMPLIANCE

🔒 **This is non-negotiable. Every report matters.**

---

**Last Updated:** February 8, 2026  
**Next Review:** February 15, 2026
