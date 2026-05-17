<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Coupons</h4>
            <p class="text-muted mb-0">Create discount codes for your customers</p>
        </div>
        <a href="<?= site_url('admin/coupons/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Create Coupon
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Code</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Min Order</th>
                            <th>Usage</th>
                            <th>Expires</th>
                            <th>Status</th>
                            <th class="text-end px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($coupons)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-ticket-perforated fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted">No coupons created yet.</p>
                                    <a href="<?= site_url('admin/coupons/create') ?>" class="btn btn-primary btn-sm">Create first coupon</a>
                                </td>
                            </tr>
                        <?php else: ?>
                        <?php foreach ($coupons as $coupon): ?>
                        <?php
                            $isExpired = $coupon['expires_at'] && strtotime($coupon['expires_at']) < time();
                            $isFull    = $coupon['max_uses'] && $coupon['used_count'] >= $coupon['max_uses'];
                        ?>
                        <tr>
                            <td class="px-3">
                                <code class="fw-bold fs-6"><?= esc($coupon['code']) ?></code>
                            </td>
                            <td>
                                <span class="badge bg-<?= $coupon['type'] === 'percentage' ? 'info' : 'warning text-dark' ?>-subtle text-<?= $coupon['type'] === 'percentage' ? 'info' : 'warning' ?>">
                                    <?= ucfirst($coupon['type']) ?>
                                </span>
                            </td>
                            <td class="fw-semibold">
                                <?= $coupon['type'] === 'percentage'
                                    ? $coupon['value'] . '%'
                                    : esc($currentTenant['currency_symbol']) . number_format($coupon['value'], 2) ?>
                            </td>
                            <td class="text-muted small">
                                <?= $coupon['min_order'] > 0 ? esc($currentTenant['currency_symbol']) . number_format($coupon['min_order'], 2) : '—' ?>
                            </td>
                            <td>
                                <span class="text-muted small"><?= $coupon['used_count'] ?><?= $coupon['max_uses'] ? '/' . $coupon['max_uses'] : '' ?> used</span>
                            </td>
                            <td class="text-muted small">
                                <?= $coupon['expires_at'] ? date('d M Y', strtotime($coupon['expires_at'])) : 'Never' ?>
                                <?php if ($isExpired): ?><span class="badge bg-danger-subtle text-danger ms-1">Expired</span><?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$coupon['is_active'] || $isExpired || $isFull): ?>
                                    <span class="badge bg-secondary-subtle text-secondary border">Inactive</span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end px-3">
                                <a href="<?= site_url('admin/coupons/' . $coupon['id'] . '/delete') ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Delete coupon <?= esc($coupon['code']) ?>?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
