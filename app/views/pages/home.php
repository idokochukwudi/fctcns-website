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
            'subtitle' => 'Empowering Future Healthcare Professionals Since 1989',
            'button_text' => 'Apply Now',
            'button_link' => $baseUrl . '/admissions'
        ],
        [
            'image_path' => $baseUrl . '/assets/images/homepage/hero-simulation-lab.jpg',
            'title' => 'Train in Advanced Simulation Environments',
            'subtitle' => 'Practice with high-fidelity manikins and virtual reality in labs designed for real-world preparedness.',
            'button_text' => 'Apply Now',
            'button_link' => $baseUrl . '/admissions'
        ],
        [
            'image_path' => $baseUrl . '/assets/images/homepage/hero-graduation-celebration.jpg',
            'title' => 'Begin Your Journey to a Fulfilling Career',
            'subtitle' => 'Our graduates are highly sought-after for their skill, integrity, and readiness to lead in diverse healthcare settings.',
            'button_text' => 'Apply Now',
            'button_link' => $baseUrl . '/admissions'
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
   CRITICAL: NO GAP BETWEEN BODY AND HEADER & FOOTER
   ========================================================================== */
html, body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
}

/* Remove ALL top spacing from body content */
body > *:first-child {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Remove spacing from main wrapper */
#main-content,
.homepage-content {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
    max-width: 100vw;
}

/* Box sizing for all elements */
*, *::before, *::after {
    box-sizing: border-box;
}

/* ==========================================================================
   CRITICAL MOBILE ENHANCEMENTS & FIXES
   ========================================================================== */
* {
    -webkit-tap-highlight-color: transparent;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    margin: 0;
    padding: 0;
}

body {
    min-height: 100vh;
    background-color: #FFFFFF;
    font-family: 'Open Sans', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
    font-weight: 400;
    line-height: 1.6;
    color: #2D3748;
    width: 100%;
    max-width: 100vw;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
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
    --color-accent-very-light: #F2E4D4;
    
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
    --transition-smooth: all 0.3s ease;
}

/* ==========================================================================
   CRITICAL: FORCE HERO FULL WIDTH - OVERRIDE ALL CONSTRAINTS
   ========================================================================== */
.hero-section {
    position: relative;
    width: 100vw !important;
    max-width: 100vw !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    left: 50% !important;
    right: 50% !important;
    transform: translateX(-50%) !important;
    background: linear-gradient(135deg, #4A3A6F, #5D4A8A);
    overflow: hidden;
    padding: 0;
    border: none;
}

/* Ensure carousel is also full width */
.hero-carousel {
    position: relative;
    width: 100% !important;
    max-width: 100vw !important;
    height: 85vh;
    min-height: 550px;
    max-height: 800px;
    overflow: hidden;
    margin: 0;
    padding: 0;
    left: 0 !important;
    right: 0 !important;
}

/* Force homepage content to have no constraints */
.homepage-content {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    width: 100% !important;
    max-width: 100vw !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow-x: hidden;
}

/* Ensure main content doesn't constrain hero */
#main-content {
    width: 100% !important;
    max-width: 100vw !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow-x: hidden;
}

/* Remove any padding from main-content-wrapper for home page */
.main-content-wrapper .homepage-content {
    padding: 0 !important;
}

/* ==========================================================================
   SIMPLIFIED CAROUSEL DESIGN - NO UNNECESSARY ANIMATIONS
   ========================================================================== */
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
    transition: opacity 0.6s ease, visibility 0.6s;
    display: flex;
    align-items: center;
    justify-content: center;
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
    max-width: 100vw;
    /* NO ZOOM ANIMATION */
}

.carousel-slide-bg::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        to right,
        rgba(0, 0, 0, 0.7) 0%,
        rgba(0, 0, 0, 0.4) 50%,
        rgba(0, 0, 0, 0.2) 100%
    );
}

/* Carousel Content - FIXED TO NOT BE HIDDEN */
.carousel-slide-content {
    position: relative;
    z-index: 3;
    color: var(--color-white);
    max-width: 1200px;
    padding: var(--spacing-xl);
    margin: 0 auto;
    text-align: left;
    width: 95%;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    height: 100%;
    /* Ensure content is above controls */
    margin-bottom: 120px; /* Space for controls */
}

.carousel-slide-title {
    font-family: var(--font-heading);
    font-size: clamp(2rem, 5vw, 4rem);
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: var(--spacing-sm);
    color: var(--color-white);
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
    width: 100%;
    max-width: 800px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.carousel-slide-subtitle {
    font-size: clamp(1.1rem, 3vw, 1.5rem);
    font-weight: 400;
    margin-bottom: var(--spacing-xl);
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.95);
    font-family: var(--font-body);
    max-width: 700px;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
}

