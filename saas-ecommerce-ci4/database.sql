-- ============================================================
-- SaaS E-Commerce System - Database Schema
-- CI4 | MySQL | Bootstrap 5
-- ============================================================

CREATE DATABASE IF NOT EXISTS saas_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE saas_ecommerce;

-- ============================================================
-- SUBSCRIPTION PLANS
-- ============================================================
CREATE TABLE plans (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    billing_cycle ENUM('monthly','yearly') DEFAULT 'monthly',
    max_products INT DEFAULT 50,
    max_orders INT DEFAULT 500,
    max_staff INT DEFAULT 3,
    storage_gb INT DEFAULT 1,
    features JSON,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TENANTS (Stores)
-- ============================================================
CREATE TABLE tenants (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    phone VARCHAR(20),
    subdomain VARCHAR(100) NOT NULL UNIQUE,
    custom_domain VARCHAR(255),
    plan_id INT UNSIGNED,
    logo VARCHAR(500),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100) DEFAULT 'Bangladesh',
    postal_code VARCHAR(20),
    currency VARCHAR(10) DEFAULT 'BDT',
    currency_symbol VARCHAR(10) DEFAULT '৳',
    timezone VARCHAR(50) DEFAULT 'Asia/Dhaka',
    tax_rate DECIMAL(5,2) DEFAULT 0.00,
    shipping_rate DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('active','suspended','trial','cancelled') DEFAULT 'trial',
    trial_ends_at DATETIME,
    settings JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE SET NULL
);

-- ============================================================
-- SUBSCRIPTIONS
-- ============================================================
CREATE TABLE subscriptions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT UNSIGNED NOT NULL,
    plan_id INT UNSIGNED NOT NULL,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('active','expired','cancelled','pending') DEFAULT 'active',
    payment_method VARCHAR(50),
    transaction_id VARCHAR(200),
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE CASCADE
);

-- ============================================================
-- USERS (Super Admin + Tenant Staff)
-- ============================================================
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT UNSIGNED,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(191) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    avatar VARCHAR(500),
    role ENUM('superadmin','admin','manager','staff') DEFAULT 'admin',
    is_active TINYINT(1) DEFAULT 1,
    email_verified_at DATETIME,
    remember_token VARCHAR(255),
    last_login_at DATETIME,
    last_login_ip VARCHAR(45),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_email_tenant (email, tenant_id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
);

-- ============================================================
-- CATEGORIES
-- ============================================================
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT UNSIGNED NOT NULL,
    parent_id INT UNSIGNED,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    UNIQUE KEY unique_slug_tenant (slug, tenant_id)
);

-- ============================================================
-- PRODUCTS
-- ============================================================
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED,
    sku VARCHAR(100),
    name VARCHAR(300) NOT NULL,
    slug VARCHAR(300) NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    sale_price DECIMAL(10,2),
    cost_price DECIMAL(10,2),
    stock INT DEFAULT 0,
    low_stock_alert INT DEFAULT 5,
    weight DECIMAL(8,3),
    unit VARCHAR(50) DEFAULT 'pcs',
    image VARCHAR(500),
    gallery JSON,
    type ENUM('physical','digital','service') DEFAULT 'physical',
    status ENUM('active','inactive','draft') DEFAULT 'active',
    is_featured TINYINT(1) DEFAULT 0,
    meta_title VARCHAR(300),
    meta_description VARCHAR(500),
    total_sold INT DEFAULT 0,
    views INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    UNIQUE KEY unique_slug_tenant (slug, tenant_id)
);

-- ============================================================
-- PRODUCT ATTRIBUTES / VARIANTS
-- ============================================================
CREATE TABLE product_variants (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    name VARCHAR(200) NOT NULL,
    sku VARCHAR(100),
    price DECIMAL(10,2),
    stock INT DEFAULT 0,
    attributes JSON,
    image VARCHAR(500),
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ============================================================
-- CUSTOMERS
-- ============================================================
CREATE TABLE customers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT UNSIGNED NOT NULL,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(191) NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255),
    avatar VARCHAR(500),
    date_of_birth DATE,
    gender ENUM('male','female','other'),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100),
    postal_code VARCHAR(20),
    is_active TINYINT(1) DEFAULT 1,
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(12,2) DEFAULT 0.00,
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_email_tenant (email, tenant_id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
);

-- ============================================================
-- COUPONS
-- ============================================================
CREATE TABLE coupons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT UNSIGNED NOT NULL,
    code VARCHAR(50) NOT NULL,
    type ENUM('percentage','fixed') DEFAULT 'percentage',
    value DECIMAL(10,2) NOT NULL,
    min_order DECIMAL(10,2) DEFAULT 0.00,
    max_uses INT,
    used_count INT DEFAULT 0,
    expires_at DATETIME,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_code_tenant (code, tenant_id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
);

