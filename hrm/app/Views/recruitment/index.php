<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">Recruitment</h1><p class="page-subtitle">Manage job postings and applications</p></div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/recruitment/applications') ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-people me-1"></i>Applications</a>
        <a href="<?= base_url('admin/recruitment/jobs/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Post Job</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-3">
        <div class="stat-card"><div class="stat-icon" style="background:#d1fae5"><i class="bi bi-briefcase-fill" style="color:#059669"></i></div>
            <div><div class="stat-value"><?= $openJobs ?></div><div class="stat-label">Open Positions</div></div></div>
    </div>
    <div class="col-sm-3">
        <div class="stat-card"><div class="stat-icon" style="background:#e0e7ff"><i class="bi bi-people-fill" style="color:#4f46e5"></i></div>
            <div><div class="stat-value"><?= $totalApps ?></div><div class="stat-label">Total Applications</div></div></div>
    </div>
    <div class="col-sm-3">
        <div class="stat-card"><div class="stat-icon" style="background:#fef3c7"><i class="bi bi-person-check" style="color:#d97706"></i></div>
            <div><div class="stat-value"><?= $shortlisted ?></div><div class="stat-label">In Interview</div></div></div>
    </div>
    <div class="col-sm-3">
        <div class="stat-card"><div class="stat-icon" style="background:#fce7f3"><i class="bi bi-star-fill" style="color:#be185d"></i></div>
            <div><div class="stat-value"><?= $hired ?></div><div class="stat-label">Hired</div></div></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0">Recent Job Postings</h6>
                <a href="<?= base_url('admin/recruitment/jobs') ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($recentJobs as $job): ?>
                    <div class="list-group-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-600"><?= esc($job['title']) ?></div>
                                <div class="small text-muted"><?= esc($job['department_name'] ?? '') ?> &bull; <?= ucfirst(str_replace('_',' ',$job['employment_type'])) ?></div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-<?= $job['status'] === 'open' ? 'success' : 'secondary' ?>-subtle text-<?= $job['status'] === 'open' ? 'success' : 'secondary' ?>"><?= ucfirst($job['status']) ?></span>
                                <div class="small text-muted"><?= $job['application_count'] ?> apps</div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0">Recent Applications</h6>
                <a href="<?= base_url('admin/recruitment/applications') ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($recentApps as $app): ?>
                    <div class="list-group-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="emp-avatar"><?= strtoupper(substr($app['applicant_name'],0,2)) ?></div>
                            <div class="flex-grow-1">
                                <div class="fw-600 small"><?= esc($app['applicant_name']) ?></div>
                                <div class="text-muted" style="font-size:0.72rem"><?= esc($app['job_title']) ?></div>
                            </div>
                            <?php $stageColors = ['applied'=>'secondary','screening'=>'info','interview'=>'warning','offer'=>'primary','hired'=>'success','rejected'=>'danger']; ?>
                            <span class="badge bg-<?= $stageColors[$app['stage']] ?? 'secondary' ?>-subtle text-<?= $stageColors[$app['stage']] ?? 'secondary' ?>">
                                <?= ucfirst($app['stage']) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
