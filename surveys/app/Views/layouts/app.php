<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> — SurveyStack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <?= $this->renderSection('styles') ?>
</head>
<body class="app-layout">
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/dashboard" class="brand">
                <div class="brand-icon">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <span class="brand-name">SurveyStack</span>
            </a>
        </div>

        <div class="sidebar-org">
            <div class="org-avatar"><?= strtoupper(substr(session('tenant_name') ?? 'O', 0, 2)) ?></div>
            <div class="org-info">
                <div class="org-name"><?= esc(session('tenant_name')) ?></div>
                <div class="org-role"><?= ucfirst(session('user_role')) ?></div>
            </div>
        </div>

        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="/dashboard" class="nav-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/surveys" class="nav-link <?= str_starts_with(uri_string(), 'surveys') ? 'active' : '' ?>">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Surveys</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/team" class="nav-link <?= uri_string() === 'team' ? 'active' : '' ?>">
                    <i class="bi bi-people"></i>
                    <span>Team</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/billing" class="nav-link <?= uri_string() === 'billing' ? 'active' : '' ?>">
                    <i class="bi bi-credit-card"></i>
                    <span>Billing</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/settings" class="nav-link <?= uri_string() === 'settings' ? 'active' : '' ?>">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <a href="/surveys/create" class="btn btn-primary btn-sm w-100 mb-3">
                <i class="bi bi-plus-lg me-1"></i>New Survey
            </a>
            <div class="user-menu dropdown">
                <button class="user-menu-btn dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="user-avatar"><?= strtoupper(substr(session('user_name') ?? 'U', 0, 1)) ?></div>
                    <div class="user-info">
                        <div class="user-name"><?= esc(session('user_name')) ?></div>
                        <div class="user-email"><?= esc(session('user_email')) ?></div>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-up">
                    <li><a class="dropdown-item" href="/settings"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/auth/logout"><i class="bi bi-box-arrow-left me-2"></i>Sign Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Bar -->
        <div class="topbar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-right">
                <a href="/surveys/create" class="btn btn-primary btn-sm d-lg-none">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>
        </div>

        <div class="page-content">
            <!-- Flash Messages -->
            <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?= esc(session('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i><?= esc(session('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
