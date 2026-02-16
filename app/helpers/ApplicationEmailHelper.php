<?php
/**
 * Application Email Helper
 * 
 * Handles all email notifications for the application process
 * Uses the existing EmailHelper functionality
 * 
 * @package FCT_CNS
 */

require_once __DIR__ . '/EmailHelper.php';

class ApplicationEmailHelper {
    
    private $emailHelper;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->emailHelper = new EmailHelper();
    }
    
    /**
     * Send welcome email after JAMB verification
     * 
     * @param array $applicant Applicant data
     * @param string $password Generated password
     * @return bool
     */
    public function sendWelcomeEmail($applicant, $password) {
        $subject = "Welcome to FCT College of Nursing Sciences Application Portal";
        
        // Check if email exists
        if (empty($applicant['email'])) {
            error_log("No email provided for applicant: " . $applicant['jamb_number']);
            return false;
        }
        
        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6B4E9B; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .credentials { background: white; padding: 15px; border-left: 4px solid #6B4E9B; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .button { display: inline-block; padding: 10px 20px; background: #6B4E9B; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>FCT College of Nursing Sciences</h2>
                    <p>2025/2026 Application Portal</p>
                </div>
                <div class='content'>
                    <h3>Welcome, {$applicant['first_name']} {$applicant['last_name']}!</h3>
                    <p>Your JAMB number has been verified successfully. You can now complete your application.</p>
                    
                    <div class='credentials'>
                        <h4>Your Login Credentials:</h4>
                        <p><strong>JAMB Number:</strong> {$applicant['jamb_number']}</p>
                        <p><strong>Password:</strong> <span style='background: #f0f0f0; padding: 5px;'>{$password}</span></p>
                    </div>
                    
                    <p><strong>Important:</strong> Please change your password after first login.</p>
                    
                    <p style='text-align: center;'>
                        <a href='" . BASE_URL . "/applicant/login' class='button'>Login to Continue Application</a>
                    </p>
                    
                    <p><strong>Next Steps:</strong></p>
                    <ol>
                        <li>Login with your credentials</li>
                        <li>Complete your application form</li>
                        <li>Upload required documents</li>
                        <li>Pay application fee (₦2,200)</li>
                        <li>Download your examination slip</li>
                    </ol>
                </div>
                <div class='footer'>
                    <p>© " . date('Y') . " FCT College of Nursing Sciences. All rights reserved.</p>
                    <p>Support: support@fctcns.edu.ng | Tel: 07039837749</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Use sendEmail method (not send)
        return $this->emailHelper->sendEmail($applicant['email'], $subject, $body);
    }
    
    /**
     * Send password reset email
     * 
     * @param array $applicant Applicant data
     * @param string $resetToken Reset token
     * @return bool
     */
    public function sendPasswordResetEmail($applicant, $resetToken) {
        if (empty($applicant['email'])) {
            return false;
        }
        
        $resetLink = BASE_URL . "/applicant/reset-password?token=" . urlencode($resetToken);
        
        $subject = "Password Reset - FCT College of Nursing Sciences";
        
        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6B4E9B; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .button { display: inline-block; padding: 10px 20px; background: #6B4E9B; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>FCT College of Nursing Sciences</h2>
                </div>
                <div class='content'>
                    <h3>Password Reset Request</h3>
                    <p>Hello {$applicant['first_name']},</p>
                    <p>We received a request to reset your password. Click the button below to set a new password:</p>
                    
                    <p style='text-align: center;'>
                        <a href='{$resetLink}' class='button'>Reset Password</a>
                    </p>
                    
                    <p>This link will expire in 1 hour.</p>
                    <p>If you didn't request this, please ignore this email.</p>
                </div>
                <div class='footer'>
                    <p>© " . date('Y') . " FCT College of Nursing Sciences</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->emailHelper->sendEmail($applicant['email'], $subject, $body);
    }
    
    /**
     * Send payment confirmation email
     * 
     * @param array $applicant Applicant data
     * @param array $payment Payment data
     * @param array $application Application data
     * @return bool
     */
    public function sendPaymentConfirmation($applicant, $payment, $application) {
        if (empty($applicant['email'])) {
            return false;
        }
        
        $subject = "Payment Confirmation - FCT College of Nursing Sciences";
        
        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6B4E9B; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .payment-details { background: white; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .button { display: inline-block; padding: 10px 20px; background: #6B4E9B; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>FCT College of Nursing Sciences</h2>
                </div>
                <div class='content'>
                    <h3>Payment Confirmed!</h3>
                    <p>Dear {$applicant['first_name']} {$applicant['last_name']},</p>
                    <p>Your payment has been successfully processed.</p>
                    
                    <div class='payment-details'>
                        <h4>Payment Details:</h4>
                        <p><strong>Application Number:</strong> {$application['application_number']}</p>
                        <p><strong>JAMB Number:</strong> {$applicant['jamb_number']}</p>
                        <p><strong>Amount:</strong> ₦" . number_format($payment['amount']) . "</p>
                        <p><strong>RRR:</strong> {$payment['rrr']}</p>
                        <p><strong>Payment Date:</strong> " . date('jS F Y, h:i A', strtotime($payment['payment_date'])) . "</p>
                    </div>
                    
                    <p>You can now download your examination slip.</p>
                    
                    <p style='text-align: center;'>
                        <a href='" . BASE_URL . "/apply/step/4' class='button'>Download Exam Slip</a>
                    </p>
                </div>
                <div class='footer'>
                    <p>© " . date('Y') . " FCT College of Nursing Sciences</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->emailHelper->sendEmail($applicant['email'], $subject, $body);
    }
    
    /**
     * Send exam slip notification
     * 
     * @param array $applicant Applicant data
     * @param array $application Application data
     * @param array $examSlip Exam slip data
     * @return bool
     */
    public function sendExamSlipNotification($applicant, $application, $examSlip) {
        if (empty($applicant['email'])) {
            return false;
        }
        
        $subject = "Examination Slip Generated - FCT College of Nursing Sciences";
        
        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6B4E9B; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .exam-details { background: white; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .button { display: inline-block; padding: 10px 20px; background: #6B4E9B; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>FCT College of Nursing Sciences</h2>
                </div>
                <div class='content'>
                    <h3>Examination Slip Ready</h3>
                    <p>Dear {$applicant['first_name']} {$applicant['last_name']},</p>
                    <p>Your examination slip has been generated successfully.</p>
                    
                    <div class='exam-details'>
                        <h4>Examination Details:</h4>
                        <p><strong>Slip Number:</strong> {$examSlip['slip_number']}</p>
                        <p><strong>Date:</strong> " . date('l, jS F Y', strtotime($examSlip['exam_date'])) . "</p>
                        <p><strong>Time:</strong> " . date('h:i A', strtotime($examSlip['exam_time'])) . "</p>
                        <p><strong>Venue:</strong> {$examSlip['exam_venue']}</p>
                        <p><strong>Reporting Time:</strong> " . date('h:i A', strtotime($examSlip['reporting_time'])) . "</p>
                        <p><strong>Seat Number:</strong> {$examSlip['seat_number']}</p>
                    </div>
                    
                    <p><strong>Important Instructions:</strong></p>
                    <ul>
                        <li>Print this slip and bring it to the examination venue</li>
                        <li>Arrive at least 30 minutes before reporting time</li>
                        <li>Bring your writing materials (pen, pencil, eraser)</li>
                        <li>Bring a valid means of identification</li>
                    </ul>
                    
                    <p style='text-align: center;'>
                        <a href='" . BASE_URL . "/apply/download-exam-slip' class='button'>Download Exam Slip</a>
                    </p>
                </div>
                <div class='footer'>
                    <p>© " . date('Y') . " FCT College of Nursing Sciences</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->emailHelper->sendEmail($applicant['email'], $subject, $body);
    }
    
    /**
     * Send application status update email
     * 
     * @param array $applicant Applicant data
     * @param array $application Application data
     * @param string $oldStatus Previous status
     * @param string $newStatus New status
     * @param string $notes Admin notes
     * @return bool
     */
    public function sendStatusUpdate($applicant, $application, $oldStatus, $newStatus, $notes = '') {
        if (empty($applicant['email'])) {
            return false;
        }
        
        $subject = "Application Status Update - FCT College of Nursing Sciences";
        
        $statusColors = [
            'pending' => '#ffc107',
            'reviewed' => '#17a2b8',
            'accepted' => '#28a745',
            'rejected' => '#dc3545',
            'waitlisted' => '#fd7e14'
        ];
        
        $color = $statusColors[$newStatus] ?? '#6c757d';
        
        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6B4E9B; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .status-badge { display: inline-block; padding: 8px 16px; background: {$color}; color: white; border-radius: 4px; font-weight: bold; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>FCT College of Nursing Sciences</h2>
                </div>
                <div class='content'>
                    <h3>Application Status Updated</h3>
                    <p>Dear {$applicant['first_name']} {$applicant['last_name']},</p>
                    <p>The status of your application has been updated.</p>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <span class='status-badge'>" . strtoupper($newStatus) . "</span>
                    </div>
                    
                    <p><strong>Application Number:</strong> {$application['application_number']}</p>
                    <p><strong>Previous Status:</strong> " . ucfirst($oldStatus) . "</p>
                    <p><strong>New Status:</strong> " . ucfirst($newStatus) . "</p>
                    
                    " . (!empty($notes) ? "<p><strong>Notes:</strong> {$notes}</p>" : "") . "
                    
                    <p style='text-align: center; margin-top: 30px;'>
                        <a href='" . BASE_URL . "/applicant/login' style='background: #6B4E9B; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login to View Details</a>
                    </p>
                </div>
                <div class='footer'>
                    <p>© " . date('Y') . " FCT College of Nursing Sciences</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->emailHelper->sendEmail($applicant['email'], $subject, $body);
    }
}