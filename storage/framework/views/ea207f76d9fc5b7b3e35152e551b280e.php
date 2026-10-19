<aside id="app-sidebar" class="app-sidebar d-flex flex-column flex-shrink-0">
    
    <div class="sidebar-brand d-flex align-items-center justify-content-between px-3 py-3">
        <a href="<?php echo e(route('dashboard')); ?>" class="d-flex align-items-center gap-2 text-decoration-none brand-link">
            <?php
                $sidebarSchool = \App\Models\Core\School::first();
                $sidebarLogoRel = ($sidebarSchool && $sidebarSchool->logo_path && file_exists(public_path($sidebarSchool->logo_path)))
                    ? $sidebarSchool->logo_path
                    : (file_exists(public_path('images/school_logo.png')) ? 'images/school_logo.png' : 'images/logo.svg');
                $sidebarLogo = asset($sidebarLogoRel);
                $sidebarLogoVer = file_exists(public_path($sidebarLogoRel)) ? filemtime(public_path($sidebarLogoRel)) : time();
            ?>
            <img src="<?php echo e($sidebarLogo); ?>?v=<?php echo e($sidebarLogoVer); ?>" alt="School Logo" class="brand-logo-img">
            <div class="brand-text">
                <div class="brand-name font-khmer-moul">សាលារៀនប្រទីប<span class="text-gold">បញ្ញា</span></div>
                <div class="brand-tagline">ប្រព័ន្ធគ្រប់គ្រងសាលារៀន</div>
            </div>
        </a>
        <button type="button" class="btn btn-sm btn-link text-dark opacity-75 d-lg-none p-0" id="sidebarCloseBtn" aria-label="Close sidebar">
            <i class="bi bi-x-lg fs-5"></i>
        </button>
    </div>

    
    <div class="sidebar-nav-wrapper flex-grow-1 overflow-y-auto px-2 py-3">
        <ul class="nav flex-column gap-1 sidebar-nav-list" id="sidebarAccordion">

            
            <li class="nav-item">
                <a href="<?php echo e(route('dashboard')); ?>"
                   class="nav-link sidebar-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="ផ្ទាំងព័ត៌មាន">
                    <i class="bi bi-grid nav-icon"></i>
                    <span class="nav-text">ផ្ទាំងព័ត៌មាន</span>
                </a>
            </li>

            
            <?php if(auth()->user()?->canAccess(['students', 'academics', 'enrollments', 'attendance', 'assignments', 'exams'])): ?>
            <li class="nav-section-header">
                <span class="nav-section-title">កិច្ចការសិក្សា & សិស្ស</span>
            </li>

            
            <?php if(auth()->user()?->canAccess(['students', 'academics'])): ?>
            <?php
                $isAcademicActive = request()->routeIs('students.*')
                    || request()->routeIs('academics.*')
                    || request()->routeIs('classes.*')
                    || request()->routeIs('classrooms.*')
                    || request()->routeIs('grades.*')
                    || request()->routeIs('sections.*')
                    || request()->routeIs('class-subjects.*')
                    || request()->routeIs('teacher-assignments.*')
                    || request()->routeIs('subjects.*')
                    || request()->routeIs('timetables.*')
                    || request()->routeIs('academic-years.*')
                    || request()->is('students*')
                    || request()->is('academics*')
                    || request()->is('classes*')
                    || request()->is('classrooms*')
                    || request()->is('class-subjects*')
                    || request()->is('teacher-assignments*')
                    || request()->is('subjects*')
                    || request()->is('timetables*')
                    || request()->is('academic-years*');
            ?>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?php echo e($isAcademicActive ? '' : 'collapsed'); ?>"
                   data-bs-toggle="collapse" href="#menuAcademic" role="button"
                   aria-expanded="<?php echo e($isAcademicActive ? 'true' : 'false'); ?>" aria-controls="menuAcademic">
                    <i class="bi bi-mortarboard nav-icon"></i>
                    <span class="nav-text">ព័ត៌មានការសិក្សា</span>
                    <i class="bi bi-chevron-down nav-arrow ms-auto"></i>
                </a>
                <div class="collapse <?php echo e($isAcademicActive ? 'show' : ''); ?>" id="menuAcademic">
                    <ul class="nav flex-column sub-nav">
                        <?php if(auth()->user()?->canAccess('students')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('students.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('students.*') || request()->is('students*') ? 'active' : ''); ?>">
                                <i class="bi bi-people me-2"></i>បញ្ជីសិស្ស
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(auth()->user()?->canAccess('academics')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('academics.index')); ?>"
                               class="nav-link sub-link <?php echo e((request()->routeIs('academics.*') || request()->routeIs('classes.*') || request()->routeIs('class-subjects.*') || request()->routeIs('teacher-assignments.*') || request()->routeIs('subjects.*') || request()->routeIs('classrooms.*') || request()->routeIs('academic-years.*') || request()->is('academics*') || request()->is('classes*') || request()->is('classrooms*') || request()->is('academic-years*')) ? 'active' : ''); ?>">
                                <i class="bi bi-mortarboard me-2"></i><?php echo e(auth()->user()?->canAccess(['subjects.view', 'subjects']) ? 'ថ្នាក់ និងមុខវិជ្ជា' : 'ថ្នាក់រៀន'); ?>

                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(auth()->user()?->canAccess('timetables')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('timetables.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('timetables.*') || request()->is('timetables*') ? 'active' : ''); ?>">
                                <i class="bi bi-calendar-week me-2"></i>កាលវិភាគសិក្សា
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            
            <?php if(auth()->user()?->canAccess('enrollments')): ?>
            <?php
                $isEnrollmentActive = request()->routeIs('enrollments.*')
                    || request()->routeIs('promotions.*')
                    || request()->routeIs('transfers.*')
                    || request()->routeIs('withdrawals.*')
                    || request()->is('enrollments*')
                    || request()->is('promotions*')
                    || request()->is('transfers*')
                    || request()->is('withdrawals*')
                    || request()->is('management/promotions*')
                    || request()->is('management/transfers*')
                    || request()->is('management/withdrawals*');
            ?>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?php echo e($isEnrollmentActive ? '' : 'collapsed'); ?>"
                   data-bs-toggle="collapse" href="#menuEnrollment" role="button"
                   aria-expanded="<?php echo e($isEnrollmentActive ? 'true' : 'false'); ?>" aria-controls="menuEnrollment">
                    <i class="bi bi-person-plus nav-icon"></i>
                    <span class="nav-text">ការចុះឈ្មោះ & ផ្ទេរ</span>
                    <i class="bi bi-chevron-down nav-arrow ms-auto"></i>
                </a>
                <div class="collapse <?php echo e($isEnrollmentActive ? 'show' : ''); ?>" id="menuEnrollment">
                    <ul class="nav flex-column sub-nav">
                        <li class="nav-item">
                            <a href="<?php echo e(route('promotions.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('promotions.*') || request()->is('promotions*') || request()->is('management/promotions*') ? 'active' : ''); ?>">
                                <i class="bi bi-graph-up-arrow me-2"></i>ឡើងថ្នាក់
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('transfers.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('transfers.*') || request()->is('transfers*') || request()->is('management/transfers*') ? 'active' : ''); ?>">
                                <i class="bi bi-arrow-left-right me-2"></i>ផ្ទេរសាលា
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('withdrawals.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('withdrawals.*') || request()->is('withdrawals*') || request()->is('management/withdrawals*') ? 'active' : ''); ?>">
                                <i class="bi bi-person-dash me-2"></i>សិស្សឈប់រៀន
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            
            <?php if(auth()->user()?->canAccess(['attendance', 'assignments', 'students'])): ?>
            <?php
                $isServicesActive = request()->routeIs('attendance.*')
                    || request()->routeIs('student-attendance.*')
                    || request()->routeIs('attendance-sessions.*')
                    || request()->routeIs('assignments.*')
                    || request()->routeIs('submissions.*')
                    || request()->routeIs('student-notes.*')
                    || request()->is('attendance*')
                    || request()->is('student-attendance*')
                    || request()->is('attendance-sessions*')
                    || request()->is('assignments*')
                    || request()->is('submissions*')
                    || request()->is('student-notes*');
            ?>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?php echo e($isServicesActive ? '' : 'collapsed'); ?>"
                   data-bs-toggle="collapse" href="#menuServices" role="button"
                   aria-expanded="<?php echo e($isServicesActive ? 'true' : 'false'); ?>" aria-controls="menuServices">
                    <i class="bi bi-ui-checks nav-icon"></i>
                    <span class="nav-text">សេវាកម្មសិស្ស</span>
                    <i class="bi bi-chevron-down nav-arrow ms-auto"></i>
                </a>
                <div class="collapse <?php echo e($isServicesActive ? 'show' : ''); ?>" id="menuServices">
                    <ul class="nav flex-column sub-nav">
                        <?php if(auth()->user()?->canAccess('attendance')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('attendance.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('attendance.*') || request()->routeIs('attendance-sessions.*') || request()->routeIs('student-attendance.*') || request()->is('attendance*') ? 'active' : ''); ?>">
                                <i class="bi bi-calendar2-check me-2"></i>វត្តមានសិស្ស
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(auth()->user()?->canAccess('assignments')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('assignments.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('assignments.*') || request()->routeIs('submissions.*') || request()->is('assignments*') ? 'active' : ''); ?>">
                                <i class="bi bi-journal-text me-2"></i>កិច្ចការផ្ទះ
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(auth()->user()?->canAccess(['students', 'attendance'])): ?>
                        <li class="nav-item">
                             <a href="<?php echo e(route('student-notes.index')); ?>"
                                class="nav-link sub-link <?php echo e(request()->routeIs('student-notes.*') || request()->is('student-notes*') ? 'active' : ''); ?>">
                                 <i class="bi bi-journal-bookmark me-2"></i>កំណត់ត្រាសិស្ស
                             </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            
            <?php if(auth()->user()?->canAccess('exams')): ?>
            <?php
                $isExamActive = request()->routeIs('examinations.*')
                    || request()->routeIs('exams.*')
                    || request()->routeIs('exam-schedules.*')
                    || request()->routeIs('exam-subjects.*')
                    || request()->routeIs('exam-results.*')
                    || request()->routeIs('student-grades.*')
                    || request()->routeIs('exam-types.*')
                    || request()->routeIs('grade-scales.*')
                    || request()->is('examinations*')
                    || request()->is('exams*')
                    || request()->is('exam-*')
                    || request()->is('student-grades*')
                    || request()->is('grade-scales*');
            ?>
            <li class="nav-item">
                <a href="<?php echo e(route('examinations.index')); ?>"
                   class="nav-link sidebar-link <?php echo e($isExamActive ? 'active' : ''); ?>"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="ការប្រឡង">
                    <i class="bi bi-award nav-icon"></i>
                    <span class="nav-text">ការប្រឡង</span>
                </a>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            
            <?php if(auth()->user()?->canAccess(['employees', 'payroll', 'finance'])): ?>
            <li class="nav-section-header">
                <span class="nav-section-title">បុគ្គលិក & ប្រាក់ខែ</span>
            </li>

            
            <?php if(auth()->user()?->canAccess('employees')): ?>
            <?php
                $isStaffActive = request()->routeIs('employees.*')
                    || request()->routeIs('departments.*')
                    || request()->routeIs('positions.*')
                    || request()->routeIs('staff-attendance.*')
                    || request()->routeIs('staff-leaves.*')
                    || request()->routeIs('employee-contracts.*')
                    || request()->is('employees*')
                    || request()->is('departments*')
                    || request()->is('positions*')
                    || request()->is('staff-attendance*')
                    || request()->is('staff-leaves*')
                    || request()->is('employee-contracts*');
            ?>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?php echo e($isStaffActive ? '' : 'collapsed'); ?>"
                   data-bs-toggle="collapse" href="#menuStaff" role="button"
                   aria-expanded="<?php echo e($isStaffActive ? 'true' : 'false'); ?>" aria-controls="menuStaff">
                    <i class="bi bi-briefcase nav-icon"></i>
                    <span class="nav-text">ព័ត៌មានបុគ្គលិក</span>
                    <i class="bi bi-chevron-down nav-arrow ms-auto"></i>
                </a>
                <div class="collapse <?php echo e($isStaffActive ? 'show' : ''); ?>" id="menuStaff">
                    <ul class="nav flex-column sub-nav">
                        <li class="nav-item">
                            <a href="<?php echo e(route('employees.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('employees.*') || request()->is('employees*') ? 'active' : ''); ?>">
                                <i class="bi bi-person-badge me-2"></i>បញ្ជីបុគ្គលិក
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('departments.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('departments.*') || request()->routeIs('positions.*') || request()->is('departments*') || request()->is('positions*') ? 'active' : ''); ?>">
                                <i class="bi bi-diagram-3 me-2"></i>រចនាសម្ព័ន្ធ / តួនាទី
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('staff-attendance.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('staff-attendance.*') || request()->is('staff-attendance*') ? 'active' : ''); ?>">
                                <i class="bi bi-clock-history me-2"></i>វត្តមានបុគ្គលិក
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('staff-leaves.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('staff-leaves.*') || request()->is('staff-leaves*') ? 'active' : ''); ?>">
                                <i class="bi bi-calendar-x me-2"></i>ច្បាប់ឈប់សម្រាក
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('employee-contracts.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('employee-contracts.*') || request()->is('employee-contracts*') ? 'active' : ''); ?>">
                                <i class="bi bi-file-earmark-text me-2"></i>កិច្ចសន្យាការងារ
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            
            <?php if(auth()->user()?->canAccess(['payroll', 'finance'])): ?>
            <?php
                $isPayrollActive = request()->routeIs('salary-structures.*')
                    || request()->routeIs('salary-components.*')
                    || request()->routeIs('payrolls.*')
                    || request()->is('salary-structures*')
                    || request()->is('salary-components*')
                    || request()->is('payrolls*');
            ?>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?php echo e($isPayrollActive ? '' : 'collapsed'); ?>"
                   data-bs-toggle="collapse" href="#menuPayroll" role="button"
                   aria-expanded="<?php echo e($isPayrollActive ? 'true' : 'false'); ?>" aria-controls="menuPayroll">
                    <i class="bi bi-currency-dollar nav-icon"></i>
                    <span class="nav-text">បើកប្រាក់ខែ</span>
                    <i class="bi bi-chevron-down nav-arrow ms-auto"></i>
                </a>
                <div class="collapse <?php echo e($isPayrollActive ? 'show' : ''); ?>" id="menuPayroll">
                    <ul class="nav flex-column sub-nav">
                        <li class="nav-item">
                            <a href="<?php echo e(route('payrolls.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('payrolls.*') || request()->is('payrolls*') ? 'active' : ''); ?>">
                                <i class="bi bi-cash-coin me-2"></i>តារាងបើកប្រាក់ខែ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('salary-structures.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('salary-structures.*') || request()->is('salary-structures*') ? 'active' : ''); ?>">
                                <i class="bi bi-layers me-2"></i>រចនាសម្ព័ន្ធប្រាក់ខែ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('salary-components.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('salary-components.*') || request()->is('salary-components*') ? 'active' : ''); ?>">
                                <i class="bi bi-list-check me-2"></i>ធាតុប្រាក់ខែ
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            
            <?php if(auth()->user()?->canAccess(['finance', 'library'])): ?>
            <li class="nav-section-header">
                <span class="nav-section-title">ហិរញ្ញវត្ថុ & ធនធាន</span>
            </li>

            
            <?php if(auth()->user()?->canAccess('finance')): ?>
            <?php
                $isFinanceActive = request()->is('finance*')
                    || request()->is('fee-categories*')
                    || request()->is('fee-structures*')
                    || request()->is('student-invoices*')
                    || request()->is('student-payments*')
                    || request()->is('payment-methods*')
                    || request()->is('expense-categories*')
                    || request()->is('school-expenses*')
                    || request()->routeIs('finance.*')
                    || request()->routeIs('fee-categories.*')
                    || request()->routeIs('fee-structures.*')
                    || request()->routeIs('student-invoices.*')
                    || request()->routeIs('student-payments.*')
                    || request()->routeIs('payment-methods.*')
                    || request()->routeIs('expense-categories.*')
                    || request()->routeIs('school-expenses.*');
            ?>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?php echo e($isFinanceActive ? '' : 'collapsed'); ?>"
                   data-bs-toggle="collapse" href="#menuFinance" role="button"
                   aria-expanded="<?php echo e($isFinanceActive ? 'true' : 'false'); ?>" aria-controls="menuFinance">
                    <i class="bi bi-cash-stack nav-icon"></i>
                    <span class="nav-text">ហិរញ្ញវត្ថុ</span>
                    <i class="bi bi-chevron-down nav-arrow ms-auto"></i>
                </a>
                <div class="collapse <?php echo e($isFinanceActive ? 'show' : ''); ?>" id="menuFinance">
                    <ul class="nav flex-column sub-nav">
                        <li class="nav-item">
                            <a href="<?php echo e(route('finance.dashboard')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('finance.dashboard') ? 'active' : ''); ?>">
                                <i class="bi bi-speedometer2 me-2"></i>ផ្ទាំងហិរញ្ញវត្ថុ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('fee-categories.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->is('fee-categories*') ? 'active' : ''); ?>">
                                <i class="bi bi-tags me-2"></i>ប្រភេទកម្រៃសិក្សា
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('fee-structures.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->is('fee-structures*') ? 'active' : ''); ?>">
                                <i class="bi bi-calculator me-2"></i>រចនាសម្ព័ន្ធកម្រៃ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('student-invoices.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->is('student-invoices*') ? 'active' : ''); ?>">
                                <i class="bi bi-receipt me-2"></i>វិក្កយបត្រ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('student-payments.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->is('student-payments*') ? 'active' : ''); ?>">
                                <i class="bi bi-credit-card me-2"></i>ការបង់ប្រាក់
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('payment-methods.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->is('payment-methods*') ? 'active' : ''); ?>">
                                <i class="bi bi-wallet me-2"></i>វិធីសាស្ត្រទូទាត់
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('expense-categories.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->is('expense-categories*') ? 'active' : ''); ?>">
                                <i class="bi bi-folder me-2"></i>ប្រភេទចំណាយ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('school-expenses.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->is('school-expenses*') ? 'active' : ''); ?>">
                                <i class="bi bi-wallet2 me-2"></i>ចំណាយទូទៅ
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            
            <?php if(auth()->user()?->canAccess('library')): ?>
            <?php
                $isLibraryActive = request()->is('library*') || request()->routeIs('library.*');
            ?>
            <li class="nav-item">
                <a class="nav-link sidebar-link <?php echo e($isLibraryActive ? '' : 'collapsed'); ?>"
                   data-bs-toggle="collapse" href="#menuLibrary" role="button"
                   aria-expanded="<?php echo e($isLibraryActive ? 'true' : 'false'); ?>" aria-controls="menuLibrary">
                    <i class="bi bi-book-half nav-icon"></i>
                    <span class="nav-text">បណ្ណាល័យ</span>
                    <i class="bi bi-chevron-down nav-arrow ms-auto"></i>
                </a>
                <div class="collapse <?php echo e($isLibraryActive ? 'show' : ''); ?>" id="menuLibrary">
                    <ul class="nav flex-column sub-nav">
                        <li class="nav-item">
                            <a href="<?php echo e(route('library.dashboard')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('library.dashboard') ? 'active' : ''); ?>">
                                <i class="bi bi-speedometer2 me-2"></i>ផ្ទាំងបណ្ណាល័យ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('library.books.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('library.books*') || request()->is('library/books*') ? 'active' : ''); ?>">
                                <i class="bi bi-journal-bookmark me-2"></i>បញ្ជីសៀវភៅ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('library.categories.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('library.categories*') || request()->is('library/categories*') ? 'active' : ''); ?>">
                                <i class="bi bi-collection me-2"></i>ប្រភេទសៀវភៅ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('library.issues.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('library.issues*') || request()->is('library/issues*') || request()->routeIs('library.returns*') || request()->is('library/returns*') || request()->routeIs('library.borrowers*') || request()->is('library/borrowers*') ? 'active' : ''); ?>">
                                <i class="bi bi-arrow-left-right me-2"></i>ការខ្ចី-សងសៀវភៅ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('library.reports.index')); ?>"
                               class="nav-link sub-link <?php echo e(request()->routeIs('library.reports*') || request()->is('library/reports*') ? 'active' : ''); ?>">
                                <i class="bi bi-bar-chart-line me-2"></i>របាយការណ៍បណ្ណាល័យ
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            
            <?php if(auth()->user()?->canAccess(['settings', 'roles'])): ?>
            <?php
                $isSystemActive = request()->routeIs('settings.*') || request()->is('settings*') || request()->is('management/users*') || request()->is('management/roles*') || request()->is('management/settings*');
            ?>
            <li class="nav-section-header">
                <span class="nav-section-title">ប្រព័ន្ធគ្រប់គ្រង</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('settings.index')); ?>"
                   class="nav-link sidebar-link <?php echo e($isSystemActive ? 'active' : ''); ?>"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="ការកំណត់ប្រព័ន្ធ">
                    <i class="bi bi-gear nav-icon"></i>
                    <span class="nav-text">ការកំណត់ប្រព័ន្ធ</span>
                </a>
            </li>
            <?php endif; ?>

        </ul>
    </div>

    
    <script>
        (function() {
            try {
                var wrapper = document.querySelector('.sidebar-nav-wrapper');
                if (wrapper) {
                    try {
                        var openMenus = JSON.parse(sessionStorage.getItem('school_sidebar_open_menus') || '[]');
                        openMenus.forEach(function(id) {
                            var el = document.getElementById(id);
                            if (el && !el.classList.contains('show')) {
                                el.classList.add('show');
                            }
                        });
                    } catch(e) {}

                    var saved = sessionStorage.getItem('school_sidebar_scroll');
                    if (saved !== null) {
                        wrapper.scrollTop = parseInt(saved, 10);
                    }
                }
            } catch(e) {}
        })();
    </script>

    
    <?php
        $sidebarUser = auth()->user();
        $sidebarPhoto = $sidebarUser?->photo_url ?? $sidebarUser?->employee?->photo_url ?? $sidebarUser?->student?->photo_url;
        $sidebarUserName = $sidebarUser?->name ?? 'អ្នកប្រើប្រាស់';
        $nameWords = preg_split('/\s+/u', trim($sidebarUserName));
        $firstChar = mb_substr(trim($sidebarUserName), 0, 1, 'UTF-8');
        $isEnglishName = preg_match('/^[a-zA-Z]/', $firstChar);
        if ($isEnglishName) {
            if (count($nameWords) > 1) {
                $userInitials = mb_strtoupper(mb_substr($nameWords[0], 0, 1, 'UTF-8') . mb_substr(end($nameWords), 0, 1, 'UTF-8'));
            } else {
                $userInitials = mb_strtoupper(mb_substr($sidebarUserName, 0, 2, 'UTF-8'));
            }
        } else {
            $userInitials = null;
        }
        $primaryRole = $sidebarUser?->roles?->first();
        $roleDisplayName = $primaryRole ? ($primaryRole->display_name ?? $primaryRole->name ?? $primaryRole->slug) : 'អ្នកប្រើប្រាស់';
    ?>
    <div class="sidebar-user-footer p-3 border-top border-black border-opacity-10 mt-auto">
        <div class="d-flex align-items-center gap-2">
            <div class="user-avatar-sm position-relative overflow-hidden">
                <?php if($sidebarPhoto): ?>
                    <img src="<?php echo e($sidebarPhoto); ?>" alt="<?php echo e($sidebarUserName); ?>" class="w-100 h-100 object-fit-cover rounded-circle" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                    <span class="d-none">
                        <?php if($userInitials): ?>
                            <?php echo e($userInitials); ?>

                        <?php else: ?>
                            <i class="bi bi-person-fill" style="font-size: 1rem;"></i>
                        <?php endif; ?>
                    </span>
                <?php elseif($userInitials): ?>
                    <span><?php echo e($userInitials); ?></span>
                <?php else: ?>
                    <i class="bi bi-person-fill" style="font-size: 1rem;"></i>
                <?php endif; ?>
            </div>
            <div class="user-info-text min-w-0">
                <div class="user-name text-truncate text-dark fw-semibold"><?php echo e($sidebarUser->name ?? 'អ្នកប្រើប្រាស់'); ?></div>
                <div class="user-role text-truncate"><?php echo e($roleDisplayName); ?></div>
            </div>
            <span class="status-indicator-dot ms-auto" title="Online"></span>
        </div>
    </div>
</aside>


<div class="sidebar-backdrop d-lg-none" id="sidebarBackdrop"></div>
<?php /**PATH D:\School\laravel\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>