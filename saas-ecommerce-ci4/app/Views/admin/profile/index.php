<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">My Profile</h4>
            <p class="text-muted mb-0">Manage your account information</p>
        </div>
    </div>

    <div class="row g-3">
        <!-- Profile Info -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Profile Information</div>
                <div class="card-body">
                    <form method="post" action="<?= site_url('admin/profile/update') ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="position-relative">
                                <?php if ($user['avatar']): ?>
                                    <img src="<?= base_url($user['avatar']) ?>" id="avatar-preview"
                                         class="rounded-circle" width="80" height="80" style="object-fit:cover">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center"
                                         style="width:80px;height:80px;font-size:1.8rem">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <input type="file" name="avatar" id="avatar-file" accept="image/*" class="d-none"
                                       onchange="previewAvatar(this)">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="document.getElementById('avatar-file').click()">
                                    <i class="bi bi-camera me-1"></i>Change Avatar
                                </button>
                                <div class="text-muted small mt-1">JPG, PNG, WEBP (max 1MB)</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                       value="<?= esc(old('name', $user['name'])) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control bg-light" value="<?= esc($user['email']) ?>" disabled>
                                <div class="form-text">Email cannot be changed.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                       value="<?= esc(old('phone', $user['phone'])) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control bg-light text-capitalize"
                                       value="<?= esc($user['role']) ?>" disabled>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-lg me-1"></i>Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Change Password</div>
                <div class="card-body">
                    <form method="post" action="<?= site_url('admin/profile/password') ?>">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="col-12"></div>
                            <div class="col-md-6">
                                <label class="form-label">New Password <span class="text-danger">*</span></label>
                                <input type="password" name="new_password" class="form-control" required minlength="8">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning px-4">
                                    <i class="bi bi-shield-lock me-1"></i>Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Activity -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 fw-semibold pt-3">Account Details</div>
                <div class="card-body small">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Member Since</span>
                        <span><?= date('d M Y', strtotime($user['created_at'])) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Last Login</span>
                        <span><?= $user['last_login_at'] ? date('d M Y, H:i', strtotime($user['last_login_at'])) : 'Never' ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Last Login IP</span>
                        <code class="small"><?= esc($user['last_login_ip'] ?? '—') ?></code>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Account Status</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            let img = document.getElementById('avatar-preview');
            if (!img) {
                const div = input.closest('.d-flex').querySelector('div');
                div.innerHTML = `<img id="avatar-preview" class="rounded-circle" width="80" height="80" style="object-fit:cover">`;
                img = document.getElementById('avatar-preview');
            }
            img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?= $this->endSection() ?>
