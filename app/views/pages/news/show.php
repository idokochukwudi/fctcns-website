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
$currentPage = $currentPage ?? 'news';
$pageTitle = $pageTitle ?? ($news['title'] ?? 'News Article') . ' - FCT College of Nursing Sciences';
$pageDescription = $pageDescription ?? ($news['excerpt'] ?? 'Read this news article from FCT College of Nursing Sciences');

// Format dates
$newsDate = !empty($news['created_at']) ? date('F j, Y', strtotime($news['created_at'])) : '';
$newsTime = !empty($news['created_at']) ? date('h:i A', strtotime($news['created_at'])) : '';

// Get author name
$authorName = $news['author_name'] ?? 'FCT Nursing College';

// Calculate reading time
$wordCount = !empty($news['content']) ? str_word_count(strip_tags($news['content'])) : 0;
$readingTime = ceil($wordCount / 200);

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
    --space-24: 6rem;
    
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

html {
    scroll-behavior: smooth;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

body {
    font-family: var(--font-body);
    font-size: 18px;
    line-height: 1.8;
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
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 var(--space-6);
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
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--gray-600);
}

.breadcrumb-item:not(:last-child)::after {
    content: "/";
    color: var(--gray-400);
}

.breadcrumb-link {
    color: var(--gray-600);
    transition: var(--transition);
}

.breadcrumb-link:hover {
    color: var(--primary);
}

.breadcrumb-current {
    color: var(--gray-900);
    font-weight: 500;
}

/* ==========================================
   ARTICLE LAYOUT
   ========================================== */
.article-page {
    min-height: 100vh;
    padding: var(--space-16) 0 var(--space-20);
}

.article-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--space-6);
}

.article-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: var(--space-16);
}

/* ==========================================
   ARTICLE HEADER
   ========================================== */
.article-header {
    max-width: 800px;
    margin: 0 auto var(--space-12);
    text-align: center;
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
}

.article-title {
    font-size: clamp(2.25rem, 6vw, 3.5rem);
    font-weight: 700;
    line-height: 1.1;
    color: var(--gray-900);
    margin-bottom: var(--space-6);
    letter-spacing: -0.02em;
}

.article-excerpt {
    font-size: 1.25rem;
    line-height: 1.6;
    color: var(--gray-600);
    font-weight: 300;
    margin-bottom: var(--space-8);
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
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
}

