<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle"><?= date('l, F d, Y') ?> &mdash; Good <?= date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') ?>, <?= esc(explode(' ', session()->get('full_name'))[0]) ?>!</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/attendance/mark') ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-clock me-1"></i>Mark Attendance
        </a>
        <a href="<?= base_url('admin/employees/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus me-1"></i>Add Employee
        </a>
    </div>
</div>

<!-- ===== STAT CARDS ===== -->
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0e7ff">
                <i class="bi bi-people-fill" style="color:#4f46e5"></i>
            </div>
            <div>
                <div class="stat-value"><?= $employeeStats['total'] ?></div>
                <div class="stat-label">Total Employees</div>
                <div class="stat-trend text-success"><i class="bi bi-arrow-up-short"></i><?= $employeeStats['newMonth'] ?> this month</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5">
                <i class="bi bi-check-circle-fill" style="color:#059669"></i>
            </div>
            <div>
                <div class="stat-value"><?= $attendanceStats['present'] ?></div>
                <div class="stat-label">Present Today</div>
                <div class="stat-trend text-warning"><i class="bi bi-clock"></i> <?= $attendanceStats['late'] ?> late</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7">
                <i class="bi bi-calendar-check-fill" style="color:#d97706"></i>
            </div>
            <div>
                <div class="stat-value"><?= $leaveStats['pending'] ?></div>
                <div class="stat-label">Pending Leaves</div>
                <div class="stat-trend text-success"><?= $leaveStats['approved'] ?> approved this year</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fce7f3">
                <i class="bi bi-cash-stack" style="color:#be185d"></i>
            </div>
            <div>
                <div class="stat-value">৳<?= number_format(($payrollSummary['net_salary'] ?? 0) / 1000, 1) ?>K</div>
                <div class="stat-label">Net Payroll This Month</div>
                <div class="stat-trend text-muted"><?= $payrollSummary['total_records'] ?? 0 ?> processed</div>
            </div>
        </div>
    </div>
</div>

<!-- ===== CHARTS ROW ===== -->
<div class="row g-4 mb-4">
    <!-- Attendance Trend -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0"><i class="bi bi-graph-up me-2 text-primary"></i>Attendance Trend (Last 7 Days)</h6>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Department Headcount -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Headcount by Department</h6>
            </div>
            <div class="card-body d-flex align-items-center">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ===== TABLES ROW ===== -->
<div class="row g-4">
    <!-- Recent Employees -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0"><i class="bi bi-person-plus me-2 text-primary"></i>Recent Employees</h6>
                <a href="<?= base_url('admin/employees') ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Employee</th>
                                <th>Joined</th>
                                <th class="pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentEmployees as $emp): ?>
                            <tr>
                                <td class="ps-4">
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
                                <td><span class="small text-muted"><?= date('M d', strtotime($emp['join_date'])) ?></span></td>
                                <td class="pe-4">
                                    <a href="<?= base_url('admin/employees/'.$emp['id']) ?>" class="btn btn-sm btn-light">View</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Leave Requests -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0"><i class="bi bi-calendar-x me-2 text-warning"></i>Pending Leave Requests</h6>
                <a href="<?= base_url('admin/leaves?status=pending') ?>" class="btn btn-sm btn-outline-warning">View All</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pendingLeaves)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-check-circle fs-2"></i>
                    <p class="mt-2 mb-0">No pending leave requests</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Employee</th>
                                <th>Type</th>
                                <th>Days</th>
                                <th class="pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingLeaves as $leave): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-600 small"><?= esc($leave['employee_name']) ?></div>
                                    <div class="text-muted" style="font-size:0.72rem"><?= esc($leave['emp_code']) ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-pill" style="background:<?= esc($leave['leave_color']) ?>22;color:<?= esc($leave['leave_color']) ?>"><?= esc($leave['leave_type_name']) ?></span>
                                </td>
                                <td><span class="fw-600"><?= $leave['total_days'] ?></span></td>
                                <td class="pe-4">
                                    <a href="<?= base_url('admin/leaves/'.$leave['id']) ?>" class="btn btn-sm btn-light">Review</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
// ===== ATTENDANCE TREND CHART =====
const trendData = <?= json_encode($attendanceTrend) ?>;
const trendLabels = trendData.map(d => {
    const date = new Date(d.date);
    return date.toLocaleDateString('en', {weekday: 'short', month: 'short', day: 'numeric'});
});

new Chart(document.getElementById('attendanceChart'), {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [
            {
                label: 'Present',
                data: trendData.map(d => d.present),
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,0.1)',
                fill: true, tension: 0.4, borderWidth: 2, pointRadius: 4,
            },
            {
                label: 'Absent',
                data: trendData.map(d => d.absent),
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.1)',
                fill: true, tension: 0.4, borderWidth: 2, pointRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});

// ===== DEPT CHART =====
const deptData = <?= json_encode($deptChart) ?>;
const deptColors = ['#4f46e5','#7c3aed','#a855f7','#ec4899','#f97316','#eab308','#22c55e','#06b6d4'];

new Chart(document.getElementById('deptChart'), {
    type: 'doughnut',
    data: {
        labels: deptData.map(d => d.name),
        datasets: [{
            data: deptData.map(d => d.count),
            backgroundColor: deptColors,
            borderWidth: 2, borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } }
        }
    }
});
</script>
<?= $this->endSection() ?>
