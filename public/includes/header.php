<?php
/**
 * University Header Template - Professional Hybrid Design
 * Desktop: Show navigation tabs
 * Mobile: Collapse into menu with "MENU" label
 * FULL WIDTH VERSION - Fixed padding issues
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
    
    <!-- Google Fonts - Professional Selection -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
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
        /* ==============================================
           PROFESSIONAL HEADER REDESIGN - CLEAN & STRUCTURED
           FULL WIDTH VERSION - FIXED PADDING
           ============================================== */
        
        :root {
            /* Professional Color Palette */
            --color-primary: #0a2c5e; /* Deep professional blue */
            --color-primary-dark: #071d42;
            --color-primary-light: #1c3d7a;
            --color-secondary: #2c5282;
            --color-accent: #c19a0e; /* Rich gold */
            --color-accent-light: #d4b031;
            --color-accent-dark: #a07c0a;
            --color-contact: #b91c1c; /* Professional red */
            --color-contact-light: #dc2626;
            --color-white: #ffffff;
            --color-off-white: #f8f9fa;
            --color-gray-50: #f9fafb;
            --color-gray-100: #f3f4f6;
            --color-gray-200: #e5e7eb;
            --color-gray-300: #d1d5db;
            --color-gray-400: #9ca3af;
            --color-gray-500: #6b7280;
            --color-gray-600: #4b5563;
            --color-gray-700: #374151;
            --color-gray-800: #1f2937;
            --color-gray-900: #111827;
            
            /* Professional Fonts */
            --font-display: 'Playfair Display', serif; /* Elegant serif for college name */
            --font-heading: 'Montserrat', sans-serif;
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            
            /* Spacing */
            --header-height: 85px;
            --nav-height: 55px;
            --container-padding: 2rem;
            
            /* Shadows */
            --shadow-subtle: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-elevated: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            
            /* Transitions */
            --transition-fast: 0.15s ease;
            --transition-smooth: 0.25s ease;
        }
        
        /* ==========================================================================
           CRITICAL: FULL WIDTH BASE RESET - ADD THIS FIRST
           ========================================================================== */
        html {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        body {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden;
            position: relative;
        }

        /* Remove all constraints from direct body children */
        body > * {
            max-width: 100%;
        }

        /* Box sizing for all elements */
        *, *::before, *::after {
            box-sizing: border-box;
        }
        
        /* ==============================================
           RESET AND BASE STYLES
           ============================================== */
        * {
            margin: 0;
            padding: 0;
        }
        
        html, body {
            height: 100%;
        }
        
        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }
        
        body {
            font-family: var(--font-body);
            color: var(--color-gray-800);
            background: var(--color-white);
            line-height: 1.5;
            display: flex;
            flex-direction: column;
            padding-top: 0;
            margin-top: 0;
        }
        
        /* ==============================================
           HEADER STYLES - Clean & Professional - FULL WIDTH
           ============================================== */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: var(--color-white);
            border-bottom: 1px solid var(--color-gray-200);
            box-shadow: var(--shadow-subtle);
            height: var(--header-height);
            width: 100vw;
            max-width: 100vw;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 2rem;
            margin: 0;
            width: 100%;
            max-width: 100vw;
            box-sizing: border-box;
        }
        
        /* ==============================================
           BRAND STYLES - Elegant & Clear
           ============================================== */
        .brand {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            text-decoration: none;
            color: inherit;
            min-width: 0;
        }
        
        .brand-logo {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            overflow: hidden;
            background: var(--color-primary);
            border: 1px solid var(--color-gray-200);
        }
        
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
            display: block;
        }
        
        .brand-logo .logo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--color-primary);
            color: var(--color-white);
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1rem;
            border-radius: 8px;
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        
        /* ELEGANT FONT FOR COLLEGE NAME - Playfair Display */
        .brand-name {
            font-family: var(--font-display);
            line-height: 1.1;
            display: flex;
            flex-direction: column;
        }
        
        /* FIXED: "FCT College of" - Clear and visible */
        .brand-line-1 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--color-primary);
            letter-spacing: 1px;
            text-transform: uppercase;
            opacity: 1;
            font-style: normal;
            margin-bottom: 2px;
            font-family: var(--font-heading);
        }
        
        /* FIXED: "Nursing Sciences" - Better font and color */
        .brand-line-2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--color-primary-dark);
            letter-spacing: -0.3px;
            font-style: normal;
            position: relative;
            font-family: var(--font-display);
        }
        
        .brand-line-2:after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--color-accent);
            border-radius: 1px;
        }
        
        /* ==============================================
           HEADER ACTIONS - Clean & Functional
           ============================================== */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        /* Search Button */
        .search-btn {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            border: 1px solid var(--color-gray-300);
            background: var(--color-white);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition-fast);
            color: var(--color-gray-600);
            font-size: 1rem;
        }
        
        .search-btn:hover {
            background: var(--color-primary);
            color: var(--color-white);
            border-color: var(--color-primary);
        }
        
        /* Apply Button - Fixed font visibility */
        .apply-btn {
            padding: 0.6rem 1.5rem;
            background: var(--color-accent);
            color: var(--color-white);
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all var(--transition-fast);
            white-space: nowrap;
            font-family: var(--font-heading);
        }
        
        .apply-btn:hover {
            background: var(--color-accent-dark);
            color: var(--color-white);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(193, 154, 14, 0.2);
        }
        
        /* User Button */
        .user-btn {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: var(--color-primary);
            color: var(--color-white);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition-fast);
        }
        
        .user-btn:hover {
            background: var(--color-primary-dark);
        }
        
        /* ==============================================
           MOBILE MENU TOGGLE - Simple & Clear
           ============================================== */
        .mobile-menu-toggle-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            cursor: pointer;
        }
        
        .mobile-menu-toggle {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            border: 1px solid var(--color-gray-300);
            background: var(--color-white);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            cursor: pointer;
            transition: all var(--transition-fast);
        }
        
        .mobile-menu-toggle:hover {
            border-color: var(--color-primary);
            background: var(--color-gray-50);
        }
        
        .mobile-menu-toggle span {
            display: block;
            width: 20px;
            height: 2px;
            background: var(--color-gray-700);
            transition: all var(--transition-smooth);
            border-radius: 1px;
        }
        
        .mobile-menu-toggle.active {
            background: var(--color-primary);
            border-color: var(--color-primary);
        }
        
        .mobile-menu-toggle.active span {
            background: var(--color-white);
        }
        
        .mobile-menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        
        .mobile-menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }
        
        /* MENU Label */
        .menu-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--color-gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* ==============================================
           DESKTOP NAVIGATION - Clean Tabs - FULL WIDTH
           ============================================== */
        .desktop-nav-container {
            position: fixed;
            top: var(--header-height);
            left: 0;
            right: 0;
            z-index: 999;
            background: var(--color-white);
            border-bottom: 1px solid var(--color-gray-200);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            height: var(--nav-height);
            width: 100vw;
            max-width: 100vw;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            box-sizing: border-box;
        }
        
        .desktop-nav {
            display: none;
            height: 100%;
            width: 100%;
        }
        
        .desktop-nav-menu {
            display: flex;
            list-style: none;
            height: 100%;
            margin: 0 auto;
            padding: 0 2rem;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            max-width: 100vw;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            box-sizing: border-box;
        }

        .desktop-nav-menu::-webkit-scrollbar {
            display: none;
        }
        
        .desktop-nav-item {
            position: relative;
            height: 100%;
            flex-shrink: 0;
        }
        
        .desktop-nav-link {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0 1.2rem;
            color: var(--color-gray-700);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all var(--transition-fast);
            border-bottom: 3px solid transparent;
            position: relative;
            font-family: var(--font-heading);
            white-space: nowrap;
        }
        
        .desktop-nav-link:hover {
            color: var(--color-primary);
            background: rgba(10, 44, 94, 0.02);
        }
        
        .desktop-nav-link:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--color-accent);
            transition: width var(--transition-smooth);
        }
        
        .desktop-nav-link:hover:before {
            width: 100%;
        }
        
        .desktop-nav-link.active {
            color: var(--color-primary);
            border-bottom-color: var(--color-accent);
            background: rgba(10, 44, 94, 0.02);
        }
        
        .desktop-nav-link.active:before {
            display: none;
        }
        
        /* SPECIAL CONTACT TAB - Simple design */
        .desktop-nav-item.contact-tab .desktop-nav-link {
            color: var(--color-contact);
            background: rgba(185, 28, 28, 0.05);
            border-left: 1px solid rgba(185, 28, 28, 0.1);
            border-right: 1px solid rgba(185, 28, 28, 0.1);
            font-weight: 600;
        }
        
        .desktop-nav-item.contact-tab .desktop-nav-link:before {
            background: var(--color-contact);
        }
        
        .desktop-nav-item.contact-tab .desktop-nav-link:hover {
            color: var(--color-white);
            background: var(--color-contact);
            border-left-color: transparent;
            border-right-color: transparent;
        }
        
        /* Dropdown menus */
        .has-dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--color-white);
            min-width: 220px;
            border-radius: 0 0 8px 8px;
            box-shadow: var(--shadow-elevated);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all var(--transition-smooth);
            z-index: 100;
            border: 1px solid var(--color-gray-200);
            border-top: none;
        }
        
        .has-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-link {
            display: block;
            padding: 0.875rem 1.5rem;
            color: var(--color-gray-700);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all var(--transition-fast);
            border-left: 3px solid transparent;
        }
        
        .dropdown-link:hover {
            background: rgba(10, 44, 94, 0.05);
            color: var(--color-primary);
            border-left-color: var(--color-accent);
            padding-left: 1.75rem;
        }
        
        /* ==============================================
           MOBILE NAVIGATION OVERLAY
           ============================================== */
        .mobile-nav-overlay {
            position: fixed;
            top: var(--header-height);
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transform: translateX(100%);
            transition: all var(--transition-smooth);
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            padding: 1.5rem 1.5rem;
            width: 100vw;
            max-width: 100vw;
            box-sizing: border-box;
        }
        
        .mobile-nav-overlay.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }
        
        .mobile-nav-content {
            width: 100%;
            margin: 0 auto;
        }
        
        /* Search in Mobile Menu */
        .mobile-search-container {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .mobile-search {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--color-gray-300);
            border-radius: 8px;
            font-size: 1rem;
            font-family: var(--font-body);
            background: var(--color-white);
            transition: all var(--transition-fast);
        }
        
        .mobile-search:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(10, 44, 94, 0.1);
        }
        
        .mobile-search-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-gray-500);
            font-size: 1.1rem;
        }
        
        /* Mobile Navigation Menu */
        .mobile-nav-menu {
            list-style: none;
            margin-bottom: 1.5rem;
        }
        
        .mobile-nav-item {
            border-bottom: 1px solid var(--color-gray-200);
        }
        
        .mobile-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            color: var(--color-gray-700);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: all var(--transition-fast);
        }
        
        .mobile-nav-link:hover {
            color: var(--color-primary);
            padding-left: 0.5rem;
        }
        
        .mobile-nav-link.active {
            color: var(--color-primary);
            font-weight: 600;
        }
        
        /* Special Mobile Contact Tab */
        .mobile-nav-item.contact-tab .mobile-nav-link {
            color: var(--color-contact);
            font-weight: 600;
            background: rgba(185, 28, 28, 0.05);
            padding: 1rem;
            margin: 0.5rem 0;
            border-radius: 8px;
            border: 1px solid rgba(185, 28, 28, 0.1);
        }
        
        .mobile-nav-item.contact-tab .mobile-nav-link:hover {
            color: var(--color-white);
            background: var(--color-contact);
            border-color: transparent;
        }
        
        /* Quick Actions in Mobile Menu */
        .mobile-quick-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .mobile-action-btn {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            background: var(--color-primary);
            color: var(--color-white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all var(--transition-fast);
            font-weight: 500;
            justify-content: center;
            text-align: center;
        }
        
        .mobile-action-btn:hover {
            background: var(--color-primary-dark);
        }
        
        .mobile-action-btn.accent {
            background: var(--color-accent);
            color: var(--color-white);
        }
        
        .mobile-action-btn.accent:hover {
            background: var(--color-accent-dark);
        }
        
        /* Contact Info in Mobile Menu */
        .mobile-contact-info {
            padding-top: 1.5rem;
            border-top: 1px solid var(--color-gray-200);
        }
        
        .mobile-contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .mobile-contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--color-gray-600);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all var(--transition-fast);
        }
        
        .mobile-contact-item:hover {
            background: var(--color-gray-100);
            color: var(--color-primary);
        }
        
        /* ==============================================
           SEARCH MODAL
           ============================================== */
        .search-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 1100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .search-modal.active {
            display: flex;
        }
        
        .search-modal-content {
            background: var(--color-white);
            width: 100%;
            max-width: 600px;
            border-radius: 12px;
            padding: 2rem;
            position: relative;
            box-shadow: var(--shadow-elevated);
        }
        
        .search-modal-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--color-gray-500);
            cursor: pointer;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all var(--transition-fast);
        }
        
        .search-modal-close:hover {
            background: var(--color-gray-100);
            color: var(--color-gray-700);
        }
        
        /* ==============================================
           FLASH MESSAGES
           ============================================== */
        .flash-messages {
            position: fixed;
            top: var(--header-height);
            left: 0;
            right: 0;
            z-index: 1001;
            padding: 1rem 2rem;
            pointer-events: none;
        }
        
        .flash-message {
            background: var(--color-white);
            border-left: 4px solid var(--color-primary);
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.5rem;
            box-shadow: var(--shadow-medium);
            animation: slideIn 0.3s ease;
            pointer-events: auto;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* ==============================================
           MAIN CONTENT AREA - FIXED PADDING
           ============================================== */
        .main-content-wrapper {
            flex: 1;
            width: 100%;
            max-width: 100vw;
            margin-top: 0;
            padding-top: var(--header-height);
            overflow-x: hidden;
            box-sizing: border-box;
        }

        .desktop-nav-container + .main-content-wrapper {
            padding-top: calc(var(--header-height) + var(--nav-height));
        }
        
        .main-content {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            box-sizing: border-box;
        }
        
        /* CRITICAL: Remove padding for homepage content */
        .main-content:has(.homepage-content) {
            padding: 0 !important;
            max-width: 100vw !important;
        }
        
        /* ==============================================
           RESPONSIVE BREAKPOINTS
           ============================================== */
        /* Mobile (default) - Hide desktop nav, show mobile toggle */
        @media (max-width: 1023px) {
            .desktop-nav-container {
                display: none !important;
            }
            
            .desktop-nav {
                display: none !important;
            }
            
            .mobile-menu-toggle-wrapper {
                display: flex;
            }
            
            .apply-btn {
                display: none;
            }
            
            .brand-line-1 {
                font-size: 0.75rem;
            }
            
            .brand-line-2 {
                font-size: 1.4rem;
            }
            
            .brand-logo {
                width: 52px;
                height: 52px;
            }
            
            .main-content-wrapper {
                padding-top: var(--header-height);
            }
            
            .header-container {
                padding: 0 1.5rem;
                max-width: 100vw;
                box-sizing: border-box;
            }
            
            .mobile-nav-overlay {
                padding: 1.5rem 1.5rem;
                width: 100vw;
                max-width: 100vw;
                box-sizing: border-box;
            }
            
            .main-content:has(.homepage-content) {
                padding: 0 !important;
                max-width: 100vw !important;
            }
        }
        
        /* Desktop (1024px and up) - Show desktop nav, hide mobile toggle */
        @media (min-width: 1024px) {
            .desktop-nav-container {
                display: block;
            }
            
            .desktop-nav {
                display: block;
            }
            
            .mobile-menu-toggle-wrapper {
                display: none;
            }
            
            .mobile-nav-overlay {
                display: none;
            }
            
            .brand-line-1 {
                font-size: 0.85rem;
            }
            
            .brand-line-2 {
                font-size: 1.8rem;
            }
            
            .brand-logo {
                width: 60px;
                height: 60px;
            }
            
            .main-content-wrapper {
                padding-top: calc(var(--header-height) + var(--nav-height));
            }
            
            .header-container {
                padding: 0 2rem;
                max-width: 100vw;
                box-sizing: border-box;
            }
            
            .desktop-nav-menu {
                justify-content: center;
                padding: 0 2rem;
                max-width: 100vw;
                box-sizing: border-box;
            }
            
            .main-content:has(.homepage-content) {
                padding: 0 !important;
                max-width: 100vw !important;
            }
        }
        
        /* ==========================================================================
           FULL WIDTH UTILITIES
           ========================================================================== */
        .full-width {
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(-50vw + 50%) !important;
            margin-right: calc(-50vw + 50%) !important;
        }

        .prevent-overflow {
            max-width: 100% !important;
            overflow-x: hidden !important;
        }

        /* Ensure images don't cause overflow */
        img, video, iframe {
            max-width: 100%;
            height: auto;
        }
        
        /* ==============================================
           UTILITY CLASSES
           ============================================== */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
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

<!-- Search Modal -->
<div class="search-modal" id="searchModal">
    <div class="search-modal-content">
        <button class="search-modal-close" aria-label="Close search" onclick="closeSearch()">
            &times;
        </button>
        <form action="<?php echo $baseUrl; ?>/search" method="GET" class="search-form">
            <div style="position: relative;">
                <i class="fas fa-search mobile-search-icon"></i>
                <input type="search" 
                       name="q" 
                       class="mobile-search" 
                       placeholder="Search courses, faculty, research..."
                       aria-label="Search the website">
            </div>
        </form>
    </div>
</div>

<!-- Fixed Header -->
<header class="site-header" role="banner">
    <div class="header-container">
        <!-- Professional Brand -->
        <a href="<?php echo $baseUrl; ?>/" class="brand">
            <div class="brand-logo">
                <?php
                $logoPath = $baseUrl . '/assets/images/logo/logo.png';
                echo '<img src="' . $logoPath . '" 
                     alt="FCT College of Nursing Sciences" 
                     onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">';
                ?>
                <div class="logo-fallback" style="display: none;">
                    FCT CNS
                </div>
            </div>
            <div class="brand-text">
                <div class="brand-name">
                    <span class="brand-line-1">FCT College of</span>
                    <span class="brand-line-2">Nursing Sciences</span>
                </div>
            </div>
        </a>
        
        <!-- Header Actions -->
        <div class="header-actions">
            <!-- Search Button -->
            <button class="search-btn" aria-label="Open search" onclick="openSearch()">
                <i class="fas fa-search"></i>
            </button>
            
            <!-- Apply Now Button (Desktop only) -->
            <a href="<?php echo $baseUrl; ?>/admissions" class="apply-btn">
                <i class="fas fa-file-alt"></i>
                <span>Apply Now</span>
            </a>
            
            <!-- User Button (only when logged in) -->
            <?php if ($isLoggedIn): ?>
            <a href="<?php echo $baseUrl; ?>/student/dashboard" class="user-btn" aria-label="User dashboard">
                <?php echo strtoupper(substr($username, 0, 1)); ?>
            </a>
            <?php endif; ?>
            
            <!-- Mobile Menu Toggle -->
            <div class="mobile-menu-toggle-wrapper" onclick="toggleMobileMenu()">
                <button class="mobile-menu-toggle" aria-label="Toggle menu" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div class="menu-label">MENU</div>
            </div>
        </div>
    </div>
</header>

<!-- Desktop Navigation -->
<div class="desktop-nav-container">
    <nav class="desktop-nav" aria-label="Main navigation">
        <ul class="desktop-nav-menu">
            <li class="desktop-nav-item">
                <a href="<?php echo $baseUrl; ?>/" 
                   class="desktop-nav-link <?php echo ($currentPage == 'home' || $currentPage == '') ? 'active' : ''; ?>">
                    Home
                </a>
            </li>
            <li class="desktop-nav-item has-dropdown">
                <a href="<?php echo $baseUrl; ?>/about" 
                   class="desktop-nav-link <?php echo $currentPage == 'about' ? 'active' : ''; ?>">
                    About
                </a>
                <div class="dropdown-menu">
                    <a href="<?php echo $baseUrl; ?>/about/leadership" class="dropdown-link">Leadership</a>
                    <a href="<?php echo $baseUrl; ?>/about/history" class="dropdown-link">History</a>
                    <a href="<?php echo $baseUrl; ?>/about/mission" class="dropdown-link">Mission & Values</a>
                    <a href="<?php echo $baseUrl; ?>/about/accreditation" class="dropdown-link">Accreditation</a>
                </div>
            </li>
            <li class="desktop-nav-item has-dropdown">
                <a href="<?php echo $baseUrl; ?>/programs" 
                   class="desktop-nav-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>">
                    Academic Programs
                </a>
                <div class="dropdown-menu">
                    <a href="<?php echo $baseUrl; ?>/programs/undergraduate" class="dropdown-link">Undergraduate</a>
                    <a href="<?php echo $baseUrl; ?>/programs/graduate" class="dropdown-link">Graduate</a>
                    <a href="<?php echo $baseUrl; ?>/programs/continuing-education" class="dropdown-link">Continuing Education</a>
                </div>
            </li>
            <li class="desktop-nav-item">
                <a href="<?php echo $baseUrl; ?>/admissions" 
                   class="desktop-nav-link <?php echo $currentPage == 'admissions' ? 'active' : ''; ?>">
                    Admissions
                </a>
            </li>
            <li class="desktop-nav-item has-dropdown">
                <a href="<?php echo $baseUrl; ?>/research" 
                   class="desktop-nav-link <?php echo $currentPage == 'research' ? 'active' : ''; ?>">
                    Research
                </a>
                <div class="dropdown-menu">
                    <a href="<?php echo $baseUrl; ?>/research/publications" class="dropdown-link">Publications</a>
                    <a href="<?php echo $baseUrl; ?>/research/projects" class="dropdown-link">Research Projects</a>
                    <a href="<?php echo $baseUrl; ?>/research/facilities" class="dropdown-link">Research Facilities</a>
                    <a href="<?php echo $baseUrl; ?>/research/grants" class="dropdown-link">Grants & Funding</a>
                </div>
            </li>
            <li class="desktop-nav-item">
                <a href="<?php echo $baseUrl; ?>/student-life" 
                   class="desktop-nav-link <?php echo $currentPage == 'student-life' ? 'active' : ''; ?>">
                    Student Life
                </a>
            </li>
            <li class="desktop-nav-item">
                <a href="<?php echo $baseUrl; ?>/faculty" 
                   class="desktop-nav-link <?php echo $currentPage == 'faculty' ? 'active' : ''; ?>">
                    Faculty
                </a>
            </li>
            <li class="desktop-nav-item">
                <a href="<?php echo $baseUrl; ?>/news" 
                   class="desktop-nav-link <?php echo $currentPage == 'news' ? 'active' : ''; ?>">
                    News & Events
                </a>
            </li>
            <!-- SPECIAL CONTACT TAB -->
            <li class="desktop-nav-item contact-tab">
                <a href="<?php echo $baseUrl; ?>/contact" 
                   class="desktop-nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
                    Contact Us
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Mobile Navigation Overlay -->
<div class="mobile-nav-overlay" id="mobileNav">
    <div class="mobile-nav-content">
        <!-- Search in Mobile Menu -->
        <div class="mobile-search-container">
            <div style="position: relative;">
                <i class="fas fa-search mobile-search-icon"></i>
                <input type="search" 
                       class="mobile-search" 
                       placeholder="Search the website..."
                       id="mobileMenuSearch"
                       aria-label="Search in mobile menu">
            </div>
        </div>
        
        <!-- Mobile Navigation Menu -->
        <ul class="mobile-nav-menu">
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/" 
                   class="mobile-nav-link <?php echo ($currentPage == 'home' || $currentPage == '') ? 'active' : ''; ?>">
                    <span>Home</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/about" 
                   class="mobile-nav-link <?php echo $currentPage == 'about' ? 'active' : ''; ?>">
                    <span>About Us</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/programs" 
                   class="mobile-nav-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>">
                    <span>Academic Programs</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/admissions" 
                   class="mobile-nav-link <?php echo $currentPage == 'admissions' ? 'active' : ''; ?>">
                    <span>Admissions</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/research" 
                   class="mobile-nav-link <?php echo $currentPage == 'research' ? 'active' : ''; ?>">
                    <span>Research</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/student-life" 
                   class="mobile-nav-link <?php echo $currentPage == 'student-life' ? 'active' : ''; ?>">
                    <span>Student Life</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/faculty" 
                   class="mobile-nav-link <?php echo $currentPage == 'faculty' ? 'active' : ''; ?>">
                    <span>Faculty</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/news" 
                   class="mobile-nav-link <?php echo $currentPage == 'news' ? 'active' : ''; ?>">
                    <span>News & Events</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <!-- SPECIAL MOBILE CONTACT TAB -->
            <li class="mobile-nav-item contact-tab">
                <a href="<?php echo $baseUrl; ?>/contact" 
                   class="mobile-nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
                    <span>Contact Us</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <?php if ($isLoggedIn): ?>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/student/dashboard" class="mobile-nav-link">
                    <span>Student Dashboard</span>
                    <i class="fas fa-tachometer-alt"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <!-- Quick Actions in Mobile Menu -->
        <div class="mobile-quick-actions">
            <a href="<?php echo $baseUrl; ?>/admissions/apply" class="mobile-action-btn">
                <i class="fas fa-file-import"></i>
                Apply Now
            </a>
            <a href="<?php echo $baseUrl; ?>/student-life" class="mobile-action-btn accent">
                <i class="fas fa-graduation-cap"></i>
                Student Portal
            </a>
            <?php if ($isLoggedIn): ?>
            <a href="<?php echo $baseUrl; ?>/student/logout" class="mobile-action-btn" style="background: #dc2626;">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
            <?php endif; ?>
        </div>
        
        <!-- Contact Info in Mobile Menu -->
        <div class="mobile-contact-info">
            <div class="mobile-contact-grid">
                <a href="tel:+2348082775076" class="mobile-contact-item">
                    <i class="fas fa-phone"></i>
                    <span>+234 808 277 5076</span>
                </a>
                <a href="mailto:info@fctcns.edu.ng" class="mobile-contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>info@fctcns.edu.ng</span>
                </a>
                <a href="<?php echo $baseUrl; ?>/visit" class="mobile-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Visit Campus</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Wrapper -->
<div class="main-content-wrapper">
    <main class="main-content">

<script>
// ==============================================
// CLEAN & SIMPLE FUNCTIONALITY
// ==============================================

// Toggle Mobile Menu
function toggleMobileMenu() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileNav = document.getElementById('mobileNav');
    const body = document.body;
    
    menuToggle.classList.toggle('active');
    mobileNav.classList.toggle('active');
    
    const isExpanded = menuToggle.classList.contains('active');
    menuToggle.setAttribute('aria-expanded', isExpanded);
    
    if (isExpanded) {
        body.style.overflow = 'hidden';
    } else {
        body.style.overflow = '';
    }
}

