<?php
/**
 * Research Controller
 * Handles all research-related requests
 * Extends base Controller for common functionality
 */
class ResearchController extends Controller
{
    private $model;
    private $uploadDir;
    private $thumbnailDir;
    private $publicUploadUrl;
    
    public function __construct()
    {
        parent::__construct();
        
        // Load model
        $this->model = new ResearchModel();
        
        // Set upload directories using your constants
        $this->uploadDir = UPLOADS_PATH . '/research/';
        $this->thumbnailDir = UPLOADS_PATH . '/research/thumbnails/';
        
        // Set public URL for uploaded files
        $this->publicUploadUrl = BASE_URL . '/uploads/research/';
        
        // Create upload directories if they don't exist
        $this->ensureDirectories();
        
        // Set admin layout for admin methods
        $this->layout = 'admin';
    }
    
    /**
     * Ensure upload directories exist
     */
    private function ensureDirectories()
    {
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
        
        if (!is_dir($this->thumbnailDir)) {
            mkdir($this->thumbnailDir, 0755, true);
        }
    }
    
    // ============================================================================
    // ADMIN ACTIONS (REQUIRE AUTHENTICATION)
    // ============================================================================
    
    /**
     * ADMIN: List all publications
     */
    public function index()
    {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Get user info from session
        $userRole = Session::getUserRole();
        $username = Session::getUsername();
        $userId = Session::getUserId();
        
        // Get filter parameters
        $filters = [
            'is_published' => $this->query('status'),
            'research_area' => $this->query('category'),
            'publication_type' => $this->query('type'),
            'year' => $this->query('year'),
            'search' => $this->query('search'),
            'order_by' => $this->query('order_by', 'publication_date'),
            'order_dir' => $this->query('order_dir', 'DESC')
        ];
        
        // Remove empty filters
        $filters = array_filter($filters);
        
        $data = [
            'publications' => $this->model->getAll($filters),
            'categories' => $this->model->getCategories(),
            'stats' => $this->model->getStats(),
            'filters' => $filters,
            'userRole' => $userRole,
            'username' => $username,
            'userId' => $userId,
            'pageTitle' => 'Research Publications - Admin',
            'currentPage' => 'research'
        ];
        
        $this->render('admin/research/index', $data);
    }
    
    /**
     * ADMIN: Show create form
     */
    public function create()
    {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Get user info from session
        $userRole = Session::getUserRole();
        $username = Session::getUsername();
        $userId = Session::getUserId();
        
        // Generate CSRF token using same method as view (Session class if available)
        $csrf_token = $this->getCSRFTokenForView();
        
        $data = [
            'categories' => $this->model->getCategories(),
            'publication' => null,
            'userRole' => $userRole,
            'username' => $username,
            'userId' => $userId,
            'pageTitle' => 'Add New Research Publication',
            'currentPage' => 'research',
            'csrf_token' => $csrf_token
        ];
        
        $this->render('admin/research/create', $data);
    }
    
    /**
     * ADMIN: Save new publication
     */
    public function store()
    {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Validate CSRF token using Session class
        $token = $this->input('csrf_token');
        
        if (empty($token)) {
            error_log("ERROR: No CSRF token in POST data");
            $this->flash('error', 'Security token missing. Please refresh the page and try again.');
            $this->redirect('/admin/research/create');
            return;
        }
        
        // Use validateCSRFToken() for consistency
        if (!Session::validateCSRFToken($token)) {
            error_log("ERROR: Invalid or expired CSRF token");
            $this->flash('error', 'Invalid or expired security token. Please refresh the page and try again.');
            $this->redirect('/admin/research/create');
            return;
        }
        
        error_log("CSRF validation PASSED using Session::validateCSRFToken()");
        
        // Get form data
        $inputData = $this->input();
        
        // Validate required fields
        $errors = $this->validatePublication($inputData);
        
        if (!empty($errors)) {
            $this->flash('errors', $errors);
            $this->flash('old', $inputData);
            $this->redirect('/admin/research/create');
            return;
        }
        
        // Handle file uploads
        $fileData = $this->handleFileUploads();
        
        // Prepare publication data
        $publicationData = $this->preparePublicationData($inputData, $fileData);
        
        // Save to database
        $id = $this->model->create($publicationData);
        
        if ($id) {
            // Clear CSRF token after successful save
            Session::clearCSRFToken();
            error_log("CSRF token cleared after successful save");
            
            $this->flash('success', 'Research publication created successfully!');
            
            if ($this->input('save_and_view')) {
                $this->redirect('/admin/research/' . $id);
            } else {
                $this->redirect('/admin/research');
            }
        } else {
            $this->flash('error', 'Failed to create publication. Please try again.');
            $this->flash('old', $inputData);
            $this->redirect('/admin/research/create');
        }
    }
    