/* ==========================================================================
   SLIDER CONTROLS - SIMPLIFIED
   ========================================================================== */
.slider-controls {
    position: absolute;
    bottom: var(--spacing-xl);
    left: 0;
    right: 0;
    z-index: 4; /* Below content */
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-md);
    padding: 0 var(--spacing-xl);
}

/* Progress Bar */
.slider-controls__progress {
    width: 100%;
    max-width: 900px;
    height: 3px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 1.5px;
    overflow: hidden;
    position: relative;
}

.slider-controls__progress-active {
    height: 100%;
    background: var(--color-accent);
    width: 0%;
    transition: width 0.1s linear;
    position: relative;
    z-index: 2;
}

/* Controls Container */
.slider-controls__container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    max-width: 900px;
    gap: var(--spacing-md);
    background: rgba(0, 0, 0, 0.4);
    border-radius: var(--radius-lg);
    padding: var(--spacing-sm) var(--spacing-lg);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Button Group */
.slider-controls__button-group {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: nowrap;
}

/* Arrow Buttons - Simplified */
.slider-controls__arrows {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.slider-controls__arrow {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-white);
    font-size: 1.1rem;
    transition: var(--transition-smooth);
    position: relative;
}

.slider-controls__arrow.left::after,
.slider-controls__arrow.right::after {
    content: '';
    position: relative;
    width: 8px;
    height: 8px;
    border-style: solid;
    border-color: var(--color-white);
}

.slider-controls__arrow.left::after {
    border-width: 2px 0 0 2px;
    transform: rotate(-45deg) translate(1px, 1px);
    margin-right: 2px;
}

.slider-controls__arrow.right::after {
    border-width: 2px 2px 0 0;
    transform: rotate(45deg) translate(-1px, 1px);
    margin-left: 2px;
}

.slider-controls__arrow:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* ==========================================================================
   SIMPLIFIED BUTTON DESIGNS - NO UNNECESSARY ANIMATIONS
   ========================================================================== */

/* White Variant Button */
.button.white-variant {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: transparent;
    color: var(--color-white);
    border: 2px solid var(--color-white);
    padding: 0.9rem 2rem;
    font-family: var(--font-heading);
    font-size: 0.95rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition-smooth);
    text-decoration: none;
    min-height: 48px;
    white-space: nowrap;
}

.button.white-variant:hover {
    background: var(--color-white);
    color: var(--color-primary-dark);
    transform: translateY(-1px);
}

.button.white-variant .button__icon-wrapper {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.button.white-variant .button__icon-wrapper::after {
    content: '›';
    font-size: 1.4rem;
    font-weight: bold;
    line-height: 1;
}

.button.white-variant:hover .button__icon-wrapper::after {
    color: var(--color-primary-dark);
}

/* Gold Accent Button */
.button.gold-accent {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: var(--color-accent);
    color: var(--color-gray-900);
    border: 2px solid var(--color-accent);
    padding: 0.9rem 2rem;
    font-family: var(--font-heading);
    font-size: 0.95rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition-smooth);
    text-decoration: none;
    min-height: 48px;
    white-space: nowrap;
}

.button.gold-accent:hover {
    background: var(--color-accent-dark);
    border-color: var(--color-accent-dark);
    transform: translateY(-1px);
}

.button.gold-accent .button__icon-wrapper {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.button.gold-accent .button__icon-wrapper::after {
    content: '›';
    font-size: 1.4rem;
    font-weight: bold;
    line-height: 1;
    color: var(--color-gray-900);
}

.button.gold-accent:hover .button__icon-wrapper::after {
    color: var(--color-gray-900);
}

/* ==========================================================================
   APPLICATION STATUS BANNER
   ========================================================================== */
.application-status-banner {
    background: linear-gradient(135deg, var(--color-closed-light), var(--color-white));
    border-left: 5px solid var(--color-closed);
    padding: var(--spacing-md);
    margin: var(--spacing-lg) auto;
    border-radius: var(--radius-md);
    max-width: 800px;
    text-align: center;
    box-shadow: var(--shadow-soft);
    width: calc(100% - 2rem);
    position: relative;
    z-index: 5;
    box-sizing: border-box;
}

.application-status-banner h3 {
    color: var(--color-closed);
    margin-bottom: var(--spacing-xs);
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
    font-family: var(--font-heading);
}

.application-status-banner p {
    color: var(--color-gray-800);
    line-height: 1.5;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.application-status-banner strong {
    color: var(--color-primary);
}

/* ==========================================================================
   BUTTONS
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
    transition: var(--transition-smooth);
    border: 2px solid transparent;
    cursor: pointer;
    min-height: 44px;
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

/* ==========================================================================
   STATISTICS SECTION
   ========================================================================== */
.stats-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0;
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
    text-align: center;
    max-width: 1200px;
    margin: 0 auto;
}

.stat-item {
    padding: var(--spacing-lg);
    background: var(--color-off-white);
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
    transform: translateY(-3px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-light);
}

.stat-icon {
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

.stat-number {
    font-family: var(--font-heading);
    font-size: clamp(1.75rem, 4vw, 2.25rem);
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--color-primary);
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    color: var(--color-gray-800);
    font-weight: 500;
    line-height: 1.4;
    letter-spacing: 0.3px;
}

/* ==========================================================================
   ACCREDITATION SECTION
   ========================================================================== */
.accreditation-section {
    background: var(--color-off-white);
    padding: var(--spacing-xl) 0;
    border-top: 1px solid var(--color-gray-100);
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
}

.accreditation-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xl);
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
}

