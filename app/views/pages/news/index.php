<?php
/**
 * News Index Page - FULLY RESPONSIVE PROFESSIONAL DESIGN
 * - FIXED: Cursor now displays properly in all input fields
 * - FIXED: Removed "Skip to main content" link from top-left corner visibility
 * - Perfect fit on all screen sizes (360px to 4K)
 * - Featured content with professional overlay on image - NO OVERFLOW
 * - WIDE horizontal cards that adapt beautifully
 * - Zero gap between sections
 * - Working AJAX newsletter subscription
 */

// Check if variables are set, if not set defaults
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '');
$news = $news ?? [];
$featuredNews = $featuredNews ?? [];
$categories = $categories ?? [];
$archiveMonths = $archiveMonths ?? [];
$popularNews = $popularNews ?? [];
$pagination = $pagination ?? ['current' => 1, 'total' => 0, 'limit' => 10, 'totalCount' => 0];
$pageTitle = $pageTitle ?? 'News - FCT College of Nursing Sciences';
$pageDescription = $pageDescription ?? 'Latest news and updates from FCT College of Nursing Sciences';
$currentPage = $currentPage ?? 'news';
$hasRealData = $hasRealData ?? false;
$error = $error ?? '';

// Define getImageUrl function
if (!function_exists('getImageUrl')) {
    function getImageUrl($path) {
        global $baseUrl;
        if (empty($path)) return '';
        $path = trim($path);
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) return htmlspecialchars($path);
        if (strpos($path, '//') === 0) return htmlspecialchars($path);
        if (strpos($path, '/uploads/') === 0) return $baseUrl . $path;
        if (strpos($path, 'uploads/') === 0) return $baseUrl . '/' . htmlspecialchars($path);
        if (preg_match('/^(news_|featured_|thumb_)/i', $path)) return $baseUrl . '/uploads/news/' . htmlspecialchars($path);
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $path)) return $baseUrl . '/uploads/news/' . htmlspecialchars($path);
        return $baseUrl . '/' . htmlspecialchars($path);
    }
}

$heroImagePath = $baseUrl . '/assets/images/news/news-hero.jpg';
$hasNews = !empty($news) || !empty($featuredNews);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta property="og:image" content="<?php echo $baseUrl; ?>/assets/images/og-news.jpg">
    <meta property="og:url" content="<?php echo $baseUrl; ?>/news">
    <meta property="og:type" content="website">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    /* EMERGENCY FULL WIDTH OVERRIDE */
body .main-content {
    padding: 0 !important;
    max-width: 100vw !important;
}

