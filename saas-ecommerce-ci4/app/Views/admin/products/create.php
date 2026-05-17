<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Add New Product</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= site_url('admin/products') ?>">Products</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>
        </div>
        <a href="<?= site_url('admin/products') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <form method="post" action="<?= site_url('admin/products/store') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <!-- Main Content -->
            <div class="col-12 col-xl-8">
                <!-- Basic Info -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Basic Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required placeholder="Enter product name">
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku" class="form-control" value="<?= old('sku') ?>" placeholder="Auto-generated if empty">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Unit</label>
                                <select name="unit" class="form-select">
                                    <?php foreach (['pcs', 'kg', 'g', 'litre', 'ml', 'box', 'set', 'pair', 'meter'] as $u): ?>
                                        <option value="<?= $u ?>" <?= old('unit') === $u ? 'selected' : '' ?>><?= strtoupper($u) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Short Description</label>
                            <input type="text" name="short_description" class="form-control" value="<?= old('short_description') ?>" placeholder="Brief product description (shown in cards)" maxlength="500">
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Full Description</label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Detailed product description..."><?= old('description') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Pricing</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Regular Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= esc($currentTenant['currency_symbol']) ?></span>
                                    <input type="number" name="price" class="form-control" step="0.01" min="0" value="<?= old('price') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= esc($currentTenant['currency_symbol']) ?></span>
                                    <input type="number" name="sale_price" class="form-control" step="0.01" min="0" value="<?= old('sale_price') ?>" placeholder="Optional">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cost Price</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= esc($currentTenant['currency_symbol']) ?></span>
                                    <input type="number" name="cost_price" class="form-control" step="0.01" min="0" value="<?= old('cost_price') ?>" placeholder="Optional">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Inventory</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" min="0" value="<?= old('stock', 0) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Low Stock Alert</label>
                                <input type="number" name="low_stock_alert" class="form-control" min="0" value="<?= old('low_stock_alert', 5) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" name="weight" class="form-control" step="0.001" min="0" value="<?= old('weight') ?>" placeholder="Optional">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">SEO</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="<?= old('meta_title') ?>" maxlength="300">
                        </div>
                        <div>
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2" maxlength="500"><?= old('meta_description') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-xl-4">
                <!-- Status -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Status & Visibility</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="draft" <?= old('status') === 'draft' ? 'selected' : '' ?>>Draft</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Product Type</label>
                            <select name="type" class="form-select">
                                <option value="physical">Physical</option>
                                <option value="digital">Digital</option>
                                <option value="service">Service</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1"
                                   <?= old('is_featured') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_featured">
                                <i class="bi bi-star-fill text-warning me-1"></i>Featured Product
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Category</div>
                    <div class="card-body">
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <a href="<?= site_url('admin/categories/create') ?>" class="btn btn-link btn-sm p-0 mt-2">+ Add new category</a>
                    </div>
                </div>

                <!-- Product Image -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Product Image</div>
                    <div class="card-body">
                        <div class="image-upload-area" id="image-drop-zone">
                            <div class="text-center py-3" id="image-placeholder">
                                <i class="bi bi-cloud-arrow-up fs-2 text-muted"></i>
                                <p class="text-muted small mb-0">Click or drag to upload image</p>
                                <p class="text-muted" style="font-size:.75rem">JPG, PNG, WEBP (max 2MB)</p>
                            </div>
                            <img id="image-preview" src="" alt="" class="img-fluid rounded d-none">
                        </div>
                        <input type="file" name="image" id="product-image" accept="image/*" class="d-none" onchange="previewImage(this)">
                        <button type="button" class="btn btn-outline-secondary w-100 btn-sm mt-2" onclick="document.getElementById('product-image').click()">
                            <i class="bi bi-upload me-1"></i>Choose Image
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-lg me-1"></i>Save Product
                    </button>
                    <a href="<?= site_url('admin/products') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('image-preview').classList.remove('d-none');
            document.getElementById('image-placeholder').classList.add('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
document.getElementById('image-drop-zone').addEventListener('click', () => {
    document.getElementById('product-image').click();
});
</script>
<?= $this->endSection() ?>
