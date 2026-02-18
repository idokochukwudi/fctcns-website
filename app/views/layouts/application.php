<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Application Portal - FCT College of Nursing Sciences'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Apply for admission into ND/HND Nursing programme'; ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6B4E9B;
            --secondary-color: #4A3B6B;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        
        .application-container {
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .application-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .application-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        
        .application-header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        
        .application-body {
            padding: 40px;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }
        
        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }
        
        .step-number {
            width: 50px;
            height: 50px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            color: #999;
            transition: all 0.3s;
        }
        
        .step.active .step-number {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .step.completed .step-number {
            background: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }
        
        .step-label {
            font-size: 14px;
            color: #666;
        }
        
        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .flash-messages {
            margin-bottom: 20px;
        }
        
        .flash-message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .flash-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .flash-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .flash-message.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .form-section h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--primary-color);
            font-size: 18px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(107, 78, 155, 0.25);
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
        }
        
        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(107, 78, 155, 0.3);
        }
        
        .btn-success {
            background: var(--success-color);
            border: none;
        }
        
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .application-footer {
            background: #f8f9fa;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }
        
        .application-footer p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        .document-preview {
            position: relative;
            display: inline-block;
            margin: 10px;
        }
        
        .document-preview img {
            max-width: 150px;
            max-height: 150px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }
        
        .document-preview .remove-document {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 30px;
            height: 30px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
        }
        
        .document-preview .remove-document:hover {
            background: #c82333;
        }
        
        .payment-details {
            background: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .payment-amount {
            font-size: 48px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 20px 0;
        }
        
        .payment-rrr {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 18px;
        }
        
        .exam-slip {
            background: white;
            border: 2px solid var(--primary-color);
            border-radius: 10px;
            padding: 30px;
            margin-top: 30px;
        }
        
        .exam-slip-header {
            text-align: center;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .exam-slip-header h2 {
            color: var(--primary-color);
            margin: 10px 0;
        }
        
        .exam-slip-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .exam-slip-item {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .exam-slip-item .label {
            font-weight: 600;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }
        
        .exam-slip-item .value {
            font-size: 16px;
            color: #333;
        }
        
        @media (max-width: 768px) {
            .application-body {
                padding: 20px;
            }
            
            .step-label {
                font-size: 12px;
            }
            
            .exam-slip-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="application-container">
        <div class="application-header">
            <h1>FCT College of Nursing Sciences</h1>
            <p>2025/2026 Admissions Application Portal</p>
        </div>
        
        <?php 
        // Safely check if application exists and has application_step
        $showSteps = false;
        $currentStep = 1;
        if (isset($application) && is_array($application) && isset($application['application_step'])) {
            $showSteps = true;
            $currentStep = (int)$application['application_step'];
        }
        ?>
        
        <?php if (!isset($hideSteps) && $showSteps): ?>
        <div class="application-body">
            <div class="step-indicator">
                <div class="step <?php echo $currentStep >= 1 ? 'completed' : ''; ?> <?php echo $currentStep == 1 ? 'active' : ''; ?>">
                    <div class="step-number">1</div>
                    <div class="step-label">JAMB Verification</div>
                </div>
                <div class="step <?php echo $currentStep >= 2 ? 'completed' : ''; ?> <?php echo $currentStep == 2 ? 'active' : ''; ?>">
                    <div class="step-number">2</div>
                    <div class="step-label">Application Form</div>
                </div>
                <div class="step <?php echo $currentStep >= 3 ? 'completed' : ''; ?> <?php echo $currentStep == 3 ? 'active' : ''; ?>">
                    <div class="step-number">3</div>
                    <div class="step-label">Payment</div>
                </div>
                <div class="step <?php echo $currentStep >= 4 ? 'completed' : ''; ?> <?php echo $currentStep == 4 ? 'active' : ''; ?>">
                    <div class="step-number">4</div>
                    <div class="step-label">Exam Slip</div>
                </div>
            </div>
        <?php else: ?>
        <div class="application-body">
        <?php endif; ?>
        
            <?php if (!empty($flash_success)): ?>
            <div class="flash-messages">
                <div class="flash-message success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($flash_success); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($flash_error)): ?>
            <div class="flash-messages">
                <div class="flash-message error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($flash_error); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($flash_info)): ?>
            <div class="flash-messages">
                <div class="flash-message info">
                    <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($flash_info); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php echo $content; ?>
        </div>
        
        <div class="application-footer">
            <p>&copy; <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
            <p>Support: 
                <?php 
                // Safely check if settings exists and has the required keys
                if (isset($settings) && is_array($settings) && isset($settings['key_value'])) {
                    echo htmlspecialchars($settings['key_value']['support_phone_1'] ?? '07039837749');
                } else {
                    echo '07039837749';
                }
                ?> | 
                Email: 
                <?php 
                if (isset($settings) && is_array($settings) && isset($settings['key_value'])) {
                    echo htmlspecialchars($settings['key_value']['support_email'] ?? 'info@fctcns.edu.ng');
                } else {
                    echo 'info@fctcns.edu.ng';  // UPDATED: Changed to info@fctcns.edu.ng
                }
                ?>
            </p>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Payment JavaScript -->
    <script src="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/assets/js/payment.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            $('.flash-message').fadeOut('slow');
        }, 5000);
        
        // Confirm before actions
        function confirmAction(message) {
            return confirm(message || 'Are you sure?');
        }
        
        // Password strength indicator
        function checkPasswordStrength(password) {
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;
            
            return strength;
        }
        
        // Image preview before upload
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $('#' + previewId).attr('src', e.target.result).show();
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Confirm passport upload
        function confirmPassportUpload(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    if (confirm('Is this your correct passport photograph? Click OK to upload.')) {
                        $('#passport-preview').attr('src', e.target.result).show();
                        $('#passport-confirmed').val('1');
                        return true;
                    } else {
                        input.value = '';
                        $('#passport-preview').hide();
                        $('#passport-confirmed').val('0');
                        return false;
                    }
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>