<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <!-- Progress Indicator -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary">FCT College of Nursing Sciences</h1>
                <p class="lead text-muted">2025/2026 Admissions Application Portal</p>
                
                <!-- Step Progress -->
                <div class="d-flex justify-content-center mt-4">
                    <div class="stepper-wrapper">
                        <div class="stepper-item completed">
                            <div class="step-counter"><i class="fas fa-check"></i></div>
                            <div class="step-name">JAMB Verified</div>
                        </div>
                        <div class="stepper-item active">
                            <div class="step-counter">2</div>
                            <div class="step-name">Application</div>
                        </div>
                        <div class="stepper-item">
                            <div class="step-counter">3</div>
                            <div class="step-name">Payment</div>
                        </div>
                        <div class="stepper-item">
                            <div class="step-counter">4</div>
                            <div class="step-name">Exam Slip</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Container for Dynamic Messages -->
            <div id="alertContainer" class="mb-4"></div>

            <!-- Application Form Card -->
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header bg-gradient-primary text-white py-4 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white bg-opacity-20 p-3 me-3">
                            <i class="fas fa-file-alt fa-2x text-white"></i>
                        </div>
                        <div>
                            <h2 class="h3 mb-1 fw-bold">Application Form</h2>
                            <p class="mb-0 opacity-75">Complete your details below to proceed</p>
                        </div>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-4 p-lg-5">
                    <!-- Flash Messages -->
                    <?php if (isset($flash_success)): ?>
                        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle fa-lg me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <?php echo $flash_success; ?>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($flash_error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle fa-lg me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <?php echo $flash_error; ?>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Main Form -->
                    <form id="applicationForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" id="jamb_number" name="jamb_number">
                        <input type="hidden" id="utme_score" name="utme_score">
                        
                        <!-- JAMB Data Summary Card -->
                        <div class="card bg-light border-0 rounded-3 mb-4" id="jambSummary">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                            <i class="fas fa-check-circle text-success fa-2x"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold mb-1">Loading JAMB data...</h5>
                                        <p class="text-muted mb-0 small">Please wait while we load your verified information</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-primary mb-0">
                                <i class="fas fa-user-circle me-2"></i>Personal Information
                            </h5>
                            <p class="text-muted small">Your JAMB information (read-only)</p>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-semibold">First Name</label>
                                <input type="text" class="form-control form-control-lg bg-light" id="first_name" readonly 
                                       style="background-color: #f8f9fa; cursor: not-allowed; border: 1px solid #e9ecef;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-semibold">Last Name</label>
                                <input type="text" class="form-control form-control-lg bg-light" id="last_name" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed; border: 1px solid #e9ecef;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-semibold">Other Names</label>
                                <input type="text" class="form-control form-control-lg bg-light" id="other_names" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed; border: 1px solid #e9ecef;">
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-semibold">Gender</label>
                                <input type="text" class="form-control bg-light" id="gender" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed; border: 1px solid #e9ecef;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-semibold">State of Origin</label>
                                <input type="text" class="form-control bg-light" id="state_of_origin" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed; border: 1px solid #e9ecef;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-semibold">LGA</label>
                                <input type="text" class="form-control bg-light" id="lga" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed; border: 1px solid #e9ecef;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-semibold">UTME Score</label>
                                <input type="text" class="form-control bg-light fw-bold text-success" id="utme_score_display" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed; border: 1px solid #e9ecef;">
                            </div>
                        </div>

                        <!-- Editable Fields Section -->
                        <div class="section-title mt-5 mb-4">
                            <h5 class="fw-bold text-primary mb-0">
                                <i class="fas fa-pen me-2"></i>Additional Information
                            </h5>
                            <p class="text-muted small">Please provide your contact details</p>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Date of Birth <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control form-control-lg" id="date_of_birth" name="date_of_birth" required>
                                <div class="invalid-feedback">Please provide your date of birth</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control form-control-lg" id="phone" name="phone" 
                                       placeholder="08012345678" pattern="[0-9]{11}" required
                                       maxlength="11" inputmode="numeric">
                                <div class="invalid-feedback">Phone number must be 11 digits</div>
                                <small class="text-muted">Enter 11-digit Nigerian mobile number</small>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">
                                Contact Address <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="address" name="address" rows="3" 
                                      placeholder="Enter your residential address" required></textarea>
                            <div class="invalid-feedback">Please provide your address</div>
                        </div>

                        <!-- Program Selection Section -->
                        <div class="section-title mt-5 mb-4">
                            <h5 class="fw-bold text-primary mb-0">
                                <i class="fas fa-graduation-cap me-2"></i>Program Selection
                            </h5>
                            <p class="text-muted small">Choose your preferred program</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8 col-lg-6">
                                <label class="form-label fw-semibold">
                                    Program Choice <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg" id="program_choice" name="program_choice" required>
                                    <option value="" selected disabled>Select a program</option>
                                    <option value="ND Nursing">ND Nursing</option>
                                    <option value="Post Basic Nursing">Post Basic Nursing</option>
                                    <option value="Midwifery">Midwifery</option>
                                    <option value="Public Health Nursing">Public Health Nursing</option>
                                </select>
                                <div class="invalid-feedback">Please select your program</div>
                            </div>
                        </div>

                        <!-- Document Upload Section -->
                        <div class="section-title mt-5 mb-4">
                            <h5 class="fw-bold text-primary mb-0">
                                <i class="fas fa-cloud-upload-alt me-2"></i>Document Upload
                            </h5>
                            <p class="text-muted small">Upload required documents (PDF, JPG, PNG accepted)</p>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="upload-area border rounded-3 p-4 text-center" id="passportArea">
                                    <i class="fas fa-camera fa-3x text-primary mb-3"></i>
                                    <h6 class="fw-bold">Passport Photograph <span class="text-danger">*</span></h6>
                                    <p class="text-muted small mb-3">Upload a recent passport photo</p>
                                    <input type="file" class="form-control" id="passport" name="passport" 
                                           accept="image/jpeg,image/png" required style="display: none;">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('passport').click()">
                                        <i class="fas fa-upload me-2"></i>Choose File
                                    </button>
                                    <small class="text-muted d-block mt-2">Max size: 1MB. Format: JPG, PNG</small>
                                    <div id="passportPreview" class="mt-3"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="upload-area border rounded-3 p-4 text-center" id="olevelArea">
                                    <i class="fas fa-file-pdf fa-3x text-primary mb-3"></i>
                                    <h6 class="fw-bold">O'Level Results <span class="text-danger">*</span></h6>
                                    <p class="text-muted small mb-3">Upload WAEC/NECO results</p>
                                    <input type="file" class="form-control" id="olevel" name="olevel[]" 
                                           multiple accept=".pdf,.jpg,.jpeg,.png" required style="display: none;">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('olevel').click()">
                                        <i class="fas fa-upload me-2"></i>Choose Files
                                    </button>
                                    <small class="text-muted d-block mt-2">Max 5 files, 2MB each. PDF or Images</small>
                                    <div id="olevelPreview" class="mt-3 text-start small"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Optional Documents -->
                        <div class="row g-3 mt-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">JAMB Result Slip (Optional)</label>
                                <input type="file" class="form-control" id="jamb_result" name="jamb_result" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Upload your JAMB result slip. Max 2MB.</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Birth Certificate (Optional)</label>
                                <input type="file" class="form-control" id="birth_certificate" name="birth_certificate" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Upload your birth certificate. Max 2MB.</small>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-5">
                            <a href="/apply/step/1" class="btn btn-outline-secondary btn-lg px-4" onclick="return confirmBack()">
                                <i class="fas fa-arrow-left me-2"></i>Back to JAMB Info
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                                <span id="submitText">
                                    <i class="fas fa-save me-2"></i>Save & Continue
                                </span>
                                <span id="submitSpinner" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Saving...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Card Footer -->
                <div class="card-footer bg-light py-3 px-4 border-0">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-lock text-muted me-2"></i>
                        <small class="text-muted">Your information is encrypted and securely stored</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.stepper-wrapper {
    display: flex;
    justify-content: space-between;
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
}

.stepper-item {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}

.stepper-item::before {
    position: absolute;
    content: "";
    border-bottom: 2px solid #e0e0e0;
    width: 100%;
    top: 20px;
    left: -50%;
    z-index: 2;
}

.stepper-item:first-child::before {
    content: none;
}

.stepper-item .step-counter {
    position: relative;
    z-index: 5;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    color: #6c757d;
    font-weight: 600;
    margin-bottom: 8px;
}

.stepper-item.active .step-counter {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}

.stepper-item.completed .step-counter {
    background: #28a745;
    border-color: #28a745;
    color: white;
}

.stepper-item .step-name {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
}

.stepper-item.active .step-name {
    color: #667eea;
    font-weight: 600;
}

.stepper-item.completed .step-name {
    color: #28a745;
}

.upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #667eea !important;
    background-color: #f8f9ff;
}

