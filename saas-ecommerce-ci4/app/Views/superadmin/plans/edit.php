<?= $this->extend('layouts/superadmin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Plan: <?= esc($plan['name']) ?></h4>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= site_url('superadmin/plans') ?>">Plans</a></li>
                <li class="breadcrumb-item active"><?= esc($plan['name']) ?></li>
            </ol>
        </div>
        <a href="<?= site_url('superadmin/plans') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="post" action="<?= site_url('superadmin/plans/' . $plan['id'] . '/update') ?>">
                <?= csrf_field() ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Plan Details</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Plan Name</label>
                                <input type="text" name="name" class="form-control" value="<?= esc(old('name', $plan['name'])) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control bg-light" value="<?= esc($plan['slug']) ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" name="price" class="form-control" step="0.01" min="0"
                                           value="<?= old('price', $plan['price']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Billing Cycle</label>
                                <select name="billing_cycle" class="form-select">
                                    <option value="monthly" <?= old('billing_cycle', $plan['billing_cycle']) === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                    <option value="yearly"  <?= old('billing_cycle', $plan['billing_cycle']) === 'yearly'  ? 'selected' : '' ?>>Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Products</label>
                                <input type="number" name="max_products" class="form-control" value="<?= old('max_products', $plan['max_products']) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Orders/mo</label>
                                <input type="number" name="max_orders" class="form-control" value="<?= old('max_orders', $plan['max_orders']) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Staff</label>
                                <input type="number" name="max_staff" class="form-control" value="<?= old('max_staff', $plan['max_staff']) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Storage (GB)</label>
                                <input type="number" name="storage_gb" class="form-control" value="<?= old('storage_gb', $plan['storage_gb']) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="<?= old('sort_order', $plan['sort_order']) ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                           <?= old('is_active', $plan['is_active']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 fw-semibold pt-3">Features</div>
                    <div class="card-body">
                        <div class="row g-2">
                            <?php
                            $featureList = [
                                'feat_analytics'        => ['Analytics Dashboard', 'bi-graph-up'],
                                'feat_custom_domain'    => ['Custom Domain', 'bi-globe'],
                                'feat_api'              => ['API Access', 'bi-code-slash'],
                                'feat_email_support'    => ['Email Support', 'bi-envelope'],
                                'feat_chat_support'     => ['Chat Support', 'bi-chat-dots'],
                                'feat_dedicated_support'=> ['Dedicated Support', 'bi-headset'],
                            ];
                            $featureKeyMap = [
                                'feat_analytics'        => 'analytics',
                                'feat_custom_domain'    => 'custom_domain',
                                'feat_api'              => 'api_access',
                                'feat_email_support'    => 'email_support',
                                'feat_chat_support'     => 'chat_support',
                                'feat_dedicated_support'=> 'dedicated_support',
                            ];
                            foreach ($featureList as $key => [$label, $icon]):
                                $featKey = $featureKeyMap[$key];
                                $isChecked = old($key) !== null ? old($key) : ($plan['features'][$featKey] ?? false);
                            ?>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="<?= $key ?>" id="<?= $key ?>"
                                           <?= $isChecked ? 'checked' : '' ?>>
                                    <label class="form-check-label small" for="<?= $key ?>">
                                        <i class="bi <?= $icon ?> me-1"></i><?= $label ?>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Update Plan
                    </button>
                    <a href="<?= site_url('superadmin/plans') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
