<?php
/**
 * About Page View Template - Enhanced for Responsiveness
 * 
 * @package FCTCNS
 * @version 4.8
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$page_title = $page_title ?? 'About | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Learn about our history, mission, vision, values, leadership, and commitment to excellence in nursing education.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="<?php echo e($page_description); ?>">
    <title><?php echo e($page_title); ?></title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* ==========================================================================
   CRITICAL FIX: No gap between header and content
   ========================================================================== */
body { 
    margin: 0 !important; 
    padding: 0 !important; 
    overflow-x: hidden;
}
main.about-page { 
    margin-top: 0 !important; 
    padding-top: 0 !important; 
}
.about-hero { 
    margin-top: 0 !important; 
    padding-top: 0 !important; 
}

/* ==========================================================================
   GLOBAL VARIABLES - Consistent Color Scheme with Admissions Page
   ========================================================================== */
:root {
    /* Professional Color Palette - Matching Admissions Page */
    --color-primary: #5D4A8A;           /* Deep sophisticated purple */
    --color-primary-dark: #4A3A6F;
    --color-primary-light: #6F5B9E;
    --color-primary-very-light: #F8F6FC;
    --color-primary-transparent: rgba(93, 74, 138, 0.08);
    
    --color-accent: #D4A574;            /* Muted gold accent */
    --color-accent-dark: #BF8F5E;
    --color-accent-light: #E6C9A5;
    
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
    
    /* Typography - Consistent with Admissions Page */
    --font-heading: 'Montserrat', sans-serif;
    --font-body: 'Open Sans', sans-serif;
    
    /* Spacing */
    --spacing-xs: 0.5rem;
    --spacing-sm: 1rem;
    --spacing-md: 1.5rem;
    --spacing-lg: 2rem;
    --spacing-xl: 2.5rem;
    --spacing-xxl: 3.5rem;
    
    /* Shadows */
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

* { 
    box-sizing: border-box; 
    margin: 0;
    padding: 0;
}

body { 
    font-family: var(--font-body); 
    font-size: 16px; 
    line-height: 1.6; 
    color: var(--color-gray-800); 
    background: var(--color-white); 
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.container { 
    width: 100%; 
    max-width: 1200px; 
    margin: 0 auto; 
    padding: 0 var(--spacing-md); 
}

/* ==========================================================================
   HERO SECTION - Enhanced for Responsiveness
   ========================================================================== */
.about-hero {
    position: relative;
    min-height: 85vh;
    height: auto;
    max-height: none;
    overflow: hidden;
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    display: flex;
    align-items: center;
    padding: var(--spacing-xl) 0;
}

.about-hero-bg {
    position: absolute;
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%;
    background-size: cover; 
    background-position: center;
    background-image: url('<?php echo $baseUrl; ?>/assets/images/about/campus-building.jpg');
    opacity: 0.6;
    z-index: 1;
}

.about-hero-bg::after {
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

.about-hero-content {
    position: relative;
    z-index: 3;
    color: var(--color-white);
    max-width: 800px;
    padding: var(--spacing-xl);
    margin: 0 auto;
    text-align: center;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.15);
    width: 90%;
}

.about-hero-badge { 
    display: inline-block;
    background: var(--color-accent); 
    color: var(--color-gray-900); 
    padding: 0.5rem 1.5rem; 
    border-radius: var(--radius-full); 
    font-size: 0.85rem; 
    font-weight: 600; 
    margin-bottom: var(--spacing-md);
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-family: var(--font-heading);
}

.about-hero-title { 
    font-family: var(--font-heading); 
    font-size: clamp(2rem, 5vw, 3rem); 
    font-weight: 700; 
    color: var(--color-white); 
    text-shadow: 0 2px 8px rgba(0,0,0,0.4);
    line-height: 1.2;
    margin-bottom: var(--spacing-md);
    letter-spacing: -0.5px;
}

.about-hero-subtitle { 
    font-size: clamp(1rem, 3vw, 1.4rem); 
    color: rgba(255,255,255,0.95);
    line-height: 1.6;
    max-width: 700px;
    margin: 0 auto;
    font-weight: 400;
}

/* ==========================================================================
   SECTIONS & CARDS - Consistent Styling
   ========================================================================== */
.section { 
    padding: var(--spacing-xl) 0; 
}

.section-alt { 
    background: var(--color-gray-50); 
    border-top: 1px solid var(--color-gray-100);
    border-bottom: 1px solid var(--color-gray-100);
}

.section-header { 
    text-align: center; 
    margin-bottom: var(--spacing-xl); 
    max-width: 800px; 
    margin-left: auto; 
    margin-right: auto; 
    padding: 0 var(--spacing-md);
}

.section-title { 
    font-family: var(--font-heading); 
    font-size: clamp(1.75rem, 4vw, 2.25rem); 
    font-weight: 600; 
    color: var(--color-primary); 
    position: relative; 
    display: inline-block;
    margin-bottom: var(--spacing-sm);
}

.section-title::after { 
    content: ''; 
    position: absolute; 
    bottom: -8px; 
    left: 50%; 
    transform: translateX(-50%); 
    width: 60px; 
    height: 3px; 
    background: var(--color-accent); 
    border-radius: 2px; 
}

.section-subtitle { 
    font-size: clamp(1rem, 2.5vw, 1.2rem); 
    color: var(--color-gray-800); 
    line-height: 1.6; 
    font-weight: 400;
    max-width: 700px;
    margin: 0 auto;
    margin-top: var(--spacing-md);
}

.grid { 
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr)); 
    gap: var(--spacing-lg); 
    margin-top: var(--spacing-lg);
}

