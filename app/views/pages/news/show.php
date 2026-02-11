<?php
/**
 * Single News Article View - PROFESSIONAL EDITORIAL REDESIGN
 * Magazine-style layout with exceptional typography and reading experience
 * Fully responsive and optimized for all screen sizes
 */

// Ensure required variables exist
$baseUrl = $baseUrl ?? BASE_URL ?? '';
$news = $news ?? [];
$relatedNews = $relatedNews ?? [];
$popularNews = $popularNews ?? []; // Ensure popularNews is available
$currentPage = $currentPage ?? 'news';
$pageTitle = $pageTitle ?? ($news['title'] ?? 'News Article') . ' - FCT College of Nursing Sciences';
$pageDescription = $pageDescription ?? ($news['excerpt'] ?? 'Read this news article from FCT College of Nursing Sciences');

// Format dates
$newsDate = !empty($news['created_at']) ? date('F j, Y', strtotime($news['created_at'])) : '';
$newsTime = !empty($news['created_at']) ? date('h:i A', strtotime($news['created_at'])) : '';

// Get author name
$authorName = $news['author_name'] ?? 'FCT Nursing College';

// Calculate reading time - FIXED: Ensure minimum 1 minute
$wordCount = !empty($news['content']) ? str_word_count(strip_tags($news['content'])) : 0;
// Set minimum reading time to 1 minute
if ($wordCount <= 50) {
    $readingTime = 1; // Very short articles
} else {
    $readingTime = max(1, ceil($wordCount / 200));
}

// Breadcrumb navigation
$breadcrumb = [
    ['label' => 'Home', 'url' => $baseUrl],
    ['label' => 'News', 'url' => $baseUrl . '/news'],
    ['label' => htmlspecialchars(substr($news['title'] ?? 'Article', 0, 40) . (strlen($news['title'] ?? '') > 40 ? '...' : '')), 'url' => '']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    
    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($news['title'] ?? $pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta property="og:image" content="<?php echo !empty($news['featured_image']) ? $baseUrl . $news['featured_image'] : $baseUrl . '/assets/images/news/default-og-image.jpg'; ?>">
    <meta property="og:url" content="<?php echo $baseUrl . '/news/' . ($news['slug'] ?? ''); ?>">
    <meta property="og:type" content="article">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($news['title'] ?? $pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="twitter:image" content="<?php echo !empty($news['featured_image']) ? $baseUrl . $news['featured_image'] : $baseUrl . '/assets/images/news/default-og-image.jpg'; ?>">
    
    <!-- Fonts - Distinctive Editorial Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Crimson+Pro:wght@300;400;500;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
   CSS VARIABLES - Editorial Magazine Theme
   ========================================== */
:root {
    /* Primary Colors */
    --primary: #5D4A8A;
    --primary-dark: #4A3A6F;
    --primary-light: #7B68A8;
    --accent: #D4A574;
    --accent-dark: #BF8F5E;
    --accent-light: #E6C9A5;
    
    /* Neutrals */
    --white: #FFFFFF;
    --cream: #FDFCFA;
    --beige: #F7F5F2;
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
    
    /* Typography - Editorial Fonts */
    --font-display: 'Playfair Display', Georgia, serif;
    --font-body: 'Crimson Pro', Georgia, serif;
    --font-ui: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    
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
    --radius-sm: 0.25rem;
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
    
    /* Transitions */
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ==========================================
   RESET & BASE
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

html {
    scroll-behavior: smooth;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

body {
    font-family: var(--font-body);
    font-size: 18px;
    line-height: 1.7;
    color: var(--gray-800);
    background: var(--cream);
    overflow-x: hidden;
}

/* ==========================================
   TYPOGRAPHY
   ========================================== */
h1, h2, h3, h4, h5, h6 {
    font-family: var(--font-display);
    font-weight: 600;
    line-height: 1.2;
    color: var(--gray-900);
    overflow-wrap: break-word;
    word-wrap: break-word;
    hyphens: auto;
}

a {
    color: var(--primary);
    text-decoration: none;
    transition: var(--transition);
}

a:hover {
    color: var(--primary-dark);
}

/* ==========================================
   UTILITIES
   ========================================== */
.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--space-6);
}

@media (max-width: 768px) {
    .container {
        padding: 0 var(--space-4);
    }
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

/* ==========================================
   BREADCRUMB
   ========================================== */
.breadcrumb {
    background: var(--white);
    border-bottom: 1px solid var(--gray-200);
    padding: var(--space-4) 0;
    position: sticky;
    top: 0;
    z-index: 100;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
}

.breadcrumb-list {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    list-style: none;
    flex-wrap: wrap;
    font-family: var(--font-ui);
    font-size: 0.875rem;
    padding: 0 var(--space-4);
    overflow: hidden;
}

@media (max-width: 768px) {
    .breadcrumb-list {
        padding: 0 var(--space-3);
        font-size: 0.8125rem;
    }
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--gray-600);
    min-width: 0;
    flex-shrink: 0;
}

.breadcrumb-item:not(:last-child)::after {
    content: "/";
    color: var(--gray-400);
    flex-shrink: 0;
}

.breadcrumb-link {
    color: var(--gray-600);
    transition: var(--transition);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 200px;
    display: inline-block;
}

@media (max-width: 480px) {
    .breadcrumb-link {
        max-width: 120px;
    }
}

.breadcrumb-link:hover {
    color: var(--primary);
}

.breadcrumb-current {
    color: var(--gray-900);
    font-weight: 500;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 300px;
    display: inline-block;
}

@media (max-width: 480px) {
    .breadcrumb-current {
        max-width: 150px;
        font-size: 0.8125rem;
    }
}

/* ==========================================
   ARTICLE LAYOUT
   ========================================== */
.article-page {
    min-height: 100vh;
    padding: var(--space-16) 0 var(--space-20);
    width: 100%;
    overflow-x: hidden;
}

@media (max-width: 768px) {
    .article-page {
        padding: var(--space-12) 0 var(--space-16);
    }
}

.article-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--space-6);
    width: 100%;
}

@media (max-width: 768px) {
    .article-container {
        padding: 0 var(--space-4);
    }
}

.article-grid {
    display: flex;
    flex-direction: column;
    gap: var(--space-16);
    width: 100%;
}

@media (min-width: 1024px) {
    .article-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: var(--space-16);
    }
}

/* ==========================================
   ARTICLE HEADER - Text Overflow Fixes
   ========================================== */
