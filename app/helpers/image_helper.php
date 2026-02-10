<?php
/**
 * Image URL Helper Functions - REFACTORED FOR CORRECT UPLOAD DIR
 * 
 * Based on actual file structure: /public/uploads/news/ (PROJECT ROOT)
 */
if (!function_exists('getImageUrl')) {
    /**
     * Get complete URL for an image
     * 
     * @param string $path Image path from database
     * @param string $type Type of image (news, event, etc.)
     * @return string Complete URL
     */
    function getImageUrl($path, $type = 'news') {
        if (empty($path)) {
            return getDefaultImageUrl($type);
        }
        
        $path = trim($path);
        $baseUrl = defined('BASE_URL') ? BASE_URL : 'http://localhost';
        
        // Already a full URL - return as-is
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }
        
        // CORRECT DIRECTORY: /public/uploads/news/ in PROJECT ROOT
        
        // If path already starts with /uploads/news/ - it's correct
        if (strpos($path, '/uploads/news/') === 0) {
            return $baseUrl . $path;
        }
        
        // If it's just a filename like "news_698afd5901750_1770716505.jpg"
        // Check if it matches your naming pattern
        if (preg_match('/^news_[a-f0-9]+_\d+\.(jpg|jpeg|png|gif|webp)$/i', $path)) {
            return $baseUrl . '/uploads/news/' . $path;
        }
        
        // If it's a generic filename that might be in the news folder
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $path)) {
            // Check if file exists in the CORRECT news folder
            $newsPath = dirname(__DIR__, 2) . '/public/uploads/news/' . $path; // PROJECT ROOT
            if (file_exists($newsPath)) {
                return $baseUrl . '/uploads/news/' . $path;
            }
        }
        
        // FALLBACK: Try as-is with /uploads/news/
        $fullPath = dirname(__DIR__, 2) . '/public' . $path; // PROJECT ROOT
        
        // If path doesn't start with /, add it
        if (strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }
        
        // Check if file exists at the given path
        if (file_exists($fullPath)) {
            return $baseUrl . $path;
        }
        
        // Last resort: Try in uploads/news directory (PROJECT ROOT)
        $newsPath = dirname(__DIR__, 2) . '/public/uploads/news/' . basename($path);
        if (file_exists($newsPath)) {
            return $baseUrl . '/uploads/news/' . basename($path);
        }
        
        // If nothing works, return default
        return getDefaultImageUrl($type);
    }
}

if (!function_exists('getDefaultImageUrl')) {
    /**
     * Get default image URL based on type
     */
    function getDefaultImageUrl($type = 'news') {
        $baseUrl = defined('BASE_URL') ? BASE_URL : 'http://localhost';
        
        $defaultImages = [
            'news' => '/assets/images/default-news.jpg',
            'event' => '/assets/images/default-event.jpg',
            'profile' => '/assets/images/default-profile.jpg'
        ];
        
        $default = $defaultImages[$type] ?? $defaultImages['news'];
        
        return $baseUrl . $default;
    }
}

if (!function_exists('checkImageExists')) {
    /**
     * Check if an image file exists
     * FIXED FOR CORRECT DIRECTORY: /public/uploads/news/ in PROJECT ROOT
     */
    function checkImageExists($path) {
        if (empty($path)) {
            return false;
        }
        
        // Check if it's a full URL
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return true;
        }
        
        // Clean up the path
        $path = trim($path);
        
        // Remove leading slash if present for consistency
        $path = ltrim($path, '/');
        
        // CORRECT BASE DIRECTORY: PROJECT ROOT
        $baseDir = dirname(__DIR__, 2) . '/public/';
        
        // Try multiple possible locations
        $possiblePaths = [
            $path, // As stored
            'uploads/news/' . basename($path), // In news folder
            'uploads/' . $path, // In general uploads
        ];
        
        foreach ($possiblePaths as $testPath) {
            $fullPath = $baseDir . $testPath;
            if (file_exists($fullPath) && is_readable($fullPath)) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('getImagePathFromUrl')) {
    /**
     * Convert image URL back to local file path
     * FIXED FOR CORRECT DIRECTORY
     */
    function getImagePathFromUrl($url) {
        if (empty($url)) {
            return '';
        }
        
        $baseUrl = defined('BASE_URL') ? BASE_URL : 'http://localhost';
        
        // Remove base URL if present
        if (strpos($url, $baseUrl) === 0) {
            $url = substr($url, strlen($baseUrl));
        }
        
        // Convert to absolute path in PROJECT ROOT
        return dirname(__DIR__, 2) . '/public' . $url;
    }
}

if (!function_exists('generateImageUrl')) {
    /**
     * Generate proper image URL from filename or path
     * Use this when creating/updating images
     */
    function generateImageUrl($filename) {
        if (empty($filename)) {
            return '';
        }
        
        $baseUrl = defined('BASE_URL') ? BASE_URL : 'http://localhost';
        
        // If it's already a full path starting with /uploads/news/, return it
        if (strpos($filename, '/uploads/news/') === 0) {
            return $baseUrl . $filename;
        }
        
        // Otherwise, assume it's a filename and put it in /uploads/news/
        return $baseUrl . '/uploads/news/' . basename($filename);
    }
}

if (!function_exists('getProjectRootPath')) {
    /**
     * Get the correct project root path for uploads
     */
    function getProjectRootPath() {
        return dirname(__DIR__, 2); // Goes up 2 levels from helpers directory
    }
}

if (!function_exists('getUploadsPath')) {
    /**
     * Get the correct uploads directory path
     */
    function getUploadsPath($type = 'news') {
        $uploadsDir = getProjectRootPath() . '/public/uploads/' . $type . '/';
        return $uploadsDir;
    }
}