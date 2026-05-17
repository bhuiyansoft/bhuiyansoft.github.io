<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | BhuiyanSoft HRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; background:linear-gradient(135deg,#1e1b4b 0%,#4f46e5 100%); display:flex; align-items:center; justify-content:center; font-family:'Inter',-apple-system,sans-serif; }
        .card { border-radius:24px; border:none; box-shadow:0 25px 60px rgba(0,0,0,0.3); max-width:420px; width:100%; }
        .form-control { border-radius:12px; }
        .btn { border-radius:12px; }
    </style>
</head>
<body>
<div class="container px-3">
    <div class="card mx-auto">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;background:#e0e7ff">
                    <i class="bi bi-lock-fill text-primary fs-4"></i>
                </div>
                <h4 class="fw-700">Forgot Password</h4>
                <p class="text-muted small">Enter your email to receive a reset link</p>
            </div>
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger small"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success small"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <form method="POST" action="<?= base_url('forgot-password') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            </form>
            <div class="text-center mt-3">
                <a href="<?= base_url('login') ?>" class="text-muted small"><i class="bi bi-arrow-left me-1"></i>Back to Login</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
