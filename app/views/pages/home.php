<?php
/**
 * Homepage View Template - Professional Redesign (Mature Light Purple Theme)
 * 
 * @package FCTCNS
 * @version 3.2
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e($page_description ?? 'FCT College of Nursing Sciences - NMCN & NBTE Accredited Nursing Education'); ?>">
    <title><?php echo e($page_title ?? 'FCT College of Nursing Sciences'); ?></title>
    
    <!-- Professional Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Open+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* CRITICAL FIX FOR HEADER SPACING */
body > main.main-content {
    margin-top: 0 !important;
}

.homepage-content {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.hero-section {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.hero-carousel {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Override any existing margins */
*[style*="margin-top"], 
*[style*="padding-top"] {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Rest of your existing homepage CSS here... */
</style>
<style>
/* ==========================================================================
   CSS RESET & GLOBAL VARIABLES - MATURE TRANSPARENT PURPLE
   ========================================================================== */
:root {
    /* Mature Transparent Purple Color Palette */
    --color-primary: rgba(107, 78, 155, 0.9);           /* Semi-transparent purple */
    --color-primary-dark: rgba(90, 65, 133, 0.9);
    --color-primary-light: rgba(123, 92, 174, 0.8);
    --color-primary-very-light: rgba(240, 235, 247, 0.6);
    --color-primary-transparent: rgba(107, 78, 155, 0.1);
    
    --color-secondary: rgba(74, 144, 226, 0.9);         /* Transparent blue */
    --color-secondary-dark: rgba(58, 123, 200, 0.9);
    
    --color-accent: rgba(255, 126, 95, 0.9);            /* Transparent coral */
    --color-accent-dark: rgba(229, 106, 74, 0.9);
    
    /* Neutral Colors */
    --color-white: rgba(255, 255, 255, 0.95);
    --color-white-solid: #ffffff;
    --color-gray-50: rgba(248, 249, 250, 0.8);
    --color-gray-100: rgba(241, 243, 244, 0.8);
    --color-gray-200: rgba(233, 236, 239, 0.6);
    --color-gray-800: rgba(52, 58, 64, 0.9);
    --color-gray-900: rgba(33, 37, 41, 0.9);
    
    /* Typography - Refined */
    --font-heading: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
    --font-body: 'Open Sans', 'Segoe UI', Roboto, sans-serif;
    
    /* Spacing */
    --spacing-xs: 0.5rem;
    --spacing-sm: 1rem;
    --spacing-md: 1.5rem;
    --spacing-lg: 2rem;
    --spacing-xl: 2.5rem;
    --spacing-xxl: 3.5rem;
    
    /* Shadows - Subtle */
    --shadow-sm: 0 1px 3px rgba(107, 78, 155, 0.08);
    --shadow-md: 0 3px 10px rgba(107, 78, 155, 0.12);
    --shadow-lg: 0 8px 25px rgba(107, 78, 155, 0.15);
    
    /* Border Radius */
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 12px;
    
    /* Transitions */
    --transition-fast: all 0.2s ease;
    --transition-base: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: var(--font-body);
    font-size: 15px;
    line-height: 1.6;
    color: var(--color-gray-800);
    background-color: var(--color-white-solid);
    overflow-x: hidden;
}

.container {
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 var(--spacing-sm);
}

/* ==========================================================================
   HERO CAROUSEL - FIXED DISPLAY
   ========================================================================== */
.hero-section {
    position: relative;
    width: 100%;
    margin-top: 0;
    padding-top: 0;
}

.hero-carousel {
    position: relative;
    width: 100%;
    height: 75vh;
    max-height: 600px;
    min-height: 450px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.3), rgba(123, 92, 174, 0.2));
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

/* Transparent overlay for text readability */
.carousel-slide-bg::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg, 
        rgba(0, 0, 0, 0.5) 0%, 
        rgba(0, 0, 0, 0.3) 40%, 
        rgba(0, 0, 0, 0.2) 100%
    );
}

/* Carousel content with transparent background */
.carousel-slide-content {
    position: relative;
    z-index: 3;
    color: var(--color-white-solid);
    max-width: 680px;
    padding: var(--spacing-lg);
    margin-left: 8%;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(8px);
    border-radius: var(--radius-md);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.carousel-slide-badge {
    display: inline-block;
    background: rgba(255, 126, 95, 0.9);
    color: var(--color-white-solid);
    padding: 0.4rem 1rem;
    border-radius: var(--radius-sm);
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: var(--spacing-md);
}

.carousel-slide-title {
    font-family: var(--font-heading);
    font-size: 2.5rem;
    font-weight: 600;
    line-height: 1.2;
    margin-bottom: var(--spacing-sm);
    color: var(--color-white-solid);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.carousel-slide-subtitle {
    font-size: 1.2rem;
    font-weight: 400;
    margin-bottom: var(--spacing-lg);
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.9);
    font-family: var(--font-body);
}

.carousel-slide-cta {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-lg);
}

/* ==========================================================================
   BUTTONS - REFINED DESIGN
   ========================================================================== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.7rem 1.5rem;
    font-family: var(--font-heading);
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    border-radius: var(--radius-md);
    transition: var(--transition-base);
    border: 1px solid transparent;
    cursor: pointer;
    min-height: 44px;
}

.btn-primary {
    background: rgba(255, 126, 95, 0.9);
    color: var(--color-white-solid);
    border-color: rgba(255, 126, 95, 0.9);
}

.btn-primary:hover, .btn-primary:focus {
    background: rgba(229, 106, 74, 0.95);
    color: var(--color-white-solid);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: var(--color-white-solid);
    border-color: rgba(255, 255, 255, 0.3);
}

.btn-secondary:hover, .btn-secondary:focus {
    background: rgba(255, 255, 255, 0.25);
    color: var(--color-white-solid);
    transform: translateY(-2px);
}

/* ==========================================================================
   STATISTICS SECTION - TRANSPARENT DESIGN
   ========================================================================== */
.stats-section {
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.05), rgba(123, 92, 174, 0.03));
    padding: var(--spacing-xl) 0;
    position: relative;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
    text-align: center;
}

