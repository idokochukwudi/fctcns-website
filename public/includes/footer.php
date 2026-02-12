<?php
/**
 * Footer Template - Professional Purple Theme with Gold Accents
 * FIXED: Unique class names to prevent conflicts with page content
 * FIXED: Social icons now show on hover (NOT BLANK) - COMPLETELY REWRITTEN
 * UPDATED: Main campus address to P. O. Box 507 Gwagwalada – Abuja, Nigeria
 *
 * @package FCT_CNS
 */

// Fallback values with type safety
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '');
$currentPage = $currentPage ?? 'home';
$currentYear = date('Y');

// Navigation data
$quickLinks = [
    ['url' => '', 'label' => 'Home'],
    ['url' => 'about', 'label' => 'About Us'],
    ['url' => 'programs', 'label' => 'Academic Programs'],
    ['url' => 'admissions', 'label' => 'Admissions'],
    ['url' => 'facilities', 'label' => 'Facilities'],
    ['url' => 'student-life', 'label' => 'Student Life'],
];

$resourceLinks = [
    ['url' => 'portal', 'label' => 'Student Portal', 'icon' => 'sign-in-alt'],
    ['url' => 'library', 'label' => 'Digital Library', 'icon' => 'book'],
    ['url' => 'news', 'label' => 'News & Events', 'icon' => 'newspaper'],
    ['url' => 'alumni', 'label' => 'Alumni Network', 'icon' => 'user-graduate'],
    ['url' => 'research', 'label' => 'Research', 'icon' => 'flask'],
    ['url' => 'careers', 'label' => 'Careers', 'icon' => 'briefcase'],
];

$legalLinks = [
    ['url' => 'privacy', 'label' => 'Privacy Policy'],
    ['url' => 'terms', 'label' => 'Terms of Service'],
    ['url' => 'accessibility', 'label' => 'Accessibility'],
    ['url' => 'sitemap', 'label' => 'Sitemap'],
];

