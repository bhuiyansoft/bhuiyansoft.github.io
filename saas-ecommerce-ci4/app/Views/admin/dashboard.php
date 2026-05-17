<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Dashboard</h4>
            <p class="text-muted mb-0">Welcome back, <?= esc($currentUser['name']) ?>!</p>
        </div>
        <div class="text-muted small">
            <i class="bi bi-calendar3 me-1"></i><?= date('D, d M Y') ?>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-bag-check fs-4"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($order_stats['total']) ?></div>
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-sub text-warning">
                        <i class="bi bi-clock me-1"></i><?= $order_stats['pending'] ?> pending
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-currency-dollar fs-4"></i>
                </div>
                <div>
                    <div class="stat-value"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order_stats['revenue_month'], 0) ?></div>
                    <div class="stat-label">Revenue (Month)</div>
                    <div class="stat-sub text-success">
                        <i class="bi bi-arrow-up me-1"></i>Today: <?= esc($currentTenant['currency_symbol']) ?><?= number_format($order_stats['revenue_today'], 0) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-box-seam fs-4"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($product_stats['total']) ?></div>
                    <div class="stat-label">Products</div>
                    <div class="stat-sub text-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i><?= $product_stats['low_stock'] ?> low stock
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <div class="stat-value"><?= number_format($customer_stats['total']) ?></div>
                    <div class="stat-label">Customers</div>
                    <div class="stat-sub text-success">
                        <i class="bi bi-person-plus me-1"></i><?= $customer_stats['new_this_month'] ?> this month
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Sales Chart -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h6 class="fw-semibold mb-0">Sales Overview (Last 30 Days)</h6>
                    <span class="badge bg-primary-subtle text-primary">Revenue</span>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="260"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h6 class="fw-semibold mb-0">Top Products</h6>
                    <a href="<?= site_url('admin/reports/products') ?>" class="btn btn-sm btn-link p-0">View all</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($top_products)): ?>
                        <div class="text-center text-muted py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No data yet</div>
                    <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($top_products as $i => $product): ?>
                        <div class="list-group-item px-3 py-2">
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-bold text-muted" style="width:20px">#<?= $i+1 ?></span>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="fw-semibold text-truncate small"><?= esc($product['name']) ?></div>
                                    <div class="text-muted" style="font-size:.75rem"><?= $product['qty_sold'] ?> sold</div>
                                </div>
                                <div class="fw-semibold small text-success"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($product['revenue'], 0) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Recent Orders -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h6 class="fw-semibold mb-0">Recent Orders</h6>
                    <a href="<?= site_url('admin/orders') ?>" class="btn btn-sm btn-outline-primary">View all</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3">Order</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recent_orders)): ?>
                                    <tr><td colspan="5" class="text-center text-muted py-4">No orders yet</td></tr>
                                <?php else: ?>
                                <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td class="px-3">
                                        <a href="<?= site_url('admin/orders/' . $order['id']) ?>" class="fw-semibold text-primary text-decoration-none">
                                            <?= esc($order['order_number']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="small"><?= esc($order['customer_name'] ?? $order['shipping_name']) ?></div>
                                    </td>
                                    <td class="fw-semibold"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['total'], 2) ?></td>
                                    <td><?= order_status_badge($order['status']) ?></td>
                                    <td class="text-muted small"><?= date('d M', strtotime($order['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h6 class="fw-semibold mb-0 text-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i>Low Stock
                    </h6>
                    <a href="<?= site_url('admin/products') ?>" class="btn btn-sm btn-link p-0 text-danger">Manage</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($low_stock)): ?>
                        <div class="text-center text-muted py-4"><i class="bi bi-check-circle text-success fs-3 d-block mb-2"></i>All stocked!</div>
                    <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($low_stock as $product): ?>
                        <a href="<?= site_url('admin/products/' . $product['id'] . '/edit') ?>" class="list-group-item list-group-item-action px-3 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold small text-truncate" style="max-width:160px"><?= esc($product['name']) ?></div>
                                    <div class="text-muted" style="font-size:.75rem">SKU: <?= esc($product['sku']) ?></div>
                                </div>
                                <span class="badge bg-danger"><?= $product['stock'] ?> left</span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
<?php
$labels   = array_column($sales_chart, 'date');
$revenue  = array_column($sales_chart, 'revenue');
$orders   = array_column($sales_chart, 'orders');
?>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            {
                label: 'Revenue (<?= esc($currentTenant['currency_symbol']) ?>)',
                data: <?= json_encode($revenue) ?>,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.08)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y',
            },
            {
                label: 'Orders',
                data: <?= json_encode($orders) ?>,
                borderColor: '#198754',
                backgroundColor: 'rgba(25,135,84,0.08)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: { type: 'linear', display: true, position: 'left', ticks: { callback: v => '<?= esc($currentTenant['currency_symbol']) ?>' + v.toLocaleString() } },
            y1: { type: 'linear', display: true, position: 'right', grid: { drawOnChartArea: false } },
            x: { ticks: { maxRotation: 0 } }
        },
        plugins: { legend: { position: 'top' } }
    }
});
</script>
<?= $this->endSection() ?>
