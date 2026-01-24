<?php
/**
 * Admissions Page View Template - Professional Layout
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
$page_title = $page_title ?? 'Admissions 2025/2026 | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Apply for ND/HND Nursing Programme 2025/2026 academic session. Post UTME screening exercise details and application guide.';
$applicationPortal = 'https://consap.fcthhss.abj.gov.ng';
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
}
main.admissions-page { 
    margin-top: 0 !important; 
    padding-top: 0 !important; 
}
.admissions-hero { 
    margin-top: 0 !important; 
    padding-top: 0 !important; 
}

/* ==========================================================================
   GLOBAL VARIABLES - Professional Color Scheme
   ========================================================================== */
:root {
    /* Professional Color Palette */
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
    
    /* Typography */
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
   HERO SECTION - Clean Background Image
   ========================================================================== */
.admissions-hero {
    position: relative;
    height: 85vh;
    max-height: 700px;
    min-height: 550px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-primary); /* Fallback color */
}

.admissions-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-image: url('<?php echo $baseUrl; ?>/assets/images/admissions/admissions-hero.jpg');
    background-attachment: fixed; /* Parallax effect */
}

.admissions-hero-content {
    position: relative;
    z-index: 3;
    color: var(--color-white);
    text-align: center;
    padding: var(--spacing-xl);
    max-width: 800px;
    width: 100%;
    background: rgba(0, 0, 0, 0.6); /* Semi-transparent background for readability */
    border-radius: var(--radius-lg);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin: 0 var(--spacing-md);
}

.admissions-hero-badge { 
    display: inline-block;
    background: var(--color-accent); 
    color: var(--color-gray-900); 
    padding: 0.75rem 2rem; 
    border-radius: var(--radius-full); 
    font-size: 0.9rem; 
    font-weight: 700; 
    margin-bottom: var(--spacing-lg);
    letter-spacing: 1px;
    text-transform: uppercase;
    font-family: var(--font-heading);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.admissions-hero-title { 
    font-family: var(--font-heading); 
    font-size: clamp(2rem, 5vw, 3.5rem); 
    font-weight: 800; 
    color: var(--color-white); 
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
    line-height: 1.1;
    margin-bottom: var(--spacing-md);
    letter-spacing: -0.5px;
}

.admissions-hero-subtitle { 
    font-size: clamp(1.1rem, 2.5vw, 1.5rem); 
    color: rgba(255,255,255,0.95);
    line-height: 1.6;
    max-width: 700px;
    margin: 0 auto;
    font-weight: 400;
}

/* ==========================================================================
   MAIN CONTENT LAYOUT
   ========================================================================== */
.section { 
    padding: var(--spacing-xxl) 0; 
}

.section-alt { 
    background: var(--color-gray-50); 
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
    font-size: clamp(1.8rem, 3vw, 2.4rem); 
    font-weight: 700; 
    color: var(--color-primary); 
    position: relative; 
    display: inline-block;
    margin-bottom: var(--spacing-md);
}

.section-title::after { 
    content: ''; 
    position: absolute; 
    bottom: -10px; 
    left: 50%; 
    transform: translateX(-50%); 
    width: 80px; 
    height: 4px; 
    background: var(--color-accent); 
    border-radius: 2px; 
}

.section-subtitle { 
    font-size: 1.2rem; 
    color: var(--color-gray-600); 
    line-height: 1.6; 
    font-weight: 400;
    max-width: 700px;
    margin: 0 auto;
    margin-top: var(--spacing-md);
}

/* ==========================================================================
   STATUS SECTION - Application Closed
   ========================================================================== */
.status-section {
    padding: var(--spacing-xl) 0;
    background: var(--color-white);
    border-bottom: 1px solid var(--color-gray-100);
}

.status-banner {
    background: linear-gradient(135deg, var(--color-gray-50), var(--color-white));
    border-left: 5px solid #dc3545;
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    margin: 0 auto;
    text-align: center;
    box-shadow: var(--shadow-soft);
    max-width: 800px;
}

.status-banner h3 {
    color: #dc3545;
    margin-bottom: var(--spacing-md);
    font-size: 1.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-sm);
}

