<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Product</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= site_url('admin/products') ?>">Products</a></li>
                    <li class="breadcrumb-item active"><?= esc($product['name']) ?></li>
                </ol>
            </nav>
        </div>
        <a href="<?= site_url('admin/products') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <form method="post" action="<?= site_url('admin/products/' . $product['id'] . '/update') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Basic Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= esc(old('name', $product['name'])) ?>" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku" class="form-control" value="<?= esc(old('sku', $product['sku'])) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Unit</label>
                                <select name="unit" class="form-select">
                                    <?php foreach (['pcs','kg','g','litre','ml','box','set','pair','meter'] as $u): ?>
                                        <option value="<?= $u ?>" <?= old('unit', $product['unit']) === $u ? 'selected' : '' ?>><?= strtoupper($u) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Short Description</label>
                            <input type="text" name="short_description" class="form-control" maxlength="500"
                                   value="<?= esc(old('short_description', $product['short_description'])) ?>">
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Full Description</label>
                            <textarea name="description" class="form-control" rows="6"><?= esc(old('description', $product['description'])) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Pricing</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Regular Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= esc($currentTenant['currency_symbol']) ?></span>
                                    <input type="number" name="price" class="form-control" step="0.01" min="0"
                                           value="<?= old('price', $product['price']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= esc($currentTenant['currency_symbol']) ?></span>
                                    <input type="number" name="sale_price" class="form-control" step="0.01" min="0"
                                           value="<?= old('sale_price', $product['sale_price']) ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cost Price</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= esc($currentTenant['currency_symbol']) ?></span>
                                    <input type="number" name="cost_price" class="form-control" step="0.01" min="0"
                                           value="<?= old('cost_price', $product['cost_price']) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Inventory</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" min="0"
                                       value="<?= old('stock', $product['stock']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Low Stock Alert</label>
                                <input type="number" name="low_stock_alert" class="form-control" min="0"
                                       value="<?= old('low_stock_alert', $product['low_stock_alert']) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" name="weight" class="form-control" step="0.001" min="0"
                                       value="<?= old('weight', $product['weight']) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">SEO</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control"
                                   value="<?= esc(old('meta_title', $product['meta_title'])) ?>">
                        </div>
                        <div>
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2"><?= esc(old('meta_description', $product['meta_description'])) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Status & Visibility</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <?php foreach (['active','inactive','draft'] as $s): ?>
                                    <option value="<?= $s ?>" <?= old('status', $product['status']) === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Product Type</label>
                            <select name="type" class="form-select">
                                <?php foreach (['physical','digital','service'] as $t): ?>
                                    <option value="<?= $t ?>" <?= old('type', $product['type']) === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1"
                                   <?= (old('is_featured', $product['is_featured'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_featured">
                                <i class="bi bi-star-fill text-warning me-1"></i>Featured Product
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Category</div>
                    <div class="card-body">
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= old('category_id', $product['category_id']) == $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Product Image</div>
                    <div class="card-body">
                        <?php if ($product['image']): ?>
                            <img src="<?= base_url($product['image']) ?>" id="image-preview" class="img-fluid rounded mb-2">
                        <?php else: ?>
                            <img id="image-preview" src="" class="img-fluid rounded mb-2 d-none">
                            <div class="image-upload-area mb-2" id="image-drop-zone">
                                <div class="text-center py-3" id="image-placeholder">
                                    <i class="bi bi-cloud-arrow-up fs-2 text-muted"></i>
                                    <p class="text-muted small mb-0">No image uploaded</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" id="product-image" accept="image/*" class="d-none" onchange="previewImage(this)">
                        <button type="button" class="btn btn-outline-secondary w-100 btn-sm" onclick="document.getElementById('product-image').click()">
                            <i class="bi bi-upload me-1"></i><?= $product['image'] ? 'Change Image' : 'Upload Image' ?>
                        </button>
                    </div>
                </div>

                <!-- Stats -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Product Stats</div>
                    <div class="card-body small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Sold</span>
                            <span class="fw-semibold"><?= number_format($product['total_sold']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Views</span>
                            <span class="fw-semibold"><?= number_format($product['views']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Added</span>
                            <span><?= date('d M Y', strtotime($product['created_at'])) ?></span>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-lg me-1"></i>Update Product
                    </button>
                    <a href="<?= site_url('admin/products/' . $product['id'] . '/delete') ?>"
                       class="btn btn-outline-danger"
                       onclick="return confirm('Are you sure you want to delete this product?')">
                        <i class="bi bi-trash me-1"></i>Delete Product
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