    /**
     * ADMIN: Show edit form
     */
    public function edit($id)
    {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        $publication = $this->model->getById($id);
        
        if (!$publication) {
            $this->flash('error', 'Publication not found');
            $this->redirect('/admin/research');
            return;
        }
        
        // Get categories
        $categories = $this->model->getCategories();
        
        // Get flash messages from Session class
        $flash_success = Session::has('success') ? Session::flash('success') : null;
        $flash_error = Session::has('error') ? Session::flash('error') : null;
        $flash_errors = Session::has('errors') ? Session::flash('errors') : null;
        $old_input = Session::has('old') ? Session::flash('old') : [];
        
        // Use old input if available, otherwise use publication data
        if (!empty($old_input)) {
            $defaults = $old_input;
        } else {
            $defaults = [
                'title' => $publication['title'] ?? '',
                'authors' => $publication['authors'] ?? '',
                'abstract' => $publication['abstract'] ?? '',
                'keywords' => $publication['keywords'] ?? '',
                'publication_type' => $publication['publication_type'] ?? 'journal',
                'journal_name' => $publication['journal_name'] ?? '',
                'volume' => $publication['volume'] ?? '',
                'issue' => $publication['issue'] ?? '',
                'pages' => $publication['pages'] ?? '',
                'publisher' => $publication['publisher'] ?? '',
                'publication_date' => $publication['publication_date'] ?? date('Y-m-d'),
                'doi' => $publication['doi'] ?? '',
                'url' => $publication['url'] ?? '',
                'research_area' => $publication['research_area'] ?? '',
                'citations' => $publication['citations'] ?? 0,
                'impact_factor' => $publication['impact_factor'] ?? '',
                'is_published' => $publication['is_published'] ?? 0,
                'is_featured' => $publication['is_featured'] ?? 0
            ];
        }
        
        // File paths for display (get public URLs)
        $currentFile = !empty($publication['file_path']) ? $this->getPublicFilePath($publication['file_path']) : '';
        $currentThumbnail = !empty($publication['thumbnail_path']) ? $this->getPublicFilePath($publication['thumbnail_path']) : '';
        
        // Get user info from session
        $userRole = Session::getUserRole();
        $username = Session::getUsername();
        $userId = Session::getUserId();
        
        // Generate CSRF token using same method as view
        $csrf_token = $this->getCSRFTokenForView();
        
        // Set data for view
        $data = [
            'publication' => $publication,
            'publicationId' => $id,
            'categories' => $categories,
            'defaults' => $defaults,
            'currentFile' => $currentFile,
            'currentThumbnail' => $currentThumbnail,
            'flash_success' => $flash_success,
            'flash_error' => $flash_error,
            'flash_errors' => $flash_errors,
            'old_input' => $old_input,
            'userRole' => $userRole,
            'username' => $username,
            'userId' => $userId,
            'pageTitle' => 'Edit Research Publication',
            'currentPage' => 'research',
            'csrf_token' => $csrf_token
        ];
        
        $this->render('admin/research/edit', $data);
    }
    
