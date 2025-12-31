<?php
/**
 * Application Success View
 * 
 * @package FCT_CNS
 * @version 1.0
 */

// Extract data passed from controller
extract($data ?? []);

// Helper function for escaping output
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// Set defaults
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '/fctcns-website');
$page_title = $page_title ?? 'Application Submitted - FCT College of Nursing Sciences';
$currentPage = $currentPage ?? 'apply';
$reference_number = $reference_number ?? '';
$name = $name ?? '';
$email = $email ?? '';
$program = $program ?? '';

// Generate current date
$currentDate = date('F j, Y');
?>

<style>
/* Success Page Styles */
.success-container {
    font-family: var(--font-body);
    line-height: 1.6;
    color: var(--color-gray-700);
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* Success Hero */
.success-hero {
    background: linear-gradient(135deg, 
        rgba(56, 161, 105, 0.95) 0%, 
        rgba(47, 133, 90, 0.9) 100%);
    color: var(--color-white);
    padding: var(--spacing-2xl) 0 var(--spacing-xl) 0;
    margin-top: 0;
    position: relative;
    overflow: hidden;
}

.success-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.hero-inner {
    position: relative;
    z-index: 1;
    max-width: 48rem;
    margin: 0 auto;
    text-align: center;
}

.hero-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2.5rem;
    line-height: 1.2;
    margin-bottom: var(--spacing-md);
    margin-top: 0;
}

.hero-subtitle {
    font-size: 1.125rem;
    opacity: 0.95;
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
}

/* Breadcrumb */
.breadcrumb {
    background-color: var(--color-gray-50);
    padding: var(--spacing-md) 0;
    border-bottom: 1px solid var(--color-gray-200);
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: 0.875rem;
}

.breadcrumb-nav a {
    color: var(--color-gray-600);
    text-decoration: none;
    transition: color var(--transition-fast);
}

.breadcrumb-nav a:hover {
    color: var(--color-primary);
    text-decoration: underline;
}

.breadcrumb-separator {
    color: var(--color-gray-400);
}

.breadcrumb-current {
    color: var(--color-primary);
    font-weight: 600;
}

/* Success Content */
.success-section {
    padding: var(--spacing-2xl) 0;
    background-color: var(--color-gray-50);
}

.success-container-inner {
    max-width: 800px;
    margin: 0 auto;
}

.success-card {
    background-color: var(--color-white);
    border-radius: var(--radius-xl);
    padding: var(--spacing-2xl);
    box-shadow: var(--shadow-lg);
    text-align: center;
}

/* Success Icon */
.success-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--color-success), #2f855a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-xl);
    color: var(--color-white);
    animation: successBounce 0.8s ease;
}

@keyframes successBounce {
    0% { transform: scale(0); }
    70% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.success-icon svg {
    width: 50px;
    height: 50px;
}

/* Success Message */
.success-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-md);
}

.success-message {
    font-size: 1.125rem;
    color: var(--color-gray-600);
    line-height: 1.6;
    margin-bottom: var(--spacing-xl);
}

/* Application Details */
.application-details {
    background-color: var(--color-gray-50);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    margin: var(--spacing-xl) 0;
    text-align: left;
}

.details-title {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-lg);
    font-size: 1.25rem;
    text-align: center;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-lg);
}

.detail-item {
    margin-bottom: var(--spacing-md);
}

.detail-label {
    font-weight: 600;
    color: var(--color-gray-600);
    font-size: 0.875rem;
    margin-bottom: var(--spacing-xs);
    display: block;
}

.detail-value {
    color: var(--color-gray-800);
    font-size: 1rem;
}

.reference-number {
    background-color: var(--color-gray-100);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    border-left: 4px solid var(--color-primary);
    font-family: var(--font-monospace);
    font-weight: 600;
    color: var(--color-primary);
    text-align: center;
    margin: var(--spacing-md) 0;
}

/* Next Steps */
.next-steps {
    margin-top: var(--spacing-xl);
    padding-top: var(--spacing-xl);
    border-top: 2px solid var(--color-gray-200);
}

.next-steps-title {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-lg);
    font-size: 1.5rem;
}

.steps-list {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: left;
}

.step-item {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
    padding: var(--spacing-md);
    background-color: var(--color-gray-50);
    border-radius: var(--radius-md);
    border-left: 4px solid var(--color-success);
}

