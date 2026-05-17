<?php

declare(strict_types=1);

/*
 * SaaS Surveys - CodeIgniter 4 Application
 * Entry point for the web application
 */

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== __DIR__ . DIRECTORY_SEPARATOR) {
    chdir(__DIR__);
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

require FCPATH . '../vendor/codeigniter4/framework/system/bootstrap.php';
