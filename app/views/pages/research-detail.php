<?php
/**
 * Research Publication Detail Page
 * Professional Design with Consistent Styling
 * 
 * @package FCTCNS
 */

// Security check - ensure publication exists
if (!isset($publication) || !isset($categories)) {
    http_response_code(404);
    echo '<h1>Publication Not Found</h1>';
    echo '<p>The requested research publication could not be found.</p>';
    echo '<p><a href="/research">Return to Research</a></p>';
    return;
}

// Helper function for escaping output
if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="<?php echo e($publication['title'] ?? 'Research Publication'); ?>">
    <title><?php echo e($pageTitle ?? 'Research Publication - FCT College of Nursing Sciences'); ?></title>
    
    <!-- Professional Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
<style>
/* ==========================================================================
   PROFESSIONAL DESIGN SYSTEM - MATCHING RESEARCH.PHP
   ========================================================================== */
:root {
    /* Professional Color Palette - Matching research.php */
    --color-primary: #5D4A8A;           /* Deep sophisticated purple */
    --color-primary-dark: #4A3A6F;
    --color-primary-light: #6F5B9E;
    --color-primary-very-light: #F8F6FC;
    --color-primary-transparent: rgba(93, 74, 138, 0.08);
    
    --color-secondary: #3A6B8F;         /* Professional blue */
    --color-secondary-dark: #2D5570;
    
    --color-accent: #D4A574;            /* Muted gold accent */
    --color-accent-dark: #BF8F5E;
    --color-accent-light: #E6C9A5;
    
    /* Research-specific colors */
    --color-journal: #4A7B9D;
    --color-conference: #7B4A9D;
    --color-book: #9D7B4A;
    --color-thesis: #4A9D7B;
    --color-report: #5D4A8A;
    
    /* Neutral Colors - Professional */
    --color-white: #FFFFFF;
    --color-off-white: #FAFAFA;
    --color-gray-50: #F5F7FA;
    --color-gray-100: #E8ECF1;
    --color-gray-200: #D1D9E3;
    --color-gray-300: #B8C2CC;
    --color-gray-600: #718096;
    --color-gray-800: #2D3748;
    --color-gray-900: #1A202C;
    --color-black: #000000;
    
    /* Typography - Professional Scale */
    --font-heading: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    --font-body: 'Open Sans', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
    
    /* Professional Spacing */
    --spacing-xs: 0.5rem;
    --spacing-sm: 0.875rem;
    --spacing-md: 1.25rem;
    --spacing-lg: 1.75rem;
    --spacing-xl: 2.25rem;
    --spacing-xxl: 3rem;
    
    /* Touch Targets */
    --touch-target: 44px;
    
    /* Subtle Professional Shadows */
    --shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.05);
    --shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.08);
    --shadow-elevated: 0 8px 24px rgba(0, 0, 0, 0.12);
    
    /* Border Radius */
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 14px;
    --radius-full: 999px;
    
    /* Transitions */
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ==========================================================================
   BASE STYLES - CONSISTENT WITH RESEARCH.PHP
   ========================================================================== */
* {
    -webkit-tap-highlight-color: transparent;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    overflow-x: hidden;
}

body {
    min-height: 100vh;
    background-color: var(--color-white);
    font-family: var(--font-body);
    font-weight: 400;
    line-height: 1.6;
    color: var(--color-gray-800);
    width: 100%;
    max-width: 100%;
}

/* Font family inheritance */
h1, h2, h3, h4, h5, h6,
button, .btn,
.section-title,
.detail-title,
.category-title,
.publication-type {
    font-family: var(--font-heading);
}

/* ==========================================================================
   LAYOUT & CONTAINER
   ========================================================================== */
.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

/* ==========================================================================
   HERO HEADER - ENHANCED CONTRAST
   ========================================================================== */
.detail-hero {
    position: relative;
    width: 100%;
    background: linear-gradient(135deg, #4A3A6F, #5D4A8A, #6F5B9E);
    color: var(--color-white);
    padding: var(--spacing-xxl) 0;
    margin: 0;
    border: none;
    overflow: hidden;
}

.detail-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

/* Badge - Better contrast */
.hero-badge {
    display: inline-block;
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 0.6rem 1.75rem;
    border-radius: var(--radius-full);
    font-family: var(--font-heading);
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: var(--spacing-md);
    text-transform: uppercase;
    box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
}

/* Title - Enhanced readability */
.detail-title {
    font-family: var(--font-heading);
    font-size: clamp(1.75rem, 4.5vw, 2.75rem);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: var(--spacing-md);
    color: var(--color-white);
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    letter-spacing: -0.5px;
}

.detail-title::after {
    content: '';
    display: block;
    width: 80px;
    height: 3px;
    background: var(--color-accent);
    margin-top: var(--spacing-sm);
    border-radius: 2px;
}

/* Hero meta - Better contrast */
.hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
    align-items: center;
}

