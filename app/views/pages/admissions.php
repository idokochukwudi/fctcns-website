<?php
/**
 * Admissions Page View Template - Updated for 2025/2026 Session
 * 
 * @package FCTCNS
 * @version 4.7
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
   GLOBAL VARIABLES - Consistent with Homepage Color Scheme
   ========================================================================== */
:root {
    /* Professional Color Palette - Matching Homepage */
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
    --color-gray-800: #2D3748;
    --color-gray-900: #1A202C;
    --color-black: #000000;
    
    /* Typography - Consistent with Homepage */
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
   HERO SECTION - Consistent with Homepage
   ========================================================================== */
.admissions-hero {
    position: relative;
    height: 75vh;
    max-height: 650px;
    min-height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
}

.admissions-hero-bg {
    position: absolute;
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%;
    background-size: cover; 
    background-position: center;
    background-image: url('<?php echo $baseUrl; ?>/assets/images/admissions/hero-2025.jpg');
    opacity: 0.6; /* Reduced opacity for less intense background */
}

.admissions-hero-bg::after {
    content: ''; 
    position: absolute; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%;
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.4) 0%, /* Lighter gradient for less intense background */
        rgba(0, 0, 0, 0.25) 50%,
        rgba(0, 0, 0, 0.15) 100%
    );
}

.admissions-hero-content {
    position: relative;
    z-index: 3;
    color: var(--color-white);
    max-width: 700px;
    padding: var(--spacing-xl);
    margin: 0 auto;
    text-align: center;
    margin-top: 15vh;
}

.admissions-hero-badge { 
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

.admissions-hero-title { 
    font-family: var(--font-heading); 
    font-size: clamp(1.8rem, 4vw, 2.8rem); 
    font-weight: 700; 
    color: var(--color-white); 
    text-shadow: 0 2px 6px rgba(0,0,0,0.5);
    line-height: 1.2;
    margin-bottom: var(--spacing-sm);
}

.admissions-hero-subtitle { 
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
    background: var(--color-gray-50); /* Lighter background */
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

.card-body { 
    padding: var(--spacing-lg); 
}

.card-title { 
    font-family: var(--font-heading); 
    font-size: 1.4rem; 
    font-weight: 600; 
    color: var(--color-primary); 
    margin-bottom: var(--spacing-md); 
    line-height: 1.3;
}

/* ==========================================================================
   LISTS & CONTENT STYLES
   ========================================================================== */
.requirement-list { 
    list-style: none; 
    padding: 0; 
    margin: 0; 
}

.requirement-list li { 
    padding: 0.6rem 0; 
    position: relative; 
    padding-left: 1.8rem; 
    color: var(--color-gray-800);
    line-height: 1.5;
}

.requirement-list li::before { 
    content: '✓'; 
    position: absolute; 
    left: 0; 
    color: var(--color-accent); 
    font-weight: bold; 
    font-size: 1.1rem; 
}

.step-list { 
    counter-reset: step; 
    list-style: none; 
    padding: 0; 
    margin: var(--spacing-lg) 0;
}

.step-list li { 
    counter-increment: step; 
    position: relative; 
    padding: 1.5rem 1.5rem 1.5rem 4rem; 
    border-left: 3px solid var(--color-primary-light); 
    margin-bottom: var(--spacing-lg); 
    background: var(--color-gray-50); /* Lighter background */
    border-radius: var(--radius-lg);
    transition: var(--transition-smooth);
}

.step-list li:hover {
    background: var(--color-white);
    box-shadow: var(--shadow-soft);
}

.step-list li::before { 
    content: counter(step); 
    position: absolute; 
    left: -20px; 
    top: 1.5rem; 
    background: var(--color-primary); 
    color: var(--color-white); 
    width: 40px; 
    height: 40px; 
    border-radius: 50%; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-weight: 700;
    font-size: 1.1rem;
    border: 3px solid var(--color-white);
    box-shadow: var(--shadow-subtle);
}

.step-list li strong {
    display: block;
    color: var(--color-primary);
    margin-bottom: var(--spacing-sm);
    font-size: 1.2rem;
}

/* ==========================================================================
   ALERT BOX - Enhanced
   ========================================================================== */
.alert-important {
    background: var(--color-gray-50); /* Lighter background */
    border-left: 4px solid var(--color-accent);
    padding: var(--spacing-lg);
    border-radius: var(--radius-lg);
    margin: var(--spacing-xl) 0;
    box-shadow: var(--shadow-subtle);
}

.alert-important h3 {
    color: var(--color-primary); 
    margin-bottom: var(--spacing-sm);
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.alert-important p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-sm);
}

.alert-important p:last-child {
    margin-bottom: 0;
}

/* ==========================================================================
   BUTTONS - Consistent with Homepage
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

.btn-disabled {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: var(--color-gray-300);
    color: var(--color-gray-600);
    padding: 0.9rem 2rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    border: 2px solid var(--color-gray-300);
    font-family: var(--font-heading);
    font-size: 1rem;
    letter-spacing: 0.3px;
    min-height: 50px;
    cursor: not-allowed;
    opacity: 0.8;
}

/* ==========================================================================
   PROCESS FLOWCHART - Enhanced Display
   ========================================================================== */
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
    background: var(--color-gray-50); /* Lighter background */
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
   STATUS BANNER - Application Closed
   ========================================================================== */
.status-banner {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-left: 5px solid #dc3545;
    padding: var(--spacing-lg);
    border-radius: var(--radius-md);
    margin: var(--spacing-xl) 0;
    text-align: center;
    box-shadow: var(--shadow-subtle);
}

.status-banner h3 {
    color: #dc3545;
    margin-bottom: var(--spacing-sm);
    font-size: 1.4rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-sm);
}