    /**
     * ADMIN: Update publication - FIXED VERSION
     */
    public function update($id)
    {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // TEST AND FIX DATABASE CONNECTION FIRST
        try {
            if (!$this->model->testConnection()) {
                // If connection fails, wait and try again
                sleep(3);
                
                // Clear the model to force reconnection
                $this->model = new ResearchModel();
            }
        } catch (Exception $e) {
            error_log("Database connection test failed: " . $e->getMessage());
        }
        
        // Validate CSRF token using Session class
        $token = $this->input('csrf_token');
        
        if (empty($token)) {
            error_log("UPDATE ERROR: No CSRF token in POST data");
            $this->flash('error', 'Security token missing. Please try again.');
            $this->redirect('/admin/research/' . $id . '/edit');
            return;
        }
        
        // Use validateCSRFToken() for consistency
        if (!Session::validateCSRFToken($token)) {
            error_log("UPDATE ERROR: Invalid or expired CSRF token");
            $this->flash('error', 'Invalid or expired security token. Please refresh the page and try again.');
            $this->redirect('/admin/research/' . $id . '/edit');
            return;
        }
        
        error_log("CSRF validation PASSED for update");
        
        // Check if publication exists
        $publication = $this->model->getById($id);
        if (!$publication) {
            $this->flash('error', 'Publication not found');
            $this->redirect('/admin/research');
            return;
        }
        
        // Validate required fields
        $errors = $this->validatePublication($this->input(), $id);
        
        if (!empty($errors)) {
            $this->flash('errors', $errors);
            $this->flash('old', $this->input());
            $this->redirect('/admin/research/' . $id . '/edit');
            return;
        }
        
        // Handle file uploads (only if new files are uploaded)
        $fileData = $this->handleFileUploads($publication);
        
        // Prepare update data
        $updateData = $this->preparePublicationData($this->input(), $fileData);
        
        // Update in database
        $result = $this->model->update($id, $updateData);
        
        if ($result) {
            // Clear CSRF token after successful update
            Session::clearCSRFToken();
            error_log("CSRF token cleared after successful update");
            
            $this->flash('success', 'Publication updated successfully!');
            
            // Delete old files if new ones were uploaded
            if (!empty($fileData['file_path']) && !empty($publication['file_path'])) {
                @unlink($this->getAbsolutePath($publication['file_path']));
            }
            
            if (!empty($fileData['thumbnail_path']) && !empty($publication['thumbnail_path'])) {
                @unlink($this->getAbsolutePath($publication['thumbnail_path']));
            }
            
            if ($this->input('save_and_view')) {
                $this->redirect('/admin/research/' . $id);
            } else {
                $this->redirect('/admin/research');
            }
        } else {
            $this->flash('error', 'Failed to update publication. Please try again.');
            $this->flash('old', $this->input());
            $this->redirect('/admin/research/' . $id . '/edit');
        }
    }
    
    /**
     * ADMIN: Show single publication
     */
    public function show($id)
    {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Get user info from session
        $userRole = Session::getUserRole();
        $username = Session::getUsername();
        $userId = Session::getUserId();
        
        $publication = $this->model->getById($id);
        
        if (!$publication) {
            $this->flash('error', 'Publication not found');
            $this->redirect('/admin/research');
            return;
        }
        
        // Get categories
        $categories = $this->model->getCategories();
        
        // Get category name
        $categoryName = 'Unknown';
        foreach ($categories as $cat) {
            if ($cat['slug'] == $publication['research_area']) {
                $categoryName = $cat['name'];
                break;
            }
        }
        
        // Publication type labels
        $pubTypes = [
            'journal' => 'Journal Article',
            'conference' => 'Conference Paper',
            'book' => 'Book/Chapter',
            'thesis' => 'Thesis/Dissertation',
            'report' => 'Technical Report'
        ];
        
        $pubTypeLabel = $pubTypes[$publication['publication_type']] ?? ucfirst($publication['publication_type']);
        
        // Convert file paths to public URLs using FIXED method
        $fileUrl = !empty($publication['file_path']) ? $this->getPublicFilePath($publication['file_path']) : null;
        $thumbnailUrl = !empty($publication['thumbnail_path']) ? $this->getPublicFilePath($publication['thumbnail_path']) : null;
        
        // Prepare data for view
        $data = [
            'publication' => $publication,
            'categories' => $categories,
            'categoryName' => $categoryName,
            'pubDate' => date('F j, Y', strtotime($publication['publication_date'])),
            'createdDate' => date('F j, Y', strtotime($publication['created_at'])),
            'updatedDate' => date('F j, Y', strtotime($publication['updated_at'])),
            'pubTypeLabel' => $pubTypeLabel,
            'keywordsArray' => !empty($publication['keywords']) ? 
                              array_map('trim', explode(',', $publication['keywords'])) : [],
            'userRole' => $userRole,
            'username' => $username,
            'userId' => $userId,
            'fileUrl' => $fileUrl,
            'thumbnailUrl' => $thumbnailUrl,
            'flash_success' => Session::has('success') ? Session::flash('success') : null,
            'flash_error' => Session::has('error') ? Session::flash('error') : null,
            'currentPage' => 'research',
            'pageTitle' => htmlspecialchars($publication['title']) . ' - FCT College of Nursing Sciences - Admin'
        ];
        
        $this->render('admin/research/show', $data);
    }
    
