<?php
/**
 * Contact Page View Template - Premium Design Pattern
 * Applied design system from admissions and homepage
 * Enhanced with professional attractive form design
 * Form fully centered, hero content positioned to reveal background
 * "Schedule a Visit" button now links to contact form with proper functionality
 * 
 * @package FCTCNS
 * @version 8.7 - Premium Design Pattern with Fixed Schedule Visit Button
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$page_title = $page_title ?? 'Contact Us | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Get in touch with us for inquiries about admissions, programs, or general information.';

// Preserve all backend variables
$settings = $contact_settings ?? [];
$csrf_token = $csrf_token ?? '';

// Updated FAQ with accurate program information
$faqs = [
    [
        'question' => 'What programs does the college currently offer?',
        'answer' => 'The college has transitioned to the collegial system. We no longer offer Basic Nursing or Basic Midwifery. Currently, we offer the ND/HND Nursing Programme (non-terminal). ND Nursing lasts 2 years, followed by HND Nursing for another 2 years, making a total of 4 years.'
    ],
    [
        'question' => 'Does the college still offer Basic Nursing or Basic Midwifery?',
        'answer' => 'No. We have fully transitioned to the collegial system and no longer offer Basic Nursing or Basic Midwifery programs. We now offer only the ND/HND Nursing Programme.'
    ],
    [
        'question' => 'What are the admission requirements for the ND/HND Nursing Programme?',
        'answer' => 'Candidates must: • Score a minimum of 170 in the current UTME • Select FCT College of Nursing Sciences, Gwagwalada as First Choice institution • Have at least 5 O\'Level credits (English Language, Mathematics, Biology, Chemistry, Physics) in not more than 2 sittings (WAEC/NECO/NABTEB) • Be 16 years of age or above'
    ],
    [
        'question' => 'When is the application period?',
        'answer' => 'Application periods vary each year. Please check the admissions page or official portal for current dates and deadlines.'
    ],
    [
        'question' => 'How do I apply?',
        'answer' => 'Applications are submitted online via the official portal. Visit our admissions page for the complete step-by-step application guide, portal access, and detailed instructions.'
    ],
    [
        'question' => 'Is there accommodation on campus?',
        'answer' => 'Limited hostel accommodation is available on a first-come, first-served basis.'
    ],
    [
        'question' => 'Are there scholarship opportunities?',
        'answer' => 'Yes, merit-based and need-based scholarships are available. Contact the admissions office for current opportunities.'
    ]
];

$heroImagePath = rtrim($baseUrl, '/') . '/assets/images/contact/contact.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="<?php echo e($page_description); ?>">
    <title><?php echo e($page_title); ?></title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Preload hero image -->
    <link rel="preload" href="<?php echo $heroImagePath; ?>" as="image">
    
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
   CRITICAL: NO GAP BETWEEN HEADER AND CONTENT
   ========================================================================== */
html, body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
    background: var(--white);
}

