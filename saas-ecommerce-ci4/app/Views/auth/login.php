<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SaaSCommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/auth.css" rel="stylesheet">
</head>
<body class="auth-page">
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="bi bi-cloud-fill text-primary fs-1"></i>
            </div>
            <h2 class="fw-bold">SaaSCommerce</h2>
            <p class="text-muted">Sign in to your account</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if ($errors = session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('auth/login') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com"
                           value="<?= old('email') ?>" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label class="form-label fw-semibold">Password</label>
                    <a href="<?= site_url('auth/forgot-password') ?>" class="text-primary small">Forgot password?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required id="password-field">
                    <button type="button" class="btn btn-light border" onclick="togglePass()">
                        <i class="bi bi-eye" id="eye-icon"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="auth-divider"><span>or</span></div>

        <div class="text-center">
            <p class="text-muted">Don't have an account?
                <a href="<?= site_url('auth/register') ?>" class="text-primary fw-semibold">Create your store</a>
            </p>
        </div>

        <!-- Demo credentials -->
        <div class="demo-credentials mt-3">
            <p class="text-muted small text-center mb-2"><i class="bi bi-info-circle me-1"></i>Demo Credentials</p>
            <div class="row g-2">
                <div class="col-6">
                    <div class="demo-card" onclick="fillDemo('superadmin@saas.com')">
                        <span class="badge bg-purple mb-1">Super Admin</span>
                        <div class="small">superadmin@saas.com</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="demo-card" onclick="fillDemo('admin@demo.com')">
                        <span class="badge bg-primary mb-1">Store Admin</span>
                        <div class="small">admin@demo.com</div>
                    </div>
                </div>
            </div>
            <p class="text-muted small text-center mt-2">Password: <code>password</code></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePass() {
    const f = document.getElementById('password-field');
    const i = document.getElementById('eye-icon');
    if (f.type === 'password') { f.type = 'text'; i.className = 'bi bi-eye-slash'; }
    else { f.type = 'password'; i.className = 'bi bi-eye'; }
}
function fillDemo(email) {
    document.querySelector('[name="email"]').value = email;
    document.querySelector('[name="password"]').value = 'password';
}
</script>
</body>
</html>
