<?php
/**
 * Nursing Programs
 * 
 * Displays accredited nursing education programs with comprehensive information,
 * filtering capabilities, and comparison features.
 * 
 * @package FCTCNS
 * @version 2.1
 */

// Security and initialization
define('PAGE_TITLE', 'Nursing Programs');
define('PAGE_DESCRIPTION', 'Explore accredited nursing education programs at Federal College of Tropical Nursing Sciences');
define('PAGE_KEYWORDS', 'nursing programs, nursing education, healthcare training, NMCN accredited, nursing courses');

require_once __DIR__ . '/../includes/header.php';

// Configuration
$baseUrl = '/fctcns-website/public';
$programs = [];
$error = null;

/**
 * Fetch programs from database with error handling
 * 
 * @return array Programs data or empty array on failure
 */
function fetchPrograms() {
    try {
        require_once dirname(__DIR__, 2) . '/app/config/database.php';
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT 
                program_id, 
                program_name, 
                program_code, 
                duration, 
                description, 
                admission_requirements, 
                career_paths, 
                accreditation_status, 
                image_path,
                category,
                display_order,
                tuition_fee,
                application_deadline,
                intake_periods
            FROM programs 
            WHERE is_active = TRUE 
            ORDER BY display_order ASC, program_name ASC
        ");
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Database error fetching programs: " . $e->getMessage());
        return [];
    } catch (Exception $e) {
        error_log("Programs data fetch error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get fallback programs data
 * 
 * @return array Structured programs data
 */
function getFallbackPrograms() {
    return [
        [
            'program_id' => 'national-diploma',
            'program_name' => 'National Diploma in Nursing',
            'program_code' => 'NDN',
            'duration' => '3 Years',
            'category' => 'basic',
            'description' => 'A comprehensive three-year program leading to the award of National Diploma in Nursing. This program provides students with the knowledge, skills, and attitudes required for professional nursing practice.',
            'admission_requirements' => [
                'Minimum of five credit passes in SSCE/GCE O\'Level including English Language, Mathematics, Biology, Chemistry, and Physics',
                'Credit passes obtained in not more than two sittings',
                'Minimum age of 17 years at time of application',
                'Satisfactory performance in entrance examination and interview',
                'Medical fitness certificate from recognized healthcare facility',
                'Character reference letter from reputable individual'
            ],
            'career_paths' => [
                'Registered Nurse in hospitals and clinical settings',
                'Nursing Officer in government healthcare institutions',
                'School Nurse in educational institutions',
                'Industrial Nurse in corporate organizations',
                'Foundation for Bachelor of Nursing Science degree',
                'Healthcare administrator or manager roles'
            ],
            'accreditation_status' => 'Fully Accredited',
            'accreditation_bodies' => ['NMCN', 'NBTE'],
            'tuition_fee' => 'Available upon request',
            'application_deadline' => 'Ongoing enrollment',
            'intake_periods' => ['January', 'September']
        ],
        [
            'program_id' => 'basic-nursing',
            'program_name' => 'Basic Nursing Program',
            'program_code' => 'BNP',
            'duration' => '3 Years',
            'category' => 'basic',
            'description' => 'The Basic Nursing program provides fundamental nursing education and training for individuals beginning their career in nursing, emphasizing patient care and clinical competence.',
            'admission_requirements' => [
                'Five credit passes in WAEC/NECO including English, Mathematics, Biology, Chemistry, and Physics',
                'Credits obtained in not more than two examination sittings',
                'Age between 17 and 35 years at time of application',
                'Medical fitness certificate from recognized healthcare facility',
                'Letter of recommendation from reputable individual',
                'Birth certificate or declaration of age document'
            ],
            'career_paths' => [
                'Staff Nurse in various healthcare facilities',
                'Nurse Educator in training institutions',
                'Public Health Nurse in community settings',
                'Clinical Nurse Specialist in specialized units',
                'Nursing Administrator in healthcare management',
                'Research Assistant in medical research institutions'
            ],
            'accreditation_status' => 'Fully Accredited',
            'accreditation_bodies' => ['NMCN', 'NBTE'],
            'tuition_fee' => 'Available upon request',
            'application_deadline' => 'Ongoing enrollment',
            'intake_periods' => ['January', 'September']
        ],
        [
            'program_id' => 'basic-midwifery',
            'program_name' => 'Basic Midwifery Program',
            'program_code' => 'BMP',
            'duration' => '3 Years',
            'category' => 'specialized',
            'description' => 'Specialized training program focusing on maternal and child healthcare, antenatal care, delivery procedures, postnatal care, and family planning services.',
            'admission_requirements' => [
                'Five credit passes including English, Mathematics, Biology, Chemistry, and Physics',
                'Female candidates only, in accordance with program regulations',
                'Minimum age of 17 years at time of application',
                'Good moral character with reference letter',
                'Physical and mental fitness for midwifery practice',
                'Demonstrated interest in maternal and child healthcare'
            ],
            'career_paths' => [
                'Registered Midwife in maternity centers and hospitals',
                'Maternity Ward Nurse in healthcare facilities',
                'Reproductive Health Nurse in clinical settings',
                'Family Planning Counselor in health centers',
                'Maternal and Child Health Coordinator',
                'Neonatal Care Specialist'
            ],
            'accreditation_status' => 'Fully Accredited',
            'accreditation_bodies' => ['NMCN', 'NBTE'],
            'tuition_fee' => 'Available upon request',
            'application_deadline' => 'Ongoing enrollment',
            'intake_periods' => ['January', 'September']
        ],
        [
            'program_id' => 'post-basic',
            'program_name' => 'Post Basic Nursing Specialization',
            'program_code' => 'PBNS',
            'duration' => '18 Months',
            'category' => 'advanced',
            'description' => 'Advanced program designed for registered nurses seeking specialization in intensive care, pediatric nursing, psychiatric nursing, or perioperative nursing.',
            'admission_requirements' => [
                'Registered Nurse (RN) with current practicing license',
                'Minimum of one year post-registration clinical experience',
                'Five O\'Level credits including English and Science subjects',
                'Professional recommendation from current employer',
                'Successful performance in selection interview',
                'Proof of registration with Nursing and Midwifery Council of Nigeria'
            ],
            'career_paths' => [
                'Specialist Nurse in chosen clinical field',
                'Nursing Unit Manager in hospital departments',
                'Clinical Instructor in nursing education institutions',
                'Nurse Consultant in specialized healthcare areas',
                'Advanced Practice Nurse with extended clinical roles',
                'Nursing Supervisor in healthcare facilities'
            ],
            'accreditation_status' => 'Fully Accredited',
            'accreditation_bodies' => ['NMCN', 'NBTE'],
            'tuition_fee' => 'Available upon request',
            'application_deadline' => 'Ongoing enrollment',
            'intake_periods' => ['March', 'October']
        ],
        [
            'program_id' => 'community-health',
            'program_name' => 'Community Health Nursing',
            'program_code' => 'CHN',
            'duration' => '3 Years',
            'category' => 'specialized',
            'description' => 'Comprehensive program focusing on public health nursing, preventive healthcare, and community-based healthcare service delivery.',
            'admission_requirements' => [
                'Five credit passes in SSCE including English, Mathematics, Biology, and two additional science subjects',
                'Demonstrated interest in community service and public health',
                'Strong communication and interpersonal skills',
                'Physical fitness for community fieldwork and health visits',
                'Willingness to work in rural and underserved communities',
                'Understanding of basic health promotion principles'
            ],
            'career_paths' => [
                'Community Health Nurse in primary healthcare centers',
                'Public Health Officer in government health agencies',
                'Health Educator in community organizations',
                'Primary Healthcare Coordinator in local government areas',
                'Epidemiology Officer in disease control programs',
                'Health Program Manager in non-governmental organizations'
            ],
            'accreditation_status' => 'Fully Accredited',
            'accreditation_bodies' => ['NMCN', 'NBTE'],
            'tuition_fee' => 'Available upon request',
            'application_deadline' => 'Ongoing enrollment',
            'intake_periods' => ['January', 'September']
        ]
    ];
}

/**
 * Parse list data from database or array
 * 
 * @param mixed $data Input data
 * @return array Parsed list
 */
function parseListData($data) {
    if (empty($data)) {
        return [];
    }
    
    if (is_array($data)) {
        return array_filter(array_map('trim', $data));
    }
    
    if (is_string($data)) {
        // Try to decode as JSON
        $decoded = json_decode($data, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_filter(array_map('trim', $decoded));
        }
        
        // Fall back to newline-separated text
        return array_filter(array_map('trim', explode("\n", $data)));
    }
    
    return [];
}

/**
 * Get program category label
 * 
 * @param string $category Category identifier
 * @return string Display label
 */
function getCategoryLabel($category) {
    $labels = [
        'basic' => 'Basic Nursing Programs',
        'advanced' => 'Advanced Specializations',
        'specialized' => 'Specialized Programs'
    ];
    
    return $labels[$category] ?? 'Nursing Programs';
}

/**
 * Sanitize output for HTML
 * 
 * @param string $text Text to sanitize
 * @return string Sanitized text
 */
function sanitizeOutput($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Fetch programs
$programs = fetchPrograms();

// Use fallback if no programs found
if (empty($programs)) {
    $programs = getFallbackPrograms();
    $error = "Note: Displaying program information. For the most current details, please contact our admissions office.";
}
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
    --font-monospace: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', monospace;
    
    /* Spacing */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-2xl: 3rem;
    
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
.programs-container {
    font-family: var(--font-body);
    line-height: 1.6;
    color: var(--color-gray-700);
}

/* Header Section */
.page-header {
    background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
    color: var(--color-white);
    padding: var(--spacing-2xl) 0;
}

.page-header h1 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: var(--spacing-md);
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

/* Tab Navigation */
.program-tabs {
    background: var(--color-white);
    border-bottom: 2px solid var(--color-gray-200);
}

.tab-button {
    padding: var(--spacing-md) var(--spacing-xl);
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

/* Responsive Design */
@media (max-width: 768px) {
    .page-header h1 {
        font-size: 2rem;
    }
    
    .program-tabs {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .comparison-table-container {
        overflow-x: auto;
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
</style>

<!-- Main Content -->
<main class="programs-container" id="main-content" role="main" aria-label="Nursing programs information">
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

    <?php if ($error): ?>
    <section class="section">
        <div class="container">
            <div class="alert alert-info" role="alert">
                <div class="alert-content">
                    <strong>Information:</strong> <?php echo sanitizeOutput($error); ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

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
            <header class="text-center mb-12">
                <h2 class="h2 mb-4">Comprehensive Nursing Curriculum</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Our programs combine theoretical foundations with extensive clinical practice, 
                    ensuring graduates are prepared for diverse healthcare settings.
                </p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php foreach ($programs as $index => $program): ?>
                <?php
                // Extract program data
                $programId = $program['program_id'] ?? 'program-' . ($index + 1);
                $programName = $program['program_name'] ?? 'Nursing Program';
                $programCode = $program['program_code'] ?? '';
                $duration = $program['duration'] ?? '';
                $description = $program['description'] ?? '';
                $category = $program['category'] ?? 'basic';
                $requirements = parseListData($program['admission_requirements'] ?? []);
                $careerPaths = parseListData($program['career_paths'] ?? []);
                $accreditation = $program['accreditation_bodies'] ?? ['NMCN', 'NBTE'];
                $tuitionFee = $program['tuition_fee'] ?? 'Contact admissions office';
                $applicationDeadline = $program['application_deadline'] ?? 'Ongoing';
                $intakePeriods = is_array($program['intake_periods'] ?? null) ? $program['intake_periods'] : [];
                ?>
                
                <article class="program-card" data-category="<?php echo sanitizeOutput($category); ?>" id="<?php echo sanitizeOutput($programId); ?>">
                    <div class="program-card-header">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="program-badge badge-<?php echo sanitizeOutput($category); ?>">
                                    <?php echo getCategoryLabel($category); ?>
                                </span>
                            </div>
                            <?php if ($programCode): ?>
                            <div class="text-sm font-semibold text-gray-500">
                                Code: <?php echo sanitizeOutput($programCode); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="h3 mb-2"><?php echo sanitizeOutput($programName); ?></h3>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <?php if ($duration): ?>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Duration: <?php echo sanitizeOutput($duration); ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($intakePeriods)): ?>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Intake: <?php echo implode(', ', $intakePeriods); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="program-card-body">
                        <?php if ($description): ?>
                        <div class="mb-6">
                            <h4 class="h4 mb-3">Program Overview</h4>
                            <p class="text-gray-700"><?php echo sanitizeOutput($description); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <?php if (!empty($requirements)): ?>
                            <div>
                                <h5 class="font-semibold text-gray-800 mb-2">Admission Requirements</h5>
                                <ul class="requirement-list">
                                    <?php foreach ($requirements as $req): ?>
                                    <li><?php echo sanitizeOutput($req); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($careerPaths)): ?>
                            <div>
                                <h5 class="font-semibold text-gray-800 mb-2">Career Opportunities</h5>
                                <ul class="requirement-list">
                                    <?php foreach ($careerPaths as $career): ?>
                                    <li><?php echo sanitizeOutput($career); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-2">Accreditation Status</h5>
                                    <div class="accreditation-badges">
                                        <?php foreach ($accreditation as $body): ?>
                                        <div class="accreditation-badge verified">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <?php echo sanitizeOutput($body); ?>
                                        </div>
                                        <?php endforeach; ?>
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
                <?php endforeach; ?>
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
                        <?php foreach ($programs as $program): ?>
                        <?php
                        $programId = $program['program_id'] ?? '';
                        $programName = $program['program_name'] ?? '';
                        $duration = $program['duration'] ?? '';
                        $category = $program['category'] ?? '';
                        $programCode = $program['program_code'] ?? '';
                        $accreditation = $program['accreditation_bodies'] ?? ['NMCN', 'NBTE'];
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo sanitizeOutput($programName); ?></strong>
                                <?php if ($programCode): ?>
                                <div class="text-sm text-gray-500"><?php echo sanitizeOutput($programCode); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo sanitizeOutput($duration); ?></td>
                            <td>
                                <?php 
                                $entryLevel = match($category) {
                                    'advanced' => 'Registered Nurse',
                                    'specialized' => 'Direct Entry',
                                    default => 'Secondary School'
                                };
                                echo $entryLevel;
                                ?>
                            </td>
                            <td>
                                <?php
                                $qualification = match($category) {
                                    'basic' => 'Diploma/Certificate',
                                    'advanced' => 'Specialist Certificate',
                                    'specialized' => 'Professional Certificate',
                                    default => 'Certificate'
                                };
                                echo $qualification;
                                ?>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <?php foreach ($accreditation as $body): ?>
                                    <span class="text-xs px-2 py-1 bg-gray-100 rounded"><?php echo $body; ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="#<?php echo sanitizeOutput($programId); ?>" class="text-primary hover:underline">
                                        View Details
                                    </a>
                                    <a href="<?php echo $baseUrl; ?>/admissions?program=<?php echo urlencode($programId); ?>" 
                                       class="text-primary hover:underline">
                                        Apply
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
                
                // Log interaction
                console.log('Program filter applied:', filter);
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
                
                const headerHeight = document.querySelector('.navbar').offsetHeight || 80;
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
    
    // Print functionality
    const printButton = document.getElementById('print-programs');
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
    
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
    
    // Performance monitoring
    window.addEventListener('load', function() {
        if ('performance' in window) {
            const perfData = performance.getEntriesByType('navigation')[0];
            if (perfData) {
                console.log('Page loaded in:', perfData.loadEventEnd - perfData.fetchStart, 'ms');
            }
        }
    });
});
</script>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>