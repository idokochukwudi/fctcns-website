<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 3); // Go up 3 levels from app/views/admin/

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

// Get statistics
$stats = [];
$recentActivities = [];
$recentApplications = [];

try {
    // Update the queries in your dashboard.php to match your table structure
    $queries = [
        'total_users' => "SELECT COUNT(*) as total FROM users WHERE is_active = 1",
        'total_applications' => "SELECT COUNT(*) as total FROM applications",
        'pending_applications' => "SELECT COUNT(*) as total FROM applications WHERE status = 'pending'",
        'total_research' => "SELECT COUNT(*) as total FROM research_publications WHERE is_published = 1",
        'total_news' => "SELECT COUNT(*) as total FROM news WHERE is_published = 1",
        'recent_activities' => "SELECT al.*, u.username as user_name FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT 10",
        'recent_applications' => "SELECT a.* FROM applications a ORDER BY a.created_at DESC LIMIT 5"
    ];
    
    foreach ($queries as $key => $sql) {
        if ($key === 'recent_activities' || $key === 'recent_applications') {
            continue; // Handle separately
        }
        $stmt = $conn->query($sql);
        $stats[$key] = $stmt->fetch()['total'];
    }
    
    // Recent activities
    $stmt = $conn->query($queries['recent_activities']);
    $recentActivities = $stmt->fetchAll();
    
    // Recent applications
    $stmt = $conn->query($queries['recent_applications']);
    $recentApplications = $stmt->fetchAll();
    
} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FCT College of Nursing Sciences</title>
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
            --admin-gray-900: #1a202c;
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
        
        /* Sidebar */
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: var(--admin-gray-900);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
        }
        
        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: var(--admin-primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.25rem;
        }
        
        .sidebar-title {
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1.2;
        }
        
        .sidebar-subtitle {
            font-size: 0.75rem;
            opacity: 0.7;
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
            padding: 0 1.5rem 0.75rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--admin-gray-500);
        }
        
        .nav-links {
            list-style: none;
        }
        
        .nav-item {
            margin-bottom: 2px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            color: var(--admin-gray-300);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
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
        
        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
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
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }
        
        .user-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.125rem;
        }
        
        .user-info span {
            font-size: 0.75rem;
            color: var(--admin-gray-400);
        }
        
        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: var(--admin-sidebar-width);
            min-height: 100vh;
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
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--admin-gray-800);
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .notification-btn, .logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            color: var(--admin-gray-600);
            transition: all 0.2s;
        }
        
        .notification-btn:hover, .logout-btn:hover {
            background: var(--admin-gray-100);
            color: var(--admin-gray-800);
        }
        
        .admin-content {
            padding: 2rem;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-users .stat-icon { background: rgba(66, 153, 225, 0.1); color: var(--admin-primary-light); }
        .stat-applications .stat-icon { background: rgba(56, 161, 105, 0.1); color: var(--admin-success); }
        .stat-research .stat-icon { background: rgba(159, 122, 234, 0.1); color: #9f7aea; }
        .stat-news .stat-icon { background: rgba(237, 137, 54, 0.1); color: var(--admin-warning); }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--admin-gray-600);
        }
        
        .stat-trend {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .trend-up { background: rgba(56, 161, 105, 0.1); color: var(--admin-success); }
        .trend-down { background: rgba(229, 62, 62, 0.1); color: var(--admin-danger); }
        
        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--admin-gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--admin-gray-800);
        }
        
        .card-header a {
            font-size: 0.875rem;
            color: var(--admin-primary);
            text-decoration: none;
        }
        
        .card-header a:hover {
            text-decoration: underline;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Activity List */
        .activity-list {
            list-style: none;
        }
        
        .activity-item {
            padding: 0.875rem 0;
            border-bottom: 1px solid var(--admin-gray-100);
            display: flex;
            gap: 0.75rem;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .activity-info h4 {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        
        .activity-info p {
            font-size: 0.75rem;
            color: var(--admin-gray-600);
        }
        
        /* Applications Table */
        .applications-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .applications-table th {
            text-align: left;
            padding: 0.75rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--admin-gray-600);
            border-bottom: 1px solid var(--admin-gray-200);
        }
        
        .applications-table td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--admin-gray-100);
        }
        
        .applications-table tr:hover {
            background: var(--admin-gray-50);
        }
        
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-pending { background: rgba(214, 158, 46, 0.1); color: var(--admin-warning); }
        .status-approved { background: rgba(56, 161, 105, 0.1); color: var(--admin-success); }
        .status-rejected { background: rgba(229, 62, 62, 0.1); color: var(--admin-danger); }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: white;
            border: 1px solid var(--admin-gray-200);
            border-radius: 8px;
            text-decoration: none;
            color: var(--admin-gray-700);
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            border-color: var(--admin-primary);
            background: var(--admin-gray-50);
            transform: translateY(-2px);
        }
        
        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(66, 153, 225, 0.1);
            color: var(--admin-primary);
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .admin-sidebar {
                width: 240px;
            }
            
            .admin-main {
                margin-left: 240px;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .admin-sidebar.active {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .admin-header {
                padding: 0 1rem;
            }
            
            .admin-content {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .mobile-menu-toggle {
                display: block;
                background: none;
                border: none;
                color: var(--admin-gray-600);
                cursor: pointer;
                padding: 0.5rem;
            }
        }
        
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
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
                        <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="nav-link active">
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
                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/applications" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                            <span>Applications</span>
                            <?php if ($stats['pending_applications'] > 0): ?>
                            <span style="margin-left: auto; background: var(--admin-warning); color: white; padding: 2px 6px; border-radius: 10px; font-size: 0.75rem;">
                                <?php echo $stats['pending_applications']; ?>
                            </span>
                            <?php endif; ?>
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
                        <a href="<?php echo BASE_URL; ?>/admin/news" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                            </svg>
                            <span>News & Events</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($userRole === 'admin'): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/users" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                            <span>Users</span>
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
                <h1>Dashboard Overview</h1>
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
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </header>
        
        <!-- Content -->
        <div class="admin-content">
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card stat-users">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo $stats['total_users']; ?></div>
                            <div class="stat-label">Total Users</div>
                        </div>
                        <div class="stat-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-trend trend-up">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        +12%
                    </div>
                </div>
                
                <div class="stat-card stat-applications">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo $stats['total_applications']; ?></div>
                            <div class="stat-label">Applications</div>
                        </div>
                        <div class="stat-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-trend trend-up">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        +24%
                    </div>
                </div>
                
                <div class="stat-card stat-research">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo $stats['total_research']; ?></div>
                            <div class="stat-label">Research Papers</div>
                        </div>
                        <div class="stat-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-trend trend-up">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        +8%
                    </div>
                </div>
                
                <div class="stat-card stat-news">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo $stats['total_news']; ?></div>
                            <div class="stat-label">News Articles</div>
                        </div>
                        <div class="stat-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-trend trend-down">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        -3%
                    </div>
                </div>
            </div>
            
            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Activities -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Recent Activities</h3>
                        <a href="<?php echo BASE_URL; ?>/admin/activities">View All</a>
                    </div>
                    <div class="card-body">
                        <ul class="activity-list">
                            <?php foreach ($recentActivities as $activity): ?>
                            <li class="activity-item">
                                <div class="activity-icon" style="background: rgba(66, 153, 225, 0.1); color: var(--admin-primary);">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="activity-info">
                                    <h4><?php echo htmlspecialchars($activity['action']); ?></h4>
                                    <p><?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?>
                                    <?php if (!empty($activity['user_name'])): ?>
                                    <br><small>By: <?php echo htmlspecialchars($activity['user_name']); ?></small>
                                    <?php endif; ?>
                                    </p>
                                </div>
                            </li>
                            <?php endforeach; ?>
                            <?php if (empty($recentActivities)): ?>
                            <li class="activity-item">
                                <div class="activity-info">
                                    <p style="color: var(--admin-gray-600); font-style: italic;">No recent activities</p>
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Recent Applications -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Recent Applications</h3>
                        <a href="<?php echo BASE_URL; ?>/admin/applications">View All</a>
                    </div>
                    <div class="card-body">
                        <table class="applications-table">
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Program</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentApplications as $app): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></strong>
                                        <div style="font-size: 0.75rem; color: var(--admin-gray-600);">
                                            <?php echo htmlspecialchars($app['email']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($app['program_applied']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($app['created_at'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $app['status']; ?>">
                                            <?php echo ucfirst($app['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($recentApplications)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 2rem; color: var(--admin-gray-600); font-style: italic;">
                                        No applications yet
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                <a href="<?php echo BASE_URL; ?>/admin/applications/create" class="action-btn">
                    <div class="action-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>New Application</h4>
                        <p style="font-size: 0.75rem; color: var(--admin-gray-600);">Add manual application</p>
                    </div>
                </a>
                
                <a href="<?php echo BASE_URL; ?>/admin/research/create" class="action-btn">
                    <div class="action-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Add Research</h4>
                        <p style="font-size: 0.75rem; color: var(--admin-gray-600);">Upload new research paper</p>
                    </div>
                </a>
                
                <a href="<?php echo BASE_URL; ?>/admin/news/create" class="action-btn">
                    <div class="action-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Create News</h4>
                        <p style="font-size: 0.75rem; color: var(--admin-gray-600);">Publish news article</p>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if ($userRole === 'admin'): ?>
                <a href="<?php echo BASE_URL; ?>/admin/users/create" class="action-btn">
                    <div class="action-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Add User</h4>
                        <p style="font-size: 0.75rem; color: var(--admin-gray-600);">Create new user account</p>
                    </div>
                </a>
                <?php endif; ?>
                
                <a href="<?php echo BASE_URL; ?>/admin/reports/generate" class="action-btn">
                    <div class="action-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Generate Report</h4>
                        <p style="font-size: 0.75rem; color: var(--admin-gray-600);">Create system report</p>
                    </div>
                </a>
            </div>
        </div>
    </main>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
        
        // Close mobile menu when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('mobileMenuToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
        
        // Auto-refresh dashboard stats every 5 minutes
        setTimeout(function() {
            location.reload();
        }, 5 * 60 * 1000);
        
        // Chart data (you would replace this with actual data from your system)
        const chartData = {
            applications: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Applications',
                    data: [65, 59, 80, 81, 56, 55],
                    backgroundColor: 'rgba(56, 161, 105, 0.2)',
                    borderColor: 'rgba(56, 161, 105, 1)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            }
        };
        
        // Initialize charts (requires Chart.js library - add this to your header)
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Chart.js is available
            if (typeof Chart !== 'undefined') {
                const ctx = document.getElementById('applicationsChart');
                if (ctx) {
                    new Chart(ctx.getContext('2d'), {
                        type: 'line',
                        data: chartData.applications,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });
        
        // Real-time notifications (simulated)
        function checkNotifications() {
            fetch('/admin/api/notifications/unread')
                .then(response => response.json())
                .then(data => {
                    if (data.count > 0) {
                        const notificationBtn = document.querySelector('.notification-btn');
                        notificationBtn.innerHTML = `
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                            </svg>
                            <span style="position: absolute; top: -5px; right: -5px; background: var(--admin-danger); color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center;">
                                ${data.count}
                            </span>
                        `;
                    }
                })
                .catch(error => console.error('Notification error:', error));
        }
        
        // Check notifications every 30 seconds
        setInterval(checkNotifications, 30000);
        checkNotifications(); // Initial check
        
        // Auto logout warning after 30 minutes of inactivity
        let idleTime = 0;
        const idleInterval = setInterval(timerIncrement, 60000); // 1 minute
        
        function timerIncrement() {
            idleTime++;
            if (idleTime > 29) { // 30 minutes
                showLogoutWarning();
            }
        }
        
        function resetIdleTime() {
            idleTime = 0;
        }
        
        // Reset idle time on user activity
        document.addEventListener('mousemove', resetIdleTime);
        document.addEventListener('keypress', resetIdleTime);
        document.addEventListener('click', resetIdleTime);
        document.addEventListener('scroll', resetIdleTime);
        
        function showLogoutWarning() {
            if (!document.getElementById('logout-warning')) {
                const warning = document.createElement('div');
                warning.id = 'logout-warning';
                warning.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: white;
                    padding: 2rem;
                    border-radius: 12px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                    z-index: 2000;
                    text-align: center;
                    max-width: 400px;
                    width: 90%;
                `;
                
                warning.innerHTML = `
                    <h3 style="margin-bottom: 1rem; color: var(--admin-warning);">Session Expiring Soon</h3>
                    <p style="margin-bottom: 1.5rem; color: var(--admin-gray-700);">
                        Your session will expire in 5 minutes due to inactivity.
                    </p>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <button onclick="extendSession()" style="padding: 0.5rem 1.5rem; background: var(--admin-primary); color: white; border: none; border-radius: 6px; cursor: pointer;">
                            Stay Logged In
                        </button>
                        <button onclick="logoutNow()" style="padding: 0.5rem 1.5rem; background: var(--admin-gray-200); color: var(--admin-gray-700); border: none; border-radius: 6px; cursor: pointer;">
                            Logout Now
                        </button>
                    </div>
                `;
                
                document.body.appendChild(warning);
                
                // Add overlay
                const overlay = document.createElement('div');
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 1999;
                `;
                document.body.appendChild(overlay);
            }
        }
        
        function extendSession() {
            fetch('/admin/api/session/extend', {
                method: 'POST',
                credentials: 'same-origin'
            })
            .then(() => {
                resetIdleTime();
                const warning = document.getElementById('logout-warning');
                const overlay = document.querySelector('#logout-warning + div');
                if (warning) warning.remove();
                if (overlay) overlay.remove();
            })
            .catch(error => console.error('Session extend error:', error));
        }
        
        function logoutNow() {
            window.location.href = '/admin/logout';
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.remove('active');
            }
        });
        
        // Print functionality
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
    
    <!-- Add Chart.js for graphs (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>