// Search Functions
function openSearch() {
    const searchModal = document.getElementById('searchModal');
    const searchInput = searchModal.querySelector('input[type="search"]');
    
    searchModal.classList.add('active');
    setTimeout(() => searchInput.focus(), 100);
    
    document.addEventListener('keydown', function closeOnEscape(e) {
        if (e.key === 'Escape') {
            closeSearch();
            document.removeEventListener('keydown', closeOnEscape);
        }
    });
}

function closeSearch() {
    const searchModal = document.getElementById('searchModal');
    searchModal.classList.remove('active');
}

// Close Mobile Menu When Clicking Outside
document.addEventListener('click', function(event) {
    const menuToggle = document.querySelector('.mobile-menu-toggle-wrapper');
    const mobileNav = document.getElementById('mobileNav');
    const searchModal = document.getElementById('searchModal');
    
    if (mobileNav.classList.contains('active') && 
        !menuToggle.contains(event.target) && 
        !mobileNav.contains(event.target)) {
        toggleMobileMenu();
    }
    
    if (searchModal.classList.contains('active') && 
        event.target === searchModal) {
        closeSearch();
    }
});

// Handle Escape Key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const mobileNav = document.getElementById('mobileNav');
        if (mobileNav.classList.contains('active')) {
            toggleMobileMenu();
        }
        closeSearch();
    }
});

