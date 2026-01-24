<?php
/**
 * Contact Page View Template - Updated with Consistent Color Scheme
 * 
 * @package FCTCNS
 * @version 5.7
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
$flash_success = $flash_success ?? '';
$flash_error = $flash_error ?? '';
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
        'answer' => 'Candidates must:<br>• Score a minimum of 170 in the current UTME<br>• Select FCT College of Nursing Sciences, Gwagwalada as First Choice institution<br>• Have at least 5 O\'Level credits (English Language, Mathematics, Biology, Chemistry, Physics) in not more than 2 sittings (WAEC/NECO/NABTEB)<br>• Be 16 years of age or above'
    ],
    [
        'question' => 'When is the application period?',
        'answer' => 'Application periods vary each year. Please check the admissions page or official portal for current dates and deadlines.'
    ],
    [
        'question' => 'How do I apply?',
        'answer' => 'Applications are submitted online via the official portal: <a href="https://consap.fcthhss.abj.gov.ng" target="_blank">https://consap.fcthhss.abj.gov.ng</a>.<br>Follow the step-by-step guide on the portal.'
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
main.contact-page { 
    margin-top: 0 !important; 
    padding-top: 0 !important; 
}
.contact-hero { 
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
.contact-hero {
    position: relative;
    height: 75vh;
    max-height: 650px;
    min-height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
}

.contact-hero-bg {
    position: absolute;
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%;
    background-size: cover; 
    background-position: center;
    background-image: url('<?php echo $baseUrl; ?>/assets/images/contact/hero-placeholder.jpg');
    opacity: 0.6;
}

.contact-hero-bg::after {
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

.contact-hero-content {
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

.contact-hero-badge { 
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

.contact-hero-title { 
    font-family: var(--font-heading); 
    font-size: clamp(1.8rem, 4vw, 2.8rem); 
    font-weight: 700; 
    color: var(--color-white); 
    text-shadow: 0 2px 6px rgba(0,0,0,0.5);
    line-height: 1.2;
    margin-bottom: var(--spacing-sm);
}

.contact-hero-subtitle { 
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
    text-align: center;
    height: 100%;
}

.card:hover { 
    transform: translateY(-8px); 
    box-shadow: var(--shadow-elevated); 
    border-color: var(--color-primary-light);
}

.card-body { 
    padding: var(--spacing-lg); 
}

.card-title { 
    font-family: var(--font-heading); 
    font-size: 1.4rem; 
    font-weight: 600; 
    color: var(--color-primary); 
    margin-bottom: var(--spacing-md); 
}

.contact-icon { 
    font-size: 3rem; 
    color: var(--color-primary); 
    margin-bottom: var(--spacing-md); 
}

/* ==========================================================================
   SUCCESS/ERROR MESSAGES - Enhanced
   ========================================================================== */
.success-message, .error-message {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    box-shadow: var(--shadow-soft);
    text-align: center;
    margin: var(--spacing-xl) auto;
    max-width: 800px;
    border-left: 6px solid;
}

.success-message {
    border-left-color: var(--color-accent);
}

.success-message i { 
    color: var(--color-accent); 
    font-size: 4rem; 
    margin-bottom: var(--spacing-lg); 
    display: block; 
}

.error-message {
    border-left-color: #dc3545;
}

.error-message i { 
    color: #dc3545; 
    font-size: 4rem; 
    margin-bottom: var(--spacing-lg); 
    display: block; 
}

.success-message h3, .error-message h3 { 
    font-family: var(--font-heading); 
    font-size: 1.8rem; 
    margin-bottom: var(--spacing-md); 
    color: var(--color-primary); 
}

/* ==========================================================================
   FAQ - Enhanced
   ========================================================================== */
.faq-item {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-md);
    box-shadow: var(--shadow-subtle);
    overflow: hidden;
    transition: var(--transition-smooth);
}

.faq-item:hover {
    box-shadow: var(--shadow-soft);
}

.faq-question {
    padding: var(--spacing-lg);
    background: var(--color-gray-50);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    color: var(--color-primary);
    transition: var(--transition-smooth);
}

.faq-question:hover { 
    background: var(--color-primary-very-light); 
}

