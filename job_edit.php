<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/jobs.php';
require __DIR__ . '/includes/layout.php';

$user = require_login();
$id = (int) ($_GET['id'] ?? 0);
$meta = find_job($id);
if (!$meta) {
    flash('warning', 'That job no longer exists.');
    redirect('dashboard.php');
}

$job = array_intersect_key($meta, array_flip(JOB_COLUMNS));
$errors = [];
$mode = 'edit';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $job = job_from_input($_POST);
    $errors = validate_job($job);
    if (!$errors) {
        update_job($id, $job, $user['fullname']);
        flash('success', 'Job "' . $job['project_system'] . '" updated.');
        redirect('dashboard.php');
    }
}

layout_start('Edit job', 'dashboard');
?>
<div class="page-head">
    <div>
        <a href="dashboard.php" class="eyebrow text-decoration-none"><i class="bi bi-arrow-left"></i> Back to dashboard</a>
        <h1>Edit job <span class="text-muted fw-semibold">#<?= $id ?></span></h1>
        <p><?= e($meta['project_system'] ?: 'Untitled') ?> · <?= e($meta['workstream'] ?: 'No workstream') ?></p>
    </div>
    <form method="post" action="job_delete.php" onsubmit="return confirm('Delete this job? This cannot be undone.');">
        <?= csrf_field() ?>
        <input type="hidden" name="ids[]" value="<?= $id ?>">
        <button class="btn btn-light-outline text-danger"><i class="bi bi-trash3 me-1"></i> Delete</button>
    </form>
</div>
<?php
require __DIR__ . '/includes/job_form.php';
layout_end('<script src="' . e(asset('js/job-form.js')) . '"></script>');
