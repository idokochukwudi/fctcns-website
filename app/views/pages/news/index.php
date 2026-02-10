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

html, body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
    overflow-x: hidden;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 16px;
    line-height: 1.6;
    color: #1a202c;
    background: #ffffff;
    overflow-x: hidden;
    text-rendering: optimizeLegibility;
    -webkit-font-smoothing: antialiased;
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
}

/* ==========================================
   HERO SECTION - NO GAP VERSION (Like Research Page)
   ========================================== */
.news-hero {
    position: relative;
    width: 100%;
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
    color: var(--white);
    margin: 0;
    border: none;
    overflow: hidden;
    min-height: 500px;
    display: flex;
    align-items: center;
}

.news-hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('<?php echo $heroImagePath; ?>');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.15;
    z-index: 1;
}

/* Transparent background wrapper for better readability */
.hero-container {
    position: relative;
    z-index: 3;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--space-4);
    width: 100%;
}

.news-hero-content {
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
    padding: var(--space-12) 0;
}

.hero-text-wrapper {
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    border-radius: 16px;
    padding: var(--space-8);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    margin: 0 auto;
}

.news-hero-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--accent), var(--accent-dark));
    color: var(--gray-900);
    padding: 0.6rem 1.75rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: var(--space-4);
    text-transform: uppercase;
    font-family: var(--font-heading);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    position: relative;
    z-index: 2;
}

.news-hero-title {
    font-family: var(--font-heading);
    font-size: clamp(2rem, 5vw, 2.75rem);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: var(--space-4);
    color: var(--white);
    position: relative;
    z-index: 2;
    letter-spacing: -0.25px;
}

.news-hero-subtitle {
    font-size: clamp(1rem, 2.5vw, 1.25rem);
    font-weight: 400;
    margin-bottom: var(--space-8);
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.95);
    font-family: var(--font-body);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    position: relative;
    z-index: 2;
    padding: 0 var(--space-4);
}

/* Search Bar - Optimized for mobile */
.news-search {
    max-width: 650px;
    margin: 0 auto;
}

.news-search-form {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
    width: 100%;
}

@media (min-width: 768px) {
    .news-search-form {
        flex-direction: row;
    }
}

.news-search-wrapper {
    position: relative;
    flex: 1;
    width: 100%;
}

.news-search-icon {
    position: absolute;
    left: var(--space-4);
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: 1rem;
    pointer-events: none;
}

.news-search-input {
    width: 100%;
    padding: var(--space-4) var(--space-4) var(--space-4) 3rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    font-family: var(--font-body);
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.95);
    color: var(--gray-900);
    transition: all 0.3s ease;
    outline: none;
    -webkit-appearance: none;
    appearance: none;
}

.news-search-input::placeholder {
    color: var(--gray-500);
}

.news-search-input:focus {
    background: var(--white);
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.2);
}

.news-search-button {
    padding: var(--space-4) var(--space-6);
    background: var(--accent);
    color: var(--gray-900);
    border: none;
    border-radius: 12px;
    font-family: var(--font-body);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    width: 100%;
}

@media (min-width: 768px) {
    .news-search-button {
        width: auto;
    }
}

.news-search-button:hover {
    background: var(--accent-dark);
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

@media (max-width: 768px) {
    .container {
        padding: 0 var(--space-4);
    }
}

/* ==========================================
   BREADCRUMBS
   ========================================== */
.breadcrumbs {
    padding: var(--space-4) 0;
    background: var(--gray-50);
}

.breadcrumb-list {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    flex-wrap: wrap;
    list-style: none;
    font-size: 0.875rem;
    padding: 0 var(--space-4);
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
    transition: color 0.3s ease;
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
    padding: var(--space-12) 0;
}

@media (max-width: 768px) {
    .news-content {
        padding: var(--space-8) 0;
    }
}

/* Section Header */
.section-header {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    margin-bottom: var(--space-8);
    padding-bottom: var(--space-4);
    border-bottom: 2px solid var(--gray-200);
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
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: var(--space-4);
}

.section-title-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.view-all-link {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--primary);
    font-weight: 600;
    font-size: 0.9375rem;
    text-decoration: none;
    transition: all 0.3s ease;
    padding: var(--space-3) var(--space-4);
    border-radius: 6px;
    background: var(--gray-50);
    white-space: nowrap;
}

