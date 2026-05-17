<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">Add Department</h1></div>
    <a href="<?= base_url('admin/departments') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0">Department Information</h6></div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('admin/departments/store') ?>">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label required">Department Name</label>
                            <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Department Code</label>
                            <input type="text" name="code" class="form-control" value="<?= old('code') ?>" placeholder="e.g. HR, IT">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Department Head</label>
                            <select name="head_id" class="form-select select2">
                                <option value="">Select Head</option>
                                <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= esc($emp['first_name'].' '.$emp['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= old('description') ?></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check me-1"></i>Save Department</button>
                        <a href="<?= base_url('admin/departments') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