.step-number {
    width: 32px;
    height: 32px;
    background-color: var(--color-success);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: var(--color-white);
    flex-shrink: 0;
    margin-top: 2px;
}

.step-content h4 {
    font-weight: 600;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-xs);
    font-size: 1.125rem;
}

.step-content p {
    color: var(--color-gray-600);
    line-height: 1.5;
    margin: 0;
}

/* Contact Info */
.contact-info {
    background-color: var(--color-gray-50);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.contact-title {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-lg);
    font-size: 1.25rem;
    text-align: center;
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
}

.contact-item {
    text-align: center;
    padding: var(--spacing-md);
}

.contact-icon {
    width: 48px;
    height: 48px;
    background-color: var(--color-gray-200);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
    color: var(--color-primary);
}

.contact-icon svg {
    width: 24px;
    height: 24px;
}

.contact-item h4 {
    font-weight: 600;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-xs);
    font-size: 1rem;
}

.contact-item p {
    color: var(--color-gray-600);
    font-size: 0.875rem;
    margin-bottom: var(--spacing-sm);
}

.contact-link {
    color: var(--color-primary);
    text-decoration: none;
    font-weight: 600;
    transition: color var(--transition-fast);
}

.contact-link:hover {
    color: var(--color-primary-dark);
    text-decoration: underline;
}

/* Actions */
.actions {
    display: flex;
    gap: var(--spacing-md);
    margin-top: var(--spacing-xl);
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-md) var(--spacing-xl);
    border-radius: var(--radius-md);
    font-weight: 600;
    text-decoration: none;
    transition: all var(--transition-base);
    border: 2px solid transparent;
    cursor: pointer;
    font-family: var(--font-heading);
    font-size: 1rem;
    min-width: 160px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--color-success), #2f855a);
    color: var(--color-white);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2f855a, #276749);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background-color: transparent;
    color: var(--color-gray-600);
    border-color: var(--color-gray-300);
}

.btn-secondary:hover {
    background-color: var(--color-gray-100);
    border-color: var(--color-gray-400);
}

.btn svg {
    width: 20px;
    height: 20px;
    margin-right: var(--spacing-sm);
}

