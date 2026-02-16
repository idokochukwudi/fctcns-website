<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <?php if (isset($verified) && $verified): ?>
                    <!-- Success State -->
                    <div class="card-header bg-success text-white text-center py-4">
                        <i class="fas fa-check-circle fa-4x mb-3"></i>
                        <h2 class="mb-0">Email Verified!</h2>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <i class="fas fa-envelope-open-text fa-3x text-success mb-3"></i>
                            <h4>Your email has been successfully verified</h4>
                            <p class="text-muted">You can now proceed to login and complete your application.</p>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="/applicant/login" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Proceed to Login
                            </a>
                        </div>
                    </div>
                    
                <?php elseif (isset($error)): ?>
                    <!-- Error State -->
                    <div class="card-header bg-danger text-white text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-4x mb-3"></i>
                        <h2 class="mb-0">Verification Failed</h2>
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
                    
                <?php else: ?>
                    <!-- Pending State -->
                    <div class="card-header bg-info text-white text-center py-4">
                        <i class="fas fa-envelope fa-4x mb-3"></i>
                        <h2 class="mb-0">Verify Your Email</h2>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <i class="fas fa-paper-plane fa-3x text-info mb-3"></i>
                            <h4>Check your inbox</h4>
                            <p class="text-muted">
                                We've sent a verification link to:<br>
                                <strong><?php echo htmlspecialchars($email ?? ''); ?></strong>
                            </p>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-clock me-2"></i>
                            The link will expire in 24 hours
                        </div>
                        
                        <p class="mb-4">
                            Didn't receive the email? 
                            <a href="/apply/resend-verification?email=<?php echo urlencode($email ?? ''); ?>" class="text-primary">
                                Click here to resend
                            </a>
                        </p>
                        
                        <hr class="my-4">
                        
                        <p class="text-muted small">
                            <i class="fas fa-info-circle me-2"></i>
                            Please check your spam folder if you don't see the email in your inbox.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>