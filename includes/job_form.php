<?php
/**
 * Shared create/edit form.
 * Expects: $job (array), $errors (array), $mode ('new'|'edit'), $meta (array|null for edit info).
 */
declare(strict_types=1);

$err = fn(string $f) => isset($errors[$f]) ? ' is-invalid' : '';
$msg = fn(string $f) => isset($errors[$f]) ? '<div class="invalid-feedback d-block">' . e($errors[$f]) . '</div>' : '';
$svpOptions = JOB_SVP_OPTIONS;
if ($job['svp_others'] !== '' && !in_array($job['svp_others'], $svpOptions, true)) {
    $svpOptions[] = $job['svp_others']; // keep legacy values selectable
}
$devOptions = array_values(array_unique(array_merge(JOB_DEVELOPERS, $job['developer'])));
$statusKeys = array_keys(JOB_STATUSES);
$currentIndex = array_search($job['status'], $statusKeys, true);
?>
<?php if ($errors): ?>
    <div class="error-summary p-3 mb-4 d-flex gap-3 align-items-start" role="alert">
        <i class="bi bi-exclamation-octagon-fill text-danger fs-5"></i>
        <div>
            <strong>Please fix <?= count($errors) ?> issue<?= count($errors) > 1 ? 's' : '' ?> before saving.</strong>
            <ul class="mb-0 mt-1 small ps-3">
                <?php foreach ($errors as $m): ?><li><?= e($m) ?></li><?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<form method="post" id="jobForm" novalidate data-md-rate="<?= MD_RATE ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="developer_existing" value="<?= e(implode(',', $job['developer'])) ?>">

    <div class="row g-4">
        <div class="col-xl-8 d-flex flex-column gap-4">

            <!-- Overview -->
            <section class="card-x">
                <div class="card-x-head">
                    <div class="d-flex align-items-center gap-3">
                        <span class="section-icon"><i class="bi bi-info-circle"></i></span>
                        <div><h2>Overview</h2><p>What is being enhanced and who is building it</p></div>
                    </div>
                </div>
                <div class="card-x-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="project_system">Project / System<span class="req">*</span></label>
                            <input class="form-control<?= $err('project_system') ?>" id="project_system" name="project_system" maxlength="100" required value="<?= e($job['project_system']) ?>" placeholder="e.g. Customer Portal">
                            <?= $msg('project_system') ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="iris">IRIS</label>
                            <input class="form-control" id="iris" name="iris" maxlength="100" value="<?= e($job['iris']) ?>" placeholder="e.g. C34251">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="brief_description">Brief description<span class="req">*</span></label>
                            <textarea class="form-control<?= $err('brief_description') ?>" id="brief_description" name="brief_description" rows="3" maxlength="100" required placeholder="Describe the enhancement in a sentence or two"><?= e($job['brief_description']) ?></textarea>
                            <div class="form-text text-end"><span data-count-for="brief_description">0</span>/100</div>
                            <?= $msg('brief_description') ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="workstream">Workstream</label>
                            <input class="form-control" id="workstream" name="workstream" maxlength="100" value="<?= e($job['workstream']) ?>" placeholder="Workstream">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="ws_code">WS code</label>
                            <input class="form-control" id="ws_code" name="ws_code" maxlength="100" value="<?= e($job['ws_code']) ?>" placeholder="e.g. 1a">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label" for="svp_others">SVP / Others<span class="req">*</span></label>
                            <select class="form-select<?= $err('svp_others') ?>" id="svp_others" name="svp_others" required>
                                <option value="">Choose…</option>
                                <?php foreach ($svpOptions as $opt): ?>
                                    <option value="<?= e($opt) ?>"<?= $job['svp_others'] === $opt ? ' selected' : '' ?>><?= e($opt) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= $msg('svp_others') ?>
                        </div>
                        <div class="col-md-8">
                            <span class="form-label d-block" id="dev-label">Developer<span class="req">*</span></span>
                            <div class="pill-check<?= $err('developer') ?>" role="group" aria-labelledby="dev-label" id="developerGroup">
                                <?php foreach ($devOptions as $i => $dev): ?>
                                    <input type="checkbox" id="dev<?= $i ?>" name="developer[]" value="<?= e($dev) ?>"<?= in_array($dev, $job['developer'], true) ? ' checked' : '' ?>>
                                    <label for="dev<?= $i ?>"><i class="bi bi-check-lg"></i><?= e($dev) ?></label>
                                <?php endforeach; ?>
                            </div>
                            <?= $msg('developer') ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="mot">MOT</label>
                            <input class="form-control" id="mot" name="mot" maxlength="100" value="<?= e($job['mot']) ?>" placeholder="e.g. MOT11">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Status & timeline -->
            <section class="card-x">
                <div class="card-x-head">
                    <div class="d-flex align-items-center gap-3">
                        <span class="section-icon"><i class="bi bi-signpost-split"></i></span>
                        <div><h2>Status &amp; timeline</h2><p>Pick the current stage and key delivery dates</p></div>
                    </div>
                </div>
                <div class="card-x-body">
                    <span class="form-label d-block" id="status-label">Status<span class="req">*</span></span>
                    <div class="stepper<?= $err('status') ?>" role="radiogroup" aria-labelledby="status-label" id="statusStepper">
                        <?php foreach (JOB_STATUSES as $status => $cfg): $idx = array_search($status, $statusKeys, true); ?>
                            <input type="radio" name="status" id="st<?= $idx ?>" value="<?= e($status) ?>"<?= $job['status'] === $status ? ' checked' : '' ?> required>
                            <label for="st<?= $idx ?>" style="--status:<?= e($cfg['color']) ?>" class="<?= $currentIndex !== false && $idx < $currentIndex ? 'done' : '' ?>">
                                <span class="step-no"><?= $idx + 1 ?></span>
                                <i class="bi <?= e($cfg['icon']) ?>"></i><?= e($status) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?= $msg('status') ?>

                    <div class="row g-3 mt-2">
                        <?php foreach (JOB_DATE_FIELDS as $field => $label): ?>
                            <div class="col-sm-6 col-lg-4">
                                <label class="form-label" for="<?= $field ?>"><?= e($label) ?></label>
                                <input type="date" class="form-control<?= $err($field) ?>" id="<?= $field ?>" name="<?= $field ?>" value="<?= e($job[$field]) ?>">
                                <?php if ($field === 'actual_systemrfs'): ?><div class="form-text"><i class="bi bi-info-circle"></i> Required once Deployed</div><?php endif; ?>
                                <?php if ($field === 'rollout'): ?><div class="form-text"><i class="bi bi-info-circle"></i> Required once Completed</div><?php endif; ?>
                                <?= $msg($field) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- BE cost -->
            <section class="card-x">
                <div class="card-x-head">
                    <div class="d-flex align-items-center gap-3">
                        <span class="section-icon"><i class="bi bi-cash-coin"></i></span>
                        <div><h2>BE cost</h2><p>Internal cost is calculated at RM <?= number_format(MD_RATE) ?> per man-day</p></div>
                    </div>
                </div>
                <div class="card-x-body cost-grid">
                    <?php foreach (['git' => ['GIT', '#8b1e3f'], 'gnt' => ['GNT', '#c9935a']] as $key => [$team, $tone]): ?>
                        <div class="cost-row" style="--tone:<?= $tone ?>">
                            <div class="team"><span></span><?= $team ?></div>
                            <div>
                                <label class="form-label" for="<?= $key ?>_internalmd">Internal MD</label>
                                <input type="number" step="0.5" min="0" inputmode="decimal" class="form-control<?= $err($key . '_internalmd') ?>" id="<?= $key ?>_internalmd" name="<?= $key ?>_internalmd" value="<?= e($job[$key . '_internalmd']) ?>" placeholder="0" data-cost-md="<?= $key ?>">
                                <?= $msg($key . '_internalmd') ?>
                            </div>
                            <div>
                                <label class="form-label" for="rm_<?= $key ?>_internal">RM internal</label>
                                <div class="input-group"><span class="input-group-text">RM</span>
                                    <input class="form-control" id="rm_<?= $key ?>_internal" value="<?= e(money($job['rm_' . $key . '_internal'])) ?>" readonly tabindex="-1">
                                </div>
                            </div>
                            <div>
                                <label class="form-label" for="<?= $key ?>_externalmd">External MD</label>
                                <input type="number" step="0.5" min="0" inputmode="decimal" class="form-control<?= $err($key . '_externalmd') ?>" id="<?= $key ?>_externalmd" name="<?= $key ?>_externalmd" value="<?= e($job[$key . '_externalmd']) ?>" placeholder="0">
                                <?= $msg($key . '_externalmd') ?>
                            </div>
                            <div>
                                <label class="form-label" for="rm_<?= $key ?>_external">RM external +6%</label>
                                <div class="input-group"><span class="input-group-text">RM</span>
                                    <input type="number" step="0.01" min="0" inputmode="decimal" class="form-control<?= $err('rm_' . $key . '_external') ?>" id="rm_<?= $key ?>_external" name="rm_<?= $key ?>_external" value="<?= e($job['rm_' . $key . '_external']) ?>" placeholder="0.00" data-cost-ext>
                                </div>
                                <?= $msg('rm_' . $key . '_external') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Remarks -->
            <section class="card-x">
                <div class="card-x-head">
                    <div class="d-flex align-items-center gap-3">
                        <span class="section-icon"><i class="bi bi-chat-left-text"></i></span>
                        <div><h2>Remarks</h2><p>Anything else the team should know</p></div>
                    </div>
                </div>
                <div class="card-x-body">
                    <textarea class="form-control" id="remark" name="remark" rows="3" maxlength="100" placeholder="Optional notes" aria-label="Remark"><?= e($job['remark']) ?></textarea>
                    <div class="form-text text-end"><span data-count-for="remark">0</span>/100</div>
                </div>
            </section>
        </div>

        <!-- Summary -->
        <div class="col-xl-4">
            <aside class="card-x summary-card">
                <div class="card-x-body">
                    <span class="eyebrow"><i class="bi bi-receipt"></i> Cost summary</span>
                    <div class="summary-total mb-3">RM <span id="sumTotal">0.00</span></div>
                    <div class="summary-line"><span>GIT internal</span><span>RM <span id="sumGitInt">0.00</span></span></div>
                    <div class="summary-line"><span>GIT external</span><span>RM <span id="sumGitExt">0.00</span></span></div>
                    <div class="summary-line"><span>GNT internal</span><span>RM <span id="sumGntInt">0.00</span></span></div>
                    <div class="summary-line"><span>GNT external</span><span>RM <span id="sumGntExt">0.00</span></span></div>

                    <hr class="my-4">
                    <span class="eyebrow"><i class="bi bi-activity"></i> Current stage</span>
                    <div id="sumStatus" class="mt-1"><?= $job['status'] ? status_badge($job['status']) : '<span class="text-muted small">Not selected yet</span>' ?></div>

                    <?php if (!empty($meta)): ?>
                        <hr class="my-4">
                        <div class="summary-line"><span>Registered by</span><span class="text-capitalize"><?= e($meta['registered_by']) ?></span></div>
                        <div class="summary-line"><span>Created</span><span><?= e(date('j M Y, g:i a', strtotime($meta['last_update_timestamp']))) ?></span></div>
                        <?php if (!empty($meta['last_update_by'])): ?>
                            <div class="summary-line"><span>Last updated</span><span><?= e(date('j M Y, g:i a', strtotime($meta['last_update_by']))) ?></span></div>
                        <?php endif; ?>
                        <?php if (!empty($meta['last_update_by_user'])): ?>
                            <div class="summary-line"><span>Updated by</span><span class="text-capitalize"><?= e($meta['last_update_by_user']) ?></span></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>

    <div class="card-x save-bar">
        <span class="text-muted small d-none d-sm-inline"><i class="bi bi-asterisk text-danger" style="font-size:.6rem"></i> Required fields</span>
        <div class="d-flex gap-2 ms-auto">
            <a href="dashboard.php" class="btn btn-light-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i><?= $mode === 'edit' ? 'Save changes' : 'Create job' ?></button>
        </div>
    </div>
</form>
