<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/jobs.php';
require __DIR__ . '/includes/layout.php';

$user = require_login();
$job = blank_job();
$errors = [];
$mode = 'new';
$meta = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $job = job_from_input($_POST);
    $errors = validate_job($job);
    if (!$errors) {
        create_job($job, $user['fullname']);
        flash('success', 'Job "' . $job['project_system'] . '" created.');
        redirect('dashboard.php');
    }
}

layout_start('New job', 'new');
?>
<div class="page-head">
    <div>
        <a href="dashboard.php" class="eyebrow text-decoration-none"><i class="bi bi-arrow-left"></i> Back to dashboard</a>
        <h1>Create a new job</h1>
        <p>Register a business enhancement request and start tracking its delivery.</p>
    </div>
</div>
<?php
require __DIR__ . '/includes/job_form.php';
layout_end('<script src="' . e(asset('js/job-form.js')) . '"></script>');
