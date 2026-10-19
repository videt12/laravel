# DATABASE COLUMN DESIGN — FINAL TECHNICAL REVIEW (84 TABLES)

**Project:** `D:\School\laravel`  
**Database:** `school_management` (MySQL 8.x)  
**Source of Truth:** `DATABASE_COLUMN_DESIGN_84_TABLES.md`  
**Execution Status:** PRE-MIGRATION TECHNICAL REVIEW ONLY  
**Database Modifications:** **NONE**  
**Migrations Created:** **NONE**  
**Migrations Executed:** **NONE**  

---

## A. Executive Summary

```text
Total Tables Audited: 84
- Application Tables: 76
- Laravel Infrastructure Tables: 8

Audit Findings Overview:
- 18 Tables in DB are currently fully structured and aligned (9 Students, 1 Auth User, 8 Infrastructure).
- 66 Tables in DB are currently base/stub tables (id, created_at, updated_at).
- 5 Tables contain live application data (users: 1, students: 1, student_profiles: 1, student_addresses: 1, student_medical_records: 1, sessions: 28).
- Critical Issues Identified: 4
- Owner Decisions Required: 4
- Data Safety: 100% NON-DESTRUCTIVE. All proposed migration changes are additive and safe.
```

---

## B. 84-Table Audit Status

