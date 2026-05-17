<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">Reports</h1><p class="page-subtitle">Generate and view HR reports</p></div>
</div>

<div class="row g-4">
    <div class="col-sm-6 col-lg-3">
        <a href="<?= base_url('admin/reports/employees') ?>" class="text-decoration-none">
            <div class="card h-100 text-center p-4 hover-shadow">
                <div class="stat-icon mx-auto mb-3" style="background:#e0e7ff;width:64px;height:64px">
                    <i class="bi bi-people-fill" style="color:#4f46e5;font-size:1.5rem"></i>
                </div>
                <h6 class="fw-700">Employee Report</h6>
                <p class="text-muted small mb-0">View employee list with details, filters by department and status</p>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= base_url('admin/reports/attendance') ?>" class="text-decoration-none">
            <div class="card h-100 text-center p-4">
                <div class="stat-icon mx-auto mb-3" style="background:#d1fae5;width:64px;height:64px">
                    <i class="bi bi-clock-fill" style="color:#059669;font-size:1.5rem"></i>
                </div>
                <h6 class="fw-700">Attendance Report</h6>
                <p class="text-muted small mb-0">Monthly attendance summary with present, absent, late records</p>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= base_url('admin/reports/payroll') ?>" class="text-decoration-none">
            <div class="card h-100 text-center p-4">
                <div class="stat-icon mx-auto mb-3" style="background:#fce7f3;width:64px;height:64px">
                    <i class="bi bi-cash-stack" style="color:#be185d;font-size:1.5rem"></i>
                </div>
                <h6 class="fw-700">Payroll Report</h6>
                <p class="text-muted small mb-0">Monthly payroll summary with gross, deductions, and net pay</p>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= base_url('admin/reports/leave') ?>" class="text-decoration-none">
            <div class="card h-100 text-center p-4">
                <div class="stat-icon mx-auto mb-3" style="background:#fef3c7;width:64px;height:64px">
                    <i class="bi bi-calendar-check-fill" style="color:#d97706;font-size:1.5rem"></i>
                </div>
                <h6 class="fw-700">Leave Report</h6>
                <p class="text-muted small mb-0">Annual leave usage and balance by employee and type</p>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="<?= base_url('admin/reports/headcount') ?>" class="text-decoration-none">
            <div class="card h-100 text-center p-4">
                <div class="stat-icon mx-auto mb-3" style="background:#f0fdf4;width:64px;height:64px">
                    <i class="bi bi-bar-chart-fill" style="color:#16a34a;font-size:1.5rem"></i>
                </div>
                <h6 class="fw-700">Headcount Report</h6>
                <p class="text-muted small mb-0">Workforce analysis by department, gender, and employment type</p>
            </div>
        </a>
    </div>
</div>

<?= $this->endSection() ?>
