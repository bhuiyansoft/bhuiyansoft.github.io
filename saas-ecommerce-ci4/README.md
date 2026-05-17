# SaaSCommerce — Multi-Tenant E-Commerce Platform (CodeIgniter 4)

A production-ready SaaS e-commerce platform built with **CodeIgniter 4**, **MySQL**, and **Bootstrap 5**. Each business gets its own isolated storefront managed through a shared admin panel, with superadmin controls for the platform operator.

---

## Features

- **Multi-tenant architecture** — shared database, tenant-isolated data
- **SaaS subscription plans** — Starter (Free), Basic, Professional, Enterprise
- **Storefront** — product catalog, cart, checkout, coupon support
- **Admin panel** — dashboard, orders, products, categories, customers, coupons, reports
- **Superadmin panel** — tenant management, plan management, revenue overview
- **REST API** — products, categories, orders, cart with coupon validation
- **Role-based access** — superadmin, admin, manager, staff
- **Chart.js dashboards** — sales trends, revenue charts, top products
- **Image uploads** — local storage with preview
- **Activity logging** — all key actions tracked

---

## Requirements

| Dependency | Version |
|---|---|
| PHP | >= 8.1 |
| MySQL | >= 5.7 / MariaDB >= 10.4 |
| Composer | >= 2.x |
| Web server | Apache / Nginx |

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/bhuiyansoft/bhuiyansoft.github.io.git
cd bhuiyansoft.github.io/saas-ecommerce-ci4
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure environment

```bash
cp .env.example .env
```

Edit `.env`:

```ini
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = saas_ecommerce
database.default.username = root
database.default.password = your_password
database.default.DBDriver = MySQLi

encryption.key = hex2bin:your-32-byte-hex-key-here
```

Generate a key:
```bash
php spark key:generate
```

### 4. Create the database

```bash
mysql -u root -p -e "CREATE DATABASE saas_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Import schema and seed data

```bash
mysql -u root -p saas_ecommerce < database.sql
```

### 6. Set permissions

```bash
chmod -R 777 writable/
```

### 7. Configure web server

**Apache** — point `DocumentRoot` to the `public/` directory, or use the included `.htaccess`.

**Nginx:**
```nginx
server {
    listen 80;
    server_name localhost;
    root /path/to/saas-ecommerce-ci4/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## Default Credentials

| Role | Email | Password |
|---|---|---|
| Superadmin | superadmin@saascommerce.com | password |
| Store Admin | admin@demoshop.com | password |

> Change these immediately in production.

---

## Application Structure

```
saas-ecommerce-ci4/
├── app/
│   ├── Config/
│   │   ├── Routes.php          # All routes
│   │   ├── Filters.php         # Auth, Tenant, CORS filters
│   │   └── Database.php
│   ├── Controllers/
│   │   ├── Auth/               # Login, Register, Logout
│   │   ├── Admin/              # Tenant admin panel
│   │   ├── Superadmin/         # Platform operator panel
│   │   ├── Store/              # Public storefront
│   │   └── Api/                # REST API (v1)
│   ├── Models/
│   │   ├── UserModel.php
│   │   ├── ProductModel.php
│   │   ├── OrderModel.php
│   │   ├── CouponModel.php
│   │   └── ...
│   ├── Filters/
│   │   ├── AuthFilter.php
│   │   ├── TenantFilter.php
│   │   └── CorsFilter.php
│   ├── Helpers/
│   │   └── app_helper.php      # Badge, format, time helpers
│   └── Views/
│       ├── layouts/            # admin.php, superadmin.php, store.php
│       ├── admin/              # Admin panel views
│       ├── superadmin/         # Superadmin panel views
│       ├── store/              # Storefront views
│       └── auth/               # Login, register, forgot password
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── admin.css
│   │   │   └── landing.css
│   │   └── js/
│   │       ├── admin.js
│   │       └── store.js
│   └── index.php
├── writable/
│   └── uploads/                # Product images
├── database.sql                # Full schema + seed data
├── composer.json
└── .env                        # Local config (not committed)
```

---

## URL Structure

| URL | Description |
|---|---|
| `/` | Landing page |
| `/pricing` | Pricing plans |
| `/contact` | Contact page |
| `/auth/login` | Login |
| `/auth/register` | Register new tenant |
| `/shop` | Storefront product listing |
| `/shop/product/{slug}` | Product detail |
| `/shop/cart` | Cart |
| `/shop/checkout` | Checkout |
| `/admin/dashboard` | Admin dashboard |
| `/superadmin/dashboard` | Superadmin dashboard |
| `/api/v1/products` | API: Products list |
| `/api/v1/categories` | API: Categories |
| `/api/v1/orders` | API: Orders (auth required) |
| `/api/v1/cart` | API: Cart |
| `/api/v1/cart/validate-coupon` | API: Coupon validation |

---

## REST API

Pass the tenant identifier via the `X-Tenant-ID` header on all API requests.

```bash
curl -H "X-Tenant-ID: 1" http://localhost/api/v1/products
curl -H "X-Tenant-ID: 1" http://localhost/api/v1/products?search=shirt&category=2
curl -H "X-Tenant-ID: 1" http://localhost/api/v1/products/featured
curl -H "X-Tenant-ID: 1" http://localhost/api/v1/categories
curl -H "X-Tenant-ID: 1" -X POST http://localhost/api/v1/cart/add \
  -H "Content-Type: application/json" \
  -d '{"product_id": 1, "qty": 2}'
curl -H "X-Tenant-ID: 1" -X POST http://localhost/api/v1/cart/validate-coupon \
  -H "Content-Type: application/json" \
  -d '{"code": "SAVE10"}'
```

---

## Subscription Plans

| Feature | Starter | Basic | Professional | Enterprise |
|---|---|---|---|---|
| Price | Free | ৳999/mo | ৳2,499/mo | ৳5,999/mo |
| Products | 10 | 100 | Unlimited | Unlimited |
| Orders/mo | 50 | 500 | Unlimited | Unlimited |
| Staff users | 1 | 3 | 10 | Unlimited |
| Storage | 1 GB | 5 GB | 20 GB | 100 GB |
| Analytics | — | ✓ | ✓ | ✓ |
| Custom domain | — | — | ✓ | ✓ |
| API access | — | — | ✓ | ✓ |
| Email support | — | ✓ | ✓ | ✓ |
| Chat support | — | — | ✓ | ✓ |
| Dedicated support | — | — | — | ✓ |

---

## License

MIT — free to use and modify.
