<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Leave Management</h1>
        <p class="page-subtitle">Manage leave requests and allocations</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/leaves/types') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-tags me-1"></i>Leave Types
        </a>
        <a href="<?= base_url('admin/leaves/allocations') ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-person-badge me-1"></i>Allocations
        </a>
        <a href="<?= base_url('admin/leaves/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Apply Leave
        </a>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7"><i class="bi bi-hourglass-split" style="color:#d97706"></i></div>
            <div><div class="stat-value"><?= $stats['pending'] ?></div><div class="stat-label">Pending Requests</div></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5"><i class="bi bi-check-circle-fill" style="color:#059669"></i></div>
            <div><div class="stat-value"><?= $stats['approved'] ?></div><div class="stat-label">Approved This Year</div></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2"><i class="bi bi-x-circle-fill" style="color:#dc2626"></i></div>
            <div><div class="stat-value"><?= $stats['rejected'] ?></div><div class="stat-label">Rejected This Year</div></div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= base_url('admin/leaves') ?>" class="btn btn-sm <?= !$filter ? 'btn-primary' : 'btn-outline-secondary' ?>">All</a>
            <a href="<?= base_url('admin/leaves?status=pending') ?>" class="btn btn-sm <?= $filter === 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' ?>">Pending</a>
            <a href="<?= base_url('admin/leaves?status=approved') ?>" class="btn btn-sm <?= $filter === 'approved' ? 'btn-success' : 'btn-outline-success' ?>">Approved</a>
            <a href="<?= base_url('admin/leaves?status=rejected') ?>" class="btn btn-sm <?= $filter === 'rejected' ? 'btn-danger' : 'btn-outline-danger' ?>">Rejected</a>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover datatable mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                        <th>Applied</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($leaves)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">No leave requests found.</td></tr>
                    <?php else: ?>
                    <?php foreach ($leaves as $leave): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-600 small"><?= esc($leave['employee_name']) ?></div>
                            <div class="text-muted" style="font-size:0.72rem"><?= esc($leave['emp_code']) ?> &mdash; <?= esc($leave['department_name'] ?? '') ?></div>
                        </td>
                        <td>
                            <span class="badge badge-pill" style="background:<?= esc($leave['leave_color']) ?>22;color:<?= esc($leave['leave_color']) ?>">
                                <?= esc($leave['leave_type_name']) ?>
                            </span>
                        </td>
                        <td class="small"><?= date('M d, Y', strtotime($leave['from_date'])) ?></td>
                        <td class="small"><?= date('M d, Y', strtotime($leave['to_date'])) ?></td>
                        <td><span class="fw-700"><?= $leave['total_days'] ?></span></td>
                        <td class="small text-muted"><?= date('M d', strtotime($leave['created_at'])) ?></td>
                        <td>
                            <?php $stColor = ['pending'=>'warning','approved'=>'success','rejected'=>'danger','cancelled'=>'secondary']; ?>
                            <span class="badge bg-<?= $stColor[$leave['status']] ?? 'secondary' ?>-subtle text-<?= $stColor[$leave['status']] ?? 'secondary' ?> text-capitalize">
                                <?= ucfirst($leave['status']) ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="<?= base_url('admin/leaves/'.$leave['id']) ?>" class="btn btn-sm btn-light">View</a>
                                <?php if ($leave['status'] === 'pending'): ?>
                                <form method="POST" action="<?= base_url('admin/leaves/approve/'.$leave['id']) ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" title="Reject"
                                    onclick="rejectLeave(<?= $leave['id'] ?>)">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title">Reject Leave Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <label class="form-label">Reason for Rejection</label>
                    <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function rejectLeave(id) {
    document.getElementById('rejectForm').action = `<?= base_url('admin/leaves/reject/') ?>${id}`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
<?= $this->endSection() ?>
