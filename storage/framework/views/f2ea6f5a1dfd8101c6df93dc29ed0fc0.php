


<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?php echo e(route('settings.users.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <h5 class="modal-title fs-6 fw-bold text-dark" id="addUserModalLabel">
                        <i class="bi bi-person-plus-fill me-2 text-warning"></i>បន្ថែមអ្នកប្រើប្រាស់ថ្មី
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">ឈ្មោះពេញ (Full Name) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required placeholder="ឧទាហរណ៍៖ សុខ ចាន់">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">អ៊ីមែល (Email) <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required placeholder="user@school.edu.kh">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">តួនាទី (Role) <span class="text-danger">*</span></label>
                        <select name="role_id" class="form-select" required>
                            <option value="">-- ជ្រើសរើសតួនាទី --</option>
                            <?php $__currentLoopData = $rolesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($r->id); ?>" <?php echo e(old('role_id') == $r->id ? 'selected' : ''); ?>>
                                    <?php echo e($r->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">ស្ថានភាព (Status) <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" <?php echo e(old('status', 'active') === 'active' ? 'selected' : ''); ?>>សកម្ម (Active)</option>
                            <option value="inactive" <?php echo e(old('status') === 'inactive' ? 'selected' : ''); ?>>អសកម្ម (Inactive)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">ពាក្យសម្ងាត់ (Password) <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required minlength="8" placeholder="យ៉ាងតិច ៨ តួអក្សរ">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark">ផ្ទៀងផ្ទាត់ពាក្យសម្ងាត់ (Confirm Password) <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="8" placeholder="វាយពាក្យសម្ងាត់ម្ដងទៀត">
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold px-3">
                        <i class="bi bi-check-lg me-1"></i>បង្កើតអ្នកប្រើប្រាស់
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
    <div class="modal fade" id="editUserModal<?php echo e($u->id); ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?php echo e($u->id); ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="<?php echo e(route('settings.users.update', $u->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-header bg-light border-bottom px-4 py-3">
                        <h5 class="modal-title fs-6 fw-bold text-dark" id="editUserModalLabel<?php echo e($u->id); ?>">
                            <i class="bi bi-pencil-square me-2 text-warning"></i>កែប្រែអ្នកប្រើប្រាស់
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">ឈ្មោះពេញ (Name) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $u->name)); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">អ៊ីមែល (Email) <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $u->email)); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">តួនាទី (Role) <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select" required>
                                <?php $__currentLoopData = $rolesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($r->id); ?>" <?php echo e($u->roles->contains('id', $r->id) ? 'selected' : ''); ?>>
                                        <?php echo e($r->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">ស្ថានភាព (Status) <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="active" <?php echo e($u->status === 'active' ? 'selected' : ''); ?>>សកម្ម (Active)</option>
                                <option value="inactive" <?php echo e($u->status === 'inactive' ? 'selected' : ''); ?>>អសកម្ម (Inactive)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">ពាក្យសម្ងាត់ថ្មី (ទុកទំនេរ ប្រសិនបើមិនចង់ប្តូរ)</label>
                            <input type="password" name="password" class="form-control" placeholder="យ៉ាងតិច ៨ តួអក្សរ">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold small text-dark">ផ្ទៀងផ្ទាត់ពាក្យសម្ងាត់ថ្មី</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="វាយពាក្យសម្ងាត់ម្ដងទៀត">
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">បោះបង់</button>
                        <button type="submit" class="btn btn-warning btn-sm fw-bold px-3">រក្សាទុកការកែប្រែ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="resetPasswordModal<?php echo e($u->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <form action="<?php echo e(route('settings.users.reset-password', $u->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header bg-light border-bottom px-3 py-2">
                        <h5 class="modal-title fs-6 fw-bold text-dark">
                            <i class="bi bi-key me-2 text-info"></i>ប្តូរពាក្យសម្ងាត់
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <p class="small text-muted mb-3">កំណត់ពាក្យសម្ងាត់ថ្មីសម្រាប់ <strong><?php echo e($u->name); ?></strong></p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">ពាក្យសម្ងាត់ថ្មី <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-sm" required minlength="8" placeholder="យ៉ាងតិច ៨ តួ">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold small text-dark">ផ្ទៀងផ្ទាត់ពាក្យសម្ងាត់ <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control form-control-sm" required minlength="8" placeholder="វាយម្ដងទៀត">
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-3 py-2 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">បោះបង់</button>
                        <button type="submit" class="btn btn-info btn-sm text-white fw-bold">ប្តូរពាក្យសម្ងាត់</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <?php if($u->id !== auth()->id()): ?>
        <div class="modal fade" id="deleteUserModal<?php echo e($u->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg">
                    <form action="<?php echo e(route('settings.users.destroy', $u->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <div class="modal-header bg-danger-subtle border-bottom border-danger-subtle px-3 py-2">
                            <h5 class="modal-title fs-6 fw-bold text-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>បញ្ជាក់ការលុប
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-3 text-center">
                            <p class="small text-dark mb-2">តើអ្នកពិតជាចង់លុបអ្នកប្រើប្រាស់ <strong><?php echo e($u->name); ?></strong> មែនទេ?</p>
                            <p class="small text-muted mb-0">ទិន្នន័យនេះនឹងត្រូវលុបចេញពីប្រព័ន្ធ (Soft Delete)។</p>
                        </div>
                        <div class="modal-footer bg-light px-3 py-2 border-top justify-content-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">បោះបង់</button>
                            <button type="submit" class="btn btn-danger btn-sm fw-bold">យល់ព្រមលុប</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?php echo e(route('settings.roles.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <h5 class="modal-title fs-6 fw-bold text-dark" id="addRoleModalLabel">
                        <i class="bi bi-shield-plus me-2 text-warning"></i>បន្ថែមតួនាទីថ្មី
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">
                            ឈ្មោះតួនាទី (Role Name) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="name" id="roleNameInput" class="form-control" list="rolePresetList" value="<?php echo e(old('name')); ?>" required placeholder="វាយបញ្ចូល ឬចុចជ្រើសរើសពីបញ្ជី...">
                            <button class="btn btn-outline-warning dropdown-toggle text-dark fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-list-check me-1"></i>ជ្រើសរើស
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 280px; overflow-y: auto;">
                                <li><h6 class="dropdown-header text-muted small">តួនាទីគំរូក្នុងសាលា</h6></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Principal" data-slug="principal">Principal (នាយកសាលា)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Vice Principal" data-slug="vice-principal">Vice Principal (នាយករងសាលា)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Academic Coordinator" data-slug="academic-coordinator">Academic Coordinator (អ្នកសម្របសម្រួលការសិក្សា)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Head of Department" data-slug="head-of-department">Head of Department (ប្រធានដេប៉ាតឺម៉ង់)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Teacher" data-slug="teacher">Teacher (គ្រូបង្រៀន)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Assistant Teacher" data-slug="assistant-teacher">Assistant Teacher (គ្រូជំនួយ)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Discipline Officer" data-slug="discipline-officer">Discipline Officer (មន្ត្រីវិន័យ)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Librarian" data-slug="librarian">Librarian (បណ្ណារក្ស)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Accountant" data-slug="accountant">Accountant (គណនេយ្យករ)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Cashier" data-slug="cashier">Cashier (បេឡាធិការ)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Registrar" data-slug="registrar">Registrar (មន្ត្រីចុះឈ្មោះ)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Administrative Officer" data-slug="administrative-officer">Administrative Officer (មន្ត្រីរដ្ឋបាល)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="IT Administrator" data-slug="it-administrator">IT Administrator (អ្នកគ្រប់គ្រង IT)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Counselor" data-slug="counselor">Counselor (ទីប្រឹក្សាអប់រំ)</a></li>
                                <li><a class="dropdown-item small preset-role-item" href="javascript:void(0)" data-name="Staff" data-slug="staff">Staff (បុគ្គលិកទូទៅ)</a></li>
                            </ul>
                        </div>
                        <datalist id="rolePresetList">
                            <option value="Principal">Principal (នាយកសាលា)</option>
                            <option value="Vice Principal">Vice Principal (នាយករងសាលា)</option>
                            <option value="Academic Coordinator">Academic Coordinator (អ្នកសម្របសម្រួលការសិក្សា)</option>
                            <option value="Head of Department">Head of Department (ប្រធានដេប៉ាតឺម៉ង់)</option>
                            <option value="Teacher">Teacher (គ្រូបង្រៀន)</option>
                            <option value="Assistant Teacher">Assistant Teacher (គ្រូជំនួយ)</option>
                            <option value="Discipline Officer">Discipline Officer (មន្ត្រីវិន័យ)</option>
                            <option value="Librarian">Librarian (បណ្ណារក្ស)</option>
                            <option value="Accountant">Accountant (គណនេយ្យករ)</option>
                            <option value="Cashier">Cashier (បេឡាធិការ)</option>
                            <option value="Registrar">Registrar (មន្ត្រីចុះឈ្មោះ)</option>
                            <option value="Administrative Officer">Administrative Officer (មន្ត្រីរដ្ឋបាល)</option>
                            <option value="IT Administrator">IT Administrator (អ្នកគ្រប់គ្រង IT)</option>
                            <option value="Counselor">Counselor (ទីប្រឹក្សាអប់រំ)</option>
                            <option value="Staff">Staff (បុគ្គលិកទូទៅ)</option>
                        </datalist>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark">កូដសម្គាល់ (Slug - ស្រេចចិត្ត)</label>
                        <input type="text" name="slug" id="roleSlugInput" class="form-control" value="<?php echo e(old('slug')); ?>" placeholder="ឧទាហរណ៍៖ assistant-manager (ទុកទំនេរដើម្បីបង្កើតស្វ័យប្រវត្តិ)">
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold px-3">
                        <i class="bi bi-check-lg me-1"></i>បង្កើតតួនាទី
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
    <div class="modal fade" id="permissionsModal<?php echo e($r->id); ?>" tabindex="-1" aria-labelledby="permissionsModalLabel<?php echo e($r->id); ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <form action="<?php echo e(route('settings.roles.permissions', $r->id)); ?>" method="POST" class="modal-content border-0 shadow-lg" style="max-height: 90vh;">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-light border-bottom px-4 py-3 flex-shrink-0">
                    <div>
                        <h5 class="modal-title fs-6 fw-bold mb-1 text-dark" id="permissionsModalLabel<?php echo e($r->id); ?>">
                            <i class="bi bi-shield-check me-2 text-warning"></i>កំណត់សិទ្ធិសម្រាប់៖ <span class="text-primary"><?php echo e($r->display_name ?? $r->name); ?></span>
                        </h5>
                        <span class="text-muted small">ជ្រើសរើសសកម្មភាពដែលតួនាទីនេះអាចអនុវត្តបានក្នុងប្រព័ន្ធ</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="overflow-y: auto;">
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded border">
                        <span class="small fw-semibold text-dark">សិទ្ធិសរុប៖ <?php echo e($permissions->count()); ?></span>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-xs btn-outline-secondary select-all-btn" data-target="role-perm-<?php echo e($r->id); ?>">
                                <i class="bi bi-check-all me-1"></i>ជ្រើសរើសទាំងអស់
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary deselect-all-btn" data-target="role-perm-<?php echo e($r->id); ?>">
                                <i class="bi bi-dash me-1"></i>ដោះការជ្រើសរើស
                            </button>
                        </div>
                    </div>

                    <div class="row g-3" id="role-perm-<?php echo e($r->id); ?>">
                        <?php $__currentLoopData = $groupedPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePerms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border shadow-none bg-white">
                                    <div class="card-header bg-light py-2 px-3 d-flex align-items-center justify-content-between">
                                        <span class="fw-bold small text-dark">
                                            <i class="bi bi-folder me-1 text-warning"></i><?php echo e(\App\Models\Auth\Permission::translateModule($module)); ?>

                                        </span>
                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">
                                            <?php echo e($modulePerms->count()); ?> សិទ្ធិ
                                        </span>
                                    </div>
                                    <div class="card-body p-3">
                                        <?php $__currentLoopData = $modulePerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="<?php echo e($p->id); ?>" id="modal_perm_<?php echo e($r->id); ?>_<?php echo e($p->id); ?>"
                                                    <?php echo e($r->permissions->contains('id', $p->id) ? 'checked' : ''); ?>>
                                                <label class="form-check-label small" for="modal_perm_<?php echo e($r->id); ?>_<?php echo e($p->id); ?>">
                                                    <div class="fw-semibold text-dark"><?php echo e($p->display_name ?? $p->name); ?></div>
                                                    <code class="text-muted" style="font-size: 0.7rem;"><?php echo e($p->slug); ?></code>
                                                </label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top flex-shrink-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold px-3">
                        <i class="bi bi-save me-1"></i>រក្សាទុកសិទ្ធិ
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal fade" id="editRoleModal<?php echo e($r->id); ?>" tabindex="-1" aria-labelledby="editRoleModalLabel<?php echo e($r->id); ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <form action="<?php echo e(route('settings.roles.update', $r->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-header bg-light border-bottom px-3 py-2">
                        <h5 class="modal-title fs-6 fw-bold text-dark" id="editRoleModalLabel<?php echo e($r->id); ?>">
                            <i class="bi bi-pencil me-2 text-warning"></i>កែប្រែតួនាទី
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">ឈ្មោះតួនាទី <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" value="<?php echo e(old('name', $r->name)); ?>" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold small text-dark">កូដសម្គាល់ (Slug)</label>
                            <input type="text" name="slug" class="form-control form-control-sm" value="<?php echo e(old('slug', $r->slug)); ?>" <?php echo e($r->slug === 'super-admin' ? 'readonly' : ''); ?>>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-3 py-2 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">បោះបង់</button>
                        <button type="submit" class="btn btn-warning btn-sm fw-bold">រក្សាទុក</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <?php if(!in_array($r->slug, ['super-admin', 'admin'])): ?>
        <div class="modal fade" id="deleteRoleModal<?php echo e($r->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg">
                    <form action="<?php echo e(route('settings.roles.destroy', $r->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <div class="modal-header bg-danger-subtle border-bottom border-danger-subtle px-3 py-2">
                            <h5 class="modal-title fs-6 fw-bold text-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>បញ្ជាក់ការលុបតួនាទី
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-3 text-center">
                            <p class="small text-dark mb-2">តើអ្នកពិតជាចង់លុបតួនាទី <strong><?php echo e($r->name); ?></strong> មែនទេ?</p>
                            <?php if($r->users_count > 0): ?>
                                <div class="alert alert-danger py-1 px-2 small mb-0">
                                    មានអ្នកប្រើប្រាស់ចំនួន <?php echo e($r->users_count); ?> នាក់កំពុងកាន់កាប់តួនាទីនេះ មិនអាចលុបបានទេ។
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer bg-light px-3 py-2 border-top justify-content-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">បោះបង់</button>
                            <button type="submit" class="btn btn-danger btn-sm fw-bold" <?php echo e($r->users_count > 0 ? 'disabled' : ''); ?>>
                                យល់ព្រមលុប
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="modal fade" id="roleUsersModal<?php echo e($r->id); ?>" tabindex="-1" aria-labelledby="roleUsersModalLabel<?php echo e($r->id); ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-xs" style="width: 36px; height: 36px; background: #e8a838;">
                            <i class="bi bi-shield-lock-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fs-6 fw-bold text-dark mb-0" id="roleUsersModalLabel<?php echo e($r->id); ?>">
                                បញ្ជីអ្នកប្រើប្រាស់ក្នុងតួនាទី៖ <?php echo e($r->display_name ?? $r->name); ?>

                            </h5>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <code class="bg-white border text-secondary px-2 py-0.5 rounded small" style="font-size: 0.72rem;"><?php echo e($r->slug); ?></code>
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                                    សរុប <?php echo e($r->users->count()); ?> នាក់
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <?php if($r->users->isEmpty()): ?>
                        <div class="text-center py-5 px-3">
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3 text-muted" style="width: 64px; height: 64px;">
                                <i class="bi bi-people fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-secondary mb-1">មិនទាន់មានអ្នកប្រើប្រាស់ក្នុងតួនាទីនេះនៅឡើយទេ</h6>
                            <p class="text-muted small mb-3">លោកអ្នកអាចចូលទៅកាន់ Tab «អ្នកប្រើប្រាស់» ដើម្បីកំណត់ ឬផ្ដល់តួនាទីនេះដល់គណនីណាមួយ។</p>
                            <a href="<?php echo e(route('settings.index', ['tab' => 'users'])); ?>" class="btn btn-outline-primary btn-sm px-3">
                                <i class="bi bi-arrow-right me-1"></i>ទៅកាន់បញ្ជីអ្នកប្រើប្រាស់
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive mb-0">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.84rem;">
                                <thead class="table-light text-muted border-bottom">
                                    <tr>
                                        <th class="ps-4" style="width: 50px;">#</th>
                                        <th>ឈ្មោះអ្នកប្រើប្រាស់</th>
                                        <th>អ៊ីមែល (Email)</th>
                                        <th>ព័ត៌មានភ្ជាប់ (Linked Profile)</th>
                                        <th class="text-center">ស្ថានភាព</th>
                                        <th class="text-end pe-4">សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $r->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uIndex => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4 text-muted small"><?php echo e($uIndex + 1); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if($user->employee && $user->employee->photo_url): ?>
                                                        <img src="<?php echo e($user->employee->photo_url); ?>" alt="" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
                                                    <?php elseif($user->student && $user->student->studentProfile && $user->student->studentProfile->photo): ?>
                                                        <img src="<?php echo e(asset('storage/'.$user->student->studentProfile->photo)); ?>" alt="" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                            <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-bold text-dark"><?php echo e($user->name); ?></div>
                                                        <?php if($user->employee): ?>
                                                            <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.68rem;"><?php echo e($user->employee->employee_code); ?></span>
                                                        <?php elseif($user->student): ?>
                                                            <span class="badge bg-info-subtle text-info border border-info-subtle font-monospace" style="font-size: 0.68rem;"><?php echo e($user->student->admission_number); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted"><i class="bi bi-envelope me-1"></i><?php echo e($user->email); ?></span>
                                            </td>
                                            <td>
                                                <?php if($user->employee): ?>
                                                    <div>
                                                        <span class="fw-medium text-dark"><?php echo e($user->employee->name ?? $user->name); ?></span>
                                                        <div class="text-muted small" style="font-size: 0.72rem;">
                                                            <?php echo e($user->employee->employee_position->name ?? 'បុគ្គលិក'); ?>

                                                            <?php if($user->employee->employee_department): ?>
                                                                (<?php echo e($user->employee->employee_department->name); ?>)
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php elseif($user->student): ?>
                                                    <div>
                                                        <span class="fw-medium text-dark">សិស្ស (<?php echo e($user->student->admission_number); ?>)</span>
                                                        <div class="text-muted small" style="font-size: 0.72rem;">
                                                            ថ្នាក់ <?php echo e($user->student->schoolClass->name ?? 'ទូទៅ'); ?>

                                                            <?php if($user->student->roll_number): ?>
                                                                (លេខរៀង៖ <?php echo e($user->student->roll_number); ?>)
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php elseif($user->guardian): ?>
                                                    <div>
                                                        <span class="fw-medium text-dark">អាណាព្យាបាល</span>
                                                        <div class="text-muted small" style="font-size: 0.72rem;">
                                                            <?php echo e($user->guardian->occupation ?? 'Guardian'); ?>

                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-muted border">គណនីប្រព័ន្ធ (Admin System)</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($user->status === 'active'): ?>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                                                        <i class="bi bi-check-circle me-1"></i>សកម្ម
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                                                        <i class="bi bi-x-circle me-1"></i>អសកម្ម
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="<?php echo e(route('settings.index', ['tab' => 'users', 'search' => $user->email])); ?>" class="btn btn-sm btn-outline-secondary py-1 px-2.5" title="កែប្រែគណនីក្នុង Tab អ្នកប្រើប្រាស់">
                                                    <i class="bi bi-pencil me-1"></i>កែប្រែ
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer bg-light px-4 py-2.5 border-top d-flex justify-content-between">
                    <span class="small text-muted">
                        <i class="bi bi-info-circle me-1"></i>អ្នកអាចកំណត់ ឬផ្លាស់ប្តូរតួនាទីរបស់គណនីក្នុង Tab «អ្នកប្រើប្រាស់»
                    </span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">បិទ</button>
                        <a href="<?php echo e(route('settings.index', ['tab' => 'users', 'role' => $r->id])); ?>" class="btn btn-primary btn-sm px-3 fw-medium">
                            <i class="bi bi-filter me-1"></i>គ្រប់គ្រងក្នុង Tab អ្នកប្រើប្រាស់
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\School\laravel\resources\views/settings/modals.blade.php ENDPATH**/ ?>