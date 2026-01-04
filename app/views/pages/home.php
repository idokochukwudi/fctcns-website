<?php
/**
 * Homepage View Template - Professional Redesign (Mature Light Purple Theme)
 * Mobile-Optimized Version
 * 
 * @package FCTCNS
 * @version 3.4
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$carouselSlides = $carouselSlides ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="<?php echo e($page_description ?? 'FCT College of Nursing Sciences - NMCN & NBTE Accredited Nursing Education'); ?>">
    <title><?php echo e($page_title ?? 'FCT College of Nursing Sciences'); ?></title>
    
    <!-- Professional Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ==========================================================================
   CRITICAL MOBILE ENHANCEMENTS & FIXES
   ========================================================================== */
* {
    -webkit-tap-highlight-color: transparent;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

html, body {
    width: 100%;
    overflow-x: hidden;
    position: relative;
}

body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background-color: #FFFFFF;
}

/* ELIMINATE ALL UNNECESSARY SPACING BETWEEN HEADER AND CONTENT */
.main-content,
.homepage-content,
.hero-section,
.hero-carousel,
.carousel-inner,
.carousel-slide {
    margin-top: 0 !important;
    padding-top: 0 !important;
    border-top: none !important;
}

/* Force remove any potential gaps */
body > *:first-child:not(header) {
    margin-top: 0 !important;
}

/* ==========================================================================
   CSS RESET & GLOBAL VARIABLES - PROFESSIONAL MATURE DESIGN
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
    
    /* Enhanced Neutral Colors - Professional */
    --color-white: #FFFFFF;
    --color-off-white: #FAFAFA;
    --color-gray-50: #F5F7FA;
    --color-gray-100: #E8ECF1;
    --color-gray-200: #D1D9E3;
    --color-gray-300: #B8C2CC;
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
   HERO CAROUSEL - PROFESSIONAL MATURE DESIGN
   ========================================================================== */
.hero-section {
    position: relative;
    width: 100%;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #4A3A6F, #5D4A8A);
}

.hero-carousel {
    position: relative;
    width: 100%;
    height: 75vh;
    max-height: 650px;
    min-height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(74, 58, 111, 0.95), rgba(93, 74, 138, 0.97));
}

.carousel-inner {
    position: relative;
    width: 100%;
    height: 100%;
}

.carousel-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.8s ease, visibility 0.8s;
    display: flex;
    align-items: center;
}

.carousel-slide.active {
    opacity: 1;
    visibility: visible;
    z-index: 2;
}

.carousel-slide-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    z-index: 1;
}

/* Professional overlay for text readability */
.carousel-slide-bg::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.6) 0%,
        rgba(0, 0, 0, 0.4) 50%,
        rgba(0, 0, 0, 0.2) 100%
    );
}

/* Carousel content - Professional minimal */
.carousel-slide-content {
    position: relative;
    z-index: 3;
    color: var(--color-white);
    max-width: 700px;
    padding: var(--spacing-xl);
    margin: 0 auto;
    text-align: center;
}

.carousel-slide-badge {
    display: inline-block;
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 0.4rem 1.2rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: var(--spacing-md);
    text-transform: uppercase;
    font-family: var(--font-heading);
}

/* REDUCED CAROUSEL FONT SIZES - More professional */
.carousel-slide-title {
    font-family: var(--font-heading);
    font-size: clamp(1.5rem, 4vw, 2rem); /* Reduced from 1.75-2.5rem */
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: var(--spacing-sm);
    color: var(--color-white);
    letter-spacing: -0.2px;
}

.carousel-slide-subtitle {
    font-size: clamp(0.95rem, 2.5vw, 1.15rem); /* Reduced from 1-1.2rem */
    font-weight: 400;
    margin-bottom: var(--spacing-lg);
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.9);
    font-family: var(--font-body);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.carousel-slide-cta {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-lg);
    justify-content: center;
}

