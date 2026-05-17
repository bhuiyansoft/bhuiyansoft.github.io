<?= $this->extend('layouts/superadmin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Tenant</h4>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= site_url('superadmin/tenants') ?>">Tenants</a></li>
                <li class="breadcrumb-item active"><?= esc($tenant['name']) ?></li>
            </ol>
        </div>
        <a href="<?= site_url('superadmin/tenants') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="post" action="<?= site_url('superadmin/tenants/' . $tenant['id'] . '/update') ?>">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Store Name</label>
                                <input type="text" name="name" class="form-control" value="<?= esc(old('name', $tenant['name'])) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control bg-light" value="<?= esc($tenant['email']) ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subdomain</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light" value="<?= esc($tenant['subdomain']) ?>" disabled>
                                    <span class="input-group-text text-muted">.app</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?= esc(old('phone', $tenant['phone'])) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Plan</label>
                                <select name="plan_id" class="form-select">
                                    <?php foreach ($plans as $plan): ?>
                                        <option value="<?= $plan['id'] ?>" <?= old('plan_id', $tenant['plan_id']) == $plan['id'] ? 'selected' : '' ?>>
                                            <?= esc($plan['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <?php foreach (['active','trial','suspended','cancelled'] as $s): ?>
                                        <option value="<?= $s ?>" <?= old('status', $tenant['status']) === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Custom Domain</label>
                                <input type="text" name="custom_domain" class="form-control"
                                       value="<?= esc(old('custom_domain', $tenant['custom_domain'])) ?>"
                                       placeholder="store.example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" class="form-control" step="0.01"
                                       value="<?= old('tax_rate', $tenant['tax_rate']) ?>">
                            </div>
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-lg me-1"></i>Update Tenant
                                    </button>
                                    <a href="<?= site_url('superadmin/tenants') ?>" class="btn btn-outline-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
