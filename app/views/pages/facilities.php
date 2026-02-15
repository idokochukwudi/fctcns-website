<?php
/**
 * Facilities Page View
 * 
 * @package FCT_CNS
 */

// Extract data passed from controller
extract($data ?? []);

// Helper function for safe output
if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// Set default values
$page_title = $page_title ?? 'Facilities - FCT College of Nursing Sciences';
$page_description = $page_description ?? 'State-of-the-art facilities including simulation laboratories, library, and learning resources';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e($page_description); ?>">
    <title><?php echo e($page_title); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
           RESET & BASE STYLES
           ========================================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: #FFFFFF;
            color: #1A1F2E;
            line-height: 1.6;
        }
        
        /* ==========================================================================
           DESIGN TOKENS - Matching Homepage
           ========================================================================== */
        :root {
            --purple-deep: #4B1F5A;
            --purple: #6C3082;
            --purple-medium: #8A4FA0;
            --purple-light: #A875BD;
            --purple-pale: #F3EAF8;
            --purple-soft: #F9F3FC;
            
            --gold-deep: #B48C3A;
            --gold: #C9A44A;
            --gold-light: #D8B86C;
            --gold-pale: #FDF6E7;
            
            --ink: #1A1F2E;
            --ink-soft: #3A4055;
            --slate: #5B677B;
            --border: #E9EDF2;
            --white: #FFFFFF;
            
            --purple-gradient: linear-gradient(135deg, #4B1F5A 0%, #6C3082 50%, #8A4FA0 100%);
            --gold-gradient: linear-gradient(135deg, #B48C3A 0%, #C9A44A 50%, #D8B86C 100%);
            
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 6px 24px rgba(0,0,0,0.06);
            --shadow-lg: 0 16px 48px rgba(0,0,0,0.08);
            --shadow-xl: 0 32px 80px rgba(0,0,0,0.12);
            --shadow-purple: 0 10px 30px rgba(108,48,130,0.25);
            
            --radius-sm: 6px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            
            --font-display: 'Cormorant Garamond', serif;
            --font-body: 'Outfit', sans-serif;
            
            --gutter: clamp(1rem, 4vw, 4rem);
            --container-max: 1400px;
            
            --space-xs: 0.5rem;
            --space-sm: 1rem;
            --space-md: 1.5rem;
            --space-lg: 2rem;
            --space-xl: 3rem;
            --space-xxl: 5rem;
        }
        
        /* ==========================================================================
           CONTAINER
           ========================================================================== */
        .container {
            width: 100%;
            max-width: var(--container-max);
            margin: 0 auto;
            padding: 0 var(--gutter);
        }
        
        /* ==========================================================================
           TYPOGRAPHY
           ========================================================================== */
        h1, h2, h3, h4 {
            font-family: var(--font-display);
            font-weight: 700;
            line-height: 1.2;
            color: var(--ink);
        }
        
        .section-header {
            text-align: center;
            margin-bottom: var(--space-xl);
        }
        
        .section-header h2 {
            font-size: clamp(2rem, 4vw, 2.8rem);
            margin-bottom: var(--space-sm);
            position: relative;
            display: inline-block;
        }
        
        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--gold-gradient);
            border-radius: 3px;
        }
        
        .section-header p {
            font-size: clamp(1rem, 1.5vw, 1.2rem);
            color: var(--slate);
            max-width: 700px;
            margin: var(--space-md) auto 0;
        }
        
        /* ==========================================================================
           BUTTONS
           ========================================================================== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-family: var(--font-body);
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn--primary {
            background: var(--purple-gradient);
            color: white;
            box-shadow: var(--shadow-purple);
        }
        
        .btn--primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(108,48,130,0.35);
        }
        
        .btn--outline {
            background: transparent;
            color: var(--purple);
            border: 2px solid var(--purple);
        }
        
        .btn--outline:hover {
            background: var(--purple);
            color: white;
            transform: translateY(-2px);
        }
        
        /* ==========================================================================
           HERO SECTION
           ========================================================================== */
        .page-hero {
            background: linear-gradient(135deg, var(--purple-deep), var(--purple));
            padding: var(--space-xxl) 0;
            position: relative;
            overflow: hidden;
        }
        
        .page-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(201,164,74,0.1) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }
        
        .page-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gold-gradient);
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }
        
        .hero-content h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            color: white;
            margin-bottom: var(--space-md);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .hero-content p {
            font-size: clamp(1.1rem, 2vw, 1.3rem);
            color: rgba(255,255,255,0.9);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .hero-icon {
            font-size: 4rem;
            color: var(--gold);
            margin-bottom: var(--space-md);
            animation: float 3s ease-in-out infinite;
        }
        
        /* ==========================================================================
           BREADCRUMB
           ========================================================================== */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: var(--space-lg);
            color: var(--slate);
            font-size: 0.9rem;
        }
        
        .breadcrumb a {
            color: var(--purple);
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            color: var(--gold);
        }
        
        .breadcrumb i {
            font-size: 0.8rem;
            color: var(--gold);
        }
        
        /* ==========================================================================
           FACILITY CARDS
           ========================================================================== */
        .facility-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--space-xl);
            margin: var(--space-xl) 0;
        }
        
        @media (min-width: 768px) {
            .facility-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        .facility-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .facility-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl), var(--shadow-purple);
            border-color: var(--purple-light);
        }
        
        .facility-image {
            height: 250px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .facility-badge {
            position: absolute;
            top: var(--space-sm);
            right: var(--space-sm);
            background: var(--purple);
            color: white;
            padding: 0.25rem 1rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: var(--shadow-md);
        }
        
        .facility-content {
            padding: var(--space-lg);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .facility-content h3 {
            font-size: 1.5rem;
            color: var(--purple-deep);
            margin-bottom: var(--space-xs);
        }
        
        .facility-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gold);
            font-size: 0.9rem;
            margin-bottom: var(--space-md);
        }
        
        .facility-location i {
            color: var(--gold);
        }
        
        .facility-description {
            color: var(--slate);
            margin-bottom: var(--space-md);
            line-height: 1.7;
        }
        
        .facility-features {
            list-style: none;
            margin-bottom: var(--space-lg);
        }
        
        .facility-features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
            color: var(--ink-soft);
            font-size: 0.95rem;
            border-bottom: 1px solid var(--border);
        }
        
        .facility-features li:last-child {
            border-bottom: none;
        }
        
        .facility-features li i {
            color: var(--gold);
            width: 20px;
            font-size: 1rem;
        }
        
        .facility-footer {
            margin-top: auto;
            display: flex;
            gap: var(--space-sm);
            flex-wrap: wrap;
        }
        
        /* ==========================================================================
           STATS SECTION
           ========================================================================== */
        .stats-section {
            background: var(--purple-soft);
            padding: var(--space-xxl) 0;
            margin: var(--space-xxl) 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-lg);
        }
        
        @media (min-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-family: var(--font-display);
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--purple-deep);
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--slate);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* ==========================================================================
           VIRTUAL TOUR SECTION
           ========================================================================== */
        .tour-section {
            background: var(--gold-pale);
            border-radius: var(--radius-xl);
            padding: var(--space-xl);
            margin: var(--space-xxl) 0;
            text-align: center;
        }
        
        .tour-section h2 {
            color: var(--purple-deep);
            margin-bottom: var(--space-md);
        }
        
        .tour-section p {
            color: var(--ink-soft);
            max-width: 700px;
            margin: 0 auto var(--space-lg);
        }
        
        .tour-placeholder {
            background: linear-gradient(135deg, var(--purple-soft), var(--gold-pale));
            border: 2px dashed var(--purple-light);
            border-radius: var(--radius-lg);
            padding: var(--space-xl);
            margin: var(--space-lg) 0;
        }
        
        .tour-placeholder i {
            font-size: 4rem;
            color: var(--purple);
            margin-bottom: var(--space-md);
            opacity: 0.5;
        }
        
        .tour-placeholder p {
            color: var(--purple);
            font-size: 1.1rem;
        }
        
        /* ==========================================================================
           CTA SECTION
           ========================================================================== */
        .cta-section {
            background: var(--purple-gradient);
            border-radius: var(--radius-xl);
            padding: var(--space-xl);
            margin: var(--space-xxl) 0;
            text-align: center;
            color: white;
        }
        
        .cta-section h2 {
            color: white;
            margin-bottom: var(--space-md);
        }
        
        .cta-section p {
            color: rgba(255,255,255,0.9);
            margin-bottom: var(--space-lg);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-buttons {
            display: flex;
            gap: var(--space-sm);
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .cta-buttons .btn--outline {
            border-color: white;
            color: white;
        }
        
        .cta-buttons .btn--outline:hover {
            background: white;
            color: var(--purple);
        }
        
        /* ==========================================================================
           ANIMATIONS
           ========================================================================== */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* ==========================================================================
           RESPONSIVE
           ========================================================================== */
        @media (max-width: 768px) {
            :root {
                --space-xl: 2.5rem;
                --space-xxl: 4rem;
            }
            
            .facility-footer {
                flex-direction: column;
            }
            
            .facility-footer .btn {
                width: 100%;
                justify-content: center;
            }
            
            .stats-grid {
                gap: var(--space-md);
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Page Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h1>Our Facilities</h1>
                <p>State-of-the-art learning environments designed for excellence in nursing education</p>
            </div>
        </div>
    </section>
    
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>/">Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Facilities</span>
        </div>
        
        <!-- Introduction -->
        <div style="text-align: center; max-width: 800px; margin: 0 auto var(--space-xl);">
            <p style="font-size: 1.1rem; color: var(--slate);">At FCT College of Nursing Sciences, we pride ourselves on providing modern, well-equipped facilities that create an optimal learning environment for our students. Our campus features cutting-edge simulation laboratories, comprehensive library resources, and comfortable student spaces.</p>
        </div>
        
        <!-- Main Facilities Grid -->
        <div class="facility-grid">
            <!-- Facility 1: Simulation Laboratory -->
            <div class="facility-card">
                <div class="facility-image" style="background-image: url('<?php echo BASE_URL; ?>/assets/images/facilities/simulation-lab.jpg');">
                    <div class="facility-badge">Featured</div>
                </div>
                <div class="facility-content">
                    <h3>Simulation Laboratory</h3>
                    <div class="facility-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Main Campus, Block A, Ground Floor</span>
                    </div>
                    <p class="facility-description">Our state-of-the-art simulation laboratory features high-fidelity manikins and virtual reality technology that mimic real clinical scenarios, allowing students to practice procedures in a safe, controlled environment.</p>
                    <ul class="facility-features">
                        <li><i class="fas fa-check-circle"></i> High-fidelity adult and pediatric manikins</li>
                        <li><i class="fas fa-check-circle"></i> Virtual reality simulation equipment</li>
                        <li><i class="fas fa-check-circle"></i> Real-time vital signs monitoring</li>
                        <li><i class="fas fa-check-circle"></i> Video recording for debriefing sessions</li>
                        <li><i class="fas fa-check-circle"></i> Emergency response scenarios</li>
                    </ul>
                    <div class="facility-footer">
                        <a href="<?php echo BASE_URL; ?>/contact?subject=Simulation Lab Tour" class="btn btn--primary">
                            <i class="fas fa-calendar-alt"></i> Schedule Tour
                        </a>
                        <a href="<?php echo BASE_URL; ?>/resources" class="btn btn--outline">
                            <i class="fas fa-book-open"></i> Related Resources
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Facility 2: Library and Learning Resources -->
            <div class="facility-card">
                <div class="facility-image" style="background-image: url('<?php echo BASE_URL; ?>/assets/images/facilities/library.jpg');">
                    <div class="facility-badge">24/7 Access</div>
                </div>
                <div class="facility-content">
                    <h3>Library & Learning Resources Center</h3>
                    <div class="facility-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Main Campus, Block C</span>
                    </div>
                    <p class="facility-description">Our comprehensive library offers extensive collections of nursing and medical literature, including textbooks, journals, and digital resources to support your academic journey.</p>
                    <ul class="facility-features">
                        <li><i class="fas fa-check-circle"></i> Over 10,000 nursing textbooks and references</li>
                        <li><i class="fas fa-check-circle"></i> Online database access (CINAHL, PubMed)</li>
                        <li><i class="fas fa-check-circle"></i> Quiet study areas and group study rooms</li>
                        <li><i class="fas fa-check-circle"></i> Computer workstations with internet access</li>
                        <li><i class="fas fa-check-circle"></i> Librarian assistance for research</li>
                    </ul>
                    <div class="facility-footer">
                        <a href="<?php echo BASE_URL; ?>/library" class="btn btn--primary">
                            <i class="fas fa-book"></i> Visit Library
                        </a>
                        <a href="<?php echo BASE_URL; ?>/resources" class="btn btn--outline">
                            <i class="fas fa-database"></i> Online Resources
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Facility 3: Clinical Skills Labs -->
            <div class="facility-card">
                <div class="facility-image" style="background-image: url('<?php echo BASE_URL; ?>/assets/images/facilities/skills-lab.jpg');">
                </div>
                <div class="facility-content">
                    <h3>Clinical Skills Laboratories</h3>
                    <div class="facility-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Main Campus, Block A, First Floor</span>
                    </div>
                    <p class="facility-description">Dedicated spaces for practicing fundamental nursing skills with low-fidelity simulators and standardized patients to build confidence before clinical rotations.</p>
                    <ul class="facility-features">
                        <li><i class="fas fa-check-circle"></i> Basic nursing skill stations</li>
                        <li><i class="fas fa-check-circle"></i> IV insertion and medication administration</li>
                        <li><i class="fas fa-check-circle"></i> Wound care and dressing change</li>
                        <li><i class="fas fa-check-circle"></i> Vital signs measurement equipment</li>
                        <li><i class="fas fa-check-circle"></i> Patient assessment tools</li>
                    </ul>
                    <div class="facility-footer">
                        <a href="<?php echo BASE_URL; ?>/programs" class="btn btn--primary">
                            <i class="fas fa-graduation-cap"></i> View Programs
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Facility 4: Anatomy & Physiology Lab -->
            <div class="facility-card">
                <div class="facility-image" style="background-image: url('<?php echo BASE_URL; ?>/assets/images/facilities/anatomy-lab.jpg');">
                </div>
                <div class="facility-content">
                    <h3>Anatomy & Physiology Laboratory</h3>
                    <div class="facility-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Science Complex</span>
                    </div>
                    <p class="facility-description">Well-equipped laboratory for studying human anatomy and physiology using models, specimens, and interactive technology.</p>
                    <ul class="facility-features">
                        <li><i class="fas fa-check-circle"></i> Anatomical models and charts</li>
                        <li><i class="fas fa-check-circle"></i> Microscopes for histology studies</li>
                        <li><i class="fas fa-check-circle"></i> Virtual dissection tables</li>
                        <li><i class="fas fa-check-circle"></i> Physiology experiment equipment</li>
                        <li><i class="fas fa-check-circle"></i> Preserved specimens for study</li>
                    </ul>
                    <div class="facility-footer">
                        <a href="<?php echo BASE_URL; ?>/contact?subject=Anatomy Lab" class="btn btn--primary">
                            <i class="fas fa-flask"></i> Lab Schedule
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Facility 5: IT Center -->
            <div class="facility-card">
                <div class="facility-image" style="background-image: url('<?php echo BASE_URL; ?>/assets/images/facilities/it-center.jpg');">
                    <div class="facility-badge">New</div>
                </div>
                <div class="facility-content">
                    <h3>Information Technology Center</h3>
                    <div class="facility-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Library Building, Second Floor</span>
                    </div>
                    <p class="facility-description">Modern computer lab with high-speed internet and specialized healthcare software for nursing informatics and research.</p>
                    <ul class="facility-features">
                        <li><i class="fas fa-check-circle"></i> 50 workstations with latest software</li>
                        <li><i class="fas fa-check-circle"></i> Electronic health records training</li>
                        <li><i class="fas fa-check-circle"></i> Nursing informatics applications</li>
                        <li><i class="fas fa-check-circle"></i> High-speed fiber optic internet</li>
                        <li><i class="fas fa-check-circle"></i> Printing and scanning services</li>
                    </ul>
                    <div class="facility-footer">
                        <a href="<?php echo BASE_URL; ?>/contact?subject=IT Center" class="btn btn--primary">
                            <i class="fas fa-laptop"></i> Computer Access
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Facility 6: Student Lounge & Study Areas -->
            <div class="facility-card">
                <div class="facility-image" style="background-image: url('<?php echo BASE_URL; ?>/assets/images/facilities/student-lounge.jpg');">
                </div>
                <div class="facility-content">
                    <h3>Student Lounge & Study Areas</h3>
                    <div class="facility-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Student Center</span>
                    </div>
                    <p class="facility-description">Comfortable spaces for students to relax, collaborate, and study between classes, fostering a supportive learning community.</p>
                    <ul class="facility-features">
                        <li><i class="fas fa-check-circle"></i> Group study rooms with whiteboards</li>
                        <li><i class="fas fa-check-circle"></i> Quiet individual study carrels</li>
                        <li><i class="fas fa-check-circle"></i> Kitchenette with microwave and vending</li>
                        <li><i class="fas fa-check-circle"></i> Comfortable seating areas</li>
                        <li><i class="fas fa-check-circle"></i> Free Wi-Fi throughout</li>
                    </ul>
                    <div class="facility-footer">
                        <a href="<?php echo BASE_URL; ?>/student-life" class="btn btn--primary">
                            <i class="fas fa-users"></i> Student Life
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Section -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">6</div>
                    <div class="stat-label">Specialized Labs</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">Library Volumes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Computer Stations</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Study Access</div>
                </div>
            </div>
        </div>
        
        <!-- Virtual Tour Section (Coming Soon) -->
        <div class="tour-section">
            <h2>Virtual Campus Tour</h2>
            <p>Experience our facilities from anywhere in the world. We're currently developing an interactive virtual tour of our campus.</p>
            
            <div class="tour-placeholder">
                <i class="fas fa-vr-cardboard"></i>
                <p>Virtual tour coming soon! Check back for updates.</p>
            </div>
            
            <p>In the meantime, you can <a href="<?php echo BASE_URL; ?>/contact" style="color: var(--purple); font-weight: 600;">contact us</a> to schedule an in-person tour of our facilities.</p>
        </div>
        
        <!-- Call to Action -->
        <div class="cta-section">
            <h2>Experience Our Facilities Firsthand</h2>
            <p>Schedule a campus tour to see our state-of-the-art facilities and learn more about how we prepare nursing students for successful careers.</p>
            <div class="cta-buttons">
                <a href="<?php echo BASE_URL; ?>/contact" class="btn btn--primary" style="background: white; color: var(--purple);">
                    <i class="fas fa-calendar-check"></i> Schedule a Tour
                </a>
                <a href="<?php echo BASE_URL; ?>/programs" class="btn btn--outline">
                    <i class="fas fa-graduation-cap"></i> View Programs
                </a>
            </div>
        </div>
    </div>
</body>
</html>