// Handle Mobile Menu Search
const mobileMenuSearch = document.getElementById('mobileMenuSearch');
if (mobileMenuSearch) {
    mobileMenuSearch.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const searchTerm = this.value.trim();
            if (searchTerm) {
                window.location.href = `<?php echo $baseUrl; ?>/search?q=${encodeURIComponent(searchTerm)}`;
                toggleMobileMenu();
            }
        }
    });
}

// Handle Search Modal Submission
const searchModalForm = document.querySelector('.search-modal .search-form');
if (searchModalForm) {
    searchModalForm.addEventListener('submit', function(e) {
        const input = this.querySelector('input[type="search"]');
        if (!input.value.trim()) {
            e.preventDefault();
            input.focus();
        }
    });
}

// Initialize on DOM Load
document.addEventListener('DOMContentLoaded', function() {
    // Close flash messages
    document.querySelectorAll('.flash-close').forEach(button => {
        button.addEventListener('click', function() {
            const flashMessage = this.closest('.flash-message');
            flashMessage.style.opacity = '0';
            flashMessage.style.transform = 'translateY(-10px)';
            setTimeout(() => flashMessage.style.display = 'none', 300);
        });
    });
    
    // Auto-hide flash messages after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.flash-message').forEach(message => {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-10px)';
            setTimeout(() => message.style.display = 'none', 300);
        });
    }, 5000);
    
    // Initialize menu toggle ARIA state
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    if (menuToggle) {
        menuToggle.setAttribute('aria-expanded', 'false');
    }
    
    // Handle logo fallback
    const logoImg = document.querySelector('.brand-logo img');
    const logoFallback = document.querySelector('.logo-fallback');
    
    if (logoImg && logoFallback) {
        logoImg.addEventListener('error', function() {
            this.style.display = 'none';
            logoFallback.style.display = 'flex';
        });
        
        logoImg.addEventListener('load', function() {
            logoFallback.style.display = 'none';
        });
        
        // Check if image loaded
        if (!logoImg.complete || logoImg.naturalWidth === 0) {
            logoImg.style.display = 'none';
            logoFallback.style.display = 'flex';
        }
    }
    
    // Handle desktop dropdown hover
    document.querySelectorAll('.has-dropdown').forEach(item => {
        item.addEventListener('mouseenter', function() {
            if (window.innerWidth >= 1024) {
                this.querySelector('.dropdown-menu').style.opacity = '1';
                this.querySelector('.dropdown-menu').style.visibility = 'visible';
                this.querySelector('.dropdown-menu').style.transform = 'translateY(0)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            if (window.innerWidth >= 1024) {
                this.querySelector('.dropdown-menu').style.opacity = '0';
                this.querySelector('.dropdown-menu').style.visibility = 'hidden';
                this.querySelector('.dropdown-menu').style.transform = 'translateY(-10px)';
            }
        });
    });
    
    // Close mobile menu on window resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            const mobileNav = document.getElementById('mobileNav');
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            
            if (mobileNav.classList.contains('active')) {
                mobileNav.classList.remove('active');
                menuToggle.classList.remove('active');
                menuToggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
        }
    });
    
    // Handle navigation scroll on desktop
    const desktopNavMenu = document.querySelector('.desktop-nav-menu');
    if (desktopNavMenu) {
        let isDown = false;
        let startX;
        let scrollLeft;
        
        desktopNavMenu.addEventListener('mousedown', (e) => {
            isDown = true;
            desktopNavMenu.classList.add('active');
            startX = e.pageX - desktopNavMenu.offsetLeft;
            scrollLeft = desktopNavMenu.scrollLeft;
        });
        
        desktopNavMenu.addEventListener('mouseleave', () => {
            isDown = false;
            desktopNavMenu.classList.remove('active');
        });
        
        desktopNavMenu.addEventListener('mouseup', () => {
            isDown = false;
            desktopNavMenu.classList.remove('active');
        });
        
        desktopNavMenu.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - desktopNavMenu.offsetLeft;
            const walk = (x - startX) * 2;
            desktopNavMenu.scrollLeft = scrollLeft - walk;
        });
    }
    
    // ==============================================
    // CRITICAL: Force full width on hero section
    // ==============================================
    const heroSection = document.querySelector('.hero-section');
    const mainContent = document.querySelector('.main-content');
    const homepageContent = document.querySelector('.homepage-content');
    
    if (heroSection && homepageContent) {
        // Remove padding from main-content when homepage exists
        if (mainContent) {
            mainContent.style.padding = '0';
            mainContent.style.maxWidth = '100vw';
        }
        
        // Force hero to full width
        heroSection.style.width = '100vw';
        heroSection.style.maxWidth = '100vw';
        heroSection.style.marginLeft = '0';
        heroSection.style.marginRight = '0';
        heroSection.style.left = '50%';
        heroSection.style.right = '50%';
        heroSection.style.transform = 'translateX(-50%)';
        heroSection.style.position = 'relative';
    }
});

