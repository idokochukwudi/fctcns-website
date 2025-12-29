<?php
/**
 * Research Page
 * 
 * Research activities, publications, ongoing projects, and research facilities.
 * 
 * @package FCTCNS
 * @version 2.1
 */

// Security and initialization
define('PAGE_TITLE', 'Research - Federal College of Tropical Nursing Sciences');
define('PAGE_DESCRIPTION', 'Research initiatives, publications, and innovation in nursing and healthcare sciences');
define('PAGE_KEYWORDS', 'nursing research, healthcare research, research publications, nursing innovation, research facilities');

// Include header
require_once __DIR__ . '/../includes/header.php';

// Configuration
$baseUrl = '/fctcns-website/public';

// Research data
$researchAreas = [
    [
        'id' => 'clinical-nursing',
        'title' => 'Clinical Nursing Research',
        'description' => 'Evidence-based practice, patient outcomes, and clinical interventions in acute and chronic care settings.',
        'faculty' => ['Dr. Amina Mohammed', 'Prof. Chinedu Okeke'],
        'projects' => 8,
        'icon' => 'clinical'
    ],
    [
        'id' => 'community-health',
        'title' => 'Community Health Nursing',
        'description' => 'Population health, disease prevention, health promotion, and community-based interventions in tropical regions.',
        'faculty' => ['Dr. Fatima Bello', 'Prof. Ibrahim Musa'],
        'projects' => 12,
        'icon' => 'community'
    ],
    [
        'id' => 'nursing-education',
        'title' => 'Nursing Education & Pedagogy',
        'description' => 'Innovative teaching methods, curriculum development, simulation training, and educational technology in nursing.',
        'faculty' => ['Dr. Grace Johnson', 'Prof. Ahmed Yusuf'],
        'projects' => 6,
        'icon' => 'education'
    ],
    [
        'id' => 'mental-health',
        'title' => 'Mental Health Nursing',
        'description' => 'Psychiatric nursing interventions, mental health promotion, stigma reduction, and community mental health services.',
        'faculty' => ['Dr. Sarah Adeyemi', 'Prof. Tunde Okafor'],
        'projects' => 5,
        'icon' => 'mental'
    ],
    [
        'id' => 'maternal-child',
        'title' => 'Maternal & Child Health',
        'description' => 'Reproductive health, antenatal care, neonatal outcomes, and child health interventions in resource-limited settings.',
        'faculty' => ['Dr. Ngozi Chukwu', 'Prof. Bola Adekunle'],
        'projects' => 10,
        'icon' => 'maternal'
    ],
    [
        'id' => 'health-policy',
        'title' => 'Health Policy & Systems',
        'description' => 'Healthcare delivery systems, policy analysis, nursing workforce development, and healthcare financing in Nigeria.',
        'faculty' => ['Dr. Emeka Nwankwo', 'Prof. Zainab Ibrahim'],
        'projects' => 7,
        'icon' => 'policy'
    ]
];