/* ==========================================================================
   BUTTONS - PROFESSIONAL STYLING
   ========================================================================== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    font-family: var(--font-heading);
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    border-radius: var(--radius-md);
    transition: var(--transition-smooth);
    border: 1px solid transparent;
    cursor: pointer;
    min-height: 42px;
    letter-spacing: 0.3px;
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
    transform: translateY(-1px);
    box-shadow: var(--shadow-soft);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: var(--color-white);
    border-color: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.btn-secondary:hover,
.btn-secondary:focus {
    background: rgba(255, 255, 255, 0.2);
    color: var(--color-white);
    border-color: rgba(255, 255, 255, 0.3);
}

/* ==========================================================================
   STATISTICS SECTION - MATURE PROFESSIONAL DESIGN
   ========================================================================== */
.stats-section {
    background: var(--color-off-white);
    padding: var(--spacing-xl) 0;
    border-bottom: 1px solid var(--color-gray-100);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
    text-align: center;
}

.stat-item {
    padding: var(--spacing-lg);
    background: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-subtle);
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-transparent);
}

.stat-icon {
    font-size: 1.75rem; /* Reduced size */
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    background: var(--color-primary-very-light);
    border-radius: 50%;
}

/* REDUCED STATS FONT SIZES - More mature */
.stat-number {
    font-family: var(--font-heading);
    font-size: 1.75rem; /* Reduced from 2.5rem */
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--color-primary);
    line-height: 1;
}

.stat-label {
    font-size: 0.85rem; /* Reduced from 0.95rem */
    color: var(--color-gray-800);
    font-weight: 500;
    line-height: 1.4;
    letter-spacing: 0.3px;
}

/* ==========================================================================
   ACCREDITATION SECTION - PROFESSIONAL ARRANGEMENT
   ========================================================================== */
.accreditation-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0;
    border-bottom: 1px solid var(--color-gray-100);
}

.accreditation-container {
    max-width: 800px;
    margin: 0 auto;
}

.accreditation-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
    text-align: center;
    align-items: center;
}

.accreditation-text {
    max-width: 600px;
    margin: 0 auto;
}

.accreditation-text h3 {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
}

.accreditation-text p {
    font-size: 1rem;
    color: var(--color-gray-800);
    line-height: 1.6;
    font-weight: 400;
    margin-bottom: var(--spacing-lg);
}

.accreditation-badges {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
}

.accreditation-badge {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-lg);
    background: var(--color-primary-very-light);
    border-radius: var(--radius-md);
    transition: var(--transition-smooth);
    border: 1px solid var(--color-primary-transparent);
    text-align: left;
}

.accreditation-badge:hover {
    background: var(--color-white);
    box-shadow: var(--shadow-subtle);
    transform: translateY(-1px);
}

.badge-icon {
    font-size: 2rem;
    color: var(--color-primary);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: var(--color-white);
    border-radius: 50%;
    box-shadow: var(--shadow-subtle);
}

.badge-text {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.badge-text strong {
    font-family: var(--font-heading);
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--color-primary);
    line-height: 1.3;
    margin-bottom: 0.25rem;
}

.badge-text span {
    font-size: 0.85rem;
    color: var(--color-gray-800);
    font-weight: 400;
    line-height: 1.4;
}

/* ==========================================================================
   PROGRAMS SECTION - PROFESSIONAL LAYOUT
   ========================================================================== */
.programs-section {
    padding: var(--spacing-xl) 0;
    background: var(--color-off-white);
    border-bottom: 1px solid var(--color-gray-100);
}

.section-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.section-title {
    font-family: var(--font-heading);
    font-size: 1.75rem;
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
    font-size: 1rem;
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-top: var(--spacing-lg);
    font-weight: 400;
    padding: 0 var(--spacing-sm);
}

.program-cards-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-lg);
    margin-top: var(--spacing-lg);
}

.program-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-subtle);
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.program-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-transparent);
}

