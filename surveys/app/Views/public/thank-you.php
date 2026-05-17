<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You — <?= esc($survey['title']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .thank-you-card { background: white; border-radius: 20px; padding: 3rem; text-align: center; max-width: 520px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,.08); }
        .success-icon { width: 80px; height: 80px; background: linear-gradient(135deg, <?= esc($survey['primary_color'] ?? '#4F46E5') ?>, <?= esc($survey['primary_color'] ?? '#4F46E5') ?>bb); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; color: white; }
        .thank-you-title { font-size: 1.75rem; font-weight: 700; color: #111; margin-bottom: 1rem; }
        .thank-you-msg { color: #6b7280; font-size: 1.05rem; line-height: 1.6; margin-bottom: 2rem; }
        .powered-by { color: #9ca3af; font-size: 0.85rem; margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="thank-you-card">
        <div class="success-icon">
            <i class="bi bi-check-lg"></i>
        </div>
        <h1 class="thank-you-title">Thank you!</h1>
        <p class="thank-you-msg">
            <?= esc($survey['thank_you_message'] ?? 'Your response has been recorded. We appreciate your time!') ?>
        </p>
        <div class="powered-by">
            Powered by <strong>SurveyStack</strong>
        </div>
    </div>
</body>
</html>
