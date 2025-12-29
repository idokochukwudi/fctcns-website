<?php
/**
 * Research Controller
 * Handles research publications management
 */
class ResearchController {
    
    /**
     * Show research list
     */
    public function index() {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Get user role for permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        
        try {
            // Include database
            require_once __DIR__ . '/../config/database.php';
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            // Build query based on user role
            if (in_array($userRole, ['admin', 'editor'])) {
                // Admins and editors see all research
                $stmt = $conn->query("SELECT * FROM research_publications ORDER BY created_at DESC");
            } else {
                // Others only see published research
                $stmt = $conn->query("SELECT * FROM research_publications WHERE is_published = 1 ORDER BY created_at DESC");
            }
            
            $research = $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Research list error: " . $e->getMessage());
            $research = [];
        }
        
        // Load the view with data - CORRECTED PATH
        $this->loadView('admin/research', [
            'research' => $research,
            'userRole' => $userRole
        ]);
    }
    
    /**
     * Show create research form
     */
    public function create() {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Check permissions
        if (!in_array($_SESSION['user_role'], ['admin', 'editor'])) {
            $_SESSION['error'] = 'You do not have permission to create research publications';
            header('Location: ' . BASE_URL . '/admin/research');
            exit;
        }
        
        $this->loadView('admin/research_create', []);
    }
    
