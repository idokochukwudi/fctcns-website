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
/* CRITICAL FIX FOR HEADER SPACING */
body > main.main-content {
    margin-top: 0 !important;
}

.homepage-content {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.hero-section {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.hero-carousel {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Override any existing margins */
*[style*="margin-top"], 
*[style*="padding-top"] {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Rest of your existing homepage CSS here... */
</style>
<style>
/* ==========================================================================
   CRITICAL FIX: No gap between header and content
   ========================================================================== */
body { margin: 0 !important; padding: 0 !important; }
main.admissions-page { margin-top: 0 !important; padding-top: 0 !important; }
.admissions-hero { margin-top: 0 !important; padding-top: 0 !important; }

/* ==========================================================================
   GLOBAL VARIABLES - Mature Transparent Purple Theme
   ========================================================================== */
:root {
    --color-primary: rgba(107, 78, 155, 0.9);
    --color-primary-dark: rgba(90, 65, 133, 0.9);
    --color-primary-light: rgba(123, 92, 174, 0.8);
    --color-primary-very-light: rgba(240, 235, 247, 0.6);
    
    --color-accent: rgba(255, 126, 95, 0.9);
    --color-accent-dark: rgba(229, 106, 74, 0.9);
    
    --color-white-solid: #ffffff;
    --color-gray-800: rgba(52, 58, 64, 0.9);
    
    --font-heading: 'Montserrat', sans-serif;
    --font-body: 'Open Sans', sans-serif;
    
    --spacing-xs: 0.5rem;
    --spacing-sm: 1rem;
    --spacing-md: 1.5rem;
    --spacing-lg: 2rem;
    --spacing-xl: 2.5rem;
    --spacing-xxl: 3.5rem;
    
    --shadow-sm: 0 1px 3px rgba(107, 78, 155, 0.08);
    --shadow-md: 0 3px 10px rgba(107, 78, 155, 0.12);
    --shadow-lg: 0 8px 25px rgba(107, 78, 155, 0.15);
    
    --radius-md: 8px;
    --radius-lg: 12px;
    
    --transition-base: all 0.3s ease;
}

* { box-sizing: border-box; }
body { font-family: var(--font-body); font-size: 15px; line-height: 1.6; color: var(--color-gray-800); background: var(--color-white-solid); }
.container { width: 100%; max-width: 1100px; margin: 0 auto; padding: 0 var(--spacing-sm); }

/* ==========================================================================
   HERO SECTION
   ========================================================================== */
.admissions-hero {
    position: relative;
    height: 75vh;
    max-height: 600px;
    min-height: 450px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.3), rgba(123, 92, 174, 0.2));
}

.admissions-hero-bg {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background-size: cover; background-position: center;
    /* PLACEHOLDER: Admissions hero image */
    background-image: url('<?php echo $baseUrl; ?>/public/assets/images/admissions/hero-2025.jpg');
}

.admissions-hero-bg::after {
    content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(90deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.3) 40%, rgba(0,0,0,0.2) 100%);
}

.admissions-hero-content {
    position: relative;
    z-index: 3;
    color: var(--color-white-solid);
    max-width: 680px;
    padding: var(--spacing-lg);
    margin-left: 8%;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(8px);
    border-radius: var(--radius-md);
    border: 1px solid rgba(255, 255, 255, 0.15);
    margin-top: 15vh;
}

.admissions-hero-badge { 
    display: inline-block;
    background: var(--color-accent); 
    color: var(--color-white-solid); 
    padding: 0.4rem 1rem; 
    border-radius: 4px; 
    font-size: 0.8rem; 
    font-weight: 600; 
    margin-bottom: var(--spacing-md); 
}
.admissions-hero-title { font-family: var(--font-heading); font-size: 2.8rem; font-weight: 700; color: var(--color-white-solid); text-shadow: 0 2px 6px rgba(0,0,0,0.5); }
.admissions-hero-subtitle { font-size: 1.2rem; color: rgba(255,255,255,0.95); }

/* ==========================================================================
   SECTIONS & CARDS
   ========================================================================== */
.section { padding: var(--spacing-xl) 0; }
.section-alt { background: var(--color-primary-very-light); }

.section-header { text-align: center; margin-bottom: var(--spacing-xl); max-width: 700px; margin-left: auto; margin-right: auto; }
.section-title { font-family: var(--font-heading); font-size: 2rem; font-weight: 600; color: var(--color-primary); position: relative; display: inline-block; }
.section-title::after { content: ''; position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 60px; height: 3px; background: var(--color-accent); border-radius: 2px; }
.section-subtitle { font-size: 1.1rem; color: var(--color-gray-800); margin-top: var(--spacing-lg); line-height: 1.6; }

.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--spacing-lg); }