// Update content padding on resize
window.addEventListener('resize', function() {
    const header = document.querySelector('.site-header');
    const navContainer = document.querySelector('.desktop-nav-container');
    const mainContentWrapper = document.querySelector('.main-content-wrapper');
    
    if (mainContentWrapper) {
        if (window.innerWidth >= 1024 && navContainer) {
            const totalHeight = header.offsetHeight + navContainer.offsetHeight;
            mainContentWrapper.style.paddingTop = totalHeight + 'px';
        } else {
            mainContentWrapper.style.paddingTop = header.offsetHeight + 'px';
        }
    }
    
    // Re-apply full width fixes on resize
    const heroSection = document.querySelector('.hero-section');
    const mainContent = document.querySelector('.main-content');
    const homepageContent = document.querySelector('.homepage-content');
    
    if (heroSection && homepageContent) {
        if (mainContent) {
            mainContent.style.padding = '0';
            mainContent.style.maxWidth = '100vw';
        }
        
        heroSection.style.width = '100vw';
        heroSection.style.maxWidth = '100vw';
        heroSection.style.marginLeft = '0';
        heroSection.style.marginRight = '0';
        heroSection.style.left = '50%';
        heroSection.style.right = '50%';
        heroSection.style.transform = 'translateX(-50%)';
        heroSection.style.position = 'relative';
    }
});

