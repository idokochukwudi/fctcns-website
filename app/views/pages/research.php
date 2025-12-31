<?php
/**
 * Research Page View - MVC Version
 * 
 * Available variables from PageController:
 * - $research: Array of research publications from database
 * - $publications: Same as $research (for compatibility)
 * - $categories: Array of research categories
 * - $baseUrl, $page_title, $page_description, $currentPage
 * 
 * @package FCTCNS
 * @version 3.0
 */

// Extract data passed from controller
extract($data ?? []);

// Helper function for escaping output
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// Set defaults
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '/fctcns-website');
$page_title = $page_title ?? 'Research - Federal College of Nursing Sciences';
$page_description = $page_description ?? 'Research initiatives, publications, and innovation in nursing and healthcare sciences';
$currentPage = $currentPage ?? 'research';

// Use real data from database
$publications = $publications ?? $research ?? [];
$categories = $categories ?? [];

// Transform database categories to research areas format
$researchAreas = [];
if (!empty($categories)) {
    foreach ($categories as $category) {
        // Count publications per category
        $count = 0;
        foreach ($publications as $pub) {
            if ($pub['research_area'] === $category['slug']) {
                $count++;
            }
        }
        
        $researchAreas[] = [
            'id' => $category['slug'],
            'title' => $category['name'],
            'description' => $category['description'] ?? 'Research in ' . $category['name'],
            'faculty' => ['Dr. Faculty Lead'], // You can update this later
            'projects' => $count,
            'icon' => strtolower(str_replace(' ', '-', substr($category['name'], 0, 10)))
        ];
    }
}

// Get unique categories from publications for filtering
$uniqueCategories = [];
foreach ($publications as $pub) {
    if (!empty($pub['research_area']) && !in_array($pub['research_area'], $uniqueCategories)) {
        $uniqueCategories[] = $pub['research_area'];
    }
}

// Keep your hardcoded data for other sections (projects, facilities, collaborations)
$ongoingProjects = $ongoingProjects ?? [
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
    ]
];

$researchFacilities = $researchFacilities ?? [
    [
        'name' => 'Simulation Laboratory',
        'description' => 'High-fidelity simulation manikins and equipment for clinical skills training and research.',
        'features' => ['Adult and pediatric manikins', 'Vital signs monitors', 'Emergency response equipment', 'Video recording system'],
        'contact' => 'Dr. Grace Johnson'
    ]
];

$collaborations = $collaborations ?? [
    ['name' => 'University of Ibadan', 'country' => 'Nigeria', 'type' => 'Academic'],
    ['name' => 'Johns Hopkins University', 'country' => 'USA', 'type' => 'International'],
    ['name' => 'University of Ghana', 'country' => 'Ghana', 'type' => 'Academic'],
    ['name' => 'University of Nairobi', 'country' => 'Kenya', 'type' => 'Academic']
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
</style>
<style>
/* ===== INTERNATIONAL RESEARCH STANDARDS ===== */
:root {
    /* Professional Color Palette - Academic & Research Focus */
    --color-oxford-blue: #002147;      /* Prestigious academic blue */
    --color-imperial-red: #D32F2F;     /* Darker red for better contrast */
    --color-research-gold: #B8860B;    /* Darker gold for better visibility */
    --color-slate-gray: #37474F;       /* Darker gray for text */
    --color-forest-green: #2E7D32;     /* Health & science - darker green */
    --color-teal-blue: #00695C;        /* Darker teal */
    --color-platinum: #F5F5F5;         /* Light backgrounds */
    --color-charcoal: #263238;         /* Dark text for better readability */
    
    /* Research Category Colors */
    --color-clinical: #1565C0;         /* Darker Blue */
    --color-community: #00796B;        /* Darker Teal */
    --color-education: #6A1B9A;        /* Darker Purple */
    --color-mental: #AD1457;           /* Darker Pink */
    --color-maternal: #EF6C00;         /* Darker Orange */
    --color-policy: #2E7D32;           /* Darker Green */
    --color-global: #558B2F;           /* Darker Lime Green */
    --color-digital: #7B1FA2;          /* Darker Orchid */
    
    /* Typography - Professional Academic */
    --font-display: 'Georgia', 'Times New Roman', serif;
    --font-heading: 'Arial', 'Helvetica Neue', sans-serif;
    --font-body: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    --font-mono: 'Consolas', 'Monaco', monospace;
    
    /* Spacing System - 8px base */
    --space-unit: 8px;
    --space-xs: calc(var(--space-unit) * 0.5);  /* 4px */
    --space-sm: var(--space-unit);              /* 8px */
    --space-md: calc(var(--space-unit) * 2);    /* 16px */
    --space-lg: calc(var(--space-unit) * 3);    /* 24px */
    --space-xl: calc(var(--space-unit) * 4);    /* 32px */
    --space-2xl: calc(var(--space-unit) * 6);   /* 48px */
    --space-3xl: calc(var(--space-unit) * 8);   /* 64px */
    --space-4xl: calc(var(--space-unit) * 12);  /* 96px */
    
    /* Border Radius */
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
    --radius-full: 9999px;
    
    /* Shadows - Professional depth */
    --shadow-subtle: 0 1px 3px rgba(0, 33, 71, 0.12);
    --shadow-elevated: 0 4px 20px rgba(0, 33, 71, 0.15);
    --shadow-prominent: 0 8px 40px rgba(0, 33, 71, 0.2);
    --shadow-accent: 0 3px 15px rgba(211, 47, 47, 0.2);
    
    /* Transitions */
    --transition-smooth: all 0.3s ease;
    --transition-bounce: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    
    /* Gradients */
    --gradient-hero: linear-gradient(135deg, var(--color-oxford-blue) 0%, #001933 100%);
    --gradient-accent: linear-gradient(90deg, var(--color-imperial-red) 0%, #C62828 100%);
    --gradient-research: linear-gradient(135deg, var(--color-teal-blue) 0%, var(--color-forest-green) 100%);
}

/* ===== BASE RESETS ===== */
.research-container {
    font-family: var(--font-body);
    line-height: 1.6;
    color: var(--color-charcoal);
    background-color: #ffffff;
    padding-top: 0 !important;
    margin-top: 0 !important;
    overflow-x: hidden;
}

/* Remove top margin from hero section */
.hero-header {
    margin-top: 0;
    padding-top: 0;
}

/* ===== HERO SECTION - ACADEMIC STANDARD ===== */
.hero-header {
    background: var(--gradient-hero);
    color: white;
    padding: var(--space-3xl) 0 var(--space-2xl);
    position: relative;
    overflow: hidden;
    margin-top: 0;
}

.hero-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(184, 134, 11, 0.05) 0%, transparent 50%);
    opacity: 0.2;
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--space-md);
}