.card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-subtle);
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.card:hover { 
    transform: translateY(-8px); 
    box-shadow: var(--shadow-elevated); 
    border-color: var(--color-primary-light);
}

.card-img { 
    width: 100%; 
    height: 250px; 
    object-fit: cover; 
    display: block;
}

.card-body { 
    padding: var(--spacing-lg); 
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.card-title { 
    font-family: var(--font-heading); 
    font-size: clamp(1.2rem, 3vw, 1.5rem); 
    font-weight: 600; 
    color: var(--color-primary); 
    margin-bottom: var(--spacing-md); 
    line-height: 1.3;
}

.card-text { 
    color: var(--color-gray-800); 
    line-height: 1.6;
    flex-grow: 1;
    font-size: 0.95rem;
}

.badge-card { 
    text-align: center; 
    padding: var(--spacing-xl);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.badge-icon { 
    font-size: 3rem; 
    color: var(--color-primary); 
    margin-bottom: var(--spacing-md); 
}

.badge-title { 
    font-family: var(--font-heading); 
    font-size: 1.4rem; 
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
}

/* ==========================================================================
   VALUES LIST - Enhanced
   ========================================================================== */
.values-list { 
    list-style: none; 
    padding: 0; 
    margin: var(--spacing-md) 0; 
}

.values-list li { 
    padding: 0.75rem 0; 
    position: relative; 
    padding-left: 2rem; 
    color: var(--color-gray-800);
    line-height: 1.5;
    font-size: 0.95rem;
}

.values-list li::before { 
    content: '✓'; 
    position: absolute; 
    left: 0; 
    color: var(--color-accent); 
    font-weight: bold; 
    font-size: 1.2rem; 
}

/* ==========================================================================
   STATISTICS - Enhanced Display
   ========================================================================== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-lg);
}

.stat-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-subtle);
    padding: var(--spacing-lg);
    text-align: center;
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 150px;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-light);
}

.stat-number {
    font-family: var(--font-heading);
    font-size: clamp(2rem, 5vw, 2.8rem);
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    line-height: 1;
}

.stat-label {
    color: var(--color-gray-800);
    font-size: 1rem;
    font-weight: 500;
}

/* ==========================================================================
   GALLERY CAROUSEL - Enhanced for Mobile
   ========================================================================== */
.gallery-container {
    position: relative;
    margin-top: var(--spacing-xl);
    width: 100%;
    overflow: hidden;
}

.gallery-carousel {
    position: relative;
    height: 600px;
    overflow: hidden;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-elevated);
}

.gallery-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.8s ease;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    width: 100%;
    height: 100%;
}

.gallery-slide.active { 
    opacity: 1; 
}

