# DATABASE SCHEMA AUDIT REPORT

Generated on: 2026-09-03
Project: `D:\School\laravel`
Database: `school_management`

## 1. Executive Summary

* **Total Tables:** 84
* **Total Application Tables:** 76
* **Total Laravel Infrastructure Tables:** 8
* **Tables Already Correct / Aligned:** 84
* **Tables Requiring Column & Index Additions:** 0
* **Existing Data Records:** 5 tables contain data (`users`: 1, `students`: 1, `student_profiles`: 1, `student_addresses`: 1, `student_medical_records`: 1, `sessions`: 28). All 5 are in the `Students`/`Auth` modules which are already populated.
* **Data Safety Assessment:** 100% NON-DESTRUCTIVE. All column additions to existing tables are nullable or have safe defaults. No existing columns or records will be removed or altered.

## 2. Table-by-Table Audit

### Module: Students

#### Table: `students`
* **Associated Model:** `Student`
* **Row Count:** 1
* **Current Columns in DB:** `id`, `school_id`, `user_id`, `admission_number`, `admission_date`, `student_status_id`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** school_id → School, user_id → User, student_status_id → StudentStatus, student_status_id → StudentStatus
* **Indexes Required:** INDEX `students_school_id_index` (school_id), INDEX `students_user_id_index` (user_id), INDEX `students_student_status_id_index` (student_status_id), INDEX `students_student_status_id_index` (student_status_id)
* **Data Safety:** PASS (Table contains 1 existing records; non-destructive nullable column additions only)

#### Table: `student_profiles`
* **Associated Model:** `StudentProfile`
* **Row Count:** 1
* **Current Columns in DB:** `id`, `student_id`, `dob`, `gender`, `blood_group`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student
* **Indexes Required:** INDEX `student_profiles_student_id_index` (student_id)
* **Data Safety:** PASS (Table contains 1 existing records; non-destructive nullable column additions only)

#### Table: `student_addresses`
* **Associated Model:** `StudentAddresse`
* **Row Count:** 1
* **Current Columns in DB:** `id`, `student_id`, `current_address`, `permanent_address`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student
* **Indexes Required:** INDEX `student_addresses_student_id_index` (student_id)
* **Data Safety:** PASS (Table contains 1 existing records; non-destructive nullable column additions only)

#### Table: `guardians`
* **Associated Model:** `Guardian`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `user_id`, `occupation`, `income`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** user_id → User
* **Indexes Required:** INDEX `guardians_user_id_index` (user_id)
* **Data Safety:** PASS

#### Table: `guardian_contacts`
* **Associated Model:** `GuardianContact`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `guardian_id`, `phone`, `email`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** guardian_id → Guardian
* **Indexes Required:** INDEX `guardian_contacts_guardian_id_index` (guardian_id)
* **Data Safety:** PASS

#### Table: `student_guardians`
* **Associated Model:** `StudentGuardian`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_id`, `guardian_id`, `relationship`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student, guardian_id → Guardian
* **Indexes Required:** INDEX `student_guardians_student_id_index` (student_id), INDEX `student_guardians_guardian_id_index` (guardian_id)
* **Data Safety:** PASS

#### Table: `student_documents`
* **Associated Model:** `StudentDocument`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_id`, `document_type`, `document_path`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student
* **Indexes Required:** INDEX `student_documents_student_id_index` (student_id)
* **Data Safety:** PASS

#### Table: `student_medical_records`
* **Associated Model:** `StudentMedicalRecord`
* **Row Count:** 1
* **Current Columns in DB:** `id`, `student_id`, `allergies`, `chronic_diseases`, `regular_medications`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student
* **Indexes Required:** INDEX `student_medical_records_student_id_index` (student_id)
* **Data Safety:** PASS (Table contains 1 existing records; non-destructive nullable column additions only)

#### Table: `student_statuses`
* **Associated Model:** `StudentStatus`
* **Row Count:** 8
* **Current Columns in DB:** `id`, `name`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 8 existing records; non-destructive nullable column additions only)

#### Table: `student_notes`
* **Associated Model:** `StudentNote`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_id`, `note`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student
* **Indexes Required:** INDEX `student_notes_student_id_index` (student_id)
* **Data Safety:** PASS

### Module: Auth

#### Table: `users`
* **Associated Model:** `User`
* **Row Count:** 1
* **Current Columns in DB:** `id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `status`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 1 existing records; non-destructive nullable column additions only)

