<?php
/**
 * Application Constants
 * 
 * Defines all the constant values used throughout the application
 * 
 * @package FCT_CNS
 */

// ============================================================================
// PATH CONSTANTS
// ============================================================================

// Define root directory path (only if not already defined)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

// Define app directory path  
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}

// Define public directory path
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', ROOT_PATH . '/public');
}

// Define uploads directory path
if (!defined('UPLOADS_PATH')) {
    define('UPLOADS_PATH', PUBLIC_PATH . '/assets/uploads');
}

// ============================================================================
// ENVIRONMENT LOADING - CRITICAL MISSING SECTION
// ============================================================================

// Load environment configuration if .env exists and not already loaded
if (!isset($_ENV['DB_HOST']) && file_exists(ROOT_PATH . '/.env')) {
    $env_lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($env_lines as $line) {
        if (strpos(trim($line), '#') === 0 || empty(trim($line))) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// ============================================================================
// URL CONSTANTS
// ============================================================================

// Base URL for the application
if (!defined('BASE_URL')) {
    if (isset($_SERVER['HTTP_HOST'])) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $script = dirname($_SERVER['SCRIPT_NAME']);

        // Remove 'public' from path if present
        $script = str_replace('/public', '', $script);

        define('BASE_URL', $protocol . '://' . $host . $script);
    } else {
        define('BASE_URL', 'http://localhost/fctcns-website');
    }
}

// ============================================================================
// APPLICATION CONSTANTS
// ============================================================================

// Application name
define('APP_NAME', 'FCT College of Nursing Sciences');

// Application environment (development, staging, production)
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');

// Debug mode (only in development)
define('APP_DEBUG', APP_ENV === 'development');

// Current year for copyright
define('CURRENT_YEAR', date('Y'));

// ============================================================================
// DATABASE CONSTANTS
// ============================================================================

// Database configuration from environment
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_PORT', $_ENV['DB_PORT'] ?? 3306);
define('DB_NAME', $_ENV['DB_DATABASE'] ?? 'fctcns_main');
define('DB_USER', $_ENV['DB_USERNAME'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASSWORD'] ?? '');

// ============================================================================
// SECURITY CONSTANTS
// ============================================================================

// Session lifetime (2 hours)
define('SESSION_LIFETIME', 7200);

// CSRF token lifetime (1 hour)
define('CSRF_TOKEN_LIFETIME', 3600);

// Password hash algorithm
define('PASSWORD_ALGO', PASSWORD_BCRYPT);

// Password hash options
define('PASSWORD_OPTIONS', ['cost' => 12]);

// ============================================================================
// UPLOAD CONSTANTS
// ============================================================================

// Maximum upload size (5MB)
define('MAX_UPLOAD_SIZE', 5242880);

// Allowed image types
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Allowed document types
define('ALLOWED_DOC_TYPES', ['pdf', 'doc', 'docx']);

// ============================================================================
// CAROUSEL CONSTANTS (STAGE 4 - CRITICAL)
// ============================================================================

// Carousel auto-rotate interval (5 seconds)
define('CAROUSEL_INTERVAL', 5000);

// Carousel transition duration (500ms)
define('CAROUSEL_TRANSITION', 500);

// Maximum carousel slides
define('MAX_CAROUSEL_SLIDES', 10);

// ============================================================================
// ADMIN CONSTANTS
// ============================================================================

// Admin login credentials (from environment)
define('ADMIN_USERNAME', $_ENV['ADMIN_USERNAME'] ?? 'admin');
define('ADMIN_EMAIL', $_ENV['ADMIN_EMAIL'] ?? 'admin@fctcns.edu.ng');

// ============================================================================
// ERROR CONSTANTS
// ============================================================================

// Error reporting based on environment
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }

    $errorTypes = [
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parse Error',
        E_NOTICE => 'Notice',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
    ];

    $errorType = $errorTypes[$errno] ?? 'Unknown Error';

    error_log("[$errorType] $errstr in $errfile on line $errline");

    if (APP_DEBUG) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; margin: 10px; border: 1px solid #f5c6cb; border-radius: 5px;'>
                <strong>$errorType:</strong> $errstr<br>
                <small>File: $errfile (Line: $errline)</small>
              </div>";
    }

    return true;
});
?>