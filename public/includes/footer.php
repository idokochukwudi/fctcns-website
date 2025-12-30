<?php
/**
 * Footer Template
 * 
 * This is the main footer file included in all views
 * 
 * @package FCT_CNS
 */

// Get data passed from controller or use defaults
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '');
$currentPage = $currentPage ?? 'home';

// Define current year
$currentYear = date('Y');
?>

    </main>
    
    <!-- Footer -->
    <footer class="page-footer" style="background: linear-gradient(135deg, #1a1f2e 0%, #0f1419 100%); color: #ffffff; margin-top: auto;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            
            <!-- Main Footer Content -->
            <div style="padding: 60px 0 40px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 50px;">
                    
                    <!-- Column 1: Brand & Mission -->
                    <div style="grid-column: span 1;">
                        <a href="<?php echo $baseUrl; ?>/" style="display: inline-block; margin-bottom: 24px;">
                            <img src="<?php echo $baseUrl; ?>/assets/images/logo/logo-white.png" 
                                 alt="FCT College of Nursing Sciences" 
                                 style="height: 48px; width: auto;">
                        </a>
                        <p style="color: #9ca3af; font-size: 15px; line-height: 1.7; margin-bottom: 24px; max-width: 280px;">
                            Empowering the next generation of healthcare leaders through excellence in nursing education and compassionate care.
                        </p>
                        
                        <!-- Social Media Links -->
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            <a href="#" aria-label="Facebook" 
                               style="width: 40px; height: 40px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1);"
                               onmouseover="this.style.background='#1877F2'; this.style.transform='translateY(-2px)'; this.style.borderColor='#1877F2';" 
                               onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(255, 255, 255, 0.1)';">
                                <i class="fab fa-facebook-f" style="font-size: 16px;"></i>
                            </a>
                            <a href="#" aria-label="Twitter"
                               style="width: 40px; height: 40px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1);"
                               onmouseover="this.style.background='#1DA1F2'; this.style.transform='translateY(-2px)'; this.style.borderColor='#1DA1F2';" 
                               onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(255, 255, 255, 0.1)';">
                                <i class="fab fa-twitter" style="font-size: 16px;"></i>
                            </a>
                            <a href="#" aria-label="Instagram"
                               style="width: 40px; height: 40px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1);"
                               onmouseover="this.style.background='linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D)'; this.style.transform='translateY(-2px)'; this.style.borderColor='#E1306C';" 
                               onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(255, 255, 255, 0.1)';">
                                <i class="fab fa-instagram" style="font-size: 16px;"></i>
                            </a>
                            <a href="#" aria-label="LinkedIn"
                               style="width: 40px; height: 40px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1);"
                               onmouseover="this.style.background='#0077B5'; this.style.transform='translateY(-2px)'; this.style.borderColor='#0077B5';" 
                               onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(255, 255, 255, 0.1)';">
                                <i class="fab fa-linkedin-in" style="font-size: 16px;"></i>
                            </a>
                            <a href="#" aria-label="YouTube"
                               style="width: 40px; height: 40px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1);"
                               onmouseover="this.style.background='#FF0000'; this.style.transform='translateY(-2px)'; this.style.borderColor='#FF0000';" 
                               onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(255, 255, 255, 0.1)';">
                                <i class="fab fa-youtube" style="font-size: 16px;"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Column 2: Quick Links -->
                    <div>
                        <h3 style="font-family: var(--font-display, 'Segoe UI', sans-serif); font-size: 16px; font-weight: 700; color: #ffffff; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Quick Links
                        </h3>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    Home
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/about" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    About Us
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/programs" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    Academic Programs
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/admissions" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    Admissions
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/student-life" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    Student Life
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/contact" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    Contact Us
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Column 3: Resources -->
                    <div>
                        <h3 style="font-family: var(--font-display, 'Segoe UI', sans-serif); font-size: 16px; font-weight: 700; color: #ffffff; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Resources
                        </h3>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/portal" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    Student Portal
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/library" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    Library Services
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/news" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    News & Events
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/careers" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    Careers
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/alumni" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    Alumni Network
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="<?php echo $baseUrl; ?>/research" 
                                   style="color: #9ca3af; text-decoration: none; font-size: 15px; transition: all 0.2s; display: inline-block;"
                                   onmouseover="this.style.color='#4a90e2'; this.style.paddingLeft='8px';"
                                   onmouseout="this.style.color='#9ca3af'; this.style.paddingLeft='0';">
                                    Research & Publications
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Column 4: Contact Information -->
                    <div>
                        <h3 style="font-family: var(--font-display, 'Segoe UI', sans-serif); font-size: 16px; font-weight: 700; color: #ffffff; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Contact Info
                        </h3>
                        <div style="color: #9ca3af; font-size: 15px; line-height: 1.8;">
                            <div style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                                <i class="fas fa-map-marker-alt" style="color: #4a90e2; margin-right: 12px; width: 18px; margin-top: 4px; flex-shrink: 0;"></i>
                                <span>Plot 123, Garki District,<br>Abuja, FCT, Nigeria</span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                                <i class="fas fa-phone" style="color: #4a90e2; margin-right: 12px; width: 18px; flex-shrink: 0;"></i>
                                <a href="tel:+23492900000" style="color: #9ca3af; text-decoration: none; transition: color 0.2s;"
                                   onmouseover="this.style.color='#4a90e2';"
                                   onmouseout="this.style.color='#9ca3af';">
                                    +234 (0) 9 290 0000
                                </a>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                                <i class="fas fa-envelope" style="color: #4a90e2; margin-right: 12px; width: 18px; flex-shrink: 0;"></i>
                                <a href="mailto:info@fctcns.edu.ng" style="color: #9ca3af; text-decoration: none; transition: color 0.2s;"
                                   onmouseover="this.style.color='#4a90e2';"
                                   onmouseout="this.style.color='#9ca3af';">
                                    info@fctcns.edu.ng
                                </a>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                                <i class="fas fa-clock" style="color: #4a90e2; margin-right: 12px; width: 18px; flex-shrink: 0;"></i>
                                <span>Mon - Fri: 8:00 AM - 5:00 PM</span>
                            </div>
                            
                            <!-- Accreditation Badges -->
                            <div style="margin-top: 24px;">
                                <div style="display: flex; flex-direction: column; gap: 10px;">
                                    <div style="background: rgba(74, 144, 226, 0.1); border: 1px solid rgba(74, 144, 226, 0.3); border-radius: 6px; padding: 10px 14px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease;"
                                         onmouseover="this.style.borderColor='#4a90e2'; this.style.background='rgba(74, 144, 226, 0.15)';"
                                         onmouseout="this.style.borderColor='rgba(74, 144, 226, 0.3)'; this.style.background='rgba(74, 144, 226, 0.1)';">
                                        <i class="fas fa-check-circle" style="color: #4a90e2; font-size: 14px;"></i>
                                        <span style="font-weight: 600; color: #4a90e2; font-size: 13px;">NMCN Accredited</span>
                                    </div>
                                    <div style="background: rgba(127, 178, 133, 0.1); border: 1px solid rgba(127, 178, 133, 0.3); border-radius: 6px; padding: 10px 14px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease;"
                                         onmouseover="this.style.borderColor='#7fb285'; this.style.background='rgba(127, 178, 133, 0.15)';"
                                         onmouseout="this.style.borderColor='rgba(127, 178, 133, 0.3)'; this.style.background='rgba(127, 178, 133, 0.1)';">
                                        <i class="fas fa-check-circle" style="color: #7fb285; font-size: 14px;"></i>
                                        <span style="font-weight: 600; color: #7fb285; font-size: 13px;">NBTE Approved</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Footer Bottom -->
            <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding: 28px 0;">
                <div style="display: flex; flex-direction: column; gap: 16px; align-items: center; text-align: center;">
                    <div style="display: flex; flex-wrap: wrap; gap: 6px 20px; justify-content: center; font-size: 14px;">
                        <a href="<?php echo $baseUrl; ?>/privacy" 
                           style="color: #9ca3af; text-decoration: none; transition: color 0.2s;"
                           onmouseover="this.style.color='#4a90e2';"
                           onmouseout="this.style.color='#9ca3af';">
                            Privacy Policy
                        </a>
                        <span style="color: #4b5563;">•</span>
                        <a href="<?php echo $baseUrl; ?>/terms" 
                           style="color: #9ca3af; text-decoration: none; transition: color 0.2s;"
                           onmouseover="this.style.color='#4a90e2';"
                           onmouseout="this.style.color='#9ca3af';">
                            Terms of Service
                        </a>
                        <span style="color: #4b5563;">•</span>
                        <a href="<?php echo $baseUrl; ?>/accessibility" 
                           style="color: #9ca3af; text-decoration: none; transition: color 0.2s;"
                           onmouseover="this.style.color='#4a90e2';"
                           onmouseout="this.style.color='#9ca3af';">
                            Accessibility
                        </a>
                        <span style="color: #4b5563;">•</span>
                        <a href="<?php echo $baseUrl; ?>/sitemap" 
                           style="color: #9ca3af; text-decoration: none; transition: color 0.2s;"
                           onmouseover="this.style.color='#4a90e2';"
                           onmouseout="this.style.color='#9ca3af';">
                            Sitemap
                        </a>
                    </div>
                    <p style="color: #6b7280; font-size: 14px; margin: 0;">
                        &copy; <?php echo $currentYear; ?> FCT College of Nursing Sciences. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <?php 
    // Only load carousel.js on homepage
    if ($currentPage == 'home'): ?>
    <script src="<?php echo $baseUrl; ?>/assets/js/carousel.js"></script>
    <?php endif; ?>

    <script src="<?php echo $baseUrl; ?>/assets/js/main.js"></script>

    <script>
    // Navigation functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile tabs toggle function
        window.toggleMobileTabs = function(button) {
            const menu = document.getElementById('mobileTabsMenu');
            const toggle = button;
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            
            menu.classList.toggle('active');
            toggle.classList.toggle('active');
            toggle.setAttribute('aria-expanded', !isExpanded);
            
            // Prevent body scroll when menu is open
            if (!isExpanded) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        };

        // Close flash messages
        document.querySelectorAll('.flash-close').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });

        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(message => {
                message.style.display = 'none';
            });
        }, 5000);

        // Navbar scroll effect
        let lastScroll = 0;
        const navbar = document.querySelector('.navbar');
        const tabsContainer = document.querySelector('.nav-tabs-container');
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 20) {
                navbar.classList.add('scrolled');
                if (tabsContainer) {
                    tabsContainer.style.boxShadow = '0 2px 15px rgba(107, 78, 155, 0.08)';
                }
            } else {
                navbar.classList.remove('scrolled');
                if (tabsContainer) {
                    tabsContainer.style.boxShadow = '0 2px 15px rgba(107, 78, 155, 0.05)';
                }
            }
            
            lastScroll = currentScroll;
        });

        // Initialize with scroll effect
        window.dispatchEvent(new Event('scroll'));

        // Search functionality
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        window.location.href = '<?php echo $baseUrl; ?>/search?q=' + encodeURIComponent(query);
                    }
                }
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const toggle = document.querySelector('.mobile-tabs-toggle');
            const menu = document.getElementById('mobileTabsMenu');
            
            if (toggle && menu && !toggle.contains(event.target) && !menu.contains(event.target) && menu.classList.contains('active')) {
                menu.classList.remove('active');
                toggle.classList.remove('active');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Close mobile menu on resize to desktop
                if (window.innerWidth > 992) {
                    const menu = document.getElementById('mobileTabsMenu');
                    const toggle = document.querySelector('.mobile-tabs-toggle');
                    
                    if (menu && menu.classList.contains('active')) {
                        menu.classList.remove('active');
                        toggle.classList.remove('active');
                        toggle.setAttribute('aria-expanded', 'false');
                        document.body.style.overflow = '';
                    }
                }
            }, 250);
        });

        // Add CSRF token to all forms
        document.querySelectorAll('form').forEach(form => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken && !form.querySelector('input[name="csrf_token"]')) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }
        });
    });

    // Handle AJAX requests with CSRF token
    const originalFetch = window.fetch;
    window.fetch = function(url, options = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (csrfToken) {
            options.headers = {
                ...options.headers,
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            };
        }
        
        return originalFetch(url, options);
    };
    </script>
</body>
</html>