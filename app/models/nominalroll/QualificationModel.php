<?php
// app/models/nominalroll/QualificationModel.php

/**
 * Qualification Model for Nominal Roll
 * Handles qualification and certification data
 */
class QualificationModel {
    
    private $db;
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    /**
     * Get distinct highest qualification values
     */
    public function getHighestQualificationOptions() {
        try {
            $stmt = $this->db->prepare("
                SELECT DISTINCT highest_qualification 
                FROM nominal_roll_employees 
                WHERE highest_qualification IS NOT NULL 
                AND highest_qualification != '' 
                ORDER BY highest_qualification
            ");
            $stmt->execute();
            
            $options = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options[] = $row['highest_qualification'];
            }
            
            return $options;
        } catch (Exception $e) {
            error_log("QualificationModel Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get distinct professional certification values
     */
    public function getProfessionalCertificationOptions() {
        try {
            $stmt = $this->db->prepare("
                SELECT DISTINCT professional_certifications 
                FROM nominal_roll_employees 
                WHERE professional_certifications IS NOT NULL 
                AND professional_certifications != '' 
                ORDER BY professional_certifications
            ");
            $stmt->execute();
            
            $options = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Handle comma-separated certifications
                $certs = explode(',', $row['professional_certifications']);
                foreach ($certs as $cert) {
                    $cert = trim($cert);
                    if ($cert && !in_array($cert, $options)) {
                        $options[] = $cert;
                    }
                }
            }
            
            sort($options);
            return $options;
        } catch (Exception $e) {
            error_log("QualificationModel Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Parse additional qualifications from JSON
     */
    public function parseAdditionalQualifications($jsonData) {
        try {
            if (empty($jsonData) || $jsonData === 'null') {
                return [];
            }
            
            $data = json_decode($jsonData, true);
            
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                // Try to parse as comma-separated
                if (strpos($jsonData, ',') !== false) {
                    $quals = explode(',', $jsonData);
                    $result = [];
                    foreach ($quals as $qual) {
                        $result[] = ['qualification' => trim($qual), 'year' => ''];
                    }
                    return $result;
                }
                return [['qualification' => $jsonData, 'year' => '']];
            }
            
            return $data;
        } catch (Exception $e) {
            error_log("Parse qualifications error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search employees by qualification
     */
    public function searchByQualification($qualificationType, $qualificationValue) {
        try {
            $sql = "SELECT * FROM nominal_roll_employees WHERE 1=1";
            $params = [];
            
            switch ($qualificationType) {
                case 'highest_qualification':
                    $sql .= " AND highest_qualification = ?";
                    $params[] = $qualificationValue;
                    break;
                    
                case 'professional_certification':
                    $sql .= " AND professional_certifications LIKE ?";
                    $params[] = "%{$qualificationValue}%";
                    break;
                    
                case 'additional_qualification':
                    $sql .= " AND additional_qualifications LIKE ?";
                    $params[] = "%{$qualificationValue}%";
                    break;
            }
            
            $sql .= " ORDER BY surname, first_name";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Qualification search error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get qualification statistics
     */
    public function getQualificationStats() {
        try {
            $stats = [];
            
            // Highest qualification stats
            $stmt = $this->db->query("
                SELECT highest_qualification, COUNT(*) as count 
                FROM nominal_roll_employees 
                WHERE highest_qualification IS NOT NULL AND highest_qualification != ''
                GROUP BY highest_qualification 
                ORDER BY count DESC
            ");
            $stats['highest_qualification'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Professional certification stats
            $stmt = $this->db->query("
                SELECT 
                    CASE 
                        WHEN professional_certifications LIKE '%TRCN%' THEN 'TRCN'
                        WHEN professional_certifications LIKE '%RN%' THEN 'RN'
                        WHEN professional_certifications LIKE '%RM%' THEN 'RM'
                        WHEN professional_certifications LIKE '%RPHN%' THEN 'RPHN'
                        WHEN professional_certifications LIKE '%NMCN%' THEN 'NMCN'
                        WHEN professional_certifications IS NOT NULL AND professional_certifications != '' THEN 'Other'
                        ELSE 'None'
                    END as certification_type,
                    COUNT(*) as count
                FROM nominal_roll_employees 
                GROUP BY certification_type 
                ORDER BY count DESC
            ");
            $stats['professional_certifications'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
        } catch (Exception $e) {
            error_log("Qualification stats error: " . $e->getMessage());
            return [];
        }
    }
}