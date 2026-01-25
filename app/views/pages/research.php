<?php
/**
 * Research Publications Page - Professional Redesign (Mature Light Purple Theme)
 * Mobile-Optimized Version - FULL WIDTH
 * Complete Redesign with Professional Sections
 * Updated: Fixed image path, added transparent background, reduced font size, removed animations
 * FIXED: Enhanced search display with better results header
 * ENHANCED: Search form scrolls to publications section
 * FIXED: No gap between header and research body + flash messages display
 * 
 * @package FCTCNS
 * @version 4.6
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$featured = $featured ?? [];
$publications = $publications ?? [];
$categories = $categories ?? [];
$searchTerm = $searchTerm ?? '';
$currentCategory = $currentCategory ?? '';
$totalPublications = count($publications);

// Flash messages
$flash_success = $flash_success ?? null;
$flash_error = $flash_error ?? null;
$flash_errors = $flash_errors ?? null;

// FIXED: Add forward slash between domain and path
$heroImagePath = rtrim($baseUrl, '/') . '/assets/images/research/research-hero.jpg';

// Check for scroll parameter
$scrollToPublications = isset($_GET['scroll']) && $_GET['scroll'] === 'publications';

// Preload the hero image
if (file_exists($_SERVER['DOCUMENT_ROOT'] . $heroImagePath)) {
    echo '<link rel="preload" href="' . $heroImagePath . '" as="image">';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="<?php echo e($page_description ?? 'FCT College of Nursing Sciences - Research Publications & Academic Research'); ?>">
    <title><?php echo e($pageTitle ?? 'Research Publications - FCT College of Nursing Sciences'); ?></title>
    
    <!-- Professional Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ==========================================================================
   CRITICAL: REMOVE ALL GAPS BETWEEN HEADER AND RESEARCH BODY
   ========================================================================== */
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
    min-height: 100vh;
    background-color: #FFFFFF;
    font-family: 'Open Sans', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
    font-weight: 400;
    line-height: 1.6;
    color: #2D3748;
    width: 100%;
    max-width: 100%;
}

/* Remove ALL spacing from body and header */
body > header,
body > .header,
body > nav,
body > .navbar {
    margin: 0 !important;
    padding: 0 !important;
}

/* Remove spacing from main wrapper */
#main-content,
.research-content,
main {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
}

/* Make sure hero section starts at top */
.research-hero {
    margin-top: 0 !important;
    padding-top: 0 !important;
    border-top: none !important;
}

/* ==========================================================================
   FLASH MESSAGES STYLES - ADDED
   ========================================================================== */
.flash-messages-container {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
    max-width: 400px;
    width: 90%;
}

.flash-message {
    padding: 15px 20px;
    margin-bottom: 10px;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideInRight 0.3s ease-out;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.flash-message.success {
    background: linear-gradient(135deg, rgba(76, 175, 80, 0.95), rgba(56, 142, 60, 0.95));
    color: white;
}

.flash-message.error {
    background: linear-gradient(135deg, rgba(244, 67, 54, 0.95), rgba(198, 40, 40, 0.95));
    color: white;
}

.flash-message.warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.95), rgba(245, 124, 0, 0.95));
    color: white;
}

.flash-message .icon {
    font-size: 1.2rem;
    flex-shrink: 0;
}

.flash-message .content {
    flex: 1;
    font-size: 0.95rem;
    line-height: 1.4;
}

.flash-message .close-btn {
    background: none;
    border: none;
    color: inherit;
    font-size: 1rem;
    cursor: pointer;
    padding: 4px;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.flash-message .close-btn:hover {
    opacity: 1;
    background: rgba(255, 255, 255, 0.1);
}

/* Mobile flash messages */
@media (max-width: 768px) {
    .flash-messages-container {
        top: 70px;
        right: 10px;
        left: 10px;
        max-width: none;
        width: auto;
    }
}

/* ==========================================================================
   GLOBAL VARIABLES - PROFESSIONAL MATURE DESIGN
   ========================================================================== */
:root {
    /* Professional Color Palette - Muted Elegance */
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
    
    /* Enhanced Neutral Colors - Professional */
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
}

/* ==========================================================================
   MAIN CONTENT STRUCTURE - NO GAPS
   ========================================================================== */
.research-content {
    display: block;
    width: 100%;
    margin: 0;
    padding: 0;
}

.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

.section {
    padding: var(--spacing-xl) 0;
    width: 100%;
}

.section-alt {
    background: var(--color-off-white);
    border-top: 1px solid var(--color-gray-100);
    border-bottom: 1px solid var(--color-gray-100);
}

.section-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 var(--spacing-md);
}

.section-title {
    font-family: var(--font-heading);
    font-size: clamp(1.5rem, 3.5vw, 2rem);
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 2px;
    background: var(--color-accent);
    border-radius: 1px;
}

