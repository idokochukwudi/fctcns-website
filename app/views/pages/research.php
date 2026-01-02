<?php
/**
 * Research Page View Template - Final Version with Dynamic Publications
 * 
 * @package FCTCNS
 * @version 4.9
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$page_title = $page_title ?? 'Research & Innovation | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Explore our research publications, facilities, and contributions to nursing science.';

// Use real data from database
$publications = $publications ?? $research ?? [];
$categories = $categories ?? [];

// Transform categories for research areas
$researchAreas = [];
if (!empty($categories)) {
    foreach ($categories as $category) {
        $count = 0;
        foreach ($publications as $pub) {
            if ($pub['research_area'] ?? '' === $category['slug']) {
                $count++;
            }
        }

        $researchAreas[] = [
            'id' => $category['slug'],
            'title' => $category['name'],
            'description' => $category['description'] ?? 'Research in ' . $category['name'],
            'projects' => $count,
            'icon' => 'flask' // default icon, can be customized per category
        ];
    }
}

// Get unique categories for filtering
$uniqueCategories = array_unique(array_column($publications, 'research_area'));
// Remove empty values
$uniqueCategories = array_filter($uniqueCategories);
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
main.research-page { margin-top: 0 !important; padding-top: 0 !important; }
.research-hero { margin-top: 0 !important; padding-top: 0 !important; }

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
.research-hero {
    position: relative;
    height: 75vh;
    max-height: 600px;
    min-height: 450px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.3), rgba(123, 92, 174, 0.2));
}

.research-hero-bg {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background-size: cover; background-position: center;
    background-image: url('<?php echo $baseUrl; ?>/public/assets/images/research/hero-placeholder.jpg');
}

.research-hero-bg::after {
    content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(90deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.3) 40%, rgba(0,0,0,0.2) 100%);
}

.research-hero-content {
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

.research-hero-badge { 
    display: inline-block;
    background: var(--color-accent); 
    color: var(--color-white-solid); 
    padding: 0.4rem 1rem; 
    border-radius: 4px; 
    font-size: 0.8rem; 
    font-weight: 600; 
    margin-bottom: var(--spacing-md); 
}
.research-hero-title { font-family: var(--font-heading); font-size: 2.8rem; font-weight: 700; color: var(--color-white-solid); text-shadow: 0 2px 6px rgba(0,0,0,0.5); }
.research-hero-subtitle { font-size: 1.2rem; color: rgba(255,255,255,0.95); }

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
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-base);
    border: 1px solid rgba(107, 78, 155, 0.1);
}
.card:hover { transform: translateY(-8px); box-shadow: var(--shadow-md); border-color: rgba(107, 78, 155, 0.3); }

.card-body { padding: var(--spacing-lg); }
.card-title { font-family: var(--font-heading); font-size: 1.6rem; font-weight: 600; color: var(--color-primary); margin-bottom: var(--spacing-md); }

.publication-item {
    background: var(--color-white-solid);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-sm);
    border-left: 4px solid var(--color-primary);
    margin-bottom: var(--spacing-lg);
}
.publication-item:hover { box-shadow: var(--shadow-md); border-left-color: var(--color-accent); }

.publication-title { font-family: var(--font-heading); font-size: 1.4rem; font-weight: 600; color: var(--color-primary); margin-bottom: var(--spacing-sm); }
.publication-authors { font-style: italic; color: var(--color-gray-800); margin-bottom: var(--spacing-sm); }
.publication-meta { font-size: 0.9rem; color: var(--color-gray-800); margin-bottom: var(--spacing-md); }
.publication-abstract { color: var(--color-gray-800); line-height: 1.6; }

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
    .research-hero-content { margin-left: 5%; max-width: 90%; text-align: center; margin-top: 10vh; }
    .research-hero-title { font-size: 2.2rem; }
    .grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<main class="research-page">
    <!-- Hero Section -->
    <section class="research-hero">
        <div class="research-hero-bg"></div>
        <div class="container">
            <div class="research-hero-content">
                <span class="research-hero-badge">Research Excellence</span>
                <h1 class="research-hero-title">Research & Innovation</h1>
                <p class="research-hero-subtitle">
                    Advancing nursing science through education, clinical practice, and evidence-based research.
                </p>
            </div>
        </div>
    </section>

    <!-- Research Areas -->
    <?php if (!empty($researchAreas)): ?>
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Research Areas</h2>
            </div>
            <div class="grid">
                <?php foreach ($researchAreas as $area): ?>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo e($area['title']); ?></h3>
                        <p><?php echo e($area['description']); ?></p>
                        <p><strong>Publications:</strong> <?php echo e($area['projects']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Publications - DYNAMIC FROM DATABASE -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Research Publications</h2>
                <p class="section-subtitle">Peer-reviewed publications from our faculty and researchers.</p>
            </div>

            <?php if (!empty($publications)): ?>
            <div>
                <?php foreach ($publications as $pub): ?>
                <div class="publication-item">
                    <h3 class="publication-title"><?php echo e($pub['title'] ?? 'Untitled Publication'); ?></h3>
                    <div class="publication-authors"><?php echo e($pub['authors'] ?? ''); ?></div>
                    <div class="publication-meta">
                        <?php if (!empty($pub['journal'])): ?>
                        <strong>Journal:</strong> <?php echo e($pub['journal']); ?> • 
                        <?php endif; ?>
                        <?php if (!empty($pub['publication_date'])): ?>
                        <strong>Year:</strong> <?php echo date('Y', strtotime($pub['publication_date'])); ?>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($pub['abstract'])): ?>
                    <div class="publication-abstract">
                        <?php echo nl2br(e($pub['abstract'])); ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($pub['doi'])): ?>
                    <a href="https://doi.org/<?php echo e($pub['doi']); ?>" target="_blank" class="btn-primary">
                        View Publication (DOI)
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-body" style="text-align:center; color:#999;">
                    <p>No publications available at this time.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Facilities -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Research & Training Facilities</h2>
            </div>
            <div class="grid">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Simulation Laboratory</h3>
                        <p>High-fidelity training environment for clinical skills development and research.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Medical Library</h3>
                        <p>Access to journals, databases, and resources supporting evidence-based research.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="container">
            <h2 class="section-title">Contribute to Nursing Research</h2>
            <p class="section-subtitle" style="max-width:700px; margin:0 auto var(--spacing-xl);">
                Collaborate with us on advancing nursing education and practice.
            </p>
            <a href="<?php echo $baseUrl; ?>/contact" class="btn-primary">
                <i class="fas fa-envelope"></i> Get in Touch
            </a>
        </div>
    </section>
</main>

</body>
</html>