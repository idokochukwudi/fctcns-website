<?php
/**
 * Student Life - Coming Soon Page
 * 
 * @package FCTCNS
 * @version 1.1
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$page_title = $page_title ?? 'Student Life | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Discover the vibrant student experience at FCT College of Nursing Sciences. Campus life, activities, and student support services.';

// Countdown to launch date (30 days from now)
$launchDate = date('Y-m-d H:i:s', strtotime('+30 days'));
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
    
    <!-- Animate.css for subtle animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
/* ==========================================================================
   CRITICAL FIXES - Ensure all content is visible
   ========================================================================== */
* { 
    box-sizing: border-box; 
    margin: 0;
    padding: 0;
}

body { 
    margin: 0 !important; 
    padding: 0 !important; 
    overflow-x: hidden;
    min-height: 100vh;
}

main {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* ==========================================================================
   GLOBAL VARIABLES - Consistent Color Scheme with Programs Page
   ========================================================================== */
:root {
    /* Professional Color Palette - Matching Programs Page */
    --color-primary: #5D4A8A;           /* Deep sophisticated purple */
    --color-primary-dark: #4A3A6F;
    --color-primary-light: #6F5B9E;
    --color-primary-very-light: #F8F6FC;
    --color-primary-transparent: rgba(93, 74, 138, 0.08);
    
    --color-accent: #D4A574;            /* Muted gold accent */
    --color-accent-dark: #BF8F5E;
    --color-accent-light: #E6C9A5;
    
    /* Student Life Specific Colors */
    --color-student-blue: #4A8CA8;
    --color-student-green: #5DA57A;
    --color-student-yellow: #F2C94C;
    
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
    
    /* Typography - Consistent with Programs Page */
    --font-heading: 'Montserrat', sans-serif;
    --font-body: 'Open Sans', sans-serif;
    
    /* Spacing */
    --spacing-xs: 0.5rem;
    --spacing-sm: 0.75rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-xxl: 2.5rem;
    
    /* Shadows */
    --shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.05);
    --shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.08);
    --shadow-elevated: 0 8px 24px rgba(0, 0, 0, 0.12);
    --shadow-glow: 0 0 30px rgba(93, 74, 138, 0.15);
    
    /* Border Radius */
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 14px;
    --radius-full: 999px;
    
    /* Transitions */
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slower: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
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
   HERO SECTION - FIXED: Ensure all content is visible
   ========================================================================== */
.student-life-hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    padding: var(--spacing-xl) var(--spacing-md);
    overflow: visible;
}

/* Animated background particles */
.particles-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 15s infinite linear;
}

@keyframes float {
    0% {
        transform: translateY(0) rotate(0deg);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(-1000px) rotate(720deg);
        opacity: 0;
    }
}

.hero-content {
    position: relative;
    z-index: 3;
    color: var(--color-white);
    text-align: center;
    padding: var(--spacing-xl);
    max-width: 900px;
    width: 100%;
    background: rgba(0, 0, 0, 0.2);
    border-radius: var(--radius-lg);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin: 0 auto;
    box-shadow: var(--shadow-glow);
    overflow: visible;
}

.coming-soon-badge { 
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--color-accent); 
    color: var(--color-gray-900); 
    padding: 0.75rem 1.5rem; 
    border-radius: var(--radius-full); 
    font-size: 0.85rem; 
    font-weight: 700; 
    margin-bottom: var(--spacing-lg); 
    letter-spacing: 1px;
    text-transform: uppercase;
    font-family: var(--font-heading);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
}

.hero-title { 
    font-family: var(--font-heading); 
    font-size: clamp(2rem, 4.5vw, 2.8rem); /* Reduced max size */
    font-weight: 800; 
    color: var(--color-white); 
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
    line-height: 1.2; /* Increased for better readability */
    margin-bottom: var(--spacing-md); 
    letter-spacing: -0.5px;
}

