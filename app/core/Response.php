<?php
/**
 * Response Class
 * 
 * Handles HTTP responses (redirects, JSON, views, etc.)
 * 
 * @package FCT_CNS
 */

class Response {
    
    /**
     * Redirect to a URL
     */
    public static function redirect($url, $statusCode = 302) {
        if (!headers_sent()) {
            header("Location: $url", true, $statusCode);
        } else {
            echo "<script>window.location.href='$url';</script>";
        }
        exit;
    }
    
    /**
     * Redirect back to previous page
     */
    public static function back() {
        $referrer = Request::referrer();
        if (!empty($referrer)) {
            self::redirect($referrer);
        } else {
            self::redirect(BASE_URL);
        }
    }
    
    /**
     * Set HTTP status code
     */
    public static function status($code) {
        http_response_code($code);
        return new self();
    }
    
    /**
     * Set HTTP header
     */
    public static function header($name, $value) {
        header("$name: $value");
        return new self();
    }
    
    /**
     * Send JSON response
     */
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Send plain text response
     */
    public static function text($text, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: text/plain; charset=utf-8');
        echo $text;
        exit;
    }
    
    /**
     * Send HTML response
     */
    public static function html($html, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }
    
    /**
     * Set flash message and redirect
     */
    public static function withMessage($type, $message, $url = null) {
        require_once APP_PATH . '/config/session.php';
        
        Session::setFlash($type, $message);
        
        if ($url) {
            self::redirect($url);
        } else {
            self::back();
        }
    }
    
    /**
     * Send success response
     */
    public static function success($message, $data = null, $redirect = null) {
        if (Request::isAjax()) {
            self::json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ]);
        } elseif ($redirect) {
            self::withMessage('success', $message, $redirect);
        } else {
            self::withMessage('success', $message);
        }
    }
    
    /**
     * Send error response
     */
    public static function error($message, $errors = [], $redirect = null) {
        if (Request::isAjax()) {
            self::json([
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ], 400);
        } elseif ($redirect) {
            self::withMessage('error', $message, $redirect);
        } else {
            self::withMessage('error', $message);
        }
    }
    
    /**
     * Download file
     */
    public static function download($filePath, $fileName = null) {
        if (!file_exists($filePath)) {
            self::error('File not found');
        }
        
        if ($fileName === null) {
            $fileName = basename($filePath);
        }
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        
        readfile($filePath);
        exit;
    }
}
?>