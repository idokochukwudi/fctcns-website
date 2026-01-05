<?php
/**
 * Admission List Model with Configurable Update Rules
 */

class AdmissionListModel {
    private $db;
    
    // Update configuration
    private $updateConfig = [
        'allow_reverse_updates' => false, // Set to true to allow Accepted → Approved
        'allowed_transitions' => [
            'Approved' => ['Accepted'], // Approved can only become Accepted
            'Accepted' => [] // Accepted cannot change (unless allow_reverse_updates = true)
        ]
    ];
    
    public function __construct() {
        $databaseFile = APP_PATH . '/config/database.php';
        if (!file_exists($databaseFile)) {
            die("Database class not found at: " . $databaseFile);
        }
        
        require_once $databaseFile;
        $this->db = Database::getInstance()->getConnection();
        
        // Load update configuration from environment if exists
        $this->loadUpdateConfig();
    }
    
    /**
     * Load update configuration
     */
    private function loadUpdateConfig() {
        // Check for environment configuration
        if (defined('ALLOW_REVERSE_ADMISSION_UPDATES')) {
            $this->updateConfig['allow_reverse_updates'] = ALLOW_REVERSE_ADMISSION_UPDATES;
        }
        
        // If reverse updates allowed, modify transitions
        if ($this->updateConfig['allow_reverse_updates']) {
            $this->updateConfig['allowed_transitions']['Accepted'] = ['Approved'];
        }
    }
    
    /**
     * Get update configuration
     */
    public function getUpdateConfig() {
        return $this->updateConfig;
    }
    
