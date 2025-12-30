<?php
/**
 * Programs Page View - MVC Version
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
$page_title = $page_title ?? 'Nursing Programs - FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Explore accredited nursing education programs at Federal College of Tropical Nursing Sciences';
$currentPage = $currentPage ?? 'programs';
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

/* Base Styles - FIXED: Remove top padding that creates extra space */
.programs-container {
    font-family: var(--font-body);
    line-height: 1.6;
    color: var(--color-gray-700);
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* Header Section - FIXED: Reduced padding */
.page-header {
    background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
    color: var(--color-white);
    padding: var(--spacing-2xl) 0 var(--spacing-xl) 0; /* Reduced vertical padding */
    margin-top: 0;
}

.page-header h1 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: var(--spacing-md);
    margin-top: 0;
}

.page-header .lead {
    font-size: 1.25rem;
    opacity: 0.9;
    max-width: 48rem;
    margin: 0 auto;
}

/* Program Card */
.program-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-200);
    transition: all var(--transition-base);
    overflow: hidden;
    height: 100%;
}

.program-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
    border-color: var(--color-primary-light);
}

.program-card-header {
    padding: var(--spacing-xl);
    border-bottom: 1px solid var(--color-gray-100);
    background: linear-gradient(135deg, var(--color-gray-50) 0%, var(--color-white) 100%);
}

.program-card-body {
    padding: var(--spacing-xl);
}

/* Program Badges */
.program-badge {
    display: inline-block;
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.badge-basic {
    background-color: rgba(66, 153, 225, 0.1);
    color: var(--color-primary);
}

.badge-advanced {
    background-color: rgba(56, 178, 172, 0.1);
    color: var(--color-accent);
}

.badge-specialized {
    background-color: rgba(159, 122, 234, 0.1);
    color: #9f7aea;
}

/* Accreditation Display */
.accreditation-badges {
    display: flex;
    gap: var(--spacing-sm);
    flex-wrap: wrap;
}

.accreditation-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-xs) var(--spacing-sm);
    background-color: var(--color-gray-50);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
}

.accreditation-badge.verified {
    background-color: rgba(56, 161, 105, 0.1);
    color: var(--color-success);
}

/* List Styles */
.requirement-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.requirement-list li {
    position: relative;
    padding-left: var(--spacing-lg);
    margin-bottom: var(--spacing-sm);
    color: var(--color-gray-600);
}

.requirement-list li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: var(--color-success);
    font-weight: 600;
}

/* Tab Navigation - FIXED: Adjusted spacing */
.program-tabs {
    background: var(--color-white);
    border-bottom: 2px solid var(--color-gray-200);
    padding: var(--spacing-md) 0; /* Reduced padding */
}

.tab-button {
    padding: var(--spacing-sm) var(--spacing-lg); /* Reduced padding */
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    font-family: var(--font-heading);
    font-weight: 600;
    color: var(--color-gray-600);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.tab-button:hover {
    color: var(--color-primary);
    background-color: var(--color-gray-50);
}

.tab-button.active {
    color: var(--color-primary);
    border-bottom-color: var(--color-primary);
    background-color: var(--color-gray-50);
}

/* Comparison Table */
.comparison-table-container {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-md);
}

.comparison-table {
    width: 100%;
    border-collapse: collapse;
}

.comparison-table thead {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
}

.comparison-table th {
    padding: var(--spacing-lg);
    text-align: left;
    color: var(--color-white);
    font-weight: 600;
    font-family: var(--font-heading);
}

.comparison-table td {
    padding: var(--spacing-lg);
    border-bottom: 1px solid var(--color-gray-200);
}

.comparison-table tbody tr:hover {
    background-color: var(--color-gray-50);
}

/* Call to Action */
.cta-section {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    color: var(--color-white);
    padding: var(--spacing-2xl) 0;
}

/* Utility Classes */
.text-balance {
    text-wrap: balance;
}

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

