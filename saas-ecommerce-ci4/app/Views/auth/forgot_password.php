<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | SaaSCommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .auth-card { border-radius: 1.25rem; box-shadow: 0 20px 60px rgba(0,0,0,.25); }
        .brand-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #667eea, #764ba2);
                      border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">

<div class="w-100" style="max-width: 440px">
    <div class="text-center mb-4">
        <a href="/" class="text-decoration-none">
            <div class="brand-icon text-white mx-auto mb-3">
                <i class="bi bi-cloud-fill"></i>
            </div>
            <h5 class="text-white fw-bold mb-0">SaaSCommerce</h5>
        </a>
    </div>

    <div class="card auth-card border-0">
        <div class="card-body p-4 p-md-5">
            <h4 class="fw-bold mb-1">Forgot your password?</h4>
            <p class="text-muted small mb-4">Enter your email and we'll send you a reset link.</p>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/auth/forgot-password" novalidate>
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>
                        <input type="email" name="email"
                               class="form-control border-start-0 ps-0 <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                               placeholder="you@example.com"
                               value="<?= esc(old('email')) ?>"
                               required autofocus>
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= $errors['email'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-send me-2"></i>Send Reset Link
                </button>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <a href="/auth/login" class="text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Back to Login
                </a>
            </div>
        </div>
    </div>

    <p class="text-center text-white-50 small mt-3">
        Don't have an account?
        <a href="/auth/register" class="text-white">Sign up free</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