.program-card-header {
    padding: var(--spacing-lg);
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    color: var(--color-white);
    position: relative;
}

.program-card-accreditation {
    position: absolute;
    top: var(--spacing-md);
    right: var(--spacing-md);
    background: var(--color-white);
    color: var(--color-primary);
    padding: 0.3rem 0.8rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.program-card-title {
    font-family: var(--font-heading);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: var(--spacing-xs);
    color: var(--color-white);
    line-height: 1.3;
    padding-right: 70px;
}

.program-card-duration {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 400;
}

.program-card-body {
    padding: var(--spacing-lg);
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.program-card-description {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    font-size: 0.95rem;
    flex-grow: 1;
    font-weight: 400;
}

.program-card-highlights {
    margin-bottom: var(--spacing-md);
    background: var(--color-primary-very-light);
    padding: var(--spacing-md);
    border-radius: var(--radius-sm);
    border-left: 3px solid var(--color-primary);
}

.highlight-title {
    font-family: var(--font-heading);
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.highlight-list {
    list-style: none;
    padding-left: 0;
}

.highlight-list li {
    padding: 0.25rem 0;
    color: var(--color-gray-800);
    position: relative;
    padding-left: 1.25rem;
    font-size: 0.9rem;
    font-weight: 400;
    line-height: 1.5;
}

.highlight-list li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--color-accent);
    font-weight: bold;
    font-size: 1rem;
    top: 0.25rem;
}

.program-card-footer {
    padding: var(--spacing-md) var(--spacing-lg);
    border-top: 1px solid var(--color-gray-100);
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
    align-items: stretch;
}

.program-card-link {
    font-family: var(--font-heading);
    color: var(--color-primary);
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition-smooth);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    padding: 0.5rem;
    border: 1px solid var(--color-primary-transparent);
    border-radius: var(--radius-sm);
}

.program-card-link:hover,
.program-card-link:focus {
    background: var(--color-primary-very-light);
    color: var(--color-primary-dark);
}

.program-card-apply {
    background: var(--color-primary);
    color: var(--color-white);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-heading);
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition-smooth);
    border: 1px solid var(--color-primary);
    font-size: 0.9rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.program-card-apply:hover,
.program-card-apply:focus {
    background: var(--color-primary-dark);
    color: var(--color-white);
}

/* ==========================================================================
   CALL TO ACTION - PROFESSIONAL WITH VISIBLE BUTTONS
   ========================================================================== */
.cta-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0;
    text-align: center;
    border-top: 1px solid var(--color-gray-100);
}

.cta-content {
    max-width: 700px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
    padding: 0 var(--spacing-sm);
}

.cta-title {
    font-family: var(--font-heading);
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--spacing-md);
    line-height: 1.3;
}

.cta-description {
    font-size: 1.05rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-lg);
    line-height: 1.6;
    font-weight: 400;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.cta-buttons {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-lg);
    align-items: center;
    width: 100%;
}

/* FIX FOR CTA BUTTONS - VISIBLE ON WHITE BACKGROUND */
.cta-section .btn-secondary {
    background: var(--color-primary);
    color: var(--color-white);
    border-color: var(--color-primary);
}

.cta-section .btn-secondary:hover,
.cta-section .btn-secondary:focus {
    background: var(--color-primary-dark);
    color: var(--color-white);
    border-color: var(--color-primary-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-soft);
}

.cta-buttons .btn {
    width: 100%;
    max-width: 250px;
    margin: 0 auto;
    justify-content: center;
}

/* ==========================================================================
   CAROUSEL CONTROLS - VERY FAINT NAVIGATION
   ========================================================================== */
.carousel-controls {
    position: absolute;
    bottom: var(--spacing-md);
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
}

.carousel-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.2);
    cursor: pointer;
    transition: var(--transition-smooth);
    padding: 0;
    min-height: 8px;
    min-width: 8px;
}

