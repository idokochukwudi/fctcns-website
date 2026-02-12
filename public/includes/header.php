<?php
/**
 * University Header Template - FULL LOGO VISIBILITY EDITION
 * REDESIGNED: No circular gold border, logo fills entire container
 * ENHANCED: Maximum logo presence, clean modern design
 * PERFECTLY BALANCED: Increased size, no overflow, premium aesthetics
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
    
    <!-- Google Fonts - Premium Modern -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700;800;900&family=Inter:wght@600;700;800&display=swap" rel="stylesheet">
    
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
           FULL LOGO VISIBILITY HEADER - NO CIRCULAR BORDER
           Logo takes up entire shape, maximum presence
           Clean modern design, premium aesthetics
           ============================================== */
        
        :root {
            /* ===== PREMIUM COLOR PALETTE ===== */
            --prestige-navy: #0a2342;
            --prestige-navy-dark: #021024;
            --prestige-navy-light: #1e3a5f;
            --prestige-gold: #b4945c;
            --prestige-gold-bold: #d4af37;
            --prestige-gold-light: #e5d3b0;
            --prestige-cream: #faf7f2;
            --prestige-ivory: #f8f4ed;
            --prestige-charcoal: #1e2e3e;
            --prestige-slate: #4a5a6a;
            --prestige-stone: #e0ddd8;
            --prestige-burgundy: #8b3a3a;
            --prestige-white: #ffffff;
            
            /* ===== TYPOGRAPHY ===== */
            --font-serif: 'Cormorant Garamond', Georgia, serif;
            --font-sans: 'Inter', -apple-system, sans-serif;
            
            /* ===== OPTIMAL DIMENSIONS - PERFECTLY BALANCED ===== */
            --header-height-desktop: 110px;
            --header-height-mobile: 100px;
            --nav-height: 50px;
            
            /* ===== FULL LOGO VISIBILITY - NO BORDER, MAXIMUM SIZE ===== */
            --logo-size-desktop: 90px;        /* Logo fills entire container */
            --logo-size-tablet: 85px;
            --logo-size-mobile: 80px;
            --logo-size-mobile-small: 75px;
            --logo-padding: 0px;              /* NO PADDING - logo uses full space */
            
            /* ===== ENHANCED TYPOGRAPHY SIZE ===== */
            --brand-text-size-1: 0.95rem;
            --brand-text-size-2: 2.1rem;
            --nav-text-size: 0.95rem;
            --button-text-size: 0.95rem;
            
            /* ===== CONTAINER PADDING ===== */
            --container-padding-desktop: 2.2rem;
            --container-padding-tablet: 1.8rem;
            --container-padding-mobile: 1.5rem;
            
            /* ===== CLEAN SHADOWS ===== */
            --shadow-subtle: 0 4px 12px rgba(0,0,0,0.03);
            --shadow-medium: 0 8px 20px rgba(0,0,0,0.06);
            --shadow-premium: 0 12px 28px rgba(10,35,66,0.08);
            
            /* ===== SMOOTH TRANSITIONS ===== */
            --transition-smooth: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
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
           PREMIUM HEADER - CLEAN, MODERN, BOLD
           ========================================================================== */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border-bottom: 3px solid var(--prestige-gold-bold);
            height: var(--header-height-desktop);
            width: 100%;
            max-width: 100vw;
            box-shadow: var(--shadow-subtle);
            transition: all var(--transition-smooth);
        }
        
        .site-header.scrolled {
            height: 100px;
            box-shadow: var(--shadow-medium);
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
           FULL LOGO VISIBILITY - NO CIRCULAR BORDER, MAXIMUM PRESENCE
           Logo takes up 100% of container - clean, modern, impactful
           ========================================================================== */
        .brand {
            display: flex;
            align-items: center;
            gap: 1.4rem;
            text-decoration: none;
            color: inherit;
            flex-shrink: 0;
            max-width: 80%;
        }
        
        /* LOGO CONTAINER - NO BORDER, NO PADDING, FULL LOGO VISIBILITY */
        .brand-logo {
            width: var(--logo-size-desktop);
            height: var(--logo-size-desktop);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border-radius: 0;              /* NO CIRCULAR BORDER */
            border: none;                 /* NO BORDER AT ALL */
            overflow: hidden;
            transition: transform var(--transition-smooth);
            box-shadow: none;             /* NO SHADOW ON CONTAINER */
            position: relative;
        }
        
        .brand:hover .brand-logo {
            transform: scale(1.08);
        }
        
        /* LOGO IMAGE - FULL WIDTH, NO PADDING, MAXIMUM VISIBILITY */
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;          /* Maintains aspect ratio */
            object-position: center;
            padding: 0;                  /* NO PADDING - full logo visibility */
            display: block;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.08));
            transition: filter var(--transition-smooth);
        }
        
        .brand:hover .brand-logo img {
            filter: drop-shadow(0 8px 16px rgba(0,0,0,0.12));
        }
        
        /* FALLBACK LOGO - CLEAN RECTANGULAR DESIGN */
        .brand-logo .logo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, var(--prestige-navy), var(--prestige-navy-dark));
            color: var(--prestige-gold-bold);
            font-family: var(--font-serif);
            font-weight: 900;
            font-size: 2.4rem;
            letter-spacing: 4px;
            border-radius: 0;            /* SQUARE/SHAPE MATCHES LOGO */
            box-shadow: var(--shadow-premium);
        }
        
        /* ==========================================================================
           PREMIUM TYPOGRAPHY - MAXIMUM VISIBILITY, BOLD PRESENCE
           ========================================================================== */
        .brand-text {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
        }
        
        .brand-name {
            font-family: var(--font-serif);
            line-height: 1.1;
        }
        
        /* "FCT College of" - COMMANDING, AUTHORITATIVE */
        .brand-line-1 {
            font-size: var(--brand-text-size-1);
            font-weight: 800;
            color: var(--prestige-navy-dark);
            letter-spacing: 2.2px;
            text-transform: uppercase;
            margin-bottom: 6px;
            font-family: var(--font-sans);
            white-space: nowrap;
            text-shadow: 0 1px 2px rgba(255,255,255,0.8);
        }
        
        /* "Nursing Sciences" - MASTERFUL, ELEGANT */
        .brand-line-2 {
            font-size: var(--brand-text-size-2);
            font-weight: 900;
            color: var(--prestige-navy);
            letter-spacing: -0.5px;
            font-family: var(--font-serif);
            line-height: 1;
            position: relative;
            display: inline-block;
            white-space: nowrap;
            text-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }
        
        /* SIGNATURE GOLD UNDERLINE - ELEGANT ACCENT */
        .brand-line-2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 70px;
            height: 4px;
            background: linear-gradient(90deg, var(--prestige-gold-bold), var(--prestige-gold-light));
            border-radius: 4px;
            transition: width var(--transition-smooth);
            box-shadow: 0 2px 6px rgba(212, 175, 55, 0.3);
        }
        
        .brand:hover .brand-line-2::after {
            width: 120px;
        }
        
        /* ==========================================================================
           PREMIUM HEADER ACTIONS - BOLD, CLEAN
           ========================================================================== */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            flex-shrink: 0;
        }
        
        .apply-btn {
            padding: 0.7rem 1.8rem;
            background: linear-gradient(135deg, var(--prestige-navy), var(--prestige-navy-dark));
            color: white;
            border: none;
            border-radius: 50px;
            font-size: var(--button-text-size);
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            white-space: nowrap;
            transition: all var(--transition-smooth);
            border: 1.5px solid var(--prestige-gold-light);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: var(--shadow-subtle);
        }
        
        .apply-btn i {
            font-size: 0.9rem;
        }
        
        .apply-btn:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-premium);
            border-color: var(--prestige-gold-bold);
            background: linear-gradient(135deg, var(--prestige-navy-dark), var(--prestige-navy));
        }
        
        .user-btn {
            width: 48px;
            height: 48px;
            border-radius: 12px;          /* SQUARE WITH ROUNDED CORNERS - MODERN */
            background: var(--prestige-ivory);
            color: var(--prestige-navy);
            border: 2.5px solid var(--prestige-gold-bold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.3rem;
            transition: all var(--transition-smooth);
            text-decoration: none;
            box-shadow: var(--shadow-subtle);
        }
        
        .user-btn:hover {
            background: var(--prestige-navy);
            color: white;
            border-color: var(--prestige-gold);
            transform: translateY(-4px) rotate(5deg);
            box-shadow: var(--shadow-premium);
        }
        
        /* ==========================================================================
           PREMIUM MOBILE MENU - MODERN, CLEAN
           ========================================================================== */
        .mobile-menu-toggle-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
        
        .mobile-menu-toggle {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            border: 2.5px solid var(--prestige-gold-bold);
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all var(--transition-smooth);
            box-shadow: var(--shadow-subtle);
        }
        
        .mobile-menu-toggle span {
            display: block;
            width: 26px;
            height: 3px;
            background: var(--prestige-navy);
            transition: all var(--transition-smooth);
            border-radius: 3px;
        }
        
        .mobile-menu-toggle:hover {
            background: var(--prestige-ivory);
            border-color: var(--prestige-gold);
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }
        
        .menu-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--prestige-navy);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        
        /* ==========================================================================
           PREMIUM DESKTOP NAVIGATION - BOLD, CLEAN TABS
           ========================================================================== */
        .desktop-nav-container {
            position: fixed;
            top: var(--header-height-desktop);
            left: 0;
            right: 0;
            z-index: 999;
            background: white;
            border-bottom: 3px solid var(--prestige-gold-light);
            height: var(--nav-height);
            width: 100%;
            max-width: 100vw;
            box-shadow: var(--shadow-subtle);
        }
        
        .site-header.scrolled + .desktop-nav-container {
            top: 100px;
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
            justify-content: flex-start;
            width: 100%;
            max-width: 1440px;
            gap: 0.4rem;
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
            color: var(--prestige-charcoal);
            text-decoration: none;
            font-weight: 600;
            font-size: var(--nav-text-size);
            transition: all var(--transition-smooth);
            position: relative;
            white-space: nowrap;
            letter-spacing: 0.3px;
            border-bottom: 3px solid transparent;
        }
        
        .desktop-nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: var(--prestige-gold-bold);
            transition: width var(--transition-smooth);
            border-radius: 3px 3px 0 0;
            box-shadow: 0 -2px 6px rgba(212, 175, 55, 0.2);
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
            font-weight: 800;
        }
        
        /* PREMIUM DROPDOWN - CLEAN, MODERN */
        .dropdown-menu {
            position: absolute;
            top: calc(100% - 2px);
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
            background: white;
            min-width: 240px;
            border-radius: 0 0 16px 16px;
            box-shadow: var(--shadow-premium);
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-smooth);
            z-index: 100;
            border: 1px solid var(--prestige-gold-light);
            border-top: 4px solid var(--prestige-gold-bold);
            padding: 0.8rem 0;
        }
        
        .has-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
        
        .dropdown-link {
            display: block;
            padding: 0.7rem 1.8rem;
            color: var(--prestige-charcoal);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all var(--transition-smooth);
            position: relative;
            white-space: nowrap;
        }
        
        .dropdown-link:hover {
            background: rgba(212, 175, 55, 0.06);
            color: var(--prestige-navy);
            padding-left: 2.3rem;
            font-weight: 700;
        }
        
        .dropdown-link::before {
            content: '→';
            position: absolute;
            left: 1.3rem;
            opacity: 0;
            transition: all var(--transition-smooth);
            color: var(--prestige-gold-bold);
            font-size: 1rem;
            font-weight: 700;
        }
        
        .dropdown-link:hover::before {
            opacity: 1;
            left: 1.6rem;
        }
        
        /* DISTINCTIVE CONTACT TAB */
        .desktop-nav-item.contact-tab .desktop-nav-link {
            color: var(--prestige-burgundy);
            background: rgba(139, 58, 58, 0.03);
            border-left: 1px solid rgba(139, 58, 58, 0.15);
            border-right: 1px solid rgba(139, 58, 58, 0.15);
            font-weight: 700;
            margin-left: 0.5rem;
            padding: 0 1.3rem;
        }
        
        .desktop-nav-item.contact-tab .desktop-nav-link i {
            margin-right: 8px;
            font-size: 0.85rem;
        }
        
        .desktop-nav-item.contact-tab .desktop-nav-link:hover {
            background: var(--prestige-burgundy);
            color: white;
        }
        
        .desktop-nav-item.contact-tab .desktop-nav-link:hover i {
            color: white;
        }
        
        /* ==========================================================================
           PREMIUM MOBILE NAVIGATION - CLEAN, MODERN
           ========================================================================== */
        .mobile-nav-overlay {
            position: fixed;
            top: var(--header-height-desktop);
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            padding: 2rem;
            width: 100%;
            border-top: 3px solid var(--prestige-gold-bold);
        }
        
        .site-header.scrolled ~ .mobile-nav-overlay {
            top: 100px;
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
            margin-bottom: 2rem;
        }
        
        .mobile-nav-item {
            border-bottom: 1px solid var(--prestige-stone);
        }
        
        .mobile-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.1rem 0;
            color: var(--prestige-charcoal);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all var(--transition-smooth);
        }
        
        .mobile-nav-link span i {
            width: 28px;
            color: var(--prestige-gold-bold);
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        .mobile-nav-link:hover {
            color: var(--prestige-navy);
            padding-left: 0.8rem;
        }
        
        .mobile-nav-link.active {
            color: var(--prestige-navy);
            font-weight: 800;
            border-left: 4px solid var(--prestige-gold-bold);
            padding-left: 1rem;
        }
        
        .mobile-nav-item.contact-tab .mobile-nav-link {
            color: var(--prestige-burgundy);
            background: rgba(139, 58, 58, 0.03);
            padding: 1.1rem 1rem;
            margin: 0.8rem 0;
            border-radius: 12px;
            border: 1px solid rgba(139, 58, 58, 0.15);
            font-weight: 700;
        }
        
        .mobile-quick-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .mobile-action-btn {
            padding: 1.1rem 1.5rem;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--prestige-navy), var(--prestige-navy-dark));
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all var(--transition-smooth);
            font-weight: 700;
            justify-content: center;
            font-size: 1rem;
            border: 1.5px solid var(--prestige-gold-light);
            letter-spacing: 0.5px;
        }
        
        .mobile-action-btn.accent {
            background: linear-gradient(135deg, var(--prestige-gold-bold), var(--prestige-gold));
            color: var(--prestige-navy-dark);
            border-color: var(--prestige-navy);
        }
        
        .mobile-contact-info {
            padding-top: 2rem;
            border-top: 2px solid var(--prestige-gold-light);
        }
        
        .mobile-contact-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .mobile-contact-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            color: var(--prestige-slate);
            text-decoration: none;
            font-size: 0.95rem;
            padding: 0.8rem;
            border-radius: 10px;
            transition: all var(--transition-smooth);
            font-weight: 500;
            border: 1px solid transparent;
        }
        
        .mobile-contact-item i {
            color: var(--prestige-gold-bold);
            width: 24px;
            font-size: 1.1rem;
        }
        
        .mobile-contact-item:hover {
            background: var(--prestige-ivory);
            border-color: var(--prestige-gold-light);
            color: var(--prestige-navy);
        }
        
        /* ==========================================================================
           RESPONSIVE BREAKPOINTS - PERFECT SCALING
           ========================================================================== */
        
        /* Extra Large Desktop */
        @media (min-width: 1400px) {
            :root {
                --header-height-desktop: 120px;
                --logo-size-desktop: 100px;
                --brand-text-size-2: 2.4rem;
                --brand-text-size-1: 1.1rem;
            }
            
            .header-container {
                max-width: 1600px;
            }
            
            .desktop-nav-menu {
                max-width: 1600px;
            }
        }
        
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
                --header-height-desktop: 105px;
                --logo-size-desktop: var(--logo-size-tablet);
                --brand-text-size-2: 1.9rem;
            }
            
            .header-container {
                padding: 0 var(--container-padding-tablet);
            }
            
            .brand-logo {
                width: 85px;
                height: 85px;
            }
            
            .desktop-nav-container {
                display: none !important;
            }
            
            .apply-btn {
                display: none;
            }
            
            .main-content-wrapper {
                padding-top: 105px;
            }
            
            .mobile-nav-overlay {
                top: 105px;
            }
        }
        
        /* Mobile */
        @media (max-width: 767px) {
            :root {
                --header-height-desktop: 100px;
                --logo-size-desktop: var(--logo-size-mobile);
                --brand-text-size-2: 1.8rem;
                --brand-text-size-1: 0.85rem;
            }
            
            .header-container {
                padding: 0 var(--container-padding-mobile);
            }
            
            .brand {
                gap: 1rem;
                max-width: 70%;
            }
            
            .brand-logo {
                width: 80px;
                height: 80px;
            }
            
            .brand-line-1 {
                white-space: normal;
                word-break: break-word;
                font-size: 0.8rem;
            }
            
            .brand-line-2 {
                white-space: normal;
                word-break: break-word;
                font-size: 1.7rem;
            }
            
            .brand-line-2::after {
                width: 55px;
                bottom: -8px;
            }
            
            .desktop-nav-container {
                display: none !important;
            }
            
            .apply-btn {
                display: none;
            }
            
            .main-content-wrapper {
                padding-top: 100px;
            }
            
            .mobile-nav-overlay {
                top: 100px;
                padding: 1.5rem;
            }
        }
        
        /* Small Mobile */
        @media (max-width: 480px) {
            :root {
                --header-height-desktop: 95px;
                --logo-size-desktop: var(--logo-size-mobile-small);
                --brand-text-size-2: 1.5rem;
                --brand-text-size-1: 0.75rem;
            }
            
            .header-container {
                padding: 0 1.2rem;
            }
            
            .brand {
                gap: 0.8rem;
                max-width: 75%;
            }
            
            .brand-logo {
                width: 75px;
                height: 75px;
            }
            
            .brand-logo .logo-fallback {
                font-size: 1.8rem;
            }
            
            .brand-line-1 {
                font-size: 0.7rem;
                letter-spacing: 1.2px;
                margin-bottom: 4px;
            }
            
            .brand-line-2 {
                font-size: 1.4rem;
            }
            
            .brand-line-2::after {
                width: 50px;
                height: 3px;
                bottom: -7px;
            }
            
            .user-btn {
                width: 42px;
                height: 42px;
                font-size: 1.1rem;
            }
            
            .mobile-menu-toggle {
                width: 42px;
                height: 42px;
            }
            
            .menu-label {
                font-size: 0.6rem;
            }
            
            .main-content-wrapper {
                padding-top: 95px;
            }
            
            .mobile-nav-overlay {
                top: 95px;
                padding: 1.2rem;
            }
        }
        
        /* Extra Small Mobile */
        @media (max-width: 360px) {
            :root {
                --logo-size-desktop: 70px;
                --brand-text-size-2: 1.3rem;
            }
            
            .brand-logo {
                width: 70px;
                height: 70px;
            }
            
            .brand-line-2 {
                font-size: 1.3rem;
            }
            
            .menu-label {
                display: none;
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
        
        /* ==========================================================================
           FLASH MESSAGES - PREMIUM NOTIFICATIONS
           ========================================================================== */
        .flash-messages {
            position: fixed;
            top: calc(var(--header-height-desktop) + 1rem);
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
            border-left: 5px solid var(--prestige-gold-bold);
            border-radius: 12px;
            padding: 1rem 1.8rem;
            margin-bottom: 0.8rem;
            box-shadow: var(--shadow-premium);
            animation: slideDown 0.3s ease;
            pointer-events: auto;
            max-width: 600px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            border: 1px solid var(--prestige-gold-light);
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

<!-- PREMIUM HEADER - FULL LOGO VISIBILITY, NO CIRCULAR BORDER -->
<header class="site-header" role="banner">
    <div class="header-container">
        <!-- BRAND WITH FULL VISIBILITY LOGO - NO BORDER, MAXIMUM PRESENCE -->
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
        
        <!-- HEADER ACTIONS - PREMIUM BUTTONS -->
        <div class="header-actions">
            <a href="<?php echo $baseUrl; ?>/admissions" class="apply-btn">
                <i class="fas fa-file-alt"></i>
                <span>Apply Now</span>
            </a>
            
            <?php if ($isLoggedIn): ?>
            <a href="<?php echo $baseUrl; ?>/student/dashboard" class="user-btn" aria-label="User dashboard">
                <?php echo strtoupper(substr($username, 0, 1)); ?>
            </a>
            <?php endif; ?>
            
            <!-- MOBILE MENU TOGGLE -->
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

<!-- DESKTOP NAVIGATION - CLEAN, MODERN TABS -->
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

<!-- PREMIUM MOBILE NAVIGATION -->
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
                <a href="<?php echo $baseUrl; ?>/student/dashboard" class="mobile-nav-link">
                    <span><i class="fas fa-tachometer-alt"></i>Dashboard</span>
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </li>
            <?php endif; ?>
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
            <?php if ($isLoggedIn): ?>
            <a href="<?php echo $baseUrl; ?>/student/logout" class="mobile-action-btn" style="background: var(--prestige-burgundy);">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
            <?php endif; ?>
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
// PREMIUM HEADER FUNCTIONALITY - SMOOTH, CLEAN
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
                menuToggle.children[0].style.transform = 'rotate(45deg) translate(6px, 6px)';
                menuToggle.children[1].style.opacity = '0';
                menuToggle.children[2].style.transform = 'rotate(-45deg) translate(6px, -6px)';
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
            if (window.scrollY > 40) {
                header.classList.add('scrolled');
                if (navContainer) navContainer.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
                if (navContainer) navContainer.classList.remove('scrolled');
            }
        }
        
        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll();
        
        // Flash Messages
        document.querySelectorAll('.flash-close').forEach(button => {
            button.addEventListener('click', function() {
                const flashMessage = this.closest('.flash-message');
                flashMessage.style.opacity = '0';
                flashMessage.style.transform = 'translateY(-10px)';
                setTimeout(() => flashMessage.remove(), 300);
            });
        });
        
        // Auto-hide flash messages
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(message => {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-10px)';
                setTimeout(() => message.remove(), 300);
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
                const headerHeight = header?.offsetHeight || 110;
                if (isDesktop && navContainer) {
                    const navHeight = navContainer.offsetHeight || 50;
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