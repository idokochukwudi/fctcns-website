<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure variables exist with safe defaults
$baseUrl = $baseUrl ?? '';
$type = $type ?? 'news';
$news = $news ?? [];
$categories = $categories ?? [];

// If categories is empty or not an array, provide default nursing categories
$defaultCategories = [
    'Academic News',
    'Research & Publications',
    'Clinical Updates',
    'Student Achievements',
    'Faculty News',
    'Continuing Education',
    'Community Outreach',
    'Health Policy',
    'Nursing Education',
    'Patient Care',
    'Technology in Nursing',
    'International Nursing',
    'Alumni News',
    'Events & Conferences',
    'Accreditation Updates',
    'Scholarships & Awards',
    'Mental Health Nursing',
    'Pediatric Nursing',
    'Geriatric Nursing',
    'Emergency Nursing',
    'Public Health Nursing',
    'Nursing Leadership',
    'Simulation Training',
    'Interprofessional Education'
];

$displayCategories = !empty($categories) ? $categories : $defaultCategories;

// CSRF token
$csrfToken = $csrf_token ?? $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = $csrfToken;
}

// Get flash messages
$flashSuccess = $_SESSION['flash_success'] ?? $flash_success ?? '';
$flashError = $_SESSION['flash_error'] ?? $flash_error ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// Get user info
$userName = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'News Manager';
$userRole = $_SESSION['user_role'] ?? 'news_manager';

// Page title based on type
$pageTitle = $type === 'event' ? 'Create New Event' : 'Create News Article';
$icon = $type === 'event' ? 'fa-calendar-plus' : 'fa-plus-circle';

