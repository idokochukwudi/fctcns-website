<?php
/**
 * University Header Template - Final Enhanced Design
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
    
    <!-- Professional Fonts - Clean sans-serif -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&family=Source+Sans+3:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
        /* University Design System with Professional Color Scheme */
        :root {
            --color-primary: #5D4A8A; /* Deep sophisticated purple from homepage */
            --color-primary-dark: #4A3A6F;
            --color-primary-light: #6F5B9E;
            --color-primary-very-light: #F8F6FC;
            --color-primary-transparent: rgba(93, 74, 138, 0.08);
            
            --color-secondary: #3A6B8F; /* Professional blue */
            --color-secondary-dark: #2D5570;
            --color-secondary-light: #4A84B5;
            
            --color-accent: #D4A574; /* Muted gold accent from homepage */
            --color-accent-dark: #BF8F5E;
            --color-accent-light: #E6C9A5;
            
            /* Neutral Colors - Professional */
            --color-white: #FFFFFF;
            --color-off-white: #FAFAFA;
            --color-gray-50: #F5F7FA;
            --color-gray-100: #E8ECF1;
            --color-gray-200: #D1D9E3;
            --color-gray-300: #B8C2CC;
            --color-gray-400: #8F9BB3;
            --color-gray-500: #6c757d;
            --color-gray-600: #495057;
            --color-gray-700: #343a40;
            --color-gray-800: #2D3748;
            --color-gray-900: #1A202C;
            --color-black: #000000;
            
            /* Fonts - Professional */
            --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-heading: 'Poppins', 'Inter', sans-serif; /* More modern than Source Sans */
            --font-brand: 'Poppins', 'Inter', sans-serif; /* For brand name - consistent weight */
            
            /* Spacing */
            --header-height-mobile: 70px;
            --header-height-desktop: 85px;
            --top-bar-height: 40px;
            --nav-height-desktop: 56px;
            
            --container-padding-mobile: 1rem;
            --container-padding-tablet: 1.5rem;
            --container-padding-desktop: 2rem;
            
            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.12);
            
            --transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: var(--font-primary);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            color: var(--color-gray-800);
            background: var(--color-white);
            line-height: 1.5;
        }
        
        /* Flash Messages */
        .flash-messages {
            position: fixed;
            top: var(--header-height-mobile);
            left: 0;
            right: 0;
            z-index: 1001;
            padding: 0.75rem var(--container-padding-mobile);
            pointer-events: none;
        }
        
        @media (min-width: 1024px) {
            .flash-messages {
                top: calc(var(--header-height-desktop) + var(--top-bar-height));
                padding: 0.75rem var(--container-padding-desktop);
            }
        }
        
        .flash-message {
            background: var(--color-white);
            border-left: 4px solid;
            border-radius: 4px;
            padding: 0.875rem 1rem;
            margin-bottom: 0.5rem;
            box-shadow: var(--shadow-lg);
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
        
        /* Top Bar - Professional Style */
        .top-bar {
            background: var(--color-primary);
            color: var(--color-white);
            height: var(--top-bar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 1023px) {
            .top-bar {
                display: none;
            }
        }
        
        .top-bar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 var(--container-padding-desktop);
            font-size: 0.875rem;
        }
        
        .top-bar-left {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .top-bar-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all var(--transition);
            font-size: 0.875rem;
            white-space: nowrap;
            position: relative;
            padding-bottom: 2px;
        }
        
        .top-bar-link:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--color-accent);
            transition: width var(--transition);
        }
        
        .top-bar-link:hover {
            color: var(--color-white);
        }
        
        .top-bar-link:hover:after {
            width: 100%;
        }
        
        .top-bar-right {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .top-bar-action {
            color: var(--color-white);
            text-decoration: none;
            font-weight: 500;
            padding: 0.25rem 0;
            position: relative;
            font-size: 0.875rem;
            transition: color var(--transition);
            padding-bottom: 2px;
        }
        
        .top-bar-action:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--color-accent);
            transition: width var(--transition);
        }
        
        .top-bar-action:hover {
            color: var(--color-accent);
        }
        
        .top-bar-action:hover:after {
            width: 100%;
        }
        
        /* STUDENT PORTAL BUTTON - Enhanced for visibility */
        .top-bar-action.student-portal {
            background: var(--color-accent);
            color: var(--color-gray-900);
            padding: 0.4rem 1.2rem;
            border-radius: 4px;
            font-weight: 600;
            transition: all var(--transition);
            border: 1px solid var(--color-accent);
        }
        
        .top-bar-action.student-portal:hover {
            background: var(--color-accent-dark);
            color: var(--color-gray-900);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(212, 165, 116, 0.3);
            border-color: var(--color-accent-dark);
        }
        
        .top-bar-action.student-portal:after {
            display: none;
        }
        
        /* Main Header */
        .site-header {
            position: fixed;
            top: var(--top-bar-height);
            left: 0;
            right: 0;
            z-index: 1000;
            background: var(--color-white);
            border-bottom: 1px solid var(--color-gray-200);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            height: var(--header-height-desktop);
        }
        
        @media (max-width: 1023px) {
            .site-header {
                top: 0;
                height: var(--header-height-mobile);
            }
        }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 var(--container-padding-mobile);
            max-width: 1400px;
            margin: 0 auto;
            gap: 1rem;
        }
        
        @media (min-width: 768px) {
            .header-container {
                padding: 0 var(--container-padding-tablet);
                gap: 1.5rem;
            }
        }
        
        @media (min-width: 1024px) {
            .header-container {
                padding: 0 var(--container-padding-desktop);
            }
        }
        
        /* ENHANCED BRAND - Clean, Mature Typography with Improved Font Styling */
        .brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
            gap: 0.75rem;
            flex: 1;
            min-width: 0;
        }
        
        /* Logo */
        .brand-logo {
            width: 65px;
            height: 65px;
            flex-shrink: 0;
        }
        
        @media (max-width: 767px) {
            .brand-logo {
                width: 40px;
                height: 40px;
            }
        }
        
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            flex: 1;
        }
        
        /* ENHANCED BRAND NAME - Improved Font Styling for Better Presentation */
        .brand-name {
            font-family: var(--font-brand);
            font-weight: 600;
            color: var(--color-primary);
            line-height: 1.1;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
        }
        
        .brand-line-1 {
            font-size: 1rem;
            font-weight: 600; /* Increased weight for better clarity */
            color: var(--color-primary);
            letter-spacing: 0.5px;
            margin-bottom: 0.1rem;
            text-transform: uppercase;
            opacity: 0.95;
        }
        
        .brand-line-2 {
            font-size: 1.5rem;
            font-weight: 800; /* Extra bold for maximum clarity */
            color: var(--color-primary-dark);
            letter-spacing: -0.02em;
            line-height: 1;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            font-family: 'Poppins', sans-serif; /* Explicit font for clarity */
        }
        
        /* Desktop Brand Styling - Enhanced */
        @media (min-width: 1024px) {
            .brand-line-1 {
                font-size: 1.1rem;
                font-weight: 600;
                letter-spacing: 0.6px;
            }
            
            .brand-line-2 {
                font-size: 1.9rem; /* Slightly larger for desktop */
                font-weight: 800;
                letter-spacing: -0.03em;
            }
        }
        
        /* Tablet Brand Styling */
        @media (min-width: 768px) and (max-width: 1023px) {
            .brand-line-1 {
                font-size: 1rem;
                font-weight: 600;
            }
            
            .brand-line-2 {
                font-size: 1.5rem;
                font-weight: 800;
            }
        }
        
        /* Mobile Brand Styling - Enhanced visibility */
        @media (max-width: 767px) {
            .brand {
                gap: 0.6rem;
            }
            
            .brand-line-1 {
                font-size: 0.9rem;
                font-weight: 600;
                letter-spacing: 0.4px;
            }
            
            .brand-line-2 {
                font-size: 1.3rem;
                font-weight: 800;
                letter-spacing: -0.01em;
            }
        }
        
        /* Small Mobile Optimization */
        @media (max-width: 480px) {
            .brand-line-1 {
                font-size: 0.85rem;
                font-weight: 600;
            }
            
            .brand-line-2 {
                font-size: 1.2rem;
                font-weight: 800;
            }
        }
        
        .brand-tagline {
            font-size: 0.7rem;
            color: var(--color-gray-600);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.15rem;
            display: none;
        }
        
        @media (min-width: 768px) {
            .brand-tagline {
                display: block;
            }
        }
        
        /* Header Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }
        
        /* Primary CTA with Enhanced Hover States */
        .primary-cta {
            padding: 0.6rem 1.25rem;
            background: var(--color-primary);
            color: var(--color-white);
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all var(--transition);
            white-space: nowrap;
            line-height: 1;
            position: relative;
            overflow: hidden;
        }
        
        @media (max-width: 1023px) {
            .primary-cta {
                display: none;
            }
        }
        
        .primary-cta:after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .primary-cta:hover {
            background: var(--color-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(93, 74, 138, 0.25);
        }
        
        .primary-cta:hover:after {
            left: 100%;
        }
        
        .primary-cta:active {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(93, 74, 138, 0.2);
        }
        
        /* Search Button - Visible on ALL devices */
        .search-btn {
            width: 44px;
            height: 44px;
            border: 1px solid var(--color-gray-300);
            border-radius: 4px;
            background: var(--color-white);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition);
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        
        .search-btn:hover {
            border-color: var(--color-primary);
            background: var(--color-primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(93, 74, 138, 0.15);
        }
        
        .search-btn:hover i {
            color: var(--color-white);
        }
        
        .search-btn i {
            color: var(--color-gray-600);
            font-size: 1.1rem;
            transition: color var(--transition);
        }
        
        /* Mobile Apply Button */
        .mobile-apply-btn {
            width: 44px;
            height: 44px;
            background: var(--color-primary);
            color: var(--color-white);
            border: none;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition);
            position: relative;
            flex-shrink: 0;
            text-decoration: none;
        }
        
        @media (min-width: 768px) {
            .mobile-apply-btn {
                display: none;
            }
        }
        
        .mobile-apply-btn:hover {
            background: var(--color-primary-dark);
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(93, 74, 138, 0.2);
        }
        
        /* Apply Tooltip for Mobile */
        .apply-tooltip {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%) translateY(-5px);
            background: var(--color-gray-800);
            color: var(--color-white);
            padding: 0.4rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition);
            z-index: 100;
            pointer-events: none;
        }
        
        .apply-tooltip:after {
            content: '';
            position: absolute;
            top: -4px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 0 4px 4px 4px;
            border-style: solid;
            border-color: transparent transparent var(--color-gray-800) transparent;
        }
        
        .mobile-apply-btn:hover .apply-tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
        
        /* Enhanced Menu Toggle with MENU Label */
        .menu-toggle-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            cursor: pointer;
            flex-shrink: 0;
        }
        
        .menu-toggle {
            width: 44px;
            height: 44px;
            border: 1px solid var(--color-gray-300);
            border-radius: 4px;
            background: var(--color-white);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 8px;
            transition: all var(--transition);
        }
        
        .menu-toggle:hover {
            border-color: var(--color-gray-400);
            background: var(--color-gray-50);
        }
        
        .menu-toggle span {
            display: block;
            width: 20px;
            height: 2px;
            background: var(--color-gray-700);
            transition: all var(--transition);
        }
        
        .menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(4px, 4px);
        }
        
        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        
        .menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(4px, -4px);
        }
        
        /* MENU Label */
        .menu-label {
            font-size: 0.7rem;
            color: var(--color-gray-600);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: -2px;
        }
        
        @media (min-width: 1024px) {
            .menu-toggle-wrapper {
                display: none;
            }
        }
        
        /* User Menu - Enhanced */
        .user-menu {
            position: relative;
        }
        
        .user-btn {
            padding: 0.5rem 1rem;
            background: var(--color-primary);
            color: var(--color-white);
            border: none;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all var(--transition);
            white-space: nowrap;
            line-height: 1;
        }
        
        .user-btn:hover {
            background: var(--color-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(93, 74, 138, 0.2);
        }
        
        .user-avatar {
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* User Dropdown */
        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--color-white);
            min-width: 240px;
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all var(--transition);
            z-index: 100;
            border: 1px solid var(--color-gray-200);
            margin-top: 0.5rem;
        }
        
        .user-menu:hover .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .user-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--color-gray-200);
            background: var(--color-gray-50);
            border-radius: 8px 8px 0 0;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--color-gray-800);
            margin-bottom: 0.25rem;
        }
        
        .user-role {
            font-size: 0.8rem;
            color: var(--color-gray-600);
            text-transform: capitalize;
        }
        
        .user-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: var(--color-gray-700);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all var(--transition);
            border-left: 3px solid transparent;
        }
        
        .user-item:hover {
            background: rgba(93, 74, 138, 0.05);
            color: var(--color-primary);
            border-left-color: var(--color-primary-light);
        }
        
        .user-item.logout:hover {
            background: rgba(220, 38, 38, 0.05);
            color: #dc2626;
            border-left-color: #dc2626;
        }
        
        /* Responsive adjustments for user button */
        @media (max-width: 767px) {
            .user-btn span {
                display: none;
            }
            
            .user-btn {
                padding: 0.6rem;
                width: 44px;
                height: 44px;
                justify-content: center;
            }
        }
        
        /* Main Navigation - Desktop with Enhanced States */
        .nav-container {
            display: none;
            position: relative;
        }
        
        @media (min-width: 1024px) {
            .nav-container {
                display: block;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--color-white);
                border-top: 1px solid var(--color-gray-200);
                box-shadow: var(--shadow-sm);
            }
            
            .nav-menu {
                display: flex;
                list-style: none;
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 var(--container-padding-desktop);
                height: 56px;
            }
            
            .nav-item {
                position: relative;
            }
            
            .nav-link {
                display: flex;
                align-items: center;
                height: 56px;
                padding: 0 1.5rem;
                color: var(--color-gray-700);
                text-decoration: none;
                font-weight: 600;
                font-size: 0.95rem;
                transition: all var(--transition);
                border-bottom: 3px solid transparent;
                white-space: nowrap;
                position: relative;
            }
            
            .nav-link:before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                width: 0;
                height: 3px;
                background: var(--color-primary);
                transition: all var(--transition);
                transform: translateX(-50%);
            }
            
            .nav-link:hover {
                color: var(--color-primary);
                background: rgba(93, 74, 138, 0.05);
            }
            
            .nav-link:hover:before {
                width: 100%;
            }
            
            .nav-link.active {
                color: var(--color-primary);
                border-bottom-color: var(--color-accent);
                background: rgba(93, 74, 138, 0.03);
            }
            
            .nav-link.active:before {
                display: none;
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
                box-shadow: var(--shadow-lg);
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: all var(--transition);
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
                transition: all var(--transition);
                border-left: 3px solid transparent;
            }
            
            .dropdown-link:hover {
                background: rgba(93, 74, 138, 0.05);
                color: var(--color-primary);
                border-left-color: var(--color-accent);
                padding-left: 1.75rem;
            }
        }
        
        /* Enhanced Mobile Navigation with Gradient Background */
        .mobile-nav {
            position: fixed;
            top: var(--header-height-mobile);
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                var(--color-white) 0%,
                var(--color-primary-very-light) 50%,
                rgba(248, 246, 252, 0.95) 100%
            );
            z-index: 999;
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1.5rem var(--container-padding-mobile);
            border-left: 1px solid var(--color-gray-200);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (min-width: 1024px) {
            .mobile-nav {
                display: none;
            }
        }
        
        .mobile-nav.active {
            transform: translateX(0);
        }
        
        .mobile-nav-header {
            padding: 1rem 0 1.5rem 0;
            border-bottom: 1px solid var(--color-gray-200);
            margin-bottom: 1.5rem;
        }
        
        .mobile-nav-title {
            font-family: var(--font-heading);
            font-weight: 600;
            color: var(--color-primary);
            font-size: 1.25rem;
            line-height: 1.3;
        }
        
        .mobile-nav-menu {
            list-style: none;
            margin-bottom: 2rem;
        }
        
        .mobile-nav-item {
            border-bottom: 1px solid var(--color-gray-200);
            transition: background-color var(--transition);
        }
        
        .mobile-nav-item:hover {
            background: rgba(93, 74, 138, 0.05);
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
            transition: all var(--transition);
            position: relative;
            padding-left: 1rem;
        }
        
        .mobile-nav-link:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 4px;
            height: 0;
            background: var(--color-primary);
            transform: translateY(-50%);
            transition: height var(--transition);
            border-radius: 0 2px 2px 0;
        }
        
        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            color: var(--color-primary);
            padding-left: 1.5rem;
        }
        
        .mobile-nav-link:hover:before,
        .mobile-nav-link.active:before {
            height: 60%;
        }
        
        .mobile-nav-link.active {
            font-weight: 600;
            color: var(--color-primary-dark);
        }
        
        /* Enhanced Mobile Navigation Footer */
        .mobile-nav-footer {
            padding: 1.5rem;
            margin-top: 1rem;
            border: 1px solid var(--color-gray-100);
            border-radius: 8px;
            background: rgba(93, 74, 138, 0.02);
        }
        
        .mobile-nav-contact {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .mobile-contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--color-gray-600);
            text-decoration: none;
            font-size: 0.875rem;
            transition: all var(--transition);
            padding: 0.5rem;
            border-radius: 6px;
        }
        
        .mobile-contact-item:hover {
            background: rgba(93, 74, 138, 0.05);
            color: var(--color-primary);
            transform: translateX(4px);
        }
        
        .mobile-nav-cta {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        /* STUDENT PORTAL BUTTON for mobile nav - Enhanced color */
        .mobile-nav-cta .student-portal {
            background: var(--color-accent);
            color: var(--color-gray-900);
            border: 1px solid var(--color-accent);
        }
        
        .mobile-nav-cta .student-portal:hover {
            background: var(--color-accent-dark);
            color: var(--color-gray-900);
            border-color: var(--color-accent-dark);
        }
        
        /* Enhanced Mobile Top Bar */
        .mobile-top-bar {
            display: none;
            background: linear-gradient(
                to right,
                var(--color-primary) 0%,
                var(--color-primary-dark) 100%
            );
            color: var(--color-white);
            padding: 0.75rem var(--container-padding-mobile);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }
        
        @media (max-width: 1023px) {
            .mobile-top-bar {
                display: block;
                position: fixed;
                top: var(--header-height-mobile);
                left: 0;
                right: 0;
                z-index: 998;
            }
            
            .mobile-top-bar-container {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                justify-content: center;
                max-width: 1400px;
                margin: 0 auto;
            }
            
            .mobile-top-link {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: rgba(255, 255, 255, 0.9);
                text-decoration: none;
                font-size: 0.8rem;
                white-space: nowrap;
                transition: all var(--transition);
                padding: 0.3rem 0.8rem;
                border-radius: 20px;
            }
            
            .mobile-top-link:hover {
                background: rgba(255, 255, 255, 0.1);
                color: var(--color-white);
                transform: translateY(-1px);
            }
            
            /* STUDENT PORTAL for mobile top bar */
            .mobile-top-link.student-portal {
                background: var(--color-accent);
                color: var(--color-gray-900);
                padding: 0.3rem 0.8rem;
                border-radius: 4px;
                font-weight: 600;
            }
            
            .mobile-top-link.student-portal:hover {
                background: var(--color-accent-dark);
                color: var(--color-gray-900);
                box-shadow: 0 2px 8px rgba(212, 165, 116, 0.3);
            }
            
            /* Set body padding for mobile to prevent content under header */
            body {
                padding-top: calc(var(--header-height-mobile) + 60px);
            }
            
            .mobile-nav {
                top: calc(var(--header-height-mobile) + 60px);
            }
            
            .flash-messages {
                top: calc(var(--header-height-mobile) + 60px);
            }
            
            /* Ensure main content is properly positioned */
            .main-content {
                margin-top: 0;
            }
        }
        
        /* Desktop adjustments - Header stays in its own space */
        @media (min-width: 1024px) {
            /* Set body padding to push content below fixed header */
            body {
                padding-top: calc(var(--header-height-desktop) + var(--top-bar-height) + var(--nav-height-desktop));
            }
            
            /* Remove any margin-top from main content */
            .main-content {
                margin-top: 0;
            }
        }
        
        /* Search Modal - Enhanced for Mobile */
        .search-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1100;
            display: none;
            align-items: flex-start;
            justify-content: center;
            padding-top: 20vh;
            backdrop-filter: blur(4px);
        }
        
        @media (max-width: 767px) {
            .search-modal {
                padding-top: 15vh;
            }
        }
        
        .search-modal.active {
            display: flex;
        }
        
        .search-modal-content {
            background: var(--color-white);
            width: 90%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            padding: 1.5rem;
            position: relative;
        }
        
        .search-modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--color-gray-500);
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all var(--transition);
        }
        
        .search-modal-close:hover {
            background: var(--color-gray-100);
            color: var(--color-gray-700);
        }
        
        .search-modal-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--color-gray-300);
            border-radius: 6px;
            font-size: 1rem;
            font-family: var(--font-primary);
            transition: all var(--transition);
            background: var(--color-white);
        }
        
        .search-modal-input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(93, 74, 138, 0.1);
        }
        
        .search-modal-icon {
            position: absolute;
            left: 2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-gray-500);
            font-size: 1.125rem;
        }
        
        /* Enhanced Focus States for Accessibility */
        .nav-link:focus,
        .primary-cta:focus,
        .user-btn:focus,
        .search-btn:focus,
        .mobile-nav-link:focus,
        .mobile-top-link:focus,
        .mobile-contact-item:focus,
        .search-modal-close:focus,
        .search-modal-input:focus {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
            border-radius: 2px;
        }
        
        /* Responsive Button Adjustments */
        @media (min-width: 768px) and (max-width: 1023px) {
            .primary-cta {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
            }
        }
        
        /* Content wrapper for proper spacing */
        .content-wrapper {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 var(--container-padding-mobile);
        }
        
        @media (min-width: 768px) {
            .content-wrapper {
                padding: 0 var(--container-padding-tablet);
            }
        }
        
        @media (min-width: 1024px) {
            .content-wrapper {
                padding: 0 var(--container-padding-desktop);
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

<!-- Search Modal (Visible on ALL devices) -->
<div class="search-modal" id="searchModal">
    <div class="search-modal-content">
        <button class="search-modal-close" aria-label="Close search">
            &times;
        </button>
        <form action="<?php echo $baseUrl; ?>/search" method="GET" class="search-form">
            <div style="position: relative;">
                <i class="fas fa-search search-modal-icon"></i>
                <input type="search" 
                       name="q" 
                       class="search-modal-input" 
                       placeholder="Search courses, faculty, research..."
                       aria-label="Search the website">
            </div>
        </form>
    </div>
</div>

<!-- Top Bar - Desktop Only -->
<div class="top-bar">
    <div class="top-bar-container">
        <div class="top-bar-left">
            <a href="tel:+23492900000" class="top-bar-link">
                <i class="fas fa-phone"></i>
                <span>+234 808 277 5076</span>
            </a>
            <a href="mailto:info@fctcns.edu.ng" class="top-bar-link">
                <i class="fas fa-envelope"></i>
                <span>info@fctcns.edu.ng</span>
            </a>
        </div>
        <div class="top-bar-right">
            <a href="<?php echo $baseUrl; ?>/admissions" class="top-bar-action">Apply Now</a>
            <a href="<?php echo $baseUrl; ?>/alumni" class="top-bar-action">Alumni</a>
            <!-- STUDENT PORTAL - Enhanced with accent color -->
            <a href="<?php echo $baseUrl; ?>/student-life" class="top-bar-action student-portal">
                <i class="fas fa-graduation-cap"></i>
                <span>Student Portal</span>
            </a>
        </div>
    </div>
</div>

<!-- Enhanced Mobile Top Bar (shown on mobile devices) -->
<div class="mobile-top-bar">
    <div class="mobile-top-bar-container">
        <a href="tel:+23492900000" class="mobile-top-link">
            <i class="fas fa-phone"></i>
            <span>Call</span>
        </a>
        <a href="mailto:info@fctcns.edu.ng" class="mobile-top-link">
            <i class="fas fa-envelope"></i>
            <span>Email</span>
        </a>
        <a href="<?php echo $baseUrl; ?>/admissions" class="mobile-top-link">
            <i class="fas fa-file-alt"></i>
            <span>Apply</span>
        </a>
        <a href="<?php echo $baseUrl; ?>/alumni" class="mobile-top-link">
            <i class="fas fa-graduation-cap"></i>
            <span>Alumni</span>
        </a>
        <!-- STUDENT PORTAL for mobile -->
        <a href="<?php echo $baseUrl; ?>/student-life" class="mobile-top-link student-portal">
            <i class="fas fa-graduation-cap"></i>
            <span>Student Portal</span>
        </a>
    </div>
</div>

<!-- Main Header -->
<header class="site-header" role="banner">
    <div class="header-container">
        <!-- Enhanced Brand with improved font styling -->
        <a href="<?php echo $baseUrl; ?>/" class="brand">
            <div class="brand-logo">
                <img src="<?php echo $baseUrl; ?>/assets/images/logo/logo.png" 
                     alt="FCT College of Nursing Sciences">
            </div>
            <div class="brand-text">
                <div class="brand-name">
                    <span class="brand-line-1">FCT College of</span>
                    <span class="brand-line-2">Nursing Sciences</span>
                </div>
                <div class="brand-tagline">Abuja, Nigeria</div>
            </div>
        </a>
        
        <!-- Header Actions -->
        <div class="header-actions">
            <!-- Search Button - Visible on ALL devices -->
            <button class="search-btn" aria-label="Search" onclick="openSearch()">
                <i class="fas fa-search"></i>
            </button>
            
            <!-- Apply Now Button - Desktop -->
            <a href="<?php echo $baseUrl; ?>/admissions" class="primary-cta">
                <i class="fas fa-file-alt"></i>
                <span>Apply Now</span>
            </a>
            
            <!-- Mobile Apply Button with Hover Tooltip -->
            <a href="<?php echo $baseUrl; ?>/admissions" class="mobile-apply-btn">
                <i class="fas fa-file-alt"></i>
                <span class="apply-tooltip">Apply</span>
            </a>
            
            <!-- User Menu (only when logged in) -->
            <?php if ($isLoggedIn): ?>
            <div class="user-menu">
                <button class="user-btn" aria-haspopup="true" aria-expanded="false">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($username, 0, 2)); ?>
                    </div>
                    <span>Account</span>
                </button>
                <div class="user-dropdown" role="menu">
                    <div class="user-header">
                        <div class="user-name"><?php echo htmlspecialchars($username); ?></div>
                        <div class="user-role"><?php echo ucfirst($userRole); ?></div>
                    </div>
                    <a href="<?php echo $baseUrl; ?>/student/dashboard" class="user-item" role="menuitem">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Student Dashboard</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/student/courses" class="user-item" role="menuitem">
                        <i class="fas fa-book"></i>
                        <span>My Courses</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/student/grades" class="user-item" role="menuitem">
                        <i class="fas fa-chart-line"></i>
                        <span>Grades</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/student/profile" class="user-item" role="menuitem">
                        <i class="fas fa-user-cog"></i>
                        <span>Profile Settings</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/student/logout" class="user-item logout" role="menuitem">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Enhanced Menu Toggle with MENU Label -->
            <div class="menu-toggle-wrapper" onclick="toggleMobileMenu()">
                <div class="menu-toggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="menu-label">MENU</div>
            </div>
        </div>
    </div>
    
    <!-- Desktop Navigation with Research Tab -->
    <nav class="nav-container" aria-label="Main navigation">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/" 
                   class="nav-link <?php echo ($currentPage == 'home' || $currentPage == '') ? 'active' : ''; ?>">
                    Home
                </a>
            </li>
            <li class="nav-item has-dropdown">
                <a href="<?php echo $baseUrl; ?>/about" 
                   class="nav-link <?php echo $currentPage == 'about' ? 'active' : ''; ?>">
                    About
                </a>
                <div class="dropdown-menu">
                    <a href="<?php echo $baseUrl; ?>/about/leadership" class="dropdown-link">Leadership</a>
                    <a href="<?php echo $baseUrl; ?>/about/history" class="dropdown-link">History</a>
                    <a href="<?php echo $baseUrl; ?>/about/mission" class="dropdown-link">Mission & Values</a>
                    <a href="<?php echo $baseUrl; ?>/about/accreditation" class="dropdown-link">Accreditation</a>
                </div>
            </li>
            <li class="nav-item has-dropdown">
                <a href="<?php echo $baseUrl; ?>/programs" 
                   class="nav-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>">
                    Academic Programs
                </a>
                <div class="dropdown-menu">
                    <a href="<?php echo $baseUrl; ?>/programs/undergraduate" class="dropdown-link">Undergraduate</a>
                    <a href="<?php echo $baseUrl; ?>/programs/graduate" class="dropdown-link">Graduate</a>
                    <a href="<?php echo $baseUrl; ?>/programs/continuing-education" class="dropdown-link">Continuing Education</a>
                </div>
            </li>
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/admissions" 
                   class="nav-link <?php echo $currentPage == 'admissions' ? 'active' : ''; ?>">
                    Admissions
                </a>
            </li>
            <!-- ADDED RESEARCH TAB -->
            <li class="nav-item has-dropdown">
                <a href="<?php echo $baseUrl; ?>/research" 
                   class="nav-link <?php echo $currentPage == 'research' ? 'active' : ''; ?>">
                    Research
                </a>
                <div class="dropdown-menu">
                    <a href="<?php echo $baseUrl; ?>/research/publications" class="dropdown-link">Publications</a>
                    <a href="<?php echo $baseUrl; ?>/research/projects" class="dropdown-link">Research Projects</a>
                    <a href="<?php echo $baseUrl; ?>/research/facilities" class="dropdown-link">Research Facilities</a>
                    <a href="<?php echo $baseUrl; ?>/research/grants" class="dropdown-link">Grants & Funding</a>
                </div>
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
                    Faculty
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
    </nav>
</header>

<!-- Enhanced Mobile Navigation with Gradient Background -->
<div class="mobile-nav" id="mobileNav">
    <div class="mobile-nav-header">
        <div class="mobile-nav-title">FCT College of Nursing Sciences</div>
    </div>
    
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
                <span>About</span>
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
        <!-- ADDED RESEARCH TAB FOR MOBILE -->
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
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/alumni" 
               class="mobile-nav-link <?php echo $currentPage == 'alumni' ? 'active' : ''; ?>">
                <span>Alumni</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/contact" 
               class="mobile-nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
                <span>Contact</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
        <?php if ($isLoggedIn): ?>
        <li class="mobile-nav-item">
            <a href="<?php echo $baseUrl; ?>/student/dashboard" 
               class="mobile-nav-link">
                <span>Student Dashboard</span>
                <i class="fas fa-tachometer-alt"></i>
            </a>
        </li>
        <?php endif; ?>
    </ul>
    
    <div class="mobile-nav-footer">
        <div class="mobile-nav-contact">
            <a href="tel:+23492900000" class="mobile-contact-item">
                <i class="fas fa-phone"></i>
                <span>+234 (0) 9 290 0000</span>
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
        
        <div class="mobile-nav-cta">
            <a href="<?php echo $baseUrl; ?>/admissions" class="primary-cta">
                <i class="fas fa-file-alt"></i>
                <span>Apply Now</span>
            </a>
            <!-- STUDENT PORTAL for mobile nav - Enhanced color -->
            <a href="<?php echo $baseUrl; ?>/student-life" class="primary-cta student-portal">
                <i class="fas fa-graduation-cap"></i>
                <span>Student Portal</span>
            </a>
            <?php if ($isLoggedIn): ?>
            <a href="<?php echo $baseUrl; ?>/student/logout" class="primary-cta" style="background: #dc2626;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Main Content -->
<main class="main-content">

<script>
// Mobile menu functionality
function toggleMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileNav = document.getElementById('mobileNav');
    const body = document.body;
    
    menuToggle.classList.toggle('active');
    mobileNav.classList.toggle('active');
    
    if (mobileNav.classList.contains('active')) {
        body.style.overflow = 'hidden';
        menuToggle.setAttribute('aria-expanded', 'true');
    } else {
        body.style.overflow = '';
        menuToggle.setAttribute('aria-expanded', 'false');
    }
}

// Search modal functionality - Works on ALL devices
function openSearch() {
    const searchModal = document.getElementById('searchModal');
    const searchInput = searchModal.querySelector('input[type="search"]');
    
    searchModal.classList.add('active');
    searchInput.focus();
    
    // Close on escape
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

// Close search modal on background click
document.getElementById('searchModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSearch();
    }
});

// Close search modal on close button click
document.querySelector('.search-modal-close').addEventListener('click', closeSearch);

// Close flash messages
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
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const menuToggle = document.querySelector('.menu-toggle-wrapper');
        const mobileNav = document.getElementById('mobileNav');
        
        if (mobileNav.classList.contains('active') && 
            !menuToggle.contains(event.target) && 
            !mobileNav.contains(event.target)) {
            toggleMobileMenu();
        }
    });
    
    // Handle keyboard navigation for user dropdown
    const userBtn = document.querySelector('.user-btn');
    if (userBtn) {
        userBtn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    }
    
    // Handle search form submission
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const input = this.querySelector('input[type="search"]');
            if (!input.value.trim()) {
                e.preventDefault();
            }
        });
    }
});
</script>