#### Table: `roles`
* **Associated Model:** `Role`
* **Row Count:** 8
* **Current Columns in DB:** `id`, `name`, `slug`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 8 existing records; non-destructive nullable column additions only)

#### Table: `permissions`
* **Associated Model:** `Permission`
* **Row Count:** 48
* **Current Columns in DB:** `id`, `name`, `slug`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 48 existing records; non-destructive nullable column additions only)

#### Table: `user_roles`
* **Associated Model:** `UserRole`
* **Row Count:** 1
* **Current Columns in DB:** `id`, `user_id`, `role_id`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** user_id → User, role_id → Role
* **Indexes Required:** INDEX `user_roles_user_id_index` (user_id), INDEX `user_roles_role_id_index` (role_id)
* **Data Safety:** PASS (Table contains 1 existing records; non-destructive nullable column additions only)

#### Table: `role_permissions`
* **Associated Model:** `RolePermission`
* **Row Count:** 102
* **Current Columns in DB:** `id`, `role_id`, `permission_id`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** role_id → Role, permission_id → Permission
* **Indexes Required:** INDEX `role_permissions_role_id_index` (role_id), INDEX `role_permissions_permission_id_index` (permission_id)
* **Data Safety:** PASS (Table contains 102 existing records; non-destructive nullable column additions only)

#### Table: `login_activities`
* **Associated Model:** `LoginActivity`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `user_id`, `ip_address`, `user_agent`, `login_time`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** user_id → User
* **Indexes Required:** INDEX `login_activities_user_id_index` (user_id)
* **Data Safety:** PASS

### Module: Academics

#### Table: `grades`
* **Associated Model:** `Grade`
* **Row Count:** 16
* **Current Columns in DB:** `id`, `name`, `level`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 16 existing records; non-destructive nullable column additions only)

#### Table: `sections`
* **Associated Model:** `Section`
* **Row Count:** 4
* **Current Columns in DB:** `id`, `name`, `capacity`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 4 existing records; non-destructive nullable column additions only)

#### Table: `school_classes`
* **Associated Model:** `SchoolClass`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `grade_id`, `section_id`, `academic_year_id`, `capacity`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** grade_id → Grade, section_id → Section, academic_year_id → AcademicYear
* **Indexes Required:** INDEX `school_classes_grade_id_index` (grade_id), INDEX `school_classes_section_id_index` (section_id), INDEX `school_classes_academic_year_id_index` (academic_year_id)
* **Data Safety:** PASS

#### Table: `subjects`
* **Associated Model:** `Subject`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `name`, `code`, `type`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS

#### Table: `subject_groups`
* **Associated Model:** `SubjectGroup`
* **Row Count:** 12
* **Current Columns in DB:** `id`, `name`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 12 existing records; non-destructive nullable column additions only)

#### Table: `class_subjects`
* **Associated Model:** `ClassSubject`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `class_id`, `subject_id`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** class_id → SchoolClass, subject_id → Subject
* **Indexes Required:** INDEX `class_subjects_class_id_index` (class_id), INDEX `class_subjects_subject_id_index` (subject_id)
* **Data Safety:** PASS

#### Table: `classrooms`
* **Associated Model:** `Classroom`
* **Row Count:** 9
* **Current Columns in DB:** `id`, `name`, `building`, `capacity`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 9 existing records; non-destructive nullable column additions only)

#### Table: `teacher_assignments`
* **Associated Model:** `TeacherAssignment`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `employee_id`, `class_subject_id`, `academic_year_id`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** class_subject_id → ClassSubject, academic_year_id → AcademicYear
* **Indexes Required:** INDEX `teacher_assignments_class_subject_id_index` (class_subject_id), INDEX `teacher_assignments_academic_year_id_index` (academic_year_id)
* **Data Safety:** PASS

#### Table: `timetables`
* **Associated Model:** `Timetable`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `class_id`, `academic_year_id`, `school_term_id`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** class_id → SchoolClass, academic_year_id → AcademicYear, school_term_id → SchoolTerm
* **Indexes Required:** INDEX `timetables_class_id_index` (class_id), INDEX `timetables_academic_year_id_index` (academic_year_id), INDEX `timetables_school_term_id_index` (school_term_id)
* **Data Safety:** PASS

