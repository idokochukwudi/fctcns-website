<?php
/**
 * JAMB Verification Failed View - Professional Design
 * Shown when JAMB verification fails with subtle, sophisticated error styling
 * Wider cards for better content display
 */
?>

<style>
/* ==========================================================================
   PROFESSIONAL DESIGN SYSTEM - SUBTLE ERROR STATES
   Wider cards for verification failed
   ========================================================================== */

:root {
    /* Error - Muted Rose (sophisticated, not shouty) */
    --error-50: #fff1f2;
    --error-100: #ffe4e6;
    --error-200: #fecdd3;
    --error-300: #fda4af;
    --error-400: #fb7185;
    --error-500: #f43f5e;
    --error-600: #e11d48;
    --error-700: #be123c;
    
    /* Warning - Muted Amber */
    --warning-50: #fffbeb;
    --warning-100: #fef3c7;
    --warning-200: #fde68a;
    --warning-300: #fcd34d;
    --warning-400: #fbbf24;
    --warning-500: #f59e0b;
    --warning-600: #d97706;
    
    /* Primary - Soft Lavender (minimal accent) */
    --primary-50: #f5f3ff;
    --primary-100: #ede9fe;
    --primary-200: #ddd6fe;
    --primary-300: #c4b5fd;
    --primary-400: #a78bfa;
    
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
.verify-failed-wrap {
    max-width: 720px; /* Increased from 640px for wider cards */
    width: 100%;
    margin: 0 auto; /* Remove vertical margin - handled by layout */
    font-family: var(--font-sans);
    padding: 0 var(--space-4); /* Responsive padding */
}

/* Main Card Design - Wider and more elegant */
.verify-failed-card {
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

/* Error Header - Subtle Rose Gradient */
.verify-failed-header {
    background: linear-gradient(145deg, var(--error-50), var(--error-100));
    color: var(--text-primary);
    padding: var(--space-10) var(--space-8);
    text-align: center;
    position: relative;
    isolation: isolate;
    border-bottom: 1px solid var(--error-200);
}

.verify-failed-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 30% 30%, rgba(244, 63, 94, 0.05) 0%, transparent 70%);
    z-index: -1;
}

.verify-failed-header::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle at 70% 50%, rgba(0,0,0,0.02) 0%, transparent 50%);
    opacity: 0.5;
    z-index: -1;
}

/* Error Icon - Smaller and more elegant */
.verify-failed-icon {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-4);
    font-size: 2.5rem;
    color: var(--error-600);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--error-200);
}

.verify-failed-header h2 {
    font-size: clamp(1.8rem, 4vw, 2.2rem);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: var(--space-2);
    letter-spacing: -0.02em;
}

.verify-failed-header p {
    font-size: clamp(0.95rem, 2vw, 1rem);
    color: var(--text-secondary);
    font-weight: 400;
}

/* Body Content */
.verify-failed-body {
    padding: var(--space-8);
}

@media (max-width: 640px) {
    .verify-failed-body {
        padding: var(--space-6);
    }
}

/* Error Message Section */
.error-message {
    text-align: center;
    margin-bottom: var(--space-6);
    position: relative;
}

.error-message::after {
    content: '';
    position: absolute;
    bottom: -1rem;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--error-400), transparent);
    border-radius: 3px;
}

.error-icon-circle {
    width: 72px;
    height: 72px;
    background: var(--error-50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-4);
    font-size: 2rem;
    color: var(--error-600);
    border: 1px solid var(--error-200);
}

.error-message h3 {
    color: var(--text-primary);
    font-size: clamp(1.3rem, 3vw, 1.5rem);
    font-weight: 600;
    margin-bottom: var(--space-3);
    letter-spacing: -0.01em;
}

.jamb-number-highlight {
    background: var(--error-50);
    color: var(--error-700);
    padding: var(--space-3) var(--space-5);
    border-radius: var(--radius-full);
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 1.1rem;
    font-weight: 500;
    display: inline-block;
    margin: var(--space-2) 0;
    border: 1px solid var(--error-200);
    box-shadow: var(--shadow-sm);
    word-break: break-all;
    max-width: 100%;
}

.error-message p {
    color: var(--text-tertiary);
    font-size: 1rem;
    line-height: 1.6;
    max-width: 90%;
    margin: var(--space-2) auto 0;
}

/* Reasons Card - Subtle Design */
.reasons-card {
    background: var(--neutral-50);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    margin-bottom: var(--space-6);
    border: 1px solid var(--border-light);
    position: relative;
    overflow: hidden;
}

.reasons-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    background: var(--error-500);
    opacity: 0.02;
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.reasons-header {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    margin-bottom: var(--space-4);
}

