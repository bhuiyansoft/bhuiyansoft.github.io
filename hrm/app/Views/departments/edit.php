<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">Edit Department</h1></div>
    <a href="<?= base_url('admin/departments') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0">Edit Department</h6></div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('admin/departments/update/'.$department['id']) ?>">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label required">Department Name</label>
                            <input type="text" name="name" class="form-control" value="<?= esc($department['name']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" value="<?= esc($department['code']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Department Head</label>
                            <select name="head_id" class="form-select select2">
                                <option value="">Select Head</option>
                                <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>" <?= $department['head_id'] == $emp['id'] ? 'selected' : '' ?>><?= esc($emp['first_name'].' '.$emp['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= esc($department['description']) ?></textarea>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" <?= $department['status'] == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $department['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Update Department</button>
                        <a href="<?= base_url('admin/departments') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
