<?php
/**
 * Homepage View Template - Professional Redesign (Mature Light Purple Theme)
 * Mobile-Optimized Version - FULL WIDTH
 * Complete Redesign with Professional Sections & Image Support
 * 
 * @package FCTCNS
 * @version 4.0
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$carouselSlides = $carouselSlides ?? [];
$baseUrl = $baseUrl ?? '/';

// Define default carousel slides if none provided
if (empty($carouselSlides)) {
    $carouselSlides = [
        [
            'image_path' => $baseUrl . '/assets/images/homepage/hero-campus-life.jpg',
            'title' => 'Welcome to FCT College of Nursing Sciences',
            'subtitle' => 'NMCN & NBTE Accredited Nursing Education Since 1989 - Join a legacy of 5,000+ nurses trained in a 35-year tradition of academic rigor and community care.',
            'button_text' => 'Discover Our Community',
            'button_link' => $baseUrl . '/student-life'
        ],
        [
            'image_path' => $baseUrl . '/assets/images/homepage/hero-simulation-lab.jpg',
            'title' => 'Train in Advanced Simulation Environments',
            'subtitle' => 'Practice with high-fidelity manikins and virtual reality in labs designed for real-world preparedness.',
            'button_text' => 'Explore Our Facilities',
            'button_link' => $baseUrl . '/facilities'
        ],
        [
            'image_path' => $baseUrl . '/assets/images/homepage/hero-graduation-celebration.jpg',
            'title' => 'Begin Your Journey to a Fulfilling Career',
            'subtitle' => 'Our graduates are highly sought-after for their skill, integrity, and readiness to lead in diverse healthcare settings.',
            'button_text' => 'View Program Outcomes',
            'button_link' => $baseUrl . '/alumni'
        ]
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="<?php echo e($page_description ?? 'FCT College of Nursing Sciences - NMCN & NBTE Accredited Nursing Education'); ?>">
    <title><?php echo e($page_title ?? 'FCT College of Nursing Sciences'); ?></title>
    
    <!-- Professional Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ==========================================================================
   TARGETED FIX FOR SPACE ABOVE CAROUSEL ONLY
   ========================================================================== */
/* Remove default browser spacing */
html {
    margin: 0;
    padding: 0;
}

/* Only target the body and elements before carousel */
body {
    margin: 0 !important;
    padding: 0 !important;
}

/* Target the hero section specifically */
.hero-section {
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
}

/* Target the carousel container */
.hero-carousel {
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    top: 0;
}

/* Remove any space from the main content wrapper */
main#main-content,
.homepage-content {
    margin: 0 !important;
    padding: 0 !important;
}

/* Only remove top margins/paddings, keep others intact */
.hero-section,
.hero-carousel,
.carousel-inner,
.carousel-slide {
    margin-top: 0 !important;
    padding-top: 0 !important;
    border-top: none !important;
}

/* If there's still space, try this specific selector */
body > .homepage-content > .hero-section:first-child {
    margin-top: -1px !important;
    padding-top: 0 !important;
}

/* Ensure carousel starts at the very top */
.carousel-slide {
    top: 0 !important;
}

