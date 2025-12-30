<?php
/**
 * Research Controller
 * Handles research publications management
 * Extends the base Controller class for common functionality
 */
class ResearchController extends Controller {
    
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Set admin layout
        $this->layout = 'admin';
        
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '',
            'currentPage' => 'research'
        ]);
    }
    
    /**
     * Show research list
     */
    public function index() {
        $research = [];
        $categories = [];
        $stats = [];
        $error = null;
        
        // Get user role for permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        
        try {
            // Build query based on user role
            $query = "
                SELECT 
                    rp.*,
                    rc.name as category_name,
                    rc.slug as category_slug,
                    YEAR(rp.publication_date) as year,
                    u.username as created_by_name
                FROM research_publications rp
                LEFT JOIN research_categories rc ON rp.research_area = rc.slug
                LEFT JOIN users u ON rp.created_by = u.id
            ";
            
            if (!in_array($userRole, ['admin', 'editor'])) {
                // Others only see published research
                $query .= " WHERE rp.is_published = 1";
            }
            
            $query .= " ORDER BY rp.created_at DESC";
            
            $stmt = $this->db->query($query);
            $research = $stmt->fetchAll();
            
            // Get categories
            $categoriesStmt = $this->db->query("
                SELECT * FROM research_categories 
                WHERE is_active = 1 
                ORDER BY sort_order, name
            ");
            $categories = $categoriesStmt->fetchAll();
            
            // Get statistics - updated to use correct field names
            $statsStmt = $this->db->query("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN rp.is_published = 1 THEN 1 ELSE 0 END) as published,
                    SUM(CASE WHEN YEAR(rp.publication_date) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as current_year,
                    COUNT(DISTINCT YEAR(rp.publication_date)) as years_represented
                FROM research_publications rp
            ");
            $stats = $statsStmt->fetch();
            
        } catch (Exception $e) {
            error_log("ResearchController index error: " . $e->getMessage());
            $error = "Failed to load research publications.";
        }
        
        // Set data for view
        $this->data = array_merge($this->data, [
            'research' => $research,
            'categories' => $categories,
            'stats' => $stats,
            'error' => $error,
            'userRole' => $userRole,
            'pageTitle' => 'Research Publications - FCT College of Nursing Sciences',
            'pageDescription' => 'Manage research publications and articles'
        ]);

        // Render view
        $this->render('admin/research/index');
    }
    
    /**
     * Show create research form
     */
    public function create() {
        // Check permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->flash('error', 'You do not have permission to create research publications');
            $this->redirect('/admin/research');
            return;
        }
        
        // Get available years for dropdown
        $years = range(date('Y'), date('Y') - 20, -1);
        
        // Get categories
        try {
            $categoriesStmt = $this->db->query("
                SELECT * FROM research_categories 
                WHERE is_active = 1 
                ORDER BY sort_order, name
            ");
            $categories = $categoriesStmt->fetchAll();
        } catch (Exception $e) {
            error_log("ResearchController create error: " . $e->getMessage());
            $categories = [];
        }
        
        // Set data for view
        $this->data = array_merge($this->data, [
            'years' => $years,
            'categories' => $categories,
            'pageTitle' => 'Create Research Publication - FCT College of Nursing Sciences',
            'pageDescription' => 'Create a new research publication'
        ]);
        
        $this->render('admin/research/create');
    }
    
    /**
     * Store new research
     */
    public function store() {
        // Check permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->flash('error', 'You do not have permission to create research publications');
            $this->redirect('/admin/research');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method');
            $this->redirect('/admin/research/create');
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $title = trim($this->input('title', ''));
            $authors = trim($this->input('authors', ''));
            $abstract = trim($this->input('abstract', ''));
            $keywords = trim($this->input('keywords', ''));
            $journal = trim($this->input('journal', ''));
            $research_area = trim($this->input('research_area', ''));
            $publication_date = $this->input('publication_date', '');
            $doi = trim($this->input('doi', ''));
            $url = trim($this->input('url', ''));
            $is_published = $this->input('is_published', 0) ? 1 : 0;
            $created_by = $_SESSION['user_id'] ?? null;
            
            // Validate
            if (empty($title) || empty($authors)) {
                throw new Exception('Title and authors are required');
            }
            
            // Validate publication date
            if (!empty($publication_date)) {
                $date = DateTime::createFromFormat('Y-m-d', $publication_date);
                if (!$date || $date->format('Y-m-d') !== $publication_date) {
                    throw new Exception('Invalid publication date format. Use YYYY-MM-DD');
                }
            } else {
                // Default to current date if not provided
                $publication_date = date('Y-m-d');
            }
            
            $stmt = $this->db->prepare("
                INSERT INTO research_publications 
                (title, authors, abstract, keywords, journal, research_area, publication_date, doi, url, is_published, created_by, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            
            $stmt->execute([
                $title, $authors, $abstract, $keywords, $journal, 
                $research_area, $publication_date, $doi, $url, $is_published, $created_by
            ]);
            
            $researchId = $this->db->lastInsertId();
            
            // Log activity
            $this->logActivity('research_created', "Research publication '{$title}' created");
            
            // Set success message
            $this->flash('success', 'Research publication created successfully');
            
            // Redirect to the new research
            $this->redirect('/admin/research/' . $researchId);
            
        } catch (Exception $e) {
            error_log("ResearchController store error: " . $e->getMessage());
            
            // Get available years for dropdown
            $years = range(date('Y'), date('Y') - 20, -1);
            
            // Get categories
            try {
                $categoriesStmt = $this->db->query("
                    SELECT * FROM research_categories 
                    WHERE is_active = 1 
                    ORDER BY sort_order, name
                ");
                $categories = $categoriesStmt->fetchAll();
            } catch (Exception $ex) {
                $categories = [];
            }
            
            // Set data with error for create form
            $this->data = array_merge($this->data, [
                'years' => $years,
                'categories' => $categories,
                'error' => $e->getMessage(),
                'formData' => [
                    'title' => $this->input('title', ''),
                    'authors' => $this->input('authors', ''),
                    'abstract' => $this->input('abstract', ''),
                    'keywords' => $this->input('keywords', ''),
                    'journal' => $this->input('journal', ''),
                    'research_area' => $this->input('research_area', ''),
                    'publication_date' => $this->input('publication_date', date('Y-m-d')),
                    'doi' => $this->input('doi', ''),
                    'url' => $this->input('url', ''),
                    'is_published' => $this->input('is_published', 0)
                ],
                'pageTitle' => 'Create Research Publication - FCT College of Nursing Sciences',
                'pageDescription' => 'Create a new research publication'
            ]);
            
            $this->render('admin/research/create');
        }
    }
    
    /**
     * Show single research
     */
    public function show() {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->flash('error', 'Research publication ID is required');
            $this->redirect('/admin/research');
            return;
        }
        
        $research = null;
        $relatedResearch = [];
        $error = null;
        
        // Get user role for permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        
        try {
            // Build query based on user role
            $query = "
                SELECT 
                    rp.*,
                    rc.name as category_name,
                    rc.slug as category_slug,
                    YEAR(rp.publication_date) as year,
                    u.username as created_by_name
                FROM research_publications rp
                LEFT JOIN research_categories rc ON rp.research_area = rc.slug
                LEFT JOIN users u ON rp.created_by = u.id
                WHERE rp.id = ?
            ";
            
            if (!in_array($userRole, ['admin', 'editor'])) {
                // Others only see published research
                $query .= " AND rp.is_published = 1";
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $research = $stmt->fetch();
            
            if (!$research) {
                $this->flash('error', 'Research publication not found or not accessible');
                $this->redirect('/admin/research');
                return;
            }
            
            // Get related research by same authors or keywords or category
            $relatedStmt = $this->db->prepare("
                SELECT 
                    rp.id, rp.title, rp.authors, rp.publication_date, 
                    rp.journal_name, rp.citations, rp.downloads_count,
                    rc.name as category_name
                FROM research_publications rp
                LEFT JOIN research_categories rc ON rp.research_area = rc.slug
                WHERE rp.id != ? AND rp.is_published = 1
                AND (
                    rp.authors LIKE ? 
                    OR rp.keywords LIKE ? 
                    OR rp.research_area = ?
                )
                ORDER BY rp.publication_date DESC 
                LIMIT 3
            ");
            
            $searchTerm = "%" . strtok($research['authors'], ',') . "%";
            $keywordTerm = "%" . strtok($research['keywords'] ?? '', ',') . "%";
            
            $relatedStmt->execute([
                $id, 
                $searchTerm, 
                $keywordTerm,
                $research['research_area']
            ]);
            $relatedResearch = $relatedStmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("ResearchController show error: " . $e->getMessage());
            $error = "Failed to load research publication.";
        }
        
        // Set data for view - also pass as 'publication' for view compatibility
        $this->data = array_merge($this->data, [
            'research' => $research,
            'publication' => $research, // For compatibility with view file
            'relatedResearch' => $relatedResearch,
            'error' => $error,
            'userRole' => $userRole,
            'pageTitle' => $research['title'] . ' - Research Publication',
            'pageDescription' => $research['abstract'] ? substr(strip_tags($research['abstract']), 0, 150) . '...' : 'Research publication details'
        ]);
        
        $this->render('admin/research/show');
    }
    
    /**
     * Show edit research form
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->flash('error', 'Research publication ID is required');
            $this->redirect('/admin/research');
            return;
        }
        
        // Check permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->flash('error', 'You do not have permission to edit research publications');
            $this->redirect('/admin/research');
            return;
        }
        
        try {
            $query = "
                SELECT 
                    rp.*,
                    rc.name as category_name,
                    YEAR(rp.publication_date) as year
                FROM research_publications rp
                LEFT JOIN research_categories rc ON rp.research_area = rc.slug
                WHERE rp.id = ?
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $research = $stmt->fetch();
            
            if (!$research) {
                $this->flash('error', 'Research publication not found');
                $this->redirect('/admin/research');
                return;
            }
            
            // Get categories
            $categoriesStmt = $this->db->query("
                SELECT * FROM research_categories 
                WHERE is_active = 1 
                ORDER BY sort_order, name
            ");
            $categories = $categoriesStmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("ResearchController edit error: " . $e->getMessage());
            $this->flash('error', 'Failed to load research publication');
            $this->redirect('/admin/research');
            return;
        }
        
        // Set data for view - also pass as 'publication' for view compatibility
        $this->data = array_merge($this->data, [
            'research' => $research,
            'publication' => $research, // CRITICAL FIX: Add this line
            'categories' => $categories,
            'pageTitle' => 'Edit Research Publication - ' . $research['title'],
            'pageDescription' => 'Edit research publication details'
        ]);
        
        $this->render('admin/research/edit');
    }
    
    /**
     * Update research
     */
    public function update() {
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            $this->flash('error', 'Research publication ID is required');
            $this->redirect('/admin/research');
            return;
        }
        
        // Check permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->flash('error', 'You do not have permission to edit research publications');
            $this->redirect('/admin/research');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method');
            $this->redirect('/admin/research/' . $id . '/edit');
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $title = trim($this->input('title', ''));
            $authors = trim($this->input('authors', ''));
            $abstract = trim($this->input('abstract', ''));
            $keywords = trim($this->input('keywords', ''));
            $journal = trim($this->input('journal', ''));
            $research_area = trim($this->input('research_area', ''));
            $publication_date = $this->input('publication_date', '');
            $doi = trim($this->input('doi', ''));
            $url = trim($this->input('url', ''));
            $is_published = $this->input('is_published', 0) ? 1 : 0;
            
            // Validate
            if (empty($title) || empty($authors)) {
                throw new Exception('Title and authors are required');
            }
            
            // Validate publication date
            if (!empty($publication_date)) {
                $date = DateTime::createFromFormat('Y-m-d', $publication_date);
                if (!$date || $date->format('Y-m-d') !== $publication_date) {
                    throw new Exception('Invalid publication date format. Use YYYY-MM-DD');
                }
            } else {
                // Default to current date if not provided
                $publication_date = date('Y-m-d');
            }
            
            $stmt = $this->db->prepare("
                UPDATE research_publications 
                SET title = ?, authors = ?, abstract = ?, keywords = ?, 
                    journal = ?, research_area = ?, publication_date = ?, doi = ?, url = ?, is_published = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                $title, $authors, $abstract, $keywords, $journal, 
                $research_area, $publication_date, $doi, $url, $is_published, $id
            ]);
            
            // Log activity
            $this->logActivity('research_updated', "Research publication #{$id} '{$title}' updated");
            
            // Set success message
            $this->flash('success', 'Research publication updated successfully');
            
            // Redirect to the research
            $this->redirect('/admin/research/' . $id);
            
        } catch (Exception $e) {
            error_log("ResearchController update error: " . $e->getMessage());
            
            // Get research data for the form
            try {
                $query = "
                    SELECT 
                        rp.*,
                        rc.name as category_name,
                        YEAR(rp.publication_date) as year
                    FROM research_publications rp
                    LEFT JOIN research_categories rc ON rp.research_area = rc.slug
                    WHERE rp.id = ?
                ";
                
                $stmt = $this->db->prepare($query);
                $stmt->execute([$id]);
                $research = $stmt->fetch();
                
                if (!$research) {
                    throw new Exception("Research publication not found");
                }
                
                // Get categories
                $categoriesStmt = $this->db->query("
                    SELECT * FROM research_categories 
                    WHERE is_active = 1 
                    ORDER BY sort_order, name
                ");
                $categories = $categoriesStmt->fetchAll();
                
                // Set data for view - also pass as 'publication' for view compatibility
                $this->data = array_merge($this->data, [
                    'research' => $research,
                    'publication' => $research, // CRITICAL FIX: Add this line
                    'categories' => $categories,
                    'error' => $e->getMessage(),
                    'pageTitle' => 'Edit Research Publication - ' . ($research['title'] ?? 'Unknown'),
                    'pageDescription' => 'Edit research publication details'
                ]);
                
                $this->render('admin/research/edit');
            } catch (Exception $ex) {
                $this->showError($e->getMessage());
            }
        }
    }
    
    /**
     * Delete research
     */
    public function destroy() {
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            $this->flash('error', 'Research publication ID is required');
            $this->redirect('/admin/research');
            return;
        }
        
        // Check permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->flash('error', 'You do not have permission to delete research publications');
            $this->redirect('/admin/research');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method');
            $this->redirect('/admin/research');
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            // Get research title before deletion for logging
            $stmt = $this->db->prepare("SELECT title FROM research_publications WHERE id = ?");
            $stmt->execute([$id]);
            $research = $stmt->fetch();
            
            if (!$research) {
                throw new Exception("Research publication not found.");
            }
            
            // Delete research
            $deleteStmt = $this->db->prepare("DELETE FROM research_publications WHERE id = ?");
            $deleteStmt->execute([$id]);
            
            // Log activity
            $this->logActivity('research_deleted', "Research publication '{$research['title']}' deleted");
            
            // Set success message
            $this->flash('success', 'Research publication deleted successfully');
            
        } catch (Exception $e) {
            error_log("ResearchController destroy error: " . $e->getMessage());
            $this->flash('error', 'Failed to delete research publication: ' . $e->getMessage());
        }

        $this->redirect('/admin/research');
    }
    
    /**
     * Toggle research publication status
     */
    public function toggleStatus() {
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            $this->flash('error', 'Research publication ID is required');
            $this->redirect('/admin/research');
            return;
        }
        
        // Check permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->redirect('/admin/research');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/research');
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $value = $this->input('value', 0);
            
            $stmt = $this->db->prepare("UPDATE research_publications SET is_published = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$value, $id]);
            
            $status = $value ? 'published' : 'unpublished';
            
            $this->flash('success', "Research publication {$status} successfully!");
            
        } catch (Exception $e) {
            error_log("ResearchController toggleStatus error: " . $e->getMessage());
            $this->flash('error', 'Failed to update status: ' . $e->getMessage());
        }

        $this->redirect('/admin/research');
    }
    
    /**
     * Bulk operations on research publications
     */
    public function bulkAction() {
        // Check permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->redirect('/admin/research');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/research');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $action = $this->input('action', '');
            $researchIds = $this->input('research_ids', []);
            
            if (empty($researchIds) || !is_array($researchIds)) {
                throw new Exception("No research publications selected.");
            }
            
            $ids = implode(',', array_map('intval', $researchIds));
            
            switch ($action) {
                case 'publish':
                    $this->db->exec("UPDATE research_publications SET is_published = 1, updated_at = NOW() WHERE id IN ({$ids})");
                    $message = "Selected research publications published successfully!";
                    break;
                    
                case 'unpublish':
                    $this->db->exec("UPDATE research_publications SET is_published = 0, updated_at = NOW() WHERE id IN ({$ids})");
                    $message = "Selected research publications unpublished successfully!";
                    break;
                    
                case 'delete':
                    $this->db->exec("DELETE FROM research_publications WHERE id IN ({$ids})");
                    $message = "Selected research publications deleted successfully!";
                    break;
                    
                default:
                    throw new Exception("Invalid action specified.");
            }
            
            // Log activity
            $this->logActivity('research_bulk_action', "Bulk action '{$action}' performed on research publications");
            
            $this->flash('success', $message);
            
        } catch (Exception $e) {
            error_log("ResearchController bulkAction error: " . $e->getMessage());
            $this->flash('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }

        $this->redirect('/admin/research');
    }
    
    /**
     * Export research to CSV
     */
    public function export() {
        // Check permissions
        $userRole = $_SESSION['user_role'] ?? 'guest';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->redirect('/admin/research');
            return;
        }
        
        try {
            // Get all research publications with category information
            $query = "
                SELECT 
                    rp.*,
                    rc.name as category_name,
                    YEAR(rp.publication_date) as year,
                    u.username as created_by_name
                FROM research_publications rp
                LEFT JOIN research_categories rc ON rp.research_area = rc.slug
                LEFT JOIN users u ON rp.created_by = u.id
                ORDER BY rp.publication_date DESC, rp.created_at DESC
            ";
            
            $stmt = $this->db->query($query);
            $research = $stmt->fetchAll();
            
            // Set headers for CSV download
            $this->header('Content-Type', 'text/csv; charset=utf-8');
            $this->header('Content-Disposition', 'attachment; filename=research_publications_' . date('Y-m-d') . '.csv');
            
            // Create output stream
            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($output, [
                'ID', 'Title', 'Authors', 'Year', 'Journal', 'Research Area', 'Category',
                'Publication Date', 'DOI', 'URL', 'Status', 'Keywords', 'Created By',
                'Created At', 'Updated At'
            ]);
            
            // Add data rows
            foreach ($research as $pub) {
                fputcsv($output, [
                    $pub['id'],
                    $pub['title'],
                    $pub['authors'],
                    $pub['year'],
                    $pub['journal'],
                    $pub['research_area'],
                    $pub['category_name'] ?? '',
                    date('Y-m-d', strtotime($pub['publication_date'])),
                    $pub['doi'],
                    $pub['url'],
                    $pub['is_published'] ? 'Published' : 'Draft',
                    $pub['keywords'],
                    $pub['created_by_name'] ?? '',
                    date('Y-m-d H:i:s', strtotime($pub['created_at'])),
                    date('Y-m-d H:i:s', strtotime($pub['updated_at']))
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log("ResearchController export error: " . $e->getMessage());
            $this->flash('error', 'Failed to export research publications.');
            $this->redirect('/admin/research');
        }
    }
    
    /**
     * Log activity
     */
    private function logActivity($action, $description) {
        try {
            $user_id = $_SESSION['user_id'] ?? null;
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs 
                (user_id, action, description, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$user_id, $action, $description, $ip_address, $user_agent]);
        } catch (Exception $e) {
            error_log("Failed to log activity: " . $e->getMessage());
        }
    }
    
    /**
     * Override render method for admin-specific views
     */
    protected function render($view = null, $data = []) {
        // Add CSRF token to all forms
        $data['csrf_token'] = $this->csrfToken();
        
        // Add flash messages
        $data['flash_success'] = $this->getFlash('success');
        $data['flash_error'] = $this->getFlash('error');
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        // Call parent render method
        parent::render($view);
    }
    
    /**
     * Show error message
     */
    private function showError($message) {
        $this->data = array_merge($this->data, [
            'error' => $message,
            'pageTitle' => 'Error - FCT College of Nursing Sciences',
            'pageDescription' => 'An error occurred'
        ]);
        
        // Try to render error view
        $errorViewPath = APP_PATH . '/views/admin/error.php';
        if (file_exists($errorViewPath)) {
            $this->render('admin/error');
        } else {
            // Fallback error display
            echo '<div style="padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;">';
            echo '<h3>Error</h3>';
            echo '<p>' . htmlspecialchars($message) . '</p>';
            echo '<p><a href="' . ($this->data['baseUrl'] ?? '') . '/admin/dashboard">Back to Dashboard</a></p>';
            echo '</div>';
        }
    }
}