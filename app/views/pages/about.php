<?php
/**
 * About Page View Template - Enhanced for Responsiveness
 * Updated: Removed overlay, enhanced fonts, professional design
 * Enhanced Leadership, Mission/Vision/Values & Accreditation Sections
 * 
 * @package FCTCNS
 * @version 5.3
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
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
    --shadow-deep: 0 16px 32px rgba(0, 0, 0, 0.15);
    
    /* Border Radius */
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 14px;
    --radius-xl: 20px;
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
   HERO SECTION - ENHANCED WITHOUT OVERLAY
   ========================================================================== */
.about-hero {
    position: relative;
    min-height: 85vh;
    height: auto;
    max-height: none;
    overflow: hidden;
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
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
    z-index: 1;
}

.about-hero-content {
    position: relative;
    z-index: 3;
    color: var(--color-white);
    max-width: 900px;
    padding: var(--spacing-xl);
    margin: 0 auto;
    text-align: center;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(5px);
    border-radius: var(--radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.2);
    width: 90%;
}

.about-hero-badge { 
    display: inline-block;
    background: var(--color-accent); 
    color: var(--color-gray-900); 
    padding: 0.6rem 1.75rem; 
    border-radius: var(--radius-full); 
    font-size: 0.9rem; 
    font-weight: 600; 
    margin-bottom: var(--spacing-md);
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-family: var(--font-heading);
    box-shadow: var(--shadow-soft);
}

.about-hero-title { 
    font-family: var(--font-heading); 
    font-size: clamp(2.25rem, 5.5vw, 3.5rem);
    font-weight: 700; 
    color: var(--color-white); 
    text-shadow: 0 2px 10px rgba(0,0,0,0.6);
    line-height: 1.15;
    margin-bottom: var(--spacing-md);
    letter-spacing: -0.5px;
}

.about-hero-subtitle { 
    font-size: clamp(1.15rem, 3.5vw, 1.6rem);
    color: rgba(255,255,255,0.95);
    line-height: 1.5;
    max-width: 800px;
    margin: 0 auto;
    font-weight: 400;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
}

/* ==========================================================================
   PROFESSIONAL MISSION, VISION & VALUES CARDS - NO ICONS
   ========================================================================== */
.mvv-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.mvv-card {
    background: var(--color-white);
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl) var(--spacing-lg);
    position: relative;
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    display: flex;
    flex-direction: column;
    height: 100%;
    box-shadow: var(--shadow-subtle);
    overflow: hidden;
}

.mvv-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-deep);
    border-color: var(--color-primary-light);
}

.mvv-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
}

.mvv-title {
    font-family: var(--font-heading);
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: var(--spacing-md);
    line-height: 1.2;
    position: relative;
    display: inline-block;
}

.mvv-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 60px;
    height: 3px;
    background: var(--color-accent);
    border-radius: 2px;
    transition: width 0.3s ease;
}

.mvv-card:hover .mvv-title::after {
    width: 100px;
}

.mvv-text {
    color: var(--color-gray-800);
    line-height: 1.7;
    font-size: 1rem;
    margin-top: var(--spacing-xs);
    flex-grow: 1;
}

/* Values List Styling - Enhanced */
.values-container {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-md);
}

.value-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: 0.75rem;
    border-radius: var(--radius-md);
    background: var(--color-gray-50);
    transition: var(--transition-smooth);
}

.value-item:hover {
    background: var(--color-primary-very-light);
    transform: translateX(5px);
}

.value-icon {
    width: 32px;
    height: 32px;
    border-radius: var(--radius-full);
    background: var(--color-white);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-accent);
    font-size: 0.9rem;
    border: 1px solid var(--color-gray-100);
}

.value-item:hover .value-icon {
    background: var(--color-accent);
    color: var(--color-white);
    border-color: var(--color-accent);
}

.value-text {
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--color-gray-800);
}

/* ==========================================================================
   ENHANCED LEADERSHIP SECTION - PROFESSIONAL DESIGN
   ========================================================================== */