.section-title {
    position: relative;
    padding-bottom: 0.5rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 2px;
    background: linear-gradient(90deg, #667eea, transparent);
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stepper-item .step-name {
        font-size: 0.65rem;
    }
    
    .stepper-item .step-counter {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between a,
    .d-flex.justify-content-between button {
        width: 100%;
    }
}
</style>

<script>
// Load saved form data from database (passed from controller)
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($application)): ?>
    // Pre-fill form with existing data
    if (document.getElementById('date_of_birth') && '<?php echo $application['date_of_birth'] ?? ''; ?>') {
        document.getElementById('date_of_birth').value = '<?php echo $application['date_of_birth'] ?? ''; ?>';
    }
    if (document.getElementById('phone') && '<?php echo $application['phone'] ?? ''; ?>') {
        document.getElementById('phone').value = '<?php echo $application['phone'] ?? ''; ?>';
    }
    if (document.getElementById('address') && '<?php echo addslashes($application['address'] ?? ''); ?>') {
        document.getElementById('address').value = '<?php echo addslashes($application['address'] ?? ''); ?>';
    }
    if (document.getElementById('program_choice') && '<?php echo $application['program_choice_1'] ?? ''; ?>') {
        document.getElementById('program_choice').value = '<?php echo $application['program_choice_1'] ?? ''; ?>';
    }
    <?php endif; ?>
});

