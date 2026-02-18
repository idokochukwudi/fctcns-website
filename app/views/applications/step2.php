<?php
/**
 * Step 2: Application Form View
 * FIXED: JAMB score rendering, O'Level data loading, clean design
 * 
 * @package FCTCNS
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$application = $application ?? [];
$applicant = $applicant ?? [];
$jamb_data = $jamb_data ?? [];
$olevel_results = $olevel_results ?? [];
$passport = $passport ?? [];
$states = $states ?? [];
$programs = $programs ?? [];
$csrf_token = $csrf_token ?? '';
$temp_password = $temp_password ?? '';
$errors = $errors ?? [];

// Get applicant name for welcome message
$applicant_name = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
if (empty($applicant_name) && !empty($application)) {
    $applicant_name = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''));
}
if (empty($applicant_name)) {
    $applicant_name = 'Applicant';
}

// Get JAMB data from various sources
$jamb_first_name = $jamb_data['first_name'] ?? $application['first_name'] ?? '';
$jamb_last_name = $jamb_data['last_name'] ?? $application['last_name'] ?? '';
$jamb_other_names = $jamb_data['other_names'] ?? $application['other_names'] ?? '';
$jamb_number = $jamb_data['jamb_number'] ?? $application['jamb_number'] ?? '';
$jamb_gender = $jamb_data['gender'] ?? $application['gender'] ?? '';
$jamb_state = $jamb_data['state_of_origin'] ?? $application['state_of_origin'] ?? '';
$jamb_lga = $jamb_data['lga'] ?? $application['lga'] ?? '';
$jamb_score = $jamb_data['score'] ?? $application['utme_score'] ?? '';

// Debug log for JAMB data
error_log("JAMB Data in view - Score: " . $jamb_score . ", First Name: " . $jamb_first_name . ", Last Name: " . $jamb_last_name);

// Flash messages
$flash_success = $flash_success ?? $_SESSION['flash_success'] ?? null;
$flash_error = $flash_error ?? $_SESSION['flash_error'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form - Step 2 - FCT College of Nursing Sciences</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            margin: 0;
        }
        
        .application-wrapper {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .top-bar {
            background: white;
            border-radius: 16px;
            padding: 20px 30px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .welcome-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .welcome-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .welcome-text h4 {
            margin: 0;
            font-weight: 600;
            color: #2d3748;
        }
        
        .welcome-text p {
            margin: 5px 0 0;
            font-size: 0.9rem;
            color: #718096;
        }
        
        .logout-btn {
            padding: 10px 25px;
            border: 2px solid #fed7d7;
            border-radius: 50px;
            color: #e53e3e;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .logout-btn:hover {
            background: #e53e3e;
            color: white;
            border-color: #e53e3e;
        }
        
        .main-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .card-header i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ffd700;
        }
        
        .card-header h2 {
            margin: 0;
            font-weight: 600;
        }
        
        .card-header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        
        .card-body {
            padding: 40px;
        }
        
        .jamb-summary {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .jamb-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }
        
        .jamb-details h4 {
            margin: 0 0 5px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .jamb-details p {
            margin: 0;
            color: #718096;
        }
        
        .jamb-badge {
            background: #48bb78;
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .section-title {
            margin: 30px 0 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .section-title h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title h3 i {
            color: #667eea;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 3px;
        }
        
        .form-label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-label .required {
            color: #e53e3e;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
            outline: none;
        }
        
        .form-control[readonly] {
            background: #f7fafc;
            cursor: not-allowed;
        }
        
        .readonly-field {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            color: #4a5568;
        }
        
        .info-text {
            font-size: 0.85rem;
            color: #718096;
            margin-top: 5px;
        }
        
        .olevel-section {
            background: #f7fafc;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .olevel-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }
        
        .document-preview {
            text-align: center;
            padding: 20px;
            background: #f7fafc;
            border-radius: 12px;
        }
        
        .document-preview img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102,126,234,0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
        }
        
        .btn-outline-primary:hover {
            background: #667eea;
            color: white;
        }
        
        .btn-success {
            background: #48bb78;
            border: none;
            color: white;
        }
        
        .btn-success:hover {
            background: #38a169;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            border: none;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: #f0fff4;
            border-left-color: #48bb78;
            color: #22543d;
        }
        
        .alert-danger {
            background: #fff5f5;
            border-left-color: #e53e3e;
            color: #742a2a;
        }
        
        .alert-warning {
            background: #fffaf0;
            border-left-color: #ed8936;
            color: #744210;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: rgba(255,255,255,0.9);
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        
        .footer a {
            color: white;
            text-decoration: none;
            border-bottom: 1px dotted rgba(255,255,255,0.5);
        }
        
        .footer a:hover {
            border-bottom-color: white;
        }
        
        .temp-password {
            background: #ebf8ff;
            border: 2px dashed #4299e1;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .temp-password .password {
            font-size: 2rem;
            font-family: monospace;
            font-weight: bold;
            color: #2b6cb0;
            letter-spacing: 2px;
        }
        
        @media (max-width: 768px) {
            .card-body {
                padding: 25px;
            }
            
            .top-bar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .welcome-section {
                flex-direction: column;
                text-align: center;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="application-wrapper">
        <!-- Top Bar with Welcome and Logout -->
        <div class="top-bar">
            <div class="welcome-section">
                <div class="welcome-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="welcome-text">
                    <h4>Welcome, <?php echo e($applicant_name); ?></h4>
                    <p><i class="fas fa-id-card me-2"></i>Application #: <?php echo e($application['application_number'] ?? 'Not assigned'); ?></p>
                </div>
            </div>
            <a href="/applicant/logout" class="logout-btn" onclick="return confirm('Are you sure you want to logout? Your progress will be saved.');">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <div class="card-header">
                <i class="fas fa-file-alt"></i>
                <h2>Application Form</h2>
                <p>Step 2 of 4 - Complete your application details</p>
            </div>
            
            <div class="card-body">
                <!-- Alert Container -->
                <div id="alertContainer"></div>

                <!-- Flash Messages -->
                <?php if (!empty($flash_success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo e($flash_success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>
                
                <?php if (!empty($flash_error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo e($flash_error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>

                <?php if (!empty($temp_password)): ?>
                    <div class="temp-password">
                        <h4><i class="fas fa-key me-2"></i>Your Login Password</h4>
                        <p class="mb-3">Please save this password. You'll need it to log in later:</p>
                        <div class="password"><?php echo e($temp_password); ?></div>
                        <p class="mt-3 mb-0 small text-muted">
                            <i class="fas fa-info-circle"></i> This password will also be sent to your email.
                        </p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h5>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- JAMB Summary Card -->
                <div class="jamb-summary">
                    <div class="jamb-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="jamb-details">
                        <h4>JAMB Verified Successfully</h4>
                        <p>
                            <strong><?php echo e($jamb_first_name); ?> <?php echo e($jamb_last_name); ?></strong> | 
                            JAMB: <?php echo e($jamb_number); ?> | 
                            Score: <span class="jamb-badge"><?php echo e($jamb_score); ?></span>
                        </p>
                    </div>
                </div>

                <!-- Main Form -->
                <form method="POST" action="/apply/save-application" enctype="multipart/form-data" id="applicationForm" class="needs-validation" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                    <input type="hidden" name="action" id="form_action" value="save">
                    <input type="hidden" name="jamb_number" value="<?php echo e($jamb_number); ?>">
                    <input type="hidden" name="utme_score" value="<?php echo e($jamb_score); ?>">
                    <input type="hidden" name="first_name" value="<?php echo e($jamb_first_name); ?>">
                    <input type="hidden" name="last_name" value="<?php echo e($jamb_last_name); ?>">
                    <input type="hidden" name="other_names" value="<?php echo e($jamb_other_names); ?>">
                    <input type="hidden" name="gender" value="<?php echo e($jamb_gender); ?>">
                    <input type="hidden" name="state_of_origin" value="<?php echo e($jamb_state); ?>">
                    <input type="hidden" name="lga" value="<?php echo e($jamb_lga); ?>">
                    
                    <!-- Personal Information Section -->
                    <div class="section-title">
                        <h3><i class="fas fa-user-circle"></i> Personal Information</h3>
                        <p class="text-muted small">Fields from JAMB record cannot be edited. Please verify they are correct.</p>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="required">*</span></label>
                            <div class="readonly-field"><?php echo e($jamb_first_name); ?></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="required">*</span></label>
                            <div class="readonly-field"><?php echo e($jamb_last_name); ?></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Other Names</label>
                            <div class="readonly-field"><?php echo e($jamb_other_names); ?></div>
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-3">
                            <label class="form-label">Gender <span class="required">*</span></label>
                            <div class="readonly-field">
                                <?php 
                                $gender_display = '';
                                if ($jamb_gender == 'M') {
                                    $gender_display = 'Male';
                                } elseif ($jamb_gender == 'F') {
                                    $gender_display = 'Female';
                                } else {
                                    $gender_display = $jamb_gender;
                                }
                                echo e($gender_display);
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">State of Origin <span class="required">*</span></label>
                            <div class="readonly-field"><?php echo e($jamb_state); ?></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">LGA <span class="required">*</span></label>
                            <div class="readonly-field"><?php echo e($jamb_lga); ?></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">UTME Score <span class="required">*</span></label>
                            <div class="readonly-field fw-bold text-success"><?php echo e($jamb_score); ?></div>
                        </div>
                    </div>

                    <!-- Editable Fields -->
                    <div class="section-title mt-5">
                        <h3><i class="fas fa-pen"></i> Additional Information</h3>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth <span class="required">*</span></label>
                            <input type="date" class="form-control" name="date_of_birth" 
                                   value="<?php echo e($application['date_of_birth'] ?? ''); ?>" required>
                            <div class="invalid-feedback">Date of birth is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="required">*</span></label>
                            <input type="tel" class="form-control" name="phone" 
                                   value="<?php echo e($application['phone'] ?? ($applicant['phone'] ?? '')); ?>" 
                                   pattern="[0-9]{11}" maxlength="11" required>
                            <div class="invalid-feedback">Phone number is required.</div>
                            <div class="info-text">Enter 11-digit Nigerian mobile number</div>
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" class="form-control" name="email" 
                                   value="<?php echo e($application['email'] ?? ($applicant['email'] ?? '')); ?>" required>
                            <div class="invalid-feedback">Valid email is required.</div>
                            <div class="info-text">Your login credentials will be sent to this email</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nationality</label>
                            <input type="text" class="form-control" name="nationality" 
                                   value="<?php echo e($application['nationality'] ?? 'Nigerian'); ?>">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Contact Address <span class="required">*</span></label>
                        <textarea class="form-control" name="address" rows="3" required><?php echo e($application['address'] ?? ''); ?></textarea>
                        <div class="invalid-feedback">Address is required.</div>
                    </div>

                    <!-- Program Selection -->
                    <div class="section-title mt-5">
                        <h3><i class="fas fa-graduation-cap"></i> Program Selection</h3>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Program Choice <span class="required">*</span></label>
                            <select class="form-select" name="program_choice" required>
                                <option value="">Select a program</option>
                                <option value="ND Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'ND Nursing' ? 'selected' : ''; ?>>ND Nursing</option>
                                <option value="Post Basic Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'Post Basic Nursing' ? 'selected' : ''; ?>>Post Basic Nursing</option>
                                <option value="Midwifery" <?php echo ($application['program_choice_1'] ?? '') == 'Midwifery' ? 'selected' : ''; ?>>Midwifery</option>
                                <option value="Public Health Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'Public Health Nursing' ? 'selected' : ''; ?>>Public Health Nursing</option>
                            </select>
                            <div class="invalid-feedback">Please select your program.</div>
                        </div>
                    </div>

                    <!-- O'Level Results Section -->
                    <div class="section-title mt-5">
                        <h3><i class="fas fa-certificate"></i> O'Level Results</h3>
                        <p class="text-muted small">Credit passes required in English, Mathematics, Biology, Chemistry, and Physics.</p>
                    </div>

                    <div class="olevel-section">
                        <div id="olevel-results-container">
                            <?php if (!empty($olevel_results) && is_array($olevel_results)): ?>
                                <?php foreach ($olevel_results as $index => $result): ?>
                                <div class="olevel-result-item mb-4 p-3 border rounded">
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
                                            <input type="text" class="form-control" name="olevel[<?php echo $index; ?>][exam_year]" 
                                                   value="<?php echo e($result['exam_year'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label">Exam Number</label>
                                            <input type="text" class="form-control" name="olevel[<?php echo $index; ?>][exam_number]" 
                                                   value="<?php echo e($result['exam_number'] ?? ''); ?>">
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
                                            <button type="button" class="btn btn-danger btn-sm remove-olevel w-100" onclick="removeOlevelItem(this)">
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
                                <div class="olevel-result-item mb-4 p-3 border rounded">
                                    <?php include 'olevel-template.php'; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-outline-primary" id="add-olevel">
                                <i class="fas fa-plus me-2"></i>Add Another Sitting
                            </button>
                        </div>
                    </div>

                    <!-- Passport Upload Section -->
                    <div class="section-title mt-5">
                        <h3><i class="fas fa-camera"></i> Passport Photograph</h3>
                        <p class="text-muted small">Upload a recent passport photograph (max 1MB, JPG or PNG)</p>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <div class="document-preview">
                                <?php if (!empty($passport) && !empty($passport['file_path'])): ?>
                                    <img src="<?php echo e($passport['file_path']); ?>" alt="Passport" id="passport-preview" style="max-width: 150px; max-height: 150px;">
                                <?php else: ?>
                                    <img src="/assets/images/default-avatar.png" alt="Passport Preview" id="passport-preview" style="max-width: 150px; max-height: 150px;">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <input type="hidden" name="passport_confirmed" id="passport-confirmed" value="0">
                            <div class="mb-3">
                                <label for="passport" class="form-label">Select Passport Photo</label>
                                <input type="file" class="form-control" id="passport" name="passport" 
                                       accept="image/jpeg,image/png" onchange="previewImage(this)">
                                <div class="info-text mt-2">Allowed: JPG, PNG. Max size: 1MB</div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-5">
                        <a href="/apply/step/1" class="btn btn-outline-primary" 
                           onclick="return confirm('Are you sure you want to go back? Unsaved data will be lost.')">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary me-2" onclick="document.getElementById('form_action').value='save'">
                                <i class="fas fa-save me-2"></i>Save Progress
                            </button>
                            <button type="submit" class="btn btn-success" onclick="document.getElementById('form_action').value='next'">
                                Save & Continue <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
            <p>
                <i class="fas fa-phone-alt"></i> Support: 07039837749 | 
                <i class="fas fa-envelope"></i> Email: <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a>
            </p>
        </div>
    </div>

    <script>
    // Initialize O'Level index
    let olevelIndex = <?php echo count($olevel_results); ?>;
    
    // Add O'Level result item
    document.getElementById('add-olevel')?.addEventListener('click', function() {
        const container = document.getElementById('olevel-results-container');
        const template = `
            <div class="olevel-result-item mb-4 p-3 border rounded">
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
                        <button type="button" class="btn btn-danger btn-sm remove-olevel w-100" onclick="removeOlevelItem(this)">
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
    
    // Remove O'Level item
    function removeOlevelItem(button) {
        if (confirm('Are you sure you want to remove this O\'Level result?')) {
            button.closest('.olevel-result-item').remove();
        }
    }
    
    // Preview image before upload
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('passport-preview').src = e.target.result;
                document.getElementById('passport-confirmed').value = '1';
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
    </script>
</body>
</html>