<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Unavailable</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { border-radius: 20px; padding: 3rem; text-align: center; max-width: 520px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,.08); }
        .icon { width: 80px; height: 80px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; color: #ef4444; }
    </style>
</head>
<body>
    <div class="card">
        <?php
        $messages = [
            'not_published'    => ['bi-lock', 'Survey Not Available', 'This survey is not currently accepting responses.'],
            'expired'          => ['bi-clock-history', 'Survey Closed', 'This survey has ended and is no longer accepting responses.'],
            'limit_reached'    => ['bi-inbox', 'Response Limit Reached', 'This survey has reached its maximum number of responses.'],
            'already_responded' => ['bi-check-circle', 'Already Responded', "You've already submitted a response to this survey."],
        ];
        [$icon, $title, $msg] = $messages[$reason] ?? ['bi-question-circle', 'Unavailable', 'This survey is not available.'];
        ?>
        <div class="icon"><i class="bi <?= $icon ?>"></i></div>
        <h2 class="fw-bold mb-3"><?= $title ?></h2>
        <p class="text-muted"><?= $msg ?></p>
        <div class="text-muted small mt-4">Powered by <strong>SurveyStack</strong></div>
    </div>
</body>
</html>
