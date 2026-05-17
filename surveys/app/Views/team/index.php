<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Team</h1>
        <p class="page-subtitle">Manage team members and permissions</p>
    </div>
    <?php if (in_array(session('user_role'), ['owner', 'admin'])): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteModal">
        <i class="bi bi-person-plus me-2"></i>Invite Member
    </button>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Team Members</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <?php if (in_array(session('user_role'), ['owner', 'admin'])): ?>
                    <th></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="member-avatar"><?= strtoupper(substr($member['name'], 0, 1)) ?></div>
                            <div>
                                <div class="fw-medium"><?= esc($member['name']) ?></div>
                                <div class="text-muted small"><?= esc($member['email']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-<?= $member['role'] === 'owner' ? 'primary' : ($member['role'] === 'admin' ? 'warning' : 'secondary') ?>-soft text-<?= $member['role'] === 'owner' ? 'primary' : ($member['role'] === 'admin' ? 'warning' : 'secondary') ?>">
                            <?= ucfirst($member['role']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-<?= $member['status'] === 'active' ? 'success' : 'warning' ?>-soft text-<?= $member['status'] === 'active' ? 'success' : 'warning' ?>">
                            <?= ucfirst($member['status']) ?>
                        </span>
                    </td>
                    <td class="text-muted"><?= date('M j, Y', strtotime($member['created_at'])) ?></td>
                    <?php if (in_array(session('user_role'), ['owner', 'admin'])): ?>
                    <td>
                        <?php if ($member['id'] !== (int)session('user_id') && $member['role'] !== 'owner'): ?>
                        <div class="d-flex gap-2">
                            <?php if (session('user_role') === 'owner'): ?>
                            <form action="/team/<?= $member['id'] ?>/role" method="POST" class="d-flex gap-1">
                                <?= csrf_field() ?>
                                <select name="role" class="form-select form-select-sm" style="width: auto">
                                    <option value="admin" <?= $member['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="member" <?= $member['role'] === 'member' ? 'selected' : '' ?>>Member</option>
                                    <option value="viewer" <?= $member['role'] === 'viewer' ? 'selected' : '' ?>>Viewer</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                            </form>
                            <?php endif; ?>
                            <form action="/team/<?= $member['id'] ?>/remove" method="POST" onsubmit="return confirm('Remove this team member?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($plan && $plan['max_team_members'] > 0): ?>
<div class="card mt-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-medium">Team capacity:</span>
                <span class="text-muted ms-2"><?= count($members) ?> of <?= $plan['max_team_members'] ?> members</span>
            </div>
            <?php if (count($members) >= $plan['max_team_members']): ?>
            <a href="/billing" class="btn btn-primary btn-sm">Upgrade for more seats</a>
            <?php endif; ?>
        </div>
        <div class="progress mt-2" style="height: 6px">
            <div class="progress-bar" style="width: <?= min(100, round(count($members)/$plan['max_team_members']*100)) ?>%"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invite Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/team/invite" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Jane Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="jane@company.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select name="role" class="form-select">
                            <option value="member">Member — Can create and manage surveys</option>
                            <option value="admin">Admin — Can manage team and settings</option>
                            <option value="viewer">Viewer — Can view surveys and reports only</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-envelope me-2"></i>Send Invitation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
