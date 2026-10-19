<?php $__env->startSection('title', 'បន្ថែមមុខតំណែងថ្មី — School Management System'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('students.index')); ?>" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">បុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('departments.index')); ?>" class="text-decoration-none text-muted">រចនាសម្ព័ន្ធបុគ្គលិក</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បន្ថែមមុខតំណែងថ្មី</li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.form-card {
    background: #ffffff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.form-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-soft, #e2e8f0);
    background: #ffffff;
}
.form-card-body {
    padding: 1.25rem;
}
.form-label-custom {
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.3rem;
    display: block;
}
.form-control-custom {
    font-size: 0.85rem;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 0.45rem 0.75rem;
    min-height: 38px;
    height: 38px;
    transition: all 0.2s;
    background-color: #ffffff;
}
textarea.form-control-custom {
    min-height: 80px;
    height: auto;
}
.form-control-custom:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 2px rgba(232, 168, 56, 0.2);
    outline: none;
}
.alert-card-danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
    border-radius: 6px;
    padding: 0.75rem 1rem;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header d-flex align-items-start justify-content-between mb-3">
    <div>
        <h1 class="page-title fw-bold text-dark mb-1" style="font-size: 1.35rem;">
            បន្ថែមមុខតំណែងថ្មី (Add New Position)
        </h1>
        <p class="page-subtitle text-muted mb-0" style="font-size: 0.85rem;">
            សូមបំពេញព័ត៌មានលម្អិតនៃមុខតំណែង ឬតួនាទីការងារបុគ្គលិកខាងក្រោម
        </p>
    </div>
    <a href="<?php echo e(route('departments.index')); ?>" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1.5 px-3 py-1.5" style="border-radius: 4px;">
        <i class="bi bi-arrow-left"></i>
        <span>ត្រឡប់ក្រោយ (Back)</span>
    </a>
</div>


<?php if($errors->any()): ?>
    <div class="alert-card alert-card-danger mb-4">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill"></i>
            <strong>សូមពិនិត្យ និងកែតម្រូវកំហុសឆ្គងខាងក្រោម មុនពេលរក្សាទុក៖</strong>
        </div>
        <ul class="mb-0 mt-2 ps-4" style="font-size: 0.85rem;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('positions.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="row g-4 justify-content-center">
        <div class="col-xl-8 col-lg-9">
            <div class="form-card mb-4">
                <div class="form-card-header bg-white pb-2">
                    <h5 class="mb-0 d-flex align-items-center">
                        <span class="badge rounded-circle p-2 me-2 d-flex align-items-center justify-content-center text-white" style="width: 28px; height: 28px; background: #e8a838; font-size: 0.85rem;"><i class="bi bi-award"></i></span>
                        <span class="fw-bold text-dark">ព័ត៌មានមុខតំណែង</span>
                        <span class="text-muted fw-normal ms-2" style="font-size: 0.82rem;">(Position Information)</span>
                    </h5>
                </div>
                <div class="form-card-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-8 col-12">
                            <label class="form-label-custom">ឈ្មោះមុខតំណែង (Position Title) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-custom <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" placeholder="ឧ. នាយកសាលា, គ្រូបង្រៀន, បេឡាធិការ..." required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4 col-12">
                            <label class="form-label-custom">លេខកូដ (Code ID)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted px-2.5" title="ចាក់សោស្វ័យប្រវត្តិ">
                                    <i class="bi bi-lock-fill text-warning"></i>
                                </span>
                                <input type="text" name="code" class="form-control form-control-custom bg-light border-start-0 font-monospace fw-bold <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('code', $nextPositionCode ?? 'POS-001')); ?>" readonly tabindex="-1" title="លេខកូដត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ និងចាក់សោ">
                            </div>
                            <div class="form-text text-muted small mt-1" style="font-size: 0.72rem;">
                                <i class="bi bi-lock-fill text-warning me-1"></i>បង្កើតស្វ័យប្រវត្តិ — ចាក់សោ (Locked)
                            </div>
                            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">ស្ថានភាព <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-control-custom <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="active" <?php echo e(old('status', 'active') === 'active' ? 'selected' : ''); ?>>សកម្ម</option>
                                <option value="inactive" <?php echo e(old('status') === 'inactive' ? 'selected' : ''); ?>>អសកម្ម</option>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">ការពិពណ៌នាការងារ (Job Description)</label>
                            <textarea name="description" rows="3" class="form-control form-control-custom <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="ពិពណ៌នាសង្ខេបអំពីតួនាទី និងការងារទទួលខុសត្រូវ..."><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                
                <div class="p-3 bg-light border-top d-flex align-items-center justify-content-between">
                    <a href="<?php echo e(route('departments.index')); ?>" class="btn btn-sm btn-outline-secondary px-3 py-1.5" style="border-radius: 4px;">
                        បោះបង់ (Cancel)
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4 py-1.5 d-flex align-items-center gap-1.5 shadow-sm">
                        <i class="bi bi-check-lg"></i>
                        <span>រក្សាទុកមុខតំណែង (Save Position)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/employees/positions/create.blade.php ENDPATH**/ ?>