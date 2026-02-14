<?php
/**
 * Homepage View Template - Professional Redesign v2.0
 * Enhanced with premium design patterns and proper spacing
 * 
 * @package FCTCNS
 * @version 5.1 - Professional Spacing & Layout
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
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
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
    margin: 0;
    padding: 0;
}

/* ==========================================================================
   CRITICAL MOBILE ENHANCEMENTS & FIXES
   ========================================================================== */
* {
    -webkit-tap-highlight-color: transparent;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

body {
    min-height: 100vh;
    background-color: #FFFFFF;
    font-family: 'Outfit', 'Open Sans', 'Segoe UI', Roboto, sans-serif;
    font-weight: 400;
    line-height: 1.6;
    color: #2D3748;
    width: 100%;
    max-width: 100vw;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

/* ==========================================================================
   DESIGN TOKENS — Premium design system
   ========================================================================== */
:root {
    --ink:          #1A1F2E;
    --ink-mid:      #2A3042;
    --ink-soft:     #3A4055;
    --slate:        #5B677B;
    --mist:         #8E9AAC;
    --border:       #E9EDF2;
    --surface:      #F7F9FC;
    --white:        #FFFFFF;

    --purple:       #8B7BB8;
    --purple-dark:  #6D5C9E;
    --purple-light: #B2A4D4;
    --purple-pale:  #F3F0FA;

    --gold:         #C9A44A;
    --gold-light:   #D8B86C;
    --gold-pale:    #FDF8ED;

    --red:          #C0392B;
    --red-pale:     #FDF3F2;
    --green:        #5D9B8C;
    --green-pale:   #EEF7F5;

    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body:    'Outfit', system-ui, sans-serif;
    --font-mono:    'JetBrains Mono', monospace;

    --radius-sm:    6px;
    --radius-md:    12px;
    --radius-lg:    20px;
    --radius-xl:    28px;
    --radius-full:  9999px;

    --shadow-xs:    0 1px 3px rgba(0,0,0,0.04);
    --shadow-sm:    0 2px 8px rgba(0,0,0,0.05);
    --shadow-md:    0 6px 24px rgba(0,0,0,0.06);
    --shadow-lg:    0 16px 48px rgba(0,0,0,0.08);
    --shadow-xl:    0 32px 80px rgba(0,0,0,0.10);

    /* fluid gutter */
    --gutter:        clamp(1.25rem, 5vw, 6rem);
    --container-max: 1400px;
    
    /* Professional Spacing - Enhanced */
    --spacing-xs: 0.5rem;
    --spacing-sm: 0.875rem;
    --spacing-md: 1.25rem;
    --spacing-lg: 2rem;
    --spacing-xl: 3rem;
    --spacing-xxl: 4rem;
    --spacing-xxxl: 5rem;
    
    /* Touch Targets */
    --touch-target: 44px;
}

/* ==========================================================================
   CONTAINER
   ========================================================================== */
.container {
    width: 100%;
    max-width: var(--container-max);
    margin-left: auto;
    margin-right: auto;
    padding-left: var(--gutter);
    padding-right: var(--gutter);
}

/* ==========================================================================
   SECTION SPACING
   ========================================================================== */
.section {
    padding: var(--spacing-xxl) 0;
}

.section-sm {
    padding: var(--spacing-xl) 0;
}

.section-lg {
    padding: var(--spacing-xxxl) 0;
}

.section--alt {
    background: var(--surface);
}

.section--bordered {
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

/* ==========================================================================
   SECTION HEADER
   ========================================================================== */
.section-header {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: var(--spacing-xl);
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border);
    border-image: linear-gradient(90deg, var(--purple) 110px, var(--border) 110px) 1;
    text-align: center;
}

.section-header--center {
    border-image: none;
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: var(--spacing-lg);
}

.section-header--center::after {
    content: '';
    display: block;
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, var(--purple), var(--purple-light));
    border-radius: 2px;
    margin: var(--spacing-md) auto 0;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 3vw, 2.4rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
    line-height: 1.2;
}

.section-subtitle {
    font-size: clamp(1rem, 1.5vw, 1.2rem);
    color: var(--slate);
    line-height: 1.6;
    max-width: 700px;
    margin: 0 auto;
    font-weight: 400;
}

/* ==========================================================================
   BUTTONS
   ========================================================================== */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0.75rem 1.75rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.22s ease;
    letter-spacing: 0.01em;
    white-space: nowrap;
}

