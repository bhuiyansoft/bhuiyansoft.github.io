<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Order <?= esc($order['order_number']) ?></h4>
            <p class="text-muted mb-0">Placed on <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('admin/orders/' . $order['id'] . '/invoice') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer me-1"></i>Invoice
            </a>
            <a href="<?= site_url('admin/orders') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <!-- Order Items -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Order Items</div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end px-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="px-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if (!empty($item['product_image'])): ?>
                                            <img src="<?= base_url($item['product_image']) ?>" alt="" width="40" height="40" class="rounded" style="object-fit:cover">
                                        <?php else: ?>
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:40px;height:40px">
                                                <i class="bi bi-box text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-semibold"><?= esc($item['product_name']) ?></div>
                                            <?php if ($item['product_sku']): ?>
                                                <div class="text-muted small">SKU: <?= esc($item['product_sku']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center"><?= $item['quantity'] ?></td>
                                <td class="text-end"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($item['unit_price'], 2) ?></td>
                                <td class="text-end px-3 fw-semibold"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($item['total_price'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-semibold px-3">Subtotal</td>
                                <td class="text-end px-3"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['subtotal'], 2) ?></td>
                            </tr>
                            <?php if ($order['tax_amount'] > 0): ?>
                            <tr>
                                <td colspan="3" class="text-end px-3">Tax</td>
                                <td class="text-end px-3"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['tax_amount'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($order['shipping_amount'] > 0): ?>
                            <tr>
                                <td colspan="3" class="text-end px-3">Shipping</td>
                                <td class="text-end px-3"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['shipping_amount'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($order['discount_amount'] > 0): ?>
                            <tr>
                                <td colspan="3" class="text-end px-3 text-success">Discount</td>
                                <td class="text-end px-3 text-success">- <?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['discount_amount'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="3" class="text-end fw-bold px-3">Total</td>
                                <td class="text-end px-3 fw-bold fs-5"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['total'], 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Shipping Address</div>
                <div class="card-body">
                    <p class="mb-1 fw-semibold"><?= esc($order['shipping_name']) ?></p>
                    <p class="mb-1 text-muted"><?= esc($order['shipping_email']) ?></p>
                    <p class="mb-1 text-muted"><?= esc($order['shipping_phone']) ?></p>
                    <p class="mb-0 text-muted">
                        <?= esc($order['shipping_address']) ?><br>
                        <?= esc($order['shipping_city']) ?>, <?= esc($order['shipping_state']) ?> <?= esc($order['shipping_postal']) ?><br>
                        <?= esc($order['shipping_country']) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <!-- Update Status -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Update Status</div>
                <div class="card-body">
                    <form method="post" action="<?= site_url('admin/orders/' . $order['id'] . '/status') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Order Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <?php foreach (['pending','processing','shipped','delivered','cancelled','refunded'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Payment Status</label>
                            <select name="payment_status" class="form-select form-select-sm">
                                <?php foreach (['unpaid','paid','partial','refunded'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $order['payment_status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Update</button>
                    </form>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Customer</div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar-sm bg-primary-subtle text-primary fw-bold">
                            <?= strtoupper(substr($order['customer_name'] ?? 'G', 0, 1)) ?>
                        </div>
                        <div>
                            <div class="fw-semibold"><?= esc($order['customer_name'] ?? $order['shipping_name']) ?></div>
                            <div class="text-muted small"><?= esc($order['customer_email'] ?? $order['shipping_email']) ?></div>
                        </div>
                    </div>
                    <?php if ($order['customer_id']): ?>
                        <a href="<?= site_url('admin/customers/' . $order['customer_id']) ?>" class="btn btn-sm btn-outline-primary w-100">
                            View Customer Profile
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Summary</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Payment Method</span>
                        <span class="fw-semibold text-capitalize"><?= esc($order['payment_method']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Order Date</span>
                        <span><?= date('d M Y', strtotime($order['created_at'])) ?></span>
                    </div>
                    <?php if ($order['shipped_at']): ?>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Shipped</span>
                        <span><?= date('d M Y', strtotime($order['shipped_at'])) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($order['delivered_at']): ?>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Delivered</span>
                        <span><?= date('d M Y', strtotime($order['delivered_at'])) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($order['notes']): ?>
                    <hr>
                    <div class="small">
                        <div class="text-muted mb-1">Notes</div>
                        <div><?= esc($order['notes']) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
