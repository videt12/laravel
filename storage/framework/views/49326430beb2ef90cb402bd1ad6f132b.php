<?php
    $userName    = $employee->user->name ?? $employee->name ?? 'Employee Profile';
    $nameParts   = explode(' ', trim($userName));
    $initials    = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
    $statusName  = $employee->employee_status->name ?? 'Active';
    $statusKh    = $employee->employee_status?->khmer_name ?? \App\Models\Employees\EmployeeStatus::translateToKhmer($statusName);
    $statusLower = strtolower($statusName);
    
    // Status color helper
    $statusBadgeClass = match(true) {
        str_contains($statusLower, 'active') => 'bg-success text-white',
        str_contains($statusLower, 'leave') => 'bg-warning text-dark',
        str_contains($statusLower, 'inactive'), str_contains($statusLower, 'terminated'), str_contains($statusLower, 'resign') => 'bg-danger text-white',
        default => 'bg-secondary text-white'
    };

    $contact = $employee->employeeContact;

    // Age calculation
    $age = $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->age : null;
    
    // Tenure calculation
    $tenure = $employee->date_of_joining ? \Carbon\Carbon::parse($employee->date_of_joining)->diffForHumans(['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) : null;
?>

<?php $__env->startSection('title', $userName . ' (' . $employee->employee_code . ') — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('students.index')); ?>" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">គ្រប់គ្រងបុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('employees.index')); ?>" class="text-decoration-none text-muted">បញ្ជីបុគ្គលិក</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page"><?php echo e($userName); ?></li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ═══════════════════════════════════════════════════════════════
   HERO PROFILE CARD
═══════════════════════════════════════════════════════════════ */
.employee-hero-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: var(--radius-lg, 12px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03), 0 4px 12px rgba(0, 0, 0, 0.03);
    margin-bottom: 1.5rem;
}
.hero-cover {
    height: 120px;
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    position: relative;
}
.hero-cover-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.12;
    background-image: radial-gradient(#fff 1px, transparent 1px);
    background-size: 16px 16px;
}
.hero-body {
    padding: 0 1.75rem 1.5rem;
    position: relative;
}
.hero-avatar-wrapper {
    position: relative;
    margin-top: -55px;
    margin-bottom: 0.75rem;
    display: inline-block;
}
.hero-avatar {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    border: 4px solid #fff;
    background: #e8a838;
    color: #fff;
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(232, 168, 56, 0.25);
}
.hero-name {
    font-size: 1.45rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.2rem;
    letter-spacing: -0.02em;
}
.hero-meta-item {
    font-size: 0.82rem;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

/* ═══════════════════════════════════════════════════════════════
   METRIC / STAT CARDS
═══════════════════════════════════════════════════════════════ */
.metric-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-top: 3px solid #e8a838;
    border-radius: var(--radius-md, 10px);
    padding: 1.1rem 1.25rem;
    height: 100%;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(232, 168, 56, 0.12);
}
.metric-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-md, 8px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
    background: #fef3c7;
    color: #b45309;
}
.metric-value {
    font-size: 1.55rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}
.metric-label {
    font-size: 0.88rem;
    font-weight: 600;
    color: #64748b;
    margin-top: 0.15rem;
}

/* ═══════════════════════════════════════════════════════════════
   NAV TABS & TAB PANELS
═══════════════════════════════════════════════════════════════ */
.employee-nav-tabs {
    border-bottom: 1px solid #e2e8f0;
    background: #fff;
    border-radius: var(--radius-lg, 12px) var(--radius-lg, 12px) 0 0;
    padding: 0 1.25rem;
}
.employee-nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    padding: 0.95rem 1.15rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    transition: all 0.2s ease;
    border-radius: 0;
}
.employee-nav-tabs .nav-link:hover {
    color: #c98b1e;
}
.employee-nav-tabs .nav-link.active {
    color: #b45309;
    border-bottom-color: #e8a838;
    background: transparent;
}

.employee-tab-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-top: none;
    border-radius: 0 0 var(--radius-lg, 12px) var(--radius-lg, 12px);
    padding: 1.75rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    margin-bottom: 2rem;
}

