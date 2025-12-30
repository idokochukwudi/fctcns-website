<?php
/**
 * Professional Header Template - International University Standard
 * 
 * Inspired by Stanford, MIT, Johns Hopkins, Oxford & Cambridge
 * 
 * @package FCT_CNS
 */

// Get data passed from controller
$pageTitle = $pageTitle ?? 'FCT College of Nursing Sciences';
$pageDescription = $pageDescription ?? 'Empowering Future Healthcare Professionals Since 1989';
$pageKeywords = $pageKeywords ?? 'nursing college, FCT, nursing education, healthcare professionals';
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '');

// Get current page for active navigation
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);
$basePath = '/fctcns-website';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}
$pathParts = explode('/', trim($path, '/'));
$currentPage = !empty($pathParts[0]) ? $pathParts[0] : 'home';
$currentPage = strtolower(trim($currentPage));

// Check if user is logged in
$isLoggedIn = false;
$userRole = '';
$username = '';

if (class_exists('Session')) {
    $isLoggedIn = Session::isAuthenticated();
    if ($isLoggedIn) {
        $userRole = Session::getUserRole();
        $username = Session::getUsername();
    }
} else {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
        $isLoggedIn = true;
        $userRole = $_SESSION['user_role'];
        $username = $_SESSION['username'] ?? '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Professional Fonts - Sans Serif Only -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Source+Sans+3:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>/assets/images/logo/favicon.ico">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php 
        if (class_exists('Session')) {
            echo Session::getCSRFToken();
        } elseif (isset($_SESSION['csrf_token'])) {
            echo $_SESSION['csrf_token'];
        }
    ?>">
    
    <style>
        /* CRITICAL FIX: Reset all margins and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Professional University Design System */
        :root {
            --color-primary: #6B4E9B;
            --color-primary-dark: #5a4185;
            --color-primary-light: #8B6CB5;
            --color-secondary: #7FB285;
            --color-white: #FFFFFF;
            --color-gray-50: #fafafa;
            --color-gray-100: #f5f5f5;
            --color-gray-200: #e5e5e5;
            --color-gray-300: #d4d4d4;
            --color-gray-400: #a3a3a3;
            --color-gray-500: #737373;
            --color-gray-600: #525252;
            --color-gray-700: #404040;
            --color-gray-800: #262626;
            --color-gray-900: #171717;
            
            --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-heading: 'Source Sans 3', 'Inter', sans-serif;
            
            --top-bar-height: 36px;
            --main-header-height: 70px;
            --nav-bar-height: 44px;
            --total-header-height: calc(var(--top-bar-height) + var(--main-header-height) + var(--nav-bar-height));
            
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            
            --transition: 0.2s ease;
        }
        
        body {
            font-family: var(--font-primary);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.6;
            color: var(--color-gray-800);
            background-color: var(--color-white);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
        
        /* Flash Messages */
        .flash-messages {
            position: fixed;
            top: var(--total-header-height);
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: calc(100% - 2rem);
            max-width: 600px;
            pointer-events: none;
        }
        
        .flash-message {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            animation: slideDown 0.3s ease;
            pointer-events: auto;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 4px solid;
        }
        
        .flash-success {
            background: #d1fae5;
            color: #065f46;
            border-color: #10b981;
        }
        
        .flash-error {
            background: #fee2e2;
            color: #991b1b;
            border-color: #ef4444;
        }
        
        .flash-warning {
            background: #fef3c7;
            color: #92400e;
            border-color: #f59e0b;
        }
        
        .flash-info {
            background: #dbeafe;
            color: #1e40af;
            border-color: #3b82f6;
        }
        
        .flash-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 1.25rem;
            opacity: 0.7;
            padding: 0;
            margin-left: 1rem;
        }
        
        .flash-close:hover {
            opacity: 1;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-1rem);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Professional Header */
        .site-header {
            background: #ffffff;
            border-bottom: 1px solid var(--color-gray-200);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            height: var(--total-header-height);
        }
        
        /* Top Bar */
        .top-bar {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            font-size: 0.813rem;
            height: var(--top-bar-height);
            display: flex;
            align-items: center;
            line-height: 1;
        }
        
        .top-bar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        
        .top-bar-left {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        
        .top-bar-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.95);
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.813rem;
            line-height: 1;
        }
        
        .top-bar-item:hover {
            color: white;
        }
        
        .top-bar-right {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .top-bar-link {
            color: rgba(255, 255, 255, 0.95);
            text-decoration: none;
            transition: var(--transition);
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.813rem;
            line-height: 1;
        }
        
        .top-bar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        /* Main Header */
        .main-header {
            height: var(--main-header-height);
            background: white;
            display: flex;
            align-items: center;
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        
        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .brand:hover {
            opacity: 0.9;
        }
        
        .brand-logo {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
        }
        
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }
        
        .brand-name {
            font-family: var(--font-heading);
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--color-gray-900);
            line-height: 1.2;
            letter-spacing: -0.01em;
        }
        
        .brand-tagline {
            font-size: 0.75rem;
            color: var(--color-gray-600);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Header Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        /* Search */
        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .search-input {
            width: 240px;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1px solid var(--color-gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            font-family: var(--font-primary);
            transition: var(--transition);
            background: var(--color-gray-50);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--color-primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(107, 78, 155, 0.1);
        }
        
        .search-input::placeholder {
            color: var(--color-gray-500);
        }
        
        .search-icon {
            position: absolute;
            left: 0.875rem;
            color: var(--color-gray-500);
            font-size: 0.875rem;
            pointer-events: none;
        }
        
        /* Portal/User Button */
        .portal-btn,
        .user-btn {
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            white-space: nowrap;
            line-height: 1;
        }
        
        .portal-btn:hover,
        .user-btn:hover {
            background: linear-gradient(135deg, var(--color-primary-dark), #4a3670);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(107, 78, 155, 0.3);
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }
        
        .user-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: white;
            min-width: 220px;
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s ease;
            border: 1px solid var(--color-gray-200);
        }
        
        .user-menu:hover .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .user-header {
            padding: 1rem;
            border-bottom: 1px solid var(--color-gray-200);
            margin-bottom: 0.5rem;
        }
        
        .user-name {
            font-weight: 700;
            color: var(--color-gray-900);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: var(--color-gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .user-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--color-gray-700);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 6px;
        }
        
        .user-item:hover {
            background: var(--color-gray-100);
            color: var(--color-primary);
        }
        
        .user-item i {
            width: 18px;
        }
        
        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: 1px solid var(--color-gray-300);
            width: 40px;
            height: 40px;
            border-radius: 6px;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }
        
        .menu-toggle:hover {
            background: var(--color-gray-100);
            border-color: var(--color-gray-400);
        }
        
        .menu-toggle-icon {
            position: absolute;
            width: 20px;
            height: 2px;
            background: var(--color-gray-700);
            left: 10px;
            top: 19px;
            transition: var(--transition);
        }
        
        .menu-toggle-icon:before,
        .menu-toggle-icon:after {
            content: '';
            position: absolute;
            width: 20px;
            height: 2px;
            background: var(--color-gray-700);
            transition: var(--transition);
        }
        
        .menu-toggle-icon:before {
            top: -6px;
        }
        
        .menu-toggle-icon:after {
            top: 6px;
        }
        
        .menu-toggle.active .menu-toggle-icon {
            background: transparent;
        }
        
        .menu-toggle.active .menu-toggle-icon:before {
            transform: rotate(45deg);
            top: 0;
        }
        
        .menu-toggle.active .menu-toggle-icon:after {
            transform: rotate(-45deg);
            top: 0;
        }
        
        /* Navigation Bar */
        .nav-bar {
            background: var(--color-gray-50);
            border-top: 1px solid var(--color-gray-200);
            height: var(--nav-bar-height);
            display: flex;
            align-items: center;
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            height: 100%;
            display: flex;
            align-items: center;
            width: 100%;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0;
            height: 100%;
        }
        
        .nav-item {
            height: 100%;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0 1.25rem;
            color: var(--color-gray-700);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: var(--transition);
            border-bottom: 2px solid transparent;
            white-space: nowrap;
        }
        
        .nav-link:hover {
            color: var(--color-primary);
            background: rgba(107, 78, 155, 0.05);
        }
        
        .nav-link.active {
            color: var(--color-primary);
            border-bottom-color: var(--color-primary);
            font-weight: 600;
        }
        
        /* Mobile Navigation */
        .mobile-nav {
            display: none;
            position: fixed;
            top: var(--total-header-height);
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            z-index: 999;
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-nav.active {
            transform: translateX(0);
        }
        
        .mobile-nav-menu {
            list-style: none;
            padding: 1rem;
        }
        
        .mobile-nav-item {
            border-bottom: 1px solid var(--color-gray-200);
        }
        
        .mobile-nav-link {
            display: block;
            padding: 1rem;
            color: var(--color-gray-700);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            color: var(--color-primary);
            background: rgba(107, 78, 155, 0.05);
        }
        
        /* Main Content - FIXED */
        .main-content {
            flex: 1;
            margin-top: var(--total-header-height) !important;
            padding-top: 0 !important;
            width: 100%;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .header-container,
            .nav-container,
            .top-bar-container {
                padding: 0 1.5rem;
            }
            
            .search-input {
                width: 200px;
            }
        }
        
        @media (max-width: 768px) {
            .top-bar {
                font-size: 0.75rem;
                height: 32px;
            }
            
            .top-bar-left,
            .top-bar-right {
                gap: 1rem;
            }
            
            .top-bar-item span:not(.fa, .fas, .far) {
                display: none;
            }
            
            .main-header {
                height: 60px;
            }
            
            .header-container {
                padding: 0 1rem;
            }
            
            .brand-logo {
                width: 42px;
                height: 42px;
            }
            
            .brand-name {
                font-size: 1rem;
            }
            
            .brand-tagline {
                font-size: 0.7rem;
            }
            
            .search-box {
                display: none;
            }
            
            .nav-bar {
                display: none;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .mobile-nav {
                display: block;
                top: calc(32px + 60px);
            }
            
            .main-content {
                margin-top: calc(32px + 60px) !important;
            }
            
            .flash-messages {
                top: calc(32px + 60px);
            }
            
            :root {
                --top-bar-height: 32px;
                --main-header-height: 60px;
                --nav-bar-height: 0px;
                --total-header-height: calc(var(--top-bar-height) + var(--main-header-height));
            }
        }
        
        @media (max-width: 480px) {
            .brand-name {
                font-size: 0.875rem;
            }
            
            .portal-btn,
            .user-btn {
                padding: 0.5rem 1rem;
                font-size: 0.813rem;
            }
            
            .header-actions {
                gap: 0.75rem;
            }
            
            .top-bar {
                height: 28px;
                font-size: 0.7rem;
            }
            
            :root {
                --top-bar-height: 28px;
            }
        }
    </style>
</head>
<body>
<!-- Flash Messages -->
<div class="flash-messages">
    <?php
    if (class_exists('Session')) {
        $flashMessages = Session::getAllFlash();
        foreach ($flashMessages as $type => $message) {
            echo '<div class="flash-message flash-' . htmlspecialchars($type) . '">';
            echo '<span>' . htmlspecialchars($message) . '</span>';
            echo '<button class="flash-close" aria-label="Close">&times;</button>';
            echo '</div>';
        }
    } elseif (isset($_SESSION['flash']) && is_array($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $type => $message) {
            echo '<div class="flash-message flash-' . htmlspecialchars($type) . '">';
            echo '<span>' . htmlspecialchars($message) . '</span>';
            echo '<button class="flash-close" aria-label="Close">&times;</button>';
            echo '</div>';
        }
        unset($_SESSION['flash']);
    }
    ?>
</div>

<!-- Site Header -->
<header class="site-header">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-container">
            <div class="top-bar-left">
                <a href="tel:+23492900000" class="top-bar-item">
                    <i class="fas fa-phone"></i>
                    <span>+234 (0) 9 290 0000</span>
                </a>
                <a href="mailto:info@fctcns.edu.ng" class="top-bar-item">
                    <i class="fas fa-envelope"></i>
                    <span>info@fctcns.edu.ng</span>
                </a>
            </div>
            <div class="top-bar-right">
                <a href="<?php echo $baseUrl; ?>/apply" class="top-bar-link">Apply Now</a>
                <a href="<?php echo $baseUrl; ?>/portal" class="top-bar-link">Student Portal</a>
            </div>
        </div>
    </div>
    
    <!-- Main Header -->
    <div class="main-header">
        <div class="header-container">
            <a href="<?php echo $baseUrl; ?>/" class="brand">
                <div class="brand-logo">
                    <img src="<?php echo $baseUrl; ?>/assets/images/logo/logo.png" 
                         alt="FCT College of Nursing Sciences">
                </div>
                <div class="brand-text">
                    <div class="brand-name">FCT College of Nursing Sciences</div>
                    <div class="brand-tagline">Abuja, Nigeria</div>
                </div>
            </a>
            
            <div class="header-actions">
                <div class="search-box">
                    <input type="text" 
                           class="search-input" 
                           placeholder="Search..."
                           aria-label="Search">
                    <i class="fas fa-search search-icon"></i>
                </div>
                
                <?php if ($isLoggedIn): ?>
                <div class="user-menu">
                    <button class="user-btn" aria-haspopup="true" aria-expanded="false">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($username, 0, 2)); ?>
                        </div>
                        <span><?php echo htmlspecialchars($username); ?></span>
                    </button>
                    <div class="user-dropdown">
                        <div class="user-header">
                            <div class="user-name"><?php echo htmlspecialchars($username); ?></div>
                            <div class="user-role"><?php echo ucfirst($userRole); ?></div>
                        </div>
                        <a href="<?php echo $baseUrl; ?>/admin/dashboard" class="user-item">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                        <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                        <a href="<?php echo $baseUrl; ?>/admin/applications" class="user-item">
                            <i class="fas fa-file-alt"></i>
                            <span>Applications</span>
                        </a>
                        <?php endif; ?>
                        <?php if ($userRole === 'admin'): ?>
                        <a href="<?php echo $baseUrl; ?>/admin/users" class="user-item">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo $baseUrl; ?>/admin/logout" class="user-item" style="color: #ef4444;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?php echo $baseUrl; ?>/admin" class="portal-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Portal Login</span>
                </a>
                <?php endif; ?>
                
                <button class="menu-toggle" aria-label="Toggle menu" onclick="toggleMobileMenu(this)">
                    <span class="menu-toggle-icon"></span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Navigation Bar -->
    <nav class="nav-bar">
        <div class="nav-container">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?php echo $baseUrl; ?>/" 
                       class="nav-link <?php echo ($currentPage == 'home' || $currentPage == '') ? 'active' : ''; ?>">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $baseUrl; ?>/about" 
                       class="nav-link <?php echo $currentPage == 'about' ? 'active' : ''; ?>">
                        About
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $baseUrl; ?>/programs" 
                       class="nav-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>">
                        Academic Programs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $baseUrl; ?>/admissions" 
                       class="nav-link <?php echo $currentPage == 'admissions' ? 'active' : ''; ?>">
                        Admissions
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $baseUrl; ?>/research" 
                       class="nav-link <?php echo $currentPage == 'research' ? 'active' : ''; ?>">
                        Research
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $baseUrl; ?>/student-life" 
                       class="nav-link <?php echo $currentPage == 'student-life' ? 'active' : ''; ?>">
                        Student Life
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $baseUrl; ?>/faculty" 
                       class="nav-link <?php echo $currentPage == 'faculty' ? 'active' : ''; ?>">
                        Faculty & Staff
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $baseUrl; ?>/news" 
                       class="nav-link <?php echo $currentPage == 'news' ? 'active' : ''; ?>">
                        News & Events
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $baseUrl; ?>/contact" 
                       class="nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
                        Contact
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- Mobile Navigation -->
<div class="mobile-nav" id="mobileNav">
    <ul class="mobile-nav-menu">
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/" 
               class="mobile-nav-link <?php echo ($currentPage == 'home' || $currentPage == '') ? 'active' : ''; ?>">
                Home
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/about" 
               class="mobile-nav-link <?php echo $currentPage == 'about' ? 'active' : ''; ?>">
                About
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/programs" 
               class="mobile-nav-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>">
                Academic Programs
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/admissions" 
               class="mobile-nav-link <?php echo $currentPage == 'admissions' ? 'active' : ''; ?>">
                Admissions
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/research" 
               class="mobile-nav-link <?php echo $currentPage == 'research' ? 'active' : ''; ?>">
                Research
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/student-life" 
               class="mobile-nav-link <?php echo $currentPage == 'student-life' ? 'active' : ''; ?>">
                Student Life
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/faculty" 
               class="mobile-nav-link <?php echo $currentPage == 'faculty' ? 'active' : ''; ?>">
                Faculty & Staff
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/news" 
               class="mobile-nav-link <?php echo $currentPage == 'news' ? 'active' : ''; ?>">
                News & Events
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/alumni" 
               class="mobile-nav-link <?php echo $currentPage == 'alumni' ? 'active' : ''; ?>">
                Alumni
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/contact" 
               class="mobile-nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
                Contact
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<main class="main-content">

<script>
// Mobile menu toggle
function toggleMobileMenu(button) {
    const mobileNav = document.getElementById('mobileNav');
    button.classList.toggle('active');
    mobileNav.classList.toggle('active');
    
    // Prevent body scroll when menu is open
    if (mobileNav.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Close flash messages
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.flash-close').forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });
    
    // Auto-hide flash messages after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.flash-message').forEach(message => {
            message.style.opacity = '0';
            setTimeout(() => message.style.display = 'none', 300);
        });
    }, 5000);
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const toggle = document.querySelector('.menu-toggle');
        const mobileNav = document.getElementById('mobileNav');
        
        if (mobileNav.classList.contains('active') && 
            !toggle.contains(event.target) && 
            !mobileNav.contains(event.target)) {
            toggle.classList.remove('active');
            mobileNav.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Search functionality
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = '<?php echo $baseUrl; ?>/search?q=' + encodeURIComponent(query);
                }
            }
        });
    }
});
</script>