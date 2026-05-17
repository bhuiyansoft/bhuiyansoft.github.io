<?= $this->extend('layouts/app') ?>
<?= $this->section('styles') ?>
<style>
.builder-layout { display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; }
@media (max-width: 991px) { .builder-layout { grid-template-columns: 1fr; } }
.question-item { background: #fff; border: 2px solid #e5e7eb; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 0.75rem; cursor: grab; transition: all 0.15s; }
.question-item:hover { border-color: #4F46E5; box-shadow: 0 4px 16px rgba(79,70,229,.1); }
.question-item.dragging { opacity: 0.4; }
.question-type-badge { font-size: 11px; background: #f0f0ff; color: #4F46E5; padding: 2px 8px; border-radius: 20px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= esc($survey['title']) ?></h1>
        <div class="d-flex align-items-center gap-2">
            <?= view('_partials/status_badge', ['status' => $survey['status']]) ?>
            <span class="text-muted small"><?= count($questions) ?> questions</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <?php if ($survey['status'] === 'draft'): ?>
        <form action="/surveys/<?= $survey['id'] ?>/publish" method="POST">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-broadcast me-2"></i>Publish
            </button>
        </form>
        <?php elseif ($survey['status'] === 'published'): ?>
        <a href="/s/<?= $survey['slug'] ?>" target="_blank" class="btn btn-success">
            <i class="bi bi-box-arrow-up-right me-2"></i>View Live
        </a>
        <?php endif; ?>
        <a href="/surveys/<?= $survey['id'] ?>/preview" target="_blank" class="btn btn-outline-secondary">
            <i class="bi bi-eye me-2"></i>Preview
        </a>
        <a href="/surveys" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="builder-layout">
    <!-- Questions Panel -->
    <div>
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Questions</h5>
                <button class="btn btn-primary btn-sm" id="addQuestionBtn">
                    <i class="bi bi-plus-lg me-1"></i>Add Question
                </button>
            </div>
            <div class="card-body p-3">
                <div id="questionsList">
                    <?php if (empty($questions)): ?>
                    <div id="emptyState" class="text-center py-5 text-muted">
                        <i class="bi bi-chat-square-text fs-2"></i>
                        <p class="mt-2">No questions yet. Click "Add Question" to get started.</p>
                    </div>
                    <?php endif; ?>

                    <?php foreach ($questions as $q): ?>
                    <div class="question-item" data-id="<?= $q['id'] ?>" draggable="true">
                        <div class="d-flex align-items-start gap-3">
                            <div class="drag-handle text-muted mt-1"><i class="bi bi-grip-vertical"></i></div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="question-type-badge"><?= str_replace('_', ' ', ucfirst($q['type'])) ?></span>
                                    <?php if ($q['is_required']): ?>
                                    <span class="badge bg-danger-soft text-danger" style="font-size:10px">Required</span>
                                    <?php endif; ?>
                                </div>
                                <div class="fw-medium"><?= esc($q['title']) ?></div>
                                <?php if (!empty($q['options'])): ?>
                                <div class="text-muted small"><?= count($q['options']) ?> options</div>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-secondary edit-question-btn" data-question='<?= json_encode($q) ?>'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-question-btn" data-id="<?= $q['id'] ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Panel -->
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-gear me-2"></i>Survey Settings</h5>
            </div>
            <div class="card-body">
                <form action="/surveys/<?= $survey['id'] ?>/edit" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="title" class="form-control" value="<?= esc($survey['title']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2"><?= esc($survey['description']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Thank You Message</label>
                        <textarea name="thank_you_message" class="form-control" rows="2"><?= esc($survey['thank_you_message']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Redirect URL <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="url" name="redirect_url" class="form-control" value="<?= esc($survey['redirect_url']) ?>" placeholder="https://...">
                    </div>

                    <hr>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="show_progress_bar" value="1" <?= $survey['show_progress_bar'] ? 'checked' : '' ?>>
                        <label class="form-check-label">Show progress bar</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="show_question_numbers" value="1" <?= $survey['show_question_numbers'] ? 'checked' : '' ?>>
                        <label class="form-check-label">Show question numbers</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_anonymous" value="1" <?= $survey['is_anonymous'] ? 'checked' : '' ?>>
                        <label class="form-check-label">Anonymous responses</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="allow_multiple_responses" value="1" <?= $survey['allow_multiple_responses'] ? 'checked' : '' ?>>
                        <label class="form-check-label">Allow multiple responses per IP</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-floppy me-2"></i>Save Settings
                    </button>
                </form>

                <hr>
                <div class="d-grid gap-2">
                    <a href="/surveys/<?= $survey['id'] ?>/share" class="btn btn-outline-secondary">
                        <i class="bi bi-share me-2"></i>Share Survey
                    </a>
                    <a href="/surveys/<?= $survey['id'] ?>/responses" class="btn btn-outline-secondary">
                        <i class="bi bi-list-ul me-2"></i>View Responses
                    </a>
                    <a href="/surveys/<?= $survey['id'] ?>/reports" class="btn btn-outline-info">
                        <i class="bi bi-bar-chart me-2"></i>Analytics Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Question Modal -->
<div class="modal fade" id="questionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionModalTitle">Add Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="questionId" value="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Question Type</label>
                        <select id="questionType" class="form-select" onchange="handleTypeChange()">
                            <optgroup label="Text">
                                <option value="short_text">Short Text</option>
                                <option value="long_text">Long Text / Paragraph</option>
                                <option value="email">Email</option>
                                <option value="number">Number</option>
                            </optgroup>
                            <optgroup label="Choice">
                                <option value="single_choice">Single Choice (Radio)</option>
                                <option value="multiple_choice">Multiple Choice (Checkbox)</option>
                                <option value="dropdown">Dropdown</option>
                                <option value="yes_no">Yes / No</option>
                            </optgroup>
                            <optgroup label="Scale">
                                <option value="rating">Rating (1-5 or 1-10)</option>
                                <option value="nps">NPS Score (0-10)</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="date">Date</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="questionRequired">
                            <label class="form-check-label fw-semibold">Required</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Question Text <span class="text-danger">*</span></label>
                        <input type="text" id="questionTitle" class="form-control" placeholder="Enter your question...">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="text" id="questionDesc" class="form-control" placeholder="Additional instructions or context...">
                    </div>

                    <!-- Options section (for choice questions) -->
                    <div class="col-12" id="optionsSection" style="display:none">
                        <label class="form-label fw-semibold">Options</label>
                        <div id="optionsList"></div>
                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="addOption()">
                            <i class="bi bi-plus me-1"></i>Add Option
                        </button>
                    </div>

                    <!-- Rating settings -->
                    <div class="col-12" id="ratingSection" style="display:none">
                        <label class="form-label fw-semibold">Scale</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <select id="ratingMin" class="form-select">
                                    <option value="1">Min: 1</option>
                                    <option value="0">Min: 0</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <select id="ratingMax" class="form-select">
                                    <option value="5">Max: 5</option>
                                    <option value="10">Max: 10</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveQuestionBtn">
                    <i class="bi bi-floppy me-2"></i>Save Question
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const SURVEY_ID = <?= $survey['id'] ?>;
const CSRF_TOKEN = '<?= csrf_hash() ?>';
let questionModal;

document.addEventListener('DOMContentLoaded', function() {
    questionModal = new bootstrap.Modal(document.getElementById('questionModal'));

    document.getElementById('addQuestionBtn').addEventListener('click', openAddModal);
    document.getElementById('saveQuestionBtn').addEventListener('click', saveQuestion);

    document.querySelectorAll('.edit-question-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            openEditModal(JSON.parse(this.dataset.question));
        });
    });

    document.querySelectorAll('.delete-question-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteQuestion(this.dataset.id);
        });
    });

    initDragDrop();
});

