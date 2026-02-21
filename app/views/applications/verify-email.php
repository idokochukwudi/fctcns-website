<?php
/**
 * Email Verification View - International Standard Design
 * Handles all verification states with modern UI
 */
?>

<style>
/* Modern CSS Reset & Variables */
:root {
    /* Success colors */
    --success-primary: #10b981;
    --success-primary-dark: #059669;
    --success-primary-light: #d1fae5;
    --success-soft: #ecfdf5;
    
    /* Error colors */
    --error-primary: #ef4444;
    --error-primary-dark: #dc2626;
    --error-primary-light: #fee2e2;
    --error-soft: #fef2f2;
    
    /* Info colors */
    --info-primary: #3b82f6;
    --info-primary-dark: #2563eb;
    --info-primary-light: #dbeafe;
    --info-soft: #eff6ff;
    
    /* Warning colors */
    --warning-primary: #f59e0b;
    --warning-primary-dark: #d97706;
    --warning-primary-light: #fef3c7;
    --warning-soft: #fffbeb;
    
    /* Neutral colors */
    --neutral-50: #f9fafb;
    --neutral-100: #f3f4f6;
    --neutral-200: #e5e7eb;
    --neutral-300: #d1d5db;
    --neutral-400: #9ca3af;
    --neutral-500: #6b7280;
    --neutral-600: #4b5563;
    --neutral-700: #374151;
    --neutral-800: #1f2937;
    --neutral-900: #111827;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    
    /* Font */
    --font-sans: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

/* Base Styles */
.verify-modern-wrap {
    max-width: 560px;
    margin: 2rem auto;
    font-family: var(--font-sans);
}

/* Card Styles */
.verify-modern-card {
    background: white;
    border-radius: 32px;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    border: 1px solid var(--neutral-200);
    animation: cardEntrance 0.4s ease-out;
}

@keyframes cardEntrance {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Header Styles - Dynamic by state */
.verify-modern-header {
    padding: 2.5rem 2rem;
    text-align: center;
    position: relative;
    isolation: isolate;
    overflow: hidden;
}

.verify-modern-header.success {
    background: linear-gradient(145deg, #10b981, #059669);
}

.verify-modern-header.danger {
    background: linear-gradient(145deg, #ef4444, #dc2626);
}

.verify-modern-header.info {
    background: linear-gradient(145deg, #3b82f6, #2563eb);
}

.verify-modern-header.warning {
    background: linear-gradient(145deg, #f59e0b, #d97706);
}

.verify-modern-header.primary {
    background: linear-gradient(145deg, #4f46e5, #4338ca);
}

.verify-modern-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.2) 0%, transparent 70%);
    z-index: -1;
}

.verify-modern-header::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5L55 20L30 35L5 20L30 5Z' fill='rgba(255,255,255,0.05)'/%3E%3C/svg%3E");
    opacity: 0.2;
    z-index: -1;
}

/* Header Icon */
.verify-modern-icon {
    width: 88px;
    height: 88px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    border: 3px solid rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
    color: white;
    animation: iconPulse 2s infinite;
}

@keyframes iconPulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(255,255,255,0.5);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 20px 10px rgba(255,255,255,0.2);
    }
}

.verify-modern-header h2 {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    margin: 0 0 0.5rem;
    letter-spacing: -0.02em;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.verify-modern-header p {
    font-size: 1rem;
    color: rgba(255,255,255,0.9);
    margin: 0;
    font-weight: 400;
}

/* Body Styles */
.verify-modern-body {
    padding: 2.5rem;
}

/* Greeting Section */
.verify-modern-greeting {
    text-align: center;
    margin-bottom: 2rem;
    position: relative;
}

.verify-modern-greeting::after {
    content: '';
    position: absolute;
    bottom: -1rem;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, transparent, var(--neutral-300), transparent);
    border-radius: 2px;
}

.greeting-icon {
    width: 64px;
    height: 64px;
    background: var(--neutral-100);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: var(--neutral-600);
    border: 2px solid var(--neutral-200);
}

.verify-modern-greeting h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0 0 0.5rem;
}

.verify-modern-greeting p {
    font-size: 1rem;
    color: var(--neutral-600);
    line-height: 1.6;
    margin: 0;
}

