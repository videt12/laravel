
<?php
    $modalUser = auth()->user();
    $modalPhoto = $modalUser?->photo_url ?? $modalUser?->employee?->photo_url ?? $modalUser?->student?->photo_url;
    $modalName = $modalUser?->name ?? 'អ្នកប្រើប្រាស់';
    $modalRole = $modalUser?->roles?->first()?->name ?? $modalUser?->role?->name ?? 'អ្នកប្រើប្រាស់';
    $firstCharModal = $modalName ? mb_substr(trim($modalName), 0, 1, 'UTF-8') : '';
    $isEnglishModal = preg_match('/^[a-zA-Z]/', $firstCharModal);
    $initialsModal = $isEnglishModal ? mb_strtoupper(mb_substr(trim($modalName), 0, 2, 'UTF-8')) : '';
?>

<div class="modal fade" id="userAccountModal" tabindex="-1" aria-labelledby="userAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 12px;">
            
            
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-amber-subtle text-warning d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: #fef3c7;">
                        <i class="bi bi-person-circle fs-5" style="color: #b45309;"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="userAccountModalLabel">ការគ្រប់គ្រងគណនី (My Account)</h6>
                        <small class="text-muted" style="font-size: 0.78rem;">មើល និងកែសម្រួលព័ត៌មានគណនីផ្ទាល់ខ្លួន</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            
            <div class="modal-body p-0">
                
                <ul class="nav nav-tabs px-4 pt-3 border-bottom bg-white" id="accountModalTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold text-dark d-flex align-items-center gap-2 px-3 py-2" id="tab-account-profile" data-bs-toggle="tab" data-bs-target="#pane-account-profile" type="button" role="tab" aria-controls="pane-account-profile" aria-selected="true">
                            <i class="bi bi-person text-warning" style="color: #d97706 !important;"></i>
                            <span>ព័ត៌មានគណនី</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold text-muted d-flex align-items-center gap-2 px-3 py-2" id="tab-account-security" data-bs-toggle="tab" data-bs-target="#pane-account-security" type="button" role="tab" aria-controls="pane-account-security" aria-selected="false">
                            <i class="bi bi-shield-lock text-warning" style="color: #d97706 !important;"></i>
                            <span>សុវត្ថិភាព & ពាក្យសម្ងាត់</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-4" id="accountModalTabContent">
                    
                    <div class="tab-pane fade show active" id="pane-account-profile" role="tabpanel" aria-labelledby="tab-account-profile">
                        
                        <div class="d-flex align-items-center gap-3 p-3 mb-4 rounded-3" style="background-color: #fffbeb; border: 1px solid #fde68a;">
                            <div class="position-relative">
                                <div class="rounded-circle overflow-hidden shadow-sm d-flex align-items-center justify-content-center text-white fw-bold" style="width: 58px; height: 58px; background: #0f172a; font-size: 1.1rem; border: 2px solid #ffffff;">
                                    <?php if($modalPhoto): ?>
                                        <img src="<?php echo e($modalPhoto); ?>" alt="<?php echo e($modalName); ?>" class="w-100 h-100 object-fit-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                        <span class="d-none">
                                            <?php if($initialsModal): ?>
                                                <?php echo e($initialsModal); ?>

                                            <?php else: ?>
                                                <i class="bi bi-person-fill"></i>
                                            <?php endif; ?>
                                        </span>
                                    <?php elseif($initialsModal): ?>
                                        <span><?php echo e($initialsModal); ?></span>
                                    <?php else: ?>
                                        <i class="bi bi-person-fill"></i>
                                    <?php endif; ?>
                                </div>
                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" title="Online"></span>
                            </div>
                            <div class="min-w-0 flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1"><?php echo e($modalName); ?></h6>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge text-white px-2 py-1" style="background-color: #d97706; font-size: 0.75rem;">
                                        <i class="bi bi-shield-check me-1"></i><?php echo e($modalRole); ?>

                                    </span>
                                    <span class="text-muted" style="font-size: 0.8rem;">
                                        <i class="bi bi-envelope me-1"></i><?php echo e($modalUser?->email); ?>

                                    </span>
                                </div>
                            </div>
                        </div>

                        
                        <?php if($modalUser?->employee): ?>
                        <div class="alert alert-info d-flex align-items-center justify-content-between p-3 mb-4 rounded-3 border-0" style="background-color: #eff6ff; color: #1e40af;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-badge fs-4"></i>
                                <div>
                                    <div class="fw-bold" style="font-size: 0.88rem;">គណនីនេះភ្ជាប់ជាមួយប្រវត្តិរូបបុគ្គលិក</div>
                                    <div class="small text-muted" style="font-size: 0.78rem;">លេខសម្គាល់បុគ្គលិក: <?php echo e($modalUser->employee->employee_id ?? 'N/A'); ?></div>
                                </div>
                            </div>
                            <a href="<?php echo e(route('employees.show', $modalUser->employee->id)); ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i>មើលព័ត៌មានបុគ្គលិក
                            </a>
                        </div>
                        <?php endif; ?>

                        
                        <form action="<?php echo e(route('profile.update')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark" style="font-size: 0.88rem;">ឈ្មោះពេញ (Full Name) <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $modalUser?->name)); ?>" required placeholder="បញ្ចូលឈ្មោះពេញ">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark" style="font-size: 0.88rem;">អាសយដ្ឋានអ៊ីមែល (Email) <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $modalUser?->email)); ?>" required placeholder="example@school.edu">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted" style="font-size: 0.88rem;">តួនាទីក្នុងប្រព័ន្ធ (Role)</label>
                                    <input type="text" class="form-control bg-light" value="<?php echo e($modalRole); ?>" readonly disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted" style="font-size: 0.88rem;">កាលបរិច្ឆេទបង្កើតគណនី (Created At)</label>
                                    <input type="text" class="form-control bg-light" value="<?php echo e($modalUser?->created_at?->format('d/m/Y H:i') ?? 'N/A'); ?>" readonly disabled>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">បោះបង់</button>
                                <button type="submit" class="btn btn-warning text-dark fw-bold" style="background-color: #f59e0b; border-color: #f59e0b;">
                                    <i class="bi bi-check2-circle me-1"></i>រក្សាទុកការផ្លាស់ប្តូរ
                                </button>
                            </div>
                        </form>
                    </div>

                    
                    <div class="tab-pane fade" id="pane-account-security" role="tabpanel" aria-labelledby="tab-account-security">
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-1">ប្តូរពាក្យសម្ងាត់ (Change Password)</h6>
                            <p class="text-muted small mb-3">ដើម្បីសុវត្ថិភាពគណនី សូមជ្រើសរើសពាក្យសម្ងាត់ដែលមានយ៉ាងតិច ៨ តួអក្សរ។</p>
                        </div>

                        
                        <form action="<?php echo e(route('profile.password.update')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.84rem;">ពាក្យសម្ងាត់បច្ចុប្បន្ន (Current Password) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="current_password" id="inputCurrentPassword" class="form-control" required placeholder="បញ្ចូលពាក្យសម្ងាត់ចាស់">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('inputCurrentPassword', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark mb-1 d-flex align-items-center" style="font-size: 0.84rem; height: 26px; white-space: nowrap;">
                                        <span>ពាក្យសម្ងាត់ថ្មី (New Password) <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="inputNewPassword" class="form-control" required minlength="8" placeholder="យ៉ាងតិច ៨ តួអក្សរ">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('inputNewPassword', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark mb-1 d-flex align-items-center" style="font-size: 0.84rem; height: 26px; white-space: nowrap;">
                                        <span>បញ្ជាក់ពាក្យសម្ងាត់ (Confirm Password) <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="inputConfirmPassword" class="form-control" required minlength="8" placeholder="វាយពាក្យសម្ងាត់ម្ដងទៀត">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('inputConfirmPassword', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">បោះបង់</button>
                                <button type="submit" class="btn btn-danger fw-bold">
                                    <i class="bi bi-key me-1"></i>ប្តូរពាក្យសម្ងាត់ឥឡូវនេះ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function switchAccountTab(tabType) {
        if (tabType === 'security') {
            const secTab = document.getElementById('tab-account-security');
            if (secTab) {
                bootstrap.Tab.getOrCreateInstance(secTab).show();
            }
        } else {
            const profTab = document.getElementById('tab-account-profile');
            if (profTab) {
                bootstrap.Tab.getOrCreateInstance(profTab).show();
            }
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
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
</script>
<?php /**PATH E:\laravel Project\laravel\resources\views/layouts/profile-modal.blade.php ENDPATH**/ ?>