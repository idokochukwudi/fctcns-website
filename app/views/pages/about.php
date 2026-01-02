<?php
/**
 * About Page View Template - Final Enhanced Version with Beautiful Gallery Captions
 * 
 * @package FCTCNS
 * @version 4.4
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$page_title = $page_title ?? 'About | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Learn about our history, mission, vision, values, leadership, and commitment to excellence in nursing education.';
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
   CRITICAL FIX: No gap between site header and page content
   ========================================================================== */
body { margin: 0 !important; padding: 0 !important; }
main.about-page { margin-top: 0 !important; padding-top: 0 !important; }
.about-hero { margin-top: 0 !important; padding-top: 0 !important; }

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
.about-hero {
    position: relative;
    height: 75vh;
    max-height: 600px;
    min-height: 450px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.3), rgba(123, 92, 174, 0.2));
}

.about-hero-bg {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background-size: cover; background-position: center;
}

.about-hero-bg::after {
    content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(90deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.3) 40%, rgba(0,0,0,0.2) 100%);
}

.about-hero-content {
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

.about-hero-badge { 
    display: inline-block;
    background: var(--color-accent); 
    color: var(--color-white-solid); 
    padding: 0.4rem 1rem; 
    border-radius: 4px; 
    font-size: 0.8rem; 
    font-weight: 600; 
    letter-spacing: 0.5px;
    margin-bottom: var(--spacing-md); 
}

.about-hero-title { 
    font-family: var(--font-heading); 
    font-size: 2.8rem; 
    font-weight: 700; 
    color: var(--color-white-solid); 
    text-shadow: 0 2px 6px rgba(0,0,0,0.5); 
    margin-bottom: var(--spacing-sm);
}

.about-hero-subtitle { font-size: 1.2rem; color: rgba(255,255,255,0.95); }

/* ==========================================================================
   SECTIONS & CARDS
   ========================================================================== */
.section { padding: var(--spacing-xl) 0; }
.section-alt { background: var(--color-primary-very-light); }

.section-header { text-align: center; margin-bottom: var(--spacing-xl); max-width: 700px; margin-left: auto; margin-right: auto; }
.section-title { font-family: var(--font-heading); font-size: 2rem; font-weight: 600; color: var(--color-primary); position: relative; display: inline-block; }
.section-title::after { content: ''; position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 60px; height: 3px; background: var(--color-accent); border-radius: 2px; }
.section-subtitle { font-size: 1.1rem; color: var(--color-gray-800); margin-top: var(--spacing-lg); line-height: 1.6; }

.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--spacing-lg); }

.card {
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
.card:hover { transform: translateY(-8px); box-shadow: var(--shadow-md); border-color: rgba(107, 78, 155, 0.3); }

.card-img { width: 100%; height: 220px; object-fit: cover; }
.card-body { padding: var(--spacing-lg); flex-grow: 1; }
.card-title { font-family: var(--font-heading); font-size: 1.4rem; font-weight: 600; color: var(--color-primary); margin-bottom: var(--spacing-sm); }
.card-text { color: var(--color-gray-800); line-height: 1.6; }

.badge-card { text-align: center; padding: var(--spacing-xl); }
.badge-icon { font-size: 3rem; color: var(--color-primary); margin-bottom: var(--spacing-md); }
.badge-title { font-family: var(--font-heading); font-size: 1.4rem; color: var(--color-primary); }

/* ==========================================================================
   GALLERY CAROUSEL - Beautiful, highly readable captions
   ========================================================================== */
.gallery-carousel {
    position: relative;
    height: 500px;
    overflow: hidden;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    margin-top: var(--spacing-lg);
}

.gallery-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.8s ease;
    background-size: cover;
    background-position: center;
}

.gallery-slide.active { opacity: 1; }

.gallery-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.85), rgba(0,0,0,0.6), transparent);
    padding: var(--spacing-xl) var(--spacing-lg);
    color: var(--color-white-solid);
    text-align: center;
    backdrop-filter: blur(6px);
}

.gallery-caption::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: var(--color-accent);
}

.gallery-caption h3 {
    font-family: var(--font-heading);
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: var(--spacing-sm);
    color: var(--color-white-solid);
    text-shadow: 0 3px 10px rgba(0,0,0,0.8);
    letter-spacing: 0.8px;
}

