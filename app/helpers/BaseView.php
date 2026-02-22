<?php
/**
 * Base View Class
 * All views can extend this for security features
 * 
 * @package FCTCNS
 */

require_once __DIR__ . '/SecurityHelper.php';

abstract class BaseView {
    protected $csp_nonce;
    protected $csrf_token;
    protected $data = [];
    
    public function __construct($data = []) {
        $this->data = $data;
        $this->csp_nonce = SecurityHelper::getCspNonce();
        $this->csrf_token = SecurityHelper::getCsrfToken();
    }
    
    /**
     * Escape output
     */
    protected function e($text) {
        return SecurityHelper::e($text);
    }
    
    /**
     * Get security meta tags
     */
    protected function getSecurityMetaTags() {
        return SecurityHelper::getSecurityMetaTags();
    }
    
    /**
     * Render the view
     */
    abstract public function render();
    
    /**
     * Include a partial view
     */
    protected function partial($partial, $locals = []) {
        extract(array_merge($this->data, $locals));
        include APP_PATH . "/app/views/partials/{$partial}.php";
    }
}