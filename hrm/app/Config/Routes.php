<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth\AuthController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// ============================================================
// AUTH ROUTES
// ============================================================
$routes->group('', ['namespace' => 'App\Controllers\Auth'], function ($routes) {
    $routes->get('/',         'AuthController::login');
    $routes->get('login',     'AuthController::login');
    $routes->post('login',    'AuthController::loginProcess');
    $routes->get('logout',    'AuthController::logout');
    $routes->get('forgot-password',  'AuthController::forgotPassword');
    $routes->post('forgot-password', 'AuthController::forgotPasswordProcess');
    $routes->get('reset-password/(:segment)',  'AuthController::resetPassword/$1');
    $routes->post('reset-password/(:segment)', 'AuthController::resetPasswordProcess/$1');
});

// ============================================================
// ADMIN ROUTES (protected)
// ============================================================
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], function ($routes) {

    // Dashboard
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');

    // Employees
    $routes->get('employees',            'EmployeeController::index');
    $routes->get('employees/create',     'EmployeeController::create');
    $routes->post('employees/store',     'EmployeeController::store');
    $routes->get('employees/(:num)',     'EmployeeController::show/$1');
    $routes->get('employees/edit/(:num)',   'EmployeeController::edit/$1');
    $routes->post('employees/update/(:num)', 'EmployeeController::update/$1');
    $routes->post('employees/delete/(:num)', 'EmployeeController::delete/$1');
    $routes->get('employees/export',     'EmployeeController::export');

    // Departments
    $routes->get('departments',              'DepartmentController::index');
    $routes->get('departments/create',       'DepartmentController::create');
    $routes->post('departments/store',       'DepartmentController::store');
    $routes->get('departments/edit/(:num)',  'DepartmentController::edit/$1');
    $routes->post('departments/update/(:num)', 'DepartmentController::update/$1');
    $routes->post('departments/delete/(:num)', 'DepartmentController::delete/$1');

    // Designations
    $routes->get('designations',                 'DesignationController::index');
    $routes->get('designations/create',          'DesignationController::create');
    $routes->post('designations/store',          'DesignationController::store');
    $routes->get('designations/edit/(:num)',     'DesignationController::edit/$1');
    $routes->post('designations/update/(:num)',  'DesignationController::update/$1');
    $routes->post('designations/delete/(:num)',  'DesignationController::delete/$1');
    $routes->get('designations/by-department/(:num)', 'DesignationController::byDepartment/$1');

    // Attendance
    $routes->get('attendance',                   'AttendanceController::index');
    $routes->get('attendance/mark',              'AttendanceController::mark');
    $routes->post('attendance/store',            'AttendanceController::store');
    $routes->post('attendance/bulk-store',       'AttendanceController::bulkStore');
    $routes->get('attendance/edit/(:num)',       'AttendanceController::edit/$1');
    $routes->post('attendance/update/(:num)',    'AttendanceController::update/$1');
    $routes->get('attendance/report',            'AttendanceController::report');
    $routes->get('attendance/employee/(:num)',   'AttendanceController::employeeAttendance/$1');

    // Leave Management
    $routes->get('leaves',                    'LeaveController::index');
    $routes->get('leaves/create',             'LeaveController::create');
    $routes->post('leaves/store',             'LeaveController::store');
    $routes->get('leaves/(:num)',             'LeaveController::show/$1');
    $routes->post('leaves/approve/(:num)',    'LeaveController::approve/$1');
    $routes->post('leaves/reject/(:num)',     'LeaveController::reject/$1');
    $routes->post('leaves/cancel/(:num)',     'LeaveController::cancel/$1');
    $routes->get('leaves/types',              'LeaveController::types');
    $routes->post('leaves/types/store',       'LeaveController::storeType');
    $routes->get('leaves/allocations',        'LeaveController::allocations');
    $routes->post('leaves/allocations/store', 'LeaveController::storeAllocation');
    $routes->get('leaves/balance/(:num)',     'LeaveController::balance/$1');

    // Payroll
    $routes->get('payroll',                       'PayrollController::index');
    $routes->get('payroll/generate',              'PayrollController::generate');
    $routes->post('payroll/process',              'PayrollController::process');
    $routes->get('payroll/(:num)',                'PayrollController::show/$1');
    $routes->post('payroll/mark-paid/(:num)',     'PayrollController::markPaid/$1');
    $routes->get('payroll/payslip/(:num)',        'PayrollController::payslip/$1');
    $routes->get('payroll/report',               'PayrollController::report');
    $routes->post('payroll/delete/(:num)',        'PayrollController::delete/$1');

    // Recruitment
    $routes->get('recruitment',                   'RecruitmentController::index');
    $routes->get('recruitment/jobs',              'RecruitmentController::jobs');
    $routes->get('recruitment/jobs/create',       'RecruitmentController::createJob');
    $routes->post('recruitment/jobs/store',       'RecruitmentController::storeJob');
    $routes->get('recruitment/jobs/edit/(:num)',  'RecruitmentController::editJob/$1');
    $routes->post('recruitment/jobs/update/(:num)', 'RecruitmentController::updateJob/$1');
    $routes->post('recruitment/jobs/delete/(:num)', 'RecruitmentController::deleteJob/$1');
    $routes->get('recruitment/applications',      'RecruitmentController::applications');
    $routes->get('recruitment/applications/(:num)', 'RecruitmentController::showApplication/$1');
    $routes->post('recruitment/applications/update-stage/(:num)', 'RecruitmentController::updateStage/$1');

    // Performance
    $routes->get('performance',                    'PerformanceController::index');
    $routes->get('performance/create',             'PerformanceController::create');
    $routes->post('performance/store',             'PerformanceController::store');
    $routes->get('performance/(:num)',             'PerformanceController::show/$1');
    $routes->get('performance/edit/(:num)',        'PerformanceController::edit/$1');
    $routes->post('performance/update/(:num)',     'PerformanceController::update/$1');

    // Reports
    $routes->get('reports',                  'ReportsController::index');
    $routes->get('reports/employees',        'ReportsController::employees');
    $routes->get('reports/attendance',       'ReportsController::attendance');
    $routes->get('reports/payroll',          'ReportsController::payroll');
    $routes->get('reports/leave',            'ReportsController::leave');
    $routes->get('reports/headcount',        'ReportsController::headcount');

    // Settings
    $routes->get('settings',                 'SettingsController::index');
    $routes->post('settings/update',         'SettingsController::update');
    $routes->get('settings/users',           'SettingsController::users');
    $routes->post('settings/users/store',    'SettingsController::storeUser');
    $routes->post('settings/users/update/(:num)', 'SettingsController::updateUser/$1');
    $routes->post('settings/users/delete/(:num)', 'SettingsController::deleteUser/$1');

    // Profile
    $routes->get('profile',          'ProfileController::index');
    $routes->post('profile/update',  'ProfileController::update');
    $routes->post('profile/password', 'ProfileController::changePassword');

    // Notifications (AJAX)
    $routes->get('notifications',            'DashboardController::notifications');
    $routes->post('notifications/mark-read', 'DashboardController::markNotificationsRead');
});
