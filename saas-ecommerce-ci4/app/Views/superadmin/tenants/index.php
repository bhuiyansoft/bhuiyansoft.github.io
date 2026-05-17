<?= $this->extend('layouts/superadmin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Tenants</h4>
            <p class="text-muted mb-0">Manage all registered stores</p>
        </div>
        <a href="<?= site_url('superadmin/tenants/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Tenant
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="get" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="search" name="search" class="form-control form-control-sm" placeholder="Name, email, subdomain..."
                           value="<?= esc($filters['search'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <?php foreach (['active','trial','suspended','cancelled'] as $s): ?>
                            <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="plan_id" class="form-select form-select-sm">
                        <option value="">All Plans</option>
                        <?php foreach ($plans as $plan): ?>
                            <option value="<?= $plan['id'] ?>" <?= ($filters['plan_id'] ?? '') == $plan['id'] ? 'selected' : '' ?>>
                                <?= esc($plan['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="<?= site_url('superadmin/tenants') ?>" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Store</th>
                            <th>Subdomain</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Trial Ends</th>
                            <th>Joined</th>
                            <th class="text-end px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tenants)): ?>
                            <tr><td colspan="7" class="text-center py-5 text-muted">No tenants found.</td></tr>
                        <?php else: ?>
                        <?php foreach ($tenants as $tenant): ?>
                        <tr>
                            <td class="px-3">
                                <div class="fw-semibold"><?= esc($tenant['name']) ?></div>
                                <div class="text-muted small"><?= esc($tenant['email']) ?></div>
                            </td>
                            <td><code class="small"><?= esc($tenant['subdomain']) ?></code></td>
                            <td>
                                <span class="badge" style="background:<?= ['starter'=>'#6c757d','basic'=>'#0d6efd','professional'=>'#6f42c1','enterprise'=>'#fd7e14'][$tenant['plan_slug'] ?? 'starter'] ?? '#6c757d' ?>">
                                    <?= esc($tenant['plan_name'] ?? 'None') ?>
                                </span>
                            </td>
                            <td>
                                <?php $sc = ['active'=>'success','trial'=>'warning','suspended'=>'danger','cancelled'=>'secondary'][$tenant['status']] ?? 'secondary'; ?>
                                <span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> border border-<?= $sc ?>-subtle">
                                    <?= ucfirst($tenant['status']) ?>
                                </span>
                            </td>
                            <td class="text-muted small">
                                <?= $tenant['trial_ends_at'] ? date('d M Y', strtotime($tenant['trial_ends_at'])) : '—' ?>
                            </td>
                            <td class="text-muted small"><?= date('d M Y', strtotime($tenant['created_at'])) ?></td>
                            <td class="text-end px-3">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="<?= site_url('superadmin/tenants/' . $tenant['id']) ?>"><i class="bi bi-eye me-2"></i>View</a></li>
                                        <li><a class="dropdown-item" href="<?= site_url('superadmin/tenants/' . $tenant['id'] . '/edit') ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <?php if ($tenant['status'] === 'suspended'): ?>
                                            <li><a class="dropdown-item text-success" href="<?= site_url('superadmin/tenants/' . $tenant['id'] . '/activate') ?>"><i class="bi bi-check-circle me-2"></i>Activate</a></li>
                                        <?php else: ?>
                                            <li><a class="dropdown-item text-warning" href="<?= site_url('superadmin/tenants/' . $tenant['id'] . '/suspend') ?>"
                                                   onclick="return confirm('Suspend this tenant?')"><i class="bi bi-pause-circle me-2"></i>Suspend</a></li>
                                        <?php endif; ?>
                                    </ul>
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
        <div class="card-footer bg-white border-0"><?= $pager->links() ?></div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
