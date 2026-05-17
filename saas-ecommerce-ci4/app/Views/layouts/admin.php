<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin') ?> | <?= esc($currentTenant['name'] ?? 'Store Admin') ?></title>
    <link rel="icon" type="image/png" href="/assets/img/favicon.png">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/admin.css" rel="stylesheet">
</head>
<body class="admin-layout">

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <?php if (!empty($currentTenant['logo'])): ?>
                <img src="<?= base_url($currentTenant['logo']) ?>" alt="Logo" height="36">
            <?php else: ?>
                <span class="brand-icon"><i class="bi bi-shop"></i></span>
            <?php endif; ?>
        </div>
        <div class="brand-text">
            <span class="brand-name"><?= esc($currentTenant['name'] ?? 'Store') ?></span>
            <span class="brand-plan badge bg-primary-subtle text-primary"><?= esc($currentTenant['status'] ?? '') ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <span class="nav-label">Main</span>
            <a href="<?= site_url('admin/dashboard') ?>" class="nav-item <?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-label">Catalog</span>
            <a href="<?= site_url('admin/products') ?>" class="nav-item <?= str_starts_with(uri_string(), 'admin/products') ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i> Products
            </a>
            <a href="<?= site_url('admin/categories') ?>" class="nav-item <?= str_starts_with(uri_string(), 'admin/categories') ? 'active' : '' ?>">
                <i class="bi bi-tags"></i> Categories
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-label">Sales</span>
            <a href="<?= site_url('admin/orders') ?>" class="nav-item <?= str_starts_with(uri_string(), 'admin/orders') ? 'active' : '' ?>">
                <i class="bi bi-bag-check"></i> Orders
            </a>
            <a href="<?= site_url('admin/customers') ?>" class="nav-item <?= str_starts_with(uri_string(), 'admin/customers') ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Customers
            </a>
            <a href="<?= site_url('admin/coupons') ?>" class="nav-item <?= str_starts_with(uri_string(), 'admin/coupons') ? 'active' : '' ?>">
                <i class="bi bi-ticket-perforated"></i> Coupons
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-label">Analytics</span>
            <a href="<?= site_url('admin/reports') ?>" class="nav-item <?= str_starts_with(uri_string(), 'admin/reports') ? 'active' : '' ?>">
                <i class="bi bi-graph-up"></i> Reports
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-label">Configuration</span>
            <a href="<?= site_url('admin/settings') ?>" class="nav-item <?= str_starts_with(uri_string(), 'admin/settings') ? 'active' : '' ?>">
                <i class="bi bi-gear"></i> Settings
            </a>
            <a href="<?= site_url('admin/profile') ?>" class="nav-item <?= str_starts_with(uri_string(), 'admin/profile') ? 'active' : '' ?>">
                <i class="bi bi-person-circle"></i> Profile
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= site_url('auth/logout') ?>" class="nav-item text-danger">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</aside>

<!-- Main Content -->
<div class="main-content" id="main-content">

    <!-- Top Navbar -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="btn btn-sm btn-light sidebar-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <nav aria-label="breadcrumb" class="ms-3 d-none d-md-block">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?= esc($title ?? '') ?></li>
                </ol>
            </nav>
        </div>
        <div class="topbar-right">
            <a href="/shop" target="_blank" class="btn btn-sm btn-outline-secondary me-2">
                <i class="bi bi-shop"></i> View Store
            </a>
            <div class="dropdown">
                <button class="btn btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="avatar-sm">
                        <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <span class="d-none d-md-inline"><?= esc($currentUser['name'] ?? '') ?></span>
                    <i class="bi bi-chevron-down small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><h6 class="dropdown-header"><?= esc($currentUser['email'] ?? '') ?></h6></li>
                    <li><a class="dropdown-item" href="<?= site_url('admin/profile') ?>"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="<?= site_url('admin/settings') ?>"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= site_url('auth/logout') ?>"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($errors = session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="/assets/js/admin.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
