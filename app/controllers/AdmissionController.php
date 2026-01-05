<?php
/**
 * Admission Controller for 2025/2026 Admission List
 */

class AdmissionController {
    private $model;
    
    public function __construct() {
        $modelPath = APP_PATH . '/models/AdmissionListModel.php';
        if (!file_exists($modelPath)) {
            die("Model file not found: " . $modelPath);
        }
        require_once $modelPath;
        
        $this->model = new AdmissionListModel();
    }
    
    /**
     * Main admission list page with pagination
     */
    public function index() {
        // Get current page from URL, default to 1
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        
        // Get records per page from GET or default to 10
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $perPage = in_array($perPage, [3, 10, 25, 50, 100]) ? $perPage : 10;
        
        // Get data
        $admissions = $this->model->getAllAdmissions($page, $perPage);
        $totalRecords = $this->model->getTotalRecords();
        $statistics = $this->model->getStatistics();
        
        // Calculate pagination
        $totalPages = ceil($totalRecords / $perPage);
        
        $this->render('admission/index', [
            'admissions' => $admissions,
            'totalRecords' => $totalRecords,
            'statistics' => $statistics,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'showAll' => isset($_GET['view_all'])
        ]);
    }
    
    /**
     * Search admission list
     */
    public function search() {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($keyword)) {
            header('Location: ' . BASE_URL . '/admission');
            exit();
        }
        
        $results = $this->model->search($keyword);
        
        $this->render('admission/search', [
            'searchResults' => $results,
            'searchKeyword' => $keyword,
            'resultCount' => count($results)
        ]);
    }
    
    /**
     * Check individual admission status
     */
    public function check() {
        $regNumber = isset($_GET['reg']) ? trim($_GET['reg']) : '';
        
        if (empty($regNumber)) {
            header('Location: ' . BASE_URL . '/admission');
            exit();
        }
        
        $result = $this->model->getByRegistrationNumber($regNumber);
        
        $this->render('admission/check', [
            'admission' => $result,
            'regNumber' => $regNumber,
            'found' => !empty($result)
        ]);
    }
    
    /**
     * Admin: Bulk update from CSV
     */
    public function adminUpdate() {
        // Add authentication in production
        // if (!isset($_SESSION['admin'])) { redirect('/admin/login'); }
        
        $message = '';
        $error = '';
        $updateResult = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
            if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
                $tmpFile = $_FILES['csv_file']['tmp_name'];
                $updateResult = $this->model->bulkUpdateFromCSV($tmpFile);
                
                if ($updateResult['success']) {
                    $message = "Update completed. Updated: {$updateResult['updated']}, Unchanged: {$updateResult['unchanged']}";
                    if (!empty($updateResult['errors'])) {
                        $error = "Errors: " . count($updateResult['errors']);
                    }
                } else {
                    $error = $updateResult['message'];
                }
            } else {
                $error = "Error uploading file";
            }
        }
        
        $this->render('admission/admin_update', [
            'message' => $message,
            'error' => $error,
            'updateResult' => $updateResult
        ]);
    }
    
    /**
     * Manual status correction (admin override)
     */
    public function manualCorrection() {
        // Add proper authentication in production
        // if (!isset($_SESSION['admin'])) { redirect('/admin/login'); }
        
        $result = null;
        $message = '';
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $regNumber = trim($_POST['reg_number'] ?? '');
            $newStatus = trim($_POST['new_status'] ?? '');
            $adminName = trim($_POST['admin_name'] ?? 'System');
            $reason = trim($_POST['reason'] ?? '');
            
            if (empty($regNumber) || empty($newStatus)) {
                $error = "Registration number and status are required";
            } elseif (!in_array($newStatus, ['Accepted', 'Approved'])) {
                $error = "Invalid status. Must be 'Accepted' or 'Approved'";
            } else {
                $result = $this->model->manualStatusCorrection($regNumber, $newStatus, $adminName, $reason);
                
                if ($result['success']) {
                    $message = $result['message'];
                } else {
                    $error = $result['message'];
                }
            }
        }
        
        // Get current update configuration
        $config = $this->model->getUpdateConfig();
        
        $this->render('admission/manual_correction', [
            'message' => $message,
            'error' => $error,
            'result' => $result,
            'config' => $config
        ]);
    }
    
    /**
     * Candidate portal - simple page to check admission status
     */
    public function candidatePortal() {
        $result = null;
        $error = '';
        $searchPerformed = false;
        
        // Check if form was submitted via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $searchPerformed = true;
            $regNumber = isset($_POST['reg_number']) ? trim($_POST['reg_number']) : '';
            
            if (empty($regNumber)) {
                $error = "Please enter your registration number";
            } else {
                $result = $this->model->getByRegistrationNumber($regNumber);
                
                if (empty($result)) {
                    $error = "No admission record found for registration number: " . htmlspecialchars($regNumber);
                }
            }
        }
        
        $this->render('admission/candidate_portal', [
            'result' => $result,
            'error' => $error,
            'searchPerformed' => $searchPerformed,
            'regNumber' => $_POST['reg_number'] ?? ''
        ]);
    }
    
    /**
     * Check status (AJAX/API endpoint)
     */
    public function checkStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admission/candidate-portal');
            exit();
        }
        
        $regNumber = isset($_POST['reg_number']) ? trim($_POST['reg_number']) : '';
        
        if (empty($regNumber)) {
            $this->renderJson([
                'success' => false,
                'message' => 'Registration number is required'
            ]);
            return;
        }
        
        $result = $this->model->getByRegistrationNumber($regNumber);
        
        if (empty($result)) {
            $this->renderJson([
                'success' => false,
                'message' => 'No admission record found for this registration number'
            ]);
            return;
        }
        
        $this->renderJson([
            'success' => true,
            'data' => $result,
            'message' => 'Admission status found'
        ]);
    }
    
    /**
     * Render a view
     */
    private function render($view, $data = []) {
        extract($data);
        
        // Check if header exists
        $headerFile = APP_PATH . '/views/layouts/header.php';
        if (file_exists($headerFile)) {
            include $headerFile;
        } else {
            echo '<!DOCTYPE html><html><head>';
            echo '<title>Admission List - FCT College of Nursing Sciences</title>';
            echo '<meta charset="UTF-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
            echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
            echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';
            echo '<style>
                body { font-family: "Segoe UI", system-ui, sans-serif; }
                .card { border: 1px solid #e0e0e0; }
                .table th { background-color: #f8f9fa; font-weight: 600; }
                .badge { font-weight: 500; padding: 0.4em 0.8em; }
            </style>';
            echo '</head><body>';
        }
        
        // Include the main view
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            die("View file not found: " . htmlspecialchars($viewFile));
        }
        
        // Check if footer exists
        $footerFile = APP_PATH . '/views/layouts/footer.php';
        if (file_exists($footerFile)) {
            include $footerFile;
        } else {
            echo '</body></html>';
        }
    }
    
    /**
     * Render JSON response
     */
    private function renderJson($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
?>