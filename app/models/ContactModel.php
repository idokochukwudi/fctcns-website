<?php
/**
 * Contact Model - Updated with Reply-to Email functionality and Webmail Compose Links
 * 
 * @package FCTCNS
 */

class ContactModel {
    private $db;
    
    public function __construct() {
        require_once APP_PATH . '/config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    /**
     * Save contact form submission
     */
    public function saveSubmission($data) {
        $sql = "INSERT INTO contact_submissions 
                (name, email, phone, subject, message, department, ip_address, user_agent) 
                VALUES (:name, :email, :phone, :subject, :message, :department, :ip, :agent)";
        
        $stmt = $this->db->prepare($sql);
        
        $result = $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':subject' => $data['subject'],
            ':message' => $data['message'],
            ':department' => $data['department'] ?? 'general',
            ':ip' => $_SERVER['REMOTE_ADDR'],
            ':agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ]);
        
        // Send auto-response if submission was successful
        if ($result) {
            $submissionId = $this->db->lastInsertId();
            $this->sendAutoResponse($submissionId);
        }
        
        return $result;
    }
    
    /**
     * Get the appropriate reply-to email address for a submission
     * 
     * @param array $submission The submission data
     * @return string The email address to use for replies
     */
    public function getReplyToEmail($submission) {
        // Get contact settings
        $settings = $this->getContactSettings();
        
        // Determine which email to use based on department
        $department = strtolower($submission['department'] ?? 'general');
        
        switch ($department) {
            case 'admissions':
                return $settings['admissions_email'] ?? 'admissions@fctcns.edu.ng';
            case 'support':
            case 'technical':
            case 'it':
                return $settings['support_email'] ?? 'support@fctcns.edu.ng';
            case 'billing':
            case 'finance':
            case 'accounts':
                return $settings['billing_email'] ?? 'billing@fctcns.edu.ng';
            case 'academic':
            case 'registrar':
                return $settings['academic_email'] ?? 'academic@fctcns.edu.ng';
            case 'general':
            default:
                return $settings['reply_to_email'] ?? 'noreply@fctcns.edu.ng';
        }
    }
    
    /**
     * Generate Gmail web compose URL
     * 
     * @param array $submission The submission data
     * @return string Gmail compose URL
     */
    public function generateGmailComposeUrl($submission) {
        $replyToEmail = $this->getReplyToEmail($submission);
        $settings = $this->getContactSettings();
        
        // Build email components
        $to = $submission['email'];
        $subject = "RE: " . $submission['subject'];
        
        // Build email body with proper line breaks
        $body = "Dear " . $submission['name'] . ",\n\n";
        $body .= "Thank you for contacting FCT College of Nursing Sciences.\n\n";
        $body .= "Regarding your inquiry about: " . $submission['subject'] . "\n\n";
        $body .= "Reference ID: #" . $submission['id'] . "\n\n\n";
        $body .= "Best regards,\n";
        $body .= "FCT College of Nursing Sciences\n";
        $body .= $settings['phone'] ?? "+234 XXX XXX XXXX";
        $body .= "\n" . ($settings['reply_to_email'] ?? 'noreply@fctcns.edu.ng');
        
        // Gmail uses different parameters:
        // view=cm - compose mode
        // to=recipient
        // su=subject  
        // body=message
        // cc=cc address
        // bcc=bcc address
        
        $params = [
            'view' => 'cm',
            'fs' => '1', // full screen
            'to' => $to,
            'su' => $subject,
            'body' => $body,
            'cc' => $replyToEmail,
            'replyto' => $replyToEmail
        ];
        
        // Build Gmail URL
        $gmailUrl = 'https://mail.google.com/mail/?';
        
        // Add parameters - manually build to ensure proper encoding
        $queryParts = [];
        foreach ($params as $key => $value) {
            $queryParts[] = $key . '=' . rawurlencode($value);
        }
        
        $gmailUrl .= implode('&', $queryParts);
        
        return $gmailUrl;
    }
    
    /**
     * Generate Outlook.com compose URL
     * 
     * @param array $submission The submission data
     * @return string Outlook compose URL
     */
    public function generateOutlookComposeUrl($submission) {
        $replyToEmail = $this->getReplyToEmail($submission);
        $settings = $this->getContactSettings();
        
        $to = $submission['email'];
        $subject = "RE: " . $submission['subject'];
        
        $body = "Dear " . $submission['name'] . ",\n\n";
        $body .= "Thank you for contacting FCT College of Nursing Sciences.\n\n";
        $body .= "Regarding your inquiry about: " . $submission['subject'] . "\n\n";
        $body .= "Reference ID: #" . $submission['id'] . "\n\n\n";
        $body .= "Best regards,\n";
        $body .= "FCT College of Nursing Sciences\n";
        $body .= $settings['phone'] ?? "+234 XXX XXX XXXX";
        $body .= "\n" . ($settings['reply_to_email'] ?? 'noreply@fctcns.edu.ng');
        
        $params = [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'cc' => $replyToEmail
        ];
        
        return 'https://outlook.live.com/mail/0/deeplink/compose?' . http_build_query($params);
    }
    
    /**
     * Generate Yahoo Mail compose URL
     * 
     * @param array $submission The submission data
     * @return string Yahoo compose URL
     */
    public function generateYahooComposeUrl($submission) {
        $replyToEmail = $this->getReplyToEmail($submission);
        $settings = $this->getContactSettings();
        
        $to = $submission['email'];
        $subject = "RE: " . $submission['subject'];
        
        $body = "Dear " . $submission['name'] . ",\n\n";
        $body .= "Thank you for contacting FCT College of Nursing Sciences.\n\n";
        $body .= "Regarding your inquiry about: " . $submission['subject'] . "\n\n";
        $body .= "Reference ID: #" . $submission['id'] . "\n\n\n";
        $body .= "Best regards,\n";
        $body .= "FCT College of Nursing Sciences\n";
        $body .= $settings['phone'] ?? "+234 XXX XXX XXXX";
        $body .= "\n" . ($settings['reply_to_email'] ?? 'noreply@fctcns.edu.ng');
        
        $params = [
            'to' => $to,
            'sub' => $subject,
            'body' => $body,
            'cc' => $replyToEmail
        ];
        
        return 'https://compose.mail.yahoo.com/?' . http_build_query($params);
    }
    
    /**
     * Generate Proton Mail compose URL
     * 
     * @param array $submission The submission data
     * @return string Proton Mail compose URL
     */
    public function generateProtonMailComposeUrl($submission) {
        $replyToEmail = $this->getReplyToEmail($submission);
        $settings = $this->getContactSettings();
        
        $to = $submission['email'];
        $subject = "RE: " . $submission['subject'];
        
        $body = "Dear " . $submission['name'] . ",\n\n";
        $body .= "Thank you for contacting FCT College of Nursing Sciences.\n\n";
        $body .= "Regarding your inquiry about: " . $submission['subject'] . "\n\n";
        $body .= "Reference ID: #" . $submission['id'] . "\n\n\n";
        $body .= "Best regards,\n";
        $body .= "FCT College of Nursing Sciences\n";
        $body .= $settings['phone'] ?? "+234 XXX XXX XXXX";
        
        $params = [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'cc' => $replyToEmail
        ];
        
        return 'https://mail.proton.me/u/0/inbox?compose=1&' . http_build_query($params);
    }
    
    /**
     * Generate mailto link for reply with webmail options
     * 
     * @param array $submission The submission data
     * @return array Mailto link components and webmail links
     */
    public function generateMailtoLink($submission) {
        $replyToEmail = $this->getReplyToEmail($submission);
        $settings = $this->getContactSettings();
        
        // Email components
        $to = $submission['email'];
        $subject = "RE: " . $submission['subject'];
        
        // Build email body with template
        $body = "Dear " . $submission['name'] . ",\n\n";
        $body .= "Thank you for contacting FCT College of Nursing Sciences.\n\n";
        $body .= "Regarding your inquiry about: " . $submission['subject'] . "\n\n";
        $body .= "Reference ID: #" . $submission['id'] . "\n\n\n\n";
        $body .= "Best regards,\n";
        $body .= "FCT College of Nursing Sciences\n";
        $body .= $settings['phone'] ?? "+234 XXX XXX XXXX";
        $body .= "\n" . ($settings['reply_to_email'] ?? 'noreply@fctcns.edu.ng');
        
        // Build mailto URL
        $params = [
            'subject' => $subject,
            'body' => $body,
            'cc' => $replyToEmail,
            'reply-to' => $replyToEmail
        ];
        
        $queryString = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $mailtoLink = "mailto:" . rawurlencode($to) . "?" . $queryString;
        
        return [
            'link' => $mailtoLink,
            'gmail_link' => $this->generateGmailComposeUrl($submission),
            'outlook_link' => $this->generateOutlookComposeUrl($submission),
            'yahoo_link' => $this->generateYahooComposeUrl($submission),
            'proton_link' => $this->generateProtonMailComposeUrl($submission),
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'reply_to' => $replyToEmail,
            'department' => $submission['department'] ?? 'general'
        ];
    }
    
    /**
     * Send auto-response to user
     */
    public function sendAutoResponse($submissionId) {
        $submission = $this->getSubmission($submissionId);
        $settings = $this->getContactSettings();
        
        $to = $submission['email'];
        $subject = "We've received your message - FCT College of Nursing Sciences";
        
        $message = "Dear {$submission['name']},\n\n";
        $message .= "Thank you for contacting FCT College of Nursing Sciences. ";
        $message .= "We have received your inquiry and will respond within 24-48 hours.\n\n";
        $message .= "Reference: #{$submission['id']}\n";
        $message .= "Department: " . ucfirst($submission['department']) . "\n";
        $message .= "Submitted: " . date('F j, Y', strtotime($submission['created_at'])) . "\n\n";
        $message .= "Best regards,\nThe FCT CNS Team\n";
        $message .= $settings['phone'] ?? "+234 XXX XXX XXXX";
        
        // Headers
        $headers = "From: " . ($settings['reply_to_email'] ?? 'noreply@fctcns.edu.ng') . "\r\n";
        $headers .= "Reply-To: " . $this->getReplyToEmail($submission) . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send email
        mail($to, $subject, $message, $headers);
        
        return true;
    }
    
    /**
     * Get all contact submissions with optional filters
     */
    public function getAllSubmissions($status = null, $limit = 100, $offset = 0) {
        $where = '';
        $params = [];
        
        if ($status) {
            $where = "WHERE status = :status";
            $params[':status'] = $status;
        }
        
        $sql = "SELECT cs.*, u.username as responder_name 
                FROM contact_submissions cs 
                LEFT JOIN users u ON cs.responded_by = u.id 
                {$where} 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get single submission by ID
     */
    public function getSubmission($id) {
        $sql = "SELECT cs.*, u.username as responder_name 
                FROM contact_submissions cs 
                LEFT JOIN users u ON cs.responded_by = u.id 
                WHERE cs.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Update submission status and notes
     */
    public function updateSubmission($id, $data) {
        $updates = [];
        $params = [':id' => $id];
        
        if (isset($data['status'])) {
            $updates[] = "status = :status";
            $params[':status'] = $data['status'];
            
            // If marking as responded, set response info
            if ($data['status'] === 'responded') {
                $updates[] = "responded_at = NOW()";
                $updates[] = "responded_by = :responded_by";
                $params[':responded_by'] = $_SESSION['user_id'] ?? null;
            }
        }
        
        if (isset($data['admin_notes'])) {
            $updates[] = "admin_notes = :admin_notes";
            $params[':admin_notes'] = $data['admin_notes'];
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE contact_submissions SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    /**
     * Delete a submission
     */
    public function deleteSubmission($id) {
        $sql = "DELETE FROM contact_submissions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Get submission statistics
     */
    public function getStatistics() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'responded' THEN 1 ELSE 0 END) as responded,
                SUM(CASE WHEN status = 'archived' THEN 1 ELSE 0 END) as archived,
                DATE(created_at) as date,
                COUNT(*) as daily_count
                FROM contact_submissions 
                GROUP BY DATE(created_at) 
                ORDER BY date DESC 
                LIMIT 30";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get contact settings
     */
    public function getContactSettings() {
        $sql = "SELECT setting_key, setting_value FROM site_settings 
                WHERE setting_key LIKE 'contact_%' 
                OR setting_key IN ('admissions_email', 'reply_to_email', 'support_email', 'billing_email', 'academic_email', 'map_latitude', 'map_longitude')";
        
        $stmt = $this->db->query($sql);
        
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        // Default values if not set
        return [
            'phone' => $settings['contact_phone'] ?? '+234 XXX XXX XXXX',
            'email' => $settings['contact_email'] ?? 'info@fctcns.edu.ng',
            'reply_to_email' => $settings['reply_to_email'] ?? 'noreply@fctcns.edu.ng',
            'support_email' => $settings['support_email'] ?? 'support@fctcns.edu.ng',
            'billing_email' => $settings['billing_email'] ?? 'billing@fctcns.edu.ng',
            'admissions_email' => $settings['admissions_email'] ?? 'admissions@fctcns.edu.ng',
            'academic_email' => $settings['academic_email'] ?? 'academic@fctcns.edu.ng',
            'address' => $settings['contact_address'] ?? 'FCT College of Nursing Sciences, Abuja, Nigeria',
            'working_hours' => $settings['contact_hours'] ?? 'Monday - Friday: 8:00 AM - 5:00 PM',
            'emergency_contact' => $settings['contact_emergency'] ?? '+234 XXX XXX XXXX',
            'map_latitude' => $settings['map_latitude'] ?? '9.0765',
            'map_longitude' => $settings['map_longitude'] ?? '7.3986'
        ];
    }
    
    /**
     * Save contact settings
     */
    public function saveContactSettings($settings) {
        try {
            $this->db->beginTransaction();
            
            foreach ($settings as $key => $value) {
                // Determine the setting key
                $settingKey = 'contact_' . $key;
                
                // Special handling for certain keys
                $specialKeys = ['admissions_email', 'reply_to_email', 'support_email', 
                              'billing_email', 'academic_email', 'map_latitude', 'map_longitude'];
                
                if (in_array($key, $specialKeys)) {
                    $settingKey = $key;
                }
                
                $sql = "INSERT INTO site_settings (setting_key, setting_value) 
                        VALUES (:key, :value) 
                        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':key' => $settingKey,
                    ':value' => $value
                ]);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Contact settings save error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Search submissions
     */
    public function searchSubmissions($searchTerm) {
        $sql = "SELECT cs.*, u.username as responder_name 
                FROM contact_submissions cs 
                LEFT JOIN users u ON cs.responded_by = u.id 
                WHERE cs.name LIKE :search 
                   OR cs.email LIKE :search 
                   OR cs.subject LIKE :search 
                   OR cs.message LIKE :search 
                ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':search' => "%{$searchTerm}%"]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get submissions by department
     */
    public function getSubmissionsByDepartment($department, $limit = 50) {
        $sql = "SELECT cs.*, u.username as responder_name 
                FROM contact_submissions cs 
                LEFT JOIN users u ON cs.responded_by = u.id 
                WHERE cs.department = :department 
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':department', $department);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Bulk update status
     */
    public function bulkUpdateStatus($ids, $status) {
        if (empty($ids)) {
            return false;
        }
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "UPDATE contact_submissions SET 
                status = ?, 
                responded_at = CASE WHEN ? = 'responded' THEN NOW() ELSE responded_at END,
                responded_by = CASE WHEN ? = 'responded' THEN ? ELSE responded_by END
                WHERE id IN ({$placeholders})";
        
        $params = array_merge([$status, $status, $status, $_SESSION['user_id'] ?? null], $ids);
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}