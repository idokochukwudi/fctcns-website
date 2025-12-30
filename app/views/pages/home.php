<?php
/**
 * Homepage View Template
 * 
 * Pure MVC view - only displays data passed from PageController.
 * NO PHP logic, NO database calls, NO header/footer includes.
 * 
 * Available variables from PageController:
 * - $carouselSlides: Array of carousel slides
 * - $baseUrl: Base URL for assets
 * - $page_title, $page_description, $currentPage, etc.
 * 
 * @package FCTCNS
 * @version 2.0
 */

// Extract data passed from controller
extract($data ?? []);

// Helper function for escaping output - ONLY define if not already defined
if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// Set defaults
$carouselSlides = $carouselSlides ?? [];
?>

<!-- Page-specific CSS -->
 <!-- Add this to the TOP of your homepage template CSS section -->
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
:root {
    --carousel-height: 500px;
    --carousel-height-mobile: 350px;
    --transition-base: all 0.3s ease;
    --transition-fast: all 0.2s ease;
    --color-primary: #6B4E9B;
    --color-primary-dark: #5a4185;
    --color-primary-light: #7B5CAE;
    --color-success: #28a745;
    --color-white: #ffffff;
    --color-gray-50: #f8f9fa;
    --color-gray-100: #f1f3f4;
    --color-gray-200: #e9ecef;
    --color-gray-600: #6c757d;
    --color-gray-800: #343a40;
    --font-heading: 'Poppins', sans-serif;
}

/* CRITICAL FIX: Remove all default spacing */
body, html {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

.homepage-content {
    margin-top: 0;
    padding-top: 0;
}

/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Hero Carousel Styles */
.hero-section {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.hero-carousel {
    position: relative;
    width: 100%;
    height: var(--carousel-height);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-top: 0;
    padding-top: 0;
}

.carousel-inner {
    position: relative;
    width: 100%;
    height: 100%;
}

.carousel-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.6s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
}

.carousel-slide.active {
    opacity: 1;
    z-index: 1;
}

.carousel-slide-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.carousel-slide-bg::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.85), rgba(127, 178, 133, 0.75));
}

.carousel-slide-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    max-width: 800px;
    padding: 2rem;
    width: 100%;
}

.carousel-slide-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    font-family: var(--font-heading);
    line-height: 1.2;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
}

.carousel-slide-subtitle {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    font-weight: 400;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.carousel-slide-btn {
    padding: 0.875rem 2.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    background: white;
    color: var(--color-primary);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition-base);
    text-decoration: none;
    display: inline-block;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.carousel-slide-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
    background: var(--color-gray-100);
}

/* Carousel Controls */
.carousel-controls {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
    display: flex;
    gap: 0.5rem;
}

.carousel-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    border: none;
    cursor: pointer;
    transition: var(--transition-fast);
}

.carousel-indicator.active {
    background: white;
    width: 32px;
    border-radius: 6px;
}

.carousel-indicator:hover {
    background: rgba(255, 255, 255, 0.8);
}

.carousel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition-fast);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    z-index: 3;
}

.carousel-nav:hover {
    background: rgba(255, 255, 255, 0.3);
}

.carousel-nav-prev {
    left: 2rem;
}

.carousel-nav-next {
    right: 2rem;
}

