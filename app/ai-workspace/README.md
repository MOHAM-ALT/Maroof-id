# AI Workspace - Maroof Project

This workspace is designed for AI agents (Claude, Continue, Laravel Boost) to collaborate on the Maroof project efficiently.

## 🎯 Purpose

- Coordinate work between multiple AI agents
- Track progress and decisions
- Maintain context across sessions
- Document everything for future reference

## 📁 Structure
```
ai-workspace/
├── context/            → Project state and information (Database, Tech Stack, Roles)
├── decisions/          → User Journeys, Business Model, Architecture Decisions
│   ├── overview.md - نظرة عامة على المشروع
│   ├── user-personas.md - 7 أنواع من المستخدمين
│   ├── business-model.md - نموذج العمل
│   ├── reseller-journey.md - رحلة الموزع
│   ├── designer-journey.md - رحلة المصمم
│   ├── partner-journey.md - رحلة شريك الطباعة
│   └── USER-JOURNEY-FIX-PLAN.md - خطة إصلاح UX
├── phases/             → المراحل التطويرية
│   └── Phase-4-Filament-Admin.md - خطة Filament كاملة
├── reports/            → Daily, weekly, and feature reports
│   └── daily/ - تقارير يومية مفصلة
├── tasks/              → All tasks (active, completed, pending, blocked)
├── progress/           → Roadmap and metrics
├── knowledge/          → Shared knowledge and standards
├── conversations/      → AI-to-AI communication logs
└── templates/          → Ready-to-use templates
```

## 🚀 Quick Start

### For Project Manager (Human)

**Starting a new feature:**
```bash
1. Copy templates/task-template.md to tasks/active/
2. Fill in the details
3. Tell Claude: "Start TASK-XXX"
4. Monitor progress in the task file
```

**Daily check:**
```bash
1. Read reports/daily/YYYY-MM-DD.md
2. Review tasks/active/
3. Check for blockers
4. Give approvals or feedback
```

### For AI Agents

**Before starting work:**
```bash
1. Read .ai-instructions/[your-name].md
2. Check tasks/active/ for assigned tasks
3. Read relevant context/ files
4. Follow templates/ for output format
```

**During work:**
```bash
1. Update task files with progress
2. Document decisions in decisions/
3. Log important conversations in conversations/
4. Update context/ as you build
```

**After completing work:**
```bash
1. Move task to tasks/completed/
2. Write feature report in reports/features/
3. Update roadmap in progress/
4. Submit daily report
```

## 📋 Templates Available

- `task-template.md` - For creating new tasks
- `report-template.md` - For feature/analysis reports
- `decision-template.md` - For architectural decisions
- `conversation-template.md` - For AI conversations
- `daily-report-template.md` - For daily summaries

## 🤝 Collaboration Rules

See: `.ai-instructions/collaboration-rules.md`

## 📊 Metrics Tracked

- Tasks completed per day
- Code lines generated
- Bugs found and fixed
- Time spent per phase
- Review iterations

## 🔄 Workflow
```
PM creates task → Claude assigns to AI → AI executes → 
Claude reviews → PM approves → Mark complete → Report
```

## 📞 Support

**Blocked on something?**
1. Document in task file
2. Mark task as "Blocked"
3. Move to tasks/blocked/
4. Notify Project Manager

**Need clarification?**
1. Document question in task file
2. Tag as "Needs Clarification"
3. Wait for PM response

---

**Version:** 1.0
**Created:** 2026-02-01
**Project:** Maroof Smart Business Cards
**Team:** Claude Desktop, Continue (Local), Laravel Boost