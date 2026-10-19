<?php $__env->startSection('title', $isAdmin ? 'ផ្ទាំងគ្រប់គ្រងសាលារៀន — School Management Dashboard' : 'ផ្ទាំងគ្រូបង្រៀន — Teacher Dashboard'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">ទំព័រដើម</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">
            <?php echo e($isAdmin ? 'ផ្ទាំងគ្រប់គ្រងសាលារៀន (Dashboard)' : 'ផ្ទាំងគ្រូបង្រៀន (Teacher Dashboard)'); ?>

        </li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ═══════════════════════════════════════════════════════════════
       DASHBOARD DESIGN SYSTEM STYLES (COMPACT & KHMER LOCALIZED)
       ═══════════════════════════════════════════════════════════════ */

    .dash-header-bar {
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(6px);
        padding: 0.45rem 0.85rem;
        border-radius: 0px !important;
        border: 1px solid var(--border, #e2e8f0);
        box-shadow: var(--shadow-xs, 0 1px 2px rgba(0,0,0,0.04));
    }

    /* ═══════════════════════════════════════════════════════════════
       DASHBOARD DESIGN SYSTEM STYLES (COMPACT & KHMER LOCALIZED)
       ═══════════════════════════════════════════════════════════════ */
    .dash-kpi-card {
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(4px);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 0px !important;
        padding: 0.38rem 0.65rem 0.32rem;
        box-shadow: var(--shadow-xs, 0 1px 2px rgba(0,0,0,0.04));
        transition: transform var(--transition-base, 0.2s ease), box-shadow var(--transition-base, 0.2s ease);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .dash-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md, 0 4px 12px rgba(0,0,0,0.08));
    }
    .dash-kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: transparent;
        transition: background 0.2s ease;
    }
    .dash-kpi-card.kpi-blue:hover::before { background: #3b82f6; }
    .dash-kpi-card.kpi-emerald:hover::before { background: #10b981; }
    .dash-kpi-card.kpi-amber:hover::before { background: #f59e0b; }
    .dash-kpi-card.kpi-purple:hover::before { background: #8b5cf6; }

    .dash-icon-box {
        width: 28px;
        height: 28px;
        border-radius: 0px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }
    .dash-kpi-card:hover .dash-icon-box {
        transform: scale(1.05);
    }
    .dash-stat-value {
        font-family: var(--font-heading, 'Siemreap', sans-serif);
        font-size: 1.45rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1.1;
        color: var(--text-dark, #0f172a);
    }

    .dash-card {
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(4px);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: var(--radius-md, 10px);
        box-shadow: var(--shadow-xs, 0 1px 2px rgba(0,0,0,0.04));
        overflow: hidden;
        transition: box-shadow var(--transition-base, 0.2s ease);
    }
    .dash-card:hover {
        box-shadow: var(--shadow-sm, 0 2px 6px rgba(0,0,0,0.06));
    }
    .dash-card-header {
        background: rgba(255, 255, 255, 0.97);
        border-bottom: 1px solid var(--border, #e2e8f0);
        padding: 0.38rem 0.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dash-card-title {
        font-family: var(--font-heading, 'Siemreap', sans-serif);
        font-weight: 700;
        font-size: 0.84rem;
        color: var(--text-dark, #0f172a);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* Financial Health Cards */
    .health-mini-card {
        border-radius: 6px;
        padding: 0.3rem 0.25rem;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid transparent;
    }
    .health-mini-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .health-mini-card.health-paid {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }
    .health-mini-card.health-partial {
        background: #fffbeb;
        border-color: #fde68a;
        color: #92400e;
    }
    .health-mini-card.health-unpaid {
        background: #fff1f2;
        border-color: #fecdd3;
        color: #9f1239;
    }
    .health-mini-card.health-overdue {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    /* Attendance Mini Cards */
    .att-mini-box {
        border-radius: 6px;
        padding: 0.35rem 0.25rem;
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid transparent;
        position: relative;
    }
    .att-mini-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .att-mini-box.att-present {
        background: #ecfdf5;
        border-color: #a7f3d0;
    }
    .att-mini-box.att-absent {
        background: #fef2f2;
        border-color: #fecaca;
    }
    .att-mini-box.att-late {
        background: #fffbeb;
        border-color: #fde68a;
    }
    .att-mini-box.att-permission {
        background: #f0f9ff;
        border-color: #bae6fd;
    }

    /* Quick action buttons */
    .quick-action-tile {
        background: var(--surface, #ffffff);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 9px;
        padding: 0.75rem 0.45rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        text-decoration: none;
        transition: all 0.2s ease;
        color: var(--text-dark, #0f172a);
        height: 100%;
    }
    .quick-action-tile:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm, 0 4px 12px rgba(0,0,0,0.06));
        border-color: var(--gold, #e8a838);
        color: var(--text-dark, #0f172a);
    }
    .quick-action-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        transition: transform 0.2s ease;
    }
    .quick-action-tile:hover .quick-action-icon {
        transform: scale(1.08);
    }

    .student-avatar-sm {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">

    
    <div class="dash-header-bar d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-2">
        <div>
            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                <h1 class="page-title mb-0" style="font-size: 1.1rem;">
                    <?php echo e($isAdmin ? 'ផ្ទាំងគ្រប់គ្រងសាលារៀន' : 'ផ្ទាំងព័ត៌មានគ្រូបង្រៀន'); ?>

                </h1>
                <span class="badge <?php echo e($isAdmin ? 'badge-gold' : 'badge-gold'); ?> d-inline-flex align-items-center gap-1" style="font-size: 0.72rem; padding: 0.18rem 0.45rem;">
                    <i class="bi <?php echo e($isAdmin ? 'bi-shield-check' : 'bi-person-badge'); ?>"></i>
                    <?php echo e($isAdmin ? 'អ្នកគ្រប់គ្រងប្រព័ន្ធ' : 'ទិដ្ឋភាពគ្រូបង្រៀន'); ?>

                </span>
            </div>
            <p class="page-subtitle mb-0 mt-0.5 d-flex align-items-center gap-2 flex-wrap" style="font-size: 0.74rem;">
                <span><i class="bi bi-mortarboard text-muted me-1"></i>ឆ្នាំសិក្សា៖ <strong class="text-dark"><?php echo e($activeYear?->name ?? 'បច្ចុប្បន្ន'); ?></strong></span>
                <span class="text-muted">&bull;</span>
                <span><i class="bi bi-calendar3 text-muted me-1"></i>ថ្ងៃនេះ៖ <strong class="text-dark"><?php echo e($today->format('l, d F Y')); ?></strong></span>
            </p>
        </div>

        <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <?php if($isAdmin): ?>
                <a href="<?php echo e(route('attendance.index')); ?>" class="btn btn-sm btn-ghost border fw-semibold d-inline-flex align-items-center gap-1 shadow-xs" style="padding: 0.25rem 0.6rem; font-size: 0.76rem;">
                    <i class="bi bi-calendar2-check text-muted"></i> វត្តមាន
                </a>
                <a href="<?php echo e(route('students.create')); ?>" class="btn btn-primary-action btn-sm shadow-sm" style="padding: 0.25rem 0.65rem; font-size: 0.76rem;">
                    <i class="bi bi-person-plus-fill"></i> បន្ថែមសិស្សថ្មី
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('attendance.index')); ?>" class="btn btn-primary-action btn-sm shadow-sm" style="padding: 0.25rem 0.65rem; font-size: 0.76rem;">
                    <i class="bi bi-calendar2-check-fill"></i> កត់ត្រាវត្តមាន
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if($isAdmin): ?>
        

        
        <div class="row g-2 mb-2">
            
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dash-kpi-card kpi-blue">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-dark fw-bold" style="font-size: 0.86rem;">សិស្សសរុប</span>
                            <div class="dash-icon-box" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <div class="dash-stat-value mb-1" id="stat-total-students"><?php echo e(number_format($totalStudents)); ?></div>
                        <div class="d-flex align-items-center gap-1 flex-wrap">
                            <span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; font-size: 0.74rem; padding: 0.15rem 0.45rem;">
                                <i class="bi bi-check-circle-fill me-1"></i><?php echo e(number_format($activeStudents)); ?> កំពុងសិក្សា
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.74rem; padding: 0.15rem 0.45rem;">
                                <?php echo e(number_format($inactiveStudents)); ?> អសកម្ម
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1.5 pt-1 border-top" style="font-size: 0.75rem;">
                        <a href="<?php echo e(route('students.index')); ?>" class="text-muted text-decoration-none d-inline-flex align-items-center gap-1 text-truncate" title="សិស្សដែលមិនទាន់បានចុះឈ្មោះក្នុងឆ្នាំសិក្សា">
                            <span>គ្មានឆ្នាំ៖</span>
                            <strong class="<?php echo e($studentsWithoutEnrollmentCount > 0 ? 'text-warning' : 'text-dark'); ?>"><?php echo e(number_format($studentsWithoutEnrollmentCount)); ?></strong>
                        </a>
                        <a href="<?php echo e(route('students.index')); ?>" class="text-muted text-decoration-none d-inline-flex align-items-center gap-1 text-truncate" title="សិស្សដែលមិនទាន់បានចាត់ចូលថ្នាក់">
                            <span>គ្មានថ្នាក់៖</span>
                            <strong class="<?php echo e($studentsWithoutClassCount > 0 ? 'text-danger' : 'text-dark'); ?>"><?php echo e(number_format($studentsWithoutClassCount)); ?></strong>
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dash-kpi-card kpi-emerald">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-dark fw-bold" style="font-size: 0.86rem;">ការចុះឈ្មោះសកម្ម</span>
                            <div class="dash-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                        </div>
                        <div class="dash-stat-value mb-1" id="stat-active-enrollments"><?php echo e(number_format($activeEnrollments)); ?></div>
                        <div class="d-flex align-items-center gap-1 flex-wrap">
                            <a href="<?php echo e(route('students.index', ['student_type' => 'new'])); ?>" class="badge text-decoration-none" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; font-size: 0.74rem; padding: 0.15rem 0.45rem;">
                                <i class="bi bi-sparkles me-1"></i><?php echo e(number_format($newStudentsCount)); ?> សិស្សថ្មី
                            </a>
                            <a href="<?php echo e(route('students.index', ['student_type' => 'returning'])); ?>" class="badge text-decoration-none" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 0.74rem; padding: 0.15rem 0.45rem;">
                                <i class="bi bi-arrow-repeat me-1"></i><?php echo e(number_format($returningStudentsCount)); ?> សិស្សបន្ត
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1.5 pt-1 border-top text-muted" style="font-size: 0.75rem;">
                        <span>ឆ្នាំសិក្សា</span>
                        <span class="fw-semibold text-dark text-truncate"><?php echo e($activeYear?->name ?? 'បច្ចុប្បន្ន'); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dash-kpi-card kpi-amber">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-dark fw-bold" style="font-size: 0.86rem;">ថ្នាក់រៀនទាំងអស់</span>
                            <div class="dash-icon-box" style="background: rgba(232, 168, 56, 0.15); color: #d97706;">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                        </div>
                        <div class="dash-stat-value mb-1" id="stat-total-classes"><?php echo e(number_format($totalClasses)); ?></div>
                        <div class="d-flex align-items-center gap-1 flex-wrap">
                            <span class="badge bg-light text-dark border" style="font-size: 0.74rem; padding: 0.15rem 0.45rem;">
                                <i class="bi bi-book me-1 text-muted"></i><?php echo e(number_format($totalSubjects)); ?> មុខវិជ្ជា
                            </span>
                            <span class="badge bg-light text-dark border" style="font-size: 0.74rem; padding: 0.15rem 0.45rem;">
                                <i class="bi bi-link-45deg me-1 text-muted"></i><?php echo e(number_format($totalClassSubjects)); ?> មុខវិជ្ជាតាមថ្នាក់
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1.5 pt-1 border-top text-muted" style="font-size: 0.75rem;">
                        <span>រចនាសម្ព័ន្ធ</span>
                        <a href="<?php echo e(route('classes.index')); ?>" class="text-decoration-none fw-semibold text-truncate ms-1" style="color: var(--gold-dark, #b45309);">គ្រប់គ្រងថ្នាក់ &rarr;</a>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dash-kpi-card kpi-purple">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-dark fw-bold" style="font-size: 0.86rem;">បុគ្គលិក &amp; គ្រូបង្រៀន</span>
                            <div class="dash-icon-box" style="background: rgba(139, 92, 246, 0.1); color: #7c3aed;">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                        </div>
                        <div class="dash-stat-value mb-1" id="stat-total-employees"><?php echo e(number_format($totalEmployees)); ?></div>
                        <div class="d-flex align-items-center gap-1 flex-wrap">
                            <span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; font-size: 0.74rem; padding: 0.15rem 0.45rem;">
                                <i class="bi bi-check-circle-fill me-1"></i><?php echo e(number_format($activeEmployees)); ?> កំពុងបម្រើការ
                            </span>
                            <span class="badge bg-light text-dark border" style="font-size: 0.74rem; padding: 0.15rem 0.45rem;">
                                <?php echo e(number_format($totalTeacherAssignments)); ?> ចាត់តាំង
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1.5 pt-1 border-top text-muted" style="font-size: 0.75rem;">
                        <span>បុគ្គលិក</span>
                        <a href="<?php echo e(route('employees.index')); ?>" class="text-decoration-none fw-semibold text-truncate ms-1" style="color: #7c3aed;">បញ្ជីបុគ្គលិក &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="dash-card mb-2">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 22px; height: 22px; background: rgba(232, 168, 56, 0.15); color: #d97706; font-size: 0.75rem;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <span>សុខភាពហិរញ្ញវត្ថុសិស្ស &amp; ថ្លៃសិក្សា</span>
                </div>
                <div class="d-flex align-items-center gap-1.5 flex-wrap small">
                    <a href="<?php echo e(route('student-invoices.index')); ?>" class="badge text-decoration-none bg-light text-dark border px-1.5 py-0.5" style="font-size: 0.7rem;">
                        ចេញវិក្កយបត្រ៖ <strong class="ms-1">$<?php echo e(number_format($totalInvoiced, 2)); ?></strong>
                    </a>
                    <a href="<?php echo e(route('student-payments.index')); ?>" class="badge text-decoration-none px-1.5 py-0.5" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; font-size: 0.7rem;">
                        ប្រមូលបាន៖ <strong class="ms-1">$<?php echo e(number_format($totalCollected, 2)); ?></strong>
                    </a>
                    <a href="<?php echo e(route('finance.student-balances', ['payment_status' => 'unpaid'])); ?>" class="badge text-decoration-none px-1.5 py-0.5 <?php echo e($totalOutstanding > 0 ? 'badge-rose' : 'bg-light text-muted border'); ?>" style="font-size: 0.7rem;">
                        នៅសល់មិនទាន់បង់៖ <strong class="ms-1">$<?php echo e(number_format($totalOutstanding, 2)); ?></strong>
                    </a>
                </div>
            </div>
            <div class="card-body p-1.5 p-md-2">
                <div class="row g-1.5 text-center">
                    <div class="col-6 col-md-3">
                        <a href="<?php echo e(route('finance.student-balances', ['payment_status' => 'paid'])); ?>" class="health-mini-card health-paid">
                            <div class="d-flex align-items-center justify-content-center gap-1 fw-bold mb-0.5" style="font-size: 0.78rem;">
                                <i class="bi bi-check-circle-fill"></i> សិស្សបង់គ្រប់
                            </div>
                            <div class="fw-bolder mb-0.5" style="font-size: 1.35rem; font-family: var(--font-heading); line-height: 1.1;"><?php echo e(number_format($paidStudentsCount)); ?></div>
                            <span class="fw-semibold opacity-75" style="font-size: 0.72rem;">ទូទាត់រួចរាល់</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="<?php echo e(route('finance.student-balances', ['payment_status' => 'partial'])); ?>" class="health-mini-card health-partial">
                            <div class="d-flex align-items-center justify-content-center gap-1 fw-bold mb-0.5" style="font-size: 0.78rem;">
                                <i class="bi bi-pie-chart-fill"></i> បង់មួយផ្នែក
                            </div>
                            <div class="fw-bolder mb-0.5" style="font-size: 1.35rem; font-family: var(--font-heading); line-height: 1.1;"><?php echo e(number_format($partialStudentsCount)); ?></div>
                            <span class="fw-semibold opacity-75" style="font-size: 0.72rem;">នៅជំពាក់ខ្លះ</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="<?php echo e(route('finance.student-balances', ['payment_status' => 'unpaid'])); ?>" class="health-mini-card health-unpaid">
                            <div class="d-flex align-items-center justify-content-center gap-1 fw-bold mb-0.5" style="font-size: 0.78rem;">
                                <i class="bi bi-exclamation-circle-fill"></i> មិនទាន់បង់ប្រាក់
                            </div>
                            <div class="fw-bolder mb-0.5" style="font-size: 1.35rem; font-family: var(--font-heading); line-height: 1.1;"><?php echo e(number_format($unpaidStudentsCount)); ?></div>
                            <span class="fw-semibold opacity-75" style="font-size: 0.72rem;">រង់ចាំការបង់</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="<?php echo e(route('finance.student-balances', ['payment_status' => 'overdue'])); ?>" class="health-mini-card health-overdue">
                            <div class="d-flex align-items-center justify-content-center gap-1 fw-bold mb-0.5" style="font-size: 0.78rem;">
                                <i class="bi bi-clock-history"></i> ហួសកាលកំណត់
                            </div>
                            <div class="fw-bolder mb-0.5" style="font-size: 1.35rem; font-family: var(--font-heading); line-height: 1.1;"><?php echo e(number_format($overdueStudentsCount)); ?></div>
                            <span class="fw-semibold opacity-75" style="font-size: 0.72rem;">ហួសថ្ងៃកំណត់</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="dash-card mb-2">
            <div class="dash-card-header">
                <div>
                    <h5 class="dash-card-title mb-0.5">
                        <i class="bi bi-calendar2-day text-warning"></i>
                        ទិដ្ឋភាពទូទៅនៃវត្តមានថ្ងៃនេះ
                    </h5>
                    <span class="text-muted small" style="font-size: 0.72rem;">
                        ស្ថានភាពវត្តមានសិស្សជាក់ស្តែងសម្រាប់ <?php echo e($today->format('d/m/Y')); ?> &bull;
                        កត់ត្រាបាន <?php echo e($todaySessionsCount); ?> វេន
                    </span>
                </div>
                <div>
                    <?php if($todayTotalAttendance > 0): ?>
                        <span class="badge px-2 py-1 rounded-pill"
                              style="background-color: rgba(16, 185, 129, 0.15); color: #065f46; border: 1px solid #a7f3d0; font-size: 0.72rem;">
                            <i class="bi bi-pie-chart-fill me-1"></i>
                            អត្រាវត្តមាន៖ <strong id="stat-attendance-rate"><?php echo e($attendanceRate); ?>%</strong>
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill border border-secondary-subtle" style="font-size: 0.7rem;">
                            <i class="bi bi-info-circle me-1"></i> មិនទាន់មានការកត់ត្រាវត្តមានថ្ងៃនេះទេ
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body p-1.5 p-md-2">
                <div class="row g-1.5">
                    
                    <div class="col-6 col-md-3">
                        <div class="att-mini-box att-present">
                            <div class="text-success fw-bold mb-0.5" style="font-size: 0.78rem;">
                                <i class="bi bi-check-circle-fill me-1"></i> វត្តមាន (មក)
                            </div>
                            <div class="fw-bolder text-success mb-0" style="font-size: 1.35rem; font-family: var(--font-heading); line-height: 1.1;" id="stat-today-present"><?php echo e(number_format($todayPresent)); ?></div>
                            <div class="text-muted fw-semibold mt-0.5" style="font-size: 0.72rem;">
                                <?php echo e($todayTotalAttendance > 0 ? round(($todayPresent / $todayTotalAttendance) * 100, 1) : 0); ?>% នៃវត្តមាន
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-6 col-md-3">
                        <div class="att-mini-box att-absent">
                            <div class="text-danger fw-bold mb-0.5" style="font-size: 0.78rem;">
                                <i class="bi bi-x-circle-fill me-1"></i> អវត្តមាន (អវត្ត)
                            </div>
                            <div class="fw-bolder text-danger mb-0" style="font-size: 1.35rem; font-family: var(--font-heading); line-height: 1.1;" id="stat-today-absent"><?php echo e(number_format($todayAbsent)); ?></div>
                            <div class="text-muted fw-semibold mt-0.5" style="font-size: 0.72rem;">
                                <?php echo e($todayTotalAttendance > 0 ? round(($todayAbsent / $todayTotalAttendance) * 100, 1) : 0); ?>% នៃវត្តមាន
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-6 col-md-3">
                        <div class="att-mini-box att-late">
                            <div class="text-warning-emphasis fw-bold mb-0.5" style="font-size: 0.78rem;">
                                <i class="bi bi-clock-history me-1"></i> មកយឺត
                            </div>
                            <div class="fw-bolder text-warning-emphasis mb-0" style="font-size: 1.35rem; font-family: var(--font-heading); line-height: 1.1;" id="stat-today-late"><?php echo e(number_format($todayLate)); ?></div>
                            <div class="text-muted fw-semibold mt-0.5" style="font-size: 0.72rem;">
                                <?php echo e($todayTotalAttendance > 0 ? round(($todayLate / $todayTotalAttendance) * 100, 1) : 0); ?>% នៃវត្តមាន
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-6 col-md-3">
                        <div class="att-mini-box att-permission">
                            <div class="text-info-emphasis fw-bold mb-0.5" style="font-size: 0.78rem;">
                                <i class="bi bi-file-earmark-medical me-1"></i> ច្បាប់ / អនុញ្ញាត
                            </div>
                            <div class="fw-bolder text-info-emphasis mb-0" style="font-size: 1.35rem; font-family: var(--font-heading); line-height: 1.1;" id="stat-today-permission"><?php echo e(number_format($todayPermission)); ?></div>
                            <div class="text-muted fw-semibold mt-0.5" style="font-size: 0.72rem;">
                                <?php echo e($todayTotalAttendance > 0 ? round(($todayPermission / $todayTotalAttendance) * 100, 1) : 0); ?>% នៃវត្តមាន
                            </div>
                        </div>
                    </div>
                </div>

                <?php if($todayTotalAttendance > 0): ?>
                    
                    <div class="progress mt-1.5" style="height: 5px; border-radius: 999px; overflow: hidden; background: #f1f5f9;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e(round(($todayPresent / $todayTotalAttendance) * 100, 1)); ?>%" title="វត្តមាន (មក)"></div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo e(round(($todayAbsent / $todayTotalAttendance) * 100, 1)); ?>%" title="អវត្តមាន"></div>
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo e(round(($todayLate / $todayTotalAttendance) * 100, 1)); ?>%" title="មកយឺត"></div>
                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo e(round(($todayPermission / $todayTotalAttendance) * 100, 1)); ?>%" title="ច្បាប់"></div>
                    </div>
                <?php endif; ?>

                
                <div class="row g-1.5 mt-1.5 pt-1.5 border-top border-light-subtle">
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center justify-content-between p-1.5 rounded-2 bg-light border border-light-subtle">
                            <span class="text-muted" style="font-size: 0.72rem;">
                                <i class="bi bi-cash-coin text-success me-1"></i> ចំណូលប្រមូលបានថ្ងៃនេះ៖
                                <strong class="text-dark">$<?php echo e(number_format($todayPaymentsSum, 2)); ?></strong>
                                <span class="text-muted">(<?php echo e(number_format($todayPaymentsCount)); ?> បង្កាន់ដៃ)</span>
                            </span>
                            <a href="<?php echo e(route('student-payments.index', ['date_from' => $today->toDateString(), 'date_to' => $today->toDateString()])); ?>" class="btn btn-sm btn-outline-success py-0 px-1.5 fw-medium" style="font-size:0.7rem;">
                                មើល &rarr;
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center justify-content-between p-1.5 rounded-2 bg-light border border-light-subtle">
                            <span class="text-muted" style="font-size: 0.72rem;">
                                <i class="bi bi-person-plus text-primary me-1"></i> ការចុះឈ្មោះថ្ងៃនេះ៖
                                <strong class="text-dark"><?php echo e(number_format($todayEnrollmentsCount)); ?></strong>
                                <span class="text-muted">នាក់</span>
                            </span>
                            <a href="<?php echo e(route('enrollments.index')); ?>" class="btn btn-sm btn-outline-primary py-0 px-1.5 fw-medium" style="font-size:0.7rem;">
                                មើល &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row g-2 mb-2">
            
            <div class="col-12 col-lg-5">
                <div class="dash-card h-100">
                    <div class="dash-card-header">
                        <h6 class="dash-card-title">
                            <i class="bi bi-book-half text-primary"></i>រចនាសម្ព័ន្ធអប់រំ &amp; ការសិក្សា
                        </h6>
                        <a href="<?php echo e(route('classes.index')); ?>" class="small text-decoration-none fw-semibold" style="color: var(--gold-dark, #b45309); font-size: 0.75rem;">
                            មើលថ្នាក់ &rarr;
                        </a>
                    </div>
                    <div class="card-body p-2.5 p-md-3 d-flex flex-column justify-content-around">
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light-subtle" style="font-size: 0.82rem;">
                            <span class="text-muted d-flex align-items-center gap-1.5">
                                <i class="bi bi-door-open text-primary opacity-75"></i> ថ្នាក់រៀនសកម្ម
                            </span>
                            <span class="fw-bold text-dark"><?php echo e(number_format($totalClasses)); ?></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light-subtle" style="font-size: 0.82rem;">
                            <span class="text-muted d-flex align-items-center gap-1.5">
                                <i class="bi bi-journal-text text-success opacity-75"></i> មុខវិជ្ជាសិក្សា
                            </span>
                            <span class="fw-bold text-dark"><?php echo e(number_format($totalSubjects)); ?></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light-subtle" style="font-size: 0.82rem;">
                            <span class="text-muted d-flex align-items-center gap-1.5">
                                <i class="bi bi-diagram-3 text-warning opacity-75"></i> ការភ្ជាប់មុខវិជ្ជាតាមថ្នាក់
                            </span>
                            <span class="fw-bold text-dark"><?php echo e(number_format($totalClassSubjects)); ?></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light-subtle" style="font-size: 0.82rem;">
                            <span class="text-muted d-flex align-items-center gap-1.5">
                                <i class="bi bi-person-workspace text-info opacity-75"></i> ការចាត់តាំងគ្រូសកម្ម
                            </span>
                            <span class="fw-bold text-dark"><?php echo e(number_format($totalTeacherAssignments)); ?></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2" style="font-size: 0.82rem;">
                            <span class="text-muted d-flex align-items-center gap-1.5">
                                <i class="bi bi-calendar-range text-purple opacity-75"></i> ឆ្នាំសិក្សាបច្ចុប្បន្ន
                            </span>
                            <span class="badge" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 0.75rem;">
                                <?php echo e($activeYear?->name ?? '—'); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-lg-7">
                <div class="dash-card h-100">
                    <div class="dash-card-header">
                        <h6 class="dash-card-title">
                            <i class="bi bi-person-lines-fill text-info"></i>សិស្សចុះឈ្មោះថ្មីៗ
                        </h6>
                        <a href="<?php echo e(route('students.index')); ?>" class="small text-decoration-none fw-semibold" style="color: var(--gold-dark, #b45309); font-size: 0.75rem;">
                            មើលទាំងអស់ (<?php echo e(number_format($totalStudents)); ?>) &rarr;
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="data-table mb-0" style="font-size: 0.82rem;">
                                <thead>
                                    <tr>
                                        <th>សិស្ស</th>
                                        <th>លេខកូដសិស្ស</th>
                                        <th>ថ្នាក់រៀន</th>
                                        <th class="text-end">ស្ថានភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $recentStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $name = $student->user?->name ?? $student->name ?? 'N/A';
                                            $initials = mb_substr(trim($name), 0, 2);
                                            $stName = strtolower($student->studentStatus?->name ?? $student->display_status ?? 'unknown');
                                        ?>
                                        <tr>
                                            <td class="py-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="student-avatar-sm" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">
                                                        <?php echo e($initials); ?>

                                                    </div>
                                                    <div>
                                                        <a href="<?php echo e(route('students.show', $student->id)); ?>" class="text-dark fw-semibold text-decoration-none d-block">
                                                            <?php echo e($name); ?>

                                                        </a>
                                                        <span class="text-muted" style="font-size: 0.68rem;">ID: #<?php echo e($student->id); ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.72rem;"><?php echo e($student->admission_number); ?></span>
                                            </td>
                                            <td class="py-2">
                                                <span class="fw-medium text-dark"><?php echo e($student->schoolClass?->name ?? '—'); ?></span>
                                            </td>
                                            <td class="text-end py-2">
                                                <?php if($stName === 'active'): ?>
                                                    <span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; font-size: 0.72rem;">
                                                        កំពុងសិក្សា
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.72rem;">
                                                        <?php echo e($student->studentStatus?->name ?? $student->display_status); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">
                                                <i class="bi bi-inbox fs-5 d-block mb-1 opacity-50"></i>
                                                មិនទាន់មានទិន្នន័យសិស្សនៅឡើយទេ។
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row g-2 mb-2">
            
            <div class="col-12 col-lg-6">
                <div class="dash-card h-100">
                    <div class="dash-card-header">
                        <h6 class="dash-card-title">
                            <i class="bi bi-person-vcard text-success"></i>ការចុះឈ្មោះចូលរៀនថ្មីៗ
                        </h6>
                        <a href="<?php echo e(route('enrollments.index')); ?>" class="small text-decoration-none fw-semibold" style="color: var(--gold-dark, #b45309); font-size: 0.75rem;">
                            មើលទាំងអស់ &rarr;
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="data-table mb-0" style="font-size: 0.82rem;">
                                <thead>
                                    <tr>
                                        <th>សិស្ស</th>
                                        <th>ថ្នាក់រៀន</th>
                                        <th>ឆ្នាំសិក្សា</th>
                                        <th class="text-end">ស្ថានភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $recentEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $eName = $enrollment->student?->user?->name ?? 'N/A';
                                            $eInitials = mb_substr(trim($eName), 0, 2);
                                            $isActive = strcasecmp($enrollment->status, 'active') === 0;
                                        ?>
                                        <tr>
                                            <td class="py-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="student-avatar-sm" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">
                                                        <?php echo e($eInitials); ?>

                                                    </div>
                                                    <span class="fw-semibold text-dark"><?php echo e($eName); ?></span>
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <span class="badge bg-light text-dark border" style="font-size: 0.72rem;">
                                                    <?php echo e($enrollment->enrollmentDetail?->class?->name ?? '—'); ?>

                                                </span>
                                            </td>
                                            <td class="py-2">
                                                <span class="text-muted small"><?php echo e($enrollment->academic_year?->name ?? '—'); ?></span>
                                            </td>
                                            <td class="text-end py-2">
                                                <span class="badge" style="<?php echo e($isActive ? 'background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;' : 'background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;'); ?> font-size: 0.72rem;">
                                                    <?php echo e($isActive ? 'សកម្ម' : $enrollment->status); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">
                                                <i class="bi bi-inbox fs-5 d-block mb-1 opacity-50"></i>
                                                មិនទាន់មានកំណត់ត្រាចុះឈ្មោះនៅឡើយទេ។
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12 col-lg-6">
                <div class="dash-card h-100">
                    <div class="dash-card-header">
                        <h6 class="dash-card-title">
                            <i class="bi bi-person-workspace text-warning"></i>ការចាត់តាំងគ្រូបង្រៀនថ្មីៗ
                        </h6>
                        <a href="<?php echo e(route('teacher-assignments.index')); ?>" class="small text-decoration-none fw-semibold" style="color: var(--gold-dark, #b45309); font-size: 0.75rem;">
                            មើលទាំងអស់ &rarr;
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="data-table mb-0" style="font-size: 0.82rem;">
                                <thead>
                                    <tr>
                                        <th>គ្រូបង្រៀន</th>
                                        <th>មុខវិជ្ជា</th>
                                        <th>ថ្នាក់រៀន</th>
                                        <th class="text-end">ឆ្នាំសិក្សា</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $recentTeacherAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $tName = $assignment->employee?->user?->name ?? $assignment->employee?->name ?? '—';
                                            $tInitials = mb_substr(trim($tName), 0, 2);
                                        ?>
                                        <tr>
                                            <td class="py-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="student-avatar-sm" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">
                                                        <?php echo e($tInitials); ?>

                                                    </div>
                                                    <span class="fw-semibold text-dark"><?php echo e($tName); ?></span>
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <span class="badge bg-light text-dark border" style="font-size: 0.72rem;">
                                                    <?php echo e($assignment->class_subject?->subject?->name ?? '—'); ?>

                                                </span>
                                            </td>
                                            <td class="py-2">
                                                <span class="fw-medium text-dark"><?php echo e($assignment->class_subject?->class?->name ?? '—'); ?></span>
                                            </td>
                                            <td class="text-end py-2">
                                                <span class="text-muted small"><?php echo e($assignment->academic_year?->name ?? '—'); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">
                                                <i class="bi bi-inbox fs-5 d-block mb-1 opacity-50"></i>
                                                មិនទាន់មានការចាត់តាំងគ្រូបង្រៀននៅឡើយទេ។
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="dash-card mb-2">
            <div class="dash-card-header">
                <h6 class="dash-card-title">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>សកម្មភាពរហ័ស &amp; ផ្លូវកាត់
                </h6>
            </div>
            <div class="card-body p-2.5 p-md-3">
                <div class="row g-2">
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?php echo e(route('students.create')); ?>" class="quick-action-tile">
                            <div class="quick-action-icon" style="background: #eff6ff; color: #2563eb;">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <span class="fw-bold" style="font-size: 0.78rem;">បន្ថែមសិស្ស</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?php echo e(route('enrollments.create')); ?>" class="quick-action-tile">
                            <div class="quick-action-icon" style="background: #ecfdf5; color: #059669;">
                                <i class="bi bi-person-vcard"></i>
                            </div>
                            <span class="fw-bold" style="font-size: 0.78rem;">ចុះឈ្មោះសិស្ស</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?php echo e(route('attendance.index')); ?>" class="quick-action-tile">
                            <div class="quick-action-icon" style="background: #fffbeb; color: #d97706;">
                                <i class="bi bi-calendar2-check"></i>
                            </div>
                            <span class="fw-bold" style="font-size: 0.78rem;">កត់ត្រាវត្តមាន</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?php echo e(route('academics.index', ['tab' => 'class-subjects'])); ?>" class="quick-action-tile">
                            <div class="quick-action-icon" style="background: #f0fdf4; color: #16a34a;">
                                <i class="bi bi-journal-bookmark"></i>
                            </div>
                            <span class="fw-bold" style="font-size: 0.78rem;">មុខវិជ្ជាតាមថ្នាក់</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?php echo e(route('academics.index', ['tab' => 'teacher-assignments'])); ?>" class="quick-action-tile">
                            <div class="quick-action-icon" style="background: #faf5ff; color: #9333ea;">
                                <i class="bi bi-person-workspace"></i>
                            </div>
                            <span class="fw-bold" style="font-size: 0.78rem;">ចាត់តាំងគ្រូ</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?php echo e(route('employees.create')); ?>" class="quick-action-tile">
                            <div class="quick-action-icon" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <span class="fw-bold" style="font-size: 0.78rem;">បន្ថែមបុគ្គលិក</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        

        
        <div class="row g-2 mb-2">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dash-kpi-card kpi-blue">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-dark fw-bold" style="font-size: 0.95rem;">ថ្នាក់ដែលទទួលបន្ទុក</span>
                            <div class="dash-icon-box" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                        </div>
                        <div class="dash-stat-value mb-1" id="stat-teacher-classes"><?php echo e(number_format($myClassesCount)); ?></div>
                        <span class="text-muted fw-semibold" style="font-size: 0.82rem;">ថ្នាក់រៀនដែលបានចាត់តាំង</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2 pt-1.5 border-top text-muted" style="font-size: 0.82rem;">
                        <span>ឆ្នាំសិក្សា</span>
                        <span class="fw-semibold text-dark"><?php echo e($activeYear?->name ?? 'បច្ចុប្បន្ន'); ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dash-kpi-card kpi-emerald">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-dark fw-bold" style="font-size: 0.95rem;">មុខវិជ្ជាបង្រៀន</span>
                            <div class="dash-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                                <i class="bi bi-book-fill"></i>
                            </div>
                        </div>
                        <div class="dash-stat-value mb-1" id="stat-teacher-subjects"><?php echo e(number_format($mySubjectsCount)); ?></div>
                        <span class="text-muted fw-semibold" style="font-size: 0.82rem;">មុខវិជ្ជាផ្សេងៗដែលបង្រៀន</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2 pt-1.5 border-top text-muted" style="font-size: 0.82rem;">
                        <span>កម្មវិធីសិក្សា</span>
                        <span class="fw-semibold text-success">សកម្ម</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dash-kpi-card kpi-amber">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-dark fw-bold" style="font-size: 0.95rem;">សិស្សក្នុងបន្ទុក</span>
                            <div class="dash-icon-box" style="background: rgba(232, 168, 56, 0.15); color: #d97706;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <div class="dash-stat-value mb-1" id="stat-teacher-students"><?php echo e(number_format($myStudentsCount)); ?></div>
                        <span class="text-success fw-bold" style="font-size: 0.82rem;">សិស្សសកម្មក្នុងថ្នាក់បង្រៀន</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2 pt-1.5 border-top text-muted" style="font-size: 0.82rem;">
                        <span>បញ្ជីឈ្មោះ</span>
                        <span class="fw-semibold text-dark">ត្រីមាសបច្ចុប្បន្ន</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dash-kpi-card kpi-purple">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-dark fw-bold" style="font-size: 0.95rem;">វេនបង្រៀនថ្ងៃនេះ</span>
                            <div class="dash-icon-box" style="background: rgba(139, 92, 246, 0.1); color: #7c3aed;">
                                <i class="bi bi-calendar-check-fill"></i>
                            </div>
                        </div>
                        <div class="dash-stat-value mb-1" id="stat-teacher-sessions"><?php echo e(number_format($todaySessionsCount)); ?></div>
                        <span class="text-muted fw-semibold" style="font-size: 0.82rem;">វេនកត់ត្រាវត្តមានថ្ងៃនេះ</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2 pt-1.5 border-top text-muted" style="font-size: 0.82rem;">
                        <span>កាលវិភាគ</span>
                        <span class="fw-semibold text-purple">ប្រចាំថ្ងៃ</span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="dash-card mb-2">
            <div class="dash-card-header">
                <div>
                    <h5 class="dash-card-title mb-0.5">
                        <i class="bi bi-calendar2-day text-warning"></i>
                        វត្តមានក្នុងថ្នាក់បង្រៀនរបស់ខ្ញុំ (ថ្ងៃនេះ)
                    </h5>
                    <span class="text-muted small" style="font-size: 0.72rem;">កំណត់ត្រាវត្តមានសម្រាប់មុខវិជ្ជា និងថ្នាក់រៀនរបស់អ្នក</span>
                </div>
                <div>
                    <?php if($todayTotalAttendance > 0): ?>
                        <span class="badge px-2.5 py-1.5 rounded-pill"
                              style="background-color: rgba(16, 185, 129, 0.15); color: #065f46; border: 1px solid #a7f3d0; font-size: 0.75rem;">
                            <i class="bi bi-pie-chart-fill me-1"></i>
                            អត្រាវត្តមាន៖ <strong id="stat-attendance-rate"><?php echo e($attendanceRate); ?>%</strong>
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 rounded-pill border border-secondary-subtle" style="font-size: 0.72rem;">
                            <i class="bi bi-info-circle me-1"></i> មិនទាន់មានការកត់ត្រាវត្តមានថ្ងៃនេះទេ
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body p-2.5 p-md-3">
                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <div class="att-mini-box att-present">
                            <div class="text-success small fw-semibold mb-0.5" style="font-size: 0.74rem;">វត្តមាន (មក)</div>
                            <div class="fs-4 fw-bold text-success mb-0" style="font-family: var(--font-heading); line-height: 1.1;" id="stat-today-present"><?php echo e(number_format($todayPresent)); ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="att-mini-box att-absent">
                            <div class="text-danger small fw-semibold mb-0.5" style="font-size: 0.74rem;">អវត្តមាន (អវត្ត)</div>
                            <div class="fs-4 fw-bold text-danger mb-0" style="font-family: var(--font-heading); line-height: 1.1;" id="stat-today-absent"><?php echo e(number_format($todayAbsent)); ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="att-mini-box att-late">
                            <div class="text-warning-emphasis small fw-semibold mb-0.5" style="font-size: 0.74rem;">មកយឺត</div>
                            <div class="fs-4 fw-bold text-warning-emphasis mb-0" style="font-family: var(--font-heading); line-height: 1.1;" id="stat-today-late"><?php echo e(number_format($todayLate)); ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="att-mini-box att-permission">
                            <div class="text-info-emphasis small fw-semibold mb-0.5" style="font-size: 0.74rem;">ច្បាប់ / អនុញ្ញាត</div>
                            <div class="fs-4 fw-bold text-info-emphasis mb-0" style="font-family: var(--font-heading); line-height: 1.1;" id="stat-today-permission"><?php echo e(number_format($todayPermission)); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="dash-card mb-2">
            <div class="dash-card-header">
                <div>
                    <h6 class="dash-card-title mb-0.5">
                        <i class="bi bi-person-workspace text-primary"></i>តារាងម៉ោងបង្រៀនរបស់ខ្ញុំ
                    </h6>
                    <span class="text-muted small" style="font-size: 0.72rem;">ថ្នាក់ និងមុខវិជ្ជាដែលអ្នកមានសិទ្ធិបង្រៀនសម្រាប់ <?php echo e($activeYear?->name ?? 'ឆ្នាំបច្ចុប្បន្ន'); ?></span>
                </div>
                <a href="<?php echo e(route('attendance.index')); ?>" class="btn btn-primary-action btn-sm shadow-sm" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                    <i class="bi bi-calendar2-check me-1"></i> កត់ត្រាវត្តមានឥឡូវនេះ
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="data-table mb-0" style="font-size: 0.82rem;">
                        <thead>
                            <tr>
                                <th>ថ្នាក់រៀន</th>
                                <th>មុខវិជ្ជា</th>
                                <th>ឆ្នាំសិក្សា</th>
                                <th class="text-end">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $teacherAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="fw-semibold text-dark py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="student-avatar-sm" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">
                                                <i class="bi bi-door-closed"></i>
                                            </div>
                                            <span><?php echo e($assignment->class_subject?->class?->name ?? '—'); ?></span>
                                        </div>
                                    </td>
                                    <td class="py-2">
                                        <span class="badge bg-light text-dark border" style="font-size: 0.72rem;">
                                            <?php echo e($assignment->class_subject?->subject?->name ?? '—'); ?>

                                        </span>
                                    </td>
                                    <td class="py-2">
                                        <span class="text-muted small"><?php echo e($assignment->academic_year?->name ?? '—'); ?></span>
                                    </td>
                                    <td class="text-end py-2">
                                        <a href="<?php echo e(route('attendance.index', ['class_id' => $assignment->class_subject?->class_id, 'teacher_assignment_id' => $assignment->id])); ?>"
                                           class="btn btn-sm btn-ghost border py-0.5 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-pencil-square text-primary"></i> វត្តមាន
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">
                                        <i class="bi bi-inbox fs-5 d-block mb-1 opacity-50"></i>
                                        មិនទាន់មានទិន្នន័យចាត់តាំងការបង្រៀនសម្រាប់គណនីរបស់អ្នកនៅឡើយទេ។ សូមទាក់ទងអ្នកគ្រប់គ្រង។
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel Project\laravel\resources\views/dashboard/index.blade.php ENDPATH**/ ?>