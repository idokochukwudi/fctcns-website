<?php
/**
 * University Header Template - Professional Hybrid Design
 * Desktop: Show navigation tabs
 * Mobile: Collapse into menu with "MENU" label
 * FULL WIDTH VERSION - Fixed padding issues
 * No search functionality
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
           NO SEARCH FUNCTIONALITY
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
           ENHANCED BRAND STYLES - Better Logo Scaling
           ============================================== */
        .brand {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            text-decoration: none;
            color: inherit;
            min-width: 0;
            flex-shrink: 0;
        }
        
        /* ENHANCED: Better logo container with responsive scaling */
        .brand-logo {
            width: 65px; /* Increased from 60px */
            height: 65px; /* Increased from 60px */
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            overflow: hidden;
            background: var(--color-primary);
            border: 1px solid var(--color-gray-200);
            transition: all var(--transition-smooth);
        }
        
        /* ENHANCED: Logo image scaling */
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px; /* Reduced padding to show more logo */
            display: block;
            transition: transform var(--transition-smooth);
        }
        
        /* Hover effect for logo */
        .brand:hover .brand-logo img {
            transform: scale(1.05);
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
            font-size: 1.1rem; /* Increased font size */
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
        
        /* ENHANCED: Better font sizing and spacing */
        .brand-line-1 {
            font-size: 0.9rem; /* Increased from 0.85rem */
            font-weight: 600;
            color: var(--color-primary);
            letter-spacing: 1px;
            text-transform: uppercase;
            opacity: 1;
            font-style: normal;
            margin-bottom: 2px;
            font-family: var(--font-heading);
        }
        
        /* ENHANCED: Improved typography for main name */
        .brand-line-2 {
            font-size: 2rem; /* Increased from 1.8rem for better visibility */
            font-weight: 700;
            color: var(--color-primary-dark);
            letter-spacing: -0.3px;
            font-style: normal;
            position: relative;
            font-family: var(--font-display);
            line-height: 1.2;
        }
        
        .brand-line-2:after {
            content: '';
            position: absolute;
            bottom: -4px; /* Adjusted position */
            left: 0;
            width: 55px; /* Slightly longer underline */
            height: 3px; /* Thicker underline */
            background: var(--color-accent);
            border-radius: 2px;
        }
        
        /* ==============================================
           HEADER ACTIONS - Search Icon Removed
           ============================================== */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem; /* Reduced gap since search is removed */
        }
        
        /* ENHANCED: Apply Button - Better visibility */
        .apply-btn {
            padding: 0.7rem 1.6rem; /* Slightly larger padding */
            background: var(--color-accent);
            color: var(--color-white);
            border: none;
            border-radius: 10px; /* More rounded */
            font-size: 0.95rem; /* Slightly larger font */
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem; /* Increased gap */
            cursor: pointer;
            transition: all var(--transition-smooth);
            white-space: nowrap;
            font-family: var(--font-heading);
            letter-spacing: 0.3px;
        }
        
        .apply-btn:hover {
            background: var(--color-accent-dark);
            color: var(--color-white);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(193, 154, 14, 0.25);
        }
        
        /* ENHANCED: User Button - Better design */
        .user-btn {
            width: 46px; /* Slightly larger */
            height: 46px; /* Slightly larger */
            border-radius: 10px; /* More rounded */
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light));
            color: var(--color-white);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem; /* Slightly larger font */
            cursor: pointer;
            transition: all var(--transition-smooth);
            box-shadow: 0 2px 8px rgba(10, 44, 94, 0.1);
        }
        
        .user-btn:hover {
            background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(10, 44, 94, 0.15);
        }
        
        /* ==============================================
           MOBILE MENU TOGGLE - Enhanced Design
           ============================================== */
        .mobile-menu-toggle-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            cursor: pointer;
        }
        
        .mobile-menu-toggle {
            width: 46px; /* Slightly larger */
            height: 46px; /* Slightly larger */
            border-radius: 10px; /* More rounded */
            border: 1px solid var(--color-gray-300);
            background: var(--color-white);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            cursor: pointer;
            transition: all var(--transition-smooth);
        }
        
        .mobile-menu-toggle:hover {
            border-color: var(--color-primary);
            background: var(--color-gray-50);
            transform: translateY(-1px);
        }
        
        .mobile-menu-toggle span {
            display: block;
            width: 22px; /* Slightly wider */
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
        
        /* ENHANCED: MENU Label */
        .menu-label {
            font-size: 0.75rem; /* Slightly larger */
            font-weight: 600;
            color: var(--color-gray-600);
            text-transform: uppercase;
            letter-spacing: 0.8px; /* Increased letter spacing */
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
        
        /* SPECIAL CONTACT TAB - Enhanced design */
        .desktop-nav-item.contact-tab .desktop-nav-link {
            color: var(--color-contact);
            background: rgba(185, 28, 28, 0.05);
            border-left: 1px solid rgba(185, 28, 28, 0.1);
            border-right: 1px solid rgba(185, 28, 28, 0.1);
            font-weight: 600;
            position: relative;
            overflow: hidden;
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
            min-width: 240px; /* Increased width */
            border-radius: 0 0 10px 10px; /* More rounded */
            box-shadow: var(--shadow-elevated);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all var(--transition-smooth);
            z-index: 100;
            border: 1px solid var(--color-gray-200);
            border-top: none;
            padding: 0.5rem 0; /* Added padding */
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
            margin: 0 0.5rem; /* Added margin */
            border-radius: 6px; /* Rounded corners */
        }
        
        .dropdown-link:hover {
            background: rgba(10, 44, 94, 0.08); /* Stronger hover */
            color: var(--color-primary);
            border-left-color: var(--color-accent);
            padding-left: 1.75rem;
        }
        
        /* ==============================================
           MOBILE NAVIGATION OVERLAY - Enhanced (No Search)
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
            padding: 1.1rem 0; /* Increased padding */
            color: var(--color-gray-700);
            text-decoration: none;
            font-weight: 500;
            font-size: 1.05rem; /* Slightly larger */
            transition: all var(--transition-fast);
        }
        
        .mobile-nav-link:hover {
            color: var(--color-primary);
            padding-left: 0.75rem; /* Increased shift */
        }
        
        .mobile-nav-link.active {
            color: var(--color-primary);
            font-weight: 600;
        }
        
        /* Enhanced Mobile Contact Tab */
        .mobile-nav-item.contact-tab .mobile-nav-link {
            color: var(--color-contact);
            font-weight: 600;
            background: rgba(185, 28, 28, 0.05);
            padding: 1.1rem 1rem; /* Increased padding */
            margin: 0.5rem 0;
            border-radius: 10px; /* More rounded */
            border: 2px solid rgba(185, 28, 28, 0.1); /* Thicker border */
        }
        
        .mobile-nav-item.contact-tab .mobile-nav-link:hover {
            color: var(--color-white);
            background: var(--color-contact);
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(185, 28, 28, 0.2);
        }
        
        /* Quick Actions in Mobile Menu */
        .mobile-quick-actions {
            display: flex;
            flex-direction: column;
            gap: 0.85rem; /* Increased gap */
            margin-bottom: 1.5rem;
        }
        
        .mobile-action-btn {
            padding: 1.1rem 1.5rem; /* Increased padding */
            border-radius: 10px; /* More rounded */
            background: var(--color-primary);
            color: var(--color-white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all var(--transition-smooth);
            font-weight: 500;
            justify-content: center;
            text-align: center;
            box-shadow: 0 2px 8px rgba(10, 44, 94, 0.1);
        }
        
        .mobile-action-btn:hover {
            background: var(--color-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(10, 44, 94, 0.15);
        }
        
        .mobile-action-btn.accent {
            background: var(--color-accent);
            color: var(--color-white);
        }
        
        .mobile-action-btn.accent:hover {
            background: var(--color-accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(193, 154, 14, 0.2);
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
            padding: 0.85rem; /* Increased padding */
            border-radius: 10px; /* More rounded */
            transition: all var(--transition-fast);
            border: 1px solid transparent;
        }
        
        .mobile-contact-item:hover {
            background: var(--color-gray-100);
            color: var(--color-primary);
            border-color: var(--color-gray-200);
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
           RESPONSIVE BREAKPOINTS - ENHANCED LOGO SCALING
           ============================================== */
        /* Extra Small Mobile (320px - 480px) */
        @media (max-width: 480px) {
            .header-container {
                padding: 0 1rem; /* Reduced padding */
            }
            
            .brand {
                gap: 0.8rem; /* Reduced gap */
            }
            
            .brand-logo {
                width: 50px; /* Responsive scaling */
                height: 50px;
            }
            
            .brand-line-1 {
                font-size: 0.7rem; /* Smaller font */
            }
            
            .brand-line-2 {
                font-size: 1.2rem; /* Smaller but still readable */
            }
            
            .user-btn,
            .mobile-menu-toggle {
                width: 40px;
                height: 40px;
            }
            
            .mobile-nav-overlay {
                padding: 1rem; /* Reduced padding */
            }
            
            .apply-btn {
                padding: 0.6rem 1rem; /* Smaller on mobile */
                font-size: 0.85rem;
            }
        }
        
        /* Small Mobile (481px - 639px) */
        @media (min-width: 481px) and (max-width: 639px) {
            .brand-logo {
                width: 55px;
                height: 55px;
            }
            
            .brand-line-1 {
                font-size: 0.75rem;
            }
            
            .brand-line-2 {
                font-size: 1.3rem;
            }
        }
        
        /* Medium Mobile/Tablet (640px - 767px) */
        @media (min-width: 640px) and (max-width: 767px) {
            .brand-logo {
                width: 58px;
                height: 58px;
            }
            
            .brand-line-1 {
                font-size: 0.8rem;
            }
            
            .brand-line-2 {
                font-size: 1.5rem;
            }
        }
        
        /* Tablet (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            .brand-logo {
                width: 60px;
                height: 60px;
            }
            
            .brand-line-1 {
                font-size: 0.85rem;
            }
            
            .brand-line-2 {
                font-size: 1.6rem;
            }
            
            .apply-btn {
                display: none; /* Hide on tablet to save space */
            }
        }
        
        /* Mobile (default up to 1023px) - Hide desktop nav, show mobile toggle */
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
            
            .brand-logo {
                width: 65px; /* Desktop size */
                height: 65px;
            }
            
            .brand-line-1 {
                font-size: 0.9rem;
            }
            
            .brand-line-2 {
                font-size: 2rem;
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
        
        /* Large Desktop (1200px and up) */
        @media (min-width: 1200px) {
            .brand-logo {
                width: 70px; /* Larger on big screens */
                height: 70px;
            }
            
            .brand-line-1 {
                font-size: 1rem;
            }
            
            .brand-line-2 {
                font-size: 2.2rem;
            }
            
            .header-container {
                padding: 0 3rem; /* More padding on large screens */
            }
            
            .desktop-nav-menu {
                padding: 0 3rem;
            }
        }
        
        /* Extra Large Desktop (1400px and up) */
        @media (min-width: 1400px) {
            .brand-logo {
                width: 75px;
                height: 75px;
            }
            
            .brand-line-1 {
                font-size: 1.1rem;
            }
            
            .brand-line-2 {
                font-size: 2.4rem;
            }
            
            .header-container {
                padding: 0 4rem;
            }
            
            .desktop-nav-menu {
                padding: 0 4rem;
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

<!-- Fixed Header -->
<header class="site-header" role="banner">
    <div class="header-container">
        <!-- Enhanced Professional Brand -->
        <a href="<?php echo $baseUrl; ?>/" class="brand">
            <div class="brand-logo">
                <?php
                $logoPath = $baseUrl . '/assets/images/logo/logo.png';
                echo '<img src="' . $logoPath . '" 
                     alt="FCT College of Nursing Sciences" 
                     loading="eager"
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
        
        <!-- Header Actions - Search Removed -->
        <div class="header-actions">
            <!-- Apply Now Button (Desktop only) -->
            <a href="<?php echo $baseUrl; ?>/admissions" class="apply-btn">
                <i class="fas fa-file-alt"></i>
                <span>Apply Now</span>
            </a>
            
            <!-- User Button (only when logged in) -->
            <?php if ($isLoggedIn): ?>
            <a href="<?php echo $baseUrl; ?>/student/dashboard" class="user-btn" aria-label="User dashboard" title="Dashboard">
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
                    <i class="fas fa-phone-alt" style="margin-right: 8px;"></i>
                    Contact Us
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Mobile Navigation Overlay -->
<div class="mobile-nav-overlay" id="mobileNav">
    <div class="mobile-nav-content">
        <!-- Mobile Navigation Menu -->
        <ul class="mobile-nav-menu">
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/" 
                   class="mobile-nav-link <?php echo ($currentPage == 'home' || $currentPage == '') ? 'active' : ''; ?>">
                    <span><i class="fas fa-home" style="margin-right: 12px; width: 20px;"></i>Home</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/about" 
                   class="mobile-nav-link <?php echo $currentPage == 'about' ? 'active' : ''; ?>">
                    <span><i class="fas fa-info-circle" style="margin-right: 12px; width: 20px;"></i>About Us</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/programs" 
                   class="mobile-nav-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>">
                    <span><i class="fas fa-graduation-cap" style="margin-right: 12px; width: 20px;"></i>Academic Programs</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/admissions" 
                   class="mobile-nav-link <?php echo $currentPage == 'admissions' ? 'active' : ''; ?>">
                    <span><i class="fas fa-sign-in-alt" style="margin-right: 12px; width: 20px;"></i>Admissions</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/research" 
                   class="mobile-nav-link <?php echo $currentPage == 'research' ? 'active' : ''; ?>">
                    <span><i class="fas fa-flask" style="margin-right: 12px; width: 20px;"></i>Research</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/student-life" 
                   class="mobile-nav-link <?php echo $currentPage == 'student-life' ? 'active' : ''; ?>">
                    <span><i class="fas fa-users" style="margin-right: 12px; width: 20px;"></i>Student Life</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/faculty" 
                   class="mobile-nav-link <?php echo $currentPage == 'faculty' ? 'active' : ''; ?>">
                    <span><i class="fas fa-chalkboard-teacher" style="margin-right: 12px; width: 20px;"></i>Faculty</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/news" 
                   class="mobile-nav-link <?php echo $currentPage == 'news' ? 'active' : ''; ?>">
                    <span><i class="fas fa-newspaper" style="margin-right: 12px; width: 20px;"></i>News & Events</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <!-- Enhanced Mobile Contact Tab -->
            <li class="mobile-nav-item contact-tab">
                <a href="<?php echo $baseUrl; ?>/contact" 
                   class="mobile-nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
                    <span><i class="fas fa-phone-alt" style="margin-right: 12px;"></i>Contact Us</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <?php if ($isLoggedIn): ?>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/student/dashboard" class="mobile-nav-link">
                    <span><i class="fas fa-tachometer-alt" style="margin-right: 12px; width: 20px;"></i>Student Dashboard</span>
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <!-- Quick Actions in Mobile Menu -->
        <div class="mobile-quick-actions">
            <a href="<?php echo $baseUrl; ?>/admissions" class="mobile-action-btn">
                <i class="fas fa-file-import"></i>
                Apply Now
            </a>
            <a href="<?php echo $baseUrl; ?>/student-life" class="mobile-action-btn accent">
                <i class="fas fa-graduation-cap"></i>
                Student Portal
            </a>
            <?php if ($isLoggedIn): ?>
            <a href="<?php echo $baseUrl; ?>/student/logout" class="mobile-action-btn" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
            <?php endif; ?>
        </div>
        
        <!-- Contact Info in Mobile Menu -->
        <div class="mobile-contact-info">
            <div class="mobile-contact-grid">
                <a href="tel:+2348082775076" class="mobile-contact-item">
                    <i class="fas fa-phone" style="color: #0a2c5e;"></i>
                    <span>+234 808 277 5076</span>
                </a>
                <a href="mailto:info@fctcns.edu.ng" class="mobile-contact-item">
                    <i class="fas fa-envelope" style="color: #0a2c5e;"></i>
                    <span>info@fctcns.edu.ng</span>
                </a>
                <a href="<?php echo $baseUrl; ?>/visit" class="mobile-contact-item">
                    <i class="fas fa-map-marker-alt" style="color: #0a2c5e;"></i>
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
// CLEAN FUNCTIONALITY - NO SEARCH
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

// Close Mobile Menu When Clicking Outside
document.addEventListener('click', function(event) {
    const menuToggle = document.querySelector('.mobile-menu-toggle-wrapper');
    const mobileNav = document.getElementById('mobileNav');
    
    if (mobileNav.classList.contains('active') && 
        !menuToggle.contains(event.target) && 
        !mobileNav.contains(event.target)) {
        toggleMobileMenu();
    }
});

// Handle Escape Key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const mobileNav = document.getElementById('mobileNav');
        if (mobileNav.classList.contains('active')) {
            toggleMobileMenu();
        }
    }
});

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
    
    // Handle logo fallback with enhanced detection
    const logoImg = document.querySelector('.brand-logo img');
    const logoFallback = document.querySelector('.logo-fallback');
    
    if (logoImg && logoFallback) {
        // Create an image object to check if logo exists
        const testImage = new Image();
        testImage.src = logoImg.src;
        
        testImage.onload = function() {
            // Logo exists, ensure it's visible
            logoImg.style.display = 'block';
            logoFallback.style.display = 'none';
        };
        
        testImage.onerror = function() {
            // Logo doesn't exist, show fallback
            logoImg.style.display = 'none';
            logoFallback.style.display = 'flex';
        };
        
        // Also keep the original error handler
        logoImg.addEventListener('error', function() {
            this.style.display = 'none';
            logoFallback.style.display = 'flex';
        });
    }
    
    // Handle desktop dropdown hover with enhanced effects
    document.querySelectorAll('.has-dropdown').forEach(item => {
        item.addEventListener('mouseenter', function() {
            if (window.innerWidth >= 1024) {
                const dropdown = this.querySelector('.dropdown-menu');
                dropdown.style.opacity = '1';
                dropdown.style.visibility = 'visible';
                dropdown.style.transform = 'translateY(0)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            if (window.innerWidth >= 1024) {
                const dropdown = this.querySelector('.dropdown-menu');
                dropdown.style.opacity = '0';
                dropdown.style.visibility = 'hidden';
                dropdown.style.transform = 'translateY(-10px)';
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
    
    // Handle navigation scroll on desktop with enhanced UX
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
        
        // Add touch support for mobile devices
        desktopNavMenu.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - desktopNavMenu.offsetLeft;
            scrollLeft = desktopNavMenu.scrollLeft;
        }, { passive: true });
        
        desktopNavMenu.addEventListener('touchend', () => {
            isDown = false;
        });
        
        desktopNavMenu.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            const x = e.touches[0].pageX - desktopNavMenu.offsetLeft;
            const walk = (x - startX) * 2;
            desktopNavMenu.scrollLeft = scrollLeft - walk;
        }, { passive: true });
    }
    
    // Force full width on hero section
    const heroSection = document.querySelector('.hero-section');
    const mainContent = document.querySelector('.main-content');
    const homepageContent = document.querySelector('.homepage-content');
    
    if (heroSection && homepageContent) {
        // Remove padding from main-content when homepage exists
        if (mainContent) {
            mainContent.style.padding = '0';
            mainContent.style.maxWidth = '100vw';
            mainContent.style.width = '100%';
        }
        
        // Force hero to full width with enhanced positioning
        heroSection.style.width = '100vw';
        heroSection.style.maxWidth = '100vw';
        heroSection.style.marginLeft = '0';
        heroSection.style.marginRight = '0';
        heroSection.style.left = '0';
        heroSection.style.right = '0';
        heroSection.style.position = 'relative';
        heroSection.style.overflow = 'hidden';
    }
});

// Update content padding on resize with debouncing
let resizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
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
                mainContent.style.width = '100%';
            }
            
            heroSection.style.width = '100vw';
            heroSection.style.maxWidth = '100vw';
            heroSection.style.marginLeft = '0';
            heroSection.style.marginRight = '0';
            heroSection.style.left = '0';
            heroSection.style.right = '0';
            heroSection.style.position = 'relative';
            heroSection.style.overflow = 'hidden';
        }
        
        // Update logo size based on viewport
        const brandLogo = document.querySelector('.brand-logo');
        if (brandLogo) {
            if (window.innerWidth < 480) {
                brandLogo.style.width = '50px';
                brandLogo.style.height = '50px';
            } else if (window.innerWidth < 768) {
                brandLogo.style.width = '55px';
                brandLogo.style.height = '55px';
            } else if (window.innerWidth < 1024) {
                brandLogo.style.width = '60px';
                brandLogo.style.height = '60px';
            } else if (window.innerWidth < 1200) {
                brandLogo.style.width = '65px';
                brandLogo.style.height = '65px';
            } else if (window.innerWidth < 1400) {
                brandLogo.style.width = '70px';
                brandLogo.style.height = '70px';
            } else {
                brandLogo.style.width = '75px';
                brandLogo.style.height = '75px';
            }
        }
    }, 100);
});