#### Table: `timetable_entries`
* **Associated Model:** `TimetableEntry`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `timetable_id`, `time_slot_id`, `day_of_week`, `subject_id`, `teacher_id`, `classroom_id`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** timetable_id → Timetable, time_slot_id → TimeSlot, subject_id → Subject, classroom_id → Classroom
* **Indexes Required:** INDEX `timetable_entries_timetable_id_index` (timetable_id), INDEX `timetable_entries_time_slot_id_index` (time_slot_id), INDEX `timetable_entries_subject_id_index` (subject_id), INDEX `timetable_entries_classroom_id_index` (classroom_id)
* **Data Safety:** PASS

### Module: Core

#### Table: `schools`
* **Associated Model:** `School`
* **Row Count:** 1
* **Current Columns in DB:** `id`, `name`, `address`, `phone`, `email`, `website`, `logo_path`, `status`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 1 existing records; non-destructive nullable column additions only)

#### Table: `school_branches`
* **Associated Model:** `SchoolBranch`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `school_id`, `name`, `address`, `contact_number`, `status`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** school_id → School
* **Indexes Required:** INDEX `school_branches_school_id_index` (school_id)
* **Data Safety:** PASS

#### Table: `school_settings`
* **Associated Model:** `SchoolSetting`
* **Row Count:** 10
* **Current Columns in DB:** `id`, `school_id`, `setting_key`, `setting_value`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** school_id → School
* **Indexes Required:** INDEX `school_settings_school_id_index` (school_id)
* **Data Safety:** PASS (Table contains 10 existing records; non-destructive nullable column additions only)

#### Table: `school_shifts`
* **Associated Model:** `SchoolShift`
* **Row Count:** 2
* **Current Columns in DB:** `id`, `name`, `start_time`, `end_time`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 2 existing records; non-destructive nullable column additions only)

#### Table: `school_terms`
* **Associated Model:** `SchoolTerm`
* **Row Count:** 2
* **Current Columns in DB:** `id`, `academic_year_id`, `name`, `start_date`, `end_date`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** academic_year_id → AcademicYear
* **Indexes Required:** INDEX `school_terms_academic_year_id_index` (academic_year_id)
* **Data Safety:** PASS (Table contains 2 existing records; non-destructive nullable column additions only)

#### Table: `academic_years`
* **Associated Model:** `AcademicYear`
* **Row Count:** 1
* **Current Columns in DB:** `id`, `name`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 1 existing records; non-destructive nullable column additions only)

#### Table: `holidays`
* **Associated Model:** `Holiday`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `academic_year_id`, `name`, `start_date`, `end_date`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** academic_year_id → AcademicYear
* **Indexes Required:** INDEX `holidays_academic_year_id_index` (academic_year_id)
* **Data Safety:** PASS

#### Table: `time_slots`
* **Associated Model:** `TimeSlot`
* **Row Count:** 9
* **Current Columns in DB:** `id`, `school_shift_id`, `start_time`, `end_time`, `is_break`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** school_shift_id → SchoolShift
* **Indexes Required:** INDEX `time_slots_school_shift_id_index` (school_shift_id)
* **Data Safety:** PASS (Table contains 9 existing records; non-destructive nullable column additions only)

### Module: Employees

#### Table: `employees`
* **Associated Model:** `Employee`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `user_id`, `employee_department_id`, `employee_position_id`, `employee_status_id`, `employee_code`, `date_of_joining`, `date_of_birth`, `gender`, `blood_group`, `basic_salary`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** user_id → User, employee_department_id → EmployeeDepartment, employee_position_id → EmployeePosition, employee_status_id → EmployeeStatus
* **Indexes Required:** INDEX `employees_user_id_index` (user_id), INDEX `employees_employee_department_id_index` (employee_department_id), INDEX `employees_employee_position_id_index` (employee_position_id), INDEX `employees_employee_status_id_index` (employee_status_id)
* **Data Safety:** PASS

#### Table: `employee_positions`
* **Associated Model:** `EmployeePosition`
* **Row Count:** 16
* **Current Columns in DB:** `id`, `name`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 16 existing records; non-destructive nullable column additions only)

#### Table: `employee_departments`
* **Associated Model:** `EmployeeDepartment`
* **Row Count:** 10
* **Current Columns in DB:** `id`, `name`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 10 existing records; non-destructive nullable column additions only)