.faq-answer {
    padding: 0 var(--spacing-lg);
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease, padding 0.4s ease;
}

.faq-answer.open {
    padding: var(--spacing-lg);
    max-height: 800px;
}

.faq-toggle { 
    font-size: 1.5rem; 
    transition: transform 0.3s ease; 
    color: var(--color-primary);
}

.faq-toggle.open { 
    transform: rotate(45deg); 
    color: var(--color-accent);
}

/* ==========================================================================
   CONTACT FORM - Enhanced
   ========================================================================== */
.contact-form {
    background: var(--color-white);
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-subtle);
    border: 1px solid var(--color-gray-100);
    max-width: 800px;
    margin: 0 auto;
}

.form-row { 
    display: grid; 
    grid-template-columns: 1fr; 
    gap: var(--spacing-lg); 
    margin-bottom: var(--spacing-lg);
}

@media (min-width: 768px) { 
    .form-row { 
        grid-template-columns: 1fr 1fr; 
    } 
}

.form-group { 
    margin-bottom: var(--spacing-lg); 
}

.form-label { 
    display: block; 
    margin-bottom: var(--spacing-sm); 
    font-weight: 600; 
    color: var(--color-primary); 
}

.form-input, .form-textarea, .form-select {
    width: 100%;
    padding: var(--spacing-sm);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-size: 1rem;
    transition: var(--transition-smooth);
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px var(--color-primary-transparent);
}

.form-textarea { 
    min-height: 150px; 
    resize: vertical; 
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
    cursor: pointer;
    border: none;
}

