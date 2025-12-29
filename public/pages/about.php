<?php
/**
 * About Page
 * 
 * Institutional overview, history, mission, vision, values, and leadership.
 * 
 * @package FCTCNS
 * @version 2.1
 */

// Security and initialization
define('PAGE_TITLE', 'About Federal College of Tropical Nursing Sciences');
define('PAGE_DESCRIPTION', 'Learn about our history, mission, vision, values, and commitment to excellence in nursing education and healthcare training.');
define('PAGE_KEYWORDS', 'about nursing college, nursing education history, mission vision values, nursing leadership, accreditation');

// Include header
require_once __DIR__ . '/../includes/header.php';

// Get base URL from header.php
$baseUrl = '/fctcns-website/public';
?>

<!-- Page-specific styles -->
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

/* Base Styles */
.about-container {
    font-family: var(--font-body);
    line-height: 1.6;
    color: var(--color-gray-700);
}

/* Hero Header */
.hero-header {
    background: linear-gradient(135deg, 
        rgba(28, 63, 128, 0.95) 0%, 
        rgba(26, 54, 93, 0.9) 100%), 
        url('<?php echo $baseUrl; ?>/assets/images/about/hero-bg.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: var(--color-white);
    padding: var(--spacing-3xl) 0 var(--spacing-2xl);
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
}

.hero-subtitle {
    font-size: 1.25rem;
    opacity: 0.95;
    line-height: 1.6;
    margin-bottom: var(--spacing-xl);
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

/* Section Styles */
.content-section {
    padding: var(--spacing-3xl) 0;
}

.section-header {
    text-align: center;
    margin-bottom: var(--spacing-2xl);
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

/* Overview Section */
.overview-section {
    background-color: var(--color-white);
}

.overview-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-3xl);
    align-items: center;
}

.overview-image {
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-xl);
}

.overview-image img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform var(--transition-slow);
}

.overview-image:hover img {
    transform: scale(1.02);
}

.overview-text h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.875rem;
    color: var(--color-primary);
    margin-bottom: var(--spacing-lg);
}

.overview-text p {
    color: var(--color-gray-700);
    line-height: 1.8;
    margin-bottom: var(--spacing-lg);
    font-size: 1.125rem;
}

/* Statistics Grid */
.statistics-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-lg);
    margin-top: var(--spacing-2xl);
}

.statistic-item {
    text-align: center;
    padding: var(--spacing-xl);
    background-color: var(--color-gray-50);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-200);
    transition: all var(--transition-base);
}

.statistic-item:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    background-color: var(--color-white);
    border-color: var(--color-primary-light);
}

.statistic-value {
    display: block;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2.5rem;
    color: var(--color-primary);
    margin-bottom: var(--spacing-xs);
}

.statistic-label {
    font-size: 0.875rem;
    color: var(--color-gray-600);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Mission, Vision, Values */
.mvv-section {
    background: linear-gradient(135deg, 
        rgba(44, 82, 130, 0.05) 0%, 
        rgba(26, 54, 93, 0.05) 100%);
}

.mvv-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
}

.mvv-card {
    background-color: var(--color-white);
    padding: var(--spacing-2xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    transition: all var(--transition-base);
    position: relative;
    overflow: hidden;
}

.mvv-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.mvv-card.mission::before {
    background: linear-gradient(90deg, var(--color-primary), var(--color-primary-light));
}

.mvv-card.vision::before {
    background: linear-gradient(90deg, var(--color-secondary), var(--color-accent));
}

.mvv-card.values::before {
    background: linear-gradient(90deg, var(--color-warning), #ed8936);
}

.mvv-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.mvv-icon {
    width: 64px;
    height: 64px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-lg);
    background: var(--color-gray-50);
    color: var(--color-primary);
}

.mvv-card h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.5rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-md);
    text-align: center;
}

.mvv-card p {
    color: var(--color-gray-600);
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
}

/* Values List */
.values-list {
    list-style: none;
    padding: 0;
    margin: var(--spacing-lg) 0 0;
}

.values-list li {
    padding: var(--spacing-sm) 0;
    color: var(--color-gray-700);
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-md);
}

.values-list li strong {
    color: var(--color-gray-800);
    font-weight: 600;
}

.values-list li::before {
    content: '';
    flex-shrink: 0;
    width: 20px;
    height: 20px;
    background-color: var(--color-success);
    border-radius: 50%;
    mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor'%3E%3Cpath fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z' clip-rule='evenodd'/%3E%3C/svg%3E");
    mask-repeat: no-repeat;
    mask-position: center;
    mask-size: 12px;
}

