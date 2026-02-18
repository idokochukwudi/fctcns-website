<?php
/**
 * Step 2: Application Form View
 * ENHANCED: Professional design matching the main application form
 * 
 * @var array $application
 * @var array $olevel_results
 * @var array $passport
 * @var string $flash_success
 * @var string $flash_error
 * @var string $temp_password
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$application = $application ?? [];
$applicant = $applicant ?? [];
$olevel_results = $olevel_results ?? [];
$passport = $passport ?? [];
$csrf_token = $csrf_token ?? '';
$temp_password = $temp_password ?? '';
$errors = $errors ?? [];

// Get applicant name for welcome message
$applicant_name = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
if (empty($applicant_name)) {
    $applicant_name = 'Applicant';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="Application Form - FCT College of Nursing Sciences">
    <title>Application Form - Step 2 - FCT College of Nursing Sciences</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* ==========================================================================
           RESET & BASE STYLES
           ========================================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        /* ==========================================================================
           DESIGN TOKENS
           ========================================================================== */
        :root {
            --primary: #6B4E9B;
            --primary-dark: #4A3B6B;
            --primary-light: #8A6FB0;
            --primary-soft: #F3EAF8;
            --gold: #C9A44A;
            --gold-light: #D8B86C;
            --success: #10b981;
            --success-light: #d1fae5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --surface: #F7F9FC;
            --border: #E9EDF2;
            --white: #FFFFFF;
            --text-dark: #1A1F2E;
            --text-muted: #6B7280;
            --shadow-sm: 0 4px 6px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 25px rgba(0,0,0,0.1);
            --shadow-lg: 0 20px 40px rgba(0,0,0,0.15);
            --shadow-primary: 0 10px 30px rgba(107,78,155,0.3);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 30px;
        }

        /* ==========================================================================
           CONTAINER & LAYOUT
           ========================================================================== */
        .application-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==========================================================================
           MAIN CARD
           ========================================================================== */
        .main-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s;
        }

        .main-card:hover {
            box-shadow: var(--shadow-lg), var(--shadow-primary);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            opacity: 0.5;
        }

        .card-header i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--gold);
        }

        .card-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #FFFFFF !important;
        }

        .card-header p {
            font-size: 14px;
            margin: 0;
            color: rgba(255,255,255,0.95) !important;
        }

        .card-body {
            padding: 40px;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 25px;
            }
        }

        /* ==========================================================================
           TOP BAR WITH WELCOME AND LOGOUT
           ========================================================================== */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 15px;
        }

        .welcome-message {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .welcome-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-soft);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.2rem;
        }

        .welcome-text {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .welcome-text strong {
            color: var(--primary-dark);
            font-weight: 600;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            background: transparent;
            color: var(--danger);
            border: 1.5px solid var(--danger-light);
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239,68,68,0.2);
        }

        @media (max-width: 480px) {
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .logout-btn {
                align-self: flex-end;
            }
        }

        /* ==========================================================================
           FORM SECTIONS
           ========================================================================== */
        .form-section {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid var(--border);
        }

        .form-section h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--primary);
            font-size: 20px;
            font-weight: 600;
            border-bottom: 2px solid var(--border);
            padding-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section h3 i {
            color: var(--gold);
        }

        /* ==========================================================================
           FORM ELEMENTS
           ========================================================================== */
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .form-label i {
            color: var(--primary);
            margin-right: 6px;
        }

        .form-control, .form-select {
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: 'Outfit', sans-serif;
            color: var(--text-dark);
            width: 100%;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(107,78,155,0.15);
            outline: none;
        }

        .form-control[readonly] {
            background: #f8f9fa;
            cursor: not-allowed;
            border-color: var(--border);
            color: #6c757d;
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 12px;
            margin-top: 5px;
        }

        .form-text {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* ==========================================================================
           BUTTONS
           ========================================================================== */
        .btn {
            padding: 12px 25px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: var(--shadow-primary);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(107,78,155,0.4);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #0ca678;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-danger {
            background: transparent;
            border: 2px solid var(--danger);
            color: var(--danger);
        }

        .btn-outline-danger:hover {
            background: var(--danger);
            color: white;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 13px;
        }

        /* ==========================================================================
           ALERTS
           ========================================================================== */
        .alert {
            border-radius: var(--radius-md);
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: var(--success-light);
            color: #065f46;
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: var(--danger-light);
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background: var(--warning-light);
            color: #92400e;
            border-left: 4px solid var(--warning);
        }

        .alert-info {
            background: var(--info-light);
            color: #1e40af;
            border-left: 4px solid var(--info);
        }

        .alert .btn-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            opacity: 0.5;
            color: currentColor;
        }

        .alert .btn-close:hover {
            opacity: 1;
        }

        .alert-heading {
            color: inherit;
            margin-bottom: 8px;
            font-weight: 600;
        }

        /* ==========================================================================
           O'LEVEL RESULT ITEMS
           ========================================================================== */
        .olevel-result-item {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .olevel-result-item:hover {
            border-color: var(--primary-light);
            box-shadow: var(--shadow-sm);
        }

        /* ==========================================================================
           DOCUMENT PREVIEW
           ========================================================================== */
        .document-preview {
            position: relative;
            display: inline-block;
            margin: 10px;
        }

        .document-preview img {
            max-width: 150px;
            max-height: 150px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            transition: all 0.3s;
        }

        .document-preview:hover img {
            border-color: var(--primary);
        }

        /* ==========================================================================
           DIVIDER
           ========================================================================== */
        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border);
            z-index: 1;
        }

        .divider span {
            background: var(--white);
            padding: 0 15px;
            color: var(--text-muted);
            font-size: 14px;
            position: relative;
            z-index: 2;
        }

        /* ==========================================================================
           FOOTER
           ========================================================================== */
        .application-footer {
            text-align: center;
            margin-top: 30px;
            color: rgba(255,255,255,0.95);
            font-size: 13px;
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        .application-footer a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px dotted rgba(255,255,255,0.5);
        }

        .application-footer a:hover {
            border-bottom-color: white;
        }

        /* ==========================================================================
           RESPONSIVE UTILITIES
           ========================================================================== */
        @media (max-width: 768px) {
            .form-section {
                padding: 20px;
            }

            .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                width: 100%;
                margin: 5px 0;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 15px;
            }

            .d-flex.justify-content-between a,
            .d-flex.justify-content-between div {
                width: 100%;
            }

            .d-flex.justify-content-between .btn {
                width: 100%;
                margin: 5px 0;
            }
        }

        /* ==========================================================================
           UTILITY CLASSES
           ========================================================================== */
        .text-primary { color: var(--primary) !important; }
        .bg-soft-primary { background: var(--primary-soft); }
        .border-primary-light { border-color: var(--primary-light); }
    </style>
