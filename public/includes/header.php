<?php
// Base URL for all links - includes /public since that's where index.php lives
$baseUrl = defined('BASE_URL') ? BASE_URL : '/fctcns-website/public';

// Get current page from REQUEST_URI (works with custom routers)
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Remove base path to get clean route
if (strpos($path, '/fctcns-website/public') === 0) {
    $path = substr($path, strlen('/fctcns-website/public'));
}

// Ensure path starts with /
if (empty($path) || $path[0] !== '/') {
    $path = '/' . $path;
}

// Get the page name from the path
$pathParts = explode('/', trim($path, '/'));
$currentPage = !empty($pathParts[0]) ? $pathParts[0] : 'home';

// Normalize the page name
$currentPage = strtolower(trim($currentPage));

// Check if user is logged in (for admin menu)
$isLoggedIn = false;
$userRole = '';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    $isLoggedIn = true;
    $userRole = $_SESSION['user_role'];
}

// Define color variables for consistency
$color_primary = '#6B4E9B';
$color_primary_dark = '#5a4185';
$color_primary_light = '#8B6CB5';
$color_secondary = '#7FB285';
$color_success = '#48BB78';
$color_gray_800 = '#2C3E50';
$color_gray_700 = '#4A5568';
$color_gray_300 = '#CBD5E0';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FCT College of Nursing Sciences - Premier institution for nursing education and healthcare professional development">
    <meta name="keywords" content="nursing school, healthcare education, nursing programs, medical training, FCT nursing, NMCN accredited, NBTE accredited">
    <title>FCT College of Nursing Sciences - <?php 
        $titles = [
            'home' => 'Home',
            'index' => 'Home',
            'about' => 'About Us',
            'programs' => 'Academic Programs',
            'admissions' => 'Admissions',
            'research' => 'Research',
            'contact' => 'Contact Us',
            'admin' => 'Administrator Portal',
            'applications' => 'Applications',
            'news' => 'News & Events',
            'faculty' => 'Faculty',
            'alumni' => 'Alumni',
            'student-life' => 'Student Life',
            'library' => 'Library'
        ];
        echo isset($titles[$currentPage]) ? $titles[$currentPage] : 'Empowering Future Healthcare Professionals';
    ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts - World-class typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>/assets/images/logo/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>/assets/images/logo/apple-touch-icon.png">
    
    <!-- Page-specific CSS -->
    <style>
        /* World-Class University Design System */
        :root {
            --color-primary: <?php echo $color_primary; ?>;
            --color-primary-dark: <?php echo $color_primary_dark; ?>;
            --color-primary-light: <?php echo $color_primary_light; ?>;
            --color-primary-alpha-05: rgba(107, 78, 155, 0.05);
            --color-primary-alpha-10: rgba(107, 78, 155, 0.1);
            --color-primary-alpha-15: rgba(107, 78, 155, 0.15);
            --color-primary-alpha-20: rgba(107, 78, 155, 0.2);
            --color-secondary: <?php echo $color_secondary; ?>;
            --color-secondary-light: rgba(127, 178, 133, 0.1);
            --color-success: <?php echo $color_success; ?>;
            --color-white: #FFFFFF;
            --color-gray-50: #FAFBFC;
            --color-gray-100: #F5F7FA;
            --color-gray-200: #E4E7EB;
            --color-gray-300: <?php echo $color_gray_300; ?>;
            --color-gray-400: #A0AEC0;
            --color-gray-500: #718096;
            --color-gray-600: #5A6C7D;
            --color-gray-700: <?php echo $color_gray_700; ?>;
            --color-gray-800: <?php echo $color_gray_800; ?>;
            --color-error: #F56565;
            --color-warning: #ED8936;
            --color-info: #4299E1;
            
            --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-display: 'Playfair Display', Georgia, serif;
            
            --navbar-height: 72px;
            --mobile-nav-height: 64px;
            --tabs-height: 56px;
            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.03);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.08);
            --shadow-xl: 0 12px 24px rgba(0, 0, 0, 0.1);
            --transition-fast: 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-normal: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-primary);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Premium Navigation Bar - Transparent Professional Design */
        .navbar {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: saturate(200%) blur(30px);
            -webkit-backdrop-filter: saturate(200%) blur(30px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 1px 20px rgba(107, 78, 155, 0.08);
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all var(--transition-normal);
        }
        
        .navbar.scrolled {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.92) 100%);
            backdrop-filter: saturate(200%) blur(40px);
            -webkit-backdrop-filter: saturate(200%) blur(40px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 30px rgba(107, 78, 155, 0.12);
        }
        
        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        /* Premium Logo & Brand */
        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 1rem;
            transition: all var(--transition-normal);
            position: relative;
            z-index: 1001;
        }
        
        .navbar-brand:hover {
            transform: translateY(-1px);
        }
        
        .navbar-logo {
            width: 48px;
            height: 48px;
            position: relative;
            flex-shrink: 0;
        }
        
        .navbar-logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
            transition: transform var(--transition-normal);
        }
        
        .navbar-brand:hover .navbar-logo-img {
            transform: scale(1.05);
        }
        
        .brand-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .navbar-title {
            color: var(--color-gray-800);
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin: 0;
        }
        
        .navbar-subtitle {
            color: var(--color-gray-600);
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
        }
        
        /* Main Navigation Tabs */
        .nav-tabs-container {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            right: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: saturate(200%) blur(30px);
            -webkit-backdrop-filter: saturate(200%) blur(30px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 15px rgba(107, 78, 155, 0.05);
            height: var(--tabs-height);
            z-index: 999;
            transition: all var(--transition-normal);
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }
        
        .navbar.scrolled + .nav-tabs-container {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.92) 100%);
            backdrop-filter: saturate(200%) blur(40px);
            -webkit-backdrop-filter: saturate(200%) blur(40px);
        }
        
        .nav-tabs-wrapper {
            max-width: 1440px;
            margin: 0 auto;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 2rem;
        }
        
        .nav-tabs {
            display: flex;
            align-items: center;
            gap: 0;
            margin: 0;
            padding: 0;
            list-style: none;
            height: 100%;
        }
        
        .tab-item {
            position: relative;
            height: 100%;
        }
        
        .tab-link {
            color: var(--color-gray-700);
            text-decoration: none;
            padding: 0 1.25rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all var(--transition-normal);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            height: 100%;
            white-space: nowrap;
            position: relative;
        }
        
        /* Sophisticated hover effect */
        .tab-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1.25rem;
            right: 1.25rem;
            height: 3px;
            background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
            border-radius: 3px 3px 0 0;
            opacity: 0;
            transform: scaleX(0.8);
            transition: all var(--transition-normal);
        }
        
        .tab-link:hover {
            color: var(--color-primary);
        }
        
        .tab-link:hover::after {
            opacity: 0.5;
            transform: scaleX(1);
        }
        
        /* Active state */
        .tab-link.active {
            color: var(--color-primary) !important;
            font-weight: 600 !important;
        }
        
        .tab-link.active::after {
            opacity: 1;
            transform: scaleX(1);
        }
        
        .tab-icon {
            font-size: 0.875rem;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Mobile Tabs Menu */
        .mobile-tabs-menu {
            display: none;
            position: fixed;
            top: var(--mobile-nav-height);
            left: 0;
            right: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%);
            backdrop-filter: saturate(200%) blur(40px);
            -webkit-backdrop-filter: saturate(200%) blur(40px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 20px rgba(107, 78, 155, 0.15);
            z-index: 998;
            padding: 1rem;
            transform: translateY(-100%);
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-slow);
        }
        
        .mobile-tabs-menu.active {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        
        .mobile-tabs-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .mobile-tab-item {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(107, 78, 155, 0.1);
            border-radius: 12px;
            padding: 1rem 0.75rem;
            text-decoration: none;
            transition: all var(--transition-normal);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            text-align: center;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .mobile-tab-item:hover {
            background: rgba(107, 78, 155, 0.05);
            border-color: var(--color-primary-alpha-20);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 78, 155, 0.1);
        }
        
        .mobile-tab-item.active {
            background: linear-gradient(135deg, var(--color-primary-alpha-15), var(--color-secondary-light));
            border-color: var(--color-primary-alpha-20);
            box-shadow: inset 0 2px 4px rgba(107, 78, 155, 0.1);
        }
        
        .mobile-tab-icon {
            font-size: 1.25rem;
            color: var(--color-primary);
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(107, 78, 155, 0.08);
            border-radius: 10px;
        }
        
        .mobile-tab-text {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--color-gray-700);
            line-height: 1.2;
        }
        
        .mobile-tab-item.active .mobile-tab-text {
            color: var(--color-primary);
            font-weight: 600;
        }
        
        /* Desktop Action Buttons */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        /* Premium Search Bar */
        .search-container {
            position: relative;
        }
        
        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .search-input {
            padding: 0.625rem 1rem;
            padding-left: 2.75rem;
            border: 1.5px solid rgba(107, 78, 155, 0.1);
            border-radius: 10px;
            font-size: 0.875rem;
            width: 200px;
            background: rgba(255, 255, 255, 0.8);
            transition: all var(--transition-normal);
            font-family: var(--font-primary);
            color: var(--color-gray-800);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .search-input::placeholder {
            color: var(--color-gray-500);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-alpha-10);
            width: 240px;
            background: rgba(255, 255, 255, 0.95);
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-gray-500);
            font-size: 0.875rem;
            transition: color var(--transition-fast);
            pointer-events: none;
        }
        
        .search-input:focus + .search-icon {
            color: var(--color-primary);
        }
        
        /* User/Portal Button */
        <?php if ($isLoggedIn): ?>
        /* User Menu (Logged In) */
        .user-menu {
            position: relative;
        }
        
        .user-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
            color: var(--color-white);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all var(--transition-normal);
            box-shadow: 0 2px 8px rgba(107, 78, 155, 0.2);
            letter-spacing: -0.01em;
            cursor: pointer;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .user-btn:hover {
            background: linear-gradient(135deg, var(--color-primary-dark), #4a3670);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(107, 78, 155, 0.3);
            color: var(--color-white);
        }
        
        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        .user-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: saturate(200%) blur(40px);
            -webkit-backdrop-filter: saturate(200%) blur(40px);
            min-width: 200px;
            border-radius: 12px;
            box-shadow: var(--shadow-xl);
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all var(--transition-normal);
            z-index: 100;
            border: 1px solid rgba(0, 0, 0, 0.06);
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
            font-weight: 600;
            color: var(--color-gray-800);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: var(--color-gray-600);
        }
        
        .user-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--color-gray-700);
            text-decoration: none;
            transition: all var(--transition-fast);
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 8px;
        }
        
        .user-item:hover {
            background: var(--color-primary-alpha-10);
            color: var(--color-primary);
        }
        
        <?php else: ?>
        /* Portal Button (Not Logged In) */
        .portal-btn {
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
            color: var(--color-white);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all var(--transition-normal);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 8px rgba(107, 78, 155, 0.2);
            letter-spacing: -0.01em;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .portal-btn:hover {
            background: linear-gradient(135deg, var(--color-primary-dark), #4a3670);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(107, 78, 155, 0.3);
            color: var(--color-white);
        }
        <?php endif; ?>
        
        /* Mobile Menu Toggle */
        .mobile-tabs-toggle {
            display: none;
            background: rgba(255, 255, 255, 0.8);
            border: 1.5px solid rgba(107, 78, 155, 0.1);
            width: 40px;
            height: 40px;
            cursor: pointer;
            border-radius: 10px;
            transition: all var(--transition-fast);
            position: relative;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .mobile-tabs-toggle:hover {
            background: rgba(107, 78, 155, 0.05);
            border-color: var(--color-primary-alpha-20);
        }
        
        .mobile-tabs-toggle-icon {
            position: absolute;
            width: 20px;
            height: 2px;
            background: var(--color-primary);
            border-radius: 2px;
            transition: var(--transition-normal);
            left: 10px;
            top: 19px;
        }
        
        .mobile-tabs-toggle-icon:before,
        .mobile-tabs-toggle-icon:after {
            content: '';
            position: absolute;
            width: 20px;
            height: 2px;
            background: var(--color-primary);
            border-radius: 2px;
            transition: var(--transition-normal);
        }
        
        .mobile-tabs-toggle-icon:before {
            top: -6px;
        }
        
        .mobile-tabs-toggle-icon:after {
            top: 6px;
        }
        
        .mobile-tabs-toggle.active .mobile-tabs-toggle-icon {
            background: transparent;
        }
        
        .mobile-tabs-toggle.active .mobile-tabs-toggle-icon:before {
            transform: rotate(45deg);
            top: 0;
        }
        
        .mobile-tabs-toggle.active .mobile-tabs-toggle-icon:after {
            transform: rotate(-45deg);
            top: 0;
        }
        
        /* Responsive Design - Tablet */
        @media (max-width: 1200px) {
            .navbar-container,
            .nav-tabs-wrapper {
                padding: 0 1.5rem;
            }
            
            .tab-link {
                padding: 0 1rem;
                font-size: 0.8125rem;
            }
            
            .search-input {
                width: 180px;
            }
            
            .search-input:focus {
                width: 200px;
            }
        }
        
        /* Responsive Design - Mobile */
        @media (max-width: 992px) {
            .navbar {
                height: var(--mobile-nav-height);
            }
            
            .navbar-container {
                padding: 0 1rem;
            }
            
            .navbar-logo {
                width: 42px;
                height: 42px;
            }
            
            .navbar-title {
                font-size: 0.95rem;
            }
            
            .navbar-subtitle {
                font-size: 0.65rem;
            }
            
            /* Hide desktop tabs on mobile */
            .nav-tabs-container {
                display: none;
            }
            
            /* Show mobile tabs toggle */
            .mobile-tabs-toggle {
                display: block;
                margin-left: auto;
                margin-right: 1rem;
            }
            
            /* Adjust mobile tabs grid */
            .mobile-tabs-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 0.5rem;
                padding: 0 0.5rem;
            }
            
            .mobile-tab-item {
                padding: 0.75rem 0.5rem;
            }
            
            .mobile-tab-icon {
                font-size: 1.1rem;
                width: 28px;
                height: 28px;
            }
            
            .mobile-tab-text {
                font-size: 0.7rem;
            }
        }
        
        /* Small mobile devices */
        @media (max-width: 768px) {
            .mobile-tabs-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .search-input {
                width: 160px;
                font-size: 0.8125rem;
                padding: 0.5rem 0.75rem;
                padding-left: 2.5rem;
            }
            
            .search-input:focus {
                width: 180px;
            }
            
            .search-icon {
                left: 0.75rem;
                font-size: 0.8125rem;
            }
            
            .portal-btn,
            .user-btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.8125rem;
            }
            
            .user-avatar {
                width: 24px;
                height: 24px;
                font-size: 0.7rem;
            }
        }
        
        /* Extra small mobile devices */
        @media (max-width: 480px) {
            .navbar-title {
                font-size: 0.85rem;
            }
            
            .navbar-subtitle {
                display: none;
            }
            
            .mobile-tabs-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .search-input {
                display: none;
            }
            
            .search-container {
                display: none;
            }
            
            .nav-actions {
                gap: 0.5rem;
            }
        }
        
        /* Smooth scrolling behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Print styles */
        @media print {
            .navbar {
                position: relative;
                box-shadow: none;
                background: white !important;
            }
            
            .nav-tabs-container,
            .mobile-tabs-toggle,
            .mobile-tabs-menu,
            .search-container {
                display: none;
            }
        }
        
        /* Scrollbar styling for tabs */
        .nav-tabs-container::-webkit-scrollbar {
            height: 4px;
        }
        
        .nav-tabs-container::-webkit-scrollbar-track {
            background: rgba(107, 78, 155, 0.05);
        }
        
        .nav-tabs-container::-webkit-scrollbar-thumb {
            background: rgba(107, 78, 155, 0.2);
            border-radius: 2px;
        }
        
        .nav-tabs-container::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 78, 155, 0.3);
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="navbar-container">
        <!-- Logo and Brand -->
        <a href="<?php echo $baseUrl; ?>/" class="navbar-brand" aria-label="FCT College of Nursing Sciences Home">
            <div class="navbar-logo">
                <img src="<?php echo $baseUrl; ?>/assets/images/logo/logo.png" 
                     alt="FCT College of Nursing Sciences" 
                     class="navbar-logo-img">
            </div>
            <div class="brand-content">
                <h1 class="navbar-title">FCT College of Nursing Sciences</h1>
                <p class="navbar-subtitle">NMCN & NBTE Accredited</p>
            </div>
        </a>

        <!-- Desktop Actions -->
        <div class="nav-actions">
            <!-- Search Bar -->
            <div class="search-container">
                <div class="search-wrapper">
                    <input type="text" 
                           class="search-input" 
                           placeholder="Search..."
                           aria-label="Search website">
                    <span class="search-icon" aria-hidden="true">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
            
            <!-- User/Portal Button -->
            <?php if ($isLoggedIn): ?>
            <!-- User Menu (Logged In) -->
            <div class="user-menu">
                <button class="user-btn" aria-haspopup="true" aria-expanded="false">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?>
                    </div>
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </button>
                <div class="user-dropdown">
                    <div class="user-header">
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
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
                    <a href="<?php echo $baseUrl; ?>/admin/research" class="user-item">
                        <i class="fas fa-flask"></i>
                        <span>Research</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/admin/news" class="user-item">
                        <i class="fas fa-newspaper"></i>
                        <span>News</span>
                    </a>
                    <?php endif; ?>
                    <?php if ($userRole === 'admin'): ?>
                    <a href="<?php echo $baseUrl; ?>/admin/users" class="user-item">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/admin/settings" class="user-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo $baseUrl; ?>/admin/logout" class="user-item" style="color: var(--color-error);">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            <?php else: ?>
            <!-- Portal Button (Not Logged In) -->
            <a href="<?php echo $baseUrl; ?>/admin"
               class="portal-btn"
               role="menuitem">
               <i class="fas fa-sign-in-alt"></i>
               <span>Portal</span>
            </a>
            <?php endif; ?>
            
            <!-- Mobile Tabs Toggle -->
            <button class="mobile-tabs-toggle" 
                    aria-label="Toggle tabs menu" 
                    aria-expanded="false" 
                    onclick="toggleMobileTabs(this)">
                <span class="mobile-tabs-toggle-icon"></span>
            </button>
        </div>
    </div>
