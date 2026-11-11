<?php
declare(strict_types=1);

function asset(string $path): string
{
    $file = __DIR__ . '/../assets/' . $path;
    $version = is_file($file) ? filemtime($file) : time();
    return 'assets/' . $path . '?v=' . $version;
}

function brand_mark(int $size = 36): string
{
    return <<<SVG
<svg class="brand-mark" width="$size" height="$size" viewBox="0 0 40 40" aria-hidden="true">
  <defs><linearGradient id="bm" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#7a1a36"/><stop offset="1" stop-color="#b83a5e"/></linearGradient></defs>
  <rect width="40" height="40" rx="11" fill="url(#bm)"/>
  <path d="M10 27l7-8 5 5 8-11" fill="none" stroke="#fff" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/>
  <circle cx="30" cy="13" r="2.6" fill="#fff"/>
</svg>
SVG;
}

function head_tags(string $title): void
{
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> · <?= e(APP_NAME) ?></title>
    <link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
    <script>
      (function () {
        try {
          var t = localStorage.getItem('theme');
          if (!t) t = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
          document.documentElement.setAttribute('data-bs-theme', t);
        } catch (e) {}
      })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php
}

function layout_start(string $title, string $active, string $extraHead = ''): void
{
    $user = current_user();
    $nav = [
        'dashboard' => ['href' => 'dashboard.php', 'icon' => 'bi-grid-1x2', 'label' => 'Dashboard'],
        'new'       => ['href' => 'job_new.php', 'icon' => 'bi-plus-square', 'label' => 'New Job'],
    ];
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php head_tags($title); ?>
    <?= $extraHead ?>
    <link href="<?= e(asset('css/app.css')) ?>" rel="stylesheet">
</head>
<body class="app-body">
<div class="app-shell">
    <aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar" aria-label="Main navigation">
        <div class="sidebar-inner">
            <a class="brand" href="dashboard.php">
                <?= brand_mark() ?>
                <span><strong><?= e(APP_NAME) ?></strong><small>Enhancement tracking</small></span>
            </a>
            <button type="button" class="btn-close d-lg-none ms-auto position-absolute top-0 end-0 m-3" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>

            <nav class="side-nav">
                <span class="side-label">Menu</span>
                <?php foreach ($nav as $key => $item): ?>
                    <a href="<?= e($item['href']) ?>" class="side-link<?= $key === $active ? ' active' : '' ?>">
                        <i class="bi <?= e($item['icon']) ?>"></i><?= e($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="sidebar-foot">
                <button type="button" class="side-link w-100 border-0 bg-transparent" data-theme-toggle>
                    <i class="bi bi-moon-stars theme-icon-light"></i><i class="bi bi-sun theme-icon-dark"></i>
                    <span>Toggle theme</span>
                </button>
                <?php if ($user): ?>
                <div class="user-chip">
                    <span class="avatar"><?= e(initials($user['fullname'])) ?></span>
                    <span class="user-meta">
                        <strong title="<?= e($user['fullname']) ?>"><?= e($user['fullname']) ?></strong>
                        <small><?= e($user['staff_id']) ?></small>
                    </span>
                    <form method="post" action="logout.php" class="ms-auto">
                        <?= csrf_field() ?>
                        <button class="btn btn-icon" title="Sign out" aria-label="Sign out"><i class="bi bi-box-arrow-right"></i></button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </aside>

    <div class="app-main">
        <header class="topbar d-lg-none">
            <button class="btn btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Open menu">
                <i class="bi bi-list fs-4"></i>
            </button>
            <a class="brand brand-sm" href="dashboard.php"><?= brand_mark(30) ?><strong><?= e(APP_NAME) ?></strong></a>
            <button type="button" class="btn btn-icon ms-auto" data-theme-toggle aria-label="Toggle theme">
                <i class="bi bi-moon-stars theme-icon-light"></i><i class="bi bi-sun theme-icon-dark"></i>
            </button>
        </header>
        <main class="content">
    <?php
}

function render_flashes(): void
{
    $flashes = take_flashes();
    if (!$flashes) {
        return;
    }
    $icons = ['success' => 'bi-check-circle-fill', 'danger' => 'bi-exclamation-octagon-fill',
              'warning' => 'bi-exclamation-triangle-fill', 'info' => 'bi-info-circle-fill'];
    echo '<div class="toast-container position-fixed top-0 end-0 p-3">';
    foreach ($flashes as $f) {
        $type = isset($icons[$f['type']]) ? $f['type'] : 'info';
        printf(
            '<div class="toast app-toast toast-%1$s" role="status" aria-live="polite" data-bs-delay="4500">'
            . '<div class="d-flex align-items-center gap-2 p-3"><i class="bi %2$s"></i><div class="me-auto">%3$s</div>'
            . '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div></div>',
            e($type), e($icons[$type]), e($f['message'])
        );
    }
    echo '</div>';
}

function layout_end(string $scripts = ''): void
{
    ?>
        </main>
        <footer class="app-footer">
            <span>&copy; <?= date('Y') ?> <?= e(APP_FULL_NAME) ?></span>
        </footer>
    </div>
</div>
<?php render_flashes(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
<?= $scripts ?>
</body>
</html>
    <?php
}
