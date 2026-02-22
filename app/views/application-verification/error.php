<?php
/**
 * Verification Error View
 * UPDATED: Purple color scheme matching JAMB verification page
 * 
 * @var string $errorCode
 * @var string $errorMessage
 * @var array $suggestions
 * @var string $institution_name
 * @var string $support_email
 */

// =========================================================
// FIX: Add require for SecurityHelper and SecurityTrait
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class VerificationErrorView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        // Default suggestions if not provided
        $suggestions = $suggestions ?? [
            'Check that the slip number or QR code is entered correctly',
            'Ensure the examination slip is from the current academic session',
            'Verify that the slip has not expired',
            'Try using another verification method (e.g., JAMB number)',
            'Contact the admissions office if the problem persists'
        ];

        $errorCode = $errorCode ?? 'ERR_VERIFICATION_FAILED';
        $errorMessage = $errorMessage ?? 'The provided verification information could not be validated.';
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
            
            <title><?php echo $this->e($pageTitle ?? 'Verification Error'); ?></title>
            
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
                    --sv1-danger:        #ef4444;
                    --sv1-danger-light:  #fee2e2;
                    --sv1-warning:       #f59e0b;
                    --sv1-warning-light: #fef3c7;
                    --sv1-border:        #E9EDF2;
                    --sv1-text-dark:     #1A1F2E;
                    --sv1-text-muted:    #6B7280;
                }
                
                body {
                    font-family: 'Inter', sans-serif;
                    background: linear-gradient(135deg, var(--sv1-primary-soft) 0%, #ffffff 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    padding: 20px;
                }
                
                .error-card {
                    border: none;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 20px 60px rgba(107,78,155,0.2);
                    animation: shake 0.5s ease-in-out;
                    max-width: 600px;
                    margin: 0 auto;
                    border: 1px solid var(--sv1-border);
                }
                
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                    20%, 40%, 60%, 80% { transform: translateX(5px); }
                }
                
                .error-header {
                    background: linear-gradient(135deg, var(--sv1-danger) 0%, #b91c1c 100%);
                    color: white;
                    padding: 40px 30px;
                    text-align: center;
                    position: relative;
                    overflow: hidden;
                }
                
                .error-header::before {
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
                
                .error-icon {
                    width: 80px;
                    height: 80px;
                    background: rgba(255,255,255,0.2);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 20px;
                    font-size: 40px;
                    border: 2px solid rgba(255,255,255,0.3);
                    animation: pulse 2s infinite;
                }
                
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                    100% { transform: scale(1); }
                }
                
                .error-body {
                    padding: 40px;
                    background: white;
                }
                
                .error-message {
                    font-size: 18px;
                    color: var(--sv1-danger);
                    margin-bottom: 20px;
                    text-align: center;
                    padding: 15px;
                    background: var(--sv1-danger-light);
                    border-left: 4px solid var(--sv1-danger);
                    border-radius: 10px;
                }
                
                .suggestions-box {
                    background: var(--sv1-warning-light);
                    border-left: 4px solid var(--sv1-warning);
                    border-radius: 10px;
                    padding: 20px;
                    margin: 20px 0;
                }
                
                .suggestion-item {
                    padding: 8px 0;
                    border-bottom: 1px dashed var(--sv1-warning);
                    color: #92400e;
                }
                
                .suggestion-item:last-child {
                    border-bottom: none;
                }
                
                .suggestion-item i {
                    color: var(--sv1-warning);
                }
                
                .action-buttons {
                    display: flex;
                    gap: 15px;
                    justify-content: center;
                    margin-top: 30px;
                }
                
                .btn-action {
                    padding: 12px 30px;
                    border-radius: 50px;
                    font-weight: 600;
                    text-decoration: none;
                    transition: all 0.3s ease;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                }
                
                .btn-primary {
                    background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                    color: white;
                    box-shadow: 0 4px 12px rgba(107,78,155,0.3);
                }
                
                .btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 30px rgba(107,78,155,0.4);
                }
                
                .btn-outline-primary {
                    border: 2px solid var(--sv1-primary);
                    color: var(--sv1-primary);
                    background: transparent;
                }
                
                .btn-outline-primary:hover {
                    background: var(--sv1-primary);
                    color: white;
                    transform: translateY(-2px);
                }
                
                .error-footer {
                    background: var(--sv1-primary-soft);
                    padding: 20px;
                    border-top: 1px solid var(--sv1-border);
                    text-align: center;
                }
                
                .error-footer a {
                    color: var(--sv1-primary);
                    text-decoration: none;
                    font-weight: 500;
                }
                
                .error-footer a:hover {
                    color: var(--sv1-primary-dark);
                    text-decoration: underline;
                }
                
                .text-warning {
                    color: var(--sv1-warning) !important;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="error-card">
                    <div class="error-header">
                        <div class="error-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h2 class="h3 mb-2">Verification Failed</h2>
                        <div class="small opacity-75">
                            Error Code: <?php echo $this->e($errorCode); ?>
                        </div>
                    </div>
                    
                    <div class="error-body">
                        <div class="error-message">
                            <i class="fas fa-times-circle me-2"></i>
                            <?php echo $this->e($errorMessage); ?>
                        </div>
                        
                        <div class="suggestions-box">
                            <h6 class="mb-3 text-warning">
                                <i class="fas fa-lightbulb me-2"></i> Possible Solutions:
                            </h6>
                            <?php foreach ($suggestions as $suggestion): ?>
                            <div class="suggestion-item">
                                <i class="fas fa-arrow-right me-2 text-warning"></i>
                                <?php echo $this->e($suggestion); ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="/application-verify/portal" class="btn-action btn-primary">
                                <i class="fas fa-redo-alt"></i> Try Again
                            </a>
                            <a href="javascript:history.back()" class="btn-action btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Go Back
                            </a>
                        </div>
                    </div>
                    
                    <div class="error-footer">
                        <p class="small text-muted mb-0">
                            <strong><?php echo $this->e($institution_name ?? 'FCT College of Nursing Sciences'); ?></strong><br>
                            Need help? Contact: <a href="mailto:<?php echo $this->e($support_email); ?>"><?php echo $this->e($support_email); ?></a>
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
                // Log error details for analytics
                document.addEventListener('DOMContentLoaded', function() {
                    console.error('Verification Error:', {
                        errorCode: '<?php echo $this->e($errorCode); ?>',
                        errorMessage: '<?php echo $this->e($errorMessage); ?>',
                        timestamp: new Date().toISOString(),
                        url: window.location.href,
                        userAgent: navigator.userAgent
                    });
                });
                
                // Handle back button with fallback
                document.querySelector('a[href="javascript:history.back()"]')?.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (document.referrer) {
                        history.back();
                    } else {
                        window.location.href = '/application-verify/portal';
                    }
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
$view = new VerificationErrorView();
$view->render(get_defined_vars());
?>