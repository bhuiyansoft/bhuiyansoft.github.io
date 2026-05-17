<?= $this->extend('layouts/store') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Shopping Cart
        <span class="badge bg-primary ms-2"><?= count($cart) ?> items</span>
    </h2>

    <?php if (empty($cart)): ?>
    <div class="text-center py-5">
        <i class="bi bi-cart-x fs-1 text-muted d-block mb-3"></i>
        <h4 class="text-muted">Your cart is empty</h4>
        <p class="text-muted">Looks like you haven't added anything yet.</p>
        <a href="/shop" class="btn btn-primary px-4">Continue Shopping</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <form method="post" action="/shop/cart/update">
                <?= csrf_field() ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <?php foreach ($cart as $productId => $item): ?>
                        <div class="d-flex align-items-center gap-3 p-3 cart-item border-bottom">
                            <div class="flex-shrink-0">
                                <?php if ($item['image']): ?>
                                    <img src="<?= base_url($item['image']) ?>" alt="" width="80" height="80" class="rounded" style="object-fit:cover">
                                <?php else: ?>
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:80px;height:80px">
                                        <i class="bi bi-box fs-3 text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold"><?= esc($item['name']) ?></h6>
                                <div class="text-muted small">SKU: <?= esc($item['sku'] ?? '—') ?></div>
                                <div class="fw-bold text-primary mt-1"><?= esc($tenant['currency_symbol']) ?><?= number_format($item['price'], 2) ?></div>
                            </div>
                            <div class="qty-control">
                                <button type="button" class="qty-minus">−</button>
                                <input type="number" name="qty[<?= $productId ?>]" value="<?= $item['qty'] ?>" min="1" max="<?= $item['stock'] ?>">
                                <button type="button" class="qty-plus">+</button>
                            </div>
                            <div class="fw-bold text-end" style="min-width:80px">
                                <?= esc($tenant['currency_symbol']) ?><?= number_format($item['price'] * $item['qty'], 2) ?>
                            </div>
                            <a href="/shop/cart/remove/<?= $productId ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Remove this item?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="/shop" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Continue Shopping
                        </a>
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-clockwise me-1"></i>Update Cart
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <!-- Coupon -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3"><i class="bi bi-ticket-perforated me-2"></i>Have a coupon?</h6>
                    <form id="coupon-form">
                        <?= csrf_field() ?>
                        <div class="input-group">
                            <input type="text" id="coupon-code" class="form-control" placeholder="Enter coupon code"
                                   value="<?= esc($coupon ?? '') ?>">
                            <button type="submit" class="btn btn-outline-primary">Apply</button>
                        </div>
                        <div id="coupon-message" class="mt-2 small"></div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Order Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span><?= esc($tenant['currency_symbol']) ?><?= number_format($subtotal, 2) ?></span>
                    </div>
                    <?php if ($tax > 0): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax (<?= $tenant['tax_rate'] ?>%)</span>
                        <span><?= esc($tenant['currency_symbol']) ?><?= number_format($tax, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($shipping > 0): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span><?= esc($tenant['currency_symbol']) ?><?= number_format($shipping, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($discount > 0): ?>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span><i class="bi bi-tag me-1"></i>Discount</span>
                        <span>−<?= esc($tenant['currency_symbol']) ?><?= number_format($discount, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                        <span>Total</span>
                        <span class="text-primary"><?= esc($tenant['currency_symbol']) ?><?= number_format($total, 2) ?></span>
                    </div>
                    <a href="/shop/checkout" class="btn btn-primary w-100 fw-semibold py-2">
                        <i class="bi bi-lock me-1"></i>Proceed to Checkout
                    </a>
                    <div class="text-center mt-2">
                        <small class="text-muted"><i class="bi bi-shield-lock me-1"></i>Secure checkout</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