/* Remove any line-height or font spacing */
.carousel-slide-content {
    margin-top: 0 !important;
    padding-top: var(--spacing-xl) !important; /* Keep original padding but no extra top space */
}
/* ==========================================================================
   CRITICAL MOBILE ENHANCEMENTS & FIXES - FULL WIDTH
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
    width: 100%;
    overflow-x: hidden;
    position: relative;
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

/* Font family inheritance for all elements */
h1, h2, h3, h4, h5, h6,
button, .btn,
.section-title,
.carousel-slide-title,
.accreditation-text h3,
.environment-content h3,
.community-content h3,
.attribute-card h3,
.hub-card h3,
.cta-title,
.stat-label,
.badge-text strong {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
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
    
    /* Status Colors */
    --color-closed: #dc3545;
    --color-closed-light: rgba(220, 53, 69, 0.1);
    --color-active: #28a745;
    --color-active-light: rgba(40, 167, 69, 0.1);
    --color-warning: #ffc107;
    --color-warning-light: rgba(255, 193, 7, 0.1);
    
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
    
    /* Transitions */
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ==========================================================================
   FULL WIDTH CONTAINERS & CENTRALIZED CONTENT
   ========================================================================== */
.container {
    width: 100%;
    max-width: 100%;
    margin: 0;
    padding: 0;
}

/* FULL WIDTH SECTIONS - NO TOP MARGIN/PADDING */
.hero-section,
.cta-section,
.stats-section,
.accreditation-section,
.learning-environment-section,
.graduate-attributes-section,
.campus-community-section,
.navigation-hub-section {
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Centralized content containers */
.container > *,
.stats-grid,
.accreditation-container,
.environment-grid,
.attributes-grid,
.community-grid,
.hub-grid,
.cta-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

/* ==========================================================================
   APPLICATION STATUS BANNER - CENTRALIZED
   ========================================================================== */
.application-status-banner {
    background: linear-gradient(135deg, var(--color-closed-light), var(--color-white));
    border-left: 5px solid var(--color-closed);
    padding: var(--spacing-md);
    margin: var(--spacing-md) auto var(--spacing-lg) auto;
    border-radius: var(--radius-md);
    max-width: 1200px;
    text-align: center;
    box-shadow: var(--shadow-soft);
    width: 95%;
}

.application-status-banner h3 {
    color: var(--color-closed);
    margin-bottom: var(--spacing-xs);
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
    font-family: var(--font-heading);
    text-align: center;
}

.application-status-banner p {
    color: var(--color-gray-800);
    line-height: 1.5;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    text-align: center;
}

.application-status-banner strong {
    color: var(--color-primary);
}

/* ==========================================================================
   HERO CAROUSEL - FULL WIDTH (NO WHITE GAPS, NO TOP SPACE)
   ========================================================================== */
.hero-section {
    position: relative;
    width: 100%;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #4A3A6F, #5D4A8A);
    overflow: hidden;
}

.hero-carousel {
    position: relative;
    width: 100%;
    height: 75vh;
    max-height: 650px;
    min-height: 500px;
    overflow: hidden;
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
    justify-content: center;
}

.carousel-slide.active {
    opacity: 1;
    visibility: visible;
    z-index: 2;
}

/* Carousel background with full width */
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

/* Carousel content - PROPERLY CENTERED */
.carousel-slide-content {
    position: relative;
    z-index: 3;
    color: var(--color-white);
    max-width: 900px;
    padding: var(--spacing-xl);
    margin: 0 auto;
    text-align: center;
    width: 90%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
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
    text-align: center;
}

/* Carousel title - NO CUTOFF */
.carousel-slide-title {
    font-family: var(--font-heading);
    font-size: clamp(1.5rem, 4.5vw, 2.8rem);
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: var(--spacing-sm);
    color: var(--color-white);
    letter-spacing: -0.2px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    width: 100%;
    word-wrap: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
    text-align: center;
    padding: 0 10px;
}

/* Carousel subtitle - NO CUTOFF */
.carousel-slide-subtitle {
    font-size: clamp(0.95rem, 2.8vw, 1.35rem);
    font-weight: 400;
    margin-bottom: var(--spacing-lg);
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.95);
    font-family: var(--font-body);
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 100%;
    word-wrap: break-word;
    overflow-wrap: break-word;
    padding: 0 10px;
}

.carousel-slide-cta {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-lg);
    justify-content: center;
    width: 100%;
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
    white-space: nowrap;
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

.btn-disabled {
    background: var(--color-gray-300);
    color: var(--color-gray-600);
    border-color: var(--color-gray-300);
    cursor: not-allowed;
    opacity: 0.7;
}

.btn-disabled:hover,
.btn-disabled:focus {
    background: var(--color-gray-300);
    color: var(--color-gray-600);
    transform: none;
    box-shadow: none;
}

/* ==========================================================================
   STATISTICS SECTION - MATURE PROFESSIONAL DESIGN - FULL WIDTH CONTENT
   ========================================================================== */
.stats-section {
    background: var(--color-off-white);
    padding: var(--spacing-xl) 0 !important;
    border-bottom: 1px solid var(--color-gray-100);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
    text-align: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
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
    font-size: 1.75rem;
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

.stat-number {
    font-family: var(--font-heading);
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--color-primary);
    line-height: 1;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--color-gray-800);
    font-weight: 500;
    line-height: 1.4;
    letter-spacing: 0.3px;
    font-family: var(--font-heading);
}

