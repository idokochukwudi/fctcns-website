<?php
/**
 * Admission List Model
 */

class AdmissionListModel {
    private $db;
    
    public function __construct() {
        // Load the Database class from config folder
        $databaseFile = APP_PATH . '/config/database.php';
        if (!file_exists($databaseFile)) {
            die("Database class not found at: " . $databaseFile);
        }
        
        require_once $databaseFile;
        
        // Now get the database connection
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllAdmissions($page = 1, $perPage = 50) {
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
    
    public function getTotalRecords() {
        $sql = "SELECT COUNT(*) as total FROM admission_list_2025_2026";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
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
    
    public function search($keyword) {
        $sql = "SELECT * FROM admission_list_2025_2026 
                WHERE registration_number LIKE :keyword 
                OR candidate_name LIKE :keyword 
                ORDER BY serial_number ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getByRegistrationNumber($regNumber) {
        $sql = "SELECT * FROM admission_list_2025_2026 
                WHERE registration_number = :regNumber 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':regNumber', $regNumber);
        $stmt->execute();
        
        return $stmt->fetch();
    }
}
?>