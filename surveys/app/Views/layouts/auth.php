<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sign In') ?> — SurveyStack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body class="auth-layout">
    <div class="auth-container">
        <div class="auth-brand">
            <a href="/" class="brand-link">
                <div class="brand-icon large">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <span class="brand-name">SurveyStack</span>
            </a>
        </div>

        <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= esc(session('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i><?= esc(session('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
