# ElafSchool Project Roadmap & TODOs 🚀

This file tracks the evolution of the ElafSchool SaaS platform. Use it to stay focused and track wins!

## 📌 Active Development (Current Sprint)
- [ ] **Dynamic Dashboard Real-time Integration**: Connect Super Admin and Owner dashboards to live database metrics (currently static).
- [ ] **Student Enrollment UI Refinement**: Finalize the "multi-tab" or simplified enrollment form flow.
- [ ] **School Owner Profile**: Ensure school owners can manage their own profile and social landing page.

## 🏢 Core Modules Status

### 1. Administration & Governance ✅
- [x] Multi-School/Multi-Tenant structure.
- [x] Subscription Packages with limits (User/Staff/Student).
- [x] Campus management.
- [x] Unified Staff Management (Super Admin can see all, Owners see their own).

### 2. Student Management 🛠️
- [x] Core Student Profile & Meta-data (B-Form, CNIC).
- [x] Parent/Guardian details tracking.
- [x] Basic Admission form (English/Urdu).
- [ ] **Student Promotions & Transfers** (Move students between classes/campuses).
- [ ] **Leaving/Alumni Certificates** (Automated generation).

### 3. Academic Engine 🛠️
- [x] Grade Levels & Sections.
- [x] Subjects by Grade.
- [x] Exam Terms & Schedule.
- [x] Basic Mark Entry foundation.
- [ ] **Advanced Grading Rules** (GPA calculation, weighted marks).
- [ ] **Automated Report Cards** (PDF Generation).

### 4. Fee & Finance 🛠️
- [x] Fee Categories & Structures.
- [x] PSID Generation for monthly billing.
- [x] Basic Collection Tracking.
- [ ] **Expense Management** (Petty cash, school-level expenses).
- [ ] **Global Financial Reporting** (Super Admin revenue overview).

### 5. Multi-Tenant Landing Pages 🛠️
- [x] Public landing page by Slug (`/s/school-slug`).
- [ ] **Branding Customization** (Upload logo, pick theme colors from dashboard).

---

## 🛠️ Developer Tips
- **TODO Tree**: Install the "Todo Tree" extension in VS Code. It will automatically find `// TODO:` tags in your code.
- **Git Flow**: Use this file to write commit messages! "Completed: [Task from TODO.md]"
- **Antigravity Support**: I will automatically check off items here as we work together.

---

## ✅ Completed Log
- `2026-04-03`: Unified Staff Management for Super Admin & Owners.
- `2026-04-03`: Implemented School Capacity Monitoring (Users/Staff/Students limits).
- `2026-04-03`: Revamped Super Admin Dashboard with Falcon Charts.
