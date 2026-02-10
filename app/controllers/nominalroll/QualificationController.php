<?php
/**
 * Qualification Controller
 * Handles qualification and certification related operations
 * 
 * @package FCT_CNS
 * @subpackage NominalRoll
 */

require_once APP_PATH . '/core/Controller.php';

class QualificationController extends Controller {
    
    /**
     * @var QualificationModel instance
     */
    private $qualificationModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Load model
        require_once APP_PATH . '/models/nominalroll/QualificationModel.php';
        $this->qualificationModel = new QualificationModel();
        
        // Check permissions
        $this->checkAccess();
    }
    
    /**
     * Check user access to qualification features
     */
    private function checkAccess() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/admin/login');
        }
        
        // Check if user has permission to access nominal roll
        if (!$this->checkPermission('nominal_roll_view')) {
            $this->flash('error', 'You do not have permission to access the qualification reports.');
            $this->redirect('/admin/dashboard');
        }
    }
    
    /**
     * Main qualification reports page
     */
    public function index() {
        try {
            // Get filter options
            $qualifications = $this->qualificationModel->getUniqueQualifications();
            $certifications = $this->qualificationModel->getUniqueCertifications();
            
            // Get stats for display
            $stats = $this->qualificationModel->getQualificationStats();
            
            // Prepare data for view
            $data = [
                'page_title' => 'Qualification & Certification Reports',
                'qualifications' => $qualifications,
                'certifications' => $certifications,
                'stats' => $stats,
                'baseUrl' => $this->data['baseUrl']
            ];
            
            $this->render('admin/nominal-roll/qualification-reports', $data);
            
        } catch (Exception $e) {
            error_log("QualificationController Error (index): " . $e->getMessage());
            $this->flash('error', 'Failed to load qualification reports: ' . $e->getMessage());
            $this->redirect('/admin/nominal-roll/reports');
        }
    }
    
    /**
     * Generate qualification-specific report
     */
    public function generateReport() {
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            // Get parameters
            $qualificationType = $this->input('qualification_type');
            $qualificationValue = $this->input('qualification_value');
            $searchTerm = $this->input('search', '');
            $department = $this->input('department', '');
            $gradeLevel = $this->input('grade_level', '');
            $state = $this->input('state', '');
            $sex = $this->input('sex', '');
            $sortOrder = $this->input('sort_order', 'surname_asc');
            $limit = (int)$this->input('preview_limit', 20);
            
            // Validate parameters
            if (empty($qualificationType) || empty($qualificationValue)) {
                $this->flash('error', 'Qualification type and value are required.');
                $this->redirect('/admin/nominal-roll/qualification-reports');
            }
            
            // Prepare filters
            $filters = [
                'search' => $searchTerm,
                'department' => $department,
                'grade_level' => $gradeLevel,
                'state' => $state,
                'sex' => $sex
            ];
            
            // Get employees
            $result = $this->qualificationModel->getEmployeesByQualification(
                $qualificationType,
                $qualificationValue,
                [], // Use default fields
                $filters,
                $sortOrder,
                $limit
            );
            
            // Prepare data for view
            $data = [
                'page_title' => "Report: {$qualificationValue} Holders",
                'employees' => $result['employees'],
                'total' => $result['total'],
                'qualification_type' => $qualificationType,
                'qualification_value' => $qualificationValue,
                'filters' => $filters,
                'sort_order' => $sortOrder,
                'preview_limit' => $limit,
                'baseUrl' => $this->data['baseUrl']
            ];
            
            // If AJAX request, return JSON
            if ($this->isAjax()) {
                $this->json([
                    'success' => true,
                    'data' => $result['employees'],
                    'total' => $result['total'],
                    'message' => "Found {$result['total']} employees with {$qualificationValue} qualification."
                ]);
                return;
            }
            
            $this->render('admin/nominal-roll/qualification-report-view', $data);
            
        } catch (Exception $e) {
            error_log("QualificationController Error (generateReport): " . $e->getMessage());
            
            if ($this->isAjax()) {
                $this->json([
                    'success' => false,
                    'error' => 'Failed to generate report: ' . $e->getMessage()
                ], 500);
            } else {
                $this->flash('error', 'Failed to generate report: ' . $e->getMessage());
                $this->redirect('/admin/nominal-roll/qualification-reports');
            }
        }
    }
    
    /**
     * Search in additional qualifications
     */
    public function searchAdditional() {
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            // Get search term
            $searchTerm = $this->input('search_term', '');
            
            if (empty($searchTerm)) {
                $this->json([
                    'success' => false,
                    'error' => 'Search term is required.'
                ], 400);
                return;
            }
            
            // Get additional filters
            $department = $this->input('department', '');
            $gradeLevel = $this->input('grade_level', '');
            $state = $this->input('state', '');
            $sex = $this->input('sex', '');
            $limit = (int)$this->input('limit', 20);
            
            $filters = [
                'department' => $department,
                'grade_level' => $gradeLevel,
                'state' => $state,
                'sex' => $sex
            ];
            
            // Search in additional qualifications
            $result = $this->qualificationModel->searchInAdditionalQualifications(
                $searchTerm,
                [], // Use default fields
                $filters,
                $limit
            );
            
            $this->json([
                'success' => true,
                'data' => $result['employees'],
                'total' => $result['total'],
                'message' => "Found {$result['total']} employees with qualifications matching '{$searchTerm}'."
            ]);
            
        } catch (Exception $e) {
            error_log("QualificationController Error (searchAdditional): " . $e->getMessage());
            $this->json([
                'success' => false,
                'error' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export qualification report to Excel
     */
    public function exportExcel() {
        try {
            // Get parameters from GET or POST
            $qualificationType = $this->input('qualification_type') ?? $this->query('qualification_type');
            $qualificationValue = $this->input('qualification_value') ?? $this->query('qualification_value');
            
            if (empty($qualificationType) || empty($qualificationValue)) {
                $this->flash('error', 'Qualification type and value are required for export.');
                $this->redirect('/admin/nominal-roll/qualification-reports');
            }
            
            // Get all employees (no limit for export)
            $result = $this->qualificationModel->getEmployeesByQualification(
                $qualificationType,
                $qualificationValue,
                [], // Use default fields
                [], // No filters for full export
                'surname_asc',
                0 // No limit
            );
            
            // Generate Excel file
            $this->generateExcelExport($result['employees'], $qualificationType, $qualificationValue);
            
        } catch (Exception $e) {
            error_log("QualificationController Error (exportExcel): " . $e->getMessage());
            $this->flash('error', 'Export failed: ' . $e->getMessage());
            $this->redirect('/admin/nominal-roll/qualification-reports');
        }
    }
    
    /**
     * Export qualification report to CSV
     */
    public function exportCsv() {
        try {
            // Get parameters from GET or POST
            $qualificationType = $this->input('qualification_type') ?? $this->query('qualification_type');
            $qualificationValue = $this->input('qualification_value') ?? $this->query('qualification_value');
            
            if (empty($qualificationType) || empty($qualificationValue)) {
                $this->flash('error', 'Qualification type and value are required for export.');
                $this->redirect('/admin/nominal-roll/qualification-reports');
            }
            
            // Get all employees (no limit for export)
            $result = $this->qualificationModel->getEmployeesByQualification(
                $qualificationType,
                $qualificationValue,
                [], // Use default fields
                [], // No filters for full export
                'surname_asc',
                0 // No limit
            );
            
            // Generate CSV file
            $this->generateCsvExport($result['employees'], $qualificationType, $qualificationValue);
            
        } catch (Exception $e) {
            error_log("QualificationController Error (exportCsv): " . $e->getMessage());
            $this->flash('error', 'Export failed: ' . $e->getMessage());
            $this->redirect('/admin/nominal-roll/qualification-reports');
        }
    }
    
    /**
     * Generate Excel export
     */
    private function generateExcelExport($employees, $qualificationType, $qualificationValue) {
        // Set headers for Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $qualificationValue . '_Holders_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        
        // Start output
        echo '<html><head>';
        echo '<meta charset="UTF-8">';
        echo '<style>';
        echo 'table { border-collapse: collapse; width: 100%; }';
        echo 'th { background-color: #4CAF50; color: white; padding: 8px; text-align: left; border: 1px solid #ddd; }';
        echo 'td { padding: 8px; text-align: left; border: 1px solid #ddd; }';
        echo 'tr:nth-child(even) { background-color: #f2f2f2; }';
        echo '</style>';
        echo '</head><body>';
        
        echo '<h2>' . htmlspecialchars($qualificationValue) . ' Holders Report</h2>';
        echo '<p>Generated on: ' . date('Y-m-d H:i:s') . '</p>';
        echo '<p>Total Records: ' . count($employees) . '</p>';
        
        if (!empty($employees)) {
            echo '<table>';
            echo '<tr>';
            
            // Headers
            foreach (array_keys($employees[0]) as $header) {
                echo '<th>' . htmlspecialchars(ucwords(str_replace('_', ' ', $header))) . '</th>';
            }
            echo '</tr>';
            
            // Data rows
            foreach ($employees as $employee) {
                echo '<tr>';
                foreach ($employee as $value) {
                    echo '<td>' . htmlspecialchars($value) . '</td>';
                }
                echo '</tr>';
            }
            
            echo '</table>';
        } else {
            echo '<p>No records found.</p>';
        }
        
        echo '</body></html>';
        exit;
    }
    
    /**
     * Generate CSV export
     */
    private function generateCsvExport($employees, $qualificationType, $qualificationValue) {
        // Set headers for CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment;filename="' . $qualificationValue . '_Holders_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        
        if (!empty($employees)) {
            // Headers
            fputcsv($output, array_keys($employees[0]));
            
            // Data rows
            foreach ($employees as $employee) {
                fputcsv($output, $employee);
            }
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Quick qualification report (for AJAX/API)
     */
    public function quickReport() {
        try {
            // This method handles quick reports from the main reports page
            $qualificationType = $this->input('type');
            $value = $this->input('value');
            
            if (empty($qualificationType) || empty($value)) {
                $this->json([
                    'success' => false,
                    'error' => 'Missing parameters'
                ], 400);
                return;
            }
            
            // Get employees
            $result = $this->qualificationModel->getEmployeesByQualification(
                $qualificationType,
                $value,
                [], // Default fields
                [], // No filters
                'surname_asc',
                10 // Limit for preview
            );
            
            $this->json([
                'success' => true,
                'data' => $result['employees'],
                'total' => $result['total'],
                'message' => "Quick report for {$value} holders generated."
            ]);
            
        } catch (Exception $e) {
            error_log("QualificationController Error (quickReport): " . $e->getMessage());
            $this->json([
                'success' => false,
                'error' => 'Quick report failed: ' . $e->getMessage()
            ], 500);
        }
    }
}