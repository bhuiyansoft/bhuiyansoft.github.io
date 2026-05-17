<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">Edit Designation</h1></div>
    <a href="<?= base_url('admin/designations') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= base_url('admin/designations/update/'.$designation['id']) ?>">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label required">Department</label>
                            <select name="department_id" class="form-select select2" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= $designation['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= esc($dept['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label required">Title</label>
                            <input type="text" name="title" class="form-control" value="<?= esc($designation['title']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-select">
                                <?php foreach (['Junior','Mid','Senior','Lead','Manager','Director','Executive'] as $lvl): ?>
                                <option value="<?= $lvl ?>" <?= $designation['level'] === $lvl ? 'selected' : '' ?>><?= $lvl ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Min Salary</label>
                            <div class="input-group"><span class="input-group-text">৳</span>
                            <input type="number" name="min_salary" class="form-control" value="<?= $designation['min_salary'] ?>"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Max Salary</label>
                            <div class="input-group"><span class="input-group-text">৳</span>
                            <input type="number" name="max_salary" class="form-control" value="<?= $designation['max_salary'] ?>"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" <?= $designation['status'] == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $designation['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= esc($designation['description']) ?></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="<?= base_url('admin/designations') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