.article-header {
    max-width: 800px;
    margin: 0 auto var(--space-12);
    text-align: center;
    width: 100%;
    padding: 0 var(--space-4);
}

@media (max-width: 768px) {
    .article-header {
        margin-bottom: var(--space-8);
        padding: 0;
    }
}

.article-category {
    display: inline-block;
    font-family: var(--font-ui);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--primary);
    background: rgba(93, 74, 138, 0.08);
    padding: var(--space-2) var(--space-5);
    border-radius: var(--radius-full);
    margin-bottom: var(--space-6);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 100%;
}

@media (max-width: 480px) {
    .article-category {
        font-size: 0.6875rem;
        padding: var(--space-2) var(--space-4);
        margin-bottom: var(--space-4);
    }
}

.article-title {
    font-size: clamp(1.75rem, 5vw, 3rem);
    font-weight: 700;
    line-height: 1.1;
    color: var(--gray-900);
    margin-bottom: var(--space-6);
    letter-spacing: -0.02em;
    overflow-wrap: break-word;
    word-wrap: break-word;
    hyphens: auto;
    padding: 0 var(--space-2);
}

@media (max-width: 768px) {
    .article-title {
        font-size: clamp(1.5rem, 4.5vw, 2.25rem);
        line-height: 1.2;
        margin-bottom: var(--space-4);
    }
}

@media (max-width: 480px) {
    .article-title {
        font-size: clamp(1.25rem, 4vw, 1.75rem);
        padding: 0;
    }
}

.article-excerpt {
    font-size: clamp(1rem, 2.5vw, 1.25rem);
    line-height: 1.6;
    color: var(--gray-600);
    font-weight: 300;
    margin-bottom: var(--space-8);
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    padding: 0 var(--space-4);
}

@media (max-width: 768px) {
    .article-excerpt {
        font-size: clamp(0.9375rem, 2vw, 1.125rem);
        line-height: 1.5;
        margin-bottom: var(--space-6);
        -webkit-line-clamp: 4;
        padding: 0 var(--space-2);
    }
}

@media (max-width: 480px) {
    .article-excerpt {
        font-size: 0.9375rem;
        line-height: 1.4;
        -webkit-line-clamp: 5;
        padding: 0;
    }
}

.article-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-6);
    flex-wrap: wrap;
    font-family: var(--font-ui);
    font-size: 0.875rem;
    color: var(--gray-600);
    padding: var(--space-6) 0;
    border-top: 1px solid var(--gray-200);
    border-bottom: 1px solid var(--gray-200);
    width: 100%;
    overflow: hidden;
}

@media (max-width: 768px) {
    .article-meta {
        gap: var(--space-4);
        font-size: 0.8125rem;
        padding: var(--space-4) 0;
    }
}

@media (max-width: 480px) {
    .article-meta {
        flex-direction: column;
        gap: var(--space-3);
        align-items: center;
        padding: var(--space-3) 0;
    }
}

.meta-item {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    flex-shrink: 0;
    min-width: 0;
}

.meta-item i {
    color: var(--accent);
    font-size: 0.875rem;
    flex-shrink: 0;
}

@media (max-width: 480px) {
    .meta-item i {
        font-size: 0.8125rem;
    }
}

.meta-item span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    min-width: 0;
}

@media (max-width: 480px) {
    .meta-item span {
        font-size: 0.75rem;
    }
}

.meta-divider {
    width: 1px;
    height: 16px;
    background: var(--gray-300);
    flex-shrink: 0;
}

@media (max-width: 480px) {
    .meta-divider {
        display: none;
    }
}

/* ==========================================
   FEATURED IMAGE
   ========================================== */
.article-hero {
    position: relative;
    width: 100%;
    max-width: 1200px;
    margin: var(--space-12) auto;
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-xl);
}

@media (max-width: 768px) {
    .article-hero {
        margin: var(--space-8) auto;
        border-radius: var(--radius-lg);
    }
}

.hero-image-wrapper {
    position: relative;
    padding-top: 56.25%;
    background: var(--gray-100);
    width: 100%;
}

.hero-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 150px;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
}

/* ==========================================
   ARTICLE CONTENT - Mobile Optimized
   ========================================== */
.article-main {
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    width: 100%;
    min-width: 0;
}

@media (max-width: 768px) {
    .article-main {
        border-radius: var(--radius-lg);
    }
}

.article-body {
    max-width: 720px;
    margin: 0 auto;
    padding: var(--space-16) var(--space-8);
    width: 100%;
    min-width: 0;
}

@media (max-width: 768px) {
    .article-body {
        padding: var(--space-12) var(--space-6);
    }
}

@media (max-width: 480px) {
    .article-body {
        padding: var(--space-8) var(--space-4);
    }
}

.article-content {
    font-size: clamp(1rem, 2vw, 1.125rem);
    line-height: 1.7;
    color: var(--gray-800);
    width: 100%;
    overflow-wrap: break-word;
    word-wrap: break-word;
    hyphens: auto;
}

@media (max-width: 480px) {
    .article-content {
        font-size: 1rem;
        line-height: 1.6;
    }
}

.article-content > * {
    margin-bottom: var(--space-6);
    max-width: 100%;
    overflow: hidden;
}

@media (max-width: 768px) {
    .article-content > * {
        margin-bottom: var(--space-4);
    }
}

.article-content p {
    margin-bottom: var(--space-6);
    text-align: justify;
    hyphens: auto;
}

@media (max-width: 768px) {
    .article-content p {
        margin-bottom: var(--space-4);
        line-height: 1.6;
    }
}

.article-content h2 {
    font-size: clamp(1.5rem, 3vw, 2rem);
    margin-top: var(--space-12);
    margin-bottom: var(--space-6);
    color: var(--gray-900);
    overflow-wrap: break-word;
    word-wrap: break-word;
}

@media (max-width: 768px) {
    .article-content h2 {
        font-size: 1.5rem;
        margin-top: var(--space-8);
        margin-bottom: var(--space-4);
    }
}

@media (max-width: 480px) {
    .article-content h2 {
        font-size: 1.25rem;
        margin-top: var(--space-6);
    }
}

.article-content h3 {
    font-size: clamp(1.25rem, 2.5vw, 1.5rem);
    margin-top: var(--space-10);
    margin-bottom: var(--space-5);
    color: var(--gray-900);
    overflow-wrap: break-word;
    word-wrap: break-word;
}

