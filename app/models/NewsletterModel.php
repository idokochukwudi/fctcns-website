<?php
/**
 * Newsletter Model - Handle newsletter subscriptions WITH WELCOME EMAIL AND SPAM NOTIFICATION
 * FIXED: Email template syntax error resolved
 * FIXED: Debug logging added for troubleshooting
 */
class NewsletterModel {
    private $db;
    private $emailHelper;
    private $config;
    
    public function __construct($database = null) {
        if ($database) {
            $this->db = $database;
        } else {
            require_once __DIR__ . '/../config/database.php';
            $database = Database::getInstance();
            $this->db = $database->getConnection();
        }
        
        // Initialize Email Helper
        require_once APP_PATH . '/helpers/EmailHelper.php';
        $this->emailHelper = new EmailHelper();
        
        // Initialize email configuration
        $this->loadEmailConfig();
    }
    
    /**
     * Load email configuration
     */
    private function loadEmailConfig() {
        try {
            if (file_exists(APP_PATH . '/config/email.php')) {
                require_once APP_PATH . '/config/email.php';
                $allConfig = require APP_PATH . '/config/email.php';
                $env = (defined('IS_LOCALHOST') && IS_LOCALHOST) ? 'development' : 'production';
                $this->config = $allConfig[$env] ?? $allConfig['production'] ?? [];
            }
            
            // Set default from email if not configured
            if (!isset($this->config['from_email'])) {
                $this->config['from_email'] = defined('NEWSLETTER_FROM_EMAIL') 
                    ? NEWSLETTER_FROM_EMAIL 
                    : 'newsletter@fctcns.edu.ng';
            }
        } catch (Exception $e) {
            error_log("Error loading email config: " . $e->getMessage());
            $this->config = ['from_email' => 'newsletter@fctcns.edu.ng'];
        }
    }
    
    /**
     * Subscribe email to newsletter - WITH WELCOME EMAIL AND SPAM NOTIFICATION
     */
    public function subscribe($email, $source = 'newsletter_widget') {
        try {
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [
                    'success' => false,
                    'message' => '❌ Please enter a valid email address'
                ];
            }
            
            // Check if already subscribed
            $checkSql = "SELECT id, status FROM newsletter_subscribers WHERE email = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$email]);
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                if ($existing['status'] === 'active') {
                    return [
                        'success' => false,
                        'message' => '⚠️ This email is already subscribed to our newsletter'
                    ];
                } elseif ($existing['status'] === 'unsubscribed') {
                    // Reactivate
                    $updateSql = "UPDATE newsletter_subscribers 
                                  SET status = 'active', 
                                      unsubscribed_at = NULL,
                                      updated_at = NOW() 
                                  WHERE id = ?";
                    $updateStmt = $this->db->prepare($updateSql);
                    $updateStmt->execute([$existing['id']]);
                    
                    // SEND WELCOME EMAIL (Reactivated)
                    $this->sendWelcomeEmail($email, true);
                    
                    return [
                        'success' => true,
                        'message' => '✅ Your subscription has been reactivated! 📧 Please check your inbox AND spam folder for our welcome email.'
                    ];
                }
            }
            
            // Generate confirmation token
            $token = bin2hex(random_bytes(16));
            