.carousel-indicator.active {
    background: rgba(255, 255, 255, 0.8);
    border-color: rgba(255, 255, 255, 0.6);
}

.carousel-indicator:hover {
    background: rgba(255, 255, 255, 0.5);
    border-color: rgba(255, 255, 255, 0.4);
}

/* VERY FAINT NAVIGATION ICONS */
.carousel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
    z-index: 10;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    min-height: 36px;
    min-width: 36px;
    opacity: 0.7;
}

.carousel-nav:hover,
.carousel-nav:focus {
    background: rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.8);
    border-color: rgba(255, 255, 255, 0.2);
    opacity: 0.9;
}

.carousel-nav-prev {
    left: 1rem;
}

.carousel-nav-next {
    right: 1rem;
}

/* Fallback carousel */
.carousel-fallback {
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    height: 75vh;
    max-height: 650px;
    min-height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: var(--spacing-lg);
}

.carousel-fallback-content {
    max-width: 600px;
    color: var(--color-white);
    padding: var(--spacing-xl);
}

.carousel-fallback h1 {
    font-family: var(--font-heading);
    font-size: clamp(1.5rem, 4vw, 2rem);
    margin-bottom: var(--spacing-md);
    color: var(--color-white);
    line-height: 1.3;
    font-weight: 600;
}

.carousel-fallback p {
    font-size: 1.1rem;
    margin-bottom: var(--spacing-lg);
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.6;
}

/* ==========================================================================
   ENHANCED RESPONSIVENESS - BOTH MOBILE & DESKTOP
   ========================================================================== */
.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