.stat-item {
    padding: var(--spacing-lg);
    background: rgba(255, 255, 255, 0.7);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    transition: var(--transition-base);
    border: 1px solid rgba(107, 78, 155, 0.1);
    backdrop-filter: blur(5px);
}

.stat-item:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.9);
    box-shadow: var(--shadow-md);
    border-color: rgba(107, 78, 155, 0.2);
}

.stat-icon {
    font-size: 2.2rem;
    color: rgba(107, 78, 155, 0.9);
    margin-bottom: var(--spacing-sm);
}

.stat-number {
    font-family: var(--font-heading);
    font-size: 2.2rem;
    font-weight: 600;
    margin-bottom: 0.3rem;
    color: rgba(107, 78, 155, 0.9);
}

.stat-label {
    font-size: 0.95rem;
    color: rgba(52, 58, 64, 0.8);
    font-weight: 500;
}

/* ==========================================================================
   ACCREDITATION SECTION - REFINED
   ========================================================================== */
.accreditation-section {
    background: rgba(240, 235, 247, 0.4);
    padding: var(--spacing-xl) 0;
    position: relative;
}

.accreditation-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: var(--spacing-lg);
}

.accreditation-text {
    flex: 1;
    min-width: 280px;
}

.accreditation-text h3 {
    font-family: var(--font-heading);
    font-size: 1.6rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: rgba(107, 78, 155, 0.9);
}

.accreditation-text p {
    font-size: 1rem;
    color: rgba(52, 58, 64, 0.8);
    line-height: 1.6;
}

.accreditation-badges {
    display: flex;
    gap: var(--spacing-md);
    flex-wrap: wrap;
}

.accreditation-badge {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-md);
    background: rgba(255, 255, 255, 0.8);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    transition: var(--transition-base);
    border: 1px solid rgba(107, 78, 155, 0.15);
    backdrop-filter: blur(5px);
}

