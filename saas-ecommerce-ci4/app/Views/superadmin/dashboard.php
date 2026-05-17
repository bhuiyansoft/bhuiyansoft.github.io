<?= $this->extend('layouts/superadmin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Super Admin Dashboard</h4>
            <p class="text-muted mb-0">Platform-wide overview</p>
        </div>
        <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i><?= date('D, d M Y') ?></span>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#ede9fe;color:#7c3aed">
                    <i class="bi bi-buildings fs-4"></i>
                </div>
                <div>
                    <div class="stat-value"><?= $stats['total'] ?></div>
                    <div class="stat-label">Total Tenants</div>
                    <div class="stat-sub text-success"><i class="bi bi-check-circle me-1"></i><?= $stats['active'] ?> active</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-clock fs-4"></i>
                </div>
                <div>
                    <div class="stat-value"><?= $stats['trial'] ?></div>
                    <div class="stat-label">On Trial</div>
                    <div class="stat-sub text-danger"><i class="bi bi-x-circle me-1"></i><?= $stats['suspended'] ?> suspended</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-currency-dollar fs-4"></i>
                </div>
                <div>
                    <div class="stat-value">
                        ৳<?= number_format(array_sum(array_column($monthly_revenue ?? [], 'revenue')), 0) ?>
                    </div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-sub text-muted">All time</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-layers fs-4"></i>
                </div>
                <div>
                    <div class="stat-value"><?= count($plans) ?></div>
                    <div class="stat-label">Active Plans</div>
                    <div class="stat-sub"><a href="<?= site_url('superadmin/plans') ?>" class="text-primary small">Manage plans</a></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Plans Overview -->
        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 fw-semibold pt-3 d-flex justify-content-between">
                    <span>Plans & Subscriptions</span>
                    <a href="<?= site_url('superadmin/plans') ?>" class="btn btn-sm btn-outline-primary">Manage</a>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">Plan</th>
                                <th class="text-center">Tenants</th>
                                <th class="text-end px-3">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($plans as $plan): ?>
                            <tr>
                                <td class="px-3">
                                    <div class="fw-semibold"><?= esc($plan['name']) ?></div>
                                    <div class="text-muted small"><?= ucfirst($plan['billing_cycle']) ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary"><?= $plan['tenant_count'] ?></span>
                                </td>
                                <td class="text-end px-3 fw-semibold">
                                    <?php if ($plan['price'] == 0): ?>
                                        <span class="text-success">Free</span>
                                    <?php else: ?>
                                        ৳<?= number_format($plan['price']) ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Monthly Revenue Trend</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tenants -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 fw-semibold pt-3 d-flex justify-content-between">
            <span>Recent Tenants</span>
            <a href="<?= site_url('superadmin/tenants') ?>" class="btn btn-sm btn-outline-primary">View all</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Store</th>
                            <th>Email</th>
                            <th>Subdomain</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th class="text-end px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_tenants as $tenant): ?>
                        <tr>
                            <td class="px-3 fw-semibold"><?= esc($tenant['name']) ?></td>
                            <td class="text-muted small"><?= esc($tenant['email']) ?></td>
                            <td>
                                <code class="small"><?= esc($tenant['subdomain']) ?>.app</code>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary"><?= esc($tenant['plan_name'] ?? 'None') ?></span>
                            </td>
                            <td>
                                <?php
                                $stMap = ['active'=>'success','trial'=>'warning','suspended'=>'danger','cancelled'=>'secondary'];
                                $sc = $stMap[$tenant['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> border border-<?= $sc ?>-subtle">
                                    <?= ucfirst($tenant['status']) ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?= date('d M Y', strtotime($tenant['created_at'])) ?></td>
                            <td class="text-end px-3">
                                <a href="<?= site_url('superadmin/tenants/' . $tenant['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column(array_reverse($monthly_revenue ?? []), 'month')) ?>,
        datasets: [{
            label: 'Revenue (৳)',
            data: <?= json_encode(array_column(array_reverse($monthly_revenue ?? []), 'revenue')) ?>,
            backgroundColor: 'rgba(79,70,229,0.7)',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { ticks: { callback: v => '৳' + v.toLocaleString() } },
            x: { ticks: { maxRotation: 0 } }
        }
    }
});
</script>
<?= $this->endSection() ?>
