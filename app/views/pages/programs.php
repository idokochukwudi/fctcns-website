<?php
/**
 * Programs Page View Template - Updated with Program Corrections & Image-Focused Hero
 * 
 * @package FCTCNS
 * @version 4.9
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$page_title = $page_title ?? 'Nursing Programs | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Explore our accredited nursing education programs designed to develop competent healthcare professionals.';

// Define program images with fallbacks
$programImages = [
    'nd-nursing' => [
        'primary' => $baseUrl . '/assets/images/programs/nd-nursing.jpg',
        'fallback' => $baseUrl . '/assets/images/programs/national-diploma.jpg',
        'placeholder' => $baseUrl . '/assets/images/placeholder/program-placeholder.jpg'
    ],
    'basic-nursing' => [
        'primary' => $baseUrl . '/assets/images/programs/basic-nursing.jpg',
        'fallback' => $baseUrl . '/assets/images/programs/basic-nursing-program.jpg',
        'placeholder' => $baseUrl . '/assets/images/placeholder/program-placeholder.jpg'
    ],
    'basic-midwifery' => [
        'primary' => $baseUrl . '/assets/images/programs/basic-midwifery.jpg',
        'fallback' => $baseUrl . '/assets/images/programs/midwifery-program.jpg',
        'placeholder' => $baseUrl . '/assets/images/placeholder/program-placeholder.jpg'
    ],
    'post-basic' => [
        'primary' => $baseUrl . '/assets/images/programs/post-basic.jpg',
        'fallback' => $baseUrl . '/assets/images/programs/post-basic-nursing.jpg',
        'placeholder' => $baseUrl . '/assets/images/placeholder/program-placeholder.jpg'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
}
main.programs-page { 
    margin-top: 0 !important; 
    padding-top: 0 !important; 
}
.programs-hero { 
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
    font-size: 15px; 
    line-height: 1.6; 
    color: var(--color-gray-800); 
    background: var(--color-white); 
}

.container { 
    width: 100%; 
    max-width: 1200px; 
    margin: 0 auto; 
    padding: 0 var(--spacing-md); 
}

/* ==========================================================================
   HERO SECTION - Clean Image Background (Like Admissions Page)
   ========================================================================== */
.programs-hero {
    position: relative;
    height: 80vh; /* Reduced from 85vh */
    max-height: 650px; /* Reduced from 700px */
    min-height: 500px; /* Reduced from 550px */
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-primary); /* Fallback color */
}

.programs-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-image: url('<?php echo $baseUrl; ?>/assets/images/programs/programs-hero.jpg');
    background-attachment: fixed; /* Parallax effect */
}

.programs-hero-bg::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.5) 0%,
        rgba(93, 74, 138, 0.3) 50%,
        rgba(212, 165, 116, 0.2) 100%
    );
}

/* FIXED: Added padding-top to prevent header overlap */
.programs-hero-content {
    position: relative;
    z-index: 3;
    color: var(--color-white);
    text-align: center;
    padding: var(--spacing-lg); /* Reduced from var(--spacing-xl) */
    max-width: 800px;
    width: 100%;
    /* FIXED: Reduced opacity from 0.6 to 0.4 for more transparency */
    background: rgba(0, 0, 0, 0.4); /* More transparent background */
    border-radius: var(--radius-lg);
    backdrop-filter: blur(5px); /* Reduced blur for more transparency */
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin: 0 var(--spacing-md);
    margin-top: 80px; /* Increased from 60px for better spacing */
}