.accreditation-badge:hover {
    transform: translateY(-3px);
    background: rgba(255, 255, 255, 0.95);
    box-shadow: var(--shadow-md);
    border-color: rgba(107, 78, 155, 0.3);
}

.badge-icon {
    font-size: 1.8rem;
    color: rgba(107, 78, 155, 0.9);
}

.badge-text {
    display: flex;
    flex-direction: column;
}

.badge-text strong {
    font-family: var(--font-heading);
    font-size: 1.1rem;
    font-weight: 600;
    color: rgba(107, 78, 155, 0.9);
}

.badge-text span {
    font-size: 0.85rem;
    color: rgba(108, 117, 125, 0.8);
    font-weight: 400;
}

/* ==========================================================================
   PROGRAMS SECTION - REFINED CARDS
   ========================================================================== */
.programs-section {
    padding: var(--spacing-xl) 0;
    background: var(--color-white-solid);
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
    font-size: 2rem;
    font-weight: 600;
    color: rgba(107, 78, 155, 0.9);
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
    width: 60px;
    height: 3px;
    background: rgba(255, 126, 95, 0.9);
    border-radius: 2px;
}

.section-subtitle {
    font-size: 1.1rem;
    color: rgba(52, 58, 64, 0.8);
    line-height: 1.6;
    margin-top: var(--spacing-lg);
    font-weight: 400;
}

/* Program Cards - Refined, less bold */
.program-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-lg);
}

.program-card {
    background: var(--color-white-solid);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-base);
    border: 1px solid rgba(233, 236, 239, 0.8);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.program-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-md);
    border-color: rgba(107, 78, 155, 0.3);
}

.program-card-header {
    padding: var(--spacing-lg);
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.9), rgba(90, 65, 133, 0.9));
    color: var(--color-white-solid);
    position: relative;
}

.program-card-accreditation {
    position: absolute;
    top: var(--spacing-md);
    right: var(--spacing-md);
    background: rgba(255, 255, 255, 0.95);
    color: rgba(107, 78, 155, 0.9);
    padding: 0.3rem 0.8rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.program-card-title {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: var(--spacing-xs);
    color: var(--color-white-solid);
    line-height: 1.3;
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
    color: rgba(52, 58, 64, 0.8);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    font-size: 0.95rem;
    flex-grow: 1;
}

.program-card-highlights {
    margin-bottom: var(--spacing-md);
    background: rgba(240, 235, 247, 0.4);
    padding: var(--spacing-md);
    border-radius: var(--radius-sm);
    border-left: 3px solid rgba(107, 78, 155, 0.6);
}

.highlight-title {
    font-family: var(--font-heading);
    font-size: 1rem;
    font-weight: 600;
    color: rgba(107, 78, 155, 0.9);
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
    padding: 0.3rem 0;
    color: rgba(52, 58, 64, 0.8);
    position: relative;
    padding-left: 1.3rem;
    font-size: 0.9rem;
    font-weight: 400;
}

.highlight-list li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: rgba(255, 126, 95, 0.9);
    font-weight: bold;
    font-size: 0.95rem;
}

.program-card-footer {
    padding: var(--spacing-md);
    border-top: 1px solid rgba(233, 236, 239, 0.8);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.program-card-link {
    font-family: var(--font-heading);
    color: rgba(107, 78, 155, 0.9);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-fast);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
}

.program-card-link:hover {
    color: rgba(90, 65, 133, 0.9);
    gap: 0.7rem;
}

.program-card-apply {
    background: rgba(255, 126, 95, 0.9);
    color: var(--color-white-solid);
    padding: 0.5rem 1.2rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-heading);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-base);
    border: 1px solid rgba(255, 126, 95, 0.9);
    font-size: 0.9rem;
}

.program-card-apply:hover {
    background: rgba(229, 106, 74, 0.95);
    color: var(--color-white-solid);
    transform: translateY(-2px);
}

/* ==========================================================================
   CALL TO ACTION - TRANSPARENT
   ========================================================================== */
.cta-section {
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.08), rgba(123, 92, 174, 0.05));
    padding: var(--spacing-xl) 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, rgba(255, 126, 95, 0.9), rgba(107, 78, 155, 0.9));
}