</head>
<body>
    <div class="application-container">
        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div class="card-header">
                <i class="fas fa-file-alt"></i>
                <h2>Application Form</h2>
                <p>Step 2 of 4 - Complete your application details</p>
            </div>
            
            <!-- Card Body -->
            <div class="card-body">
                <!-- TOP BAR WITH WELCOME AND LOGOUT BUTTON -->
                <div class="top-bar">
                    <div class="welcome-message">
                        <div class="welcome-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="welcome-text">
                            Welcome, <strong><?php echo e($applicant_name); ?></strong>
                        </div>
                    </div>
                    <a href="/applicant/logout" class="logout-btn" onclick="return confirm('Are you sure you want to logout? Your progress will be saved.');">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>

                <!-- Flash Messages -->
                <?php if (isset($flash_success) && $flash_success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo htmlspecialchars($flash_success); ?></span>
                        <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                    </div>
                <?php endif; ?>

                <?php if (isset($flash_error) && $flash_error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($flash_error); ?></span>
                        <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                    </div>
                <?php endif; ?>

                <?php if (isset($temp_password) && $temp_password): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div>
                            <h5 class="alert-heading"><i class="fas fa-key me-2"></i>Your Login Password</h5>
                            <p class="mb-2">Please save this password. You'll need it to log in later:</p>
                            <div class="bg-light p-3 text-center rounded" style="background: white; border: 2px dashed var(--primary-light);">
                                <strong style="font-size: 1.5rem; font-family: monospace; color: var(--primary);"><?php echo htmlspecialchars($temp_password); ?></strong>
                            </div>
                            <p class="mt-2 mb-0 small text-muted">
                                <i class="fas fa-info-circle"></i> This password will also be sent to your email after you provide it.
                            </p>
                        </div>
                        <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <h5 class="alert-heading">Please fix the following errors:</h5>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Main Form -->
                <form method="POST" action="/apply/save-application" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                    <input type="hidden" name="action" id="form_action" value="save">
                    
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-user-circle"></i> Personal Information</h3>
                        <p class="text-muted small mb-3">Fields from JAMB record cannot be edited. Please verify they are correct.</p>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">
                                    <i class="fas fa-user"></i> First Name
                                </label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo e($application['first_name'] ?? ''); ?>" readonly>
                                <small class="text-muted">From JAMB record - cannot be changed</small>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">
                                    <i class="fas fa-user"></i> Last Name
                                </label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo e($application['last_name'] ?? ''); ?>" readonly>
                                <small class="text-muted">From JAMB record - cannot be changed</small>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="other_names" class="form-label">
                                    <i class="fas fa-user"></i> Other Names
                                </label>
                                <input type="text" class="form-control" id="other_names" name="other_names" 
                                       value="<?php echo e($application['other_names'] ?? ''); ?>" readonly>
                                <small class="text-muted">From JAMB record - cannot be changed</small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="gender" class="form-label">
                                    <i class="fas fa-venus-mars"></i> Gender
                                </label>
                                <input type="text" class="form-control" id="gender" name="gender" 
                                       value="<?php echo isset($application['gender']) ? ($application['gender'] == 'M' ? 'Male' : ($application['gender'] == 'F' ? 'Female' : '')) : ''; ?>" readonly>
                                <small class="text-muted">From JAMB record</small>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="state_of_origin" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> State of Origin
                                </label>
                                <input type="text" class="form-control" id="state_of_origin" name="state_of_origin" 
                                       value="<?php echo e($application['state_of_origin'] ?? ''); ?>" readonly>
                                <small class="text-muted">From JAMB record</small>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="lga" class="form-label">
                                    <i class="fas fa-map-pin"></i> LGA
                                </label>
                                <input type="text" class="form-control" id="lga" name="lga" 
                                       value="<?php echo e($application['lga'] ?? ''); ?>" readonly>
                                <small class="text-muted">From JAMB record</small>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="utme_score" class="form-label">
                                    <i class="fas fa-star"></i> UTME Score
                                </label>
                                <input type="text" class="form-control" id="utme_score" name="utme_score" 
                                       value="<?php echo e($application['utme_score'] ?? ''); ?>" readonly>
                                <small class="text-muted">From JAMB record</small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Date of Birth <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                       value="<?php echo e($application['date_of_birth'] ?? ''); ?>" required>
                                <div class="invalid-feedback">Date of birth is required.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nationality" class="form-label">
                                    <i class="fas fa-flag"></i> Nationality
                                </label>
                                <input type="text" class="form-control" id="nationality" name="nationality" 
                                       value="<?php echo e($application['nationality'] ?? 'Nigerian'); ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo e($application['email'] ?? ($applicant['email'] ?? '')); ?>" required>
                                <div class="invalid-feedback">Valid email is required.</div>
                                <small class="text-muted">Your login credentials will be sent to this email</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone"></i> Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo e($application['phone'] ?? ($applicant['phone'] ?? '')); ?>" required>
                                <div class="invalid-feedback">Phone number is required.</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-home"></i> Contact Address <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="address" name="address" rows="2" required><?php echo e($application['address'] ?? ''); ?></textarea>
                            <div class="invalid-feedback">Address is required.</div>
                        </div>
                    </div>
                    
                    <!-- Program Choice Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-graduation-cap"></i> Program Choice</h3>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="program_choice_1" class="form-label">
                                    <i class="fas fa-book"></i> Select Program <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="program_choice_1" name="program_choice_1" required>
                                    <option value="">Select Program</option>
                                    <option value="ND Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'ND Nursing' ? 'selected' : ''; ?>>ND Nursing</option>
                                    <option value="Post Basic Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'Post Basic Nursing' ? 'selected' : ''; ?>>Post Basic Nursing</option>
                                    <option value="Midwifery" <?php echo ($application['program_choice_1'] ?? '') == 'Midwifery' ? 'selected' : ''; ?>>Midwifery</option>
                                    <option value="Public Health Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'Public Health Nursing' ? 'selected' : ''; ?>>Public Health Nursing</option>
                                </select>
                                <div class="invalid-feedback">Please select your program.</div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields for other choices (not used) -->
                        <input type="hidden" name="program_choice_2" value="">
                        <input type="hidden" name="program_choice_3" value="">
                    </div>
                    
                    <!-- O'Level Results Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-certificate"></i> O'Level Results</h3>
                        <p class="text-muted small mb-3">Provide your O'Level results. Credit passes required in English, Mathematics, Biology, Chemistry, and Physics.</p>
                        
                        <div id="olevel-results-container">
                            <?php if (!empty($olevel_results)): ?>
                                <?php foreach ($olevel_results as $index => $result): ?>
                                <div class="olevel-result-item">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label">Exam Type</label>
                                            <select class="form-select" name="olevel[<?php echo $index; ?>][exam_type]" required>
                                                <option value="WAEC" <?php echo ($result['exam_type'] ?? '') == 'WAEC' ? 'selected' : ''; ?>>WAEC</option>
                                                <option value="NECO" <?php echo ($result['exam_type'] ?? '') == 'NECO' ? 'selected' : ''; ?>>NECO</option>
                                                <option value="NABTEB" <?php echo ($result['exam_type'] ?? '') == 'NABTEB' ? 'selected' : ''; ?>>NABTEB</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Year</label>
                                            <input type="text" class="form-control" name="olevel[<?php echo $index; ?>][exam_year]" value="<?php echo e($result['exam_year'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label">Exam Number</label>
                                            <input type="text" class="form-control" name="olevel[<?php echo $index; ?>][exam_number]" value="<?php echo e($result['exam_number'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Sitting</label>
                                            <select class="form-select" name="olevel[<?php echo $index; ?>][sitting]">
                                                <option value="1st" <?php echo ($result['sitting'] ?? '') == '1st' ? 'selected' : ''; ?>>1st Sitting</option>
                                                <option value="2nd" <?php echo ($result['sitting'] ?? '') == '2nd' ? 'selected' : ''; ?>>2nd Sitting</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="this.closest('.olevel-result-item').remove()">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">English</label>
                                            <select class="form-select" name="olevel[<?php echo $index; ?>][english_grade]" required>
                                                <option value="">Select</option>
                                                <?php foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade): ?>
                                                    <option value="<?php echo $grade; ?>" <?php echo ($result['english_grade'] ?? '') == $grade ? 'selected' : ''; ?>><?php echo $grade; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Mathematics</label>
                                            <select class="form-select" name="olevel[<?php echo $index; ?>][mathematics_grade]" required>
                                                <option value="">Select</option>
                                                <?php foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade): ?>
                                                    <option value="<?php echo $grade; ?>" <?php echo ($result['mathematics_grade'] ?? '') == $grade ? 'selected' : ''; ?>><?php echo $grade; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Biology</label>
                                            <select class="form-select" name="olevel[<?php echo $index; ?>][biology_grade]" required>
                                                <option value="">Select</option>
                                                <?php foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade): ?>
                                                    <option value="<?php echo $grade; ?>" <?php echo ($result['biology_grade'] ?? '') == $grade ? 'selected' : ''; ?>><?php echo $grade; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Chemistry</label>
                                            <select class="form-select" name="olevel[<?php echo $index; ?>][chemistry_grade]" required>
                                                <option value="">Select</option>
                                                <?php foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade): ?>
                                                    <option value="<?php echo $grade; ?>" <?php echo ($result['chemistry_grade'] ?? '') == $grade ? 'selected' : ''; ?>><?php echo $grade; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Physics</label>
                                            <select class="form-select" name="olevel[<?php echo $index; ?>][physics_grade]" required>
                                                <option value="">Select</option>
                                                <?php foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade): ?>
                                                    <option value="<?php echo $grade; ?>" <?php echo ($result['physics_grade'] ?? '') == $grade ? 'selected' : ''; ?>><?php echo $grade; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="olevel-result-item">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label">Exam Type</label>
                                            <select class="form-select" name="olevel[0][exam_type]" required>
                                                <option value="WAEC">WAEC</option>
                                                <option value="NECO">NECO</option>
                                                <option value="NABTEB">NABTEB</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Year</label>
                                            <input type="text" class="form-control" name="olevel[0][exam_year]" required>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label">Exam Number</label>
                                            <input type="text" class="form-control" name="olevel[0][exam_number]">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Sitting</label>
                                            <select class="form-select" name="olevel[0][sitting]">
                                                <option value="1st">1st Sitting</option>
                                                <option value="2nd">2nd Sitting</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="this.closest('.olevel-result-item').remove()">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">English</label>
                                            <select class="form-select" name="olevel[0][english_grade]" required>
                                                <option value="">Select</option>
                                                <?php foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade): ?>
                                                    <option value="<?php echo $grade; ?>"><?php echo $grade; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Mathematics</label>
                                            <select class="form-select" name="olevel[0][mathematics_grade]" required>
                                                <option value="">Select</option>
                                                <?php foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade): ?>
                                                    <option value="<?php echo $grade; ?>"><?php echo $grade; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Biology</label>
                                            <select class="form-select" name="olevel[0][biology_grade]" required>
                                                <option value="">Select</option>
                                                <?php foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade): ?>
                                                    <option value="<?php echo $grade; ?>"><?php echo $grade; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Chemistry</label>
                                            <select class="form-select" name="olevel[0][chemistry_grade]" required>
                                                <option value="">Select</option>
                                                <?php foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade): ?>
                                                    <option value="<?php echo $grade; ?>"><?php echo $grade; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label class="form-label">Physics</label>
                                            <select class="form-select" name="olevel[0][physics_grade]" required>
                                                <option value="">Select</option>
                                                <?php foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade): ?>
                                                    <option value="<?php echo $grade; ?>"><?php echo $grade; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-outline-primary" id="add-olevel">
                                <i class="fas fa-plus"></i> Add Another Sitting
                            </button>
                        </div>
                    </div>
                    
                    <!-- Passport Upload Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-camera"></i> Passport Photograph</h3>
                        <p class="text-muted small mb-3">Upload a recent passport photograph (max 500KB, JPG or PNG)</p>
                        
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <?php if (!empty($passport)): ?>
                                    <div class="document-preview">
                                        <img src="<?php echo e($passport['file_path']); ?>" alt="Passport" id="passport-preview">
                                    </div>
                                <?php else: ?>
                                    <div class="document-preview">
                                        <img src="/assets/images/default-avatar.png" alt="Passport Preview" id="passport-preview" style="display: none;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-8">
                                <input type="hidden" name="passport_confirmed" id="passport-confirmed" value="0">
                                <div class="mb-3">
                                    <label for="passport" class="form-label">Select Passport Photo</label>
                                    <input type="file" class="form-control" id="passport" name="passport" 
                                           accept="image/jpeg,image/png" onchange="confirmPassportUpload(this)">
                                    <small class="text-muted">Allowed: JPG, PNG. Max size: 500KB</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="/applicant/logout" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to logout? Your progress will be saved.');">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" onclick="document.getElementById('form_action').value='save'">
                                <i class="fas fa-save"></i> Save Progress
                            </button>
                            <button type="submit" class="btn btn-success" onclick="document.getElementById('form_action').value='next'">
                                Save & Continue <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="application-footer">
            <p>© <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
            <p>
                <i class="fas fa-phone-alt"></i> Support: 07039837749 | 
                <i class="fas fa-envelope"></i> Email: <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Add O'Level result item
    let olevelIndex = <?php echo count($olevel_results ?? [1]); ?>;

    document.getElementById('add-olevel').addEventListener('click', function() {
        const container = document.getElementById('olevel-results-container');
        const template = `
            <div class="olevel-result-item">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Exam Type</label>
                        <select class="form-select" name="olevel[${olevelIndex}][exam_type]" required>
                            <option value="WAEC">WAEC</option>
                            <option value="NECO">NECO</option>
                            <option value="NABTEB">NABTEB</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Year</label>
                        <input type="text" class="form-control" name="olevel[${olevelIndex}][exam_year]" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Exam Number</label>
                        <input type="text" class="form-control" name="olevel[${olevelIndex}][exam_number]">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Sitting</label>
                        <select class="form-select" name="olevel[${olevelIndex}][sitting]">
                            <option value="1st">1st Sitting</option>
                            <option value="2nd">2nd Sitting</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="this.closest('.olevel-result-item').remove()">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-2 mb-2">
                        <label class="form-label">English</label>
                        <select class="form-select" name="olevel[${olevelIndex}][english_grade]" required>
                            <option value="">Select</option>
                            <option value="A1">A1</option>
                            <option value="B2">B2</option>
                            <option value="B3">B3</option>
                            <option value="C4">C4</option>
                            <option value="C5">C5</option>
                            <option value="C6">C6</option>
                            <option value="D7">D7</option>
                            <option value="E8">E8</option>
                            <option value="F9">F9</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Mathematics</label>
                        <select class="form-select" name="olevel[${olevelIndex}][mathematics_grade]" required>
                            <option value="">Select</option>
                            <option value="A1">A1</option>
                            <option value="B2">B2</option>
                            <option value="B3">B3</option>
                            <option value="C4">C4</option>
                            <option value="C5">C5</option>
                            <option value="C6">C6</option>
                            <option value="D7">D7</option>
                            <option value="E8">E8</option>
                            <option value="F9">F9</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Biology</label>
                        <select class="form-select" name="olevel[${olevelIndex}][biology_grade]" required>
                            <option value="">Select</option>
                            <option value="A1">A1</option>
                            <option value="B2">B2</option>
                            <option value="B3">B3</option>
                            <option value="C4">C4</option>
                            <option value="C5">C5</option>
                            <option value="C6">C6</option>
                            <option value="D7">D7</option>
                            <option value="E8">E8</option>
                            <option value="F9">F9</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Chemistry</label>
                        <select class="form-select" name="olevel[${olevelIndex}][chemistry_grade]" required>
                            <option value="">Select</option>
                            <option value="A1">A1</option>
                            <option value="B2">B2</option>
                            <option value="B3">B3</option>
                            <option value="C4">C4</option>
                            <option value="C5">C5</option>
                            <option value="C6">C6</option>
                            <option value="D7">D7</option>
                            <option value="E8">E8</option>
                            <option value="F9">F9</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Physics</label>
                        <select class="form-select" name="olevel[${olevelIndex}][physics_grade]" required>
                            <option value="">Select</option>
                            <option value="A1">A1</option>
                            <option value="B2">B2</option>
                            <option value="B3">B3</option>
                            <option value="C4">C4</option>
                            <option value="C5">C5</option>
                            <option value="C6">C6</option>
                            <option value="D7">D7</option>
                            <option value="E8">E8</option>
                            <option value="F9">F9</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', template);
        olevelIndex++;
    });

    // Confirm passport upload
    function confirmPassportUpload(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                if (confirm('Is this your correct passport photograph? Click OK to upload.')) {
                    document.getElementById('passport-preview').src = e.target.result;
                    document.getElementById('passport-preview').style.display = 'block';
                    document.getElementById('passport-confirmed').value = '1';
                } else {
                    input.value = '';
                    document.getElementById('passport-preview').style.display = 'none';
                    document.getElementById('passport-confirmed').value = '0';
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form validation
    (function() {
        'use strict';
        
        var forms = document.querySelectorAll('.needs-validation');
        
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    })();

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