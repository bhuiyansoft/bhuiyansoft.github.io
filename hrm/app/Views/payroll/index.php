<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Payroll</h1>
        <p class="page-subtitle">Manage employee salaries and payslips</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/payroll/report') ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-bar-chart me-1"></i>Annual Report
        </a>
        <a href="<?= base_url('admin/payroll/generate?month='.$month.'&year='.$year) ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-play-fill me-1"></i>Generate Payroll
        </a>
    </div>
</div>

<!-- Month Filter -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Month</label>
                <select name="month" class="form-select form-select-sm">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $month == $m ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$m,1)) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Year</label>
                <select name="year" class="form-select form-select-sm">
                    <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                    <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Apply</button>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<?php if ($summary): ?>
<div class="row g-3 mb-4">
    <div class="col-sm-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0e7ff"><i class="bi bi-people-fill" style="color:#4f46e5"></i></div>
            <div><div class="stat-value"><?= $summary['total_records'] ?></div><div class="stat-label">Employees</div></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fce7f3"><i class="bi bi-currency-dollar" style="color:#be185d"></i></div>
            <div><div class="stat-value">৳<?= number_format($summary['gross_salary']/1000,1) ?>K</div><div class="stat-label">Gross Payroll</div></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2"><i class="bi bi-dash-circle-fill" style="color:#dc2626"></i></div>
            <div><div class="stat-value">৳<?= number_format($summary['total_deductions']/1000,1) ?>K</div><div class="stat-label">Deductions</div></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5"><i class="bi bi-cash-coin" style="color:#059669"></i></div>
            <div><div class="stat-value">৳<?= number_format($summary['net_salary']/1000,1) ?>K</div><div class="stat-label">Net Payroll</div></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover datatable mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Department</th>
                        <th>Basic</th>
                        <th>Gross</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payroll)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-cash-stack fs-2 d-block mb-2"></i>
                            No payroll data for <?= date('F Y', mktime(0,0,0,$month,1,$year)) ?>.
                            <a href="<?= base_url('admin/payroll/generate?month='.$month.'&year='.$year) ?>">Generate now</a>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($payroll as $p): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-600 small"><?= esc($p['full_name']) ?></div>
                            <div class="text-muted" style="font-size:0.72rem"><?= esc($p['emp_code']) ?></div>
                        </td>
                        <td class="small text-muted"><?= esc($p['department_name'] ?? '-') ?></td>
                        <td>৳<?= number_format($p['basic_salary']) ?></td>
                        <td>৳<?= number_format($p['gross_salary']) ?></td>
                        <td class="text-danger">৳<?= number_format($p['total_deductions']) ?></td>
                        <td class="fw-700 text-success">৳<?= number_format($p['net_salary']) ?></td>
                        <td>
                            <?php $colors = ['draft'=>'secondary','processed'=>'primary','paid'=>'success']; ?>
                            <span class="badge bg-<?= $colors[$p['status']] ?? 'secondary' ?>-subtle text-<?= $colors[$p['status']] ?? 'secondary' ?> text-capitalize"><?= ucfirst($p['status']) ?></span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="<?= base_url('admin/payroll/payslip/'.$p['id']) ?>" class="btn btn-sm btn-light" title="Payslip" target="_blank">
                                    <i class="bi bi-receipt"></i>
                                </a>
                                <?php if ($p['status'] === 'processed'): ?>
                                <form method="POST" action="<?= base_url('admin/payroll/mark-paid/'.$p['id']) ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-success" title="Mark Paid">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
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

<?= $this->endSection() ?>