.leadership-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.leadership-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    position: relative;
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    display: flex;
    flex-direction: column;
    height: 100%;
    box-shadow: var(--shadow-subtle);
}

.leadership-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-deep);
    border-color: var(--color-primary-light);
}

.leadership-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 115%;
    overflow: hidden;
    background: linear-gradient(135deg, var(--color-primary-very-light), var(--color-gray-50));
}

.leadership-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 20%;
    transition: transform 0.6s ease;
}

.leadership-card:hover .leadership-img {
    transform: scale(1.05);
}

.leadership-content {
    padding: var(--spacing-lg);
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.leadership-title {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: var(--spacing-xs);
    line-height: 1.3;
}

.leadership-role {
    font-family: var(--font-heading);
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--color-accent-dark);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: var(--spacing-xs);
}

.leadership-dept {
    font-size: 0.95rem;
    color: var(--color-gray-600);
    margin-bottom: var(--spacing-md);
    line-height: 1.5;
    border-bottom: 1px solid var(--color-gray-100);
    padding-bottom: var(--spacing-md);
}

.leadership-bio {
    font-size: 0.95rem;
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    flex-grow: 1;
}

.leadership-social {
    display: flex;
    gap: var(--spacing-sm);
    margin-top: auto;
    padding-top: var(--spacing-sm);
    border-top: 1px solid var(--color-gray-100);
}

.leadership-social a {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--color-primary-very-light);
    color: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition-smooth);
    text-decoration: none;
}

.leadership-social a:hover {
    background: var(--color-primary);
    color: var(--color-white);
    transform: translateY(-2px);
}

/* Leadership Quote Section - Perfectly Centered */
.leadership-quote {
    margin-top: var(--spacing-xxl);
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl) var(--spacing-lg);
    color: var(--color-white);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    width: 100%;
}

.leadership-quote::before {
    content: '"';
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 200px;
    font-family: var(--font-heading);
    color: rgba(255, 255, 255, 0.1);
    line-height: 1;
    pointer-events: none;
}

.leadership-quote-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    width: 100%;
}

.leadership-quote-text {
    font-size: 1.35rem;
    font-weight: 400;
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
    font-style: italic;
    max-width: 750px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}

.leadership-quote-author {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-accent);
    margin-bottom: var(--spacing-xs);
    text-align: center;
    width: 100%;
}

.leadership-quote-title {
    font-size: 1rem;
    opacity: 0.95;
    font-weight: 400;
    text-align: center;
    width: 100%;
    letter-spacing: 0.5px;
}

/* ==========================================================================
   PROFESSIONAL ACCREDITATION SECTION - REDESIGNED
   ========================================================================== */
.accreditation-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.accreditation-card {
    background: linear-gradient(135deg, var(--color-white), var(--color-gray-50));
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl);
    position: relative;
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    display: flex;
    flex-direction: column;
    height: 100%;
    box-shadow: var(--shadow-subtle);
    overflow: hidden;
}

.accreditation-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-deep);
    border-color: var(--color-primary-light);
    background: linear-gradient(135deg, var(--color-white), var(--color-primary-very-light));
}

.accreditation-badge {
    position: absolute;
    top: var(--spacing-lg);
    right: var(--spacing-lg);
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 0.4rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    z-index: 2;
    box-shadow: var(--shadow-soft);
}

.accreditation-logo-wrapper {
    width: 100px;
    height: 100px;
    border-radius: var(--radius-lg);
    background: var(--color-white);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--spacing-lg);
    border: 1px solid var(--color-gray-100);
    box-shadow: var(--shadow-subtle);
    transition: var(--transition-smooth);
}

.accreditation-card:hover .accreditation-logo-wrapper {
    border-color: var(--color-primary-light);
    transform: scale(1.05);
    box-shadow: var(--shadow-soft);
}

.accreditation-logo {
    font-size: 3rem;
    color: var(--color-primary);
    transition: var(--transition-smooth);
}

