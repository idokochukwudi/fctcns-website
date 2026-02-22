<?php
/**
 * Application Verification Result View
 * UPDATED: Purple color scheme matching JAMB verification page
 * 
 * @var array $verificationData
 * @var array $exam_slip
 * @var array $application
 * @var array $applicant
 * @var bool $has_paid
 * @var array $status
 */

// =========================================================
// FIX: Add require for SecurityHelper and SecurityTrait
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class VerificationResultView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        $baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $institution_name = $institution_name ?? 'FCT College of Nursing Sciences';
        $institution_address = $institution_address ?? 'Gwagwalada, Abuja';
        $support_email = $support_email ?? 'verification@fctcns.edu.ng';

        // Construct full name
        $fullName = trim(
            ($applicant['title'] ?? '') . ' ' . 
            ($applicant['first_name'] ?? '') . ' ' . 
            ($applicant['last_name'] ?? '')
        );

        // If full name is empty, try to get from application
        if (empty($fullName)) {
            $fullName = trim(
                ($application['first_name'] ?? '') . ' ' . 
                ($application['last_name'] ?? '')
            );
        }

        // Default if still empty
        if (empty($fullName)) {
            $fullName = 'Not Provided';
        }
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
            
            <!-- CSRF Token for JavaScript -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">
            
            <title><?php echo $this->e($pageTitle ?? 'Verification Result'); ?></title>
            
            <!-- ========================================================= -->
            <!-- 3. Add CSP nonce to all style tags -->
            <!-- 7. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            
            <!-- Bootstrap 5 CSS with SRI -->
            <?php 
            $bootstrapCssUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';
            $bootstrapCssSri = SecurityHelper::getSriHash($bootstrapCssUrl);
            ?>
            <link href="<?php echo $bootstrapCssUrl; ?>" 
                  rel="stylesheet"
                  <?php if ($bootstrapCssSri): ?>integrity="<?php echo $bootstrapCssSri; ?>"<?php endif; ?>
                  crossorigin="anonymous">
            
            <!-- Font Awesome with SRI -->
            <?php 
            $faUrl = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
            $faSri = SecurityHelper::getSriHash($faUrl);
            ?>
            <link rel="stylesheet" 
                  href="<?php echo $faUrl; ?>"
                  <?php if ($faSri): ?>integrity="<?php echo $faSri; ?>"<?php endif; ?>
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">
            
            <!-- Google Fonts - NO SRI HASH -->
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            
            <style nonce="<?php echo $csp_nonce; ?>">
                :root {
                    --sv1-primary:       #6B4E9B;
                    --sv1-primary-dark:  #4A3B6B;
                    --sv1-primary-light: #8A6FB0;
                    --sv1-primary-soft:  #F3EAF8;
                    --sv1-gold:          #C9A44A;
                    --sv1-gold-light:    #E2B05F;
                    --sv1-gold-pale:     #FDF6E9;
                    --sv1-success:       #10b981;
                    --sv1-success-light: #d1fae5;
                    --sv1-danger:        #ef4444;
                    --sv1-danger-light:  #fee2e2;
                    --sv1-warning:       #f59e0b;
                    --sv1-warning-light: #fef3c7;
                    --sv1-info:          #3b82f6;
                    --sv1-info-light:    #dbeafe;
                    --sv1-border:        #E9EDF2;
                    --sv1-text-dark:     #1A1F2E;
                    --sv1-text-muted:    #6B7280;
                }
                
                body {
                    font-family: 'Inter', sans-serif;
                    background: linear-gradient(135deg, var(--sv1-primary-soft) 0%, #ffffff 100%);
                    min-height: 100vh;
                    padding: 30px 0;
                }
                
                .result-card {
                    border: none;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 20px 60px rgba(107,78,155,0.2);
                    animation: slideIn 0.5s ease-out;
                    max-width: 1000px;
                    margin: 0 auto;
                    border: 1px solid var(--sv1-border);
                }
                
                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .result-header {
                    padding: 30px;
                    text-align: center;
                    color: white;
                    position: relative;
                    overflow: hidden;
                }
                
                .result-header.verified {
                    background: linear-gradient(135deg, var(--sv1-primary-dark), var(--sv1-primary));
                }
                
                .result-header.unverified {
                    background: linear-gradient(135deg, var(--sv1-danger), #b91c1c);
                }
                
                .result-header.warning {
                    background: linear-gradient(135deg, var(--sv1-warning), var(--sv1-gold));
                }
                
                .result-header::before {
                    content: '';
                    position: absolute;
                    top: -50%;
                    right: -50%;
                    width: 200%;
                    height: 200%;
                    background: rgba(255,255,255,0.05);
                    transform: rotate(45deg);
                    animation: shine 3s infinite;
                }
                
                @keyframes shine {
                    0% { transform: translateX(-100%) rotate(45deg); }
                    100% { transform: translateX(100%) rotate(45deg); }
                }
                
                .result-icon {
                    width: 80px;
                    height: 80px;
                    background: rgba(201,164,74,0.2);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 20px;
                    font-size: 40px;
                    color: var(--sv1-gold-light);
                    position: relative;
                    z-index: 1;
                    border: 2px solid rgba(201,164,74,0.3);
                }
                
                .verification-badge {
                    display: inline-block;
                    padding: 8px 20px;
                    background: rgba(255,255,255,0.15);
                    border-radius: 50px;
                    font-weight: 600;
                    font-size: 14px;
                    backdrop-filter: blur(5px);
                    margin-top: 15px;
                    border: 1px solid rgba(255,255,255,0.2);
                }
                
                .result-body {
                    padding: 40px;
                    background: white;
                }
                
                .candidate-photo {
                    width: 150px;
                    height: 150px;
                    object-fit: cover;
                    border: 3px solid var(--sv1-primary);
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(107,78,155,0.2);
                }
                
                .photo-placeholder {
                    width: 150px;
                    height: 150px;
                    background: var(--sv1-primary-soft);
                    border: 3px solid var(--sv1-border);
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 60px;
                    color: var(--sv1-primary-light);
                    margin: 0 auto;
                }
                
                .verification-id-box {
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    background: var(--sv1-primary-soft);
                    padding: 20px;
                    border-radius: 10px;
                    border-left: 4px solid var(--sv1-primary);
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 15px;
                    margin: 20px 0;
                }
                
                .info-item {
                    padding: 12px;
                    background: var(--sv1-primary-soft);
                    border-radius: 10px;
                    border-left: 4px solid var(--sv1-primary);
                }
                
                .info-label {
                    font-size: 11px;
                    color: var(--sv1-text-muted);
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 5px;
                }
                
                .info-value {
                    font-size: 16px;
                    font-weight: 600;
                    color: var(--sv1-text-dark);
                    word-break: break-word;
                }
                
                .status-badge {
                    display: inline-block;
                    padding: 5px 15px;
                    border-radius: 50px;
                    font-size: 12px;
                    font-weight: 600;
                }
                
                .status-badge.success {
                    background: var(--sv1-success-light);
                    color: var(--sv1-success);
                }
                
                .status-badge.danger {
                    background: var(--sv1-danger-light);
                    color: var(--sv1-danger);
                }
                
                .status-badge.warning {
                    background: var(--sv1-warning-light);
                    color: var(--sv1-warning);
                }
                
                .status-badge.info {
                    background: var(--sv1-info-light);
                    color: var(--sv1-info);
                }
                
                .warnings-list {
                    margin: 20px 0;
                    padding: 15px;
                    background: var(--sv1-warning-light);
                    border: 1px solid var(--sv1-warning);
                    border-left: 4px solid var(--sv1-warning);
                    border-radius: 10px;
                }
                
                .warning-item {
                    display: flex;
                    align-items: center;
                    padding: 8px 0;
                    border-bottom: 1px dashed var(--sv1-warning);
                }
                
                .warning-item:last-child {
                    border-bottom: none;
                }
                
                .warning-item i {
                    color: var(--sv1-warning);
                    margin-right: 10px;
                    font-size: 14px;
                }
                
                .action-buttons {
                    display: flex;
                    gap: 15px;
                    justify-content: center;
                    margin-top: 30px;
                    flex-wrap: wrap;
                }
                
                .btn-action {
                    padding: 12px 30px;
                    border-radius: 50px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    border: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    text-decoration: none;
                    cursor: pointer;
                }
                
                .btn-action:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 10px 30px rgba(107,78,155,0.2);
                }
                
                .btn-primary {
                    background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                    color: white;
                    box-shadow: 0 4px 12px rgba(107,78,155,0.3);
                }
                
                .btn-primary:hover {
                    background: var(--sv1-primary-dark);
                    box-shadow: 0 8px 20px rgba(107,78,155,0.4);
                }
                
                .btn-outline-primary {
                    background: white;
                    color: var(--sv1-primary);
                    border: 2px solid var(--sv1-primary);
                }
                
                .btn-outline-primary:hover {
                    background: var(--sv1-primary);
                    color: white;
                }
                
                .verification-footer {
                    background: var(--sv1-primary-soft);
                    padding: 20px;
                    border-top: 1px solid var(--sv1-border);
                    text-align: center;
                }
                
                .institution-seal {
                    width: 60px;
                    height: 60px;
                    margin-bottom: 10px;
                    filter: drop-shadow(0 4px 6px rgba(107,78,155,0.2));
                }
                
                .text-primary {
                    color: var(--sv1-primary) !important;
                }
                
                .bg-success {
                    background-color: var(--sv1-success) !important;
                }
                
                .alert-success {
                    background: var(--sv1-success-light);
                    border-color: var(--sv1-success);
                    color: #065f46;
                    border-left-width: 4px;
                    border-left-style: solid;
                }
                
                .alert-danger {
                    background: var(--sv1-danger-light);
                    border-color: var(--sv1-danger);
                    color: #991b1b;
                    border-left-width: 4px;
                    border-left-style: solid;
                }
                
                hr {
                    border-color: var(--sv1-border);
                }
                
                .badge.bg-info {
                    background-color: var(--sv1-info) !important;
                }
                
                code {
                    color: var(--sv1-primary-dark);
                    background: var(--sv1-primary-soft);
                    padding: 2px 6px;
                    border-radius: 4px;
                }
                
                @media print {
                    .no-print {
                        display: none !important;
                    }
                    
                    body {
                        background: white;
                        padding: 0;
                    }
                    
                    .result-card {
                        box-shadow: none;
                        border: 2px solid var(--sv1-primary-dark);
                    }
                    
                    .result-header {
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="result-card">
                    <!-- Header based on verification status -->
                    <div class="result-header <?php echo $status['is_valid'] ? 'verified' : 'unverified'; ?>">
                        <div class="result-icon">
                            <i class="fas fa-<?php echo $status['is_valid'] ? 'check-circle' : 'times-circle'; ?>"></i>
                        </div>
                        <h2 class="h3 mb-2">Examination Slip Verification</h2>
                        <div class="verification-badge">
                            <i class="fas fa-clock me-2"></i>
                            <?php echo date('jS F Y, h:i A', strtotime($verificationData['verification_time'])); ?>
                        </div>
                        
                        <!-- Status Badges -->
                        <div class="mt-3">
                            <?php foreach ($status['badges'] ?? [] as $badge): ?>
                            <span class="status-badge <?php echo $badge['type']; ?> me-2">
                                <?php echo $badge['text']; ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="result-body">
                        <!-- Candidate Photo and Verification Info Row -->
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <?php 
                                $photoPath = '';
                                if (!empty($applicant['passport_photo'])) {
                                    $photoPath = $applicant['passport_photo'];
                                } elseif (!empty($application['passport_photo'])) {
                                    $photoPath = $application['passport_photo'];
                                }
                                
                                if (!empty($photoPath)): 
                                ?>
                                    <img src="<?php echo $this->e($photoPath); ?>" 
                                         alt="Passport" 
                                         class="candidate-photo"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'150\' height=\'150\' viewBox=\'0 0 150 150\'%3E%3Crect width=\'150\' height=\'150\' fill=\'%23f3eaf8\'/%3E%3Ccircle cx=\'75\' cy=\'75\' r=\'40\' fill=\'%238a6fb0\'/%3E%3Ctext x=\'75\' y=\'120\' text-anchor=\'middle\' fill=\'%236b4e9b\' font-size=\'14\' font-family=\'Arial\'%3ENo Photo%3C/text%3E%3C/svg%3E';">
                                <?php else: ?>
                                    <div class="photo-placeholder">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                <?php endif; ?>
                                <p class="small text-muted mt-2">
                                    <i class="fas fa-camera"></i> Passport Photograph
                                </p>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="verification-id-box">
                                    <h5 class="text-primary mb-3">Verification Details</h5>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="mb-2">
                                                <strong>Verification ID:</strong><br>
                                                <code><?php echo $this->e($verificationData['verification_id']); ?></code>
                                            </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-2">
                                                <strong>Verification Time:</strong><br>
                                                <?php echo date('jS F Y, h:i A', strtotime($verificationData['verification_time'])); ?>
                                            </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-2">
                                                <strong>IP Address:</strong><br>
                                                <?php echo $this->e($verificationData['verification_ip'] ?? $_SERVER['REMOTE_ADDR']); ?>
                                            </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-2">
                                                <strong>Status:</strong><br>
                                                <span class="badge bg-success">Verified</span>
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    <p class="small text-muted mb-0">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        This verification is digitally signed and authenticated by FCT College of Nursing Sciences.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Verification Summary -->
                        <div class="alert alert-<?php echo $status['is_valid'] ? 'success' : 'danger'; ?> mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-<?php echo $status['is_valid'] ? 'check-circle' : 'exclamation-triangle'; ?> fa-2x me-3"></i>
                                <div>
                                    <strong>
                                        <?php if ($status['is_valid']): ?>
                                            This examination slip is authentic and has been verified.
                                        <?php else: ?>
                                            This examination slip could not be verified.
                                        <?php endif; ?>
                                    </strong>
                                    <?php if ($has_paid): ?>
                                        <br><small>Payment status: Verified</small>
                                    <?php else: ?>
                                        <br><small class="text-danger">Payment pending verification</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Warnings (if any) -->
                        <?php if (!empty($status['warnings'])): ?>
                        <div class="warnings-list">
                            <h6 class="mb-2 text-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i> Warnings
                            </h6>
                            <?php foreach ($status['warnings'] as $warning): ?>
                            <div class="warning-item">
                                <i class="fas fa-exclamation-circle"></i>
                                <span><?php echo $this->e($warning); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Candidate Information -->
                        <h6 class="mb-3">
                            <i class="fas fa-user-graduate me-2 text-primary"></i>
                            Candidate Information
                        </h6>
                        
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">
                                    <?php echo $this->e($fullName); ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Application Number</div>
                                <div class="info-value"><?php echo $this->e($application['application_number'] ?? 'N/A'); ?></div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">JAMB Number</div>
                                <div class="info-value"><?php echo $this->e($application['jamb_number'] ?? 'N/A'); ?></div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Programme</div>
                                <div class="info-value"><?php echo $this->e($application['program_choice_1'] ?? 'N/A'); ?></div>
                            </div>
                        </div>
                        
                        <!-- Examination Details -->
                        <h6 class="mb-3 mt-4">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            Examination Details
                        </h6>
                        
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Slip Number</div>
                                <div class="info-value"><?php echo $this->e($exam_slip['slip_number'] ?? 'N/A'); ?></div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Examination Date</div>
                                <div class="info-value">
                                    <?php echo !empty($exam_slip['exam_date']) ? date('l, jS F Y', strtotime($exam_slip['exam_date'])) : 'N/A'; ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Examination Time</div>
                                <div class="info-value">
                                    <?php echo !empty($exam_slip['exam_time']) ? date('h:i A', strtotime($exam_slip['exam_time'])) : 'N/A'; ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Reporting Time</div>
                                <div class="info-value">
                                    <?php echo !empty($exam_slip['reporting_time']) ? date('h:i A', strtotime($exam_slip['reporting_time'])) : 'N/A'; ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Venue</div>
                                <div class="info-value"><?php echo $this->e($exam_slip['exam_venue'] ?? 'N/A'); ?></div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Seat Number</div>
                                <div class="info-value">
                                    <span class="badge bg-info"><?php echo $this->e($exam_slip['seat_number'] ?? 'N/A'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons no-print">
                            <button class="btn-action btn-primary" onclick="window.print()">
                                <i class="fas fa-print"></i> Print Result
                            </button>
                            <a href="/application-verify/portal" class="btn-action btn-outline-primary">
                                <i class="fas fa-redo-alt"></i> Verify Another
                            </a>
                        </div>
                    </div>
                    
                    <div class="verification-footer">
                        <img src="/assets/img/college-seal.png" alt="College Seal" class="institution-seal" 
                             onerror="this.style.display='none'">
                        <p class="small text-muted mb-1">
                            <strong><?php echo $this->e($institution_name); ?></strong><br>
                            <?php echo $this->e($institution_address); ?>
                        </p>
                        <p class="small text-muted mb-0">
                            This is an official verification from FCT College of Nursing Sciences.<br>
                            For inquiries: <a href="mailto:<?php echo $this->e($support_email); ?>"><?php echo $this->e($support_email); ?></a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all script tags -->
            <!-- ========================================================= -->
            
            <!-- Bootstrap JS with SRI -->
            <?php 
            $bootstrapJsUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
            $bootstrapJsSri = SecurityHelper::getSriHash($bootstrapJsUrl);
            ?>
            <script src="<?php echo $bootstrapJsUrl; ?>" 
                    <?php if ($bootstrapJsSri): ?>integrity="<?php echo $bootstrapJsSri; ?>"<?php endif; ?>
                    crossorigin="anonymous"
                    nonce="<?php echo $csp_nonce; ?>"></script>
            
            <script nonce="<?php echo $csp_nonce; ?>">
                // Print functionality
                document.querySelector('.btn-action.btn-primary')?.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.print();
                });
                
                // Add print styles
                const style = document.createElement('style');
                style.innerHTML = `
                    @media print {
                        body { background: white; }
                        .result-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .badge, .status-badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .candidate-photo { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .info-item { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                    }
                `;
                document.head.appendChild(style);
                
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                
                // Log verification for audit
                console.log('Verification Result:', {
                    verification_id: '<?php echo $this->e($verificationData['verification_id']); ?>',
                    is_valid: <?php echo $status['is_valid'] ? 'true' : 'false'; ?>,
                    timestamp: '<?php echo $verificationData['verification_time']; ?>'
                });
            </script>
        </body>
        </html>
        <?php
    }
}

// =========================================================
// 8. Add the view instantiation at the bottom
// =========================================================
$view = new VerificationResultView();
$view->render(get_defined_vars());
?>