<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Store') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/store.css" rel="stylesheet">
</head>
<body>

<!-- Top bar -->
<div class="bg-primary text-white py-1" style="font-size:.82rem">
    <div class="container d-flex justify-content-between">
        <span><i class="bi bi-telephone me-1"></i> +880 1700-000000</span>
        <span><i class="bi bi-envelope me-1"></i> support@store.com</span>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary fs-4" href="/shop">
            <i class="bi bi-bag-heart-fill me-1"></i>ShopNow
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/shop">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/shop?sort=popular">Popular</a></li>
                <li class="nav-item"><a class="nav-link" href="/shop?sort=newest">New Arrivals</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <form action="/shop/search" method="get" class="d-flex">
                    <div class="input-group input-group-sm">
                        <input type="search" name="q" class="form-control" placeholder="Search products..." value="<?= esc($this->request->getGet('q') ?? '') ?>">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
                <a href="/shop/cart" class="btn btn-outline-primary btn-sm position-relative">
                    <i class="bi bi-cart3 fs-5"></i>
                    <?php
                    $cartCount = array_sum(array_column(session()->get('cart') ?? [], 'qty'));
                    if ($cartCount > 0):
                    ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-badge">
                        <?= $cartCount ?>
                    </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show m-0 rounded-0" role="alert">
    <div class="container"><?= session()->getFlashdata('success') ?></div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show m-0 rounded-0" role="alert">
    <div class="container"><?= session()->getFlashdata('error') ?></div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Page Content -->
<?= $this->renderSection('content') ?>

<!-- Footer -->
<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-bag-heart-fill text-primary me-2"></i>ShopNow</h5>
                <p class="text-muted small">Your one-stop shop for quality products. Fast delivery, easy returns, and 24/7 support.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="btn btn-sm btn-outline-light"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-light"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-light"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
            <div class="col-md-2">
                <h6 class="fw-semibold mb-3">Shop</h6>
                <ul class="list-unstyled small text-muted">
                    <li><a href="/shop" class="text-muted text-decoration-none">All Products</a></li>
                    <li><a href="/shop?sort=popular" class="text-muted text-decoration-none">Popular</a></li>
                    <li><a href="/shop?sort=newest" class="text-muted text-decoration-none">New Arrivals</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h6 class="fw-semibold mb-3">Support</h6>
                <ul class="list-unstyled small text-muted">
                    <li><a href="/contact" class="text-muted text-decoration-none">Contact Us</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Track Order</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold mb-3">Newsletter</h6>
                <p class="text-muted small">Subscribe for the latest deals and offers.</p>
                <div class="input-group">
                    <input type="email" class="form-control form-control-sm" placeholder="Your email">
                    <button class="btn btn-primary btn-sm">Subscribe</button>
                </div>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 small text-muted">
            <span>&copy; <?= date('Y') ?> ShopNow. All rights reserved.</span>
            <span>Powered by <a href="/" class="text-primary text-decoration-none">SaaSCommerce</a></span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/store.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
