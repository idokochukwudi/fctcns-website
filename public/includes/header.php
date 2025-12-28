<?php
// Base URL for all links
$baseUrl = '/fctcns-website/public';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

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
            'admin' => 'Administrator Portal'
        ];
        echo isset($titles[$currentPage]) ? $titles[$currentPage] : 'Empowering Future Healthcare Professionals';
    ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@300;400;600;700&family=Source+Sans+Pro:wght@300;400;600&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>/assets/images/logo/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>/assets/images/logo/apple-touch-icon.png">
    
    <!-- Page-specific CSS -->
    <style>
        /* Global color variables for consistency */
        :root {
            --color-primary: <?php echo $color_primary; ?>;
            --color-primary-dark: <?php echo $color_primary_dark; ?>;
            --color-primary-light: <?php echo $color_primary_light; ?>;
            --color-primary-alpha-05: rgba(107, 78, 155, 0.05);
            --color-primary-alpha-10: rgba(107, 78, 155, 0.1);
            --color-primary-alpha-20: rgba(107, 78, 155, 0.2);
            --color-secondary: <?php echo $color_secondary; ?>;
            --color-secondary-light: rgba(127, 178, 133, 0.1);
            --color-success: <?php echo $color_success; ?>;
            --color-white: #FFFFFF;
            --color-gray-50: #F8F9FA;
            --color-gray-100: #F1F3F4;
            --color-gray-200: #E1E8ED;
            --color-gray-300: <?php echo $color_gray_300; ?>;
            --color-gray-400: #A0AEC0;
            --color-gray-500: #718096;
            --color-gray-600: #5A6C7D;
            --color-gray-700: <?php echo $color_gray_700; ?>;
            --color-gray-800: <?php echo $color_gray_800; ?>;
            --color-error: #F56565;
            --color-warning: #ED8936;
            --color-info: #4299E1;
            
            --font-heading: 'Libre Franklin', 'Helvetica Neue', Arial, sans-serif;
            --font-body: 'Source Sans Pro', 'Segoe UI', Tahoma, sans-serif;
            --font-accent: 'Merriweather', Georgia, serif;
            
            --text-xs: 0.8rem;
            --text-sm: 0.875rem;
            --text-base: 1rem;
            --text-lg: 1.125rem;
            --text-xl: 1.25rem;
            --text-2xl: 1.5rem;
            --text-3xl: 1.875rem;
            --text-4xl: 2.25rem;
            
            --navbar-height: 72px;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.08);
            --transition-fast: 0.2s ease;
            --transition-normal: 0.3s ease;
        }
        
        /* Professional Navbar Styling with PNG-like transparency */
        .navbar {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 249, 255, 0.96) 100%);
            box-shadow: var(--shadow-md);
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(107, 78, 155, 0.08);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        
        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 0.75rem;
        }
        
        /* Logo styling - Replace with your actual logo */
        .navbar-logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        /* If you have an actual logo image, use this instead:
        .navbar-logo-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
        */
        
        .brand-content {
            display: flex;
            flex-direction: column;
        }
        
        .navbar-title {
            color: var(--color-primary);
            font-family: var(--font-heading);
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        
        .navbar-subtitle {
            color: var(--color-gray-600);
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0;
            letter-spacing: 0.2px;
        }
        
        /* Desktop Navigation */
        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            color: var(--color-gray-700);
            text-decoration: none;
            padding: 0.5rem 0.875rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all var(--transition-normal);
            display: block;
            position: relative;
        }
        
        .nav-link:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
            transition: all var(--transition-normal);
            transform: translateX(-50%);
        }
        
        .nav-link:hover {
            color: var(--color-primary);
            background-color: var(--color-primary-alpha-10);
        }
        
        .nav-link:hover:before {
            width: 70%;
        }
        
        .nav-link.active {
            color: var(--color-primary);
            background-color: var(--color-primary-alpha-05);
        }
        
        .nav-link.active:before {
            width: 70%;
        }
        
        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: rgba(255, 255, 255, 0.98);
            min-width: 220px;
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            padding: 0.5rem 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all var(--transition-normal);
            z-index: 100;
            border: 1px solid rgba(107, 78, 155, 0.1);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(5px);
        }
        
        .dropdown-item {
            display: block;
            padding: 0.6rem 1.25rem;
            color: var(--color-gray-700);
            text-decoration: none;
            transition: all var(--transition-fast);
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        .dropdown-item:hover {
            background-color: var(--color-primary-alpha-10);
            color: var(--color-primary);
        }
        
        /* Search Bar */
        .search-container {
            position: relative;
            margin: 0 0.75rem;
        }
        
        .search-input {
            padding: 0.5rem 0.75rem;
            padding-left: 2.5rem;
            border: 1px solid var(--color-gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            width: 200px;
            background: rgba(255, 255, 255, 0.9);
            transition: all var(--transition-fast);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(107, 78, 155, 0.1);
            width: 220px;
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-gray-500);
            font-size: 0.9rem;
        }
        
        /* Portal Button */
        .portal-btn {
            padding: 0.5rem 1.25rem;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
            color: var(--color-white);
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all var(--transition-normal);
            display: inline-block;
        }
        
        .portal-btn:hover {
            background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 78, 155, 0.25);
            color: var(--color-white);
        }
        
        /* Mobile Menu Toggle */
        .navbar-toggle {
            display: none;
            background: none;
            border: none;
            width: 36px;
            height: 36px;
            cursor: pointer;
            position: relative;
            border-radius: 4px;
        }
        
        .navbar-toggle:hover {
            background-color: var(--color-primary-alpha-10);
        }
        
        .navbar-toggle-icon {
            position: absolute;
            width: 20px;
            height: 2px;
            background: var(--color-primary);
            border-radius: 1px;
            transition: var(--transition-normal);
            left: 8px;
            top: 17px;
        }
        
        .navbar-toggle-icon:before,
        .navbar-toggle-icon:after {
            content: '';
            position: absolute;
            width: 20px;
            height: 2px;
            background: var(--color-primary);
            border-radius: 1px;
            transition: var(--transition-normal);
        }
        
        .navbar-toggle-icon:before {
            top: -6px;
        }
        
        .navbar-toggle-icon:after {
            top: 6px;
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .navbar-container {
                padding: 0 1rem;
            }
            
            .navbar-nav {
                gap: 0.2rem;
            }
            
            .nav-link {
                padding: 0.45rem 0.7rem;
                font-size: 0.85rem;
            }
            
            .search-input {
                width: 160px;
            }
            
            .search-input:focus {
                width: 180px;
            }
            
            .portal-btn {
                padding: 0.45rem 1rem;
                font-size: 0.85rem;
            }
            
            .navbar-logo {
                width: 45px;
                height: 45px;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-toggle {
                display: block;
            }
            
            .navbar-nav {
                position: fixed;
                top: var(--navbar-height);
                left: 0;
                right: 0;
                background: rgba(255, 255, 255, 0.98);
                flex-direction: column;
                padding: 1rem;
                box-shadow: var(--shadow-lg);
                transform: translateY(-100%);
                opacity: 0;
                visibility: hidden;
                transition: all var(--transition-normal);
                gap: 0;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }
            
            .navbar-nav.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }
            
            .nav-item {
                width: 100%;
            }
            
            .nav-link {
                padding: 0.75rem;
                border-radius: 4px;
                margin-bottom: 0.25rem;
            }
            
            .search-container {
                margin: 0.5rem 0;
                width: 100%;
            }
            
            .search-input {
                width: 100%;
                padding: 0.75rem;
                padding-left: 3rem;
            }
            
            .search-input:focus {
                width: 100%;
            }
            
            .dropdown-menu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                border: none;
                padding-left: 1rem;
                background: var(--color-gray-50);
                margin-top: 0.25rem;
                border-radius: 4px;
            }
            
            .portal-btn {
                margin-top: 0.5rem;
                width: 100%;
                text-align: center;
            }
            
            .navbar-brand {
                flex-direction: row;
                align-items: center;
            }
            
            .navbar-logo {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="navbar-container">
        <!-- Logo and Brand -->
        <a href="<?php echo $baseUrl; ?>/" class="navbar-brand">
            <!-- Logo Placeholder - Replace with your actual logo -->
            <div class="navbar-logo">
                <img src="<?php echo $baseUrl; ?>/assets/images/logo/logo.png" alt="FCT College of Nursing Sciences" class="navbar-logo-img">
            </div>
            <div class="brand-content">
                <h1 class="navbar-title">
                    FCT College of Nursing Sciences
                </h1>
                <p class="navbar-subtitle">NMCN & NBTE Accredited Institution</p>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <ul class="navbar-nav" role="menubar">
            <li class="nav-item" role="none">
                <a href="<?php echo $baseUrl; ?>/"
                   class="nav-link <?php echo ($currentPage == 'home' || $currentPage == 'index') ? 'active' : ''; ?>"
                   role="menuitem">
                   Home
                </a>
            </li>
            <li class="nav-item" role="none">
                <a href="<?php echo $baseUrl; ?>/about"
                   class="nav-link <?php echo $currentPage == 'about' ? 'active' : ''; ?>"
                   role="menuitem">
                   About
                </a>
            </li>
            <li class="nav-item dropdown" role="none">
                <a href="<?php echo $baseUrl; ?>/programs"
                   class="nav-link <?php echo $currentPage == 'programs' ? 'active' : ''; ?>"
                   role="menuitem"
                   aria-haspopup="true">
                   Programs
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li role="none"><a href="<?php echo $baseUrl; ?>/programs#basic-nursing" class="dropdown-item" role="menuitem">Basic Nursing</a></li>
                    <li role="none"><a href="<?php echo $baseUrl; ?>/programs#basic-midwifery" class="dropdown-item" role="menuitem">Basic Midwifery</a></li>
                    <li role="none"><a href="<?php echo $baseUrl; ?>/programs#post-basic" class="dropdown-item" role="menuitem">Post Basic Nursing</a></li>
                    <li role="none"><a href="<?php echo $baseUrl; ?>/programs#community-health" class="dropdown-item" role="menuitem">Community Health Nursing</a></li>
                    <li role="none"><a href="<?php echo $baseUrl; ?>/programs#national-diploma" class="dropdown-item" role="menuitem">National Diploma (ND)</a></li>
                    <li role="none"><a href="<?php echo $baseUrl; ?>/programs#higher-national-diploma" class="dropdown-item" role="menuitem">Higher National Diploma (HND)</a></li>
                </ul>
            </li>
            <li class="nav-item" role="none">
                <a href="<?php echo $baseUrl; ?>/admissions"
                   class="nav-link <?php echo $currentPage == 'admissions' ? 'active' : ''; ?>"
                   role="menuitem">
                   Admissions
                </a>
            </li>
            <li class="nav-item" role="none">
                <a href="<?php echo $baseUrl; ?>/research"
                   class="nav-link <?php echo $currentPage == 'research' ? 'active' : ''; ?>"
                   role="menuitem">
                   Research
                </a>
            </li>
            <li class="nav-item" role="none">
                <a href="<?php echo $baseUrl; ?>/contact"
                   class="nav-link <?php echo $currentPage == 'contact' ? 'active' : ''; ?>"
                   role="menuitem">
                   Contact
                </a>
            </li>
            
            <!-- Search Bar -->
            <li class="nav-item search-container" role="none">
                <span class="search-icon">🔍</span>
                <input type="text" 
                       class="search-input" 
                       placeholder="Search..."
                       aria-label="Search website">
            </li>
            
            <li class="nav-item" role="none">
                <a href="<?php echo $baseUrl; ?>/admin"
                   class="portal-btn"
                   role="menuitem">
                   Portal
                </a>
            </li>
        </ul>

        <!-- Mobile Menu Toggle -->
        <button class="navbar-toggle" aria-label="Toggle navigation" aria-expanded="false" onclick="toggleMobileMenu()">
            <span class="navbar-toggle-icon"></span>
        </button>
    </div>
</nav>

<!-- Add padding to main content to account for fixed navbar -->
<div style="padding-top: var(--navbar-height);">

<script>
function toggleMobileMenu() {
    const nav = document.querySelector('.navbar-nav');
    const toggle = document.querySelector('.navbar-toggle');
    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
    
    nav.classList.toggle('active');
    toggle.setAttribute('aria-expanded', !isExpanded);
}

// Search functionality
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
});
</script>