@media (max-width: 768px) {
    .article-content h3 {
        font-size: 1.25rem;
        margin-top: var(--space-6);
        margin-bottom: var(--space-3);
    }
}

@media (max-width: 480px) {
    .article-content h3 {
        font-size: 1.125rem;
        margin-top: var(--space-4);
    }
}

.article-content h4 {
    font-size: clamp(1.125rem, 2vw, 1.25rem);
    margin-top: var(--space-8);
    margin-bottom: var(--space-4);
    color: var(--gray-900);
    overflow-wrap: break-word;
    word-wrap: break-word;
}

@media (max-width: 768px) {
    .article-content h4 {
        font-size: 1.125rem;
        margin-top: var(--space-4);
        margin-bottom: var(--space-2);
    }
}

.article-content img {
    width: 100%;
    max-width: 100%;
    height: auto;
    border-radius: var(--radius-lg);
    margin: var(--space-12) 0;
    box-shadow: var(--shadow-lg);
}

@media (max-width: 768px) {
    .article-content img {
        margin: var(--space-8) 0;
        border-radius: var(--radius-md);
    }
}

.article-content ul,
.article-content ol {
    margin-left: var(--space-8);
    margin-bottom: var(--space-6);
    overflow: hidden;
}

@media (max-width: 768px) {
    .article-content ul,
    .article-content ol {
        margin-left: var(--space-6);
        margin-bottom: var(--space-4);
    }
}

.article-content li {
    margin-bottom: var(--space-3);
    overflow-wrap: break-word;
    word-wrap: break-word;
}

@media (max-width: 768px) {
    .article-content li {
        margin-bottom: var(--space-2);
    }
}

.article-content blockquote {
    border-left: 4px solid var(--accent);
    padding: var(--space-6) var(--space-8);
    margin: var(--space-12) 0;
    background: var(--beige);
    border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
    font-style: italic;
    font-size: clamp(1rem, 2vw, 1.25rem);
    color: var(--gray-700);
    overflow-wrap: break-word;
    word-wrap: break-word;
}

@media (max-width: 768px) {
    .article-content blockquote {
        padding: var(--space-4) var(--space-6);
        margin: var(--space-8) 0;
        font-size: 1.125rem;
    }
}

@media (max-width: 480px) {
    .article-content blockquote {
        padding: var(--space-3) var(--space-4);
        margin: var(--space-6) 0;
        font-size: 1rem;
        border-left-width: 3px;
    }
}

.article-content blockquote p:last-child {
    margin-bottom: 0;
}

.article-content code {
    background: var(--gray-100);
    padding: var(--space-1) var(--space-2);
    border-radius: var(--radius-sm);
    font-family: 'Monaco', 'Courier New', monospace;
    font-size: 0.9em;
    word-break: break-word;
}

.article-content pre {
    background: var(--gray-900);
    color: var(--gray-100);
    padding: var(--space-6);
    border-radius: var(--radius-lg);
    overflow-x: auto;
    margin: var(--space-10) 0;
    -webkit-overflow-scrolling: touch;
}

@media (max-width: 768px) {
    .article-content pre {
        padding: var(--space-4);
        margin: var(--space-8) 0;
        font-size: 0.875rem;
    }
}

.article-content pre code {
    background: none;
    padding: 0;
    color: inherit;
    white-space: pre;
    word-break: normal;
    overflow-wrap: normal;
}

.article-content a {
    color: var(--primary);
    text-decoration: underline;
    text-decoration-color: rgba(93, 74, 138, 0.3);
    text-underline-offset: 2px;
    transition: var(--transition);
    word-break: break-word;
}

.article-content a:hover {
    color: var(--primary-dark);
    text-decoration-color: var(--primary-dark);
}

/* Drop Cap - First Letter */
.article-content p:first-of-type::first-letter {
    font-family: var(--font-display);
    font-size: clamp(2.5rem, 6vw, 4.5rem);
    line-height: 0.8;
    float: left;
    margin: 0.1em 0.15em 0 0;
    color: var(--primary);
    font-weight: 700;
}

@media (max-width: 768px) {
    .article-content p:first-of-type::first-letter {
        font-size: 3rem;
        margin: 0.05em 0.1em 0 0;
    }
}

@media (max-width: 480px) {
    .article-content p:first-of-type::first-letter {
        font-size: 2.5rem;
    }
}

/* ==========================================
   ARTICLE FOOTER
   ========================================== */
.article-footer {
    max-width: 720px;
    margin: 0 auto;
    padding: var(--space-12) var(--space-8) var(--space-16);
    border-top: 2px solid var(--gray-200);
    width: 100%;
    min-width: 0;
}

@media (max-width: 768px) {
    .article-footer {
        padding: var(--space-8) var(--space-6) var(--space-12);
    }
}

@media (max-width: 480px) {
    .article-footer {
        padding: var(--space-6) var(--space-4) var(--space-8);
    }
}

.article-tags {
    margin-bottom: var(--space-10);
    width: 100%;
    overflow: hidden;
}

@media (max-width: 768px) {
    .article-tags {
        margin-bottom: var(--space-8);
    }
}

.tags-label {
    font-family: var(--font-ui);
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--gray-700);
    margin-bottom: var(--space-4);
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 480px) {
    .tags-label {
        font-size: 0.8125rem;
        margin-bottom: var(--space-3);
    }
}

.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-3);
    list-style: none;
    overflow: hidden;
}

@media (max-width: 480px) {
    .tags-list {
        gap: var(--space-2);
    }
}

.tag-link {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    font-family: var(--font-ui);
    font-size: 0.875rem;
    padding: var(--space-2) var(--space-4);
    background: var(--beige);
    color: var(--gray-700);
    border-radius: var(--radius-full);
    transition: var(--transition);
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex-shrink: 0;
}

@media (max-width: 480px) {
    .tag-link {
        font-size: 0.8125rem;
        padding: var(--space-1) var(--space-3);
        gap: var(--space-1);
    }
}

.tag-link:hover {
    background: var(--primary);
    color: var(--white);
    transform: translateY(-2px);
}

/* Share Section */
.share-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-8);
    background: var(--beige);
    border-radius: var(--radius-xl);
    flex-wrap: wrap;
    gap: var(--space-6);
    width: 100%;
    overflow: hidden;
}

@media (max-width: 768px) {
    .share-section {
        padding: var(--space-6);
        gap: var(--space-4);
    }
}

