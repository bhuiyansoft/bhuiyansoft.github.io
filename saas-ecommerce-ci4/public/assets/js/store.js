/* ============================================================
   SaaSCommerce Store JS
   ============================================================ */

'use strict';

// Add to cart
async function addToCart(productId, qty = 1) {
    const btn = document.querySelector(`[data-product-id="${productId}"]`);
    if (btn) { btn.classList.add('loading'); btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Adding...'; }

    try {
        const resp = await fetch('/shop/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ product_id: productId, qty })
        });
        const data = await resp.json();

        if (data.success) {
            updateCartBadge(data.cart_count);
            showCartToast(data.message);
        } else {
            showAlert(data.message || 'Failed to add to cart.', 'danger');
        }
    } catch {
        showAlert('Network error. Please try again.', 'danger');
    } finally {
        if (btn) { btn.classList.remove('loading'); btn.innerHTML = '<i class="bi bi-cart-plus me-1"></i>Add to Cart'; }
    }
}

function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (count > 0) {
        if (!badge) {
            const cartBtn = document.querySelector('.cart-btn-wrapper');
            if (cartBtn) {
                const b = document.createElement('span');
                b.id = 'cart-badge';
                b.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                b.textContent = count;
                cartBtn.appendChild(b);
            }
        } else {
            badge.textContent = count;
        }
    }
}

function showCartToast(msg) {
    const container = document.getElementById('toast-container') || (() => {
        const el = document.createElement('div');
        el.id = 'toast-container';
        el.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        el.style.zIndex = '9999';
        document.body.appendChild(el);
        return el;
    })();

    const t = document.createElement('div');
    t.className = 'toast align-items-center text-white bg-success border-0 show';
    t.innerHTML = `<div class="d-flex"><div class="toast-body"><i class="bi bi-cart-check me-2"></i>${msg}</div><button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    container.appendChild(t);
    setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 300); }, 2500);
}

function showAlert(msg, type = 'danger') {
    const el = document.createElement('div');
    el.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    el.style.zIndex = '9999';
    el.innerHTML = `${msg}<button class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

// Quantity controls
document.addEventListener('click', function(e) {
    if (e.target.closest('.qty-minus')) {
        const input = e.target.closest('.qty-control').querySelector('input');
        if (input && parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            input.dispatchEvent(new Event('change'));
        }
    }
    if (e.target.closest('.qty-plus')) {
        const input = e.target.closest('.qty-control').querySelector('input');
        const max = parseInt(input?.getAttribute('max')) || 999;
        if (input && parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
            input.dispatchEvent(new Event('change'));
        }
    }
});

// Coupon validation
const couponForm = document.getElementById('coupon-form');
if (couponForm) {
    couponForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const code = document.getElementById('coupon-code').value.trim();
        if (!code) return;

        try {
            const resp = await fetch('/api/v1/cart/validate-coupon', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ code })
            });
            const data = await resp.json();

            const msg = document.getElementById('coupon-message');
            if (data.valid) {
                msg.className = 'mt-2 small text-success';
                msg.innerHTML = `<i class="bi bi-check-circle me-1"></i>Coupon applied! You save ${data.discount}`;
                document.getElementById('coupon-discount').value = data.discount;
            } else {
                msg.className = 'mt-2 small text-danger';
                msg.innerHTML = `<i class="bi bi-x-circle me-1"></i>${data.message}`;
            }
        } catch {
            console.error('Coupon check failed');
        }
    });
}
