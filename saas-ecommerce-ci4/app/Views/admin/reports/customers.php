<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Customer Report</h4>
            <p class="text-muted mb-0">Top customers ranked by lifetime spend</p>
        </div>
        <a href="<?= site_url('admin/reports') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">#</th>
                            <th>Customer</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                            <th>Avg. Order</th>
                            <th>Loyalty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_customers)): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">No customer data yet.</td></tr>
                        <?php else: ?>
                        <?php
                            $maxSpend = max(array_column($top_customers, 'total_spent')) ?: 1;
                        ?>
                        <?php foreach ($top_customers as $i => $c): ?>
                        <tr>
                            <td class="px-3 fw-bold text-muted"><?= $i + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-primary-subtle text-primary fw-bold">
                                        <?= strtoupper(substr($c['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?= esc($c['name']) ?></div>
                                        <div class="text-muted small"><?= esc($c['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary"><?= $c['order_count'] ?></span></td>
                            <td class="fw-semibold text-success">
                                <?= esc($currentTenant['currency_symbol']) ?><?= number_format($c['total_spent'], 2) ?>
                            </td>
                            <td class="text-muted small">
                                <?= esc($currentTenant['currency_symbol']) ?><?= number_format($c['order_count'] > 0 ? $c['total_spent'] / $c['order_count'] : 0, 2) ?>
                            </td>
                            <td style="min-width:140px">
                                <div class="progress" style="height:8px">
                                    <div class="progress-bar bg-success" style="width:<?= ($c['total_spent'] / $maxSpend) * 100 ?>%"></div>
                                </div>
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

<?= $this->endSection() ?>