.meta-item {
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.meta-item i {
    color: var(--accent);
}

.meta-divider {
    width: 1px;
    height: 16px;
    background: var(--gray-300);
}

/* ==========================================
   FEATURED IMAGE
   ========================================== */
.article-hero {
    position: relative;
    width: 100%;
    max-width: 1200px;
    margin: var(--space-12) auto;
    border-radius: var(--radius-2xl);
    overflow: hidden;
    box-shadow: var(--shadow-xl);
}

.hero-image-wrapper {
    position: relative;
    padding-top: 56.25%;
    background: var(--gray-100);
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
   ARTICLE CONTENT
   ========================================== */
.article-main {
    background: var(--white);
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.article-body {
    max-width: 720px;
    margin: 0 auto;
    padding: var(--space-16) var(--space-8);
}

.article-content {
    font-size: 1.125rem;
    line-height: 1.8;
    color: var(--gray-800);
}

.article-content > * {
    margin-bottom: var(--space-6);
}

.article-content p {
    margin-bottom: var(--space-6);
}

.article-content h2 {
    font-size: 2rem;
    margin-top: var(--space-12);
    margin-bottom: var(--space-6);
    color: var(--gray-900);
}

.article-content h3 {
    font-size: 1.5rem;
    margin-top: var(--space-10);
    margin-bottom: var(--space-5);
    color: var(--gray-900);
}

.article-content h4 {
    font-size: 1.25rem;
    margin-top: var(--space-8);
    margin-bottom: var(--space-4);
    color: var(--gray-900);
}

.article-content img {
    width: 100%;
    border-radius: var(--radius-xl);
    margin: var(--space-12) 0;
    box-shadow: var(--shadow-lg);
}

.article-content ul,
.article-content ol {
    margin-left: var(--space-8);
    margin-bottom: var(--space-6);
}

.article-content li {
    margin-bottom: var(--space-3);
}

.article-content blockquote {
    border-left: 4px solid var(--accent);
    padding: var(--space-6) var(--space-8);
    margin: var(--space-12) 0;
    background: var(--beige);
    border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
    font-style: italic;
    font-size: 1.25rem;
    color: var(--gray-700);
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
}

.article-content pre {
    background: var(--gray-900);
    color: var(--gray-100);
    padding: var(--space-6);
    border-radius: var(--radius-lg);
    overflow-x: auto;
    margin: var(--space-10) 0;
}

.article-content pre code {
    background: none;
    padding: 0;
    color: inherit;
}

.article-content a {
    color: var(--primary);
    text-decoration: underline;
    text-decoration-color: rgba(93, 74, 138, 0.3);
    text-underline-offset: 2px;
    transition: var(--transition);
}

.article-content a:hover {
    color: var(--primary-dark);
    text-decoration-color: var(--primary-dark);
}

/* Drop Cap - First Letter */
.article-content p:first-of-type::first-letter {
    font-family: var(--font-display);
    font-size: 4.5rem;
    line-height: 0.8;
    float: left;
    margin: 0.1em 0.15em 0 0;
    color: var(--primary);
    font-weight: 700;
}

/* ==========================================
   ARTICLE FOOTER
   ========================================== */
.article-footer {
    max-width: 720px;
    margin: 0 auto;
    padding: var(--space-12) var(--space-8) var(--space-16);
    border-top: 2px solid var(--gray-200);
}

.article-tags {
    margin-bottom: var(--space-10);
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
}

.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-3);
    list-style: none;
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
}

.share-label {
    font-family: var(--font-ui);
    font-weight: 600;
    color: var(--gray-800);
}

.share-buttons {
    display: flex;
    gap: var(--space-3);
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
}

.back-button:hover {
    background: var(--primary);
    color: var(--white);
    transform: translateX(-4px);
}

/* ==========================================
   SIDEBAR
   ========================================== */
.article-sidebar {
    position: sticky;
    top: var(--space-20);
    height: fit-content;
}

.sidebar-section {
    background: var(--white);
    border-radius: var(--radius-2xl);
    padding: var(--space-8);
    margin-bottom: var(--space-6);
    box-shadow: var(--shadow);
}

.sidebar-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--space-6);
    padding-bottom: var(--space-4);
    border-bottom: 2px solid var(--gray-200);
}

/* Author Card */
.author-card {
    text-align: center;
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
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--white);
    box-shadow: var(--shadow-lg);
}

.author-name {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--space-2);
}

.author-role {
    font-family: var(--font-ui);
    font-size: 0.875rem;
    color: var(--accent);
    font-weight: 500;
    margin-bottom: var(--space-4);
}

.author-bio {
    font-size: 0.9375rem;
    line-height: 1.6;
    color: var(--gray-600);
}

/* Popular Articles */
.popular-list {
    list-style: none;
}

.popular-item {
    padding: var(--space-4) 0;
    border-bottom: 1px solid var(--gray-200);
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
}

.popular-link:hover {
    transform: translateX(4px);
}

.popular-title {
    font-size: 1rem;
    font-weight: 500;
    line-height: 1.4;
    color: var(--gray-800);
    margin-bottom: var(--space-2);
}

.popular-meta {
    font-family: var(--font-ui);
    font-size: 0.75rem;
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

/* Newsletter */
.newsletter-form {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}

.newsletter-description {
    font-size: 0.9375rem;
    line-height: 1.6;
    color: var(--gray-600);
    margin-bottom: var(--space-4);
}

.newsletter-input-wrapper {
    position: relative;
}

.newsletter-input {
    width: 100%;
    padding: var(--space-4) var(--space-5);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-xl);
    font-family: var(--font-ui);
    font-size: 0.9375rem;
    transition: var(--transition);
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
}

/* ==========================================
   RELATED ARTICLES
   ========================================== */
.related-section {
    margin-top: var(--space-20);
    padding: var(--space-16) 0;
    background: var(--white);
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-lg);
}

.related-header {
    max-width: 800px;
    margin: 0 auto var(--space-12);
    text-align: center;
    padding: 0 var(--space-6);
}

.related-title {
    font-family: var(--font-display);
    font-size: clamp(1.875rem, 4vw, 2.5rem);
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: var(--space-4);
}

.related-subtitle {
    font-size: 1.125rem;
    color: var(--gray-600);
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: var(--space-8);
    padding: 0 var(--space-8);
}

.related-card {
    background: var(--beige);
    border-radius: var(--radius-xl);
    overflow: hidden;
    transition: var(--transition);
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
}

