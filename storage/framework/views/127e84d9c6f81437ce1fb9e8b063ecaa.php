<?php $__env->startSection('title', 'កំណត់ចំណាំសិស្ស — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">សេវាកម្មសិស្ស</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កំណត់ចំណាំសិស្ស</li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ═══════════════════════════════════════════════════════════════════
   NOTE TYPE BADGES
═══════════════════════════════════════════════════════════════════ */
.note-type-badge {
    display: inline-flex;
    align-items: center;
    gap: .28rem;
    border-radius: 999px;
    padding: .18rem .6rem;
    font-size: .72rem;
    font-weight: 600;
    white-space: nowrap;
    letter-spacing: .1px;
}
.note-type-academic       { background: #dbeafe; color: #1e40af; }
.note-type-behavioral     { background: #fce7f3; color: #9d174d; }
.note-type-health         { background: #d1fae5; color: #065f46; }
.note-type-administrative { background: #e0e7ff; color: #3730a3; }
.note-type-parent         { background: #fef3c7; color: #92400e; }
.note-type-general        { background: #f1f5f9; color: #475569; }

/* ═══════════════════════════════════════════════════════════════════
   PRIORITY BADGES
═══════════════════════════════════════════════════════════════════ */
.note-priority-badge {
    display: inline-flex;
    align-items: center;
    gap: .28rem;
    border-radius: var(--radius-sm);
    padding: .2rem .55rem;
    font-size: .7rem;
    font-weight: 700;
    white-space: nowrap;
    text-transform: uppercase;
    letter-spacing: .4px;
}
.note-priority-urgent { background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; }
.note-priority-high   { background: #fff7ed; color: #c2410c; border: 1px solid #fdba74; }
.note-priority-normal { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
.note-priority-low    { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

/* ═══════════════════════════════════════════════════════════════════
   STATUS BADGES
═══════════════════════════════════════════════════════════════════ */
.note-status-badge {
    display: inline-flex;
    align-items: center;
    gap: .28rem;
    border-radius: 999px;
    padding: .18rem .65rem;
    font-size: .72rem;
    font-weight: 600;
    white-space: nowrap;
}
.note-status-active   { background: #dcfce7; color: #15803d; }
.note-status-resolved { background: #e0e7ff; color: #4338ca; }
.note-status-archived { background: #f1f5f9; color: #64748b; }

/* ═══════════════════════════════════════════════════════════════════
   FLAG ICONS
═══════════════════════════════════════════════════════════════════ */
.note-flag { font-size: .8rem; }
.note-flag-important  { color: #d97706; }
.note-flag-followup   { color: #2563eb; }
.note-flag-inactive   { color: #cbd5e1; }

/* ═══════════════════════════════════════════════════════════════════
   FILTER SELECTS
═══════════════════════════════════════════════════════════════════ */
.filter-select {
    font-size: .8rem;
    padding: .3rem .65rem;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    color: var(--text-dark);
    background-color: #fff;
    cursor: pointer;
    height: 30px;
}
.filter-select:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 2px var(--gold-subtle);
    outline: none;
}

/* Active filter chip */
.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    background: var(--gold-light);
    color: var(--gold-dark);
    border-radius: 999px;
    padding: .15rem .65rem;
    font-size: .73rem;
    font-weight: 500;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $khmerNoteTypes = [
        'academic'       => 'ការសិក្សា',
        'behavioral'     => 'អាកប្បកិរិយា',
        'health'         => 'សុខភាព',
        'administrative' => 'រដ្ឋបាល',
        'parent'         => 'មាតាបិតា',
        'general'        => 'ទូទៅ',
    ];
    $khmerPriorities = [
        'urgent' => 'បន្ទាន់',
        'high'   => 'ខ្ពស់',
        'normal' => 'ធម្មតា',
        'low'    => 'ទាប',
    ];
    $khmerStatuses = [
        'active'   => 'សកម្ម',
        'resolved' => 'បានដោះស្រាយ',
        'archived' => 'ទុកជាឯកសារ',
    ];
?>


<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">កំណត់ចំណាំសិស្ស</h1>
        <p class="page-subtitle">គ្រប់គ្រង និងពិនិត្យមើលកំណត់ចំណាំរបស់សិស្ស</p>
    </div>
    <a href="<?php echo e(route('student-notes.create')); ?>" class="btn btn-primary-action">
        <i class="bi bi-plus-lg me-1"></i>+ បន្ថែមកំណត់ចំណាំ
    </a>
</div>


<div class="toolbar-card mb-4">
    <form method="GET" action="<?php echo e(route('student-notes.index')); ?>" id="filterForm">
        <div class="row g-2 align-items-center">

            
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="search-input-wrap">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" name="search" id="searchInput"
                           value="<?php echo e($search); ?>"
                           class="form-control form-control-sm"
                           placeholder="ឈ្មោះសិស្ស អត្តលេខ ឬកំណត់ចំណាំ..."
                           autocomplete="off">
                </div>
            </div>

            
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                <select name="type" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">ប្រភេទទាំងអស់</option>
                    <?php $__currentLoopData = \App\Models\Students\StudentNote::NOTE_TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e($type === $val ? 'selected' : ''); ?>><?php echo e($khmerNoteTypes[$val] ?? $label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                <select name="priority" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">អាទិភាពទាំងអស់</option>
                    <?php $__currentLoopData = \App\Models\Students\StudentNote::PRIORITIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e($priority === $val ? 'selected' : ''); ?>><?php echo e($khmerPriorities[$val] ?? $label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                <select name="status" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">ស្ថានភាពទាំងអស់</option>
                    <?php $__currentLoopData = \App\Models\Students\StudentNote::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e($status === $val ? 'selected' : ''); ?>><?php echo e($khmerStatuses[$val] ?? $label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="col-xl-2 col-lg-1 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-outline-secondary px-3">
                    <i class="bi bi-funnel me-1"></i>ចម្រាញ់
                </button>
                <?php if($search || $type || $priority || $status): ?>
                    <a href="<?php echo e(route('student-notes.index')); ?>" class="btn btn-sm btn-link text-muted text-decoration-none px-2">
                        <i class="bi bi-x-circle me-1"></i>កំណត់ឡើងវិញ
                    </a>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($search || $type || $priority || $status): ?>
        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
            <span class="text-muted" style="font-size:.75rem">តម្រងសកម្ម:</span>
            <?php if($search): ?>
                <span class="filter-chip">ស្វែងរក: "<?php echo e($search); ?>"
                    <a href="<?php echo e(route('student-notes.index', array_filter(['type'=>$type,'priority'=>$priority,'status'=>$status]))); ?>" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            <?php endif; ?>
            <?php if($type): ?>
                <span class="filter-chip">ប្រភេទ: <?php echo e($khmerNoteTypes[$type] ?? (\App\Models\Students\StudentNote::NOTE_TYPES[$type] ?? $type)); ?>

                    <a href="<?php echo e(route('student-notes.index', array_filter(['search'=>$search,'priority'=>$priority,'status'=>$status]))); ?>" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            <?php endif; ?>
            <?php if($priority): ?>
                <span class="filter-chip">អាទិភាព: <?php echo e($khmerPriorities[$priority] ?? (\App\Models\Students\StudentNote::PRIORITIES[$priority] ?? $priority)); ?>

                    <a href="<?php echo e(route('student-notes.index', array_filter(['search'=>$search,'type'=>$type,'status'=>$status]))); ?>" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            <?php endif; ?>
            <?php if($status): ?>
                <span class="filter-chip">ស្ថានភាព: <?php echo e($khmerStatuses[$status] ?? (\App\Models\Students\StudentNote::STATUSES[$status] ?? $status)); ?>

                    <a href="<?php echo e(route('student-notes.index', array_filter(['search'=>$search,'type'=>$type,'priority'=>$priority]))); ?>" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </form>
</div>


<div class="data-card">

    <div class="data-card-header">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-dark">កំណត់ចំណាំសិស្ស</span>
            <span class="count-badge"><?php echo e($notes->total()); ?></span>
            <?php if($search || $type || $priority || $status): ?>
                <span class="text-muted" style="font-size:.75rem">· បានចម្រាញ់</span>
            <?php endif; ?>
        </div>
        <small class="text-muted">
            <?php if($notes->total() > 0): ?>
                បង្ហាញពី <?php echo e($notes->firstItem()); ?>–<?php echo e($notes->lastItem()); ?> នៃ <?php echo e($notes->total()); ?>

            <?php endif; ?>
        </small>
    </div>

    <?php if($notes->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-journal-text"></i></div>
            <p class="empty-title">
                <?php if($search || $type || $priority || $status): ?> មិនមានកំណត់ចំណាំដែលត្រូវនឹងតម្រងស្វែងរកឡើយ <?php else: ?> មិនមានកំណត់ចំណាំសិស្សឡើយ <?php endif; ?>
            </p>
            <p class="empty-subtitle">
                <?php if($search || $type || $priority || $status): ?>
                    សូមសាកល្បងកែសម្រួល ឬ <a href="<?php echo e(route('student-notes.index')); ?>" class="text-decoration-none">សម្អាតតម្រង</a>។
                <?php else: ?>
                    មិនទាន់មានកំណត់ចំណាំសិស្សនៅឡើយទេ។
                <?php endif; ?>
            </p>
            <?php if(!$search && !$type && !$priority && !$status): ?>
                <a href="<?php echo e(route('student-notes.create')); ?>" class="btn btn-primary-action btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>+ បន្ថែមកំណត់ចំណាំសិស្ស
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:36px"></th>
                        <th>សិស្ស</th>
                        <th>កំណត់ចំណាំ</th>
                        <th>ប្រភេទ</th>
                        <th>អាទិភាព</th>
                        <th>ស្ថានភាព</th>
                        <th style="width:70px">ទង់សម្គាល់</th>
                        <th style="width:90px">កាលបរិច្ឆេទ</th>
                        <th style="width:52px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $name         = $note->student?->user?->name ?? '—';
                        $initials     = strtoupper(substr($name, 0, 1));
                        $avatarColors = ['#e8a838','#3b82f6','#10b981','#8b5cf6','#ef4444','#f59e0b'];
                        $color        = $avatarColors[crc32($name) % count($avatarColors)];
                    ?>
                    <tr class="table-row-hover">

                        
                        <td class="text-muted text-center" style="font-size:.75rem"><?php echo e($notes->firstItem() + $i); ?></td>

                        
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <?php
                                    $photoUrl = $note->student?->photo_url;
                                ?>
                                <?php if($photoUrl): ?>
                                    <img src="<?php echo e($photoUrl); ?>"
                                         alt="<?php echo e($name); ?>"
                                         class="rounded-circle flex-shrink-0 border object-fit-cover shadow-xs"
                                         style="width:34px;height:34px;"
                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                    <div class="student-avatar d-none flex-shrink-0" style="background:<?php echo e($color); ?>1a;color:<?php echo e($color); ?>"><?php echo e($initials); ?></div>
                                <?php else: ?>
                                    <div class="student-avatar flex-shrink-0" style="background:<?php echo e($color); ?>1a;color:<?php echo e($color); ?>"><?php echo e($initials); ?></div>
                                <?php endif; ?>
                                <div class="lh-sm">
                                    <a href="<?php echo e(route('students.show', $note->student)); ?>"
                                       class="fw-semibold text-dark text-decoration-none student-name-link">
                                        <?php echo e($name); ?>

                                    </a>
                                    <?php if($note->student?->admission_number): ?>
                                        <div class="text-muted" style="font-size:.73rem"><?php echo e($note->student->admission_number); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>

                        
                        <td style="max-width:240px">
                            <a href="<?php echo e(route('student-notes.show', $note)); ?>"
                               class="text-secondary text-decoration-none d-block text-truncate"
                               style="font-size:.84rem;max-width:230px"
                               title="<?php echo e($note->note); ?>">
                                <?php echo e($note->preview); ?>

                            </a>
                        </td>

                        
                        <td>
                            <?php if($note->note_type): ?>
                                <span class="note-type-badge <?php echo e($note->note_type_class); ?>">
                                    <?php echo e($khmerNoteTypes[$note->note_type] ?? $note->note_type_label); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:.8rem">—</span>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <span class="note-priority-badge <?php echo e($note->priority_class); ?>">
                                <?php if($note->priority === 'urgent'): ?> <i class="bi bi-exclamation-circle-fill"></i>
                                <?php elseif($note->priority === 'high'): ?> <i class="bi bi-arrow-up-circle-fill"></i>
                                <?php elseif($note->priority === 'low'): ?> <i class="bi bi-arrow-down-circle"></i>
                                <?php else: ?> <i class="bi bi-dash-circle"></i>
                                <?php endif; ?>
                                <?php echo e($khmerPriorities[$note->priority] ?? $note->priority_label); ?>

                            </span>
                        </td>

                        
                        <td>
                            <span class="note-status-badge <?php echo e($note->status_class); ?>">
                                <?php if($note->status === 'active'): ?> <i class="bi bi-circle-fill" style="font-size:.45rem"></i>
                                <?php elseif($note->status === 'resolved'): ?> <i class="bi bi-check-circle-fill" style="font-size:.7rem"></i>
                                <?php else: ?> <i class="bi bi-archive" style="font-size:.7rem"></i>
                                <?php endif; ?>
                                <?php echo e($khmerStatuses[$note->status] ?? $note->status_label); ?>

                            </span>
                        </td>

                        
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-bookmark-star-fill note-flag <?php echo e($note->is_important ? 'note-flag-important' : 'note-flag-inactive'); ?>"
                                   title="<?php echo e($note->is_important ? 'សំខាន់' : 'មិនសំខាន់'); ?>"
                                   data-bs-toggle="tooltip"></i>
                                <i class="bi bi-clock-history note-flag <?php echo e($note->follow_up_required ? 'note-flag-followup' : 'note-flag-inactive'); ?>"
                                   title="<?php echo e($note->follow_up_required ? 'ត្រូវការតាមដានបន្ត' : 'មិនត្រូវការតាមដាន'); ?>"
                                   data-bs-toggle="tooltip"></i>
                            </div>
                        </td>

                        
                        <td class="text-muted" style="font-size:.8rem;white-space:nowrap">
                            <?php echo e(($note->note_date ?? $note->created_at)?->format('d/m/Y') ?? '—'); ?>

                        </td>

                        
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn-action-dots" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="min-width:140px;font-size:.875rem">
                                    <li>
                                        <a class="dropdown-item py-2" href="<?php echo e(route('student-notes.show', $note)); ?>">
                                            <i class="bi bi-eye me-2 text-muted"></i>មើលលម្អិត
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?php echo e(route('student-notes.edit', $note)); ?>">
                                            <i class="bi bi-pencil me-2 text-muted"></i>កែសម្រួល
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <button type="button" class="dropdown-item py-2 text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delModal<?php echo e($note->id); ?>">
                                            <i class="bi bi-trash me-2"></i>លុប
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            
                            <div class="modal fade" id="delModal<?php echo e($note->id); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body p-4 text-center">
                                            <div class="mb-3">
                                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:48px;height:48px">
                                                    <i class="bi bi-trash text-danger fs-5"></i>
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-1">លុបកំណត់ចំណាំនេះមែនទេ?</h6>
                                            <p class="text-muted small mb-4">
                                                កំណត់ចំណាំសម្រាប់សិស្ស <strong><?php echo e($name); ?></strong> នឹងត្រូវបានលុបចេញជាអចិន្ត្រៃយ៍។
                                            </p>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                                                <form action="<?php echo e(route('student-notes.destroy', $note)); ?>" method="POST">
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

        <?php if($notes->hasPages()): ?>
        <div class="data-card-footer">
            <div class="text-muted" style="font-size:.8rem">ទំព័រ <?php echo e($notes->currentPage()); ?> នៃ <?php echo e($notes->lastPage()); ?></div>
            <?php echo e($notes->links('pagination::bootstrap-5')); ?>

        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => document.getElementById('filterForm').submit(), 500);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/students/notes/index.blade.php ENDPATH**/ ?>