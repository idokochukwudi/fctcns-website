<?php
/**
 * Step 4 View - Exam Slip
 * Redesigned: Premium institutional design with security enhancements
 * FIXED: Removed redundant navbar, kept only progress steps
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

                /* ─── Progress Steps - Now the Main Header ─────────── */
                .progress-header {
                    background: var(--white);
                    padding: 1rem 2rem;
                    border-bottom: 2px solid var(--primary-light);
                    box-shadow: var(--shadow-md);
                    position: sticky;
                    top: 0;
                    z-index: 100;
                }

                .progress-steps {
                    display: flex;
                    justify-content: center;
                    gap: 2rem;
                    max-width: 800px;
                    margin: 0 auto;
                }

                .step {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    color: var(--grey-4);
                    font-size: 0.85rem;
                    position: relative;
                    flex: 1;
                }

                .step:not(:last-child)::after {
                    content: '';
                    position: absolute;
                    right: -1rem;
                    top: 50%;
                    width: 1rem;
                    height: 2px;
                    background: var(--grey-3);
                    transform: translateY(-50%);
                }

                .step.completed {
                    color: var(--green);
                }

                .step.completed::after {
                    background: var(--green);
                }

                .step.active {
                    color: var(--primary);
                }

                .step.active::after {
                    background: var(--primary);
                }

                .step-number {
                    width: 28px;
                    height: 28px;
                    border-radius: 50%;
                    background: var(--grey-2);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 600;
                    font-size: 0.8rem;
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

                /* ─── User Info Bar ───────────────────────────────── */
                .user-bar {
                    background: var(--primary-soft);
                    padding: 0.5rem 2rem;
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    gap: 1.5rem;
                    border-bottom: 1px solid var(--border);
                    font-size: 0.85rem;
                }

                .user-info {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    color: var(--primary-dark);
                }

                .user-info i {
                    color: var(--primary);
                }

                .logout-link {
                    color: var(--grey-5);
                    text-decoration: none;
                    display: flex;
                    align-items: center;
                    gap: 0.3rem;
                    padding: 0.25rem 0.75rem;
                    border-radius: var(--radius-sm);
                    transition: all 0.2s;
                }

                .logout-link:hover {
                    background: var(--white);
                    color: var(--red);
                }

                .logout-link i {
                    font-size: 0.8rem;
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
                    padding: 1.2rem 1.5rem;
                    color: white;
                    margin-bottom: 2rem;
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    box-shadow: var(--shadow-lg);
                }

                .success-icon {
                    width: 40px;
                    height: 40px;
                    background: rgba(255,255,255,0.2);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.2rem;
                }

                .success-content {
                    flex: 1;
                }

                .success-title {
                    font-size: 1.2rem;
                    font-weight: 600;
                    margin-bottom: 0.2rem;
                }

                .success-message {
                    opacity: 0.9;
                    font-size: 0.85rem;
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
                    font-size: 1.6rem;
                    font-weight: 400;
                    margin-bottom: 0.2rem;
                }

                .card-header p {
                    opacity: 0.8;
                    font-size: 0.85rem;
                }

                .card-body {
                    padding: 2rem;
                }

                /* ─── Exam Slip Preview ──────────────────────────── */
                .slip-preview {
                    background: var(--primary-soft);
                    border: 2px dashed var(--primary-light);
                    border-radius: var(--radius-lg);
                    padding: 1.5rem;
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
                    font-size: 2.5rem;
                    color: var(--primary);
                    margin-bottom: 0.5rem;
                }

                .preview-title {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: var(--primary-dark);
                    margin-bottom: 0.25rem;
                }

                .preview-subtitle {
                    color: var(--grey-4);
                    font-size: 0.85rem;
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
                    padding: 1rem;
                }

                .info-label {
                    font-size: 0.65rem;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    color: var(--grey-4);
                    margin-bottom: 0.25rem;
                }

                .info-value {
                    font-size: 0.95rem;
                    font-weight: 600;
                    color: var(--primary-dark);
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .info-value i {
                    color: var(--gold);
                    width: 18px;
                    font-size: 0.9rem;
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
                    gap: 0.5rem;
                    padding: 0.85rem 1.5rem;
                    border: none;
                    border-radius: var(--radius-md);
                    font-size: 0.95rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    text-decoration: none;
                    flex: 1;
                    min-width: 180px;
                }

                .btn i {
                    font-size: 1rem;
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
                    padding: 0.85rem 1.2rem;
                    background: var(--orange-pale);
                    border-radius: var(--radius-md);
                    border-left: 4px solid var(--orange);
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    color: #92400e;
                    font-size: 0.9rem;
                }

                .note-box i {
                    font-size: 1.1rem;
                }

                /* ─── Support Section ────────────────────────────── */
                .support-section {
                    margin-top: 2rem;
                    padding-top: 1.5rem;
                    border-top: 1px solid var(--border);
                }

                .support-title {
                    text-align: center;
                    margin-bottom: 1.2rem;
                    color: var(--primary-dark);
                    font-size: 1rem;
                }

                .support-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 1rem;
                }

                .support-card {
                    background: var(--primary-soft);
                    border-radius: var(--radius-md);
                    padding: 1rem;
                    text-align: center;
                }

                .support-icon {
                    width: 36px;
                    height: 36px;
                    background: var(--white);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 0.5rem;
                    color: var(--primary);
                    font-size: 0.9rem;
                }

                .support-card h4 {
                    color: var(--primary-dark);
                    margin-bottom: 0.25rem;
                    font-size: 0.9rem;
                }

                .support-card p {
                    color: var(--grey-5);
                    font-size: 0.8rem;
                }

                /* ─── Toast Notifications ────────────────────────── */
                .toast-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 0.85rem 1.2rem;
                    border-radius: var(--radius-md);
                    color: white;
                    font-size: 0.9rem;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
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
                    .progress-header {
                        padding: 0.75rem 1rem;
                    }
                    
                    .progress-steps {
                        gap: 0.5rem;
                    }
                    
                    .step:not(:last-child)::after {
                        display: none;
                    }
                    
                    .step-label {
                        display: none;
                    }
                    
                    .step {
                        justify-content: center;
                    }
                    
                    .user-bar {
                        padding: 0.5rem 1rem;
                        justify-content: space-between;
                    }
                    
                    .main-container {
                        padding: 0 1rem;
                        margin: 1rem auto;
                    }
                    
                    .card-header {
                        padding: 1.2rem;
                    }
                    
                    .card-header h1 {
                        font-size: 1.3rem;
                    }
                    
                    .card-body {
                        padding: 1.2rem;
                    }
                    
                    .info-grid {
                        grid-template-columns: 1fr;
                        gap: 0.75rem;
                    }
                    
                    .action-buttons {
                        flex-direction: column;
                    }
                    
                    .btn {
                        width: 100%;
                        min-width: auto;
                    }
                    
                    .support-grid {
                        grid-template-columns: 1fr;
                        gap: 0.75rem;
                    }
                }
            </style>
        </head>
        <body>

        <!-- ─── User Bar (Compact) ─────────────────────────────────── -->
        <div class="user-bar">
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span><?php echo $this->e($applicant_name); ?></span>
            </div>
            <a href="/applicant/logout" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- ─── Progress Steps - Main Header ───────────────────────── -->
        <div class="progress-header">
            <div class="progress-steps">
                <div class="step completed">
                    <span class="step-number">1</span>
                    <span class="step-label">JAMB</span>
                </div>
                <div class="step completed">
                    <span class="step-number">2</span>
                    <span class="step-label">Form</span>
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
                    <div class="success-message">Your examination slip is ready. Download and print it for the screening exercise.</div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card">
                <div class="card-header">
                    <h1>Examination Slip</h1>
                    <p>Download and print your examination slip</p>
                </div>
                <div class="card-body">

                    <!-- Exam Slip Preview Card -->
                    <div class="slip-preview" id="slipPreview">
                        <div class="preview-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="preview-title">View/Print Examination Slip</div>
                        <div class="preview-subtitle">
                            Slip: <?php echo $this->e($exam_slip['slip_number'] ?? 'Not Available'); ?>
                        </div>
                    </div>

                    <!-- Key Information Grid -->
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Slip Number</div>
                            <div class="info-value">
                                <i class="fas fa-hashtag"></i>
                                <?php echo $this->e($exam_slip['slip_number'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Application No.</div>
                            <div class="info-value">
                                <i class="fas fa-file-alt"></i>
                                <?php echo $this->e($application['application_number'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Exam Date</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo $this->e(date('d M Y', strtotime($exam_slip['exam_date'] ?? $exam_details['date']))); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Exam Time</div>
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
                                <?php echo $this->e($exam_slip['seat_number'] ?? 'TBA'); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Downloads</div>
                            <div class="info-value">
                                <i class="fas fa-download"></i>
                                <?php echo (int)($exam_slip['download_count'] ?? 0); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn btn-primary" id="viewPrintBtn">
                            <i class="fas fa-print"></i> View / Print
                        </button>
                        <button class="btn btn-success" id="downloadBtn">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                        <a href="/apply/step/1" class="btn btn-outline">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </div>

                    <!-- Important Note -->
                    <div class="note-box">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span><strong>Important:</strong> Print this slip and bring it to the screening center. Digital copies may not be accepted.</span>
                    </div>

                    <!-- Support Section -->
                    <div class="support-section">
                        <div class="support-title">Need Help?</div>
                        <div class="support-grid">
                            <div class="support-card">
                                <div class="support-icon"><i class="fas fa-phone"></i></div>
                                <h4>Phone</h4>
                                <p>07039837749</p>
                            </div>
                            <div class="support-card">
                                <div class="support-icon"><i class="fab fa-whatsapp"></i></div>
                                <h4>WhatsApp</h4>
                                <p>08082775076</p>
                            </div>
                            <div class="support-card">
                                <div class="support-icon"><i class="fas fa-envelope"></i></div>
                                <h4>Email</h4>
                                <p>admissions@fctcns.edu.ng</p>
                            </div>
                        </div>
                    </div>

                </div><!-- /card-body -->
            </div><!-- /card -->

        </div><!-- /main-container -->

        <!-- Hidden iframe for download -->
        <iframe id="downloadFrame" style="display:none;"></iframe>

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
            const downloadFrame = document.getElementById('downloadFrame');

            // ── Toast Notification ───────────────────────────────────
            function showToast(message, type = 'info') {
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
                }, 4000);
            }

            // ─── Fixed Download Function ─────────────────────────────
            function triggerDownload(btn) {
                if (!slipNumber) {
                    showToast('Exam slip not available', 'error');
                    return;
                }

                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
                btn.disabled = true;

                showToast('Preparing your PDF...', 'info');

                // Create download URL with timestamp to prevent caching
                const downloadUrl = baseUrl + '/apply/download-exam-slip?csrf=' + encodeURIComponent(csrfToken) + '&t=' + Date.now();
                
                // Use iframe for download
                if (downloadFrame) {
                    // Set up one-time load event
                    const onLoad = function() {
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                            showToast('Download started', 'success');
                        }, 1000);
                        downloadFrame.removeEventListener('load', onLoad);
                    };
                    
                    downloadFrame.addEventListener('load', onLoad);
                    downloadFrame.src = downloadUrl;
                } else {
                    // Fallback
                    window.location.href = downloadUrl;
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 1000);
                }
            }

            // ─── Open Print View ─────────────────────────────────────
            function openPrintView() {
                if (!slipNumber) {
                    showToast('Exam slip not available', 'error');
                    return;
                }

                const printWindow = window.open(
                    baseUrl + '/apply/print-exam-slip?csrf=' + encodeURIComponent(csrfToken) + '&t=' + Date.now(),
                    'PrintExamSlip',
                    'width=900,height=700,scrollbars=yes,resizable=yes'
                );

                if (!printWindow) {
                    showToast('Please allow pop-ups to view the exam slip', 'error');
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
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    if (viewPrintBtn) {
                        openPrintView();
                    }
                }
            });

            // ── Check if slip was recently generated ─────────────────
            <?php if (isset($_GET['new']) && $_GET['new'] == 1): ?>
            showToast('Your examination slip has been generated!', 'success');
            <?php endif; ?>

            console.log('Step 4 ready - Slip:', slipNumber);

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