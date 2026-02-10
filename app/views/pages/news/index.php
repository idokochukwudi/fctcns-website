<?php
/**
 * News Index Page - PROFESSIONAL REDESIGN
 * - Full-width hero section with modern gradient overlay
 * - Clean, magazine-style layout
 * - Featured news carousel
 * - Masonry-style news grid
 * - Fully responsive across all screen sizes
 * - Professional purple/beige color scheme
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
        
        // External URLs
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return htmlspecialchars($path);
        }
        
        // Protocol-relative URLs
        if (strpos($path, '//') === 0) {
            return htmlspecialchars($path);
        }
        
        // Path starts with /uploads/
        if (strpos($path, '/uploads/') === 0) {
            return $baseUrl . $path;
        }
        
        // Path starts with uploads/ (without leading slash)
        if (strpos($path, 'uploads/') === 0) {
            return $baseUrl . '/' . htmlspecialchars($path);
        }
        
        // For relative paths from uploads directory
        if (preg_match('/^(news_|featured_|thumb_)/i', $path)) {
            return $baseUrl . '/uploads/news/' . htmlspecialchars($path);
        }
        
        // Check if it's just a filename
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $path)) {
            return $baseUrl . '/uploads/news/' . htmlspecialchars($path);
        }
        
        // Last resort: just prepend base URL
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
/* ==========================================
   RESET & BASE STYLES
   ========================================== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 16px;
    line-height: 1.7;
    color: #1a202c;
    background: #ffffff;
    overflow-x: hidden;
}

main {
    margin: 0;
    padding: 0;
}

/* ==========================================
   CSS VARIABLES
   ========================================== */
:root {
    /* Colors */
    --primary: #5D4A8A;
    --primary-dark: #4A3A6F;
    --primary-light: #7B68A8;
    --accent: #D4A574;
    --accent-dark: #BF8F5E;
    --accent-light: #E6C9A5;
    
    /* Neutrals */
    --white: #FFFFFF;
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-400: #9CA3AF;
    --gray-500: #6B7280;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --gray-900: #111827;
    
    /* Typography */
    --font-heading: 'Playfair Display', Georgia, serif;
    --font-body: 'Inter', sans-serif;
    
    /* Spacing */
    --space-1: 0.25rem;
    --space-2: 0.5rem;
    --space-3: 0.75rem;
    --space-4: 1rem;
    --space-5: 1.25rem;
    --space-6: 1.5rem;
    --space-8: 2rem;
    --space-10: 2.5rem;
    --space-12: 3rem;
    --space-16: 4rem;
    --space-20: 5rem;
    
    /* Border Radius */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.5rem;
    --radius-full: 9999px;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    
    /* Transitions */
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ==========================================
   FULL-WIDTH HERO SECTION
   ========================================== */
.news-hero {
    position: relative;
    width: 100%;
    min-height: 85vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
    overflow: hidden;
}

.news-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(212, 165, 116, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(123, 104, 168, 0.15) 0%, transparent 50%),
        linear-gradient(135deg, rgba(93, 74, 138, 0.95) 0%, rgba(74, 58, 111, 0.9) 100%);
    z-index: 1;
}

.news-hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('<?php echo $heroImagePath; ?>');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.15;
    z-index: 0;
}

/* Decorative Elements */
.hero-decoration {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(40px);
    z-index: 1;
}

.hero-decoration-1 {
    width: 400px;
    height: 400px;
    top: -100px;
    right: -100px;
}

.hero-decoration-2 {
    width: 300px;
    height: 300px;
    bottom: -50px;
    left: -50px;
}

.hero-container {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--space-20) var(--space-6);
}

.news-hero-content {
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
    color: var(--white);
}

.news-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    color: var(--white);
    padding: var(--space-3) var(--space-6);
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    margin-bottom: var(--space-8);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: fadeInDown 0.6s ease-out;
}

.news-hero-badge i {
    color: var(--accent);
}

.news-hero-title {
    font-family: var(--font-heading);
    font-size: clamp(2.5rem, 7vw, 5rem);
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: var(--space-6);
    background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.8) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

