<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Verification Error'); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6B4E9B;
            --secondary-color: #4A3B6E;
            --error-color: #dc3545;
            --warning-color: #ffc107;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        
        .error-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: shake 0.5s ease-in-out;
            max-width: 700px;
            margin: 0 auto;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .error-header {
            background: linear-gradient(135deg, var(--error-color) 0%, #c82333 100%);
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
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }
        
        .error-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 50px;
            animation: pulse 2s infinite;
            position: relative;
            z-index: 1;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .error-code {
            font-size: 14px;
            background: rgba(0,0,0,0.2);
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            margin-top: 15px;
        }
        
        .error-body {
            padding: 40px;
            background: white;
        }
        
        .error-message {
            font-size: 20px;
            font-weight: 600;
            color: #721c24;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .suggestions-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .suggestion-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #ffeeba;
        }
        
        .suggestion-item:last-child {
            border-bottom: none;
        }
        
        .suggestion-item i {
            color: #856404;
            margin-right: 15px;
            font-size: 18px;
        }
        
        .support-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }
        
        .support-card {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .support-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .support-card i {
            font-size: 30px;
            margin-bottom: 10px;
        }
        
        .support-card.whatsapp i { color: #25D366; }
        .support-card.phone i { color: #007bff; }
        .support-card.email i { color: #ea4335; }
        
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
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .debug-info {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            border: 1px solid #dee2e6;
        }
        
        .error-footer {
            background: #f8f9fa;
            padding: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
        }
        
        .floating-help {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .help-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 5px 20px rgba(107, 78, 155, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .help-button:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(107, 78, 155, 0.6);
        }
        
        @media (max-width: 768px) {
            .error-body {
                padding: 20px;
            }
            
            .support-options {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .support-options {
                grid-template-columns: 1fr;
            }
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
                <p class="mb-0 opacity-75">The document could not be verified</p>
                <div class="error-code">
                    Error Code: <?php echo htmlspecialchars($errorCode ?? 'UNKNOWN'); ?>
                </div>
            </div>
            
            <div class="error-body">
                <!-- Main Error Message -->
                <div class="error-message">
                    <i class="fas fa-times-circle me-2" style="color: var(--error-color);"></i>
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
                
                <!-- Possible Reasons -->
                <div class="suggestions-box">
                    <h6 class="mb-3 text-warning">
                        <i class="fas fa-lightbulb me-2"></i> Possible Solutions:
                    </h6>
                    <?php if (!empty($suggestions)): ?>
                        <?php foreach ($suggestions as $suggestion): ?>
                        <div class="suggestion-item">
                            <i class="fas fa-arrow-right"></i>
                            <span><?php echo htmlspecialchars($suggestion); ?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="suggestion-item">
                            <i class="fas fa-arrow-right"></i>
                            <span>Check that you entered the correct information</span>
                        </div>
                        <div class="suggestion-item">
                            <i class="fas fa-arrow-right"></i>
                            <span>Ensure the document has been properly generated</span>
                        </div>
                        <div class="suggestion-item">
                            <i class="fas fa-arrow-right"></i>
                            <span>Contact the admissions office for assistance</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Support Options -->
                <h6 class="mb-3 mt-4">
                    <i class="fas fa-headset me-2 text-primary"></i>
                    Need Immediate Assistance?
                </h6>
                
                <div class="support-options">
                    <div class="support-card whatsapp" onclick="window.open('https://wa.me/2347039837749', '_blank')">
                        <i class="fab fa-whatsapp"></i>
                        <h6>WhatsApp</h6>
                        <small class="text-muted">Chat with support</small>
                    </div>
                    
                    <div class="support-card phone" onclick="window.location.href='tel:07039837749'">
                        <i class="fas fa-phone-alt"></i>
                        <h6>Call Us</h6>
                        <small class="text-muted">07039837749</small>
                    </div>
                    
                    <div class="support-card email" onclick="window.location.href='mailto:verification@fctcns.edu.ng'">
                        <i class="fas fa-envelope"></i>
                        <h6>Email</h6>
                        <small class="text-muted">verification@fctcns.edu.ng</small>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="/application-verify/portal" class="btn-action btn-primary">
                        <i class="fas fa-redo-alt"></i> Try Again
                    </a>
                    <a href="javascript:history.back()" class="btn-action btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Go Back
                    </a>
                </div>
                
                <!-- Debug Information (only in debug mode) -->
                <?php if (!empty($debugInfo) && defined('APP_DEBUG') && APP_DEBUG): ?>
                <div class="debug-info">
                    <h6 class="mb-2">
                        <i class="fas fa-bug me-2"></i> Debug Information
                    </h6>
                    <pre class="mb-0" style="font-size: 11px;"><?php echo htmlspecialchars(print_r($debugInfo, true)); ?></pre>
                </div>
                <?php endif; ?>
                
                <!-- Additional Help -->
                <div class="text-center mt-4">
                    <p class="small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Response time: Within 24 hours for email support
                    </p>
                </div>
            </div>
            
            <div class="error-footer">
                <p class="small text-muted mb-0">
                    <strong><?php echo $institution_name ?? 'FCT College of Nursing Sciences'; ?></strong><br>
                    Verification Support: <a href="mailto:<?php echo $support_email ?? 'verification@fctcns.edu.ng'; ?>">
                        <?php echo $support_email ?? 'verification@fctcns.edu.ng'; ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Floating Help Button -->
    <div class="floating-help">
        <button class="help-button" onclick="showHelpOptions()">
            <i class="fas fa-question"></i>
        </button>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Show help options as toast
        function showHelpOptions() {
            const helpMessage = 'Contact Support:\n\n📞 Phone: 07039837749\n💬 WhatsApp: wa.me/2347039837749\n📧 Email: verification@fctcns.edu.ng';
            
            if (navigator.share) {
                navigator.share({
                    title: 'Need Help?',
                    text: 'Contact FCT College Verification Support',
                    url: 'https://fctcns.edu.ng/support'
                }).catch(() => {
                    alert(helpMessage);
                });
            } else {
                alert(helpMessage);
            }
        }
        
        // Auto-redirect to portal after 30 seconds (optional)
        let redirectTimer = setTimeout(function() {
            if (confirm('Redirecting to verification portal. Click OK to go now or Cancel to stay.')) {
                window.location.href = '/application-verify/portal';
            }
        }, 30000);
        
        // Clear timer if user interacts
        document.addEventListener('click', function() {
            clearTimeout(redirectTimer);
        });
        
        // Log error to analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'verification_error', {
                'error_code': '<?php echo $errorCode ?? 'UNKNOWN'; ?>',
                'error_message': '<?php echo addslashes($errorMessage); ?>'
            });
        }
    </script>
</body>
</html>