.accreditation-card:hover .accreditation-logo {
    color: var(--color-accent);
    transform: scale(1.1);
}

.accreditation-title {
    font-family: var(--font-heading);
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--color-primary);
    margin-bottom: var(--spacing-xs);
    line-height: 1.1;
    letter-spacing: -0.5px;
}

.accreditation-subtitle {
    font-family: var(--font-heading);
    font-size: 1rem;
    font-weight: 600;
    color: var(--color-accent-dark);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: var(--spacing-md);
}

.accreditation-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.accreditation-description {
    color: var(--color-gray-800);
    line-height: 1.6;
    font-size: 1rem;
    margin-bottom: var(--spacing-sm);
}

.accreditation-features {
    list-style: none;
    padding: 0;
    margin: 0;
}

.accreditation-features li {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: 0.5rem 0;
    color: var(--color-gray-800);
    font-size: 0.95rem;
}

.accreditation-features li i {
    color: var(--color-accent);
    font-size: 1rem;
    width: 20px;
}

/* REMOVED - Accreditation seal that was overlapping footer */

/* ==========================================================================
   STATISTICS - Enhanced Display
   ========================================================================== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr));
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
    min-height: 160px;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-light);
}

.stat-number {
    font-family: var(--font-heading);
    font-size: clamp(2.2rem, 5vw, 3rem);
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    line-height: 1;
}

.stat-label {
    color: var(--color-gray-800);
    font-size: 1.1rem;
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
    font-size: 1.9rem;
    font-weight: 700;
    margin-bottom: var(--spacing-md);
    color: var(--color-white);
    line-height: 1.3;
}

.gallery-caption p {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.95);
    line-height: 1.6;
    margin: 0;
}

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
    font-size: 1.05rem;
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
    font-size: 1.05rem;
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
        font-size: 1.7rem;
    }
    
    .gallery-caption p {
        font-size: 1.05rem;
    }
    
    .mvv-grid {
        gap: var(--spacing-lg);
    }
    
    .leadership-quote-text {
        font-size: 1.25rem;
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
        font-size: clamp(2rem, 6vw, 2.8rem);
        margin-bottom: var(--spacing-sm);
    }
    
    .about-hero-subtitle {
        font-size: clamp(1.15rem, 4vw, 1.4rem);
        line-height: 1.5;
    }
    
    .gallery-carousel {
        height: 400px;
    }
    
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
        font-size: 1.5rem;
        margin-bottom: var(--spacing-xs);
    }
    
    .gallery-caption p {
        font-size: 1rem;
        line-height: 1.4;
    }
    
    .gallery-dots {
        bottom: var(--spacing-md);
    }
    
    /* Mission, Vision, Values - Stack on tablet */
    .mvv-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    /* Accreditation - Stack on tablet */
    .accreditation-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .leadership-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-primary,
    .btn-secondary {
        max-width: 300px;
    }
    
    .leadership-quote {
        padding: var(--spacing-lg);
    }
    
    .leadership-quote-text {
        font-size: 1.2rem;
    }
    
    .leadership-quote-author {
        font-size: 1.15rem;
    }
    
    .accreditation-title {
        font-size: 2rem;
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
        padding: 0.5rem 1.25rem;
        font-size: 0.8rem;
        margin-bottom: var(--spacing-sm);
    }
    
    .about-hero-title {
        font-size: 1.8rem;
        line-height: 1.3;
        margin-bottom: var(--spacing-xs);
    }
    
    .about-hero-subtitle {
        font-size: 1.1rem;
        line-height: 1.4;
    }
    
    .section {
        padding: var(--spacing-lg) 0;
    }
    
    .section-header {
        margin-bottom: var(--spacing-lg);
    }
    
    .section-title {
        font-size: 1.6rem;
    }
    
    .section-subtitle {
        font-size: 1.05rem;
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
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
    }
    
    .gallery-caption p {
        font-size: 0.9rem;
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
    
    .mvv-grid {
        gap: var(--spacing-md);
    }
    
    .mvv-card {
        padding: var(--spacing-lg);
    }
    
    .mvv-title {
        font-size: 1.6rem;
    }
    
    .accreditation-grid {
        gap: var(--spacing-md);
    }
    
    .accreditation-card {
        padding: var(--spacing-lg);
    }
    
    .accreditation-title {
        font-size: 1.8rem;
    }
    
    .accreditation-logo-wrapper {
        width: 80px;
        height: 80px;
    }
    
    .accreditation-logo {
        font-size: 2.5rem;
    }
    
    .leadership-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
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
        font-size: 2.2rem;
    }
    
    .cta-section {
        padding: var(--spacing-xl) 0;
    }
    
    .btn-primary,
    .btn-secondary {
        padding: 0.875rem 1.5rem;
        font-size: 1rem;
        min-height: 44px;
    }
    
    .value-item {
        padding: 0.6rem;
    }
    
    .value-text {
        font-size: 0.9rem;
    }
    
    .leadership-quote {
        padding: var(--spacing-md);
    }
    
    .leadership-quote-text {
        font-size: 1.1rem;
        margin-bottom: var(--spacing-md);
    }
    
    .leadership-quote-author {
        font-size: 1.1rem;
    }
    
    .leadership-quote-title {
        font-size: 0.9rem;
    }
    
    .accreditation-features li {
        font-size: 0.9rem;
    }
}

