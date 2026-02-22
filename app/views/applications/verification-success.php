<?php
/**
 * Verification Success View - Professional Design
 * Shown after email verification with subtle, sophisticated color scheme
 * Wider cards for better content display
 * FIXED: Security enhancements with SecurityTrait and CSP nonce
 * 
 * @package FCTCNS
 * @version 2.0 - Security Enhanced
 */

// =========================================================
// 1. Add required helpers at the top of each view file
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class VerificationSuccessView {
    use SecurityTrait;
    
    public function render($data) {
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        // Extract data
        extract($data ?? []);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            
            <!-- ========================================================= -->
            <!-- 2. Add security meta tags in the head -->
            <!-- ========================================================= -->
            <?php echo $this->getSecurityMetaTags(); ?>
            
            <!-- ========================================================= -->
            <!-- 3. Add CSRF meta tag for JavaScript -->
            <!-- ========================================================= -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">
            
            <title>Verification Successful - FCT College of Nursing Sciences</title>
            
            <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            
            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all style tags -->
            <!-- 5. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" 
                  rel="stylesheet"
                  integrity="sha384-0pCryB3hBqYHZO9dKsIIzN8wH+Z4k5P+GZ8TlqM9m8A3TlPI9c7JZ6nG+K/t9fb"
                  crossorigin="anonymous">
            
            <link rel="stylesheet" 
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                  integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
            /* ==========================================================================
               PROFESSIONAL DESIGN SYSTEM - SUBTLE & SOPHISTICATED
               Wider cards for verification success
               ========================================================================== */

            :root {
                /* Success - Muted Green (sophisticated, not shouty) */
                --success-50: #ecfdf5;
                --success-100: #d1fae5;
                --success-200: #a7f3d0;
                --success-300: #6ee7b7;
                --success-400: #34d399;
                --success-500: #10b981;
                --success-600: #059669;
                --success-700: #047857;
                
                /* Primary - Soft Lavender (minimal accent) */
                --primary-50: #f5f3ff;
                --primary-100: #ede9fe;
                --primary-200: #ddd6fe;
                --primary-300: #c4b5fd;
                --primary-400: #a78bfa;
                --primary-500: #8b5cf6;
                --primary-600: #7c3aed;
                
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
                
                /* Text colors */
                --text-primary: var(--neutral-900);
                --text-secondary: var(--neutral-700);
                --text-tertiary: var(--neutral-500);
                --text-inverse: #ffffff;
                
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
            .verify-success-wrap {
                max-width: 720px; /* Increased from 640px for wider cards */
                width: 100%;
                margin: 0 auto; /* Remove vertical margin - handled by layout */
                font-family: var(--font-sans);
                padding: 0 var(--space-4); /* Responsive padding */
            }

            /* Main Card Design - Wider and more elegant */
            .verify-success-card {
                background: #ffffff;
                border-radius: var(--radius-2xl);
                overflow: hidden;
                box-shadow: var(--shadow-lg);
                border: 1px solid var(--border-light);
                animation: slideUp 0.5s ease-out;
                width: 100%;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Success Header - Subtle Green Gradient */
            .verify-success-header {
                background: linear-gradient(145deg, var(--success-50), var(--success-100));
                color: var(--text-primary);
                padding: var(--space-10) var(--space-8);
                text-align: center;
                position: relative;
                isolation: isolate;
                border-bottom: 1px solid var(--success-200);
            }

            .verify-success-header::before {
                content: '';
                position: absolute;
                inset: 0;
                background: radial-gradient(circle at 30% 30%, rgba(16, 185, 129, 0.05) 0%, transparent 70%);
                z-index: -1;
            }

            .verify-success-header::after {
                content: '';
                position: absolute;
                inset: 0;
                background-image: radial-gradient(circle at 70% 50%, rgba(0,0,0,0.02) 0%, transparent 50%);
                opacity: 0.5;
                z-index: -1;
            }

            /* Success Icon - Smaller and more elegant */
            .verify-success-icon {
                width: 80px;
                height: 80px;
                background: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto var(--space-4);
                font-size: 2.5rem;
                color: var(--success-600);
                box-shadow: var(--shadow-md);
                border: 1px solid var(--success-200);
            }

            .verify-success-header h2 {
                font-size: clamp(1.8rem, 4vw, 2.2rem);
                font-weight: 600;
                color: var(--text-primary);
                margin-bottom: var(--space-2);
                letter-spacing: -0.02em;
            }

            .verify-success-header p {
                font-size: clamp(0.95rem, 2vw, 1rem);
                color: var(--text-secondary);
                font-weight: 400;
            }

            /* Body Content */
            .verify-success-body {
                padding: var(--space-8);
            }

            @media (max-width: 640px) {
                .verify-success-body {
                    padding: var(--space-6);
                }
            }

            /* Welcome Section */
            .welcome-message {
                text-align: center;
                margin-bottom: var(--space-6);
                position: relative;
            }

            .welcome-message::after {
                content: '';
                position: absolute;
                bottom: -1rem;
                left: 50%;
                transform: translateX(-50%);
                width: 60px;
                height: 3px;
                background: linear-gradient(90deg, transparent, var(--success-400), transparent);
                border-radius: 3px;
            }

            .welcome-message h3 {
                color: var(--text-primary);
                font-size: clamp(1.3rem, 3vw, 1.5rem);
                font-weight: 600;
                margin-bottom: var(--space-2);
                letter-spacing: -0.01em;
            }

            .welcome-message p {
                color: var(--text-tertiary);
                font-size: 1rem;
                line-height: 1.6;
            }

            /* Account Details Card - Subtle Design */
            .account-details {
                background: var(--neutral-50);
                border-radius: var(--radius-xl);
                padding: var(--space-6);
                margin-bottom: var(--space-6);
                border: 1px solid var(--border-light);
                position: relative;
                overflow: hidden;
            }

            .account-details::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 120px;
                height: 120px;
                background: var(--success-500);
                opacity: 0.02;
                border-radius: 50%;
                transform: translate(30%, -30%);
            }

            .account-details h4 {
                color: var(--text-primary);
                font-size: 1.1rem;
                font-weight: 600;
                margin-bottom: var(--space-4);
                display: flex;
                align-items: center;
                gap: var(--space-2);
            }

            .account-details h4 i {
                color: var(--success-600);
                font-size: 1.2rem;
            }

            .detail-grid {
                display: grid;
                gap: var(--space-3);
            }

            .detail-item {
                display: flex;
                align-items: center;
                padding: var(--space-3) var(--space-4);
                background: white;
                border-radius: var(--radius-lg);
                box-shadow: var(--shadow-sm);
                border: 1px solid var(--border-light);
            }

            .detail-icon {
                width: 40px;
                height: 40px;
                background: var(--success-50);
                border-radius: var(--radius-md);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--success-600);
                margin-right: var(--space-3);
                flex-shrink: 0;
            }

            .detail-content {
                flex: 1;
            }

            .detail-label {
                color: var(--text-tertiary);
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 0.03em;
                margin-bottom: 0.15rem;
            }

            .detail-value {
                color: var(--text-primary);
                font-weight: 500;
                font-size: 1rem;
            }

            .email-chip {
                background: var(--success-50);
                color: var(--success-700);
                padding: var(--space-2) var(--space-3);
                border-radius: var(--radius-full);
                font-size: 0.9rem;
                border: 1px solid var(--success-200);
                display: inline-flex;
                align-items: center;
                gap: var(--space-2);
                max-width: 100%;
                word-break: break-all;
            }

            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: var(--space-2);
                padding: var(--space-2) var(--space-3);
                background: var(--success-50);
                color: var(--success-700);
                border-radius: var(--radius-full);
                font-weight: 500;
                font-size: 0.9rem;
                border: 1px solid var(--success-200);
            }

            /* Next Steps Section */
            .next-steps {
                margin-bottom: var(--space-6);
            }

            .next-steps h4 {
                color: var(--text-primary);
                font-size: 1.1rem;
                font-weight: 600;
                margin-bottom: var(--space-4);
                display: flex;
                align-items: center;
                gap: var(--space-2);
            }

            .next-steps h4 i {
                color: var(--success-600);
            }

            .steps-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: var(--space-3);
            }

            @media (max-width: 480px) {
                .steps-grid {
                    grid-template-columns: 1fr;
                }
            }

            .step-card {
                background: white;
                border: 1px solid var(--border-light);
                border-radius: var(--radius-lg);
                padding: var(--space-4);
                text-align: center;
                transition: all 0.2s ease;
                position: relative;
                overflow: hidden;
            }

            .step-card:hover {
                border-color: var(--success-300);
                box-shadow: var(--shadow-md);
                transform: translateY(-2px);
            }

            .step-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: linear-gradient(90deg, var(--success-400), var(--success-600));
                transform: scaleX(0);
                transition: transform 0.2s ease;
            }

            .step-card:hover::before {
                transform: scaleX(1);
            }

            .step-number {
                width: 36px;
                height: 36px;
                background: var(--success-100);
                color: var(--success-700);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto var(--space-3);
                font-weight: 600;
                font-size: 1rem;
                transition: all 0.2s ease;
            }

            .step-card:hover .step-number {
                background: var(--success-600);
                color: white;
            }

            .step-card h5 {
                color: var(--text-primary);
                font-size: 0.95rem;
                font-weight: 600;
                margin-bottom: var(--space-1);
            }

            .step-card p {
                color: var(--text-tertiary);
                font-size: 0.8rem;
                margin-bottom: 0;
                line-height: 1.4;
            }

            /* Success Alert */
            .alert-success-custom {
                background: var(--success-50);
                border: 1px solid var(--success-200);
                border-radius: var(--radius-lg);
                padding: var(--space-4) var(--space-5);
                margin-bottom: var(--space-6);
                display: flex;
                align-items: flex-start;
                gap: var(--space-3);
                animation: slideInRight 0.4s ease-out;
            }

            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(15px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .alert-success-custom i {
                color: var(--success-600);
                font-size: 1.25rem;
                flex-shrink: 0;
            }

            .alert-content {
                flex: 1;
            }

            .alert-content strong {
                color: var(--success-700);
                display: block;
                margin-bottom: var(--space-1);
                font-size: 0.95rem;
            }

            .alert-content p {
                color: var(--text-secondary);
                margin-bottom: 0;
                font-size: 0.9rem;
                line-height: 1.5;
            }

            /* Modern Button */
            .btn-success-modern {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: var(--space-3);
                width: 100%;
                padding: var(--space-4) var(--space-6);
                background: var(--success-600);
                color: white;
                border: none;
                border-radius: var(--radius-lg);
                font-size: 1rem;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.2s ease;
                box-shadow: var(--shadow-md);
                position: relative;
                overflow: hidden;
                cursor: pointer;
            }

            .btn-success-modern:hover {
                background: var(--success-700);
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
                color: white;
            }

            .btn-success-modern i {
                transition: transform 0.2s ease;
                font-size: 1rem;
            }

            .btn-success-modern:hover i {
                transform: translateX(5px);
            }

            /* Secondary Links */
            .login-link {
                text-align: center;
                margin: var(--space-4) 0;
            }

            .login-link a {
                color: var(--text-tertiary);
                text-decoration: none;
                font-size: 0.9rem;
                transition: color 0.2s ease;
                display: inline-flex;
                align-items: center;
                gap: var(--space-2);
            }

            .login-link a:hover {
                color: var(--success-600);
            }

            .login-link a i {
                font-size: 0.9rem;
            }

            /* Support Footer */
            .support-footer {
                background: var(--neutral-50);
                border: 1px solid var(--border-light);
                border-radius: var(--radius-full);
                padding: var(--space-3) var(--space-6);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: var(--space-3);
                color: var(--text-secondary);
                font-size: 0.9rem;
                margin-top: var(--space-5);
                flex-wrap: wrap;
            }

            .support-footer i {
                color: var(--success-600);
                font-size: 1rem;
            }

            .support-footer a {
                color: var(--text-primary);
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
                border-bottom: 1px dotted transparent;
            }

            .support-footer a:hover {
                color: var(--success-600);
                border-bottom-color: var(--success-200);
            }

            /* Loading Spinner */
            .spinner {
                width: 18px;
                height: 18px;
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                border-top-color: white;
                animation: spin 0.6s linear infinite;
                display: inline-block;
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            /* Separator */
            .separator {
                color: var(--neutral-400);
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .verify-success-wrap {
                    max-width: 600px;
                }
                
                .verify-success-header {
                    padding: var(--space-8) var(--space-6);
                }
                
                .verify-success-icon {
                    width: 70px;
                    height: 70px;
                    font-size: 2rem;
                }
                
                .support-footer {
                    border-radius: var(--radius-lg);
                    flex-wrap: wrap;
                    gap: var(--space-2);
                }
            }

            @media (max-width: 640px) {
                .verify-success-header h2 {
                    font-size: 1.8rem;
                }
                
                .verify-success-body {
                    padding: var(--space-5);
                }
                
                .account-details {
                    padding: var(--space-5);
                }
                
                .detail-item {
                    flex-wrap: wrap;
                }
                
                .alert-success-custom {
                    flex-direction: column;
                    text-align: center;
                }
                
                .support-footer {
                    flex-direction: column;
                    text-align: center;
                    border-radius: var(--radius-lg);
                    gap: var(--space-2);
                }
                
                .separator {
                    display: none;
                }
            }

            @media (max-width: 480px) {
                .verify-success-wrap {
                    padding: 0 var(--space-3);
                }
                
                .verify-success-header {
                    padding: var(--space-6) var(--space-4);
                }
                
                .verify-success-icon {
                    width: 60px;
                    height: 60px;
                    font-size: 1.75rem;
                }
                
                .verify-success-header h2 {
                    font-size: 1.5rem;
                }
                
                .detail-icon {
                    width: 36px;
                    height: 36px;
                    font-size: 0.9rem;
                }
                
                .email-chip {
                    font-size: 0.85rem;
                    padding: var(--space-2);
                }
                
                .btn-success-modern {
                    padding: var(--space-3) var(--space-4);
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
        </head>
        <body>
            <div class="verify-success-wrap">
                <div class="verify-success-card">
                    <!-- Header with Subtle Green Gradient -->
                    <div class="verify-success-header">
                        <div class="verify-success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2>Verification Successful</h2>
                        <p>Your email has been verified</p>
                    </div>
                    
                    <div class="verify-success-body">
                        <!-- Welcome Message -->
                        <div class="welcome-message">
                            <h3>Welcome, <?php echo $this->e($applicant_name ?? 'Applicant'); ?>!</h3>
                            <p>Your account is now active and ready for application</p>
                        </div>
                        
                        <!-- Account Details - Clean Card -->
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
                                                <?php echo $this->e($email ?? $applicant['email'] ?? 'Verified'); ?>
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
                                            <span class="status-badge">
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
                            <span>Need assistance?</span>
                            <a href="tel:07039837749" rel="noopener noreferrer">0703 983 7749</a>
                            <span class="separator">•</span>
                            <a href="mailto:support@fctcns.edu.ng" rel="noopener noreferrer">Email Support</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- 6. Add CSP nonce to all script tags -->
            <!-- ========================================================= -->
            <script nonce="<?php echo $csp_nonce; ?>">
            // ======================================================
            // Verification Success JavaScript with Security Enhancements
            // ======================================================

            // Get CSRF token from meta tag
            function getCsrfToken() {
                const meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            }

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
                        
                        // Optional: Track click with CSRF protection
                        if (getCsrfToken()) {
                            fetch('/api/track-redirect', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': getCsrfToken()
                                },
                                body: JSON.stringify({
                                    action: 'verification_success_click',
                                    timestamp: Date.now()
                                })
                            }).catch(() => {}); // Silent fail
                        }
                    });
                }
                
                // Optional countdown for better UX (disabled if button already clicked)
                let seconds = 5;
                const btn = document.getElementById('continueBtn');
                const originalText = btn ? btn.querySelector('span:first-child').textContent : '';
                
                if (btn) {
                    const timer = setInterval(function() {
                        seconds--;
                        if (seconds > 0 && seconds < 5 && btn.style.pointerEvents !== 'none') {
                            btn.querySelector('span:first-child').textContent = `Continue (${seconds}s)`;
                        } else if (seconds <= 0) {
                            clearInterval(timer);
                            if (btn.style.pointerEvents !== 'none') {
                                btn.querySelector('span:first-child').textContent = originalText;
                            }
                        }
                    }, 1000);
                }

                // External link security
                document.querySelectorAll('a[href^="http"]:not([rel*="noopener"])').forEach(link => {
                    if (link.hostname !== window.location.hostname) {
                        link.setAttribute('target', '_blank');
                        link.setAttribute('rel', 'noopener noreferrer');
                    }
                });

                // Add security for tel and mailto links
                document.querySelectorAll('a[href^="tel:"], a[href^="mailto:"]').forEach(link => {
                    link.addEventListener('click', function(e) {
                        // No security concerns, but we can track if needed
                        console.log('Contact link clicked:', this.href);
                    });
                });
            });
            </script>
        </body>
        </html>
        <?php
    }
}

// =========================================================
// 7. Add the view instantiation at the bottom
// =========================================================
$view = new VerificationSuccessView();
$view->render(get_defined_vars());
?>