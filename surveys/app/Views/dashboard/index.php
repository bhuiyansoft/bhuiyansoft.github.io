<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, <?= esc(session('user_name')) ?>!</p>
    </div>
    <a href="/surveys/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>New Survey
    </a>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary-soft">
                <i class="bi bi-clipboard-check text-primary"></i>
            </div>
            <div class="stat-value"><?= number_format($stats['total_surveys']) ?></div>
            <div class="stat-label">Total Surveys</div>
            <div class="stat-change text-muted"><?= $stats['published'] ?> published · <?= $stats['draft'] ?> draft</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-success-soft">
                <i class="bi bi-chat-left-text text-success"></i>
            </div>
            <div class="stat-value"><?= number_format($stats['total_responses']) ?></div>
            <div class="stat-label">Total Responses</div>
            <div class="stat-change text-muted">All time</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-warning-soft">
                <i class="bi bi-broadcast text-warning"></i>
            </div>
            <div class="stat-value"><?= $stats['published'] ?></div>
            <div class="stat-label">Active Surveys</div>
            <div class="stat-change text-muted">Currently accepting responses</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-info-soft">
                <i class="bi bi-shield-check text-info"></i>
            </div>
            <div class="stat-value"><?= ucfirst($plan['name']) ?></div>
            <div class="stat-label">Current Plan</div>
            <div class="stat-change">
                <a href="/billing" class="text-primary small">Upgrade <i class="bi bi-arrow-up-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Response Trend Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Response Trend</h5>
                <span class="badge bg-light text-muted">Last 7 days</span>
            </div>
            <div class="card-body">
                <canvas id="responseTrendChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Plan Usage -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Plan Usage</h5>
            </div>
            <div class="card-body">
                <div class="usage-item mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="usage-label">Surveys</span>
                        <span class="usage-count"><?= $stats['total_surveys'] ?> / <?= $plan['max_surveys'] < 0 ? '∞' : $plan['max_surveys'] ?></span>
                    </div>
                    <?php $surveyPct = $plan['max_surveys'] > 0 ? min(100, ($stats['total_surveys'] / $plan['max_surveys']) * 100) : 0; ?>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar <?= $surveyPct > 80 ? 'bg-danger' : 'bg-primary' ?>" style="width: <?= $surveyPct ?>%"></div>
                    </div>
                </div>

                <div class="usage-item mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="usage-label">Team Members</span>
                        <span class="usage-count">1 / <?= $plan['max_team_members'] < 0 ? '∞' : $plan['max_team_members'] ?></span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 10%"></div>
                    </div>
                </div>

                <div class="plan-features mt-4">
                    <div class="fw-semibold mb-2">Plan Features</div>
                    <?php $features = $plan_features ?? []; ?>
                    <?php foreach (['basic_analytics' => 'Basic Analytics', 'export_csv' => 'CSV Export', 'custom_branding' => 'Custom Branding', 'skip_logic' => 'Skip Logic', 'api_access' => 'API Access'] as $key => $label): ?>
                    <div class="feature-check <?= in_array($key, $features) ? '' : 'disabled' ?>">
                        <i class="bi <?= in_array($key, $features) ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' ?>"></i>
                        <?= $label ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($plan['slug'] !== 'enterprise'): ?>
                <a href="/billing" class="btn btn-outline-primary btn-sm w-100 mt-3">Upgrade Plan</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Surveys -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Recent Surveys</h5>
        <a href="/surveys" class="btn btn-outline-secondary btn-sm">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Survey</th>
                    <th>Status</th>
                    <th>Responses</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recent_surveys)): ?>
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="empty-state-sm">
                            <i class="bi bi-clipboard-plus fs-2 text-muted"></i>
                            <p class="mt-2 mb-0">No surveys yet. <a href="/surveys/create">Create your first survey</a></p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($recent_surveys as $survey): ?>
                <tr>
                    <td>
                        <div class="fw-medium"><?= esc($survey['title']) ?></div>
                        <div class="text-muted small"><?= $survey['question_count'] ?? 0 ?> questions</div>
                    </td>
                    <td><?= view('_partials/status_badge', ['status' => $survey['status']]) ?></td>
                    <td><?= number_format($survey['response_count'] ?? 0) ?></td>
                    <td class="text-muted"><?= date('M j, Y', strtotime($survey['created_at'])) ?></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="/surveys/<?= $survey['id'] ?>/edit" class="btn btn-outline-secondary">Edit</a>
                            <a href="/surveys/<?= $survey['id'] ?>/reports" class="btn btn-outline-secondary">Reports</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const trendData = <?= json_encode(array_values($response_trend ?? [])) ?>;
const trendLabels = <?= json_encode(array_map(fn($d) => date('M j', strtotime($d)), array_keys($response_trend ?? []))) ?>;

new Chart(document.getElementById('responseTrendChart'), {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [{
            label: 'Responses',
            data: trendData,
            borderColor: '#4F46E5',
            backgroundColor: 'rgba(79, 70, 229, 0.08)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#4F46E5',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(0,0,0,0.04)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
<?= $this->endSection() ?>