/* Fallback Carousel */
.carousel-fallback {
    background: linear-gradient(135deg, #6B4E9B, #7FB285);
    height: var(--carousel-height);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
    padding: 2rem;
    width: 100%;
    margin-top: 0;
}

.carousel-fallback-content {
    max-width: 800px;
    padding: 2rem;
}

.carousel-fallback h2 {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
    font-family: var(--font-heading);
}

.carousel-fallback p {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

/* Statistics Section */
.stats-section {
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    color: white;
    padding: 2.5rem 0;
    position: relative;
    z-index: 1;
    margin-top: 0;
    width: 100%;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    text-align: center;
}

.stat-item {
    padding: 1rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-family: var(--font-heading);
}

.stat-label {
    font-size: 1rem;
    opacity: 0.9;
}

/* Accreditation Badges */
.accreditation-badges {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.accreditation-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: white;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    color: var(--color-primary);
}

/* Section Spacing */
.section {
    padding: 3rem 0;
    position: relative;
    z-index: 1;
    width: 100%;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
    width: 100%;
}

/* Text alignment */
.text-center {
    text-align: center;
}

.text-justify-center {
    text-align: center;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

/* Section Headers */
.section-header {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: var(--color-gray-800);
    font-family: var(--font-heading);
}

.section-subtitle {
    font-size: 1.25rem;
    color: var(--color-gray-600);
    line-height: 1.5;
}

/* Program Cards */
.program-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 1.5rem;
}

.program-card {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: var(--transition-base);
    border: 1px solid var(--color-gray-200);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.program-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border-color: var(--color-primary-light);
}

.program-card-header {
    margin-bottom: 1rem;
}

.program-card-accreditation {
    display: inline-block;
    background: var(--color-success);
    color: white;
    padding: 0.375rem 1rem;
    border-radius: 5px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.program-card-accreditation.nbte {
    background: var(--color-primary);
}

.program-card-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--color-gray-800);
    font-family: var(--font-heading);
}

.program-card-duration {
    color: var(--color-gray-600);
    font-size: 0.95rem;
    margin-bottom: 1rem;
    font-weight: 500;
}

.program-card-description {
    color: var(--color-gray-600);
    line-height: 1.6;
    margin-bottom: 1rem;
    font-size: 1rem;
    flex-grow: 1;
}

.program-card-requirements {
    font-size: 0.9rem;
    color: var(--color-gray-600);
    margin-bottom: 1.5rem;
    padding-left: 1rem;
    border-left: 3px solid var(--color-primary);
    background: var(--color-gray-50);
    padding: 1rem;
    border-radius: 5px;
}

.program-card-link {
    color: var(--color-primary);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-fast);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    margin-top: auto;
}

.program-card-link:hover {
    color: var(--color-primary-dark);
    gap: 0.75rem;
}

/* Button Styles */
.btn {
    display: inline-block;
    padding: 0.875rem 2rem;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition-base);
    border: 2px solid transparent;
    font-size: 1rem;
}

.btn-primary {
    background: var(--color-primary);
    color: var(--color-white);
}

