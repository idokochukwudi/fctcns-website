<?php
/**
 * About Page View Template - Updated with Consistent Color Scheme
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
   HERO SECTION - Consistent with Admissions Page
   ========================================================================== */
.about-hero {
    position: relative;
    height: 75vh;
    max-height: 650px;
    min-height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
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
        rgba(0, 0, 0, 0.4) 0%,
        rgba(0, 0, 0, 0.25) 50%,
        rgba(0, 0, 0, 0.15) 100%
    );
}

.about-hero-content {
    position: relative;
    z-index: 3;
    color: var(--color-white);
    max-width: 700px;
    padding: var(--spacing-xl);
    margin: 0 auto;
    text-align: center;
    margin-top: 15vh;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(8px);
    border-radius: var(--radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.15);
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
    font-size: clamp(1.8rem, 4vw, 2.8rem); 
    font-weight: 700; 
    color: var(--color-white); 
    text-shadow: 0 2px 6px rgba(0,0,0,0.5);
    line-height: 1.2;
    margin-bottom: var(--spacing-sm);
}

.about-hero-subtitle { 
    font-size: clamp(1rem, 2.5vw, 1.3rem); 
    color: rgba(255,255,255,0.95);
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto;
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
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
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
}

.card:hover { 
    transform: translateY(-8px); 
    box-shadow: var(--shadow-elevated); 
    border-color: var(--color-primary-light);
}

.card-img { 
    width: 100%; 
    height: 220px; 
    object-fit: cover; 
}

.card-body { 
    padding: var(--spacing-lg); 
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.card-title { 
    font-family: var(--font-heading); 
    font-size: 1.4rem; 
    font-weight: 600; 
    color: var(--color-primary); 
    margin-bottom: var(--spacing-md); 
    line-height: 1.3;
}

.card-text { 
    color: var(--color-gray-800); 
    line-height: 1.6;
    flex-grow: 1;
}

.badge-card { 
    text-align: center; 
    padding: var(--spacing-xl);
    display: flex;
    flex-direction: column;
    align-items: center;
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
    padding: 0.6rem 0; 
    position: relative; 
    padding-left: 1.8rem; 
    color: var(--color-gray-800);
    line-height: 1.5;
}

.values-list li::before { 
    content: '✓'; 
    position: absolute; 
    left: 0; 
    color: var(--color-accent); 
    font-weight: bold; 
    font-size: 1.1rem; 
}

/* ==========================================================================
   STATISTICS - Enhanced Display
   ========================================================================== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-soft);
    border-color: var(--color-primary-light);
}

.stat-number {
    font-family: var(--font-heading);
    font-size: 2.8rem;
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
   GALLERY CAROUSEL - Redesigned for Mobile & Desktop
   ========================================================================== */
.gallery-container {
    position: relative;
    margin-top: var(--spacing-xl);
}

.gallery-carousel {
    position: relative;
    height: 550px;
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
}

.gallery-slide.active { 
    opacity: 1; 
}

/* Desktop Caption - Positioned to the side */
.gallery-caption {
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    width: 45%;
    max-width: 450px;
    background: rgba(93, 74, 138, 0.92); /* var(--color-primary) with opacity */
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

/* Mobile Caption - Overlay at bottom */
@media (max-width: 1024px) {
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
            rgba(93, 74, 138, 0.85),
            rgba(93, 74, 138, 0.7)
        );
        padding: var(--spacing-lg);
        border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .gallery-caption h3 {
        font-size: 1.5rem;
        margin-bottom: var(--spacing-sm);
    }
    
    .gallery-caption p {
        font-size: 1rem;
    }
}

/* Gallery Navigation Dots */
.gallery-dots {
    display: flex;
    justify-content: center;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-lg);
    position: absolute;
    bottom: var(--spacing-md);
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
   BUTTONS - Consistent with Admissions Page
   ========================================================================== */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 0.9rem 2rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid var(--color-accent);
    font-family: var(--font-heading);
    font-size: 1rem;
    letter-spacing: 0.3px;
    min-height: 50px;
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
    padding: 0.9rem 2rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid var(--color-primary);
    font-family: var(--font-heading);
    font-size: 1rem;
    letter-spacing: 0.3px;
    min-height: 50px;
}

