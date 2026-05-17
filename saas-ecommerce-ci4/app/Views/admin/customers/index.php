<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Customers</h4>
            <p class="text-muted mb-0">Manage customer accounts</p>
        </div>
        <a href="<?= site_url('admin/customers/create') ?>" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i> Add Customer
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="get" class="d-flex gap-2 align-items-end">
                <input type="search" name="search" class="form-control form-control-sm" placeholder="Name, email, phone..."
                       value="<?= esc($filters['search'] ?? '') ?>" style="max-width:300px">
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                <a href="<?= site_url('admin/customers') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Customer</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Orders</th>
                            <th>Spent</th>
                            <th>Joined</th>
                            <th class="text-end px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)): ?>
                            <tr><td colspan="7" class="text-center py-5 text-muted">No customers found.</td></tr>
                        <?php else: ?>
                        <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td class="px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-primary-subtle text-primary fw-bold">
                                        <?= strtoupper(substr($customer['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?= esc($customer['name']) ?></div>
                                        <div class="text-muted small"><?= esc($customer['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted small"><?= esc($customer['phone'] ?? '—') ?></td>
                            <td class="text-muted small"><?= esc($customer['city'] ?? '—') ?></td>
                            <td><span class="badge bg-primary-subtle text-primary"><?= $customer['total_orders'] ?></span></td>
                            <td class="fw-semibold text-success small">
                                <?= esc($currentTenant['currency_symbol']) ?><?= number_format($customer['total_spent'], 2) ?>
                            </td>
                            <td class="text-muted small"><?= date('d M Y', strtotime($customer['created_at'])) ?></td>
                            <td class="text-end px-3">
                                <a href="<?= site_url('admin/customers/' . $customer['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (!empty($pager)): ?>
        <div class="card-footer bg-white border-0"><?= $pager->links() ?></div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
