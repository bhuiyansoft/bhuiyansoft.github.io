<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SurveyStack — Powerful SaaS Survey Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/">
            <div class="brand-icon small"><i class="bi bi-bar-chart-fill"></i></div>
            SurveyStack
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto me-4 gap-1">
                <li class="nav-item"><a class="nav-link" href="/features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="/pricing">Pricing</a></li>
            </ul>
            <a href="/auth/login" class="btn btn-outline-secondary me-2">Sign In</a>
            <a href="/auth/register" class="btn btn-primary">Get Started Free</a>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero-section">
    <div class="container text-center">
        <div class="hero-badge mb-3">
            <i class="bi bi-lightning-fill text-warning me-1"></i>
            Collect insights, make better decisions
        </div>
        <h1 class="hero-title">Create surveys that<br><span class="text-primary">actually get responses</span></h1>
        <p class="hero-subtitle">
            Beautiful, responsive surveys with powerful analytics. Build in minutes,<br>collect data instantly, make decisions with confidence.
        </p>
        <div class="hero-cta d-flex gap-3 justify-content-center flex-wrap">
            <a href="/auth/register" class="btn btn-primary btn-xl">
                <i class="bi bi-rocket-takeoff me-2"></i>Start for Free
            </a>
            <a href="#features" class="btn btn-outline-secondary btn-xl">
                <i class="bi bi-play-circle me-2"></i>See How It Works
            </a>
        </div>
        <div class="hero-trust mt-4 text-muted small">
            <i class="bi bi-shield-check me-1"></i>No credit card required · Free forever plan · GDPR compliant
        </div>
    </div>
</section>

<!-- Stats -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-sm-4">
                <div class="stat-num">50K+</div>
                <div class="stat-label text-muted">Active surveys</div>
            </div>
            <div class="col-sm-4">
                <div class="stat-num">2M+</div>
                <div class="stat-label text-muted">Responses collected</div>
            </div>
            <div class="col-sm-4">
                <div class="stat-num">10K+</div>
                <div class="stat-label text-muted">Happy teams</div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features-section" id="features">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Everything you need to collect insights</h2>
            <p class="section-subtitle text-muted">Powerful tools that make survey creation easy and data analysis actionable</p>
        </div>
        <div class="row g-4">
            <?php
            $features = [
                ['bi-pencil-square', 'Survey Builder', 'Intuitive drag-and-drop builder with 10+ question types including rating, NPS, matrix, and more.'],
                ['bi-bar-chart-line', 'Real-time Analytics', 'Watch responses come in live. Visual charts and statistics that auto-update as data arrives.'],
                ['bi-people', 'Team Collaboration', 'Invite team members with role-based permissions. Work together on surveys seamlessly.'],
                ['bi-share', 'Easy Sharing', 'Share via link, QR code, email, or embed directly on your website. Works on all devices.'],
                ['bi-shield-lock', 'Privacy & Security', 'Anonymous responses, GDPR compliant, SSL encrypted. Your data stays private.'],
                ['bi-download', 'Data Export', 'Export all responses as CSV or PDF. Connect with your favorite tools via API.'],
            ];
            foreach ($features as [$icon, $title, $desc]):
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi <?= $icon ?>"></i></div>
                    <h4 class="feature-title"><?= $title ?></h4>
                    <p class="feature-desc text-muted"><?= $desc ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Question Types -->
<section class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">14+ Question Types</h2>
            <p class="section-subtitle text-muted">Choose the right question format for every situation</p>
        </div>
        <div class="row g-3 justify-content-center">
            <?php
            $types = [
                ['bi-type', 'Short Text'], ['bi-paragraph', 'Long Text'], ['bi-ui-radios', 'Single Choice'],
                ['bi-ui-checks', 'Multiple Choice'], ['bi-chevron-down', 'Dropdown'], ['bi-star', 'Rating'],
                ['bi-graph-up', 'NPS Score'], ['bi-calendar3', 'Date'], ['bi-envelope', 'Email'],
                ['bi-123', 'Number'], ['bi-check-square', 'Yes / No'], ['bi-grid-3x3', 'Matrix'],
                ['bi-sort-numeric-down', 'Ranking'], ['bi-paperclip', 'File Upload'],
            ];
            foreach ($types as [$icon, $name]):
            ?>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="type-chip">
                    <i class="bi <?= $icon ?>"></i> <?= $name ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Pricing -->
<section class="pricing-section" id="pricing">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Simple, transparent pricing</h2>
            <p class="section-subtitle text-muted">Start free, upgrade when you need more power</p>
        </div>
        <div class="row g-4 justify-content-center">
            <?php foreach ($plans as $plan): ?>
            <div class="col-md-4">
                <div class="plan-card <?= $plan['slug'] === 'pro' ? 'plan-popular' : '' ?>">
                    <?php if ($plan['slug'] === 'pro'): ?>
                    <div class="plan-badge-popular">Most Popular</div>
                    <?php endif; ?>
                    <div class="plan-name"><?= esc($plan['name']) ?></div>
                    <div class="plan-price">
                        <?php if ($plan['price_monthly'] == 0): ?>
                        <span class="price-amount">Free</span>
                        <?php else: ?>
                        <span class="price-currency">$</span><span class="price-amount"><?= (int)$plan['price_monthly'] ?></span><span class="price-period">/mo</span>
                        <?php endif; ?>
                    </div>
                    <ul class="plan-features">
                        <li><i class="bi bi-check-circle text-success"></i><?= $plan['max_surveys'] < 0 ? 'Unlimited' : $plan['max_surveys'] ?> surveys</li>
                        <li><i class="bi bi-check-circle text-success"></i><?= $plan['max_responses_per_survey'] < 0 ? 'Unlimited' : number_format($plan['max_responses_per_survey']) ?> responses/survey</li>
                        <li><i class="bi bi-check-circle text-success"></i><?= $plan['max_team_members'] < 0 ? 'Unlimited' : $plan['max_team_members'] ?> team members</li>
                    </ul>
                    <a href="/auth/register" class="btn <?= $plan['slug'] === 'pro' ? 'btn-primary' : 'btn-outline-secondary' ?> w-100">
                        Get Started
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container text-center">
        <h2 class="cta-title">Ready to collect better insights?</h2>
        <p class="cta-subtitle">Join thousands of teams using SurveyStack to make data-driven decisions</p>
        <a href="/auth/register" class="btn btn-white btn-xl">
            <i class="bi bi-rocket-takeoff me-2"></i>Start Free Today
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="brand-icon small"><i class="bi bi-bar-chart-fill"></i></div>
                    <span class="fw-bold">SurveyStack</span>
                </div>
                <p class="text-muted small">Powerful, beautiful surveys for modern teams. Built with CodeIgniter 4.</p>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="fw-semibold mb-3">Product</h6>
                <ul class="list-unstyled">
                    <li><a href="/features" class="footer-link">Features</a></li>
                    <li><a href="/pricing" class="footer-link">Pricing</a></li>
                    <li><a href="/auth/register" class="footer-link">Get Started</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="fw-semibold mb-3">Account</h6>
                <ul class="list-unstyled">
                    <li><a href="/auth/login" class="footer-link">Sign In</a></li>
                    <li><a href="/auth/register" class="footer-link">Register</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <p class="text-muted small mb-0">© <?= date('Y') ?> SurveyStack. Built with CodeIgniter 4.</p>
            <div class="d-flex gap-3">
                <a href="#" class="footer-link small">Privacy Policy</a>
                <a href="#" class="footer-link small">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