$socialLinks = [
    ['url' => '#', 'label' => 'Facebook', 'icon' => 'facebook-f'],
    ['url' => '#', 'label' => 'Twitter', 'icon' => 'twitter'],
    ['url' => '#', 'label' => 'Instagram', 'icon' => 'instagram'],
    ['url' => '#', 'label' => 'LinkedIn', 'icon' => 'linkedin-in'],
    ['url' => '#', 'label' => 'YouTube', 'icon' => 'youtube'],
];
?>

    </main>

    <!-- Footer -->
    <footer class="footer" role="contentinfo" aria-label="Site footer">
        <!-- Subtle gold top border -->
        <div class="footer-top-border" aria-hidden="true"></div>

        <div class="footer-container">
            <!-- Main Footer Grid -->
            <div class="footer-grid">
                <!-- Brand Column -->
                <div class="footer-column footer-column-brand">
                    <div class="footer-brand">
                        <div class="footer-logo-container">
                            <img src="<?= htmlspecialchars($baseUrl) ?>/assets/images/logo/logo-footer.png"
                                 alt="FCT College of Nursing Sciences"
                                 class="footer-logo"
                                 width="200"
                                 height="67"
                                 loading="lazy">
                            <div class="footer-logo-fallback" style="display: none;">
                                <span class="logo-fallback-acronym">FCT</span>
                                <span class="logo-fallback-full">College of Nursing Sciences</span>
                            </div>
                        </div>
                        
                        <div class="footer-mission">
                            <h2 class="footer-institution-title">FCT College of Nursing Sciences</h2>
                            <p class="footer-mission-text">
                                Excellence in nursing education and healthcare training in Nigeria's Federal Capital Territory.
                            </p>
                        </div>

                        <!-- FIXED: Changed class names to prevent conflict with About page -->
                        <div class="footer-accreditation-badges">
                            <span class="footer-accred-badge">
                                <span class="footer-badge-dot"></span>
                                NMCN Accredited
                            </span>
                            <span class="footer-accred-badge">
                                <span class="footer-badge-dot"></span>
                                NBTE Approved
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Column -->
                <div class="footer-column">
                    <h3 class="footer-heading">Quick Links</h3>
                    <nav aria-label="Quick navigation">
                        <ul class="footer-list">
                            <?php foreach ($quickLinks as $link): ?>
                                <li class="footer-list-item">
                                    <a href="<?= htmlspecialchars($baseUrl) ?>/<?= $link['url'] ?>" 
                                       class="footer-link">
                                        <span class="link-arrow" aria-hidden="true">→</span>
                                        <?= htmlspecialchars($link['label']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>

                <!-- Resources Column -->
                <div class="footer-column">
                    <h3 class="footer-heading">Resources</h3>
                    <nav aria-label="Resources navigation">
                        <ul class="footer-list">
                            <?php foreach ($resourceLinks as $link): ?>
                                <li class="footer-list-item">
                                    <a href="<?= htmlspecialchars($baseUrl) ?>/<?= $link['url'] ?>" 
                                       class="footer-link">
                                        <svg class="footer-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <?php if ($link['icon'] === 'sign-in-alt'): ?>
                                                <path d="M16 7L21 12M21 12L16 17M21 12H9M12 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <?php elseif ($link['icon'] === 'book'): ?>
                                                <path d="M12 6.25278V19.2528M12 6.25278C10.8321 5.47686 9.44649 5 7.95 5C5.81933 5 3.9075 5.86795 2.55005 7.23628L2.55005 18.7637C3.9075 17.3954 5.81933 16.5275 7.95 16.5275C9.44649 16.5275 10.8321 17.0043 12 17.7803M12 6.25278C13.1679 5.47686 14.5535 5 16.05 5C18.1807 5 20.0925 5.86795 21.45 7.23628L21.45 18.7637C20.0925 17.3954 18.1807 16.5275 16.05 16.5275C14.5535 16.5275 13.1679 17.0043 12 17.7803" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <?php elseif ($link['icon'] === 'newspaper'): ?>
                                                <path d="M19 20H5C4.46957 20 3.96086 19.7893 3.58579 19.4142C3.21071 19.0391 3 18.5304 3 18V6C3 5.46957 3.21071 4.96086 3.58579 4.58579C3.96086 4.21071 4.46957 4 5 4H15C15.5304 4 16.0391 4.21071 16.4142 4.58579C16.7893 4.96086 17 5.46957 17 6V18C17 18.5304 17.2107 19.0391 17.5858 19.4142C17.9609 19.7893 18.4696 20 19 20ZM19 20C19.5304 20 20.0391 19.7893 20.4142 19.4142C20.7893 19.0391 21 18.5304 21 18V9C21 8.46957 20.7893 7.96086 20.4142 7.58579C20.0391 7.21071 19.5304 7 19 7H17M7 8H13M7 12H17M7 16H13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <?php elseif ($link['icon'] === 'user-graduate'): ?>
                                                <path d="M12 14L2 9L12 4L22 9L12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M18 12.5V16.5C18 17.2956 17.6839 18.0587 17.1213 18.6213C16.5587 19.1839 15.7956 19.5 15 19.5H9C8.20435 19.5 7.44129 19.1839 6.87868 18.6213C6.31607 18.0587 6 17.2956 6 16.5V12.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M6.5 9.5L2 12V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <?php elseif ($link['icon'] === 'flask'): ?>
                                                <path d="M9 3H15M12 3V9M12 9L18.5 17.5C19.0509 18.3155 19.125 19.3512 18.7 20.2C18.276 21.0456 17.438 21.5975 16.5 21.6H7.5C6.562 21.5975 5.72404 21.0456 5.3 20.2C4.875 19.3512 4.94908 18.3155 5.5 17.5L12 9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <?php elseif ($link['icon'] === 'briefcase'): ?>
                                                <path d="M20 7H4C2.89543 7 2 7.89543 2 9V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V9C22 7.89543 21.1046 7 20 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M16 21V5C16 4.46957 15.7893 3.96086 15.4142 3.58579C15.0391 3.21071 14.5304 3 14 3H10C9.46957 3 8.96086 3.21071 8.58579 3.58579C8.21071 3.96086 8 4.46957 8 5V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <?php endif; ?>
                                        </svg>
                                        <?= htmlspecialchars($link['label']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>

                <!-- Contact Column -->
                <div class="footer-column">
                    <h3 class="footer-heading">Contact</h3>
                    <address class="footer-address">
                        <div class="contact-group">
                            <div class="contact-item">
                                <svg class="contact-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="9" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>
                                    <span class="contact-label">Main Campus:</span>
                                    P. O. Box 507 Gwagwalada – Abuja, Nigeria
                                </span>
                            </div>
                            
                            <div class="contact-item">
                                <svg class="contact-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M22 16.92V19C22.001 19.2762 21.946 19.5499 21.8386 19.8045C21.7311 20.0591 21.5736 20.2891 21.3757 20.4803C21.1778 20.6714 20.9436 20.8198 20.6866 20.9175C20.4296 21.0153 20.1554 21.0602 19.88 21.05C16.7587 20.7644 13.7393 19.8148 11.03 18.27C8.46086 16.8251 6.25985 14.823 4.60001 12.4C2.98086 9.93997 2.01298 7.13199 2.00001 4.23997C1.98943 3.96362 2.03435 3.68842 2.13213 3.43046C2.22991 3.1725 2.37833 2.93732 2.56949 2.73847C2.76065 2.53963 2.99061 2.38116 3.24655 2.2726C3.5025 2.16404 3.77856 2.10769 4.05801 2.10697H7.00001C7.35161 2.10255 7.6924 2.23081 7.95001 2.46397C8.20762 2.69713 8.3613 3.01677 8.38001 3.35897C8.4783 4.45547 8.73021 5.53155 9.13001 6.55297C9.24052 6.83293 9.26322 7.13818 9.19548 7.43078C9.12774 7.72337 8.97245 7.98846 8.75001 8.18997L6.98001 9.80997C8.33346 12.1997 10.3403 14.2007 12.73 15.55L14.35 13.78C14.5515 13.5576 14.8166 13.4023 15.1092 13.3345C15.4018 13.2668 15.7071 13.2895 15.987 13.4C17.0085 13.7998 18.0845 14.0517 19.181 14.15C19.5275 14.1692 19.8505 14.3271 20.0834 14.5908C20.3164 14.8544 20.4398 15.202 20.425 15.558C20.425 15.678 20.425 15.798 20.425 15.93L22 16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>
                                    <span class="contact-label">Phone:</span>
                                    <a href="tel:+2348082775076" class="contact-link">+234 808 277 5076</a>
                                </span>
                            </div>
                            
                            <div class="contact-item">
                                <svg class="contact-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>
                                    <span class="contact-label">Email:</span>
                                    <a href="mailto:info@fctcns.edu.ng" class="contact-link">info@fctcns.edu.ng</a>
                                </span>
                            </div>
                            
                            <div class="contact-item">
                                <svg class="contact-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>
                                    <span class="contact-label">Hours:</span>
                                    Mon-Fri: 8:00 AM - 5:00 PM
                                </span>
                            </div>
                        </div>
                    </address>

                    <div class="footer-social">
                        <span class="social-label">Connect:</span>
                        <div class="social-links">
                            <?php foreach ($socialLinks as $social): ?>
                                <a href="<?= htmlspecialchars($social['url']) ?>" 
                                   class="social-link"
                                   aria-label="<?= htmlspecialchars($social['label']) ?>"
                                   title="<?= htmlspecialchars($social['label']) ?>">
                                    <svg class="social-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <?php if ($social['icon'] === 'facebook-f'): ?>
                                            <path d="M18 2H15C13.6739 2 12.4021 2.52678 11.4645 3.46447C10.5268 4.40215 10 5.67392 10 7V10H7V14H10V22H14V14H17L18 10H14V7C14 6.73478 14.1054 6.48043 14.2929 6.29289C14.4804 6.10536 14.7348 6 15 6H18V2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <?php elseif ($social['icon'] === 'twitter'): ?>
                                            <path d="M22 4.00002C22 4.00002 21.3 6.10002 20 7.40002C21.6 15.4 12.4 21.5 5.00001 18.1C7.00001 18.1 9.20001 17.5 10.5 16.2C7.50001 15.7 5.50001 13.2 5.5 11.2C6.2 11.6 7.1 11.7 8 11.7C5.5 10.2 4.7 6.70002 6.5 4.50002C9 7.30002 12.5 9.20002 17 9.40002C16.5 7.10002 17.8 4.70002 20 4.00002Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <?php elseif ($social['icon'] === 'instagram'): ?>
                                            <rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16 11.37C16.1234 12.2022 15.9813 13.0522 15.5938 13.799C15.2063 14.5458 14.5931 15.1514 13.8416 15.5297C13.0901 15.908 12.2384 16.0396 11.4078 15.906C10.5771 15.7724 9.80977 15.3801 9.21484 14.7852C8.61991 14.1902 8.22764 13.4229 8.09403 12.5922C7.96042 11.7616 8.09207 10.9099 8.47034 10.1584C8.84861 9.40685 9.45419 8.79374 10.201 8.40624C10.9478 8.01874 11.7978 7.87659 12.63 8.00002C13.4789 8.12594 14.2648 8.52152 14.8717 9.12846C15.4785 9.73539 15.8741 10.5211 16 11.37Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M17.5 6.5H17.51" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <?php elseif ($social['icon'] === 'linkedin-in'): ?>
                                            <path d="M16 8C17.5913 8 19.1174 8.63214 20.2426 9.75736C21.3679 10.8826 22 12.4087 22 14V21H18V14C18 13.4696 17.7893 12.9609 17.4142 12.5858C17.0391 12.2107 16.5304 12 16 12C15.4696 12 14.9609 12.2107 14.5858 12.5858C14.2107 12.9609 14 13.4696 14 14V21H10V14C10 12.4087 10.6321 10.8826 11.7574 9.75736C12.8826 8.63214 14.4087 8 16 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M6 9H2V21H6V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M4 6C5.10457 6 6 5.10457 6 4C6 2.89543 5.10457 2 4 2C2.89543 2 2 2.89543 2 4C2 5.10457 2.89543 6 4 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <?php elseif ($social['icon'] === 'youtube'): ?>
                                            <path d="M22.54 6.42C22.4212 5.94541 22.1793 5.51057 21.8387 5.15941C21.498 4.80824 21.0708 4.55318 20.6 4.42C18.88 4 12 4 12 4C12 4 5.12 4 3.4 4.46C2.92925 4.59318 2.50204 4.84824 2.16137 5.19941C1.82069 5.55057 1.57879 5.98541 1.46 6.46C1.14521 8.20556 0.991235 9.97631 1 11.75C0.988765 13.537 1.14275 15.3213 1.46 17.08C1.59087 17.5398 1.83556 17.9581 2.1697 18.2936C2.50385 18.6291 2.91544 18.871 3.37 19C5.12 19.46 12 19.46 12 19.46C12 19.46 18.88 19.46 20.6 19C21.0708 18.8668 21.498 18.6118 21.8387 18.2606C22.1793 17.9094 22.4212 17.4746 22.54 17C22.8524 15.2676 23.0063 13.5104 23 11.75C23.0112 9.96295 22.8572 8.1787 22.54 6.42Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M9.75 15.02L15.5 11.75L9.75 8.47998V15.02Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <?php endif; ?>
                                    </svg>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Institutional Stats - FIXED: Changed to unique class names -->
            <div class="footer-stats">
                <div class="footer-stats-grid">
                    <div class="footer-stat-card">
                        <div class="footer-stat-value">800+</div>
                        <div class="footer-stat-label">Active Students</div>
                        <div class="footer-stat-trend">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M12 5L12 19M12 5L5 12M12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            +12% this year
                        </div>
                    </div>
                    <div class="footer-stat-card">
                        <div class="footer-stat-value">6+</div>
                        <div class="footer-stat-label">Partner Hospitals</div>
                        <div class="footer-stat-trend">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M19 10L14 5M19 10L22 7L17 2L14 5M19 10L12 17M5 19L8 16M3 21L7 17L5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Clinical affiliations
                        </div>
                    </div>
                    <div class="footer-stat-card">
                        <div class="footer-stat-value">2+</div>
                        <div class="footer-stat-label">Academic Programs</div>
                        <div class="footer-stat-trend">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M12 14L2 9L12 4L22 9L12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18 12.5V16.5C18 17.2956 17.6839 18.0587 17.1213 18.6213C16.5587 19.1839 15.7956 19.5 15 19.5H9C8.20435 19.5 7.44129 19.1839 6.87868 18.6213C6.31607 18.0587 6 17.2956 6 16.5V12.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Expanding
                        </div>
                    </div>
                    <div class="footer-stat-card">
                        <div class="footer-stat-value">95%</div>
                        <div class="footer-stat-label">Graduate Success</div>
                        <div class="footer-stat-trend">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Employed within 6 months
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <div class="footer-copyright">
                        <p>&copy; <?= $currentYear ?> FCT College of Nursing Sciences. All rights reserved.</p>
                    </div>
                    
                    <nav class="footer-legal" aria-label="Legal navigation">
                        <?php foreach ($legalLinks as $index => $link): ?>
                            <?php if ($index > 0): ?>
                                <span class="legal-separator" aria-hidden="true">|</span>
                            <?php endif; ?>
                            <a href="<?= htmlspecialchars($baseUrl) ?>/<?= $link['url'] ?>" class="legal-link">
                                <?= htmlspecialchars($link['label']) ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                    
                    <div class="footer-credit">
                        <span class="credit-text">Powered by</span>
                        <a href="tel:+2348082775076" class="credit-link">Cloudit Technologies</a>
                        <span class="credit-badge">v3.0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Top Button -->
        <button class="back-to-top" id="footerBackToTop" aria-label="Back to top of page" title="Back to top">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 4L12 20M12 4L6 10M12 4L18 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="back-to-top-text">Top</span>
        </button>
    </footer>

    <!-- Scripts - FULLY ISOLATED, NO BODY AFFECTIONS -->
    <script src="<?= htmlspecialchars($baseUrl) ?>/assets/js/main.js" defer></script>
    <?php if ($currentPage === 'home'): ?>
        <script src="<?= htmlspecialchars($baseUrl) ?>/assets/js/carousel.js" defer></script>
    <?php endif; ?>

    <!-- FOOTER ONLY JAVASCRIPT - COMPLETELY ISOLATED, NO GLOBAL LEAKS -->
    <script>
        (function() {
            "use strict";
            
            // Wait for DOM to be ready - using local scope only
            function initializeFooterFeatures() {
                // ===== BACK TO TOP - ISOLATED =====
                const backToTopBtn = document.getElementById('footerBackToTop');
                
                if (backToTopBtn) {
                    // Initially hidden
                    backToTopBtn.style.display = 'none';
                    backToTopBtn.classList.remove('visible');
                    
                    // Scroll handler - isolated
                    function toggleBackToTop() {
                        if (!backToTopBtn) return;
                        try {
                            if (window.scrollY > 400) {
                                backToTopBtn.style.display = 'flex';
                                backToTopBtn.classList.add('visible');
                                backToTopBtn.setAttribute('aria-hidden', 'false');
                            } else {
                                backToTopBtn.style.display = 'none';
                                backToTopBtn.classList.remove('visible');
                                backToTopBtn.setAttribute('aria-hidden', 'true');
                            }
                        } catch(e) {
                            // Fail silently - no global impact
                        }
                    }
                    
                    // Scroll to top - isolated
                    function scrollToTop(e) {
                        if (e) e.preventDefault();
                        try {
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        } catch(e) {
                            // Fallback for older browsers
                            window.scrollTo(0, 0);
                        }
                    }
                    
                    // Add event listeners with cleanup capability
                    window.addEventListener('scroll', toggleBackToTop, { passive: true });
                    backToTopBtn.addEventListener('click', scrollToTop);
                    
                    // Initial check
                    toggleBackToTop();
                }
                
                // ===== LOGO FALLBACK - ISOLATED =====
                const footerLogo = document.querySelector('.footer .footer-logo');
                const fallbackContainer = document.querySelector('.footer .footer-logo-fallback');
                
                if (footerLogo && fallbackContainer) {
                    // Simple error handler
                    footerLogo.onerror = function() {
                        try {
                            this.style.display = 'none';
                            if (fallbackContainer) {
                                fallbackContainer.style.display = 'flex';
                            }
                        } catch(e) {
                            // Fail silently
                        }
                    };
                    
                    // Check if already errored
                    if (footerLogo.complete && footerLogo.naturalHeight === 0) {
                        footerLogo.onerror();
                    }
                }
            }
            
            // Initialize when DOM is ready - but DON'T attach to window
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeFooterFeatures);
            } else {
                initializeFooterFeatures();
            }
        })();
    </script>

    <!-- FOOTER STYLES - COMPLETELY FIXED SOCIAL ICONS HOVER -->
    <style>
        /* ==========================================================================
           FOOTER ONLY - NO IMPACT ON BODY OR OTHER SECTIONS
           FIXED: Changed generic class names to footer-specific names
           FIXED: Renamed .accreditation-badge → .footer-accred-badge
           FIXED: Social icons hover - COMPLETELY REWRITTEN, now 100% visible
           ========================================================================== */
        
        /* Color System - Footer Only */
        .footer {
            --footer-bg: #2a2538;
            --footer-bg-light: #322c42;
            --footer-bg-gradient: linear-gradient(145deg, #2a2538 0%, #322c42 100%);
            --gold: #c6a15b;
            --gold-light: #d4b47c;
            --gold-dark: #a88646;
            --gold-soft: rgba(198, 161, 91, 0.12);
            --footer-text-primary: #ffffff;
            --footer-text-secondary: #e8e2f0;
            --footer-text-muted: #c2b8d0;
            --footer-text-dim: #9e92b0;
            --footer-border: rgba(255, 255, 255, 0.08);
            --footer-hover-bg: rgba(198, 161, 91, 0.1);
            --transition: all 0.2s ease;
        }

        /* Footer Container - Isolated */
        .footer {
            background-color: var(--footer-bg);
            background-image: var(--footer-bg-gradient);
            color: var(--footer-text-primary);
            font-family: 'Montserrat', 'Open Sans', sans-serif;
            position: relative;
            margin-top: 3rem;
            border-top: none;
            width: 100%;
            clear: both;
        }

        /* Top Border */
        .footer .footer-top-border {
            height: 3px;
            background: linear-gradient(90deg, var(--gold-dark), var(--gold), var(--gold-dark));
            width: 100%;
            opacity: 0.8;
        }

        /* Container */
        .footer .footer-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 3rem 2rem 1.5rem;
        }

        /* Grid Layout */
        .footer .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 2.5rem;
            margin-bottom: 2.5rem;
        }

        /* Brand Section */
        .footer .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .footer .footer-logo {
            height: 67px;
            width: auto;
            max-width: 200px;
            display: block;
        }

        .footer .footer-logo-fallback {
            display: none;
            align-items: baseline;
            gap: 0.5rem;
        }

        .footer .logo-fallback-acronym {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--footer-text-primary);
            letter-spacing: -1px;
            line-height: 1;
        }

        .footer .logo-fallback-full {
            font-size: 0.95rem;
            color: var(--footer-text-secondary);
            font-weight: 400;
            line-height: 1.2;
        }

        .footer .footer-institution-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--footer-text-primary);
            margin: 0 0 0.5rem 0;
        }

        .footer .footer-mission-text {
            color: var(--footer-text-secondary);
            line-height: 1.6;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Accreditation Badges */
        .footer .footer-accreditation-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .footer .footer-accred-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--gold-soft);
            color: var(--footer-text-secondary);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.4rem 1rem;
            border-radius: 30px;
            border: 1px solid rgba(198, 161, 91, 0.25);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer .footer-badge-dot {
            width: 8px;
            height: 8px;
            background-color: var(--gold);
            border-radius: 50%;
            display: inline-block;
        }

        /* Headings */
        .footer .footer-heading {
            font-size: 1rem;
            font-weight: 700;
            color: var(--footer-text-primary);
            margin: 0 0 1.5rem 0;
            padding-bottom: 0.6rem;
            border-bottom: 2px solid var(--gold);
            display: inline-block;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Lists */
        .footer .footer-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer .footer-list-item {
            margin-bottom: 0.75rem;
        }

        .footer .footer-link {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--footer-text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: var(--transition);
            padding: 0.2rem 0;
            border-bottom: 1px solid transparent;
        }

        .footer .footer-icon,
        .footer .footer-link svg {
            color: var(--gold);
            width: 16px;
            height: 16px;
            stroke: var(--gold);
            stroke-width: 2;
            flex-shrink: 0;
        }

        .footer .link-arrow {
            color: var(--gold);
            font-weight: 600;
        }

        .footer .footer-link:hover {
            color: var(--footer-text-primary);
            border-bottom-color: var(--gold);
            transform: translateX(4px);
        }

        .footer .footer-link:hover svg {
            stroke: var(--gold-light);
        }

        /* Contact Section */
        .footer .footer-address {
            font-style: normal;
            margin-bottom: 1.5rem;
        }

        .footer .contact-group {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .footer .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            color: var(--footer-text-secondary);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .footer .contact-icon {
            color: var(--gold);
            width: 18px;
            height: 18px;
            stroke: var(--gold);
            stroke-width: 2;
            margin-top: 0.2rem;
            flex-shrink: 0;
        }

        .footer .contact-label {
            font-weight: 600;
            color: var(--footer-text-primary);
            margin-right: 0.25rem;
        }

        .footer .contact-link {
            color: var(--footer-text-secondary);
            text-decoration: none;
            border-bottom: 1px solid transparent;
        }

        .footer .contact-link:hover {
            color: var(--footer-text-primary);
            border-bottom-color: var(--gold);
        }

        /* ===== SOCIAL LINKS - COMPLETELY REWRITTEN - 100% VISIBLE ON HOVER ===== */
        .footer .footer-social {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }

        .footer .social-label {
            color: var(--footer-text-primary);
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer .social-links {
            display: flex;
            gap: 0.75rem;
        }

        .footer .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: rgba(198, 161, 91, 0.12);
            border-radius: 8px;
            border: 1px solid rgba(198, 161, 91, 0.3);
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .footer .social-icon {
            width: 16px;
            height: 16px;
            display: block;
        }

        /* Target ALL SVG elements directly */
        .footer .social-icon path,
        .footer .social-icon rect,
        .footer .social-icon circle,
        .footer .social-icon polygon,
        .footer .social-icon polyline {
            stroke: var(--gold);
            stroke-width: 2;
            fill: none;
            transition: stroke 0.2s ease, stroke-width 0.2s ease;
        }

        /* CRITICAL FIX: Hover state with maximum specificity */
        .footer .social-link:hover {
            background-color: var(--gold) !important;
            border-color: var(--gold) !important;
            transform: translateY(-3px);
            box-shadow: 0 5px 12px rgba(198, 161, 91, 0.3);
        }

        /* Force white stroke on ALL hovered SVG elements */
        .footer .social-link:hover .social-icon path,
        .footer .social-link:hover .social-icon rect,
        .footer .social-link:hover .social-icon circle,
        .footer .social-link:hover .social-icon polygon,
        .footer .social-link:hover .social-icon polyline {
            stroke: #ffffff !important;
            stroke-width: 2.2 !important;
            fill: none !important;
        }

        /* Stats Section */
        .footer .footer-stats {
            margin: 2.5rem 0 2rem;
            padding: 1.75rem 0;
            border-top: 1px solid var(--footer-border);
            border-bottom: 1px solid var(--footer-border);
        }

        .footer .footer-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .footer .footer-stat-card {
            text-align: center;
            padding: 0.5rem;
        }

        .footer .footer-stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--footer-text-primary);
            line-height: 1;
            margin-bottom: 0.4rem;
            letter-spacing: -0.02em;
        }

        .footer .footer-stat-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--footer-text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .footer .footer-stat-trend {
            font-size: 0.75rem;
            color: var(--footer-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
        }

        .footer .footer-stat-trend svg {
            width: 12px;
            height: 12px;
            stroke: var(--gold);
            stroke-width: 2;
        }

        /* Footer Bottom */
        .footer .footer-bottom {
            padding-top: 1.5rem;
        }

        .footer .footer-bottom-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer .footer-copyright {
            color: var(--footer-text-dim);
            font-size: 0.85rem;
        }

        .footer .footer-copyright p {
            margin: 0;
        }

        .footer .footer-legal {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .footer .legal-link {
            color: var(--footer-text-dim);
            text-decoration: none;
            font-size: 0.8rem;
            transition: var(--transition);
            padding: 0.2rem 0.4rem;
        }

        .footer .legal-link:hover {
            color: var(--footer-text-primary);
            background-color: var(--gold-soft);
            border-radius: 4px;
        }

        .footer .legal-separator {
            color: var(--gold);
            font-size: 0.8rem;
            opacity: 0.5;
        }

        .footer .footer-credit {
            color: var(--footer-text-dim);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .footer .credit-link {
            color: var(--gold);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border-bottom: 1px solid transparent;
        }

        .footer .credit-link:hover {
            color: var(--gold-light);
            border-bottom-color: var(--gold);
        }

        .footer .credit-badge {
            background-color: var(--gold);
            color: var(--footer-bg);
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-left: 0.25rem;
        }

        /* BACK TO TOP - FOOTER ONLY */
        .footer .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: none;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.2rem;
            background-color: var(--gold);
            color: var(--footer-bg);
            border: none;
            border-radius: 40px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            z-index: 9999;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            opacity: 0;
            visibility: hidden;
        }

        .footer .back-to-top.visible {
            display: flex !important;
            opacity: 1;
            visibility: visible;
        }

        .footer .back-to-top:hover {
            background-color: var(--gold-light);
            transform: translateY(-3px);
        }

        .footer .back-to-top svg {
            width: 18px;
            height: 18px;
            stroke: var(--footer-bg);
            stroke-width: 2.5;
        }

        .footer .back-to-top-text {
            display: inline-block;
            color: var(--footer-bg);
            font-weight: 700;
        }

        /* ===== RESPONSIVE - FOOTER ONLY ===== */
        @media (max-width: 1024px) {
            .footer .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
            .footer .footer-column-brand {
                grid-column: 1 / -1;
            }
            .footer .footer-stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .footer .footer-container {
                padding: 2rem 1.5rem 1rem;
            }
            .footer .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .footer .footer-stats-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .footer .footer-bottom-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            .footer .footer-legal {
                justify-content: center;
            }
            .footer .footer-credit {
                justify-content: center;
            }
            .footer .back-to-top {
                bottom: 1.5rem;
                right: 1.5rem;
                padding: 0.7rem;
                border-radius: 50%;
            }
            .footer .back-to-top-text {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .footer .footer-container {
                padding: 1.5rem 1rem 1rem;
            }
            .footer .footer-social {
                flex-direction: column;
                align-items: flex-start;
            }
            .footer .social-links {
                width: 100%;
                justify-content: space-between;
            }
            .footer .footer-stat-value {
                font-size: 2rem;
            }
        }

        /* Print Styles - Footer Only */
        @media print {
            .footer {
                background: white;
                color: black;
            }
            .footer .footer-top-border,
            .footer .footer-stats,
            .footer .back-to-top,
            .footer .social-links {
                display: none !important;
            }
            .footer .footer-link,
            .footer .contact-link,
            .footer .legal-link {
                color: black !important;
            }
        }
    </style>
</body>
</html>