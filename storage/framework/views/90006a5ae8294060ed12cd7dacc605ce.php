<?php $__env->startSection('title', 'បញ្ជីបុគ្គលិក — School Management System'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('students.index')); ?>" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">គ្រប់គ្រងបុគ្គលិក</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បុគ្គលិក</li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ═══════════════════════════════════════════════════════════════════
   PAGE HERO BANNER
═══════════════════════════════════════════════════════════════════ */
.employee-banner {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border-radius: var(--radius-lg, 12px);
    padding: 0.75rem 1.25rem;
    margin-bottom: 0.5rem;
    position: relative;
    overflow: hidden;
    color: #fff;
    box-shadow: 0 4px 15px rgba(232, 168, 56, 0.18);
}
.employee-banner::before {
    content: '';
    position: absolute;
    top: -40px;
    right: -40px;
    width: 170px;
    height: 170px;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 50%;
    pointer-events: none;
}
.employee-banner::after {
    content: '';
    position: absolute;
    bottom: -35px;
    right: 120px;
    width: 110px;
    height: 110px;
    background: rgba(255, 255, 255, 0.06);
    border-radius: 50%;
    pointer-events: none;
}
.employee-banner .banner-icon {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-md, 8px);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: #fff;
    margin-bottom: 0.25rem;
}
.employee-banner h1 {
    font-size: 1.05rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.15rem;
    letter-spacing: -0.02em;
}
.employee-banner p {
    font-size: 0.76rem;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
}
.employee-banner .banner-actions {
    position: absolute;
    top: 0.75rem;
    right: 1rem;
    z-index: 2;
    display: flex;
    gap: 0.5rem;
}
.employee-banner .banner-actions .btn {
    font-size: 0.78rem !important;
    padding: 0.25rem 0.65rem !important;
}

/* ═══════════════════════════════════════════════════════════════════
   METRIC SUMMARY CARDS
═══════════════════════════════════════════════════════════════════ */
.metric-stat-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-top: 3px solid #e8a838;
    border-radius: var(--radius-md, 10px);
    padding: 0.75rem 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    display: flex;
    align-items: center;
    gap: 0.85rem;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.metric-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(232, 168, 56, 0.1);
}
.metric-stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
    background: #fef3c7;
    color: #b45309;
}
.metric-stat-val {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--text-dark, #0f172a);
    line-height: 1.15;
}
.metric-stat-label {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text-muted, #64748b);
    margin-top: 0.1rem;
    line-height: 1.3;
}

/* ═══════════════════════════════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════════════════════════════ */
.filter-bar {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: var(--radius-lg, 12px);
    padding: 0.35rem 0.65rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.filter-inputs {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex-wrap: wrap;
}
.search-wrapper {
    position: relative;
    min-width: 240px;
}
.search-wrapper i {
    position: absolute;
    left: 0.65rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
    font-size: 0.8rem;
}
.search-wrapper input {
    width: 100%;
    padding: 0.25rem 0.65rem 0.25rem 1.85rem;
    font-size: 0.8rem;
    height: 32px;
    min-height: 32px;
    border: 1px solid #cbd5e1;
    border-radius: var(--radius-md, 8px);
    transition: all 0.2s ease;
    background-color: #fff;
    line-height: 1.3;
}
.search-wrapper input:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 2px rgba(232, 168, 56, 0.15);
    outline: none;
}
.filter-select {
    font-size: 0.8rem;
    padding: 0.25rem 1.8rem 0.25rem 0.65rem;
    height: 32px;
    min-height: 32px;
    border: 1px solid #cbd5e1;
    border-radius: var(--radius-md, 8px);
    background-color: #fff;
    color: #1e293b;
    transition: all 0.15s ease;
    line-height: 1.3;
}
.filter-select:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 2px rgba(232, 168, 56, 0.15);
    outline: none;
}