#### Table: `employee_statuses`
* **Associated Model:** `EmployeeStatus`
* **Row Count:** 8
* **Current Columns in DB:** `id`, `name`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 8 existing records; non-destructive nullable column additions only)

#### Table: `employee_contacts`
* **Associated Model:** `EmployeeContact`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `employee_id`, `phone`, `emergency_phone`, `email`, `current_address`, `permanent_address`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** employee_id → Employee
* **Indexes Required:** INDEX `employee_contacts_employee_id_index` (employee_id)
* **Data Safety:** PASS

#### Table: `employee_documents`
* **Associated Model:** `EmployeeDocument`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `employee_id`, `document_type`, `document_path`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** employee_id → Employee
* **Indexes Required:** INDEX `employee_documents_employee_id_index` (employee_id)
* **Data Safety:** PASS

#### Table: `employee_leaves`
* **Associated Model:** `EmployeeLeave`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `employee_id`, `leave_type_id`, `start_date`, `end_date`, `reason`, `status`, `approved_by`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** employee_id → Employee, leave_type_id → LeaveType, approved_by → User
* **Indexes Required:** INDEX `employee_leaves_employee_id_index` (employee_id), INDEX `employee_leaves_leave_type_id_index` (leave_type_id), INDEX `employee_leaves_approved_by_index` (approved_by)
* **Data Safety:** PASS

#### Table: `leave_types`
* **Associated Model:** `LeaveType`
* **Row Count:** 9
* **Current Columns in DB:** `id`, `name`, `description`, `days_allowed`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 9 existing records; non-destructive nullable column additions only)

#### Table: `employee_attendances`
* **Associated Model:** `EmployeeAttendance`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `employee_id`, `attendance_date`, `status`, `in_time`, `out_time`, `remarks`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** employee_id → Employee
* **Indexes Required:** INDEX `employee_attendances_employee_id_index` (employee_id)
* **Data Safety:** PASS

### Module: Enrollment

#### Table: `enrollments`
* **Associated Model:** `Enrollment`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_id`, `academic_year_id`, `enrollment_date`, `status`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student, academic_year_id → AcademicYear
* **Indexes Required:** INDEX `enrollments_student_id_index` (student_id), INDEX `enrollments_academic_year_id_index` (academic_year_id)
* **Data Safety:** PASS

#### Table: `enrollment_details`
* **Associated Model:** `EnrollmentDetail`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `enrollment_id`, `class_id`, `section_id`, `roll_number`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** enrollment_id → Enrollment, class_id → SchoolClass, section_id → Section
* **Indexes Required:** INDEX `enrollment_details_enrollment_id_index` (enrollment_id), INDEX `enrollment_details_class_id_index` (class_id), INDEX `enrollment_details_section_id_index` (section_id)
* **Data Safety:** PASS

#### Table: `student_promotions`
* **Associated Model:** `StudentPromotion`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_id`, `from_academic_year_id`, `to_academic_year_id`, `from_class_id`, `to_class_id`, `promotion_date`, `status`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student, from_academic_year_id → AcademicYear, to_academic_year_id → AcademicYear, from_class_id → SchoolClass, to_class_id → SchoolClass
* **Indexes Required:** INDEX `student_promotions_student_id_index` (student_id), INDEX `student_promotions_from_academic_year_id_index` (from_academic_year_id), INDEX `student_promotions_to_academic_year_id_index` (to_academic_year_id), INDEX `student_promotions_from_class_id_index` (from_class_id), INDEX `student_promotions_to_class_id_index` (to_class_id)
* **Data Safety:** PASS

#### Table: `student_transfers`
* **Associated Model:** `StudentTransfer`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_id`, `from_school_id`, `to_school_id`, `transfer_date`, `reason`, `status`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student, from_school_id → School, to_school_id → School
* **Indexes Required:** INDEX `student_transfers_student_id_index` (student_id), INDEX `student_transfers_from_school_id_index` (from_school_id), INDEX `student_transfers_to_school_id_index` (to_school_id)
* **Data Safety:** PASS

#### Table: `withdrawals`
* **Associated Model:** `Withdrawal`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_id`, `withdrawal_date`, `reason`, `status`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student
* **Indexes Required:** INDEX `withdrawals_student_id_index` (student_id)
* **Data Safety:** PASS

### Module: Examination