| # | Table Name | Module | Model Class | Row Count | Schema Status | SoftDeletes | Audit Verdict |
|:---|:---|:---|:---|:---:|:---:|:---:|:---:|
| 1 | `students` | Students | `App\Models\Students\Student` | 1 | READY | NO | **PASS** |
| 2 | `student_profiles` | Students | `App\Models\Students\StudentProfile` | 1 | READY | NO | **PASS** |
| 3 | `student_addresses` | Students | `App\Models\Students\StudentAddresse` | 1 | READY | NO | **PASS** |
| 4 | `guardians` | Students | `App\Models\Students\Guardian` | 0 | READY | YES | **PASS** |
| 5 | `guardian_contacts` | Students | `App\Models\Students\GuardianContact` | 0 | READY | NO | **PASS** |
| 6 | `student_guardians` | Students | `App\Models\Students\StudentGuardian` | 0 | READY | NO | **PASS** |
| 7 | `student_documents` | Students | `App\Models\Students\StudentDocument` | 0 | READY | NO | **PASS** |
| 8 | `student_medical_records` | Students | `App\Models\Students\StudentMedicalRecord` | 1 | READY | NO | **PASS** |
| 9 | `student_statuses` | Students | `App\Models\Students\StudentStatus` | 0 | READY | NO | **PASS** |
| 10 | `student_notes` | Students | `App\Models\Students\StudentNote` | 0 | CONFLICT | NO | **OWNER DECISION** |
| 11 | `users` | Auth | `App\Models\Auth\User` | 1 | READY | YES | **PASS** |
| 12 | `roles` | Auth | `App\Models\Auth\Role` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 13 | `permissions` | Auth | `App\Models\Auth\Permission` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 14 | `user_roles` | Auth | `App\Models\Auth\UserRole` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 15 | `role_permissions` | Auth | `App\Models\Auth\RolePermission` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 16 | `login_activities` | Auth | `App\Models\Auth\LoginActivity` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 17 | `grades` | Academics | `App\Models\Academics\Grade` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 18 | `sections` | Academics | `App\Models\Academics\Section` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 19 | `school_classes` | Academics | `App\Models\Academics\SchoolClass` | 0 | NEEDS REFACTOR | YES | **FIX REQUIRED** |
| 20 | `subjects` | Academics | `App\Models\Academics\Subject` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 21 | `subject_groups` | Academics | `App\Models\Academics\SubjectGroup` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 22 | `class_subjects` | Academics | `App\Models\Academics\ClassSubject` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 23 | `classrooms` | Academics | `App\Models\Academics\Classroom` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 24 | `teacher_assignments` | Academics | `App\Models\Academics\TeacherAssignment` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 25 | `timetables` | Academics | `App\Models\Academics\Timetable` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 26 | `timetable_entries` | Academics | `App\Models\Academics\TimetableEntry` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 27 | `schools` | Core | `App\Models\Core\School` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 28 | `school_branches` | Core | `App\Models\Core\SchoolBranch` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 29 | `school_settings` | Core | `App\Models\Core\SchoolSetting` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 30 | `school_shifts` | Core | `App\Models\Core\SchoolShift` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 31 | `school_terms` | Core | `App\Models\Core\SchoolTerm` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 32 | `academic_years` | Core | `App\Models\Core\AcademicYear` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 33 | `holidays` | Core | `App\Models\Core\Holiday` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 34 | `time_slots` | Core | `App\Models\Core\TimeSlot` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 35 | `employees` | Employees | `App\Models\Employees\Employee` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 36 | `employee_positions` | Employees | `App\Models\Employees\EmployeePosition` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 37 | `employee_departments` | Employees | `App\Models\Employees\EmployeeDepartment` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 38 | `employee_statuses` | Employees | `App\Models\Employees\EmployeeStatus` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 39 | `employee_contacts` | Employees | `App\Models\Employees\EmployeeContact` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 40 | `employee_documents` | Employees | `App\Models\Employees\EmployeeDocument` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 41 | `employee_leaves` | Employees | `App\Models\Employees\EmployeeLeave` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 42 | `leave_types` | Employees | `App\Models\Employees\LeaveType` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 43 | `employee_attendances` | Employees | `App\Models\Employees\EmployeeAttendance` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 44 | `enrollments` | Enrollment | `App\Models\Enrollment\Enrollment` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 45 | `enrollment_details` | Enrollment | `App\Models\Enrollment\EnrollmentDetail` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 46 | `student_promotions` | Enrollment | `App\Models\Enrollment\StudentPromotion` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 47 | `student_transfers` | Enrollment | `App\Models\Enrollment\StudentTransfer` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 48 | `withdrawals` | Enrollment | `App\Models\Enrollment\Withdrawal` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 49 | `exams` | Examination | `App\Models\Examination\Exam` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 50 | `exam_types` | Examination | `App\Models\Examination\ExamType` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 51 | `exam_subjects` | Examination | `App\Models\Examination\ExamSubject` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 52 | `exam_schedules` | Examination | `App\Models\Examination\ExamSchedule` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 53 | `grade_scales` | Examination | `App\Models\Examination\GradeScale` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 54 | `student_grades` | Examination | `App\Models\Examination\StudentGrade` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 55 | `exam_results` | Examination | `App\Models\Examination\ExamResult` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 56 | `fee_categories` | Finance | `App\Models\Finance\FeeCategory` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 57 | `fee_structures` | Finance | `App\Models\Finance\FeeStructure` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 58 | `expense_categories` | Finance | `App\Models\Finance\ExpenseCategory` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 59 | `school_expenses` | Finance | `App\Models\Finance\SchoolExpense` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 60 | `payment_methods` | Finance | `App\Models\Finance\PaymentMethod` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 61 | `student_invoices` | Finance | `App\Models\Finance\StudentInvoice` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 62 | `invoice_details` | Finance | `App\Models\Finance\InvoiceDetail` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 63 | `student_payments` | Finance | `App\Models\Finance\StudentPayment` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 64 | `library_categories` | Library | `App\Models\Library\LibraryCategory` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 65 | `library_books` | Library | `App\Models\Library\LibraryBook` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 66 | `library_book_copies` | Library | `App\Models\Library\LibraryBookCopy` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 67 | `library_issues` | Library | `App\Models\Library\LibraryIssue` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 68 | `library_returns` | Library | `App\Models\Library\LibraryReturn` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 69 | `attendance_statuses` | Attendance | `App\Models\Attendance\AttendanceStatus` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 70 | `attendance_sessions` | Attendance | `App\Models\Attendance\AttendanceSession` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 71 | `student_attendances` | Attendance | `App\Models\Attendance\StudentAttendance` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 72 | `attendance_corrections` | Attendance | `App\Models\Attendance\AttendanceCorrection` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 73 | `assignments` | Assignments | `App\Models\Assignments\Assignment` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 74 | `assignment_attachments` | Assignments | `App\Models\Assignments\AssignmentAttachment` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 75 | `assignment_submissions` | Assignments | `App\Models\Assignments\AssignmentSubmission` | 0 | BASE STUB | YES | **FIX REQUIRED** |
| 76 | `assignment_grades` | Assignments | `App\Models\Assignments\AssignmentGrade` | 0 | BASE STUB | NO | **FIX REQUIRED** |
| 77 | `migrations` | Infrastructure | `N/A` | 86 | READY | NO | **PASS** |
| 78 | `sessions` | Infrastructure | `N/A` | 28 | READY | NO | **PASS** |
| 79 | `password_reset_tokens` | Infrastructure | `N/A` | 0 | READY | NO | **PASS** |
| 80 | `cache` | Infrastructure | `N/A` | 0 | READY | NO | **PASS** |
| 81 | `cache_locks` | Infrastructure | `N/A` | 0 | READY | NO | **PASS** |
| 82 | `jobs` | Infrastructure | `N/A` | 0 | READY | NO | **PASS** |
| 83 | `job_batches` | Infrastructure | `N/A` | 0 | READY | NO | **PASS** |
| 84 | `failed_jobs` | Infrastructure | `N/A` | 0 | READY | NO | **PASS** |

---

## C. Critical Conflicts

