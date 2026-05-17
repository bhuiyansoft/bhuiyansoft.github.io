<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Responses</h1>
        <p class="page-subtitle"><?= esc($survey['title']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="/surveys/<?= $survey['id'] ?>/reports" class="btn btn-outline-secondary">
            <i class="bi bi-bar-chart me-2"></i>Analytics
        </a>
        <a href="/surveys/<?= $survey['id'] ?>/reports/export/csv" class="btn btn-outline-secondary">
            <i class="bi bi-download me-2"></i>Export CSV
        </a>
        <a href="/surveys/<?= $survey['id'] ?>/edit" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<!-- Analytics summary -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card-sm">
            <div class="stat-sm-value"><?= number_format($analytics['total_responses']) ?></div>
            <div class="stat-sm-label">Complete Responses</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card-sm">
            <div class="stat-sm-value"><?= $analytics['completion_rate'] ?>%</div>
            <div class="stat-sm-label">Completion Rate</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card-sm">
            <?php $sec = $analytics['avg_time_seconds']; ?>
            <div class="stat-sm-value"><?= $sec > 60 ? round($sec/60, 1) . 'm' : $sec . 's' ?></div>
            <div class="stat-sm-label">Avg. Time to Complete</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Submitted</th>
                    <th>IP Address</th>
                    <th>Time Taken</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($responses)): ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No responses yet. Share your survey to start collecting responses.
                        </div>
                        <a href="/surveys/<?= $survey['id'] ?>/share" class="btn btn-primary btn-sm mt-2">Share Survey</a>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($responses as $i => $response): ?>
                <tr>
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td><?= date('M j, Y g:i A', strtotime($response['completed_at'])) ?></td>
                    <td class="text-muted font-monospace small"><?= esc($response['respondent_ip'] ?? '—') ?></td>
                    <td>
                        <?php if ($response['time_taken']): ?>
                        <?= $response['time_taken'] > 60 ? round($response['time_taken']/60, 1) . 'm' : $response['time_taken'] . 's' ?>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-success-soft text-success">Complete</span>
                    </td>
                    <td>
                        <a href="/surveys/<?= $survey['id'] ?>/responses/<?= $response['id'] ?>" class="btn btn-sm btn-outline-secondary">
                            View
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($pager) && $pager): ?>
    <div class="card-footer d-flex justify-content-center">
        <?= $pager->links() ?>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