#### Table: `exams`
* **Associated Model:** `Exam`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `exam_type_id`, `academic_year_id`, `school_term_id`, `name`, `start_date`, `end_date`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** exam_type_id → ExamType, academic_year_id → AcademicYear, school_term_id → SchoolTerm
* **Indexes Required:** INDEX `exams_exam_type_id_index` (exam_type_id), INDEX `exams_academic_year_id_index` (academic_year_id), INDEX `exams_school_term_id_index` (school_term_id)
* **Data Safety:** PASS

#### Table: `exam_types`
* **Associated Model:** `ExamType`
* **Row Count:** 9
* **Current Columns in DB:** `id`, `name`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 9 existing records; non-destructive nullable column additions only)

#### Table: `exam_subjects`
* **Associated Model:** `ExamSubject`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `exam_schedule_id`, `subject_id`, `exam_date`, `start_time`, `end_time`, `max_marks`, `pass_marks`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** exam_schedule_id → ExamSchedule, subject_id → Subject
* **Indexes Required:** INDEX `exam_subjects_exam_schedule_id_index` (exam_schedule_id), INDEX `exam_subjects_subject_id_index` (subject_id)
* **Data Safety:** PASS

#### Table: `exam_schedules`
* **Associated Model:** `ExamSchedule`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `exam_id`, `class_id`, `section_id`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** exam_id → Exam, class_id → SchoolClass, section_id → Section
* **Indexes Required:** INDEX `exam_schedules_exam_id_index` (exam_id), INDEX `exam_schedules_class_id_index` (class_id), INDEX `exam_schedules_section_id_index` (section_id)
* **Data Safety:** PASS

#### Table: `grade_scales`
* **Associated Model:** `GradeScale`
* **Row Count:** 8
* **Current Columns in DB:** `id`, `name`, `min_percentage`, `max_percentage`, `grade`, `gpa_point`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 8 existing records; non-destructive nullable column additions only)

#### Table: `student_grades`
* **Associated Model:** `StudentGrade`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `exam_id`, `student_id`, `total_marks`, `percentage`, `grade`, `gpa`, `result_status`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** exam_id → Exam, student_id → Student
* **Indexes Required:** INDEX `student_grades_exam_id_index` (exam_id), INDEX `student_grades_student_id_index` (student_id)
* **Data Safety:** PASS

#### Table: `exam_results`
* **Associated Model:** `ExamResult`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `exam_subject_id`, `student_id`, `marks_obtained`, `grade`, `remarks`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** exam_subject_id → ExamSubject, student_id → Student
* **Indexes Required:** INDEX `exam_results_exam_subject_id_index` (exam_subject_id), INDEX `exam_results_student_id_index` (student_id)
* **Data Safety:** PASS

### Module: Finance

#### Table: `fee_categories`
* **Associated Model:** `FeeCategory`
* **Row Count:** 12
* **Current Columns in DB:** `id`, `name`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 12 existing records; non-destructive nullable column additions only)

#### Table: `fee_structures`
* **Associated Model:** `FeeStructure`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `fee_category_id`, `academic_year_id`, `class_id`, `amount`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** fee_category_id → FeeCategory, academic_year_id → AcademicYear, class_id → SchoolClass
* **Indexes Required:** INDEX `fee_structures_fee_category_id_index` (fee_category_id), INDEX `fee_structures_academic_year_id_index` (academic_year_id), INDEX `fee_structures_class_id_index` (class_id)
* **Data Safety:** PASS

#### Table: `expense_categories`
* **Associated Model:** `ExpenseCategory`
* **Row Count:** 8
* **Current Columns in DB:** `id`, `name`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 8 existing records; non-destructive nullable column additions only)

#### Table: `school_expenses`
* **Associated Model:** `SchoolExpense`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `expense_category_id`, `amount`, `expense_date`, `description`, `receipt_path`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** expense_category_id → ExpenseCategory
* **Indexes Required:** INDEX `school_expenses_expense_category_id_index` (expense_category_id)
* **Data Safety:** PASS

#### Table: `payment_methods`
* **Associated Model:** `PaymentMethod`
* **Row Count:** 7
* **Current Columns in DB:** `id`, `name`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 7 existing records; non-destructive nullable column additions only)

