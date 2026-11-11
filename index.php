<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';

if (current_user()) {
    redirect('dashboard.php');
}

$error = '';
$staffId = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $staffId = trim((string) ($_POST['staff_id'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($staffId === '' || $password === '') {
        $error = 'Please enter your Staff ID and password.';
    } else {
        $stmt = db()->prepare('SELECT sysid, fullname, staff_id, password FROM login WHERE staff_id = ? LIMIT 1');
        $stmt->execute([$staffId]);
        $row = $stmt->fetch();

        $stored = $row['password'] ?? '';
        $isHash = str_starts_with($stored, '$2y$') || str_starts_with($stored, '$argon2');
        $valid = $row && ($isHash ? password_verify($password, $stored) : hash_equals($stored, $password));

        if ($valid) {
            // Transparently upgrade legacy plain-text passwords to secure hashes.
            if (!$isHash || password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                db()->prepare('UPDATE login SET password = ? WHERE sysid = ?')
                    ->execute([password_hash($password, PASSWORD_DEFAULT), $row['sysid']]);
            }
            session_regenerate_id(true);
            $_SESSION['user'] = ['id' => (int) $row['sysid'], 'fullname' => $row['fullname'], 'staff_id' => $row['staff_id']];
            flash('success', 'Welcome back, ' . ucwords($row['fullname']) . '!');
            redirect('dashboard.php');
        }
        $error = 'Staff ID or password is incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php head_tags('Sign in'); ?>
    <link href="<?= e(asset('css/app.css')) ?>" rel="stylesheet">
</head>
<body>
<div class="auth-page">
    <section class="auth-hero">
        <a class="brand" href="index.php">
            <?= brand_mark(42) ?>
            <span><strong><?= e(APP_NAME) ?></strong><small><?= e(APP_FULL_NAME) ?></small></span>
        </a>

        <h1>Every enhancement, from idea to rollout.</h1>
        <p class="lead">Track business enhancement requests, delivery milestones and man-day costs in one clean workspace.</p>

        <ul class="auth-features">
            <li><i class="bi bi-kanban"></i> Seven-stage delivery pipeline</li>
            <li><i class="bi bi-calendar2-check"></i> RFS &amp; rollout date tracking</li>
            <li><i class="bi bi-cash-coin"></i> Automatic internal cost calculation</li>
        </ul>

        <div class="auth-float d-none d-xl-block" aria-hidden="true">
            <div class="d-flex justify-content-between small fw-semibold"><span>Pipeline health</span><span>72%</span></div>
            <div class="mini-bar"><span style="width:72%"></span></div>
            <div class="d-flex justify-content-between small fw-semibold mt-3"><span>Completed this quarter</span><span>18</span></div>
            <div class="mini-bar"><span style="width:54%"></span></div>
        </div>

        <small class="copy">&copy; <?= date('Y') ?> <?= e(APP_FULL_NAME) ?></small>
    </section>

    <section class="auth-form-wrap position-relative">
        <button type="button" class="btn btn-icon auth-theme" data-theme-toggle aria-label="Toggle theme">
            <i class="bi bi-moon-stars theme-icon-light"></i><i class="bi bi-sun theme-icon-dark"></i>
        </button>

        <div class="auth-card fade-up">
            <span class="eyebrow"><i class="bi bi-shield-lock"></i> Secure sign in</span>
            <h2 class="mb-1">Welcome back</h2>
            <p class="text-muted mb-4">Sign in with your staff credentials to continue.</p>

            <?php foreach (take_flashes() as $f): ?>
                <div class="alert alert-<?= e($f['type']) ?> border-0 rounded-3 py-2 small"><?= e($f['message']) ?></div>
            <?php endforeach; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center gap-2 py-2 small" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i><?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label" for="staff_id">Staff ID</label>
                    <div class="auth-input">
                        <i class="bi bi-person-badge"></i>
                        <input class="form-control" id="staff_id" name="staff_id" autocomplete="username"
                               placeholder="e.g. 1234abn" value="<?= e($staffId) ?>" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <div class="auth-input">
                        <i class="bi bi-lock"></i>
                        <input class="form-control" type="password" id="password" name="password"
                               autocomplete="current-password" placeholder="••••••••" required>
                        <button type="button" class="btn btn-icon toggle-pw" data-toggle-password="password" aria-label="Show password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <button class="btn btn-primary btn-lg w-100" type="submit">
                    Sign in <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </form>
        </div>
    </section>
</div>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
