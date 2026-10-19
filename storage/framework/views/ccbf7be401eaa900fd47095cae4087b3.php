<?php $__env->startSection('title', 'គ្រប់គ្រងកាលវិភាគ — School Management System'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('students.index')); ?>" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កាលវិភាគសិក្សា</li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ═══════════════════════════════════════════════════════════════════
   PAGE HEADER
═══════════════════════════════════════════════════════════════════ */
.page-title {
    font-size: 1.4rem; font-weight: 800; color: var(--text-dark);
    margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.6rem;
}
.page-title-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; background: #ecfdf5; color: #10b981;
    border-radius: var(--radius-md); font-size: 1.15rem;
}
.page-subtitle { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0; }

/* ═══════════════════════════════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════════════════════════════ */
.filter-bar {
    background: #fff; border: 1px solid var(--border-soft); border-radius: 0px !important;
    padding: 0.5rem 0.85rem; display: flex; align-items: center; justify-content: space-between;
    gap: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.filter-inputs { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.search-wrapper { position: relative; width: 240px; }
.search-wrapper i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem; }
.search-wrapper input {
    width: 100%; padding: 0.35rem 0.75rem 0.35rem 2rem; font-size: 0.875rem; min-height: 36px; height: 36px;
    border: 1px solid var(--border); border-radius: 0px !important; transition: all 0.2s;
    line-height: 1.4;
}
.search-wrapper input:focus { border-color: var(--gold); box-shadow: 0 0 0 2px var(--gold-subtle); outline: none; }
.filter-select {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    font-size: 0.85rem !important;
    min-height: 36px !important;
    height: 36px !important;
    line-height: 1.4 !important;
    padding: 0.35rem 2rem 0.35rem 0.75rem !important;
    border: 1px solid var(--border) !important;
    border-radius: 0px !important;
    background-color: #fff !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.7rem center !important;
    background-size: 13px 9px !important;
    vertical-align: middle !important;
    cursor: pointer;
}
.filter-select::-ms-expand {
    display: none !important;
}
.filter-select:focus { border-color: var(--gold); box-shadow: 0 0 0 2px var(--gold-subtle); outline: none; }

/* ═══════════════════════════════════════════════════════════════════
   DATA TABLE
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: #fff; border: 1px solid var(--border-soft); border-radius: 0px !important;
    overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.table-custom { margin-bottom: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
.table-custom thead th {
    background: #f8fafc; color: var(--text-muted); font-size: 0.875rem;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px;
    padding: 0.5rem 0.65rem; border-bottom: 1px solid var(--border-soft); white-space: nowrap;
    line-height: 1.4;
}
.table-custom tbody tr { transition: background-color 0.15s; }
.table-custom tbody tr:hover { background-color: #fafbfc; }
.table-custom tbody td {
    padding: 0.45rem 0.65rem; font-size: 0.875rem; color: var(--text-dark);
    border-bottom: 1px solid var(--border-soft); vertical-align: middle;
    line-height: 1.4;
}
.table-custom tbody tr:last-child td { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════════
   CUSTOM UI COMPONENTS
═══════════════════════════════════════════════════════════════════ */
.class-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;
    border-radius: 4px; padding: 0.18rem 0.55rem;
    font-weight: 700; font-size: 0.85rem; letter-spacing: 0.1px;
    line-height: 1.3;
}
.year-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    background: #f1f5f9; color: #475569; border-radius: 4px;
    padding: 0.15rem 0.45rem; font-size: 0.82rem; font-weight: 600;
    line-height: 1.3;
}
.metric-pill {
    display: inline-flex; align-items: center; gap: 0.35rem;
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;
    padding: 0.15rem 0.5rem; white-space: nowrap;
}
.metric-val { font-weight: 700; font-size: 0.85rem; color: var(--text-dark); line-height: 1.2; }
.metric-lbl { font-size: 0.75rem; color: var(--text-muted); }

