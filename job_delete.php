<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/jobs.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}
verify_csrf();

$ids = (array) ($_POST['ids'] ?? []);
$deleted = delete_jobs($ids);
flash('success', $deleted === 1 ? '1 job deleted.' : "$deleted jobs deleted.");

if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
    header('Content-Type: application/json');
    echo json_encode(['deleted' => $deleted]);
    exit;
}
redirect('dashboard.php');
