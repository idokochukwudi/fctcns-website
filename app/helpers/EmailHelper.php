<?php
/**
 * Email Helper - Optimized for GO54 Shared Hosting
 * Uses PHP mail() function - no SMTP configuration needed
 */
class EmailHelper {
    
    private $config;
    private $isLocalhost;
    
    public function __construct() {
        $this->isLocalhost = defined('IS_LOCALHOST') ? IS_LOCALHOST : false;
        $this->loadConfig();
    }
    
    /**
     * Load email configuration
     */
    private function loadConfig() {
        $configFile = APP_PATH . '/config/email.php';
        if (file_exists($configFile)) {
            $allConfig = require $configFile;
            $env = $this->isLocalhost ? 'development' : 'production';
            $this->config = $allConfig[$env];
        } else {
            // Fallback configuration
            $this->config = [
                'from_email' => $this->isLocalhost ? 'noreply@localhost.local' : 'newsletter@fctcns.edu.ng',
                'from_name' => 'FCT College of Nursing Sciences',
                'use_smtp' => false
            ];
        }
    }
    
    /**
     * Send HTML email using PHP mail() - WORKS ON GO54
     */
    public function sendEmail($to, $subject, $htmlContent, $textContent = '') {
        try {
            // On localhost, just log the email instead of sending
            if ($this->isLocalhost) {
                return $this->logEmail($to, $subject, $htmlContent);
            }
            
            // PRODUCTION - GO54 SHARED HOSTING
            $from_email = $this->config['from_email'];
            $from_name = $this->config['from_name'];
            
            // Generate plain text version if not provided
            if (empty($textContent)) {
                $textContent = strip_tags($htmlContent);
            }
            
            // Headers
            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: ' . $from_name . ' <' . $from_email . '>',
                'Reply-To: ' . $from_email,
                'X-Mailer: PHP/' . phpversion()
            ];
            
            // Send email
            $success = mail($to, $subject, $htmlContent, implode("\r\n", $headers));
            
            if ($success) {
                error_log("✅ Email sent successfully to: $to");
            } else {
                error_log("❌ Failed to send email to: $to");
            }
            
            return $success;
            
        } catch (Exception $e) {
            error_log("EmailHelper error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Log email on localhost
     */
    private function logEmail($to, $subject, $content) {
        $logFile = APP_PATH . '/logs/emails.log';
        
        // Create logs directory if it doesn't exist
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        
        $logEntry = date('Y-m-d H:i:s') . " - TO: $to - SUBJECT: $subject\n";
        $logEntry .= "CONTENT: " . strip_tags($content) . "\n";
        $logEntry .= "----------------------------------------\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        error_log("📧 LOCALHOST: Email logged instead of sent to: $to");
        return true;
    }
}