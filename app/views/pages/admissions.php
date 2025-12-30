<?php
/**
 * Admissions Page View - MVC Version
 * 
 * Pure MVC view - only displays data passed from PageController.
 * NO header/footer includes, NO PHP logic.
 * 
 * Available variables from PageController:
 * - $baseUrl: Base URL for assets
 * - $page_title, $page_description, $currentPage, etc.
 * 
 * @package FCTCNS
 * @version 2.0
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

// Admission data (could be moved to controller later)
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
/* Professional Design System */
:root {
    /* Color System */
    --color-primary: #2c5282;
    --color-primary-dark: #1a365d;
    --color-primary-light: #4299e1;
    --color-secondary: #4a5568;
    --color-accent: #38b2ac;
    --color-success: #38a169;
    --color-warning: #d69e2e;
    --color-danger: #e53e3e;
    --color-info: #3182ce;
    
    /* Neutral Colors */
    --color-white: #ffffff;
    --color-gray-50: #f7fafc;
    --color-gray-100: #edf2f7;
    --color-gray-200: #e2e8f0;
    --color-gray-300: #cbd5e0;
    --color-gray-400: #a0aec0;
    --color-gray-500: #718096;
    --color-gray-600: #4a5568;
    --color-gray-700: #2d3748;
    --color-gray-800: #1a202c;
    --color-gray-900: #171923;
    
    /* Typography */
    --font-heading: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --font-monospace: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', monospace;
    
    /* Spacing */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-2xl: 3rem;
    --spacing-3xl: 4rem;
    
    /* Border Radius */
    --radius-sm: 0.25rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    
    /* Transitions */
    --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);
}

/* Base Styles - FIXED: Remove top padding */
.admissions-container {
    font-family: var(--font-body);
    line-height: 1.6;
    color: var(--color-gray-700);
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* Hero Header - FIXED: Reduced padding */
.hero-header {
    background: linear-gradient(135deg, 
        rgba(44, 82, 130, 0.95) 0%, 
        rgba(26, 54, 93, 0.9) 100%);
    color: var(--color-white);
    padding: var(--spacing-2xl) 0 var(--spacing-xl) 0; /* Reduced */
    margin-top: 0;
    position: relative;
    overflow: hidden;
}

.hero-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 48rem;
    margin: 0 auto;
    text-align: center;
}

.hero-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 3rem;
    line-height: 1.2;
    margin-bottom: var(--spacing-lg);
    margin-top: 0;
}

.hero-subtitle {
    font-size: 1.25rem;
    opacity: 0.95;
    line-height: 1.6;
    margin-bottom: var(--spacing-xl);
}

/* Primary CTA Button */
.cta-primary {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    background: linear-gradient(135deg, var(--color-success), #2f855a);
    color: var(--color-white);
    padding: var(--spacing-lg) var(--spacing-2xl);
    border-radius: var(--radius-lg);
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.125rem;
    text-decoration: none;
    box-shadow: var(--shadow-lg);
    transition: all var(--transition-base);
    border: none;
    cursor: pointer;
}

.cta-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
    background: linear-gradient(135deg, #2f855a, #276749);
    text-decoration: none;
}

.cta-primary:focus {
    outline: 2px solid var(--color-white);
    outline-offset: 2px;
}

.cta-primary svg {
    width: 20px;
    height: 20px;
}

/* Breadcrumb */
.breadcrumb {
    background-color: var(--color-gray-50);
    padding: var(--spacing-md) 0;
    border-bottom: 1px solid var(--color-gray-200);
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: 0.875rem;
}

.breadcrumb-nav a {
    color: var(--color-gray-600);
    text-decoration: none;
    transition: color var(--transition-fast);
}

.breadcrumb-nav a:hover {
    color: var(--color-primary);
    text-decoration: underline;
}

.breadcrumb-separator {
    color: var(--color-gray-400);
}

.breadcrumb-current {
    color: var(--color-primary);
    font-weight: 600;
}

/* Section Styles - FIXED: Reduced padding */
.content-section {
    padding: var(--spacing-xl) 0; /* Reduced from 3xl */
}

.section-header {
    text-align: center;
    margin-bottom: var(--spacing-xl); /* Reduced */
}

.section-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2.5rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-md);
}

.section-description {
    font-size: 1.125rem;
    color: var(--color-gray-600);
    max-width: 48rem;
    margin: 0 auto;
    line-height: 1.6;
}

/* Application Portal Section */
.portal-section {
    background: linear-gradient(135deg, 
        rgba(56, 161, 105, 0.08) 0%, 
        rgba(47, 133, 90, 0.08) 100%);
}