@media (max-width: 480px) {
    .share-section {
        flex-direction: column;
        align-items: flex-start;
        padding: var(--space-4);
        gap: var(--space-3);
    }
}

.share-label {
    font-family: var(--font-ui);
    font-weight: 600;
    color: var(--gray-800);
    font-size: clamp(0.875rem, 1.5vw, 1rem);
    flex-shrink: 0;
}

@media (max-width: 480px) {
    .share-label {
        font-size: 0.875rem;
    }
}

.share-buttons {
    display: flex;
    gap: var(--space-3);
    flex-wrap: wrap;
}

@media (max-width: 480px) {
    .share-buttons {
        gap: var(--space-2);
        width: 100%;
        justify-content: space-between;
    }
}

.share-btn {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: var(--white);
    transition: var(--transition);
    flex-shrink: 0;
}

@media (max-width: 480px) {
    .share-btn {
        width: 40px;
        height: 40px;
        font-size: 0.875rem;
    }
}

.share-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.share-btn.facebook { background: #1877F2; }
.share-btn.twitter { background: #1DA1F2; }
.share-btn.linkedin { background: #0A66C2; }
.share-btn.whatsapp { background: #25D366; }
.share-btn.email { background: var(--gray-700); }

/* Back Button */
.back-button {
    display: inline-flex;
    align-items: center;
    gap: var(--space-3);
    font-family: var(--font-ui);
    font-weight: 600;
    color: var(--primary);
    padding: var(--space-4) var(--space-6);
    border: 2px solid var(--primary);
    border-radius: var(--radius-xl);
    transition: var(--transition);
    margin-top: var(--space-8);
    width: 100%;
    justify-content: center;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .back-button {
        padding: var(--space-3) var(--space-4);
        margin-top: var(--space-6);
        font-size: 0.9375rem;
    }
}

@media (max-width: 480px) {
    .back-button {
        padding: var(--space-3);
        font-size: 0.875rem;
        gap: var(--space-2);
    }
}

.back-button:hover {
    background: var(--primary);
    color: var(--white);
    transform: translateX(-4px);
}

/* ==========================================
   SIDEBAR - Responsive Design
   ========================================== */
.article-sidebar {
    width: 100%;
    min-width: 0;
}

@media (min-width: 1024px) {
    .article-sidebar {
        position: sticky;
        top: var(--space-16);
        height: fit-content;
    }
}

.sidebar-section {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-8);
    margin-bottom: var(--space-6);
    box-shadow: var(--shadow);
    width: 100%;
    min-width: 0;
}

@media (max-width: 768px) {
    .sidebar-section {
        padding: var(--space-6);
        margin-bottom: var(--space-4);
    }
}

@media (max-width: 480px) {
    .sidebar-section {
        padding: var(--space-4);
    }
}

.sidebar-title {
    font-family: var(--font-display);
    font-size: clamp(1.125rem, 2vw, 1.25rem);
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--space-6);
    padding-bottom: var(--space-4);
    border-bottom: 2px solid var(--gray-200);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .sidebar-title {
        font-size: 1.125rem;
        margin-bottom: var(--space-4);
        padding-bottom: var(--space-3);
    }
}

/* Author Card */
.author-card {
    text-align: center;
    width: 100%;
    overflow: hidden;
}

.author-avatar {
    width: 100px;
    height: 100px;
    margin: 0 auto var(--space-5);
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-size: clamp(2rem, 4vw, 2.5rem);
    font-weight: 700;
    color: var(--white);
    box-shadow: var(--shadow-lg);
}

@media (max-width: 768px) {
    .author-avatar {
        width: 80px;
        height: 80px;
        font-size: 1.75rem;
        margin-bottom: var(--space-4);
    }
}

.author-name {
    font-family: var(--font-display);
    font-size: clamp(1.125rem, 2vw, 1.25rem);
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--space-2);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .author-name {
        font-size: 1.125rem;
    }
}

.author-role {
    font-family: var(--font-ui);
    font-size: 0.875rem;
    color: var(--accent);
    font-weight: 500;
    margin-bottom: var(--space-4);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .author-role {
        font-size: 0.8125rem;
        margin-bottom: var(--space-3);
    }
}

.author-bio {
    font-size: clamp(0.875rem, 1.5vw, 0.9375rem);
    line-height: 1.6;
    color: var(--gray-600);
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
}

@media (max-width: 768px) {
    .author-bio {
        font-size: 0.875rem;
        -webkit-line-clamp: 3;
    }
}

/* ==========================================
   POPULAR ARTICLES - DYNAMIC VERSION WITH FIXED READING TIME
   ========================================== */
.popular-list {
    list-style: none;
    width: 100%;
}

.popular-item {
    padding: var(--space-4) 0;
    border-bottom: 1px solid var(--gray-200);
    width: 100%;
    overflow: hidden;
}

@media (max-width: 768px) {
    .popular-item {
        padding: var(--space-3) 0;
    }
}

.popular-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.popular-item:first-child {
    padding-top: 0;
}

.popular-link {
    display: block;
    transition: var(--transition);
    width: 100%;
    overflow: hidden;
}

.popular-link:hover {
    transform: translateX(4px);
}

.popular-title {
    font-size: clamp(0.9375rem, 1.5vw, 1rem);
    font-weight: 500;
    line-height: 1.4;
    color: var(--gray-800);
    margin-bottom: var(--space-2);
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    min-height: 2.8em;
}

@media (max-width: 768px) {
    .popular-title {
        font-size: 0.9375rem;
        -webkit-line-clamp: 3;
        min-height: 4.2em;
    }
}

.popular-meta {
    font-family: var(--font-ui);
    font-size: 0.75rem;
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: var(--space-3);
    overflow: hidden;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .popular-meta {
        gap: var(--space-2);
        font-size: 0.6875rem;
    }
}

/* Newsletter */
.newsletter-form {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    width: 100%;
}

@media (max-width: 768px) {
    .newsletter-form {
        gap: var(--space-3);
    }
}

.newsletter-description {
    font-size: clamp(0.875rem, 1.5vw, 0.9375rem);
    line-height: 1.6;
    color: var(--gray-600);
    margin-bottom: var(--space-4);
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

@media (max-width: 768px) {
    .newsletter-description {
        font-size: 0.875rem;
        margin-bottom: var(--space-3);
        -webkit-line-clamp: 4;
    }
}

.newsletter-input-wrapper {
    position: relative;
    width: 100%;
}

.newsletter-input {
    width: 100%;
    padding: var(--space-4) var(--space-5);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-xl);
    font-family: var(--font-ui);
    font-size: 0.9375rem;
    transition: var(--transition);
    min-width: 0;
}

@media (max-width: 768px) {
    .newsletter-input {
        padding: var(--space-3) var(--space-4);
        font-size: 0.875rem;
    }
}

.newsletter-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(93, 74, 138, 0.1);
}