/* Detail Box / Grids */
.detail-box {
    padding: 0.85rem 1rem;
    background: #f8fafc;
    border-radius: var(--radius-md, 8px);
    border: 1px solid #f1f5f9;
}
.detail-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 0.25rem;
}
.detail-value {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
    word-break: break-word;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert" style="border-radius: var(--radius-md, 8px);">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div class="flex-grow-1"><?php echo e(session('success')); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert" style="border-radius: var(--radius-md, 8px);">
        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
        <div class="flex-grow-1"><?php echo e(session('error')); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<div class="employee-hero-card">
    <div class="hero-cover">
        <div class="hero-cover-pattern"></div>
    </div>
    <div class="hero-body">
        <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
            <div class="d-flex align-items-end gap-3 flex-wrap">
                <div class="hero-avatar-wrapper">
                    <div class="hero-avatar position-relative overflow-hidden">
                        <?php if($employee->photo_url): ?>
                            <img src="<?php echo e($employee->photo_url); ?>" alt="<?php echo e($userName); ?>" class="w-100 h-100 rounded-circle" style="object-fit: cover;" onerror="this.style.display='none'; document.getElementById('heroAvatarFallback')?.classList.remove('d-none');">
                            <span id="heroAvatarFallback" class="d-none"><?php echo e($initials ?: 'EM'); ?></span>
                        <?php else: ?>
                            <?php echo e($initials ?: 'EM'); ?>

                        <?php endif; ?>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h1 class="hero-name mb-0">
                            <?php echo e($employee->full_name); ?>

                            <?php if(($employee->last_name_khmer || $employee->first_name_khmer) && ($employee->last_name || $employee->first_name)): ?>
                                <span class="fs-6 text-muted fw-normal ms-1">(<?php echo e(trim(($employee->last_name ?? '') . ' ' . ($employee->first_name ?? ''))); ?>)</span>
                            <?php endif; ?>
                        </h1>
                        <span class="badge <?php echo e($statusBadgeClass); ?> px-2.5 py-1 rounded-pill small">
                            <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i><?php echo e($statusKh); ?>

                        </span>
                        <?php if($employee->employee_type): ?>
                            <span class="badge bg-warning bg-opacity-20 text-dark border border-warning border-opacity-50 px-2.5 py-1 rounded-pill small fw-semibold">
                                <i class="bi bi-person-workspace me-1 text-warning"></i><?php echo e($employee->employee_type); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex align-items-center gap-3 flex-wrap mt-1">
                        <span class="hero-meta-item">
                            <i class="bi bi-person-badge text-teal"></i>
                            <strong class="font-monospace"><?php echo e($employee->employee_code); ?></strong>
                        </span>
                        <span class="hero-meta-item">
                            <i class="bi bi-building text-primary"></i>
                            <?php echo e($employee->employee_department->name ?? 'គ្មានដេប៉ាតឺម៉ង់'); ?>

                        </span>
                        <span class="hero-meta-item">
                            <i class="bi bi-briefcase text-secondary"></i>
                            <?php echo e($employee->employee_position->name ?? 'គ្មានមុខតំណែង'); ?>

                        </span>
                        <?php if($employee->date_of_joining): ?>
                            <span class="hero-meta-item">
                                <i class="bi bi-calendar3 text-muted"></i>
                                ចូលបម្រើការងារ <?php echo e($employee->date_of_joining->format('d/m/Y')); ?>

                                <?php if($tenure): ?> (<?php echo e($tenure); ?>) <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i>បញ្ជីបុគ្គលិក
                </a>
                <?php
                    $latestContract = $employee->activeContract ?? $employee->employeeContracts()->latest()->first();
                ?>
                <?php if($latestContract): ?>
                    <a href="<?php echo e(route('employee-contracts.print', $latestContract)); ?>" target="_blank" class="btn btn-sm shadow-sm text-dark fw-semibold" style="background: #fef3c7; border: 1px solid #f59e0b;">
                        <i class="bi bi-printer me-1 text-warning"></i>បោះពុម្ពកិច្ចសន្យា
                    </a>
                    <a href="<?php echo e(route('employee-contracts.show', $latestContract)); ?>" class="btn btn-outline-warning btn-sm shadow-sm text-dark">
                        <i class="bi bi-file-earmark-text me-1"></i>កិច្ចសន្យា
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('employee-contracts.create', ['employee_id' => $employee->id])); ?>" class="btn btn-outline-warning btn-sm shadow-sm text-dark text-nowrap" style="white-space:nowrap;">
                        <i class="bi bi-file-earmark-plus me-1"></i>បង្កើតកិច្ចសន្យា
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('employees.id-card', $employee)); ?>" target="_blank" class="btn btn-outline-primary btn-sm shadow-sm">
                    <i class="bi bi-person-badge me-1"></i>បោះពុម្ពកាតបុគ្គលិក
                </a>
                <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="btn btn-primary-action btn-sm shadow-sm text-white" style="background: #e8a838; border-color: #e8a838;">
                    <i class="bi bi-pencil-square me-1"></i>កែប្រែព័ត៌មាន
                </a>
                <button type="button" class="btn btn-outline-danger btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteEmployeeModal">
                    <i class="bi bi-trash me-1"></i>លុប
                </button>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="metric-card">
            <div class="metric-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div>
                <div class="metric-value">
                    <?php echo e($employee->basic_salary ? '$' . number_format($employee->basic_salary, 2) : '—'); ?>

                </div>
                <div class="metric-label">ប្រាក់ខែគោលប្រចាំខែ</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="metric-card">
            <div class="metric-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <div class="metric-value">
                    <?php echo e($employee->employee_attendances->count()); ?>

                </div>
                <div class="metric-label">កំណត់ត្រាវត្តមាន</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="metric-card">
            <div class="metric-icon">
                <i class="bi bi-calendar2-check"></i>
            </div>
            <div>
                <div class="metric-value">
                    <?php echo e($employee->employee_leaves->count()); ?>

                </div>
                <div class="metric-label">ពាក្យសុំច្បាប់ឈប់សម្រាក</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="metric-card">
            <div class="metric-icon">
                <i class="bi bi-folder2-open"></i>
            </div>
            <div>
                <div class="metric-value">
                    <?php echo e($employee->employee_documents->count()); ?>

                </div>
                <div class="metric-label">ឯកសារភ្ជាប់</div>
            </div>
        </div>
    </div>
