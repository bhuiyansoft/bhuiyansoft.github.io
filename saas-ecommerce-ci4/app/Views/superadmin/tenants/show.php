<?= $this->extend('layouts/superadmin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><?= esc($tenant['name']) ?></h4>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= site_url('superadmin/tenants') ?>">Tenants</a></li>
                <li class="breadcrumb-item active"><?= esc($tenant['name']) ?></li>
            </ol>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('superadmin/tenants/' . $tenant['id'] . '/edit') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <?php if ($tenant['status'] === 'suspended'): ?>
                <a href="<?= site_url('superadmin/tenants/' . $tenant['id'] . '/activate') ?>" class="btn btn-success btn-sm">
                    <i class="bi bi-check-circle me-1"></i>Activate
                </a>
            <?php else: ?>
                <a href="<?= site_url('superadmin/tenants/' . $tenant['id'] . '/suspend') ?>"
                   class="btn btn-warning btn-sm" onclick="return confirm('Suspend this tenant?')">
                    <i class="bi bi-pause-circle me-1"></i>Suspend
                </a>
            <?php endif; ?>
            <a href="<?= site_url('superadmin/tenants') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-3">
        <!-- Tenant Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-inline-flex align-items-center justify-content-center mb-2"
                             style="width:64px;height:64px;font-size:1.5rem">
                            <?= strtoupper(substr($tenant['name'], 0, 1)) ?>
                        </div>
                        <h5 class="fw-bold mb-0"><?= esc($tenant['name']) ?></h5>
                        <code class="small"><?= esc($tenant['subdomain']) ?>.app</code>
                        <div class="mt-2">
                            <?php $sc = ['active'=>'success','trial'=>'warning','suspended'=>'danger','cancelled'=>'secondary'][$tenant['status']] ?? 'secondary'; ?>
                            <span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> border border-<?= $sc ?>-subtle px-3 py-1">
                                <?= ucfirst($tenant['status']) ?>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Plan</span>
                            <span class="fw-semibold"><?= esc($tenant['plan_name'] ?? 'None') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Email</span>
                            <span><?= esc($tenant['email']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Phone</span>
                            <span><?= esc($tenant['phone'] ?? '—') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Currency</span>
                            <span><?= esc($tenant['currency_symbol']) ?> <?= esc($tenant['currency']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Trial Ends</span>
                            <span><?= $tenant['trial_ends_at'] ? date('d M Y', strtotime($tenant['trial_ends_at'])) : '—' ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Registered</span>
                            <span><?= date('d M Y', strtotime($tenant['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Stats -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Revenue</div>
                <div class="card-body">
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="bg-primary-subtle rounded p-3">
                                <div class="fw-bold text-primary fs-5"><?= number_format($order_stats['total_orders'] ?? 0) ?></div>
                                <div class="text-muted" style="font-size:.72rem">Orders</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success-subtle rounded p-3">
                                <div class="fw-bold text-success">৳<?= number_format($order_stats['total_revenue'] ?? 0, 0) ?></div>
                                <div class="text-muted" style="font-size:.72rem">Revenue</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Users -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Staff Users</div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Last Login</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">No users found.</td></tr>
                            <?php else: ?>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="px-3 fw-semibold"><?= esc($u['name']) ?></td>
                                <td class="small text-muted"><?= esc($u['email']) ?></td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary text-capitalize"><?= esc($u['role']) ?></span>
                                </td>
                                <td class="small text-muted">
                                    <?= $u['last_login_at'] ? date('d M Y', strtotime($u['last_login_at'])) : 'Never' ?>
                                </td>
                                <td>
                                    <?php if ($u['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border">Inactive</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
