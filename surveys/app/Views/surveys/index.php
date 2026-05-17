<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Surveys</h1>
        <p class="page-subtitle">Manage and track all your surveys</p>
    </div>
    <a href="/surveys/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>New Survey
    </a>
</div>

<!-- Filter tabs -->
<div class="filter-tabs mb-4">
    <a href="/surveys" class="filter-tab <?= !$current_status ? 'active' : '' ?>">All</a>
    <a href="/surveys?status=published" class="filter-tab <?= $current_status === 'published' ? 'active' : '' ?>">
        <i class="bi bi-broadcast me-1"></i>Published
    </a>
    <a href="/surveys?status=draft" class="filter-tab <?= $current_status === 'draft' ? 'active' : '' ?>">
        <i class="bi bi-pencil me-1"></i>Draft
    </a>
    <a href="/surveys?status=closed" class="filter-tab <?= $current_status === 'closed' ? 'active' : '' ?>">
        <i class="bi bi-stop-circle me-1"></i>Closed
    </a>
</div>

<?php if (empty($surveys)): ?>
<div class="empty-state">
    <div class="empty-icon"><i class="bi bi-clipboard-plus"></i></div>
    <h3>No surveys yet</h3>
    <p>Create your first survey to start collecting responses from your audience.</p>
    <a href="/surveys/create" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-lg me-2"></i>Create Survey
    </a>
</div>

<?php else: ?>
<div class="row g-4">
    <?php foreach ($surveys as $survey): ?>
    <div class="col-lg-6 col-xl-4">
        <div class="survey-card">
            <div class="survey-card-header" style="background: linear-gradient(135deg, <?= esc($survey['primary_color'] ?? '#4F46E5') ?>, <?= esc($survey['primary_color'] ?? '#4F46E5') ?>aa)">
                <div class="survey-card-status">
                    <?= view('_partials/status_badge', ['status' => $survey['status']]) ?>
                </div>
            </div>
            <div class="survey-card-body">
                <h3 class="survey-card-title"><?= esc($survey['title']) ?></h3>
                <?php if ($survey['description']): ?>
                <p class="survey-card-desc"><?= esc(substr($survey['description'], 0, 80)) ?><?= strlen($survey['description']) > 80 ? '…' : '' ?></p>
                <?php endif; ?>

                <div class="survey-meta">
                    <div class="meta-item">
                        <i class="bi bi-question-circle"></i>
                        <?= $survey['question_count'] ?? 0 ?> questions
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-chat-left-text"></i>
                        <?= number_format($survey['response_count'] ?? 0) ?> responses
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-calendar3"></i>
                        <?= date('M j', strtotime($survey['created_at'])) ?>
                    </div>
                </div>
            </div>
            <div class="survey-card-footer">
                <div class="survey-actions">
                    <a href="/surveys/<?= $survey['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <?php if ($survey['status'] === 'published'): ?>
                    <a href="/s/<?= $survey['slug'] ?>" target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-box-arrow-up-right"></i> Open
                    </a>
                    <?php endif; ?>
                    <a href="/surveys/<?= $survey['id'] ?>/reports" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-bar-chart"></i> Reports
                    </a>
                    <div class="dropdown ms-auto">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/surveys/<?= $survey['id'] ?>/share">
                                <i class="bi bi-share me-2"></i>Share</a></li>
                            <li><a class="dropdown-item" href="/surveys/<?= $survey['id'] ?>/responses">
                                <i class="bi bi-list-ul me-2"></i>Responses</a></li>
                            <li><a class="dropdown-item" href="/surveys/<?= $survey['id'] ?>/preview" target="_blank">
                                <i class="bi bi-eye me-2"></i>Preview</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if ($survey['status'] === 'draft'): ?>
                            <li>
                                <form action="/surveys/<?= $survey['id'] ?>/publish" method="POST">
                                    <?= csrf_field() ?>
                                    <button class="dropdown-item text-success">
                                        <i class="bi bi-broadcast me-2"></i>Publish
                                    </button>
                                </form>
                            </li>
                            <?php elseif ($survey['status'] === 'published'): ?>
                            <li>
                                <form action="/surveys/<?= $survey['id'] ?>/close" method="POST">
                                    <?= csrf_field() ?>
                                    <button class="dropdown-item text-warning">
                                        <i class="bi bi-stop-circle me-2"></i>Close
                                    </button>
                                </form>
                            </li>
                            <?php endif; ?>
                            <li>
                                <form action="/surveys/<?= $survey['id'] ?>/delete" method="POST"
                                    onsubmit="return confirm('Delete this survey and all its responses?')">
                                    <?= csrf_field() ?>
                                    <button class="dropdown-item text-danger">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
