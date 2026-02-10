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
// PREVENT MULTIPLE INCLUDES - FIXED
// ============================================================================
if (defined('CONSTANTS_LOADED')) {
    return; // Stop processing if already loaded
}

// ============================================================================
// SECURITY: Prevent direct access - FIXED LOGIC
// ============================================================================
// Only prevent direct access if ROOT_PATH is not defined AND we're accessing directly
if (!defined('ROOT_PATH') && basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    die('Direct access not permitted');
}

// ============================================================================
// ENVIRONMENT CONFIGURATION - MUST BE LOADED BEFORE PATHS
// ============================================================================

// Determine root path for .env file - different approach for local vs production
$possibleRootPaths = [
    '/home2/fctcnsed/fctcns-app',  // Production path
    dirname(__DIR__, 2),           // Local development (C:/xampp/htdocs/fctcns-website)
];

$envLoaded = false;
foreach ($possibleRootPaths as $envRootPath) {
    $envFile = $envRootPath . '/.env';
    if (file_exists($envFile)) {
        // Load .env file
        $env_lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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
        $envLoaded = true;
        break;
    }
}

// ============================================================================
// PATH CONSTANTS - CRITICAL: Correct paths for your structure
// ============================================================================

// Root directory: Can be local or production
if (!defined('ROOT_PATH')) {
    // Check for environment variable first (production)
    if (isset($_ENV['ROOT_PATH'])) {
        define('ROOT_PATH', $_ENV['ROOT_PATH']);
    } else {
        // Default local path
        define('ROOT_PATH', dirname(__DIR__, 2));
    }
}

// App directory: C:/xampp/htdocs/fctcns-website/app
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}

// Public directory: Can be local or production
if (!defined('PUBLIC_PATH')) {
    // Check for environment variable first (production)
    if (isset($_ENV['PUBLIC_PATH'])) {
        define('PUBLIC_PATH', $_ENV['PUBLIC_PATH']);
    } else {
        // Default local path
        define('PUBLIC_PATH', ROOT_PATH . '/public');
    }
}

