<?php
/**
 * O'Level Result Model
 * 
 * Handles O'Level result data operations
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
     * Get results by application ID
     */
    public function getByApplicationId($applicationId) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE application_id = :application_id ORDER BY sitting, exam_year",
            ['application_id' => $applicationId]
        );
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
     */
    public function deleteByApplicationId($applicationId) {
        return $this->delete('application_id = :application_id', ['application_id' => $applicationId]);
    }
    
    /**
     * Get results by sitting
     */
    public function getBySitting($applicationId, $sitting) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE application_id = :application_id AND sitting = :sitting",
            ['application_id' => $applicationId, 'sitting' => $sitting]
        );
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
        foreach ($results as $result) {
            $sitting = $result['sitting'];
            if (!isset($formatted[$sitting])) {
                $formatted[$sitting] = [
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
        
        return $formatted;
    }
}