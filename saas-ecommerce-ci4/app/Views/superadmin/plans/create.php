<?= $this->extend('layouts/superadmin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Create Plan</h4>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= site_url('superadmin/plans') ?>">Plans</a></li>
                <li class="breadcrumb-item active">Create New</li>
            </ol>
        </div>
        <a href="<?= site_url('superadmin/plans') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="post" action="<?= site_url('superadmin/plans/store') ?>">
                <?= csrf_field() ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Plan Details</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Plan Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required
                                       placeholder="e.g. Professional">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" class="form-control" value="<?= old('slug') ?>" required
                                       placeholder="e.g. professional" pattern="[a-z0-9\-]+">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" name="price" class="form-control" step="0.01" min="0"
                                           value="<?= old('price', 0) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Billing Cycle</label>
                                <select name="billing_cycle" class="form-select">
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Products</label>
                                <input type="number" name="max_products" class="form-control" value="<?= old('max_products', 100) ?>">
                                <div class="form-text">-1 = Unlimited</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Orders/mo</label>
                                <input type="number" name="max_orders" class="form-control" value="<?= old('max_orders', 1000) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Staff</label>
                                <input type="number" name="max_staff" class="form-control" value="<?= old('max_staff', 5) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Storage (GB)</label>
                                <input type="number" name="storage_gb" class="form-control" value="<?= old('storage_gb', 5) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="<?= old('sort_order', 0) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Features</div>
                    <div class="card-body">
                        <div class="row g-2">
                            <?php
                            $featureList = [
                                'feat_analytics'        => ['Analytics Dashboard', 'bi-graph-up'],
                                'feat_custom_domain'    => ['Custom Domain', 'bi-globe'],
                                'feat_api'              => ['API Access', 'bi-code-slash'],
                                'feat_email_support'    => ['Email Support', 'bi-envelope'],
                                'feat_chat_support'     => ['Chat Support', 'bi-chat-dots'],
                                'feat_dedicated_support'=> ['Dedicated Support', 'bi-headset'],
                            ];
                            foreach ($featureList as $key => [$label, $icon]):
                            ?>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="<?= $key ?>" id="<?= $key ?>"
                                           <?= old($key) ? 'checked' : '' ?>>
                                    <label class="form-check-label small" for="<?= $key ?>">
                                        <i class="bi <?= $icon ?> me-1"></i><?= $label ?>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Create Plan
                    </button>
                    <a href="<?= site_url('superadmin/plans') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
