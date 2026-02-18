<?php
/**
 * Forgot Password View
 * 
 * @package FCTCNS
 */

// Extract data array to variables
extract($data ?? []);

// Helper function for safe output
if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$csrf_token = $csrf_token ?? '';
$baseUrl = $baseUrl ?? '/';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - FCT College of Nursing Sciences</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }
        
        .forgot-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }
        
        .forgot-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            animation: slideUp 0.5s ease;
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
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .card-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .card-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .card-body {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .btn-reset {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-reset:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-reset:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            font-weight: 500;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-outline-primary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        
        .alert .btn-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            opacity: 0.5;
            padding: 0;
            line-height: 1;
            color: inherit;
        }
        
        .alert .btn-close:hover {
            opacity: 1;
        }
        
        .divider {
            text-align: center;
            margin: 30px 0 20px;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
            z-index: 1;
        }
        
        .divider span {
            background: white;
            padding: 0 15px;
            color: #999;
            font-size: 14px;
            position: relative;
            z-index: 2;
        }
        
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-to-login a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .back-to-login a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        /* REMOVED: .footer styles - now using layout footer */
        
        .info-text {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="card-header">
                <i class="fas fa-key fa-2x mb-2"></i>
                <h2>Forgot Password</h2>
                <p>Reset your password to access your account</p>
            </div>
            
            <div class="card-body">
                <!-- PHP Flash Messages -->
                <?php if (isset($_SESSION['flash_success']) && !empty($_SESSION['flash_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div>
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo e($_SESSION['flash_success']); ?>
                    </div>
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['flash_error']) && !empty($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div>
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo e($_SESSION['flash_error']); ?>
                    </div>
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>
                
                <!-- Forgot Password Form -->
                <form method="POST" action="/applicant/forgot-password" id="forgotForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                    
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i> Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               value="<?php echo e($_SESSION['email_value'] ?? ''); ?>" 
                               placeholder="Enter your registered email address"
                               required>
                        <div class="info-text">
                            <i class="fas fa-info-circle"></i> We'll send a password reset link to this email
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-reset" id="resetBtn">
                        <span id="resetText"><i class="fas fa-paper-plane me-2"></i>Send Reset Link</span>
                        <span id="resetSpinner" style="display: none;">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Sending...
                        </span>
                    </button>
                </form>
                
                <div class="divider">
                    <span>OR</span>
                </div>
                
                <div class="back-to-login">
                    <a href="/applicant/login">
                        <i class="fas fa-arrow-left me-2"></i>Back to Login
                    </a>
                </div>
                
                <div class="text-center mt-3">
                    <p class="mb-0" style="color: #666;">Don't have an account?</p>
                    <a href="/apply/register" class="btn-outline-primary mt-2">
                        <i class="fas fa-user-plus me-2"></i>Start New Application
                    </a>
                </div>
            </div>
        </div>
        
        <!-- REMOVED: Duplicate footer - now using layout footer -->
        
    </div>
    
    <script>
    // Form submission loading state
    document.getElementById('forgotForm').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        
        if (!email) {
            e.preventDefault();
            alert('Please enter your email address');
            return false;
        }
        
        // Show loading state
        document.getElementById('resetText').style.display = 'none';
        document.getElementById('resetSpinner').style.display = 'inline-block';
        document.getElementById('resetBtn').disabled = true;
        
        return true;
    });
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 500);
        });
    }, 5000);
    </script>
</body>
</html>