/* Leadership Section */
.leadership-section {
    background-color: var(--color-white);
}

.leadership-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
}

.leadership-card {
    background-color: var(--color-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: all var(--transition-base);
    height: 100%;
}

.leadership-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.leader-image-container {
    position: relative;
    overflow: hidden;
    height: 280px;
}

.leader-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-slow);
}

.leadership-card:hover .leader-image-container img {
    transform: scale(1.05);
}

.leader-info {
    padding: var(--spacing-xl);
}

.leader-name {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.25rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-xs);
}

.leader-title {
    color: var(--color-primary);
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: var(--spacing-md);
    display: block;
}

.leader-bio {
    color: var(--color-gray-600);
    font-size: 0.875rem;
    line-height: 1.6;
}

/* Accreditation Section */
.accreditation-section {
    background: linear-gradient(135deg, 
        var(--color-primary) 0%, 
        var(--color-primary-dark) 100%);
    color: var(--color-white);
    padding: var(--spacing-3xl) 0;
}

.accreditation-content {
    text-align: center;
    max-width: 48rem;
    margin: 0 auto;
}

.accreditation-badges {
    display: flex;
    justify-content: center;
    gap: var(--spacing-xl);
    flex-wrap: wrap;
    margin-top: var(--spacing-2xl);
}

.accreditation-badge {
    background-color: var(--color-white);
    padding: var(--spacing-xl) var(--spacing-2xl);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    box-shadow: var(--shadow-lg);
    transition: all var(--transition-base);
}

.accreditation-badge:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.accreditation-badge span {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-primary);
    font-size: 1.125rem;
}

/* Call to Action */
.cta-section {
    background: linear-gradient(135deg, 
        rgba(44, 82, 130, 0.08) 0%, 
        rgba(26, 54, 93, 0.08) 100%);
    padding: var(--spacing-3xl) 0;
    text-align: center;
}

.cta-content {
    max-width: 48rem;
    margin: 0 auto;
}

