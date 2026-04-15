Brief Requirements:

REQUIRED MODULES - STUDENT INFORMATION - FACULTY INFORMATION - INSTRUCTION (SYLLABUS, LESSONS, CURRICULUM) - SCHEDULING (COURSE, SECTION, ROOMS, LAB, FACULTY) - EVENTS (CURRICULAR, AND EXTRA-CURRICULAR) - COMPREHENSIVE SEARCHES/FILTER

---

# 📄 Product Requirement Document (PRD)

**Project Name:** CCS Comprehensive Profiling System
**Target Organization:** College of Computing Studies (CCS), University of Cabuyao (Pamantasan ng Cabuyao)
**Tech Stack:** React.js (Frontend) & Laravel (Backend API)
**Document Version:** 1.0

## 1. Product Vision & Objective

To develop a centralized, robust profiling and management system for the College of Computing Studies (CCS). The system will act as a comprehensive hub—potentially integrating with or complementing the existing **Pinnacle LMS**—to track student and faculty profiles, academic/non-academic histories, health and disciplinary records, instruction, and scheduling. A key feature is the **Comprehensive Search/Filter**, enabling administrators to easily profile students based on specific criteria (e.g., skills, GPA, medical clearance) for events, competitions, or interventions.

## 2. Target Audience & User Roles

- **System Administrators / CCS Deans & Staff:** Full access to manage all modules, configure the curriculum, and utilize comprehensive profiling/filtering.
- **Faculty Members:** Access to view their schedules (`FACULTY_ASSIGNMENTS`), assigned sections, and view profiles of students within their classes.
- **Students:** Access to view their own comprehensive profiles (academic history, skills, medical records, affiliations, and schedules), similar to the _Pinnacle LMS_ dashboard experience shown in the references.

## 3. UI/UX & Design Guidelines (Based on Provided Images)

- **Branding:**
  - Primary Colors: University of Cabuyao Green and White (as seen in the Pinnacle LMS and University banners).
  - Secondary/Departmental Colors: CCS Orange and Black (as seen in the CCS logo).
- **Layout Structure:** A clean, modern sidebar navigation approach (similar to the Pinnacle LMS UI). Sidebar links should include: _Dashboard, User Profiles, Academics, Scheduling, Events, Reports/Filters_.
- **Typography & Components:** Rounded, soft-edged cards for data visualization, clear input fields, and distinct call-to-action buttons (like the green "LOGIN" button).

## 4. Functional Requirements & Modules

### Module 1: Student Information Management

- **Core Profile:** Manage basic demographics, contact details, and physical stats (Height, Weight, BMI).
- **Medical History:** Track medical conditions, allergies, and last checkup dates.
- **Skills Profiling:** Assign skills from the `SKILL_MASTER` to students with specific proficiency levels.
- **Affiliations & Violations:** Track organizational roles (`STUDENT_AFFILIATIONS`) and disciplinary records with clearance statuses (`STUDENT_VIOLATIONS`).
- **Academic & Non-Academic Tracking:** Record grades per semester (`STUDENT_ACADEMIC_HISTORY`) and event participation results (`STUDENT_NON_ACADEMIC_HISTORY`).

### Module 2: Faculty Information

- **Faculty Profiles:** Manage faculty details, departmental affiliations, and their specific specializations.
- **Workload/Assignments:** Track which subjects and sections a faculty member is teaching per semester.

### Module 3: Instruction (Curriculum & Academics)

- **Course & Subject Setup:** Define programs (e.g., BS IT, BS CS, BS IS) and the subjects under them.
- **Curriculum Mapping:** Map specific subjects to specific year levels and courses to establish prerequisites and graduation requirements.
- _Note for Developers:_ The requirements mention "Syllabus and Lessons", but the ERD currently lacks these. We will need to create `SYLLABUS` and `LESSONS` tables linked to `SUBJECTS`.

### Module 4: Scheduling

- **Section Management:** Group students into sections linked to their respective courses and year levels.
- **Faculty Assignments:** Assign faculty to specific sections and subjects per semester/school year.
- _Note for Developers:_ The requirements mention "Rooms and Labs", but they are missing from the ERD. We must introduce `ROOMS` and `LABS` tables and link them to `FACULTY_ASSIGNMENTS` or a new `SCHEDULES` table.

### Module 5: Events Tracking

- **Event Creation:** Admins can create curricular and extra-curricular events.
- **Requirement Filtering:** Events can be configured with a `required_gpa` or `required_skill`.
- **Participation Tracking:** Log student roles and results in these events.

### Module 6: Comprehensive Searches / Filters (The "Profiling" Engine)