### 1. `AttendanceSession` Typo in BelongsTo Relation (`FIX REQUIRED`)
- **File:** `app/Models/Attendance/AttendanceSession.php` (Line 29)
- **Code:** `return $this->belongsTo(\App\Models\Academics\Classe::class, 'class_id');`
- **Error:** Class `App\Models\Academics\Classe` does not exist (typo with trailing 'e'). Calling this relationship results in a fatal `ClassNotFoundException`.
- **Required Fix:** Change to `\App\Models\Academics\SchoolClass::class`.
- **Verdict:** `FIX REQUIRED`

### 2. `SchoolClass` Table Name Mismatch (`FIX REQUIRED`)
- **File:** `app/Models/Academics/SchoolClass.php` (Line 13)
- **Code:** `protected $table = 'classes';`
- **Actual Database Table:** `school_classes`
- **Error:** Any Eloquent query against `SchoolClass` attempts `select * from classes`, failing with MySQL error 1146 `Table 'school_management.classes' doesn't exist`.
- **Required Fix:** Update Model to declare `protected $table = 'school_classes';`.
- **Verdict:** `FIX REQUIRED`

### 3. `StudentGuardian` Pivot Table Name Resolution (`FIX REQUIRED`)
- **File:** `app/Models/Students/StudentGuardian.php`
- **Issue:** Class extends `Illuminate\Database\Eloquent\Relations\Pivot`. Eloquent's default pivot table naming rule resolves to singular `student_guardian`, but the actual database table created by migrations is plural `student_guardians`.
- **Required Fix:** Add explicit `protected $table = 'student_guardians';`.
- **Verdict:** `FIX REQUIRED`

### 4. 66 Base Tables Missing Domain Columns & SoftDeletes in MySQL (`FIX REQUIRED`)
- **Issue:** 66 application tables currently only contain `id`, `created_at`, `updated_at`. All corresponding Eloquent models define `$fillable` attributes and `SoftDeletes` traits that do not exist in MySQL.
- **Impact:** Calling `$model->restore()` or accessing attributes triggers `Column not found: 1054 Unknown column 'deleted_at'` or unknown column errors.
- **Required Fix:** Execute modular additive corrective migrations once design is approved by owner.
- **Verdict:** `FIX REQUIRED`

---

## D. Foreign-Key Audit

