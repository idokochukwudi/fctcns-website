/**
 * Navigation functionality - Mobile menu, dropdowns
 * Complete professional version with all fixes
 */

class Navigation {
    constructor() {
        this.navbarToggle = document.querySelector('.navbar-toggle');
        this.navbarNav = document.querySelector('.navbar-nav');
        this.dropdowns = document.querySelectorAll('.dropdown');
        this.dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        
        this.isMobile = window.innerWidth <= 768;
        
        this.init();
        this.setupEventListeners();
        this.handleResize();
    }
    
    init() {
        console.log('Navigation initialized');
        
        // Initialize dropdown state
        this.dropdowns.forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    }
    
    setupEventListeners() {
        // Mobile menu toggle
        if (this.navbarToggle) {
            this.navbarToggle.addEventListener('click', (e) => this.toggleMobileMenu(e));
        }
        
        // Handle dropdown toggles - with proper event handling
        this.dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => this.handleDropdownToggle(e));
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => this.handleClickOutside(e));
        
        // Close mobile menu when clicking a link
        document.querySelectorAll('.navbar-nav a').forEach(link => {
            link.addEventListener('click', () => {
                if (this.isMobile) {
                    this.closeMobileMenu();
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', () => this.handleResize());
    }
    
    handleResize() {
        this.isMobile = window.innerWidth <= 768;
        
        // Close all dropdowns when switching between mobile and desktop
        if (this.isMobile) {
            // On mobile, ensure dropdowns are closed initially
            this.dropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        } else {
            // On desktop, close mobile menu if open
            this.closeMobileMenu();
        }
    }
    
    toggleMobileMenu(e) {
        if (e) e.preventDefault();
        
        this.navbarToggle.classList.toggle('active');
        this.navbarNav.classList.toggle('mobile-menu-active');
        
        const isExpanded = this.navbarToggle.classList.contains('active');
        this.navbarToggle.setAttribute('aria-expanded', isExpanded.toString());
        
        // Close all dropdowns when toggling mobile menu
        if (!isExpanded) {
            this.dropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
    }
    
    closeMobileMenu() {
        this.navbarToggle.classList.remove('active');
        this.navbarNav.classList.remove('mobile-menu-active');
        this.navbarToggle.setAttribute('aria-expanded', 'false');
        
        // Close all dropdowns
        this.dropdowns.forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    }
    
    handleDropdownToggle(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const dropdown = e.target.closest('.dropdown');
        if (!dropdown) return;
        
        const isCurrentlyActive = dropdown.classList.contains('active');
        
        // Close all other dropdowns
        this.dropdowns.forEach(other => {
            if (other !== dropdown) {
                other.classList.remove('active');
            }
        });
        
        // Toggle current dropdown
        if (isCurrentlyActive) {
            dropdown.classList.remove('active');
        } else {
            dropdown.classList.add('active');
        }
        
        // On mobile, keep menu open when clicking dropdown
        if (this.isMobile && !this.navbarNav.classList.contains('mobile-menu-active')) {
            this.toggleMobileMenu(e);
        }
    }
    
    handleClickOutside(e) {
        // Don't close if clicking on navbar toggle
        if (e.target.closest('.navbar-toggle')) {
            return;
        }
        
        // Close dropdowns if clicking outside
        if (!e.target.closest('.dropdown') && !e.target.closest('.navbar-nav')) {
            this.dropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
        
        // Close mobile menu if clicking outside
        if (this.isMobile && 
            !e.target.closest('.navbar-nav') && 
            !e.target.closest('.navbar-toggle') &&
            this.navbarNav.classList.contains('mobile-menu-active')) {
            this.closeMobileMenu();
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    try {
        new Navigation();
        console.log('Navigation system loaded successfully');
    } catch (error) {
        console.error('Failed to initialize navigation:', error);
    }
});

// Export for potential module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Navigation;
}