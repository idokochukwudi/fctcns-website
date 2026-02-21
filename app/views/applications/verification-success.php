<?php
/**
 * Verification Success View - International Standard Design
 * Shown after email verification with modern green color scheme
 */
?>

<style>
/* Modern CSS Reset & Variables */
:root {
    --success-primary: #10b981;
    --success-primary-dark: #059669;
    --success-primary-light: #d1fae5;
    --success-secondary: #34d399;
    --success-soft: #ecfdf5;
    --success-gradient: linear-gradient(145deg, #10b981, #059669);
    --success-gradient-soft: linear-gradient(145deg, #ecfdf5, #d1fae5);
    --text-dark: #1f2937;
    --text-body: #4b5563;
    --text-light: #9ca3af;
    --border-light: #e5e7eb;
    --shadow-sm: 0 4px 6px -1px rgba(16, 185, 129, 0.1), 0 2px 4px -1px rgba(16, 185, 129, 0.06);
    --shadow-md: 0 20px 25px -5px rgba(16, 185, 129, 0.1), 0 10px 10px -5px rgba(16, 185, 129, 0.04);
    --shadow-lg: 0 25px 50px -12px rgba(16, 185, 129, 0.25);
    --font-sans: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
}

.verify-success-wrap {
    max-width: 640px;
    margin: 3rem auto;
    font-family: var(--font-sans);
}

/* Main Card Design */
.verify-success-card {
    background: #ffffff;
    border-radius: 32px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(16, 185, 129, 0.1);
    backdrop-filter: blur(10px);
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Success Header with Green Theme */
.verify-success-header {
    background: var(--success-gradient);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
    position: relative;
    isolation: isolate;
}

.verify-success-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.2) 0%, transparent 60%);
    z-index: -1;
}

.verify-success-header::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5L55 20L30 35L5 20L30 5Z' fill='rgba(255,255,255,0.05)'/%3E%3C/svg%3E") repeat;
    opacity: 0.1;
    z-index: -1;
}

/* Success Icon Animation */
.verify-success-icon {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 3rem;
    color: #fff;
    position: relative;
    animation: pulseCheck 2s infinite;
}

@keyframes pulseCheck {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 20px 10px rgba(255, 255, 255, 0.3);
    }
}

.verify-success-header h2 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    letter-spacing: -0.02em;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.verify-success-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 400;
}

/* Body Content */
.verify-success-body {
    padding: 2.5rem;
}

/* Welcome Section */
.welcome-message {
    text-align: center;
    margin-bottom: 2.5rem;
    position: relative;
}

.welcome-message::after {
    content: '';
    position: absolute;
    bottom: -1.25rem;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: var(--success-gradient);
    border-radius: 2px;
}

.welcome-message h3 {
    color: var(--text-dark);
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    letter-spacing: -0.01em;
}

.welcome-message p {
    color: var(--text-body);
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Account Details Card - Modern Design */
.account-details {
    background: var(--success-soft);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2.5rem;
    border: 1px solid rgba(16, 185, 129, 0.2);
    position: relative;
    overflow: hidden;
}

.account-details::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    background: var(--success-gradient);
    opacity: 0.05;
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.account-details h4 {
    color: var(--success-primary-dark);
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.account-details h4 i {
    color: var(--success-primary);
    font-size: 1.4rem;
}

.detail-grid {
    display: grid;
    gap: 1.25rem;
}

.detail-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: var(--success-primary-light);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--success-primary);
    margin-right: 1rem;
}

.detail-content {
    flex: 1;
}

.detail-label {
    color: var(--text-light);
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.detail-value {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 1.1rem;
}

.email-chip {
    background: var(--success-soft);
    color: var(--success-primary-dark);
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-size: 0.95rem;
    border: 1px solid rgba(16, 185, 129, 0.3);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--success-primary-light);
    color: var(--success-primary-dark);
    border-radius: 100px;
    font-weight: 500;
    font-size: 0.9rem;
}

/* Next Steps Section */
.next-steps {
    margin-bottom: 2.5rem;
}

