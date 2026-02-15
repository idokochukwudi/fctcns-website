<?php
/**
 * Homepage View Template - Professional Redesign v3.3
 * Premium Design with Fixed Mobile Layout, No Gaps, and Resources Links
 * UPDATED: Program information from programs page integrated
 * NOTE: Community Nursing Program will soon be integrated fully
 * 
 * @package FCTCNS
 * @version 6.4 - Programs Information Integrated
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

// Program images from programs page
$programImages = [
    'nd-nursing'    => rtrim($baseUrl,'/') . '/assets/images/programs/nd-nursing.jpg',
    'basic-nursing' => rtrim($baseUrl,'/') . '/assets/images/programs/basic-nursing.jpg',
    'basic-midwifery'=> rtrim($baseUrl,'/') . '/assets/images/programs/basic-midwifery.jpg',
    'post-basic'    => rtrim($baseUrl,'/') . '/assets/images/programs/post-basic.jpg',
    'community-nursing' => rtrim($baseUrl,'/') . '/assets/images/programs/community-nursing.jpg', // Placeholder for community program
];
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
       RESET & BASE STYLES - ABSOLUTELY NO GAPS
       ========================================================================== */
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
        overflow-x: hidden;
        background: #FFFFFF;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 16px;
        line-height: 1.6;
        color: #1A1F2E;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Remove ALL spacing from main content */
    .homepage-content {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
    }

    /* Ensure first element has no margin/padding */
    .homepage-content > *:first-child {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    section {
        display: block;
    }

    /* ==========================================================================
       DESIGN TOKENS
       ========================================================================== */
    :root {
        /* Colors - Purple & Gold */
        --purple-deep: #4B1F5A;
        --purple: #6C3082;
        --purple-medium: #8A4FA0;
        --purple-light: #A875BD;
        --purple-pale: #F3EAF8;
        --purple-soft: #F9F3FC;
        
        --gold-deep: #B48C3A;
        --gold: #C9A44A;
        --gold-light: #D8B86C;
        --gold-pale: #FDF6E7;
        --gold-soft: #FFFAF0;
        
        /* Additional colors from programs page */
        --green: #5D9B8C;
        --green-pale: #EEF7F5;
        --amber: #C9870A;
        --amber-pale: #FEF6E4;
        
        /* Neutrals */
        --ink: #1A1F2E;
        --ink-mid: #2A3042;
        --ink-soft: #3A4055;
        --slate: #5B677B;
        --mist: #8E9AAC;
        --border: #E9EDF2;
        --surface: #F7F9FC;
        --white: #FFFFFF;
        
        /* Gradients */
        --purple-gradient: linear-gradient(135deg, #4B1F5A 0%, #6C3082 50%, #8A4FA0 100%);
        --gold-gradient: linear-gradient(135deg, #B48C3A 0%, #C9A44A 50%, #D8B86C 100%);
        
        /* Shadows */
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
        --shadow-md: 0 6px 24px rgba(0,0,0,0.06);
        --shadow-lg: 0 16px 48px rgba(0,0,0,0.08);
        --shadow-xl: 0 32px 80px rgba(0,0,0,0.12);
        --shadow-purple: 0 10px 30px rgba(108,48,130,0.25);
        --shadow-gold: 0 10px 30px rgba(201,164,74,0.2);
        
        /* Border Radius */
        --radius-sm: 6px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --radius-xl: 28px;
        --radius-full: 9999px;
        
        /* Typography */
        --font-display: 'Cormorant Garamond', Georgia, serif;
        --font-body: 'Outfit', system-ui, sans-serif;
        --font-mono: 'JetBrains Mono', monospace;
        
        /* Spacing - Clean and Consistent */
        --gutter: clamp(1rem, 4vw, 4rem);
        --container-max: 1400px;
        
        --space-xs: 0.5rem;
        --space-sm: 1rem;
        --space-md: 1.5rem;
        --space-lg: 2rem;
        --space-xl: 3rem;
        --space-xxl: 5rem;
    }

    /* ==========================================================================
       CONTAINER
       ========================================================================== */
    .container {
        width: 100%;
        max-width: var(--container-max);
        margin: 0 auto;
        padding: 0 var(--gutter);
    }

    /* ==========================================================================
       TYPOGRAPHY
       ========================================================================== */
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-display);
        font-weight: 700;
        line-height: 1.2;
        color: var(--ink);
    }

    .section-header {
        text-align: center;
        margin-bottom: var(--space-xl);
    }

    .section-header h2 {
        font-size: clamp(2rem, 4vw, 2.8rem);
        margin-bottom: var(--space-sm);
        position: relative;
        display: inline-block;
    }

    .section-header h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: var(--gold-gradient);
        border-radius: 3px;
    }

    .section-header p {
        font-size: clamp(1rem, 1.5vw, 1.2rem);
        color: var(--slate);
        max-width: 700px;
        margin: var(--space-md) auto 0;
    }

    /* ==========================================================================
       BUTTONS
       ========================================================================== */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-family: var(--font-body);
        font-size: 0.95rem;
        font-weight: 500;
        line-height: 1;
        text-decoration: none;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn--primary {
        background: var(--purple-gradient);
        color: white;
        background-size: 200% auto;
        box-shadow: var(--shadow-purple);
    }

    .btn--primary:hover {
        background-position: right center;
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(108,48,130,0.35);
    }

    .btn--secondary {
        background: var(--gold-gradient);
        color: white;
        background-size: 200% auto;
        box-shadow: var(--shadow-gold);
    }

    .btn--secondary:hover {
        background-position: right center;
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(201,164,74,0.3);
    }

    .btn--outline {
        background: transparent;
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
    }

    .btn--outline:hover {
        background: rgba(255,255,255,0.1);
        border-color: white;
        transform: translateY(-2px);
    }

    .btn--purple-outline {
        background: transparent;
        color: var(--purple);
        border: 2px solid var(--purple);
    }

    .btn--purple-outline:hover {
        background: var(--purple);
        color: white;
        transform: translateY(-2px);
    }

    /* Additional button styles from programs page */
    .btn--ghost {
        background: transparent;
        color: white;
        border: 1.5px solid rgba(255,255,255,0.35);
    }
    
    .btn--ghost:hover {
        border-color: white;
        background: rgba(255,255,255,0.1);
        color: white;
        transform: translateY(-2px);
    }
    
    .btn--lg {
        padding: 0.85rem 2rem;
        font-size: 1rem;
    }

    /* ==========================================================================
       HERO CAROUSEL - No Gap at Top with Resources Button
       ========================================================================== */
    .hero-section {
        position: relative;
        width: 100%;
        height: 90vh;
        min-height: 600px;
        max-height: 1000px;
        overflow: hidden;
        margin: 0;
        padding: 0;
        top: 0;
        left: 0;
    }

    .hero-carousel {
        position: relative;
        width: 100%;
        height: 100%;
    }

    /* Slides */
    .carousel-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.8s ease, visibility 0.8s ease;
    }

    .carousel-slide.active {
        opacity: 1;
        visibility: visible;
        z-index: 2;
    }

    /* Background Image */
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

    /* Subtle gradient at bottom for text readability */
    .carousel-slide-bg::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 40%;
        background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
        pointer-events: none;
        z-index: 2;
    }

    /* Top Badge - Fixed Positioning for All Screens */
    .carousel-top-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 30;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    @media (min-width: 768px) {
        .carousel-top-badge {
            top: var(--space-lg);
            left: var(--gutter);
        }
    }

    .badge-year {
        font-family: var(--font-display);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--gold);
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        padding: 0.35rem 1rem;
        border-radius: 40px;
        border: 1px solid rgba(255,255,255,0.2);
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .badge-year span {
        color: white;
        font-family: var(--font-body);
        font-size: 0.75rem;
        font-weight: 400;
        margin-right: 0.25rem;
    }

    @media (max-width: 480px) {
        .badge-year {
            font-size: 0.9rem;
            padding: 0.25rem 0.75rem;
        }
    }

    /* Content Container */
    .carousel-content-container {
        position: relative;
        z-index: 10;
        width: 100%;
        height: 100%;
        max-width: var(--container-max);
        margin: 0 auto;
        padding: 0 var(--gutter);
        display: flex;
        align-items: center;
    }

    /* Content Wrapper */
    .carousel-content-wrapper {
        width: 100%;
        max-width: 650px;
        padding: var(--space-lg);
        background: rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: var(--radius-lg);
        border: 1px solid rgba(255,255,255,0.15);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        animation: slideUp 0.8s ease forwards;
    }

    /* Desktop positioning */
    @media (min-width: 1200px) {
        .carousel-content-wrapper {
            margin-left: 5%;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .carousel-content-wrapper {
            margin-left: 3%;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .carousel-content-wrapper {
            margin-left: 2%;
            max-width: 550px;
        }
    }

    /* Mobile - Full width, no overlap with controls */
    @media (max-width: 767px) {
        .carousel-content-wrapper {
            margin: 0 auto;
            max-width: 90%;
            padding: var(--space-md);
            background: rgba(0, 0, 0, 0.5);
            margin-bottom: 80px; /* Space for controls */
        }
        
        .carousel-content-container {
            align-items: flex-end;
            padding-bottom: 20px;
        }
    }

    /* Eyebrow */
    .carousel-eyebrow {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .eyebrow-line {
        width: 50px;
        height: 2px;
        background: var(--gold-gradient);
    }

    .eyebrow-text {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 500;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--gold-light);
    }

    /* Title */
    .carousel-title {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 4vw, 3.5rem);
        font-weight: 700;
        line-height: 1.1;
        color: white;
        margin-bottom: 1.25rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    /* Subtitle */
    .carousel-subtitle {
        font-size: clamp(0.9rem, 1.3vw, 1.1rem);
        color: rgba(255,255,255,0.95);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        max-width: 550px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }

    /* CTA Group - Updated with Resources button */
    .carousel-cta-group {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    @media (max-width: 480px) {
        .carousel-cta-group {
            flex-direction: column;
            width: 100%;
        }
        
        .carousel-cta-group .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Slide Counter - Repositioned on Mobile */
    .carousel-counter {
        position: absolute;
        bottom: var(--space-lg);
        right: var(--gutter);
        z-index: 20;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        padding: 0.4rem 1rem;
        border-radius: 40px;
        border: 1px solid rgba(255,255,255,0.15);
        color: white;
    }

    @media (max-width: 767px) {
        .carousel-counter {
            bottom: 100px; /* Above controls */
            right: var(--gutter);
        }
    }

    .counter-current {
        font-family: var(--font-display);
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--gold);
        line-height: 1;
    }

    .counter-sep {
        font-family: var(--font-mono);
        font-size: 0.8rem;
        color: rgba(255,255,255,0.5);
    }

    .counter-total {
        font-family: var(--font-mono);
        font-size: 0.8rem;
        color: white;
    }

    /* Slider Controls - Better Mobile Positioning */
    .slider-controls {
        position: absolute;
        bottom: var(--space-lg);
        left: 50%;
        transform: translateX(-50%);
        z-index: 25;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        width: 100%;
        max-width: 500px;
        padding: 0 var(--gutter);
        pointer-events: none;
    }

    @media (max-width: 767px) {
        .slider-controls {
            bottom: 20px;
            padding: 0 15px;
        }
    }

    /* Progress Bar */
    .progress-bar {
        width: 100%;
        height: 3px;
        background: rgba(255,255,255,0.2);
        border-radius: 3px;
        overflow: hidden;
        pointer-events: all;
        cursor: pointer;
    }

    .progress-fill {
        height: 100%;
        background: var(--gold-gradient);
        width: 0%;
        transition: width 0.1s linear;
    }

    /* Controls Container */
    .controls-container {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        pointer-events: all;
    }

    /* Arrow Controls */
    .arrow-controls {
        display: flex;
        gap: 0.5rem;
    }

    .arrow-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.2);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        color: white;
        font-size: 1rem;
    }

    .arrow-btn:hover {
        background: var(--purple);
        border-color: var(--gold);
        transform: scale(1.1);
    }

    @media (max-width: 480px) {
        .arrow-btn {
            width: 36px;
            height: 36px;
        }
    }

    /* Dot Indicators - Hide on very small screens */
    .dot-indicators {
        display: flex;
        gap: 0.5rem;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        padding: 0.4rem 0.8rem;
        border-radius: 40px;
        border: 1px solid rgba(255,255,255,0.15);
    }

    @media (max-width: 480px) {
        .dot-indicators {
            display: none;
        }
    }

    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        padding: 0;
    }

    .dot.active {
        width: 24px;
        background: var(--gold-gradient);
        border-radius: 4px;
    }

    .dot:hover:not(.active) {
        background: rgba(255,255,255,0.8);
    }

    /* Animation */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .carousel-eyebrow,
    .carousel-title,
    .carousel-subtitle,
    .carousel-cta-group {
        animation: slideUp 0.5s ease forwards;
        opacity: 0;
    }

    .carousel-slide.active .carousel-eyebrow {
        animation-delay: 0.2s;
    }

    .carousel-slide.active .carousel-title {
        animation-delay: 0.3s;
    }

    .carousel-slide.active .carousel-subtitle {
        animation-delay: 0.4s;
    }

    .carousel-slide.active .carousel-cta-group {
        animation-delay: 0.5s;
    }

    /* ==========================================================================
       PROVOST WELCOME SECTION - Directly Under Carousel
       ========================================================================== */
    .provost-section {
        padding: var(--space-xxl) 0;
        background: linear-gradient(135deg, var(--white) 0%, var(--purple-soft) 100%);
        position: relative;
        overflow: hidden;
        margin: 0;
    }

    .provost-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--space-xl);
        align-items: start;
        position: relative;
        z-index: 2;
    }

    @media (min-width: 992px) {
        .provost-grid {
            grid-template-columns: 380px 1fr;
        }
    }

    /* Left Column */
    .provost-left {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
    }

    /* Photo Frame */
    .provost-photo {
        position: relative;
        width: 100%;
        max-width: 380px;
        margin: 0 auto;
    }

    .provost-photo::before {
        content: '';
        position: absolute;
        top: 15px;
        right: -15px;
        bottom: -15px;
        left: 15px;
        background: linear-gradient(135deg, var(--purple), var(--gold));
        border-radius: var(--radius-lg);
        opacity: 0.4;
        transition: opacity 0.3s ease;
        z-index: 1;
    }

    .provost-photo:hover::before {
        opacity: 0.7;
    }

    .provost-photo img {
        position: relative;
        z-index: 2;
        width: 100%;
        height: auto;
        aspect-ratio: 3/4;
        object-fit: cover;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-xl);
        border: 3px solid white;
    }

    /* Name Card */
    .provost-name-card {
        background: var(--purple-gradient);
        border-radius: var(--radius-md);
        padding: var(--space-md);
        text-align: center;
        color: white;
        box-shadow: var(--shadow-purple);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .provost-name-card h3 {
        font-family: var(--font-display);
        font-size: 1.5rem;
        color: white;
        margin-bottom: 0.25rem;
    }

    .provost-name-card p {
        font-family: var(--font-mono);
        font-size: 0.8rem;
        color: var(--gold-light);
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* Accreditation Badges */
    .provost-badges {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
        justify-content: center;
    }

    .badge {
        background: white;
        border: 1px solid var(--purple-light);
        border-radius: 40px;
        padding: 0.5rem 1rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--purple-deep);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-sm);
    }

    .badge i {
        color: var(--gold);
    }

    /* Right Column */
    .provost-right {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }

    /* Pull Quote */
    .provost-quote {
        border-left: 4px solid var(--gold);
        padding: var(--space-md) var(--space-lg);
        background: var(--gold-pale);
        border-radius: 0 var(--radius-md) var(--radius-md) 0;
        position: relative;
    }

    .provost-quote::before {
        content: '"';
        position: absolute;
        top: 10px;
        left: 20px;
        font-family: var(--font-display);
        font-size: 4rem;
        color: var(--gold);
        opacity: 0.2;
    }

    .provost-quote p {
        font-family: var(--font-display);
        font-size: clamp(1.2rem, 2vw, 1.4rem);
        font-style: italic;
        color: var(--purple-deep);
        position: relative;
        z-index: 2;
    }

    /* Message Body */
    .provost-message {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
    }

    .provost-message p {
        font-size: 1rem;
        color: var(--ink-soft);
        line-height: 1.8;
    }

    .provost-message p strong {
        color: var(--purple-deep);
        background: var(--purple-pale);
        padding: 0.1rem 0.3rem;
        border-radius: 4px;
        font-weight: 600;
    }

    /* Signature */
    .provost-signature {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding-top: var(--space-md);
        border-top: 2px solid;
        border-image: linear-gradient(90deg, var(--gold), var(--purple), transparent) 1;
        border-top-style: solid;
        border-image-slice: 1;
    }

    .signature-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--gold), var(--purple));
        box-shadow: 0 0 20px rgba(201,164,74,0.3);
    }

    .signature-text strong {
        display: block;
        font-family: var(--font-display);
        font-size: 1.2rem;
        color: var(--purple-deep);
    }

    .signature-text span {
        font-size: 0.8rem;
        color: var(--slate);
        font-family: var(--font-mono);
    }

    /* CTA Button - Updated to Resources */
    .provost-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        background: var(--purple-gradient);
        color: white;
        text-decoration: none;
        border-radius: var(--radius-sm);
        font-weight: 600;
        transition: all 0.3s ease;
        width: fit-content;
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: var(--shadow-purple);
        background-size: 200% auto;
    }

    .provost-cta:hover {
        background-position: right center;
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(108,48,130,0.4);
    }

    .provost-cta i {
        color: var(--gold);
        transition: transform 0.3s ease;
    }

    .provost-cta:hover i {
        transform: translateX(5px);
    }

    @media (max-width: 480px) {
        .provost-cta {
            width: 100%;
            justify-content: center;
        }
    }

    /* ==========================================================================
       APPLICATION STATUS BANNER
       ========================================================================== */
    .status-section {
        padding: 0 0 var(--space-xxl) 0;
        background: var(--white);
    }

    .status-banner {
        background: var(--gold-pale);
        border-left: 4px solid var(--gold);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        display: flex;
        gap: var(--space-md);
        align-items: flex-start;
        box-shadow: var(--shadow-md);
    }

    .status-banner i {
        font-size: 2rem;
        color: var(--gold);
        flex-shrink: 0;
    }

    .status-banner h3 {
        font-size: 1.3rem;
        color: var(--purple-deep);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-banner p {
        color: var(--ink-soft);
        margin-bottom: 0.5rem;
    }

    .status-banner strong {
        color: var(--purple-deep);
    }

    @media (max-width: 768px) {
        .status-banner {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    /* ==========================================================================
       STATISTICS SECTION
       ========================================================================== */
    .stats-section {
        padding: var(--space-xxl) 0;
        background: var(--white);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-md);
        margin-top: var(--space-xl);
    }

    @media (min-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: var(--space-lg);
        }
    }

    .stat-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--purple-gradient);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg), var(--shadow-purple);
        border-color: var(--purple-light);
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        background: var(--purple-gradient);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto var(--space-md);
        box-shadow: var(--shadow-purple);
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-number {
        font-family: var(--font-display);
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--purple-deep);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--slate);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500;
    }

    /* ==========================================================================
       ACCREDITATION SECTION
       ========================================================================== */
    .accreditation-section {
        padding: var(--space-xxl) 0;
        background: var(--purple-soft);
    }

    .accreditation-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--space-lg);
        max-width: 1000px;
        margin: var(--space-xl) auto 0;
    }

    @media (min-width: 768px) {
        .accreditation-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .accred-card {
        background: var(--white);
        border: 1px solid var(--purple-light);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-md);
    }

    .accred-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl), var(--shadow-purple);
        border-color: var(--purple);
    }

    .accred-icon {
        width: 80px;
        height: 80px;
        background: var(--purple-gradient);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto var(--space-lg);
        box-shadow: var(--shadow-purple);
        transition: all 0.3s ease;
    }

    .accred-card:hover .accred-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .accred-card h3 {
        font-size: 1.4rem;
        color: var(--purple-deep);
        margin-bottom: var(--space-sm);
    }

    .accred-card p {
        color: var(--slate);
        line-height: 1.6;
    }

    /* ==========================================================================
       PROGRAMS SECTION - UPDATED with full program information from programs page
       ========================================================================== */
    .programs-section {
        padding: var(--space-xxl) 0;
        background: var(--white);
    }

    .programs-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--space-lg);
        margin-top: var(--space-xl);
    }

    @media (min-width: 768px) {
        .programs-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .program-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        animation: fadeInUp 0.5s ease forwards;
    }

    .program-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--purple-light);
    }

    .program-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, var(--purple), var(--purple-light));
        transform: scaleY(0);
        transform-origin: center;
        transition: transform 0.28s ease;
        border-radius: 3px 0 0 3px;
        z-index: 1;
    }

    .program-card:hover::before {
        transform: scaleY(1);
    }

    .program-card-img-wrap {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: var(--surface);
    }

    .program-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }

    .program-card:hover .program-card-img {
        transform: scale(1.05);
    }

    .program-status {
        position: absolute;
        top: 0.85rem;
        left: 0.85rem;
        font-family: var(--font-mono);
        font-size: 0.6rem;
        font-weight: 500;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 4px;
        z-index: 2;
    }

    .program-status--active {
        background: var(--green);
        color: white;
    }

    .program-status--transition {
        background: var(--amber);
        color: white;
    }

    .program-status--note {
        background: var(--purple);
        color: white;
    }

    .program-card-content {
        padding: var(--space-lg);
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .program-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
    }

    .program-card h3 {
        font-family: var(--font-display);
        font-size: 1.3rem;
        color: var(--purple-deep);
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }

    .program-duration {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--purple-pale);
        color: var(--purple-dark);
        border: 1px solid var(--purple-light);
        border-radius: var(--radius-full);
        font-family: var(--font-mono);
        font-size: 0.65rem;
        font-weight: 500;
        padding: 3px 10px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .program-duration i {
        font-size: 0.6rem;
    }

    .program-breakdown {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.85rem;
        flex-wrap: wrap;
    }

    .program-breakdown-pill {
        background: var(--surface);
        color: var(--slate);
        border: 1px solid var(--border);
        border-radius: var(--radius-full);
        font-family: var(--font-mono);
        font-size: 0.62rem;
        padding: 2px 9px;
    }

    .program-breakdown-arrow {
        color: var(--mist);
        font-size: 0.7rem;
    }

    .program-card p {
        color: var(--slate);
        margin-bottom: var(--space-md);
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .program-highlights {
        background: var(--purple-pale);
        border-left: 3px solid var(--purple);
        border-radius: 0 var(--radius-md) var(--radius-md) 0;
        padding: 0.9rem 1.1rem;
        margin-bottom: 1.25rem;
    }

    .program-highlights-title {
        font-family: var(--font-mono);
        font-size: 0.65rem;
        font-weight: 500;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--purple-dark);
        margin-bottom: 0.6rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .program-highlights-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.2rem 1rem;
        list-style: none;
    }

    @media (max-width: 560px) {
        .program-highlights-list {
            grid-template-columns: 1fr;
        }
    }

    .program-highlights-list li {
        font-size: 0.82rem;
        color: var(--ink-soft);
        line-height: 1.5;
        padding: 0.2rem 0;
        padding-left: 1.1rem;
        position: relative;
    }

    .program-highlights-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 9px;
        width: 5px;
        height: 5px;
        background: var(--gold);
        border-radius: 50%;
    }

    .program-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding-top: 1.1rem;
        border-top: 1px solid var(--border);
        flex-wrap: wrap;
        margin-top: auto;
    }

    .program-footer-meta {
        font-family: var(--font-mono);
        font-size: 0.68rem;
        color: var(--mist);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .program-footer-meta i {
        font-size: 0.62rem;
        color: var(--slate);
    }

    .program-actions {
        display: flex;
        gap: 0.5rem;
    }

    @media (max-width: 480px) {
        .program-card-footer {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .program-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }

    /* ==========================================================================
       ENVIRONMENT SECTION - Updated with Resources link
       ========================================================================== */
    .environment-section {
        padding: var(--space-xxl) 0;
        background: var(--purple-soft);
    }

    .environment-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--space-lg);
        margin-top: var(--space-xl);
    }

    @media (min-width: 768px) {
        .environment-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .environment-card {
        background: var(--white);
        border: 1px solid var(--purple-light);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }

    .environment-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl), var(--shadow-purple);
        border-color: var(--purple);
    }

    .environment-image {
        height: 220px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .image-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: var(--space-sm) var(--space-md);
        background: linear-gradient(to top, rgba(75,31,90,0.9), transparent);
        color: white;
        font-family: var(--font-mono);
        font-size: 0.75rem;
    }

    .environment-content {
        padding: var(--space-lg);
    }

    .environment-content h3 {
        font-size: 1.3rem;
        color: var(--purple-deep);
        margin-bottom: var(--space-sm);
    }

    .environment-content p {
        color: var(--slate);
        margin-bottom: var(--space-md);
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* ==========================================================================
       CTA SECTION - Updated with Resources link
       ========================================================================== */
    .cta-section {
        background: var(--purple-gradient);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin: 0 0 var(--space-xxl) 0;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-xl);
    }

    .cta-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }

    .cta-content h2 {
        font-size: clamp(1.8rem, 3.5vw, 2.3rem);
        color: white;
        margin-bottom: var(--space-md);
    }

    .cta-content p {
        font-size: 1rem;
        color: rgba(255,255,255,0.95);
        margin-bottom: var(--space-lg);
        line-height: 1.6;
    }

    .cta-buttons {
        display: flex;
        gap: var(--space-sm);
        justify-content: center;
        flex-wrap: wrap;
    }

    @media (max-width: 480px) {
        .cta-buttons {
            flex-direction: column;
            align-items: stretch;
        }
        
        .cta-buttons .btn {
            width: 100%;
        }
    }

    /* CTA Dark Card from programs page */
    .program-cta-card {
        background: linear-gradient(160deg, #2A3042 0%, #3A4055 100%);
        border-radius: var(--radius-xl);
        padding: clamp(1.75rem, 4vw, 2.75rem);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        flex-wrap: wrap;
        box-shadow: var(--shadow-xl);
        position: relative;
        overflow: hidden;
        margin-top: var(--space-xxl);
    }

    .program-cta-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 15%;
        bottom: 15%;
        width: 3px;
        background: linear-gradient(to bottom, var(--purple-light), var(--purple));
        border-radius: 3px;
    }

    .program-cta-card-content {
        flex: 1;
        min-width: 220px;
    }

    .program-cta-card-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--purple);
        color: white;
        font-family: var(--font-mono);
        font-size: 0.62rem;
        font-weight: 500;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 4px;
        margin-bottom: 0.85rem;
    }

    .program-cta-card-title {
        font-family: var(--font-display);
        font-size: clamp(1.4rem, 2.5vw, 2rem);
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        letter-spacing: -0.01em;
    }

    .program-cta-card-desc {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.6;
    }

    .program-cta-card-actions {
        display: flex;
        gap: 0.85rem;
        flex-wrap: wrap;
    }

    @media (max-width: 480px) {
        .program-cta-card {
            flex-direction: column;
        }
        
        .program-cta-card-actions {
            flex-direction: column;
            width: 100%;
        }
        
        .program-cta-card-actions .btn {
            justify-content: center;
        }
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

    .stat-card,
    .accred-card,
    .environment-card {
        opacity: 0;
        animation: fadeInUp 0.5s ease forwards;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }

    .accred-card:nth-child(1) { animation-delay: 0.2s; }
    .accred-card:nth-child(2) { animation-delay: 0.3s; }

    .program-card:nth-child(1) { animation-delay: 0.1s; }
    .program-card:nth-child(2) { animation-delay: 0.15s; }
    .program-card:nth-child(3) { animation-delay: 0.2s; }
    .program-card:nth-child(4) { animation-delay: 0.25s; }
    .program-card:nth-child(5) { animation-delay: 0.3s; }

    .environment-card:nth-child(1) { animation-delay: 0.2s; }
    .environment-card:nth-child(2) { animation-delay: 0.3s; }

    /* ==========================================================================
       ACCESSIBILITY
       ========================================================================== */
    :focus-visible {
        outline: 3px solid var(--gold);
        outline-offset: 3px;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        border: 0;
    }
</style>
</head>
<body>

<!-- Homepage Content -->
<main id="main-content" class="homepage-content" role="main">
    
    <!-- ========== HERO CAROUSEL - No Gap at Top with Resources Button ========== -->
    <section class="hero-section" aria-label="Featured content carousel">
        <?php if (empty($carouselSlides)): ?>
            <div style="background: var(--purple-deep); color: white; padding: 6rem 2rem; text-align: center; height: 100%; display: flex; align-items: center; justify-content: center;">
                <div>
                    <h1 style="font-family: var(--font-display); font-size: 2.5rem;">Welcome to FCT College of Nursing Sciences</h1>
                    <p style="opacity: 0.8; margin-top: 1rem;">NMCN & NBTE Accredited Nursing Education Since 1989</p>
                </div>
            </div>
        <?php else: ?>
            <div id="heroCarousel" class="hero-carousel" role="region" aria-label="Featured slides">
                <div class="carousel-inner">
                    <?php foreach ($carouselSlides as $index => $slide): ?>
                    <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                         data-slide="<?php echo $index; ?>"
                         aria-hidden="<?php echo $index === 0 ? 'false' : 'true'; ?>"
                         role="group"
                         aria-label="Slide <?php echo $index + 1; ?> of <?php echo count($carouselSlides); ?>">

                        <!-- Background Image -->
                        <div class="carousel-slide-bg"
                             style="background-image: url('<?php echo e($slide['image_path']); ?>');"
                             role="img"
                             aria-label="<?php echo e($slide['title']); ?>">
                        </div>

                        <!-- Content Container -->
                        <div class="carousel-content-container">
                            <div class="carousel-content-wrapper">
                                <!-- Eyebrow -->
                                <div class="carousel-eyebrow">
                                    <span class="eyebrow-line"></span>
                                    <span class="eyebrow-text">Excellence in Nursing Education</span>
                                </div>

                                <!-- Title -->
                                <h1 class="carousel-title">
                                    <?php echo e($slide['title']); ?>
                                </h1>

                                <!-- Subtitle -->
                                <p class="carousel-subtitle">
                                    <?php echo e($slide['subtitle']); ?>
                                </p>

                                <!-- CTA Buttons - UPDATED with Resources button -->
                                <div class="carousel-cta-group">
                                    <?php if (!empty($slide['button_text']) && !empty($slide['button_link'])): ?>
                                    <a href="<?php echo e($slide['button_link']); ?>" 
                                       class="btn btn--primary">
                                        <?php echo e($slide['button_text']); ?>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?php echo $baseUrl; ?>/about" class="btn btn--outline">
                                        Learn More
                                    </a>
                                    <!-- Changed from /programs to /resources with icon -->
                                    <a href="<?php echo $baseUrl; ?>/resources" class="btn btn--secondary">
                                        <i class="fas fa-book-open"></i> Explore Resources
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Top Badge - Est. 1989 -->
                <div class="carousel-top-badge">
                    <div class="badge-year">
                        <span>Est.</span> 1989
                    </div>
                </div>

                <!-- Slide Counter -->
                <div class="carousel-counter" aria-hidden="true">
                    <span class="counter-current" id="carouselCurrentNum">01</span>
                    <span class="counter-sep">/</span>
                    <span class="counter-total"><?php echo str_pad(count($carouselSlides), 2, '0', STR_PAD_LEFT); ?></span>
                </div>

                <!-- Slider Controls -->
                <div class="slider-controls">
                    <!-- Progress Bar -->
                    <div class="progress-bar" onclick="carouselController.goToFromProgress(event)">
                        <div class="progress-fill" id="carouselProgress"></div>
                    </div>

                    <!-- Controls Container -->
                    <div class="controls-container">
                        <!-- Arrow Controls -->
                        <div class="arrow-controls">
                            <button class="arrow-btn" onclick="carouselController.prev()" aria-label="Previous slide">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="arrow-btn" onclick="carouselController.next()" aria-label="Next slide">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>

                        <!-- Dot Indicators -->
                        <div class="dot-indicators" role="tablist" aria-label="Slide indicators">
                            <?php foreach ($carouselSlides as $index => $slide): ?>
                            <button class="dot <?php echo $index === 0 ? 'active' : ''; ?>"
                                    onclick="carouselController.goTo(<?php echo $index; ?>)"
                                    role="tab"
                                    aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                                    aria-label="Go to slide <?php echo $index + 1; ?>">
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </section>

    <!-- ========== PROVOST WELCOME MESSAGE - Directly Under Carousel with Updated CTA ========== -->
    <section class="provost-section" aria-label="Message from the Provost">
        <div class="container">
            <div class="section-header">
                <h2>Message from the <span style="color: var(--purple);">Provost</span></h2>
                <p>Leadership with vision, commitment to excellence</p>
            </div>

            <div class="provost-grid">
                <!-- Left Column -->
                <div class="provost-left">
                    <div class="provost-photo">
                        <img src="<?php echo $baseUrl; ?>/assets/images/provost/provost-photo.jpg" 
                             alt="Comr. Deborah Yusuf - Provost"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/400x500?text=Provost';">
                    </div>

                    <div class="provost-name-card">
                        <h3>Comr. Deborah Yusuf</h3>
                        <p>Ag. Provost FCTCNS</p>
                    </div>

                    <div class="provost-badges">
                        <span class="badge"><i class="fas fa-check-circle"></i> NMCN Accredited</span>
                        <span class="badge"><i class="fas fa-check-circle"></i> NBTE Approved</span>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="provost-right">
                    <div class="provost-quote">
                        <p>"Our mission is not merely to train nurses — it is to mould compassionate leaders who will transform healthcare delivery across Nigeria and beyond."</p>
                    </div>

                    <div class="provost-message">
                        <p>It is with immense pride and heartfelt warmth that I welcome you to the <strong>FCT College of Nursing Sciences, Gwagwalada, Abuja</strong> — an institution that has stood as a beacon of quality nursing education in Nigeria's Federal Capital Territory for over three decades.</p>
                        
                        <p>Since our founding in <strong>1989</strong>, we have remained steadfast in our commitment to producing well-rounded, highly competent, and ethically grounded nursing professionals. Our graduates serve with distinction in hospitals, clinics, and communities across the nation.</p>
                        
                        <p>Whether you are a prospective student, a parent, or a healthcare partner — I invite you to experience the transformative education we offer. Our faculty are not just teachers; they are mentors, researchers, and practitioners fully dedicated to your growth and success.</p>
                        
                        <p>We are fully accredited by the <strong>Nursing and Midwifery Council of Nigeria (NMCN)</strong> and the <strong>National Board for Technical Education (NBTE)</strong>.</p>
                    </div>

                    <div class="provost-signature">
                        <div class="signature-dot"></div>
                        <div class="signature-text">
                            <strong>Comr. Deborah Yusuf</strong>
                            <span>Ag. Provost, FCT College of Nursing Sciences</span>
                        </div>
                    </div>

                    <!-- Updated to Resources with icon -->
                    <a href="<?php echo $baseUrl; ?>/resources" class="provost-cta">
                        <i class="fas fa-book-open"></i> Explore Resources <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== APPLICATION STATUS BANNER ========== -->
    <section class="status-section">
        <div class="container">
            <div class="status-banner">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <h3><i class="fas fa-clock"></i> 2025/2026 Admissions Status</h3>
                    <p>The application portal for the 2025/2026 academic session is now closed. Sales of forms period ended on 28th September 2025. No further applications are being accepted for this session.</p>
                    <p><strong>Next Admissions Cycle:</strong> 2026/2027 academic session</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== STATISTICS SECTION ========== -->
    <section class="stats-section" aria-label="College statistics">
        <div class="container">
            <div class="section-header">
                <h2>Our Impact in Numbers</h2>
                <p>A legacy of excellence in nursing education since 1989</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-number">35+</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-number">5,000+</div>
                    <div class="stat-label">Nursing Graduates</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-number">100%</div>
                    <div class="stat-label">NMCN Accredited</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-number">5</div>
                    <div class="stat-label">Academic Programs</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ACCREDITATION SECTION ========== -->
    <section class="accreditation-section" aria-label="Accreditation">
        <div class="container">
            <div class="section-header">
                <h2>Nationally Recognized Accreditation</h2>
                <p>Our programs meet the highest standards set by Nigeria's regulatory bodies</p>
            </div>

            <div class="accreditation-grid">
                <div class="accred-card">
                    <div class="accred-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h3>NMCN Approved</h3>
                    <p>Nursing & Midwifery Council of Nigeria - Full accreditation for all nursing programs</p>
                </div>

                <div class="accred-card">
                    <div class="accred-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <h3>NBTE Accredited</h3>
                    <p>National Board for Technical Education - Accreditation for technical programs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== PROGRAMS SECTION - UPDATED with full program information from programs page ========== -->
    <section class="programs-section" aria-label="Academic programs">
        <div class="container">
            <div class="section-header">
                <h2>Our Accredited Programs</h2>
                <p>Nationally recognised nursing programs designed for real-world healthcare delivery</p>
            </div>

            <div class="programs-grid">

                <!-- CARD 1: ND/HND Nursing Programme -->
                <article class="program-card">
                    <div class="program-card-img-wrap">
                        <img src="<?php echo $programImages['nd-nursing']; ?>"
                             alt="ND/HND Nursing Programme"
                             class="program-card-img"
                             loading="lazy"
                             onerror="this.closest('.program-card-img-wrap').style.background='var(--purple-pale)'; this.style.display='none';">
                        <span class="program-status program-status--active">Currently Available</span>
                    </div>

                    <div class="program-card-content">
                        <div class="program-card-top">
                            <h3>ND/HND Nursing Programme</h3>
                            <span class="program-duration">
                                <i class="far fa-clock"></i> 4 Years · Non-Terminal
                            </span>
                        </div>

                        <div class="program-breakdown">
                            <span class="program-breakdown-pill">ND — 2 Years</span>
                            <span class="program-breakdown-arrow"><i class="fas fa-arrow-right"></i></span>
                            <span class="program-breakdown-pill">HND — 2 Years</span>
                        </div>

                        <p>
                            Comprehensive four-year non-terminal programme leading to National Diploma (ND) and Higher National Diploma (HND) qualifications. Combines theoretical knowledge with practical skills for advanced healthcare delivery.
                        </p>

                        <div class="program-highlights">
                            <div class="program-highlights-title"><i class="fas fa-list-check"></i> Key Features</div>
                            <ul class="program-highlights-list">
                                <li>NBTE accredited programme</li>
                                <li>Non-terminal ND/HND structure</li>
                                <li>JAMB UTME pathway</li>
                                <li>Clinical rotations & internships</li>
                                <li>Modern simulation labs</li>
                                <li>Research methodology training</li>
                            </ul>
                        </div>

                        <div class="program-card-footer">
                            <div class="program-footer-meta">
                                <i class="fas fa-shield-halved"></i> NBTE & NMCN Approved
                            </div>
                            <div class="program-actions">
                                <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn--purple-outline btn-sm">Learn More</a>
                                <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn--primary btn-sm">Apply Now</a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- CARD 2: Basic Nursing -->
                <article class="program-card">
                    <div class="program-card-img-wrap">
                        <img src="<?php echo $programImages['basic-nursing']; ?>"
                             alt="Basic Nursing Programme"
                             class="program-card-img"
                             loading="lazy"
                             onerror="this.closest('.program-card-img-wrap').style.background='var(--purple-pale)'; this.style.display='none';">
                        <span class="program-status program-status--transition">Programme Transition</span>
                    </div>

                    <div class="program-card-content">
                        <div class="program-card-top">
                            <h3>Basic Nursing</h3>
                            <span class="program-duration">
                                <i class="far fa-clock"></i> 3 Years
                            </span>
                        </div>

                        <p>
                            Comprehensive general nursing education preparing students for registration as Registered Nurses (RN). <strong>Note: This programme is transitioning to the ND/HND system.</strong>
                        </p>

                        <div class="program-highlights">
                            <div class="program-highlights-title"><i class="fas fa-list-check"></i> Key Features</div>
                            <ul class="program-highlights-list">
                                <li>Full NMCN accreditation</li>
                                <li>Extensive clinical practice</li>
                                <li>Simulation training</li>
                                <li>Exam preparation support</li>
                                <li>Professional development</li>
                            </ul>
                        </div>

                        <div class="program-card-footer">
                            <div class="program-footer-meta">
                                <i class="fas fa-arrows-rotate"></i> Transitioning to ND/HND
                            </div>
                            <div class="program-actions">
                                <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn--purple-outline btn-sm">Learn More</a>
                                <a href="<?php echo $baseUrl; ?>/contact" class="btn btn--surface btn-sm">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- CARD 3: Basic Midwifery -->
                <article class="program-card">
                    <div class="program-card-img-wrap">
                        <img src="<?php echo $programImages['basic-midwifery']; ?>"
                             alt="Basic Midwifery Programme"
                             class="program-card-img"
                             loading="lazy"
                             onerror="this.closest('.program-card-img-wrap').style.background='var(--purple-pale)'; this.style.display='none';">
                        <span class="program-status program-status--transition">Programme Transition</span>
                    </div>

                    <div class="program-card-content">
                        <div class="program-card-top">
                            <h3>Basic Midwifery</h3>
                            <span class="program-duration">
                                <i class="far fa-clock"></i> 3 Years
                            </span>
                        </div>

                        <p>
                            Specialised training in maternal and child healthcare, antenatal care, delivery, and postnatal services. <strong>Note: This programme is transitioning to the ND/HND system.</strong>
                        </p>

                        <div class="program-highlights">
                            <div class="program-highlights-title"><i class="fas fa-list-check"></i> Key Features</div>
                            <ul class="program-highlights-list">
                                <li>NMCN approved</li>
                                <li>Maternity clinical placements</li>
                                <li>Family planning training</li>
                                <li>Neonatal care focus</li>
                                <li>Community outreach</li>
                            </ul>
                        </div>

                        <div class="program-card-footer">
                            <div class="program-footer-meta">
                                <i class="fas fa-arrows-rotate"></i> Transitioning to ND/HND
                            </div>
                            <div class="program-actions">
                                <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn--purple-outline btn-sm">Learn More</a>
                                <a href="<?php echo $baseUrl; ?>/contact" class="btn btn--surface btn-sm">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- CARD 4: Post Basic Nursing Specialisation -->
                <article class="program-card">
                    <div class="program-card-img-wrap">
                        <img src="<?php echo $programImages['post-basic']; ?>"
                             alt="Post Basic Nursing Specialisation"
                             class="program-card-img"
                             loading="lazy"
                             onerror="this.closest('.program-card-img-wrap').style.background='var(--purple-pale)'; this.style.display='none';">
                        <span class="program-status program-status--transition">Programme Transition</span>
                    </div>

                    <div class="program-card-content">
                        <div class="program-card-top">
                            <h3>Post Basic Nursing Specialisation</h3>
                            <span class="program-duration">
                                <i class="far fa-clock"></i> 18 Months
                            </span>
                        </div>

                        <p>
                            Advanced specialisation for registered nurses in intensive care, paediatrics, perioperative, or psychiatric nursing. <strong>Note: This programme is transitioning to the ND/HND system.</strong>
                        </p>

                        <div class="program-highlights">
                            <div class="program-highlights-title"><i class="fas fa-list-check"></i> Key Features</div>
                            <ul class="program-highlights-list">
                                <li>Specialist clinical training</li>
                                <li>Leadership development</li>
                                <li>Research methodology</li>
                                <li>Career advancement pathway</li>
                                <li>Expert faculty mentorship</li>
                            </ul>
                        </div>

                        <div class="program-card-footer">
                            <div class="program-footer-meta">
                                <i class="fas fa-arrows-rotate"></i> Transitioning to ND/HND
                            </div>
                            <div class="program-actions">
                                <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn--purple-outline btn-sm">Learn More</a>
                                <a href="<?php echo $baseUrl; ?>/contact" class="btn btn--surface btn-sm">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- CARD 5: Community Health (Soon to be fully integrated) -->
                <article class="program-card">
                    <div class="program-card-img-wrap">
                        <img src="<?php echo $programImages['community-nursing']; ?>"
                             alt="Community Health Nursing"
                             class="program-card-img"
                             loading="lazy"
                             onerror="this.closest('.program-card-img-wrap').style.background='var(--purple-pale)'; this.style.display='none';">
                        <span class="program-status program-status--note">Soon to be Integrated</span>
                    </div>

                    <div class="program-card-content">
                        <div class="program-card-top">
                            <h3>Community Health Nursing</h3>
                            <span class="program-duration">
                                <i class="far fa-clock"></i> 3 Years
                            </span>
                        </div>

                        <p>
                            Program focusing on public health, disease prevention, and community-based healthcare delivery. <strong>Note: This programme will soon be fully integrated into the ND/HND system.</strong>
                        </p>

                        <div class="program-highlights">
                            <div class="program-highlights-title"><i class="fas fa-list-check"></i> Key Features</div>
                            <ul class="program-highlights-list">
                                <li>Community health focus</li>
                                <li>Public health emphasis</li>
                                <li>Disease prevention</li>
                                <li>Health education</li>
                                <li>Rural healthcare</li>
                            </ul>
                        </div>

                        <div class="program-card-footer">
                            <div class="program-footer-meta">
                                <i class="fas fa-arrows-rotate"></i> Integration in Progress
                            </div>
                            <div class="program-actions">
                                <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn--purple-outline btn-sm">Learn More</a>
                                <a href="<?php echo $baseUrl; ?>/contact" class="btn btn--surface btn-sm">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </article>

            </div>
        </div>
    </section>

    <!-- ========== LEARNING ENVIRONMENT SECTION - UPDATED Resources Link ========== -->
    <section class="environment-section" aria-label="Learning environment">
        <div class="container">
            <div class="section-header">
                <h2>State-of-the-Art Learning Facilities</h2>
                <p>Modern facilities designed to provide hands-on training and real-world experience</p>
            </div>

            <div class="environment-grid">
                <div class="environment-card">
                    <div class="environment-image" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/homepage/environment-simulation-lab.jpg');">
                        <div class="image-caption">Advanced simulation laboratory</div>
                    </div>
                    <div class="environment-content">
                        <h3>Simulation Laboratories</h3>
                        <p>Train with high-fidelity manikins, virtual reality simulations, and fully equipped clinical environments that mirror real healthcare settings.</p>
                        <a href="<?php echo $baseUrl; ?>/facilities#labs" class="btn btn--purple-outline">
                            View Facilities <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="environment-card">
                    <div class="environment-image" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/homepage/environment-student-support.jpg');">
                        <div class="image-caption">Student support and collaboration spaces</div>
                    </div>
                    <div class="environment-content">
                        <h3>Learning Resources</h3>
                        <p>Access comprehensive learning materials, digital resources, and collaborative spaces designed to enhance your educational experience.</p>
                        <!-- Updated from /facilities#resources to /resources -->
                        <a href="<?php echo $baseUrl; ?>/resources" class="btn btn--purple-outline">
                            <i class="fas fa-book-open"></i> Explore Resources <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FINAL CALL TO ACTION - Enhanced with Programs CTA Card ========== -->
    <div class="container">
        <!-- Regular CTA Section -->
        <section class="cta-section" aria-label="Call to action">
            <div class="cta-content">
                <h2>Begin Your Nursing Journey Today</h2>
                <p>While the 2025/2026 admissions are closed, now is the perfect time to explore our programs, learn about our campus, and prepare for the next admissions cycle.</p>
                <div class="cta-buttons">
                    <!-- Updated from /programs to /resources -->
                    <a href="<?php echo $baseUrl; ?>/resources" class="btn btn--secondary">
                        <i class="fas fa-book-open"></i> Explore Resources
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" class="btn btn--outline">
                        <i class="fas fa-phone-alt"></i> Contact Admissions
                    </a>
                </div>
            </div>
        </section>

        <!-- Enhanced Programs CTA Card from programs page -->
        <div class="program-cta-card">
            <div class="program-cta-card-content">
                <span class="program-cta-card-tag"><i class="fas fa-graduation-cap"></i> Start Your Journey</span>
                <h2 class="program-cta-card-title">Begin Your Nursing Career Today</h2>
                <p class="program-cta-card-desc">
                    Join thousands of graduates making a difference in healthcare across Nigeria. Apply for the next admissions cycle.
                </p>
            </div>
            <div class="program-cta-card-actions">
                <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn--secondary btn--lg">
                    <i class="fas fa-file-alt"></i> Apply Now
                </a>
                <a href="<?php echo $baseUrl; ?>/contact" class="btn btn--ghost btn--lg">
                    <i class="fas fa-phone-alt"></i> Contact Admissions
                </a>
            </div>
        </div>
    </div>
</main>

<!-- ========== CAROUSEL JAVASCRIPT ========== -->
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
            
            // Start auto-play
            this.startAutoPlay();
            
            // Pause on interaction
            carousel.addEventListener('mouseenter', () => {
                this.stopAutoPlay();
                this.stopProgress();
            });
            
            carousel.addEventListener('mouseleave', () => {
                this.startAutoPlay();
                this.startProgress();
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
            
            // Update dots
            const dots = document.querySelectorAll('.dot');
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
                dot.setAttribute('aria-selected', i === index ? 'true' : 'false');
            });
            
            // Update counter
            const counter = document.getElementById('carouselCurrentNum');
            if (counter) {
                counter.textContent = String(index + 1).padStart(2, '0');
            }
            
            // Reset progress
            this.resetProgress();
            
            setTimeout(() => {
                this.isTransitioning = false;
            }, 800);
        },
        
        goTo(index) {
            this.stopAutoPlay();
            this.goToSlide(index);
            setTimeout(() => this.startAutoPlay(), 3000);
        },
        
        goToFromProgress(event) {
            if (!this.progressBar) return;
            
            const rect = event.currentTarget.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const percentage = (x / rect.width) * 100;
            const slideIndex = Math.floor((percentage / 100) * this.totalSlides);
            
            this.goTo(Math.min(slideIndex, this.totalSlides - 1));
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