/* Table Actions */
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: 4px;
    color: var(--text-muted); transition: all 0.2s; background: transparent; border: none; font-size: 0.85rem;
}
.action-btn:hover { background: #f1f5f9; color: var(--text-dark); }
.action-btn-danger:hover { background: #fef2f2; color: #dc2626; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">
            <div class="page-title-icon"><i class="bi bi-calendar-week"></i></div>
            គ្រប់គ្រងកាលវិភាគសិក្សា
        </h1>
        <p class="page-subtitle">ពិនិត្យមើល និងកំណត់កាលវិភាគមេសម្រាប់គ្រប់ថ្នាក់រៀនទាំងអស់</p>
    </div>
    <?php if(auth()->user()?->canAccess('timetables.create')): ?>
    <div>
        <a href="<?php echo e(route('timetables.create')); ?>" class="btn btn-primary-action shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>បង្កើតកាលវិភាគ
        </a>
    </div>
    <?php endif; ?>
</div>

<?php if(session('success')): ?>
    <div class="alert-card alert-card-success mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span><?php echo e(session('success')); ?></span>
    </div>
<?php endif; ?>


<div class="filter-bar mb-4">
    <form action="<?php echo e(route('timetables.index')); ?>" method="GET" class="filter-inputs m-0 w-100">
        
        
        <div class="search-wrapper flex-grow-1" style="max-width: 350px;">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="ស្វែងរកតាមឈ្មោះថ្នាក់រៀន...">
        </div>

        
        <select name="academic_year_id" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
            <option value="">ឆ្នាំសិក្សាទាំងអស់</option>
            <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($year->id); ?>" <?php echo e(request('academic_year_id') == $year->id ? 'selected' : ''); ?>>
                    <?php echo e($year->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="shift" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
            <option value="">គ្រប់វេនទាំងអស់</option>
            <option value="morning" <?php echo e(request('shift') == 'morning' ? 'selected' : ''); ?>>☀️ វេនពេលព្រឹក / ពេលថ្ងៃ (07:00 – 10:00)</option>
            <option value="afternoon" <?php echo e(request('shift') == 'afternoon' ? 'selected' : ''); ?>>⛅ វេនពេលរសៀល (13:00 – 16:00)</option>
        </select>

        
        <?php if(request()->anyFilled(['search', 'academic_year_id', 'shift'])): ?>
            <a href="<?php echo e(route('timetables.index')); ?>" class="btn btn-sm btn-ghost ms-2 text-danger" style="font-size:0.75rem">
                <i class="bi bi-x-circle me-1"></i>សម្អាតការស្វែងរក
            </a>
        <?php endif; ?>
    </form>
</div>


<div class="table-card">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th style="width:45px" class="text-center">ល.រ</th>
                    <th style="width:20%">ថ្នាក់រៀន</th>
                    <th style="width:28%">វេនសិក្សា</th>
                    <th style="width:18%">ឆមាស / ត្រីមាស</th>
                    <th style="width:16%">ឆ្នាំសិក្សា</th>
                    <th style="width:95px" class="text-center">ចំនួនម៉ោង/ខែ</th>
                    <th style="width:85px" class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $timetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $hasMorning = $timetable->timetable_entries->contains(fn($e) => $e->time_slot && \Carbon\Carbon::parse($e->time_slot->start_time)->hour < 12);
                        $hasAfternoon = $timetable->timetable_entries->contains(fn($e) => $e->time_slot && \Carbon\Carbon::parse($e->time_slot->start_time)->hour >= 12);
                        $className = ($timetable->class->grade->name ?? '') . ' ' . ($timetable->class->section->name ?? '');
                        $isAfternoon = str_contains($className, 'រសៀល') || (!$hasMorning && $hasAfternoon);

                        $termName = $timetable->school_term->name ?? '—';
                        $termMap = [
                            'First Term' => 'ឆមាសទី ១',
                            'Second Term' => 'ឆមាសទី ២',
                            'Third Term' => 'ឆមាសទី ៣',
                            'Term 1' => 'ឆមាសទី ១',
                            'Term 2' => 'ឆមាសទី ២',
                            'Term 3' => 'ឆមាសទី ៣',
                            'Semester 1' => 'ឆមាសទី ១',
                            'Semester 2' => 'ឆមាសទី ២',
                        ];
                        $displayTerm = $termMap[$termName] ?? $termName;
                    ?>
                    <tr>
                        <td class="text-muted text-center" style="font-size:0.85rem">#<?php echo e($timetable->id); ?></td>
                        <td>
                            <div class="class-badge">
                                <?php echo e($timetable->class->grade->name ?? '—'); ?> — <?php echo e($timetable->class->section->name ?? '—'); ?>

                            </div>
                        </td>
                        <td>
                            <?php if($isAfternoon): ?>
                                <span class="badge px-2.5 py-1 fw-semibold" style="font-size:0.82rem; background: #fff7ed; color: #9a3412; border: 1px solid #fdba74; border-radius: 4px;">
                                    <i class="bi bi-cloud-sun-fill me-1" style="color: #ea580c;"></i> វេនពេលរសៀល (13:00 – 16:00)
                                </span>
                            <?php else: ?>
                                <span class="badge px-2.5 py-1 fw-semibold" style="font-size:0.82rem; background: #fef3c7; color: #78350f; border: 1px solid #fde68a; border-radius: 4px;">
                                    <i class="bi bi-sun-fill me-1" style="color: #b45309;"></i> វេនពេលព្រឹក / ពេលថ្ងៃ (07:00 – 10:00)
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="fw-medium text-dark"><?php echo e($displayTerm); ?></span>
                        </td>
                        <td>
                            <span class="year-badge">
                                <i class="bi bi-calendar3"></i>
                                <?php echo e($timetable->academic_year->name ?? '—'); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge border fw-semibold" style="background:#f0fdfa; border-color:#ccfbf1; color:#0f766e; font-size: 0.82rem; padding: 0.2rem 0.55rem;" title="<?php echo e($timetable->timetable_entries_count ?? 0); ?> ម៉ោង/សប្ដាហ៍">
                                <?php echo e(($timetable->timetable_entries_count ?? 0) * 4); ?> ម៉ោង/ខែ
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="<?php echo e(route('timetables.show', $timetable)); ?>" class="action-btn" data-bs-toggle="tooltip" title="មើលកាលវិភាគ">
                                    <i class="bi bi-calendar-event"></i>
                                </a>
                                <?php if(auth()->user()?->canAccess('timetables.edit')): ?>
                                <a href="<?php echo e(route('timetables.edit', $timetable)); ?>" class="action-btn" data-bs-toggle="tooltip" title="កែសម្រួល">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php endif; ?>
                                <?php if(auth()->user()?->canAccess('timetables.delete')): ?>
                                <button type="button" class="action-btn action-btn-danger" 
                                        data-bs-toggle="tooltip" title="លុបកាលវិភាគ"
                                        onclick="confirmDelete(<?php echo e($timetable->id); ?>, '<?php echo e(addslashes($timetable->class->grade->name ?? '')); ?> — <?php echo e(addslashes($timetable->class->section->name ?? '')); ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-calendar-x fs-1"></i></div>
                            <h6 class="fw-bold text-dark">មិនមានកាលវិភាគឡើយ</h6>
                            <p class="text-muted small mb-0">សូមសាកល្បងផ្លាស់ប្តូរការច្រោះស្វែងរក ឬបង្កើតកាលវិភាគថ្មី។</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($timetables->hasPages()): ?>
        <div class="p-3 border-top bg-light">
            <?php echo e($timetables->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:56px;height:56px">
                        <i class="bi bi-trash text-danger fs-3"></i>
                    </span>
                </div>
                <h6 class="fw-bold mb-1">លុបកាលវិភាគ?</h6>
                <p class="text-muted small mb-4">
                    តើអ្នកពិតជាចង់លុបកាលវិភាគសម្រាប់ថ្នាក់ <strong id="deleteClassName"></strong> មែនទេ? រាល់ម៉ោងបង្រៀនទាំងអស់នឹងត្រូវបាត់បង់។
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <form id="deleteForm" method="POST" action="">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger px-3">លុប</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    function confirmDelete(id, name) {
        document.getElementById('deleteClassName').textContent = name;
        document.getElementById('deleteForm').action = `/timetables/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/academics/timetables/index.blade.php ENDPATH**/ ?>