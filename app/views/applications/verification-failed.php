<?php
/**
 * JAMB Verification Failed View - International Standard Design
 * Shown when JAMB verification fails with modern error styling
 */
?>

<style>
/* Modern CSS Reset & Variables */
:root {
    --error-primary: #ef4444;
    --error-primary-dark: #dc2626;
    --error-primary-light: #fee2e2;
    --error-secondary: #f87171;
    --error-soft: #fef2f2;
    --error-gradient: linear-gradient(145deg, #ef4444, #dc2626);
    --error-gradient-soft: linear-gradient(145deg, #fef2f2, #fee2e2);
    --warning-primary: #f59e0b;
    --warning-light: #fef3c7;
    --text-dark: #1f2937;
    --text-body: #4b5563;
    --text-light: #9ca3af;
    --border-light: #e5e7eb;
    --shadow-sm: 0 4px 6px -1px rgba(239, 68, 68, 0.1), 0 2px 4px -1px rgba(239, 68, 68, 0.06);
    --shadow-md: 0 20px 25px -5px rgba(239, 68, 68, 0.1), 0 10px 10px -5px rgba(239, 68, 68, 0.04);
    --shadow-lg: 0 25px 50px -12px rgba(239, 68, 68, 0.25);
    --font-sans: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
}

.verify-failed-wrap {
    max-width: 640px;
    margin: 3rem auto;
    font-family: var(--font-sans);
}

/* Main Card Design */
.verify-failed-card {
    background: #ffffff;
    border-radius: 32px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(239, 68, 68, 0.1);
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

/* Error Header with Red Theme */
.verify-failed-header {
    background: var(--error-gradient);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
    position: relative;
    isolation: isolate;
}

.verify-failed-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
    z-index: -1;
}

.verify-failed-header::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 5L35 20L20 35L5 20L20 5Z' fill='rgba(255,255,255,0.03)'/%3E%3C/svg%3E") repeat;
    opacity: 0.3;
    z-index: -1;
}

/* Error Icon Animation */
.verify-failed-icon {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 3px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 3rem;
    color: #fff;
    position: relative;
    animation: warningPulse 2s infinite;
}

@keyframes warningPulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.5);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 30px 15px rgba(255, 255, 255, 0.2);
    }
}

.verify-failed-header h2 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    letter-spacing: -0.02em;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.verify-failed-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 400;
}

/* Body Content */
.verify-failed-body {
    padding: 2.5rem;
}

/* Error Message Section */
.error-message {
    text-align: center;
    margin-bottom: 2.5rem;
    position: relative;
}

.error-message::after {
    content: '';
    position: absolute;
    bottom: -1.25rem;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: var(--error-gradient);
    border-radius: 2px;
}

.error-icon-circle {
    width: 80px;
    height: 80px;
    background: var(--error-soft);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
    color: var(--error-primary);
    border: 2px solid rgba(239, 68, 68, 0.1);
}

.error-message h3 {
    color: var(--text-dark);
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
}

.jamb-number-highlight {
    background: var(--error-soft);
    color: var(--error-primary-dark);
    padding: 0.75rem 1.5rem;
    border-radius: 100px;
    font-family: 'Courier New', monospace;
    font-size: 1.2rem;
    font-weight: 600;
    display: inline-block;
    margin: 1rem 0;
    border: 1px solid rgba(239, 68, 68, 0.2);
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.05);
}

.error-message p {
    color: var(--text-body);
    font-size: 1.1rem;
    line-height: 1.6;
    max-width: 80%;
    margin: 0 auto;
}

/* Reasons Card */
.reasons-card {
    background: var(--error-soft);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2.5rem;
    border: 1px solid rgba(239, 68, 68, 0.2);
    position: relative;
    overflow: hidden;
}

.reasons-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: var(--error-gradient);
    opacity: 0.03;
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.reasons-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.reasons-header i {
    width: 48px;
    height: 48px;
    background: white;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--warning-primary);
    font-size: 1.5rem;
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.1);
}

.reasons-header h4 {
    color: var(--text-dark);
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0;
}

.reasons-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.reason-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.75rem 1rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    transition: all 0.3s ease;
}

.reason-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
}