.portal-card {
    background-color: var(--color-white);
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl); /* Reduced */
    box-shadow: var(--shadow-lg);
    max-width: 56rem;
    margin: 0 auto;
}

.portal-header {
    text-align: center;
    margin-bottom: var(--spacing-lg); /* Reduced */
}

.portal-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--color-success), #2f855a);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md); /* Reduced */
    color: var(--color-white);
}

.portal-icon svg {
    width: 40px;
    height: 40px;
}

.portal-card h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.875rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-md);
}

.portal-card p {
    color: var(--color-gray-600);
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
}

.portal-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin: var(--spacing-lg) 0; /* Reduced */
}

.portal-feature {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-md);
}

.portal-feature svg {
    width: 24px;
    height: 24px;
    color: var(--color-success);
    flex-shrink: 0;
    margin-top: 2px;
}

.portal-feature h4 {
    font-weight: 600;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-xs);
}

.portal-feature p {
    color: var(--color-gray-600);
    font-size: 0.875rem;
    margin: 0;
}

.portal-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg); /* Reduced */
}

.portal-url {
    background-color: var(--color-gray-50);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    border: 1px solid var(--color-gray-200);
    font-family: var(--font-monospace);
    font-size: 0.875rem;
    color: var(--color-gray-700);
    word-break: break-all;
    width: 100%;
    max-width: 32rem;
    text-align: center;
}

/* Requirements Section */
.requirements-section {
    background-color: var(--color-white);
}

.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
    margin-top: var(--spacing-lg); /* Reduced */
}

.requirement-card {
    background-color: var(--color-gray-50);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    border-left: 4px solid var(--color-primary);
}

.requirement-card h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.5rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-lg);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.requirement-card h3 svg {
    width: 24px;
    height: 24px;
    color: var(--color-primary);
}

.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.requirements-list li {
    padding: var(--spacing-sm) 0;
    color: var(--color-gray-700);
    position: relative;
    padding-left: var(--spacing-lg);
}

.requirements-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: var(--spacing-md);
    width: 8px;
    height: 8px;
    background-color: var(--color-success);
    border-radius: 50%;
}

/* Program Requirements */
.program-requirements {
    margin-top: var(--spacing-xl); /* Reduced */
}

.program-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-lg);
}

.program-card {
    background-color: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg); /* Reduced */
    border: 1px solid var(--color-gray-200);
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-base);
}

.program-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--color-primary-light);
}

.program-card h4 {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: var(--spacing-md);
    font-size: 1.125rem;
}

.program-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.program-card li {
    padding: var(--spacing-xs) 0;
    color: var(--color-gray-600);
    font-size: 0.875rem;
    position: relative;
    padding-left: var(--spacing-md);
}

.program-card li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--color-primary-light);
}

/* Admission Timeline */
.timeline-section {
    background: linear-gradient(135deg, 
        rgba(44, 82, 130, 0.05) 0%, 
        rgba(26, 54, 93, 0.05) 100%);
}

.timeline-container {
    max-width: 64rem;
    margin: 0 auto;
}

.timeline-tabs {
    display: flex;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-lg); /* Reduced */
    flex-wrap: wrap;
    justify-content: center;
}

.timeline-tab {
    padding: var(--spacing-md) var(--spacing-xl);
    background-color: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--radius-lg);
    font-family: var(--font-heading);
    font-weight: 600;
    color: var(--color-gray-600);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.timeline-tab:hover {
    background-color: var(--color-gray-50);
    border-color: var(--color-gray-300);
}

.timeline-tab.active {
    background-color: var(--color-primary);
    color: var(--color-white);
    border-color: var(--color-primary);
}

.timeline-content {
    background-color: var(--color-white);
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl); /* Reduced */
    box-shadow: var(--shadow-md);
}

.timeline-period {
    text-align: center;
    margin-bottom: var(--spacing-lg); /* Reduced */
}

.timeline-period h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.5rem;
    color: var(--color-primary);
    margin-bottom: var(--spacing-xs);
}

.timeline-period p {
    color: var(--color-gray-600);
    font-size: 0.875rem;
}

.timeline-events {
    position: relative;
}

.timeline-events::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: var(--color-gray-200);
}

.timeline-event {
    display: flex;
    margin-bottom: var(--spacing-lg); /* Reduced */
    position: relative;
}

.timeline-marker {
    width: 60px;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}

.timeline-dot {
    width: 16px;
    height: 16px;
    background-color: var(--color-primary);
    border-radius: 50%;
    border: 3px solid var(--color-white);
    box-shadow: 0 0 0 3px var(--color-primary-light);
}