.status-banner p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-sm);
    font-size: 1.1rem;
}

.status-banner p:last-child {
    margin-bottom: 0;
}

/* ==========================================================================
   NOTICE SECTION
   ========================================================================== */
.notice-section {
    background: var(--color-primary-very-light);
    border-top: 1px solid var(--color-gray-100);
    border-bottom: 1px solid var(--color-gray-100);
}

.alert-important {
    background: var(--color-white);
    border-left: 4px solid var(--color-accent);
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    margin: 0 auto;
    max-width: 900px;
    box-shadow: var(--shadow-soft);
}

.alert-important h3 {
    color: var(--color-primary); 
    margin-bottom: var(--spacing-md);
    font-size: 1.4rem;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.alert-important p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-sm);
    font-size: 1.1rem;
}

.alert-important p:last-child {
    margin-bottom: 0;
}

/* ==========================================================================
   DETAILS GRID
   ========================================================================== */
.details-grid { 
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
    gap: var(--spacing-lg); 
    margin-top: var(--spacing-xl);
}

.detail-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-soft);
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    overflow: hidden;
    height: 100%;
    padding: var(--spacing-xl);
    text-align: center;
}

.detail-card:hover { 
    transform: translateY(-8px); 
    box-shadow: var(--shadow-elevated); 
    border-color: var(--color-primary-light);
}

.detail-icon {
    font-size: 2.5rem;
    color: var(--color-primary);
    margin-bottom: var(--spacing-md);
}

.detail-card h3 {
    font-family: var(--font-heading); 
    font-size: 1.4rem; 
    font-weight: 600; 
    color: var(--color-primary); 
    margin-bottom: var(--spacing-md); 
    line-height: 1.3;
}

.detail-card p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-sm);
}

.detail-card p strong {
    color: var(--color-gray-900);
}

/* ==========================================================================
   REQUIREMENTS SECTION
   ========================================================================== */
.requirements-container {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-soft);
    padding: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.requirement-list { 
    list-style: none; 
    padding: 0; 
    margin: 0; 
}

.requirement-list li { 
    padding: 0.8rem 0; 
    position: relative; 
    padding-left: 2.2rem; 
    color: var(--color-gray-800);
    line-height: 1.6;
    font-size: 1.1rem;
    border-bottom: 1px solid var(--color-gray-100);
}

.requirement-list li:last-child {
    border-bottom: none;
}

.requirement-list li::before { 
    content: '✓'; 
    position: absolute; 
    left: 0; 
    color: var(--color-accent); 
    font-weight: bold; 
    font-size: 1.3rem; 
}

/* ==========================================================================
   PROCESS SECTION - REDESIGNED
   ========================================================================== */
.process-section {
    background: var(--color-white);
}

.process-container {
    margin-top: var(--spacing-xl);
}

.process-timeline {
    position: relative;
    max-width: 1000px;
    margin: 0 auto;
}

.process-timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 100%;
    background: var(--color-primary-light);
    border-radius: 2px;
}

.process-step {
    position: relative;
    margin-bottom: var(--spacing-xxl);
    display: flex;
    align-items: center;
    width: 100%;
}

.process-step:nth-child(odd) {
    flex-direction: row-reverse;
}

.process-step-content {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    box-shadow: var(--shadow-soft);
    border: 1px solid var(--color-gray-100);
    width: 45%;
    position: relative;
    transition: var(--transition-smooth);
}

.process-step-content:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-elevated);
    border-color: var(--color-primary-light);
}

.process-step:nth-child(odd) .process-step-content {
    margin-right: auto;
    margin-left: 0;
}

.process-step:nth-child(even) .process-step-content {
    margin-left: auto;
    margin-right: 0;
}

