<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-danger text-white text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-4x mb-3"></i>
                    <h2 class="mb-0">JAMB Verification Failed</h2>
                </div>
                
                <div class="card-body p-5 text-center">
                    <i class="fas fa-search fa-5x text-muted mb-4"></i>
                    
                    <h4 class="mb-3">JAMB Number Not Found</h4>
                    <p class="lead mb-4">The JAMB number <strong class="text-primary"><?php echo htmlspecialchars($jamb_number); ?></strong> was not found in our records.</p>
                    
                    <div class="alert alert-info text-start">
                        <h5 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Possible Reasons:</h5>
                        <ul class="mb-0">
                            <li>You may have entered an incorrect JAMB number</li>
                            <li>Your JAMB record has not been uploaded yet</li>
                            <li>You did not select this institution as first choice</li>
                            <li>Your UTME score is below the minimum requirement</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="/apply/step/1" class="btn btn-primary btn-lg">
                            <i class="fas fa-redo me-2"></i>Try Again
                        </a>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="mt-4">
                        <h5>Need help?</h5>
                        <p class="mb-1"><i class="fas fa-phone me-2 text-primary"></i>07039837749</p>
                        <p><i class="fas fa-envelope me-2 text-primary"></i>support@fctcns.edu.ng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>