$publications = [
    [
        'title' => 'Effectiveness of Community-Based Interventions in Reducing Malaria Prevalence in Rural Nigeria',
        'authors' => 'Mohammed, A., Okeke, C., Bello, F.',
        'journal' => 'Journal of Tropical Nursing',
        'year' => '2023',
        'volume' => '12',
        'issue' => '3',
        'pages' => '45-58',
        'doi' => '10.1007/s12345-023-00123-4',
        'category' => 'community-health'
    ],
    [
        'title' => 'Digital Simulation Training for Clinical Decision-Making in Emergency Nursing',
        'authors' => 'Johnson, G., Yusuf, A., Adeyemi, S.',
        'journal' => 'International Journal of Nursing Education',
        'year' => '2023',
        'volume' => '15',
        'issue' => '2',
        'pages' => '112-125',
        'doi' => '10.1016/j.ijned.2023.04.003',
        'category' => 'nursing-education'
    ],
    [
        'title' => 'Barriers to Mental Health Service Utilization Among Urban Youth in Northern Nigeria',
        'authors' => 'Adeyemi, S., Okafor, T., Chukwu, N.',
        'journal' => 'African Journal of Psychiatry',
        'year' => '2022',
        'volume' => '25',
        'issue' => '4',
        'pages' => '201-215',
        'doi' => '10.4314/ajpsy.v25i4.3',
        'category' => 'mental-health'
    ],
    [
        'title' => 'Nurse-Led Antenatal Care and Pregnancy Outcomes in Primary Health Centers',
        'authors' => 'Chukwu, N., Adekunle, B., Nwankwo, E.',
        'journal' => 'Journal of Maternal and Child Health',
        'year' => '2022',
        'volume' => '18',
        'issue' => '1',
        'pages' => '33-47',
        'doi' => '10.1080/1742514X.2022.2056789',
        'category' => 'maternal-child'
    ],
    [
        'title' => 'Workforce Challenges and Nursing Retention Strategies in Nigerian Public Hospitals',
        'authors' => 'Nwankwo, E., Ibrahim, Z., Mohammed, A.',
        'journal' => 'Health Policy and Planning',
        'year' => '2022',
        'volume' => '37',
        'issue' => '6',
        'pages' => '789-802',
        'doi' => '10.1093/heapol/czac012',
        'category' => 'health-policy'
    ]
];

$ongoingProjects = [
    [
        'title' => 'Telehealth Interventions for Chronic Disease Management in Rural Communities',
        'investigators' => ['Dr. Amina Mohammed', 'Dr. Fatima Bello'],
        'funder' => 'National Institutes of Health (NIH)',
        'duration' => '2022-2025',
        'budget' => '₦25,000,000',
        'status' => 'active'
    ],
    [
        'title' => 'Development of Culturally-Sensitive Mental Health Screening Tools for Nigerian Adolescents',
        'investigators' => ['Dr. Sarah Adeyemi', 'Prof. Tunde Okafor'],
        'funder' => 'African Mental Health Foundation',
        'duration' => '2023-2024',
        'budget' => '₦12,000,000',
        'status' => 'active'
    ],
    [
        'title' => 'Impact of Simulation-Based Training on Nursing Students\' Clinical Competence',
        'investigators' => ['Dr. Grace Johnson', 'Prof. Ahmed Yusuf'],
        'funder' => 'Tertiary Education Trust Fund (TETFund)',
        'duration' => '2022-2024',
        'budget' => '₦18,500,000',
        'status' => 'active'
    ],
    [
        'title' => 'Community-Based Prevention of Non-Communicable Diseases in Urban Slums',
        'investigators' => ['Prof. Chinedu Okeke', 'Dr. Ngozi Chukwu'],
        'funder' => 'World Health Organization (WHO)',
        'duration' => '2023-2026',
        'budget' => '₦32,000,000',
        'status' => 'planning'
    ]
];

$researchFacilities = [
    [
        'name' => 'Simulation Laboratory',
        'description' => 'High-fidelity simulation manikins and equipment for clinical skills training and research.',
        'features' => ['Adult and pediatric manikins', 'Vital signs monitors', 'Emergency response equipment', 'Video recording system'],
        'contact' => 'Dr. Grace Johnson'
    ],
    [
        'name' => 'Nursing Research Center',
        'description' => 'Dedicated space for research activities including data analysis and collaborative work.',
        'features' => ['Statistical software licenses', 'Qualitative analysis tools', 'Conference facilities', 'Research library'],
        'contact' => 'Prof. Chinedu Okeke'
    ],
    [
        'name' => 'Community Health Research Unit',
        'description' => 'Mobile research unit for community-based studies and field data collection.',
        'features' => ['Portable medical equipment', 'Data collection tablets', 'Mobile laboratory', 'Fieldwork kits'],
        'contact' => 'Dr. Fatima Bello'
    ],
    [
        'name' => 'Digital Health Innovation Lab',
        'description' => 'Technology-enabled research space for telehealth and digital health interventions.',
        'features' => ['Telemedicine equipment', 'Mobile health apps development', 'Data visualization tools', 'Virtual reality training'],
        'contact' => 'Dr. Emeka Nwankwo'
    ]
];

