<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Sales Report</h4>
            <p class="text-muted mb-0">Detailed sales data with date filtering</p>
        </div>
        <a href="<?= site_url('admin/reports') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <!-- Date Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-2">
            <form method="get" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label small mb-1">From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="<?= esc($date_from) ?>">
                </div>
                <div class="col-auto">
                    <label class="form-label small mb-1">To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="<?= esc($date_to) ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                </div>
                <div class="col-auto ms-auto small text-muted align-self-center">
                    Showing <?= date('d M Y', strtotime($date_from)) ?> – <?= date('d M Y', strtotime($date_to)) ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary KPIs -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-currency-dollar fs-4"></i></div>
                <div>
                    <div class="stat-value"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($total_revenue, 2) ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-bag-check fs-4"></i></div>
                <div>
                    <div class="stat-value"><?= number_format($total_orders) ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-graph-up fs-4"></i></div>
                <div>
                    <div class="stat-value"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($avg_order, 2) ?></div>
                    <div class="stat-label">Avg. Order Value</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 fw-semibold pt-3 d-flex justify-content-between">
            <span>Orders (<?= count($orders) ?>)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Order #</th>
                            <th>Customer</th>
                            <th>Method</th>
                            <th>Subtotal</th>
                            <th>Tax</th>
                            <th>Shipping</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="9" class="text-center py-5 text-muted">No orders in this period.</td></tr>
                        <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                        <tr>
                            <td class="px-3">
                                <a href="<?= site_url('admin/orders/' . $o['id']) ?>" class="text-primary text-decoration-none fw-semibold">
                                    <?= esc($o['order_number']) ?>
                                </a>
                            </td>
                            <td class="small"><?= esc($o['shipping_name']) ?></td>
                            <td class="small text-capitalize"><?= esc($o['payment_method']) ?></td>
                            <td class="small"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($o['subtotal'], 2) ?></td>
                            <td class="small"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($o['tax_amount'], 2) ?></td>
                            <td class="small"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($o['shipping_amount'], 2) ?></td>
                            <td class="small text-success"><?= $o['discount_amount'] > 0 ? '-' . esc($currentTenant['currency_symbol']) . number_format($o['discount_amount'], 2) : '—' ?></td>
                            <td class="fw-semibold"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($o['total'], 2) ?></td>
                            <td class="small text-muted"><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($orders)): ?>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="7" class="text-end px-3 fw-bold">Grand Total</td>
                            <td class="fw-bold text-success"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($total_revenue, 2) ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
