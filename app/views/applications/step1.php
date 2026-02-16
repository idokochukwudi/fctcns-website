<div class="text-center mb-4">
    <h2>Step 1: JAMB Verification</h2>
    <p class="text-muted">Enter your JAMB registration number to begin your application</p>
</div>

<?php if (isset($portal_closed) && $portal_closed): ?>
    <div class="alert alert-warning text-center">
        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
        <h4>Application Portal Closed</h4>
        <p><?php echo htmlspecialchars($portal_message); ?></p>
        <hr>
        <p class="mb-0">The next admissions cycle will be announced on this portal.</p>
    </div>
<?php else: ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <?php if (empty($terms)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Terms and conditions are not available at the moment. Please try again later.
            </div>
        <?php else: ?>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-contract"></i> Terms and Conditions</h5>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                <h4><?php echo htmlspecialchars($terms['title']); ?></h4>
                <div class="terms-content">
                    <?php echo nl2br(htmlspecialchars($terms['content'])); ?>
                </div>
                <p class="text-muted mt-3"><small>Version: <?php echo htmlspecialchars($terms['version']); ?> | Effective: <?php echo date('jS F Y', strtotime($terms['effective_date'])); ?></small></p>
            </div>
        </div>
        
        <form method="POST" action="/apply/verify-jamb" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="form-group mb-3">
                <label for="jamb_number" class="form-label">JAMB Registration Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="jamb_number" name="jamb_number" 
                       placeholder="e.g., 202550805685FF" required pattern="[0-9A-Z]{10,14}">
                <div class="invalid-feedback">Please enter a valid JAMB number (10-14 characters, letters and numbers only).</div>
                <small class="text-muted">Enter the JAMB registration number you used for the 2025 UTME.</small>
            </div>
            
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="accept_terms" name="accept_terms" value="1" required>
                <label class="form-check-label" for="accept_terms">
                    I have read and agree to the Terms and Conditions <span class="text-danger">*</span>
                </label>
                <div class="invalid-feedback">You must accept the terms and conditions to proceed.</div>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> By proceeding, you confirm that:
                <ul class="mb-0 mt-2">
                    <li>You have a minimum UTME score of <?php echo htmlspecialchars($settings['key_value']['min_utme_score'] ?? '170'); ?></li>
                    <li>You selected FCT College of Nursing Sciences as your first choice</li>
                    <li>You have the required O'Level credits</li>
                    <li>You are at least <?php echo htmlspecialchars($settings['key_value']['min_age'] ?? '16'); ?> years old</li>
                </ul>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-check-circle"></i> Verify JAMB Number & Continue
                </button>
            </div>
        </form>
        <?php endif; ?>
        
        <hr class="my-4">
        
        <div class="text-center">
            <p class="mb-2">Already have an account?</p>
            <a href="/applicant/login" class="btn btn-outline-primary">
                <i class="fas fa-sign-in-alt"></i> Login to Continue Application
            </a>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    
    var forms = document.querySelectorAll('.needs-validation');
    
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
})();

// Auto-format JAMB number - Convert to uppercase and remove special characters
document.getElementById('jamb_number').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
});
</script>
<?php endif; ?>