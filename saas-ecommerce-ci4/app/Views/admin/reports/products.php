<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Product Performance</h4>
            <p class="text-muted mb-0">Best selling products ranked by quantity sold</p>
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
                            <th class="px-3" style="width:40px">#</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Qty Sold</th>
                            <th>Revenue</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_products)): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">No sales data available yet.</td></tr>
                        <?php else: ?>
                        <?php
                            $maxQty = max(array_column($top_products, 'qty_sold')) ?: 1;
                        ?>
                        <?php foreach ($top_products as $i => $p): ?>
                        <tr>
                            <td class="px-3 fw-bold text-muted"><?= $i + 1 ?></td>
                            <td class="fw-semibold"><?= esc($p['name']) ?></td>
                            <td><code class="small"><?= esc($p['sku'] ?? '—') ?></code></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3"><?= number_format($p['qty_sold']) ?></span>
                            </td>
                            <td class="fw-semibold text-success">
                                <?= esc($currentTenant['currency_symbol']) ?><?= number_format($p['revenue'], 2) ?>
                            </td>
                            <td style="min-width:160px">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:8px">
                                        <div class="progress-bar bg-primary" style="width:<?= ($p['qty_sold'] / $maxQty) * 100 ?>%"></div>
                                    </div>
                                    <span class="text-muted small"><?= round(($p['qty_sold'] / $maxQty) * 100) ?>%</span>
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