// Uploads directory: Can be local or production
if (!defined('UPLOADS_PATH')) {
    define('UPLOADS_PATH', PUBLIC_PATH . '/assets/uploads');
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
// URL CONSTANTS - CRITICAL FOR SUBDIRECTORY - FIXED
// ============================================================================

// Base URL for the application
if (!defined('BASE_URL')) {
    // Check for environment variable first (production)
    if (isset($_ENV['BASE_URL'])) {
        define('BASE_URL', rtrim($_ENV['BASE_URL'], '/'));
    } else {
        // Local development - SIMPLIFIED: Use localhost only (NO PROJECT FOLDER)
        if (isset($_SERVER['HTTP_HOST'])) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'];
            // REMOVE THE PROJECT FOLDER - Apache serves from root
            define('BASE_URL', rtrim($protocol . $host, '/'));
        } else {
            // Fallback for CLI
            define('BASE_URL', 'http://localhost'); // REMOVED /fctcns-website
        }
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
if (!defined('APP_NAME')) {
    define('APP_NAME', 'FCT College of Nursing Sciences');
}

// Application environment (development, staging, production)
if (!defined('APP_ENV')) {
    $app_env = isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : 'development';
    define('APP_ENV', $app_env);
}

// Debug mode - enabled in development
if (!defined('APP_DEBUG')) {
    $debug_env = isset($_ENV['APP_DEBUG']) ? strtolower($_ENV['APP_DEBUG']) : '';
    $is_debug = (APP_ENV === 'development') || ($debug_env === 'true' || $debug_env === '1');
    define('APP_DEBUG', $is_debug);
}

// Current year for copyright
if (!defined('CURRENT_YEAR')) {
    define('CURRENT_YEAR', date('Y'));
}

// Application version
if (!defined('APP_VERSION')) {
    define('APP_VERSION', '2.0.0');
}

// Default timezone
if (!defined('DEFAULT_TIMEZONE')) {
    define('DEFAULT_TIMEZONE', 'Africa/Lagos');
    date_default_timezone_set(DEFAULT_TIMEZONE);
}

// ============================================================================
// DATABASE CONFIGURATION
// ============================================================================

// Database configuration from environment
if (!defined('DB_HOST')) {
    $db_host = isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'localhost';
    define('DB_HOST', $db_host);
}

if (!defined('DB_PORT')) {
    $db_port = isset($_ENV['DB_PORT']) ? $_ENV['DB_PORT'] : 3306;
    define('DB_PORT', $db_port);
}

if (!defined('DB_NAME')) {
    $db_name = isset($_ENV['DB_DATABASE']) ? $_ENV['DB_DATABASE'] : (isset($_ENV['DB_NAME']) ? $_ENV['DB_NAME'] : 'fctcns_main');
    define('DB_NAME', $db_name);
}

if (!defined('DB_USER')) {
    $db_user = isset($_ENV['DB_USERNAME']) ? $_ENV['DB_USERNAME'] : (isset($_ENV['DB_USER']) ? $_ENV['DB_USER'] : 'root');
    define('DB_USER', $db_user);
}

if (!defined('DB_PASS')) {
    $db_pass = isset($_ENV['DB_PASSWORD']) ? $_ENV['DB_PASSWORD'] : (isset($_ENV['DB_PASS']) ? $_ENV['DB_PASS'] : '');
    define('DB_PASS', $db_pass);
}

// Database charset
if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', 'utf8mb4');
}

// Database collation
if (!defined('DB_COLLATION')) {
    define('DB_COLLATION', 'utf8mb4_unicode_ci');
}

// Admission update configuration
if (!defined('ALLOW_REVERSE_ADMISSION_UPDATES')) {
    // Set to true to allow Accepted → Approved updates
    // Set to false for normal operation (Approved → Accepted only)
    define('ALLOW_REVERSE_ADMISSION_UPDATES', false);
}

// ============================================================================
// MVC DEFAULT SETTINGS
// ============================================================================

// Default controller
if (!defined('DEFAULT_CONTROLLER')) {
    define('DEFAULT_CONTROLLER', 'PageController');
}

// Default controller method
if (!defined('DEFAULT_METHOD')) {
    define('DEFAULT_METHOD', 'home');
}

// Default layout template
if (!defined('DEFAULT_LAYOUT')) {
    define('DEFAULT_LAYOUT', 'main');
}

// View file extension
if (!defined('VIEW_EXTENSION')) {
    define('VIEW_EXTENSION', '.php');
}

// Model file extension
if (!defined('MODEL_EXTENSION')) {
    define('MODEL_EXTENSION', '.php');
}

// Controller file extension
if (!defined('CONTROLLER_EXTENSION')) {
    define('CONTROLLER_EXTENSION', '.php');
}

// ============================================================================
// SECURITY SETTINGS
// ============================================================================

// Session lifetime (2 hours)
if (!defined('SESSION_LIFETIME')) {
    define('SESSION_LIFETIME', 7200);
}

// CSRF token lifetime (1 hour)
if (!defined('CSRF_TOKEN_LIFETIME')) {
    define('CSRF_TOKEN_LIFETIME', 3600);
}

// Password hash algorithm
if (!defined('PASSWORD_ALGO')) {
    define('PASSWORD_ALGO', PASSWORD_BCRYPT);
}

// Password hash options
if (!defined('PASSWORD_OPTIONS')) {
    define('PASSWORD_OPTIONS', ['cost' => 12]);
}

// Minimum password length
if (!defined('MIN_PASSWORD_LENGTH')) {
    define('MIN_PASSWORD_LENGTH', 8);
}

// ============================================================================
// FILE UPLOAD SETTINGS
// ============================================================================

// Maximum upload size (5MB)
if (!defined('MAX_UPLOAD_SIZE')) {
    define('MAX_UPLOAD_SIZE', 5242880);
}

// Allowed image types
if (!defined('ALLOWED_IMAGE_TYPES')) {
    define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
}

// Allowed document types
if (!defined('ALLOWED_DOC_TYPES')) {
    define('ALLOWED_DOC_TYPES', ['pdf', 'doc', 'docx']);
}

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
if (!defined('MAINTENANCE_MODE')) {
    define('MAINTENANCE_MODE', false);
}

// Cache lifetime (1 hour)
if (!defined('CACHE_LIFETIME')) {
    define('CACHE_LIFETIME', 3600);
}

// API rate limit
if (!defined('API_RATE_LIMIT')) {
    define('API_RATE_LIMIT', 60);
}

// Email configuration
if (!defined('EMAIL_FROM')) {
    $email_from = isset($_ENV['EMAIL_FROM']) ? $_ENV['EMAIL_FROM'] : 'noreply@fctcns.edu.ng';
    define('EMAIL_FROM', $email_from);
}

if (!defined('EMAIL_FROM_NAME')) {
    $email_from_name = isset($_ENV['EMAIL_FROM_NAME']) ? $_ENV['EMAIL_FROM_NAME'] : 'FCT College of Nursing Sciences';
    define('EMAIL_FROM_NAME', $email_from_name);
}

// ============================================================================
// LOAD HELPER FUNCTIONS - NO DUPLICATE FUNCTIONS HERE
// ============================================================================

// Load URL helper functions (url_helper.php contains all the helper functions)
$urlHelperFile = APP_PATH . '/helpers/url_helper.php';
if (file_exists($urlHelperFile)) {
    require_once $urlHelperFile;
}

// Load additional helper functions if they exist
$functionsFile = APP_PATH . '/helpers/functions.php';
if (file_exists($functionsFile)) {
    require_once $functionsFile;
}

// Load image helper
$imageHelperFile = APP_PATH . '/helpers/image_helper.php';
if (file_exists($imageHelperFile)) {
    require_once $imageHelperFile;
}

// ============================================================================
// INITIALIZATION COMPLETE
// ============================================================================

// Mark that constants have been loaded - ONCE AT THE END
define('CONSTANTS_LOADED', true);