.process-step-icon {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 60px;
    background: var(--color-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-white);
    font-size: 1.5rem;
    z-index: 10;
    border: 4px solid var(--color-white);
    box-shadow: var(--shadow-soft);
}

.process-step-number {
    display: block;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
    font-family: var(--font-heading);
}

.process-step-title {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--spacing-md);
    line-height: 1.3;
}

.process-step-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.process-step-list li {
    padding: 0.6rem 0;
    position: relative;
    padding-left: 1.8rem;
    color: var(--color-gray-800);
    line-height: 1.5;
    font-size: 1.05rem;
    border-bottom: 1px solid var(--color-gray-100);
}

.process-step-list li:last-child {
    border-bottom: none;
}

.process-step-list li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--color-accent);
    font-weight: bold;
    font-size: 1.5rem;
}

.process-step-list li a {
    color: var(--color-primary);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
}

.process-step-list li a:hover {
    color: var(--color-primary-dark);
    text-decoration: underline;
}

/* Process Flowchart */
.process-flowchart {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-xl);
    overflow: hidden;
    box-shadow: var(--shadow-soft);
    border: 1px solid var(--color-gray-100);
}

.process-flowchart-image {
    width: 100%;
    height: auto;
    display: block;
    object-fit: contain;
    max-height: 500px;
}

.process-flowchart-caption {
    padding: var(--spacing-md);
    background: var(--color-gray-50);
    text-align: center;
    color: var(--color-gray-800);
    font-size: 0.9rem;
    border-top: 1px solid var(--color-gray-100);
}

.process-flowchart-placeholder {
    background: var(--color-gray-50);
    height: 400px;
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-gray-600);
    font-size: 1.1rem;
    text-align: center;
    border: 2px dashed var(--color-gray-300);
    padding: var(--spacing-lg);
}

/* ==========================================================================
   CONTACT SECTION
   ========================================================================== */
.contact-grid { 
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
    gap: var(--spacing-lg); 
    margin-top: var(--spacing-xl);
}

.contact-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-soft);
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    overflow: hidden;
    height: 100%;
    padding: var(--spacing-xl);
    text-align: center;
}

.contact-card:hover { 
    transform: translateY(-8px); 
    box-shadow: var(--shadow-elevated); 
    border-color: var(--color-primary-light);
}

.contact-icon {
    font-size: 2.5rem;
    color: var(--color-primary);
    margin-bottom: var(--spacing-md);
}

.contact-card h3 {
    font-family: var(--font-heading); 
    font-size: 1.4rem; 
    font-weight: 600; 
    color: var(--color-primary); 
    margin-bottom: var(--spacing-md); 
    line-height: 1.3;
}

.contact-card p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-sm);
}

/* ==========================================================================
   BUTTONS
   ========================================================================== */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 1rem 2.5rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid var(--color-accent);
    font-family: var(--font-heading);
    font-size: 1.1rem;
    letter-spacing: 0.3px;
    min-height: 56px;
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
    padding: 1rem 2.5rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid var(--color-primary);
    font-family: var(--font-heading);
    font-size: 1.1rem;
    letter-spacing: 0.3px;
    min-height: 56px;
}

.btn-secondary:hover { 
    background: var(--color-primary-dark); 
    color: var(--color-white);
    transform: translateY(-3px); 
    box-shadow: var(--shadow-soft); 
    border-color: var(--color-primary-dark);
}

.btn-disabled {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: var(--color-gray-300);
    color: var(--color-gray-600);
    padding: 1rem 2.5rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    border: 2px solid var(--color-gray-300);
    font-family: var(--font-heading);
    font-size: 1.1rem;
    letter-spacing: 0.3px;
    min-height: 56px;
    cursor: not-allowed;
    opacity: 0.8;
}

/* ==========================================================================
   CTA SECTION
   ========================================================================== */