/* ==========================================================================
   ACCREDITATION SECTION - PROFESSIONAL ARRANGEMENT - FULL WIDTH CONTENT
   ========================================================================== */
.accreditation-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0 !important;
    border-bottom: 1px solid var(--color-gray-100);
}

.accreditation-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

.accreditation-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
    text-align: center;
    align-items: center;
}

.accreditation-text {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
    padding: 0 var(--spacing-md);
}

.accreditation-text h3 {
    font-family: var(--font-heading);
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
    text-align: center;
}

.accreditation-text p {
    font-size: 1.05rem;
    color: var(--color-gray-800);
    line-height: 1.6;
    font-weight: 400;
    margin-bottom: var(--spacing-lg);
    text-align: center;
    font-family: var(--font-body);
}

.accreditation-badges {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
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
    text-align: center;
    flex-direction: column;
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
    min-width: 0;
    text-align: center;
    align-items: center;
}

.badge-text strong {
    font-family: var(--font-heading);
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--color-primary);
    line-height: 1.3;
    margin-bottom: 0.25rem;
    word-wrap: break-word;
    text-align: center;
}

.badge-text span {
    font-size: 0.85rem;
    color: var(--color-gray-800);
    font-weight: 400;
    line-height: 1.4;
    font-family: var(--font-body);
    text-align: center;
}

/* ==========================================================================
   SECTION STYLES - REUSABLE
   ========================================================================== */
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
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    position: relative;
    display: inline-block;
    text-align: center;
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
    font-size: 1.05rem;
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-top: var(--spacing-lg);
    font-weight: 400;
    padding: 0 var(--spacing-sm);
    text-align: center;
    font-family: var(--font-body);
}

/* ==========================================================================
   LEARNING ENVIRONMENT SECTION WITH IMAGE CAPTIONS
   ========================================================================== */
.learning-environment-section {
    background: var(--color-off-white);
    padding: var(--spacing-xl) 0 !important;
}

.environment-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-xl);
    margin-top: var(--spacing-lg);
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 var(--spacing-md);
}

.environment-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-subtle);
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    height: 100%;
}

.environment-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-transparent);
}

.environment-image-container {
    position: relative;
    height: 250px;
    width: 100%;
}

.environment-image {
    height: 100%;
    width: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

/* Image caption styling */
.image-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 8px 12px;
    font-size: 0.85rem;
    text-align: center;
    font-family: var(--font-body);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

.environment-content {
    padding: var(--spacing-lg);
    text-align: center;
}

.environment-content h3 {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
    text-align: center;
}

.environment-content p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    font-size: 1rem;
    font-weight: 400;
    text-align: center;
    font-family: var(--font-body);
}

.environment-features {
    list-style: none;
    padding-left: 0;
    margin-top: var(--spacing-md);
    text-align: left;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.environment-features li {
    padding: 0.35rem 0;
    color: var(--color-gray-800);
    position: relative;
    padding-left: 1.75rem;
    font-size: 0.95rem;
    font-weight: 400;
    line-height: 1.5;
    font-family: var(--font-body);
}

.environment-features li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--color-accent);
    font-weight: bold;
    font-size: 1.1rem;
    top: 0.35rem;
}

/* ==========================================================================
   GRADUATE ATTRIBUTES SECTION
   ========================================================================== */
.graduate-attributes-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0 !important;
    border-top: 1px solid var(--color-gray-100);
}

.attributes-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-lg);
    margin-top: var(--spacing-lg);
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 var(--spacing-md);
}