.hero-authors {
    font-size: clamp(1.1rem, 2.5vw, 1.3rem);
    font-weight: 500;
    color: rgba(255, 255, 255, 0.95);
    line-height: 1.4;
    flex: 1;
    min-width: 250px;
}

.hero-authors i {
    color: var(--color-accent);
    margin-right: 8px;
}

.hero-date {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    background: rgba(255, 255, 255, 0.15);
    color: var(--color-white);
    border-radius: var(--radius-full);
    font-size: 0.95rem;
    font-weight: 500;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.hero-date i {
    color: var(--color-accent-light);
}

/* ==========================================================================
   MAIN CONTENT AREA
   ========================================================================== */
.detail-content {
    padding: var(--spacing-xl) 0;
    background: var(--color-white);
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-xl);
    max-width: 1200px;
    margin: 0 auto;
}

@media (min-width: 992px) {
    .detail-grid {
        grid-template-columns: 1fr 350px; /* Increased sidebar width from 300px */
    }
}

/* ==========================================================================
   PUBLICATION METADATA CARD
   ========================================================================== */
.metadata-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    box-shadow: var(--shadow-soft);
    border: 1px solid var(--color-gray-100);
    order: 2;
}

@media (min-width: 992px) {
    .metadata-card {
        order: 1;
    }
}

.metadata-title {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-sm);
    border-bottom: 2px solid var(--color-gray-100);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.metadata-title i {
    color: var(--color-primary);
}

.metadata-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.metadata-item {
    padding: var(--spacing-md) 0;
    border-bottom: 1px solid var(--color-gray-100);
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.metadata-item:last-child {
    border-bottom: none;
}

.metadata-label {
    font-family: var(--font-heading);
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--color-primary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.metadata-label i {
    font-size: 0.85rem;
    color: var(--color-primary-light);
}

.metadata-value {
    font-size: 1rem;
    color: var(--color-gray-800);
    line-height: 1.5;
}

.metadata-value a {
    color: var(--color-secondary);
    text-decoration: none;
    transition: var(--transition-smooth);
}

.metadata-value a:hover {
    color: var(--color-secondary-dark);
    text-decoration: underline;
}

/* Publication type badge */
.publication-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-white);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.publication-type-badge.journal {
    background: var(--color-journal);
}

.publication-type-badge.conference {
    background: var(--color-conference);
}

.publication-type-badge.book {
    background: var(--color-book);
}

.publication-type-badge.thesis {
    background: var(--color-thesis);
}

.publication-type-badge.report {
    background: var(--color-report);
}

/* ==========================================================================
   ABSTRACT SECTION - WIDER CONTAINER
   ========================================================================== */
.abstract-section {
    background: var(--color-off-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    border: 1px solid var(--color-gray-100);
    order: 1;
    /* Full width on mobile, adjusts on desktop */
    width: 100%;
}

@media (min-width: 992px) {
    .abstract-section {
        order: 2;
        /* On desktop, it takes the remaining space after sidebar */
        grid-column: 1 / span 1;
    }
}

.section-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-sm);
    border-bottom: 2px solid var(--color-gray-100);
}

.section-icon {
    width: 40px;
    height: 40px;
    background: var(--color-primary);
    color: var(--color-white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.section-title {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--color-primary);
    margin: 0;
}

.abstract-content {
    font-size: 1.05rem;
    line-height: 1.7;
    color: var(--color-gray-800);
    white-space: pre-line;
    /* Optimal line length for readability */
    max-width: 100%;
}

.abstract-content p {
    margin-bottom: var(--spacing-md);
}

.abstract-content p:last-child {
    margin-bottom: 0;
}

/* ==========================================================================
   KEYWORDS SECTION
   ========================================================================== */
.keywords-section {
    margin-top: var(--spacing-xl);
    order: 3;
    grid-column: 1 / -1;
}

.keywords-container {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-md);
}

.keyword {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--color-primary-very-light);
    color: var(--color-primary);
    border-radius: var(--radius-full);
    font-size: 0.9rem;
    font-weight: 500;
    border: 1px solid var(--color-primary-light);
    transition: var(--transition-smooth);
    text-decoration: none;
}