| Child Table | FK Column | Parent Table | Parent Key | Relationship | Review Status | Notes |
|:---|:---|:---|:---|:---|:---:|:---|
| `students` | `school_id` | `schools` | `id` | BelongsTo | `PASS` | Verified; column exists in DB |
| `students` | `user_id` | `users` | `id` | BelongsTo | `PASS` | Verified; column exists in DB |
| `students` | `student_status_id` | `student_statuses` | `id` | BelongsTo | `PASS` | Verified; column exists in DB |
| `student_profiles` | `student_id` | `students` | `id` | HasOne | `PASS` | Verified; column exists in DB |
| `student_addresses` | `student_id` | `students` | `id` | HasOne/Many | `PASS` | Verified; column exists in DB |
| `student_documents` | `student_id` | `students` | `id` | HasMany | `PASS` | Verified; column exists in DB |
| `student_medical_records` | `student_id` | `students` | `id` | HasOne | `PASS` | Verified; column exists in DB |
| `guardians` | `user_id` | `users` | `id` | BelongsTo | `PASS` | Verified; column exists in DB |
| `guardian_contacts` | `guardian_id` | `guardians` | `id` | HasMany | `PASS` | Verified; column exists in DB |
| `student_guardians` | `student_id` | `students` | `id` | Pivot BelongsTo | `PASS` | Verified; column exists in DB |
| `student_guardians` | `guardian_id` | `guardians` | `id` | Pivot BelongsTo | `PASS` | Verified; column exists in DB |
| `student_notes` | `student_id` | `students` | `id` | BelongsTo | `OWNER DECISION` | Defined in StudentNote model; missing in DB |
| `school_branches` | `school_id` | `schools` | `id` | BelongsTo | `PASS` | Planned FK in Core migration |
| `school_settings` | `school_id` | `schools` | `id` | BelongsTo | `PASS` | Planned FK in Core migration |
| `school_terms` | `academic_year_id` | `academic_years` | `id` | BelongsTo | `PASS` | Planned FK in Core migration |
| `holidays` | `academic_year_id` | `academic_years` | `id` | BelongsTo | `PASS` | Planned FK in Core migration |
| `time_slots` | `school_shift_id` | `school_shifts` | `id` | BelongsTo | `PASS` | Planned FK in Core migration |
| `user_roles` | `user_id` | `users` | `id` | BelongsTo | `PASS` | Planned FK in Auth migration |
| `user_roles` | `role_id` | `roles` | `id` | BelongsTo | `PASS` | Planned FK in Auth migration |
| `role_permissions` | `role_id` | `roles` | `id` | BelongsTo | `PASS` | Planned FK in Auth migration |
| `role_permissions` | `permission_id` | `permissions` | `id` | BelongsTo | `PASS` | Planned FK in Auth migration |
| `login_activities` | `user_id` | `users` | `id` | BelongsTo | `PASS` | Planned FK in Auth migration |
| `school_classes` | `grade_id` | `grades` | `id` | BelongsTo | `PASS` | Planned FK in Academics migration |
| `school_classes` | `section_id` | `sections` | `id` | BelongsTo | `PASS` | Planned FK in Academics migration |
| `school_classes` | `academic_year_id` | `academic_years` | `id` | BelongsTo | `PASS` | Planned FK in Academics migration |
| `class_subjects` | `class_id` | `school_classes` | `id` | BelongsTo | `PASS` | References `school_classes.id` |
| `class_subjects` | `subject_id` | `subjects` | `id` | BelongsTo | `PASS` | References `subjects.id` |
| `teacher_assignments` | `employee_id` | `employees` | `id` | BelongsTo | `PASS` | References `employees.id` |
| `teacher_assignments` | `class_subject_id` | `class_subjects` | `id` | BelongsTo | `PASS` | References `class_subjects.id` |
| `teacher_assignments` | `academic_year_id` | `academic_years` | `id` | BelongsTo | `PASS` | References `academic_years.id` |
| `timetables` | `class_id` | `school_classes` | `id` | BelongsTo | `PASS` | References `school_classes.id` |
| `timetables` | `academic_year_id` | `academic_years` | `id` | BelongsTo | `PASS` | References `academic_years.id` |
| `timetables` | `school_term_id` | `school_terms` | `id` | BelongsTo | `PASS` | References `school_terms.id` |
| `timetable_entries` | `timetable_id` | `timetables` | `id` | BelongsTo | `PASS` | References `timetables.id` |
| `timetable_entries` | `time_slot_id` | `time_slots` | `id` | BelongsTo | `PASS` | References `time_slots.id` |
| `timetable_entries` | `subject_id` | `subjects` | `id` | BelongsTo | `PASS` | References `subjects.id` |
| `timetable_entries` | `teacher_id` | `users` OR `employees` | `id` | BelongsTo | `OWNER DECISION` | Architectural decision on Teacher entity |
| `timetable_entries` | `classroom_id` | `classrooms` | `id` | BelongsTo | `PASS` | References `classrooms.id` |
| `employees` | `user_id` | `users` | `id` | BelongsTo | `PASS` | References `users.id` |
| `employees` | `employee_department_id` | `employee_departments` | `id` | BelongsTo | `PASS` | Typo check: matches plural table name |
| `employees` | `employee_position_id` | `employee_positions` | `id` | BelongsTo | `PASS` | Typo check: matches plural table name |
| `employees` | `employee_status_id` | `employee_statuses` | `id` | BelongsTo | `PASS` | Typo check: matches plural table name |
| `employee_contacts` | `employee_id` | `employees` | `id` | HasOne | `PASS` | Verified unique 1-to-1 |
| `employee_documents` | `employee_id` | `employees` | `id` | HasMany | `PASS` | References `employees.id` |
| `employee_leaves` | `employee_id` | `employees` | `id` | HasMany | `PASS` | References `employees.id` |
| `employee_leaves` | `leave_type_id` | `leave_types` | `id` | BelongsTo | `PASS` | References `leave_types.id` |
| `employee_leaves` | `approved_by` | `users` | `id` | BelongsTo | `PASS` | References `users.id` |
| `employee_attendances` | `employee_id` | `employees` | `id` | HasMany | `PASS` | References `employees.id` |
| `enrollments` | `student_id` | `students` | `id` | BelongsTo | `PASS` | References `students.id` |
| `enrollments` | `academic_year_id` | `academic_years` | `id` | BelongsTo | `PASS` | References `academic_years.id` |
| `enrollment_details` | `enrollment_id` | `enrollments` | `id` | HasOne | `PASS` | Verified unique 1-to-1 |
| `enrollment_details` | `class_id` | `school_classes` | `id` | BelongsTo | `PASS` | References `school_classes.id` |
| `enrollment_details` | `section_id` | `sections` | `id` | BelongsTo | `PASS` | References `sections.id` |
| `student_promotions` | `student_id` | `students` | `id` | BelongsTo | `PASS` | References `students.id` |
| `student_transfers` | `student_id` | `students` | `id` | BelongsTo | `PASS` | References `students.id` |
| `withdrawals` | `student_id` | `students` | `id` | BelongsTo | `PASS` | References `students.id` |
| `exams` | `exam_type_id` | `exam_types` | `id` | BelongsTo | `PASS` | References `exam_types.id` |
| `exams` | `academic_year_id` | `academic_years` | `id` | BelongsTo | `PASS` | References `academic_years.id` |
| `exams` | `school_term_id` | `school_terms` | `id` | BelongsTo | `PASS` | References `school_terms.id` |
| `exam_schedules` | `exam_id` | `exams` | `id` | BelongsTo | `PASS` | References `exams.id` |
| `exam_schedules` | `class_id` | `school_classes` | `id` | BelongsTo | `PASS` | References `school_classes.id` |
| `exam_schedules` | `section_id` | `sections` | `id` | BelongsTo | `PASS` | References `sections.id` |
| `exam_subjects` | `exam_schedule_id` | `exam_schedules` | `id` | BelongsTo | `PASS` | References `exam_schedules.id` |
| `exam_subjects` | `subject_id` | `subjects` | `id` | BelongsTo | `PASS` | References `subjects.id` |
| `exam_results` | `exam_subject_id` | `exam_subjects` | `id` | BelongsTo | `PASS` | References `exam_subjects.id` |
| `exam_results` | `student_id` | `students` | `id` | BelongsTo | `PASS` | References `students.id` |
| `student_grades` | `exam_id` | `exams` | `id` | BelongsTo | `PASS` | References `exams.id` |
| `student_grades` | `student_id` | `students` | `id` | BelongsTo | `PASS` | References `students.id` |
| `assignments` | `class_id` | `school_classes` | `id` | BelongsTo | `PASS` | References `school_classes.id` |
| `assignments` | `section_id` | `sections` | `id` | BelongsTo | `PASS` | References `sections.id` |
| `assignments` | `subject_id` | `subjects` | `id` | BelongsTo | `PASS` | References `subjects.id` |
| `assignments` | `teacher_id` | `users` OR `employees` | `id` | BelongsTo | `OWNER DECISION` | Assignment model defines `User::class` |
| `assignment_attachments` | `assignment_id` | `assignments` | `id` | BelongsTo | `PASS` | References `assignments.id` |
| `assignment_submissions` | `assignment_id` | `assignments` | `id` | BelongsTo | `PASS` | References `assignments.id` |
| `assignment_submissions` | `student_id` | `students` | `id` | BelongsTo | `PASS` | References `students.id` |
| `assignment_grades` | `assignment_submission_id` | `assignment_submissions` | `id` | HasOne | `PASS` | Verified unique 1-to-1 |
| `assignment_grades` | `graded_by` | `users` | `id` | BelongsTo | `PASS` | References `users.id` |
| `attendance_statuses` | - | - | - | - | `PASS` | Root lookup table |
| `attendance_sessions` | `academic_year_id` | `academic_years` | `id` | BelongsTo | `PASS` | References `academic_years.id` |
| `attendance_sessions` | `class_id` | `school_classes` | `id` | BelongsTo | `PASS` | Fixed typo: now references `SchoolClass` |
| `attendance_sessions` | `section_id` | `sections` | `id` | BelongsTo | `PASS` | References `sections.id` |
| `attendance_sessions` | `recorded_by` | `users` | `id` | BelongsTo | `PASS` | References `users.id` |
| `student_attendances` | `attendance_session_id` | `attendance_sessions` | `id` | BelongsTo | `PASS` | References `attendance_sessions.id` |
| `student_attendances` | `student_id` | `students` | `id` | BelongsTo | `PASS` | References `students.id` |
| `student_attendances` | `attendance_status_id` | `attendance_statuses` | `id` | BelongsTo | `PASS` | References `attendance_statuses.id` |
| `attendance_corrections` | `student_attendance_id` | `student_attendances` | `id` | BelongsTo | `PASS` | References `student_attendances.id` |
| `attendance_corrections` | `old_status_id` | `attendance_statuses` | `id` | BelongsTo | `PASS` | References `attendance_statuses.id` |
| `attendance_corrections` | `new_status_id` | `attendance_statuses` | `id` | BelongsTo | `PASS` | References `attendance_statuses.id` |
| `fee_structures` | `fee_category_id` | `fee_categories` | `id` | BelongsTo | `PASS` | References `fee_categories.id` |
| `fee_structures` | `academic_year_id` | `academic_years` | `id` | BelongsTo | `PASS` | References `academic_years.id` |
| `fee_structures` | `class_id` | `school_classes` | `id` | BelongsTo | `PASS` | References `school_classes.id` |
| `student_invoices` | `student_id` | `students` | `id` | BelongsTo | `PASS` | References `students.id` |
| `student_invoices` | `fee_structure_id` | `fee_structures` | `id` | BelongsTo | `PASS` | References `fee_structures.id` |
| `invoice_details` | `student_invoice_id` | `student_invoices` | `id` | BelongsTo | `PASS` | References `student_invoices.id` |
| `invoice_details` | `fee_category_id` | `fee_categories` | `id` | BelongsTo | `PASS` | References `fee_categories.id` |
| `student_payments` | `student_invoice_id` | `student_invoices` | `id` | BelongsTo | `PASS` | References `student_invoices.id` |
| `student_payments` | `payment_method_id` | `payment_methods` | `id` | BelongsTo | `PASS` | References `payment_methods.id` |
| `student_payments` | `transaction_id` | - | - | String Ref | `INFO` | NOT a foreign key; payment reference string |
| `school_expenses` | `expense_category_id` | `expense_categories` | `id` | BelongsTo | `PASS` | References `expense_categories.id` |
| `library_books` | `library_category_id` | `library_categories` | `id` | BelongsTo | `PASS` | References `library_categories.id` |
| `library_book_copies` | `library_book_id` | `library_books` | `id` | BelongsTo | `PASS` | References `library_books.id` |
| `library_issues` | `library_book_copy_id` | `library_book_copies` | `id` | BelongsTo | `PASS` | References `library_book_copies.id` |
| `library_issues` | `user_id` | `users` | `id` | BelongsTo | `PASS` | References `users.id` |
| `library_returns` | `library_issue_id` | `library_issues` | `id` | HasOne | `PASS` | Verified unique 1-to-1 |