.accreditation-text {
    text-align: center;
    max-width: 800px;
    width: 100%;
    padding: 0 var(--spacing-md);
}

.accreditation-text h3 {
    font-family: var(--font-heading);
    font-size: clamp(1.5rem, 3.5vw, 2rem);
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
}

.accreditation-text p {
    font-size: 1.05rem;
    color: var(--color-gray-800);
    line-height: 1.6;
    font-weight: 400;
    margin-bottom: var(--spacing-lg);
    font-family: var(--font-body);
}

.accreditation-badges {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
    box-sizing: border-box;
}

.accreditation-badge {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-lg);
    background: var(--color-white);
    border-radius: var(--radius-lg);
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    width: 100%;
    max-width: 100%;
    overflow: hidden;
    box-sizing: border-box;
}

.accreditation-badge:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-light);
}

.badge-icon {
    font-size: 2rem;
    color: var(--color-primary);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    background: var(--color-primary-very-light);
    border-radius: 50%;
    min-width: 70px;
}

.badge-text {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
    overflow: hidden;
}

.badge-text strong {
    font-family: var(--font-heading);
    font-size: clamp(1.1rem, 2vw, 1.3rem);
    font-weight: 600;
    color: var(--color-primary);
    line-height: 1.3;
    margin-bottom: 0.25rem;
    word-wrap: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
}

.badge-text span {
    font-size: clamp(0.85rem, 1.8vw, 0.95rem);
    color: var(--color-gray-800);
    font-weight: 400;
    line-height: 1.4;
    font-family: var(--font-body);
    word-wrap: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
}

/* ==========================================================================
   PROGRAMS PREVIEW SECTION
   ========================================================================== */
.programs-preview-section {
    background: var(--color-white);
    padding: var(--spacing-xl) 0;
}

.programs-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-lg);
    max-width: 1200px;
    margin: 0 auto;
}

.program-card {
    background: var(--color-off-white);
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
    border-color: var(--color-primary-light);
}

.program-icon {
    font-size: 2.5rem;
    color: var(--color-primary);
    margin: var(--spacing-lg) auto var(--spacing-md);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: var(--color-white);
    border-radius: 50%;
    box-shadow: var(--shadow-subtle);
}

.program-content {
    padding: var(--spacing-lg);
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.program-content h3 {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
}

.program-content p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    font-size: 0.95rem;
    font-weight: 400;
    flex-grow: 1;
    font-family: var(--font-body);
}

.program-features {
    list-style: none;
    padding-left: 0;
    margin: var(--spacing-md) 0;
    text-align: left;
    max-width: 300px;
    margin-left: auto;
    margin-right: auto;
}

.program-features li {
    padding: 0.35rem 0;
    color: var(--color-gray-800);
    position: relative;
    padding-left: 1.5rem;
    font-size: 0.9rem;
    font-weight: 400;
    line-height: 1.5;
    font-family: var(--font-body);
}

.program-features li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--color-accent);
    font-weight: bold;
    font-size: 1rem;
}

/* ==========================================================================
   LEARNING ENVIRONMENT SECTION
   ========================================================================== */
.learning-environment-section {
    background: var(--color-off-white);
    padding: var(--spacing-xl) 0;
    border-top: 1px solid var(--color-gray-100);
}

.environment-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-xl);
    max-width: 1200px;
    margin: 0 auto;
}

.environment-card {
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

.environment-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-light);
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
}

.environment-content {
    padding: var(--spacing-lg);
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.environment-content h3 {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    line-height: 1.3;
}

.environment-content p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    font-size: 1rem;
    font-weight: 400;
    flex-grow: 1;
    font-family: var(--font-body);
}