.card {
    background: var(--color-white-solid);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    transition: var(--transition-base);
    border: 1px solid rgba(107, 78, 155, 0.1);
}
.card:hover { transform: translateY(-8px); box-shadow: var(--shadow-md); }

.card-body { padding: var(--spacing-lg); }
.card-title { font-family: var(--font-heading); font-size: 1.6rem; font-weight: 600; color: var(--color-primary); margin-bottom: var(--spacing-md); }

.requirement-list { list-style: none; padding: 0; margin: 0; }
.requirement-list li { padding: 0.6rem 0; position: relative; padding-left: 1.8rem; color: var(--color-gray-800); }
.requirement-list li::before { content: '✓'; position: absolute; left: 0; color: var(--color-accent); font-weight: bold; font-size: 1.2rem; }

.step-list { counter-reset: step; list-style: none; padding: 0; }
.step-list li { counter-increment: step; position: relative; padding: 1rem 0 1rem 3rem; border-left: 3px solid var(--color-primary-light); margin-bottom: var(--spacing-md); background: rgba(240, 235, 247, 0.3); border-radius: var(--radius-md); }
.step-list li::before { content: counter(step); position: absolute; left: -12px; top: 1rem; background: var(--color-primary); color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }

/* Alert Box */
.alert-important {
    background: rgba(255, 126, 95, 0.1);
    border-left: 4px solid var(--color-accent);
    padding: var(--spacing-lg);
    border-radius: var(--radius-md);
    margin: var(--spacing-xl) 0;
}

/* Buttons */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--color-accent);
    color: var(--color-white-solid);
    padding: 0.8rem 1.8rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-base);
}
.btn-primary:hover { background: var(--color-accent-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }

/* CTA */
.cta-section { background: linear-gradient(135deg, rgba(107, 78, 155, 0.08), rgba(123, 92, 174, 0.05)); text-align: center; padding: var(--spacing-xxl) 0; }

/* Responsive */
@media (max-width: 768px) {
    .admissions-hero-content { margin-left: 5%; max-width: 90%; text-align: center; margin-top: 10vh; }
    .admissions-hero-title { font-size: 2.2rem; }
    .grid { grid-template-columns: 1fr; }
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
                    Post UTME Screening Exercise Now Open • Sales of Forms: 15th – 28th September 2025
                </p>
            </div>
        </div>
    </section>

    <!-- Important Notice -->
    <section class="section section-alt">
        <div class="container">
            <div class="alert-important">
                <h3 style="color: var(--color-primary); margin-bottom: var(--spacing-sm);">⚠️ Important Notice</h3>
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
                <h2 class="section-title">How to Apply</h2>
            </div>
            
            <!-- IMAGE PLACEHOLDER: Application process flowchart -->
            <div style="background:#f0f0f0; height:300px; border-radius:var(--radius-lg); margin-bottom:var(--spacing-xl); display:flex; align-items:center; justify-content:center; color:#999; font-size:1.2rem; text-align:center;">
                Application Process Flowchart Placeholder<br>(Add: /public/assets/images/admissions/process-flowchart.jpg)
            </div>
            
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
                        <p><strong>Telegram:</strong> <a href="https://t.me/+SWH5opeTcTXs34Ko" target="_blank">Official Channel</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="container">
            <h2 class="section-title">Apply Now for 2025/2026 Session</h2>
            <p class="section-subtitle" style="max-width:700px; margin:0 auto var(--spacing-xl);">
                Don't miss the deadline – 28th September 2025
            </p>
            <a href="<?php echo $applicationPortal; ?>" target="_blank" class="btn-primary">
                <i class="fas fa-external-link-alt"></i> Go to Application Portal
            </a>
        </div>
    </section>
</main>

</body>
</html>