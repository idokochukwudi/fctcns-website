<?php
/**
 * Step 2: Application Form View
 * 
 * @var array $application
 * @var array $olevel_results
 * @var array $passport
 * @var string $flash_success
 * @var string $flash_error
 * @var string $temp_password
 */

// Display flash messages at the VERY TOP
if (isset($flash_success) && $flash_success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($flash_success); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($flash_error) && $flash_error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($flash_error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($temp_password) && $temp_password): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-key me-2"></i>Your Login Password</h5>
        <p class="mb-2">Please save this password. You'll need it to log in later:</p>
        <div class="bg-light p-3 text-center rounded">
            <strong style="font-size: 1.5rem; font-family: monospace;"><?php echo htmlspecialchars($temp_password); ?></strong>
        </div>
        <p class="mt-2 mb-0 small text-muted">
            <i class="fas fa-info-circle"></i> This password will also be sent to your email after you provide it.
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="text-center mb-4">
    <h2>Step 2: Application Form</h2>
    <p class="text-muted">Please fill in your personal and academic details accurately</p>
</div>

<form method="POST" action="/apply/save-application" enctype="multipart/form-data" class="needs-validation" novalidate>
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
    <input type="hidden" name="action" id="form_action" value="save">
    
    <!-- Personal Information Section -->
    <div class="form-section">
        <h3><i class="fas fa-user"></i> Personal Information</h3>
        <p class="text-muted small">Fields from JAMB record cannot be edited. Please verify they are correct.</p>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" 
                       value="<?php echo htmlspecialchars($application['first_name'] ?? ''); ?>" readonly 
                       style="background-color: #f5f5f5; cursor: not-allowed;">
                <small class="text-muted">From JAMB record - cannot be changed</small>
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" 
                       value="<?php echo htmlspecialchars($application['last_name'] ?? ''); ?>" readonly
                       style="background-color: #f5f5f5; cursor: not-allowed;">
                <small class="text-muted">From JAMB record - cannot be changed</small>
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="other_names" class="form-label">Other Names</label>
                <input type="text" class="form-control" id="other_names" name="other_names" 
                       value="<?php echo htmlspecialchars($application['other_names'] ?? ''); ?>" readonly
                       style="background-color: #f5f5f5; cursor: not-allowed;">
                <small class="text-muted">From JAMB record - cannot be changed</small>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="gender" class="form-label">Gender</label>
                <input type="text" class="form-control" id="gender" name="gender" 
                       value="<?php echo isset($application['gender']) ? ($application['gender'] == 'M' ? 'Male' : ($application['gender'] == 'F' ? 'Female' : '')) : ''; ?>" readonly
                       style="background-color: #f5f5f5; cursor: not-allowed;">
                <small class="text-muted">From JAMB record</small>
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="state_of_origin" class="form-label">State of Origin</label>
                <input type="text" class="form-control" id="state_of_origin" name="state_of_origin" 
                       value="<?php echo htmlspecialchars($application['state_of_origin'] ?? ''); ?>" readonly
                       style="background-color: #f5f5f5; cursor: not-allowed;">
                <small class="text-muted">From JAMB record</small>
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="lga" class="form-label">LGA</label>
                <input type="text" class="form-control" id="lga" name="lga" 
                       value="<?php echo htmlspecialchars($application['lga'] ?? ''); ?>" readonly
                       style="background-color: #f5f5f5; cursor: not-allowed;">
                <small class="text-muted">From JAMB record</small>
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="utme_score" class="form-label">UTME Score</label>
                <input type="text" class="form-control" id="utme_score" name="utme_score" 
                       value="<?php echo htmlspecialchars($application['utme_score'] ?? ''); ?>" readonly
                       style="background-color: #f5f5f5; cursor: not-allowed;">
                <small class="text-muted">From JAMB record</small>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                       value="<?php echo htmlspecialchars($application['date_of_birth'] ?? ''); ?>" required>
                <div class="invalid-feedback">Date of birth is required.</div>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="nationality" class="form-label">Nationality</label>
                <input type="text" class="form-control" id="nationality" name="nationality" 
                       value="<?php echo htmlspecialchars($application['nationality'] ?? 'Nigerian'); ?>">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($application['email'] ?? ($applicant['email'] ?? '')); ?>" required>
                <div class="invalid-feedback">Valid email is required.</div>
                <small class="text-muted">Your login credentials will be sent to this email</small>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($application['phone'] ?? ($applicant['phone'] ?? '')); ?>" required>
                <div class="invalid-feedback">Phone number is required.</div>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="address" class="form-label">Contact Address <span class="text-danger">*</span></label>
            <textarea class="form-control" id="address" name="address" rows="2" required><?php echo htmlspecialchars($application['address'] ?? ''); ?></textarea>
            <div class="invalid-feedback">Address is required.</div>
        </div>
    </div>
    
    <!-- Program Choice Section - Simplified -->
    <div class="form-section">
        <h3><i class="fas fa-graduation-cap"></i> Program Choice</h3>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="program_choice_1" class="form-label">Select Program <span class="text-danger">*</span></label>
                <select class="form-select" id="program_choice_1" name="program_choice_1" required>
                    <option value="">Select Program</option>
                    <option value="ND Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'ND Nursing' ? 'selected' : ''; ?>>ND Nursing</option>
                    <option value="Post Basic Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'Post Basic Nursing' ? 'selected' : ''; ?>>Post Basic Nursing</option>
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
        <p class="text-muted small">Provide your O'Level results. Credit passes required in English, Mathematics, Biology, Chemistry, and Physics.</p>
        
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
                            <input type="text" class="form-control" name="olevel[<?php echo $index; ?>][exam_year]" value="<?php echo htmlspecialchars($result['exam_year'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Exam Number</label>
                            <input type="text" class="form-control" name="olevel[<?php echo $index; ?>][exam_number]" value="<?php echo htmlspecialchars($result['exam_number'] ?? ''); ?>">
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
                <i class="fas fa-plus"></i> Add Another Sitting
            </button>
        </div>
    </div>
    
    <!-- Passport Upload Section -->
    <div class="form-section">
        <h3><i class="fas fa-camera"></i> Passport Photograph</h3>
        <p class="text-muted small">Upload a recent passport photograph (max 500KB, JPG or PNG)</p>
        
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                <?php if (!empty($passport)): ?>
                    <div class="document-preview">
                        <img src="<?php echo htmlspecialchars($passport['file_path']); ?>" alt="Passport" id="passport-preview" style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                <?php else: ?>
                    <div class="document-preview">
                        <img src="/assets/images/default-avatar.png" alt="Passport Preview" id="passport-preview" style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px; display: none;">
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
    
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <h5><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h5>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between mt-4">
        <a href="/applicant/logout" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to logout? Your progress will be saved.');">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <div>
            <button type="submit" class="btn btn-primary" onclick="document.getElementById('form_action').value='save'">
                <i class="fas fa-save"></i> Save Progress
            </button>
            <button type="submit" class="btn btn-success" onclick="document.getElementById('form_action').value='next'">
                Save & Continue <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</form>

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