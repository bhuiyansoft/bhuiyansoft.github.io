<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Create Survey</h1>
        <p class="page-subtitle">Set up your new survey</p>
    </div>
    <a href="/surveys" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Surveys
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="/surveys/create" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Survey Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg"
                            value="<?= esc(old('title')) ?>"
                            placeholder="e.g. Customer Satisfaction Survey Q1 2024" required autofocus>
                        <div class="form-text">Choose a clear, descriptive title for your survey</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Brief description of what this survey is about..."><?= esc(old('description')) ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Survey Color</label>
                        <div class="color-picker-row">
                            <?php $colors = ['#4F46E5', '#0EA5E9', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6']; ?>
                            <?php foreach ($colors as $color): ?>
                            <label class="color-swatch">
                                <input type="radio" name="primary_color" value="<?= $color ?>" <?= $color === '#4F46E5' ? 'checked' : '' ?>>
                                <span style="background: <?= $color ?>"></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Anonymous Responses</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_anonymous" value="1" checked>
                            <label class="form-check-label">Collect responses anonymously (no personal data)</label>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right me-2"></i>Create & Add Questions
                        </button>
                        <a href="/surveys" class="btn btn-outline-secondary btn-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Templates -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-grid me-2"></i>Or start from a template</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php
                    $templates = [
                        ['icon' => 'emoji-smile', 'color' => '#10B981', 'name' => 'Customer Satisfaction', 'desc' => 'CSAT survey with NPS'],
                        ['icon' => 'briefcase', 'color' => '#4F46E5', 'name' => 'Employee Feedback', 'desc' => 'Engagement & satisfaction'],
                        ['icon' => 'star', 'color' => '#F59E0B', 'name' => 'Product Review', 'desc' => 'Rating & feedback form'],
                        ['icon' => 'mortarboard', 'color' => '#8B5CF6', 'name' => 'Training Evaluation', 'desc' => 'Course feedback form'],
                        ['icon' => 'graph-up', 'color' => '#0EA5E9', 'name' => 'Market Research', 'desc' => 'Consumer insights survey'],
                        ['icon' => 'calendar-event', 'color' => '#EC4899', 'name' => 'Event Feedback', 'desc' => 'Post-event evaluation'],
                    ];
                    foreach ($templates as $t):
                    ?>
                    <div class="col-md-4">
                        <div class="template-card" style="--tc: <?= $t['color'] ?>">
                            <div class="template-icon">
                                <i class="bi bi-<?= $t['icon'] ?>"></i>
                            </div>
                            <div class="template-name"><?= $t['name'] ?></div>
                            <div class="template-desc"><?= $t['desc'] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-muted small mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>Templates coming soon in the Pro plan</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