/* ==========================================================================
   CTA SECTION
   ========================================================================== */
.cta-section {
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    padding: var(--spacing-xxl) 0;
    text-align: center;
    color: var(--color-white);
    margin-bottom: 0 !important;
    border-bottom: none !important;
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
}

.cta-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

.cta-title {
    font-family: var(--font-heading);
    font-size: clamp(1.75rem, 4vw, 2.5rem);
    font-weight: 700;
    margin-bottom: var(--spacing-md);
    line-height: 1.2;
    color: var(--color-white) !important;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
}

.cta-description {
    font-size: 1.1rem;
    margin-bottom: var(--spacing-xl);
    line-height: 1.6;
    font-weight: 400;
    color: rgba(255, 255, 255, 0.95);
    font-family: var(--font-body);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.cta-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    justify-content: center;
}

.cta-section .btn-primary {
    background: var(--color-accent);
    color: var(--color-gray-900) !important;
    border-color: var(--color-accent);
}

.cta-section .btn-primary:hover {
    background: var(--color-accent-dark);
    color: var(--color-gray-900) !important;
}

.cta-section .btn-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: var(--color-white);
    border-color: rgba(255, 255, 255, 0.3);
}

.cta-section .btn-secondary:hover {
    background: rgba(255, 255, 255, 0.25);
    color: var(--color-white);
}

/* ==========================================================================
   RESPONSIVE DESIGN
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
    
    .hero-carousel {
        height: 90vh;
        min-height: 600px;
    }
    
    /* Fixed: Text content has space above controls */
    .carousel-slide-content {
        margin-bottom: 140px;
    }
    
    .application-status-banner {
        max-width: 700px;
        padding: var(--spacing-lg);
    }
    
    .application-status-banner h3 {
        font-size: 1.3rem;
    }
    
    .application-status-banner p {
        font-size: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-lg);
    }
    
    .stat-item {
        padding: var(--spacing-xl);
    }
    
    .accreditation-badges {
        flex-direction: row;
        justify-content: center;
        max-width: 1000px;
        gap: var(--spacing-lg);
    }
    
    .accreditation-badge {
        flex: 1;
        min-width: 0;
        max-width: 400px;
    }
    
    .badge-text strong {
        font-size: clamp(1.1rem, 1.8vw, 1.3rem);
    }
    
    .badge-text span {
        font-size: clamp(0.85rem, 1.5vw, 0.95rem);
    }
    
    .programs-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-xl);
    }
    
    .environment-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-xl);
    }
    
    .carousel-slide-title {
        font-size: clamp(2rem, 5vw, 4rem);
    }
    
    .carousel-slide-subtitle {
        font-size: clamp(1.1rem, 3vw, 1.5rem);
    }
    
    .slider-controls {
        padding: 0 var(--spacing-xl);
        bottom: var(--spacing-xl);
        gap: var(--spacing-md);
    }
    
    .slider-controls__progress {
        max-width: 800px;
        height: 4px;
    }
    
    .slider-controls__container {
        max-width: 800px;
        padding: var(--spacing-md);
        gap: var(--spacing-md);
    }
    
    .slider-controls__button-group {
        gap: 0.75rem;
    }
    
    .button.white-variant,
    .button.gold-accent {
        padding: 0.85rem 1.75rem;
        font-size: 0.9rem;
        min-height: 46px;
    }
    
    .slider-controls__arrow {
        width: 42px;
        height: 42px;
    }
}

/* Desktop (1024px+) */
@media (min-width: 1024px) {
    .carousel-slide-content {
        padding: var(--spacing-xxl);
        margin-bottom: 160px;
    }
    
    .programs-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .environment-content {
        padding: var(--spacing-xl);
    }
    
    .hero-carousel {
        max-height: 850px;
    }
    
    .accreditation-badges {
        max-width: 1100px;
        gap: var(--spacing-xl);
    }
    
    .accreditation-badge {
        max-width: 450px;
    }
    
    .badge-text strong {
        font-size: 1.3rem;
    }
    
    .badge-text span {
        font-size: 0.95rem;
    }
    
    .slider-controls {
        bottom: var(--spacing-xxl);
    }
    
    .slider-controls__progress {
        max-width: 900px;
        height: 4px;
    }
    
    .slider-controls__container {
        max-width: 900px;
        padding: var(--spacing-md) var(--spacing-lg);
    }
    
    .slider-controls__button-group {
        gap: 1rem;
    }
    
    .button.white-variant,
    .button.gold-accent {
        padding: 1rem 2rem;
        font-size: 0.95rem;
        min-height: 50px;
    }
    
    .slider-controls__arrow {
        width: 44px;
        height: 44px;
    }
}

