<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-card">
    <div class="auth-card-header">
        <h1>Start for free</h1>
        <p>Create your SurveyStack account — no credit card required</p>
    </div>

    <?php if (session()->has('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach (session('errors') as $e): ?>
        <div><i class="bi bi-exclamation-circle me-1"></i><?= esc($e) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <form action="/auth/register" method="POST" class="auth-form">
        <?= csrf_field() ?>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Your Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?= esc(old('name')) ?>"
                        placeholder="John Smith" required autofocus>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Organization Name</label>
                    <input type="text" name="org_name" class="form-control" value="<?= esc(old('org_name')) ?>"
                        placeholder="Acme Inc." required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Work Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>"
                    placeholder="you@company.com" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required minlength="8">
                <button type="button" class="btn btn-outline-secondary toggle-password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password_confirm" class="form-control" placeholder="Repeat password" required>
            </div>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="terms" required>
            <label class="form-check-label" for="terms">
                I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-person-plus me-2"></i>Create Free Account
        </button>
    </form>

    <div class="features-list mt-3">
        <div class="feature-item"><i class="bi bi-check-circle-fill text-success me-2"></i>Free forever plan</div>
        <div class="feature-item"><i class="bi bi-check-circle-fill text-success me-2"></i>3 surveys, 100 responses</div>
        <div class="feature-item"><i class="bi bi-check-circle-fill text-success me-2"></i>No credit card required</div>
    </div>

    <div class="auth-footer">
        Already have an account? <a href="/auth/login">Sign in</a>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.previousElementSibling;
        const icon = this.querySelector('i');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    });
});
</script>

<?= $this->endSection() ?>
