<?php
/**
 * URL Helper Functions
 *
 * @package FCT_CNS
 */

// Check if BASE_URL is defined
if (!defined('BASE_URL')) {
    // Load constants if not loaded
    $constantsFile = dirname(__DIR__, 2) . '/app/config/constants.php';
    if (file_exists($constantsFile)) {
        require_once $constantsFile;
    } else {
        // Fallback - UPDATED to match the new structure
        define('BASE_URL', 'http://localhost');
    }
}

/**
 * Generate a full URL
 *
 * @param string $path The path to append to base URL
 * @return string Full URL
 */
function url($path = '') {
    return BASE_URL . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Generate an asset URL
 *
 * @param string $asset Asset path relative to assets folder
 * @return string Full asset URL
 */
function asset($asset) {
    // FIXED: Now correctly points to /assets/ from root
    return BASE_URL . '/assets/' . ltrim($asset, '/');
}

/**
 * Generate an upload URL
 *
 * @param string $file Upload file path relative to uploads folder
 * @return string Full upload URL
 */
function upload($file) {
    // FIXED: Updated path to match the structure (uploads are in assets/uploads)
    return BASE_URL . '/assets/uploads/' . ltrim($file, '/');
}

/**
 * Generate a link tag with active class
 *
 * @param string $url The URL
 * @param string $text Link text
 * @param string $activeClass Class to add if active
 * @return string HTML link
 */
function nav_link($url, $text, $activeClass = 'active') {
    $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // FIXED: Remove the base path logic since Apache serves from root
    // No need to strip '/fctcns-website' anymore
    $isActive = ($currentUrl === $url || $currentUrl === $url . '/');

    $class = $isActive ? 'class="' . $activeClass . '"' : '';
    return '<a href="' . url($url) . '" ' . $class . '>' . htmlspecialchars($text) . '</a>';
}

/**
 * Redirect to a URL
 *
 * @param string $url URL to redirect to
 * @param int $statusCode HTTP status code
 */
function redirect($url, $statusCode = 302) {
    header('Location: ' . url($url), true, $statusCode);
    exit;
}

/**
 * Get current URL
 *
 * @return string Current URL
 */
function current_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Check if current page is active
 *
 * @param string $url URL to check
 * @return bool True if active
 */
function is_active($url) {
    $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // FIXED: No need to strip '/fctcns-website' anymore
    // Direct comparison works since Apache serves from root
    return ($currentUrl === $url || $currentUrl === $url . '/');
}

/**
 * Generate route URL (MVC-friendly)
 *
 * @param string $controller Controller name
 * @param string $action Action method
 * @param array $params Route parameters
 * @return string Generated URL
 */
function route($controller, $action = null, $params = []) {
    if ($action === null) {
        return url('/' . $controller);
    }

    // For now, return simple URL based on common patterns
    // In a more advanced router, this would map to actual routes
    return url('/' . $controller . '/' . $action);
}

/**
 * Escape HTML output
 *
 * @param string $text Text to escape
 * @return string Escaped text
 */
if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Get query parameter
 *
 * @param string $key Parameter key
 * @param mixed $default Default value
 * @return mixed Parameter value
 */
if (!function_exists('get_param')) {
    function get_param($key, $default = null) {
        return $_GET[$key] ?? $default;
    }
}

/**
 * Get POST parameter
 *
 * @param string $key Parameter key
 * @param mixed $default Default value
 * @return mixed Parameter value
 */
if (!function_exists('post_param')) {
    function post_param($key, $default = null) {
        return $_POST[$key] ?? $default;
    }
}