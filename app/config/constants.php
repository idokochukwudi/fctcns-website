<?php
/**
 * Application Constants
 * 
 * Defines all constant values used throughout the application.
 * This is the first file loaded in the application bootstrap.
 * 
 * @package FCT_CNS
 * @version 2.0
 */

// ============================================================================
// SECURITY: Prevent direct access
// ============================================================================
if (!defined('ROOT_PATH')) {
    define('CONSTANTS_LOADED', true);
}

// ============================================================================
// PATH CONSTANTS - CRITICAL: Correct paths for your structure
// ============================================================================

// Root directory: C:/xampp/htdocs/fctcns-website
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

// App directory: C:/xampp/htdocs/fctcns-website/app
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}

// Public directory: C:/xampp/htdocs/fctcns-website/public
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', ROOT_PATH . '/public');
}

// Uploads directory: C:/xampp/htdocs/fctcns-website/public/uploads
if (!defined('UPLOADS_PATH')) {
    define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');
}

// ============================================================================
// MVC STRUCTURE PATHS
// ============================================================================

// Views directory: C:/xampp/htdocs/fctcns-website/app/views
if (!defined('VIEWS_PATH')) {
    define('VIEWS_PATH', APP_PATH . '/views');
}

// Controllers directory: C:/xampp/htdocs/fctcns-website/app/controllers
if (!defined('CONTROLLERS_PATH')) {
    define('CONTROLLERS_PATH', APP_PATH . '/controllers');
}

// Models directory: C:/xampp/htdocs/fctcns-website/app/models
if (!defined('MODELS_PATH')) {
    define('MODELS_PATH', APP_PATH . '/models');
}

// Core directory: C:/xampp/htdocs/fctcns-website/app/core
if (!defined('CORE_PATH')) {
    define('CORE_PATH', APP_PATH . '/core');
}

// Pages views directory
if (!defined('PAGES_PATH')) {
    define('PAGES_PATH', VIEWS_PATH . '/pages');
}

// Layouts directory
if (!defined('LAYOUTS_PATH')) {
    define('LAYOUTS_PATH', VIEWS_PATH . '/layouts');
}

// Admin views directory
if (!defined('ADMIN_VIEWS_PATH')) {
    define('ADMIN_VIEWS_PATH', VIEWS_PATH . '/admin');
}

// Admin layouts directory
if (!defined('ADMIN_LAYOUTS_PATH')) {
    define('ADMIN_LAYOUTS_PATH', ADMIN_VIEWS_PATH . '/layouts');
}

// Includes directory (shared header/footer)
if (!defined('INCLUDES_PATH')) {
    define('INCLUDES_PATH', PUBLIC_PATH . '/includes');
}

// ============================================================================
// ENVIRONMENT CONFIGURATION
// ============================================================================

// Load .env file if exists
if (file_exists(ROOT_PATH . '/.env')) {
    $env_lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($env_lines as $line) {
        $line = trim($line);
        if (strpos($line, '#') === 0 || empty($line)) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// ============================================================================
// URL CONSTANTS - CRITICAL FOR SUBDIRECTORY
// ============================================================================

// Base URL for the application (MUST MATCH YOUR SUBDIRECTORY)
if (!defined('BASE_URL')) {
    if (isset($_SERVER['HTTP_HOST'])) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        
        // Your project is in subdirectory: /fctcns-website
        $project_folder = 'fctcns-website';
        
        // Build the base URL
        $base_url = $protocol . $host . '/' . $project_folder;
        
        define('BASE_URL', rtrim($base_url, '/'));
    } else {
        // Fallback for CLI
        define('BASE_URL', 'http://localhost/fctcns-website');
    }
}

// Site URL (alias for BASE_URL)
if (!defined('SITE_URL')) {
    define('SITE_URL', BASE_URL);
}

// Assets URL - Points to public/assets
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', SITE_URL . '/assets');
}

// Public URL for direct file access
if (!defined('PUBLIC_URL')) {
    define('PUBLIC_URL', SITE_URL);
}

// ============================================================================
// APPLICATION SETTINGS
// ============================================================================

// Application name
define('APP_NAME', 'FCT College of Nursing Sciences');

// Application environment (development, staging, production)
define('APP_ENV', isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : 'development');

// Debug mode - enabled in development
$debug_env = isset($_ENV['APP_DEBUG']) ? strtolower($_ENV['APP_DEBUG']) : '';
define('APP_DEBUG', (APP_ENV === 'development') || ($debug_env === 'true' || $debug_env === '1'));

// Current year for copyright
define('CURRENT_YEAR', date('Y'));

// Application version
define('APP_VERSION', '2.0.0');

// Default timezone
date_default_timezone_set('Africa/Lagos');

// ============================================================================
// DATABASE CONFIGURATION
// ============================================================================

// Database configuration from environment
define('DB_HOST', isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'localhost');
define('DB_PORT', isset($_ENV['DB_PORT']) ? $_ENV['DB_PORT'] : 3306);
define('DB_NAME', isset($_ENV['DB_DATABASE']) ? $_ENV['DB_DATABASE'] : (isset($_ENV['DB_NAME']) ? $_ENV['DB_NAME'] : 'fctcns_main'));
define('DB_USER', isset($_ENV['DB_USERNAME']) ? $_ENV['DB_USERNAME'] : (isset($_ENV['DB_USER']) ? $_ENV['DB_USER'] : 'root'));
define('DB_PASS', isset($_ENV['DB_PASSWORD']) ? $_ENV['DB_PASSWORD'] : (isset($_ENV['DB_PASS']) ? $_ENV['DB_PASS'] : ''));

