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

// Get next display order
$nextOrder = 1;
try {
    require_once APP_PATH . '/models/CarouselModel.php';
    $carouselModel = new CarouselModel();
    $nextOrder = $carouselModel->getNextDisplayOrder();
} catch (Exception $e) {
    error_log("Carousel create error: " . $e->getMessage());
}

// Get flash messages
$flash_success = isset($_SESSION['flash_success']) ? $_SESSION['flash_success'] : null;
$flash_error = isset($_SESSION['flash_error']) ? $_SESSION['flash_error'] : null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// Get form data from session if exists (for repopulating after error)
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [
    'title' => '',
    'subtitle' => '',
    'image_path' => '',
    'button_text' => '',
    'button_link' => '',
    'display_order' => $nextOrder,
    'is_active' => 1
];
unset($_SESSION['form_data']);

// Generate CSRF Token
require_once APP_PATH . '/core/Security.php';
$csrf_token = Security::generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Carousel Slide - FCT College of Nursing Sciences</title>
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
            font-size: 1rem;
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
            margin-bottom: 0.125rem;
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
            font-weight: 600;
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
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--admin-gray-200);
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
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
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
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
                <h1>Add New Carousel Slide</h1>
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
                
                <!-- Page Header -->
                <div class="page-header" style="margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--admin-gray-200);">
                    <h1 style="font-size: 1.75rem; font-weight: 600; color: var(--admin-gray-800); margin-bottom: 0.5rem;">
                        Add New Carousel Slide
                    </h1>
                    <p style="color: var(--admin-gray-600); font-size: 1rem;">
                        Create a new slide for the homepage carousel
                    </p>
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
                            Image preview will appear here
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
                    <form action="<?php echo BASE_URL; ?>/admin/carousel/store" 
                          method="POST" 
                          id="slideForm"
                          enctype="multipart/form-data">
                        
                        <!-- CSRF Token Field - CRITICAL FOR SECURITY -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                        
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
                                Carousel Image *
                            </label>
                            
                            <p class="image-upload-help" style="color: var(--admin-gray-600); font-size: 0.875rem; margin-bottom: 0.5rem;">
                                Recommended: 1920×500 pixels, JPG or PNG format, max 2MB
                            </p>
                            
                            <!-- File Upload Input -->
                            <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                                <div style="position: relative; flex: 1;">
                                    <input type="file" 
                                           id="carousel_image" 
                                           name="carousel_image" 
                                           accept="image/jpeg,image/png,image/webp,image/gif"
                                           class="file-input"
                                           style="width: 100%; padding: 0.75rem; border: 2px dashed var(--admin-gray-300); border-radius: 6px; background: var(--admin-gray-50); cursor: pointer;"
                                           required>
                                    
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none; text-align: center;">
                                        <svg width="40" height="40" fill="var(--admin-gray-500)" viewBox="0 0 20 20" style="margin-bottom: 0.5rem;">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM8.293 9.293a1 1 0 011.414 0L11 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        <span style="color: var(--admin-gray-600); font-size: 0.875rem;">
                                            Click to upload or drag and drop
                                        </span>
                                        <br>
                                        <span style="color: var(--admin-gray-500); font-size: 0.75rem;">
                                            JPG, PNG, WebP up to 2MB
                                        </span>
                                    </div>
                                </div>
                                
                                <div style="flex-shrink: 0;">
                                    <button type="button" 
                                            class="clear-btn"
                                            style="padding: 0.75rem 1.5rem; background: var(--admin-gray-200); color: var(--admin-gray-700); border: none; border-radius: 6px; cursor: pointer; font-weight: 500;"
                                            onclick="clearImage()"
                                            id="clearBtn"
                                            style="display: none;">
                                        Clear
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Image Preview -->
                            <div id="imagePreviewContainer" style="margin-top: 1rem; display: none;">
                                <p style="margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--admin-gray-700);">
                                    Image Preview:
                                </p>
                                <div style="position: relative; display: inline-block;">
                                    <img id="imagePreview" 
                                         src="" 
                                         alt="Preview" 
                                         style="max-width: 300px; max-height: 150px; border-radius: 6px; border: 1px solid var(--admin-gray-200);">
                                </div>
                                <div id="fileInfo" style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--admin-gray-600);"></div>
                            </div>
                            
                            <div class="form-help">
                                The image will be uploaded and processed when you submit the form
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
                            <a href="<?php echo BASE_URL; ?>/admin/carousel" class="btn btn-outline">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Create Slide
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Help Section -->
                <div style="margin-top: 2rem; padding: 1.5rem; background: var(--admin-gray-50); border-radius: 8px;">
                    <h4 style="margin-bottom: 0.75rem; color: var(--admin-gray-700); font-size: 0.875rem;">Image Guidelines</h4>
                    <ul style="color: var(--admin-gray-600); font-size: 0.875rem; margin: 0; padding-left: 1.25rem;">
                        <li>Recommended image size: 1920×500 pixels</li>
                        <li>Use high-quality images with good contrast</li>
                        <li>Images should not contain important details at the edges</li>
                        <li>File formats: JPG, PNG, or WebP</li>
                        <li>Keep file sizes under 500KB for faster loading</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        // Simple image preview - NO AJAX
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
            
            // Show clear button
            document.getElementById('clearBtn').style.display = 'block';
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('imagePreview');
                if (previewImg) {
                    previewImg.src = e.target.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                }
                
                // Update the background preview too
                const previewBg = document.getElementById('previewBg');
                if (previewBg) {
                    previewBg.style.backgroundImage = `url('${e.target.result}')`;
                    previewBg.classList.add('has-image');
                    previewBg.innerHTML = '';
                }
                
                // Show file info
                const fileInfo = document.getElementById('fileInfo');
                if (fileInfo) {
                    const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                    fileInfo.textContent = `${file.name} (${sizeInMB} MB)`;
                }
            };
            reader.readAsDataURL(file);
        });
        
        // Clear image
        function clearImage() {
            document.getElementById('carousel_image').value = '';
            document.getElementById('imagePreviewContainer').style.display = 'none';
            document.getElementById('clearBtn').style.display = 'none';
            document.getElementById('previewBg').style.backgroundImage = '';
            document.getElementById('previewBg').classList.remove('has-image');
            document.getElementById('previewBg').innerHTML = 'Image preview will appear here';
        }
        
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
        
        // Simple form validation and loading state
        document.getElementById('slideForm').addEventListener('submit', function(e) {
            // Basic validation
            const title = document.getElementById('title').value.trim();
            const subtitle = document.getElementById('subtitle').value.trim();
            const imageInput = document.getElementById('carousel_image');
            
            if (!title) {
                e.preventDefault();
                alert('Please enter a title');
                return;
            }
            
            if (!subtitle) {
                e.preventDefault();
                alert('Please enter a subtitle');
                return;
            }
            
            if (!imageInput.files || imageInput.files.length === 0) {
                e.preventDefault();
                alert('Please select an image');
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="animate-spin"><path d="M10 3v2a5 5 0 00-5 5H3a7 7 0 017-7z"/></svg> Creating Slide...';
                submitBtn.disabled = true;
            }
        });
        
        // Add some CSS for animation
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