<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $_SESSION = [];
    session_regenerate_id(true);
    flash('success', 'You have been signed out. See you soon!');
}
redirect('index.php');
