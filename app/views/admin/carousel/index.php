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

// Get carousel slides
$slides = [];
try {
    require_once APP_PATH . '/models/CarouselModel.php';
    $carouselModel = new CarouselModel();
    $slides = $carouselModel->getAllSlides();
} catch (Exception $e) {
    error_log("Carousel index error: " . $e->getMessage());
}

// Get flash messages
$flash_success = isset($_SESSION['flash_success']) ? $_SESSION['flash_success'] : null;
$flash_error = isset($_SESSION['flash_error']) ? $_SESSION['flash_error'] : null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Carousel Slides - FCT College of Nursing Sciences</title>
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
        
        /* Carousel Specific Styles */
        .carousel-admin-container {
            padding: 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--admin-gray-200);
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--admin-gray-800);
        }
        
        /* Flash Messages */
        .flash-messages {
            margin-bottom: 1.5rem;
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: rgba(56, 161, 105, 0.1);
            border: 1px solid rgba(56, 161, 105, 0.2);
            color: var(--admin-success);
        }
        
        .alert-error {
            background: rgba(229, 62, 62, 0.1);
            border: 1px solid rgba(229, 62, 62, 0.2);
            color: var(--admin-danger);
        }
        
        /* Buttons */
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
        }
        
        .btn-primary {
            background: var(--admin-primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--admin-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44, 82, 130, 0.2);
        }
        
        .btn-outline {
            background: white;
            color: var(--admin-primary);
            border: 1px solid var(--admin-gray-300);
        }
        
        .btn-outline:hover {
            background: var(--admin-gray-50);
            border-color: var(--admin-primary);
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .btn-danger {
            background: var(--admin-danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #c53030;
        }
        
        .btn-success {
            background: var(--admin-success);
            color: white;
        }
        
        .btn-success:hover {
            background: #2f855a;
        }
        
        /* Carousel Table */
        .carousel-table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .carousel-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .carousel-table th {
            text-align: left;
            padding: 1rem 1.5rem;
            background: var(--admin-gray-50);
            font-weight: 600;
            color: var(--admin-gray-700);
            border-bottom: 1px solid var(--admin-gray-200);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .carousel-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--admin-gray-100);
            vertical-align: top;
        }
        
        .carousel-table tr:hover {
            background: var(--admin-gray-50);
        }
        
        .carousel-table tr:last-child td {
            border-bottom: none;
        }
        
        .slide-preview {
            width: 120px;
            height: 60px;
            border-radius: 6px;
            overflow: hidden;
            background: var(--admin-gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: var(--admin-gray-600);
        }
        
        .slide-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .slide-info h4 {
            font-weight: 600;
            color: var(--admin-gray-800);
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }
        
        .slide-info p {
            color: var(--admin-gray-600);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        
        .slide-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            color: var(--admin-gray-600);
        }
        
        .order-badge {
            background: var(--admin-gray-100);
            color: var(--admin-gray-700);
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--admin-gray-600);
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            color: var(--admin-gray-300);
        }
        
        /* Modal for delete confirmation */
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
                <h1>Manage Carousel Slides</h1>
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
            <div class="carousel-admin-container">
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
                <div class="page-header">
                    <h1 class="page-title">Carousel Slides</h1>
                    <a href="<?php echo BASE_URL; ?>/admin/carousel/create" class="btn btn-primary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Add New Slide
                    </a>
                </div>
                
                <!-- Carousel Content -->
                <?php if (empty($slides)): ?>
                    <div class="empty-state">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                        </svg>
                        <h3>No Carousel Slides Found</h3>
                        <p>Get started by adding your first carousel slide.</p>
                        <a href="<?php echo BASE_URL; ?>/admin/carousel/create" class="btn btn-primary" style="margin-top: 1rem;">
                            Create First Slide
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Carousel Table -->
                    <div class="carousel-table-container">
                        <table class="carousel-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">Order</th>
                                    <th>Slide Preview & Info</th>
                                    <th style="width: 120px;">Status</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($slides as $slide): ?>
                                <tr>
                                    <td>
                                        <div class="order-badge">
                                            <?php echo $slide['display_order']; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 1rem;">
                                            <div class="slide-preview">
                                                <?php if (!empty($slide['image_path'])): ?>
                                                <img src="<?php echo BASE_URL . $slide['image_path']; ?>" 
                                                     alt="<?php echo htmlspecialchars($slide['title']); ?>"
                                                     onerror="this.style.display='none'; this.parentNode.innerHTML='No Image';">
                                                <?php else: ?>
                                                No Image
                                                <?php endif; ?>
                                            </div>
                                            <div class="slide-info">
                                                <h4><?php echo htmlspecialchars($slide['title']); ?></h4>
                                                <p><?php echo htmlspecialchars($slide['subtitle']); ?></p>
                                                <div class="slide-meta">
                                                    <?php if (!empty($slide['button_text'])): ?>
                                                    <span>Button: <?php echo htmlspecialchars($slide['button_text']); ?></span>
                                                    <?php endif; ?>
                                                    <span>Updated: <?php echo date('M d, Y', strtotime($slide['updated_at'])); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <form action="<?php echo BASE_URL; ?>/admin/carousel/toggle/<?php echo $slide['id']; ?>" 
                                              method="POST" style="display: inline;">
                                            <button type="submit" class="btn btn-sm <?php echo $slide['is_active'] ? 'btn-success' : 'btn-outline'; ?>">
                                                <?php echo $slide['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?php echo BASE_URL; ?>/admin/carousel/edit/<?php echo $slide['id']; ?>" 
                                               class="btn btn-outline btn-sm">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="showDeleteModal(<?php echo $slide['id']; ?>, '<?php echo htmlspecialchars(addslashes($slide['title'])); ?>')">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Instructions -->
                    <div style="background: var(--admin-gray-50); padding: 1.5rem; border-radius: 8px; margin-top: 2rem;">
                        <h4 style="margin-bottom: 0.75rem; color: var(--admin-gray-800);">How to Use</h4>
                        <ul style="color: var(--admin-gray-600); margin: 0; padding-left: 1.25rem;">
                            <li>Active slides appear on the homepage</li>
                            <li>Display order determines slide sequence (1 = first)</li>
                            <li>Image path should be relative to your website root (e.g., /assets/images/carousel/slide.jpg)</li>
                            <li>Button text and link are optional</li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <h3>Confirm Delete</h3>
            <p>Are you sure you want to delete "<span id="deleteSlideTitle"></span>"? This action cannot be undone.</p>
            <form id="deleteForm" method="POST" action="">
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" onclick="hideDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Slide</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Delete Modal Functions
        function showDeleteModal(id, title) {
            document.getElementById('deleteSlideTitle').textContent = title;
            document.getElementById('deleteForm').action = '<?php echo BASE_URL; ?>/admin/carousel/delete/' + id;
            document.getElementById('deleteModal').classList.add('active');
        }
        
        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
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
        
        // Toggle status button feedback
        const toggleButtons = document.querySelectorAll('form[action*="toggle"] button');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('form');
                const originalText = this.textContent;
                
                // Show loading state
                this.innerHTML = '<svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="spinner"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg> Updating...';
                this.disabled = true;
                
                // Submit form
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(new FormData(form))
                })
                .then(response => {
                    if (response.ok) {
                        // Reload page to show updated status
                        window.location.reload();
                    } else {
                        alert('Failed to update status');
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update status');
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
                
                e.preventDefault();
            });
        });
    </script>
</body>
</html>