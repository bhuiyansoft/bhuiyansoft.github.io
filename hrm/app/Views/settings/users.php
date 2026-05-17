<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">User Management</h1><p class="page-subtitle">Manage system users and roles</p></div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="bi bi-plus-lg me-1"></i>Add User
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover datatable mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">User</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Last Login</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-600 small"><?= esc($u['full_name'] ?? $u['username']) ?></div>
                            <div class="text-muted" style="font-size:0.72rem"><?= esc($u['email']) ?></div>
                        </td>
                        <td class="small"><?= esc($u['username']) ?></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary text-capitalize"><?= str_replace('_', ' ', $u['role']) ?></span>
                        </td>
                        <td class="small text-muted"><?= $u['last_login'] ? date('M d, Y H:i', strtotime($u['last_login'])) : 'Never' ?></td>
                        <td>
                            <span class="badge <?= $u['is_active'] ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                                <?= $u['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light" onclick="editUser(<?= htmlspecialchars(json_encode($u)) ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <?php if ($u['id'] !== session()->get('user_id')): ?>
                            <form method="POST" action="<?= base_url('admin/settings/users/delete/'.$u['id']) ?>" class="d-inline" onsubmit="return confirm('Delete user?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= base_url('admin/settings/users/store') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Employee (Optional)</label>
                            <select name="employee_id" class="form-select select2">
                                <option value="">Select Employee</option>
                                <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= esc($emp['first_name'].' '.$emp['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Password</label>
                            <input type="password" name="password" class="form-control" minlength="8" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="employee">Employee</option>
                                <option value="manager">Manager</option>
                                <option value="hr_manager">HR Manager</option>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editUserForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" id="editRole" class="form-select">
                                <option value="employee">Employee</option>
                                <option value="manager">Manager</option>
                                <option value="hr_manager">HR Manager</option>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="is_active" id="editStatus" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">New Password <small class="text-muted">(leave empty to keep current)</small></label>
                            <input type="password" name="password" id="editPassword" class="form-control" minlength="8">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function editUser(user) {
    document.getElementById('editUserForm').action = `<?= base_url('admin/settings/users/update/') ?>${user.id}`;
    document.getElementById('editRole').value = user.role;
    document.getElementById('editStatus').value = user.is_active;
    document.getElementById('editPassword').value = '';
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}
</script>
<?= $this->endSection() ?>