.btn--purple { background: var(--purple); color: white; }
.btn--purple:hover {
    background: var(--purple-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(139,123,184,0.32);
}

.btn--gold { background: var(--gold); color: white; }
.btn--gold:hover {
    background: var(--gold-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(201,164,74,0.3);
}

.btn--outline { 
    background: transparent; 
    color: var(--purple); 
    border: 1.5px solid var(--purple); 
}
.btn--outline:hover { 
    background: var(--purple); 
    color: white; 
    transform: translateY(-2px); 
}

.btn--surface { 
    background: var(--surface); 
    color: var(--ink-soft); 
    border: 1px solid var(--border); 
}
.btn--surface:hover { 
    background: var(--border); 
    color: var(--ink); 
}

.btn--lg { padding: 0.85rem 2rem; font-size: 1rem; }

/* Legacy button classes for compatibility */
.btn-primary {
    background: var(--gold);
    color: white;
    border: none;
}

.btn-primary:hover {
    background: var(--gold-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(201,164,74,0.3);
}

.btn-secondary {
    background: var(--purple);
    color: white;
    border: none;
}

.btn-secondary:hover {
    background: var(--purple-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(139,123,184,0.32);
}

.button.white-variant {
    background: transparent;
    color: white;
    border: 1.5px solid rgba(255,255,255,0.35);
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0.75rem 1.75rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.22s ease;
}

.button.white-variant:hover {
    border-color: white;
    background: rgba(255,255,255,0.1);
    transform: translateY(-2px);
}

.button.gold-accent {
    background: var(--gold);
    color: white;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0.75rem 1.75rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.22s ease;
}

.button.gold-accent:hover {
    background: var(--gold-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(201,164,74,0.3);
}

.button__icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.button__icon-wrapper::after {
    content: '›';
    font-size: 1.4rem;
    font-weight: bold;
    line-height: 1;
}

/* ==========================================================================
   HERO CAROUSEL
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
    background: linear-gradient(145deg, #2A2A42 0%, #383856 100%);
    overflow: hidden;
    padding: 0;
    border: none;
}

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
}

.carousel-slide-bg::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 70% 30%, rgba(139,123,184,0.18) 0%, transparent 60%);
    z-index: 1;
    pointer-events: none;
}

.carousel-content-container {
    position: relative;
    z-index: 3;
    width: 100%;
    max-width: var(--container-max);
    margin: 0 auto;
    padding: 0 var(--gutter);
    display: flex;
    align-items: center;
    justify-content: flex-start;
    height: 100%;
}

.carousel-content-wrapper {
    background: rgba(0, 0, 0, 0.35);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: var(--radius-lg);
    padding: clamp(2rem, 5vw, 3rem);
    max-width: 700px;
    width: 100%;
    box-shadow: var(--shadow-xl);
}

.carousel-slide-content {
    color: white;
}

.carousel-slide-title {
    font-family: var(--font-display);
    font-size: clamp(2.2rem, 5vw, 3.8rem);
    font-weight: 700;
    line-height: 1.1;
    color: white;
    margin-bottom: 1.25rem;
    letter-spacing: -0.01em;
    text-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.carousel-slide-subtitle {
    font-size: clamp(1rem, 2vw, 1.25rem);
    color: rgba(255,255,255,0.9);
    font-weight: 300;
    line-height: 1.6;
    margin-bottom: 2rem;
    max-width: 600px;
}

/* ==========================================================================
   SLIDER CONTROLS
   ========================================================================== */
.slider-controls {
    position: absolute;
    bottom: var(--spacing-xl);
    left: 0;
    right: 0;
    z-index: 4;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-md);
    padding: 0 var(--gutter);
}

.slider-controls__progress {
    width: 100%;
    max-width: 600px;
    height: 3px;
    background: rgba(255,255,255,0.2);
    border-radius: 3px;
    overflow: hidden;
}

.slider-controls__progress-active {
    height: 100%;
    background: var(--gold);
    width: 0%;
    transition: width 0.1s linear;
}

.slider-controls__container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-md);
    background: rgba(0,0,0,0.35);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: var(--radius-lg);
    padding: 0.75rem 1.5rem;
}

.slider-controls__arrows {
    display: flex;
    gap: 0.75rem;
}

.slider-controls__arrow {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.25);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.22s ease;
    position: relative;
}

.slider-controls__arrow:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.4);
    transform: scale(1.05);
}

