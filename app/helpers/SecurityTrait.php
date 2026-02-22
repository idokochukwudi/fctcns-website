<?php
/**
 * SecurityTrait.php - Security helpers for views
 * Place in: app/helpers/SecurityTrait.php
 */

trait SecurityTrait {
    
    /**
     * Secure output encoding - use this for ALL dynamic content
     */
    protected function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Secure JSON encoding for JavaScript variables
     */
    protected function secureJsonEncode($data) {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Generate CSP nonce for inline scripts
     */
    protected function getCspNonce() {
        if (!isset($_SESSION['csp_nonce'])) {
            $_SESSION['csp_nonce'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['csp_nonce'];
    }
    
    /**
     * Get CSRF token with expiration
     */
    protected function getCsrfToken() {
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }
        
        // Clean expired tokens (older than 1 hour)
        foreach ($_SESSION['csrf_tokens'] as $token => $time) {
            if (time() - $time > 3600) {
                unset($_SESSION['csrf_tokens'][$token]);
            }
        }
        
        // Generate new token
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_tokens'][$token] = time();
        
        return $token;
    }
    
    /**
     * Generate meta security headers
     */
    protected function getSecurityMetaTags() {
        return '
            <meta http-equiv="X-Content-Type-Options" content="nosniff">
            <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
            <meta http-equiv="Content-Security-Policy" content="default-src \'self\'; script-src \'self\' \'nonce-' . $this->getCspNonce() . '\' https://cdn.jsdelivr.net https://code.jquery.com https://cdnjs.cloudflare.com; style-src \'self\' \'unsafe-inline\' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; font-src \'self\' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src \'self\' data:; connect-src \'self\';">
            <meta name="referrer" content="strict-origin-when-cross-origin">
        ';
    }
}