.hero-section {
    width: 100vw !important;
    position: relative !important;
    left: 50% !important;
    right: 50% !important;
    margin-left: -50vw !important;
    margin-right: -50vw !important;
}
    </style>
    <style>
        /* ==========================================
           RESET & BASE STYLES
           ========================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100%;
            overflow-x: hidden;
            background: #fcfcfc;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #1e293b;
            background: #ffffff;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        main {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        /* ==========================================
           CSS VARIABLES
           ========================================== */
        :root {
            --primary: #5D4A8A;
            --primary-dark: #4A3A6F;
            --primary-light: #7B68A8;
            --accent: #D4A574;
            --accent-dark: #BF8F5E;
            --white: #FFFFFF;
            --gray-50: #F8FAFC;
            --gray-100: #F1F5F9;
            --gray-200: #E2E8F0;
            --gray-300: #CBD5E1;
            --gray-400: #94A3B8;
            --gray-500: #64748B;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1E293B;
            --gray-900: #0F172A;
            --font-heading: 'Playfair Display', Georgia, serif;
            --font-body: 'Inter', sans-serif;
        }

        /* ==========================================
           CONTAINER
           ========================================== */
        .container {
            width: 100%;
            margin: 0 auto;
            padding-left: clamp(1rem, 4vw, 2rem);
            padding-right: clamp(1rem, 4vw, 2rem);
            max-width: 1440px;
        }

        /* ==========================================
           HERO SECTION
           ========================================== */
        .news-hero {
            position: relative;
            width: 100%;
            background: linear-gradient(145deg, var(--primary-dark), var(--primary));
            color: var(--white);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            min-height: clamp(350px, 45vh, 500px);
        }

        .news-hero-bg {
            position: absolute;
            inset: 0;
            background-image: url('<?php echo $heroImagePath; ?>');
            background-size: cover;
            background-position: center;
            opacity: 0.12;
            z-index: 1;
        }

        .hero-container {
            position: relative;
            z-index: 3;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: clamp(1.5rem, 4vw, 3rem) clamp(1rem, 4vw, 2rem);
        }

        .news-hero-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-text-wrapper {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            padding: clamp(1.5rem, 4vw, 2.5rem);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .news-hero-badge {
            display: inline-block;
            background: var(--accent);
            color: var(--gray-900);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .news-hero-title {
            font-family: var(--font-heading);
            font-size: clamp(1.8rem, 5vw, 3rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1rem;
            color: var(--white);
        }

        .news-hero-subtitle {
            font-size: clamp(0.95rem, 2vw, 1.2rem);
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.95);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ==========================================
           SEARCH FORM - FIXED CURSOR VISIBILITY
           ========================================== */
        .news-search {
            max-width: 600px;
            margin: 0 auto;
        }

        .news-search-form {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        @media (min-width: 640px) {
            .news-search-form {
                flex-direction: row;
            }
        }

        .news-search-wrapper {
            position: relative;
            flex: 1;
        }

        .news-search-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
            z-index: 4;
        }

        .news-search-input {
            width: 100%;
            height: 52px;
            padding: 0 1rem 0 3rem;
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 400;
            background: #ffffff;
            color: #0F172A;
            caret-color: #5D4A8A;
            outline: none;
            transition: all 0.2s ease;
            line-height: normal;
        }

        .news-search-input::placeholder {
            color: #64748B;
            opacity: 1;
            font-weight: 400;
        }

        .news-search-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.25);
            background: #ffffff;
            color: #0F172A;
            caret-color: var(--primary);
        }

        .news-search-button {
            height: 52px;
            padding: 0 2rem;
            background: var(--accent);
            color: var(--gray-900);
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .news-search-button:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
        }

        /* ==========================================
           BREADCRUMBS
           ========================================== */
        .breadcrumbs {
            padding: 0.85rem 0;
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
        }

        .breadcrumb-list {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            list-style: none;
            font-size: 0.85rem;
        }

        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        /* ==========================================
           FEATURED CONTENT - COMPLETELY FIXED
           ========================================== */
        .featured-section {
            margin: 2rem 0 2.5rem;
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .featured-article {
            position: relative;
            width: 100%;
            background: linear-gradient(145deg, var(--primary-dark), var(--primary));
            display: flex;
            align-items: center;
            min-height: 280px;
        }

        @media (min-width: 768px) {
            .featured-article {
                min-height: 320px;
            }
        }

        @media (min-width: 1024px) {
            .featured-article {
                min-height: 380px;
            }
        }

        .featured-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .featured-gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(105deg, 
                rgba(74, 58, 111, 0.97) 0%, 
                rgba(93, 74, 138, 0.9) 40%,
                rgba(212, 165, 116, 0.4) 100%);
            z-index: 2;
        }

        .featured-content {
            position: relative;
            z-index: 3;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 2rem 2.5rem;
        }

        @media (max-width: 768px) {
            .featured-content {
                padding: 1.5rem;
                align-items: flex-end;
                background: linear-gradient(0deg, 
                    rgba(0, 0, 0, 0.85) 0%, 
                    rgba(93, 74, 138, 0.7) 80%,
                    transparent 100%);
            }
        }

        .featured-content-wrapper {
            max-width: 65%;
            color: var(--white);
        }

        @media (max-width: 1024px) {
            .featured-content-wrapper {
                max-width: 75%;
            }
        }

        @media (max-width: 768px) {
            .featured-content-wrapper {
                max-width: 100%;
            }
        }

        .featured-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--accent);
            color: var(--gray-900);
            padding: 0.4rem 1.25rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .featured-title {
            font-family: var(--font-heading);
            font-size: clamp(1.2rem, 3vw, 2rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 0.75rem;
            color: var(--white);
        }

        .featured-excerpt {
            font-size: clamp(0.85rem, 1.8vw, 1rem);
            margin-bottom: 1.25rem;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.95);
            max-width: 90%;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .featured-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .featured-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--white);
            font-size: 0.8rem;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.35rem 1rem;
            border-radius: 50px;
            backdrop-filter: blur(4px);
        }

        .featured-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-featured {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--accent);
            color: var(--gray-900);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-featured-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: transparent;
            color: var(--white);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            border: 2px solid var(--white);
        }

        /* ==========================================
           SECTION HEADER
           ========================================== */
        .section-header {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid var(--gray-200);
        }

        @media (min-width: 768px) {
            .section-header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .section-title {
            font-family: var(--font-heading);
            font-size: clamp(1.4rem, 3.5vw, 1.8rem);
            font-weight: 700;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-title-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(145deg, var(--primary), var(--primary-light));
            color: var(--white);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .view-all-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            background: var(--gray-100);
            transition: all 0.2s ease;
        }

        .view-all-link:hover {
            background: var(--primary);
            color: var(--white);
        }

        /* ==========================================
           FILTER BAR - FIXED CURSOR VISIBILITY
           ========================================== */
        .filter-bar {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1rem 1.25rem;
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
        }

        @media (min-width: 768px) {
            .filter-bar {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            width: 100%;
        }

        @media (min-width: 640px) {
            .filter-group {
                flex-direction: row;
                align-items: center;
                flex-wrap: wrap;
            }
        }

        .filter-label {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.9rem;
        }

        .filter-select {
            padding: 0.6rem 2rem 0.6rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 400;
            background: #ffffff;
            color: #1E293B;
            cursor: pointer;
            appearance: none;
            width: 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 14px;
        }

        @media (min-width: 640px) {
            .filter-select {
                width: auto;
                min-width: 180px;
            }
        }

        .filter-select:hover {
            border-color: var(--primary);
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(93, 74, 138, 0.1);
        }

        .results-count {
            color: var(--gray-600);
            font-size: 0.9rem;
            white-space: nowrap;
        }

        /* ==========================================
           NEWS GRID - WIDE CARDS
           ========================================== */
        .news-grid {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .news-card {
            display: flex;
            flex-direction: column;
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            border: 1px solid var(--gray-200);
        }

        @media (min-width: 768px) {
            .news-card {
                flex-direction: row;
                min-height: 240px;
            }
        }

        .news-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(93, 74, 138, 0.08);
            border-color: var(--primary-light);
        }

        .news-image-wrapper {
            position: relative;
            width: 100%;
            height: 200px;
            flex-shrink: 0;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .news-image-wrapper {
                width: 30%;
                height: auto;
            }
        }

        .news-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .news-card:hover .news-image {
            transform: scale(1.05);
        }

        .news-category-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--primary);
            color: var(--white);
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            z-index: 2;
        }

        .news-card-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .news-title {
            font-family: var(--font-heading);
            font-size: clamp(1.1rem, 2.5vw, 1.4rem);
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 0.75rem;
            color: var(--gray-900);
        }

        .news-title a {
            color: inherit;
            text-decoration: none;
        }

        .news-title a:hover {
            color: var(--primary);
        }

        .news-excerpt {
            color: var(--gray-600);
            line-height: 1.5;
            margin-bottom: 1rem;
            flex: 1;
            font-size: 0.9rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-card-footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 2px solid var(--gray-200);
            gap: 1rem;
        }

        .news-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .news-meta-item {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            color: var(--gray-500);
            font-size: 0.8rem;
            font-weight: 500;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--white);
            background: var(--primary);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .read-more:hover {
            background: var(--primary-dark);
            gap: 0.75rem;
        }

        /* ==========================================
           NEWS LAYOUT - SIDEBAR
           ========================================== */
        .news-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-top: 0.5rem;
        }

        @media (min-width: 1024px) {
            .news-layout {
                grid-template-columns: 1fr 300px;
                gap: 2rem;
            }
        }

        .sidebar {
            width: 100%;
        }

        @media (min-width: 1024px) {
            .sidebar {
                position: sticky;
                top: 1.5rem;
                height: fit-content;
            }
        }

        .sidebar-widget {
            background: var(--white);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-200);
        }

        .widget-title {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .widget-title-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(145deg, var(--primary), var(--primary-light));
            color: var(--white);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-list {
            list-style: none;
        }

        .category-item {
            margin-bottom: 0.6rem;
        }

        .category-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 1rem;
            background: var(--gray-50);
            border-radius: 8px;
            color: var(--gray-700);
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .category-link:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateX(3px);
        }

        .category-count {
            background: var(--white);
            color: var(--primary);
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .popular-list {
            list-style: none;
        }

        .popular-item {
            display: flex;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .popular-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .popular-image-wrapper {
            width: 70px;
            height: 60px;
            flex-shrink: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .popular-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .popular-content {
            flex: 1;
        }

        .popular-title {
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 0.35rem;
        }

        .popular-title a {
            color: var(--gray-800);
            text-decoration: none;
        }

        .popular-title a:hover {
            color: var(--primary);
        }

        .popular-date {
            color: var(--gray-500);
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .sidebar-newsletter {
            background: linear-gradient(145deg, var(--primary-dark), var(--primary));
            border-radius: 16px;
            padding: 1.5rem;
            color: var(--white);
        }

        .sidebar-newsletter .widget-title {
            color: var(--white);
            margin-bottom: 0.75rem;
        }

        .sidebar-newsletter .widget-title-icon {
            background: var(--white);
            color: var(--primary);
        }

        .newsletter-description {
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
        }

        .newsletter-input-wrapper {
            position: relative;
            margin-bottom: 0.75rem;
        }

        .newsletter-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
            z-index: 4;
        }

        .newsletter-input {
            width: 100%;
            padding: 0.75rem 0.75rem 0.75rem 2.5rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 400;
            background: #ffffff;
            color: #0F172A;
            caret-color: #5D4A8A;
            height: 48px;
            outline: none;
            transition: all 0.2s ease;
            line-height: normal;
        }

        .newsletter-input::placeholder {
            color: #64748B;
            opacity: 1;
            font-weight: 400;
        }

        .newsletter-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.25);
            background: #ffffff;
            color: #0F172A;
            caret-color: var(--primary);
        }

        .newsletter-button {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent);
            color: var(--gray-900);
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            height: 48px;
            transition: all 0.2s ease;
        }

        .newsletter-button:hover:not(:disabled) {
            background: var(--accent-dark);
            transform: translateY(-2px);
        }

        .newsletter-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .newsletter-disclaimer {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 0.75rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination-list {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-link {
            min-width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 0.75rem;
            background: var(--white);
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            color: var(--gray-700);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .pagination-link:hover:not(.active) {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        .pagination-link.active {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            background: var(--gray-50);
            border-radius: 16px;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: var(--gray-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--gray-400);
        }

        .empty-title {
            font-family: var(--font-heading);
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: var(--white);
        }

        .notification {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            background: var(--white);
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            max-width: 350px;
            animation: slideIn 0.3s ease;
        }

        .notification.success {
            border-left-color: #10b981;
        }

        .notification.error {
            border-left-color: #ef4444;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* ==========================================
           FIXED: SKIP TO CONTENT LINK - MOVED TO RIGHT CORNER
           ========================================== */
        .skip-to-content {
            position: absolute;
            top: -40px;
            right: 1rem; /* Changed from left:0 to right:1rem */
            left: auto; /* Reset left */
            background: var(--primary);
            color: var(--white);
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            z-index: 1001;
            border-radius: 0 0 8px 8px; /* Rounded bottom corners */
        }

        .skip-to-content:focus {
            top: 0;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>

<main class="news-page" id="main-content">
    <!-- HERO SECTION -->
    <section class="news-hero" aria-label="News hero section">
        <div class="news-hero-bg"></div>
        
        <div class="hero-container">
            <div class="news-hero-content">
                <div class="hero-text-wrapper">
                    <span class="news-hero-badge">
                        <i class="fas fa-newspaper"></i>
                        Latest Updates
                    </span>
                    
                    <h1 class="news-hero-title">News & Announcements</h1>
                    
                    <p class="news-hero-subtitle">
                        Stay informed with the latest developments, achievements, and important announcements from FCT College of Nursing Sciences.
                    </p>
                    
                    <div class="news-search">
                        <form class="news-search-form" action="<?php echo $baseUrl; ?>/news/search" method="GET" role="search">
                            <div class="news-search-wrapper">
                                <i class="fas fa-search news-search-icon"></i>
                                <input type="search" 
                                       name="q" 
                                       class="news-search-input" 
                                       placeholder="Search news, announcements..." 
                                       aria-label="Search news articles"
                                       value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                            </div>
                            <button type="submit" class="news-search-button">
                                <i class="fas fa-search"></i>
                                <span>Search</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumbs -->
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <div class="container">
            <ul class="breadcrumb-list">
                <li class="breadcrumb-item">
                    <a href="<?php echo $baseUrl; ?>">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <span>/</span>
                </li>
                <li class="breadcrumb-item" aria-current="page">News</li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <section class="news-content">
        <div class="container">
            <?php if (!$hasNews): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h2 class="empty-title">No News Articles Yet</h2>
                <p class="empty-description">
                    We're currently preparing our latest news and updates. Please check back soon.
                </p>
                <a href="<?php echo $baseUrl; ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Return to Homepage
                </a>
            </div>
            <?php else: ?>
            
            <!-- FEATURED CONTENT -->
            <div class="featured-section">
                <div class="featured-article">
                    <img src="<?php echo $baseUrl; ?>/assets/images/news/featured-nursing.jpg" 
                         alt="Nursing students in practical training"
                         class="featured-image"
                         onerror="this.style.display='none'; this.parentElement.style.background='linear-gradient(145deg, #4A3A6F, #5D4A8A)';">
                    
                    <div class="featured-gradient-overlay"></div>
                    
                    <div class="featured-content">
                        <div class="featured-content-wrapper">
                            <span class="featured-badge">
                                <i class="fas fa-graduation-cap"></i>
                                Featured Academic News
                            </span>
                            
                            <h2 class="featured-title">
                                Strengthening Nursing Education Through Practical Training and Innovation
                            </h2>
                            
                            <p class="featured-excerpt">
                                The College of Nursing continues to enhance nursing education by integrating hands-on clinical training, modern learning tools, and evidence-based practices to prepare students for real-world healthcare challenges.
                            </p>
                            
                            <div class="featured-meta">
                                <span class="featured-meta-item">
                                    <i class="far fa-calendar-alt"></i>
                                    February 10, 2026
                                </span>
                                <span class="featured-meta-item">
                                    <i class="far fa-eye"></i>
                                    19 views
                                </span>
                                <span class="featured-meta-item">
                                    <i class="far fa-clock"></i>
                                    5 min read
                                </span>
                            </div>
                            
                            <div class="featured-actions">
                                <a href="<?php echo $baseUrl; ?>/news/strengthening-nursing-education" class="btn-featured">
                                    Read Full Article
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                <a href="<?php echo $baseUrl; ?>/news/category/academic" class="btn-featured-outline">
                                    <i class="fas fa-folder"></i>
                                    Academic News
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- News Layout with Sidebar -->
            <div class="news-layout">
                <!-- Main Content -->
                <div>
                    <div class="section-header">
                        <h2 class="section-title">
                            <div class="section-title-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>Latest News</span>
                        </h2>
                        <a href="<?php echo $baseUrl; ?>/news/archive" class="view-all-link">
                            View Archive
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="filter-bar">
                        <div class="filter-group">
                            <span class="filter-label">Filter:</span>
                            <select class="filter-select" id="category-filter" aria-label="Filter by category">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category => $count): ?>
                                <option value="<?php echo urlencode(strtolower(str_replace(' ', '-', $category))); ?>">
                                    <?php echo htmlspecialchars($category); ?> (<?php echo $count; ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <select class="filter-select" id="sort-filter" aria-label="Sort by">
                                <option value="latest">Latest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="popular">Most Popular</option>
                            </select>
                        </div>
                        
                        <div class="results-count">
                            <strong><?php echo count($news); ?></strong> of <strong><?php echo $pagination['totalCount']; ?></strong> articles
                        </div>
                    </div>
                    
                    <!-- News Grid -->
                    <?php if (empty($news)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="empty-title">No Articles Found</h3>
                        <p class="empty-description">
                            Try adjusting your filters or search criteria.
                        </p>
                        <a href="<?php echo $baseUrl; ?>/news" class="btn btn-outline">
                            <i class="fas fa-sync"></i> Reset Filters
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="news-grid">
                        <?php foreach ($news as $item): ?>
                        <article class="news-card">
                            <div class="news-image-wrapper">
                                <?php if (!empty($item['featured_image'])): ?>
                                <img src="<?php echo getImageUrl($item['featured_image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                     class="news-image"
                                     loading="lazy"
                                     onerror="this.onerror=null;this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-news.jpg';">
                                <?php else: ?>
                                <div style="width:100%;height:100%;background:linear-gradient(145deg, #E2E8F0, #CBD5E1);display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-newspaper" style="font-size:3rem;color:#94A3B8;"></i>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['category'])): ?>
                                <span class="news-category-badge">
                                    <?php echo htmlspecialchars($item['category']); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="news-card-body">
                                <h3 class="news-title">
                                    <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>">
                                        <?php echo htmlspecialchars($item['title']); ?>
                                    </a>
                                </h3>
                                
                                <?php if (!empty($item['excerpt'])): ?>
                                <p class="news-excerpt">
                                    <?php echo htmlspecialchars(substr(strip_tags($item['excerpt']), 0, 180)) . '...'; ?>
                                </p>
                                <?php endif; ?>
                                
                                <div class="news-card-footer">
                                    <div class="news-meta">
                                        <div class="news-meta-item">
                                            <i class="far fa-calendar"></i>
                                            <span><?php echo date('M d, Y', strtotime($item['created_at'])); ?></span>
                                        </div>
                                        <div class="news-meta-item">
                                            <i class="far fa-eye"></i>
                                            <span><?php echo number_format($item['views_count'] ?? 0); ?></span>
                                        </div>
                                    </div>
                                    
                                    <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>" class="read-more">
                                        Read More
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($pagination['total'] > 1): ?>
                    <nav class="pagination" aria-label="Page navigation">
                        <ul class="pagination-list">
                            <?php if ($pagination['current'] > 1): ?>
                            <li>
                                <a href="?page=<?php echo $pagination['current'] - 1; ?>" class="pagination-link" aria-label="Previous page">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                            <li>
                                <a href="?page=<?php echo $i; ?>" 
                                   class="pagination-link <?php echo $i == $pagination['current'] ? 'active' : ''; ?>"
                                   aria-label="Page <?php echo $i; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current'] < $pagination['total']): ?>
                            <li>
                                <a href="?page=<?php echo $pagination['current'] + 1; ?>" class="pagination-link" aria-label="Next page">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <aside class="sidebar" aria-label="News sidebar">
                    <!-- Categories Widget -->
                    <?php if (!empty($categories)): ?>
                    <div class="sidebar-widget">
                        <h3 class="widget-title">
                            <div class="widget-title-icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <span>Categories</span>
                        </h3>
                        <ul class="category-list">
                            <?php foreach ($categories as $category => $count): ?>
                            <li class="category-item">
                                <a href="<?php echo $baseUrl; ?>/news/category/<?php echo urlencode(strtolower(str_replace(' ', '-', $category))); ?>" 
                                   class="category-link">
                                    <span><?php echo htmlspecialchars($category); ?></span>
                                    <span class="category-count"><?php echo $count; ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Popular News Widget -->
                    <?php if (!empty($popularNews)): ?>
                    <div class="sidebar-widget">
                        <h3 class="widget-title">
                            <div class="widget-title-icon">
                                <i class="fas fa-fire"></i>
                            </div>
                            <span>Popular News</span>
                        </h3>
                        <ul class="popular-list">
                            <?php foreach ($popularNews as $popular): ?>
                            <li class="popular-item">
                                <div class="popular-image-wrapper">
                                    <?php if (!empty($popular['featured_image'])): ?>
                                    <img src="<?php echo getImageUrl($popular['featured_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($popular['title']); ?>" 
                                         class="popular-image"
                                         loading="lazy"
                                         onerror="this.onerror=null;this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-small.jpg';">
                                    <?php else: ?>
                                    <div style="width:100%;height:100%;background:var(--gray-200);display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-image" style="color:var(--gray-400);"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="popular-content">
                                    <h4 class="popular-title">
                                        <a href="<?php echo $baseUrl; ?>/news/<?php echo $popular['slug']; ?>">
                                            <?php echo htmlspecialchars($popular['title']); ?>
                                        </a>
                                    </h4>
                                    <div class="popular-date">
                                        <i class="far fa-calendar"></i>
                                        <?php echo date('M d, Y', strtotime($popular['created_at'])); ?>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Newsletter Widget -->
                    <div class="sidebar-newsletter">
                        <h3 class="widget-title">
                            <div class="widget-title-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>Stay Updated</span>
                        </h3>
                        <p class="newsletter-description">
                            Subscribe to receive the latest news and updates directly in your inbox.
                        </p>
                        
                        <div id="newsletter-message" style="display: none; margin-bottom: 1rem; padding: 0.75rem; border-radius: 0.5rem; font-size: 0.875rem;"></div>
                        
                        <form class="newsletter-form" id="newsletter-form" action="<?php echo BASE_URL; ?>/newsletter/subscribe" method="POST">
                            <div class="newsletter-input-wrapper">
                                <i class="fas fa-envelope newsletter-icon"></i>
                                <input type="email" 
                                       name="email"
                                       class="newsletter-input" 
                                       placeholder="Your email address" 
                                       required
                                       aria-label="Email for newsletter"
                                       id="newsletter-email">
                            </div>
                            
                            <button type="submit" class="newsletter-button" id="newsletter-submit">
                                <i class="fas fa-paper-plane"></i>
                                <span>Subscribe</span>
                            </button>
                            
                            <p class="newsletter-disclaimer">
                                We respect your privacy. Unsubscribe anytime.
                            </p>
                        </form>
                    </div>
                </aside>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Newsletter Form AJAX
    const newsletterForm = document.getElementById('newsletter-form');
    const emailInput = document.getElementById('newsletter-email');
    const submitBtn = document.getElementById('newsletter-submit');
    const messageDiv = document.getElementById('newsletter-message');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = emailInput.value.trim();
            if (!email || !isValidEmail(email)) {
                showMessage('Please enter a valid email address', 'error');
                return;
            }
            
            emailInput.disabled = true;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';
            
            try {
                const formData = new FormData();
                formData.append('email', email);
                formData.append('source', 'newsletter_sidebar');
                
                const response = await fetch('<?php echo BASE_URL; ?>/newsletter/subscribe', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage(data.message, 'success');
                    emailInput.value = '';
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('Connection error. Please try again.', 'error');
            } finally {
                emailInput.disabled = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> <span>Subscribe</span>';
            }
        });
    }
    
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    function showMessage(message, type) {
        messageDiv.style.display = 'block';
        messageDiv.textContent = message;
        messageDiv.style.background = type === 'success' ? '#d4edda' : '#f8d7da';
        messageDiv.style.color = type === 'success' ? '#155724' : '#721c24';
        messageDiv.style.border = type === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
        
        if (type === 'success') {
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }
    }
    
    // Filter functionality
    const categoryFilter = document.getElementById('category-filter');
    const sortFilter = document.getElementById('sort-filter');
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            if (this.value) {
                window.location.href = '<?php echo $baseUrl; ?>/news/category/' + this.value;
            }
        });
    }
    
    if (sortFilter) {
        sortFilter.addEventListener('change', function() {
            const params = new URLSearchParams(window.location.search);
            params.set('sort', this.value);
            params.set('page', '1');
            window.location.search = params.toString();
        });
    }
});
</script>

</body>
</html>