</div>


<ul class="nav nav-tabs employee-nav-tabs" id="employeeProfileTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
            <i class="bi bi-person-vcard me-1.5"></i>១. ផ្ទាល់ខ្លួន & ២. អាសយដ្ឋាន
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="employment-tab" data-bs-toggle="tab" data-bs-target="#employment" type="button" role="tab" aria-controls="employment" aria-selected="false">
            <i class="bi bi-briefcase me-1.5"></i>៣. ការងារ & ៤. ប្រាក់ខែ
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button" role="tab" aria-controls="education" aria-selected="false">
            <i class="bi bi-mortarboard me-1.5"></i>៥. ការសិក្សា & ជំនាញ
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency" type="button" role="tab" aria-controls="emergency" aria-selected="false">
            <i class="bi bi-heart-pulse me-1.5"></i>៦. ទំនាក់ទំនងបន្ទាន់
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab" aria-controls="attendance" aria-selected="false">
            <i class="bi bi-calendar-check me-1.5"></i>វត្តមាន
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="leaves-tab" data-bs-toggle="tab" data-bs-target="#leaves" type="button" role="tab" aria-controls="leaves" aria-selected="false">
            <i class="bi bi-airplane me-1.5"></i>ច្បាប់ឈប់សម្រាក
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">
            <i class="bi bi-files me-1.5"></i>ឯកសារ
        </button>
    </li>
</ul>