- **Advanced Query Builder:** Allow admins to generate custom reports and lists.
  - _Example Use Case:_ "Show all 3rd Year BSCS students with a GPA higher than 2.0, possessing 'Programming' skills, and with no active violations."
  - _Example Use Case:_ "Find students with 'Sports' skills who are medically cleared (no severe medical conditions)."

## 5. Technical Requirements & Database Adjustments

### Backend (Laravel)

- **API Development:** Build RESTful APIs for all modules.
- **Authentication:** Use Laravel Sanctum or Passport for role-based access control (Admin, Faculty, Student).
- **Database Setup:** Translate the provided ERD into Laravel Migrations.
  - _Action Required:_ Add standard Laravel timestamps (`created_at`, `updated_at`, `deleted_at` for soft deletes) to all tables.

### Frontend (React.js)

- **Framework:** React (Next.js is recommended if SEO or SSR is needed, otherwise standard Vite + React).
- **State Management:** Redux Toolkit or React Query for fetching and caching API data.
- **Styling:** TailwindCSS is highly recommended to easily replicate the modern, clean UI of the Pinnacle LMS.

### Missing ERD Entities to be added during Development:

To fully satisfy the "Required Modules" requested, the dev team must design and add the following tables:

1.  `ROOMS` (room_id, room_name, type [Lecture/Lab], capacity)
2.  `SYLLABUS` (syllabus_id, subject_id, description, objective)
3.  `LESSONS` (lesson_id, syllabus_id, title, content)
4.  Update `FACULTY_ASSIGNMENTS` to include `room_id` and specific time slots (days, start_time, end_time).

## 6. Implementation Phases

- **Phase 1: Database & Authentication:** Setup Laravel, run migrations, seed initial data (Skills, Courses, Subjects), and setup Role-based login.
- **Phase 2: Core CRUD operations:** Build backend and frontend interfaces for Students, Faculty, and Instruction modules.
- **Phase 3: Scheduling & Events:** Implement Sections, Faculty assignments (with rooms/labs), and event tracking.
- **Phase 4: Comprehensive Profiling UI:** Build the advanced React filtering interface using complex Laravel Eloquent queries.
- **Phase 5: UI Polish:** Refine the interface to match the University of Cabuyao / CCS branding.

---

ERD Diagram Context:

Based on the entity-relationship diagram provided, here is the extracted information from every box, organized for easy comprehension. I have separated them by their table names and listed their respective fields (columns), including Primary Key (PK) and Foreign Key (FK) indicators.

### **STUDENTS**

- student_id (PK)
- student_number
- first_name
- middle_name
- last_name
- gender
- birthdate
- email
- contact_number
- guardian_name
- guardian_contact
- height
- weight
- bmi
- course_id (FK)
- section_id (FK)
- year_level
- enrollment_status
- overall_gpa

---

### **SKILL_MASTER**

- skill_id (PK)
- skill_name
- skill_category
- _(Programming, Sports, Arts, Leadership)_

---

### **EVENTS**

- event_id (PK)
- event_name
- category
- _(Sports, Academic, Cultural)_
- required_gpa
- required_skill

---

### **COURSES**

- course_id (PK)
- course_code
- course_name
- department

---

### **CURRICULUM**

- curriculum_id (PK)
- course_id (FK)
- subject_id (FK)
- year_level

---

### **SUBJECTS**

- subject_id (PK)
- subject_code
- subject_name
- units

---

### **STUDENT_AFFILIATIONS**

- affiliation_id (PK)
- student_id (FK)
- organization_name
- role
- status

---

### **STUDENT_VIOLATIONS**

- violation_id (PK)
- student_id (FK)
- violation_type
- severity_level
- violation_date
- clearance_status

---

### **STUDENT_SKILLS**

- student_skill_id (PK)
- student_id (FK)
- skill_id (FK)
- skill_level

---

### **STUDENT_ACADEMIC_HISTORY**

- academic_id (PK)
- student_id (FK)
- subject_id (FK)
- grade
- semester
- school_year

---

### **STUDENT_NON_ACADEMIC_HISTORY**

- participation_id (PK)
- student_id (FK)
- event_id (FK)
- role
- result

---

### **SECTIONS**

- section_id (PK)
- section_name
- course_id (FK)
- year_level

---

### **STUDENT_MEDICAL_HISTORY**

- medical_id (PK)
- student_id (FK)
- medical_condition
- allergies
- medications
- last_checkup_date
- notes

---

### **FACULTY**

- faculty_id (PK)
- first_name
- last_name
- department
- specialization

---

### **FACULTY_ASSIGNMENTS**

- assignment_id (PK)
- faculty_id (FK)
- subject_id (FK)
- section_id (FK)
- semester
- school_year