.view-all-link:hover {
    background: var(--primary);
    color: var(--white);
}

/* ==========================================
   FILTER BAR - Mobile Optimized with Text Overflow Fixes
   ========================================== */
.filter-bar {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    margin-bottom: var(--space-8);
    padding: var(--space-4);
    background: var(--gray-50);
    border-radius: 12px;
    width: 100%;
    overflow: hidden;
}

@media (min-width: 768px) {
    .filter-bar {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-6);
    }
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
    width: 100%;
    min-width: 0;
}

@media (min-width: 480px) {
    .filter-group {
        flex-direction: row;
        align-items: center;
        gap: var(--space-3);
        width: auto;
        flex-wrap: wrap;
    }
}

.filter-label {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.9375rem;
    white-space: nowrap;
    flex-shrink: 0;
}

.filter-select {
    padding: var(--space-3) var(--space-8) var(--space-3) var(--space-4);
    border: 2px solid var(--gray-200);
    border-radius: 6px;
    font-family: var(--font-body);
    font-size: 0.9375rem;
    background: var(--white);
    color: var(--gray-700);
    cursor: pointer;
    transition: all 0.3s ease;
    appearance: none;
    width: 100%;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (min-width: 480px) {
    .filter-select {
        width: auto;
        min-width: 150px;
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
    font-size: 0.9375rem;
    text-align: center;
    white-space: nowrap;
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .results-count {
        text-align: left;
    }
}

.results-count strong {
    color: var(--gray-900);
    font-weight: 600;
}

/* ==========================================
   NEWS GRID - Responsive Layout with Text Overflow Fixes
   ========================================== */
.news-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--space-6);
    margin-bottom: var(--space-8);
    width: 100%;
}

@media (min-width: 640px) {
    .news-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .news-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.news-card {
    background: var(--white);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid var(--gray-100);
    width: 100%;
    min-width: 0;
}

.news-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-light);
}

.news-image-wrapper {
    position: relative;
    padding-top: 60%;
    overflow: hidden;
    background: var(--gray-100);
    width: 100%;
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
    transform: scale(1.05);
}

.news-category-badge {
    position: absolute;
    top: var(--space-3);
    left: var(--space-3);
    background: var(--primary);
    color: var(--white);
    padding: var(--space-2) var(--space-3);
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    z-index: 2;
    max-width: calc(100% - 24px);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.news-card-body {
    padding: var(--space-4);
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    width: 100%;
}

.news-title {
    font-family: var(--font-heading);
    font-size: 1.125rem;
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: var(--space-3);
    color: var(--gray-900);
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    min-height: 2.6em;
}

@media (min-width: 768px) {
    .news-title {
        font-size: 1.25rem;
        -webkit-line-clamp: 3;
        min-height: 3.9em;
    }
}

.news-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.news-title a:hover {
    color: var(--primary);
}

.news-excerpt {
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: var(--space-4);
    flex: 1;
    font-size: 0.9375rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    min-height: 4.8em;
}

@media (max-width: 480px) {
    .news-excerpt {
        font-size: 0.875rem;
        line-height: 1.5;
        -webkit-line-clamp: 4;
        min-height: 6em;
    }
}

.news-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: var(--space-4);
    border-top: 1px solid var(--gray-200);
    flex-wrap: wrap;
    gap: var(--space-3);
    width: 100%;
    min-width: 0;
}

.news-meta {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    flex-wrap: wrap;
    min-width: 0;
}

.news-meta-item {
    display: flex;
    align-items: center;
    gap: var(--space-1);
    color: var(--gray-500);
    font-size: 0.75rem;
    white-space: nowrap;
}

@media (min-width: 480px) {
    .news-meta-item {
        font-size: 0.875rem;
    }
}

.news-meta-item i {
    color: var(--primary);
    font-size: 0.75rem;
    flex-shrink: 0;
}