<div class="tab-content employee-tab-card" id="employeeProfileTabsContent">

    
    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
        <div class="row g-4">
            
            <div class="col-12">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="bi bi-person-lines-fill text-warning fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">១. ព័ត៌មានផ្ទាល់ខ្លួន</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">អត្តលេខបុគ្គលិក</div>
                            <div class="detail-value font-monospace text-warning fw-bold"><?php echo e($employee->employee_code); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">គោត្តនាម-នាម (ខ្មែរ)</div>
                            <div class="detail-value text-primary">
                                <?php echo e(trim(($employee->last_name_khmer ?? '') . ' ' . ($employee->first_name_khmer ?? '')) ?: '—'); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">គោត្តនាម-នាម (ឡាតាំង)</div>
                            <div class="detail-value">
                                <?php echo e(trim(($employee->last_name ?? '') . ' ' . ($employee->first_name ?? '')) ?: $userName); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ភេទ</div>
                            <div class="detail-value"><?php echo e($employee->gender ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ថ្ងៃខែឆ្នាំកំណើត</div>
                            <div class="detail-value">
                                <?php echo e($employee->date_of_birth ? $employee->date_of_birth->format('d/m/Y') : '—'); ?>

                                <?php if($age): ?> <span class="badge bg-light text-muted border ms-1"><?php echo e($age); ?> ឆ្នាំ</span> <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">លេខទូរស័ព្ទ</div>
                            <div class="detail-value">
                                <?php if($contact?->phone): ?>
                                    <a href="tel:<?php echo e($contact->phone); ?>" class="text-decoration-none text-dark fw-semibold">
                                        <i class="bi bi-telephone-outbound me-1 text-primary"></i><?php echo e($contact->phone); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">អ៊ីមែល</div>
                            <div class="detail-value">
                                <?php if($employee->user?->email || $contact?->email): ?>
                                    <a href="mailto:<?php echo e($employee->user?->email ?: $contact?->email); ?>" class="text-decoration-none text-primary">
                                        <i class="bi bi-envelope me-1"></i><?php echo e($employee->user?->email ?: $contact?->email); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ស្ថានភាពអាពាហ៍ពិពាហ៍</div>
                            <div class="detail-value"><?php echo e($employee->marital_status ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">អត្តសញ្ញាណប័ណ្ណ / លិខិតឆ្លងដែន</div>
                            <div class="detail-value font-monospace"><?php echo e($employee->national_id ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">សញ្ជាតិ</div>
                            <div class="detail-value"><?php echo e($employee->nationality ?: 'ខ្មែរ'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ទីកន្លែងកំណើត</div>
                            <div class="detail-value"><?php echo e($employee->place_of_birth ?: ($employee->birth_province ? implode(', ', array_filter([$employee->birth_village ? 'ភូមិ '.$employee->birth_village : null, $employee->birth_commune ? 'ឃុំ/សង្កាត់ '.$employee->birth_commune : null, $employee->birth_district ? 'ស្រុក/ខណ្ឌ '.$employee->birth_district : null, $employee->birth_province ? 'ខេត្ត/រាជធានី '.$employee->birth_province : null])) : '—')); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12 mt-2">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="bi bi-geo-alt text-warning fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">២. ព័ត៌មានអាសយដ្ឋាន</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">អាសយដ្ឋានបច្ចុប្បន្ន</div>
                            <div class="detail-value"><?php echo e($contact?->current_address ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">អាសយដ្ឋានអចិន្ត្រៃយ៍</div>
                            <div class="detail-value"><?php echo e($contact?->permanent_address ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ភូមិ</div>
                            <div class="detail-value"><?php echo e($contact?->village ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ឃុំ/សង្កាត់</div>
                            <div class="detail-value"><?php echo e($contact?->commune ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ស្រុក/ខណ្ឌ</div>
                            <div class="detail-value"><?php echo e($contact?->district ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ខេត្ត/រាជធានី</div>
                            <div class="detail-value"><?php echo e($contact?->province ?: '—'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">
        <div class="row g-4">
            
            <div class="col-12">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="bi bi-building-check text-warning fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">៣. ព័ត៌មានការងារ</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">អត្តលេខបុគ្គលិក</div>
                            <div class="detail-value font-monospace text-warning fw-bold"><?php echo e($employee->employee_code); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">តួនាទី / មុខតំណែង</div>
                            <div class="detail-value"><?php echo e($employee->employee_position->name ?? '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ផ្នែក</div>
                            <div class="detail-value"><?php echo e($employee->employee_department->name ?? '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ប្រភេទបុគ្គលិក</div>
                            <div class="detail-value">
                                <?php if($employee->employee_type): ?>
                                    <span class="badge bg-warning bg-opacity-20 text-dark border border-warning border-opacity-50 px-2 py-1">
                                        <?php echo e($employee->employee_type); ?>

                                    </span>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ថ្ងៃចូលបម្រើការងារ</div>
                            <div class="detail-value">
                                <?php echo e($employee->date_of_joining ? $employee->date_of_joining->format('d/m/Y') : '—'); ?>

                                <?php if($tenure): ?> <small class="text-muted d-block">(<?php echo e($tenure); ?>)</small> <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ស្ថានភាពការងារ</div>
                            <div class="detail-value">
                                <span class="badge <?php echo e($statusBadgeClass); ?> px-2 py-1 small">
                                    <?php echo e($statusKh); ?> (<?php echo e($statusName); ?>)
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">កិច្ចសន្យាការងារ</div>
                            <div class="detail-value"><?php echo e($employee->contract_type ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">អ្នកគ្រប់គ្រងផ្ទាល់</div>
                            <div class="detail-value"><?php echo e($employee->supervisor_name ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ទីតាំងបំពេញការងារ</div>
                            <div class="detail-value"><?php echo e($employee->work_location ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">លេខសម្គាល់គណនីប្រព័ន្ធ</div>
                            <div class="detail-value font-monospace">#<?php echo e($employee->user_id); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ស្ថានភាពគណនី</div>
                            <div class="detail-value">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2">សកម្ម</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12 mt-2">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="bi bi-cash-stack text-warning fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">៤. ព័ត៌មានប្រាក់ខែ</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ប្រាក់ខែគោល</div>
                            <div class="detail-value text-success fs-6 fw-bold">
                                <?php echo e($employee->basic_salary ? '$' . number_format($employee->basic_salary, 2) : '—'); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ប្រភេទប្រាក់ខែ</div>
                            <div class="detail-value"><?php echo e($employee->salary_type ?: 'ប្រចាំខែ'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ប្រាក់ឧបត្ថម្ភ</div>
                            <div class="detail-value text-info fw-semibold">
                                <?php echo e($employee->allowance ? '$' . number_format($employee->allowance, 2) : '$0.00'); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ប្រាក់កាត់កង</div>
                            <div class="detail-value text-danger fw-semibold">
                                <?php echo e($employee->deduction ? '$' . number_format($employee->deduction, 2) : '$0.00'); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">វិធីសាស្ត្របើកប្រាក់ខែ</div>
                            <div class="detail-value"><?php echo e($employee->payment_method ?: 'ផ្ទេរតាមធនាគារ'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ឈ្មោះធនាគារ</div>
                            <div class="detail-value"><?php echo e($employee->bank_name ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">លេខគណនីធនាគារ</div>
                            <div class="detail-value font-monospace"><?php echo e($employee->bank_account_number ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ឈ្មោះម្ចាស់គណនី</div>
                            <div class="detail-value"><?php echo e($employee->bank_account_name ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">កាលបរិច្ឆេទមានប្រសិទ្ធភាព</div>
                            <div class="detail-value">
                                <?php echo e($employee->salary_effective_date ? \Carbon\Carbon::parse($employee->salary_effective_date)->format('d/m/Y') : '—'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
        <div class="row g-4">
            <div class="col-12">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="bi bi-mortarboard text-warning fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">៥. ព័ត៌មានការសិក្សា និងជំនាញ</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">កម្រិតវប្បធម៌ខ្ពស់បំផុត</div>
                            <div class="detail-value text-primary fw-semibold"><?php echo e($employee->highest_qualification ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">សាកលវិទ្យាល័យ / គ្រឹះស្ថានសិក្សា</div>
                            <div class="detail-value"><?php echo e($employee->university ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ជំនាញឯកទេស</div>
                            <div class="detail-value"><?php echo e($employee->major ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ឆ្នាំបញ្ចប់ការសិក្សា</div>
                            <div class="detail-value font-monospace"><?php echo e($employee->graduation_year ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">គរុកោសល្យ / គរុវិជ្ជា</div>
                            <div class="detail-value"><?php echo e($employee->teaching_qualification ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="detail-label">វិញ្ញាបនបត្រវិជ្ជាជីវៈ</div>
                            <div class="detail-value"><?php echo e($employee->professional_certificate ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">បទពិសោធន៍ការងារ</div>
                            <div class="detail-value">
                                <?php if($employee->experience_years): ?>
                                    <span class="badge bg-info bg-opacity-10 text-dark border px-2 py-1">
                                        <i class="bi bi-clock-history me-1"></i><?php echo e($employee->experience_years); ?> ឆ្នាំ
                                    </span>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <div class="detail-box">
                            <div class="detail-label">ជំនាញ និងសមត្ថភាព</div>
                            <div class="detail-value"><?php echo e($employee->skills ?: '—'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="tab-pane fade" id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
        <div class="row g-4">
            <div class="col-12">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="bi bi-heart-pulse text-danger fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">៦. ទំនាក់ទំនងបន្ទាន់</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-4 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ឈ្មោះអ្នកទំនាក់ទំនងបន្ទាន់</div>
                            <div class="detail-value fw-bold text-dark"><?php echo e($contact?->emergency_contact_name ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">ត្រូវជា / ទំនាក់ទំនង</div>
                            <div class="detail-value text-primary"><?php echo e($contact?->emergency_relationship ?: '—'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="detail-box">
                            <div class="detail-label">លេខទូរស័ព្ទបន្ទាន់</div>
                            <div class="detail-value">
                                <?php if($contact?->emergency_phone): ?>
                                    <a href="tel:<?php echo e($contact->emergency_phone); ?>" class="text-decoration-none text-danger fw-bold">
                                        <i class="bi bi-telephone-fill me-1 text-danger"></i><?php echo e($contact->emergency_phone); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-box">
                            <div class="detail-label">អាសយដ្ឋានអ្នកទំនាក់ទំនងបន្ទាន់</div>
                            <div class="detail-value"><?php echo e($contact?->emergency_address ?: '—'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-dark mb-0">កំណត់ត្រាវត្តមានថ្មីៗ (១០ កំណត់ត្រាចុងក្រោយ)</h6>
            <a href="<?php echo e(route('staff-attendance.index')); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-box-arrow-up-right me-1"></i>មើលបញ្ជីវត្តមានពេញលេញ
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="small text-uppercase text-muted">កាលបរិច្ឆេទ</th>
                        <th class="small text-uppercase text-muted">ស្ថានភាព</th>
                        <th class="small text-uppercase text-muted">ម៉ោងចូល</th>
                        <th class="small text-uppercase text-muted">ម៉ោងចេញ</th>
                        <th class="small text-uppercase text-muted">សម្គាល់</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $employee->employee_attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $attStatus = strtolower($att->attendance_status ?? 'present');
                            $attBadge = match(true) {
                                str_contains($attStatus, 'present') => 'bg-success text-white',
                                str_contains($attStatus, 'late') => 'bg-warning text-dark',
                                str_contains($attStatus, 'absent') => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };
                            $attKh = match(true) {
                                str_contains($attStatus, 'present') => 'វត្តមាន',
                                str_contains($attStatus, 'late') => 'មកយឺត',
                                str_contains($attStatus, 'absent') => 'អវត្តមាន',
                                str_contains($attStatus, 'leave') => 'ច្បាប់',
                                default => ucfirst($attStatus)
                            };
                        ?>
                        <tr>
                            <td class="fw-semibold">
                                <?php echo e($att->attendance_date ? \Carbon\Carbon::parse($att->attendance_date)->format('d/m/Y') : '—'); ?>

                            </td>
                            <td>
                                <span class="badge <?php echo e($attBadge); ?> px-2 py-0.5 rounded-pill small">
                                    <?php echo e($attKh); ?>

                                </span>
                            </td>
                            <td class="small"><?php echo e($att->in_time ? \Carbon\Carbon::parse($att->in_time)->format('h:i A') : '—'); ?></td>
                            <td class="small"><?php echo e($att->out_time ? \Carbon\Carbon::parse($att->out_time)->format('h:i A') : '—'); ?></td>
                            <td class="small text-muted"><?php echo e($att->remarks ?: '—'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-x fs-3 d-block mb-1"></i>
                                មិនទាន់មានកំណត់ត្រាវត្តមានសម្រាប់បុគ្គលិកនេះនៅឡើយទេ។
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="tab-pane fade" id="leaves" role="tabpanel" aria-labelledby="leaves-tab">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-dark mb-0">ពាក្យសុំច្បាប់ឈប់សម្រាកថ្មីៗ</h6>
            <a href="<?php echo e(route('staff-leaves.index')); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-box-arrow-up-right me-1"></i>មើលទិន្នន័យច្បាប់ឈប់សម្រាកទាំងអស់
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="small text-uppercase text-muted">ប្រភេទច្បាប់</th>
                        <th class="small text-uppercase text-muted">ចន្លោះកាលបរិច្ឆេទ</th>
                        <th class="small text-uppercase text-muted">ចំនួនថ្ងៃ</th>
                        <th class="small text-uppercase text-muted">ស្ថានភាព</th>
                        <th class="small text-uppercase text-muted">មូលហេតុ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $employee->employee_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $leaveStatus = strtolower($leave->status ?? 'pending');
                            $leaveBadge = match(true) {
                                str_contains($leaveStatus, 'approved') => 'bg-success text-white',
                                str_contains($leaveStatus, 'rejected') => 'bg-danger text-white',
                                default => 'bg-warning text-dark'
                            };
                            $leaveKh = match(true) {
                                str_contains($leaveStatus, 'approved') => 'បានអនុម័ត',
                                str_contains($leaveStatus, 'rejected') => 'បដិសេធ',
                                default => 'រង់ចាំពិនិត្យ'
                            };
                        ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($leave->leave_type->name ?? 'ច្បាប់ទូទៅ'); ?></td>
                            <td class="small">
                                <?php echo e($leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') : '—'); ?>

                                ដល់
                                <?php echo e($leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') : '—'); ?>

                            </td>
                            <td class="small font-monospace"><?php echo e($leave->total_days ?? 1); ?></td>
                            <td>
                                <span class="badge <?php echo e($leaveBadge); ?> px-2 py-0.5 rounded-pill small">
                                    <?php echo e($leaveKh); ?>

                                </span>
                            </td>
                            <td class="small text-muted"><?php echo e($leave->reason ?: '—'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-cup-hot fs-3 d-block mb-1"></i>
                                មិនមានទិន្នន័យពាក្យសុំច្បាប់ឈប់សម្រាកនៅឡើយទេ។
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-dark mb-0">ឯកសារភ្ជាប់របស់បុគ្គលិក</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="small text-uppercase text-muted">ប្រភេទឯកសារ</th>
                        <th class="small text-uppercase text-muted">ទីតាំងឯកសារ</th>
                        <th class="small text-uppercase text-muted">កាលបរិច្ឆេទផ្ទុកឡើង</th>
                        <th class="small text-uppercase text-muted text-end">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $employee->employee_documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold">
                                <i class="bi bi-file-earmark-pdf me-1 text-danger"></i>
                                <?php echo e($doc->document_type ?? 'ឯកសារបុគ្គលិក'); ?>

                            </td>
                            <td class="text-muted font-monospace small"><?php echo e($doc->document_path); ?></td>
                            <td class="small text-muted"><?php echo e($doc->created_at ? $doc->created_at->format('d/m/Y') : '—'); ?></td>
                            <td class="text-end">
                                <?php if(!empty($doc->document_path)): ?>
                                    <a href="<?php echo e(asset('storage/' . $doc->document_path)); ?>" target="_blank" class="btn btn-outline-primary btn-sm py-0.5 px-2">
                                        <i class="bi bi-download me-1"></i>មើល
                                    </a>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-folder-x fs-3 d-block mb-1"></i>
                                មិនទាន់មានឯកសារភ្ជាប់សម្រាប់បុគ្គលិកនេះនៅឡើយទេ។
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>


<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-lg, 12px);">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger" style="width: 64px; height: 64px;">
                        <i class="bi bi-exclamation-triangle fs-2"></i>
                    </span>
                </div>
                <h5 class="fw-bold mb-2 text-dark">លុបប្រវត្តិរូបបុគ្គលិក?</h5>
                <p class="text-muted small mb-4">
                    តើអ្នកពិតជាចង់លុបបុគ្គលិក <strong class="text-dark"><?php echo e($userName); ?></strong> (<span class="font-monospace text-danger fw-semibold"><?php echo e($employee->employee_code); ?></span>) នេះមែនទេ?<br>
                    ទិន្នន័យបុគ្គលិកនឹងត្រូវលុប ប៉ុន្តែប្រវត្តិនៃវត្តមាន កិច្ចសន្យា និងប្រាក់បៀវត្សរ៍នឹងនៅរក្សាទុកដោយសុវត្ថិភាព។
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">បោះបង់</button>
                    <form method="POST" action="<?php echo e(route('employees.destroy', $employee)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm px-4 shadow-sm">
                            <i class="bi bi-trash me-1"></i>យល់ព្រមលុបបុគ្គលិក
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/employees/employee/show.blade.php ENDPATH**/ ?>