.hero-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 2.8rem;
    line-height: 1.2;
    margin-bottom: var(--space-md);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    letter-spacing: -0.5px;
    color: white;
}

.hero-subtitle {
    font-size: 1.125rem;
    opacity: 0.95;
    line-height: 1.6;
    margin-bottom: var(--space-xl);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    font-weight: 300;
    color: rgba(255, 255, 255, 0.95);
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: var(--space-xl);
    margin-top: var(--space-xl);
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2.2rem;
    color: var(--color-research-gold);
    line-height: 1;
    margin-bottom: var(--space-xs);
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.9);
}

/* ===== BREADCRUMB ===== */
.breadcrumb {
    background-color: var(--color-platinum);
    padding: var(--space-sm) 0;
    border-bottom: 1px solid rgba(0, 33, 71, 0.1);
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-size: 0.875rem;
}

.breadcrumb-nav a {
    color: var(--color-slate-gray);
    text-decoration: none;
    transition: var(--transition-smooth);
    font-weight: 500;
}

.breadcrumb-nav a:hover {
    color: var(--color-oxford-blue);
    text-decoration: underline;
}

.breadcrumb-separator {
    color: var(--color-slate-gray);
    opacity: 0.5;
}

.breadcrumb-current {
    color: var(--color-oxford-blue);
    font-weight: 600;
}

/* ===== SECTION COMMON STYLES ===== */
.content-section {
    padding: var(--space-2xl) 0;
}

.section-header {
    text-align: center;
    margin-bottom: var(--space-xl);
    position: relative;
}

.section-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2rem;
    color: var(--color-oxford-blue);
    margin-bottom: var(--space-md);
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background: var(--gradient-accent);
    border-radius: var(--radius-full);
}

.section-description {
    font-size: 1rem;
    color: var(--color-slate-gray);
    max-width: 800px;
    margin: var(--space-md) auto 0;
    line-height: 1.6;
}

/* ===== RESEARCH AREAS - GRID LAYOUT ===== */
.research-areas-section {
    background-color: #f8f9fa;
}

.research-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: var(--space-lg);
    margin-top: var(--space-lg);
}

.research-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    box-shadow: var(--shadow-subtle);
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(0, 33, 71, 0.1);
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
    transform: translateY(-5px);
    box-shadow: var(--shadow-elevated);
    border-color: transparent;
}

/* Category-specific colors */
.research-card.clinical::before { background: var(--color-clinical); }
.research-card.community::before { background: var(--color-community); }
.research-card.education::before { background: var(--color-education); }
.research-card.mental::before { background: var(--color-mental); }
.research-card.maternal::before { background: var(--color-maternal); }
.research-card.policy::before { background: var(--color-policy); }

.research-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--space-md);
    background: rgba(0, 33, 71, 0.05);
    color: var(--color-oxford-blue);
}

.research-card h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.25rem;
    color: var(--color-oxford-blue);
    margin-bottom: var(--space-sm);
    line-height: 1.3;
}

.research-card p {
    color: var(--color-slate-gray);
    line-height: 1.6;
    margin-bottom: var(--space-md);
    font-size: 0.95rem;
}

.research-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: var(--space-md);
    padding-top: var(--space-md);
    border-top: 1px solid rgba(0, 33, 71, 0.1);
    flex-wrap: wrap;
    gap: var(--space-sm);
}

.research-faculty {
    font-size: 0.85rem;
    color: var(--color-slate-gray);
    flex: 1;
    min-width: 150px;
}

