<?php
// This is a VIEW file - should only display data, not process it
// All data comes from the controller via the extract() function

// Check if required data exists
if (!isset($publications) || !isset($categories)) {
    die('Error: View cannot be accessed directly. Please use the controller.');
}

// Variables are already available from controller via extract()
// $publications, $categories, $stats, $filters, $userRole, $username, etc.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Publications - FCT College of Nursing Sciences</title>
    <style>
        /* CSS Variables for theming */
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
        
        /* Reset & Base Styles */
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
        
        /* Main Layout Container */
        .app-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }
        
        /* Sidebar Styles */
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
        
        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-width: 0; /* Prevents content from overflowing */
        }
        
        /* Top Header */
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
        
        /* Content Wrapper */
        .content-wrapper {
            padding: var(--spacing-lg);
        }
        
        /* Flash Messages */
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
        
        /* Page Actions */
        .page-actions {
            display: flex;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-xl);
        }
        
        /* Buttons */
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
        
        /* Statistics Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-xl);
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: var(--spacing-lg);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary-color);
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
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: var(--spacing-xs);
        }
        
        /* Filters Section */
        .filters-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
        }
        
        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-lg);
            flex-wrap: wrap;
            gap: var(--spacing-md);
        }
        
        .filters-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--spacing-md);
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
            padding: 0.625rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23718096' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        /* Bulk Actions */
        .bulk-actions {
            display: none;
            align-items: center;
            gap: var(--spacing-md);
            background: var(--gray-50);
            padding: var(--spacing-md);
            border-radius: 8px;
            margin-bottom: var(--spacing-lg);
            flex-wrap: wrap;
        }
        
        .bulk-actions.show {
            display: flex;
        }
        
        /* Publications Table */
        .publications-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .publications-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        
        .publications-table th {
            text-align: left;
            padding: var(--spacing-md);
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 1px solid var(--gray-200);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
        
        .publications-table td {
            padding: var(--spacing-md);
            border-bottom: 1px solid var(--gray-100);
            vertical-align: top;
        }
        
        .publications-table tr:last-child td {
            border-bottom: none;
        }
        
        .publications-table tr:hover {
            background: var(--gray-50);
        }
        
        .publication-title {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 4px;
            word-break: break-word;
        }
        
        .publication-authors {
            font-size: 0.875rem;
            color: var(--gray-600);
            line-height: 1.4;
            word-break: break-word;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
        
        .bg-success {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success-color);
        }
        
        .bg-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }
        
        .bg-info {
            background: rgba(49, 130, 206, 0.1);
            color: var(--info-color);
        }
        
        .bg-warning {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning-color);
        }
        
        .action-cell {
            white-space: nowrap;
        }
        
        .action-buttons {
            display: flex;
            gap: var(--spacing-xs);
            flex-wrap: wrap;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: var(--spacing-2xl) var(--spacing-md);
            color: var(--gray-600);
        }
        
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: var(--spacing-lg);
            color: var(--gray-300);
        }
        
        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: var(--spacing-sm);
            color: var(--gray-700);
        }
        
        .empty-state p {
            margin-bottom: var(--spacing-lg);
        }
        
        /* Modal */
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
            z-index: 1100;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            padding: var(--spacing-md);
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background: white;
            border-radius: 12px;
            padding: var(--spacing-lg);
            width: 100%;
            max-width: 400px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            margin-bottom: var(--spacing-lg);
        }
        
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .modal-body {
            margin-bottom: var(--spacing-xl);
        }
        
        .modal-footer {
            display: flex;
            gap: var(--spacing-md);
            justify-content: flex-end;
            flex-wrap: wrap;
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
        }
        
        /* Responsive Design */
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
            
            .filter-form {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .content-wrapper {
                padding: var(--spacing-md);
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-form {
                grid-template-columns: 1fr;
            }
            
            .page-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .filters-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .modal-footer {
                flex-direction: column;
            }
            
            .modal-footer .btn {
                width: 100%;
            }
            
            .action-buttons {
                justify-content: center;
            }
        }
        
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .top-header {
                padding: 0 var(--spacing-md);
            }
            
            .content-wrapper {
                padding: var(--spacing-sm);
            }
            
            .page-title {
                font-size: 1.125rem;
            }
            
            .bulk-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .bulk-actions .btn,
            .bulk-actions .form-control {
                width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .publications-table {
                min-width: 600px;
            }
            
            .stat-card {
                padding: var(--spacing-md);
            }
            
            .stat-value {
                font-size: 1.25rem;
            }
        }
        
        /* Utility Classes */
        .d-none {
            display: none !important;
        }
        
        .d-flex {
            display: flex !important;
        }
        
        .w-100 {
            width: 100%;
        }
        
        .mb-0 { margin-bottom: 0 !important; }
        .mb-1 { margin-bottom: var(--spacing-xs) !important; }
        .mb-2 { margin-bottom: var(--spacing-sm) !important; }
        .mb-3 { margin-bottom: var(--spacing-md) !important; }
        .mb-4 { margin-bottom: var(--spacing-lg) !important; }
        .mb-5 { margin-bottom: var(--spacing-xl) !important; }
        
        .mt-1 { margin-top: var(--spacing-xs) !important; }
        .mt-2 { margin-top: var(--spacing-sm) !important; }
        .mt-3 { margin-top: var(--spacing-md) !important; }
        .mt-4 { margin-top: var(--spacing-lg) !important; }
        .mt-5 { margin-top: var(--spacing-xl) !important; }
        
        .mx-auto {
            margin-left: auto !important;
            margin-right: auto !important;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .flex-wrap {
            flex-wrap: wrap;
        }
        
        .align-items-center {
            align-items: center;
        }
        
        .justify-content-between {
            justify-content: space-between;
        }
        
        .flex-column {
            flex-direction: column;
        }
        
        .gap-1 { gap: var(--spacing-xs); }
        .gap-2 { gap: var(--spacing-sm); }
        .gap-3 { gap: var(--spacing-md); }
        .gap-4 { gap: var(--spacing-lg); }
        .gap-5 { gap: var(--spacing-xl); }
        
        /* Print Styles */
        @media print {
            .app-sidebar,
            .top-header,
            .bulk-actions,
            .action-buttons,
            .filters-section,
            .page-actions {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .content-wrapper {
                padding: 0 !important;
            }
            
            .publications-table th {
                background: var(--gray-100) !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
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
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <h1 class="page-title">Research Publications</h1>
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
            
            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Flash Messages -->
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
                
                <!-- Page Actions -->
                <div class="page-actions">
                    <a href="<?php echo BASE_URL; ?>/admin/research/create" class="btn btn-primary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Add New Publication
                    </a>
                </div>
                
                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-title">Total Publications</div>
                        <div class="stat-value"><?php echo $stats['total_publications'] ?? 0; ?></div>
                    </div>
                    
                    <div class="stat-card success">
                        <div class="stat-title">Published</div>
                        <div class="stat-value"><?php echo $stats['published_count'] ?? 0; ?></div>
                    </div>
                    
                    <div class="stat-card info">
                        <div class="stat-title">Total Views</div>
                        <div class="stat-value"><?php echo $stats['total_views'] ?? 0; ?></div>
                    </div>
                    
                    <div class="stat-card warning">
                        <div class="stat-title">Featured</div>
                        <div class="stat-value"><?php echo $stats['featured_count'] ?? 0; ?></div>
                    </div>
                </div>
                
                <!-- Filters -->
                <div class="filters-section">
                    <div class="filters-header">
                        <h2 class="filters-title">Filters</h2>
                        <a href="<?php echo BASE_URL; ?>/admin/research" class="btn btn-sm btn-outline">Clear Filters</a>
                    </div>
                    
                    <form method="GET" class="filter-form">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="1" <?php echo ($filters['status'] ?? '') == '1' ? 'selected' : ''; ?>>Published</option>
                                <option value="0" <?php echo ($filters['status'] ?? '') == '0' ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Research Area</label>
                            <select name="category" class="form-control">
                                <option value="">All Areas</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['slug']; ?>" <?php echo ($filters['category'] ?? '') == $category['slug'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Publication Type</label>
                            <select name="type" class="form-control">
                                <option value="">All Types</option>
                                <option value="journal" <?php echo ($filters['type'] ?? '') == 'journal' ? 'selected' : ''; ?>>Journal</option>
                                <option value="conference" <?php echo ($filters['type'] ?? '') == 'conference' ? 'selected' : ''; ?>>Conference</option>
                                <option value="book" <?php echo ($filters['type'] ?? '') == 'book' ? 'selected' : ''; ?>>Book</option>
                                <option value="thesis" <?php echo ($filters['type'] ?? '') == 'thesis' ? 'selected' : ''; ?>>Thesis</option>
                                <option value="report" <?php echo ($filters['type'] ?? '') == 'report' ? 'selected' : ''; ?>>Report</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Year</label>
                            <input type="number" name="year" class="form-control" placeholder="e.g., 2024" 
                                   value="<?php echo htmlspecialchars($filters['year'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search title, authors, keywords..."
                                   value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Order By</label>
                            <select name="order_by" class="form-control">
                                <option value="publication_date" <?php echo ($filters['order_by'] ?? '') == 'publication_date' ? 'selected' : ''; ?>>Date</option>
                                <option value="title" <?php echo ($filters['order_by'] ?? '') == 'title' ? 'selected' : ''; ?>>Title</option>
                                <option value="views_count" <?php echo ($filters['order_by'] ?? '') == 'views_count' ? 'selected' : ''; ?>>Views</option>
                                <option value="citations" <?php echo ($filters['order_by'] ?? '') == 'citations' ? 'selected' : ''; ?>>Citations</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Order Direction</label>
                            <select name="order_dir" class="form-control">
                                <option value="DESC" <?php echo ($filters['order_dir'] ?? '') == 'DESC' ? 'selected' : ''; ?>>Descending</option>
                                <option value="ASC" <?php echo ($filters['order_dir'] ?? '') == 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        </div>
                    </form>
                </div>
                
                <!-- Bulk Actions -->
                <div class="bulk-actions" id="bulkActions">
                    <select id="bulkAction" class="form-control">
                        <option value="">Bulk Actions</option>
                        <option value="publish">Publish</option>
                        <option value="unpublish">Unpublish</option>
                        <option value="feature">Feature</option>
                        <option value="unfeature">Unfeature</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button id="applyBulkAction" class="btn btn-primary">Apply</button>
                </div>
                
                <!-- Publications List -->
                <div class="publications-section">
                    <?php if (empty($publications)): ?>
                        <div class="empty-state">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                            </svg>
                            <h3>No Research Publications Found</h3>
                            <p>Get started by adding your first research publication.</p>
                            <a href="<?php echo BASE_URL; ?>/admin/research/create" class="btn btn-primary">
                                Add First Publication
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="publications-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">
                                            <input type="checkbox" id="selectAll" class="select-all-checkbox">
                                        </th>
                                        <th class="publication-cell">Publication</th>
                                        <th style="width: 120px;">Research Area</th>
                                        <th style="width: 100px;">Type</th>
                                        <th style="width: 100px;">Date</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 120px;" class="action-cell">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($publications as $pub): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="publication-checkbox" value="<?php echo $pub['id']; ?>">
                                        </td>
                                        <td class="publication-cell">
                                            <div class="publication-title">
                                                <?php echo htmlspecialchars($pub['title'] ?? ''); ?>
                                                <?php if ($pub['is_featured'] ?? false): ?>
                                                <span class="badge bg-warning">Featured</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="publication-authors">
                                                <?php echo htmlspecialchars(substr($pub['authors'] ?? '', 0, 100)); ?><?php echo strlen($pub['authors'] ?? '') > 100 ? '...' : ''; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($pub['category_name'] ?? $pub['research_area'] ?? ''); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo ucfirst($pub['publication_type'] ?? ''); ?></span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($pub['publication_date'] ?? date('Y-m-d'))); ?></td>
                                        <td>
                                            <?php if ($pub['is_published'] ?? false): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="action-cell">
                                            <div class="action-buttons">
                                                <a href="<?php echo BASE_URL; ?>/admin/research/<?php echo $pub['id']; ?>" 
                                                   class="btn btn-sm btn-outline" title="View">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </a>
                                                
                                                <a href="<?php echo BASE_URL; ?>/admin/research/<?php echo $pub['id']; ?>/edit" 
                                                   class="btn btn-sm btn-outline" title="Edit">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                    </svg>
                                                </a>
                                                
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger delete-publication" 
                                                        data-id="<?php echo $pub['id']; ?>" 
                                                        data-title="<?php echo htmlspecialchars($pub['title'] ?? ''); ?>"
                                                        title="Delete">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Delete</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete "<span id="deletePublicationTitle"></span>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="hideDeleteModal()">Cancel</button>
                <form id="deleteForm" method="POST" action="" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo Session::getCSRFToken(); ?>">
                    <button type="submit" class="btn btn-danger">Delete Publication</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Mobile Sidebar Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.querySelector('.main-content');
        
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
        
        // Delete Modal Functions - UPDATED SECTION
        function showDeleteModal(id, title) {
            document.getElementById('deletePublicationTitle').textContent = title;
            document.getElementById('deleteForm').action = '<?php echo BASE_URL; ?>/admin/research/' + id + '/delete';
            document.getElementById('deleteModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
            document.body.style.overflow = '';
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
        
        // Delete publication buttons
        document.querySelectorAll('.delete-publication').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                showDeleteModal(id, title);
            });
        });
        
        // Handle delete form submission - prevent default and submit normally
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            // Allow the form to submit normally
            // The controller will handle the redirect and flash message
            return true;
        });
        
        // Bulk Actions
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.publication-checkbox');
        const bulkActions = document.getElementById('bulkActions');
        
        if (selectAll && checkboxes.length > 0) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkActions();
            });
            
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkActions);
            });
            
            function updateBulkActions() {
                const checked = document.querySelectorAll('.publication-checkbox:checked');
                if (checked.length > 0) {
                    bulkActions.classList.add('show');
                    // Scroll bulk actions into view on mobile
                    if (window.innerWidth < 768) {
                        bulkActions.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                } else {
                    bulkActions.classList.remove('show');
                }
            }
            
            // Apply bulk action
            document.getElementById('applyBulkAction').addEventListener('click', function() {
                const action = document.getElementById('bulkAction').value;
                const selectedIds = Array.from(document.querySelectorAll('.publication-checkbox:checked'))
                    .map(cb => cb.value);
                
                if (!action || selectedIds.length === 0) {
                    alert('Please select an action and at least one publication.');
                    return;
                }
                
                if (action === 'delete' && !confirm(`Are you sure you want to delete ${selectedIds.length} publication(s)?`)) {
                    return;
                }
                
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo BASE_URL; ?>/admin/research/bulk-action';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = 'csrf_token';
                csrfToken.value = '<?php echo Session::getCSRFToken(); ?>';
                form.appendChild(csrfToken);
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = action;
                form.appendChild(actionInput);
                
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
            });
        }
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Touch support for mobile sidebar swipe
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;
            
            // Swipe right to open sidebar on mobile
            if (swipeDistance > swipeThreshold && window.innerWidth < 1024 && !sidebar.classList.contains('active')) {
                toggleSidebar();
            }
            // Swipe left to close sidebar
            else if (swipeDistance < -swipeThreshold && window.innerWidth < 1024 && sidebar.classList.contains('active')) {
                toggleSidebar();
            }
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure proper layout on load
            if (window.innerWidth < 1024) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>