<?php
/**
 * Verification Success View
 * Shown after email verification - User clicks from email and sees this page
 * Then clicks button to proceed to JAMB verification (Step 1)
 */
?>

<style>
.verify-success-wrap {
    max-width: 600px;
    margin: 2rem auto;
}

.verify-success-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.12);
}

.verify-success-header {
    background: linear-gradient(135deg, #0F1B35 0%, #1e2b4f 100%);
    color: white;
    padding: 2.5rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.verify-success-header::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    opacity: 0.3;
}

.verify-success-icon {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.15);
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
    color: #fff;
}

.verify-success-header h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-family: 'Playfair Display', serif;
}

.verify-success-header p {
    opacity: 0.9;
    font-size: 1rem;
    margin-bottom: 0;
}

.verify-success-body {
    padding: 2.5rem 2rem;
}

.welcome-message {
    text-align: center;
    margin-bottom: 2rem;
}

.welcome-message h3 {
    color: #0F1B35;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-family: 'Playfair Display', serif;
}

.welcome-message p {
    color: #6B7280;
    font-size: 1rem;
}

.account-details {
    background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f8 100%);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #e5e7eb;
}

.account-details h4 {
    color: #0F1B35;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.account-details h4 i {
    color: #C6A43F;
}

.detail-row {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    width: 120px;
    color: #6B7280;
    font-size: 0.9rem;
}

.detail-value {
    flex: 1;
    color: #1F2937;
    font-weight: 500;
}

.email-highlight {
    background: #e8f0fe;
    color: #0F1B35;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-family: monospace;
    font-size: 0.95rem;
    border: 1px solid #C6A43F;
}

.next-steps {
    margin-bottom: 2rem;
}

.next-steps h4 {
    color: #0F1B35;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.next-steps h4 i {
    color: #C6A43F;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.step-item {
    background: #f9fafb;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    border: 1px solid #e5e7eb;
    transition: all 0.3s;
}

.step-item:hover {
    border-color: #C6A43F;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.step-number {
    width: 32px;
    height: 32px;
    background: #0F1B35;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    font-weight: 600;
    font-size: 0.9rem;
}

.step-item h5 {
    color: #0F1B35;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.step-item p {
    color: #6B7280;
    font-size: 0.8rem;
    margin-bottom: 0;
}

.alert-info-custom {
    background: #f0f7ff;
    border-left: 4px solid #C6A43F;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.alert-info-custom i {
    color: #C6A43F;
    font-size: 1.2rem;
    margin-top: 2px;
}

.alert-info-custom p {
    color: #1F2937;
    margin-bottom: 0;
    font-size: 0.95rem;
}

.btn-success-custom {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    width: 100%;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #0F1B35 0%, #1e2b4f 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(15,27,53,0.2);
}

.btn-success-custom:hover {
    background: linear-gradient(135deg, #1a2b4e 0%, #2a3b5f 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(15,27,53,0.3);
    color: white;
    text-decoration: none;
}

.btn-success-custom i {
    transition: transform 0.3s;
}

.btn-success-custom:hover i {
    transform: translateX(5px);
}

.login-link {
    text-align: center;
    margin-top: 1.5rem;
}

.login-link a {
    color: #6B7280;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s;
}

.login-link a:hover {
    color: #0F1B35;
}

.login-link a i {
    margin-right: 0.25rem;
}

.support-footer {
    margin-top: 1.5rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    color: #6B7280;
    font-size: 0.9rem;
}

.support-footer i {
    color: #C6A43F;
}

@media (max-width: 480px) {
    .verify-success-body {
        padding: 1.5rem;
    }
    
    .steps-grid {
        grid-template-columns: 1fr;
    }
    
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .detail-label {
        width: auto;
    }
}
</style>

<div class="verify-success-wrap">
    <div class="verify-success-card">
        <div class="verify-success-header">
            <div class="verify-success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Email Verified Successfully!</h2>
            <p>FCT College of Nursing Sciences</p>
        </div>
        
        <div class="verify-success-body">
            <!-- Welcome Message -->
            <div class="welcome-message">
                <h3>Welcome, <?php echo htmlspecialchars($applicant_name ?? 'Applicant'); ?>!</h3>
                <p>Your email address has been verified. You're now ready to begin your application.</p>
            </div>
            
            <!-- Account Details -->
            <div class="account-details">
                <h4>
                    <i class="fas fa-user-circle"></i>
                    Account Information
                </h4>
                
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">
                        <span class="email-highlight">
                            <i class="fas fa-envelope"></i>
                            <?php echo htmlspecialchars($email ?? $applicant['email'] ?? 'Not available'); ?>
                        </span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Account:</span>
                    <span class="detail-value">
                        <span class="badge bg-info">Active</span>
                    </span>
                </div>
            </div>
            
            <!-- Next Steps -->
            <div class="next-steps">
                <h4>
                    <i class="fas fa-road"></i>
                    Next Steps in Your Application
                </h4>
                
                <div class="steps-grid">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <h5>JAMB Verification</h5>
                        <p>Verify your JAMB registration number</p>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <h5>Application Form</h5>
                        <p>Complete your personal details</p>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <h5>Payment</h5>
                        <p>Pay application fee via Remita</p>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <h5>Exam Slip</h5>
                        <p>Download your examination slip</p>
                    </div>
                </div>
            </div>
            
            <!-- Important Note -->
            <div class="alert-info-custom">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>You're now logged in!</strong> Your session is active. The next step is to verify your JAMB registration number.
                </div>
            </div>
            
            <!-- Continue Button -->
            <a href="/apply/step/1" class="btn-success-custom" id="continueBtn">
                <span>Continue to JAMB Verification</span>
                <i class="fas fa-arrow-right"></i>
            </a>
            
            <!-- Alternative Login Link -->
            <div class="login-link">
                <a href="/applicant/login">
                    <i class="fas fa-sign-in-alt"></i> Already have an account? Login here
                </a>
            </div>
            
            <!-- Support Footer -->
            <div class="support-footer">
                <i class="fas fa-headset"></i>
                <span>Need help? Contact support: 07039837749 or info@fctcns.edu.ng</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const continueBtn = document.getElementById('continueBtn');
    
    if (continueBtn) {
        continueBtn.addEventListener('click', function(e) {
            // Disable button to prevent double clicking
            this.style.pointerEvents = 'none';
            this.innerHTML = '<span>Loading...</span> <i class="fas fa-spinner fa-spin"></i>';
        });
    }
    
    // Optional: Auto-redirect after 10 seconds
    let seconds = 10;
    const btn = document.getElementById('continueBtn');
    const originalText = btn.innerHTML;
    
    const timer = setInterval(function() {
        seconds--;
        if (seconds > 0 && seconds < 10) {
            btn.innerHTML = `<span>Continue to JAMB Verification (${seconds}s)</span> <i class="fas fa-arrow-right"></i>`;
        } else if (seconds <= 0) {
            clearInterval(timer);
            btn.innerHTML = originalText;
        }
    }, 1000);
});
</script>