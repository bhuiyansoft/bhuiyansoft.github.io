<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/landing.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="/">
            <i class="bi bi-cloud-fill me-2"></i>SaaSCommerce
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLanding">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarLanding">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="/pricing">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" href="/shop">Demo Store</a></li>
            </ul>
            <div class="d-flex gap-2">
                <a href="/auth/login" class="btn btn-outline-light btn-sm">Sign In</a>
                <a href="/auth/register" class="btn btn-warning btn-sm fw-semibold">Start Free Trial</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100 pt-5">
            <div class="col-lg-6 text-white">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2">
                    <i class="bi bi-lightning-charge me-1"></i>New: Multi-tenant SaaS Platform
                </span>
                <h1 class="display-4 fw-bold mb-4 lh-sm">
                    Build & Launch Your<br>
                    <span class="text-warning">Online Store</span><br>
                    in Minutes
                </h1>
                <p class="lead text-white-75 mb-4">
                    Complete SaaS e-commerce platform with multi-tenant support,
                    product management, order tracking, analytics, and more.
                    No coding required.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="/auth/register" class="btn btn-warning btn-lg fw-bold px-4">
                        <i class="bi bi-rocket-takeoff me-2"></i>Start for Free
                    </a>
                    <a href="/shop" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-play-circle me-2"></i>View Demo
                    </a>
                </div>
                <div class="d-flex gap-4 text-white-75">
                    <div class="text-center">
                        <div class="fw-bold fs-4">500+</div>
                        <div class="small">Stores</div>
                    </div>
                    <div class="vr opacity-25"></div>
                    <div class="text-center">
                        <div class="fw-bold fs-4">50K+</div>
                        <div class="small">Orders</div>
                    </div>
                    <div class="vr opacity-25"></div>
                    <div class="text-center">
                        <div class="fw-bold fs-4">99.9%</div>
                        <div class="small">Uptime</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-image-wrapper">
                    <!-- Dashboard mockup -->
                    <div class="mockup-window shadow-lg rounded-3 overflow-hidden" style="background:#1e293b">
                        <div class="mockup-titlebar d-flex align-items-center gap-2 px-3 py-2" style="background:#0f172a">
                            <span class="mockup-dot" style="background:#ef4444"></span>
                            <span class="mockup-dot" style="background:#f59e0b"></span>
                            <span class="mockup-dot" style="background:#22c55e"></span>
                            <span class="text-muted small ms-2" style="color:#64748b!important">admin.saascommerce.com</span>
                        </div>
                        <div class="p-3 text-white" style="font-size:.8rem">
                            <div class="row g-2 mb-3">
                                <div class="col-3"><div class="rounded p-2 text-center" style="background:#1d4ed8"><div class="fw-bold">৳48K</div><div style="font-size:.65rem;color:#93c5fd">Revenue</div></div></div>
                                <div class="col-3"><div class="rounded p-2 text-center" style="background:#065f46"><div class="fw-bold">128</div><div style="font-size:.65rem;color:#6ee7b7">Orders</div></div></div>
                                <div class="col-3"><div class="rounded p-2 text-center" style="background:#7c2d12"><div class="fw-bold">342</div><div style="font-size:.65rem;color:#fca5a5">Products</div></div></div>
                                <div class="col-3"><div class="rounded p-2 text-center" style="background:#4c1d95"><div class="fw-bold">89</div><div style="font-size:.65rem;color:#c4b5fd">Customers</div></div></div>
                            </div>
                            <div style="background:#0f172a;border-radius:6px;padding:8px">
                                <div style="color:#64748b;margin-bottom:6px;font-size:.7rem">Recent Orders</div>
                                <?php foreach (['ORD-001 · Rahim Ahmed · ৳2,500 · Delivered', 'ORD-002 · Karim Uddin · ৳1,800 · Processing', 'ORD-003 · Nasrin Begum · ৳3,200 · Pending'] as $row): ?>
                                <div class="d-flex justify-content-between mb-1 py-1" style="border-bottom:1px solid #1e293b">
                                    <span style="color:#94a3b8"><?= $row ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section id="features" class="py-6 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2">Features</span>
            <h2 class="fw-bold display-6">Everything You Need to Sell Online</h2>
            <p class="text-muted lead">Powerful tools for modern e-commerce businesses</p>
        </div>
        <div class="row g-4">
            <?php
            $features = [
                ['icon'=>'bi-shop-window','color'=>'primary','title'=>'Multi-Tenant Stores','desc'=>'Each business gets their own isolated store with unique subdomain and branding.'],
                ['icon'=>'bi-box-seam','color'=>'success','title'=>'Product Management','desc'=>'Add unlimited products with variants, images, pricing, and inventory tracking.'],
                ['icon'=>'bi-bag-check','color'=>'warning','title'=>'Order Management','desc'=>'Process orders, update status, generate invoices, and manage fulfillment.'],
                ['icon'=>'bi-people','color'=>'info','title'=>'Customer Management','desc'=>'Track customers, order history, lifetime value, and manage relationships.'],
                ['icon'=>'bi-graph-up','color'=>'danger','title'=>'Analytics & Reports','desc'=>'Sales charts, revenue tracking, top products, and customer insights.'],
                ['icon'=>'bi-ticket-perforated','color'=>'purple','title'=>'Coupons & Discounts','desc'=>'Create percentage or fixed discount coupons with usage limits and expiry.'],
                ['icon'=>'bi-layers','color'=>'indigo','title'=>'Subscription Plans','desc'=>'Multiple pricing tiers with feature gating for your SaaS model.'],
                ['icon'=>'bi-shield-check','color'=>'teal','title'=>'Role-Based Access','desc'=>'Admin, manager, and staff roles with granular permission control.'],
            ];
            foreach ($features as $f): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-<?= $f['color'] ?>-subtle text-<?= $f['color'] ?> mb-3">
                            <i class="bi <?= $f['icon'] ?> fs-4"></i>
                        </div>
                        <h5 class="fw-semibold mb-2"><?= $f['title'] ?></h5>
                        <p class="text-muted small mb-0"><?= $f['desc'] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Pricing Preview -->
