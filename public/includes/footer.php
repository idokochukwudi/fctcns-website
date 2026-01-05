<?php
/**
 * Footer Template
 *
 * @package FCT_CNS
 */

// Fallback values
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '');
$currentPage = $currentPage ?? 'home';
$currentYear = date('Y'); // Shows 2026
?>

    </main>

    <!-- Footer -->
    <footer class="page-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Brand & Social -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="<?= htmlspecialchars($baseUrl) ?>/assets/images/logo/logo-footer.png"
                             alt="FCT College of Nursing Sciences Logo"
                             height="48"
                             loading="lazy"
                             onerror="this.onerror=null; this.alt=''; this.src=''">
                    </div>

                    <p class="brand-slogan">
                        Empowering the next generation of healthcare leaders through excellence in nursing education and compassionate care.
                    </p>

                    <div class="social-links">
                        <a href="#" aria-label="Facebook" title="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter" title="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram" title="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="LinkedIn" title="Follow us on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/">Home</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/about">About Us</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/programs">Programs</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/admissions">Admissions</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/contact">Contact</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div class="footer-column">
                    <h3>Resources</h3>
                    <ul class="footer-links">
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/portal">Student Portal</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/library">Library</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/news">News</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/alumni">Alumni</a></li>
                        <li><a href="<?= htmlspecialchars($baseUrl) ?>/research">Research</a></li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="footer-column">
                    <h3>Contact Info</h3>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt contact-icon"></i>
                            <div>Plot 123, Garki District<br>Abuja, FCT, Nigeria</div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone contact-icon"></i>
                            <a href="tel:+23492900000">+234 (0) 9 290 0000</a>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope contact-icon"></i>
                            <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock contact-icon"></i>
                            <div>Mon - Fri: 8:00 AM - 5:00 PM</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-inner">
                    <!-- Bottom Navigation Links - Evenly spaced for justified look -->
                    <nav class="footer-bottom-links" aria-label="Footer navigation">
                        <a href="<?= htmlspecialchars($baseUrl) ?>/privacy">Privacy Policy</a>
                        <a href="<?= htmlspecialchars($baseUrl) ?>/terms">Terms of Service</a>
                        <a href="<?= htmlspecialchars($baseUrl) ?>/accessibility">Accessibility</a>
                        <a href="<?= htmlspecialchars($baseUrl) ?>/sitemap">Sitemap</a>
                    </nav>

                    <!-- Copyright & Developer Credit - Centered below links -->
                    <div class="footer-legal-text">
                        <p class="copyright-text">
                            © <?= $currentYear ?> FCT College of Nursing Sciences. All rights reserved.
                        </p>
                        <p class="developer-credit">
                            Website developed by Cloudit Technologies
                        </p>
                    </div>
                </div>

                <!-- Accreditation Badges - Aligned to the right -->
                <div class="accreditation-badges">
                    <div class="accreditation-grid">
                        <div class="accreditation-badge badge-nmcn" role="button" tabindex="0">
                            <i class="fas fa-check-circle badge-icon"></i>
                            <span class="badge-text">NMCN Accredited</span>
                        </div>
                        <div class="accreditation-badge badge-nbte" role="button" tabindex="0">
                            <i class="fas fa-check-circle badge-icon"></i>
                            <span class="badge-text">NBTE Approved</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?= htmlspecialchars($baseUrl) ?>/assets/js/main.js"></script>
    <?php if ($currentPage === 'home'): ?>
        <script src="<?= htmlspecialchars($baseUrl) ?>/assets/js/carousel.js"></script>
    <?php endif; ?>

    <!-- Footer-specific JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Hover effect for contact links
            document.querySelectorAll('.contact-item a').forEach(link => {
                link.addEventListener('mouseenter', () => {
                    link.style.color = 'rgba(255, 126, 95, 0.9)';
                });
                link.addEventListener('mouseleave', () => {
                    link.style.color = '';
                });
            });

            // Accreditation badge interaction
            document.querySelectorAll('.accreditation-badge').forEach(badge => {
                const badgeType = badge.classList.contains('badge-nmcn') ? 'NMCN' : 'NBTE';

                badge.addEventListener('click', () => {
                    alert(`Learn more about our ${badgeType} accreditation on our accreditation page.`);
                });

                badge.addEventListener('keypress', e => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        alert(`Learn more about our ${badgeType} accreditation on our accreditation page.`);
                    }
                });
            });
        });
    </script>

    <!-- Footer-only Styles -->
    <style>
        .page-footer {
            background: linear-gradient(135deg, #0a0e17 0%, #1a1f2e 100%);
            color: #ffffff;
            font-family: 'Montserrat', 'Open Sans', sans-serif;
            margin-top: 4rem;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer-brand .brand-slogan {
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.6;
            margin: 1.5rem 0 1rem;
            max-width: 320px;
        }

        .footer-logo img {
            max-height: 68px;
            height: 68px;
            width: auto;
        }

        .footer-column h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(255, 126, 95, 0.9);
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover,
        .footer-links a:focus {
            color: rgba(255, 126, 95, 0.9);
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
            color: rgba(255, 255, 255, 0.85);
        }

        .contact-icon {
            color: rgba(255, 126, 95, 0.9);
            margin-right: 0.75rem;
            margin-top: 0.25rem;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .social-links {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: background 0.2s ease;
        }

        .social-links a:hover,
        .social-links a:focus {
            background: rgba(255, 126, 95, 0.2);
        }

        /* Footer Bottom Layout */
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .footer-bottom-inner {
            text-align: center;
            flex: 1;
            min-width: 300px;
        }

        /* Justified alignment for links */
        .footer-bottom-links {
            display: flex;
            justify-content: center;
            gap: 2rem; /* Increased for better spacing and justified feel */
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .footer-bottom-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s ease;
            white-space: nowrap;
        }

        .footer-bottom-links a:hover,
        .footer-bottom-links a:focus {
            color: rgba(255, 126, 95, 0.9);
        }

        .footer-legal-text {
            color: rgba(255, 255, 255, 0.6);
        }

        .copyright-text {
            margin: 0.5rem 0;
            font-size: 0.875rem;
        }

        .developer-credit {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
            font-style: italic;
            margin: 0.5rem 0 0;
        }

        /* Accreditation Badges - Right aligned */
        .accreditation-badges {
            flex-shrink: 0;
        }

        .accreditation-grid {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .accreditation-badge {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            min-width: 180px;
            cursor: pointer;
            white-space: nowrap;
        }

        .accreditation-badge:hover,
        .accreditation-badge:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .badge-nmcn {
            border-color: rgba(107, 78, 155, 0.4);
            background: rgba(107, 78, 155, 0.1);
        }

        .badge-nmcn:hover,
        .badge-nmcn:focus {
            border-color: rgba(107, 78, 155, 0.6);
            background: rgba(107, 78, 155, 0.15);
        }

        .badge-nmcn .badge-icon,
        .badge-nmcn .badge-text {
            color: rgba(107, 78, 155, 0.9);
        }

        .badge-nbte {
            border-color: rgba(127, 178, 133, 0.4);
            background: rgba(127, 178, 133, 0.1);
        }

        .badge-nbte:hover,
        .badge-nbte:focus {
            border-color: rgba(127, 178, 133, 0.6);
            background: rgba(127, 178, 133, 0.15);
        }

        .badge-nbte .badge-icon,
        .badge-nbte .badge-text {
            color: rgba(127, 178, 133, 0.9);
        }

        @media (max-width: 992px) {
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .accreditation-grid {
                justify-content: center;
            }

            .footer-bottom-inner {
                order: 2;
            }

            .accreditation-badges {
                order: 1;
            }
        }

        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }

            .footer-bottom-links {
                flex-direction: column;
                gap: 0.75rem;
            }

            .accreditation-grid {
                flex-direction: column;
                align-items: center;
            }

            .accreditation-badge {
                min-width: 220px;
                justify-content: center;
            }
        }
    </style>
</body>
</html>