<?php
/**
 * Admin Carousel Controller
 * Handles CRUD operations for carousel slides in admin panel
 */
class AdminCarouselController extends Controller {
    private $carouselModel;
    private $uploadDir;
    private $webPath;
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private $maxFileSize = 2 * 1024 * 1024; // 2MB
    
    public function __construct() {
        parent::__construct();
        
        $this->layout = null;
        
        // ============================================
        // CRITICAL: Set correct upload paths
        // ============================================
        
        // Your structure: C:/xampp/htdocs/fctcns-website/public/assets/images/carousel/
        // Your URLs use: /assets/images/carousel/ (because .htaccess redirects to public)
        
        // Filesystem path where images will be stored
        $this->uploadDir = PUBLIC_PATH . '/assets/images/carousel/';
        
        // Web path stored in database (matches what frontend uses)
        $this->webPath = '/assets/images/carousel/';
        
        // Debug logging
        error_log("=== CAROUSEL UPLOAD PATHS ===");
        error_log("Public Path: " . PUBLIC_PATH);
        error_log("Upload Dir: " . $this->uploadDir);
        error_log("Web Path: " . $this->webPath);
        error_log("Directory exists: " . (is_dir($this->uploadDir) ? 'Yes' : 'No'));
        error_log("Is writable: " . (is_writable($this->uploadDir) ? 'Yes' : 'No'));
        
        // Create directory if it doesn't exist
        if (!is_dir($this->uploadDir)) {
            error_log("Creating directory: " . $this->uploadDir);
            if (!mkdir($this->uploadDir, 0755, true)) {
                error_log("FAILED to create directory: " . $this->uploadDir);
                throw new Exception("Failed to create upload directory: " . $this->uploadDir);
            }
            error_log("Directory created successfully");
        }
        
        // Initialize model
        require_once APP_PATH . '/models/CarouselModel.php';
        $this->carouselModel = new CarouselModel();
        
        // Check admin/editor permissions
        $this->checkAdminAccess();
    }
    
