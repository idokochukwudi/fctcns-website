<?php
/**
 * Image URL Helper Functions - UNIVERSAL (Local & Shared Hosting)
 */
if (!function_exists('getImageUrl')) {
    /**
     * Get complete URL for an image - Works on both environments
     */
    function getImageUrl($path, $type = 'news') {
        if (empty($path)) {
            return getDefaultImageUrl($type);
        }
        
        $path = trim($path);
        $baseUrl = defined('BASE_URL') ? BASE_URL : getCurrentBaseUrl();
        
        // Already a full URL - return as-is
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }
        
        // If path starts with /uploads/, it's already correct
        if (strpos($path, '/uploads/') === 0) {
            // Check if file exists
            if (checkImageExists($path)) {
                return $baseUrl . $path;
            }
            
            // Try alternative paths
            $altPaths = [
                '/public' . $path, // For local dev with /public structure
                $path
            ];
            
            foreach ($altPaths as $altPath) {
                if (checkImageExists($altPath)) {
                    return $baseUrl . $altPath;
                }
            }
        }
        
        // If it's just a filename
        if (preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $path)) {
            // Try different possible locations
            $possiblePaths = [
                '/uploads/' . $type . '/' . $path,
                '/public/uploads/' . $type . '/' . $path, // Local dev
                '/uploads/' . $path,
                $path
            ];
            
            foreach ($possiblePaths as $possiblePath) {
                if (checkImageExists($possiblePath)) {
                    return $baseUrl . $possiblePath;
                }
            }
        }
        
        // Last resort: return default
        return getDefaultImageUrl($type);
    }
}

if (!function_exists('getCurrentBaseUrl')) {
    /**
     * Get base URL dynamically for both environments
     */
    function getCurrentBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        // For shared hosting (cPanel), document root is usually public_html
        // For local dev, it might be different
        return $protocol . $host;
    }
}

if (!function_exists('checkImageExists')) {
    /**
     * Check if an image file exists - UNIVERSAL VERSION
     */
    function checkImageExists($path) {
        if (empty($path)) {
            return false;
        }
        
        $path = trim($path);
        
        // Try multiple possible base directories
        $possibleBaseDirs = [
            $_SERVER['DOCUMENT_ROOT'], // Shared hosting (public_html)
            dirname($_SERVER['DOCUMENT_ROOT'], 1) . '/public', // Local dev with /public
            dirname(__DIR__, 2) . '/public', // Project root for local dev
        ];
        
        // Clean path - remove leading slash for consistency
        $cleanPath = ltrim($path, '/');
        
        foreach ($possibleBaseDirs as $baseDir) {
            $fullPath = rtrim($baseDir, '/') . '/' . $cleanPath;
            
            if (file_exists($fullPath) && is_readable($fullPath)) {
                return true;
            }
            
            // Also try with the path as-is (with leading slash)
            $fullPath2 = rtrim($baseDir, '/') . $path;
            if (file_exists($fullPath2) && is_readable($fullPath2)) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('getUploadPath')) {
    /**
     * Get the correct upload path for current environment
     */
    function getUploadPath($type = 'news') {
        // Try different possible upload locations
        $possiblePaths = [
            $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $type . '/', // Shared hosting
            dirname($_SERVER['DOCUMENT_ROOT'], 1) . '/public/uploads/' . $type . '/', // Local dev
            dirname(__DIR__, 2) . '/public/uploads/' . $type . '/', // Project root
        ];
        
        foreach ($possiblePaths as $path) {
            if (is_dir($path) || is_writable(dirname($path))) {
                return $path;
            }
        }
        
        // Default to document root
        return $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $type . '/';
    }
}

if (!function_exists('getRelativeUploadPath')) {
    /**
     * Get relative path for database storage
     */
    function getRelativeUploadPath($filename, $type = 'news') {
        // Store in format that works for both environments
        return '/uploads/' . $type . '/' . basename($filename);
    }
}

if (!function_exists('getDefaultImageUrl')) {
    /**
     * Get default image URL
     */
    function getDefaultImageUrl($type = 'news') {
        $baseUrl = defined('BASE_URL') ? BASE_URL : getCurrentBaseUrl();
        
        $defaultImages = [
            'news' => $baseUrl . '/assets/images/default-news.jpg',
            'event' => $baseUrl . '/assets/images/default-event.jpg',
            'profile' => $baseUrl . '/assets/images/default-profile.jpg'
        ];
        
        return $defaultImages[$type] ?? $defaultImages['news'];
    }
}