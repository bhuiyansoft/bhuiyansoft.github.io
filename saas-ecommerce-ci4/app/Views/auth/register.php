<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Store | SaaSCommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/auth.css" rel="stylesheet">
</head>
<body class="auth-page">
<div class="auth-container" style="max-width: 520px">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="bi bi-shop-window text-primary fs-1"></i>
            </div>
            <h2 class="fw-bold">Create Your Store</h2>
            <p class="text-muted">Start selling online in minutes</p>
        </div>

        <?php if ($errors = session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('auth/register') ?>">
            <?= csrf_field() ?>

            <h6 class="text-muted fw-semibold text-uppercase small mb-3"><i class="bi bi-shop me-2"></i>Store Information</h6>

            <div class="mb-3">
                <label class="form-label">Store Name <span class="text-danger">*</span></label>
                <input type="text" name="store_name" class="form-control" placeholder="My Awesome Store"
                       value="<?= old('store_name') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Subdomain <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text" name="subdomain" class="form-control" placeholder="mystore"
                           value="<?= old('subdomain') ?>" id="subdomain-input" required>
                    <span class="input-group-text bg-light text-muted">.saascommerce.com</span>
                </div>
                <div class="form-text">Only letters, numbers, and hyphens. e.g. my-store</div>
            </div>

            <div class="mb-4">
                <label class="form-label">Select Plan <span class="text-danger">*</span></label>
                <div class="row g-2">
                    <?php foreach ($plans as $plan): ?>
                    <div class="col-6">
                        <label class="plan-option <?= $plan['price'] == 0 ? 'recommended' : '' ?>">
                            <input type="radio" name="plan_id" value="<?= $plan['id'] ?>"
                                   <?= (old('plan_id') == $plan['id'] || ($plan['slug'] === 'starter' && !old('plan_id'))) ? 'checked' : '' ?>>
                            <div class="plan-card">
                                <div class="plan-name fw-semibold"><?= esc($plan['name']) ?></div>
                                <div class="plan-price">
                                    <?php if ($plan['price'] == 0): ?>
                                        <span class="text-success fw-bold">Free</span>
                                    <?php else: ?>
                                        <span class="fw-bold">৳<?= number_format($plan['price']) ?></span>
                                        <span class="text-muted small">/mo</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($plan['price'] == 0): ?>
                                    <span class="badge bg-success-subtle text-success small">30 day trial</span>
                                <?php endif; ?>
                            </div>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <h6 class="text-muted fw-semibold text-uppercase small mb-3"><i class="bi bi-person me-2"></i>Account Information</h6>

            <div class="mb-3">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Your full name"
                       value="<?= old('name') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com"
                       value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required minlength="8">
                <div class="password-strength mt-1" id="pwd-strength"></div>
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirm" class="form-control" placeholder="Repeat password" required>
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="terms" required>
                <label class="form-check-label small" for="terms">
                    I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-rocket-takeoff me-2"></i>Create My Store
            </button>
        </form>

        <div class="text-center mt-3">
            <p class="text-muted">Already have an account?
                <a href="<?= site_url('auth/login') ?>" class="text-primary fw-semibold">Sign in</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-generate subdomain from store name
document.querySelector('[name="store_name"]').addEventListener('input', function() {
    const sub = document.getElementById('subdomain-input');
    if (!sub.dataset.manual) {
        sub.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }
});
document.getElementById('subdomain-input').addEventListener('input', function() {
    this.dataset.manual = 'true';
});
// Password strength
document.querySelector('[name="password"]').addEventListener('input', function() {
    const el = document.getElementById('pwd-strength');
    const len = this.value.length;
    let cls = '', txt = '';
    if (len === 0) { el.innerHTML = ''; return; }
    if (len < 6) { cls = 'text-danger'; txt = 'Weak'; }
    else if (len < 10) { cls = 'text-warning'; txt = 'Fair'; }
    else { cls = 'text-success'; txt = 'Strong'; }
    el.innerHTML = `<small class="${cls}"><i class="bi bi-shield me-1"></i>${txt}</small>`;
});
</script>
</body>
</html>
