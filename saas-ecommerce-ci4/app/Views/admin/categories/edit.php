<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Category</h4>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= site_url('admin/categories') ?>">Categories</a></li>
                <li class="breadcrumb-item active"><?= esc($category['name']) ?></li>
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
                    <form method="post" action="<?= site_url('admin/categories/' . $category['id'] . '/update') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= esc(old('name', $category['name'])) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" class="form-select">
                                <option value="">None (Top Level)</option>
                                <?php foreach ($categories as $cat): ?>
                                    <?php if ($cat['id'] != $category['id'] && !$cat['parent_id']): ?>
                                    <option value="<?= $cat['id'] ?>" <?= old('parent_id', $category['parent_id']) == $cat['id'] ? 'selected' : '' ?>>
                                        <?= esc($cat['name']) ?>
                                    </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= esc(old('description', $category['description'])) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control"
                                   value="<?= old('sort_order', $category['sort_order']) ?>" min="0" style="max-width:140px">
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                                       <?= old('is_active', $category['is_active']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i>Update Category
                            </button>
                            <a href="<?= site_url('admin/categories/' . $category['id'] . '/delete') ?>"
                               class="btn btn-outline-danger"
                               onclick="return confirm('Delete this category?')">
                                <i class="bi bi-trash me-1"></i>Delete
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
