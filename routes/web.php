<?php

use App\Http\Controllers\Academics\AcademicsController;
use App\Http\Controllers\Academics\AcademicYearController;
use App\Http\Controllers\Academics\ClassroomController;
use App\Http\Controllers\Academics\ClassSubjectController;
use App\Http\Controllers\Academics\GradeController;
use App\Http\Controllers\Academics\SchoolClassController;
use App\Http\Controllers\Academics\SectionController;
use App\Http\Controllers\Academics\SubjectController;
use App\Http\Controllers\Academics\TeacherAssignmentController;
use App\Http\Controllers\Academics\TimetableController;
use App\Http\Controllers\Assignments\AssignmentController;
use App\Http\Controllers\Assignments\AssignmentGradeController;
use App\Http\Controllers\Assignments\AssignmentSubmissionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceSessionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\BulkData\BulkDataController;
use App\Http\Controllers\CambodiaLocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employees\EmployeeAttendanceController;
use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\Employees\EmployeeDepartmentController;
use App\Http\Controllers\Employees\EmployeeLeaveController;
use App\Http\Controllers\Employees\EmployeePositionController;
use App\Http\Controllers\Enrollment\EnrollmentController;
use App\Http\Controllers\Enrollment\PromotionController;
use App\Http\Controllers\Enrollment\TransferController;
use App\Http\Controllers\Enrollment\WithdrawalController;
use App\Http\Controllers\Examination\ExamController;
use App\Http\Controllers\Examination\ExaminationsController;
use App\Http\Controllers\Examination\ExamResultController;
use App\Http\Controllers\Examination\ExamScheduleController;
use App\Http\Controllers\Examination\ExamSubjectController;
use App\Http\Controllers\Examination\ExamTypeController;
use App\Http\Controllers\Examination\GradeScaleController;
use App\Http\Controllers\Examination\StudentGradeController;
use App\Http\Controllers\Finance\ExpenseCategoryController;
use App\Http\Controllers\Finance\FeeCategoryController;
use App\Http\Controllers\Finance\FeeStructureController;
use App\Http\Controllers\Finance\FinanceDashboardController;
use App\Http\Controllers\Finance\PaymentMethodController;
use App\Http\Controllers\Finance\SchoolExpenseController;
use App\Http\Controllers\Finance\StudentBalanceController;
use App\Http\Controllers\Finance\StudentInvoiceController;
use App\Http\Controllers\Finance\StudentPaymentController;
use App\Http\Controllers\Library\LibraryBookController;
use App\Http\Controllers\Library\LibraryBookCopyController;
use App\Http\Controllers\Library\LibraryBorrowerController;
use App\Http\Controllers\Library\LibraryCategoryController;
use App\Http\Controllers\Library\LibraryDashboardController;
use App\Http\Controllers\Library\LibraryIssueController;
use App\Http\Controllers\Library\LibraryReportController;
use App\Http\Controllers\Library\LibraryReturnController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\Payroll\EmployeeContractController;
use App\Http\Controllers\Payroll\PayrollController;
use App\Http\Controllers\Payroll\SalaryComponentController;
use App\Http\Controllers\Payroll\SalaryStructureController;
use App\Http\Controllers\Printable\DocumentPrintController;
use App\Http\Controllers\Settings\BackupController;
use App\Http\Controllers\Settings\SystemSettingsController;
use App\Http\Controllers\Settings\TelegramController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentNoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─────────────────────────────────────────────────
    //  User Profile & Password Management
    // ─────────────────────────────────────────────────
    Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // ─────────────────────────────────────────────────
    //  Cambodia Administrative Locations (Cascading API)
    // ─────────────────────────────────────────────────
    Route::get('locations/cambodia/districts', [CambodiaLocationController::class, 'getDistricts'])->name('locations.cambodia.districts');
    Route::get('locations/cambodia/communes', [CambodiaLocationController::class, 'getCommunes'])->name('locations.cambodia.communes');
    Route::get('locations/cambodia/villages', [CambodiaLocationController::class, 'getVillages'])->name('locations.cambodia.villages');

    // ─────────────────────────────────────────────────
    //  Student Management — dedicated resource routes
    // ─────────────────────────────────────────────────
    Route::post('students/check-duplicate', [StudentController::class, 'checkDuplicate'])->name('students.check-duplicate');
    Route::patch('students/{student}/status', [StudentController::class, 'updateStatus'])->name('students.update-status');
    Route::resource('students', StudentController::class);
    Route::post('students/{student}/guardians', [StudentController::class, 'storeGuardian'])->name('students.guardians.store');
    Route::delete('students/{student}/guardians/{guardian}', [StudentController::class, 'removeGuardian'])->name('students.guardians.destroy');
    Route::post('students/{student}/documents', [StudentController::class, 'storeDocument'])->name('students.documents.store');
    Route::delete('students/{student}/documents/{document}', [StudentController::class, 'destroyDocument'])->name('students.documents.destroy');

    // ─────────────────────────────────────────────────
    //  Student Notes — dedicated resource routes
    // ─────────────────────────────────────────────────
    Route::resource('student-notes', StudentNoteController::class);

    Route::resource('student-attendance', StudentAttendanceController::class);

    // ─────────────────────────────────────────────────
    //  Attendance Sessions
    // ─────────────────────────────────────────────────
    Route::resource('attendance-sessions', AttendanceSessionController::class);
    Route::get('attendance-sessions/{id}/take-attendance', [AttendanceSessionController::class, 'takeAttendance'])->name('attendance-sessions.take-attendance');
    Route::post('attendance-sessions/{id}/take-attendance', [AttendanceSessionController::class, 'saveAttendance'])->name('attendance-sessions.save-attendance');
    Route::patch('attendance-sessions/{id}/close', [AttendanceSessionController::class, 'closeSession'])->name('attendance-sessions.close');
    Route::patch('attendance-sessions/{id}/reopen', [AttendanceSessionController::class, 'reopenSession'])->name('attendance-sessions.reopen');

    // ─────────────────────────────────────────────────
    //  Attendance Register (unified form — NEW)
    // ─────────────────────────────────────────────────
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/save', [AttendanceController::class, 'save'])->name('attendance.save');
    Route::get('attendance/monthly-register', [AttendanceController::class, 'monthlyRegister'])->name('attendance.monthly-register');
    Route::get('api/attendance/teacher-assignments', [AttendanceController::class, 'getTeacherAssignments'])->name('api.attendance.assignments');

    // ─────────────────────────────────────────────────
    //  AJAX — Teacher Assignments (legacy, kept for compat)
    // ─────────────────────────────────────────────────
    Route::get('api/class-teacher-assignments', [AttendanceSessionController::class, 'getClassAssignments'])->name('api.class-assignments');

    // ─────────────────────────────────────────────────
    //  Academics — unified management page
    // ─────────────────────────────────────────────────
    Route::get('academics', [AcademicsController::class, 'index'])->name('academics.index');

    // ─────────────────────────────────────────────────
    //  Academics — Classes, Subjects, Timetables
    //  (kept for backward-compat & create/edit/store forms)
    // ─────────────────────────────────────────────────
    Route::resource('classes', SchoolClassController::class);
    Route::resource('grades', GradeController::class)->only(['store', 'update', 'destroy']);
    Route::resource('sections', SectionController::class)->only(['store', 'update', 'destroy']);
    Route::post('class-subjects/sync', [ClassSubjectController::class, 'sync'])->name('class-subjects.sync');
    Route::resource('class-subjects', ClassSubjectController::class);
    Route::resource('teacher-assignments', TeacherAssignmentController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('academic-years', AcademicYearController::class);
    Route::post('academic-years/{academic_year}/toggle-active', [AcademicYearController::class, 'toggleActive'])->name('academic-years.toggle-active');
    Route::resource('timetables', TimetableController::class);
    Route::post('timetables/{timetable}/entries', [TimetableController::class, 'storeEntry'])->name('timetables.entries.store');
    Route::delete('timetables/{timetable}/entries/{entry}', [TimetableController::class, 'destroyEntry'])->name('timetables.entries.destroy');

    // ─────────────────────────────────────────────────
    //  Enrollment
    // ─────────────────────────────────────────────────
    Route::resource('enrollments', EnrollmentController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('transfers', TransferController::class);
    Route::resource('withdrawals', WithdrawalController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', EmployeeDepartmentController::class);
    Route::resource('positions', EmployeePositionController::class);
    Route::post('staff-attendance/scan-qr', [EmployeeAttendanceController::class, 'scanQr'])->name('staff-attendance.scan-qr');
    Route::post('staff-attendance/verify-card', [EmployeeAttendanceController::class, 'verifyCard'])->name('staff-attendance.verify-card');
    Route::post('staff-attendance/record-verified', [EmployeeAttendanceController::class, 'recordVerifiedAttendance'])->name('staff-attendance.record-verified');
    Route::post('staff-attendance/cards/{employee}/generate', [EmployeeAttendanceController::class, 'generateCard'])->name('staff-attendance.cards.generate');
    Route::post('staff-attendance/cards/{card}/toggle-status', [EmployeeAttendanceController::class, 'toggleCardStatus'])->name('staff-attendance.cards.toggle');
    Route::get('staff-attendance/scanner', [EmployeeAttendanceController::class, 'scanner'])->name('staff-attendance.scanner');
    Route::get('staff-attendance/employee/{employee}', [EmployeeAttendanceController::class, 'employeeDetail'])->name('staff-attendance.employee');
    Route::post('staff-attendance/shift-settings', [EmployeeAttendanceController::class, 'updateShiftSettings'])->name('staff-attendance.shift-settings.update');
    Route::resource('staff-attendance', EmployeeAttendanceController::class);
    Route::resource('staff-leaves', EmployeeLeaveController::class)->parameters(['staff-leaves' => 'staff_leaf']);

    // ─────────────────────────────────────────────────
    //  Payroll & Employee Contracts
    // ─────────────────────────────────────────────────
    Route::resource('salary-structures', SalaryStructureController::class);
    Route::resource('salary-components', SalaryComponentController::class);
    Route::resource('employee-contracts', EmployeeContractController::class);
    Route::get('employee-contracts/{employee_contract}/print', [EmployeeContractController::class, 'print'])->name('employee-contracts.print');
    Route::post('employee-contracts/{employee_contract}/renew', [EmployeeContractController::class, 'renew'])->name('employee-contracts.renew');

    Route::resource('payrolls', PayrollController::class);
    Route::post('payrolls/{payroll}/submit', [PayrollController::class, 'submit'])->name('payrolls.submit');
    Route::post('payrolls/{payroll}/approve', [PayrollController::class, 'approve'])->name('payrolls.approve');
    Route::post('payrolls/{payroll}/pay', [PayrollController::class, 'pay'])->name('payrolls.pay');
    Route::post('payrolls/{payroll}/cancel', [PayrollController::class, 'cancel'])->name('payrolls.cancel');
    Route::get('payrolls/{payroll}/payslip', [PayrollController::class, 'payslip'])->name('payrolls.payslip');

    // ─────────────────────────────────────────────────
    //  Unified System Settings
    // ─────────────────────────────────────────────────
    Route::get('settings', [SystemSettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/users', [SystemSettingsController::class, 'storeUser'])->name('settings.users.store');
    Route::put('settings/users/{user}', [SystemSettingsController::class, 'updateUser'])->name('settings.users.update');
    Route::patch('settings/users/{user}/toggle-status', [SystemSettingsController::class, 'toggleUserStatus'])->name('settings.users.toggle-status');
    Route::post('settings/users/{user}/reset-password', [SystemSettingsController::class, 'resetUserPassword'])->name('settings.users.reset-password');
    Route::delete('settings/users/{user}', [SystemSettingsController::class, 'destroyUser'])->name('settings.users.destroy');

    Route::post('settings/roles', [SystemSettingsController::class, 'storeRole'])->name('settings.roles.store');
    Route::put('settings/roles/{role}', [SystemSettingsController::class, 'updateRole'])->name('settings.roles.update');
    Route::post('settings/roles/{role}/permissions', [SystemSettingsController::class, 'syncRolePermissions'])->name('settings.roles.permissions');
    Route::delete('settings/roles/{role}', [SystemSettingsController::class, 'destroyRole'])->name('settings.roles.destroy');

    Route::put('settings/school-profile', [SystemSettingsController::class, 'updateSchoolProfile'])->name('settings.school-profile.update');
    Route::post('settings/system', [SystemSettingsController::class, 'updateSystemSettings'])->name('settings.system.update');

    // ─────────────────────────────────────────────────
    //  Dynamic Management Controller (legacy compatibility fallback)
    // ─────────────────────────────────────────────────
    Route::prefix('management/{entity}')->group(function () {
        Route::get('/', [ManagementController::class, 'index'])->name('management.index');
        Route::get('/create', [ManagementController::class, 'create'])->name('management.create');
        Route::post('/', [ManagementController::class, 'store'])->name('management.store');
        Route::get('/{id}', [ManagementController::class, 'show'])->name('management.show');
        Route::get('/{id}/edit', [ManagementController::class, 'edit'])->name('management.edit');
        Route::put('/{id}', [ManagementController::class, 'update'])->name('management.update');
        Route::delete('/{id}', [ManagementController::class, 'destroy'])->name('management.destroy');
    });

    // ─────────────────────────────────────────────────
    //  Assignments
    // ─────────────────────────────────────────────────
    Route::resource('assignments', AssignmentController::class);
    Route::resource('assignments.submissions', AssignmentSubmissionController::class)->shallow();
    Route::resource('submissions.grades', AssignmentGradeController::class)->shallow();

    // ─────────────────────────────────────────────────
    //  Examinations & Grading
    // ─────────────────────────────────────────────────
    Route::get('examinations', [ExaminationsController::class, 'index'])->name('examinations.index');
    Route::resource('exam-types', ExamTypeController::class);
    Route::resource('exams', ExamController::class);
    Route::post('exams/{exam}/calculate-grades', [StudentGradeController::class, 'recalculateAll'])->name('exams.calculate-grades');
    Route::resource('exam-schedules', ExamScheduleController::class);
    Route::resource('exam-subjects', ExamSubjectController::class);
    Route::get('exam-subjects/{exam_subject}/scores', [ExamResultController::class, 'scoreEntry'])->name('exam-subjects.scores');
    Route::post('exam-subjects/{exam_subject}/scores', [ExamResultController::class, 'storeScores'])->name('exam-subjects.scores.store');
    Route::resource('grade-scales', GradeScaleController::class);
    Route::resource('exam-results', ExamResultController::class);
    Route::resource('student-grades', StudentGradeController::class)->only(['index', 'show']);

    // ─────────────────────────────────────────────────
    //  Finance Management
    // ─────────────────────────────────────────────────
    Route::get('finance', [FinanceDashboardController::class, 'index'])->name('finance.dashboard');
    Route::match(['GET', 'POST'], 'finance/cleanup-test-data', [FinanceDashboardController::class, 'cleanupTestData'])->name('finance.cleanup-test-data');
    Route::get('finance/student-balances', [StudentBalanceController::class, 'index'])->name('finance.student-balances');
    Route::resource('fee-categories', FeeCategoryController::class);
    Route::resource('fee-structures', FeeStructureController::class);
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::patch('student-invoices/{student_invoice}/cancel', [StudentInvoiceController::class, 'cancel'])->name('student-invoices.cancel');
    Route::get('student-invoices/{student_invoice}/receipt', [StudentInvoiceController::class, 'receipt'])->name('student-invoices.receipt');
    Route::resource('student-invoices', StudentInvoiceController::class);
    Route::get('students/{student}/unpaid-invoices', [StudentPaymentController::class, 'getUnpaidInvoices'])->name('students.unpaid-invoices');
    Route::resource('student-payments', StudentPaymentController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::get('student-payments/{student_payment}/receipt', [StudentPaymentController::class, 'receipt'])->whereNumber('student_payment')->name('student-payments.receipt');
    Route::get('student-payments/{student_payment}', [StudentPaymentController::class, 'receipt'])->whereNumber('student_payment')->name('student-payments.show');
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::resource('school-expenses', SchoolExpenseController::class);

    // ─────────────────────────────────────────────────
    //  Library Management
    // ─────────────────────────────────────────────────
    Route::get('library', [LibraryDashboardController::class, 'index'])->name('library.dashboard');
    Route::get('library/dashboard', [LibraryDashboardController::class, 'index']);
    Route::resource('library/categories', LibraryCategoryController::class)->names('library.categories')->except(['show']);
    Route::resource('library/books', LibraryBookController::class)->names('library.books');
    Route::post('library/books/{book}/copies', [LibraryBookCopyController::class, 'store'])->name('library.books.copies.store');
    Route::put('library/copies/{copy}', [LibraryBookCopyController::class, 'update'])->name('library.copies.update');
    Route::delete('library/copies/{copy}', [LibraryBookCopyController::class, 'destroy'])->name('library.copies.destroy');
    Route::resource('library/issues', LibraryIssueController::class)->names('library.issues')->only(['index', 'create', 'store', 'show']);
    Route::get('library/returns', [LibraryReturnController::class, 'index'])->name('library.returns.index');
    Route::get('library/issues/{issue}/return', [LibraryReturnController::class, 'create'])->name('library.returns.create');
    Route::post('library/returns', [LibraryReturnController::class, 'store'])->name('library.returns.store');
    Route::get('library/borrowers', [LibraryBorrowerController::class, 'index'])->name('library.borrowers.index');
    Route::get('library/borrowers/{user}', [LibraryBorrowerController::class, 'show'])->name('library.borrowers.show');
    Route::get('library/reports', [LibraryReportController::class, 'index'])->name('library.reports.index');

    // ─────────────────────────────────────────────────
    //  Printable Documents Suite
    // ─────────────────────────────────────────────────
    Route::get('examinations/{exam}/report-card/{student}', [DocumentPrintController::class, 'studentReportCard'])->name('examinations.report-card');
    Route::get('examinations/{exam}/class-report-cards/{school_class}', [DocumentPrintController::class, 'classReportCards'])->name('examinations.class-report-cards');
    Route::get('students/{student}/id-card', [DocumentPrintController::class, 'studentIdCard'])->name('students.id-card');
    Route::get('classes/{school_class}/id-cards', [DocumentPrintController::class, 'classIdCards'])->name('classes.id-cards');
    Route::get('students/{student}/certificate', [DocumentPrintController::class, 'studyCertificate'])->name('students.certificate');
    Route::get('employees/{employee}/id-card', [DocumentPrintController::class, 'employeeIdCard'])->name('employees.id-card');

    // ─────────────────────────────────────────────────
    //  Bulk Data Import & Export Hub
    // ─────────────────────────────────────────────────
    Route::get('bulk-data/students/sample', [BulkDataController::class, 'downloadStudentSample'])->name('students.import-sample');
    Route::post('bulk-data/students/import', [BulkDataController::class, 'importStudents'])->name('students.import');
    Route::get('bulk-data/students/export', [BulkDataController::class, 'exportStudents'])->name('students.export');
    Route::get('bulk-data/examinations/{exam}/export-grades', [BulkDataController::class, 'exportExamGrades'])->name('examinations.export-grades');

    // ─────────────────────────────────────────────────
    //  Telegram Bot Integration & Notifications
    // ─────────────────────────────────────────────────
    Route::post('settings/telegram/test', [TelegramController::class, 'test'])->name('settings.telegram.test');
    Route::post('settings/telegram/broadcast', [TelegramController::class, 'broadcast'])->name('settings.telegram.broadcast');

    // ─────────────────────────────────────────────────
    //  1-Click Database Backup & System Audit
    // ─────────────────────────────────────────────────
    Route::post('settings/backup', [BackupController::class, 'store'])->name('settings.backup.store');
    Route::get('settings/backup/download/{filename}', [BackupController::class, 'download'])->name('settings.backup.download');
    Route::delete('settings/backup/{filename}', [BackupController::class, 'destroy'])->name('settings.backup.destroy');
});
