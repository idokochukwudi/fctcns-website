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
        // Fallback
        define('BASE_URL', 'http://localhost/fctcns-website');
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
    return BASE_URL . '/assets/' . ltrim($asset, '/');
}

/**
 * Generate an upload URL
 * 
 * @param string $file Upload file path relative to uploads folder
 * @return string Full upload URL
 */
function upload($file) {
    return BASE_URL . '/uploads/' . ltrim($file, '/');
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
    $basePath = '/fctcns-website';
    
    // Remove base path from current URL for comparison
    if (strpos($currentUrl, $basePath) === 0) {
        $currentUrl = substr($currentUrl, strlen($basePath));
    }
    
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
    $basePath = '/fctcns-website';
    
    // Remove base path from current URL for comparison
    if (strpos($currentUrl, $basePath) === 0) {
        $currentUrl = substr($currentUrl, strlen($basePath));
    }
    
    return ($currentUrl === $url || $currentUrl === $url . '/');
}
?>