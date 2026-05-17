<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Share Survey</h1>
        <p class="page-subtitle"><?= esc($survey['title']) ?></p>
    </div>
    <a href="/surveys/<?= $survey['id'] ?>/edit" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Editor
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <?php if ($survey['status'] !== 'published'): ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            This survey is not published yet. Publish it first to share with respondents.
            <a href="/surveys/<?= $survey['id'] ?>/edit" class="alert-link ms-2">Go to editor →</a>
        </div>
        <?php endif; ?>

        <?php $surveyUrl = base_url('s/' . $survey['slug']); ?>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-link-45deg me-2"></i>Share Link</h5>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <input type="text" class="form-control" id="surveyLink" value="<?= esc($surveyUrl) ?>" readonly>
                    <button class="btn btn-primary" onclick="copyLink()">
                        <i class="bi bi-copy me-1"></i>Copy
                    </button>
                    <a href="<?= esc($surveyUrl) ?>" target="_blank" class="btn btn-outline-secondary">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </div>
                <div class="text-muted small mt-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Share this link via email, social media, or embed it on your website.
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-qr-code me-2"></i>QR Code</h5>
            </div>
            <div class="card-body text-center">
                <div id="qrCode" class="mb-3 d-flex justify-content-center"></div>
                <p class="text-muted small">Scan this QR code with a mobile device to take the survey</p>
                <button class="btn btn-outline-secondary" onclick="downloadQR()">
                    <i class="bi bi-download me-2"></i>Download QR Code
                </button>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-code-slash me-2"></i>Embed Code</h5>
            </div>
            <div class="card-body">
                <textarea class="form-control font-monospace" rows="4" readonly><?= esc('<iframe src="' . $surveyUrl . '" width="100%" height="600" frameborder="0" style="border-radius:8px"></iframe>') ?></textarea>
                <button class="btn btn-outline-secondary btn-sm mt-2" onclick="copyEmbed()">
                    <i class="bi bi-copy me-1"></i>Copy Embed Code
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-share me-2"></i>Share via</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3 flex-wrap">
                    <a href="mailto:?subject=<?= urlencode($survey['title']) ?>&body=<?= urlencode("Please complete our survey: $surveyUrl") ?>"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-envelope me-2"></i>Email
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($surveyUrl) ?>" target="_blank"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-linkedin me-2"></i>LinkedIn
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode($surveyUrl) ?>&text=<?= urlencode('Please take our survey: ' . $survey['title']) ?>" target="_blank"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-twitter-x me-2"></i>Twitter/X
                    </a>
                    <a href="https://www.facebook.com/sharer.php?u=<?= urlencode($surveyUrl) ?>" target="_blank"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-facebook me-2"></i>Facebook
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
const surveyUrl = <?= json_encode($surveyUrl) ?>;

new QRCode(document.getElementById('qrCode'), {
    text: surveyUrl,
    width: 200,
    height: 200,
    colorDark: '#4F46E5',
    colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.H
});

function copyLink() {
    navigator.clipboard.writeText(surveyUrl);
    showToast('Link copied to clipboard!');
}

function copyEmbed() {
    const code = `<iframe src="${surveyUrl}" width="100%" height="600" frameborder="0" style="border-radius:8px"></iframe>`;
    navigator.clipboard.writeText(code);
    showToast('Embed code copied!');
}

function downloadQR() {
    const canvas = document.querySelector('#qrCode canvas');
    if (!canvas) return;
    const link = document.createElement('a');
    link.download = 'survey-qr.png';
    link.href = canvas.toDataURL();
    link.click();
}

function showToast(msg) {
    const toast = document.createElement('div');
    toast.className = 'position-fixed bottom-0 end-0 m-3 alert alert-success';
    toast.style.zIndex = 9999;
    toast.innerHTML = `<i class="bi bi-check-circle me-2"></i>${msg}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
<?= $this->endSection() ?>
