<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Products</h4>
            <p class="text-muted mb-0">Manage your store's product catalog</p>
        </div>
        <a href="<?= site_url('admin/products/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Product
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="get" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="search" name="search" class="form-control form-control-sm" placeholder="Search by name, SKU..."
                           value="<?= esc($filters['search'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="<?= site_url('admin/products') ?>" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3" style="width:60px">
                                <input type="checkbox" class="form-check-input" id="select-all">
                            </th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Sales</th>
                            <th class="text-end px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted">No products found.</p>
                                    <a href="<?= site_url('admin/products/create') ?>" class="btn btn-primary btn-sm">Add your first product</a>
                                </td>
                            </tr>
                        <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="px-3">
                                <input type="checkbox" class="form-check-input row-check" value="<?= $product['id'] ?>">
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="product-thumb">
                                        <?php if ($product['image']): ?>
                                            <img src="<?= base_url($product['image']) ?>" alt="" class="rounded" width="42" height="42" style="object-fit:cover">
                                        <?php else: ?>
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:42px;height:42px">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?= esc($product['name']) ?></div>
                                        <div class="text-muted small">SKU: <?= esc($product['sku']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= esc($product['category_name'] ?? '—') ?></span></td>
                            <td>
                                <?php if ($product['sale_price']): ?>
                                    <div class="fw-semibold text-success"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($product['sale_price'], 2) ?></div>
                                    <div class="text-muted text-decoration-line-through small"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($product['price'], 2) ?></div>
                                <?php else: ?>
                                    <div class="fw-semibold"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($product['price'], 2) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($product['stock'] <= $product['low_stock_alert']): ?>
                                    <span class="badge bg-danger"><?= $product['stock'] ?></span>
                                <?php elseif ($product['stock'] <= $product['low_stock_alert'] * 2): ?>
                                    <span class="badge bg-warning text-dark"><?= $product['stock'] ?></span>
                                <?php else: ?>
                                    <span class="text-dark"><?= $product['stock'] ?></span>
                                <?php endif; ?>
                                <span class="text-muted small"> <?= esc($product['unit']) ?></span>
                            </td>
                            <td>
                                <?php
                                $statusMap = ['active' => 'success', 'inactive' => 'secondary', 'draft' => 'warning'];
                                $cls = $statusMap[$product['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $cls ?>-subtle text-<?= $cls ?> border border-<?= $cls ?>-subtle">
                                    <?= ucfirst($product['status']) ?>
                                </span>
                                <?php if ($product['is_featured']): ?>
                                    <i class="bi bi-star-fill text-warning ms-1" title="Featured"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small"><?= $product['total_sold'] ?> sold</td>
                            <td class="text-end px-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= site_url('admin/products/' . $product['id'] . '/edit') ?>" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/shop/product/<?= esc($product['slug']) ?>" target="_blank" class="btn btn-outline-secondary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= site_url('admin/products/' . $product['id'] . '/delete') ?>"
                                       class="btn btn-outline-danger"
                                       onclick="return confirm('Delete this product?')" title="Delete">
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
        <?php if (!empty($pager)): ?>
        <div class="card-footer bg-white border-0">
            <?= $pager->links() ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.getElementById('select-all').addEventListener('change', function() {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
});
</script>
<?= $this->endSection() ?>
