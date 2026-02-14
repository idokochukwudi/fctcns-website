<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure variables exist with safe defaults
$baseUrl = $baseUrl ?? '';
$news = $news ?? [];
$categories = $categories ?? [];

// If no news data, redirect to dashboard
if (empty($news)) {
    header('Location: ' . $baseUrl . '/admin/news-manager');
    exit;
}

// Get user info
$userName = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'News Manager';
$userRole = $_SESSION['user_role'] ?? 'news_manager';

// Determine if this is an event or news
$isEvent = ($news['type'] ?? 'news') === 'event';
$pageTitle = $isEvent ? 'View Event' : 'View News Article';
$icon = $isEvent ? 'fa-calendar-alt' : 'fa-newspaper';

// Format dates
$createdDate = !empty($news['created_at']) ? date('F j, Y \a\t g:i A', strtotime($news['created_at'])) : 'N/A';
$updatedDate = !empty($news['updated_at']) ? date('F j, Y \a\t g:i A', strtotime($news['updated_at'])) : 'N/A';
$publishedDate = !empty($news['published_at']) ? date('F j, Y \a\t g:i A', strtotime($news['published_at'])) : null;

// Format event dates
if ($isEvent) {
    $eventDate = !empty($news['event_date']) ? date('F j, Y', strtotime($news['event_date'])) : null;
    $eventEndDate = !empty($news['event_end_date']) ? date('F j, Y', strtotime($news['event_end_date'])) : null;
    $eventTime = $news['event_time'] ?? null;
    $eventLocation = $news['event_location'] ?? null;
}

// Process tags
$tags = [];
if (!empty($news['tags'])) {
    if (is_string($news['tags']) && $news['tags'][0] === '[') {
        $tags = json_decode($news['tags'], true) ?: [];
    } else {
        $tags = array_map('trim', explode(',', $news['tags']));
    }
}

// Permission flags
$canEdit = $canEdit ?? true;
$canDelete = $canDelete ?? true;
$canPublish = $canPublish ?? true;