/* Mobile (767px and below) */
@media (max-width: 767px) {
    .hero-carousel {
        height: 80vh;
        min-height: 500px;
        width: 100vw;
        max-width: 100vw;
    }
    
    .hero-section {
        width: 100vw !important;
        max-width: 100vw !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
    }
    
    .carousel-slide-content {
        padding: var(--spacing-lg);
        text-align: center;
        align-items: center;
        margin-bottom: 160px; /* More space for stacked controls */
    }
    
    .carousel-slide-title {
        font-size: 1.8rem;
        line-height: 1.2;
        text-align: center;
    }
    
    .carousel-slide-subtitle {
        font-size: 1rem;
        line-height: 1.4;
        text-align: center;
    }
    
    /* Mobile controls - Stacked layout */
    .slider-controls {
        bottom: var(--spacing-lg);
        padding: 0 var(--spacing-md);
        gap: var(--spacing-md);
    }
    
    .slider-controls__container {
        flex-direction: column;
        gap: var(--spacing-md);
        align-items: stretch;
        padding: var(--spacing-md);
    }
    
    .slider-controls__button-group {
        order: 2;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        width: 100%;
    }
    
    .slider-controls__arrows {
        order: 1;
        justify-content: center;
        padding: 0.5rem;
        margin-bottom: var(--spacing-sm);
    }
    
    .slider-controls__progress {
        order: 3;
        margin-top: var(--spacing-sm);
        max-width: 100%;
        height: 3px;
    }
    
    .button.white-variant,
    .button.gold-accent {
        width: 100%;
        justify-content: center;
        padding: 0.85rem 1.5rem;
        font-size: 0.9rem;
        min-height: 46px;
    }
    
    .slider-controls__arrow {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .section {
        padding: var(--spacing-lg) 0;
        width: 100%;
        max-width: 100vw;
    }
    
    .cta-section {
        padding: var(--spacing-xl) 0;
        width: 100%;
        max-width: 100vw;
    }
    
    .application-status-banner {
        margin: var(--spacing-md) auto;
        width: calc(100% - 1.5rem);
    }
    
    .accreditation-badge {
        flex-direction: column;
        text-align: center;
        padding: var(--spacing-md);
        gap: var(--spacing-sm);
    }
    
    .badge-icon {
        margin: 0 auto;
    }
    
    .badge-text {
        align-items: center;
        text-align: center;
    }
}

/* Small mobile (480px and below) */
@media (max-width: 480px) {
    .hero-carousel {
        height: 75vh;
        min-height: 450px;
        width: 100vw;
        max-width: 100vw;
    }
    
    .hero-section {
        width: 100vw !important;
        max-width: 100vw !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
    }
    
    .carousel-slide-title {
        font-size: 1.5rem;
    }
    
    .carousel-slide-subtitle {
        font-size: 0.95rem;
    }
    
    .carousel-slide-content {
        margin-bottom: 180px; /* Even more space for very small screens */
    }
    
    .slider-controls {
        bottom: var(--spacing-md);
        padding: 0 var(--spacing-sm);
        gap: var(--spacing-sm);
    }
    
    .slider-controls__container {
        padding: var(--spacing-sm);
        gap: var(--spacing-sm);
    }
    
    .button.white-variant,
    .button.gold-accent {
        padding: 0.75rem 1.25rem;
        font-size: 0.85rem;
        min-height: 42px;
    }
    
    .slider-controls__arrow {
        width: 36px;
        height: 36px;
        font-size: 0.9rem;
    }
    
    .slider-controls__progress {
        height: 2px;
    }
    
    .stat-item {
        padding: var(--spacing-md);
    }
    
    .accreditation-badge {
        padding: var(--spacing-sm);
    }
    
    .badge-icon {
        width: 60px;
        height: 60px;
        font-size: 1.75rem;
        min-width: 60px;
    }
    
    .badge-text strong {
        font-size: 1rem;
    }
    
    .badge-text span {
        font-size: 0.8rem;
        line-height: 1.3;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-buttons .btn {
        width: 100%;
        max-width: 280px;
    }
    
    .application-status-banner {
        padding: var(--spacing-sm);
        margin: var(--spacing-md) auto;
        width: calc(100% - 1rem);
    }
    
    .application-status-banner h3 {
        font-size: 1rem;
    }
    
    .application-status-banner p {
        font-size: 0.85rem;
        line-height: 1.4;
    }
    
    .cta-title {
        font-size: 1.5rem;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.6);
    }
    
    .cta-description {
        font-size: 0.95rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
    }
}

/* Large desktop (1400px+) */
@media (min-width: 1400px) {
    .container {
        max-width: 1300px;
    }
    
    .application-status-banner {
        max-width: 750px;
    }
    
    .hero-carousel {
        max-height: 900px;
    }
    
    .carousel-slide-content {
        margin-bottom: 180px;
    }
    
    .accreditation-badges {
        max-width: 1200px;
    }
    
    .accreditation-badge {
        max-width: 500px;
    }
    
    .badge-text strong {
        font-size: 1.4rem;
    }
    
    .badge-text span {
        font-size: 1rem;
    }
    
    .slider-controls {
        bottom: var(--spacing-xxl);
    }
    
    .slider-controls__progress {
        max-width: 1000px;
        height: 4px;
    }
    
    .slider-controls__container {
        max-width: 1000px;
    }
    
    .slider-controls__button-group {
        gap: 1.25rem;
    }
    
    .button.white-variant,
    .button.gold-accent {
        padding: 1.1rem 2.25rem;
        font-size: 1rem;
        min-height: 54px;
    }
    
    .slider-controls__arrow {
        width: 46px;
        height: 46px;
    }
}

/* Landscape Orientation */
@media (max-height: 600px) and (orientation: landscape) {
    .hero-carousel {
        height: 100vh;
        min-height: 400px;
    }
    
    .carousel-slide-title {
        font-size: clamp(1.5rem, 3vw, 2.5rem);
    }
    
    .carousel-slide-subtitle {
        font-size: clamp(0.9rem, 2vw, 1.2rem);
        margin-bottom: var(--spacing-md);
    }
    
    .carousel-slide-content {
        margin-bottom: 140px;
    }
    
    .slider-controls {
        bottom: var(--spacing-md);
        gap: var(--spacing-sm);
    }
    
    .slider-controls__container {
        padding: var(--spacing-sm);
    }
    
    .button.white-variant,
    .button.gold-accent {
        padding: 0.7rem 1.5rem;
        min-height: 40px;
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

/* Print styles */
@media print {
    .hero-carousel,
    .carousel-nav,
    .slider-controls,
    .btn {
        display: none !important;
    }
    
    .section {
        page-break-inside: avoid;
    }
}
</style>
</head>
<body>

<!-- Homepage Content -->
<main id="main-content" class="homepage-content" role="main">
    
    <!-- ========== SIMPLIFIED HERO CAROUSEL ========== -->
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
            <!-- Simplified Carousel -->
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
                            <h1 class="carousel-slide-title">
                                <?php echo e($slide['title']); ?>
                            </h1>
                            <p class="carousel-slide-subtitle">
                                <?php echo e($slide['subtitle']); ?>
                            </p>
                            
                            <!-- CTA Button from slide -->
                            <?php if (!empty($slide['button_text']) && !empty($slide['button_link'])): ?>
                            <a href="<?php echo e($slide['button_link']); ?>" 
                               class="button gold-accent"
                               aria-label="<?php echo e($slide['button_text']); ?> - <?php echo e($slide['title']); ?>">
                                <div><?php echo e($slide['button_text']); ?></div>
                                <div class="button__icon-wrapper"></div>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Simplified Controls -->
                <div class="slider-controls">
                    <!-- Progress Bar -->
                    <div class="slider-controls__progress">
                        <div class="slider-controls__progress-active" id="carouselProgress"></div>
                    </div>
                    
                    <!-- Controls Container -->
                    <div class="slider-controls__container">
                        <!-- Arrows -->
                        <div class="slider-controls__arrows">
                            <div class="slider-controls__arrow left" 
                                 onclick="carouselController.prev()"
                                 aria-label="Previous slide"
                                 role="button"
                                 tabindex="0"></div>
                            <div class="slider-controls__arrow right" 
                                 onclick="carouselController.next()"
                                 aria-label="Next slide"
                                 role="button"
                                 tabindex="0"></div>
                        </div>
                        
                        <!-- Button Group -->
                        <div class="slider-controls__button-group">
                            <a href="<?php echo $baseUrl; ?>/programs" class="button white-variant">
                                <div>Explore Programs</div>
                                <div class="button__icon-wrapper"></div>
                            </a>
                            <a href="<?php echo $baseUrl; ?>/admissions" class="button gold-accent">
                                <div>Apply Now</div>
                                <div class="button__icon-wrapper"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <!-- ========== APPLICATION STATUS BANNER ========== -->
    <div class="container">
        <div class="application-status-banner">
            <h3><i class="fas fa-times-circle"></i> 2025/2026 Admissions Status</h3>
            <p>The application portal for the 2025/2026 academic session is now closed. Sales of forms period ended on 28th September 2025. No further applications are being accepted for this session.</p>
            <p><strong>Next Admissions Cycle:</strong> 2026/2027 academic session</p>
        </div>
    </div>

    <!-- ========== STATISTICS SECTION ========== -->
    <section class="stats-section" aria-label="College statistics">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Impact in Numbers</h2>
                <p class="section-subtitle">A legacy of excellence in nursing education since 1989</p>
            </div>
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

    <!-- ========== ACCREDITATION SECTION ========== -->
    <section class="accreditation-section" aria-label="Accreditation badges">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Nationally Recognized Accreditation</h2>
                <p class="section-subtitle">Our programs meet the highest standards set by Nigeria's regulatory bodies for nursing education</p>
            </div>
            
            <div class="accreditation-content">
                <div class="accreditation-text">
                    <h3>Quality Assured Education</h3>
                    <p>FCT College of Nursing Sciences maintains full accreditation with all relevant professional bodies, ensuring our graduates are prepared for successful nursing careers.</p>
                </div>
                
                <div class="accreditation-badges">
                    <div class="accreditation-badge">
                        <div class="badge-icon">
                            <i class="fas fa-stethoscope" aria-hidden="true"></i>
                        </div>
                        <div class="badge-text">
                            <strong>NMCN Approved</strong>
                            <span>Nursing & Midwifery Council of Nigeria - Full accreditation for all nursing programs</span>
                        </div>
                    </div>
                    <div class="accreditation-badge">
                        <div class="badge-icon">
                            <i class="fas fa-university" aria-hidden="true"></i>
                        </div>
                        <div class="badge-text">
                            <strong>NBTE Accredited</strong>
                            <span>National Board for Technical Education - Accreditation for technical programs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== PROGRAMS PREVIEW SECTION ========== -->
    <section class="programs-preview-section section-alt" aria-label="Academic programs">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Academic Programs</h2>
                <p class="section-subtitle">Choose from our range of accredited nursing programs designed to launch successful healthcare careers</p>
            </div>
            
            <div class="programs-grid">
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-user-nurse" aria-hidden="true"></i>
                    </div>
                    <div class="program-content">
                        <h3>Basic Nursing</h3>
                        <p>Comprehensive 3-year program covering fundamentals of nursing practice, patient care, and clinical skills.</p>
                        <ul class="program-features">
                            <li>3-year duration</li>
                            <li>Clinical rotations</li>
                            <li>NMCN accredited</li>
                        </ul>
                        <a href="<?php echo $baseUrl; ?>/programs#basic-nursing" class="btn btn-secondary" style="margin-top: auto;">
                            Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-baby" aria-hidden="true"></i>
                    </div>
                    <div class="program-content">
                        <h3>Midwifery</h3>
                        <p>Specialized program focusing on maternal and child health, antenatal care, and delivery procedures.</p>
                        <ul class="program-features">
                            <li>3-year duration</li>
                            <li>Maternity specialization</li>
                            <li>NMCN accredited</li>
                        </ul>
                        <a href="<?php echo $baseUrl; ?>/programs#midwifery" class="btn btn-secondary" style="margin-top: auto;">
                            Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-procedures" aria-hidden="true"></i>
                    </div>
                    <div class="program-content">
                        <h3>Post-Basic Nursing</h3>
                        <p>Advanced program for registered nurses seeking specialization in areas like anesthesia, critical care, or public health.</p>
                        <ul class="program-features">
                            <li>18-month duration</li>
                            <li>Specialization options</li>
                            <li>For registered nurses</li>
                        </ul>
                        <a href="<?php echo $baseUrl; ?>/programs#post-basic" class="btn btn-secondary" style="margin-top: auto;">
                            Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-heartbeat" aria-hidden="true"></i>
                    </div>
                    <div class="program-content">
                        <h3>Community Health</h3>
                        <p>Program focusing on public health, disease prevention, and community-based healthcare delivery.</p>
                        <ul class="program-features">
                            <li>3-year duration</li>
                            <li>Community focus</li>
                            <li>Public health emphasis</li>
                        </ul>
                        <a href="<?php echo $baseUrl; ?>/programs#community-health" class="btn btn-secondary" style="margin-top: auto;">
                            Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== LEARNING ENVIRONMENT SECTION ========== -->
    <section class="learning-environment-section" aria-label="Learning environment">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">State-of-the-Art Learning Facilities</h2>
                <p class="section-subtitle">Modern facilities designed to provide hands-on training and real-world experience</p>
            </div>
            
            <div class="environment-grid">
                <div class="environment-card">
                    <div class="environment-image-container">
                        <div class="environment-image" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/homepage/environment-simulation-lab.jpg');"></div>
                        <div class="image-caption">Advanced simulation laboratory</div>
                    </div>
                    <div class="environment-content">
                        <h3>Simulation Laboratories</h3>
                        <p>Train with high-fidelity manikins, virtual reality simulations, and fully equipped clinical environments that mirror real healthcare settings.</p>
                        <a href="<?php echo $baseUrl; ?>/facilities#labs" class="btn btn-secondary" style="margin-top: auto;">
                            View Facilities <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                
                <div class="environment-card">
                    <div class="environment-image-container">
                        <div class="environment-image" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/homepage/environment-student-support.jpg');"></div>
                        <div class="image-caption">Student support and collaboration spaces</div>
                    </div>
                    <div class="environment-content">
                        <h3>Learning Resources</h3>
                        <p>Access comprehensive learning materials, digital resources, and collaborative spaces designed to enhance your educational experience.</p>
                        <a href="<?php echo $baseUrl; ?>/facilities#resources" class="btn btn-secondary" style="margin-top: auto;">
                            Explore Resources <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FINAL CALL TO ACTION ========== -->
    <section class="cta-section" aria-label="Call to action">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Begin Your Nursing Journey Today</h2>
                <p class="cta-description">
                    While the 2025/2026 admissions are closed, now is the perfect time to explore our programs, learn about our campus, and prepare for the next admissions cycle.
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

<!-- ========== SIMPLIFIED CAROUSEL JAVASCRIPT ========== -->
<script>
(function() {
    'use strict';
    
    const carouselController = {
        currentSlide: 0,
        totalSlides: 0,
        autoPlayInterval: null,
        autoPlayDelay: 5000,
        progressInterval: null,
        progressDelay: 50,
        isTransitioning: false,
        progressBar: null,
        progressStartTime: null,
        
        init() {
            const carousel = document.getElementById('heroCarousel');
            if (!carousel) return;
            
            const slides = carousel.querySelectorAll('.carousel-slide');
            this.totalSlides = slides.length;
            
            if (this.totalSlides === 0) return;
            
            this.progressBar = document.getElementById('carouselProgress');
            
            // Initialize first slide
            slides[0].classList.add('active');
            slides[0].setAttribute('aria-hidden', 'false');
            
            // Start auto-play with progress
            this.startAutoPlay();
            
            // Pause on interaction
            carousel.addEventListener('mouseenter', () => {
                this.stopAutoPlay();
                this.stopProgress();
            });
            carousel.addEventListener('mouseleave', () => {
                this.startAutoPlay();
            });
            
            // Touch support
            this.addTouchSupport(carousel);
            
            // Keyboard navigation
            carousel.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    this.prev();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    this.next();
                }
            });
            
            // Initialize progress
            this.resetProgress();
        },
        
        goToSlide(index) {
            if (this.isTransitioning || index === this.currentSlide || index < 0 || index >= this.totalSlides) return;
            
            this.isTransitioning = true;
            
            const slides = document.querySelectorAll('.carousel-slide');
            
            // Hide current slide
            slides[this.currentSlide].classList.remove('active');
            slides[this.currentSlide].setAttribute('aria-hidden', 'true');
            
            // Show new slide
            slides[index].classList.add('active');
            slides[index].setAttribute('aria-hidden', 'false');
            
            this.currentSlide = index;
            
            // Reset progress
            this.resetProgress();
            
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
            
            this.startProgress();
        },
        
        stopAutoPlay() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
                this.autoPlayInterval = null;
            }
            this.stopProgress();
        },
        
        resetProgress() {
            if (this.progressBar) {
                this.progressBar.style.width = '0%';
            }
            this.progressStartTime = Date.now();
            this.stopProgress();
            this.startProgress();
        },
        
        startProgress() {
            if (this.progressInterval) {
                clearInterval(this.progressInterval);
            }
            
            if (!this.progressBar || !this.progressStartTime) {
                this.progressStartTime = Date.now();
            }
            
            this.progressInterval = setInterval(() => {
                if (this.isTransitioning || !this.progressBar) return;
                
                const elapsed = Date.now() - this.progressStartTime;
                const progress = Math.min((elapsed / this.autoPlayDelay) * 100, 100);
                
                this.progressBar.style.width = progress + '%';
                
                if (progress >= 100) {
                    this.stopProgress();
                }
            }, this.progressDelay);
        },
        
        stopProgress() {
            if (this.progressInterval) {
                clearInterval(this.progressInterval);
                this.progressInterval = null;
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
                
                setTimeout(() => {
                    this.startAutoPlay();
                }, 3000);
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
    
    // Handle visibility change
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            carouselController.stopAutoPlay();
        } else {
            carouselController.startAutoPlay();
        }
    });
})();
</script>

</body>
</html>