/* Enhanced Caption System */
.gallery-caption {
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    width: 45%;
    max-width: 450px;
    background: rgba(93, 74, 138, 0.92);
    padding: var(--spacing-xl);
    color: var(--color-white);
    backdrop-filter: blur(8px);
    border-radius: var(--radius-lg) 0 0 var(--radius-lg);
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.2);
    z-index: 10;
}

.gallery-caption::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: var(--color-accent);
}

.gallery-caption h3 {
    font-family: var(--font-heading);
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: var(--spacing-md);
    color: var(--color-white);
    line-height: 1.3;
}

.gallery-caption p {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.95);
    line-height: 1.6;
    margin: 0;
}

/* Gallery Navigation Dots */
.gallery-dots {
    display: flex;
    justify-content: center;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-lg);
    position: absolute;
    bottom: var(--spacing-lg);
    left: 0;
    right: 0;
    z-index: 20;
}

.gallery-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    border: 2px solid var(--color-white);
    cursor: pointer;
    transition: var(--transition-smooth);
}

.gallery-dot.active {
    background: var(--color-accent);
    transform: scale(1.2);
}

.gallery-dot:hover {
    background: var(--color-accent-light);
}

/* ==========================================================================
   BUTTONS - Enhanced for Mobile
   ========================================================================== */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 1rem 2rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid var(--color-accent);
    font-family: var(--font-heading);
    font-size: 1rem;
    letter-spacing: 0.3px;
    min-height: 50px;
    width: 100%;
    max-width: 250px;
}

.btn-primary:hover { 
    background: var(--color-accent-dark); 
    color: var(--color-gray-900);
    transform: translateY(-3px); 
    box-shadow: var(--shadow-soft); 
    border-color: var(--color-accent-dark);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: var(--color-primary);
    color: var(--color-white);
    padding: 1rem 2rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid var(--color-primary);
    font-family: var(--font-heading);
    font-size: 1rem;
    letter-spacing: 0.3px;
    min-height: 50px;
    width: 100%;
    max-width: 250px;
}

.btn-secondary:hover { 
    background: var(--color-primary-dark); 
    color: var(--color-white);
    transform: translateY(-3px); 
    box-shadow: var(--shadow-soft); 
    border-color: var(--color-primary-dark);
}

/* ==========================================================================
   CTA SECTION - Enhanced for Mobile
   ========================================================================== */
.cta-section { 
    background: linear-gradient(135deg, var(--color-gray-50), var(--color-white));
    text-align: center; 
    padding: var(--spacing-xxl) 0; 
    border-top: 1px solid var(--color-gray-100);
    border-bottom: 1px solid var(--color-gray-100);
}

.cta-section .section-title {
    margin-bottom: var(--spacing-md);
}

.cta-buttons {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
    flex-wrap: wrap;
    margin-top: var(--spacing-lg);
}

/* ==========================================================================
   ENHANCED RESPONSIVE DESIGN
   ========================================================================== */

/* Large Tablets & Small Laptops */
@media (max-width: 1024px) {
    .about-hero {
        min-height: 75vh;
    }
    
    .gallery-carousel {
        height: 500px;
    }
    
    .gallery-caption {
        width: 55%;
        padding: var(--spacing-lg);
    }
    
    .gallery-caption h3 {
        font-size: 1.6rem;
    }
    
    .gallery-caption p {
        font-size: 1rem;
    }
}