.btn-primary:hover {
    background: var(--color-primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(107, 78, 155, 0.25);
}

.btn-outline {
    background: transparent;
    color: var(--color-primary);
    border-color: var(--color-primary);
}

.btn-outline:hover {
    background: var(--color-primary);
    color: var(--color-white);
    transform: translateY(-2px);
}

.btn-lg {
    padding: 1rem 2.5rem;
    font-size: 1.125rem;
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

.gap-6 {
    gap: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-carousel {
        height: var(--carousel-height-mobile);
    }
    
    .carousel-fallback {
        height: var(--carousel-height-mobile);
    }
    
    .carousel-slide-title {
        font-size: 1.75rem;
    }
    
    .carousel-slide-subtitle {
        font-size: 1.125rem;
    }
    
    .carousel-slide-btn {
        padding: 0.75rem 2rem;
        font-size: 1rem;
    }
    
    .carousel-nav {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }
    
    .carousel-nav-prev {
        left: 1rem;
    }
    
    .carousel-nav-next {
        right: 1rem;
    }
    
    .program-cards-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .section {
        padding: 2rem 0;
    }
    
    .section-title {
        font-size: 1.75rem;
    }
    
    .section-subtitle {
        font-size: 1.125rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .stats-section {
        padding: 2rem 0;
    }
}

@media (max-width: 480px) {
    .carousel-slide-title {
        font-size: 1.5rem;
    }
    
    .carousel-slide-subtitle {
        font-size: 1rem;
    }
    
    .carousel-slide-content {
        padding: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .accreditation-badges {
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
    
    .accreditation-badge {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .stat-number {
        font-size: 1.75rem;
    }
    
    .stats-section {
        padding: 1.5rem 0;
    }
}

/* FIX: Ensure no space at the top */
body > *:first-child {
    margin-top: 0 !important;
    padding-top: 0 !important;
}
</style>

<!-- Homepage Content -->
<div class="homepage-content">
    <!-- Hero Carousel Section -->
    <section class="hero-section" aria-label="Featured content carousel">
        <?php if (empty($carouselSlides)): ?>
            <!-- Fallback carousel -->
            <div class="carousel-fallback" role="region" aria-label="Welcome message">
                <div>
                    <h2>Welcome to FCT College of Nursing Sciences</h2>
                    <p>NMCN & NBTE Accredited Nursing Education Since 1989</p>
                    <a href="<?php echo $baseUrl; ?>/programs" class="btn btn-primary btn-lg">
                        Explore Programs
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Dynamic Carousel -->
            <div id="heroCarousel" class="hero-carousel" role="region" aria-label="Featured slides">
                <div class="carousel-inner">
                    <?php foreach ($carouselSlides as $index => $slide): ?>
                    <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                         data-slide="<?php echo $index; ?>"
                         aria-hidden="<?php echo $index === 0 ? 'false' : 'true'; ?>">
                        <div class="carousel-slide-bg" 
                             style="background-image: url('<?php echo e($slide['image_path']); ?>');"
                             role="img"
                             aria-label="<?php echo e($slide['title']); ?>">
                        </div>
                        <div class="carousel-slide-content">
                            <h2 class="carousel-slide-title">
                                <?php echo e($slide['title']); ?>
                            </h2>
                            <p class="carousel-slide-subtitle">
                                <?php echo e($slide['subtitle']); ?>
                            </p>
                            <?php if (!empty($slide['button_text']) && !empty($slide['button_link'])): ?>
                            <a href="<?php echo e($slide['button_link']); ?>" 
                               class="carousel-slide-btn">
                                <?php echo e($slide['button_text']); ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Navigation Arrows -->
                <button class="carousel-nav carousel-nav-prev" 
                        aria-label="Previous slide"
                        onclick="carouselController.prev()">
                    ‹
                </button>
                <button class="carousel-nav carousel-nav-next" 
                        aria-label="Next slide"
                        onclick="carouselController.next()">
                    ›
                </button>
                
                <!-- Indicators -->
                <div class="carousel-controls" role="group" aria-label="Carousel indicators">
                    <?php foreach ($carouselSlides as $index => $slide): ?>
                    <button class="carousel-indicator <?php echo $index === 0 ? 'active' : ''; ?>"
                            data-slide="<?php echo $index; ?>"
                            aria-label="Go to slide <?php echo $index + 1; ?>"
                            onclick="carouselController.goToSlide(<?php echo $index; ?>)">
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">35+</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5,000+</div>
                    <div class="stat-label">Graduates</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">NMCN Accredited</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Expert Faculty</div>
                </div>
            </div>
            
            <!-- Accreditation Badges -->
            <div class="accreditation-badges">
                <div class="accreditation-badge">
                    <span>NMCN</span>
                    <span>Accredited</span>
                </div>
                <div class="accreditation-badge">
                    <span>NBTE</span>
                    <span>Accredited</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Programs Section -->
    <section class="section">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Accredited Nursing Programs</h2>
                <p class="section-subtitle text-justify-center">
                    Fully approved programs meeting NMCN & NBTE standards. We offer comprehensive 
                    nursing education including National Diploma (ND) and Higher National Diploma (HND) 
                    programs recognized nationwide.
                </p>
            </div>
            
            <div class="program-cards-grid">
                <!-- Program 1: Basic Nursing -->
                <article class="program-card">
                    <div class="program-card-header">
                        <span class="program-card-accreditation">NMCN Accredited</span>
                        <h3 class="program-card-title">Basic Nursing</h3>
                        <div class="program-card-duration">Duration: 3 Years</div>
                    </div>
                    <p class="program-card-description">
                        Comprehensive general nursing education preparing students for registration as Registered Nurses (RN). Includes medical, surgical, pediatric, and community nursing.
                    </p>
                    <div class="program-card-requirements">
                        <strong>Entry Requirements:</strong><br>
                        • 5 Credits in English, Mathematics, Biology, Chemistry, Physics<br>
                        • WAEC/NECO/NABTEB at not more than 2 sittings<br>
                        • Minimum age: 16 years
                    </div>
                    <a href="<?php echo $baseUrl; ?>/programs" 
                       class="program-card-link"
                       aria-label="Learn more about Basic Nursing">
                        Learn More →
                    </a>
                </article>
                
                <!-- Program 2: National Diploma (ND) -->
                <article class="program-card">
                    <div class="program-card-header">
                        <span class="program-card-accreditation nbte">NBTE Accredited</span>
                        <h3 class="program-card-title">National Diploma in Nursing</h3>
                        <div class="program-card-duration">Duration: 2 Years</div>
                    </div>
                    <p class="program-card-description">
                        Polytechnic-based nursing education leading to ND qualification. Combines theoretical knowledge with practical skills for healthcare delivery in various settings.
                    </p>
                    <div class="program-card-requirements">
                        <strong>Entry Requirements:</strong><br>
                        • 5 Credits including English, Mathematics, Biology, Chemistry<br>
                        • JAMB UTME with required cut-off mark<br>
                        • Post-UTME screening<br>
                        • Minimum age: 16 years
                    </div>
                    <a href="<?php echo $baseUrl; ?>/programs" 
                       class="program-card-link"
                       aria-label="Learn more about National Diploma">
                        Learn More →
                    </a>
                </article>
                
                <!-- Program 3: Higher National Diploma (HND) -->
                <article class="program-card">
                    <div class="program-card-header">
                        <span class="program-card-accreditation nbte">NBTE Accredited</span>
                        <h3 class="program-card-title">Higher National Diploma in Nursing</h3>
                        <div class="program-card-duration">Duration: 2 Years</div>
                    </div>
                    <p class="program-card-description">
                        Advanced nursing education for Registered Nurses or ND holders. Focus on nursing administration, education, research, and specialized clinical practice.
                    </p>
                    <div class="program-card-requirements">
                        <strong>Entry Requirements:</strong><br>
                        • National Diploma in Nursing or equivalent<br>
                        • Minimum of 1-year post-ND experience<br>
                        • Current practicing license (for RN holders)<br>
                        • 5 O'Level Credits including English & Sciences
                    </div>
                    <a href="<?php echo $baseUrl; ?>/programs" 
                       class="program-card-link"
                       aria-label="Learn more about Higher National Diploma">
                        Learn More →
                    </a>
                </article>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section" style="background: linear-gradient(135deg, rgba(107, 78, 155, 0.08), rgba(127, 178, 133, 0.08));">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Begin Your Nursing Career Today</h2>
                <p class="section-subtitle text-justify-center">
                    Join our community of healthcare professionals making a difference across Nigeria. 
                    Our graduates are highly sought after in hospitals, clinics, and healthcare institutions nationwide.
                </p>
                <div class="flex flex-wrap justify-center gap-6" style="margin-top: 2rem;">
                    <a href="<?php echo $baseUrl; ?>/admissions" 
                       class="btn btn-primary btn-lg">
                        Apply Now
                    </a>
                    <a href="<?php echo $baseUrl; ?>/programs" 
                       class="btn btn-outline btn-lg">
                        View All Programs
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" 
                       class="btn btn-outline btn-lg">
                        Contact Admissions
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript for Carousel -->
<script>
(function() {
    'use strict';
    
    const carouselController = {
        currentSlide: 0,
        totalSlides: 0,
        autoPlayInterval: null,
        autoPlayDelay: 5000,
        isTransitioning: false,
        
        init() {
            const carousel = document.getElementById('heroCarousel');
            if (!carousel) return;
            
            const slides = carousel.querySelectorAll('.carousel-slide');
            this.totalSlides = slides.length;
            
            if (this.totalSlides === 0) return;
            
            this.startAutoPlay();
            
            // Pause autoplay on hover
            carousel.addEventListener('mouseenter', () => this.stopAutoPlay());
            carousel.addEventListener('mouseleave', () => this.startAutoPlay());
            
            // Add keyboard navigation
            carousel.setAttribute('tabindex', '0');
            carousel.addEventListener('keydown', (e) => this.handleKeyboard(e));
        },
        
        goToSlide(index) {
            if (this.isTransitioning || index === this.currentSlide) return;
            
            this.isTransitioning = true;
            
            const slides = document.querySelectorAll('.carousel-slide');
            const indicators = document.querySelectorAll('.carousel-indicator');
            
            // Update current slide
            slides[this.currentSlide].classList.remove('active');
            slides[this.currentSlide].setAttribute('aria-hidden', 'true');
            
            // Update new slide
            slides[index].classList.add('active');
            slides[index].setAttribute('aria-hidden', 'false');
            
            // Update indicators
            indicators[this.currentSlide].classList.remove('active');
            indicators[index].classList.add('active');
            
            this.currentSlide = index;
            
            setTimeout(() => {
                this.isTransitioning = false;
            }, 600);
        },
        
        next() {
            const nextIndex = (this.currentSlide + 1) % this.totalSlides;
            this.goToSlide(nextIndex);
        },
        
        prev() {
            const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            this.goToSlide(prevIndex);
        },
        
        startAutoPlay() {
            this.stopAutoPlay();
            this.autoPlayInterval = setInterval(() => {
                this.next();
            }, this.autoPlayDelay);
        },
        
        stopAutoPlay() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
                this.autoPlayInterval = null;
            }
        },
        
        handleKeyboard(e) {
            const carousel = document.getElementById('heroCarousel');
            if (!carousel || document.activeElement !== carousel) return;
            
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    this.prev();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    this.next();
                    break;
                case 'Home':
                    e.preventDefault();
                    this.goToSlide(0);
                    break;
                case 'End':
                    e.preventDefault();
                    this.goToSlide(this.totalSlides - 1);
                    break;
            }
        }
    };
    
    window.carouselController = carouselController;
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            carouselController.init();
        });
    } else {
        carouselController.init();
    }
    
    // Clean up on page unload
    window.addEventListener('beforeunload', () => {
        carouselController.stopAutoPlay();
    });
})();
</script>