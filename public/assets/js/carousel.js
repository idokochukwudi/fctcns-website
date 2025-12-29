/**
 * Carousel Component - Fetches slides from database
 * Auto-rotates, touch swipe, keyboard navigation
 */

class Carousel {
    constructor(elementId, options = {}) {
        this.carousel = document.getElementById(elementId);
        if (!this.carousel) return;
        
        this.options = {
            autoPlay: true,
            interval: 5000,
            transitionDuration: 500,
            pauseOnHover: true,
            ...options
        };
        
        this.slides = [];
        this.currentIndex = 0;
        this.isPlaying = this.options.autoPlay;
        this.timer = null;
        this.touchStartX = 0;
        this.touchEndX = 0;
        
        this.init();
    }
    
    async init() {
        await this.fetchSlides();
        this.render();
        this.setupEventListeners();
        if (this.isPlaying) this.startAutoPlay();
    }
    
    async fetchSlides() {
        try {
            const response = await fetch('/fctcns-website/public/api/carousel');
            if (!response.ok) throw new Error('Failed to fetch carousel slides');
            
            const data = await response.json();
            this.slides = data.slides || [];
            
            // If API fails, use fallback data
            if (this.slides.length === 0) {
                this.slides = this.getFallbackSlides();
            }
        } catch (error) {
            console.error('Carousel fetch error:', error);
            this.slides = this.getFallbackSlides();
        }
    }
    
    getFallbackSlides() {
        return [
            {
                title: "Welcome to FCT College of Nursing Sciences",
                subtitle: "Empowering Future Healthcare Professionals Since 1989",
                image_path: "/fctcns-website/public/assets/images/carousel/slide1.jpg",
                button_text: "Explore Programs",
                button_link: "/programs"
            },
            {
                title: "Excellence in Nursing Education",
                subtitle: "State-of-the-art facilities and experienced faculty",
                image_path: "/fctcns-website/public/assets/images/carousel/slide2.jpg",
                button_text: "Learn More",
                button_link: "/about"
            },
            {
                title: "Start Your Journey Today",
                subtitle: "Applications now open for 2025 admission",
                image_path: "/fctcns-website/public/assets/images/carousel/slide3.jpg",
                button_text: "Apply Now",
                button_link: "/admissions"
            }
        ];
    }
    
