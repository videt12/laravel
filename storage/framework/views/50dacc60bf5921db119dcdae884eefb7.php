
<?php if(isset($authContext) && $authContext['isTeacher']): ?>
    <div class="alert alert-info border-0 rounded-3 shadow-xs d-flex align-items-center mb-2 py-2 px-3">
        <i class="bi bi-info-circle-fill me-2 fs-5 text-primary"></i>
        <div class="small">អ្នកកំពុងមើលវិញ្ញាសាដែលត្រូវនឹងមុខវិជ្ជា និងថ្នាក់បង្រៀនរបស់អ្នក។ ចុចលើ <strong>បញ្ចូលពិន្ទុ</strong> ដើម្បីគ្រប់គ្រងតារាងពិន្ទុសិស្ស។</div>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-xs rounded-3 bg-white mb-2">
    <div class="card-body py-2 px-3">
        <form action="<?php echo e(route('examinations.index')); ?>" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="results">
            <input type="hidden" name="subtab" value="results">
            <div class="col-12 col-md-4">
                <div class="search-wrapper w-100" style="max-width: 100%;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="ស្វែងរកមុខវិជ្ជា ឬការប្រឡង...">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="exam_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">ការប្រឡងទាំងអស់</option>
                    <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($exam->id); ?>" <?php echo e(request('exam_id') == $exam->id ? 'selected' : ''); ?>>
                            <?php echo e($exam->name); ?> (<?php echo e($exam->academic_year?->name ?? 'Year'); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="class_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">ថ្នាក់ទាំងអស់</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>" <?php echo e(request('class_id') == $c->id ? 'selected' : ''); ?>>
                            <?php echo e($c->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-filter flex-fill">
                    <i class="bi bi-filter me-1"></i> ចម្រាញ់
                </button>
                <?php if(request()->hasAny(['search', 'exam_id', 'class_id'])): ?>
                    <a href="<?php echo e(route('examinations.index', ['tab' => 'results', 'subtab' => 'results'])); ?>" class="btn btn-sm btn-light border" title="សម្អាតតម្រង">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">ការប្រឡង</th>
                    <th>ថ្នាក់រៀន</th>
                    <th>វិញ្ញាសាប្រឡង</th>
                    <th>កាលបរិច្ឆេទ</th>
                    <th>ពិន្ទុអតិបរមា</th>
                    <th>ស្ថានភាពបញ្ចូលពិន្ទុ</th>
                    <th class="text-end pe-4">តារាងពិន្ទុ</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $resultsExamSubjects_paginated; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $esub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4">
                            <span class="fw-semibold text-dark"><?php echo e($esub->exam_schedule?->exam?->name ?? '—'); ?></span>
                            <div class="text-muted small"><?php echo e($esub->exam_schedule?->exam?->academic_year?->name ?? '—'); ?></div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?php echo e($esub->exam_schedule?->schoolClass?->name ?? '—'); ?>

                                <?php if($esub->exam_schedule?->section): ?>
                                    (<?php echo e($esub->exam_schedule->section->name); ?>)
                                <?php endif; ?>
                            </span>
                        </td>
                        <td class="fw-semibold text-primary">
                            <?php echo e($esub->subject?->name ?? '—'); ?>

                            <span class="text-muted small">(<?php echo e($esub->subject?->code ?? ''); ?>)</span>
                        </td>
                        <td class="small text-muted">
                            <?php echo e($esub->exam_date?->format('d/m/Y') ?? 'មិនទាន់កំណត់'); ?>

                        </td>
                        <td>
                            <strong><?php echo e(number_format($esub->max_marks, 2)); ?></strong>
                            <span class="text-muted small">(ជាប់: <?php echo e(number_format($esub->pass_marks, 2)); ?>)</span>
                        </td>
                        <td>
                            <?php if($esub->exam_results_count > 0): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> បញ្ចូលពិន្ទុរួច (<?php echo e($esub->exam_results_count); ?>)
                                </span>
                            <?php else: ?>
                                <span class="badge bg-light text-muted border px-2 py-1">
                                    មិនទាន់បញ្ចូលពិន្ទុ
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('exam-subjects.scores', $esub->id)); ?>" class="btn btn-sm btn-gold text-white py-1 px-3" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); font-size: 0.82rem;">
                                <i class="bi bi-pencil-square me-1"></i> បញ្ចូលពិន្ទុ
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                            <?php if(isset($authContext) && $authContext['isTeacher']): ?>
                                មិនមានវិញ្ញាសាប្រឡងសម្រាប់ថ្នាក់ និងមុខវិជ្ជារបស់លោកអ្នកឡើយ។
                            <?php else: ?>
                                មិនមានវិញ្ញាសាប្រឡងដែលបានកំណត់ឡើយ។
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($resultsExamSubjects_paginated && $resultsExamSubjects_paginated->hasPages()): ?>
        <div class="p-3 border-top">
            <?php echo e($resultsExamSubjects_paginated->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\School\laravel\resources\views/examinations/tabs/results/exam-results.blade.php ENDPATH**/ ?>