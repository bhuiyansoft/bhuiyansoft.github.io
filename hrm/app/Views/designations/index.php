<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">Designations</h1><p class="page-subtitle">Manage job titles and positions</p></div>
    <a href="<?= base_url('admin/designations/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Designation</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover datatable mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Department</th>
                        <th>Level</th>
                        <th>Salary Range</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($designations as $des): ?>
                    <tr>
                        <td class="ps-4 fw-600"><?= esc($des['title']) ?></td>
                        <td class="text-muted small"><?= esc($des['department_name'] ?? '-') ?></td>
                        <td><span class="badge bg-light text-dark"><?= esc($des['level'] ?? '-') ?></span></td>
                        <td class="small">৳<?= number_format($des['min_salary']) ?> - ৳<?= number_format($des['max_salary']) ?></td>
                        <td><span class="badge <?= $des['status'] ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>"><?= $des['status'] ? 'Active' : 'Inactive' ?></span></td>
                        <td class="text-end pe-4">
                            <a href="<?= base_url('admin/designations/edit/'.$des['id']) ?>" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="<?= base_url('admin/designations/delete/'.$des['id']) ?>" class="d-inline" onsubmit="return confirm('Delete?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
