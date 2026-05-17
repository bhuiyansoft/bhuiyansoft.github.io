<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= esc($order['order_number']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; font-size: .9rem; }
        .invoice-header { background: linear-gradient(135deg, #1e1b4b, #312e81); color: #fff; padding: 32px; border-radius: 12px 12px 0 0; }
        .invoice-body { padding: 32px; }
        .invoice-table th { background: #f8fafc; font-size:.78rem; font-weight:700; text-transform:uppercase; color:#64748b; }
        .invoice-total { background: #f8fafc; border-radius: 8px; padding: 20px; }
        .badge-status { padding: 6px 14px; border-radius: 20px; font-size: .78rem; font-weight: 600; }
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-light">

<div class="no-print d-flex justify-content-center gap-2 py-3">
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        <i class="bi bi-printer me-1"></i>Print Invoice
    </button>
    <a href="<?= site_url('admin/orders/' . $order['id']) ?>" class="btn btn-outline-secondary btn-sm">
        ← Back to Order
    </a>
</div>

<div class="container" style="max-width: 820px">
    <div class="card border-0 shadow-sm mb-4">
        <!-- Header -->
        <div class="invoice-header">
            <div class="row align-items-start">
                <div class="col">
                    <h3 class="fw-bold mb-1"><?= esc($currentTenant['name'] ?? 'Store Name') ?></h3>
                    <?php if ($currentTenant['address']): ?>
                        <p class="mb-0 opacity-75 small"><?= esc($currentTenant['address']) ?></p>
                    <?php endif; ?>
                    <?php if ($currentTenant['phone']): ?>
                        <p class="mb-0 opacity-75 small"><?= esc($currentTenant['phone']) ?></p>
                    <?php endif; ?>
                    <p class="mb-0 opacity-75 small"><?= esc($currentTenant['email'] ?? '') ?></p>
                </div>
                <div class="col text-end">
                    <h2 class="fw-bold mb-1">INVOICE</h2>
                    <div class="opacity-75 small"># <?= esc($order['order_number']) ?></div>
                    <div class="mt-2">
                        <?php
                        $pmColor = ['paid'=>'#22c55e','unpaid'=>'#ef4444','partial'=>'#f59e0b','refunded'=>'#6b7280'][$order['payment_status']] ?? '#6b7280';
                        ?>
                        <span class="badge-status" style="background:<?= $pmColor ?>20;color:<?= $pmColor ?>;border:1px solid <?= $pmColor ?>40">
                            <?= strtoupper($order['payment_status']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-body">
            <!-- Meta -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="fw-semibold text-muted text-uppercase small mb-2">Bill To</h6>
                    <div class="fw-semibold"><?= esc($order['shipping_name']) ?></div>
                    <div class="text-muted small"><?= esc($order['shipping_email']) ?></div>
                    <div class="text-muted small"><?= esc($order['shipping_phone']) ?></div>
                    <div class="text-muted small mt-1">
                        <?= esc($order['shipping_address']) ?><br>
                        <?= esc(implode(', ', array_filter([$order['shipping_city'], $order['shipping_state'], $order['shipping_postal']]))) ?><br>
                        <?= esc($order['shipping_country']) ?>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="fw-semibold text-muted text-uppercase small mb-2">Invoice Details</h6>
                    <table class="ms-auto" style="font-size:.85rem">
                        <tr><td class="text-muted pe-3">Invoice Date</td><td class="fw-semibold"><?= date('d M Y', strtotime($order['created_at'])) ?></td></tr>
                        <tr><td class="text-muted pe-3">Payment Method</td><td class="fw-semibold text-capitalize"><?= esc($order['payment_method']) ?></td></tr>
                        <tr><td class="text-muted pe-3">Order Status</td><td class="fw-semibold text-capitalize"><?= esc($order['status']) ?></td></tr>
                    </table>
                </div>
            </div>

            <!-- Items -->
            <table class="table invoice-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>SKU</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $i => $item): ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td class="fw-semibold"><?= esc($item['product_name']) ?></td>
                        <td class="text-muted small"><?= esc($item['product_sku'] ?? '—') ?></td>
                        <td class="text-center"><?= $item['quantity'] ?></td>
                        <td class="text-end"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($item['unit_price'], 2) ?></td>
                        <td class="text-end fw-semibold"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($item['total_price'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Totals -->
            <div class="row justify-content-end mt-3">
                <div class="col-md-5">
                    <div class="invoice-total">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Subtotal</span>
                            <span><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['subtotal'], 2) ?></span>
                        </div>
                        <?php if ($order['tax_amount'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Tax</span>
                            <span><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['tax_amount'], 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($order['shipping_amount'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Shipping</span>
                            <span><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['shipping_amount'], 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($order['discount_amount'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 small text-success">
                            <span>Discount</span>
                            <span>−<?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['discount_amount'], 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span class="fs-5 text-primary"><?= esc($currentTenant['currency_symbol']) ?><?= number_format($order['total'], 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if ($order['notes']): ?>
            <div class="mt-4 p-3 bg-light rounded small">
                <strong>Order Notes:</strong> <?= esc($order['notes']) ?>
            </div>
            <?php endif; ?>

            <!-- Footer -->
            <div class="text-center text-muted small mt-5 pt-4 border-top">
                <p class="mb-1">Thank you for your business!</p>
                <p class="mb-0"><?= esc($currentTenant['name'] ?? '') ?> · <?= esc($currentTenant['email'] ?? '') ?></p>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</body>
</html>