.news-hero-subtitle {
    font-size: clamp(1.125rem, 2.5vw, 1.375rem);
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: var(--space-12);
    font-weight: 400;
    animation: fadeInUp 0.6s ease-out 0.4s both;
}

/* Search Bar */
.news-search {
    max-width: 650px;
    margin: 0 auto;
    animation: fadeInUp 0.6s ease-out 0.6s both;
}

.news-search-form {
    position: relative;
    display: flex;
    gap: var(--space-3);
}

.news-search-wrapper {
    position: relative;
    flex: 1;
}

.news-search-icon {
    position: absolute;
    left: var(--space-5);
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: 1.125rem;
    pointer-events: none;
}

.news-search-input {
    width: 100%;
    padding: var(--space-5) var(--space-6) var(--space-5) 3.5rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-xl);
    font-family: var(--font-body);
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.95);
    color: var(--gray-900);
    transition: var(--transition);
    outline: none;
}

.news-search-input::placeholder {
    color: var(--gray-400);
}

.news-search-input:hover {
    background: var(--white);
    border-color: rgba(255, 255, 255, 0.3);
}

.news-search-input:focus {
    background: var(--white);
    border-color: var(--accent);
    box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.2), var(--shadow-xl);
    transform: translateY(-2px);
}

.news-search-button {
    padding: var(--space-5) var(--space-8);
    background: var(--accent);
    color: var(--gray-900);
    border: none;
    border-radius: var(--radius-xl);
    font-family: var(--font-body);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.news-search-button:hover {
    background: var(--accent-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.news-search-button:active {
    transform: translateY(0);
}

/* Animations */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ==========================================
   CONTAINER
   ========================================== */
.container {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 var(--space-6);
}

/* ==========================================
   BREADCRUMBS
   ========================================== */
.breadcrumbs {
    padding: var(--space-6) 0;
    background: var(--gray-50);
}

.breadcrumb-list {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    flex-wrap: wrap;
    list-style: none;
    font-size: 0.875rem;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--gray-600);
}

.breadcrumb-item a {
    color: var(--primary);
    text-decoration: none;
    transition: var(--transition);
}

.breadcrumb-item a:hover {
    color: var(--primary-dark);
}

.breadcrumb-item:last-child {
    color: var(--gray-500);
}

.breadcrumb-separator {
    color: var(--gray-400);
}

/* ==========================================
   MAIN CONTENT
   ========================================== */
.news-content {
    padding: var(--space-20) 0;
}

/* Section Header */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-12);
    padding-bottom: var(--space-6);
    border-bottom: 2px solid var(--gray-200);
}

.section-title {
    font-family: var(--font-heading);
    font-size: clamp(1.875rem, 4vw, 2.5rem);
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: var(--space-4);
}

.section-title-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.view-all-link {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--primary);
    font-weight: 600;
    font-size: 0.9375rem;
    text-decoration: none;
    transition: var(--transition);
    padding: var(--space-3) var(--space-5);
    border-radius: var(--radius-md);
    background: var(--gray-50);
}

.view-all-link:hover {
    background: var(--primary);
    color: var(--white);
    transform: translateX(4px);
}

/* ==========================================
   FEATURED NEWS CAROUSEL
   ========================================== */
.featured-carousel {
    position: relative;
    margin-bottom: var(--space-20);
}

.carousel-container {
    position: relative;
    overflow: hidden;
    border-radius: var(--radius-2xl);
}

.carousel-track {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.carousel-slide {
    min-width: 100%;
    position: relative;
}

.featured-card {
    position: relative;
    height: 600px;
    border-radius: var(--radius-2xl);
    overflow: hidden;
    background: var(--gray-900);
}

.featured-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s ease;
}

.featured-card:hover .featured-image {
    transform: scale(1.05);
}

.featured-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(0, 0, 0, 0.9) 0%,
        rgba(0, 0, 0, 0.4) 50%,
        rgba(0, 0, 0, 0.2) 100%
    );
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: var(--space-12);
}