.cta-content {
    max-width: 700px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.cta-title {
    font-family: var(--font-heading);
    font-size: 2rem;
    font-weight: 600;
    color: rgba(107, 78, 155, 0.9);
    margin-bottom: var(--spacing-md);
}

.cta-description {
    font-size: 1.1rem;
    color: rgba(52, 58, 64, 0.8);
    margin-bottom: var(--spacing-lg);
    line-height: 1.6;
    font-weight: 400;
}

.cta-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

/* ==========================================================================
   CAROUSEL CONTROLS - FIXED (NO PAUSE BUTTON)
   ========================================================================== */
.carousel-controls {
    position: absolute;
    bottom: var(--spacing-lg);
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    gap: var(--spacing-xs);
}

.carousel-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    border: none;
    cursor: pointer;
    transition: var(--transition-fast);
    padding: 0;
}

.carousel-indicator.active {
    background: var(--color-white-solid);
    transform: scale(1.2);
}

.carousel-indicator:hover {
    background: rgba(255, 255, 255, 0.7);
}

.carousel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.1);
    border: none;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition-base);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-white-solid);
    font-size: 1.2rem;
    z-index: 10;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.carousel-nav:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-50%) scale(1.1);
}

.carousel-nav-prev {
    left: var(--spacing-md);
}

.carousel-nav-next {
    right: var(--spacing-md);
}

/* REMOVED: .carousel-pause-btn - Play/Pause button removed */

/* Fallback carousel */
.carousel-fallback {
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.3), rgba(123, 92, 174, 0.2));
    height: 75vh;
    max-height: 600px;
    min-height: 450px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: var(--spacing-lg);
}

.carousel-fallback-content {
    max-width: 600px;
    color: rgba(52, 58, 64, 0.9);
    background: rgba(255, 255, 255, 0.9);
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    backdrop-filter: blur(10px);
}

.carousel-fallback h1 {
    font-family: var(--font-heading);
    font-size: 2.2rem;
    margin-bottom: var(--spacing-md);
    color: rgba(107, 78, 155, 0.9);
    line-height: 1.2;
}

.carousel-fallback p {
    font-size: 1.2rem;
    margin-bottom: var(--spacing-lg);
    color: rgba(52, 58, 64, 0.8);
}

/* ==========================================================================
   RESPONSIVE DESIGN
   ========================================================================== */
@media (max-width: 1024px) {
    .carousel-slide-title {
        font-size: 2.2rem;
    }
    
    .carousel-slide-content {
        margin-left: 5%;
    }
    
    .program-cards-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
}

