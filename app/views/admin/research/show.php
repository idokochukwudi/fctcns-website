<?php
// This is a VIEW file - should only display data, not process it
// All data comes from the controller via the extract() function

// Check if required data exists
if (!isset($publication) || !isset($categories)) {
    die('Error: View cannot be accessed directly. Please use the controller.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Publication Details'); ?></title>
    <style>
        /* CSS Variables - Match index.php */
        :root {
            /* Colors */
            --primary-color: #2c5282;
            --primary-dark: #1a365d;
            --primary-light: #4299e1;
            --success-color: #38a169;
            --warning-color: #d69e2e;
            --danger-color: #e53e3e;
            --info-color: #3182ce;
            
            /* Grayscale */
            --gray-50: #f7fafc;
            --gray-100: #edf2f7;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e0;
            --gray-400: #a0aec0;
            --gray-500: #718096;
            --gray-600: #4a5568;
            --gray-700: #2d3748;
            --gray-800: #1a202c;
            
            /* Spacing */
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;
            
            /* Layout */
            --sidebar-width: 260px;
            --header-height: 60px;
            --sidebar-collapsed-width: 70px;
        }
        
        /* Reset & Base Styles - Match index.php */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            font-size: 16px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif;
            line-height: 1.5;
            color: var(--gray-700);
            background-color: var(--gray-100);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Main Layout Container - Match index.php */
        .app-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }
        
        /* Sidebar Styles - Copy from index.php */
        .app-sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            transition: all 0.3s ease;
            overflow-y: auto;
            transform: translateX(0);
        }
        
        .sidebar-header {
            padding: var(--spacing-lg);
            border-bottom: 1px solid var(--gray-200);
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            text-decoration: none;
            color: var(--gray-800);
            min-width: 0;
        }
        
        .logo-icon {
            width: 36px;
            height: 36px;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        
        .logo-title {
            font-weight: 600;
            font-size: 0.875rem;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .logo-subtitle {
            font-size: 0.7rem;
            color: var(--gray-600);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .close-sidebar-btn {
            display: none;
            background: none;
            border: none;
            color: var(--gray-600);
            cursor: pointer;
            padding: var(--spacing-sm);
            border-radius: 6px;
        }
        
        .sidebar-nav {
            flex: 1;
            padding: var(--spacing-lg);
            overflow-y: auto;
        }
        
        .nav-section {
            margin-bottom: var(--spacing-xl);
        }
        
        .nav-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-600);
            margin-bottom: var(--spacing-md);
            padding-bottom: var(--spacing-sm);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .nav-links {
            list-style: none;
        }
        
        .nav-item {
            margin-bottom: var(--spacing-xs);
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md);
            text-decoration: none;
            color: var(--gray-700);
            border-radius: 8px;
            transition: all 0.2s;
            white-space: nowrap;
            overflow: hidden;
        }
        
        .nav-link:hover {
            background: var(--gray-100);
            color: var(--primary-color);
        }
        
        .nav-link.active {
            background: var(--gray-100);
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        
        .sidebar-footer {
            padding: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
            flex-shrink: 0;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        .user-info {
            flex: 1;
            min-width: 0;
        }
        
        .user-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-info span {
            font-size: 0.75rem;
            color: var(--gray-600);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Main Content Area - Match index.php */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-width: 0;
        }
        
        /* Top Header - Match index.php */
        .top-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 0 var(--spacing-lg);
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--gray-600);
            cursor: pointer;
            padding: var(--spacing-sm);
            border-radius: 6px;
        }
        
        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }
        
        /* Content Wrapper - Match index.php */
        .content-wrapper {
            padding: var(--spacing-lg);
        }
        
        /* Flash Messages - Match index.php */
        .flash-messages {
            margin-bottom: var(--spacing-lg);
        }
        
        .alert {
            padding: var(--spacing-md);
            border-radius: 8px;
            margin-bottom: var(--spacing-sm);
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-sm);
        }
        
        .alert-success {
            background: rgba(56, 161, 105, 0.1);
            border: 1px solid rgba(56, 161, 105, 0.2);
            color: var(--success-color);
        }
        
        .alert-error {
            background: rgba(229, 62, 62, 0.1);
            border: 1px solid rgba(229, 62, 62, 0.2);
            color: var(--danger-color);
        }
        
        /* Buttons - Match index.php */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44, 82, 130, 0.2);
        }
        
        .btn-outline {
            background: white;
            color: var(--primary-color);
            border: 1px solid var(--gray-300);
        }
        
        .btn-outline:hover {
            background: var(--gray-50);
            border-color: var(--primary-color);
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .btn-danger {
            background: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background: #c53030;
        }
        
        .btn-success {
            background: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background: #2f855a;
        }
        
        /* Publication Header */
        .publication-header {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
        }
        
        .publication-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-800);
            line-height: 1.3;
            margin-bottom: var(--spacing-lg);
            word-break: break-word;
        }
        
        .publication-meta {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
            padding-bottom: var(--spacing-lg);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            color: var(--gray-600);
            font-size: 0.875rem;
            flex-wrap: wrap;
        }
        
        .meta-badges {
            display: flex;
            gap: var(--spacing-sm);
            flex-wrap: wrap;
        }
        
        /* Badges - Match index.php */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            white-space: nowrap;
        }
        
        .bg-primary {
            background: rgba(49, 130, 206, 0.1);
            color: var(--primary-color);
        }
        
        .bg-success {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success-color);
        }
        
        .bg-warning {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning-color);
        }
        
        .bg-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }
        
        /* Sections */
        .content-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: var(--spacing-lg);
            padding-bottom: var(--spacing-md);
            border-bottom: 1px solid var(--gray-200);
        }
        
        /* Authors Section */
        .authors-content {
            color: var(--gray-700);
            line-height: 1.8;
            word-break: break-word;
        }
        
        /* Abstract Section */
        .abstract-content {
            color: var(--gray-700);
            line-height: 1.8;
            white-space: pre-wrap;
            word-break: break-word;
        }
        
        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--spacing-xl);
        }
        
        .details-item {
            margin-bottom: var(--spacing-lg);
            display: flex;
            flex-direction: column;
        }
        
        .details-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-600);
            margin-bottom: var(--spacing-xs);
        }
        
        .details-value {
            font-size: 0.875rem;
            color: var(--gray-800);
            font-weight: 500;
            word-break: break-word;
        }
        
        .details-value.empty {
            color: var(--gray-400);
            font-style: italic;
        }
        
        /* Keywords Section */
        .keywords-list {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-sm);
        }
        
        .keyword {
            background: var(--gray-100);
            color: var(--gray-700);
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
        }
        
        /* Files Section */
        .files-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing-lg);
        }
        
        .file-card {
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: var(--spacing-lg);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: all 0.2s;
        }
        
        .file-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .file-icon {
            width: 48px;
            height: 48px;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: var(--spacing-md);
        }
        
        .file-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: var(--spacing-sm);
            word-break: break-all;
        }
        
        .file-actions {
            display: flex;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-md);
        }
        
        .thumbnail-preview {
            width: 100%;
            max-width: 300px;
            height: auto;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            margin-top: var(--spacing-md);
        }
        
        /* Statistics Section */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: var(--spacing-lg);
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-top: var(--spacing-sm);
            display: block;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: var(--spacing-md);
            margin-top: var(--spacing-xl);
            padding-top: var(--spacing-xl);
            border-top: 1px solid var(--gray-200);
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            flex: 1;
            min-width: 150px;
        }
        
        /* Mobile Overlay - Match index.php */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }
        
        /* Responsive Design - Match index.php pattern */
        @media (max-width: 1024px) {
            .app-sidebar {
                transform: translateX(-100%);
            }
            
            .app-sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .close-sidebar-btn {
                display: block;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .files-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .content-wrapper {
                padding: var(--spacing-md);
            }
            
            .publication-header,
            .content-section {
                padding: var(--spacing-lg);
            }
            
            .publication-title {
                font-size: 1.5rem;
            }
            
            .publication-meta {
                flex-direction: column;
                gap: var(--spacing-md);
            }
            
            .top-header {
                padding: 0 var(--spacing-md);
            }
            
            .page-title {
                font-size: 1.125rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .content-wrapper {
                padding: var(--spacing-sm);
            }
            
            .publication-header,
            .content-section {
                padding: var(--spacing-md);
            }
        }
        
        @media (max-width: 480px) {
            .publication-title {
                font-size: 1.25rem;
            }
            
            .section-title {
                font-size: 1.125rem;
            }
            
            .file-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .file-actions .btn {
                width: 100%;
            }
        }
        
        /* Print Styles */
        @media print {
            .app-sidebar,
            .top-header,
            .action-buttons,
            .file-actions {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .content-wrapper {
                padding: 0 !important;
            }
            
            .publication-header,
            .content-section {
                box-shadow: none !important;
                border: 1px solid var(--gray-300) !important;
            }
        }
        
        /* Utility Classes - Match index.php */
        .d-none { display: none !important; }
        .d-flex { display: flex !important; }
        .w-100 { width: 100%; }
        
        .mb-0 { margin-bottom: 0 !important; }
        .mb-1 { margin-bottom: var(--spacing-xs) !important; }
        .mb-2 { margin-bottom: var(--spacing-sm) !important; }
        .mb-3 { margin-bottom: var(--spacing-md) !important; }
        .mb-4 { margin-bottom: var(--spacing-lg) !important; }
        .mb-5 { margin-bottom: var(--spacing-xl) !important; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .flex-wrap { flex-wrap: wrap; }
        .align-items-center { align-items: center; }
        .justify-content-between { justify-content: space-between; }
        .flex-column { flex-direction: column; }
        
        .gap-1 { gap: var(--spacing-xs); }
        .gap-2 { gap: var(--spacing-sm); }
        .gap-3 { gap: var(--spacing-md); }
        .gap-4 { gap: var(--spacing-lg); }
        .gap-5 { gap: var(--spacing-xl); }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar - Match index.php -->
        <aside class="app-sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="logo">
                    <div class="logo-icon">FCT</div>
                    <div class="logo-text">
                        <div class="logo-title">FCT CNS</div>
                        <div class="logo-subtitle">Research Portal</div>
                    </div>
                </a>
                <button class="close-sidebar-btn" id="closeSidebarBtn">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <h3 class="nav-section-title">Main</h3>
                    <ul class="nav-links">
                        <li class="nav-item">
                            <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="nav-link <?php echo ($currentPage ?? '') == 'dashboard' ? 'active' : ''; ?>">
                                <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="nav-section">
                    <h3 class="nav-section-title">Research Management</h3>
                    <ul class="nav-links">
                        <?php if (in_array($userRole ?? '', ['admin', 'editor', 'research'])): ?>
                        <li class="nav-item">
                            <a href="<?php echo BASE_URL; ?>/admin/research" class="nav-link <?php echo ($currentPage ?? '') == 'research' ? 'active' : ''; ?>">
                                <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                <span>Research Publications</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($username ?? 'U', 0, 2)); ?>
                    </div>
                    <div class="user-info">
                        <h4><?php echo htmlspecialchars($username ?? 'User'); ?></h4>
                        <span><?php echo ucfirst($userRole ?? 'guest'); ?> - Research</span>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header - Match index.php -->
            <header class="top-header">
                <div class="header-left">
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <h1 class="page-title">Publication Details</h1>
                </div>
                <div class="header-right">
                    <a href="<?php echo BASE_URL; ?>/admin/logout" class="btn btn-sm btn-outline">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                        </svg>
                        Logout
                    </a>
                </div>
            </header>
            
            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Flash Messages - Match index.php -->
                <?php if (($flash_success ?? false) || ($flash_error ?? false)): ?>
                <div class="flash-messages">
                    <?php if ($flash_success ?? false): ?>
                    <div class="alert alert-success">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo htmlspecialchars($flash_success); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($flash_error ?? false): ?>
                    <div class="alert alert-error">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo htmlspecialchars($flash_error); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Publication Header -->
                <div class="publication-header">
                    <h2 class="publication-title"><?php echo htmlspecialchars($publication['title']); ?></h2>
                    
                    <div class="publication-meta">
                        <div class="meta-item">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span>Published: <?php echo $pubDate; ?></span>
                        </div>
                        
                        <div class="meta-item">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            <span>Type: <?php echo $pubTypeLabel; ?></span>
                        </div>
                        
                        <div class="meta-item">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                            </svg>
                            <span>Category: <?php echo htmlspecialchars($categoryName); ?></span>
                        </div>
                    </div>
                    
                    <div class="meta-badges">
                        <span class="badge bg-primary"><?php echo strtoupper($publication['publication_type']); ?></span>
                        
                        <?php if ($publication['is_published']): ?>
                        <span class="badge bg-success">Published</span>
                        <?php else: ?>
                        <span class="badge bg-warning">Draft</span>
                        <?php endif; ?>
                        
                        <?php if ($publication['is_featured']): ?>
                        <span class="badge bg-success">Featured</span>
                        <?php endif; ?>
                        
                        <?php if (!empty($publication['doi'])): ?>
                        <span class="badge bg-secondary">DOI: <?php echo htmlspecialchars($publication['doi']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Authors Section -->
                <div class="content-section">
                    <h3 class="section-title">Authors</h3>
                    <div class="authors-content">
                        <?php echo htmlspecialchars($publication['authors']); ?>
                    </div>
                </div>
                
                <!-- Abstract Section -->
                <div class="content-section">
                    <h3 class="section-title">Abstract</h3>
                    <div class="abstract-content">
                        <?php echo nl2br(htmlspecialchars($publication['abstract'])); ?>
                    </div>
                </div>
                
                <!-- Details Grid -->
                <div class="details-grid">
                    <!-- Publication Details -->
                    <div class="content-section">
                        <h3 class="section-title">Publication Details</h3>
                        
                        <div class="details-item">
                            <span class="details-label">Publication Type</span>
                            <span class="details-value"><?php echo $pubTypeLabel; ?></span>
                        </div>
                        
                        <?php if (!empty($publication['journal_name'])): ?>
                        <div class="details-item">
                            <span class="details-label">Journal/Conference</span>
                            <span class="details-value"><?php echo htmlspecialchars($publication['journal_name']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($publication['volume'])): ?>
                        <div class="details-item">
                            <span class="details-label">Volume</span>
                            <span class="details-value"><?php echo htmlspecialchars($publication['volume']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($publication['issue'])): ?>
                        <div class="details-item">
                            <span class="details-label">Issue</span>
                            <span class="details-value"><?php echo htmlspecialchars($publication['issue']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($publication['pages'])): ?>
                        <div class="details-item">
                            <span class="details-label">Pages</span>
                            <span class="details-value"><?php echo htmlspecialchars($publication['pages']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($publication['publisher'])): ?>
                        <div class="details-item">
                            <span class="details-label">Publisher</span>
                            <span class="details-value"><?php echo htmlspecialchars($publication['publisher']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="details-item">
                            <span class="details-label">Publication Date</span>
                            <span class="details-value"><?php echo $pubDate; ?></span>
                        </div>
                    </div>
                    
                    <!-- Research Information -->
                    <div class="content-section">
                        <h3 class="section-title">Research Information</h3>
                        
                        <div class="details-item">
                            <span class="details-label">Research Area</span>
                            <span class="details-value"><?php echo htmlspecialchars($categoryName); ?></span>
                        </div>
                        
                        <?php if (!empty($publication['citations'])): ?>
                        <div class="details-item">
                            <span class="details-label">Citations</span>
                            <span class="details-value"><?php echo number_format($publication['citations']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($publication['impact_factor'])): ?>
                        <div class="details-item">
                            <span class="details-label">Impact Factor</span>
                            <span class="details-value"><?php echo htmlspecialchars($publication['impact_factor']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($publication['doi'])): ?>
                        <div class="details-item">
                            <span class="details-label">DOI</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="details-value"><?php echo htmlspecialchars($publication['doi']); ?></span>
                                <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($publication['doi']); ?>', this)">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($publication['url'])): ?>
                        <div class="details-item">
                            <span class="details-label">URL</span>
                            <div class="d-flex align-items-center gap-2">
                                <a href="<?php echo htmlspecialchars($publication['url']); ?>" target="_blank" rel="noopener" class="details-value">
                                    <?php echo htmlspecialchars($publication['url']); ?>
                                </a>
                                <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($publication['url']); ?>', this)">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Keywords Section -->
                <?php if (!empty($keywordsArray)): ?>
                <div class="content-section">
                    <h3 class="section-title">Keywords</h3>
                    <div class="keywords-list">
                        <?php foreach ($keywordsArray as $keyword): ?>
                            <span class="keyword"><?php echo htmlspecialchars($keyword); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Files Section -->
                <div class="content-section">
                    <h3 class="section-title">Files</h3>
                    <div class="files-grid">
                        <?php if (!empty($publication['file_path'])): ?>
                        <div class="file-card">
                            <div class="file-icon">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="file-name"><?php echo basename($publication['file_path']); ?></div>
                            <div class="file-actions">
                                <a href="<?php echo BASE_URL . '/' . $publication['file_path']; ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    View
                                </a>
                                <a href="<?php echo BASE_URL . '/' . $publication['file_path']; ?>" download class="btn btn-primary btn-sm">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($publication['thumbnail_path'])): ?>
                        <div class="file-card">
                            <div class="file-icon">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="file-name"><?php echo basename($publication['thumbnail_path']); ?></div>
                            <img src="<?php echo BASE_URL . '/' . $publication['thumbnail_path']; ?>" alt="Thumbnail" class="thumbnail-preview">
                            <div class="file-actions">
                                <a href="<?php echo BASE_URL . '/' . $publication['thumbnail_path']; ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    View
                                </a>
                                <a href="<?php echo BASE_URL . '/' . $publication['thumbnail_path']; ?>" download class="btn btn-primary btn-sm">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (empty($publication['file_path']) && empty($publication['thumbnail_path'])): ?>
                        <div class="details-item">
                            <span class="details-value empty">No files uploaded</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Statistics Section -->
                <div class="content-section">
                    <h3 class="section-title">Publication Statistics</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-value"><?php echo $publication['views'] ?? 0; ?></span>
                            <span class="stat-label">Total Views</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?php echo $publication['downloads'] ?? 0; ?></span>
                            <span class="stat-label">Total Downloads</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?php echo $publication['citations'] ?? 0; ?></span>
                            <span class="stat-label">Citations</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?php echo $publication['created_by'] ?? 'Unknown'; ?></span>
                            <span class="stat-label">Created By</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?php echo date('M d', strtotime($publication['created_at'] ?? date('Y-m-d'))); ?></span>
                            <span class="stat-label">Created Date</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?php echo $publication['is_published'] ? 'Yes' : 'No'; ?></span>
                            <span class="stat-label">Published</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="content-section">
                    <div class="action-buttons">
                        <a href="<?php echo BASE_URL; ?>/admin/research/<?php echo $publication['id']; ?>/edit" class="btn btn-primary">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                            Edit Publication
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>/research/<?php echo $publication['id']; ?>" target="_blank" rel="noopener" class="btn btn-outline">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            View Public Page
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>/admin/research" class="btn btn-outline">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Overlay for Mobile - Match index.php -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
    </div>
    
    <script>
        // Mobile Sidebar Toggle - Same as index.php
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }
        
        if (mobileMenuBtn && sidebar) {
            mobileMenuBtn.addEventListener('click', toggleSidebar);
            closeSidebarBtn.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }
        
        // Close sidebar when clicking on a link (mobile only)
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    toggleSidebar();
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Copy to clipboard function
        window.copyToClipboard = function(text, button) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success feedback
                const originalHTML = button.innerHTML;
                button.style.color = 'var(--success-color)';
                button.innerHTML = `
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                `;
                
                // Reset after 2 seconds
                setTimeout(function() {
                    button.style.color = '';
                    button.innerHTML = originalHTML;
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                alert('Failed to copy to clipboard');
            });
        };
        
        // Auto-hide flash messages after 5 seconds - Same as index.php
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure proper layout on load
            if (window.innerWidth < 1024) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            }
        });
        
        // Add copy button styles
        const style = document.createElement('style');
        style.textContent = `
            .copy-btn {
                background: none;
                border: none;
                color: var(--gray-500);
                cursor: pointer;
                padding: 0.25rem;
                border-radius: 4px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s;
            }
            
            .copy-btn:hover {
                color: var(--primary-color);
                background: var(--gray-100);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>