.featured-badge {
    position: absolute;
    top: var(--space-6);
    right: var(--space-6);
    background: var(--accent);
    color: var(--gray-900);
    padding: var(--space-3) var(--space-5);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    box-shadow: var(--shadow-lg);
}

.featured-category {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: var(--white);
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: var(--space-4);
    width: fit-content;
}

.featured-title {
    font-family: var(--font-heading);
    font-size: clamp(1.875rem, 4vw, 3rem);
    font-weight: 700;
    color: var(--white);
    line-height: 1.2;
    margin-bottom: var(--space-4);
}

.featured-title a {
    color: inherit;
    text-decoration: none;
    transition: var(--transition);
}

.featured-title a:hover {
    color: var(--accent-light);
}

.featured-excerpt {
    font-size: 1.125rem;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.6;
    margin-bottom: var(--space-6);
    max-width: 700px;
}

.featured-meta {
    display: flex;
    align-items: center;
    gap: var(--space-6);
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9375rem;
}

.meta-item i {
    color: var(--accent);
}

/* Carousel Controls */
.carousel-controls {
    position: absolute;
    bottom: var(--space-6);
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: var(--space-3);
    z-index: 10;
}

.carousel-btn {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}

.carousel-btn:hover {
    background: var(--accent);
    color: var(--gray-900);
    transform: scale(1.1);
}

.carousel-indicators {
    display: flex;
    gap: var(--space-2);
}

.carousel-indicator {
    width: 10px;
    height: 10px;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition);
}

.carousel-indicator.active {
    background: var(--accent);
    width: 30px;
    border-radius: var(--radius-full);
}

/* ==========================================
   FILTER BAR
   ========================================== */
.filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--space-6);
    margin-bottom: var(--space-12);
    padding: var(--space-6);
    background: var(--gray-50);
    border-radius: var(--radius-xl);
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    flex-wrap: wrap;
}

.filter-label {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.9375rem;
}

.filter-select {
    padding: var(--space-3) var(--space-10) var(--space-3) var(--space-4);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-size: 0.9375rem;
    background: var(--white);
    color: var(--gray-700);
    cursor: pointer;
    transition: var(--transition);
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%235D4A8A' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right var(--space-4) center;
    background-size: 1rem;
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
    font-size: 0.9375rem;
}

.results-count strong {
    color: var(--gray-900);
    font-weight: 600;
}

/* ==========================================
   NEWS GRID - MASONRY LAYOUT
   ========================================== */
.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: var(--space-8);
    margin-bottom: var(--space-12);
}

.news-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid var(--gray-100);
}

.news-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-light);
}

.news-image-wrapper {
    position: relative;
    padding-top: 60%;
    overflow: hidden;
    background: var(--gray-100);
}

.news-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.news-card:hover .news-image {
    transform: scale(1.1);
}

.news-category-badge {
    position: absolute;
    top: var(--space-4);
    left: var(--space-4);
    background: var(--primary);
    color: var(--white);
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    z-index: 2;
}

.news-card-body {
    padding: var(--space-6);
    flex: 1;
    display: flex;
    flex-direction: column;
}

.news-title {
    font-family: var(--font-heading);
    font-size: 1.375rem;
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: var(--space-4);
    color: var(--gray-900);
}

.news-title a {
    color: inherit;
    text-decoration: none;
    transition: var(--transition);
}

.news-title a:hover {
    color: var(--primary);
}

.news-excerpt {
    color: var(--gray-600);
    line-height: 1.7;
    margin-bottom: var(--space-6);
    flex: 1;
    font-size: 0.9375rem;
}

.news-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: var(--space-4);
    border-top: 1px solid var(--gray-200);
}

.news-meta {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    flex-wrap: wrap;
}

.news-meta-item {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--gray-500);
    font-size: 0.875rem;
}

.news-meta-item i {
    color: var(--primary);
}

.read-more {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--primary);
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: var(--transition);
}

.read-more:hover {
    color: var(--primary-dark);
    gap: var(--space-3);
}

/* ==========================================
   SIDEBAR
   ========================================== */
.news-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: var(--space-12);
}

.sidebar {
    position: sticky;
    top: var(--space-6);
    height: fit-content;
}

