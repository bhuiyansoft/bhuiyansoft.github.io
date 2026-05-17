<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Analytics Report</h1>
        <p class="page-subtitle"><?= esc($survey['title']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="/surveys/<?= $survey['id'] ?>/reports/export/csv" class="btn btn-outline-secondary">
            <i class="bi bi-download me-2"></i>Export CSV
        </a>
        <a href="/surveys/<?= $survey['id'] ?>/responses" class="btn btn-outline-secondary">
            <i class="bi bi-list-ul me-2"></i>All Responses
        </a>
        <a href="/surveys/<?= $survey['id'] ?>/edit" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<!-- Summary Stats -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary-soft"><i class="bi bi-chat-left-text text-primary"></i></div>
            <div class="stat-value"><?= number_format($analytics['total_responses']) ?></div>
            <div class="stat-label">Total Responses</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-success-soft"><i class="bi bi-percent text-success"></i></div>
            <div class="stat-value"><?= $analytics['completion_rate'] ?>%</div>
            <div class="stat-label">Completion Rate</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-warning-soft"><i class="bi bi-clock text-warning"></i></div>
            <div class="stat-value">
                <?php
                $sec = $analytics['avg_time_seconds'];
                echo $sec > 60 ? round($sec/60, 1) . 'm' : $sec . 's';
                ?>
            </div>
            <div class="stat-label">Avg. Time</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-info-soft"><i class="bi bi-half text-info"></i></div>
            <div class="stat-value"><?= number_format($analytics['partial_responses']) ?></div>
            <div class="stat-label">Partial Responses</div>
        </div>
    </div>
</div>

<!-- Response Trend -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Responses Over Time</h5>
        <span class="text-muted small">Last 30 days</span>
    </div>
    <div class="card-body">
        <canvas id="trendChart" height="80"></canvas>
    </div>
</div>

<!-- Question Stats -->
<?php foreach ($question_stats as $stat): ?>
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="text-muted small mb-1"><?= ucwords(str_replace('_', ' ', $stat['question_type'])) ?></div>
                <h5 class="card-title mb-0"><?= esc($stat['question_title']) ?></h5>
            </div>
            <div class="badge bg-secondary-soft text-secondary"><?= $stat['answer_count'] ?> responses</div>
        </div>
    </div>
    <div class="card-body">
        <?php if (in_array($stat['question_type'], ['single_choice', 'multiple_choice', 'dropdown', 'yes_no'])): ?>
            <?php
            $total = array_sum($stat['data']);
            arsort($stat['data']);
            ?>
            <?php foreach ($stat['data'] as $label => $count): ?>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span><?= esc($label) ?></span>
                    <span class="text-muted"><?= $count ?> (<?= $total ? round($count/$total*100) : 0 ?>%)</span>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar" style="width: <?= $total ? round($count/$total*100) : 0 ?>%; background: #4F46E5"></div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($stat['data'])): ?>
            <p class="text-muted mb-0">No responses yet.</p>
            <?php endif; ?>

        <?php elseif (in_array($stat['question_type'], ['rating', 'nps'])): ?>
            <div class="row">
                <div class="col-md-6">
                    <canvas id="chart-<?= $stat['question_id'] ?>" height="200"></canvas>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="text-center w-100">
                        <div style="font-size: 3rem; font-weight: 700; color: #4F46E5">
                            <?= $stat['average'] ?? '-' ?>
                        </div>
                        <div class="text-muted">Average score</div>
                        <?php if ($stat['question_type'] === 'nps'): ?>
                        <?php
                        $promoters = array_sum(array_filter($stat['data'], fn($v, $k) => $k >= 9, ARRAY_FILTER_USE_BOTH));
                        $detractors = array_sum(array_filter($stat['data'], fn($v, $k) => $k <= 6, ARRAY_FILTER_USE_BOTH));
                        $total = $stat['answer_count'];
                        $nps = $total ? round((($promoters - $detractors) / $total) * 100) : 0;
                        ?>
                        <div class="mt-2">
                            <span class="badge bg-<?= $nps >= 50 ? 'success' : ($nps >= 0 ? 'warning' : 'danger') ?> fs-6">
                                NPS: <?= $nps ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <script>
            new Chart(document.getElementById('chart-<?= $stat['question_id'] ?>'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_keys($stat['data'])) ?>,
                    datasets: [{ label: 'Responses', data: <?= json_encode(array_values($stat['data'])) ?>,
                        backgroundColor: '#4F46E5', borderRadius: 4 }]
                },
                options: { responsive: true, plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } } }
            });
            </script>

        <?php else: ?>
            <?php $texts = array_slice((array)$stat['data'], 0, 10); ?>
            <?php if (empty($texts)): ?>
            <p class="text-muted mb-0">No responses yet.</p>
            <?php else: ?>
            <div class="text-responses">
                <?php foreach ($texts as $text): ?>
                <div class="text-response-item"><?= esc($text) ?></div>
                <?php endforeach; ?>
                <?php if (count((array)$stat['data']) > 10): ?>
                <p class="text-muted small mt-2"><?= count((array)$stat['data']) - 10 ?> more responses. Export CSV to see all.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const dailyData = <?= json_encode($analytics['daily_responses']) ?>;
const labels = dailyData.map(d => new Date(d.date).toLocaleDateString('en', {month:'short', day:'numeric'}));
const values = dailyData.map(d => d.count);

new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Responses',
            data: values,
            borderColor: '#4F46E5',
            backgroundColor: 'rgba(79,70,229,0.08)',
            fill: true,
            tension: 0.4,
            pointRadius: 3
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(0,0,0,.04)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
<?= $this->endSection() ?>
