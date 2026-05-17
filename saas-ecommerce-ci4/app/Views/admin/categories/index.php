<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Categories</h4>
            <p class="text-muted mb-0">Organize your product catalog</p>
        </div>
        <a href="<?= site_url('admin/categories/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Category
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3">Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th class="text-end px-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No categories yet.</td></tr>
                    <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td class="px-3 fw-semibold"><?= esc($cat['name']) ?></td>
                        <td><code class="small"><?= esc($cat['slug']) ?></code></td>
                        <td><span class="badge bg-primary-subtle text-primary"><?= $cat['product_count'] ?></span></td>
                        <td>
                            <?php if ($cat['is_active']): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary border">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?= $cat['sort_order'] ?></td>
                        <td class="text-end px-3">
                            <div class="btn-group btn-group-sm">
                                <a href="<?= site_url('admin/categories/' . $cat['id'] . '/edit') ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= site_url('admin/categories/' . $cat['id'] . '/delete') ?>"
                                   class="btn btn-outline-danger"
                                   onclick="return confirm('Delete this category?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