.btn-secondary:hover { 
    background: var(--color-primary-dark); 
    color: var(--color-white);
    transform: translateY(-3px); 
    box-shadow: var(--shadow-soft); 
    border-color: var(--color-primary-dark);
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
   RESPONSIVE DESIGN
   ========================================================================== */
@media (max-width: 1024px) {
    .gallery-carousel {
        height: 500px;
    }
}

@media (max-width: 768px) {
    :root {
        --spacing-xs: 0.5rem;
        --spacing-sm: 0.875rem;
        --spacing-md: 1.25rem;
        --spacing-lg: 1.75rem;
        --spacing-xl: 2rem;
        --spacing-xxl: 2.5rem;
    }
    
    .about-hero {
        height: 60vh;
        min-height: 400px;
    }
    
    .about-hero-content {
        margin-top: 10vh;
        padding: var(--spacing-lg);
        margin-left: var(--spacing-md);
        margin-right: var(--spacing-md);
    }
    
    .grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }
    
    .stat-number {
        font-size: 2.2rem;
    }
    
    .gallery-carousel {
        height: 400px;
    }
    
    .gallery-caption {
        padding: var(--spacing-md);
    }
    
    .gallery-caption h3 {
        font-size: 1.4rem;
        margin-bottom: var(--spacing-xs);
    }
    
    .gallery-caption p {
        font-size: 0.95rem;
    }
    
    .btn-primary,
    .btn-secondary {
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        min-height: 44px;
    }
    
    .gallery-dots {
        bottom: var(--spacing-sm);
    }
    
    .gallery-dot {
        width: 10px;
        height: 10px;
    }
}