.next-steps h4 {
    color: var(--text-dark);
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.next-steps h4 i {
    color: var(--success-primary);
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.step-card {
    background: white;
    border: 1px solid var(--border-light);
    border-radius: 20px;
    padding: 1.25rem 1rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.step-card:hover {
    transform: translateY(-4px);
    border-color: var(--success-primary);
    box-shadow: var(--shadow-sm);
}

.step-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--success-gradient);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.step-card:hover::before {
    transform: scaleX(1);
}

.step-number {
    width: 40px;
    height: 40px;
    background: var(--success-primary-light);
    color: var(--success-primary-dark);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.step-card:hover .step-number {
    background: var(--success-primary);
    color: white;
}

.step-card h5 {
    color: var(--text-dark);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.step-card p {
    color: var(--text-body);
    font-size: 0.85rem;
    margin-bottom: 0;
    line-height: 1.4;
}

/* Success Alert */
.alert-success-custom {
    background: var(--success-soft);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 20px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    animation: slideInRight 0.5s ease-out;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alert-success-custom i {
    color: var(--success-primary);
    font-size: 1.5rem;
    flex-shrink: 0;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    color: var(--success-primary-dark);
    display: block;
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.alert-content p {
    color: var(--text-body);
    margin-bottom: 0;
    font-size: 0.95rem;
    line-height: 1.5;
}

/* Modern Button */
.btn-success-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    width: 100%;
    padding: 1.25rem 2rem;
    background: var(--success-gradient);
    color: white;
    border: none;
    border-radius: 18px;
    font-size: 1.2rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}

.btn-success-modern::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-success-modern:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    color: white;
    text-decoration: none;
}

.btn-success-modern:hover::before {
    width: 300px;
    height: 300px;
}

.btn-success-modern i {
    transition: transform 0.3s ease;
    font-size: 1.2rem;
}

.btn-success-modern:hover i {
    transform: translateX(8px);
}

/* Secondary Links */
.login-link {
    text-align: center;
    margin: 1.5rem 0;
}

.login-link a {
    color: var(--text-light);
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.login-link a:hover {
    color: var(--success-primary);
}

.login-link a i {
    font-size: 1rem;
}

/* Support Footer */
.support-footer {
    background: var(--success-soft);
    border-radius: 100px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    color: var(--text-body);
    font-size: 0.95rem;
    margin-top: 2rem;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.support-footer i {
    color: var(--success-primary);
    font-size: 1.2rem;
}

.support-footer a {
    color: var(--success-primary-dark);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.support-footer a:hover {
    color: var(--success-primary);
}

/* Loading Spinner */
.spinner {
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 640px) {
    .verify-success-wrap {
        margin: 1.5rem 1rem;
    }
    
    .verify-success-header {
        padding: 2rem 1.5rem;
    }
    
    .verify-success-header h2 {
        font-size: 2rem;
    }
    
    .verify-success-body {
        padding: 1.5rem;
    }
    
    .steps-grid {
        grid-template-columns: 1fr;
    }
    
    .support-footer {
        flex-direction: column;
        text-align: center;
        border-radius: 24px;
        gap: 0.5rem;
    }
    
    .detail-item {
        flex-wrap: wrap;
    }
    
    .alert-success-custom {
        flex-direction: column;
        text-align: center;
    }
}

/* Print Styles */
@media print {
    .verify-success-card {
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .btn-success-modern,
    .login-link,
    .support-footer {
        display: none;
    }
}
</style>

<div class="verify-success-wrap">
    <div class="verify-success-card">
        <!-- Header with Green Gradient -->
        <div class="verify-success-header">
            <div class="verify-success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Verification Successful!</h2>
            <p>Your email has been verified</p>
        </div>
        
        <div class="verify-success-body">
            <!-- Welcome Message -->
            <div class="welcome-message">
                <h3>Welcome, <?php echo htmlspecialchars($applicant_name ?? 'Applicant'); ?>!</h3>
                <p>Your account is now active and ready for application</p>
            </div>
            
            <!-- Account Details - Modern Card -->
            <div class="account-details">
                <h4>
                    <i class="fas fa-id-card"></i>
                    Account Overview
                </h4>
                
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Email Address</div>
                            <div class="detail-value">
                                <span class="email-chip">
                                    <i class="fas fa-check-circle"></i>
                                    <?php echo htmlspecialchars($email ?? $applicant['email'] ?? 'Verified'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Verification Status</div>
                            <div class="detail-value">
                                <span class="status-badge">
                                    <i class="fas fa-check-circle"></i>
                                    Verified
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Account Status</div>
                            <div class="detail-value">
                                <span class="status-badge" style="background: #e8f5e9; color: #2e7d32;">
                                    <i class="fas fa-circle"></i>
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Next Steps -->
            <div class="next-steps">
                <h4>
                    <i class="fas fa-tasks"></i>
                    Complete Your Application
                </h4>
                
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h5>JAMB Verification</h5>
                        <p>Link your JAMB registration</p>
                    </div>
                    
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h5>Personal Details</h5>
                        <p>Complete your profile</p>
                    </div>
                    
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h5>Payment</h5>
                        <p>Process application fee</p>
                    </div>
                    
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h5>Exam Slip</h5>
                        <p>Download your slip</p>
                    </div>
                </div>
            </div>
            
            <!-- Success Message -->
            <div class="alert-success-custom">
                <i class="fas fa-check-circle"></i>
                <div class="alert-content">
                    <strong>✓ Session Active</strong>
                    <p>You're logged in. Proceed to verify your JAMB number.</p>
                </div>
            </div>
            
            <!-- Main Action Button -->
            <a href="/apply/step/1" class="btn-success-modern" id="continueBtn">
                <span>Continue to JAMB Verification</span>
                <i class="fas fa-arrow-right" id="btnIcon"></i>
                <span class="spinner" style="display: none;" id="spinner"></span>
            </a>
            
            <!-- Secondary Link -->
            <div class="login-link">
                <a href="/applicant/login">
                    <i class="fas fa-sign-in-alt"></i>
                    Already registered? Login here
                </a>
            </div>
            
            <!-- Support Information -->
            <div class="support-footer">
                <i class="fas fa-headset"></i>
                <span>Need assistance? Contact our support team</span>
                <a href="tel:07039837749">0703 983 7749</a>
                <span>•</span>
                <a href="mailto:support@fctcns.edu.ng">Email</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const continueBtn = document.getElementById('continueBtn');
    const btnIcon = document.getElementById('btnIcon');
    const spinner = document.getElementById('spinner');
    
    if (continueBtn) {
        continueBtn.addEventListener('click', function(e) {
            // Disable button to prevent double clicking
            this.style.pointerEvents = 'none';
            btnIcon.style.display = 'none';
            spinner.style.display = 'inline-block';
            this.querySelector('span:first-child').textContent = 'Loading...';
        });
    }
    
    // Animated countdown for better UX
    let seconds = 5;
    const btn = document.getElementById('continueBtn');
    const originalText = btn.querySelector('span:first-child').textContent;
    
    const timer = setInterval(function() {
        seconds--;
        if (seconds > 0 && seconds < 5) {
            btn.querySelector('span:first-child').textContent = `Continue (${seconds}s)`;
        } else if (seconds <= 0) {
            clearInterval(timer);
            if (btn.style.pointerEvents !== 'none') {
                btn.querySelector('span:first-child').textContent = originalText;
            }
        }
    }, 1000);
});
</script>