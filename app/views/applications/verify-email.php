<?php
/**
 * Email Verification View - Professional Design
 * Wider cards with subtle colors that integrate seamlessly with layout
 */
?>

<style>
/* ==========================================================================
   PROFESSIONAL DESIGN SYSTEM - SUBTLE & SOPHISTICATED
   Wider cards for better content display
   ========================================================================== */

:root {
    /* Primary - Soft Lavender (minimal, professional) */
    --primary-50: #f5f3ff;
    --primary-100: #ede9fe;
    --primary-200: #ddd6fe;
    --primary-300: #c4b5fd;
    --primary-400: #a78bfa;
    --primary-500: #8b5cf6;
    --primary-600: #7c3aed;
    --primary-700: #6d28d9;
    
    /* Success - Muted Green */
    --success-50: #ecfdf5;
    --success-100: #d1fae5;
    --success-200: #a7f3d0;
    --success-300: #6ee7b7;
    --success-400: #34d399;
    --success-500: #10b981;
    --success-600: #059669;
    
    /* Error - Muted Rose */
    --error-50: #fff1f2;
    --error-100: #ffe4e6;
    --error-200: #fecdd3;
    --error-300: #fda4af;
    --error-400: #fb7185;
    --error-500: #f43f5e;
    --error-600: #e11d48;
    
    /* Warning - Muted Amber */
    --warning-50: #fffbeb;
    --warning-100: #fef3c7;
    --warning-200: #fde68a;
    --warning-300: #fcd34d;
    --warning-400: #fbbf24;
    --warning-500: #f59e0b;
    --warning-600: #d97706;
    
    /* Info - Muted Blue */
    --info-50: #eff6ff;
    --info-100: #dbeafe;
    --info-200: #bfdbfe;
    --info-300: #93c5fd;
    --info-400: #60a5fa;
    --info-500: #3b82f6;
    --info-600: #2563eb;
    
    /* Neutral - Sophisticated Grays */
    --neutral-50: #fafafa;
    --neutral-100: #f4f4f5;
    --neutral-200: #e4e4e7;
    --neutral-300: #d4d4d8;
    --neutral-400: #a1a1aa;
    --neutral-500: #71717a;
    --neutral-600: #52525b;
    --neutral-700: #3f3f46;
    --neutral-800: #27272a;
    --neutral-900: #18181b;
    
    /* Background & Surface */
    --bg-body: var(--neutral-50);
    --bg-surface: #ffffff;
    --bg-subtle: var(--neutral-100);
    
    /* Borders */
    --border-light: var(--neutral-200);
    --border: var(--neutral-300);
    
    /* Shadows - Extremely Subtle */
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.02);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.02), 0 2px 4px -2px rgb(0 0 0 / 0.01);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.02), 0 4px 6px -4px rgb(0 0 0 / 0.01);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.02), 0 8px 10px -6px rgb(0 0 0 / 0.01);
    
    /* Border Radius - Consistent */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.25rem;
    
    /* Typography */
    --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
    
    /* Spacing - Responsive */
    --space-1: clamp(0.125rem, 0.5vw, 0.25rem);
    --space-2: clamp(0.25rem, 1vw, 0.5rem);
    --space-3: clamp(0.375rem, 1.5vw, 0.75rem);
    --space-4: clamp(0.5rem, 2vw, 1rem);
    --space-5: clamp(0.625rem, 2.5vw, 1.25rem);
    --space-6: clamp(0.75rem, 3vw, 1.5rem);
    --space-8: clamp(1rem, 4vw, 2rem);
    --space-10: clamp(1.25rem, 5vw, 2.5rem);
    --space-12: clamp(1.5rem, 6vw, 3rem);
    --space-16: clamp(2rem, 8vw, 4rem);
}

/* Base Styles */
.verify-modern-wrap {
    max-width: 720px; /* Increased from 560px for wider cards */
    width: 100%;
    margin: 0 auto; /* Removed vertical margin - will be handled by layout */
    font-family: var(--font-sans);
    padding: 0 var(--space-4); /* Responsive padding */
}

/* Card Styles - Wider and more elegant */
.verify-modern-card {
    background: white;
    border-radius: var(--radius-2xl);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-light);
    animation: cardEntrance 0.4s ease-out;
    width: 100%;
}

