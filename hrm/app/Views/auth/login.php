<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | BhuiyanSoft HRM System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4f46e5 100%);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%; max-width: 440px;
        }

        .login-header {
            background: linear-gradient(135deg, #1e1b4b, #4f46e5);
            padding: 40px 40px 30px;
            text-align: center;
            color: white;
        }

        .login-logo {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; font-weight: 800;
            margin: 0 auto 16px;
            backdrop-filter: blur(10px);
        }

        .login-body { padding: 36px 40px; }

        .form-floating label { color: #64748b; }
        .form-floating .form-control {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            font-size: 0.95rem;
        }
        .form-floating .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none; border-radius: 12px;
            padding: 14px; font-size: 1rem;
            font-weight: 600; letter-spacing: 0.02em;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79,70,229,0.4);
        }

        .demo-creds {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            font-size: 0.8rem;
        }

        .demo-creds h6 { color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }

        .floating-shapes { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape" style="width:300px;height:300px;top:-100px;right:-80px;animation-delay:0s"></div>
        <div class="shape" style="width:200px;height:200px;bottom:-60px;left:-60px;animation-delay:2s"></div>
        <div class="shape" style="width:150px;height:150px;top:40%;left:10%;animation-delay:4s"></div>
    </div>

    <div class="container px-3" style="position:relative;z-index:1">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width:440px">

                <div class="login-card">
                    <!-- Header -->
                    <div class="login-header">
                        <div class="login-logo">H</div>
                        <h3 class="fw-700 mb-1">BhuiyanSoft HRM</h3>
                        <p class="mb-0 opacity-75 small">Human Resource Management System</p>
                    </div>

                    <!-- Body -->
                    <div class="login-body">
                        <h5 class="fw-700 mb-1">Welcome back!</h5>
                        <p class="text-muted mb-4 small">Sign in to your account to continue</p>

                        <!-- Flash Messages -->
                        <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger rounded-3 py-2 small" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <?= esc(session()->getFlashdata('error')) ?>
                        </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success rounded-3 py-2 small" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <?= esc(session()->getFlashdata('success')) ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= base_url('login') ?>">
                            <?= csrf_field() ?>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="credential" name="credential"
                                    placeholder="Email or Username" value="<?= old('credential', 'admin') ?>" required autofocus>
                                <label for="credential"><i class="bi bi-person me-2"></i>Email or Username</label>
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required>
                                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                                <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y me-2 text-muted p-1"
                                    onclick="togglePassword()" style="z-index:10;background:none;border:none">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label text-muted small" for="remember">Remember me</label>
                                </div>
                                <a href="<?= base_url('forgot-password') ?>" class="text-primary small text-decoration-none fw-500">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </button>
                        </form>

                        <!-- Demo Credentials -->
                        <div class="demo-creds mt-4">
                            <h6 class="mb-2">Demo Credentials</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="fw-600 text-dark">Super Admin</div>
                                    <div class="text-muted">admin / password</div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-600 text-dark">Employee</div>
                                    <div class="text-muted">john.smith / password</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-center text-white mt-4 small opacity-75">
                    &copy; <?= date('Y') ?> BhuiyanSoft. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function togglePassword() {
        const pwd = document.getElementById('password');
        const eye = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            eye.className = 'bi bi-eye-slash';
        } else {
            pwd.type = 'password';
            eye.className = 'bi bi-eye';
        }
    }
    </script>
</body>
</html>
