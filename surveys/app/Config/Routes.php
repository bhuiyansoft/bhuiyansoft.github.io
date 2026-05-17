<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public routes
$routes->get('/', 'HomeController::index');
$routes->get('/pricing', 'HomeController::pricing');
$routes->get('/features', 'HomeController::features');

// Auth routes
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth\LoginController::index');
    $routes->post('login', 'Auth\LoginController::login');
    $routes->get('register', 'Auth\RegisterController::index');
    $routes->post('register', 'Auth\RegisterController::register');
    $routes->get('logout', 'Auth\LoginController::logout');
    $routes->get('forgot-password', 'Auth\ForgotPasswordController::index');
    $routes->post('forgot-password', 'Auth\ForgotPasswordController::send');
    $routes->get('reset-password/(:segment)', 'Auth\ForgotPasswordController::reset/$1');
    $routes->post('reset-password/(:segment)', 'Auth\ForgotPasswordController::update/$1');
});

// Dashboard (authenticated)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Surveys
    $routes->get('surveys', 'SurveysController::index');
    $routes->get('surveys/create', 'SurveysController::create');
    $routes->post('surveys/create', 'SurveysController::store');
    $routes->get('surveys/(:num)/edit', 'SurveysController::edit/$1');
    $routes->post('surveys/(:num)/edit', 'SurveysController::update/$1');
    $routes->post('surveys/(:num)/delete', 'SurveysController::delete/$1');
    $routes->post('surveys/(:num)/publish', 'SurveysController::publish/$1');
    $routes->post('surveys/(:num)/close', 'SurveysController::close/$1');
    $routes->get('surveys/(:num)/share', 'SurveysController::share/$1');
    $routes->get('surveys/(:num)/preview', 'SurveysController::preview/$1');

    // Survey Builder (AJAX)
    $routes->post('surveys/(:num)/questions', 'QuestionsController::store/$1');
    $routes->put('surveys/questions/(:num)', 'QuestionsController::update/$1');
    $routes->delete('surveys/questions/(:num)', 'QuestionsController::delete/$1');
    $routes->post('surveys/(:num)/questions/reorder', 'QuestionsController::reorder/$1');

    // Responses & Analytics
    $routes->get('surveys/(:num)/responses', 'ResponsesController::index/$1');
    $routes->get('surveys/(:num)/responses/(:num)', 'ResponsesController::show/$1/$2');
    $routes->get('surveys/(:num)/reports', 'ReportsController::show/$1');
    $routes->get('surveys/(:num)/reports/export/(:alpha)', 'ReportsController::export/$1/$2');

    // Settings
    $routes->get('settings', 'SettingsController::index');
    $routes->post('settings/profile', 'SettingsController::updateProfile');
    $routes->post('settings/password', 'SettingsController::updatePassword');
    $routes->post('settings/organization', 'SettingsController::updateOrganization');

    // Team management
    $routes->get('team', 'TeamController::index');
    $routes->post('team/invite', 'TeamController::invite');
    $routes->post('team/(:num)/remove', 'TeamController::remove/$1');
    $routes->post('team/(:num)/role', 'TeamController::updateRole/$1');

    // Billing
    $routes->get('billing', 'BillingController::index');
    $routes->post('billing/upgrade', 'BillingController::upgrade');
});

// Public survey taking
$routes->get('s/(:segment)', 'Public\SurveyPublicController::show/$1');
$routes->post('s/(:segment)', 'Public\SurveyPublicController::submit/$1');
$routes->get('s/(:segment)/thank-you', 'Public\SurveyPublicController::thankYou/$1');

// API routes (for AJAX)
$routes->group('api/v1', ['filter' => 'auth'], function ($routes) {
    $routes->get('surveys', 'Api\SurveysApiController::index');
    $routes->get('surveys/(:num)/stats', 'Api\SurveysApiController::stats/$1');
    $routes->get('surveys/(:num)/responses/data', 'Api\ResponsesApiController::chartData/$1');
});
