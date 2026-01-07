<?php
/**
 * Create Employee View
 * Form to add new employee to nominal roll
 */
?>
<div class="create-employee-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1>Add New Employee</h1>
                <p class="subtitle">Fill in employee details below</p>
            </div>
            <div class="header-actions">
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($flash_success)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($flash_success); ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($flash_error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($flash_error); ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <!-- Employee Form -->
    <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/store" enctype="multipart/form-data" class="employee-form" id="employeeForm">
        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
        
        <!-- Row 1: Basic Information -->
        <div class="form-row">
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-id-card"></i> Basic Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group required">
                            <label for="employee_number">Employee Number *</label>
                            <input type="text" 
                                   id="employee_number" 
                                   name="employee_number" 
                                   value="<?php echo htmlspecialchars($formData['employee_number'] ?? $employeeNumber ?? ''); ?>"
                                   class="form-control"
                                   required
                                   placeholder="EMP20240001">
                            <small class="form-text">Unique identifier for the employee</small>
                        </div>

                        <div class="form-group required">
                            <label for="surname">Surname *</label>
                            <input type="text" 
                                   id="surname" 
                                   name="surname" 
                                   value="<?php echo htmlspecialchars($formData['surname'] ?? ''); ?>"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group required">
                            <label for="first_name">First Name *</label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="<?php echo htmlspecialchars($formData['first_name'] ?? ''); ?>"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" 
                                   id="middle_name" 
                                   name="middle_name" 
                                   value="<?php echo htmlspecialchars($formData['middle_name'] ?? ''); ?>"
                                   class="form-control">
                        </div>

                        <div class="form-group required">
                            <label for="sex">Sex *</label>
                            <select id="sex" name="sex" class="form-control" required>
                                <option value="">Select Sex</option>
                                <option value="Male" <?php echo ($formData['sex'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($formData['sex'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>

                        <div class="form-group required">
                            <label for="date_of_birth">Date of Birth *</label>
                            <input type="date" 
                                   id="date_of_birth" 
                                   name="date_of_birth" 
                                   value="<?php echo htmlspecialchars($formData['date_of_birth'] ?? ''); ?>"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group required">
                            <label for="marital_status">Marital Status *</label>
                            <select id="marital_status" name="marital_status" class="form-control" required>
                                <option value="">Select Status</option>
                                <option value="Single" <?php echo ($formData['marital_status'] ?? '') === 'Single' ? 'selected' : ''; ?>>Single</option>
                                <option value="Married" <?php echo ($formData['marital_status'] ?? '') === 'Married' ? 'selected' : ''; ?>>Married</option>
                                <option value="Divorced" <?php echo ($formData['marital_status'] ?? '') === 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                                <option value="Widowed" <?php echo ($formData['marital_status'] ?? '') === 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                            </select>
                        </div>

                        <div class="form-group required">
                            <label for="nationality">Nationality *</label>
                            <select id="nationality" name="nationality" class="form-control" required>
                                <option value="">Select Nationality</option>
                                <option value="Nigerian" <?php echo ($formData['nationality'] ?? '') === 'Nigerian' ? 'selected' : ''; ?>>Nigerian</option>
                                <option value="Ghanaian" <?php echo ($formData['nationality'] ?? '') === 'Ghanaian' ? 'selected' : ''; ?>>Ghanaian</option>
                                <option value="Other" <?php echo ($formData['nationality'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="religion">Religion</label>
                            <select id="religion" name="religion" class="form-control">
                                <option value="">Select Religion</option>
                                <option value="Christianity" <?php echo ($formData['religion'] ?? '') === 'Christianity' ? 'selected' : ''; ?>>Christianity</option>
                                <option value="Islam" <?php echo ($formData['religion'] ?? '') === 'Islam' ? 'selected' : ''; ?>>Islam</option>
                                <option value="Traditional" <?php echo ($formData['religion'] ?? '') === 'Traditional' ? 'selected' : ''; ?>>Traditional Religion</option>
                                <option value="Other" <?php echo ($formData['religion'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Employment Details -->
        <div class="form-row">
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-briefcase"></i> Employment Details</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group required">
                            <label for="rank">Rank *</label>
                            <input type="text" 
                                   id="rank" 
                                   name="rank" 
                                   value="<?php echo htmlspecialchars($formData['rank'] ?? ''); ?>"
                                   class="form-control"
                                   required
                                   placeholder="e.g., Senior Lecturer">
                        </div>

                        <div class="form-group required">
                            <label for="grade_level">Grade Level (GL) *</label>
                            <select id="grade_level" name="grade_level" class="form-control" required>
                                <option value="">Select Grade Level</option>
                                <?php for ($i = 1; $i <= 17; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($formData['grade_level'] ?? '') == $i ? 'selected' : ''; ?>>
                                    GL <?php echo $i; ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="step">Step</label>
                            <select id="step" name="step" class="form-control">
                                <option value="">Select Step</option>
                                <?php for ($i = 1; $i <= 15; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($formData['step'] ?? '') == $i ? 'selected' : ''; ?>>
                                    Step <?php echo $i; ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cadre">Cadre</label>
                            <input type="text" 
                                   id="cadre" 
                                   name="cadre" 
                                   value="<?php echo htmlspecialchars($formData['cadre'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., Academic, Non-Academic">
                        </div>

                        <div class="form-group">
                            <label for="staff_type">Staff Type</label>
                            <select id="staff_type" name="staff_type" class="form-control">
                                <option value="">Select Staff Type</option>
                                <option value="Academic" <?php echo ($formData['staff_type'] ?? '') === 'Academic' ? 'selected' : ''; ?>>Academic</option>
                                <option value="Non-Academic" <?php echo ($formData['staff_type'] ?? '') === 'Non-Academic' ? 'selected' : ''; ?>>Non-Academic</option>
                                <option value="Administrative" <?php echo ($formData['staff_type'] ?? '') === 'Administrative' ? 'selected' : ''; ?>>Administrative</option>
                                <option value="Technical" <?php echo ($formData['staff_type'] ?? '') === 'Technical' ? 'selected' : ''; ?>>Technical</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="employment_type">Employment Type</label>
                            <select id="employment_type" name="employment_type" class="form-control">
                                <option value="">Select Employment Type</option>
                                <option value="Permanent" <?php echo ($formData['employment_type'] ?? '') === 'Permanent' ? 'selected' : ''; ?>>Permanent</option>
                                <option value="Contract" <?php echo ($formData['employment_type'] ?? '') === 'Contract' ? 'selected' : ''; ?>>Contract</option>
                                <option value="Adjunct" <?php echo ($formData['employment_type'] ?? '') === 'Adjunct' ? 'selected' : ''; ?>>Adjunct</option>
                                <option value="Visiting" <?php echo ($formData['employment_type'] ?? '') === 'Visiting' ? 'selected' : ''; ?>>Visiting</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="appointment_type">Appointment Type</label>
                            <select id="appointment_type" name="appointment_type" class="form-control">
                                <option value="">Select Appointment Type</option>
                                <option value="Confirmed" <?php echo ($formData['appointment_type'] ?? '') === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="Acting" <?php echo ($formData['appointment_type'] ?? '') === 'Acting' ? 'selected' : ''; ?>>Acting</option>
                                <option value="Secondment" <?php echo ($formData['appointment_type'] ?? '') === 'Secondment' ? 'selected' : ''; ?>>Secondment</option>
                                <option value="Deputation" <?php echo ($formData['appointment_type'] ?? '') === 'Deputation' ? 'selected' : ''; ?>>Deputation</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" 
                                   id="department" 
                                   name="department" 
                                   value="<?php echo htmlspecialchars($formData['department'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., Nursing Sciences">
                        </div>

                        <div class="form-group required">
                            <label for="date_of_first_appointment">Date of 1st Appointment *</label>
                            <input type="date" 
                                   id="date_of_first_appointment" 
                                   name="date_of_first_appointment" 
                                   value="<?php echo htmlspecialchars($formData['date_of_first_appointment'] ?? ''); ?>"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="date_of_confirmation">Date of Confirmation</label>
                            <input type="date" 
                                   id="date_of_confirmation" 
                                   name="date_of_confirmation" 
                                   value="<?php echo htmlspecialchars($formData['date_of_confirmation'] ?? ''); ?>"
                                   class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="rank_on_first_appointment">Rank on 1st Appointment</label>
                            <input type="text" 
                                   id="rank_on_first_appointment" 
                                   name="rank_on_first_appointment" 
                                   value="<?php echo htmlspecialchars($formData['rank_on_first_appointment'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Rank at first appointment">
                        </div>

                        <div class="form-group">
                            <label for="date_of_present_appointment">Date of Present Appointment</label>
                            <input type="date" 
                                   id="date_of_present_appointment" 
                                   name="date_of_present_appointment" 
                                   value="<?php echo htmlspecialchars($formData['date_of_present_appointment'] ?? ''); ?>"
                                   class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2b: Educational Qualifications -->
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-graduation-cap"></i> Educational Qualifications</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group required">
                            <label for="highest_qualification">Highest Qualification *</label>
                            <select id="highest_qualification" name="highest_qualification" class="form-control" required>
                                <option value="">Select Highest Qualification</option>
                                <option value="PhD" <?php echo ($formData['highest_qualification'] ?? '') === 'PhD' ? 'selected' : ''; ?>>PhD</option>
                                <option value="MSc" <?php echo ($formData['highest_qualification'] ?? '') === 'MSc' ? 'selected' : ''; ?>>MSc/M.A</option>
                                <option value="BSc" <?php echo ($formData['highest_qualification'] ?? '') === 'BSc' ? 'selected' : ''; ?>>BSc/B.A/B.Ed</option>
                                <option value="HND" <?php echo ($formData['highest_qualification'] ?? '') === 'HND' ? 'selected' : ''; ?>>HND</option>
                                <option value="OND" <?php echo ($formData['highest_qualification'] ?? '') === 'OND' ? 'selected' : ''; ?>>OND</option>
                                <option value="NCE" <?php echo ($formData['highest_qualification'] ?? '') === 'NCE' ? 'selected' : ''; ?>>NCE</option>
                                <option value="SSCE" <?php echo ($formData['highest_qualification'] ?? '') === 'SSCE' ? 'selected' : ''; ?>>SSCE/WASC</option>
                                <option value="FSLC" <?php echo ($formData['highest_qualification'] ?? '') === 'FSLC' ? 'selected' : ''; ?>>FSLC</option>
                                <option value="Others" <?php echo ($formData['highest_qualification'] ?? '') === 'Others' ? 'selected' : ''; ?>>Others</option>
                            </select>
                        </div>

                        <div class="form-group required">
                            <label for="year_of_highest_qualification">Year of Highest Qualification *</label>
                            <select id="year_of_highest_qualification" name="year_of_highest_qualification" class="form-control" required>
                                <option value="">Select Year</option>
                                <?php for ($year = date('Y'); $year >= 1960; $year--): ?>
                                <option value="<?php echo $year; ?>" <?php echo ($formData['year_of_highest_qualification'] ?? '') == $year ? 'selected' : ''; ?>>
                                    <?php echo $year; ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="institution_attended">Institution Attended</label>
                            <input type="text" 
                                   id="institution_attended" 
                                   name="institution_attended" 
                                   value="<?php echo htmlspecialchars($formData['institution_attended'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., University of Nigeria, Nsukka">
                        </div>

                        <div class="form-group">
                            <label for="course_of_study">Course of Study</label>
                            <input type="text" 
                                   id="course_of_study" 
                                   name="course_of_study" 
                                   value="<?php echo htmlspecialchars($formData['course_of_study'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., Nursing Science">
                        </div>

                        <div class="form-group">
                            <label for="class_of_degree">Class of Degree</label>
                            <select id="class_of_degree" name="class_of_degree" class="form-control">
                                <option value="">Select Class</option>
                                <option value="First Class" <?php echo ($formData['class_of_degree'] ?? '') === 'First Class' ? 'selected' : ''; ?>>First Class</option>
                                <option value="Second Class Upper" <?php echo ($formData['class_of_degree'] ?? '') === 'Second Class Upper' ? 'selected' : ''; ?>>Second Class Upper</option>
                                <option value="Second Class Lower" <?php echo ($formData['class_of_degree'] ?? '') === 'Second Class Lower' ? 'selected' : ''; ?>>Second Class Lower</option>
                                <option value="Third Class" <?php echo ($formData['class_of_degree'] ?? '') === 'Third Class' ? 'selected' : ''; ?>>Third Class</option>
                                <option value="Pass" <?php echo ($formData['class_of_degree'] ?? '') === 'Pass' ? 'selected' : ''; ?>>Pass</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="professional_certifications">Professional Certifications</label>
                            <textarea id="professional_certifications" 
                                      name="professional_certifications" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="List professional certifications separated by commas"><?php echo htmlspecialchars($formData['professional_certifications'] ?? ''); ?></textarea>
                        </div>

                        <!-- Additional Qualifications - FIXED -->
                        <div class="form-group">
                            <label>Additional Qualifications</label>
                            <div id="qualifications-container">
                                <!-- Initial field will be added by JavaScript -->
                            </div>
                            <button type="button" id="add-qualification-btn" class="btn btn-sm btn-outline">
                                <i class="fas fa-plus"></i> Add Qualification
                            </button>
                            <small class="form-text">Add other qualifications with year obtained</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Location & Origin -->
        <div class="form-row">
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Origin</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group required">
                            <label for="state">State of Origin *</label>
                            <select id="state" name="state" class="form-control" required>
                                <option value="">Select State</option>
                                <?php 
                                $nigerian_states = [
                                    'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
                                    'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT', 'Gombe',
                                    'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara',
                                    'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau',
                                    'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'
                                ];
                                foreach ($nigerian_states as $state): ?>
                                <option value="<?php echo htmlspecialchars($state); ?>"
                                    <?php echo ($formData['state'] ?? '') === $state ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($state); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group required">
                            <label for="local_govt_area">Local Government Area *</label>
                            <select id="local_govt_area" name="local_govt_area" class="form-control" required disabled>
                                <option value="">Select State first</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="geopolitical_zone">Geopolitical Zone</label>
                            <select id="geopolitical_zone" name="geopolitical_zone" class="form-control">
                                <option value="">Select Zone</option>
                                <option value="North Central" <?php echo ($formData['geopolitical_zone'] ?? '') === 'North Central' ? 'selected' : ''; ?>>North Central</option>
                                <option value="North East" <?php echo ($formData['geopolitical_zone'] ?? '') === 'North East' ? 'selected' : ''; ?>>North East</option>
                                <option value="North West" <?php echo ($formData['geopolitical_zone'] ?? '') === 'North West' ? 'selected' : ''; ?>>North West</option>
                                <option value="South East" <?php echo ($formData['geopolitical_zone'] ?? '') === 'South East' ? 'selected' : ''; ?>>South East</option>
                                <option value="South South" <?php echo ($formData['geopolitical_zone'] ?? '') === 'South South' ? 'selected' : ''; ?>>South South</option>
                                <option value="South West" <?php echo ($formData['geopolitical_zone'] ?? '') === 'South West' ? 'selected' : ''; ?>>South West</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="state_of_residence">State of Residence</label>
                            <select id="state_of_residence" name="state_of_residence" class="form-control">
                                <option value="">Same as State of Origin</option>
                                <?php foreach ($nigerian_states as $state): ?>
                                <option value="<?php echo htmlspecialchars($state); ?>"
                                    <?php echo ($formData['state_of_residence'] ?? '') === $state ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($state); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="residential_address">Residential Address</label>
                            <textarea id="residential_address" 
                                      name="residential_address" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Full residential address"><?php echo htmlspecialchars($formData['residential_address'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="contact_address">Contact Address</label>
                            <textarea id="contact_address" 
                                      name="contact_address" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Contact address if different from residential"><?php echo htmlspecialchars($formData['contact_address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3b: Medical & Identification -->
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-user-tie"></i> Medical & Identification</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="pf_number">Personal File (PF) Number</label>
                            <input type="text" 
                                   id="pf_number" 
                                   name="pf_number" 
                                   value="<?php echo htmlspecialchars($formData['pf_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., FCTCNS/PF/001">
                        </div>

                        <div class="form-group">
                            <label for="nhf_number">NHF Number</label>
                            <input type="text" 
                                   id="nhf_number" 
                                   name="nhf_number" 
                                   value="<?php echo htmlspecialchars($formData['nhf_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., NHF/12345/001">
                        </div>

                        <div class="form-group">
                            <label for="nin">NIN (National Identity Number)</label>
                            <input type="text" 
                                   id="nin" 
                                   name="nin" 
                                   value="<?php echo htmlspecialchars($formData['nin'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="11-digit NIN">
                        </div>

                        <div class="form-group">
                            <label for="telephone_number">Telephone Number</label>
                            <input type="tel" 
                                   id="telephone_number" 
                                   name="telephone_number" 
                                   value="<?php echo htmlspecialchars($formData['telephone_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., 08012345678"
                                   pattern="[0-9]{11}"
                                   title="11 digit Nigerian phone number">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., john.doe@example.com">
                        </div>

                        <div class="form-group">
                            <label for="blood_group">Blood Group</label>
                            <select id="blood_group" name="blood_group" class="form-control">
                                <option value="">Select Blood Group</option>
                                <option value="O+" <?php echo ($formData['blood_group'] ?? '') === 'O+' ? 'selected' : ''; ?>>O Positive (O+)</option>
                                <option value="O-" <?php echo ($formData['blood_group'] ?? '') === 'O-' ? 'selected' : ''; ?>>O Negative (O-)</option>
                                <option value="A+" <?php echo ($formData['blood_group'] ?? '') === 'A+' ? 'selected' : ''; ?>>A Positive (A+)</option>
                                <option value="A-" <?php echo ($formData['blood_group'] ?? '') === 'A-' ? 'selected' : ''; ?>>A Negative (A-)</option>
                                <option value="B+" <?php echo ($formData['blood_group'] ?? '') === 'B+' ? 'selected' : ''; ?>>B Positive (B+)</option>
                                <option value="B-" <?php echo ($formData['blood_group'] ?? '') === 'B-' ? 'selected' : ''; ?>>B Negative (B-)</option>
                                <option value="AB+" <?php echo ($formData['blood_group'] ?? '') === 'AB+' ? 'selected' : ''; ?>>AB Positive (AB+)</option>
                                <option value="AB-" <?php echo ($formData['blood_group'] ?? '') === 'AB-' ? 'selected' : ''; ?>>AB Negative (AB-)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="genotype">Genotype</label>
                            <select id="genotype" name="genotype" class="form-control">
                                <option value="">Select Genotype</option>
                                <option value="AA" <?php echo ($formData['genotype'] ?? '') === 'AA' ? 'selected' : ''; ?>>AA</option>
                                <option value="AS" <?php echo ($formData['genotype'] ?? '') === 'AS' ? 'selected' : ''; ?>>AS</option>
                                <option value="SS" <?php echo ($formData['genotype'] ?? '') === 'SS' ? 'selected' : ''; ?>>SS</option>
                                <option value="AC" <?php echo ($formData['genotype'] ?? '') === 'AC' ? 'selected' : ''; ?>>AC</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="disability">Disability</label>
                            <select id="disability" name="disability" class="form-control">
                                <option value="No" <?php echo ($formData['disability'] ?? '') === 'No' ? 'selected' : ''; ?>>No</option>
                                <option value="Yes" <?php echo ($formData['disability'] ?? '') === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                            </select>
                        </div>

                        <div class="form-group" id="disabilityTypeContainer" style="display: none;">
                            <label for="disability_type">Type of Disability</label>
                            <input type="text" 
                                   id="disability_type" 
                                   name="disability_type" 
                                   value="<?php echo htmlspecialchars($formData['disability_type'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Specify disability type">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 4: Financial Information -->
        <div class="form-row">
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-file-invoice-dollar"></i> Financial Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <select id="bank_name" name="bank_name" class="form-control">
                                <option value="">Select Bank</option>
                                <?php 
                                $nigerian_banks = [
                                    'Access Bank', 'Citibank', 'Ecobank', 'Fidelity Bank', 'First Bank',
                                    'First City Monument Bank', 'Guaranty Trust Bank', 'Heritage Bank',
                                    'Keystone Bank', 'Polaris Bank', 'Providus Bank', 'Stanbic IBTC Bank',
                                    'Standard Chartered Bank', 'Sterling Bank', 'Suntrust Bank',
                                    'Union Bank', 'United Bank for Africa', 'Unity Bank', 'Wema Bank',
                                    'Zenith Bank'
                                ];
                                foreach ($nigerian_banks as $bank): ?>
                                <option value="<?php echo htmlspecialchars($bank); ?>"
                                    <?php echo ($formData['bank_name'] ?? '') === $bank ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($bank); ?>
                                </option>
                                <?php endforeach; ?>
                                <option value="Other">Other Bank</option>
                            </select>
                        </div>

                        <div class="form-group" id="otherBankContainer" style="display: none;">
                            <label for="other_bank_name">Specify Bank Name</label>
                            <input type="text" 
                                   id="other_bank_name" 
                                   name="other_bank_name" 
                                   value="<?php echo htmlspecialchars($formData['other_bank_name'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Enter bank name">
                        </div>

                        <div class="form-group">
                            <label for="bank_branch">Bank Branch</label>
                            <input type="text" 
                                   id="bank_branch" 
                                   name="bank_branch" 
                                   value="<?php echo htmlspecialchars($formData['bank_branch'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., Gwagwalada Branch">
                        </div>

                        <div class="form-group">
                            <label for="account_number">Account Number</label>
                            <input type="text" 
                                   id="account_number" 
                                   name="account_number" 
                                   value="<?php echo htmlspecialchars($formData['account_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="10-20 digits"
                                   pattern="[0-9]{10,20}"
                                   title="10-20 digit account number">
                        </div>

                        <div class="form-group">
                            <label for="account_name">Account Name</label>
                            <input type="text" 
                                   id="account_name" 
                                   name="account_name" 
                                   value="<?php echo htmlspecialchars($formData['account_name'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Account holder's name">
                        </div>

                        <div class="form-group">
                            <label for="pension_fund_admin">Pension Fund Administrator (PFA)</label>
                            <select id="pension_fund_admin" name="pension_fund_admin" class="form-control">
                                <option value="">Select PFA</option>
                                <?php 
                                $pension_administrators = [
                                    'Access Pensions', 'AIICO Pension Managers', 'APT Pension Fund Managers',
                                    'ARM Pension Managers', 'Crusader Sterling Pensions', 'Fidelity Pension Managers',
                                    'First Guarantee Pension', 'Future Unity Glanvills Pensions', 'IEI-Anchor Pension Managers',
                                    'Investment One Pension Managers', 'Leadway Pensure PFA', 'Nigerian University Pension Management Co.',
                                    'NPF Pensions', 'Oak Pensions', 'OAK Pensions', 'PAL Pensions', 'Premium Pension',
                                    'Radix Pension Managers', 'Sigma Pensions', 'Stanbic IBTC Pension Managers',
                                    'Trustfund Pensions', 'Veritas Glanvills Pensions'
                                ];
                                foreach ($pension_administrators as $pfa): ?>
                                <option value="<?php echo htmlspecialchars($pfa); ?>"
                                    <?php echo ($formData['pension_fund_admin'] ?? '') === $pfa ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($pfa); ?>
                                </option>
                                <?php endforeach; ?>
                                <option value="Other">Other PFA</option>
                            </select>
                        </div>

                        <div class="form-group" id="otherPFAContainer" style="display: none;">
                            <label for="other_pension_fund_admin">Specify PFA</label>
                            <input type="text" 
                                   id="other_pension_fund_admin" 
                                   name="other_pension_fund_admin" 
                                   value="<?php echo htmlspecialchars($formData['other_pension_fund_admin'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Enter PFA name">
                        </div>

                        <div class="form-group">
                            <label for="pension_number">Pension Number</label>
                            <input type="text" 
                                   id="pension_number" 
                                   name="pension_number" 
                                   value="<?php echo htmlspecialchars($formData['pension_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Pension Registration Number">
                        </div>

                        <div class="form-group">
                            <label for="tin_number">Tax Identification No (TIN)</label>
                            <input type="text" 
                                   id="tin_number" 
                                   name="tin_number" 
                                   value="<?php echo htmlspecialchars($formData['tin_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="10-12 digit TIN">
                        </div>

                        <div class="form-group">
                            <label for="salary_structure">Salary Structure</label>
                            <select id="salary_structure" name="salary_structure" class="form-control">
                                <option value="">Select Salary Structure</option>
                                <option value="CONMESS" <?php echo ($formData['salary_structure'] ?? '') === 'CONMESS' ? 'selected' : ''; ?>>CONMESS</option>
                                <option value="CONTISS" <?php echo ($formData['salary_structure'] ?? '') === 'CONTISS' ? 'selected' : ''; ?>>CONTISS</option>
                                <option value="CONHESS" <?php echo ($formData['salary_structure'] ?? '') === 'CONHESS' ? 'selected' : ''; ?>>CONHESS</option>
                                <option value="CONPSS" <?php echo ($formData['salary_structure'] ?? '') === 'CONPSS' ? 'selected' : ''; ?>>CONPSS</option>
                                <option value="Others" <?php echo ($formData['salary_structure'] ?? '') === 'Others' ? 'selected' : ''; ?>>Others</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 4b: Emergency Contacts -->
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-user-friends"></i> Emergency Contacts & Next of Kin</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="emergency_contact_name">Emergency Contact Name</label>
                            <input type="text" 
                                   id="emergency_contact_name" 
                                   name="emergency_contact_name" 
                                   value="<?php echo htmlspecialchars($formData['emergency_contact_name'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Full name of emergency contact">
                        </div>

                        <div class="form-group">
                            <label for="emergency_contact_phone">Emergency Contact Phone</label>
                            <input type="tel" 
                                   id="emergency_contact_phone" 
                                   name="emergency_contact_phone" 
                                   value="<?php echo htmlspecialchars($formData['emergency_contact_phone'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., 08012345678"
                                   pattern="[0-9]{11}"
                                   title="11 digit Nigerian phone number">
                        </div>

                        <div class="form-group">
                            <label for="emergency_contact_relationship">Emergency Contact Relationship</label>
                            <select id="emergency_contact_relationship" name="emergency_contact_relationship" class="form-control">
                                <option value="">Select Relationship</option>
                                <option value="Spouse" <?php echo ($formData['emergency_contact_relationship'] ?? '') === 'Spouse' ? 'selected' : ''; ?>>Spouse</option>
                                <option value="Parent" <?php echo ($formData['emergency_contact_relationship'] ?? '') === 'Parent' ? 'selected' : ''; ?>>Parent</option>
                                <option value="Sibling" <?php echo ($formData['emergency_contact_relationship'] ?? '') === 'Sibling' ? 'selected' : ''; ?>>Sibling</option>
                                <option value="Child" <?php echo ($formData['emergency_contact_relationship'] ?? '') === 'Child' ? 'selected' : ''; ?>>Child</option>
                                <option value="Relative" <?php echo ($formData['emergency_contact_relationship'] ?? '') === 'Relative' ? 'selected' : ''; ?>>Relative</option>
                                <option value="Friend" <?php echo ($formData['emergency_contact_relationship'] ?? '') === 'Friend' ? 'selected' : ''; ?>>Friend</option>
                                <option value="Other" <?php echo ($formData['emergency_contact_relationship'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="next_of_kin_name">Next of Kin Name</label>
                            <input type="text" 
                                   id="next_of_kin_name" 
                                   name="next_of_kin_name" 
                                   value="<?php echo htmlspecialchars($formData['next_of_kin_name'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Full name of next of kin">
                        </div>

                        <div class="form-group">
                            <label for="next_of_kin_phone">Next of Kin Phone</label>
                            <input type="tel" 
                                   id="next_of_kin_phone" 
                                   name="next_of_kin_phone" 
                                   value="<?php echo htmlspecialchars($formData['next_of_kin_phone'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., 08012345678"
                                   pattern="[0-9]{11}"
                                   title="11 digit Nigerian phone number">
                        </div>

                        <div class="form-group">
                            <label for="next_of_kin_relationship">Next of Kin Relationship</label>
                            <select id="next_of_kin_relationship" name="next_of_kin_relationship" class="form-control">
                                <option value="">Select Relationship</option>
                                <option value="Spouse" <?php echo ($formData['next_of_kin_relationship'] ?? '') === 'Spouse' ? 'selected' : ''; ?>>Spouse</option>
                                <option value="Parent" <?php echo ($formData['next_of_kin_relationship'] ?? '') === 'Parent' ? 'selected' : ''; ?>>Parent</option>
                                <option value="Sibling" <?php echo ($formData['next_of_kin_relationship'] ?? '') === 'Sibling' ? 'selected' : ''; ?>>Sibling</option>
                                <option value="Child" <?php echo ($formData['next_of_kin_relationship'] ?? '') === 'Child' ? 'selected' : ''; ?>>Child</option>
                                <option value="Relative" <?php echo ($formData['next_of_kin_relationship'] ?? '') === 'Relative' ? 'selected' : ''; ?>>Relative</option>
                                <option value="Other" <?php echo ($formData['next_of_kin_relationship'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="next_of_kin_address">Next of Kin Address</label>
                            <textarea id="next_of_kin_address" 
                                      name="next_of_kin_address" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Full address of next of kin"><?php echo htmlspecialchars($formData['next_of_kin_address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 5: Passport Photo -->
        <div class="form-row">
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-camera"></i> Passport Photo</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="passport_photo">Upload Passport Photo</label>
                            <div class="file-upload">
                                <div class="upload-area">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Drag & drop or click to browse</p>
                                    <small>Max size: 2MB. Allowed: JPG, JPEG, PNG</small>
                                    <input type="file" 
                                           id="passport_photo" 
                                           name="passport_photo" 
                                           class="form-control-file"
                                           accept=".jpg,.jpeg,.png">
                                </div>
                            </div>
                            
                            <div class="upload-preview" id="uploadPreview" style="display: none;">
                                <div class="preview-header">
                                    <span>Preview</span>
                                    <button type="button" id="removeImage" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i> Remove
                                    </button>
                                </div>
                                <img id="previewImage" src="#" alt="Preview">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Save Employee Record
            </button>
            <button type="button" id="saveDraft" class="btn btn-secondary btn-lg">
                <i class="fas fa-save"></i> Save as Draft
            </button>
            <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline btn-lg">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<!-- Template for Qualification Entry - SIMPLE FIX -->
<template id="qualification-template">
    <div class="qualification-entry">
        <div class="qualification-row">
            <input type="text" 
                   name="qualification_name[]" 
                   class="form-control qualification-name"
                   placeholder="Qualification (e.g., BSc Nursing)">
            <select name="qualification_year[]" class="form-control qualification-year">
                <option value="">Year</option>
                <?php for ($year = date('Y'); $year >= 1960; $year--): ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php endfor; ?>
            </select>
            <button type="button" class="btn btn-danger remove-qualification" title="Remove">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</template>

<!-- JavaScript for Enhanced Form - SIMPLE WORKING VERSION -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Nigerian States and LGAs Data
    const nigerianLGAs = {
        'Abia': ['Aba North', 'Aba South', 'Arochukwu', 'Bende', 'Ikwuano', 'Isiala Ngwa North', 'Isiala Ngwa South', 
                'Isuikwuato', 'Obi Ngwa', 'Ohafia', 'Osisioma', 'Ugwunagbo', 'Ukwa East', 'Ukwa West', 
                'Umuahia North', 'Umuahia South', 'Umu Nneochi'],
        'Adamawa': ['Demsa', 'Fufure', 'Ganye', 'Girei', 'Gombi', 'Guyuk', 'Hong', 'Jada', 'Lamurde', 
                   'Madagali', 'Maiha', 'Mayo Belwa', 'Michika', 'Mubi North', 'Mubi South', 
                   'Numan', 'Shelleng', 'Song', 'Toungo', 'Yola North', 'Yola South'],
        'Akwa Ibom': ['Abak', 'Eastern Obolo', 'Eket', 'Esit Eket', 'Essien Udim', 'Etim Ekpo', 'Etinan', 
                     'Ibeno', 'Ibesikpo Asutan', 'Ibiono-Ibom', 'Ika', 'Ikono', 'Ikot Abasi', 
                     'Ikot Ekpene', 'Ini', 'Itu', 'Mbo', 'Mkpat-Enin', 'Nsit-Atai', 'Nsit-Ibom', 
                     'Nsit-Ubium', 'Obot Akara', 'Okobo', 'Onna', 'Oron', 'Oruk Anam', 'Udung-Uko', 
                     'Ukanafun', 'Uruan', 'Urue-Offong/Oruko', 'Uyo'],
        'Anambra': ['Aguata', 'Anambra East', 'Anambra West', 'Anaocha', 'Awka North', 'Awka South', 
                   'Ayamelum', 'Dunukofia', 'Ekwusigo', 'Idemili North', 'Idemili South', 'Ihiala', 
                   'Njikoka', 'Nnewi North', 'Nnewi South', 'Ogbaru', 'Onitsha North', 'Onitsha South', 
                   'Orumba North', 'Orumba South', 'Oyi'],
        'Bauchi': ['Alkaleri', 'Bauchi', 'Bogoro', 'Damban', 'Darazo', 'Dass', 'Gamawa', 'Ganjuwa', 
                  'Giade', 'Itas/Gadau', 'Jama\'are', 'Katagum', 'Kirfi', 'Misau', 'Ningi', 
                  'Shira', 'Tafawa Balewa', 'Toro', 'Warji', 'Zaki'],
        'Bayelsa': ['Brass', 'Ekeremor', 'Kolokuma/Opokuma', 'Nembe', 'Ogbia', 'Sagbama', 'Southern Ijaw', 'Yenagoa'],
        'Benue': ['Agatu', 'Apa', 'Ado', 'Buruku', 'Gboko', 'Guma', 'Gwer East', 'Gwer West', 
                 'Katsina-Ala', 'Konshisha', 'Kwande', 'Logo', 'Makurdi', 'Obi', 'Ogbadibo', 
                 'Ohimini', 'Oju', 'Okpokwu', 'Oturkpo', 'Tarka', 'Ukum', 'Ushongo', 'Vandeikya'],
        'Borno': ['Abadam', 'Askira/Uba', 'Bama', 'Bayo', 'Biu', 'Chibok', 'Damboa', 'Dikwa', 
                 'Gubio', 'Guzamala', 'Gwoza', 'Hawul', 'Jere', 'Kaga', 'Kala/Balge', 'Konduga', 
                 'Kukawa', 'Kwaya Kusar', 'Mafa', 'Magumeri', 'Maiduguri', 'Marte', 'Mobbar', 
                 'Monguno', 'Ngala', 'Nganzai', 'Shani'],
        'Cross River': ['Abi', 'Akamkpa', 'Akpabuyo', 'Bakassi', 'Bekwarra', 'Biase', 'Boki', 
                       'Calabar Municipal', 'Calabar South', 'Etung', 'Ikom', 'Obanliku', 'Obubra', 
                       'Obudu', 'Odukpani', 'Ogoja', 'Yakuur', 'Yala'],
        'Delta': ['Aniocha North', 'Aniocha South', 'Bomadi', 'Burutu', 'Ethiope East', 'Ethiope West', 
                 'Ika North East', 'Ika South', 'Isoko North', 'Isoko South', 'Ndokwa East', 
                 'Ndokwa West', 'Okpe', 'Oshimili North', 'Oshimili South', 'Patani', 'Sapele', 
                 'Udu', 'Ughelli North', 'Ughelli South', 'Ukwuani', 'Uvwie', 'Warri North', 
                 'Warri South', 'Warri South West'],
        'Ebonyi': ['Abakaliki', 'Afikpo North', 'Afikpo South', 'Ebonyi', 'Ezza North', 'Ezza South', 
                  'Ikwo', 'Ishielu', 'Ivo', 'Izzi', 'Ohaozara', 'Ohaukwu', 'Onicha'],
        'Edo': ['Akoko-Edo', 'Egor', 'Esan Central', 'Esan North-East', 'Esan South-East', 'Esan West', 
               'Etsako Central', 'Etsako East', 'Etsako West', 'Igueben', 'Ikpoba Okha', 'Orhionmwon', 
               'Oredo', 'Ovia North-East', 'Ovia South-West', 'Owan East', 'Owan West', 'Uhunmwonde'],
        'Ekiti': ['Ado Ekiti', 'Efon', 'Ekiti East', 'Ekiti South-West', 'Ekiti West', 'Emure', 
                 'Gbonyin', 'Ido Osi', 'Ijero', 'Ikere', 'Ikole', 'Ilejemeje', 'Irepodun/Ifelodun', 
                 'Ise/Orun', 'Moba', 'Oye'],
        'Enugu': ['Aninri', 'Awgu', 'Enugu East', 'Enugu North', 'Enugu South', 'Ezeagu', 'Igbo Etiti', 
                 'Igbo Eze North', 'Igbo Eze South', 'Isi Uzo', 'Nkanu East', 'Nkanu West', 
                 'Nsukka', 'Oji River', 'Udenu', 'Udi', 'Uzo Uwani'],
        'FCT': ['Abaji', 'Bwari', 'Gwagwalada', 'Kuje', 'Kwali', 'Municipal Area Council'],
        'Gombe': ['Akko', 'Balanga', 'Billiri', 'Dukku', 'Funakaye', 'Gombe', 'Kaltungo', 'Kwami', 'Nafada', 'Shongom', 'Yamaltu/Deba'],
        'Imo': ['Aboh Mbaise', 'Ahiazu Mbaise', 'Ehime Mbano', 'Ezinihitte', 'Ideato North', 'Ideato South', 
               'Ihitte/Uboma', 'Ikeduru', 'Isiala Mbano', 'Isu', 'Mbaitoli', 'Ngor Okpala', 
               'Njaba', 'Nkwerre', 'Nwangele', 'Obowo', 'Oguta', 'Ohaji/Egbema', 'Okigwe', 'Orlu', 
               'Orsu', 'Oru East', 'Oru West', 'Owerri Municipal', 'Owerri North', 'Owerri West', 'Unuimo'],
        'Jigawa': ['Auyo', 'Babura', 'Biriniwa', 'Birnin Kudu', 'Buji', 'Dutse', 'Gagarawa', 'Garki', 
                  'Gumel', 'Guri', 'Gwaram', 'Gwiwa', 'Hadejia', 'Jahun', 'Kafin Hausa', 'Kazaure', 
                  'Kiri Kasama', 'Kiyawa', 'Kaugama', 'Maigatari', 'Malam Madori', 'Miga', 'Ringim', 
                  'Roni', 'Sule Tankarkar', 'Taura', 'Yankwashi'],
        'Kaduna': ['Birnin Gwari', 'Chikun', 'Giwa', 'Igabi', 'Ikara', 'Jaba', 'Jema\'a', 'Kachia', 
                  'Kaduna North', 'Kaduna South', 'Kagarko', 'Kajuru', 'Kaura', 'Kauru', 'Kubau', 
                  'Kudan', 'Lere', 'Makarfi', 'Sabon Gari', 'Sanga', 'Soba', 'Zangon Kataf', 'Zaria'],
        'Kano': ['Ajingi', 'Albasu', 'Bagwai', 'Bebeji', 'Bichi', 'Bunkure', 'Dala', 'Dambatta', 
                'Dawakin Kudu', 'Dawakin Tofa', 'Doguwa', 'Fagge', 'Gabasawa', 'Garko', 'Garun Mallam', 
                'Gaya', 'Gezawa', 'Gwale', 'Gwarzo', 'Kabo', 'Kano Municipal', 'Karaye', 'Kibiya', 
                'Kiru', 'Kumbotso', 'Kunchi', 'Kura', 'Madobi', 'Makoda', 'Minjibir', 'Nasarawa', 
                'Rano', 'Rimin Gado', 'Rogo', 'Shanono', 'Sumaila', 'Takai', 'Tarauni', 'Tofa', 
                'Tsanyawa', 'Tudun Wada', 'Ungogo', 'Warawa', 'Wudil'],
        'Katsina': ['Bakori', 'Batagarawa', 'Batsari', 'Baure', 'Bindawa', 'Charanchi', 'Dan Musa', 
                   'Dandume', 'Danja', 'Daura', 'Dutsi', 'Dutsin Ma', 'Faskari', 'Funtua', 'Ingawa', 
                   'Jibia', 'Kafur', 'Kaita', 'Kankara', 'Kankia', 'Katsina', 'Kurfi', 'Kusada', 
                   'Mai\'Adua', 'Malumfashi', 'Mani', 'Mashi', 'Matazu', 'Musawa', 'Rimi', 'Sabuwa', 
                   'Safana', 'Sandamu', 'Zango'],
        'Kebbi': ['Aleiro', 'Arewa Dandi', 'Argungu', 'Augie', 'Bagudo', 'Birnin Kebbi', 'Bunza', 
                 'Dandi', 'Fakai', 'Gwandu', 'Jega', 'Kalgo', 'Koko/Besse', 'Maiyama', 'Ngaski', 
                 'Sakaba', 'Shanga', 'Suru', 'Wasagu/Danko', 'Yauri', 'Zuru'],
        'Kogi': ['Adavi', 'Ajaokuta', 'Ankpa', 'Bassa', 'Dekina', 'Ibaji', 'Idah', 'Igalamela Odolu', 
                'Ijumu', 'Kabba/Bunu', 'Kogi', 'Lokoja', 'Mopa Muro', 'Ofu', 'Ogori/Magongo', 
                'Okehi', 'Okene', 'Olamaboro', 'Omala', 'Yagba East', 'Yagba West'],
        'Kwara': ['Asa', 'Baruten', 'Edu', 'Ekiti', 'Ifelodun', 'Ilorin East', 'Ilorin South', 
                 'Ilorin West', 'Irepodun', 'Isin', 'Kaiama', 'Moro', 'Offa', 'Oke Ero', 'Oyun', 'Pategi'],
        'Lagos': ['Agege', 'Ajeromi-Ifelodun', 'Alimosho', 'Amuwo-Odofin', 'Apapa', 'Badagry', 'Epe', 
                 'Eti Osa', 'Ibeju-Lekki', 'Ifako-Ijaiye', 'Ikeja', 'Ikorodu', 'Kosofe', 'Lagos Island', 
                 'Lagos Mainland', 'Mushin', 'Ojo', 'Oshodi-Isolo', 'Shomolu', 'Surulere'],
        'Nasarawa': ['Akwanga', 'Awe', 'Doma', 'Karu', 'Keana', 'Keffi', 'Kokona', 'Lafia', 'Nasarawa', 'Nasarawa Egon', 'Obi', 'Toto', 'Wamba'],
        'Niger': ['Agaie', 'Agwara', 'Bida', 'Borgu', 'Bosso', 'Chanchaga', 'Edati', 'Gbako', 'Gurara', 
                 'Katcha', 'Kontagora', 'Lapai', 'Lavun', 'Magama', 'Mariga', 'Mashegu', 'Mokwa', 
                 'Moya', 'Paikoro', 'Rafi', 'Rijau', 'Shiroro', 'Suleja', 'Tafa', 'Wushishi'],
        'Ogun': ['Abeokuta North', 'Abeokuta South', 'Ado-Odo/Ota', 'Egbado North', 'Egbado South', 
                'Ewekoro', 'Ifo', 'Ijebu East', 'Ijebu North', 'Ijebu North East', 'Ijebu Ode', 
                'Ikenne', 'Imeko Afon', 'Ipokia', 'Obafemi Owode', 'Odeda', 'Odogbolu', 'Ogun Waterside', 
                'Remo North', 'Shagamu', 'Yewa North', 'Yewa South'],
        'Ondo': ['Akoko North-East', 'Akoko North-West', 'Akoko South-East', 'Akoko South-West', 
                'Akure North', 'Akure South', 'Ese Odo', 'Idanre', 'Ifedore', 'Ilaje', 'Ile Oluji/Okeigbo', 
                'Irele', 'Odigbo', 'Okitipupa', 'Ondo East', 'Ondo West', 'Ose', 'Owo'],
        'Osun': ['Atakunmosa East', 'Atakunmosa West', 'Aiyedaade', 'Aiyedire', 'Boluwaduro', 'Boripe', 
                'Ede North', 'Ede South', 'Ife Central', 'Ife East', 'Ife North', 'Ife South', 
                'Egbedore', 'Ejigbo', 'Ifedayo', 'Ifelodun', 'Ila', 'Ilesa East', 'Ilesa West', 
                'Irepodun', 'Irewole', 'Isokan', 'Iwo', 'Obokun', 'Odo Otin', 'Ola Oluwa', 'Olorunda', 
                'Oriade', 'Orolu', 'Osogbo'],
        'Oyo': ['Afijio', 'Akinyele', 'Atiba', 'Atisbo', 'Egbeda', 'Ibadan North', 'Ibadan North-East', 
               'Ibadan North-West', 'Ibadan South-East', 'Ibadan South-West', 'Ibarapa Central', 
               'Ibarapa East', 'Ibarapa North', 'Ido', 'Irepo', 'Iseyin', 'Itesiwaju', 'Iwajowa', 
               'Kajola', 'Lagelu', 'Ogbomosho North', 'Ogbomosho South', 'Ogo Oluwa', 'Olorunsogo', 
               'Oluyole', 'Ona Ara', 'Orelope', 'Ori Ire', 'Oyo East', 'Oyo West', 'Saki East', 
               'Saki West', 'Surulere'],
        'Plateau': ['Barkin Ladi', 'Bassa', 'Bokkos', 'Jos East', 'Jos North', 'Jos South', 'Kanam', 
                   'Kanke', 'Langtang North', 'Langtang South', 'Mangu', 'Mikang', 'Pankshin', 
                   'Qua\'an Pan', 'Riyom', 'Shendam', 'Wase'],
        'Rivers': ['Abua/Odual', 'Ahoada East', 'Ahoada West', 'Akuku-Toru', 'Andoni', 'Asari-Toru', 
                  'Bonny', 'Degema', 'Eleme', 'Emuoha', 'Etche', 'Gokana', 'Ikwerre', 'Khana', 
                  'Obio/Akpor', 'Ogba/Egbema/Ndoni', 'Ogu/Bolo', 'Okrika', 'Omuma', 'Opobo/Nkoro', 
                  'Oyigbo', 'Port Harcourt', 'Tai'],
        'Sokoto': ['Binji', 'Bodinga', 'Dange Shuni', 'Gada', 'Goronyo', 'Gudu', 'Gwadabawa', 'Illela', 
                  'Isa', 'Kebbe', 'Kware', 'Rabah', 'Sabon Birni', 'Shagari', 'Silame', 'Sokoto North', 
                  'Sokoto South', 'Tambuwal', 'Tangaza', 'Tureta', 'Wamako', 'Wurno', 'Yabo'],
        'Taraba': ['Ardo Kola', 'Bali', 'Donga', 'Gashaka', 'Gassol', 'Ibi', 'Jalingo', 'Karim Lamido', 
                  'Kurmi', 'Lau', 'Sardauna', 'Takum', 'Ussa', 'Wukari', 'Yorro', 'Zing'],
        'Yobe': ['Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 'Gujba', 'Gulani', 
                'Jakusko', 'Karasuwa', 'Machina', 'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari'],
        'Zamfara': ['Anka', 'Bakura', 'Birnin Magaji/Kiyaw', 'Bukkuyum', 'Bungudu', 'Chafe', 'Gummi', 
                   'Gusau', 'Kaura Namoda', 'Maradun', 'Maru', 'Shinkafi', 'Talata Mafara', 'Tsafe', 'Zurmi']
    };

    // State and LGA Selection
    const stateSelect = document.getElementById('state');
    const lgaSelect = document.getElementById('local_govt_area');

    if (stateSelect && lgaSelect) {
        stateSelect.addEventListener('change', function() {
            const selectedState = this.value;
            
            if (selectedState && nigerianLGAs[selectedState]) {
                lgaSelect.disabled = false;
                lgaSelect.innerHTML = '<option value="">Select LGA</option>';
                
                nigerianLGAs[selectedState].forEach(lga => {
                    const option = document.createElement('option');
                    option.value = lga;
                    option.textContent = lga;
                    lgaSelect.appendChild(option);
                });
                
                const formLGA = '<?php echo $formData["local_govt_area"] ?? ""; ?>';
                if (formLGA && nigerianLGAs[selectedState].includes(formLGA)) {
                    lgaSelect.value = formLGA;
                }
            } else {
                lgaSelect.disabled = true;
                lgaSelect.innerHTML = '<option value="">Select State first</option>';
            }
        });

        if (stateSelect.value) {
            stateSelect.dispatchEvent(new Event('change'));
        }
    }

    // Multiple Qualifications with Year - SIMPLE WORKING VERSION
    const qualificationsContainer = document.getElementById('qualifications-container');
    const addQualificationBtn = document.getElementById('add-qualification-btn');
    const qualificationTemplate = document.getElementById('qualification-template');

    function addQualificationField(name = '', year = '') {
        const templateContent = qualificationTemplate.content.cloneNode(true);
        const entry = templateContent.querySelector('.qualification-entry');
        
        // Set values if provided
        const nameInput = entry.querySelector('.qualification-name');
        const yearSelect = entry.querySelector('.qualification-year');
        
        nameInput.value = name;
        yearSelect.value = year;
        
        // Add remove functionality
        const removeBtn = entry.querySelector('.remove-qualification');
        removeBtn.addEventListener('click', function() {
            entry.remove();
        });
        
        qualificationsContainer.appendChild(entry);
    }
    
    // Add initial qualification field on page load
    addQualificationField();
    
    // Add more qualifications button
    addQualificationBtn.addEventListener('click', function() {
        addQualificationField();
    });

    // Image preview for passport photo
    const passportPhotoInput = document.getElementById('passport_photo');
    const previewImage = document.getElementById('previewImage');
    const uploadPreview = document.getElementById('uploadPreview');
    const removeImageBtn = document.getElementById('removeImage');
    const fileUploadArea = document.querySelector('.upload-area');
    
    if (passportPhotoInput) {
        // Click to browse
        fileUploadArea.addEventListener('click', function() {
            passportPhotoInput.click();
        });
        
        // File input change
        passportPhotoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                handleImageUpload(this.files[0]);
            }
        });

        function handleImageUpload(file) {
            // Check file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                passportPhotoInput.value = '';
                return;
            }
            
            // Check file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Only JPG, JPEG, and PNG files are allowed');
                passportPhotoInput.value = '';
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                uploadPreview.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
        }
    }
    
    // Remove image button
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            passportPhotoInput.value = '';
            uploadPreview.style.display = 'none';
            previewImage.src = '#';
        });
    }
    
    // Bank Name - Show other bank input if "Other" is selected
    const bankSelect = document.getElementById('bank_name');
    const otherBankContainer = document.getElementById('otherBankContainer');
    
    if (bankSelect && otherBankContainer) {
        bankSelect.addEventListener('change', function() {
            if (this.value === 'Other') {
                otherBankContainer.style.display = 'block';
            } else {
                otherBankContainer.style.display = 'none';
                document.getElementById('other_bank_name').value = '';
            }
        });
        
        if (bankSelect.value === 'Other') {
            otherBankContainer.style.display = 'block';
        }
    }
    
    // PFA - Show other PFA input if "Other" is selected
    const pfaSelect = document.getElementById('pension_fund_admin');
    const otherPFAContainer = document.getElementById('otherPFAContainer');
    
    if (pfaSelect && otherPFAContainer) {
        pfaSelect.addEventListener('change', function() {
            if (this.value === 'Other') {
                otherPFAContainer.style.display = 'block';
            } else {
                otherPFAContainer.style.display = 'none';
                document.getElementById('other_pension_fund_admin').value = '';
            }
        });
        
        if (pfaSelect.value === 'Other') {
            otherPFAContainer.style.display = 'block';
        }
    }
    
    // Disability field - Show disability type if "Yes" is selected
    const disabilitySelect = document.getElementById('disability');
    const disabilityTypeContainer = document.getElementById('disabilityTypeContainer');
    
    if (disabilitySelect && disabilityTypeContainer) {
        disabilitySelect.addEventListener('change', function() {
            if (this.value === 'Yes') {
                disabilityTypeContainer.style.display = 'block';
            } else {
                disabilityTypeContainer.style.display = 'none';
                document.getElementById('disability_type').value = '';
            }
        });
        
        if (disabilitySelect.value === 'Yes') {
            disabilityTypeContainer.style.display = 'block';
        }
    }
    
    // Save as Draft button
    const saveDraftBtn = document.getElementById('saveDraft');
    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', function() {
            const form = document.getElementById('employeeForm');
            const draftInput = document.createElement('input');
            draftInput.type = 'hidden';
            draftInput.name = 'save_as_draft';
            draftInput.value = '1';
            form.appendChild(draftInput);
            form.submit();
        });
    }
    
    // Form validation
    const form = document.getElementById('employeeForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Validate qualification entries
            const qualificationEntries = document.querySelectorAll('.qualification-entry');
            qualificationEntries.forEach(entry => {
                const nameInput = entry.querySelector('.qualification-name');
                const yearSelect = entry.querySelector('.qualification-year');
                
                if (nameInput.value.trim() && !yearSelect.value) {
                    isValid = false;
                    yearSelect.classList.add('is-invalid');
                    alert('Please select year for all qualifications');
                } else {
                    yearSelect.classList.remove('is-invalid');
                }
            });
            
            // Validate email if provided
            const emailField = document.getElementById('email');
            if (emailField && emailField.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailField.value)) {
                    isValid = false;
                    emailField.classList.add('is-invalid');
                    alert('Please enter a valid email address');
                }
            }
            
            // Validate account number if provided
            const accountField = document.getElementById('account_number');
            if (accountField && accountField.value) {
                const accountRegex = /^[0-9]{10,20}$/;
                if (!accountRegex.test(accountField.value)) {
                    isValid = false;
                    accountField.classList.add('is-invalid');
                    alert('Account number must be 10-20 digits');
                }
            }
            
            // Validate phone number if provided
            const phoneField = document.getElementById('telephone_number');
            if (phoneField && phoneField.value) {
                const phoneRegex = /^[0-9]{11}$/;
                if (!phoneRegex.test(phoneField.value)) {
                    isValid = false;
                    phoneField.classList.add('is-invalid');
                    alert('Phone number must be 11 digits');
                }
            }
            
            // Validate NIN if provided
            const ninField = document.getElementById('nin');
            if (ninField && ninField.value) {
                const ninRegex = /^[0-9]{11}$/;
                if (!ninRegex.test(ninField.value)) {
                    isValid = false;
                    ninField.classList.add('is-invalid');
                    alert('NIN must be 11 digits');
                }
            }
            
            // Validate emergency contact phone if provided
            const emergencyPhoneField = document.getElementById('emergency_contact_phone');
            if (emergencyPhoneField && emergencyPhoneField.value) {
                const phoneRegex = /^[0-9]{11}$/;
                if (!phoneRegex.test(emergencyPhoneField.value)) {
                    isValid = false;
                    emergencyPhoneField.classList.add('is-invalid');
                    alert('Emergency contact phone must be 11 digits');
                }
            }
            
            // Validate next of kin phone if provided
            const nextOfKinPhoneField = document.getElementById('next_of_kin_phone');
            if (nextOfKinPhoneField && nextOfKinPhoneField.value) {
                const phoneRegex = /^[0-9]{11}$/;
                if (!phoneRegex.test(nextOfKinPhoneField.value)) {
                    isValid = false;
                    nextOfKinPhoneField.classList.add('is-invalid');
                    alert('Next of kin phone must be 11 digits');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields (*) correctly');
            }
        });
    }
    
    // Date validation
    const dobInput = document.getElementById('date_of_birth');
    const firstAppointmentInput = document.getElementById('date_of_first_appointment');
    const confirmationInput = document.getElementById('date_of_confirmation');
    const presentAppointmentInput = document.getElementById('date_of_present_appointment');
    
    if (dobInput) {
        const minDate = new Date();
        minDate.setFullYear(minDate.getFullYear() - 18);
        dobInput.max = minDate.toISOString().split('T')[0];
    }
    
    const minAppointmentDate = '1960-01-01';
    
    if (firstAppointmentInput) {
        firstAppointmentInput.min = minAppointmentDate;
        firstAppointmentInput.max = new Date().toISOString().split('T')[0];
    }
    
    if (confirmationInput) {
        confirmationInput.min = minAppointmentDate;
        confirmationInput.max = new Date().toISOString().split('T')[0];
    }
    
    if (presentAppointmentInput) {
        presentAppointmentInput.min = minAppointmentDate;
        presentAppointmentInput.max = new Date().toISOString().split('T')[0];
    }
    
    // Auto-calculate geopolitical zone based on state
    if (stateSelect) {
        const zoneMapping = {
            'North Central': ['FCT', 'Nasarawa', 'Kogi', 'Kwara', 'Niger', 'Plateau', 'Benue'],
            'North East': ['Adamawa', 'Bauchi', 'Borno', 'Gombe', 'Taraba', 'Yobe'],
            'North West': ['Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Sokoto', 'Zamfara'],
            'South East': ['Abia', 'Anambra', 'Ebonyi', 'Enugu', 'Imo'],
            'South South': ['Akwa Ibom', 'Bayelsa', 'Cross River', 'Delta', 'Edo', 'Rivers'],
            'South West': ['Ekiti', 'Lagos', 'Ogun', 'Ondo', 'Osun', 'Oyo']
        };
        
        stateSelect.addEventListener('change', function() {
            const selectedState = this.value;
            const zoneSelect = document.getElementById('geopolitical_zone');
            
            if (zoneSelect && selectedState) {
                for (const [zone, states] of Object.entries(zoneMapping)) {
                    if (states.includes(selectedState)) {
                        zoneSelect.value = zone;
                        break;
                    }
                }
            }
        });
    }
});
</script>