$collaborations = [
    ['name' => 'University of Ibadan', 'country' => 'Nigeria', 'type' => 'Academic'],
    ['name' => 'Johns Hopkins University', 'country' => 'USA', 'type' => 'International'],
    ['name' => 'University of Ghana', 'country' => 'Ghana', 'type' => 'Regional'],
    ['name' => 'Federal Ministry of Health', 'country' => 'Nigeria', 'type' => 'Government'],
    ['name' => 'World Health Organization', 'country' => 'International', 'type' => 'International'],
    ['name' => 'African Population and Health Research Center', 'country' => 'Kenya', 'type' => 'Regional']
];
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
    --color-info: #3182ce;
    
    /* Research-specific colors */
    --color-research-clinical: #4299e1;
    --color-research-community: #38b2ac;
    --color-research-education: #9f7aea;
    --color-research-mental: #ed64a6;
    --color-research-maternal: #ed8936;
    --color-research-policy: #667eea;
    
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

/* Base Styles */
.research-container {
    font-family: var(--font-body);
    line-height: 1.6;
    color: var(--color-gray-700);
}

/* Hero Header */
.hero-header {
    background: linear-gradient(135deg, 
        rgba(44, 82, 130, 0.95) 0%, 
        rgba(26, 54, 93, 0.9) 100%), 
        url('<?php echo $baseUrl; ?>/assets/images/research/hero-bg.jpg');
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

/* Research Areas */
.research-areas-section {
    background-color: var(--color-white);
}

.research-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.research-card {
    background-color: var(--color-white);
    border-radius: var(--radius-xl);
    padding: var(--spacing-2xl);
    border: 1px solid var(--color-gray-200);
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-base);
    position: relative;
    overflow: hidden;
}

.research-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.research-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
    border-color: transparent;
}

.research-card.clinical::before {
    background: linear-gradient(90deg, var(--color-research-clinical), #63b3ed);
}

.research-card.community::before {
    background: linear-gradient(90deg, var(--color-research-community), #4fd1c7);
}

.research-card.education::before {
    background: linear-gradient(90deg, var(--color-research-education), #b794f4);
}

.research-card.mental::before {
    background: linear-gradient(90deg, var(--color-research-mental), #f687b3);
}

.research-card.maternal::before {
    background: linear-gradient(90deg, var(--color-research-maternal), #f6ad55);
}

.research-card.policy::before {
    background: linear-gradient(90deg, var(--color-research-policy), #7c9cff);
}

.research-icon {
    width: 64px;
    height: 64px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--spacing-lg);
}

.research-card.clinical .research-icon {
    background-color: rgba(66, 153, 225, 0.1);
    color: var(--color-research-clinical);
}

.research-card.community .research-icon {
    background-color: rgba(56, 178, 172, 0.1);
    color: var(--color-research-community);
}

.research-card.education .research-icon {
    background-color: rgba(159, 122, 234, 0.1);
    color: var(--color-research-education);
}

.research-card.mental .research-icon {
    background-color: rgba(237, 100, 166, 0.1);
    color: var(--color-research-mental);
}

.research-card.maternal .research-icon {
    background-color: rgba(237, 137, 54, 0.1);
    color: var(--color-research-maternal);
}

.research-card.policy .research-icon {
    background-color: rgba(102, 126, 234, 0.1);
    color: var(--color-research-policy);
}

.research-card h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.5rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-md);
}

.research-card p {
    color: var(--color-gray-600);
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
}

.research-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--color-gray-200);
}

.research-faculty {
    font-size: 0.875rem;
    color: var(--color-gray-600);
}

.research-projects {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    background-color: var(--color-gray-100);
    color: var(--color-gray-700);
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 600;
}

/* Publications Section */
.publications-section {
    background: linear-gradient(135deg, 
        rgba(44, 82, 130, 0.05) 0%, 
        rgba(26, 54, 93, 0.05) 100%);
}

.publications-filter {
    display: flex;
    justify-content: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-xl);
    flex-wrap: wrap;
}

