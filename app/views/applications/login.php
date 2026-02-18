<?php
/**
 * Applicant Login View
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
    <title>Applicant Login - FCT College of Nursing Sciences</title>
    
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
        
        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }
        
        .login-card {
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
            background-color: #fff8f8;
        }
        
        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        
        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            height: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-left: none;
            background: white;
            color: #667eea;
        }
        
        .input-group .btn:hover {
            background: #f8f9fa;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        
        .was-validated .form-control:invalid ~ .invalid-feedback,
        .form-control.is-invalid ~ .invalid-feedback {
            display: block;
        }
        
        .btn-login {
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
        
        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login:disabled {
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
        
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
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
        
        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }
        
        .forgot-password a {
            color: #999;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .forgot-password a:hover {
            color: #667eea;
        }
        
        /* NO FOOTER STYLES HERE - Using layout footer */
        
        .login-type-indicator {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }
        
        .login-type-indicator i {
            color: #667eea;
            margin-right: 3px;
        }
        
        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 0.2em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
            margin-right: 5px;
        }
        
        @keyframes spinner-border {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="card-header">
                <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                <h2>FCT College of Nursing Sciences</h2>
                <p>2025/2026 Admissions Application Portal</p>
            </div>
            
            <div class="card-body">
                <!-- PHP Flash Messages -->
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
                
                <?php if (isset($_SESSION['flash_info']) && !empty($_SESSION['flash_info'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <div>
                        <i class="fas fa-info-circle me-2"></i>
                        <?php echo e($_SESSION['flash_info']); ?>
                    </div>
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                </div>
                <?php unset($_SESSION['flash_info']); ?>
                <?php endif; ?>
                
                <!-- Login Form -->
                <form method="POST" action="/applicant/login" class="needs-validation" id="loginForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                    
                    <div class="form-group">
                        <label for="login" class="form-label">
                            <i class="fas fa-user me-1"></i> Email, Phone, or JAMB Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control <?php echo (isset($_SESSION['login_error'])) ? 'is-invalid' : ''; ?>" 
                               id="login" 
                               name="login" 
                               value="<?php echo e($_SESSION['login_value'] ?? ''); ?>" 
                               placeholder="Enter your email, phone number, or JAMB number"
                               autocomplete="username"
                               required>
                        <?php if (isset($_SESSION['login_error'])): ?>
                        <div class="invalid-feedback">
                            <?php echo e($_SESSION['login_error']); ?>
                        </div>
                        <?php endif; ?>
                        <div class="login-type-indicator">
                            <i class="fas fa-info-circle"></i> You can login with your email, phone number, or JAMB registration number
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i> Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control <?php echo (isset($_SESSION['password_error'])) ? 'is-invalid' : ''; ?>" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter your password"
                                   autocomplete="current-password"
                                   required>
                            <button class="btn" type="button" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        <?php if (isset($_SESSION['password_error'])): ?>
                        <div class="invalid-feedback">
                            <?php echo e($_SESSION['password_error']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn-login" id="loginBtn">
                        <span id="loginText"><i class="fas fa-sign-in-alt me-2"></i>Login</span>
                        <span id="loginSpinner" style="display: none;">
                            <span class="spinner-border" role="status" aria-hidden="true"></span>
                            Logging in...
                        </span>
                    </button>
                </form>
                
                <div class="divider">
                    <span>OR</span>
                </div>
                
                <div class="text-center">
                    <p class="mb-3" style="color: #666;">Don't have an account?</p>
                    <a href="/apply/register" class="btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>Start New Application
                    </a>
                </div>
                
                <div class="forgot-password">
                    <a href="/applicant/forgot-password">
                        <i class="fas fa-key me-1"></i> Forgot Password?
                    </a>
                </div>
            </div>
        </div>
        
        <!-- NO FOOTER HERE - Footer is provided by the layout -->
        
    </div>
    
    <script>
    // Toggle password visibility
    function togglePassword() {
        const password = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (password.type === 'password') {
            password.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    
    // Form submission with client-side validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const login = document.getElementById('login').value.trim();
        const password = document.getElementById('password').value;
        
        let isValid = true;
        
        // Reset previous error states
        document.getElementById('login').classList.remove('is-invalid');
        document.getElementById('password').classList.remove('is-invalid');
        
        // Validate login
        if (!login) {
            document.getElementById('login').classList.add('is-invalid');
            // Create or update feedback div
            let feedback = document.getElementById('login').nextElementSibling;
            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                document.getElementById('login').parentNode.appendChild(feedback);
            }
            feedback.textContent = 'Please enter your email, phone, or JAMB number.';
            isValid = false;
        }
        
        // Validate password
        if (!password) {
            document.getElementById('password').classList.add('is-invalid');
            // Find the feedback div in the input-group
            const feedback = document.querySelector('#password + .btn + .invalid-feedback, #password ~ .invalid-feedback');
            if (feedback) {
                feedback.textContent = 'Please enter your password.';
            }
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        document.getElementById('loginText').style.display = 'none';
        document.getElementById('loginSpinner').style.display = 'inline-block';
        document.getElementById('loginBtn').disabled = true;
        
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
    
    // Clear any existing session errors when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // This helps clean up any stale error messages
        <?php
        unset($_SESSION['login_error']);
        unset($_SESSION['password_error']);
        ?>
    });
    </script>
</body>
</html>