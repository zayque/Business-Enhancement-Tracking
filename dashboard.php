<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/jobs.php';
require __DIR__ . '/includes/layout.php';

$user = require_login();
$jobs = all_jobs();

$statusCounts = array_fill_keys(array_keys(JOB_STATUSES), 0);
$devCounts = array_fill_keys(JOB_DEVELOPERS, 0);
$totalCost = 0.0;
$rows = [];

foreach ($jobs as $i => $job) {
    $status = $job['status'];
    $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
    $devs = developers_list($job['developer']);
    foreach ($devs as $dev) {
        $devCounts[$dev] = ($devCounts[$dev] ?? 0) + 1;
    }
    $cost = job_total_cost($job);
    $totalCost += $cost;

    $rows[] = $job + [
        'no'         => count($jobs) - $i,
        'devs'       => $devs,
        'total_cost' => $cost,
        'color'      => status_color($status),
        'icon'       => JOB_STATUSES[$status]['icon'] ?? 'bi-circle',
    ];
}

$total = count($jobs);
$completed = $statusCounts['Completed'] ?? 0;
$deployed = $statusCounts['Deployed'] ?? 0;
$active = $total - $completed;
$completionRate = $total ? round($completed / $total * 100) : 0;

$hour = (int) date('G');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
$firstName = ucfirst(strtok($user['fullname'], ' ') ?: $user['fullname']);

$chartData = [
    'status' => [
        'labels' => array_keys($statusCounts),
        'values' => array_values($statusCounts),
        'colors' => array_map('status_color', array_keys($statusCounts)),
    ],
    'developers' => ['labels' => array_keys($devCounts), 'values' => array_values($devCounts)],
];
$jsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;

layout_start(
    'Dashboard',
    'dashboard',
    '<link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">'
);
?>
<div class="page-head">
    <div>
        <span class="eyebrow"><i class="bi bi-calendar3"></i> <?= e(date('l, j F Y')) ?></span>
        <h1><?= e($greeting) ?>, <?= e($firstName) ?> 👋</h1>
        <p>Here's what's happening across your business enhancements.</p>
    </div>
    <a href="job_new.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> New job</a>
</div>

<div class="row g-3 g-xl-4 mb-4">
    <div class="col-sm-6 col-xl-3 fade-up">
        <div class="card-x kpi kpi-hero">
            <div class="kpi-top"><span class="kpi-label">Total BE cost</span><span class="kpi-icon"><i class="bi bi-wallet2"></i></span></div>
            <div class="kpi-value"><small>RM</small><?= e(number_format($totalCost, 2)) ?></div>
            <div class="kpi-foot">Internal + external, all jobs</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 fade-up">
        <div class="card-x kpi" style="--tone:#8b1e3f">
            <div class="kpi-top"><span class="kpi-label">Total jobs</span><span class="kpi-icon"><i class="bi bi-collection"></i></span></div>
            <div class="kpi-value"><?= $total ?></div>
            <div class="kpi-foot">Registered enhancements</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 fade-up">
        <div class="card-x kpi" style="--tone:#f59e0b">
            <div class="kpi-top"><span class="kpi-label">In progress</span><span class="kpi-icon"><i class="bi bi-lightning-charge"></i></span></div>
            <div class="kpi-value"><?= $active ?></div>
            <div class="kpi-foot"><?= $deployed ?> deployed, awaiting completion</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 fade-up">
        <div class="card-x kpi" style="--tone:#22c55e">
            <div class="kpi-top"><span class="kpi-label">Completed</span><span class="kpi-icon"><i class="bi bi-patch-check"></i></span></div>
            <div class="kpi-value"><?= $completed ?></div>
            <div class="kpi-foot"><?= $completionRate ?>% completion rate</div>
        </div>
    </div>
</div>

