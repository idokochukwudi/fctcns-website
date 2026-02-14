<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure variables exist with safe defaults
$baseUrl = $baseUrl ?? '';
$content = $content ?? [];
$stats = $stats ?? [];
$categories = $categories ?? [];
$draftArticles = $draftArticles ?? [];
$popularArticles = $popularArticles ?? [];
$upcomingEvents = $upcomingEvents ?? [];
$recentActivity = $recentActivity ?? [];
$filters = $filters ?? [];
$pagination = $pagination ?? [];

// Get user info from session
$userName = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'News Manager';
$userRole = $_SESSION['user_role'] ?? 'news_manager';
$userAvatar = $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=4f46e5&color=fff';

// Stats protection with defaults
$defaultStats = [
    'total' => 0,
    'published' => 0,
    'draft' => 0,
    'featured' => 0,
    'news' => 0,
    'events' => 0,
    'breaking' => 0,
    'views' => 0,
    'this_month' => 0,
    'this_week' => 0,
    'categories' => 0,
    'upcoming_events' => 0
];

// Merge with defaults
$stats = array_merge($defaultStats, $stats);

// Verify each key exists
foreach ($defaultStats as $key => $defaultValue) {
    if (!array_key_exists($key, $stats)) {
        $stats[$key] = $defaultValue;
    }
}

// CSRF token
$csrfToken = $csrf_token ?? $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = $csrfToken;
}

// Get flash messages
$flashSuccess = $_SESSION['flash_success'] ?? $flash_success ?? '';
$flashError = $_SESSION['flash_error'] ?? $flash_error ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// Permission flags
$canCreate = $canCreate ?? true;
$canEdit = $canEdit ?? true;
$canDelete = $canDelete ?? true;
$canPublish = $canPublish ?? true;
$canManageCategories = $canManageCategories ?? true;

