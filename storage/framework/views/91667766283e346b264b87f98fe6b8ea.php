
<div class="subtabs-wrapper">
    <div class="subtabs-nav">
        <a href="<?php echo e(route('examinations.index', ['tab' => 'results', 'subtab' => 'results'])); ?>"
           class="subtab-link <?php echo e($subtab === 'results' ? 'active' : ''); ?>">
            <i class="bi bi-pencil-square"></i>
            <span>លទ្ធផលប្រឡង</span>
            <span class="subtab-badge"><?php echo e($counts['results'] ?? 0); ?></span>
        </a>
        <a href="<?php echo e(route('examinations.index', ['tab' => 'results', 'subtab' => 'grades'])); ?>"
           class="subtab-link <?php echo e($subtab === 'grades' ? 'active' : ''); ?>">
            <i class="bi bi-card-checklist"></i>
            <span>និទ្ទេសសិស្ស</span>
            <span class="subtab-badge"><?php echo e($counts['grades'] ?? 0); ?></span>
        </a>
    </div>
</div>

<?php if($subtab === 'grades'): ?>
    <?php echo $__env->make('examinations.tabs.results.student-grades', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php else: ?>
    <?php echo $__env->make('examinations.tabs.results.exam-results', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php /**PATH D:\School\laravel\resources\views/examinations/tabs/results-grades.blade.php ENDPATH**/ ?>