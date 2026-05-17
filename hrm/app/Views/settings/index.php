<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">System Settings</h1><p class="page-subtitle">Configure your HRM system</p></div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="list-group" id="settingsTabs">
            <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="tab">
                <i class="bi bi-gear me-2"></i>General
            </a>
            <a href="#company" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                <i class="bi bi-building me-2"></i>Company
            </a>
            <a href="<?= base_url('admin/settings/users') ?>" class="list-group-item list-group-item-action">
                <i class="bi bi-people me-2"></i>Users &amp; Roles
            </a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="general">
                <div class="card">
                    <div class="card-header"><h6 class="card-title mb-0">General Settings</h6></div>
                    <div class="card-body">
                        <form method="POST" action="<?= base_url('admin/settings/update') ?>">
                            <?= csrf_field() ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">System Name</label>
                                    <input type="text" name="system_name" class="form-control" value="BhuiyanSoft HRM">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Timezone</label>
                                    <select name="timezone" class="form-select">
                                        <option value="Asia/Dhaka" selected>Asia/Dhaka</option>
                                        <option value="UTC">UTC</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Working Hours (Start)</label>
                                    <input type="time" name="work_start" class="form-control" value="09:00">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Working Hours (End)</label>
                                    <input type="time" name="work_end" class="form-control" value="17:00">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Working Days Per Month</label>
                                    <input type="number" name="working_days" class="form-control" value="26">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Currency Symbol</label>
                                    <input type="text" name="currency" class="form-control" value="৳">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
