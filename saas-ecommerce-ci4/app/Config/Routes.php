<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// PUBLIC / LANDING PAGE
// ============================================================
$routes->get('/', 'Store\HomeController::index');
$routes->get('/pricing', 'Store\HomeController::pricing');
$routes->get('/contact', 'Store\HomeController::contact');

// ============================================================
// AUTH ROUTES
// ============================================================
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth\LoginController::index');
    $routes->post('login', 'Auth\LoginController::login');
    $routes->get('register', 'Auth\RegisterController::index');
    $routes->post('register', 'Auth\RegisterController::store');
    $routes->get('logout', 'Auth\LoginController::logout');
    $routes->get('forgot-password', 'Auth\LoginController::forgotPassword');
    $routes->post('forgot-password', 'Auth\LoginController::sendReset');
});

// ============================================================
// SUPER ADMIN ROUTES
// ============================================================
$routes->group('superadmin', ['filter' => 'auth:superadmin'], function ($routes) {
    $routes->get('dashboard', 'SuperAdmin\DashboardController::index');

    // Tenants
    $routes->get('tenants', 'SuperAdmin\TenantsController::index');
    $routes->get('tenants/create', 'SuperAdmin\TenantsController::create');
    $routes->post('tenants/store', 'SuperAdmin\TenantsController::store');
    $routes->get('tenants/(:num)/edit', 'SuperAdmin\TenantsController::edit/$1');
    $routes->post('tenants/(:num)/update', 'SuperAdmin\TenantsController::update/$1');
    $routes->get('tenants/(:num)/suspend', 'SuperAdmin\TenantsController::suspend/$1');
    $routes->get('tenants/(:num)/activate', 'SuperAdmin\TenantsController::activate/$1');
    $routes->get('tenants/(:num)', 'SuperAdmin\TenantsController::show/$1');

    // Plans
    $routes->get('plans', 'SuperAdmin\PlansController::index');
    $routes->get('plans/create', 'SuperAdmin\PlansController::create');
    $routes->post('plans/store', 'SuperAdmin\PlansController::store');
    $routes->get('plans/(:num)/edit', 'SuperAdmin\PlansController::edit/$1');
    $routes->post('plans/(:num)/update', 'SuperAdmin\PlansController::update/$1');

    // Revenue & Reports
    $routes->get('revenue', 'SuperAdmin\DashboardController::revenue');
    $routes->get('users', 'SuperAdmin\DashboardController::users');
});

// ============================================================
// ADMIN (TENANT) ROUTES
// ============================================================
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // Products
    $routes->get('products', 'Admin\ProductsController::index');
    $routes->get('products/create', 'Admin\ProductsController::create');
    $routes->post('products/store', 'Admin\ProductsController::store');
    $routes->get('products/(:num)/edit', 'Admin\ProductsController::edit/$1');
    $routes->post('products/(:num)/update', 'Admin\ProductsController::update/$1');
    $routes->get('products/(:num)/delete', 'Admin\ProductsController::delete/$1');
    $routes->get('products/(:num)', 'Admin\ProductsController::show/$1');

    // Categories
    $routes->get('categories', 'Admin\CategoriesController::index');
    $routes->get('categories/create', 'Admin\CategoriesController::create');
    $routes->post('categories/store', 'Admin\CategoriesController::store');
    $routes->get('categories/(:num)/edit', 'Admin\CategoriesController::edit/$1');
    $routes->post('categories/(:num)/update', 'Admin\CategoriesController::update/$1');
    $routes->get('categories/(:num)/delete', 'Admin\CategoriesController::delete/$1');

    // Orders
    $routes->get('orders', 'Admin\OrdersController::index');
    $routes->get('orders/(:num)', 'Admin\OrdersController::show/$1');
    $routes->post('orders/(:num)/status', 'Admin\OrdersController::updateStatus/$1');
    $routes->get('orders/(:num)/invoice', 'Admin\OrdersController::invoice/$1');

    // Customers
    $routes->get('customers', 'Admin\CustomersController::index');
    $routes->get('customers/(:num)', 'Admin\CustomersController::show/$1');
    $routes->get('customers/create', 'Admin\CustomersController::create');
    $routes->post('customers/store', 'Admin\CustomersController::store');

    // Coupons
    $routes->get('coupons', 'Admin\CouponsController::index');
    $routes->get('coupons/create', 'Admin\CouponsController::create');
    $routes->post('coupons/store', 'Admin\CouponsController::store');
    $routes->get('coupons/(:num)/delete', 'Admin\CouponsController::delete/$1');

    // Reports
    $routes->get('reports', 'Admin\ReportsController::index');
    $routes->get('reports/sales', 'Admin\ReportsController::sales');
    $routes->get('reports/products', 'Admin\ReportsController::products');
    $routes->get('reports/customers', 'Admin\ReportsController::customers');

    // Settings
    $routes->get('settings', 'Admin\SettingsController::index');
    $routes->post('settings/update', 'Admin\SettingsController::update');
    $routes->post('settings/logo', 'Admin\SettingsController::updateLogo');

    // Profile
    $routes->get('profile', 'Admin\ProfileController::index');
    $routes->post('profile/update', 'Admin\ProfileController::update');
    $routes->post('profile/password', 'Admin\ProfileController::changePassword');
});

// ============================================================
// STORE FRONT ROUTES
// ============================================================
$routes->group('shop', function ($routes) {
    $routes->get('/', 'Store\ShopController::index');
    $routes->get('product/(:segment)', 'Store\ShopController::product/$1');
    $routes->get('category/(:segment)', 'Store\ShopController::category/$1');
    $routes->get('cart', 'Store\CartController::index');
    $routes->post('cart/add', 'Store\CartController::add');
    $routes->post('cart/update', 'Store\CartController::update');
    $routes->get('cart/remove/(:num)', 'Store\CartController::remove/$1');
    $routes->get('checkout', 'Store\CheckoutController::index');
    $routes->post('checkout/place', 'Store\CheckoutController::placeOrder');
    $routes->get('order/success/(:segment)', 'Store\CheckoutController::success/$1');
    $routes->get('search', 'Store\ShopController::search');
});

// ============================================================
// API ROUTES (JSON)
// ============================================================
$routes->group('api/v1', ['filter' => 'cors'], function ($routes) {
    $routes->get('products', 'Api\ProductsController::index');
    $routes->get('products/(:num)', 'Api\ProductsController::show/$1');
    $routes->get('categories', 'Api\CategoriesController::index');
    $routes->get('orders/(:segment)', 'Api\OrdersController::show/$1');
    $routes->post('cart/validate-coupon', 'Api\CartController::validateCoupon');
});