body > *:first-child {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

main.contact-page {
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
    
    /* Professional Spacing */
    --spacing-xs: 0.5rem;
    --spacing-sm: 0.875rem;
    --spacing-md: 1.25rem;
    --spacing-lg: 2rem;
    --spacing-xl: 3rem;
    --spacing-xxl: 4rem;
    --spacing-xxxl: 5rem;
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
    width: 100%;
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
   SECTION HEADER — Centralized
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
    width: 100%;
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
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 3vw, 2.4rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
    line-height: 1.2;
    text-align: center;
    width: 100%;
}

.section-subtitle {
    font-size: clamp(1rem, 1.5vw, 1.2rem);
    color: var(--slate);
    line-height: 1.6;
    max-width: 700px;
    margin: 0 auto;
    font-weight: 400;
    text-align: center;
    width: 100%;
}

/* ==========================================================================
   BUTTONS — matches premium design pattern
   ========================================================================== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0.85rem 2rem;
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

.btn--lg { padding: 1rem 2.5rem; font-size: 1rem; }

/* Legacy button classes mapped to new design */
.btn-primary {
    background: var(--gold);
    color: white;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0.85rem 2rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.22s ease;
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
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0.85rem 2rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.22s ease;
}

.btn-secondary:hover {
    background: var(--purple-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(139,123,184,0.32);
}

.btn-call {
    background: transparent;
    color: white;
    border: 1.5px solid rgba(255,255,255,0.35);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0.85rem 2rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.22s ease;
}

.btn-call:hover {
    border-color: white;
    background: rgba(255,255,255,0.1);
    transform: translateY(-2px);
}

.btn-admissions {
    background: var(--purple);
    color: white;
    border: 1.5px solid var(--gold);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 1rem 2.5rem;
    border-radius: var(--radius-md);
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.22s ease;
}

.btn-admissions:hover {
    background: var(--purple-dark);
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(139,123,184,0.4);
    border-color: var(--gold-light);
}

.btn-admissions i {
    color: var(--gold);
}

/* ==========================================================================
   HERO SECTION — Positioned to reveal background image
   ========================================================================== */
.contact-hero {
    position: relative;
    width: 100%;
    background: linear-gradient(145deg, #2A2A42 0%, #383856 100%);
    color: var(--white);
    padding: var(--spacing-xxl) 0;
    margin: 0;
    border: none;
    overflow: hidden;
    min-height: 550px;
    display: flex;
    align-items: center;
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('<?php echo $heroImagePath; ?>');
    background-size: cover;
    background-position: center 30%;
    background-repeat: no-repeat;
    opacity: 0.25;
    z-index: 1;
}

.contact-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 70% 30%, rgba(139,123,184,0.15) 0%, transparent 70%);
    z-index: 2;
    pointer-events: none;
}

.hero-text-overlay {
    position: relative;
    z-index: 3;
    width: 100%;
    max-width: var(--container-max);
    margin: 0 auto;
    padding: 0 var(--gutter);
}

.hero-text-wrapper {
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: var(--radius-lg);
    padding: clamp(2rem, 5vw, 3rem);
    max-width: 650px;
    width: fit-content;
    box-shadow: var(--shadow-xl);
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: rgba(139,123,184,0.2);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(201,164,74,0.35);
    padding: 0.5rem 1.25rem;
    border-radius: var(--radius-full);
    margin-bottom: 1.25rem;
    font-family: var(--font-mono);
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--gold);
    width: fit-content;
}

.hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.2rem, 5vw, 3.2rem);
    font-weight: 700;
    line-height: 1.1;
    color: white;
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
    text-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.hero-title span {
    color: var(--gold-light);
    font-style: italic;
}

.hero-description {
    font-size: clamp(1rem, 2vw, 1.2rem);
    color: rgba(255,255,255,0.95);
    font-weight: 300;
    line-height: 1.7;
    margin-bottom: 2rem;
    max-width: 550px;
}

.hero-cta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 2rem;
}

.hero-icon {
    position: absolute;
    right: 5%;
    top: 50%;
    transform: translateY(-50%);
    font-size: clamp(4rem, 12vw, 8rem);
    color: rgba(255,255,255,0.1);
    z-index: 2;
    pointer-events: none;
}

/* ==========================================================================
   CARDS — Centralized
   ========================================================================== */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
    width: 100%;
}

.card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: clamp(1.5rem, 3vw, 2rem);
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--purple), var(--purple-light));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.28s ease;
}

.card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(139,123,184,0.2);
}

.card:hover::before {
    transform: scaleX(1);
}

.contact-icon {
    width: 70px;
    height: 70px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1.25rem;
    transition: all 0.28s ease;
}

.card:hover .contact-icon {
    background: var(--purple);
    color: white;
    transform: scale(1.1);
}

.card-title {
    font-family: var(--font-display);
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
    text-align: center;
    width: 100%;
}

.card p {
    font-size: 0.95rem;
    color: var(--slate);
    line-height: 1.7;
    text-align: center;
    width: 100%;
}

/* ==========================================================================
   CONTACT FORM — FULLY CENTRALIZED
   ========================================================================== */
.contact-form {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 0;
    box-shadow: var(--shadow-lg);
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    overflow: hidden;
    width: 100%;
}

/* Form Accent Decoration */
.form-accent {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 1.5rem 2rem 0.5rem;
}

.accent-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--purple-light);
    opacity: 0.5;
}

.accent-line {
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, var(--purple), var(--gold), var(--purple));
    border-radius: 2px;
    max-width: 200px;
}

