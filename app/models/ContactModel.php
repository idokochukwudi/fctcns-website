<?php
/**
 * Contact Model
 * Handles contact form data operations and CRUD
 * 
 * @package FCTCNS
 * @version 2.0
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
     * Auto-responder Feature
     * Purpose: Send automatic acknowledgment emails to users.
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
        $message .= "Submitted: " . date('F j, Y', strtotime($submission['created_at'])) . "\n\n";
        $message .= "Best regards,\nThe FCT CNS Team";
        
        // Additional headers for better email formatting
        $headers = "From: noreply@fctcns.edu.ng\r\n";
        $headers .= "Reply-To: " . $settings['email'] . "\r\n";
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
     * Get contact settings (store in site_settings table)
     */
    public function getContactSettings() {
        $sql = "SELECT setting_key, setting_value FROM site_settings WHERE setting_key LIKE 'contact_%' OR setting_key = 'admissions_email' OR setting_key LIKE 'map_%'";
        $stmt = $this->db->query($sql);
        
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        return [
            'phone' => $settings['contact_phone'] ?? '+234 XXX XXX XXXX',
            'email' => $settings['contact_email'] ?? 'info@fctcns.edu.ng',
            'address' => $settings['contact_address'] ?? 'FCT College of Nursing Sciences, Abuja, Nigeria',
            'working_hours' => $settings['contact_hours'] ?? 'Monday - Friday: 8:00 AM - 5:00 PM',
            'emergency_contact' => $settings['contact_emergency'] ?? '+234 XXX XXX XXXX',
            'admissions_email' => $settings['admissions_email'] ?? 'admissions@fctcns.edu.ng',
            'map_latitude' => $settings['map_latitude'] ?? '9.0765',
            'map_longitude' => $settings['map_longitude'] ?? '7.3986'
        ];
    }
    
    /**
     * Save contact settings
     */
    public function saveContactSettings($settings) {
        try {
            error_log("ContactModel::saveContactSettings called with: " . print_r($settings, true));
            
            foreach ($settings as $key => $value) {
                // Determine the setting key
                $settingKey = 'contact_' . $key;
                
                // Special handling for certain keys
                if ($key === 'admissions_email' || $key === 'map_latitude' || $key === 'map_longitude') {
                    $settingKey = $key; // Keep as is for these keys
                }
                
                error_log("Saving setting: $settingKey = '$value'");
                
                $sql = "INSERT INTO site_settings (setting_key, setting_value) 
                        VALUES (?, ?) 
                        ON DUPLICATE KEY UPDATE setting_value = ?";
                
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([$settingKey, $value, $value]);
                
                if (!$result) {
                    $errorInfo = $stmt->errorInfo();
                    error_log("Failed to save setting '$settingKey': " . print_r($errorInfo, true));
                    throw new Exception("Database error for setting '$settingKey': " . $errorInfo[2]);
                }
                
                error_log("Successfully saved setting: $settingKey");
            }
            
            error_log("All contact settings saved successfully");
            return true;
            
        } catch (Exception $e) {
            error_log("Contact settings save error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
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
}
?>