<?php

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Autoload dependencies
require BASE_PATH . '/vendor/autoload.php';

// Run application
App\Core\App::run();
