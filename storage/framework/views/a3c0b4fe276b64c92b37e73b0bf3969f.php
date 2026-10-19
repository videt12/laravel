<?php $__env->startSection('title', 'បន្ថែមបុគ្គលិកថ្មី — School Management System'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('students.index')); ?>" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('employees.index')); ?>" class="text-decoration-none text-muted">បុគ្គលិក</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បន្ថែមបុគ្គលិក</li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
:root {
    --amber-brand: #e8a838;
    --amber-dark: #c98b1e;
    --amber-subtle: #fef3c7;
    --amber-text: #b45309;
    --border-color: #e2e8f0;
}

.employee-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.employee-header-icon {
    width: 40px;
    height: 40px;
    background: #e8a838;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}

/* MULTI-STEP WIZARD */
.wizard-stepper {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.65rem 0.85rem;
    margin-bottom: 1.15rem;
}
.wizard-steps-track {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
    padding-bottom: 2px;
}
.wizard-steps-track::-webkit-scrollbar {
    height: 3px;
}
.wizard-steps-track::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.wizard-step-tab {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 20px;
    padding: 0.25rem 0.6rem;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.73rem;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    user-select: none;
    white-space: nowrap;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.wizard-step-tab:hover {
    border-color: #cbd5e1;
    background: #f1f5f9;
    color: #1e293b;
}
.wizard-step-tab .step-num {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #cbd5e1;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.68rem;
    font-weight: 700;
    transition: all 0.2s ease;
}
.wizard-step-tab.active {
    background: #fffdf5;
    border-color: #e8a838;
    color: #92400e;
    box-shadow: 0 1px 3px rgba(232, 168, 56, 0.18);
}
.wizard-step-tab.active .step-num {
    background: #e8a838;
    color: #ffffff;
}
.wizard-step-tab.completed {
    background: #f0fdf4;
    border-color: #86efac;
    color: #166534;
}
.wizard-step-tab.completed .step-num {
    background: #22c55e;
    color: #ffffff;
}
.step-connector {
    flex-grow: 1;
    height: 2px;
    background: #e2e8f0;
    min-width: 8px;
}
.wizard-step-pane {
    display: none;
    animation: wizardFadeIn 0.22s ease-in-out;
}
.wizard-step-pane.active {
    display: block;
}
@keyframes wizardFadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

.unified-form-card {
    background: #ffffff;
    border: 1px solid var(--border-color, #e2e8f0);
    border-top: 3px solid #e8a838;
    border-radius: 8px;
    padding: 1.25rem 1.35rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 4px 12px rgba(0, 0, 0, 0.02);
}
.form-section-block {
    position: relative;
}
.section-divider {
    height: 1px;
    background: #f1f5f9;
    border-top: 1px dashed #e2e8f0;
    margin: 1.35rem 0 1.15rem 0;
}
.step-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.4rem;
    border-bottom: 1px solid #f1f5f9;
}
.step-badge {
    width: 24px;
    height: 24px;
    background: #e8a838;
    color: #ffffff;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.76rem;
    font-weight: 700;
    flex-shrink: 0;
}
.step-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.step-subtitle {
    font-size: 0.76rem;
    color: #64748b;
    font-weight: 400;
    margin-left: 0.25rem;
}

.account-type-box {
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 0.25rem 0.65rem;
    cursor: pointer;
    background: #fff;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
}
.account-type-box:hover {
    border-color: #94a3b8;
    background-color: #f8fafc;
}
.account-type-box.active {
    border-color: #e8a838;
    background-color: #fffdf5;
    box-shadow: 0 0 0 1px #e8a838;
}
.account-type-radio {
    accent-color: #e8a838;
    width: 0.85rem;
    height: 0.85rem;
    cursor: pointer;
    margin: 0;
    flex-shrink: 0;
}

.form-label-kh {
    font-size: 0.78rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.25rem;
}
.required-star {
    color: #ef4444;
    font-weight: bold;
}
.form-control-custom,
.form-select-custom {
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 0.35rem 0.65rem;
    font-size: 0.82rem;
    height: 35px;
    min-height: 35px;
    color: #1e293b;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
textarea.form-control-custom {
    height: auto;
    min-height: 65px;
    padding: 0.45rem 0.65rem;
}
.form-control-custom:focus,
.form-select-custom:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 2px rgba(232, 168, 56, 0.18);
    outline: 0;
}
.form-control-custom::placeholder {
    color: #94a3b8;
    font-size: 0.78rem;
}

.password-field-wrapper {
    position: relative;
    width: 100%;
}
.password-field-wrapper .form-control-custom {
    padding-right: 36px !important;
}
.password-field-wrapper .btn-password-toggle {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    width: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    color: #64748b;
    font-size: 0.95rem;
    cursor: pointer;
    padding: 0;
    z-index: 5;
    transition: color 0.15s ease-in-out;
}
.password-field-wrapper .btn-password-toggle:hover {
    color: #1e293b;
}
.password-field-wrapper .btn-password-toggle:focus {
    outline: none;
    color: #e8a838;
}
.password-field-wrapper .form-control-custom.is-invalid {
    background-image: none !important;
}

.student-photo-box {
    width: 130px;
    height: 165px;
    background-color: #f8fafc;
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
}
.student-photo-box:hover {
    border-color: #e8a838;
    background-color: #fffdf5;
    box-shadow: 0 4px 12px rgba(232, 168, 56, 0.16);
}
.student-photo-box img#thumbPhotoPreview {
    object-fit: cover !important;
    object-position: center top !important;
}

.btn-photo-remove-badge {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 22px;
    height: 22px;
    border-radius: 50% !important;
    background: #ef4444 !important;
    color: #ffffff !important;
    border: 2px solid #ffffff !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    display: none;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    z-index: 5;
    transition: transform 0.15s ease, background-color 0.15s ease;
}
.btn-photo-remove-badge:hover {
    background: #dc2626 !important;
    transform: scale(1.1);
    color: #ffffff !important;
}

.preview-card {
    background: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 0.85rem 1.15rem;
    position: sticky;
    top: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.preview-avatar-wrap {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #e2e8f0;
    margin: 0.5rem auto 0.75rem;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.preview-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.35rem 0;
    border-bottom: 1px dashed #f1f5f9;
    font-size: 0.78rem;
}
.preview-row:last-child {
    border-bottom: none;
}
.preview-label {
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.preview-val {
    color: #1e293b;
    font-weight: 500;
    text-align: right;
    max-width: 58%;
    word-break: break-word;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="employee-header">
    <div class="d-flex align-items-center gap-2.5">
        <div class="employee-header-icon shadow-xs">
            <i class="bi bi-person-fill"></i>
        </div>
        <div>
            <h3 class="fw-bold text-dark mb-0" style="font-size: 1.12rem;">បន្ថែមព័ត៌មានបុគ្គលិកថ្មី</h3>
            <p class="text-muted small mb-0" style="font-size: 0.74rem;">បង្កើតទិន្នន័យបុគ្គលិកពេញលេញ (ផ្ទាល់ខ្លួន, អាសយដ្ឋាន, ការងារ, ប្រាក់ខែ, ការសិក្សា និងទំនាក់ទំនងបន្ទាន់)</p>
        </div>
    </div>
    <div>
        <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-sm btn-outline-warning text-warning-emphasis bg-white border-warning fw-medium px-2.5 py-1 shadow-xs" style="border-radius: 6px; font-size: 0.78rem;">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅបញ្ជីបុគ្គលិក
        </a>
    </div>
</div>


<?php if(isset($errors) && $errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-3 mb-3 shadow-sm" role="alert" style="border-radius: 8px;">
        <i class="bi bi-exclamation-octagon-fill fs-5 mt-0.5 text-danger"></i>
        <div class="flex-grow-1">
            <h6 class="alert-heading fw-bold mb-1">សូមកែតម្រូវកំហុសឆ្គងខាងក្រោម៖</h6>
            <ul class="mb-0 ps-3 small">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('employees.store')); ?>" method="POST" id="employeeCreateForm" enctype="multipart/form-data" novalidate>
    <?php echo csrf_field(); ?>

    <?php
        $selectedAccountType = old('account_type', 'none');
    ?>

    <div class="row g-2.5">
        
        <div class="col-xl-8 col-lg-8">

            
            <div class="unified-form-card">

                
                <div class="wizard-stepper">
                    <div class="d-flex align-items-center justify-content-between wizard-steps-track gap-1 flex-nowrap overflow-x-auto">
                        <div class="wizard-step-tab active" data-step="1" onclick="showStep(1)" role="button" tabindex="0">
                            <span class="step-num">1</span>
                            <span class="step-label">ព័ត៌មានផ្ទាល់ខ្លួន</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="2" onclick="showStep(2)" role="button" tabindex="0">
                            <span class="step-num">2</span>
                            <span class="step-label">អាសយដ្ឋាន</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="3" onclick="showStep(3)" role="button" tabindex="0">
                            <span class="step-num">3</span>
                            <span class="step-label">ការងារ</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="4" onclick="showStep(4)" role="button" tabindex="0">
                            <span class="step-num">4</span>
                            <span class="step-label">ប្រាក់ខែ</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="5" onclick="showStep(5)" role="button" tabindex="0">
                            <span class="step-num">5</span>
                            <span class="step-label">ការសិក្សា</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="6" onclick="showStep(6)" role="button" tabindex="0">
                            <span class="step-num">6</span>
                            <span class="step-label">ទំនាក់ទំនងបន្ទាន់</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="7" onclick="showStep(7)" role="button" tabindex="0">
                            <span class="step-num">7</span>
                            <span class="step-label">គណនីប្រព័ន្ធ</span>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 4px; background-color: #e2e8f0; border-radius: 999px;">
                        <div class="progress-bar" id="wizardProgressBar" role="progressbar" style="width: 14.28%; background-color: #e8a838; transition: width 0.3s ease;"></div>
                    </div>
                </div>

                
                <div class="wizard-step-pane active" id="stepPane1" data-step="1">

                
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">1</span>
                        <h4 class="step-title">១. ព័ត៌មានផ្ទាល់ខ្លួន</h4>
                    </div>

                <div class="row g-2">
                    
                    <div class="col-md-4">
                        <label for="employee_code" class="form-label-kh">អត្តលេខបុគ្គលិក <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted px-2 py-1">
                                <i class="bi bi-lock-fill text-warning"></i>
                            </span>
                            <input type="text"
                                   name="employee_code"
                                   id="employee_code"
                                   class="form-control form-control-custom bg-light border-start-0 font-monospace fw-bold <?php $__errorArgs = ['employee_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('employee_code', $nextEmployeeCode ?? 'EMP0001')); ?>"
                                   readonly
                                   tabindex="-1">
                        </div>
                        <div class="form-text text-muted small mt-0.5" style="font-size: 0.68rem;">អត្តលេខស្វ័យប្រវត្តិ — ចាក់សោកុំឲ្យកែបាន</div>
                        <?php $__errorArgs = ['employee_code'];
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

                    
                    <div class="col-md-4">
                        <label for="last_name_khmer" class="form-label-kh">គោត្តនាម (ខ្មែរ) <span class="required-star">*</span></label>
                        <input type="text" name="last_name_khmer" id="last_name_khmer" class="form-control form-control-custom <?php $__errorArgs = ['last_name_khmer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('last_name_khmer')); ?>" placeholder="ឧ. សុខ" oninput="updateLivePreview()">
                        <?php $__errorArgs = ['last_name_khmer'];
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

                    
                    <div class="col-md-4">
                        <label for="first_name_khmer" class="form-label-kh">នាម (ខ្មែរ) <span class="required-star">*</span></label>
                        <input type="text" name="first_name_khmer" id="first_name_khmer" class="form-control form-control-custom <?php $__errorArgs = ['first_name_khmer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('first_name_khmer')); ?>" placeholder="ឧ. ចាន់ដា" oninput="updateLivePreview()">
                        <?php $__errorArgs = ['first_name_khmer'];
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

                    
                    <div class="col-md-6">
                        <label for="last_name" class="form-label-kh">គោត្តនាម (អក្សរឡាតាំង) <span class="required-star">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control form-control-custom <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('last_name')); ?>" placeholder="SOK" oninput="updateLivePreview()">
                        <?php $__errorArgs = ['last_name'];
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

                    
                    <div class="col-md-6">
                        <label for="first_name" class="form-label-kh">នាម (អក្សរឡាតាំង) <span class="required-star">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control form-control-custom <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('first_name')); ?>" placeholder="CHANDA" oninput="updateLivePreview()">
                        <?php $__errorArgs = ['first_name'];
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

                    
                    <div class="col-md-4">
                        <label for="gender" class="form-label-kh">ភេទ <span class="required-star">*</span></label>
                        <select name="gender" id="gender" class="form-select form-select-custom <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required onchange="updateLivePreview()">
                            <option value="">ជ្រើសរើសភេទ</option>
                            <option value="Male" <?php echo e(old('gender') == 'Male' ? 'selected' : ''); ?>>ប្រុស</option>
                            <option value="Female" <?php echo e(old('gender') == 'Female' ? 'selected' : ''); ?>>ស្រី</option>
                            <option value="Other" <?php echo e(old('gender') == 'Other' ? 'selected' : ''); ?>>ផ្សេងៗ</option>
                        </select>
                        <?php $__errorArgs = ['gender'];
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

                    
                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label-kh">ថ្ងៃខែឆ្នាំកំណើត <span class="required-star">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control form-control-custom <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('date_of_birth')); ?>" onchange="updateLivePreview()">
                        <?php $__errorArgs = ['date_of_birth'];
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

                    
                    <div class="col-md-4">
                        <label for="phone" class="form-label-kh">លេខទូរស័ព្ទ <span class="required-star">*</span></label>
                        <input type="text" name="phone" id="phone" class="form-control form-control-custom <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('phone')); ?>" placeholder="012 345 678" oninput="updateLivePreview()">
                        <?php $__errorArgs = ['phone'];
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

                    
                    <div class="col-md-4">
                        <label for="contact_email" class="form-label-kh">អ៊ីមែលបុគ្គលិក</label>
                        <input type="email" name="contact_email" id="contact_email" class="form-control form-control-custom <?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('contact_email')); ?>" placeholder="staff@example.com">
                        <?php $__errorArgs = ['contact_email'];
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

                    
                    <div class="col-md-4">
                        <label for="marital_status" class="form-label-kh">ស្ថានភាពអាពាហ៍ពិពាហ៍</label>
                        <select name="marital_status" id="marital_status" class="form-select form-select-custom <?php $__errorArgs = ['marital_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">ជ្រើសរើសស្ថានភាព</option>
                            <option value="Single" <?php echo e(old('marital_status') == 'Single' ? 'selected' : ''); ?>>នៅលីវ</option>
                            <option value="Married" <?php echo e(old('marital_status') == 'Married' ? 'selected' : ''); ?>>រៀបការរួច</option>
                            <option value="Divorced" <?php echo e(old('marital_status') == 'Divorced' ? 'selected' : ''); ?>>លែងលះ</option>
                            <option value="Widowed" <?php echo e(old('marital_status') == 'Widowed' ? 'selected' : ''); ?>>មេម៉ាយ / ពោះម៉ាយ</option>
                        </select>
                        <?php $__errorArgs = ['marital_status'];
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

                    
                    <div class="col-md-4">
                        <label for="national_id" class="form-label-kh">អត្តសញ្ញាណប័ណ្ណ / លិខិតឆ្លងដែន <span class="required-star">*</span></label>
                        <input type="text" name="national_id" id="national_id" class="form-control form-control-custom <?php $__errorArgs = ['national_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('national_id')); ?>" placeholder="លេខអត្តសញ្ញាណប័ណ្ណ ឬ លិខិតឆ្លងដែន">
                        <?php $__errorArgs = ['national_id'];
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

                    
                    <div class="col-12 mt-2 pt-2 border-top">
                        <label class="form-label-kh d-block mb-1 text-muted" style="font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-geo-alt-fill text-warning me-1"></i> ទីកន្លែងកំណើត
                        </label>
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="birth_province" class="form-label-kh">ខេត្ត/រាជធានីកំណើត <span class="required-star">*</span></label>
                        <select name="birth_province" id="birth_province" class="form-select form-control-custom <?php $__errorArgs = ['birth_province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php echo $__env->make('partials.cambodia_province_options', ['selected' => old('birth_province')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </select>
                        <?php $__errorArgs = ['birth_province'];
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

                    <div class="col-md-3 col-6">
                        <label for="birth_district" class="form-label-kh">ស្រុក/ខណ្ឌកំណើត <span class="required-star">*</span></label>
                        <select name="birth_district" id="birth_district" class="form-select form-control-custom <?php $__errorArgs = ['birth_district'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-selected="<?php echo e(old('birth_district')); ?>">
                            <option value="">ជ្រើសខេត្តជាមុន</option>
                        </select>
                        <?php $__errorArgs = ['birth_district'];
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

                    <div class="col-md-3 col-6">
                        <label for="birth_commune" class="form-label-kh">ឃុំ/សង្កាត់កំណើត <span class="required-star">*</span></label>
                        <select name="birth_commune" id="birth_commune" class="form-select form-control-custom <?php $__errorArgs = ['birth_commune'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-selected="<?php echo e(old('birth_commune')); ?>">
                            <option value="">ជ្រើសស្រុកជាមុន</option>
                        </select>
                        <?php $__errorArgs = ['birth_commune'];
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

                    <div class="col-md-3 col-6">
                        <label for="birth_village" class="form-label-kh">ភូមិកំណើត <span class="required-star">*</span></label>
                        <select name="birth_village" id="birth_village" class="form-select form-control-custom <?php $__errorArgs = ['birth_village'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-selected="<?php echo e(old('birth_village')); ?>">
                            <option value="">ជ្រើសឃុំជាមុន</option>
                        </select>
                        <?php $__errorArgs = ['birth_village'];
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

                    
                    <div class="col-12 mt-1">
                        <label class="form-label-kh d-block mb-1">រូបថតបុគ្គលិក <span class="required-star">*</span> <span class="text-muted fw-normal" style="font-size: 0.72rem;">(ទំហំ 3x4 ឬ 4x6)</span></label>
                        <div style="width: 130px;">
                            <div class="student-photo-box text-center p-2" onclick="document.getElementById('photo_input').click()" title="ចុចដើម្បីជ្រើសរើស ឬប្តូររូបថត">
                                <img id="thumbPhotoPreview" src="" alt="Preview" class="position-absolute w-100 h-100 d-none" style="object-fit: cover; object-position: center top; top: 0; left: 0;">
                                <div id="thumbPhotoPlaceholder" class="d-flex flex-column align-items-center text-muted w-100">
                                    <i class="bi bi-person-bounding-box mb-1 text-warning" style="font-size: 2rem; line-height: 1;"></i>
                                    <span class="fw-medium text-dark" style="font-size: 0.78rem; line-height: 1.3;">ចុចដើម្បីផ្ទុកឡើងរូបថត</span>
                                    <span class="text-muted" style="font-size: 0.68rem; line-height: 1.2;">3x4 ឬ 4x6</span>
                                </div>
                            </div>

                            
                            <div class="d-flex align-items-center gap-1 mt-1.5 w-100">
                                <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1 d-flex align-items-center justify-content-center gap-1 py-1" onclick="document.getElementById('photo_input').click()" style="font-size: 0.72rem; border-radius: 4px;">
                                    <i class="bi bi-camera"></i> <span id="photoActionLabel">ជ្រើសរូប</span>
                                </button>
                                <button type="button" id="btnRemovePhoto" class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-center py-1 px-2 d-none" onclick="removeSelectedPhoto()" style="font-size: 0.72rem; border-radius: 4px;" title="លុបរូបថត">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </div>
                        <input type="file" name="photo" id="photo_input" class="d-none <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/png, image/jpeg, image/webp" onchange="previewEmployeePhoto(this)">
                        <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1" style="font-size: 0.72rem;"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                </div>
                </div> 

                
                <div class="wizard-step-pane" id="stepPane2" data-step="2" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">2</span>
                        <h4 class="step-title">២. ព័ត៌មានអាសយដ្ឋាន</h4>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label-kh d-flex align-items-center gap-1.5 text-primary mb-2" style="font-size: 0.8rem; font-weight: 700;">
                            <i class="bi bi-geo-alt-fill"></i> អាសយដ្ឋានបច្ចុប្បន្ន (Current Address)
                        </label>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label for="current_address" class="form-label-kh">លេខផ្លូវ</label>
                                <input type="text" name="current_address" id="current_address" class="form-control form-control-custom <?php $__errorArgs = ['current_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('current_address')); ?>" placeholder="បញ្ចូលលេខផ្លូវ..." oninput="syncPermanentAddress()">
                                <?php $__errorArgs = ['current_address'];
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

                            <div class="col-md-3 col-6">
                                <label for="province" class="form-label-kh">ខេត្ត / រាជធានី <span class="required-star">*</span></label>
                                <select name="province" id="province" class="form-select form-control-custom <?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required onchange="syncPermanentAddress()">
                                    <?php echo $__env->make('partials.cambodia_province_options', ['selected' => old('province')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </select>
                                <?php $__errorArgs = ['province'];
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

                            <div class="col-md-3 col-6">
                                <label for="district" class="form-label-kh">ស្រុក / ខណ្ឌ <span class="required-star">*</span></label>
                                <select name="district" id="district" class="form-select form-control-custom <?php $__errorArgs = ['district'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-selected="<?php echo e(old('district')); ?>" required onchange="syncPermanentAddress()">
                                    <option value="">ជ្រើសខេត្តជាមុន</option>
                                </select>
                                <?php $__errorArgs = ['district'];
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

                            <div class="col-md-3 col-6">
                                <label for="commune" class="form-label-kh">ឃុំ / សង្កាត់ <span class="required-star">*</span></label>
                                <select name="commune" id="commune" class="form-select form-control-custom <?php $__errorArgs = ['commune'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-selected="<?php echo e(old('commune')); ?>" required onchange="syncPermanentAddress()">
                                    <option value="">ជ្រើសស្រុកជាមុន</option>
                                </select>
                                <?php $__errorArgs = ['commune'];
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

                            <div class="col-md-3 col-6">
                                <label for="village" class="form-label-kh">ភូមិ <span class="required-star">*</span></label>
                                <select name="village" id="village" class="form-select form-control-custom <?php $__errorArgs = ['village'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-selected="<?php echo e(old('village')); ?>" required onchange="syncPermanentAddress()">
                                    <option value="">ជ្រើសឃុំជាមុន</option>
                                </select>
                                <?php $__errorArgs = ['village'];
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

                    
                    <div class="pt-2.5 border-top">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <label class="form-label-kh d-flex align-items-center gap-1.5 text-secondary mb-0" style="font-size: 0.8rem; font-weight: 700;">
                                <i class="bi bi-house-door-fill text-warning"></i> អាសយដ្ឋានអចិន្ត្រៃយ៍ (Permanent Address)
                            </label>
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="checkbox" id="sameAsCurrentAddress" onchange="toggleSameAddress(this)" style="cursor: pointer; accent-color: #e8a838;">
                                <label class="form-check-label text-dark fw-medium small" for="sameAsCurrentAddress" style="cursor: pointer; font-size: 0.76rem;">
                                    ដូចគ្នានឹងអាសយដ្ឋានបច្ចុប្បន្ន
                                </label>
                            </div>
                        </div>

                        
                        <input type="hidden" name="permanent_address" id="permanent_address" value="<?php echo e(old('permanent_address')); ?>">

                        <div class="row g-2">
                            <div class="col-md-12">
                                <label for="permanent_street" class="form-label-kh">លេខផ្លូវ</label>
                                <input type="text" name="permanent_street" id="permanent_street" class="form-control form-control-custom" value="<?php echo e(old('permanent_street')); ?>" placeholder="បញ្ចូលលេខផ្លូវ..." oninput="updateCompiledPermanentAddress()">
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="permanent_province" class="form-label-kh">ខេត្ត / រាជធានី <span class="required-star">*</span></label>
                                <select name="permanent_province" id="permanent_province" class="form-select form-control-custom" onchange="updateCompiledPermanentAddress()">
                                    <?php echo $__env->make('partials.cambodia_province_options', ['selected' => old('permanent_province')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </select>
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="permanent_district" class="form-label-kh">ស្រុក / ខណ្ឌ <span class="required-star">*</span></label>
                                <select name="permanent_district" id="permanent_district" class="form-select form-control-custom" data-selected="<?php echo e(old('permanent_district')); ?>" onchange="updateCompiledPermanentAddress()">
                                    <option value="">ជ្រើសខេត្តជាមុន</option>
                                </select>
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="permanent_commune" class="form-label-kh">ឃុំ / សង្កាត់ <span class="required-star">*</span></label>
                                <select name="permanent_commune" id="permanent_commune" class="form-select form-control-custom" data-selected="<?php echo e(old('permanent_commune')); ?>" onchange="updateCompiledPermanentAddress()">
                                    <option value="">ជ្រើសស្រុកជាមុន</option>
                                </select>
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="permanent_village" class="form-label-kh">ភូមិ <span class="required-star">*</span></label>
                                <select name="permanent_village" id="permanent_village" class="form-select form-control-custom" data-selected="<?php echo e(old('permanent_village')); ?>" onchange="updateCompiledPermanentAddress()">
                                    <option value="">ជ្រើសឃុំជាមុន</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                </div> 

                
                <div class="wizard-step-pane" id="stepPane3" data-step="3" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">3</span>
                        <h4 class="step-title">៣. ព័ត៌មានការងារ</h4>
                    </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <label for="employee_position_id" class="form-label-kh">តួនាទី / មុខតំណែង <span class="required-star">*</span></label>
                        <select name="employee_position_id" id="employee_position_id" class="form-select form-select-custom <?php $__errorArgs = ['employee_position_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required onchange="updateLivePreview()">
                            <option value="">ជ្រើសរើសមុខតំណែង</option>
                            <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pos->id); ?>" data-name="<?php echo e($pos->name); ?>" <?php echo e(old('employee_position_id') == $pos->id ? 'selected' : ''); ?>>
                                    <?php echo e($pos->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['employee_position_id'];
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

                    <div class="col-md-6">
                        <label for="employee_department_id" class="form-label-kh">ផ្នែក</label>
                        <select name="employee_department_id" id="employee_department_id" class="form-select form-select-custom <?php $__errorArgs = ['employee_department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onchange="updateLivePreview()">
                            <option value="">ជ្រើសរើសផ្នែក</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dept->id); ?>" data-name="<?php echo e($dept->name); ?>" <?php echo e(old('employee_department_id') == $dept->id ? 'selected' : ''); ?>>
                                    <?php echo e($dept->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['employee_department_id'];
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

                    <div class="col-md-6">
                        <label for="employee_type" class="form-label-kh">ប្រភេទបុគ្គលិក <span class="required-star">*</span></label>
                        <select name="employee_type" id="employee_type" class="form-select form-select-custom <?php $__errorArgs = ['employee_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">ជ្រើសរើសប្រភេទបុគ្គលិក</option>
                            <option value="Teacher" <?php echo e(old('employee_type') == 'Teacher' ? 'selected' : ''); ?>>គ្រូបង្រៀន</option>
                            <option value="Administrative Staff" <?php echo e(old('employee_type') == 'Administrative Staff' ? 'selected' : ''); ?>>បុគ្គលិករដ្ឋបាល</option>
                            <option value="Accountant" <?php echo e(old('employee_type') == 'Accountant' ? 'selected' : ''); ?>>គណនេយ្យករ</option>
                            <option value="Receptionist" <?php echo e(old('employee_type') == 'Receptionist' ? 'selected' : ''); ?>>បុគ្គលិកទទួលភ្ញៀវ</option>
                            <option value="Librarian" <?php echo e(old('employee_type') == 'Librarian' ? 'selected' : ''); ?>>បណ្ណារក្ស</option>
                            <option value="IT Support" <?php echo e(old('employee_type') == 'IT Support' ? 'selected' : ''); ?>>ព័ត៌មានវិទ្យា</option>
                            <option value="Support Staff" <?php echo e(old('employee_type') == 'Support Staff' ? 'selected' : ''); ?>>បុគ្គលិកជំនួយ</option>
                            <option value="Other" <?php echo e(old('employee_type') == 'Other' ? 'selected' : ''); ?>>ផ្សេងៗ</option>
                        </select>
                        <?php $__errorArgs = ['employee_type'];
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

                    <div class="col-md-6">
                        <label for="date_of_joining" class="form-label-kh">ថ្ងៃចូលបម្រើការងារ <span class="required-star">*</span></label>
                        <input type="date" name="date_of_joining" id="date_of_joining" class="form-control form-control-custom <?php $__errorArgs = ['date_of_joining'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('date_of_joining', date('Y-m-d'))); ?>" required onchange="updateLivePreview()">
                        <?php $__errorArgs = ['date_of_joining'];
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

                    <div class="col-md-4">
                        <label for="employee_status_id" class="form-label-kh">ស្ថានភាពការងារ <span class="required-star">*</span></label>
                        <select name="employee_status_id" id="employee_status_id" class="form-select form-select-custom <?php $__errorArgs = ['employee_status_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required onchange="updateLivePreview()">
                            <option value="">ជ្រើសរើសស្ថានភាពការងារ</option>
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($stat->id); ?>" data-name="<?php echo e($stat->khmer_name); ?>" <?php echo e(old('employee_status_id', 1) == $stat->id ? 'selected' : ''); ?>>
                                    <?php echo e($stat->khmer_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['employee_status_id'];
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

                    <div class="col-md-4">
                        <label for="contract_type" class="form-label-kh">កិច្ចសន្យាការងារ</label>
                        <select name="contract_type" id="contract_type" class="form-select form-select-custom <?php $__errorArgs = ['contract_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onchange="onEmployeeContractTypeChange(this)">
                            <option value="">ជ្រើសរើសប្រភេទកិច្ចសន្យា</option>
                            <option value="Permanent" <?php echo e(old('contract_type') == 'Permanent' ? 'selected' : ''); ?>>កិច្ចសន្យាអចិន្ត្រៃយ៍</option>
                            <option value="Fixed Term" <?php echo e(old('contract_type') == 'Fixed Term' ? 'selected' : ''); ?>>កិច្ចសន្យាមានកំណត់</option>
                            <option value="Probation" <?php echo e(old('contract_type', 'Probation') == 'Probation' ? 'selected' : ''); ?>>សាកល្បងការងារ</option>
                            <option value="Part-time" <?php echo e(old('contract_type') == 'Part-time' ? 'selected' : ''); ?>>ក្រៅម៉ោង</option>
                            <option value="Internship" <?php echo e(old('contract_type') == 'Internship' ? 'selected' : ''); ?>>កម្មសិក្សា</option>
                        </select>
                        <?php $__errorArgs = ['contract_type'];
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

                    <div class="col-md-4">
                        <label for="supervisor_name" class="form-label-kh">អ្នកគ្រប់គ្រងផ្ទាល់</label>
                        <input type="text" name="supervisor_name" id="supervisor_name" class="form-control form-control-custom <?php $__errorArgs = ['supervisor_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('supervisor_name')); ?>" placeholder="ឈ្មោះអ្នកគ្រប់គ្រងផ្ទាល់">
                        <?php $__errorArgs = ['supervisor_name'];
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

                    <div class="col-md-12">
                        <label for="work_location" class="form-label-kh">ទីតាំងបំពេញការងារ</label>
                        <input type="text" name="work_location" id="work_location" class="form-control form-control-custom <?php $__errorArgs = ['work_location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('work_location')); ?>" placeholder="ឧ. សាខាចម្បង, អគារ A, បន្ទប់រដ្ឋបាល...">
                        <?php $__errorArgs = ['work_location'];
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

                
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2 p-2.5 rounded-2" style="background: #fffdf5; border: 1px solid #fde68a;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width:30px;height:30px;background:#fef3c7;color:#92400e;font-size:1rem;">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </span>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:0.86rem;">កិច្ចសន្យាការងារដំបូង (Initial Employment Contract)</div>
                                <div class="text-muted" style="font-size:0.73rem;">បង្កើតកិច្ចសន្យាការងារសកម្មដោយស្វ័យប្រវត្តិក្នុពេលចុះឈ្មោះបុគ្គលិកថ្មី (មិនបាច់ទៅបង្កើតម្តងទៀត)</div>
                            </div>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="create_contract" id="create_contract" value="1" checked onchange="toggleContractFields(this)" style="cursor:pointer; width: 2.2rem; height: 1.15rem; accent-color: #e8a838;">
                            <label class="form-check-label fw-bold text-dark small" for="create_contract" style="cursor:pointer; font-size: 0.78rem;">
                                បង្កើតកិច្ចសន្យាភ្លាមៗ
                            </label>
                        </div>
                    </div>

                    <div id="contract_fields_wrapper">
                        <div class="row g-2 p-2.5 bg-light rounded-2 border">
                            
                            
                            <div class="col-md-3 col-sm-6">
                                <label for="contract_number" class="form-label-kh">លេខកិច្ចសន្យា <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted px-2 py-1" title="ចាក់សោស្វ័យប្រវត្តិ">
                                        <i class="bi bi-lock-fill text-warning"></i>
                                    </span>
                                    <input type="text"
                                           name="contract_number"
                                           id="contract_number"
                                           class="form-control form-control-custom bg-light border-start-0 font-monospace fw-bold <?php $__errorArgs = ['contract_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('contract_number', $nextContractNumber ?? 'CTR-001')); ?>"
                                           readonly
                                           tabindex="-1"
                                           title="លេខកិច្ចសន្យាត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ និងចាក់សោ"
                                           oninput="updateLivePreview()">
                                </div>
                                <div class="form-text text-muted" style="font-size:0.68rem;">
                                    <i class="bi bi-lock-fill text-warning me-1"></i>បង្កើតដោយស្វ័យប្រវត្តិ — ចាក់សោ
                                </div>
                                <?php $__errorArgs = ['contract_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="col-md-3 col-sm-6">
                                <label for="contract_type" class="form-label-kh">ប្រភេទកិច្ចសន្យា <span class="required-star">*</span></label>
                                <select name="contract_type" id="contract_type" class="form-select form-select-custom <?php $__errorArgs = ['contract_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onchange="onEmployeeContractTypeChange(this)">
                                    <option value="Probation" <?php if(old('contract_type', 'Probation') === 'Probation'): echo 'selected'; endif; ?>>ការសាកល្បងការងារ (Probation)</option>
                                    <option value="Fixed Term" <?php if(old('contract_type') === 'Fixed Term'): echo 'selected'; endif; ?>>កិច្ចសន្យាមានកំណត់ថិរវេលា (Fixed Term)</option>
                                    <option value="Permanent" <?php if(old('contract_type') === 'Permanent'): echo 'selected'; endif; ?>>កិច្ចសន្យាមិនកំណត់ថិរវេលា (Permanent)</option>
                                    <option value="Part-time" <?php if(old('contract_type') === 'Part-time'): echo 'selected'; endif; ?>>ការងារក្រៅម៉ោង (Part-Time)</option>
                                    <option value="Internship" <?php if(old('contract_type') === 'Internship'): echo 'selected'; endif; ?>>កម្មសិក្សាការងារ (Internship)</option>
                                </select>
                                <div class="form-text text-muted" style="font-size:0.68rem;">ប្រភេទនៃកិច្ចព្រមព្រៀងការងារ</div>
                                <?php $__errorArgs = ['contract_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="col-md-3 col-sm-6">
                                <label for="contract_start_date" class="form-label-kh">ថ្ងៃចាប់ផ្តើមកិច្ចសន្យា <span class="required-star">*</span></label>
                                <input type="date"
                                       name="contract_start_date"
                                       id="contract_start_date"
                                       class="form-control form-control-custom <?php $__errorArgs = ['contract_start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('contract_start_date', date('Y-m-d'))); ?>"
                                       onchange="onContractDatesChange()">
                                <div class="form-text text-muted" style="font-size:0.68rem;">ស្របតាមថ្ងៃចូលបម្រើការងារ</div>
                                <?php $__errorArgs = ['contract_start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="col-md-3 col-sm-6">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="contract_end_date" class="form-label-kh mb-0">ថ្ងៃបញ្ចប់កិច្ចសន្យា</label>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-link p-0 text-muted" style="font-size:0.68rem; text-decoration:none;" onclick="addMonthsToContractEndDate(3)">+3ខែ</button>
                                        <span class="text-muted" style="font-size:0.68rem;">|</span>
                                        <button type="button" class="btn btn-link p-0 text-muted" style="font-size:0.68rem; text-decoration:none;" onclick="addMonthsToContractEndDate(12)">+1ឆ្នាំ</button>
                                    </div>
                                </div>
                                <input type="date"
                                       name="contract_end_date"
                                       id="contract_end_date"
                                       class="form-control form-control-custom mt-1 <?php $__errorArgs = ['contract_end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('contract_end_date', date('Y-m-d', strtotime('+3 months')))); ?>"
                                       onchange="updateLivePreview()">
                                <div class="form-text text-muted" style="font-size:0.68rem;" id="end_date_hint">សាកល្បង 3 ខែ (គណនាស្វ័យប្រវត្តិ)</div>
                                <?php $__errorArgs = ['contract_end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            
                            <div class="col-md-3 col-sm-6">
                                <label for="contract_work_hours" class="form-label-kh">ម៉ោងការងារក្នុងមួយសប្តាហ៍</label>
                                <div class="input-group">
                                    <input type="number"
                                           name="contract_work_hours"
                                           id="contract_work_hours"
                                           class="form-control form-control-custom"
                                           value="<?php echo e(old('contract_work_hours', 40)); ?>"
                                           min="1" max="168">
                                    <span class="input-group-text bg-white small px-2 py-1" style="font-size:0.75rem;">ម៉ោង/សប្តាហ៍</span>
                                </div>
                                <div class="form-text text-muted" style="font-size:0.68rem;">ស្តង់ដារ ៤០ ម៉ោង/សប្តាហ៍</div>
                            </div>

                            
                            <div class="col-md-3 col-sm-6">
                                <label for="contract_status" class="form-label-kh">ស្ថានភាពកិច្ចសន្យា</label>
                                <select name="contract_status" id="contract_status" class="form-select form-select-custom <?php $__errorArgs = ['contract_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onchange="updateLivePreview()">
                                    <option value="active" <?php if(old('contract_status', 'active') === 'active'): echo 'selected'; endif; ?>>ដំណើរការ (សកម្ម)</option>
                                    <option value="draft" <?php if(old('contract_status') === 'draft'): echo 'selected'; endif; ?>>ពង្រាង (Draft)</option>
                                </select>
                                <div class="form-text text-muted" style="font-size:0.68rem;">ស្ថានភាពដំបូងនៃកិច្ចសន្យា</div>
                                <?php $__errorArgs = ['contract_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="col-md-3 col-sm-6">
                                <label for="contract_salary_structure_id" class="form-label-kh">រចនាសម្ព័ន្ធប្រាក់ខែ (បើមាន)</label>
                                <select name="contract_salary_structure_id" id="contract_salary_structure_id" class="form-select form-select-custom">
                                    <option value="">ជ្រើសរើស</option>
                                    <?php if(isset($salaryStructures)): ?>
                                        <?php $__currentLoopData = $salaryStructures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $struct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($struct->id); ?>" <?php echo e(old('contract_salary_structure_id') == $struct->id ? 'selected' : ''); ?>>
                                                <?php echo e($struct->name); ?> ($<?php echo e(number_format($struct->base_salary, 2)); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text text-muted" style="font-size:0.68rem;">រចនាសម្ព័ន្ធប្រាក់បៀវត្ស</div>
                            </div>

                            
                            <div class="col-md-3 col-sm-6">
                                <label for="contract_salary" class="form-label-kh">ប្រាក់ខែក្នុងកិច្ចសន្យា ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-success fw-bold px-2 py-1">$</span>
                                    <input type="number"
                                           step="0.01" min="0"
                                           name="contract_salary"
                                           id="contract_salary"
                                           class="form-control form-control-custom border-start-0"
                                           value="<?php echo e(old('contract_salary')); ?>"
                                           placeholder="យកតាមប្រាក់ខែគោល"
                                           oninput="updateLivePreview()">
                                </div>
                                <div class="form-text text-muted" style="font-size:0.68rem;">ចម្លងស្វ័យប្រវត្តិតាមប្រាក់ខែគោលដែលបញ្ចូល</div>
                            </div>

                            
                            
                            <div class="col-12">
                                <label for="contract_notes" class="form-label-kh">កំណត់សម្គាល់កិច្ចសន្យា</label>
                                <input type="text"
                                       name="contract_notes"
                                       id="contract_notes"
                                       class="form-control form-control-custom"
                                       value="<?php echo e(old('contract_notes', 'កិច្ចសន្យាការងារដំបូង (បង្កើតស្វ័យប្រវត្តិជាមួយទិន្នន័យបុគ្គលិក)')); ?>"
                                       placeholder="កំណត់សម្គាល់បន្ថែមលើកិច្ចសន្យា...">
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </div> 

                
                <div class="wizard-step-pane" id="stepPane4" data-step="4" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">4</span>
                        <h4 class="step-title">៤. ព័ត៌មានប្រាក់ខែ</h4>
                    </div>

                <div class="row g-2">
                    <div class="col-md-3">
                        <label for="basic_salary" class="form-label-kh">ប្រាក់ខែគោល ($) <span class="required-star">*</span></label>
                        <input type="number" step="0.01" min="0" name="basic_salary" id="basic_salary" class="form-control form-control-custom <?php $__errorArgs = ['basic_salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('basic_salary')); ?>" placeholder="ឧ. 500.00">
                        <?php $__errorArgs = ['basic_salary'];
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

                    <div class="col-md-3">
                        <label for="salary_type" class="form-label-kh">ប្រភេទប្រាក់ខែ</label>
                        <select name="salary_type" id="salary_type" class="form-select form-select-custom <?php $__errorArgs = ['salary_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="Monthly" <?php echo e(old('salary_type', 'Monthly') == 'Monthly' ? 'selected' : ''); ?>>ប្រចាំខែ</option>
                            <option value="Daily" <?php echo e(old('salary_type') == 'Daily' ? 'selected' : ''); ?>>ប្រចាំថ្ងៃ</option>
                            <option value="Hourly" <?php echo e(old('salary_type') == 'Hourly' ? 'selected' : ''); ?>>ប្រចាំម៉ោង</option>
                        </select>
                        <?php $__errorArgs = ['salary_type'];
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

                    <div class="col-md-3">
                        <label for="allowance" class="form-label-kh">ប្រាក់ឧបត្ថម្ភ ($)</label>
                        <input type="number" step="0.01" min="0" name="allowance" id="allowance" class="form-control form-control-custom <?php $__errorArgs = ['allowance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('allowance', 0)); ?>" placeholder="0.00">
                        <?php $__errorArgs = ['allowance'];
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

                    <div class="col-md-3">
                        <label for="payment_method" class="form-label-kh">វិធីសាស្ត្របើកប្រាក់ខែ</label>
                        <select name="payment_method" id="payment_method" class="form-select form-select-custom <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="Bank Transfer" <?php echo e(old('payment_method', 'Bank Transfer') == 'Bank Transfer' ? 'selected' : ''); ?>>ផ្ទេរតាមធនាគារ</option>
                            <option value="Cash" <?php echo e(old('payment_method') == 'Cash' ? 'selected' : ''); ?>>សាច់ប្រាក់ផ្ទាល់</option>
                            <option value="Cheque" <?php echo e(old('payment_method') == 'Cheque' ? 'selected' : ''); ?>>មូលប្បទានប័ត្រ</option>
                        </select>
                        <?php $__errorArgs = ['payment_method'];
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

                    <div class="col-md-4">
                        <label for="bank_name" class="form-label-kh">ឈ្មោះធនាគារ</label>
                        <input type="text" name="bank_name" id="bank_name" list="bank_suggestions" class="form-control form-control-custom <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('bank_name')); ?>" placeholder="ABA, ACLEDA, Canadia...">
                        <datalist id="bank_suggestions">
                            <option value="ABA Bank"></option>
                            <option value="ACLEDA Bank"></option>
                            <option value="Canadia Bank"></option>
                            <option value="Wing Bank"></option>
                            <option value="Sathapana Bank"></option>
                            <option value="J Trust Royal Bank"></option>
                            <option value="Vattanac Bank"></option>
                        </datalist>
                        <?php $__errorArgs = ['bank_name'];
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

                    <div class="col-md-4">
                        <label for="bank_account_number" class="form-label-kh">លេខគណនីធនាគារ</label>
                        <input type="text" name="bank_account_number" id="bank_account_number" class="form-control form-control-custom font-monospace <?php $__errorArgs = ['bank_account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('bank_account_number')); ?>" placeholder="000 123 456">
                        <?php $__errorArgs = ['bank_account_number'];
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

                    <div class="col-md-4">
                        <label for="salary_effective_date" class="form-label-kh">កាលបរិច្ឆេទមានប្រសិទ្ធភាព</label>
                        <input type="date" name="salary_effective_date" id="salary_effective_date" class="form-control form-control-custom <?php $__errorArgs = ['salary_effective_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('salary_effective_date')); ?>">
                        <?php $__errorArgs = ['salary_effective_date'];
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
                </div> 

                
                <div class="wizard-step-pane" id="stepPane5" data-step="5" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">5</span>
                        <h4 class="step-title">៥. ព័ត៌មានការសិក្សា និងជំនាញ</h4>
                    </div>

                <div class="row g-2">
                    <div class="col-md-4">
                        <label for="highest_qualification" class="form-label-kh">កម្រិតវប្បធម៌ខ្ពស់បំផុត</label>
                        <select name="highest_qualification" id="highest_qualification" class="form-select form-select-custom <?php $__errorArgs = ['highest_qualification'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">ជ្រើសរើសកម្រិតវប្បធម៌</option>
                            <option value="Doctorate" <?php echo e(old('highest_qualification') == 'Doctorate' ? 'selected' : ''); ?>>បណ្ឌិត</option>
                            <option value="Master" <?php echo e(old('highest_qualification') == 'Master' ? 'selected' : ''); ?>>អនុបណ្ឌិត</option>
                            <option value="Bachelor" <?php echo e(old('highest_qualification') == 'Bachelor' ? 'selected' : ''); ?>>បរិញ្ញាបត្រ</option>
                            <option value="Associate" <?php echo e(old('highest_qualification') == 'Associate' ? 'selected' : ''); ?>>បរិញ្ញាបត្ររង</option>
                            <option value="High School" <?php echo e(old('highest_qualification') == 'High School' ? 'selected' : ''); ?>>មធ្យមសិក្សាទុតិយភូមិ (បាក់ឌុប)</option>
                            <option value="Other" <?php echo e(old('highest_qualification') == 'Other' ? 'selected' : ''); ?>>ផ្សេងៗ</option>
                        </select>
                        <?php $__errorArgs = ['highest_qualification'];
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

                    <div class="col-md-5">
                        <label for="university" class="form-label-kh">សាកលវិទ្យាល័យ / សាលារៀន</label>
                        <input type="text" name="university" id="university" class="form-control form-control-custom <?php $__errorArgs = ['university'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('university')); ?>" placeholder="ឈ្មោះគ្រឹះស្ថានសិក្សា / សាកលវិទ្យាល័យ">
                        <?php $__errorArgs = ['university'];
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

                    <div class="col-md-3">
                        <label for="graduation_year" class="form-label-kh">ឆ្នាំបញ្ចប់ការសិក្សា</label>
                        <input type="text" name="graduation_year" id="graduation_year" class="form-control form-control-custom <?php $__errorArgs = ['graduation_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('graduation_year')); ?>" placeholder="ឧ. 2022">
                        <?php $__errorArgs = ['graduation_year'];
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

                    <div class="col-md-6">
                        <label for="major" class="form-label-kh">ជំនាញឯកទេស / មុខវិជ្ជា</label>
                        <input type="text" name="major" id="major" class="form-control form-control-custom <?php $__errorArgs = ['major'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('major')); ?>" placeholder="ឧ. អក្សរសាស្ត្រខ្មែរ, គណិតវិទ្យា, ព័ត៌មានវិទ្យា...">
                        <?php $__errorArgs = ['major'];
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

                    <div class="col-md-6">
                        <label for="teaching_qualification" class="form-label-kh">គរុកោសល្យ / គរុវិជ្ជា</label>
                        <input type="text" name="teaching_qualification" id="teaching_qualification" list="teaching_qual_list" class="form-control form-control-custom <?php $__errorArgs = ['teaching_qualification'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('teaching_qualification')); ?>" placeholder="ឧ. គរុកោសល្យជាន់ខ្ពស់ NIE, មូលដ្ឋាន...">
                        <datalist id="teaching_qual_list">
                            <option value="វិទ្យាស្ថានជាតិអប់រំ (NIE - ឧត្តម)"></option>
                            <option value="គរុកោសល្យភូមិភាគ (RTTC - មូលដ្ឋាន)"></option>
                            <option value="គរុកោសល្យខេត្ត (PTTC - បឋម)"></option>
                            <option value="គ្មាន (មិនមាន)"></option>
                        </datalist>
                        <?php $__errorArgs = ['teaching_qualification'];
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

                    <div class="col-md-6">
                        <label for="professional_certificate" class="form-label-kh">វិញ្ញាបនបត្រវិជ្ជាជីវៈ</label>
                        <textarea name="professional_certificate" id="professional_certificate" rows="2" class="form-control form-control-custom <?php $__errorArgs = ['professional_certificate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="វិញ្ញាបនបត្របណ្តុះបណ្តាលវិជ្ជាជីវៈផ្សេងៗ..."><?php echo e(old('professional_certificate')); ?></textarea>
                        <?php $__errorArgs = ['professional_certificate'];
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

                    <div class="col-md-6">
                        <label for="skills" class="form-label-kh">ជំនាញ និងសមត្ថភាព</label>
                        <textarea name="skills" id="skills" rows="2" class="form-control form-control-custom <?php $__errorArgs = ['skills'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="ជំនាញកុំព្យូទ័រ, ភាសាបរទេស, ភាពជាអ្នកដឹកនាំ..."><?php echo e(old('skills')); ?></textarea>
                        <?php $__errorArgs = ['skills'];
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

                    <div class="col-md-12">
                        <label for="experience_years" class="form-label-kh">បទពិសោធន៍ការងារ (ឆ្នាំ)</label>
                        <input type="text" name="experience_years" id="experience_years" class="form-control form-control-custom <?php $__errorArgs = ['experience_years'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('experience_years')); ?>" placeholder="ឧ. បទពិសោធន៍បង្រៀន ៥ ឆ្នាំ, រដ្ឋបាល ៣ ឆ្នាំ...">
                        <?php $__errorArgs = ['experience_years'];
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
                </div> 

                
                <div class="wizard-step-pane" id="stepPane6" data-step="6" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">6</span>
                        <h4 class="step-title">៦. ទំនាក់ទំនងបន្ទាន់</h4>
                    </div>

                <div class="row g-2">
                    <div class="col-md-4">
                        <label for="emergency_contact_name" class="form-label-kh">ឈ្មោះអ្នកទំនាក់ទំនង <span class="required-star">*</span></label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-control form-control-custom <?php $__errorArgs = ['emergency_contact_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('emergency_contact_name')); ?>" placeholder="ឈ្មោះអ្នកត្រូវទាក់ទង">
                        <?php $__errorArgs = ['emergency_contact_name'];
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

                    <div class="col-md-4">
                        <label for="emergency_relationship" class="form-label-kh">ត្រូវជា / ទំនាក់ទំនង</label>
                        <select name="emergency_relationship" id="emergency_relationship" class="form-select form-select-custom <?php $__errorArgs = ['emergency_relationship'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">ជ្រើសរើសទំនាក់ទំនង</option>
                            <option value="Parent" <?php echo e(old('emergency_relationship') == 'Parent' ? 'selected' : ''); ?>>ឪពុក / ម្តាយ</option>
                            <option value="Spouse" <?php echo e(old('emergency_relationship') == 'Spouse' ? 'selected' : ''); ?>>ប្តី / ប្រពន្ធ</option>
                            <option value="Sibling" <?php echo e(old('emergency_relationship') == 'Sibling' ? 'selected' : ''); ?>>បងប្អូន</option>
                            <option value="Child" <?php echo e(old('emergency_relationship') == 'Child' ? 'selected' : ''); ?>>កូន</option>
                            <option value="Relative" <?php echo e(old('emergency_relationship') == 'Relative' ? 'selected' : ''); ?>>សាច់ញាតិ</option>
                            <option value="Friend" <?php echo e(old('emergency_relationship') == 'Friend' ? 'selected' : ''); ?>>មិត្តភក្តិ</option>
                            <option value="Other" <?php echo e(old('emergency_relationship') == 'Other' ? 'selected' : ''); ?>>ផ្សេងៗ</option>
                        </select>
                        <?php $__errorArgs = ['emergency_relationship'];
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

                    <div class="col-md-4">
                        <label for="emergency_phone" class="form-label-kh">លេខទូរស័ព្ទបន្ទាន់ <span class="required-star">*</span></label>
                        <input type="text" name="emergency_phone" id="emergency_phone" class="form-control form-control-custom <?php $__errorArgs = ['emergency_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('emergency_phone')); ?>" placeholder="012 345 678">
                        <?php $__errorArgs = ['emergency_phone'];
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

                    <div class="col-md-12">
                        <label for="emergency_address" class="form-label-kh">អាសយដ្ឋានអ្នកទំនាក់ទំនង</label>
                        <input type="text" name="emergency_address" id="emergency_address" class="form-control form-control-custom <?php $__errorArgs = ['emergency_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('emergency_address')); ?>" placeholder="អាសយដ្ឋានអ្នកទំនាក់ទំនងបន្ទាន់...">
                        <?php $__errorArgs = ['emergency_address'];
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
                </div> 

                
                <div class="wizard-step-pane" id="stepPane7" data-step="7" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge" style="background:#6366f1;"><i class="bi bi-person-badge"></i></span>
                        <h4 class="step-title">៧. គណនីប្រើប្រាស់ប្រព័ន្ធ</h4>
                    </div>

                    
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2.5">
                        <label class="account-type-box <?php echo e($selectedAccountType === 'none' ? 'active' : ''); ?>" id="labelAccountNone" onclick="toggleAccountType('none')">
                            <input type="radio" name="account_type" id="account_type_none" value="none" class="account-type-radio" <?php echo e($selectedAccountType === 'none' ? 'checked' : ''); ?>>
                            <i class="bi bi-slash-circle text-muted" style="font-size: 0.78rem;"></i>
                            <span class="fw-bold text-dark" style="font-size: 0.76rem;">មិនទាន់ត្រូវការគណនី</span>
                            <span class="text-muted" style="font-size: 0.68rem;">(មិនបង្កើតគណនី Login)</span>
                        </label>
                        <label class="account-type-box <?php echo e($selectedAccountType === 'new' ? 'active' : ''); ?>" id="labelAccountNew" onclick="toggleAccountType('new')">
                            <input type="radio" name="account_type" id="account_type_new" value="new" class="account-type-radio" <?php echo e($selectedAccountType === 'new' ? 'checked' : ''); ?>>
                            <span class="fw-bold text-dark" style="font-size: 0.76rem;">បង្កើតគណនីថ្មី</span>
                            <span class="text-muted" style="font-size: 0.68rem;">(សម្រាប់បុគ្គលិកថ្មី)</span>
                        </label>
                        <label class="account-type-box <?php echo e($selectedAccountType === 'existing' ? 'active' : ''); ?>" id="labelAccountExisting" onclick="toggleAccountType('existing')">
                            <input type="radio" name="account_type" id="account_type_existing" value="existing" class="account-type-radio" <?php echo e($selectedAccountType === 'existing' ? 'checked' : ''); ?>>
                            <i class="bi bi-people-fill text-muted" style="font-size: 0.78rem;"></i>
                            <span class="fw-bold text-dark" style="font-size: 0.76rem;">ប្រើគណនីដែលមានស្រាប់</span>
                            <span class="text-muted" style="font-size: 0.68rem;">(ពីក្នុងប្រព័ន្ធ)</span>
                        </label>
                    </div>

                    
                    <div id="sectionNoneUser" class="alert alert-light border py-2 px-3 mb-0" style="<?php echo e($selectedAccountType === 'none' ? '' : 'display:none;'); ?>; border-radius: 6px;">
                        <div class="d-flex align-items-center gap-2 text-muted">
                            <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                            <span style="font-size: 0.78rem;">បុគ្គលិកនេះនឹងមិនទាន់មានគណនីចូលប្រើប្រព័ន្ធ (Login) នោះទេ។ លោកអ្នកអាចភ្ជាប់ ឬបង្កើតគណនីជូនគាត់នៅពេលក្រោយបាន។</span>
                        </div>
                    </div>

                    
                    <div id="sectionNewUser" style="<?php echo e($selectedAccountType === 'new' ? '' : 'display:none;'); ?>">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label for="new_name" class="form-label-kh">ឈ្មោះគណនី <span class="required-star">*</span></label>
                                <input type="text" name="new_name" id="new_name" class="form-control form-control-custom <?php $__errorArgs = ['new_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('new_name')); ?>" placeholder="ឈ្មោះពេញបុគ្គលិក" oninput="updateLivePreview()">
                                <?php $__errorArgs = ['new_name'];
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
                            <div class="col-md-3">
                                <label for="new_email" class="form-label-kh">អ៊ីមែលគណនី <span class="required-star">*</span></label>
                                <input type="email" name="new_email" id="new_email" class="form-control form-control-custom <?php $__errorArgs = ['new_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('new_email')); ?>" placeholder="user@school.edu.kh" oninput="updateLivePreview()">
                                <?php $__errorArgs = ['new_email'];
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
                            <div class="col-md-3">
                                <label for="new_password" class="form-label-kh">ពាក្យសម្ងាត់ <span class="required-star">*</span></label>
                                <div class="password-field-wrapper">
                                    <input type="password" name="new_password" id="new_password" class="form-control form-control-custom <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="បញ្ចូលពាក្យសម្ងាត់">
                                    <button class="btn-password-toggle" type="button" onclick="togglePasswordVisibility('new_password', this)" title="បង្ហាញ/លាក់ពាក្យសម្ងាត់" tabindex="-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <?php $__errorArgs = ['new_password'];
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
                            <div class="col-md-3">
                                <label for="role_id" class="form-label-kh">តួនាទីក្នុងប្រព័ន្ធ (Role) <span class="required-star">*</span></label>
                                <select name="role_id" id="role_id" class="form-select form-select-custom <?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">ជ្រើសរើសតួនាទី</option>
                                    <?php if(isset($rolesList)): ?>
                                        <?php $__currentLoopData = $rolesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->id); ?>" <?php echo e(old('role_id') == $role->id ? 'selected' : ''); ?>>
                                                <?php echo e($role->display_name ?? $role->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['role_id'];
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

                    
                    <div id="sectionExistingUser" style="<?php echo e($selectedAccountType === 'existing' ? '' : 'display:none;'); ?>">
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label for="user_id" class="form-label-kh">ជ្រើសរើសគណនីអ្នកប្រើប្រាស់ <span class="required-star">*</span></label>
                                <select name="user_id" id="user_id" class="form-select form-select-custom <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onchange="updateLivePreview()">
                                    <option value="">ជ្រើសរើសគណនីអ្នកប្រើប្រាស់</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" data-name="<?php echo e($user->name); ?>" data-email="<?php echo e($user->email); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['user_id'];
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
                </div>
                </div> 

                
                <div class="d-flex justify-content-between align-items-center gap-2 pt-3 mt-4 border-top">
                    <div class="d-flex align-items-center gap-1.5">
                        <button type="button" class="btn btn-outline-secondary px-3 py-1.5 fw-medium align-items-center gap-1 shadow-xs" id="btnPrevStep" onclick="navigateStep(-1)" style="border-radius: 6px; font-size: 0.82rem; display: none !important;">
                            <i class="bi bi-arrow-left"></i> ថយក្រោយ
                        </button>
                        <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-light text-secondary border border-secondary-subtle px-3 py-1.5 fw-medium align-items-center gap-1 shadow-xs" id="btnCancelStep" style="border-radius: 6px; font-size: 0.82rem; display: inline-flex !important;">
                            <i class="bi bi-x-lg"></i> បោះបង់
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn text-white px-3.5 py-1.5 fw-medium align-items-center gap-1.5 shadow-sm" id="btnSaveAnytime" style="background: #e8a838; border: none; border-radius: 6px; font-size: 0.82rem; display: none !important;">
                            <i class="bi bi-file-earmark-check"></i> រក្សាទុកទិន្នន័យ
                        </button>
                        <button type="button" class="btn text-white px-3.5 py-1.5 fw-medium align-items-center gap-1.5 shadow-sm" id="btnNextStep" onclick="navigateStep(1)" style="background: #2563eb; border: none; border-radius: 6px; font-size: 0.82rem; display: inline-flex !important;">
                            បន្ទាប់ <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

            </div> 
        </div>

        
        <div class="col-xl-4 col-lg-4">
            <div class="preview-card">
                <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 28px; height: 28px; background: #e8a838;">
                        <i class="bi bi-person-fill fs-6"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.86rem;">មើលជាមុន</h6>
                        <small class="text-muted" style="font-size: 0.72rem;">(កាតព័ត៌មានសង្ខេប)</small>
                    </div>
                </div>

                
                <div class="text-center my-2">
                    <div class="preview-avatar-wrap mx-auto" id="previewAvatarContainer">
                        <i class="bi bi-person-fill text-white" style="font-size: 2.6rem;" id="previewAvatarIcon"></i>
                        <img id="previewAvatarImg" src="" alt="Avatar" class="d-none w-100 h-100" style="object-fit: cover;">
                    </div>
                </div>

                
                <div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-person text-secondary"></i> ឈ្មោះ</span>
                        <span class="preview-val fw-bold text-dark" id="previewName">—</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-person-vcard text-secondary"></i> អត្តលេខបុគ្គលិក</span>
                        <span class="preview-val font-monospace" id="previewCode">—</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-briefcase text-secondary"></i> មុខតំណែង</span>
                        <span class="preview-val" id="previewPosition">—</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-building text-secondary"></i> ផ្នែក</span>
                        <span class="preview-val" id="previewDepartment">—</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-shield-check text-secondary"></i> ស្ថានភាព</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" id="previewStatus">មិនទាន់កំណត់</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-envelope text-secondary"></i> អ៊ីមែល</span>
                        <span class="preview-val" id="previewEmail">—</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-telephone text-secondary"></i> ទូរស័ព្ទ</span>
                        <span class="preview-val" id="previewPhone">—</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-calendar-check text-secondary"></i> ថ្ងៃចូលធ្វើការ</span>
                        <span class="preview-val" id="previewJoinDate">—</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-gender-ambiguous text-secondary"></i> ភេទ</span>
                        <span class="preview-val" id="previewGender">—</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-hourglass-split text-secondary"></i> អាយុ</span>
                        <span class="preview-val" id="previewAge">—</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-file-earmark-text text-secondary"></i> កិច្ចសន្យា</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" id="previewContract">—</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function toggleAccountType(type) {
        const newSec = document.getElementById('sectionNewUser');
        const existSec = document.getElementById('sectionExistingUser');
        const noneSec = document.getElementById('sectionNoneUser');
        const boxNew = document.getElementById('labelAccountNew');
        const boxExist = document.getElementById('labelAccountExisting');
        const boxNone = document.getElementById('labelAccountNone');

        if (type === 'new') {
            if (newSec) newSec.style.display = 'block';
            if (existSec) existSec.style.display = 'none';
            if (noneSec) noneSec.style.display = 'none';
            if (boxNew) boxNew.classList.add('active');
            if (boxExist) boxExist.classList.remove('active');
            if (boxNone) boxNone.classList.remove('active');
            const rad = document.getElementById('account_type_new');
            if (rad) rad.checked = true;
            autoFillAccountInfo();
        } else if (type === 'existing') {
            if (newSec) newSec.style.display = 'none';
            if (existSec) existSec.style.display = 'block';
            if (noneSec) noneSec.style.display = 'none';
            if (boxNew) boxNew.classList.remove('active');
            if (boxExist) boxExist.classList.add('active');
            if (boxNone) boxNone.classList.remove('active');
            const rad = document.getElementById('account_type_existing');
            if (rad) rad.checked = true;
        } else {
            // 'none'
            if (newSec) newSec.style.display = 'none';
            if (existSec) existSec.style.display = 'none';
            if (noneSec) noneSec.style.display = 'block';
            if (boxNew) boxNew.classList.remove('active');
            if (boxExist) boxExist.classList.remove('active');
            if (boxNone) boxNone.classList.add('active');
            const rad = document.getElementById('account_type_none');
            if (rad) rad.checked = true;
        }
        updateLivePreview();
    }

    function autoFillAccountInfo() {
        const nameInput = document.getElementById('new_name');
        const emailInput = document.getElementById('new_email');
        
        const lastNameKh = document.getElementById('last_name_khmer')?.value?.trim() || '';
        const firstNameKh = document.getElementById('first_name_khmer')?.value?.trim() || '';
        const fullNameKh = (lastNameKh + ' ' + firstNameKh).trim();

        const lastNameLat = document.getElementById('last_name')?.value?.trim() || '';
        const firstNameLat = document.getElementById('first_name')?.value?.trim() || '';
        const fullNameLat = (lastNameLat + ' ' + firstNameLat).trim();

        if (nameInput && !nameInput.value.trim()) {
            if (fullNameKh && fullNameLat) {
                nameInput.value = fullNameLat + ' (' + fullNameKh + ')';
            } else if (fullNameKh) {
                nameInput.value = fullNameKh;
            } else if (fullNameLat) {
                nameInput.value = fullNameLat;
            }
        }

        const contactEmail = document.getElementById('contact_email')?.value?.trim() || '';
        if (emailInput && !emailInput.value.trim() && contactEmail) {
            emailInput.value = contactEmail;
        }
    }

    function togglePasswordVisibility(fieldId, btn) {
        const input = document.getElementById(fieldId);
        if (!input) return;
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    }

    function previewEmployeePhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const liveImg = document.getElementById('previewAvatarImg');
                const liveIcon = document.getElementById('previewAvatarIcon');
                liveImg.src = e.target.result;
                liveImg.classList.remove('d-none');
                liveIcon.classList.add('d-none');

                const thumbImg = document.getElementById('thumbPhotoPreview');
                const thumbIcon = document.getElementById('thumbPhotoPlaceholder');
                thumbImg.src = e.target.result;
                thumbImg.classList.remove('d-none');
                thumbIcon.classList.add('d-none');

                const btnRemove = document.getElementById('btnRemovePhoto');
                if (btnRemove) btnRemove.classList.remove('d-none');

                const actionLabel = document.getElementById('photoActionLabel');
                if (actionLabel) actionLabel.textContent = 'ប្តូររូប';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeSelectedPhoto() {
        const input = document.getElementById('photo_input');
        if (input) input.value = '';

        const thumbImg = document.getElementById('thumbPhotoPreview');
        const thumbIcon = document.getElementById('thumbPhotoPlaceholder');
        if (thumbImg) {
            thumbImg.src = '';
            thumbImg.classList.add('d-none');
        }
        if (thumbIcon) thumbIcon.classList.remove('d-none');

        const btnRemove = document.getElementById('btnRemovePhoto');
        if (btnRemove) btnRemove.classList.add('d-none');

        const actionLabel = document.getElementById('photoActionLabel');
        if (actionLabel) actionLabel.textContent = 'ជ្រើសរូប';

        const liveImg = document.getElementById('previewAvatarImg');
        const liveIcon = document.getElementById('previewAvatarIcon');
        if (liveImg) {
            liveImg.src = '';
            liveImg.classList.add('d-none');
        }
        if (liveIcon) liveIcon.classList.remove('d-none');
    }

    function updateLivePreview() {
        const isNew = document.getElementById('account_type_new')?.checked;
        const isExisting = document.getElementById('account_type_existing')?.checked;
        let name = '';
        let email = '';

        const lastNameKh = document.getElementById('last_name_khmer')?.value?.trim() || '';
        const firstNameKh = document.getElementById('first_name_khmer')?.value?.trim() || '';
        const fullNameKh = (lastNameKh + ' ' + firstNameKh).trim();

        const lastNameLat = document.getElementById('last_name')?.value?.trim() || '';
        const firstNameLat = document.getElementById('first_name')?.value?.trim() || '';
        const fullNameLat = (lastNameLat + ' ' + firstNameLat).trim();

        if (fullNameKh) {
            name = fullNameKh;
            if (fullNameLat) name += ` (${fullNameLat})`;
        } else if (fullNameLat) {
            name = fullNameLat;
        } else {
            name = '—';
        }

        if (isNew) {
            email = document.getElementById('new_email')?.value?.trim() || document.getElementById('contact_email')?.value?.trim() || '—';
        } else if (isExisting) {
            const userSelect = document.getElementById('user_id');
            const selectedOpt = userSelect?.options[userSelect.selectedIndex];
            email = selectedOpt?.dataset?.email || '—';
        } else {
            email = document.getElementById('contact_email')?.value?.trim() || '—';
        }

        document.getElementById('previewName').textContent = name;
        document.getElementById('previewEmail').textContent = email;

        // Code
        const code = document.getElementById('employee_code')?.value?.trim();
        document.getElementById('previewCode').textContent = code ? code : '—';

        // Position
        const posSelect = document.getElementById('employee_position_id');
        const posText = posSelect?.selectedIndex > 0 ? posSelect.options[posSelect.selectedIndex].text : '—';
        document.getElementById('previewPosition').textContent = posText;

        // Department
        const deptSelect = document.getElementById('employee_department_id');
        const deptText = deptSelect?.selectedIndex > 0 ? deptSelect.options[deptSelect.selectedIndex].text : '—';
        document.getElementById('previewDepartment').textContent = deptText;

        // Status
        const statSelect = document.getElementById('employee_status_id');
        const statBadge = document.getElementById('previewStatus');
        if (statSelect && statSelect.selectedIndex > 0) {
            statBadge.textContent = statSelect.options[statSelect.selectedIndex].text;
            statBadge.className = 'badge bg-success text-white px-2 py-1';
        } else {
            statBadge.textContent = 'មិនទាន់កំណត់';
            statBadge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2 py-1';
        }

        // Phone
        const phone = document.getElementById('phone')?.value?.trim();
        document.getElementById('previewPhone').textContent = phone ? phone : '—';

        // Gender
        const genderSelect = document.getElementById('gender');
        let genderText = '—';
        if (genderSelect && genderSelect.value) {
            genderText = genderSelect.options[genderSelect.selectedIndex].text;
        }
        document.getElementById('previewGender').textContent = genderText;

        // Joining date
        const joinDate = document.getElementById('date_of_joining')?.value;
        if (joinDate) {
            const parts = joinDate.split('-');
            if (parts.length === 3) {
                document.getElementById('previewJoinDate').textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
            } else {
                document.getElementById('previewJoinDate').textContent = joinDate;
            }
        } else {
            document.getElementById('previewJoinDate').textContent = '—';
        }

        // Age from date of birth
        const dobVal = document.getElementById('date_of_birth')?.value;
        if (dobVal) {
            const birthDate = new Date(dobVal);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            if (age >= 0 && !isNaN(age)) {
                document.getElementById('previewAge').textContent = age + ' ឆ្នាំ';
            } else {
                document.getElementById('previewAge').textContent = '—';
            }
        } else {
            document.getElementById('previewAge').textContent = '—';
        }

        // Contract preview
        const createContractCheckbox = document.getElementById('create_contract');
        const previewContractEl = document.getElementById('previewContract');
        if (previewContractEl) {
            if (createContractCheckbox && createContractCheckbox.checked) {
                const num = document.getElementById('contract_number')?.value || 'CTR-Auto';
                previewContractEl.className = 'badge bg-success-subtle text-success border border-success-subtle px-2 py-1';
                previewContractEl.innerHTML = `<i class="bi bi-file-earmark-check me-1"></i>${num} (សកម្ម)`;
            } else {
                previewContractEl.className = 'badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1';
                previewContractEl.textContent = 'មិនបង្កើត';
            }
        }
    }

    function toggleContractFields(checkbox) {
        const wrapper = document.getElementById('contract_fields_wrapper');
        if (wrapper) {
            wrapper.style.display = checkbox.checked ? 'block' : 'none';
        }
        updateLivePreview();
    }

    function onEmployeeContractTypeChange(selectEl) {
        syncContractDatesFromType();
        updateLivePreview();
    }

    function syncContractDatesFromType() {
        const contractTypeEl = document.getElementById('contract_type');
        const startInput = document.getElementById('contract_start_date');
        const endInput = document.getElementById('contract_end_date');
        const hintEl = document.getElementById('end_date_hint');
        if (!contractTypeEl || !startInput || !endInput) return;

        const val = contractTypeEl.value;
        const baseDate = startInput.value ? new Date(startInput.value) : new Date();

        if (val === 'Probation') {
            const d = new Date(baseDate);
            d.setMonth(d.getMonth() + 3);
            endInput.value = d.toISOString().split('T')[0];
            if (hintEl) hintEl.textContent = 'សាកល្បង 3 ខែ (គណនាស្វ័យប្រវត្តិ)';
        } else if (val === 'Fixed Term') {
            const d = new Date(baseDate);
            d.setFullYear(d.getFullYear() + 1);
            endInput.value = d.toISOString().split('T')[0];
            if (hintEl) hintEl.textContent = 'កំណត់ថិរវេលា 1 ឆ្នាំ (គណនាស្វ័យប្រវត្តិ)';
        } else if (val === 'Internship') {
            const d = new Date(baseDate);
            d.setMonth(d.getMonth() + 3);
            endInput.value = d.toISOString().split('T')[0];
            if (hintEl) hintEl.textContent = 'កម្មសិក្សា 3 ខែ (គណនាស្វ័យប្រវត្តិ)';
        } else if (val === 'Permanent') {
            endInput.value = '';
            if (hintEl) hintEl.textContent = 'អចិន្ត្រៃយ៍ — មិនកំណត់កាលបរិច្ឆេទបញ្ចប់';
        }
    }

    function onContractDatesChange() {
        syncContractDatesFromType();
        updateLivePreview();
    }

    function addMonthsToContractEndDate(months) {
        const startInput = document.getElementById('contract_start_date');
        const endInput = document.getElementById('contract_end_date');
        const startDateVal = startInput?.value || new Date().toISOString().split('T')[0];
        if (endInput) {
            const d = new Date(startDateVal);
            d.setMonth(d.getMonth() + months);
            endInput.value = d.toISOString().split('T')[0];
            updateLivePreview();
        }
    }

    /* WIZARD LOGIC */
    let currentWizardStep = 1;
    const totalWizardSteps = 7;

    function showStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > totalWizardSteps) return;
        currentWizardStep = stepNumber;

        if (stepNumber === 7) {
            autoFillAccountInfo();
        }

        // Toggle panes
        for (let i = 1; i <= totalWizardSteps; i++) {
            const pane = document.getElementById('stepPane' + i);
            if (pane) {
                if (i === currentWizardStep) {
                    pane.classList.add('active');
                    pane.style.display = 'block';
                } else {
                    pane.classList.remove('active');
                    pane.style.display = 'none';
                }
            }
        }

        // Toggle tabs
        document.querySelectorAll('.wizard-step-tab').forEach(tab => {
            const step = parseInt(tab.getAttribute('data-step'));
            tab.classList.remove('active', 'completed');
            if (step === currentWizardStep) {
                tab.classList.add('active');
            } else if (step < currentWizardStep) {
                tab.classList.add('completed');
            }
        });

        // Update progress bar
        const pct = Math.round((currentWizardStep / totalWizardSteps) * 100);
        const pBar = document.getElementById('wizardProgressBar');
        if (pBar) pBar.style.width = pct + '%';

        // Update button states
        const btnPrev = document.getElementById('btnPrevStep');
        const btnNext = document.getElementById('btnNextStep');
        const btnSave = document.getElementById('btnSaveAnytime');
        const btnCancel = document.getElementById('btnCancelStep');

        function setBtnDisplay(el, isVisible) {
            if (!el) return;
            if (isVisible) {
                el.style.setProperty('display', 'inline-flex', 'important');
            } else {
                el.style.setProperty('display', 'none', 'important');
            }
        }

        setBtnDisplay(btnPrev, currentWizardStep > 1);
        setBtnDisplay(btnCancel, true);
        setBtnDisplay(btnNext, currentWizardStep < totalWizardSteps);
        setBtnDisplay(btnSave, currentWizardStep === totalWizardSteps);

        // Smooth scroll to top of form card
        const formCard = document.querySelector('.unified-form-card');
        if (formCard && window.scrollY > formCard.offsetTop) {
            formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function validateStep(stepNumber) {
        let isValid = true;
        let firstInvalidEl = null;

        function markInvalid(inputEl, message) {
            isValid = false;
            inputEl.classList.add('is-invalid');
            
            let parent = inputEl.closest('.password-field-wrapper')?.parentElement || inputEl.closest('.input-group') || inputEl.parentElement;
            let feedback = parent.querySelector('.step-error-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback d-block step-error-feedback';
                parent.appendChild(feedback);
            }
            feedback.textContent = message;

            if (!firstInvalidEl) {
                firstInvalidEl = inputEl;
            }

            const clearHandler = function() {
                inputEl.classList.remove('is-invalid');
                if (feedback) feedback.remove();
                inputEl.removeEventListener('input', clearHandler);
                inputEl.removeEventListener('change', clearHandler);
            };
            inputEl.addEventListener('input', clearHandler);
            inputEl.addEventListener('change', clearHandler);
        }

        if (stepNumber === 1) {
            const lastNameKh = document.getElementById('last_name_khmer');
            if (lastNameKh && !lastNameKh.value.trim()) {
                markInvalid(lastNameKh, 'សូមបញ្ចូលគោត្តនាម (ខ្មែរ)');
            }

            const firstNameKh = document.getElementById('first_name_khmer');
            if (firstNameKh && !firstNameKh.value.trim()) {
                markInvalid(firstNameKh, 'សូមបញ្ចូលនាម (ខ្មែរ)');
            }

            const genderEl = document.getElementById('gender');
            if (genderEl && !genderEl.value) {
                markInvalid(genderEl, 'សូមជ្រើសរើសភេទ');
            }

            const dobEl = document.getElementById('date_of_birth');
            if (dobEl && !dobEl.value) {
                markInvalid(dobEl, 'សូមជ្រើសរើសថ្ងៃខែឆ្នាំកំណើត');
            }

            const phoneEl = document.getElementById('phone');
            if (phoneEl && !phoneEl.value.trim()) {
                markInvalid(phoneEl, 'សូមបញ្ចូលលេខទូរស័ព្ទ');
            }
        } else if (stepNumber === 3) {
            const posEl = document.getElementById('employee_position_id');
            if (posEl && !posEl.value) {
                markInvalid(posEl, 'សូមជ្រើសរើសមុខតំណែង');
            }
            const statEl = document.getElementById('employee_status_id');
            if (statEl && !statEl.value) {
                markInvalid(statEl, 'សូមជ្រើសរើសស្ថានភាពការងារ');
            }
            const joinEl = document.getElementById('date_of_joining');
            if (joinEl && !joinEl.value) {
                markInvalid(joinEl, 'សូមជ្រើសរើសថ្ងៃចូលបម្រើការងារ');
            }

            if (document.getElementById('create_contract')?.checked) {
                const contractNumEl = document.getElementById('contract_number');
                if (contractNumEl && !contractNumEl.value.trim()) {
                    markInvalid(contractNumEl, 'សូមបញ្ចូលលេខកិច្ចសន្យា');
                }
            }
        } else if (stepNumber === 7) {
            const isNew = document.getElementById('account_type_new')?.checked;
            const isExisting = document.getElementById('account_type_existing')?.checked;
            if (isNew) {
                const nameEl = document.getElementById('new_name');
                if (nameEl && !nameEl.value.trim()) {
                    markInvalid(nameEl, 'សូមបញ្ចូលឈ្មោះគណនី');
                }
                const emailEl = document.getElementById('new_email');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailEl && (!emailEl.value.trim() || !emailRegex.test(emailEl.value.trim()))) {
                    markInvalid(emailEl, 'សូមបញ្ចូលអ៊ីមែលឱ្យបានត្រឹមត្រូវ');
                }
                const passEl = document.getElementById('new_password');
                if (passEl && (!passEl.value || passEl.value.length < 6)) {
                    markInvalid(passEl, 'សូមបញ្ចូលពាក្យសម្ងាត់យ៉ាងតិច ៦ ខ្ទង់');
                }
                const roleEl = document.getElementById('role_id');
                if (roleEl && !roleEl.value) {
                    markInvalid(roleEl, 'សូមជ្រើសរើសតួនាទី (Role)');
                }
            } else if (isExisting) {
                const userEl = document.getElementById('user_id');
                if (userEl && !userEl.value) {
                    markInvalid(userEl, 'សូមជ្រើសរើសគណនីអ្នកប្រើប្រាស់');
                }
            }
        }

        if (!isValid && firstInvalidEl) {
            firstInvalidEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidEl.focus();
        }

        return isValid;
    }

    function navigateStep(direction) {
        showStep(currentWizardStep + direction);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Setup listeners for contract auto-sync
        const basicSalaryEl = document.getElementById('basic_salary');
        if (basicSalaryEl) {
            basicSalaryEl.addEventListener('input', function() {
                const contractSalary = document.getElementById('contract_salary');
                if (contractSalary && (!contractSalary.value || contractSalary.dataset.autofilled === '1')) {
                    contractSalary.value = this.value;
                    contractSalary.dataset.autofilled = '1';
                }
                updateLivePreview();
            });
        }

        const dateOfJoiningEl = document.getElementById('date_of_joining');
        if (dateOfJoiningEl) {
            dateOfJoiningEl.addEventListener('change', function() {
                const contractStartDate = document.getElementById('contract_start_date');
                if (contractStartDate) {
                    contractStartDate.value = this.value;
                }
                syncContractDatesFromType();
                updateLivePreview();
            });
        }

        syncContractDatesFromType();
        updateLivePreview();

        // If validation errors exist, automatically jump to the step containing the first error
        const firstInvalid = document.querySelector('.wizard-step-pane .is-invalid');
        if (firstInvalid) {
            const pane = firstInvalid.closest('.wizard-step-pane');
            if (pane && pane.getAttribute('data-step')) {
                showStep(parseInt(pane.getAttribute('data-step')));
                firstInvalid.focus();
                return;
            }
        }
        showStep(1);
    });
</script>
<?php echo $__env->make('students.partials.cambodia_districts_js', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/employees/employee/create.blade.php ENDPATH**/ ?>