/* Very Small Mobile Devices */
@media (max-width: 360px) {
    .about-hero {
        min-height: 60vh;
    }
    
    .about-hero-title {
        font-size: 1.6rem;
    }
    
    .about-hero-subtitle {
        font-size: 1rem;
    }
    
    .gallery-carousel {
        height: 250px;
    }
    
    .gallery-caption {
        padding: var(--spacing-sm);
    }
    
    .gallery-caption h3 {
        font-size: 1.15rem;
    }
    
    .gallery-caption p {
        font-size: 0.85rem;
    }
    
    .container {
        padding: 0 var(--spacing-sm);
    }
    
    .leadership-title {
        font-size: 1.2rem;
    }
    
    .accreditation-title {
        font-size: 1.6rem;
    }
    
    .accreditation-logo-wrapper {
        width: 70px;
        height: 70px;
    }
    
    .accreditation-logo {
        font-size: 2.2rem;
    }
    
    .leadership-quote-text {
        font-size: 1rem;
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
        font-size: 2rem;
        margin-bottom: var(--spacing-xs);
    }
    
    .about-hero-subtitle {
        font-size: 1.1rem;
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
    
    .about-hero-title {
        font-size: 4rem;
    }
    
    .about-hero-subtitle {
        font-size: 1.8rem;
    }
    
    .gallery-carousel {
        height: 700px;
    }
    
    .leadership-grid {
        grid-template-columns: repeat(5, 1fr);
    }
    
    .mvv-grid {
        gap: var(--spacing-xxl);
    }
    
    .accreditation-grid {
        gap: var(--spacing-xxl);
    }
    
    .leadership-quote-text {
        font-size: 1.5rem;
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
    
    .gallery-carousel {
        display: none;
    }
    
    .section {
        padding: 1rem 0;
        break-inside: avoid;
    }
    
    .mvv-card,
    .accreditation-card,
    .leadership-card {
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
        break-inside: avoid;
        page-break-inside: avoid;
    }
    
    .mvv-card::before {
        background: var(--color-gray-300);
    }
    
    .leadership-quote {
        background: var(--color-gray-50);
        color: var(--color-gray-900);
        border: 1px solid var(--color-gray-300);
    }
    
    .value-item {
        background: var(--color-white);
        border: 1px solid var(--color-gray-200);
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
    .stat-card:hover,
    .leadership-card:hover,
    .mvv-card:hover,
    .accreditation-card:hover {
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

    <!-- Mission, Vision, Values - Professionally Designed Cards (No Icons) -->
    <section class="section section-alt" aria-labelledby="mission-vision-values">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" id="mission-vision-values">Mission, Vision & Values</h2>
                <p class="section-subtitle">The guiding principles that define our commitment to nursing excellence.</p>
            </div>
            
            <div class="mvv-grid">
                <!-- Mission Card - No Icon -->
                <div class="mvv-card">
                    <h3 class="mvv-title">Our Mission</h3>
                    <p class="mvv-text">
                        To prepare competent and polyvalent nurses that will use problem-solving skills in providing safe, acceptable, effective health services to meet the health needs of individuals, families and the communities at all levels of care.
                    </p>
                </div>

                <!-- Vision Card - No Icon -->
                <div class="mvv-card">
                    <h3 class="mvv-title">Our Vision</h3>
                    <p class="mvv-text">
                        To be one of the best college of Nursing Sciences in Nigeria especially in the area of imparting knowledge into prospective nurses as well as providing solutions to the much needed health services as required by the people of the FCT in particular and Nigeria at large.
                    </p>
                </div>

                <!-- Core Values Card - No Icon -->
                <div class="mvv-card">
                    <h3 class="mvv-title">Core Values</h3>
                    <div class="values-container">
                        <div class="value-item">
                            <span class="value-icon"><i class="fas fa-check"></i></span>
                            <span class="value-text">Excellence in Education</span>
                        </div>
                        <div class="value-item">
                            <span class="value-icon"><i class="fas fa-check"></i></span>
                            <span class="value-text">Integrity and Ethics</span>
                        </div>
                        <div class="value-item">
                            <span class="value-icon"><i class="fas fa-check"></i></span>
                            <span class="value-text">Compassionate Care</span>
                        </div>
                        <div class="value-item">
                            <span class="value-icon"><i class="fas fa-check"></i></span>
                            <span class="value-text">Innovation and Research</span>
                        </div>
                        <div class="value-item">
                            <span class="value-icon"><i class="fas fa-check"></i></span>
                            <span class="value-text">Professional Development</span>
                        </div>
                        <div class="value-item">
                            <span class="value-icon"><i class="fas fa-check"></i></span>
                            <span class="value-text">Community Service</span>
                        </div>
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

    <!-- Enhanced Leadership Section -->
    <section class="section section-alt" aria-labelledby="leadership">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" id="leadership">Institutional Leadership</h2>
                <p class="section-subtitle">Distinguished leaders shaping the future of nursing education and healthcare delivery in the Federal Capital Territory.</p>
            </div>
            
            <div class="leadership-grid">
                <!-- FCT Minister -->
                <div class="leadership-card">
                    <div class="leadership-image-wrapper">
                        <img src="<?php echo $baseUrl; ?>/assets/images/leadership/fct-minister.jpg" alt="Ezenwo Nyesom Wike CON" class="leadership-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';" loading="lazy">
                    </div>
                    <div class="leadership-content">
                        <h3 class="leadership-title">Ezenwo Nyesom Wike CON</h3>
                        <p class="leadership-role">FCT Minister</p>
                        <p class="leadership-dept">Federal Capital Territory Administration</p>
                        <p class="leadership-bio">Distinguished leader and legal practitioner driving transformative healthcare infrastructure development and educational advancement in the nation's capital.</p>
                        <div class="leadership-social">
                            <a href="#" aria-label="LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
                            <a href="#" aria-label="Profile"><i class="fas fa-user-circle"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Mandate Secretary -->
                <div class="leadership-card">
                    <div class="leadership-image-wrapper">
                        <img src="<?php echo $baseUrl; ?>/assets/images/leadership/mandate-secretary.jpg" alt="Dr. Adedolapo Fasawe" class="leadership-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';" loading="lazy">
                    </div>
                    <div class="leadership-content">
                        <h3 class="leadership-title">Dr. Adedolapo Fasawe</h3>
                        <p class="leadership-role">Mandate Secretary</p>
                        <p class="leadership-dept">Health Services & Environment Secretariat</p>
                        <p class="leadership-bio">Visionary public health administrator with extensive experience in healthcare policy, environmental health, and institutional development.</p>
                        <div class="leadership-social">
                            <a href="#" aria-label="LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
                            <a href="#" aria-label="Profile"><i class="fas fa-user-circle"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Permanent Secretary -->
                <div class="leadership-card">
                    <div class="leadership-image-wrapper">
                        <img src="<?php echo $baseUrl; ?>/assets/images/leadership/permanent-secretary.jpg" alt="Dr. Babagana Adam" class="leadership-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';" loading="lazy">
                    </div>
                    <div class="leadership-content">
                        <h3 class="leadership-title">Dr. Babagana Adam</h3>
                        <p class="leadership-role">Permanent Secretary</p>
                        <p class="leadership-dept">Health Services & Environment Secretariat</p>
                        <p class="leadership-bio">Seasoned administrator and healthcare strategist dedicated to strengthening public health systems and nursing education standards.</p>
                        <div class="leadership-social">
                            <a href="#" aria-label="LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
                            <a href="#" aria-label="Profile"><i class="fas fa-user-circle"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Director, Nursing Services -->
                <div class="leadership-card">
                    <div class="leadership-image-wrapper">
                        <img src="<?php echo $baseUrl; ?>/assets/images/leadership/director-nursing.jpg" alt="Mrs Ijoema Jimi Bada" class="leadership-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';" loading="lazy">
                    </div>
                    <div class="leadership-content">
                        <h3 class="leadership-title">Mrs Ijoema Jimi Bada</h3>
                        <p class="leadership-role">Director</p>
                        <p class="leadership-dept">Nursing Services</p>
                        <p class="leadership-bio">Accomplished nursing professional with decades of experience advancing nursing practice standards and clinical education excellence.</p>
                        <div class="leadership-social">
                            <a href="#" aria-label="LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
                            <a href="#" aria-label="Profile"><i class="fas fa-user-circle"></i></a>
                        </div>
                    </div>
                </div>

                <!-- College Provost -->
                <div class="leadership-card">
                    <div class="leadership-image-wrapper">
                        <img src="<?php echo $baseUrl; ?>/assets/images/leadership/college-provost.jpg" alt="Comr. Deborah Yusuf" class="leadership-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';" loading="lazy">
                    </div>
                    <div class="leadership-content">
                        <h3 class="leadership-title">Comr. Deborah Yusuf</h3>
                        <p class="leadership-role">Provost</p>
                        <p class="leadership-dept">FCT College of Nursing Sciences</p>
                        <p class="leadership-bio">Dynamic academic leader and nursing educator committed to innovative curriculum development and student-centered learning approaches.</p>
                        <div class="leadership-social">
                            <a href="#" aria-label="LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
                            <a href="#" aria-label="Profile"><i class="fas fa-user-circle"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leadership Quote Section - Perfectly Centered on All Screens -->
            <div class="leadership-quote">
                <div class="leadership-quote-content">
                    <p class="leadership-quote-text">"Our collective vision is to nurture a new generation of nursing professionals who will transform healthcare delivery across Nigeria. Through strategic leadership and unwavering commitment to excellence, we are building an institution that stands as a beacon of hope and quality education."</p>
                    <p class="leadership-quote-author">Comr. Deborah Yusuf</p>
                    <p class="leadership-quote-title">Provost, FCT College of Nursing Sciences</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Professional Accreditation Section - Redesigned (No Footer Overlap) -->
    <section class="section" aria-labelledby="accreditation">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" id="accreditation">Institutional Accreditation</h2>
                <p class="section-subtitle">Nationally recognized and fully accredited by Nigeria's premier regulatory bodies.</p>
            </div>
            
            <div class="accreditation-grid">
                <!-- NMCN Accreditation Card -->
                <div class="accreditation-card">
                    <span class="accreditation-badge">Full Accreditation</span>
                    <div class="accreditation-logo-wrapper">
                        <i class="fas fa-stethoscope accreditation-logo"></i>
                    </div>
                    <h3 class="accreditation-title">NMCN</h3>
                    <p class="accreditation-subtitle">Nursing & Midwifery Council of Nigeria</p>
                    <div class="accreditation-body">
                        <p class="accreditation-description">
                            Full accreditation status for all nursing programs, ensuring our graduates meet the highest professional standards.
                        </p>
                        <ul class="accreditation-features">
                            <li><i class="fas fa-check-circle"></i> Basic Nursing Program</li>
                            <li><i class="fas fa-check-circle"></i> Post-Basic Nursing Programs</li>
                            <li><i class="fas fa-check-circle"></i> Midwifery Education</li>
                            <li><i class="fas fa-check-circle"></i> Continuing Professional Development</li>
                        </ul>
                        <!-- Removed accreditation-seal that was overlapping footer -->
                    </div>
                </div>

                <!-- NBTE Accreditation Card -->
                <div class="accreditation-card">
                    <span class="accreditation-badge">Full Accreditation</span>
                    <div class="accreditation-logo-wrapper">
                        <i class="fas fa-university accreditation-logo"></i>
                    </div>
                    <h3 class="accreditation-title">NBTE</h3>
                    <p class="accreditation-subtitle">National Board for Technical Education</p>
                    <div class="accreditation-body">
                        <p class="accreditation-description">
                            Comprehensive accreditation for technical education programs, demonstrating our commitment to educational excellence.
                        </p>
                        <ul class="accreditation-features">
                            <li><i class="fas fa-check-circle"></i> Community Health Programs</li>
                            <li><i class="fas fa-check-circle"></i> Health Technology Programs</li>
                            <li><i class="fas fa-check-circle"></i> Technical Nursing Education</li>
                            <li><i class="fas fa-check-circle"></i> Vocational Training Standards</li>
                        </ul>
                        <!-- Removed accreditation-seal that was overlapping footer -->
                    </div>
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
    gallerySlides.forEach(slide => {
        slide.classList.remove('active');
    });
    
    galleryDots.forEach((dot, i) => {
        dot.classList.remove('active');
        dot.setAttribute('aria-selected', i === index ? 'true' : 'false');
    });
    
    gallerySlides[index].classList.add('active');
    galleryDots[index].classList.add('active');
    galleryDots[index].setAttribute('aria-selected', 'true');
    currentSlide = index;
}

function nextSlide() {
    const nextIndex = (currentSlide + 1) % gallerySlides.length;
    showSlide(nextIndex);
}

function startAutoSlide() {
    autoSlideInterval = setInterval(nextSlide, intervalTime);
}

galleryDots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        clearInterval(autoSlideInterval);
        showSlide(index);
        startAutoSlide();
    });
    
    dot.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            clearInterval(autoSlideInterval);
            showSlide(index);
            startAutoSlide();
        }
    });
});

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
        nextSlide();
    } else if (touchEndX - touchStartX > swipeThreshold) {
        const prevIndex = (currentSlide - 1 + gallerySlides.length) % gallerySlides.length;
        showSlide(prevIndex);
    }
}

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

document.addEventListener('DOMContentLoaded', () => {
    startAutoSlide();
});

document.querySelectorAll('.leadership-img, .card-img').forEach(img => {
    img.addEventListener('error', function() {
        this.src = '<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';
        this.alt = 'Image not available';
    });
});

if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                imageObserver.unobserve(img);
            }
        });
    }, {
        rootMargin: '50px 0px',
        threshold: 0.01
    });
    
    document.querySelectorAll('[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const heroBg = document.querySelector('.about-hero-bg');
    if (heroBg) {
        const bgImage = new Image();
        bgImage.onload = function() {
            heroBg.style.backgroundImage = 'url("<?php echo $baseUrl; ?>/assets/images/about/campus-building.jpg")';
            heroBg.style.opacity = '0.8';
        };
        bgImage.onerror = function() {
            heroBg.style.background = 'linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))';
        };
        bgImage.src = '<?php echo $baseUrl; ?>/assets/images/about/campus-building.jpg';
    }
});

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

</body>
</html>