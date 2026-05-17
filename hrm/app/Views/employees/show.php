<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Employee Profile</h1>
        <p class="page-subtitle"><?= esc($employee['employee_id']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/employees/edit/'.$employee['id']) ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="<?= base_url('admin/employees') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card text-center mb-4">
            <div class="card-body py-4">
                <?php if ($employee['photo']): ?>
                    <img src="<?= base_url('uploads/'.$employee['photo']) ?>" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;border:4px solid #e0e7ff" alt="">
                <?php else: ?>
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:100px;height:100px;background:#e0e7ff;font-size:2.5rem;font-weight:700;color:#4f46e5">
                        <?= strtoupper(substr($employee['first_name'],0,1).substr($employee['last_name'],0,1)) ?>
                    </div>
                <?php endif; ?>
                <h5 class="fw-700 mb-1"><?= esc($employee['first_name'].' '.$employee['last_name']) ?></h5>
                <p class="text-muted mb-2 small"><?= esc($employee['designation_title'] ?? 'No Designation') ?></p>
                <span class="badge badge-pill status-<?= $employee['status'] ?> mb-3">
                    <?= ucfirst(str_replace('_', ' ', $employee['status'])) ?>
                </span>
                <div class="border-top pt-3">
                    <div class="row text-center g-0">
                        <div class="col">
                            <div class="fw-700"><?= esc($employee['department_name'] ?? '-') ?></div>
                            <div class="text-muted small">Department</div>
                        </div>
                        <div class="col border-start">
                            <div class="fw-700"><?= $employee['join_date'] ? date('M Y', strtotime($employee['join_date'])) : '-' ?></div>
                            <div class="text-muted small">Joined</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title mb-0">Contact Information</h6></div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">Email</dt>
                    <dd class="col-7"><a href="mailto:<?= esc($employee['email']) ?>"><?= esc($employee['email']) ?></a></dd>
                    <dt class="col-5 text-muted">Phone</dt>
                    <dd class="col-7"><?= esc($employee['phone'] ?? '-') ?></dd>
                    <dt class="col-5 text-muted">Address</dt>
                    <dd class="col-7"><?= esc($employee['address'] ?? '-') ?></dd>
                    <dt class="col-5 text-muted">City</dt>
                    <dd class="col-7"><?= esc($employee['city'] ?? '-') ?></dd>
                    <dt class="col-5 text-muted">Country</dt>
                    <dd class="col-7"><?= esc($employee['country'] ?? '-') ?></dd>
                </dl>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0">Emergency Contact</h6></div>
            <div class="card-body small">
                <div class="fw-600"><?= esc($employee['emergency_contact_name'] ?? 'N/A') ?></div>
                <div class="text-muted"><?= esc($employee['emergency_contact_phone'] ?? '') ?></div>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="col-lg-8">
        <!-- Tabs -->
        <div class="card mb-4">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="profileTabs">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#details">Details</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#attendance">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#leaves">Leave Balance</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#documents">Documents</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">

                    <!-- Details Tab -->
                    <div class="tab-pane fade show active" id="details">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Employee ID</label>
                                <div class="fw-600"><?= esc($employee['employee_id']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Gender</label>
                                <div class="fw-600 text-capitalize"><?= esc($employee['gender'] ?? '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Date of Birth</label>
                                <div class="fw-600"><?= $employee['date_of_birth'] ? date('M d, Y', strtotime($employee['date_of_birth'])) : '-' ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">National ID</label>
                                <div class="fw-600"><?= esc($employee['national_id'] ?? '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Employment Type</label>
                                <div class="fw-600 text-capitalize"><?= str_replace('_', ' ', $employee['employment_type'] ?? '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Reports To</label>
                                <div class="fw-600"><?= esc($employee['manager_name'] ?? '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Basic Salary</label>
                                <div class="fw-600">৳<?= number_format($employee['basic_salary']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Bank</label>
                                <div class="fw-600"><?= esc($employee['bank_name'] ?? '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Account Number</label>
                                <div class="fw-600"><?= esc($employee['bank_account'] ?? '-') ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Tab -->
                    <div class="tab-pane fade" id="attendance">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="mb-0">Recent Attendance</h6>
                            <a href="<?= base_url('admin/attendance/employee/'.$employee['id']) ?>" class="btn btn-sm btn-outline-primary">Full Report</a>
                        </div>
                        <?php if (empty($recentAttendance)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-clock fs-2"></i>
                            <p class="mt-2">No attendance records found.</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr><th>Date</th><th>Check In</th><th>Check Out</th><th>Hours</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentAttendance as $att): ?>
                                    <tr>
                                        <td><?= date('M d', strtotime($att['date'])) ?></td>
                                        <td><?= $att['check_in'] ? substr($att['check_in'],0,5) : '-' ?></td>
                                        <td><?= $att['check_out'] ? substr($att['check_out'],0,5) : '-' ?></td>
                                        <td><?= $att['total_hours'] ?>h</td>
                                        <td><span class="badge status-<?= $att['status'] ?>"><?= ucfirst($att['status']) ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Leave Balance -->
                    <div class="tab-pane fade" id="leaves">
                        <?php if (empty($leaveBalance)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-calendar fs-2"></i>
                            <p class="mt-2">No leave allocation found for this year.</p>
                        </div>
                        <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($leaveBalance as $lb): ?>
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3">
                                    <div class="fw-600"><?= esc($lb['name']) ?></div>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <div class="progress flex-grow-1" style="height:6px">
                                            <?php $pct = $lb['allocated_days'] > 0 ? ($lb['used_days'] / $lb['allocated_days']) * 100 : 0; ?>
                                            <div class="progress-bar" style="width:<?= $pct ?>%;background:#4f46e5"></div>
                                        </div>
                                        <span class="small text-muted"><?= $lb['used_days'] ?>/<?= $lb['allocated_days'] ?></span>
                                    </div>
                                    <div class="small text-muted mt-1"><?= $lb['remaining_days'] ?> days remaining</div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Documents -->
                    <div class="tab-pane fade" id="documents">
                        <?php if (empty($documents)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-file-earmark fs-2"></i>
                            <p class="mt-2">No documents uploaded.</p>
                        </div>
                        <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($documents as $doc): ?>
                            <div class="list-group-item d-flex align-items-center gap-3">
                                <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-600 small"><?= esc($doc['document_type']) ?></div>
                                    <div class="text-muted" style="font-size:0.72rem"><?= esc($doc['file_name']) ?></div>
                                </div>
                                <a href="<?= base_url('uploads/'.$doc['file_path']) ?>" class="btn btn-sm btn-light" target="_blank">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
