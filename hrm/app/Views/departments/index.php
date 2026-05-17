<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Departments</h1>
        <p class="page-subtitle">Manage organizational departments</p>
    </div>
    <a href="<?= base_url('admin/departments/create') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Add Department
    </a>
</div>

<div class="row g-4">
    <?php foreach ($departments as $dept): ?>
    <div class="col-sm-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon" style="background:#e0e7ff;width:48px;height:48px">
                        <i class="bi bi-diagram-3" style="color:#4f46e5;font-size:1.2rem"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                            <li><a class="dropdown-item" href="<?= base_url('admin/departments/edit/'.$dept['id']) ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                            <li>
                                <form method="POST" action="<?= base_url('admin/departments/delete/'.$dept['id']) ?>" onsubmit="return confirm('Delete department?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <h5 class="fw-700 mb-1"><?= esc($dept['name']) ?></h5>
                <?php if ($dept['code']): ?>
                <span class="badge bg-light text-muted mb-2"><?= esc($dept['code']) ?></span>
                <?php endif; ?>
                <p class="text-muted small mb-3"><?= esc($dept['description'] ?? 'No description') ?></p>
                <div class="border-top pt-3">
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="fw-700 text-primary fs-5"><?= $dept['employee_count'] ?></div>
                            <div class="text-muted small">Employees</div>
                        </div>
                        <div class="col-6">
                            <div class="fw-600 small"><?= esc($dept['head_name'] ?? 'Not Assigned') ?></div>
                            <div class="text-muted small">Department Head</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