.attribute-card {
    background: var(--color-primary-very-light);
    padding: var(--spacing-lg);
    border-radius: var(--radius-lg);
    border-left: 4px solid var(--color-primary);
    transition: var(--transition-smooth);
    display: flex;
    flex-direction: column;
    height: 100%;
    text-align: center;
}

.attribute-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-soft);
}

.attribute-icon {
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
    flex-shrink: 0;
    margin: 0 auto;
}

.attribute-card h3 {
    font-family: var(--font-heading);
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
    min-height: 3em;
    text-align: center;
}

.attribute-card p {
    color: var(--color-gray-800);
    line-height: 1.6;
    font-size: 0.95rem;
    font-weight: 400;
    flex-grow: 1;
    text-align: center;
    font-family: var(--font-body);
}

/* ==========================================================================
   CAMPUS & COMMUNITY SECTION WITH IMAGE CAPTIONS
   ========================================================================== */
.campus-community-section {
    background: var(--color-off-white);
    padding: var(--spacing-xl) 0 !important;
    border-top: 1px solid var(--color-gray-100);
}

.community-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-xl);
    margin-top: var(--spacing-lg);
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 var(--spacing-md);
}

.community-card {
    display: flex;
    flex-direction: column;
    background: var(--color-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-subtle);
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    height: 100%;
}

.community-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-transparent);
}

.community-image-container {
    position: relative;
    height: 250px;
    width: 100%;
}

.community-image {
    height: 100%;
    width: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.community-content {
    padding: var(--spacing-lg);
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    text-align: center;
}

.community-content h3 {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
    text-align: center;
}

.community-content p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    font-size: 1rem;
    font-weight: 400;
    flex-grow: 1;
    text-align: center;
    font-family: var(--font-body);
}

.community-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--color-primary);
    font-weight: 500;
    text-decoration: none;
    font-family: var(--font-heading);
    transition: var(--transition-smooth);
    font-size: 0.95rem;
    margin-top: auto;
    text-align: center;
    justify-content: center;
}

.community-link:hover {
    color: var(--color-primary-dark);
    gap: 0.75rem;
}

/* ==========================================================================
   NAVIGATION HUB SECTION
   ========================================================================== */
.navigation-hub-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0 !important;
    border-top: 1px solid var(--color-gray-100);
}

.hub-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-lg);
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 var(--spacing-md);
}

.hub-card {
    background: var(--color-primary-very-light);
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    text-align: center;
    border: 1px solid var(--color-primary-transparent);
    transition: var(--transition-smooth);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.hub-card:hover {
    background: var(--color-white);
    transform: translateY(-4px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary);
}

.hub-icon {
    font-size: 2.5rem;
    color: var(--color-primary);
    margin-bottom: var(--spacing-md);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: var(--color-white);
    border-radius: 50%;
    margin: 0 auto var(--spacing-md);
    box-shadow: var(--shadow-subtle);
    flex-shrink: 0;
}

.hub-card h3 {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
    min-height: 3.5em;
    text-align: center;
}

.hub-card p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
    font-size: 1rem;
    font-weight: 400;
    flex-grow: 1;
    text-align: center;
    font-family: var(--font-body);
}

.hub-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: var(--color-primary);
    font-weight: 500;
    text-decoration: none;
    font-family: var(--font-heading);
    transition: var(--transition-smooth);
    font-size: 1rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid var(--color-primary);
    border-radius: var(--radius-md);
    margin-top: auto;
    text-align: center;
}

.hub-link:hover {
    background: var(--color-primary);
    color: var(--color-white);
    gap: 0.75rem;
}

/* ==========================================================================
   CALL TO ACTION - PROFESSIONAL WITH VISIBLE BUTTONS - FULL WIDTH
   ========================================================================== */
.cta-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0 !important;
    text-align: center;
    border-top: 1px solid var(--color-gray-100);
}

.cta-content {
    max-width: 1000px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
    padding: 0 var(--spacing-md);
    text-align: center;
}

