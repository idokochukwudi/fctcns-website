<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Progress Indicator -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary">FCT College of Nursing Sciences</h1>
                <p class="lead">2025/2026 Admissions Application Portal</p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <span class="badge bg-primary rounded-pill px-3 py-2">Step 1: JAMB Verification</span>
                    <span class="badge bg-secondary rounded-pill px-3 py-2">Step 2: Application Form</span>
                    <span class="badge bg-secondary rounded-pill px-3 py-2">Step 3: Payment</span>
                    <span class="badge bg-secondary rounded-pill px-3 py-2">Step 4: Exam Slip</span>
                </div>
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

            <!-- Alert Container for Messages -->
            <div id="alertContainer" class="mb-4"></div>

            <!-- JAMB Verification Card -->
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <i class="fas fa-id-card fa-3x mb-3"></i>
                    <h2 class="h3 mb-0">JAMB Verification</h2>
                    <p class="mb-0 small">Enter your JAMB registration number to begin</p>
                </div>
                
                <div class="card-body p-4">
                    <?php if (empty($terms)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Terms and conditions are not available at the moment. Please try again later.
                        </div>
                    <?php else: ?>
                    
                    <!-- Terms and Conditions -->
                    <div class="card bg-light mb-4">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Terms and Conditions</h5>
                        </div>
                        <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                            <h6><?php echo htmlspecialchars($terms['title']); ?></h6>
                            <div class="small">
                                <?php echo nl2br(htmlspecialchars($terms['content'])); ?>
                            </div>
                            <hr>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-clock me-1"></i>Version: <?php echo $terms['version']; ?> | 
                                Effective: <?php echo date('jS F Y', strtotime($terms['effective_date'])); ?>
                            </p>
                        </div>
                    </div>

                    <!-- JAMB Verification Form -->
                    <form id="jambVerificationForm">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="mb-4">
                            <label for="jamb_number" class="form-label fw-bold">
                                <i class="fas fa-id-card me-2 text-primary"></i>JAMB Registration Number
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="jamb_number" 
                                   name="jamb_number" 
                                   placeholder="e.g., 202550805685FF"
                                   style="text-transform: uppercase;"
                                   required>
                            <small class="text-muted">Enter the JAMB registration number you used for the 2025 UTME.</small>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="accept_terms" name="accept_terms" required>
                            <label class="form-check-label" for="accept_terms">
                                I have read and agree to the 
                                <a href="/terms" target="_blank" class="text-primary">Terms and Conditions</a>
                            </label>
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
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100" id="verifyBtn">
                            <span id="btnText"><i class="fas fa-check-circle me-2"></i>Verify JAMB Number</span>
                            <span id="btnSpinner" style="display: none;">
                                <i class="fas fa-spinner fa-spin me-2"></i>Verifying...
                            </span>
                        </button>
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
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Auto-format JAMB number - Convert to uppercase and remove special characters
document.getElementById('jamb_number').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
});

// JAMB Verification Form Submission
document.getElementById('jambVerificationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const jambNumber = document.getElementById('jamb_number').value.trim().toUpperCase();
    const acceptTerms = document.getElementById('accept_terms').checked;
    
    if (!jambNumber) {
        showAlert('Please enter your JAMB number', 'danger');
        return;
    }
    
    if (!/^[0-9A-Z]{10,14}$/.test(jambNumber)) {
        showAlert('Invalid JAMB number format', 'danger');
        return;
    }
    
    if (!acceptTerms) {
        showAlert('You must accept the terms and conditions', 'danger');
        return;
    }
    
    // Show loading
    document.getElementById('btnText').style.display = 'none';
    document.getElementById('btnSpinner').style.display = 'inline-block';
    document.getElementById('verifyBtn').disabled = true;
    
    try {
        const response = await fetch('/apply/verify-jamb', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        console.log('Response:', data); // Debug log
        
        if (data.success) {
            // Store JAMB data in sessionStorage
            sessionStorage.setItem('jamb_data', JSON.stringify(data.data));
            sessionStorage.setItem('jamb_verified', 'true');
            
            showAlert('JAMB verified successfully! Redirecting to application form...', 'success');
            
            // Redirect to application form with JAMB data
            setTimeout(() => {
                window.location.href = '/apply/form';
            }, 1500);
        } else {
            showAlert(data.message || 'Verification failed', 'danger');
            resetButton();
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Network error. Please try again.', 'danger');
        resetButton();
    }
});

function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alertContainer.innerHTML = '', 300);
        }
    }, 5000);
}

function resetButton() {
    document.getElementById('btnText').style.display = 'inline-block';
    document.getElementById('btnSpinner').style.display = 'none';
    document.getElementById('verifyBtn').disabled = false;
}

// Check if already verified on page load
document.addEventListener('DOMContentLoaded', function() {
    const jambVerified = sessionStorage.getItem('jamb_verified');
    const jambData = sessionStorage.getItem('jamb_data');
    
    if (jambVerified === 'true' && jambData) {
        showAlert('You already have verified JAMB data. Redirecting to application form...', 'info');
        setTimeout(() => {
            window.location.href = '/apply/form';
        }, 2000);
    }
});
</script>