.reasons-header i {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--warning-600);
    font-size: 1.2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
}

.reasons-header h4 {
    color: var(--text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
}

.reasons-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
}

.reason-item {
    display: flex;
    align-items: flex-start;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
    transition: all 0.2s ease;
}

.reason-item:hover {
    border-color: var(--error-300);
    box-shadow: var(--shadow-md);
    transform: translateX(3px);
}

.reason-icon {
    width: 32px;
    height: 32px;
    background: var(--error-50);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--error-600);
    font-size: 0.9rem;
    flex-shrink: 0;
}

.reason-text {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.5;
    flex: 1;
}

.reason-text strong {
    color: var(--error-700);
    font-weight: 600;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-3);
    margin-bottom: var(--space-5);
}

@media (max-width: 480px) {
    .quick-actions {
        grid-template-columns: 1fr;
    }
}

.action-card {
    background: white;
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    padding: var(--space-4);
    text-align: center;
    transition: all 0.2s ease;
    text-decoration: none;
    color: inherit;
}

.action-card:hover {
    border-color: var(--error-400);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
    text-decoration: none;
}

.action-card i {
    font-size: 1.75rem;
    color: var(--error-600);
    margin-bottom: var(--space-2);
}

.action-card h5 {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: var(--space-1);
}

.action-card p {
    color: var(--text-tertiary);
    font-size: 0.8rem;
    margin-bottom: 0;
}

/* Main Action Button */
.btn-error-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-3);
    width: 100%;
    padding: var(--space-4) var(--space-6);
    background: var(--error-600);
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
    margin-bottom: var(--space-5);
    cursor: pointer;
}

.btn-error-modern:hover {
    background: var(--error-700);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: white;
}

.btn-error-modern i {
    transition: transform 0.2s ease;
    font-size: 1rem;
}

.btn-error-modern:hover i {
    transform: rotate(180deg);
}

/* Divider */
.custom-divider {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    margin: var(--space-5) 0;
    color: var(--text-tertiary);
    font-size: 0.85rem;
}

.custom-divider::before,
.custom-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border), transparent);
}

/* Support Section */
.support-section {
    background: var(--neutral-50);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    text-align: center;
    margin-top: var(--space-4);
}

.support-section h5 {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: var(--space-4);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
}

.support-section h5 i {
    color: var(--error-600);
}

.contact-grid {
    display: flex;
    justify-content: center;
    gap: var(--space-3);
    flex-wrap: wrap;
}

@media (max-width: 640px) {
    .contact-grid {
        flex-direction: column;
        align-items: center;
    }
}

.contact-item {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-2) var(--space-4);
    background: white;
    border-radius: var(--radius-full);
    border: 1px solid var(--border-light);
    transition: all 0.2s ease;
}

.contact-item:hover {
    border-color: var(--error-400);
    box-shadow: var(--shadow-sm);
    transform: translateY(-2px);
}

.contact-item i {
    width: 28px;
    height: 28px;
    background: var(--error-50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--error-600);
    font-size: 0.85rem;
}

.contact-item a {
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
}

.contact-item a:hover {
    color: var(--error-600);
}

.contact-item span {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.support-note {
    margin-top: var(--space-4);
    font-size: 0.8rem;
    color: var(--text-tertiary);
}

/* Loading Spinner */
.spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .verify-failed-wrap {
        max-width: 600px;
    }
    
    .verify-failed-header {
        padding: var(--space-8) var(--space-6);
    }
    
    .verify-failed-icon {
        width: 70px;
        height: 70px;
        font-size: 2rem;
    }
}

@media (max-width: 640px) {
    .verify-failed-header h2 {
        font-size: 1.8rem;
    }
    
    .verify-failed-body {
        padding: var(--space-5);
    }
    
    .error-icon-circle {
        width: 64px;
        height: 64px;
        font-size: 1.75rem;
    }
    
    .jamb-number-highlight {
        font-size: 1rem;
        padding: var(--space-2) var(--space-4);
    }
    
    .error-message p {
        max-width: 100%;
    }
    
    .reasons-card {
        padding: var(--space-5);
    }
}