.timeline-content-box {
    flex: 1;
    padding-left: var(--spacing-lg);
}

.timeline-date {
    font-family: var(--font-heading);
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--spacing-xs);
    font-size: 1.125rem;
}

.timeline-activity {
    color: var(--color-gray-700);
    line-height: 1.5;
}

/* Process Steps */
.process-section {
    background-color: var(--color-white);
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
    margin-top: var(--spacing-lg); /* Reduced */
}

.process-step {
    position: relative;
    padding: var(--spacing-lg); /* Reduced */
    background-color: var(--color-gray-50);
    border-radius: var(--radius-lg);
    text-align: center;
    transition: all var(--transition-base);
}

.process-step:hover {
    transform: translateY(-4px);
    background-color: var(--color-white);
    box-shadow: var(--shadow-lg);
}

.step-number {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light));
    color: var(--color-white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.25rem;
    margin: 0 auto var(--spacing-md); /* Reduced */
}

.process-step h4 {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-md);
    font-size: 1.25rem;
}

.process-step p {
    color: var(--color-gray-600);
    line-height: 1.6;
    font-size: 0.875rem;
}

/* Contact & Support */
.support-section {
    background: linear-gradient(135deg, 
        rgba(44, 82, 130, 0.08) 0%, 
        rgba(26, 54, 93, 0.08) 100%);
}

.support-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
    margin-top: var(--spacing-lg); /* Reduced */
}

.support-card {
    background-color: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg); /* Reduced */
    text-align: center;
    border: 1px solid var(--color-gray-200);
    transition: all var(--transition-base);
}

.support-card:hover {
    transform: translateY(-4px);
    border-color: var(--color-primary-light);
    box-shadow: var(--shadow-md);
}

.support-icon {
    width: 64px;
    height: 64px;
    background-color: var(--color-gray-100);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md); /* Reduced */
    color: var(--color-primary);
}

.support-icon svg {
    width: 32px;
    height: 32px;
}

.support-card h4 {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-sm);
    font-size: 1.125rem;
}

.support-card p {
    color: var(--color-gray-600);
    font-size: 0.875rem;
    margin-bottom: var(--spacing-md);
}

.support-contact {
    font-family: var(--font-heading);
    font-weight: 600;
    color: var(--color-primary);
    text-decoration: none;
    transition: color var(--transition-fast);
}

.support-contact:hover {
    color: var(--color-primary-dark);
    text-decoration: underline;
}

/* Container utility */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .timeline-events::before {
        left: 20px;
    }
    
    .timeline-marker {
        width: 40px;
    }
    
    .content-section {
        padding: var(--spacing-lg) 0; /* Smaller on tablet */
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
    }
    
    .content-section {
        padding: var(--spacing-md) 0; /* Smaller on mobile */
    }
    
    .portal-features {
        grid-template-columns: 1fr;
    }
    
    .requirements-grid {
        grid-template-columns: 1fr;
    }
    
    .program-grid {
        grid-template-columns: 1fr;
    }
    
    .process-steps {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg); /* Reduced gap on mobile */
    }
    
    .support-grid {
        grid-template-columns: 1fr;
    }
    
    .timeline-tabs {
        flex-direction: column;
    }
    
    .timeline-tab {
        text-align: center;
    }
    
    .portal-card {
        padding: var(--spacing-lg);
    }
    
    .cta-primary {
        padding: var(--spacing-md) var(--spacing-xl);
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 1.75rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .portal-card {
        padding: var(--spacing-md);
    }
    
    .portal-icon {
        width: 60px;
        height: 60px;
    }
    
    .portal-icon svg {
        width: 30px;
        height: 30px;
    }
}

/* Accessibility */
.visually-hidden {
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

.focus-visible:focus {
    outline: 2px solid var(--color-primary);
    outline-offset: 2px;
}

/* Skip to Main Content */
.skip-to-content {
    position: absolute;
    top: -40px;
    left: 0;
    background: var(--color-primary);
    color: white;
    padding: 8px;
    z-index: 1001;
    text-decoration: none;
}

.skip-to-content:focus {
    top: 0;
}

/* Hidden utility class */
.hidden {
    display: none !important;
}

/* Button utility for print button */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: var(--radius-md);
    font-weight: 600;
    text-decoration: none;
    transition: all var(--transition-base);
    border: 2px solid transparent;
    cursor: pointer;
    font-family: var(--font-heading);
    font-size: 0.875rem;
}