.related-category {
    font-family: var(--font-ui);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--primary);
    margin-bottom: var(--space-3);
}

.related-card-title {
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.3;
    color: var(--gray-900);
    margin-bottom: var(--space-3);
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
}

/* ==========================================
   CALL TO ACTION
   ========================================== */
.cta-banner {
    margin-top: var(--space-20);
    padding: var(--space-20) var(--space-8);
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    color: var(--white);
    border-radius: var(--radius-2xl);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.cta-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
}

.cta-content {
    position: relative;
    z-index: 1;
    max-width: 700px;
    margin: 0 auto;
}

.cta-title {
    font-family: var(--font-display);
    font-size: clamp(1.875rem, 4vw, 2.5rem);
    font-weight: 700;
    margin-bottom: var(--space-4);
}

.cta-description {
    font-size: 1.125rem;
    line-height: 1.6;
    margin-bottom: var(--space-8);
    opacity: 0.95;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: var(--space-4);
    flex-wrap: wrap;
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
   RESPONSIVE DESIGN
   ========================================== */

/* Large Tablets (1024px) */
@media (max-width: 1024px) {
    .article-grid {
        grid-template-columns: 1fr;
    }
    
    .article-sidebar {
        position: static;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-6);
    }
}

/* Medium Tablets (768px) */
@media (max-width: 768px) {
    :root {
        --space-16: 3rem;
        --space-20: 4rem;
        --space-24: 5rem;
    }
    
    .article-page {
        padding: var(--space-12) 0 var(--space-16);
    }
    
    .article-body {
        padding: var(--space-12) var(--space-6);
    }
    
    .article-footer {
        padding: var(--space-10) var(--space-6) var(--space-12);
    }
    
    .share-section {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .related-grid {
        grid-template-columns: 1fr;
    }
}

/* Mobile (576px) */
@media (max-width: 576px) {
    body {
        font-size: 17px;
    }
    
    .article-container {
        padding: 0 var(--space-4);
    }
    
    .article-header {
        margin-bottom: var(--space-10);
    }
    
    .article-meta {
        flex-direction: column;
        gap: var(--space-3);
    }
    
    .meta-divider {
        display: none;
    }
    
    .article-body {
        padding: var(--space-10) var(--space-5);
    }
    
    .article-content {
        font-size: 1.0625rem;
    }
    
    .article-content p:first-of-type::first-letter {
        font-size: 3.5rem;
    }
    
    .sidebar-section {
        padding: var(--space-6);
    }
    
    .cta-banner {
        padding: var(--space-16) var(--space-6);
    }
    
    .cta-buttons {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
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
        grid-template-columns: 1fr !important;
    }
    
    body {
        background: white !important;
    }
    
    .article-main,
    .sidebar-section {
        box-shadow: none !important;
        border: 1px solid var(--gray-300) !important;
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
                <!-- Author -->
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

                <!-- Popular Articles -->
                <div class="sidebar-section">
                    <h2 class="sidebar-title">Popular Articles</h2>
                    <ul class="popular-list">
                        <li class="popular-item">
                            <a href="<?php echo $baseUrl; ?>/news/sample" class="popular-link">
                                <h3 class="popular-title">Latest Nursing Research Findings 2024</h3>
                                <div class="popular-meta">
                                    <span><i class="far fa-calendar"></i> Jan 15, 2024</span>
                                    <span><i class="far fa-clock"></i> 5 min</span>
                                </div>
                            </a>
                        </li>
                        <li class="popular-item">
                            <a href="<?php echo $baseUrl; ?>/news/sample" class="popular-link">
                                <h3 class="popular-title">New Healthcare Initiatives in Nigeria</h3>
                                <div class="popular-meta">
                                    <span><i class="far fa-calendar"></i> Jan 10, 2024</span>
                                    <span><i class="far fa-clock"></i> 4 min</span>
                                </div>
                            </a>
                        </li>
                        <li class="popular-item">
                            <a href="<?php echo $baseUrl; ?>/news/sample" class="popular-link">
                                <h3 class="popular-title">Student Achievements and Awards</h3>
                                <div class="popular-meta">
                                    <span><i class="far fa-calendar"></i> Dec 28, 2023</span>
                                    <span><i class="far fa-clock"></i> 6 min</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
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
                                echo ceil($relatedWordCount / 200); 
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
});
</script>

</body>
</html>