.research-projects {
    display: inline-flex;
    align-items: center;
    gap: var(--space-xs);
    background: linear-gradient(135deg, var(--color-oxford-blue), var(--color-slate-gray));
    color: white;
    padding: var(--space-xs) var(--space-md);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ===== PUBLICATIONS SECTION - ACADEMIC STYLE ===== */
.publications-section {
    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
}

.publications-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--space-lg);
    flex-wrap: wrap;
    gap: var(--space-md);
}

.publications-controls {
    display: flex;
    gap: var(--space-md);
    align-items: center;
    flex-wrap: wrap;
}

.search-container {
    position: relative;
    width: 280px;
    min-width: 200px;
}

.search-input {
    width: 100%;
    padding: var(--space-sm) var(--space-md);
    padding-left: 40px;
    border: 1px solid rgba(0, 33, 71, 0.2);
    border-radius: var(--radius-full);
    font-family: var(--font-body);
    font-size: 0.9rem;
    transition: var(--transition-smooth);
    background: white;
    color: var(--color-charcoal);
}

.search-input:focus {
    outline: none;
    border-color: var(--color-oxford-blue);
    box-shadow: 0 0 0 2px rgba(0, 33, 71, 0.1);
}

.search-icon {
    position: absolute;
    left: var(--space-md);
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-slate-gray);
    width: 18px;
    height: 18px;
}

.publications-filter {
    display: flex;
    gap: var(--space-xs);
    flex-wrap: wrap;
}

.filter-btn {
    padding: var(--space-xs) var(--space-md);
    background: white;
    border: 1px solid rgba(0, 33, 71, 0.2);
    border-radius: var(--radius-full);
    font-family: var(--font-heading);
    font-weight: 600;
    color: var(--color-slate-gray);
    cursor: pointer;
    transition: var(--transition-smooth);
    font-size: 0.85rem;
}

.filter-btn:hover {
    background: rgba(0, 33, 71, 0.05);
    border-color: var(--color-oxford-blue);
    color: var(--color-oxford-blue);
}

.filter-btn.active {
    background: var(--color-oxford-blue);
    color: white;
    border-color: var(--color-oxford-blue);
}

.publications-list {
    display: grid;
    gap: var(--space-md);
}

.publication-item {
    background: white;
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    border-left: 4px solid var(--color-oxford-blue);
    box-shadow: var(--shadow-subtle);
    transition: var(--transition-smooth);
    position: relative;
}

.publication-item:hover {
    transform: translateX(4px);
    box-shadow: var(--shadow-elevated);
    border-left-color: var(--color-imperial-red);
}

.publication-header {
    margin-bottom: var(--space-md);
}

.publication-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--color-oxford-blue);
    margin-bottom: var(--space-xs);
    line-height: 1.4;
}

.publication-authors {
    color: var(--color-slate-gray);
    font-size: 0.9rem;
    font-style: italic;
    margin-bottom: var(--space-sm);
}

.publication-meta {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-md);
    margin-bottom: var(--space-md);
    font-size: 0.85rem;
    color: var(--color-slate-gray);
}

.publication-meta-item {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
}

.publication-meta-item svg {
    width: 14px;
    height: 14px;
    color: var(--color-oxford-blue);
    opacity: 0.7;
}

.publication-abstract {
    color: var(--color-charcoal);
    line-height: 1.6;
    margin-bottom: var(--space-md);
    font-size: 0.9rem;
    border-left: 2px solid var(--color-platinum);
    padding-left: var(--space-md);
}

.publication-actions {
    display: flex;
    gap: var(--space-sm);
    flex-wrap: wrap;
    border-top: 1px solid rgba(0, 33, 71, 0.1);
    padding-top: var(--space-md);
}

.publication-action {
    display: inline-flex;
    align-items: center;
    gap: var(--space-xs);
    color: var(--color-oxford-blue);
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    transition: var(--transition-smooth);
    padding: var(--space-xs) var(--space-md);
    border-radius: var(--radius-md);
    background: rgba(0, 33, 71, 0.05);
    border: none;
    cursor: pointer;
}

.publication-action:hover {
    background: var(--color-oxford-blue);
    color: white;
    text-decoration: none;
}

.publication-action.citation {
    background: rgba(211, 47, 47, 0.1);
    color: var(--color-imperial-red);
}

.publication-action.citation:hover {
    background: var(--color-imperial-red);
    color: white;
}

/* ===== PROJECTS TIMELINE VIEW ===== */
.projects-section {
    background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);
}

.projects-timeline {
    position: relative;
    max-width: 1200px;
    margin: var(--space-xl) auto;
    padding: var(--space-lg) 0;
}

.projects-timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    height: 100%;
    background: linear-gradient(to bottom, var(--color-oxford-blue), var(--color-teal-blue));
    opacity: 0.3;
}

.project-timeline-item {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: var(--space-xl);
    position: relative;
}

.project-timeline-item:nth-child(odd) .project-card {
    margin-right: 50%;
    margin-left: var(--space-lg);
}

.project-timeline-item:nth-child(even) .project-card {
    margin-left: 50%;
    margin-right: var(--space-lg);
}

.project-timeline-marker {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: white;
    border: 3px solid var(--color-oxford-blue);
    z-index: 1;
}