.btn-primary:hover { 
    background: var(--color-accent-dark); 
    color: var(--color-gray-900);
    transform: translateY(-3px); 
    box-shadow: var(--shadow-soft); 
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
@media (max-width: 768px) {
    :root {
        --spacing-xs: 0.5rem;
        --spacing-sm: 0.875rem;
        --spacing-md: 1.25rem;
        --spacing-lg: 1.75rem;
        --spacing-xl: 2rem;
        --spacing-xxl: 2.5rem;
    }
    
    .contact-hero {
        height: 60vh;
        min-height: 400px;
    }
    
    .contact-hero-content {
        margin-top: 10vh;
        padding: var(--spacing-lg);
        margin-left: var(--spacing-md);
        margin-right: var(--spacing-md);
    }
    
    .grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .contact-form {
        padding: var(--spacing-lg);
    }
    
    .faq-question {
        padding: var(--spacing-md);
    }
    
    .faq-answer.open {
        padding: var(--spacing-md);
    }
    
    .btn-primary,
    .btn-secondary {
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        min-height: 44px;
    }
    
    .success-message,
    .error-message {
        padding: var(--spacing-lg);
        margin: var(--spacing-lg) var(--spacing-md);
    }
    
    .success-message h3,
    .error-message h3 {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .contact-hero {
        height: 55vh;
        min-height: 350px;
    }
    
    .contact-hero-badge {
        padding: 0.4rem 1rem;
        font-size: 0.75rem;
    }
    
    .contact-hero-title {
        font-size: 1.6rem;
    }
    
    .contact-hero-subtitle {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.4rem;
    }
    
    .card-title {
        font-size: 1.2rem;
    }
    
    .contact-icon {
        font-size: 2.5rem;
    }
    
    .form-row {
        gap: var(--spacing-md);
    }
    
    .form-group {
        margin-bottom: var(--spacing-md);
    }
}

/* Print Styles */
@media print {
    .contact-hero {
        height: auto;
        min-height: auto;
        background: var(--color-white);
        color: var(--color-black);
    }
    
    .contact-hero-bg {
        display: none;
    }
    
    .contact-hero-content {
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
    
    .contact-form {
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
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
    .faq-item:hover {
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

<main class="contact-page">
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="contact-hero-bg"></div>
        <div class="container">
            <div class="contact-hero-content">
                <span class="contact-hero-badge">Get in Touch</span>
                <h1 class="contact-hero-title">Contact Us</h1>
                <p class="contact-hero-subtitle">
                    We're here to assist you with any questions or inquiries.
                </p>
            </div>
        </div>
    </section>

    <!-- Success Message (Form Hidden) -->
    <?php if (!empty($flash_success)): ?>
    <div class="container">
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <h3>Message Sent Successfully!</h3>
            <p><?php echo e($flash_success); ?></p>
            <p>Thank you for contacting us. We have received your message and will respond within 24-48 hours.</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Error Message (Form Still Visible Below) -->
    <?php if (!empty($flash_error)): ?>
    <div class="container">
        <div class="error-message">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>There was an error sending your message</h3>
            <p><?php echo e($flash_error); ?></p>
            <p>Please review the form and try again, or contact us directly using the information below.</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Contact Information -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Contact Information</h2>
            </div>

            <div class="grid">
                <div class="card">
                    <div class="card-body">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <h3 class="card-title">Address</h3>
                        <p><?php echo e($settings['address'] ?? 'FCT College of Nursing Sciences<br>Gwagwalada, Abuja'); ?></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                        <h3 class="card-title">Phone</h3>
                        <p><?php echo e($settings['phone'] ?? '+234 XXX XXX XXXX'); ?><br>
                        <?php echo e($settings['admissions_phone'] ?? 'Admissions: Ext. XXX'); ?></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <h3 class="card-title">Email</h3>
                        <p><?php echo e($settings['email'] ?? 'info@fctcns.edu.ng'); ?><br>
                        <?php echo e($settings['admissions_email'] ?? 'admissions@fctcns.edu.ng'); ?></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="contact-icon"><i class="fas fa-clock"></i></div>
                        <h3 class="card-title">Office Hours</h3>
                        <p><?php echo e($settings['working_hours'] ?? 'Monday – Friday: 8:00 AM – 5:00 PM'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form - Only shown if NO success message -->
    <?php if (empty($flash_success)): ?>
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Send us a Message</h2>
                <p class="section-subtitle">We typically respond within 24-48 hours.</p>
            </div>

            <div class="contact-form">
                <form action="<?php echo $baseUrl; ?>/contact/submit" method="POST" id="contactForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-input" required value="<?php echo e($_POST['name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-input" required value="<?php echo e($_POST['email'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-input" value="<?php echo e($_POST['phone'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Inquiry Type</label>
                            <select name="department" class="form-select">
                                <option value="general">General Inquiry</option>
                                <option value="admissions" <?php echo (($_POST['department'] ?? '') === 'admissions') ? 'selected' : ''; ?>>Admissions</option>
                                <option value="academic" <?php echo (($_POST['department'] ?? '') === 'academic') ? 'selected' : ''; ?>>Academic Programs</option>
                                <option value="student" <?php echo (($_POST['department'] ?? '') === 'student') ? 'selected' : ''; ?>>Student Services</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subject *</label>
                        <input type="text" name="subject" class="form-input" required value="<?php echo e($_POST['subject'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-textarea" rows="8" required><?php echo e($_POST['message'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Attachment (Optional)</label>
                        <input type="file" name="attachment" class="form-input">
                    </div>

                    <div style="text-align:center;">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Frequently Asked Questions -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="section-subtitle">Find answers to common questions about our programs and admissions.</p>
            </div>

            <div>
                <?php foreach ($faqs as $faq): ?>
                <div class="faq-item">
                    <div class="faq-question" onclick="this.nextElementSibling.classList.toggle('open'); this.querySelector('.faq-toggle').classList.toggle('open');">
                        <span><?php echo e($faq['question']); ?></span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p><?php echo nl2br(e($faq['answer'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="container">
            <h2 class="section-title">Visit Our Campus</h2>
            <p class="section-subtitle" style="max-width:700px; margin:0 auto var(--spacing-xl);">
                We welcome visitors and prospective students.
            </p>
            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-secondary">
                <i class="fas fa-calendar-alt"></i> Schedule a Visit
            </a>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure all FAQ answers are closed on load
    document.querySelectorAll('.faq-answer').forEach(answer => {
        answer.classList.remove('open');
    });
    document.querySelectorAll('.faq-toggle').forEach(toggle => {
        toggle.classList.remove('open');
    });
    
    // Form validation enhancement
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const requiredFields = contactForm.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
            }
        });
    }
});
</script>

</body>
</html>