.hero-subtitle { 
    font-size: clamp(1rem, 2.2vw, 1.2rem); /* Reduced sizes */
    color: rgba(255,255,255,0.95);
    line-height: 1.5; 
    max-width: 700px;
    margin: 0 auto;
    font-weight: 400;
    margin-bottom: var(--spacing-lg);
    padding: 0 var(--spacing-sm);
}

/* Launch Countdown */
.countdown-container {
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    margin: var(--spacing-lg) auto;
    max-width: 600px;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    overflow: visible;
}

.countdown-title {
    font-family: var(--font-heading);
    font-size: 1.1rem;
    color: var(--color-accent);
    margin-bottom: var(--spacing-md);
    font-weight: 600;
}

.countdown-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--spacing-sm);
}

.countdown-item {
    text-align: center;
    background: rgba(0, 0, 0, 0.2);
    border-radius: var(--radius-md);
    padding: var(--spacing-md);
    transition: var(--transition-smooth);
}

.countdown-item:hover {
    transform: translateY(-3px);
    background: rgba(0, 0, 0, 0.3);
}

.countdown-number {
    display: block;
    font-family: var(--font-heading);
    font-size: 1.8rem; /* Reduced */
    font-weight: 700;
    color: var(--color-white);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.countdown-label {
    display: block;
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
}

/* Hero Actions */
.hero-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
    margin-top: var(--spacing-xl);
    flex-wrap: wrap;
}

.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 0.85rem 1.75rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid var(--color-accent);
    font-family: var(--font-heading);
    font-size: 1rem;
    letter-spacing: 0.3px;
    min-height: 48px;
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
    gap: 0.75rem;
    background: transparent;
    color: var(--color-white);
    padding: 0.85rem 1.75rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid rgba(255, 255, 255, 0.3);
    font-family: var(--font-heading);
    font-size: 1rem;
    letter-spacing: 0.3px;
    min-height: 48px;
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
   PREVIEW SECTION
   ========================================================================== */
.preview-section {
    padding: var(--spacing-xxl) 0;
    background: linear-gradient(to bottom, var(--color-primary-very-light), var(--color-white));
    position: relative;
}

.preview-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, var(--color-accent-light), transparent);
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
    font-size: clamp(1.6rem, 3.5vw, 2.2rem); 
    font-weight: 700; 
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
    line-height: 1.5; 
    font-weight: 400;
    max-width: 700px;
    margin: 0 auto;
    margin-top: var(--spacing-md);
}

/* Preview Features Grid */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.feature-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    text-align: center;
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    box-shadow: var(--shadow-subtle);
    position: relative;
    overflow: hidden;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
}

.feature-card:hover { 
    transform: translateY(-5px); 
    box-shadow: var(--shadow-elevated); 
    border-color: var(--color-primary-light);
}

.feature-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto var(--spacing-lg);
    background: var(--color-primary-very-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: var(--color-primary);
    transition: var(--transition-smooth);
}

.feature-card:hover .feature-icon {
    background: var(--color-primary);
    color: var(--color-white);
    transform: scale(1.05) rotate(5deg);
}

.feature-card h3 {
    font-family: var(--font-heading);
    font-size: 1.3rem;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    font-weight: 600;
}

.feature-card p {
    color: var(--color-gray-600);
    line-height: 1.5;
    margin-bottom: var(--spacing-md);
    font-size: 0.95rem;
}

.feature-highlights {
    text-align: left;
    background: var(--color-primary-very-light);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    border-left: 4px solid var(--color-accent);
}