.project-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    box-shadow: var(--shadow-elevated);
    width: calc(50% - var(--space-xl));
    position: relative;
    transition: var(--transition-smooth);
}

.project-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-prominent);
}

.project-status {
    position: absolute;
    top: var(--space-md);
    right: var(--space-md);
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: rgba(46, 125, 50, 0.1);
    color: var(--color-forest-green);
}

.status-planning {
    background: rgba(184, 134, 11, 0.1);
    color: var(--color-research-gold);
}

.status-completed {
    background: rgba(21, 101, 192, 0.1);
    color: var(--color-clinical);
}

.project-card h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--color-oxford-blue);
    margin-bottom: var(--space-sm);
    line-height: 1.4;
    padding-right: 60px;
}

.project-investigators {
    color: var(--color-slate-gray);
    font-size: 0.9rem;
    margin-bottom: var(--space-md);
    padding-bottom: var(--space-sm);
    border-bottom: 1px solid rgba(0, 33, 71, 0.1);
}

.project-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: var(--space-md);
}

.project-detail {
    display: flex;
    flex-direction: column;
    gap: var(--space-xs);
}

.project-label {
    font-size: 0.7rem;
    color: var(--color-slate-gray);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.project-value {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-oxford-blue);
    font-size: 0.9rem;
}

/* ===== RESEARCH FACILITIES ===== */
.facilities-section {
    background: white;
}

.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--space-lg);
    margin-top: var(--space-lg);
}

.facility-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    box-shadow: var(--shadow-subtle);
    transition: var(--transition-smooth);
    border: 1px solid rgba(0, 33, 71, 0.1);
}

.facility-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-elevated);
    border-color: var(--color-teal-blue);
}

.facility-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--color-teal-blue), var(--color-forest-green));
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--space-md);
    color: white;
}

.facility-card h3 {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--color-oxford-blue);
    margin-bottom: var(--space-sm);
}

.facility-card p {
    color: var(--color-slate-gray);
    line-height: 1.6;
    margin-bottom: var(--space-md);
    font-size: 0.9rem;
}

.facility-features {
    list-style: none;
    padding: 0;
    margin: 0 0 var(--space-md);
}

.facility-features li {
    padding: var(--space-xs) 0;
    color: var(--color-charcoal);
    position: relative;
    padding-left: var(--space-md);
    font-size: 0.9rem;
    line-height: 1.5;
}

.facility-features li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--color-forest-green);
    font-weight: bold;
}

/* FIXED: Facility Director section */
.facility-contact {
    display: flex;
    align-items: center;
    gap: var(--space-md);
    padding-top: var(--space-md);
    border-top: 1px solid rgba(0, 33, 71, 0.1);
    color: var(--color-slate-gray);
    font-size: 0.85rem;
    font-weight: 500;
    flex-wrap: wrap;
    margin-top: auto;
}

.facility-contact-photo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--color-oxford-blue), var(--color-slate-gray));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.facility-contact-info {
    flex: 1;
    min-width: 150px;
}

.facility-contact-info strong {
    display: block;
    color: var(--color-oxford-blue);
    margin-bottom: 2px;
}

.facility-contact-info span {
    display: block;
    color: var(--color-slate-gray);
    font-size: 0.8rem;
}

/* ===== COLLABORATIONS WORLD MAP STYLE ===== */
.collaborations-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #eef5ff 100%);
    position: relative;
    overflow: hidden;
}

.world-map-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.05;
    background: 
        radial-gradient(circle at 20% 30%, var(--color-teal-blue) 0%, transparent 50%),
        radial-gradient(circle at 80% 70%, var(--color-forest-green) 0%, transparent 50%);
}

.collaborations-header {
    text-align: center;
    margin-bottom: var(--space-xl);
    position: relative;
    z-index: 1;
}

.region-filter {
    display: flex;
    justify-content: center;
    gap: var(--space-xs);
    margin-top: var(--space-md);
    flex-wrap: wrap;
}

.region-btn {
    padding: var(--space-xs) var(--space-md);
    background: white;
    border: 1px solid rgba(0, 33, 71, 0.2);
    border-radius: var(--radius-full);
    font-family: var(--font-heading);
    font-weight: 600;
    color: var(--color-slate-gray);
    cursor: pointer;
    transition: var(--transition-smooth);
    font-size: 0.85rem;
}

.region-btn:hover,
.region-btn.active {
    background: var(--color-oxford-blue);
    color: white;
    border-color: var(--color-oxford-blue);
}

.collaborations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: var(--space-lg);
    position: relative;
    z-index: 1;
}

.collaboration-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    text-align: center;
    transition: var(--transition-smooth);
    box-shadow: var(--shadow-subtle);
    border: 1px solid rgba(0, 33, 71, 0.1);
}

.collaboration-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-elevated);
    border-color: var(--color-oxford-blue);
}

.collaboration-flag {
    font-size: 2rem;
    margin-bottom: var(--space-sm);
    display: block;
    line-height: 1;
}

.collaboration-name {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-oxford-blue);
    margin-bottom: var(--space-xs);
    font-size: 1rem;
}

.collaboration-country {
    color: var(--color-slate-gray);
    font-size: 0.9rem;
    margin-bottom: var(--space-sm);
    font-weight: 500;
}