/* Tablets */
@media (max-width: 768px) {
    :root {
        --spacing-xs: 0.5rem;
        --spacing-sm: 0.875rem;
        --spacing-md: 1.25rem;
        --spacing-lg: 1.5rem;
        --spacing-xl: 2rem;
        --spacing-xxl: 2.5rem;
    }
    
    .about-hero {
        min-height: 70vh;
        padding: var(--spacing-lg) 0;
    }
    
    .about-hero-content {
        padding: var(--spacing-lg);
        margin: 0 auto;
        width: 95%;
        backdrop-filter: blur(5px);
    }
    
    .about-hero-title {
        font-size: clamp(1.8rem, 6vw, 2.5rem);
        margin-bottom: var(--spacing-sm);
    }
    
    .about-hero-subtitle {
        font-size: clamp(1rem, 4vw, 1.2rem);
        line-height: 1.5;
    }
    
    .gallery-carousel {
        height: 400px;
    }
    
    /* Mobile-optimized caption */
    .gallery-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        top: auto;
        transform: none;
        width: 100%;
        max-width: 100%;
        background: linear-gradient(
            to top,
            rgba(93, 74, 138, 0.95),
            rgba(93, 74, 138, 0.85) 70%,
            rgba(93, 74, 138, 0.7)
        );
        padding: var(--spacing-lg);
        border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .gallery-caption h3 {
        font-size: 1.4rem;
        margin-bottom: var(--spacing-xs);
    }
    
    .gallery-caption p {
        font-size: 0.95rem;
        line-height: 1.4;
    }
    
    .gallery-dots {
        bottom: var(--spacing-md);
    }
    
    .grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }
    
    .card-img {
        height: 200px;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-primary,
    .btn-secondary {
        max-width: 300px;
    }
}