.cta-section { 
    background: var(--color-white);
    text-align: center; 
    padding: var(--spacing-xxl) 0; 
    border-top: 1px solid var(--color-gray-100);
}

.cta-section .section-title {
    margin-bottom: var(--spacing-md);
}

/* ==========================================================================
   UTILITY CLASSES
   ========================================================================== */
.text-center { text-align: center; }
.text-primary { color: var(--color-primary); }
.text-accent { color: var(--color-accent); }
.font-bold { font-weight: 600; }
.text-muted { color: var(--color-gray-600); }

/* ==========================================================================
   RESPONSIVE DESIGN
   ========================================================================== */
@media (max-width: 992px) {
    .process-timeline::before {
        left: 30px;
    }
    
    .process-step {
        flex-direction: row !important;
        align-items: flex-start;
    }
    
    .process-step-content {
        width: calc(100% - 100px);
        margin-left: 100px !important;
        margin-right: 0 !important;
    }
    
    .process-step-icon {
        left: 30px;
        transform: translateX(0);
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
    
    .admissions-hero {
        height: 70vh;
        min-height: 450px;
        background-attachment: scroll; /* Remove parallax on mobile */
    }
    
    .admissions-hero-content {
        padding: var(--spacing-lg);
        margin: 0 var(--spacing-sm);
    }
    
    .admissions-hero-badge {
        padding: 0.6rem 1.5rem;
        font-size: 0.8rem;
    }
    
    .admissions-hero-title {
        font-size: clamp(1.8rem, 4vw, 2.5rem);
    }
    
    .admissions-hero-subtitle {
        font-size: clamp(1rem, 2vw, 1.2rem);
    }
    
    .section {
        padding: var(--spacing-xl) 0;
    }
    
    .details-grid,
    .contact-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .detail-card,
    .contact-card {
        padding: var(--spacing-lg);
    }
    
    .status-banner,
    .alert-important {
        padding: var(--spacing-lg);
        margin: 0 var(--spacing-md);
    }
    
    .requirements-container {
        padding: var(--spacing-lg);
    }
    
    .requirement-list li {
        padding-left: 1.8rem;
        font-size: 1rem;
    }
    
    .process-step-content {
        padding: var(--spacing-lg);
        width: calc(100% - 80px);
        margin-left: 80px !important;
    }
    
    .process-step-icon {
        width: 50px;
        height: 50px;
        font-size: 1.3rem;
        left: 25px;
    }
    
    .process-step-number {
        font-size: 1.5rem;
    }
    
    .process-step-title {
        font-size: 1.2rem;
    }
    
    .btn-primary,
    .btn-secondary,
    .btn-disabled {
        padding: 0.8rem 1.8rem;
        font-size: 1rem;
        min-height: 50px;
    }
    
    .process-flowchart-placeholder {
        height: 300px;
    }
}

@media (max-width: 480px) {
    .admissions-hero {
        height: 60vh;
        min-height: 400px;
    }
    
    .admissions-hero-badge {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
    }
    
    .admissions-hero-title {
        font-size: 1.6rem;
    }
    
    .admissions-hero-subtitle {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.6rem;
    }
    
    .detail-card h3,
    .contact-card h3 {
        font-size: 1.2rem;
    }
    
    .process-step-content {
        width: calc(100% - 60px);
        margin-left: 60px !important;
        padding: var(--spacing-md);
    }
    
    .process-step-icon {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
        left: 20px;
    }
    
    .process-step-number {
        font-size: 1.3rem;
    }
    
    .process-step-title {
        font-size: 1.1rem;
    }
    
    .process-step-list li {
        font-size: 1rem;
        padding-left: 1.5rem;
    }
    
    .process-flowchart-placeholder {
        height: 250px;
        font-size: 1rem;
    }
}

/* Print Styles */
@media print {
    .admissions-hero {
        height: auto;
        min-height: auto;
        background: var(--color-white);
        color: var(--color-black);
    }
    
    .admissions-hero-bg {
        display: none;
    }
    
    .admissions-hero-content {
        color: var(--color-black);
        background: transparent;
        backdrop-filter: none;
        border: none;
    }
    
    .btn-primary,
    .btn-secondary,
    .btn-disabled {
        display: none;
    }
    
    .detail-card,
    .contact-card,
    .process-step-content {
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
    }
    
    .alert-important,
    .status-banner {
        border: 1px solid var(--color-gray-300);
    }
    
    .process-timeline::before {
        display: none;
    }
    
    .process-step-icon {
        border: 1px solid var(--color-gray-300);
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        transition: none !important;
        animation: none !important;
    }
    
    .detail-card:hover,
    .contact-card:hover,
    .process-step-content:hover,
    .btn-primary:hover,
    .btn-secondary:hover {
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

<main class="admissions-page">
    <!-- Hero Section with Clean Background Image -->
    <section class="admissions-hero">
        <div class="admissions-hero-bg"></div>
        <div class="container">
            <div class="admissions-hero-content">
                <span class="admissions-hero-badge">2025/2026 Admissions</span>
                <h1 class="admissions-hero-title">ND/HND Nursing Programme</h1>
                <p class="admissions-hero-subtitle">
                    Application for 2025/2026 Session is Closed • Sales of Forms: 15th – 28th September 2025
                </p>
            </div>
        </div>
    </section>

    <!-- Status Section -->
    <section class="status-section">
        <div class="container">
            <div class="status-banner">
                <h3><i class="fas fa-times-circle"></i> Application Closed</h3>
                <p><strong>The application portal for the 2025/2026 academic session is now closed.</strong></p>
                <p>The sales of forms period ended on 28th September 2025. No further applications are being accepted for this session.</p>
                <p class="text-muted" style="margin-top: var(--spacing-sm);">Please check back for updates on the 2026/2027 admissions cycle.</p>
            </div>
        </div>
    </section>

    <!-- Notice Section -->
    <section class="section notice-section">
        <div class="container">
            <div class="alert-important">
                <h3><i class="fas fa-exclamation-triangle"></i> Important Notice</h3>
                <p><strong>No extension</strong> of the application deadline. The College has <strong>NO AGENTS</strong>. Beware of fraudulent websites and deal only through official channels.</p>
                <p style="margin-top: var(--spacing-sm);">All applications must be submitted through the official portal only.</p>
            </div>
        </div>
    </section>

    <!-- Key Details Section -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Admission Details</h2>
                <p class="section-subtitle">Key information about the 2025/2026 admissions process</p>
            </div>
            
            <div class="details-grid">
                <div class="detail-card">
                    <div class="detail-icon">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <h3>Application Period</h3>
                    <p><strong>Sales of Forms:</strong> Monday, 15th September – Wednesday, 28th September 2025</p>
                    <p><strong>Application Fee:</strong> ₦2,200 (Non-refundable)</p>
                    <p class="text-muted" style="margin-top: var(--spacing-sm);"><em>Application period has ended</em></p>
                </div>
                
                <div class="detail-card">
                    <div class="detail-icon">
                        <i class="far fa-clock"></i>
                    </div>
                    <h3>Examination Schedule</h3>
                    <p><strong>Post UTME Screening:</strong> 6th, 7th, and 8th October 2025</p>
                    <p><strong>Venue:</strong> FCT College of Nursing Sciences, Gwagwalada (within UATH)</p>
                    <p><strong>Reporting Time:</strong> 8:00 AM daily</p>
                </div>
                
                <div class="detail-card">
                    <div class="detail-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Program Information</h3>
                    <p><strong>Program:</strong> ND/HND Nursing (Non-terminal)</p>
                    <p><strong>Duration:</strong> 4 Years Total (2 Years ND + 2 Years HND)</p>
                    <p><strong>Accreditation:</strong> NBTE & NMCN Approved</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Requirements Section -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Eligibility Requirements</h2>
                <p class="section-subtitle">Minimum requirements for admission consideration</p>
            </div>
            
            <div class="requirements-container">
                <ul class="requirement-list">
                    <li>Minimum UTME score of <strong>170</strong> in the 2025 JAMB examination</li>
                    <li>Selected FCT College of Nursing Sciences, Gwagwalada as <strong>First Choice</strong> institution</li>
                    <li>At least <strong>5 O'Level Credits</strong> (English Language, Mathematics, Biology, Chemistry, Physics) in not more than <strong>2 sittings</strong> (WAEC/NECO/NABTEB)</li>
                    <li>Must be <strong>16 years</strong> of age or above at the time of application</li>
                    <li>Valid JAMB registration number and personal details</li>
                    <li>Complete and accurate personal information on the application form</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Application Process Section -->
    <section class="section process-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Application Process</h2>
                <p class="section-subtitle">Step-by-step guide to the application procedure (2025/2026 Session)</p>
            </div>
            
            <!-- Process Flowchart -->
            <?php
            $flowchartPath = $baseUrl . '/assets/images/admissions/process-flowchart.jpg';
            $flowchartExists = file_exists($_SERVER['DOCUMENT_ROOT'] . parse_url($flowchartPath, PHP_URL_PATH));
            ?>
            
            <?php if ($flowchartExists): ?>
            <div class="process-flowchart">
                <img 
                    src="<?php echo $flowchartPath; ?>" 
                    alt="Application Process Flowchart for FCT College of Nursing Sciences Admissions 2025/2026"
                    class="process-flowchart-image"
                >
                <div class="process-flowchart-caption">
                    Application Process Flowchart for 2025/2026 Admissions (Process has ended)
                </div>
            </div>
            <?php else: ?>
            <div class="process-flowchart-placeholder">
                <div>
                    <i class="fas fa-diagram-project" style="font-size: 3rem; margin-bottom: var(--spacing-md); color: var(--color-gray-400);"></i>
                    <p>Application Process Flowchart (2025/2026)</p>
                    <p style="font-size: 0.9rem; margin-top: var(--spacing-xs);">
                        Add your flowchart image at:<br>
                        <code>/assets/images/admissions/process-flowchart.jpg</code>
                    </p>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Process Steps Timeline -->
            <div class="process-container">
                <div class="process-timeline">
                    <!-- Step 1 -->
                    <div class="process-step">
                        <div class="process-step-content">
                            <span class="process-step-number">01</span>
                            <h3 class="process-step-title">Account Creation & Registration</h3>
                            <ul class="process-step-list">
                                <li>Visit the official portal: <a href="<?php echo $applicationPortal; ?>" target="_blank"><?php echo $applicationPortal; ?></a></li>
                                <li>Read and agree to the terms and conditions</li>
                                <li>Enter your 2025 JAMB registration number for validation</li>
                                <li>Provide valid email address, phone number, and create a secure password</li>
                            </ul>
                        </div>
                        <div class="process-step-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="process-step">
                        <div class="process-step-content">
                            <span class="process-step-number">02</span>
                            <h3 class="process-step-title">Complete Application Form</h3>
                            <ul class="process-step-list">
                                <li>Log in with your registered credentials</li>
                                <li>Navigate to "Apply Now" or "My Application" section</li>
                                <li>Fill all required personal and academic information accurately</li>
                                <li>Upload required documents (passport photograph, O'Level results)</li>
                            </ul>
                        </div>
                        <div class="process-step-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="process-step">
                        <div class="process-step-content">
                            <span class="process-step-number">03</span>
                            <h3 class="process-step-title">Payment & Verification</h3>
                            <ul class="process-step-list">
                                <li>Click "Proceed to Payment" to generate RRR code</li>
                                <li>Pay ₦2,200 application fee online or at any commercial bank</li>
                                <li>Return to portal and click "Verify Payment" to confirm payment</li>
                                <li>Wait for payment confirmation before proceeding</li>
                            </ul>
                        </div>
                        <div class="process-step-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="process-step">
                        <div class="process-step-content">
                            <span class="process-step-number">04</span>
                            <h3 class="process-step-title">Examination Slip & Preparation</h3>
                            <ul class="process-step-list">
                                <li>Download and print your examination slip from the portal</li>
                                <li>Bring printed slip, writing materials, and valid ID to exam venue</li>
                                <li>Arrive at least 30 minutes before scheduled examination time</li>
                            </ul>
                        </div>
                        <div class="process-step-icon">
                            <i class="fas fa-print"></i>
                        </div>
                    </div>
                </div>
                
                <p class="text-muted text-center" style="margin-top: var(--spacing-xl); font-style: italic;">
                    <i class="fas fa-info-circle"></i> This application process was for the 2025/2026 session. The portal is now closed.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact & Support Section -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Support & Enquiries</h2>
                <p class="section-subtitle">Get assistance with your application or inquiries</p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3>Phone Support</h3>
                    <p><strong>Call:</strong> 07039837749 / 08036625119</p>
                    <p><strong>WhatsApp Only:</strong> 08082775076</p>
                    <p><strong>Hours:</strong> Mon-Fri, 9:00 AM - 5:00 PM</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email & Online</h3>
                    <p><strong>Email:</strong> support.consap@fcthhss.abj.gov.ng</p>
                    <p><strong>Live Chat:</strong> Available on the portal</p>
                    <p><strong>Telegram:</strong> <a href="https://t.me/+SWH5opeTcTXs34Ko" target="_blank" class="text-primary">Official Channel</a></p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Campus Visit</h3>
                    <p><strong>Address:</strong> FCT College of Nursing Sciences, Gwagwalada, Abuja</p>
                    <p><strong>Office Hours:</strong> Monday – Friday, 8:00 AM – 5:00 PM</p>
                    <p><strong>Note:</strong> Appointments recommended</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">2025/2026 Admissions Status</h2>
                <p class="section-subtitle">The application period for the current academic session has ended</p>
            </div>
            
            <div style="display: flex; flex-direction: column; align-items: center; gap: var(--spacing-lg);">
                <div style="display: flex; gap: var(--spacing-md); justify-content: center; flex-wrap: wrap;">
                    <span class="btn-disabled">
                        <i class="fas fa-lock"></i> Application Portal (Closed)
                    </span>
                    <a href="<?php echo $baseUrl; ?>/programs" class="btn-secondary">
                        <i class="fas fa-book-open"></i> View Programs
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" class="btn-primary">
                        <i class="fas fa-phone-alt"></i> Contact Admissions
                    </a>
                </div>
                
                <div class="text-muted" style="text-align: center; max-width: 600px; margin-top: var(--spacing-md);">
                    <p><i class="fas fa-calendar-alt"></i> <strong>Next Admissions Cycle:</strong> 2026/2027 academic session</p>
                    <p style="margin-top: var(--spacing-xs);">Check back regularly for updates on the next admissions cycle.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle hero background image loading
    const heroBg = document.querySelector('.admissions-hero-bg');
    const heroImage = new Image();
    
    heroImage.onload = function() {
        console.log('Hero background image loaded successfully');
    };
    
    heroImage.onerror = function() {
        console.warn('Hero background image failed to load');
        heroBg.style.backgroundImage = 'linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))';
    };
    
    heroImage.src = '<?php echo $baseUrl; ?>/assets/images/admissions/admissions-hero.jpg';
    
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
    
    // Animate process steps on scroll
    const processSteps = document.querySelectorAll('.process-step-content');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    processSteps.forEach(step => {
        step.style.opacity = '0';
        step.style.transform = 'translateY(20px)';
        step.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(step);
    });
});
</script>

</body>
</html>