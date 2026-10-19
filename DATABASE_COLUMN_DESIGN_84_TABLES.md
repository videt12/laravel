# DATABASE COLUMN DESIGN & AUDIT SPECIFICATION (84 TABLES)

**Project:** `D:\School\laravel`  
**Database:** `school_management` (MySQL 8.x)  
**Generated Date:** 2026-09-03  
**Phase:** DESIGN + AUDIT ONLY (Database Modifications: **NONE**)  

---

## A. Executive Summary

```text
Total Tables: 84
Application Tables: 76
Infrastructure Tables: 8

Current Schema Status:
- Fully Populated Tables in DB: 18 tables (9 Students + 1 Auth User + 8 Infrastructure)
- Base/Stub Tables in DB: 66 tables (created with only id, created_at, updated_at)
- Tables with Active Live Data: 5 tables (users: 1 row, students: 1 row, student_profiles: 1 row, student_addresses: 1 row, student_medical_records: 1 row, sessions: 28 rows)
- Total Missing Columns Across 66 Base Tables: 267 columns
- Data Safety: 100% NON-DESTRUCTIVE DESIGN. All recommended additions are strictly additive and nullable.

Major Conflicts Identified:
1. StudentNote Model expects `student_id` & `note`, whereas Section 3 specification defines only `id, created_at, updated_at`.
2. SchoolClass Model defined $table = 'classes', whereas database table is `school_classes`.
3. StudentGuardian Pivot Model did not declare explicit $table = 'student_guardians'.
4. 66 application tables lack their domain attributes in the MySQL database, causing 1054 Unknown Column errors when relationships are eager-loaded.
```

---

## B. Complete Table-by-Table Specification

### Students Module

#### 1. TABLE: `students`

**Purpose:** Primary record linking an enrolled student to an institution user account, school, admission code, and status.  
**Module:** Students  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `school_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | schools.id | Associated school ID |
| `user_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | users.id | Associated user login account ID |
| `admission_number` | `VARCHAR(255)` | YES | `NULL` | UNI | - | Unique institutional student admission number |
| `admission_date` | `DATE` | YES | `NULL` | - | - | Official date of admission |
| `student_status_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | student_statuses.id | Student lifecycle status reference |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `UNIQUE `students_admission_number_unique` (`admission_number`)`
- `INDEX `students_school_id_index` (`school_id`)`
- `INDEX `students_user_id_index` (`user_id`)`
- `INDEX `students_student_status_id_index` (`student_status_id`)`

##### Relationships:
- belongsTo Core\School (school_id -> schools.id)
- belongsTo Auth\User (user_id -> users.id)
- belongsTo Students\StudentStatus (student_status_id -> student_statuses.id)
- hasOne Students\StudentProfile (student_id)
- hasOne Students\StudentAddresse (student_id)
- hasMany Students\StudentDocument (student_id)
- hasOne Students\StudentMedicalRecord (student_id)
- hasMany Students\StudentNote (student_id)
- hasMany Enrollment\Enrollment (student_id)
- belongsToMany Students\Guardian via student_guardians

---

#### 2. TABLE: `student_profiles`

**Purpose:** Stores personal, demographic, and biological profile data for a student.  
**Module:** Students  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Parent student ID |
| `dob` | `DATE` | YES | `NULL` | - | - | Date of birth |
| `gender` | `VARCHAR(20)` | YES | `NULL` | - | - | Gender (Male, Female, Other) |
| `blood_group` | `VARCHAR(10)` | YES | `NULL` | - | - | Blood group (A+, B+, O+, etc.) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_profiles_student_id_index` (`student_id`)`

##### Relationships:
- belongsTo Students\Student (student_id -> students.id)

---

#### 3. TABLE: `student_addresses`

**Purpose:** Stores residential address details (current and permanent/hometown) for a student.  
**Module:** Students  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Parent student ID |
| `current_address` | `TEXT` | YES | `NULL` | - | - | Current living/boarding address |
| `permanent_address` | `TEXT` | YES | `NULL` | - | - | Permanent family/hometown address |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_addresses_student_id_index` (`student_id`)`

##### Relationships:
- belongsTo Students\Student (student_id -> students.id)

---

#### 4. TABLE: `guardians`

**Purpose:** Parent and guardian master records linked to user credentials with professional & economic details.  
**Module:** Students  
**Schema Status:** `READY`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `user_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | users.id | Associated user login account ID |
| `occupation` | `VARCHAR(255)` | YES | `NULL` | - | - | Profession or job title |
| `income` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Annual or monthly income |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `guardians_user_id_index` (`user_id`)`

##### Relationships:
- belongsTo Auth\User (user_id -> users.id)
- hasMany Students\GuardianContact (guardian_id)
- hasMany Students\StudentGuardian (guardian_id)
- belongsToMany Students\Student via student_guardians

---

#### 5. TABLE: `guardian_contacts`

**Purpose:** Phone numbers and email addresses for guardians for emergency and official correspondence.  
**Module:** Students  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `guardian_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | guardians.id | Parent guardian ID |
| `phone` | `VARCHAR(30)` | YES | `NULL` | - | - | Contact telephone number |
| `email` | `VARCHAR(255)` | YES | `NULL` | - | - | Contact email address |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `guardian_contacts_guardian_id_index` (`guardian_id`)`

##### Relationships:
- belongsTo Students\Guardian (guardian_id -> guardians.id)

---

#### 6. TABLE: `student_guardians`

**Purpose:** Many-to-many pivot table binding students to their guardians with relationship type specification.  
**Module:** Students  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Target student ID |
| `guardian_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | guardians.id | Target guardian ID |
| `relationship` | `VARCHAR(50)` | YES | `NULL` | - | - | Relationship description (e.g. Father, Mother, Guardian) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_guardians_student_id_index` (`student_id`)`
- `INDEX `student_guardians_guardian_id_index` (`guardian_id`)`
- `UNIQUE `student_guardians_student_guardian_unique` (`student_id`, `guardian_id`)`

##### Relationships:
- belongsTo Students\Student (student_id -> students.id)
- belongsTo Students\Guardian (guardian_id -> guardians.id)

---

#### 7. TABLE: `student_documents`

**Purpose:** Official uploaded records, birth certificates, national IDs, and prior school transcripts.  
**Module:** Students  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Parent student ID |
| `document_type` | `VARCHAR(255)` | YES | `NULL` | - | - | Type of document (e.g. Birth Certificate, Transcript) |
| `document_path` | `VARCHAR(255)` | YES | `NULL` | - | - | Storage path to file |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_documents_student_id_index` (`student_id`)`

##### Relationships:
- belongsTo Students\Student (student_id -> students.id)

---

#### 8. TABLE: `student_medical_records`

**Purpose:** Confidential health history, chronic medical conditions, allergies, and recurring prescriptions.  
**Module:** Students  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Parent student ID |
| `allergies` | `TEXT` | YES | `NULL` | - | - | Known food, drug or environmental allergies |
| `chronic_diseases` | `TEXT` | YES | `NULL` | - | - | Chronic ailments (e.g. Asthma, Diabetes) |
| `regular_medications` | `TEXT` | YES | `NULL` | - | - | Daily or emergency prescriptions |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_medical_records_student_id_index` (`student_id`)`

##### Relationships:
- belongsTo Students\Student (student_id -> students.id)

---

#### 9. TABLE: `student_statuses`

**Purpose:** Lookup table for student enrollment lifecycle states (e.g., Active, Suspended, Graduated, Transferred).  
**Module:** Students  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `name` | `VARCHAR(255)` | NO | `NULL` | - | - | Status title (e.g. Active, Inactive, Suspended) |
| `description` | `VARCHAR(255)` | YES | `NULL` | - | - | Detailed explanation of this status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- hasMany Students\Student (student_status_id)

---

#### 10. TABLE: `student_notes`

**Purpose:** Staff and counselor notes recorded regarding student academic progress or conduct.  
**Module:** Students  
**Schema Status:** `CONFLICT`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Target student ID (Needed by StudentNote Model) |
| `note` | `TEXT` | YES | `NULL` | - | - | Note content (Needed by StudentNote Model) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_notes_student_id_index` (`student_id`)`

##### Relationships:
- belongsTo Students\Student (student_id -> students.id)

---

### Auth Module

#### 11. TABLE: `users`

**Purpose:** Primary authentication identity for administrators, teachers, students, and guardians.  
**Module:** Auth  
**Schema Status:** `READY`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `name` | `VARCHAR(255)` | NO | `NULL` | - | - | User display name |
| `email` | `VARCHAR(255)` | NO | `NULL` | UNI | - | Unique email address for login |
| `email_verified_at` | `TIMESTAMP` | YES | `NULL` | - | - | Timestamp when email was confirmed |
| `password` | `VARCHAR(255)` | NO | `NULL` | - | - | Bcrypt hashed password string |
| `remember_token` | `VARCHAR(100)` | YES | `NULL` | - | - | Remember-me session token |
| `status` | `VARCHAR(30)` | NO | `active` | - | - | Account status (active, inactive, suspended) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `UNIQUE `users_email_unique` (`email`)`

##### Relationships:
- hasMany Auth\UserRole (user_id)
- hasMany Auth\LoginActivity (user_id)
- hasOne Students\Student (user_id)
- hasOne Students\Guardian (user_id)
- hasOne Employees\Employee (user_id)

---

