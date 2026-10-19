<?php $__env->startSection('title', 'បង្កើតកិច្ចសន្យាការងារបុគ្គលិក — School Management System'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('students.index')); ?>" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ប្រាក់បៀវត្ស និងបុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('employee-contracts.index')); ?>" class="text-decoration-none text-muted">កិច្ចសន្យាការងារ</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បង្កើតកិច្ចសន្យាការងារ</li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header d-flex align-items-start justify-content-between mb-2.5">
    <div>
        <h1 class="page-title d-flex align-items-center gap-2" style="font-size:1.25rem;">
            <span class="d-flex align-items-center justify-content-center rounded-2" style="width:34px;height:34px;background:#fef3c7;color:#c98b1e;font-size:1.1rem;">
                <i class="bi bi-file-earmark-text-fill"></i>
            </span>
            <span>បង្កើតកិច្ចសន្យាការងារបុគ្គលិក</span>
        </h1>
        <p class="page-subtitle mb-0" style="font-size:0.82rem;">បង្កើតកិច្ចសន្យាការងារស្របតាមច្បាប់ស្តីពីការងារ នៃព្រះរាជាណាចក្រកម្ពុជា សម្រាប់សាលារៀន ព្រះធីរបញ្ញា</p>
    </div>
    <div>
        <a href="<?php echo e(route('employee-contracts.index')); ?>" class="btn btn-outline-secondary d-flex align-items-center gap-1.5 px-3 py-1.5 shadow-xs" style="font-size:0.82rem; border-radius:6px;">
            <i class="bi bi-arrow-left"></i>
            <span>ត្រឡប់ក្រោយ</span>
        </a>
    </div>
</div>


<?php if($errors->any()): ?>
    <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-start gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-octagon-fill fs-5 mt-0.5"></i>
        <div>
            <strong>សូមពិនិត្យកំហុសខាងក្រោមមុនពេលរក្សាទុក៖</strong>
            <ul class="mb-0 mt-1 ps-3 small">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if(session('success')): ?>
    <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div><?php echo e(session('success')); ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('employee-contracts.store')); ?>" enctype="multipart/form-data" id="contractForm" novalidate>
<?php echo csrf_field(); ?>

