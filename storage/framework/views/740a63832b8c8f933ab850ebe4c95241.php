<?php $__env->startSection('title', 'កិច្ចការសាលា — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កិច្ចការសាលា</li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.filter-select, .filter-input {
    font-size: .8rem; padding: .3rem .65rem;
    border-radius: var(--radius-sm); border: 1px solid var(--border);
    color: var(--text-dark); background-color: #fff; height: 32px;
}
.filter-select:focus, .filter-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.15); outline: none; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">កិច្ចការសាលា</h1>
        <p class="page-subtitle">គ្រប់គ្រងកិច្ចការសាលា និងការងារសិស្ស។</p>
    </div>
    <a href="<?php echo e(route('assignments.create')); ?>" class="btn btn-primary-action">
        <i class="bi bi-plus-lg me-1"></i>បង្កើតកិច្ចការថ្មី
    </a>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 border-0 shadow-sm mb-4 py-2 px-3" role="alert" style="border-radius:var(--radius-md);font-size:.875rem">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span><?php echo e(session('success')); ?></span>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="toolbar-card mb-4">
    <form method="GET" action="<?php echo e(route('assignments.index')); ?>" id="filterForm">
        <div class="row g-2 align-items-center">
            <div class="col-xl-3 col-lg-3 col-md-4">
                <input type="text" name="search" class="filter-input w-100" placeholder="ស្វែងរកតាមចំណងជើង..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4">
                <select name="class_id" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">ថ្នាក់ទាំងអស់</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                            <?php echo e($class->grade?->name); ?><?php echo e($class->section?->name ? ' — '.$class->section->name : ''); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4">
                <select name="subject_id" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">មុខវិជ្ជាទាំងអស់</option>
                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($subject->id); ?>" <?php echo e(request('subject_id') == $subject->id ? 'selected' : ''); ?>>
                            <?php echo e($subject->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-secondary px-3" style="height: 32px;">ចម្រាញ់</button>
                <?php if(request()->hasAny(['search', 'class_id', 'subject_id'])): ?>
                    <a href="<?php echo e(route('assignments.index')); ?>" class="btn btn-sm btn-link text-muted text-decoration-none px-2">
                        <i class="bi bi-x-circle me-1"></i>កំណត់ឡើងវិញ
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<div class="data-card">
    <div class="data-card-header">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-dark">កិច្ចការសាលា</span>
            <span class="count-badge"><?php echo e($assignments->total()); ?></span>
        </div>
        <small class="text-muted">
            <?php if($assignments->total() > 0): ?>
                បង្ហាញពី <?php echo e($assignments->firstItem()); ?>–<?php echo e($assignments->lastItem()); ?> នៃ <?php echo e($assignments->total()); ?>

            <?php endif; ?>
        </small>
    </div>

    <?php if($assignments->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-journal-text"></i></div>
            <p class="empty-title">មិនមានកិច្ចការសាលាឡើយ</p>
            <p class="empty-subtitle">
                <?php if(request()->hasAny(['search', 'class_id', 'subject_id'])): ?>
                    មិនមានកិច្ចការសាលាណាដែលត្រូវនឹងតម្រងស្វែងរកឡើយ។ <a href="<?php echo e(route('assignments.index')); ?>" class="text-decoration-none">សម្អាតតម្រង</a>
                <?php else: ?>
                    បង្កើតកិច្ចការដំបូងរបស់អ្នកដើម្បីចាប់ផ្ដើម។
                <?php endif; ?>
            </p>
            <?php if(!request()->hasAny(['search', 'class_id', 'subject_id'])): ?>
                <a href="<?php echo e(route('assignments.create')); ?>" class="btn btn-primary-action btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>បង្កើតកិច្ចការថ្មី
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width:40px">#</th>
                        <th>កិច្ចការសាលា</th>
                        <th>ថ្នាក់រៀន</th>
                        <th>មុខវិជ្ជា</th>
                        <th>កាលបរិច្ឆេទផុតកំណត់</th>
                        <th>ពិន្ទុសរុប</th>
                        <th style="width:52px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $classLabel = ($assignment->class?->grade?->name ?? '—');
                        $sectionName = $assignment->section?->name ?? '';
                        if ($sectionName) $classLabel .= ' — ' . $sectionName;
                    ?>
                    <tr>
                        <td class="text-center text-muted" style="font-size:.75rem"><?php echo e($assignments->firstItem() + $i); ?></td>
                        <td>
                            <div class="fw-semibold text-dark" style="font-size:.875rem"><?php echo e($assignment->title); ?></div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark" style="font-size:.875rem"><?php echo e($classLabel); ?></div>
                        </td>
                        <td>
                            <div class="text-muted" style="font-size:.84rem"><?php echo e($assignment->subject?->name ?? '—'); ?></div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark" style="font-size:.875rem">
                                <?php echo e($assignment->due_date?->format('d/m/Y') ?? '—'); ?>

                            </div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:.84rem"><?php echo e($assignment->total_marks); ?></span>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn-action-dots" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="min-width:168px;font-size:.875rem">
                                    <li>
                                        <a class="dropdown-item py-2" href="<?php echo e(route('assignments.show', $assignment->id)); ?>">
                                            <i class="bi bi-eye me-2 text-muted"></i>មើលព័ត៌មានលម្អិត
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?php echo e(route('assignments.edit', $assignment->id)); ?>">
                                            <i class="bi bi-pencil me-2 text-muted"></i>កែសម្រួល
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <button type="button" class="dropdown-item py-2 text-danger"
                                                data-bs-toggle="modal" data-bs-target="#delModal<?php echo e($assignment->id); ?>">
                                            <i class="bi bi-trash me-2"></i>លុប
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="modal fade" id="delModal<?php echo e($assignment->id); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body p-4 text-center">
                                            <div class="mb-3">
                                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:48px;height:48px">
                                                    <i class="bi bi-trash text-danger fs-5"></i>
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-1">លុបកិច្ចការនេះមែនទេ?</h6>
                                            <p class="text-muted small mb-4">
                                                <strong><?php echo e($assignment->title); ?></strong>
                                            </p>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                                                <form action="<?php echo e(route('assignments.destroy', $assignment->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger px-3">លុប</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <?php if($assignments->hasPages()): ?>
        <div class="data-card-footer d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.8rem">ទំព័រ <?php echo e($assignments->currentPage()); ?> នៃ <?php echo e($assignments->lastPage()); ?></div>
            <?php echo e($assignments->links('pagination::bootstrap-5')); ?>

        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/assignments/index.blade.php ENDPATH**/ ?>