.feature-highlights h4 {
    font-family: var(--font-heading);
    font-size: 0.95rem;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.highlight-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.highlight-list li {
    padding: 0.3rem 0;
    position: relative;
    padding-left: 1.5rem;
    color: var(--color-gray-700);
    line-height: 1.4;
    font-size: 0.9rem;
}

.highlight-list li::before { 
    content: '→'; 
    position: absolute; 
    left: 0; 
    color: var(--color-accent); 
    font-weight: bold; 
}

/* ==========================================================================
   STUDENT SPOTLIGHT
   ========================================================================== */
.student-spotlight {
    padding: var(--spacing-xxl) 0;
    background: linear-gradient(135deg, var(--color-gray-50) 0%, var(--color-white) 100%);
    position: relative;
}

.spotlight-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    max-width: 800px;
    margin: 0 auto;
    box-shadow: var(--shadow-elevated);
    border: 1px solid var(--color-gray-100);
    position: relative;
    overflow: hidden;
}

.spotlight-card::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(45deg, transparent 50%, var(--color-primary-transparent) 50%);
    z-index: 1;
}

.spotlight-content {
    position: relative;
    z-index: 2;
}

.spotlight-quote {
    font-size: 1.2rem;
    color: var(--color-gray-800);
    line-height: 1.6;
    font-style: italic;
    margin-bottom: var(--spacing-lg);
    position: relative;
    padding-left: var(--spacing-lg);
}

.spotlight-quote::before {
    content: '❝';
    position: absolute;
    left: 0;
    top: -5px;
    font-size: 2.5rem;
    color: var(--color-accent);
    opacity: 0.3;
}

.spotlight-author {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--color-primary);
    color: var(--color-white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 600;
    flex-shrink: 0;
}

.author-info h4 {
    font-family: var(--font-heading);
    font-size: 1.1rem;
    color: var(--color-primary);
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.author-info p {
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

/* ==========================================================================
   NOTIFY SECTION
   ========================================================================== */
.notify-section {
    padding: var(--spacing-xxl) 0;
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    color: var(--color-white);
    text-align: center;
}

.notify-section .section-title {
    color: var(--color-white);
}

.notify-section .section-title::after {
    background: var(--color-accent);
}

.notify-section .section-subtitle {
    color: rgba(255, 255, 255, 0.9);
}

.notify-form {
    max-width: 500px;
    margin: var(--spacing-xl) auto;
}

.form-group {
    margin-bottom: var(--spacing-md);
}

.form-input {
    width: 100%;
    padding: 0.9rem 1.25rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-md);
    background: rgba(255, 255, 255, 0.1);
    color: var(--color-white);
    font-family: var(--font-body);
    font-size: 1rem;
    transition: var(--transition-smooth);
    backdrop-filter: blur(10px);
}

.form-input:focus {
    outline: none;
    border-color: var(--color-accent);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
}

.form-input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.form-checkbox {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-sm);
    margin: var(--spacing-md) 0;
}

.form-checkbox input[type="checkbox"] {
    margin-top: 0.25rem;
    accent-color: var(--color-accent);
}

.form-checkbox label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.5;
    text-align: left;
}

.notify-btn {
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
    width: 100%;
    cursor: pointer;
}

.notify-btn:hover { 
    background: var(--color-accent-dark); 
    color: var(--color-gray-900);
    transform: translateY(-3px); 
    box-shadow: 0 8px 25px rgba(212, 165, 116, 0.4); 
    border-color: var(--color-accent-dark);
}

/* ==========================================================================
   ANIMATION CLASSES
   ========================================================================== */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
}

