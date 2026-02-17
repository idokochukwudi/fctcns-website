<div class="text-center mb-4">
    <h2>Step 1: JAMB Verification</h2>
    <p class="text-muted">Enter your JAMB registration number to begin your application</p>
</div>

<?php if (isset($portal_closed) && $portal_closed): ?>
    <div class="alert alert-warning text-center">
        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
        <h4>Application Portal Closed</h4>
        <p><?php echo htmlspecialchars($portal_message); ?></p>
        <hr>
        <p class="mb-0">The next admissions cycle will be announced on this portal.</p>
    </div>
<?php else: ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <?php if (empty($terms)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Terms and conditions are not available at the moment. Please try again later.
            </div>
        <?php else: ?>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-contract"></i> Terms and Conditions</h5>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                <h4><?php echo htmlspecialchars($terms['title']); ?></h4>
                <div class="terms-content">
                    <?php echo nl2br(htmlspecialchars($terms['content'])); ?>
                </div>
                <p class="text-muted mt-3"><small>Version: <?php echo htmlspecialchars($terms['version']); ?> | Effective: <?php echo date('jS F Y', strtotime($terms['effective_date'])); ?></small></p>
            </div>
        </div>
        
        <!-- JAMB Verification Form -->
        <form id="jambVerificationForm" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="form-group mb-3">
                <label for="jamb_number" class="form-label">JAMB Registration Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="jamb_number" name="jamb_number" 
                       placeholder="e.g., 202550805685FF" required pattern="[0-9A-Z]{10,14}">
                <div class="invalid-feedback">Please enter a valid JAMB number (10-14 characters, letters and numbers only).</div>
                <small class="text-muted">Enter the JAMB registration number you used for the 2025 UTME.</small>
            </div>
            
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="accept_terms" name="accept_terms" value="1" required>
                <label class="form-check-label" for="accept_terms">
                    I have read and agree to the Terms and Conditions <span class="text-danger">*</span>
                </label>
                <div class="invalid-feedback">You must accept the terms and conditions to proceed.</div>
            </div>
            
            <div id="alertContainer"></div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> By proceeding, you confirm that:
                <ul class="mb-0 mt-2">
                    <li>You have a minimum UTME score of <?php echo htmlspecialchars($settings['key_value']['min_utme_score'] ?? '170'); ?></li>
                    <li>You selected FCT College of Nursing Sciences as your first choice</li>
                    <li>You have the required O'Level credits</li>
                    <li>You are at least <?php echo htmlspecialchars($settings['key_value']['min_age'] ?? '16'); ?> years old</li>
                </ul>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg" id="verifyBtn">
                    <span id="btnText"><i class="fas fa-check-circle"></i> Verify JAMB Number & Continue</span>
                    <span id="btnSpinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Verifying...
                    </span>
                </button>
            </div>
        </form>

        <!-- Application Form (Hidden initially) -->
        <form id="applicationForm" style="display: none;" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required readonly>
                            <div class="invalid-feedback">First name is required.</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required readonly>
                            <div class="invalid-feedback">Last name is required.</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                            <div class="invalid-feedback">Date of birth is required.</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                            <div class="invalid-feedback">Please select your gender.</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State of Origin <span class="text-danger">*</span></label>
                            <select class="form-control" id="state" name="state" required>
                                <option value="">Select State</option>
                                <option value="Abia">Abia</option>
                                <option value="Adamawa">Adamawa</option>
                                <option value="Akwa Ibom">Akwa Ibom</option>
                                <option value="Anambra">Anambra</option>
                                <option value="Bauchi">Bauchi</option>
                                <option value="Bayelsa">Bayelsa</option>
                                <option value="Benue">Benue</option>
                                <option value="Borno">Borno</option>
                                <option value="Cross River">Cross River</option>
                                <option value="Delta">Delta</option>
                                <option value="Ebonyi">Ebonyi</option>
                                <option value="Edo">Edo</option>
                                <option value="Ekiti">Ekiti</option>
                                <option value="Enugu">Enugu</option>
                                <option value="FCT - Abuja">FCT - Abuja</option>
                                <option value="Gombe">Gombe</option>
                                <option value="Imo">Imo</option>
                                <option value="Jigawa">Jigawa</option>
                                <option value="Kaduna">Kaduna</option>
                                <option value="Kano">Kano</option>
                                <option value="Katsina">Katsina</option>
                                <option value="Kebbi">Kebbi</option>
                                <option value="Kogi">Kogi</option>
                                <option value="Kwara">Kwara</option>
                                <option value="Lagos">Lagos</option>
                                <option value="Nasarawa">Nasarawa</option>
                                <option value="Niger">Niger</option>
                                <option value="Ogun">Ogun</option>
                                <option value="Ondo">Ondo</option>
                                <option value="Osun">Osun</option>
                                <option value="Oyo">Oyo</option>
                                <option value="Plateau">Plateau</option>
                                <option value="Rivers">Rivers</option>
                                <option value="Sokoto">Sokoto</option>
                                <option value="Taraba">Taraba</option>
                                <option value="Yobe">Yobe</option>
                                <option value="Zamfara">Zamfara</option>
                            </select>
                            <div class="invalid-feedback">Please select your state of origin.</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="lga" class="form-label">LGA <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lga" name="lga" required>
                            <div class="invalid-feedback">LGA is required.</div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Contact Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            <div class="invalid-feedback">Address is required.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Program Selection</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="program_choice_1" class="form-label">Program Choice 1 <span class="text-danger">*</span></label>
                            <select class="form-control" id="program_choice_1" name="program_choice_1" required>
                                <option value="">Select Program</option>
                                <option value="ND Nursing">ND Nursing</option>
                                <option value="HND Nursing">HND Nursing</option>
                                <option value="ND/HND Nursing (Non-terminal)">ND/HND Nursing (Non-terminal)</option>
                                <option value="Post-Basic Nursing">Post-Basic Nursing</option>
                                <option value="Midwifery">Midwifery</option>
                            </select>
                            <div class="invalid-feedback">Please select your first choice program.</div>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="program_choice_2" class="form-label">Program Choice 2 (Optional)</label>
                            <select class="form-control" id="program_choice_2" name="program_choice_2">
                                <option value="">Select Program</option>
                                <option value="ND Nursing">ND Nursing</option>
                                <option value="HND Nursing">HND Nursing</option>
                                <option value="ND/HND Nursing (Non-terminal)">ND/HND Nursing (Non-terminal)</option>
                                <option value="Post-Basic Nursing">Post-Basic Nursing</option>
                                <option value="Midwifery">Midwifery</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="program_choice_3" class="form-label">Program Choice 3 (Optional)</label>
                            <select class="form-control" id="program_choice_3" name="program_choice_3">
                                <option value="">Select Program</option>
                                <option value="ND Nursing">ND Nursing</option>
                                <option value="HND Nursing">HND Nursing</option>
                                <option value="ND/HND Nursing (Non-terminal)">ND/HND Nursing (Non-terminal)</option>
                                <option value="Post-Basic Nursing">Post-Basic Nursing</option>
                                <option value="Midwifery">Midwifery</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-file-upload"></i> Document Upload</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="passport" class="form-label">Passport Photograph <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="passport" name="passport" accept="image/*" required onchange="previewPassport(this)">
                            <div class="invalid-feedback">Passport photograph is required.</div>
                            <small class="text-muted">Max size: 1MB. Format: JPG, PNG</small>
                            <div id="passportPreview" class="mt-2"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="olevel" class="form-label">O'Level Results <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="olevel" name="olevel[]" multiple accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="invalid-feedback">O'Level results are required.</div>
                            <small class="text-muted">Upload all your O'Level results (WAEC/NECO). Max 5 files, 2MB each. PDF or Images.</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="jamb_result" class="form-label">JAMB Result Slip</label>
                            <input type="file" class="form-control" id="jamb_result" name="jamb_result" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Optional: Upload your JAMB result slip. Max 2MB.</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="birth_certificate" class="form-label">Birth Certificate</label>
                            <input type="file" class="form-control" id="birth_certificate" name="birth_certificate" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Optional: Upload your birth certificate. Max 2MB.</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-success btn-lg" id="submitApplication">
                    <i class="fas fa-save"></i> Save and Continue to Payment
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
            </div>
        </form>
        <?php endif; ?>
        
        <hr class="my-4">
        
        <div class="text-center">
            <p class="mb-2">Already have an account?</p>
            <a href="/applicant/login" class="btn btn-outline-primary">
                <i class="fas fa-sign-in-alt"></i> Login to Continue Application
            </a>
        </div>
    </div>
</div>

<script>
// Form validation for Bootstrap
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

// Auto-format JAMB number - Convert to uppercase and remove special characters
document.getElementById('jamb_number').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
});

// JAMB Verification Form Submission
document.getElementById('jambVerificationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Check if terms are accepted
    if (!document.getElementById('accept_terms').checked) {
        showAlert('You must accept the terms and conditions to proceed', 'danger');
        return;
    }
    
    // Get form data
    const formData = new FormData(this);
    const jambNumber = document.getElementById('jamb_number').value.trim().toUpperCase();
    
    // Validate
    if (!jambNumber) {
        showAlert('Please enter your JAMB number', 'danger');
        return;
    }
    
    if (!/^[0-9A-Z]{10,14}$/.test(jambNumber)) {
        showAlert('Invalid JAMB number format', 'danger');
        return;
    }
    
    // Show loading
    document.getElementById('btnText').style.display = 'none';
    document.getElementById('btnSpinner').style.display = 'inline-block';
    document.getElementById('verifyBtn').disabled = true;
    
    try {
        const response = await fetch('/apply/verify-jamb', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        console.log('Response:', data); // Debug log
        
        if (data.success) {
            // Store the JAMB data in sessionStorage
            sessionStorage.setItem('jamb_data', JSON.stringify(data.data));
            sessionStorage.setItem('jamb_verified', 'true');
            
            // Show success message
            showAlert('JAMB verified successfully! Loading form...', 'success');
            
            // Hide verification form and show application form
            document.getElementById('jambVerificationForm').style.display = 'none';
            document.getElementById('applicationForm').style.display = 'block';
            
            // Fill in the JAMB data
            fillJAMBData(data.data);
            
        } else {
            showAlert(data.message || 'Verification failed', 'danger');
            resetButton();
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Network error. Please try again.', 'danger');
        resetButton();
    }
});

// Function to fill JAMB data in the form
function fillJAMBData(data) {
    // Extract name parts
    const fullName = data.name || '';
    const nameParts = fullName.split(' ');
    
    // For demo data, we have name and score
    // You'll need to adjust based on what your API returns
    
    document.getElementById('first_name').value = nameParts[0] || '';
    document.getElementById('last_name').value = nameParts.slice(1).join(' ') || '';
    
    // If your API returns more data, add them here
    if (data.gender) {
        document.getElementById('gender').value = data.gender;
    }
    if (data.state_of_origin) {
        document.getElementById('state').value = data.state_of_origin;
    }
    if (data.lga) {
        document.getElementById('lga').value = data.lga;
    }
    
    // Show a message that JAMB data is loaded
    const infoDiv = document.createElement('div');
    infoDiv.className = 'alert alert-info mt-3';
    infoDiv.innerHTML = `
        <i class="fas fa-info-circle me-2"></i>
        JAMB data loaded for <strong>${data.name}</strong> (UTME Score: ${data.score})
    `;
    document.getElementById('applicationForm').insertBefore(infoDiv, document.getElementById('applicationForm').firstChild);
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

function resetButton() {
    document.getElementById('btnText').style.display = 'inline-block';
    document.getElementById('btnSpinner').style.display = 'none';
    document.getElementById('verifyBtn').disabled = false;
}

// Handle passport preview
function previewPassport(input) {
    if (input.files && input.files[0]) {
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
        reader.readAsDataURL(input.files[0]);
    }
}

function removePassport() {
    document.getElementById('passport').value = '';
    document.getElementById('passportPreview').innerHTML = '';
}

// Handle form submission
document.getElementById('submitApplication').addEventListener('click', async function(e) {
    e.preventDefault();
    
    // Validate required fields
    const dob = document.getElementById('date_of_birth').value;
    const address = document.getElementById('address').value;
    const gender = document.getElementById('gender').value;
    const state = document.getElementById('state').value;
    const lga = document.getElementById('lga').value;
    const program1 = document.getElementById('program_choice_1').value;
    const passport = document.getElementById('passport').files[0];
    const olevel = document.getElementById('olevel').files;
    
    if (!dob) {
        showAlert('Please enter your date of birth', 'danger');
        return;
    }
    
    if (!gender) {
        showAlert('Please select your gender', 'danger');
        return;
    }
    
    if (!state) {
        showAlert('Please select your state of origin', 'danger');
        return;
    }
    
    if (!lga) {
        showAlert('Please enter your LGA', 'danger');
        return;
    }
    
    if (!address) {
        showAlert('Please enter your address', 'danger');
        return;
    }
    
    if (!program1) {
        showAlert('Please select your first choice program', 'danger');
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
    
    // Show loading on button
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    btn.disabled = true;
    
    try {
        // Create FormData with all form data
        const formData = new FormData();
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
        formData.append('jamb_number', jambData.jamb_number);
        formData.append('first_name', document.getElementById('first_name').value);
        formData.append('last_name', document.getElementById('last_name').value);
        formData.append('date_of_birth', dob);
        formData.append('gender', gender);
        formData.append('state', state);
        formData.append('lga', lga);
        formData.append('address', address);
        formData.append('program_choice_1', program1);
        formData.append('program_choice_2', document.getElementById('program_choice_2').value);
        formData.append('program_choice_3', document.getElementById('program_choice_3').value);
        formData.append('utme_score', jambData.score || '');
        formData.append('passport', passport);
        
        // Add O'Level files
        for (let i = 0; i < olevel.length; i++) {
            formData.append('olevel[]', olevel[i]);
        }
        
        // Add optional files
        const jambResult = document.getElementById('jamb_result').files[0];
        if (jambResult) {
            formData.append('jamb_result', jambResult);
        }
        
        const birthCertificate = document.getElementById('birth_certificate').files[0];
        if (birthCertificate) {
            formData.append('birth_certificate', birthCertificate);
        }
        
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
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Network error. Please try again.', 'danger');
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});

function goBack() {
    document.getElementById('applicationForm').style.display = 'none';
    document.getElementById('jambVerificationForm').style.display = 'block';
    resetButton();
    
    // Clear form data from session if user wants to restart
    if (confirm('Do you want to clear the JAMB data and start over?')) {
        sessionStorage.removeItem('jamb_data');
        sessionStorage.removeItem('jamb_verified');
        document.getElementById('jamb_number').value = '';
        document.getElementById('accept_terms').checked = false;
    }
}

// Check if user is logged in (you can add this)
document.addEventListener('DOMContentLoaded', function() {
    // Check if already verified and show form directly
    const jambVerified = sessionStorage.getItem('jamb_verified');
    const jambData = sessionStorage.getItem('jamb_data');
    
    if (jambVerified === 'true' && jambData) {
        document.getElementById('jambVerificationForm').style.display = 'none';
        document.getElementById('applicationForm').style.display = 'block';
        fillJAMBData(JSON.parse(jambData));
    }
});
</script>
<?php endif; ?>