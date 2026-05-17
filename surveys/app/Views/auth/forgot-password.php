<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-card">
    <div class="auth-card-header">
        <div class="auth-icon"><i class="bi bi-key"></i></div>
        <h1>Reset your password</h1>
        <p>Enter your email and we'll send you reset instructions</p>
    </div>

    <?php if (session()->has('success')): ?>
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i><?= esc(session('success')) ?>
    </div>
    <?php endif; ?>

    <form action="/auth/forgot-password" method="POST" class="auth-form">
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label">Email address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="you@company.com" required autofocus>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-send me-2"></i>Send Reset Link
        </button>
    </form>

    <div class="auth-footer">
        <a href="/auth/login"><i class="bi bi-arrow-left me-1"></i>Back to sign in</a>
    </div>
</div>

<?= $this->endSection() ?>
