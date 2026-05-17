<?= $this->extend('layouts/store') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-5 px-4">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success-subtle" style="width:80px;height:80px">
                            <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-success mb-2">Order Confirmed!</h2>
                    <p class="text-muted mb-1">Thank you for your purchase.</p>
                    <p class="text-muted mb-4">Your order number is:</p>
                    <div class="bg-light rounded-3 px-4 py-3 mb-4 d-inline-block">
                        <span class="fw-bold fs-4 font-monospace"><?= esc($order['order_number']) ?></span>
                    </div>

                    <div class="text-start mb-4">
                        <h5 class="fw-semibold mb-3">Order Summary</h5>
                        <?php foreach ($items as $item): ?>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span><?= esc($item['product_name']) ?> × <?= $item['quantity'] ?></span>
                            <span class="fw-semibold">৳<?= number_format($item['total_price'], 2) ?></span>
                        </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total Paid</span>
                            <span class="text-primary">৳<?= number_format($order['total'], 2) ?></span>
                        </div>
                    </div>

                    <div class="alert alert-info text-start py-2">
                        <i class="bi bi-envelope me-2"></i>
                        A confirmation email will be sent to <strong><?= esc($order['shipping_email']) ?></strong>
                    </div>

                    <div class="d-flex gap-3 justify-content-center mt-3">
                        <a href="/shop" class="btn btn-outline-primary">
                            <i class="bi bi-shop me-1"></i>Continue Shopping
                        </a>
                        <a href="/auth/login" class="btn btn-primary">
                            <i class="bi bi-person me-1"></i>Track Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