</nav>

<!-- Desktop Tabs Navigation -->
<div class="nav-tabs-container">
    <div class="nav-tabs-wrapper">
        <ul class="nav-tabs" role="tablist">
            <!-- Home Tab -->
            <li class="tab-item" role="none">
                <a href="<?php echo $baseUrl; ?>/"
                   class="tab-link <?php echo ($currentPage == 'home' || $currentPage == '' || $path == '/') ? 'active' : ''; ?>"
                   role="tab"
                   aria-selected="<?php echo ($currentPage == 'home' || $currentPage == '' || $path == '/') ? 'true' : 'false'; ?>">
                   <i class="fas fa-home tab-icon"></i>
                   <span>Home</span>
                </a>
            </li>
            
            <!-- About Tab -->
            <li class="tab-item" role="none">
                <a href="<?php echo $baseUrl; ?>/about"
                   class="tab-link <?php echo $currentPage == 'about' ? 'active' : ''; ?>"
                   role="tab"
                   aria-selected="<?php echo $currentPage == 'about' ? 'true' : 'false'; ?>">
                   <i class="fas fa-university tab-icon"></i>
                   <span>About</span>
                </a>
            </li>
            
            <!-- Programs Tab -->
            <li class="tab-item" role="none">
                <a href="<?php echo $baseUrl; ?>/programs"
                   class="tab-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>"
                   role="tab"
                   aria-selected="<?php echo $currentPage == 'programs' ? 'true' : 'false'; ?>">
                   <i class="fas fa-graduation-cap tab-icon"></i>
                   <span>Programs</span>
                </a>
            </li>
            
            <!-- Admissions Tab -->
            <li class="tab-item" role="none">
                <a href="<?php echo $baseUrl; ?>/admissions"
                   class="tab-link <?php echo $currentPage == 'admissions' ? 'active' : ''; ?>"
                   role="tab"
                   aria-selected="<?php echo $currentPage == 'admissions' ? 'true' : 'false'; ?>">
                   <i class="fas fa-file-alt tab-icon"></i>
                   <span>Admissions</span>
                </a>
            </li>
            
            <!-- Research Tab -->
            <li class="tab-item" role="none">
                <a href="<?php echo $baseUrl; ?>/research"
                   class="tab-link <?php echo $currentPage == 'research' ? 'active' : ''; ?>"
                   role="tab"
                   aria-selected="<?php echo $currentPage == 'research' ? 'true' : 'false'; ?>">
                   <i class="fas fa-flask tab-icon"></i>
                   <span>Research</span>
                </a>
            </li>
            
            <!-- Campus Life Tab -->
            <li class="tab-item" role="none">
                <a href="<?php echo $baseUrl; ?>/student-life"
                   class="tab-link <?php echo $currentPage == 'student-life' ? 'active' : ''; ?>"
                   role="tab"
                   aria-selected="<?php echo $currentPage == 'student-life' ? 'true' : 'false'; ?>">
                   <i class="fas fa-school tab-icon"></i>
                   <span>Campus Life</span>
                </a>
            </li>
            
            <!-- News Tab -->
            <li class="tab-item" role="none">
                <a href="<?php echo $baseUrl; ?>/news"
                   class="tab-link <?php echo $currentPage == 'news' ? 'active' : ''; ?>"
                   role="tab"
                   aria-selected="<?php echo $currentPage == 'news' ? 'true' : 'false'; ?>">
                   <i class="fas fa-newspaper tab-icon"></i>
                   <span>News</span>
                </a>
            </li>
            
            <!-- Faculty Tab -->
            <li class="tab-item" role="none">
                <a href="<?php echo $baseUrl; ?>/faculty"
                   class="tab-link <?php echo $currentPage == 'faculty' ? 'active' : ''; ?>"
                   role="tab"
                   aria-selected="<?php echo $currentPage == 'faculty' ? 'true' : 'false'; ?>">
                   <i class="fas fa-chalkboard-teacher tab-icon"></i>
                   <span>Faculty</span>
                </a>
            </li>
            
            <!-- Alumni Tab -->
            <li class="tab-item" role="none">
                <a href="<?php echo $baseUrl; ?>/alumni"
                   class="tab-link <?php echo $currentPage == 'alumni' ? 'active' : ''; ?>"
                   role="tab"
                   aria-selected="<?php echo $currentPage == 'alumni' ? 'true' : 'false'; ?>">
                   <i class="fas fa-user-graduate tab-icon"></i>
                   <span>Alumni</span>
                </a>
            </li>
            
            <!-- Contact Tab -->
            <li class="tab-item" role="none">
                <a href="<?php echo $baseUrl; ?>/contact"
                   class="tab-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>"
                   role="tab"
                   aria-selected="<?php echo $currentPage == 'contact' ? 'true' : 'false'; ?>">
                   <i class="fas fa-envelope tab-icon"></i>
                   <span>Contact</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Mobile Tabs Menu (Hidden by default) -->
