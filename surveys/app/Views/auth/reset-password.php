<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-card">
    <div class="auth-card-header">
        <div class="auth-icon"><i class="bi bi-shield-lock"></i></div>
        <h1>Set new password</h1>
        <p>Enter and confirm your new password below</p>
    </div>

    <?php if (session()->has('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach (session('errors') as $e): ?>
        <div><i class="bi bi-exclamation-circle me-1"></i><?= esc($e) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form action="/auth/reset-password/<?= esc($token) ?>" method="POST" class="auth-form">
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label">New Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required minlength="8" autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password_confirm" class="form-control" placeholder="Repeat password" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-check-circle me-2"></i>Update Password
        </button>
    </form>

    <div class="auth-footer">
        <a href="/auth/login"><i class="bi bi-arrow-left me-1"></i>Back to sign in</a>
    </div>
</div>

<?= $this->endSection() ?>
