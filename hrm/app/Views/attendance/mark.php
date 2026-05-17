<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Mark Attendance</h1>
        <p class="page-subtitle">Bulk attendance marking for <?= date('l, F d, Y', strtotime($date)) ?></p>
    </div>
    <a href="<?= base_url('admin/attendance') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<form method="POST" action="<?= base_url('admin/attendance/bulk-store') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="date" value="<?= esc($date) ?>">

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" id="dateFilter" class="form-control form-control-sm" value="<?= esc($date) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Department</label>
                    <select id="deptFilter" class="form-select form-select-sm" onchange="window.location.href='<?= base_url('admin/attendance/mark?date='.$date.'&dept_id=') ?>'+this.value">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['id'] ?>" <?= $deptId == $dept['id'] ? 'selected' : '' ?>><?= esc($dept['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="markAll('present')">Mark All Present</button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="markAll('absent')">Mark All Absent</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0">Employees (<?= count($employees) ?>)</h6>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-save me-1"></i>Save Attendance
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:50px">#</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $i => $emp): ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= $i + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($emp['photo']): ?>
                                        <img src="<?= base_url('uploads/'.$emp['photo']) ?>" class="emp-avatar" alt="">
                                    <?php else: ?>
                                        <div class="emp-avatar"><?= strtoupper(substr($emp['first_name'],0,1).substr($emp['last_name'],0,1)) ?></div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-600 small"><?= esc($emp['first_name'].' '.$emp['last_name']) ?></div>
                                        <div class="text-muted" style="font-size:0.72rem"><?= esc($emp['employee_id']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="small text-muted"><?= esc($emp['department_name'] ?? '-') ?></td>
                            <td>
                                <input type="time" name="employees[<?= $emp['id'] ?>][check_in]"
                                    class="form-control form-control-sm" style="width:120px"
                                    value="<?= $emp['check_in'] ?? '09:00' ?>">
                            </td>
                            <td>
                                <input type="time" name="employees[<?= $emp['id'] ?>][check_out]"
                                    class="form-control form-control-sm" style="width:120px"
                                    value="<?= $emp['check_out'] ?? '17:00' ?>">
                            </td>
                            <td>
                                <select name="employees[<?= $emp['id'] ?>][status]" class="form-select form-select-sm status-select" style="width:120px">
                                    <?php
                                    $statuses = ['present', 'absent', 'late', 'half_day', 'holiday', 'weekend'];
                                    $current  = $emp['att_status'] ?? 'present';
                                    foreach ($statuses as $s):
                                    ?>
                                    <option value="<?= $s ?>" <?= $current === $s ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $s)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Save All Attendance
            </button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function markAll(status) {
    document.querySelectorAll('.status-select').forEach(s => s.value = status);
}

document.getElementById('dateFilter').addEventListener('change', function() {
    const dept = document.getElementById('deptFilter').value;
    window.location.href = `<?= base_url('admin/attendance/mark?dept_id=') ?>${dept}&date=${this.value}`;
});
</script>
<?= $this->endSection() ?>
