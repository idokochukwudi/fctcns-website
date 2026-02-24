<?php
/**
 * O'Level Result Model
 * 
 * Handles O'Level result data operations
 * FIXED: Added getCreditCheckSummary method for consistent validation across controller and view
 * FIXED: Added proper sequential indexing in getByApplicationId method
 * 
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

class OlevelResultModel extends BaseModel {
    
    protected $table = 'olevel_results';
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get results by application ID with proper indexing
     * FIXED: Ensures sequential indexing (0,1,2...) for use in frontend
     * 
     * @param int $applicationId
     * @return array
     */
    public function getByApplicationId($applicationId) {
        $sql = "SELECT * FROM {$this->table} WHERE application_id = :application_id ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['application_id' => $applicationId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ensure sequential indexing (0,1,2...) to prevent gaps
        return array_values($results);
    }
    
    /**
     * Save O'Level results
     */
    public function saveResults($applicationId, $results) {
        try {
            $this->beginTransaction();
            
            // Delete existing results for this application
            $this->delete('application_id = :application_id', ['application_id' => $applicationId]);
            
            $savedCount = 0;
            
            foreach ($results as $result) {
                // Validate required grades
                if (empty($result['english_grade']) || empty($result['mathematics_grade']) ||
                    empty($result['biology_grade']) || empty($result['chemistry_grade']) ||
                    empty($result['physics_grade'])) {
                    continue;
                }
                
                $data = [
                    'application_id' => $applicationId,
                    'exam_type' => $result['exam_type'],
                    'exam_year' => $result['exam_year'],
                    'exam_number' => $result['exam_number'] ?? null,
                    'sitting' => $result['sitting'] ?? '1st',
                    'english_grade' => strtoupper($result['english_grade']),
                    'mathematics_grade' => strtoupper($result['mathematics_grade']),
                    'biology_grade' => strtoupper($result['biology_grade']),
                    'chemistry_grade' => strtoupper($result['chemistry_grade']),
                    'physics_grade' => strtoupper($result['physics_grade']),
                    'other_subjects' => !empty($result['other_subjects']) ? json_encode($result['other_subjects']) : null,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                if ($this->insert($data)) {
                    $savedCount++;
                }
            }
            
            $this->commit();
            
            return $savedCount;
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("OlevelResultModel::saveResults - Error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get credit check summary for display and gating
     * Returns structured data for use in controller and view
     * 
     * @param int $applicationId
     * @return array
     */
    public function getCreditCheckSummary($applicationId) {
        $results = $this->getByApplicationId($applicationId);
        
        $creditGrades    = ['A1', 'B2', 'B3', 'C4', 'C5', 'C6'];
        $gradeOrder      = ['A1','B2','B3','C4','C5','C6','D7','E8','F9'];
        $requiredSubjects = [
            'english'     => 'English Language',
            'mathematics' => 'Mathematics',
            'biology'     => 'Biology',
            'chemistry'   => 'Chemistry',
            'physics'     => 'Physics',
        ];

        // Find best grade per subject across all sittings
        $bestGrades = [];
        foreach ($results as $sitting) {
            foreach ($requiredSubjects as $key => $label) {
                $gradeKey = $key . '_grade';
                if (!empty($sitting[$gradeKey])) {
                    $grade = strtoupper($sitting[$gradeKey]);
                    if (!isset($bestGrades[$key])) {
                        $bestGrades[$key] = $grade;
                    } else {
                        $currentRank = array_search($bestGrades[$key], $gradeOrder);
                        $newRank     = array_search($grade, $gradeOrder);
                        if ($newRank !== false && ($currentRank === false || $newRank < $currentRank)) {
                            $bestGrades[$key] = $grade;
                        }
                    }
                }
            }
        }

        // Check which subjects have credit passes
        $creditsAchieved = 0;
        $missingSubjects  = [];
        $failedSubjects   = [];
        $subjectStatus    = [];

        foreach ($requiredSubjects as $key => $label) {
            if (!isset($bestGrades[$key])) {
                $missingSubjects[]      = $label;
                $subjectStatus[$key]    = ['label' => $label, 'grade' => null, 'passed' => false, 'missing' => true];
            } elseif (in_array($bestGrades[$key], $creditGrades)) {
                $creditsAchieved++;
                $subjectStatus[$key] = ['label' => $label, 'grade' => $bestGrades[$key], 'passed' => true, 'missing' => false];
            } else {
                $failedSubjects[]    = $label . ' (' . $bestGrades[$key] . ')';
                $subjectStatus[$key] = ['label' => $label, 'grade' => $bestGrades[$key], 'passed' => false, 'missing' => false];
            }
        }

        $meetsRequirement = ($creditsAchieved >= 5);

        // Build human-readable message
        if ($meetsRequirement) {
            $message = 'All 5 required subjects have credit passes. You may proceed to payment.';
        } elseif (!empty($missingSubjects) && !empty($failedSubjects)) {
            $message = 'Missing grades for: ' . implode(', ', $missingSubjects) . '. '
                     . 'Below credit in: ' . implode(', ', $failedSubjects) . '.';
        } elseif (!empty($missingSubjects)) {
            $message = 'No grade entered for: ' . implode(', ', $missingSubjects) . '.';
        } else {
            $message = 'Credit passes required in: ' . implode(', ', $failedSubjects) . '.';
        }

        return [
            'meets_requirement' => $meetsRequirement,
            'credits_achieved'  => $creditsAchieved,
            'credits_required'  => 5,
            'best_grades'       => $bestGrades,
            'subject_status'    => $subjectStatus,
            'missing_subjects'  => $missingSubjects,
            'failed_subjects'   => $failedSubjects,
            'total_sittings'    => count($results),
            'message'           => $message,
        ];
    }
    
    /**
     * Validate O'Level results meet requirements
     * FIXED: Now properly checks across multiple sittings and accepts credit grades
     */
    public function validateRequirements($applicationId) {
        $results = $this->getByApplicationId($applicationId);
        
        if (empty($results)) {
            return ['valid' => false, 'message' => 'No O\'Level results provided'];
        }
        
        require_once MODELS_PATH . '/application/SettingsModel.php';
        $settings = new SettingsModel();
        $maxSittings = intval($settings->get('max_olevel_sittings', 2));
        
        if (count($results) > $maxSittings) {
            return ['valid' => false, 'message' => "Maximum of {$maxSittings} sittings allowed"];
        }
        
        // Define credit grades
        $creditGrades = ['A1', 'B2', 'B3', 'C4', 'C5', 'C6'];
        
        // Required subjects
        $requiredSubjects = [
            'english_grade' => 'English',
            'mathematics_grade' => 'Mathematics',
            'biology_grade' => 'Biology',
            'chemistry_grade' => 'Chemistry',
            'physics_grade' => 'Physics'
        ];
        
        // Track best grade for each subject across all sittings
        $bestGrades = [];
        
        foreach ($results as $result) {
            foreach (array_keys($requiredSubjects) as $subject) {
                if (!empty($result[$subject])) {
                    $grade = $result[$subject];
                    
                    // If we don't have a grade for this subject yet, or this grade is better
                    if (!isset($bestGrades[$subject])) {
                        $bestGrades[$subject] = $grade;
                    } else {
                        // Check if this grade is better (lower index in creditGrades means better)
                        $currentRank = array_search($bestGrades[$subject], $creditGrades);
                        $newRank = array_search($grade, $creditGrades);
                        
                        // If both are credit grades, keep the better one
                        if ($currentRank !== false && $newRank !== false) {
                            if ($newRank < $currentRank) {
                                $bestGrades[$subject] = $grade;
                            }
                        }
                        // If current is not credit but new is, use new
                        elseif ($currentRank === false && $newRank !== false) {
                            $bestGrades[$subject] = $grade;
                        }
                        // If neither is credit, keep whatever we have
                    }
                }
            }
        }
        
        // Check if all required subjects have credit passes
        $allCredits = true;
        $missingSubjects = [];
        $failedSubjects = [];
        
        foreach ($requiredSubjects as $subject => $displayName) {
            if (!isset($bestGrades[$subject])) {
                $allCredits = false;
                $missingSubjects[] = $displayName;
            } elseif (!in_array($bestGrades[$subject], $creditGrades)) {
                $allCredits = false;
                $failedSubjects[] = "{$displayName} (grade: {$bestGrades[$subject]})";
            }
        }
        
        if (!$allCredits) {
            $message = '';
            if (!empty($missingSubjects)) {
                $message = 'Missing grades for: ' . implode(', ', $missingSubjects);
            } elseif (!empty($failedSubjects)) {
                $message = 'Credit passes required. Failed subjects: ' . implode(', ', $failedSubjects);
            } else {
                $message = 'Credit passes required in all five subjects';
            }
            
            return [
                'valid' => false, 
                'message' => $message,
                'details' => [
                    'missing' => $missingSubjects,
                    'failed' => $failedSubjects,
                    'best_grades' => $bestGrades
                ]
            ];
        }
        
        return ['valid' => true, 'message' => 'O\'Level requirements met'];
    }
    
    /**
     * Verify O'Level results (admin function)
     */
    public function verifyResults($applicationId, $verifiedBy) {
        return $this->update(
            [
                'is_verified' => 1,
                'verified_at' => date('Y-m-d H:i:s'),
                'verified_by' => $verifiedBy
            ],
            'application_id = :application_id',
            ['application_id' => $applicationId]
        );
    }
    
    /**
     * Check if application has O'Level results
     */
    public function hasResults($applicationId) {
        $count = $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE application_id = :application_id",
            ['application_id' => $applicationId]
        );
        
        return $count > 0;
    }
    
    /**
     * Delete results for application - CRITICAL for preventing duplication
     * This method ensures old results are removed before saving new ones
     * 
     * @param int $applicationId
     * @return bool
     */
    public function deleteByApplicationId($applicationId) {
        $sql = "DELETE FROM {$this->table} WHERE application_id = :application_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['application_id' => $applicationId]);
    }
    
    /**
     * Get results by sitting
     */
    public function getBySitting($applicationId, $sitting) {
        $sql = "SELECT * FROM {$this->table} WHERE application_id = :application_id AND sitting = :sitting ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['application_id' => $applicationId, 'sitting' => $sitting]);
        return array_values($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    /**
     * Count results by application
     */
    public function countByApplication($applicationId) {
        return $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE application_id = :application_id",
            ['application_id' => $applicationId]
        );
    }
    
    /**
     * Get results summary for application
     */
    public function getSummary($applicationId) {
        $results = $this->getByApplicationId($applicationId);
        
        if (empty($results)) {
            return [
                'total_sittings' => 0,
                'subjects' => [],
                'best_grades' => []
            ];
        }
        
        $summary = [
            'total_sittings' => count($results),
            'subjects' => [],
            'best_grades' => []
        ];
        
        $subjectGrades = [
            'english_grade' => [],
            'mathematics_grade' => [],
            'biology_grade' => [],
            'chemistry_grade' => [],
            'physics_grade' => []
        ];
        
        $gradeScore = [
            'A1' => 8, 'B2' => 7, 'B3' => 6, 'C4' => 5, 'C5' => 4, 'C6' => 3,
            'D7' => 2, 'E8' => 1, 'F9' => 0
        ];
        
        foreach ($results as $result) {
            foreach (array_keys($subjectGrades) as $subject) {
                if (!empty($result[$subject])) {
                    $grade = $result[$subject];
                    $subjectGrades[$subject][] = [
                        'grade' => $grade,
                        'score' => $gradeScore[$grade] ?? 0,
                        'sitting' => $result['sitting'],
                        'exam_type' => $result['exam_type'],
                        'exam_year' => $result['exam_year']
                    ];
                }
            }
        }
        
        // Get best grade for each subject
        foreach ($subjectGrades as $subject => $grades) {
            if (!empty($grades)) {
                usort($grades, function($a, $b) {
                    return $b['score'] <=> $a['score'];
                });
                $summary['best_grades'][$subject] = $grades[0]['grade'];
                $summary['subjects'][$subject] = $grades; // Store all grades for this subject
            }
        }
        
        return $summary;
    }
    
    /**
     * Get formatted results for display
     */
    public function getFormattedResults($applicationId) {
        $results = $this->getByApplicationId($applicationId);
        
        if (empty($results)) {
            return [];
        }
        
        $formatted = [];
        foreach ($results as $index => $result) {
            $sitting = $result['sitting'];
            if (!isset($formatted[$sitting])) {
                $formatted[$sitting] = [
                    'index' => $index,
                    'exam_type' => $result['exam_type'],
                    'exam_year' => $result['exam_year'],
                    'exam_number' => $result['exam_number'],
                    'subjects' => []
                ];
            }
            
            $formatted[$sitting]['subjects'] = [
                'english' => $result['english_grade'],
                'mathematics' => $result['mathematics_grade'],
                'biology' => $result['biology_grade'],
                'chemistry' => $result['chemistry_grade'],
                'physics' => $result['physics_grade']
            ];
            
            if (!empty($result['other_subjects'])) {
                $formatted[$sitting]['other_subjects'] = json_decode($result['other_subjects'], true);
            }
        }
        
        // Re-index to ensure sequential
        return array_values($formatted);
    }
    
    /**
     * Insert a new record
     * Overrides parent to ensure ID is returned properly
     * 
     * @param array $data
     * @return int|false
     */
    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Get the database connection
     * 
     * @return PDO
     */
    public function getConnection() {
        return $this->db;
    }
}