// Set initial content padding
window.addEventListener('load', function() {
    const header = document.querySelector('.site-header');
    const navContainer = document.querySelector('.desktop-nav-container');
    const mainContentWrapper = document.querySelector('.main-content-wrapper');
    
    if (mainContentWrapper) {
        if (window.innerWidth >= 1024 && navContainer) {
            const totalHeight = header.offsetHeight + navContainer.offsetHeight;
            mainContentWrapper.style.paddingTop = totalHeight + 'px';
        } else {
            mainContentWrapper.style.paddingTop = header.offsetHeight + 'px';
        }
    }
    
    // Apply full width fixes on load
    const heroSection = document.querySelector('.hero-section');
    const mainContent = document.querySelector('.main-content');
    const homepageContent = document.querySelector('.homepage-content');
    
    if (heroSection && homepageContent) {
        if (mainContent) {
            mainContent.style.padding = '0';
            mainContent.style.maxWidth = '100vw';
        }
        
        heroSection.style.width = '100vw';
        heroSection.style.maxWidth = '100vw';
        heroSection.style.marginLeft = '0';
        heroSection.style.marginRight = '0';
        heroSection.style.left = '50%';
        heroSection.style.right = '50%';
        heroSection.style.transform = 'translateX(-50%)';
        heroSection.style.position = 'relative';
    }
});