/* Section spacing - FIXED: Reduced margins */
.section {
    padding: var(--spacing-xl) 0; /* Reduced from 2xl/3xl */
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header h1 {
        font-size: 2rem;
    }
    
    .program-tabs {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: var(--spacing-sm) 0; /* Even smaller on mobile */
    }
    
    .comparison-table-container {
        overflow-x: auto;
    }
    
    .section {
        padding: var(--spacing-lg) 0; /* Smaller on mobile */
    }
}

@media print {
    .tab-button,
    .program-tabs,
    .cta-section,
    .navbar,
    .footer {
        display: none;
    }
    
    .program-card {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
    }
}

/* Additional fixes to remove extra space */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

/* Remove any default margins that create space */
h1, h2, h3, h4, h5, h6 {
    margin-top: 0;
}

/* Grid utilities */
.grid {
    display: grid;
    gap: var(--spacing-md);
}

.grid-cols-1 {
    grid-template-columns: 1fr;
}

@media (min-width: 1024px) {
    .lg\:grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Flex utilities */
.flex {
    display: flex;
}

.flex-wrap {
    flex-wrap: wrap;
}

.justify-center {
    justify-content: center;
}

.justify-between {
    justify-content: space-between;
}

.items-center {
    align-items: center;
}

.items-start {
    align-items: flex-start;
}

.space-x-1 > * + * {
    margin-left: var(--spacing-xs);
}

.gap-1 { gap: var(--spacing-xs); }
.gap-2 { gap: var(--spacing-sm); }
.gap-3 { gap: var(--spacing-md); }
.gap-4 { gap: var(--spacing-lg); }
.gap-6 { gap: var(--spacing-xl); }
.gap-8 { gap: var(--spacing-2xl); }

/* Margin utilities */
.mt-0 { margin-top: 0 !important; }
.mt-2 { margin-top: var(--spacing-sm); }
.mt-4 { margin-top: var(--spacing-md); }
.mt-6 { margin-top: var(--spacing-lg); }
.mt-8 { margin-top: var(--spacing-xl); }
.mt-10 { margin-top: var(--spacing-2xl); }
.mb-2 { margin-bottom: var(--spacing-sm); }
.mb-3 { margin-bottom: var(--spacing-md); }
.mb-4 { margin-bottom: var(--spacing-md); }
.mb-6 { margin-bottom: var(--spacing-lg); }
.mb-8 { margin-bottom: var(--spacing-xl); }
.mb-10 { margin-bottom: var(--spacing-2xl); }
.mb-12 { margin-bottom: var(--spacing-2xl); }

/* Text utilities */
.text-center { text-align: center; }
.text-lg { font-size: 1.125rem; }
.text-sm { font-size: 0.875rem; }
.text-xs { font-size: 0.75rem; }
.text-gray-600 { color: var(--color-gray-600); }
.text-gray-500 { color: var(--color-gray-500); }
.text-gray-700 { color: var(--color-gray-700); }
.text-gray-800 { color: var(--color-gray-800); }
.text-primary { color: var(--color-primary); }
.text-white { color: var(--color-white); }
.text-white\/90 { color: rgba(255, 255, 255, 0.9); }
.text-white\/80 { color: rgba(255, 255, 255, 0.8); }

/* Heading utilities */
.h2 { 
    font-size: 2rem; 
    font-weight: 700;
    font-family: var(--font-heading);
    margin-bottom: var(--spacing-md);
}
.h3 { 
    font-size: 1.5rem; 
    font-weight: 600;
    font-family: var(--font-heading);
    margin-bottom: var(--spacing-sm);
}
.h4 { 
    font-size: 1.25rem; 
    font-weight: 600;
    font-family: var(--font-heading);
    margin-bottom: var(--spacing-sm);
}

/* Button utilities */
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
}

.btn-sm {
    padding: var(--spacing-xs) var(--spacing-md);
    font-size: 0.875rem;
}

