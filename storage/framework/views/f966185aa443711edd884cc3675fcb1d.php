


<div class="modal fade" id="createExamModal" tabindex="-1" aria-labelledby="createExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="createExamModalLabel">
                    <i class="bi bi-journal-plus text-primary me-2"></i>បង្កើតការប្រឡងថ្មី
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('exams.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-8">
                            <label for="create_exam_name" class="form-label small fw-bold text-dark">
                                ឈ្មោះការប្រឡង <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="create_exam_name" class="form-control" required
                                   placeholder="ឧ. ការប្រឡងឆមាសទី១ ឆ្នាំ២០២៦">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="create_exam_type_id" class="form-label small fw-bold text-dark">
                                ប្រភេទការប្រឡង <span class="text-danger">*</span>
                            </label>
                            <select name="exam_type_id" id="create_exam_type_id" class="form-select" required>
                                <option value="">ជ្រើសរើសប្រភេទ</option>
                                <?php $__currentLoopData = $examTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="create_exam_ay_id" class="form-label small fw-bold text-dark">
                                ឆ្នាំសិក្សា <span class="text-danger">*</span>
                            </label>
                            <select name="academic_year_id" id="create_exam_ay_id" class="form-select" required>
                                <option value="">ជ្រើសរើសឆ្នាំសិក្សា</option>
                                <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year->id); ?>" <?php echo e($year->is_active ? 'selected' : ''); ?>>
                                        <?php echo e($year->name); ?> <?php echo e($year->is_active ? '(សកម្ម)' : ''); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_exam_term_id" class="form-label small fw-bold text-dark">
                                ឆមាស / វគ្គសិក្សា
                            </label>
                            <select name="school_term_id" id="create_exam_term_id" class="form-select">
                                <option value="">ពេញមួយឆ្នាំសិក្សា / គ្មាន</option>
                                <?php $__currentLoopData = $schoolTerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($term->id); ?>"><?php echo e($term->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="create_exam_start_date" class="form-label small fw-bold text-dark">
                                កាលបរិច្ឆេទចាប់ផ្ដើម
                            </label>
                            <input type="date" name="start_date" id="create_exam_start_date" class="form-control">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_exam_end_date" class="form-label small fw-bold text-dark">
                                កាលបរិច្ឆេទបញ្ចប់
                            </label>
                            <input type="date" name="end_date" id="create_exam_end_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកការប្រឡង
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editExamModal" tabindex="-1" aria-labelledby="editExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="editExamModalLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>កែសម្រួលការប្រឡង
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExamForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-8">
                            <label for="edit_exam_name" class="form-label small fw-bold text-dark">
                                ឈ្មោះការប្រឡង <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="edit_exam_name" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="edit_exam_type_id" class="form-label small fw-bold text-dark">
                                ប្រភេទការប្រឡង <span class="text-danger">*</span>
                            </label>
                            <select name="exam_type_id" id="edit_exam_type_id" class="form-select" required>
                                <option value="">ជ្រើសរើសប្រភេទ</option>
                                <?php $__currentLoopData = $examTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="edit_exam_academic_year_id" class="form-label small fw-bold text-dark">
                                ឆ្នាំសិក្សា <span class="text-danger">*</span>
                            </label>
                            <select name="academic_year_id" id="edit_exam_academic_year_id" class="form-select" required>
                                <option value="">ជ្រើសរើសឆ្នាំសិក្សា</option>
                                <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?> <?php echo e($year->is_active ? '(សកម្ម)' : ''); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_exam_school_term_id" class="form-label small fw-bold text-dark">
                                ឆមាស / វគ្គសិក្សា
                            </label>
                            <select name="school_term_id" id="edit_exam_school_term_id" class="form-select">
                                <option value="">ពេញមួយឆ្នាំសិក្សា / គ្មាន</option>
                                <?php $__currentLoopData = $schoolTerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($term->id); ?>"><?php echo e($term->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="edit_exam_start_date" class="form-label small fw-bold text-dark">
                                កាលបរិច្ឆេទចាប់ផ្ដើម
                            </label>
                            <input type="date" name="start_date" id="edit_exam_start_date" class="form-control">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_exam_end_date" class="form-label small fw-bold text-dark">
                                កាលបរិច្ឆេទបញ្ចប់
                            </label>
                            <input type="date" name="end_date" id="edit_exam_end_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>ធ្វើបច្ចុប្បន្នភាពការប្រឡង
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="createScheduleModal" tabindex="-1" aria-labelledby="createScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="createScheduleModalLabel">
                    <i class="bi bi-calendar-plus text-primary me-2"></i>បង្កើតកាលវិភាគប្រឡង
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('exam-schedules.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="create_schedule_exam_id" class="form-label small fw-bold text-dark">
                            ការប្រឡង <span class="text-danger">*</span>
                        </label>
                        <select name="exam_id" id="create_schedule_exam_id" class="form-select" required>
                            <option value="">ជ្រើសរើសការប្រឡង</option>
                            <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ex->id); ?>" <?php echo e(request('exam_id') == $ex->id ? 'selected' : ''); ?>>
                                    <?php echo e($ex->name); ?> (<?php echo e($ex->academic_year?->name ?? 'Year'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="create_schedule_class_id" class="form-label small fw-bold text-dark">
                            ថ្នាក់រៀន <span class="text-danger">*</span>
                        </label>
                        <select name="class_id" id="create_schedule_class_id" class="form-select" required>
                            <option value="">ជ្រើសរើសថ្នាក់រៀន</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>">
                                    <?php echo e($c->name); ?> (<?php echo e($c->academic_year?->name ?? 'Year'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="form-text small text-muted">ឆ្នាំសិក្សារបស់ថ្នាក់រៀនត្រូវតែត្រូវគ្នាជាមួយនឹងការប្រឡងដែលបានជ្រើសរើស។</div>
                    </div>

                    <div>
                        <label for="create_schedule_section_id" class="form-label small fw-bold text-dark">
                            បន្ទប់/ក្រុម
                        </label>
                        <select name="section_id" id="create_schedule_section_id" class="form-select">
                            <option value="">គ្រប់បន្ទប់/ក្រុមទាំងអស់</option>
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sec->id); ?>"><?php echo e($sec->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកកាលវិភាគ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="editScheduleModalLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>កែសម្រួលកាលវិភាគប្រឡង
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editScheduleForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_schedule_exam_id" class="form-label small fw-bold text-dark">
                            ការប្រឡង <span class="text-danger">*</span>
                        </label>
                        <select name="exam_id" id="edit_schedule_exam_id" class="form-select" required>
                            <option value="">ជ្រើសរើសការប្រឡង</option>
                            <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ex->id); ?>">
                                    <?php echo e($ex->name); ?> (<?php echo e($ex->academic_year?->name ?? 'Year'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_schedule_class_id" class="form-label small fw-bold text-dark">
                            ថ្នាក់រៀន <span class="text-danger">*</span>
                        </label>
                        <select name="class_id" id="edit_schedule_class_id" class="form-select" required>
                            <option value="">ជ្រើសរើសថ្នាក់រៀន</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>">
                                    <?php echo e($c->name); ?> (<?php echo e($c->academic_year?->name ?? 'Year'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="edit_schedule_section_id" class="form-label small fw-bold text-dark">
                            បន្ទប់/ក្រុម
                        </label>
                        <select name="section_id" id="edit_schedule_section_id" class="form-select">
                            <option value="">គ្រប់បន្ទប់/ក្រុមទាំងអស់</option>
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sec->id); ?>"><?php echo e($sec->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>ធ្វើបច្ចុប្បន្នភាពកាលវិភាគ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="createExamSubjectModal" tabindex="-1" aria-labelledby="createExamSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="createExamSubjectModalLabel">
                    <i class="bi bi-file-earmark-plus text-primary me-2"></i>បន្ថែមមុខវិជ្ជាប្រឡង
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('exam-subjects.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-7">
                            <label for="create_esub_schedule_id" class="form-label small fw-bold text-dark">
                                កាលវិភាគប្រឡង <span class="text-danger">*</span>
                            </label>
                            <select name="exam_schedule_id" id="create_esub_schedule_id" class="form-select" required>
                                <option value="">ជ្រើសរើសកាលវិភាគ (ការប្រឡង និងថ្នាក់រៀន)</option>
                                <?php $__currentLoopData = $allSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>">
                                        <?php echo e($s->exam?->name); ?> — <?php echo e($s->schoolClass?->name); ?> <?php echo e($s->section ? '('.$s->section->name.')' : ''); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="create_esub_subject_id" class="form-label small fw-bold text-dark">
                                មុខវិជ្ជា <span class="text-danger">*</span>
                            </label>
                            <select name="subject_id" id="create_esub_subject_id" class="form-select" required>
                                <option value="">ជ្រើសរើសមុខវិជ្ជា</option>
                                <?php $__currentLoopData = $allSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sub->id); ?>"><?php echo e($sub->name); ?> (<?php echo e($sub->code ?? 'SUB'); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label for="create_esub_exam_date" class="form-label small fw-bold text-dark">
                                កាលបរិច្ឆេទប្រឡង
                            </label>
                            <input type="date" name="exam_date" id="create_esub_exam_date" class="form-control">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="create_esub_start_time" class="form-label small fw-bold text-dark">
                                ម៉ោងចាប់ផ្ដើម
                            </label>
                            <input type="time" name="start_time" id="create_esub_start_time" class="form-control">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="create_esub_end_time" class="form-label small fw-bold text-dark">
                                ម៉ោងបញ្ចប់
                            </label>
                            <input type="time" name="end_time" id="create_esub_end_time" class="form-control">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="create_esub_max_marks" class="form-label small fw-bold text-dark">
                                ពិន្ទុអតិបរមា <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="1" max="1000" name="max_marks" id="create_esub_max_marks"
                                   class="form-control" value="100.00" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_esub_pass_marks" class="form-label small fw-bold text-dark">
                                ពិន្ទុជាប់ <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" max="1000" name="pass_marks" id="create_esub_pass_marks"
                                   class="form-control" value="33.00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកមុខវិជ្ជាប្រឡង
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editExamSubjectModal" tabindex="-1" aria-labelledby="editExamSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="editExamSubjectModalLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>កែសម្រួលមុខវិជ្ជាប្រឡង
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExamSubjectForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-7">
                            <label for="edit_esub_schedule_id" class="form-label small fw-bold text-dark">
                                កាលវិភាគប្រឡង <span class="text-danger">*</span>
                            </label>
                            <select name="exam_schedule_id" id="edit_esub_schedule_id" class="form-select" required>
                                <option value="">ជ្រើសរើសកាលវិភាគ (ការប្រឡង និងថ្នាក់រៀន)</option>
                                <?php $__currentLoopData = $allSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>">
                                        <?php echo e($s->exam?->name); ?> — <?php echo e($s->schoolClass?->name); ?> <?php echo e($s->section ? '('.$s->section->name.')' : ''); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="edit_esub_subject_id" class="form-label small fw-bold text-dark">
                                មុខវិជ្ជា <span class="text-danger">*</span>
                            </label>
                            <select name="subject_id" id="edit_esub_subject_id" class="form-select" required>
                                <option value="">ជ្រើសរើសមុខវិជ្ជា</option>
                                <?php $__currentLoopData = $allSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sub->id); ?>"><?php echo e($sub->name); ?> (<?php echo e($sub->code ?? 'SUB'); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label for="edit_esub_exam_date" class="form-label small fw-bold text-dark">
                                កាលបរិច្ឆេទប្រឡង
                            </label>
                            <input type="date" name="exam_date" id="edit_esub_exam_date" class="form-control">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="edit_esub_start_time" class="form-label small fw-bold text-dark">
                                ម៉ោងចាប់ផ្ដើម
                            </label>
                            <input type="time" name="start_time" id="edit_esub_start_time" class="form-control">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="edit_esub_end_time" class="form-label small fw-bold text-dark">
                                ម៉ោងបញ្ចប់
                            </label>
                            <input type="time" name="end_time" id="edit_esub_end_time" class="form-control">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="edit_esub_max_marks" class="form-label small fw-bold text-dark">
                                ពិន្ទុអតិបរមា <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="1" max="1000" name="max_marks" id="edit_esub_max_marks"
                                   class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_esub_pass_marks" class="form-label small fw-bold text-dark">
                                ពិន្ទុជាប់ <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" max="1000" name="pass_marks" id="edit_esub_pass_marks"
                                   class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>ធ្វើបច្ចុប្បន្នភាពមុខវិជ្ជាប្រឡង
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="createGradeScaleModal" tabindex="-1" aria-labelledby="createGradeScaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-3 py-2.5">
                <h5 class="modal-title fw-bold text-dark fs-6" id="createGradeScaleModalLabel">
                    <i class="bi bi-bar-chart-steps text-primary me-2"></i>បន្ថែមកម្រិតនិទ្ទេស
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('grade-scales.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-3">
                    <div class="row g-2.5 mb-2.5">
                        <div class="col-12 col-md-8">
                            <label for="create_gs_name" class="form-label small fw-semibold text-dark mb-1">
                                ឈ្មោះកម្រិត <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="create_gs_name" class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" required
                                   placeholder="ឧ. ល្អប្រសើរ, ល្អណាស់, មធ្យម">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="create_gs_grade" class="form-label small fw-semibold text-dark mb-1">
                                និទ្ទេស <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="grade" id="create_gs_grade" class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" required
                                   placeholder="ឧ. A+, B, C">
                        </div>
                    </div>

                    <div class="row g-2.5">
                        <div class="col-12 col-md-4">
                            <label for="create_gs_min_pct" class="form-label small fw-semibold text-dark mb-1">
                                ភាគរយអប្បបរមា (%) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" max="100" name="min_percentage" id="create_gs_min_pct"
                                   class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" required placeholder="ឧ. 80.00">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="create_gs_max_pct" class="form-label small fw-semibold text-dark mb-1">
                                ភាគរយអតិបរមា (%) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" max="100" name="max_percentage" id="create_gs_max_pct"
                                   class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" required placeholder="ឧ. 89.99">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="create_gs_gpa" class="form-label small fw-semibold text-dark mb-1">
                                ពិន្ទុ GPA
                            </label>
                            <input type="number" step="0.01" min="0" max="10" name="gpa_point" id="create_gs_gpa"
                                   class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" value="0.00" placeholder="ឧ. 4.00">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-3 py-2 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="height: 32px; font-size: 0.82rem;">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-3" style="height: 32px; font-size: 0.82rem;">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកកម្រិតនិទ្ទេស
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editGradeScaleModal" tabindex="-1" aria-labelledby="editGradeScaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-3 py-2.5">
                <h5 class="modal-title fw-bold text-dark fs-6" id="editGradeScaleModalLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>កែសម្រួលកម្រិតនិទ្ទេស
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editGradeScaleForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body p-3">
                    <div class="row g-2.5 mb-2.5">
                        <div class="col-12 col-md-8">
                            <label for="edit_gs_name" class="form-label small fw-semibold text-dark mb-1">
                                ឈ្មោះកម្រិត <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="edit_gs_name" class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="edit_gs_grade" class="form-label small fw-semibold text-dark mb-1">
                                និទ្ទេស <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="grade" id="edit_gs_grade" class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" required>
                        </div>
                    </div>

                    <div class="row g-2.5">
                        <div class="col-12 col-md-4">
                            <label for="edit_gs_min_percentage" class="form-label small fw-semibold text-dark mb-1">
                                ភាគរយអប្បបរមា (%) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" max="100" name="min_percentage" id="edit_gs_min_percentage"
                                   class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="edit_gs_max_percentage" class="form-label small fw-semibold text-dark mb-1">
                                ភាគរយអតិបរមា (%) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" max="100" name="max_percentage" id="edit_gs_max_percentage"
                                   class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="edit_gs_gpa_point" class="form-label small fw-semibold text-dark mb-1">
                                ពិន្ទុ GPA
                            </label>
                            <input type="number" step="0.01" min="0" max="10" name="gpa_point" id="edit_gs_gpa_point"
                                   class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-3 py-2 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="height: 32px; font-size: 0.82rem;">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-3" style="height: 32px; font-size: 0.82rem;">
                        <i class="bi bi-check-lg me-1"></i>ធ្វើបច្ចុប្បន្នភាពកម្រិតនិទ្ទេស
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="createExamTypeModal" tabindex="-1" aria-labelledby="createExamTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-3 py-2.5">
                <h5 class="modal-title fw-bold text-dark fs-6" id="createExamTypeModalLabel">
                    <i class="bi bi-tags text-primary me-2"></i>បង្កើតប្រភេទការប្រឡង
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('exam-types.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-3">
                    <div class="mb-2.5">
                        <label for="create_et_name" class="form-label small fw-semibold text-dark mb-1">
                            ឈ្មោះប្រភេទ <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="create_et_name" class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" required
                               placeholder="ឧ. ការប្រឡងពាក់កណ្តាលឆមាស, ការប្រឡងប្រចាំខែ">
                    </div>
                    <div>
                        <label for="create_et_description" class="form-label small fw-semibold text-dark mb-1">
                            ការពិពណ៌នា
                        </label>
                        <textarea name="description" id="create_et_description" rows="2" class="form-control form-control-sm" style="font-size: 0.85rem;"
                                  placeholder="ពិពណ៌នាសង្ខេបអំពីពេលវេលាដែលការប្រឡងនេះត្រូវរៀបចំឡើង..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-3 py-2 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="height: 32px; font-size: 0.82rem;">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-3" style="height: 32px; font-size: 0.82rem;">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកប្រភេទការប្រឡង
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editExamTypeModal" tabindex="-1" aria-labelledby="editExamTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-3 py-2.5">
                <h5 class="modal-title fw-bold text-dark fs-6" id="editExamTypeModalLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>កែសម្រួលប្រភេទការប្រឡង
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExamTypeForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body p-3">
                    <div class="mb-2.5">
                        <label for="edit_et_name" class="form-label small fw-semibold text-dark mb-1">
                            ឈ្មោះប្រភេទ <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="edit_et_name" class="form-control form-control-sm" style="height: 36px; font-size: 0.85rem;" required>
                    </div>
                    <div>
                        <label for="edit_et_description" class="form-label small fw-semibold text-dark mb-1">
                            ការពិពណ៌នា
                        </label>
                        <textarea name="description" id="edit_et_description" rows="2" class="form-control form-control-sm" style="font-size: 0.85rem;"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-3 py-2 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="height: 32px; font-size: 0.82rem;">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-3" style="height: 32px; font-size: 0.82rem;">
                        <i class="bi bi-check-lg me-1"></i>ធ្វើបច្ចុប្បន្នភាព
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-body p-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger mb-3" style="width: 58px; height: 58px;">
                    <i class="bi bi-trash3-fill fs-3"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1" id="deleteModalLabel" style="font-size: 1.1rem;">
                    លុប <span id="deleteEntityType">ទិន្នន័យ</span>?
                </h6>
                <p class="text-muted small mb-3">
                    តើអ្នកប្រាកដជាចង់លុប <strong id="deleteEntityName" class="text-dark"></strong> មែនទេ?
                    សកម្មភាពនេះមិនអាចត្រឡប់វិញបានឡើយ។
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="height: 34px; font-size: 0.82rem;">
                        បោះបង់
                    </button>
                    <form id="deleteForm" method="POST" action="">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger px-3.5 d-inline-flex align-items-center gap-1.5" style="height: 34px; font-size: 0.82rem;">
                            <i class="bi bi-trash3"></i>
                            <span>លុបទិន្នន័យ</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\School\laravel\resources\views/examinations/modals.blade.php ENDPATH**/ ?>