<header class="app-topbar bg-white border-bottom sticky-top">
    <div class="container-fluid px-3 px-lg-4 d-flex align-items-center justify-content-between h-100">
        
        
        <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <button type="button" class="btn btn-topbar-icon" id="sidebarToggleBtn" aria-label="Toggle navigation" title="បិទ/បើកម៉ឺនុយចំហៀង">
                <i class="bi bi-list fs-5"></i>
            </button>

            
            <div class="topbar-breadcrumb d-none d-md-flex align-items-center">
                <?php if (! empty(trim($__env->yieldContent('breadcrumb')))): ?>
                    <?php echo $__env->yieldContent('breadcrumb'); ?>
                <?php else: ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none text-muted">ទំព័រដើម</a></li>
                            <?php if(request()->routeIs('dashboard')): ?>
                                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ផ្ទាំងព័ត៌មាន</li>
                            <?php elseif(request()->routeIs('students.*')): ?>
                                <li class="breadcrumb-item"><a href="<?php echo e(route('students.index')); ?>" class="text-decoration-none text-muted">ការសិក្សា</a></li>
                                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">សិស្ស</li>
                            <?php else: ?>
                                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ផ្ទាំងព័ត៌មាន</li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="d-flex align-items-center gap-2 gap-sm-3">

            
            <?php if(auth()->user()?->canAccess('students')): ?>
            <div class="topbar-search d-none d-sm-block">
                <form action="<?php echo e(route('students.index')); ?>" method="GET" class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: .85rem;"></i>
                    <input type="text"
                           name="search"
                           class="form-control form-control-sm topbar-search-input ps-5 pe-4"
                           placeholder="ស្វែងរកសិស្ស, ថ្នាក់រៀន, កំណត់ត្រា..."
                           autocomplete="off">
                    <span class="search-shortcut position-absolute top-50 end-0 translate-middle-y me-2 text-muted d-none d-md-inline-block">/</span>
                </form>
            </div>

            
            <div class="d-sm-none">
                <button type="button" class="btn btn-topbar-icon" data-bs-toggle="collapse" data-bs-target="#mobileSearchCollapse" aria-expanded="false" title="ស្វែងរក">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <?php endif; ?>

            
            <?php
                $authUser = auth()->user();
                $userPhoto = $authUser?->photo_url ?? $authUser?->employee?->photo_url ?? $authUser?->student?->photo_url;
                $userName = $authUser?->name ?? 'អ្នកគ្រប់គ្រង';
                $userRoleName = $authUser?->roles?->first()?->display_name ?? $authUser?->roles?->first()?->name ?? $authUser?->role?->name ?? 'អ្នកប្រើប្រាស់';
                $firstChar = $userName ? mb_substr(trim($userName), 0, 1, 'UTF-8') : '';
                $isEnglishName = preg_match('/^[a-zA-Z]/', $firstChar);
            ?>
            <div class="dropdown">
                <button type="button" class="btn btn-topbar-user d-flex align-items-center gap-2 p-1" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar-topbar position-relative overflow-hidden">
                        <?php if($userPhoto): ?>
                            <img src="<?php echo e($userPhoto); ?>" alt="<?php echo e($userName); ?>" class="w-100 h-100 object-fit-cover rounded-circle" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                            <span class="d-none">
                                <?php if($isEnglishName): ?>
                                    <?php echo e(mb_strtoupper(mb_substr(trim($userName), 0, 2, 'UTF-8'))); ?>

                                <?php else: ?>
                                    <i class="bi bi-person-fill" style="font-size: 1rem;"></i>
                                <?php endif; ?>
                            </span>
                        <?php elseif($isEnglishName): ?>
                            <span><?php echo e(mb_strtoupper(mb_substr(trim($userName), 0, 2, 'UTF-8'))); ?></span>
                        <?php else: ?>
                            <i class="bi bi-person-fill" style="font-size: 1rem;"></i>
                        <?php endif; ?>
                    </div>
                    <div class="d-none d-lg-block text-start lh-1 me-1">
                        <div class="fw-semibold text-dark user-name-topbar"><?php echo e($userName); ?></div>
                        <small class="text-muted user-role-topbar"><?php echo e($userRoleName); ?></small>
                    </div>
                    <i class="bi bi-chevron-down text-muted ms-1" style="font-size: .75rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2 user-dropdown-menu" aria-labelledby="userMenuDropdown">
                    <li class="px-3 py-2 border-bottom mb-1 d-lg-none">
                        <div class="fw-semibold text-dark"><?php echo e(auth()->user()?->name ?? 'អ្នកគ្រប់គ្រង'); ?></div>
                        <small class="text-muted"><?php echo e(auth()->user()?->email ?? 'admin@school.edu'); ?></small>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#userAccountModal" onclick="switchAccountTab('profile')">
                            <i class="bi bi-person text-muted"></i>
                            <span>គណនីរបស់ខ្ញុំ</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#userAccountModal" onclick="switchAccountTab('security')">
                            <i class="bi bi-shield-lock text-muted"></i>
                            <span>ការកំណត់គណនី</span>
                        </a>
                    </li>
                    <?php if(auth()->user()?->canAccess(['settings', 'roles'])): ?>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="<?php echo e(route('settings.index')); ?>">
                            <i class="bi bi-gear text-muted"></i>
                            <span>ការកំណត់ប្រព័ន្ធ</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="m-0 p-0">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger border-0 bg-transparent w-100">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>ចាកចេញពីប្រព័ន្ធ</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    
    <?php if(auth()->user()?->canAccess('students')): ?>
    <div class="collapse bg-white border-top px-3 py-2 d-sm-none" id="mobileSearchCollapse">
        <form action="<?php echo e(route('students.index')); ?>" method="GET" class="position-relative">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: .85rem;"></i>
            <input type="text"
                   name="search"
                   class="form-control form-control-sm ps-5 pe-3"
                   placeholder="ស្វែងរកសិស្ស, ថ្នាក់រៀន, កំណត់ត្រា..."
                   autocomplete="off">
        </form>
    </div>
    <?php endif; ?>
</header>


<?php echo $__env->make('layouts.profile-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\laravel Project\laravel\resources\views/layouts/navbar.blade.php ENDPATH**/ ?>