/* ═══════════════════════════════════════════════════════════════════
   DATA TABLE
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-top: 3px solid #e8a838;
    border-radius: var(--radius-lg, 12px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03), 0 4px 12px rgba(0, 0, 0, 0.02);
}
.table-custom {
    margin-bottom: 0;
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.table-custom thead th {
    background: #fffbeb;
    color: #78350f;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.45rem 0.75rem;
    border-bottom: 1px solid #fde68a;
    white-space: nowrap;
}
.table-custom tbody tr {
    transition: background-color 0.15s ease;
}
.table-custom tbody tr:hover {
    background-color: #fffdf5;
}
.table-custom tbody td {
    padding: 0.45rem 0.75rem;
    font-size: 0.82rem;
    color: #1e293b;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.table-custom tbody tr:last-child td {
    border-bottom: none;
}

/* Staff Avatar & Profile */
.employee-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e8a838;
    color: #fff;
    font-size: 0.85rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 4px rgba(232, 168, 56, 0.2);
}
.employee-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.employee-name {
    font-weight: 600;
    color: #0f172a;
    text-decoration: none;
    transition: color 0.15s ease;
}
.employee-name:hover {
    color: #c98b1e;
}
.employee-code-badge {
    font-size: 0.72rem;
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    color: #b45309;
    background: #fef3c7;
    border: 1px solid #fde68a;
    border-radius: 4px;
    padding: 0.1rem 0.4rem;
    font-weight: 600;
}

/* Action Buttons */
.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: var(--radius-sm, 6px);
    color: #64748b;
    transition: all 0.15s ease;
    background: transparent;
    border: 1px solid transparent;
    text-decoration: none;
    font-size: 0.82rem;
}
.action-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #cbd5e1;
}
.action-btn-view:hover {
    background: #fef3c7;
    color: #b45309;
    border-color: #fde68a;
}
.action-btn-edit:hover {
    background: #fffbeb;
    color: #92400e;
    border-color: #fde68a;
}
.action-btn-danger:hover {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fca5a5;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="employee-banner">
    <div class="banner-actions">
        <a href="<?php echo e(route('employees.create')); ?>" class="btn btn-sm text-white shadow-sm" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.35);">
            <i class="bi bi-person-plus-fill me-1.5"></i>+ បន្ថែមបុគ្គលិកថ្មី
        </a>
    </div>
    <div class="banner-icon"><i class="bi bi-people-fill"></i></div>
    <h1>បញ្ជីព័ត៌មានបុគ្គលិក និងលោកគ្រូអ្នកគ្រូ</h1>
    <p>បញ្ជីរាយនាមបុគ្គលិកបង្រៀន រដ្ឋបាល និងបុគ្គលិកជំនួយការទាំងអស់។</p>
</div>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-2 shadow-sm" role="alert" style="border-radius: var(--radius-md, 8px);">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div class="flex-grow-1"><?php echo e(session('success')); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-2 shadow-sm" role="alert" style="border-radius: var(--radius-md, 8px);">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
        <div class="flex-grow-1"><?php echo e(session('error')); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<div class="row g-3 mb-3">
    <div class="col-xl-3 col-sm-6">
        <div class="metric-stat-card">
            <div class="metric-stat-icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <div>
                <div class="metric-stat-val"><?php echo e($stats['total'] ?? $employees->total()); ?></div>
                <div class="metric-stat-label">បុគ្គលិកសរុប</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="metric-stat-card">
            <div class="metric-stat-icon">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div>
                <div class="metric-stat-val"><?php echo e($stats['active'] ?? '—'); ?></div>
                <div class="metric-stat-label">បុគ្គលិកសកម្ម</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="metric-stat-card">
            <div class="metric-stat-icon">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="metric-stat-val"><?php echo e($stats['departments'] ?? $departments->count()); ?></div>
                <div class="metric-stat-label">ផ្នែក</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="metric-stat-card">
            <div class="metric-stat-icon">
                <i class="bi bi-briefcase"></i>
            </div>
            <div>
                <div class="metric-stat-val"><?php echo e($stats['positions'] ?? '—'); ?></div>
                <div class="metric-stat-label">មុខតំណែង</div>
            </div>
        </div>
    </div>
</div>


