<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Orders</h4>
            <p class="text-muted mb-0">Manage all customer orders</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="get" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="search" name="search" class="form-control form-control-sm" placeholder="Order # or customer name..."
                           value="<?= esc($filters['search'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <?php foreach (['pending','processing','shipped','delivered','cancelled','refunded'] as $s): ?>
                            <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="payment_status" class="form-select form-select-sm">
                        <option value="">Payment Status</option>
                        <?php foreach (['unpaid','paid','partial','refunded'] as $s): ?>
                            <option value="<?= $s ?>" <?= ($filters['payment_status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control form-control-sm" value="<?= esc($filters['date_from'] ?? '') ?>" placeholder="From">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control form-control-sm" value="<?= esc($filters['date_to'] ?? '') ?>" placeholder="To">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="<?= site_url('admin/orders') ?>" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
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
                            <th class="px-3">Order #</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-bag-check fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted">No orders found.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="px-3">
                                <a href="<?= site_url('admin/orders/' . $order['id']) ?>" class="fw-semibold text-primary text-decoration-none">
                                    <?= esc($order['order_number']) ?>
                                </a>
                            </td>
                            <td>
                                <div class="fw-semibold small"><?= esc($order['customer_name'] ?? $order['shipping_name']) ?></div>
                                <div class="text-muted" style="font-size:.75rem"><?= esc($order['customer_email'] ?? $order['shipping_email']) ?></div>
                            </td>
                            <td class="text-muted small">—</td>
                            <td class="fw-semibold"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['total'], 2) ?></td>
                            <td>
                                <?php
                                $pmMap = ['paid'=>'success','unpaid'=>'danger','partial'=>'warning','refunded'=>'info'];
                                $pm    = $pmMap[$order['payment_status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $pm ?>-subtle text-<?= $pm ?> border border-<?= $pm ?>-subtle">
                                    <?= ucfirst($order['payment_status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $stMap = ['pending'=>'warning','processing'=>'info','shipped'=>'primary','delivered'=>'success','cancelled'=>'danger','refunded'=>'secondary'];
                                $st    = $stMap[$order['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $st ?>-subtle text-<?= $st ?> border border-<?= $st ?>-subtle">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                            <td class="text-end px-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= site_url('admin/orders/' . $order['id']) ?>" class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= site_url('admin/orders/' . $order['id'] . '/invoice') ?>" class="btn btn-outline-secondary" title="Invoice">
                                        <i class="bi bi-receipt"></i>
                                    </a>
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
        <div class="card-footer bg-white border-0">
            <?= $pager->links() ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