    /**
     * ADMIN: Delete publication - FIXED VERSION
     */
    public function destroy($id)
    {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Validate CSRF token using Session class - FIXED: Use validateCSRFToken()
        $token = $this->input('csrf_token');
        if (empty($token) || !Session::validateCSRFToken($token)) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/research');
            return;
        }
        
        $result = $this->model->delete($id);
        
        if ($result) {
            // Clear CSRF token after successful deletion
            Session::clearCSRFToken();
            
            // Delete associated files
            if (!empty($result['file_path'])) {
                @unlink($this->getAbsolutePath($result['file_path']));
            }
            
            if (!empty($result['thumbnail_path'])) {
                @unlink($this->getAbsolutePath($result['thumbnail_path']));
            }
            
            $this->flash('success', 'Publication deleted successfully!');
            $this->redirect('/admin/research');
        } else {
            $this->flash('error', 'Failed to delete publication');
            $this->redirect('/admin/research');
        }
    }
    
    /**
     * ADMIN: Toggle publish status - FIXED VERSION
     */
    public function toggleStatus($id)
    {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Validate CSRF token using Session class - FIXED: Use validateCSRFToken()
        $token = $this->input('csrf_token');
        if (empty($token) || !Session::validateCSRFToken($token)) {
            $this->json(['success' => false, 'message' => 'Security token expired'], 403);
            return;
        }
        
        $result = $this->model->toggleStatus($id);
        
        if ($result) {
            // Clear CSRF token after successful operation
            Session::clearCSRFToken();
            
            $publication = $this->model->getById($id);
            $this->json([
                'success' => true,
                'is_published' => $publication['is_published'],
                'message' => $publication['is_published'] ? 'Published successfully!' : 'Unpublished successfully!'
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }
    
    /**
     * ADMIN: Bulk actions - FIXED VERSION
     */
    public function bulkAction()
    {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Validate CSRF token using Session class - FIXED: Use validateCSRFToken()
        $token = $this->input('csrf_token');
        if (empty($token) || !Session::validateCSRFToken($token)) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/research');
            return;
        }
        
        if (empty($this->input('selected_ids')) || empty($this->input('action'))) {
            $this->flash('error', 'No items selected or no action specified');
            $this->redirect('/admin/research');
        }
        
        $ids = $this->input('selected_ids');
        $action = $this->input('action');
        $count = 0;
        
        switch ($action) {
            case 'publish':
                foreach ($ids as $id) {
                    $this->model->toggleStatus($id, 1);
                    $count++;
                }
                $this->flash('success', "{$count} publications published successfully!");
                break;
                
            case 'unpublish':
                foreach ($ids as $id) {
                    $this->model->toggleStatus($id, 0);
                    $count++;
                }
                $this->flash('success', "{$count} publications unpublished successfully!");
                break;
                
            case 'delete':
                foreach ($ids as $id) {
                    $result = $this->model->delete($id);
                    if ($result) {
                        $count++;
                        // Delete files
                        if (!empty($result['file_path'])) @unlink($this->getAbsolutePath($result['file_path']));
                        if (!empty($result['thumbnail_path'])) @unlink($this->getAbsolutePath($result['thumbnail_path']));
                    }
                }
                $this->flash('success', "{$count} publications deleted successfully!");
                break;
                
            case 'feature':
                foreach ($ids as $id) {
                    $this->model->update($id, ['is_featured' => 1]);
                    $count++;
                }
                $this->flash('success', "{$count} publications featured successfully!");
                break;
                
            case 'unfeature':
                foreach ($ids as $id) {
                    $this->model->update($id, ['is_featured' => 0]);
                    $count++;
                }
                $this->flash('success', "{$count} publications unfeatured successfully!");
                break;
        }
        
        // Clear CSRF token after successful bulk action
        Session::clearCSRFToken();
        
        $this->redirect('/admin/research');
    }
    
    // ============================================================================
    // PUBLIC ACTIONS (NO AUTHENTICATION REQUIRED)
    // ============================================================================
    
    /**
     * PUBLIC: Display research page
     */
    public function publicIndex()
    {
        // Switch to main layout for public pages
        $this->layout = 'main';
        
        // Get filter parameters
        $category = $this->query('category');
        $search = $this->query('search');
        
        if ($category) {
            $publications = $this->model->getByCategory($category, 50);
        } elseif ($search) {
            $publications = $this->model->getAll([
                'search' => $search,
                'is_published' => 1,
                'limit' => 50
            ]);
        } else {
            $publications = $this->model->getPublished(20);
        }
        
        // Convert file paths to public URLs
        foreach ($publications as &$pub) {
            if (!empty($pub['file_path'])) {
                $pub['file_url'] = $this->getPublicFilePath($pub['file_path']);
            }
            if (!empty($pub['thumbnail_path'])) {
                $pub['thumbnail_url'] = $this->getPublicFilePath($pub['thumbnail_path']);
            }
        }
        
        $data = [
            'publications' => $publications,
            'categories' => $this->model->getCategories(),
            'featured' => $this->model->getFeatured(5),
            'currentCategory' => $category,
            'searchTerm' => $search,
            'pageTitle' => 'Research Publications - FCT College of Nursing Sciences',
            'currentPage' => 'research'
        ];
        
        $this->render('pages/research', $data);
    }
    
    /**
     * PUBLIC: View single publication
     */
    public function publicShow($id)
    {
        // Switch to main layout for public pages
        $this->layout = 'main';
        
        $publication = $this->model->getById($id);
        
        if (!$publication || !$publication['is_published']) {
            $this->flash('error', 'Publication not found or not published');
            $this->redirect('/research');
        }
        
        // Get categories
        $categories = $this->model->getCategories();
        
        // Get category name
        $categoryName = 'Unknown';
        foreach ($categories as $cat) {
            if ($cat['slug'] == $publication['research_area']) {
                $categoryName = $cat['name'];
                break;
            }
        }
        
        // Publication type labels
        $pubTypes = [
            'journal' => 'Journal Article',
            'conference' => 'Conference Paper',
            'book' => 'Book/Chapter',
            'thesis' => 'Thesis/Dissertation',
            'report' => 'Technical Report'
        ];
        
        $pubTypeLabel = $pubTypes[$publication['publication_type']] ?? ucfirst($publication['publication_type']);
        
        // Convert file paths to public URLs using FIXED method
        $fileUrl = !empty($publication['file_path']) ? $this->getPublicFilePath($publication['file_path']) : null;
        $thumbnailUrl = !empty($publication['thumbnail_path']) ? $this->getPublicFilePath($publication['thumbnail_path']) : null;
        
        // Increment view count
        $this->model->incrementViews($id);
        
        $data = [
            'publication' => $publication,
            'categories' => $categories,
            'categoryName' => $categoryName,
            'pubDate' => date('F j, Y', strtotime($publication['publication_date'])),
            'pubTypeLabel' => $pubTypeLabel,
            'keywordsArray' => !empty($publication['keywords']) ? 
                              array_map('trim', explode(',', $publication['keywords'])) : [],
            'related' => $this->model->getByCategory($publication['research_area'], 5),
            'fileUrl' => $fileUrl,
            'thumbnailUrl' => $thumbnailUrl,
            'pageTitle' => htmlspecialchars($publication['title']) . ' - FCT College of Nursing Sciences',
            'currentPage' => 'research'
        ];
        
        // Check if research-detail.php exists
        $viewPath = 'pages/research-detail.php';
        $fullPath = APP_PATH . '/views/' . $viewPath;
        
        if (file_exists($fullPath)) {
            $this->render('pages/research-detail', $data);
        } else {
            $data['showDetail'] = true;
            $this->render('pages/research', $data);
        }
    }
    
    /**
     * PUBLIC: Download research file
     */
    public function download($id)
    {
        $publication = $this->model->getById($id);
        
        if (!$publication || !$publication['is_published'] || empty($publication['file_path'])) {
            $this->flash('error', 'File not available for download');
            $this->redirect('/research');
        }
        
        // Get absolute file path
        $filePath = $this->getAbsolutePath($publication['file_path']);
        
        if (!file_exists($filePath)) {
            error_log("Download ERROR: File not found at path: $filePath");
            $this->flash('error', 'File not found on server');
            $this->redirect('/research/' . $id);
        }
        
        // Check file permissions
        if (!is_readable($filePath)) {
            error_log("Download ERROR: File not readable. Permissions: " . substr(sprintf('%o', fileperms($filePath)), -4));
            @chmod($filePath, 0644);
            
            if (!is_readable($filePath)) {
                $this->flash('error', 'File permission error. Contact administrator.');
                $this->redirect('/research/' . $id);
            }
        }
        
        // Increment download count
        $this->model->incrementDownloads($id);
        
        // Serve file for download
        $fileName = basename($filePath);
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        
        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        readfile($filePath);
        exit;
    }
    
    // ============================================================================
    // HELPER METHODS
    // ============================================================================
    
    /**
     * Get CSRF token that matches what the view expects
     */
    private function getCSRFTokenForView()
    {
        // Use the legacy method that matches what the view uses
        if (method_exists('Session', 'getCSRFToken')) {
            $token = Session::getCSRFToken();
            return $token;
        }
        
        // Fallback
        return $this->generateCsrfToken();
    }
    
    /**
     * Generate CSRF token (compatible with Controller logic)
     */
    private function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }
        
        // Generate new token
        $token = bin2hex(random_bytes(32));
        
        // Store with timestamp
        $_SESSION['csrf_tokens'][$token] = time();
        
        // Clean up old tokens (older than 1 hour)
        $this->cleanupOldCsrfTokens();
        
        return $token;
    }
    
    /**
     * Clean up old CSRF tokens
     */
    private function cleanupOldCsrfTokens()
    {
        if (isset($_SESSION['csrf_tokens'])) {
            foreach ($_SESSION['csrf_tokens'] as $token => $timestamp) {
                if (time() - $timestamp > 3600) {
                    unset($_SESSION['csrf_tokens'][$token]);
                }
            }
        }
    }
    
    /**
     * Convert file path to public URL
     */
    private function getPublicFilePath($filePath)
    {
        // If already a URL, return as is
        if (strpos($filePath, 'http') === 0) {
            return $filePath;
        }
        
        // If it's an absolute Windows path (C:\ or C:/)
        if (strpos($filePath, 'C:\\') === 0 || strpos($filePath, 'C:/') === 0) {
            // Extract just the filename
            $filename = basename($filePath);
            
            // Determine if it's a thumbnail or main file
            if (strpos($filePath, 'thumbnails') !== false || strpos($filename, 'thumbnail') !== false) {
                return BASE_URL . '/uploads/research/thumbnails/' . $filename;
            } else {
                return BASE_URL . '/uploads/research/' . $filename;
            }
        }
        
        // If it's an absolute Unix path starting with /
        if (strpos($filePath, '/') === 0) {
            // Check if it contains 'uploads' directory
            if (strpos($filePath, 'uploads') !== false) {
                // Extract everything from 'uploads' onward
                $parts = explode('uploads', $filePath, 2);
                if (count($parts) > 1) {
                    return BASE_URL . '/uploads' . $parts[1];
                }
            }
            
            // If we can't parse it, just use the filename
            $filename = basename($filePath);
            if (strpos($filePath, 'thumbnails') !== false) {
                return BASE_URL . '/uploads/research/thumbnails/' . $filename;
            } else {
                return BASE_URL . '/uploads/research/' . $filename;
            }
        }
        
        // If it's a relative path (like "research/filename.pdf" or "research/thumbnails/filename.jpg")
        if (strpos($filePath, 'research/thumbnails/') === 0) {
            return BASE_URL . '/uploads/' . $filePath;
        } elseif (strpos($filePath, 'research/') === 0) {
            return BASE_URL . '/uploads/' . $filePath;
        } elseif (strpos($filePath, 'thumbnails/') === 0) {
            return BASE_URL . '/uploads/research/' . $filePath;
        }
        
        // Default: assume it's a filename in the research directory
        $filename = basename($filePath);
        if (strpos($filename, 'thumbnail') !== false) {
            return BASE_URL . '/uploads/research/thumbnails/' . $filename;
        } else {
            return BASE_URL . '/uploads/research/' . $filename;
        }
    }
    
    /**
     * Convert stored file path to absolute path on server
     */
    private function getAbsolutePath($filePath)
    {
        // If it's already an absolute path, return it
        if (strpos($filePath, 'C:\\') === 0 || strpos($filePath, 'C:/') === 0 || strpos($filePath, '/') === 0) {
            return $filePath;
        }
        
        // If it's a relative path starting with research/
        if (strpos($filePath, 'research/thumbnails/') === 0) {
            return UPLOADS_PATH . '/' . $filePath;
        } elseif (strpos($filePath, 'research/') === 0) {
            return UPLOADS_PATH . '/' . $filePath;
        } elseif (strpos($filePath, 'thumbnails/') === 0) {
            return UPLOADS_PATH . '/research/' . $filePath;
        }
        
        // Default: assume it's just a filename
        $filename = basename($filePath);
        
        // Check if it's likely a thumbnail
        if (strpos($filename, 'thumbnail') !== false) {
            return $this->thumbnailDir . $filename;
        } else {
            return $this->uploadDir . $filename;
        }
    }
    
    /**
     * Validate publication data
     */
    private function validatePublication($data, $id = null)
    {
        $errors = [];
        
        // Required fields
        if (empty(trim($data['title'] ?? ''))) {
            $errors['title'] = 'Title is required';
        } elseif (strlen($data['title']) > 500) {
            $errors['title'] = 'Title must be less than 500 characters';
        }
        
        if (empty(trim($data['authors'] ?? ''))) {
            $errors['authors'] = 'At least one author is required';
        }
        
        if (empty(trim($data['abstract'] ?? ''))) {
            $errors['abstract'] = 'Abstract is required';
        } elseif (strlen($data['abstract']) < 50) {
            $errors['abstract'] = 'Abstract must be at least 50 characters';
        }
        
        if (empty($data['research_area'] ?? '')) {
            $errors['research_area'] = 'Research area is required';
        }
        
        if (empty($data['publication_date'] ?? '')) {
            $errors['publication_date'] = 'Publication date is required';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['publication_date'])) {
            $errors['publication_date'] = 'Invalid date format (YYYY-MM-DD required)';
        }
        
        return $errors;
    }
    
    /**
     * Prepare publication data from form
     */
    private function preparePublicationData($formData, $fileData = [])
    {
        // Proper checkbox handling
        $isPublished = isset($formData['is_published']) && $formData['is_published'] == '1' ? 1 : 0;
        $isFeatured = isset($formData['is_featured']) && $formData['is_featured'] == '1' ? 1 : 0;
        
        $data = [
            'title' => $formData['title'] ?? '',
            'authors' => $formData['authors'] ?? '',
            'abstract' => $formData['abstract'] ?? '',
            'publication_type' => $formData['publication_type'] ?? 'journal',
            'journal_name' => $formData['journal_name'] ?? null,
            'volume' => $formData['volume'] ?? null,
            'issue' => $formData['issue'] ?? null,
            'pages' => $formData['pages'] ?? null,
            'publisher' => $formData['publisher'] ?? null,
            'publication_date' => $formData['publication_date'] ?? date('Y-m-d'),
            'doi' => $formData['doi'] ?? null,
            'url' => $formData['url'] ?? null,
            'keywords' => $formData['keywords'] ?? null,
            'research_area' => $formData['research_area'] ?? '',
            'citations' => $formData['citations'] ?? 0,
            'impact_factor' => $formData['impact_factor'] ?? null,
            'is_featured' => $isFeatured,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? date('Y-m-d H:i:s') : null,
            'created_by' => $_SESSION['user_id'] ?? 1
        ];
        
        // Merge file data if provided
        if (!empty($fileData)) {
            $data = array_merge($data, $fileData);
        }
        
        return $data;
    }
    
    /**
     * Handle file uploads
     */
    private function handleFileUploads($existingPublication = null)
    {
        $fileData = [];
        
        // Handle research file upload
        if (isset($_FILES['research_file']) && $_FILES['research_file']['error'] === UPLOAD_ERR_OK && !empty($_FILES['research_file']['name'])) {
            $uploadResult = $this->uploadResearchFile($_FILES['research_file']);
            
            if ($uploadResult['success']) {
                $fileData['file_path'] = $uploadResult['file_path'];
                $fileData['file_size'] = $uploadResult['file_size'];
                $fileData['file_type'] = $uploadResult['file_type'];
                
                // Set proper file permissions
                @chmod($this->getAbsolutePath($uploadResult['file_path']), 0644);
            }
        }
        
        // Handle thumbnail upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK && !empty($_FILES['thumbnail']['name'])) {
            $uploadResult = $this->uploadThumbnail($_FILES['thumbnail']);
            
            if ($uploadResult['success']) {
                $fileData['thumbnail_path'] = $uploadResult['file_path'];
                @chmod($this->getAbsolutePath($uploadResult['file_path']), 0644);
            }
        }
        
        return $fileData;
    }
    
    /**
     * Upload research file
     */
    private function uploadResearchFile($file)
    {
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $maxSize = 10 * 1024 * 1024; // 10MB
        
        $result = $this->uploadFile($file, $this->uploadDir, $allowedTypes, $maxSize);
        
        // If successful, store as relative path
        if ($result['success'] && !empty($result['file_path'])) {
            // Convert to relative path from uploads directory
            $uploadsPath = realpath(UPLOADS_PATH);
            if ($uploadsPath && strpos($result['file_path'], $uploadsPath) === 0) {
                $relativePath = str_replace($uploadsPath . DIRECTORY_SEPARATOR, '', $result['file_path']);
                $result['file_path'] = str_replace('\\', '/', $relativePath);
            } else {
                // Store just the filename
                $filename = basename($result['file_path']);
                $result['file_path'] = 'research/' . $filename;
            }
        }
        
        return $result;
    }
    
    /**
     * Upload thumbnail
     */
    private function uploadThumbnail($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        $result = $this->uploadFile($file, $this->thumbnailDir, $allowedTypes, $maxSize, true);
        
        // If successful, store as relative path
        if ($result['success'] && !empty($result['file_path'])) {
            // Convert to relative path from uploads directory
            $uploadsPath = realpath(UPLOADS_PATH);
            if ($uploadsPath && strpos($result['file_path'], $uploadsPath) === 0) {
                $relativePath = str_replace($uploadsPath . DIRECTORY_SEPARATOR, '', $result['file_path']);
                $result['file_path'] = str_replace('\\', '/', $relativePath);
            } else {
                // Store just the filename
                $filename = basename($result['file_path']);
                $result['file_path'] = 'research/thumbnails/' . $filename;
            }
        }
        
        return $result;
    }
    
    /**
     * Generic file upload function - FIXED VERSION (Simple)
     */
    private function uploadFile($file, $directory, $allowedTypes, $maxSize, $isImage = false)
    {
        $result = [
            'success' => false,
            'error' => '',
            'file_path' => null,
            'file_size' => null,
            'file_type' => null
        ];
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $result['error'] = 'File upload failed with error code: ' . $file['error'];
            return $result;
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $result['error'] = 'File size exceeds maximum allowed size';
            return $result;
        }
        
        // FIXED: Use multiple methods to get MIME type
        $fileType = null;
        
        // Method 1: Use finfo if available (more reliable)
        if (function_exists('finfo_open')) {
            $finfo = @finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $fileType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
            }
        }
        
        // Method 2: Use mime_content_type if available
        if (empty($fileType) && function_exists('mime_content_type')) {
            $fileType = @mime_content_type($file['tmp_name']);
        }
        
        // Method 3: Fallback to file extension
        if (empty($fileType)) {
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $mimeTypes = [
                // Images
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'bmp' => 'image/bmp',
                
                // Documents
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'ppt' => 'application/vnd.ms-powerpoint',
                'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'txt' => 'text/plain',
                'csv' => 'text/csv',
            ];
            
            $fileType = $mimeTypes[$extension] ?? 'application/octet-stream';
            
            // Log the fallback for debugging
            error_log("MIME type fallback used: Extension .$extension -> $fileType");
        }
        
        // Validate the detected MIME type
        if (!in_array($fileType, $allowedTypes)) {
            $result['error'] = "Invalid file type ($fileType). Allowed types: " . implode(', ', $allowedTypes);
            return $result;
        }
        
        // For images, do additional validation
        if ($isImage) {
            $imageInfo = @getimagesize($file['tmp_name']);
            if (!$imageInfo) {
                $result['error'] = 'Uploaded file is not a valid image';
                return $result;
            }
        }
        
        // Generate unique filename
        $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . preg_replace('/[^a-z0-9]/i', '_', $originalName) . '.' . $extension;
        $filePath = $directory . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $result['success'] = true;
            $result['file_path'] = $filePath;
            $result['file_size'] = $file['size'];
            $result['file_type'] = $fileType;
            
            // Set file permissions
            @chmod($filePath, 0644);
        } else {
            $result['error'] = 'Failed to move uploaded file';
        }
        
        return $result;
    }
}