<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <?php if (isset($verified) && $verified): ?>
                    <!-- Success State -->
                    <div class="card-header bg-success text-white text-center py-4">
                        <i class="fas fa-check-circle fa-4x mb-3"></i>
                        <h2 class="h3 mb-0">Email Verified!</h2>
                    </div>
                    
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <i class="fas fa-envelope-open-text fa-3x text-success mb-3"></i>
                            <h4>Thank you, <?php echo htmlspecialchars($applicant_name ?? ''); ?>!</h4>
                            <p class="text-muted">Your email has been successfully verified.</p>
                        </div>
                        
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            You can now login to complete your application.
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <a href="/applicant/login" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Proceed to Login
                            </a>
                        </div>
                    </div>
                    
                <?php elseif (isset($error)): ?>
                    <!-- Error State -->
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
                    <!-- Already Verified State -->
                    <div class="card-header bg-info text-white text-center py-4">
                        <i class="fas fa-info-circle fa-4x mb-3"></i>
                        <h2 class="h3 mb-0">Already Verified</h2>
                    </div>
                    
                    <div class="card-body p-4 text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <a href="/applicant/login" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Go to Login
                            </a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Default/Processing State (should not normally be seen) -->
                    <div class="card-header bg-warning text-white text-center py-4">
                        <i class="fas fa-clock fa-4x mb-3"></i>
                        <h2 class="h3 mb-0">Processing...</h2>
                    </div>
                    
                    <div class="card-body p-4 text-center">
                        <p>Please wait while we process your verification.</p>
                        <div class="spinner-border text-primary mt-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>