.filter-btn {
    padding: var(--spacing-sm) var(--spacing-lg);
    background-color: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--radius-lg);
    font-family: var(--font-heading);
    font-weight: 600;
    color: var(--color-gray-600);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.filter-btn:hover {
    background-color: var(--color-gray-50);
    border-color: var(--color-gray-300);
}

.filter-btn.active {
    background-color: var(--color-primary);
    color: var(--color-white);
    border-color: var(--color-primary);
}

.publications-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.publication-item {
    background-color: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    border: 1px solid var(--color-gray-200);
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-base);
}

.publication-item:hover {
    transform: translateX(4px);
    box-shadow: var(--shadow-md);
    border-color: var(--color-primary-light);
}

.publication-header {
    margin-bottom: var(--spacing-md);
}

.publication-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.25rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-xs);
    line-height: 1.4;
}

.publication-authors {
    color: var(--color-gray-600);
    font-size: 0.875rem;
    font-style: italic;
    margin-bottom: var(--spacing-sm);
}

.publication-details {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    font-size: 0.875rem;
    color: var(--color-gray-600);
}

.publication-detail {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.publication-detail svg {
    width: 16px;
    height: 16px;
    color: var(--color-primary);
}

.publication-actions {
    display: flex;
    gap: var(--spacing-md);
    flex-wrap: wrap;
}

.publication-action {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: var(--color-primary);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    transition: color var(--transition-fast);
}

.publication-action:hover {
    color: var(--color-primary-dark);
    text-decoration: underline;
}

/* Ongoing Projects */
.projects-section {
    background-color: var(--color-white);
}

.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.project-card {
    background-color: var(--color-white);
    border-radius: var(--radius-xl);
    padding: var(--spacing-2xl);
    border: 1px solid var(--color-gray-200);
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-base);
    position: relative;
}

.project-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--color-primary-light);
}

.project-status {
    position: absolute;
    top: var(--spacing-xl);
    right: var(--spacing-xl);
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-active {
    background-color: rgba(56, 161, 105, 0.1);
    color: var(--color-success);
}

.status-planning {
    background-color: rgba(214, 158, 46, 0.1);
    color: var(--color-warning);
}

.project-card h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.25rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-md);
    padding-right: 80px;
}

.project-investigators {
    color: var(--color-gray-600);
    font-size: 0.875rem;
    margin-bottom: var(--spacing-lg);
}

.project-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--color-gray-200);
}

.project-detail {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.project-label {
    font-size: 0.75rem;
    color: var(--color-gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}

.project-value {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-gray-800);
    font-size: 0.875rem;
}

/* Research Facilities */
.facilities-section {
    background: linear-gradient(135deg, 
        rgba(56, 161, 105, 0.05) 0%, 
        rgba(47, 133, 90, 0.05) 100%);
}

.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.facility-card {
    background-color: var(--color-white);
    border-radius: var(--radius-xl);
    padding: var(--spacing-2xl);
    border: 1px solid var(--color-gray-200);
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-base);
}

.facility-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--color-success);
}

.facility-card h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.25rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-md);
}

.facility-card p {
    color: var(--color-gray-600);
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
}

.facility-features {
    list-style: none;
    padding: 0;
    margin: 0 0 var(--spacing-lg);
}

.facility-features li {
    padding: var(--spacing-xs) 0;
    color: var(--color-gray-700);
    position: relative;
    padding-left: var(--spacing-lg);
}

.facility-features li::before {
    content: '';
    position: absolute;
    left: 0;
    top: var(--spacing-md);
    width: 8px;
    height: 8px;
    background-color: var(--color-success);
    border-radius: 50%;
}

.facility-contact {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--color-gray-200);
    color: var(--color-gray-600);
    font-size: 0.875rem;
}

.facility-contact svg {
    width: 16px;
    height: 16px;
    color: var(--color-primary);
}

/* Collaborations */
.collaborations-section {
    background-color: var(--color-white);
}

