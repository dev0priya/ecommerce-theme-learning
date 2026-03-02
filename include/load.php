<?php
// include/load.php

// ===============================
// APPLICATION ENVIRONMENT
// ===============================
define('APP_ENV', 'development'); 
// change to 'development' while coding
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

if (APP_ENV === 'production') {
    // Hide errors from users
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    // Log errors to private file
    ini_set('error_log', __DIR__ . '/../error_log.txt');
} else {
    // Show errors while developing
    ini_set('display_errors', 1);
}

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/auth.php';
