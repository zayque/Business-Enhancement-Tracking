<?php
declare(strict_types=1);

/*
 * Job (business enhancement) domain: options, validation, persistence.
 * Records live in the `user` table (name kept for compatibility with the original schema).
 */

const MD_RATE = 800; // RM per internal man-day

const JOB_STATUSES = [
    'Initiate'    => ['color' => '#64748b', 'icon' => 'bi-flag'],
    'Requirement' => ['color' => '#0ea5e9', 'icon' => 'bi-card-checklist'],
    'Assessment'  => ['color' => '#f59e0b', 'icon' => 'bi-search'],
    'Build'       => ['color' => '#8b5cf6', 'icon' => 'bi-hammer'],
    'Testing'     => ['color' => '#ec4899', 'icon' => 'bi-bug'],
    'Deployed'    => ['color' => '#14b8a6', 'icon' => 'bi-rocket-takeoff'],
    'Completed'   => ['color' => '#22c55e', 'icon' => 'bi-check2-circle'],
];

const JOB_DEVELOPERS = ['GITD', 'GNT', 'NRO-Megat', 'NRO-Ain'];

const JOB_SVP_OPTIONS = [
    'SVP2-Waiters Clearence',
    'SVP3 - Accelerate Service Installation',
    'SVP10 - NDDigital',
    'NMO',
];

const JOB_DATE_FIELDS = [
    'be_submission'           => 'BE Submission',
    'assessment_completed'    => 'Assessment Completion',
    'target_systemrfs'        => 'Target System RFS',
    'revise_target_systemrfs' => 'Revised Target System RFS',
    'actual_systemrfs'        => 'Actual System RFSI',
    'rollout'                 => 'Target Rollout Date',
];

/** Every column the form writes. */
const JOB_COLUMNS = [
    'workstream', 'ws_code', 'developer', 'svp_others', 'iris', 'project_system',
    'brief_description', 'status', 'be_submission', 'assessment_completed',
    'target_systemrfs', 'revise_target_systemrfs', 'actual_systemrfs', 'rollout', 'mot',
    'git_internalmd', 'git_externalmd', 'rm_git_internal', 'rm_git_external',
    'gnt_internalmd', 'gnt_externalmd', 'rm_gnt_internal', 'rm_gnt_external', 'remark',
];

function blank_job(): array
{
    $job = array_fill_keys(JOB_COLUMNS, '');
    $job['developer'] = [];
    return $job;
}

function developers_list(string|array $value): array
{
    if (is_array($value)) {
        return array_values(array_filter(array_map('trim', $value), 'strlen'));
    }
    return array_values(array_filter(array_map('trim', explode(',', $value)), 'strlen'));
}

function to_number(mixed $value): float
{
    $clean = str_replace([',', ' ', 'RM'], '', (string) $value);
    return is_numeric($clean) ? (float) $clean : 0.0;
}

function money(mixed $value): string
{
    return number_format(to_number($value), 2);
}

/** Build a normalised job array from POST input. */
function job_from_input(array $in): array
{
    $job = blank_job();
    foreach (JOB_COLUMNS as $col) {
        if ($col === 'developer') {
            continue;
        }
        $job[$col] = trim(mb_substr((string) ($in[$col] ?? ''), 0, 100));
    }
    $job['developer'] = array_values(array_intersect(
        developers_list((array) ($in['developer'] ?? [])),
        array_merge(JOB_DEVELOPERS, developers_list((string) ($in['developer_existing'] ?? '')))
    ));

    foreach (array_keys(JOB_DATE_FIELDS) as $field) {
        if ($job[$field] !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $job[$field])) {
            $job[$field] = '';
        }
    }

    // Internal cost is derived from man-days; never trust the client value.
    $job['rm_git_internal'] = number_format(to_number($job['git_internalmd']) * MD_RATE, 2, '.', '');
    $job['rm_gnt_internal'] = number_format(to_number($job['gnt_internalmd']) * MD_RATE, 2, '.', '');

    return $job;
}