.slider-controls__arrow.left::after {
    content: '';
    width: 12px;
    height: 12px;
    border-style: solid;
    border-color: white;
    border-width: 0 0 2px 2px;
    transform: rotate(45deg) translate(2px, -2px);
}

.slider-controls__arrow.right::after {
    content: '';
    width: 12px;
    height: 12px;
    border-style: solid;
    border-color: white;
    border-width: 2px 2px 0 0;
    transform: rotate(45deg) translate(-2px, 2px);
}

/* ==========================================================================
   APPLICATION STATUS BANNER
   ========================================================================== */
.application-status-banner {
    border-radius: var(--radius-lg);
    padding: clamp(1.5rem, 3vw, 2rem);
    border: 1px solid;
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
    background: var(--red-pale);
    border-color: rgba(192,57,43,0.2);
    border-left: 4px solid var(--red);
    margin: var(--spacing-xl) auto;
    max-width: 1000px;
}

.application-status-banner h3 {
    font-family: var(--font-display);
    font-size: 1.35rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: var(--red);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.application-status-banner p {
    font-size: 1rem;
    line-height: 1.7;
    color: var(--ink-soft);
    margin-bottom: 0.5rem;
}

.application-status-banner p:last-child {
    margin-bottom: 0;
}

.application-status-banner strong {
    color: var(--ink);
}

/* ==========================================================================
   STATISTICS SECTION
   ========================================================================== */
.stats-section {
    padding: var(--spacing-xxl) 0;
    background: var(--white);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-top: 2rem;
}

@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 2.5rem;
    }
}

.stat-item {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 2rem 1.5rem;
    text-align: center;
    transition: transform 0.28s ease, box-shadow 0.28s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: var(--shadow-sm);
}

.stat-item:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
    border-color: var(--purple-light);
}

.stat-icon {
    width: 64px;
    height: 64px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 1.25rem;
    transition: all 0.28s ease;
}

.stat-item:hover .stat-icon {
    background: var(--purple);
    color: white;
    transform: scale(1.1);
}

.stat-number {
    font-family: var(--font-display);
    font-size: clamp(2rem, 3vw, 2.5rem);
    font-weight: 700;
    color: var(--purple);
    line-height: 1.1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--slate);
    line-height: 1.4;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ==========================================================================
   ACCREDITATION SECTION - REDESIGNED WITH PROPER SPACING
   ========================================================================== */
.accreditation-section {
    padding: var(--spacing-xxl) 0;
    background: var(--surface);
}

.accreditation-content {
    max-width: 1000px;
    margin: 0 auto;
}

.accreditation-text {
    text-align: center;
    margin-bottom: var(--spacing-xl);
}

.accreditation-text h3 {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 1rem;
}

.accreditation-text p {
    font-size: 1.1rem;
    color: var(--slate);
    line-height: 1.7;
    max-width: 800px;
    margin: 0 auto;
}

.accreditation-badges {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    max-width: 800px;
    margin: 0 auto;
}

@media (min-width: 768px) {
    .accreditation-badges {
        flex-direction: row;
        gap: 2rem;
    }
}

.accreditation-badge {
    flex: 1;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: transform 0.28s ease, box-shadow 0.28s ease;
    box-shadow: var(--shadow-md);
}

.accreditation-badge:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-xl);
    border-color: var(--purple-light);
}

.badge-icon {
    width: 80px;
    height: 80px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-bottom: 1.5rem;
    transition: all 0.28s ease;
}

.accreditation-badge:hover .badge-icon {
    background: var(--purple);
    color: white;
    transform: scale(1.1);
}

.badge-text strong {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 0.75rem;
    display: block;
}

.badge-text span {
    font-size: 0.95rem;
    color: var(--slate);
    line-height: 1.6;
    display: block;
}

/* ==========================================================================
   PROGRAMS SECTION - REDESIGNED WITH PROPER SPACING
   ========================================================================== */
.programs-section {
    padding: var(--spacing-xxl) 0;
    background: var(--white);
}

.programs-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-top: 2.5rem;
}

