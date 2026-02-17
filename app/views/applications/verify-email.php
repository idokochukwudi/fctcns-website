<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                
                <?php if (isset($verified) && $verified): ?>
                    <!-- Email Verified Success Page -->
                    <div class="card-header bg-success text-white text-center py-4">
                        <i class="fas fa-check-circle fa-4x mb-3"></i>
                        <h2 class="h3 mb-0">Email Verified!</h2>
                    </div>
                    <div class="card-body p-4 text-center">
                        <i class="fas fa-envelope-open-text fa-3x text-success mb-3"></i>
                        <h4>Thank you, <?php echo htmlspecialchars($applicant_name ?? ''); ?>!</h4>
                        <p class="text-muted mb-4">Your email has been successfully verified.</p>
                        <p class="mb-4">You are now logged in. Please complete your JAMB verification.</p>
                        <a href="/apply/step/1" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-right me-2"></i>Continue to JAMB Verification
                        </a>
                    </div>
                    
                <?php elseif (isset($error)): ?>
                    <!-- Verification Failed Page -->
                    <div class="card-header bg-danger text-white text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-4x mb-3"></i>
                        <h2 class="h3 mb-0">Verification Failed</h2>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="alert alert-danger">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                        <p class="mb-4">Possible reasons:</p>
                        <ul class="text-start mb-4">
                            <li>The verification link may have expired</li>
                            <li>The link may have already been used</li>
                            <li>The token might be invalid</li>
                        </ul>
                        <div class="d-grid gap-2">
                            <a href="/apply/register" class="btn btn-primary">
                                <i class="fas fa-redo me-2"></i>Register Again
                            </a>
                            <a href="/applicant/login" class="btn btn-outline-secondary">
                                Try Login Instead
                            </a>
                        </div>
                    </div>
                    
                <?php elseif (isset($message)): ?>
                    <!-- Already Verified Page -->
                    <div class="card-header bg-info text-white text-center py-4">
                        <i class="fas fa-info-circle fa-4x mb-3"></i>
                        <h2 class="h3 mb-0">Already Verified</h2>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="alert alert-info">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                        <a href="/applicant/login" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Go to Login
                        </a>
                    </div>
                    
                <?php elseif (isset($email_sent) && $email_sent): ?>
                    <!-- Email Sent Page (No token) - FIXED VERSION -->
                    <div class="card-header bg-primary text-white text-center py-4">
                        <i class="fas fa-envelope fa-4x mb-3"></i>
                        <h2 class="h3 mb-0">Verify Your Email</h2>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <i class="fas fa-paper-plane fa-3x text-primary mb-3"></i>
                            <h4>Check your inbox</h4>
                            
                            <?php if (!empty($email)): ?>
                                <p class="text-muted">
                                    We've sent a verification link to:<br>
                                    <strong class="text-primary"><?php echo htmlspecialchars($email); ?></strong>
                                </p>
                            <?php else: ?>
                                <p class="text-muted">
                                    We've sent a verification link to your email address.
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-clock me-2"></i>
                            <strong>The link will expire in 24 hours</strong>
                        </div>
                        
                        <div class="mt-4 mb-3 p-3 bg-light rounded">
                            <p class="mb-2"><i class="fas fa-question-circle me-2"></i>Didn't receive the email?</p>
                            <a href="/apply/resend-verification?email=<?php echo urlencode($email ?? ''); ?>" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-redo me-2"></i>Resend Verification Email
                            </a>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-muted small">
                            <p class="mb-2">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Common issues:</strong>
                            </p>
                            <ul class="text-start list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-spam me-2 text-warning"></i>
                                    Check your spam/junk folder
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-clock me-2 text-info"></i>
                                    Wait a few minutes for the email to arrive
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-envelope me-2 text-danger"></i>
                                    Make sure you entered the correct email address
                                </li>
                            </ul>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="mt-3">
                            <a href="/applicant/login" class="text-muted text-decoration-none">
                                <i class="fas fa-sign-in-alt me-2"></i>Already verified? Login here
                            </a>
                        </div>
                        
                        <div class="mt-2">
                            <a href="/apply/register" class="text-muted text-decoration-none small">
                                <i class="fas fa-user-plus me-2"></i>Register with a different email
                            </a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Fallback (should not happen) -->
                    <div class="card-header bg-warning text-white text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-4x mb-3"></i>
                        <h2 class="h3 mb-0">Something went wrong</h2>
                    </div>
                    <div class="card-body p-4 text-center">
                        <p>Please try again or contact support.</p>
                        <div class="d-grid gap-2">
                            <a href="/apply/register" class="btn btn-primary">Register Again</a>
                            <a href="/applicant/login" class="btn btn-outline-secondary">Go to Login</a>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>
            
            <!-- Support Information -->
            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i class="fas fa-question-circle me-2"></i>
                    Need help? Contact support at 
                    <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a> or call 
                    <a href="tel:07039837749">07039837749</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript for auto-focus and interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert:not(.alert-info)');
        alerts.forEach(function(alert) {
            alert.classList.remove('show');
            setTimeout(function() {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 300);
        });
    }, 5000);
    
    // Add click handler for resend link (optional tracking)
    var resendLink = document.querySelector('a[href*="resend-verification"]');
    if (resendLink) {
        resendLink.addEventListener('click', function(e) {
            console.log('Resend verification clicked for email:', 
                new URLSearchParams(this.href.split('?')[1]).get('email'));
        });
    }
});
</script>