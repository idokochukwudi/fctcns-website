<?php
// app/views/layouts/news_admin.php

// Get the absolute path to the root
$rootPath = dirname(__DIR__, 3); // Go up 3 levels from app/views/layouts/

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

// Get news statistics if needed
$stats = [];
try {
    // Load NewsModel if available
    if (file_exists(APP_PATH . '/models/NewsModel.php')) {
        require_once APP_PATH . '/models/NewsModel.php';
        $newsModel = new NewsModel();
        $stats = $newsModel->getStats();
    } else {
        // Fallback to direct query
        $queries = [
            'total_news' => "SELECT COUNT(*) as total FROM news",
            'published_news' => "SELECT COUNT(*) as total FROM news WHERE is_published = 1",
            'draft_news' => "SELECT COUNT(*) as total FROM news WHERE is_published = 0",
            'featured_news' => "SELECT COUNT(*) as total FROM news WHERE is_featured = 1"
        ];
        
        foreach ($queries as $key => $sql) {
            $stmt = $conn->query($sql);
            $stats[$key] = $stmt->fetch()['total'];
        }
    }
} catch (Exception $e) {
    error_log("News admin layout error: " . $e->getMessage());
    $stats = [
        'total_news' => 0,
        'published_news' => 0,
        'draft_news' => 0,
        'featured_news' => 0
    ];
}