#### 12. TABLE: `roles`

**Purpose:** Security user roles (e.g. Super Admin, Principal, Teacher, Student, Parent).  
**Module:** Auth  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `name` | `VARCHAR(100)` | NO | `NULL` | - | - | Human-readable role title |
| `slug` | `VARCHAR(100)` | NO | `NULL` | UNI | - | Machine-readable slug (e.g. super-admin) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `UNIQUE `roles_slug_unique` (`slug`)`

##### Relationships:
- hasMany Auth\UserRole (role_id)
- hasMany Auth\RolePermission (role_id)

---

#### 13. TABLE: `permissions`

**Purpose:** Fine-grained capability permission definitions for RBAC authorization checks.  
**Module:** Auth  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `name` | `VARCHAR(100)` | NO | `NULL` | - | - | Human-readable permission label |
| `slug` | `VARCHAR(100)` | NO | `NULL` | UNI | - | Unique permission key (e.g. students.create) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `UNIQUE `permissions_slug_unique` (`slug`)`

##### Relationships:
- hasMany Auth\RolePermission (permission_id)

---

#### 14. TABLE: `user_roles`

**Purpose:** Mapping table linking users to one or more assigned security roles.  
**Module:** Auth  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `user_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | users.id | Target user ID |
| `role_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | roles.id | Target role ID |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `user_roles_user_id_index` (`user_id`)`
- `INDEX `user_roles_role_id_index` (`role_id`)`
- `UNIQUE `user_roles_user_role_unique` (`user_id`, `role_id`)`

##### Relationships:
- belongsTo Auth\User (user_id -> users.id)
- belongsTo Auth\Role (role_id -> roles.id)

---

#### 15. TABLE: `role_permissions`

**Purpose:** Mapping table granting permissions to roles.  
**Module:** Auth  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `role_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | roles.id | Target role ID |
| `permission_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | permissions.id | Target permission ID |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `role_permissions_role_id_index` (`role_id`)`
- `INDEX `role_permissions_permission_id_index` (`permission_id`)`
- `UNIQUE `role_permissions_role_permission_unique` (`role_id`, `permission_id`)`

##### Relationships:
- belongsTo Auth\Role (role_id -> roles.id)
- belongsTo Auth\Permission (permission_id -> permissions.id)

---

#### 16. TABLE: `login_activities`

**Purpose:** Audit log tracking user logins, client IP addresses, User-Agents and timestamps.  
**Module:** Auth  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `user_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | users.id | Authenticated user ID |
| `ip_address` | `VARCHAR(45)` | YES | `NULL` | - | - | Client IPv4 / IPv6 address |
| `user_agent` | `TEXT` | YES | `NULL` | - | - | Browser and device user agent string |
| `login_time` | `TIMESTAMP` | YES | `NULL` | - | - | Login event timestamp |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `login_activities_user_id_index` (`user_id`)`

##### Relationships:
- belongsTo Auth\User (user_id -> users.id)

---

### Academics Module

#### 17. TABLE: `grades`

**Purpose:** Academic grade or year levels in the school (e.g. Grade 1, Grade 10).  
**Module:** Academics  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `name` | `VARCHAR(100)` | NO | `NULL` | - | - | Grade level label (e.g. Grade 1) |
| `level` | `INT` | YES | `NULL` | - | - | Numerical grade sorting order |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- hasMany Academics\SchoolClass (grade_id)

---

#### 18. TABLE: `sections`

**Purpose:** Class section or stream division (e.g. Section A, Section B).  
**Module:** Academics  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `name` | `VARCHAR(100)` | NO | `NULL` | - | - | Section identifier (e.g. A, B, Blue) |
| `capacity` | `INT` | YES | `NULL` | - | - | Maximum student capacity |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- hasMany Academics\SchoolClass (section_id)

---

#### 19. TABLE: `school_classes`

**Purpose:** Combines a grade, section, and academic year into an active class cohort.  
**Module:** Academics  
**Schema Status:** `CONFLICT`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `grade_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | grades.id | Grade level reference |
| `section_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | sections.id | Section reference |
| `academic_year_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | academic_years.id | Academic calendar year reference |
| `capacity` | `INT` | YES | `NULL` | - | - | Target seating capacity |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `school_classes_grade_id_index` (`grade_id`)`
- `INDEX `school_classes_section_id_index` (`section_id`)`
- `INDEX `school_classes_academic_year_id_index` (`academic_year_id`)`

##### Relationships:
- belongsTo Academics\Grade (grade_id -> grades.id)
- belongsTo Academics\Section (section_id -> sections.id)
- belongsTo Core\AcademicYear (academic_year_id -> academic_years.id)
- hasMany Academics\ClassSubject (class_id)
- hasMany Academics\Timetable (class_id)

---

#### 20. TABLE: `subjects`

**Purpose:** Academic curriculum subjects (e.g. Mathematics, English, Physics).  
**Module:** Academics  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `name` | `VARCHAR(255)` | NO | `NULL` | - | - | Subject name |
| `code` | `VARCHAR(50)` | NO | `NULL` | UNI | - | Unique subject course code (e.g. MATH101) |
| `type` | `VARCHAR(50)` | YES | `theory` | - | - | Course format (theory, practical, elective) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `UNIQUE `subjects_code_unique` (`code`)`

##### Relationships:
- hasMany Academics\ClassSubject (subject_id)
- hasMany Academics\TimetableEntry (subject_id)

---

#### 21. TABLE: `subject_groups`

**Purpose:** Subject department or clustering (e.g. STEM, Humanities, Languages).  
**Module:** Academics  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `name` | `VARCHAR(100)` | NO | `NULL` | - | - | Group name |
| `description` | `TEXT` | YES | `NULL` | - | - | Detailed overview |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- None / Self-contained

---

#### 22. TABLE: `class_subjects`

**Purpose:** Assigns curriculum subjects to school classes.  
**Module:** Academics  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `class_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | school_classes.id | School class ID |
| `subject_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | subjects.id | Subject ID |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `class_subjects_class_id_index` (`class_id`)`
- `INDEX `class_subjects_subject_id_index` (`subject_id`)`
- `UNIQUE `class_subjects_class_subject_unique` (`class_id`, `subject_id`)`

##### Relationships:
- belongsTo Academics\SchoolClass (class_id -> school_classes.id)
- belongsTo Academics\Subject (subject_id -> subjects.id)
- hasMany Academics\TeacherAssignment (class_subject_id)

---

#### 23. TABLE: `classrooms`

**Purpose:** Physical room and building inventory for timetable scheduling.  
**Module:** Academics  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `name` | `VARCHAR(100)` | NO | `NULL` | - | - | Room number or room name |
| `building` | `VARCHAR(100)` | YES | `NULL` | - | - | Building or wing name |
| `capacity` | `INT` | YES | `NULL` | - | - | Seating capacity |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- hasMany Academics\TimetableEntry (classroom_id)

---

#### 24. TABLE: `teacher_assignments`

**Purpose:** Assigns faculty teachers to class-subject combinations per academic year.  
**Module:** Academics  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `employee_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | employees.id | Assigned teacher employee ID |
| `class_subject_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | class_subjects.id | Target class subject ID |
| `academic_year_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | academic_years.id | Academic year ID |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `teacher_assignments_employee_id_index` (`employee_id`)`
- `INDEX `teacher_assignments_class_subject_id_index` (`class_subject_id`)`
- `INDEX `teacher_assignments_academic_year_id_index` (`academic_year_id`)`

##### Relationships:
- belongsTo Academics\ClassSubject (class_subject_id -> class_subjects.id)
- belongsTo Core\AcademicYear (academic_year_id -> academic_years.id)

---

#### 25. TABLE: `timetables`

**Purpose:** Timetable schedule container for a class, term, and academic year.  
**Module:** Academics  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `class_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | school_classes.id | School class ID |
| `academic_year_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | academic_years.id | Academic year ID |
| `school_term_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | school_terms.id | School term ID |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `timetables_class_id_index` (`class_id`)`
- `INDEX `timetables_academic_year_id_index` (`academic_year_id`)`
- `INDEX `timetables_school_term_id_index` (`school_term_id`)`

##### Relationships:
- belongsTo Academics\SchoolClass (class_id -> school_classes.id)
- belongsTo Core\AcademicYear (academic_year_id -> academic_years.id)
- belongsTo Core\SchoolTerm (school_term_id -> school_terms.id)
- hasMany Academics\TimetableEntry (timetable_id)

---

#### 26. TABLE: `timetable_entries`

**Purpose:** Individual scheduled periods mapping day, slot, subject, teacher, and classroom.  
**Module:** Academics  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key |
| `timetable_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | timetables.id | Parent timetable ID |
| `time_slot_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | time_slots.id | Session time slot ID |
| `day_of_week` | `VARCHAR(20)` | NO | `NULL` | - | - | Day (Monday, Tuesday, etc.) |
| `subject_id` | `BIGINT UNSIGNED` | NO | `NULL` | MUL | subjects.id | Scheduled subject ID |
| `teacher_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | users.id | Assigned teacher user ID |
| `classroom_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | classrooms.id | Assigned classroom ID |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Record update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `timetable_entries_timetable_id_index` (`timetable_id`)`
- `INDEX `timetable_entries_time_slot_id_index` (`time_slot_id`)`
- `INDEX `timetable_entries_subject_id_index` (`subject_id`)`
- `INDEX `timetable_entries_teacher_id_index` (`teacher_id`)`
- `INDEX `timetable_entries_classroom_id_index` (`classroom_id`)`

