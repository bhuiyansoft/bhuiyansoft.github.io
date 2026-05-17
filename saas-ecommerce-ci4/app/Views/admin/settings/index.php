<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Store Settings</h4>
            <p class="text-muted mb-0">Configure your store's information and preferences</p>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <!-- Store Info -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Store Information</div>
                <div class="card-body">
                    <form method="post" action="<?= site_url('admin/settings/update') ?>">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Store Name</label>
                                <input type="text" name="name" class="form-control" value="<?= esc($tenant['name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?= esc($tenant['phone'] ?? '') ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2"><?= esc($tenant['address'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="<?= esc($tenant['city'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" value="<?= esc($tenant['state'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="<?= esc($tenant['country'] ?? 'Bangladesh') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Currency Code</label>
                                <input type="text" name="currency" class="form-control" value="<?= esc($tenant['currency'] ?? 'BDT') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Currency Symbol</label>
                                <input type="text" name="currency_symbol" class="form-control" value="<?= esc($tenant['currency_symbol'] ?? '৳') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" class="form-control" step="0.01" min="0" max="100" value="<?= esc($tenant['tax_rate'] ?? 0) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Flat Shipping Rate</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= esc($tenant['currency_symbol'] ?? '৳') ?></span>
                                    <input type="number" name="shipping_rate" class="form-control" step="0.01" min="0" value="<?= esc($tenant['shipping_rate'] ?? 0) ?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-lg me-1"></i>Save Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Logo Upload -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Store Logo</div>
                <div class="card-body text-center">
                    <?php if ($tenant['logo']): ?>
                        <img src="<?= base_url($tenant['logo']) ?>" alt="Logo" class="img-fluid mb-3 rounded" style="max-height:80px">
                    <?php else: ?>
                        <div class="bg-light rounded p-4 mb-3">
                            <i class="bi bi-image text-muted fs-2"></i>
                            <div class="text-muted small mt-1">No logo uploaded</div>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="<?= site_url('admin/settings/logo') ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="file" name="logo" class="form-control form-control-sm mb-2" accept="image/*">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">Upload Logo</button>
                    </form>
                </div>
            </div>

            <!-- Store Info Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Account Info</div>
                <div class="card-body small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subdomain</span>
                        <code><?= esc($tenant['subdomain']) ?></code>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-<?= $tenant['status'] === 'active' ? 'success' : 'warning' ?>-subtle text-<?= $tenant['status'] === 'active' ? 'success' : 'warning' ?>">
                            <?= ucfirst($tenant['status']) ?>
                        </span>
                    </div>
                    <?php if ($tenant['trial_ends_at']): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Trial Ends</span>
                        <span><?= date('d M Y', strtotime($tenant['trial_ends_at'])) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