<div class="row g-2.5">

    
    <div class="col-12 col-xl-8 col-lg-7">

        
        <div class="form-card border-0 shadow-sm mb-3">

            
            <div class="form-card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size:0.75rem; background:#e8a838 !important;">
                        <i class="bi bi-people-fill"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">១. ព័ត៌មានភាគីនៃកិច្ចសន្យា</h5>
                        <span class="text-muted" style="font-size: 0.75rem;">និយោជក (ភាគី «ក») និង និយោជិត (ភាគី «ខ»)</span>
                    </div>
                </div>
                <span class="badge px-2 py-1" style="background:#fef3c7; color:#78350f; border: 1px solid #fde68a; font-weight:700; font-size:0.72rem;">ភាគីកិច្ចសន្យា</span>
            </div>

            <div class="form-card-body p-3">
                
                <div class="p-2 mb-2 rounded-2 d-flex align-items-center gap-2.5" style="background:#fffbeb; border: 1px solid #fef3c7;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width:32px;height:32px;background:#fde68a;color:#92400e;">
                        <i class="bi bi-bank2 fs-6"></i>
                    </div>
                    <div class="small">
                        <div class="fw-bold text-dark" style="font-size:0.83rem;">ភាគី «ក» (និយោជក)៖ <strong>សាលារៀន ព្រះធីរបញ្ញា</strong></div>
                        <div class="text-muted" style="font-size:0.75rem;">តំណាងដោយ៖ <strong>អ្នកស្រី សូ វាសនា (នាយិកាសាលា)</strong></div>
                    </div>
                </div>

                
                <?php
                    $employeesWithoutContract = $employees->filter(fn($e) => !$e->activeContract);
                    $employeesWithContract = $employees->filter(fn($e) => (bool)$e->activeContract);
                    $isPreselectedWithContract = $selectedEmployee && $selectedEmployee->activeContract;
                    $defaultFilter = $isPreselectedWithContract ? 'all' : 'no_contract';
                ?>
                <div class="row g-2">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1.5">
                            <label class="form-label-custom fw-semibold text-dark mb-0">
                                ជ្រើសរើសនិយោជិត (ភាគី «ខ») <span class="text-danger">*</span>
                            </label>
                            
                            <div class="btn-group btn-group-sm" role="group" id="empFilterGroup">
                                <button type="button" class="btn btn-outline-success <?php echo e($defaultFilter === 'no_contract' ? 'active' : ''); ?>" 
                                        data-filter="no_contract" onclick="filterEmployeeList('no_contract')">
                                    <i class="bi bi-person-check me-1"></i>មិនទាន់មានកិច្ចសន្យា (<?php echo e($employeesWithoutContract->count()); ?>)
                                </button>
                                <button type="button" class="btn btn-outline-warning text-dark <?php echo e($defaultFilter === 'has_contract' ? 'active' : ''); ?>" 
                                        data-filter="has_contract" onclick="filterEmployeeList('has_contract')">
                                    <i class="bi bi-file-earmark-check me-1"></i>មានកិច្ចសន្យារួច (<?php echo e($employeesWithContract->count()); ?>)
                                </button>
                                <button type="button" class="btn btn-outline-secondary <?php echo e($defaultFilter === 'all' ? 'active' : ''); ?>" 
                                        data-filter="all" onclick="filterEmployeeList('all')">
                                    <i class="bi bi-people me-1"></i>ទាំងអស់ (<?php echo e($employees->count()); ?>)
                                </button>
                            </div>
                        </div>

                        
                        <div class="search-input-wrap mb-2">
                            <i class="bi bi-search text-muted"></i>
                            <input type="text" id="employee_search_input" class="form-control form-control-sm" 
                                   placeholder="វាយបញ្ចូលឈ្មោះ ឬកូដបុគ្គលិក ដើម្បីស្វែងរក..." 
                                   oninput="searchEmployeeList(this.value)">
                        </div>

                        <select name="employee_id" id="employee_select" 
                                class="form-select form-control-custom <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                required onchange="updateEmployeeMetadata(this)">
                            <option value="">— សូមជ្រើសរើសបុគ្គលិក —</option>

                            
                            <optgroup label="✨ បុគ្គលិកមិនទាន់មានកិច្ចសន្យា (អាចចុះកិច្ចសន្យាថ្មីបាន)" id="optgroup_no_contract">
                                <?php $__currentLoopData = $employeesWithoutContract; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $empName = $emp->user?->name ?? 'N/A';
                                        $empCode = $emp->employee_code;
                                        $empPos = $emp->employee_position?->name ?? 'Staff';
                                        $empDept = $emp->employee_department?->name ?? 'General';
                                        $empSalary = $emp->basic_salary ?? 0;
                                        $empGender = $emp->gender ?? 'N/A';
                                        $empPhone = $emp->user?->phone ?? '—';
                                    ?>
                                    <option value="<?php echo e($emp->id); ?>" 
                                            data-has-contract="0"
                                            data-name="<?php echo e($empName); ?>"
                                            data-code="<?php echo e($empCode); ?>"
                                            data-position="<?php echo e($empPos); ?>"
                                            data-department="<?php echo e($empDept); ?>"
                                            data-salary="<?php echo e($empSalary); ?>"
                                            data-gender="<?php echo e($empGender); ?>"
                                            data-phone="<?php echo e($empPhone); ?>"
                                            <?php if(old('employee_id', $selectedEmployee?->id) == $emp->id): echo 'selected'; endif; ?>>
                                        ✅ [មិនទាន់មាន] <?php echo e($empName); ?> (<?php echo e($empCode); ?>) — [<?php echo e($empPos); ?> - <?php echo e($empDept); ?>]
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>

                            
                            <optgroup label="⚠️ បុគ្គលិកមានកិច្ចសន្យារួចហើយ (មានកិច្ចសន្យាសកម្ម)" id="optgroup_has_contract">
                                <?php $__currentLoopData = $employeesWithContract; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $empName = $emp->user?->name ?? 'N/A';
                                        $empCode = $emp->employee_code;
                                        $empPos = $emp->employee_position?->name ?? 'Staff';
                                        $empDept = $emp->employee_department?->name ?? 'General';
                                        $empSalary = $emp->basic_salary ?? 0;
                                        $empGender = $emp->gender ?? 'N/A';
                                        $empPhone = $emp->user?->phone ?? '—';
                                        $contractNum = $emp->activeContract?->contract_number ?? 'មានសកម្ម';
                                    ?>
                                    <option value="<?php echo e($emp->id); ?>" 
                                            data-has-contract="1"
                                            data-contract-number="<?php echo e($contractNum); ?>"
                                            data-name="<?php echo e($empName); ?>"
                                            data-code="<?php echo e($empCode); ?>"
                                            data-position="<?php echo e($empPos); ?>"
                                            data-department="<?php echo e($empDept); ?>"
                                            data-salary="<?php echo e($empSalary); ?>"
                                            data-gender="<?php echo e($empGender); ?>"
                                            data-phone="<?php echo e($empPhone); ?>"
                                            <?php if(old('employee_id', $selectedEmployee?->id) == $emp->id): echo 'selected'; endif; ?>>
                                        🔒 [មានរួច: #<?php echo e($contractNum); ?>] <?php echo e($empName); ?> (<?php echo e($empCode); ?>) — [<?php echo e($empPos); ?> - <?php echo e($empDept); ?>]
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        </select>
                        <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        
                        <div id="contract_warning_box" class="alert alert-warning border-warning d-flex align-items-center gap-2 mt-2 mb-0 py-2 px-3 small" style="display:none !important;">
                            <i class="bi bi-exclamation-triangle-fill fs-5 text-warning flex-shrink-0"></i>
                            <div>
                                <strong>ចំណាំ៖</strong> បុគ្គលិកនេះមានកិច្ចសន្យាសកម្មរួចហើយ (<span id="warning_contract_num" class="fw-bold font-monospace"></span>)។
                                <div class="text-danger fw-semibold">ប្រព័ន្ធនឹងមិនអនុញ្ញាតឱ្យបង្កើតកិច្ចសន្យាសកម្មថ្មីជាន់គ្នានោះឡើយ (ត្រូវបញ្ចប់កិច្ចសន្យាចាស់សិន ឬជ្រើសរើសស្ថានភាពពង្រាង)។</div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-12" id="employee_quick_card" style="display: none;">
                        <div class="p-2.5 bg-light rounded border d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold" 
                                     style="width: 38px; height: 38px; background: #e8a838;">
                                    <span id="meta_avatar_txt" style="font-size:0.8rem;">EMP</span>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" id="meta_name" style="font-size:0.88rem;">—</div>
                                    <div class="text-muted" style="font-size:0.75rem;">
                                        កូដ៖ <span class="fw-semibold text-dark" id="meta_code">—</span> | 
                                        មុខតំណែង៖ <span class="badge bg-white border text-dark" id="meta_position">—</span> | 
                                        ផ្នែក៖ <span class="text-secondary" id="meta_department">—</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-muted" style="font-size:0.72rem;">ប្រាក់ខែគោលបច្ចុប្បន្ន</div>
                                <div class="fw-bold text-success" id="meta_salary" style="font-size:0.95rem;">$0.00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="form-card-header bg-light border-top border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size:0.75rem; background:#e8a838 !important;">
                        <i class="bi bi-calendar2-week"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">២. លក្ខខណ្ឌ និងថិរវេលាកិច្ចសន្យា</h5>
                        <span class="text-muted" style="font-size: 0.75rem;">រយៈពេលកិច្ចសន្យា (ប្រការ១), ការសាកល្បង (ប្រការ២) និង ម៉ោងការងារ (ប្រការ៧)</span>
                    </div>
                </div>
                <span class="badge px-2 py-1" style="background:#fef3c7; color:#78350f; border: 1px solid #fde68a; font-weight:700; font-size:0.72rem;">ថិរវេលា</span>
            </div>

            <div class="form-card-body p-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            លេខកិច្ចសន្យា <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted px-2.5" title="ចាក់សោស្វ័យប្រវត្តិ">
                                <i class="bi bi-lock-fill text-warning"></i>
                            </span>
                            <input type="text" name="contract_number" id="contract_number"
                                   class="form-control form-control-custom bg-light border-start-0 font-monospace fw-bold <?php $__errorArgs = ['contract_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('contract_number', $nextContractNumber ?? 'CTR-001')); ?>" 
                                   readonly tabindex="-1" title="លេខកិច្ចសន្យាត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ និងចាក់សោ"
                                   placeholder="CTR-001" oninput="updateLivePreview()">
                        </div>
                        <div class="form-text text-muted small mt-1" style="font-size: 0.72rem;">
                            <i class="bi bi-lock-fill text-warning me-1"></i>បង្កើតស្វ័យប្រវត្តិ — ចាក់សោ
                        </div>
                        <?php $__errorArgs = ['contract_number'];
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
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ប្រភេទកិច្ចសន្យា <span class="text-danger">*</span>
                        </label>
                        <select name="contract_type" id="contract_type"
                                class="form-select form-control-custom <?php $__errorArgs = ['contract_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                required onchange="onContractTypeChange(this)">
                            <option value="fixed_term" <?php if(old('contract_type', 'fixed_term') === 'fixed_term'): echo 'selected'; endif; ?>>
                                កិច្ចសន្យាមានកំណត់ថិរវេលា
                            </option>
                            <option value="probation" <?php if(old('contract_type') === 'probation'): echo 'selected'; endif; ?>>
                                ការសាកល្បងការងារ
                            </option>
                            <option value="permanent" <?php if(old('contract_type') === 'permanent'): echo 'selected'; endif; ?>>
                                កិច្ចសន្យាមិនកំណត់ថិរវេលា
                            </option>
                            <option value="part_time" <?php if(old('contract_type') === 'part_time'): echo 'selected'; endif; ?>>
                                ការងារក្រៅម៉ោង
                            </option>
                            <option value="intern" <?php if(old('contract_type') === 'intern'): echo 'selected'; endif; ?>>
                                កម្មសិក្សាការងារ
                            </option>
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

                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label-custom fw-semibold text-dark mb-0">
                                ថ្ងៃចាប់ផ្តើម <span class="text-danger">*</span>
                            </label>
                            <button type="button" class="btn btn-link p-0 text-decoration-none small" style="font-size:0.75rem; color:#b45309;" onclick="setStartDateToday()">
                                ដាក់ថ្ងៃនេះ
                            </button>
                        </div>
                        <input type="date" name="start_date" id="start_date"
                               class="form-control form-control-custom <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('start_date', date('Y-m-d'))); ?>" required onchange="onStartDateChange()">
                        <?php $__errorArgs = ['start_date'];
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
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label-custom fw-semibold text-dark mb-0">
                                ថ្ងៃបញ្ចប់ <span class="text-muted small">(ជម្រើស)</span>
                            </label>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-light border px-2 py-0" style="font-size:0.72rem;" onclick="addMonthsToEndDate(3)">
                                    +3 ខែ (សាកល្បង)
                                </button>
                                <button type="button" class="btn btn-light border px-2 py-0" style="font-size:0.72rem;" onclick="addMonthsToEndDate(12)">
                                    +1 ឆ្នាំ (មានកំណត់)
                                </button>
                            </div>
                        </div>
                        <input type="date" name="end_date" id="end_date"
                               class="form-control form-control-custom <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('end_date', date('Y-m-d', strtotime('+1 year')) )); ?>" onchange="updateLivePreview()">
                        <div class="form-text text-muted" style="font-size:0.75rem;">ទុកទំនេរ ប្រសិនបើជាកិច្ចសន្យាមិនកំណត់ថិរវេលា</div>
                        <?php $__errorArgs = ['end_date'];
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
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ម៉ោងធ្វើការក្នុងមួយសប្តាហ៍ <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="work_hours_per_week" id="work_hours_per_week"
                                   class="form-control form-control-custom <?php $__errorArgs = ['work_hours_per_week'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('work_hours_per_week', 40)); ?>" step="0.5" min="1" max="168" required oninput="updateLivePreview()">
                            <span class="input-group-text bg-light text-muted">ម៉ោង/សប្តាហ៍</span>
                        </div>
                        <div class="form-text text-muted" style="font-size:0.75rem;">ស្តង់ដារកម្ពុជា (ប្រការ៧)៖ ៨ម៉ោង/ថ្ងៃ និង ៥ថ្ងៃ/សប្តាហ៍ = ៤០ ម៉ោង</div>
                        <?php $__errorArgs = ['work_hours_per_week'];
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
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ស្ថានភាពកិច្ចសន្យា <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="contract_status"
                                class="form-select form-control-custom <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required onchange="updateLivePreview()">
                            <option value="active" <?php if(old('status', 'active') === 'active'): echo 'selected'; endif; ?>>ដំណើរការ (សកម្ម)</option>
                            <option value="draft" <?php if(old('status') === 'draft'): echo 'selected'; endif; ?>>ពង្រាង</option>
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
                </div>
            </div>

            
            <div class="form-card-header bg-light border-top border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size:0.75rem; background:#e8a838 !important;">
                        <i class="bi bi-cash-stack"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">៣. ប្រាក់បៀវត្ស និងរចនាសម្ព័ន្ធប្រាក់ខែ</h5>
                        <span class="text-muted" style="font-size: 0.75rem;">ប្រាក់ខែគោល (ប្រការ៤) និង ប្រាក់ខែទី១៣/ប្រាក់បំណាច់ឆ្នាំ (ប្រការ៦)</span>
                    </div>
                </div>
                <span class="badge px-2 py-1" style="background:#fef3c7; color:#78350f; border: 1px solid #fde68a; font-weight:700; font-size:0.72rem;">ប្រាក់បៀវត្ស</span>
            </div>

            <div class="form-card-body p-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ប្រាក់បៀវត្សឯកភាព / ប្រាក់ខែគោល <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted fw-bold">$</span>
                            <input type="number" name="salary" id="salary_input"
                                   class="form-control form-control-custom <?php $__errorArgs = ['salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('salary', '250.00')); ?>" step="0.01" min="0" 
                                   placeholder="250.00" required oninput="updateLivePreview()">
                            <span class="input-group-text bg-light text-muted">ដុល្លារ / ខែ</span>
                        </div>
                        <div class="form-text text-muted" style="font-size:0.75rem;">គិតជាដុល្លារអាមេរិក ស្របតាមប្រការ៤នៃកិច្ចសន្យា</div>
                        <?php $__errorArgs = ['salary'];
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
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            រចនាសម្ព័ន្ធប្រាក់បៀវត្ស
                        </label>
                        <select name="salary_structure_id" id="salary_structure_id"
                                class="form-select form-control-custom <?php $__errorArgs = ['salary_structure_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">— គ្មាន (ប្រើប្រាក់បៀវត្សឯកភាពផ្ទាល់) —</option>
                            <?php $__currentLoopData = $structures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s->id); ?>" <?php if(old('salary_structure_id') == $s->id): echo 'selected'; endif; ?>>
                                    <?php echo e($s->name); ?> ($<?php echo e(number_format($s->base_salary, 2)); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="form-text text-muted" style="font-size:0.75rem;">ជ្រើសរើសដើម្បីកំណត់ប្រាក់ឧបត្ថម្ភ និងការកាត់ទុកផ្សេងៗតាមប្រព័ន្ធ</div>
                        <?php $__errorArgs = ['salary_structure_id'];
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
                        <div class="p-2.5 rounded-2" style="background:#f8fafc; border:1px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-2 mb-1.5">
                                <i class="bi bi-award-fill text-warning"></i>
                                <span class="fw-bold text-dark" style="font-size:0.84rem;">អត្ថប្រយោជន៍ និងលក្ខខណ្ឌការងារស្របតាមកិច្ចសន្យាផ្លូវការ៖</span>
                            </div>
                            <div class="row g-1.5 text-muted" style="font-size:0.78rem;">
                                <div class="col-md-6">
                                    <i class="bi bi-check2-circle text-success me-1"></i><strong>ប្រាក់ខែទី១៣៖</strong> យោងតាមប្រការ ៦.១
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-check2-circle text-success me-1"></i><strong>ការឈប់សម្រាកប្រចាំឆ្នាំ៖</strong> យោងតាមប្រការ ៨.១
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-check2-circle text-success me-1"></i><strong>ការលំហែមាតុភាព៖</strong> ៣ខែ យោងតាមប្រការ ៩.១
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-check2-circle text-success me-1"></i><strong>ការជូនដំណឹងមុនលាឈប់៖</strong> ១ខែ យោងតាមប្រការ ១០.១
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="form-card-header bg-light border-top border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size:0.75rem; background:#e8a838 !important;">
                        <i class="bi bi-paperclip"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">៤. ឯកសារកិច្ចសន្យា និងកំណត់សម្គាល់</h5>
                        <span class="text-muted" style="font-size: 0.75rem;">ភ្ជាប់ឯកសារកិច្ចសន្យាដែលបានចុះហត្ថលេខារួច និងកំណត់សម្គាល់បន្ថែម</span>
                    </div>
                </div>
                <span class="badge px-2 py-1" style="background:#fef3c7; color:#78350f; border: 1px solid #fde68a; font-weight:700; font-size:0.72rem;">ឯកសារ</span>
            </div>

            <div class="form-card-body p-3">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ភ្ជាប់ឯកសារកិច្ចសន្យាស្កេន
                        </label>
                        <input type="file" name="document" id="document"
                               class="form-control form-control-custom <?php $__errorArgs = ['document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               accept=".pdf,.doc,.docx,image/*">
                        <div class="form-text text-muted" style="font-size:0.75rem;">គាំទ្រប្រភេទ PDF, Word ឬរូបភាពស្កេន (ទំហំអតិបរមា 5MB)</div>
                        <?php $__errorArgs = ['document'];
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
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            កំណត់សម្គាល់ ឬលក្ខខណ្ឌពិសេស
                        </label>
                        <textarea name="notes" id="notes" rows="2" 
                                  class="form-control form-control-custom <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  placeholder="បញ្ជាក់អំពីការចាត់តាំងមុខវិជ្ជាបង្រៀន លក្ខខណ្ឌបន្ថែម ឬកាតព្វកិច្ចពិសេស..."><?php echo e(old('notes')); ?></textarea>
                        <?php $__errorArgs = ['notes'];
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

            
            <div class="form-card-footer border-top bg-light p-2.5 d-flex align-items-center justify-content-between">
                <div class="text-muted small" style="font-size:0.78rem;">
                    <i class="bi bi-info-circle me-1"></i>ពិនិត្យព័ត៌មានកិច្ចសន្យាខាងលើ រួចចុច <strong class="text-dark">រក្សាទុកកិច្ចសន្យា</strong>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('employee-contracts.index')); ?>" class="btn btn-sm btn-light border px-3 d-flex align-items-center text-secondary" style="border-radius:6px; font-size:0.82rem;">
                        <i class="bi bi-x me-1 fs-5"></i> បោះបង់
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary-action px-3.5 py-1.5 shadow-sm" style="border-radius:6px; font-size:0.84rem;">
                        <i class="bi bi-check-lg me-1"></i> រក្សាទុកកិច្ចសន្យា
                    </button>
                </div>
            </div>

        </div>

    </div>

    
    <div class="col-12 col-xl-4 col-lg-5">
        
        
        <div class="sticky-top" style="top: 1.5rem; z-index: 10;">
            
            
            <div class="form-card mb-2.5 border-0 shadow-sm overflow-hidden">
                <div class="p-2.5 text-center text-white" style="background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);">
                    <div class="small fw-semibold text-uppercase tracking-wider opacity-75" style="font-size:0.68rem; letter-spacing:1px;">
                        ព្រះរាជាណាចក្រកម្ពុជា • ជាតិ សាសនា ព្រះមហាក្សត្រ
                    </div>
                    <h6 class="fw-bold mb-0.5 mt-1">កិច្ចសន្យាការងារ</h6>
                    <div class="small opacity-90" style="font-size:0.75rem;">សាលារៀន ព្រះធីរបញ្ញា</div>
                </div>

                <div class="p-3 bg-white">
                    <div class="text-center pb-2 border-bottom mb-2">
                        <span class="badge rounded-pill px-2.5 py-1" id="preview_type_badge" style="background:#fef3c7; color:#78350f; font-weight:700; font-size:0.75rem;">
                            កិច្ចសន្យាមានកំណត់ថិរវេលា
                        </span>
                        <div class="fw-bold text-dark mt-1 font-monospace" style="font-size:0.92rem;" id="preview_contract_num"><?php echo e($nextContractNumber ?? 'CTR-001'); ?></div>
                    </div>

                    <div class="mb-2">
                        <div class="text-muted small mb-0.5 fw-bold text-uppercase" style="font-size:0.68rem; letter-spacing:0.5px;">ភាគី «ក» (និយោជក)</div>
                        <div class="p-2 rounded bg-light small" style="font-size:0.8rem;">
                            <div class="fw-bold text-dark">សាលារៀន ព្រះធីរបញ្ញា</div>
                            <div class="text-muted">តំណាងដោយ៖ អ្នកស្រី សូ វាសនា (នាយិកាសាលា)</div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="text-muted small mb-0.5 fw-bold text-uppercase" style="font-size:0.68rem; letter-spacing:0.5px;">ភាគី «ខ» (និយោជិត)</div>
                        <div class="p-2 rounded bg-light small" style="font-size:0.8rem;">
                            <div class="fw-bold text-dark" id="preview_emp_name">សូមជ្រើសរើសនិយោជិត...</div>
                            <div class="text-muted mt-0.5">
                                មុខតំណែង៖ <span class="fw-semibold text-dark" id="preview_emp_pos">—</span><br>
                                ផ្នែក៖ <span class="fw-semibold text-dark" id="preview_emp_dept">—</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-2.5 rounded-2 mb-2" style="background:#fffbeb; border:1px solid #fef3c7;">
                        <div class="d-flex justify-content-between mb-1 small" style="font-size:0.78rem;">
                            <span class="text-muted">ប្រាក់ខែគោល៖</span>
                            <strong class="text-dark" id="preview_salary">$250.00 / ខែ</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 small" style="font-size:0.78rem;">
                            <span class="text-muted">ម៉ោងការងារ៖</span>
                            <strong class="text-dark" id="preview_hours">40 ម៉ោង / សប្តាហ៍</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 small" style="font-size:0.78rem;">
                            <span class="text-muted">ថ្ងៃចាប់ផ្តើម៖</span>
                            <strong class="text-dark" id="preview_start"><?php echo e(date('d-m-Y')); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between small" style="font-size:0.78rem;">
                            <span class="text-muted">ថ្ងៃបញ្ចប់៖</span>
                            <strong class="text-dark" id="preview_end"><?php echo e(date('d-m-Y', strtotime('+1 year'))); ?></strong>
                        </div>
                    </div>

                    <div class="text-center pt-1.5 border-top">
                        <span class="badge px-2.5 py-1 text-success rounded-pill" id="preview_status_badge" style="background:#dcfce7; font-weight:600; font-size:0.75rem;">
                            <i class="bi bi-check-circle-fill me-1"></i> ដំណើរការ (សកម្ម)
                        </span>
                    </div>
                </div>
            </div>

            
            <div class="form-card border-0 shadow-sm p-3 bg-white">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size:0.95rem;">
                    <i class="bi bi-journal-check" style="color:#e8a838;"></i>
                    <span>ខ្លឹមសារសំខាន់នៃកិច្ចសន្យាការងារ</span>
                </h6>
                <div class="accordion accordion-flush" id="contractClausesAccordion">
                    <div class="accordion-item border-0 mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed p-2 bg-light rounded text-dark small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#clause1">
                                ប្រការ១ & ២៖ ថិរវេលា និងការសាកល្បង
                            </button>
                        </h2>
                        <div id="clause1" class="accordion-collapse collapse" data-bs-parent="#contractClausesAccordion">
                            <div class="accordion-body p-2 small text-muted" style="font-size:0.78rem;">
                                • រយៈពេលកិច្ចសន្យា ១២ខែ ឬ ១ឆ្នាំ (ជូនដំណឹងមុន ១ខែ សម្រាប់ការបន្ត)<br>
                                • រយៈពេលសាកល្បងការងារ ៣ (បី) ខែ។
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed p-2 bg-light rounded text-dark small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#clause4">
                                ប្រការ៤ & ៦៖ ប្រាក់បៀវត្ស និងប្រាក់រង្វាន់
                            </button>
                        </h2>
                        <div id="clause4" class="accordion-collapse collapse" data-bs-parent="#contractClausesAccordion">
                            <div class="accordion-body p-2 small text-muted" style="font-size:0.78rem;">
                                • ប្រាក់ខែគោលជាដុល្លារអាមេរិក (USD)<br>
                                • ប្រាក់ខែទី១៣ និងប្រាក់បំណាច់ឆ្នាំតាមការសម្រេចរបស់ស្ថាប័ន។
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed p-2 bg-light rounded text-dark small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#clause7">
                                ប្រការ៧, ៨ & ៩៖ ម៉ោងការងារ និងការឈប់សម្រាក
                            </button>
                        </h2>
                        <div id="clause7" class="accordion-collapse collapse" data-bs-parent="#contractClausesAccordion">
                            <div class="accordion-body p-2 small text-muted" style="font-size:0.78rem;">
                                • ធ្វើការ ៨ម៉ោង/ថ្ងៃ និង ៥ថ្ងៃ/សប្តាហ៍ (៤០ម៉ោង/សប្តាហ៍)<br>
                                • ឈប់សម្រាកបុណ្យជាតិ និងច្បាប់ប្រចាំឆ្នាំ<br>
                                • ឈប់សម្រាកលំហែមាតុភាព ៣ (បី) ខែ។
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.form-card {
    background: #ffffff;
    border: 1px solid var(--border, #e2e8f0);
    border-top: 3px solid var(--gold, #e8a838);
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
}
.form-card-header {
    border-bottom: 1px solid var(--border-soft, #f1f5f9);
}
.form-control-custom, .form-select-custom {
    font-size: 0.85rem;
    line-height: 1.5;
    padding: 0.38rem 0.75rem;
    height: 36px;
    border-color: #cbd5e1;
    border-radius: 6px;
    transition: all 0.15s ease-in-out;
}
textarea.form-control-custom {
    height: auto;
    min-height: 70px;
}
.form-control-custom:focus, .form-select-custom:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 3px rgba(232, 168, 56, .18);
    outline: none;
}
.form-label-custom {
    font-size: 0.82rem;
    margin-bottom: 0.2rem;
}
.input-group-text {
    font-size: 0.82rem;
}
.form-text {
    font-size: 0.72rem !important;
    margin-top: 0.15rem !important;
}
.btn-primary-action {
    background: #e8a838;
    border: 1px solid #e8a838;
    color: #ffffff;
    font-weight: 600;
}
.btn-primary-action:hover {
    background: #c98b1e;
    border-color: #c98b1e;
    color: #ffffff;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentEmpFilter = '<?php echo e($defaultFilter); ?>';

function filterEmployeeList(filterType) {
    currentEmpFilter = filterType;
    
    // Update button states
    const btnGroup = document.getElementById('empFilterGroup');
    if (btnGroup) {
        btnGroup.querySelectorAll('button').forEach(btn => {
            if (btn.getAttribute('data-filter') === filterType) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    const optgroupNo = document.getElementById('optgroup_no_contract');
    const optgroupHas = document.getElementById('optgroup_has_contract');
    const searchVal = (document.getElementById('employee_search_input')?.value || '').toLowerCase().trim();

    if (optgroupNo && optgroupHas) {
        if (filterType === 'no_contract') {
            optgroupNo.style.display = '';
            optgroupHas.style.display = 'none';
        } else if (filterType === 'has_contract') {
            optgroupNo.style.display = 'none';
            optgroupHas.style.display = '';
        } else {
            optgroupNo.style.display = '';
            optgroupHas.style.display = '';
        }
    }

    searchEmployeeList(searchVal);
}

function searchEmployeeList(query) {
    const q = (query || '').toLowerCase().trim();
    const select = document.getElementById('employee_select');
    if (!select) return;

    const optgroupNo = document.getElementById('optgroup_no_contract');
    const optgroupHas = document.getElementById('optgroup_has_contract');

    let visibleNo = 0;
    let visibleHas = 0;

    const options = select.querySelectorAll('option');
    options.forEach(opt => {
        if (!opt.value) return; // skip placeholder
        const text = opt.innerText.toLowerCase();
        const code = (opt.getAttribute('data-code') || '').toLowerCase();
        const name = (opt.getAttribute('data-name') || '').toLowerCase();
        const dept = (opt.getAttribute('data-department') || '').toLowerCase();
        const pos = (opt.getAttribute('data-position') || '').toLowerCase();
        const hasContract = opt.getAttribute('data-has-contract');

        const matchesQuery = !q || text.includes(q) || code.includes(q) || name.includes(q) || dept.includes(q) || pos.includes(q);
        
        let matchesFilter = true;
        if (currentEmpFilter === 'no_contract') {
            matchesFilter = (hasContract === '0');
        } else if (currentEmpFilter === 'has_contract') {
            matchesFilter = (hasContract === '1');
        }

        if (matchesQuery && matchesFilter) {
            opt.hidden = false;
            opt.disabled = false;
            opt.style.display = '';
            if (hasContract === '0') visibleNo++;
            else visibleHas++;
        } else {
            opt.hidden = true;
            opt.disabled = true;
            opt.style.display = 'none';
        }
    });

    if (optgroupNo) {
        optgroupNo.style.display = (currentEmpFilter !== 'has_contract' && visibleNo > 0) ? '' : 'none';
    }
    if (optgroupHas) {
        optgroupHas.style.display = (currentEmpFilter !== 'no_contract' && visibleHas > 0) ? '' : 'none';
    }
}

function updateEmployeeMetadata(selectEl) {
    const opt = selectEl.options[selectEl.selectedIndex];
    const quickCard = document.getElementById('employee_quick_card');
    const warningBox = document.getElementById('contract_warning_box');
    const warningNum = document.getElementById('warning_contract_num');
    
    if (!opt || !opt.value) {
        if (quickCard) quickCard.style.display = 'none';
        if (warningBox) warningBox.style.setProperty('display', 'none', 'important');
        document.getElementById('preview_emp_name').innerText = 'សូមជ្រើសរើសនិយោជិត...';
        document.getElementById('preview_emp_pos').innerText = '—';
        document.getElementById('preview_emp_dept').innerText = '—';
        return;
    }

    const name = opt.getAttribute('data-name') || 'N/A';
    const code = opt.getAttribute('data-code') || '—';
    const pos = opt.getAttribute('data-position') || 'Staff';
    const dept = opt.getAttribute('data-department') || 'General';
    const salary = parseFloat(opt.getAttribute('data-salary') || 0);
    const hasContract = opt.getAttribute('data-has-contract') === '1';
    const contractNum = opt.getAttribute('data-contract-number') || '';

    // Warning if already has active contract
    if (warningBox && warningNum) {
        if (hasContract) {
            warningNum.innerText = contractNum;
            warningBox.style.setProperty('display', 'flex', 'important');
        } else {
            warningBox.style.setProperty('display', 'none', 'important');
        }
    }

    // Update Quick Card
    document.getElementById('meta_name').innerText = name;
    document.getElementById('meta_code').innerText = code;
    document.getElementById('meta_position').innerText = pos;
    document.getElementById('meta_department').innerText = dept;
    document.getElementById('meta_salary').innerText = '$' + salary.toFixed(2);
    document.getElementById('meta_avatar_txt').innerText = name.substring(0, 2).toUpperCase();
    if (quickCard) quickCard.style.display = 'block';

    // Auto-fill salary if empty or 0
    const salaryInput = document.getElementById('salary_input');
    if (salaryInput && (!salaryInput.value || parseFloat(salaryInput.value) === 0 || salaryInput.value === '250.00')) {
        if (salary > 0) {
            salaryInput.value = salary.toFixed(2);
        }
    }

    // Update Live Preview
    document.getElementById('preview_emp_name').innerText = name + ' (' + code + ')';
    document.getElementById('preview_emp_pos').innerText = pos;
    document.getElementById('preview_emp_dept').innerText = dept;
    updateLivePreview();
}

function onContractTypeChange(selectEl) {
    const val = selectEl.value;
    const endInput = document.getElementById('end_date');
    const badge = document.getElementById('preview_type_badge');
    
    if (val === 'probation') {
        badge.innerText = 'ការសាកល្បងការងារ';
        addMonthsToEndDate(3);
    } else if (val === 'fixed_term') {
        badge.innerText = 'កិច្ចសន្យាមានកំណត់ថិរវេលា';
        addMonthsToEndDate(12);
    } else if (val === 'permanent') {
        badge.innerText = 'កិច្ចសន្យាមិនកំណត់ថិរវេលា';
        if (endInput) endInput.value = '';
    } else if (val === 'part_time') {
        badge.innerText = 'ការងារក្រៅម៉ោង';
    } else if (val === 'intern') {
        badge.innerText = 'កម្មសិក្សាការងារ';
        addMonthsToEndDate(3);
    }
    updateLivePreview();
}

function setStartDateToday() {
    const today = new Date().toISOString().split('T')[0];
    const startInput = document.getElementById('start_date');
    if (startInput) {
        startInput.value = today;
        onStartDateChange();
    }
}

function onStartDateChange() {
    const startVal = document.getElementById('start_date').value;
    const contractType = document.getElementById('contract_type').value;
    if (contractType === 'probation') {
        addMonthsToEndDate(3);
    } else if (contractType === 'fixed_term') {
        addMonthsToEndDate(12);
    }
    updateLivePreview();
}

function addMonthsToEndDate(months) {
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    if (!startInput || !endInput) return;

    const baseDate = startInput.value ? new Date(startInput.value) : new Date();
    baseDate.setMonth(baseDate.getMonth() + months);
    endInput.value = baseDate.toISOString().split('T')[0];
    updateLivePreview();
}

function updateLivePreview() {
    // Contract number
    const numInput = document.getElementById('contract_number');
    const previewNum = document.getElementById('preview_contract_num');
    if (numInput && previewNum) {
        previewNum.innerText = numInput.value || 'CTR-001';
    }

    // Salary
    const salInput = document.getElementById('salary_input');
    const previewSal = document.getElementById('preview_salary');
    if (salInput && previewSal) {
        const val = parseFloat(salInput.value) || 0;
        previewSal.innerText = '$' + val.toFixed(2) + ' / ខែ';
    }

    // Hours
    const hoursInput = document.getElementById('work_hours_per_week');
    const previewHours = document.getElementById('preview_hours');
    if (hoursInput && previewHours) {
        previewHours.innerText = (hoursInput.value || 40) + ' ម៉ោង / សប្តាហ៍';
    }

    // Dates
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const previewStart = document.getElementById('preview_start');
    const previewEnd = document.getElementById('preview_end');

    if (startInput && previewStart && startInput.value) {
        previewStart.innerText = formatDate(startInput.value);
    }
    if (endInput && previewEnd) {
        previewEnd.innerText = endInput.value ? formatDate(endInput.value) : 'មិនកំណត់';
    }

    // Status
    const statusSelect = document.getElementById('contract_status');
    const previewStatus = document.getElementById('preview_status_badge');
    if (statusSelect && previewStatus) {
        if (statusSelect.value === 'active') {
            previewStatus.className = 'badge px-3 py-1.5 text-success rounded-pill';
            previewStatus.style.background = '#dcfce7';
            previewStatus.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> ដំណើរការ (សកម្ម)';
        } else {
            previewStatus.className = 'badge px-3 py-1.5 text-secondary rounded-pill';
            previewStatus.style.background = '#f1f5f9';
            previewStatus.innerHTML = '<i class="bi bi-hourglass me-1"></i> ពង្រាង';
        }
    }
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return ('0' + d.getDate()).slice(-2) + '-' + months[d.getMonth()] + '-' + d.getFullYear();
}

// Initialise on load
document.addEventListener('DOMContentLoaded', function() {
    filterEmployeeList(currentEmpFilter);
    const empSelect = document.getElementById('employee_select');
    if (empSelect && empSelect.value) {
        updateEmployeeMetadata(empSelect);
    }
    updateLivePreview();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/payroll/contracts/create.blade.php ENDPATH**/ ?>