##### Relationships:
- belongsTo Academics\Timetable (timetable_id -> timetables.id)
- belongsTo Core\TimeSlot (time_slot_id -> time_slots.id)
- belongsTo Academics\Subject (subject_id -> subjects.id)
- belongsTo Academics\Classroom (classroom_id -> classrooms.id)

---

### Core Module

#### 27. TABLE: `schools`

**Purpose:** Domain model table for School in Core module.  
**Module:** Core  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `address` | `TEXT` | YES | `NULL` | - | - | Attribute: address |
| `phone` | `VARCHAR(50)` | YES | `NULL` | - | - | Attribute: phone |
| `email` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: email |
| `website` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: website |
| `logo_path` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: logo_path |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany SchoolBranch (school_id)
- HasMany SchoolSetting (school_id)

---

#### 28. TABLE: `school_branches`

**Purpose:** Domain model table for SchoolBranch in Core module.  
**Module:** Core  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `school_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | schools.id | Attribute: school_id |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `address` | `TEXT` | YES | `NULL` | - | - | Attribute: address |
| `contact_number` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: contact_number |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `school_branches_school_id_index` (`school_id`)`

##### Relationships:
- BelongsTo School (school_id)

---

#### 29. TABLE: `school_settings`

**Purpose:** Domain model table for SchoolSetting in Core module.  
**Module:** Core  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `school_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | schools.id | Attribute: school_id |
| `setting_key` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: setting_key |
| `setting_value` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: setting_value |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `school_settings_school_id_index` (`school_id`)`

##### Relationships:
- BelongsTo School (school_id)

---

#### 30. TABLE: `school_shifts`

**Purpose:** Domain model table for SchoolShift in Core module.  
**Module:** Core  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `start_time` | `TIME` | YES | `NULL` | - | - | Attribute: start_time |
| `end_time` | `TIME` | YES | `NULL` | - | - | Attribute: end_time |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany TimeSlot (school_shift_id)

---

#### 31. TABLE: `school_terms`

**Purpose:** Domain model table for SchoolTerm in Core module.  
**Module:** Core  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `academic_year_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | academic_years.id | Attribute: academic_year_id |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `start_date` | `DATE` | YES | `NULL` | - | - | Attribute: start_date |
| `end_date` | `DATE` | YES | `NULL` | - | - | Attribute: end_date |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `school_terms_academic_year_id_index` (`academic_year_id`)`

##### Relationships:
- BelongsTo AcademicYear (academic_year_id)
- HasMany Timetable (school_term_id)

---

#### 32. TABLE: `academic_years`

**Purpose:** Domain model table for AcademicYear in Core module.  
**Module:** Core  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `start_date` | `DATE` | YES | `NULL` | - | - | Attribute: start_date |
| `end_date` | `DATE` | YES | `NULL` | - | - | Attribute: end_date |
| `is_active` | `BOOLEAN` | YES | `NULL` | - | - | Attribute: is_active |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany SchoolTerm (academic_year_id)
- HasMany Holiday (academic_year_id)
- HasMany SchoolClass (academic_year_id)
- HasMany TeacherAssignment (academic_year_id)
- HasMany Timetable (academic_year_id)

---

#### 33. TABLE: `holidays`

**Purpose:** Domain model table for Holiday in Core module.  
**Module:** Core  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `academic_year_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | academic_years.id | Attribute: academic_year_id |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `start_date` | `DATE` | YES | `NULL` | - | - | Attribute: start_date |
| `end_date` | `DATE` | YES | `NULL` | - | - | Attribute: end_date |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `holidays_academic_year_id_index` (`academic_year_id`)`

##### Relationships:
- BelongsTo AcademicYear (academic_year_id)

---

#### 34. TABLE: `time_slots`

**Purpose:** Domain model table for TimeSlot in Core module.  
**Module:** Core  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `school_shift_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | school_shifts.id | Attribute: school_shift_id |
| `start_time` | `TIME` | YES | `NULL` | - | - | Attribute: start_time |
| `end_time` | `TIME` | YES | `NULL` | - | - | Attribute: end_time |
| `is_break` | `BOOLEAN` | YES | `NULL` | - | - | Attribute: is_break |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `time_slots_school_shift_id_index` (`school_shift_id`)`

##### Relationships:
- BelongsTo SchoolShift (school_shift_id)
- HasMany TimetableEntry (time_slot_id)

---

### Employees Module

#### 35. TABLE: `employees`

**Purpose:** Domain model table for Employee in Employees module.  
**Module:** Employees  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `user_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | users.id | Attribute: user_id |
| `employee_department_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | employee_departments.id | Attribute: employee_department_id |
| `employee_position_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | employee_positions.id | Attribute: employee_position_id |
| `employee_status_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | employee_statuss.id | Attribute: employee_status_id |
| `employee_code` | `VARCHAR(255)` | YES | `NULL` | UNI | - | Attribute: employee_code |
| `date_of_joining` | `DATE` | YES | `NULL` | - | - | Attribute: date_of_joining |
| `date_of_birth` | `DATE` | YES | `NULL` | - | - | Attribute: date_of_birth |
| `gender` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: gender |
| `blood_group` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: blood_group |
| `basic_salary` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: basic_salary |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `employees_user_id_index` (`user_id`)`
- `INDEX `employees_employee_department_id_index` (`employee_department_id`)`
- `INDEX `employees_employee_position_id_index` (`employee_position_id`)`
- `INDEX `employees_employee_status_id_index` (`employee_status_id`)`
- `UNIQUE `employees_employee_code_unique` (`employee_code`)`

##### Relationships:
- BelongsTo User (user_id)
- BelongsTo EmployeeDepartment (employee_department_id)
- BelongsTo EmployeePosition (employee_position_id)
- BelongsTo EmployeeStatus (employee_status_id)
- HasOne EmployeeContact (employee_id)
- HasMany EmployeeDocument (employee_id)
- HasMany EmployeeLeave (employee_id)
- HasMany EmployeeAttendance (employee_id)

---

#### 36. TABLE: `employee_positions`

**Purpose:** Domain model table for EmployeePosition in Employees module.  
**Module:** Employees  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany Employee (employee_position_id)

---

#### 37. TABLE: `employee_departments`

**Purpose:** Domain model table for EmployeeDepartment in Employees module.  
**Module:** Employees  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany Employee (employee_department_id)

---

#### 38. TABLE: `employee_statuses`

**Purpose:** Domain model table for EmployeeStatus in Employees module.  
**Module:** Employees  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany Employee (employee_status_id)

---

#### 39. TABLE: `employee_contacts`

**Purpose:** Domain model table for EmployeeContact in Employees module.  
**Module:** Employees  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `employee_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | employees.id | Attribute: employee_id |
| `phone` | `VARCHAR(50)` | YES | `NULL` | - | - | Attribute: phone |
| `emergency_phone` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: emergency_phone |
| `email` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: email |
| `current_address` | `TEXT` | YES | `NULL` | - | - | Attribute: current_address |
| `permanent_address` | `TEXT` | YES | `NULL` | - | - | Attribute: permanent_address |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `employee_contacts_employee_id_index` (`employee_id`)`

##### Relationships:
- BelongsTo Employee (employee_id)

---

#### 40. TABLE: `employee_documents`

**Purpose:** Domain model table for EmployeeDocument in Employees module.  
**Module:** Employees  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `employee_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | employees.id | Attribute: employee_id |
| `document_type` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: document_type |
| `document_path` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: document_path |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `employee_documents_employee_id_index` (`employee_id`)`

##### Relationships:
- BelongsTo Employee (employee_id)

---

#### 41. TABLE: `employee_leaves`

**Purpose:** Domain model table for EmployeeLeave in Employees module.  
**Module:** Employees  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `employee_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | employees.id | Attribute: employee_id |
| `leave_type_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | leave_types.id | Attribute: leave_type_id |
| `start_date` | `DATE` | YES | `NULL` | - | - | Attribute: start_date |
| `end_date` | `DATE` | YES | `NULL` | - | - | Attribute: end_date |
| `reason` | `TEXT` | YES | `NULL` | - | - | Attribute: reason |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `approved_by` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: approved_by |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `employee_leaves_employee_id_index` (`employee_id`)`
- `INDEX `employee_leaves_leave_type_id_index` (`leave_type_id`)`

##### Relationships:
- BelongsTo Employee (employee_id)
- BelongsTo LeaveType (leave_type_id)
- BelongsTo User (approved_by)

---

#### 42. TABLE: `leave_types`

**Purpose:** Domain model table for LeaveType in Employees module.  
**Module:** Employees  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `days_allowed` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: days_allowed |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany EmployeeLeave (leave_type_id)

---

#### 43. TABLE: `employee_attendances`

**Purpose:** Domain model table for EmployeeAttendance in Employees module.  
**Module:** Employees  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `employee_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | employees.id | Attribute: employee_id |
| `attendance_date` | `DATE` | YES | `NULL` | - | - | Attribute: attendance_date |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `in_time` | `TIME` | YES | `NULL` | - | - | Attribute: in_time |
| `out_time` | `TIME` | YES | `NULL` | - | - | Attribute: out_time |
| `remarks` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: remarks |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `employee_attendances_employee_id_index` (`employee_id`)`