@media (min-width: 768px) {
    .programs-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
}

@media (min-width: 1024px) {
    .programs-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
}

.program-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 2rem 1.5rem;
    transition: transform 0.28s ease, box-shadow 0.28s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-sm);
}

.program-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--purple), var(--purple-light));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.28s ease;
}

.program-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-xl);
    border-color: var(--purple-light);
}

.program-card:hover::before {
    transform: scaleX(1);
}

.program-icon {
    width: 64px;
    height: 64px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 1.5rem;
    transition: all 0.28s ease;
}

.program-card:hover .program-icon {
    background: var(--purple);
    color: white;
    transform: scale(1.1);
}

.program-card h3 {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 1rem;
    line-height: 1.3;
}

.program-card p {
    font-size: 0.95rem;
    color: var(--slate);
    line-height: 1.7;
    margin-bottom: 1.5rem;
    flex-grow: 1;
}

.program-features {
    list-style: none;
    margin: 0 0 1.5rem 0;
    padding: 0;
}

.program-features li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.5rem 0;
    font-size: 0.9rem;
    color: var(--slate);
    line-height: 1.5;
    border-bottom: 1px solid var(--border);
}

.program-features li:last-child {
    border-bottom: none;
}

.program-features li::before {
    content: '';
    width: 6px;
    height: 6px;
    background: var(--gold);
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 8px;
}

.program-card .btn {
    align-self: flex-start;
    margin-top: auto;
}

/* ==========================================================================
   ENVIRONMENT SECTION
   ========================================================================== */
.environment-section {
    padding: var(--spacing-xxl) 0;
    background: var(--surface);
}

.environment-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-top: 2.5rem;
}

@media (min-width: 768px) {
    .environment-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
}

.environment-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: transform 0.28s ease, box-shadow 0.28s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-md);
}

.environment-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-xl);
    border-color: var(--purple-light);
}

.environment-image-container {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.environment-image {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    transition: transform 0.5s ease;
}

.environment-card:hover .environment-image {
    transform: scale(1.08);
}

.image-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
    padding: 1.5rem 1rem 0.75rem;
    font-size: 0.85rem;
    font-family: var(--font-mono);
}

.environment-content {
    padding: 2rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.environment-content h3 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 1rem;
}

.environment-content p {
    font-size: 0.95rem;
    color: var(--slate);
    line-height: 1.7;
    margin-bottom: 1.5rem;
    flex-grow: 1;
}

/* ==========================================================================
   CTA SECTION
   ========================================================================== */
