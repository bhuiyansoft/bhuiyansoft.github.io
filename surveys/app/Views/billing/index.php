<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Billing & Plans</h1>
        <p class="page-subtitle">Manage your subscription</p>
    </div>
</div>

<!-- Current Plan -->
<div class="card mb-4 border-primary">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3">
            <div class="plan-badge">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <div class="text-muted small">Current Plan</div>
                <h4 class="mb-0"><?= esc($current_plan['name']) ?> Plan</h4>
                <div class="text-muted">
                    <?php if ($current_plan['price_monthly'] == 0): ?>
                    Free forever
                    <?php else: ?>
                    $<?= number_format($current_plan['price_monthly'], 2) ?>/month
                    <?php if ($tenant['plan_expires_at']): ?>
                    · Renews <?= date('M j, Y', strtotime($tenant['plan_expires_at'])) ?>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Plans Grid -->
<h4 class="mb-3">Choose a Plan</h4>
<div class="row g-4 mb-4">
    <?php foreach ($plans as $plan): ?>
    <?php $isCurrent = $plan['id'] === $current_plan['id']; ?>
    <div class="col-md-4">
        <div class="plan-card <?= $plan['slug'] === 'pro' ? 'plan-popular' : '' ?> <?= $isCurrent ? 'plan-current' : '' ?>">
            <?php if ($plan['slug'] === 'pro'): ?>
            <div class="plan-badge-popular">Most Popular</div>
            <?php endif; ?>
            <div class="plan-name"><?= esc($plan['name']) ?></div>
            <div class="plan-price">
                <?php if ($plan['price_monthly'] == 0): ?>
                <span class="price-amount">Free</span>
                <?php else: ?>
                <span class="price-currency">$</span>
                <span class="price-amount"><?= (int)$plan['price_monthly'] ?></span>
                <span class="price-period">/month</span>
                <?php endif; ?>
            </div>
            <?php if ($plan['price_yearly'] > 0): ?>
            <div class="price-yearly text-muted small">$<?= (int)$plan['price_yearly'] ?>/year — Save <?= round(100 - ($plan['price_yearly'] / ($plan['price_monthly'] * 12) * 100)) ?>%</div>
            <?php endif; ?>

            <ul class="plan-features">
                <li><i class="bi bi-check-circle text-success"></i>
                    <?= $plan['max_surveys'] < 0 ? 'Unlimited' : number_format($plan['max_surveys']) ?> surveys
                </li>
                <li><i class="bi bi-check-circle text-success"></i>
                    <?= $plan['max_responses_per_survey'] < 0 ? 'Unlimited' : number_format($plan['max_responses_per_survey']) ?> responses/survey
                </li>
                <li><i class="bi bi-check-circle text-success"></i>
                    <?= $plan['max_team_members'] < 0 ? 'Unlimited' : $plan['max_team_members'] ?> team member<?= $plan['max_team_members'] != 1 ? 's' : '' ?>
                </li>
                <?php $features = json_decode($plan['features'] ?? '[]', true); ?>
                <?php
                $featureLabels = [
                    'advanced_analytics' => 'Advanced Analytics',
                    'export_csv'         => 'CSV Export',
                    'export_pdf'         => 'PDF Reports',
                    'custom_branding'    => 'Custom Branding',
                    'skip_logic'         => 'Skip Logic',
                    'api_access'         => 'API Access',
                    'white_label'        => 'White Label',
                    'priority_support'   => 'Priority Support',
                    'sso'                => 'Single Sign-On',
                ];
                ?>
                <?php foreach ($featureLabels as $key => $label): ?>
                <?php if (in_array($key, $features)): ?>
                <li><i class="bi bi-check-circle text-success"></i><?= $label ?></li>
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <?php if ($isCurrent): ?>
            <button class="btn btn-outline-secondary w-100" disabled>Current Plan</button>
            <?php else: ?>
            <form action="/billing/upgrade" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="plan_slug" value="<?= esc($plan['slug']) ?>">
                <button type="submit" class="btn <?= $plan['slug'] === 'pro' ? 'btn-primary' : 'btn-outline-primary' ?> w-100">
                    <?= $plan['price_monthly'] > ($current_plan['price_monthly'] ?? 0) ? 'Upgrade to' : 'Switch to' ?>
                    <?= esc($plan['name']) ?>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5>Need something custom?</h5>
                <p class="text-muted mb-0">Contact our sales team for custom enterprise plans with dedicated support, custom integrations, and volume pricing.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="mailto:sales@surveystack.io" class="btn btn-outline-primary">
                    <i class="bi bi-envelope me-2"></i>Contact Sales
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