##### Relationships:
- BelongsTo Employee (employee_id)

---

### Enrollment Module

#### 44. TABLE: `enrollments`

**Purpose:** Domain model table for Enrollment in Enrollment module.  
**Module:** Enrollment  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Attribute: student_id |
| `academic_year_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | academic_years.id | Attribute: academic_year_id |
| `enrollment_date` | `DATE` | YES | `NULL` | - | - | Attribute: enrollment_date |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `enrollments_student_id_index` (`student_id`)`
- `INDEX `enrollments_academic_year_id_index` (`academic_year_id`)`

##### Relationships:
- BelongsTo Student (student_id)
- BelongsTo AcademicYear (academic_year_id)
- HasOne EnrollmentDetail (enrollment_id)

---

#### 45. TABLE: `enrollment_details`

**Purpose:** Domain model table for EnrollmentDetail in Enrollment module.  
**Module:** Enrollment  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `enrollment_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | enrollments.id | Attribute: enrollment_id |
| `class_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | school_classes.id | Attribute: class_id |
| `section_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | sections.id | Attribute: section_id |
| `roll_number` | `VARCHAR(50)` | YES | `NULL` | - | - | Attribute: roll_number |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `enrollment_details_enrollment_id_index` (`enrollment_id`)`
- `INDEX `enrollment_details_class_id_index` (`class_id`)`
- `INDEX `enrollment_details_section_id_index` (`section_id`)`

##### Relationships:
- BelongsTo Enrollment (enrollment_id)
- BelongsTo SchoolClass (class_id)
- BelongsTo Section (section_id)

---

#### 46. TABLE: `student_promotions`

**Purpose:** Domain model table for StudentPromotion in Enrollment module.  
**Module:** Enrollment  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Attribute: student_id |
| `from_academic_year_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | academic_years.id | Attribute: from_academic_year_id |
| `to_academic_year_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | academic_years.id | Attribute: to_academic_year_id |
| `from_class_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | school_classes.id | Attribute: from_class_id |
| `to_class_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | school_classes.id | Attribute: to_class_id |
| `promotion_date` | `DATE` | YES | `NULL` | - | - | Attribute: promotion_date |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_promotions_student_id_index` (`student_id`)`
- `INDEX `student_promotions_from_academic_year_id_index` (`from_academic_year_id`)`
- `INDEX `student_promotions_to_academic_year_id_index` (`to_academic_year_id`)`
- `INDEX `student_promotions_from_class_id_index` (`from_class_id`)`
- `INDEX `student_promotions_to_class_id_index` (`to_class_id`)`

##### Relationships:
- BelongsTo Student (student_id)
- BelongsTo AcademicYear (from_academic_year_id)
- BelongsTo AcademicYear (to_academic_year_id)
- BelongsTo SchoolClass (from_class_id)
- BelongsTo SchoolClass (to_class_id)

---

#### 47. TABLE: `student_transfers`

**Purpose:** Domain model table for StudentTransfer in Enrollment module.  
**Module:** Enrollment  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Attribute: student_id |
| `from_school_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | schools.id | Attribute: from_school_id |
| `to_school_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | schools.id | Attribute: to_school_id |
| `transfer_date` | `DATE` | YES | `NULL` | - | - | Attribute: transfer_date |
| `reason` | `TEXT` | YES | `NULL` | - | - | Attribute: reason |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_transfers_student_id_index` (`student_id`)`
- `INDEX `student_transfers_from_school_id_index` (`from_school_id`)`
- `INDEX `student_transfers_to_school_id_index` (`to_school_id`)`

##### Relationships:
- BelongsTo Student (student_id)
- BelongsTo School (from_school_id)
- BelongsTo School (to_school_id)

---

#### 48. TABLE: `withdrawals`

**Purpose:** Domain model table for Withdrawal in Enrollment module.  
**Module:** Enrollment  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Attribute: student_id |
| `withdrawal_date` | `DATE` | YES | `NULL` | - | - | Attribute: withdrawal_date |
| `reason` | `TEXT` | YES | `NULL` | - | - | Attribute: reason |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `withdrawals_student_id_index` (`student_id`)`

##### Relationships:
- BelongsTo Student (student_id)

---

### Examination Module

#### 49. TABLE: `exams`

**Purpose:** Domain model table for Exam in Examination module.  
**Module:** Examination  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `exam_type_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | exam_types.id | Attribute: exam_type_id |
| `academic_year_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | academic_years.id | Attribute: academic_year_id |
| `school_term_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | school_terms.id | Attribute: school_term_id |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `start_date` | `DATE` | YES | `NULL` | - | - | Attribute: start_date |
| `end_date` | `DATE` | YES | `NULL` | - | - | Attribute: end_date |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `exams_exam_type_id_index` (`exam_type_id`)`
- `INDEX `exams_academic_year_id_index` (`academic_year_id`)`
- `INDEX `exams_school_term_id_index` (`school_term_id`)`

##### Relationships:
- BelongsTo ExamType (exam_type_id)
- BelongsTo AcademicYear (academic_year_id)
- BelongsTo SchoolTerm (school_term_id)
- HasMany ExamSchedule (exam_id)
- HasMany StudentGrade (exam_id)

---

#### 50. TABLE: `exam_types`

**Purpose:** Domain model table for ExamType in Examination module.  
**Module:** Examination  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany Exam (exam_type_id)

---

#### 51. TABLE: `exam_subjects`

**Purpose:** Domain model table for ExamSubject in Examination module.  
**Module:** Examination  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `exam_schedule_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | exam_schedules.id | Attribute: exam_schedule_id |
| `subject_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | subjects.id | Attribute: subject_id |
| `exam_date` | `DATE` | YES | `NULL` | - | - | Attribute: exam_date |
| `start_time` | `TIME` | YES | `NULL` | - | - | Attribute: start_time |
| `end_time` | `TIME` | YES | `NULL` | - | - | Attribute: end_time |
| `max_marks` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: max_marks |
| `pass_marks` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: pass_marks |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `exam_subjects_exam_schedule_id_index` (`exam_schedule_id`)`
- `INDEX `exam_subjects_subject_id_index` (`subject_id`)`

##### Relationships:
- BelongsTo ExamSchedule (exam_schedule_id)
- BelongsTo Subject (subject_id)
- HasMany ExamResult (exam_subject_id)

---

#### 52. TABLE: `exam_schedules`

**Purpose:** Domain model table for ExamSchedule in Examination module.  
**Module:** Examination  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `exam_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | exams.id | Attribute: exam_id |
| `class_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | school_classes.id | Attribute: class_id |
| `section_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | sections.id | Attribute: section_id |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `exam_schedules_exam_id_index` (`exam_id`)`
- `INDEX `exam_schedules_class_id_index` (`class_id`)`
- `INDEX `exam_schedules_section_id_index` (`section_id`)`

##### Relationships:
- BelongsTo Exam (exam_id)
- BelongsTo SchoolClass (class_id)
- BelongsTo Section (section_id)
- HasMany ExamSubject (exam_schedule_id)

---

#### 53. TABLE: `grade_scales`

**Purpose:** Domain model table for GradeScale in Examination module.  
**Module:** Examination  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `min_percentage` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: min_percentage |
| `max_percentage` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: max_percentage |
| `grade` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: grade |
| `gpa_point` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: gpa_point |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- None / Self-contained

---

#### 54. TABLE: `student_grades`

**Purpose:** Domain model table for StudentGrade in Examination module.  
**Module:** Examination  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `exam_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | exams.id | Attribute: exam_id |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Attribute: student_id |
| `total_marks` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: total_marks |
| `percentage` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: percentage |
| `grade` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: grade |
| `gpa` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: gpa |
| `result_status` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: result_status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_grades_exam_id_index` (`exam_id`)`
- `INDEX `student_grades_student_id_index` (`student_id`)`

##### Relationships:
- BelongsTo Exam (exam_id)
- BelongsTo Student (student_id)

---

#### 55. TABLE: `exam_results`

**Purpose:** Domain model table for ExamResult in Examination module.  
**Module:** Examination  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `exam_subject_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | exam_subjects.id | Attribute: exam_subject_id |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Attribute: student_id |
| `marks_obtained` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: marks_obtained |
| `grade` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: grade |
| `remarks` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: remarks |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `exam_results_exam_subject_id_index` (`exam_subject_id`)`
- `INDEX `exam_results_student_id_index` (`student_id`)`

##### Relationships:
- BelongsTo ExamSubject (exam_subject_id)
- BelongsTo Student (student_id)

---

### Finance Module

#### 56. TABLE: `fee_categories`

**Purpose:** Domain model table for FeeCategory in Finance module.  
**Module:** Finance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany FeeStructure (fee_category_id)
- HasMany InvoiceDetail (fee_category_id)

---

#### 57. TABLE: `fee_structures`

**Purpose:** Domain model table for FeeStructure in Finance module.  
**Module:** Finance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `fee_category_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | fee_categorys.id | Attribute: fee_category_id |
| `academic_year_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | academic_years.id | Attribute: academic_year_id |
| `class_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | school_classes.id | Attribute: class_id |
| `amount` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: amount |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `fee_structures_fee_category_id_index` (`fee_category_id`)`
- `INDEX `fee_structures_academic_year_id_index` (`academic_year_id`)`
- `INDEX `fee_structures_class_id_index` (`class_id`)`

##### Relationships:
- BelongsTo FeeCategory (fee_category_id)
- BelongsTo AcademicYear (academic_year_id)
- BelongsTo SchoolClass (class_id)
- HasMany StudentInvoice (fee_structure_id)

---

#### 58. TABLE: `expense_categories`

**Purpose:** Domain model table for ExpenseCategory in Finance module.  
**Module:** Finance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany SchoolExpense (expense_category_id)

---

#### 59. TABLE: `school_expenses`

**Purpose:** Domain model table for SchoolExpense in Finance module.  
**Module:** Finance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `expense_category_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | expense_categories.id | Attribute: expense_category_id |
| `amount` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: amount |
| `expense_date` | `DATE` | YES | `NULL` | - | - | Attribute: expense_date |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `receipt_path` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: receipt_path |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `school_expenses_expense_category_id_index` (`expense_category_id`)`

##### Relationships:
- BelongsTo ExpenseCategory (expense_category_id)

---

#### 60. TABLE: `payment_methods`

**Purpose:** Domain model table for PaymentMethod in Finance module.  
**Module:** Finance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany StudentPayment (payment_method_id)

---

#### 61. TABLE: `student_invoices`

**Purpose:** Domain model table for StudentInvoice in Finance module.  
**Module:** Finance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Attribute: student_id |
| `fee_structure_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | fee_structures.id | Attribute: fee_structure_id |
| `total_amount` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: total_amount |
| `discount_amount` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: discount_amount |
| `net_amount` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: net_amount |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `due_date` | `DATE` | YES | `NULL` | - | - | Attribute: due_date |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_invoices_student_id_index` (`student_id`)`
- `INDEX `student_invoices_fee_structure_id_index` (`fee_structure_id`)`