.programs-hero-badge { 
    display: inline-block;
    background: var(--color-accent); 
    color: var(--color-gray-900); 
    padding: 0.6rem 1.5rem; /* Reduced padding */
    border-radius: var(--radius-full); 
    font-size: 0.85rem; /* Slightly smaller */
    font-weight: 700; 
    margin-bottom: var(--spacing-md); /* Reduced from var(--spacing-lg) */
    letter-spacing: 1px;
    text-transform: uppercase;
    font-family: var(--font-heading);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.programs-hero-title { 
    font-family: var(--font-heading); 
    font-size: clamp(1.8rem, 5vw, 3rem); /* Reduced max from 3.5rem to 3rem */
    font-weight: 800; 
    color: var(--color-white); 
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
    line-height: 1.1;
    margin-bottom: var(--spacing-sm); /* Reduced from var(--spacing-md) */
    letter-spacing: -0.5px;
}

.programs-hero-subtitle { 
    font-size: clamp(1rem, 2.5vw, 1.3rem); /* Reduced max from 1.5rem to 1.3rem */
    color: rgba(255,255,255,0.95);
    line-height: 1.5; /* Reduced from 1.6 */
    max-width: 700px;
    margin: 0 auto;
    font-weight: 400;
    margin-bottom: var(--spacing-md); /* Added margin bottom */
}

/* Program Stats - Made more compact */
.hero-stats {
    display: flex;
    justify-content: center;
    gap: var(--spacing-lg); /* Reduced from var(--spacing-xl) */
    margin: var(--spacing-md) 0; /* Reduced from var(--spacing-lg) */
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
    min-width: 100px; /* Ensure consistent width */
}

.stat-number {
    display: block;
    font-family: var(--font-heading);
    font-size: 2rem; /* Reduced from 2.5rem */
    font-weight: 700;
    color: var(--color-accent);
    line-height: 1;
}

.stat-label {
    display: block;
    font-size: 0.8rem; /* Reduced from 0.9rem */
    color: rgba(255, 255, 255, 0.9);
    margin-top: 0.25rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Hero Actions - Made more compact */
.hero-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
    margin-top: var(--spacing-lg); /* Reduced from var(--spacing-xl) */
    flex-wrap: wrap;
}

.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem; /* Reduced from 0.75rem */
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 0.8rem 1.8rem; /* Reduced from 1rem 2.5rem */
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid var(--color-accent);
    font-family: var(--font-heading);
    font-size: 1rem; /* Reduced from 1.1rem */
    letter-spacing: 0.3px;
    min-height: 48px; /* Reduced from 56px */
    box-shadow: 0 4px 15px rgba(212, 165, 116, 0.3);
}

.btn-hero-primary:hover { 
    background: var(--color-accent-dark); 
    color: var(--color-gray-900);
    transform: translateY(-3px); 
    box-shadow: 0 8px 25px rgba(212, 165, 116, 0.4); 
    border-color: var(--color-accent-dark);
}

.btn-hero-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem; /* Reduced from 0.75rem */
    background: transparent;
    color: var(--color-white);
    padding: 0.8rem 1.8rem; /* Reduced from 1rem 2.5rem */
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid rgba(255, 255, 255, 0.3);
    font-family: var(--font-heading);
    font-size: 1rem; /* Reduced from 1.1rem */
    letter-spacing: 0.3px;
    min-height: 48px; /* Reduced from 56px */
    backdrop-filter: blur(10px);
}

.btn-hero-secondary:hover { 
    background: rgba(255, 255, 255, 0.1); 
    color: var(--color-white);
    transform: translateY(-3px); 
    box-shadow: var(--shadow-soft); 
    border-color: var(--color-accent);
}

/* ==========================================================================
   SECTIONS & PROGRAM CARDS - Consistent Styling
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
}

.section-title { 
    font-family: var(--font-heading); 
    font-size: clamp(1.5rem, 3vw, 2rem); 
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
    font-size: 1.1rem; 
    color: var(--color-gray-800); 
    line-height: 1.6; 
    font-weight: 400;
    max-width: 700px;
    margin: 0 auto;
    margin-top: var(--spacing-md);
}

.grid { 
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); 
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
    display: flex;
    flex-direction: column;
    height: 100%;
}
.program-card:hover { 
    transform: translateY(-8px); 
    box-shadow: var(--shadow-elevated); 
    border-color: var(--color-primary-light);
}

.program-card-header {
    padding: var(--spacing-lg);
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    color: var(--color-white);
    position: relative;
}

/* Program Images - Improved with better fallback */
.program-card-img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
    transition: var(--transition-smooth);
    background: var(--color-gray-50);
}

.program-card:hover .program-card-img {
    transform: scale(1.05);
}

.program-card-title { 
    font-family: var(--font-heading); 
    font-size: 1.6rem; 
    font-weight: 600; 
    color: var(--color-white); 
    margin-bottom: var(--spacing-sm); 
}

.program-card-duration { 
    display: flex; 
    align-items: center; 
    gap: 0.5rem; 
    color: rgba(255,255,255,0.9); 
    font-size: 0.95rem; 
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
    flex-grow: 1;
}

