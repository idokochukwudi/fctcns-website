<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-success text-white text-center py-4">
                    <i class="fas fa-check-circle fa-4x mb-3"></i>
                    <h2 class="mb-0">JAMB Verification Successful!</h2>
                </div>
                
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-check fa-3x text-success mb-3"></i>
                        <h4>Welcome, <?php echo htmlspecialchars($applicant['first_name'] . ' ' . $applicant['last_name']); ?>!</h4>
                        <p class="lead">Your JAMB number has been successfully verified.</p>
                    </div>
                    
                    <!-- IMPORTANT: Display password ONCE -->
                    <div class="alert alert-warning">
                        <h5 class="alert-heading"><i class="fas fa-key me-2"></i>Your Login Password</h5>
                        <p class="mb-2">Please save this password. You'll need it to log in later:</p>
                        <div class="bg-light p-3 text-center rounded">
                            <strong style="font-size: 1.5rem; font-family: monospace;"><?php echo $password; ?></strong>
                        </div>
                        <p class="mt-2 mb-0 small text-muted">
                            <i class="fas fa-info-circle"></i> This password will also be sent to your email after you provide it.
                        </p>
                    </div>
                    
                    <div class="alert alert-info">
                        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>What happens next?</h5>
                        <p class="mb-0">You will now proceed to complete your application form. Please have the following ready:</p>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3 bg-light rounded-circle p-3">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Personal Information</h6>
                                    <small class="text-muted">Date of birth, address, etc.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3 bg-light rounded-circle p-3">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Contact Details</h6>
                                    <small class="text-muted">Email and phone number</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3 bg-light rounded-circle p-3">
                                    <i class="fas fa-graduation-cap text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">O'Level Results</h6>
                                    <small class="text-muted">WAEC/NECO results</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3 bg-light rounded-circle p-3">
                                    <i class="fas fa-camera text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Passport Photograph</h6>
                                    <small class="text-muted">Recent passport photo</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Your JAMB details (name, state, LGA, UTME score) are pre-filled and cannot be edited. Please verify they are correct.
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="/apply/step/2" class="btn btn-primary btn-lg" id="continueBtn">
                            <i class="fas fa-arrow-right me-2"></i>Continue to Application Form
                        </a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-2"></i>You will be automatically logged in for this session
                        </small>
                    </div>
                </div>
                
                <div class="card-footer text-muted text-center py-3">
                    <i class="fas fa-shield-alt me-2"></i>Your information is secure
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Disable button after click to prevent double submission
document.getElementById('continueBtn').addEventListener('click', function(e) {
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
    this.style.pointerEvents = 'none';
});
</script>