/* Form Header - FULLY CENTERED */
.form-header {
    text-align: center;
    padding: 0 2rem 1.5rem;
    border-bottom: 1px solid var(--border);
    margin-bottom: 1.5rem;
}

.form-header-text {
    text-align: center;
}

.form-header-text h3 {
    font-family: var(--font-display);
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 0.5rem;
    text-align: center;
}

.form-header-text p {
    font-size: 1rem;
    color: var(--slate);
    text-align: center;
    max-width: 500px;
    margin: 0 auto;
}

/* Response Time Badge - FULLY CENTERED with flexbox wrapper */
.response-time-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    margin-bottom: 2rem;
}

.response-time-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: var(--purple-pale);
    padding: 0.5rem 1.25rem;
    border-radius: var(--radius-full);
    border: 1px solid var(--purple-light);
    width: fit-content;
}

.response-time-badge i {
    color: var(--purple);
    font-size: 1rem;
}

.response-time-badge span {
    font-size: 0.9rem;
    color: var(--purple-dark);
    font-weight: 500;
}

/* Form Body */
.form-body {
    padding: 0 2rem 2rem;
    max-width: 700px;
    margin: 0 auto;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 768px) {
    .form-row {
        grid-template-columns: 1fr 1fr;
    }
}

.form-group {
    margin-bottom: 1.5rem;
    position: relative;
    width: 100%;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-label {
    display: block;
    margin-bottom: 0.6rem;
    font-family: var(--font-body);
    font-weight: 500;
    color: var(--ink-soft);
    font-size: 0.9rem;
    letter-spacing: 0.3px;
    text-align: left;
}

.required-star {
    color: var(--red);
    margin-left: 3px;
    font-size: 1.1rem;
}

/* Input Wrapper */
.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.form-input,
.form-textarea,
.form-select {
    width: 100%;
    padding: 0.9rem 1rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-size: 0.95rem;
    color: var(--ink);
    background: var(--white);
    transition: all 0.3s ease;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
}

.form-input:hover,
.form-textarea:hover,
.form-select:hover {
    border-color: var(--purple-light);
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: var(--purple);
    box-shadow: 0 0 0 4px var(--purple-pale), inset 0 2px 4px rgba(0,0,0,0.02);
}

/* Select specific styling */
.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%238B7BB8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px;
    padding-right: 2.5rem;
}

/* Textarea specific */
.form-textarea {
    min-height: 140px;
    resize: vertical;
}

/* Form Footer - CENTERED on mobile */
.form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

.form-footer-note {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--slate);
    font-size: 0.9rem;
}

.form-footer-note i {
    color: var(--gold);
    font-size: 1.1rem;
}

.form-footer-note span {
    font-weight: 500;
    color: var(--ink-soft);
}

/* Submit Button */
.btn-submit {
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    color: white;
    border: none;
    padding: 1rem 2.5rem;
    border-radius: var(--radius-md);
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}

.btn-submit::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    background: linear-gradient(135deg, var(--purple-dark), var(--purple));
}

.btn-submit:hover::before {
    left: 100%;
}

.btn-submit i {
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}

.btn-submit:hover i {
    transform: translateX(5px);
}

/* ==========================================================================
   FAQ SECTION — Centralized
   ========================================================================== */
.faq-item {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    margin-bottom: 1rem;
    overflow: hidden;
    transition: all 0.28s ease;
    width: 100%;
}

.faq-item:hover {
    border-color: rgba(139,123,184,0.3);
    box-shadow: var(--shadow-md);
}

.faq-question {
    padding: 1.5rem;
    background: var(--white);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--ink);
    transition: all 0.22s ease;
}

.faq-question:hover {
    color: var(--purple);
    background: var(--purple-pale);
}

.faq-toggle {
    font-size: 1.5rem;
    font-weight: 300;
    color: var(--purple);
    transition: transform 0.3s ease;
}

.faq-toggle.open {
    transform: rotate(45deg);
    color: var(--gold);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: var(--surface);
}

.faq-answer.open {
    max-height: 500px;
}

.faq-answer p {
    padding: 1.5rem;
    font-size: 0.95rem;
    color: var(--slate);
    line-height: 1.7;
    white-space: pre-line;
}