.newsletter-button {
    width: 100%;
    padding: var(--space-4) var(--space-6);
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border: none;
    border-radius: var(--radius-xl);
    font-family: var(--font-ui);
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    min-width: 0;
}

@media (max-width: 768px) {
    .newsletter-button {
        padding: var(--space-3) var(--space-4);
        font-size: 0.875rem;
    }
}

.newsletter-button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.newsletter-disclaimer {
    font-family: var(--font-ui);
    font-size: 0.75rem;
    color: var(--gray-500);
    line-height: 1.4;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* ==========================================
   RELATED ARTICLES
   ========================================== */
.related-section {
    margin-top: var(--space-20);
    padding: var(--space-16) 0;
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    width: 100%;
    overflow: hidden;
}

@media (max-width: 768px) {
    .related-section {
        margin-top: var(--space-16);
        padding: var(--space-12) 0;
    }
}

.related-header {
    max-width: 800px;
    margin: 0 auto var(--space-12);
    text-align: center;
    padding: 0 var(--space-6);
    width: 100%;
}

@media (max-width: 768px) {
    .related-header {
        margin-bottom: var(--space-8);
        padding: 0 var(--space-4);
    }
}

.related-title {
    font-family: var(--font-display);
    font-size: clamp(1.5rem, 4vw, 2rem);
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: var(--space-4);
    overflow-wrap: break-word;
    word-wrap: break-word;
}

@media (max-width: 768px) {
    .related-title {
        font-size: 1.5rem;
        margin-bottom: var(--space-3);
    }
}

.related-subtitle {
    font-size: clamp(0.9375rem, 1.5vw, 1.125rem);
    color: var(--gray-600);
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    padding: 0 var(--space-4);
}

@media (max-width: 768px) {
    .related-subtitle {
        font-size: 0.9375rem;
        -webkit-line-clamp: 3;
    }
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--space-8);
    padding: 0 var(--space-8);
    width: 100%;
}

@media (max-width: 768px) {
    .related-grid {
        grid-template-columns: 1fr;
        gap: var(--space-6);
        padding: 0 var(--space-6);
    }
}

@media (max-width: 480px) {
    .related-grid {
        gap: var(--space-4);
        padding: 0 var(--space-4);
    }
}

.related-card {
    background: var(--beige);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: var(--transition);
    width: 100%;
    min-width: 0;
}

.related-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.related-image-wrapper {
    position: relative;
    padding-top: 60%;
    overflow: hidden;
    background: var(--gray-200);
    width: 100%;
}

.related-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.related-card:hover .related-image {
    transform: scale(1.1);
}

.related-content {
    padding: var(--space-6);
    width: 100%;
    min-width: 0;
}

@media (max-width: 768px) {
    .related-content {
        padding: var(--space-4);
    }
}

.related-category {
    font-family: var(--font-ui);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--primary);
    margin-bottom: var(--space-3);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 480px) {
    .related-category {
        font-size: 0.6875rem;
        margin-bottom: var(--space-2);
    }
}

.related-card-title {
    font-size: clamp(1.125rem, 2vw, 1.25rem);
    font-weight: 600;
    line-height: 1.3;
    color: var(--gray-900);
    margin-bottom: var(--space-3);
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    min-height: 2.6em;
}

@media (max-width: 768px) {
    .related-card-title {
        font-size: 1.125rem;
        -webkit-line-clamp: 3;
        min-height: 3.9em;
    }
}

.related-card-title a {
    color: inherit;
}

.related-card-meta {
    font-family: var(--font-ui);
    font-size: 0.875rem;
    color: var(--gray-600);
    display: flex;
    align-items: center;
    gap: var(--space-4);
    overflow: hidden;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .related-card-meta {
        font-size: 0.8125rem;
        gap: var(--space-3);
    }
}

@media (max-width: 480px) {
    .related-card-meta {
        font-size: 0.75rem;
        gap: var(--space-2);
    }
}

/* ==========================================
   CALL TO ACTION
   ========================================== */
.cta-banner {
    margin-top: var(--space-20);
    padding: var(--space-16) var(--space-8);
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    color: var(--white);
    border-radius: var(--radius-xl);
    text-align: center;
    position: relative;
    overflow: hidden;
    width: 100%;
}

@media (max-width: 768px) {
    .cta-banner {
        margin-top: var(--space-16);
        padding: var(--space-12) var(--space-6);
    }
}

@media (max-width: 480px) {
    .cta-banner {
        padding: var(--space-8) var(--space-4);
    }
}

.cta-content {
    position: relative;
    z-index: 1;
    max-width: 700px;
    margin: 0 auto;
    width: 100%;
}

.cta-title {
    font-family: var(--font-display);
    font-size: clamp(1.5rem, 4vw, 2rem);
    font-weight: 700;
    margin-bottom: var(--space-4);
    overflow-wrap: break-word;
    word-wrap: break-word;
    padding: 0 var(--space-4);
}

@media (max-width: 768px) {
    .cta-title {
        font-size: 1.5rem;
        margin-bottom: var(--space-3);
        padding: 0 var(--space-2);
    }
}

.cta-description {
    font-size: clamp(0.9375rem, 1.5vw, 1.125rem);
    line-height: 1.6;
    margin-bottom: var(--space-8);
    opacity: 0.95;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    padding: 0 var(--space-6);
}

@media (max-width: 768px) {
    .cta-description {
        font-size: 0.9375rem;
        margin-bottom: var(--space-6);
        -webkit-line-clamp: 4;
        padding: 0 var(--space-4);
    }
}

@media (max-width: 480px) {
    .cta-description {
        font-size: 0.875rem;
        -webkit-line-clamp: 5;
        padding: 0 var(--space-2);
    }
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: var(--space-4);
    flex-wrap: wrap;
    width: 100%;
}