@media (min-width: 768px) {
    :root {
        --spacing-xs: 0.5rem;
        --spacing-sm: 1rem;
        --spacing-md: 1.5rem;
        --spacing-lg: 2rem;
        --spacing-xl: 2.5rem;
        --spacing-xxl: 3.5rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-md);
    }
    
    .stat-item {
        padding: var(--spacing-xl);
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .stat-icon {
        font-size: 2rem;
        width: 60px;
        height: 60px;
    }
    
    .accreditation-badges {
        flex-direction: row;
        justify-content: center;
        max-width: 800px;
    }
    
    .accreditation-badge {
        flex: 1;
        min-width: 250px;
        max-width: 300px;
    }
    
    .program-cards-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: var(--spacing-lg);
    }
    
    .program-card-footer {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
    
    .program-card-link {
        justify-content: flex-start;
        width: auto;
    }
    
    .program-card-apply {
        width: auto;
    }
    
    .cta-buttons {
        flex-direction: row;
        justify-content: center;
        gap: var(--spacing-md);
    }
    
    .cta-buttons .btn {
        width: auto;
        min-width: 160px;
    }
    
    .carousel-nav {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .carousel-nav-prev {
        left: 2rem;
    }
    
    .carousel-nav-next {
        right: 2rem;
    }
}

@media (min-width: 1024px) {
    .carousel-slide-content {
        text-align: left;
        margin-left: 10%;
        margin-right: auto;
    }
    
    .carousel-slide-cta {
        justify-content: flex-start;
    }
    
    .accreditation-content {
        flex-direction: row;
        text-align: left;
        align-items: flex-start;
        gap: var(--spacing-xl);
    }
    
    .accreditation-text {
        flex: 1;
        text-align: left;
        margin: 0;
    }
    
    .accreditation-badges {
        flex: 1;
        max-width: none;
    }
    
    .program-card-title {
        font-size: 1.35rem;
    }
    
    .carousel-nav-prev {
        left: 3rem;
    }
    
    .carousel-nav-next {
        right: 3rem;
    }
}

@media (max-width: 480px) {
    .hero-carousel {
        height: 70vh;
        min-height: 450px;
    }
    
    .carousel-slide-content {
        padding: var(--spacing-lg);
    }
    
    .carousel-slide-title {
        font-size: 1.4rem;
    }
    
    .carousel-slide-subtitle {
        font-size: 0.9rem;
    }
    
    .carousel-slide-cta {
        flex-direction: column;
        align-items: center;
    }
    
    .carousel-slide-cta .btn {
        width: 100%;
        max-width: 280px;
    }
    
    .carousel-nav {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
    
    .carousel-nav-prev {
        left: 0.75rem;
    }
    
    .carousel-nav-next {
        right: 0.75rem;
    }
    
    .stat-item {
        padding: var(--spacing-md);
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .stat-icon {
        font-size: 1.5rem;
        width: 45px;
        height: 45px;
    }
    
    .program-card-title {
        padding-right: 0;
    }
    
    .program-card-accreditation {
        position: static;
        margin-bottom: var(--spacing-sm);
        display: inline-block;
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
        scroll-behavior: auto !important;
    }
}

:focus-visible {
    outline: 2px solid var(--color-accent);
    outline-offset: 2px;
    border-radius: var(--radius-sm);
}

/* Print Styles */
@media print {
    .hero-carousel,
    .carousel-nav,
    .carousel-controls {
        display: none !important;
    }
    
    .carousel-fallback {
        height: auto;
        min-height: auto;
    }
    
    .btn {
        display: none;
    }
    
    .program-card-apply,
    .program-card-link {
        display: none;
    }
    
    .stats-section,
    .accreditation-section {
        page-break-inside: avoid;
    }
    
    .program-card {
        page-break-inside: avoid;
        border: 1px solid #000;
        box-shadow: none;
    }
}

/* Safe Area Insets */
@supports (padding: max(0px)) {
    body,
    .container {
        padding-left: max(var(--spacing-md), env(safe-area-inset-left));
        padding-right: max(var(--spacing-md), env(safe-area-inset-right));
    }
}
</style>
</head>
<body>

<!-- Homepage Content -->
<main id="main-content" class="homepage-content" role="main">
    
    <!-- ========== HERO CAROUSEL ========== -->
    <section class="hero-section" aria-label="Featured content carousel">
        <?php if (empty($carouselSlides)): ?>
            <!-- Fallback carousel -->
            <div class="carousel-fallback" role="region" aria-label="Welcome message">
                <div class="carousel-fallback-content">
                    <h1>Welcome to FCT College of Nursing Sciences</h1>
                    <p>NMCN & NBTE Accredited Nursing Education Since 1989</p>
                    <div class="cta-buttons" style="margin-top: 1.5rem;">
                        <a href="<?php echo $baseUrl; ?>/programs" class="btn btn-primary">
                            <i class="fas fa-book-open" aria-hidden="true"></i> Explore Programs
                        </a>
                        <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-secondary">
                            <i class="fas fa-file-alt" aria-hidden="true"></i> Apply Now
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Dynamic Carousel -->
            <div id="heroCarousel" class="hero-carousel" role="region" aria-label="Featured slides" tabindex="0">
                <div class="carousel-inner">
                    <?php foreach ($carouselSlides as $index => $slide): ?>
                    <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                         data-slide="<?php echo $index; ?>"
                         aria-hidden="<?php echo $index === 0 ? 'false' : 'true'; ?>"
                         role="group" 
                         aria-label="Slide <?php echo $index + 1; ?> of <?php echo count($carouselSlides); ?>">
                        <div class="carousel-slide-bg" 
                             style="background-image: url('<?php echo e($slide['image_path']); ?>');"
                             role="img"
                             aria-label="<?php echo e($slide['title']); ?>">
                        </div>
                        <div class="carousel-slide-content">
                            <span class="carousel-slide-badge">Featured</span>
                            <h1 class="carousel-slide-title">
                                <?php echo e($slide['title']); ?>
                            </h1>
                            <p class="carousel-slide-subtitle">
                                <?php echo e($slide['subtitle']); ?>
                            </p>
                            <div class="carousel-slide-cta">
                                <?php if (!empty($slide['button_text']) && !empty($slide['button_link'])): ?>
                                <a href="<?php echo e($slide['button_link']); ?>" 
                                   class="btn btn-primary"
                                   aria-label="<?php echo e($slide['button_text']); ?> - <?php echo e($slide['title']); ?>">
                                    <i class="fas fa-arrow-right" aria-hidden="true"></i> <?php echo e($slide['button_text']); ?>
                                </a>
                                <?php endif; ?>
                                <a href="<?php echo $baseUrl; ?>/programs" class="btn btn-secondary">
                                    <i class="fas fa-book" aria-hidden="true"></i> All Programs
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Faint Navigation Arrows -->
                <button class="carousel-nav carousel-nav-prev" 
                        aria-label="Previous slide"
                        onclick="carouselController.prev()">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    <span class="sr-only">Previous Slide</span>
                </button>
                <button class="carousel-nav carousel-nav-next" 
                        aria-label="Next slide"
                        onclick="carouselController.next()">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    <span class="sr-only">Next Slide</span>
                </button>
                
                <!-- Indicators -->
                <div class="carousel-controls">
                    <?php foreach ($carouselSlides as $index => $slide): ?>
                    <button class="carousel-indicator <?php echo $index === 0 ? 'active' : ''; ?>"
                            data-slide="<?php echo $index; ?>"
                            aria-label="Go to slide <?php echo $index + 1; ?>"
                            onclick="carouselController.goToSlide(<?php echo $index; ?>)"
                            aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                        <span class="sr-only">Slide <?php echo $index + 1; ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <!-- ========== STATISTICS ========== -->
    <section class="stats-section" aria-label="College statistics">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    </div>
                    <div class="stat-number">35+</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate" aria-hidden="true"></i>
                    </div>
                    <div class="stat-number">5,000+</div>
                    <div class="stat-label">Graduates</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-award" aria-hidden="true"></i>
                    </div>
                    <div class="stat-number">100%</div>
                    <div class="stat-label">NMCN Accredited</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher" aria-hidden="true"></i>
                    </div>
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Expert Faculty</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ACCREDITATION - PROPERLY ARRANGED ========== -->
    <section class="accreditation-section" aria-label="Accreditation badges">
        <div class="container accreditation-container">
            <div class="accreditation-content">
                <div class="accreditation-text">
                    <h3>Nationally Recognized Accreditation</h3>
                    <p>Our programs meet the highest standards set by Nigeria's regulatory bodies for nursing education.</p>
                </div>
                <div class="accreditation-badges">
                    <div class="accreditation-badge">
                        <div class="badge-icon">
                            <i class="fas fa-stethoscope" aria-hidden="true"></i>
                        </div>
                        <div class="badge-text">
                            <strong>NMCN</strong>
                            <span>Nursing & Midwifery Council of Nigeria</span>
                        </div>
                    </div>
                    <div class="accreditation-badge">
                        <div class="badge-icon">
                            <i class="fas fa-university" aria-hidden="true"></i>
                        </div>
                        <div class="badge-text">
                            <strong>NBTE</strong>
                            <span>National Board for Technical Education</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== PROGRAMS ========== -->
    <section class="programs-section" aria-label="Academic programs">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Accredited Nursing Programs</h2>
                <p class="section-subtitle">
                    Fully approved programs meeting NMCN & NBTE standards. We offer comprehensive 
                    nursing education recognized nationwide.
                </p>
            </div>
            
            <div class="program-cards-grid">
                <!-- Basic Nursing -->
                <article class="program-card">
                    <div class="program-card-header">
                        <span class="program-card-accreditation">NMCN</span>
                        <h3 class="program-card-title">Basic Nursing</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock" aria-hidden="true"></i> Duration: 3 Years
                        </div>
                    </div>
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Comprehensive general nursing education preparing students for registration as Registered Nurses (RN). 
                            Includes medical, surgical, pediatric, and community nursing.
                        </p>
                        <div class="program-card-highlights">
                            <div class="highlight-title">
                                <i class="fas fa-star" aria-hidden="true"></i> Key Features
                            </div>
                            <ul class="highlight-list">
                                <li>Full NMCN accreditation</li>
                                <li>Clinical rotations</li>
                                <li>Simulation labs</li>
                                <li>Exam preparation</li>
                            </ul>
                        </div>
                    </div>
                    <div class="program-card-footer">
                        <a href="<?php echo $baseUrl; ?>/programs/basic-nursing" 
                           class="program-card-link"
                           aria-label="Learn more about Basic Nursing">
                            Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                        <a href="<?php echo $baseUrl; ?>/admissions" class="program-card-apply">
                            <i class="fas fa-file-alt" aria-hidden="true"></i> Apply Now
                        </a>
                    </div>
                </article>
                
                <!-- National Diploma -->
                <article class="program-card">
                    <div class="program-card-header">
                        <span class="program-card-accreditation">NBTE</span>
                        <h3 class="program-card-title">National Diploma in Nursing</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock" aria-hidden="true"></i> Duration: 2 Years
                        </div>
                    </div>
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Polytechnic-based nursing education leading to ND qualification. Combines theoretical knowledge 
                            with practical skills for healthcare delivery.
                        </p>
                        <div class="program-card-highlights">
                            <div class="highlight-title">
                                <i class="fas fa-star" aria-hidden="true"></i> Key Features
                            </div>
                            <ul class="highlight-list">
                                <li>NBTE accredited</li>
                                <li>JAMB UTME pathway</li>
                                <li>Industry curriculum</li>
                                <li>Technical skills</li>
                            </ul>
                        </div>
                    </div>
                    <div class="program-card-footer">
                        <a href="<?php echo $baseUrl; ?>/programs/nd-nursing" 
                           class="program-card-link"
                           aria-label="Learn more about National Diploma in Nursing">
                            Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                        <a href="<?php echo $baseUrl; ?>/admissions" class="program-card-apply">
                            <i class="fas fa-file-alt" aria-hidden="true"></i> Apply Now
                        </a>
                    </div>
                </article>
                
                <!-- Higher National Diploma -->
                <article class="program-card">
                    <div class="program-card-header">
                        <span class="program-card-accreditation">NBTE</span>
                        <h3 class="program-card-title">Higher National Diploma in Nursing</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock" aria-hidden="true"></i> Duration: 2 Years
                        </div>
                    </div>
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Advanced nursing education for Registered Nurses or ND holders. Focus on nursing administration, 
                            education, and specialized clinical practice.
                        </p>
                        <div class="program-card-highlights">
                            <div class="highlight-title">
                                <i class="fas fa-star" aria-hidden="true"></i> Key Features
                            </div>
                            <ul class="highlight-list">
                                <li>Advanced specialization</li>
                                <li>Leadership training</li>
                                <li>Research methodology</li>
                                <li>Career advancement</li>
                            </ul>
                        </div>
                    </div>
                    <div class="program-card-footer">
                        <a href="<?php echo $baseUrl; ?>/programs/hnd-nursing" 
                           class="program-card-link"
                           aria-label="Learn more about Higher National Diploma in Nursing">
                            Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                        <a href="<?php echo $baseUrl; ?>/admissions" class="program-card-apply">
                            <i class="fas fa-file-alt" aria-hidden="true"></i> Apply Now
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- ========== CALL TO ACTION ========== -->
    <section class="cta-section" aria-label="Call to action">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Begin Your Nursing Career Today</h2>
                <p class="cta-description">
                    Join our community of healthcare professionals making a difference across Nigeria. 
                    Our graduates are highly sought after nationwide.
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo $baseUrl; ?>/admissions" 
                       class="btn btn-primary">
                        <i class="fas fa-file-alt" aria-hidden="true"></i> Apply Now
                    </a>
                    <a href="<?php echo $baseUrl; ?>/programs" 
                       class="btn btn-secondary">
                        <i class="fas fa-book-open" aria-hidden="true"></i> View Programs
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" 
                       class="btn btn-secondary">
                        <i class="fas fa-phone-alt" aria-hidden="true"></i> Contact Admissions
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- ========== CAROUSEL JAVASCRIPT ========== -->
<script>
(function() {
    'use strict';
    
    const carouselController = {
        currentSlide: 0,
        totalSlides: 0,
        autoPlayInterval: null,
        autoPlayDelay: 6000,
        isTransitioning: false,
        
        init() {
            const carousel = document.getElementById('heroCarousel');
            if (!carousel) return;
            
            const slides = carousel.querySelectorAll('.carousel-slide');
            this.totalSlides = slides.length;
            
            if (this.totalSlides === 0) return;
            
            // Initialize first slide
            slides[0].classList.add('active');
            slides[0].setAttribute('aria-hidden', 'false');
            
            // Initialize indicators
            const indicators = carousel.querySelectorAll('.carousel-indicator');
            if (indicators.length > 0) {
                indicators[0].classList.add('active');
                indicators[0].setAttribute('aria-current', 'true');
            }
            
            // Start auto-play
            this.startAutoPlay();
            
            // Pause on interaction
            carousel.addEventListener('mouseenter', () => this.stopAutoPlay());
            carousel.addEventListener('mouseleave', () => this.startAutoPlay());
            
            // Touch support
            this.addTouchSupport(carousel);
        },
        
        goToSlide(index) {
            if (this.isTransitioning || index === this.currentSlide || index < 0 || index >= this.totalSlides) return;
            
            this.isTransitioning = true;
            
            const slides = document.querySelectorAll('.carousel-slide');
            const indicators = document.querySelectorAll('.carousel-indicator');
            
            // Hide current slide
            slides[this.currentSlide].classList.remove('active');
            slides[this.currentSlide].setAttribute('aria-hidden', 'true');
            
            if (indicators[this.currentSlide]) {
                indicators[this.currentSlide].classList.remove('active');
                indicators[this.currentSlide].setAttribute('aria-current', 'false');
            }
            
            // Show new slide
            slides[index].classList.add('active');
            slides[index].setAttribute('aria-hidden', 'false');
            
            if (indicators[index]) {
                indicators[index].classList.add('active');
                indicators[index].setAttribute('aria-current', 'true');
            }
            
            this.currentSlide = index;
            
            setTimeout(() => {
                this.isTransitioning = false;
            }, 600);
        },
        
        next() {
            const nextIndex = (this.currentSlide + 1) % this.totalSlides;
            this.goToSlide(nextIndex);
        },
        
        prev() {
            const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            this.goToSlide(prevIndex);
        },
        
        startAutoPlay() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
            }
            
            this.autoPlayInterval = setInterval(() => {
                if (!this.isTransitioning) {
                    this.next();
                }
            }, this.autoPlayDelay);
        },
        
        stopAutoPlay() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
                this.autoPlayInterval = null;
            }
        },
        
        addTouchSupport(carousel) {
            let touchStartX = 0;
            let touchEndX = 0;
            const minSwipeDistance = 50;
            
            carousel.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
                this.stopAutoPlay();
            }, { passive: true });
            
            carousel.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                const distance = touchStartX - touchEndX;
                
                if (Math.abs(distance) < minSwipeDistance) return;
                
                if (distance > 0) {
                    this.next();
                } else {
                    this.prev();
                }
                
                setTimeout(() => this.startAutoPlay(), 3000);
            }, { passive: true });
        }
    };
    
    window.carouselController = carouselController;
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => carouselController.init());
    } else {
        carouselController.init();
    }
})();

// Accessibility and mobile enhancements
(function() {
    'use strict';
    
    // Add screen reader only class
    const style = document.createElement('style');
    style.textContent = `
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
    `;
    document.head.appendChild(style);
})();
</script>

</body>
</html>