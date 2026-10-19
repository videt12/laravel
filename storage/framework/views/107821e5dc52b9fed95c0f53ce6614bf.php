<?php $__env->startSection('title', 'គ្រប់គ្រងការឡើងថ្នាក់ — School Management System'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('students.index')); ?>" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការចុះឈ្មោះ</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ការឡើងថ្នាក់</li>
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
    width: 36px; height: 36px; background: #fffbeb; color: #d97706;
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
    border: 1px solid var(--border); border-radius: 0px !important; transition: all 0.2s; line-height: 1.4;
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
    background-position: right 0.75rem center !important;
    background-size: 14px 10px !important;
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
    background: #f8fafc; color: var(--text-muted); font-size: 0.8rem;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px;
    padding: 0.4rem 0.75rem; border-bottom: 1px solid var(--border-soft); white-space: nowrap;
}
.table-custom tbody tr { transition: background-color 0.15s; }
.table-custom tbody tr:hover { background-color: #fafbfc; }
.table-custom tbody td {
    padding: 0.35rem 0.75rem; font-size: 0.86rem; color: var(--text-dark);
    border-bottom: 1px solid var(--border-soft); vertical-align: middle;
}
.table-custom tbody tr:last-child td { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════════
   CUSTOM UI COMPONENTS
═══════════════════════════════════════════════════════════════════ */
.student-name { font-weight: 600; color: #1e293b; text-decoration: none; font-size: 0.88rem; line-height: 1.25; }
.student-name:hover { color: var(--gold); }
.student-id { font-size: 0.78rem; color: var(--text-muted); font-family: monospace; line-height: 1.1; }
.class-badge {
    display: inline-flex; align-items: center; gap: 0.25rem;
    background: #f8fafc; border: 1px solid var(--border-soft);
    border-radius: 0px !important; padding: 0.15rem 0.5rem;
    font-weight: 600; font-size: 0.82rem;
}
.year-badge {
    display: inline-flex; align-items: center; gap: 0.25rem;
    background: #f1f5f9; color: #475569; border-radius: 0px !important;
    padding: 0.12rem 0.45rem; font-size: 0.78rem; font-weight: 600;
}
.status-badge {
    display: inline-flex; align-items: center; padding: 0.15rem 0.55rem;
    border-radius: 0px !important; font-size: 0.78rem; font-weight: 600;
}
.status-promoted { background: #dcfce7; color: #166534; }
.status-demoted { background: #fee2e2; color: #991b1b; }
.status-transferred { background: #e0e7ff; color: #3730a3; }
.status-pending { background: #fef3c7; color: #92400e; }

/* Table Actions */
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: 0px !important;
    color: var(--text-muted); transition: all 0.2s; background: transparent; border: none;
    font-size: 0.85rem;
}
.action-btn:hover { background: #f1f5f9; color: var(--text-dark); }
.action-btn-danger:hover { background: #fef2f2; color: #dc2626; }

/* ═══════════════════════════════════════════════════════════════════
   TABLE GROUP HEADER ROW (Soft Slate Medium Contrast)
═══════════════════════════════════════════════════════════════════ */
.table-group-header-row td {
    background: #e9eef5 !important;
    border-top: 1px solid #cbd5e1 !important;
    border-bottom: 1px solid #cbd5e1 !important;
    border-left: 3px solid #d97706 !important;
    padding: 0.45rem 0.85rem !important;
    color: #0f172a !important;
}
.table-group-header-row .group-title-label {
    color: #64748b !important;
    font-size: 0.84rem;
    font-weight: 600;
}
.table-group-header-row .group-title {
    color: #0f172a !important;
    font-size: 0.9rem;
    font-weight: 700;
}
.table-group-header-row .badge-group-count {
    background: #ffffff !important;
    color: #334155 !important;
    border: 1px solid #cbd5e1 !important;
    font-weight: 600;
    font-size: 0.74rem;
}
.table-group-header-row .group-toggle-btn {
    border: 1px solid #cbd5e1 !important;
    background: #ffffff !important;
    color: #475569 !important;
    font-size: 0.72rem;
    padding: 0.15rem 0.55rem !important;
    border-radius: 4px !important;
    transition: all 0.15s ease-in-out;
}
.table-group-header-row .group-toggle-btn:hover {
    background: #f1f5f9 !important;
    color: #0f172a !important;
    border-color: #94a3b8 !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">
            <div class="page-title-icon"><i class="bi bi-graph-up-arrow"></i></div>
            គ្រប់គ្រងការឡើងថ្នាក់
        </h1>
        <p class="page-subtitle">តាមដាន និងកត់ត្រាការឡើងថ្នាក់របស់សិស្សរវាងឆ្នាំសិក្សា។</p>
    </div>
    <div>
        <a href="<?php echo e(route('promotions.create')); ?>" class="btn btn-primary-action shadow-sm" style="background:#d97706; border-color:#b45309;">
            <i class="bi bi-plus-circle me-2"></i>បង្កើតការឡើងថ្នាក់ថ្មី
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert-card alert-card-success mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span><?php echo e(session('success')); ?></span>
    </div>
<?php endif; ?>


<div class="filter-bar mb-2">
    <form action="<?php echo e(route('promotions.index')); ?>" method="GET" class="filter-inputs m-0 w-100">
        <input type="hidden" name="group_by" value="<?php echo e(request('group_by', 'to_class')); ?>">

        
        <div class="search-wrapper flex-grow-1" style="max-width: 300px;">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="ស្វែងរកតាមឈ្មោះសិស្ស ឬអត្តលេខ...">
        </div>

        
        <select name="class_id" class="form-select filter-select fw-medium" style="width:auto; min-width: 190px;" onchange="this.form.submit()">
            <option value="">🎓 គ្រប់ថ្នាក់ទាំងអស់</option>
            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $cLabel = ($c->grade?->name ?? 'Class ' . $c->id) . ($c->section?->name ? ' — ' . $c->section->name : '');
                ?>
                <option value="<?php echo e($c->id); ?>" <?php echo e(request('class_id') == $c->id ? 'selected' : ''); ?>>
                    <?php echo e($cLabel); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="shift" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
            <option value="">គ្រប់វេនទាំងអស់</option>
            <option value="morning" <?php echo e(request('shift') == 'morning' ? 'selected' : ''); ?>>វេនព្រឹក</option>
            <option value="afternoon" <?php echo e(request('shift') == 'afternoon' ? 'selected' : ''); ?>>វេនរសៀល</option>
        </select>

        
        <select name="academic_year_id" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
            <option value="">📅 ឆ្នាំសិក្សាឡើងទៅទាំងអស់</option>
            <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($year->id); ?>" <?php echo e(request('academic_year_id') == $year->id ? 'selected' : ''); ?>>
                    <?php echo e($year->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="status" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
            <option value="">ស្ថានភាពទាំងអស់</option>
            <option value="Promoted" <?php echo e(request('status') == 'Promoted' ? 'selected' : ''); ?>>បានឡើងថ្នាក់</option>
            <option value="Demoted" <?php echo e(request('status') == 'Demoted' ? 'selected' : ''); ?>>ត្រួតថ្នាក់</option>
            <option value="Transferred" <?php echo e(request('status') == 'Transferred' ? 'selected' : ''); ?>>បានផ្ទេរ</option>
            <option value="Pending" <?php echo e(request('status') == 'Pending' ? 'selected' : ''); ?>>រង់ចាំ</option>
        </select>

        
        <?php if(request()->anyFilled(['search', 'class_id', 'shift', 'academic_year_id', 'status'])): ?>
            <a href="<?php echo e(route('promotions.index', ['group_by' => request('group_by', 'to_class')])); ?>" class="btn btn-sm btn-ghost ms-2 text-danger" style="font-size:0.75rem">
                <i class="bi bi-x-circle me-1"></i>សម្អាតការស្វែងរក
            </a>
        <?php endif; ?>
    </form>
</div>

<?php
    $groupByMode = request('group_by', 'to_class');
    
    if ($groupByMode === 'from_class') {
        $groupedPromotions = $promotions->getCollection()->groupBy(function($item) {
            $fromGrade = $item->from_class->grade->name ?? '';
            $fromSection = $item->from_class->section->name ?? '';
            $fromYear = $item->from_academic_year->name ?? '';
            $name = trim("{$fromGrade} — {$fromSection}");
            return $name ? ($fromYear ? "{$name} ({$fromYear})" : $name) : 'ថ្នាក់មិនកំណត់ (Unassigned)';
        });
    } elseif ($groupByMode === 'flat') {
        $groupedPromotions = collect(['all' => $promotions->getCollection()]);
    } else {
        // default: to_class
        $groupedPromotions = $promotions->getCollection()->groupBy(function($item) {
            $toGrade = $item->to_class->grade->name ?? '';
            $toSection = $item->to_class->section->name ?? '';
            $toYear = $item->to_academic_year->name ?? '';
            $name = trim("{$toGrade} — {$toSection}");
            return $name ? ($toYear ? "{$name} ({$toYear})" : $name) : 'ថ្នាក់មិនកំណត់ (Unassigned)';
        });
    }
?>


<div class="d-flex align-items-center justify-content-between mb-2 px-1 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
        <span class="text-muted small" style="font-size: 0.8rem;">
            សរុប៖ <strong class="text-dark"><?php echo e($promotions->total()); ?></strong> កំណត់ត្រា
            <?php if($groupByMode !== 'flat'): ?>
                (ចែកជា <strong class="text-primary"><?php echo e($groupedPromotions->count()); ?></strong> ក្រុមថ្នាក់)
            <?php endif; ?>
        </span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <?php if($groupByMode !== 'flat' && $groupedPromotions->count() > 1): ?>
            <button type="button" class="btn btn-sm btn-outline-secondary py-0.5 px-2" style="font-size: 0.75rem;" onclick="toggleAllGroups()" id="toggleAllGroupsBtn">
                <i class="bi bi-arrows-collapse me-1"></i><span>បង្រួមទាំងអស់</span>
            </button>
        <?php endif; ?>
        <div class="d-flex align-items-center gap-1">
            <span class="text-muted small d-none d-sm-inline" style="font-size: 0.76rem;">បង្ហាញជា៖</span>
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?php echo e(request()->fullUrlWithQuery(['group_by' => 'to_class'])); ?>" 
                   class="btn btn-sm <?php echo e($groupByMode === 'to_class' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary'); ?>" 
                   style="font-size: 0.75rem;">
                    <i class="bi bi-folder-symlink-fill me-1"></i>តាមថ្នាក់ថ្មី
                </a>
                <a href="<?php echo e(request()->fullUrlWithQuery(['group_by' => 'from_class'])); ?>" 
                   class="btn btn-sm <?php echo e($groupByMode === 'from_class' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary'); ?>" 
                   style="font-size: 0.75rem;">
                    <i class="bi bi-folder-fill me-1"></i>តាមថ្នាក់ដើម
                </a>
                <a href="<?php echo e(request()->fullUrlWithQuery(['group_by' => 'flat'])); ?>" 
                   class="btn btn-sm <?php echo e($groupByMode === 'flat' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary'); ?>" 
                   style="font-size: 0.75rem;">
                    <i class="bi bi-list-ul me-1"></i>តារាងលាត
                </a>
            </div>
        </div>
    </div>
</div>


<div class="table-card">
    <div class="table-responsive">
        <table class="table table-custom mb-0" id="promotionsTable">
            <thead>
                <tr>
                    <th style="min-width: 180px;">សិស្ស</th>
                    <th>ពីថ្នាក់ដើម</th>
                    <th style="width: 30px;"><i class="bi bi-arrow-right text-muted"></i></th>
                    <th>ឡើងទៅថ្នាក់</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end pe-3">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php if($promotions->isEmpty()): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-graph-up-arrow fs-1"></i></div>
                            <h6 class="fw-bold text-dark">រកមិនឃើញកំណត់ត្រាឡើងថ្នាក់ទេ</h6>
                            <p class="text-muted small mb-0">សូមព្យាយាមផ្លាស់ប្តូរការច្រោះស្វែងរក ឬកត់ត្រាការឡើងថ្នាក់ថ្មី។</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $__currentLoopData = $groupedPromotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupTitle => $groupItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($groupByMode !== 'flat'): ?>
                            
                            <tr class="table-group-header-row">
                                <td colspan="6" class="py-2 px-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-2 d-inline-flex align-items-center justify-content-center shadow-xs" 
                                                  style="width: 26px; height: 26px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; font-size: 0.82rem;">
                                                <i class="bi bi-mortarboard-fill"></i>
                                            </span>
                                            <span class="group-title">
                                                <span class="group-title-label"><?php echo e($groupByMode === 'to_class' ? 'ឡើងទៅថ្នាក់៖ ' : 'ពីថ្នាក់ដើម៖ '); ?></span>
                                                <span><?php echo e($groupTitle); ?></span>
                                            </span>
                                            <span class="badge badge-group-count px-2 py-0.5 rounded-pill shadow-xs">
                                                <?php echo e($groupItems->count()); ?> នាក់
                                            </span>
                                        </div>
                                        <div>
                                            <button type="button" class="btn group-toggle-btn shadow-xs" onclick="toggleGroup('group-<?php echo e($loop->index); ?>', this)">
                                                <i class="bi bi-chevron-up me-1"></i><span>បង្រួម</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php $__currentLoopData = $groupItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $studentPhoto = $promotion->student?->photo_url;
                                $studentKhmerName = trim(($promotion->student?->profile?->last_name_khmer ?? '') . ' ' . ($promotion->student?->profile?->first_name_khmer ?? ''));
                                $studentLatinName = $promotion->student?->user?->name ?? 'សិស្ស';
                                $displayName = $studentKhmerName ?: $studentLatinName;
                            ?>
                            <tr class="group-row-group-<?php echo e($loop->parent->index); ?>">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if($studentPhoto): ?>
                                            <img src="<?php echo e($studentPhoto); ?>" alt="<?php echo e($displayName); ?>" class="rounded-circle border object-fit-cover shadow-xs flex-shrink-0" style="width:28px;height:28px;" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                            <div class="bg-light rounded-circle d-none align-items-center justify-content-center flex-shrink-0" style="width:28px;height:28px;font-size:0.85rem;">
                                                <i class="bi bi-person text-secondary"></i>
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px;height:28px;font-size:0.85rem;">
                                                <i class="bi bi-person text-secondary"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <a href="<?php echo e(route('students.show', $promotion->student_id)); ?>" class="student-name d-block">
                                                <?php echo e($displayName); ?>

                                            </a>
                                            <div class="student-id"><?php echo e($promotion->student->admission_number ?? 'គ្មានអត្តលេខ'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="class-badge">
                                        <?php echo e($promotion->from_class->grade->name ?? ''); ?> — <?php echo e($promotion->from_class->section->name ?? ''); ?>

                                    </span>
                                </td>
                                <td class="text-center text-muted">
                                    <i class="bi bi-arrow-right"></i>
                                </td>
                                <td>
                                    <span class="class-badge" style="background:#fef3c7; border-color:#fde68a; color:#b45309">
                                        <?php echo e($promotion->to_class->grade->name ?? ''); ?> — <?php echo e($promotion->to_class->section->name ?? ''); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php
                                        $statusClass = 'status-pending';
                                        $statusLabel = $promotion->status ?? 'មិនស្គាល់';
                                        if($promotion->status == 'Promoted') {
                                            $statusClass = 'status-promoted';
                                            $statusLabel = 'បានឡើងថ្នាក់';
                                        } elseif($promotion->status == 'Demoted') {
                                            $statusClass = 'status-demoted';
                                            $statusLabel = 'ត្រួតថ្នាក់';
                                        } elseif($promotion->status == 'Transferred') {
                                            $statusClass = 'status-transferred';
                                            $statusLabel = 'បានផ្ទេរ';
                                        } elseif($promotion->status == 'Pending') {
                                            $statusClass = 'status-pending';
                                            $statusLabel = 'រង់ចាំ';
                                        }
                                    ?>
                                    <span class="status-badge <?php echo e($statusClass); ?>">
                                        <?php echo e($statusLabel); ?>

                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="<?php echo e(route('promotions.show', $promotion)); ?>" class="action-btn" data-bs-toggle="tooltip" title="មើលព័ត៌មានលម្អិត">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('promotions.edit', $promotion)); ?>" class="action-btn" data-bs-toggle="tooltip" title="កែសម្រួល">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="action-btn action-btn-danger" 
                                                data-bs-toggle="tooltip" title="លុប"
                                                onclick="confirmDelete(<?php echo e($promotion->id); ?>, '<?php echo e(addslashes($displayName)); ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($promotions->hasPages()): ?>
        <div class="p-3 border-top bg-light">
            <?php echo e($promotions->links('pagination::bootstrap-5')); ?>

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
                <h6 class="fw-bold mb-1">លុបកំណត់ត្រាឡើងថ្នាក់?</h6>
                <p class="text-muted small mb-4">
                    តើអ្នកពិតជាចង់លុបកំណត់ត្រាឡើងថ្នាក់របស់សិស្ស <strong id="deleteStudentName"></strong> នេះមែនទេ?
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <form id="deleteForm" method="POST" action="">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger px-3">យល់ព្រមលុប</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    function confirmDelete(id, name) {
        document.getElementById('deleteStudentName').textContent = name;
        document.getElementById('deleteForm').action = `/promotions/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    function toggleGroup(groupId, btn) {
        const rows = document.querySelectorAll('.group-row-' + groupId);
        let isCollapsed = false;
        rows.forEach(r => {
            if (r.style.display === 'none') {
                r.style.display = '';
                isCollapsed = false;
            } else {
                r.style.display = 'none';
                isCollapsed = true;
            }
        });
        if (btn) {
            const icon = btn.querySelector('i');
            const text = btn.querySelector('span');
            if (isCollapsed) {
                if (icon) icon.className = 'bi bi-chevron-down me-1';
                if (text) text.textContent = 'ពង្រីក';
                btn.classList.add('btn-light');
                btn.classList.remove('btn-outline-secondary');
            } else {
                if (icon) icon.className = 'bi bi-chevron-up me-1';
                if (text) text.textContent = 'បង្រួម';
                btn.classList.remove('btn-light');
                btn.classList.add('btn-outline-secondary');
            }
        }
    }

    function toggleAllGroups() {
        const allGroupRows = document.querySelectorAll('[class*="group-row-group-"]');
        const allBtns = document.querySelectorAll('.group-toggle-btn');
        const mainBtn = document.getElementById('toggleAllGroupsBtn');
        const textSpan = mainBtn ? mainBtn.querySelector('span') : null;
        const shouldCollapse = textSpan ? textSpan.textContent.includes('បង្រួម') : true;

        allGroupRows.forEach(r => {
            r.style.display = shouldCollapse ? 'none' : '';
        });

        allBtns.forEach(btn => {
            const icon = btn.querySelector('i');
            const text = btn.querySelector('span');
            if (shouldCollapse) {
                if (icon) icon.className = 'bi bi-chevron-down me-1';
                if (text) text.textContent = 'ពង្រីក';
                btn.classList.add('btn-light');
                btn.classList.remove('btn-outline-secondary');
            } else {
                if (icon) icon.className = 'bi bi-chevron-up me-1';
                if (text) text.textContent = 'បង្រួម';
                btn.classList.remove('btn-light');
                btn.classList.add('btn-outline-secondary');
            }
        });

        if (mainBtn) {
            const icon = mainBtn.querySelector('i');
            if (shouldCollapse) {
                if (icon) icon.className = 'bi bi-arrows-expand me-1';
                if (textSpan) textSpan.textContent = 'ពង្រីកទាំងអស់';
            } else {
                if (icon) icon.className = 'bi bi-arrows-collapse me-1';
                if (textSpan) textSpan.textContent = 'បង្រួមទាំងអស់';
            }
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/enrollment/promotions/index.blade.php ENDPATH**/ ?>