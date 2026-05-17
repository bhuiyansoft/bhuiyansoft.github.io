<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Response Details</h1>
        <p class="page-subtitle"><?= esc($survey['title']) ?></p>
    </div>
    <a href="/surveys/<?= $survey['id'] ?>/responses" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Responses
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Answers</h5>
            </div>
            <div class="card-body">
                <?php if (empty($response['answers'])): ?>
                <p class="text-muted">No answers recorded.</p>
                <?php else: ?>
                <?php foreach ($response['answers'] as $i => $answer): ?>
                <div class="mb-4 <?= $i < count($response['answers']) - 1 ? 'pb-4 border-bottom' : '' ?>">
                    <div class="text-muted small mb-1"><?= ucwords(str_replace('_', ' ', $answer['question_type'])) ?></div>
                    <div class="fw-semibold mb-2"><?= esc($answer['question_title']) ?></div>
                    <div class="answer-value">
                        <?php
                        $value = $answer['answer_value'];
                        $values = $answer['answer_values'] ? json_decode($answer['answer_values'], true) : null;
                        ?>
                        <?php if ($values): ?>
                            <?php foreach ($values as $v): ?>
                            <span class="badge bg-primary-soft text-primary me-1"><?= esc($v) ?></span>
                            <?php endforeach; ?>
                        <?php elseif ($value): ?>
                            <span class="answer-text"><?= esc($value) ?></span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Response Info</h5></div>
            <div class="card-body">
                <div class="info-item">
                    <div class="info-label">Submitted</div>
                    <div class="info-value"><?= date('M j, Y g:i A', strtotime($response['completed_at'])) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">IP Address</div>
                    <div class="info-value font-monospace"><?= esc($response['respondent_ip'] ?? '—') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Time Taken</div>
                    <div class="info-value">
                        <?php if ($response['time_taken']): ?>
                        <?= $response['time_taken'] > 60 ? round($response['time_taken']/60, 1) . ' minutes' : $response['time_taken'] . ' seconds' ?>
                        <?php else: ?>—<?php endif; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value"><span class="badge bg-success">Complete</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
