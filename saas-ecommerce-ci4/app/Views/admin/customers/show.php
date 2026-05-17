<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><?= esc($customer['name']) ?></h4>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= site_url('admin/customers') ?>">Customers</a></li>
                <li class="breadcrumb-item active"><?= esc($customer['name']) ?></li>
            </ol>
        </div>
        <a href="<?= site_url('admin/customers') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center py-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <?= strtoupper(substr($customer['name'], 0, 1)) ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?= esc($customer['name']) ?></h5>
                    <p class="text-muted small mb-3"><?= esc($customer['email']) ?></p>
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="bg-primary-subtle rounded p-2">
                                <div class="fw-bold text-primary"><?= $customer['order_count'] ?? $customer['total_orders'] ?></div>
                                <div class="text-muted" style="font-size:.72rem">Orders</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success-subtle rounded p-2">
                                <div class="fw-bold text-success"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($customer['total_spent'] ?? 0, 0) ?></div>
                                <div class="text-muted" style="font-size:.72rem">Lifetime Value</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Contact Info</div>
                <div class="card-body small">
                    <div class="d-flex gap-2 mb-2">
                        <i class="bi bi-envelope text-muted mt-1"></i>
                        <div><?= esc($customer['email']) ?></div>
                    </div>
                    <?php if ($customer['phone']): ?>
                    <div class="d-flex gap-2 mb-2">
                        <i class="bi bi-telephone text-muted mt-1"></i>
                        <div><?= esc($customer['phone']) ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if ($customer['city'] || $customer['country']): ?>
                    <div class="d-flex gap-2 mb-2">
                        <i class="bi bi-geo-alt text-muted mt-1"></i>
                        <div><?= esc(implode(', ', array_filter([$customer['city'], $customer['country']]))) ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="d-flex gap-2">
                        <i class="bi bi-calendar text-muted mt-1"></i>
                        <div>Joined <?= date('d M Y', strtotime($customer['created_at'])) ?></div>
                    </div>
                    <?php if ($customer['notes']): ?>
                    <hr>
                    <div class="text-muted"><?= esc($customer['notes']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-semibold pt-3 d-flex justify-content-between">
                    <span>Order History</span>
                    <a href="<?= site_url('admin/orders?customer_id=' . $customer['id']) ?>" class="btn btn-sm btn-link p-0">View all</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3">Order #</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end px-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orders)): ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No orders yet.</td></tr>
                                <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td class="px-3 fw-semibold">
                                        <a href="<?= site_url('admin/orders/' . $order['id']) ?>" class="text-primary text-decoration-none">
                                            <?= esc($order['order_number']) ?>
                                        </a>
                                    </td>
                                    <td><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['total'], 2) ?></td>
                                    <td>
                                        <?php $pm = ['paid'=>'success','unpaid'=>'danger','partial'=>'warning','refunded'=>'secondary'][$order['payment_status']] ?? 'secondary'; ?>
                                        <span class="badge bg-<?= $pm ?>-subtle text-<?= $pm ?>"><?= ucfirst($order['payment_status']) ?></span>
                                    </td>
                                    <td>
                                        <?php $st = ['pending'=>'warning','processing'=>'info','shipped'=>'primary','delivered'=>'success','cancelled'=>'danger'][$order['status']] ?? 'secondary'; ?>
                                        <span class="badge bg-<?= $st ?>-subtle text-<?= $st ?>"><?= ucfirst($order['status']) ?></span>
                                    </td>
                                    <td class="text-muted small"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                    <td class="text-end px-3">
                                        <a href="<?= site_url('admin/orders/' . $order['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
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
</div>

<style>
.avatar-lg {
    width: 72px; height: 72px; border-radius: 50%;
    background: #e0e7ff; color: #4f46e5;
    font-size: 1.8rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
</style>

<?= $this->endSection() ?>
