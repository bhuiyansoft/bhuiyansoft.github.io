<?php

/*
 * ---------------------------------------------------------------
 * SaaSCommerce - CI4 E-Commerce Platform
 * ---------------------------------------------------------------
 * Set the base directory for the framework.
 */

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

$pathsPath = FCPATH . '../app/Config/Paths.php';
require $pathsPath;

$paths = new Config\Paths();

// SYSTEMPATH
if (! is_dir($paths->systemDirectory)) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME);
    exit(3);
}

define('SYSTEMPATH', rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR);

// APPPATH
if (! is_dir($paths->appDirectory)) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME);
    exit(3);
}

define('APPPATH', rtrim($paths->appDirectory, '\\/ ') . DIRECTORY_SEPARATOR);

// WRITEPATH
define('WRITEPATH', rtrim($paths->writableDirectory, '\\/ ') . DIRECTORY_SEPARATOR);

// ROOTPATH
define('ROOTPATH', realpath(APPPATH . '../') . DIRECTORY_SEPARATOR);

require SYSTEMPATH . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);
$app->run();