.cta-section {
    background: linear-gradient(160deg, #2A3042 0%, #3A4055 100%);
    border-radius: var(--radius-xl);
    padding: clamp(3rem, 6vw, 4rem);
    margin: var(--spacing-xxl) auto;
    max-width: 1200px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
}

.cta-section::before {
    content: '';
    position: absolute;
    left: 0;
    top: 15%;
    bottom: 15%;
    width: 4px;
    background: linear-gradient(to bottom, var(--purple-light), var(--purple));
    border-radius: 4px;
}

.cta-content {
    position: relative;
    z-index: 2;
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.cta-title {
    font-family: var(--font-display);
    font-size: clamp(2rem, 3.5vw, 2.5rem);
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
}

.cta-description {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.9);
    line-height: 1.7;
    margin: 1.5rem auto;
    max-width: 600px;
}

.cta-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

/* ==========================================================================
   ANIMATIONS
   ========================================================================== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-animate {
    animation: fadeInUp 0.5s ease forwards;
}

.stat-item,
.accreditation-badge,
.program-card,
.environment-card {
    opacity: 0;
    animation: fadeInUp 0.5s ease forwards;
}

.stat-item:nth-child(1) { animation-delay: 0.1s; }
.stat-item:nth-child(2) { animation-delay: 0.2s; }
.stat-item:nth-child(3) { animation-delay: 0.3s; }
.stat-item:nth-child(4) { animation-delay: 0.4s; }

.accreditation-badge:nth-child(1) { animation-delay: 0.2s; }
.accreditation-badge:nth-child(2) { animation-delay: 0.3s; }

.program-card:nth-child(1) { animation-delay: 0.1s; }
.program-card:nth-child(2) { animation-delay: 0.2s; }
.program-card:nth-child(3) { animation-delay: 0.3s; }
.program-card:nth-child(4) { animation-delay: 0.4s; }

.environment-card:nth-child(1) { animation-delay: 0.2s; }
.environment-card:nth-child(2) { animation-delay: 0.3s; }

@media (prefers-reduced-motion: reduce) {
    * {
        animation: none !important;
        transition: none !important;
    }
}

/* ==========================================================================
   RESPONSIVE DESIGN
   ========================================================================== */
@media (max-width: 768px) {
    :root {
        --gutter: 1rem;
        --spacing-xl: 2rem;
        --spacing-xxl: 3rem;
    }
    
    .hero-carousel {
        height: 70vh;
        min-height: 500px;
    }
    
    .carousel-content-wrapper {
        padding: 1.5rem;
    }
    
    .carousel-slide-title {
        font-size: 1.8rem;
    }
    
    .carousel-slide-subtitle {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .slider-controls {
        bottom: var(--spacing-lg);
    }
    
    .slider-controls__arrow {
        width: 40px;
        height: 40px;
    }
    
    .application-status-banner {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .accreditation-text h3 {
        font-size: 1.6rem;
    }
    
    .badge-text strong {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .hero-carousel {
        height: 65vh;
        min-height: 450px;
    }
    
    .carousel-content-wrapper {
        padding: 1.25rem;
    }
    
    .carousel-slide-title {
        font-size: 1.5rem;
    }
    
    .slider-controls__arrow {
        width: 36px;
        height: 36px;
    }
    
    .stat-item {
        padding: 1.5rem 1rem;
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        font-size: 1.4rem;
    }
    
    .stat-number {
        font-size: 1.8rem;
    }
    
    .badge-icon {
        width: 70px;
        height: 70px;
        font-size: 1.8rem;
    }
    
    .program-card {
        padding: 1.5rem;
    }
    
    .program-icon {
        width: 56px;
        height: 56px;
        font-size: 1.4rem;
    }
    
    .program-card h3 {
        font-size: 1.3rem;
    }
    
    .cta-title {
        font-size: 1.6rem;
    }
    
    .cta-description {
        font-size: 1rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-buttons .btn {
        width: 100%;
        max-width: 280px;
    }
}

/* ==========================================================================
   ACCESSIBILITY
   ========================================================================== */
:focus-visible {
    outline: 2px solid var(--gold);
    outline-offset: 2px;
    border-radius: var(--radius-sm);
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

/* Print styles */
@media print {
    .hero-section,
    .slider-controls,
    .btn,
    .button,
    .cta-section {
        display: none !important;
    }
    
    .card,
    .stat-item,
    .accreditation-badge,
    .program-card,
    .environment-card {
        box-shadow: none;
        border: 1px solid #ccc;
        break-inside: avoid;
        page-break-inside: avoid;
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
                </div>
            </div>
        <?php else: ?>
            <!-- Premium Carousel -->
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
                        <div class="carousel-content-container">
                            <div class="carousel-content-wrapper">
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
                                        <span><?php echo e($slide['button_text']); ?></span>
                                        <span class="button__icon-wrapper"></span>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Premium Controls -->
                <div class="slider-controls">
                    <!-- Progress Bar -->
                    <div class="slider-controls__progress">
                        <div class="slider-controls__progress-active" id="carouselProgress"></div>
                    </div>
                    
                    <!-- Controls Container -->
                    <div class="slider-controls__container">
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
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <!-- ========== APPLICATION STATUS BANNER ========== -->
    <div class="container">
        <div class="application-status-banner">
            <div style="flex-shrink:0;">
                <i class="fas fa-times-circle" style="color: var(--red); font-size: 2rem;"></i>
            </div>
            <div>
                <h3><i class="fas fa-times-circle" aria-hidden="true"></i> 2025/2026 Admissions Status</h3>
                <p>The application portal for the 2025/2026 academic session is now closed. Sales of forms period ended on 28th September 2025. No further applications are being accepted for this session.</p>
                <p><strong>Next Admissions Cycle:</strong> 2026/2027 academic session</p>
            </div>
        </div>
    </div>

    <!-- ========== STATISTICS SECTION ========== -->
    <section class="stats-section" aria-label="College statistics">
        <div class="container">
            <div class="section-header section-header--center">
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

    <!-- ========== ACCREDITATION SECTION - REDESIGNED ========== -->
    <section class="accreditation-section" aria-label="Accreditation badges">
        <div class="container">
            <div class="accreditation-content">
                <div class="section-header section-header--center">
                    <h2 class="section-title">Nationally Recognized Accreditation</h2>
                    <p class="section-subtitle">Our programs meet the highest standards set by Nigeria's regulatory bodies for nursing education</p>
                </div>
                
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

    <!-- ========== PROGRAMS SECTION - REDESIGNED ========== -->
    <section class="programs-section" aria-label="Academic programs">
        <div class="container">
            <div class="section-header section-header--center">
                <h2 class="section-title">Our Academic Programs</h2>
                <p class="section-subtitle">Choose from our range of accredited nursing programs designed to launch successful healthcare careers</p>
            </div>
            
            <div class="programs-grid">
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-user-nurse" aria-hidden="true"></i>
                    </div>
                    <h3>Basic Nursing</h3>
                    <p>Comprehensive 3-year program covering fundamentals of nursing practice, patient care, and clinical skills.</p>
                    <ul class="program-features">
                        <li>3-year duration</li>
                        <li>Clinical rotations</li>
                        <li>NMCN accredited</li>
                    </ul>
                    <a href="<?php echo $baseUrl; ?>/programs#basic-nursing" class="btn btn--outline">
                        Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-baby" aria-hidden="true"></i>
                    </div>
                    <h3>Midwifery</h3>
                    <p>Specialized program focusing on maternal and child health, antenatal care, and delivery procedures.</p>
                    <ul class="program-features">
                        <li>3-year duration</li>
                        <li>Maternity specialization</li>
                        <li>NMCN accredited</li>
                    </ul>
                    <a href="<?php echo $baseUrl; ?>/programs#midwifery" class="btn btn--outline">
                        Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-procedures" aria-hidden="true"></i>
                    </div>
                    <h3>Post-Basic Nursing</h3>
                    <p>Advanced program for registered nurses seeking specialization in areas like anesthesia, critical care, or public health.</p>
                    <ul class="program-features">
                        <li>18-month duration</li>
                        <li>Specialization options</li>
                        <li>For registered nurses</li>
                    </ul>
                    <a href="<?php echo $baseUrl; ?>/programs#post-basic" class="btn btn--outline">
                        Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                
                <div class="program-card">
                    <div class="program-icon">
                        <i class="fas fa-heartbeat" aria-hidden="true"></i>
                    </div>
                    <h3>Community Health</h3>
                    <p>Program focusing on public health, disease prevention, and community-based healthcare delivery.</p>
                    <ul class="program-features">
                        <li>3-year duration</li>
                        <li>Community focus</li>
                        <li>Public health emphasis</li>
                    </ul>
                    <a href="<?php echo $baseUrl; ?>/programs#community-health" class="btn btn--outline">
                        Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== LEARNING ENVIRONMENT SECTION ========== -->
    <section class="environment-section" aria-label="Learning environment">
        <div class="container">
            <div class="section-header section-header--center">
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
                        <a href="<?php echo $baseUrl; ?>/facilities#labs" class="btn btn--purple">
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
                        <a href="<?php echo $baseUrl; ?>/facilities#resources" class="btn btn--purple">
                            Explore Resources <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FINAL CALL TO ACTION ========== -->
    <div class="container">
        <section class="cta-section" aria-label="Call to action">
            <div class="cta-content">
                <h2 class="cta-title">Begin Your Nursing Journey Today</h2>
                <p class="cta-description">
                    While the 2025/2026 admissions are closed, now is the perfect time to explore our programs, learn about our campus, and prepare for the next admissions cycle.
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo $baseUrl; ?>/programs" 
                       class="btn btn--gold">
                        <i class="fas fa-book-open" aria-hidden="true"></i> Explore Programs
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" 
                       class="btn btn--outline">
                        <i class="fas fa-phone-alt" aria-hidden="true"></i> Contact Admissions
                    </a>
                    <a href="<?php echo $baseUrl; ?>/student-life" 
                       class="btn btn--outline">
                        <i class="fas fa-users" aria-hidden="true"></i> Student Life
                    </a>
                </div>
            </div>
        </section>
    </div>
</main>

<!-- ========== CAROUSEL JAVASCRIPT — Unchanged ========== -->
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