.sidebar-widget {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-8);
    margin-bottom: var(--space-6);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-100);
}

.widget-title {
    font-family: var(--font-heading);
    font-size: 1.375rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--space-6);
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.widget-title-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Category List */
.category-list {
    list-style: none;
}

.category-item {
    margin-bottom: var(--space-2);
}

.category-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-3) var(--space-4);
    background: var(--gray-50);
    border-radius: var(--radius-md);
    color: var(--gray-700);
    text-decoration: none;
    transition: var(--transition);
    font-size: 0.9375rem;
}

.category-link:hover {
    background: var(--primary);
    color: var(--white);
    transform: translateX(4px);
}

.category-count {
    background: var(--white);
    color: var(--primary);
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
}

.category-link:hover .category-count {
    background: var(--accent);
    color: var(--gray-900);
}

/* Popular Posts */
.popular-list {
    list-style: none;
}

.popular-item {
    display: flex;
    gap: var(--space-4);
    padding: var(--space-4) 0;
    border-bottom: 1px solid var(--gray-200);
}

.popular-item:last-child {
    border-bottom: none;
}

.popular-image-wrapper {
    width: 90px;
    height: 70px;
    flex-shrink: 0;
    border-radius: var(--radius-md);
    overflow: hidden;
}

.popular-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.popular-item:hover .popular-image {
    transform: scale(1.1);
}

.popular-content {
    flex: 1;
    min-width: 0;
}

.popular-title {
    font-size: 0.9375rem;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: var(--space-2);
}

.popular-title a {
    color: var(--gray-800);
    text-decoration: none;
    transition: var(--transition);
}

.popular-title a:hover {
    color: var(--primary);
}

.popular-date {
    color: var(--gray-500);
    font-size: 0.8125rem;
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

/* Newsletter Widget */
.newsletter-form {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}

.newsletter-description {
    color: var(--gray-600);
    font-size: 0.9375rem;
    line-height: 1.6;
}

.newsletter-input-wrapper {
    position: relative;
}

.newsletter-input {
    width: 100%;
    padding: var(--space-4) var(--space-4) var(--space-4) 3rem;
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-size: 0.9375rem;
    transition: var(--transition);
}

.newsletter-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(93, 74, 138, 0.1);
}

.newsletter-icon {
    position: absolute;
    left: var(--space-4);
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
}

.newsletter-button {
    padding: var(--space-4) var(--space-6);
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border: none;
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
}

.newsletter-button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.newsletter-disclaimer {
    font-size: 0.75rem;
    color: var(--gray-500);
    line-height: 1.5;
}

/* ==========================================
   PAGINATION
   ========================================== */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: var(--space-3);
    margin-top: var(--space-16);
    flex-wrap: wrap;
}

.pagination-list {
    display: flex;
    gap: var(--space-2);
    list-style: none;
}

.pagination-link {
    min-width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 var(--space-4);
    background: var(--white);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-md);
    color: var(--gray-700);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
}

.pagination-link:hover:not(.active):not(.disabled) {
    background: var(--primary);
    color: var(--white);
    border-color: var(--primary);
    transform: translateY(-2px);
}

.pagination-link.active {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border-color: var(--primary);
}

.pagination-link.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* ==========================================
   EMPTY STATES
   ========================================== */
.empty-state {
    text-align: center;
    padding: var(--space-20) var(--space-6);
    background: var(--gray-50);
    border-radius: var(--radius-2xl);
}

.empty-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto var(--space-8);
    background: linear-gradient(135deg, var(--gray-200), var(--gray-300));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    color: var(--gray-400);
}

.empty-title {
    font-family: var(--font-heading);
    font-size: 2rem;
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: var(--space-4);
}

.empty-description {
    font-size: 1.125rem;
    color: var(--gray-600);
    max-width: 600px;
    margin: 0 auto var(--space-8);
    line-height: 1.7;
}