.section-subtitle {
    font-size: clamp(0.95rem, 2.5vw, 1.1rem);
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-top: var(--spacing-lg);
    font-weight: 400;
    padding: 0 var(--spacing-sm);
    text-align: center;
    font-family: var(--font-body);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

/* ==========================================================================
   HERO SECTION - NO TOP GAP
   ========================================================================== */
.research-hero {
    position: relative;
    width: 100%;
    background: #2D3748 url('<?php echo $heroImagePath; ?>') no-repeat center center;
    background-size: cover;
    color: var(--color-white);
    padding: var(--spacing-xxl) 0;
    margin: 0;
    border: none;
    overflow: hidden;
    min-height: 500px;
    display: flex;
    align-items: center;
}

.hero-container {
    position: relative;
    z-index: 3;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
    width: 100%;
}

.hero-content {
    text-align: center;
    max-width: 900px;
    margin: 0 auto;
    padding: var(--spacing-xl) 0;
}

/* Transparent background wrapper for better readability */
.hero-text-wrapper {
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.hero-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--color-accent), var(--color-accent-dark));
    color: var(--color-gray-900);
    padding: 0.6rem 1.75rem;
    border-radius: var(--radius-full);
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: var(--spacing-md);
    text-transform: uppercase;
    font-family: var(--font-heading);
    box-shadow: var(--shadow-soft);
    position: relative;
    z-index: 2;
}

/* REDUCED FONT SIZE: From 3.75rem to 2.75rem max */
.hero-title {
    font-family: var(--font-heading);
    font-size: clamp(2rem, 5vw, 2.75rem); /* Reduced from 3.75rem */
    font-weight: 700;
    line-height: 1.2; /* Adjusted for better readability */
    margin-bottom: var(--spacing-md);
    color: var(--color-white);
    position: relative;
    z-index: 2;
    letter-spacing: -0.25px;
}

.hero-description {
    font-size: clamp(1rem, 2.5vw, 1.25rem); /* Reduced from 1.65rem */
    font-weight: 400;
    margin-bottom: var(--spacing-xl);
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.95);
    font-family: var(--font-body);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    position: relative;
    z-index: 2;
    padding: 0 var(--spacing-md);
}

.hero-cta {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    justify-content: center;
    width: 100%;
    position: relative;
    z-index: 2;
    margin-top: var(--spacing-xl);
    padding-top: var(--spacing-md);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* ==========================================================================
   BUTTONS - NO ANIMATIONS
   ========================================================================== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.85rem 1.75rem;
    font-family: var(--font-heading);
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: var(--radius-md);
    border: 2px solid transparent;
    cursor: pointer;
    min-height: 44px;
    letter-spacing: 0.3px;
    white-space: nowrap;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, var(--color-accent), var(--color-accent-dark));
    color: var(--color-gray-900);
    border-color: var(--color-accent);
}

.btn-primary:hover,
.btn-primary:focus {
    background: linear-gradient(135deg, var(--color-accent-dark), var(--color-accent));
    color: var(--color-gray-900);
    box-shadow: var(--shadow-soft);
}

.btn-secondary {
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    color: var(--color-white);
    border-color: var(--color-primary);
}

.btn-secondary:hover,
.btn-secondary:focus {
    background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary));
    color: var(--color-white);
    box-shadow: var(--shadow-soft);
}

.btn-outline-light {
    background: transparent;
    color: var(--color-white);
    border-color: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.btn-outline-light:hover,
.btn-outline-light:focus {
    background: rgba(255, 255, 255, 0.15);
    color: var(--color-white);
    box-shadow: var(--shadow-soft);
    border-color: rgba(255, 255, 255, 0.6);
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    min-height: 36px;
}

.btn-outline-primary {
    background: transparent;
    color: var(--color-primary);
    border-color: var(--color-primary);
}

.btn-outline-primary:hover,
.btn-outline-primary:focus {
    background: var(--color-primary);
    color: var(--color-white);
}

/* ==========================================================================
   SEARCH SECTION
   ========================================================================== */
.search-section {
    background: var(--color-white);
    padding: var(--spacing-lg) 0;
    box-shadow: var(--shadow-subtle);
    border-bottom: 1px solid var(--color-gray-100);
}

.search-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

/* Enhanced search results header */
.search-results-header {
    background: var(--color-primary-very-light);
    padding: 1.5rem;
    border-radius: var(--radius-md);
    margin-bottom: 1.5rem;
    border: 1px solid var(--color-gray-200);
}

.search-results-header h3 {
    margin: 0 0 0.5rem 0;
    color: var(--color-primary);
    font-size: 1.2rem;
}

.search-form {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-sm);
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--radius-md);
    overflow: hidden;
}

.search-input-group:focus-within {
    border-color: var(--color-primary-light);
    box-shadow: 0 0 0 3px var(--color-primary-transparent);
}

.search-icon {
    padding: 0 var(--spacing-md);
    color: var(--color-gray-600);
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-input {
    flex: 1;
    border: none;
    padding: 0.9rem 0;
    font-size: 1rem;
    font-family: var(--font-body);
    color: var(--color-gray-900);
    background: transparent;
    outline: none;
    min-width: 0;
}

.search-input::placeholder {
    color: var(--color-gray-600);
    font-weight: 400;
}

.search-filters {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: var(--spacing-sm);
    align-items: center;
}

.filter-select {
    padding: 0.85rem 1rem;
    border: 1px solid var(--color-gray-200);
    border-radius: var(--radius-md);
    font-size: 0.95rem;
    font-family: var(--font-body);
    color: var(--color-gray-900);
    background: var(--color-white);
    cursor: pointer;
    width: 100%;
}

.filter-select:focus {
    outline: none;
    border-color: var(--color-primary-light);
    box-shadow: 0 0 0 3px var(--color-primary-transparent);
}

.search-button {
    width: 100%;
    justify-content: center;
}

.search-button .btn {
    width: 100%;
}

.search-clear {
    background: none;
    border: none;
    color: var(--color-gray-600);
    padding: 0 1rem;
    cursor: pointer;
    font-size: 1rem;
}

.clear-button {
    display: flex;
    align-items: center;
}

/* ==========================================================================
   STATISTICS SECTION
   ========================================================================== */
.research-stats-section {
    background: var(--color-off-white);
    padding: var(--spacing-xl) 0;
    border-top: 1px solid var(--color-gray-100);
}

.research-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
    text-align: center;
    max-width: 1200px;
    margin: 0 auto;
}

