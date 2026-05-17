<?= $this->extend('layouts/store') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/shop">Shop</a></li>
            <?php if ($product['category_name']): ?>
                <li class="breadcrumb-item"><a href="/shop?category=<?= $product['category_id'] ?>"><?= esc($product['category_name']) ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active"><?= esc($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Product Image -->
        <div class="col-lg-5">
            <div class="position-relative">
                <?php if ($product['image']): ?>
                    <img src="<?= base_url($product['image']) ?>" alt="<?= esc($product['name']) ?>"
                         class="img-fluid rounded-3 w-100" style="max-height:480px;object-fit:cover"
                         id="main-image">
                <?php else: ?>
                    <div class="product-img-placeholder rounded-3 w-100" style="height:400px;font-size:6rem">
                        <i class="bi bi-image"></i>
                    </div>
                <?php endif; ?>
                <?php if ($product['sale_price']): ?>
                    <?php $disc = round((($product['price'] - $product['sale_price']) / $product['price']) * 100); ?>
                    <span class="position-absolute top-0 start-0 m-3 badge bg-danger fs-6 rounded-pill">-<?= $disc ?>%</span>
                <?php endif; ?>
                <?php if ($product['is_featured']): ?>
                    <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark">
                        <i class="bi bi-star-fill me-1"></i>Featured
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7">
            <?php if ($product['category_name']): ?>
                <span class="badge bg-light text-muted border mb-2"><?= esc($product['category_name']) ?></span>
            <?php endif; ?>

            <h1 class="fw-bold mb-2"><?= esc($product['name']) ?></h1>

            <?php if ($product['sku']): ?>
                <p class="text-muted small mb-3">SKU: <code><?= esc($product['sku']) ?></code></p>
            <?php endif; ?>

            <!-- Price -->
            <div class="mb-4">
                <?php if ($product['sale_price']): ?>
                    <span class="fs-2 fw-bold text-primary"><?= esc($tenant['currency_symbol']) ?><?= number_format($product['sale_price'], 2) ?></span>
                    <span class="fs-5 text-muted text-decoration-line-through ms-2"><?= esc($tenant['currency_symbol']) ?><?= number_format($product['price'], 2) ?></span>
                    <span class="ms-2 badge bg-danger">Save <?= esc($tenant['currency_symbol']) ?><?= number_format($product['price'] - $product['sale_price'], 2) ?></span>
                <?php else: ?>
                    <span class="fs-2 fw-bold text-primary"><?= esc($tenant['currency_symbol']) ?><?= number_format($product['price'], 2) ?></span>
                <?php endif; ?>
                <span class="text-muted small ms-2">/ <?= esc($product['unit']) ?></span>
            </div>

            <!-- Short Description -->
            <?php if ($product['short_description']): ?>
                <p class="text-muted mb-4"><?= esc($product['short_description']) ?></p>
            <?php endif; ?>

            <!-- Stock -->
            <div class="mb-4">
                <?php if ($product['stock'] > 10): ?>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                        <i class="bi bi-check-circle me-1"></i>In Stock (<?= $product['stock'] ?> available)
                    </span>
                <?php elseif ($product['stock'] > 0): ?>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">
                        <i class="bi bi-exclamation-circle me-1"></i>Only <?= $product['stock'] ?> left!
                    </span>
                <?php else: ?>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                        <i class="bi bi-x-circle me-1"></i>Out of Stock
                    </span>
                <?php endif; ?>
            </div>

            <!-- Add to Cart -->
            <?php if ($product['stock'] > 0): ?>
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="qty-control" style="border-radius:10px;overflow:hidden;border:2px solid #dee2e6">
                    <button type="button" class="qty-minus" style="padding:10px 16px;font-size:1.1rem">−</button>
                    <input type="number" id="qty-input" value="1" min="1" max="<?= $product['stock'] ?>"
                           style="border:none;width:60px;text-align:center;font-size:1.1rem;font-weight:700">
                    <button type="button" class="qty-plus" style="padding:10px 16px;font-size:1.1rem">+</button>
                </div>
                <button onclick="addToCart(<?= $product['id'] ?>, parseInt(document.getElementById('qty-input').value))"
                        data-product-id="<?= $product['id'] ?>"
                        class="btn btn-primary btn-lg px-5 add-to-cart-btn flex-grow-1">
                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                </button>
            </div>
            <?php else: ?>
            <button class="btn btn-secondary btn-lg px-5 mb-4" disabled>Out of Stock</button>
            <?php endif; ?>

            <!-- Meta Info -->
            <div class="border rounded-3 p-3 small text-muted">
                <div class="row g-2">
                    <?php if ($product['weight']): ?>
                    <div class="col-6">
                        <i class="bi bi-box me-1"></i><strong>Weight:</strong> <?= $product['weight'] ?>kg
                    </div>
                    <?php endif; ?>
                    <div class="col-6">
                        <i class="bi bi-bag me-1"></i><strong>Type:</strong> <?= ucfirst($product['type']) ?>
                    </div>
                    <div class="col-6">
                        <i class="bi bi-eye me-1"></i><strong>Views:</strong> <?= number_format($product['views']) ?>
                    </div>
                    <div class="col-6">
                        <i class="bi bi-star me-1"></i><strong>Sold:</strong> <?= number_format($product['total_sold']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Description -->
    <?php if ($product['description']): ?>
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-semibold pt-3">
                    <i class="bi bi-file-text me-2"></i>Product Description
                </div>
                <div class="card-body">
                    <div class="text-muted"><?= nl2br(esc($product['description'])) ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Related Products -->
    <?php if (!empty($related)): ?>
    <div class="mt-5">
        <h4 class="fw-bold mb-4">Related Products</h4>
        <div class="row row-cols-2 row-cols-md-4 g-3">
            <?php foreach ($related as $rp): ?>
            <div class="col">
                <div class="card product-card border-0 shadow-sm h-100">
                    <a href="/shop/product/<?= esc($rp['slug']) ?>">
                        <?php if ($rp['image']): ?>
                            <img src="<?= base_url($rp['image']) ?>" class="card-img-top" alt="<?= esc($rp['name']) ?>"
                                 style="height:160px;object-fit:cover">
                        <?php else: ?>
                            <div class="product-img-placeholder" style="height:160px;font-size:2.5rem"><i class="bi bi-image"></i></div>
                        <?php endif; ?>
                    </a>
                    <div class="card-body p-3">
                        <h6 class="mb-1 fw-semibold small">
                            <a href="/shop/product/<?= esc($rp['slug']) ?>" class="text-dark text-decoration-none"><?= esc($rp['name']) ?></a>
                        </h6>
                        <div class="fw-bold text-primary small">
                            <?= esc($tenant['currency_symbol']) ?><?= number_format($rp['sale_price'] ?? $rp['price'], 2) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
