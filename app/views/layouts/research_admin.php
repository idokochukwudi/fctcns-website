<?php
/**
 * Research Admin Layout - SINGLE LAYOUT FOR ALL USERS
 * Shows research navigation for everyone
 */

// Ensure we have required data
$pageTitle = $pageTitle ?? 'Research Management';
$content = $content ?? '';
$userRole = $userRole ?? 'guest';
$username = $username ?? 'User';
$currentPage = $currentPage ?? 'research';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Portal - FCT CNS</title>
    <style>
        /* CSS Variables - OPTIMIZED FOR WIDE CONTENT */
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
            
            /* Layout - WIDE CONTENT AREA */
            --sidebar-width: 200px;  /* Narrow sidebar */
            --header-height: 60px;
        }
        
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-100);
            color: var(--gray-700);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Layout Container */
        .layout-container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }
        
        /* Sidebar - SUPER COMPACT */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            color: white;
            flex-shrink: 0;
        }
        
        .sidebar-header {
            padding: var(--spacing-md);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            text-decoration: none;
            color: white;
            width: 100%;
        }
        
        .sidebar-logo {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
        
        .sidebar-title {
            font-weight: 600;
            font-size: 0.85rem;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .sidebar-subtitle {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* COMPACT NAVIGATION */
        .sidebar-nav {
            flex: 1;
            padding: var(--spacing-md);
            overflow-y: auto;
        }
        
        .nav-section {
            margin-bottom: var(--spacing-md);
        }
        
        .nav-section + .nav-section {
            margin-top: var(--spacing-sm);
        }
        
        .nav-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: var(--spacing-xs);
            padding-bottom: var(--spacing-xs);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
            gap: var(--spacing-sm);
            padding: var(--spacing-sm);
            text-decoration: none;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 6px;
            transition: all 0.2s;
            white-space: nowrap;
            overflow: hidden;
            font-size: 0.8rem;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 500;
            border-left-color: white;
        }
        
        .nav-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }
        
        /* COMPACT USER FOOTER */
        .sidebar-footer {
            padding: var(--spacing-md);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
            flex-shrink: 0;
        }
        
        .user-info h4 {
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.125rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-info span {
            font-size: 0.625rem;
            color: rgba(255, 255, 255, 0.7);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .logout-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem;
            margin-top: 0.375rem;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 6px;
            font-size: 0.75rem;
            transition: background 0.2s;
        }
        
        .logout-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        /* MAIN CONTENT AREA - TAKES ALL REMAINING SPACE */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-width: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100vw - var(--sidebar-width));
            background: var(--gray-100);
        }
        
        /* Page Header */
        .page-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 0 var(--spacing-xl);
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
            flex-shrink: 0;
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
            transition: background 0.2s;
        }
        
        .mobile-menu-btn:hover {
            background: var(--gray-100);
        }
        
        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 2px 4px rgba(44, 82, 130, 0.2);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44, 82, 130, 0.3);
        }
        
        .btn-outline {
            background: white;
            color: var(--primary-color);
            border: 1px solid var(--gray-300);
        }
        
        .btn-outline:hover {
            background: var(--gray-50);
            border-color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        /* Content Container - FULL WIDTH */
        .content-container {
            padding: var(--spacing-xl);
            width: 100%;
            flex: 1;
            max-width: 100%;
            overflow-x: visible;
        }
        
        /* Page Actions */
        .page-actions {
            display: flex;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-xl);
            max-width: 100%;
        }
        
        /* Statistics Grid - FULL WIDTH */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
            max-width: 100%;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: var(--spacing-lg);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .stat-card.success {
            border-left-color: var(--success-color);
        }
        
        .stat-card.info {
            border-left-color: var(--info-color);
        }
        
        .stat-card.warning {
            border-left-color: var(--warning-color);
        }
        
        .stat-title {
            font-size: 0.75rem;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: var(--spacing-sm);
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: var(--spacing-xs);
        }
        
        /* Filters Section - FULL WIDTH */
        .filters-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
            transition: all 0.3s ease;
            max-width: 100%;
        }
        
        .filters-section:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }
        
        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-xl);
            flex-wrap: wrap;
            gap: var(--spacing-md);
            max-width: 100%;
        }
        
        .filters-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-800);
        }
        
        /* Filter Form - FULL WIDTH WITH MANY COLUMNS */
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--spacing-lg);
            max-width: 100%;
        }
        
        .form-group {
            margin-bottom: 0;
        }
        
        .form-label {
            display: block;
            margin-bottom: var(--spacing-sm);
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23718096' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 3rem;
        }
        
        /* Publications Section - FULL WIDTH */
        .publications-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            max-width: 100%;
        }
        
        .publications-section:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 12px;
            max-width: 100%;
        }
        
        .publications-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }
        
        .publications-table th {
            text-align: left;
            padding: var(--spacing-lg);
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 2px solid var(--gray-200);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .publications-table td {
            padding: var(--spacing-lg);
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
            transition: background 0.2s;
        }
        
        .publications-table tr:last-child td {
            border-bottom: none;
        }
        
        .publications-table tr:hover td {
            background: var(--gray-50);
        }
        
        .publication-title {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 8px;
            word-break: break-word;
            line-height: 1.4;
            font-size: 1rem;
        }
        
        .publication-authors {
            font-size: 0.875rem;
            color: var(--gray-600);
            line-height: 1.5;
            word-break: break-word;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
        
        .bg-success {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(56, 161, 105, 0.2);
        }
        
        .bg-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-200);
        }
        
        .bg-info {
            background: rgba(49, 130, 206, 0.1);
            color: var(--info-color);
            border: 1px solid rgba(49, 130, 206, 0.2);
        }
        
        .bg-warning {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(214, 158, 46, 0.2);
        }
        
        .action-cell {
            white-space: nowrap;
        }
        
        .action-buttons {
            display: flex;
            gap: var(--spacing-sm);
            flex-wrap: wrap;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: var(--spacing-2xl) var(--spacing-md);
            color: var(--gray-600);
            max-width: 100%;
        }
        
        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
            backdrop-filter: blur(4px);
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                left: -100%;
                width: 260px;
                transition: left 0.3s ease;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
                width: 100vw;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .content-container {
                padding: var(--spacing-lg);
            }
            
            .page-header {
                padding: 0 var(--spacing-lg);
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-form {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .content-container {
                padding: var(--spacing-md);
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-form {
                grid-template-columns: 1fr;
            }
            
            .page-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        @media (max-width: 640px) {
            .page-header {
                padding: 0 var(--spacing-md);
            }
            
            .content-container {
                padding: var(--spacing-sm);
            }
        }
        
        /* LARGE SCREEN OPTIMIZATIONS */
        @media (min-width: 1200px) {
            .filter-form {
                grid-template-columns: repeat(4, 1fr);
            }
            
            /* Make publication column wider on large screens */
            .publications-table th.publication-cell,
            .publications-table td.publication-cell {
                min-width: 500px;
            }
        }
        
        @media (min-width: 1400px) {
            .filter-form {
                grid-template-columns: repeat(5, 1fr);
            }
            
            .publications-table th.publication-cell,
            .publications-table td.publication-cell {
                min-width: 600px;
            }
        }
        
        @media (min-width: 1600px) {
            .filter-form {
                grid-template-columns: repeat(6, 1fr);
            }
            
            .publications-table th.publication-cell,
            .publications-table td.publication-cell {
                min-width: 700px;
            }
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="layout-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo BASE_URL; ?>/admin/research" class="sidebar-brand">
                    <div class="sidebar-logo">R</div>
                    <div>
                        <div class="sidebar-title">Research Portal</div>
                        <div class="sidebar-subtitle">FCT CNS</div>
                    </div>
                </a>
                <button class="mobile-menu-btn" id="closeSidebarBtn">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <h3 class="nav-section-title">Main</h3>
                    <ul class="nav-links">
                        <!-- Only show Dashboard for non-research_manager users -->
                        <?php if ($userRole !== 'research_manager'): ?>
                        <li class="nav-item">
                            <a href="<?php echo BASE_URL; ?>/admin/dashboard" 
                               class="nav-link <?php echo ($currentPage ?? '') == 'dashboard' ? 'active' : ''; ?>">
                                <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="nav-section">
                    <h3 class="nav-section-title">Research Management</h3>
                    <ul class="nav-links">
                        <li class="nav-item">
                            <a href="<?php echo BASE_URL; ?>/admin/research" 
                               class="nav-link <?php echo ($currentPage ?? '') == 'research' ? 'active' : ''; ?>">
                                <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                <span>All Publications</span>
                            </a>
                        </li>
                        
                        <?php if (Session::hasPermission('research_create')): ?>
                        <li class="nav-item">
                            <a href="<?php echo BASE_URL; ?>/admin/research/create" class="nav-link">
                                <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>Add Publication</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                
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
                <a href="<?php echo BASE_URL; ?>/admin/logout" class="logout-link">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4.414l-4.293 4.293a1 1 0 01-1.414 0L4 7.414 5.414 6l3.293 3.293L13.586 6 14 7.414z" clip-rule="evenodd"/>
                    </svg>
                    Logout
                </a>
            </div>
        </aside>
        
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay"></div>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <header class="page-header">
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
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Logout
                    </a>
                </div>
            </header>
            
            <!-- Content Container -->
            <div class="content-container">
                <?php echo $content; ?>
            </div>
        </main>
    </div>
    
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mobileMenuBtn = document.querySelector('#mobileMenuBtn');
            const closeSidebarBtn = document.querySelector('#closeSidebarBtn');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            }
            
            if (mobileMenuBtn && sidebar) {
                mobileMenuBtn.addEventListener('click', toggleSidebar);
                closeSidebarBtn.addEventListener('click', toggleSidebar);
                sidebarOverlay.addEventListener('click', toggleSidebar);
                
                // Close sidebar when clicking on a link (mobile only)
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 1025) {
                            toggleSidebar();
                        }
                    });
                });
                
                // Handle window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1025) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    </script>
</body>
</html>