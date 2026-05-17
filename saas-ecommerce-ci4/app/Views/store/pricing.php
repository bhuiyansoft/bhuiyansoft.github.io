<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> | SaaSCommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/landing.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/"><i class="bi bi-cloud-fill me-2"></i>SaaSCommerce</a>
        <div class="d-flex gap-2">
            <a href="/auth/login" class="btn btn-outline-light btn-sm">Login</a>
            <a href="/auth/register" class="btn btn-warning btn-sm">Start Free</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">Simple, Transparent Pricing</h1>
        <p class="lead text-muted">Choose the plan that fits your business. Upgrade or downgrade anytime.</p>
    </div>

    <div class="row g-4 justify-content-center">
        <?php foreach ($plans as $i => $plan):
            $features = $plan['features'] ?? [];
            $popular  = $i === 2;
        ?>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow h-100 <?= $popular ? 'border-primary border-2' : '' ?>"
                 style="<?= $popular ? 'border:2px solid #0d6efd!important' : '' ?>">
                <?php if ($popular): ?>
                    <div class="bg-primary text-white text-center py-2 small fw-bold"
                         style="border-radius:calc(.5rem - 1px) calc(.5rem - 1px) 0 0">
                        ★ MOST POPULAR
                    </div>
                <?php endif; ?>
                <div class="card-body p-4 d-flex flex-column">
                    <h4 class="fw-bold"><?= esc($plan['name']) ?></h4>
                    <div class="mb-4">
                        <span class="display-5 fw-bold">
                            <?= $plan['price'] > 0 ? '৳' . number_format($plan['price'], 0) : 'Free' ?>
                        </span>
                        <?php if ($plan['price'] > 0): ?>
                            <span class="text-muted">/<?= $plan['billing_cycle'] ?></span>
                        <?php endif; ?>
                    </div>
                    <ul class="list-unstyled mb-4 flex-grow-1">
                        <?php
                        $featureDefs = [
                            'max_products'  => [$plan['max_products'] < 0 ? 'Unlimited' : $plan['max_products'], ' products'],
                            'max_orders'    => [$plan['max_orders']  < 0 ? 'Unlimited' : number_format($plan['max_orders']), ' orders/mo'],
                            'max_staff'     => [$plan['max_staff']   < 0 ? 'Unlimited' : $plan['max_staff'],   ' staff users'],
                            'storage_gb'    => [$plan['storage_gb'], 'GB storage'],
                        ];
                        foreach ($featureDefs as [$val, $label]): ?>
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span><?= $val . $label ?></span>
                        </li>
                        <?php endforeach; ?>
                        <?php
                        $featureLabels = [
                            'analytics'         => ['Analytics','bi-graph-up'],
                            'custom_domain'     => ['Custom domain','bi-globe'],
                            'api_access'        => ['API access','bi-code-slash'],
                            'email_support'     => ['Email support','bi-envelope'],
                            'chat_support'      => ['Chat support','bi-chat-dots'],
                            'dedicated_support' => ['Dedicated support','bi-headset'],
                        ];
                        foreach ($featureLabels as $key => [$label, $icon]):
                            $has = !empty($features[$key]);
                        ?>
                        <li class="mb-2 d-flex align-items-center gap-2 <?= !$has ? 'text-muted' : '' ?>">
                            <i class="bi <?= $has ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' ?>"></i>
                            <span><?= $label ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="/auth/register?plan=<?= $plan['id'] ?>"
                       class="btn <?= $popular ? 'btn-primary' : 'btn-outline-primary' ?> w-100 fw-semibold py-2">
                        <?= $plan['price'] == 0 ? 'Start for Free' : 'Start 30-Day Trial' ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- FAQ -->
    <div class="mt-5 pt-4">
        <h3 class="fw-bold text-center mb-4">Frequently Asked Questions</h3>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <?php
                    $faqs = [
                        ['Can I change my plan later?', 'Yes! You can upgrade or downgrade your plan at any time from your store settings.'],
                        ['Is there a free trial?', 'All paid plans come with a 30-day free trial. No credit card required to start.'],
                        ['What payment methods do you accept?', 'We accept bKash, Nagad, credit/debit cards, and bank transfers.'],
                        ['Can I cancel anytime?', 'Yes, you can cancel your subscription at any time. You will retain access until the end of your billing period.'],
                    ];
                    foreach ($faqs as $i => [$q, $a]): ?>
                    <div class="accordion-item border-0 mb-2 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?> fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
                                <?= $q ?>
                            </button>
                        </h2>
                        <div id="faq<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted"><?= $a ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