.read-more {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--primary);
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.3s ease;
    white-space: nowrap;
    flex-shrink: 0;
}

.read-more:hover {
    color: var(--primary-dark);
    gap: var(--space-3);
}

/* ==========================================
   SIDEBAR - Responsive Layout
   ========================================== */
.news-layout {
    display: flex;
    flex-direction: column;
    gap: var(--space-8);
    width: 100%;
}

@media (min-width: 1024px) {
    .news-layout {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: var(--space-8);
    }
}

.sidebar {
    position: sticky;
    top: var(--space-4);
    height: fit-content;
    width: 100%;
}

@media (min-width: 1024px) {
    .sidebar {
        top: var(--space-6);
    }
}

.sidebar-widget {
    background: var(--white);
    border-radius: 12px;
    padding: var(--space-6);
    margin-bottom: var(--space-6);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--gray-100);
    width: 100%;
}

.widget-title {
    font-family: var(--font-heading);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--space-4);
    display: flex;
    align-items: center;
    gap: var(--space-3);
    overflow: hidden;
}

.widget-title-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Category List */
.category-list {
    list-style: none;
    width: 100%;
}

.category-item {
    margin-bottom: var(--space-2);
    width: 100%;
}

.category-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-2) var(--space-3);
    background: var(--gray-50);
    border-radius: 6px;
    color: var(--gray-700);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.9375rem;
    width: 100%;
    min-width: 0;
    overflow: hidden;
}

.category-link span:first-child {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
    min-width: 0;
}

.category-link:hover {
    background: var(--primary);
    color: var(--white);
    padding-left: var(--space-4);
}

.category-count {
    background: var(--white);
    color: var(--primary);
    padding: var(--space-1) var(--space-2);
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 24px;
    text-align: center;
    flex-shrink: 0;
    margin-left: var(--space-2);
}

.category-link:hover .category-count {
    background: var(--accent);
    color: var(--gray-900);
}

/* Popular Posts */
.popular-list {
    list-style: none;
    width: 100%;
}

.popular-item {
    display: flex;
    gap: var(--space-3);
    padding: var(--space-3) 0;
    border-bottom: 1px solid var(--gray-200);
    width: 100%;
    min-width: 0;
}

.popular-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.popular-image-wrapper {
    width: 80px;
    height: 60px;
    flex-shrink: 0;
    border-radius: 6px;
    overflow: hidden;
}

.popular-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
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
    margin-bottom: var(--space-1);
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    min-height: 2.8em;
}

@media (max-width: 480px) {
    .popular-title {
        font-size: 0.875rem;
        -webkit-line-clamp: 3;
        min-height: 4.2em;
    }
}

.popular-title a {
    color: var(--gray-800);
    text-decoration: none;
    transition: color 0.3s ease;
}

.popular-title a:hover {
    color: var(--primary);
}

.popular-date {
    color: var(--gray-500);
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: var(--space-1);
    white-space: nowrap;
}

/* Newsletter Widget */
.newsletter-form {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
    width: 100%;
}

.newsletter-description {
    color: var(--gray-600);
    font-size: 0.9375rem;
    line-height: 1.5;
    margin-bottom: var(--space-3);
    overflow: hidden;
}

.newsletter-input-wrapper {
    position: relative;
    width: 100%;
}

.newsletter-input {
    width: 100%;
    padding: var(--space-3) var(--space-3) var(--space-3) 2.5rem;
    border: 2px solid var(--gray-200);
    border-radius: 6px;
    font-family: var(--font-body);
    font-size: 0.9375rem;
    transition: all 0.3s ease;
    min-width: 0;
}

.newsletter-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(93, 74, 138, 0.1);
}

.newsletter-icon {
    position: absolute;
    left: var(--space-3);
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: 1rem;
}

.newsletter-button {
    padding: var(--space-3) var(--space-4);
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border: none;
    border-radius: 6px;
    font-family: var(--font-body);
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    width: 100%;
}

.newsletter-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(93, 74, 138, 0.2);
}

