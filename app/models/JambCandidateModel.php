<?php
/**
 * JAMB Candidate Model
 * 
 * Handles JAMB candidate data operations
 * 
 * @package FCT_CNS
 */

require_once MODELS_PATH . '/BaseModel.php';

class JambCandidateModel extends BaseModel {
    
    protected $table = 'jamb_candidates';
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Find candidate by JAMB number
     * 
     * @param string $jambNumber JAMB registration number
     * @return array|false Candidate data or false
     */
    public function findByJambNumber($jambNumber) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE jamb_number = :jamb_number",
            ['jamb_number' => $jambNumber]
        );
    }
    
    /**
     * Get candidates by program applied
     * 
     * @param string $program Program name
     * @return array Candidates
     */
    public function getByProgram($program) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE program_applied = :program ORDER BY aggregate_score DESC",
            ['program' => $program]
        );
    }
    
    /**
     * Get candidates by score range
     * 
     * @param int $min Minimum score
     * @param int $max Maximum score
     * @return array Candidates
     */
    public function getByScoreRange($min, $max) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE aggregate_score BETWEEN :min AND :max ORDER BY aggregate_score DESC",
            ['min' => $min, 'max' => $max]
        );
    }
    
    /**
     * Get candidates by state of origin
     * 
     * @param string $state State name
     * @return array Candidates
     */
    public function getByState($state) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE state_of_origin = :state ORDER BY aggregate_score DESC",
            ['state' => $state]
        );
    }
    
    /**
     * Get used candidates (already registered)
     * 
     * @return array Used candidates
     */
    public function getUsedCandidates() {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE is_used = 1 ORDER BY used_at DESC"
        );
    }
    
    /**
     * Get available candidates (not used)
     * 
     * @return array Available candidates
     */
    public function getAvailableCandidates() {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE is_used = 0 ORDER BY aggregate_score DESC"
        );
    }
    
    /**
     * Mark candidate as used
     * 
     * @param int $candidateId Candidate ID
     * @param int $applicationId Application ID that used this candidate
     * @return bool Success
     */
    public function markAsUsed($candidateId, $applicationId) {
        return $this->update(
            [
                'is_used' => 1,
                'used_at' => date('Y-m-d H:i:s'),
                'used_by_application_id' => $applicationId
            ],
            'id = :id',
            ['id' => $candidateId]
        );
    }
    
    /**
     * Import multiple candidates from array
     * 
     * @param array $candidates Array of candidate data
     * @return array Import results [success, failed, errors]
     */
    public function importCandidates($candidates) {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        foreach ($candidates as $index => $candidate) {
            try {
                // Validate required fields
                if (empty($candidate['jamb_number'])) {
                    throw new Exception("Row " . ($index + 1) . ": JAMB number is required");
                }
                
                // Check if already exists
                $existing = $this->findByJambNumber($candidate['jamb_number']);
                if ($existing) {
                    $results['failed']++;
                    $results['errors'][] = "Row " . ($index + 1) . ": JAMB number {$candidate['jamb_number']} already exists";
                    continue;
                }
                
                // Prepare data
                $data = [
                    'jamb_number' => $candidate['jamb_number'],
                    'first_name' => $candidate['first_name'] ?? '',
                    'last_name' => $candidate['last_name'] ?? '',
                    'other_names' => $candidate['other_names'] ?? null,
                    'gender' => $candidate['gender'] ?? 'M',
                    'state_of_origin' => $candidate['state_of_origin'] ?? '',
                    'lga' => $candidate['lga'] ?? '',
                    'aggregate_score' => intval($candidate['aggregate_score'] ?? 0),
                    'program_applied' => $candidate['program_applied'] ?? '',
                    'institution' => $candidate['institution'] ?? 'FCT College of Nursing Sciences',
                    'email' => $candidate['email'] ?? null,
                    'phone' => $candidate['phone'] ?? null,
                    'date_of_birth' => $candidate['date_of_birth'] ?? null,
                    'exam_year' => $candidate['exam_year'] ?? date('Y'),
                    'is_imported' => 1,
                    'is_used' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $this->insert($data);
                $results['success']++;
                
            } catch (Exception $e) {
                $results['failed']++;
                $results['errors'][] = $e->getMessage();
            }
        }
        
        return $results;
    }
    
    /**
     * Get import statistics
     * 
     * @return array Statistics
     */
    public function getStats() {
        $stats = $this->fetchOne("
            SELECT 
                COUNT(*) as total,
                SUM(is_used) as used,
                SUM(is_imported) as imported,
                SUM(CASE WHEN is_used = 0 THEN 1 ELSE 0 END) as available,
                AVG(aggregate_score) as avg_score,
                MIN(aggregate_score) as min_score,
                MAX(aggregate_score) as max_score
            FROM {$this->table}
        ");
        
        // Get counts by program
        $byProgram = $this->fetchAll("
            SELECT program_applied, COUNT(*) as count 
            FROM {$this->table} 
            GROUP BY program_applied 
            ORDER BY count DESC
        ");
        
        $stats['by_program'] = $byProgram;
        
        // Get counts by state
        $byState = $this->fetchAll("
            SELECT state_of_origin, COUNT(*) as count 
            FROM {$this->table} 
            GROUP BY state_of_origin 
            ORDER BY count DESC 
            LIMIT 10
        ");
        
        $stats['by_state'] = $byState;
        
        return $stats;
    }
    
    /**
     * Search candidates
     * 
     * @param string $query Search query
     * @return array Matching candidates
     */
    public function search($query) {
        $search = '%' . $query . '%';
        
        return $this->fetchAll("
            SELECT * FROM {$this->table} 
            WHERE jamb_number LIKE :search 
               OR first_name LIKE :search 
               OR last_name LIKE :search 
               OR email LIKE :search 
               OR phone LIKE :search
            ORDER BY 
                CASE 
                    WHEN jamb_number LIKE :search_exact THEN 1
                    WHEN jamb_number LIKE :search_start THEN 2
                    ELSE 3
                END,
                aggregate_score DESC
            LIMIT 50",
            [
                'search' => $search,
                'search_exact' => $query,
                'search_start' => $query . '%'
            ]
        );
    }
    
    /**
     * Delete imported candidates (for cleanup)
     * 
     * @param bool $onlyUnused Only delete unused candidates
     * @return int Number deleted
     */
    public function deleteImported($onlyUnused = true) {
        $where = "is_imported = 1";
        if ($onlyUnused) {
            $where .= " AND is_used = 0";
        }
        
        return $this->delete($where);
    }
    
    /**
     * Get candidates needing verification (low scores, etc.)
     * 
     * @param int $threshold Score threshold
     * @return array Candidates below threshold
     */
    public function getCandidatesBelowThreshold($threshold = 170) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE aggregate_score < :threshold ORDER BY aggregate_score",
            ['threshold' => $threshold]
        );
    }
    
    /**
     * Bulk update candidates (for corrections)
     * 
     * @param array $updates Array of [id => data] to update
     * @return array Results
     */
    public function bulkUpdate($updates) {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        foreach ($updates as $id => $data) {
            try {
                $result = $this->update($data, 'id = :id', ['id' => $id]);
                if ($result) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                }
            } catch (Exception $e) {
                $results['failed']++;
                $results['errors'][] = "ID {$id}: " . $e->getMessage();
            }
        }
        
        return $results;
    }
    
    /**
     * Export candidates to array
     * 
     * @param array $filters Filters to apply
     * @return array Candidate data
     */
    public function export($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (!empty($filters['used'])) {
            if ($filters['used'] === 'used') {
                $sql .= " AND is_used = 1";
            } elseif ($filters['used'] === 'unused') {
                $sql .= " AND is_used = 0";
            }
        }
        
        if (!empty($filters['program'])) {
            $sql .= " AND program_applied = :program";
            $params['program'] = $filters['program'];
        }
        
        if (!empty($filters['min_score'])) {
            $sql .= " AND aggregate_score >= :min_score";
            $params['min_score'] = $filters['min_score'];
        }
        
        if (!empty($filters['max_score'])) {
            $sql .= " AND aggregate_score <= :max_score";
            $params['max_score'] = $filters['max_score'];
        }
        
        $sql .= " ORDER BY aggregate_score DESC";
        
        return $this->fetchAll($sql, $params);
    }
}