.collaboration-type {
    display: inline-block;
    padding: var(--space-xs) var(--space-md);
    background: rgba(0, 33, 71, 0.1);
    color: var(--color-oxford-blue);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ===== DATA & METRICS SECTION ===== */
.metrics-section {
    background: linear-gradient(135deg, var(--color-oxford-blue) 0%, #001a33 100%);
    color: white;
    padding: var(--space-2xl) 0;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: var(--space-lg);
    margin-top: var(--space-lg);
}

.metric-card {
    text-align: center;
    padding: var(--space-lg);
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-lg);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition-smooth);
}

.metric-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.15);
}

.metric-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-md);
    color: white;
}

.metric-value {
    display: block;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2rem;
    color: var(--color-research-gold);
    line-height: 1;
    margin-bottom: var(--space-xs);
}

.metric-label {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
}

/* ===== CALL TO ACTION ===== */
.cta-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #eef5ff 100%);
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 10% 20%, rgba(211, 47, 47, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 90% 80%, rgba(0, 105, 92, 0.05) 0%, transparent 50%);
}

.cta-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.cta-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2rem;
    color: var(--color-oxford-blue);
    margin-bottom: var(--space-md);
}

.cta-description {
    font-size: 1rem;
    color: var(--color-slate-gray);
    line-height: 1.6;
    margin-bottom: var(--space-xl);
}

.cta-buttons {
    display: flex;
    gap: var(--space-md);
    justify-content: center;
    flex-wrap: wrap;
}

/* ===== BUTTON STYLES ===== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-md) var(--space-lg);
    border-radius: var(--radius-full);
    font-family: var(--font-heading);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-smooth);
    border: 2px solid transparent;
    cursor: pointer;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
    min-height: 44px;
}

.btn-primary {
    background: var(--gradient-accent);
    color: white;
    box-shadow: var(--shadow-accent);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(211, 47, 47, 0.3);
}

.btn-secondary {
    background: var(--color-oxford-blue);
    color: white;
}

.btn-secondary:hover {
    background: #001a33;
    transform: translateY(-2px);
    box-shadow: var(--shadow-elevated);
}

.btn-outline {
    background: transparent;
    color: var(--color-oxford-blue);
    border-color: var(--color-oxford-blue);
}

.btn-outline:hover {
    background: var(--color-oxford-blue);
    color: white;
}

/* ===== UTILITY CLASSES ===== */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--space-md);
}

.text-center { text-align: center; }
.mb-0 { margin-bottom: 0 !important; }
.mt-0 { margin-top: 0 !important; }

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1200px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .projects-timeline::before {
        left: 30px;
    }
    
    .project-timeline-item:nth-child(odd) .project-card,
    .project-timeline-item:nth-child(even) .project-card {
        margin-left: 60px;
        margin-right: 0;
        width: calc(100% - 80px);
    }
    
    .project-timeline-marker {
        left: 30px;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.75rem;
    }
    
    .hero-stats {
        gap: var(--space-lg);
    }
    
    .stat-number {
        font-size: 1.75rem;
    }
    
    .research-grid,
    .facilities-grid,
    .collaborations-grid,
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .publications-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .publications-controls {
        flex-direction: column;
        width: 100%;
    }
    
    .search-container {
        width: 100%;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .facility-contact {
        flex-direction: column;
        text-align: center;
        gap: var(--space-sm);
    }
    
    .facility-contact-photo {
        margin: 0 auto;
    }
    
    .facility-contact-info {
        text-align: center;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 1.75rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .research-card,
    .project-card,
    .facility-card,
    .publication-item {
        padding: var(--space-md);
    }
    
    .hero-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-md);
    }
    
    .stat-item {
        padding: var(--space-sm);
    }
}

/* ===== ACCESSIBILITY ===== */
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
    outline: 3px solid var(--color-imperial-red);
    outline-offset: 2px;
}

/* ===== ANIMATIONS ===== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-in {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* ===== LOADING STATES ===== */
.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: var(--radius-md);
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ===== FIXES FOR SPACING ISSUES ===== */
/* Remove any default margins that might cause gaps */
main#main-content {
    margin: 0;
    padding: 0;
}

/* Ensure no gaps between sections */
.content-section:first-of-type {
    padding-top: var(--space-2xl);
}

/* Fix for empty state */
.empty-state {
    background: white;
    border-radius: var(--radius-lg);
    padding: var(--space-xl);
    text-align: center;
    color: var(--color-slate-gray);
    border: 1px solid rgba(0, 33, 71, 0.1);
}

.empty-state svg {
    margin-bottom: var(--space-md);
    opacity: 0.5;
}

.empty-state h3 {
    color: var(--color-oxford-blue);
    margin-bottom: var(--space-sm);
}
</style>