// Set initial content padding on load
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
            mainContent.style.width = '100%';
        }
        
        heroSection.style.width = '100vw';
        heroSection.style.maxWidth = '100vw';
        heroSection.style.marginLeft = '0';
        heroSection.style.marginRight = '0';
        heroSection.style.left = '0';
        heroSection.style.right = '0';
        heroSection.style.position = 'relative';
        heroSection.style.overflow = 'hidden';
    }
    
    // Initial logo size setup
    const brandLogo = document.querySelector('.brand-logo');
    if (brandLogo) {
        if (window.innerWidth < 480) {
            brandLogo.style.width = '50px';
            brandLogo.style.height = '50px';
        } else if (window.innerWidth < 768) {
            brandLogo.style.width = '55px';
            brandLogo.style.height = '55px';
        } else if (window.innerWidth < 1024) {
            brandLogo.style.width = '60px';
            brandLogo.style.height = '60px';
        } else if (window.innerWidth < 1200) {
            brandLogo.style.width = '65px';
            brandLogo.style.height = '65px';
        } else if (window.innerWidth < 1400) {
            brandLogo.style.width = '70px';
            brandLogo.style.height = '70px';
        } else {
            brandLogo.style.width = '75px';
            brandLogo.style.height = '75px';
        }
    }
});

// Enhanced header positioning
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

// Fix for horizontal scroll issues with enhanced detection
window.addEventListener('load', function() {
    // Prevent horizontal scroll
    document.documentElement.style.overflowX = 'hidden';
    document.body.style.overflowX = 'hidden';
    
    // Fix any overflowing elements with better detection
    function checkOverflow() {
        document.querySelectorAll('*').forEach(element => {
            const rect = element.getBoundingClientRect();
            if (rect.right > window.innerWidth + 5 || rect.left < -5) { // Small tolerance
                element.style.maxWidth = '100%';
                element.style.overflowX = 'hidden';
                
                // Special handling for specific elements
                if (element.classList.contains('desktop-nav-menu')) {
                    element.style.overflowX = 'auto';
                }
            }
        });
    }
    
    checkOverflow();
    
    // Re-check on resize
    window.addEventListener('resize', checkOverflow);
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            // Close mobile menu if open
            const mobileNav = document.getElementById('mobileNav');
            if (mobileNav.classList.contains('active')) {
                toggleMobileMenu();
            }
            
            window.scrollTo({
                top: targetElement.offsetTop - 100,
                behavior: 'smooth'
            });
        }
    });
});
</script>