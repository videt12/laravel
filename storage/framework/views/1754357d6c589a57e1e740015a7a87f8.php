
<div class="tab-pane fade <?php echo e($activeTab === 'users' ? 'show active' : ''); ?>" id="tab-users" role="tabpanel">

    
    <div class="filter-bar mb-3">
        <form method="GET" action="<?php echo e(route('settings.index')); ?>" class="d-flex align-items-center gap-2 flex-wrap w-100">
            <input type="hidden" name="tab" value="users">

            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="ស្វែងរកឈ្មោះ ឬអ៊ីមែល..." aria-label="Search users">
            </div>

            <select name="role" class="form-select filter-select">
                <option value="">-- គ្រប់តួនាទី (All Roles) --</option>
                <?php $__currentLoopData = $rolesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($r->id); ?>" <?php echo e(request('role') == $r->id ? 'selected' : ''); ?>>
                        <?php echo e($r->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="status" class="form-select filter-select">
                <option value="">-- គ្រប់ស្ថានភាព --</option>
                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>សកម្ម (Active)</option>
                <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>អសកម្ម (Inactive)</option>
            </select>

            <button type="submit" class="btn btn-filter">
                <i class="bi bi-funnel me-1"></i>ត្រង
            </button>

            <?php if(request('search') || request('role') || request('status')): ?>
                <a href="<?php echo e(route('settings.index', ['tab' => 'users'])); ?>" class="btn btn-outline-secondary btn-sm" title="កំណត់ឡើងវិញ">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>សម្អាត
                </a>
            <?php endif; ?>

            <div class="ms-auto">
                <span class="text-muted small">សរុប៖ <strong class="text-dark"><?php echo e($users->total()); ?></strong> នាក់</span>
            </div>
        </form>
    </div>

    
    <div class="table-card mb-4">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>អ្នកប្រើប្រាស់</th>
                        <th>តួនាទី</th>
                        <th>ស្ថានភាព</th>
                        <th>ចូលចុងក្រោយ (Last Login)</th>
                        <th>ថ្ងៃបង្កើត</th>
                        <th class="text-end" style="width: 130px;">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-muted small"><?php echo e($users->firstItem() + $index); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar-circle">
                                        <?php echo e(strtoupper(substr($u->name, 0, 2))); ?>

                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?php echo e($u->name); ?></div>
                                        <div class="text-muted small"><i class="bi bi-envelope me-1"></i><?php echo e($u->email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php $__empty_2 = true; $__currentLoopData = $u->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                        <i class="bi bi-shield me-1"></i><?php echo e($role->name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">គ្មានតួនាទី</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($u->status === 'active'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i>សកម្ម
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                        <i class="bi bi-x-circle me-1"></i>អសកម្ម
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($u->login_activities_max_login_time): ?>
                                    <span class="small text-dark fw-medium">
                                        <i class="bi bi-clock-history me-1 text-muted"></i>
                                        <?php echo e(\Carbon\Carbon::parse($u->login_activities_max_login_time)->diffForHumans()); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">មិនធ្លាប់ចូល</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted">
                                <?php echo e($u->created_at ? $u->created_at->format('d/m/Y') : '—'); ?>

                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="ជម្រើស">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <button type="button" class="dropdown-item small" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo e($u->id); ?>">
                                                <i class="bi bi-pencil me-2 text-warning"></i>កែប្រែព័ត៌មាន
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item small" data-bs-toggle="modal" data-bs-target="#resetPasswordModal<?php echo e($u->id); ?>">
                                                <i class="bi bi-key me-2 text-info"></i>ប្តូរពាក្យសម្ងាត់
                                            </button>
                                        </li>
                                        <li>
                                            <form action="<?php echo e(route('settings.users.toggle-status', $u->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="dropdown-item small <?php echo e($u->status === 'active' ? 'text-danger' : 'text-success'); ?>" <?php echo e($u->id === auth()->id() ? 'disabled' : ''); ?>>
                                                    <i class="bi bi-power me-2"></i><?php echo e($u->status === 'active' ? 'ផ្អាកដំណើរការ (Deactivate)' : 'បើកដំណើរការ (Activate)'); ?>

                                                </button>
                                            </form>
                                        </li>
                                        <?php if($u->id !== auth()->id()): ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?php echo e($u->id); ?>">
                                                    <i class="bi bi-trash me-2"></i>លុបគណនី
                                                </button>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="empty-state-cell">
                                <div class="empty-icon"><i class="bi bi-people"></i></div>
                                <h6 class="fw-bold text-muted mb-1">មិនមានអ្នកប្រើប្រាស់នៅឡើយទេ</h6>
                                <p class="text-muted small mb-0">សូមចុចប៊ូតុង "+ បន្ថែមអ្នកប្រើប្រាស់" ដើម្បីបង្កើតគណនីថ្មី។</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($users->hasPages()): ?>
            <div class="p-3 border-top bg-white d-flex justify-content-end">
                <?php echo e($users->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH D:\School\laravel\resources\views/settings/tabs/users.blade.php ENDPATH**/ ?>