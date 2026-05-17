<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Create Coupon</h4>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= site_url('admin/coupons') ?>">Coupons</a></li>
                <li class="breadcrumb-item active">Create New</li>
            </ol>
        </div>
        <a href="<?= site_url('admin/coupons') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="post" action="<?= site_url('admin/coupons/store') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="code" class="form-control text-uppercase fw-bold font-monospace"
                                       value="<?= old('code') ?>" required placeholder="e.g. SAVE20"
                                       style="letter-spacing:.1em" oninput="this.value=this.value.toUpperCase()">
                                <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                    <i class="bi bi-shuffle"></i> Generate
                                </button>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" id="coupon-type" onchange="toggleValueLabel()">
                                    <option value="percentage" <?= old('type') === 'percentage' ? 'selected' : '' ?>>Percentage (%)</option>
                                    <option value="fixed"      <?= old('type') === 'fixed'      ? 'selected' : '' ?>>Fixed Amount</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="value-symbol">%</span>
                                    <input type="number" name="value" class="form-control" step="0.01" min="0"
                                           value="<?= old('value') ?>" required placeholder="e.g. 20">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Minimum Order Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= esc($currentTenant['currency_symbol']) ?></span>
                                    <input type="number" name="min_order" class="form-control" step="0.01" min="0"
                                           value="<?= old('min_order', 0) ?>">
                                </div>
                                <div class="form-text">Leave 0 for no minimum.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Max Uses</label>
                                <input type="number" name="max_uses" class="form-control" min="1"
                                       value="<?= old('max_uses') ?>" placeholder="Unlimited">
                                <div class="form-text">Leave blank for unlimited.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Expiry Date</label>
                            <input type="datetime-local" name="expires_at" class="form-control"
                                   value="<?= old('expires_at') ?>">
                            <div class="form-text">Leave blank for no expiry.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i>Create Coupon
                            </button>
                            <a href="<?= site_url('admin/coupons') ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) code += chars.charAt(Math.floor(Math.random() * chars.length));
    document.querySelector('[name="code"]').value = code;
}
function toggleValueLabel() {
    const type = document.getElementById('coupon-type').value;
    document.getElementById('value-symbol').textContent = type === 'percentage' ? '%' : '<?= esc($currentTenant['currency_symbol']) ?>';
}
</script>
<?= $this->endSection() ?>
