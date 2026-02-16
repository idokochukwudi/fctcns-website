<?php
/**
 * University Header Template - SOPHISTICATED MATURE EDITION
 * REDESIGNED: Minimized gold accents, premium navy dominance
 * FIXED: Mobile menu displays under handle on all screen sizes
 * ELEGANT: Refined, professional, authoritative
 * UPDATED: Logo size reduced and properly fitted with header title
 * UPDATED: Student Life and Faculty tabs commented out
 * UPDATED: Apply Now buttons now point to /apply application portal
 * UPDATED: Flash messages integrated with professional styling
 * UPDATED: Apply button with refined typography and proper contrast
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    
    <!-- Google Fonts - Sophisticated Academic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
           SOPHISTICATED MATURE HEADER - MINIMAL GOLD
           Navy-dominant color palette, refined accents
           Mobile menu ALWAYS displays under handle
           Professional, authoritative, timeless
           UPDATED: Logo properly sized and fitted with title
           UPDATED: Flash messages with professional styling
           UPDATED: Apply button with refined typography
           ============================================== */
        
        :root {
            /* ===== SOPHISTICATED COLOR PALETTE - NAVY DOMINANT ===== */
            --prestige-navy: #0a2342;        /* Deep academic navy - PRIMARY */
            --prestige-navy-dark: #021024;    /* Almost black navy */
            --prestige-navy-light: #1e3a5f;   /* Rich navy for gradients */
            --prestige-gold-subtle: #9c8c6c;  /* MUED GOLD - very subtle */
            --prestige-gold-accent: #b29b6e;  /* Only used for micro-interactions */
            --prestige-cream: #f5f2ed;        /* Warm off-white */
            --prestige-ivory: #f8f6f2;        /* Clean background */
            --prestige-charcoal: #2c3e4e;     /* Sophisticated dark gray */
            --prestige-slate: #5e6f7e;        /* Medium gray for secondary text */
            --prestige-stone: #e2dfda;        /* Light gray border */
            --prestige-burgundy: #7a3e3e;     /* Muted burgundy - professional */
            --prestige-white: #ffffff;
            
            /* Flash message colors */
            --flash-success-bg: #e8f0f5;      /* Light navy-tinted background */
            --flash-success-border: #0a2342;   /* Navy border */
            --flash-success-text: #0a2342;     /* Navy text */
            --flash-success-icon: #0a2342;      /* Navy icon */
            
            --flash-error-bg: #fef2f2;         /* Light background */
            --flash-error-border: #7a3e3e;      /* Burgundy border */
            --flash-error-text: #7a3e3e;        /* Burgundy text */
            --flash-error-icon: #7a3e3e;         /* Burgundy icon */
            
            /* ===== TYPOGRAPHY - REFINED ===== */
            --font-serif: 'Cormorant Garamond', Georgia, serif;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            
            /* ===== OPTIMAL DIMENSIONS - PERFECTLY BALANCED ===== */
            --header-height-desktop: 100px;
            --header-height-mobile: 90px;
            --nav-height: 48px;
            
            /* ===== REDUCED LOGO SIZE - PROPERLY FITTED ===== */
            --logo-size-desktop: 70px;
            --logo-size-tablet: 65px;
            --logo-size-mobile: 60px;
            --logo-size-mobile-small: 55px;
            --logo-padding: 0px;
            
            /* ===== ENHANCED TYPOGRAPHY SIZE ===== */
            --brand-text-size-1: 0.8rem;
            --brand-text-size-2: 1.9rem;
            --brand-text-size-2-mobile: 1.6rem;
            --brand-text-size-2-small: 1.4rem;
            --nav-text-size: 0.9rem;
            --button-text-size: 0.9rem;
            
            /* ===== CONTAINER PADDING ===== */
            --container-padding-desktop: 2.2rem;
            --container-padding-tablet: 1.8rem;
            --container-padding-mobile: 1.5rem;
            
            /* ===== SUBTLE SHADOWS - NO GLITZ ===== */
            --shadow-subtle: 0 2px 8px rgba(10, 35, 66, 0.04);
            --shadow-medium: 0 4px 16px rgba(10, 35, 66, 0.06);
            --shadow-elevated: 0 8px 24px rgba(10, 35, 66, 0.08);
            
            /* ===== SMOOTH TRANSITIONS ===== */
            --transition-smooth: 0.2s ease;
        }
        
        /* ==========================================================================
           RESET - PERFECT CONTAINMENT
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
           SOPHISTICATED HEADER - NAVY DOMINANT, GOLD MINIMAL
           ========================================================================== */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border-bottom: 1px solid var(--prestige-stone);
            height: var(--header-height-desktop);
            width: 100%;
            max-width: 100vw;
            box-shadow: var(--shadow-subtle);
            transition: all var(--transition-smooth);
            display: flex;
            align-items: center;
        }
        
        .site-header.scrolled {
            height: 90px;
            box-shadow: var(--shadow-medium);
            border-bottom-color: var(--prestige-navy-light);
        }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 var(--container-padding-desktop);
            width: 100%;
            max-width: 1440px;
            margin: 0 auto;
        }
        
        /* ==========================================================================
           ELEGANT LOGO - REDUCED SIZE, PROPERLY FITTED WITH TITLE
           ========================================================================== */
        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: inherit;
            flex-shrink: 0;
            max-width: 70%;
            height: 100%;
        }
        
        /* LOGO CONTAINER - REDUCED SIZE */
        .brand-logo {
            width: var(--logo-size-desktop);
            height: var(--logo-size-desktop);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            overflow: hidden;
            transition: transform var(--transition-smooth);
            position: relative;
        }
        
        .brand:hover .brand-logo {
            transform: scale(1.03);
        }
        
        /* LOGO IMAGE - PERFECTLY CONTAINED */
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            padding: 0;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));
            transition: filter var(--transition-smooth);
        }
        
        .brand:hover .brand-logo img {
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.08));
        }
        
        /* FALLBACK LOGO - CLEAN NAVY */
        .brand-logo .logo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, var(--prestige-navy), var(--prestige-navy-dark));
            color: white;
            font-family: var(--font-serif);
            font-weight: 800;
            font-size: 2rem;
            letter-spacing: 3px;
            border-radius: 0;
            box-shadow: var(--shadow-subtle);
        }
        
        /* ==========================================================================
           REFINED TYPOGRAPHY - PERFECTLY ALIGNED WITH LOGO
           ========================================================================== */
        .brand-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
            min-width: 0;
            line-height: 1.2;
        }
        
        .brand-name {
            font-family: var(--font-serif);
            line-height: 1.1;
        }
        
        /* "FCT College of" - SUBTLE, PROFESSIONAL */
        .brand-line-1 {
            font-size: var(--brand-text-size-1);
            font-weight: 600;
            color: var(--prestige-slate);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 3px;
            font-family: var(--font-sans);
            white-space: nowrap;
        }
        
        /* "Nursing Sciences" - DEEP NAVY, COMMANDING */
        .brand-line-2 {
            font-size: var(--brand-text-size-2);
            font-weight: 800;
            color: var(--prestige-navy-dark);
            letter-spacing: -0.3px;
            font-family: var(--font-serif);
            line-height: 1;
            position: relative;
            display: inline-block;
            white-space: nowrap;
        }
        
        /* MINIMAL GOLD UNDERLINE - ELEGANT, NOT OVERBEARING */
        .brand-line-2::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--prestige-gold-subtle);
            border-radius: 1px;
            transition: width var(--transition-smooth);
            opacity: 0.8;
        }
        
        .brand:hover .brand-line-2::after {
            width: 80px;
            background: var(--prestige-gold-accent);
        }
        
        /* ==========================================================================
           HEADER ACTIONS - CLEAN, MINIMAL GOLD
           ========================================================================== */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            flex-shrink: 0;
            height: 100%;
        }
        
        /* APPLY BUTTON - PROFESSIONAL TYPOGRAPHY & CONTRAST */
        .apply-btn {
            padding: 0.5rem 1.4rem;
            background: var(--prestige-navy);
            color: var(--prestige-white);
            border: none;
            border-radius: 4px;
            font-size: var(--button-text-size);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            transition: all var(--transition-smooth);
            letter-spacing: 0.3px;
            box-shadow: var(--shadow-subtle);
            font-family: var(--font-sans);
            text-transform: uppercase;
            border: 1px solid transparent;
        }
        
        .apply-btn i {
            font-size: 0.8rem;
            color: var(--prestige-white);
            transition: transform var(--transition-smooth);
        }
        
        .apply-btn:hover {
            background: var(--prestige-navy-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            border-color: var(--prestige-gold-subtle);
        }
        
        .apply-btn:hover i {
            transform: translateX(3px);
        }
        
        .user-btn {
            width: 42px;
            height: 42px;
            border-radius: 4px;
            background: var(--prestige-ivory);
            color: var(--prestige-navy);
            border: 1px solid var(--prestige-stone);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all var(--transition-smooth);
            text-decoration: none;
            font-family: var(--font-serif);
        }
        
        .user-btn:hover {
            background: var(--prestige-navy);
            color: white;
            border-color: var(--prestige-navy);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        /* ==========================================================================
           MOBILE MENU HANDLE - ALWAYS VISIBLE ON MOBILE
           ========================================================================== */
        .mobile-menu-toggle-wrapper {
            display: none;  /* Hidden by default, shown only on mobile */
            flex-direction: column;
            align-items: center;
            gap: 3px;
            cursor: pointer;
            margin-left: 0.3rem;
        }
        
        .mobile-menu-toggle {
            width: 42px;
            height: 42px;
            border-radius: 4px;
            border: 1px solid var(--prestige-stone);
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all var(--transition-smooth);
        }
        
        .mobile-menu-toggle span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--prestige-navy);
            transition: all var(--transition-smooth);
            border-radius: 2px;
        }
        
        .mobile-menu-toggle:hover {
            border-color: var(--prestige-navy-light);
            background: var(--prestige-ivory);
        }
        
        .menu-label {
            font-size: 0.55rem;
            font-weight: 600;
            color: var(--prestige-slate);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* ==========================================================================
           DESKTOP NAVIGATION - CLEAN, PROFESSIONAL TABS
           ========================================================================== */
        .desktop-nav-container {
            position: fixed;
            top: var(--header-height-desktop);
            left: 0;
            right: 0;
            z-index: 999;
            background: white;
            border-bottom: 1px solid var(--prestige-stone);
            height: var(--nav-height);
            width: 100%;
            max-width: 100vw;
            box-shadow: var(--shadow-subtle);
        }
        
        .site-header.scrolled + .desktop-nav-container {
            top: 90px;
        }
        
        .desktop-nav {
            display: block;
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
            justify-content: flex-start;
            width: 100%;
            max-width: 1440px;
            gap: 0.1rem;
        }
        
        .desktop-nav-item {
            position: relative;
            height: 100%;
            flex-shrink: 0;
        }
        
        /* NAV LINKS - CLEAN, TYPOGRAPHY-FOCUSED */
        .desktop-nav-link {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0 1rem;
            color: var(--prestige-charcoal);
            text-decoration: none;
            font-weight: 500;
            font-size: var(--nav-text-size);
            transition: color var(--transition-smooth);
            position: relative;
            white-space: nowrap;
            letter-spacing: 0.2px;
        }
        
        /* MINIMAL HOVER INDICATOR - NAVY, NOT GOLD */
        .desktop-nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--prestige-navy);
            transition: width var(--transition-smooth);
            border-radius: 1px;
        }
        
        .desktop-nav-link:hover::before,
        .desktop-nav-link.active::before {
            width: 70%;
        }
        
        .desktop-nav-link:hover {
            color: var(--prestige-navy);
        }
        
        .desktop-nav-link.active {
            color: var(--prestige-navy);
            font-weight: 700;
        }
        
        /* ELEGANT DROPDOWN - NO GOLD BORDERS */
        .dropdown-menu {
            position: absolute;
            top: calc(100% - 1px);
            left: 50%;
            transform: translateX(-50%) translateY(-5px);
            background: white;
            min-width: 220px;
            border-radius: 0 0 8px 8px;
            box-shadow: var(--shadow-elevated);
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-smooth);
            z-index: 100;
            border: 1px solid var(--prestige-stone);
            border-top: 2px solid var(--prestige-navy);
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
            font-size: 0.85rem;
            transition: all var(--transition-smooth);
            position: relative;
            white-space: nowrap;
        }
        
        .dropdown-link:hover {
            background: rgba(10, 35, 66, 0.02);
            color: var(--prestige-navy);
            padding-left: 1.8rem;
            font-weight: 600;
        }
        
        .dropdown-link::before {
            content: '→';
            position: absolute;
            left: 1rem;
            opacity: 0;
            transition: all var(--transition-smooth);
            color: var(--prestige-navy);
            font-size: 0.9rem;
        }
        
        .dropdown-link:hover::before {
            opacity: 1;
            left: 1.2rem;
        }
        
        /* CONTACT TAB - BURGUNDY, PROFESSIONAL */
        .desktop-nav-item.contact-tab .desktop-nav-link {
            color: var(--prestige-burgundy);
            background: rgba(122, 62, 62, 0.02);
            border-left: 1px solid rgba(122, 62, 62, 0.08);
            border-right: 1px solid rgba(122, 62, 62, 0.08);
            font-weight: 600;
            margin-left: 0.3rem;
            padding: 0 1.2rem;
        }
        
        .desktop-nav-item.contact-tab .desktop-nav-link i {
            margin-right: 5px;
            font-size: 0.8rem;
            color: var(--prestige-burgundy);
        }
        
        .desktop-nav-item.contact-tab .desktop-nav-link:hover {
            background: var(--prestige-burgundy);
            color: white;
        }
        
        .desktop-nav-item.contact-tab .desktop-nav-link:hover i {
            color: white;
        }
        
        /* ==========================================================================
           MOBILE NAVIGATION - ALWAYS DISPLAYS UNDER HANDLE
           ========================================================================== */
        .mobile-nav-overlay {
            position: fixed;
            top: var(--header-height-desktop);
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.25s ease;
            overflow-y: auto;
            padding: 2rem;
            width: 100%;
            border-top: 1px solid var(--prestige-stone);
            box-shadow: var(--shadow-elevated);
        }
        
        .site-header.scrolled ~ .mobile-nav-overlay {
            top: 90px;
        }
        
        .mobile-nav-overlay.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
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
            font-weight: 500;
            font-size: 1rem;
            transition: all var(--transition-smooth);
        }
        
        .mobile-nav-link span i {
            width: 24px;
            color: var(--prestige-navy);
            margin-right: 10px;
            font-size: 1rem;
        }
        
        .mobile-nav-link:hover {
            color: var(--prestige-navy);
            padding-left: 0.3rem;
        }
        
        .mobile-nav-link.active {
            color: var(--prestige-navy);
            font-weight: 700;
            border-left: 3px solid var(--prestige-navy);
            padding-left: 0.6rem;
        }
        
        .mobile-nav-item.contact-tab .mobile-nav-link {
            color: var(--prestige-burgundy);
            background: rgba(122, 62, 62, 0.02);
            padding: 0.9rem 0.6rem;
            margin: 0.5rem 0;
            border-radius: 4px;
            border: 1px solid rgba(122, 62, 62, 0.1);
            font-weight: 600;
        }
        
        .mobile-nav-item.contact-tab .mobile-nav-link i {
            color: var(--prestige-burgundy);
        }
        
        .mobile-quick-actions {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            margin-bottom: 1.5rem;
        }
        
        .mobile-action-btn {
            padding: 0.9rem 1.2rem;
            border-radius: 4px;
            background: var(--prestige-navy);
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all var(--transition-smooth);
            font-weight: 600;
            justify-content: center;
            font-size: 0.95rem;
            border: none;
            letter-spacing: 0.3px;
            font-family: var(--font-sans);
        }
        
        .mobile-action-btn i {
            font-size: 0.9rem;
            color: white;
        }
        
        .mobile-action-btn.accent {
            background: var(--prestige-navy-light);
            color: white;
        }
        
        .mobile-action-btn:hover {
            background: var(--prestige-navy-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .mobile-contact-info {
            padding-top: 1.2rem;
            border-top: 1px solid var(--prestige-stone);
        }
        
        .mobile-contact-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.6rem;
        }
        
        .mobile-contact-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--prestige-slate);
            text-decoration: none;
            font-size: 0.85rem;
            padding: 0.6rem;
            border-radius: 4px;
            transition: all var(--transition-smooth);
            font-weight: 500;
            border: 1px solid transparent;
        }
        
        .mobile-contact-item i {
            color: var(--prestige-navy);
            width: 20px;
            font-size: 0.9rem;
        }
        
        .mobile-contact-item:hover {
            background: var(--prestige-ivory);
            border-color: var(--prestige-stone);
            color: var(--prestige-navy);
        }
        
        /* ==========================================================================
           FLASH MESSAGES - PROFESSIONAL NOTIFICATIONS
           ========================================================================== */
        .flash-messages {
            position: fixed;
            top: calc(var(--header-height-desktop) + var(--nav-height) + 0.5rem);
            left: 50%;
            transform: translateX(-50%);
            z-index: 1001;
            width: 100%;
            max-width: 600px;
            padding: 0 1rem;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .site-header.scrolled ~ .flash-messages {
            top: calc(90px + var(--nav-height) + 0.5rem);
        }
        
        .flash-message {
            padding: 1rem 1.5rem;
            border-radius: 4px;
            background: white;
            box-shadow: var(--shadow-elevated);
            pointer-events: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border-left-width: 4px;
            border-left-style: solid;
            animation: slideDown 0.3s ease;
            font-family: var(--font-sans);
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .flash-message.flash-success {
            border-left-color: var(--flash-success-border);
            background: var(--flash-success-bg);
            color: var(--flash-success-text);
        }
        
        .flash-message.flash-error {
            border-left-color: var(--flash-error-border);
            background: var(--flash-error-bg);
            color: var(--flash-error-text);
        }
        
        .flash-message i {
            font-size: 1.1rem;
            margin-right: 0.5rem;
        }
        
        .flash-success i {
            color: var(--flash-success-icon);
        }
        
        .flash-error i {
            color: var(--flash-error-icon);
        }
        
        .flash-message span {
            flex: 1;
            display: flex;
            align-items: center;
        }
        
        .flash-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            color: currentColor;
            opacity: 0.5;
            padding: 0 0.2rem;
            transition: opacity var(--transition-smooth);
            font-family: var(--font-sans);
        }
        
        .flash-close:hover {
            opacity: 1;
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
           RESPONSIVE BREAKPOINTS - PROPERLY ADJUSTED
           ========================================================================== */
        
        /* Tablet and Mobile - SHOW MOBILE MENU HANDLE, HIDE DESKTOP NAV */
        @media (max-width: 1023px) {
            :root {
                --header-height-desktop: 90px;
                --logo-size-desktop: var(--logo-size-tablet);
                --brand-text-size-2: var(--brand-text-size-2-mobile);
            }
            
            .desktop-nav-container {
                display: none !important;
            }
            
            .desktop-nav {
                display: none !important;
            }
            
            .mobile-menu-toggle-wrapper {
                display: flex !important;
            }
            
            .apply-btn {
                display: none;
            }
            
            .header-container {
                padding: 0 var(--container-padding-tablet);
            }
            
            .brand {
                max-width: 75%;
                gap: 0.9rem;
            }
            
            .brand-logo {
                width: 65px;
                height: 65px;
            }
            
            .brand-line-2::after {
                bottom: -5px;
                width: 45px;
            }
            
            .mobile-nav-overlay {
                top: 90px;
            }
            
            .flash-messages {
                top: calc(var(--header-height-desktop) + 0.5rem);
            }
            
            .site-header.scrolled ~ .flash-messages {
                top: calc(90px + 0.5rem);
            }
        }
        
        /* Tablet specific */
        @media (min-width: 768px) and (max-width: 1023px) {
            .brand-line-1 {
                font-size: 0.75rem;
                letter-spacing: 1.2px;
            }
            
            .brand-line-2 {
                font-size: 1.7rem;
            }
        }
        
        /* Mobile */
        @media (max-width: 767px) {
            :root {
                --header-height-desktop: 85px;
                --logo-size-desktop: var(--logo-size-mobile);
                --brand-text-size-2: 1.5rem;
                --brand-text-size-1: 0.7rem;
            }
            
            .header-container {
                padding: 0 var(--container-padding-mobile);
            }
            
            .brand {
                gap: 0.8rem;
                max-width: 70%;
            }
            
            .brand-logo {
                width: 60px;
                height: 60px;
            }
            
            .brand-logo .logo-fallback {
                font-size: 1.8rem;
            }
            
            .brand-line-1 {
                letter-spacing: 1px;
                white-space: normal;
                word-break: break-word;
            }
            
            .brand-line-2 {
                white-space: normal;
                word-break: break-word;
            }
            
            .brand-line-2::after {
                width: 40px;
                bottom: -5px;
            }
            
            .user-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            
            .mobile-menu-toggle {
                width: 40px;
                height: 40px;
            }
            
            .mobile-nav-overlay {
                top: 85px;
                padding: 1.5rem;
            }
            
            .flash-messages {
                padding: 0 1rem;
                max-width: 100%;
            }
            
            .flash-message {
                padding: 0.8rem 1rem;
                font-size: 0.9rem;
            }
        }
        
        /* Small Mobile */
        @media (max-width: 480px) {
            :root {
                --header-height-desktop: 80px;
                --logo-size-desktop: var(--logo-size-mobile-small);
                --brand-text-size-2: 1.3rem;
                --brand-text-size-1: 0.65rem;
            }
            
            .header-container {
                padding: 0 1rem;
            }
            
            .brand {
                gap: 0.6rem;
                max-width: 75%;
            }
            
            .brand-logo {
                width: 55px;
                height: 55px;
            }
            
            .brand-logo .logo-fallback {
                font-size: 1.6rem;
                letter-spacing: 2px;
            }
            
            .brand-line-1 {
                font-size: 0.6rem;
                letter-spacing: 0.8px;
            }
            
            .brand-line-2 {
                font-size: 1.2rem;
            }
            
            .brand-line-2::after {
                width: 35px;
                height: 2px;
                bottom: -4px;
            }
            
            .user-btn {
                width: 38px;
                height: 38px;
                font-size: 0.95rem;
            }
            
            .mobile-menu-toggle {
                width: 38px;
                height: 38px;
            }
            
            .menu-label {
                font-size: 0.5rem;
            }
            
            .mobile-nav-overlay {
                top: 80px;
                padding: 1rem;
            }
            
            .mobile-nav-link {
                padding: 0.8rem 0;
                font-size: 0.9rem;
            }
        }
        
        /* Extra Small Mobile */
        @media (max-width: 360px) {
            .brand-line-2 {
                font-size: 1.1rem;
            }
            
            .menu-label {
                display: none;
            }
            
            .brand-logo {
                width: 50px;
                height: 50px;
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
            padding-top: calc(var(--header-height-desktop) + var(--nav-height));
            overflow-x: hidden;
        }
        
        .main-content {
            width: 100%;
            max-width: 1440px;
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
    </style>
</head>
<body>

<!-- Flash Messages -->
<div class="flash-messages">
    <?php
    if (class_exists('Session') && method_exists('Session', 'getAllFlash')) {
        $flashMessages = Session::getAllFlash();
        foreach ($flashMessages as $type => $message) {
            if (!empty($message)) {
                $icon = ($type === 'success') ? 'fa-check-circle' : 'fa-exclamation-circle';
                echo '<div class="flash-message flash-' . htmlspecialchars($type) . '">';
                echo '<span><i class="fas ' . $icon . '"></i> ' . htmlspecialchars($message) . '</span>';
                echo '<button class="flash-close" aria-label="Close">&times;</button>';
                echo '</div>';
            }
        }
        // Clear flash messages after displaying
        if (method_exists('Session', 'clearFlash')) {
            Session::clearFlash();
        }
    } elseif (isset($_SESSION['flash']) && is_array($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $type => $message) {
            if (!empty($message)) {
                $icon = ($type === 'success') ? 'fa-check-circle' : 'fa-exclamation-circle';
                echo '<div class="flash-message flash-' . htmlspecialchars($type) . '">';
                echo '<span><i class="fas ' . $icon . '"></i> ' . htmlspecialchars($message) . '</span>';
                echo '<button class="flash-close" aria-label="Close">&times;</button>';
                echo '</div>';
            }
        }
        unset($_SESSION['flash']);
    }
    ?>
</div>

<!-- SOPHISTICATED HEADER - REDUCED LOGO, PERFECTLY FITTED -->
<header class="site-header" role="banner">
    <div class="header-container">
        <!-- BRAND WITH REDUCED LOGO - PROPERLY FITTED -->
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
        
        <!-- HEADER ACTIONS - Updated to point to /apply -->
        <div class="header-actions">
            <a href="<?php echo $baseUrl; ?>/apply" class="apply-btn">
                <i class="fas fa-file-alt"></i>
                <span>Apply Now</span>
            </a>
            
            <?php if ($isLoggedIn): ?>
            <a href="<?php echo $baseUrl; ?>/admin/dashboard" class="user-btn" aria-label="User dashboard">
                <?php echo strtoupper(substr($username, 0, 1)); ?>
            </a>
            <?php endif; ?>
            
            <!-- MOBILE MENU HANDLE -->
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

<!-- DESKTOP NAVIGATION -->
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
                    Programs
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
                    <a href="<?php echo $baseUrl; ?>/research/projects" class="dropdown-link">Projects</a>
                    <a href="<?php echo $baseUrl; ?>/research/facilities" class="dropdown-link">Facilities</a>
                    <a href="<?php echo $baseUrl; ?>/research/grants" class="dropdown-link">Grants & Funding</a>
                </div>
            </li>
            <!-- Student Life Tab - Commented Out
            <li class="desktop-nav-item">
                <a href="<?php echo $baseUrl; ?>/student-life" 
                   class="desktop-nav-link <?php echo $currentPage == 'student-life' ? 'active' : ''; ?>">
                    Student Life
                </a>
            </li>
            -->
            <!-- Faculty Tab - Commented Out
            <li class="desktop-nav-item">
                <a href="<?php echo $baseUrl; ?>/faculty" 
                   class="desktop-nav-link <?php echo $currentPage == 'faculty' ? 'active' : ''; ?>">
                    Faculty
                </a>
            </li>
            -->
            <li class="desktop-nav-item">
                <a href="<?php echo $baseUrl; ?>/news" 
                   class="desktop-nav-link <?php echo $currentPage == 'news' ? 'active' : ''; ?>">
                    News & Events
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

<!-- MOBILE NAVIGATION -->
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
            <!-- Student Life Mobile Tab - Commented Out
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/student-life" 
                   class="mobile-nav-link <?php echo $currentPage == 'student-life' ? 'active' : ''; ?>">
                    <span><i class="fas fa-users"></i>Student Life</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            -->
            <!-- Faculty Mobile Tab - Commented Out
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/faculty" 
                   class="mobile-nav-link <?php echo $currentPage == 'faculty' ? 'active' : ''; ?>">
                    <span><i class="fas fa-chalkboard-teacher"></i>Faculty</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            -->
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/news" 
                   class="mobile-nav-link <?php echo $currentPage == 'news' ? 'active' : ''; ?>">
                    <span><i class="fas fa-newspaper"></i>News & Events</span>
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
            <?php if ($isLoggedIn): ?>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/admin/dashboard" class="mobile-nav-link">
                    <span><i class="fas fa-tachometer-alt"></i>Dashboard</span>
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo $baseUrl; ?>/logout" class="mobile-nav-link">
                    <span><i class="fas fa-sign-out-alt"></i>Logout</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <!-- Mobile Quick Actions - Updated to point to /apply -->
        <div class="mobile-quick-actions">
            <a href="<?php echo $baseUrl; ?>/apply" class="mobile-action-btn">
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
// SOPHISTICATED HEADER FUNCTIONALITY - CLEAN, RELIABLE
// ==============================================

(function() {
    "use strict";
    
    // Toggle Mobile Menu
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
            
            // Animate hamburger to X
            if (isExpanded) {
                menuToggle.children[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                menuToggle.children[1].style.opacity = '0';
                menuToggle.children[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
            } else {
                menuToggle.children[0].style.transform = 'none';
                menuToggle.children[1].style.opacity = '1';
                menuToggle.children[2].style.transform = 'none';
            }
        }
    };
    
    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.site-header');
        const navContainer = document.querySelector('.desktop-nav-container');
        
        // Scroll Effects
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
        
        // Flash Messages - Close button functionality
        document.querySelectorAll('.flash-close').forEach(button => {
            button.addEventListener('click', function() {
                const flashMessage = this.closest('.flash-message');
                flashMessage.style.opacity = '0';
                flashMessage.style.transform = 'translateY(-10px)';
                setTimeout(() => flashMessage.remove(), 250);
            });
        });
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(message => {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-10px)';
                setTimeout(() => message.remove(), 250);
            });
        }, 5000);
        
        // Logo Fallback
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
                
                // Reset hamburger
                if (menuToggle) {
                    menuToggle.children[0].style.transform = 'none';
                    menuToggle.children[1].style.opacity = '1';
                    menuToggle.children[2].style.transform = 'none';
                }
            }
            
            // Update main content padding
            const mainContentWrapper = document.querySelector('.main-content-wrapper');
            if (mainContentWrapper) {
                const headerHeight = header?.offsetHeight || 100;
                if (isDesktop && navContainer) {
                    const navHeight = navContainer.offsetHeight || 48;
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
        
        handleResponsive();
        
        // Prevent horizontal scroll
        document.documentElement.style.overflowX = 'hidden';
        document.body.style.overflowX = 'hidden';
    });
})();
</script>