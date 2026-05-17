<?= $this->extend('layouts/superadmin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Subscription Plans</h4>
            <p class="text-muted mb-0">Manage pricing tiers for your SaaS</p>
        </div>
        <a href="<?= site_url('superadmin/plans/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Plan
        </a>
    </div>

    <div class="row g-4">
        <?php foreach ($plans as $plan):
            $features = is_string($plan['features']) ? json_decode($plan['features'], true) : ($plan['features'] ?? []);
        ?>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-0"><?= esc($plan['name']) ?></h5>
                            <code class="small text-muted"><?= esc($plan['slug']) ?></code>
                        </div>
                        <?php if ($plan['is_active']): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary border">Inactive</span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <span class="display-6 fw-bold">
                            <?= $plan['price'] > 0 ? '৳' . number_format($plan['price'], 0) : 'Free' ?>
                        </span>
                        <?php if ($plan['price'] > 0): ?>
                            <span class="text-muted small">/<?= $plan['billing_cycle'] ?></span>
                        <?php endif; ?>
                    </div>

                    <ul class="list-unstyled small text-muted mb-4">
                        <li class="mb-1"><i class="bi bi-box-seam me-2 text-primary"></i>
                            <?= $plan['max_products'] < 0 ? 'Unlimited' : $plan['max_products'] ?> products
                        </li>
                        <li class="mb-1"><i class="bi bi-bag-check me-2 text-primary"></i>
                            <?= $plan['max_orders'] < 0 ? 'Unlimited' : number_format($plan['max_orders']) ?> orders/mo
                        </li>
                        <li class="mb-1"><i class="bi bi-people me-2 text-primary"></i>
                            <?= $plan['max_staff'] < 0 ? 'Unlimited' : $plan['max_staff'] ?> staff
                        </li>
                        <li class="mb-1"><i class="bi bi-hdd me-2 text-primary"></i>
                            <?= $plan['storage_gb'] ?>GB storage
                        </li>
                        <?php if (!empty($features['analytics'])): ?>
                        <li class="mb-1"><i class="bi bi-graph-up me-2 text-success"></i>Analytics</li>
                        <?php endif; ?>
                        <?php if (!empty($features['custom_domain'])): ?>
                        <li class="mb-1"><i class="bi bi-globe me-2 text-success"></i>Custom domain</li>
                        <?php endif; ?>
                        <?php if (!empty($features['api_access'])): ?>
                        <li class="mb-1"><i class="bi bi-code-slash me-2 text-success"></i>API access</li>
                        <?php endif; ?>
                    </ul>

                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="badge bg-primary-subtle text-primary"><?= $plan['tenant_count'] ?> tenants</span>
                        <a href="<?= site_url('superadmin/plans/' . $plan['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