##### Relationships:
- BelongsTo Student (student_id)
- BelongsTo FeeStructure (fee_structure_id)
- HasMany InvoiceDetail (student_invoice_id)
- HasMany StudentPayment (student_invoice_id)

---

#### 62. TABLE: `invoice_details`

**Purpose:** Domain model table for InvoiceDetail in Finance module.  
**Module:** Finance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `student_invoice_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | student_invoices.id | Attribute: student_invoice_id |
| `fee_category_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | fee_categorys.id | Attribute: fee_category_id |
| `amount` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: amount |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `invoice_details_student_invoice_id_index` (`student_invoice_id`)`
- `INDEX `invoice_details_fee_category_id_index` (`fee_category_id`)`

##### Relationships:
- BelongsTo StudentInvoice (student_invoice_id)
- BelongsTo FeeCategory (fee_category_id)

---

#### 63. TABLE: `student_payments`

**Purpose:** Domain model table for StudentPayment in Finance module.  
**Module:** Finance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `student_invoice_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | student_invoices.id | Attribute: student_invoice_id |
| `payment_method_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | payment_methods.id | Attribute: payment_method_id |
| `amount` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: amount |
| `payment_date` | `DATE` | YES | `NULL` | - | - | Attribute: payment_date |
| `transaction_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | transactions.id | Attribute: transaction_id |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_payments_student_invoice_id_index` (`student_invoice_id`)`
- `INDEX `student_payments_payment_method_id_index` (`payment_method_id`)`
- `INDEX `student_payments_transaction_id_index` (`transaction_id`)`

##### Relationships:
- BelongsTo StudentInvoice (student_invoice_id)
- BelongsTo PaymentMethod (payment_method_id)

---

### Library Module

#### 64. TABLE: `library_categories`

**Purpose:** Domain model table for LibraryCategory in Library module.  
**Module:** Library  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`

##### Relationships:
- HasMany LibraryBook (library_category_id)

---

#### 65. TABLE: `library_books`

**Purpose:** Domain model table for LibraryBook in Library module.  
**Module:** Library  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `title` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: title |
| `author` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: author |
| `publisher` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: publisher |
| `isbn` | `VARCHAR(50)` | YES | `NULL` | UNI | - | Attribute: isbn |
| `library_category_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | library_categories.id | Attribute: library_category_id |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `UNIQUE `library_books_isbn_unique` (`isbn`)`
- `INDEX `library_books_library_category_id_index` (`library_category_id`)`

##### Relationships:
- BelongsTo LibraryCategory (library_category_id)
- HasMany LibraryBookCopy (library_book_id)

---

#### 66. TABLE: `library_book_copies`

**Purpose:** Domain model table for LibraryBookCopy in Library module.  
**Module:** Library  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `library_book_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | library_books.id | Attribute: library_book_id |
| `accession_number` | `VARCHAR(50)` | YES | `NULL` | UNI | - | Attribute: accession_number |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `library_book_copies_library_book_id_index` (`library_book_id`)`
- `UNIQUE `library_book_copies_accession_number_unique` (`accession_number`)`

##### Relationships:
- BelongsTo LibraryBook (library_book_id)
- HasMany LibraryIssue (library_book_copy_id)

---

#### 67. TABLE: `library_issues`

**Purpose:** Domain model table for LibraryIssue in Library module.  
**Module:** Library  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `library_book_copy_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | library_book_copies.id | Attribute: library_book_copy_id |
| `user_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | users.id | Attribute: user_id |
| `issue_date` | `DATE` | YES | `NULL` | - | - | Attribute: issue_date |
| `due_date` | `DATE` | YES | `NULL` | - | - | Attribute: due_date |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `library_issues_library_book_copy_id_index` (`library_book_copy_id`)`
- `INDEX `library_issues_user_id_index` (`user_id`)`

##### Relationships:
- BelongsTo LibraryBookCopy (library_book_copy_id)
- BelongsTo User (user_id)
- HasOne LibraryReturn (library_issue_id)

---

#### 68. TABLE: `library_returns`

**Purpose:** Domain model table for LibraryReturn in Library module.  
**Module:** Library  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `library_issue_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | library_issues.id | Attribute: library_issue_id |
| `return_date` | `DATE` | YES | `NULL` | - | - | Attribute: return_date |
| `fine_amount` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: fine_amount |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `library_returns_library_issue_id_index` (`library_issue_id`)`

##### Relationships:
- BelongsTo LibraryIssue (library_issue_id)

---

### Attendance Module

#### 69. TABLE: `attendance_statuses`

**Purpose:** Domain model table for AttendanceStatus in Attendance module.  
**Module:** Attendance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: name |
| `code` | `VARCHAR(50)` | YES | `NULL` | UNI | - | Attribute: code |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `UNIQUE `attendance_statuses_code_unique` (`code`)`

##### Relationships:
- HasMany StudentAttendance (attendance_status_id)
- HasMany AttendanceCorrection (old_status_id)
- HasMany AttendanceCorrection (new_status_id)

---

#### 70. TABLE: `attendance_sessions`

**Purpose:** Domain model table for AttendanceSession in Attendance module.  
**Module:** Attendance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `academic_year_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | academic_years.id | Attribute: academic_year_id |
| `class_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | school_classes.id | Attribute: class_id |
| `section_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | sections.id | Attribute: section_id |
| `attendance_date` | `DATE` | YES | `NULL` | - | - | Attribute: attendance_date |
| `recorded_by` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: recorded_by |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `attendance_sessions_academic_year_id_index` (`academic_year_id`)`
- `INDEX `attendance_sessions_class_id_index` (`class_id`)`
- `INDEX `attendance_sessions_section_id_index` (`section_id`)`

##### Relationships:
- BelongsTo AcademicYear (academic_year_id)
- BelongsTo Section (section_id)
- BelongsTo User (recorded_by)
- HasMany StudentAttendance (attendance_session_id)

---

#### 71. TABLE: `student_attendances`

**Purpose:** Domain model table for StudentAttendance in Attendance module.  
**Module:** Attendance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `attendance_session_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | attendance_sessions.id | Attribute: attendance_session_id |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Attribute: student_id |
| `attendance_status_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | attendance_statuses.id | Attribute: attendance_status_id |
| `remarks` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: remarks |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `student_attendances_attendance_session_id_index` (`attendance_session_id`)`
- `INDEX `student_attendances_student_id_index` (`student_id`)`
- `INDEX `student_attendances_attendance_status_id_index` (`attendance_status_id`)`

##### Relationships:
- BelongsTo AttendanceSession (attendance_session_id)
- BelongsTo Student (student_id)
- BelongsTo AttendanceStatus (attendance_status_id)
- HasMany AttendanceCorrection (student_attendance_id)

---

#### 72. TABLE: `attendance_corrections`

**Purpose:** Domain model table for AttendanceCorrection in Attendance module.  
**Module:** Attendance  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `student_attendance_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | student_attendances.id | Attribute: student_attendance_id |
| `old_status_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | attendance_statuses.id | Attribute: old_status_id |
| `new_status_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | attendance_statuses.id | Attribute: new_status_id |
| `reason` | `TEXT` | YES | `NULL` | - | - | Attribute: reason |
| `requested_by` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: requested_by |
| `approved_by` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: approved_by |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `attendance_corrections_student_attendance_id_index` (`student_attendance_id`)`
- `INDEX `attendance_corrections_old_status_id_index` (`old_status_id`)`
- `INDEX `attendance_corrections_new_status_id_index` (`new_status_id`)`

##### Relationships:
- BelongsTo StudentAttendance (student_attendance_id)
- BelongsTo AttendanceStatus (old_status_id)
- BelongsTo AttendanceStatus (new_status_id)
- BelongsTo User (requested_by)
- BelongsTo User (approved_by)

