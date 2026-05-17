<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Generate Payroll</h1>
        <p class="page-subtitle">Process payroll for <?= date('F Y', mktime(0,0,0,$month,1,$year)) ?></p>
    </div>
    <a href="<?= base_url('admin/payroll') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<form method="POST" action="<?= base_url('admin/payroll/process') ?>">
    <?= csrf_field() ?>

    <!-- Month/Year -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Month</label>
                    <select name="month" class="form-select" id="payMonth">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $month == $m ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$m,1)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-select" id="payYear">
                        <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                        <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='<?= base_url('admin/payroll/generate?month=') ?>'+document.getElementById('payMonth').value+'&year='+document.getElementById('payYear').value">
                        Reload Employees
                    </button>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label">Select All Unprocessed</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0">Employees (<?= count($employees) ?>)</h6>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-play-fill me-1"></i>Process Selected</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:50px">
                                <input type="checkbox" class="form-check-input" id="checkAll">
                            </th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Basic Salary</th>
                            <th>Est. Gross</th>
                            <th>Est. Net</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $emp): ?>
                        <tr class="<?= $emp['already_generated'] ? 'table-light' : '' ?>">
                            <td class="ps-4">
                                <?php if (!$emp['already_generated']): ?>
                                <input type="checkbox" name="employee_ids[]" value="<?= $emp['id'] ?>" class="form-check-input emp-check">
                                <?php else: ?>
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-600 small"><?= esc($emp['first_name'].' '.$emp['last_name']) ?></div>
                                <div class="text-muted" style="font-size:0.72rem"><?= esc($emp['employee_id']) ?></div>
                            </td>
                            <td class="small text-muted"><?= esc($emp['department_name'] ?? '-') ?></td>
                            <td>৳<?= number_format($emp['basic_salary']) ?></td>
                            <td>৳<?= number_format($emp['basic_salary'] * 1.53) ?></td>
                            <td>৳<?= number_format($emp['basic_salary'] * 1.53 * 0.80) ?></td>
                            <td>
                                <?php if ($emp['already_generated']): ?>
                                <span class="badge bg-success-subtle text-success">Processed</span>
                                <?php else: ?>
                                <span class="badge bg-warning-subtle text-warning">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary"><i class="bi bi-play-fill me-2"></i>Generate Payroll</button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.getElementById('checkAll').addEventListener('change', function() {
    document.querySelectorAll('.emp-check').forEach(cb => cb.checked = this.checked);
});
</script>
<?= $this->endSection() ?>