// Database charset
define('DB_CHARSET', 'utf8mb4');

// Database collation
define('DB_COLLATION', 'utf8mb4_unicode_ci');

// ============================================================================
// MVC DEFAULT SETTINGS
// ============================================================================

// Default controller
define('DEFAULT_CONTROLLER', 'PageController');

// Default controller method
define('DEFAULT_METHOD', 'home');

// Default layout template
define('DEFAULT_LAYOUT', 'main');

// View file extension
define('VIEW_EXTENSION', '.php');

// Model file extension
define('MODEL_EXTENSION', '.php');

// Controller file extension
define('CONTROLLER_EXTENSION', '.php');

// ============================================================================
// SECURITY SETTINGS
// ============================================================================

// Session lifetime (2 hours)
define('SESSION_LIFETIME', 7200);

// CSRF token lifetime (1 hour)
define('CSRF_TOKEN_LIFETIME', 3600);

// Password hash algorithm
define('PASSWORD_ALGO', PASSWORD_BCRYPT);

// Password hash options
define('PASSWORD_OPTIONS', ['cost' => 12]);

// Minimum password length
define('MIN_PASSWORD_LENGTH', 8);

// ============================================================================
// FILE UPLOAD SETTINGS
// ============================================================================

// Maximum upload size (5MB)
define('MAX_UPLOAD_SIZE', 5242880);

// Allowed image types
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Allowed document types
define('ALLOWED_DOC_TYPES', ['pdf', 'doc', 'docx']);

// ============================================================================
// ERROR HANDLING CONFIGURATION
// ============================================================================

// Configure error reporting based on environment
if (APP_DEBUG) {
    // Development: Show all errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('log_errors', 1);
} else {
    // Production: Hide errors from users
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
}

// Set error log path
$error_log_dir = APP_PATH . '/logs';
if (!file_exists($error_log_dir)) {
    @mkdir($error_log_dir, 0755, true);
}
ini_set('error_log', $error_log_dir . '/error.log');

// Custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // Skip if error reporting is disabled
    if (!(error_reporting() & $errno)) {
        return false;
    }

    // Log the error
    error_log("PHP Error [$errno]: $errstr in $errfile on line $errline");
    
    // Only show detailed errors in debug mode
    if (APP_DEBUG) {
        echo '<div style="background: #ffebee; color: #c62828; padding: 15px; margin: 10px; border: 1px solid #ef9a9a; border-radius: 4px; font-family: monospace;">
                <strong>PHP Error:</strong> ' . htmlspecialchars($errstr) . '<br>
                <small>File: ' . htmlspecialchars($errfile) . ' (Line: ' . $errline . ')</small>
              </div>';
    }
    
    return true;
}, E_ALL);

// ============================================================================
// APPLICATION CONSTANTS
// ============================================================================

// Maintenance mode flag
define('MAINTENANCE_MODE', false);

// Cache lifetime (1 hour)
define('CACHE_LIFETIME', 3600);

// API rate limit
define('API_RATE_LIMIT', 60);

// Email configuration
define('EMAIL_FROM', isset($_ENV['EMAIL_FROM']) ? $_ENV['EMAIL_FROM'] : 'noreply@fctcns.edu.ng');
define('EMAIL_FROM_NAME', isset($_ENV['EMAIL_FROM_NAME']) ? $_ENV['EMAIL_FROM_NAME'] : 'FCT College of Nursing Sciences');

// ============================================================================
// HELPER FUNCTIONS - REMOVED e() FUNCTION FROM HERE
// ============================================================================

/**
 * Get base URL with optional path
 * 
 * @param string $path Optional path to append
 * @return string Complete URL
 */
function base_url($path = '') {
    $url = BASE_URL;
    if ($path) {
        $url .= '/' . ltrim($path, '/');
    }
    return $url;
}

/**
 * Get asset URL for CSS/JS/images
 * 
 * @param string $path Asset path relative to assets folder
 * @return string Complete asset URL
 */
function asset_url($path) {
    return ASSETS_URL . '/' . ltrim($path, '/');
}

/**
 * Get public URL for files
 * 
 * @param string $path Path relative to public folder
 * @return string Complete public URL
 */
function public_url($path = '') {
    $url = PUBLIC_URL;
    if ($path) {
        $url .= '/' . ltrim($path, '/');
    }
    return $url;
}

/**
 * Get upload URL for uploaded files
 * 
 * @param string $path Path relative to uploads folder
 * @return string Complete upload URL
 */
function upload_url($path = '') {
    return public_url('uploads/' . ltrim($path, '/'));
}

/**
 * Check if application is in development mode
 * 
 * @return bool True if in development mode
 */
function is_dev() {
    return APP_ENV === 'development';
}

/**
 * Redirect to a URL
 * 
 * @param string $url URL to redirect to
 * @param int $statusCode HTTP status code
 */
function redirect($url, $statusCode = 302) {
    if (strpos($url, 'http') !== 0) {
        $url = base_url($url);
    }
    header('Location: ' . $url, true, $statusCode);
    exit();
}

/**
 * Get current URL path
 * 
 * @return string Current URL path
 */
function current_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

// ============================================================================
// INITIALIZATION COMPLETE
// ============================================================================
?>