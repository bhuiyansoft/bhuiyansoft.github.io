<?= $this->extend('layouts/store') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-credit-card me-2"></i>Checkout</h2>

    <form method="post" action="/shop/checkout/place">
        <?= csrf_field() ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Customer Info -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">
                        <i class="bi bi-person me-2"></i>Customer Information
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control" required placeholder="Your full name" value="<?= old('customer_name') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" class="form-control" required placeholder="you@example.com" value="<?= old('customer_email') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="customer_phone" class="form-control" required placeholder="+880 1XXXXXXXXX" value="<?= old('customer_phone') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">
                        <i class="bi bi-geo-alt me-2"></i>Shipping Address
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Street Address <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_address" class="form-control" required placeholder="House no, road, area" value="<?= old('shipping_address') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_city" class="form-control" required placeholder="City" value="<?= old('shipping_city') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State / Division</label>
                                <input type="text" name="shipping_state" class="form-control" placeholder="Dhaka" value="<?= old('shipping_state') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="shipping_postal" class="form-control" placeholder="1200" value="<?= old('shipping_postal') ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Country</label>
                                <select name="shipping_country" class="form-select">
                                    <option value="Bangladesh" selected>Bangladesh</option>
                                    <option value="India">India</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">
                        <i class="bi bi-credit-card me-2"></i>Payment Method
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php
                            $methods = [
                                ['id'=>'cod','name'=>'Cash on Delivery','icon'=>'bi-cash','desc'=>'Pay when you receive your order'],
                                ['id'=>'bkash','name'=>'bKash','icon'=>'bi-phone','desc'=>'Mobile banking payment'],
                                ['id'=>'nagad','name'=>'Nagad','icon'=>'bi-phone-fill','desc'=>'Mobile banking payment'],
                                ['id'=>'card','name'=>'Credit/Debit Card','icon'=>'bi-credit-card-2-front','desc'=>'Visa, Mastercard, etc.'],
                            ];
                            foreach ($methods as $m): ?>
                            <div class="col-6">
                                <label class="payment-method d-block">
                                    <input type="radio" name="payment_method" value="<?= $m['id'] ?>" class="d-none payment-radio"
                                           <?= old('payment_method', 'cod') === $m['id'] ? 'checked' : '' ?>>
                                    <div class="card border-2 p-3 payment-card">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi <?= $m['icon'] ?> fs-5 text-primary"></i>
                                            <div>
                                                <div class="fw-semibold small"><?= $m['name'] ?></div>
                                                <div class="text-muted" style="font-size:.7rem"><?= $m['desc'] ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <label class="form-label fw-semibold">Order Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Special instructions for delivery..."><?= old('notes') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top:80px">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Order Summary</div>
                    <div class="card-body p-0">
                        <?php foreach ($cart as $item): ?>
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary-subtle text-primary rounded-pill"><?= $item['qty'] ?></span>
                                <span class="small"><?= esc(truncate_text($item['name'], 30)) ?></span>
                            </div>
                            <span class="fw-semibold small">৳<?= number_format($item['price'] * $item['qty'], 2) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Subtotal</span>
                            <span>৳<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <?php if ($tax > 0): ?>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Tax</span>
                            <span>৳<?= number_format($tax, 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($shipping > 0): ?>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Shipping</span>
                            <span>৳<?= number_format($shipping, 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($discount > 0): ?>
                        <div class="d-flex justify-content-between mb-2 small text-success">
                            <span>Discount</span>
                            <span>−৳<?= number_format($discount, 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span class="fs-5 text-primary">৳<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                            <i class="bi bi-lock me-2"></i>Place Order — ৳<?= number_format($total, 2) ?>
                        </button>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                <i class="bi bi-shield-lock me-1"></i>Your payment info is secure
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.querySelectorAll('.payment-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.payment-card').forEach(c => c.classList.remove('border-primary', 'bg-primary-subtle'));
        if (this.checked) {
            this.closest('.payment-method').querySelector('.payment-card').classList.add('border-primary', 'bg-primary-subtle');
        }
    });
    if (radio.checked) {
        radio.closest('.payment-method').querySelector('.payment-card').classList.add('border-primary', 'bg-primary-subtle');
    }
});
</script>
<?= $this->endSection() ?>
