<?php
// This is a VIEW file - should only display data, not process it
// All data comes from the controller via the extract() function

// Check if required data exists
if (!isset($publication) || !isset($categories)) {
    die('Error: View cannot be accessed directly. Please use the controller.');
}

// Variables are already available from controller via extract()
// $publication, $categories, $defaults, $currentFile, $currentThumbnail, etc.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Edit Publication'; ?> - FCT College of Nursing Sciences</title>
    <style>
        /* CSS Variables */
        :root {
            --primary-color: #2c5282;
            --primary-dark: #1a365d;
            --primary-light: #4299e1;
            --success-color: #38a169;
            --warning-color: #d69e2e;
            --danger-color: #e53e3e;
            --info-color: #3182ce;
            
            --gray-50: #f7fafc;
            --gray-100: #edf2f7;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e0;
            --gray-400: #a0aec0;
            --gray-500: #718096;
            --gray-600: #4a5568;
            --gray-700: #2d3748;
            --gray-800: #1a202c;
            
            --sidebar-width: 260px;
            --header-height: 70px;
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
        }
        
        /* Main Layout Container */
        .layout-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Fixed Sidebar - Desktop */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            flex-shrink: 0;
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--gray-800);
        }
        
        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
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
            line-height: 1.2;
        }
        
        .sidebar-subtitle {
            font-size: 0.75rem;
            color: var(--gray-600);
            line-height: 1.2;
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
            color: var(--gray-600);
            padding: 0 1.5rem 0.5rem;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid var(--gray-200);
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
            color: var(--gray-700);
            transition: all 0.2s;
        }
        
        .nav-link:hover {
            background: var(--gray-100);
            color: var(--primary-color);
        }
        
        .nav-link.active {
            background: var(--gray-100);
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
        }
        
        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--gray-200);
            flex-shrink: 0;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
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
        }
        
        .user-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.125rem;
        }
        
        .user-info span {
            font-size: 0.75rem;
            color: var(--gray-600);
        }
        
        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-width: 0; /* Prevents overflow */
        }
        
        /* Page Header */
        .page-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 1.5rem 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 100%;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        /* Main Content Container */
        .content-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
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
            color: var(--success-color);
        }
        
        .alert-error {
            background: rgba(229, 62, 62, 0.1);
            border: 1px solid rgba(229, 62, 62, 0.2);
            color: var(--danger-color);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
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
        
        .btn-danger {
            background: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background: #c53030;
        }
        
        /* Form Layout */
        .form-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: start;
        }
        
        /* Form Cards */
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .form-card-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .form-card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-800);
        }
        
        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .form-label.required::after {
            content: " *";
            color: var(--danger-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
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
        
        .form-control.is-invalid {
            border-color: var(--danger-color);
        }
        
        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: var(--danger-color);
        }
        
        .form-text {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: var(--gray-600);
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        /* Grid Layouts */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
        }
        
        /* Checkboxes */
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1px solid var(--gray-300);
            cursor: pointer;
        }
        
        .form-check-label {
            font-size: 0.875rem;
            cursor: pointer;
        }
        
        /* Current Files Display */
        .current-file {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .current-file-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .current-file-title {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.875rem;
        }
        
        .current-file-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-600);
            font-size: 0.875rem;
        }
        
        .file-delete-btn {
            background: none;
            border: none;
            color: var(--danger-color);
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .file-delete-btn:hover {
            background: rgba(229, 62, 62, 0.1);
        }
        
        .thumbnail-preview {
            width: 100%;
            max-width: 200px;
            height: auto;
            border-radius: 6px;
            border: 1px solid var(--gray-200);
            margin-top: 0.5rem;
        }
        
        /* File Upload */
        .file-upload {
            border: 2px dashed var(--gray-300);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background: var(--gray-50);
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .file-upload:hover {
            border-color: var(--primary-color);
            background: var(--gray-100);
        }
        
        .file-upload input[type="file"] {
            display: none;
        }
        
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-600);
        }
        
        /* Publication Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-top: 1rem;
        }
        
        .stat-item {
            background: var(--gray-50);
            border-radius: 6px;
            padding: 0.75rem;
            text-align: center;
        }
        
        .stat-value {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 1rem;
            display: block;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: var(--gray-600);
            display: block;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--gray-200);
        }
        
        .action-buttons .btn {
            flex: 1;
        }
        
        /* Progress Indicator */
        .progress-indicator {
            background: rgba(49, 130, 206, 0.1);
            border: 1px solid rgba(49, 130, 206, 0.2);
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: none;
            align-items: center;
            gap: 0.75rem;
        }
        
        .progress-indicator.show {
            display: flex;
        }
        
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid var(--gray-300);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Delete Modal */
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
            width: 90%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            margin-bottom: 1.5rem;
        }
        
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .modal-body {
            margin-bottom: 2rem;
        }
        
        .modal-footer {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .form-layout {
                grid-template-columns: 1fr;
            }
            
            .grid-3 {
                grid-template-columns: 1fr 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .layout-container {
                flex-direction: column;
            }
            
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                display: none; /* Hide sidebar on mobile, show hamburger menu instead */
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-container {
                padding: 1rem;
            }
            
            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .page-header {
                padding: 1rem;
            }
            
            .page-header-content {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            /* Mobile Menu Toggle */
            .mobile-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                background: white;
                border-bottom: 1px solid var(--gray-200);
            }
            
            .sidebar.active {
                display: flex;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-header {
                display: none;
            }
        }
        
        /* Character Count */
        .char-count {
            font-size: 0.75rem;
            color: var(--gray-600);
            text-align: right;
            margin-top: 0.25rem;
        }
        
        /* Full-width on small screens */
        @media (max-width: 640px) {
            .form-card {
                padding: 1rem;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        /* Print Styles */
        @media print {
            .sidebar,
            .page-header {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .form-card {
                box-shadow: none;
                border: 1px solid var(--gray-300);
            }
            
            .action-buttons,
            .file-upload {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="layout-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="sidebar-brand">
                    <div class="sidebar-logo">FCT</div>
                    <div>
                        <div class="sidebar-title">FCT CNS</div>
                        <div class="sidebar-subtitle">Research Portal</div>
                    </div>
                </a>
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
        <main class="main-content">
            <!-- Page Header -->
            <header class="page-header">
                <div class="page-header-content">
                    <h1 class="page-title">Edit Publication</h1>
                    <div>
                        <a href="<?php echo BASE_URL; ?>/admin/research" class="btn btn-outline">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Back to List
                        </a>
                    </div>
                </div>
            </header>
            
            <!-- Content Container -->
            <div class="content-container">
                <!-- Flash Messages -->
                <?php if ($flash_success ?? false): ?>
                <div class="flash-messages">
                    <div class="alert alert-success">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo htmlspecialchars($flash_success); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($flash_error ?? false): ?>
                <div class="flash-messages">
                    <div class="alert alert-error">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo htmlspecialchars($flash_error); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Progress Indicator -->
                <div class="progress-indicator" id="progressIndicator">
                    <div class="spinner"></div>
                    <span>Updating publication...</span>
                </div>
                
                <!-- Main Form -->
                <form id="publicationForm" method="POST" action="<?php echo BASE_URL; ?>/admin/research/<?php echo $publicationId; ?>/update" enctype="multipart/form-data">
                    <!-- CSRF Token and Method Spoofing -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
                    
                    <div class="form-layout">
                        <!-- Left Column - Main Content -->
                        <div>
                            <!-- Basic Information -->
                            <div class="form-card">
                                <div class="form-card-header">
                                    <h2 class="form-card-title">Basic Information</h2>
                                </div>
                                
                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title" class="form-label required">Title</label>
                                    <input type="text" class="form-control <?php echo isset($flash_errors['title']) ? 'is-invalid' : ''; ?>" 
                                           id="title" name="title" 
                                           value="<?php echo htmlspecialchars($defaults['title'] ?? ''); ?>"
                                           required maxlength="500">
                                    <?php if (isset($flash_errors['title'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($flash_errors['title']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-text">Maximum 500 characters</div>
                                </div>
                                
                                <!-- Authors -->
                                <div class="form-group">
                                    <label for="authors" class="form-label required">Authors</label>
                                    <textarea class="form-control <?php echo isset($flash_errors['authors']) ? 'is-invalid' : ''; ?>" 
                                              id="authors" name="authors" rows="3" required><?php echo htmlspecialchars($defaults['authors'] ?? ''); ?></textarea>
                                    <?php if (isset($flash_errors['authors'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($flash_errors['authors']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-text">Enter authors separated by commas. Format: Lastname, Firstname</div>
                                </div>
                                
                                <!-- Abstract -->
                                <div class="form-group">
                                    <label for="abstract" class="form-label required">Abstract</label>
                                    <textarea class="form-control <?php echo isset($flash_errors['abstract']) ? 'is-invalid' : ''; ?>" 
                                              id="abstract" name="abstract" rows="6" required
                                              minlength="200"><?php echo htmlspecialchars($defaults['abstract'] ?? ''); ?></textarea>
                                    <?php if (isset($flash_errors['abstract'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($flash_errors['abstract']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-text">Minimum 200 characters. Current: <span id="abstractCount"><?php echo strlen($defaults['abstract'] ?? ''); ?></span> characters</div>
                                </div>
                                
                                <!-- Keywords -->
                                <div class="form-group">
                                    <label for="keywords" class="form-label">Keywords</label>
                                    <input type="text" class="form-control" id="keywords" name="keywords"
                                           value="<?php echo htmlspecialchars($defaults['keywords'] ?? ''); ?>">
                                    <div class="form-text">Separate keywords with commas</div>
                                </div>
                            </div>
                            
                            <!-- Publication Details -->
                            <div class="form-card">
                                <div class="form-card-header">
                                    <h2 class="form-card-title">Publication Details</h2>
                                </div>
                                
                                <div class="grid-2">
                                    <!-- Publication Type -->
                                    <div class="form-group">
                                        <label for="publication_type" class="form-label required">Publication Type</label>
                                        <select class="form-control" id="publication_type" name="publication_type" required>
                                            <option value="">Select Type</option>
                                            <option value="journal" <?php echo ($defaults['publication_type'] ?? '') == 'journal' ? 'selected' : ''; ?>>Journal Article</option>
                                            <option value="conference" <?php echo ($defaults['publication_type'] ?? '') == 'conference' ? 'selected' : ''; ?>>Conference Paper</option>
                                            <option value="book" <?php echo ($defaults['publication_type'] ?? '') == 'book' ? 'selected' : ''; ?>>Book/Chapter</option>
                                            <option value="thesis" <?php echo ($defaults['publication_type'] ?? '') == 'thesis' ? 'selected' : ''; ?>>Thesis/Dissertation</option>
                                            <option value="report" <?php echo ($defaults['publication_type'] ?? '') == 'report' ? 'selected' : ''; ?>>Technical Report</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Research Area -->
                                    <div class="form-group">
                                        <label for="research_area" class="form-label required">Research Area</label>
                                        <select class="form-control <?php echo isset($flash_errors['research_area']) ? 'is-invalid' : ''; ?>" 
                                                id="research_area" name="research_area" required>
                                            <option value="">Select Research Area</option>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['slug']; ?>" 
                                                <?php echo ($defaults['research_area'] ?? '') == $category['slug'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($flash_errors['research_area'])): ?>
                                            <div class="invalid-feedback">
                                                <?php echo htmlspecialchars($flash_errors['research_area']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Journal/Conference Details -->
                                <div id="journalDetails" style="display: <?php echo in_array($defaults['publication_type'] ?? '', ['journal', 'conference']) ? 'block' : 'none'; ?>; margin-bottom: 1.5rem;">
                                    <div class="grid-3">
                                        <div class="form-group">
                                            <label for="journal_name" class="form-label">Journal/Conference Name</label>
                                            <input type="text" class="form-control" id="journal_name" name="journal_name"
                                                   value="<?php echo htmlspecialchars($defaults['journal_name'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="volume" class="form-label">Volume</label>
                                            <input type="text" class="form-control" id="volume" name="volume"
                                                   value="<?php echo htmlspecialchars($defaults['volume'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="issue" class="form-label">Issue</label>
                                            <input type="text" class="form-control" id="issue" name="issue"
                                                   value="<?php echo htmlspecialchars($defaults['issue'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Pages, Publisher, Date -->
                                <div class="grid-3">
                                    <div class="form-group">
                                        <label for="pages" class="form-label">Pages</label>
                                        <input type="text" class="form-control" id="pages" name="pages"
                                               value="<?php echo htmlspecialchars($defaults['pages'] ?? ''); ?>"
                                               placeholder="e.g., 123-145">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="publisher" class="form-label">Publisher</label>
                                        <input type="text" class="form-control" id="publisher" name="publisher"
                                               value="<?php echo htmlspecialchars($defaults['publisher'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="publication_date" class="form-label required">Publication Date</label>
                                        <input type="date" class="form-control <?php echo isset($flash_errors['publication_date']) ? 'is-invalid' : ''; ?>" 
                                               id="publication_date" name="publication_date" 
                                               value="<?php echo htmlspecialchars($defaults['publication_date'] ?? ''); ?>" required>
                                        <?php if (isset($flash_errors['publication_date'])): ?>
                                            <div class="invalid-feedback">
                                                <?php echo htmlspecialchars($flash_errors['publication_date']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- DOI and URL -->
                                <div class="grid-2">
                                    <div class="form-group">
                                        <label for="doi" class="form-label">DOI</label>
                                        <input type="text" class="form-control <?php echo isset($flash_errors['doi']) ? 'is-invalid' : ''; ?>" 
                                               id="doi" name="doi"
                                               value="<?php echo htmlspecialchars($defaults['doi'] ?? ''); ?>"
                                               placeholder="e.g., 10.1000/182">
                                        <?php if (isset($flash_errors['doi'])): ?>
                                            <div class="invalid-feedback">
                                                <?php echo htmlspecialchars($flash_errors['doi']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="url" class="form-label">URL</label>
                                        <input type="url" class="form-control" id="url" name="url"
                                               value="<?php echo htmlspecialchars($defaults['url'] ?? ''); ?>"
                                               placeholder="https://example.com">
                                    </div>
                                </div>
                                
                                <!-- Citations and Impact Factor -->
                                <div class="grid-2">
                                    <div class="form-group">
                                        <label for="citations" class="form-label">Citations</label>
                                        <input type="number" class="form-control" id="citations" name="citations"
                                               value="<?php echo htmlspecialchars($defaults['citations'] ?? 0); ?>" min="0">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="impact_factor" class="form-label">Impact Factor</label>
                                        <input type="number" class="form-control" id="impact_factor" name="impact_factor"
                                               value="<?php echo htmlspecialchars($defaults['impact_factor'] ?? ''); ?>"
                                               step="0.001" min="0" placeholder="e.g., 3.456">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Sidebar -->
                        <div>
                            <!-- Publication Status -->
                            <div class="form-card">
                                <div class="form-card-header">
                                    <h2 class="form-card-title">Publication Status</h2>
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1"
                                        <?php echo ($defaults['is_published'] ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_published">
                                        Publish this publication
                                    </label>
                                </div>
                                <div class="form-text" style="margin-top: 0.5rem; margin-bottom: 1rem;">
                                    Published articles will appear on the public research page
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1"
                                        <?php echo ($defaults['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_featured">
                                        Feature this publication
                                    </label>
                                </div>
                                <div class="form-text" style="margin-top: 0.5rem;">
                                    Featured articles will be highlighted on the public research page
                                </div>
                            </div>
                            
                            <!-- Research File -->
                            <div class="form-card">
                                <div class="form-card-header">
                                    <h2 class="form-card-title">Research File</h2>
                                </div>
                                
                                <?php if ($currentFile ?? ''): ?>
                                <div class="current-file">
                                    <div class="current-file-header">
                                        <div class="current-file-title">Current File</div>
                                        <button type="button" class="file-delete-btn" onclick="removeCurrentFile('research_file')" title="Remove current file">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="current-file-info">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span><?php echo basename($currentFile ?? ''); ?></span>
                                        <a href="<?php echo BASE_URL . '/' . ($currentFile ?? ''); ?>" target="_blank" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                    </div>
                                    <input type="hidden" id="current_research_file" name="current_research_file" value="<?php echo $currentFile ?? ''; ?>">
                                </div>
                                <?php endif; ?>
                                
                                <div class="file-upload" style="margin-top: 1rem;">
                                    <label for="research_file" class="file-upload-label">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span><?php echo ($currentFile ?? '') ? 'Replace research file' : 'Click to upload research file'; ?></span>
                                        <span style="font-size: 0.75rem;">(PDF, DOC, DOCX - Max: 10MB)</span>
                                    </label>
                                    <input type="file" id="research_file" name="research_file"
                                           accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                </div>
                            </div>
                            
                            <!-- Thumbnail Image -->
                            <div class="form-card">
                                <div class="form-card-header">
                                    <h2 class="form-card-title">Thumbnail Image</h2>
                                </div>
                                
                                <?php if ($currentThumbnail ?? ''): ?>
                                <div class="current-file">
                                    <div class="current-file-header">
                                        <div class="current-file-title">Current Thumbnail</div>
                                        <button type="button" class="file-delete-btn" onclick="removeCurrentFile('thumbnail')" title="Remove current thumbnail">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <img src="<?php echo BASE_URL . '/' . ($currentThumbnail ?? ''); ?>" alt="Current thumbnail" class="thumbnail-preview">
                                    <input type="hidden" id="current_thumbnail" name="current_thumbnail" value="<?php echo $currentThumbnail ?? ''; ?>">
                                </div>
                                <?php endif; ?>
                                
                                <div class="file-upload" style="margin-top: 1rem;">
                                    <label for="thumbnail" class="file-upload-label">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                        <span><?php echo ($currentThumbnail ?? '') ? 'Replace thumbnail' : 'Click to upload thumbnail'; ?></span>
                                        <span style="font-size: 0.75rem;">(JPG, PNG, GIF, WebP - Max: 2MB)</span>
                                    </label>
                                    <input type="file" id="thumbnail" name="thumbnail"
                                           accept="image/jpeg,image/png,image/gif,image/webp">
                                </div>
                            </div>
                            
                            <!-- Publication Stats -->
                            <div class="form-card">
                                <div class="form-card-header">
                                    <h2 class="form-card-title">Publication Statistics</h2>
                                </div>
                                
                                <div class="stats-grid">
                                    <div class="stat-item">
                                        <span class="stat-value"><?php echo $publication['views'] ?? 0; ?></span>
                                        <span class="stat-label">Views</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value"><?php echo $publication['downloads'] ?? 0; ?></span>
                                        <span class="stat-label">Downloads</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value"><?php echo $publication['created_by'] ?? 'Unknown'; ?></span>
                                        <span class="stat-label">Created By</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value"><?php echo date('M d, Y', strtotime($publication['created_at'] ?? date('Y-m-d'))); ?></span>
                                        <span class="stat-label">Created Date</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="form-card">
                                <div class="action-buttons">
                                    <button type="submit" name="save" class="btn btn-primary">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Update Publication
                                    </button>
                                    
                                    <button type="submit" name="save_and_view" class="btn btn-outline">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Update & View
                                    </button>
                                </div>
                                
                                <div class="action-buttons">
                                    <a href="<?php echo BASE_URL; ?>/research/<?php echo $publicationId; ?>" target="_blank" class="btn btn-outline" style="flex: 1;">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        View Public Page
                                    </a>
                                    
                                    <button type="button" onclick="showDeleteModal()" class="btn btn-danger" style="flex: 1;">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Delete Publication</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this publication?</p>
                <p><strong><?php echo htmlspecialchars($publication['title'] ?? ''); ?></strong></p>
                <p class="form-text">This action cannot be undone. All associated files will also be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="hideDeleteModal()">Cancel</button>
                <form method="POST" action="<?php echo BASE_URL; ?>/admin/research/<?php echo $publicationId; ?>/delete" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
                    <button type="submit" class="btn btn-danger">Delete Publication</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('publicationForm');
            const progressIndicator = document.getElementById('progressIndicator');
            const abstractTextarea = document.getElementById('abstract');
            const abstractCount = document.getElementById('abstractCount');
            const publicationType = document.getElementById('publication_type');
            const journalDetails = document.getElementById('journalDetails');
            const publicationDate = document.getElementById('publication_date');
            const deleteModal = document.getElementById('deleteModal');
            
            // Abstract character count
            function updateAbstractCount() {
                abstractCount.textContent = abstractTextarea.value.length;
            }
            
            abstractTextarea.addEventListener('input', updateAbstractCount);
            updateAbstractCount(); // Initial count
            
            // Show/hide journal details based on publication type
            function toggleJournalDetails() {
                if (publicationType.value === 'journal' || publicationType.value === 'conference') {
                    journalDetails.style.display = 'block';
                } else {
                    journalDetails.style.display = 'none';
                }
            }
            
            publicationType.addEventListener('change', toggleJournalDetails);
            
            // Form validation and submission
            form.addEventListener('submit', function(e) {
                // Client-side validation
                let isValid = true;
                
                // Required fields
                const requiredFields = ['title', 'authors', 'abstract', 'research_area', 'publication_date'];
                requiredFields.forEach(fieldName => {
                    const field = form.elements[fieldName];
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    }
                });
                
                // Abstract minimum length
                if (abstractTextarea.value.length < 200) {
                    abstractTextarea.classList.add('is-invalid');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields correctly.');
                    return;
                }
                
                // Show progress indicator
                progressIndicator.classList.add('show');
                
                // Disable submit buttons to prevent double submission
                const submitButtons = form.querySelectorAll('button[type="submit"]');
                submitButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.innerHTML = '<svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="animation: spin 1s linear infinite;"><path d="M4 10a6 6 0 1112 0 6 6 0 01-12 0z" fill="none" stroke="currentColor" stroke-width="2"/></svg> Updating...';
                });
            });
            
            // File upload preview
            const researchFileInput = document.getElementById('research_file');
            const thumbnailInput = document.getElementById('thumbnail');
            
            researchFileInput.addEventListener('change', function() {
                const label = this.previousElementSibling;
                if (this.files.length > 0) {
                    label.innerHTML = `
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>${this.files[0].name}</span>
                        <span style="font-size: 0.75rem;">(${(this.files[0].size / 1024 / 1024).toFixed(2)} MB)</span>
                    `;
                }
            });
            
            thumbnailInput.addEventListener('change', function() {
                const label = this.previousElementSibling;
                if (this.files.length > 0) {
                    // Show image preview if it's an image
                    if (this.files[0].type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Remove any existing preview
                            const existingPreview = label.querySelector('img');
                            if (existingPreview) {
                                existingPreview.remove();
                            }
                            
                            // Add new preview
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.maxWidth = '200px';
                            img.style.maxHeight = '150px';
                            img.style.marginTop = '0.5rem';
                            img.style.borderRadius = '6px';
                            img.style.border = '1px solid var(--gray-200)';
                            label.appendChild(img);
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                    
                    label.innerHTML = `
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>${this.files[0].name}</span>
                        <span style="font-size: 0.75rem;">(${(this.files[0].size / 1024 / 1024).toFixed(2)} MB)</span>
                    `;
                }
            });
            
            // Remove current file
            window.removeCurrentFile = function(type) {
                if (confirm('Are you sure you want to remove the current file?')) {
                    const currentFileInput = document.getElementById('current_' + type);
                    if (currentFileInput) {
                        currentFileInput.value = '';
                    }
                    
                    // Hide the current file display
                    const currentFileDiv = currentFileInput.closest('.current-file');
                    if (currentFileDiv) {
                        currentFileDiv.style.display = 'none';
                    }
                }
            };
            
            // Delete modal functions
            window.showDeleteModal = function() {
                deleteModal.classList.add('active');
            };
            
            window.hideDeleteModal = function() {
                deleteModal.classList.remove('active');
            };
            
            // Close modal when clicking outside
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    hideDeleteModal();
                }
            });
        });
    </script>
</body>
</html>