.newsletter-disclaimer {
    font-size: 0.75rem;
    color: var(--gray-500);
    line-height: 1.4;
    margin-top: var(--space-2);
    overflow: hidden;
}

/* ==========================================
   PAGINATION
   ========================================== */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: var(--space-2);
    margin-top: var(--space-8);
    flex-wrap: wrap;
    width: 100%;
}

.pagination-list {
    display: flex;
    gap: var(--space-1);
    list-style: none;
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
}

.pagination-link {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 var(--space-3);
    background: var(--white);
    border: 2px solid var(--gray-200);
    border-radius: 6px;
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.pagination-link:hover:not(.active):not(.disabled) {
    background: var(--primary);
    color: var(--white);
    border-color: var(--primary);
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
    padding: var(--space-12) var(--space-4);
    background: var(--gray-50);
    border-radius: 12px;
    width: 100%;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto var(--space-6);
    background: linear-gradient(135deg, var(--gray-200), var(--gray-300));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--gray-400);
}

.empty-title {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: var(--space-3);
}

.empty-description {
    font-size: 1rem;
    color: var(--gray-600);
    max-width: 500px;
    margin: 0 auto var(--space-6);
    line-height: 1.6;
}

/* ==========================================
   BUTTONS
   ========================================== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    padding: var(--space-3) var(--space-6);
    border-radius: 6px;
    font-family: var(--font-body);
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid;
    white-space: nowrap;
    flex-shrink: 0;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border-color: var(--primary);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(93, 74, 138, 0.2);
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
    top: var(--space-4);
    right: var(--space-4);
    left: var(--space-4);
    background: var(--white);
    padding: var(--space-4);
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-left: 4px solid var(--primary);
    z-index: 1000;
    animation: slideIn 0.3s ease;
    display: flex;
    align-items: flex-start;
    gap: var(--space-3);
    max-width: calc(100% - var(--space-8));
}

@media (min-width: 480px) {
    .notification {
        left: auto;
        max-width: 400px;
    }
}

.notification-icon {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
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
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* ==========================================
   ACCESSIBILITY
   ========================================== */
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

:focus-visible {
    outline: 3px solid var(--accent);
    outline-offset: 2px;
    border-radius: 6px;
}

/* Skip to content link */
.skip-to-content {
    position: absolute;
    top: -40px;
    left: 0;
    background: var(--primary);
    color: var(--white);
    padding: var(--space-3);
    text-decoration: none;
    z-index: 1001;
}

.skip-to-content:focus {
    top: 0;
}

/* ==========================================
   PERFORMANCE OPTIMIZATIONS
   ========================================== */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Optimize image rendering */
img {
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
    max-width: 100%;
    height: auto;
}

/* ==========================================
   RESPONSIVE DESIGN - MOBILE FIXES
   ========================================== */