---

### Assignments Module

#### 73. TABLE: `assignments`

**Purpose:** Domain model table for Assignment in Assignments module.  
**Module:** Assignments  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `class_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | school_classes.id | Attribute: class_id |
| `section_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | sections.id | Attribute: section_id |
| `subject_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | subjects.id | Attribute: subject_id |
| `teacher_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | users.id | Attribute: teacher_id |
| `title` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: title |
| `description` | `TEXT` | YES | `NULL` | - | - | Attribute: description |
| `due_date` | `DATE` | YES | `NULL` | - | - | Attribute: due_date |
| `total_marks` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: total_marks |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `assignments_class_id_index` (`class_id`)`
- `INDEX `assignments_section_id_index` (`section_id`)`
- `INDEX `assignments_subject_id_index` (`subject_id`)`
- `INDEX `assignments_teacher_id_index` (`teacher_id`)`

##### Relationships:
- BelongsTo SchoolClass (class_id)
- BelongsTo Section (section_id)
- BelongsTo Subject (subject_id)
- BelongsTo User (teacher_id)
- HasMany AssignmentAttachment (assignment_id)
- HasMany AssignmentSubmission (assignment_id)

---

#### 74. TABLE: `assignment_attachments`

**Purpose:** Domain model table for AssignmentAttachment in Assignments module.  
**Module:** Assignments  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `assignment_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | assignments.id | Attribute: assignment_id |
| `file_name` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: file_name |
| `file_path` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: file_path |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `assignment_attachments_assignment_id_index` (`assignment_id`)`

##### Relationships:
- BelongsTo Assignment (assignment_id)

---

#### 75. TABLE: `assignment_submissions`

**Purpose:** Domain model table for AssignmentSubmission in Assignments module.  
**Module:** Assignments  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `YES (deleted_at)`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `assignment_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | assignments.id | Attribute: assignment_id |
| `student_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | students.id | Attribute: student_id |
| `submission_date` | `DATE` | YES | `NULL` | - | - | Attribute: submission_date |
| `status` | `VARCHAR(30)` | YES | `NULL` | - | - | Attribute: status |
| `remarks` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: remarks |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |
| `deleted_at` | `TIMESTAMP` | YES | `NULL` | - | - | Soft delete timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `assignment_submissions_assignment_id_index` (`assignment_id`)`
- `INDEX `assignment_submissions_student_id_index` (`student_id`)`

##### Relationships:
- BelongsTo Assignment (assignment_id)
- BelongsTo Student (student_id)
- HasOne AssignmentGrade (assignment_submission_id)

---

#### 76. TABLE: `assignment_grades`

**Purpose:** Domain model table for AssignmentGrade in Assignments module.  
**Module:** Assignments  
**Schema Status:** `NEEDS REVIEW`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Primary Key autoincrement ID |
| `assignment_submission_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | assignment_submissions.id | Attribute: assignment_submission_id |
| `marks_obtained` | `DECIMAL(12,2)` | YES | `NULL` | - | - | Attribute: marks_obtained |
| `grade` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: grade |
| `feedback` | `TEXT` | YES | `NULL` | - | - | Attribute: feedback |
| `graded_by` | `VARCHAR(255)` | YES | `NULL` | - | - | Attribute: graded_by |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Creation timestamp |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | - | - | Update timestamp |

##### Indexes:
- `PRIMARY (`id`)`
- `INDEX `assignment_grades_assignment_submission_id_index` (`assignment_submission_id`)`

##### Relationships:
- BelongsTo AssignmentSubmission (assignment_submission_id)
- BelongsTo User (graded_by)

---

### Infrastructure Module

#### 77. TABLE: `migrations`

**Purpose:** Laravel system framework infrastructure table for migrations.  
**Module:** Infrastructure  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `INT UNSIGNED` | NO | `NULL` | PRI | - | Framework migrations attribute |
| `migration` | `VARCHAR(255)` | NO | `NULL` | - | - | Framework migrations attribute |
| `batch` | `INT` | NO | `NULL` | - | - | Framework migrations attribute |

##### Indexes:
- `Managed by Laravel framework migrations`

##### Relationships:
- Laravel Framework Internal

---

#### 78. TABLE: `sessions`

**Purpose:** Laravel system framework infrastructure table for sessions.  
**Module:** Infrastructure  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `VARCHAR(255)` | NO | `NULL` | PRI | - | Framework sessions attribute |
| `user_id` | `BIGINT UNSIGNED` | YES | `NULL` | MUL | - | Framework sessions attribute |
| `ip_address` | `VARCHAR(45)` | YES | `NULL` | - | - | Framework sessions attribute |
| `user_agent` | `TEXT` | YES | `NULL` | - | - | Framework sessions attribute |
| `payload` | `LONGTEXT` | NO | `NULL` | - | - | Framework sessions attribute |
| `last_activity` | `INT` | NO | `NULL` | MUL | - | Framework sessions attribute |

##### Indexes:
- `Managed by Laravel framework migrations`

##### Relationships:
- Laravel Framework Internal

---

#### 79. TABLE: `password_reset_tokens`

**Purpose:** Laravel system framework infrastructure table for password_reset_tokens.  
**Module:** Infrastructure  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `email` | `VARCHAR(255)` | NO | `NULL` | PRI | - | Framework password_reset_tokens attribute |
| `token` | `VARCHAR(255)` | NO | `NULL` | - | - | Framework password_reset_tokens attribute |
| `created_at` | `TIMESTAMP` | YES | `NULL` | - | - | Framework password_reset_tokens attribute |

##### Indexes:
- `Managed by Laravel framework migrations`

##### Relationships:
- Laravel Framework Internal

---

#### 80. TABLE: `cache`

**Purpose:** Laravel system framework infrastructure table for cache.  
**Module:** Infrastructure  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `key` | `VARCHAR(255)` | NO | `NULL` | PRI | - | Framework cache attribute |
| `value` | `MEDIUMTEXT` | NO | `NULL` | - | - | Framework cache attribute |
| `expiration` | `BIGINT` | NO | `NULL` | MUL | - | Framework cache attribute |

##### Indexes:
- `Managed by Laravel framework migrations`

##### Relationships:
- Laravel Framework Internal

---

#### 81. TABLE: `cache_locks`

**Purpose:** Laravel system framework infrastructure table for cache_locks.  
**Module:** Infrastructure  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `key` | `VARCHAR(255)` | NO | `NULL` | PRI | - | Framework cache_locks attribute |
| `owner` | `VARCHAR(255)` | NO | `NULL` | - | - | Framework cache_locks attribute |
| `expiration` | `BIGINT` | NO | `NULL` | MUL | - | Framework cache_locks attribute |

##### Indexes:
- `Managed by Laravel framework migrations`

##### Relationships:
- Laravel Framework Internal

---

#### 82. TABLE: `jobs`

**Purpose:** Laravel system framework infrastructure table for jobs.  
**Module:** Infrastructure  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Framework jobs attribute |
| `queue` | `VARCHAR(255)` | NO | `NULL` | MUL | - | Framework jobs attribute |
| `payload` | `LONGTEXT` | NO | `NULL` | - | - | Framework jobs attribute |
| `attempts` | `SMALLINT UNSIGNED` | NO | `NULL` | - | - | Framework jobs attribute |
| `reserved_at` | `INT UNSIGNED` | YES | `NULL` | - | - | Framework jobs attribute |
| `available_at` | `INT UNSIGNED` | NO | `NULL` | - | - | Framework jobs attribute |
| `created_at` | `INT UNSIGNED` | NO | `NULL` | - | - | Framework jobs attribute |

##### Indexes:
- `Managed by Laravel framework migrations`

##### Relationships:
- Laravel Framework Internal

---

#### 83. TABLE: `job_batches`

**Purpose:** Laravel system framework infrastructure table for job_batches.  
**Module:** Infrastructure  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `VARCHAR(255)` | NO | `NULL` | PRI | - | Framework job_batches attribute |
| `name` | `VARCHAR(255)` | NO | `NULL` | - | - | Framework job_batches attribute |
| `total_jobs` | `INT` | NO | `NULL` | - | - | Framework job_batches attribute |
| `pending_jobs` | `INT` | NO | `NULL` | - | - | Framework job_batches attribute |
| `failed_jobs` | `INT` | NO | `NULL` | - | - | Framework job_batches attribute |
| `failed_job_ids` | `LONGTEXT` | NO | `NULL` | - | - | Framework job_batches attribute |
| `options` | `MEDIUMTEXT` | YES | `NULL` | - | - | Framework job_batches attribute |
| `cancelled_at` | `INT` | YES | `NULL` | - | - | Framework job_batches attribute |
| `created_at` | `INT` | NO | `NULL` | - | - | Framework job_batches attribute |
| `finished_at` | `INT` | YES | `NULL` | - | - | Framework job_batches attribute |

##### Indexes:
- `Managed by Laravel framework migrations`

##### Relationships:
- Laravel Framework Internal

---

#### 84. TABLE: `failed_jobs`

**Purpose:** Laravel system framework infrastructure table for failed_jobs.  
**Module:** Infrastructure  
**Schema Status:** `READY`  
**Soft Delete:** `NO`  

##### Columns:

| Column | Type | Nullable | Default | Key | Foreign Key | Purpose |
|:---|:---|:---|:---|:---|:---|:---|
| `id` | `BIGINT UNSIGNED` | NO | `NULL` | PRI | - | Framework failed_jobs attribute |
| `uuid` | `VARCHAR(255)` | NO | `NULL` | UNI | - | Framework failed_jobs attribute |
| `connection` | `VARCHAR(255)` | NO | `NULL` | MUL | - | Framework failed_jobs attribute |
| `queue` | `VARCHAR(255)` | NO | `NULL` | - | - | Framework failed_jobs attribute |
| `payload` | `LONGTEXT` | NO | `NULL` | - | - | Framework failed_jobs attribute |
| `exception` | `LONGTEXT` | NO | `NULL` | - | - | Framework failed_jobs attribute |
| `failed_at` | `TIMESTAMP` | NO | `CURRENT_TIMESTAMP` | - | - | Framework failed_jobs attribute |

##### Indexes:
- `Managed by Laravel framework migrations`

##### Relationships:
- Laravel Framework Internal

---

## C. Model Conflicts

### 1. `StudentNote` Model Conflict
```text
Model: App\Models\Students\StudentNote
Model expects:
- $fillable: ['student_id', 'note']
- Relationship: belongsTo(Student::class, 'student_id')
- Student::notes() relationship: hasMany(StudentNote::class, 'student_id')