.program-highlights {
    background: var(--color-primary-very-light);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    border-left: 4px solid var(--color-primary);
    margin-bottom: var(--spacing-md);
}

.highlight-title { 
    font-family: var(--font-heading); 
    font-size: 1.1rem; 
    color: var(--color-primary); 
    margin-bottom: var(--spacing-sm); 
    display: flex; 
    align-items: center; 
    gap: 0.5rem; 
}

.highlight-list { 
    list-style: none; 
    padding-left: 0; 
    margin: 0;
}

.highlight-list li { 
    padding: 0.4rem 0; 
    position: relative; 
    padding-left: 1.5rem; 
    color: var(--color-gray-800); 
    line-height: 1.5;
}

.highlight-list li::before { 
    content: '✓'; 
    position: absolute; 
    left: 0; 
    color: var(--color-accent); 
    font-weight: bold; 
    font-size: 1.1rem;
}

.program-card-footer {
    padding: var(--spacing-md) 0 0 0;
    border-top: 1px solid var(--color-gray-100);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
    margin-top: auto;
}

/* ==========================================================================
   BUTTONS - Consistent with Admissions Page
   ========================================================================== */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid var(--color-accent);
    font-family: var(--font-heading);
    font-size: 1rem;
    letter-spacing: 0.3px;
    min-height: 44px;
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
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid var(--color-primary);
    font-family: var(--font-heading);
    font-size: 1rem;
    letter-spacing: 0.3px;
    min-height: 44px;
}

.btn-secondary:hover { 
    background: var(--color-primary-dark); 
    color: var(--color-white);
    transform: translateY(-3px); 
    box-shadow: var(--shadow-soft); 
    border-color: var(--color-primary-dark);
}

.btn-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    color: var(--color-primary);
    border: 2px solid var(--color-primary);
    background: transparent;
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    font-family: var(--font-heading);
    font-size: 1rem;
    letter-spacing: 0.3px;
    min-height: 44px;
}

.btn-outline:hover { 
    background: var(--color-primary); 
    color: var(--color-white);
    transform: translateY(-3px); 
    box-shadow: var(--shadow-soft);
}

/* ==========================================================================
   CTA SECTION - Consistent with Admissions Page
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

/* ==========================================================================
   PROGRAM STATUS BADGE
   ========================================================================== */