/* Print Styles */
@media print {
    .success-hero,
    .breadcrumb,
    .actions,
    .btn,
    .skip-to-content {
        display: none;
    }
    
    .success-card {
        box-shadow: none;
        border: 1px solid var(--color-gray-300);
    }
    
    .application-details {
        border: 1px solid var(--color-gray-300);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .success-card {
        padding: var(--spacing-lg);
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .contact-grid {
        grid-template-columns: 1fr;
    }
    
    .actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn {
        width: 100%;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .success-title {
        font-size: 1.75rem;
    }
}

@media (max-width: 480px) {
    .success-card {
        padding: var(--spacing-md);
    }
    
    .success-icon {
        width: 80px;
        height: 80px;
    }
    
    .success-icon svg {
        width: 40px;
        height: 40px;
    }
    
    .hero-title {
        font-size: 1.75rem;
    }
    
    .success-title {
        font-size: 1.5rem;
    }
}

/* Print Button */
.print-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: var(--radius-md);
    font-weight: 600;
    text-decoration: none;
    transition: all var(--transition-base);
    border: 2px solid var(--color-gray-300);
    cursor: pointer;
    font-family: var(--font-heading);
    font-size: 0.875rem;
    background-color: var(--color-white);
    color: var(--color-gray-700);
    margin-top: var(--spacing-md);
}

.print-btn:hover {
    background-color: var(--color-gray-100);
    border-color: var(--color-gray-400);
}

.print-btn svg {
    width: 16px;
    height: 16px;
    margin-right: var(--spacing-sm);
}
</style>

<!-- Main Content -->
<main id="main-content" class="success-container" role="main" aria-label="Application submitted successfully">
    <!-- Skip to Content Link -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>

    <!-- Success Hero -->
    <header class="success-hero" role="banner">
        <div class="container">
            <div class="hero-inner">
                <h1 class="hero-title">Application Submitted Successfully!</h1>
                <p class="hero-subtitle">
                    Thank you for applying to FCT College of Nursing Sciences. 
                    Your application has been received and is being processed.
                </p>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <div class="breadcrumb-nav">
                <a href="<?php echo $baseUrl; ?>/">Home</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <a href="<?php echo $baseUrl; ?>/admissions">Admissions</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <a href="<?php echo $baseUrl; ?>/apply">Application Form</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <span class="breadcrumb-current" aria-current="page">Success</span>
            </div>
        </div>
    </nav>

    <!-- Success Content -->
    <section class="success-section" id="success-content">
        <div class="container">
            <div class="success-container-inner">
                <div class="success-card">
                    <!-- Success Icon -->
                    <div class="success-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <!-- Success Message -->
                    <h2 class="success-title">Thank You, <?php echo e($name); ?>!</h2>
                    
                    <p class="success-message">
                        Your application for the <strong><?php echo e($program); ?></strong> program has been 
                        successfully submitted and is now under review. Please save your application 
                        reference number for future correspondence.
                    </p>

                    <!-- Application Details -->
                    <div class="application-details">
                        <h3 class="details-title">Application Details</h3>
                        
                        <div class="details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Application Reference Number</span>
                                <div class="reference-number"><?php echo e($reference_number); ?></div>
                                <span class="field-help">Use this reference when contacting us about your application</span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Date Submitted</span>
                                <div class="detail-value"><?php echo e($currentDate); ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Applicant Name</span>
                                <div class="detail-value"><?php echo e($name); ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Program Applied For</span>
                                <div class="detail-value"><?php echo e($program); ?></div>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Email Address</span>
                                <div class="detail-value"><?php echo e($email); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="next-steps">
                        <h3 class="next-steps-title">What Happens Next?</h3>
                        
                        <ul class="steps-list">
                            <li class="step-item">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h4>Application Review</h4>
                                    <p>
                                        Our admissions team will review your application within 5-7 working days. 
                                        You'll receive an email notification once the review is complete.
                                    </p>
                                </div>
                            </li>
                            
                            <li class="step-item">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h4>Document Verification</h4>
                                    <p>
                                        If your application meets the initial requirements, you'll be asked to 
                                        submit supporting documents for verification.
                                    </p>
                                </div>
                            </li>
                            
                            <li class="step-item">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h4>Entrance Examination</h4>
                                    <p>
                                        Qualified applicants will be invited for an entrance examination. 
                                        Details about date, time, and venue will be communicated via email.
                                    </p>
                                </div>
                            </li>
                            
                            <li class="step-item">
                                <div class="step-number">4</div>
                                <div class="step-content">
                                    <h4>Interview & Admission</h4>
                                    <p>
                                        Successful candidates will proceed to oral interviews, followed by 
                                        provisional admission offers for those who qualify.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Information -->
                    <div class="contact-info">
                        <h3 class="contact-title">Need Help or Have Questions?</h3>
                        
                        <div class="contact-grid">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <h4>Email Support</h4>
                                <p>For application inquiries</p>
                                <a href="mailto:admissions@fctcns.edu.ng" class="contact-link">
                                    admissions@fctcns.edu.ng
                                </a>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                </div>
                                <h4>Phone Support</h4>
                                <p>Monday - Friday, 8 AM - 4 PM</p>
                                <a href="tel:+2348031234567" class="contact-link">
                                    +234 803 123 4567
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="actions">
                        <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-secondary">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Back to Admissions
                        </a>
                        
                        <a href="<?php echo $baseUrl; ?>/" class="btn btn-primary">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            Return to Homepage
                        </a>
                    </div>

                    <!-- Print Option -->
                    <button class="print-btn" onclick="window.print()">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                        </svg>
                        Print This Page
                    </button>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Auto-refresh prevention for back button
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    
    // Copy reference number to clipboard
    const copyRefBtn = document.getElementById('copyRefBtn');
    if (copyRefBtn) {
        copyRefBtn.addEventListener('click', function() {
            const refNumber = document.querySelector('.reference-number').textContent;
            
            navigator.clipboard.writeText(refNumber).then(function() {
                // Show success message
                const originalText = copyRefBtn.innerHTML;
                copyRefBtn.innerHTML = `
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Copied!
                `;
                
                setTimeout(function() {
                    copyRefBtn.innerHTML = originalText;
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
            });
        });
    }
    
    // Print functionality
    const printBtn = document.querySelector('.print-btn');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Auto-scroll to top
    window.scrollTo(0, 0);
});
</script>