    render() {
        if (this.slides.length === 0) {
            this.carousel.innerHTML = '<div class="carousel__empty">Carousel loading...</div>';
            return;
        }
        
        this.carousel.innerHTML = `
            <div class="carousel__slides">
                ${this.slides.map((slide, index) => `
                    <div class="carousel__slide ${index === 0 ? 'active' : ''}" 
                         data-index="${index}"
                         role="group"
                         aria-roledescription="slide"
                         aria-label="Slide ${index + 1} of ${this.slides.length}">
                        <img src="${slide.image_path}" 
                             alt="${slide.title}" 
                             class="carousel__image"
                             loading="${index === 0 ? 'eager' : 'lazy'}">
                        <div class="carousel__content">
                            <h2 class="carousel__title">${slide.title}</h2>
                            <p class="carousel__subtitle">${slide.subtitle}</p>
                            <div class="carousel__buttons">
                                <a href="${slide.button_link}" class="btn btn-primary">
                                    ${slide.button_text}
                                </a>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
            
            <button class="carousel__nav carousel__nav--prev" 
                    aria-label="Previous slide">
                <svg class="carousel__nav-icon" viewBox="0 0 24 24">
                    <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/>
                </svg>
            </button>
            
            <button class="carousel__nav carousel__nav--next" 
                    aria-label="Next slide">
                <svg class="carousel__nav-icon" viewBox="0 0 24 24">
                    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                </svg>
            </button>
            
            <div class="carousel__indicators" role="tablist">
                ${this.slides.map((_, index) => `
                    <button class="carousel__indicator ${index === 0 ? 'active' : ''}"
                            data-index="${index}"
                            role="tab"
                            aria-label="Go to slide ${index + 1}"
                            aria-selected="${index === 0}">
                    </button>
                `).join('')}
            </div>
            
            <div class="carousel__progress">
                <div class="carousel__progress-bar"></div>
            </div>
            
            <button class="carousel__play-pause" 
                    aria-label="${this.isPlaying ? 'Pause carousel' : 'Play carousel'}">
                <svg class="carousel__play-pause-icon" viewBox="0 0 24 24">
                    ${this.isPlaying ? 
                        '<path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>' : 
                        '<path d="M8 5v14l11-7z"/>'}
                </svg>
            </button>
            
            <div class="carousel__counter" aria-live="polite">
                Slide ${this.currentIndex + 1} of ${this.slides.length}
            </div>
        `;
        
        this.updateProgressBar();
    }
    
    setupEventListeners() {
        // Previous/Next buttons
        this.carousel.querySelector('.carousel__nav--prev').addEventListener('click', () => this.prev());
        this.carousel.querySelector('.carousel__nav--next').addEventListener('click', () => this.next());
        
        // Indicators
        this.carousel.querySelectorAll('.carousel__indicator').forEach(indicator => {
            indicator.addEventListener('click', (e) => {
                const index = parseInt(e.target.dataset.index);
                this.goToSlide(index);
            });
        });
        
        // Play/Pause button
        this.carousel.querySelector('.carousel__play-pause').addEventListener('click', () => this.togglePlay());
        
        // Keyboard navigation
        this.carousel.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prev();
            if (e.key === 'ArrowRight') this.next();
            if (e.key === ' ') {
                e.preventDefault();
                this.togglePlay();
            }
        });
        
        // Touch swipe
        this.carousel.addEventListener('touchstart', (e) => {
            this.touchStartX = e.touches[0].clientX;
            this.pause();
        });
        
        this.carousel.addEventListener('touchmove', (e) => {
            this.touchEndX = e.touches[0].clientX;
        });
        
        this.carousel.addEventListener('touchend', () => {
            const diff = this.touchStartX - this.touchEndX;
            const threshold = 50;
            
            if (Math.abs(diff) > threshold) {
                if (diff > 0) this.next();
                else this.prev();
            }
            
            if (this.options.autoPlay) this.startAutoPlay();
        });
        
        // Pause on hover
        if (this.options.pauseOnHover) {
            this.carousel.addEventListener('mouseenter', () => this.pause());
            this.carousel.addEventListener('mouseleave', () => {
                if (this.options.autoPlay) this.startAutoPlay();
            });
        }
    }
    
    next() {
        this.goToSlide((this.currentIndex + 1) % this.slides.length);
    }
    
    prev() {
        this.goToSlide((this.currentIndex - 1 + this.slides.length) % this.slides.length);
    }
    
    goToSlide(index) {
        if (index < 0 || index >= this.slides.length || index === this.currentIndex) return;
        
        // Update slides
        const slides = this.carousel.querySelectorAll('.carousel__slide');
        const indicators = this.carousel.querySelectorAll('.carousel__indicator');
        
        slides[this.currentIndex].classList.remove('active');
        indicators[this.currentIndex].classList.remove('active');
        indicators[this.currentIndex].setAttribute('aria-selected', 'false');
        
        this.currentIndex = index;
        
        slides[this.currentIndex].classList.add('active');
        indicators[this.currentIndex].classList.add('active');
        indicators[this.currentIndex].setAttribute('aria-selected', 'true');
        
        // Update counter
        this.carousel.querySelector('.carousel__counter').textContent = 
            `Slide ${this.currentIndex + 1} of ${this.slides.length}`;
        
        // Reset progress bar
        this.updateProgressBar();
    }
    
    startAutoPlay() {
        this.pause();
        this.isPlaying = true;
        
        this.timer = setInterval(() => {
            this.next();
        }, this.options.interval);
        
        // Update play/pause button
        const icon = this.carousel.querySelector('.carousel__play-pause-icon');
        const button = this.carousel.querySelector('.carousel__play-pause');
        icon.innerHTML = '<path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>';
        button.setAttribute('aria-label', 'Pause carousel');
    }
    
    pause() {
        this.isPlaying = false;
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
        
        // Update play/pause button
        const icon = this.carousel.querySelector('.carousel__play-pause-icon');
        const button = this.carousel.querySelector('.carousel__play-pause');
        if (icon && button) {
            icon.innerHTML = '<path d="M8 5v14l11-7z"/>';
            button.setAttribute('aria-label', 'Play carousel');
        }
    }
    
    togglePlay() {
        if (this.isPlaying) this.pause();
        else this.startAutoPlay();
    }
    
    updateProgressBar() {
        const progressBar = this.carousel.querySelector('.carousel__progress-bar');
        if (!progressBar) return;
        
        progressBar.style.width = '0%';
        
        if (this.isPlaying) {
            const startTime = Date.now();
            const updateProgress = () => {
                if (!this.isPlaying) return;
                
                const elapsed = Date.now() - startTime;
                const progress = Math.min((elapsed / this.options.interval) * 100, 100);
                progressBar.style.width = `${progress}%`;
                
                if (progress < 100) {
                    requestAnimationFrame(updateProgress);
                }
            };
            requestAnimationFrame(updateProgress);
        }
    }
}

// Initialize carousel when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const carousel = new Carousel('homepage-carousel', {
        autoPlay: true,
        interval: 5000,
        pauseOnHover: true
    });
});