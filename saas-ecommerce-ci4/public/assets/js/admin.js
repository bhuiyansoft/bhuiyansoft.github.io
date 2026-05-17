/* ============================================================
   SaaSCommerce Admin JS
   ============================================================ */

'use strict';

// Sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');

    if (window.innerWidth <= 991) {
        sidebar.classList.toggle('mobile-open');
    } else {
        sidebar.classList.toggle('collapsed');
        if (mainContent) {
            mainContent.classList.toggle('expanded');
        }
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }
}

// Restore sidebar state
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    if (sidebar && window.innerWidth > 991) {
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert.alert-success').forEach(a => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(a);
            bsAlert?.close();
        });
    }, 4000);

    // Tooltips
    document.querySelectorAll('[title]').forEach(el => {
        new bootstrap.Tooltip(el, { trigger: 'hover', placement: 'top' });
    });

    // Confirm dialogs
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm || 'Are you sure?')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
});

// Image preview helper
function previewImage(input, previewId = 'image-preview', placeholderId = 'image-placeholder') {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            if (preview) { preview.src = e.target.result; preview.classList.remove('d-none'); }
            if (placeholder) { placeholder.classList.add('d-none'); }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Order status update via AJAX
function updateOrderStatus(orderId, status, paymentStatus) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    fetch(`/admin/orders/${orderId}/status`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: new URLSearchParams({ status, payment_status: paymentStatus, csrf_token: csrfToken })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Status updated successfully!', 'success');
        } else {
            showToast(data.message || 'Update failed', 'danger');
        }
    })
    .catch(() => showToast('Network error', 'danger'));
}

// Toast notification
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 show`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    container.appendChild(toast);
    setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 3500);
}

function createToastContainer() {
    const el = document.createElement('div');
    el.id = 'toast-container';
    el.className = 'toast-container position-fixed top-0 end-0 p-3';
    el.style.zIndex = '9999';
    document.body.appendChild(el);
    return el;
}

// Select all checkbox
document.addEventListener('change', function(e) {
    if (e.target.id === 'select-all') {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = e.target.checked);
    }
});

// Form validation highlight
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const btn = this.querySelector('[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + btn.innerHTML;
        }
    });
});

// Number formatting
function formatNumber(n, decimals = 0) {
    return Number(n).toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
}
