<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-card">
    <div class="auth-card-header">
        <h1>Welcome back</h1>
        <p>Sign in to your SurveyStack account</p>
    </div>

    <?php if (session()->has('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach (session('errors') as $e): ?>
        <div><i class="bi bi-exclamation-circle me-1"></i><?= esc($e) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form action="/auth/login" method="POST" class="auth-form">
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label">Email address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>"
                    placeholder="you@company.com" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label d-flex justify-content-between">
                Password
                <a href="/auth/forgot-password" class="text-primary small">Forgot password?</a>
            </label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                <button type="button" class="btn btn-outline-secondary toggle-password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me for 30 days</label>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
        </button>
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="/auth/register">Start free trial</a>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.previousElementSibling;
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
});
</script>

<?= $this->endSection() ?>