<section class="py-6">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="badge bg-warning-subtle text-warning mb-2 px-3 py-2">Pricing</span>
            <h2 class="fw-bold display-6">Simple, Transparent Pricing</h2>
            <p class="text-muted lead">Start free, scale when you're ready</p>
        </div>
        <div class="row g-4 justify-content-center">
            <?php foreach ($plans as $i => $plan): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 <?= $i === 2 ? 'border-primary border-2' : '' ?>" style="<?= $i === 2 ? 'border:2px solid #0d6efd!important' : '' ?>">
                    <?php if ($i === 2): ?>
                        <div class="text-center py-2 bg-primary text-white" style="border-radius:calc(.5rem - 1px) calc(.5rem - 1px) 0 0;font-size:.8rem;font-weight:600">
                            MOST POPULAR
                        </div>
                    <?php endif; ?>
                    <div class="card-body p-4">
                        <h5 class="fw-bold"><?= esc($plan['name']) ?></h5>
                        <div class="mb-3">
                            <?php if ($plan['price'] == 0): ?>
                                <span class="display-5 fw-bold">Free</span>
                            <?php else: ?>
                                <span class="display-5 fw-bold">৳<?= number_format($plan['price'], 0) ?></span>
                                <span class="text-muted">/mo</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-muted small mb-4">
                            <div class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i><?= $plan['max_products'] < 0 ? 'Unlimited' : $plan['max_products'] ?> products</div>
                            <div class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i><?= $plan['max_orders'] < 0 ? 'Unlimited' : number_format($plan['max_orders']) ?> orders/mo</div>
                            <div class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i><?= $plan['max_staff'] < 0 ? 'Unlimited' : $plan['max_staff'] ?> staff users</div>
                            <div class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i><?= $plan['storage_gb'] ?>GB storage</div>
                        </div>
                        <a href="/auth/register?plan=<?= $plan['id'] ?>" class="btn <?= $i === 2 ? 'btn-primary' : 'btn-outline-primary' ?> w-100 fw-semibold">
                            <?= $plan['price'] == 0 ? 'Get Started Free' : 'Start Trial' ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="/pricing" class="btn btn-link text-muted">Compare all plans <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5" style="background:linear-gradient(135deg,#1e1b4b,#312e81)">
    <div class="container py-4 text-center text-white">
        <h2 class="fw-bold display-6 mb-3">Ready to Start Selling?</h2>
        <p class="lead text-white-75 mb-4">Join hundreds of businesses already growing with SaaSCommerce</p>
        <a href="/auth/register" class="btn btn-warning btn-lg fw-bold px-5">
            <i class="bi bi-rocket-takeoff me-2"></i>Create Your Free Store
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="fw-bold"><i class="bi bi-cloud-fill me-2 text-primary"></i>SaaSCommerce</div>
            <div class="text-muted small">&copy; <?= date('Y') ?> SaaSCommerce. All rights reserved.</div>
            <div class="d-flex gap-3 small">
                <a href="#" class="text-muted text-decoration-none">Privacy</a>
                <a href="#" class="text-muted text-decoration-none">Terms</a>
                <a href="/contact" class="text-muted text-decoration-none">Contact</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Navbar scroll effect
window.addEventListener('scroll', () => {
    const nav = document.getElementById('mainNav');
    nav.classList.toggle('navbar-scrolled', window.scrollY > 50);
});
</script>
</body>
</html>