Task:

Currently, what can we continue on working on? This is a group project that is assigned by parts

Explore our project directory

Then provide a proposal

---

Based on my exploration of your CCS Comprehensive Profiling System, here's a detailed analysis and proposal:

## 📊 Current Project Status

**Completed Modules (80% done):**

- ✅ Core database schema (students, faculty, courses, subjects, sections, events, skills)
- ✅ Authentication & role-based access (dean/faculty/student)
- ✅ Student profile management (academic history, skills, violations, medical records)
- ✅ Faculty management & basic assignments
- ✅ Dashboard views for all three roles
- ✅ Advanced reporting & filtering system
- ✅ Event registration system

**Missing/Incomplete Modules:**

## 🎯 Proposal: What Can Be Worked On Next

### **Option 1: Notifications System** (High Priority, Medium Complexity)

**Status:** Controller exists but no database table or routes

**Tasks:**

- Create [`notifications`](backend/database/migrations) migration table
- Register notification routes in [`api.php`](backend/routes/api.php:1)
- Build frontend notification bell/dropdown component
- Add real-time notification triggers (new events, grade posted, violations)

**Assignable to:** 1 developer, ~3-5 days

---

### **Option 2: Rooms & Labs Management** (High Priority, Medium Complexity)

**Status:** Mentioned in PRD but not implemented

**Tasks:**

- Create [`ROOMS`](backend/database/migrations) and [`LABS`](backend/database/migrations) migration tables
- Build RoomController and LabController
- Update [`FacultyAssignment`](backend/app/Models/FacultyAssignment.php:1) to use room_id FK instead of string
- Create Dean UI for room/lab management
- Add room availability checker for scheduling conflicts

**Assignable to:** 1-2 developers, ~4-6 days

---

### **Option 3: Syllabus & Lessons Module** (Medium Priority, High Complexity)

**Status:** Explicitly missing from ERD per PRD

**Tasks:**

- Create [`SYLLABUS`](backend/database/migrations) and [`LESSONS`](backend/database/migrations) tables
- Build Syllabus and Lesson models/controllers
- Link to [`subjects`](backend/app/Models/Subject.php:1) table
- Create faculty UI for syllabus creation/editing
- Create student UI for viewing syllabus & lessons
- Add file upload for lesson materials

**Assignable to:** 2 developers, ~5-7 days

---

### **Option 4: Enhanced Scheduling Interface** (High Priority, High Complexity)

**Status:** [`DeanScheduling.tsx`](frontend/src/ui/pages/dean/DeanScheduling.tsx:1) is just a placeholder

**Tasks:**

- Build full CRUD UI for faculty assignments in [`DeanScheduling.tsx`](frontend/src/ui/pages/dean/DeanScheduling.tsx:1)
- Add time slot conflict detection
- Create visual schedule grid/calendar view
- Integrate with rooms system (Option 2)
- Add bulk import for schedules (CSV/Excel)

**Assignable to:** 2 developers, ~6-8 days

---

### **Option 5: Curriculum Management Enhancement** (Medium Priority, Medium Complexity)

**Status:** [`DeanCurriculum.tsx`](frontend/src/ui/pages/dean/DeanCurriculum.tsx:1) is read-only

**Tasks:**

- Enhance [`CurriculumController`](backend/app/Http/Controllers/Api/CurriculumController.php:1) with full CRUD
- Build UI for adding/editing curriculum mappings
- Add prerequisite chain visualization
- Create curriculum version management
- Add curriculum export/print functionality

**Assignable to:** 1-2 developers, ~4-5 days

---

### **Option 6: Grade Management System** (Medium Priority, Medium Complexity)

**Status:** Basic UI exists, needs backend enhancement

**Tasks:**

- Enhance grade entry validation in [`StudentController`](backend/app/Http/Controllers/Api/StudentController.php:1)
- Add bulk grade import (CSV/Excel)
- Create grade computation rules (midterm/finals weighting)
- Add grade submission workflow (draft → submitted → locked)
- Build grade analytics dashboard

**Assignable to:** 1-2 developers, ~4-6 days

---

## 🎨 Recommended Approach for Group Work

**Phase 1 (Parallel Development):**

- **Team Member A:** Notifications System (Option 1)
- **Team Member B:** Rooms & Labs (Option 2)
- **Team Member C:** Curriculum Enhancement (Option 5)

**Phase 2 (After Phase 1):**

- **Team Member A+B:** Enhanced Scheduling (Option 4) - requires rooms
- **Team Member C:** Syllabus & Lessons (Option 3)

**Phase 3 (Polish):**

- Grade Management (Option 6)
- UI/UX refinements
- Testing & bug fixes