@keyframes cardEntrance {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Header Styles - Subtle gradients */
.verify-modern-header {
    padding: var(--space-8) var(--space-8);
    text-align: center;
    position: relative;
    isolation: isolate;
    overflow: hidden;
}

.verify-modern-header.success {
    background: linear-gradient(145deg, var(--success-50), var(--success-100));
    border-bottom: 1px solid var(--success-200);
}

.verify-modern-header.danger {
    background: linear-gradient(145deg, var(--error-50), var(--error-100));
    border-bottom: 1px solid var(--error-200);
}

.verify-modern-header.info {
    background: linear-gradient(145deg, var(--info-50), var(--info-100));
    border-bottom: 1px solid var(--info-200);
}

.verify-modern-header.warning {
    background: linear-gradient(145deg, var(--warning-50), var(--warning-100));
    border-bottom: 1px solid var(--warning-200);
}

.verify-modern-header.primary {
    background: linear-gradient(145deg, var(--primary-50), var(--primary-100));
    border-bottom: 1px solid var(--primary-200);
}

/* Subtle pattern overlay */
.verify-modern-header::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle at 30% 50%, rgba(0,0,0,0.02) 0%, transparent 50%);
    opacity: 0.4;
    z-index: -1;
}

/* Header Icon - Smaller and more elegant */
.verify-modern-icon {
    width: 72px;
    height: 72px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-4);
    font-size: 2rem;
    color: var(--neutral-700);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
}

.verify-modern-header.success .verify-modern-icon {
    color: var(--success-600);
}

.verify-modern-header.danger .verify-modern-icon {
    color: var(--error-600);
}

.verify-modern-header.info .verify-modern-icon {
    color: var(--info-600);
}

.verify-modern-header.warning .verify-modern-icon {
    color: var(--warning-600);
}

.verify-modern-header.primary .verify-modern-icon {
    color: var(--primary-600);
}

.verify-modern-header h2 {
    font-size: clamp(1.5rem, 4vw, 2rem);
    font-weight: 600;
    color: var(--neutral-800);
    margin: 0 0 var(--space-2);
    letter-spacing: -0.02em;
}

.verify-modern-header p {
    font-size: clamp(0.875rem, 2vw, 1rem);
    color: var(--neutral-600);
    margin: 0;
    font-weight: 400;
}

/* Body Styles - More breathing room */
.verify-modern-body {
    padding: var(--space-8);
}

@media (max-width: 640px) {
    .verify-modern-body {
        padding: var(--space-6);
    }
}

/* Greeting Section */
.verify-modern-greeting {
    text-align: center;
    margin-bottom: var(--space-6);
    position: relative;
}

.greeting-icon {
    width: 56px;
    height: 56px;
    background: var(--neutral-100);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-3);
    font-size: 1.25rem;
    color: var(--neutral-500);
    border: 1px solid var(--border-light);
}

.verify-modern-greeting h4 {
    font-size: clamp(1.125rem, 3vw, 1.25rem);
    font-weight: 600;
    color: var(--neutral-800);
    margin: 0 0 var(--space-1);
}

.verify-modern-greeting p {
    font-size: 0.95rem;
    color: var(--neutral-600);
    line-height: 1.6;
    margin: 0;
}

/* Alert Banners - Subtle and professional */
.alert-modern {
    display: flex;
    align-items: flex-start;
    gap: var(--space-3);
    padding: var(--space-4) var(--space-5);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-5);
    animation: slideInRight 0.3s ease-out;
    border: 1px solid transparent;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alert-modern i {
    font-size: 1.125rem;
    flex-shrink: 0;
}

.alert-modern span {
    flex: 1;
    font-size: 0.95rem;
    line-height: 1.5;
}

.alert-modern.success {
    background: var(--success-50);
    border-color: var(--success-200);
    color: var(--success-700);
}

.alert-modern.success i {
    color: var(--success-600);
}

.alert-modern.danger {
    background: var(--error-50);
    border-color: var(--error-200);
    color: var(--error-700);
}

.alert-modern.danger i {
    color: var(--error-600);
}

.alert-modern.info {
    background: var(--info-50);
    border-color: var(--info-200);
    color: var(--info-700);
}

.alert-modern.info i {
    color: var(--info-600);
}

.alert-modern.warning {
    background: var(--warning-50);
    border-color: var(--warning-200);
    color: var(--warning-700);
}

.alert-modern.warning i {
    color: var(--warning-600);
}

/* Email Chip */
.email-chip-modern {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    background: var(--neutral-100);
    border: 1px solid var(--border-light);
    border-radius: 100px;
    padding: var(--space-2) var(--space-4);
    margin: var(--space-3) 0;
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--neutral-700);
    box-shadow: var(--shadow-sm);
    max-width: 100%;
    word-break: break-all;
}

.email-chip-modern i {
    color: var(--primary-500);
    font-size: 0.9rem;
}

/* Reasons List - Clean and organized */
.reasons-modern {
    background: var(--neutral-50);
    border-radius: var(--radius-lg);
    padding: var(--space-5);
    margin-bottom: var(--space-5);
    border: 1px solid var(--border-light);
}