.cta-buttons {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
    flex-wrap: wrap;
    margin-top: var(--spacing-xl);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .overview-content {
        grid-template-columns: 1fr;
        gap: var(--spacing-2xl);
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
}

@media (max-width: 768px) {
    .content-section {
        padding: var(--spacing-2xl) 0;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .mvv-grid,
    .leadership-grid {
        grid-template-columns: 1fr;
    }
    
    .accreditation-badges {
        flex-direction: column;
        align-items: center;
        gap: var(--spacing-md);
    }
    
    .accreditation-badge {
        width: 100%;
        max-width: 320px;
        justify-content: center;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-buttons .btn {
        width: 100%;
        max-width: 280px;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
    }
    
    .section-title {
        font-size: 1.75rem;
    }
    
    .mvv-card {
        padding: var(--spacing-xl);
    }
}

/* Print Styles */
@media print {
    .hero-header,
    .breadcrumb,
    .cta-section {
        display: none;
    }
    
    .content-section {
        padding: 1rem 0;
    }
    
    .statistic-item {
        page-break-inside: avoid;
        border: 1px solid var(--color-gray-400);
    }
    
    .leadership-card {
        page-break-inside: avoid;
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
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
</style>

<!-- Main Content -->
<main id="main-content" class="about-container" role="main" aria-label="About the institution">
    <!-- Skip to Content Link -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>

    <!-- Hero Header -->
    <header class="hero-header" role="banner">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">About Federal College of Tropical Nursing Sciences</h1>
                <p class="hero-subtitle">
                    For over three decades, we have led nursing education excellence, producing competent, 
                    compassionate healthcare professionals who transform patient care and community health.
                </p>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <div class="breadcrumb-nav">
                <a href="<?php echo $baseUrl; ?>/">Home</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <span class="breadcrumb-current" aria-current="page">About Us</span>
            </div>
        </div>
    </nav>

    <!-- Institutional Overview -->
    <section class="content-section overview-section" id="overview">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Our Legacy and Heritage</h2>
                <p class="section-description">
                    Established in 1989, Federal College of Tropical Nursing Sciences has evolved into 
                    one of Nigeria's premier institutions for comprehensive nursing education.
                </p>
            </header>
            
            <div class="overview-content">
                <div class="overview-image">
                    <img src="<?php echo $baseUrl; ?>/assets/images/about/campus-building.jpg" 
                         alt="Main campus building of Federal College of Tropical Nursing Sciences" 
                         loading="lazy"
                         onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-building.jpg'">
                </div>
                
                <article class="overview-text">
                    <h3>Excellence in Nursing Education Since 1989</h3>
                    <p>
                        The Federal College of Tropical Nursing Sciences was established with a clear 
                        mission: to develop competent, compassionate, and professional nurses equipped 
                        to meet Nigeria's evolving healthcare demands. Our institution combines academic 
                        rigor with practical clinical training, ensuring graduates excel in diverse 
                        healthcare settings.
                    </p>
                    <p>
                        Our curriculum integrates evidence-based practice with ethical considerations, 
                        preparing nurses who understand healthcare as both science and human service. 
                        Through strategic partnerships with leading healthcare institutions, we provide 
                        students with comprehensive clinical exposure and professional networking 
                        opportunities.
                    </p>
                    <p>
                        With modern simulation laboratories, experienced faculty, and a commitment to 
                        continuous improvement, we maintain our position at the forefront of nursing 
                        education innovation in Nigeria and across West Africa.
                    </p>
                    
                    <div class="statistics-grid">
                        <div class="statistic-item">
                            <span class="statistic-value">35+</span>
                            <span class="statistic-label">Years of Excellence</span>
                        </div>
                        <div class="statistic-item">
                            <span class="statistic-value">5,000+</span>
                            <span class="statistic-label">Nursing Graduates</span>
                        </div>
                        <div class="statistic-item">
                            <span class="statistic-value">100%</span>
                            <span class="statistic-label">NMCN Accredited</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Mission, Vision & Values -->
    <section class="content-section mvv-section" id="mission-vision-values">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Our Guiding Principles</h2>
                <p class="section-description">
                    The foundational principles that shape our educational philosophy and institutional culture.
                </p>
            </header>
            
            <div class="mvv-grid">
                <!-- Mission -->
                <article class="mvv-card mission">
                    <div class="mvv-icon" aria-hidden="true">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h3>Our Mission</h3>
                    <p>
                        To deliver exceptional nursing education through innovative teaching, research, 
                        and community engagement, developing competent and compassionate nursing 
                        professionals who demonstrate excellence in clinical practice, leadership, 
                        and ethical conduct while advancing healthcare in Nigeria and beyond.
                    </p>
                </article>
                
                <!-- Vision -->
                <article class="mvv-card vision">
                    <div class="mvv-icon" aria-hidden="true">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3>Our Vision</h3>
                    <p>
                        To be Africa's leading institution for nursing education and research, 
                        recognized for producing healthcare professionals who transform communities 
                        through innovative practice, ethical leadership, and compassionate, 
                        evidence-based care.
                    </p>
                </article>
                
                <!-- Core Values -->
                <article class="mvv-card values">
                    <div class="mvv-icon" aria-hidden="true">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3>Core Values</h3>
                    <p>These principles guide every aspect of our institutional operations:</p>
                    <ul class="values-list">
                        <li><strong>Excellence:</strong> Commitment to highest standards in education and practice</li>
                        <li><strong>Integrity:</strong> Upholding ethical principles and professional accountability</li>
                        <li><strong>Compassion:</strong> Demonstrating empathy in patient and community care</li>
                        <li><strong>Innovation:</strong> Embracing advancement in nursing practice and education</li>
                        <li><strong>Professionalism:</strong> Maintaining highest standards of conduct and competence</li>
                        <li><strong>Service:</strong> Dedication to community health and wellbeing improvement</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <!-- Leadership Team -->
    <section class="content-section leadership-section" id="leadership">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Institutional Leadership</h2>
                <p class="section-description">
                    Experienced professionals guiding our institution toward educational excellence and 
                    healthcare innovation.
                </p>
            </header>
            
            <div class="leadership-grid">
                <!-- Principal -->
                <article class="leadership-card">
                    <div class="leader-image-container">
                        <img src="<?php echo $baseUrl; ?>/assets/images/leadership/principal.jpg" 
                             alt="Dr. Amina Mohammed, Principal/CEO" 
                             loading="lazy"
                             onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-leadership.jpg'">
                    </div>
                    <div class="leader-info">
                        <h3 class="leader-name">Dr. Amina Mohammed</h3>
                        <span class="leader-title">Principal/Chief Executive Officer</span>
                        <p class="leader-bio">
                            PhD in Nursing Education with over 25 years of nursing practice and 
                            administrative experience. Dr. Mohammed has published extensively in 
                            nursing journals and serves on national healthcare advisory committees.
                        </p>
                    </div>
                </article>
                
                <!-- Vice Principal Academic -->
                <article class="leadership-card">
                    <div class="leader-image-container">
                        <img src="<?php echo $baseUrl; ?>/assets/images/leadership/vp-academic.jpg" 
                             alt="Prof. Chinedu Okeke, Vice Principal Academic" 
                             loading="lazy"
                             onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-leadership.jpg'">
                    </div>
                    <div class="leader-info">
                        <h3 class="leader-name">Prof. Chinedu Okeke</h3>
                        <span class="leader-title">Vice Principal, Academic Affairs</span>
                        <p class="leader-bio">
                            Professor of Community Health Nursing specializing in curriculum 
                            development and educational technology. Leads academic quality assurance 
                            initiatives and faculty development programs.
                        </p>
                    </div>
                </article>
                
                <!-- Director of Clinical Services -->
                <article class="leadership-card">
                    <div class="leader-image-container">
                        <img src="<?php echo $baseUrl; ?>/assets/images/leadership/director-clinical.jpg" 
                             alt="Mrs. Fatima Bello, Director of Clinical Services" 
                             loading="lazy"
                             onerror="this.src='<?php echo $baseUrl; ?>/assets/images/placeholder-leadership.jpg'">
                    </div>
                    <div class="leader-info">
                        <h3 class="leader-name">Mrs. Fatima Bello</h3>
                        <span class="leader-title">Director, Clinical Services</span>
                        <p class="leader-bio">
                            Registered Nurse/Midwife with specialization in critical care nursing. 
                            Oversees clinical placements and institutional partnerships across the 
                            Federal Capital Territory healthcare network.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Accreditation -->
    <section class="accreditation-section" id="accreditation">
        <div class="container">
            <div class="accreditation-content">
                <h2 class="section-title" style="color: var(--color-white);">Institutional Accreditation</h2>
                <p style="font-size: 1.125rem; opacity: 0.95; margin-bottom: var(--spacing-xl);">
                    Federal College of Tropical Nursing Sciences maintains full accreditation 
                    with all relevant regulatory bodies, ensuring our programs meet national 
                    and international nursing education standards.
                </p>
                
                <div class="accreditation-badges">
                    <div class="accreditation-badge">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Nursing & Midwifery Council of Nigeria</span>
                    </div>
                    <div class="accreditation-badge">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>National Board for Technical Education</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="content-section cta-section" id="join-community">
        <div class="container">
            <div class="cta-content">
                <h2 class="section-title">Join Our Nursing Community</h2>
                <p class="section-description" style="margin-bottom: 0;">
                    Begin your professional nursing journey at one of Nigeria's most respected 
                    nursing institutions. Our comprehensive programs provide the foundation for 
                    a rewarding healthcare career.
                </p>
                
                <div class="cta-buttons">
                    <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-primary btn-lg">
                        Begin Application Process
                    </a>
                    <a href="<?php echo $baseUrl; ?>/programs" class="btn btn-outline btn-lg">
                        Explore Our Programs
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" class="btn btn-outline btn-lg">
                        Schedule Consultation
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
    
    // Image error handling
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('error', function() {
            const src = this.src;
            if (src.includes('leadership/') || src.includes('principal') || 
                src.includes('vp-academic') || src.includes('director-clinical')) {
                this.src = '<?php echo $baseUrl; ?>/assets/images/placeholder-leadership.jpg';
            } else if (src.includes('about/') || src.includes('campus')) {
                this.src = '<?php echo $baseUrl; ?>/assets/images/placeholder-building.jpg';
            }
        });
    });
    
    // Statistics animation
    function animateStatistics() {
        const statisticItems = document.querySelectorAll('.statistic-item');
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const valueElement = entry.target.querySelector('.statistic-value');
                    const text = valueElement.textContent;
                    const numericValue = parseInt(text.replace('+', ''));
                    
                    if (!isNaN(numericValue)) {
                        valueElement.textContent = '0';
                        let current = 0;
                        const increment = numericValue / 30;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= numericValue) {
                                valueElement.textContent = text;
                                clearInterval(timer);
                            } else {
                                valueElement.textContent = Math.floor(current) + (text.includes('+') ? '+' : '');
                            }
                        }, 50);
                    }
                    
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        statisticItems.forEach(item => observer.observe(item));
    }
    
    // Initialize animations when page loads
    setTimeout(animateStatistics, 500);
    
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
    
    // Print functionality
    const printButton = document.getElementById('print-page');
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
});
</script>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>