@media (max-width: 480px) {
    .cta-buttons {
        flex-direction: column;
        gap: var(--space-3);
    }
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-4) var(--space-8);
    font-family: var(--font-ui);
    font-weight: 600;
    font-size: 0.9375rem;
    border-radius: var(--radius-xl);
    transition: var(--transition);
    cursor: pointer;
    border: 2px solid;
    flex-shrink: 0;
    min-width: 0;
    justify-content: center;
    text-align: center;
}

@media (max-width: 768px) {
    .btn {
        padding: var(--space-3) var(--space-6);
        font-size: 0.875rem;
    }
}

@media (max-width: 480px) {
    .btn {
        width: 100%;
        padding: var(--space-3) var(--space-4);
        font-size: 0.875rem;
    }
}

.btn-primary {
    background: var(--white);
    color: var(--primary);
    border-color: var(--white);
}

.btn-primary:hover {
    background: var(--accent);
    color: var(--white);
    border-color: var(--accent);
    transform: translateY(-2px);
}

.btn-outline {
    background: transparent;
    color: var(--white);
    border-color: var(--white);
}

.btn-outline:hover {
    background: var(--white);
    color: var(--primary);
    transform: translateY(-2px);
}

/* ==========================================
   EXTREME MOBILE FIXES (320px and below)
   ========================================== */