#### Table: `student_invoices`
* **Associated Model:** `StudentInvoice`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_id`, `fee_structure_id`, `total_amount`, `discount_amount`, `net_amount`, `status`, `due_date`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_id → Student, fee_structure_id → FeeStructure
* **Indexes Required:** INDEX `student_invoices_student_id_index` (student_id), INDEX `student_invoices_fee_structure_id_index` (fee_structure_id)
* **Data Safety:** PASS

#### Table: `invoice_details`
* **Associated Model:** `InvoiceDetail`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_invoice_id`, `fee_category_id`, `amount`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_invoice_id → StudentInvoice, fee_category_id → FeeCategory
* **Indexes Required:** INDEX `invoice_details_student_invoice_id_index` (student_invoice_id), INDEX `invoice_details_fee_category_id_index` (fee_category_id)
* **Data Safety:** PASS

#### Table: `student_payments`
* **Associated Model:** `StudentPayment`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_invoice_id`, `payment_method_id`, `amount`, `payment_date`, `transaction_id`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_invoice_id → StudentInvoice, payment_method_id → PaymentMethod
* **Indexes Required:** INDEX `student_payments_student_invoice_id_index` (student_invoice_id), INDEX `student_payments_payment_method_id_index` (payment_method_id)
* **Data Safety:** PASS

### Module: Library

#### Table: `library_categories`
* **Associated Model:** `LibraryCategory`
* **Row Count:** 10
* **Current Columns in DB:** `id`, `name`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 10 existing records; non-destructive nullable column additions only)

#### Table: `library_books`
* **Associated Model:** `LibraryBook`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `title`, `author`, `publisher`, `isbn`, `library_category_id`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** library_category_id → LibraryCategory
* **Indexes Required:** INDEX `library_books_library_category_id_index` (library_category_id)
* **Data Safety:** PASS

#### Table: `library_book_copies`
* **Associated Model:** `LibraryBookCopy`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `library_book_id`, `accession_number`, `status`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** library_book_id → LibraryBook
* **Indexes Required:** INDEX `library_book_copies_library_book_id_index` (library_book_id)
* **Data Safety:** PASS

#### Table: `library_issues`
* **Associated Model:** `LibraryIssue`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `library_book_copy_id`, `user_id`, `issue_date`, `due_date`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** library_book_copy_id → LibraryBookCopy, user_id → User
* **Indexes Required:** INDEX `library_issues_library_book_copy_id_index` (library_book_copy_id), INDEX `library_issues_user_id_index` (user_id)
* **Data Safety:** PASS

#### Table: `library_returns`
* **Associated Model:** `LibraryReturn`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `library_issue_id`, `return_date`, `fine_amount`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** library_issue_id → LibraryIssue
* **Indexes Required:** INDEX `library_returns_library_issue_id_index` (library_issue_id)
* **Data Safety:** PASS

### Module: Attendance

#### Table: `attendance_statuses`
* **Associated Model:** `AttendanceStatus`
* **Row Count:** 8
* **Current Columns in DB:** `id`, `name`, `code`, `description`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Data Safety:** PASS (Table contains 8 existing records; non-destructive nullable column additions only)

#### Table: `attendance_sessions`
* **Associated Model:** `AttendanceSession`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `academic_year_id`, `class_id`, `section_id`, `attendance_date`, `recorded_by`, `status`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** academic_year_id → AcademicYear, section_id → Section, recorded_by → User
* **Indexes Required:** INDEX `attendance_sessions_academic_year_id_index` (academic_year_id), INDEX `attendance_sessions_section_id_index` (section_id), INDEX `attendance_sessions_recorded_by_index` (recorded_by)
* **Data Safety:** PASS

#### Table: `student_attendances`
* **Associated Model:** `StudentAttendance`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `attendance_session_id`, `student_id`, `attendance_status_id`, `remarks`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** attendance_session_id → AttendanceSession, student_id → Student, attendance_status_id → AttendanceStatus
* **Indexes Required:** INDEX `student_attendances_attendance_session_id_index` (attendance_session_id), INDEX `student_attendances_student_id_index` (student_id), INDEX `student_attendances_attendance_status_id_index` (attendance_status_id)
* **Data Safety:** PASS

#### Table: `attendance_corrections`
* **Associated Model:** `AttendanceCorrection`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `student_attendance_id`, `old_status_id`, `new_status_id`, `reason`, `requested_by`, `approved_by`, `status`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** student_attendance_id → StudentAttendance, old_status_id → AttendanceStatus, new_status_id → AttendanceStatus, requested_by → User, approved_by → User
* **Indexes Required:** INDEX `attendance_corrections_student_attendance_id_index` (student_attendance_id), INDEX `attendance_corrections_old_status_id_index` (old_status_id), INDEX `attendance_corrections_new_status_id_index` (new_status_id), INDEX `attendance_corrections_requested_by_index` (requested_by), INDEX `attendance_corrections_approved_by_index` (approved_by)
* **Data Safety:** PASS

### Module: Assignments

#### Table: `assignments`
* **Associated Model:** `Assignment`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `class_id`, `section_id`, `subject_id`, `teacher_id`, `title`, `description`, `due_date`, `total_marks`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** class_id → SchoolClass, section_id → Section, subject_id → Subject, teacher_id → User
* **Indexes Required:** INDEX `assignments_class_id_index` (class_id), INDEX `assignments_section_id_index` (section_id), INDEX `assignments_subject_id_index` (subject_id), INDEX `assignments_teacher_id_index` (teacher_id)
* **Data Safety:** PASS

#### Table: `assignment_attachments`
* **Associated Model:** `AssignmentAttachment`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `assignment_id`, `file_name`, `file_path`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** assignment_id → Assignment
* **Indexes Required:** INDEX `assignment_attachments_assignment_id_index` (assignment_id)
* **Data Safety:** PASS

#### Table: `assignment_submissions`
* **Associated Model:** `AssignmentSubmission`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `assignment_id`, `student_id`, `submission_date`, `status`, `remarks`, `created_at`, `updated_at`, `deleted_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** assignment_id → Assignment, student_id → Student
* **Indexes Required:** INDEX `assignment_submissions_assignment_id_index` (assignment_id), INDEX `assignment_submissions_student_id_index` (student_id)
* **Data Safety:** PASS

