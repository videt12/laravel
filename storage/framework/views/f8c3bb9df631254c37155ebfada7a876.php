<?php $__env->startSection('title', 'គ្រប់គ្រងការផ្ទេរសាលា — School Management System'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('students.index')); ?>" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការចុះឈ្មោះ</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ការផ្ទេរសាលា</li>
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
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
    padding: 0.35rem 0.65rem; display: flex; align-items: center; justify-content: space-between;
    gap: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.filter-inputs { display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
.search-wrapper { position: relative; width: 220px; }
.search-wrapper i { position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.8rem; }
.search-wrapper input {
    width: 100%; padding: 0.25rem 0.65rem 0.25rem 1.85rem; font-size: 0.8rem; min-height: 32px; height: 32px;
    border: 1px solid var(--border); border-radius: var(--radius-md); transition: all 0.2s; line-height: 1.3;
}
.search-wrapper input:focus { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(79,70,229,.1); outline: none; }
.filter-select {
    font-size: 0.8rem; min-height: 32px; height: 32px; line-height: 1.3;
    padding: 0.25rem 1.8rem 0.25rem 0.65rem;
    border: 1px solid var(--border); border-radius: var(--radius-md); background-color: #fff;
}
.filter-select:focus { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(79,70,229,.1); outline: none; }

/* ═══════════════════════════════════════════════════════════════════
   DATA TABLE
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
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
.student-name:hover { color: var(--primary); }
.student-id { font-size: 0.78rem; color: var(--text-muted); font-family: monospace; line-height: 1.1; }
.school-badge {
    display: inline-flex; align-items: center; gap: 0.25rem;
    background: #f8fafc; border: 1px solid var(--border-soft);
    border-radius: var(--radius-sm); padding: 0.12rem 0.45rem;
    font-weight: 600; font-size: 0.78rem;
}
.status-badge {
    display: inline-flex; align-items: center; padding: 0.15rem 0.55rem;
    border-radius: 999px; font-size: 0.78rem; font-weight: 600;
}
.status-completed { background: #dcfce7; color: #166534; }
.status-rejected { background: #fee2e2; color: #991b1b; }
.status-approved { background: #e0e7ff; color: #3730a3; }
.status-pending { background: #fef3c7; color: #92400e; }

/* Table Actions */
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: var(--radius-sm);
    color: var(--text-muted); transition: all 0.2s; background: transparent; border: none;
    font-size: 0.85rem;
}
.action-btn:hover { background: #f1f5f9; color: var(--text-dark); }
.action-btn-danger:hover { background: #fef2f2; color: #dc2626; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">
            <div class="page-title-icon"><i class="bi bi-arrow-left-right"></i></div>
            គ្រប់គ្រងការផ្ទេរសាលា
        </h1>
        <p class="page-subtitle">តាមដាន និងកត់ត្រាការផ្ទេរការសិក្សារបស់សិស្សរវាងសាលានិងសាលា។</p>
    </div>
    <div>
        <a href="<?php echo e(route('transfers.create')); ?>" class="btn btn-primary-action shadow-sm" style="background:#d97706; border-color:#b45309;">
            <i class="bi bi-plus-circle me-2"></i>ផ្ទេរសាលាថ្មី
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
    <form action="<?php echo e(route('transfers.index')); ?>" method="GET" class="filter-inputs m-0 w-100">
        
        
        <div class="search-wrapper flex-grow-1" style="max-width: 350px;">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="ស្វែងរកតាមឈ្មោះសិស្ស ឬអត្តលេខ...">
        </div>

        
        <select name="status" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
            <option value="">ស្ថានភាពទាំងអស់</option>
            <option value="Completed" <?php echo e(request('status') == 'Completed' ? 'selected' : ''); ?>>បានបញ្ចប់</option>
            <option value="Approved" <?php echo e(request('status') == 'Approved' ? 'selected' : ''); ?>>បានអនុម័ត</option>
            <option value="Pending" <?php echo e(request('status') == 'Pending' ? 'selected' : ''); ?>>រង់ចាំការអនុម័ត</option>
            <option value="Rejected" <?php echo e(request('status') == 'Rejected' ? 'selected' : ''); ?>>បដិសេធ</option>
        </select>

        
        <?php if(request()->anyFilled(['search', 'status'])): ?>
            <a href="<?php echo e(route('transfers.index')); ?>" class="btn btn-sm btn-ghost ms-2 text-danger" style="font-size:0.75rem">
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
                    <th>សិស្ស</th>
                    <th>ពីសាលាដើម</th>
                    <th><i class="bi bi-arrow-right text-muted"></i></th>
                    <th>ទៅសាលាថ្មី</th>
                    <th>កាលបរិច្ឆេទផ្ទេរ</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px;height:28px;font-size:0.85rem;">
                                    <i class="bi bi-person text-secondary"></i>
                                </div>
                                <div>
                                    <a href="#" class="student-name d-block">
                                        <?php echo e($transfer->student->user->name ?? 'មិនស្គាល់ឈ្មោះសិស្ស'); ?>

                                    </a>
                                    <div class="student-id"><?php echo e($transfer->student->admission_number ?? 'មិនមាន'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="school-badge">
                                <i class="bi bi-building"></i>
                                <?php echo e($transfer->from_school->name ?? 'មិនស្គាល់សាលា'); ?>

                            </span>
                        </td>
                        <td class="text-center text-muted">
                            <i class="bi bi-arrow-right"></i>
                        </td>
                        <td>
                            <span class="school-badge" style="background:#eef2ff; border-color:#c7d2fe; color:#4338ca">
                                <i class="bi bi-building"></i>
                                <?php echo e($transfer->to_school->name ?? 'មិនស្គាល់សាលា'); ?>

                            </span>
                        </td>
                        <td class="text-muted" style="font-size:0.75rem">
                            <?php echo e($transfer->transfer_date ? \Carbon\Carbon::parse($transfer->transfer_date)->format('M d, Y') : '—'); ?>

                        </td>
                        <td>
                            <?php
                                $statusClass = 'status-pending';
                                $statusLabel = $transfer->status ?? 'មិនស្គាល់';
                                if($transfer->status == 'Completed') {
                                    $statusClass = 'status-completed';
                                    $statusLabel = 'បានបញ្ចប់';
                                } elseif($transfer->status == 'Approved') {
                                    $statusClass = 'status-approved';
                                    $statusLabel = 'បានអនុម័ត';
                                } elseif($transfer->status == 'Rejected') {
                                    $statusClass = 'status-rejected';
                                    $statusLabel = 'បដិសេធ';
                                } elseif($transfer->status == 'Pending') {
                                    $statusClass = 'status-pending';
                                    $statusLabel = 'រង់ចាំការអនុម័ត';
                                }
                            ?>
                            <span class="status-badge <?php echo e($statusClass); ?>">
                                <?php echo e($statusLabel); ?>

                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="<?php echo e(route('transfers.show', $transfer)); ?>" class="action-btn" data-bs-toggle="tooltip" title="មើលព័ត៌មានលម្អិត">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo e(route('transfers.edit', $transfer)); ?>" class="action-btn" data-bs-toggle="tooltip" title="កែសម្រួលការផ្ទេរសាលា">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="action-btn action-btn-danger" 
                                        data-bs-toggle="tooltip" title="លុបការផ្ទេរសាលា"
                                        onclick="confirmDelete(<?php echo e($transfer->id); ?>, '<?php echo e(addslashes($transfer->student->user->name ?? 'Unknown')); ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-arrow-left-right fs-1"></i></div>
                            <h6 class="fw-bold text-dark">រកមិនឃើញកំណត់ត្រាផ្ទេរសាលាទេ</h6>
                            <p class="text-muted small mb-0">សូមព្យាយាមផ្លាស់ប្តូរការច្រោះស្វែងរក ឬកត់ត្រាការផ្ទេរសាលាថ្មី។</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($transfers->hasPages()): ?>
        <div class="p-3 border-top bg-light">
            <?php echo e($transfers->links('pagination::bootstrap-5')); ?>

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
                <h6 class="fw-bold mb-1">លុបកំណត់ត្រាផ្ទេរសាលា?</h6>
                <p class="text-muted small mb-4">
                    តើអ្នកពិតជាចង់លុបកំណត់ត្រាផ្ទេរសាលារបស់សិស្ស <strong id="deleteStudentName"></strong> នេះមែនទេ?
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
        document.getElementById('deleteForm').action = `/transfers/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/enrollment/transfers/index.blade.php ENDPATH**/ ?>