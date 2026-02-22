<?php
/**
 * Security Helper
 * Provides security functions for views without breaking existing structure
 * 
 * @package FCTCNS
 */

class SecurityHelper {
    
    /**
     * Generate CSP nonce
     * @return string
     */
    public static function getCspNonce() {
        if (!isset($_SESSION['csp_nonce'])) {
            $_SESSION['csp_nonce'] = base64_encode(random_bytes(16));
        }
        return $_SESSION['csp_nonce'];
    }
    
    /**
     * Get CSRF token
     * @return string
     */
    public static function getCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Generate security meta tags
     * @return string
     */
    public static function getSecurityMetaTags() {
        $nonce = self::getCspNonce();
        
        // Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'nonce-" . $nonce . "' " .
               "https://cdnjs.cloudflare.com " .
               "https://code.jquery.com " .
               "https://cdn.jsdelivr.net " .
               "https://fonts.googleapis.com " .
               "https://www.google.com " .
               "https://www.gstatic.com; " .
               "style-src 'self' 'nonce-" . $nonce . "' " .
               "https://fonts.googleapis.com " .
               "https://cdnjs.cloudflare.com; " .
               "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
               "img-src 'self' data: https:; " .
               "connect-src 'self'; " .
               "frame-ancestors 'self';";
        
        return implode("\n    ", [
            '<meta http-equiv="Content-Security-Policy" content="' . htmlspecialchars($csp) . '">',
            '<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">',
            '<meta http-equiv="X-Content-Type-Options" content="nosniff">',
            '<meta name="referrer" content="strict-origin-when-cross-origin">',
            '<meta name="csrf-token" content="' . self::getCsrfToken() . '">'
        ]);
    }
    
    /**
     * Escape HTML output
     * @param string $text
     * @return string
     */
    public static function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Generate SRI hash for CDN resources
     * @param string $url
     * @return string|null
     */
    public static function getSriHash($url) {
        // Common CDN resources with their SRI hashes
        $sriHashes = [
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' => 'sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' => 'sha512-ENjdO4Dr2+biM4pKkX98RNF/QRMg9gKaVjY5jRkRxs1+ckgCk9J30rWjw2H5qWv3WbLp1WvD5I8nsLZBnlllg==',
            'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap' => 'sha384-0pCryB3hBqYHZO9dKsIIzN8wH+Z4k5P+GZ8TlqM9m8A3TlPI9c7JZ6nG+K/t9fb',
            'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap' => 'sha384-0pCryB3hBqYHZO9dKsIIzN8wH+Z4k5P+GZ8TlqM9m8A3TlPI9c7JZ6nG+K/t9fb',
            'https://code.jquery.com/jquery-3.6.0.min.js' => 'sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js' => 'sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' => 'sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM',
        ];
        
        return $sriHashes[$url] ?? null;
    }
}

// Global function for easy escaping in views
if (!function_exists('e')) {
    function e($text) {
        return SecurityHelper::e($text);
    }
}