.btn-lg {
    padding: var(--spacing-md) var(--spacing-xl);
    font-size: 1.125rem;
}

.btn-primary {
    background-color: var(--color-primary);
    color: var(--color-white);
    border-color: var(--color-primary);
}

.btn-primary:hover {
    background-color: var(--color-primary-dark);
    border-color: var(--color-primary-dark);
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

.btn-light {
    background-color: var(--color-white);
    color: var(--color-primary);
    border-color: var(--color-white);
}

.btn-light:hover {
    background-color: var(--color-gray-100);
    border-color: var(--color-gray-100);
}

.btn-outline-light {
    background-color: transparent;
    color: var(--color-white);
    border-color: var(--color-white);
}

.btn-outline-light:hover {
    background-color: var(--color-white);
    color: var(--color-primary);
}

/* Alert */
.alert {
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-lg);
}

.alert-info {
    background-color: rgba(66, 153, 225, 0.1);
    border: 1px solid rgba(66, 153, 225, 0.3);
    color: var(--color-primary);
}

/* Border utilities */
.border-t {
    border-top: 1px solid var(--color-gray-200);
}

.pt-6 {
    padding-top: var(--spacing-lg);
}

/* Max width utilities */
.max-w-2xl { max-width: 42rem; }
.max-w-3xl { max-width: 48rem; }

/* Background utilities */
.bg-gray-50 {
    background-color: var(--color-gray-50);
}

/* Accessibility */
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
</style>