.keyword:hover {
    background: var(--color-primary);
    color: var(--color-white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-subtle);
}

.keyword i {
    font-size: 0.8rem;
}

/* ==========================================================================
   ACTION BUTTONS
   ========================================================================== */
.actions-section {
    margin-top: var(--spacing-xl);
    order: 4;
    grid-column: 1 / -1;
    padding-top: var(--spacing-xl);
    border-top: 1px solid var(--color-gray-100);
}

.actions-container {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    justify-content: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-sm);
    padding: 0.9rem 1.75rem;
    font-family: var(--font-heading);
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: var(--radius-md);
    transition: var(--transition-smooth);
    border: 2px solid transparent;
    cursor: pointer;
    min-height: var(--touch-target);
    letter-spacing: 0.3px;
    text-align: center;
}

.btn-primary {
    background: var(--color-accent);
    color: var(--color-gray-900);
    border-color: var(--color-accent);
}

.btn-primary:hover,
.btn-primary:focus {
    background: var(--color-accent-dark);
    color: var(--color-gray-900);
    transform: translateY(-2px);
    box-shadow: var(--shadow-soft);
}

.btn-secondary {
    background: var(--color-primary);
    color: var(--color-white);
    border-color: var(--color-primary);
}

.btn-secondary:hover,
.btn-secondary:focus {
    background: var(--color-primary-dark);
    color: var(--color-white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-soft);
}

.btn-outline {
    background: transparent;
    color: var(--color-primary);
    border-color: var(--color-primary);
}

.btn-outline:hover,
.btn-outline:focus {
    background: var(--color-primary);
    color: var(--color-white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-soft);
}

/* ==========================================================================
   RELATED PUBLICATIONS
   ========================================================================== */
.related-section {
    margin-top: var(--spacing-xl);
    padding: var(--spacing-xl) 0;
    background: var(--color-off-white);
    border-top: 1px solid var(--color-gray-100);
    order: 5;
    grid-column: 1 / -1;
}

.related-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

@media (min-width: 768px) {
    .related-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 992px) {
    .related-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.related-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    border: 1px solid var(--color-gray-100);
    transition: var(--transition-smooth);
    text-decoration: none;
    color: inherit;
    display: block;
}

.related-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-light);
}

.related-card-title {
    font-family: var(--font-heading);
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    line-height: 1.3;
}

.related-card-authors {
    font-size: 0.9rem;
    color: var(--color-gray-600);
    margin-bottom: var(--spacing-sm);
    line-height: 1.4;
}

.related-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    color: var(--color-gray-500);
}

.related-card-type {
    padding: 0.2rem 0.6rem;
    background: var(--color-primary-very-light);
    color: var(--color-primary);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 500;
}

/* ==========================================================================
   METRICS & STATISTICS
   ========================================================================== */
.metrics-section {
    margin-top: var(--spacing-xl);
    order: 6;
    grid-column: 1 / -1;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

@media (min-width: 768px) {
    .metrics-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.metric-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    text-align: center;
    border: 1px solid var(--color-gray-100);
    transition: var(--transition-smooth);
}

.metric-card:hover {
    border-color: var(--color-primary-light);
    box-shadow: var(--shadow-subtle);
}

.metric-icon {
    font-size: 1.5rem;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
}

.metric-value {
    font-family: var(--font-heading);
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--color-primary);
    line-height: 1;
}

.metric-label {
    font-size: 0.9rem;
    color: var(--color-gray-600);
    margin-top: 0.25rem;
}

/* ==========================================================================
   BREADCRUMB NAVIGATION
   ========================================================================== */
.breadcrumb {
    padding: var(--spacing-md) 0;
    background: var(--color-off-white);
    border-bottom: 1px solid var(--color-gray-100);
}

.breadcrumb-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

.breadcrumb-list {
    display: flex;
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 0;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: var(--color-gray-600);
}

.breadcrumb-item:not(:last-child)::after {
    content: '›';
    color: var(--color-gray-400);
}

.breadcrumb-link {
    color: var(--color-primary);
    text-decoration: none;
    transition: var(--transition-smooth);
}

.breadcrumb-link:hover {
    color: var(--color-primary-dark);
    text-decoration: underline;
}

.breadcrumb-current {
    color: var(--color-gray-800);
    font-weight: 500;
}

/* ==========================================================================
   RESPONSIVE ADJUSTMENTS
   ========================================================================== */
