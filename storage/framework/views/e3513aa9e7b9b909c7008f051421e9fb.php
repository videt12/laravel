


<div class="row g-2 mb-3">
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-xs rounded-3 px-3 py-2 bg-white h-100 border-start border-primary border-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block" style="font-size: 0.74rem;">បុគ្គលិកសរុប</span>
                    <div class="fs-5 fw-bold text-dark lh-1 my-1"><?php echo e($cardsData ? $cardsData->total() : ($allEmployees ? $allEmployees->count() : 0)); ?></div>
                    <span class="text-muted" style="font-size: 0.7rem;">បុគ្គលិកទាំងអស់ក្នុងប្រព័ន្ធ</span>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 1.05rem;">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-xs rounded-3 px-3 py-2 bg-white h-100 border-start border-success border-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block" style="font-size: 0.74rem;">កាតដំណើរការសកម្ម</span>
                    <div class="fs-5 fw-bold text-success lh-1 my-1">
                        <?php echo e($cardsData ? $cardsData->filter(function($e) { $c = $e->activeCard ?? $e->employeeCards?->first(); return $c && $c->isActive(); })->count() : 0); ?>

                    </div>
                    <span class="text-success" style="font-size: 0.7rem;">អាចស្កេនវត្តមានបានធម្មតា</span>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 1.05rem;">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-xs rounded-3 px-3 py-2 bg-white h-100 border-start border-danger border-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block" style="font-size: 0.74rem;">កាតត្រូវបានបិទ</span>
                    <div class="fs-5 fw-bold text-danger lh-1 my-1">
                        <?php echo e($cardsData ? $cardsData->filter(function($e) { $c = $e->activeCard ?? $e->employeeCards?->first(); return $c && !$c->isActive(); })->count() : 0); ?>

                    </div>
                    <span class="text-danger" style="font-size: 0.7rem;">កាតបាត់ ឬផ្អាកដំណើរការ</span>
                </div>
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 1.05rem;">
                    <i class="bi bi-shield-x"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-xs rounded-3 overflow-hidden mb-3">
    <div class="card-header bg-white px-3 py-2.5 border-bottom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 1rem;">
                    <i class="bi bi-person-vcard-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">
                        បញ្ជីកាតសម្គាល់ខ្លួន និង QR Code របស់បុគ្គលិក
                    </h6>
                    <small class="text-muted" style="font-size: 0.72rem;">គ្រប់គ្រង Secure QR Token, បោះពុម្ព ឬចេញកាតថ្មីភ្លាមៗ</small>
                </div>
            </div>
            
            
            <div>
                <form action="<?php echo e(route('staff-attendance.index')); ?>" method="GET" class="d-flex align-items-center gap-1.5 m-0">
                    <input type="hidden" name="tab" value="cards">
                    <div class="input-group input-group-sm" style="max-width: 250px;">
                        <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-2.5 py-1 text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control form-control-sm bg-light border-start-0 ps-1 py-1" placeholder="ស្វែងរកឈ្មោះ / អត្តលេខ..." value="<?php echo e(request('search')); ?>" style="font-size: 0.8rem;">
                        <button class="btn btn-sm btn-warning rounded-end-pill px-2.5 py-1 text-dark fw-bold" type="submit" style="font-size: 0.78rem;">
                            ស្វែងរក
                        </button>
                    </div>
                    <?php if(request('search')): ?>
                        <a href="<?php echo e(route('staff-attendance.index', ['tab' => 'cards'])); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" style="font-size: 0.75rem;" title="កំណត់ឡើងវិញ">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="white-space: nowrap; font-size: 0.85rem;">
                <thead class="table-light text-secondary text-uppercase" style="font-size: 0.74rem;">
                    <tr>
                        <th class="ps-3 py-2" style="width: 44px;">#</th>
                        <th class="py-2">បុគ្គលិក</th>
                        <th class="py-2">តួនាទី & ដេប៉ាតឺម៉ង់</th>
                        <th class="py-2">លេខកាត & Token QR</th>
                        <th class="py-2">ថ្ងៃចេញកាត</th>
                        <th class="py-2">ស្ថានភាព</th>
                        <th class="text-end pe-3 py-2">សកម្មភាពគ្រប់គ្រង</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $cardsData ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $card = $emp->activeCard ?? $emp->employeeCards?->first();
                            $hasActiveCard = $card && $card->isActive();
                            $empName = $emp->full_name ?? $emp->user?->name ?? 'បុគ្គលិក';
                            $empInitials = strtoupper(mb_substr($empName, 0, 2));
                        ?>
                        <tr>
                            <td class="ps-3 text-muted small font-monospace py-2">
                                <?php echo e(isset($cardsData) && method_exists($cardsData, 'firstItem') ? ($cardsData->firstItem() + $index) : ($index + 1)); ?>

                            </td>
                            <td class="py-2">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle overflow-hidden border shadow-xs d-flex align-items-center justify-content-center bg-light fw-bold text-secondary" style="width: 36px; height: 36px; flex-shrink: 0; font-size: 0.8rem;">
                                        <?php if(!empty($emp->photo_url)): ?>
                                            <img src="<?php echo e($emp->photo_url); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo e($empName); ?>">
                                        <?php else: ?>
                                            <div class="bg-primary-subtle text-primary fw-bold w-100 h-100 d-flex align-items-center justify-content-center">
                                                <?php echo e($empInitials); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark lh-sm" style="font-size: 0.86rem;"><?php echo e($empName); ?></div>
                                        <div class="text-muted font-monospace" style="font-size: 0.72rem;">
                                            <i class="bi bi-person-badge me-0.5"></i><?php echo e($emp->employee_code ?? ('EMP-'.$emp->id)); ?>

                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2">
                                <div class="fw-semibold text-dark lh-sm" style="font-size: 0.82rem;"><?php echo e($emp->employee_position?->name ?? 'គ្រូបង្រៀន'); ?></div>
                                <div class="text-muted" style="font-size: 0.72rem;"><?php echo e($emp->employee_department?->name ?? 'ទូទៅ'); ?></div>
                            </td>
                            <td class="py-2">
                                <?php if($card): ?>
                                    <div class="fw-bold font-monospace text-primary mb-0.5" style="font-size: 0.8rem;">
                                        <i class="bi bi-credit-card-2-front me-1"></i><?php echo e($card->card_number); ?>

                                    </div>
                                    <div class="d-inline-flex align-items-center gap-1 bg-light px-2 py-0.5 rounded-pill border" style="font-size: 0.7rem;">
                                        <code class="text-secondary font-monospace"><?php echo e(Str::limit($card->card_token, 14)); ?></code>
                                        <button type="button" class="btn btn-sm btn-link p-0 text-primary ms-0.5" onclick="copyCardToken('<?php echo e($card->card_token); ?>', '<?php echo e(addslashes($empName)); ?>', this)" title="ចម្លង Token សម្រាប់ស្កេន">
                                            <i class="bi bi-clipboard" style="font-size: 0.78rem;"></i>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border px-2 py-0.5" style="font-size: 0.72rem;">មិនទាន់មានកាត</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted font-monospace py-2" style="font-size: 0.78rem;">
                                <?php if($card && $card->issued_at): ?>
                                    <div><?php echo e($card->issued_at->format('d-M-Y')); ?></div>
                                    <div class="text-muted" style="font-size: 0.68rem;"><?php echo e($card->issued_at->diffForHumans()); ?></div>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="py-2">
                                <?php if(!$card): ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-0.5 rounded-pill" style="font-size: 0.72rem;">
                                        <i class="bi bi-dash-circle me-1"></i>គ្មានកាត
                                    </span>
                                <?php elseif($hasActiveCard): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>សកម្ម
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 rounded-pill fw-semibold" style="font-size: 0.72rem;" title="<?php echo e($card->deactivation_reason); ?>">
                                        <i class="bi bi-slash-circle-fill me-1"></i>បានបិទ
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3 py-2">
                                <div class="d-inline-flex align-items-center gap-1" style="white-space: nowrap;">
                                    
                                    <a href="<?php echo e(route('employees.id-card', $emp->id)); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-2 py-0.5 d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;" title="មើល & បោះពុម្ពកាតបុគ្គលិកផ្លូវការ">
                                        <i class="bi bi-printer-fill"></i>
                                        <span>បោះពុម្ព</span>
                                    </a>

                                    <?php if($card): ?>
                                        
                                        <?php if($hasActiveCard): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-0.5 d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;" onclick="openDeactivateModal(<?php echo e($card->id); ?>, '<?php echo e(addslashes($empName)); ?>', '<?php echo e($card->card_number); ?>')" title="បិទដំណើរការកាត (ករណីបាត់ ឬខូច)">
                                                <i class="bi bi-shield-lock-fill"></i>
                                                <span>បិទកាត</span>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-2 py-0.5 d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;" title="បើកដំណើរការកាតឡើងវិញ" onclick="openActivateModal(<?php echo e($card->id); ?>, '<?php echo e(addslashes($empName)); ?>', '<?php echo e($card->card_number); ?>')">
                                                <i class="bi bi-unlock-fill"></i>
                                                <span>បើកវិញ</span>
                                            </button>
                                        <?php endif; ?>

                                        
                                        <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-2 py-0.5 d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;" title="ចេញកាតថ្មី (បិទកាតចាស់)" onclick="openGenerateModal(<?php echo e($emp->id); ?>, '<?php echo e(addslashes($empName)); ?>', '<?php echo e($card->card_number); ?>')">
                                            <i class="bi bi-arrow-repeat"></i>
                                            <span>កាតថ្មី</span>
                                        </button>
                                    <?php else: ?>
                                        
                                        <button type="button" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-2.5 py-0.5 shadow-xs d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;" onclick="openGenerateModal(<?php echo e($emp->id); ?>, '<?php echo e(addslashes($empName)); ?>', '')">
                                            <i class="bi bi-plus-circle-fill"></i>
                                            <span>បង្កើតកាត</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-person-x fs-2 text-secondary opacity-50 mb-1 d-block"></i>
                                <span class="small">មិនមានទិន្នន័យបុគ្គលិកត្រូវបង្ហាញឡើយ។</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if(isset($cardsData) && method_exists($cardsData, 'hasPages') && $cardsData->hasPages()): ?>
        <div class="card-footer bg-white px-3 py-2 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted" style="font-size: 0.75rem;">
                បង្ហាញពី <?php echo e($cardsData->firstItem()); ?> ដល់ <?php echo e($cardsData->lastItem()); ?> នៃបុគ្គលិកសរុប <?php echo e($cardsData->total()); ?> នាក់
            </div>
            <div>
                <?php echo e($cardsData->links()); ?>

            </div>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\School\laravel\resources\views/employees/attendance/partials/cards_table.blade.php ENDPATH**/ ?>