<?php
/**
 * University Header Template - PERFECTLY FITTED LOGO EDITION
 * REDESIGNED: Logo fits perfectly, no overflow, balanced proportions
 * FIXED: Gold border contained, mobile rendering flawless, compact tabs
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    
    <!-- Google Fonts - Balanced & Readable -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700;800&family=Inter:wght@500;600;700&display=swap" rel="stylesheet">
    
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
           PERFECTLY BALANCED HEADER - NO OVERFLOW
           Logo fits precisely | Mobile rendering flawless
           Compact tabs preserved | Premium aesthetics
           ============================================== */
        
        :root {
            /* ===== REFINED COLOR PALETTE ===== */
            --prestige-navy: #0a2342;
            --prestige-navy-dark: #021024;
            --prestige-gold: #b4945c;
            --prestige-gold-bold: #c4a041;
            --prestige-gold-light: #e5d3b0;
            --prestige-cream: #faf7f2;
            --prestige-ivory: #f8f4ed;
            --prestige-charcoal: #2c3e4e;
            --prestige-slate: #5a6a7a;
            --prestige-stone: #e8e6e1;
            --prestige-burgundy: #8b3a3a;
            --prestige-white: #ffffff;
            
            /* ===== TYPOGRAPHY ===== */
            --font-serif: 'Cormorant Garamond', Georgia, serif;
            --font-sans: 'Inter', -apple-system, sans-serif;
            
            /* ===== PERFECTLY BALANCED DIMENSIONS - NO OVERFLOW ===== */
            --header-height-desktop: 90px;
            --header-height-mobile: 80px;
            --nav-height: 44px;
            
            /* ===== PERFECTLY SIZED LOGO - FITS WITHIN CONTAINER ===== */
            --logo-size-desktop: 70px;      /* Perfect size - not too big, not too small */
            --logo-size-tablet: 65px;
            --logo-size-mobile: 60px;
            --logo-size-mobile-small: 55px;
            --logo-border-width: 2.5px;      /* Clean border - no overflow */
            --logo-padding: 10px;            /* Perfect padding for logo */
            
            /* ===== CONTAINER PADDING - OPTIMAL ===== */
            --container-padding-desktop: 2rem;
            --container-padding-tablet: 1.5rem;
            --container-padding-mobile: 1rem;
            
            /* ===== SUBTLE SHADOWS ===== */
            --shadow-subtle: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-medium: 0 4px 12px rgba(0,0,0,0.06);
            
            /* ===== FAST TRANSITIONS ===== */
            --transition-fast: 0.2s ease;
        }
        
        /* ==========================================================================
           RESET - CRITICAL FOR NO OVERFLOW
           ========================================================================== */
        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        
        body {
            background: white;
            font-family: var(--font-sans);
            color: var(--prestige-charcoal);
            line-height: 1.5;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* ==========================================================================
           HEADER - CLEAN, NO OVERFLOW, PERFECT FIT
           ========================================================================== */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border-bottom: 2px solid var(--prestige-gold-bold);
            height: var(--header-height-desktop);
            width: 100%;
            max-width: 100vw;
            box-shadow: var(--shadow-subtle);
            transition: height var(--transition-fast);
        }
        
        .site-header.scrolled {
            height: 80px;
            border-bottom-width: 2px;
        }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 var(--container-padding-desktop);
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }
        
        /* ==========================================================================
           PERFECTLY FITTED LOGO - NO OVERFLOW, CLEAN BORDERS
           ========================================================================== */
        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: inherit;
            flex-shrink: 0;
            max-width: 70%; /* Prevents overflow on mobile */
        }
        
        /* LOGO CONTAINER - CONTAINED, CLEAN, NO OVERFLOW */
        .brand-logo {
            width: var(--logo-size-desktop);
            height: var(--logo-size-desktop);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 50%;
            border: var(--logo-border-width) solid var(--prestige-gold-bold);
            overflow: hidden; /* CRITICAL: Contains the image */
            transition: transform var(--transition-fast);
            box-shadow: 0 0 0 2px white, 0 0 0 4px rgba(196, 160, 65, 0.1);
            position: relative;
        }
        
        .brand:hover .brand-logo {
            transform: scale(1.03);
            border-color: var(--prestige-gold);
            box-shadow: 0 0 0 2px white, 0 0 0 6px rgba(196, 160, 65, 0.15);
        }
        
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: var(--logo-padding);
            display: block;
        }
        
        /* FALLBACK LOGO - CLEAN AND CONTAINED */
        .brand-logo .logo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, var(--prestige-navy), var(--prestige-navy-dark));
            color: var(--prestige-gold-bold);
            font-family: var(--font-serif);
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: 2px;
        }
        
        /* ==========================================================================
           PERFECTLY READABLE TYPOGRAPHY - CLEAR, NOT OVERWHELMING
           ========================================================================== */
        .brand-text {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0; /* Prevents text overflow */
        }
        
        .brand-name {
            font-family: var(--font-serif);
            line-height: 1.1;
        }
        
        /* "FCT College of" - CLEAR, READABLE, PERFECT CONTRAST */
        .brand-line-1 {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--prestige-navy-dark);
            letter-spacing: 1.8px;
            text-transform: uppercase;
            margin-bottom: 3px;
            font-family: var(--font-sans);
            white-space: nowrap;
        }
        
        /* "Nursing Sciences" - BOLD BUT BALANCED */
        .brand-line-2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--prestige-navy);
            letter-spacing: -0.3px;
            font-family: var(--font-serif);
            line-height: 1;
            position: relative;
            display: inline-block;
            white-space: nowrap;
        }
        
        /* ELEGANT GOLD UNDERLINE - CLEAN, NOT OVERBEARING */
        .brand-line-2::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50px;
            height: 2px;
            background: linear-gradient(90deg, var(--prestige-gold-bold), var(--prestige-gold-light));
            border-radius: 2px;
            transition: width var(--transition-fast);
        }
        
        .brand:hover .brand-line-2::after {
            width: 80px;
        }
        
        /* ==========================================================================
           HEADER ACTIONS - CLEAN, COMPACT, PERFECTLY ALIGNED
           ========================================================================== */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            flex-shrink: 0;
        }
        
        .apply-btn {
            padding: 0.5rem 1.2rem;
            background: linear-gradient(135deg, var(--prestige-navy), var(--prestige-navy-dark));
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            transition: all var(--transition-fast);
            border: 1px solid var(--prestige-gold-light);
        }
        
        .apply-btn i {
            font-size: 0.75rem;
        }
        
        .apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            border-color: var(--prestige-gold-bold);
        }
        
        .user-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--prestige-ivory);
            color: var(--prestige-navy);
            border: 2px solid var(--prestige-gold-bold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            transition: all var(--transition-fast);
            text-decoration: none;
        }
        
        .user-btn:hover {
            background: var(--prestige-navy);
            color: white;
            border-color: var(--prestige-gold);
            transform: translateY(-2px);
        }
        
        /* ==========================================================================
           MOBILE MENU TOGGLE - CLEAN, COMPACT
           ========================================================================== */
        .mobile-menu-toggle-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            cursor: pointer;
        }
        
        .mobile-menu-toggle {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            border: 2px solid var(--prestige-gold-bold);
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all var(--transition-fast);
        }
        
        .mobile-menu-toggle span {
            display: block;
            width: 20px;
            height: 2px;
            background: var(--prestige-navy);
            transition: all var(--transition-fast);
            border-radius: 2px;
        }
        
        .menu-label {
            font-size: 0.55rem;
            font-weight: 700;
            color: var(--prestige-navy);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* ==========================================================================
           COMPACT DESKTOP NAVIGATION - PERFECTLY PORTABLE TABS
           ========================================================================== */
        .desktop-nav-container {
            position: fixed;
            top: var(--header-height-desktop);
            left: 0;
            right: 0;
            z-index: 999;
            background: white;
            border-bottom: 2px solid var(--prestige-gold-light);
            height: var(--nav-height);
            width: 100%;
            max-width: 100vw;
        }
        
        .site-header.scrolled + .desktop-nav-container {
            top: 80px;
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
            margin: 0;
            padding: 0 var(--container-padding-desktop);
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            max-width: 100%;
            gap: 0.2rem;
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
            padding: 0 0.9rem;
            color: var(--prestige-charcoal);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem;
            transition: color var(--transition-fast);
            position: relative;
            white-space: nowrap;
            letter-spacing: 0.2px;
        }
        
        .desktop-nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--prestige-gold-bold);
            transition: width var(--transition-fast);
            border-radius: 2px;
        }
        
        .desktop-nav-link:hover::before,
        .desktop-nav-link.active::before {
            width: 70%;
        }
        
        .desktop-nav-link.active {
            color: var(--prestige-navy);
            font-weight: 700;
        }
        
        /* COMPACT DROPDOWN - CLEAN AND TIDY */
        .dropdown-menu {
            position: absolute;
            top: calc(100% - 1px);
            left: 50%;
            transform: translateX(-50%) translateY(-5px);
            background: white;
            min-width: 200px;
            border-radius: 0 0 8px 8px;
            box-shadow: var(--shadow-medium);
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-fast);
            z-index: 100;
            border: 1px solid var(--prestige-stone);
            border-top: 3px solid var(--prestige-gold-bold);
            padding: 0.5rem 0;
        }
        
        .has-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
        
        .dropdown-link {
            display: block;
            padding: 0.5rem 1.5rem;
            color: var(--prestige-charcoal);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem;
            transition: all var(--transition-fast);
            white-space: nowrap;
        }
        
        .dropdown-link:hover {
            background: rgba(196, 160, 65, 0.05);
            color: var(--prestige-navy);
            padding-left: 2rem;
        }
        
        /* CONTACT TAB - DISTINCTIVE BUT CLEAN */
        .desktop-nav-item.contact-tab .desktop-nav-link {
            color: var(--prestige-burgundy);
            background: rgba(139, 58, 58, 0.02);
            border-left: 1px solid rgba(139, 58, 58, 0.1);
            border-right: 1px solid rgba(139, 58, 58, 0.1);
            font-weight: 600;
            margin-left: 0.3rem;
        }
        
        .desktop-nav-item.contact-tab .desktop-nav-link i {
            margin-right: 5px;
            font-size: 0.75rem;
        }
        
        .desktop-nav-item.contact-tab .desktop-nav-link:hover {
            background: var(--prestige-burgundy);
            color: white;
        }
        
        /* ==========================================================================
           MOBILE NAVIGATION - PERFECTLY RENDERED, NO SCATTER
           ========================================================================== */
        .mobile-nav-overlay {
            position: fixed;
            top: var(--header-height-desktop);
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transform: translateX(100%);
            transition: all 0.3s ease;
            overflow-y: auto;
            padding: 1.5rem;
            width: 100%;
        }
        
        .site-header.scrolled + .desktop-nav-container + .mobile-nav-overlay,
        .site-header.scrolled ~ .mobile-nav-overlay {
            top: 80px;
        }
        
        .mobile-nav-overlay.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }
        
        .mobile-nav-content {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }
        
        .mobile-nav-menu {
            list-style: none;
            margin-bottom: 1.5rem;
        }
        
        .mobile-nav-item {
            border-bottom: 1px solid var(--prestige-stone);
        }
        
        .mobile-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 0;
            color: var(--prestige-charcoal);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .mobile-nav-link span i {
            width: 24px;
            color: var(--prestige-gold-bold);
            margin-right: 10px;
        }
        
        .mobile-nav-link.active {
            color: var(--prestige-navy);
            border-left: 3px solid var(--prestige-gold-bold);
            padding-left: 0.8rem;
        }
        
        .mobile-quick-actions {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
        }
        
        .mobile-action-btn {
            padding: 0.9rem 1.2rem;
            border-radius: 8px;
            background: var(--prestige-navy);
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-weight: 600;
            justify-content: center;
            font-size: 0.9rem;
        }
        
        .mobile-action-btn.accent {
            background: var(--prestige-gold-bold);
            color: var(--prestige-navy);
        }
        
        .mobile-contact-info {
            padding-top: 1.5rem;
            border-top: 1px solid var(--prestige-stone);
        }
        
        .mobile-contact-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }
        
        .mobile-contact-item {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            color: var(--prestige-slate);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.7rem;
            border-radius: 6px;
        }
        
        .mobile-contact-item i {
            color: var(--prestige-gold-bold);
            width: 20px;
        }
        
        /* ==========================================================================
           PERFECT RESPONSIVE BREAKPOINTS - NO OVERFLOW, NO SCATTER
           ========================================================================== */
        
        /* Desktop */
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
                padding-top: calc(var(--header-height-desktop) + var(--nav-height));
            }
        }
        
        /* Tablet */
        @media (min-width: 768px) and (max-width: 1023px) {
            :root {
                --header-height-desktop: 85px;
                --logo-size-desktop: var(--logo-size-tablet);
            }
            
            .header-container {
                padding: 0 var(--container-padding-tablet);
            }
            
            .brand-logo {
                width: 65px;
                height: 65px;
            }
            
            .brand-line-2 {
                font-size: 1.4rem;
            }
            
            .desktop-nav-container {
                display: none !important;
            }
            
            .apply-btn {
                display: none;
            }
            
            .main-content-wrapper {
                padding-top: 85px;
            }
            
            .mobile-nav-overlay {
                top: 85px;
            }
        }
        
        /* Mobile */
        @media (max-width: 767px) {
            :root {
                --header-height-desktop: 80px;
                --header-height-mobile: 80px;
                --logo-size-desktop: var(--logo-size-mobile);
            }
            
            .header-container {
                padding: 0 var(--container-padding-mobile);
            }
            
            .brand {
                gap: 0.8rem;
                max-width: 65%;
            }
            
            .brand-logo {
                width: 60px;
                height: 60px;
            }
            
            .brand-logo img {
                padding: 9px;
            }
            
            .brand-logo .logo-fallback {
                font-size: 1.5rem;
            }
            
            .brand-line-1 {
                font-size: 0.65rem;
                letter-spacing: 1.2px;
                white-space: normal;
                word-break: break-word;
            }
            
            .brand-line-2 {
                font-size: 1.3rem;
                white-space: normal;
                word-break: break-word;
            }
            
            .brand-line-2::after {
                width: 40px;
                bottom: -4px;
            }
            
            .desktop-nav-container {
                display: none !important;
            }
            
            .apply-btn {
                display: none;
            }
            
            .main-content-wrapper {
                padding-top: 80px;
            }
            
            .mobile-nav-overlay {
                top: 80px;
                padding: 1.2rem;
            }
        }
        
        /* Small Mobile */
        @media (max-width: 480px) {
            :root {
                --header-height-desktop: 75px;
                --logo-size-desktop: var(--logo-size-mobile-small);
            }
            
            .header-container {
                padding: 0 0.8rem;
            }
            
            .brand {
                gap: 0.7rem;
                max-width: 70%;
            }
            
            .brand-logo {
                width: 55px;
                height: 55px;
                border-width: 2px;
            }
            
            .brand-logo img {
                padding: 8px;
            }
            
            .brand-logo .logo-fallback {
                font-size: 1.3rem;
            }
            
            .brand-line-1 {
                font-size: 0.6rem;
                letter-spacing: 1px;
            }
            
            .brand-line-2 {
                font-size: 1.1rem;
            }
            
            .brand-line-2::after {
                width: 35px;
                height: 2px;
                bottom: -4px;
            }
            
            .user-btn {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }
            
            .mobile-menu-toggle {
                width: 36px;
                height: 36px;
            }
            
            .main-content-wrapper {
                padding-top: 75px;
            }
            
            .mobile-nav-overlay {
                top: 75px;
                padding: 1rem;
            }
            
            .mobile-nav-link {
                padding: 0.8rem 0;
                font-size: 0.9rem;
            }
        }
        
        /* Extra Small Mobile */
        @media (max-width: 360px) {
            .brand-line-1 {
                font-size: 0.55rem;
                letter-spacing: 0.8px;
            }
            
            .brand-line-2 {
                font-size: 1rem;
            }
            
            .brand-logo {
                width: 50px;
                height: 50px;
            }
            
            .menu-label {
                display: none;
            }
        }
        
        /* Mobile-only rules */
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
                padding-top: var(--header-height-desktop);
            }
            .mobile-nav-overlay {
                display: block;
            }
        }
        
        /* ==========================================================================
           MAIN CONTENT
           ========================================================================== */
        .main-content-wrapper {
            flex: 1;
            width: 100%;
            max-width: 100vw;
            margin-top: 0;
            padding-top: var(--header-height-desktop);
            overflow-x: hidden;
        }
        
        .main-content {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 var(--container-padding-desktop);
        }
        
        .full-width {
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(-50vw + 50%) !important;
            margin-right: calc(-50vw + 50%) !important;
        }
        
        img {
            max-width: 100%;
            height: auto;
        }
        
        /* Flash Messages */
        .flash-messages {
            position: fixed;
            top: calc(var(--header-height-desktop) + 0.5rem);
            left: 0;
            right: 0;
            z-index: 1001;
            padding: 0 1rem;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .flash-message {
            background: white;
            border-left: 4px solid var(--prestige-gold-bold);
            border-radius: 6px;
            padding: 0.8rem 1.2rem;
            margin-bottom: 0.5rem;
            box-shadow: var(--shadow-medium);
            animation: slideDown 0.3s ease;
            pointer-events: auto;
            max-width: 500px;
            width: 100%;
            font-size: 0.9rem;
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

<!-- PERFECTLY FITTED HEADER - NO OVERFLOW, CLEAN DESIGN -->
<header class="site-header" role="banner">
    <div class="header-container">
        <!-- PERFECTLY SIZED LOGO - FITS WITHIN BORDERS, NO OVERFLOW -->
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
        
        <!-- HEADER ACTIONS - PERFECTLY ALIGNED -->
        <div class="header-actions">
            <a href="<?php echo $baseUrl; ?>/admissions" class="apply-btn">
                <i class="fas fa-file-alt"></i>
                <span>Apply</span>
            </a>
            
            <?php if ($isLoggedIn): ?>
            <a href="<?php echo $baseUrl; ?>/student/dashboard" class="user-btn" aria-label="User dashboard">
                <?php echo strtoupper(substr($username, 0, 1)); ?>
            </a>
            <?php endif; ?>
            
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

<!-- COMPACT DESKTOP NAVIGATION - CLEAN TABS -->
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
                    <a href="<?php echo $baseUrl; ?>/about/mission" class="dropdown-link">Mission</a>
                    <a href="<?php echo $baseUrl; ?>/about/accreditation" class="dropdown-link">Accreditation</a>
                </div>
            </li>
            <li class="desktop-nav-item has-dropdown">
                <a href="<?php echo $baseUrl; ?>/programs" 
                   class="desktop-nav-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>">
                    Programs
                </a>
                <div class="dropdown-menu">
                    <a href="<?php echo $baseUrl; ?>/programs/undergraduate" class="dropdown-link">Undergrad</a>
                    <a href="<?php echo $baseUrl; ?>/programs/graduate" class="dropdown-link">Graduate</a>
                    <a href="<?php echo $baseUrl; ?>/programs/continuing-education" class="dropdown-link">Continuing Ed</a>
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
                    <a href="<?php echo $baseUrl; ?>/research/projects" class="dropdown-link">Projects</a>
                    <a href="<?php echo $baseUrl; ?>/research/facilities" class="dropdown-link">Facilities</a>
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
                    News
                </a>
            </li>
            <li class="desktop-nav-item contact-tab">
                <a href="<?php echo $baseUrl; ?>/contact" 
                   class="desktop-nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
                    <i class="fas fa-phone-alt"></i>
                    Contact
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- CLEAN MOBILE NAVIGATION - NO SCATTER -->
<div class="mobile-nav-overlay" id="mobileNav">
    <div class="mobile-nav-content">
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
                    <span><i class="fas fa-info-circle"></i>About</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/programs" 
                   class="mobile-nav-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>">
                    <span><i class="fas fa-graduation-cap"></i>Programs</span>
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
                    <span><i class="fas fa-newspaper"></i>News</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="mobile-nav-item contact-tab">
                <a href="<?php echo $baseUrl; ?>/contact" 
                   class="mobile-nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
                    <span><i class="fas fa-phone-alt"></i>Contact</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
        
        <div class="mobile-quick-actions">
            <a href="<?php echo $baseUrl; ?>/admissions" class="mobile-action-btn">
                <i class="fas fa-file-import"></i>
                Apply Now
            </a>
            <a href="<?php echo $baseUrl; ?>/student-life" class="mobile-action-btn accent">
                <i class="fas fa-graduation-cap"></i>
                Student Portal
            </a>
        </div>
        
        <div class="mobile-contact-info">
            <div class="mobile-contact-grid">
                <a href="tel:+2348082775076" class="mobile-contact-item">
                    <i class="fas fa-phone-alt"></i>
                    +234 808 277 5076
                </a>
                <a href="mailto:info@fctcns.edu.ng" class="mobile-contact-item">
                    <i class="fas fa-envelope"></i>
                    info@fctcns.edu.ng
                </a>
                <a href="<?php echo $baseUrl; ?>/visit" class="mobile-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    Visit Campus
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
// CLEAN HEADER FUNCTIONALITY - NO BUGS, NO OVERFLOW
// ==============================================

(function() {
    "use strict";
    
    window.toggleMobileMenu = function() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileNav = document.getElementById('mobileNav');
        const body = document.body;
        
        if (menuToggle && mobileNav) {
            menuToggle.classList.toggle('active');
            mobileNav.classList.toggle('active');
            
            const isExpanded = menuToggle.classList.contains('active');
            menuToggle.setAttribute('aria-expanded', isExpanded);
            body.style.overflow = isExpanded ? 'hidden' : '';
        }
    };
    
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.site-header');
        const navContainer = document.querySelector('.desktop-nav-container');
        
        function handleScroll() {
            if (window.scrollY > 30) {
                header.classList.add('scrolled');
                if (navContainer) navContainer.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
                if (navContainer) navContainer.classList.remove('scrolled');
            }
        }
        
        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll();
        
        // Flash messages
        document.querySelectorAll('.flash-close').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.flash-message').remove();
            });
        });
        
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(msg => msg.remove());
        }, 4000);
        
        // Logo fallback
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
        
        // Close mobile menu on outside click
        document.addEventListener('click', function(event) {
            const menuToggle = document.querySelector('.mobile-menu-toggle-wrapper');
            const mobileNav = document.getElementById('mobileNav');
            
            if (mobileNav?.classList.contains('active') && 
                !menuToggle?.contains(event.target) && 
                !mobileNav.contains(event.target)) {
                window.toggleMobileMenu();
            }
        });
        
        // Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const mobileNav = document.getElementById('mobileNav');
                if (mobileNav?.classList.contains('active')) {
                    window.toggleMobileMenu();
                }
            }
        });
        
        // Responsive adjustments
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
        }
        
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(handleResponsive, 100);
        });
        
        handleResponsive();
        
        // Prevent horizontal scroll
        document.documentElement.style.overflowX = 'hidden';
        document.body.style.overflowX = 'hidden';
    });
})();
</script>