/* ==========================================================================
   ADMISSIONS CTA — Centralized
   ========================================================================== */
.admissions-cta-container {
    background: linear-gradient(160deg, #2A3042 0%, #3A4055 100%);
    border-radius: var(--radius-xl);
    padding: clamp(2rem, 4vw, 3rem);
    margin-top: var(--spacing-xl);
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    width: 100%;
}

.admissions-cta-container::before {
    content: '';
    position: absolute;
    left: 0;
    top: 15%;
    bottom: 15%;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple-light), var(--purple));
    border-radius: 3px;
}

.admissions-cta-title {
    font-family: var(--font-display);
    font-size: 1.6rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
    text-align: center;
}

.admissions-cta-text {
    font-size: 1rem;
    color: rgba(255,255,255,0.8);
    line-height: 1.7;
    max-width: 600px;
    margin: 0 auto 1.5rem;
    text-align: center;
}

/* ==========================================================================
   CTA SECTION — Centralized premium card
   ========================================================================== */
.cta-section {
    background: linear-gradient(160deg, #2A3042 0%, #3A4055 100%);
    border-radius: var(--radius-xl);
    padding: clamp(3rem, 6vw, 4rem);
    margin: var(--spacing-xxl) auto;
    max-width: 1000px;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    width: 100%;
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

.cta-section .section-title {
    color: white;
    margin-bottom: 1rem;
    text-align: center;
}

.cta-section .section-subtitle {
    color: rgba(255,255,255,0.8);
    margin-bottom: 2rem;
    text-align: center;
}

.cta-section .btn {
    margin: 0 auto;
    cursor: pointer;
    display: inline-flex;
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

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.card,
.faq-item,
.admissions-cta-container,
.contact-form,
.cta-section {
    opacity: 0;
    animation: fadeInUp 0.5s ease forwards;
}

.form-header {
    animation: slideInLeft 0.5s ease 0.1s forwards;
    opacity: 0;
}

.form-group {
    opacity: 0;
    animation: fadeInUp 0.5s ease forwards;
}

.form-group:nth-child(1) { animation-delay: 0.1s; }
.form-group:nth-child(2) { animation-delay: 0.15s; }
.form-group:nth-child(3) { animation-delay: 0.2s; }
.form-group:nth-child(4) { animation-delay: 0.25s; }
.form-group:nth-child(5) { animation-delay: 0.3s; }
.form-group:nth-child(6) { animation-delay: 0.35s; }

.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }

.faq-item:nth-child(1) { animation-delay: 0.1s; }
.faq-item:nth-child(2) { animation-delay: 0.15s; }
.faq-item:nth-child(3) { animation-delay: 0.2s; }
.faq-item:nth-child(4) { animation-delay: 0.25s; }
.faq-item:nth-child(5) { animation-delay: 0.3s; }
.faq-item:nth-child(6) { animation-delay: 0.35s; }
.faq-item:nth-child(7) { animation-delay: 0.4s; }

@media (prefers-reduced-motion: reduce) {
    * {
        animation: none !important;
        transition: none !important;
    }
}

/* ==========================================================================
   RESPONSIVE DESIGN — Fully responsive adjustments
   ========================================================================== */
@media (max-width: 1024px) {
    :root {
        --gutter: 2rem;
    }
    
    .hero-text-wrapper {
        max-width: 600px;
    }
    
    .hero-icon {
        font-size: clamp(3rem, 10vw, 6rem);
    }
}

@media (max-width: 768px) {
    :root {
        --gutter: 1.5rem;
        --spacing-xl: 2.5rem;
        --spacing-xxl: 3rem;
    }
    
    .contact-hero {
        min-height: 450px;
        padding: var(--spacing-xl) 0;
    }
    
    .contact-hero::before {
        background-position: center 20%;
    }
    
    .hero-text-wrapper {
        padding: 1.5rem;
        max-width: 100%;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-description {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .hero-cta {
        flex-direction: column;
        width: 100%;
    }
    
    .hero-cta .btn,
    .hero-cta .btn-call {
        width: 100%;
        justify-content: center;
    }
    
    .hero-icon {
        display: none;
    }
    
    .grid {
        gap: 1rem;
    }
    
    .card {
        padding: 1.5rem;
    }
    
    /* Form responsive */
    .form-header {
        padding: 0 1.5rem 1.5rem;
    }
    
    .form-header-text h3 {
        font-size: 1.4rem;
    }
    
    .form-body {
        padding: 0 1.5rem 1.5rem;
    }
    
    .form-footer {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .form-footer-note {
        justify-content: center;
    }
    
    .btn-submit {
        width: 100%;
        justify-content: center;
    }
    
    .faq-question {
        padding: 1.25rem;
        font-size: 1rem;
    }
    
    .admissions-cta-title {
        font-size: 1.4rem;
    }
    
    .btn-admissions {
        width: 100%;
        max-width: 400px;
        justify-content: center;
        margin: 0 auto;
    }
    
    .cta-section {
        padding: 2.5rem 1.5rem;
    }
    
    .cta-section .btn {
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 480px) {
    :root {
        --gutter: 1rem;
        --spacing-xl: 2rem;
        --spacing-xxl: 2.5rem;
    }
    
    .contact-hero {
        min-height: 400px;
    }
    
    .hero-text-wrapper {
        padding: 1.25rem;
    }
    
    .hero-badge {
        font-size: 0.7rem;
        padding: 0.4rem 1rem;
    }
    
    .hero-title {
        font-size: 1.6rem;
    }
    
    .hero-description {
        font-size: 0.95rem;
    }
    
    .section-title {
        font-size: 1.6rem;
    }
    
    .section-subtitle {
        font-size: 0.95rem;
    }
    
    .card-title {
        font-size: 1.2rem;
    }
    
    .contact-icon {
        width: 60px;
        height: 60px;
        font-size: 1.6rem;
    }
    
    /* Form responsive small */
    .form-header-text h3 {
        font-size: 1.3rem;
    }
    
    .form-header-text p {
        font-size: 0.9rem;
    }
    
    .form-row {
        gap: 1rem;
    }
    
    .form-input,
    .form-textarea,
    .form-select {
        padding: 0.8rem;
        font-size: 0.9rem;
    }
    
    .response-time-badge {
        padding: 0.4rem 1rem;
    }
    
    .response-time-badge span {
        font-size: 0.85rem;
    }
    
    .faq-question {
        padding: 1rem;
        font-size: 0.95rem;
    }
    
    .faq-toggle {
        font-size: 1.25rem;
    }
    
    .faq-answer p {
        padding: 1rem;
        font-size: 0.9rem;
    }
    
    .admissions-cta-title {
        font-size: 1.2rem;
    }
    
    .admissions-cta-text {
        font-size: 0.95rem;
    }
    
    .btn-admissions {
        padding: 0.875rem 1.5rem;
        font-size: 1rem;
    }
    
    .cta-section {
        padding: 2rem 1rem;
    }
    
    .cta-section .section-title {
        font-size: 1.5rem;
    }
    
    .cta-section .section-subtitle {
        font-size: 0.95rem;
    }
}

/* Large screens */
@media (min-width: 1400px) {
    .hero-text-wrapper {
        max-width: 700px;
    }
    
    .hero-description {
        max-width: 600px;
    }
    
    .contact-form {
        max-width: 1000px;
    }
    
    .form-body {
        max-width: 800px;
    }
    
    .cta-section {
        max-width: 1100px;
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
    .contact-hero,
    .hero-cta,
    .btn,
    .btn-admissions,
    .cta-section {
        display: none !important;
    }
    
    .card,
    .faq-item {
        box-shadow: none;
        border: 1px solid #ccc;
        break-inside: avoid;
    }
}
</style>
</head>
<body>

<main class="contact-page">
    <!-- Hero Section - Positioned to reveal background image -->
    <section class="contact-hero" id="contactHero">
        <div class="hero-text-overlay">
            <div class="hero-text-wrapper">
                <div class="hero-badge">
                    <i class="fas fa-headset"></i>
                    <span>Get in Touch</span>
                </div>
                <h1 class="hero-title">
                    Contact <span>Our Team</span>
                </h1>
                <p class="hero-description">
                    We're here to assist you with admissions, program inquiries, and general information. 
                    Our dedicated team is committed to providing timely and helpful responses.
                </p>
                <div class="hero-cta">
                    <a href="#contact-form" class="btn--gold btn">
                        <i class="fas fa-envelope"></i> Send Message
                    </a>
                    <a href="tel:<?php echo e($settings['phone'] ?? '+234XXX'); ?>" class="btn-call">
                        <i class="fas fa-phone-alt"></i> Call Now
                    </a>
                </div>
            </div>
            <div class="hero-icon" aria-hidden="true">
                <i class="fas fa-headset"></i>
            </div>
        </div>
    </section>

    <!-- Contact Information - Premium Cards - Centralized -->
    <section class="section section--alt">
        <div class="container">
            <div class="section-header section-header--center">
                <h2 class="section-title">Contact Information</h2>
                <p class="section-subtitle">Multiple ways to reach our team</p>
            </div>

            <div class="grid">
                <div class="card">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3 class="card-title">Address</h3>
                    <p><?php echo nl2br(e($settings['address'] ?? 'FCT College of Nursing Sciences<br>Gwagwalada, Abuja')); ?></p>
                </div>

                <div class="card">
                    <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                    <h3 class="card-title">Phone</h3>
                    <p>0904 767 6799<br>0703 983 7749<br><?php echo e($settings['admissions_phone'] ?? 'Admissions: Ext. 123'); ?></p>
                </div>

                <div class="card">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <h3 class="card-title">Email</h3>
                    <p><?php echo e($settings['email'] ?? 'info@fctcns.edu.ng'); ?><br>
                    <?php echo e($settings['admissions_email'] ?? 'admissions@fctcns.edu.ng'); ?></p>
                </div>

                <div class="card">
                    <div class="contact-icon"><i class="fas fa-clock"></i></div>
                    <h3 class="card-title">Office Hours</h3>
                    <p><?php echo e($settings['working_hours'] ?? 'Monday – Friday: 8:00 AM – 5:00 PM'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form - FULLY CENTRALIZED -->
    <section class="section" id="contact-form">
        <div class="container">
            <div class="section-header section-header--center">
                <h2 class="section-title">Send us a Message</h2>
                <p class="section-subtitle">We typically respond within 24-48 hours.</p>
            </div>

            <div class="contact-form">
                <!-- Decorative top accent -->
                <div class="form-accent">
                    <div class="accent-dot"></div>
                    <div class="accent-line"></div>
                    <div class="accent-dot"></div>
                </div>
                
                <!-- Form Header - FULLY CENTERED -->
                <div class="form-header">
                    <div class="form-header-text">
                        <h3>We'd love to hear from you</h3>
                        <p>Fill out the form below and our team will get back to you shortly</p>
                    </div>
                </div>
                
                <!-- Response Time Badge - FULLY CENTERED with wrapper -->
                <div class="response-time-wrapper">
                    <div class="response-time-badge">
                        <i class="fas fa-clock"></i>
                        <span>Average response time: <strong>4 hours</strong></span>
                    </div>
                </div>
                
                <!-- Form Body - CENTERED with max-width -->
                <div class="form-body">
                    <form action="<?php echo $baseUrl; ?>/contact/submit" method="POST" id="contactForm" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    Full Name <span class="required-star">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" name="name" class="form-input" required value="<?php echo e($_POST['name'] ?? ''); ?>" placeholder="Enter your full name">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    Email Address <span class="required-star">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="email" name="email" class="form-input" required value="<?php echo e($_POST['email'] ?? ''); ?>" placeholder="you@example.com">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    Phone Number
                                </label>
                                <div class="input-wrapper">
                                    <input type="tel" name="phone" class="form-input" value="<?php echo e($_POST['phone'] ?? ''); ?>" placeholder="+234 XXX XXX XXXX">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    Inquiry Type
                                </label>
                                <div class="input-wrapper">
                                    <select name="department" class="form-select">
                                        <option value="general" <?php echo (($_POST['department'] ?? '') === 'general') ? 'selected' : ''; ?>>General Inquiry</option>
                                        <option value="admissions" <?php echo (($_POST['department'] ?? '') === 'admissions') ? 'selected' : ''; ?>>Admissions</option>
                                        <option value="academic" <?php echo (($_POST['department'] ?? '') === 'academic') ? 'selected' : ''; ?>>Academic Programs</option>
                                        <option value="student" <?php echo (($_POST['department'] ?? '') === 'student') ? 'selected' : ''; ?>>Student Services</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Subject <span class="required-star">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="text" name="subject" class="form-input" required value="<?php echo e($_POST['subject'] ?? ''); ?>" placeholder="Brief subject of your message">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Message <span class="required-star">*</span>
                            </label>
                            <textarea name="message" class="form-textarea" rows="6" required placeholder="Please write your message here..."><?php echo e($_POST['message'] ?? ''); ?></textarea>
                        </div>

                        <!-- Form Footer -->
                        <div class="form-footer">
                            <div class="form-footer-note">
                                <i class="fas fa-shield-alt"></i>
                                <span>Your information is secure</span>
                            </div>
                            
                            <button type="submit" class="btn-submit">
                                <span>Send Message</span>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Frequently Asked Questions - Centralized -->
    <section class="section section--alt">
        <div class="container">
            <div class="section-header section-header--center">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="section-subtitle">Find answers to common questions about our programs and admissions.</p>
            </div>

            <div>
                <?php foreach ($faqs as $faq): ?>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span><?php echo e($faq['question']); ?></span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p><?php echo nl2br(e($faq['answer'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Premium Admissions CTA - Centralized -->
            <div class="admissions-cta-container">
                <div class="admissions-cta-title">
                    Ready to Start Your Journey?
                </div>
                <p class="admissions-cta-text">
                    Visit our comprehensive admissions page for complete information on programs, requirements, application deadlines, and the step-by-step application process.
                </p>
                <a href="<?php echo $baseUrl; ?>/admissions" class="btn-admissions">
                    Go to Admissions Page <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Premium CTA Section - Schedule a Visit now links to contact form -->
    <div class="container">
        <section class="cta-section">
            <h2 class="section-title">Visit Our Campus</h2>
            <p class="section-subtitle">
                We welcome visitors and prospective students to tour our facilities and learn more about our programs.
            </p>
            <a href="#contact-form" class="btn--gold btn--lg" onclick="document.getElementById('contact-form').scrollIntoView({behavior: 'smooth'}); return false;">
                Schedule a Visit <i class="fas fa-calendar-alt"></i>
            </a>
        </section>
    </div>
</main>

<script>
// Toggle FAQ function
function toggleFAQ(element) {
    const answer = element.nextElementSibling;
    const toggle = element.querySelector('.faq-toggle');
    
    // Toggle classes
    answer.classList.toggle('open');
    toggle.classList.toggle('open');
    
    // Change + to × (multiplication sign)
    if (toggle.textContent === '+') {
        toggle.textContent = '×';
    } else {
        toggle.textContent = '+';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Ensure all FAQ answers are closed on load
    document.querySelectorAll('.faq-answer').forEach(answer => {
        answer.classList.remove('open');
    });
    document.querySelectorAll('.faq-toggle').forEach(toggle => {
        toggle.classList.remove('open');
        toggle.textContent = '+';
    });
    
    // Form validation with enhanced feedback
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const requiredFields = contactForm.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--red)';
                    field.style.boxShadow = '0 0 0 3px rgba(192, 57, 43, 0.1)';
                    
                    // Add shake animation
                    field.style.animation = 'shake 0.5s ease';
                    setTimeout(() => {
                        field.style.animation = '';
                    }, 500);
                } else {
                    field.style.borderColor = '';
                    field.style.boxShadow = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
            }
        });
        
        // Real-time validation styling
        const inputs = contactForm.querySelectorAll('.form-input, .form-textarea, .form-select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.style.borderColor = 'var(--red)';
                } else if (this.value.trim()) {
                    this.style.borderColor = 'var(--green)';
                } else {
                    this.style.borderColor = '';
                }
            });
            
            input.addEventListener('focus', function() {
                this.style.borderColor = 'var(--purple)';
            });
        });
    }
    
    // EXTRA SAFETY: Force remove any margin/padding on body and first elements
    document.body.style.margin = '0';
    document.body.style.padding = '0';
    const firstChild = document.body.firstElementChild;
    if (firstChild) {
        firstChild.style.marginTop = '0';
        firstChild.style.paddingTop = '0';
    }
});

// Add shake animation keyframes if not already present
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);
</script>

</body>
</html>