<div class="mobile-tabs-menu" id="mobileTabsMenu">
    <div class="mobile-tabs-grid">
        <!-- Home -->
        <a href="<?php echo $baseUrl; ?>/"
           class="mobile-tab-item <?php echo ($currentPage == 'home' || $currentPage == '' || $path == '/') ? 'active' : ''; ?>">
           <i class="fas fa-home mobile-tab-icon"></i>
           <span class="mobile-tab-text">Home</span>
        </a>
        
        <!-- About -->
        <a href="<?php echo $baseUrl; ?>/about"
           class="mobile-tab-item <?php echo $currentPage == 'about' ? 'active' : ''; ?>">
           <i class="fas fa-university mobile-tab-icon"></i>
           <span class="mobile-tab-text">About</span>
        </a>
        
        <!-- Programs -->
        <a href="<?php echo $baseUrl; ?>/programs"
           class="mobile-tab-item <?php echo $currentPage == 'programs' ? 'active' : ''; ?>">
           <i class="fas fa-graduation-cap mobile-tab-icon"></i>
           <span class="mobile-tab-text">Programs</span>
        </a>
        
        <!-- Admissions -->
        <a href="<?php echo $baseUrl; ?>/admissions"
           class="mobile-tab-item <?php echo $currentPage == 'admissions' ? 'active' : ''; ?>">
           <i class="fas fa-file-alt mobile-tab-icon"></i>
           <span class="mobile-tab-text">Admissions</span>
        </a>
        
        <!-- Research -->
        <a href="<?php echo $baseUrl; ?>/research"
           class="mobile-tab-item <?php echo $currentPage == 'research' ? 'active' : ''; ?>">
           <i class="fas fa-flask mobile-tab-icon"></i>
           <span class="mobile-tab-text">Research</span>
        </a>
        
        <!-- Campus Life -->
        <a href="<?php echo $baseUrl; ?>/student-life"
           class="mobile-tab-item <?php echo $currentPage == 'student-life' ? 'active' : ''; ?>">
           <i class="fas fa-school mobile-tab-icon"></i>
           <span class="mobile-tab-text">Campus Life</span>
        </a>
        
        <!-- News -->
        <a href="<?php echo $baseUrl; ?>/news"
           class="mobile-tab-item <?php echo $currentPage == 'news' ? 'active' : ''; ?>">
           <i class="fas fa-newspaper mobile-tab-icon"></i>
           <span class="mobile-tab-text">News</span>
        </a>
        
        <!-- Faculty -->
        <a href="<?php echo $baseUrl; ?>/faculty"
           class="mobile-tab-item <?php echo $currentPage == 'faculty' ? 'active' : ''; ?>">
           <i class="fas fa-chalkboard-teacher mobile-tab-icon"></i>
           <span class="mobile-tab-text">Faculty</span>
        </a>
        
        <!-- Alumni -->
        <a href="<?php echo $baseUrl; ?>/alumni"
           class="mobile-tab-item <?php echo $currentPage == 'alumni' ? 'active' : ''; ?>">
           <i class="fas fa-user-graduate mobile-tab-icon"></i>
           <span class="mobile-tab-text">Alumni</span>
        </a>
        
        <!-- Contact -->
        <a href="<?php echo $baseUrl; ?>/contact"
           class="mobile-tab-item <?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
           <i class="fas fa-envelope mobile-tab-icon"></i>
           <span class="mobile-tab-text">Contact</span>
        </a>
        
        <!-- Library -->
        <a href="<?php echo $baseUrl; ?>/library"
           class="mobile-tab-item <?php echo $currentPage == 'library' ? 'active' : ''; ?>">
           <i class="fas fa-book mobile-tab-icon"></i>
           <span class="mobile-tab-text">Library</span>
        </a>
    </div>
