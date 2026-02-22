<?php
/**
 * Payment Failed View
 * 
 * @package FCTCNS
 */

// =========================================================
// FIX: Add require for SecurityHelper and SecurityTrait
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class PaymentFailedView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        $error_message = $error_message ?? 'Payment processing failed';
        $payment_reference = $payment_reference ?? 'N/A';
        $application = $application ?? [];
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            
            <!-- ========================================================= -->
            <!-- 2. Add security meta tags in the head -->
            <!-- ========================================================= -->
            <?php echo $this->getSecurityMetaTags(); ?>
            
            <title>Payment Failed - FCT College of Nursing Sciences</title>
            
            <!-- CSRF Token for JavaScript -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">
            
            <!-- ========================================================= -->
            <!-- 3. Add CSP nonce to all style tags -->
            <!-- 7. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            
            <!-- Bootstrap 5 with SRI -->
            <?php 
            $bootstrapCssUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';
            $bootstrapCssSri = SecurityHelper::getSriHash($bootstrapCssUrl);
            ?>
            <link href="<?php echo $bootstrapCssUrl; ?>" 
                  rel="stylesheet"
                  <?php if ($bootstrapCssSri): ?>integrity="<?php echo $bootstrapCssSri; ?>"<?php endif; ?>
                  crossorigin="anonymous">
            
            <!-- Font Awesome with CORRECT SRI hash -->
            <?php 
            $faUrl = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
            $faSri = SecurityHelper::getSriHash($faUrl);
            ?>
            <link rel="stylesheet" 
                  href="<?php echo $faUrl; ?>"
                  <?php if ($faSri): ?>integrity="<?php echo $faSri; ?>"<?php endif; ?>
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
                body {
                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }
                
                .failed-card {
                    border: none;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                    animation: shake 0.5s ease-in-out;
                }
                
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                    20%, 40%, 60%, 80% { transform: translateX(5px); }
                }
                
                .failed-header {
                    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                    color: white;
                    padding: 40px;
                    text-align: center;
                }
                
                .failed-icon {
                    width: 100px;
                    height: 100px;
                    background: rgba(255,255,255,0.2);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 20px;
                    font-size: 50px;
                    animation: bounce 1s infinite;
                }
                
                @keyframes bounce {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-10px); }
                }
                
                .failed-body {
                    padding: 40px;
                    background: white;
                }
                
                .error-details {
                    background: #fff5f5;
                    border-radius: 15px;
                    padding: 20px;
                    margin: 20px 0;
                    border-left: 4px solid #dc3545;
                }
                
                .detail-item {
                    display: flex;
                    justify-content: space-between;
                    padding: 10px 0;
                    border-bottom: 1px solid #fde2e2;
                }
                
                .detail-item:last-child {
                    border-bottom: none;
                }
                
                .detail-label {
                    color: #721c24;
                    font-weight: 600;
                }
                
                .detail-value {
                    color: #721c24;
                    font-weight: 600;
                    word-break: break-word;
                    text-align: right;
                    max-width: 60%;
                }
                
                .action-buttons {
                    display: flex;
                    gap: 15px;
                    justify-content: center;
                    margin-top: 30px;
                    flex-wrap: wrap;
                }
                
                .btn-action {
                    padding: 12px 30px;
                    border-radius: 50px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    border: none;
                    cursor: pointer;
                }
                
                .btn-action:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                }
                
                .btn-primary {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                }
                
                .btn-outline-primary {
                    border: 2px solid #667eea;
                    color: #667eea;
                    background: transparent;
                }
                
                .btn-outline-primary:hover {
                    background: #667eea;
                    color: white;
                }
                
                .support-card {
                    background: #f8f9fa;
                    border-radius: 15px;
                    padding: 20px;
                    margin-top: 30px;
                }
                
                .help-option {
                    transition: all 0.3s ease;
                    cursor: pointer;
                    text-decoration: none;
                    color: inherit;
                    display: block;
                }
                
                .help-option:hover {
                    background: white;
                    transform: translateX(5px);
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
                    z-index: 10000;
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
                
                .toast-error { background: #dc3545; }
                .toast-info { background: #17a2b8; }
                
                /* Modal spinner */
                .spinner-border {
                    width: 3rem;
                    height: 3rem;
                }
                
                /* Responsive */
                @media (max-width: 768px) {
                    .failed-body { padding: 20px; }
                    .failed-header { padding: 30px; }
                    .action-buttons { flex-direction: column; }
                    .btn-action { width: 100%; }
                    .detail-item { flex-direction: column; gap: 5px; }
                    .detail-value { text-align: left; max-width: 100%; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card failed-card">
                            <div class="failed-header">
                                <div class="failed-icon">
                                    <i class="fas fa-times"></i>
                                </div>
                                <h2 class="mb-2">Payment Failed</h2>
                                <p class="mb-0 opacity-75">We couldn't process your payment</p>
                            </div>
                            
                            <div class="failed-body">
                                <div class="text-center mb-4">
                                    <h4>Oops! Something went wrong</h4>
                                    <p class="text-muted">Don't worry, this happens sometimes. Here's what you can do:</p>
                                </div>
                                
                                <div class="error-details">
                                    <h5 class="mb-3 text-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Error Details
                                    </h5>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">Error Message:</span>
                                        <span class="detail-value"><?php echo $this->e($error_message); ?></span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">Reference:</span>
                                        <span class="detail-value"><?php echo $this->e($payment_reference); ?></span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">Date/Time:</span>
                                        <span class="detail-value"><?php echo $this->e(date('jS F Y, h:i A')); ?></span>
                                    </div>
                                    
                                    <?php if (!empty($application['application_number'])): ?>
                                    <div class="detail-item">
                                        <span class="detail-label">Application No:</span>
                                        <span class="detail-value"><?php echo $this->e($application['application_number']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="action-buttons">
                                    <button onclick="retryPayment()" class="btn btn-primary btn-action" id="retryBtn">
                                        <i class="fas fa-redo-alt me-2"></i> Retry Payment
                                    </button>
                                    <a href="/apply/step/3" class="btn btn-outline-primary btn-action">
                                        <i class="fas fa-arrow-left me-2"></i> Go Back
                                    </a>
                                </div>
                                
                                <div class="support-card">
                                    <h5 class="mb-3"><i class="fas fa-headset me-2"></i>Need Help?</h5>
                                    <p class="text-muted small mb-3">Choose how you'd like to get support:</p>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <a href="tel:07039837749" class="help-option p-3 border rounded text-center" onclick="trackSupportClick('phone')">
                                                <i class="fas fa-phone-alt text-primary fa-2x mb-2"></i>
                                                <h6 class="mb-0">Call Us</h6>
                                                <small class="text-muted">07039837749</small>
                                            </a>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <a href="https://wa.me/2347039837749" class="help-option p-3 border rounded text-center" target="_blank" rel="noopener noreferrer" onclick="trackSupportClick('whatsapp')">
                                                <i class="fab fa-whatsapp text-success fa-2x mb-2"></i>
                                                <h6 class="mb-0">WhatsApp</h6>
                                                <small class="text-muted">Chat with us</small>
                                            </a>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <a href="mailto:support@fctcns.edu.ng" class="help-option p-3 border rounded text-center" onclick="trackSupportClick('email')">
                                                <i class="fas fa-envelope text-info fa-2x mb-2"></i>
                                                <h6 class="mb-0">Email</h6>
                                                <small class="text-muted">support@fctcns.edu.ng</small>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <hr class="my-4">
                                    
                                    <div class="text-center">
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-clock me-1"></i>
                                            Response time: Within 24 hours
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <a href="/" class="text-decoration-none">
                                        <i class="fas fa-home me-1"></i> Return to Homepage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Retry Modal -->
            <div class="modal fade" id="retryModal" tabindex="-1" aria-labelledby="retryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="retryModalLabel">
                                <i class="fas fa-redo-alt me-2"></i> Retry Payment
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <div class="spinner-border text-primary mb-3" role="status" id="retrySpinner" style="display: none;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <i class="fas fa-exclamation-circle text-warning fa-4x mb-3" id="retryIcon"></i>
                            <p id="retryMessage">Are you sure you want to retry the payment?</p>
                            <p class="text-muted small" id="retrySubMessage">This will redirect you to the payment page again.</p>
                            
                            <!-- Hidden CSRF token for retry -->
                            <input type="hidden" id="modalCsrfToken" value="<?php echo $this->e($csrf_token); ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="confirmRetry()" id="confirmRetryBtn">
                                Yes, Retry Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all script tags -->
            <!-- ========================================================= -->
            
            <!-- Bootstrap JS with SRI -->
            <?php 
            $bootstrapJsUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
            $bootstrapJsSri = SecurityHelper::getSriHash($bootstrapJsUrl);
            ?>
            <script src="<?php echo $bootstrapJsUrl; ?>" 
                    <?php if ($bootstrapJsSri): ?>integrity="<?php echo $bootstrapJsSri; ?>"<?php endif; ?>
                    crossorigin="anonymous"
                    nonce="<?php echo $csp_nonce; ?>"></script>
            
            <script nonce="<?php echo $csp_nonce; ?>">
                // ======================================================
                // Payment Failed JavaScript with Security Enhancements
                // ======================================================
                
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                let retryModal;
                
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize Bootstrap modal
                    if (typeof bootstrap !== 'undefined') {
                        retryModal = new bootstrap.Modal(document.getElementById('retryModal'));
                    }
                    
                    // Track page view
                    trackPageView();
                    
                    // Add shake animation to error icon
                    const icon = document.querySelector('.failed-icon');
                    if (icon) {
                        icon.style.animation = 'bounce 1s infinite';
                    }
                });

                // Show toast notification
                function showToast(msg, type = 'error') {
                    // Remove existing toasts
                    document.querySelectorAll('.toast-notification').forEach(t => t.remove());
                    
                    // Create toast element
                    const toast = document.createElement('div');
                    toast.className = `toast-notification toast-${type}`;
                    toast.setAttribute('role', 'alert');
                    
                    const icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
                    
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

                // Track support clicks
                function trackSupportClick(type) {
                    console.log(`Support clicked: ${type}`);
                    // You can add analytics tracking here
                    
                    // Show feedback toast
                    showToast(`Connecting to ${type} support...`, 'info');
                }

                // Track page view
                function trackPageView() {
                    const pageData = {
                        page: 'payment_failed',
                        timestamp: new Date().toISOString(),
                        referrer: document.referrer,
                        error: '<?php echo $this->e(addslashes($error_message)); ?>',
                        reference: '<?php echo $this->e($payment_reference); ?>'
                    };
                    
                    // You can send this to your analytics endpoint
                    console.log('Page view tracked:', pageData);
                    
                    // Optional: Send to server
                    fetch('/api/track-event', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            event: 'payment_failed_view',
                            data: pageData
                        })
                    }).catch(err => console.error('Tracking failed:', err));
                }

                // Retry payment function
                function retryPayment() {
                    if (retryModal) {
                        retryModal.show();
                    } else {
                        // Fallback if modal not initialized
                        if (confirm('Are you sure you want to retry the payment?')) {
                            window.location.href = '/apply/step/3';
                        }
                    }
                }

                // Confirm retry with CSRF validation
                function confirmRetry() {
                    const spinner = document.getElementById('retrySpinner');
                    const icon = document.getElementById('retryIcon');
                    const message = document.getElementById('retryMessage');
                    const subMessage = document.getElementById('retrySubMessage');
                    const btn = document.getElementById('confirmRetryBtn');
                    const modalCsrf = document.getElementById('modalCsrfToken').value;
                    
                    // Verify CSRF token matches
                    if (modalCsrf !== csrfToken) {
                        showToast('Security token mismatch. Please refresh the page.', 'error');
                        return;
                    }
                    
                    // Show loading state
                    spinner.style.display = 'inline-block';
                    icon.style.display = 'none';
                    message.textContent = 'Processing...';
                    if (subMessage) subMessage.textContent = 'Please wait while we redirect you.';
                    btn.disabled = true;
                    
                    // Track retry attempt
                    fetch('/api/track-event', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            event: 'payment_retry_attempt',
                            reference: '<?php echo $this->e($payment_reference); ?>',
                            timestamp: new Date().toISOString()
                        })
                    }).catch(err => console.error('Tracking failed:', err));
                    
                    // Redirect to payment page
                    setTimeout(function() {
                        window.location.href = '/apply/step/3';
                    }, 1500);
                }

                // Handle back button
                window.addEventListener('pageshow', function(event) {
                    if (event.persisted) {
                        // Page was loaded from cache - reset any stuck states
                        const spinner = document.getElementById('retrySpinner');
                        const icon = document.getElementById('retryIcon');
                        const message = document.getElementById('retryMessage');
                        const btn = document.getElementById('confirmRetryBtn');
                        
                        if (spinner) spinner.style.display = 'none';
                        if (icon) icon.style.display = 'inline-block';
                        if (message) message.textContent = 'Are you sure you want to retry the payment?';
                        if (btn) btn.disabled = false;
                    }
                });

                // Prevent double-click on retry button
                let isRetrying = false;
                document.getElementById('retryBtn')?.addEventListener('click', function(e) {
                    if (isRetrying) {
                        e.preventDefault();
                        showToast('Already processing...', 'info');
                        return;
                    }
                    isRetrying = true;
                    setTimeout(() => { isRetrying = false; }, 3000);
                });

                // Add keyboard shortcut for retry (Ctrl/Cmd + R)
                document.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                        e.preventDefault();
                        retryPayment();
                    }
                });

                // Log error to console for debugging (safe version)
                console.log('Payment failed for reference: <?php echo $this->e($payment_reference); ?>');
            </script>
        </body>
        </html>
        <?php
    }
}

// =========================================================
// 8. Add the view instantiation at the bottom
// =========================================================
$view = new PaymentFailedView();
$view->render(get_defined_vars());
?>