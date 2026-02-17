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

// Function to check if table has a column
function tableHasColumn($conn, $table, $column) {
    try {
        $stmt = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        return false;
    }
}

// Get statistics
$stats = [];
$recentActivities = [];
$recentApplications = [];
$recentContacts = [];
$recentNews = [];
$recentEvents = [];

try {
    // Check database structure
    $newsHasType = tableHasColumn($conn, 'news', 'type');
    $eventsTableExists = tableHasColumn($conn, 'events', 'id');
    
    // ===== APPLICATION STATISTICS =====
    try {
        // Get application statistics
        $appStats = $conn->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'reviewed' THEN 1 ELSE 0 END) as reviewed,
                SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
            FROM applications
        ")->fetch();
        
        $stats['total_applications'] = $appStats['total'] ?? 0;
        $stats['pending_applications'] = $appStats['pending'] ?? 0;
        $stats['reviewed_applications'] = $appStats['reviewed'] ?? 0;
        $stats['accepted_applications'] = $appStats['accepted'] ?? 0;
        $stats['rejected_applications'] = $appStats['rejected'] ?? 0;
        
        // Recent applications with applicant details
        $stmt = $conn->query("
            SELECT a.*, ap.first_name, ap.last_name, ap.email 
            FROM applications a
            JOIN applicants ap ON a.applicant_id = ap.id
            ORDER BY a.created_at DESC
            LIMIT 5
        ");
        $recentApplications = $stmt->fetchAll();
        
    } catch (Exception $e) {
        error_log("Error fetching application stats: " . $e->getMessage());
        $stats['total_applications'] = 0;
        $stats['pending_applications'] = 0;
        $stats['reviewed_applications'] = 0;
        $stats['accepted_applications'] = 0;
        $stats['rejected_applications'] = 0;
        $recentApplications = [];
    }
    
    // ===== NEWS AND EVENTS STATISTICS =====
    try {
        // Check if news table has type column (your current structure)
        $newsHasType = tableHasColumn($conn, 'news', 'type');
        
        if ($newsHasType) {
            // Combined news and events in same table
            $stmt = $conn->query("
                SELECT 
                    COUNT(*) as total_all,
                    SUM(type = 'news' OR type IS NULL) as total_news,
                    SUM(type = 'event') as total_events,
                    SUM((type = 'news' OR type IS NULL) AND is_published = 1) as published_news,
                    SUM(type = 'event' AND is_published = 1) as published_events,
                    SUM((type = 'news' OR type IS NULL) AND is_published = 0) as draft_news,
                    SUM(type = 'event' AND is_published = 0) as draft_events
                FROM news
            ");
            $newsStats = $stmt->fetch();
            
            $stats['total_news'] = $newsStats['total_news'] ?? 0;
            $stats['total_events'] = $newsStats['total_events'] ?? 0;
            $stats['published_news'] = $newsStats['published_news'] ?? 0;
            $stats['published_events'] = $newsStats['published_events'] ?? 0;
            $stats['draft_news'] = $newsStats['draft_news'] ?? 0;
            $stats['draft_events'] = $newsStats['draft_events'] ?? 0;
            $stats['total_news_events'] = ($newsStats['total_news'] ?? 0) + ($newsStats['total_events'] ?? 0);
        } else {
            // Separate tables
            $stmt = $conn->query("SELECT COUNT(*) as total FROM news WHERE is_published = 1");
            $stats['published_news'] = $stmt->fetch()['total'];
            
            $stmt = $conn->query("SELECT COUNT(*) as total FROM news");
            $stats['total_news'] = $stmt->fetch()['total'];
            
            $stmt = $conn->query("SELECT COUNT(*) as total FROM events WHERE is_published = 1");
            $stats['published_events'] = $stmt->fetch()['total'];
            
            $stmt = $conn->query("SELECT COUNT(*) as total FROM events");
            $stats['total_events'] = $stmt->fetch()['total'];
            
            $stats['draft_news'] = $stats['total_news'] - $stats['published_news'];
            $stats['draft_events'] = $stats['total_events'] - $stats['published_events'];
            $stats['total_news_events'] = $stats['total_news'] + $stats['total_events'];
        }
    } catch (Exception $e) {
        error_log("Error fetching news stats: " . $e->getMessage());
        $stats['total_news'] = $stats['total_events'] = 0;
        $stats['published_news'] = $stats['published_events'] = 0;
        $stats['draft_news'] = $stats['draft_events'] = 0;
        $stats['total_news_events'] = 0;
    }
    
    if ($newsHasType && !$eventsTableExists) {
        // OLD SYSTEM: news table contains both news and events (has type column)
        $queries = [
            'total_users' => "SELECT COUNT(*) as total FROM users WHERE is_active = 1",
            'total_research' => "SELECT COUNT(*) as total FROM research_publications WHERE is_published = 1",
            'upcoming_events' => "SELECT COUNT(*) as total FROM news WHERE is_published = 1 AND type = 'event' AND (event_date >= CURDATE() OR event_date IS NULL)",
            'total_contacts' => "SELECT COUNT(*) as total FROM contact_submissions",
            'pending_contacts' => "SELECT COUNT(*) as total FROM contact_submissions WHERE status = 'pending'",
            'recent_activities' => "SELECT al.*, u.username as user_name FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT 10",
            'recent_news' => "SELECT n.*, u.username as author_name FROM news n LEFT JOIN users u ON n.author_id = u.id WHERE n.is_published = 1 AND n.type = 'news' ORDER BY n.created_at DESC LIMIT 5",
            'recent_events' => "SELECT n.*, u.username as author_name FROM news n LEFT JOIN users u ON n.author_id = u.id WHERE n.is_published = 1 AND n.type = 'event' ORDER BY n.event_date DESC, n.created_at DESC LIMIT 5"
        ];
    } else {
        // NEW SYSTEM: separate tables for news and events
        $queries = [
            'total_users' => "SELECT COUNT(*) as total FROM users WHERE is_active = 1",
            'total_research' => "SELECT COUNT(*) as total FROM research_publications WHERE is_published = 1",
            'upcoming_events' => "SELECT COUNT(*) as total FROM events WHERE is_published = 1 AND (event_date >= CURDATE() OR event_date IS NULL)",
            'total_contacts' => "SELECT COUNT(*) as total FROM contact_submissions",
            'pending_contacts' => "SELECT COUNT(*) as total FROM contact_submissions WHERE status = 'pending'",
            'recent_activities' => "SELECT al.*, u.username as user_name FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT 10",
            'recent_news' => "SELECT n.*, u.username as author_name FROM news n LEFT JOIN users u ON n.author_id = u.id WHERE n.is_published = 1 ORDER BY n.created_at DESC LIMIT 5",
            'recent_events' => "SELECT e.*, u.username as author_name FROM events e LEFT JOIN users u ON e.author_id = u.id WHERE e.is_published = 1 ORDER BY e.event_date DESC, e.created_at DESC LIMIT 5"
        ];
    }
    
    foreach ($queries as $key => $sql) {
        if (in_array($key, ['recent_activities', 'recent_news', 'recent_events'])) {
            continue; // Handle separately
        }
        $stmt = $conn->query($sql);
        $stats[$key] = $stmt->fetch()['total'];
    }
    
    // Recent activities
    $stmt = $conn->query($queries['recent_activities']);
    $recentActivities = $stmt->fetchAll();
    
    // Recent contact submissions
    $stmt = $conn->query("
        SELECT cs.* FROM contact_submissions cs 
        ORDER BY cs.created_at DESC 
        LIMIT 5
    ");
    $recentContacts = $stmt->fetchAll();
    
    // Recent news
    $stmt = $conn->query($queries['recent_news']);
    $recentNews = $stmt->fetchAll();
    
    // Recent events
    $stmt = $conn->query($queries['recent_events']);
    $recentEvents = $stmt->fetchAll();
    
} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
    // Set default values to prevent errors
    $stats = array_merge([
        'total_users' => 0,
        'total_applications' => 0,
        'pending_applications' => 0,
        'reviewed_applications' => 0,
        'accepted_applications' => 0,
        'rejected_applications' => 0,
        'total_research' => 0,
        'total_news' => 0,
        'total_events' => 0,
        'total_news_events' => 0,
        'published_news' => 0,
        'published_events' => 0,
        'draft_news' => 0,
        'draft_events' => 0,
        'upcoming_events' => 0,
        'total_contacts' => 0,
        'pending_contacts' => 0
    ], $stats);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Admin Dashboard - FCT College of Nursing Sciences</title>
    <style>
        /* Admin Dashboard Styles - Enhanced for Responsiveness */
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
            --admin-news: #4f46e5;
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
        
        /* Sidebar */
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
            cursor: pointer;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            text-decoration: none;
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
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }
        
        .stat-header {
            display: flex;
            align-items: flex-start;
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
            flex-shrink: 0;
        }
        
        .stat-users .stat-icon { background: rgba(66, 153, 225, 0.1); color: var(--admin-primary-light); }
        .stat-applications .stat-icon { background: rgba(56, 161, 105, 0.1); color: var(--admin-success); }
        .stat-research .stat-icon { background: rgba(159, 122, 234, 0.1); color: var(--admin-purple); }
        .stat-news .stat-icon { background: rgba(245, 158, 11, 0.1); color: var(--admin-warning); }
        .stat-events .stat-icon { background: rgba(139, 92, 246, 0.1); color: var(--admin-purple); }
        
        .stat-value {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            line-height: 1;
            background: linear-gradient(135deg, currentColor, var(--admin-gray-800));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--admin-gray-600);
            font-weight: 500;
        }
        
        .stat-trend {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 600;
            margin-top: 0.75rem;
        }
        
        .trend-up { background: rgba(56, 161, 105, 0.1); color: var(--admin-success); }
        .trend-down { background: rgba(229, 62, 62, 0.1); color: var(--admin-danger); }
        
        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 400px), 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .content-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--admin-gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--admin-gray-50);
        }
        
        .card-header h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--admin-gray-800);
            letter-spacing: -0.025em;
        }
        
        .card-header a {
            font-size: 0.875rem;
            color: var(--admin-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
        
        .card-header a:hover {
            color: var(--admin-primary-dark);
            background: var(--admin-gray-100);
        }
        
        .card-body {
            padding: 1.5rem;
            overflow-x: auto;
        }
        
        /* Activity List */
        .activity-list {
            list-style: none;
        }
        
        .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--admin-gray-100);
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 0.125rem;
        }
        
        .activity-info {
            flex: 1;
            min-width: 0;
        }
        
        .activity-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--admin-gray-800);
            line-height: 1.4;
        }
        
        .activity-info p {
            font-size: 0.8125rem;
            color: var(--admin-gray-600);
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
        }
        
        /* Tables */
        .applications-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 600px;
        }
        
        .applications-table th {
            text-align: left;
            padding: 0.875rem 1rem;
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
        
        .applications-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--admin-gray-100);
            vertical-align: middle;
        }
        
        .applications-table tbody tr {
            transition: background-color 0.2s;
        }
        
        .applications-table tbody tr:hover {
            background: var(--admin-gray-50);
        }
        
        .applications-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .status-pending { background: rgba(214, 158, 46, 0.1); color: var(--admin-warning); }
        .status-review { background: rgba(49, 130, 206, 0.1); color: var(--admin-info); }
        .status-approved { background: rgba(56, 161, 105, 0.1); color: var(--admin-success); }
        .status-rejected { background: rgba(229, 62, 62, 0.1); color: var(--admin-danger); }
        .status-published { background: rgba(56, 161, 105, 0.1); color: var(--admin-success); }
        .status-draft { background: rgba(214, 158, 46, 0.1); color: var(--admin-warning); }
        .status-upcoming { background: rgba(139, 92, 246, 0.1); color: var(--admin-purple); }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 250px), 1fr));
            gap: 1.25rem;
            margin-top: 2rem;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 1.25rem;
            background: white;
            border: 1px solid var(--admin-gray-200);
            border-radius: 12px;
            text-decoration: none;
            color: var(--admin-gray-800);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .action-btn:hover {
            border-color: var(--admin-primary);
            background: var(--admin-gray-50);
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            text-decoration: none;
        }
        
        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(66, 153, 225, 0.1);
            color: var(--admin-primary);
            flex-shrink: 0;
            transition: all 0.3s;
        }
        
        .action-btn:hover .action-icon {
            transform: scale(1.1);
        }
        
        .action-btn h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--admin-gray-800);
        }
        
        .action-btn p {
            font-size: 0.8125rem;
            color: var(--admin-gray-600);
            line-height: 1.4;
        }
        
        /* News/Events specific action icons */
        .news-action-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--admin-warning);
        }
        
        .events-action-icon {
            background: rgba(139, 92, 246, 0.1);
            color: var(--admin-purple);
        }
        
        /* Event date badge */
        .event-date {
            font-size: 0.75rem;
            color: var(--admin-purple);
            background: rgba(139, 92, 246, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            margin-top: 0.25rem;
        }
        
        /* Breaking news badge */
        .breaking-badge {
            background: rgba(229, 62, 62, 0.1);
            color: var(--admin-danger);
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
            font-size: 0.6875rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        
        /* Featured badge */
        .featured-badge {
            background: rgba(66, 153, 225, 0.1);
            color: var(--admin-primary);
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
            font-size: 0.6875rem;
            font-weight: 600;
        }
        
        /* Enhanced Responsive Design */
        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
        }
        
        @media (max-width: 1200px) {
            .admin-sidebar {
                width: 240px;
            }
            
            .admin-main {
                margin-left: 240px;
            }
        }
        
        @media (max-width: 1024px) {
            .admin-sidebar {
                width: 220px;
            }
            
            .admin-main {
                margin-left: 220px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }
            
            .content-grid {
                grid-template-columns: 1fr;
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
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .stat-card {
                padding: 1.25rem;
            }
            
            .stat-value {
                font-size: 1.875rem;
            }
            
            .content-card {
                margin-bottom: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-header {
                flex-wrap: wrap;
                height: auto;
                min-height: 64px;
                padding: 0.75rem 1rem;
            }
            
            .header-title {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .applications-table {
                font-size: 0.875rem;
                min-width: unset;
            }
            
            .applications-table th,
            .applications-table td {
                padding: 0.75rem 0.5rem;
            }
            
            .activity-item {
                padding: 0.875rem 0;
            }
            
            .activity-info p {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }
        }
        
        @media (max-width: 480px) {
            .admin-sidebar {
                width: 100%;
                max-width: 280px;
            }
            
            .admin-content {
                padding: 0.75rem;
            }
            
            .stat-card {
                padding: 1rem;
            }
            
            .stat-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
            }
            
            .stat-value {
                font-size: 1.75rem;
            }
            
            .action-btn {
                padding: 1rem;
            }
            
            .action-icon {
                width: 40px;
                height: 40px;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            
            .card-header a {
                align-self: flex-start;
            }
        }
        
        @media (max-width: 360px) {
            html {
                font-size: 14px;
            }
            
            .stats-grid {
                gap: 0.75rem;
            }
            
            .content-grid {
                gap: 1rem;
            }
            
            .quick-actions {
                gap: 1rem;
            }
        }
        
        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .stat-card:hover {
                transform: none;
            }
            
            .action-btn:hover {
                transform: none;
                box-shadow: var(--shadow-md);
            }
            
            .nav-link {
                padding: 1rem 1.25rem;
            }
            
            .notification-btn,
            .logout-btn,
            .mobile-menu-toggle {
                padding: 0.625rem;
                min-width: 44px;
                min-height: 44px;
            }
        }
        
        /* Print styles */
        @media print {
            .admin-sidebar,
            .admin-header,
            .action-btn,
            .quick-actions,
            .card-header a,
            .stat-trend {
                display: none !important;
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .admin-content {
                padding: 0;
            }
            
            .stat-card,
            .content-card {
                box-shadow: none;
                border: 1px solid var(--admin-gray-300);
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
        
        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .stat-card {
                border: 2px solid;
            }
            
            .action-btn {
                border: 2px solid var(--admin-gray-300);
            }
            
            .nav-link {
                border-left-width: 4px;
            }
        }
        
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
            
            .stat-card:hover,
            .action-btn:hover {
                transform: none;
            }
        }
        
        /* Loading states */
        .loading {
            position: relative;
            overflow: hidden;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Empty states */
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
        
        /* Focus styles for accessibility */
        :focus-visible {
            outline: 3px solid var(--admin-primary-light);
            outline-offset: 2px;
        }
        
        /* Skip to main content link for screen readers */
        .skip-to-content {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--admin-primary);
            color: white;
            padding: 0.75rem 1rem;
            text-decoration: none;
            z-index: 9999;
            transition: top 0.2s;
        }
        
        .skip-to-content:focus {
            top: 0;
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
                    <!-- NEWS MANAGER SIDEBAR LINK -->
                    <?php 
                    $showNewsManager = in_array($userRole, ['admin', 'editor', 'news_manager']) || 
                                      (isset($_SESSION['user_roles']) && in_array('news_manager', $_SESSION['user_roles']));
                    if ($showNewsManager): 
                    ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/news-manager" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                            </svg>
                            <span>News & Events</span>
                            <?php if (($stats['draft_news'] + $stats['draft_events']) > 0): ?>
                            <span class="nav-badge">
                                <?php echo $stats['draft_news'] + $stats['draft_events']; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($userRole !== 'nominal_roll_user'): // HIDE FOR NOMINAL ROLL ONLY USERS ?>
                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/applications" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                            <span>Applications</span>
                            <?php if ($stats['pending_applications'] > 0): ?>
                            <span class="nav-badge">
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
                            <span>News Articles</span>
                        </a>
                    </li>
                    
                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/events" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span>Events</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                    
                    <!-- Carousel Slides Link -->
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
                    <?php endif; // End hide for nominal_roll_user ?>
                    
                    <!-- ALWAYS SHOW NOMINAL ROLL -->
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
                    
                    <!-- Contact Management Link - Only show for non-nominal_roll_user -->
                    <?php if ($userRole !== 'nominal_roll_user'): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/contact" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/>
                            </svg>
                            <span>Contact Messages</span>
                            <?php 
                            $pendingCount = $stats['pending_contacts'] ?? 0;
                            if ($pendingCount > 0): 
                            ?>
                            <span class="nav-badge">
                                <?php echo $pendingCount; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($userRole === 'admin'): ?>
                    <!-- User Management Link -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/users" class="nav-link" onclick="return handleUserManagementClick(event)">
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
            
            <?php if ($userRole !== 'nominal_roll_user'): // Hide tools section for nominal_roll_user ?>
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
                    <!-- My Profile Link - Added -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/users/profile" class="nav-link">
                            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span>My Profile</span>
                        </a>
                    </li>
                    
                    <!-- Change Password Link - Added -->
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
                        // Display role with proper formatting
                        $roleDisplayNames = [
                            'admin' => 'Administrator',
                            'editor' => 'Editor',
                            'viewer' => 'Viewer',
                            'moderator' => 'Moderator',
                            'supervisor' => 'Supervisor',
                            'nominal_roll_user' => 'Nominal Roll User',
                            'research_manager' => 'Research Manager',
                            'news_manager' => 'News & Events Manager'
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
                <h1>Dashboard Overview</h1>
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
        
        <!-- Content -->
        <div class="admin-content">
            <!-- Stats Grid -->
            <div class="stats-grid">
                <!-- NEWS MANAGER STAT CARD -->
                <?php 
                $showNewsManager = in_array($userRole, ['admin', 'editor', 'news_manager']) || 
                                  (isset($_SESSION['user_roles']) && in_array('news_manager', $_SESSION['user_roles']));
                if ($showNewsManager): 
                ?>
                <div class="stat-card" style="border-left-color: var(--admin-news);" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/news-manager'">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo number_format($stats['total_news_events'] ?? 0); ?></div>
                            <div class="stat-label">News & Events</div>
                        </div>
                        <div class="stat-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--admin-news);">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-trend" style="background: rgba(79, 70, 229, 0.1); color: var(--admin-news);">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo ($stats['published_news'] + $stats['published_events']) ?? 0; ?> published
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($userRole !== 'nominal_roll_user'): // Hide non-nominal roll stats ?>
                <div class="stat-card stat-users" style="border-left-color: var(--admin-primary);" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/users'">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo number_format($stats['total_users']); ?></div>
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
                
                <div class="stat-card stat-applications" style="border-left-color: var(--admin-success);" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/applications'">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo number_format($stats['total_applications']); ?></div>
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
                        <?php echo $stats['pending_applications']; ?> pending
                    </div>
                </div>
                
                <div class="stat-card stat-research" style="border-left-color: var(--admin-purple);" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/research'">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo number_format($stats['total_research']); ?></div>
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
                
                <!-- News Statistics Card -->
                <div class="stat-card stat-news" style="border-left-color: var(--admin-warning);" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/news'">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo number_format($stats['total_news']); ?></div>
                            <div class="stat-label">News Articles</div>
                        </div>
                        <div class="stat-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-trend trend-up">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo $stats['published_news']; ?> published
                    </div>
                </div>

                <!-- Events Statistics Card -->
                <div class="stat-card stat-events" style="border-left-color: var(--admin-purple);" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/events'">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo number_format($stats['total_events']); ?></div>
                            <div class="stat-label">Events</div>
                        </div>
                        <div class="stat-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-trend trend-up">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo $stats['upcoming_events']; ?> upcoming
                    </div>
                </div>
                
                <!-- Contact Messages Card -->
                <div class="stat-card" style="border-left-color: var(--admin-info);" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/contact'">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo number_format($stats['total_contacts'] ?? 0); ?></div>
                            <div class="stat-label">Contact Messages</div>
                        </div>
                        <div class="stat-icon" style="background: rgba(49, 130, 206, 0.1); color: var(--admin-info);">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-trend <?php echo ($stats['pending_contacts'] ?? 0) > 0 ? 'trend-up' : 'trend-down'; ?>" 
                         style="background: rgba(214, 158, 46, 0.1); color: var(--admin-warning);">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo $stats['pending_contacts'] ?? 0; ?> pending
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Nominal Roll Statistics Card - Always shown -->
                <?php 
                // Get nominal roll statistics
                $nominalStats = [];
                try {
                    $nominalQueries = [
                        'total_records' => "SELECT COUNT(*) as total FROM nominal_roll_employees",
                        'active_records' => "SELECT COUNT(*) as total FROM nominal_roll_employees WHERE status = 'active'",
                        'pending_records' => "SELECT COUNT(*) as total FROM nominal_roll_employees WHERE status = 'pending'"
                    ];
                    
                    foreach ($nominalQueries as $key => $sql) {
                        $stmt = $conn->query($sql);
                        $nominalStats[$key] = $stmt->fetch()['total'];
                    }
                } catch (Exception $e) {
                    $nominalStats = [
                        'total_records' => 0,
                        'active_records' => 0,
                        'pending_records' => 0
                    ];
                }
                ?>
                
                <div class="stat-card" style="border-left-color: var(--admin-purple);" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/nominal-roll'">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value"><?php echo number_format($nominalStats['total_records']); ?></div>
                            <div class="stat-label">Total Nominal Records</div>
                        </div>
                        <div class="stat-icon" style="background: rgba(159, 122, 234, 0.1); color: var(--admin-purple);">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-trend trend-up" style="background: rgba(56, 161, 105, 0.1); color: var(--admin-success);">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo $nominalStats['active_records']; ?> active
                    </div>
                </div>
            </div>
            
            <!-- Content Grid -->
            <?php if ($userRole !== 'nominal_roll_user'): // Hide non-nominal roll content ?>
            <div class="content-grid">
                <!-- Recent Activities -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Recent Activities</h3>
                        <a href="<?php echo BASE_URL; ?>/admin/activities">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentActivities)): ?>
                        <ul class="activity-list">
                            <?php foreach (array_slice($recentActivities, 0, 5) as $activity): ?>
                            <li class="activity-item">
                                <div class="activity-icon" style="background: rgba(66, 153, 225, 0.1); color: var(--admin-primary);">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="activity-info">
                                    <h4><?php echo htmlspecialchars($activity['action']); ?></h4>
                                    <p>
                                        <?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?>
                                        <?php if (!empty($activity['user_name'])): ?>
                                        <span>• By: <?php echo htmlspecialchars($activity['user_name']); ?></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📊</div>
                            <div class="empty-state-title">No recent activities</div>
                            <div class="empty-state-description">Activities will appear here as users interact with the system.</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Applications Overview Card -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Applications Overview</h3>
                        <a href="<?php echo BASE_URL; ?>/admin/applications">View All</a>
                    </div>
                    <div class="card-body">
                        <?php
                        // Get application statistics
                        try {
                            $appStats = $conn->query("
                                SELECT 
                                    COUNT(*) as total,
                                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                                    SUM(CASE WHEN status = 'reviewed' THEN 1 ELSE 0 END) as reviewed,
                                    SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                                FROM applications
                            ")->fetch();
                        } catch (Exception $e) {
                            $appStats = ['total' => 0, 'pending' => 0, 'reviewed' => 0, 'accepted' => 0, 'rejected' => 0];
                        }
                        ?>
                        
                        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.5rem; margin-bottom: 1.5rem;">
                            <div style="text-align: center; padding: 0.75rem; background: #f7fafc; border-radius: 8px;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #2d3748;"><?php echo $appStats['total']; ?></div>
                                <div style="font-size: 0.75rem; color: #718096;">Total</div>
                            </div>
                            <div style="text-align: center; padding: 0.75rem; background: #fef3c7; border-radius: 8px;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #d97706;"><?php echo $appStats['pending']; ?></div>
                                <div style="font-size: 0.75rem; color: #92400e;">Pending</div>
                            </div>
                            <div style="text-align: center; padding: 0.75rem; background: #e0f2fe; border-radius: 8px;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #0284c7;"><?php echo $appStats['reviewed']; ?></div>
                                <div style="font-size: 0.75rem; color: #075985;">Reviewed</div>
                            </div>
                            <div style="text-align: center; padding: 0.75rem; background: #d1fae5; border-radius: 8px;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #059669;"><?php echo $appStats['accepted']; ?></div>
                                <div style="font-size: 0.75rem; color: #065f46;">Accepted</div>
                            </div>
                            <div style="text-align: center; padding: 0.75rem; background: #fee2e2; border-radius: 8px;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #dc2626;"><?php echo $appStats['rejected']; ?></div>
                                <div style="font-size: 0.75rem; color: #991b1b;">Rejected</div>
                            </div>
                        </div>
                        
                        <!-- Recent Applications -->
                        <h4 style="margin: 1rem 0 0.75rem; font-size: 0.875rem; color: #4a5568;">Recent Applications</h4>
                        <?php
                        try {
                            $recentApps = $conn->query("
                                SELECT a.*, ap.first_name, ap.last_name, ap.email 
                                FROM applications a
                                JOIN applicants ap ON a.applicant_id = ap.id
                                ORDER BY a.created_at DESC
                                LIMIT 5
                            ")->fetchAll();
                        } catch (Exception $e) {
                            $recentApps = [];
                        }
                        ?>
                        
                        <?php if (!empty($recentApps)): ?>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <th style="text-align: left; padding: 0.5rem; font-size: 0.75rem; color: #718096;">Applicant</th>
                                    <th style="text-align: left; padding: 0.5rem; font-size: 0.75rem; color: #718096;">Program</th>
                                    <th style="text-align: left; padding: 0.5rem; font-size: 0.75rem; color: #718096;">Date</th>
                                    <th style="text-align: left; padding: 0.5rem; font-size: 0.75rem; color: #718096;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentApps as $app): ?>
                                <tr style="border-bottom: 1px solid #edf2f7;">
                                    <td style="padding: 0.5rem;">
                                        <a href="<?php echo BASE_URL; ?>/admin/applications/view/<?php echo $app['id']; ?>" style="color: #2c5282; text-decoration: none; font-weight: 500;">
                                            <?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?>
                                        </a>
                                        <div style="font-size: 0.75rem; color: #718096;"><?php echo htmlspecialchars($app['application_number']); ?></div>
                                    </td>
                                    <td style="padding: 0.5rem;"><?php echo htmlspecialchars($app['program_choice_1']); ?></td>
                                    <td style="padding: 0.5rem;"><?php echo date('M d, Y', strtotime($app['created_at'])); ?></td>
                                    <td style="padding: 0.5rem;">
                                        <span style="display: inline-block; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; 
                                            <?php
                                            $colors = [
                                                'pending' => 'background: #fef3c7; color: #d97706;',
                                                'reviewed' => 'background: #e0f2fe; color: #0284c7;',
                                                'accepted' => 'background: #d1fae5; color: #059669;',
                                                'rejected' => 'background: #fee2e2; color: #dc2626;'
                                            ];
                                            echo $colors[$app['status']] ?? 'background: #f1f5f9; color: #475569;';
                                            ?>
                                        ">
                                            <?php echo ucfirst($app['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div style="text-align: center; padding: 2rem; color: #a0aec0;">
                            No applications yet
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Contact Submissions -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Recent Contact Messages</h3>
                        <a href="<?php echo BASE_URL; ?>/admin/contact">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentContacts)): ?>
                        <table class="applications-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentContacts as $contact): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/admin/contact/view/<?php echo $contact['id']; ?>" 
                                           style="color: var(--admin-primary); text-decoration: none; font-weight: 500;">
                                            <?php echo htmlspecialchars($contact['name']); ?>
                                        </a>
                                        <div style="font-size: 0.75rem; color: var(--admin-gray-600);">
                                            <?php echo htmlspecialchars($contact['email'] ?? ''); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars(mb_strlen($contact['subject']) > 30 ? substr($contact['subject'], 0, 30) . '...' : $contact['subject']); ?>
                                    </td>
                                    <td><?php echo date('M d', strtotime($contact['created_at'])); ?></td>
                                    <td>
                                        <?php if ($contact['status'] === 'pending'): ?>
                                        <span class="status-badge status-pending">Pending</span>
                                        <?php else: ?>
                                        <span class="status-badge status-approved">Responded</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📧</div>
                            <div class="empty-state-title">No contact messages</div>
                            <div class="empty-state-description">Contact messages will appear here when users contact the site.</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent News -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Recent News</h3>
                        <a href="<?php echo BASE_URL; ?>/admin/news">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentNews)): ?>
                        <table class="applications-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentNews as $newsItem): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/admin/news/<?php echo $newsItem['id']; ?>" 
                                           style="color: var(--admin-primary); text-decoration: none; font-weight: 500;">
                                            <?php echo htmlspecialchars(mb_strlen($newsItem['title']) > 30 ? substr($newsItem['title'], 0, 30) . '...' : $newsItem['title']); ?>
                                        </a>
                                        <?php if ($newsItem['is_breaking'] ?? false): ?>
                                        <span class="breaking-badge">Breaking</span>
                                        <?php endif; ?>
                                        <?php if ($newsItem['is_featured'] ?? false): ?>
                                        <span class="featured-badge">Featured</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($newsItem['category'] ?? 'General'); ?></td>
                                    <td><?php echo date('M d', strtotime($newsItem['created_at'])); ?></td>
                                    <td>
                                        <?php if ($newsItem['is_published'] ?? false): ?>
                                        <span class="status-badge status-published">Published</span>
                                        <?php else: ?>
                                        <span class="status-badge status-draft">Draft</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📰</div>
                            <div class="empty-state-title">No recent news</div>
                            <div class="empty-state-description">News articles will appear here when published.</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Events -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Recent Events</h3>
                        <a href="<?php echo BASE_URL; ?>/admin/events">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentEvents)): ?>
                        <table class="applications-table">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentEvents as $event): 
                                    $isUpcoming = isset($event['event_date']) && strtotime($event['event_date']) > time();
                                    $statusClass = $isUpcoming ? 'status-upcoming' : ($event['is_published'] ?? false ? 'status-published' : 'status-draft');
                                    $statusText = $isUpcoming ? 'Upcoming' : ($event['is_published'] ?? false ? 'Published' : 'Draft');
                                ?>
                                <tr>
                                    <td>
                                        <?php 
                                        // Determine the correct link based on database structure
                                        if ($eventsTableExists) {
                                            $eventLink = BASE_URL . '/admin/events/' . $event['id'];
                                        } else {
                                            $eventLink = BASE_URL . '/admin/news/' . $event['id'];
                                        }
                                        ?>
                                        <a href="<?php echo $eventLink; ?>" 
                                           style="color: var(--admin-primary); text-decoration: none; font-weight: 500;">
                                            <?php echo htmlspecialchars(mb_strlen($event['title']) > 30 ? substr($event['title'], 0, 30) . '...' : $event['title']); ?>
                                        </a>
                                        <?php if ($event['is_featured'] ?? false): ?>
                                        <span class="featured-badge">Featured</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($event['event_date'])): ?>
                                        <div class="event-date">
                                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            </svg>
                                            <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($event['event_location'] ?? 'TBA'); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo $statusText; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📅</div>
                            <div class="empty-state-title">No recent events</div>
                            <div class="empty-state-description">Events will appear here when created.</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- Nominal Roll User Dashboard Content -->
            <div class="content-grid">
                <!-- Welcome Message for Nominal Roll Users -->
                <div class="content-card" style="grid-column: 1 / -1;">
                    <div class="card-header">
                        <h3>Welcome, <?php echo htmlspecialchars($username); ?>!</h3>
                    </div>
                    <div class="card-body">
                        <div style="text-align: center; padding: 2rem;">
                            <div style="font-size: 3rem; color: var(--admin-purple); margin-bottom: 1rem;">👥</div>
                            <h2 style="margin-bottom: 1rem; color: var(--admin-gray-800);">Nominal Roll Management</h2>
                            <p style="color: var(--admin-gray-600); max-width: 600px; margin: 0 auto 2rem; line-height: 1.6;">
                                You have access to manage student records in the Nominal Roll system. 
                                Click the button below or use the sidebar navigation to get started.
                            </p>
                            <a href="<?php echo BASE_URL; ?>/admin/nominal-roll" 
                               class="action-btn" style="max-width: 300px; margin: 0 auto; text-align: left;">
                                <div class="action-icon" style="background: rgba(159, 122, 234, 0.1); color: var(--admin-purple);">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Go to Nominal Roll</h4>
                                    <p>Manage student records</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions for Nominal Roll -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions" style="margin-top: 0;">
                            <a href="<?php echo BASE_URL; ?>/admin/nominal-roll/create" class="action-btn">
                                <div class="action-icon" style="background: rgba(56, 161, 105, 0.1); color: var(--admin-success);">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Add New Record</h4>
                                    <p>Create new student record</p>
                                </div>
                            </a>
                            
                            <a href="<?php echo BASE_URL; ?>/admin/nominal-roll/export" class="action-btn">
                                <div class="action-icon" style="background: rgba(66, 153, 225, 0.1); color: var(--admin-primary);">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Export Data</h4>
                                    <p>Export records to Excel/CSV</p>
                                </div>
                            </a>
                            
                            <a href="<?php echo BASE_URL; ?>/admin/nominal-roll/bulk-upload" class="action-btn">
                                <div class="action-icon" style="background: rgba(214, 158, 46, 0.1); color: var(--admin-warning);">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Bulk Upload</h4>
                                    <p>Upload multiple records at once</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Nominal Roll Activity -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Recent Nominal Roll Activity</h3>
                    </div>
                    <div class="card-body">
                        <?php 
                        // Get recent nominal roll activities
                        try {
                            $stmt = $conn->prepare("
                                SELECT al.*, u.username as user_name 
                                FROM activity_logs al 
                                LEFT JOIN users u ON al.user_id = u.id 
                                WHERE al.action LIKE '%nominal_roll%' 
                                ORDER BY al.created_at DESC 
                                LIMIT 5
                            ");
                            $stmt->execute();
                            $nominalActivities = $stmt->fetchAll();
                        } catch (Exception $e) {
                            $nominalActivities = [];
                        }
                        ?>
                        
                        <?php if (!empty($nominalActivities)): ?>
                        <ul class="activity-list">
                            <?php foreach ($nominalActivities as $activity): ?>
                            <li class="activity-item">
                                <div class="activity-icon" style="background: rgba(159, 122, 234, 0.1); color: var(--admin-purple);">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                </div>
                                <div class="activity-info">
                                    <h4><?php echo htmlspecialchars(str_replace('_', ' ', $activity['action'])); ?></h4>
                                    <p>
                                        <?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?>
                                        <?php if (!empty($activity['user_name'])): ?>
                                        <span>• By: <?php echo htmlspecialchars($activity['user_name']); ?></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📝</div>
                            <div class="empty-state-title">No recent activities</div>
                            <div class="empty-state-description">Activities will appear here as you manage nominal roll records.</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <!-- NEWS MANAGER QUICK ACTION -->
                <?php 
                $showNewsManager = in_array($userRole, ['admin', 'editor', 'news_manager']) || 
                                  (isset($_SESSION['user_roles']) && in_array('news_manager', $_SESSION['user_roles']));
                if ($showNewsManager): 
                ?>
                <a href="<?php echo BASE_URL; ?>/admin/news-manager" class="action-btn">
                    <div class="action-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--admin-news);">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                            <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                        </svg>
                    </div>
                    <div>
                        <h4>News & Events Manager</h4>
                        <p>Manage news articles and events</p>
                    </div>
                </a>
                <?php endif; ?>
                
                <!-- Application Quick Actions -->
                <?php if ($userRole !== 'nominal_roll_user'): ?>
                <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                <a href="<?php echo BASE_URL; ?>/admin/applications" class="action-btn">
                    <div class="action-icon" style="background: rgba(56, 161, 105, 0.1); color: var(--admin-success);">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Manage Applications</h4>
                        <p>View and process applications</p>
                    </div>
                </a>
                
                <a href="<?php echo BASE_URL; ?>/admin/applications/settings" class="action-btn">
                    <div class="action-icon" style="background: rgba(214, 158, 46, 0.1); color: #d69e2e;">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Application Settings</h4>
                        <p>Configure fees, dates, requirements</p>
                    </div>
                </a>
                
                <a href="<?php echo BASE_URL; ?>/admin/applications/jamb-import" class="action-btn">
                    <div class="action-icon" style="background: rgba(56, 161, 105, 0.1); color: #38a169;">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Import JAMB Data</h4>
                        <p>Upload JAMB candidate records</p>
                    </div>
                </a>
                
                <a href="<?php echo BASE_URL; ?>/admin/applications/payments" class="action-btn">
                    <div class="action-icon" style="background: rgba(159, 122, 234, 0.1); color: #9f7aea;">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>View Payments</h4>
                        <p>Monitor payment transactions</p>
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
                        <p>Create system report</p>
                    </div>
                </a>
                
                <!-- Contact Management Action -->
                <a href="<?php echo BASE_URL; ?>/admin/contact" class="action-btn">
                    <div class="action-icon" style="background: rgba(49, 130, 206, 0.1); color: var(--admin-info);">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Manage Contacts</h4>
                        <p>View and respond to messages</p>
                    </div>
                </a>
                <?php endif; ?>
                
                <!-- Always show Nominal Roll quick action -->
                <a href="<?php echo BASE_URL; ?>/admin/nominal-roll" class="action-btn">
                    <div class="action-icon" style="background: rgba(159, 122, 234, 0.1); color: var(--admin-purple);">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Nominal Roll</h4>
                        <p>Manage student records</p>
                    </div>
                </a>
                
                <?php if ($userRole === 'nominal_roll_user'): ?>
                <!-- Additional quick actions for nominal_roll_user -->
                <a href="<?php echo BASE_URL; ?>/admin/nominal-roll/create" class="action-btn">
                    <div class="action-icon" style="background: rgba(56, 161, 105, 0.1); color: var(--admin-success);">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Add Student</h4>
                        <p>Create new student record</p>
                    </div>
                </a>
                
                <a href="<?php echo BASE_URL; ?>/admin/nominal-roll/export" class="action-btn">
                    <div class="action-icon" style="background: rgba(66, 153, 225, 0.1); color: var(--admin-primary);">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Export Data</h4>
                        <p>Export to Excel/CSV</p>
                    </div>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <script>
        // Enhanced Mobile menu toggle with improved touch handling
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
        
        // Handle orientation change
        window.addEventListener('orientationchange', function() {
            setTimeout(function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }, 300);
        });
        
        // Auto-refresh dashboard stats every 5 minutes
        let refreshInterval = setTimeout(function() {
            location.reload();
        }, 5 * 60 * 1000);
        
        // Reset refresh timer on user activity
        document.addEventListener('mousemove', resetRefreshTimer);
        document.addEventListener('keypress', resetRefreshTimer);
        document.addEventListener('click', resetRefreshTimer);
        document.addEventListener('touchstart', resetRefreshTimer);
        
        function resetRefreshTimer() {
            clearTimeout(refreshInterval);
            refreshInterval = setTimeout(function() {
                location.reload();
            }, 5 * 60 * 1000);
        }
        
        // Handle User Management link click
        function handleUserManagementClick(event) {
            // Prevent any default handling that might be interfering
            event.preventDefault();
            event.stopPropagation();
            
            // Get the href from the link
            const href = event.currentTarget.getAttribute('href');
            
            // Navigate to the URL
            if (href) {
                window.location.href = href;
            }
            
            return false;
        }
        
        // Fixed Notification System
        let notificationCount = 0;
        
        function checkNotifications() {
            const pendingApplications = <?php echo $stats['pending_applications']; ?>;
            const pendingContacts = <?php echo $stats['pending_contacts'] ?? 0; ?>;
            
            notificationCount = pendingApplications + pendingContacts;
            
            const notificationBtn = document.getElementById('notificationBtn');
            if (notificationCount > 0) {
                const existingBadge = notificationBtn.querySelector('.notification-badge');
                if (existingBadge) existingBadge.remove();
                
                const badge = document.createElement('span');
                badge.className = 'notification-badge';
                badge.textContent = notificationCount > 99 ? '99+' : notificationCount;
                notificationBtn.appendChild(badge);
                
                notificationBtn.onclick = showNotifications;
            } else {
                notificationBtn.onclick = showNoNotifications;
            }
        }
        
        function showNotifications() {
            const pendingApplications = <?php echo $stats['pending_applications']; ?>;
            const pendingContacts = <?php echo $stats['pending_contacts'] ?? 0; ?>;
            
            const notificationHtml = `
                <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000; display: flex; align-items: center; justify-content: center;">
                    <div style="background: white; border-radius: 12px; padding: 1.5rem; max-width: 400px; width: 90%; max-height: 80vh; overflow-y: auto;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h3 style="margin: 0; color: var(--admin-gray-800);">Notifications</h3>
                            <button onclick="closeNotifications()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--admin-gray-600);">&times;</button>
                        </div>
                        <div style="display: grid; gap: 0.75rem;">
                            ${pendingApplications > 0 ? `
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: rgba(214, 158, 46, 0.1); border-radius: 8px;">
                                <div style="width: 32px; height: 32px; background: var(--admin-warning); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">${pendingApplications}</div>
                                <div>
                                    <strong style="color: var(--admin-gray-800);">Pending Applications</strong>
                                    <div style="font-size: 0.875rem; color: var(--admin-gray-600);">${pendingApplications} application${pendingApplications > 1 ? 's' : ''} need review</div>
                                </div>
                            </div>
                            ` : ''}
                            
                            ${pendingContacts > 0 ? `
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: rgba(49, 130, 206, 0.1); border-radius: 8px;">
                                <div style="width: 32px; height: 32px; background: var(--admin-info); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">${pendingContacts}</div>
                                <div>
                                    <strong style="color: var(--admin-gray-800);">Pending Contact Messages</strong>
                                    <div style="font-size: 0.875rem; color: var(--admin-gray-600);">${pendingContacts} message${pendingContacts > 1 ? 's' : ''} awaiting response</div>
                                </div>
                            </div>
                            ` : ''}
                            
                            ${notificationCount === 0 ? `
                            <div style="text-align: center; padding: 2rem; color: var(--admin-gray-500);">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔔</div>
                                <div>No new notifications</div>
                            </div>
                            ` : ''}
                        </div>
                        ${notificationCount > 0 ? `
                        <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                            <a href="<?php echo BASE_URL; ?>/admin/applications" style="flex: 1; padding: 0.5rem; background: var(--admin-primary); color: white; text-align: center; border-radius: 6px; text-decoration: none; font-weight: 500;">View Applications</a>
                            ${pendingContacts > 0 ? `<a href="<?php echo BASE_URL; ?>/admin/contact" style="flex: 1; padding: 0.5rem; background: var(--admin-info); color: white; text-align: center; border-radius: 6px; text-decoration: none; font-weight: 500;">View Messages</a>` : ''}
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            
            const notificationDiv = document.createElement('div');
            notificationDiv.innerHTML = notificationHtml;
            document.body.appendChild(notificationDiv);
        }
        
        function showNoNotifications() {
            const notificationHtml = `
                <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000; display: flex; align-items: center; justify-content: center;">
                    <div style="background: white; border-radius: 12px; padding: 2rem; text-align: center; max-width: 300px; width: 90%;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🔔</div>
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--admin-gray-800);">No Notifications</h3>
                        <p style="color: var(--admin-gray-600); margin-bottom: 1.5rem;">You're all caught up!</p>
                        <button onclick="closeNotifications()" style="padding: 0.5rem 1.5rem; background: var(--admin-primary); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">Close</button>
                    </div>
                </div>
            `;
            
            const notificationDiv = document.createElement('div');
            notificationDiv.innerHTML = notificationHtml;
            document.body.appendChild(notificationDiv);
        }
        
        function closeNotifications() {
            const notificationModal = document.querySelector('div[style*="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5)"]');
            if (notificationModal) {
                notificationModal.remove();
            }
        }
        
        // Check notifications on page load
        document.addEventListener('DOMContentLoaded', checkNotifications);
        
        // Auto logout warning after 30 minutes of inactivity
        let idleTime = 0;
        let idleInterval;
        let warningShown = false;
        
        function startIdleTimer() {
            idleInterval = setInterval(() => {
                idleTime++;
                if (idleTime > 29 && !warningShown) {
                    showLogoutWarning();
                    warningShown = true;
                }
            }, 60000);
        }
        
        function resetIdleTime() {
            idleTime = 0;
            if (warningShown) {
                const warning = document.getElementById('logout-warning');
                const overlay = document.querySelector('#logout-warning + div');
                if (warning) warning.remove();
                if (overlay) overlay.remove();
                warningShown = false;
            }
        }
        
        // Reset idle time on user activity
        document.addEventListener('mousemove', resetIdleTime);
        document.addEventListener('keypress', resetIdleTime);
        document.addEventListener('click', resetIdleTime);
        document.addEventListener('touchstart', resetIdleTime);
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
                        Your session will expire in 1 minute due to inactivity.
                    </p>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <button onclick="extendSession()" style="padding: 0.5rem 1.5rem; background: var(--admin-primary); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                            Stay Logged In
                        </button>
                        <button onclick="logoutNow()" style="padding: 0.5rem 1.5rem; background: var(--admin-gray-200); color: var(--admin-gray-700); border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
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
                
                // Auto logout after 1 more minute
                setTimeout(() => {
                    if (warningShown) {
                        logoutNow();
                    }
                }, 60000);
            }
        }
        
        function extendSession() {
            fetch('<?php echo BASE_URL; ?>/admin/api/session/extend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(() => {
                resetIdleTime();
                const warning = document.getElementById('logout-warning');
                const overlay = document.querySelector('#logout-warning + div');
                if (warning) warning.remove();
                if (overlay) overlay.remove();
                warningShown = false;
            })
            .catch(error => {
                console.error('Session extend error:', error);
                // Even if API fails, reset the timer locally
                resetIdleTime();
                warningShown = false;
            });
        }
        
        function logoutNow() {
            window.location.href = '<?php echo BASE_URL; ?>/admin/logout';
        }
        
        // Start idle timer
        startIdleTimer();
        
        // Print functionality
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
        
        // Performance optimization - lazy load images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => imageObserver.observe(img));
        });
        
        // Handle offline/online status
        window.addEventListener('online', function() {
            showToast('You are back online', 'success');
        });
        
        window.addEventListener('offline', function() {
            showToast('You are offline. Some features may not work.', 'warning');
        });
        
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--admin-success)' : 'var(--admin-warning)'};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: var(--shadow-lg);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>