// Ensure header stays at top and full width
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.site-header');
    const navContainer = document.querySelector('.desktop-nav-container');
    const body = document.body;
    
    // Ensure body has no margin/padding and is full width
    body.style.margin = '0';
    body.style.padding = '0';
    body.style.width = '100%';
    body.style.maxWidth = '100vw';
    body.style.overflowX = 'hidden';
    
    // Ensure HTML is also full width
    document.documentElement.style.margin = '0';
    document.documentElement.style.padding = '0';
    document.documentElement.style.width = '100%';
    document.documentElement.style.maxWidth = '100vw';
    document.documentElement.style.overflowX = 'hidden';
    
    if (header) {
        header.style.position = 'fixed';
        header.style.top = '0';
        header.style.left = '0';
        header.style.right = '0';
        header.style.width = '100%';
        header.style.maxWidth = '100vw';
        header.style.zIndex = '1000';
        header.style.margin = '0';
        header.style.padding = '0';
    }
    
    if (navContainer && window.innerWidth >= 1024) {
        navContainer.style.position = 'fixed';
        navContainer.style.top = header.offsetHeight + 'px';
        navContainer.style.left = '0';
        navContainer.style.right = '0';
        navContainer.style.width = '100%';
        navContainer.style.maxWidth = '100vw';
        navContainer.style.zIndex = '999';
        navContainer.style.margin = '0';
        navContainer.style.padding = '0';
    }
});

// Fix for horizontal scroll issues
window.addEventListener('load', function() {
    // Prevent horizontal scroll
    document.documentElement.style.overflowX = 'hidden';
    document.body.style.overflowX = 'hidden';
    
    // Fix any overflowing elements
    document.querySelectorAll('*').forEach(element => {
        const rect = element.getBoundingClientRect();
        if (rect.right > window.innerWidth) {
            element.style.maxWidth = '100%';
            element.style.overflowX = 'hidden';
        }
    });
});
</script>