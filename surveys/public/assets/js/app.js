// SurveyStack — Main Application JS

document.addEventListener('DOMContentLoaded', function () {
    // Sidebar toggle on mobile
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    if (toggle && sidebar) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
        if (mainContent) {
            mainContent.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    sidebar.classList.remove('open');
                }
            });
        }
    }

    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert:not(.alert-permanent)').forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    // Confirm dialogs
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // Copy to clipboard helper
    window.copyToClipboard = function (text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function () {
                showToast('Copied to clipboard!', 'success');
            });
        } else {
            const input = document.createElement('textarea');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showToast('Copied!', 'success');
        }
    };

    // Toast notification
    window.showToast = function (message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `position-fixed bottom-0 end-0 m-3 alert alert-${type} alert-dismissible fade show`;
        toast.style.cssText = 'z-index: 9999; min-width: 250px; max-width: 400px;';
        toast.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        setTimeout(function () {
            if (toast.parentNode) toast.remove();
        }, 4000);
    };

    // Responsive tables on mobile
    document.querySelectorAll('.table-responsive').forEach(function (el) {
        if (el.scrollWidth > el.clientWidth) {
            el.classList.add('has-scroll');
        }
    });
});