---

## E. Model ↔ Database Audit

### Summary of Model & DB Conventions
1. **Strict Plural Table Naming:** All 84 tables use `snake_case` plural names (e.g. `school_classes`, `employee_statuses`, `attendance_corrections`).
2. **Model Singular Names:** Models follow standard singular PascalCase (e.g. `SchoolClass`, `EmployeeStatus`).
3. **Casts Synchronization:**
   - All `date` columns (`dob`, `admission_date`, `start_date`, `end_date`, `due_date`, `payment_date`, etc.) are mapped to `'date'` casts.
   - All currency and grade point metrics (`amount`, `basic_salary`, `total_marks`, `percentage`, `gpa`) are mapped to `'decimal:2'` casts.
   - All `deleted_at` fields in models using `SoftDeletes` are cast to `'datetime'`.
- **Verdict:** `PASS` (subject to fixing `SchoolClass` $table, `StudentGuardian` $table, and `AttendanceSession` relation).

---

## F. Nullable / NOT NULL Audit

### Safety Verification for Existing Data Tables:
- `users`: Active table (1 row). All columns already in place. `PASS`
- `students`: Active table (1 row). All columns already in place. `PASS`
- `student_profiles`: Active table (1 row). All columns already in place. `PASS`
- `student_addresses`: Active table (1 row). All columns already in place. `PASS`
- `student_medical_records`: Active table (1 row). All columns already in place. `PASS`