</div>

<!-- Add padding to main content to account for fixed navbar and tabs -->
<div style="padding-top: calc(var(--navbar-height) + var(--tabs-height));">

<script>
// Mobile tabs toggle function
function toggleMobileTabs(button) {
    const menu = document.getElementById('mobileTabsMenu');
    const toggle = button;
    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
    
    menu.classList.toggle('active');
    toggle.classList.toggle('active');
    toggle.setAttribute('aria-expanded', !isExpanded);
    
    // Prevent body scroll when menu is open
    if (!isExpanded) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Enhanced search functionality
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Navbar scroll effect
    let lastScroll = 0;
    const navbar = document.querySelector('.navbar');
    const tabsContainer = document.querySelector('.nav-tabs-container');
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 20) {
            navbar.classList.add('scrolled');
            if (tabsContainer) {
                tabsContainer.style.boxShadow = '0 2px 15px rgba(107, 78, 155, 0.08)';
            }
        } else {
            navbar.classList.remove('scrolled');
            if (tabsContainer) {
                tabsContainer.style.boxShadow = '0 2px 15px rgba(107, 78, 155, 0.05)';
            }
        }
        
        lastScroll = currentScroll;
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const toggle = document.querySelector('.mobile-tabs-toggle');
        const menu = document.getElementById('mobileTabsMenu');
        
        if (!toggle.contains(event.target) && !menu.contains(event.target) && menu.classList.contains('active')) {
            menu.classList.remove('active');
            toggle.classList.remove('active');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }
    });
    
    // Close mobile menu when clicking a tab link
    const mobileTabLinks = document.querySelectorAll('.mobile-tab-item');
    mobileTabLinks.forEach(link => {
        link.addEventListener('click', function() {
            const menu = document.getElementById('mobileTabsMenu');
            const toggle = document.querySelector('.mobile-tabs-toggle');
            
            if (menu.classList.contains('active')) {
                menu.classList.remove('active');
                toggle.classList.remove('active');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
        });
    });
    
    // Active state management for tabs
    const currentPath = window.location.pathname;
    const tabLinks = document.querySelectorAll('.tab-link, .mobile-tab-item');
    tabLinks.forEach(link => {
        const linkPath = link.getAttribute('href');
        if (linkPath && currentPath.includes(linkPath.replace('<?php echo $baseUrl; ?>', ''))) {
            link.classList.add('active');
        }
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    const navbarHeight = document.querySelector('.navbar').offsetHeight;
                    const tabsHeight = document.querySelector('.nav-tabs-container') ? document.querySelector('.nav-tabs-container').offsetHeight : 0;
                    const targetPosition = target.offsetTop - navbarHeight - tabsHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile tabs menu if open
                    const menu = document.getElementById('mobileTabsMenu');
                    const toggle = document.querySelector('.mobile-tabs-toggle');
                    if (menu.classList.contains('active')) {
                        menu.classList.remove('active');
                        toggle.classList.remove('active');
                        toggle.setAttribute('aria-expanded', 'false');
                        document.body.style.overflow = '';
                    }
                }
            }
        });
    });
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Close mobile menu on resize to desktop
            if (window.innerWidth > 992) {
                const menu = document.getElementById('mobileTabsMenu');
                const toggle = document.querySelector('.mobile-tabs-toggle');
                
                if (menu.classList.contains('active')) {
                    menu.classList.remove('active');
                    toggle.classList.remove('active');
                    toggle.setAttribute('aria-expanded', 'false');
                    document.body.style.overflow = '';
                }
            }
        }, 250);
    });
    
    // Keyboard navigation support
    document.addEventListener('keydown', function(e) {
        // Close mobile menu on Escape key
        if (e.key === 'Escape') {
            const menu = document.getElementById('mobileTabsMenu');
            const toggle = document.querySelector('.mobile-tabs-toggle');
            
            if (menu.classList.contains('active')) {
                menu.classList.remove('active');
                toggle.classList.remove('active');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
        }
        
        // Focus trap for mobile menu
        if (e.key === 'Tab' && window.innerWidth <= 992) {
            const menu = document.getElementById('mobileTabsMenu');
            if (menu.classList.contains('active')) {
                const focusableElements = menu.querySelectorAll('a, button');
                const firstFocusable = focusableElements[0];
                const lastFocusable = focusableElements[focusableElements.length - 1];
                
                if (e.shiftKey) {
                    if (document.activeElement === firstFocusable) {
                        e.preventDefault();
                        lastFocusable.focus();
                    }
                } else {
                    if (document.activeElement === lastFocusable) {
                        e.preventDefault();
                        firstFocusable.focus();
                    }
                }
            }
        }
    });
});

// User dropdown functionality for mobile
document.addEventListener('DOMContentLoaded', function() {
    const userMenu = document.querySelector('.user-menu');
    const userBtn = document.querySelector('.user-btn');
    const userDropdown = document.querySelector('.user-dropdown');
    
    if (userMenu && window.innerWidth <= 768) {
        userBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenu.contains(event.target)) {
                userDropdown.style.display = 'none';
            }
        });
    }
});

// Initialize with scroll effect
window.dispatchEvent(new Event('scroll'));
</script>
</body>
</html>