.cta-title {
    font-family: var(--font-heading);
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--spacing-md);
    line-height: 1.3;
    text-align: center;
}

.cta-description {
    font-size: 1.05rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-lg);
    line-height: 1.6;
    font-weight: 400;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    font-family: var(--font-body);
}

.cta-buttons {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-lg);
    align-items: center;
    width: 100%;
    justify-content: center;
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
    text-align: center;
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
    width: 100%;
}

.carousel-fallback-content {
    max-width: 800px;
    color: var(--color-white);
    padding: var(--spacing-xl);
    text-align: center;
}

.carousel-fallback h1 {
    font-family: var(--font-heading);
    font-size: clamp(1.5rem, 4vw, 2rem);
    margin-bottom: var(--spacing-md);
    color: var(--color-white);
    line-height: 1.3;
    font-weight: 600;
    text-align: center;
}

.carousel-fallback p {
    font-size: 1.1rem;
    margin-bottom: var(--spacing-lg);
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.6;
    text-align: center;
    font-family: var(--font-body);
}

/* ==========================================================================
   ENHANCED RESPONSIVENESS - BOTH MOBILE & DESKTOP
   ========================================================================== */
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
        max-width: 1200px;
    }
    
    .accreditation-badge {
        flex: 1;
        min-width: 300px;
        max-width: 350px;
        flex-direction: row;
        text-align: left;
    }
    
    .badge-text {
        align-items: flex-start;
        text-align: left;
    }
    
    .badge-text strong,
    .badge-text span {
        text-align: left;
    }
    
    .environment-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-xl);
    }
    
    .attributes-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .community-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-xl);
    }
    
    .hub-grid {
        grid-template-columns: repeat(3, 1fr);
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
    
    .environment-content h3,
    .community-content h3 {
        font-size: 1.75rem;
    }
    
    .environment-content p,
    .community-content p {
        font-size: 1.05rem;
    }
    
    .carousel-slide-title {
        font-size: clamp(1.8rem, 4.5vw, 3rem);
    }
    
    .carousel-slide-subtitle {
        font-size: clamp(1.1rem, 2.8vw, 1.4rem);
    }
}

@media (min-width: 1024px) {
    .carousel-slide-content {
        text-align: center;
        margin: 0 auto;
        padding: var(--spacing-xxl);
    }
    
    .carousel-slide-cta {
        justify-content: center;
    }
    
    .accreditation-content {
        flex-direction: row;
        align-items: flex-start;
        gap: var(--spacing-xl);
    }
    
    .accreditation-text {
        flex: 1;
        text-align: left;
        margin: 0;
        padding: 0;
    }
    
    .accreditation-text h3,
    .accreditation-text p {
        text-align: left;
    }
    
    .accreditation-badges {
        flex: 1;
        max-width: none;
    }
    
    .carousel-nav-prev {
        left: 3rem;
    }
    
    .carousel-nav-next {
        right: 3rem;
    }
    
    /* Full width containers for large screens */
    .stats-grid,
    .environment-grid,
    .attributes-grid,
    .community-grid,
    .hub-grid {
        padding: 0 var(--spacing-lg);
    }
    
    .environment-content,
    .community-content {
        padding: var(--spacing-xl);
    }
    
    .hero-carousel {
        max-height: 700px;
    }
}

@media (min-width: 1400px) {
    .container {
        max-width: 100%;
    }
    
    .stats-grid,
    .environment-grid,
    .attributes-grid,
    .community-grid,
    .hub-grid {
        max-width: 1400px;
    }
    
    .carousel-slide-content {
        max-width: 1000px;
    }
}

