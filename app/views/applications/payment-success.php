<?php
/**
 * Payment Success View
 * 
 * @package FCTCNS
 */

// =========================================================
// FIX: Add require for SecurityHelper and SecurityTrait
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class PaymentSuccessView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        $applicant_name = $applicant_name ?? 'Applicant';
        $applicant_email = $applicant_email ?? '';
        $application = $application ?? [];
        $payment = $payment ?? [];
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
            
            <title>Payment Successful - FCT College of Nursing Sciences</title>
            
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
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }
                
                .success-card {
                    border: none;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                    animation: slideIn 0.5s ease-out;
                }
                
                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateY(50px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .success-header {
                    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                    color: white;
                    padding: 40px;
                    text-align: center;
                }
                
                .success-icon {
                    width: 100px;
                    height: 100px;
                    background: rgba(255,255,255,0.2);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 20px;
                    font-size: 50px;
                    animation: pulse 2s infinite;
                }
                
                @keyframes pulse {
                    0% {
                        transform: scale(1);
                        box-shadow: 0 0 0 0 rgba(255,255,255,0.7);
                    }
                    70% {
                        transform: scale(1.1);
                        box-shadow: 0 0 0 10px rgba(255,255,255,0);
                    }
                    100% {
                        transform: scale(1);
                        box-shadow: 0 0 0 0 rgba(255,255,255,0);
                    }
                }
                
                .success-body {
                    padding: 40px;
                    background: white;
                }
                
                .payment-details {
                    background: #f8f9fa;
                    border-radius: 15px;
                    padding: 20px;
                    margin: 20px 0;
                }
                
                .detail-item {
                    display: flex;
                    justify-content: space-between;
                    padding: 10px 0;
                    border-bottom: 1px solid #dee2e6;
                }
                
                .detail-item:last-child {
                    border-bottom: none;
                }
                
                .detail-label {
                    color: #6c757d;
                    font-weight: 600;
                }
                
                .detail-value {
                    color: #212529;
                    font-weight: 600;
                }
                
                .rrr-code {
                    background: #e9ecef;
                    padding: 15px;
                    border-radius: 10px;
                    font-family: 'Courier New', monospace;
                    font-size: 1.2rem;
                    text-align: center;
                    letter-spacing: 2px;
                    border: 2px dashed #28a745;
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
                }
                
                .btn-action:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                }
                
                .btn-primary {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    color: white;
                }
                
                .btn-success {
                    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                    border: none;
                    color: white;
                }
                
                .btn-outline-secondary {
                    border: 2px solid #6c757d;
                    color: #6c757d;
                    background: transparent;
                }
                
                .btn-outline-secondary:hover {
                    background: #6c757d;
                    color: white;
                }
                
                .btn-link {
                    color: #6c757d;
                    text-decoration: none;
                    padding: 8px 16px;
                    transition: all 0.2s;
                }
                
                .btn-link:hover {
                    color: #28a745;
                    transform: translateY(-2px);
                }
                
                .confetti {
                    position: absolute;
                    width: 10px;
                    height: 10px;
                    background: #f0f0f0;
                    opacity: 0;
                    pointer-events: none;
                    z-index: 9999;
                }
                
                @keyframes confetti {
                    0% {
                        transform: translateY(-100vh) rotate(0deg);
                        opacity: 1;
                    }
                    100% {
                        transform: translateY(100vh) rotate(720deg);
                        opacity: 0;
                    }
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
                
                .toast-success { background: #28a745; }
                .toast-error { background: #dc3545; }
                .toast-info { background: #17a2b8; }
                
                /* Responsive */
                @media (max-width: 768px) {
                    .success-body { padding: 20px; }
                    .success-header { padding: 30px; }
                    .action-buttons { flex-direction: column; }
                    .btn-action { width: 100%; }
                    .btn-group { display: flex; flex-direction: column; gap: 10px; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card success-card">
                            <div class="success-header">
                                <div class="success-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <h2 class="mb-2">Payment Successful!</h2>
                                <p class="mb-0 opacity-75">Your application fee has been received</p>
                            </div>
                            
                            <div class="success-body">
                                <div class="text-center mb-4">
                                    <h4>Thank You, <?php echo $this->e($applicant_name); ?>!</h4>
                                    <p class="text-muted">Your payment has been processed successfully</p>
                                </div>
                                
                                <div class="payment-details">
                                    <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Payment Details</h5>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">Application Number:</span>
                                        <span class="detail-value"><?php echo $this->e($application['application_number'] ?? 'N/A'); ?></span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">RRR:</span>
                                        <span class="detail-value text-primary"><?php echo $this->e($payment['rrr'] ?? 'N/A'); ?></span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">Amount Paid:</span>
                                        <span class="detail-value text-success">₦<?php echo number_format($payment['amount'] ?? 0, 2); ?></span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">Payment Date:</span>
                                        <span class="detail-value"><?php echo $this->e(date('jS F Y, h:i A', strtotime($payment['payment_date'] ?? date('Y-m-d H:i:s')))); ?></span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">Transaction ID:</span>
                                        <span class="detail-value"><?php echo $this->e($payment['transaction_id'] ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="rrr-code mb-4">
                                    <small class="text-muted d-block mb-2">Your RRR Code</small>
                                    <strong><?php echo $this->e($payment['rrr'] ?? 'N/A'); ?></strong>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Your examination slip is now available. Click the button below to view and print it.
                                </div>
                                
                                <div class="action-buttons">
                                    <a href="/apply/step/4" class="btn btn-primary btn-action" id="viewSlipBtn">
                                        <i class="fas fa-ticket-alt me-2"></i> View Exam Slip
                                    </a>
                                    <a href="/apply/download-exam-slip?csrf=<?php echo urlencode($csrf_token); ?>&t=<?php echo time(); ?>" class="btn btn-success btn-action" id="downloadBtn">
                                        <i class="fas fa-download me-2"></i> Download Slip
                                    </a>
                                    <button onclick="printExamSlip()" class="btn btn-outline-secondary btn-action">
                                        <i class="fas fa-print me-2"></i> Print
                                    </button>
                                </div>
                                
                                <hr class="my-4">
                                
                                <div class="text-center">
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-envelope me-1"></i>
                                        A confirmation email has been sent to <?php echo $this->e($applicant_email); ?>
                                    </p>
                                    <div class="btn-group" role="group">
                                        <a href="/applicant/dashboard" class="btn btn-link">
                                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                        </a>
                                        <a href="/apply/step/4" class="btn btn-link">
                                            <i class="fas fa-eye me-1"></i> View Slip
                                        </a>
                                        <a href="/support" class="btn btn-link">
                                            <i class="fas fa-question-circle me-1"></i> Need Help?
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all script tags -->
            <!-- ========================================================= -->
            <script nonce="<?php echo $csp_nonce; ?>">
                // ======================================================
                // Payment Success JavaScript with Security Enhancements
                // ======================================================
                
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Create confetti effect
                function createConfetti() {
                    for (let i = 0; i < 50; i++) {
                        setTimeout(() => {
                            const confetti = document.createElement('div');
                            confetti.className = 'confetti';
                            confetti.style.left = Math.random() * 100 + '%';
                            confetti.style.animation = `confetti ${Math.random() * 3 + 2}s linear forwards`;
                            confetti.style.background = `hsl(${Math.random() * 360}, 70%, 50%)`;
                            document.body.appendChild(confetti);
                            
                            // Remove after animation
                            setTimeout(() => confetti.remove(), 5000);
                        }, i * 50);
                    }
                }

                // Show toast notification
                function showToast(msg, type = 'success') {
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

                // Print exam slip with security
                function printExamSlip() {
                    // Verify with CSRF token for audit trail
                    fetch('/api/verify-print-access', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ 
                            action: 'print_exam_slip',
                            csrf_token: csrfToken 
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const printUrl = '/apply/print-exam-slip?csrf=' + encodeURIComponent(csrfToken);
                            const pw = window.open(printUrl, '_blank');
                            if (pw) {
                                pw.onload = () => setTimeout(() => { pw.focus(); pw.print(); }, 800);
                                showToast('Print window opened successfully', 'success');
                            } else {
                                showToast('Pop-up blocked. Please enable pop-ups and try again.', 'error');
                            }
                        } else {
                            showToast('Unable to verify print permissions', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Print verification error:', error);
                        showToast('Error preparing print. Please try again.', 'error');
                    });
                }

                // Download button handler
                document.getElementById('downloadBtn')?.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const downloadUrl = this.href;
                    
                    fetch(downloadUrl, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.href = downloadUrl;
                            showToast('Download started', 'success');
                        } else {
                            showToast('Download failed. Please try again.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Download error:', error);
                        showToast('Download failed. Please try again.', 'error');
                    });
                });

                // View slip button handler
                document.getElementById('viewSlipBtn')?.addEventListener('click', function(e) {
                    showToast('Loading your examination slip...', 'info');
                });

                // Track page view for analytics (optional)
                function trackPaymentSuccess() {
                    // You can add analytics tracking here if needed
                    console.log('Payment success page viewed', {
                        application: '<?php echo $this->e($application['application_number'] ?? 'N/A'); ?>',
                        amount: '<?php echo $payment['amount'] ?? 0; ?>',
                        timestamp: new Date().toISOString()
                    });
                }

                // Initialize on page load
                document.addEventListener('DOMContentLoaded', function() {
                    // Create confetti effect
                    createConfetti();
                    
                    // Track page view
                    trackPaymentSuccess();
                    
                    // Auto-refresh CSRF token if needed (optional)
                    setInterval(function() {
                        fetch('/api/refresh-csrf', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.token) {
                                // Update meta tag with new token
                                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                                console.log('CSRF token refreshed');
                            }
                        })
                        .catch(error => console.error('Failed to refresh CSRF token:', error));
                    }, 15 * 60 * 1000); // Refresh every 15 minutes
                });

                // Handle back button cache
                window.addEventListener('pageshow', function(event) {
                    if (event.persisted) {
                        // Page was loaded from cache - recreate confetti
                        createConfetti();
                    }
                });

                // Prevent right-click on sensitive elements
                document.querySelectorAll('.rrr-code, .detail-value').forEach(el => {
                    el.addEventListener('contextmenu', e => e.preventDefault());
                });

                // Add keyboard shortcut for print (Ctrl/Cmd + P)
                document.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                        e.preventDefault();
                        printExamSlip();
                    }
                });
            </script>
            
            <!-- Bootstrap JS with SRI -->
            <?php 
            $bootstrapJsUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
            $bootstrapJsSri = SecurityHelper::getSriHash($bootstrapJsUrl);
            ?>
            <script src="<?php echo $bootstrapJsUrl; ?>" 
                    <?php if ($bootstrapJsSri): ?>integrity="<?php echo $bootstrapJsSri; ?>"<?php endif; ?>
                    crossorigin="anonymous"
                    nonce="<?php echo $csp_nonce; ?>"></script>
        </body>
        </html>
        <?php
    }
}

// =========================================================
// 8. Add the view instantiation at the bottom
// =========================================================
$view = new PaymentSuccessView();
$view->render(get_defined_vars());
?>