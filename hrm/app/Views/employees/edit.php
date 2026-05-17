<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">Edit Employee</h1><p class="page-subtitle"><?= esc($employee['employee_id']) ?></p></div>
    <a href="<?= base_url('admin/employees/'.$employee['id']) ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Profile</a>
</div>

<form method="POST" action="<?= base_url('admin/employees/update/'.$employee['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h6 class="card-title mb-0">Personal Information</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= esc($employee['first_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= esc($employee['last_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= esc($employee['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control" value="<?= esc($employee['phone']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="male" <?= $employee['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= $employee['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= $employee['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="<?= esc($employee['date_of_birth']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">National ID</label>
                            <input type="text" name="national_id" class="form-control" value="<?= esc($employee['national_id']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2"><?= esc($employee['address']) ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="<?= esc($employee['city']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control" value="<?= esc($employee['state']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" value="<?= esc($employee['country']) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h6 class="card-title mb-0">Employment Details</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Department</label>
                            <select name="department_id" class="form-select select2" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= $employee['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= esc($dept['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Designation</label>
                            <select name="designation_id" class="form-select select2">
                                <option value="">Select Designation</option>
                                <?php foreach ($designations as $des): ?>
                                <option value="<?= $des['id'] ?>" <?= $employee['designation_id'] == $des['id'] ? 'selected' : '' ?>><?= esc($des['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Employment Type</label>
                            <select name="employment_type" class="form-select">
                                <?php foreach (['full_time','part_time','contract','intern'] as $et): ?>
                                <option value="<?= $et ?>" <?= $employee['employment_type'] === $et ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$et)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <?php foreach (['active','inactive','terminated','on_leave'] as $st): ?>
                                <option value="<?= $st ?>" <?= $employee['status'] === $st ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$st)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Join Date</label>
                            <input type="date" name="join_date" class="form-control" value="<?= esc($employee['join_date']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Basic Salary</label>
                            <div class="input-group"><span class="input-group-text">৳</span>
                            <input type="number" name="basic_salary" class="form-control" value="<?= $employee['basic_salary'] ?>" step="0.01"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" value="<?= esc($employee['bank_name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bank Account</label>
                            <input type="text" name="bank_account" class="form-control" value="<?= esc($employee['bank_account']) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><h6 class="card-title mb-0">Profile Photo</h6></div>
                <div class="card-body text-center">
                    <?php if ($employee['photo']): ?>
                        <img src="<?= base_url('uploads/'.$employee['photo']) ?>" id="photoPreviewImg" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover">
                    <?php else: ?>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:100px;height:100px;background:#e0e7ff;font-size:2rem;color:#4f46e5">
                            <?= strtoupper(substr($employee['first_name'],0,1)) ?>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                    <label for="photoInput" class="btn btn-outline-primary btn-sm"><i class="bi bi-upload me-1"></i>Change Photo</label>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check me-1"></i>Update Employee</button>
                <a href="<?= base_url('admin/employees/'.$employee['id']) ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