function openAddModal() {
    document.getElementById('questionModalTitle').textContent = 'Add Question';
    document.getElementById('questionId').value = '';
    document.getElementById('questionTitle').value = '';
    document.getElementById('questionDesc').value = '';
    document.getElementById('questionType').value = 'short_text';
    document.getElementById('questionRequired').checked = false;
    document.getElementById('optionsList').innerHTML = '';
    handleTypeChange();
    questionModal.show();
}

function openEditModal(question) {
    document.getElementById('questionModalTitle').textContent = 'Edit Question';
    document.getElementById('questionId').value = question.id;
    document.getElementById('questionTitle').value = question.title;
    document.getElementById('questionDesc').value = question.description || '';
    document.getElementById('questionType').value = question.type;
    document.getElementById('questionRequired').checked = !!question.is_required;

    handleTypeChange();

    if (question.options && question.options.length > 0) {
        document.getElementById('optionsList').innerHTML = '';
        question.options.forEach(opt => addOption(opt.label));
    }

    questionModal.show();
}

function handleTypeChange() {
    const type = document.getElementById('questionType').value;
    const choiceTypes = ['single_choice', 'multiple_choice', 'dropdown'];
    const optSec = document.getElementById('optionsSection');
    const ratingSec = document.getElementById('ratingSection');

    optSec.style.display = choiceTypes.includes(type) ? '' : 'none';
    ratingSec.style.display = type === 'rating' ? '' : 'none';

    if (choiceTypes.includes(type) && document.getElementById('optionsList').children.length === 0) {
        addOption('Option 1');
        addOption('Option 2');
    }
}

