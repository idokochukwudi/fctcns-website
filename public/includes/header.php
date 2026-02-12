<?php
/**
 * University Header Template - Prestige Edition
 * Professional Redesign with Elevated Typography, Refined Spacing, Enhanced Visual Hierarchy
 * FULL WIDTH VERSION - Premium Academic Institution Aesthetic
 * ENHANCED: Dramatically larger logo with proper scaling
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
    
    <!-- Google Fonts - Prestige Selection -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
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
           PREMIUM HEADER REDESIGN - PRESTIGE ACADEMIC EDITION
           ENHANCED: LARGER, MORE PROMINENT LOGO
           Refined Typography | Enhanced Spacing | Premium Visual Hierarchy
           Ivory Tower Aesthetic | Full Width Architecture
           ============================================== */
        
        :root {
            /* ===== PREMIUM COLOR PALETTE ===== */
            --prestige-navy: #0a2342;       /* Deep academic blue - primary */
            --prestige-navy-dark: #05182e;   /* Deeper navy for contrast */
            --prestige-navy-light: #1e3a5f;  /* Lighter navy for gradients */
            --prestige-gold: #aa8c54;        /* Refined gold - not too bright */
            --prestige-gold-light: #c4a77d;  /* Softer gold for accents */
            --prestige-gold-dark: #8a6e3f;   /* Deep gold for hover states */
            --prestige-cream: #faf7f2;       /* Warm white - easy on eyes */
            --prestige-ivory: #f5f0e8;       /* Slightly warmer cream */
            --prestige-charcoal: #2c3e4e;    /* Sophisticated dark gray */
            --prestige-slate: #5a6a7a;        /* Medium gray for text */
            --prestige-stone: #e8e6e1;        /* Light gray for borders */
            
            /* ===== ACCENT COLORS ===== */
            --prestige-burgundy: #8b3a3a;     /* Professional red accent */
            --prestige-burgundy-light: #a55858;
            --prestige-forest: #2c5f2d;       /* Optional green accent */
            
            /* ===== TYPOGRAPHY - PREMIUM FONTS ===== */
            --font-serif: 'Cormorant Garamond', 'Playfair Display', Georgia, serif;  /* Elegant serif for institution name */
            --font-sans: 'Montserrat', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            
            /* ===== SPACING - GOLDEN RATIO INSPIRED ===== */
            --space-xs: 0.5rem;   /* 8px */
            --space-sm: 0.75rem;  /* 12px */
            --space-md: 1rem;     /* 16px */
            --space-lg: 1.5rem;   /* 24px */
            --space-xl: 2rem;     /* 32px */
            --space-2xl: 3rem;    /* 48px */
            
            /* ===== HEADER DIMENSIONS ===== */
            --header-height: 100px;      /* Increased to accommodate larger logo */
            --nav-height: 56px;         /* Refined navigation height */
            
            /* ===== LOGO DIMENSIONS - SIGNIFICANTLY ENLARGED ===== */
            --logo-size-desktop: 100px;     /* Dramatically larger logo */
            --logo-size-desktop-large: 110px; /* Even larger on big screens */
            --logo-size-tablet: 85px;       /* Larger on tablets */
            --logo-size-mobile: 70px;       /* Larger on mobile */
            --logo-size-mobile-small: 60px; /* Still prominent on small devices */
            
            /* ===== CONTAINER PADDING - RESPONSIVE ===== */
            --container-padding-mobile: 1.25rem;
            --container-padding-tablet: 2rem;
            --container-padding-desktop: 3rem;
            --container-padding-wide: 4rem;
            
            /* ===== ELEVATION & SHADOWS ===== */
            --shadow-subtle: 0 2px 8px rgba(10, 35, 66, 0.04);
            --shadow-medium: 0 8px 20px rgba(10, 35, 66, 0.06);
            --shadow-elevated: 0 16px 32px rgba(10, 35, 66, 0.08);
            --shadow-gold: 0 4px 12px rgba(170, 140, 84, 0.15);
            
            /* ===== TRANSITIONS ===== */
            --transition-fast: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-premium: 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); /* Bounce effect */
            
            /* ===== BORDERS & RADII ===== */
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --border-thin: 1px;
            --border-medium: 2px;
            --border-bold: 3px;
        }
        
        /* ==========================================================================
           CRITICAL: FULL WIDTH ARCHITECTURE - NO CONSTRAINTS
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
            background: white;
            font-family: var(--font-body);
            color: var(--prestige-charcoal);
            line-height: 1.6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ==========================================================================
           PREMIUM HEADER - IVORY TOWER AESTHETIC
           ========================================================================== */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border-bottom: var(--border-thin) solid var(--prestige-stone);
            height: var(--header-height);
            width: 100vw;
            max-width: 100vw;
            box-shadow: var(--shadow-subtle);
            transition: all var(--transition-smooth);
        }

        .site-header.scrolled {
            height: 90px; /* Reduced when scrolled but still accommodates logo */
            box-shadow: var(--shadow-medium);
            border-bottom-color: var(--prestige-gold-light);
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 var(--container-padding-desktop);
            margin: 0;
            width: 100%;
            max-width: 100vw;
            transition: padding var(--transition-smooth);
        }

        /* ==========================================================================
           PREMIUM BRANDING - HERITAGE TYPOGRAPHY WITH ENLARGED LOGO
           ========================================================================== */
        .brand {
            display: flex;
            align-items: center;
            gap: 1.8rem; /* Increased gap for larger logo */
            text-decoration: none;
            color: inherit;
            min-width: 0;
            flex-shrink: 0;
            transition: gap var(--transition-smooth);
        }

        /* DRAMATICALLY ENLARGED CREST-STYLE LOGO CONTAINER */
        .brand-logo {
            width: var(--logo-size-desktop);
            height: var(--logo-size-desktop);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 50%; /* Circular crest-like shape */
            border: var(--border-bold) solid var(--prestige-gold);
            overflow: hidden;
            transition: all var(--transition-premium);
            box-shadow: 0 0 0 3px white, 0 0 0 6px rgba(170, 140, 84, 0.1);
        }

        .brand:hover .brand-logo {
            transform: scale(1.03);
            border-color: var(--prestige-gold-dark);
            box-shadow: 0 0 0 3px white, 0 0 0 10px rgba(170, 140, 84, 0.15);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 15px; /* Optimal padding for logo visibility */
            display: block;
            transition: transform var(--transition-smooth);
        }

        .brand:hover .brand-logo img {
            transform: scale(1.08);
        }

        .brand-logo .logo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--prestige-navy), var(--prestige-navy-light));
            color: var(--prestige-gold);
            font-family: var(--font-serif);
            font-weight: 700;
            font-size: 1.8rem; /* Larger fallback text */
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .brand-name {
            font-family: var(--font-serif);
            line-height: 1.1;
            display: flex;
            flex-direction: column;
        }

        /* REFINED TYPOGRAPHY HIERARCHY */
        .brand-line-1 {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--prestige-slate);
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-family: var(--font-sans);
        }

        .brand-line-2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--prestige-navy);
            letter-spacing: -0.5px;
            font-family: var(--font-serif);
            line-height: 1;
            position: relative;
            display: inline-block;
        }

        /* ELEGANT GOLD UNDERLINE - SIGNATURE ELEMENT */
        .brand-line-2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 70px;
            height: 3px;
            background: linear-gradient(90deg, var(--prestige-gold), var(--prestige-gold-light));
            border-radius: 3px;
            transition: width var(--transition-smooth);
        }

        .brand:hover .brand-line-2::after {
            width: 120px;
        }

        /* ==========================================================================
           HEADER ACTIONS - REFINED BUTTONS
           ========================================================================== */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        /* PREMIUM APPLY BUTTON */
        .apply-btn {
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, var(--prestige-navy), var(--prestige-navy-light));
            color: white;
            border: none;
            border-radius: 40px; /* Pill shape */
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all var(--transition-smooth);
            white-space: nowrap;
            font-family: var(--font-sans);
            letter-spacing: 0.5px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow-subtle);
        }

        .apply-btn i {
            font-size: 0.95rem;
        }

        .apply-btn:hover {
            background: linear-gradient(135deg, var(--prestige-navy-dark), var(--prestige-navy));
            transform: translateY(-3px);
            box-shadow: var(--shadow-gold);
            border-color: var(--prestige-gold-light);
        }

        /* ELEGANT USER BUTTON - ENLARGED TO MATCH LOGO SCALE */
        .user-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%; /* Circular to match logo */
            background: var(--prestige-ivory);
            color: var(--prestige-navy);
            border: 2px solid var(--prestige-gold-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all var(--transition-smooth);
            text-decoration: none;
        }

        .user-btn:hover {
            background: var(--prestige-navy);
            color: white;
            border-color: var(--prestige-gold);
            transform: translateY(-3px);
            box-shadow: var(--shadow-gold);
        }

        /* ==========================================================================
           REFINED MOBILE MENU TOGGLE - ENLARGED
           ========================================================================== */
        .mobile-menu-toggle-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .mobile-menu-toggle {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: 2px solid var(--prestige-stone);
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all var(--transition-smooth);
        }

        .mobile-menu-toggle:hover {
            border-color: var(--prestige-gold);
            background: var(--prestige-ivory);
            transform: translateY(-2px);
        }

        .mobile-menu-toggle span {
            display: block;
            width: 24px;
            height: 2.5px;
            background: var(--prestige-navy);
            transition: all var(--transition-smooth);
            border-radius: 3px;
        }

        .mobile-menu-toggle.active {
            background: var(--prestige-navy);
            border-color: var(--prestige-navy);
        }

        .mobile-menu-toggle.active span {
            background: white;
        }

        .mobile-menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
            transform: translateX(-10px);
        }

        .mobile-menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        .menu-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--prestige-slate);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: color var(--transition-smooth);
        }

        .mobile-menu-toggle.active + .menu-label {
            color: var(--prestige-navy);
        }

        /* ==========================================================================
           DESKTOP NAVIGATION - ACADEMIC PRESTIGE
           ========================================================================== */
        .desktop-nav-container {
            position: fixed;
            top: var(--header-height);
            left: 0;
            right: 0;
            z-index: 999;
            background: white;
            border-bottom: var(--border-thin) solid var(--prestige-stone);
            height: var(--nav-height);
            width: 100vw;
            max-width: 100vw;
            box-shadow: 0 2px 4px rgba(10, 35, 66, 0.02);
            transition: all var(--transition-smooth);
        }

        .desktop-nav-container.scrolled {
            box-shadow: var(--shadow-subtle);
            border-bottom-color: var(--prestige-gold);
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
            padding: 0 var(--container-padding-desktop);
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 100vw;
            gap: 0.5rem;
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
            padding: 0 1.25rem;
            color: var(--prestige-charcoal);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all var(--transition-smooth);
            position: relative;
            font-family: var(--font-sans);
            white-space: nowrap;
            letter-spacing: 0.3px;
        }

        /* ELEGANT HOVER EFFECT */
        .desktop-nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--prestige-gold), var(--prestige-gold-light));
            transition: width var(--transition-smooth);
            border-radius: 3px 3px 0 0;
        }

        .desktop-nav-link:hover::before,
        .desktop-nav-link.active::before {
            width: 80%;
        }

        .desktop-nav-link:hover {
            color: var(--prestige-navy);
            background: rgba(10, 35, 66, 0.02);
        }

        .desktop-nav-link.active {
            color: var(--prestige-navy);
            font-weight: 600;
        }

        /* PREMIUM DROPDOWN MENUS */
        .has-dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% - 1px);
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
            background: white;
            min-width: 260px;
            border-radius: 0 0 12px 12px;
            box-shadow: var(--shadow-elevated);
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-smooth);
            z-index: 100;
            border: 1px solid var(--prestige-stone);
            border-top: 3px solid var(--prestige-gold);
            padding: 0.75rem 0;
        }

        .has-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .dropdown-link {
            display: block;
            padding: 0.8rem 2rem;
            color: var(--prestige-charcoal);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all var(--transition-fast);
            position: relative;
            white-space: nowrap;
        }

        .dropdown-link:hover {
            background: rgba(170, 140, 84, 0.05);
            color: var(--prestige-navy);
            padding-left: 2.5rem;
        }

        .dropdown-link::before {
            content: '→';
            position: absolute;
            left: 1.2rem;
            opacity: 0;
            transition: all var(--transition-fast);
            color: var(--prestige-gold);
        }

        .dropdown-link:hover::before {
            opacity: 1;
            left: 1.5rem;
        }

        /* DISTINCTIVE CONTACT TAB */
        .desktop-nav-item.contact-tab .desktop-nav-link {
            color: var(--prestige-burgundy);
            background: rgba(139, 58, 58, 0.03);
            border-left: 1px solid rgba(139, 58, 58, 0.1);
            border-right: 1px solid rgba(139, 58, 58, 0.1);
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .desktop-nav-item.contact-tab .desktop-nav-link i {
            color: var(--prestige-burgundy);
            transition: all var(--transition-smooth);
        }

        .desktop-nav-item.contact-tab .desktop-nav-link:hover {
            background: var(--prestige-burgundy);
            color: white;
        }

        .desktop-nav-item.contact-tab .desktop-nav-link:hover i {
            color: white;
        }

        .desktop-nav-item.contact-tab .desktop-nav-link::before {
            background: linear-gradient(90deg, var(--prestige-burgundy), #c17e7e);
        }

        /* ==========================================================================
           PREMIUM MOBILE NAVIGATION OVERLAY
           ========================================================================== */
        .mobile-nav-overlay {
            position: fixed;
            top: var(--header-height);
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transform: translateX(100%);
            transition: all var(--transition-smooth);
            overflow-y: auto;
            overflow-x: hidden;
            padding: 2.5rem 2rem;
            width: 100vw;
            max-width: 100vw;
        }

        .mobile-nav-overlay.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        .mobile-nav-content {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .mobile-nav-menu {
            list-style: none;
            margin-bottom: 2.5rem;
        }

        .mobile-nav-item {
            border-bottom: 1px solid var(--prestige-stone);
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.3rem 0;
            color: var(--prestige-charcoal);
            text-decoration: none;
            font-weight: 500;
            font-size: 1.15rem;
            transition: all var(--transition-fast);
        }

        .mobile-nav-link span i {
            width: 28px;
            color: var(--prestige-gold);
            margin-right: 12px;
        }

        .mobile-nav-link:hover {
            color: var(--prestige-navy);
            padding-left: 0.75rem;
        }

        .mobile-nav-link.active {
            color: var(--prestige-navy);
            font-weight: 700;
            border-left: 3px solid var(--prestige-gold);
            padding-left: 1rem;
        }

        .mobile-nav-item.contact-tab .mobile-nav-link {
            color: var(--prestige-burgundy);
            background: rgba(139, 58, 58, 0.03);
            padding: 1.3rem 1rem;
            margin: 0.75rem 0;
            border-radius: 12px;
            border: 1px solid rgba(139, 58, 58, 0.1);
            font-weight: 600;
        }

        .mobile-nav-item.contact-tab .mobile-nav-link:hover {
            background: var(--prestige-burgundy);
            color: white;
        }

        .mobile-nav-item.contact-tab .mobile-nav-link:hover span i {
            color: white;
        }

        .mobile-quick-actions {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            margin-bottom: 2.5rem;
        }

        .mobile-action-btn {
            padding: 1.3rem 1.5rem;
            border-radius: 12px;
            background: var(--prestige-navy);
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all var(--transition-smooth);
            font-weight: 600;
            justify-content: center;
            border: none;
            font-size: 1.05rem;
        }

        .mobile-action-btn i {
            font-size: 1.15rem;
        }

        .mobile-action-btn:hover {
            background: var(--prestige-navy-light);
            transform: translateY(-3px);
            box-shadow: var(--shadow-gold);
        }

        .mobile-action-btn.accent {
            background: var(--prestige-gold);
            color: var(--prestige-navy);
        }

        .mobile-action-btn.accent:hover {
            background: var(--prestige-gold-light);
        }

        .mobile-contact-info {
            padding-top: 2.5rem;
            border-top: 1px solid var(--prestige-stone);
        }

        .mobile-contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.2rem;
        }

        .mobile-contact-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            color: var(--prestige-slate);
            text-decoration: none;
            font-size: 1rem;
            padding: 0.85rem;
            border-radius: 8px;
            transition: all var(--transition-fast);
            border: 1px solid transparent;
        }

        .mobile-contact-item i {
            color: var(--prestige-gold);
            width: 22px;
            font-size: 1.1rem;
        }

        .mobile-contact-item:hover {
            background: var(--prestige-ivory);
            border-color: var(--prestige-gold-light);
            color: var(--prestige-navy);
        }

        /* ==========================================================================
           FLASH MESSAGES - ELEGANT NOTIFICATIONS
           ========================================================================== */
        .flash-messages {
            position: fixed;
            top: calc(var(--header-height) + 1rem);
            left: 0;
            right: 0;
            z-index: 1001;
            padding: 0 2rem;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .flash-message {
            background: white;
            border-left: 4px solid var(--prestige-gold);
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 0.5rem;
            box-shadow: var(--shadow-medium);
            animation: slideDown 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: auto;
            max-width: 600px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .flash-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            color: var(--prestige-slate);
            padding: 0 0.5rem;
            transition: all var(--transition-fast);
        }

        .flash-close:hover {
            color: var(--prestige-burgundy);
            transform: scale(1.1);
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* ==========================================================================
           MAIN CONTENT ARCHITECTURE
           ========================================================================== */
        .main-content-wrapper {
            flex: 1;
            width: 100%;
            max-width: 100vw;
            margin-top: 0;
            padding-top: var(--header-height);
            overflow-x: hidden;
        }

        .desktop-nav-container + .main-content-wrapper {
            padding-top: calc(var(--header-height) + var(--nav-height));
        }

        .main-content {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 var(--container-padding-desktop);
        }

        /* Full width content support */
        .main-content:has(.full-width-content) {
            padding: 0 !important;
            max-width: 100vw !important;
        }

        /* ==========================================================================
           RESPONSIVE ARCHITECTURE - ENHANCED LOGO SCALING
           ========================================================================== */
        
        /* Extra Large Desktop (1400px+) */
        @media (min-width: 1400px) {
            :root {
                --header-height: 110px;
                --nav-height: 60px;
                --logo-size-desktop: var(--logo-size-desktop-large);
            }
            
            .header-container {
                padding: 0 var(--container-padding-wide);
            }
            
            .brand {
                gap: 2.2rem;
            }
            
            .brand-logo {
                width: 110px;
                height: 110px;
            }
            
            .brand-logo img {
                padding: 18px;
            }
            
            .brand-line-1 {
                font-size: 1rem;
                letter-spacing: 3px;
            }
            
            .brand-line-2 {
                font-size: 2.6rem;
            }
            
            .brand-line-2::after {
                width: 80px;
                height: 3px;
            }
            
            .desktop-nav-menu {
                padding: 0 var(--container-padding-wide);
            }
            
            .desktop-nav-link {
                padding: 0 1.6rem;
                font-size: 1.05rem;
            }
        }

        /* Large Desktop (1200px - 1399px) */
        @media (min-width: 1200px) and (max-width: 1399px) {
            :root {
                --header-height: 105px;
                --logo-size-desktop: 100px;
            }
            
            .header-container {
                padding: 0 var(--container-padding-desktop);
            }
            
            .brand {
                gap: 2rem;
            }
            
            .brand-logo {
                width: 100px;
                height: 100px;
            }
            
            .brand-logo img {
                padding: 16px;
            }
            
            .brand-line-2 {
                font-size: 2.4rem;
            }
            
            .desktop-nav-menu {
                padding: 0 var(--container-padding-desktop);
            }
        }

        /* Desktop (1024px - 1199px) */
        @media (min-width: 1024px) and (max-width: 1199px) {
            :root {
                --header-height: 100px;
                --logo-size-desktop: 90px;
            }
            
            .header-container {
                padding: 0 var(--container-padding-desktop);
            }
            
            .brand {
                gap: 1.8rem;
            }
            
            .brand-logo {
                width: 90px;
                height: 90px;
            }
            
            .brand-logo img {
                padding: 14px;
            }
            
            .brand-line-1 {
                font-size: 0.85rem;
            }
            
            .brand-line-2 {
                font-size: 2.1rem;
            }
            
            .desktop-nav-link {
                padding: 0 1.1rem;
                font-size: 0.92rem;
            }
            
            .apply-btn {
                padding: 0.7rem 1.6rem;
            }
        }

        /* Desktop & Tablet (1024px and up) - Show desktop nav */
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
            
            .main-content-wrapper {
                padding-top: calc(var(--header-height) + var(--nav-height));
            }
        }

        /* Tablet (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            :root {
                --header-height: 90px;
                --logo-size-tablet: 85px;
                --container-padding-desktop: var(--container-padding-tablet);
            }
            
            .header-container {
                padding: 0 var(--container-padding-tablet);
            }
            
            .brand {
                gap: 1.5rem;
            }
            
            .brand-logo {
                width: 85px;
                height: 85px;
            }
            
            .brand-logo img {
                padding: 14px;
            }
            
            .brand-line-1 {
                font-size: 0.8rem;
                letter-spacing: 2px;
            }
            
            .brand-line-2 {
                font-size: 1.9rem;
            }
            
            .desktop-nav-container {
                display: none !important;
            }
            
            .apply-btn {
                display: none;
            }
            
            .user-btn {
                width: 48px;
                height: 48px;
                font-size: 1.1rem;
            }
            
            .mobile-menu-toggle {
                width: 48px;
                height: 48px;
            }
            
            .main-content-wrapper {
                padding-top: var(--header-height);
            }
            
            .main-content {
                padding: 0 var(--container-padding-tablet);
            }
        }

        /* Mobile Landscape (480px - 767px) */
        @media (min-width: 480px) and (max-width: 767px) {
            :root {
                --header-height: 85px;
                --logo-size-mobile: 70px;
                --container-padding-desktop: var(--container-padding-mobile);
            }
            
            .header-container {
                padding: 0 var(--container-padding-mobile);
            }
            
            .brand {
                gap: 1.2rem;
            }
            
            .brand-logo {
                width: 70px;
                height: 70px;
            }
            
            .brand-logo img {
                padding: 12px;
            }
            
            .brand-line-1 {
                font-size: 0.75rem;
                letter-spacing: 1.8px;
            }
            
            .brand-line-2 {
                font-size: 1.6rem;
            }
            
            .brand-line-2::after {
                width: 50px;
                bottom: -6px;
            }
            
            .desktop-nav-container {
                display: none !important;
            }
            
            .apply-btn {
                display: none;
            }
            
            .user-btn {
                width: 45px;
                height: 45px;
                font-size: 1rem;
            }
            
            .mobile-menu-toggle {
                width: 45px;
                height: 45px;
            }
            
            .mobile-nav-overlay {
                padding: 1.5rem;
            }
            
            .mobile-nav-link {
                font-size: 1.05rem;
                padding: 1.1rem 0;
            }
            
            .main-content-wrapper {
                padding-top: var(--header-height);
            }
            
            .main-content {
                padding: 0 var(--container-padding-mobile);
            }
        }

        /* Mobile Portrait (320px - 479px) */
        @media (max-width: 479px) {
            :root {
                --header-height: 80px;
                --logo-size-mobile-small: 60px;
                --container-padding-desktop: 1rem;
            }
            
            .header-container {
                padding: 0 1rem;
            }
            
            .brand {
                gap: 1rem;
            }
            
            .brand-logo {
                width: 60px;
                height: 60px;
            }
            
            .brand-logo img {
                padding: 10px;
            }
            
            .brand-logo .logo-fallback {
                font-size: 1.2rem;
            }
            
            .brand-line-1 {
                font-size: 0.65rem;
                letter-spacing: 1.5px;
                margin-bottom: 3px;
            }
            
            .brand-line-2 {
                font-size: 1.3rem;
            }
            
            .brand-line-2::after {
                width: 45px;
                height: 2px;
                bottom: -5px;
            }
            
            .desktop-nav-container {
                display: none !important;
            }
            
            .apply-btn {
                display: none;
            }
            
            .user-btn {
                width: 42px;
                height: 42px;
                font-size: 0.95rem;
                border-width: 1.5px;
            }
            
            .mobile-menu-toggle {
                width: 42px;
                height: 42px;
            }
            
            .mobile-menu-toggle span {
                width: 20px;
                height: 2px;
            }
            
            .menu-label {
                font-size: 0.6rem;
                letter-spacing: 1px;
            }
            
            .mobile-nav-overlay {
                padding: 1rem;
            }
            
            .mobile-nav-link {
                font-size: 1rem;
                padding: 1rem 0;
            }
            
            .mobile-action-btn {
                padding: 1.1rem;
                font-size: 0.95rem;
            }
            
            .mobile-contact-grid {
                grid-template-columns: 1fr;
            }
            
            .main-content-wrapper {
                padding-top: var(--header-height);
            }
            
            .main-content {
                padding: 0 1rem;
            }
        }

        /* Mobile-only styles */
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
            
            .main-content {
                padding: 0 var(--container-padding-mobile);
            }
            
            .main-content:has(.full-width-content) {
                padding: 0 !important;
            }
        }

        /* ==========================================================================
           UTILITY CLASSES
           ========================================================================== */
        .full-width {
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(-50vw + 50%) !important;
            margin-right: calc(-50vw + 50%) !important;
        }

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

        /* Ensure images don't overflow */
        img {
            max-width: 100%;
            height: auto;
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

<!-- Fixed Header - Prestige Edition with ENLARGED LOGO -->
<header class="site-header" role="banner">
    <div class="header-container">
        <!-- Premium Brand with SIGNIFICANTLY ENLARGED Crest-style Logo -->
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
                    FCT
                </div>
            </div>
            <div class="brand-text">
                <div class="brand-name">
                    <span class="brand-line-1">FCT College of</span>
                    <span class="brand-line-2">Nursing Sciences</span>
                </div>
            </div>
        </a>
        
        <!-- Header Actions - Enlarged to match logo scale -->
        <div class="header-actions">
            <!-- Premium Apply Button (Desktop only) -->
            <a href="<?php echo $baseUrl; ?>/admissions" class="apply-btn">
                <i class="fas fa-file-alt"></i>
                <span>Apply Now</span>
            </a>
            
            <!-- User Button (only when logged in) - Enlarged -->
            <?php if ($isLoggedIn): ?>
            <a href="<?php echo $baseUrl; ?>/student/dashboard" class="user-btn" aria-label="User dashboard" title="Dashboard">
                <?php echo strtoupper(substr($username, 0, 1)); ?>
            </a>
            <?php endif; ?>
            
            <!-- Refined Mobile Menu Toggle - Enlarged -->
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

<!-- Desktop Navigation - Prestige Edition -->
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
            <!-- Distinguished Contact Tab -->
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

<!-- Premium Mobile Navigation Overlay -->
<div class="mobile-nav-overlay" id="mobileNav">
    <div class="mobile-nav-content">
        <!-- Mobile Navigation Menu -->
        <ul class="mobile-nav-menu">
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/" 
                   class="mobile-nav-link <?php echo ($currentPage == 'home' || $currentPage == '') ? 'active' : ''; ?>">
                    <span><i class="fas fa-home"></i>Home</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/about" 
                   class="mobile-nav-link <?php echo $currentPage == 'about' ? 'active' : ''; ?>">
                    <span><i class="fas fa-info-circle"></i>About Us</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/programs" 
                   class="mobile-nav-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>">
                    <span><i class="fas fa-graduation-cap"></i>Academic Programs</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/admissions" 
                   class="mobile-nav-link <?php echo $currentPage == 'admissions' ? 'active' : ''; ?>">
                    <span><i class="fas fa-sign-in-alt"></i>Admissions</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/research" 
                   class="mobile-nav-link <?php echo $currentPage == 'research' ? 'active' : ''; ?>">
                    <span><i class="fas fa-flask"></i>Research</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/student-life" 
                   class="mobile-nav-link <?php echo $currentPage == 'student-life' ? 'active' : ''; ?>">
                    <span><i class="fas fa-users"></i>Student Life</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/faculty" 
                   class="mobile-nav-link <?php echo $currentPage == 'faculty' ? 'active' : ''; ?>">
                    <span><i class="fas fa-chalkboard-teacher"></i>Faculty</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/news" 
                   class="mobile-nav-link <?php echo $currentPage == 'news' ? 'active' : ''; ?>">
                    <span><i class="fas fa-newspaper"></i>News & Events</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <!-- Premium Mobile Contact Tab -->
            <li class="mobile-nav-item contact-tab">
                <a href="<?php echo $baseUrl; ?>/contact" 
                   class="mobile-nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
                    <span><i class="fas fa-phone-alt"></i>Contact Us</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <?php if ($isLoggedIn): ?>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/student/dashboard" class="mobile-nav-link">
                    <span><i class="fas fa-tachometer-alt"></i>Student Dashboard</span>
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <!-- Premium Quick Actions -->
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
            <a href="<?php echo $baseUrl; ?>/student/logout" class="mobile-action-btn" style="background: var(--prestige-burgundy);">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
            <?php endif; ?>
        </div>
        
        <!-- Premium Contact Information -->
        <div class="mobile-contact-info">
            <div class="mobile-contact-grid">
                <a href="tel:+2348082775076" class="mobile-contact-item">
                    <i class="fas fa-phone-alt"></i>
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
// PREMIUM HEADER FUNCTIONALITY
// Refined Interactions | Smooth Transitions | Performance Optimized
// ==============================================

(function() {
    "use strict";
    
    // Toggle Mobile Menu
    window.toggleMobileMenu = function() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileNav = document.getElementById('mobileNav');
        const body = document.body;
        
        menuToggle.classList.toggle('active');
        mobileNav.classList.toggle('active');
        
        const isExpanded = menuToggle.classList.contains('active');
        menuToggle.setAttribute('aria-expanded', isExpanded);
        body.style.overflow = isExpanded ? 'hidden' : '';
    };
    
    // DOM Ready Initialization
    document.addEventListener('DOMContentLoaded', function() {
        
        // ===== SCROLL EFFECTS =====
        const header = document.querySelector('.site-header');
        const navContainer = document.querySelector('.desktop-nav-container');
        
        function handleScroll() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
                if (navContainer) navContainer.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
                if (navContainer) navContainer.classList.remove('scrolled');
            }
        }
        
        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll(); // Initial check
        
        // ===== FLASH MESSAGES =====
        document.querySelectorAll('.flash-close').forEach(button => {
            button.addEventListener('click', function() {
                const flashMessage = this.closest('.flash-message');
                flashMessage.style.opacity = '0';
                flashMessage.style.transform = 'translateY(-10px)';
                setTimeout(() => flashMessage.style.removeProperty('display'), 300);
            });
        });
        
        // Auto-hide flash messages
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(message => {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-10px)';
                setTimeout(() => message.style.display = 'none', 300);
            });
        }, 5000);
        
        // ===== LOGO FALLBACK =====
        const logoImg = document.querySelector('.brand-logo img');
        const logoFallback = document.querySelector('.logo-fallback');
        
        if (logoImg && logoFallback) {
            const testImage = new Image();
            testImage.src = logoImg.src;
            
            testImage.onload = function() {
                logoImg.style.display = 'block';
                logoFallback.style.display = 'none';
            };
            
            testImage.onerror = function() {
                logoImg.style.display = 'none';
                logoFallback.style.display = 'flex';
            };
        }
        
        // ===== CLOSE MOBILE MENU ON OUTSIDE CLICK =====
        document.addEventListener('click', function(event) {
            const menuToggle = document.querySelector('.mobile-menu-toggle-wrapper');
            const mobileNav = document.getElementById('mobileNav');
            
            if (mobileNav && mobileNav.classList.contains('active') && 
                !menuToggle?.contains(event.target) && 
                !mobileNav.contains(event.target)) {
                window.toggleMobileMenu();
            }
        });
        
        // ===== ESCAPE KEY HANDLER =====
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const mobileNav = document.getElementById('mobileNav');
                if (mobileNav?.classList.contains('active')) {
                    window.toggleMobileMenu();
                }
            }
        });
        
        // ===== RESPONSIVE ADJUSTMENTS =====
        function handleResponsive() {
            const isDesktop = window.innerWidth >= 1024;
            const mobileNav = document.getElementById('mobileNav');
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            
            if (isDesktop && mobileNav?.classList.contains('active')) {
                mobileNav.classList.remove('active');
                menuToggle?.classList.remove('active');
                menuToggle?.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
            
            // Update main content padding
            const mainContentWrapper = document.querySelector('.main-content-wrapper');
            if (mainContentWrapper) {
                const headerHeight = header?.offsetHeight || 100;
                if (isDesktop && navContainer) {
                    const navHeight = navContainer.offsetHeight || 56;
                    mainContentWrapper.style.paddingTop = (headerHeight + navHeight) + 'px';
                } else {
                    mainContentWrapper.style.paddingTop = headerHeight + 'px';
                }
            }
        }
        
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(handleResponsive, 100);
        });
        
        handleResponsive(); // Initial call
        
        // ===== DROPDOWN HOVER ENHANCEMENTS =====
        if (window.innerWidth >= 1024) {
            document.querySelectorAll('.has-dropdown').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    const dropdown = this.querySelector('.dropdown-menu');
                    if (dropdown) {
                        dropdown.style.opacity = '1';
                        dropdown.style.visibility = 'visible';
                        dropdown.style.transform = 'translateX(-50%) translateY(0)';
                    }
                });
                
                item.addEventListener('mouseleave', function() {
                    const dropdown = this.querySelector('.dropdown-menu');
                    if (dropdown) {
                        dropdown.style.opacity = '0';
                        dropdown.style.visibility = 'hidden';
                        dropdown.style.transform = 'translateX(-50%) translateY(-10px)';
                    }
                });
            });
        }
        
        // ===== PREVENT HORIZONTAL SCROLL =====
        function preventOverflow() {
            document.documentElement.style.overflowX = 'hidden';
            document.body.style.overflowX = 'hidden';
        }
        
        preventOverflow();
        window.addEventListener('resize', preventOverflow);
        
        // ===== SMOOTH SCROLL FOR ANCHOR LINKS =====
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    
                    // Close mobile menu if open
                    const mobileNav = document.getElementById('mobileNav');
                    if (mobileNav?.classList.contains('active')) {
                        window.toggleMobileMenu();
                    }
                    
                    const headerHeight = header?.offsetHeight || 100;
                    const navHeight = window.innerWidth >= 1024 && navContainer ? navContainer.offsetHeight : 0;
                    const offset = target.offsetTop - headerHeight - navHeight - 20;
                    
                    window.scrollTo({
                        top: offset,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
})();
</script>