.reason-icon {
    width: 32px;
    height: 32px;
    background: var(--error-soft);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--error-primary);
    font-size: 1rem;
    flex-shrink: 0;
}

.reason-text {
    color: var(--text-body);
    font-size: 0.95rem;
    line-height: 1.5;
    flex: 1;
}

.reason-text strong {
    color: var(--error-primary-dark);
    font-weight: 600;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 2rem;
}

.action-card {
    background: white;
    border: 1px solid var(--border-light);
    border-radius: 20px;
    padding: 1.25rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.action-card:hover {
    border-color: var(--error-primary);
    transform: translateY(-4px);
    box-shadow: var(--shadow-sm);
    text-decoration: none;
}

.action-card i {
    font-size: 2rem;
    color: var(--error-primary);
    margin-bottom: 0.75rem;
}

.action-card h5 {
    color: var(--text-dark);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.action-card p {
    color: var(--text-light);
    font-size: 0.85rem;
    margin-bottom: 0;
}

/* Main Action Button */
.btn-error-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    width: 100%;
    padding: 1.25rem 2rem;
    background: var(--error-gradient);
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
    margin-bottom: 1.5rem;
}

.btn-error-modern::before {
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

.btn-error-modern:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    color: white;
    text-decoration: none;
}

.btn-error-modern:hover::before {
    width: 300px;
    height: 300px;
}

.btn-error-modern i {
    transition: transform 0.3s ease;
    font-size: 1.2rem;
}

.btn-error-modern:hover i {
    transform: rotate(180deg);
}

/* Support Section */
.support-section {
    background: linear-gradient(145deg, #f9fafb, #f3f4f6);
    border-radius: 24px;
    padding: 1.5rem;
    text-align: center;
    margin-top: 2rem;
}

.support-section h5 {
    color: var(--text-dark);
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.support-section h5 i {
    color: var(--error-primary);
}

.contact-grid {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    background: white;
    border-radius: 100px;
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.contact-item:hover {
    border-color: var(--error-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
}

.contact-item i {
    width: 32px;
    height: 32px;
    background: var(--error-soft);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--error-primary);
    font-size: 1rem;
}

.contact-item a {
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
}

.contact-item a:hover {
    color: var(--error-primary);
}

/* Divider */
.custom-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 2rem 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

.custom-divider::before,
.custom-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border-light), transparent);
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
    .verify-failed-wrap {
        margin: 1.5rem 1rem;
    }
    
    .verify-failed-header {
        padding: 2rem 1.5rem;
    }
    
    .verify-failed-header h2 {
        font-size: 2rem;
    }
    
    .verify-failed-body {
        padding: 1.5rem;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
    
    .contact-grid {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    
    .contact-item {
        width: 100%;
        justify-content: center;
    }
    
    .error-message p {
        max-width: 100%;
    }
    
    .reasons-card {
        padding: 1.5rem;
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
        <!-- Header with Error Gradient -->
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
                    <?php echo htmlspecialchars($jamb_number); ?>
                </div>
                <p>We couldn't find this JAMB number in our database</p>
            </div>
            
            <!-- Reasons Card -->
            <div class="reasons-card">
                <div class="reasons-header">
                    <i class="fas fa-lightbulb"></i>
                    <h4>Why this might have happened</h4>
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
                            <strong>Institution not selected:</strong> This college must be your first choice
                        </span>
                    </li>
                    <li class="reason-item">
                        <span class="reason-icon">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <span class="reason-text">
                            <strong>Score requirement:</strong> Your UTME score may be below the minimum
                        </span>
                    </li>
                </ul>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="/apply/step/1" class="action-card">
                    <i class="fas fa-redo-alt"></i>
                    <h5>Try Again</h5>
                    <p>Re-enter your JAMB number</p>
                </a>
                
                <a href="/contact" class="action-card">
                    <i class="fas fa-headset"></i>
                    <h5>Get Help</h5>
                    <p>Contact support team</p>
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
                
                <p style="margin-top: 1rem; font-size: 0.85rem; color: var(--text-light);">
                    Our support team typically responds within 24 hours
                </p>
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
    
    // Animated countdown for better UX (optional)
    let seconds = 3;
    const btn = document.getElementById('retryBtn');
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
});
</script>