function addOption(value = '') {
    const list = document.getElementById('optionsList');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <span class="input-group-text"><i class="bi bi-grip-vertical text-muted"></i></span>
        <input type="text" class="form-control option-input" placeholder="Enter option..." value="${value}">
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="bi bi-x"></i>
        </button>`;
    list.appendChild(div);
}

async function saveQuestion() {
    const title = document.getElementById('questionTitle').value.trim();
    if (!title) {
        alert('Question text is required.');
        return;
    }

    const type = document.getElementById('questionType').value;
    const questionId = document.getElementById('questionId').value;

    const choiceTypes = ['single_choice', 'multiple_choice', 'dropdown'];
    let options = [];
    if (choiceTypes.includes(type)) {
        document.querySelectorAll('.option-input').forEach((input, i) => {
            if (input.value.trim()) {
                options.push({ label: input.value.trim(), value: input.value.trim(), sort_order: i });
            }
        });
    }

    const payload = {
        type,
        title,
        description: document.getElementById('questionDesc').value,
        is_required: document.getElementById('questionRequired').checked ? 1 : 0,
        options,
        settings: {}
    };

    if (type === 'rating') {
        payload.settings.min = parseInt(document.getElementById('ratingMin').value);
        payload.settings.max = parseInt(document.getElementById('ratingMax').value);
    }

    const url = questionId
        ? `/surveys/questions/${questionId}`
        : `/surveys/${SURVEY_ID}/questions`;
    const method = questionId ? 'PUT' : 'POST';

    const saveBtn = document.getElementById('saveQuestionBtn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

    try {
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (data.success) {
            questionModal.hide();
            location.reload();
        } else {
            alert(data.error || 'Failed to save question.');
        }
    } catch (e) {
        alert('Network error. Please try again.');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="bi bi-floppy me-2"></i>Save Question';
    }
}

async function deleteQuestion(id) {
    if (!confirm('Delete this question? This cannot be undone.')) return;

    const res = await fetch(`/surveys/questions/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
    });

    const data = await res.json();
    if (data.success) {
        document.querySelector(`.question-item[data-id="${id}"]`)?.remove();
        const list = document.getElementById('questionsList');
        if (!list.querySelector('.question-item')) {
            document.getElementById('emptyState')?.remove();
            list.innerHTML = `<div id="emptyState" class="text-center py-5 text-muted">
                <i class="bi bi-chat-square-text fs-2"></i>
                <p class="mt-2">No questions yet. Click "Add Question" to get started.</p>
            </div>`;
        }
    }
}

function initDragDrop() {
    const list = document.getElementById('questionsList');
    let dragSrc = null;

    list.addEventListener('dragstart', e => {
        dragSrc = e.target.closest('.question-item');
        if (dragSrc) dragSrc.classList.add('dragging');
    });

    list.addEventListener('dragover', e => {
        e.preventDefault();
        const target = e.target.closest('.question-item');
        if (target && target !== dragSrc) {
            const rect = target.getBoundingClientRect();
            const next = e.clientY > rect.top + rect.height / 2;
            list.insertBefore(dragSrc, next ? target.nextSibling : target);
        }
    });

    list.addEventListener('dragend', async () => {
        if (dragSrc) dragSrc.classList.remove('dragging');
        dragSrc = null;
        await saveOrder();
    });
}

async function saveOrder() {
    const items = document.querySelectorAll('.question-item[data-id]');
    const order = {};
    items.forEach((el, i) => { order[el.dataset.id] = i + 1; });

    await fetch(`/surveys/${SURVEY_ID}/questions/reorder`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ order })
    });
}
</script>
<?= $this->endSection() ?>