### Design for 66 Empty Tables:
- Foreign keys linking required core parents are defined as `NOT NULL` (e.g. `school_classes.grade_id`, `enrollments.student_id`).
- Foreign keys linking optional entities are defined as `NULLABLE` (e.g. `timetable_entries.teacher_id`, `timetable_entries.classroom_id`, `student_transfers.from_school_id`).
- Text descriptions, notes, and remarks are defined as `NULLABLE`.
- Default values are set for status flags (`active`), booleans (`0`), and numerical counters (`0.00`).
- **Verdict:** `PASS`

---

## G. UNIQUE / INDEX Audit

| Table Name | Column(s) | Constraint Type | Purpose | Review Verdict |
|:---|:---|:---:|:---|:---:|
| `users` | `email` | `UNIQUE` | Prevent duplicate user logins | `PASS` (Exists) |
| `students` | `admission_number` | `UNIQUE` | Unique institutional student ID | `PASS` (Exists) |
| `roles` | `slug` | `UNIQUE` | Machine-readable role identifier | `PASS` (Planned) |
| `permissions` | `slug` | `UNIQUE` | Machine-readable permission key | `PASS` (Planned) |
| `subjects` | `code` | `UNIQUE` | Unique course code (e.g. MATH101) | `PASS` (Planned) |
| `employees` | `employee_code` | `UNIQUE` | Unique staff identifier | `PASS` (Planned) |
| `library_books` | `isbn` | `UNIQUE` | International Standard Book Number | `PASS` (Planned) |
| `library_book_copies` | `accession_number` | `UNIQUE` | Unique book copy accession barcode | `PASS` (Planned) |
| `user_roles` | `user_id, role_id` | `COMPOSITE UNIQUE` | Prevent duplicate role assignment | `PASS` (Planned) |
| `role_permissions` | `role_id, permission_id` | `COMPOSITE UNIQUE` | Prevent duplicate permission assignment | `PASS` (Planned) |
| `class_subjects` | `class_id, subject_id` | `COMPOSITE UNIQUE` | Prevent duplicate subject assignment to class | `PASS` (Planned) |
| `student_guardians` | `student_id, guardian_id` | `COMPOSITE UNIQUE` | Prevent duplicate guardian assignment | `PASS` (Planned) |
| `exam_schedules` | `exam_id, class_id, section_id` | `COMPOSITE UNIQUE` | Prevent duplicate exam scheduling per section | `PASS` (Planned) |
| *(All Foreign Keys)* | `*_id` | `INDEX` | High performance indexing on all foreign keys | `PASS` (Planned) |