.research-stat-item {
    padding: var(--spacing-lg);
    background: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-subtle);
    border: 1px solid var(--color-gray-100);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.research-stat-icon {
    font-size: 1.75rem;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: var(--color-white);
    border-radius: 50%;
    box-shadow: var(--shadow-subtle);
}

.research-stat-number {
    font-family: var(--font-heading);
    font-size: clamp(1.75rem, 4vw, 2.25rem);
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--color-primary);
    line-height: 1;
}

.research-stat-label {
    font-size: 0.9rem;
    color: var(--color-gray-800);
    font-weight: 500;
    line-height: 1.4;
    letter-spacing: 0.3px;
}

/* ==========================================================================
   FEATURED PUBLICATIONS SECTION
   ========================================================================== */
.featured-publications-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0;
}

.featured-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-lg);
    max-width: 1200px;
    margin: 0 auto;
}

.featured-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-soft);
    border: 1px solid var(--color-gray-100);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.featured-header {
    padding: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    background: linear-gradient(135deg, var(--color-primary-very-light), var(--color-white));
    border-bottom: 1px solid var(--color-gray-100);
}

.featured-badge {
    display: inline-block;
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 0.4rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: var(--spacing-sm);
    text-transform: uppercase;
}

.featured-date {
    color: var(--color-gray-600);
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.featured-date i {
    font-size: 0.8rem;
}

.featured-content {
    padding: var(--spacing-lg);
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.featured-title {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
}

.featured-title a {
    color: inherit;
    text-decoration: none;
}

.featured-title a:hover {
    color: var(--color-primary-dark);
    text-decoration: underline;
}

.featured-authors {
    color: var(--color-gray-800);
    font-size: 0.95rem;
    margin-bottom: var(--spacing-md);
    line-height: 1.5;
    font-family: var(--font-body);
}

.featured-abstract {
    color: var(--color-gray-700);
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    flex-grow: 1;
    font-family: var(--font-body);
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.featured-footer {
    padding: var(--spacing-md) var(--spacing-lg);
    background: var(--color-off-white);
    border-top: 1px solid var(--color-gray-100);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
}

.featured-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.featured-tag {
    padding: 0.25rem 0.75rem;
    background: var(--color-white);
    color: var(--color-primary);
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid var(--color-gray-200);
}

.featured-type {
    background: var(--color-journal);
    color: var(--color-white);
    border: none;
}

.featured-type.conference {
    background: var(--color-conference);
}

.featured-type.book {
    background: var(--color-book);
}

.featured-type.thesis {
    background: var(--color-thesis);
}

.featured-metrics {
    display: flex;
    gap: var(--spacing-md);
    color: var(--color-gray-600);
    font-size: 0.85rem;
}

.featured-metrics span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.featured-metrics i {
    font-size: 0.8rem;
}

/* ==========================================================================
   RESEARCH AREAS SECTION
   ========================================================================== */
.research-areas-section {
    background: var(--color-off-white);
    padding: var(--spacing-xl) 0;
    border-top: 1px solid var(--color-gray-100);
}

.categories-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-lg);
    max-width: 1200px;
    margin: 0 auto;
}

.category-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-subtle);
    border: 1px solid var(--color-gray-100);
    height: 100%;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
}

.category-icon {
    padding: var(--spacing-xl) var(--spacing-lg) var(--spacing-md);
    background: linear-gradient(135deg, var(--color-primary-very-light), var(--color-white));
    text-align: center;
}

.category-icon i {
    font-size: 2.5rem;
    color: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: var(--color-white);
    border-radius: 50%;
    box-shadow: var(--shadow-subtle);
}

.category-content {
    padding: var(--spacing-lg);
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.category-title {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
}

.category-description {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    font-size: 0.95rem;
    font-weight: 400;
    flex-grow: 1;
    font-family: var(--font-body);
}

.category-count {
    display: inline-block;
    margin-top: auto;
    padding: 0.5rem 1rem;
    background: var(--color-primary-very-light);
    color: var(--color-primary);
    border-radius: var(--radius-full);
    font-size: 0.9rem;
    font-weight: 600;
    border: 1px solid var(--color-gray-100);
}

/* ==========================================================================
   ALL PUBLICATIONS SECTION
   ========================================================================== */
.all-publications-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0;
}

.publications-header {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-xl);
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 var(--spacing-md);
}

.publications-title {
    font-family: var(--font-heading);
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--color-primary);
    margin: 0;
}

.publications-count {
    color: var(--color-gray-600);
    font-size: 1rem;
    font-weight: 400;
    font-family: var(--font-body);
}

.no-publications {
    max-width: 800px;
    margin: 0 auto;
    padding: var(--spacing-xl) var(--spacing-md);
    text-align: center;
}

.no-publications-icon {
    font-size: 3rem;
    color: var(--color-gray-300);
    margin-bottom: var(--spacing-md);
}

.no-publications-title {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    color: var(--color-gray-700);
    margin-bottom: var(--spacing-sm);
}

.no-publications-message {
    color: var(--color-gray-600);
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
    font-family: var(--font-body);
}