@media (max-width: 768px) {
    .detail-hero {
        padding: var(--spacing-xl) 0;
    }
    
    .hero-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
    }
    
    .hero-date {
        align-self: flex-start;
    }
    
    .metadata-card,
    .abstract-section {
        padding: var(--spacing-lg);
    }
    
    .actions-container {
        flex-direction: column;
    }
    
    .actions-container .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .detail-title {
        font-size: 1.5rem;
    }
    
    .hero-authors {
        font-size: 1rem;
    }
    
    .metadata-card,
    .abstract-section {
        padding: var(--spacing-md);
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
}

/* ==========================================================================
   LARGER DESKTOP OPTIMIZATION
   ========================================================================== */
@media (min-width: 1200px) {
    .detail-grid {
        max-width: 1400px; /* Wider container on large screens */
    }
    
    .abstract-content {
        font-size: 1.1rem; /* Slightly larger text on wide screens */
        line-height: 1.8; /* More spacing for readability */
    }
}

/* ==========================================================================
   PRINT STYLES
   ========================================================================== */
@media print {
    .abstract-section {
        width: 100% !important;
        page-break-inside: avoid;
        border: 1px solid #ddd;
        background: white !important;
    }
    
    .abstract-content {
        font-size: 12pt;
        line-height: 1.6;
    }
}
</style>
</head>
<body>

<!-- Breadcrumb Navigation -->
<nav class="breadcrumb" aria-label="Breadcrumb">
    <div class="breadcrumb-container">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item">
                <a href="/" class="breadcrumb-link">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="/research" class="breadcrumb-link">
                    <i class="fas fa-book-open"></i> Research
                </a>
            </li>
            <li class="breadcrumb-item">
                <span class="breadcrumb-current">
                    <i class="fas fa-file-alt"></i> Publication Detail
                </span>
            </li>
        </ol>
    </div>
</nav>