<div class="row g-3 g-xl-4 mb-4">
    <div class="col-xl-5">
        <div class="card-x h-100">
            <div class="card-x-head">
                <div><h2>Pipeline by status</h2><p>Where every job currently sits</p></div>
            </div>
            <div class="card-x-body">
                <div class="row g-4 align-items-center">
                    <div class="col-sm-6">
                        <div class="chart-wrap" style="height:210px">
                            <canvas id="statusChart" aria-label="Jobs by status chart" role="img"></canvas>
                            <div class="donut-center"><div><strong><?= $total ?></strong><span>jobs</span></div></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <ul class="legend-list">
                            <?php foreach ($statusCounts as $status => $count): ?>
                                <li style="--status:<?= e(status_color($status)) ?>">
                                    <span class="dot"></span>
                                    <span style="width:88px"><?= e($status) ?></span>
                                    <span class="bar"><span style="width:<?= $total ? round($count / $total * 100) : 0 ?>%"></span></span>
                                    <span class="num"><?= $count ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-7">
        <div class="card-x h-100">
            <div class="card-x-head">
                <div><h2>Workload by developer</h2><p>Jobs assigned to each delivery team</p></div>
            </div>
            <div class="card-x-body">
                <div class="chart-wrap"><canvas id="devChart" aria-label="Jobs by developer chart" role="img"></canvas></div>
            </div>
        </div>
    </div>
</div>

<div class="card-x">
    <div class="card-x-head">
        <div class="d-flex align-items-center gap-3">
            <span class="section-icon"><i class="bi bi-table"></i></span>
            <div><h2>All jobs</h2><p>Click a row's arrow to see full details</p></div>
        </div>
        <div class="filter-chips" id="statusFilter">
            <button type="button" class="filter-chip active" data-status="">All <span class="count"><?= $total ?></span></button>
            <?php foreach ($statusCounts as $status => $count): if (!$count) continue; ?>
                <button type="button" class="filter-chip" data-status="<?= e($status) ?>" style="--status:<?= e(status_color($status)) ?>">
                    <span class="dot"></span><?= e($status) ?> <span class="count"><?= $count ?></span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (!$jobs): ?>
        <div class="empty-state">
            <span class="section-icon"><i class="bi bi-inbox"></i></span>
            <h3 class="h5">No jobs yet</h3>
            <p class="text-muted">Create your first business enhancement to start tracking.</p>
            <a href="job_new.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> New job</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table id="jobsTable" class="table table-x w-100">
                <thead>
                <tr>
                    <th style="width:32px"><input class="form-check-input" type="checkbox" id="checkAll" aria-label="Select all"></th>
                    <th style="width:36px"></th>
                    <th>No</th>
                    <th>Project / System</th>
                    <th>Workstream</th>
                    <th>Developer</th>
                    <th>Status</th>
                    <th>Target RFS</th>
                    <th class="text-end">Total cost (RM)</th>
                    <th>Updated</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="bulk-bar" id="bulkBar" role="region" aria-label="Bulk actions">
    <span><strong id="bulkCount">0</strong> selected</span>
    <button type="button" class="btn btn-sm btn-light" id="bulkClear">Clear</button>
    <button type="button" class="btn btn-sm btn-danger" id="bulkDelete"><i class="bi bi-trash3 me-1"></i>Delete</button>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 p-2">
            <div class="modal-body text-center p-4">
                <span class="section-icon mb-3" style="width:56px;height:56px;font-size:1.5rem;background:rgba(239,68,68,.1);color:#ef4444"><i class="bi bi-trash3"></i></span>
                <h3 class="h5">Delete <span id="confirmCount">0</span> job(s)?</h3>
                <p class="text-muted mb-4">This action can't be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes, delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/json" id="jobsData"><?= json_encode($rows, $jsonFlags) ?></script>
<script type="application/json" id="chartData"><?= json_encode($chartData, $jsonFlags) ?></script>
<?php
layout_end(
    '<meta name="csrf-token" content="' . e(csrf_token()) . '">'
    . '<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>'
    . '<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>'
    . '<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>'
    . '<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.js"></script>'
    . '<script src="' . e(asset('js/dashboard.js')) . '"></script>'
);