/* Publication List */
.publications-list {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

.publication-item {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-md);
    padding: var(--spacing-lg);
    border: 1px solid var(--color-gray-100);
}

.publication-main {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.publication-header {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.publication-meta {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
}

.publication-type {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    background: var(--color-primary-very-light);
    color: var(--color-primary);
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid var(--color-gray-200);
}

.publication-date {
    color: var(--color-gray-600);
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.publication-title {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--color-primary);
    line-height: 1.3;
    margin: 0;
}

.publication-title a {
    color: inherit;
    text-decoration: none;
}

.publication-title a:hover {
    color: var(--color-primary-dark);
    text-decoration: underline;
}

.publication-featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    background: var(--color-accent);
    color: var(--color-gray-900);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
    vertical-align: middle;
}

.publication-authors {
    color: var(--color-gray-800);
    font-size: 0.95rem;
    line-height: 1.5;
    font-family: var(--font-body);
}

.publication-authors i {
    color: var(--color-gray-600);
    font-size: 0.9rem;
    margin-right: 0.25rem;
}

.publication-abstract {
    color: var(--color-gray-700);
    font-size: 0.95rem;
    line-height: 1.6;
    margin: var(--spacing-sm) 0;
    font-family: var(--font-body);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.publication-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--color-gray-100);
}

.publication-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.publication-tag {
    padding: 0.25rem 0.75rem;
    background: var(--color-off-white);
    color: var(--color-gray-700);
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid var(--color-gray-200);
}

.publication-category {
    background: var(--color-primary-very-light);
    color: var(--color-primary);
    border-color: var(--color-primary-light);
}

.publication-doi {
    background: var(--color-gray-50);
    color: var(--color-gray-700);
    font-family: 'Courier New', monospace;
    font-size: 0.75rem;
}

.publication-actions {
    display: flex;
    gap: var(--spacing-sm);
    align-items: center;
}

.publication-metrics {
    display: flex;
    gap: var(--spacing-md);
    color: var(--color-gray-600);
    font-size: 0.85rem;
}

.publication-metrics span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.publication-metrics i {
    font-size: 0.8rem;
}

/* ==========================================================================
   PAGINATION
   ========================================================================== */
.pagination-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0;
    border-top: 1px solid var(--color-gray-100);
}

.pagination-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: var(--spacing-xs);
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-item {
    margin: 0;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 var(--spacing-sm);
    border: 1px solid var(--color-gray-200);
    background: var(--color-white);
    color: var(--color-gray-700);
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    border-radius: var(--radius-md);
    font-family: var(--font-heading);
}

.page-link:hover {
    background: var(--color-primary-very-light);
    color: var(--color-primary);
    border-color: var(--color-primary-light);
}

.page-item.active .page-link {
    background: var(--color-primary);
    color: var(--color-white);
    border-color: var(--color-primary);
}

.page-item.disabled .page-link {
    background: var(--color-gray-100);
    color: var(--color-gray-400);
    border-color: var(--color-gray-200);
    cursor: not-allowed;
}

/* ==========================================================================
   RESPONSIVE DESIGN FOR ALL SCREEN SIZES
   ========================================================================== */

/* Tablet and larger (768px+) */
@media (min-width: 768px) {
    :root {
        --spacing-sm: 1rem;
        --spacing-md: 1.5rem;
        --spacing-lg: 2rem;
        --spacing-xl: 2.5rem;
        --spacing-xxl: 3.5rem;
    }
    
    .container {
        padding: 0 var(--spacing-lg);
    }
    
    .research-hero {
        min-height: 600px;
    }
    
    .hero-text-wrapper {
        padding: var(--spacing-xl);
        margin: 0 auto;
        max-width: 800px;
    }
    
    .search-form {
        grid-template-columns: 1fr auto auto;
        gap: var(--spacing-md);
    }
    
    .search-filters {
        grid-template-columns: 1fr auto auto;
    }
    
    .research-stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-lg);
    }
    
    .research-stat-item {
        padding: var(--spacing-xl);
    }
    
    .featured-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-xl);
    }
    
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-xl);
    }
    
    .publication-main {
        flex-direction: row;
        align-items: flex-start;
    }
    
    .publication-content {
        flex: 1;
        min-width: 0;
    }
    
    .publication-actions {
        flex-shrink: 0;
    }
}

/* Desktop (1024px+) */
@media (min-width: 1024px) {
    .hero-content {
        text-align: left;
        max-width: 1200px;
        padding: var(--spacing-xxl) 0;
    }
    
    .hero-text-wrapper {
        text-align: left;
        max-width: 700px;
        margin: 0;
    }
    
    .hero-title {
        text-align: left;
        font-size: 2.5rem;
    }
    
    .hero-description {
        text-align: left;
        margin-left: 0;
        margin-right: 0;
        font-size: 1.375rem;
        max-width: 650px;
    }
    
    .hero-cta {
        justify-content: flex-start;
    }
    
    .featured-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .categories-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .publications-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    
    .research-hero {
        min-height: 700px;
    }
}

