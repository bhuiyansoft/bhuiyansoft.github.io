<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Employees</h1>
        <p class="page-subtitle">Manage all employee records</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/employees/export') ?>" class="btn btn-outline-success btn-sm">
            <i class="bi bi-download me-1"></i>Export CSV
        </a>
        <a href="<?= base_url('admin/employees/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Add Employee
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" id="empSearch" class="form-control form-control-sm" placeholder="Name, email, ID...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Department</label>
                <select id="deptFilter" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    <?php
                    $depts = array_unique(array_column($employees, 'department_name'));
                    foreach ($depts as $dept): if ($dept): ?>
                    <option value="<?= esc($dept) ?>"><?= esc($dept) ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select id="statusFilter" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="terminated">Terminated</option>
                    <option value="on_leave">On Leave</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">View</label>
                <div class="btn-group w-100" role="group">
                    <button type="button" class="btn btn-sm btn-primary" id="tableView"><i class="bi bi-table"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="cardView"><i class="bi bi-grid"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table View -->
<div id="tableViewContainer">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover datatable mb-0" id="empTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:40px">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Type</th>
                            <th>Join Date</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $emp): ?>
                        <tr>
                            <td class="ps-4">
                                <input type="checkbox" class="form-check-input emp-checkbox" value="<?= $emp['id'] ?>">
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if ($emp['photo']): ?>
                                        <img src="<?= base_url('uploads/'.$emp['photo']) ?>" class="emp-avatar" alt="">
                                    <?php else: ?>
                                        <div class="emp-avatar"><?= strtoupper(substr($emp['first_name'],0,1).substr($emp['last_name'],0,1)) ?></div>
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?= base_url('admin/employees/'.$emp['id']) ?>" class="fw-600 text-dark text-decoration-none">
                                            <?= esc($emp['first_name'].' '.$emp['last_name']) ?>
                                        </a>
                                        <div class="text-muted small"><?= esc($emp['email']) ?></div>
                                        <div class="text-muted" style="font-size:0.7rem"><?= esc($emp['employee_id']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-muted"><?= esc($emp['department_name'] ?? '-') ?></span></td>
                            <td><span class="small"><?= esc($emp['designation_title'] ?? '-') ?></span></td>
                            <td>
                                <span class="badge bg-light text-dark text-capitalize">
                                    <?= str_replace('_', ' ', $emp['employment_type']) ?>
                                </span>
                            </td>
                            <td><span class="small text-muted"><?= $emp['join_date'] ? date('M d, Y', strtotime($emp['join_date'])) : '-' ?></span></td>
                            <td>
                                <span class="badge badge-pill status-<?= $emp['status'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $emp['status'])) ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                        <li><a class="dropdown-item" href="<?= base_url('admin/employees/'.$emp['id']) ?>"><i class="bi bi-eye me-2"></i>View Profile</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/employees/edit/'.$emp['id']) ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/attendance/employee/'.$emp['id']) ?>"><i class="bi bi-clock me-2"></i>Attendance</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="<?= base_url('admin/employees/delete/'.$emp['id']) ?>" onsubmit="return confirm('Delete this employee?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Card View -->
<div id="cardViewContainer" class="d-none">
    <div class="row g-4">
        <?php foreach ($employees as $emp): ?>
        <div class="col-sm-6 col-lg-4 col-xl-3 emp-card-item"
            data-dept="<?= esc($emp['department_name'] ?? '') ?>"
            data-status="<?= esc($emp['status']) ?>"
            data-name="<?= esc(strtolower($emp['first_name'].' '.$emp['last_name'].' '.$emp['email'].' '.$emp['employee_id'])) ?>">
            <div class="card h-100 text-center p-4">
                <div class="position-relative mb-3">
                    <?php if ($emp['photo']): ?>
                        <img src="<?= base_url('uploads/'.$emp['photo']) ?>" class="rounded-circle" style="width:72px;height:72px;object-fit:cover" alt="">
                    <?php else: ?>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:72px;height:72px;background:#e0e7ff;font-size:1.5rem;font-weight:700;color:#4f46e5">
                            <?= strtoupper(substr($emp['first_name'],0,1).substr($emp['last_name'],0,1)) ?>
                        </div>
                    <?php endif; ?>
                    <span class="position-absolute bottom-0 end-0 translate-middle badge rounded-pill status-<?= $emp['status'] ?>" style="transform:translate(-20%,10%) !important">
                        <?= ucfirst($emp['status']) ?>
                    </span>
                </div>
                <h6 class="fw-700 mb-0"><?= esc($emp['first_name'].' '.$emp['last_name']) ?></h6>
                <div class="text-muted small mb-1"><?= esc($emp['employee_id']) ?></div>
                <div class="badge bg-light text-dark mb-2"><?= esc($emp['department_name'] ?? 'No Dept') ?></div>
                <div class="text-muted small mb-3"><?= esc($emp['designation_title'] ?? 'No Designation') ?></div>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="<?= base_url('admin/employees/'.$emp['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                    <a href="<?= base_url('admin/employees/edit/'.$emp['id']) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
// View toggle
document.getElementById('tableView').addEventListener('click', function() {
    document.getElementById('tableViewContainer').classList.remove('d-none');
    document.getElementById('cardViewContainer').classList.add('d-none');
    this.classList.replace('btn-outline-primary', 'btn-primary');
    document.getElementById('cardView').classList.replace('btn-primary', 'btn-outline-primary');
});
document.getElementById('cardView').addEventListener('click', function() {
    document.getElementById('tableViewContainer').classList.add('d-none');
    document.getElementById('cardViewContainer').classList.remove('d-none');
    this.classList.replace('btn-outline-primary', 'btn-primary');
    document.getElementById('tableView').classList.replace('btn-primary', 'btn-outline-primary');
});

// Select all
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.emp-checkbox').forEach(cb => cb.checked = this.checked);
});

// Card filters
function filterCards() {
    const search = document.getElementById('empSearch').value.toLowerCase();
    const dept   = document.getElementById('deptFilter').value.toLowerCase();
    const status = document.getElementById('statusFilter').value.toLowerCase();
    document.querySelectorAll('.emp-card-item').forEach(item => {
        const matchName   = !search || item.dataset.name.includes(search);
        const matchDept   = !dept   || item.dataset.dept.toLowerCase() === dept;
        const matchStatus = !status || item.dataset.status === status;
        item.style.display = matchName && matchDept && matchStatus ? '' : 'none';
    });
}

document.getElementById('empSearch').addEventListener('input', filterCards);
document.getElementById('deptFilter').addEventListener('change', filterCards);
document.getElementById('statusFilter').addEventListener('change', filterCards);
</script>
<?= $this->endSection() ?>