@media (max-width: 768px) {
    .hero-carousel {
        height: 65vh;
        min-height: 400px;
    }
    
    .carousel-slide-content {
        margin: 0 auto;
        padding: var(--spacing-md);
        text-align: center;
        max-width: 90%;
        background: rgba(0, 0, 0, 0.4);
    }
    
    .carousel-slide-title {
        font-size: 1.8rem;
    }
    
    .carousel-slide-subtitle {
        font-size: 1.1rem;
    }
    
    .carousel-nav {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
    }
    
    .carousel-nav-prev {
        left: var(--spacing-sm);
    }
    
    .carousel-nav-next {
        right: var(--spacing-sm);
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .program-cards-grid {
        grid-template-columns: 1fr;
    }
    
    .accreditation-content {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }
    
    .cta-title {
        font-size: 1.8rem;
    }
    
    .cta-buttons {
        gap: var(--spacing-sm);
    }
    
    .btn {
        font-size: 0.9rem;
        padding: 0.6rem 1.2rem;
    }
}

@media (max-width: 576px) {
    .hero-carousel {
        height: 55vh;
        min-height: 350px;
    }
    
    .carousel-slide-title {
        font-size: 1.6rem;
    }
    
    .carousel-slide-subtitle {
        font-size: 1rem;
    }
    
    .carousel-slide-cta {
        flex-direction: column;
        align-items: center;
        width: 100%;
    }
    
    .btn {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }
    
    .section-title {
        font-size: 1.6rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .accreditation-badges {
        flex-direction: column;
        width: 100%;
    }
    
    .accreditation-badge {
        width: 100%;
        justify-content: center;
    }
    
    .program-card-title {
        font-size: 1.3rem;
    }
    
    .program-card-accreditation {
        position: static;
        margin-bottom: var(--spacing-sm);
        display: inline-block;
    }
    
    .program-card-footer {
        flex-direction: column;
        gap: var(--spacing-sm);
        align-items: stretch;
    }
    
    .program-card-link, .program-card-apply {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-title {
        font-size: 1.6rem;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

:focus {
    outline: 2px solid rgba(255, 126, 95, 0.9);
    outline-offset: 2px;
}
</style>
</head>
<body>

<!-- Homepage Content -->
<main id="main-content" class="homepage-content">
    
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
                            <i class="fas fa-book-open"></i> Explore Programs
                        </a>
                        <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-secondary">
                            <i class="fas fa-file-alt"></i> Apply Now
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Dynamic Carousel - FIXED DISPLAY -->
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
                                    <i class="fas fa-arrow-right"></i> <?php echo e($slide['button_text']); ?>
                                </a>
                                <?php endif; ?>
                                <a href="<?php echo $baseUrl; ?>/programs" class="btn btn-secondary">
                                    <i class="fas fa-book"></i> All Programs
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Navigation Arrows -->
                <button class="carousel-nav carousel-nav-prev" 
                        aria-label="Previous slide"
                        onclick="carouselController.prev()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-nav carousel-nav-next" 
                        aria-label="Next slide"
                        onclick="carouselController.next()">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Indicators Only (No pause button) -->
                <div class="carousel-controls">
                    <?php foreach ($carouselSlides as $index => $slide): ?>
                    <button class="carousel-indicator <?php echo $index === 0 ? 'active' : ''; ?>"
                            data-slide="<?php echo $index; ?>"
                            aria-label="Go to slide <?php echo $index + 1; ?>"
                            onclick="carouselController.goToSlide(<?php echo $index; ?>)"
                            aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>">
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
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-number">35+</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-number">5,000+</div>
                    <div class="stat-label">Graduates</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-number">100%</div>
                    <div class="stat-label">NMCN Accredited</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Expert Faculty</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ACCREDITATION ========== -->
    <section class="accreditation-section" aria-label="Accreditation badges">
        <div class="container">
            <div class="accreditation-content">
                <div class="accreditation-text">
                    <h3>Nationally Recognized Accreditation</h3>
                    <p>Our programs meet the highest standards set by Nigeria's regulatory bodies for nursing education.</p>
                </div>
                <div class="accreditation-badges">
                    <div class="accreditation-badge">
                        <div class="badge-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div class="badge-text">
                            <strong>NMCN</strong>
                            <span>Nursing & Midwifery Council of Nigeria</span>
                        </div>
                    </div>
                    <div class="accreditation-badge">
                        <div class="badge-icon">
                            <i class="fas fa-university"></i>
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
                            <i class="far fa-clock"></i> Duration: 3 Years
                        </div>
                    </div>
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Comprehensive general nursing education preparing students for registration as Registered Nurses (RN). 
                            Includes medical, surgical, pediatric, and community nursing.
                        </p>
                        <div class="program-card-highlights">
                            <div class="highlight-title">
                                <i class="fas fa-star"></i> Key Features
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
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="<?php echo $baseUrl; ?>/admissions" class="program-card-apply">
                            Apply Now
                        </a>
                    </div>
                </article>
                
                <!-- National Diploma -->
                <article class="program-card">
                    <div class="program-card-header">
                        <span class="program-card-accreditation">NBTE</span>
                        <h3 class="program-card-title">National Diploma in Nursing</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock"></i> Duration: 2 Years
                        </div>
                    </div>
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Polytechnic-based nursing education leading to ND qualification. Combines theoretical knowledge 
                            with practical skills for healthcare delivery.
                        </p>
                        <div class="program-card-highlights">
                            <div class="highlight-title">
                                <i class="fas fa-star"></i> Key Features
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
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="<?php echo $baseUrl; ?>/admissions" class="program-card-apply">
                            Apply Now
                        </a>
                    </div>
                </article>
                
                <!-- Higher National Diploma -->
                <article class="program-card">
                    <div class="program-card-header">
                        <span class="program-card-accreditation">NBTE</span>
                        <h3 class="program-card-title">Higher National Diploma in Nursing</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock"></i> Duration: 2 Years
                        </div>
                    </div>
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Advanced nursing education for Registered Nurses or ND holders. Focus on nursing administration, 
                            education, and specialized clinical practice.
                        </p>
                        <div class="program-card-highlights">
                            <div class="highlight-title">
                                <i class="fas fa-star"></i> Key Features
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
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="<?php echo $baseUrl; ?>/admissions" class="program-card-apply">
                            Apply Now
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
                        <i class="fas fa-file-alt"></i> Apply Now
                    </a>
                    <a href="<?php echo $baseUrl; ?>/programs" 
                       class="btn btn-secondary">
                        <i class="fas fa-book-open"></i> View Programs
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" 
                       class="btn btn-secondary">
                        <i class="fas fa-phone-alt"></i> Contact
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
        autoPlayDelay: 5000, // 5 seconds between slides
        isTransitioning: false,
        
        init() {
            const carousel = document.getElementById('heroCarousel');
            if (!carousel) {
                console.log('Carousel element not found');
                return;
            }
            
            const slides = carousel.querySelectorAll('.carousel-slide');
            this.totalSlides = slides.length;
            
            console.log(`Found ${this.totalSlides} carousel slides`);
            
            if (this.totalSlides === 0) {
                console.log('No carousel slides found, using fallback');
                return;
            }
            
            // Initialize first slide
            slides[0].classList.add('active');
            slides[0].setAttribute('aria-hidden', 'false');
            
            // Initialize indicators
            const indicators = carousel.querySelectorAll('.carousel-indicator');
            if (indicators.length > 0) {
                indicators[0].classList.add('active');
                indicators[0].setAttribute('aria-current', 'true');
            }
            
            // Start auto-play immediately
            this.startAutoPlay();
            
            // Pause on hover (optional - you can remove this if you want continuous autoplay)
            carousel.addEventListener('mouseenter', () => this.stopAutoPlay());
            carousel.addEventListener('mouseleave', () => this.startAutoPlay());
            
            // Keyboard navigation
            carousel.addEventListener('keydown', (e) => this.handleKeyboard(e));
            
            // Touch support
            this.addTouchSupport(carousel);
            
            console.log('Carousel initialized with auto-play');
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
            // Clear any existing interval
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
            }
            
            // Start new interval
            this.autoPlayInterval = setInterval(() => {
                if (!this.isTransitioning) {
                    this.next();
                }
            }, this.autoPlayDelay);
            
            console.log('Auto-play started');
        },
        
        stopAutoPlay() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
                this.autoPlayInterval = null;
                console.log('Auto-play stopped');
            }
        },
        
        handleKeyboard(e) {
            const carousel = document.getElementById('heroCarousel');
            if (!carousel || !document.activeElement.closest('#heroCarousel')) return;
            
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    this.prev();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    this.next();
                    break;
                case 'Home':
                    e.preventDefault();
                    this.goToSlide(0);
                    break;
                case 'End':
                    e.preventDefault();
                    this.goToSlide(this.totalSlides - 1);
                    break;
            }
        },
        
        addTouchSupport(carousel) {
            let touchStartX = 0;
            let touchEndX = 0;
            const minSwipeDistance = 50;
            
            carousel.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
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
            }, { passive: true });
        }
    };
    
    window.carouselController = carouselController;
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded, initializing carousel...');
            carouselController.init();
        });
    } else {
        console.log('DOM already loaded, initializing carousel...');
        carouselController.init();
    }
    
    // Clean up
    window.addEventListener('beforeunload', () => {
        carouselController.stopAutoPlay();
    });
})();
</script>

</body>
</html>