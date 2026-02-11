<?php
/**
 * Newsletter Model - Handle newsletter subscriptions WITH WELCOME EMAIL
 */
class NewsletterModel {
    private $db;
    private $emailHelper;
    
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
    }
    
    /**
     * Subscribe email to newsletter - WITH WELCOME EMAIL
     */
    public function subscribe($email, $source = 'newsletter_widget') {
        try {
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [
                    'success' => false,
                    'message' => 'Please enter a valid email address'
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
                        'message' => 'This email is already subscribed to our newsletter'
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
                        'message' => 'Your subscription has been reactivated! Check your email for confirmation.'
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
                    'message' => 'Thank you for subscribing! Please check your email for confirmation.',
                    'id' => $id
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to subscribe. Please try again.'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Newsletter subscription error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ];
        }
    }
    
    /**
     * Send welcome email to new subscribers
     */
    private function sendWelcomeEmail($email, $isReactivated = false) {
        try {
            if ($isReactivated) {
                $subject = "Welcome Back to FCT Nursing College Newsletter!";
                $heading = "You're Back!";
                $message = "Your subscription has been reactivated successfully.";
            } else {
                $subject = "Welcome to FCT College of Nursing Sciences Newsletter!";
                $heading = "Thank You for Subscribing!";
                $message = "You've successfully subscribed to our newsletter.";
            }
            
            // Build HTML email template
            $htmlContent = $this->buildWelcomeEmailTemplate($heading, $message, $email);
            
            // Send email
            $sent = $this->emailHelper->sendEmail(
                $email,
                $subject,
                $htmlContent
            );
            
            if ($sent) {
                error_log("✅ Welcome email sent to: $email");
            } else {
                error_log("❌ Failed to send welcome email to: $email");
            }
            
            return $sent;
            
        } catch (Exception $e) {
            error_log("sendWelcomeEmail error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Build welcome email HTML template
     */
    private function buildWelcomeEmailTemplate($heading, $message, $email) {
        $baseUrl = defined('BASE_URL') ? BASE_URL : 'https://fctcns.edu.ng';
        $year = date('Y');
        
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>FCT College of Nursing Sciences</h1>
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
                    <li>Latest news and announcements</li>
                    <li>Upcoming events and deadlines</li>
                    <li>Research breakthroughs</li>
                    <li>Student achievements</li>
                    <li>Institutional updates</li>
                </ul>
            </div>
            
            <center>
                <a href="{$baseUrl}/news" class="button">View Latest News →</a>
            </center>
            
            <p style="font-size: 14px; color: #777;">
                We're committed to keeping you informed about the latest developments in nursing education and healthcare at FCT College of Nursing Sciences.
            </p>
        </div>
        
        <div class="footer">
            <p>FCT College of Nursing Sciences</p>
            <p>Abuja, Nigeria</p>
            
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Twitter</a> |
                <a href="#">LinkedIn</a> |
                <a href="#">Instagram</a>
            </div>
            
            <div class="unsubscribe">
                <a href="{$baseUrl}/newsletter/unsubscribe?email=" . urlencode('{$email}') . "">Unsubscribe</a> |
                <a href="{$baseUrl}/privacy">Privacy Policy</a>
            </div>
            
            <p style="margin-top: 15px; font-size: 11px;">
                &copy; {$year} FCT College of Nursing Sciences. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
HTML;
    }
    
    /**
     * Unsubscribe email from newsletter
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
                    'message' => 'You have been unsubscribed successfully.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Email not found or already unsubscribed.'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Newsletter unsubscribe error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred. Please try again.'
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
}