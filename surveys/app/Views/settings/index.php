<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title">Settings</h1>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group">
            <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                <i class="bi bi-person me-2"></i>Profile
            </a>
            <a href="#password" class="list-group-item list-group-item-action" data-bs-toggle="list">
                <i class="bi bi-lock me-2"></i>Password
            </a>
            <?php if (in_array(session('user_role'), ['owner', 'admin'])): ?>
            <a href="#organization" class="list-group-item list-group-item-action" data-bs-toggle="list">
                <i class="bi bi-building me-2"></i>Organization
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-9">
        <div class="tab-content">
            <!-- Profile -->
            <div class="tab-pane fade show active" id="profile">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Profile Information</h5></div>
                    <div class="card-body">
                        <form action="/settings/profile" method="POST">
                            <?= csrf_field() ?>
                            <div class="row g-3 mb-4">
                                <div class="col-md-2">
                                    <div class="user-avatar-lg"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
                                </div>
                                <div class="col-md-10">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Full Name</label>
                                        <input type="text" name="name" class="form-control" value="<?= esc($user['name']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email Address</label>
                                        <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Role</label>
                                <input type="text" class="form-control" value="<?= ucfirst(session('user_role')) ?>" disabled>
                                <div class="form-text">Roles are managed by your organization owner.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Member Since</label>
                                <input type="text" class="form-control" value="<?= date('F j, Y', strtotime($user['created_at'])) ?>" disabled>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-floppy me-2"></i>Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="tab-pane fade" id="password">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Change Password</h5></div>
                    <div class="card-body">
                        <form action="/settings/password" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">New Password</label>
                                <input type="password" name="new_password" class="form-control" required minlength="8">
                                <div class="form-text">Minimum 8 characters</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-lock me-2"></i>Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Organization -->
            <?php if (in_array(session('user_role'), ['owner', 'admin'])): ?>
            <div class="tab-pane fade" id="organization">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Organization Settings</h5></div>
                    <div class="card-body">
                        <form action="/settings/organization" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Organization Name</label>
                                <input type="text" name="name" class="form-control" value="<?= esc($tenant['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Website</label>
                                <input type="url" name="website" class="form-control" value="<?= esc($tenant['website']) ?>" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Industry</label>
                                <select name="industry" class="form-select">
                                    <option value="">Select industry...</option>
                                    <?php foreach (['Technology', 'Healthcare', 'Education', 'Finance', 'Retail', 'Marketing', 'Consulting', 'Non-profit', 'Government', 'Other'] as $ind): ?>
                                    <option value="<?= $ind ?>" <?= $tenant['industry'] === $ind ? 'selected' : '' ?>><?= $ind ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Brand Color</label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="color" name="primary_color" class="form-control form-control-color" value="<?= esc($tenant['primary_color'] ?? '#4F46E5') ?>">
                                    <span class="text-muted">Used in survey designs and emails</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Organization Slug</label>
                                <input type="text" class="form-control" value="<?= esc($tenant['slug']) ?>" disabled>
                                <div class="form-text">Your unique organization identifier (cannot be changed).</div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-floppy me-2"></i>Save Organization Settings
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
