<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Reports & Analytics</h4>
            <p class="text-muted mb-0">Track your store performance</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('admin/reports/sales') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-file-earmark-bar-graph me-1"></i>Sales Report
            </a>
            <a href="<?= site_url('admin/reports/products') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-box-seam me-1"></i>Products
            </a>
            <a href="<?= site_url('admin/reports/customers') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-people me-1"></i>Customers
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-bag-check fs-4"></i></div>
                <div>
                    <div class="stat-value"><?= number_format($order_stats['total']) ?></div>
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-sub text-muted">All time</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-currency-dollar fs-4"></i></div>
                <div>
                    <div class="stat-value"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order_stats['revenue_month'], 0) ?></div>
                    <div class="stat-label">This Month</div>
                    <div class="stat-sub text-muted">Revenue</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-clock fs-4"></i></div>
                <div>
                    <div class="stat-value"><?= number_format($order_stats['pending']) ?></div>
                    <div class="stat-label">Pending Orders</div>
                    <div class="stat-sub text-muted">Need action</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon bg-info-subtle text-info"><i class="bi bi-calendar-check fs-4"></i></div>
                <div>
                    <div class="stat-value"><?= number_format($order_stats['today']) ?></div>
                    <div class="stat-label">Today's Orders</div>
                    <div class="stat-sub text-muted"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order_stats['revenue_today'], 0) ?> revenue</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h6 class="fw-semibold mb-0">Sales Trend</h6>
                    <div class="btn-group btn-group-sm">
                        <?php foreach ([7=>'7D', 30=>'30D', 90=>'90D'] as $d => $label): ?>
                        <a href="?period=<?= $d ?>" class="btn btn-<?= $period == $d ? 'primary' : 'outline-secondary' ?>"><?= $label ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="280"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h6 class="fw-semibold mb-0">Top Products</h6>
                    <a href="<?= site_url('admin/reports/products') ?>" class="btn btn-sm btn-link p-0">Full report</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($top_products)): ?>
                        <div class="text-center text-muted py-4"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No data</div>
                    <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($top_products, 0, 8) as $i => $p): ?>
                        <div class="list-group-item px-3 py-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted fw-bold small" style="width:20px">#<?= $i+1 ?></span>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold small text-truncate"><?= esc($p['name']) ?></div>
                                    <div class="text-muted" style="font-size:.72rem"><?= $p['qty_sold'] ?> sold</div>
                                </div>
                                <div class="text-success fw-semibold small"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($p['revenue'], 0) ?></div>
                            </div>
                        </div>
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
const chartData = <?= json_encode($sales_chart) ?>;
const labels  = chartData.map(r => r.date);
const revenue = chartData.map(r => parseFloat(r.revenue || 0));
const orders  = chartData.map(r => parseInt(r.orders || 0));

new Chart(document.getElementById('salesChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels,
        datasets: [
            { label: 'Revenue', data: revenue, backgroundColor: 'rgba(13,110,253,0.75)', borderRadius: 5, yAxisID: 'y' },
            { label: 'Orders', data: orders, type: 'line', borderColor: '#198754', backgroundColor: 'rgba(25,135,84,0.1)',
              tension: 0.4, fill: true, yAxisID: 'y1' }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y:  { position: 'left',  ticks: { callback: v => '<?= esc($currentTenant['currency_symbol']) ?>' + v.toLocaleString() } },
            y1: { position: 'right', grid: { drawOnChartArea: false } },
            x:  { ticks: { maxRotation: 0 } }
        },
        plugins: { legend: { position: 'top' } }
    }
});
</script>
<?= $this->endSection() ?>