    /**
     * Get admission records with pagination
     */
    public function getAllAdmissions($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM admission_list_2025_2026 
                ORDER BY serial_number ASC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get total number of records
     */
    public function getTotalRecords() {
        $sql = "SELECT COUNT(*) as total FROM admission_list_2025_2026";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    /**
     * Get admission statistics
     */
    public function getStatistics() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN admission_status = 'Accepted' THEN 1 ELSE 0 END) as accepted_count,
                SUM(CASE WHEN admission_status = 'Approved' THEN 1 ELSE 0 END) as approved_count
                FROM admission_list_2025_2026";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Search by registration number or name
     */
    public function search($keyword) {
        $searchTerm = '%' . $keyword . '%';
        
        $sql = "SELECT * FROM admission_list_2025_2026 
                WHERE registration_number LIKE ? 
                OR candidate_name LIKE ? 
                ORDER BY serial_number ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get single admission by registration number
     */
    public function getByRegistrationNumber($regNumber) {
        $sql = "SELECT * FROM admission_list_2025_2026 
                WHERE registration_number = :regNumber 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':regNumber', $regNumber);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Bulk update admission statuses from CSV
     * Follows configurable update rules
     */
    public function bulkUpdateFromCSV($csvFilePath) {
        if (!file_exists($csvFilePath)) {
            return [
                'success' => false, 
                'message' => 'CSV file not found',
                'config' => $this->updateConfig
            ];
        }
        
        $updated = 0;
        $unchanged = 0;
        $rejected = 0;
        $errors = [];
        $details = [];
        
        $handle = fopen($csvFilePath, 'r');
        if (!$handle) {
            return [
                'success' => false, 
                'message' => 'Cannot open CSV file',
                'config' => $this->updateConfig
            ];
        }
        
        // Read CSV header
        $headers = fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) >= 2) {
                $regNumber = trim($data[0]);
                $newStatus = trim($data[1]);
                
                // Validate status
                if (!in_array($newStatus, ['Accepted', 'Approved'])) {
                    $errors[] = "Invalid status '$newStatus' for $regNumber";
                    $rejected++;
                    continue;
                }
                
                // Get current status
                $current = $this->getByRegistrationNumber($regNumber);
                
                if (!$current) {
                    $errors[] = "Candidate not found: $regNumber";
                    $rejected++;
                    continue;
                }
                
                $currentStatus = $current['admission_status'];
                
                // Check if update is allowed based on configuration
                $isAllowed = $this->isUpdateAllowed($currentStatus, $newStatus);
                
                if ($isAllowed['allowed']) {
                    // Perform the update
                    $sql = "UPDATE admission_list_2025_2026 
                            SET admission_status = :newStatus 
                            WHERE registration_number = :regNumber";
                    
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':newStatus', $newStatus);
                    $stmt->bindParam(':regNumber', $regNumber);
                    
                    if ($stmt->execute()) {
                        $updated++;
                        $details[] = [
                            'registration' => $regNumber,
                            'from' => $currentStatus,
                            'to' => $newStatus,
                            'action' => 'updated'
                        ];
                    } else {
                        $errors[] = "Failed to update $regNumber";
                        $rejected++;
                    }
                } else {
                    if ($currentStatus === $newStatus) {
                        $unchanged++;
                        $details[] = [
                            'registration' => $regNumber,
                            'from' => $currentStatus,
                            'to' => $newStatus,
                            'action' => 'unchanged',
                            'reason' => 'Same status'
                        ];
                    } else {
                        $rejected++;
                        $details[] = [
                            'registration' => $regNumber,
                            'from' => $currentStatus,
                            'to' => $newStatus,
                            'action' => 'rejected',
                            'reason' => $isAllowed['reason']
                        ];
                    }
                }
            }
        }
        
        fclose($handle);
        
        return [
            'success' => true,
            'updated' => $updated,
            'unchanged' => $unchanged,
            'rejected' => $rejected,
            'errors' => $errors,
            'details' => $details,
            'config' => $this->updateConfig
        ];
    }
    
    /**
     * Check if a status update is allowed
     */
    private function isUpdateAllowed($currentStatus, $newStatus) {
        // Same status - no update needed
        if ($currentStatus === $newStatus) {
            return [
                'allowed' => false,
                'reason' => 'Status unchanged'
            ];
        }
        
        // Check allowed transitions from configuration
        if (isset($this->updateConfig['allowed_transitions'][$currentStatus])) {
            if (in_array($newStatus, $this->updateConfig['allowed_transitions'][$currentStatus])) {
                return [
                    'allowed' => true,
                    'reason' => 'Allowed transition'
                ];
            }
        }
        
        // If reverse updates are allowed globally
        if ($this->updateConfig['allow_reverse_updates'] && 
            $currentStatus === 'Accepted' && $newStatus === 'Approved') {
            return [
                'allowed' => true,
                'reason' => 'Reverse update allowed'
            ];
        }
        
        return [
            'allowed' => false,
            'reason' => 'Transition not allowed by configuration'
        ];
    }
    
    /**
     * Manual status correction (admin override)
     */
    public function manualStatusCorrection($regNumber, $newStatus, $adminName = '', $reason = '') {
        try {
            // First verify the candidate exists
            $current = $this->getByRegistrationNumber($regNumber);
            
            if (!$current) {
                return [
                    'success' => false,
                    'message' => "Candidate not found: $regNumber"
                ];
            }
            
            $currentStatus = $current['admission_status'];
            
            // Manual override - bypass normal rules
            $sql = "UPDATE admission_list_2025_2026 
                    SET admission_status = :newStatus 
                    WHERE registration_number = :regNumber";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':newStatus', $newStatus);
            $stmt->bindParam(':regNumber', $regNumber);
            
            if ($stmt->execute()) {
                // Log the manual correction
                $logMessage = sprintf(
                    "Manual status correction: %s → %s. Admin: %s. Reason: %s",
                    $currentStatus,
                    $newStatus,
                    $adminName,
                    $reason
                );
                
                error_log("ADMIN_OVERRIDE: " . $logMessage);
                
                // You could also save to an audit table here
                // $this->logAdminAction($regNumber, $currentStatus, $newStatus, $adminName, $reason);
                
                return [
                    'success' => true,
                    'message' => "Status updated from $currentStatus to $newStatus",
                    'previous_status' => $currentStatus,
                    'new_status' => $newStatus
                ];
            } else {
                return [
                    'success' => false,
                    'message' => "Database update failed"
                ];
            }
            
        } catch (Exception $e) {
            error_log("Manual correction error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "Error: " . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get update statistics by type
     */
    public function getUpdateStatistics() {
        $sql = "SELECT 
                admission_status,
                COUNT(*) as count,
                DATE(created_at) as date_created
                FROM admission_list_2025_2026 
                GROUP BY admission_status, DATE(created_at)
                ORDER BY date_created DESC, admission_status";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>