.btn-outline {
    background-color: transparent;
    color: var(--color-primary);
    border-color: var(--color-primary);
}

.btn-outline:hover {
    background-color: var(--color-primary);
    color: var(--color-white);
}

/* Print Styles */
@media print {
    .hero-header,
    .breadcrumb,
    .cta-primary,
    .support-section,
    .btn,
    .skip-to-content {
        display: none;
    }
    
    .content-section {
        padding: 0.5rem 0;
    }
    
    .portal-url {
        border: 1px solid var(--color-gray-400);
        background-color: var(--color-white);
    }
    
    .process-step {
        page-break-inside: avoid;
        border: 1px solid var(--color-gray-300);
        box-shadow: none;
    }
}
</style>

<!-- Main Content -->
<main id="main-content" class="admissions-container" role="main" aria-label="Admissions information">
    <!-- Skip to Content Link -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>

    <!-- Hero Header -->
    <header class="hero-header" role="banner">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Begin Your Nursing Journey</h1>
                <p class="hero-subtitle">
                    Join FCT College of Nursing Sciences and become part of the next 
                    generation of healthcare professionals making a difference in communities.
                </p>
                <a href="#application-portal" class="cta-primary">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Apply Now via Official Portal
                </a>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <div class="breadcrumb-nav">
                <a href="<?php echo $baseUrl; ?>/">Home</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <span class="breadcrumb-current" aria-current="page">Admissions</span>
            </div>
        </div>
    </nav>

    <!-- Application Portal Section -->
    <section class="content-section portal-section" id="application-portal">
        <div class="container">
            <div class="portal-card">
                <div class="portal-header">
                    <div class="portal-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3>Official Application Portal</h3>
                    <p>
                        All applications to FCT College of Nursing Sciences must be submitted 
                        through our official online application portal. The portal provides a secure, 
                        streamlined application process with real-time status updates.
                    </p>
                </div>
                
                <div class="portal-features">
                    <div class="portal-feature">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4>Secure Submission</h4>
                            <p>256-bit SSL encryption for safe document upload and data protection</p>
                        </div>
                    </div>
                    
                    <div class="portal-feature">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4>Real-time Tracking</h4>
                            <p>Monitor your application status from submission to admission decision</p>
                        </div>
                    </div>
                    
                    <div class="portal-feature">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4>Document Upload</h4>
                            <p>Upload required documents directly through the portal interface</p>
                        </div>
                    </div>
                    
                    <div class="portal-feature">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4>Technical Support</h4>
                            <p>24/7 technical assistance for portal-related issues and inquiries</p>
                        </div>
                    </div>
                </div>
                
                <div class="portal-actions">
                    <div class="portal-url">
                        <?php echo e($applicationPortalUrl); ?>
                    </div>
                    
                    <a href="<?php echo e($applicationPortalUrl); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="cta-primary"
                       id="portal-link">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Access Application Portal
                    </a>
                    
                    <p style="color: var(--color-gray-600); font-size: 0.875rem; text-align: center;">
                        <strong>Note:</strong> Applications are only accepted through this official portal. 
                        Beware of unauthorized third-party websites.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Admission Requirements -->
    <section class="content-section requirements-section" id="requirements">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Admission Requirements</h2>
                <p class="section-description">
                    General and program-specific requirements for admission consideration
                </p>
            </header>
            
            <div class="requirements-grid">
                <article class="requirement-card">
                    <h3>
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        General Requirements
                    </h3>
                    <ul class="requirements-list">
                        <?php foreach ($generalRequirements as $requirement): ?>
                        <li><?php echo e($requirement); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </article>
                
                <article class="requirement-card">
                    <h3>
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                        </svg>
                        Additional Documents
                    </h3>
                    <ul class="requirements-list">
                        <li>Completed application form from portal</li>
                        <li>Payment receipt for application fee</li>
                        <li>Local Government Certificate of Origin</li>
                        <li>JAMB Result Slip (where applicable)</li>
                        <li>Post-UTME Screening Result (where applicable)</li>
                        <li>Evidence of name change (if applicable)</li>
                    </ul>
                </article>
            </div>
            
            <div class="program-requirements">
                <h3 style="font-family: var(--font-heading); font-weight: 700; color: var(--color-gray-800); margin-bottom: var(--spacing-lg); text-align: center;">
                    Program-Specific Requirements
                </h3>
                
                <div class="program-grid">
                    <?php foreach ($programSpecificRequirements as $program => $requirements): ?>
                    <div class="program-card">
                        <h4><?php echo e($program); ?></h4>
                        <ul>
                            <?php foreach ($requirements as $requirement): ?>
                            <li><?php echo e($requirement); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Admission Timeline -->
    <section class="content-section timeline-section" id="timeline">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Admission Timeline</h2>
                <p class="section-description">
                    Important dates and deadlines for the academic year <?php echo e($currentYear); ?>-<?php echo e($nextYear); ?>
                </p>
            </header>
            
            <div class="timeline-container">
                <div class="timeline-tabs" role="tablist" aria-label="Admission intake periods">
                    <?php foreach ($admissionTimeline as $index => $timeline): ?>
                    <button class="timeline-tab <?php echo $index === 0 ? 'active' : ''; ?>" 
                            data-tab="timeline-<?php echo $index + 1; ?>"
                            role="tab"
                            aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                            aria-controls="panel-<?php echo $index + 1; ?>">
                        <?php echo e($timeline['period']); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                
                <?php foreach ($admissionTimeline as $index => $timeline): ?>
                <div class="timeline-content <?php echo $index === 0 ? '' : 'hidden'; ?>" 
                     id="panel-<?php echo $index + 1; ?>"
                     role="tabpanel"
                     aria-labelledby="tab-<?php echo $index + 1; ?>">
                    <div class="timeline-period">
                        <h3><?php echo e($timeline['period']); ?></h3>
                        <p>Important activities and deadlines</p>
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
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Admission Process -->
    <section class="content-section process-section" id="process">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Admission Process</h2>
                <p class="section-description">
                    Step-by-step guide from application to registration
                </p>
            </header>
            
            <div class="process-steps">
                <?php foreach ($admissionProcess as $step): ?>
                <article class="process-step">
                    <div class="step-number"><?php echo e($step['step']); ?></div>
                    <h4><?php echo e($step['title']); ?></h4>
                    <p><?php echo e($step['description']); ?></p>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact & Support -->
    <section class="content-section support-section" id="support">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Admissions Support</h2>
                <p class="section-description">
                    Contact our admissions office for assistance with the application process
                </p>
            </header>
            
            <div class="support-grid">
                <div class="support-card">
                    <div class="support-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                    </div>
                    <h4>Email Support</h4>
                    <p>For inquiries and document submission</p>
                    <a href="mailto:admissions@fctcns.edu.ng" class="support-contact">
                        admissions@fctcns.edu.ng
                    </a>
                </div>
                
                <div class="support-card">
                    <div class="support-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                    </div>
                    <h4>Phone Support</h4>
                    <p>Monday - Friday, 8:00 AM - 4:00 PM</p>
                    <a href="tel:+2348031234567" class="support-contact">
                        +234 803 123 4567
                    </a>
                </div>
                
                <div class="support-card">
                    <div class="support-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4>Portal Assistance</h4>
                    <p>Technical support for application portal</p>
                    <a href="mailto:portal-support@fctcns.edu.ng" class="support-contact">
                        portal-support@fctcns.edu.ng
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- JavaScript Enhancements -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Tab functionality for timeline
    const timelineTabs = document.querySelectorAll('.timeline-tab');
    const timelinePanels = document.querySelectorAll('.timeline-content');
    
    timelineTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Update tab states
            timelineTabs.forEach(t => {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            
            // Show selected panel
            timelinePanels.forEach(panel => {
                panel.classList.add('hidden');
            });
            
            const targetPanel = document.getElementById('panel-' + tabId.split('-')[1]);
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
            }
        });
    });
    
    // External link confirmation
    const portalLink = document.getElementById('portal-link');
    if (portalLink) {
        portalLink.addEventListener('click', function(e) {
            if (!confirm('You are being redirected to the official application portal. Continue?')) {
                e.preventDefault();
            }
        });
    }
    
    // Print page functionality
    const printButton = document.createElement('button');
    printButton.className = 'btn btn-outline';
    printButton.innerHTML = `
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="margin-right: 8px;">
            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
        </svg>
        Print This Page
    `;
    
    printButton.addEventListener('click', function() {
        window.print();
    });
    
    // Add print button to portal actions
    const portalActions = document.querySelector('.portal-actions');
    if (portalActions) {
        portalActions.appendChild(printButton);
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                
                const headerHeight = document.querySelector('.navbar')?.offsetHeight || 80;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset;
                const offsetPosition = targetPosition - headerHeight - 20;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Update URL without reload
                if (history.pushState) {
                    history.pushState(null, null, href);
                }
                
                // Accessibility focus management
                target.setAttribute('tabindex', '-1');
                target.focus({ preventScroll: true });
            }
        });
    });
});
</script>