            // Insert new subscriber
            $sql = "INSERT INTO newsletter_subscribers (
                        email, 
                        status, 
                        source, 
                        ip_address, 
                        user_agent,
                        confirmation_token,
                        subscribed_at, 
                        created_at, 
                        updated_at
                    ) VALUES (?, 'active', ?, ?, ?, ?, NOW(), NOW(), NOW())";
            
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                $email,
                $source,
                $ip,
                $userAgent,
                $token
            ]);
            
            if ($success) {
                $id = $this->db->lastInsertId();
                
                // SEND WELCOME EMAIL (New subscriber)
                $this->sendWelcomeEmail($email);
                
                // Update confirmation_sent_at
                $updateSql = "UPDATE newsletter_subscribers SET confirmation_sent_at = NOW() WHERE id = ?";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->execute([$id]);
                
                return [
                    'success' => true,
                    'message' => '✅ Thank you for subscribing! 📧 Please check your inbox AND spam folder for our welcome email.',
                    'id' => $id
                ];
            } else {
                return [
                    'success' => false,
                    'message' => '❌ Failed to subscribe. Please try again.'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Newsletter subscription error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => '❌ An error occurred. Please try again later.'
            ];
        }
    }
    
    /**
     * Send welcome email to new subscribers - WITH DEBUG LOGGING
     */
    private function sendWelcomeEmail($email, $isReactivated = false) {
        try {
            // DEBUG LOGGING
            error_log("=== SENDING WELCOME EMAIL ===");
            error_log("To: $email");
            error_log("Reactivated: " . ($isReactivated ? 'Yes' : 'No'));
            error_log("Server time: " . date('Y-m-d H:i:s'));
            
            // Ensure config is loaded
            if (!isset($this->config) || empty($this->config)) {
                $this->loadEmailConfig();
            }
            
            if ($isReactivated) {
                $subject = "🎉 Welcome Back to FCT Nursing College Newsletter!";
                $heading = "You're Back!";
                $message = "Your subscription has been reactivated successfully. We're glad to have you with us again!";
            } else {
                $subject = "🎉 Welcome to FCT College of Nursing Sciences Newsletter!";
                $heading = "Thank You for Subscribing!";
                $message = "You've successfully subscribed to our newsletter. Welcome to the FCT Nursing family!";
            }
            
            // Build HTML email template
            $htmlContent = $this->buildWelcomeEmailTemplate($heading, $message, $email);
            
            // Log template details
            error_log("Email template built successfully");
            error_log("Subject: " . $subject);
            error_log("Email template length: " . strlen($htmlContent) . " characters");
            error_log("Template preview (first 200 chars): " . substr($htmlContent, 0, 200) . "...");
            
            // Verify EmailHelper exists
            if (!$this->emailHelper) {
                error_log("❌ EmailHelper not initialized!");
                return false;
            }
            
            // Send email
            error_log("Attempting to send email via EmailHelper...");
            $sent = $this->emailHelper->sendEmail(
                $email,
                $subject,
                $htmlContent
            );
            
            if ($sent) {
                error_log("✅ Welcome email sent successfully to: $email");
            } else {
                error_log("❌ FAILED to send welcome email to: $email");
                // Log EmailHelper errors if available
                if (method_exists($this->emailHelper, 'getLastError')) {
                    error_log("EmailHelper last error: " . $this->emailHelper->getLastError());
                }
            }
            
            return $sent;
            
        } catch (Exception $e) {
            error_log("❌❌❌ sendWelcomeEmail EXCEPTION: " . $e->getMessage());
            error_log("Exception type: " . get_class($e));
            error_log("File: " . $e->getFile() . ":" . $e->getLine());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }
    
    /**
     * Build welcome email HTML template - FIXED VERSION
     * CRITICAL FIX: No PHP concatenation inside HEREDOC
     * CRITICAL FIX: Unsubscribe link properly built before HEREDOC
     */
    private function buildWelcomeEmailTemplate($heading, $message, $email) {
        // Get base URL
        $baseUrl = defined('BASE_URL') ? BASE_URL : 'https://fctcns.edu.ng';
        $year = date('Y');
        
        // CRITICAL FIX: Build the unsubscribe link BEFORE the HEREDOC
        $fromEmail = $this->config['from_email'] ?? 'newsletter@fctcns.edu.ng';
        $unsubscribeLink = $baseUrl . '/newsletter/unsubscribe?email=' . urlencode($email);
        $newsLink = $baseUrl . '/news';
        $privacyLink = $baseUrl . '/privacy';
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #5D4A8A, #4A3A6F);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .spam-notice {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            margin: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: 600;
        }
        .content {
            padding: 30px 25px;
        }
        .content h2 {
            color: #5D4A8A;
            margin-top: 0;
            font-size: 22px;
        }
        .content p {
            margin-bottom: 20px;
            font-size: 16px;
            color: #555;
        }
        .button {
            display: inline-block;
            background: #5D4A8A;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background: #4A3A6F;
        }
        .footer {
            background: #f8f8f8;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #888;
            border-top: 1px solid #eee;
        }
        .footer a {
            color: #5D4A8A;
            text-decoration: none;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 5px;
            color: #5D4A8A;
        }
        .unsubscribe {
            margin-top: 15px;
            font-size: 12px;
        }
        .deliverability-tips {
            margin-top: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #5D4A8A;
            font-size: 13px;
            border-radius: 0 5px 5px 0;
        }
        .emoji {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 FCT College of Nursing Sciences</h1>
        </div>
        
        <div class="spam-notice">
            ⚠️ <strong>Didn't see this email?</strong> Please check your <strong>SPAM/JUNK folder</strong> 
            and mark us as "Not Spam" to ensure you receive future updates.
        </div>
        
        <div class="content">
            <h2>$heading</h2>
            <p>$message</p>
            <p>You'll now receive the latest news, events, and updates from our institution directly in your inbox.</p>
            
            <div style="background: #f0f0f0; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 0; color: #333; font-size: 14px;">
                    <strong>📧 What to expect:</strong>
                </p>
                <ul style="margin-bottom: 0; color: #555;">
                    <li>📢 Latest news and announcements</li>
                    <li>📅 Upcoming events and deadlines</li>
                    <li>🔬 Research breakthroughs</li>
                    <li>🎓 Student achievements</li>
                    <li>🏛️ Institutional updates</li>
                </ul>
            </div>
            
            <center>
                <a href="{$newsLink}" class="button">📰 View Latest News →</a>
            </center>
            
            <p style="font-size: 14px; color: #777; font-style: italic;">
                We're committed to keeping you informed about the latest developments in nursing education and healthcare at FCT College of Nursing Sciences.
            </p>
            
            <div class="deliverability-tips">
                <p style="margin: 0 0 10px 0; color: #495057; font-weight: 600;">
                    📌 To ensure our emails reach your inbox:
                </p>
                <ul style="margin-bottom: 0; color: #6c757d; padding-left: 20px;">
                    <li>➕ Add <strong>{$fromEmail}</strong> to your address book</li>
                    <li>📨 Mark this email as "Not Spam" if it landed in your junk folder</li>
                    <li>📋 Check your promotions tab if using Gmail</li>
                </ul>
            </div>
            
            <div style="margin-top: 20px; padding: 12px; background: #e8f4f8; border-radius: 5px; font-size: 13px; color: #2c3e50; text-align: center;">
                <span class="emoji">💡</span> <strong>Quick tip:</strong> Dragging this email from spam to inbox automatically marks us as safe!
            </div>
        </div>
        
        <div class="footer">
            <p style="font-size: 16px; font-weight: 600; color: #5D4A8A;">FCT College of Nursing Sciences</p>
            <p style="margin: 5px 0;">Abuja, Nigeria</p>
            
            <div class="social-links">
                <a href="#">📘 Facebook</a> |
                <a href="#">🐦 Twitter</a> |
                <a href="#">💼 LinkedIn</a> |
                <a href="#">📸 Instagram</a>
            </div>
            
            <div class="unsubscribe">
                <a href="{$unsubscribeLink}">📧 Unsubscribe</a> |
                <a href="{$privacyLink}">🔒 Privacy Policy</a>
            </div>
            
            <p style="margin-top: 20px; font-size: 11px; color: #999;">
                &copy; {$year} FCT College of Nursing Sciences. All rights reserved.<br>
                This email was sent to {$email} because you subscribed to our newsletter.
            </p>
        </div>
    </div>
</body>
</html>
HTML;
    }
    
    /**
     * Unsubscribe email from newsletter - WITH IMPROVED MESSAGING
     */
    public function unsubscribe($email, $token = null) {
        try {
            $sql = "UPDATE newsletter_subscribers 
                    SET status = 'unsubscribed', 
                        unsubscribed_at = NOW(),
                        updated_at = NOW() 
                    WHERE email = ?";
            
            $params = [$email];
            
            if ($token) {
                $sql .= " AND confirmation_token = ?";
                $params[] = $token;
            }
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute($params);
            
            if ($success && $stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => '✅ You have been successfully unsubscribed. We\'re sorry to see you go! 👋'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => '❌ Email not found or already unsubscribed.'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Newsletter unsubscribe error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => '❌ An error occurred. Please try again.'
            ];
        }
    }
    
    /**
     * Confirm subscription
     */
    public function confirm($email, $token) {
        try {
            $sql = "UPDATE newsletter_subscribers 
                    SET confirmed_at = NOW(),
                        updated_at = NOW() 
                    WHERE email = ? AND confirmation_token = ? AND status = 'active'";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email, $token]);
            
            return $stmt->rowCount() > 0;
            
        } catch (Exception $e) {
            error_log("Newsletter confirm error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get active subscribers count
     */
    public function getSubscriberCount() {
        try {
            $sql = "SELECT COUNT(*) as count FROM newsletter_subscribers WHERE status = 'active'";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            error_log("Newsletter count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get all active subscribers (for admin)
     */
    public function getActiveSubscribers($limit = 100) {
        try {
            $sql = "SELECT id, email, source, subscribed_at 
                    FROM newsletter_subscribers 
                    WHERE status = 'active' 
                    ORDER BY subscribed_at DESC 
                    LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Newsletter getActiveSubscribers error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get subscriber by email
     */
    public function getSubscriberByEmail($email) {
        try {
            $sql = "SELECT * FROM newsletter_subscribers WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Newsletter getSubscriberByEmail error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Delete subscriber (admin only)
     */
    public function deleteSubscriber($id) {
        try {
            $sql = "DELETE FROM newsletter_subscribers WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Newsletter deleteSubscriber error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Test email sending - For debugging only
     */
    public function testEmailSending($email) {
        error_log("=== TEST EMAIL SENDING ===");
        error_log("Testing email to: $email");
        
        $result = $this->sendWelcomeEmail($email, false);
        
        error_log("Test email result: " . ($result ? "SUCCESS" : "FAILED"));
        
        return [
            'success' => $result,
            'message' => $result ? '✅ Test email sent successfully' : '❌ Test email failed'
        ];
    }
}