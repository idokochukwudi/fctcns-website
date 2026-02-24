<?php
/**
 * Application Forgot Password Controller
 * 
 * Dedicated controller for handling forgot password functionality
 * Isolates the password reset flow from the main application controller
 * 
 * @package FCT_CNS
 */

require_once __DIR__ . '/ApplicationBaseController.php';
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class ApplicationForgotController extends ApplicationBaseController {
    
    use SecurityTrait;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Set layout to a minimal layout for auth pages
        $this->layout = 'auth';
        
        error_log("=== ApplicationForgotController initialized ===");
    }
    
    /**
     * Show forgot password page
     */
    public function forgotPassword() {
        error_log("=== forgotPassword() method called ===");
        
        // Initialize security
        $this->initSecurity();
        
        // If already logged in, redirect to dashboard
        if ($this->isApplicantLoggedIn()) {
            $this->redirect('/applicant/dashboard');
            return;
        }
        
        // Get CSRF token using trait method
        $csrfToken = $this->getCsrfToken();
        
        // Get CSP nonce for the view
        $cspNonce = $this->getCspNonce();
        
        // Get security meta tags
        $securityMetaTags = $this->getSecurityMetaTags();
        
        // Prepare data for view
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Forgot Password - FCT College of Nursing Sciences',
            'csrf_token' => $csrfToken,
            'csp_nonce' => $cspNonce,
            'security_meta_tags' => $securityMetaTags,
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '/'
        ]);
        
        // Clear any existing flash messages that might interfere
        $this->clearOldFlashMessages();
        
        error_log("Rendering forgot password view with data: " . print_r($this->data, true));
        
        // Render the view
        $this->render('applications/forgot-password');
    }
    
    /**
     * Process forgot password request
     */
    public function processForgotPassword() {
        error_log("=== processForgotPassword() method called ===");
        error_log("POST data: " . print_r($_POST, true));
        
        // Initialize security
        $this->initSecurity();
        
        // Check if POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Not a POST request, redirecting to forgot password page");
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Validate CSRF token
        if (!$this->validateCsrfToken()) {
            error_log("CSRF validation failed");
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            $_SESSION['email_value'] = $_POST['email'] ?? '';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Get email from POST
        $email = trim($_POST['email'] ?? '');
        error_log("Email received: " . $email);
        
        // Store email to repopulate form on error
        $_SESSION['email_value'] = $email;
        
        // Validate email
        if (empty($email)) {
            error_log("Email is empty");
            $_SESSION['flash_error'] = 'Please enter your email address.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error_log("Invalid email format: " . $email);
            $_SESSION['flash_error'] = 'Please enter a valid email address.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Find applicant by email
        $applicant = $this->applicantModel->findByEmail($email);
        error_log("Applicant found: " . ($applicant ? 'Yes (ID: ' . $applicant['id'] . ')' : 'No'));
        
        if (!$applicant) {
            // For security, don't reveal that email doesn't exist
            error_log("No applicant found with email: " . $email . " - Still showing success message for security");
            
            // Clear email value from session since we're showing success
            unset($_SESSION['email_value']);
            
            // Set success message in flash
            $_SESSION['flash_success'] = 'If your email is registered in our system, you will receive a password reset link shortly.';
            
            // Redirect back to forgot password page with success message
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        try {
            // Check if applicant has email verified
            if (!isset($applicant['email_verified']) || $applicant['email_verified'] != 1) {
                error_log("Email not verified for applicant ID: " . $applicant['id']);
                
                // Send verification email instead
                $this->sendVerificationEmail($email);
                
                // Set appropriate message
                $_SESSION['flash_info'] = 'Your email is not verified. A verification link has been sent to your email. Please verify your email first.';
                
                // Clear email value from session
                unset($_SESSION['email_value']);
                
                $this->redirect('/applicant/forgot-password');
                return;
            }
            
            // Generate reset token
            $resetToken = bin2hex(random_bytes(32));
            $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            error_log("Generated reset token for applicant ID " . $applicant['id'] . ": " . $resetToken);
            
            // Save reset token to database
            $updateResult = $this->applicantModel->update(
                [
                    'reset_token' => $resetToken,
                    'reset_expires' => $resetExpiry
                ],
                'id = :id',
                ['id' => $applicant['id']]
            );
            
            if (!$updateResult) {
                error_log("Failed to update applicant with reset token");
                throw new Exception("Failed to save reset token");
            }
            
            // Send reset email
            $emailSent = $this->sendPasswordResetEmail($email, $resetToken);
            
            if (!$emailSent) {
                error_log("Failed to send password reset email to: " . $email);
                // Continue anyway, don't show error to user
            } else {
                error_log("Password reset email sent successfully to: " . $email);
            }
            
            // Clear email value from session
            unset($_SESSION['email_value']);
            
            // CRITICAL: Set success message in session
            $_SESSION['flash_success'] = 'Password reset instructions have been sent to your email address. Please check your inbox and follow the link to reset your password.';
            
            error_log("Flash success set: " . $_SESSION['flash_success']);
            error_log("Redirecting to forgot password page with success message");
            
            // Redirect back to forgot password page with success message
            $this->redirect('/applicant/forgot-password');
            
        } catch (Exception $e) {
            error_log("Password reset error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            $_SESSION['flash_error'] = 'An error occurred while processing your request. Please try again later.';
            $this->redirect('/applicant/forgot-password');
        }
    }
    
    /**
     * Send password reset email
     * 
     * @param string $email Recipient email
     * @param string $token Reset token
     * @return bool True if email sent successfully
     */
    private function sendPasswordResetEmail($email, $token) {
        $resetLink = (defined('BASE_URL') ? BASE_URL : '') . '/applicant/reset-password?token=' . $token;
        
        $subject = "Password Reset - FCT College of Nursing Sciences";
        
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6B4E9B; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { padding: 30px; background: #f9f9f9; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; padding: 12px 30px; background: #6B4E9B; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
                .button:hover { background: #4A3B6B; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .note { background: #fff3cd; border: 1px solid #ffeeba; padding: 15px; border-radius: 5px; margin: 20px 0; color: #856404; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>FCT College of Nursing Sciences</h2>
                    <p>Gwagwalada, Abuja</p>
                </div>
                <div class='content'>
                    <h3>Password Reset Request</h3>
                    <p>Hello,</p>
                    <p>You recently requested to reset your password for your application portal account. Click the button below to proceed:</p>
                    
                    <p style='text-align: center;'>
                        <a href='{$resetLink}' class='button'>Reset Password</a>
                    </p>
                    
                    <p>If the button doesn't work, copy and paste this link into your browser:</p>
                    <p style='word-break: break-all; background: #eee; padding: 10px; border-radius: 5px;'>{$resetLink}</p>
                    
                    <div class='note'>
                        <strong>Note:</strong> This link will expire in 1 hour. If you didn't request this, please ignore this email.
                    </div>
                    
                    <p>For security reasons, never share this link with anyone.</p>
                    
                    <p>Best regards,<br>Admissions Office<br>FCT College of Nursing Sciences</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " FCT College of Nursing Sciences. All rights reserved.</p>
                    <p>Contact: admissions@fctcns.edu.ng | Support: 07039837749</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Use email helper if available
        if (file_exists(APP_PATH . '/helpers/EmailHelper.php')) {
            require_once APP_PATH . '/helpers/EmailHelper.php';
            $emailHelper = new EmailHelper();
            
            try {
                $result = $emailHelper->sendEmail($email, $subject, $message);
                error_log("EmailHelper sendEmail result: " . ($result ? 'success' : 'failed'));
                return $result;
            } catch (Exception $e) {
                error_log("EmailHelper exception: " . $e->getMessage());
                return false;
            }
        } else {
            // Fallback - log the email
            error_log("=== PASSWORD RESET EMAIL (FALLBACK) ===");
            error_log("To: $email");
            error_log("Subject: $subject");
            error_log("Link: $resetLink");
            error_log("========================================");
            
            // For development, store in session to test
            $_SESSION['last_reset_link'] = $resetLink;
            
            return true; // Assume success for development
        }
    }
    
    /**
     * Send verification email
     * 
     * @param string $email Recipient email
     * @return bool True if email sent successfully
     */
    private function sendVerificationEmail($email) {
        $applicant = $this->applicantModel->findByEmail($email);
        
        if (!$applicant) {
            return false;
        }
        
        // Generate new verification token
        $verificationToken = bin2hex(random_bytes(32));
        
        // Update applicant with new token
        $this->applicantModel->update(
            ['verification_token' => $verificationToken],
            'id = :id',
            ['id' => $applicant['id']]
        );
        
        $verificationLink = (defined('BASE_URL') ? BASE_URL : '') . '/apply/verify-email?token=' . $verificationToken;
        
        $subject = "Email Verification - FCT College of Nursing Sciences";
        
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6B4E9B; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .button { display: inline-block; padding: 10px 20px; background: #6B4E9B; color: white; text-decoration: none; border-radius: 5px; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>FCT College of Nursing Sciences</h2>
                    <p>Email Verification</p>
                </div>
                <div class='content'>
                    <h3>Verify Your Email</h3>
                    <p>Please verify your email address by clicking the button below:</p>
                    
                    <p style='text-align: center;'>
                        <a href='{$verificationLink}' class='button'>Verify Email</a>
                    </p>
                    
                    <p>This link will expire in 24 hours.</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " FCT College of Nursing Sciences</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Use email helper if available
        if (file_exists(APP_PATH . '/helpers/EmailHelper.php')) {
            require_once APP_PATH . '/helpers/EmailHelper.php';
            $emailHelper = new EmailHelper();
            return $emailHelper->sendEmail($email, $subject, $message);
        }
        
        // Fallback - log
        error_log("Verification email would be sent to: $email");
        return true;
    }
    
    /**
     * Show reset password page
     */
    public function resetPassword() {
        error_log("=== resetPassword() method called ===");
        
        // Initialize security
        $this->initSecurity();
        
        $token = $_GET['token'] ?? '';
        error_log("Reset token from URL: " . $token);
        
        if (empty($token)) {
            error_log("No token provided");
            $_SESSION['flash_error'] = 'Invalid password reset link.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Verify token
        $applicant = $this->applicantModel->findByResetToken($token);
        error_log("Applicant found by reset token: " . ($applicant ? 'Yes' : 'No'));
        
        if (!$applicant) {
            error_log("Invalid or expired reset token: " . $token);
            $_SESSION['flash_error'] = 'Invalid or expired reset link. Please request a new one.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Check if token is expired
        if (!empty($applicant['reset_expires']) && strtotime($applicant['reset_expires']) < time()) {
            error_log("Reset token expired for applicant ID: " . $applicant['id']);
            $_SESSION['flash_error'] = 'Your reset link has expired. Please request a new one.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Generate CSRF token
        $csrfToken = $this->getCsrfToken();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Reset Password - FCT College of Nursing Sciences',
            'token' => $token,
            'email' => $applicant['email'],
            'csrf_token' => $csrfToken,
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '/'
        ]);
        
        $this->render('applications/reset-password');
    }
    
    /**
     * Process reset password
     */
    public function processResetPassword() {
        error_log("=== processResetPassword() method called ===");
        error_log("POST data: " . print_r($_POST, true));
        
        // Initialize security
        $this->initSecurity();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Not a POST request");
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Validate CSRF - FIXED to properly check both token types
        $csrfToken = $_POST['csrf_token'] ?? '';
        
        if (empty($csrfToken)) {
            error_log("CSRF token missing from POST");
            $_SESSION['flash_error'] = 'Security token missing. Please try again.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        error_log("Validating CSRF token: " . substr($csrfToken, 0, 10) . "...");
        
        // Check if token exists in csrf_tokens array (multi-token system)
        $validToken = false;
        
        if (isset($_SESSION['csrf_tokens']) && is_array($_SESSION['csrf_tokens'])) {
            if (isset($_SESSION['csrf_tokens'][$csrfToken])) {
                $tokenTime = $_SESSION['csrf_tokens'][$csrfToken];
                
                // Check if token is expired (older than 1 hour)
                $lifetime = defined('CSRF_TOKEN_LIFETIME') ? CSRF_TOKEN_LIFETIME : 3600;
                if (time() - $tokenTime <= $lifetime) {
                    error_log("CSRF validation successful via csrf_tokens array");
                    
                    // Remove token after use (one-time use)
                    unset($_SESSION['csrf_tokens'][$csrfToken]);
                    $validToken = true;
                } else {
                    error_log("CSRF token expired");
                    unset($_SESSION['csrf_tokens'][$csrfToken]);
                }
            }
        }
        
        // Fallback to single csrf_token in session (legacy system)
        if (!$validToken && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $csrfToken)) {
            error_log("CSRF validation successful via single csrf_token");
            
            // Check if token is expired
            $lifetime = defined('CSRF_TOKEN_LIFETIME') ? CSRF_TOKEN_LIFETIME : 3600;
            if (isset($_SESSION['csrf_token_time']) && (time() - $_SESSION['csrf_token_time'] > $lifetime)) {
                error_log("CSRF token expired");
                $validToken = false;
            } else {
                $validToken = true;
                
                // Generate new token for next request
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $_SESSION['csrf_token_time'] = time();
            }
        }
        
        if (!$validToken) {
            error_log("CSRF validation failed: token not found in session");
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        
        error_log("Token: " . $token);
        
        if (empty($token)) {
            error_log("No token provided");
            $_SESSION['flash_error'] = 'Invalid reset token.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Validate password
        if (empty($password)) {
            error_log("Password is empty");
            $_SESSION['flash_error'] = 'Please enter a new password.';
            $this->redirect('/applicant/reset-password?token=' . urlencode($token));
            return;
        }
        
        if (strlen($password) < 8) {
            error_log("Password too short");
            $_SESSION['flash_error'] = 'Password must be at least 8 characters long.';
            $this->redirect('/applicant/reset-password?token=' . urlencode($token));
            return;
        }
        
        if ($password !== $confirm) {
            error_log("Passwords do not match");
            $_SESSION['flash_error'] = 'Passwords do not match.';
            $this->redirect('/applicant/reset-password?token=' . urlencode($token));
            return;
        }
        
        // Verify token
        $applicant = $this->applicantModel->findByResetToken($token);
        error_log("Applicant found by reset token: " . ($applicant ? 'Yes' : 'No'));
        
        if (!$applicant) {
            error_log("Invalid token");
            $_SESSION['flash_error'] = 'Invalid or expired reset link. Please request a new one.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Check if token is expired
        if (!empty($applicant['reset_expires']) && strtotime($applicant['reset_expires']) < time()) {
            error_log("Token expired");
            $_SESSION['flash_error'] = 'Your reset link has expired. Please request a new one.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        try {
            // Begin transaction
            $ownTransaction = false;
            if (!$this->applicantModel->getConnection()->inTransaction()) {
                $this->applicantModel->beginTransaction();
                $ownTransaction = true;
            }
            
            // Update password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateResult = $this->applicantModel->update(
                [
                    'password' => $hashedPassword,
                    'reset_token' => null,
                    'reset_expires' => null
                ],
                'id = :id',
                ['id' => $applicant['id']]
            );
            
            if (!$updateResult) {
                throw new Exception("Failed to update password");
            }
            
            // Commit transaction
            if ($ownTransaction) {
                $this->applicantModel->commit();
            }
            
            error_log("Password reset successfully for applicant ID: " . $applicant['id']);
            
            // Set success message
            $_SESSION['flash_success'] = 'Your password has been reset successfully. You can now login with your new password.';
            
            // Redirect to login page
            $this->redirect('/applicant/login');
            
        } catch (Exception $e) {
            // Rollback transaction
            if ($ownTransaction && $this->applicantModel->getConnection()->inTransaction()) {
                $this->applicantModel->rollBack();
            }
            
            error_log("Password reset error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred while resetting your password. Please try again.';
            $this->redirect('/applicant/reset-password?token=' . urlencode($token));
        }
    }
    
    /**
     * Initialize security measures
     */
    private function initSecurity() {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate session fingerprint if not exists
        if (!isset($_SESSION['security_fingerprint'])) {
            $_SESSION['security_fingerprint'] = $this->generateSessionFingerprint();
        }
    }
    
    /**
     * Generate session fingerprint
     */
    private function generateSessionFingerprint() {
        return hash('sha256', 
            ($_SERVER['HTTP_USER_AGENT'] ?? '') . 
            ($_SERVER['REMOTE_ADDR'] ?? '') . 
            session_id()
        );
    }
    
    /**
     * Get CSRF token from SecurityTrait or generate if not available
     * 
     * @return string
     */
    protected function getCsrfToken() {
        // Try to use SecurityHelper first
        if (class_exists('SecurityHelper') && method_exists('SecurityHelper', 'getCsrfToken')) {
            return SecurityHelper::getCsrfToken();
        }
        
        // Generate a new token
        $token = bin2hex(random_bytes(32));
        
        // Store in csrf_tokens array with timestamp
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }
        
        $_SESSION['csrf_tokens'][$token] = time();
        
        // Also store as single token for backward compatibility
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_time'] = time();
        
        error_log("Generated new CSRF token: " . substr($token, 0, 10) . "...");
        
        return $token;
    }
    
    /**
     * Get CSP nonce from SecurityTrait or generate if not available
     * 
     * @return string
     */
    protected function getCspNonce() {
        // Try to use SecurityHelper first
        if (class_exists('SecurityHelper') && method_exists('SecurityHelper', 'getCspNonce')) {
            return SecurityHelper::getCspNonce();
        }
        
        // Fallback to session-based nonce
        if (!isset($_SESSION['csp_nonce'])) {
            $_SESSION['csp_nonce'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['csp_nonce'];
    }
    
    /**
     * Get security meta tags
     * 
     * @return string
     */
    protected function getSecurityMetaTags() {
        // Try to use SecurityHelper first
        if (class_exists('SecurityHelper') && method_exists('SecurityHelper', 'getSecurityMetaTags')) {
            return SecurityHelper::getSecurityMetaTags();
        }
        
        // Return empty string as fallback
        return '';
    }
    
    /**
     * Clear old flash messages that might interfere
     */
    private function clearOldFlashMessages() {
        // Keep only recent flash messages
        if (isset($_SESSION['flash_success']) && !isset($_SESSION['flash_success_shown'])) {
            // Mark as shown
            $_SESSION['flash_success_shown'] = true;
        }
        
        if (isset($_SESSION['flash_error']) && !isset($_SESSION['flash_error_shown'])) {
            $_SESSION['flash_error_shown'] = true;
        }
    }
    
    /**
     * Redirect helper - FIXED to match parent Controller signature
     * 
     * @param string $url URL to redirect to
     * @param int $statusCode HTTP status code (default 302)
     */
    protected function redirect($url, $statusCode = 302) {
        error_log("ApplicationForgotController redirecting to: " . $url . " with status code: " . $statusCode);
        
        if (!headers_sent()) {
            header('Location: ' . $url, true, $statusCode);
            exit;
        }
        
        echo '<script>window.location.href="' . $url . '";</script>';
        exit;
    }
}