/* Mobile (767px and below) */
@media (max-width: 767px) {
    .research-hero {
        padding: var(--spacing-xl) 0;
        min-height: 450px;
    }
    
    .hero-text-wrapper {
        padding: var(--spacing-lg);
        margin: 0 var(--spacing-sm);
    }
    
    .hero-title {
        font-size: 1.75rem;
        line-height: 1.2;
    }
    
    .hero-description {
        font-size: 1.125rem;
        line-height: 1.4;
    }
    
    .hero-cta {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-cta .btn {
        width: 100%;
        max-width: 280px;
    }
    
    .section {
        padding: var(--spacing-lg) 0;
    }
    
    .publication-item {
        padding: var(--spacing-md);
    }
    
    .publication-footer {
        flex-direction: column;
        align-items: stretch;
        gap: var(--spacing-sm);
    }
    
    .publication-actions {
        justify-content: space-between;
        width: 100%;
    }
    
    .pagination {
        flex-wrap: wrap;
    }
    
    .search-results-header > div {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .search-results-header > div > div:last-child {
        margin-left: 0;
        width: 100%;
        justify-content: space-between;
    }
}

/* Small mobile (480px and below) */
@media (max-width: 480px) {
    .research-hero {
        padding: var(--spacing-lg) 0;
        min-height: 400px;
    }
    
    .hero-text-wrapper {
        padding: var(--spacing-md);
    }
    
    .hero-badge {
        padding: 0.4rem 1.25rem;
        font-size: 0.75rem;
        margin-bottom: var(--spacing-md);
    }
    
    .hero-title {
        font-size: 1.5rem;
        line-height: 1.2;
    }
    
    .hero-description {
        font-size: 1rem;
        line-height: 1.4;
        padding: 0;
    }
    
    .search-filters {
        grid-template-columns: 1fr;
    }
    
    .featured-header,
    .featured-content,
    .featured-footer {
        padding: var(--spacing-md);
    }
    
    .featured-title {
        font-size: 1.2rem;
    }
    
    .publication-title {
        font-size: 1.2rem;
    }
    
    .publication-meta {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .page-link {
        min-width: 36px;
        height: 36px;
        font-size: 0.85rem;
        padding: 0 0.5rem;
    }
}

/* Large desktop (1400px+) */
@media (min-width: 1400px) {
    .container {
        max-width: 1300px;
    }
    
    .hero-title {
        font-size: 2.75rem;
    }
    
    .hero-description {
        font-size: 1.5rem;
    }
}

/* ==========================================================================
   ACCESSIBILITY & UTILITY CLASSES
   ========================================================================== */
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
    outline: 3px solid var(--color-accent);
    outline-offset: 3px;
    border-radius: var(--radius-sm);
}

mark {
    background: var(--color-accent-light);
    padding: 0 0.1em;
    border-radius: 2px;
}

/* Print styles */
@media print {
    .research-hero,
    .search-section,
    .btn {
        display: none !important;
    }
    
    .section {
        page-break-inside: avoid;
    }
    
    .publication-item {
        border: 1px solid #000;
        margin-bottom: 1rem;
    }
}

</style>
</head>
<body>

<!-- Flash Messages Container - ADDED -->
<div class="flash-messages-container">
    <?php if (!empty($flash_success)): ?>
    <div class="flash-message success">
        <div class="icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="content">
            <?php echo e($flash_success); ?>
        </div>
        <button class="close-btn" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($flash_error)): ?>
    <div class="flash-message error">
        <div class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="content">
            <?php echo e($flash_error); ?>
        </div>
        <button class="close-btn" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($flash_errors) && is_array($flash_errors)): ?>
        <?php foreach ($flash_errors as $error): ?>
        <div class="flash-message error">
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="content">
                <?php echo e($error); ?>
            </div>
            <button class="close-btn" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Research Content -->