<style>
/* Main Container */
.create-employee-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Page Header */
.page-header {
    margin-bottom: 30px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header-title h1 {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 8px 0;
}

.header-title .subtitle {
    color: #718096;
    font-size: 16px;
    margin: 0;
}

/* Form Layout */
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
    gap: 30px;
    margin-bottom: 30px;
}

/* Form Cards */
.form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}

.form-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
}

.card-header {
    padding: 20px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-bottom: 1px solid #e2e8f0;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: white;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 30px;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

/* Form Groups */
.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #4a5568;
    font-size: 14px;
}

.form-group.required label {
    position: relative;
}

.form-group.required label:after {
    content: ' *';
    color: #e53e3e;
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
    background: #f8fafc;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
}

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
    background-position: right 10px center;
    background-repeat: no-repeat;
    background-size: 20px;
    padding-right: 40px;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Qualifications */
.qualification-entry {
    margin-bottom: 15px;
}

.qualification-row {
    display: flex;
    gap: 10px;
    align-items: center;
}

.qualification-name {
    flex: 3;
}

.qualification-year {
    flex: 1;
    min-width: 100px;
}

.remove-qualification {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e53e3e !important;
    color: white !important;
    border: 1px solid #c53030 !important;
    border-radius: 6px !important;
    cursor: pointer;
    transition: all 0.2s;
}