// Ensure all expected fields exist with default values
$news = array_merge([
    'id' => 0,
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'category' => '',
    'tags' => '',
    'featured_image' => '',
    'is_published' => 1,
    'is_featured' => 0,
    'is_breaking' => 0,
    'meta_title' => '',
    'meta_description' => '',
    'meta_keywords' => '',
    'type' => $type,
    'event_date' => date('Y-m-d'),
    'event_end_date' => '',
    'event_time' => '',
    'event_location' => ''
], $news);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>FCT College of Nursing Sciences - <?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2c3e50;
            --primary-dark: #1a252f;
            --secondary: #3498db;
            --secondary-dark: #2980b9;
            --success: #27ae60;
            --success-dark: #219a52;
            --warning: #f39c12;
            --danger: #e74c3c;
            --danger-dark: #c0392b;
            --info: #3498db;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --gray: #95a5a6;
            --gray-light: #ecf0f1;
            --border: #bdc3c7;
            --sidebar-width: 260px;
            --header-height: 60px;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
            --shadow-lg: 0 5px 20px rgba(0,0,0,0.15);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            color: var(--dark);
            line-height: 1.5;
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }

        /* Layout */
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
            left: 0;
            top: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.active {
                transform: translateX(0);
            }
        }

        .sidebar-header {
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .college-logo {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--primary);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .college-name {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
            line-height: 1.3;
            word-break: break-word;
        }

        .college-tagline {
            font-size: 11px;
            opacity: 0.8;
            letter-spacing: 0.5px;
        }

        .user-profile {
            padding: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 600;
            color: white;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }

        .user-info {
            min-width: 0;
            flex: 1;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info p {
            font-size: 11px;
            opacity: 0.8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-menu {
            padding: 15px 0;
        }

        .nav-item {
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-size: 14px;
            white-space: nowrap;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--secondary);
        }

        .nav-item.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: var(--secondary);
        }

        .nav-item i {
            width: 20px;
            font-size: 16px;
            flex-shrink: 0;
        }

        .nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 10px 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background-color: #f5f6fa;
            width: calc(100% - var(--sidebar-width));
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Header */
        .header {
            background: white;
            height: var(--header-height);
            padding: 0 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
            width: 100%;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
            flex: 1;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--dark);
            cursor: pointer;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
        }

        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: var(--danger);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logout-btn:hover {
            background: var(--danger-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        }

        @media (max-width: 480px) {
            .logout-btn span {
                display: none;
            }
        }

        /* Container */
        .container-fluid {
            padding: 20px 15px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header h1 i {
            color: var(--secondary);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: white;
            color: var(--dark);
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--gray-light);
            transform: translateX(-3px);
        }

        /* Progress Indicator */
        .progress-container {
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 20px;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border);
            transform: translateY(-50%);
            z-index: 1;
        }

        .progress-step {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex: 1;
            background: white;
            padding: 0 10px;
        }

        .step-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray);
            transition: all 0.3s ease;
        }

        .progress-step.active .step-dot {
            background: var(--secondary);
            border-color: var(--secondary);
            color: white;
        }

        .progress-step.completed .step-dot {
            background: var(--success);
            border-color: var(--success);
            color: white;
        }

        .step-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--gray);
            text-align: center;
        }

        .progress-step.active .step-label {
            color: var(--secondary);
            font-weight: 600;
        }

        .progress-step.completed .step-label {
            color: var(--success);
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            width: 100%;
        }

        .form-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .form-header h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .form-body {
            padding: 25px;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
        }

        .form-label .required {
            color: var(--danger);
            margin-left: 3px;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-control.error {
            border-color: var(--danger);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2395a5a6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }

        /* Quill Editor */
        .editor-container {
            height: 400px;
            margin-bottom: 20px;
        }

        .ql-toolbar {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            background: var(--light);
        }

        .ql-container {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            font-size: 14px;
        }

        /* Checkbox Group */
        .checkbox-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-item label {
            font-size: 14px;
            color: var(--dark);
            cursor: pointer;
        }

        /* Image Upload */
        .image-upload-container {
            border: 2px dashed var(--border);
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .image-upload-container:hover {
            border-color: var(--secondary);
            background: rgba(52, 152, 219, 0.05);
        }

        .image-upload-container i {
            font-size: 48px;
            color: var(--gray);
            margin-bottom: 10px;
        }

        .image-upload-container p {
            color: var(--gray);
            font-size: 14px;
        }

        .image-upload-container .small {
            font-size: 12px;
            margin-top: 5px;
            color: var(--gray);
        }

        .image-preview {
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            display: none;
        }

        .image-preview.active {
            display: block;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
        }

        .preview-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .image-info {
            flex: 1;
            min-width: 200px;
        }

        .image-info p {
            margin-bottom: 5px;
            font-size: 13px;
        }

        .image-info .file-name {
            font-weight: 600;
            color: var(--dark);
        }

        .image-info .file-size {
            color: var(--gray);
        }

        .btn-remove-image {
            background: var(--danger);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-remove-image:hover {
            background: var(--danger-dark);
        }

        .current-image {
            margin-top: 15px;
            padding: 15px;
            background: var(--gray-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .current-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }

        /* Event Fields */
        .event-fields {
            margin-top: 20px;
            padding: 20px;
            background: var(--gray-light);
            border-radius: 8px;
        }

        .datetime-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        /* Field Error */
        .field-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        /* Tab Navigation */
        .tab-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--secondary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: var(--success-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--gray);
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-outline {
            background: white;
            color: var(--secondary);
            border: 1px solid var(--secondary);
        }

        .btn-outline:hover {
            background: var(--secondary);
            color: white;
        }

        @media (max-width: 480px) {
            .tab-navigation {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Flash Messages */
        .flash-message {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideIn 0.3s ease;
            gap: 10px;
            flex-wrap: wrap;
        }

        .flash-success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
            border: 1px solid rgba(39, 174, 96, 0.2);
        }

        .flash-error {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger);
            border: 1px solid rgba(231, 76, 60, 0.2);
        }

        .flash-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 18px;
            flex-shrink: 0;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Info Box */
        .info-box {
            background: rgba(52, 152, 219, 0.1);
            border: 1px solid rgba(52, 152, 219, 0.2);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-box i {
            color: var(--secondary);
            font-size: 20px;
            flex-shrink: 0;
        }

        .info-box p {
            font-size: 13px;
            color: var(--dark);
            line-height: 1.5;
        }

        /* Character Count */
        .char-count {
            text-align: right;
            font-size: 12px;
            color: var(--gray);
            margin-top: 5px;
        }

        .char-count.warning {
            color: var(--warning);
        }

        .char-count.danger {
            color: var(--danger);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-header {
                padding: 15px 20px;
            }
            
            .form-header h2 {
                font-size: 18px;
            }
            
            .form-body {
                padding: 20px 15px;
            }
            
            .progress-steps {
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .progress-step {
                min-width: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="college-logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="college-name">FCT College of Nursing Sciences</div>
                <div class="college-tagline">Excellence in Nursing Education</div>
            </div>

            <div class="user-profile">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($userName, 0, 2)); ?>
                </div>
                <div class="user-info">
                    <h4 class="text-truncate"><?php echo htmlspecialchars($userName); ?></h4>
                    <p class="text-truncate"><?php echo ucfirst(str_replace('_', ' ', $userRole)); ?></p>
                </div>
            </div>

            <nav class="nav-menu">
                <a href="<?php echo $baseUrl; ?>/admin/news-manager" class="nav-item">
                    <i class="fas fa-newspaper"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="<?php echo $baseUrl; ?>/admin/news-manager/create?type=news" class="nav-item active">
                    <i class="fas fa-plus-circle"></i>
                    <span>Create News</span>
                </a>
                <a href="<?php echo $baseUrl; ?>/admin/news-manager/create?type=event" class="nav-item">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Create Event</span>
                </a>

                <div class="nav-divider"></div>

                <a href="<?php echo $baseUrl; ?>/admin/news-manager?status=draft" class="nav-item">
                    <i class="fas fa-pen"></i>
                    <span>Drafts</span>
                </a>

                <a href="<?php echo $baseUrl; ?>/admin/news-manager?type=event" class="nav-item">
                    <i class="fas fa-calendar"></i>
                    <span>Events</span>
                </a>

                <div class="nav-divider"></div>

                <a href="<?php echo $baseUrl; ?>/news" target="_blank" class="nav-item">
                    <i class="fas fa-globe"></i>
                    <span>View Website</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
                </div>
                <div class="header-right">
                    <a href="<?php echo $baseUrl; ?>/logout" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </header>

            <!-- Content Container -->
            <div class="container-fluid">
                <!-- Flash Messages -->
                <?php if ($flashSuccess): ?>
                <div class="flash-message flash-success">
                    <span><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($flashSuccess); ?></span>
                    <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
                <?php endif; ?>
                
                <?php if ($flashError): ?>
                <div class="flash-message flash-error">
                    <span><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($flashError); ?></span>
                    <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
                <?php endif; ?>

                <!-- Page Header -->
                <div class="page-header">
                    <h1>
                        <i class="fas <?php echo $icon; ?>"></i>
                        <?php echo $pageTitle; ?>
                    </h1>
                    <a href="<?php echo $baseUrl; ?>/admin/news-manager" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>

                <!-- Info Box -->
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>
                        <strong>Tip:</strong> Fill in the required fields marked with <span style="color: var(--danger);">*</span>. 
                        You can save as draft and publish later, or publish immediately. Use the tabs to navigate between sections.
                    </p>
                </div>

                <!-- Progress Indicator -->
                <div class="progress-container">
                    <div class="progress-steps">
                        <div class="progress-step active" data-step="1">
                            <div class="step-dot">1</div>
                            <div class="step-label">Content</div>
                        </div>
                        <div class="progress-step" data-step="2">
                            <div class="step-dot">2</div>
                            <div class="step-label">Media</div>
                        </div>
                        <div class="progress-step" data-step="3">
                            <div class="step-dot">3</div>
                            <div class="step-label">SEO</div>
                        </div>
                        <div class="progress-step" data-step="4">
                            <div class="step-dot">4</div>
                            <div class="step-label">Settings</div>
                        </div>
                    </div>
                </div>

                <!-- Main Form -->
                <form method="POST" action="<?php echo $baseUrl; ?>/admin/news-manager/store" 
                      id="newsForm" enctype="multipart/form-data" class="form-card">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    <input type="hidden" name="type" id="form-type" value="<?php echo $type; ?>">
                    <input type="hidden" name="save_draft" id="save_draft" value="0">
                    
                    <!-- Hidden fields for base64 image data -->
                    <input type="hidden" name="featured_image_data" id="featured-image-data" value="">
                    <input type="hidden" name="featured_image_filename" id="featured-image-filename" value="">
                    <input type="hidden" name="remove_image" id="remove_image" value="0">

                    <!-- Form Header -->
                    <div class="form-header">
                        <h2>
                            <i class="fas <?php echo $icon; ?>"></i>
                            <?php echo $type === 'event' ? 'Event Details' : 'News Article Details'; ?>
                        </h2>
                        <p>Enter the details for your <?php echo $type === 'event' ? 'event' : 'news article'; ?> below</p>
                    </div>

                    <!-- Form Body -->
                    <div class="form-body">
                        <!-- TAB 1: Content -->
                        <div class="tab-content active" id="tab-content">
                            <div class="form-grid">
                                <!-- Title -->
                                <div class="form-group full-width">
                                    <label class="form-label">
                                        Title <span class="required">*</span>
                                    </label>
                                    <input type="text" name="title" id="title-input" class="form-control" 
                                           value="<?php echo htmlspecialchars($news['title']); ?>"
                                           placeholder="Enter a descriptive title" required maxlength="200">
                                    <div class="char-count" id="titleCount">0/200</div>
                                    <div class="field-error" id="title-error" style="display: none;"></div>
                                </div>

                                <!-- Slug -->
                                <div class="form-group full-width">
                                    <label class="form-label">URL Slug</label>
                                    <input type="text" name="slug" id="slug-input" class="form-control" 
                                           value="<?php echo htmlspecialchars($news['slug']); ?>"
                                           placeholder="Auto-generated from title">
                                    <small style="color: var(--gray); font-size: 11px;">
                                        URL-friendly version of the title (auto-generates if left blank)
                                    </small>
                                </div>

                                <!-- Excerpt -->
                                <div class="form-group full-width">
                                    <label class="form-label">Excerpt / Summary</label>
                                    <textarea name="excerpt" id="excerpt" class="form-control" 
                                              rows="3" placeholder="Brief summary (optional)" maxlength="300"><?php echo htmlspecialchars($news['excerpt']); ?></textarea>
                                    <div class="char-count" id="excerptCount">0/300</div>
                                </div>

                                <!-- Content -->
                                <div class="form-group full-width">
                                    <label class="form-label">
                                        Content <span class="required">*</span>
                                    </label>
                                    <div id="editor" class="editor-container"></div>
                                    <textarea name="content" id="content" style="display: none;" required><?php echo htmlspecialchars($news['content']); ?></textarea>
                                    <div class="field-error" id="content-error" style="display: none;"></div>
                                </div>
                            </div>
                            
                            <div class="tab-navigation">
                                <div></div>
                                <button type="button" class="btn btn-primary next-btn" data-next="tab-media">
                                    Next: Media <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 2: Media -->
                        <div class="tab-content" id="tab-media">
                            <div class="form-grid">
                                <!-- Featured Image -->
                                <div class="form-group full-width">
                                    <label class="form-label">Featured Image</label>
                                    
                                    <!-- Image Upload Area -->
                                    <div class="image-upload-container" onclick="document.getElementById('image-input').click()">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Click to upload featured image</p>
                                        <p class="small">PNG, JPG, GIF, WEBP up to 5MB</p>
                                        <p class="small">Recommended size: 1200x630px</p>
                                    </div>
                                    
                                    <input type="file" id="image-input" accept="image/*" style="display: none;" 
                                           onchange="previewImage(event)" name="featured_image_upload">
                                    <input type="hidden" name="featured_image" id="featured-image" 
                                           value="<?php echo htmlspecialchars($news['featured_image']); ?>">
                                    
                                    <!-- Image Preview -->
                                    <div class="image-preview" id="image-preview">
                                        <div class="preview-container" id="preview-container">
                                            <!-- Preview will be inserted here -->
                                        </div>
                                    </div>

                                    <!-- Current Image (for edit mode) -->
                                    <?php if (!empty($news['featured_image'])): ?>
                                    <div class="current-image" id="current-image">
                                        <img src="<?php echo $baseUrl . htmlspecialchars($news['featured_image']); ?>" alt="Current">
                                        <div>
                                            <p><strong>Current Image</strong></p>
                                            <p class="small">Upload a new image to replace it</p>
                                            <button type="button" class="btn-remove-image" onclick="markImageForRemoval()">
                                                <i class="fas fa-trash"></i> Remove Image
                                            </button>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Tags -->
                                <div class="form-group full-width">
                                    <label class="form-label">Tags</label>
                                    <input type="text" name="tags" class="form-control" 
                                           value="<?php 
                                           $tagsValue = $news['tags'] ?? '';
                                           if (!empty($tagsValue) && $tagsValue[0] === '[') {
                                               $tagsArray = json_decode($tagsValue, true);
                                               echo htmlspecialchars(is_array($tagsArray) ? implode(', ', $tagsArray) : $tagsValue);
                                           } else {
                                               echo htmlspecialchars($tagsValue);
                                           }
                                           ?>" 
                                           placeholder="e.g., nursing, education, research (comma separated)">
                                    <small style="color: var(--gray); font-size: 11px;">
                                        Separate tags with commas
                                    </small>
                                </div>
                            </div>
                            
                            <div class="tab-navigation">
                                <button type="button" class="btn btn-outline prev-btn" data-prev="tab-content">
                                    <i class="fas fa-arrow-left"></i> Previous: Content
                                </button>
                                <button type="button" class="btn btn-primary next-btn" data-next="tab-seo">
                                    Next: SEO <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 3: SEO -->
                        <div class="tab-content" id="tab-seo">
                            <div class="form-grid">
                                <!-- Meta Title -->
                                <div class="form-group full-width">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control" 
                                           value="<?php echo htmlspecialchars($news['meta_title']); ?>"
                                           placeholder="Title for search engines (optional)" maxlength="70">
                                    <div class="char-count">Recommended: 50-60 characters</div>
                                </div>

                                <!-- Meta Description -->
                                <div class="form-group full-width">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" 
                                              rows="3" placeholder="Description for search engines (optional)" maxlength="160"><?php echo htmlspecialchars($news['meta_description']); ?></textarea>
                                    <div class="char-count">Recommended: 150-160 characters</div>
                                </div>

                                <!-- Meta Keywords -->
                                <div class="form-group full-width">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control" 
                                           value="<?php echo htmlspecialchars($news['meta_keywords']); ?>"
                                           placeholder="Keywords for search engines (optional)">
                                </div>
                            </div>
                            
                            <div class="tab-navigation">
                                <button type="button" class="btn btn-outline prev-btn" data-prev="tab-media">
                                    <i class="fas fa-arrow-left"></i> Previous: Media
                                </button>
                                <button type="button" class="btn btn-primary next-btn" data-next="tab-settings">
                                    Next: Settings <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 4: Settings -->
                        <div class="tab-content" id="tab-settings">
                            <div class="form-grid">
                                <!-- Category -->
                                <div class="form-group">
                                    <label class="form-label">
                                        Category <span class="required">*</span>
                                    </label>
                                    <select name="category" class="form-control" required id="category-select">
                                        <option value="">Select Category</option>
                                        <?php foreach ($displayCategories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category); ?>" 
                                            <?php echo ($news['category'] === $category) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="field-error" id="category-error" style="display: none;"></div>
                                </div>

                                <!-- Article Type -->
                                <div class="form-group">
                                    <label class="form-label">Article Type</label>
                                    <select name="article_type" class="form-control" id="type-select">
                                        <option value="news" <?php echo $type === 'news' ? 'selected' : ''; ?>>News Article</option>
                                        <option value="event" <?php echo $type === 'event' ? 'selected' : ''; ?>>Event</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Event Fields -->
                            <div id="event-fields" class="event-fields" style="<?php echo $type === 'event' ? 'display: block;' : 'display: none;'; ?>">
                                <div class="datetime-group">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Event Date <span class="required">*</span>
                                        </label>
                                        <input type="date" name="event_date" class="form-control" 
                                               value="<?php echo htmlspecialchars($news['event_date']); ?>" 
                                               id="event-date-input">
                                        <div class="field-error" id="event-date-error" style="display: none;"></div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Event End Date</label>
                                        <input type="date" name="event_end_date" class="form-control" 
                                               value="<?php echo htmlspecialchars($news['event_end_date']); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Event Time</label>
                                        <input type="time" name="event_time" class="form-control" 
                                               value="<?php echo htmlspecialchars($news['event_time']); ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Event Location</label>
                                    <input type="text" name="event_location" class="form-control" 
                                           value="<?php echo htmlspecialchars($news['event_location']); ?>" 
                                           placeholder="e.g., Main Auditorium, Online">
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="form-group full-width">
                                <label class="form-label">Options</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="is_published" id="is_published" 
                                               value="1" <?php echo $news['is_published'] ? 'checked' : 'checked'; ?>>
                                        <label for="is_published">Publish immediately</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="is_featured" id="is_featured" 
                                               value="1" <?php echo $news['is_featured'] ? 'checked' : ''; ?>>
                                        <label for="is_featured">Feature this content</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="is_breaking" id="is_breaking" 
                                               value="1" <?php echo $news['is_breaking'] ? 'checked' : ''; ?>>
                                        <label for="is_breaking">Mark as breaking news</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-navigation">
                                <button type="button" class="btn btn-outline prev-btn" data-prev="tab-seo">
                                    <i class="fas fa-arrow-left"></i> Previous: SEO
                                </button>
                                <div style="display: flex; gap: 12px;">
                                    <button type="submit" class="btn btn-secondary" onclick="setDraft()">
                                        <i class="fas fa-save"></i> Save as Draft
                                    </button>
                                    <button type="submit" class="btn btn-primary" onclick="setPublish()">
                                        <i class="fas fa-paper-plane"></i> Publish
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Quill Editor Script -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Initialize Quill editor
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video', 'blockquote', 'code-block'],
                    ['clean']
                ]
            },
            placeholder: 'Write your article content here...'
        });

        // Set initial content
        const contentField = document.getElementById('content');
        const initialContent = contentField.value.trim();
        if (initialContent && initialContent !== '') {
            quill.root.innerHTML = initialContent;
        }

        // Update hidden content field
        quill.on('text-change', function() {
            contentField.value = quill.root.innerHTML;
        });

        // Tab Navigation
        const tabs = ['tab-content', 'tab-media', 'tab-seo', 'tab-settings'];
        const progressSteps = document.querySelectorAll('.progress-step');
        let currentTabIndex = 0;

        // Next button click
        document.querySelectorAll('.next-btn').forEach(button => {
            button.addEventListener('click', function() {
                const nextTabId = this.getAttribute('data-next');
                const currentTab = document.querySelector('.tab-content.active');
                
                if (validateTab(currentTab.id)) {
                    // Hide current tab
                    currentTab.classList.remove('active');
                    
                    // Show next tab
                    document.getElementById(nextTabId).classList.add('active');
                    
                    // Update progress indicator
                    updateProgress(nextTabId);
                }
            });
        });

        // Previous button click
        document.querySelectorAll('.prev-btn').forEach(button => {
            button.addEventListener('click', function() {
                const prevTabId = this.getAttribute('data-prev');
                const currentTab = document.querySelector('.tab-content.active');
                
                // Hide current tab
                currentTab.classList.remove('active');
                
                // Show previous tab
                document.getElementById(prevTabId).classList.add('active');
                
                // Update progress indicator
                updateProgress(prevTabId);
            });
        });

        // Update progress indicator
        function updateProgress(tabId) {
            const tabMap = {
                'tab-content': 0,
                'tab-media': 1,
                'tab-seo': 2,
                'tab-settings': 3
            };
            
            const newIndex = tabMap[tabId];
            
            progressSteps.forEach((step, index) => {
                step.classList.remove('active', 'completed');
                
                if (index === newIndex) {
                    step.classList.add('active');
                } else if (index < newIndex) {
                    step.classList.add('completed');
                }
            });
        }

        // Validate tab
        function validateTab(tabId) {
            const tab = document.getElementById(tabId);
            let isValid = true;
            
            // Clear all previous errors
            tab.querySelectorAll('.field-error').forEach(el => el.style.display = 'none');
            tab.querySelectorAll('.form-control').forEach(el => el.classList.remove('error'));
            
            // Check required fields
            tab.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    const errorDiv = document.getElementById(field.id + '-error');
                    if (errorDiv) {
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = 'This field is required';
                    } else {
                        // Create error div if it doesn't exist
                        const newErrorDiv = document.createElement('div');
                        newErrorDiv.className = 'field-error';
                        newErrorDiv.textContent = 'This field is required';
                        field.parentNode.insertBefore(newErrorDiv, field.nextSibling);
                    }
                }
            });
            
            // Special validation for content tab
            if (tabId === 'tab-content') {
                const content = quill.root.innerHTML.trim();
                if (content === '' || content === '<p><br></p>') {
                    isValid = false;
                    document.getElementById('content-error').style.display = 'block';
                    document.getElementById('content-error').textContent = 'Content is required';
                }
            }
            
            // Special validation for settings tab
            if (tabId === 'tab-settings') {
                const category = document.getElementById('category-select').value;
                if (!category) {
                    isValid = false;
                    document.getElementById('category-error').style.display = 'block';
                }
                
                // Validate event fields if event type
                if (document.getElementById('type-select').value === 'event') {
                    const eventDate = document.getElementById('event-date-input');
                    if (eventDate && !eventDate.value) {
                        isValid = false;
                        document.getElementById('event-date-error').style.display = 'block';
                    }
                }
            }
            
            if (!isValid) {
                alert('Please fill in all required fields before proceeding.');
            }
            
            return isValid;
        }

        // Set publish/draft flags
        function setPublish() {
            document.getElementById('save_draft').value = '0';
        }

        function setDraft() {
            document.getElementById('save_draft').value = '1';
        }

        // Image preview
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const previewContainer = document.getElementById('preview-container');
            const imageUrl = document.getElementById('featured-image');
            
            if (input.files && input.files[0]) {
                // Validate file size
                if (input.files[0].size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    input.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(input.files[0].type)) {
                    alert('Please select a valid image file (JPEG, PNG, GIF, WebP)');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="preview-image">
                        <div class="image-info">
                            <p><span class="file-name">${input.files[0].name}</span></p>
                            <p><span class="file-size">${Math.round(input.files[0].size / 1024)} KB</span></p>
                            <button type="button" class="btn-remove-image" onclick="removeImage()">
                                <i class="fas fa-trash"></i> Remove Image
                            </button>
                        </div>
                    `;
                    
                    preview.classList.add('active');
                    
                    // Store base64 data for form submission
                    document.getElementById('featured-image-data').value = e.target.result;
                    document.getElementById('featured-image-filename').value = input.files[0].name;
                    
                    // Clear existing image reference
                    imageUrl.value = '';
                    
                    // Hide current image if exists
                    const currentImage = document.getElementById('current-image');
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            const preview = document.getElementById('image-preview');
            const previewContainer = document.getElementById('preview-container');
            const fileInput = document.getElementById('image-input');
            const imageDataField = document.getElementById('featured-image-data');
            const imageFilenameField = document.getElementById('featured-image-filename');
            
            preview.classList.remove('active');
            previewContainer.innerHTML = '';
            fileInput.value = '';
            imageDataField.value = '';
            imageFilenameField.value = '';
        }

        function markImageForRemoval() {
            if (confirm('Are you sure you want to remove the current image?')) {
                document.getElementById('remove_image').value = '1';
                document.getElementById('current-image').style.display = 'none';
            }
        }

        // Event type toggle
        document.getElementById('type-select').addEventListener('change', function() {
            const formType = document.getElementById('form-type');
            const eventFields = document.getElementById('event-fields');
            
            formType.value = this.value;
            
            if (this.value === 'event') {
                eventFields.style.display = 'block';
                // Set event date to today if empty
                const eventDateInput = document.getElementById('event-date-input');
                if (!eventDateInput.value) {
                    const today = new Date().toISOString().split('T')[0];
                    eventDateInput.value = today;
                }
            } else {
                eventFields.style.display = 'none';
            }
        });

        // Character counters
        function updateCharCount(inputId, countId, max) {
            const input = document.getElementById(inputId);
            const count = document.getElementById(countId);
            
            if (input && count) {
                const len = input.value.length;
                count.textContent = len + (max ? '/' + max : ' characters');
                
                if (max) {
                    if (len > max) {
                        count.classList.add('danger');
                        count.classList.remove('warning');
                    } else if (len > max * 0.8) {
                        count.classList.add('warning');
                        count.classList.remove('danger');
                    } else {
                        count.classList.remove('warning', 'danger');
                    }
                }
            }
        }

        document.getElementById('title-input')?.addEventListener('input', function() {
            updateCharCount('title-input', 'titleCount', 200);
        });

        document.getElementById('excerpt')?.addEventListener('input', function() {
            updateCharCount('excerpt', 'excerptCount', 300);
        });

        // Auto-generate slug from title
        document.getElementById('title-input')?.addEventListener('blur', function() {
            const slugInput = document.getElementById('slug-input');
            if (slugInput && !slugInput.value) {
                const slug = this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
                slugInput.value = slug;
            }
        });

        // Form validation before submission
        document.getElementById('newsForm').addEventListener('submit', function(e) {
            // Update content field
            contentField.value = quill.root.innerHTML;
            
            // Validate all required fields
            const title = document.getElementById('title-input').value.trim();
            const content = contentField.value.trim();
            const category = document.getElementById('category-select').value;
            
            let isValid = true;
            let errorMessage = '';
            
            if (!title) {
                isValid = false;
                errorMessage += '- Title is required\n';
                document.getElementById('title-input').classList.add('error');
            }
            
            if (!content || content === '<p><br></p>') {
                isValid = false;
                errorMessage += '- Content is required\n';
                document.getElementById('content-error').style.display = 'block';
            }
            
            if (!category) {
                isValid = false;
                errorMessage += '- Category is required\n';
                document.getElementById('category-select').classList.add('error');
            }
            
            // Check event fields if event type
            if (document.getElementById('type-select').value === 'event') {
                const eventDate = document.getElementById('event-date-input').value;
                if (!eventDate) {
                    isValid = false;
                    errorMessage += '- Event date is required\n';
                    document.getElementById('event-date-input').classList.add('error');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fix the following errors:\n\n' + errorMessage);
                
                // Switch to the first tab with error
                if (!title || !content) {
                    switchTab('tab-content');
                } else if (!category || (document.getElementById('type-select').value === 'event' && !document.getElementById('event-date-input').value)) {
                    switchTab('tab-settings');
                }
                
                return false;
            }
            
            return true;
        });

        // Helper function to switch tabs
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
            updateProgress(tabId);
        }

        // Initialize character counts
        window.addEventListener('load', function() {
            updateCharCount('title-input', 'titleCount', 200);
            updateCharCount('excerpt', 'excerptCount', 300);
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !menuToggle.contains(event.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.remove('active');
            }
        });

        // Auto-hide flash messages
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(msg => {
                msg.style.opacity = '0';
                msg.style.transition = 'opacity 0.5s ease';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save as draft
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                setDraft();
                document.getElementById('newsForm').submit();
            }
            
            // Ctrl/Cmd + P to publish
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                setPublish();
                document.getElementById('newsForm').submit();
            }
        });
    </script>
</body>
</html>