<!-- Main Content -->
<main id="main-content" class="programs-container" role="main" aria-label="Nursing programs information">
    <!-- Skip to Content Link -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="text-center">
                <h1 class="text-balance">Nursing Education Programs</h1>
                <p class="lead">
                    Accredited nursing programs designed to develop competent healthcare professionals 
                    meeting national and international standards.
                </p>
                <div class="mt-6">
                    <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-light btn-lg">
                        Begin Application Process
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Filter Navigation -->
    <section class="program-tabs">
        <div class="container">
            <nav role="navigation" aria-label="Program categories">
                <div class="flex justify-center space-x-1">
                    <button class="tab-button active" data-filter="all" aria-current="page">
                        All Programs
                    </button>
                    <button class="tab-button" data-filter="basic">
                        Basic Programs
                    </button>
                    <button class="tab-button" data-filter="advanced">
                        Advanced Specializations
                    </button>
                    <button class="tab-button" data-filter="specialized">
                        Specialized Programs
                    </button>
                </div>
            </nav>
        </div>
    </section>

    <!-- Programs Grid -->
    <section class="section" id="programs-grid">
        <div class="container">
            <header class="text-center mb-10">
                <h2 class="h2 mb-4">Comprehensive Nursing Curriculum</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Our programs combine theoretical foundations with extensive clinical practice, 
                    ensuring graduates are prepared for diverse healthcare settings.
                </p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- National Diploma in Nursing -->
                <article class="program-card" data-category="basic" id="national-diploma">
                    <div class="program-card-header">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="program-badge badge-basic">
                                    Basic Nursing Programs
                                </span>
                            </div>
                            <div class="text-sm font-semibold text-gray-500">
                                Code: NDN
                            </div>
                        </div>
                        
                        <h3 class="h3 mb-2">National Diploma in Nursing</h3>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Duration: 3 Years
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Intake: January, September
                            </div>
                        </div>
                    </div>
                    
                    <div class="program-card-body">
                        <div class="mb-6">
                            <h4 class="h4 mb-3">Program Overview</h4>
                            <p class="text-gray-700">A comprehensive three-year program leading to the award of National Diploma in Nursing. This program provides students with the knowledge, skills, and attitudes required for professional nursing practice.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h5 class="font-semibold text-gray-800 mb-2">Admission Requirements</h5>
                                <ul class="requirement-list">
                                    <li>Minimum of five credit passes in SSCE/GCE O'Level including English Language, Mathematics, Biology, Chemistry, and Physics</li>
                                    <li>Credit passes obtained in not more than two sittings</li>
                                    <li>Minimum age of 17 years at time of application</li>
                                    <li>Satisfactory performance in entrance examination and interview</li>
                                    <li>Medical fitness certificate from recognized healthcare facility</li>
                                    <li>Character reference letter from reputable individual</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h5 class="font-semibold text-gray-800 mb-2">Career Opportunities</h5>
                                <ul class="requirement-list">
                                    <li>Registered Nurse in hospitals and clinical settings</li>
                                    <li>Nursing Officer in government healthcare institutions</li>
                                    <li>School Nurse in educational institutions</li>
                                    <li>Industrial Nurse in corporate organizations</li>
                                    <li>Foundation for Bachelor of Nursing Science degree</li>
                                    <li>Healthcare administrator or manager roles</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-2">Accreditation Status</h5>
                                    <div class="accreditation-badges">
                                        <div class="accreditation-badge verified">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            NMCN
                                        </div>
                                        <div class="accreditation-badge verified">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            NBTE
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex gap-3">
                                    <a href="#program-comparison" class="btn btn-outline btn-sm">
                                        Compare
                                    </a>
                                    <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-primary btn-sm">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Basic Nursing Program -->
                <article class="program-card" data-category="basic" id="basic-nursing">
                    <div class="program-card-header">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="program-badge badge-basic">
                                    Basic Nursing Programs
                                </span>
                            </div>
                            <div class="text-sm font-semibold text-gray-500">
                                Code: BNP
                            </div>
                        </div>
                        
                        <h3 class="h3 mb-2">Basic Nursing Program</h3>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Duration: 3 Years
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Intake: January, September
                            </div>
                        </div>
                    </div>
                    
                    <div class="program-card-body">
                        <div class="mb-6">
                            <h4 class="h4 mb-3">Program Overview</h4>
                            <p class="text-gray-700">The Basic Nursing program provides fundamental nursing education and training for individuals beginning their career in nursing, emphasizing patient care and clinical competence.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h5 class="font-semibold text-gray-800 mb-2">Admission Requirements</h5>
                                <ul class="requirement-list">
                                    <li>Five credit passes in WAEC/NECO including English, Mathematics, Biology, Chemistry, and Physics</li>
                                    <li>Credits obtained in not more than two examination sittings</li>
                                    <li>Age between 17 and 35 years at time of application</li>
                                    <li>Medical fitness certificate from recognized healthcare facility</li>
                                    <li>Letter of recommendation from reputable individual</li>
                                    <li>Birth certificate or declaration of age document</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h5 class="font-semibold text-gray-800 mb-2">Career Opportunities</h5>
                                <ul class="requirement-list">
                                    <li>Staff Nurse in various healthcare facilities</li>
                                    <li>Nurse Educator in training institutions</li>
                                    <li>Public Health Nurse in community settings</li>
                                    <li>Clinical Nurse Specialist in specialized units</li>
                                    <li>Nursing Administrator in healthcare management</li>
                                    <li>Research Assistant in medical research institutions</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-2">Accreditation Status</h5>
                                    <div class="accreditation-badges">
                                        <div class="accreditation-badge verified">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            NMCN
                                        </div>
                                        <div class="accreditation-badge verified">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            NBTE
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex gap-3">
                                    <a href="#program-comparison" class="btn btn-outline btn-sm">
                                        Compare
                                    </a>
                                    <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-primary btn-sm">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Basic Midwifery Program -->
                <article class="program-card" data-category="specialized" id="basic-midwifery">
                    <div class="program-card-header">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="program-badge badge-specialized">
                                    Specialized Programs
                                </span>
                            </div>
                            <div class="text-sm font-semibold text-gray-500">
                                Code: BMP
                            </div>
                        </div>
                        
                        <h3 class="h3 mb-2">Basic Midwifery Program</h3>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Duration: 3 Years
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Intake: January, September
                            </div>
                        </div>
                    </div>
                    
                    <div class="program-card-body">
                        <div class="mb-6">
                            <h4 class="h4 mb-3">Program Overview</h4>
                            <p class="text-gray-700">Specialized training program focusing on maternal and child healthcare, antenatal care, delivery procedures, postnatal care, and family planning services.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h5 class="font-semibold text-gray-800 mb-2">Admission Requirements</h5>
                                <ul class="requirement-list">
                                    <li>Five credit passes including English, Mathematics, Biology, Chemistry, and Physics</li>
                                    <li>Female candidates only, in accordance with program regulations</li>
                                    <li>Minimum age of 17 years at time of application</li>
                                    <li>Good moral character with reference letter</li>
                                    <li>Physical and mental fitness for midwifery practice</li>
                                    <li>Demonstrated interest in maternal and child healthcare</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h5 class="font-semibold text-gray-800 mb-2">Career Opportunities</h5>
                                <ul class="requirement-list">
                                    <li>Registered Midwife in maternity centers and hospitals</li>
                                    <li>Maternity Ward Nurse in healthcare facilities</li>
                                    <li>Reproductive Health Nurse in clinical settings</li>
                                    <li>Family Planning Counselor in health centers</li>
                                    <li>Maternal and Child Health Coordinator</li>
                                    <li>Neonatal Care Specialist</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-2">Accreditation Status</h5>
                                    <div class="accreditation-badges">
                                        <div class="accreditation-badge verified">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            NMCN
                                        </div>
                                        <div class="accreditation-badge verified">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            NBTE
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex gap-3">
                                    <a href="#program-comparison" class="btn btn-outline btn-sm">
                                        Compare
                                    </a>
                                    <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-primary btn-sm">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Post Basic Nursing Specialization -->
                <article class="program-card" data-category="advanced" id="post-basic">
                    <div class="program-card-header">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="program-badge badge-advanced">
                                    Advanced Specializations
                                </span>
                            </div>
                            <div class="text-sm font-semibold text-gray-500">
                                Code: PBNS
                            </div>
                        </div>
                        
                        <h3 class="h3 mb-2">Post Basic Nursing Specialization</h3>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Duration: 18 Months
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Intake: March, October
                            </div>
                        </div>
                    </div>
                    
                    <div class="program-card-body">
                        <div class="mb-6">
                            <h4 class="h4 mb-3">Program Overview</h4>
                            <p class="text-gray-700">Advanced program designed for registered nurses seeking specialization in intensive care, pediatric nursing, psychiatric nursing, or perioperative nursing.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h5 class="font-semibold text-gray-800 mb-2">Admission Requirements</h5>
                                <ul class="requirement-list">
                                    <li>Registered Nurse (RN) with current practicing license</li>
                                    <li>Minimum of one year post-registration clinical experience</li>
                                    <li>Five O'Level credits including English and Science subjects</li>
                                    <li>Professional recommendation from current employer</li>
                                    <li>Successful performance in selection interview</li>
                                    <li>Proof of registration with Nursing and Midwifery Council of Nigeria</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h5 class="font-semibold text-gray-800 mb-2">Career Opportunities</h5>
                                <ul class="requirement-list">
                                    <li>Specialist Nurse in chosen clinical field</li>
                                    <li>Nursing Unit Manager in hospital departments</li>
                                    <li>Clinical Instructor in nursing education institutions</li>
                                    <li>Nurse Consultant in specialized healthcare areas</li>
                                    <li>Advanced Practice Nurse with extended clinical roles</li>
                                    <li>Nursing Supervisor in healthcare facilities</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-2">Accreditation Status</h5>
                                    <div class="accreditation-badges">
                                        <div class="accreditation-badge verified">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            NMCN
                                        </div>
                                        <div class="accreditation-badge verified">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            NBTE
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex gap-3">
                                    <a href="#program-comparison" class="btn btn-outline btn-sm">
                                        Compare
                                    </a>
                                    <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-primary btn-sm">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Program Comparison -->
    <section class="section bg-gray-50" id="program-comparison">
        <div class="container">
            <header class="text-center mb-10">
                <h2 class="h2 mb-4">Program Comparison</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Detailed comparison of our nursing programs to assist in your decision-making process
                </p>
            </header>
            
            <div class="comparison-table-container">
                <table class="comparison-table" aria-label="Detailed program comparison">
                    <thead>
                        <tr>
                            <th scope="col">Program Name</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Entry Level</th>
                            <th scope="col">Qualification Awarded</th>
                            <th scope="col">Accreditation</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>National Diploma in Nursing</strong>
                                <div class="text-sm text-gray-500">NDN</div>
                            </td>
                            <td>3 Years</td>
                            <td>Secondary School</td>
                            <td>Diploma/Certificate</td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <span class="text-xs px-2 py-1 bg-gray-100 rounded">NMCN</span>
                                    <span class="text-xs px-2 py-1 bg-gray-100 rounded">NBTE</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="#national-diploma" class="text-primary hover:underline">
                                        View Details
                                    </a>
                                    <a href="<?php echo $baseUrl; ?>/admissions?program=national-diploma" 
                                       class="text-primary hover:underline">
                                        Apply
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Basic Nursing Program</strong>
                                <div class="text-sm text-gray-500">BNP</div>
                            </td>
                            <td>3 Years</td>
                            <td>Secondary School</td>
                            <td>Diploma/Certificate</td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <span class="text-xs px-2 py-1 bg-gray-100 rounded">NMCN</span>
                                    <span class="text-xs px-2 py-1 bg-gray-100 rounded">NBTE</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="#basic-nursing" class="text-primary hover:underline">
                                        View Details
                                    </a>
                                    <a href="<?php echo $baseUrl; ?>/admissions?program=basic-nursing" 
                                       class="text-primary hover:underline">
                                        Apply
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Basic Midwifery Program</strong>
                                <div class="text-sm text-gray-500">BMP</div>
                            </td>
                            <td>3 Years</td>
                            <td>Direct Entry</td>
                            <td>Professional Certificate</td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <span class="text-xs px-2 py-1 bg-gray-100 rounded">NMCN</span>
                                    <span class="text-xs px-2 py-1 bg-gray-100 rounded">NBTE</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="#basic-midwifery" class="text-primary hover:underline">
                                        View Details
                                    </a>
                                    <a href="<?php echo $baseUrl; ?>/admissions?program=basic-midwifery" 
                                       class="text-primary hover:underline">
                                        Apply
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Post Basic Nursing Specialization</strong>
                                <div class="text-sm text-gray-500">PBNS</div>
                            </td>
                            <td>18 Months</td>
                            <td>Registered Nurse</td>
                            <td>Specialist Certificate</td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <span class="text-xs px-2 py-1 bg-gray-100 rounded">NMCN</span>
                                    <span class="text-xs px-2 py-1 bg-gray-100 rounded">NBTE</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="#post-basic" class="text-primary hover:underline">
                                        View Details
                                    </a>
                                    <a href="<?php echo $baseUrl; ?>/admissions?program=post-basic" 
                                       class="text-primary hover:underline">
                                        Apply
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <div class="text-center">
                <h2 class="h2 text-white mb-6">Begin Your Healthcare Career</h2>
                <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Join our community of healthcare professionals and make a meaningful difference 
                    in patient care and community health.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-light btn-lg">
                        Start Application Process
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" class="btn btn-outline-light btn-lg">
                        Schedule Consultation
                    </a>
                </div>
                <div class="mt-8 text-white/80">
                    <p class="text-sm">
                        Need assistance? Contact our admissions office at 
                        <a href="mailto:admissions@fctcns.edu.ng" class="underline">admissions@fctcns.edu.ng</a>
                        or call <a href="tel:+234XXXXXXXXXX" class="underline">+234 XXX XXX XXXX</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Professional JavaScript Implementation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Tab Filtering System
    const tabButtons = document.querySelectorAll('.tab-button');
    const programCards = document.querySelectorAll('.program-card');
    
    if (tabButtons.length > 0 && programCards.length > 0) {
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active state
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.removeAttribute('aria-current');
                });
                
                this.classList.add('active');
                this.setAttribute('aria-current', 'page');
                
                // Filter programs
                const filter = this.dataset.filter;
                filterPrograms(filter);
            });
        });
    }
    
    /**
     * Filter programs by category
     */
    function filterPrograms(category) {
        let visibleCount = 0;
        
        programCards.forEach(card => {
            const cardCategory = card.dataset.category;
            const shouldShow = category === 'all' || cardCategory === category;
            
            if (shouldShow) {
                card.style.display = 'block';
                visibleCount++;
                
                // Add fade-in animation
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
        
        // Update screen reader status
        updateFilterStatus(category, visibleCount);
    }
    
    /**
     * Update accessibility status message
     */
    function updateFilterStatus(category, count) {
        const statusElement = document.getElementById('filter-status') || createStatusElement();
        const categoryName = getCategoryName(category);
        
        statusElement.textContent = `${count} ${categoryName} program${count !== 1 ? 's' : ''} displayed.`;
        
        // Announce to screen readers
        announceToScreenReader(statusElement.textContent);
    }
    
    /**
     * Get readable category name
     */
    function getCategoryName(category) {
        const names = {
            'all': '',
            'basic': 'basic nursing',
            'advanced': 'advanced specialization',
            'specialized': 'specialized'
        };
        return names[category] || category;
    }
    
    /**
     * Create status element for screen readers
     */
    function createStatusElement() {
        const statusElement = document.createElement('div');
        statusElement.id = 'filter-status';
        statusElement.className = 'visually-hidden';
        statusElement.setAttribute('role', 'status');
        statusElement.setAttribute('aria-live', 'polite');
        document.querySelector('#programs-grid').prepend(statusElement);
        return statusElement;
    }
    
    /**
     * Announce message to screen readers
     */
    function announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('role', 'status');
        announcement.setAttribute('aria-live', 'polite');
        announcement.className = 'visually-hidden';
        announcement.textContent = message;
        
        document.body.appendChild(announcement);
        
        setTimeout(() => {
            document.body.removeChild(announcement);
        }, 1000);
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
                
                // Update URL without page reload
                if (history.pushState) {
                    history.pushState(null, null, href);
                }
                
                // Focus management for accessibility
                target.setAttribute('tabindex', '-1');
                target.focus({ preventScroll: true });
            }
        });
    });
    
    // Keyboard navigation for tabs
    document.addEventListener('keydown', function(e) {
        const activeTab = document.querySelector('.tab-button.active');
        if (!activeTab) return;
        
        const tabs = Array.from(tabButtons);
        let currentIndex = tabs.indexOf(activeTab);
        
        switch(e.key) {
            case 'ArrowRight':
            case 'ArrowDown':
                e.preventDefault();
                currentIndex = (currentIndex + 1) % tabs.length;
                tabs[currentIndex].click();
                tabs[currentIndex].focus();
                break;
                
            case 'ArrowLeft':
            case 'ArrowUp':
                e.preventDefault();
                currentIndex = (currentIndex - 1 + tabs.length) % tabs.length;
                tabs[currentIndex].click();
                tabs[currentIndex].focus();
                break;
                
            case 'Home':
                e.preventDefault();
                tabs[0].click();
                tabs[0].focus();
                break;
                
            case 'End':
                e.preventDefault();
                tabs[tabs.length - 1].click();
                tabs[tabs.length - 1].focus();
                break;
        }
    });
});
</script>