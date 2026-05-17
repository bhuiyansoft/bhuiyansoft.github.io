<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= $title ?? 'HRM System' ?> | BhuiyanSoft HRM</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= base_url('assets/img/favicon.svg') ?>">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <!-- Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed: 70px;
            --topbar-height: 60px;
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --sidebar-bg: #1e1b4b;
            --sidebar-text: #c7d2fe;
            --sidebar-hover: #3730a3;
            --sidebar-active: #4f46e5;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f1f5f9;
            color: #334155;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            transition: width 0.3s ease;
            z-index: 1040;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed { width: var(--sidebar-collapsed); }

        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-brand .brand-logo {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white; font-weight: 700;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-name {
            font-size: 1.1rem; font-weight: 700;
            color: white; margin-left: 12px;
            white-space: nowrap; overflow: hidden;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .brand-name { opacity: 0; width: 0; margin: 0; }

        .sidebar-nav { flex: 1; padding: 16px 0; overflow-y: auto; overflow-x: hidden; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        .nav-section {
            padding: 6px 16px 4px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(199,210,254,0.4);
            white-space: nowrap; overflow: hidden;
        }

        .sidebar.collapsed .nav-section { opacity: 0; }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.2s;
            font-size: 0.875rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .sidebar-nav .nav-link:hover { background: var(--sidebar-hover); color: white; }
        .sidebar-nav .nav-link.active { background: var(--primary); color: white; }

        .sidebar-nav .nav-link .nav-icon {
            font-size: 1.1rem;
            width: 24px;
            flex-shrink: 0;
            text-align: center;
        }

        .sidebar-nav .nav-link .nav-text {
            margin-left: 12px;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .nav-link .nav-text { opacity: 0; width: 0; overflow: hidden; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: 10px; margin: 2px 8px; }

        .nav-badge {
            margin-left: auto;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 10px;
        }

        .sidebar.collapsed .nav-badge { display: none; }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .main-wrapper.expanded { margin-left: var(--sidebar-collapsed); }

        /* ===== TOPBAR ===== */
        .topbar {
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0; z-index: 1030;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .topbar-toggle {
            background: none; border: none;
            color: #64748b; font-size: 1.3rem;
            cursor: pointer; padding: 6px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .topbar-toggle:hover { background: #f1f5f9; }

        .breadcrumb { margin: 0; }
        .breadcrumb-item, .breadcrumb-item a { font-size: 0.8rem; color: #64748b; }
        .breadcrumb-item.active { color: #334155; }

        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }

        .topbar-icon-btn {
            background: none; border: none;
            width: 38px; height: 38px;
            border-radius: 10px;
            color: #64748b; font-size: 1.1rem;
            cursor: pointer; position: relative;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.2s;
        }

        .topbar-icon-btn:hover { background: #f1f5f9; }
        .notification-badge {
            position: absolute; top: 4px; right: 4px;
            width: 16px; height: 16px;
            background: #ef4444; color: white;
            font-size: 0.6rem; font-weight: 700;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        .topbar-divider { width: 1px; height: 30px; background: #e2e8f0; margin: 0 6px; }

        .topbar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }

        .topbar-user:hover { background: #f1f5f9; }

        .topbar-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 0.85rem;
        }

        .topbar-user-info { display: flex; flex-direction: column; }
        .topbar-user-name { font-size: 0.85rem; font-weight: 600; color: #334155; line-height: 1.2; }
        .topbar-user-role { font-size: 0.7rem; color: #94a3b8; text-transform: capitalize; }

        /* ===== PAGE CONTENT ===== */
        .page-content { padding: 24px; flex: 1; }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-title { font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0; }
        .page-subtitle { font-size: 0.85rem; color: #64748b; margin-top: 2px; }

        /* ===== CARDS ===== */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 16px 16px 0 0 !important;
            padding: 16px 20px;
        }

        .card-title { font-size: 1rem; font-weight: 600; color: #1e293b; margin: 0; }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            background: white;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-value { font-size: 1.8rem; font-weight: 700; color: #1e293b; line-height: 1; }
        .stat-label { font-size: 0.8rem; color: #64748b; margin-top: 4px; }
        .stat-trend { font-size: 0.75rem; margin-top: 6px; }

        /* ===== BADGES ===== */
        .badge-pill { border-radius: 20px; padding: 4px 12px; font-size: 0.72rem; font-weight: 600; }

        /* ===== ALERTS ===== */
        .alert { border-radius: 12px; border: none; font-size: 0.875rem; }

        /* ===== BUTTONS ===== */
        .btn { border-radius: 10px; font-weight: 500; font-size: 0.875rem; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

        /* ===== TABLE ===== */
        .table { font-size: 0.875rem; }
        .table th { font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .table-hover tbody tr:hover { background: #f8fafc; }

        /* ===== FORM ===== */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            font-size: 0.875rem;
            padding: 10px 14px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }

        .form-label { font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 6px; }

        /* ===== EMPLOYEE AVATAR ===== */
        .emp-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: #e0e7ff;
            display: inline-flex; align-items: center; justify-content: center;
            color: var(--primary); font-weight: 700; font-size: 0.8rem;
        }

        /* ===== STATUS BADGES ===== */
        .status-active    { background: #d1fae5; color: #065f46; }
        .status-inactive  { background: #fee2e2; color: #991b1b; }
        .status-pending   { background: #fef3c7; color: #92400e; }
        .status-approved  { background: #d1fae5; color: #065f46; }
        .status-rejected  { background: #fee2e2; color: #991b1b; }
        .status-processed { background: #e0e7ff; color: #3730a3; }
        .status-paid      { background: #d1fae5; color: #065f46; }
        .status-draft     { background: #f1f5f9; color: #64748b; }

        /* ===== FOOTER ===== */
        .main-footer {
            padding: 16px 24px;
            background: white;
            border-top: 1px solid #e2e8f0;
            font-size: 0.8rem;
            color: #94a3b8;
            text-align: center;
        }

        /* ===== MOBILE ===== */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
        }

        @media (max-width: 991.98px) {
            .sidebar { left: calc(-1 * var(--sidebar-width)); }
            .sidebar.mobile-open { left: 0; }
            .sidebar-overlay.show { display: block; }
            .main-wrapper { margin-left: 0 !important; }
        }

        /* ===== PRINT ===== */
        @media print {
            .sidebar, .topbar, .main-footer, .no-print { display: none !important; }
            .main-wrapper { margin-left: 0 !important; }
            .page-content { padding: 0; }
        }
    </style>
</head>
<body>

<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===== SIDEBAR ===== -->
<nav class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-logo">H</div>
        <span class="brand-name">BhuiyanSoft HRM</span>
    </div>

    <!-- Navigation -->
    <div class="sidebar-nav">

        <div class="nav-section">Main</div>
        <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= uri_string() === 'admin/dashboard' || uri_string() === 'admin' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-grid-1x2-fill"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>

        <div class="nav-section">Human Resources</div>
        <a href="<?= base_url('admin/employees') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/employees') ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-people-fill"></i></span>
            <span class="nav-text">Employees</span>
        </a>
        <a href="<?= base_url('admin/departments') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/departments') ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-diagram-3-fill"></i></span>
            <span class="nav-text">Departments</span>
        </a>
        <a href="<?= base_url('admin/designations') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/designations') ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-award-fill"></i></span>
            <span class="nav-text">Designations</span>
        </a>

        <div class="nav-section">Workforce</div>
        <a href="<?= base_url('admin/attendance') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/attendance') ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-clock-fill"></i></span>
            <span class="nav-text">Attendance</span>
        </a>
        <a href="<?= base_url('admin/leaves') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/leaves') ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-calendar-check-fill"></i></span>
            <span class="nav-text">Leave Management</span>
        </a>
        <a href="<?= base_url('admin/payroll') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/payroll') ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-cash-stack"></i></span>
            <span class="nav-text">Payroll</span>
        </a>

        <div class="nav-section">Talent</div>
        <a href="<?= base_url('admin/recruitment') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/recruitment') ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-person-plus-fill"></i></span>
            <span class="nav-text">Recruitment</span>
        </a>
        <a href="<?= base_url('admin/performance') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/performance') ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-graph-up-arrow"></i></span>
            <span class="nav-text">Performance</span>
        </a>

        <div class="nav-section">Analytics</div>
        <a href="<?= base_url('admin/reports') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/reports') ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-bar-chart-fill"></i></span>
            <span class="nav-text">Reports</span>
        </a>

        <?php if (in_array(session()->get('role'), ['super_admin', 'admin'])): ?>
        <div class="nav-section">System</div>
        <a href="<?= base_url('admin/settings') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/settings') ? 'active' : '' ?>">
            <span class="nav-icon"><i class="bi bi-gear-fill"></i></span>
            <span class="nav-text">Settings</span>
        </a>
        <?php endif; ?>

    </div>

    <!-- Sidebar Footer -->
    <div style="padding: 12px 16px; border-top: 1px solid rgba(255,255,255,0.05);">
        <a href="<?= base_url('logout') ?>" class="nav-link text-danger" onclick="return confirm('Are you sure you want to logout?')">
            <span class="nav-icon"><i class="bi bi-box-arrow-left"></i></span>
            <span class="nav-text">Logout</span>
        </a>
    </div>
</nav>

<!-- ===== MAIN WRAPPER ===== -->
<div class="main-wrapper" id="mainWrapper">

    <!-- ===== TOPBAR ===== -->
    <header class="topbar">
        <button class="topbar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>

        <nav aria-label="breadcrumb" class="ms-3 d-none d-md-block">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Home</a></li>
                <li class="breadcrumb-item active"><?= $title ?? 'Dashboard' ?></li>
            </ol>
        </nav>

        <div class="topbar-right">
            <!-- Search -->
            <button class="topbar-icon-btn" title="Search" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="bi bi-search"></i>
            </button>

            <!-- Notifications -->
            <div class="dropdown">
                <button class="topbar-icon-btn" data-bs-toggle="dropdown" id="notifBtn">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge d-none" id="notifCount">0</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3" style="width: 340px; max-height: 420px; overflow-y: auto;">
                    <div class="px-3 py-2 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold">Notifications</h6>
                        <button class="btn btn-sm btn-link text-muted p-0" id="markAllRead">Mark all read</button>
                    </div>
                    <div id="notifList">
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-bell-slash fs-3"></i>
                            <p class="mb-0 mt-2 small">No new notifications</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="topbar-divider"></div>

            <!-- User Menu -->
            <div class="dropdown">
                <a href="#" class="topbar-user text-decoration-none" data-bs-toggle="dropdown">
                    <?php
                    $photo    = session()->get('photo');
                    $fullName = session()->get('full_name') ?? 'User';
                    $initials = strtoupper(substr($fullName, 0, 1)) . strtoupper(substr(strstr($fullName, ' '), 1, 1));
                    ?>
                    <?php if ($photo): ?>
                        <img src="<?= base_url('uploads/' . $photo) ?>" alt="<?= $fullName ?>" class="topbar-avatar">
                    <?php else: ?>
                        <div class="topbar-avatar"><?= $initials ?></div>
                    <?php endif; ?>
                    <div class="topbar-user-info d-none d-md-flex">
                        <span class="topbar-user-name"><?= esc($fullName) ?></span>
                        <span class="topbar-user-role"><?= str_replace('_', ' ', session()->get('role') ?? '') ?></span>
                    </div>
                    <i class="bi bi-chevron-down text-muted small ms-1 d-none d-md-block"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-1">
                    <li class="px-3 py-2 border-bottom">
                        <div class="fw-600 text-dark"><?= esc($fullName) ?></div>
                        <small class="text-muted"><?= esc(session()->get('email')) ?></small>
                    </li>
                    <li><a class="dropdown-item" href="<?= base_url('admin/profile') ?>"><i class="bi bi-person me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('admin/settings') ?>"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>" onclick="return confirm('Logout?')"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- ===== PAGE CONTENT ===== -->
    <main class="page-content">

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ((array)session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Page Body -->
        <?= $this->renderSection('content') ?>

    </main>

    <!-- Footer -->
    <footer class="main-footer">
        &copy; <?= date('Y') ?> BhuiyanSoft HRM System. All rights reserved. Built with CodeIgniter 4 &amp; Bootstrap 5.
    </footer>
</div>

<!-- ===== SEARCH MODAL ===== -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-0">
                <div class="input-group border-0">
                    <span class="input-group-text bg-white border-0 ps-4"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="globalSearch" class="form-control border-0 py-3 fs-5" placeholder="Search employees, departments...">
                    <button class="btn border-0 pe-4" data-bs-dismiss="modal"><i class="bi bi-x-lg text-muted"></i></button>
                </div>
                <div id="searchResults" class="px-3 pb-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ===== SIDEBAR TOGGLE =====
const sidebar       = document.getElementById('sidebar');
const mainWrapper   = document.getElementById('mainWrapper');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const toggle        = document.getElementById('sidebarToggle');

function isMobile() { return window.innerWidth < 992; }

toggle.addEventListener('click', () => {
    if (isMobile()) {
        sidebar.classList.toggle('mobile-open');
        sidebarOverlay.classList.toggle('show');
    } else {
        sidebar.classList.toggle('collapsed');
        mainWrapper.classList.toggle('expanded');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }
});

sidebarOverlay.addEventListener('click', () => {
    sidebar.classList.remove('mobile-open');
    sidebarOverlay.classList.remove('show');
});

// Restore sidebar state
if (!isMobile() && localStorage.getItem('sidebarCollapsed') === 'true') {
    sidebar.classList.add('collapsed');
    mainWrapper.classList.add('expanded');
}

// ===== AUTO-DISMISS ALERTS =====
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(a);
        bsAlert.close();
    });
}, 5000);

// ===== DATATABLES =====
$(function() {
    if ($('.datatable').length) {
        $('.datatable').DataTable({
            responsive: true,
            pageLength: 25,
            language: { search: '', searchPlaceholder: 'Search...', lengthMenu: '_MENU_ per page' }
        });
    }

    // Select2
    if ($('.select2').length) {
        $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });
    }

    // Flatpickr
    flatpickr('.datepicker', { dateFormat: 'Y-m-d', allowInput: true });
    flatpickr('.datetimepicker', { dateFormat: 'Y-m-d H:i', enableTime: true, allowInput: true });
    flatpickr('.timepicker', { enableTime: true, noCalendar: true, dateFormat: 'H:i', allowInput: true });
});

// ===== NOTIFICATIONS =====
function loadNotifications() {
    fetch('<?= base_url('admin/notifications') ?>')
        .then(r => r.json())
        .then(data => {
            const list = document.getElementById('notifList');
            const badge = document.getElementById('notifCount');
            const unread = data.notifications.filter(n => !n.is_read).length;

            if (unread > 0) {
                badge.textContent = unread > 9 ? '9+' : unread;
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }

            if (data.notifications.length > 0) {
                list.innerHTML = data.notifications.map(n => `
                    <a href="${n.link || '#'}" class="dropdown-item py-2 ${!n.is_read ? 'bg-light' : ''}">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <div class="fw-600 small">${n.title}</div>
                                <div class="text-muted" style="font-size:0.75rem">${n.message}</div>
                            </div>
                        </div>
                    </a>`).join('');
            }
        }).catch(() => {});
}

document.getElementById('markAllRead')?.addEventListener('click', () => {
    fetch('<?= base_url('admin/notifications/mark-read') ?>', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'} })
        .then(() => loadNotifications());
});

// Load every 60 seconds
loadNotifications();
setInterval(loadNotifications, 60000);

// ===== GLOBAL SEARCH =====
let searchTimeout;
document.getElementById('globalSearch')?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    const results = document.getElementById('searchResults');
    if (q.length < 2) { results.innerHTML = ''; return; }
    searchTimeout = setTimeout(() => {
        results.innerHTML = '<p class="text-muted small py-2">Searching...</p>';
    }, 200);
});
</script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
