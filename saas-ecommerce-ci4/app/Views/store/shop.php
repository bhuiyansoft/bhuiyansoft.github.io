<?= $this->extend('layouts/store') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-2 py-4 bg-light border-end" style="min-height:100vh">
            <h6 class="fw-bold mb-3 px-3">Categories</h6>
            <div class="list-group list-group-flush">
                <a href="/shop" class="list-group-item list-group-item-action <?= empty($filters['category_id']) ? 'active' : '' ?>">
                    All Products
                </a>
                <?php foreach ($categories as $cat): ?>
                <a href="/shop/category/<?= esc($cat['slug']) ?>"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'active' : '' ?>">
                    <?= esc($cat['name']) ?>
                    <span class="badge bg-secondary rounded-pill"><?= $cat['product_count'] ?></span>
                </a>
                <?php endforeach; ?>
            </div>

            <hr class="mx-3">
            <h6 class="fw-bold mb-3 px-3">Price Range</h6>
            <form method="get" action="/shop" class="px-3">
                <?php if (!empty($filters['category_id'])): ?>
                    <input type="hidden" name="category" value="<?= $filters['category_id'] ?>">
                <?php endif; ?>
                <div class="mb-2">
                    <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min ৳" value="<?= $filters['min_price'] ?? '' ?>">
                </div>
                <div class="mb-2">
                    <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max ৳" value="<?= $filters['max_price'] ?? '' ?>">
                </div>
                <button type="submit" class="btn btn-sm btn-primary w-100">Apply</button>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-10 py-4 px-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <?php if (!empty($category)): ?>
                        <h4 class="fw-bold mb-0"><?= esc($category['name']) ?></h4>
                    <?php elseif (!empty($filters['search'])): ?>
                        <h4 class="fw-bold mb-0">Results for "<?= esc($filters['search']) ?>"</h4>
                    <?php else: ?>
                        <h4 class="fw-bold mb-0">All Products</h4>
                    <?php endif; ?>
                    <p class="text-muted small mb-0"><?= count($products) ?> products</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Sort:</span>
                    <select class="form-select form-select-sm" style="width:auto" onchange="location.href=this.value">
                        <?php
                        $sorts = ['newest'=>'Newest','popular'=>'Popular','price_asc'=>'Price: Low to High','price_desc'=>'Price: High to Low'];
                        foreach ($sorts as $val => $label): ?>
                            <option value="/shop?sort=<?= $val ?><?= !empty($filters['category_id']) ? '&category='.$filters['category_id'] : '' ?>"
                                    <?= ($filters['sort'] ?? 'newest') === $val ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <?php if (empty($products)): ?>
            <div class="text-center py-5">
                <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">No products found</h5>
                <a href="/shop" class="btn btn-primary mt-2">Browse All Products</a>
            </div>
            <?php else: ?>
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
                <?php foreach ($products as $product):
                    $price    = $product['sale_price'] ?? $product['price'];
                    $hasOffer = !empty($product['sale_price']);
                    $discount = $hasOffer ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100) : 0;
                ?>
                <div class="col">
                    <div class="card product-card h-100 border-0 shadow-sm position-relative">
                        <?php if ($hasOffer): ?>
                            <span class="badge-sale">-<?= $discount ?>%</span>
                        <?php endif; ?>
                        <?php if ($product['is_featured']): ?>
                            <span class="badge-featured"><i class="bi bi-star-fill"></i></span>
                        <?php endif; ?>

                        <a href="/shop/product/<?= esc($product['slug']) ?>">
                            <?php if ($product['image']): ?>
                                <img src="<?= base_url($product['image']) ?>" class="card-img-top product-img" alt="<?= esc($product['name']) ?>">
                            <?php else: ?>
                                <div class="product-img-placeholder"><i class="bi bi-image"></i></div>
                            <?php endif; ?>
                        </a>

                        <div class="card-body d-flex flex-column p-3">
                            <?php if ($product['category_name']): ?>
                                <span class="badge bg-light text-muted border mb-1 align-self-start" style="font-size:.7rem">
                                    <?= esc($product['category_name']) ?>
                                </span>
                            <?php endif; ?>
                            <h6 class="mb-2 fw-semibold flex-grow-1">
                                <a href="/shop/product/<?= esc($product['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= esc($product['name']) ?>
                                </a>
                            </h6>
                            <div class="mb-2">
                                <span class="fw-bold text-primary fs-6">৳<?= number_format($price, 2) ?></span>
                                <?php if ($hasOffer): ?>
                                    <span class="text-muted text-decoration-line-through ms-1 small">৳<?= number_format($product['price'], 2) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($product['stock'] > 0): ?>
                                <button onclick="addToCart(<?= $product['id'] ?>)"
                                        data-product-id="<?= $product['id'] ?>"
                                        class="btn btn-outline-primary btn-sm add-to-cart-btn">
                                    <i class="bi bi-cart-plus me-1"></i>Add to Cart
                                </button>
                            <?php else: ?>
                                <button class="btn btn-outline-secondary btn-sm" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($pager)): ?>
            <div class="mt-4 d-flex justify-content-center">
                <?= $pager->links() ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
