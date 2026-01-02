<?php
/**
 * Programs Page View Template - Redesigned to Match Homepage Theme
 * 
 * @package FCTCNS
 * @version 4.5
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$page_title = $page_title ?? 'Nursing Programs | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Explore our accredited nursing education programs designed to develop competent healthcare professionals.';
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
main.programs-page { margin-top: 0 !important; padding-top: 0 !important; }
.programs-hero { margin-top: 0 !important; padding-top: 0 !important; }

/* ==========================================================================
   GLOBAL VARIABLES - Mature Transparent Purple Theme (Same as Homepage & About)
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
   HERO SECTION - Matching Homepage Style
   ========================================================================== */
.programs-hero {
    position: relative;
    height: 75vh;
    max-height: 600px;
    min-height: 450px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.3), rgba(123, 92, 174, 0.2));
}

.programs-hero-bg {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background-size: cover; background-position: center;
    /* PLACEHOLDER: Replace with your programs hero image */
    background-image: url('<?php echo $baseUrl; ?>/public/assets/images/programs/hero-placeholder.jpg');
}

.programs-hero-bg::after {
    content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(90deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.3) 40%, rgba(0,0,0,0.2) 100%);
}

.programs-hero-content {
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

.programs-hero-badge { 
    display: inline-block;
    background: var(--color-accent); 
    color: var(--color-white-solid); 
    padding: 0.4rem 1rem; 
    border-radius: 4px; 
    font-size: 0.8rem; 
    font-weight: 600; 
    margin-bottom: var(--spacing-md); 
}
.programs-hero-title { font-family: var(--font-heading); font-size: 2.8rem; font-weight: 700; color: var(--color-white-solid); text-shadow: 0 2px 6px rgba(0,0,0,0.5); }
.programs-hero-subtitle { font-size: 1.2rem; color: rgba(255,255,255,0.95); }

/* ==========================================================================
   SECTIONS & PROGRAM CARDS
   ========================================================================== */
.section { padding: var(--spacing-xl) 0; }
.section-alt { background: var(--color-primary-very-light); }

.section-header { text-align: center; margin-bottom: var(--spacing-xl); max-width: 700px; margin-left: auto; margin-right: auto; }
.section-title { font-family: var(--font-heading); font-size: 2rem; font-weight: 600; color: var(--color-primary); position: relative; display: inline-block; }
.section-title::after { content: ''; position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 60px; height: 3px; background: var(--color-accent); border-radius: 2px; }
.section-subtitle { font-size: 1.1rem; color: var(--color-gray-800); margin-top: var(--spacing-lg); line-height: 1.6; }

.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: var(--spacing-lg); }

.program-card {
    background: var(--color-white-solid);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-base);
    border: 1px solid rgba(107, 78, 155, 0.1);
    display: flex;
    flex-direction: column;
    height: 100%;
}
.program-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-md); border-color: rgba(107, 78, 155, 0.3); }

.program-card-header {
    padding: var(--spacing-lg);
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    color: var(--color-white-solid);
    position: relative;
}

/* PLACEHOLDER: Program-specific image */
.program-card-img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    background: #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 1rem;
    text-align: center;
    /* Replace this entire block with actual image: <img src="<?php echo $baseUrl; ?>/public/assets/images/programs/[program-name].jpg" alt="..." class="program-card-img"> */
}

.program-card-body { padding: var(--spacing-lg); flex-grow: 1; }
.program-card-title { font-family: var(--font-heading); font-size: 1.6rem; font-weight: 600; color: var(--color-white-solid); margin-bottom: var(--spacing-sm); }
.program-card-duration { display: flex; align-items: center; gap: 0.5rem; color: rgba(255,255,255,0.9); font-size: 0.95rem; }

.program-card-description { color: var(--color-gray-800); line-height: 1.6; margin-bottom: var(--spacing-md); flex-grow: 1; }

.program-highlights {
    background: rgba(240, 235, 247, 0.4);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    border-left: 4px solid var(--color-primary);
    margin-bottom: var(--spacing-md);
}

.highlight-title { font-family: var(--font-heading); font-size: 1.1rem; color: var(--color-primary); margin-bottom: var(--spacing-sm); display: flex; align-items: center; gap: 0.5rem; }
.highlight-list { list-style: none; padding-left: 0; }
.highlight-list li { padding: 0.4rem 0; position: relative; padding-left: 1.5rem; color: var(--color-gray-800); }
.highlight-list li::before { content: '✓'; position: absolute; left: 0; color: var(--color-accent); font-weight: bold; }

.program-card-footer {
    padding: var(--spacing-md);
    border-top: 1px solid rgba(233, 236, 239, 0.8);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
}