.reasons-modern h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-700);
    margin: 0 0 var(--space-3);
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.reasons-modern h5 i {
    color: var(--warning-500);
}

.reasons-modern ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

.reasons-modern li {
    display: flex;
    align-items: flex-start;
    gap: var(--space-2);
    font-size: 0.95rem;
    color: var(--neutral-600);
    line-height: 1.5;
}

.reason-bullet {
    width: 18px;
    height: 18px;
    background: var(--error-50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}

.reason-bullet i {
    font-size: 0.6rem;
    color: var(--error-500);
}

/* Tips Block */
.tips-modern {
    background: var(--neutral-50);
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--border-light);
    margin-bottom: var(--space-5);
}

.tips-header {
    background: var(--neutral-100);
    padding: var(--space-3) var(--space-5);
    display: flex;
    align-items: center;
    gap: var(--space-2);
    border-bottom: 1px solid var(--border-light);
}

.tips-header i {
    color: var(--warning-500);
    font-size: 1rem;
}

.tips-header span {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--neutral-600);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.tips-list {
    padding: var(--space-4) var(--space-5);
    margin: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
}

.tips-list li {
    display: flex;
    align-items: flex-start;
    gap: var(--space-2);
    font-size: 0.95rem;
    color: var(--neutral-600);
    line-height: 1.5;
}

.tips-list li i {
    width: 16px;
    font-size: 0.85rem;
    margin-top: 3px;
    flex-shrink: 0;
}

.tips-list li i.fa-folder { color: var(--warning-600); }
.tips-list li i.fa-clock { color: var(--info-600); }
.tips-list li i.fa-at { color: var(--success-600); }

/* Resend Block */
.resend-modern {
    background: white;
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    padding: var(--space-5);
    margin-bottom: var(--space-5);
    text-align: center;
    box-shadow: var(--shadow-sm);
}

.resend-modern p {
    font-size: 0.95rem;
    color: var(--neutral-600);
    margin-bottom: var(--space-3);
}

.resend-email-display {
    background: var(--neutral-100);
    border-radius: var(--radius-md);
    padding: var(--space-3);
    margin-top: var(--space-3);
    font-size: 0.9rem;
    color: var(--neutral-600);
    word-break: break-all;
    border: 1px solid var(--border-light);
}

.resend-email-display i {
    color: var(--primary-500);
    margin-right: var(--space-2);
}

/* Buttons - Professional and consistent */
.btn-modern-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    width: 100%;
    padding: var(--space-3) var(--space-6);
    background: var(--primary-600);
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
    margin-bottom: var(--space-3);
    cursor: pointer;
}

.btn-modern-primary:hover {
    background: var(--primary-700);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
    color: white;
}

.btn-modern-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    width: 100%;
    padding: var(--space-3) var(--space-6);
    background: transparent;
    color: var(--neutral-700);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    margin-bottom: var(--space-3);
    cursor: pointer;
}

.btn-modern-outline:hover {
    background: var(--neutral-50);
    border-color: var(--neutral-400);
    color: var(--neutral-900);
    transform: translateY(-1px);
}

.btn-modern-ghost {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-1);
    padding: var(--space-2) var(--space-4);
    background: transparent;
    color: var(--neutral-500);
    border: none;
    border-radius: var(--radius-md);
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-modern-ghost:hover {
    background: var(--neutral-100);
    color: var(--neutral-700);
}

/* Divider */
.divider-modern {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    margin: var(--space-5) 0;
    color: var(--neutral-400);
    font-size: 0.85rem;
}

.divider-modern::before,
.divider-modern::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border), transparent);
}

/* Action Group */
.action-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--space-2);
    margin: var(--space-5) 0;
}

/* Support Footer - Integrated with layout */
.support-modern {
    background: var(--neutral-50);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-full);
    padding: var(--space-3) var(--space-6);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-3);
    margin-top: var(--space-5);
    font-size: 0.9rem;
    color: var(--neutral-600);
    flex-wrap: wrap;
}

.support-modern i {
    color: var(--primary-500);
    font-size: 1rem;
}

.support-modern a {
    color: var(--neutral-700);
    font-weight: 500;
    text-decoration: none;
    transition: color 0.2s ease;
    border-bottom: 1px dotted transparent;
}

.support-modern a:hover {
    color: var(--primary-600);
    border-bottom-color: var(--primary-200);
}