@media (max-width: 480px) {
    .verify-failed-wrap {
        padding: 0 var(--space-3);
    }
    
    .verify-failed-header {
        padding: var(--space-6) var(--space-4);
    }
    
    .verify-failed-icon {
        width: 60px;
        height: 60px;
        font-size: 1.75rem;
    }
    
    .verify-failed-header h2 {
        font-size: 1.5rem;
    }
    
    .reason-item {
        padding: var(--space-2) var(--space-3);
    }
    
    .reason-icon {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    .reason-text {
        font-size: 0.85rem;
    }
    
    .btn-error-modern {
        padding: var(--space-3) var(--space-4);
    }
    
    .contact-item {
        width: 100%;
        justify-content: center;
    }
}

/* Print Styles */
@media print {
    .verify-failed-card {
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .btn-error-modern,
    .quick-actions,
    .support-section {
        display: none;
    }
}
</style>

<div class="verify-failed-wrap">
    <div class="verify-failed-card">
        <!-- Header with Subtle Error Gradient -->
        <div class="verify-failed-header">
            <div class="verify-failed-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2>Verification Failed</h2>
            <p>JAMB record not found</p>
        </div>
        
        <div class="verify-failed-body">
            <!-- Error Message -->
            <div class="error-message">
                <div class="error-icon-circle">
                    <i class="fas fa-search"></i>
                </div>
                <h3>JAMB Number Not Found</h3>
                <div class="jamb-number-highlight">
                    <?php echo htmlspecialchars($jamb_number ?? 'Unknown'); ?>
                </div>
                <p>We couldn't find this JAMB number in our records</p>
            </div>
            
            <!-- Reasons Card -->
            <div class="reasons-card">
                <div class="reasons-header">
                    <i class="fas fa-lightbulb"></i>
                    <h4>Why this happened</h4>
                </div>
                
                <ul class="reasons-list">
                    <li class="reason-item">
                        <span class="reason-icon">
                            <i class="fas fa-keyboard"></i>
                        </span>
                        <span class="reason-text">
                            <strong>Incorrect entry:</strong> Double-check the JAMB number you entered
                        </span>
                    </li>
                    <li class="reason-item">
                        <span class="reason-icon">
                            <i class="fas fa-database"></i>
                        </span>
                        <span class="reason-text">
                            <strong>Record not uploaded:</strong> Your JAMB record may not be synced yet
                        </span>
                    </li>
                    <li class="reason-item">
                        <span class="reason-icon">
                            <i class="fas fa-university"></i>
                        </span>
                        <span class="reason-text">
                            <strong>Institution not selected:</strong> This must be your first choice
                        </span>
                    </li>
                    <li class="reason-item">
                        <span class="reason-icon">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <span class="reason-text">
                            <strong>Score requirement:</strong> Your UTME score may be below minimum
                        </span>
                    </li>
                </ul>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="/apply/step/1" class="action-card">
                    <i class="fas fa-redo-alt"></i>
                    <h5>Try Again</h5>
                    <p>Re-enter JAMB number</p>
                </a>
                
                <a href="/contact" class="action-card">
                    <i class="fas fa-headset"></i>
                    <h5>Get Help</h5>
                    <p>Contact support</p>
                </a>
            </div>
            
            <!-- Main Action Button -->
            <a href="/apply/step/1" class="btn-error-modern" id="retryBtn">
                <span>Retry Verification</span>
                <i class="fas fa-redo-alt" id="btnIcon"></i>
                <span class="spinner" style="display: none;" id="spinner"></span>
            </a>
            
            <!-- Divider -->
            <div class="custom-divider">
                <span>Need assistance?</span>
            </div>
            
            <!-- Support Section -->
            <div class="support-section">
                <h5>
                    <i class="fas fa-headset"></i>
                    Contact Support
                </h5>
                
                <div class="contact-grid">
                    <div class="contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <a href="tel:07039837749">0703 983 7749</a>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:support@fctcns.edu.ng">support@fctcns.edu.ng</a>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span>Mon-Fri, 8am-5pm</span>
                    </div>
                </div>
                
                <div class="support-note">
                    Our team typically responds within 24 hours
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const retryBtn = document.getElementById('retryBtn');
    const btnIcon = document.getElementById('btnIcon');
    const spinner = document.getElementById('spinner');
    
    if (retryBtn) {
        retryBtn.addEventListener('click', function(e) {
            // Disable button to prevent double clicking
            this.style.pointerEvents = 'none';
            btnIcon.style.display = 'none';
            spinner.style.display = 'inline-block';
            this.querySelector('span:first-child').textContent = 'Redirecting...';
        });
    }
    
    // Optional countdown for better UX (disabled if button already clicked)
    let seconds = 3;
    const btn = document.getElementById('retryBtn');
    if (btn) {
        const originalText = btn.querySelector('span:first-child').textContent;
        
        const timer = setInterval(function() {
            seconds--;
            if (seconds > 0 && seconds < 3 && btn.style.pointerEvents !== 'none') {
                btn.querySelector('span:first-child').textContent = `Retry (${seconds}s)`;
            } else if (seconds <= 0) {
                clearInterval(timer);
                if (btn.style.pointerEvents !== 'none') {
                    btn.querySelector('span:first-child').textContent = originalText;
                }
            }
        }, 1000);
    }
});
</script>