.gallery-caption p {
    font-size: 1.15rem;
    color: rgba(255,255,255,0.95);
    text-shadow: 0 2px 6px rgba(0,0,0,0.7);
    max-width: 800px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Buttons */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--color-accent);
    color: var(--color-white-solid);
    padding: 0.7rem 1.5rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-base);
}
.btn-primary:hover { background: var(--color-accent-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }

.cta-section { background: linear-gradient(135deg, rgba(107, 78, 155, 0.08), rgba(123, 92, 174, 0.05)); text-align: center; padding: var(--spacing-xxl) 0; }

/* Responsive */
@media (max-width: 768px) {
    .about-hero-content { margin-left: 5%; max-width: 90%; text-align: center; margin-top: 10vh; }
    .about-hero-title { font-size: 2.2rem; }
    .grid { grid-template-columns: 1fr; }
    .gallery-carousel { height: 400px; }
    .gallery-caption h3 { font-size: 1.8rem; }
    .gallery-caption p { font-size: 1rem; }
}
</style>
</head>
<body>

<main class="about-page">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="about-hero-bg" style="background-image: url('<?php echo $baseUrl; ?>/public/assets/images/about/campus-building.jpg');"></div>
        <div class="container">
            <div class="about-hero-content">
                <span class="about-hero-badge">Excellence Since 1989</span>
                <h1 class="about-hero-title">About FCT College of Nursing Sciences</h1>
                <p class="about-hero-subtitle">
                    A premier institution dedicated to excellence in nursing education, research, and healthcare training in Nigeria's Federal Capital Territory.
                </p>
            </div>
        </div>
    </section>

    <!-- Mission, Vision, Values -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Mission, Vision & Values</h2>
                <p class="section-subtitle">The guiding principles that define our commitment to nursing excellence.</p>
            </div>
            <div class="grid">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Our Mission</h3>
                        <p class="card-text">To deliver exceptional nursing education through innovative teaching, research, and community engagement, developing competent and compassionate nursing professionals who demonstrate excellence in clinical practice, leadership, and ethical conduct.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Our Vision</h3>
                        <p class="card-text">To be Africa's leading institution for nursing education and research, recognized for producing healthcare professionals who transform communities through innovative practice, ethical leadership, and compassionate, evidence-based care.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Core Values</h3>
                        <ul style="list-style: none; padding-left: 0; margin-top: var(--spacing-md);">
                            <li style="padding: 0.4rem 0; position: relative; padding-left: 1.5rem;">✓ Excellence in Education</li>
                            <li style="padding: 0.4rem 0; position: relative; padding-left: 1.5rem;">✓ Integrity and Ethics</li>
                            <li style="padding: 0.4rem 0; position: relative; padding-left: 1.5rem;">✓ Compassionate Care</li>
                            <li style="padding: 0.4rem 0; position: relative; padding-left: 1.5rem;">✓ Innovation and Research</li>
                            <li style="padding: 0.4rem 0; position: relative; padding-left: 1.5rem;">✓ Professional Development</li>
                            <li style="padding: 0.4rem 0; position: relative; padding-left: 1.5rem;">✓ Community Service</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Impact in Numbers</h2>
            </div>
            <div class="grid">
                <div class="card" style="text-align: center;">
                    <div style="font-size: 2.8rem; font-weight: 600; color: var(--color-primary);">35+</div>
                    <p class="card-text">Years of Excellence</p>
                </div>
                <div class="card" style="text-align: center;">
                    <div style="font-size: 2.8rem; font-weight: 600; color: var(--color-primary);">5,000+</div>
                    <p class="card-text">Nursing Graduates</p>
                </div>
                <div class="card" style="text-align: center;">
                    <div style="font-size: 2.8rem; font-weight: 600; color: var(--color-primary);">100%</div>
                    <p class="card-text">NMCN Accredited</p>
                </div>
                <div class="card" style="text-align: center;">
                    <div style="font-size: 2.8rem; font-weight: 600; color: var(--color-primary);">50+</div>
                    <p class="card-text">Faculty Members</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Institutional Leadership</h2>
                <p class="section-subtitle">Experienced professionals guiding our institution toward educational excellence.</p>
            </div>
            <div class="grid">
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/public/assets/images/leadership/fct-minister.jpg" alt="Ezenwo Nyesom Wike CON, FCT Minister" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/public/assets/images/placeholder/person-placeholder.jpg';">
                    <div class="card-body">
                        <h3 class="card-title">Ezenwo Nyesom Wike CON</h3>
                        <p class="card-text">FCT Minister<br>Federal Capital Territory Administration</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/public/assets/images/leadership/mandate-secretary.jpg" alt="Dr. Adedolapo Fasawe" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/public/assets/images/placeholder/person-placeholder.jpg';">
                    <div class="card-body">
                        <h3 class="card-title">Dr. Adedolapo Fasawe</h3>
                        <p class="card-text">Mandate Secretary<br>Health Services & Environment Secretariat</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/public/assets/images/leadership/permanent-secretary.jpg" alt="Dr. Babagana Adam" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/public/assets/images/placeholder/person-placeholder.jpg';">
                    <div class="card-body">
                        <h3 class="card-title">Dr. Babagana Adam</h3>
                        <p class="card-text">Permanent Secretary<br>Health Services & Environment Secretariat</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/public/assets/images/leadership/director-nursing.jpg" alt="Mrs Ijoema Jimi Bada" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/public/assets/images/placeholder/person-placeholder.jpg';">
                    <div class="card-body">
                        <h3 class="card-title">Mrs Ijoema Jimi Bada</h3>
                        <p class="card-text">Director, Nursing Services<br>Health Services & Environment Secretariat</p>
                    </div>
                </div>
                <div class="card">
                    <img src="<?php echo $baseUrl; ?>/public/assets/images/leadership/college-provost.jpg" alt="Comr. Deborah Yusuf" class="card-img" onerror="this.src='<?php echo $baseUrl; ?>/public/assets/images/placeholder/person-placeholder.jpg';">
                    <div class="card-body">
                        <h3 class="card-title">Comr. Deborah Yusuf</h3>
                        <p class="card-text">Provost, FCTCNS<br>FCT College of Nursing Sciences</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Accreditation -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Institutional Accreditation</h2>
            </div>
            <div class="grid">
                <div class="card badge-card">
                    <i class="fas fa-stethoscope badge-icon" aria-hidden="true"></i>
                    <h3 class="badge-title">NMCN</h3>
                    <p class="card-text">Nursing & Midwifery Council of Nigeria<br>Full accreditation for all nursing programs.</p>
                </div>
                <div class="card badge-card">
                    <i class="fas fa-university badge-icon" aria-hidden="true"></i>
                    <h3 class="badge-title">NBTE</h3>
                    <p class="card-text">National Board for Technical Education<br>Accreditation for technical programs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Carousel - With beautiful, highly readable captions -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Learning Environment</h2>
                <p class="section-subtitle">Modern facilities supporting excellence in nursing education.</p>
            </div>
            <div class="gallery-carousel">
                <div class="gallery-slide active" style="background-image: url('<?php echo $baseUrl; ?>/public/assets/images/about/simulation-lab.jpg');">
                    <div class="gallery-caption">
                        <h3>Simulation Laboratory</h3>
                        <p>State-of-the-art simulation lab where students practice clinical skills in a controlled, realistic environment.</p>
                    </div>
                </div>
                <div class="gallery-slide" style="background-image: url('<?php echo $baseUrl; ?>/public/assets/images/about/library.jpg');">
                    <div class="gallery-caption">
                        <h3>Medical Library</h3>
                        <p>Comprehensive collection of nursing journals, textbooks, and digital resources for research and study.</p>
                    </div>
                </div>
                <div class="gallery-slide" style="background-image: url('<?php echo $baseUrl; ?>/public/assets/images/about/classroom.jpg');">
                    <div class="gallery-caption">
                        <h3>Interactive Classrooms</h3>
                        <p>Technology-enhanced learning spaces designed for collaborative nursing education and discussion.</p>
                    </div>
                </div>
                <div class="gallery-slide" style="background-image: url('<?php echo $baseUrl; ?>/public/assets/images/about/campus-building.jpg');">
                    <div class="gallery-caption">
                        <h3>Main Campus</h3>
                        <p>The heart of our institution where future nursing professionals begin their transformative journey.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <h2 class="section-title">Join Our Nursing Community</h2>
            <p class="section-subtitle" style="max-width: 700px; margin: 0 auto var(--spacing-xl);">Begin your professional nursing journey at one of Nigeria's most respected institutions.</p>
            <div style="display: flex; gap: var(--spacing-md); justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo $baseUrl; ?>/admissions" class="btn-primary"><i class="fas fa-file-alt"></i> Begin Application</a>
                <a href="<?php echo $baseUrl; ?>/programs" class="btn-primary"><i class="fas fa-book-open"></i> Explore Programs</a>
            </div>
        </div>
    </section>
</main>

<!-- Auto-play Carousel Script -->
<script>
const gallerySlides = document.querySelectorAll('.gallery-slide');
let currentSlide = 0;
const intervalTime = 5000;

function nextGallerySlide() {
    gallerySlides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % gallerySlides.length;
    gallerySlides[currentSlide].classList.add('active');
}

setInterval(nextGallerySlide, intervalTime);
</script>

</body>
</html>