.program-status {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: var(--spacing-xs);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.status-transition {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

/* ==========================================================================
   IMAGE LOADING STATES
   ========================================================================== */
.img-loading {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ==========================================================================
   PROGRAM DURATION BREAKDOWN
   ========================================================================== */
.program-duration-breakdown {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255,255,255,0.8);
    font-size: 0.9rem;
    margin-top: 0.25rem;
    flex-wrap: wrap;
}

.duration-breakdown {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    background: rgba(255,255,255,0.1);
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    font-size: 0.85rem;
}

/* ==========================================================================
   RESPONSIVE DESIGN
   ========================================================================== */
@media (max-width: 768px) {
    :root {
        --spacing-xs: 0.5rem;
        --spacing-sm: 0.875rem;
        --spacing-md: 1.25rem;
        --spacing-lg: 1.75rem;
        --spacing-xl: 2rem;
        --spacing-xxl: 2.5rem;
    }
    
    .programs-hero {
        height: 70vh;
        min-height: 450px;
        background-attachment: scroll; /* Remove parallax on mobile */
    }
    
    .programs-hero-content {
        padding: var(--spacing-md); /* Further reduced on mobile */
        margin: 0 var(--spacing-sm);
        margin-top: 60px; /* Reduced for mobile */
        background: rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(3px);
    }
    
    .programs-hero-badge {
        padding: 0.5rem 1.2rem; /* Further reduced */
        font-size: 0.8rem;
        margin-bottom: var(--spacing-sm); /* Further reduced */
    }
    
    .programs-hero-title {
        font-size: clamp(1.6rem, 4vw, 2.2rem); /* Further reduced */
        margin-bottom: var(--spacing-xs); /* Further reduced */
    }
    
    .programs-hero-subtitle {
        font-size: clamp(0.95rem, 2vw, 1.1rem); /* Further reduced */
        line-height: 1.4; /* Further reduced */
        margin-bottom: var(--spacing-sm); /* Further reduced */
    }
    
    .hero-stats {
        gap: var(--spacing-sm); /* Further reduced */
        margin: var(--spacing-sm) 0; /* Further reduced */
    }
    
    .stat-number {
        font-size: 1.6rem; /* Further reduced */
    }
    
    .stat-label {
        font-size: 0.75rem; /* Further reduced */
    }
    
    .hero-actions {
        flex-direction: column;
        gap: var(--spacing-sm);
        margin-top: var(--spacing-md); /* Further reduced */
    }
    
    .btn-hero-primary,
    .btn-hero-secondary {
        width: 100%;
        justify-content: center;
        padding: 0.7rem 1.5rem; /* Further reduced */
        min-height: 44px; /* Further reduced */
        font-size: 0.95rem; /* Further reduced */
    }
    
    .section {
        padding: var(--spacing-xl) 0;
    }
    
    .grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .program-card-img {
        height: 200px;
    }
    
    .program-card-header {
        padding: var(--spacing-md);
    }
    
    .program-card-body {
        padding: var(--spacing-md);
    }
    
    .program-card-footer {
        flex-direction: column;
        align-items: stretch;
        gap: var(--spacing-sm);
    }
    
    .btn-primary,
    .btn-secondary,
    .btn-outline {
        width: 100%;
        justify-content: center;
    }
    
    .program-highlights {
        padding: var(--spacing-sm);
    }
    
    .program-duration-breakdown {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}

@media (max-width: 480px) {
    .programs-hero {
        height: 65vh; /* Further reduced */
        min-height: 400px;
    }
    
    .programs-hero-content {
        margin-top: 50px; /* Further reduced */
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(2px);
    }
    
    .programs-hero-badge {
        padding: 0.4rem 1rem; /* Further reduced */
        font-size: 0.75rem;
    }
    
    .programs-hero-title {
        font-size: 1.5rem; /* Further reduced */
    }
    
    .programs-hero-subtitle {
        font-size: 0.9rem; /* Further reduced */
    }
    
    .hero-stats {
        flex-direction: column;
        gap: var(--spacing-xs);
    }
    
    .stat-item {
        min-width: auto;
    }
    
    .section-title {
        font-size: 1.4rem;
    }
    
    .program-card-title {
        font-size: 1.4rem;
    }
    
    .program-card-img {
        height: 180px;
    }
    
    .highlight-title {
        font-size: 1rem;
    }
    
    .highlight-list li {
        font-size: 0.9rem;
    }
}

/* Print Styles */
@media print {
    .programs-hero {
        height: auto;
        min-height: auto;
        background: var(--color-white);
        color: var(--color-black);
    }
    
    .programs-hero-bg {
        display: none;
    }
    
    .programs-hero-content {
        color: var(--color-black);
        background: transparent;
        backdrop-filter: none;
        border: none;
        margin-top: 0;
    }
    
    .btn-hero-primary,
    .btn-hero-secondary,
    .btn-primary,
    .btn-secondary,
    .btn-outline {
        display: none;
    }
    
    .program-card {
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
    }
    
    .program-card-img {
        display: none;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        transition: none !important;
        animation: none !important;
    }
    
    .program-card:hover,
    .btn-hero-primary:hover,
    .btn-hero-secondary:hover,
    .btn-primary:hover,
    .btn-secondary:hover,
    .btn-outline:hover {
        transform: none !important;
    }
    
    .img-loading {
        animation: none !important;
    }
}

:focus-visible {
    outline: 2px solid var(--color-accent);
    outline-offset: 2px;
    border-radius: var(--radius-sm);
}
</style>
</head>
<body>

<main class="programs-page">
    <!-- Hero Section with Clean Background Image -->
    <section class="programs-hero">
        <div class="programs-hero-bg"></div>
        <div class="container">
            <div class="programs-hero-content">
                <span class="programs-hero-badge">Accredited Programs</span>
                <h1 class="programs-hero-title">Nursing Education Programs</h1>
                <p class="programs-hero-subtitle">
                    Fully accredited programs combining theoretical excellence with hands-on clinical training.
                </p>
                
                <!-- Program Stats -->
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">4</span>
                        <span class="stat-label">Programs</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Accredited</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">4+</span>
                        <span class="stat-label">Years Duration</span>
                    </div>
                </div>
                
                <!-- Hero Actions -->
                <div class="hero-actions">
                    <a href="#programs" class="btn-hero-primary">
                        <i class="fas fa-book-medical"></i>
                        Explore Programs
                    </a>
                    <a href="<?php echo $baseUrl; ?>/admissions" class="btn-hero-secondary">
                        <i class="fas fa-graduation-cap"></i>
                        Apply Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Grid -->
    <section class="section" id="programs">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Accredited Programs</h2>
                <p class="section-subtitle">Choose from our range of nationally recognized nursing programs.</p>
            </div>

            <div class="grid">
                <!-- National Diploma in Nursing (Updated) -->
                <article class="program-card">
                    <img 
                        src="<?php echo $programImages['nd-nursing']['primary']; ?>" 
                        alt="National Diploma in Nursing Program" 
                        class="program-card-img img-loading"
                        data-fallback="<?php echo $programImages['nd-nursing']['fallback']; ?>"
                        data-placeholder="<?php echo $programImages['nd-nursing']['placeholder']; ?>"
                    >
                    
                    <div class="program-card-header">
                        <h3 class="program-card-title">ND/HND Nursing Programme</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock"></i> Duration: 4 Years (Non-Terminal)
                        </div>
                        <div class="program-duration-breakdown">
                            <span class="duration-breakdown">ND: 2 Years</span>
                            <span>→</span>
                            <span class="duration-breakdown">HND: 2 Years</span>
                        </div>
                        <span class="program-status status-active">Currently Available</span>
                    </div>
                    
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Comprehensive four-year non-terminal program leading to National Diploma (ND) and Higher National Diploma (HND) qualifications. Combines theoretical knowledge with practical skills for advanced healthcare delivery.
                        </p>
                        
                        <div class="program-highlights">
                            <div class="highlight-title"><i class="fas fa-star"></i> Key Features</div>
                            <ul class="highlight-list">
                                <li>NBTE accredited program</li>
                                <li>Non-terminal ND/HND structure</li>
                                <li>JAMB UTME pathway</li>
                                <li>Clinical rotations and internships</li>
                                <li>Modern simulation labs</li>
                                <li>Research methodology training</li>
                            </ul>
                        </div>
                        
                        <div class="program-card-footer">
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-outline">Learn More</a>
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary">Apply Now</a>
                        </div>
                    </div>
                </article>

                <!-- Basic Nursing (Transition) -->
                <article class="program-card">
                    <img 
                        src="<?php echo $programImages['basic-nursing']['primary']; ?>" 
                        alt="Basic Nursing Program" 
                        class="program-card-img img-loading"
                        data-fallback="<?php echo $programImages['basic-nursing']['fallback']; ?>"
                        data-placeholder="<?php echo $programImages['basic-nursing']['placeholder']; ?>"
                    >
                    
                    <div class="program-card-header">
                        <h3 class="program-card-title">Basic Nursing</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock"></i> Duration: 3 Years
                        </div>
                        <span class="program-status status-transition">Program Transition</span>
                    </div>
                    
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Comprehensive general nursing education preparing students for registration as Registered Nurses (RN). <strong>Note: This program is transitioning to the ND/HND system.</strong>
                        </p>
                        
                        <div class="program-highlights">
                            <div class="highlight-title"><i class="fas fa-star"></i> Key Features</div>
                            <ul class="highlight-list">
                                <li>Full NMCN accreditation</li>
                                <li>Extensive clinical practice</li>
                                <li>Simulation training</li>
                                <li>Exam preparation support</li>
                                <li>Professional development</li>
                            </ul>
                        </div>
                        
                        <div class="program-card-footer">
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-outline">Learn More</a>
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary">Contact for Info</a>
                        </div>
                    </div>
                </article>

                <!-- Basic Midwifery (Transition) -->
                <article class="program-card">
                    <img 
                        src="<?php echo $programImages['basic-midwifery']['primary']; ?>" 
                        alt="Basic Midwifery Program" 
                        class="program-card-img img-loading"
                        data-fallback="<?php echo $programImages['basic-midwifery']['fallback']; ?>"
                        data-placeholder="<?php echo $programImages['basic-midwifery']['placeholder']; ?>"
                    >
                    
                    <div class="program-card-header">
                        <h3 class="program-card-title">Basic Midwifery</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock"></i> Duration: 3 Years
                        </div>
                        <span class="program-status status-transition">Program Transition</span>
                    </div>
                    
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Specialized training in maternal and child healthcare, antenatal care, delivery, and postnatal services. <strong>Note: This program is transitioning to the ND/HND system.</strong>
                        </p>
                        
                        <div class="program-highlights">
                            <div class="highlight-title"><i class="fas fa-star"></i> Key Features</div>
                            <ul class="highlight-list">
                                <li>NMCN approved</li>
                                <li>Maternity clinical placements</li>
                                <li>Family planning training</li>
                                <li>Neonatal care focus</li>
                                <li>Community outreach</li>
                            </ul>
                        </div>
                        
                        <div class="program-card-footer">
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-outline">Learn More</a>
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary">Contact for Info</a>
                        </div>
                    </div>
                </article>

                <!-- Post Basic Nursing Specialization (Transition) -->
                <article class="program-card">
                    <img 
                        src="<?php echo $programImages['post-basic']['primary']; ?>" 
                        alt="Post Basic Nursing Specialization" 
                        class="program-card-img img-loading"
                        data-fallback="<?php echo $programImages['post-basic']['fallback']; ?>"
                        data-placeholder="<?php echo $programImages['post-basic']['placeholder']; ?>"
                    >
                    
                    <div class="program-card-header">
                        <h3 class="program-card-title">Post Basic Nursing Specialization</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock"></i> Duration: 18 Months
                        </div>
                        <span class="program-status status-transition">Program Transition</span>
                    </div>
                    
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Advanced specialization for registered nurses in intensive care, pediatrics, perioperative, or psychiatric nursing. <strong>Note: This program is transitioning to the ND/HND system.</strong>
                        </p>
                        
                        <div class="program-highlights">
                            <div class="highlight-title"><i class="fas fa-star"></i> Key Features</div>
                            <ul class="highlight-list">
                                <li>Specialist clinical training</li>
                                <li>Leadership development</li>
                                <li>Research methodology</li>
                                <li>Career advancement pathway</li>
                                <li>Expert faculty mentorship</li>
                            </ul>
                        </div>
                        
                        <div class="program-card-footer">
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-outline">Learn More</a>
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary">Contact for Info</a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <h2 class="section-title">Begin Your Nursing Career Today</h2>
            <p class="section-subtitle" style="max-width: 700px; margin: 0 auto var(--spacing-xl);">
                Join thousands of graduates making a difference in healthcare across Nigeria.
            </p>
            <div style="display: flex; gap: var(--spacing-md); justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary"><i class="fas fa-file-alt"></i> Apply Now</a>
                <a href="<?php echo $baseUrl; ?>/contact" class="btn-secondary"><i class="fas fa-phone-alt"></i> Contact Admissions</a>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced image loading with multiple fallbacks
    const programImages = document.querySelectorAll('.program-card-img');
    
    function loadImageWithFallback(img) {
        const primarySrc = img.src;
        const fallbackSrc = img.dataset.fallback;
        const placeholderSrc = img.dataset.placeholder;
        
        // First, try the primary image
        const primaryImage = new Image();
        
        primaryImage.onload = function() {
            img.src = primarySrc;
            img.classList.remove('img-loading');
        };
        
        primaryImage.onerror = function() {
            // If primary fails, try fallback
            const fallbackImage = new Image();
            
            fallbackImage.onload = function() {
                img.src = fallbackSrc;
                img.classList.remove('img-loading');
            };
            
            fallbackImage.onerror = function() {
                // If fallback fails, use placeholder
                img.src = placeholderSrc;
                img.alt = 'Program Image - Image not available';
                img.classList.remove('img-loading');
            };
            
            fallbackImage.src = fallbackSrc;
        };
        
        primaryImage.src = primarySrc;
    }
    
    // Load all images with fallback
    programImages.forEach(img => {
        loadImageWithFallback(img);
    });
    
    // Handle hero background image loading
    const heroBg = document.querySelector('.programs-hero-bg');
    if (heroBg) {
        const heroImage = new Image();
        
        heroImage.onload = function() {
            console.log('Programs hero background image loaded successfully');
        };
        
        heroImage.onerror = function() {
            console.warn('Programs hero background image failed to load');
            heroBg.style.backgroundImage = 'linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))';
            heroBg.style.opacity = '1';
        };
        
        heroImage.src = '<?php echo $baseUrl; ?>/assets/images/programs/programs-hero.jpg';
    }
    
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Animate program cards on scroll
    const programCards = document.querySelectorAll('.program-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    programCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>

</body>
</html>