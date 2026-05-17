<?= $this->extend('layouts/superadmin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Create Tenant</h4>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= site_url('superadmin/tenants') ?>">Tenants</a></li>
                <li class="breadcrumb-item active">Create New</li>
            </ol>
        </div>
        <a href="<?= site_url('superadmin/tenants') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="post" action="<?= site_url('superadmin/tenants/store') ?>">
                <?= csrf_field() ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Store Information</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Store Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subdomain <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="subdomain" class="form-control" value="<?= old('subdomain') ?>"
                                           required placeholder="mystore" pattern="[a-z0-9\-]+">
                                    <span class="input-group-text text-muted">.app</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?= old('phone') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Plan <span class="text-danger">*</span></label>
                                <select name="plan_id" class="form-select" required>
                                    <option value="">Select Plan</option>
                                    <?php foreach ($plans as $plan): ?>
                                        <option value="<?= $plan['id'] ?>" <?= old('plan_id') == $plan['id'] ? 'selected' : '' ?>>
                                            <?= esc($plan['name']) ?> — <?= $plan['price'] > 0 ? '৳' . number_format($plan['price']) . '/mo' : 'Free' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="trial">Trial</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Admin Account</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Admin Name <span class="text-danger">*</span></label>
                                <input type="text" name="admin_name" class="form-control" value="<?= old('admin_name') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Temporary Password <span class="text-danger">*</span></label>
                                <input type="text" name="admin_pass" class="form-control" value="<?= old('admin_pass') ?>" required minlength="6">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Create Tenant
                    </button>
                    <a href="<?= site_url('superadmin/tenants') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