-- ============================================================
-- ORDERS
-- ============================================================
CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT UNSIGNED NOT NULL,
    customer_id INT UNSIGNED,
    order_number VARCHAR(50) NOT NULL,
    status ENUM('pending','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
    payment_status ENUM('unpaid','paid','partial','refunded') DEFAULT 'unpaid',
    payment_method VARCHAR(50),
    transaction_id VARCHAR(200),
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    tax_amount DECIMAL(10,2) DEFAULT 0.00,
    shipping_amount DECIMAL(10,2) DEFAULT 0.00,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    coupon_id INT UNSIGNED,
    shipping_name VARCHAR(200),
    shipping_email VARCHAR(191),
    shipping_phone VARCHAR(20),
    shipping_address TEXT,
    shipping_city VARCHAR(100),
    shipping_state VARCHAR(100),
    shipping_country VARCHAR(100),
    shipping_postal VARCHAR(20),
    billing_name VARCHAR(200),
    billing_address TEXT,
    notes TEXT,
    shipped_at DATETIME,
    delivered_at DATETIME,
    cancelled_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_order_tenant (order_number, tenant_id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE SET NULL
);

-- ============================================================
-- ORDER ITEMS
-- ============================================================
CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED,
    variant_id INT UNSIGNED,
    product_name VARCHAR(300) NOT NULL,
    product_sku VARCHAR(100),
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    product_snapshot JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL
);

-- ============================================================
-- PAYMENTS
-- ============================================================
CREATE TABLE payments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT UNSIGNED NOT NULL,
    order_id INT UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    method VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(200),
    status ENUM('pending','completed','failed','refunded') DEFAULT 'pending',
    gateway_response JSON,
    notes TEXT,
    paid_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- ============================================================
-- REVIEWS
-- ============================================================
CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    customer_id INT UNSIGNED,
    rating TINYINT NOT NULL DEFAULT 5,
    title VARCHAR(200),
    body TEXT,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
);

-- ============================================================
-- ACTIVITY LOG
-- ============================================================
CREATE TABLE activity_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id INT UNSIGNED,
    user_id INT UNSIGNED,
    type VARCHAR(100),
    description TEXT,
    subject_type VARCHAR(100),
    subject_id INT UNSIGNED,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tenant (tenant_id),
    INDEX idx_user (user_id)
);

-- ============================================================
-- SEED DATA
-- ============================================================

INSERT INTO plans (name, slug, price, billing_cycle, max_products, max_orders, max_staff, storage_gb, features, sort_order) VALUES
('Starter', 'starter', 0.00, 'monthly', 20, 100, 1, 1, '{"analytics":false,"custom_domain":false,"api_access":false,"email_support":true}', 1),
('Basic', 'basic', 999.00, 'monthly', 100, 1000, 3, 5, '{"analytics":true,"custom_domain":false,"api_access":false,"email_support":true,"chat_support":false}', 2),
('Professional', 'professional', 2499.00, 'monthly', 500, 10000, 10, 20, '{"analytics":true,"custom_domain":true,"api_access":true,"email_support":true,"chat_support":true}', 3),
('Enterprise', 'enterprise', 9999.00, 'monthly', -1, -1, -1, 100, '{"analytics":true,"custom_domain":true,"api_access":true,"email_support":true,"chat_support":true,"dedicated_support":true,"sla":true}', 4);

INSERT INTO tenants (name, email, phone, subdomain, plan_id, status, currency, currency_symbol, trial_ends_at) VALUES
('BhuiyanSoft Demo Store', 'admin@demo.com', '01700000000', 'demo', 3, 'active', 'BDT', '৳', DATE_ADD(NOW(), INTERVAL 30 DAY));

INSERT INTO users (tenant_id, name, email, password, role, is_active, email_verified_at) VALUES
(NULL, 'Super Admin', 'superadmin@saas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin', 1, NOW()),
(1, 'Store Admin', 'admin@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW());

-- Default password: password

INSERT INTO categories (tenant_id, name, slug, sort_order, is_active) VALUES
(1, 'Electronics', 'electronics', 1, 1),
(1, 'Clothing', 'clothing', 2, 1),
(1, 'Home & Garden', 'home-garden', 3, 1),
(1, 'Books', 'books', 4, 1),
(1, 'Sports', 'sports', 5, 1);

INSERT INTO products (tenant_id, category_id, sku, name, slug, description, price, sale_price, stock, unit, status, is_featured) VALUES
(1, 1, 'ELEC-001', 'Wireless Bluetooth Headphones', 'wireless-bluetooth-headphones', 'Premium quality wireless headphones with noise cancellation.', 2500.00, 1999.00, 50, 'pcs', 'active', 1),
(1, 1, 'ELEC-002', 'Smart Watch Pro', 'smart-watch-pro', 'Feature-rich smartwatch with health monitoring.', 5500.00, 4499.00, 30, 'pcs', 'active', 1),
(1, 2, 'CLO-001', 'Premium Cotton T-Shirt', 'premium-cotton-tshirt', '100% cotton comfortable t-shirt.', 850.00, 699.00, 200, 'pcs', 'active', 0),
(1, 3, 'HG-001', 'LED Desk Lamp', 'led-desk-lamp', 'Energy efficient LED desk lamp with adjustable brightness.', 1200.00, NULL, 75, 'pcs', 'active', 0),
(1, 1, 'ELEC-003', 'USB-C Hub 7-in-1', 'usbc-hub-7in1', 'Multi-port USB-C hub for laptop connectivity.', 1800.00, 1499.00, 40, 'pcs', 'active', 1);

INSERT INTO customers (tenant_id, name, email, phone, city, country, is_active) VALUES
(1, 'Rahim Uddin', 'rahim@example.com', '01711111111', 'Dhaka', 'Bangladesh', 1),
(1, 'Karim Ahmed', 'karim@example.com', '01722222222', 'Chittagong', 'Bangladesh', 1),
(1, 'Nasrin Begum', 'nasrin@example.com', '01733333333', 'Sylhet', 'Bangladesh', 1);
