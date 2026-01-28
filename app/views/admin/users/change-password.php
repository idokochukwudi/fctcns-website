<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - FCT CNS</title>
    <style>
        /* Simple standalone styles */
        :root {
            --primary: #2c5282;
            --primary-dark: #1a365d;
            --primary-light: #4299e1;
            --danger: #e53e3e;
            --gray-100: #edf2f7;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e0;
            --gray-600: #718096;
            --gray-700: #4a5568;
            --gray-800: #2d3748;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .password-container {
            width: 100%;
            max-width: 450px;
        }
        
        .password-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .password-header {
            background: var(--primary);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .password-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .password-header p {
            opacity: 0.9;
            font-size: 0.875rem;
        }
        
        .password-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--gray-700);
            font-size: 0.875rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 0.875rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
            margin-top: 1rem;
        }
        
        .btn-secondary:hover {
            background: var(--gray-300);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
        }
        
        .alert-danger {
            background: #fed7d7;
            color: #9b2c2c;
            border-color: #fc8181;
        }
        
        .alert-success {
            background: #c6f6d5;
            color: #276749;
            border-color: #9ae6b4;
        }
        
        .password-strength {
            margin-top: 0.5rem;
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            overflow: hidden;
        }
        
        .strength-meter {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background 0.3s;
        }
        
        .strength-weak { width: 33%; background: var(--danger); }
        .strength-medium { width: 66%; background: var(--primary-light); }
        .strength-strong { width: 100%; background: #38a169; }
        
        .password-requirements {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: var(--gray-600);
        }
        
        .requirement {
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .requirement.met {
            color: #38a169;
        }
        
        .requirement:before {
            content: "○";
            font-size: 0.875rem;
        }
        
        .requirement.met:before {
            content: "✓";
        }
    </style>
</head>
<body>
    <div class="password-container">
        <div class="password-card">
            <div class="password-header">
                <h1>Change Password</h1>
                <p>Enter your new password below</p>
            </div>
            
            <div class="password-body">
                <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/change-password">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
                    
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password *</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password" class="form-label">New Password *</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required 
                               oninput="checkPasswordStrength(this.value)">
                        <div class="password-strength">
                            <div class="strength-meter" id="strengthMeter"></div>
                        </div>
                        <div class="password-requirements" id="passwordRequirements">
                            <div class="requirement" id="reqLength">At least 8 characters</div>
                            <div class="requirement" id="reqUppercase">Contains uppercase letter</div>
                            <div class="requirement" id="reqLowercase">Contains lowercase letter</div>
                            <div class="requirement" id="reqNumber">Contains number</div>
                            <div class="requirement" id="reqSpecial">Contains special character</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm New Password *</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required 
                               oninput="checkPasswordMatch()">
                        <div class="form-text" id="passwordMatchText"></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Change Password</button>
                    
                    <?php if (!isset($force_change)): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-secondary">
                        Cancel and Return to Dashboard
                    </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <script>
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Check length
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Check character types
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Update strength meter
            const meter = document.getElementById('strengthMeter');
            meter.className = 'strength-meter';
            
            if (password.length === 0) {
                meter.style.width = '0%';
            } else if (strength <= 2) {
                meter.className += ' strength-weak';
            } else if (strength <= 4) {
                meter.className += ' strength-medium';
            } else {
                meter.className += ' strength-strong';
            }
            
            // Update requirements
            document.getElementById('reqLength').className = password.length >= 8 ? 'requirement met' : 'requirement';
            document.getElementById('reqUppercase').className = /[A-Z]/.test(password) ? 'requirement met' : 'requirement';
            document.getElementById('reqLowercase').className = /[a-z]/.test(password) ? 'requirement met' : 'requirement';
            document.getElementById('reqNumber').className = /[0-9]/.test(password) ? 'requirement met' : 'requirement';
            document.getElementById('reqSpecial').className = /[^A-Za-z0-9]/.test(password) ? 'requirement met' : 'requirement';
        }
        
        function checkPasswordMatch() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchText = document.getElementById('passwordMatchText');
            
            if (confirmPassword.length === 0) {
                matchText.textContent = '';
                matchText.style.color = '';
            } else if (newPassword === confirmPassword) {
                matchText.textContent = '✓ Passwords match';
                matchText.style.color = '#38a169';
            } else {
                matchText.textContent = '✗ Passwords do not match';
                matchText.style.color = '#e53e3e';
            }
        }
        
        // Initialize check
        document.addEventListener('DOMContentLoaded', function() {
            checkPasswordStrength(document.getElementById('new_password').value);
            checkPasswordMatch();
        });
    </script>
</body>
</html>