.collaborations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.collaboration-card {
    background-color: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    border: 1px solid var(--color-gray-200);
    text-align: center;
    transition: all var(--transition-base);
}

.collaboration-card:hover {
    transform: translateY(-4px);
    border-color: var(--color-primary-light);
    box-shadow: var(--shadow-md);
}

.collaboration-name {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-xs);
    font-size: 1.125rem;
}

.collaboration-country {
    color: var(--color-gray-600);
    font-size: 0.875rem;
    margin-bottom: var(--spacing-sm);
}

.collaboration-type {
    display: inline-block;
    padding: var(--spacing-xs) var(--spacing-sm);
    background-color: var(--color-gray-100);
    color: var(--color-gray-700);
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Call to Action */
.cta-section {
    background: linear-gradient(135deg, 
        var(--color-primary) 0%, 
        var(--color-primary-dark) 100%);
    color: var(--color-white);
    padding: var(--spacing-3xl) 0;
}

.cta-content {
    text-align: center;
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
    .hero-title {
        font-size: 2.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .research-grid,
    .projects-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
}

@media (max-width: 768px) {
    .content-section {
        padding: var(--spacing-2xl) 0;
    }
    
    .research-grid,
    .projects-grid,
    .facilities-grid,
    .collaborations-grid {
        grid-template-columns: 1fr;
    }
    
    .publications-filter {
        flex-direction: column;
        align-items: center;
    }
    
    .filter-btn {
        width: 100%;
        max-width: 200px;
    }
    
    .project-details {
        grid-template-columns: 1fr;
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
    
    .research-card,
    .project-card,
    .facility-card {
        padding: var(--spacing-xl);
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

.hidden {
    display: none !important;
}
</style>

<!-- Main Content -->
<main id="main-content" class="research-container" role="main" aria-label="Research information">
    <!-- Skip to Content Link -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>

    <!-- Hero Header -->
    <header class="hero-header" role="banner">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Advancing Nursing Through Research</h1>
                <p class="hero-subtitle">
                    Driving innovation in healthcare through evidence-based research, 
                    collaborative partnerships, and transformative discoveries in nursing science.
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
                <span class="breadcrumb-current" aria-current="page">Research</span>
            </div>
        </div>
    </nav>

    <!-- Research Areas -->
    <section class="content-section research-areas-section" id="research-areas">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Research Focus Areas</h2>
                <p class="section-description">
                    Our research spans diverse areas of nursing and healthcare, addressing critical 
                    challenges and advancing evidence-based practice.
                </p>
            </header>
            
            <div class="research-grid">
                <?php foreach ($researchAreas as $area): ?>
                <article class="research-card <?php echo $area['icon']; ?>" data-category="<?php echo $area['id']; ?>">
                    <div class="research-icon">
                        <?php if ($area['icon'] === 'clinical'): ?>
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd"/>
                        </svg>
                        <?php elseif ($area['icon'] === 'community'): ?>
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/>
                        </svg>
                        <?php elseif ($area['icon'] === 'education'): ?>
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                        <?php elseif ($area['icon'] === 'mental'): ?>
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                        </svg>
                        <?php elseif ($area['icon'] === 'maternal'): ?>
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        <?php else: ?>
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2h1v2a2 2 0 01-2 2h-1v2a2 2 0 002 2h12a2 2 0 002-2v-2h-1a2 2 0 01-2-2v-2h1a2 2 0 002-2V6a2 2 0 00-2-2H4z" clip-rule="evenodd"/>
                        </svg>
                        <?php endif; ?>
                    </div>
                    <h3><?php echo htmlspecialchars($area['title']); ?></h3>
                    <p><?php echo htmlspecialchars($area['description']); ?></p>
                    <div class="research-meta">
                        <div class="research-faculty">
                            <strong>Faculty Leads:</strong> <?php echo implode(', ', $area['faculty']); ?>
                        </div>
                        <div class="research-projects">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo $area['projects']; ?> Projects
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Recent Publications -->
    <section class="content-section publications-section" id="publications">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Recent Publications</h2>
                <p class="section-description">
                    Selected peer-reviewed publications by our faculty and research teams
                </p>
            </header>
            
            <div class="publications-filter">
                <button class="filter-btn active" data-filter="all">All Publications</button>
                <?php 
                $uniqueCategories = array_unique(array_column($publications, 'category'));
                foreach ($uniqueCategories as $category): 
                    $categoryName = str_replace('-', ' ', $category);
                ?>
                <button class="filter-btn" data-filter="<?php echo $category; ?>">
                    <?php echo ucwords($categoryName); ?>
                </button>
                <?php endforeach; ?>
            </div>
            
            <div class="publications-list">
                <?php foreach ($publications as $publication): ?>
                <article class="publication-item" data-category="<?php echo $publication['category']; ?>">
                    <div class="publication-header">
                        <h3 class="publication-title"><?php echo htmlspecialchars($publication['title']); ?></h3>
                        <div class="publication-authors"><?php echo htmlspecialchars($publication['authors']); ?></div>
                    </div>
                    
                    <div class="publication-details">
                        <div class="publication-detail">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo htmlspecialchars($publication['year']); ?>
                        </div>
                        
                        <div class="publication-detail">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                            </svg>
                            <?php echo htmlspecialchars($publication['journal']); ?>
                        </div>
                        
                        <div class="publication-detail">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Vol. <?php echo htmlspecialchars($publication['volume']); ?>, Issue <?php echo htmlspecialchars($publication['issue']); ?>, pp. <?php echo htmlspecialchars($publication['pages']); ?>
                        </div>
                        
                        <?php if (!empty($publication['doi'])): ?>
                        <div class="publication-detail">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                            </svg>
                            DOI: <?php echo htmlspecialchars($publication['doi']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="publication-actions">
                        <a href="https://doi.org/<?php echo htmlspecialchars($publication['doi']); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="publication-action">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                            </svg>
                            View Article
                        </a>
                        <a href="#" class="publication-action">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Ongoing Research Projects -->
    <section class="content-section projects-section" id="projects">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Ongoing Research Projects</h2>
                <p class="section-description">
                    Currently funded research initiatives and collaborations
                </p>
            </header>
            
            <div class="projects-grid">
                <?php foreach ($ongoingProjects as $project): ?>
                <article class="project-card">
                    <div class="project-status <?php echo 'status-' . $project['status']; ?>">
                        <?php echo $project['status']; ?>
                    </div>
                    <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                    <div class="project-investigators">
                        <strong>Principal Investigators:</strong> <?php echo implode(', ', $project['investigators']); ?>
                    </div>
                    
                    <div class="project-details">
                        <div class="project-detail">
                            <span class="project-label">Funding Agency</span>
                            <span class="project-value"><?php echo htmlspecialchars($project['funder']); ?></span>
                        </div>
                        <div class="project-detail">
                            <span class="project-label">Duration</span>
                            <span class="project-value"><?php echo htmlspecialchars($project['duration']); ?></span>
                        </div>
                        <div class="project-detail">
                            <span class="project-label">Budget</span>
                            <span class="project-value"><?php echo htmlspecialchars($project['budget']); ?></span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Research Facilities -->
    <section class="content-section facilities-section" id="facilities">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Research Facilities & Resources</h2>
                <p class="section-description">
                    State-of-the-art facilities supporting cutting-edge nursing research
                </p>
            </header>
            
            <div class="facilities-grid">
                <?php foreach ($researchFacilities as $facility): ?>
                <article class="facility-card">
                    <h3><?php echo htmlspecialchars($facility['name']); ?></h3>
                    <p><?php echo htmlspecialchars($facility['description']); ?></p>
                    
                    <ul class="facility-features">
                        <?php foreach ($facility['features'] as $feature): ?>
                        <li><?php echo htmlspecialchars($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="facility-contact">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Contact: <?php echo htmlspecialchars($facility['contact']); ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Research Collaborations -->
    <section class="content-section collaborations-section" id="collaborations">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Research Collaborations</h2>
                <p class="section-description">
                    Partnerships with leading institutions and organizations worldwide
                </p>
            </header>
            
            <div class="collaborations-grid">
                <?php foreach ($collaborations as $collaboration): ?>
                <div class="collaboration-card">
                    <div class="collaboration-name"><?php echo htmlspecialchars($collaboration['name']); ?></div>
                    <div class="collaboration-country"><?php echo htmlspecialchars($collaboration['country']); ?></div>
                    <div class="collaboration-type"><?php echo htmlspecialchars($collaboration['type']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="content-section cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="section-title" style="color: var(--color-white);">Join Our Research Community</h2>
                <p style="font-size: 1.125rem; opacity: 0.95; margin-bottom: var(--spacing-xl);">
                    Interested in collaborating or learning more about our research initiatives?
                </p>
                
                <div class="cta-buttons">
                    <a href="<?php echo $baseUrl; ?>/contact?subject=Research%20Collaboration" 
                       class="btn btn-light btn-lg">
                        Contact Research Office
                    </a>
                    <a href="<?php echo $baseUrl; ?>/faculty" 
                       class="btn btn-outline-light btn-lg">
                        Meet Our Researchers
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
    
    // Publication filtering
    const filterButtons = document.querySelectorAll('.filter-btn');
    const publicationItems = document.querySelectorAll('.publication-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter publications
            publicationItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, 50);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // Smooth scrolling
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
                
                if (history.pushState) {
                    history.pushState(null, null, href);
                }
                
                target.setAttribute('tabindex', '-1');
                target.focus({ preventScroll: true });
            }
        });
    });
    
    // Research card animations
    const researchCards = document.querySelectorAll('.research-card');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    researchCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });
    
    // Publication search functionality
    const searchInput = document.createElement('input');
    searchInput.type = 'search';
    searchInput.placeholder = 'Search publications...';
    searchInput.className = 'search-input';
    searchInput.style.cssText = `
        padding: var(--spacing-md);
        border: 1px solid var(--color-gray-300);
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 400px;
        margin: 0 auto var(--spacing-xl);
        display: block;
        font-family: var(--font-body);
    `;
    
    const publicationsContainer = document.querySelector('.publications-section .container');
    if (publicationsContainer) {
        const filterSection = publicationsContainer.querySelector('.publications-filter');
        filterSection.parentNode.insertBefore(searchInput, filterSection);
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            publicationItems.forEach(item => {
                const title = item.querySelector('.publication-title').textContent.toLowerCase();
                const authors = item.querySelector('.publication-authors').textContent.toLowerCase();
                const journal = item.querySelectorAll('.publication-detail')[1]?.textContent.toLowerCase() || '';
                
                if (title.includes(searchTerm) || authors.includes(searchTerm) || journal.includes(searchTerm)) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, 50);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    }
    
    // Export publications as CSV
    const exportButton = document.createElement('button');
    exportButton.className = 'btn btn-outline';
    exportButton.innerHTML = `
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="margin-right: 8px;">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
        Export Publications
    `;
    
    exportButton.addEventListener('click', function() {
        const publicationsData = <?php echo json_encode($publications); ?>;
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Title,Authors,Journal,Year,Volume,Issue,Pages,DOI\n";
        
        publicationsData.forEach(pub => {
            const row = [
                `"${pub.title}"`,
                `"${pub.authors}"`,
                `"${pub.journal}"`,
                pub.year,
                pub.volume,
                pub.issue,
                `"${pub.pages}"`,
                pub.doi
            ].join(',');
            csvContent += row + "\n";
        });
        
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'fctcns-publications.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
    
    // Add export button to publications section
    const publicationsHeader = document.querySelector('.publications-section .section-header');
    if (publicationsHeader) {
        const buttonContainer = document.createElement('div');
        buttonContainer.style.marginTop = 'var(--spacing-md)';
        buttonContainer.appendChild(exportButton);
        publicationsHeader.appendChild(buttonContainer);
    }
});
</script>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>