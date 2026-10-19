


<div class="modal fade" id="createGradeModal" tabindex="-1" aria-labelledby="createGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="createGradeModalLabel">
                    <i class="bi bi-mortarboard-fill text-warning me-2"></i>បន្ថែមកម្រិតថ្នាក់
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('grades.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="create_grade_name" class="form-label small fw-bold text-dark">
                            ឈ្មោះកម្រិតថ្នាក់ <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="create_grade_name" class="form-control" required
                                placeholder="ឧ. ថ្នាក់ទី ៧, ថ្នាក់ទី ៨, ថ្នាក់ទី ៩...">
                        <div class="form-text small text-muted">ឈ្មោះដែលត្រូវបង្ហាញនៅលើរបាយការណ៍ កាលវិភាគ និងបញ្ជីថ្នាក់រៀន។</div>
                    </div>

                    <div class="mb-3">
                        <label for="create_grade_level" class="form-label small fw-bold text-dark">
                            លេខលំដាប់ថ្នាក់ <span class="text-muted fw-normal">(ស្រេចចិត្ត)</span>
                        </label>
                        <input type="number" name="level" id="create_grade_level" class="form-control"
                               min="0" max="100" placeholder="ឧ. 1, 2, 3 (ទុកទទេដើម្បីកំណត់ស្វ័យប្រវត្តិ)">
                        <div class="form-text small text-muted">ប្រើសម្រាប់រៀបលំដាប់ថ្នាក់។ ប្រសិនបើទុកទទេ ប្រព័ន្ធនឹងកំណត់ស្វ័យប្រវត្តិតាមលំដាប់បន្តបន្ទាប់។</div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកកម្រិតថ្នាក់
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="createSectionModal" tabindex="-1" aria-labelledby="createSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6" id="createSectionModalLabel">
                    <i class="bi bi-door-open-fill text-primary me-2"></i>បន្ថែមបន្ទប់ / ក្រុមអក្សរ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('sections.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="create_section_name" class="form-label small fw-bold text-dark">
                            ឈ្មោះ / អក្សរសម្គាល់បន្ទប់ <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="create_section_name" class="form-control" required
                               placeholder="ឧ. ក, ខ, គ, A, B...">
                        <div class="form-text small text-muted">អក្សរឬឈ្មោះសម្គាល់បន្ទប់ (ឧទាហរណ៍៖ ក ឬ A)។</div>
                    </div>

                    <div class="mb-3">
                        <label for="create_section_capacity" class="form-label small fw-bold text-dark">
                            ចំណុះកៅអីលំនាំដើម
                        </label>
                        <input type="number" name="capacity" id="create_section_capacity" class="form-control"
                               min="1" max="500" value="40" placeholder="ឧ. 40">
                        <div class="form-text small text-muted">ចំនួនកៅអីលំនាំដើមសម្រាប់ថ្នាក់រៀនដែលបង្កើតក្រោមបន្ទប់នេះ។</div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកបន្ទប់
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH D:\School\laravel\resources\views/academics/partials/grade-section-modals.blade.php ENDPATH**/ ?>