.animate-on-scroll.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Loading Animation */
.loading-shimmer {
    background: linear-gradient(90deg, 
        var(--color-gray-100) 25%, 
        var(--color-gray-200) 50%, 
        var(--color-gray-100) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ==========================================================================
   RESPONSIVE DESIGN
   ========================================================================== */
/* Desktop (992px and up) */
@media (min-width: 992px) {
    .student-life-hero {
        padding: var(--spacing-xxl) var(--spacing-md);
    }
    
    .hero-content {
        padding: var(--spacing-xxl);
    }
    
    .features-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Tablet (768px to 991px) */
@media (max-width: 991px) and (min-width: 768px) {
    .student-life-hero {
        padding: var(--spacing-xl) var(--spacing-md);
    }
    
    .hero-content {
        padding: var(--spacing-xl);
    }
    
    .features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .hero-title {
        font-size: 2.2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
}

/* Mobile (max-width: 767px) */
@media (max-width: 767px) {
    .student-life-hero {
        padding: var(--spacing-lg) var(--spacing-sm);
        min-height: auto;
    }
    
    .hero-content {
        padding: var(--spacing-lg);
        margin: var(--spacing-sm);
    }
    
    .hero-title {
        font-size: 1.8rem;
        line-height: 1.3;
    }
    
    .hero-subtitle {
        font-size: 1rem;
        margin-bottom: var(--spacing-md);
    }
    
    .countdown-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .hero-actions {
        flex-direction: column;
        gap: var(--spacing-sm);
        margin-top: var(--spacing-lg);
    }
    
    .btn-hero-primary,
    .btn-hero-secondary {
        width: 100%;
        justify-content: center;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .feature-card {
        padding: var(--spacing-lg);
    }
    
    .spotlight-card {
        padding: var(--spacing-lg);
        margin: 0 var(--spacing-sm);
    }
    
    .spotlight-quote {
        font-size: 1.1rem;
        padding-left: var(--spacing-md);
        line-height: 1.5;
    }
    
    .spotlight-quote::before {
        font-size: 2rem;
        top: -3px;
    }
    
    .notify-form {
        margin: var(--spacing-lg) auto;
    }
}

/* Small Mobile (max-width: 480px) */
@media (max-width: 480px) {
    .hero-title {
        font-size: 1.6rem;
    }
    
    .hero-subtitle {
        font-size: 0.95rem;
    }
    
    .countdown-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-xs);
    }
    
    .countdown-item {
        padding: var(--spacing-sm);
    }
    
    .countdown-number {
        font-size: 1.5rem;
    }
    
    .countdown-label {
        font-size: 0.7rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .feature-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .feature-card h3 {
        font-size: 1.2rem;
    }
    
    .feature-card p {
        font-size: 0.9rem;
    }
    
    .spotlight-quote {
        padding-left: var(--spacing-sm);
        font-size: 1rem;
    }
    
    .author-avatar {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    
    .author-info h4 {
        font-size: 1rem;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        transition: none !important;
        animation: none !important;
    }
    
    .particle,
    .coming-soon-badge,
    .feature-card:hover,
    .btn-hero-primary:hover,
    .btn-hero-secondary:hover,
    .notify-btn:hover {
        animation: none !important;
        transform: none !important;
    }
}

:focus-visible {
    outline: 2px solid var(--color-accent);
    outline-offset: 2px;
    border-radius: var(--radius-sm);
}

/* Print Styles */
@media print {
    .student-life-hero {
        background: var(--color-white) !important;
        color: var(--color-black) !important;
        min-height: auto;
        padding: var(--spacing-md) 0 !important;
    }
    
    .particles-container {
        display: none;
    }
    
    .hero-content {
        color: var(--color-black);
        background: transparent;
        backdrop-filter: none;
        border: none;
        box-shadow: none;
        margin-top: 0;
        padding: 0 !important;
    }
    
    .btn-hero-primary,
    .btn-hero-secondary,
    .notify-btn {
        display: none;
    }
    
    .feature-card {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
    }
}
</style>
</head>
<body>

<!-- Hero Section - FIXED: All content visible -->
<section class="student-life-hero">
    <div class="particles-container" id="particles"></div>
    
    <div class="container">
        <div class="hero-content">
            <div class="coming-soon-badge animate__animated animate__pulse animate__infinite">
                <i class="fas fa-clock"></i>
                Coming Soon
            </div>
            
            <h1 class="hero-title">Student Life Experience</h1>
            <p class="hero-subtitle">
                Get ready to explore campus life, student activities, and everything that makes 
                FCT College of Nursing Sciences more than just an institution.
            </p>
            
            <!-- Launch Countdown -->
            <div class="countdown-container">
                <h3 class="countdown-title">Launching In</h3>
                <div class="countdown-grid">
                    <div class="countdown-item">
                        <span class="countdown-number" id="days">30</span>
                        <span class="countdown-label">Days</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="hours">00</span>
                        <span class="countdown-label">Hours</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="minutes">00</span>
                        <span class="countdown-label">Minutes</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="seconds">00</span>
                        <span class="countdown-label">Seconds</span>
                    </div>
                </div>
            </div>
            
            <!-- Hero Actions -->
            <div class="hero-actions">
                <a href="#notify" class="btn-hero-primary">
                    <i class="fas fa-bell"></i>
                    Notify Me on Launch
                </a>
                <a href="<?php echo $baseUrl; ?>" class="btn-hero-secondary">
                    <i class="fas fa-home"></i>
                    Return to Homepage
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Preview Section -->
<section class="preview-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">What to Expect</h2>
            <p class="section-subtitle">
                Discover the vibrant campus experience that awaits our nursing students.
            </p>
        </div>
        
        <div class="features-grid">
            <!-- Campus Facilities -->
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-university"></i>
                </div>
                <h3>Campus Facilities</h3>
                <p>Explore our modern campus equipped with state-of-the-art learning and recreational facilities.</p>
                <div class="feature-highlights">
                    <h4><i class="fas fa-star"></i> Featured Areas</h4>
                    <ul class="highlight-list">
                        <li>Advanced Simulation Labs</li>
                        <li>Digital Library & Resource Center</li>
                        <li>Student Recreation Center</li>
                        <li>Modern Lecture Halls</li>
                        <li>Health & Wellness Center</li>
                    </ul>
                </div>
            </div>
            
            <!-- Student Activities -->
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Student Activities</h3>
                <p>Engage in diverse extracurricular activities, clubs, and organizations.</p>
                <div class="feature-highlights">
                    <h4><i class="fas fa-star"></i> Featured Activities</h4>
                    <ul class="highlight-list">
                        <li>Student Nursing Association</li>
                        <li>Community Outreach Programs</li>
                        <li>Sports & Recreation</li>
                        <li>Cultural Events</li>
                        <li>Leadership Development</li>
                    </ul>
                </div>
            </div>
            
            <!-- Support Services -->
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <h3>Support Services</h3>
                <p>Comprehensive support systems to help you succeed academically and personally.</p>
                <div class="feature-highlights">
                    <h4><i class="fas fa-star"></i> Support Areas</h4>
                    <ul class="highlight-list">
                        <li>Academic Advising</li>
                        <li>Career Counseling</li>
                        <li>Mental Health Support</li>
                        <li>Tutoring Services</li>
                        <li>Accommodation Assistance</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Student Spotlight -->
<section class="student-spotlight">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Student Voices</h2>
            <p class="section-subtitle">
                Hear from our students about their campus experience.
            </p>
        </div>
        
        <div class="spotlight-card animate-on-scroll">
            <div class="spotlight-content">
                <blockquote class="spotlight-quote">
                    "The student life at FCT College goes beyond academics. It's a community that supports your growth as both a healthcare professional and as an individual. The campus facilities and extracurricular activities create a well-rounded experience that prepares you for real-world challenges."
                </blockquote>
                
                <div class="spotlight-author">
                    <div class="author-avatar">JC</div>
                    <div class="author-info">
                        <h4>Jessica Chukwu</h4>
                        <p>ND Nursing Student, Class of 2024</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Notify Section -->
<section class="notify-section" id="notify">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Be the First to Know</h2>
            <p class="section-subtitle">
                Get notified when we launch the complete Student Life section.
            </p>
        </div>
        
        <form class="notify-form" id="notifyForm">
            <div class="form-group">
                <input type="email" 
                       class="form-input" 
                       placeholder="Enter your email address" 
                       required>
            </div>
            
            <div class="form-checkbox">
                <input type="checkbox" id="updates" checked required>
                <label for="updates">
                    I would like to receive updates about student activities, events, and campus news.
                </label>
            </div>
            
            <button type="submit" class="notify-btn">
                <i class="fas fa-paper-plane"></i>
                Notify Me on Launch
            </button>
        </form>
        
        <p style="margin-top: var(--spacing-md); color: rgba(255,255,255,0.7); font-size: 0.95rem;">
            <i class="fas fa-shield-alt"></i> Your information is safe with us. We respect your privacy.
        </p>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Create animated particles
    const particlesContainer = document.getElementById('particles');
    const particleCount = 20; // Reduced for better performance
    
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        // Random properties
        const size = Math.random() * 3 + 1;
        const left = Math.random() * 100;
        const delay = Math.random() * 15;
        const duration = Math.random() * 10 + 10;
        
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${left}%`;
        particle.style.animationDelay = `${delay}s`;
        particle.style.animationDuration = `${duration}s`;
        
        particlesContainer.appendChild(particle);
    }
    
    // Countdown Timer
    const launchDate = new Date('<?php echo $launchDate; ?>').getTime();
    
    function updateCountdown() {
        const now = new Date().getTime();
        const timeRemaining = launchDate - now;
        
        if (timeRemaining > 0) {
            const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
            
            document.getElementById('days').textContent = days.toString().padStart(2, '0');
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        } else {
            // Countdown finished
            document.querySelector('.countdown-container').innerHTML = `
                <div style="text-align: center; padding: var(--spacing-sm);">
                    <h3 style="color: var(--color-accent); margin-bottom: var(--spacing-sm);">
                        <i class="fas fa-check-circle"></i> Launched!
                    </h3>
                    <p style="color: var(--color-white); font-size: 0.9rem;">The Student Life section is now live!</p>
                </div>
            `;
        }
    }
    
    // Update countdown every second
    updateCountdown();
    setInterval(updateCountdown, 1000);
    
    // Scroll animations
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    
    animateElements.forEach(element => {
        observer.observe(element);
    });
    
    // Notify form submission
    const notifyForm = document.getElementById('notifyForm');
    
    if (notifyForm) {
        notifyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('.notify-btn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            submitBtn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Show success message
                this.innerHTML = `
                    <div style="text-align: center; padding: var(--spacing-sm);">
                        <div style="font-size: 2.5rem; color: var(--color-accent); margin-bottom: var(--spacing-sm);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 style="color: var(--color-white); margin-bottom: var(--spacing-sm); font-size: 1.1rem;">
                            Successfully Registered!
                        </h3>
                        <p style="color: rgba(255,255,255,0.9); font-size: 0.9rem;">
                            We'll notify you as soon as the Student Life section launches.
                        </p>
                    </div>
                `;
            }, 1500);
        });
    }
    
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            e.preventDefault();
            const targetElement = document.querySelector(href);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Handle reduced motion preference
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        // Disable floating animations
        const particles = document.querySelectorAll('.particle');
        particles.forEach(particle => {
            particle.style.animation = 'none';
        });
        
        // Disable pulse animation
        const badge = document.querySelector('.coming-soon-badge');
        if (badge) {
            badge.style.animation = 'none';
        }
    }
    
    // Ensure hero content is properly sized on load
    function adjustHeroHeight() {
        const heroSection = document.querySelector('.student-life-hero');
        const heroContent = document.querySelector('.hero-content');
        
        if (heroSection && heroContent) {
            const contentHeight = heroContent.offsetHeight;
            const windowHeight = window.innerHeight;
            
            // Add padding to ensure content fits
            if (contentHeight > windowHeight * 0.8) {
                heroSection.style.paddingTop = 'var(--spacing-xxl)';
                heroSection.style.paddingBottom = 'var(--spacing-xxl)';
            }
        }
    }
    
    // Adjust on load and resize
    window.addEventListener('load', adjustHeroHeight);
    window.addEventListener('resize', adjustHeroHeight);
    setTimeout(adjustHeroHeight, 100); // Initial adjustment
});
</script>

</body>
</html>