@media (max-width: 320px) {
    /* Further reduce font sizes */
    body {
        font-size: 16px;
    }
    
    .article-title {
        font-size: 1.25rem;
        line-height: 1.3;
    }
    
    .article-excerpt {
        font-size: 0.875rem;
        line-height: 1.4;
        -webkit-line-clamp: 6;
    }
    
    .article-content {
        font-size: 0.9375rem;
        line-height: 1.5;
    }
    
    .article-content h2 {
        font-size: 1.125rem;
    }
    
    .article-content h3 {
        font-size: 1rem;
    }
    
    .article-content h4 {
        font-size: 0.9375rem;
    }
    
    /* Adjust spacing */
    .article-meta {
        font-size: 0.75rem;
        gap: var(--space-2);
    }
    
    .meta-item i {
        font-size: 0.75rem;
    }
    
    /* Make sidebar sections full width */
    .sidebar-section {
        padding: var(--space-3);
    }
    
    .sidebar-title {
        font-size: 1rem;
    }
    
    .author-avatar {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .author-name {
        font-size: 1rem;
    }
    
    .author-role {
        font-size: 0.75rem;
    }
    
    .author-bio {
        font-size: 0.8125rem;
        -webkit-line-clamp: 5;
    }
    
    .popular-title {
        font-size: 0.875rem;
        -webkit-line-clamp: 4;
        min-height: 5.6em;
    }
    
    .popular-meta {
        font-size: 0.625rem;
    }
    
    /* Related articles */
    .related-grid {
        grid-template-columns: 1fr;
    }
    
    .related-card-title {
        font-size: 1rem;
        -webkit-line-clamp: 4;
        min-height: 5.2em;
    }
    
    /* CTA */
    .cta-title {
        font-size: 1.25rem;
    }
    
    .cta-description {
        font-size: 0.8125rem;
        -webkit-line-clamp: 6;
    }
    
    .btn {
        font-size: 0.8125rem;
        padding: var(--space-2) var(--space-3);
    }
}

/* ==========================================
   PRINT STYLES
   ========================================== */
@media print {
    .breadcrumb,
    .article-sidebar,
    .share-section,
    .back-button,
    .related-section,
    .cta-banner {
        display: none !important;
    }
    
    .article-grid {
        display: block !important;
    }
    
    body {
        background: white !important;
        font-size: 12pt !important;
        line-height: 1.5 !important;
    }
    
    .article-main,
    .sidebar-section {
        box-shadow: none !important;
        border: 1px solid var(--gray-300) !important;
    }
    
    .article-content {
        font-size: 12pt !important;
        line-height: 1.5 !important;
    }
    
    .article-title,
    .article-excerpt,
    .article-content h2,
    .article-content h3,
    .article-content h4 {
        overflow: visible !important;
        -webkit-line-clamp: unset !important;
        min-height: auto !important;
    }
}

/* ==========================================
   ACCESSIBILITY
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

:focus-visible {
    outline: 3px solid var(--accent);
    outline-offset: 3px;
    border-radius: var(--radius-sm);
}

/* ==========================================
   TEXT OVERFLOW UTILITIES
   ========================================== */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.truncate-multiline {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-box-orient: vertical;
}

/* ==========================================
   NOTIFICATION ANIMATIONS
   ========================================== */
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

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
    </style>
</head>
<body>

<!-- Breadcrumb -->
<nav class="breadcrumb" aria-label="Breadcrumb">
    <div class="container">
        <ol class="breadcrumb-list">
            <?php foreach ($breadcrumb as $item): ?>
            <li class="breadcrumb-item">
                <?php if (!empty($item['url'])): ?>
                <a href="<?php echo $item['url']; ?>" class="breadcrumb-link">
                    <?php echo $item['label']; ?>
                </a>
                <?php else: ?>
                <span class="breadcrumb-current"><?php echo $item['label']; ?></span>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>

<!-- Main Article -->
<main class="article-page">
    <div class="article-container">
        <!-- Article Header -->
        <header class="article-header">
            <?php if (!empty($news['category'])): ?>
            <span class="article-category"><?php echo htmlspecialchars($news['category']); ?></span>
            <?php endif; ?>
            
            <h1 class="article-title"><?php echo htmlspecialchars($news['title'] ?? 'News Article'); ?></h1>
            
            <?php if (!empty($news['excerpt'])): ?>
            <p class="article-excerpt"><?php echo htmlspecialchars($news['excerpt']); ?></p>
            <?php endif; ?>
            
            <div class="article-meta">
                <div class="meta-item">
                    <i class="far fa-calendar"></i>
                    <span><?php echo $newsDate; ?></span>
                </div>
                
                <span class="meta-divider"></span>
                
                <div class="meta-item">
                    <i class="far fa-clock"></i>
                    <span><?php echo $readingTime; ?> min read</span>
                </div>
                
                <?php if (!empty($news['views_count'])): ?>
                <span class="meta-divider"></span>
                <div class="meta-item">
                    <i class="far fa-eye"></i>
                    <span><?php echo number_format($news['views_count']); ?> views</span>
                </div>
                <?php endif; ?>
                
                <span class="meta-divider"></span>
                
                <div class="meta-item">
                    <i class="far fa-user"></i>
                    <span>By <?php echo htmlspecialchars($authorName); ?></span>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        <?php if (!empty($news['featured_image'])): ?>
        <div class="article-hero">
            <div class="hero-image-wrapper">
                <img src="<?php echo $baseUrl . $news['featured_image']; ?>" 
                     alt="<?php echo htmlspecialchars($news['title'] ?? ''); ?>" 
                     class="hero-image"
                     onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:100%;background:linear-gradient(135deg,var(--gray-200),var(--gray-300));display:flex;align-items:center;justify-content:center;color:var(--gray-500);font-size:3rem;\'><i class=\'fas fa-newspaper\'></i></div>';">
                <div class="hero-overlay"></div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Article Grid -->
        <div class="article-grid">
            <!-- Main Content -->
            <article class="article-main">
                <div class="article-body">
                    <div class="article-content">
                        <?php if (!empty($news['content'])): ?>
                            <?php echo $news['content']; ?>
                        <?php else: ?>
                            <p>This article's content is currently unavailable. Please check back later for updates.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <footer class="article-footer">
                    <!-- Tags -->
                    <?php if (!empty($news['tags'])): ?>
                    <div class="article-tags">
                        <span class="tags-label">Topics</span>
                        <?php 
                        $tags = [];
                        if (is_string($news['tags'])) {
                            $decoded = json_decode($news['tags'], true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $tags = $decoded;
                            } else {
                                $tags = array_map('trim', explode(',', $news['tags']));
                            }
                        } elseif (is_array($news['tags'])) {
                            $tags = $news['tags'];
                        }
                        
                        if (!empty($tags)):
                        ?>
                        <ul class="tags-list">
                            <?php foreach ($tags as $tag): ?>
                            <li>
                                <a href="<?php echo $baseUrl; ?>/news/search?q=<?php echo urlencode($tag); ?>" class="tag-link">
                                    <i class="fas fa-hashtag"></i>
                                    <?php echo htmlspecialchars(trim($tag)); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Share Section -->
                    <div class="share-section">
                        <span class="share-label">Share this article</span>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($baseUrl . '/news/' . ($news['slug'] ?? '')); ?>" 
                               class="share-btn facebook" 
                               target="_blank" 
                               rel="noopener"
                               aria-label="Share on Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($baseUrl . '/news/' . ($news['slug'] ?? '')); ?>&text=<?php echo urlencode($news['title'] ?? ''); ?>" 
                               class="share-btn twitter" 
                               target="_blank" 
                               rel="noopener"
                               aria-label="Share on Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($baseUrl . '/news/' . ($news['slug'] ?? '')); ?>" 
                               class="share-btn linkedin" 
                               target="_blank" 
                               rel="noopener"
                               aria-label="Share on LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://wa.me/?text=<?php echo urlencode($news['title'] . ' - ' . $baseUrl . '/news/' . ($news['slug'] ?? '')); ?>" 
                               class="share-btn whatsapp" 
                               target="_blank" 
                               rel="noopener"
                               aria-label="Share on WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="mailto:?subject=<?php echo urlencode($news['title'] ?? ''); ?>&body=<?php echo urlencode($baseUrl . '/news/' . ($news['slug'] ?? '')); ?>" 
                               class="share-btn email"
                               aria-label="Share via Email">
                                <i class="far fa-envelope"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <a href="<?php echo $baseUrl; ?>/news" class="back-button">
                        <i class="fas fa-arrow-left"></i>
                        Back to All News
                    </a>
                </footer>
            </article>

            <!-- Sidebar -->
            <aside class="article-sidebar">
                <!-- Author Section -->
                <div class="sidebar-section">
                    <h2 class="sidebar-title">About the Author</h2>
                    <div class="author-card">
                        <div class="author-avatar">
                            <?php echo strtoupper(substr($authorName, 0, 1)); ?>
                        </div>
                        <h3 class="author-name"><?php echo htmlspecialchars($authorName); ?></h3>
                        <p class="author-role">Healthcare Writer</p>
                        <p class="author-bio">
                            Dedicated to sharing the latest developments in nursing education and healthcare advancements.
                        </p>
                    </div>
                </div>

                <!-- ==========================================
                     SIDEBAR - POPULAR ARTICLES - DYNAMIC VERSION WITH FIXED READING TIME
                     ========================================== -->
                <div class="sidebar-section">
                    <h2 class="sidebar-title">Popular Articles</h2>
                    
                    <?php if (!empty($popularNews)): ?>
                        <ul class="popular-list">
                            <?php 
                            // Limit to top 5
                            $displayCount = 0;
                            $maxDisplay = 5;
                            foreach ($popularNews as $popular): 
                                if ($displayCount >= $maxDisplay) break;
                                $displayCount++;
                                
                                // Format date
                                $popularDate = !empty($popular['created_at']) 
                                    ? date('M j, Y', strtotime($popular['created_at'])) 
                                    : '';
                                
                                // Calculate reading time - FIXED: Always at least 1 minute
                                $popularWordCount = !empty($popular['content']) 
                                    ? str_word_count(strip_tags($popular['content'])) 
                                    : 0;
                                
                                // Ensure minimum reading time is 1 minute
                                $popularReadingTime = max(1, ceil($popularWordCount / 200));
                            ?>
                            <li class="popular-item">
                                <a href="<?php echo $baseUrl; ?>/news/<?php echo htmlspecialchars($popular['slug'] ?? ''); ?>" 
                                   class="popular-link">
                                    <h3 class="popular-title">
                                        <?php echo htmlspecialchars($popular['title'] ?? 'Untitled'); ?>
                                    </h3>
                                    <div class="popular-meta">
                                        <span>
                                            <i class="far fa-calendar"></i> 
                                            <?php echo $popularDate; ?>
                                        </span>
                                        <span>
                                            <i class="far fa-clock"></i> 
                                            <?php echo $popularReadingTime; ?> min read
                                        </span>
                                        <?php if (!empty($popular['views_count'])): ?>
                                        <span>
                                            <i class="far fa-eye"></i> 
                                            <?php echo number_format($popular['views_count']); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <!-- View All Popular Link -->
                        <div style="margin-top: var(--space-4); text-align: center;">
                            <a href="<?php echo $baseUrl; ?>/news?sort=popular" 
                               class="tag-link" 
                               style="display: inline-block;">
                                <i class="fas fa-fire"></i>
                                View All Popular
                            </a>
                        </div>
                        
                    <?php else: ?>
                        <!-- Fallback: Show message when no popular articles -->
                        <div style="text-align: center; padding: var(--space-4);">
                            <i class="fas fa-newspaper" style="font-size: 2rem; color: var(--gray-400); margin-bottom: var(--space-2);"></i>
                            <p style="color: var(--gray-600); margin-bottom: var(--space-3);">
                                No popular articles yet
                            </p>
                            <a href="<?php echo $baseUrl; ?>/news" class="tag-link">
                                Browse All News
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Newsletter Section -->
                <div class="sidebar-section">
                    <h2 class="sidebar-title">Stay Updated</h2>
                    <p class="newsletter-description">
                        Get the latest news and updates delivered directly to your inbox.
                    </p>
                    <form class="newsletter-form" id="newsletter-form">
                        <div class="newsletter-input-wrapper">
                            <input type="email" 
                                   class="newsletter-input" 
                                   placeholder="Your email address" 
                                   required
                                   aria-label="Email for newsletter">
                        </div>
                        <button type="submit" class="newsletter-button">
                            <i class="fas fa-paper-plane"></i>
                            Subscribe
                        </button>
                        <p class="newsletter-disclaimer">
                            We respect your privacy. Unsubscribe anytime.
                        </p>
                    </form>
                </div>
            </aside>
        </div>

        <!-- Related Articles -->
        <?php if (!empty($relatedNews)): ?>
        <section class="related-section">
            <div class="related-header">
                <h2 class="related-title">Related Articles</h2>
                <p class="related-subtitle">Continue exploring our latest news and updates</p>
            </div>
            
            <div class="related-grid">
                <?php foreach ($relatedNews as $related): ?>
                <article class="related-card">
                    <div class="related-image-wrapper">
                        <?php if (!empty($related['featured_image'])): ?>
                        <img src="<?php echo $baseUrl . $related['featured_image']; ?>" 
                             alt="<?php echo htmlspecialchars($related['title'] ?? ''); ?>" 
                             class="related-image"
                             onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:100%;background:linear-gradient(135deg,var(--gray-200),var(--gray-300));display:flex;align-items:center;justify-content:center;color:var(--gray-500);font-size:2.5rem;\'><i class=\'fas fa-newspaper\'></i></div>';">
                        <?php else: ?>
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--gray-200),var(--gray-300));display:flex;align-items:center;justify-content:center;color:var(--gray-500);font-size:2.5rem;">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="related-content">
                        <?php if (!empty($related['category'])): ?>
                        <span class="related-category"><?php echo htmlspecialchars($related['category']); ?></span>
                        <?php endif; ?>
                        <h3 class="related-card-title">
                            <a href="<?php echo $baseUrl; ?>/news/<?php echo $related['slug']; ?>">
                                <?php echo htmlspecialchars(substr($related['title'] ?? '', 0, 60)) . (strlen($related['title'] ?? '') > 60 ? '...' : ''); ?>
                            </a>
                        </h3>
                        <div class="related-card-meta">
                            <span><i class="far fa-calendar"></i> <?php echo date('M j, Y', strtotime($related['created_at'])); ?></span>
                            <span><i class="far fa-clock"></i> 
                                <?php 
                                $relatedWordCount = !empty($related['content']) ? str_word_count(strip_tags($related['content'])) : 0;
                                $relatedReadingTime = max(1, ceil($relatedWordCount / 200));
                                echo $relatedReadingTime; 
                                ?> min
                            </span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Call to Action -->
        <section class="cta-banner">
            <div class="cta-content">
                <h2 class="cta-title">Explore More News & Updates</h2>
                <p class="cta-description">
                    Stay informed with the latest developments in nursing education, research breakthroughs, and institutional announcements from FCT College of Nursing Sciences.
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo $baseUrl; ?>/news" class="btn btn-primary">
                        <i class="fas fa-newspaper"></i>
                        View All News
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" class="btn btn-outline">
                        <i class="fas fa-envelope"></i>
                        Contact Us
                    </a>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Newsletter subscription
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            if (validateEmail(email)) {
                const button = this.querySelector('.newsletter-button');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';
                button.disabled = true;
                
                setTimeout(() => {
                    showNotification('Thank you for subscribing!', 'success');
                    this.reset();
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }, 1500);
            } else {
                showNotification('Please enter a valid email address', 'error');
            }
        });
    }
    
    // Email validation
    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    // Notification system
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: var(--shadow-xl);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.3s ease;
            font-family: var(--font-ui);
            font-size: 0.9375rem;
        `;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
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
    
    // Back to top button
    const backToTop = document.createElement('button');
    backToTop.innerHTML = '<i class="fas fa-chevron-up"></i>';
    backToTop.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 3rem;
        height: 3rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: var(--shadow-lg);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100;
        transition: var(--transition);
    `;
    backToTop.setAttribute('aria-label', 'Back to top');
    
    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    document.body.appendChild(backToTop);
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTop.style.display = 'flex';
        } else {
            backToTop.style.display = 'none';
        }
    });
    
    // Dynamic text truncation for mobile
    function adjustTextForMobile() {
        const isMobile = window.innerWidth <= 480;
        
        // Adjust article excerpt
        const articleExcerpt = document.querySelector('.article-excerpt');
        if (articleExcerpt) {
            const text = articleExcerpt.textContent || articleExcerpt.innerText;
            if (isMobile && text.length > 150) {
                articleExcerpt.style.webkitLineClamp = '5';
            } else {
                articleExcerpt.style.webkitLineClamp = '3';
            }
        }
        
        // Adjust related article titles
        const relatedTitles = document.querySelectorAll('.related-card-title');
        relatedTitles.forEach(title => {
            const text = title.textContent || title.innerText;
            if (isMobile && text.length > 80) {
                title.style.webkitLineClamp = '4';
            } else if (window.innerWidth <= 320 && text.length > 60) {
                title.style.webkitLineClamp = '5';
            } else {
                title.style.webkitLineClamp = '2';
            }
        });
        
        // Adjust popular article titles
        const popularTitles = document.querySelectorAll('.popular-title');
        popularTitles.forEach(title => {
            const text = title.textContent || title.innerText;
            if (isMobile && text.length > 60) {
                title.style.webkitLineClamp = '4';
            } else if (window.innerWidth <= 320 && text.length > 40) {
                title.style.webkitLineClamp = '5';
            } else {
                title.style.webkitLineClamp = '2';
            }
        });
    }
    
    // Run on load and resize
    adjustTextForMobile();
    window.addEventListener('resize', adjustTextForMobile);
});
</script>

</body>
</html>