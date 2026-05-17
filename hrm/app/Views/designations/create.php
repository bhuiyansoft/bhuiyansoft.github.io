<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">Add Designation</h1></div>
    <a href="<?= base_url('admin/designations') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0">Designation Details</h6></div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('admin/designations/store') ?>">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label required">Department</label>
                            <select name="department_id" class="form-select select2" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= esc($dept['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label required">Designation Title</label>
                            <input type="text" name="title" class="form-control" value="<?= old('title') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-select">
                                <option value="">Select</option>
                                <option value="Junior">Junior</option>
                                <option value="Mid">Mid</option>
                                <option value="Senior">Senior</option>
                                <option value="Lead">Lead</option>
                                <option value="Manager">Manager</option>
                                <option value="Director">Director</option>
                                <option value="Executive">Executive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Min Salary (BDT)</label>
                            <div class="input-group"><span class="input-group-text">৳</span>
                            <input type="number" name="min_salary" class="form-control" value="<?= old('min_salary') ?>" min="0"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Max Salary (BDT)</label>
                            <div class="input-group"><span class="input-group-text">৳</span>
                            <input type="number" name="max_salary" class="form-control" value="<?= old('max_salary') ?>" min="0"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= old('description') ?></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Save Designation</button>
                        <a href="<?= base_url('admin/designations') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
