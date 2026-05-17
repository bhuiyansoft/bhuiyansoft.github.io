<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div><h1 class="page-title">Performance Reviews</h1><p class="page-subtitle">Evaluate employee performance</p></div>
    <a href="<?= base_url('admin/performance/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>New Review</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover datatable mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Department</th>
                        <th>Period</th>
                        <th>Review Date</th>
                        <th>Score</th>
                        <th>Rating</th>
                        <th>Reviewer</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reviews)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">No performance reviews found.</td></tr>
                    <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <?php if ($review['photo']): ?>
                                    <img src="<?= base_url('uploads/'.$review['photo']) ?>" class="emp-avatar" alt="">
                                <?php else: ?>
                                    <div class="emp-avatar"><?= strtoupper(substr($review['employee_name'],0,2)) ?></div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-600 small"><?= esc($review['employee_name']) ?></div>
                                    <div class="text-muted" style="font-size:0.72rem"><?= esc($review['emp_code']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="small text-muted"><?= esc($review['department_name'] ?? '-') ?></td>
                        <td><?= esc($review['review_period']) ?></td>
                        <td class="small"><?= date('M d, Y', strtotime($review['review_date'])) ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress" style="width:60px;height:6px">
                                    <div class="progress-bar bg-primary" style="width:<?= ($review['overall_score']/5)*100 ?>%"></div>
                                </div>
                                <span class="small fw-600"><?= number_format($review['overall_score'],1) ?>/5</span>
                            </div>
                        </td>
                        <td>
                            <?php $ratingColors = ['excellent'=>'success','good'=>'primary','satisfactory'=>'info','needs_improvement'=>'warning','unsatisfactory'=>'danger']; ?>
                            <span class="badge bg-<?= $ratingColors[$review['rating']] ?? 'secondary' ?>-subtle text-<?= $ratingColors[$review['rating']] ?? 'secondary' ?> text-capitalize">
                                <?= str_replace('_', ' ', ucfirst($review['rating'])) ?>
                            </span>
                        </td>
                        <td class="small text-muted"><?= esc($review['reviewer_name'] ?? '-') ?></td>
                        <td class="text-end pe-4">
                            <a href="<?= base_url('admin/performance/'.$review['id']) ?>" class="btn btn-sm btn-light">View</a>
                            <a href="<?= base_url('admin/performance/edit/'.$review['id']) ?>" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
