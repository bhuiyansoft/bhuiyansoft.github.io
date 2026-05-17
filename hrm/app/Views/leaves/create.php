<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">Apply Leave</h1></div>
    <a href="<?= base_url('admin/leaves') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0">Leave Application</h6></div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('admin/leaves/store') ?>">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label required">Employee</label>
                            <select name="employee_id" class="form-select select2" required onchange="loadBalance(this.value)">
                                <option value="">Select Employee</option>
                                <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= esc($emp['first_name'].' '.$emp['last_name']) ?> (<?= esc($emp['employee_id']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label required">Leave Type</label>
                            <select name="leave_type_id" class="form-select" required>
                                <option value="">Select Type</option>
                                <?php foreach ($leave_types as $lt): ?>
                                <option value="<?= $lt['id'] ?>"><?= esc($lt['name']) ?> (<?= $lt['days_allowed'] ?> days)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">From Date</label>
                            <input type="date" name="from_date" class="form-control datepicker" required onchange="calcDays()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">To Date</label>
                            <input type="date" name="to_date" class="form-control datepicker" required onchange="calcDays()">
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3" id="daysInfo" style="display:none">
                                <strong>Total Days: <span id="totalDays">0</span></strong>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label required">Reason</label>
                            <textarea name="reason" class="form-control" rows="4" required minlength="10" placeholder="Please provide a reason..."></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                        <a href="<?= base_url('admin/leaves') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4" id="balanceInfo" style="display:none">
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0">Leave Balance</h6></div>
            <div class="card-body" id="balanceContent"></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function calcDays() {
    const from = document.querySelector('[name="from_date"]').value;
    const to   = document.querySelector('[name="to_date"]').value;
    if (from && to) {
        const days = Math.floor((new Date(to) - new Date(from)) / 86400000) + 1;
        document.getElementById('totalDays').textContent = days;
        document.getElementById('daysInfo').style.display = days > 0 ? 'block' : 'none';
    }
}

function loadBalance(empId) {
    if (!empId) return;
    fetch(`<?= base_url('admin/leaves/balance/') ?>${empId}`)
        .then(r => r.json())
        .then(data => {
            const box = document.getElementById('balanceInfo');
            const content = document.getElementById('balanceContent');
            box.style.display = 'block';
            if (data.length === 0) {
                content.innerHTML = '<p class="text-muted small">No leave allocated for this employee.</p>';
                return;
            }
            content.innerHTML = data.map(b => `
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-600">${b.leave_type_name}</span>
                        <span class="text-muted">${b.remaining_days}/${b.allocated_days} days</span>
                    </div>
                    <div class="progress" style="height:6px">
                        <div class="progress-bar" style="width:${b.allocated_days > 0 ? (b.used_days/b.allocated_days)*100 : 0}%;background:#4f46e5"></div>
                    </div>
                </div>`).join('');
        });
}
</script>
<?= $this->endSection() ?>