/* Mobile Devices */
@media (max-width: 480px) {
    .about-hero {
        min-height: 65vh;
        padding: var(--spacing-md) 0;
    }
    
    .about-hero-content {
        padding: var(--spacing-md);
        backdrop-filter: blur(3px);
    }
    
    .about-hero-badge {
        padding: 0.4rem 1rem;
        font-size: 0.75rem;
        margin-bottom: var(--spacing-sm);
    }
    
    .about-hero-title {
        font-size: 1.6rem;
        line-height: 1.3;
        margin-bottom: var(--spacing-xs);
    }
    
    .about-hero-subtitle {
        font-size: 1rem;
        line-height: 1.4;
    }
    
    .section {
        padding: var(--spacing-lg) 0;
    }
    
    .section-header {
        margin-bottom: var(--spacing-lg);
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .section-subtitle {
        font-size: 1rem;
        margin-top: var(--spacing-sm);
    }
    
    .gallery-carousel {
        height: 300px;
        border-radius: var(--radius-md);
    }
    
    .gallery-caption {
        padding: var(--spacing-md);
    }
    
    .gallery-caption h3 {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }
    
    .gallery-caption p {
        font-size: 0.85rem;
        line-height: 1.3;
    }
    
    .gallery-dots {
        bottom: var(--spacing-sm);
    }
    
    .gallery-dot {
        width: 10px;
        height: 10px;
        border-width: 1px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-sm);
    }
    
    .stat-card {
        padding: var(--spacing-md);
        min-height: 120px;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .card-title {
        font-size: 1.2rem;
    }
    
    .card-body {
        padding: var(--spacing-md);
    }
    
    .cta-section {
        padding: var(--spacing-xl) 0;
    }
    
    .btn-primary,
    .btn-secondary {
        padding: 0.875rem 1.5rem;
        font-size: 0.95rem;
        min-height: 44px;
    }
    
    .values-list li {
        padding: 0.5rem 0;
        padding-left: 1.5rem;
        font-size: 0.9rem;
    }
    
    .badge-card {
        padding: var(--spacing-lg);
    }
    
    .badge-icon {
        font-size: 2.5rem;
    }
}

/* Very Small Mobile Devices */
@media (max-width: 360px) {
    .about-hero {
        min-height: 60vh;
    }
    
    .about-hero-title {
        font-size: 1.4rem;
    }
    
    .about-hero-subtitle {
        font-size: 0.9rem;
    }
    
    .gallery-carousel {
        height: 250px;
    }
    
    .gallery-caption {
        padding: var(--spacing-sm);
    }
    
    .gallery-caption h3 {
        font-size: 1rem;
    }
    
    .gallery-caption p {
        font-size: 0.8rem;
    }
    
    .container {
        padding: 0 var(--spacing-sm);
    }
}

/* Landscape Orientation */
@media (max-height: 600px) and (orientation: landscape) {
    .about-hero {
        min-height: 100vh;
        padding: var(--spacing-md) 0;
    }
    
    .about-hero-content {
        margin: 0 auto;
        padding: var(--spacing-md);
    }
    
    .about-hero-title {
        font-size: 1.8rem;
        margin-bottom: var(--spacing-xs);
    }
    
    .about-hero-subtitle {
        font-size: 1rem;
        line-height: 1.4;
    }
}

/* High-resolution displays */
@media (min-width: 1400px) {
    .container {
        max-width: 1320px;
    }
    
    .about-hero {
        min-height: 90vh;
    }
    
    .gallery-carousel {
        height: 700px;
    }
}

/* Print Styles */
@media print {
    .about-hero {
        min-height: auto;
        background: var(--color-white);
        color: var(--color-black);
        padding: 2rem 0;
    }
    
    .about-hero-bg {
        display: none;
    }
    
    .about-hero-content {
        color: var(--color-black);
        background: transparent;
        backdrop-filter: none;
        border: none;
        box-shadow: none;
    }
    
    .btn-primary,
    .btn-secondary {
        display: none;
    }
    
    .card {
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
        break-inside: avoid;
    }
    
    .gallery-carousel {
        display: none;
    }
    
    .section {
        padding: 1rem 0;
        break-inside: avoid;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        transition: none !important;
        animation: none !important;
    }
    
    .card:hover,
    .btn-primary:hover,
    .btn-secondary:hover,
    .stat-card:hover {
        transform: none !important;
    }
    
    .gallery-slide {
        transition: none;
    }
}

:focus-visible {
    outline: 3px solid var(--color-accent);
    outline-offset: 3px;
    border-radius: var(--radius-sm);
}

/* Screen Reader Only */
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
</style>
</head>
<body>

<main class="about-page">
    <!-- Hero Section - Enhanced for Mobile -->
    <section class="about-hero" aria-label="About FCT College of Nursing Sciences">
        <div class="about-hero-bg" role="img" aria-label="Campus building background"></div>
        <div class="container">
            <div class="about-hero-content">
                <span class="about-hero-badge">Excellence Since 1989</span>
                <h1 class="about-hero-title">About FCT College of Nursing Sciences</h1>
                <p class="about-hero-subtitle">
                    A premier institution dedicated to excellence in nursing education, research, and healthcare training in Nigeria's Federal Capital Territory.
                </p>
            </div>
        </div>
    </section>

    <!-- Mission, Vision, Values -->
    <section class="section section-alt" aria-labelledby="mission-vision-values">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" id="mission-vision-values">Mission, Vision & Values</h2>
                <p class="section-subtitle">The guiding principles that define our commitment to nursing excellence.</p>
            </div>
            <div class="grid">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Our Mission</h3>
                        <p class="card-text">To deliver exceptional nursing education through innovative teaching, research, and community engagement, developing competent and compassionate nursing professionals who demonstrate excellence in clinical practice, leadership, and ethical conduct.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Our Vision</h3>
                        <p class="card-text">To be Africa's leading institution for nursing education and research, recognized for producing healthcare professionals who transform communities through innovative practice, ethical leadership, and compassionate, evidence-based care.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Core Values</h3>
                        <ul class="values-list">
                            <li>Excellence in Education</li>
                            <li>Integrity and Ethics</li>
                            <li>Compassionate Care</li>
                            <li>Innovation and Research</li>
                            <li>Professional Development</li>
                            <li>Community Service</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="section" aria-labelledby="statistics">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" id="statistics">Our Impact in Numbers</h2>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">35+</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">5,000+</div>
                    <div class="stat-label">Nursing Graduates</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">NMCN Accredited</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Faculty Members</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership -->
    <section class="section section-alt" aria-labelledby="leadership">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" id="leadership">Institutional Leadership</h2>
                <p class="section-subtitle">Experienced professionals guiding our institution toward educational excellence.</p>
            </div>
            <div class="grid">
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/assets/images/leadership/fct-minister.jpg" alt="Ezenwo Nyesom Wike CON, FCT Minister" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';" loading="lazy">
                    <div class="card-body">
                        <h3 class="card-title">Ezenwo Nyesom Wike CON</h3>
                        <p class="card-text">FCT Minister<br>Federal Capital Territory Administration</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/assets/images/leadership/mandate-secretary.jpg" alt="Dr. Adedolapo Fasawe" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';" loading="lazy">
                    <div class="card-body">
                        <h3 class="card-title">Dr. Adedolapo Fasawe</h3>
                        <p class="card-text">Mandate Secretary<br>Health Services & Environment Secretariat</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/assets/images/leadership/permanent-secretary.jpg" alt="Dr. Babagana Adam" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';" loading="lazy">
                    <div class="card-body">
                        <h3 class="card-title">Dr. Babagana Adam</h3>
                        <p class="card-text">Permanent Secretary<br>Health Services & Environment Secretariat</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/assets/images/leadership/director-nursing.jpg" alt="Mrs Ijoema Jimi Bada" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';" loading="lazy">
                    <div class="card-body">
                        <h3 class="card-title">Mrs Ijoema Jimi Bada</h3>
                        <p class="card-text">Director, Nursing Services<br>Health Services & Environment Secretariat</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/assets/images/leadership/college-provost.jpg" alt="Comr. Deborah Yusuf" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';" loading="lazy">
                    <div class="card-body">
                        <h3 class="card-title">Comr. Deborah Yusuf</h3>
                        <p class="card-text">Provost, FCTCNS<br>FCT College of Nursing Sciences</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Accreditation -->
    <section class="section" aria-labelledby="accreditation">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" id="accreditation">Institutional Accreditation</h2>
            </div>
            <div class="grid">
                <div class="card badge-card">
                    <i class="fas fa-stethoscope badge-icon" aria-hidden="true"></i>
                    <h3 class="badge-title">NMCN</h3>
                    <p class="card-text">Nursing & Midwifery Council of Nigeria<br>Full accreditation for all nursing programs.</p>
                </div>
                <div class="card badge-card">
                    <i class="fas fa-university badge-icon" aria-hidden="true"></i>
                    <h3 class="badge-title">NBTE</h3>
                    <p class="card-text">National Board for Technical Education<br>Accreditation for technical programs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Carousel - Enhanced for Mobile -->
    <section class="section section-alt" aria-labelledby="gallery">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" id="gallery">Our Learning Environment</h2>
                <p class="section-subtitle">Modern facilities supporting excellence in nursing education.</p>
            </div>
            
            <div class="gallery-container">
                <div class="gallery-carousel" role="region" aria-label="Campus gallery">
                    <!-- Slide 1 -->
                    <div class="gallery-slide active" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/about/simulation-lab.jpg');" role="img" aria-label="Simulation Laboratory">
                        <div class="gallery-caption">
                            <h3>Simulation Laboratory</h3>
                            <p>State-of-the-art simulation lab where students practice clinical skills in a controlled, realistic environment.</p>
                        </div>
                    </div>
                    
                    <!-- Slide 2 -->
                    <div class="gallery-slide" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/about/library.jpg');" role="img" aria-label="Medical Library">
                        <div class="gallery-caption">
                            <h3>Medical Library</h3>
                            <p>Comprehensive collection of nursing journals, textbooks, and digital resources for research and study.</p>
                        </div>
                    </div>
                    
                    <!-- Slide 3 -->
                    <div class="gallery-slide" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/about/classroom.jpg');" role="img" aria-label="Interactive Classroom">
                        <div class="gallery-caption">
                            <h3>Interactive Classrooms</h3>
                            <p>Technology-enhanced learning spaces designed for collaborative nursing education and discussion.</p>
                        </div>
                    </div>
                    
                    <!-- Slide 4 -->
                    <div class="gallery-slide" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/about/campus-building.jpg');" role="img" aria-label="Main Campus Building">
                        <div class="gallery-caption">
                            <h3>Main Campus</h3>
                            <p>The heart of our institution where future nursing professionals begin their transformative journey.</p>
                        </div>
                    </div>
                    
                    <!-- Navigation Dots -->
                    <div class="gallery-dots" role="tablist" aria-label="Gallery slides">
                        <div class="gallery-dot active" data-slide="0" role="tab" aria-selected="true" aria-label="Slide 1: Simulation Laboratory"></div>
                        <div class="gallery-dot" data-slide="1" role="tab" aria-selected="false" aria-label="Slide 2: Medical Library"></div>
                        <div class="gallery-dot" data-slide="2" role="tab" aria-selected="false" aria-label="Slide 3: Interactive Classrooms"></div>
                        <div class="gallery-dot" data-slide="3" role="tab" aria-selected="false" aria-label="Slide 4: Main Campus"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section" aria-labelledby="cta-title">
        <div class="container">
            <h2 class="section-title" id="cta-title">Join Our Nursing Community</h2>
            <p class="section-subtitle" style="max-width: 700px; margin: 0 auto;">Begin your professional nursing journey at one of Nigeria's most respected institutions.</p>
            <div class="cta-buttons">
                <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary"><i class="fas fa-file-alt"></i> Begin Application</a>
                <a href="<?php echo $baseUrl; ?>/programs" class="btn-secondary"><i class="fas fa-book-open"></i> Explore Programs</a>
            </div>
        </div>
    </section>
</main>

<!-- Enhanced Carousel Script -->
<script>
const gallerySlides = document.querySelectorAll('.gallery-slide');
const galleryDots = document.querySelectorAll('.gallery-dot');
let currentSlide = 0;
const intervalTime = 5000;
let autoSlideInterval;

function showSlide(index) {
    // Hide all slides
    gallerySlides.forEach(slide => {
        slide.classList.remove('active');
    });
    
    // Update all dots
    galleryDots.forEach((dot, i) => {
        dot.classList.remove('active');
        dot.setAttribute('aria-selected', i === index ? 'true' : 'false');
    });
    
    // Show selected slide
    gallerySlides[index].classList.add('active');
    galleryDots[index].classList.add('active');
    galleryDots[index].setAttribute('aria-selected', 'true');
    currentSlide = index;
}

function nextSlide() {
    const nextIndex = (currentSlide + 1) % gallerySlides.length;
    showSlide(nextIndex);
}

// Initialize auto-slide
function startAutoSlide() {
    autoSlideInterval = setInterval(nextSlide, intervalTime);
}

// Add click events to dots
galleryDots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        clearInterval(autoSlideInterval);
        showSlide(index);
        startAutoSlide();
    });
    
    // Keyboard accessibility for dots
    dot.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            clearInterval(autoSlideInterval);
            showSlide(index);
            startAutoSlide();
        }
    });
});