/* Loading Spinner */
.spinner-modern {
    display: inline-block;
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive Breakpoints - Optimized for all screens */
@media (max-width: 768px) {
    .verify-modern-wrap {
        max-width: 600px;
        padding: 0 var(--space-3);
    }
    
    .verify-modern-header {
        padding: var(--space-6) var(--space-5);
    }
    
    .verify-modern-icon {
        width: 64px;
        height: 64px;
        font-size: 1.75rem;
    }
    
    .support-modern {
        border-radius: var(--radius-lg);
        flex-direction: row;
        flex-wrap: wrap;
        gap: var(--space-2);
    }
}

@media (max-width: 640px) {
    .verify-modern-wrap {
        max-width: 100%;
    }
    
    .verify-modern-body {
        padding: var(--space-5);
    }
    
    .verify-modern-header h2 {
        font-size: 1.5rem;
    }
    
    .verify-modern-header p {
        font-size: 0.875rem;
    }
    
    .support-modern {
        flex-direction: column;
        text-align: center;
        gap: var(--space-2);
        padding: var(--space-4);
    }
    
    .action-group {
        flex-direction: column;
    }
    
    .btn-modern-primary,
    .btn-modern-outline {
        padding: var(--space-3) var(--space-4);
    }
}

@media (max-width: 480px) {
    .verify-modern-icon {
        width: 56px;
        height: 56px;
        font-size: 1.5rem;
    }
    
    .verify-modern-header {
        padding: var(--space-5) var(--space-4);
    }
    
    .email-chip-modern {
        font-size: 0.85rem;
        padding: var(--space-2) var(--space-3);
    }
    
    .reasons-modern,
    .tips-modern,
    .resend-modern {
        padding: var(--space-4);
    }
    
    .alert-modern {
        padding: var(--space-3) var(--space-4);
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
            <h2>Email Verified</h2>
            <p>Your email has been successfully verified</p>
        </div>
        
        <div class="verify-modern-body">
            <div class="verify-modern-greeting">
                <div class="greeting-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h4>Welcome, <?php echo htmlspecialchars($applicant_name ?? 'Applicant'); ?>!</h4>
                <p>Your account is now active and ready for the next steps.</p>
            </div>

            <div class="alert-modern success">
                <i class="fas fa-check-circle"></i>
                <span>Your email has been verified. Proceed to verify your JAMB number.</span>
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
            <p>Unable to verify your email address</p>
        </div>
        
        <div class="verify-modern-body">
            <div class="alert-modern danger">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>

            <div class="reasons-modern">
                <h5>
                    <i class="fas fa-search"></i>
                    Why this happened
                </h5>
                <ul>
                    <li>
                        <span class="reason-bullet"><i class="fas fa-times"></i></span>
                        The verification link has expired (valid for 24 hours)
                    </li>
                    <li>
                        <span class="reason-bullet"><i class="fas fa-times"></i></span>
                        The link has already been used
                    </li>
                    <li>
                        <span class="reason-bullet"><i class="fas fa-times"></i></span>
                        The verification token is invalid
                    </li>
                </ul>
            </div>

            <?php if (isset($resend_email) && $resend_email): ?>
            <div class="resend-modern">
                <p>Need a new verification link?</p>
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
            <p>Your email has already been confirmed</p>
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
            <p>Verification email has been sent</p>
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
                <span><strong>Link expires in 24 hours.</strong> Please verify your email before then.</span>
            </div>

            <div class="resend-modern">
                <p>Didn't receive the email?</p>
                <a href="/apply/resend-verification?email=<?php echo urlencode($email ?? ''); ?>"
                   class="btn-modern-outline" style="margin-bottom: 0;">
                    <i class="fas fa-redo-alt"></i> Resend Email
                </a>
            </div>

            <div class="tips-modern">
                <div class="tips-header">
                    <i class="fas fa-lightbulb"></i>
                    <span>Quick tips</span>
                </div>
                <ul class="tips-list">
                    <li>
                        <i class="fas fa-folder"></i>
                        Check your <strong>spam or junk folder</strong>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        Wait a few minutes — delivery can take time
                    </li>
                    <li>
                        <i class="fas fa-at"></i>
                        Make sure you entered the <strong>correct email</strong>
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
            <p>An unexpected error occurred</p>
        </div>
        
        <div class="verify-modern-body">
            <div class="alert-modern warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Please try again or contact support if the problem persists.</span>
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

    <!-- Support Footer -->
    <div class="support-modern">
        <i class="fas fa-headset"></i>
        <span>Need assistance?</span>
        <a href="mailto:support@fctcns.edu.ng">support@fctcns.edu.ng</a>
        <span class="separator">•</span>
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
                const icon = this.querySelector('i') ? this.querySelector('i').outerHTML : '';
                this.innerHTML = '<span class="spinner-modern" style="margin-right: 0.5rem;"></span> Loading...';
                this.style.pointerEvents = 'none';
                
                // Restore after 10 seconds if stuck (safety)
                setTimeout(() => {
                    if (this.innerHTML.includes('Loading')) {
                        this.innerHTML = originalText;
                        this.style.pointerEvents = 'auto';
                    }
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