    /**
     * Check if user has admin/editor access
     */
    private function checkAdminAccess() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            $this->redirect('/admin/login');
        }
        
        $allowedRoles = ['admin', 'editor'];
        if (!in_array($_SESSION['user_role'], $allowedRoles)) {
            $this->redirect('/admin/dashboard');
        }
    }
    
    /**
     * Validate image upload
     */
    private function validateUpload($file) {
        $errors = [];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = $this->getUploadErrorMessage($file['error']);
            return $errors;
        }
        
        // Validate file size
        if ($file['size'] > $this->maxFileSize) {
            $errors[] = 'File size exceeds maximum limit of 2MB.';
        }
        
        // Validate file type using both MIME and extension
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $this->allowedTypes)) {
            $errors[] = 'Invalid file type. Only JPG, PNG, WebP, and GIF are allowed.';
        }
        
        // Additional extension check
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($extension, $allowedExtensions)) {
            $errors[] = 'Invalid file extension.';
        }
        
        // Verify it's actually an image
        if (!getimagesize($file['tmp_name'])) {
            $errors[] = 'File is not a valid image.';
        }
        
        return $errors;
    }
    
    /**
     * Get human-readable upload error message
     */
    private function getUploadErrorMessage($errorCode) {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive in HTML form.',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.'
        ];
        
        return $uploadErrors[$errorCode] ?? 'Unknown upload error.';
    }
    
    /**
     * Handle image upload via AJAX
     */
    public function uploadImage() {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_FILES['carousel_image'])) {
                throw new Exception('No file uploaded.');
            }
            
            $file = $_FILES['carousel_image'];
            
            // Validate upload
            $errors = $this->validateUpload($file);
            if (!empty($errors)) {
                throw new Exception(implode(' ', $errors));
            }
            
            // Generate unique filename with sanitization
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $filename = 'slide_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $destination = $this->uploadDir . $filename;
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                throw new Exception('Failed to save uploaded file.');
            }
            
            // Set proper permissions
            chmod($destination, 0644);
            
            // Return path using webPath
            $imagePath = $this->webPath . $filename;
            
            echo json_encode([
                'success' => true,
                'image_path' => $imagePath,
                'filename' => $filename,
                'message' => 'Image uploaded successfully.'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
    
    /**
     * Handle file upload (non-AJAX)
     */
    private function handleFileUpload($file) {
        error_log("=== STARTING FILE UPLOAD ===");
        error_log("File name: " . $file['name']);
        error_log("File size: " . $file['size']);
        error_log("File temp: " . $file['tmp_name']);
        error_log("File error: " . $file['error']);
        error_log("Upload dir: " . $this->uploadDir);
        
        // Validate upload
        $errors = $this->validateUpload($file);
        if (!empty($errors)) {
            error_log("Validation errors: " . implode(', ', $errors));
            throw new Exception(implode(' ', $errors));
        }
        
        // Check directory permissions
        if (!is_writable($this->uploadDir)) {
            error_log("Upload directory is not writable: " . $this->uploadDir);
            error_log("Directory permissions: " . substr(sprintf('%o', fileperms($this->uploadDir)), -4));
            throw new Exception('Upload directory is not writable. Please check permissions.');
        }
        
        // Generate unique filename
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'slide_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = $this->uploadDir . $filename;
        
        error_log("Generated filename: " . $filename);
        error_log("Destination: " . $destination);
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            error_log("FAILED to move uploaded file");
            error_log("Temp file exists: " . (file_exists($file['tmp_name']) ? 'Yes' : 'No'));
            error_log("Is uploaded: " . (is_uploaded_file($file['tmp_name']) ? 'Yes' : 'No'));
            throw new Exception('Failed to save uploaded file. Please try again.');
        }
        
        error_log("File moved successfully to: " . $destination);
        
        // Set proper permissions
        chmod($destination, 0644);
        
        $webPath = $this->webPath . $filename;
        error_log("Web path to return: " . $webPath);
        error_log("=== FILE UPLOAD COMPLETE ===");
        
        return $webPath;
    }
    
    /**
     * Delete image file safely
     */
    private function deleteImageFile($imagePath) {
        if (empty($imagePath)) {
            return false;
        }
        
        // Security: Ensure path contains carousel directory
        if (strpos($imagePath, '/assets/images/carousel/') === false) {
            error_log("Attempted to delete file outside carousel directory: " . $imagePath);
            return false;
        }
        
        $filename = basename($imagePath);
        $filepath = $this->uploadDir . $filename;
        
        // Additional security: ensure no path traversal
        if (realpath(dirname($filepath)) !== realpath($this->uploadDir)) {
            error_log("Path traversal attempt detected: " . $filepath);
            return false;
        }
        
        if (file_exists($filepath) && is_file($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }
    
    /**
     * Save form data to session
     */
    private function saveFormData($data) {
        $_SESSION['form_data'] = $data;
    }
    
    /**
     * List all carousel slides
     */
    public function index() {
        $slides = $this->carouselModel->getAllSlides();
        
        // Get flash messages
        $flash_success = $_SESSION['flash_success'] ?? '';
        $flash_error = $_SESSION['flash_error'] ?? '';
        
        // Clear flash messages
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
        
        $this->data = [
            'slides' => $slides,
            'flash_success' => $flash_success,
            'flash_error' => $flash_error,
            'userRole' => $_SESSION['user_role'] ?? '',
            'username' => $_SESSION['username'] ?? ''
        ];
        
        $this->render('admin/carousel/index');
    }
    
    /**
     * Show create form
     */
    public function create() {
        $nextOrder = $this->carouselModel->getNextDisplayOrder();
        
        // Get flash messages
        $flash_error = $_SESSION['flash_error'] ?? '';
        
        // Get form data (for repopulation after error)
        $formData = $_SESSION['form_data'] ?? [
            'title' => '',
            'subtitle' => '',
            'image_path' => '',
            'button_text' => '',
            'button_link' => '',
            'display_order' => $nextOrder,
            'is_active' => 1
        ];
        
        // Clear session data
        unset($_SESSION['flash_error'], $_SESSION['form_data']);
        
        $this->data = [
            'flash_error' => $flash_error,
            'formData' => $formData,
            'nextOrder' => $nextOrder,
            'userRole' => $_SESSION['user_role'] ?? '',
            'username' => $_SESSION['username'] ?? ''
        ];
        
        $this->render('admin/carousel/create');
    }
    
    /**
     * Store new slide
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/carousel');
            exit;
        }
        
        try {
            // Prepare form data
            $formData = [
                'title' => trim($_POST['title'] ?? ''),
                'subtitle' => trim($_POST['subtitle'] ?? ''),
                'button_text' => trim($_POST['button_text'] ?? ''),
                'button_link' => trim($_POST['button_link'] ?? ''),
                'display_order' => intval($_POST['display_order'] ?? 1),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            // Save for repopulation if error occurs
            $this->saveFormData($formData);
            
            // Validate required fields
            if (empty($formData['title'])) {
                throw new Exception('Title is required.');
            }
            
            if (empty($formData['subtitle'])) {
                throw new Exception('Subtitle is required.');
            }
            
            // Handle image upload
            $imagePath = '';
            
            // Check for direct file upload (your form uses this)
            if (isset($_FILES['carousel_image']) && $_FILES['carousel_image']['error'] === UPLOAD_ERR_OK) {
                error_log("Processing direct file upload");
                $imagePath = $this->handleFileUpload($_FILES['carousel_image']);
            } 
            // No image provided
            else {
                $errorMsg = 'Please upload an image.';
                if (isset($_FILES['carousel_image'])) {
                    $errorMsg .= ' Error: ' . $this->getUploadErrorMessage($_FILES['carousel_image']['error']);
                }
                throw new Exception($errorMsg);
            }
            
            // Prepare data for database
            $data = array_merge($formData, ['image_path' => $imagePath]);
            
            // Save to database
            if ($this->carouselModel->createSlide($data)) {
                // Clear form data on success
                unset($_SESSION['form_data']);
                
                // Get the uploaded filename for the success message
                $filename = basename($imagePath);
                
                // Set detailed success message
                $_SESSION['flash_success'] = 
                    '✅ Carousel slide created successfully!<br>' .
                    '• Title: ' . htmlspecialchars($formData['title']) . '<br>' .
                    '• Image: ' . $filename . ' uploaded<br>' .
                    '• Status: ' . ($formData['is_active'] ? 'Active' : 'Inactive');
                
                header('Location: ' . BASE_URL . '/admin/carousel');
                exit;
            } else {
                // Delete uploaded file if database insert failed
                $this->deleteImageFile($imagePath);
                throw new Exception('Failed to save slide to database.');
            }
            
        } catch (Exception $e) {
            error_log("Carousel store error: " . $e->getMessage());
            $_SESSION['flash_error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/carousel/create');
            exit;
        }
    }
    
    /**
     * Show edit form
     */
    public function edit($id) {
        $slide = $this->carouselModel->getSlideById($id);
        
        if (!$slide) {
            $_SESSION['flash_error'] = 'Slide not found.';
            header('Location: ' . BASE_URL . '/admin/carousel');
            exit;
        }
        
        // Get flash messages
        $flash_error = $_SESSION['flash_error'] ?? '';
        
        // Use form data from session if exists, otherwise use slide data
        $formData = $_SESSION['form_data'] ?? $slide;
        
        // Clear session data
        unset($_SESSION['flash_error'], $_SESSION['form_data']);
        
        $this->data = [
            'slide' => $slide,
            'flash_error' => $flash_error,
            'formData' => $formData,
            'userRole' => $_SESSION['user_role'] ?? '',
            'username' => $_SESSION['username'] ?? ''
        ];
        
        $this->render('admin/carousel/edit');
    }
    
    /**
     * Update slide
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/carousel');
            exit;
        }
        
        try {
            $slide = $this->carouselModel->getSlideById($id);
            if (!$slide) {
                throw new Exception('Slide not found.');
            }
            
            // Save form data for repopulation
            $this->saveFormData($_POST);
            
            // Validate required fields
            if (empty($_POST['title'])) {
                throw new Exception('Title is required.');
            }
            
            if (empty($_POST['subtitle'])) {
                throw new Exception('Subtitle is required.');
            }
            
            // Handle image upload
            $imagePath = $slide['image_path']; // Default to existing
            $oldImagePath = $slide['image_path'];
            
            // Check if new image was uploaded via AJAX
            if (!empty($_POST['image_path']) && $_POST['image_path'] !== $oldImagePath) {
                $imagePath = $_POST['image_path'];
                
                // Verify the file exists
                $filename = basename($imagePath);
                $filepath = $this->uploadDir . $filename;
                if (!file_exists($filepath)) {
                    throw new Exception('Uploaded image file not found. Please try uploading again.');
                }
            } 
            // Check direct file upload
            elseif (isset($_FILES['carousel_image']) && $_FILES['carousel_image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleFileUpload($_FILES['carousel_image']);
            }
            
            // Prepare data
            $data = [
                'title' => trim($_POST['title']),
                'subtitle' => trim($_POST['subtitle']),
                'image_path' => $imagePath,
                'button_text' => trim($_POST['button_text'] ?? ''),
                'button_link' => trim($_POST['button_link'] ?? ''),
                'display_order' => intval($_POST['display_order'] ?? $slide['display_order']),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            // Update database
            if ($this->carouselModel->updateSlide($id, $data)) {
                // Delete old image if it changed
                if ($imagePath !== $oldImagePath) {
                    $this->deleteImageFile($oldImagePath);
                }
                
                unset($_SESSION['form_data']);
                
                // Set detailed success message for update
                $filename = basename($imagePath);
                $_SESSION['flash_success'] = 
                    '✅ Carousel slide updated successfully!<br>' .
                    '• Title: ' . htmlspecialchars($_POST['title']) . '<br>' .
                    '• Image: ' . ($imagePath !== $oldImagePath ? $filename . ' uploaded' : 'No change') . '<br>' .
                    '• Status: ' . (isset($_POST['is_active']) ? 'Active' : 'Inactive');
                
                header('Location: ' . BASE_URL . '/admin/carousel');
                exit;
            } else {
                // Delete new image if database update failed
                if ($imagePath !== $oldImagePath) {
                    $this->deleteImageFile($imagePath);
                }
                throw new Exception('Failed to update slide in database.');
            }
            
        } catch (Exception $e) {
            error_log("Carousel update error: " . $e->getMessage());
            $_SESSION['flash_error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/carousel/edit/' . $id);
            exit;
        }
    }
    
    /**
     * Delete slide
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/carousel');
            exit;
        }
        
        try {
            $slide = $this->carouselModel->getSlideById($id);
            if (!$slide) {
                throw new Exception('Slide not found.');
            }
            
            // Delete from database
            if ($this->carouselModel->deleteSlide($id)) {
                // Delete image file
                $this->deleteImageFile($slide['image_path']);
                
                // Set detailed success message for delete
                $_SESSION['flash_success'] = 
                    '✅ Carousel slide deleted successfully!<br>' .
                    '• Title: ' . htmlspecialchars($slide['title']) . ' removed<br>' .
                    '• Image file: ' . basename($slide['image_path']) . ' deleted';
            } else {
                throw new Exception('Failed to delete slide from database.');
            }
            
        } catch (Exception $e) {
            error_log("Carousel delete error: " . $e->getMessage());
            $_SESSION['flash_error'] = $e->getMessage();
        }
        
        header('Location: ' . BASE_URL . '/admin/carousel');
        exit;
    }
    
    /**
     * Toggle active status
     */
    public function toggle($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/carousel');
            exit;
        }
        
        try {
            $slide = $this->carouselModel->getSlideById($id);
            if (!$slide) {
                throw new Exception('Slide not found.');
            }
            
            $newStatus = $slide['is_active'] ? 0 : 1;
            
            if ($this->carouselModel->toggleActive($id)) {
                $_SESSION['flash_success'] = 
                    '✅ Slide status updated!<br>' .
                    '• Title: ' . htmlspecialchars($slide['title']) . '<br>' .
                    '• Status changed to: ' . ($newStatus ? 'Active' : 'Inactive');
            } else {
                throw new Exception('Failed to update slide status.');
            }
        } catch (Exception $e) {
            error_log("Carousel toggle error: " . $e->getMessage());
            $_SESSION['flash_error'] = $e->getMessage();
        }
        
        header('Location: ' . BASE_URL . '/admin/carousel');
        exit;
    }
}