<!-- Main Content -->
<main id="main-content" class="research-container" role="main" aria-label="Research information">
    <!-- Skip to Content Link -->
    <a href="#main-content" class="skip-to-content visually-hidden focus-visible">Skip to main content</a>

    <!-- Hero Header -->
    <header class="hero-header" role="banner">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Center for Nursing Research & Innovation</h1>
                <p class="hero-subtitle">
                    Advancing evidence-based practice through rigorous scientific inquiry, 
                    transformative collaborations, and cutting-edge innovation in healthcare.
                </p>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">150+</span>
                        <span class="stat-label">Peer-Reviewed Publications</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">45+</span>
                        <span class="stat-label">Active Research Projects</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">60+</span>
                        <span class="stat-label">International Collaborations</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">₦850M+</span>
                        <span class="stat-label">Research Funding</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <div class="breadcrumb-nav">
                <a href="<?php echo $baseUrl; ?>/">Home</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <a href="<?php echo $baseUrl; ?>/research">Research</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <span class="breadcrumb-current" aria-current="page">Research Center</span>
            </div>
        </div>
    </nav>

    <!-- Research Metrics -->
    <section class="content-section metrics-section" id="metrics">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title" style="color: white;">Research Impact Metrics</h2>
                <p class="section-description" style="color: rgba(255, 255, 255, 0.9);">
                    Quantifying our contribution to nursing science and healthcare innovation
                </p>
            </header>
            
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <span class="metric-value">2.8</span>
                    <span class="metric-label">Average Journal Impact Factor</span>
                </div>
                
                <div class="metric-card">
                    <div class="metric-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                    </div>
                    <span class="metric-value">3,200+</span>
                    <span class="metric-label">Total Citations</span>
                </div>
                
                <div class="metric-card">
                    <div class="metric-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 11.75c-.69 0-1.25.56-1.25 1.25s.56 1.25 1.25 1.25 1.25-.56 1.25-1.25-.56-1.25-1.25-1.25zm6 0c-.69 0-1.25.56-1.25 1.25s.56 1.25 1.25 1.25 1.25-.56 1.25-1.25-.56-1.25-1.25-1.25zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8 0-.29.02-.58.05-.86 2.36-1.05 4.23-2.98 5.21-5.37C11.07 8.33 14.05 10 17.42 10c.78 0 1.53-.09 2.25-.26.21.71.33 1.47.33 2.26 0 4.41-3.59 8-8 8z"/>
                        </svg>
                    </div>
                    <span class="metric-value">85%</span>
                    <span class="metric-label">Open Access Publications</span>
                </div>
                
                <div class="metric-card">
                    <div class="metric-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </div>
                    <span class="metric-value">12</span>
                    <span class="metric-label">Countries Represented</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Research Areas -->
    <section class="content-section research-areas-section" id="research-areas">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Strategic Research Themes</h2>
                <p class="section-description">
                    Interdisciplinary research clusters addressing critical healthcare challenges 
                    through innovative methodologies and collaborative approaches
                </p>
            </header>
            
            <div class="research-grid">
                <?php foreach ($researchAreas as $area): ?>
                <article class="research-card <?php echo e($area['icon']); ?>" data-category="<?php echo e($area['id']); ?>">
                    <div class="research-icon">
                        <?php if ($area['icon'] === 'clinical'): ?>
                        <svg width="28" height="28" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd"/>
                        </svg>
                        <?php elseif ($area['icon'] === 'community'): ?>
                        <svg width="28" height="28" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/>
                        </svg>
                        <?php elseif ($area['icon'] === 'education'): ?>
                        <svg width="28" height="28" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                        <?php else: ?>
                        <svg width="28" height="28" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                        </svg>
                        <?php endif; ?>
                    </div>
                    <h3><?php echo e($area['title']); ?></h3>
                    <p><?php echo e($area['description']); ?></p>
                    <div class="research-meta">
                        <div class="research-faculty">
                            <strong>Research Lead:</strong> <?php echo e(implode(', ', $area['faculty'])); ?>
                        </div>
                        <div class="research-projects">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e($area['projects']); ?> Studies
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
            <div class="publications-header">
                <header class="section-header" style="text-align: left; flex: 1;">
                    <h2 class="section-title">Research Publications</h2>
                    <p class="section-description" style="margin-left: 0; text-align: left;">
                        Peer-reviewed articles, conference proceedings, and academic contributions
                    </p>
                </header>
                
                <div class="publications-controls">
                    <div class="search-container">
                        <svg class="search-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                        <input type="search" class="search-input" placeholder="Search publications..." aria-label="Search publications">
                    </div>
                    
                    <div class="publications-filter">
                        <button class="filter-btn active" data-filter="all">All</button>
                        <?php if (!empty($uniqueCategories)): ?>
                            <?php foreach ($uniqueCategories as $category): 
                                $categoryName = str_replace('-', ' ', $category);
                            ?>
                            <button class="filter-btn" data-filter="<?php echo e($category); ?>">
                                <?php echo e(ucwords($categoryName)); ?>
                            </button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <button class="filter-btn" data-filter="2023">2023</button>
                        <button class="filter-btn" data-filter="2022">2022</button>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($publications)): ?>
            <div class="publications-list">
                <?php foreach ($publications as $pub): ?>
                <article class="publication-item" data-category="<?php echo e($pub['research_area'] ?? ''); ?>" data-year="<?php echo date('Y', strtotime($pub['publication_date'] ?? '2023')); ?>">
                    <div class="publication-header">
                        <h3 class="publication-title"><?php echo e($pub['title']); ?></h3>
                        <div class="publication-authors"><?php echo e($pub['authors']); ?></div>
                    </div>
                    
                    <div class="publication-meta">
                        <div class="publication-meta-item">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo date('F Y', strtotime($pub['publication_date'])); ?>
                        </div>
                        
                        <?php if (!empty($pub['journal'])): ?>
                        <div class="publication-meta-item">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                            </svg>
                            <?php echo e($pub['journal']); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($pub['doi'])): ?>
                        <div class="publication-meta-item">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e($pub['doi']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($pub['abstract'])): ?>
                    <div class="publication-abstract">
                        <?php echo nl2br(e($pub['abstract'])); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="publication-actions">
                        <?php if (!empty($pub['doi'])): ?>
                        <a href="https://doi.org/<?php echo e($pub['doi']); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="publication-action">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                            </svg>
                            View Article
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($pub['file_path'])): ?>
                        <a href="<?php echo $baseUrl; ?>/download/research/<?php echo e($pub['id']); ?>" 
                           class="publication-action">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Download PDF
                        </a>
                        <?php endif; ?>
                        
                        <button class="publication-action citation" onclick="copyCitation('<?php echo e($pub['id']); ?>')">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                            Cite
                        </button>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state" style="text-align: center; padding: 40px; color: var(--color-slate-gray);">
                <svg width="48" height="48" fill="currentColor" viewBox="0 0 20 20" style="margin-bottom: 16px; opacity: 0.5;">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <h3 style="margin-bottom: 8px; color: var(--color-oxford-blue);">No Publications Available</h3>
                <p>Research publications will be listed here as they become available.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Ongoing Research Projects -->
    <section class="content-section projects-section" id="projects">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Current Research Portfolio</h2>
                <p class="section-description">
                    Active research initiatives, clinical trials, and longitudinal studies
                </p>
            </header>
            
            <div class="projects-timeline">
                <?php foreach ($ongoingProjects as $index => $project): ?>
                <div class="project-timeline-item">
                    <div class="project-timeline-marker"></div>
                    <article class="project-card">
                        <div class="project-status <?php echo 'status-' . e($project['status']); ?>">
                            <?php echo e(ucfirst($project['status'])); ?>
                        </div>
                        <h3><?php echo e($project['title']); ?></h3>
                        <div class="project-investigators">
                            <strong>Principal Investigators:</strong> <?php echo e(implode(', ', $project['investigators'])); ?>
                        </div>
                        
                        <div class="project-grid">
                            <div class="project-detail">
                                <span class="project-label">Funding Agency</span>
                                <span class="project-value"><?php echo e($project['funder']); ?></span>
                            </div>
                            <div class="project-detail">
                                <span class="project-label">Duration</span>
                                <span class="project-value"><?php echo e($project['duration']); ?></span>
                            </div>
                            <div class="project-detail">
                                <span class="project-label">Budget</span>
                                <span class="project-value"><?php echo e($project['budget']); ?></span>
                            </div>
                            <div class="project-detail">
                                <span class="project-label">Study Type</span>
                                <span class="project-value">Clinical Trial</span>
                            </div>
                        </div>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Research Facilities -->
    <section class="content-section facilities-section" id="facilities">
        <div class="container">
            <header class="section-header">
                <h2 class="section-title">Core Research Facilities</h2>
                <p class="section-description">
                    Advanced infrastructure supporting interdisciplinary research and innovation
                </p>
            </header>
            
            <div class="facilities-grid">
                <?php foreach ($researchFacilities as $facility): ?>
                <article class="facility-card">
                    <div class="facility-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3><?php echo e($facility['name']); ?></h3>
                    <p><?php echo e($facility['description']); ?></p>
                    
                    <ul class="facility-features">
                        <?php foreach ($facility['features'] as $feature): ?>
                        <li><?php echo e($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <!-- FIXED: Facility Director Section -->
                    <div class="facility-contact">
                        <div class="facility-contact-photo">
                            <?php 
                            // Get initials from name
                            $nameParts = explode(' ', $facility['contact']);
                            $initials = '';
                            foreach ($nameParts as $part) {
                                if (preg_match('/[A-Z]/', $part)) {
                                    $initials .= substr($part, 0, 1);
                                }
                            }
                            echo e($initials ?: substr($facility['contact'], 0, 2));
                            ?>
                        </div>
                        <div class="facility-contact-info">
                            <strong>Facility Director</strong>
                            <span><?php echo e($facility['contact']); ?></span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Research Collaborations -->
    <section class="content-section collaborations-section" id="collaborations">
        <div class="world-map-bg"></div>
        <div class="container">
            <header class="collaborations-header">
                <h2 class="section-title">Global Research Network</h2>
                <p class="section-description">
                    Strategic partnerships with leading institutions worldwide
                </p>
                
                <div class="region-filter">
                    <button class="region-btn active" data-region="all">All Regions</button>
                    <button class="region-btn" data-region="africa">Africa</button>
                    <button class="region-btn" data-region="europe">Europe</button>
                    <button class="region-btn" data-region="asia">Asia</button>
                    <button class="region-btn" data-region="americas">Americas</button>
                </div>
            </header>
            
            <div class="collaborations-grid">
                <?php foreach ($collaborations as $collaboration): ?>
                <div class="collaboration-card" data-region="<?php echo strtolower(e($collaboration['country'] === 'Nigeria' ? 'africa' : ($collaboration['country'] === 'USA' ? 'americas' : 'europe'))); ?>">
                    <div class="collaboration-flag">
                        <?php if ($collaboration['country'] === 'Nigeria'): ?>🇳🇬
                        <?php elseif ($collaboration['country'] === 'USA'): ?>🇺🇸
                        <?php elseif ($collaboration['country'] === 'Ghana'): ?>🇬🇭
                        <?php elseif ($collaboration['country'] === 'Kenya'): ?>🇰🇪
                        <?php else: ?>🌍
                        <?php endif; ?>
                    </div>
                    <div class="collaboration-name"><?php echo e($collaboration['name']); ?></div>
                    <div class="collaboration-country"><?php echo e($collaboration['country']); ?></div>
                    <div class="collaboration-type"><?php echo e($collaboration['type']); ?> Partner</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="content-section cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Collaborate With Us</h2>
                <p class="cta-description">
                    Join our network of researchers, clinicians, and innovators working to 
                    advance nursing science and improve healthcare outcomes globally.
                </p>
                
                <div class="cta-buttons">
                    <a href="<?php echo $baseUrl; ?>/contact?subject=Research%20Collaboration%20Inquiry" 
                       class="btn btn-primary">
                        Propose a Collaboration
                    </a>
                    <a href="<?php echo $baseUrl; ?>/research/opportunities" 
                       class="btn btn-outline">
                        View Research Opportunities
                    </a>
                    <a href="<?php echo $baseUrl; ?>/faculty" 
                       class="btn btn-secondary">
                        Meet Our Research Team
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
    
    // ===== PUBLICATIONS FILTERING =====
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
                const category = item.getAttribute('data-category');
                const year = item.getAttribute('data-year');
                
                if (filter === 'all' || category === filter || year === filter) {
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
    
    // ===== REGION FILTERING =====
    const regionButtons = document.querySelectorAll('.region-btn');
    const collaborationCards = document.querySelectorAll('.collaboration-card');
    
    regionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const region = this.getAttribute('data-region');
            
            // Update active button
            regionButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter collaborations
            collaborationCards.forEach(card => {
                const cardRegion = card.getAttribute('data-region');
                
                if (region === 'all' || cardRegion === region) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // ===== SEARCH FUNCTIONALITY =====
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            publicationItems.forEach(item => {
                const title = item.querySelector('.publication-title').textContent.toLowerCase();
                const authors = item.querySelector('.publication-authors').textContent.toLowerCase();
                const abstract = item.querySelector('.publication-abstract')?.textContent.toLowerCase() || '';
                
                if (searchTerm === '' || 
                    title.includes(searchTerm) || 
                    authors.includes(searchTerm) || 
                    abstract.includes(searchTerm)) {
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
    
    // ===== CITATION COPY FUNCTION =====
    window.copyCitation = function(publicationId) {
        // This would typically fetch citation data from an API
        const citation = `Author(s). (Year). Title. Journal, Volume(Issue), Pages. DOI`;
        
        navigator.clipboard.writeText(citation).then(() => {
            // Show success message
            const button = event.target.closest('.citation');
            const originalText = button.innerHTML;
            button.innerHTML = '<svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Copied!';
            button.style.background = 'rgba(46, 125, 50, 0.1)';
            button.style.color = 'var(--color-forest-green)';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = 'rgba(211, 47, 47, 0.1)';
                button.style.color = 'var(--color-imperial-red)';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy citation: ', err);
        });
    };
    
    // ===== SMOOTH SCROLLING =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                
                const headerHeight = document.querySelector('.navbar')?.offsetHeight || 100;
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
    
    // ===== INTERSECTION OBSERVER FOR ANIMATIONS =====
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    document.querySelectorAll('.research-card, .publication-item, .project-card, .facility-card, .collaboration-card, .metric-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        observer.observe(el);
    });
    
    // ===== EXPORT PUBLICATIONS =====
    const exportButton = document.createElement('button');
    exportButton.className = 'btn btn-outline';
    exportButton.style.marginTop = 'var(--space-md)';
    exportButton.innerHTML = `
        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20" style="margin-right: 6px;">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
        Export to CSV
    `;
    
    exportButton.addEventListener('click', function() {
        const publicationsData = <?php echo json_encode($publications); ?>;
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Title,Authors,Journal,Year,DOI\n";
        
        publicationsData.forEach(pub => {
            const row = [
                `"${pub.title}"`,
                `"${pub.authors}"`,
                `"${pub.journal || ''}"`,
                pub.year || new Date(pub.publication_date || '').getFullYear(),
                pub.doi || ''
            ].join(',');
            csvContent += row + "\n";
        });
        
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'fctcns-research-publications.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
    
    // Add export button to publications section
    const publicationsSection = document.querySelector('.publications-section .section-header');
    if (publicationsSection && <?php echo !empty($publications) ? 'true' : 'false'; ?>) {
        publicationsSection.appendChild(exportButton);
    }
});
</script>