/* Small mobile (480px and below) - TEXT OVERFLOW FIXES */
@media (max-width: 480px) {
    /* Reduce font sizes for better fit */
    .news-hero {
        min-height: 400px;
    }
    
    .hero-text-wrapper {
        padding: var(--space-4);
        margin: 0 var(--space-3);
    }
    
    .news-hero-badge {
        padding: 0.4rem 1.25rem;
        font-size: 0.75rem;
        margin-bottom: var(--space-3);
    }
    
    .news-hero-title {
        font-size: 1.5rem;
        line-height: 1.2;
    }
    
    .news-hero-subtitle {
        font-size: 0.9375rem;
        line-height: 1.4;
        padding: 0;
        margin-bottom: var(--space-6);
    }
    
    /* Filter bar fixes */
    .filter-bar {
        padding: var(--space-3);
        gap: var(--space-3);
    }
    
    .filter-group {
        gap: var(--space-2);
    }
    
    .filter-label {
        font-size: 0.875rem;
    }
    
    .filter-select {
        padding: var(--space-2) var(--space-6) var(--space-2) var(--space-3);
        font-size: 0.875rem;
    }
    
    .results-count {
        font-size: 0.875rem;
    }
    
    /* News card fixes */
    .news-card {
        width: 100%;
        max-width: 100%;
    }
    
    .news-card-body {
        padding: var(--space-3);
    }
    
    .news-title {
        font-size: 1rem;
        -webkit-line-clamp: 3;
        min-height: 3.9em;
        margin-bottom: var(--space-2);
    }
    
    .news-excerpt {
        font-size: 0.8125rem;
        line-height: 1.4;
        -webkit-line-clamp: 4;
        min-height: 5.6em;
        margin-bottom: var(--space-3);
    }
    
    .news-card-footer {
        padding-top: var(--space-3);
        gap: var(--space-2);
    }
    
    .news-meta {
        gap: var(--space-2);
    }
    
    .news-meta-item {
        font-size: 0.6875rem;
        gap: 0.125rem;
    }
    
    .news-meta-item i {
        font-size: 0.6875rem;
    }
    
    .read-more {
        font-size: 0.8125rem;
    }
    
    /* Category badge fixes */
    .news-category-badge {
        font-size: 0.625rem;
        padding: 0.125rem 0.5rem;
        max-width: calc(100% - 16px);
    }
    
    /* Sidebar fixes */
    .sidebar-widget {
        padding: var(--space-4);
    }
    
    .widget-title {
        font-size: 1.125rem;
        gap: var(--space-2);
    }
    
    .widget-title-icon {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
    
    .category-link {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
    
    .popular-title {
        font-size: 0.8125rem;
        -webkit-line-clamp: 3;
        min-height: 3.9em;
    }
    
    .popular-date {
        font-size: 0.6875rem;
    }
}

/* Very small mobile (360px and below) */
@media (max-width: 360px) {
    .news-hero-title {
        font-size: 1.375rem;
    }
    
    .news-hero-subtitle {
        font-size: 0.875rem;
    }
    
    .news-search-input {
        font-size: 0.9375rem;
        padding: var(--space-3) var(--space-3) var(--space-3) 2.5rem;
    }
    
    .news-search-button {
        padding: var(--space-3) var(--space-4);
        font-size: 0.9375rem;
    }
    
    .news-title {
        font-size: 0.9375rem;
        -webkit-line-clamp: 4;
        min-height: 5.2em;
    }
    
    .news-excerpt {
        font-size: 0.75rem;
        -webkit-line-clamp: 5;
        min-height: 7.5em;
    }
    
    /* Make filter bar single column */
    .filter-group {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-select {
        width: 100%;
    }
    
    /* Adjust grid gap */
    .news-grid {
        gap: var(--space-4);
    }
}

/* Tablet (768px and below) */
@media (max-width: 768px) {
    .container {
        padding: 0 var(--space-4);
    }
    
    .news-content {
        padding: var(--space-8) 0;
    }
    
    .section-header {
        margin-bottom: var(--space-6);
    }
    
    .section-title {
        font-size: 1.375rem;
    }
    
    .news-grid {
        gap: var(--space-4);
    }
    
    .notification {
        left: var(--space-3);
        right: var(--space-3);
    }
}

/* Desktop (1024px+) */
@media (min-width: 1024px) {
    .news-hero-content {
        text-align: left;
        max-width: 1200px;
        padding: var(--space-16) 0;
    }
    
    .hero-text-wrapper {
        text-align: left;
        max-width: 700px;
        margin: 0;
    }
    
    .news-hero-title {
        text-align: left;
        font-size: 2.5rem;
    }
    
    .news-hero-subtitle {
        text-align: left;
        margin-left: 0;
        margin-right: 0;
        font-size: 1.375rem;
        max-width: 650px;
        padding: 0;
    }
    
    .news-search {
        max-width: 650px;
        margin: 0;
    }
    
    .news-hero {
        min-height: 700px;
    }
}

/* Landscape Orientation */
@media (max-height: 600px) and (orientation: landscape) {
    .news-hero {
        min-height: 100vh;
    }
}

/* Print Styles */
@media print {
    .news-hero,
    .news-search,
    .sidebar,
    .carousel-controls,
    .pagination,
    .btn,
    .notification {
        display: none;
    }
    
    .news-card {
        box-shadow: none;
        border: 1px solid var(--gray-300);
        break-inside: avoid;
        width: 100% !important;
    }
    
    .news-content {
        padding: 0;
    }
    
    .news-title,
    .news-excerpt {
        overflow: visible !important;
        -webkit-line-clamp: unset !important;
        display: block !important;
        min-height: auto !important;
    }
}
    </style>
</head>
<body>

<a href="#main-content" class="skip-to-content">Skip to main content</a>

<main class="news-page" id="main-content">
    <!-- Full-Width Hero Section - No Gap Version -->
    <section class="news-hero" aria-label="News hero section">
        <div class="news-hero-bg"></div>
        
        <div class="hero-container">
            <div class="news-hero-content">
                <div class="hero-text-wrapper">
                    <span class="news-hero-badge">
                        <i class="fas fa-newspaper" aria-hidden="true"></i>
                        Latest Updates
                    </span>
                    
                    <h1 class="news-hero-title">News & Announcements</h1>
                    
                    <p class="news-hero-subtitle">
                        Stay informed with the latest developments, achievements, and important announcements from FCT College of Nursing Sciences.
                    </p>
                    
                    <div class="news-search">
                        <form class="news-search-form" action="<?php echo $baseUrl; ?>/news/search" method="GET" role="search">
                            <div class="news-search-wrapper">
                                <i class="fas fa-search news-search-icon" aria-hidden="true"></i>
                                <input type="search" 
                                       name="q" 
                                       class="news-search-input" 
                                       placeholder="Search for news, announcements, events..." 
                                       aria-label="Search news articles"
                                       value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                            </div>
                            <button type="submit" class="news-search-button">
                                <i class="fas fa-search" aria-hidden="true"></i>
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
                        <i class="fas fa-home" aria-hidden="true"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <span class="breadcrumb-separator" aria-hidden="true">/</span>
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
                <div class="empty-icon" aria-hidden="true">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h2 class="empty-title">No News Articles Yet</h2>
                <p class="empty-description">
                    We're currently preparing our latest news and updates. Please check back soon for the most recent developments from FCT College of Nursing Sciences.
                </p>
                <a href="<?php echo $baseUrl; ?>" class="btn btn-primary">
                    <i class="fas fa-home" aria-hidden="true"></i> Return to Homepage
                </a>
            </div>
            <?php else: ?>
            
            <!-- Featured News Carousel -->
            <?php if (!empty($featuredNews)): ?>
            <div class="featured-carousel" aria-label="Featured news carousel">
                <div class="carousel-container">
                    <div class="carousel-track">
                        <?php foreach ($featuredNews as $index => $featured): ?>
                        <div class="carousel-slide" role="group" aria-label="Slide <?php echo $index + 1; ?> of <?php echo count($featuredNews); ?>">
                            <article class="featured-card">
                                <?php if (!empty($featured['featured_image'])): ?>
                                <img src="<?php echo getImageUrl($featured['featured_image']); ?>" 
                                     alt="<?php echo htmlspecialchars($featured['title']); ?>" 
                                     class="featured-image"
                                     loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                                     width="1200"
                                     height="600"
                                     onerror="this.onerror=null;this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-news.jpg';">
                                <?php endif; ?>
                                
                                <div class="featured-overlay">
                                    <span class="featured-badge" aria-hidden="true">
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
                                            <i class="far fa-calendar" aria-hidden="true"></i>
                                            <span><?php echo date('F d, Y', strtotime($featured['created_at'])); ?></span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="far fa-eye" aria-hidden="true"></i>
                                            <span><?php echo number_format($featured['views_count'] ?? 0); ?> views</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="far fa-clock" aria-hidden="true"></i>
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
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <div class="carousel-indicators" role="tablist">
                        <?php foreach ($featuredNews as $index => $item): ?>
                        <button class="carousel-indicator <?php echo $index === 0 ? 'active' : ''; ?>" 
                                data-index="<?php echo $index; ?>"
                                role="tab"
                                aria-label="Go to slide <?php echo $index + 1; ?>"
                                aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                            <span class="sr-only">Slide <?php echo $index + 1; ?></span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-btn carousel-next" aria-label="Next slide">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
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
                            <div class="section-title-icon" aria-hidden="true">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span>Latest News</span>
                        </h2>
                        <a href="<?php echo $baseUrl; ?>/news/archive" class="view-all-link">
                            View Archive
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <div class="filter-group">
                            <label class="filter-label" for="category-filter">Filter by:</label>
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
                        <div class="empty-icon" aria-hidden="true">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="empty-title">No Articles Found</h3>
                        <p class="empty-description">
                            We couldn't find any articles matching your criteria. Try adjusting your filters.
                        </p>
                        <a href="<?php echo $baseUrl; ?>/news" class="btn btn-outline">
                            <i class="fas fa-sync" aria-hidden="true"></i> Reset Filters
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
                                     width="400"
                                     height="240"
                                     onerror="this.onerror=null;this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-news.jpg';">
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
                                            <i class="far fa-calendar" aria-hidden="true"></i>
                                            <span><?php echo date('M d, Y', strtotime($item['created_at'])); ?></span>
                                        </div>
                                        <div class="news-meta-item">
                                            <i class="far fa-eye" aria-hidden="true"></i>
                                            <span><?php echo number_format($item['views_count'] ?? 0); ?></span>
                                        </div>
                                    </div>
                                    
                                    <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>" class="read-more">
                                        Read More
                                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
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
                                <a href="?page=<?php echo $pagination['current'] - 1; ?>" class="pagination-link" aria-label="Go to previous page">
                                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            $start = max(1, $pagination['current'] - 2);
                            $end = min($pagination['total'], $pagination['current'] + 2);
                            
                            for ($i = $start; $i <= $end; $i++): ?>
                            <li>
                                <a href="?page=<?php echo $i; ?>" 
                                   class="pagination-link <?php echo $i == $pagination['current'] ? 'active' : ''; ?>"
                                   aria-label="Go to page <?php echo $i; ?>"
                                   <?php echo $i == $pagination['current'] ? 'aria-current="page"' : ''; ?>>
                                    <?php echo $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current'] < $pagination['total']): ?>
                            <li>
                                <a href="?page=<?php echo $pagination['current'] + 1; ?>" class="pagination-link" aria-label="Go to next page">
                                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
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
                            <div class="widget-title-icon" aria-hidden="true">
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
                            <div class="widget-title-icon" aria-hidden="true">
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
                                         width="80"
                                         height="60"
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
                                            <?php echo htmlspecialchars(substr($popular['title'], 0, 60)) . '...'; ?>
                                        </a>
                                    </h4>
                                    <div class="popular-date">
                                        <i class="far fa-calendar" aria-hidden="true"></i>
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
                            <div class="widget-title-icon" aria-hidden="true">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>Newsletter</span>
                        </h3>
                        <p class="newsletter-description">
                            Subscribe to receive the latest news and updates directly in your inbox.
                        </p>
                        <form class="newsletter-form" id="newsletter-form" aria-label="Newsletter subscription form">
                            <div class="newsletter-input-wrapper">
                                <i class="fas fa-envelope newsletter-icon" aria-hidden="true"></i>
                                <input type="email" 
                                       class="newsletter-input" 
                                       placeholder="Your email address" 
                                       required
                                       aria-label="Email address for newsletter">
                            </div>
                            <button type="submit" class="newsletter-button">
                                <i class="fas fa-paper-plane" aria-hidden="true"></i>
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
    let autoPlayInterval;
    
    function updateCarousel() {
        if (!track || !slides.length) return;
        
        track.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentSlide);
            indicator.setAttribute('aria-selected', index === currentSlide ? 'true' : 'false');
        });
        
        // Update ARIA live region
        updateSlideLiveRegion();
    }
    
    function updateSlideLiveRegion() {
        const liveRegion = document.getElementById('carousel-live-region');
        if (liveRegion) {
            liveRegion.textContent = `Slide ${currentSlide + 1} of ${totalSlides}`;
        }
    }
    
    function nextSlide() {
        if (totalSlides <= 1) return;
        currentSlide = (currentSlide + 1) % totalSlides;
        updateCarousel();
    }
    
    function prevSlide() {
        if (totalSlides <= 1) return;
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateCarousel();
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            clearInterval(autoPlayInterval);
            prevSlide();
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            clearInterval(autoPlayInterval);
            nextSlide();
        });
    }
    
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            clearInterval(autoPlayInterval);
            currentSlide = index;
            updateCarousel();
        });
    });
    
    // Auto-play carousel
    if (totalSlides > 1) {
        autoPlayInterval = setInterval(nextSlide, 5000);
        
        // Pause auto-play on hover
        const carouselContainer = document.querySelector('.carousel-container');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', () => {
                clearInterval(autoPlayInterval);
            });
            
            carouselContainer.addEventListener('mouseleave', () => {
                clearInterval(autoPlayInterval);
                autoPlayInterval = setInterval(nextSlide, 5000);
            });
        }
    }
    
    // Initialize carousel
    updateCarousel();
    
    // Newsletter form
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();
            
            if (!email || !isValidEmail(email)) {
                showNotification('Please enter a valid email address', 'error');
                return;
            }
            
            // Simulate API call
            showNotification('Thank you for subscribing!', 'success');
            this.reset();
        });
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
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
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', 'polite');
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            </div>
            <p>${message}</p>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
    
    // Lazy loading for images with Intersection Observer
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    // Load the image
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        delete img.dataset.src;
                    }
                    
                    // Stop observing
                    imageObserver.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.1
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const target = document.querySelector(targetId);
            if (target) {
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Focus the target for accessibility
                target.setAttribute('tabindex', '-1');
                target.focus();
            }
        });
    });
    
    // Performance optimization: Debounce resize events
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Update carousel on resize
            updateCarousel();
        }, 250);
    });
    
    // Keyboard navigation for carousel
    document.addEventListener('keydown', function(e) {
        const carousel = document.querySelector('.featured-carousel');
        if (!carousel) return;
        
        const isCarouselFocused = carousel.contains(document.activeElement);
        if (!isCarouselFocused) return;
        
        switch(e.key) {
            case 'ArrowLeft':
                e.preventDefault();
                clearInterval(autoPlayInterval);
                prevSlide();
                break;
            case 'ArrowRight':
                e.preventDefault();
                clearInterval(autoPlayInterval);
                nextSlide();
                break;
            case 'Home':
                e.preventDefault();
                clearInterval(autoPlayInterval);
                currentSlide = 0;
                updateCarousel();
                break;
            case 'End':
                e.preventDefault();
                clearInterval(autoPlayInterval);
                currentSlide = totalSlides - 1;
                updateCarousel();
                break;
        }
    });
    
    // Text truncation fix for very long titles on mobile
    function adjustTextTruncation() {
        const newsTitles = document.querySelectorAll('.news-title');
        const isMobile = window.innerWidth <= 480;
        
        newsTitles.forEach(title => {
            if (isMobile) {
                // Adjust line clamp based on content length
                const text = title.textContent || title.innerText;
                if (text.length > 100) {
                    title.style.webkitLineClamp = '4';
                    title.style.minHeight = '6.5em';
                } else if (text.length > 60) {
                    title.style.webkitLineClamp = '3';
                    title.style.minHeight = '4.9em';
                }
            } else {
                // Reset for desktop
                title.style.webkitLineClamp = '';
                title.style.minHeight = '';
            }
        });
    }
    
    // Run on load and resize
    adjustTextTruncation();
    window.addEventListener('resize', adjustTextTruncation);
});

// Add slideOut animation for notifications
const style = document.createElement('style');
style.textContent = `
@keyframes slideOut {
    to {
        transform: translateY(-20px);
        opacity: 0;
    }
}
`;
document.head.appendChild(style);

// Progressive enhancement: Add ARIA live region for carousel
const liveRegion = document.createElement('div');
liveRegion.id = 'carousel-live-region';
liveRegion.className = 'sr-only';
liveRegion.setAttribute('aria-live', 'polite');
liveRegion.setAttribute('aria-atomic', 'true');
document.body.appendChild(liveRegion);
</script>

</body>
</html>