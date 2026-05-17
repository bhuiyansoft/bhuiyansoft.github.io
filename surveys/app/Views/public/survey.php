<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($survey['title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/survey-public.css" rel="stylesheet">
    <style>
        :root { --brand-color: <?= esc($survey['primary_color'] ?? '#4F46E5') ?>; }
    </style>
</head>
<body class="survey-body">

<?php if ($is_preview ?? false): ?>
<div class="preview-banner">
    <i class="bi bi-eye me-2"></i>Preview Mode — Responses are not recorded
    <a href="/surveys/<?= $survey['id'] ?>/edit" class="btn btn-sm btn-light ms-3">Exit Preview</a>
</div>
<?php endif; ?>

<div class="survey-wrapper">
    <div class="survey-container">
        <!-- Survey Header -->
        <div class="survey-header" style="background: linear-gradient(135deg, var(--brand-color), var(--brand-color)cc)">
            <h1 class="survey-title"><?= esc($survey['title']) ?></h1>
            <?php if ($survey['description']): ?>
            <p class="survey-description"><?= esc($survey['description']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Progress Bar -->
        <?php if ($survey['show_progress_bar']): ?>
        <div class="survey-progress">
            <div class="progress-bar-track">
                <div class="progress-bar-fill" id="progressBar" style="width: 0%; background: var(--brand-color)"></div>
            </div>
            <div class="progress-text"><span id="progressText">0</span> of <?= count($questions) ?> answered</div>
        </div>
        <?php endif; ?>

        <!-- Survey Form -->
        <form action="<?= ($is_preview ?? false) ? '#' : '/s/' . $survey['slug'] ?>" method="POST" id="surveyForm" class="survey-form">
            <?= ($is_preview ?? false) ? '' : csrf_field() ?>

            <?php if (session()->has('validation_errors')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>Please answer all required questions.
            </div>
            <?php endif; ?>

            <?php foreach ($questions as $index => $question): ?>
            <?php
            $qNum = $index + 1;
            $key = 'q_' . $question['id'];
            $hasError = isset(session('validation_errors')[$question['id']]);
            $oldValue = old($key);
            ?>
            <div class="question-block <?= $hasError ? 'question-error' : '' ?>" id="q-block-<?= $question['id'] ?>">
                <div class="question-number">
                    <?php if ($survey['show_question_numbers']): ?>
                    <span class="q-num"><?= $qNum ?></span>
                    <?php endif; ?>
                    <?php if ($question['is_required']): ?>
                    <span class="required-star">*</span>
                    <?php endif; ?>
                </div>
                <div class="question-content">
                    <div class="question-title"><?= esc($question['title']) ?></div>
                    <?php if ($question['description']): ?>
                    <div class="question-description"><?= esc($question['description']) ?></div>
                    <?php endif; ?>

                    <div class="question-input mt-3">
                        <?php switch ($question['type']):
                            case 'short_text': ?>
                            <input type="text" name="<?= $key ?>" class="form-control survey-input"
                                placeholder="<?= esc($question['placeholder'] ?? 'Your answer...') ?>"
                                value="<?= esc($oldValue) ?>"
                                <?= $question['is_required'] ? 'required' : '' ?>>
                        <?php break; case 'long_text': ?>
                            <textarea name="<?= $key ?>" class="form-control survey-input" rows="4"
                                placeholder="<?= esc($question['placeholder'] ?? 'Your answer...') ?>"
                                <?= $question['is_required'] ? 'required' : '' ?>><?= esc($oldValue) ?></textarea>
                        <?php break; case 'email': ?>
                            <input type="email" name="<?= $key ?>" class="form-control survey-input"
                                placeholder="your@email.com" value="<?= esc($oldValue) ?>"
                                <?= $question['is_required'] ? 'required' : '' ?>>
                        <?php break; case 'number': ?>
                            <input type="number" name="<?= $key ?>" class="form-control survey-input"
                                placeholder="0" value="<?= esc($oldValue) ?>"
                                <?= $question['is_required'] ? 'required' : '' ?>>
                        <?php break; case 'date': ?>
                            <input type="date" name="<?= $key ?>" class="form-control survey-input"
                                value="<?= esc($oldValue) ?>"
                                <?= $question['is_required'] ? 'required' : '' ?>>
                        <?php break; case 'single_choice': ?>
                            <div class="choice-list">
                                <?php foreach ($question['options'] as $opt): ?>
                                <label class="choice-item">
                                    <input type="radio" name="<?= $key ?>" value="<?= esc($opt['label']) ?>"
                                        <?= $oldValue === $opt['label'] ? 'checked' : '' ?>
                                        <?= $question['is_required'] ? 'required' : '' ?>>
                                    <span class="choice-check"></span>
                                    <span class="choice-label"><?= esc($opt['label']) ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        <?php break; case 'multiple_choice': ?>
                            <div class="choice-list">
                                <?php foreach ($question['options'] as $opt): ?>
                                <label class="choice-item">
                                    <input type="checkbox" name="<?= $key ?>[]" value="<?= esc($opt['label']) ?>"
                                        <?= is_array($oldValue) && in_array($opt['label'], $oldValue) ? 'checked' : '' ?>>
                                    <span class="choice-check checkbox"></span>
                                    <span class="choice-label"><?= esc($opt['label']) ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        <?php break; case 'dropdown': ?>
                            <select name="<?= $key ?>" class="form-select survey-input"
                                <?= $question['is_required'] ? 'required' : '' ?>>
                                <option value="">Select an option...</option>
                                <?php foreach ($question['options'] as $opt): ?>
                                <option value="<?= esc($opt['label']) ?>" <?= $oldValue === $opt['label'] ? 'selected' : '' ?>>
                                    <?= esc($opt['label']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        <?php break; case 'yes_no': ?>
                            <div class="yes-no-group">
                                <label class="yes-no-btn">
                                    <input type="radio" name="<?= $key ?>" value="Yes" <?= $oldValue === 'Yes' ? 'checked' : '' ?> <?= $question['is_required'] ? 'required' : '' ?>>
                                    <span><i class="bi bi-hand-thumbs-up me-2"></i>Yes</span>
                                </label>
                                <label class="yes-no-btn">
                                    <input type="radio" name="<?= $key ?>" value="No" <?= $oldValue === 'No' ? 'checked' : '' ?>>
                                    <span><i class="bi bi-hand-thumbs-down me-2"></i>No</span>
                                </label>
                            </div>
                        <?php break; case 'rating': ?>
                            <?php
                            $settings = $question['settings'] ?? [];
                            $min = $settings['min'] ?? 1;
                            $max = $settings['max'] ?? 5;
                            ?>
                            <div class="rating-group" data-name="<?= $key ?>">
                                <?php for ($i = $min; $i <= $max; $i++): ?>
                                <label class="rating-btn">
                                    <input type="radio" name="<?= $key ?>" value="<?= $i ?>" <?= $oldValue == $i ? 'checked' : '' ?> <?= $question['is_required'] ? 'required' : '' ?>>
                                    <span><?= $i ?></span>
                                </label>
                                <?php endfor; ?>
                            </div>
                            <div class="d-flex justify-content-between text-muted small mt-1">
                                <span><?= $min ?> — Not at all likely</span>
                                <span>Extremely likely — <?= $max ?></span>
                            </div>
                        <?php break; case 'nps': ?>
                            <div class="nps-group" data-name="<?= $key ?>">
                                <?php for ($i = 0; $i <= 10; $i++): ?>
                                <label class="nps-btn">
                                    <input type="radio" name="<?= $key ?>" value="<?= $i ?>" <?= $oldValue == $i ? 'checked' : '' ?> <?= $question['is_required'] ? 'required' : '' ?>>
                                    <span><?= $i ?></span>
                                </label>
                                <?php endfor; ?>
                            </div>
                            <div class="d-flex justify-content-between text-muted small mt-1">
                                <span>0 — Not likely</span>
                                <span>Very likely — 10</span>
                            </div>
                        <?php break; endswitch; ?>
                    </div>

                    <?php if ($hasError): ?>
                    <div class="error-msg"><i class="bi bi-exclamation-circle me-1"></i>This question requires an answer.</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (!($is_preview ?? false)): ?>
            <div class="survey-submit">
                <button type="submit" class="btn-submit" style="background: var(--brand-color)">
                    <i class="bi bi-send me-2"></i>Submit Survey
                </button>
            </div>
            <?php endif; ?>
        </form>

        <div class="survey-footer">
            <div class="powered-by">
                Powered by <strong>SurveyStack</strong>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Progress tracking
const totalQuestions = <?= count($questions) ?>;
function updateProgress() {
    let answered = 0;
    document.querySelectorAll('.question-block').forEach(block => {
        const inputs = block.querySelectorAll('input, select, textarea');
        for (const input of inputs) {
            if ((input.type === 'radio' || input.type === 'checkbox') && input.checked) { answered++; break; }
            else if (input.type !== 'radio' && input.type !== 'checkbox' && input.value.trim()) { answered++; break; }
        }
    });
    const pct = Math.round((answered / totalQuestions) * 100);
    const bar = document.getElementById('progressBar');
    if (bar) { bar.style.width = pct + '%'; document.getElementById('progressText').textContent = answered; }
}

document.querySelectorAll('input, select, textarea').forEach(el => {
    el.addEventListener('change', updateProgress);
    el.addEventListener('input', updateProgress);
});

// Rating/NPS visual feedback
document.querySelectorAll('.rating-group, .nps-group').forEach(group => {
    group.querySelectorAll('label').forEach(label => {
        label.addEventListener('click', function() {
            group.querySelectorAll('label').forEach(l => l.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
});
</script>
</body>
</html>
