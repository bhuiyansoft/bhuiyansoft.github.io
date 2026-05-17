<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Attendance</h1>
        <p class="page-subtitle"><?= date('l, F d, Y', strtotime($date)) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/attendance/report') ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-bar-chart me-1"></i>Monthly Report
        </a>
        <a href="<?= base_url('admin/attendance/mark') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-clock me-1"></i>Mark Attendance
        </a>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5"><i class="bi bi-check-circle-fill" style="color:#059669"></i></div>
            <div><div class="stat-value"><?= $stats['present'] ?></div><div class="stat-label">Present</div></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7"><i class="bi bi-clock-fill" style="color:#d97706"></i></div>
            <div><div class="stat-value"><?= $stats['late'] ?></div><div class="stat-label">Late</div></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2"><i class="bi bi-x-circle-fill" style="color:#dc2626"></i></div>
            <div><div class="stat-value"><?= $stats['absent'] ?></div><div class="stat-label">Absent</div></div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control form-control-sm" value="<?= esc($date) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Department</label>
                <select name="dept_id" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= $deptId == $dept['id'] ? 'selected' : '' ?>><?= esc($dept['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
        </form>
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
                        <th>Department</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($attendance)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">No attendance records for this date.</td></tr>
                    <?php else: ?>
                    <?php foreach ($attendance as $att): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <?php if ($att['photo']): ?>
                                    <img src="<?= base_url('uploads/'.$att['photo']) ?>" class="emp-avatar" alt="">
                                <?php else: ?>
                                    <div class="emp-avatar"><?= strtoupper(substr($att['full_name'],0,2)) ?></div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-600 small"><?= esc($att['full_name']) ?></div>
                                    <div class="text-muted" style="font-size:0.72rem"><?= esc($att['emp_code']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="small text-muted"><?= esc($att['department_name'] ?? '-') ?></td>
                        <td><?= $att['check_in'] ? '<span class="text-success fw-600">'.substr($att['check_in'],0,5).'</span>' : '<span class="text-muted">-</span>' ?></td>
                        <td><?= $att['check_out'] ? '<span class="text-danger fw-600">'.substr($att['check_out'],0,5).'</span>' : '<span class="text-muted">-</span>' ?></td>
                        <td><?= $att['total_hours'] ? $att['total_hours'].'h' : '-' ?></td>
                        <td>
                            <?php
                            $stColors = ['present'=>'success','absent'=>'danger','late'=>'warning','half_day'=>'info','holiday'=>'primary','weekend'=>'secondary'];
                            $sc = $stColors[$att['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> text-capitalize"><?= ucfirst(str_replace('_',' ',$att['status'])) ?></span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?= base_url('admin/attendance/edit/'.$att['id']) ?>" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
