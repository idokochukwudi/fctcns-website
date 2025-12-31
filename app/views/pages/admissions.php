<?php
/**
 * Admissions Page View - MVC Version
 * 
 * Professional, international-standard admissions page for FCT College of Nursing Sciences
 * Pure MVC view - only displays data passed from PageController.
 * 
 * Available variables from PageController:
 * - $baseUrl: Base URL for assets
 * - $page_title, $page_description, $currentPage, etc.
 * 
 * @package FCTCNS
 * @version 2.1
 */

// Extract data passed from controller
extract($data ?? []);

// Helper function for escaping output
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// Set defaults
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '/fctcns-website');
$page_title = $page_title ?? 'Admissions - FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Apply for nursing programs. Learn about admission requirements, application process, and deadlines.';
$currentPage = $currentPage ?? 'admissions';

// Admission data
$currentYear = date('Y');
$nextYear = $currentYear + 1;
$applicationPortalUrl = 'https://consap.fcthhss.abj.gov.ng';

$admissionTimeline = [
    [
        'period' => 'January Intake',
        'activities' => [
            ['date' => "October 1, $currentYear", 'activity' => 'Online applications open'],
            ['date' => "December 15, $currentYear", 'activity' => 'Application deadline'],
            ['date' => "January 5-10, $nextYear", 'activity' => 'Entrance examination'],
            ['date' => "January 15-20, $nextYear", 'activity' => 'Oral interview'],
            ['date' => "January 25, $nextYear", 'activity' => 'Admission list published'],
            ['date' => "February 1, $nextYear", 'activity' => 'Registration begins']
        ]
    ],
    [
        'period' => 'September Intake',
        'activities' => [
            ['date' => "April 1, $nextYear", 'activity' => 'Online applications open'],
            ['date' => "July 15, $nextYear", 'activity' => 'Application deadline'],
            ['date' => "August 5-10, $nextYear", 'activity' => 'Entrance examination'],
            ['date' => "August 15-20, $nextYear", 'activity' => 'Oral interview'],
            ['date' => "August 25, $nextYear", 'activity' => 'Admission list published'],
            ['date' => "September 1, $nextYear", 'activity' => 'Registration begins']
        ]
    ]
];

$generalRequirements = [
    'Five credit passes in O\'Level (WAEC/NECO) including English Language, Mathematics, Biology, Chemistry, and Physics',
    'Credits must be obtained in not more than two sittings',
    'Minimum age of 17 years at time of application',
    'Birth certificate or declaration of age',
    'Medical fitness certificate from a recognized hospital',
    'Two recent passport photographs (white background)',
    'Character reference letter from a reputable individual',
    'Testimonial from last school attended'
];

$programSpecificRequirements = [
    'Basic Nursing' => [
        'Secondary school certificate with required credits',
        'JAMB UTME score (where applicable)',
        'Post-UTME screening participation'
    ],
    'National Diploma Nursing' => [
        'Secondary school certificate with required credits',
        'JAMB UTME score for direct entry',
        'Basic Nursing certificate for some specialties'
    ],
    'Post Basic Nursing' => [
        'Registered Nurse (RN) certificate',
        'Current practicing license',
        'Minimum one year post-registration experience',
        'Recommendation letter from employer'
    ],
    'Community Health Nursing' => [
        'Secondary school certificate with required credits',
        'Interest in community health services',
        'Willingness to work in rural areas'
    ]
];

$admissionProcess = [
    ['step' => 1, 'title' => 'Application Submission', 'description' => 'Complete online application via official portal with required documents'],
    ['step' => 2, 'title' => 'Document Verification', 'description' => 'Credentials verification and eligibility screening by admissions committee'],
    ['step' => 3, 'title' => 'Entrance Examination', 'description' => 'Written examination covering English, Mathematics, Biology, and General Knowledge'],
    ['step' => 4, 'title' => 'Oral Interview', 'description' => 'Personal interview to assess communication skills and professional suitability'],
    ['step' => 5, 'title' => 'Admission Offer', 'description' => 'Provisional admission offer to successful candidates'],
    ['step' => 6, 'title' => 'Acceptance & Registration', 'description' => 'Acceptance fee payment and completion of registration formalities']
];
?>