// Pause auto-slide on hover/focus
const galleryCarousel = document.querySelector('.gallery-carousel');
galleryCarousel.addEventListener('mouseenter', () => {
    clearInterval(autoSlideInterval);
});

galleryCarousel.addEventListener('mouseleave', () => {
    startAutoSlide();
});

galleryCarousel.addEventListener('focusin', () => {
    clearInterval(autoSlideInterval);
});

galleryCarousel.addEventListener('focusout', () => {
    startAutoSlide();
});

// Touch swipe for mobile
let touchStartX = 0;
let touchEndX = 0;

galleryCarousel.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
    clearInterval(autoSlideInterval);
});

galleryCarousel.addEventListener('touchmove', (e) => {
    e.preventDefault();
});

galleryCarousel.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
    startAutoSlide();
});

function handleSwipe() {
    const swipeThreshold = 50;
    
    if (touchStartX - touchEndX > swipeThreshold) {
        // Swipe left - next slide
        nextSlide();
    } else if (touchEndX - touchStartX > swipeThreshold) {
        // Swipe right - previous slide
        const prevIndex = (currentSlide - 1 + gallerySlides.length) % gallerySlides.length;
        showSlide(prevIndex);
    }
}

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (document.activeElement.closest('.gallery-carousel')) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            clearInterval(autoSlideInterval);
            const prevIndex = (currentSlide - 1 + gallerySlides.length) % gallerySlides.length;
            showSlide(prevIndex);
            startAutoSlide();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            clearInterval(autoSlideInterval);
            nextSlide();
            startAutoSlide();
        }
    }
});

// Start the carousel
document.addEventListener('DOMContentLoaded', () => {
    startAutoSlide();
});

// Handle image loading errors
document.querySelectorAll('.card-img').forEach(img => {
    img.addEventListener('error', function() {
        this.src = '<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';
        this.alt = 'Image not available';
    });
});

// Lazy loading for images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                imageObserver.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}
</script>

</body>
</html>