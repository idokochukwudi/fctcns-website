<?php
/**
 * Terms and Conditions Model
 * 
 * Handles terms and conditions data operations
 * 
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

class TermsModel extends BaseModel {
    
    protected $table = 'terms_conditions';
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get active terms
     */
    public function getActive() {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} 
             WHERE is_active = 1 
             ORDER BY effective_date DESC, version DESC 
             LIMIT 1"
        );
    }
    
    /**
     * Get terms by version
     */
    public function getByVersion($version) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE version = :version ORDER BY effective_date DESC LIMIT 1",
            ['version' => $version]
        );
    }
    
    /**
     * Get all terms versions
     */
    public function getAllVersions() {
        return $this->fetchAll(
            "SELECT id, title, version, is_active, effective_date, created_at, updated_at 
             FROM {$this->table} 
             ORDER BY effective_date DESC, version DESC"
        );
    }
    
    /**
     * Create new terms version
     */
    public function createVersion($title, $content, $effectiveDate, $updatedBy, $version = null) {
        // Deactivate current active terms
        $this->deactivateAll();
        
        // Generate version number if not provided
        if (!$version) {
            $lastVersion = $this->fetchColumn(
                "SELECT MAX(version) FROM {$this->table}"
            );
            
            if ($lastVersion) {
                $parts = explode('.', $lastVersion);
                $version = $parts[0] . '.' . (intval($parts[1] ?? 0) + 1);
            } else {
                $version = '1.0';
            }
        }
        
        $data = [
            'title' => $title,
            'content' => $content,
            'version' => $version,
            'is_active' => 1,
            'effective_date' => $effectiveDate,
            'updated_by' => $updatedBy,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($data);
    }
    
    /**
     * Update terms
     */
    public function updateTerms($id, $data, $updatedBy) {
        $data['updated_by'] = $updatedBy;
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->update($data, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Deactivate all terms
     */
    public function deactivateAll() {
        return $this->update(
            ['is_active' => 0],
            'is_active = 1'
        );
    }
    
    /**
     * Activate a specific terms version
     */
    public function activate($id) {
        $this->deactivateAll();
        
        return $this->update(
            ['is_active' => 1],
            'id = :id',
            ['id' => $id]
        );
    }
    
    /**
     * Check if terms need to be accepted
     */
    public function needsAcceptance($lastAcceptedVersion = null, $lastAcceptedDate = null) {
        $active = $this->getActive();
        
        if (!$active) {
            return true;
        }
        
        if (!$lastAcceptedVersion || !$lastAcceptedDate) {
            return true;
        }
        
        // Check if version is different
        if ($active['version'] !== $lastAcceptedVersion) {
            return true;
        }
        
        // Check if effective date is after last acceptance
        if (strtotime($active['effective_date']) > strtotime($lastAcceptedDate)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get terms for acceptance display
     */
    public function getForAcceptance() {
        $terms = $this->getActive();
        
        if (!$terms) {
            return null;
        }
        
        return [
            'id' => $terms['id'],
            'title' => $terms['title'],
            'content' => $terms['content'],
            'version' => $terms['version'],
            'effective_date' => $terms['effective_date']
        ];
    }
    
    /**
     * Get terms by ID
     */
    public function getById($id) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }
    
    /**
     * Delete terms version
     */
    public function deleteVersion($id) {
        $terms = $this->getById($id);
        
        if (!$terms) {
            return false;
        }
        
        // Don't allow deletion of active terms
        if ($terms['is_active'] == 1) {
            return false;
        }
        
        return $this->delete('id = :id', ['id' => $id]);
    }
    
    /**
     * Get terms history for applicant
     */
    public function getApplicantHistory($applicantId) {
        return $this->fetchAll(
            "SELECT ta.*, tc.title, tc.version, tc.effective_date
             FROM terms_acceptance ta
             JOIN {$this->table} tc ON ta.terms_id = tc.id
             WHERE ta.applicant_id = :applicant_id
             ORDER BY ta.accepted_at DESC",
            ['applicant_id' => $applicantId]
        );
    }
    
    /**
     * Record terms acceptance by applicant
     */
    public function recordAcceptance($applicantId, $termsId, $ipAddress = null) {
        $data = [
            'applicant_id' => $applicantId,
            'terms_id' => $termsId,
            'accepted_at' => date('Y-m-d H:i:s'),
            'ip_address' => $ipAddress ?? $_SERVER['REMOTE_ADDR'] ?? null
        ];
        
        return $this->db->insert('terms_acceptance', $data);
    }
    
    /**
     * Get latest acceptance for applicant
     */
    public function getLatestAcceptance($applicantId) {
        return $this->fetchOne(
            "SELECT ta.*, tc.title, tc.version, tc.effective_date, tc.content
             FROM terms_acceptance ta
             JOIN {$this->table} tc ON ta.terms_id = tc.id
             WHERE ta.applicant_id = :applicant_id
             ORDER BY ta.accepted_at DESC
             LIMIT 1",
            ['applicant_id' => $applicantId]
        );
    }
}