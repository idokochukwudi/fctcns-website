<div class="text-center mb-4">
    <h2>Step 3: Payment</h2>
    <p class="text-muted">Complete your payment to generate your examination slip</p>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="payment-details">
            <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
            <h3>Application Fee</h3>
            <div class="payment-amount">
                <?php echo htmlspecialchars($formatted_fee); ?>
            </div>
            <p class="text-muted">This fee is non-refundable</p>
            
            <!-- Payment alerts container -->
            <div id="payment-alerts"></div>
            
            <!-- Payment instructions container (initially hidden) -->
            <div id="payment-instructions" style="display: none;"></div>
            
            <?php if (!empty($pending_payment)): ?>
                <div class="alert alert-info">
                    <h5><i class="fas fa-clock"></i> Pending Payment</h5>
                    <p>You have a pending payment. Please complete it or verify if already paid.</p>
                    <div class="payment-rrr">
                        <strong>RRR:</strong> <?php echo htmlspecialchars($pending_payment['rrr']); ?>
                    </div>
                    <div class="mt-3">
                        <a href="https://remitademo.net/remita/ecomm/frame/handleCCD.action?rrr=<?php echo urlencode($pending_payment['rrr']); ?>" 
                           target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> Complete Payment
                        </a>
                        <button type="button" class="btn btn-success" id="verify-payment-btn" data-rrr="<?php echo htmlspecialchars($pending_payment['rrr']); ?>" onclick="verifyPayment('<?php echo htmlspecialchars($pending_payment['rrr']); ?>')">
                            <i class="fas fa-check-circle"></i> I've Paid, Verify
                        </button>
                    </div>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/apply/initiate-payment" class="mt-4">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="alert alert-warning text-start">
                    <h6><i class="fas fa-info-circle"></i> Payment Instructions:</h6>
                    <ul class="mb-0">
                        <li>Click "Pay Now" to generate a Remita RRR (Retrieval Reference Number)</li>
                        <li>You'll be redirected to Remita's secure payment page</li>
                        <li>Pay online using your card or print the invoice and pay at any bank</li>
                        <li>After payment, return here and click "Verify Payment"</li>
                        <li>Your exam slip will be available immediately after verification</li>
                    </ul>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg" id="pay-now-btn">
                    <i class="fas fa-play"></i> Pay Now
                </button>
            </form>
        </div>
        
        <div class="mt-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-question-circle"></i> Payment Support</h5>
                </div>
                <div class="card-body">
                    <p>If you encounter any issues with payment, please contact support:</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone text-primary"></i> Phone: <?php echo htmlspecialchars($settings['key_value']['support_phone_1'] ?? '07039837749'); ?></li>
                        <li><i class="fab fa-whatsapp text-success"></i> WhatsApp: <?php echo htmlspecialchars($settings['key_value']['support_whatsapp'] ?? '08082775076'); ?></li>
                        <li><i class="fas fa-envelope text-danger"></i> Email: <?php echo htmlspecialchars($settings['key_value']['support_email'] ?? 'support.consap@fcthhss.abj.gov.ng'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="/apply/step/2" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Form
            </a>
            <a href="/applicant/logout" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to logout?');">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>

<script>
function verifyPayment(rrr) {
    if (!confirm('Have you completed the payment? Click OK to verify.')) {
        return;
    }
    
    // Get the button that was clicked
    const btn = event ? event.target : document.querySelector('#verify-payment-btn');
    if (!btn) return;
    
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
    btn.disabled = true;
    
    // Show message in payment alerts
    const alertsDiv = document.getElementById('payment-alerts');
    if (alertsDiv) {
        alertsDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Verifying payment. Please wait...</div>';
    }
    
    // Make AJAX request to check status
    fetch('/payment/check-status?rrr=' + encodeURIComponent(rrr))
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Payment verified successfully
                if (alertsDiv) {
                    alertsDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Payment verified! Redirecting...</div>';
                }
                window.location.href = '/apply/verify-payment?rrr=' + encodeURIComponent(rrr);
            } else if (data.status === 'pending') {
                // Payment still pending
                if (alertsDiv) {
                    alertsDiv.innerHTML = '<div class="alert alert-warning"><i class="fas fa-clock"></i> Payment is still processing. Please try again in a few minutes.</div>';
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
            } else {
                // Payment failed or not found
                if (alertsDiv) {
                    alertsDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Payment not verified. Please ensure you have completed the payment or contact support.</div>';
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (alertsDiv) {
                alertsDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> An error occurred while verifying payment. Please try again.</div>';
            }
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
}

// Initialize payment instructions if needed
document.addEventListener('DOMContentLoaded', function() {
    const payNowBtn = document.getElementById('pay-now-btn');
    const instructionsDiv = document.getElementById('payment-instructions');
    
    if (payNowBtn && instructionsDiv) {
        payNowBtn.addEventListener('click', function(e) {
            // Optional: Show loading state when form is submitted
            const form = this.closest('form');
            if (form) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating RRR...';
                this.disabled = true;
            }
        });
    }
});
</script>