Current Database / Section 3 Specification:
- Table `student_notes` has only `id, created_at, updated_at`

Conflict:
Calling $student->notes or eager-loading notes causes SQL error: 1054 Unknown column 'student_notes.student_id'.

Recommendation for Review:
Add `student_id` (bigint unsigned, FK) and `note` (text) to `student_notes` during migration approval phase, OR remove the relationship from Student & StudentNote models.
```

### 2. `SchoolClass` Model Table Name Mismatch
```text
Model: App\Models\Academics\SchoolClass
Model defined: protected $table = 'classes';
Actual Database Table: `school_classes`

Conflict:
Any query on SchoolClass attempts to read table `classes` which does not exist in MySQL.

Recommendation for Review:
Update SchoolClass Model to declare `protected $table = 'school_classes';`.
```

### 3. `StudentGuardian` Pivot Table Resolution
```text
Model: App\Models\Students\StudentGuardian (extends Illuminate\Database\Eloquent\Relations\Pivot)
Actual Database Table: `student_guardians` (plural)
Eloquent Default Pivot: `student_guardian` (singular)

Recommendation for Review:
Declare explicit `protected $table = 'student_guardians';` in StudentGuardian pivot class to ensure consistency.
```

### 4. 66 Base Tables Column Void
```text
All 66 models across Academics, Core, Employees, Enrollment, Examination, Finance, Library, Attendance, and Assignments define $fillable, $casts, and relationships that do not exist in the physical MySQL tables.
Status: Awaiting owner approval on column design to generate modular corrective migrations.
```

---

## D. Missing / Extra Columns Summary

| Table | Missing Columns in DB | Unexpected Columns in DB | Reason |
|:---|:---|:---|:---|
| `student_notes` | `student_id`, `note` | None | Initial stub migration `02_...` was never amended with a `02a_...` migration. |
| `school_classes` | `grade_id`, `section_id`, `academic_year_id`, `capacity`, `deleted_at` | None | Migration `03_...` created only stub; domain columns pending. |
| `schools` | `name`, `address`, `phone`, `email`, `website`, `logo_path`, `status`, `deleted_at` | None | Domain columns defined in Model but stub in DB. |
| `academic_years` | `name`, `start_date`, `end_date`, `is_active`, `deleted_at` | None | Domain columns defined in Model but stub in DB. |
| `employees` | `user_id`, `employee_department_id`, `employee_position_id`, `employee_status_id`, `employee_code`, `date_of_joining`, `date_of_birth`, `gender`, `blood_group`, `basic_salary`, `deleted_at` | None | Domain columns defined in Model but stub in DB. |
| `enrollments` | `student_id`, `academic_year_id`, `enrollment_date`, `status`, `deleted_at` | None | Domain columns defined in Model but stub in DB. |
| `exams` | `exam_type_id`, `academic_year_id`, `school_term_id`, `name`, `start_date`, `end_date` | None | Domain columns defined in Model but stub in DB. |
| `student_invoices` | `student_id`, `fee_structure_id`, `total_amount`, `discount_amount`, `net_amount`, `status`, `due_date`, `deleted_at` | None | Domain columns defined in Model but stub in DB. |
| `library_books` | `title`, `author`, `publisher`, `isbn`, `library_category_id`, `deleted_at` | None | Domain columns defined in Model but stub in DB. |
| *(Remaining 57 tables)* | *See Table-by-Table specification above* | None | Stub migrations pending domain column phase. |

---

## E. Foreign-Key Matrix

| Child Table | Foreign Key Column | Parent Table | Parent Key | Relationship Type | Current DB Status |
|:---|:---|:---|:---|:---|:---|
| `students` | `school_id` | `schools` | `id` | Many-to-One | Column exists; FK index recommended |
| `students` | `user_id` | `users` | `id` | One-to-One | Column exists; FK index recommended |
| `students` | `student_status_id` | `student_statuses` | `id` | Many-to-One | Column exists; FK index recommended |
| `student_profiles` | `student_id` | `students` | `id` | One-to-One | Column exists; FK index recommended |
| `student_addresses` | `student_id` | `students` | `id` | One-to-One | Column exists; FK index recommended |
| `student_documents` | `student_id` | `students` | `id` | Many-to-One | Column exists; FK index recommended |
| `student_medical_records` | `student_id` | `students` | `id` | One-to-One | Column exists; FK index recommended |
| `guardians` | `user_id` | `users` | `id` | One-to-One | Column exists; FK index recommended |
| `guardian_contacts` | `guardian_id` | `guardians` | `id` | Many-to-One | Column exists; FK index recommended |
| `student_guardians` | `student_id` | `students` | `id` | Pivot Many-to-Many | Column exists; FK index recommended |
| `student_guardians` | `guardian_id` | `guardians` | `id` | Pivot Many-to-Many | Column exists; FK index recommended |
| `student_notes` | `student_id` | `students` | `id` | Many-to-One | Column MISSING in DB (Model Conflict) |
| `school_branches` | `school_id` | `schools` | `id` | Many-to-One | Column MISSING in DB |
| `school_settings` | `school_id` | `schools` | `id` | Many-to-One | Column MISSING in DB |
| `school_terms` | `academic_year_id` | `academic_years` | `id` | Many-to-One | Column MISSING in DB |
| `holidays` | `academic_year_id` | `academic_years` | `id` | Many-to-One | Column MISSING in DB |
| `time_slots` | `school_shift_id` | `school_shifts` | `id` | Many-to-One | Column MISSING in DB |
| `user_roles` | `user_id` | `users` | `id` | Many-to-One | Column MISSING in DB |
| `user_roles` | `role_id` | `roles` | `id` | Many-to-One | Column MISSING in DB |
| `role_permissions` | `role_id` | `roles` | `id` | Many-to-One | Column MISSING in DB |
| `role_permissions` | `permission_id` | `permissions` | `id` | Many-to-One | Column MISSING in DB |
| `login_activities` | `user_id` | `users` | `id` | Many-to-One | Column MISSING in DB |
| `school_classes` | `grade_id` | `grades` | `id` | Many-to-One | Column MISSING in DB |
| `school_classes` | `section_id` | `sections` | `id` | Many-to-One | Column MISSING in DB |
| `school_classes` | `academic_year_id` | `academic_years` | `id` | Many-to-One | Column MISSING in DB |
| `class_subjects` | `class_id` | `school_classes` | `id` | Many-to-One | Column MISSING in DB |
| `class_subjects` | `subject_id` | `subjects` | `id` | Many-to-One | Column MISSING in DB |
| `teacher_assignments` | `employee_id` | `employees` | `id` | Many-to-One | Column MISSING in DB |
| `teacher_assignments` | `class_subject_id` | `class_subjects` | `id` | Many-to-One | Column MISSING in DB |
| `teacher_assignments` | `academic_year_id` | `academic_years` | `id` | Many-to-One | Column MISSING in DB |
| `timetables` | `class_id` | `school_classes` | `id` | Many-to-One | Column MISSING in DB |
| `timetables` | `academic_year_id` | `academic_years` | `id` | Many-to-One | Column MISSING in DB |
| `timetables` | `school_term_id` | `school_terms` | `id` | Many-to-One | Column MISSING in DB |
| `timetable_entries` | `timetable_id` | `timetables` | `id` | Many-to-One | Column MISSING in DB |
| `timetable_entries` | `time_slot_id` | `time_slots` | `id` | Many-to-One | Column MISSING in DB |
| `timetable_entries` | `subject_id` | `subjects` | `id` | Many-to-One | Column MISSING in DB |
| `timetable_entries` | `classroom_id` | `classrooms` | `id` | Many-to-One | Column MISSING in DB |
| `employees` | `user_id` | `users` | `id` | One-to-One | Column MISSING in DB |
| `employees` | `employee_department_id` | `employee_departments` | `id` | Many-to-One | Column MISSING in DB |
| `employees` | `employee_position_id` | `employee_positions` | `id` | Many-to-One | Column MISSING in DB |
| `employees` | `employee_status_id` | `employee_statuses` | `id` | Many-to-One | Column MISSING in DB |
| `employee_contacts` | `employee_id` | `employees` | `id` | One-to-One | Column MISSING in DB |
| `employee_documents` | `employee_id` | `employees` | `id` | Many-to-One | Column MISSING in DB |
| `employee_leaves` | `employee_id` | `employees` | `id` | Many-to-One | Column MISSING in DB |
| `employee_leaves` | `leave_type_id` | `leave_types` | `id` | Many-to-One | Column MISSING in DB |
| `employee_attendances` | `employee_id` | `employees` | `id` | Many-to-One | Column MISSING in DB |
| `enrollments` | `student_id` | `students` | `id` | Many-to-One | Column MISSING in DB |
| `enrollments` | `academic_year_id` | `academic_years` | `id` | Many-to-One | Column MISSING in DB |
| `enrollment_details` | `enrollment_id` | `enrollments` | `id` | One-to-One | Column MISSING in DB |
| `enrollment_details` | `class_id` | `school_classes` | `id` | Many-to-One | Column MISSING in DB |
| `enrollment_details` | `section_id` | `sections` | `id` | Many-to-One | Column MISSING in DB |
| `student_promotions` | `student_id` | `students` | `id` | Many-to-One | Column MISSING in DB |
| `student_transfers` | `student_id` | `students` | `id` | Many-to-One | Column MISSING in DB |
| `withdrawals` | `student_id` | `students` | `id` | Many-to-One | Column MISSING in DB |
| `exams` | `exam_type_id` | `exam_types` | `id` | Many-to-One | Column MISSING in DB |
| `exams` | `academic_year_id` | `academic_years` | `id` | Many-to-One | Column MISSING in DB |
| `exams` | `school_term_id` | `school_terms` | `id` | Many-to-One | Column MISSING in DB |
| `exam_schedules` | `exam_id` | `exams` | `id` | Many-to-One | Column MISSING in DB |
| `exam_schedules` | `class_id` | `school_classes` | `id` | Many-to-One | Column MISSING in DB |
| `exam_schedules` | `section_id` | `sections` | `id` | Many-to-One | Column MISSING in DB |
| `exam_subjects` | `exam_schedule_id` | `exam_schedules` | `id` | Many-to-One | Column MISSING in DB |
| `exam_subjects` | `subject_id` | `subjects` | `id` | Many-to-One | Column MISSING in DB |
| `exam_results` | `exam_subject_id` | `exam_subjects` | `id` | Many-to-One | Column MISSING in DB |
| `exam_results` | `student_id` | `students` | `id` | Many-to-One | Column MISSING in DB |
| `student_grades` | `exam_id` | `exams` | `id` | Many-to-One | Column MISSING in DB |
| `student_grades` | `student_id` | `students` | `id` | Many-to-One | Column MISSING in DB |
| `assignments` | `class_id` | `school_classes` | `id` | Many-to-One | Column MISSING in DB |
| `assignments` | `section_id` | `sections` | `id` | Many-to-One | Column MISSING in DB |
| `assignments` | `subject_id` | `subjects` | `id` | Many-to-One | Column MISSING in DB |
| `assignments` | `teacher_id` | `users` | `id` | Many-to-One | Column MISSING in DB |
| `assignment_attachments` | `assignment_id` | `assignments` | `id` | Many-to-One | Column MISSING in DB |
| `assignment_submissions` | `assignment_id` | `assignments` | `id` | Many-to-One | Column MISSING in DB |
| `assignment_submissions` | `student_id` | `students` | `id` | Many-to-One | Column MISSING in DB |
| `assignment_grades` | `assignment_submission_id` | `assignment_submissions` | `id` | One-to-One | Column MISSING in DB |
| `student_attendances` | `attendance_session_id` | `attendance_sessions` | `id` | Many-to-One | Column MISSING in DB |
| `student_attendances` | `student_id` | `students` | `id` | Many-to-One | Column MISSING in DB |
| `student_attendances` | `attendance_status_id` | `attendance_statuses` | `id` | Many-to-One | Column MISSING in DB |
| `attendance_sessions` | `academic_year_id` | `academic_years` | `id` | Many-to-One | Column MISSING in DB |
| `attendance_sessions` | `class_id` | `school_classes` | `id` | Many-to-One | Column MISSING in DB |
| `attendance_sessions` | `section_id` | `sections` | `id` | Many-to-One | Column MISSING in DB |
| `attendance_corrections` | `student_attendance_id` | `student_attendances` | `id` | Many-to-One | Column MISSING in DB |
| `fee_structures` | `fee_category_id` | `fee_categories` | `id` | Many-to-One | Column MISSING in DB |
| `fee_structures` | `academic_year_id` | `academic_years` | `id` | Many-to-One | Column MISSING in DB |
| `fee_structures` | `class_id` | `school_classes` | `id` | Many-to-One | Column MISSING in DB |
| `student_invoices` | `student_id` | `students` | `id` | Many-to-One | Column MISSING in DB |
| `student_invoices` | `fee_structure_id` | `fee_structures` | `id` | Many-to-One | Column MISSING in DB |
| `invoice_details` | `student_invoice_id` | `student_invoices` | `id` | Many-to-One | Column MISSING in DB |
| `invoice_details` | `fee_category_id` | `fee_categories` | `id` | Many-to-One | Column MISSING in DB |
| `student_payments` | `student_invoice_id` | `student_invoices` | `id` | Many-to-One | Column MISSING in DB |
| `student_payments` | `payment_method_id` | `payment_methods` | `id` | Many-to-One | Column MISSING in DB |
| `school_expenses` | `expense_category_id` | `expense_categories` | `id` | Many-to-One | Column MISSING in DB |
| `library_books` | `library_category_id` | `library_categories` | `id` | Many-to-One | Column MISSING in DB |
| `library_book_copies` | `library_book_id` | `library_books` | `id` | Many-to-One | Column MISSING in DB |
| `library_issues` | `library_book_copy_id` | `library_book_copies` | `id` | Many-to-One | Column MISSING in DB |
| `library_issues` | `user_id` | `users` | `id` | Many-to-One | Column MISSING in DB |
| `library_returns` | `library_issue_id` | `library_issues` | `id` | One-to-One | Column MISSING in DB |

---

## F. Index Matrix

| Table | Index Name | Indexed Columns | Type | Purpose | Current DB Status |
|:---|:---|:---|:---|:---|:---|
| `users` | `users_email_unique` | `email` | UNIQUE | Prevent duplicate login emails | EXISTS |
| `students` | `students_admission_number_unique` | `admission_number` | UNIQUE | Institutional unique student identifier | EXISTS |
| `students` | `students_school_id_index` | `school_id` | INDEX | Rapid filtering by school branch | Recommended |
| `students` | `students_user_id_index` | `user_id` | INDEX | Rapid lookup from user account | Recommended |
| `students` | `students_student_status_id_index` | `student_status_id` | INDEX | Status filtering | Recommended |
| `student_profiles` | `student_profiles_student_id_index` | `student_id` | INDEX | Fast join from students | Recommended |
| `student_addresses` | `student_addresses_student_id_index` | `student_id` | INDEX | Fast join from students | Recommended |
| `student_documents` | `student_documents_student_id_index` | `student_id` | INDEX | Fast join from students | Recommended |
| `student_medical_records` | `student_medical_records_student_id_index` | `student_id` | INDEX | Fast join from students | Recommended |
| `student_guardians` | `student_guardians_student_id_index` | `student_id` | INDEX | Pivot index for student lookups | Recommended |
| `student_guardians` | `student_guardians_guardian_id_index` | `guardian_id` | INDEX | Pivot index for guardian lookups | Recommended |
| `student_guardians` | `student_guardians_unique` | `student_id, guardian_id` | UNIQUE | Prevent duplicate pivot attachments | Recommended |
| `guardians` | `guardians_user_id_index` | `user_id` | INDEX | Fast join to user | Recommended |
| `guardian_contacts` | `guardian_contacts_guardian_id_index` | `guardian_id` | INDEX | Fast join to guardian | Recommended |
| `student_notes` | `student_notes_student_id_index` | `student_id` | INDEX | Fast join from students | Recommended (Awaiting Review) |
| `roles` | `roles_slug_unique` | `slug` | UNIQUE | Fast role slug lookup | Planned |
| `permissions` | `permissions_slug_unique` | `slug` | UNIQUE | Fast permission slug lookup | Planned |
| `user_roles` | `user_roles_unique` | `user_id, role_id` | UNIQUE | Prevent duplicate role assignment | Planned |
| `role_permissions` | `role_permissions_unique` | `role_id, permission_id` | UNIQUE | Prevent duplicate permission assignment | Planned |
| `subjects` | `subjects_code_unique` | `code` | UNIQUE | Prevent duplicate course codes | Planned |
| `class_subjects` | `class_subjects_unique` | `class_id, subject_id` | UNIQUE | Prevent duplicate subject assignment to class | Planned |
| `employees` | `employees_employee_code_unique` | `employee_code` | UNIQUE | Institutional employee code | Planned |
| `employees` | `employees_user_id_index` | `user_id` | INDEX | Fast join to user | Planned |
| `library_books` | `library_books_isbn_unique` | `isbn` | UNIQUE | International Standard Book Number | Planned |
| `library_book_copies` | `library_book_copies_accession_number_unique` | `accession_number` | UNIQUE | Library accession code | Planned |

