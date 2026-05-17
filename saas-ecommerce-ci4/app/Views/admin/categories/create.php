<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Add Category</h4>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= site_url('admin/categories') ?>">Categories</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </div>
        <a href="<?= site_url('admin/categories') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="post" action="<?= site_url('admin/categories/store') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required
                                   placeholder="e.g. Electronics, Clothing...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" class="form-select">
                                <option value="">None (Top Level)</option>
                                <?php foreach ($categories as $cat): ?>
                                    <?php if (!$cat['parent_id']): ?>
                                    <option value="<?= $cat['id'] ?>" <?= old('parent_id') == $cat['id'] ? 'selected' : '' ?>>
                                        <?= esc($cat['name']) ?>
                                    </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Optional description..."><?= old('description') ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="<?= old('sort_order', 0) ?>" min="0" style="max-width:140px">
                            <div class="form-text">Lower numbers appear first.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i>Save Category
                            </button>
                            <a href="<?= site_url('admin/categories') ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