/* ==========================================
   BUTTONS
   ========================================== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-3);
    padding: var(--space-4) var(--space-8);
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    transition: var(--transition);
    cursor: pointer;
    border: 2px solid;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border-color: var(--primary);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-outline {
    background: transparent;
    color: var(--primary);
    border-color: var(--primary);
}

.btn-outline:hover {
    background: var(--primary);
    color: var(--white);
}

/* ==========================================
   NOTIFICATIONS
   ========================================== */
.notification {
    position: fixed;
    top: var(--space-6);
    right: var(--space-6);
    background: var(--white);
    padding: var(--space-5) var(--space-6);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    border-left: 4px solid var(--primary);
    z-index: 1000;
    max-width: 400px;
    animation: slideIn 0.3s ease;
    display: flex;
    align-items: flex-start;
    gap: var(--space-4);
}

.notification-icon {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
}

.notification.success {
    border-left-color: #10b981;
}

.notification.success .notification-icon {
    color: #10b981;
}

.notification.error {
    border-left-color: #ef4444;
}

.notification.error .notification-icon {
    color: #ef4444;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* ==========================================
   RESPONSIVE DESIGN
   ========================================== */

/* Large Tablets (1200px) */
@media (max-width: 1200px) {
    .news-layout {
        grid-template-columns: 1fr;
    }
    
    .sidebar {
        position: static;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-6);
    }
    
    .sidebar-widget {
        margin-bottom: 0;
    }
}

/* Medium Tablets (992px) */
@media (max-width: 992px) {
    .news-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
    
    .featured-card {
        height: 500px;
    }
    
    .featured-overlay {
        padding: var(--space-8);
    }
}

/* Small Tablets (768px) */
@media (max-width: 768px) {
    :root {
        --space-20: 3rem;
        --space-16: 2.5rem;
        --space-12: 2rem;
    }
    
    .news-hero {
        min-height: 70vh;
    }
    
    .hero-container {
        padding: var(--space-16) var(--space-4);
    }
    
    .news-search-form {
        flex-direction: column;
    }
    
    .news-search-button {
        width: 100%;
        justify-content: center;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-4);
    }
    
    .filter-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        width: 100%;
        justify-content: space-between;
    }
    
    .news-grid {
        grid-template-columns: 1fr;
        gap: var(--space-6);
    }
    
    .featured-card {
        height: 400px;
    }
    
    .sidebar {
        grid-template-columns: 1fr;
    }
    
    .carousel-btn {
        width: 40px;
        height: 40px;
    }
}