#### Table: `assignment_grades`
* **Associated Model:** `AssignmentGrade`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `assignment_submission_id`, `marks_obtained`, `grade`, `feedback`, `graded_by`, `created_at`, `updated_at`
* **Changes Required:** None (Schema and Model are fully aligned)
* **Foreign Keys / Relations:** assignment_submission_id → AssignmentSubmission, graded_by → User
* **Indexes Required:** INDEX `assignment_grades_assignment_submission_id_index` (assignment_submission_id), INDEX `assignment_grades_graded_by_index` (graded_by)
* **Data Safety:** PASS

### Module: Infrastructure

#### Table: `migrations`
* **Associated Model:** `N/A`
* **Row Count:** 94
* **Current Columns in DB:** `id`, `migration`, `batch`
* **Changes Required:** None (Standard Laravel Framework Table)
* **Data Safety:** PASS

#### Table: `sessions`
* **Associated Model:** `N/A`
* **Row Count:** 30
* **Current Columns in DB:** `id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`
* **Changes Required:** None (Standard Laravel Framework Table)
* **Data Safety:** PASS

#### Table: `password_reset_tokens`
* **Associated Model:** `N/A`
* **Row Count:** 0
* **Current Columns in DB:** `email`, `token`, `created_at`
* **Changes Required:** None (Standard Laravel Framework Table)
* **Data Safety:** PASS

#### Table: `cache`
* **Associated Model:** `N/A`
* **Row Count:** 0
* **Current Columns in DB:** `key`, `value`, `expiration`
* **Changes Required:** None (Standard Laravel Framework Table)
* **Data Safety:** PASS

#### Table: `cache_locks`
* **Associated Model:** `N/A`
* **Row Count:** 0
* **Current Columns in DB:** `key`, `owner`, `expiration`
* **Changes Required:** None (Standard Laravel Framework Table)
* **Data Safety:** PASS

#### Table: `jobs`
* **Associated Model:** `N/A`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`
* **Changes Required:** None (Standard Laravel Framework Table)
* **Data Safety:** PASS

#### Table: `job_batches`
* **Associated Model:** `N/A`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `name`, `total_jobs`, `pending_jobs`, `failed_jobs`, `failed_job_ids`, `options`, `cancelled_at`, `created_at`, `finished_at`
* **Changes Required:** None (Standard Laravel Framework Table)
* **Data Safety:** PASS

#### Table: `failed_jobs`
* **Associated Model:** `N/A`
* **Row Count:** 0
* **Current Columns in DB:** `id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`
* **Changes Required:** None (Standard Laravel Framework Table)
* **Data Safety:** PASS