/* Alert Banners */
.alert-modern {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: 20px;
    margin-bottom: 1.5rem;
    animation: slideInRight 0.3s ease-out;
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

.alert-modern i {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.alert-modern span {
    flex: 1;
    font-size: 0.95rem;
    line-height: 1.5;
}

.alert-modern.success {
    background: var(--success-soft);
    border: 1px solid var(--success-primary-light);
    color: var(--success-primary-dark);
}

.alert-modern.success i {
    color: var(--success-primary);
}

.alert-modern.danger {
    background: var(--error-soft);
    border: 1px solid var(--error-primary-light);
    color: var(--error-primary-dark);
}

.alert-modern.danger i {
    color: var(--error-primary);
}

.alert-modern.info {
    background: var(--info-soft);
    border: 1px solid var(--info-primary-light);
    color: var(--info-primary-dark);
}

.alert-modern.info i {
    color: var(--info-primary);
}

.alert-modern.warning {
    background: var(--warning-soft);
    border: 1px solid var(--warning-primary-light);
    color: var(--warning-primary-dark);
}

.alert-modern.warning i {
    color: var(--warning-primary);
}

/* Email Chip */
.email-chip-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: var(--neutral-100);
    border: 1px solid var(--neutral-200);
    border-radius: 100px;
    padding: 0.75rem 1.5rem;
    margin: 1rem 0;
    font-size: 1rem;
    font-weight: 500;
    color: var(--neutral-800);
    box-shadow: var(--shadow-sm);
}

.email-chip-modern i {
    color: var(--info-primary);
}

/* Reasons List */
.reasons-modern {
    background: var(--neutral-50);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--neutral-200);
}

.reasons-modern h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-700);
    margin: 0 0 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.reasons-modern h5 i {
    color: var(--warning-primary);
}

.reasons-modern ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.reasons-modern li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    font-size: 0.95rem;
    color: var(--neutral-600);
    line-height: 1.5;
}

.reason-bullet {
    width: 20px;
    height: 20px;
    background: var(--error-soft);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}

.reason-bullet i {
    font-size: 0.7rem;
    color: var(--error-primary);
}

/* Tips Block */
.tips-modern {
    background: var(--neutral-50);
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid var(--neutral-200);
    margin-bottom: 1.5rem;
}

.tips-header {
    background: var(--neutral-100);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border-bottom: 1px solid var(--neutral-200);
}

.tips-header i {
    color: var(--warning-primary);
    font-size: 1rem;
}

.tips-header span {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--neutral-700);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tips-list {
    padding: 1.25rem 1.5rem;
    margin: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.tips-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    font-size: 0.95rem;
    color: var(--neutral-600);
    line-height: 1.5;
}

.tips-list li i {
    width: 18px;
    font-size: 0.9rem;
    margin-top: 3px;
    flex-shrink: 0;
}

.tips-list li i.fa-folder { color: #d97706; }
.tips-list li i.fa-clock { color: #3b82f6; }
.tips-list li i.fa-at { color: #10b981; }

/* Resend Block */
.resend-modern {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
}

.resend-modern p {
    font-size: 0.95rem;
    color: var(--neutral-600);
    margin-bottom: 1rem;
}

.resend-email-display {
    background: var(--neutral-50);
    border-radius: 12px;
    padding: 0.75rem;
    margin-top: 1rem;
    font-size: 0.9rem;
    color: var(--neutral-500);
    word-break: break-all;
}

.resend-email-display i {
    color: var(--info-primary);
    margin-right: 0.5rem;
}

/* Buttons */
.btn-modern-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    width: 100%;
    padding: 1rem 1.5rem;
    background: linear-gradient(145deg, #4f46e5, #4338ca);
    color: white;
    border: none;
    border-radius: 18px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    margin-bottom: 0.75rem;
    position: relative;
    overflow: hidden;
}

.btn-modern-primary::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-modern-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    color: white;
    text-decoration: none;
}

.btn-modern-primary:hover::before {
    width: 300px;
    height: 300px;
}

.btn-modern-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    width: 100%;
    padding: 1rem 1.5rem;
    background: white;
    color: var(--neutral-700);
    border: 1.5px solid var(--neutral-200);
    border-radius: 18px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-bottom: 0.75rem;
}

.btn-modern-outline:hover {
    background: var(--neutral-50);
    border-color: var(--neutral-300);
    color: var(--neutral-900);
    text-decoration: none;
    transform: translateY(-1px);
}

.btn-modern-ghost {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: transparent;
    color: var(--neutral-500);
    border: none;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-modern-ghost:hover {
    background: var(--neutral-100);
    color: var(--neutral-700);
    text-decoration: none;
}

/* Divider */
.divider-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 1.5rem 0;
    color: var(--neutral-400);
    font-size: 0.85rem;
}

.divider-modern::before,
.divider-modern::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--neutral-200), transparent);
}