.status-banner p {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-sm);
}

.status-banner p:last-child {
    margin-bottom: 0;
}

/* ==========================================================================
   CTA SECTION - Consistent with Homepage
   ========================================================================== */
.cta-section { 
    background: linear-gradient(135deg, var(--color-gray-50), var(--color-white)); /* Lighter gradient */
    text-align: center; 
    padding: var(--spacing-xxl) 0; 
    border-top: 1px solid var(--color-gray-100);
    border-bottom: 1px solid var(--color-gray-100);
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
        height: 60vh;
        min-height: 400px;
    }
    
    .admissions-hero-content {
        margin-top: 10vh;
        padding: var(--spacing-lg);
    }
    
    .grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .step-list li {
        padding: 1.25rem 1.25rem 1.25rem 3.5rem;
        margin-bottom: var(--spacing-md);
    }
    
    .step-list li::before {
        left: -18px;
        top: 1.25rem;
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }
    
    .btn-primary,
    .btn-secondary,
    .btn-disabled {
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        min-height: 44px;
    }
    
    .process-flowchart-placeholder {
        height: 300px;
    }
}

@media (max-width: 480px) {
    .admissions-hero {
        height: 55vh;
        min-height: 350px;
    }
    
    .admissions-hero-badge {
        padding: 0.4rem 1rem;
        font-size: 0.75rem;
    }
    
    .admissions-hero-title {
        font-size: 1.6rem;
    }
    
    .admissions-hero-subtitle {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.4rem;
    }
    
    .card-title {
        font-size: 1.2rem;
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
    
    .card {
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
    }
    
    .alert-important {
        border: 1px solid var(--color-gray-300);
    }
    
    .status-banner {
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
    <!-- Hero Section -->
    <section class="admissions-hero">
        <div class="admissions-hero-bg"></div>
        <div class="container">
            <div class="admissions-hero-content">
                <span class="admissions-hero-badge">2025/2026 Admissions</span>
                <h1 class="admissions-hero-title">ND/HND Nursing Programme</h1>
                <p class="admissions-hero-subtitle">
                    <!-- UPDATED: Application is now closed -->
                    Application for 2025/2026 Session is Closed • Sales of Forms: 15th – 28th September 2025
                </p>
            </div>
        </div>
    </section>

    <!-- Application Closed Banner -->
    <section class="section">
        <div class="container">
            <div class="status-banner">
                <h3><i class="fas fa-times-circle"></i> Application Closed</h3>
                <p><strong>The application portal for the 2025/2026 academic session is now closed.</strong></p>
                <p>The sales of forms period ended on 28th September 2025. No further applications are being accepted for this session.</p>
                <p class="text-muted" style="margin-top: var(--spacing-sm);">Please check back for updates on the 2026/2027 admissions cycle.</p>
            </div>
        </div>
    </section>

    <!-- Important Notice -->
    <section class="section section-alt">
        <div class="container">
            <div class="alert-important">
                <h3>⚠️ Important Notice</h3>
                <p><strong>No extension</strong> of the application deadline. The College has <strong>NO AGENTS</strong>. Beware of fraudulent websites and deal only through official channels.</p>
            </div>
        </div>
    </section>

    <!-- Key Details -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Key Admission Details</h2>
            </div>
            
            <div class="grid">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Application Period</h3>
                        <p><strong>Sales of Forms:</strong> Monday, 15th September – Wednesday, 28th September 2025</p>
                        <p><strong>Application Fee:</strong> ₦2,200 (Non-refundable)</p>
                        <p class="text-muted" style="margin-top: var(--spacing-sm);"><em>Application period has ended</em></p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Examination Dates</h3>
                        <p><strong>Post UTME Screening:</strong> 6th, 7th, and 8th October 2025</p>
                        <p><strong>Venue:</strong> FCT College of Nursing Sciences, Gwagwalada (within UATH)</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Requirements -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Eligibility Requirements</h2>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <ul class="requirement-list">
                        <li>Minimum UTME score of <strong>170</strong> in 2025 JAMB examination</li>
                        <li>Selected FCT College of Nursing Sciences, Gwagwalada as <strong>First Choice</strong> institution</li>
                        <li>At least <strong>5 O'Level Credits</strong> (English, Mathematics, Biology, Chemistry, Physics) in not more than <strong>2 sittings</strong> (WAEC/NECO/NABTEB)</li>
                        <li>Must be <strong>16 years</strong> of age or above</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Application Process -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Application Process (2025/2026)</h2>
            </div>
            
            <!-- Process Flowchart Image Display -->
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
            
            <ol class="step-list">
                <li>
                    <strong>Sign Up / Create Account</strong>
                    <ul class="requirement-list" style="margin-top: var(--spacing-sm);">
                        <li>Visit <a href="<?php echo $applicationPortal; ?>" target="_blank"><?php echo $applicationPortal; ?></a></li>
                        <li>Read instructions and check the agreement box</li>
                        <li>Enter your JAMB registration number to validate</li>
                        <li>Provide email, phone number, and create password</li>
                    </ul>
                </li>
                <li>
                    <strong>Complete Application Form</strong>
                    <ul class="requirement-list" style="margin-top: var(--spacing-sm);">
                        <li>Log in with your credentials</li>
                        <li>Click "Apply Now" or "My Application"</li>
                        <li>Fill all required fields accurately</li>
                    </ul>
                </li>
                <li>
                    <strong>Make Payment</strong>
                    <ul class="requirement-list" style="margin-top: var(--spacing-sm);">
                        <li>Click "Proceed to Payment"</li>
                        <li>Generate RRR code</li>
                        <li>Pay ₦2,200 online or at any bank</li>
                        <li>Return to portal and click "Verify Payment"</li>
                    </ul>
                </li>
                <li>
                    <strong>Print Exam Slip</strong>
                    <ul class="requirement-list" style="margin-top: var(--spacing-sm);">
                        <li>Download and print your examination slip</li>
                        <li>Bring printed slip to the exam venue</li>
                    </ul>
                </li>
            </ol>
            <p class="text-muted text-center" style="margin-top: var(--spacing-lg);">
                <em>This application process was for the 2025/2026 session. The portal is now closed.</em>
            </p>
        </div>
    </section>

    <!-- Support Contact -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Support & Enquiries</h2>
            </div>
            
            <div class="grid">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Phone Support</h3>
                        <p><strong>Call:</strong> 07039837749 / 08036625119</p>
                        <p><strong>WhatsApp Only:</strong> 08082775076</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Email & Online</h3>
                        <p><strong>Email:</strong> support.consap@fcthhss.abj.gov.ng</p>
                        <p><strong>Live Chat:</strong> Available on the portal</p>
                        <p><strong>Telegram:</strong> <a href="https://t.me/+SWH5opeTcTXs34Ko" target="_blank" class="text-primary">Official Channel</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="container">
            <h2 class="section-title">2025/2026 Admissions</h2>
            <p class="section-subtitle" style="max-width:700px; margin:0 auto var(--spacing-xl);">
                The application period for the 2025/2026 academic session has ended.
            </p>
            <div>
                <span class="btn-disabled">
                    <i class="fas fa-lock"></i> Application Portal (Closed)
                </span>
                <a href="<?php echo $baseUrl; ?>/programs" class="btn-secondary" style="margin-left: var(--spacing-md);">
                    <i class="fas fa-book-open"></i> View Programs
                </a>
            </div>
            <p class="text-muted" style="margin-top: var(--spacing-lg);">
                Check back later for information about the 2026/2027 admissions cycle.
            </p>
        </div>
    </section>
</main>

</body>
</html>