<div class="filter-bar mb-2">
    <form action="<?php echo e(route('employees.index')); ?>" method="GET" class="filter-inputs m-0 w-100">
        
        <div class="search-wrapper flex-grow-1">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="ស្វែងរកតាមឈ្មោះ, អត្តលេខ, អ៊ីមែល ឬលេខទូរស័ព្ទ...">
        </div>

        
        <select name="department_id" class="form-select filter-select" style="width: auto;" onchange="this.form.submit()">
            <option value="">ផ្នែកទាំងអស់</option>
            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($dept->id); ?>" <?php echo e(request('department_id') == $dept->id ? 'selected' : ''); ?>>
                    <?php echo e($dept->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        
        
        <select name="status_id" class="form-select filter-select" style="width: auto;" onchange="this.form.submit()">
            <option value="">ស្ថានភាពទាំងអស់</option>
            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($stat->id); ?>" <?php echo e(request('status_id') == $stat->id ? 'selected' : ''); ?>>
                    <?php echo e($stat->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <button type="submit" class="btn btn-sm btn-outline-secondary px-2.5 d-inline-flex align-items-center" style="height: 32px; font-size: 0.8rem;">
            <i class="bi bi-funnel me-1"></i>ស្វែងរក
        </button>

        
        <?php if(request()->anyFilled(['search', 'department_id', 'status_id'])): ?>
            <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-sm btn-outline-danger px-2.5 d-inline-flex align-items-center" style="height: 32px; font-size: 0.8rem;">
                <i class="bi bi-x-circle me-1"></i>កំណត់ឡើងវិញ
            </a>
        <?php endif; ?>
    </form>
</div>


<div class="table-card">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>ប្រវត្តិរូបបុគ្គលិក</th>
                    <th>ផ្នែក</th>
                    <th>តួនាទី / មុខតំណែង</th>
                    <th>ព័ត៌មានទំនាក់ទំនង</th>
                    <th>កាលបរិច្ឆេទចូលបម្រើការងារ</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $name = $employee->user->name ?? $employee->name ?? 'មិនស្គាល់ឈ្មោះបុគ្គលិក';
                        $nameArr = explode(' ', trim($name));
                        $initials = strtoupper(substr($nameArr[0], 0, 1) . (isset($nameArr[1]) ? substr($nameArr[1], 0, 1) : ''));
                        
                        $rawStatus = $employee->employee_status->name ?? 'Active';
                        $statusLower = strtolower($rawStatus);
                        $statusText = $employee->employee_status?->khmer_name ?? \App\Models\Employees\EmployeeStatus::translateToKhmer($rawStatus);
                        $badgeClass = match(true) {
                            str_contains($statusLower, 'active') => 'bg-success text-white',
                            str_contains($statusLower, 'leave') => 'bg-warning text-dark',
                            str_contains($statusLower, 'inactive'), str_contains($statusLower, 'terminated'), str_contains($statusLower, 'resign') => 'bg-danger text-white',
                            default => 'bg-secondary text-white'
                        };
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="employee-avatar overflow-hidden position-relative">
                                    <?php if($employee->photo_url): ?>
                                        <img src="<?php echo e($employee->photo_url); ?>" alt="<?php echo e($name); ?>" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                        <span class="d-none"><?php echo e($initials ?: 'EM'); ?></span>
                                    <?php else: ?>
                                        <?php echo e($initials ?: 'EM'); ?>

                                    <?php endif; ?>
                                </div>
                                <div>
                                    <a href="<?php echo e(route('employees.show', $employee)); ?>" class="employee-name d-block mb-0.5">
                                        <?php echo e($name); ?>

                                    </a>
                                    <span class="employee-code-badge">
                                        <?php echo e($employee->employee_code); ?>

                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2.5 py-1">
                                <i class="bi bi-building me-1 text-muted"></i><?php echo e($employee->employee_department->name ?? '—'); ?>

                            </span>
                        </td>
                        <td>
                            <span class="fw-medium text-dark"><?php echo e($employee->employee_position->name ?? '—'); ?></span>
                        </td>
                        <td>
                            <div class="small">
                                <?php if($employee->employeeContact?->phone): ?>
                                    <div><i class="bi bi-telephone me-1 text-teal"></i><?php echo e($employee->employeeContact->phone); ?></div>
                                <?php endif; ?>
                                <?php if($employee->user?->email): ?>
                                    <div class="text-muted"><i class="bi bi-envelope me-1"></i><?php echo e($employee->user->email); ?></div>
                                <?php endif; ?>
                                <?php if(!$employee->employeeContact?->phone && !$employee->user?->email): ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="small text-muted">
                            <?php echo e($employee->date_of_joining ? $employee->date_of_joining->format('M d, Y') : '—'); ?>

                        </td>
                        <td>
                            <span class="badge <?php echo e($badgeClass); ?> px-2.5 py-1 rounded-pill small">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i><?php echo e($statusText); ?>

                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="<?php echo e(route('employees.id-card', $employee)); ?>" target="_blank" class="action-btn" style="color:#0284c7" data-bs-toggle="tooltip" title="បោះពុម្ពកាតបុគ្គលិក">
                                    <i class="bi bi-person-badge"></i>
                                </a>
                                <a href="<?php echo e(route('employees.show', $employee)); ?>" class="action-btn action-btn-view" data-bs-toggle="tooltip" title="មើលព័ត៌មានលម្អិត">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="action-btn action-btn-edit" data-bs-toggle="tooltip" title="កែសម្រួល">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="action-btn action-btn-danger" 
                                        data-bs-toggle="tooltip" title="លុប"
                                        onclick="confirmDelete(<?php echo e($employee->id); ?>, '<?php echo e(addslashes($name)); ?>', '<?php echo e(addslashes($employee->employee_code)); ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted mb-1"><i class="bi bi-people fs-2" style="color: #e8a838; opacity: 0.65;"></i></div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.92rem;">រកមិនឃើញទិន្នន័យបុគ្គលិកទេ</h6>
                            <p class="text-muted small mb-2.5" style="font-size: 0.78rem;">មិនមានបុគ្គលិកត្រូវនឹងការច្រោះស្វែងរករបស់អ្នក ឬមិនទាន់មានការចុះឈ្មោះបុគ្គលិកនៅឡើយទេ។</p>
                            <a href="<?php echo e(route('employees.create')); ?>" class="btn btn-sm btn-primary-action text-white shadow-sm" style="background: #e8a838; border-color: #e8a838; font-size: 0.78rem; padding: 0.3rem 0.75rem;">
                                <i class="bi bi-plus-circle me-1"></i>+ បន្ថែមបុគ្គលិកដំបូង
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($employees->hasPages()): ?>
        <div class="p-3 border-top bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="text-muted small">
                បង្ហាញពី <?php echo e($employees->firstItem()); ?> ដល់ <?php echo e($employees->lastItem()); ?> នៃ <?php echo e($employees->total()); ?> នាក់
            </div>
            <div>
                <?php echo e($employees->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-lg, 12px);">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger" style="width: 64px; height: 64px;">
                        <i class="bi bi-exclamation-triangle fs-2"></i>
                    </span>
                </div>
                <h5 class="fw-bold mb-1 text-dark">លុបប្រវត្តិរូបបុគ្គលិក?</h5>
                <p class="text-muted small mb-4">
                    តើអ្នកពិតជាចង់លុបបុគ្គលិក <strong class="text-dark" id="deleteEmployeeName"></strong> (<span id="deleteEmployeeCode" class="font-monospace text-danger fw-semibold"></span>) នេះមែនទេ?<br>
                    សកម្មភាពនេះនឹងផ្អាកគណនី និងលុបចេញពីបញ្ជី ប៉ុន្តែនៅរក្សាទុកប្រវត្តិនៃវត្តមាន និងប្រាក់បៀវត្សរ៍ដដែល។
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">បោះបង់</button>
                    <form id="deleteForm" method="POST" action="">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm px-4 shadow-sm">
                            <i class="bi bi-trash me-1"></i>យល់ព្រមលុប
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });

    function confirmDelete(id, name, code) {
        document.getElementById('deleteEmployeeName').textContent = name;
        document.getElementById('deleteEmployeeCode').textContent = code;
        document.getElementById('deleteForm').action = `<?php echo e(url('employees')); ?>/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel Project\laravel\resources\views/employees/employee/index.blade.php ENDPATH**/ ?>