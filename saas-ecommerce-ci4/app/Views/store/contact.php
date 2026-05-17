<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | SaaSCommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
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
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="fw-bold">Contact Us</h1>
                <p class="lead text-muted">We're here to help. Send us a message and we'll respond within 24 hours.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width:56px;height:56px;font-size:1.4rem">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <h5 class="fw-semibold">Email Us</h5>
                            <p class="text-muted small mb-2">For general inquiries</p>
                            <a href="mailto:support@saascommerce.com" class="text-primary text-decoration-none">
                                support@saascommerce.com
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width:56px;height:56px;font-size:1.4rem">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <h5 class="fw-semibold">Call Us</h5>
                            <p class="text-muted small mb-2">Mon–Fri, 9AM–6PM</p>
                            <a href="tel:+8801700000000" class="text-success text-decoration-none">+880 1700-000000</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width:56px;height:56px;font-size:1.4rem">
                                <i class="bi bi-chat-dots"></i>
                            </div>
                            <h5 class="fw-semibold">Live Chat</h5>
                            <p class="text-muted small mb-2">Available for Pro+ plans</p>
                            <span class="badge bg-warning-subtle text-warning border">Pro Feature</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4">Send a Message</h4>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" placeholder="Your name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="you@example.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Subject</label>
                                <select class="form-select">
                                    <option>General Inquiry</option>
                                    <option>Technical Support</option>
                                    <option>Billing Issue</option>
                                    <option>Partnership</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" rows="5" placeholder="Describe your issue or question..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