// Get page title from controller or default
$pageTitle = $pageTitle ?? 'News Management';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - FCT College of Nursing Sciences</title>
    
    <!-- Include dashboard CSS -->
    <style>
        /* Inherit all styles from dashboard.php */
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
            --admin-purple: #9f7aea;
            --admin-gray-50: #f7fafc;
            --admin-gray-100: #edf2f7;
            --admin-gray-200: #e2e8f0;
            --admin-gray-300: #cbd5e0;
            --admin-gray-400: #a0aec0;
            --admin-gray-500: #718096;
            --admin-gray-600: #4a5568;
            --admin-gray-700: #2d3748;
            --admin-gray-800: #1a202c;
            --admin-gray-900: #171923;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--admin-gray-100);
            color: var(--admin-gray-800);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.5;
        }
        
        /* Sidebar - EXACT COPY FROM DASHBOARD */
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: var(--admin-gray-900);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-xl);
            transform: translateX(0);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }
        
        .sidebar-header {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            background: var(--admin-gray-900);
            z-index: 10;
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
            transition: opacity 0.2s;
        }
        
        .sidebar-brand:hover {
            opacity: 0.9;
        }
        
        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .sidebar-title {
            font-size: 1.125rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.025em;
        }
        
        .sidebar-subtitle {
            font-size: 0.75rem;
            opacity: 0.7;
            margin-top: 0.125rem;
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 1.25rem 0;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }
        
        .nav-section {
            margin-bottom: 1.5rem;
        }
        
        .nav-section:last-child {
            margin-bottom: 0;
        }
        
        .nav-section-title {
            padding: 0 1.25rem 0.5rem;
            font-size: 0.6875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--admin-gray-400);
            font-weight: 600;
        }
        
        .nav-links {
            list-style: none;
        }
        
        .nav-item {
            margin-bottom: 0.125rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            color: var(--admin-gray-300);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
        }
        
        .nav-link:hover::before {
            left: 100%;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }
        
        .nav-link.active {
            background: rgba(66, 153, 225, 0.1);
            color: var(--admin-primary-light);
            border-left-color: var(--admin-primary-light);
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        
        .nav-badge {
            margin-left: auto;
            background: var(--admin-warning);
            color: white;
            padding: 0.125rem 0.5rem;
            border-radius: 10px;
            font-size: 0.6875rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            bottom: 0;
            background: var(--admin-gray-900);
            z-index: 10;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            flex-shrink: 0;
            font-size: 0.875rem;
        }
        
        .user-info {
            flex: 1;
            min-width: 0;
        }
        
        .user-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.125rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-info span {
            font-size: 0.75rem;
            color: var(--admin-gray-400);
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: var(--admin-sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: var(--shadow-sm);
        }
        
        .header-title h1 {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--admin-gray-800);
            letter-spacing: -0.025em;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .notification-btn, .logout-btn, .mobile-menu-toggle {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            color: var(--admin-gray-600);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .notification-btn:hover, .logout-btn:hover, .mobile-menu-toggle:hover {
            background: var(--admin-gray-100);
            color: var(--admin-gray-800);
        }
        
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--admin-danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.6875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .mobile-menu-toggle {
            display: none;
        }
        
        .admin-content {
            padding: 1.5rem;
            flex: 1;
            overflow-y: auto;
            overscroll-behavior: contain;
        }
        
        /* News-specific styles */
        .news-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .news-stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s;
            border-left: 4px solid;
            position: relative;
            overflow: hidden;
        }
        
        .news-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .news-stat-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        
        .news-stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .stat-total .news-stat-icon { background: rgba(66, 153, 225, 0.1); color: var(--admin-primary-light); }
        .stat-published .news-stat-icon { background: rgba(56, 161, 105, 0.1); color: var(--admin-success); }
        .stat-draft .news-stat-icon { background: rgba(214, 158, 46, 0.1); color: var(--admin-warning); }
        .stat-featured .news-stat-icon { background: rgba(159, 122, 234, 0.1); color: var(--admin-purple); }
        
        .news-stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.125rem;
            line-height: 1;
            background: linear-gradient(135deg, currentColor, var(--admin-gray-800));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .news-stat-label {
            font-size: 0.8125rem;
            color: var(--admin-gray-600);
            font-weight: 500;
        }
        
        /* News action bar */
        .news-action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .action-btn-primary, .action-btn-secondary {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .action-btn-primary {
            background: var(--admin-primary);
            color: white;
            border: none;
        }
        
        .action-btn-primary:hover {
            background: var(--admin-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        .action-btn-secondary {
            background: white;
            color: var(--admin-gray-700);
            border: 1px solid var(--admin-gray-300);
        }
        
        .action-btn-secondary:hover {
            background: var(--admin-gray-50);
            border-color: var(--admin-gray-400);
        }
        
        /* Filter bar */
        .filter-bar {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr));
            gap: 1rem;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .filter-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--admin-gray-700);
        }
        
        .filter-select, .filter-input {
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--admin-gray-300);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
        }
        
        .filter-select:focus, .filter-input:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        
        /* News table */
        .news-table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            margin-bottom: 1.5rem;
        }
        
        .news-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        
        .news-table th {
            text-align: left;
            padding: 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--admin-gray-600);
            background: var(--admin-gray-50);
            border-bottom: 2px solid var(--admin-gray-200);
            font-weight: 700;
            position: sticky;
            top: 0;
        }
        
        .news-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--admin-gray-100);
            vertical-align: middle;
        }
        
        .news-table tbody tr {
            transition: background-color 0.2s;
        }
        
        .news-table tbody tr:hover {
            background: var(--admin-gray-50);
        }
        
        .news-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .news-status {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .status-published { background: rgba(56, 161, 105, 0.1); color: var(--admin-success); }
        .status-draft { background: rgba(214, 158, 46, 0.1); color: var(--admin-warning); }
        .status-archived { background: rgba(160, 174, 192, 0.1); color: var(--admin-gray-500); }
        
        .news-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-icon-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: 1px solid var(--admin-gray-300);
            color: var(--admin-gray-600);
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .action-icon-btn:hover {
            background: var(--admin-gray-100);
            border-color: var(--admin-gray-400);
            color: var(--admin-gray-800);
        }
        
        .action-icon-btn.view:hover { background: rgba(66, 153, 225, 0.1); color: var(--admin-primary); }
        .action-icon-btn.edit:hover { background: rgba(56, 161, 105, 0.1); color: var(--admin-success); }
        .action-icon-btn.delete:hover { background: rgba(229, 62, 62, 0.1); color: var(--admin-danger); }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        
        .pagination-btn, .pagination-current {
            padding: 0.5rem 0.875rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .pagination-btn {
            background: white;
            border: 1px solid var(--admin-gray-300);
            color: var(--admin-gray-700);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .pagination-btn:hover {
            background: var(--admin-gray-50);
            border-color: var(--admin-gray-400);
        }
        
        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .pagination-current {
            background: var(--admin-primary);
            color: white;
            border: none;
        }
        
        /* Responsive design */
        @media (max-width: 1024px) {
            .admin-sidebar {
                width: 220px;
            }
            
            .admin-main {
                margin-left: 220px;
            }
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .admin-sidebar.active {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .admin-header {
                padding: 0 1rem;
                height: 64px;
            }
            
            .mobile-menu-toggle {
                display: flex;
            }
            
            .header-title h1 {
                font-size: 1.25rem;
            }
            
            .admin-content {
                padding: 1rem;
            }
            
            .news-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .news-action-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .action-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .filter-bar {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 640px) {
            .news-stats-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-btn-primary, .action-btn-secondary {
                width: 100%;
                justify-content: center;
            }
            
            .news-table {
                font-size: 0.875rem;
            }
            
            .news-table th,
            .news-table td {
                padding: 0.75rem 0.5rem;
            }
        }
        
        /* Flash messages */
        .flash-messages {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-width: 400px;
        }
        
        .flash-message {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: var(--shadow-lg);
            animation: slideInRight 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .flash-success {
            background: var(--admin-success);
        }
        
        .flash-error {
            background: var(--admin-danger);
        }
        
        .flash-warning {
            background: var(--admin-warning);
        }
        
        .flash-info {
            background: var(--admin-info);
        }
        
        .flash-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 0.25rem;
            margin-left: 1rem;
            font-size: 1.25rem;
            line-height: 1;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--admin-gray-200);
            border-top-color: var(--admin-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--admin-gray-500);
        }
        
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--admin-gray-600);
        }
        
        .empty-state-description {
            font-size: 0.875rem;
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>
    
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
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/analytics" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            <span>Analytics</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="nav-section">
                <h3 class="nav-section-title">Management</h3>
                <ul class="nav-links">
                    <?php if ($userRole !== 'nominal_roll_user'): ?>
                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/applications" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                            <span>Applications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/research" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            <span>Research</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/news" class="nav-link active">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                            </svg>
                            <span>News & Events</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/carousel" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>Carousel Slides</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if (in_array($userRole, ['admin', 'editor', 'nominal_roll_user'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/nominal-roll" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                            <span>Nominal Roll</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($userRole !== 'nominal_roll_user'): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/contact" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/>
                            </svg>
                            <span>Contact Messages</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($userRole === 'admin'): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/users" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                            <span>User Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/settings" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                            <span>Settings</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <?php if ($userRole !== 'nominal_roll_user'): ?>
            <div class="nav-section">
                <h3 class="nav-section-title">Tools</h3>
                <ul class="nav-links">
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/reports" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"/>
                            </svg>
                            <span>Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/backup" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            <span>Backup</span>
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Logout Section -->
            <div class="nav-section">
                <h3 class="nav-section-title">Account</h3>
                <ul class="nav-links">
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/users/profile" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span>My Profile</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/users/change-password" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Change Password</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/logout" class="nav-link" 
                           style="color: var(--admin-danger);" 
                           onclick="return confirm('Are you sure you want to logout?');">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>Logout</span>
                        </a>
                    </li>
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
                    <span>
                        <?php 
                        $roleDisplayNames = [
                            'admin' => 'Administrator',
                            'editor' => 'Editor',
                            'viewer' => 'Viewer',
                            'moderator' => 'Moderator',
                            'supervisor' => 'Supervisor',
                            'nominal_roll_user' => 'Nominal Roll User'
                        ];
                        echo isset($roleDisplayNames[$userRole]) ? $roleDisplayNames[$userRole] : ucfirst($userRole);
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="admin-main" id="main-content">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-title">
                <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
            </div>
            <div class="header-actions">
                <button class="notification-btn" title="Notifications" id="notificationBtn">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                </button>
                <a href="<?php echo BASE_URL; ?>/admin/logout" class="logout-btn" title="Logout">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                    </svg>
                </a>
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </header>
        
        <!-- Flash Messages -->
        <div class="flash-messages" id="flashMessages">
            <?php
            if (isset($_SESSION['flash_messages'])) {
                foreach ($_SESSION['flash_messages'] as $message) {
                    echo '<div class="flash-message flash-' . htmlspecialchars($message['type']) . '">';
                    echo '<span>' . htmlspecialchars($message['text']) . '</span>';
                    echo '<button class="flash-close">&times;</button>';
                    echo '</div>';
                }
                unset($_SESSION['flash_messages']);
            }
            ?>
        </div>
        
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner"></div>
        </div>
        
        <!-- Content Area -->
        <div class="admin-content">
            <?php 
            // This is where the specific news view content will be included
            if (!empty($content)) {
                echo $content;
            } else {
                // Default content area
                ?>
                <div class="news-stats-grid">
                    <div class="news-stat-card stat-total" style="border-left-color: var(--admin-primary);">
                        <div class="news-stat-header">
                            <div>
                                <div class="news-stat-value"><?php echo number_format($stats['total_news'] ?? 0); ?></div>
                                <div class="news-stat-label">Total News</div>
                            </div>
                            <div class="news-stat-icon">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                    <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="news-stat-card stat-published" style="border-left-color: var(--admin-success);">
                        <div class="news-stat-header">
                            <div>
                                <div class="news-stat-value"><?php echo number_format($stats['published_news'] ?? 0); ?></div>
                                <div class="news-stat-label">Published</div>
                            </div>
                            <div class="news-stat-icon">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="news-stat-card stat-draft" style="border-left-color: var(--admin-warning);">
                        <div class="news-stat-header">
                            <div>
                                <div class="news-stat-value"><?php echo number_format($stats['draft_news'] ?? 0); ?></div>
                                <div class="news-stat-label">Drafts</div>
                            </div>
                            <div class="news-stat-icon">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="news-stat-card stat-featured" style="border-left-color: var(--admin-purple);">
                        <div class="news-stat-header">
                            <div>
                                <div class="news-stat-value"><?php echo number_format($stats['featured_news'] ?? 0); ?></div>
                                <div class="news-stat-label">Featured</div>
                            </div>
                            <div class="news-stat-icon">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </main>
    
    <script>
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        
        mobileMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileMenuToggle.contains(event.target) && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Close mobile menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }, 250);
        });
        
        // Flash messages auto-close
        document.querySelectorAll('.flash-close').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.animation = 'slideInRight 0.3s ease reverse forwards';
                setTimeout(() => {
                    this.parentElement.remove();
                }, 300);
            });
        });
        
        // Auto-remove flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(message => {
                message.style.animation = 'slideInRight 0.3s ease reverse forwards';
                setTimeout(() => message.remove(), 300);
            });
        }, 5000);
        
        // Loading overlay functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }
        
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }
        
        // Form submission loading
        document.addEventListener('submit', function(e) {
            if (e.target.tagName === 'FORM') {
                showLoading();
            }
        });
        
        // Notification system
        function checkNotifications() {
            // This would typically fetch from an API endpoint
            // For now, we'll just show a placeholder
            const notificationBtn = document.getElementById('notificationBtn');
            notificationBtn.onclick = function() {
                alert('Notifications feature will be implemented in the next phase.');
            };
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkNotifications();
            
            // Add active state to current page in sidebar
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href').replace(BASE_URL, ''))) {
                    link.classList.add('active');
                }
            });
        });
        
        // Define BASE_URL for JavaScript
        const BASE_URL = '<?php echo BASE_URL; ?>';
        
        // AJAX helper function
        function ajaxRequest(url, method = 'GET', data = null) {
            showLoading();
            return fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: data ? JSON.stringify(data) : null
            })
            .then(response => {
                hideLoading();
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .catch(error => {
                hideLoading();
                console.error('AJAX error:', error);
                showFlashMessage('An error occurred. Please try again.', 'error');
                throw error;
            });
        }
        
        // Flash message helper
        function showFlashMessage(message, type = 'info') {
            const flashMessages = document.getElementById('flashMessages');
            const flashDiv = document.createElement('div');
            flashDiv.className = `flash-message flash-${type}`;
            flashDiv.innerHTML = `
                <span>${message}</span>
                <button class="flash-close">&times;</button>
            `;
            flashMessages.appendChild(flashDiv);
            
            // Add close event
            flashDiv.querySelector('.flash-close').addEventListener('click', function() {
                flashDiv.style.animation = 'slideInRight 0.3s ease reverse forwards';
                setTimeout(() => flashDiv.remove(), 300);
            });
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (flashDiv.parentNode) {
                    flashDiv.style.animation = 'slideInRight 0.3s ease reverse forwards';
                    setTimeout(() => flashDiv.remove(), 300);
                }
            }, 5000);
        }
        
        // Confirm dialog helper
        function confirmDialog(message) {
            return new Promise((resolve) => {
                const dialog = document.createElement('div');
                dialog.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0,0,0,0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 9999;
                `;
                
                dialog.innerHTML = `
                    <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 400px; width: 90%;">
                        <h3 style="margin-bottom: 1rem; color: var(--admin-gray-800);">Confirm Action</h3>
                        <p style="margin-bottom: 1.5rem; color: var(--admin-gray-600);">${message}</p>
                        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                            <button id="confirmCancel" style="padding: 0.5rem 1.5rem; background: var(--admin-gray-200); color: var(--admin-gray-700); border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                Cancel
                            </button>
                            <button id="confirmOk" style="padding: 0.5rem 1.5rem; background: var(--admin-danger); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                Confirm
                            </button>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(dialog);
                
                dialog.querySelector('#confirmCancel').addEventListener('click', () => {
                    document.body.removeChild(dialog);
                    resolve(false);
                });
                
                dialog.querySelector('#confirmOk').addEventListener('click', () => {
                    document.body.removeChild(dialog);
                    resolve(true);
                });
                
                // Close on escape
                const escapeHandler = (e) => {
                    if (e.key === 'Escape') {
                        document.body.removeChild(dialog);
                        document.removeEventListener('keydown', escapeHandler);
                        resolve(false);
                    }
                };
                document.addEventListener('keydown', escapeHandler);
            });
        }
    </script>
</body>
</html>