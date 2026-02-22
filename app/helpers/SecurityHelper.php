<?php
/**
 * Security Helper
 * Provides security functions for views without breaking existing structure
 * 
 * @package FCTCNS
 */

// Include Session class for compatibility
require_once dirname(__DIR__) . '/config/session.php';

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
     * Get CSRF token - COMPATIBLE with existing Session class
     * @return string
     */
    public static function getCsrfToken() {
        // Use Session class to generate token (multi-token version)
        return Session::generateCSRFTokenMulti();
    }
    
    /**
     * Validate CSRF token - COMPATIBLE with existing Session class
     * @param string|null $token
     * @return bool
     */
    public static function validateCsrfToken($token = null) {
        if ($token === null) {
            $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
        }

        if (empty($token)) {
            error_log("CSRF validation failed: Token is empty");
            return false;
        }

        // Use Session class to validate (handles both multi and legacy)
        $isValid = Session::validateCSRFTokenMulti($token);
        
        if (!$isValid) {
            error_log("CSRF validation failed: Invalid token - " . substr($token, 0, 8) . "...");
        }
        
        return $isValid;
    }
    
    /**
     * Remove used CSRF token
     * @param string $token
     * @return void
     */
    public static function removeCsrfToken($token) {
        Session::removeCSRFToken($token);
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
            // Font Awesome 6.4.0
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' => 'sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==',

            // Font Awesome 6.7.2 - CORRECT HASH
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' => 'sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==',

            // jQuery
            'https://code.jquery.com/jquery-3.6.0.min.js' => 'sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=',

            // Bootstrap 5.3.0
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js' => 'sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' => 'sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM',
        ];

        return $sriHashes[$url] ?? null;
    }

    /**
     * Check if a resource should use SRI
     * @param string $url
     * @return bool
     */
    public static function shouldUseSri($url) {
        // Don't use SRI for Google Fonts (they change based on user agent)
        if (strpos($url, 'fonts.googleapis.com') !== false) {
            return false;
        }

        // Don't use SRI for resources we don't have hashes for
        return self::getSriHash($url) !== null;
    }
}

// Global function for easy escaping in views
if (!function_exists('e')) {
    function e($text) {
        return SecurityHelper::e($text);
    }
}