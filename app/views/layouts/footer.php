<?php
/**
 * Footer Template - Professional Redesign
 *
 * @package FCT_CNS
 */

// Fallback values
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '');
$currentPage = $currentPage ?? 'home';
$currentYear = date('Y');
?>

    </main>

    <!-- Footer -->
    <footer class="page-footer">
        <div class="footer-container">
            <!-- Main Footer Content -->
            <div class="footer-main">
                <!-- Brand & About -->
                <div class="footer-section footer-about">
                    <div class="footer-logo">
                        <img src="<?= htmlspecialchars($baseUrl) ?>/assets/images/logo/logo-footer.png"
                             alt="FCT College of Nursing Sciences Logo"
                             height="60"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='<?= htmlspecialchars($baseUrl) ?>/assets/images/logo/logo-placeholder.png'">
                    </div>
                    
                    <p class="footer-about-text">
                        FCT College of Nursing Sciences is a premier institution dedicated to excellence in nursing education, research, and healthcare training in Nigeria's Federal Capital Territory.
                    </p>
                    
                    <div class="footer-social">
                        <span class="social-label">Follow Us:</span>
                        <div class="social-icons">
                            <a href="#" class="social-icon" aria-label="Facebook" title="Follow us on Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-icon" aria-label="Twitter" title="Follow us on Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon" aria-label="Instagram" title="Follow us on Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-icon" aria-label="LinkedIn" title="Follow us on LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-icon" aria-label="YouTube" title="Watch us on YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section footer-links">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-menu">
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/">Home</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/about">About Us</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/programs">Academic Programs</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/admissions">Admissions</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/facilities">Facilities</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/student-life">Student Life</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div class="footer-section footer-links">
                    <h3 class="footer-heading">Resources</h3>
                    <ul class="footer-menu">
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/portal">Student Portal</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/library">Digital Library</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/news">News & Events</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/alumni">Alumni Network</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/research">Research</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/careers">Careers</a></li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="footer-section footer-contact">
                    <h3 class="footer-heading">Contact Information</h3>
                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-text">
                                <strong>Address:</strong>
                                <p>Plot 123, Garki District<br>Abuja, FCT, Nigeria</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-text">
                                <strong>Phone:</strong>
                                <p><a href="tel:+23492900000">+234 (0) 9 290 0000</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <strong>Email:</strong>
                                <p><a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-text">
                                <strong>Office Hours:</strong>
                                <p>Mon - Fri: 8:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accreditation & Trust Badges -->
            <div class="footer-accreditation">
                <div class="accreditation-title">Officially Accredited By:</div>
                <div class="accreditation-badges">
                    <div class="accreditation-badge badge-nmcn">
                        <div class="badge-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div class="badge-content">
                            <div class="badge-title">NMCN Accredited</div>
                            <div class="badge-subtitle">Nursing & Midwifery Council of Nigeria</div>
                        </div>
                    </div>
                    
                    <div class="accreditation-badge badge-nbte">
                        <div class="badge-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="badge-content">
                            <div class="badge-title">NBTE Approved</div>
                            <div class="badge-subtitle">National Board for Technical Education</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <!-- Copyright -->
                    <div class="footer-copyright">
                        <p>&copy; <?= $currentYear ?> FCT College of Nursing Sciences. All Rights Reserved.</p>
                        <p class="developer-credit">Website developed by Cloudit Technologies</p>
                    </div>
                    
                    <!-- Legal Links -->
                    <nav class="footer-legal" aria-label="Legal links">
                        <a href="<?= htmlspecialchars($baseUrl) ?>/privacy">Privacy Policy</a>
                        <span class="separator">•</span>
                        <a href="<?= htmlspecialchars($baseUrl) ?>/terms">Terms of Service</a>
                        <span class="separator">•</span>
                        <a href="<?= htmlspecialchars($baseUrl) ?>/accessibility">Accessibility</a>
                        <span class="separator">•</span>
                        <a href="<?= htmlspecialchars($baseUrl) ?>/sitemap">Sitemap</a>
                    </nav>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" aria-label="Back to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Scripts -->
    <script src="<?= htmlspecialchars($baseUrl) ?>/assets/js/main.js"></script>
    <?php if ($currentPage === 'home'): ?>
        <script src="<?= htmlspecialchars($baseUrl) ?>/assets/js/carousel.js"></script>
    <?php endif; ?>

    <!-- Footer-specific JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Back to top button
            const backToTopButton = document.querySelector('.back-to-top');
            
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('visible');
                } else {
                    backToTopButton.classList.remove('visible');
                }
            });
            
            backToTopButton.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Accreditation badge interaction
            document.querySelectorAll('.accreditation-badge').forEach(badge => {
                badge.addEventListener('click', () => {
                    const type = badge.classList.contains('badge-nmcn') ? 'NMCN' : 'NBTE';
                    window.location.href = '<?= htmlspecialchars($baseUrl) ?>/accreditation';
                });
                
                badge.addEventListener('keypress', e => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const type = badge.classList.contains('badge-nmcn') ? 'NMCN' : 'NBTE';
                        window.location.href = '<?= htmlspecialchars($baseUrl) ?>/accreditation';
                    }
                });
            });
            
            // Handle logo fallback
            const footerLogo = document.querySelector('.footer-logo img');
            if (footerLogo) {
                footerLogo.addEventListener('error', function() {
                    // Create a text-based fallback
                    const logoContainer = this.parentElement;
                    this.style.display = 'none';
                    
                    const textLogo = document.createElement('div');
                    textLogo.className = 'text-logo-fallback';
                    textLogo.innerHTML = `
                        <div class="logo-text">
                            <div class="logo-main">FCT</div>
                            <div class="logo-sub">College of Nursing Sciences</div>
                        </div>
                    `;
                    logoContainer.appendChild(textLogo);
                });
            }
        });
    </script>

    <!-- Footer-only Styles -->
    <style>
        /* ==========================================================================
           FOOTER STYLES - PROFESSIONAL REDESIGN
           ========================================================================== */
        .page-footer {
            background: linear-gradient(135deg, var(--color-gray-900), var(--color-black));
            color: var(--color-white);
            font-family: var(--font-body);
            position: relative;
            margin-top: 0;
            border-top: 1px solid var(--color-gray-800);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 1.5rem 2rem;
        }

        /* Main Footer Layout */
        .footer-main {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 3rem;
            margin-bottom: 3rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-section {
            margin-bottom: 0;
        }

        /* About Section - FIXED LOGO STYLING */
        .footer-about .footer-logo {
            margin-bottom: 1.5rem;
            min-height: 60px;
            display: flex;
            align-items: center;
        }

        .footer-logo img {
            height: 60px;
            width: auto;
            /* REMOVED the filter that was making it white */
            /* filter: brightness(0) invert(1); */
        }

        /* Text-based logo fallback */
        .text-logo-fallback {
            display: none;
        }

        .footer-logo img:not([src]), 
        .footer-logo img[src=""],
        .footer-logo:has(.text-logo-fallback) img {
            display: none;
        }

        .footer-logo:has(.text-logo-fallback) .text-logo-fallback {
            display: block;
        }

        .logo-text {
            font-family: var(--font-heading);
            color: var(--color-white);
        }

        .logo-main {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--color-primary);
            line-height: 1;
        }

        .logo-sub {
            font-size: 0.9rem;
            color: var(--color-white);
            font-weight: 400;
            margin-top: 0.25rem;
            line-height: 1.2;
        }

        .footer-about-text {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .footer-social {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .social-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .social-icons {
            display: flex;
            gap: 0.75rem;
        }

        .social-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-white);
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-icon:hover {
            background: var(--color-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(93, 74, 138, 0.3);
        }

        /* Links Sections */
        .footer-heading {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--color-white);
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--color-accent);
        }

        .footer-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-menu li {
            margin-bottom: 0.75rem;
        }

        .footer-menu a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-menu a:hover {
            color: var(--color-accent);
            transform: translateX(5px);
        }

        /* Contact Section */
        .contact-details {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .contact-item {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .contact-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-accent);
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .contact-text strong {
            display: block;
            color: var(--color-white);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            font-family: var(--font-heading);
        }

        .contact-text p,
        .contact-text a {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-text a:hover {
            color: var(--color-accent);
        }

        /* Accreditation Section */
        .footer-accreditation {
            margin-bottom: 2.5rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .accreditation-title {
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
            font-family: var(--font-heading);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            letter-spacing: 0.5px;
        }

        .accreditation-badges {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .accreditation-badge {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            max-width: 280px;
            flex: 1;
            min-width: 250px;
        }

        .accreditation-badge:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .badge-nmcn {
            background: linear-gradient(135deg, rgba(93, 74, 138, 0.15), rgba(93, 74, 138, 0.05));
            border-color: rgba(93, 74, 138, 0.3);
        }

        .badge-nbte {
            background: linear-gradient(135deg, rgba(58, 107, 143, 0.15), rgba(58, 107, 143, 0.05));
            border-color: rgba(58, 107, 143, 0.3);
        }

        .badge-icon {
            width: 50px;
            height: 50px;
            background: var(--color-white);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .badge-nmcn .badge-icon {
            color: var(--color-primary);
        }

        .badge-nbte .badge-icon {
            color: var(--color-secondary);
        }

        .badge-content {
            flex: 1;
        }

        .badge-title {
            font-family: var(--font-heading);
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-white);
            margin-bottom: 0.25rem;
        }

        .badge-subtitle {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.4;
        }

        /* Footer Bottom */
        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-bottom-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .footer-copyright {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .footer-copyright p {
            margin: 0.25rem 0;
        }

        .developer-credit {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
            font-style: italic;
        }

        .footer-legal {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .footer-legal a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        .footer-legal a:hover {
            color: var(--color-accent);
        }

        .separator {
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.75rem;
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 48px;
            height: 48px;
            background: var(--color-primary);
            color: var(--color-white);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--color-primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        /* ==========================================================================
           RESPONSIVE DESIGN
           ========================================================================== */

        /* Tablet */
        @media (max-width: 1024px) {
            .footer-main {
                grid-template-columns: repeat(2, 1fr);
                gap: 2.5rem;
            }
            
            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .footer-legal {
                justify-content: center;
            }
            
            .accreditation-badges {
                gap: 1.5rem;
            }
            
            .accreditation-badge {
                min-width: 220px;
            }
        }

        /* Mobile */
        @media (max-width: 768px) {
            .footer-container {
                padding: 2rem 1rem 1.5rem;
            }
            
            .footer-main {
                grid-template-columns: 1fr;
                gap: 2.5rem;
                margin-bottom: 2rem;
                padding-bottom: 2rem;
            }
            
            .footer-accreditation {
                padding: 1.5rem 1rem;
                margin-bottom: 2rem;
            }
            
            .accreditation-badges {
                flex-direction: column;
                align-items: center;
            }
            
            .accreditation-badge {
                min-width: 100%;
                max-width: 100%;
            }
            
            .back-to-top {
                bottom: 1rem;
                right: 1rem;
                width: 44px;
                height: 44px;
                font-size: 1.1rem;
            }
            
            .footer-about-text {
                font-size: 0.9rem;
            }
            
            .footer-heading {
                font-size: 1rem;
            }
            
            .footer-menu a,
            .contact-text p,
            .contact-text a {
                font-size: 0.9rem;
            }
        }

        /* Small Mobile */
        @media (max-width: 480px) {
            .footer-main {
                gap: 2rem;
            }
            
            .footer-section {
                text-align: center;
            }
            
            .footer-heading::after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .footer-social {
                align-items: center;
            }
            
            .social-icons {
                justify-content: center;
            }
            
            .contact-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 0.75rem;
            }
            
            .contact-icon {
                margin: 0 auto;
            }
            
            .footer-menu li {
                text-align: center;
            }
            
            .footer-menu a:hover {
                transform: none;
            }
            
            .footer-copyright {
                font-size: 0.85rem;
            }
            
            .footer-legal {
                font-size: 0.8rem;
            }
        }

        /* Print Styles */
        @media print {
            .page-footer {
                background: white;
                color: black;
                border-top: 2px solid #ddd;
            }
            
            /* REMOVED the filter for print */
            .footer-logo img {
                filter: none;
            }
            
            .social-icons,
            .back-to-top {
                display: none;
            }
            
            .footer-menu a,
            .contact-text a {
                color: black;
            }
        }
    </style>
</body>
</html>