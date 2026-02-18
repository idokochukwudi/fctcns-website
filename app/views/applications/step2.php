<?php
/**
 * Step 2: Application Form View
 * FIXED: Removed score field, expanded width for better fit
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

// Flash messages
$flash_success = $flash_success ?? $_SESSION['flash_success'] ?? null;
$flash_error = $flash_error ?? $_SESSION['flash_error'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 2: Application Form - FCT College of Nursing Sciences</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            max-width: 1200px; /* Increased from 1000px to 1200px */
            margin: 0 auto;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .card-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 2rem;
        }
        
        .card-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .card-body {
            padding: 50px; /* Increased padding */
            background: white;
        }
        
        .jumbotron {
            background: #f0f4f8;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            border-left: 5px solid #667eea;
        }
        
        .jumbotron h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.4rem;
        }
        
        .jumbotron p {
            margin: 0;
            color: #666;
            font-size: 1.1rem;
        }
        
        .badge-score {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .form-section {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .form-section h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.4rem;
            font-weight: 600;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .form-section h3 i {
            color: #667eea;
            margin-right: 10px;
        }
        
        .form-label {
            font-weight: 500;
            color: #555;
            font-size: 0.95rem;
        }
        
        .form-label .text-danger {
            font-size: 1.2rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        
        .form-control[readonly] {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
        
        .document-preview {
            text-align: center;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 10px;
            border: 2px dashed #ddd;
        }
        
        .document-preview img {
            max-width: 180px; /* Increased from 150px */
            max-height: 180px;
            border-radius: 5px;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.3);
        }
        
        .btn-success {
            background: #28a745;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .btn-outline-danger {
            border: 2px solid #dc3545;
            color: #dc3545;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
        }
        
        .btn-outline-danger:hover {
            background: #dc3545;
            color: white;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            border-left: 5px solid;
            margin-bottom: 20px;
            padding: 15px 20px;
        }
        
        .alert-success {
            background: #d4edda;
            border-left-color: #28a745;
        }
        
        .alert-danger {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        
        .alert-warning {
            background: #fff3cd;
            border-left-color: #ffc107;
        }
        
        .olevel-result-item {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .text-muted small {
            display: block;
            margin-top: 5px;
            font-size: 0.9rem;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: rgba(255,255,255,0.9);
            font-size: 0.95rem;
        }
        
        .footer a {
            color: white;
            text-decoration: none;
            border-bottom: 1px dotted rgba(255,255,255,0.5);
        }
        
        .footer a:hover {
            border-bottom-color: white;
        }
        
        /* Grid adjustments for better spacing */
        .row {
            margin-right: -15px;
            margin-left: -15px;
        }
        
        .col-md-2, .col-md-3, .col-md-4, .col-md-6 {
            padding-right: 15px;
            padding-left: 15px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 25px;
            }
            
            .form-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Flash Messages at VERY TOP -->
        <?php if (!empty($flash_success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo e($flash_success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
        
        <?php if (!empty($flash_error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo e($flash_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
        
        <?php if (!empty($temp_password)): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="fas fa-key me-2"></i>Your Login Password</h5>
                <p class="mb-2">Please save this password. You'll need it to log in later:</p>
                <div class="bg-light p-3 text-center rounded">
                    <strong style="font-size: 1.5rem; font-family: monospace;"><?php echo e($temp_password); ?></strong>
                </div>
                <p class="mt-2 mb-0 small text-muted">
                    <i class="fas fa-info-circle"></i> This password will also be sent to your email after you provide it.
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h2>Step 2: Application Form</h2>
                <p>Please fill in your personal and academic details accurately</p>
            </div>
            
            <div class="card-body">
                <!-- JAMB Summary -->
                <div class="jumbotron">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4><i class="fas fa-check-circle text-success me-2"></i>JAMB Verified Successfully</h4>
                            <p class="mb-0">
                                <strong><?php echo e($jamb_data['first_name'] ?? $application['first_name']); ?> <?php echo e($jamb_data['last_name'] ?? $application['last_name']); ?></strong> | 
                                JAMB: <?php echo e($jamb_data['jamb_number'] ?? $application['jamb_number']); ?>
                            </p>
                        </div>
                        <a href="/applicant/logout" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to logout? Your progress will be saved.');">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </div>
                
                <!-- Error Display -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h5>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Main Form -->
                <form method="POST" action="/apply/save-application" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                    <input type="hidden" name="action" id="form_action" value="save">
                    <input type="hidden" name="jamb_number" value="<?php echo e($jamb_data['jamb_number'] ?? $application['jamb_number']); ?>">
                    <input type="hidden" name="utme_score" value="<?php echo e($jamb_data['score'] ?? $application['utme_score']); ?>">
                    <input type="hidden" name="first_name" value="<?php echo e($jamb_data['first_name'] ?? $application['first_name']); ?>">
                    <input type="hidden" name="last_name" value="<?php echo e($jamb_data['last_name'] ?? $application['last_name']); ?>">
                    <input type="hidden" name="other_names" value="<?php echo e($jamb_data['other_names'] ?? $application['other_names']); ?>">
                    <input type="hidden" name="gender" value="<?php echo e($jamb_data['gender'] ?? $application['gender']); ?>">
                    <input type="hidden" name="state_of_origin" value="<?php echo e($jamb_data['state_of_origin'] ?? $application['state_of_origin']); ?>">
                    <input type="hidden" name="lga" value="<?php echo e($jamb_data['lga'] ?? $application['lga']); ?>">
                    
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-user"></i> Personal Information</h3>
                        <p class="text-muted small mb-3">Fields from JAMB record cannot be edited. Please verify they are correct.</p>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" value="<?php echo e($jamb_data['first_name'] ?? $application['first_name']); ?>" readonly>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" value="<?php echo e($jamb_data['last_name'] ?? $application['last_name']); ?>" readonly>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Other Names</label>
                                <input type="text" class="form-control" value="<?php echo e($jamb_data['other_names'] ?? $application['other_names']); ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Gender</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo isset($jamb_data['gender']) ? ($jamb_data['gender'] == 'M' ? 'Male' : ($jamb_data['gender'] == 'F' ? 'Female' : '')) : (isset($application['gender']) ? ($application['gender'] == 'M' ? 'Male' : ($application['gender'] == 'F' ? 'Female' : '')) : ''); ?>" 
                                       readonly>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">State of Origin</label>
                                <input type="text" class="form-control" value="<?php echo e($jamb_data['state_of_origin'] ?? $application['state_of_origin']); ?>" readonly>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">LGA</label>
                                <input type="text" class="form-control" value="<?php echo e($jamb_data['lga'] ?? $application['lga']); ?>" readonly>
                            </div>
                            
                            <!-- Score field completely removed -->
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                       value="<?php echo e($application['date_of_birth'] ?? ''); ?>" required>
                                <div class="invalid-feedback">Date of birth is required.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nationality</label>
                                <input type="text" class="form-control" id="nationality" name="nationality" 
                                       value="<?php echo e($application['nationality'] ?? 'Nigerian'); ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo e($application['email'] ?? ($applicant['email'] ?? '')); ?>" required>
                                <div class="invalid-feedback">Valid email is required.</div>
                                <small class="text-muted">Your login credentials will be sent to this email</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo e($application['phone'] ?? ($applicant['phone'] ?? '')); ?>" 
                                       pattern="[0-9]{11}" maxlength="11" required>
                                <div class="invalid-feedback">Phone number is required.</div>
                                <small class="text-muted">Enter 11-digit Nigerian mobile number</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Contact Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="2" required><?php echo e($application['address'] ?? ''); ?></textarea>
                            <div class="invalid-feedback">Address is required.</div>
                        </div>
                    </div>
                    
                    <!-- Program Choice Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-graduation-cap"></i> Program Choice</h3>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Program <span class="text-danger">*</span></label>
                                <select class="form-select" id="program_choice_1" name="program_choice_1" required>
                                    <option value="">Select Program</option>
                                    <option value="ND Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'ND Nursing' ? 'selected' : ''; ?>>ND Nursing</option>
                                    <option value="Post Basic Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'Post Basic Nursing' ? 'selected' : ''; ?>>Post Basic Nursing</option>
                                </select>
                                <div class="invalid-feedback">Please select your program.</div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields for other choices -->
                        <input type="hidden" name="program_choice_2" value="">
                        <input type="hidden" name="program_choice_3" value="">
                    </div>
                    
                    <!-- O'Level Results Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-certificate"></i> O'Level Results</h3>
                        <p class="text-muted small mb-3">Credit passes required in English, Mathematics, Biology, Chemistry, and Physics.</p>
                        
                        <div id="olevel-results-container">
                            <?php if (!empty($olevel_results)): ?>
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
                                            <button type="button" class="btn btn-danger btn-sm remove-olevel w-100" onclick="this.closest('.olevel-result-item').remove()">
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
                                <!-- Default O'Level item -->
                                <div class="olevel-result-item mb-4 p-3 border rounded">
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
                                            <button type="button" class="btn btn-danger btn-sm remove-olevel w-100" onclick="this.closest('.olevel-result-item').remove()">
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
                                <i class="fas fa-plus me-2"></i> Add Another Sitting
                            </button>
                        </div>
                    </div>
                    
                    <!-- Passport Upload Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-camera"></i> Passport Photograph</h3>
                        <p class="text-muted small mb-3">Upload a recent passport photograph (max 500KB, JPG or PNG)</p>
                        
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <div class="document-preview">
                                    <?php if (!empty($passport) && !empty($passport['file_path'])): ?>
                                        <img src="<?php echo e($passport['file_path']); ?>" alt="Passport" id="passport-preview">
                                    <?php else: ?>
                                        <img src="/assets/images/default-avatar.png" alt="Passport Preview" id="passport-preview" style="max-width: 150px; max-height: 150px; display: none;">
                                    <?php endif; ?>
                                </div>
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
                        <a href="/apply/step/1" class="btn btn-outline-secondary" onclick="return confirm('Are you sure you want to go back? Unsaved data will be lost.')">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary me-2" onclick="document.getElementById('form_action').value='save'">
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
        <div class="footer">
            <p>© <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
            <p>
                <i class="fas fa-phone-alt"></i> Support: 07039837749 | 
                <i class="fas fa-envelope"></i> <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a>
            </p>
        </div>
    </div>
    
    <script>
    // Add O'Level result item
    let olevelIndex = <?php echo count($olevel_results ?? [1]); ?>;
    
    document.getElementById('add-olevel').addEventListener('click', function() {
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
                        <button type="button" class="btn btn-danger btn-sm remove-olevel w-100" onclick="this.closest('.olevel-result-item').remove()">
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
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>