.btn-primary {
    background: var(--color-accent);
    color: var(--color-white-solid);
    padding: 0.6rem 1.4rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-base);
}
.btn-primary:hover { background: var(--color-accent-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }

.btn-outline {
    color: var(--color-primary);
    border: 1px solid var(--color-primary);
    background: transparent;
    padding: 0.6rem 1.4rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
}
.btn-outline:hover { background: var(--color-primary); color: var(--color-white-solid); }

/* CTA */
.cta-section { background: linear-gradient(135deg, rgba(107, 78, 155, 0.08), rgba(123, 92, 174, 0.05)); text-align: center; padding: var(--spacing-xxl) 0; }

/* Responsive */
@media (max-width: 768px) {
    .programs-hero-content { margin-left: 5%; max-width: 90%; text-align: center; margin-top: 10vh; }
    .programs-hero-title { font-size: 2.2rem; }
    .grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<main class="programs-page">
    <!-- Hero Section -->
    <section class="programs-hero">
        <div class="programs-hero-bg"></div>
        <div class="container">
            <div class="programs-hero-content">
                <span class="programs-hero-badge">Accredited Programs</span>
                <h1 class="programs-hero-title">Nursing Education Programs</h1>
                <p class="programs-hero-subtitle">
                    Fully accredited programs combining theoretical excellence with hands-on clinical training to prepare competent healthcare professionals.
                </p>
            </div>
        </div>
    </section>

    <!-- Programs Grid -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Accredited Programs</h2>
                <p class="section-subtitle">Choose from our range of nationally recognized nursing programs.</p>
            </div>

            <div class="grid">
                <!-- National Diploma in Nursing -->
                <article class="program-card">
                    <!-- IMAGE PLACEHOLDER - Replace with actual program image -->
                    <div class="program-card-img">
                        <div>Program Image Placeholder<br>(Add: /public/assets/images/programs/national-diploma.jpg)</div>
                    </div>
                    
                    <div class="program-card-header">
                        <h3 class="program-card-title">National Diploma in Nursing</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock"></i> Duration: 3 Years
                        </div>
                    </div>
                    
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Comprehensive three-year program leading to ND qualification. Combines theoretical knowledge with practical skills for healthcare delivery.
                        </p>
                        
                        <div class="program-highlights">
                            <div class="highlight-title"><i class="fas fa-star"></i> Key Features</div>
                            <ul class="highlight-list">
                                <li>NBTE accredited</li>
                                <li>JAMB UTME pathway</li>
                                <li>Clinical rotations</li>
                                <li>Modern simulation labs</li>
                            </ul>
                        </div>
                        
                        <div class="program-card-footer">
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-outline">Learn More</a>
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary">Apply Now</a>
                        </div>
                    </div>
                </article>

                <!-- Basic Nursing -->
                <article class="program-card">
                    <!-- IMAGE PLACEHOLDER -->
                    <div class="program-card-img">
                        <div>Program Image Placeholder<br>(Add: /public/assets/images/programs/basic-nursing.jpg)</div>
                    </div>
                    
                    <div class="program-card-header">
                        <h3 class="program-card-title">Basic Nursing</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock"></i> Duration: 3 Years
                        </div>
                    </div>
                    
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Comprehensive general nursing education preparing students for registration as Registered Nurses (RN).
                        </p>
                        
                        <div class="program-highlights">
                            <div class="highlight-title"><i class="fas fa-star"></i> Key Features</div>
                            <ul class="highlight-list">
                                <li>Full NMCN accreditation</li>
                                <li>Extensive clinical practice</li>
                                <li>Simulation training</li>
                                <li>Exam preparation support</li>
                            </ul>
                        </div>
                        
                        <div class="program-card-footer">
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-outline">Learn More</a>
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary">Apply Now</a>
                        </div>
                    </div>
                </article>

                <!-- Basic Midwifery -->
                <article class="program-card">
                    <!-- IMAGE PLACEHOLDER -->
                    <div class="program-card-img">
                        <div>Program Image Placeholder<br>(Add: /public/assets/images/programs/basic-midwifery.jpg)</div>
                    </div>
                    
                    <div class="program-card-header">
                        <h3 class="program-card-title">Basic Midwifery</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock"></i> Duration: 3 Years
                        </div>
                    </div>
                    
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Specialized training in maternal and child healthcare, antenatal care, delivery, and postnatal services.
                        </p>
                        
                        <div class="program-highlights">
                            <div class="highlight-title"><i class="fas fa-star"></i> Key Features</div>
                            <ul class="highlight-list">
                                <li>NMCN approved</li>
                                <li>Maternity clinical placements</li>
                                <li>Family planning training</li>
                                <li>Neonatal care focus</li>
                            </ul>
                        </div>
                        
                        <div class="program-card-footer">
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-outline">Learn More</a>
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary">Apply Now</a>
                        </div>
                    </div>
                </article>

                <!-- Post Basic Nursing Specialization -->
                <article class="program-card">
                    <!-- IMAGE PLACEHOLDER -->
                    <div class="program-card-img">
                        <div>Program Image Placeholder<br>(Add: /public/assets/images/programs/post-basic.jpg)</div>
                    </div>
                    
                    <div class="program-card-header">
                        <h3 class="program-card-title">Post Basic Nursing Specialization</h3>
                        <div class="program-card-duration">
                            <i class="far fa-clock"></i> Duration: 18 Months
                        </div>
                    </div>
                    
                    <div class="program-card-body">
                        <p class="program-card-description">
                            Advanced specialization for registered nurses in intensive care, pediatrics, perioperative, or psychiatric nursing.
                        </p>
                        
                        <div class="program-highlights">
                            <div class="highlight-title"><i class="fas fa-star"></i> Key Features</div>
                            <ul class="highlight-list">
                                <li>Specialist clinical training</li>
                                <li>Leadership development</li>
                                <li>Research methodology</li>
                                <li>Career advancement pathway</li>
                            </ul>
                        </div>
                        
                        <div class="program-card-footer">
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-outline">Learn More</a>
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary">Apply Now</a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <h2 class="section-title">Begin Your Nursing Career Today</h2>
            <p class="section-subtitle" style="max-width: 700px; margin: 0 auto var(--spacing-xl);">
                Join thousands of graduates making a difference in healthcare across Nigeria.
            </p>
            <div style="display: flex; gap: var(--spacing-md); justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary"><i class="fas fa-file-alt"></i> Apply Now</a>
                <a href="<?php echo $baseUrl; ?>/contact" class="btn-primary"><i class="fas fa-phone-alt"></i> Contact Admissions</a>
            </div>
        </div>
    </section>
</main>

</body>
</html>