<main id="main-content" class="research-content" role="main">
    
    <!-- ========== HERO SECTION WITH IMAGE ========== -->
    <section class="research-hero" id="researchHero" aria-label="Research publications hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-text-wrapper">
                    <span class="hero-badge">Academic Research</span>
                    <h1 class="hero-title">Research Publications</h1>
                    <p class="hero-description">
                        Explore cutting-edge research from FCT CNS faculty and students. Our publications span various domains of nursing science, healthcare innovation, and clinical practice.
                    </p>
                    <div class="hero-cta">
                        <a href="#publications" class="btn btn-primary">
                            <i class="fas fa-search" aria-hidden="true"></i> Browse Publications
                        </a>
                        <a href="#categories" class="btn btn-outline-light">
                            <i class="fas fa-folder" aria-hidden="true"></i> Research Areas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== SEARCH SECTION ========== -->
    <section class="search-section" aria-label="Search publications">
        <div class="search-container">
            <?php if ($searchTerm || $currentCategory): ?>
            <div class="search-results-header" style="background: var(--color-primary-very-light); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid var(--color-gray-200);">
                <h3 style="margin: 0 0 0.5rem 0; color: var(--color-primary); font-size: 1.2rem;">
                    <i class="fas fa-search" style="margin-right: 0.5rem;"></i>Search Results
                </h3>
                
                <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
                    <?php if ($searchTerm): ?>
                    <div style="background: var(--color-white); padding: 0.5rem 1rem; border-radius: var(--radius-full); border: 1px solid var(--color-gray-300); display: flex; align-items: center;">
                        <strong style="margin-right: 0.5rem; color: var(--color-gray-700);">Keywords:</strong>
                        <span style="color: var(--color-primary);">"<?php echo e($searchTerm); ?>"</span>
                        <a href="?<?php echo $currentCategory ? 'category=' . urlencode($currentCategory) : ''; ?>" 
                           style="margin-left: 0.75rem; color: var(--color-gray-600); text-decoration: none;">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($currentCategory): ?>
                    <?php 
                    // Get category name
                    $categoryName = '';
                    foreach ($categories as $cat) {
                        if ($cat['slug'] == $currentCategory) {
                            $categoryName = $cat['name'];
                            break;
                        }
                    }
                    ?>
                    <div style="background: var(--color-primary); color: white; padding: 0.5rem 1rem; border-radius: var(--radius-full); display: flex; align-items: center;">
                        <i class="fas fa-folder" style="margin-right: 0.5rem;"></i>
                        <span><?php echo e($categoryName ?: $currentCategory); ?></span>
                        <a href="?<?php echo $searchTerm ? 'search=' . urlencode($searchTerm) : ''; ?>" 
                           style="margin-left: 0.75rem; color: rgba(255,255,255,0.8); text-decoration: none;">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div style="margin-left: auto; font-weight: 500; color: var(--color-gray-700);">
                        Found <?php echo count($publications); ?> publication<?php echo count($publications) !== 1 ? 's' : ''; ?>
                    </div>
                    
                    <?php if ($searchTerm || $currentCategory): ?>
                    <a href="/research" style="display: inline-flex; align-items: center; color: var(--color-primary); text-decoration: none; font-weight: 500;">
                        <i class="fas fa-undo-alt" style="margin-right: 0.5rem;"></i>
                        Clear all filters
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <form method="GET" action="/research" class="search-form">
                <div class="search-input-group">
                    <div class="search-icon">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           class="search-input" 
                           placeholder="Search publications by title, authors, abstract, or keywords..."
                           value="<?php echo e($searchTerm); ?>"
                           aria-label="Search publications"
                           id="searchInput">
                    <?php if ($searchTerm): ?>
                    <button type="button" class="search-clear" aria-label="Clear search" style="background: none; border: none; color: var(--color-gray-600); padding: 0 1rem; cursor: pointer; font-size: 1rem;">
                        <i class="fas fa-times"></i>
                    </button>
                    <?php endif; ?>
                </div>
                
                <div class="search-filters">
                    <select name="category" class="filter-select" aria-label="Filter by research area" id="categorySelect">
                        <option value="">All Research Areas</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo e($category['slug']); ?>" 
                                <?php echo ($currentCategory == $category['slug']) ? 'selected' : ''; ?>>
                                <?php echo e($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <div class="search-button">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search" aria-hidden="true"></i> Search
                        </button>
                    </div>
                    
                    <?php if ($searchTerm || $currentCategory): ?>
                    <div class="clear-button">
                        <a href="/research" class="btn btn-outline-primary">
                            <i class="fas fa-times" aria-hidden="true"></i> Clear All
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <!-- ========== RESEARCH STATISTICS ========== -->
    <section class="research-stats-section" aria-label="Research statistics">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Research Impact</h2>
                <p class="section-subtitle">Contributing to nursing science and healthcare innovation</p>
            </div>
            
            <div class="research-stats-grid">
                <div class="research-stat-item">
                    <div class="research-stat-icon">
                        <i class="fas fa-file-alt" aria-hidden="true"></i>
                    </div>
                    <div class="research-stat-number">
                        <?php echo count($publications); ?>+
                    </div>
                    <div class="research-stat-label">Publications</div>
                </div>
                
                <div class="research-stat-item">
                    <div class="research-stat-icon">
                        <i class="fas fa-users" aria-hidden="true"></i>
                    </div>
                    <div class="research-stat-number">
                        50+
                    </div>
                    <div class="research-stat-label">Researchers</div>
                </div>
                
                <div class="research-stat-item">
                    <div class="research-stat-icon">
                        <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                    </div>
                    <div class="research-stat-number">
                        10+
                    </div>
                    <div class="research-stat-label">Research Areas</div>
                </div>
                
                <div class="research-stat-item">
                    <div class="research-stat-icon">
                        <i class="fas fa-quote-right" aria-hidden="true"></i>
                    </div>
                    <div class="research-stat-number">
                        1,000+
                    </div>
                    <div class="research-stat-label">Total Citations</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FEATURED PUBLICATIONS ========== -->
    <?php if (!empty($featured)): ?>
    <section class="featured-publications-section section-alt" aria-label="Featured research publications">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Research</h2>
                <p class="section-subtitle">Highlighted publications from our researchers</p>
            </div>
            
            <div class="featured-grid">
                <?php foreach ($featured as $pub): ?>
                <article class="featured-card">
                    <div class="featured-header">
                        <span class="featured-badge">Featured</span>
                        <div class="featured-date">
                            <i class="far fa-calendar-alt" aria-hidden="true"></i>
                            <?php echo date('M Y', strtotime($pub['publication_date'])); ?>
                        </div>
                    </div>
                    
                    <div class="featured-content">
                        <h3 class="featured-title">
                            <a href="/research/<?php echo e($pub['id']); ?>">
                                <?php echo e($pub['title']); ?>
                            </a>
                        </h3>
                        
                        <p class="featured-authors">
                            <?php echo e(substr($pub['authors'], 0, 100)); ?><?php echo (strlen($pub['authors']) > 100) ? '...' : ''; ?>
                        </p>
                        
                        <p class="featured-abstract">
                            <?php echo e(substr(strip_tags($pub['abstract']), 0, 200)); ?>...
                        </p>
                    </div>
                    
                    <div class="featured-footer">
                        <div class="featured-tags">
                            <span class="featured-tag featured-type <?php echo e($pub['publication_type']); ?>">
                                <?php echo ucfirst($pub['publication_type']); ?>
                            </span>
                            <span class="featured-tag">
                                <?php echo e($pub['category_name'] ?? $pub['research_area']); ?>
                            </span>
                        </div>
                        
                        <div class="featured-metrics">
                            <span>
                                <i class="fas fa-eye" aria-hidden="true"></i>
                                <?php echo e($pub['views_count']); ?>
                            </span>
                            <span>
                                <i class="fas fa-download" aria-hidden="true"></i>
                                <?php echo e($pub['downloads_count']); ?>
                            </span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ========== RESEARCH AREAS ========== -->
    <section id="categories" class="research-areas-section" aria-label="Research areas">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Research Areas</h2>
                <p class="section-subtitle">Explore publications by research category</p>
            </div>
            
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                <a href="/research?category=<?php echo e($category['slug']); ?>&scroll=publications" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-folder-open" aria-hidden="true"></i>
                    </div>
                    
                    <div class="category-content">
                        <h3 class="category-title"><?php echo e($category['name']); ?></h3>
                        
                        <p class="category-description">
                            <?php echo e(substr($category['description'], 0, 100)); ?><?php echo (strlen($category['description']) > 100) ? '...' : ''; ?>
                        </p>
                        
                        <span class="category-count">
                            Browse Publications
                        </span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ========== ALL PUBLICATIONS ========== -->
    <section id="publications" class="all-publications-section" aria-label="All publications">
        <div class="container">
            <div class="publications-header">
                <h2 class="publications-title">All Publications</h2>
                <p class="publications-count">
                    <?php echo $totalPublications; ?> publication<?php echo ($totalPublications !== 1) ? 's' : ''; ?> found
                </p>
            </div>

            <?php if (empty($publications)): ?>
                <div class="no-publications">
                    <div class="no-publications-icon" aria-hidden="true">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="no-publications-title">No Publications Found</h3>
                    <p class="no-publications-message">
                        <?php if ($searchTerm || $currentCategory): ?>
                            No publications match your search criteria. Try different keywords or browse all publications.
                        <?php else: ?>
                            There are currently no publications available. Please check back soon.
                        <?php endif; ?>
                    </p>
                    <?php if ($searchTerm || $currentCategory): ?>
                        <a href="/research" class="btn btn-primary">
                            <i class="fas fa-undo" aria-hidden="true"></i> View All Publications
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="publications-list">
                    <?php foreach ($publications as $pub): ?>
                    <article class="publication-item">
                        <div class="publication-main">
                            <div class="publication-content">
                                <header class="publication-header">
                                    <div class="publication-meta">
                                        <span class="publication-type">
                                            <i class="fas fa-file-alt" aria-hidden="true"></i>
                                            <?php echo ucfirst($pub['publication_type']); ?>
                                        </span>
                                        <span class="publication-date">
                                            <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                            <?php echo date('F d, Y', strtotime($pub['publication_date'])); ?>
                                        </span>
                                    </div>
                                    
                                    <h3 class="publication-title">
                                        <a href="/research/<?php echo e($pub['id']); ?>">
                                            <?php echo e($pub['title']); ?>
                                        </a>
                                        <?php if ($pub['is_featured']): ?>
                                        <span class="publication-featured-badge">
                                            <i class="fas fa-star" aria-hidden="true"></i> Featured
                                        </span>
                                        <?php endif; ?>
                                    </h3>
                                    
                                    <p class="publication-authors">
                                        <i class="fas fa-users" aria-hidden="true"></i>
                                        <?php echo e($pub['authors']); ?>
                                    </p>
                                </header>
                                
                                <p class="publication-abstract">
                                    <?php echo e(substr(strip_tags($pub['abstract']), 0, 200)); ?>...
                                </p>
                            </div>
                            
                            <footer class="publication-footer">
                                <div class="publication-tags">
                                    <span class="publication-tag publication-category">
                                        <?php echo e($pub['category_name'] ?? $pub['research_area']); ?>
                                    </span>
                                    <?php if (!empty($pub['doi'])): ?>
                                    <span class="publication-tag publication-doi">
                                        DOI: <?php echo e($pub['doi']); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="publication-actions">
                                    <div class="publication-metrics">
                                        <span>
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                            <?php echo e($pub['views_count']); ?>
                                        </span>
                                        <span>
                                            <i class="fas fa-download" aria-hidden="true"></i>
                                            <?php echo e($pub['downloads_count']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="action-buttons">
                                        <?php if (!empty($pub['file_path'])): ?>
                                        <a href="/research/<?php echo e($pub['id']); ?>/download" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Download full text">
                                            <i class="fas fa-download" aria-hidden="true"></i>
                                            <span class="sr-only">Download</span>
                                        </a>
                                        <?php endif; ?>
                                        <a href="/research/<?php echo e($pub['id']); ?>" 
                                           class="btn btn-sm btn-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </footer>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ========== PAGINATION ========== -->
    <?php if (!empty($publications) && $totalPublications > 10): ?>
    <section class="pagination-section" aria-label="Publications pagination">
        <div class="pagination-container">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>
    <?php endif; ?>
</main>

<!-- ========== JAVASCRIPT ENHANCEMENTS ========== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Auto-remove flash messages after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.flash-message').forEach(function(msg) {
            msg.style.opacity = '0';
            msg.style.transform = 'translateX(100%)';
            setTimeout(function() {
                if (msg.parentNode) {
                    msg.parentNode.removeChild(msg);
                }
            }, 300);
        });
    }, 5000);
    
    // Manual close for flash messages
    document.querySelectorAll('.flash-message .close-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const msg = this.closest('.flash-message');
            msg.style.opacity = '0';
            msg.style.transform = 'translateX(100%)';
            setTimeout(function() {
                if (msg.parentNode) {
                    msg.parentNode.removeChild(msg);
                }
            }, 300);
        });
    });
    
    // FIXED: Correct image path for hero section
    const heroImagePath = '<?php echo $heroImagePath; ?>';
    const heroSection = document.getElementById('researchHero');
    
    // Load hero background image with fallback
    if (heroSection) {
        const heroImage = new Image();
        
        heroImage.onload = function() {
            // Apply image to the hero section
            heroSection.style.background = '#2D3748 url("' + heroImagePath + '") no-repeat center center';
            heroSection.style.backgroundSize = 'cover';
        };
        
        heroImage.onerror = function() {
            // Keep the existing gradient background if image fails
            heroSection.style.background = '#2D3748';
        };
        
        heroImage.src = heroImagePath;
    }
    
    // Publication type color mapping
    const typeColors = {
        'journal': 'var(--color-journal)',
        'conference': 'var(--color-conference)',
        'book': 'var(--color-book)',
        'thesis': 'var(--color-thesis)',
        'report': 'var(--color-primary)'
    };
    
    // Apply publication type colors
    const typeElements = document.querySelectorAll('.featured-type, .publication-type');
    
    typeElements.forEach(el => {
        const type = el.classList.contains('featured-type') 
            ? el.classList[1] 
            : el.textContent.toLowerCase().trim();
        
        const color = typeColors[type];
        if (color) {
            el.style.backgroundColor = color;
            el.style.color = 'var(--color-white)';
            el.style.borderColor = color;
        }
    });
    
    // Enhanced search functionality
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        const searchInput = document.getElementById('searchInput');
        const categorySelect = document.getElementById('categorySelect');
        const clearButtons = document.querySelectorAll('.search-clear, .clear-button a');
        
        // Clear search input button
        clearButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.classList.contains('search-clear')) {
                    e.preventDefault();
                    searchInput.value = '';
                    searchInput.focus();
                    
                    // If category is selected, submit with just category
                    if (categorySelect.value) {
                        // Add scroll parameter
                        const url = new URL(window.location.href);
                        url.searchParams.set('scroll', 'publications');
                        searchForm.action = url.pathname + url.search;
                        searchForm.submit();
                    }
                }
                // For clear all button, it will navigate to /research
            });
        });
        
        // Auto-submit when category changes if there's a search term
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                // Only auto-submit if there's a search term
                if (searchInput.value.trim()) {
                    // Add scroll parameter to URL
                    const url = new URL(window.location.href);
                    url.searchParams.set('scroll', 'publications');
                    searchForm.action = url.pathname + url.search;
                    searchForm.submit();
                }
            });
        }
        
        // Prevent empty search submissions
        searchForm.addEventListener('submit', function(e) {
            // If both search and category are empty, prevent submission
            if (!searchInput.value.trim() && !categorySelect.value) {
                e.preventDefault();
                return false;
            }
            
            // If only whitespace in search, clear it
            if (searchInput.value.trim() === '') {
                searchInput.value = '';
            }
            
            // Add scroll parameter to URL to scroll to publications after search
            const url = new URL(window.location.href);
            url.searchParams.set('scroll', 'publications');
            searchForm.action = url.pathname + url.search;
            
            // For immediate visual feedback, scroll after a small delay
            // but before the page reloads
            setTimeout(() => {
                if (searchInput.value.trim() || categorySelect.value) {
                    const publicationsSection = document.getElementById('publications');
                    if (publicationsSection) {
                        publicationsSection.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start' 
                        });
                    }
                }
            }, 100);
        });
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Focus search on Ctrl/Cmd + K
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
            }
            
            // Clear search on Escape when focused
            if (e.key === 'Escape' && document.activeElement === searchInput) {
                searchInput.value = '';
            }
        });
    }
    
    // Highlight search terms in results
    const searchTerm = '<?php echo e(addslashes($searchTerm)); ?>';
    if (searchTerm.trim()) {
        const searchTerms = searchTerm.toLowerCase().split(' ').filter(term => term.length > 2);
        
        if (searchTerms.length > 0) {
            // Function to highlight text
            const highlightText = (element) => {
                const html = element.innerHTML;
                let newHtml = html;
                
                searchTerms.forEach(term => {
                    if (term.length > 2) {
                        const regex = new RegExp(`(${term})`, 'gi');
                        newHtml = newHtml.replace(regex, '<mark style="background: var(--color-accent-light); padding: 0 0.1em; border-radius: 2px;">$1</mark>');
                    }
                });
                
                if (newHtml !== html) {
                    element.innerHTML = newHtml;
                }
            };
            
            // Highlight in titles and abstracts
            document.querySelectorAll('.featured-title, .publication-title, .featured-abstract, .publication-abstract, .featured-authors, .publication-authors').forEach(el => {
                highlightText(el);
            });
        }
    }
    
    // Keyboard navigation for publication cards
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.classList.contains('category-card')) {
            e.target.click();
        }
    });
    
    // Scroll to publications section if scroll parameter is present
    <?php if ($scrollToPublications): ?>
    setTimeout(function() {
        const publicationsSection = document.getElementById('publications');
        if (publicationsSection) {
            publicationsSection.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    }, 300); // Small delay to ensure page is fully loaded
    <?php endif; ?>
});
</script>

</body>
</html>