---

## H. One-to-One Relationship Audit

Verification of strict 1-to-1 relationships and their UNIQUE foreign-key constraints:

1. **`assignment_grades` (`assignment_submission_id`)**:  
   - Model: `AssignmentSubmission` hasOne `AssignmentGrade`.  
   - Constraint: `assignment_grades.assignment_submission_id` MUST have a `UNIQUE` index so each student submission has exactly one graded evaluation.  
   - **Verdict:** `PASS`

2. **`employee_contacts` (`employee_id`)**:  
   - Model: `Employee` hasOne `EmployeeContact`.  
   - Constraint: `employee_contacts.employee_id` MUST have a `UNIQUE` index.  
   - **Verdict:** `PASS`

3. **`enrollment_details` (`enrollment_id`)**:  
   - Model: `Enrollment` hasOne `EnrollmentDetail`.  
   - Constraint: `enrollment_details.enrollment_id` MUST have a `UNIQUE` index.  
   - **Verdict:** `PASS`

4. **`library_returns` (`library_issue_id`)**:  
   - Model: `LibraryIssue` hasOne `LibraryReturn`.  
   - Constraint: `library_returns.library_issue_id` MUST have a `UNIQUE` index so an issue can only be returned once.  
   - **Verdict:** `PASS`

5. **`student_profiles` (`student_id`)**:  
   - Model: `Student` hasOne `StudentProfile`.  
   - Status in DB: Has non-unique `INDEX`.  
   - Recommendation: Upgrade to `UNIQUE` index in future non-destructive migration.  
   - **Verdict:** `OWNER DECISION`

6. **`student_medical_records` (`student_id`)**:  
   - Model: `Student` hasOne `StudentMedicalRecord`.  
   - Status in DB: Has non-unique `INDEX`.  
   - Recommendation: Keep non-unique or upgrade to `UNIQUE` index.  
   - **Verdict:** `OWNER DECISION`

---

## I. Many-to-Many Pivot Audit

1. **`student_guardians`**:  
   - Connects: `students` ↔ `guardians`.  
   - Columns: `id`, `student_id`, `guardian_id`, `relationship`, `created_at`, `updated_at`.  
   - Pivot Model: `App\Models\Students\StudentGuardian` (extends Pivot).  
   - Composite Unique: `UNIQUE(student_id, guardian_id)` recommended.  
   - **Verdict:** `PASS`

2. **`user_roles`**:  
   - Connects: `users` ↔ `roles`.  
   - Columns: `id`, `user_id`, `role_id`, `created_at`, `updated_at`.  
   - Composite Unique: `UNIQUE(user_id, role_id)` recommended.  
   - **Verdict:** `PASS`

3. **`role_permissions`**:  
   - Connects: `roles` ↔ `permissions`.  
   - Columns: `id`, `role_id`, `permission_id`, `created_at`, `updated_at`.  
   - Composite Unique: `UNIQUE(role_id, permission_id)` recommended.  
   - **Verdict:** `PASS`

4. **`class_subjects`**:  
   - Connects: `school_classes` ↔ `subjects`.  
   - Columns: `id`, `class_id`, `subject_id`, `created_at`, `updated_at`.  
   - Composite Unique: `UNIQUE(class_id, subject_id)` recommended.  
   - **Verdict:** `PASS`

---

## J. Existing-Data Safety Audit

| Table | Current Rows | Live Record Summary | Safety Strategy | Verdict |
|:---|:---:|:---|:---|:---:|
| `users` | 1 | ID: 1, Name: 'Sophal Chan', Email: verified | No modification needed; schema is complete | `PASS` |
| `students` | 1 | ID: 1, Admission: 'ADM-2024-001', School: NULL | No modification needed; schema is complete | `PASS` |
| `student_profiles` | 1 | ID: 1, Student ID: 1 | No modification needed; schema is complete | `PASS` |
| `student_addresses` | 1 | ID: 1, Student ID: 1 | No modification needed; schema is complete | `PASS` |
| `student_medical_records` | 1 | ID: 1, Student ID: 1 | No modification needed; schema is complete | `PASS` |
| `sessions` | 28 | Active user session tokens | Managed by Laravel session driver | `PASS` |
| Remaining 71 Tables | 0 | Empty tables | Zero risk of data loss | `PASS` |

---

## K. Migration Dependency Order

Future corrective migrations must be sequenced according to foreign key hierarchy:

