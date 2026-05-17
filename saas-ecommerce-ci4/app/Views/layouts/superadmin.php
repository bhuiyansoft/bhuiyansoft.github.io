<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Super Admin') ?> | SaaSCommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/admin.css" rel="stylesheet">
    <style>
        .sidebar { background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%); }
        .sidebar-brand { border-bottom-color: rgba(255,255,255,0.1); }
        .brand-name { color: #c7d2fe; }
        .nav-label { color: #818cf8; }
        .nav-item { color: #c7d2fe !important; }
        .nav-item:hover, .nav-item.active { background: rgba(99,102,241,0.3) !important; color: #fff !important; }
        .topbar { border-bottom-color: #e5e7eb; }
        .sidebar-badge { background: #4f46e5; color: #fff; font-size: 0.65rem; padding: 2px 6px; border-radius: 10px; }
    </style>
</head>
<body class="admin-layout">

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <span class="brand-icon text-indigo-300"><i class="bi bi-cloud-fill text-white fs-4"></i></span>
        </div>
        <div class="brand-text">
            <span class="brand-name fw-bold">SaaSCommerce</span>
            <span class="sidebar-badge">Super Admin</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <span class="nav-label">Overview</span>
            <a href="<?= site_url('superadmin/dashboard') ?>" class="nav-item <?= uri_string() === 'superadmin/dashboard' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="<?= site_url('superadmin/revenue') ?>" class="nav-item <?= uri_string() === 'superadmin/revenue' ? 'active' : '' ?>">
                <i class="bi bi-currency-dollar"></i> Revenue
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-label">Management</span>
            <a href="<?= site_url('superadmin/tenants') ?>" class="nav-item <?= str_starts_with(uri_string(), 'superadmin/tenants') ? 'active' : '' ?>">
                <i class="bi bi-buildings"></i> Tenants
            </a>
            <a href="<?= site_url('superadmin/plans') ?>" class="nav-item <?= str_starts_with(uri_string(), 'superadmin/plans') ? 'active' : '' ?>">
                <i class="bi bi-layers"></i> Plans
            </a>
            <a href="<?= site_url('superadmin/users') ?>" class="nav-item <?= uri_string() === 'superadmin/users' ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Users
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= site_url('auth/logout') ?>" class="nav-item" style="color:#f87171!important">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</aside>

<div class="main-content" id="main-content">
    <header class="topbar">
        <div class="topbar-left">
            <button class="btn btn-sm btn-light sidebar-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="ms-3 fw-semibold text-muted d-none d-md-inline"><?= esc($title ?? '') ?></span>
        </div>
        <div class="topbar-right">
            <div class="dropdown">
                <button class="btn btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="avatar-sm bg-indigo-600" style="background:#4f46e5">
                        <?= strtoupper(substr($currentUser['name'] ?? 'S', 0, 1)) ?>
                    </div>
                    <span class="d-none d-md-inline"><?= esc($currentUser['name'] ?? '') ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><h6 class="dropdown-header">Super Admin</h6></li>
                    <li><a class="dropdown-item text-danger" href="<?= site_url('auth/logout') ?>"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main class="page-content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mx-3 mt-3">
                <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3">
                <i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="/assets/js/admin.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
