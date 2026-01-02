<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 4); // Go up 3 levels from app/views/admin/carousel/

// Load constants first
require_once $rootPath . '/app/config/constants.php';

// Require authentication
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

// Include database
require_once APP_PATH . '/config/database.php';
$db = Database::getInstance();
$conn = $db->getConnection();

// Get user info
require_once APP_PATH . '/config/session.php';
$userRole = $_SESSION['user_role'];
$username = $_SESSION['username'];

// Get slide ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : (isset($id) ? $id : 0);
if (!$id) {
    header('Location: ' . BASE_URL . '/admin/carousel');
    exit;
}

// Get slide data
$slide = [];
$flash_error = null;
$formData = [];

try {
    require_once APP_PATH . '/models/CarouselModel.php';
    $carouselModel = new CarouselModel();
    $slide = $carouselModel->getSlideById($id);
    
    if (!$slide) {
        $flash_error = 'Slide not found.';
    }
} catch (Exception $e) {
    error_log("Carousel edit error: " . $e->getMessage());
    $flash_error = 'Error loading slide data.';
}

// Get flash messages
$flash_success = isset($_SESSION['flash_success']) ? $_SESSION['flash_success'] : null;
if (isset($_SESSION['flash_error'])) {
    $flash_error = $_SESSION['flash_error'];
}
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// Get form data from session if exists (for repopulating after error)
if (isset($_SESSION['form_data'])) {
    $formData = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
} else {
    $formData = $slide;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Carousel Slide - FCT College of Nursing Sciences</title>
    <style>
        /* Admin Dashboard Styles */
        :root {
            --admin-sidebar-width: 260px;
            --admin-header-height: 70px;
            --admin-primary: #2c5282;
            --admin-primary-dark: #1a365d;
            --admin-primary-light: #4299e1;
            --admin-success: #38a169;
            --admin-warning: #d69e2e;
            --admin-danger: #e53e3e;
            --admin-info: #3182ce;
            --admin-gray-50: #f7fafc;
            --admin-gray-100: #edf2f7;
            --admin-gray-200: #e2e8f0;
            --admin-gray-300: #cbd5e0;
            --admin-gray-600: #718096;
            --admin-gray-700: #4a5568;
            --admin-gray-800: #2d3748;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--admin-gray-100);
            color: var(--admin-gray-800);
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: white;
            border-right: 1px solid var(--admin-gray-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--admin-gray-200);
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--admin-gray-800);
        }
        
        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: var(--admin-primary);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.125rem;
        }
        
        .sidebar-title {
            font-weight: 600;
            font-size: 1rem; /* FIXED: = changed to : */
        }
        
        .sidebar-subtitle {
            font-size: 0.75rem;
            color: var(--admin-gray-600);
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }
        
        .nav-section {
            margin-bottom: 1.5rem;
        }
        
        .nav-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--admin-gray-600);
            padding: 0 1.5rem 0.5rem;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid var(--admin-gray-200);
        }
        
        .nav-links {
            list-style: none;
        }
        
        .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            color: var(--admin-gray-700);
            transition: all 0.2s;
            position: relative;
        }
        
        .nav-link:hover {
            background: var(--admin-gray-50);
            color: var(--admin-primary);
        }
        
        .nav-link.active {
            background: var(--admin-gray-50);
            color: var(--admin-primary);
            border-left: 3px solid var(--admin-primary);
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
        }
        
        /* Main Content Styles */
        .admin-main {
            flex: 1;
            margin-left: var(--admin-sidebar-width);
            display: flex;
            flex-direction: column;
        }
        
        .admin-header {
            height: var(--admin-header-height);
            background: white;
            border-bottom: 1px solid var(--admin-gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .header-title h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--admin-gray-800);
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .notification-btn, .logout-btn {
            background: none;
            border: none;
            color: var(--admin-gray-600);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .notification-btn:hover, .logout-btn:hover {
            background: var(--admin-gray-100);
            color: var(--admin-gray-800);
        }
        
        .admin-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        
        /* User Profile */
        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--admin-gray-200);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--admin-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .user-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.125rem; /* FIXED: = changed to : */
        }
        
        .user-info span {
            font-size: 0.75rem;
            color: var(--admin-gray-600);
        }
        
        /* Form Styles */
        .form-container {
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            color: var(--admin-gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--admin-gray-200);
            border-radius: 6px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }
        
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        
        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-input.error, .form-textarea.error, .form-select.error {
            border-color: var(--admin-danger);
        }
        
        .error-message {
            color: var(--admin-danger);
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .form-help {
            color: var(--admin-gray-600);
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-checkbox {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1px solid var(--admin-gray-300);
            cursor: pointer;
        }
        
        .form-checkbox:checked {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
        }
        
        .preview-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--admin-gray-50);
            border-radius: 8px;
            border: 1px dashed var(--admin-gray-300);
        }
        
        .preview-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--admin-gray-700);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .slide-preview-large {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: relative;
            min-height: 200px;
        }
        
        .slide-preview-bg {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #6B4E9B, #7FB285);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.875rem;
        }
        
        .slide-preview-bg.has-image {
            background-size: cover;
            background-position: center;
        }
        
        .slide-preview-content {
            padding: 1.5rem;
            text-align: center;
        }
        
        .preview-title-text {
            font-size: 1.5rem;
            font-weight: 600; /* FIXED: = changed to : */
            color: var(--admin-gray-800);
            margin-bottom: 0.5rem;
        }
        
        .preview-subtitle {
            color: var(--admin-gray-600);
            margin-bottom: 1rem;
        }
        
        .preview-button {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: white;
            color: #6B4E9B;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            border: 2px solid #6B4E9B;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--admin-gray-200);
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px; /* FIXED: = changed to : */
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background: var(--admin-primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--admin-primary-dark);
            transform: translateY(-1px);
        }
        
        .btn-outline {
            background: white;
            color: var(--admin-gray-700);
            border: 1px solid var(--admin-gray-300);
        }
        
        .btn-outline:hover {
            background: var(--admin-gray-50);
            border-color: var(--admin-gray-400);
        }
        
        .btn-danger {
            background: var(--admin-danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #c53030;
        }
        
        .alert-error {
            background: rgba(229, 62, 62, 0.1);
            border: 1px solid rgba(229, 62, 62, 0.2);
            color: var(--admin-danger);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: rgba(56, 161, 105, 0.1);
            border: 1px solid rgba(56, 161, 105, 0.2);
            color: var(--admin-success);
            padding: 1rem;
            border-radius: 8px; /* FIXED: = changed to : */
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .delete-section {
            background: rgba(229, 62, 62, 0.05);
            border: 1px solid rgba(229, 62, 62, 0.2);
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .delete-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--admin-danger);
            margin-bottom: 0.75rem;
            font-weight: 600;
        }
        
        .delete-warning {
            color: var(--admin-gray-600);
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .modal h3 {
            margin-bottom: 1rem;
            color: var(--admin-gray-800);
        }
        
        .modal p {
            margin-bottom: 2rem;
            color: var(--admin-gray-600);
        }
        
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        
        /* Additional styles for consistency */
        .current-image {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--admin-gray-50);
            border-radius: 8px; /* FIXED: = changed to : */
            border: 1px solid var(--admin-gray-200);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="sidebar-brand">
                <div class="sidebar-logo">FCT</div>
                <div>
                    <div class="sidebar-title">FCT CNS</div>
                    <div class="sidebar-subtitle">Admin Portal</div>
                </div>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">
                <h3 class="nav-section-title">Main</h3>
                <ul class="nav-links">
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="nav-section">
                <h3 class="nav-section-title">Management</h3>
                <ul class="nav-links">
                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                    <!-- Carousel Slides Link - ACTIVE -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/carousel" class="nav-link active">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>Carousel Slides</span>
                        </a>
                    </li>
                    
                    <!-- Other management links -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/applications" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            <span>Applications</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($username, 0, 2)); ?>
                </div>
                <div class="user-info">
                    <h4><?php echo htmlspecialchars($username); ?></h4>
                    <span><?php echo ucfirst($userRole); ?></span>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-title">
                <h1>Edit Carousel Slide</h1>
            </div>
            <div class="header-actions">
                <button class="notification-btn" title="Notifications">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                </button>
                <a href="<?php echo BASE_URL; ?>/admin/logout" class="logout-btn" title="Logout">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </header>
        
        <!-- Content -->
        <div class="admin-content">
            <?php if (!$slide): ?>
                <div class="alert-error">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Slide not found. <a href="<?php echo BASE_URL; ?>/admin/carousel" style="color: inherit; text-decoration: underline;">Return to carousel slides</a>
                </div>
            <?php else: ?>
                <div class="form-container">
                    <!-- Flash Messages -->
                    <?php if ($flash_success || $flash_error): ?>
                    <div class="flash-messages">
                        <?php if ($flash_success): ?>
                        <div class="alert alert-success">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo htmlspecialchars($flash_success); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($flash_error): ?>
                        <div class="alert alert-error">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo htmlspecialchars($flash_error); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Page Header with Slide ID -->
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                        <div>
                            <h2 style="font-size: 1.75rem; font-weight: 600; color: var(--admin-gray-800); margin-bottom: 0.5rem;">
                                Edit Carousel Slide
                            </h2>
                            <p style="color: var(--admin-gray-600); font-size: 1rem;">
                                Update slide #<?php echo $slide['id']; ?>: <?php echo htmlspecialchars($slide['title']); ?>
                            </p>
                        </div>
                        <div style="background: var(--admin-gray-50); padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem; color: var(--admin-gray-600);">
                            ID: <?php echo $slide['id']; ?>
                        </div>
                    </div>
                    
                    <!-- Live Preview -->
                    <div class="preview-section">
                        <div class="preview-title">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            Live Preview
                        </div>
                        <div class="slide-preview-large">
                            <div class="slide-preview-bg <?php echo !empty($formData['image_path']) ? 'has-image' : ''; ?>" 
                                 id="previewBg" 
                                 style="<?php echo !empty($formData['image_path']) ? 'background-image: url(\'' . BASE_URL . $formData['image_path'] . '\')' : ''; ?>">
                                <?php if (empty($formData['image_path'])): ?>
                                No image set
                                <?php endif; ?>
                            </div>
                            <div class="slide-preview-content">
                                <h3 class="preview-title-text" id="previewTitle">
                                    <?php echo !empty($formData['title']) ? htmlspecialchars($formData['title']) : 'Slide Title'; ?>
                                </h3>
                                <p class="preview-subtitle" id="previewSubtitle">
                                    <?php echo !empty($formData['subtitle']) ? htmlspecialchars($formData['subtitle']) : 'Slide subtitle text will appear here'; ?>
                                </p>
                                <?php if (!empty($formData['button_text'])): ?>
                                <a href="#" class="preview-button" id="previewButton">
                                    <?php echo htmlspecialchars($formData['button_text']); ?>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form -->
                    <div class="form-card">
                        <form action="<?php echo BASE_URL; ?>/admin/carousel/update/<?php echo $slide['id']; ?>" 
                              method="POST" 
                              id="slideForm"
                              enctype="multipart/form-data">
                            
                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $this->data['csrf_token'] ?? ''; ?>">
                            
                            <!-- Title -->
                            <div class="form-group">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" 
                                       id="title" 
                                       name="title" 
                                       class="form-input" 
                                       value="<?php echo htmlspecialchars($formData['title']); ?>"
                                       placeholder="Enter slide title"
                                       maxlength="100"
                                       required>
                                <div class="form-help">Main headline for the slide (max 100 characters)</div>
                            </div>
                            
                            <!-- Subtitle -->
                            <div class="form-group">
                                <label for="subtitle" class="form-label">Subtitle *</label>
                                <textarea id="subtitle" 
                                          name="subtitle" 
                                          class="form-textarea" 
                                          placeholder="Enter slide subtitle"
                                          maxlength="200"
                                          required><?php echo htmlspecialchars($formData['subtitle']); ?></textarea>
                                <div class="form-help">Supporting text for the slide (max 200 characters)</div>
                            </div>
                            
                            <!-- Image Upload Section -->
                            <div class="form-group">
                                <label class="form-label" style="display: block; font-weight: 500; color: var(--admin-gray-700); margin-bottom: 0.5rem;">
                                    Carousel Image (Leave empty to keep current)
                                </label>
                                
                                <p class="image-upload-help" style="color: var(--admin-gray-600); font-size: 0.875rem; margin-bottom: 0.5rem;">
                                    Recommended: 1920×500 pixels, JPG or PNG format, max 2MB
                                </p>
                                
                                <!-- Current Image Display -->
                                <?php if (!empty($slide['image_path'])): ?>
                                <div class="current-image">
                                    <p style="margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--admin-gray-700);">
                                        Current Image:
                                    </p>
                                    <img src="<?php echo BASE_URL . $slide['image_path']; ?>" 
                                         alt="Current slide image"
                                         style="max-width: 300px; max-height: 150px; border-radius: 6px;"
                                         onerror="this.style.display='none'; this.parentNode.innerHTML='<p style=\"color:var(--admin-danger);\">Image not found at: <?php echo htmlspecialchars($slide['image_path']); ?></p>';">
                                    <p style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--admin-gray-600);">
                                        <?php echo $slide['image_path']; ?>
                                    </p>
                                </div>
                                
                                <!-- Hidden field for existing image -->
                                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($slide['image_path']); ?>">
                                <?php endif; ?>
                                
                                <!-- File Upload Input -->
                                <div style="display: flex; gap: 1rem; align-items: center; margin-top: 1rem;">
                                    <div style="position: relative; flex: 1;">
                                        <input type="file" 
                                               id="carousel_image" 
                                               name="carousel_image" 
                                               accept="image/jpeg,image/png,image/webp,image/gif"
                                               class="file-input"
                                               style="width: 100%; padding: 0.75rem; border: 2px dashed var(--admin-gray-300); border-radius: 6px; background: var(--admin-gray-50); cursor: pointer;">
                                        
                                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none; text-align: center;">
                                            <svg width="40" height="40" fill="var(--admin-gray-500)" viewBox="0 0 20 20" style="margin-bottom: 0.5rem;">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM8.293 9.293a1 1 0 011.414 0L11 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            <span style="color: var(--admin-gray-600); font-size: 0.875rem;">
                                                Click to upload new image
                                            </span>
                                            <br>
                                            <span style="color: var(--admin-gray-500); font-size: 0.75rem;">
                                                JPG, PNG, WebP up to 2MB
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Image Preview for new upload -->
                                <div id="imagePreviewContainer" style="margin-top: 1rem; display: none;">
                                    <p style="margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--admin-gray-700);">
                                        New Image Preview:
                                    </p>
                                    <img id="imagePreview" 
                                         src="" 
                                         alt="Preview" 
                                         style="max-width: 300px; max-height: 150px; border-radius: 6px; border: 1px solid var(--admin-gray-200);">
                                </div>
                                
                                <div class="form-help">
                                    Upload a new image to replace the current one, or leave empty to keep existing image
                                </div>
                            </div>
                            
                            <!-- Button Text -->
                            <div class="form-group">
                                <label for="button_text" class="form-label">Button Text (Optional)</label>
                                <input type="text" 
                                       id="button_text" 
                                       name="button_text" 
                                       class="form-input" 
                                       value="<?php echo htmlspecialchars($formData['button_text']); ?>"
                                       placeholder="e.g., Learn More, Apply Now, Read More"
                                       maxlength="50">
                                <div class="form-help">Text for the call-to-action button</div>
                            </div>
                            
                            <!-- Button Link -->
                            <div class="form-group">
                                <label for="button_link" class="form-label">Button Link (Optional)</label>
                                <input type="text" 
                                       id="button_link" 
                                       name="button_link" 
                                       class="form-input" 
                                       value="<?php echo htmlspecialchars($formData['button_link']); ?>"
                                       placeholder="/programs, /about, /contact"
                                       maxlength="200">
                                <div class="form-help">URL path for the button (relative to website root)</div>
                            </div>
                            
                            <!-- Display Order -->
                            <div class="form-group">
                                <label for="display_order" class="form-label">Display Order</label>
                                <input type="number" 
                                       id="display_order" 
                                       name="display_order" 
                                       class="form-input" 
                                       value="<?php echo htmlspecialchars($formData['display_order']); ?>"
                                       min="1"
                                       max="100"
                                       style="width: 100px;">
                                <div class="form-help">Determines the order of slides (1 = first slide)</div>
                            </div>
                            
                            <!-- Active Status -->
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="checkbox-group">
                                    <input type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           class="form-checkbox" 
                                           value="1" 
                                           <?php echo $formData['is_active'] ? 'checked' : ''; ?>>
                                    <label for="is_active">Active (visible on homepage)</label>
                                </div>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="form-actions">
                                <div>
                                    <a href="<?php echo BASE_URL; ?>/admin/carousel" class="btn btn-outline">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Update Slide
                                    </button>
                                </div>
                                <div>
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            onclick="showDeleteModal()">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Delete Slide
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Delete Section -->
                        <div class="delete-section">
                            <div class="delete-header">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Danger Zone
                            </div>
                            <p class="delete-warning">
                                Deleting this slide will remove it permanently from the database. 
                                This action cannot be undone.
                            </p>
                            <form action="<?php echo BASE_URL; ?>/admin/carousel/delete/<?php echo $slide['id']; ?>" 
                                  method="POST" 
                                  id="deleteForm"
                                  onsubmit="return confirm('Are you sure you want to delete this slide? This action cannot be undone.');">
                                <!-- CSRF Token for delete form -->
                                <input type="hidden" name="csrf_token" value="<?php echo $this->data['csrf_token'] ?? ''; ?>">
                                <button type="submit" class="btn btn-danger">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Permanently Delete This Slide
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Slide Metadata -->
                    <div style="margin-top: 2rem; padding: 1.5rem; background: var(--admin-gray-50); border-radius: 8px;">
                        <h4 style="margin-bottom: 0.75rem; color: var(--admin-gray-700); font-size: 0.875rem;">Slide Information</h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.875rem;">
                            <div>
                                <span style="color: var(--admin-gray-600);">Created:</span>
                                <span style="color: var(--admin-gray-800); font-weight: 500;">
                                    <?php echo date('F j, Y, g:i a', strtotime($slide['created_at'])); ?>
                                </span>
                            </div>
                            <div>
                                <span style="color: var(--admin-gray-600);">Last Updated:</span>
                                <span style="color: var(--admin-gray-800); font-weight: 500;">
                                    <?php echo date('F j, Y, g:i a', strtotime($slide['updated_at'])); ?>
                                </span>
                            </div>
                            <div>
                                <span style="color: var(--admin-gray-600);">Current Status:</span>
                                <span style="color: var(--admin-gray-800); font-weight: 500;">
                                    <?php echo $slide['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <h3>Confirm Delete</h3>
            <p>Are you sure you want to delete "<span id="deleteSlideTitle"><?php echo htmlspecialchars($slide['title'] ?? ''); ?></span>"? This action cannot be undone.</p>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="hideDeleteModal()">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Permanently</button>
            </div>
        </div>
    </div>
    
    <script>
        // Simple image preview
        document.getElementById('carousel_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, PNG, WebP, or GIF)');
                this.value = '';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('imagePreview');
                if (previewImg) {
                    previewImg.src = e.target.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                }
                
                // Update live preview
                const previewBg = document.getElementById('previewBg');
                if (previewBg) {
                    previewBg.style.backgroundImage = `url('${e.target.result}')`;
                    previewBg.classList.add('has-image');
                    previewBg.innerHTML = '';
                }
            };
            reader.readAsDataURL(file);
        });
        
        // Live preview for text fields
        document.getElementById('title').addEventListener('input', function() {
            document.getElementById('previewTitle').textContent = this.value || 'Slide Title';
        });
        
        document.getElementById('subtitle').addEventListener('input', function() {
            document.getElementById('previewSubtitle').textContent = this.value || 'Slide subtitle text will appear here';
        });
        
        document.getElementById('button_text').addEventListener('input', function() {
            const previewButton = document.getElementById('previewButton');
            const previewContent = document.querySelector('.slide-preview-content');
            
            if (this.value) {
                if (!previewButton) {
                    const button = document.createElement('a');
                    button.href = '#';
                    button.className = 'preview-button';
                    button.id = 'previewButton';
                    button.textContent = this.value;
                    previewContent.appendChild(button);
                } else {
                    previewButton.textContent = this.value;
                }
            } else if (previewButton) {
                previewButton.remove();
            }
        });
        
        // Simple form validation
        document.getElementById('slideForm').addEventListener('submit', function(e) {
            // Show loading
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="animate-spin"><path d="M10 3v2a5 5 0 00-5 5H3a7 7 0 017-7z"/></svg> Updating...';
                submitBtn.disabled = true;
            }
            // Let the form submit normally
        });
        
        // Delete modal functions
        function showDeleteModal() {
            document.getElementById('deleteModal').classList.add('active');
        }
        
        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        
        function confirmDelete() {
            document.getElementById('deleteForm').submit();
        }
        
        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
            }
        });
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Add CSS for animation
        const style = document.createElement('style');
        style.textContent = `
            .animate-spin {
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>