.remove-qualification:hover {
    background: #c53030 !important;
    transform: scale(1.05);
}

#add-qualification-btn {
    margin-top: 10px;
}

/* File Upload */
.file-upload {
    margin-bottom: 20px;
}

.upload-area {
    border: 3px dashed #cbd5e0;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.3s;
}

.upload-area:hover {
    border-color: #667eea;
    background: #edf2f7;
}

.upload-area.highlight {
    border-color: #667eea;
    background: #e6fffa;
}

.upload-area i {
    font-size: 48px;
    color: #a0aec0;
    margin-bottom: 15px;
}

.upload-area p {
    font-size: 16px;
    color: #4a5568;
    margin: 0 0 8px 0;
}

.upload-area small {
    font-size: 12px;
    color: #a0aec0;
}

.file-upload input[type="file"] {
    display: none;
}

.upload-preview {
    display: none;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    background: white;
    margin-top: 20px;
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e2e8f0;
}

.preview-header span {
    font-weight: 600;
    color: #4a5568;
}

.upload-preview img {
    display: block;
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    margin: 0 auto;
    border: 1px solid #e2e8f0;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 15px;
    padding: 30px 0;
    border-top: 1px solid #e2e8f0;
    margin-top: 20px;
    justify-content: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}

.btn-lg {
    padding: 14px 28px;
    font-size: 16px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.btn-outline {
    background: transparent;
    color: #4a5568;
    border: 2px solid #e2e8f0;
}

.btn-outline:hover {
    background: #f8fafc;
    border-color: #cbd5e0;
}

.btn-danger {
    background: #e53e3e !important;
    color: white !important;
    border: 1px solid #c53030 !important;
}

.btn-danger:hover {
    background: #c53030 !important;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 14px;
}

/* Alerts */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: #f0fff4;
    border: 2px solid #9ae6b4;
    color: #22543d;
}