/* Mobile (576px) */
@media (max-width: 576px) {
    .container {
        padding: 0 var(--space-4);
    }
    
    .news-hero {
        min-height: 60vh;
    }
    
    .hero-container {
        padding: var(--space-12) var(--space-4);
    }
    
    .news-hero-badge {
        font-size: 0.75rem;
        padding: var(--space-2) var(--space-4);
    }
    
    .news-search-input {
        padding: var(--space-4) var(--space-4) var(--space-4) 2.5rem;
        font-size: 0.9375rem;
    }
    
    .news-search-icon {
        font-size: 1rem;
    }
    
    .featured-card {
        height: 350px;
    }
    
    .featured-overlay {
        padding: var(--space-6);
    }
    
    .featured-badge {
        top: var(--space-4);
        right: var(--space-4);
        padding: var(--space-2) var(--space-3);
        font-size: 0.625rem;
    }
    
    .carousel-controls {
        bottom: var(--space-4);
        gap: var(--space-2);
    }
    
    .carousel-btn {
        width: 36px;
        height: 36px;
        font-size: 0.875rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .section-title-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .news-card-body {
        padding: var(--space-5);
    }
    
    .news-title {
        font-size: 1.125rem;
    }
    
    .sidebar-widget {
        padding: var(--space-6);
    }
    
    .widget-title {
        font-size: 1.125rem;
    }
    
    .notification {
        left: var(--space-4);
        right: var(--space-4);
        max-width: none;
    }
}

/* Very Small Devices (360px) */
@media (max-width: 360px) {
    .news-hero-title {
        font-size: 2rem;
    }
    
    .featured-card {
        height: 300px;
    }
    
    .featured-title {
        font-size: 1.5rem;
    }
    
    .featured-excerpt {
        font-size: 0.9375rem;
    }
}

/* Landscape Orientation */
@media (max-height: 600px) and (orientation: landscape) {
    .news-hero {
        min-height: 100vh;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

:focus-visible {
    outline: 3px solid var(--accent);
    outline-offset: 3px;
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

/* Print Styles */
@media print {
    .news-hero,
    .news-search,
    .sidebar,
    .carousel-controls,
    .pagination,
    .btn {
        display: none;
    }
    
    .news-card {
        box-shadow: none;
        border: 1px solid var(--gray-300);
        break-inside: avoid;
    }
}
    </style>
</head>
<body>

<main class="news-page">
    <!-- Full-Width Hero Section -->
    <section class="news-hero">
        <div class="news-hero-bg"></div>
        <div class="hero-decoration hero-decoration-1"></div>
        <div class="hero-decoration hero-decoration-2"></div>
        
        <div class="hero-container">
            <div class="news-hero-content">
                <div class="news-hero-badge">
                    <i class="fas fa-newspaper"></i>
                    <span>Latest Updates</span>
                </div>
                
                <h1 class="news-hero-title">News & Announcements</h1>
                
                <p class="news-hero-subtitle">
                    Stay informed with the latest developments, achievements, and important announcements from FCT College of Nursing Sciences.
                </p>
                
                <div class="news-search">
                    <form class="news-search-form" action="<?php echo $baseUrl; ?>/news/search" method="GET">
                        <div class="news-search-wrapper">
                            <i class="fas fa-search news-search-icon"></i>
                            <input type="search" 
                                   name="q" 
                                   class="news-search-input" 
                                   placeholder="Search for news, announcements, events..." 
                                   aria-label="Search news"
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
    </section>

    <!-- Breadcrumbs -->
    <section class="breadcrumbs">
        <div class="container">
            <ul class="breadcrumb-list">
                <li class="breadcrumb-item">
                    <a href="<?php echo $baseUrl; ?>">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <span class="breadcrumb-separator">/</span>
                </li>
                <li class="breadcrumb-item">News</li>
            </ul>
        </div>
    </section>

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
                    We're currently preparing our latest news and updates. Please check back soon for the most recent developments from FCT College of Nursing Sciences.
                </p>
                <a href="<?php echo $baseUrl; ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Return to Homepage
                </a>
            </div>
            <?php else: ?>
            
            <!-- Featured News Carousel -->
            <?php if (!empty($featuredNews)): ?>
            <div class="featured-carousel">
                <div class="carousel-container">
                    <div class="carousel-track">
                        <?php foreach ($featuredNews as $index => $featured): ?>
                        <div class="carousel-slide">
                            <article class="featured-card">
                                <?php if (!empty($featured['featured_image'])): ?>
                                <img src="<?php echo getImageUrl($featured['featured_image']); ?>" 
                                     alt="<?php echo htmlspecialchars($featured['title']); ?>" 
                                     class="featured-image"
                                     onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-news.jpg';">
                                <?php endif; ?>
                                
                                <div class="featured-overlay">
                                    <span class="featured-badge">
                                        <i class="fas fa-star"></i> Featured
                                    </span>
                                    
                                    <?php if (!empty($featured['category'])): ?>
                                    <span class="featured-category">
                                        <?php echo htmlspecialchars($featured['category']); ?>
                                    </span>
                                    <?php endif; ?>
                                    
                                    <h2 class="featured-title">
                                        <a href="<?php echo $baseUrl; ?>/news/<?php echo $featured['slug']; ?>">
                                            <?php echo htmlspecialchars($featured['title']); ?>
                                        </a>
                                    </h2>
                                    
                                    <?php if (!empty($featured['excerpt'])): ?>
                                    <p class="featured-excerpt">
                                        <?php echo htmlspecialchars(substr(strip_tags($featured['excerpt']), 0, 200)) . '...'; ?>
                                    </p>
                                    <?php endif; ?>
                                    
                                    <div class="featured-meta">
                                        <div class="meta-item">
                                            <i class="far fa-calendar"></i>
                                            <span><?php echo date('F d, Y', strtotime($featured['created_at'])); ?></span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="far fa-eye"></i>
                                            <span><?php echo number_format($featured['views_count'] ?? 0); ?> views</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="far fa-clock"></i>
                                            <span>5 min read</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php if (count($featuredNews) > 1): ?>
                <div class="carousel-controls">
                    <button class="carousel-btn carousel-prev" aria-label="Previous slide">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="carousel-indicators">
                        <?php foreach ($featuredNews as $index => $item): ?>
                        <div class="carousel-indicator <?php echo $index === 0 ? 'active' : ''; ?>" 
                             data-index="<?php echo $index; ?>"></div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-btn carousel-next" aria-label="Next slide">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <div class="news-layout">
                <!-- Main Content -->
                <div>
                    <!-- Section Header -->
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
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <div class="filter-group">
                            <label class="filter-label">Filter by:</label>
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
                            Showing <strong><?php echo count($news); ?></strong> of <strong><?php echo $pagination['totalCount']; ?></strong> articles
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
                            We couldn't find any articles matching your criteria. Try adjusting your filters.
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
                                     onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-news.jpg';">
                                <?php else: ?>
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--gray-200),var(--gray-300));display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-newspaper" style="font-size:3rem;color:var(--gray-400);"></i>
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
                                    <?php echo htmlspecialchars(substr(strip_tags($item['excerpt']), 0, 150)) . '...'; ?>
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
                                <a href="?page=<?php echo $pagination['current'] - 1; ?>" class="pagination-link">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            $start = max(1, $pagination['current'] - 2);
                            $end = min($pagination['total'], $pagination['current'] + 2);
                            
                            for ($i = $start; $i <= $end; $i++): ?>
                            <li>
                                <a href="?page=<?php echo $i; ?>" 
                                   class="pagination-link <?php echo $i == $pagination['current'] ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current'] < $pagination['total']): ?>
                            <li>
                                <a href="?page=<?php echo $pagination['current'] + 1; ?>" class="pagination-link">
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
                <aside class="sidebar">
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
                                         onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-small.jpg';">
                                    <?php else: ?>
                                    <div style="width:100%;height:100%;background:var(--gray-200);display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-image" style="color:var(--gray-400);"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="popular-content">
                                    <h4 class="popular-title">
                                        <a href="<?php echo $baseUrl; ?>/news/<?php echo $popular['slug']; ?>">
                                            <?php echo htmlspecialchars(substr($popular['title'], 0, 60)) . '...'; ?>
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
                    <div class="sidebar-widget">
                        <h3 class="widget-title">
                            <div class="widget-title-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>Newsletter</span>
                        </h3>
                        <p class="newsletter-description">
                            Subscribe to receive the latest news and updates directly in your inbox.
                        </p>
                        <form class="newsletter-form" id="newsletter-form">
                            <div class="newsletter-input-wrapper">
                                <i class="fas fa-envelope newsletter-icon"></i>
                                <input type="email" 
                                       class="newsletter-input" 
                                       placeholder="Your email address" 
                                       required>
                            </div>
                            <button type="submit" class="newsletter-button">
                                <i class="fas fa-paper-plane"></i>
                                Subscribe Now
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
    // Carousel functionality
    const track = document.querySelector('.carousel-track');
    const slides = document.querySelectorAll('.carousel-slide');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    const indicators = document.querySelectorAll('.carousel-indicator');
    
    let currentSlide = 0;
    const totalSlides = slides.length;
    
    function updateCarousel() {
        if (!track) return;
        
        track.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentSlide);
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateCarousel();
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        });
    }
    
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentSlide = index;
            updateCarousel();
        });
    });
    
    // Auto-play carousel
    if (totalSlides > 1) {
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }, 5000);
    }
    
    // Newsletter form
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            showNotification('Thank you for subscribing!', 'success');
            this.reset();
        });
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
    
    // Notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            </div>
            <p>${message}</p>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
    
    // Lazy loading for images
    const images = document.querySelectorAll('img[loading="lazy"]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.src;
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});

@keyframes slideOut {
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</script>

</body>
</html>