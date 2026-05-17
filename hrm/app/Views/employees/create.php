<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Add Employee</h1>
        <p class="page-subtitle">Fill in the details to add a new employee</p>
    </div>
    <a href="<?= base_url('admin/employees') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<form method="POST" action="<?= base_url('admin/employees/store') ?>" enctype="multipart/form-data" id="employeeForm">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Personal Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title"><i class="bi bi-person me-2"></i>Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= old('first_name') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= old('last_name') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="<?= old('phone') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="male" <?= old('gender') === 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= old('gender') === 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= old('gender') === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control datepicker" value="<?= old('date_of_birth') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">National ID</label>
                            <input type="text" name="national_id" class="form-control" value="<?= old('national_id') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2"><?= old('address') ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="<?= old('city') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State/Division</label>
                            <input type="text" name="state" class="form-control" value="<?= old('state') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" value="<?= old('country', 'Bangladesh') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title"><i class="bi bi-briefcase me-2"></i>Employment Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Department</label>
                            <select name="department_id" class="form-select select2" id="departmentSelect" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= old('department_id') == $dept['id'] ? 'selected' : '' ?>>
                                    <?= esc($dept['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Designation</label>
                            <select name="designation_id" class="form-select select2" id="designationSelect">
                                <option value="">Select Designation</option>
                                <?php foreach ($designations as $des): ?>
                                <option value="<?= $des['id'] ?>" <?= old('designation_id') == $des['id'] ? 'selected' : '' ?>>
                                    <?= esc($des['title']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reports To</label>
                            <select name="reporting_to" class="form-select select2">
                                <option value="">Select Manager</option>
                                <?php foreach ($employees as $e): ?>
                                <option value="<?= $e['id'] ?>"><?= esc($e['first_name'].' '.$e['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Employment Type</label>
                            <select name="employment_type" class="form-select">
                                <option value="full_time" <?= old('employment_type') === 'full_time' ? 'selected' : '' ?>>Full Time</option>
                                <option value="part_time" <?= old('employment_type') === 'part_time' ? 'selected' : '' ?>>Part Time</option>
                                <option value="contract" <?= old('employment_type') === 'contract' ? 'selected' : '' ?>>Contract</option>
                                <option value="intern" <?= old('employment_type') === 'intern' ? 'selected' : '' ?>>Intern</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Join Date</label>
                            <input type="date" name="join_date" class="form-control datepicker" value="<?= old('join_date', date('Y-m-d')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Basic Salary (BDT)</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="basic_salary" class="form-control" value="<?= old('basic_salary') ?>" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title"><i class="bi bi-bank me-2"></i>Bank Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" value="<?= old('bank_name') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="bank_account" class="form-control" value="<?= old('bank_account') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title"><i class="bi bi-telephone-fill me-2"></i>Emergency Contact</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Contact Name</label>
                            <input type="text" name="emergency_contact_name" class="form-control" value="<?= old('emergency_contact_name') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Phone</label>
                            <input type="tel" name="emergency_contact_phone" class="form-control" value="<?= old('emergency_contact_phone') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Photo Upload -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title"><i class="bi bi-image me-2"></i>Profile Photo</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" id="photoPreview"
                            style="width:100px;height:100px;background:#e0e7ff;font-size:2rem;color:#4f46e5;cursor:pointer">
                            <i class="bi bi-person-plus"></i>
                        </div>
                    </div>
                    <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                    <label for="photoInput" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-upload me-1"></i>Upload Photo
                    </label>
                    <p class="text-muted small mt-2 mb-0">JPG, PNG, GIF up to 2MB</p>
                </div>
            </div>

            <!-- Create User Account -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title"><i class="bi bi-person-lock me-2"></i>System Access</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="create_user" id="createUser">
                        <label class="form-check-label fw-600" for="createUser">Create Login Account</label>
                    </div>
                    <div id="userRoleSection" class="d-none">
                        <label class="form-label">User Role</label>
                        <select name="user_role" class="form-select">
                            <option value="employee">Employee</option>
                            <option value="manager">Manager</option>
                            <option value="hr_manager">HR Manager</option>
                            <option value="admin">Admin</option>
                        </select>
                        <small class="text-muted">Default password: Password@123</small>
                    </div>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="card mb-4 bg-primary text-white border-0">
                <div class="card-body">
                    <h6 class="fw-700 mb-3"><i class="bi bi-info-circle me-2"></i>Quick Info</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><i class="bi bi-check2 me-2"></i>Employee ID auto-generated</li>
                        <li class="mb-2"><i class="bi bi-check2 me-2"></i>Default password: Password@123</li>
                        <li class="mb-2"><i class="bi bi-check2 me-2"></i>All fields with * are required</li>
                        <li><i class="bi bi-check2 me-2"></i>Email must be unique</li>
                    </ul>
                </div>
            </div>

            <!-- Submit -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Save Employee
                </button>
                <a href="<?= base_url('admin/employees') ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
// Photo preview
document.getElementById('photoInput').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = `<img src="${e.target.result}" style="width:100px;height:100px;object-fit:cover;border-radius:50%">`;
        };
        reader.readAsDataURL(this.files[0]);
    }
});

// Toggle user account section
document.getElementById('createUser').addEventListener('change', function() {
    document.getElementById('userRoleSection').classList.toggle('d-none', !this.checked);
});

// Dynamic designation load
document.getElementById('departmentSelect').addEventListener('change', function() {
    const deptId = this.value;
    const desSelect = document.getElementById('designationSelect');
    desSelect.innerHTML = '<option value="">Loading...</option>';
    if (!deptId) { desSelect.innerHTML = '<option value="">Select Designation</option>'; return; }
    fetch(`<?= base_url('admin/designations/by-department/') ?>${deptId}`)
        .then(r => r.json())
        .then(data => {
            desSelect.innerHTML = '<option value="">Select Designation</option>' +
                data.map(d => `<option value="${d.id}">${d.title}</option>`).join('');
        });
});
</script>
<?= $this->endSection() ?>