    /**
     * Store new research
     */
    public function store() {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Check permissions
        if (!in_array($_SESSION['user_role'], ['admin', 'editor'])) {
            $_SESSION['error'] = 'You do not have permission to create research publications';
            header('Location: ' . BASE_URL . '/admin/research');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method';
            header('Location: ' . BASE_URL . '/admin/research/create');
            exit;
        }
        
        $title = trim($_POST['title'] ?? '');
        $authors = trim($_POST['authors'] ?? '');
        $abstract = trim($_POST['abstract'] ?? '');
        $keywords = trim($_POST['keywords'] ?? '');
        $journal = trim($_POST['journal'] ?? '');
        $year = intval($_POST['year'] ?? date('Y'));
        $doi = trim($_POST['doi'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $is_published = isset($_POST['is_published']) ? 1 : 0;
        
        if (empty($title) || empty($authors)) {
            $_SESSION['error'] = 'Title and authors are required';
            header('Location: ' . BASE_URL . '/admin/research/create');
            exit;
        }
        
        try {
            require_once __DIR__ . '/../config/database.php';
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("
                INSERT INTO research_publications 
                (title, authors, abstract, keywords, journal, year, doi, url, is_published, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $title, $authors, $abstract, $keywords, $journal, 
                $year, $doi, $url, $is_published, $_SESSION['user_id']
            ]);
            
            $researchId = $conn->lastInsertId();
            
            $_SESSION['success'] = 'Research publication created successfully';
            header('Location: ' . BASE_URL . '/admin/research/' . $researchId);
            exit;
            
        } catch (Exception $e) {
            error_log("Research creation error: " . $e->getMessage());
            $_SESSION['error'] = 'Failed to create research publication';
            header('Location: ' . BASE_URL . '/admin/research/create');
            exit;
        }
    }
    
    /**
     * Show single research
     */
    public function show($id) {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        $userRole = $_SESSION['user_role'] ?? 'guest';
        
        try {
            require_once __DIR__ . '/../config/database.php';
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            // Build query based on user role
            if (in_array($userRole, ['admin', 'editor'])) {
                $stmt = $conn->prepare("SELECT * FROM research_publications WHERE id = ?");
            } else {
                $stmt = $conn->prepare("SELECT * FROM research_publications WHERE id = ? AND is_published = 1");
            }
            
            $stmt->execute([$id]);
            $research = $stmt->fetch();
            
            if (!$research) {
                $_SESSION['error'] = 'Research publication not found';
                header('Location: ' . BASE_URL . '/admin/research');
                exit;
            }
            
        } catch (Exception $e) {
            error_log("Research show error: " . $e->getMessage());
            $_SESSION['error'] = 'Failed to load research publication';
            header('Location: ' . BASE_URL . '/admin/research');
            exit;
        }
        
        // For now, redirect to edit view or use the main research view
        $this->loadView('admin/research_edit', [
            'research' => $research,
            'userRole' => $userRole,
            'mode' => 'view' // Add a mode parameter to indicate view-only
        ]);
    }
    
    /**
     * Show edit research form
     */
    public function edit($id) {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Check permissions
        if (!in_array($_SESSION['user_role'], ['admin', 'editor'])) {
            $_SESSION['error'] = 'You do not have permission to edit research publications';
            header('Location: ' . BASE_URL . '/admin/research');
            exit;
        }
        
        try {
            require_once __DIR__ . '/../config/database.php';
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("SELECT * FROM research_publications WHERE id = ?");
            $stmt->execute([$id]);
            $research = $stmt->fetch();
            
            if (!$research) {
                $_SESSION['error'] = 'Research publication not found';
                header('Location: ' . BASE_URL . '/admin/research');
                exit;
            }
            
        } catch (Exception $e) {
            error_log("Research edit error: " . $e->getMessage());
            $_SESSION['error'] = 'Failed to load research publication';
            header('Location: ' . BASE_URL . '/admin/research');
            exit;
        }
        
        $this->loadView('admin/research_edit', [
            'research' => $research,
            'mode' => 'edit'
        ]);
    }
    
    /**
     * Update research
     */
    public function update($id) {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Check permissions
        if (!in_array($_SESSION['user_role'], ['admin', 'editor'])) {
            $_SESSION['error'] = 'You do not have permission to edit research publications';
            header('Location: ' . BASE_URL . '/admin/research');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method';
            header('Location: ' . BASE_URL . '/admin/research/' . $id . '/edit');
            exit;
        }
        
        $title = trim($_POST['title'] ?? '');
        $authors = trim($_POST['authors'] ?? '');
        $abstract = trim($_POST['abstract'] ?? '');
        $keywords = trim($_POST['keywords'] ?? '');
        $journal = trim($_POST['journal'] ?? '');
        $year = intval($_POST['year'] ?? date('Y'));
        $doi = trim($_POST['doi'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $is_published = isset($_POST['is_published']) ? 1 : 0;
        
        if (empty($title) || empty($authors)) {
            $_SESSION['error'] = 'Title and authors are required';
            header('Location: ' . BASE_URL . '/admin/research/' . $id . '/edit');
            exit;
        }
        
        try {
            require_once __DIR__ . '/../config/database.php';
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("
                UPDATE research_publications 
                SET title = ?, authors = ?, abstract = ?, keywords = ?, 
                    journal = ?, year = ?, doi = ?, url = ?, is_published = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                $title, $authors, $abstract, $keywords, $journal, 
                $year, $doi, $url, $is_published, $id
            ]);
            
            $_SESSION['success'] = 'Research publication updated successfully';
            header('Location: ' . BASE_URL . '/admin/research/' . $id);
            exit;
            
        } catch (Exception $e) {
            error_log("Research update error: " . $e->getMessage());
            $_SESSION['error'] = 'Failed to update research publication';
            header('Location: ' . BASE_URL . '/admin/research/' . $id . '/edit');
            exit;
        }
    }
    
    /**
     * Delete research
     */
    public function destroy($id) {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Check permissions
        if (!in_array($_SESSION['user_role'], ['admin', 'editor'])) {
            $_SESSION['error'] = 'You do not have permission to delete research publications';
            header('Location: ' . BASE_URL . '/admin/research');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method';
            header('Location: ' . BASE_URL . '/admin/research');
            exit;
        }
        
        try {
            require_once __DIR__ . '/../config/database.php';
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $stmt = $conn->prepare("DELETE FROM research_publications WHERE id = ?");
            $stmt->execute([$id]);
            
            $_SESSION['success'] = 'Research publication deleted successfully';
            header('Location: ' . BASE_URL . '/admin/research');
            exit;
            
        } catch (Exception $e) {
            error_log("Research delete error: " . $e->getMessage());
            $_SESSION['error'] = 'Failed to delete research publication';
            header('Location: ' . BASE_URL . '/admin/research');
            exit;
        }
    }
    
    /**
     * Load view with data
     */
    private function loadView($view, $data = []) {
        // Define APP_PATH if not defined
        if (!defined('APP_PATH')) {
            define('APP_PATH', dirname(__DIR__));
        }
        
        // Define BASE_URL if not defined
        if (!defined('BASE_URL')) {
            // Try to get BASE_URL from constants file
            $constantsPath = APP_PATH . '/config/constants.php';
            if (file_exists($constantsPath)) {
                require_once $constantsPath;
            } else {
                // Fallback definition
                define('BASE_URL', 'http://localhost/fctcns-website');
            }
        }
        
        // Extract data for the view
        extract($data);
        
        // Include the view file
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Fallback error
            echo "<h1>View not found</h1>";
            echo "<p>View file not found: " . htmlspecialchars($viewPath) . "</p>";
            echo "<p>Looking for: " . htmlspecialchars($view) . ".php</p>";
            echo "<p>APP_PATH: " . APP_PATH . "</p>";
            echo "<p><a href='" . BASE_URL . "/admin/dashboard'>Return to Dashboard</a></p>";
        }
    }
}