<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">My Profile</h1><p class="page-subtitle">Manage your personal information and account settings</p></div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center mb-4">
            <div class="card-body py-4">
                <?php if ($employee && $employee['photo']): ?>
                    <img src="<?= base_url('uploads/'.$employee['photo']) ?>" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover">
                <?php else: ?>
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:100px;height:100px;background:#e0e7ff;font-size:2rem;font-weight:700;color:#4f46e5">
                        <?= strtoupper(substr(session()->get('full_name')??'U',0,1)) ?>
                    </div>
                <?php endif; ?>
                <h5 class="fw-700"><?= esc(session()->get('full_name')) ?></h5>
                <p class="text-muted small"><?= esc($employee['designation_title'] ?? 'No Designation') ?></p>
                <span class="badge badge-pill status-active"><?= ucfirst(str_replace('_',' ',session()->get('role'))) ?></span>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0"><i class="bi bi-lock me-2"></i>Change Password</h6></div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('admin/profile/password') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" minlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Change Password</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0"><i class="bi bi-person me-2"></i>Update Profile</h6></div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('admin/profile/update') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <?php if ($employee): ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" value="<?= esc($employee['first_name']) ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" value="<?= esc($employee['last_name']) ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= esc($employee['email']) ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control" value="<?= esc($employee['phone']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2"><?= esc($employee['address']) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="<?= esc($employee['city']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control" value="<?= esc($employee['state']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact</label>
                            <input type="text" name="emergency_contact_name" class="form-control" value="<?= esc($employee['emergency_contact_name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Phone</label>
                            <input type="tel" name="emergency_contact_phone" class="form-control" value="<?= esc($employee['emergency_contact_phone']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">No employee profile linked. Contact administrator.</div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
