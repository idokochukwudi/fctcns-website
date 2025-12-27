<?php
/**
 * FCT College of Nursing Sciences - Main Entry Point
 * 
 * @package FCT_CNS
 */

// Define absolute path to root directory
define('ROOT_PATH', dirname(__DIR__));

// Load environment configuration
if (file_exists(ROOT_PATH . '/.env')) {
    $env_lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($env_lines as $line) {
        if (strpos(trim($line), '#') === 0 || empty(trim($line))) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
} else {
    die('<h1>Configuration Error</h1><p>.env file not found. Please copy .env.example to .env and configure your settings.</p>');
}

// Start session
session_start();

// Set timezone to West Africa Time
date_default_timezone_set('Africa/Lagos');

// Display errors in development
if ($_ENV['APP_ENV'] === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Simple router for Stage 1 testing
$request_uri = $_SERVER['REQUEST_URI'];
$request_path = parse_url($request_uri, PHP_URL_PATH);

// Remove project folder from path if present
$script_name = dirname($_SERVER['SCRIPT_NAME']);
if ($script_name !== '/' && strpos($request_path, $script_name) === 0) {
    $request_path = substr($request_path, strlen($script_name));
}

// Route to appropriate page
$routes = [
    '/' => 'pages/home.php',
    '/about' => 'pages/about.php',
    '/programs' => 'pages/programs.php',
    '/admissions' => 'pages/admissions.php',
    '/contact' => 'pages/contact.php',
    '/admin' => 'admin/login.php',
];

if (isset($routes[$request_path]) && file_exists(__DIR__ . '/' . $routes[$request_path])) {
    require __DIR__ . '/' . $routes[$request_path];
} else {
    // Default to homepage
    require __DIR__ . '/pages/home.php';
}
?>