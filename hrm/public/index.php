<?php

/*
 * ---------------------------------------------------------------
 * HRM System - BhuiyanSoft
 * Built with CodeIgniter 4, MySQL, Bootstrap 5
 * ---------------------------------------------------------------
 */

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Load the framework bootstrap file.
require realpath(FCPATH . '../') . '/vendor/autoload.php';

/*
 * ---------------------------------------------------------------
 * BOOTSTRAPPING
 * ---------------------------------------------------------------
 */
$pathsConfig = FCPATH . '../app/Config/Paths.php';
// ^^^ Change this if you move your application folder
require realpath($pathsConfig) ?: $pathsConfig;

// New up an instance of our paths config to access the path information.
$paths = new Config\Paths();

// Location of the framework bootstrap file.
require realpath($paths->systemDirectory . '/bootstrap.php') ?: $paths->systemDirectory . '/bootstrap.php';

// Run CodeIgniter
$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);
$app->run();
