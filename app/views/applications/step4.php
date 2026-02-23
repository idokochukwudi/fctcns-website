<?php
/**
 * Step 4 View - Exam Slip
 * Redesigned: Premium institutional design with security enhancements
 * FIXED: Removed redundant headers, fixed download functionality, cleaned up layout
 * 
 * @package FCTCNS
 */

// =========================================================
// 1. Add required helpers at the top of each view file
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class Step4View {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        if (!function_exists('e')) {
            function e($text) {
                return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
            }
        }

        $baseUrl = $baseUrl ?? '/';
        $application = $application ?? [];
        $exam_slip = $exam_slip ?? [];
        $applicant = $applicant ?? [];
        $applicant_name = $applicant_name ?? 'Applicant';
        $olevel_results = $olevel_results ?? [];
        $has_exam_slip = $has_exam_slip ?? false;
        $exam_details = $exam_details ?? [
            'date' => 'To be announced',
            'venue' => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
            'reporting_time' => '8:00 AM'
        ];
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
            <meta name="description" content="Examination Slip - FCT College of Nursing Sciences">
            
            <!-- ========================================================= -->
            <!-- 2. Add security meta tags in the head -->
            <!-- ========================================================= -->
            <?php echo $this->getSecurityMetaTags(); ?>
            
            <!-- ========================================================= -->
            <!-- 3. Add CSRF meta tag for JavaScript -->
            <!-- ========================================================= -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">
            
            <title>Examination Slip — FCT College of Nursing Sciences</title>

            <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            
            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all style tags -->
            <!-- 5. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" 
                  rel="stylesheet">
            
            <link rel="stylesheet" 
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                  integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
                /* ─── CSS Variables - Purple Theme ─────────────────── */
                :root {
                    --primary:       #6B4E9B;
                    --primary-dark:  #4A3B6B;
                    --primary-light: #8A6FB0;
                    --primary-soft:  #F3EAF8;
                    --gold:          #C9A44A;
                    --gold-light:    #E2B05F;
                    --gold-pale:     #FDF6E9;
                    --white:         #FFFFFF;
                    --grey-1:        #F4F6FB;
                    --grey-2:        #E8ECF5;
                    --grey-3:        #C5CEDF;
                    --grey-4:        #8695AE;
                    --grey-5:        #4A5568;
                    --ink:           #1A2438;
                    --green:         #10b981;
                    --green-pale:    #d1fae5;
                    --red:           #ef4444;
                    --red-pale:      #fee2e2;
                    --orange:        #f59e0b;
                    --orange-pale:   #fef3c7;
                    --blue:          #3b82f6;
                    --blue-pale:     #dbeafe;
                    
                    --border:        #E0E7F0;
                    --shadow-sm:     0 2px 8px rgba(107,78,155,0.05);
                    --shadow-md:     0 4px 16px rgba(107,78,155,0.08);
                    --shadow-lg:     0 12px 40px rgba(107,78,155,0.12);
                    
                    --radius-sm:     6px;
                    --radius-md:     12px;
                    --radius-lg:     20px;
                    --radius-xl:     28px;
                    
                    --font-display:  'DM Serif Display', Georgia, serif;
                    --font-body:     'DM Sans', system-ui, sans-serif;
                    --font-mono:     'DM Mono', monospace;
                }

                /* ─── Reset ──────────────────────────────────────── */
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: var(--font-body);
                    background: var(--primary-soft);
                    color: var(--ink);
                    min-height: 100vh;
                    line-height: 1.5;
                }

                /* ─── Navigation Bar - SINGLE CLEAN NAVIGATION ───── */
                .navbar {
                    background: var(--white);
                    border-bottom: 2px solid var(--primary-light);
                    padding: 0.75rem 2rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    box-shadow: var(--shadow-md);
                    position: sticky;
                    top: 0;
                    z-index: 100;
                }

                .navbar-brand {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                }

                .navbar-logo {
                    width: 40px;
                    height: 40px;
                    background: var(--primary);
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: bold;
                    font-size: 1.2rem;
                }

                .navbar-title {
                    font-weight: 600;
                    color: var(--primary-dark);
                    font-size: 1.1rem;
                }

                .navbar-subtitle {
                    font-size: 0.8rem;
                    color: var(--grey-4);
                    margin-left: 0.5rem;
                    padding-left: 0.5rem;
                    border-left: 2px solid var(--primary-soft);
                }

                .navbar-menu {
                    display: flex;
                    align-items: center;
                    gap: 1.5rem;
                }

                .navbar-item {
                    color: var(--grey-5);
                    text-decoration: none;
                    font-size: 0.9rem;
                    padding: 0.5rem 1rem;
                    border-radius: var(--radius-sm);
                    transition: all 0.2s;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .navbar-item:hover {
                    background: var(--primary-soft);
                    color: var(--primary);
                }

                .navbar-item i {
                    font-size: 0.9rem;
                    color: var(--primary);
                }

                .navbar-user {
                    background: var(--primary-soft);
                    padding: 0.5rem 1.2rem;
                    border-radius: 100px;
                    font-size: 0.9rem;
                    color: var(--primary-dark);
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .navbar-user i {
                    color: var(--primary);
                }

                /* ─── Progress Steps ─────────────────────────────── */
                .progress-steps {
                    background: var(--white);
                    padding: 1.5rem 2rem;
                    border-bottom: 1px solid var(--border);
                    display: flex;
                    justify-content: center;
                    gap: 2rem;
                }

                .step {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    color: var(--grey-4);
                }

                .step.completed {
                    color: var(--green);
                }

                .step.active {
                    color: var(--primary);
                }

                .step-number {
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    background: var(--grey-2);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 600;
                    font-size: 0.9rem;
                }

                .step.completed .step-number {
                    background: var(--green);
                    color: white;
                }

                .step.active .step-number {
                    background: var(--primary);
                    color: white;
                }

                .step-label {
                    font-weight: 500;
                }

                /* ─── Main Container ─────────────────────────────── */
                .main-container {
                    max-width: 1000px;
                    margin: 2rem auto;
                    padding: 0 2rem;
                }

                /* ─── Success Banner ─────────────────────────────── */
                .success-banner {
                    background: linear-gradient(135deg, var(--green), #0d9488);
                    border-radius: var(--radius-lg);
                    padding: 1.5rem 2rem;
                    color: white;
                    margin-bottom: 2rem;
                    display: flex;
                    align-items: center;
                    gap: 1.5rem;
                    box-shadow: var(--shadow-lg);
                }

                .success-icon {
                    width: 48px;
                    height: 48px;
                    background: rgba(255,255,255,0.2);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.5rem;
                }

                .success-content {
                    flex: 1;
                }

                .success-title {
                    font-size: 1.3rem;
                    font-weight: 600;
                    margin-bottom: 0.25rem;
                }

                .success-message {
                    opacity: 0.9;
                    font-size: 0.9rem;
                }

                /* ─── Card ───────────────────────────────────────── */
                .card {
                    background: var(--white);
                    border-radius: var(--radius-xl);
                    box-shadow: var(--shadow-lg);
                    overflow: hidden;
                    margin-bottom: 2rem;
                }

                .card-header {
                    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                    padding: 1.5rem 2rem;
                    color: white;
                }

                .card-header h1 {
                    font-family: var(--font-display);
                    font-size: 1.8rem;
                    font-weight: 400;
                    margin-bottom: 0.25rem;
                }

                .card-header p {
                    opacity: 0.8;
                    font-size: 0.9rem;
                }

                .card-body {
                    padding: 2rem;
                }

                /* ─── Exam Slip Preview ──────────────────────────── */
                .slip-preview {
                    background: var(--primary-soft);
                    border: 2px dashed var(--primary-light);
                    border-radius: var(--radius-lg);
                    padding: 2rem;
                    margin-bottom: 2rem;
                    text-align: center;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                .slip-preview:hover {
                    background: var(--gold-pale);
                    border-color: var(--gold);
                    transform: translateY(-2px);
                    box-shadow: var(--shadow-md);
                }

                .preview-icon {
                    font-size: 3rem;
                    color: var(--primary);
                    margin-bottom: 1rem;
                }

                .preview-title {
                    font-size: 1.2rem;
                    font-weight: 600;
                    color: var(--primary-dark);
                    margin-bottom: 0.5rem;
                }

                .preview-subtitle {
                    color: var(--grey-4);
                    font-size: 0.9rem;
                }

                /* ─── Info Grid ──────────────────────────────────── */
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 1rem;
                    margin-bottom: 2rem;
                }

                .info-item {
                    background: var(--primary-soft);
                    border-radius: var(--radius-md);
                    padding: 1.2rem;
                }

                .info-label {
                    font-size: 0.7rem;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    color: var(--grey-4);
                    margin-bottom: 0.5rem;
                }

                .info-value {
                    font-size: 1rem;
                    font-weight: 600;
                    color: var(--primary-dark);
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .info-value i {
                    color: var(--gold);
                    width: 20px;
                }

                /* ─── Action Buttons ─────────────────────────────── */
                .action-buttons {
                    display: flex;
                    gap: 1rem;
                    flex-wrap: wrap;
                    margin-top: 2rem;
                }

                .btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.75rem;
                    padding: 1rem 2rem;
                    border: none;
                    border-radius: var(--radius-md);
                    font-size: 1rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    text-decoration: none;
                    flex: 1;
                    min-width: 200px;
                }

                .btn i {
                    font-size: 1.1rem;
                }

                .btn-primary {
                    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                    color: white;
                    box-shadow: 0 4px 12px rgba(107,78,155,0.3);
                }

                .btn-primary:hover:not(:disabled) {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(107,78,155,0.4);
                }

                .btn-success {
                    background: var(--green);
                    color: white;
                    box-shadow: 0 4px 12px rgba(16,185,129,0.3);
                }

                .btn-success:hover:not(:disabled) {
                    background: #0d9488;
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(16,185,129,0.4);
                }

                .btn-outline {
                    background: transparent;
                    color: var(--primary);
                    border: 2px solid var(--primary-light);
                }

                .btn-outline:hover {
                    background: var(--primary-soft);
                    border-color: var(--primary);
                }

                .btn:disabled {
                    opacity: 0.6;
                    cursor: not-allowed;
                }

                /* ─── Important Note ─────────────────────────────── */
                .note-box {
                    margin-top: 2rem;
                    padding: 1rem 1.5rem;
                    background: var(--orange-pale);
                    border-radius: var(--radius-md);
                    border-left: 4px solid var(--orange);
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    color: #92400e;
                }

                .note-box i {
                    font-size: 1.2rem;
                }

                /* ─── Support Section ────────────────────────────── */
                .support-section {
                    margin-top: 2rem;
                    padding-top: 2rem;
                    border-top: 1px solid var(--border);
                }

                .support-title {
                    text-align: center;
                    margin-bottom: 1.5rem;
                    color: var(--primary-dark);
                    font-size: 1.1rem;
                }

                .support-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 1rem;
                }

                .support-card {
                    background: var(--primary-soft);
                    border-radius: var(--radius-md);
                    padding: 1.2rem;
                    text-align: center;
                }

                .support-icon {
                    width: 40px;
                    height: 40px;
                    background: var(--white);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 0.75rem;
                    color: var(--primary);
                    font-size: 1rem;
                }

                .support-card h4 {
                    color: var(--primary-dark);
                    margin-bottom: 0.25rem;
                    font-size: 0.95rem;
                }

                .support-card p {
                    color: var(--grey-5);
                    font-size: 0.85rem;
                }

                /* ─── Toast Notifications ────────────────────────── */
                .toast-notification {
                    position: fixed;
                    top: 80px;
                    right: 20px;
                    padding: 1rem 1.5rem;
                    border-radius: var(--radius-md);
                    color: white;
                    font-size: 0.95rem;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    box-shadow: var(--shadow-lg);
                    z-index: 10000;
                    animation: slideInRight 0.3s ease;
                }

                .toast-success { background: var(--green); }
                .toast-error { background: var(--red); }
                .toast-info { background: var(--blue); }

                @keyframes slideInRight {
                    from {
                        opacity: 0;
                        transform: translateX(100%);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }

                /* ─── Responsive ─────────────────────────────────── */
                @media (max-width: 768px) {
                    .navbar {
                        padding: 0.75rem 1rem;
                        flex-direction: column;
                        gap: 1rem;
                    }
                    
                    .navbar-brand {
                        width: 100%;
                        justify-content: space-between;
                    }
                    
                    .navbar-menu {
                        width: 100%;
                        justify-content: center;
                        flex-wrap: wrap;
                        gap: 0.5rem;
                    }
                    
                    .progress-steps {
                        padding: 1rem;
                        flex-wrap: wrap;
                        gap: 1rem;
                    }
                    
                    .main-container {
                        padding: 0 1rem;
                    }
                    
                    .card-header {
                        padding: 1.2rem;
                    }
                    
                    .card-header h1 {
                        font-size: 1.4rem;
                    }
                    
                    .card-body {
                        padding: 1.2rem;
                    }
                    
                    .info-grid {
                        grid-template-columns: 1fr;
                    }
                    
                    .action-buttons {
                        flex-direction: column;
                    }
                    
                    .btn {
                        width: 100%;
                    }
                    
                    .support-grid {
                        grid-template-columns: 1fr;
                    }
                }
            </style>
        </head>
        <body>

        <!-- ─── SINGLE NAVIGATION BAR ─────────────────────────────── -->
        <nav class="navbar">
            <div class="navbar-brand">
                <div class="navbar-logo">CNS</div>
                <span class="navbar-title">FCT College of Nursing Sciences</span>
                <span class="navbar-subtitle">Admissions Portal 2025/26</span>
            </div>
            <div class="navbar-menu">
                <a href="/apply/step/1" class="navbar-item">
                    <i class="fas fa-file-alt"></i> Application
                </a>
                <a href="/applicant/logout" class="navbar-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <span class="navbar-user">
                    <i class="fas fa-user-circle"></i> <?php echo $this->e($applicant_name); ?>
                </span>
            </div>
        </nav>

        <!-- ─── PROGRESS STEPS ────────────────────────────────────── -->
        <div class="progress-steps">
            <div class="step completed">
                <span class="step-number">1</span>
                <span class="step-label">JAMB Verification</span>
            </div>
            <div class="step completed">
                <span class="step-number">2</span>
                <span class="step-label">Application Form</span>
            </div>
            <div class="step completed">
                <span class="step-number">3</span>
                <span class="step-label">Payment</span>
            </div>
            <div class="step active">
                <span class="step-number">4</span>
                <span class="step-label">Exam Slip</span>
            </div>
        </div>

        <!-- ─── Main Container ─────────────────────────────────────── -->
        <div class="main-container">

            <!-- Alert Container for Toast Notifications -->
            <div id="alertContainer"></div>

            <!-- Success Banner -->
            <div class="success-banner">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="success-content">
                    <div class="success-title">Payment Successful!</div>
                    <div class="success-message">Your examination slip is ready. Please download and print it for the screening exercise.</div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card">
                <div class="card-header">
                    <h1>Examination Slip</h1>
                    <p>Download and print your examination slip for the screening exercise</p>
                </div>
                <div class="card-body">

                    <!-- Exam Slip Preview Card -->
                    <div class="slip-preview" id="slipPreview">
                        <div class="preview-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="preview-title">View/Print Examination Slip</div>
                        <div class="preview-subtitle">
                            Slip Number: <?php echo $this->e($exam_slip['slip_number'] ?? 'Not Available'); ?>
                        </div>
                    </div>

                    <!-- Key Information Grid -->
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Slip Number</div>
                            <div class="info-value">
                                <i class="fas fa-hashtag"></i>
                                <?php echo $this->e($exam_slip['slip_number'] ?? 'Not Available'); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Application Number</div>
                            <div class="info-value">
                                <i class="fas fa-file-alt"></i>
                                <?php echo $this->e($application['application_number'] ?? 'Not Available'); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Examination Date</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo $this->e(date('l, jS F Y', strtotime($exam_slip['exam_date'] ?? $exam_details['date']))); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Examination Time</div>
                            <div class="info-value">
                                <i class="fas fa-clock"></i>
                                <?php echo $this->e($exam_slip['exam_time'] ?? '10:00 AM'); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Reporting Time</div>
                            <div class="info-value">
                                <i class="fas fa-hourglass-start"></i>
                                <?php echo $this->e($exam_slip['reporting_time'] ?? $exam_details['reporting_time']); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Venue</div>
                            <div class="info-value">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo $this->e($exam_slip['exam_venue'] ?? $exam_details['venue']); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Seat Number</div>
                            <div class="info-value">
                                <i class="fas fa-chair"></i>
                                <?php echo $this->e($exam_slip['seat_number'] ?? 'To be assigned'); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Downloads</div>
                            <div class="info-value">
                                <i class="fas fa-download"></i>
                                <?php echo (int)($exam_slip['download_count'] ?? 0); ?> times
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn btn-primary" id="viewPrintBtn">
                            <i class="fas fa-print"></i>
                            View / Print Slip
                        </button>
                        <button class="btn btn-success" id="downloadBtn">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </button>
                        <a href="/apply/step/1" class="btn btn-outline">
                            <i class="fas fa-home"></i>
                            Back to Home
                        </a>
                    </div>

                    <!-- Important Note -->
                    <div class="note-box">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span><strong>Important:</strong> Please print your examination slip and bring it to the screening center. Digital copies on phones may not be accepted.</span>
                    </div>

                    <!-- Support Section -->
                    <div class="support-section">
                        <div class="support-title">Need Assistance?</div>
                        <div class="support-grid">
                            <div class="support-card">
                                <div class="support-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <h4>Phone Support</h4>
                                <p>07039837749</p>
                            </div>
                            <div class="support-card">
                                <div class="support-icon">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <h4>WhatsApp</h4>
                                <p>08082775076</p>
                            </div>
                            <div class="support-card">
                                <div class="support-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h4>Email</h4>
                                <p>admissions@fctcns.edu.ng</p>
                            </div>
                        </div>
                    </div>

                </div><!-- /card-body -->
            </div><!-- /card -->

        </div><!-- /main-container -->

        <!-- ========================================================= -->
        <!-- 7. Add CSP nonce to all script tags -->
        <!-- ========================================================= -->
        <script nonce="<?php echo $csp_nonce; ?>">
        (function() {
            'use strict';

            // ── Configuration ─────────────────────────────────────────
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const baseUrl = '<?php echo $baseUrl; ?>';
            const slipNumber = '<?php echo $this->e($exam_slip['slip_number'] ?? ''); ?>';
            const applicationId = '<?php echo $this->e($application['id'] ?? ''); ?>';

            // ── DOM Elements ─────────────────────────────────────────
            const alertContainer = document.getElementById('alertContainer');
            const viewPrintBtn = document.getElementById('viewPrintBtn');
            const downloadBtn = document.getElementById('downloadBtn');
            const slipPreview = document.getElementById('slipPreview');

            // ── Toast Notification ───────────────────────────────────
            function showToast(message, type = 'info') {
                // Remove existing toasts
                document.querySelectorAll('.toast-notification').forEach(t => t.remove());

                const toast = document.createElement('div');
                toast.className = `toast-notification toast-${type}`;
                
                const icon = type === 'success' ? 'fa-check-circle' : 
                            type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
                
                const safeMsg = String(message).replace(/[<>]/g, '');
                toast.innerHTML = `<i class="fas ${icon}"></i> ${safeMsg}`;
                
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.transition = 'opacity 0.3s, transform 0.3s';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }

            // ── Track Event ──────────────────────────────────────────
            function trackEvent(eventType) {
                if (csrfToken) {
                    fetch('/api/track-event', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            event: eventType,
                            slipNumber: slipNumber,
                            applicationId: applicationId,
                            timestamp: new Date().toISOString()
                        })
                    }).catch(err => console.error('Tracking failed:', err));
                }
            }

            // ─── FIXED: Download Function ─────────────────────────────
            function triggerDownload(btn) {
                if (!slipNumber) {
                    showToast('Exam slip not available for download', 'error');
                    return;
                }

                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing PDF...';
                btn.disabled = true;

                trackEvent('exam_slip_download');
                showToast('Preparing your PDF for download...', 'info');

                // Create a hidden anchor element for download
                const downloadUrl = baseUrl + '/apply/download-exam-slip?csrf=' + encodeURIComponent(csrfToken) + '&t=' + Date.now();
                
                // Use fetch to check if the download is available first
                fetch(downloadUrl, {
                    method: 'HEAD',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Download not available');
                    }
                    
                    // If HEAD request succeeds, proceed with download
                    const a = document.createElement('a');
                    a.href = downloadUrl;
                    a.download = 'exam-slip-' + slipNumber + '.html';
                    a.style.display = 'none';
                    document.body.appendChild(a);
                    a.click();
                    
                    setTimeout(() => {
                        document.body.removeChild(a);
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        showToast('Download started successfully', 'success');
                    }, 1000);
                })
                .catch(error => {
                    console.error('Download error:', error);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    showToast('Download failed. Please try again or use Print instead.', 'error');
                });
            }

            // ─── FIXED: Open Print View ───────────────────────────────
            function openPrintView() {
                if (!slipNumber) {
                    showToast('Exam slip not available', 'error');
                    return;
                }

                trackEvent('exam_slip_view');
                
                // Open print view in new window with proper dimensions
                const printWindow = window.open(
                    baseUrl + '/apply/print-exam-slip?csrf=' + encodeURIComponent(csrfToken) + '&t=' + Date.now(),
                    'PrintExamSlip',
                    'width=900,height=700,scrollbars=yes,resizable=yes,menubar=yes,toolbar=yes,location=yes,status=yes'
                );

                if (!printWindow) {
                    showToast('Please allow pop-ups to view the exam slip', 'error');
                } else {
                    showToast('Opening print view...', 'info');
                }
            }

            // ── Event Listeners ──────────────────────────────────────
            if (viewPrintBtn) {
                viewPrintBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openPrintView();
                });
            }

            if (downloadBtn) {
                downloadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    triggerDownload(this);
                });
            }

            if (slipPreview) {
                slipPreview.addEventListener('click', function(e) {
                    e.preventDefault();
                    openPrintView();
                });
            }

            // ── Keyboard Shortcuts ───────────────────────────────────
            document.addEventListener('keydown', function(e) {
                // Ctrl+P or Cmd+P for print
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    if (viewPrintBtn) {
                        openPrintView();
                    }
                }
            });

            // ── Log page view ────────────────────────────────────────
            console.log('Step 4 page loaded:', {
                slipNumber: slipNumber,
                applicationId: applicationId,
                timestamp: new Date().toISOString()
            });

            // Check if slip was recently generated
            <?php if (isset($_GET['new']) && $_GET['new'] == 1): ?>
            showToast('Your examination slip has been generated successfully!', 'success');
            <?php endif; ?>

            // Prevent accidental navigation
            window.addEventListener('beforeunload', function(e) {
                // No confirmation dialog, just log
                console.log('User leaving step 4 page');
            });

        })();
        </script>

        </body>
        </html>
        <?php
    }
}

// =========================================================
// 9. Add the view instantiation at the bottom
// =========================================================
$view = new Step4View();
$view->render(get_defined_vars());
?>