// Current date for greeting
$currentHour = date('H');
$greeting = '';
if ($currentHour < 12) {
    $greeting = 'Good Morning';
} elseif ($currentHour < 17) {
    $greeting = 'Good Afternoon';
} else {
    $greeting = 'Good Evening';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>FCT College of Nursing Sciences - News Management Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --sidebar-collapsed-width: 0px;
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

        .nav-item span {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-item .badge {
            margin-left: auto;
            font-size: 10px;
            padding: 2px 6px;
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

        .notification-badge {
            position: relative;
            cursor: pointer;
            flex-shrink: 0;
        }

        .notification-badge i {
            font-size: 18px;
            color: var(--gray);
        }

        .badge-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 9px;
            padding: 2px 5px;
            border-radius: 10px;
            min-width: 16px;
            text-align: center;
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

        .logout-btn i {
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .logout-btn span {
                display: none;
            }
            
            .logout-btn {
                padding: 6px 10px;
            }
        }

        /* Container */
        .container-fluid {
            padding: 20px 15px;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-lg);
        }

        .welcome-banner h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 8px;
            word-break: break-word;
        }

        .welcome-banner p {
            font-size: 14px;
            opacity: 0.9;
            word-break: break-word;
        }

        /* Stats Cards - Responsive Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }

        @media (max-width: 380px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--secondary), var(--primary));
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-icon.primary {
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary);
        }

        .stat-icon.success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .stat-icon.warning {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }

        .stat-icon.info {
            background: rgba(44, 62, 80, 0.1);
            color: var(--primary);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            line-height: 1.2;
        }

        .stat-label {
            color: var(--gray);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            word-break: break-word;
        }

        /* Dashboard Grid - Adjusted for better sidebar space */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.8fr 1.2fr; /* Changed from 2fr 1fr to give sidebar more space */
            gap: 20px;
            margin-bottom: 25px;
            width: 100%;
        }

        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        /* Sidebar Content - Ensure proper width */
        .dashboard-grid > div:last-child {
            min-width: 0;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Cards in sidebar */
        .dashboard-grid > div:last-child .card {
            width: 100%;
            max-width: 100%;
            margin-left: 0;
            margin-right: 0;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 20px;
            width: 100%;
            max-width: 100%;
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            word-break: break-word;
        }

        .card-title i {
            color: var(--secondary);
            flex-shrink: 0;
        }

        .card-body {
            padding: 20px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .card-body p, 
        .card-body h4,
        .card-body a {
            max-width: 100%;
            overflow-wrap: break-word;
            word-wrap: break-word;
            hyphens: auto;
        }

        .card-footer {
            padding: 12px 20px;
            border-top: 1px solid var(--gray-light);
            background: #f8f9fa;
        }
        
        .card-footer a {
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Filters */
        .filters-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            width: 100%;
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            width: 100%;
        }

        .form-group {
            margin-bottom: 0;
            min-width: 0;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 4px;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 8px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .filter-actions {
                grid-column: 1 / -1;
            }
        }

        /* Buttons */
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
            white-space: nowrap;
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

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: var(--danger-dark);
            transform: translateY(-2px);
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

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-block {
            width: 100%;
            justify-content: center;
        }

        @media (max-width: 480px) {
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Table Container - Improved Responsive Design */
        .table-container {
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            margin-bottom: 0;
            border-radius: 8px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
            table-layout: fixed;
        }

        .table th {
            text-align: left;
            padding: 12px 8px;
            font-weight: 600;
            font-size: 12px;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 2px solid var(--gray-light);
            white-space: nowrap;
        }

        /* Fixed column widths to ensure actions column fits */
        .table th:nth-child(1) { width: 40px; }  /* Checkbox */
        .table th:nth-child(2) { width: 20%; }  /* Title - REDUCED from default to 20% */
        .table th:nth-child(3) { width: 8%; }   /* Type */
        .table th:nth-child(4) { width: 12%; }  /* Category */
        .table th:nth-child(5) { width: 8%; }   /* Status */
        .table th:nth-child(6) { width: 8%; }   /* Featured */
        .table th:nth-child(7) { width: 8%; }   /* Views */
        .table th:nth-child(8) { width: 12%; }  /* Created */
        .table th:nth-child(9) { width: 16%; }  /* Actions */

        .table td {
            padding: 12px 8px;
            border-bottom: 1px solid var(--gray-light);
            font-size: 13px;
            vertical-align: middle;
            word-break: break-word;
        }

        /* Ensure title column content doesn't overflow */
        .table td:nth-child(2) {
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .table td:nth-child(2) a {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .table td:nth-child(2) div:last-child {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table tbody tr:hover {
            background: rgba(52, 152, 219, 0.02);
        }

        /* Responsive Table - Stack on Mobile */
        @media (max-width: 768px) {
            .table-container {
                overflow-x: hidden;
            }
            
            .table {
                min-width: 100%;
                border: 0;
                table-layout: auto;
            }
            
            .table thead {
                display: none;
            }
            
            .table tbody tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid var(--border);
                border-radius: 8px;
                background: white;
                box-shadow: var(--shadow);
            }
            
            .table td {
                display: flex;
                align-items: center;
                padding: 10px 12px;
                border-bottom: 1px solid var(--gray-light);
                text-align: left;
                gap: 8px;
            }
            
            .table td:last-child {
                border-bottom: none;
            }
            
            .table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--gray);
                min-width: 80px;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }
            
            /* Special handling for checkbox */
            .table td:first-child::before {
                content: "Select";
            }
            
            /* Special handling for actions */
            .table td:last-child::before {
                content: "Actions";
            }

            /* Reset all column widths on mobile */
            .table th:nth-child(1),
            .table th:nth-child(2),
            .table th:nth-child(3),
            .table th:nth-child(4),
            .table th:nth-child(5),
            .table th:nth-child(6),
            .table th:nth-child(7),
            .table th:nth-child(8),
            .table th:nth-child(9) {
                width: auto;
            }
            
            .table td:nth-child(2) {
                max-width: 100%;
                white-space: normal;
            }
            
            .table td:nth-child(2) a {
                white-space: normal;
            }
            
            .table td:nth-child(2) div:last-child {
                white-space: normal;
            }
        }

        /* Badges */
        .badge {
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-block;
            white-space: nowrap;
        }

        .badge-success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .badge-warning {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }

        .badge-info {
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary);
        }

        .badge-danger {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger);
        }

        .badge-primary {
            background: rgba(44, 62, 80, 0.1);
            color: var(--primary);
        }

        /* Action Buttons - Improved Responsive Design */
        .action-buttons {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .icon-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid var(--border);
            color: var(--gray);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            flex-shrink: 0;
        }

        .icon-btn:hover {
            background: var(--secondary);
            color: white;
            border-color: var(--secondary);
        }

        .icon-btn.delete:hover {
            background: var(--danger);
            border-color: var(--danger);
        }

        /* Mobile action buttons - stacked */
        @media (max-width: 480px) {
            .action-buttons {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 4px;
                width: 100%;
            }
            
            .icon-btn {
                width: 100%;
                height: 36px;
            }
        }

        @media (max-width: 360px) {
            .action-buttons {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Bulk Actions */
        .bulk-actions {
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            width: 100%;
        }

        .bulk-select {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .bulk-select input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .bulk-select label {
            font-size: 13px;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .bulk-actions {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .bulk-select {
                width: 100%;
            }
            
            .bulk-actions select,
            .bulk-actions .btn {
                width: 100%;
            }
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin-top: 25px;
            flex-wrap: wrap;
            width: 100%;
        }

        .pagination-btn {
            padding: 6px 12px;
            border: 1px solid var(--border);
            background: white;
            color: var(--dark);
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            font-size: 13px;
        }

        .pagination-btn:hover:not(.disabled) {
            background: var(--secondary);
            color: white;
            border-color: var(--secondary);
        }

        .pagination-btn.active {
            background: var(--secondary);
            color: white;
            border-color: var(--secondary);
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Activity List - Fixed overflow */
        .activity-list {
            list-style: none;
            width: 100%;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--gray-light);
            width: 100%;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .activity-icon.create {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .activity-icon.update {
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary);
        }

        .activity-content {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .activity-text {
            font-size: 13px;
            color: var(--dark);
            margin-bottom: 3px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .activity-text a {
            color: var(--secondary);
            text-decoration: none;
            word-break: break-word;
            overflow-wrap: break-word;
            display: inline-block;
            max-width: 100%;
        }

        .activity-text a:hover {
            text-decoration: underline;
        }

        .activity-time {
            font-size: 11px;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        /* Sidebar Lists - Fixed overflow */
        .sidebar-list {
            list-style: none;
            width: 100%;
        }

        .sidebar-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-light);
            gap: 8px;
            width: 100%;
        }

        .sidebar-item:last-child {
            border-bottom: none;
        }

        .sidebar-item-title {
            font-size: 13px;
            color: var(--dark);
            text-decoration: none;
            transition: color 0.3s ease;
            word-break: break-word;
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding-right: 5px;
        }

        .sidebar-item-title:hover {
            color: var(--secondary);
        }

        .sidebar-item-count {
            background: var(--gray-light);
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            color: var(--gray);
            white-space: nowrap;
            flex-shrink: 0;
        }
        
        .sidebar-item .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            display: block;
        }

        /* Upcoming Events items */
        .sidebar-item[style*="flex-direction: column"] {
            width: 100%;
            overflow: hidden;
        }

        .sidebar-item[style*="flex-direction: column"] .sidebar-item-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            display: block;
        }

        .sidebar-item[style*="flex-direction: column"] div[style*="display: flex"] {
            width: 100%;
            flex-wrap: wrap;
            gap: 8px;
        }

        .sidebar-item[style*="flex-direction: column"] div[style*="display: flex"] span {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Quick Stats - Fixed layout */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 15px;
            width: 100%;
        }

        .quick-stat {
            text-align: center;
            padding: 12px 8px;
            background: var(--gray-light);
            border-radius: 8px;
            overflow: hidden;
            min-width: 0;
        }

        .quick-stat-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--secondary);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .quick-stat-label {
            font-size: 11px;
            color: var(--gray);
            word-break: break-word;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Quick Actions buttons - Fixed wrapping */
        .quick-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            width: 100%;
        }

        .quick-actions .btn {
            flex: 0 1 auto;
            min-width: 0;
            max-width: 100%;
        }

        /* Documentation button fix */
        .card[style*="background: linear-gradient"] .btn-outline {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding-left: 12px;
            padding-right: 12px;
        }

        .card[style*="background: linear-gradient"] .btn-outline i {
            flex-shrink: 0;
            margin-right: 4px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 15px;
        }

        .empty-state-icon {
            font-size: 48px;
            color: var(--gray);
            margin-bottom: 15px;
        }

        .empty-state-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
            word-break: break-word;
        }

        .empty-state-description {
            color: var(--gray);
            margin-bottom: 20px;
            font-size: 14px;
            word-break: break-word;
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

        /* Utility Classes */
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .w-100 {
            width: 100%;
        }

        .d-none {
            display: none;
        }

        .d-block {
            display: block;
        }

        @media (max-width: 480px) {
            .d-sm-none {
                display: none;
            }
        }

        /* Mobile Optimizations */
        @media (max-width: 480px) {
            .container-fluid {
                padding: 15px 10px;
            }
            
            .card-header {
                padding: 12px 15px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            .welcome-banner {
                padding: 15px;
            }
            
            .welcome-banner h1 {
                font-size: 18px;
            }
            
            .welcome-banner p {
                font-size: 12px;
            }
            
            .quick-actions {
                flex-direction: column;
            }
            
            .quick-actions .btn {
                width: 100%;
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
                <a href="<?php echo $baseUrl; ?>/admin/news-manager" class="nav-item active">
                    <i class="fas fa-newspaper"></i>
                    <span>Dashboard</span>
                </a>
                
                <?php if ($canCreate): ?>
                <a href="<?php echo $baseUrl; ?>/admin/news-manager/create?type=news" class="nav-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Create News</span>
                </a>
                <a href="<?php echo $baseUrl; ?>/admin/news-manager/create?type=event" class="nav-item">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Create Event</span>
                </a>
                <?php endif; ?>

                <?php if ($canManageCategories): ?>
                <a href="<?php echo $baseUrl; ?>/admin/news-manager/categories" class="nav-item">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
                <?php endif; ?>

                <div class="nav-divider"></div>

                <a href="<?php echo $baseUrl; ?>/admin/news-manager?status=draft" class="nav-item">
                    <i class="fas fa-pen"></i>
                    <span>Drafts</span>
                    <?php if (!empty($draftArticles)): ?>
                    <span class="badge badge-warning"><?php echo count($draftArticles); ?></span>
                    <?php endif; ?>
                </a>

                <a href="<?php echo $baseUrl; ?>/admin/news-manager?type=event" class="nav-item">
                    <i class="fas fa-calendar"></i>
                    <span>Events</span>
                    <?php if (!empty($upcomingEvents)): ?>
                    <span class="badge badge-success"><?php echo count($upcomingEvents); ?></span>
                    <?php endif; ?>
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
                    <h1 class="page-title">News Management Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="notification-badge">
                        <i class="far fa-bell"></i>
                        <span class="badge-count">3</span>
                    </div>
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

                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <h1><?php echo $greeting; ?>, <?php echo htmlspecialchars($userName); ?>!</h1>
                    <p>Welcome to the FCT College of Nursing Sciences News Management System. Here you can create, manage, and publish news articles and events.</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon primary">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <div class="stat-value"><?php echo number_format($stats['total']); ?></div>
                        </div>
                        <div class="stat-label">Total Content</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-value"><?php echo number_format($stats['published']); ?></div>
                        </div>
                        <div class="stat-label">Published</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon warning">
                                <i class="fas fa-pen"></i>
                            </div>
                            <div class="stat-value"><?php echo number_format($stats['draft']); ?></div>
                        </div>
                        <div class="stat-label">Drafts</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon info">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-value"><?php echo number_format($stats['featured']); ?></div>
                        </div>
                        <div class="stat-label">Featured</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon success">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-value"><?php echo number_format($stats['this_month']); ?></div>
                        </div>
                        <div class="stat-label">This Month</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon primary">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="stat-value"><?php echo number_format($stats['views']); ?></div>
                        </div>
                        <div class="stat-label">Total Views</div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filters-card">
                    <form method="GET" action="<?php echo $baseUrl; ?>/admin/news-manager" class="filter-form">
                        <div class="form-group">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by title..." 
                                   value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="published" <?php echo ($filters['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo ($filters['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control">
                                <option value="">All Types</option>
                                <option value="news" <?php echo ($filters['type'] ?? '') === 'news' ? 'selected' : ''; ?>>News</option>
                                <option value="event" <?php echo ($filters['type'] ?? '') === 'event' ? 'selected' : ''; ?>>Event</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                        <?php echo ($filters['category'] ?? '') === $cat['category'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['category']); ?> (<?php echo $cat['count']; ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-filter"></i> Apply
                            </button>
                            <a href="<?php echo $baseUrl; ?>/admin/news-manager" class="btn btn-outline btn-sm">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Dashboard Grid -->
                <div class="dashboard-grid">
                    <!-- Main Content Table -->
                    <div>
                        <!-- Quick Actions -->
                        <?php if ($canCreate): ?>
                        <div class="quick-actions">
                            <a href="<?php echo $baseUrl; ?>/admin/news-manager/create?type=news" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create News
                            </a>
                            <a href="<?php echo $baseUrl; ?>/admin/news-manager/create?type=event" class="btn btn-success">
                                <i class="fas fa-plus"></i> Create Event
                            </a>
                        </div>
                        <?php endif; ?>

                        <!-- Bulk Actions Form -->
                        <form id="bulkForm" method="POST" action="<?php echo $baseUrl; ?>/admin/news-manager/bulk-action">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            
                            <div class="bulk-actions">
                                <div class="bulk-select">
                                    <input type="checkbox" id="selectAll">
                                    <label for="selectAll">Select All</label>
                                </div>
                                
                                <select name="action" class="form-control" style="max-width: 200px;" required>
                                    <option value="">Bulk Actions</option>
                                    <?php if ($canPublish): ?>
                                    <option value="publish">Publish</option>
                                    <option value="unpublish">Unpublish</option>
                                    <?php endif; ?>
                                    <?php if ($canEdit): ?>
                                    <option value="feature">Feature</option>
                                    <option value="unfeature">Unfeature</option>
                                    <?php endif; ?>
                                    <?php if ($canDelete): ?>
                                    <option value="delete">Delete</option>
                                    <?php endif; ?>
                                </select>
                                
                                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirmBulkAction()">
                                    <i class="fas fa-play"></i> Apply
                                </button>
                            </div>
                            
                            <!-- Content Table -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-list"></i>
                                        Content Management
                                    </h3>
                                    <span class="badge badge-primary">Total: <?php echo $pagination['totalCount'] ?? 0; ?></span>
                                </div>
                                <div class="table-container">
                                    <?php if (empty($content)): ?>
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                        <h3 class="empty-state-title">No Content Found</h3>
                                        <p class="empty-state-description">
                                            <?php if (!empty($filters)): ?>
                                            Try adjusting your filters or clear them to see all content.
                                            <?php else: ?>
                                            Get started by creating your first news article or event.
                                            <?php endif; ?>
                                        </p>
                                        <?php if ($canCreate && empty($filters)): ?>
                                        <a href="<?php echo $baseUrl; ?>/admin/news-manager/create?type=news" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create News
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                    <?php else: ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="selectAllHeader">
                                                </th>
                                                <th>Title</th>
                                                <th>Type</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Featured</th>
                                                <th>Views</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($content as $item): ?>
                                            <tr>
                                                <td data-label="Select">
                                                    <input type="checkbox" name="ids[]" value="<?php echo $item['id']; ?>" class="item-checkbox">
                                                </td>
                                                <td data-label="Title">
                                                    <div style="font-weight: 500;">
                                                        <a href="<?php echo $baseUrl; ?>/admin/news-manager/<?php echo $item['id']; ?>/edit" 
                                                           style="color: var(--primary); text-decoration: none; word-break: break-word;">
                                                            <?php echo htmlspecialchars(strlen($item['title'] ?? '') > 30 ? substr($item['title'], 0, 30) . '...' : ($item['title'] ?? 'Untitled')); ?>
                                                        </a>
                                                    </div>
                                                    <?php if (!empty($item['slug'])): ?>
                                                    <div style="font-size: 10px; color: var(--gray);" class="text-truncate">
                                                        <i class="fas fa-link"></i> <?php echo htmlspecialchars(strlen($item['slug']) > 20 ? substr($item['slug'], 0, 20) . '...' : $item['slug']); ?>
                                                    </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td data-label="Type">
                                                    <span class="badge badge-info">
                                                        <?php echo ucfirst($item['type'] ?? 'news'); ?>
                                                    </span>
                                                </td>
                                                <td data-label="Category">
                                                    <?php if (!empty($item['category'])): ?>
                                                    <span class="badge badge-primary">
                                                        <?php echo htmlspecialchars(strlen($item['category']) > 15 ? substr($item['category'], 0, 15) . '...' : $item['category']); ?>
                                                    </span>
                                                    <?php else: ?>
                                                    <span style="color: var(--gray);">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td data-label="Status">
                                                    <?php if ($item['is_published'] ?? 0): ?>
                                                    <span class="badge badge-success">Published</span>
                                                    <?php else: ?>
                                                    <span class="badge badge-warning">Draft</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td data-label="Featured">
                                                    <?php if ($item['is_featured'] ?? 0): ?>
                                                    <span class="badge badge-info">Featured</span>
                                                    <?php else: ?>
                                                    <span style="color: var(--gray);">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td data-label="Views">
                                                    <span style="display: flex; align-items: center; gap: 4px;">
                                                        <i class="fas fa-eye" style="color: var(--gray);"></i>
                                                        <?php echo number_format($item['views_count'] ?? 0); ?>
                                                    </span>
                                                </td>
                                                <td data-label="Created">
                                                    <span style="display: flex; align-items: center; gap: 4px; white-space: nowrap;">
                                                        <i class="far fa-calendar" style="color: var(--gray);"></i>
                                                        <?php echo !empty($item['created_at']) ? date('M d, Y', strtotime($item['created_at'])) : '—'; ?>
                                                    </span>
                                                </td>
                                                <td data-label="Actions">
                                                    <div class="action-buttons">
                                                        <?php if ($canEdit): ?>
                                                        <a href="<?php echo $baseUrl; ?>/admin/news-manager/<?php echo $item['id']; ?>/edit" 
                                                           class="icon-btn" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($canPublish): ?>
                                                        <button type="button" class="icon-btn" title="Toggle Publish" 
                                                                onclick="togglePublish(<?php echo $item['id']; ?>, <?php echo $item['is_published'] ? 1 : 0; ?>)">
                                                            <i class="fas <?php echo $item['is_published'] ? 'fa-eye' : 'fa-eye-slash'; ?>"></i>
                                                        </button>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($canEdit): ?>
                                                        <button type="button" class="icon-btn" title="Toggle Featured" 
                                                                onclick="toggleFeatured(<?php echo $item['id']; ?>, <?php echo $item['is_featured'] ? 1 : 0; ?>)">
                                                            <i class="fas <?php echo $item['is_featured'] ? 'fa-star' : 'fa-star-o'; ?>"></i>
                                                        </button>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($item['slug']) && $item['is_published']): ?>
                                                        <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>" 
                                                           target="_blank" class="icon-btn" title="View on Website">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($canDelete): ?>
                                                        <form method="POST" action="<?php echo $baseUrl; ?>/admin/news-manager/delete/<?php echo $item['id']; ?>" 
                                                              style="display: inline;" 
                                                              onsubmit="return confirm('Are you sure you want to delete this item? This action cannot be undone.');">
                                                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                                            <button type="submit" class="icon-btn delete" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Pagination -->
                        <?php if (($pagination['total'] ?? 0) > 1): ?>
                        <div class="pagination">
                            <?php 
                            $currentPage = $pagination['current'] ?? 1;
                            $totalPages = $pagination['total'] ?? 1;
                            
                            // Build query string for filters
                            $queryParams = [];
                            foreach ($filters as $key => $value) {
                                if (!empty($value)) {
                                    $queryParams[$key] = $value;
                                }
                            }
                            $queryString = !empty($queryParams) ? '&' . http_build_query($queryParams) : '';
                            ?>
                            
                            <?php if ($currentPage > 1): ?>
                            <a href="?page=<?php echo $currentPage - 1 . $queryString; ?>" class="pagination-btn">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <?php else: ?>
                            <span class="pagination-btn disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                            <?php endif; ?>
                            
                            <?php
                            $start = max(1, $currentPage - 2);
                            $end = min($totalPages, $start + 4);
                            $start = max(1, $end - 4);
                            
                            for ($i = $start; $i <= $end; $i++):
                            ?>
                            <a href="?page=<?php echo $i . $queryString; ?>" 
                               class="pagination-btn <?php echo $i == $currentPage ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?php echo $currentPage + 1 . $queryString; ?>" class="pagination-btn">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            <?php else: ?>
                            <span class="pagination-btn disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Sidebar Content -->
                    <div>
                        <!-- Categories -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-tags"></i>
                                    Categories
                                </h3>
                                <?php if ($canManageCategories): ?>
                                <a href="<?php echo $baseUrl; ?>/admin/news-manager/categories" class="btn btn-sm btn-outline">
                                    Manage
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <?php if (empty($categories)): ?>
                                <p style="color: var(--gray); text-align: center;">No categories found</p>
                                <?php else: ?>
                                <ul class="sidebar-list">
                                    <?php foreach (array_slice($categories, 0, 8) as $cat): ?>
                                    <li class="sidebar-item">
                                        <a href="<?php echo $baseUrl; ?>/admin/news-manager?category=<?php echo urlencode($cat['category']); ?>" 
                                           class="sidebar-item-title">
                                            <i class="fas fa-folder" style="color: var(--secondary); margin-right: 6px;"></i>
                                            <?php echo htmlspecialchars($cat['category']); ?>
                                        </a>
                                        <span class="sidebar-item-count"><?php echo $cat['count']; ?></span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if (count($categories) > 8): ?>
                                <div style="text-align: center; margin-top: 12px;">
                                    <a href="<?php echo $baseUrl; ?>/admin/news-manager/categories" style="color: var(--secondary); font-size: 12px;">
                                        View all <?php echo count($categories); ?> categories <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Draft Articles -->
                        <?php if (!empty($draftArticles)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-pen"></i>
                                    Draft Articles
                                </h3>
                                <span class="badge badge-warning"><?php echo count($draftArticles); ?></span>
                            </div>
                            <div class="card-body">
                                <ul class="sidebar-list">
                                    <?php foreach ($draftArticles as $draft): ?>
                                    <li class="sidebar-item">
                                        <a href="<?php echo $baseUrl; ?>/admin/news-manager/<?php echo $draft['id']; ?>/edit" 
                                           class="sidebar-item-title text-truncate">
                                            <?php echo htmlspecialchars(strlen($draft['title'] ?? '') > 30 ? substr($draft['title'], 0, 30) . '...' : ($draft['title'] ?? 'Untitled')); ?>
                                        </a>
                                        <span class="sidebar-item-count">
                                            <i class="far fa-clock"></i>
                                            <?php echo !empty($draft['created_at']) ? date('M d', strtotime($draft['created_at'])) : ''; ?>
                                        </span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a href="<?php echo $baseUrl; ?>/admin/news-manager?status=draft">View all drafts <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Popular Articles -->
                        <?php if (!empty($popularArticles)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-fire"></i>
                                    Popular Articles
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul class="sidebar-list">
                                    <?php foreach ($popularArticles as $popular): ?>
                                    <li class="sidebar-item">
                                        <a href="<?php echo $baseUrl; ?>/admin/news-manager/<?php echo $popular['id']; ?>/edit" 
                                           class="sidebar-item-title text-truncate">
                                            <?php echo htmlspecialchars(strlen($popular['title'] ?? '') > 30 ? substr($popular['title'], 0, 30) . '...' : ($popular['title'] ?? 'Untitled')); ?>
                                        </a>
                                        <span class="sidebar-item-count">
                                            <i class="fas fa-eye"></i> <?php echo number_format($popular['views_count'] ?? 0); ?>
                                        </span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Upcoming Events -->
                        <?php if (!empty($upcomingEvents)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-alt"></i>
                                    Upcoming Events
                                </h3>
                                <span class="badge badge-success"><?php echo count($upcomingEvents); ?></span>
                            </div>
                            <div class="card-body">
                                <ul class="sidebar-list">
                                    <?php foreach ($upcomingEvents as $event): ?>
                                    <li class="sidebar-item" style="flex-direction: column; align-items: flex-start; gap: 5px;">
                                        <a href="<?php echo $baseUrl; ?>/admin/news-manager/<?php echo $event['id']; ?>/edit" 
                                           class="sidebar-item-title" style="font-weight: 500; width: 100%;">
                                            <?php echo htmlspecialchars(strlen($event['title'] ?? '') > 35 ? substr($event['title'], 0, 35) . '...' : ($event['title'] ?? 'Untitled')); ?>
                                        </a>
                                        <div style="display: flex; gap: 12px; font-size: 11px; color: var(--gray); flex-wrap: wrap;">
                                            <?php if (!empty($event['event_date'])): ?>
                                            <span><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($event['event_date'])); ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($event['event_location'])): ?>
                                            <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars(strlen($event['event_location']) > 20 ? substr($event['event_location'], 0, 20) . '...' : $event['event_location']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a href="<?php echo $baseUrl; ?>/admin/news-manager?type=event">View all events <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Recent Activity -->
                        <?php if (!empty($recentActivity)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-history"></i>
                                    Recent Activity
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul class="activity-list">
                                    <?php foreach (array_slice($recentActivity, 0, 5) as $activity): ?>
                                    <li class="activity-item">
                                        <div class="activity-icon <?php echo $activity['type']; ?>">
                                            <i class="fas fa-<?php echo $activity['icon']; ?>"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-text">
                                                <?php if (isset($activity['id'])): ?>
                                                <a href="<?php echo $baseUrl; ?>/admin/news-manager/<?php echo $activity['id']; ?>/edit">
                                                    <?php echo htmlspecialchars(strlen($activity['text']) > 50 ? substr($activity['text'], 0, 50) . '...' : $activity['text']); ?>
                                                </a>
                                                <?php else: ?>
                                                <?php echo htmlspecialchars(strlen($activity['text']) > 50 ? substr($activity['text'], 0, 50) . '...' : $activity['text']); ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="activity-time">
                                                <i class="far fa-clock"></i> <?php echo $activity['time']; ?>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Quick Stats -->
                        <div class="quick-stats">
                            <div class="quick-stat">
                                <div class="quick-stat-value"><?php echo number_format($stats['this_week']); ?></div>
                                <div class="quick-stat-label">This Week</div>
                            </div>
                            <div class="quick-stat">
                                <div class="quick-stat-value"><?php echo number_format($stats['upcoming_events'] ?? 0); ?></div>
                                <div class="quick-stat-label">Upcoming Events</div>
                            </div>
                            <div class="quick-stat">
                                <div class="quick-stat-value"><?php echo number_format($stats['categories']); ?></div>
                                <div class="quick-stat-label">Categories</div>
                            </div>
                            <div class="quick-stat">
                                <div class="quick-stat-value"><?php echo number_format($stats['breaking']); ?></div>
                                <div class="quick-stat-label">Breaking News</div>
                            </div>
                        </div>
                        
                        <!-- Help Card -->
                        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <div class="card-body" style="text-align: center;">
                                <i class="fas fa-question-circle" style="font-size: 36px; margin-bottom: 12px;"></i>
                                <h4 style="margin-bottom: 8px; font-size: 16px;">Need Help?</h4>
                                <p style="font-size: 13px; opacity: 0.9; margin-bottom: 15px;">
                                    Check out our documentation or contact support.
                                </p>
                                <a href="#" class="btn btn-sm btn-outline" style="background: white; color: #764ba2; border: none;">
                                    <i class="fas fa-book"></i> Documentation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Bulk selection
        const selectAllHeader = document.getElementById('selectAllHeader');
        const selectAll = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        
        function updateSelectAll() {
            const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
            
            if (selectAllHeader) selectAllHeader.checked = allChecked;
            if (selectAll) selectAll.checked = allChecked;
            
            // Update select all text
            if (selectAll) {
                const label = selectAll.nextElementSibling;
                if (someChecked && !allChecked) {
                    const count = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
                    label.textContent = `Selected (${count})`;
                } else {
                    label.textContent = 'Select All';
                }
            }
        }
        
        if (selectAllHeader) {
            selectAllHeader.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                updateSelectAll();
            });
        }
        
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                updateSelectAll();
            });
        }
        
        if (itemCheckboxes.length > 0) {
            itemCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSelectAll);
            });
        }
        
        // Confirm bulk action
        function confirmBulkAction() {
            const selectedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
            const action = document.querySelector('select[name="action"]')?.value;
            
            if (selectedCount === 0) {
                alert('Please select at least one item.');
                return false;
            }
            
            if (!action) {
                alert('Please select an action.');
                return false;
            }
            
            if (action === 'delete') {
                return confirm(`Are you sure you want to delete ${selectedCount} item(s)? This action cannot be undone.`);
            }
            
            return confirm(`Are you sure you want to ${action} ${selectedCount} item(s)?`);
        }
        
        // Toggle publish
        function togglePublish(id, currentStatus) {
            if (!confirm(`Are you sure you want to ${currentStatus ? 'unpublish' : 'publish'} this item?`)) {
                return;
            }
            
            fetch('<?php echo $baseUrl; ?>/admin/news-manager/' + id + '/toggle-publish', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'csrf_token=<?php echo $csrfToken; ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update status');
            });
        }
        
        // Toggle featured
        function toggleFeatured(id, currentStatus) {
            if (!confirm(`Are you sure you want to ${currentStatus ? 'remove from' : 'add to'} featured?`)) {
                return;
            }
            
            fetch('<?php echo $baseUrl; ?>/admin/news-manager/' + id + '/toggle-featured', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'csrf_token=<?php echo $csrfToken; ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update featured status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update featured status');
            });
        }
        
        // Auto-hide flash messages
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(msg => {
                msg.style.opacity = '0';
                msg.style.transition = 'opacity 0.5s ease';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);
        
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
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectAll();
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + N for new news
                if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                    e.preventDefault();
                    window.location.href = '<?php echo $baseUrl; ?>/admin/news-manager/create?type=news';
                }
                
                // Ctrl/Cmd + E for new event
                if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                    e.preventDefault();
                    window.location.href = '<?php echo $baseUrl; ?>/admin/news-manager/create?type=event';
                }
                
                // Escape to clear filters
                if (e.key === 'Escape') {
                    const clearBtn = document.querySelector('a[href*="/admin/news-manager"]:not([href*="?"])');
                    if (clearBtn && window.location.search.includes('?')) {
                        window.location.href = '<?php echo $baseUrl; ?>/admin/news-manager';
                    }
                }
            });
        });
    </script>
</body>
</html>