@media (max-width: 480px) {
    .about-hero {
        height: 55vh;
        min-height: 350px;
    }
    
    .about-hero-badge {
        padding: 0.4rem 1rem;
        font-size: 0.75rem;
    }
    
    .about-hero-title {
        font-size: 1.6rem;
    }
    
    .about-hero-subtitle {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.4rem;
    }
    
    .card-title {
        font-size: 1.2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .gallery-carousel {
        height: 350px;
    }
    
    .gallery-caption {
        padding: var(--spacing-sm);
    }
    
    .gallery-caption h3 {
        font-size: 1.2rem;
    }
    
    .gallery-caption p {
        font-size: 0.85rem;
    }
    
    .gallery-dots {
        bottom: var(--spacing-xs);
    }
}

/* Print Styles */
@media print {
    .about-hero {
        height: auto;
        min-height: auto;
        background: var(--color-white);
        color: var(--color-black);
    }
    
    .about-hero-bg {
        display: none;
    }
    
    .about-hero-content {
        color: var(--color-black);
        background: transparent;
        backdrop-filter: none;
        border: none;
    }
    
    .btn-primary,
    .btn-secondary {
        display: none;
    }
    
    .card {
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
    }
    
    .gallery-carousel {
        display: none;
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
}

:focus-visible {
    outline: 2px solid var(--color-accent);
    outline-offset: 2px;
    border-radius: var(--radius-sm);
}
</style>
</head>
<body>

<main class="about-page">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="about-hero-bg"></div>
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
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Mission, Vision & Values</h2>
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
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Impact in Numbers</h2>
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
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Institutional Leadership</h2>
                <p class="section-subtitle">Experienced professionals guiding our institution toward educational excellence.</p>
            </div>
            <div class="grid">
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/assets/images/leadership/fct-minister.jpg" alt="Ezenwo Nyesom Wike CON, FCT Minister" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';">
                    <div class="card-body">
                        <h3 class="card-title">Ezenwo Nyesom Wike CON</h3>
                        <p class="card-text">FCT Minister<br>Federal Capital Territory Administration</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/assets/images/leadership/mandate-secretary.jpg" alt="Dr. Adedolapo Fasawe" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';">
                    <div class="card-body">
                        <h3 class="card-title">Dr. Adedolapo Fasawe</h3>
                        <p class="card-text">Mandate Secretary<br>Health Services & Environment Secretariat</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/assets/images/leadership/permanent-secretary.jpg" alt="Dr. Babagana Adam" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';">
                    <div class="card-body">
                        <h3 class="card-title">Dr. Babagana Adam</h3>
                        <p class="card-text">Permanent Secretary<br>Health Services & Environment Secretariat</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/assets/images/leadership/director-nursing.jpg" alt="Mrs Ijoema Jimi Bada" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';">
                    <div class="card-body">
                        <h3 class="card-title">Mrs Ijoema Jimi Bada</h3>
                        <p class="card-text">Director, Nursing Services<br>Health Services & Environment Secretariat</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/assets/images/leadership/college-provost.jpg" alt="Comr. Deborah Yusuf" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder/person-placeholder.jpg';">
                    <div class="card-body">
                        <h3 class="card-title">Comr. Deborah Yusuf</h3>
                        <p class="card-text">Provost, FCTCNS<br>FCT College of Nursing Sciences</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Accreditation -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Institutional Accreditation</h2>
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

    <!-- Gallery Carousel - Redesigned -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Learning Environment</h2>
                <p class="section-subtitle">Modern facilities supporting excellence in nursing education.</p>
            </div>
            
            <div class="gallery-container">
                <div class="gallery-carousel">
                    <!-- Slide 1 -->
                    <div class="gallery-slide active" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/about/simulation-lab.jpg');">
                        <div class="gallery-caption">
                            <h3>Simulation Laboratory</h3>
                            <p>State-of-the-art simulation lab where students practice clinical skills in a controlled, realistic environment.</p>
                        </div>
                    </div>
                    
                    <!-- Slide 2 -->
                    <div class="gallery-slide" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/about/library.jpg');">
                        <div class="gallery-caption">
                            <h3>Medical Library</h3>
                            <p>Comprehensive collection of nursing journals, textbooks, and digital resources for research and study.</p>
                        </div>
                    </div>
                    
                    <!-- Slide 3 -->
                    <div class="gallery-slide" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/about/classroom.jpg');">
                        <div class="gallery-caption">
                            <h3>Interactive Classrooms</h3>
                            <p>Technology-enhanced learning spaces designed for collaborative nursing education and discussion.</p>
                        </div>
                    </div>
                    
                    <!-- Slide 4 -->
                    <div class="gallery-slide" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/about/campus-building.jpg');">
                        <div class="gallery-caption">
                            <h3>Main Campus</h3>
                            <p>The heart of our institution where future nursing professionals begin their transformative journey.</p>
                        </div>
                    </div>
                    
                    <!-- Navigation Dots -->
                    <div class="gallery-dots">
                        <div class="gallery-dot active" data-slide="0"></div>
                        <div class="gallery-dot" data-slide="1"></div>
                        <div class="gallery-dot" data-slide="2"></div>
                        <div class="gallery-dot" data-slide="3"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <h2 class="section-title">Join Our Nursing Community</h2>
            <p class="section-subtitle" style="max-width: 700px; margin: 0 auto var(--spacing-xl);">Begin your professional nursing journey at one of Nigeria's most respected institutions.</p>
            <div style="display: flex; gap: var(--spacing-md); justify-content: center; flex-wrap: wrap;">
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
    galleryDots.forEach(dot => {
        dot.classList.remove('active');
    });
    
    // Show selected slide
    gallerySlides[index].classList.add('active');
    galleryDots[index].classList.add('active');
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
});

// Pause auto-slide on hover
const galleryCarousel = document.querySelector('.gallery-carousel');
galleryCarousel.addEventListener('mouseenter', () => {
    clearInterval(autoSlideInterval);
});

galleryCarousel.addEventListener('mouseleave', () => {
    startAutoSlide();
});

// Touch swipe for mobile
let touchStartX = 0;
let touchEndX = 0;

galleryCarousel.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

galleryCarousel.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    
    if (touchStartX - touchEndX > swipeThreshold) {
        // Swipe left - next slide
        clearInterval(autoSlideInterval);
        nextSlide();
        startAutoSlide();
    } else if (touchEndX - touchStartX > swipeThreshold) {
        // Swipe right - previous slide
        clearInterval(autoSlideInterval);
        const prevIndex = (currentSlide - 1 + gallerySlides.length) % gallerySlides.length;
        showSlide(prevIndex);
        startAutoSlide();
    }
}

// Start the carousel
startAutoSlide();
</script>

</body>
</html>