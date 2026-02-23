<?php
/**
 * Step 4 View - Exam Slip
 * Redesigned: Premium institutional design with security enhancements
 * FIXED: Button responsiveness, download functionality, and CSP compliance
 * FIXED: Dashboard commented out
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

                /* ─── Navigation ─────────────────────────────────── */
                .nav-bar {
                    background: var(--white);
                    border-bottom: 1px solid var(--border);
                    padding: 0.75rem 2rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    box-shadow: var(--shadow-sm);
                    position: sticky;
                    top: 0;
                    z-index: 100;
                }

                .nav-brand {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                }

                .nav-logo {
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

                .nav-title {
                    font-weight: 600;
                    color: var(--primary-dark);
                }

                .nav-links {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                }

                .nav-link {
                    color: var(--grey-5);
                    text-decoration: none;
                    font-size: 0.9rem;
                    padding: 0.5rem 1rem;
                    border-radius: var(--radius-sm);
                    transition: all 0.2s;
                }

                .nav-link:hover {
                    background: var(--primary-soft);
                    color: var(--primary);
                }

                .nav-link i {
                    margin-right: 0.5rem;
                    font-size: 0.85rem;
                }

                .user-badge {
                    background: var(--primary-soft);
                    padding: 0.5rem 1rem;
                    border-radius: var(--radius-sm);
                    font-size: 0.85rem;
                    color: var(--primary-dark);
                    font-weight: 500;
                }

                .user-badge i {
                    margin-right: 0.5rem;
                    color: var(--primary);
                }

                /* ─── Main Container ─────────────────────────────── */
                .main-container {
                    max-width: 1200px;
                    margin: 2rem auto;
                    padding: 0 2rem;
                }

                /* ─── Success Banner ─────────────────────────────── */
                .success-banner {
                    background: linear-gradient(135deg, var(--green), #0d9488);
                    border-radius: var(--radius-lg);
                    padding: 2rem;
                    color: white;
                    margin-bottom: 2rem;
                    display: flex;
                    align-items: center;
                    gap: 1.5rem;
                    box-shadow: var(--shadow-lg);
                }

                .success-icon {
                    width: 64px;
                    height: 64px;
                    background: rgba(255,255,255,0.2);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 2rem;
                }

                .success-content {
                    flex: 1;
                }

                .success-title {
                    font-size: 1.8rem;
                    font-weight: 700;
                    margin-bottom: 0.5rem;
                    font-family: var(--font-display);
                }

                .success-message {
                    opacity: 0.9;
                    font-size: 1rem;
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
                    padding: 2rem 2.5rem;
                    color: white;
                }

                .card-header h1 {
                    font-family: var(--font-display);
                    font-size: 2rem;
                    font-weight: 400;
                    margin-bottom: 0.5rem;
                }

                .card-header p {
                    opacity: 0.8;
                    font-size: 0.95rem;
                }

                .card-body {
                    padding: 2.5rem;
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
                    gap: 1.5rem;
                    margin-bottom: 2rem;
                }

                .info-item {
                    background: var(--primary-soft);
                    border-radius: var(--radius-md);
                    padding: 1.5rem;
                }

                .info-label {
                    font-size: 0.75rem;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    color: var(--grey-4);
                    margin-bottom: 0.5rem;
                }

                .info-value {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: var(--primary-dark);
                }

                .info-value i {
                    color: var(--gold);
                    margin-right: 0.5rem;
                }

                /* ─── O'Level Summary ────────────────────────────── */
                .olevel-summary {
                    background: var(--primary-soft);
                    border-radius: var(--radius-lg);
                    padding: 1.5rem;
                    margin-bottom: 2rem;
                }

                .summary-title {
                    font-size: 1rem;
                    font-weight: 600;
                    color: var(--primary-dark);
                    margin-bottom: 1rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .subject-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 1rem;
                }

                .subject-item {
                    background: var(--white);
                    border-radius: var(--radius-sm);
                    padding: 0.75rem;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .subject-name {
                    font-size: 0.85rem;
                    color: var(--grey-5);
                }

                .subject-grade {
                    font-weight: 600;
                    padding: 0.25rem 0.5rem;
                    border-radius: 4px;
                    font-size: 0.8rem;
                }

                .grade-A1, .grade-B2, .grade-B3 {
                    background: #2e7d32;
                    color: white;
                }

                .grade-C4, .grade-C5, .grade-C6 {
                    background: #f57c00;
                    color: white;
                }

                .grade-D7, .grade-E8, .grade-F9 {
                    background: #c62828;
                    color: white;
                }

                .credit-badge {
                    background: var(--green);
                    color: white;
                    font-size: 0.7rem;
                    padding: 0.2rem 0.5rem;
                    border-radius: 12px;
                    margin-left: 0.5rem;
                }

                /* ─── Action Buttons ─────────────────────────────── */
                .action-buttons {
                    display: flex;
                    gap: 1rem;
                    flex-wrap: wrap;
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

                .btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(107,78,155,0.4);
                }

                .btn-success {
                    background: var(--green);
                    color: white;
                    box-shadow: 0 4px 12px rgba(16,185,129,0.3);
                }

                .btn-success:hover {
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

                /* ─── Support Section ────────────────────────────── */
                .support-section {
                    margin-top: 2rem;
                    padding-top: 2rem;
                    border-top: 1px solid var(--border);
                }

                .support-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 1rem;
                }

                .support-card {
                    background: var(--primary-soft);
                    border-radius: var(--radius-md);
                    padding: 1.5rem;
                    text-align: center;
                }

                .support-icon {
                    width: 48px;
                    height: 48px;
                    background: var(--white);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 1rem;
                    color: var(--primary);
                    font-size: 1.2rem;
                }

                .support-card h4 {
                    color: var(--primary-dark);
                    margin-bottom: 0.5rem;
                    font-size: 1rem;
                }

                .support-card p {
                    color: var(--grey-5);
                    font-size: 0.9rem;
                }

                /* ─── Toast Notifications ────────────────────────── */
                .toast-notification {
                    position: fixed;
                    top: 20px;
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
                    .nav-bar {
                        padding: 0.75rem 1rem;
                        flex-direction: column;
                        gap: 1rem;
                    }
                    
                    .nav-links {
                        width: 100%;
                        justify-content: center;
                    }
                    
                    .main-container {
                        padding: 0 1rem;
                    }
                    
                    .card-header {
                        padding: 1.5rem;
                    }
                    
                    .card-header h1 {
                        font-size: 1.5rem;
                    }
                    
                    .card-body {
                        padding: 1.5rem;
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
                    
                    .subject-grid {
                        grid-template-columns: 1fr;
                    }
                }

                @media (max-width: 480px) {
                    .success-banner {
                        flex-direction: column;
                        text-align: center;
                    }
                    
                    .nav-links {
                        flex-wrap: wrap;
                    }
                }
            </style>
        </head>
        <body>

        <!-- ─── Navigation ─────────────────────────────────────────── -->
        <nav class="nav-bar">
            <div class="nav-brand">
                <div class="nav-logo">CNS</div>
                <span class="nav-title">FCT College of Nursing Sciences</span>
            </div>
            <div class="nav-links">
                <!-- Dashboard commented out for now 
                <a href="/dashboard" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                -->
                <a href="/apply/step/1" class="nav-link">
                    <i class="fas fa-file-alt"></i> Application
                </a>
                <a href="/applicant/logout" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <span class="user-badge">
                    <i class="fas fa-user-circle"></i> <?php echo $this->e($applicant_name); ?>
                </span>
            </div>
        </nav>

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
                    <div class="success-message">Your application fee has been confirmed. Your examination slip is now available below.</div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card">
                <div class="card-header">
                    <h1>Examination Slip</h1>
                    <p>Download and print your examination slip for the screening exercise</p>
                </div>
                <div class="card-body">

                    <!-- Exam Slip Preview -->
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

                    <!-- O'Level Results Summary (if available) -->
                    <?php if (!empty($olevel_results)): ?>
                    <div class="olevel-summary">
                        <div class="summary-title">
                            <i class="fas fa-graduation-cap"></i>
                            O'Level Results Summary
                        </div>
                        <div class="subject-grid">
                            <?php
                            $requiredSubjects = ['english', 'mathematics', 'biology', 'chemistry', 'physics'];
                            $creditGrades = ['A1', 'B2', 'B3', 'C4', 'C5', 'C6'];
                            
                            // Get best grades across sittings
                            $bestGrades = [];
                            $gradeOrder = ['A1','B2','B3','C4','C5','C6','D7','E8','F9'];
                            
                            foreach ($olevel_results as $sitting) {
                                foreach ($requiredSubjects as $subject) {
                                    $gradeKey = $subject . '_grade';
                                    if (!empty($sitting[$gradeKey])) {
                                        $grade = $sitting[$gradeKey];
                                        if (!isset($bestGrades[$subject]) || 
                                            array_search($grade, $gradeOrder) < array_search($bestGrades[$subject], $gradeOrder)) {
                                            $bestGrades[$subject] = $grade;
                                        }
                                    }
                                }
                            }
                            
                            $subjectLabels = [
                                'english' => 'English',
                                'mathematics' => 'Mathematics',
                                'biology' => 'Biology',
                                'chemistry' => 'Chemistry',
                                'physics' => 'Physics'
                            ];
                            
                            foreach ($bestGrades as $subject => $grade):
                                $isCredit = in_array($grade, $creditGrades);
                                $gradeClass = str_replace(['1','2','3','4','5','6','7','8','9'], '', $grade);
                            ?>
                            <div class="subject-item">
                                <span class="subject-name"><?php echo $subjectLabels[$subject]; ?></span>
                                <span class="subject-grade grade-<?php echo $gradeClass; ?>">
                                    <?php echo $grade; ?>
                                    <?php if ($isCredit): ?>
                                        <span class="credit-badge">✓</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

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

                    <!-- Important Notes -->
                    <div style="margin-top: 2rem; padding: 1rem; background: var(--orange-pale); border-radius: var(--radius-md); border-left: 4px solid var(--orange);">
                        <p style="display: flex; align-items: center; gap: 0.5rem; color: #92400e;">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Important:</strong> Please print your examination slip and bring it to the screening center. Digital copies may not be accepted.
                        </p>
                    </div>

                    <!-- Support Section -->
                    <div class="support-section">
                        <h3 style="text-align: center; margin-bottom: 2rem; color: var(--primary-dark);">Need Assistance?</h3>
                        <div class="support-grid">
                            <div class="support-card">
                                <div class="support-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <h4>Phone Support</h4>
                                <p>07039837749</p>
                                <p style="font-size: 0.8rem; color: var(--grey-4);">Mon-Fri, 9am-5pm</p>
                            </div>
                            <div class="support-card">
                                <div class="support-icon">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <h4>WhatsApp</h4>
                                <p>08082775076</p>
                                <p style="font-size: 0.8rem; color: var(--grey-4);">Quick response</p>
                            </div>
                            <div class="support-card">
                                <div class="support-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h4>Email</h4>
                                <p>admissions@fctcns.edu.ng</p>
                                <p style="font-size: 0.8rem; color: var(--grey-4);">24hr response</p>
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
                
                alertContainer.appendChild(toast);

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
                            timestamp: new Date().toISOString()
                        })
                    }).catch(err => console.error('Tracking failed:', err));
                }
            }

            // ── Open Print View ──────────────────────────────────────
            function openPrintView() {
                if (!slipNumber) {
                    showToast('Exam slip not available', 'error');
                    return;
                }

                trackEvent('exam_slip_view');
                
                // Open print view in new window
                const printWindow = window.open(
                    baseUrl + '/apply/print-exam-slip?csrf=' + encodeURIComponent(csrfToken) + '&t=' + Date.now(),
                    'PrintExamSlip',
                    'width=900,height=700,scrollbars=yes,resizable=yes'
                );

                if (!printWindow) {
                    showToast('Please allow pop-ups to view the exam slip', 'error');
                }
            }

            // ── Trigger Download ─────────────────────────────────────
            function triggerDownload(btn) {
                if (!slipNumber) {
                    showToast('Exam slip not available for download', 'error');
                    return;
                }

                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing...';
                btn.disabled = true;

                trackEvent('exam_slip_download');
                showToast('Preparing PDF for download...', 'info');

                // Create hidden iframe for download
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = baseUrl + '/apply/download-exam-slip?csrf=' + encodeURIComponent(csrfToken) + '&t=' + Date.now();

                iframe.onload = function() {
                    setTimeout(() => {
                        btn.innerHTML = orig;
                        btn.disabled = false;
                        showToast('Download started', 'success');
                        document.body.removeChild(iframe);
                    }, 1500);
                };

                iframe.onerror = function() {
                    btn.innerHTML = orig;
                    btn.disabled = false;
                    showToast('Download failed. Please try again.', 'error');
                    document.body.removeChild(iframe);
                };

                document.body.appendChild(iframe);
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
                timestamp: new Date().toISOString()
            });

            // Check if slip was recently generated
            <?php if (isset($_GET['new']) && $_GET['new'] == 1): ?>
            showToast('Your examination slip has been generated successfully!', 'success');
            <?php endif; ?>

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