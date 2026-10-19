
<div class="card border-0 shadow-xs rounded-3 bg-white mb-2">
    <div class="card-body py-2 px-3">
        <form action="<?php echo e(route('examinations.index')); ?>" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="exams">
            <div class="col-12 col-md-4">
                <div class="search-wrapper w-100" style="max-width: 100%;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="ស្វែងរកឈ្មោះការប្រឡង...">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="academic_year_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">ឆ្នាំសិក្សាទាំងអស់</option>
                    <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($year->id); ?>" <?php echo e(request('academic_year_id') == $year->id ? 'selected' : ''); ?>>
                            <?php echo e($year->name); ?> <?php echo e($year->is_active ? '(សកម្ម)' : ''); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="exam_type_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">ប្រភេទប្រឡងទាំងអស់</option>
                    <?php $__currentLoopData = $examTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->id); ?>" <?php echo e(request('exam_type_id') == $type->id ? 'selected' : ''); ?>>
                            <?php echo e($type->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-filter flex-fill">
                    <i class="bi bi-filter me-1"></i> ចម្រាញ់
                </button>
                <?php if(request()->hasAny(['search', 'academic_year_id', 'exam_type_id'])): ?>
                    <a href="<?php echo e(route('examinations.index', ['tab' => 'exams'])); ?>" class="btn btn-sm btn-light border" title="សម្អាតតម្រង">
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
                    <th class="ps-4">ឈ្មោះការប្រឡង</th>
                    <th>ប្រភេទ</th>
                    <th>ឆ្នាំសិក្សា</th>
                    <th>ឆមាស / វគ្គ</th>
                    <th>កាលបរិច្ឆេទប្រឡង</th>
                    <th>ថ្នាក់ដែលបានកំណត់</th>
                    <th>សិស្សដែលបានបញ្ចូលពិន្ទុ</th>
                    <th class="text-end pe-4">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $exams_paginated; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4">
                            <?php if($canManageExams ?? false): ?>
                                <a href="<?php echo e(route('exams.show', $exam->id)); ?>" class="fw-semibold text-dark text-decoration-none">
                                    <?php echo e($exam->name); ?>

                                </a>
                            <?php else: ?>
                                <span class="fw-semibold text-dark"><?php echo e($exam->name); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                <?php echo e($exam->exam_type?->name ?? '—'); ?>

                            </span>
                        </td>
                        <td><?php echo e($exam->academic_year?->name ?? '—'); ?></td>
                        <td><?php echo e($exam->school_term?->name ?? 'ពេញមួយឆ្នាំ'); ?></td>
                        <td class="text-muted small">
                            <?php if($exam->start_date && $exam->end_date): ?>
                                <?php echo e($exam->start_date->format('d M')); ?> — <?php echo e($exam->end_date->format('d M Y')); ?>

                            <?php elseif($exam->start_date): ?>
                                ចាប់ផ្ដើម <?php echo e($exam->start_date->format('d M Y')); ?>

                            <?php else: ?>
                                មិនទាន់កំណត់
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('examinations.index', ['tab' => 'schedules', 'exam_id' => $exam->id])); ?>" class="text-decoration-none">
                                <span class="badge bg-light text-dark border">
                                    <?php echo e($exam->exam_schedules_count); ?> ថ្នាក់
                                </span>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo e(route('examinations.index', ['tab' => 'results', 'subtab' => 'grades', 'exam_id' => $exam->id])); ?>" class="text-decoration-none">
                                <span class="badge <?php echo e($exam->student_grades_count > 0 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border'); ?>">
                                    <?php echo e($exam->student_grades_count); ?> នាក់
                                </span>
                            </a>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <?php if($canManageExams ?? false): ?>
                                <a href="<?php echo e(route('exams.show', $exam->id)); ?>" class="action-btn" title="មើលព័ត៌មានលម្អិត">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php endif; ?>
                                <?php if($canManageExams ?? false): ?>
                                <button type="button" class="action-btn" title="កែសម្រួលការប្រឡង"
                                        onclick="openEditExamModal(<?php echo e($exam->id); ?>, '<?php echo e(addslashes($exam->name)); ?>', <?php echo e($exam->exam_type_id); ?>, <?php echo e($exam->academic_year_id); ?>, '<?php echo e($exam->school_term_id ?? ''); ?>', '<?php echo e($exam->start_date ? $exam->start_date->format('Y-m-d') : ''); ?>', '<?php echo e($exam->end_date ? $exam->end_date->format('Y-m-d') : ''); ?>')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <?php if($exam->exam_schedules_count === 0 && $exam->student_grades_count === 0): ?>
                                    <button type="button" class="action-btn action-btn-danger" title="លុបការប្រឡង"
                                            onclick="confirmDelete('<?php echo e(route('exams.destroy', $exam->id)); ?>', '<?php echo e(addslashes($exam->name)); ?>', 'Exam')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                            មិនមានទិន្នន័យការប្រឡងដែលត្រូវនឹងលក្ខខណ្ឌស្វែងរកឡើយ។
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($exams_paginated && $exams_paginated->hasPages()): ?>
        <div class="p-3 border-top">
            <?php echo e($exams_paginated->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\School\laravel\resources\views/examinations/tabs/exams.blade.php ENDPATH**/ ?>