@media (max-width: 480px) {
    .hero-carousel {
        height: 70vh;
        min-height: 450px;
    }
    
    .carousel-slide-content {
        padding: var(--spacing-lg);
        width: 95%;
    }
    
    .carousel-slide-title {
        font-size: 1.4rem;
        line-height: 1.2;
        padding: 0 5px;
    }
    
    .carousel-slide-subtitle {
        font-size: 0.9rem;
        line-height: 1.5;
        padding: 0 5px;
    }
    
    .carousel-slide-cta {
        flex-direction: column;
        align-items: center;
        gap: var(--spacing-xs);
    }
    
    .carousel-slide-cta .btn {
        width: 100%;
        max-width: 280px;
        margin: 5px 0;
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
    
    .attribute-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .hub-icon {
        width: 70px;
        height: 70px;
        font-size: 2rem;
    }
    
    .attribute-card h3 {
        min-height: auto;
        font-size: 1.1rem;
    }
    
    .hub-card h3 {
        min-height: auto;
        font-size: 1.3rem;
    }
    
    .application-status-banner {
        width: 95%;
        padding: var(--spacing-sm);
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
    
    .stats-section,
    .accreditation-section {
        page-break-inside: avoid;
    }
    
    .environment-image-container,
    .community-image-container {
        display: none;
    }
}

/* Safe Area Insets */
@supports (padding: max(0px)) {
    .carousel-slide-content,
    .stats-grid,
    .environment-grid,
    .attributes-grid,
    .community-grid,
    .hub-grid {
        padding-left: max(var(--spacing-md), env(safe-area-inset-left));
        padding-right: max(var(--spacing-md), env(safe-area-inset-right));
    }
}

/* Fix for text overflow */
.attribute-card,
.hub-card,
.badge-text,
.carousel-slide-title,
.carousel-slide-subtitle,
.environment-content h3,
.community-content h3,
.hub-card h3,
.attribute-card h3,
.section-title {
    overflow-wrap: break-word !important;
    word-wrap: break-word !important;
    hyphens: auto;
}

/* Screen reader only class */
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

/* Ensure all body text uses Open Sans */
p, 
li, 
span:not([class*="heading"]):not([class*="title"]):not([class*="btn"]),
.accreditation-text p,
.environment-content p,
.community-content p,
.hub-card p,
.attribute-card p,
.cta-description,
.section-subtitle,
.badge-text span,
.application-status-banner p,
.environment-features li,
.community-link {
    font-family: 'Open Sans', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif !important;
}

/* Ensure all headings use Montserrat */
h1, h2, h3, h4, h5, h6,
.carousel-slide-title,
.section-title,
.accreditation-text h3,
.environment-content h3,
.community-content h3,
.hub-card h3,
.attribute-card h3,
.cta-title,
.application-status-banner h3,
.stat-label,
.badge-text strong {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
}
</style>
</head>
<body>

<!-- Homepage Content -->
<main id="main-content" class="homepage-content" role="main">
    
    <!-- ========== HERO CAROUSEL - FULL WIDTH ========== -->
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
                        <a href="<?php echo $baseUrl; ?>/student-life" class="btn btn-secondary">
                            <i class="fas fa-users" aria-hidden="true"></i> Discover Community
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Dynamic Carousel - FULL WIDTH -->
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

    <!-- ========== APPLICATION STATUS BANNER ========== -->
    <div class="container">
        <div class="application-status-banner centralized-content">
            <h3><i class="fas fa-times-circle"></i> 2025/2026 Admissions Status</h3>
            <p>The application portal for the 2025/2026 academic session is now closed. Sales of forms period ended on 28th September 2025. No further applications are being accepted for this session.</p>
            <p><strong>Next Admissions Cycle:</strong> 2026/2027 academic session</p>
        </div>
    </div>

    <!-- ========== STATISTICS - FULL WIDTH CONTENT ========== -->
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
                    <div class="stat-label">Nursing Graduates</div>
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
                    <div class="stat-number">4</div>
                    <div class="stat-label">Academic Programs</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ACCREDITATION - FULL WIDTH CONTENT ========== -->
    <section class="accreditation-section" aria-label="Accreditation badges">
        <div class="container">
            <div class="accreditation-container">
                <div class="accreditation-content">
                    <div class="accreditation-text">
                        <h3>Nationally Recognized Accreditation</h3>
                        <p>Our programs meet the highest standards set by Nigeria's regulatory bodies for nursing education and technical education.</p>
                    </div>
                    <div class="accreditation-badges">
                        <div class="accreditation-badge">
                            <div class="badge-icon">
                                <i class="fas fa-stethoscope" aria-hidden="true"></i>
                            </div>
                            <div class="badge-text">
                                <strong>NMCN Approved</strong>
                                <span>Nursing & Midwifery Council of Nigeria</span>
                            </div>
                        </div>
                        <div class="accreditation-badge">
                            <div class="badge-icon">
                                <i class="fas fa-university" aria-hidden="true"></i>
                            </div>
                            <div class="badge-text">
                                <strong>NBTE Accredited</strong>
                                <span>National Board for Technical Education</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== LEARNING ENVIRONMENT SECTION ========== -->
    <section class="learning-environment-section" aria-label="Learning environment">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Learning Environment</h2>
                <p class="section-subtitle">
                    We provide state-of-the-art facilities and a supportive community designed to foster both academic excellence and personal growth.
                </p>
            </div>
            
            <div class="environment-grid">
                <div class="environment-card">
                    <!-- Your actual image: environment-simulation-lab.jpg -->
                    <div class="environment-image-container">
                        <div class="environment-image" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/homepage/environment-simulation-lab.jpg');"></div>
                        <div class="image-caption">Close-up of simulation equipment</div>
                    </div>
                    <div class="environment-content">
                        <h3>Advanced Simulation Labs</h3>
                        <p>Train with high-fidelity manikins, virtual reality simulations, and fully equipped clinical environments that mirror real healthcare settings.</p>
                        <ul class="environment-features">
                            <li>High-fidelity patient simulators</li>
                            <li>Virtual reality training modules</li>
                            <li>Maternity and pediatric labs</li>
                            <li>Emergency response simulation</li>
                            <li>24/7 access for practice sessions</li>
                        </ul>
                    </div>
                </div>
                
                <div class="environment-card">
                    <!-- Your actual image: environment-student-support.jpg -->
                    <div class="environment-image-container">
                        <div class="environment-image" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/homepage/environment-student-support.jpg');"></div>
                        <div class="image-caption">Students collaborating/being mentored</div>
                    </div>
                    <div class="environment-content">
                        <h3>Comprehensive Student Support</h3>
                        <p>From academic advising to wellness resources, we ensure every student has the support they need to thrive personally and professionally.</p>
                        <ul class="environment-features">
                            <li>Academic success coaching</li>
                            <li>Mental health and wellness services</li>
                            <li>Career counseling and placement</li>
                            <li>Peer mentoring programs</li>
                            <li>Learning resource center</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== GRADUATE ATTRIBUTES SECTION ========== -->
    <section class="graduate-attributes-section" aria-label="Graduate attributes">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">The FCTCNS Graduate</h2>
                <p class="section-subtitle">
                    Our graduates are distinguished by their clinical competence, ethical practice, and commitment to community health.
                </p>
            </div>
            
            <div class="attributes-grid">
                <div class="attribute-card">
                    <div class="attribute-icon">
                        <i class="fas fa-user-md" aria-hidden="true"></i>
                    </div>
                    <h3>Clinical Excellence</h3>
                    <p>Proficient in evidence-based practice with hands-on experience across diverse healthcare settings.</p>
                </div>
                
                <div class="attribute-card">
                    <div class="attribute-icon">
                        <i class="fas fa-brain" aria-hidden="true"></i>
                    </div>
                    <h3>Critical Thinking</h3>
                    <p>Skilled in clinical reasoning, problem-solving, and making sound decisions in complex situations.</p>
                </div>
                
                <div class="attribute-card">
                    <div class="attribute-icon">
                        <i class="fas fa-hands-helping" aria-hidden="true"></i>
                    </div>
                    <h3>Compassionate Care</h3>
                    <p>Deliver patient-centered care with empathy, respect, and cultural sensitivity.</p>
                </div>
                
                <div class="attribute-card">
                    <div class="attribute-icon">
                        <i class="fas fa-chart-line" aria-hidden="true"></i>
                    </div>
                    <h3>Leadership Ready</h3>
                    <p>Prepared to lead healthcare teams, advocate for patients, and drive quality improvement.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== CAMPUS & COMMUNITY SECTION ========== -->
    <section class="campus-community-section" aria-label="Campus and community">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Campus & Community Life</h2>
                <p class="section-subtitle">
                    Experience a vibrant campus community that extends learning beyond the classroom and into meaningful service.
                </p>
            </div>
            
            <div class="community-grid">
                <div class="community-card">
                    <!-- Your actual image: community-student-life.jpg -->
                    <div class="community-image-container">
                        <div class="community-image" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/homepage/community-student-life.jpg');"></div>
                        <div class="image-caption">Student group activities/clubs</div>
                    </div>
                    <div class="community-content">
                        <h3>Student Organizations</h3>
                        <p>Join nursing associations, community service groups, and professional development clubs that enhance your education and build lasting connections.</p>
                        <a href="<?php echo $baseUrl; ?>/student-life" class="community-link">
                            Explore Student Life <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                
                <div class="community-card">
                    <!-- Your actual image: community-outreach.jpg -->
                    <div class="community-image-container">
                        <div class="community-image" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/homepage/community-outreach.jpg');"></div>
                        <div class="image-caption">Community health events/volunteering</div>
                    </div>
                    <div class="community-content">
                        <h3>Community Outreach</h3>
                        <p>Participate in health screening campaigns, health education programs, and community partnerships that make a real difference in public health.</p>
                        <a href="<?php echo $baseUrl; ?>/community" class="community-link">
                            View Community Impact <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== NAVIGATION HUB SECTION ========== -->
    <section class="navigation-hub-section" aria-label="Next steps navigation">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Take Your Next Step</h2>
                <p class="section-subtitle">
                    Begin your journey toward a rewarding nursing career with these essential resources.
                </p>
            </div>
            
            <div class="hub-grid">
                <div class="hub-card">
                    <div class="hub-icon">
                        <i class="fas fa-book-medical" aria-hidden="true"></i>
                    </div>
                    <h3>Explore Programs</h3>
                    <p>Discover our accredited nursing programs and find the path that matches your career goals.</p>
                    <a href="<?php echo $baseUrl; ?>/programs" class="hub-link">
                        View Programs <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                
                <div class="hub-card">
                    <div class="hub-icon">
                        <i class="fas fa-calendar-check" aria-hidden="true"></i>
                    </div>
                    <h3>Plan Your Visit</h3>
                    <p>Schedule a campus tour to experience our facilities and meet with faculty and current students.</p>
                    <a href="<?php echo $baseUrl; ?>/visit" class="hub-link">
                        Schedule Tour <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                
                <div class="hub-card">
                    <div class="hub-icon">
                        <i class="fas fa-comments" aria-hidden="true"></i>
                    </div>
                    <h3>Contact Admissions</h3>
                    <p>Our admissions team is ready to answer your questions about requirements, deadlines, and next steps.</p>
                    <a href="<?php echo $baseUrl; ?>/contact" class="hub-link">
                        Get in Touch <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== CALL TO ACTION - FULL WIDTH ========== -->
    <section class="cta-section" aria-label="Call to action">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Ready to Start Your Nursing Journey?</h2>
                <p class="cta-description">
                    While the 2025/2026 admissions are closed, now is the perfect time to explore our programs, plan your visit, and prepare for the next admissions cycle in 2026/2027.
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo $baseUrl; ?>/programs" 
                       class="btn btn-primary">
                        <i class="fas fa-book-open" aria-hidden="true"></i> Explore Programs
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" 
                       class="btn btn-secondary">
                        <i class="fas fa-phone-alt" aria-hidden="true"></i> Contact Admissions
                    </a>
                    <a href="<?php echo $baseUrl; ?>/student-life" 
                       class="btn btn-secondary">
                        <i class="fas fa-users" aria-hidden="true"></i> Student Life
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
</script>

</body>
</html>