function validate_job(array $job): array
{
    $errors = [];
    $required = [
        'project_system'    => 'Project / System is required.',
        'brief_description' => 'Brief description is required.',
        'svp_others'        => 'Please choose an SVP / Others option.',
    ];
    foreach ($required as $field => $msg) {
        if ($job[$field] === '') {
            $errors[$field] = $msg;
        }
    }
    if (!array_key_exists($job['status'], JOB_STATUSES)) {
        $errors['status'] = 'Please choose a status.';
    }
    if (count($job['developer']) === 0) {
        $errors['developer'] = 'Select at least one developer.';
    }
    if ($job['status'] === 'Deployed' && $job['actual_systemrfs'] === '') {
        $errors['actual_systemrfs'] = 'Required when status is Deployed.';
    }
    if ($job['status'] === 'Completed') {
        foreach (['rollout' => 'Target rollout date', 'git_internalmd' => 'GIT internal MD',
                  'gnt_internalmd' => 'GNT internal MD', 'gnt_externalmd' => 'GNT external MD'] as $field => $label) {
            if ($job[$field] === '') {
                $errors[$field] = "$label is required when status is Completed.";
            }
        }
    }
    foreach (['git_internalmd', 'git_externalmd', 'rm_git_external', 'gnt_internalmd',
              'gnt_externalmd', 'rm_gnt_external'] as $field) {
        if ($job[$field] !== '' && !is_numeric(str_replace(',', '', $job[$field]))) {
            $errors[$field] = 'Must be a number.';
        }
    }
    return $errors;
}

function job_db_values(array $job): array
{
    $values = [];
    foreach (JOB_COLUMNS as $col) {
        $values[$col] = $col === 'developer' ? implode(',', $job['developer']) : $job[$col];
    }
    return $values;
}

function create_job(array $job, string $userName): int
{
    $values = job_db_values($job) + ['registered_by' => $userName, 'last_update_by_user' => $userName];
    $cols = array_keys($values);
    $sql = sprintf(
        'INSERT INTO `user` (%s) VALUES (%s)',
        implode(',', array_map(fn($c) => "`$c`", $cols)),
        implode(',', array_map(fn($c) => ":$c", $cols))
    );
    db()->prepare($sql)->execute($values);
    return (int) db()->lastInsertId();
}

function update_job(int $id, array $job, string $userName): void
{
    $values = job_db_values($job) + ['last_update_by_user' => $userName];
    $sets = implode(',', array_map(fn($c) => "`$c` = :$c", array_keys($values)));
    $values['sysid'] = $id;
    db()->prepare("UPDATE `user` SET $sets WHERE sysid = :sysid")->execute($values);
}

function find_job(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM `user` WHERE sysid = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }
    $row['developer'] = developers_list($row['developer']);
    return $row;
}

function delete_jobs(array $ids): int
{
    $ids = array_values(array_unique(array_filter(array_map('intval', $ids), fn($id) => $id > 0)));
    if (!$ids) {
        return 0;
    }
    $marks = implode(',', array_fill(0, count($ids), '?'));
    $stmt = db()->prepare("DELETE FROM `user` WHERE sysid IN ($marks)");
    $stmt->execute($ids);
    return $stmt->rowCount();
}

function all_jobs(): array
{
    return db()->query('SELECT * FROM `user` ORDER BY sysid DESC')->fetchAll();
}

function job_total_cost(array $row): float
{
    return to_number($row['rm_git_internal']) + to_number($row['rm_git_external'])
         + to_number($row['rm_gnt_internal']) + to_number($row['rm_gnt_external']);
}

function status_color(string $status): string
{
    return JOB_STATUSES[$status]['color'] ?? '#94a3b8';
}

function status_badge(string $status): string
{
    $icon = JOB_STATUSES[$status]['icon'] ?? 'bi-circle';
    return sprintf(
        '<span class="status-badge" style="--status:%s"><i class="bi %s"></i>%s</span>',
        e(status_color($status)), e($icon), e($status ?: 'Unknown')
    );
}
