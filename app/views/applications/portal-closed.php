<?php
/**
 * Portal Closed View
 * Fits inside the portal layout's $content slot.
 * 
 * @package FCTCNS
 */

// =========================================================
// FIX: Add require for SecurityHelper and SecurityTrait
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class PortalClosedView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        $portal_message = $portal_message ?? 'The application portal is currently closed. Please check back later for the next admission cycle.';
        ?>
        <style nonce="<?php echo $csp_nonce; ?>">
            .closed-wrap {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 1rem 0 .5rem;
            }

            /* ── Icon badge ─────────────────────────────────────────── */
            .closed-icon-wrap {
                width: 80px; height: 80px;
                background: #FEF3C7;
                border: 1.5px solid rgba(245,158,11,.25);
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                font-size: 2rem;
                color: #D97706;
                margin-bottom: 1.5rem;
                flex-shrink: 0;
            }

            /* ── Heading ─────────────────────────────────────────────── */
            .closed-title {
                font-family: 'Playfair Display', Georgia, serif;
                font-size: clamp(1.4rem, 3vw, 1.9rem);
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: .75rem;
            }

            .closed-message {
                font-size: .95rem;
                color: var(--text-body);
                max-width: 520px;
                line-height: 1.6;
                margin-bottom: 1.75rem;
            }

            /* ── Notice banner ───────────────────────────────────────── */
            .closed-notice {
                display: flex;
                align-items: flex-start;
                gap: .75rem;
                background: #FFFBF0;
                border: 1px solid rgba(200,150,58,.25);
                border-left: 4px solid var(--gold);
                border-radius: 12px;
                padding: 1rem 1.25rem;
                text-align: left;
                max-width: 560px;
                width: 100%;
                margin-bottom: 2rem;
                font-size: .875rem;
                color: #5a4010;
                line-height: 1.6;
            }

            .closed-notice i {
                color: var(--gold);
                font-size: 1rem;
                flex-shrink: 0;
                margin-top: 2px;
            }

            .closed-notice strong { color: #3d2a00; }

            /* ── Divider ─────────────────────────────────────────────── */
            .closed-divider {
                width: 100%;
                max-width: 560px;
                height: 1px;
                background: var(--border);
                margin-bottom: 2rem;
            }

            /* ── Next cycle card ─────────────────────────────────────── */
            .closed-next-card {
                width: 100%;
                max-width: 420px;
                border: 1px solid var(--border);
                border-radius: 14px;
                overflow: hidden;
                margin-bottom: 2rem;
                text-align: left;
            }

            .closed-next-head {
                background: var(--navy);
                padding: .85rem 1.25rem;
                display: flex;
                align-items: center;
                gap: .65rem;
                border-bottom: 2px solid var(--gold);
            }

            .closed-next-head-icon {
                width: 28px; height: 28px;
                background: rgba(200,150,58,0.15);
                border-radius: 6px;
                display: flex; align-items: center; justify-content: center;
                color: var(--gold-light);
                font-size: .75rem;
                flex-shrink: 0;
            }

            .closed-next-head h5 {
                font-family: 'Playfair Display', serif;
                font-size: .95rem;
                font-weight: 600;
                color: #fff;
                margin: 0;
            }

            .closed-next-body {
                background: var(--off-white);
                padding: 1.25rem;
            }

            .closed-next-body p {
                font-size: .875rem;
                color: var(--text-body);
                line-height: 1.6;
                margin-bottom: .6rem;
            }

            .closed-next-body p:last-child { margin-bottom: 0; }

            /* ── Action buttons ──────────────────────────────────────── */
            .closed-actions {
                display: flex;
                gap: .75rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .btn-closed-primary {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                padding: .75rem 1.75rem;
                background: var(--navy);
                color: #fff;
                border: none;
                border-radius: 10px;
                font-family: 'DM Sans', sans-serif;
                font-size: .9rem;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
                transition: all .25s;
                box-shadow: 0 4px 14px rgba(15,27,53,.2);
            }

            .btn-closed-primary:hover {
                background: var(--navy-light);
                transform: translateY(-1px);
                box-shadow: 0 8px 20px rgba(15,27,53,.28);
                color: #fff;
            }

            .btn-closed-outline {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                padding: .75rem 1.75rem;
                background: transparent;
                color: var(--navy);
                border: 1.5px solid var(--border-dark);
                border-radius: 10px;
                font-family: 'DM Sans', sans-serif;
                font-size: .9rem;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
                transition: all .25s;
            }

            .btn-closed-outline:hover {
                background: var(--off-white);
                border-color: var(--navy);
                color: var(--navy);
            }

            /* Toast notification */
            .toast-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                border-radius: 8px;
                color: white;
                font-size: 14px;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                animation: slideInRight 0.3s ease;
            }

            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(100%);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .toast-info { background: #17a2b8; }
            .toast-success { background: #28a745; }
            .toast-error { background: #dc3545; }

            /* External link indicator */
            .external-link::after {
                content: '\f08e';
                font-family: 'Font Awesome 6 Free';
                font-weight: 900;
                font-size: 0.7rem;
                margin-left: 5px;
                opacity: 0.7;
            }

            @media (max-width: 480px) {
                .closed-actions { flex-direction: column; width: 100%; max-width: 320px; }
                .btn-closed-primary,
                .btn-closed-outline { width: 100%; justify-content: center; }
            }
        </style>

        <div class="closed-wrap">

            <!-- CSRF Token for JavaScript (if needed) -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">

            <!-- Icon -->
            <div class="closed-icon-wrap">
                <i class="fas fa-clock"></i>
            </div>

            <!-- Heading -->
            <h2 class="closed-title">Application Portal is Currently Closed</h2>
            <p class="closed-message"><?php echo $this->e($portal_message); ?></p>

            <!-- Notice -->
            <div class="closed-notice">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Important Notice:</strong> No extension of the application deadline will be granted.
                    The College has <strong>NO AGENTS</strong>. Beware of fraudulent websites and individuals —
                    deal only through official channels.
                </div>
            </div>

            <div class="closed-divider"></div>

            <!-- Next admissions cycle -->
            <div class="closed-next-card">
                <div class="closed-next-head">
                    <div class="closed-next-head-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5>Next Admissions Cycle</h5>
                </div>
                <div class="closed-next-body">
                    <p>The next admissions cycle will be for the <strong>2026/2027 academic session</strong>.</p>
                    <p>Check back regularly for updates and announcements regarding the next admissions cycle.</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="closed-actions">
                <a href="/" class="btn-closed-primary" id="homeBtn">
                    <i class="fas fa-home"></i> Return to Homepage
                </a>
                <a href="/contact" class="btn-closed-outline" id="contactBtn">
                    <i class="fas fa-envelope"></i> Contact Admissions
                </a>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- 4. Add CSP nonce to all script tags -->
        <!-- ========================================================= -->
        <script nonce="<?php echo $csp_nonce; ?>">
            // ======================================================
            // Portal Closed JavaScript with Security Enhancements
            // ======================================================
            
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Show toast notification
            function showToast(msg, type = 'info') {
                // Remove existing toasts
                document.querySelectorAll('.toast-notification').forEach(t => t.remove());
                
                // Create toast element
                const toast = document.createElement('div');
                toast.className = `toast-notification toast-${type}`;
                toast.setAttribute('role', 'alert');
                
                const icon = type === 'success' ? 'fa-check-circle' : 
                            type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
                
                // Sanitize message to prevent XSS
                const safeMsg = String(msg).replace(/[<>]/g, '');
                
                toast.innerHTML = `<i class="fas ${icon}"></i> ${safeMsg}`;
                
                document.body.appendChild(toast);
                
                // Auto remove after 3 seconds
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.3s, transform 0.3s';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Track link clicks
            function trackLinkClick(url, label) {
                console.log(`Link clicked: ${label} - ${url}`);
                
                // Optional: Send to analytics
                if (csrfToken) {
                    fetch('/api/track-event', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            event: 'portal_closed_link_click',
                            label: label,
                            url: url,
                            timestamp: new Date().toISOString()
                        })
                    }).catch(err => console.error('Tracking failed:', err));
                }
            }

            // Handle external links securely
            function handleExternalLinks() {
                document.querySelectorAll('a[href^="http"]:not(.internal-link)').forEach(link => {
                    if (link.hostname !== window.location.hostname) {
                        link.setAttribute('target', '_blank');
                        link.setAttribute('rel', 'noopener noreferrer');
                        link.classList.add('external-link');
                        
                        // Add click tracking
                        link.addEventListener('click', function(e) {
                            trackLinkClick(this.href, 'external_link');
                        });
                    }
                });
            }

            // Add smooth scroll to top
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            // Track home button click
            document.getElementById('homeBtn')?.addEventListener('click', function(e) {
                trackLinkClick(this.href, 'return_home');
                // Show friendly message
                showToast('Returning to homepage...', 'info');
            });

            // Track contact button click
            document.getElementById('contactBtn')?.addEventListener('click', function(e) {
                trackLinkClick(this.href, 'contact_admissions');
            });

            // Add keyboard shortcut for home (Alt+H)
            document.addEventListener('keydown', function(e) {
                if (e.altKey && e.key === 'h') {
                    e.preventDefault();
                    window.location.href = '/';
                }
            });

            // Add animation to icon
            document.addEventListener('DOMContentLoaded', function() {
                const icon = document.querySelector('.closed-icon-wrap i');
                if (icon) {
                    // Add subtle pulse animation
                    setInterval(() => {
                        icon.style.transition = 'transform 0.3s ease';
                        icon.style.transform = 'scale(1.1)';
                        setTimeout(() => {
                            icon.style.transform = 'scale(1)';
                        }, 300);
                    }, 3000);
                }

                // Handle external links
                handleExternalLinks();

                // Check if coming from a specific referrer
                if (document.referrer) {
                    try {
                        const referrerUrl = new URL(document.referrer);
                        if (referrerUrl.hostname !== window.location.hostname) {
                            console.log('External referrer:', referrerUrl.hostname);
                            // Optional: Show a welcome back message
                            setTimeout(() => {
                                showToast('Welcome to FCT College of Nursing Sciences', 'info');
                            }, 1000);
                        }
                    } catch (e) {
                        // Invalid URL, ignore
                    }
                }

                // Add smooth scrolling for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({ behavior: 'smooth' });
                        }
                    });
                });

                // Log page view
                console.log('Portal closed page loaded at:', new Date().toISOString());
            });

            // Handle back button cache
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    console.log('Page loaded from cache');
                    // Re-initialize any dynamic elements
                    handleExternalLinks();
                }
            });

            // Prevent right-click on sensitive elements
            document.querySelectorAll('.closed-notice').forEach(el => {
                el.addEventListener('contextmenu', e => e.preventDefault());
            });

            // Add escape key to clear any active states
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Remove any active toasts
                    document.querySelectorAll('.toast-notification').forEach(t => t.remove());
                }
            });

            // Track page visibility
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    console.log('Page became visible at:', new Date().toISOString());
                }
            });

            // Add performance marking
            performance.mark('portal-closed-loaded');
            
            // Log performance
            window.addEventListener('load', function() {
                performance.mark('portal-closed-fully-loaded');
                performance.measure('page-load', 'portal-closed-loaded', 'portal-closed-fully-loaded');
                const measures = performance.getEntriesByType('measure');
                console.log('Page load time:', measures[0]?.duration.toFixed(0) + 'ms');
            });
        </script>
        <?php
    }
}

// =========================================================
// 8. Add the view instantiation at the bottom
// =========================================================
$view = new PortalClosedView();
$view->render(get_defined_vars());
?>