// Load JAMB data on page load - FIXED to check both sessionStorage AND server data
document.addEventListener('DOMContentLoaded', function() {
    // First check if we have JAMB data from the server (passed from PHP)
    <?php if (isset($jamb_data) && $jamb_data): ?>
    // Use server-provided JAMB data
    const serverJambData = <?php echo json_encode($jamb_data); ?>;
    console.log('Using server JAMB data:', serverJambData);
    
    // Fill JAMB data from server
    document.getElementById('first_name').value = serverJambData.first_name || '';
    document.getElementById('last_name').value = serverJambData.last_name || '';
    document.getElementById('other_names').value = serverJambData.other_names || '';
    
    // Convert gender code to full text if needed
    let genderText = '';
    if (serverJambData.gender === 'M') genderText = 'Male';
    else if (serverJambData.gender === 'F') genderText = 'Female';
    else genderText = serverJambData.gender || '';
    document.getElementById('gender').value = genderText;
    
    document.getElementById('state_of_origin').value = serverJambData.state_of_origin || '';
    document.getElementById('lga').value = serverJambData.lga || '';
    document.getElementById('utme_score_display').value = serverJambData.score || '';
    
    // Hidden fields
    document.getElementById('jamb_number').value = serverJambData.jamb_number || '';
    document.getElementById('utme_score').value = serverJambData.score || '';
    
    // Update summary
    document.getElementById('jambSummary').innerHTML = `
        <div class="card-body p-4">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="fas fa-check-circle text-success fa-2x"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="fw-bold mb-1">JAMB Verified Successfully</h5>
                    <p class="text-muted mb-0 small">
                        <strong>${serverJambData.first_name} ${serverJambData.last_name}</strong> | 
                        JAMB: ${serverJambData.jamb_number} | 
                        Score: <span class="badge bg-success">${serverJambData.score}</span>
                    </p>
                </div>
            </div>
        </div>
    `;
    
    <?php else: ?>
    // Fallback to sessionStorage if no server data
    const jambData = sessionStorage.getItem('jamb_data');
    const jambVerified = sessionStorage.getItem('jamb_verified');
    
    if (!jambData || !jambVerified) {
        // Check if we have application data from server instead
        <?php if (isset($application) && !empty($application['jamb_number'])): ?>
        // We have application data but no JAMB data in sessionStorage - this is OK
        console.log('No sessionStorage JAMB data but application exists - proceeding normally');
        
        // Construct JAMB data from application
        const appData = {
            jamb_number: '<?php echo $application['jamb_number'] ?? ''; ?>',
            first_name: '<?php echo $application['first_name'] ?? ''; ?>',
            last_name: '<?php echo $application['last_name'] ?? ''; ?>',
            other_names: '<?php echo $application['other_names'] ?? ''; ?>',
            gender: '<?php echo $application['gender'] ?? ''; ?>',
            state_of_origin: '<?php echo $application['state_of_origin'] ?? ''; ?>',
            lga: '<?php echo $application['lga'] ?? ''; ?>',
            score: '<?php echo $application['utme_score'] ?? ''; ?>'
        };
        
        // Fill JAMB data from application
        document.getElementById('first_name').value = appData.first_name || '';
        document.getElementById('last_name').value = appData.last_name || '';
        document.getElementById('other_names').value = appData.other_names || '';
        
        // Convert gender code to full text
        let genderText = '';
        if (appData.gender === 'M') genderText = 'Male';
        else if (appData.gender === 'F') genderText = 'Female';
        else genderText = appData.gender || '';
        document.getElementById('gender').value = genderText;
        
        document.getElementById('state_of_origin').value = appData.state_of_origin || '';
        document.getElementById('lga').value = appData.lga || '';
        document.getElementById('utme_score_display').value = appData.score || '';
        
        // Hidden fields
        document.getElementById('jamb_number').value = appData.jamb_number || '';
        document.getElementById('utme_score').value = appData.score || '';
        
        // Update summary
        document.getElementById('jambSummary').innerHTML = `
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold mb-1">JAMB Verified Successfully</h5>
                        <p class="text-muted mb-0 small">
                            <strong>${appData.first_name} ${appData.last_name}</strong> | 
                            JAMB: ${appData.jamb_number} | 
                            Score: <span class="badge bg-success">${appData.score}</span>
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        <?php else: ?>
        // No JAMB data anywhere - redirect to verification
        console.log('No JAMB data found anywhere, redirecting to verification');
        showAlert('Please verify your JAMB number first', 'warning');
        setTimeout(() => {
            window.location.href = '/apply/step/1';
        }, 2000);
        return;
        <?php endif; ?>
    } else {
        // Use sessionStorage data
        try {
            const data = JSON.parse(jambData);
            console.log('Loading JAMB data from sessionStorage:', data);
            
            // Fill JAMB data
            document.getElementById('first_name').value = data.first_name || '';
            document.getElementById('last_name').value = data.last_name || '';
            document.getElementById('other_names').value = data.other_names || '';
            
            // Convert gender code to full text
            let genderText = '';
            if (data.gender === 'M') genderText = 'Male';
            else if (data.gender === 'F') genderText = 'Female';
            document.getElementById('gender').value = genderText;
            
            document.getElementById('state_of_origin').value = data.state_of_origin || '';
            document.getElementById('lga').value = data.lga || '';
            document.getElementById('utme_score_display').value = data.score || '';
            
            // Hidden fields
            document.getElementById('jamb_number').value = data.jamb_number || '';
            document.getElementById('utme_score').value = data.score || '';
            
            // Update summary
            document.getElementById('jambSummary').innerHTML = `
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold mb-1">JAMB Verified Successfully</h5>
                            <p class="text-muted mb-0 small">
                                <strong>${data.first_name} ${data.last_name}</strong> | 
                                JAMB: ${data.jamb_number} | 
                                Score: <span class="badge bg-success">${data.score}</span>
                            </p>
                        </div>
                    </div>
                </div>
            `;
        } catch (e) {
            console.error('Error parsing JAMB data:', e);
            // Don't redirect - use application data if available
            <?php if (isset($application) && !empty($application['jamb_number'])): ?>
            console.log('Falling back to application data');
            // Fallback to application data (handled above)
            <?php else: ?>
            showAlert('Error loading JAMB data. Please verify again.', 'danger');
            setTimeout(() => {
                window.location.href = '/apply/step/1';
            }, 2000);
            <?php endif; ?>
        }
    }
    <?php endif; ?>
});

// Form submission with enhanced error handling
document.getElementById('applicationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Validate required fields
    const dob = document.getElementById('date_of_birth').value;
    const phone = document.getElementById('phone').value;
    const address = document.getElementById('address').value;
    const program = document.getElementById('program_choice').value;
    const passport = document.getElementById('passport').files[0];
    const olevel = document.getElementById('olevel').files;
    
    if (!dob) {
        showAlert('Please enter your date of birth', 'danger');
        return;
    }
    
    if (!phone || !/^[0-9]{11}$/.test(phone)) {
        showAlert('Please enter a valid 11-digit phone number', 'danger');
        return;
    }
    
    if (!address || address.trim() === '') {
        showAlert('Please enter your address', 'danger');
        return;
    }
    
    if (!program) {
        showAlert('Please select your program', 'danger');
        return;
    }
    
    if (!passport) {
        showAlert('Please upload your passport photograph', 'danger');
        return;
    }
    
    if (olevel.length === 0) {
        showAlert('Please upload your O\'Level results', 'danger');
        return;
    }
    
    // Validate JAMB data exists
    const jambNumber = document.getElementById('jamb_number').value;
    if (!jambNumber) {
        showAlert('JAMB verification data not found. Please restart your application.', 'danger');
        return;
    }
    
    // Show loading state
    document.getElementById('submitText').style.display = 'none';
    document.getElementById('submitSpinner').style.display = 'inline-block';
    document.getElementById('submitBtn').disabled = true;
    
    try {
        // Create FormData with all form data
        const formData = new FormData(this);
        
        // Add JAMB data
        formData.append('jamb_number', document.getElementById('jamb_number').value);
        formData.append('first_name', document.getElementById('first_name').value);
        formData.append('last_name', document.getElementById('last_name').value);
        formData.append('other_names', document.getElementById('other_names').value);
        
        // Convert gender text back to code
        const genderField = document.getElementById('gender').value;
        const genderCode = genderField === 'Male' ? 'M' : (genderField === 'Female' ? 'F' : '');
        formData.append('gender', genderCode);
        
        formData.append('state_of_origin', document.getElementById('state_of_origin').value);
        formData.append('lga', document.getElementById('lga').value);
        formData.append('utme_score', document.getElementById('utme_score').value || '');
        
        // Make the API call
        const response = await fetch('/apply/save-application', {
            method: 'POST',
            body: formData
        });
        
        // Check if response is OK
        if (!response.ok) {
            throw new Error(`Server error: ${response.status} ${response.statusText}`);
        }
        
        // Check content type
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Non-JSON response received:', text.substring(0, 200));
            throw new Error('Server returned an invalid response format');
        }
        
        // Parse JSON response
        const result = await response.json();
        
        if (result.success) {
            showAlert('Application saved successfully! Redirecting to payment...', 'success');
            
            // Redirect to payment page
            setTimeout(() => {
                window.location.href = '/apply/step/3';
            }, 2000);
        } else {
            // Show error message from server
            const errorMessage = result.message || 'Failed to save application. Please try again.';
            showAlert(errorMessage, 'danger');
            
            // If there are upload errors, show them
            if (result.upload_errors && result.upload_errors.length > 0) {
                console.warn('Upload errors:', result.upload_errors);
            }
            
            resetSubmitButton();
        }
    } catch (error) {
        console.error('Form submission error:', error);
        
        // Show user-friendly error message
        let userMessage = 'Network error. Please check your connection and try again.';
        
        if (error.message.includes('Failed to fetch')) {
            userMessage = 'Unable to connect to server. Please check your internet connection.';
        } else if (error.message.includes('JSON')) {
            userMessage = 'Server error. Please try again later.';
        } else if (error.message.includes('500')) {
            userMessage = 'Server error. Our team has been notified.';
        } else if (error.message.includes('404')) {
            userMessage = 'Service temporarily unavailable. Please try again.';
        } else if (error.message) {
            userMessage = error.message;
        }
        
        showAlert(userMessage, 'danger');
        resetSubmitButton();
    }
});

// File upload preview functions
document.getElementById('passport').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        
        // Validate file size
        if (file.size > 1 * 1024 * 1024) {
            showAlert('Passport image must be less than 1MB', 'warning');
            this.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match('image.*')) {
            showAlert('Please upload an image file (JPG, PNG)', 'warning');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('passportPreview');
            preview.innerHTML = `
                <div class="position-relative d-inline-block">
                    <img src="${e.target.result}" class="img-thumbnail rounded-3" style="max-height: 120px; border: 2px solid #28a745;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle" 
                            onclick="removePassport()" style="transform: translate(30%, -30%);">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <small class="text-success d-block mt-2">
                    <i class="fas fa-check-circle me-1"></i>${file.name} (${(file.size / 1024).toFixed(1)}KB)
                </small>
            `;
            
            // Update upload area styling
            document.getElementById('passportArea').classList.add('border-success');
        }
        reader.readAsDataURL(file);
    }
});

document.getElementById('olevel').addEventListener('change', function(e) {
    const files = this.files;
    const preview = document.getElementById('olevelPreview');
    
    if (files.length > 0) {
        let html = '<ul class="list-unstyled mb-0">';
        let totalSize = 0;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            totalSize += file.size;
            
            // Validate each file
            if (file.size > 2 * 1024 * 1024) {
                showAlert(`File "${file.name}" exceeds 2MB limit`, 'warning');
                this.value = '';
                preview.innerHTML = '';
                return;
            }
            
            html += `
                <li class="mb-2">
                    <i class="fas fa-file-${file.type.includes('pdf') ? 'pdf' : 'image'} text-primary me-2"></i>
                    <small>${file.name.substring(0, 30)}${file.name.length > 30 ? '...' : ''} (${(file.size / 1024).toFixed(1)}KB)</small>
                </li>
            `;
        }
        
        // Check total size (optional - adjust as needed)
        if (totalSize > 10 * 1024 * 1024) {
            showAlert('Total file size exceeds 10MB. Please compress files.', 'warning');
            this.value = '';
            preview.innerHTML = '';
            return;
        }
        
        html += '</ul>';
        html += `<small class="text-success"><i class="fas fa-check-circle me-1"></i>${files.length} file(s) selected</small>`;
        preview.innerHTML = html;
        
        // Update upload area styling
        document.getElementById('olevelArea').classList.add('border-success');
    } else {
        preview.innerHTML = '';
        document.getElementById('olevelArea').classList.remove('border-success');
    }
});

function removePassport() {
    document.getElementById('passport').value = '';
    document.getElementById('passportPreview').innerHTML = '';
    document.getElementById('passportArea').classList.remove('border-success');
}

function confirmBack() {
    return confirm('Are you sure you want to go back to JAMB verification? Your form data will be lost if you haven\'t saved.');
}

function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    const icon = type === 'success' ? 'fa-check-circle' : 
                 type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle';
    
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas ${icon} fa-lg me-2"></i>
                </div>
                <div class="flex-grow-1">
                    ${message}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    // Auto dismiss after 5 seconds (except for errors)
    if (type !== 'danger') {
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => alertContainer.innerHTML = '', 300);
            }
        }, 5000);
    }
}

function resetSubmitButton() {
    document.getElementById('submitText').style.display = 'inline-block';
    document.getElementById('submitSpinner').style.display = 'none';
    document.getElementById('submitBtn').disabled = false;
}

// Bootstrap form validation
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