<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Progress Indicator -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary">FCT College of Nursing Sciences</h1>
                <p class="lead">2025/2026 Admissions Application Portal</p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <span class="badge bg-success rounded-pill px-3 py-2">✓ Step 1: JAMB Verified</span>
                    <span class="badge bg-primary rounded-pill px-3 py-2">Step 2: Application Form</span>
                    <span class="badge bg-secondary rounded-pill px-3 py-2">Step 3: Payment</span>
                    <span class="badge bg-secondary rounded-pill px-3 py-2">Step 4: Exam Slip</span>
                </div>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer" class="mb-4"></div>

            <!-- Application Form -->
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                    <h2 class="mb-0">Application Form</h2>
                    <p class="mb-0">Step 2 of 4 - Complete your details below</p>
                </div>
                
                <div class="card-body p-4">
                    <!-- Flash Messages -->
                    <?php if (isset($flash_success)): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i><?php echo $flash_success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($flash_error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $flash_error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form id="applicationForm" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" id="jamb_number" name="jamb_number">
                        <input type="hidden" id="utme_score" name="utme_score">
                        
                        <!-- JAMB Data Summary -->
                        <div class="alert alert-info mb-4" id="jambSummary">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Loading JAMB data...</strong>
                        </div>

                        <!-- Personal Information -->
                        <h5 class="mb-3"><i class="fas fa-user me-2 text-primary"></i>Personal Information</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" readonly 
                                       style="background-color: #f8f9fa; cursor: not-allowed;">
                                <small class="text-muted">From JAMB record</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed;">
                                <small class="text-muted">From JAMB record</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Other Names</label>
                                <input type="text" class="form-control" id="other_names" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed;">
                                <small class="text-muted">From JAMB record</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Gender</label>
                                <input type="text" class="form-control" id="gender" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed;">
                                <small class="text-muted">From JAMB record</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">State of Origin</label>
                                <input type="text" class="form-control" id="state_of_origin" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed;">
                                <small class="text-muted">From JAMB record</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">LGA</label>
                                <input type="text" class="form-control" id="lga" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed;">
                                <small class="text-muted">From JAMB record</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">UTME Score</label>
                                <input type="text" class="form-control" id="utme_score_display" readonly
                                       style="background-color: #f8f9fa; cursor: not-allowed;">
                                <small class="text-muted">From JAMB record</small>
                            </div>
                        </div>

                        <!-- Editable Fields -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                                <div class="invalid-feedback">Date of birth is required.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       placeholder="08012345678" pattern="[0-9]{11}" required>
                                <div class="invalid-feedback">Phone number must be 11 digits.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contact Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                            <div class="invalid-feedback">Address is required.</div>
                        </div>

                        <!-- Program Selection -->
                        <h5 class="mb-3 mt-4"><i class="fas fa-graduation-cap me-2 text-primary"></i>Program Selection</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Program Choice <span class="text-danger">*</span></label>
                            <select class="form-control" id="program_choice" name="program_choice" required>
                                <option value="">Select Program</option>
                                <option value="ND Nursing">ND Nursing</option>
                                <option value="Post Basic Nursing">Post Basic Nursing</option>
                                <option value="Midwifery">Midwifery</option>
                                <option value="Public Health Nursing">Public Health Nursing</option>
                            </select>
                            <div class="invalid-feedback">Please select your program.</div>
                        </div>

                        <!-- Document Upload -->
                        <h5 class="mb-3 mt-4"><i class="fas fa-upload me-2 text-primary"></i>Document Upload</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Passport Photograph <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="passport" name="passport" 
                                   accept="image/jpeg,image/png" required>
                            <small class="text-muted">Max size: 1MB. Format: JPG, PNG</small>
                            <div id="passportPreview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">O'Level Results <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="olevel" name="olevel[]" 
                                   multiple accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Upload all your O'Level results (WAEC/NECO). Max 5 files, 2MB each. PDF or Images.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">JAMB Result Slip (Optional)</label>
                                <input type="file" class="form-control" id="jamb_result" name="jamb_result" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Upload your JAMB result slip. Max 2MB.</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Birth Certificate (Optional)</label>
                                <input type="file" class="form-control" id="birth_certificate" name="birth_certificate" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Upload your birth certificate. Max 2MB.</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 mt-4" id="submitBtn">
                            <span id="submitText"><i class="fas fa-save me-2"></i>Save and Continue to Payment</span>
                            <span id="submitSpinner" style="display: none;">
                                <i class="fas fa-spinner fa-spin me-2"></i>Saving...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Load JAMB data on page load
document.addEventListener('DOMContentLoaded', function() {
    const jambData = sessionStorage.getItem('jamb_data');
    const jambVerified = sessionStorage.getItem('jamb_verified');
    
    if (!jambData || !jambVerified) {
        showAlert('Please verify your JAMB number first', 'warning');
        setTimeout(() => {
            window.location.href = '/apply/step/1';
        }, 2000);
        return;
    }
    
    try {
        const data = JSON.parse(jambData);
        console.log('Loading JAMB data:', data);
        
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
            <i class="fas fa-check-circle me-2"></i>
            <strong>JAMB Verified:</strong> ${data.first_name} ${data.last_name} (JAMB: ${data.jamb_number}, Score: ${data.score})
        `;
    } catch (e) {
        console.error('Error parsing JAMB data:', e);
        showAlert('Error loading JAMB data. Please verify again.', 'danger');
        setTimeout(() => {
            window.location.href = '/apply/step/1';
        }, 2000);
    }
});

// Form submission
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
    
    if (!address) {
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
    const jambData = JSON.parse(sessionStorage.getItem('jamb_data') || '{}');
    if (!jambData.jamb_number) {
        showAlert('JAMB verification data not found. Please restart your application.', 'danger');
        return;
    }
    
    // Show loading
    document.getElementById('submitText').style.display = 'none';
    document.getElementById('submitSpinner').style.display = 'inline-block';
    document.getElementById('submitBtn').disabled = true;
    
    try {
        // Create FormData with all form data
        const formData = new FormData(this);
        
        // Add JAMB data
        formData.append('jamb_number', jambData.jamb_number);
        formData.append('first_name', document.getElementById('first_name').value);
        formData.append('last_name', document.getElementById('last_name').value);
        formData.append('other_names', document.getElementById('other_names').value);
        
        // Convert gender text back to code
        const genderField = document.getElementById('gender').value;
        const genderCode = genderField === 'Male' ? 'M' : (genderField === 'Female' ? 'F' : '');
        formData.append('gender', genderCode);
        
        formData.append('state_of_origin', document.getElementById('state_of_origin').value);
        formData.append('lga', document.getElementById('lga').value);
        formData.append('utme_score', jambData.score || '');
        
        const response = await fetch('/apply/save-application', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Application saved! Redirecting to payment...', 'success');
            setTimeout(() => {
                window.location.href = '/apply/step/3';
            }, 2000);
        } else {
            showAlert(result.message || 'Failed to save application', 'danger');
            resetSubmitButton();
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Network error. Please try again.', 'danger');
        resetSubmitButton();
    }
});

// Passport preview
document.getElementById('passport').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('passportPreview');
            preview.innerHTML = `
                <div class="position-relative d-inline-block">
                    <img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                            onclick="removePassport()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }
        reader.readAsDataURL(this.files[0]);
    }
});

function removePassport() {
    document.getElementById('passport').value = '';
    document.getElementById('passportPreview').innerHTML = '';
}

function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alertContainer.innerHTML = '', 300);
        }
    }, 5000);
}

function resetSubmitButton() {
    document.getElementById('submitText').style.display = 'inline-block';
    document.getElementById('submitSpinner').style.display = 'none';
    document.getElementById('submitBtn').disabled = false;
}
</script>