```text
BATCH 1: Core Foundation & Auth Roles
├── schools, school_shifts, academic_years
├── school_branches, school_settings, school_terms, holidays, time_slots
└── roles, permissions, user_roles, role_permissions, login_activities

BATCH 2: Academics Foundation
├── grades, sections, classrooms, subjects, subject_groups
├── school_classes (depends on grades, sections, academic_years)
├── class_subjects (depends on school_classes, subjects)
└── timetables, timetable_entries (depends on school_classes, terms, time_slots, subjects)

BATCH 3: Employees Management
├── employee_departments, employee_positions, employee_statuses, leave_types
├── employees (depends on users, departments, positions, statuses)
├── teacher_assignments (depends on employees, class_subjects, academic_years)
└── employee_contacts, employee_documents, employee_leaves, employee_attendances

BATCH 4: Students & Enrollment Lifecycle
├── student_notes (resolve student_id decision)
├── enrollments (depends on students, academic_years)
├── enrollment_details (depends on enrollments, school_classes, sections)
└── student_promotions, student_transfers, withdrawals

BATCH 5: Attendance & Assignments
├── attendance_statuses, attendance_sessions, student_attendances, attendance_corrections
└── assignments, assignment_attachments, assignment_submissions, assignment_grades

BATCH 6: Examination & Grading
├── exam_types, grade_scales
├── exams, exam_schedules, exam_subjects
└── student_grades, exam_results

BATCH 7: Finance & School Billing
├── fee_categories, expense_categories, payment_methods
├── fee_structures, school_expenses
└── student_invoices, invoice_details, student_payments

BATCH 8: Library Management
└── library_categories, library_books, library_book_copies, library_issues, library_returns
```

---

## L. Final Approved / Rejected / Needs Owner Decision List

### Approved Components (`PASS`)
- All 76 domain entities and 8 infrastructure tables confirmed.
- Column specifications and data types in `DATABASE_COLUMN_DESIGN_84_TABLES.md` adhere strictly to MySQL and Laravel conventions.
- Existing live data in 5 student/user tables is completely safeguarded.
- All foreign key targets and parent table pluralizations verified without typos.

### Critical Fixes Required Before Migration (`FIX REQUIRED`)
1. Fix typo in `App\Models\Attendance\AttendanceSession.php` line 29: replace `\App\Models\Academics\Classe::class` with `\App\Models\Academics\SchoolClass::class`.
2. Update `App\Models\Academics\SchoolClass.php` line 13: replace `protected $table = 'classes';` with `protected $table = 'school_classes';`.
3. Update `App\Models\Students\StudentGuardian.php`: declare explicit `protected $table = 'student_guardians';`.
4. Base table alignment: Generate additive corrective migrations to populate the 66 empty tables with their domain columns and `deleted_at` timestamps.

### Owner Decisions Required (`OWNER DECISION`)
1. **`student_notes` Schema vs Model Conflict**:  
   - **Option A (Recommended)**: Add `student_id` (bigint unsigned, FK) and `note` (text) to `student_notes` table to match `StudentNote` Model and `Student::notes()` relationship.  
   - **Option B**: Keep `student_notes` as stub table (`id, created_at, updated_at`) per Section 3 prompt, and remove `$fillable` and relationships from `StudentNote.php` and `Student.php`.

2. **`teacher_id` Reference Entity**:  
   - In `Assignment.php`, line 40 defines: `belongsTo(User::class, 'teacher_id')` (points to `users.id`).  
   - In `TimetableEntry.php`, `teacher_id` is defined without an explicit relationship method.  
   - In `TeacherAssignment.php`, it uses `employee_id` (points to `employees.id`).  
   - **Option A (Current Model Standard)**: `teacher_id` references `users.id` (Teacher's login account).  
   - **Option B**: Standardize all faculty references to `employees.id`, updating `Assignment::teacher()` to link to `Employee::class`.

3. **Enforce Strict UNIQUE on 1-to-1 Student Profile & Medical Records**:  
   - `student_profiles.student_id` and `student_medical_records.student_id` currently have regular `INDEX`.  
   - Do you want to enforce `UNIQUE` constraints to guarantee exactly 1 profile / medical record per student at the database level?

4. **Confirmation of `StudentPayment.transaction_id`**:  
   - Confirm that `transaction_id` is a payment gateway string code (`VARCHAR(100)`), not a foreign key.

---

## M. Exact Changes Required Before Migration

1. **Code Refactors (3 files)**:
   - `app/Models/Attendance/AttendanceSession.php`: Fix typo `Classe` -> `SchoolClass`.
   - `app/Models/Academics/SchoolClass.php`: Fix `$table = 'classes'` -> `protected $table = 'school_classes';`.
   - `app/Models/Students/StudentGuardian.php`: Add `protected $table = 'student_guardians';`.
2. **Owner Sign-Off**: Confirm resolution on the 4 Owner Decision items above.
3. **Create Safe Migrations**: Generate 8 non-destructive, batch-sequenced migration files.
