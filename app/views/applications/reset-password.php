<?php
/**
 * Reset Password View
 * 
 * @package FCTCNS
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$csrf_token = $csrf_token ?? '';
$token = $token ?? '';
$email = $email ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - FCT College of Nursing Sciences</title>
    
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
        
        .reset-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }
        
        .reset-card {
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
        
        .alert {
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
        
        .footer {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 1);
            font-size: 13px;
            line-height: 1.6;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            background-color: rgba(0,0,0,0.2);
            padding: 15px;
            border-radius: 8px;
            backdrop-filter: blur(5px);
        }
        
        .footer a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px dotted rgba(255,255,255,0.5);
        }
        
        .footer a:hover {
            border-bottom-color: white;
        }
        
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .password-requirements i {
            color: #667eea;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="card-header">
                <i class="fas fa-lock fa-2x mb-2"></i>
                <h2>Reset Password</h2>
                <p>Create a new password for <?php echo e($email); ?></p>
            </div>
            
            <div class="card-body">
                <!-- PHP Flash Messages -->
                <?php if (isset($_SESSION['flash_error']) && !empty($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div>
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo e($_SESSION['flash_error']); ?>
                    </div>
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()">×</button>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>
                
                <!-- Reset Password Form -->
                <form method="POST" action="/applicant/reset-password" id="resetForm">
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                    <input type="hidden" name="token" value="<?php echo e($token); ?>">
                    
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i> New Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter new password"
                                   minlength="8"
                                   required>
                            <button class="btn" type="button" onclick="togglePassword('password', 'toggleIcon1')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                        <div class="password-requirements">
                            <i class="fas fa-info-circle"></i> Password must be at least 8 characters long
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">
                            <i class="fas fa-check-circle me-1"></i> Confirm Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   placeholder="Confirm new password"
                                   minlength="8"
                                   required>
                            <button class="btn" type="button" onclick="togglePassword('confirm_password', 'toggleIcon2')">
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-reset" id="resetBtn">
                        <span id="resetText"><i class="fas fa-save me-2"></i>Reset Password</span>
                        <span id="resetSpinner" style="display: none;">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Resetting...
                        </span>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="footer">
            <p>© <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
            <p>
                <i class="fas fa-phone-alt"></i> Support: 07039837749 | 
                <i class="fas fa-envelope"></i> Email: <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a>
            </p>
        </div>
    </div>
    
    <script>
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    document.getElementById('resetForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long');
            return false;
        }
        
        if (password !== confirm) {
            e.preventDefault();
            alert('Passwords do not match');
            return false;
        }
        
        // Show loading state
        document.getElementById('resetText').style.display = 'none';
        document.getElementById('resetSpinner').style.display = 'inline-block';
        document.getElementById('resetBtn').disabled = true;
        
        return true;
    });
    </script>
</body>
</html>