// CSRF token for actions
$csrfToken = $csrf_token ?? $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCT College of Nursing Sciences - <?php echo $pageTitle; ?></title>
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
            --header-height: 60px;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
            --shadow-lg: 0 5px 20px rgba(0,0,0,0.15);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Layout */
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
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
        }

        .college-tagline {
            font-size: 11px;
            opacity: 0.8;
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
            flex-shrink: 0;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .user-info p {
            font-size: 11px;
            opacity: 0.8;
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
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--dark);
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
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
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
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
            padding: 10px 20px;
            background: white;
            color: var(--dark);
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--gray-light);
            transform: translateX(-3px);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
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

        .btn-outline {
            background: white;
            color: var(--secondary);
            border: 1px solid var(--secondary);
        }

        .btn-outline:hover {
            background: var(--secondary);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: var(--danger-dark);
        }

        /* Meta Bar */
        .meta-bar {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
        }

        .meta-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .meta-item i {
            color: var(--secondary);
            width: 20px;
        }

        .meta-label {
            color: var(--gray);
            font-weight: 500;
        }

        .meta-value {
            color: var(--dark);
            font-weight: 600;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-badge.published {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .status-badge.draft {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }

        .featured-badge {
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary);
        }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .content-header {
            padding: 25px 30px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .content-header h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .content-header .meta {
            display: flex;
            gap: 20px;
            font-size: 14px;
            opacity: 0.9;
            flex-wrap: wrap;
        }

        .content-header .meta i {
            margin-right: 5px;
        }

        .content-body {
            padding: 30px;
        }

        /* Featured Image */
        .featured-image {
            margin-bottom: 30px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .featured-image img {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
        }

        .image-caption {
            padding: 10px 15px;
            background: var(--light);
            color: var(--gray);
            font-size: 13px;
            border-top: 1px solid var(--border);
        }

        /* Article Content */
        .article-content {
            font-size: 16px;
            line-height: 1.8;
            color: var(--dark);
        }

        .article-content h1,
        .article-content h2,
        .article-content h3,
        .article-content h4 {
            margin: 1.5em 0 0.5em;
            color: var(--primary);
        }

        .article-content p {
            margin-bottom: 1.2em;
        }

        .article-content ul,
        .article-content ol {
            margin-bottom: 1.2em;
            padding-left: 2em;
        }

        .article-content blockquote {
            margin: 1.5em 0;
            padding: 1em 1.5em;
            background: var(--light);
            border-left: 4px solid var(--secondary);
            font-style: italic;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1em 0;
        }

        .article-content pre {
            background: var(--dark);
            color: white;
            padding: 1em;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1em 0;
        }

        .article-content code {
            background: var(--light);
            padding: 2px 5px;
            border-radius: 4px;
            font-family: monospace;
        }

        /* Excerpt */
        .excerpt {
            background: var(--light);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid var(--secondary);
            font-style: italic;
            color: var(--gray);
        }

        /* Tags */
        .tags {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .tags-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            padding: 4px 12px;
            background: var(--light);
            border-radius: 30px;
            font-size: 13px;
            color: var(--gray);
        }

        /* Event Details */
        .event-details {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .event-details h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .event-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .event-detail-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .event-detail-item i {
            font-size: 20px;
            opacity: 0.9;
        }

        .event-detail-item .label {
            font-size: 12px;
            opacity: 0.8;
            margin-bottom: 2px;
        }

        .event-detail-item .value {
            font-size: 16px;
            font-weight: 600;
        }

        /* Meta Grid */
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 20px;
            margin-bottom: 30px;
        }

        .meta-grid-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .meta-grid-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .meta-grid-content {
            flex: 1;
        }

        .meta-grid-label {
            font-size: 11px;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .meta-grid-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
        }

        /* Action Bar */
        .action-bar {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 20px;
            margin-top: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        /* Delete Form */
        .delete-form {
            display: inline;
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

        /* Responsive */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 20px;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .content-header {
                padding: 20px;
            }

            .content-header h2 {
                font-size: 20px;
            }

            .content-body {
                padding: 20px;
            }

            .meta-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .meta-info {
                gap: 15px;
            }

            .action-bar {
                justify-content: center;
            }

            .btn {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .meta-info {
                flex-direction: column;
                gap: 10px;
            }

            .action-buttons {
                width: 100%;
            }

            .btn {
                width: 100%;
            }

            .event-details-grid {
                grid-template-columns: 1fr;
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
                    <h4><?php echo htmlspecialchars($userName); ?></h4>
                    <p><?php echo ucfirst(str_replace('_', ' ', $userRole)); ?></p>
                </div>
            </div>

            <nav class="nav-menu">
                <a href="<?php echo $baseUrl; ?>/admin/news-manager" class="nav-item">
                    <i class="fas fa-newspaper"></i>
                    <span>Dashboard</span>
                </a>
                
                <?php if ($canEdit): ?>
                <a href="<?php echo $baseUrl; ?>/admin/news-manager/create?type=news" class="nav-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Create News</span>
                </a>
                <a href="<?php echo $baseUrl; ?>/admin/news-manager/create?type=event" class="nav-item">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Create Event</span>
                </a>
                <?php endif; ?>

                <a href="<?php echo $baseUrl; ?>/admin/news-manager/categories" class="nav-item">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
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
                <!-- Page Header -->
                <div class="page-header">
                    <h1>
                        <i class="fas <?php echo $icon; ?>"></i>
                        <?php echo htmlspecialchars($news['title'] ?? 'Untitled'); ?>
                    </h1>
                    <a href="<?php echo $baseUrl; ?>/admin/news-manager" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>

                <!-- Meta Bar -->
                <div class="meta-bar">
                    <div class="meta-info">
                        <div class="meta-item">
                            <i class="fas fa-hashtag"></i>
                            <span class="meta-label">ID:</span>
                            <span class="meta-value">#<?php echo $news['id'] ?? 'N/A'; ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span class="meta-label">Author:</span>
                            <span class="meta-value"><?php echo htmlspecialchars($news['author_name'] ?? 'Unknown'); ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-folder"></i>
                            <span class="meta-label">Category:</span>
                            <span class="meta-value"><?php echo htmlspecialchars($news['category'] ?? 'Uncategorized'); ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span class="meta-label">Created:</span>
                            <span class="meta-value"><?php echo $createdDate; ?></span>
                        </div>
                    </div>
                    <div>
                        <span class="status-badge <?php echo ($news['is_published'] ?? 0) ? 'published' : 'draft'; ?>">
                            <i class="fas <?php echo ($news['is_published'] ?? 0) ? 'fa-check-circle' : 'fa-pen'; ?>"></i>
                            <?php echo ($news['is_published'] ?? 0) ? 'Published' : 'Draft'; ?>
                        </span>
                        <?php if ($news['is_featured'] ?? 0): ?>
                        <span class="status-badge featured-badge" style="margin-left: 10px;">
                            <i class="fas fa-star"></i>
                            Featured
                        </span>
                        <?php endif; ?>
                        <?php if ($news['is_breaking'] ?? 0): ?>
                        <span class="status-badge featured-badge" style="margin-left: 10px; background: rgba(231, 76, 60, 0.1); color: var(--danger);">
                            <i class="fas fa-exclamation-triangle"></i>
                            Breaking
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Event Details (if event) -->
                <?php if ($isEvent && ($eventDate || $eventLocation)): ?>
                <div class="event-details">
                    <h3><i class="fas fa-calendar-alt"></i> Event Details</h3>
                    <div class="event-details-grid">
                        <?php if ($eventDate): ?>
                        <div class="event-detail-item">
                            <i class="fas fa-calendar-day"></i>
                            <div>
                                <div class="label">Date</div>
                                <div class="value">
                                    <?php echo $eventDate; ?>
                                    <?php if ($eventTime): ?>
                                    <br><small style="font-size: 13px;">at <?php echo htmlspecialchars($eventTime); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($eventEndDate): ?>
                        <div class="event-detail-item">
                            <i class="fas fa-calendar-week"></i>
                            <div>
                                <div class="label">End Date</div>
                                <div class="value"><?php echo $eventEndDate; ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($eventLocation): ?>
                        <div class="event-detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <div class="label">Location</div>
                                <div class="value"><?php echo htmlspecialchars($eventLocation); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Main Content Card -->
                <div class="content-card">
                    <div class="content-header">
                        <h2><?php echo htmlspecialchars($news['title'] ?? 'Untitled'); ?></h2>
                        <div class="meta">
                            <span><i class="fas fa-eye"></i> <?php echo number_format($news['views_count'] ?? 0); ?> views</span>
                            <?php if ($publishedDate): ?>
                            <span><i class="fas fa-clock"></i> Published <?php echo $publishedDate; ?></span>
                            <?php endif; ?>
                            <?php if ($updatedDate && $updatedDate !== $createdDate): ?>
                            <span><i class="fas fa-edit"></i> Updated <?php echo $updatedDate; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="content-body">
                        <!-- Featured Image -->
                        <?php if (!empty($news['featured_image'])): ?>
                        <div class="featured-image">
                            <img src="<?php echo $baseUrl . htmlspecialchars($news['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($news['title']); ?>">
                            <div class="image-caption">
                                <i class="fas fa-image"></i> Featured Image
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Excerpt -->
                        <?php if (!empty($news['excerpt'])): ?>
                        <div class="excerpt">
                            <i class="fas fa-quote-left" style="color: var(--secondary); margin-right: 8px;"></i>
                            <?php echo nl2br(htmlspecialchars($news['excerpt'])); ?>
                        </div>
                        <?php endif; ?>

                        <!-- Main Content -->
                        <div class="article-content">
                            <?php echo $news['content'] ?? '<p>No content available.</p>'; ?>
                        </div>

                        <!-- Tags -->
                        <?php if (!empty($tags)): ?>
                        <div class="tags">
                            <div class="tags-title">
                                <i class="fas fa-tags"></i> Tags
                            </div>
                            <div class="tag-list">
                                <?php foreach ($tags as $tag): ?>
                                <?php if (!empty($tag)): ?>
                                <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Meta Grid -->
                <div class="meta-grid">
                    <div class="meta-grid-item">
                        <div class="meta-grid-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="meta-grid-content">
                            <div class="meta-grid-label">Created</div>
                            <div class="meta-grid-value"><?php echo $createdDate; ?></div>
                        </div>
                    </div>
                    
                    <?php if ($updatedDate && $updatedDate !== $createdDate): ?>
                    <div class="meta-grid-item">
                        <div class="meta-grid-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="meta-grid-content">
                            <div class="meta-grid-label">Last Updated</div>
                            <div class="meta-grid-value"><?php echo $updatedDate; ?></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($news['slug'])): ?>
                    <div class="meta-grid-item">
                        <div class="meta-grid-icon">
                            <i class="fas fa-link"></i>
                        </div>
                        <div class="meta-grid-content">
                            <div class="meta-grid-label">URL Slug</div>
                            <div class="meta-grid-value"><?php echo htmlspecialchars($news['slug']); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($news['views_count'])): ?>
                    <div class="meta-grid-item">
                        <div class="meta-grid-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="meta-grid-content">
                            <div class="meta-grid-label">Total Views</div>
                            <div class="meta-grid-value"><?php echo number_format($news['views_count']); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($news['meta_title']) || !empty($news['meta_description'])): ?>
                    <div class="meta-grid-item" style="grid-column: 1 / -1;">
                        <div class="meta-grid-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="meta-grid-content">
                            <div class="meta-grid-label">SEO</div>
                            <div class="meta-grid-value">
                                <?php if (!empty($news['meta_title'])): ?>
                                <strong>Title:</strong> <?php echo htmlspecialchars($news['meta_title']); ?><br>
                                <?php endif; ?>
                                <?php if (!empty($news['meta_description'])): ?>
                                <strong>Description:</strong> <?php echo htmlspecialchars($news['meta_description']); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Action Bar -->
                <div class="action-bar">
                    <?php if ($canEdit): ?>
                    <a href="<?php echo $baseUrl; ?>/admin/news-manager/<?php echo $news['id']; ?>/edit" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <?php endif; ?>

                    <?php if ($canPublish): ?>
                    <form method="POST" action="<?php echo $baseUrl; ?>/admin/news-manager/<?php echo $news['id']; ?>/toggle-publish" style="display: inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        <button type="submit" class="btn btn-success">
                            <i class="fas <?php echo ($news['is_published'] ?? 0) ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                            <?php echo ($news['is_published'] ?? 0) ? 'Unpublish' : 'Publish'; ?>
                        </button>
                    </form>
                    <?php endif; ?>

                    <?php if ($canEdit): ?>
                    <form method="POST" action="<?php echo $baseUrl; ?>/admin/news-manager/<?php echo $news['id']; ?>/toggle-featured" style="display: inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        <button type="submit" class="btn btn-outline">
                            <i class="fas <?php echo ($news['is_featured'] ?? 0) ? 'fa-star-o' : 'fa-star'; ?>"></i>
                            <?php echo ($news['is_featured'] ?? 0) ? 'Remove Featured' : 'Mark Featured'; ?>
                        </button>
                    </form>
                    <?php endif; ?>

                    <?php if (!empty($news['slug']) && ($news['is_published'] ?? 0)): ?>
                    <a href="<?php echo $baseUrl; ?>/news/<?php echo $news['slug']; ?>" target="_blank" class="btn btn-outline">
                        <i class="fas fa-external-link-alt"></i> View on Website
                    </a>
                    <?php endif; ?>

                    <?php if ($canDelete): ?>
                    <form method="POST" action="<?php echo $baseUrl; ?>/admin/news-manager/delete/<?php echo $news['id']; ?>" 
                          class="delete-form" 
                          onsubmit="return confirm('Are you sure you want to delete this item? This action cannot be undone.');">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                    <?php endif; ?>
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
    </script>
</body>
</html>