.alert-danger {
    background: #fff5f5;
    border: 2px solid #fed7d7;
    color: #c53030;
}

.alert i {
    font-size: 18px;
}

/* Invalid Fields */
.is-invalid {
    border-color: #e53e3e !important;
    background-color: #fff5f5;
}

.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1) !important;
}

/* Responsive Design */
@media (max-width: 1300px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-card {
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .create-employee-container {
        padding: 15px;
    }
    
    .header-content {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .header-title h1 {
        font-size: 24px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .card-header {
        padding: 15px 20px;
    }
    
    .qualification-row {
        flex-direction: column;
        gap: 10px;
    }
    
    .qualification-name,
    .qualification-year {
        width: 100%;
    }
    
    .remove-qualification {
        width: 100%;
        height: 40px;
        margin-top: 5px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    .upload-area {
        padding: 30px 15px;
    }
}

@media (max-width: 480px) {
    .header-title h1 {
        font-size: 22px;
    }
    
    .header-title .subtitle {
        font-size: 14px;
    }
    
    .form-control {
        padding: 8px 12px;
        font-size: 14px;
    }
    
    .btn-lg {
        padding: 12px 20px;
        font-size: 15px;
    }
    
    .upload-area i {
        font-size: 36px;
    }
    
    .upload-area p {
        font-size: 14px;
    }
}

/* Print Styles */
@media print {
    .create-employee-container {
        padding: 0;
    }
    
    .header-actions,
    .form-actions,
    .btn,
    .file-upload,
    #add-qualification-btn {
        display: none !important;
    }
    
    .form-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
        page-break-inside: avoid;
    }
    
    .form-control {
        background: transparent !important;
        border: 1px solid #ddd !important;
    }
}

/* Force red button styling */
.btn-danger,
.remove-qualification {
    background: #e53e3e !important;
    color: white !important;
    border-color: #c53030 !important;
}

.btn-danger:hover,
.remove-qualification:hover {
    background: #c53030 !important;
    color: white !important;
}
</style>