<!-- Hero Header with Enhanced Contrast -->
<header class="detail-hero">
    <div class="hero-content">
        <span class="hero-badge">Research Publication</span>
        
        <h1 class="detail-title">
            <?php echo e($publication['title'] ?? 'Untitled Publication'); ?>
        </h1>
        
        <div class="hero-meta">
            <p class="hero-authors">
                <i class="fas fa-users"></i>
                <?php echo e($publication['authors'] ?? 'N/A'); ?>
            </p>
            
            <div class="hero-date">
                <i class="far fa-calendar-alt"></i>
                <?php echo e($pubDate ?? 'N/A'); ?>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="detail-content" role="main">
    <div class="container">
        <div class="detail-grid">
            
            <!-- Metadata Card -->
            <aside class="metadata-card">
                <h2 class="metadata-title">
                    <i class="fas fa-info-circle"></i> Publication Details
                </h2>
                
                <ul class="metadata-list">
                    <li class="metadata-item">
                        <span class="metadata-label">
                            <i class="fas fa-tag"></i> Category
                        </span>
                        <span class="metadata-value"><?php echo e($categoryName ?? 'Unknown'); ?></span>
                    </li>
                    
                    <li class="metadata-item">
                        <span class="metadata-label">
                            <i class="fas fa-file-alt"></i> Type
                        </span>
                        <span class="metadata-value">
                            <span class="publication-type-badge <?php echo e($publication['publication_type'] ?? ''); ?>">
                                <?php echo e($pubTypeLabel ?? 'Unknown'); ?>
                            </span>
                        </span>
                    </li>
                    
                    <?php if (!empty($publication['journal_name'])): ?>
                    <li class="metadata-item">
                        <span class="metadata-label">
                            <i class="fas fa-book"></i> Journal
                        </span>
                        <span class="metadata-value"><?php echo e($publication['journal_name']); ?>
                            <?php if (!empty($publication['volume'])): ?>
                                , Vol. <?php echo e($publication['volume']); ?>
                            <?php endif; ?>
                            <?php if (!empty($publication['issue'])): ?>
                                , Iss. <?php echo e($publication['issue']); ?>
                            <?php endif; ?>
                            <?php if (!empty($publication['pages'])): ?>
                                , pp. <?php echo e($publication['pages']); ?>
                            <?php endif; ?>
                        </span>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (!empty($publication['publisher'])): ?>
                    <li class="metadata-item">
                        <span class="metadata-label">
                            <i class="fas fa-building"></i> Publisher
                        </span>
                        <span class="metadata-value"><?php echo e($publication['publisher']); ?></span>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (!empty($publication['doi'])): ?>
                    <li class="metadata-item">
                        <span class="metadata-label">
                            <i class="fas fa-fingerprint"></i> DOI
                        </span>
                        <span class="metadata-value">
                            <a href="https://doi.org/<?php echo e($publication['doi']); ?>" target="_blank" rel="noopener">
                                <?php echo e($publication['doi']); ?>
                            </a>
                        </span>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (!empty($publication['url'])): ?>
                    <li class="metadata-item">
                        <span class="metadata-label">
                            <i class="fas fa-link"></i> URL
                        </span>
                        <span class="metadata-value">
                            <a href="<?php echo e($publication['url']); ?>" target="_blank" rel="noopener">
                                <?php echo e(str_replace(['http://', 'https://'], '', $publication['url'])); ?>
                            </a>
                        </span>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (!empty($publication['citations']) && $publication['citations'] > 0): ?>
                    <li class="metadata-item">
                        <span class="metadata-label">
                            <i class="fas fa-quote-right"></i> Citations
                        </span>
                        <span class="metadata-value"><?php echo e($publication['citations']); ?></span>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (!empty($publication['impact_factor'])): ?>
                    <li class="metadata-item">
                        <span class="metadata-label">
                            <i class="fas fa-chart-line"></i> Impact Factor
                        </span>
                        <span class="metadata-value"><?php echo e($publication['impact_factor']); ?></span>
                    </li>
                    <?php endif; ?>
                </ul>
            </aside>
            
            <!-- Abstract Section - Now Wider -->
            <article class="abstract-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h2 class="section-title">Abstract</h2>
                </div>
                
                <div class="abstract-content">
                    <?php echo nl2br(e($publication['abstract'] ?? 'No abstract available.')); ?>
                </div>
            </article>
            
            <!-- Keywords Section -->
            <?php if (!empty($keywordsArray)): ?>
            <section class="keywords-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h2 class="section-title">Keywords</h2>
                </div>
                
                <div class="keywords-container">
                    <?php foreach ($keywordsArray as $keyword): ?>
                        <a href="/research?search=<?php echo urlencode(trim($keyword)); ?>" class="keyword">
                            <i class="fas fa-hashtag"></i>
                            <?php echo e(trim($keyword)); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            <section class="actions-section">
                <div class="actions-container">
                    <a href="/research" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Research
                    </a>
                    
                    <?php if (!empty($publication['file_path'])): ?>
                    <a href="/research/<?php echo e($publication['id'] ?? ''); ?>/download" class="btn btn-primary">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($publication['url'])): ?>
                    <a href="<?php echo e($publication['url']); ?>" target="_blank" rel="noopener" class="btn btn-outline">
                        <i class="fas fa-external-link-alt"></i> View Online
                    </a>
                    <?php endif; ?>
                </div>
            </section>
            
            <!-- Metrics -->
            <section class="metrics-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h2 class="section-title">Publication Metrics</h2>
                </div>
                
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="metric-value">
                            <?php echo e($publication['views_count'] ?? 0); ?>
                        </div>
                        <div class="metric-label">Views</div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="metric-value">
                            <?php echo e($publication['downloads_count'] ?? 0); ?>
                        </div>
                        <div class="metric-label">Downloads</div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-quote-right"></i>
                        </div>
                        <div class="metric-value">
                            <?php echo e($publication['citations'] ?? 0); ?>
                        </div>
                        <div class="metric-label">Citations</div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="metric-value">
                            <?php echo e($publication['is_featured'] ? 'Yes' : 'No'); ?>
                        </div>
                        <div class="metric-label">Featured</div>
                    </div>
                </div>
            </section>
            
            <!-- Related Publications -->
            <?php if (!empty($related) && count($related) > 0): ?>
            <section class="related-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <h2 class="section-title">Related Publications</h2>
                </div>
                
                <div class="related-grid">
                    <?php foreach (array_slice($related, 0, 6) as $relatedPub): ?>
                        <a href="/research/<?php echo e($relatedPub['id']); ?>" class="related-card">
                            <h3 class="related-card-title">
                                <?php echo e(substr($relatedPub['title'], 0, 80)); ?><?php echo (strlen($relatedPub['title']) > 80) ? '...' : ''; ?>
                            </h3>
                            <p class="related-card-authors">
                                <?php echo e(substr($relatedPub['authors'], 0, 60)); ?><?php echo (strlen($relatedPub['authors']) > 60) ? '...' : ''; ?>
                            </p>
                            <div class="related-card-meta">
                                <span><?php echo date('M Y', strtotime($relatedPub['publication_date'])); ?></span>
                                <span class="related-card-type">
                                    <?php echo ucfirst($relatedPub['publication_type']); ?>
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
        </div>
    </div>
</main>

</body>
</html>