/* Action Group */
.action-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    margin: 1.5rem 0;
}

/* Support Footer */
.support-modern {
    background: var(--neutral-50);
    border: 1px solid var(--neutral-200);
    border-radius: 100px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: var(--neutral-600);
}

.support-modern i {
    color: var(--info-primary);
    font-size: 1.1rem;
}

.support-modern a {
    color: var(--neutral-700);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
}

.support-modern a:hover {
    color: var(--info-primary);
}

/* Loading Spinner */
.spinner-modern {
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 640px) {
    .verify-modern-wrap {
        margin: 1rem;
    }
    
    .verify-modern-body {
        padding: 1.5rem;
    }
    
    .verify-modern-header {
        padding: 2rem 1.5rem;
    }
    
    .verify-modern-header h2 {
        font-size: 1.75rem;
    }
    
    .support-modern {
        flex-direction: column;
        text-align: center;
        border-radius: 24px;
        gap: 0.5rem;
    }
    
    .action-group {
        flex-direction: column;
    }
}

/* Print Styles */
@media print {
    .verify-modern-card {
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .btn-modern-primary,
    .btn-modern-outline,
    .btn-modern-ghost,
    .support-modern {
        display: none;
    }
}
</style>

<div class="verify-modern-wrap">

    <?php if (isset($verified) && $verified): ?>
    <!-- STATE 1: Email Verified Successfully -->
    <div class="verify-modern-card">
        <div class="verify-modern-header success">
            <div class="verify-modern-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Email Verified!</h2>
            <p>FCT College of Nursing Sciences</p>
        </div>
        
        <div class="verify-modern-body">
            <div class="verify-modern-greeting">
                <div class="greeting-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h4>Welcome, <?php echo htmlspecialchars($applicant_name ?? 'Applicant'); ?>!</h4>
                <p>Your email has been successfully verified. Your account is now active.</p>
            </div>

            <div class="alert-modern success">
                <i class="fas fa-check-circle"></i>
                <span><strong>Ready to proceed:</strong> Verify your JAMB registration number to continue.</span>
            </div>

            <a href="/apply/step/1" class="btn-modern-primary" id="continueBtn">
                <span>Continue to JAMB Verification</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <?php elseif (isset($error)): ?>
    <!-- STATE 2: Verification Failed -->
    <div class="verify-modern-card">
        <div class="verify-modern-header danger">
            <div class="verify-modern-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2>Verification Failed</h2>
            <p>Unable to verify your email</p>
        </div>
        
        <div class="verify-modern-body">
            <div class="alert-modern danger">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>

            <div class="reasons-modern">
                <h5>
                    <i class="fas fa-search"></i>
                    Common reasons
                </h5>
                <ul>
                    <li>
                        <span class="reason-bullet"><i class="fas fa-times"></i></span>
                        Link expired (valid for 24 hours only)
                    </li>
                    <li>
                        <span class="reason-bullet"><i class="fas fa-times"></i></span>
                        Link already used
                    </li>
                    <li>
                        <span class="reason-bullet"><i class="fas fa-times"></i></span>
                        Invalid or corrupted token
                    </li>
                </ul>
            </div>

            <?php if (isset($resend_email) && $resend_email): ?>
            <div class="resend-modern">
                <p><i class="fas fa-paper-plane" style="color: var(--info-primary); margin-right: 0.5rem;"></i> Need a new verification link?</p>
                <a href="/apply/resend-verification?email=<?php echo urlencode($resend_email); ?>" 
                   class="btn-modern-outline" style="margin-bottom: 0;">
                    <i class="fas fa-redo-alt"></i> Resend Verification Email
                </a>
                <?php if (!empty($resend_email)): ?>
                <div class="resend-email-display">
                    <i class="fas fa-envelope"></i>
                    <?php echo htmlspecialchars($resend_email); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="action-group">
                <a href="/apply/register" class="btn-modern-primary">
                    <i class="fas fa-user-plus"></i> Register Again
                </a>
                <a href="/applicant/login" class="btn-modern-outline">
                    <i class="fas fa-sign-in-alt"></i> Try Login Instead
                </a>
            </div>
        </div>
    </div>

    <?php elseif (isset($message)): ?>
    <!-- STATE 3: Already Verified -->
    <div class="verify-modern-card">
        <div class="verify-modern-header info">
            <div class="verify-modern-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <h2>Already Verified</h2>
            <p>Email already confirmed</p>
        </div>
        
        <div class="verify-modern-body">
            <div class="alert-modern info">
                <i class="fas fa-info-circle"></i>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>

            <a href="/applicant/login" class="btn-modern-primary">
                <i class="fas fa-sign-in-alt"></i> Go to Login
            </a>
        </div>
    </div>

    <?php elseif (isset($email_sent) && $email_sent): ?>
    <!-- STATE 4: Verification Email Sent -->
    <div class="verify-modern-card">
        <div class="verify-modern-header primary">
            <div class="verify-modern-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <h2>Check Your Inbox</h2>
            <p>Verification email sent</p>
        </div>
        
        <div class="verify-modern-body">
            <div class="verify-modern-greeting">
                <div class="greeting-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Verification link sent!</h4>
                <?php if (!empty($email)): ?>
                <div class="email-chip-modern">
                    <i class="fas fa-envelope"></i>
                    <?php echo htmlspecialchars($email); ?>
                </div>
                <?php else: ?>
                <p>We've sent a verification link to your registered email address.</p>
                <?php endif; ?>
            </div>

            <div class="alert-modern warning">
                <i class="fas fa-clock"></i>
                <span><strong>Valid for 24 hours.</strong> Please verify your email before the link expires.</span>
            </div>

            <div class="resend-modern">
                <p><i class="fas fa-question-circle" style="color: var(--warning-primary);"></i> Didn't receive the email?</p>
                <a href="/apply/resend-verification?email=<?php echo urlencode($email ?? ''); ?>"
                   class="btn-modern-outline" style="margin-bottom: 0;">
                    <i class="fas fa-redo-alt"></i> Resend Email
                </a>
            </div>

            <div class="tips-modern">
                <div class="tips-header">
                    <i class="fas fa-lightbulb"></i>
                    <span>Troubleshooting</span>
                </div>
                <ul class="tips-list">
                    <li>
                        <i class="fas fa-folder"></i>
                        Check your <strong>spam/junk folder</strong>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        Wait a few minutes — delivery may take time
                    </li>
                    <li>
                        <i class="fas fa-at"></i>
                        Verify you entered the <strong>correct email</strong>
                    </li>
                </ul>
            </div>

            <div class="divider-modern">
                <span>or</span>
            </div>

            <div class="action-group">
                <a href="/applicant/login" class="btn-modern-ghost">
                    <i class="fas fa-sign-in-alt"></i> Already verified? Login
                </a>
                <a href="/apply/register" class="btn-modern-ghost">
                    <i class="fas fa-user-plus"></i> Register with different email
                </a>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- STATE 5: Fallback / Error -->
    <div class="verify-modern-card">
        <div class="verify-modern-header warning">
            <div class="verify-modern-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2>Something Went Wrong</h2>
            <p>Unexpected error occurred</p>
        </div>
        
        <div class="verify-modern-body">
            <div class="alert-modern warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>An unexpected error occurred. Please try again or contact support.</span>
            </div>

            <div class="action-group">
                <a href="/apply/register" class="btn-modern-primary">
                    <i class="fas fa-redo"></i> Register Again
                </a>
                <a href="/applicant/login" class="btn-modern-outline">
                    <i class="fas fa-sign-in-alt"></i> Go to Login
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Support Footer (shown on all states) -->
    <div class="support-modern">
        <i class="fas fa-headset"></i>
        <span>Need help?</span>
        <a href="mailto:support@fctcns.edu.ng">support@fctcns.edu.ng</a>
        <span>•</span>
        <a href="tel:07039837749">0703 983 7749</a>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle button loading states
    const buttons = document.querySelectorAll('.btn-modern-primary, .btn-modern-outline');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.classList.contains('btn-modern-primary') && !this.hasAttribute('data-no-loading')) {
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-modern" style="margin-right: 0.5rem;"></span> Loading...';
                this.style.pointerEvents = 'none';
                
                // Restore after 10 seconds if stuck (safety)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.pointerEvents = 'auto';
                }, 10000);
            }
        });
    });
    
    // Auto-dismiss non-critical alerts after 8 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert-modern:not(.warning):not(.danger)').forEach(function(el) {
            el.style.transition = 'opacity 0.5s, transform 0.5s';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            setTimeout(function() { 
                if (el.parentNode) el.remove(); 
            }, 500);
        });
    }, 8000);
});
</script>