<!-- Page-specific styles -->
<style>
/* Reset and Base Styles */
.admissions-container {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', Roboto, sans-serif;
    line-height: 1.6;
    color: #1a202c;
    background: #fff;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

/* Modern Hero Section */
.hero-section {
    background: linear-gradient(135deg, #1a365d 0%, #2c5282 100%);
    color: white;
    padding: 6rem 0 4rem;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(66, 153, 225, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(56, 178, 172, 0.1) 0%, transparent 50%);
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
}

.hero-badge {
    display: inline-block;
    background: linear-gradient(135deg, #38b2ac, #4299e1);
    color: white;
    padding: 0.5rem 1.25rem;
    border-radius: 2rem;
    font-size: 0.875rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    background: linear-gradient(to right, #fff, #e2e8f0);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.hero-subtitle {
    font-size: 1.25rem;
    line-height: 1.6;
    opacity: 0.95;
    max-width: 700px;
    margin: 0 auto 2.5rem;
    color: #cbd5e0;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #38b2ac;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #a0aec0;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

/* Enhanced Primary CTA Button */
.cta-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: linear-gradient(135deg, #38a169, #2f855a);
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 0.75rem;
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    font-size: 1.125rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(56, 161, 105, 0.3);
}

.cta-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: 0.5s;
}

.cta-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(56, 161, 105, 0.4);
    background: linear-gradient(135deg, #2f855a, #276749);
}

.cta-primary:hover::before {
    left: 100%;
}

.cta-primary:focus {
    outline: 2px solid #38a169;
    outline-offset: 2px;
}

.cta-primary svg {
    width: 20px;
    height: 20px;
    transition: transform 0.3s ease;
}

.cta-primary:hover svg {
    transform: translateX(4px);
}

/* Breadcrumb Navigation */
.breadcrumb {
    background: #f7fafc;
    padding: 1rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.breadcrumb-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.breadcrumb-link {
    color: #4a5568;
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb-link:hover {
    color: #2c5282;
    text-decoration: underline;
}

.breadcrumb-separator {
    color: #a0aec0;
}

.breadcrumb-current {
    color: #2c5282;
    font-weight: 600;
}

/* Section Header */
.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-label {
    display: inline-block;
    color: #2c5282;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.75rem;
    background: rgba(44, 82, 130, 0.1);
    padding: 0.25rem 1rem;
    border-radius: 1rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.section-description {
    font-size: 1.125rem;
    color: #4a5568;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Enhanced Portal Section */
.portal-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
}

.portal-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.portal-card {
    background: white;
    border-radius: 1.5rem;
    padding: 3rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

.portal-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #38a169, #4299e1, #2c5282);
}

.portal-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.portal-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #38a169, #2f855a);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    box-shadow: 0 10px 20px rgba(56, 161, 105, 0.3);
}

.portal-icon svg {
    width: 36px;
    height: 36px;
}

.portal-features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.portal-feature {
    background: #f7fafc;
    padding: 1.5rem;
    border-radius: 1rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.portal-feature:hover {
    transform: translateY(-4px);
    border-color: #38a169;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.feature-icon {
    width: 48px;
    height: 48px;
    background: #e6fffa;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    color: #38b2ac;
}

.feature-icon svg {
    width: 24px;
    height: 24px;
}

.portal-actions {
    background: #f7fafc;
    border-radius: 1rem;
    padding: 2rem;
    margin-top: 2rem;
    text-align: center;
}

.portal-url-box {
    background: white;
    border: 2px dashed #cbd5e0;
    border-radius: 0.75rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.portal-url {
    font-family: 'SF Mono', Monaco, monospace;
    font-size: 0.875rem;
    color: #4a5568;
    word-break: break-all;
    background: #f7fafc;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    display: inline-block;
}

/* Requirements Section */
.requirements-section {
    padding: 5rem 0;
    background: white;
}

.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.requirement-card {
    background: linear-gradient(135deg, #fff, #f7fafc);
    border-radius: 1rem;
    padding: 2rem;
    border: 1px solid #e2e8f0;
    position: relative;
    transition: all 0.3s ease;
}

.requirement-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    border-color: #4299e1;
}

.card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.card-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #4299e1, #2c5282);
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.card-icon svg {
    width: 24px;
    height: 24px;
}

.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.requirements-list li {
    padding: 0.75rem 0;
    color: #4a5568;
    position: relative;
    padding-left: 1.75rem;
    border-bottom: 1px solid #e2e8f0;
}

.requirements-list li:last-child {
    border-bottom: none;
}

.requirements-list li::before {
    content: '✓';
    position: absolute;
    left: 0;
    top: 0.75rem;
    color: #38a169;
    font-weight: bold;
}

/* Timeline Section */
.timeline-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
}

.timeline-tabs {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.timeline-tab {
    padding: 1rem 2rem;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 0.75rem;
    font-weight: 600;
    color: #4a5568;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 200px;
    text-align: center;
}

.timeline-tab:hover {
    border-color: #4299e1;
    color: #2c5282;
}

.timeline-tab.active {
    background: #2c5282;
    border-color: #2c5282;
    color: white;
}

.timeline-content {
    background: white;
    border-radius: 1.5rem;
    padding: 3rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.timeline-header {
    text-align: center;
    margin-bottom: 3rem;
}

.timeline-period {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c5282;
    margin-bottom: 0.5rem;
}

.timeline-events {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
}

.timeline-events::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, #4299e1, #38b2ac);
}

.timeline-event {
    display: flex;
    align-items: flex-start;
    margin-bottom: 2rem;
    position: relative;
}

.timeline-marker {
    width: 60px;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
}

.timeline-dot {
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    border: 3px solid #4299e1;
    box-shadow: 0 0 0 4px rgba(66, 153, 225, 0.2);
}

.timeline-date {
    font-weight: 700;
    color: #2c5282;
    margin-bottom: 0.25rem;
    font-size: 1.125rem;
}

.timeline-activity {
    color: #4a5568;
    line-height: 1.5;
}

/* Process Steps */
.process-section {
    padding: 5rem 0;
    background: white;
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    counter-reset: step-counter;
}

.process-step {
    background: #f7fafc;
    border-radius: 1rem;
    padding: 2rem;
    position: relative;
    transition: all 0.3s ease;
}

.process-step:hover {
    transform: translateY(-4px);
    background: white;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
}

.process-step::before {
    counter-increment: step-counter;
    content: counter(step-counter);
    position: absolute;
    top: -20px;
    left: -20px;
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #2c5282, #4299e1);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
    box-shadow: 0 8px 16px rgba(44, 82, 130, 0.3);
}

.step-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #38b2ac, #4299e1);
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    color: white;
}

/* Contact Section */
.contact-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #1a365d 0%, #2c5282 100%);
    color: white;
}

.contact-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.contact-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 1rem;
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.contact-card:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-4px);
}

.contact-icon {
    width: 64px;
    height: 64px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
}

.contact-icon svg {
    width: 32px;
    height: 32px;
}

.contact-info {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0.5rem 0;
    color: white;
}

/* Stats Banner */
.stats-banner {
    background: linear-gradient(135deg, #38a169, #2f855a);
    color: white;
    padding: 3rem 0;
    margin: 3rem 0;
}

.stats-grid {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    text-align: center;
}

.stat-badge {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

/* FAQ Section */
.faq-section {
    padding: 5rem 0;
    background: #f7fafc;
}

.faq-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 2rem;
}

.faq-item {
    background: white;
    border-radius: 1rem;
    margin-bottom: 1rem;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.faq-question {
    padding: 1.5rem;
    font-weight: 600;
    color: #1a202c;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.faq-answer {
    padding: 0 1.5rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    color: #4a5568;
    border-top: 1px solid #e2e8f0;
}

.faq-answer.active {
    padding: 1.5rem;
    max-height: 500px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .hero-title {
        font-size: 2.75rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .portal-features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 4rem 0 3rem;
    }
    
    .hero-title {
        font-size: 2.25rem;
    }
    
    .hero-stats {
        gap: 2rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .section-title {
        font-size: 1.75rem;
    }
    
    .portal-card,
    .timeline-content {
        padding: 2rem;
    }
    
    .portal-features-grid,
    .requirements-grid,
    .process-steps,
    .contact-grid {
        grid-template-columns: 1fr;
    }
    
    .timeline-tabs {
        flex-direction: column;
        align-items: center;
    }
    
    .timeline-tab {
        width: 100%;
        max-width: 300px;
    }
    
    .timeline-events::before {
        left: 20px;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 1.875rem;
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
    }
    
    .cta-primary {
        padding: 0.875rem 2rem;
        font-size: 1rem;
    }
    
    .portal-card {
        padding: 1.5rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
}

/* Print Styles */
@media print {
    .hero-section,
    .cta-primary,
    .timeline-tabs,
    .stats-banner {
        display: none;
    }
    
    .section-header {
        margin-bottom: 1rem;
    }
    
    .requirement-card,
    .portal-card {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid #e2e8f0;
    }
}
</style>

<!-- Main Content -->
<main id="main-content" class="admissions-container" role="main" aria-label="Admissions information">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-badge" role="note" aria-label="Admission status">Admissions Open</div>
            <h1 class="hero-title">Transform Lives Through Nursing Excellence</h1>
            <p class="hero-subtitle">
                Join FCT College of Nursing Sciences, where we educate future healthcare leaders 
                with world-class training, modern facilities, and a commitment to compassionate care.
            </p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Graduate Employment</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24:1</div>
                    <div class="stat-label">Student-Faculty Ratio</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">85%</div>
                    <div class="stat-label">Clinical Success Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100+</div>
                    <div class="stat-label">Hospital Partnerships</div>
                </div>
            </div>
            
            <!-- UPDATED CTA BUTTON -->
            <a href="<?php echo $baseUrl; ?>/apply" class="cta-primary">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                </svg>
                Start Your Online Application
            </a>
        </div>
    </section>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <div class="breadcrumb-content">
            <a href="<?php echo $baseUrl; ?>/" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?php echo $baseUrl; ?>/programs" class="breadcrumb-link">Programs</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Admissions</span>
        </div>
    </nav>

    <!-- Stats Banner -->
    <section class="stats-banner">
        <div class="stats-grid">
            <div>
                <div class="stat-badge">2,500+</div>
                <div>Alumni Worldwide</div>
            </div>
            <div>
                <div class="stat-badge">45+</div>
                <div>Years of Excellence</div>
            </div>
            <div>
                <div class="stat-badge">15</div>
                <div>Specialized Programs</div>
            </div>
            <div>
                <div class="stat-badge">98%</div>
                <div>NCLEX Pass Rate</div>
            </div>
        </div>
    </section>

    <!-- Application Portal Section -->
    <section class="portal-section" id="application-portal" aria-labelledby="portal-title">
        <div class="portal-container">
            <header class="section-header">
                <div class="section-label">Digital Application</div>
                <h2 id="portal-title" class="section-title">Secure Online Application Portal</h2>
                <p class="section-description">
                    Submit your application through our secure, user-friendly portal designed 
                    for a seamless admissions experience with real-time tracking and updates.
                </p>
            </header>
            
            <div class="portal-card">
                <div class="portal-header">
                    <div class="portal-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 2rem; margin-bottom: 1rem;">Streamlined Application Process</h3>
                    <p style="font-size: 1.125rem; color: #4a5568;">
                        Our digital portal ensures your application is processed efficiently 
                        with secure document upload, automated verification, and instant 
                        confirmation notifications.
                    </p>
                </div>
                
                <div class="portal-features-grid">
                    <div class="portal-feature">
                        <div class="feature-icon">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h4>Military-Grade Security</h4>
                        <p>256-bit SSL encryption with biometric authentication for maximum data protection</p>
                    </div>
                    
                    <div class="portal-feature">
                        <div class="feature-icon">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h4>Progress Dashboard</h4>
                        <p>Real-time tracking of application status with milestone notifications</p>
                    </div>
                    
                    <div class="portal-feature">
                        <div class="feature-icon">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </div>
                        <h4>Smart Document Upload</h4>
                        <p>AI-powered document validation and auto-correct formatting assistance</p>
                    </div>
                    
                    <div class="portal-feature">
                        <div class="feature-icon">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h4>24/7 Support</h4>
                        <p>Round-the-clock technical assistance with average response time under 2 hours</p>
                    </div>
                </div>
                
                <div class="portal-actions">
                    <div class="portal-url-box">
                        <p style="margin-bottom: 0.5rem; color: #4a5568; font-size: 0.875rem;">Official Application Portal:</p>
                        <div class="portal-url"><?php echo e($applicationPortalUrl); ?></div>
                    </div>
                    
                    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        <a href="<?php echo $baseUrl; ?>/apply" class="cta-primary">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Begin Application
                        </a>
                        
                        <a href="<?php echo $baseUrl; ?>/apply/guide" class="btn" style="background: transparent; border: 2px solid #4299e1; color: #4299e1;">
                            <svg fill="currentColor" viewBox="0 0 20 20" style="width: 18px; height: 18px; margin-right: 8px;">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Application Guide
                        </a>
                    </div>
                    
                    <p style="margin-top: 1.5rem; color: #718096; font-size: 0.875rem; max-width: 600px;">
                        <strong>Important:</strong> Applications are processed exclusively through our official portal. 
                        Beware of fraudulent websites and always verify the URL before submitting.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Admission Requirements -->
    <section class="requirements-section" id="requirements" aria-labelledby="requirements-title">
        <div class="portal-container">
            <header class="section-header">
                <div class="section-label">Eligibility Criteria</div>
                <h2 id="requirements-title" class="section-title">Admission Requirements & Prerequisites</h2>
                <p class="section-description">
                    Comprehensive overview of academic and non-academic requirements for all nursing programs
                </p>
            </header>
            
            <div class="requirements-grid">
                <article class="requirement-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 style="font-size: 1.5rem; margin: 0;">Academic Requirements</h3>
                    </div>
                    <ul class="requirements-list">
                        <?php foreach ($generalRequirements as $requirement): ?>
                        <li><?php echo e($requirement); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </article>
                
                <article class="requirement-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 style="font-size: 1.5rem; margin: 0;">Documentation Checklist</h3>
                    </div>
                    <ul class="requirements-list">
                        <li>Completed online application form (print and sign)</li>
                        <li>Payment evidence for application fees</li>
                        <li>Certified true copies of academic certificates</li>
                        <li>Birth certificate or sworn declaration of age</li>
                        <li>Medical fitness certificate from government hospital</li>
                        <li>Certificate of Local Government Origin</li>
                        <li>Four recent passport photographs</li>
                        <li>Professional reference letters (for Post Basic programs)</li>
                    </ul>
                </article>
            </div>
            
            <div style="margin-top: 4rem;">
                <h3 style="text-align: center; font-size: 2rem; color: #1a202c; margin-bottom: 2rem;">Program-Specific Requirements</h3>
                
                <div class="program-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                    <?php foreach ($programSpecificRequirements as $program => $requirements): ?>
                    <div class="requirement-card">
                        <h4 style="color: #2c5282; margin-bottom: 1rem; font-size: 1.25rem;"><?php echo e($program); ?></h4>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <?php foreach ($requirements as $requirement): ?>
                            <li style="padding: 0.5rem 0; color: #4a5568; border-bottom: 1px solid #e2e8f0;">
                                <span style="color: #38a169; margin-right: 0.5rem;">✓</span>
                                <?php echo e($requirement); ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="timeline-section" id="timeline" aria-labelledby="timeline-title">
        <div class="portal-container">
            <header class="section-header">
                <div class="section-label">Academic Calendar</div>
                <h2 id="timeline-title" class="section-title">Admission Timeline & Deadlines</h2>
                <p class="section-description">
                    Critical dates and milestones for the <?php echo e($currentYear); ?>-<?php echo e($nextYear); ?> academic session
                </p>
            </header>
            
            <div class="timeline-tabs" role="tablist">
                <?php foreach ($admissionTimeline as $index => $timeline): ?>
                <button class="timeline-tab <?php echo $index === 0 ? 'active' : ''; ?>" 
                        data-tab="timeline-<?php echo $index + 1; ?>"
                        role="tab"
                        aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                    <?php echo e($timeline['period']); ?>
                </button>
                <?php endforeach; ?>
            </div>
            
            <?php foreach ($admissionTimeline as $index => $timeline): ?>
            <div class="timeline-content <?php echo $index === 0 ? '' : 'hidden'; ?>" 
                 id="panel-<?php echo $index + 1; ?>"
                 role="tabpanel"
                 style="<?php echo $index === 0 ? '' : 'display: none;'; ?>">
                <div class="timeline-header">
                    <h3 class="timeline-period"><?php echo e($timeline['period']); ?></h3>
                    <p style="color: #718096;">Key admission activities and important deadlines</p>
                </div>
                
                <div class="timeline-events">
                    <?php foreach ($timeline['activities'] as $activity): ?>
                    <div class="timeline-event">
                        <div class="timeline-marker">
                            <div class="timeline-dot"></div>
                        </div>
                        <div class="timeline-content-box">
                            <div class="timeline-date"><?php echo e($activity['date']); ?></div>
                            <div class="timeline-activity"><?php echo e($activity['activity']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                    <p style="color: #718096; font-size: 0.875rem;">
                        <strong>Note:</strong> Late applications may be considered on a space-available basis. 
                        Early application is strongly recommended.
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section" id="process" aria-labelledby="process-title">
        <div class="portal-container">
            <header class="section-header">
                <div class="section-label">Application Journey</div>
                <h2 id="process-title" class="section-title">The Admissions Process</h2>
                <p class="section-description">
                    A transparent, step-by-step guide from initial inquiry to enrollment confirmation
                </p>
            </header>
            
            <div class="process-steps">
                <?php foreach ($admissionProcess as $step): ?>
                <article class="process-step">
                    <div class="step-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4 style="font-size: 1.25rem; color: #1a202c; margin-bottom: 0.75rem;">Step <?php echo e($step['step']); ?>: <?php echo e($step['title']); ?></h4>
                    <p style="color: #4a5568; line-height: 1.6;"><?php echo e($step['description']); ?></p>
                </article>
                <?php endforeach; ?>
            </div>
            
            <div style="text-align: center; margin-top: 3rem; padding: 2rem; background: #f7fafc; border-radius: 1rem;">
                <h4 style="font-size: 1.25rem; color: #1a202c; margin-bottom: 1rem;">Estimated Processing Time</h4>
                <p style="color: #4a5568; max-width: 600px; margin: 0 auto;">
                    Complete applications are typically processed within <strong>4-6 weeks</strong> from submission. 
                    You will receive email notifications at each stage of the review process.
                </p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section" id="faq" aria-labelledby="faq-title">
        <div class="faq-container">
            <header class="section-header">
                <div class="section-label">Common Questions</div>
                <h2 id="faq-title" class="section-title">Frequently Asked Questions</h2>
                <p class="section-description">
                    Quick answers to common admissions queries
                </p>
            </header>
            
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        What is the application fee and payment method?
                        <svg fill="currentColor" viewBox="0 0 20 20" width="20" height="20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        The application fee is ₦10,000 (non-refundable). Payment can be made through our secure online portal using debit/credit cards, bank transfer, or at designated bank branches. A payment receipt must be uploaded with your application.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Can I apply for multiple programs simultaneously?
                        <svg fill="currentColor" viewBox="0 0 20 20" width="20" height="20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        No, applicants may only apply for one program per academic session. If you're unsure which program best fits your qualifications, contact our admissions office for guidance before submitting your application.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        What are the accommodation options for students?
                        <svg fill="currentColor" viewBox="0 0 20 20" width="20" height="20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        We offer on-campus dormitories with modern amenities and 24/7 security. Accommodation is guaranteed for all first-year students. Off-campus housing assistance is also available through our student services office.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Is financial aid or scholarships available?
                        <svg fill="currentColor" viewBox="0 0 20 20" width="20" height="20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        Yes, we offer merit-based scholarships, need-based financial aid, and special grants for outstanding candidates. Scholarship applications are considered separately after admission offers. International students should check specific eligibility criteria.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact" aria-labelledby="contact-title">
        <div class="contact-content">
            <header class="section-header">
                <div class="section-label" style="background: rgba(255, 255, 255, 0.2); color: white;">Get In Touch</div>
                <h2 id="contact-title" class="section-title" style="color: white;">Admissions Support Center</h2>
                <p class="section-description" style="color: #cbd5e0;">
                    Our dedicated admissions team is ready to assist you throughout the application process
                </p>
            </header>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                    </div>
                    <h4 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Email Admissions</h4>
                    <p style="color: #cbd5e0; font-size: 0.875rem;">General inquiries and document submissions</p>
                    <a href="mailto:admissions@fctcns.edu.ng" class="contact-info">
                        admissions@fctcns.edu.ng
                    </a>
                    <p style="color: #a0aec0; font-size: 0.875rem; margin-top: 0.5rem;">Response time: 24-48 hours</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                    </div>
                    <h4 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Call Admissions</h4>
                    <p style="color: #cbd5e0; font-size: 0.875rem;">Direct line for urgent inquiries</p>
                    <a href="tel:+2348031234567" class="contact-info">
                        +234 803 123 4567
                    </a>
                    <p style="color: #a0aec0; font-size: 0.875rem; margin-top: 0.5rem;">Mon-Fri, 8:00 AM - 5:00 PM WAT</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Portal Support</h4>
                    <p style="color: #cbd5e0; font-size: 0.875rem;">Technical assistance and troubleshooting</p>
                    <a href="mailto:portal-support@fctcns.edu.ng" class="contact-info">
                        portal-support@fctcns.edu.ng
                    </a>
                    <p style="color: #a0aec0; font-size: 0.875rem; margin-top: 0.5rem;">24/7 technical support</p>
                </div>
            </div>
            
            <div style="margin-top: 3rem; padding: 2rem; background: rgba(255, 255, 255, 0.1); border-radius: 1rem;">
                <h4 style="font-size: 1.25rem; margin-bottom: 1rem;">Visit Our Campus</h4>
                <p style="color: #cbd5e0; margin-bottom: 1rem;">
                    Federal Secretariat Complex, Phase 1, Abuja, FCT, Nigeria
                </p>
                <p style="color: #a0aec0; font-size: 0.875rem;">
                    Campus tours available by appointment. Contact admissions to schedule your visit.
                </p>
            </div>
        </div>
    </section>
</main>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Tab functionality
    const timelineTabs = document.querySelectorAll('.timeline-tab');
    const timelinePanels = document.querySelectorAll('.timeline-content');
    
    timelineTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            const panelId = 'panel-' + tabId.split('-')[1];
            
            // Update tab states
            timelineTabs.forEach(t => {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            
            // Show selected panel
            timelinePanels.forEach(panel => {
                panel.style.display = 'none';
                panel.classList.add('hidden');
            });
            
            const targetPanel = document.getElementById(panelId);
            if (targetPanel) {
                targetPanel.style.display = 'block';
                targetPanel.classList.remove('hidden');
            }
        });
    });
    
    // FAQ toggle functionality
    window.toggleFaq = function(element) {
        const answer = element.nextElementSibling;
        const icon = element.querySelector('svg');
        
        answer.classList.toggle('active');
        
        if (answer.classList.contains('active')) {
            icon.style.transform = 'rotate(180deg)';
        } else {
            icon.style.transform = 'rotate(0deg)';
        }
    };
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                
                const headerHeight = 80; // Adjust based on your header height
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset;
                const offsetPosition = targetPosition - headerHeight - 20;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Update URL
                if (history.pushState) {
                    history.pushState(null, null, href);
                }
            }
        });
    });
    
    // Enhanced portal link confirmation
    const portalLinks = document.querySelectorAll('a[href*="apply"]');
    portalLinks.forEach(link => {
        if (link.classList.contains('cta-primary')) {
            link.addEventListener('click', function(e) {
                if (!confirm('You will be redirected to the application form. Make sure you have all required documents ready. Continue?')) {
                    e.preventDefault();
                }
            });
        }
    });
    
    // Add print button
    const printButton = document.createElement('button');
    printButton.className = 'btn';
    printButton.innerHTML = `
        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 18px; height: 18px; margin-right: 8px;">
            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
        </svg>
        Print Admissions Information
    `;
    
    printButton.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: white;
        border: 2px solid #2c5282;
        color: #2c5282;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        cursor: pointer;
        transition: all 0.3s ease;
    `;
    
    printButton.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 6px 16px rgba(0, 0, 0, 0.2)';
    });
    
    printButton.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    });
    
    printButton.addEventListener('click', function() {
        window.print();
    });
    
    document.body.appendChild(printButton);
    
    // Add scroll progress indicator
    const progressBar = document.createElement('div');
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, #38a169, #4299e1);
        z-index: 9999;
        transition: width 0.1s ease;
    `;
    
    document.body.appendChild(progressBar);
    
    window.addEventListener('scroll', function() {
        const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (window.pageYOffset / windowHeight) * 100;
        progressBar.style.width = scrolled + '%';
    });
    
    // Add keyboard navigation for tabs
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
            const tabs = Array.from(timelineTabs);
            const activeTab = tabs.findIndex(tab => tab.classList.contains('active'));
            let nextTab;
            
            if (e.key === 'ArrowRight') {
                nextTab = tabs[(activeTab + 1) % tabs.length];
            } else {
                nextTab = tabs[(activeTab - 1 + tabs.length) % tabs.length];
            }
            
            if (nextTab) {
                nextTab.click();
                nextTab.focus();
            }
        }
    });
});
</script>