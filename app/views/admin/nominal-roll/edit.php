<?php
/**
 * Edit Employee View
 * Form to edit existing employee in nominal roll
 */
?>
<div class="edit-employee-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1>Edit Employee Record</h1>
                <p class="subtitle">Update employee details below</p>
                <div class="employee-badge">
                    <span class="badge badge-info">Employee No: <?php echo htmlspecialchars($employee['employee_number']); ?></span>
                    <span class="badge badge-secondary"><?php echo htmlspecialchars($employee['rank']); ?></span>
                    <span class="badge badge-light">Last updated: <?php echo !empty($employee['updated_at']) ? date('M d, Y', strtotime($employee['updated_at'])) : 'Never'; ?></span>
                </div>
            </div>
            <div class="header-actions">
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/view/<?php echo $employee['id']; ?>" class="btn btn-info">
                    <i class="fas fa-eye"></i> View
                </a>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($flash_success)) { ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($flash_success); ?>
    </div>
    <?php } ?>
    
    <?php if (!empty($flash_error)) { ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($flash_error); ?>
    </div>
    <?php } ?>
    
    <?php if (!empty($error)) { ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
    </div>
    <?php } ?>

    <!-- Employee Form -->
    <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/update/<?php echo $employee['id']; ?>" enctype="multipart/form-data" class="employee-form" id="employeeForm">
        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
        <input type="hidden" name="_method" value="PUT">
        
        <!-- Row 1: Basic Information -->
        <div class="form-row">
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-id-card"></i> Basic Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <!-- Employee Number -->
                        <div class="form-group required">
                            <label for="employee_number">Employee Number *</label>
                            <input type="text" 
                                   id="employee_number" 
                                   name="employee_number" 
                                   value="<?php echo htmlspecialchars($formData['employee_number'] ?? $employee['employee_number'] ?? ''); ?>"
                                   class="form-control"
                                   required
                                   placeholder="EMP20240001">
                            <small class="form-text">Unique identifier for the employee</small>
                        </div>

                        <!-- Surname -->
                        <div class="form-group required">
                            <label for="surname">Surname *</label>
                            <input type="text" 
                                   id="surname" 
                                   name="surname" 
                                   value="<?php echo htmlspecialchars($formData['surname'] ?? $employee['surname'] ?? ''); ?>"
                                   class="form-control"
                                   required>
                        </div>

                        <!-- First Name -->
                        <div class="form-group required">
                            <label for="first_name">First Name *</label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="<?php echo htmlspecialchars($formData['first_name'] ?? $employee['first_name'] ?? ''); ?>"
                                   class="form-control"
                                   required>
                        </div>

                        <!-- Middle Name -->
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" 
                                   id="middle_name" 
                                   name="middle_name" 
                                   value="<?php echo htmlspecialchars($formData['middle_name'] ?? $employee['middle_name'] ?? ''); ?>"
                                   class="form-control">
                        </div>

                        <!-- Sex -->
                        <div class="form-group required">
                            <label for="sex">Sex *</label>
                            <select id="sex" name="sex" class="form-control" required>
                                <option value="">Select Sex</option>
                                <option value="Male" <?php echo (isset($formData['sex']) ? $formData['sex'] : (isset($employee['sex']) ? $employee['sex'] : '')) === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($formData['sex']) ? $formData['sex'] : (isset($employee['sex']) ? $employee['sex'] : '')) === 'Female' ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>

                        <!-- Date of Birth -->
                        <div class="form-group required">
                            <label for="date_of_birth">Date of Birth *</label>
                            <input type="date" 
                                   id="date_of_birth" 
                                   name="date_of_birth" 
                                   value="<?php echo htmlspecialchars($formData['date_of_birth'] ?? $employee['date_of_birth'] ?? ''); ?>"
                                   class="form-control"
                                   required>
                        </div>

                        <!-- Marital Status -->
                        <div class="form-group required">
                            <label for="marital_status">Marital Status *</label>
                            <select id="marital_status" name="marital_status" class="form-control" required>
                                <option value="">Select Status</option>
                                <option value="Single" <?php echo (isset($formData['marital_status']) ? $formData['marital_status'] : (isset($employee['marital_status']) ? $employee['marital_status'] : '')) === 'Single' ? 'selected' : ''; ?>>Single</option>
                                <option value="Married" <?php echo (isset($formData['marital_status']) ? $formData['marital_status'] : (isset($employee['marital_status']) ? $employee['marital_status'] : '')) === 'Married' ? 'selected' : ''; ?>>Married</option>
                                <option value="Divorced" <?php echo (isset($formData['marital_status']) ? $formData['marital_status'] : (isset($employee['marital_status']) ? $employee['marital_status'] : '')) === 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                                <option value="Widowed" <?php echo (isset($formData['marital_status']) ? $formData['marital_status'] : (isset($employee['marital_status']) ? $employee['marital_status'] : '')) === 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                            </select>
                        </div>

                        <!-- Nationality -->
                        <div class="form-group required">
                            <label for="nationality">Nationality *</label>
                            <select id="nationality" name="nationality" class="form-control" required>
                                <option value="">Select Nationality</option>
                                <option value="Nigerian" <?php echo (isset($formData['nationality']) ? $formData['nationality'] : (isset($employee['nationality']) ? $employee['nationality'] : '')) === 'Nigerian' ? 'selected' : ''; ?>>Nigerian</option>
                                <option value="Ghanaian" <?php echo (isset($formData['nationality']) ? $formData['nationality'] : (isset($employee['nationality']) ? $employee['nationality'] : '')) === 'Ghanaian' ? 'selected' : ''; ?>>Ghanaian</option>
                                <option value="Other" <?php echo (isset($formData['nationality']) ? $formData['nationality'] : (isset($employee['nationality']) ? $employee['nationality'] : '')) === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <!-- Religion -->
                        <div class="form-group">
                            <label for="religion">Religion</label>
                            <select id="religion" name="religion" class="form-control">
                                <option value="">Select Religion</option>
                                <option value="Christianity" <?php echo (isset($formData['religion']) ? $formData['religion'] : (isset($employee['religion']) ? $employee['religion'] : '')) === 'Christianity' ? 'selected' : ''; ?>>Christianity</option>
                                <option value="Islam" <?php echo (isset($formData['religion']) ? $formData['religion'] : (isset($employee['religion']) ? $employee['religion'] : '')) === 'Islam' ? 'selected' : ''; ?>>Islam</option>
                                <option value="Traditional" <?php echo (isset($formData['religion']) ? $formData['religion'] : (isset($employee['religion']) ? $employee['religion'] : '')) === 'Traditional' ? 'selected' : ''; ?>>Traditional Religion</option>
                                <option value="Other" <?php echo (isset($formData['religion']) ? $formData['religion'] : (isset($employee['religion']) ? $employee['religion'] : '')) === 'Other' ? 'selected' : ''; ?>>Other</option>
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
                        <!-- Rank -->
                        <div class="form-group required">
                            <label for="rank">Rank *</label>
                            <input type="text" 
                                   id="rank" 
                                   name="rank" 
                                   value="<?php echo htmlspecialchars($formData['rank'] ?? $employee['rank'] ?? ''); ?>"
                                   class="form-control"
                                   required
                                   placeholder="e.g., Senior Lecturer">
                        </div>

                        <!-- Grade Level -->
                        <div class="form-group required">
                            <label for="grade_level">Grade Level (GL) *</label>
                            <select id="grade_level" name="grade_level" class="form-control" required>
                                <option value="">Select Grade Level</option>
                                <?php for ($i = 1; $i <= 17; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($formData['grade_level']) ? $formData['grade_level'] : (isset($employee['grade_level']) ? $employee['grade_level'] : '')) == $i ? 'selected' : ''; ?>>
                                    GL <?php echo $i; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Step -->
                        <div class="form-group">
                            <label for="step">Step</label>
                            <select id="step" name="step" class="form-control">
                                <option value="">Select Step</option>
                                <?php for ($i = 1; $i <= 15; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($formData['step']) ? $formData['step'] : (isset($employee['step']) ? $employee['step'] : '')) == $i ? 'selected' : ''; ?>>
                                    Step <?php echo $i; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Cadre -->
                        <div class="form-group">
                            <label for="cadre">Cadre</label>
                            <input type="text" 
                                   id="cadre" 
                                   name="cadre" 
                                   value="<?php echo htmlspecialchars($formData['cadre'] ?? $employee['cadre'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., Academic, Non-Academic">
                        </div>

                        <!-- Staff Type -->
                        <div class="form-group">
                            <label for="staff_type">Staff Type</label>
                            <select id="staff_type" name="staff_type" class="form-control">
                                <option value="">Select Staff Type</option>
                                <option value="Academic" <?php echo (isset($formData['staff_type']) ? $formData['staff_type'] : (isset($employee['staff_type']) ? $employee['staff_type'] : '')) === 'Academic' ? 'selected' : ''; ?>>Academic</option>
                                <option value="Non-Academic" <?php echo (isset($formData['staff_type']) ? $formData['staff_type'] : (isset($employee['staff_type']) ? $employee['staff_type'] : '')) === 'Non-Academic' ? 'selected' : ''; ?>>Non-Academic</option>
                                <option value="Administrative" <?php echo (isset($formData['staff_type']) ? $formData['staff_type'] : (isset($employee['staff_type']) ? $employee['staff_type'] : '')) === 'Administrative' ? 'selected' : ''; ?>>Administrative</option>
                                <option value="Technical" <?php echo (isset($formData['staff_type']) ? $formData['staff_type'] : (isset($employee['staff_type']) ? $employee['staff_type'] : '')) === 'Technical' ? 'selected' : ''; ?>>Technical</option>
                            </select>
                        </div>

                        <!-- Employment Type -->
                        <div class="form-group">
                            <label for="employment_type">Employment Type</label>
                            <select id="employment_type" name="employment_type" class="form-control">
                                <option value="">Select Employment Type</option>
                                <option value="Permanent" <?php echo (isset($formData['employment_type']) ? $formData['employment_type'] : (isset($employee['employment_type']) ? $employee['employment_type'] : '')) === 'Permanent' ? 'selected' : ''; ?>>Permanent</option>
                                <option value="Contract" <?php echo (isset($formData['employment_type']) ? $formData['employment_type'] : (isset($employee['employment_type']) ? $employee['employment_type'] : '')) === 'Contract' ? 'selected' : ''; ?>>Contract</option>
                                <option value="Adjunct" <?php echo (isset($formData['employment_type']) ? $formData['employment_type'] : (isset($employee['employment_type']) ? $employee['employment_type'] : '')) === 'Adjunct' ? 'selected' : ''; ?>>Adjunct</option>
                                <option value="Visiting" <?php echo (isset($formData['employment_type']) ? $formData['employment_type'] : (isset($employee['employment_type']) ? $employee['employment_type'] : '')) === 'Visiting' ? 'selected' : ''; ?>>Visiting</option>
                            </select>
                        </div>

                        <!-- Appointment Type -->
                        <div class="form-group">
                            <label for="appointment_type">Appointment Type</label>
                            <select id="appointment_type" name="appointment_type" class="form-control">
                                <option value="">Select Appointment Type</option>
                                <option value="Confirmed" <?php echo (isset($formData['appointment_type']) ? $formData['appointment_type'] : (isset($employee['appointment_type']) ? $employee['appointment_type'] : '')) === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="Acting" <?php echo (isset($formData['appointment_type']) ? $formData['appointment_type'] : (isset($employee['appointment_type']) ? $employee['appointment_type'] : '')) === 'Acting' ? 'selected' : ''; ?>>Acting</option>
                                <option value="Secondment" <?php echo (isset($formData['appointment_type']) ? $formData['appointment_type'] : (isset($employee['appointment_type']) ? $employee['appointment_type'] : '')) === 'Secondment' ? 'selected' : ''; ?>>Secondment</option>
                                <option value="Deputation" <?php echo (isset($formData['appointment_type']) ? $formData['appointment_type'] : (isset($employee['appointment_type']) ? $employee['appointment_type'] : '')) === 'Deputation' ? 'selected' : ''; ?>>Deputation</option>
                            </select>
                        </div>

                        <!-- Department -->
                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" 
                                   id="department" 
                                   name="department" 
                                   value="<?php echo htmlspecialchars($formData['department'] ?? $employee['department'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., Nursing Sciences">
                        </div>

                        <!-- Date of First Appointment -->
                        <div class="form-group required">
                            <label for="date_of_first_appointment">Date of 1st Appointment *</label>
                            <input type="date" 
                                   id="date_of_first_appointment" 
                                   name="date_of_first_appointment" 
                                   value="<?php echo htmlspecialchars($formData['date_of_first_appointment'] ?? $employee['date_of_first_appointment'] ?? ''); ?>"
                                   class="form-control"
                                   required>
                        </div>

                        <!-- Date of Confirmation -->
                        <div class="form-group">
                            <label for="date_of_confirmation">Date of Confirmation</label>
                            <input type="date" 
                                   id="date_of_confirmation" 
                                   name="date_of_confirmation" 
                                   value="<?php echo htmlspecialchars($formData['date_of_confirmation'] ?? $employee['date_of_confirmation'] ?? ''); ?>"
                                   class="form-control">
                        </div>

                        <!-- Rank on First Appointment -->
                        <div class="form-group">
                            <label for="rank_on_first_appointment">Rank on 1st Appointment</label>
                            <input type="text" 
                                   id="rank_on_first_appointment" 
                                   name="rank_on_first_appointment" 
                                   value="<?php echo htmlspecialchars($formData['rank_on_first_appointment'] ?? $employee['rank_on_first_appointment'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Rank at first appointment">
                        </div>

                        <!-- Date of Present Appointment -->
                        <div class="form-group">
                            <label for="date_of_present_appointment">Date of Present Appointment</label>
                            <input type="date" 
                                   id="date_of_present_appointment" 
                                   name="date_of_present_appointment" 
                                   value="<?php echo htmlspecialchars($formData['date_of_present_appointment'] ?? $employee['date_of_present_appointment'] ?? ''); ?>"
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
                        <!-- Highest Qualification -->
                        <div class="form-group required">
                            <label for="highest_qualification">Highest Qualification *</label>
                            <select id="highest_qualification" name="highest_qualification" class="form-control" required>
                                <option value="">Select Highest Qualification</option>
                                <option value="PhD" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'PhD' ? 'selected' : ''; ?>>PhD</option>
                                <option value="MSc" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'MSc' ? 'selected' : ''; ?>>MSc/M.A</option>
                                <option value="BSc" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'BSc' ? 'selected' : ''; ?>>BSc/B.A/B.Ed</option>
                                <option value="HND" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'HND' ? 'selected' : ''; ?>>HND</option>
                                <option value="OND" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'OND' ? 'selected' : ''; ?>>OND</option>
                                <option value="NCE" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'NCE' ? 'selected' : ''; ?>>NCE</option>
                                <option value="SSCE" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'SSCE' ? 'selected' : ''; ?>>SSCE/WASC</option>
                                <option value="FSLC" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'FSLC' ? 'selected' : ''; ?>>FSLC</option>
                                <option value="Others" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'Others' ? 'selected' : ''; ?>>Others</option>
                            </select>
                        </div>

                        <!-- Year of Highest Qualification -->
                        <div class="form-group required">
                            <label for="year_of_highest_qualification">Year of Highest Qualification *</label>
                            <select id="year_of_highest_qualification" name="year_of_highest_qualification" class="form-control" required>
                                <option value="">Select Year</option>
                                <?php for ($year = date('Y'); $year >= 1960; $year--) { ?>
                                <option value="<?php echo $year; ?>" <?php echo (isset($formData['year_of_highest_qualification']) ? $formData['year_of_highest_qualification'] : (isset($employee['year_of_highest_qualification']) ? $employee['year_of_highest_qualification'] : '')) == $year ? 'selected' : ''; ?>>
                                    <?php echo $year; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Institution Attended -->
                        <div class="form-group">
                            <label for="institution_attended">Institution Attended</label>
                            <input type="text" 
                                   id="institution_attended" 
                                   name="institution_attended" 
                                   value="<?php echo htmlspecialchars($formData['institution_attended'] ?? $employee['institution_attended'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., University of Nigeria, Nsukka">
                        </div>

                        <!-- Course of Study -->
                        <div class="form-group">
                            <label for="course_of_study">Course of Study</label>
                            <input type="text" 
                                   id="course_of_study" 
                                   name="course_of_study" 
                                   value="<?php echo htmlspecialchars($formData['course_of_study'] ?? $employee['course_of_study'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., Nursing Science">
                        </div>

                        <!-- Class of Degree -->
                        <div class="form-group">
                            <label for="class_of_degree">Class of Degree</label>
                            <select id="class_of_degree" name="class_of_degree" class="form-control">
                                <option value="">Select Class</option>
                                <option value="First Class" <?php echo (isset($formData['class_of_degree']) ? $formData['class_of_degree'] : (isset($employee['class_of_degree']) ? $employee['class_of_degree'] : '')) === 'First Class' ? 'selected' : ''; ?>>First Class</option>
                                <option value="Second Class Upper" <?php echo (isset($formData['class_of_degree']) ? $formData['class_of_degree'] : (isset($employee['class_of_degree']) ? $employee['class_of_degree'] : '')) === 'Second Class Upper' ? 'selected' : ''; ?>>Second Class Upper</option>
                                <option value="Second Class Lower" <?php echo (isset($formData['class_of_degree']) ? $formData['class_of_degree'] : (isset($employee['class_of_degree']) ? $employee['class_of_degree'] : '')) === 'Second Class Lower' ? 'selected' : ''; ?>>Second Class Lower</option>
                                <option value="Third Class" <?php echo (isset($formData['class_of_degree']) ? $formData['class_of_degree'] : (isset($employee['class_of_degree']) ? $employee['class_of_degree'] : '')) === 'Third Class' ? 'selected' : ''; ?>>Third Class</option>
                                <option value="Pass" <?php echo (isset($formData['class_of_degree']) ? $formData['class_of_degree'] : (isset($employee['class_of_degree']) ? $employee['class_of_degree'] : '')) === 'Pass' ? 'selected' : ''; ?>>Pass</option>
                            </select>
                        </div>

                        <!-- Professional Certifications -->
                        <div class="form-group">
                            <label for="professional_certifications">Professional Certifications</label>
                            <textarea id="professional_certifications" 
                                      name="professional_certifications" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="List professional certifications separated by commas"><?php echo htmlspecialchars($formData['professional_certifications'] ?? $employee['professional_certifications'] ?? ''); ?></textarea>
                        </div>

                        <!-- Additional Qualifications - FIXED: Added full-width-group class -->
                        <div class="form-group full-width-group">
                            <label>Additional Qualifications</label>
                            <div id="qualifications-container">
                                <?php
                                // Parse additional qualifications if they exist
                                $additional_qualifications = [];
                                
                                if (!empty($employee['additional_qualifications'])) {
                                    if (is_string($employee['additional_qualifications'])) {
                                        $additional_qualifications = json_decode($employee['additional_qualifications'], true);
                                        if (json_last_error() !== JSON_ERROR_NONE) {
                                            // If it's not valid JSON, treat it as a simple string
                                            $additional_qualifications = [['qualification' => $employee['additional_qualifications'], 'year' => '']];
                                        }
                                    } elseif (is_array($employee['additional_qualifications'])) {
                                        $additional_qualifications = $employee['additional_qualifications'];
                                    }
                                }
                                
                                // Display existing qualifications
                                if (!empty($additional_qualifications) && is_array($additional_qualifications)) {
                                    foreach ($additional_qualifications as $index => $qual) {
                                        $qualName = $qual['qualification'] ?? $qual['name'] ?? $qual ?? '';
                                        $qualYear = $qual['year'] ?? '';
                                ?>
                                <div class="qualification-entry">
                                    <div class="qualification-row">
                                        <input type="text" 
                                               name="additional_qualifications[<?php echo $index; ?>][qualification]" 
                                               class="form-control qualification-name"
                                               value="<?php echo htmlspecialchars($qualName); ?>"
                                               placeholder="Qualification (e.g., BSc Nursing)">
                                        <select name="additional_qualifications[<?php echo $index; ?>][year]" class="form-control qualification-year">
                                            <option value="">Year</option>
                                            <?php for ($year = date('Y'); $year >= 1960; $year--) { ?>
                                            <option value="<?php echo $year; ?>" <?php echo $qualYear == $year ? 'selected' : ''; ?>>
                                                <?php echo $year; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <button type="button" class="btn btn-danger remove-qualification" title="Remove">
                                            <i class="fas fa-trash"></i>
                                            <span class="btn-text">Remove</span>
                                        </button>
                                    </div>
                                </div>
                                <?php 
                                    }
                                } else {
                                ?>
                                <!-- Default empty entry when no qualifications exist -->
                                <div class="qualification-entry">
                                    <div class="qualification-row">
                                        <input type="text" 
                                               name="additional_qualifications[0][qualification]" 
                                               class="form-control qualification-name"
                                               placeholder="Qualification (e.g., BSc Nursing)">
                                        <select name="additional_qualifications[0][year]" class="form-control qualification-year">
                                            <option value="">Year</option>
                                            <?php for ($year = date('Y'); $year >= 1960; $year--) { ?>
                                            <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                            <?php } ?>
                                        </select>
                                        <button type="button" class="btn btn-danger remove-qualification" title="Remove">
                                            <i class="fas fa-trash"></i>
                                            <span class="btn-text">Remove</span>
                                        </button>
                                    </div>
                                </div>
                                <?php } ?>
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
                        <!-- State of Origin -->
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
                                foreach ($nigerian_states as $state) { ?>
                                <option value="<?php echo htmlspecialchars($state); ?>"
                                    <?php echo (isset($formData['state']) ? $formData['state'] : (isset($employee['state']) ? $employee['state'] : '')) === $state ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($state); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Local Government Area -->
                        <div class="form-group required">
                            <label for="local_govt_area">Local Government Area *</label>
                            <select id="local_govt_area" name="local_govt_area" class="form-control" required>
                                <option value="">Select LGA</option>
                                <!-- Will be populated by JavaScript based on state -->
                            </select>
                        </div>

                        <!-- Geopolitical Zone -->
                        <div class="form-group">
                            <label for="geopolitical_zone">Geopolitical Zone</label>
                            <select id="geopolitical_zone" name="geopolitical_zone" class="form-control">
                                <option value="">Select Zone</option>
                                <option value="North Central" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'North Central' ? 'selected' : ''; ?>>North Central</option>
                                <option value="North East" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'North East' ? 'selected' : ''; ?>>North East</option>
                                <option value="North West" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'North West' ? 'selected' : ''; ?>>North West</option>
                                <option value="South East" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'South East' ? 'selected' : ''; ?>>South East</option>
                                <option value="South South" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'South South' ? 'selected' : ''; ?>>South South</option>
                                <option value="South West" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'South West' ? 'selected' : ''; ?>>South West</option>
                            </select>
                        </div>

                        <!-- State of Residence -->
                        <div class="form-group">
                            <label for="state_of_residence">State of Residence</label>
                            <select id="state_of_residence" name="state_of_residence" class="form-control">
                                <option value="">Same as State of Origin</option>
                                <?php foreach ($nigerian_states as $state) { ?>
                                <option value="<?php echo htmlspecialchars($state); ?>"
                                    <?php echo (isset($formData['state_of_residence']) ? $formData['state_of_residence'] : (isset($employee['state_of_residence']) ? $employee['state_of_residence'] : '')) === $state ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($state); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Residential Address -->
                        <div class="form-group">
                            <label for="residential_address">Residential Address</label>
                            <textarea id="residential_address" 
                                      name="residential_address" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Full residential address"><?php echo htmlspecialchars($formData['residential_address'] ?? $employee['residential_address'] ?? ''); ?></textarea>
                        </div>

                        <!-- Contact Address -->
                        <div class="form-group">
                            <label for="contact_address">Contact Address</label>
                            <textarea id="contact_address" 
                                      name="contact_address" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Contact address if different from residential"><?php echo htmlspecialchars($formData['contact_address'] ?? $employee['contact_address'] ?? ''); ?></textarea>
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
                        <!-- PF Number -->
                        <div class="form-group">
                            <label for="pf_number">Personal File (PF) Number</label>
                            <input type="text" 
                                   id="pf_number" 
                                   name="pf_number" 
                                   value="<?php echo htmlspecialchars($formData['pf_number'] ?? $employee['pf_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., FCTCNS/PF/001">
                        </div>

                        <!-- NHF Number -->
                        <div class="form-group">
                            <label for="nhf_number">NHF Number</label>
                            <input type="text" 
                                   id="nhf_number" 
                                   name="nhf_number" 
                                   value="<?php echo htmlspecialchars($formData['nhf_number'] ?? $employee['nhf_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., NHF/12345/001">
                        </div>

                        <!-- NIN -->
                        <div class="form-group">
                            <label for="nin">NIN (National Identity Number)</label>
                            <input type="text" 
                                   id="nin" 
                                   name="nin" 
                                   value="<?php echo htmlspecialchars($formData['nin'] ?? $employee['nin'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="11-digit NIN">
                        </div>

                        <!-- Telephone Number -->
                        <div class="form-group">
                            <label for="telephone_number">Telephone Number</label>
                            <input type="tel" 
                                   id="telephone_number" 
                                   name="telephone_number" 
                                   value="<?php echo htmlspecialchars($formData['telephone_number'] ?? $employee['telephone_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., 08012345678"
                                   pattern="[0-9]{11}"
                                   title="11 digit Nigerian phone number">
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($formData['email'] ?? $employee['email'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., john.doe@example.com">
                        </div>

                        <!-- Blood Group -->
                        <div class="form-group">
                            <label for="blood_group">Blood Group</label>
                            <select id="blood_group" name="blood_group" class="form-control">
                                <option value="">Select Blood Group</option>
                                <option value="O+" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'O+' ? 'selected' : ''; ?>>O Positive (O+)</option>
                                <option value="O-" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'O-' ? 'selected' : ''; ?>>O Negative (O-)</option>
                                <option value="A+" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'A+' ? 'selected' : ''; ?>>A Positive (A+)</option>
                                <option value="A-" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'A-' ? 'selected' : ''; ?>>A Negative (A-)</option>
                                <option value="B+" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'B+' ? 'selected' : ''; ?>>B Positive (B+)</option>
                                <option value="B-" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'B-' ? 'selected' : ''; ?>>B Negative (B-)</option>
                                <option value="AB+" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'AB+' ? 'selected' : ''; ?>>AB Positive (AB+)</option>
                                <option value="AB-" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'AB-' ? 'selected' : ''; ?>>AB Negative (AB-)</option>
                            </select>
                        </div>

                        <!-- Genotype -->
                        <div class="form-group">
                            <label for="genotype">Genotype</label>
                            <select id="genotype" name="genotype" class="form-control">
                                <option value="">Select Genotype</option>
                                <option value="AA" <?php echo (isset($formData['genotype']) ? $formData['genotype'] : (isset($employee['genotype']) ? $employee['genotype'] : '')) === 'AA' ? 'selected' : ''; ?>>AA</option>
                                <option value="AS" <?php echo (isset($formData['genotype']) ? $formData['genotype'] : (isset($employee['genotype']) ? $employee['genotype'] : '')) === 'AS' ? 'selected' : ''; ?>>AS</option>
                                <option value="SS" <?php echo (isset($formData['genotype']) ? $formData['genotype'] : (isset($employee['genotype']) ? $employee['genotype'] : '')) === 'SS' ? 'selected' : ''; ?>>SS</option>
                                <option value="AC" <?php echo (isset($formData['genotype']) ? $formData['genotype'] : (isset($employee['genotype']) ? $employee['genotype'] : '')) === 'AC' ? 'selected' : ''; ?>>AC</option>
                            </select>
                        </div>

                        <!-- Disability -->
                        <div class="form-group">
                            <label for="disability">Disability</label>
                            <select id="disability" name="disability" class="form-control">
                                <option value="No" <?php echo (isset($formData['disability']) ? $formData['disability'] : (isset($employee['disability']) ? $employee['disability'] : 'No')) === 'Yes' ? '' : 'selected'; ?>>No</option>
                                <option value="Yes" <?php echo (isset($formData['disability']) ? $formData['disability'] : (isset($employee['disability']) ? $employee['disability'] : 'No')) === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                            </select>
                        </div>

                        <!-- Disability Type -->
                        <div class="form-group" id="disabilityTypeContainer" style="<?php echo (isset($formData['disability']) ? $formData['disability'] : (isset($employee['disability']) ? $employee['disability'] : 'No')) === 'Yes' ? 'display: block;' : 'display: none;'; ?>">
                            <label for="disability_type">Type of Disability</label>
                            <input type="text" 
                                   id="disability_type" 
                                   name="disability_type" 
                                   value="<?php echo htmlspecialchars($formData['disability_type'] ?? $employee['disability_type'] ?? ''); ?>"
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
                        <!-- Bank Name -->
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
                                foreach ($nigerian_banks as $bank) { ?>
                                <option value="<?php echo htmlspecialchars($bank); ?>"
                                    <?php echo (isset($formData['bank_name']) ? $formData['bank_name'] : (isset($employee['bank_name']) ? $employee['bank_name'] : '')) === $bank ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($bank); ?>
                                </option>
                                <?php } ?>
                                <option value="Other" <?php echo (!empty(isset($formData['bank_name']) ? $formData['bank_name'] : (isset($employee['bank_name']) ? $employee['bank_name'] : '')) && !in_array(isset($formData['bank_name']) ? $formData['bank_name'] : (isset($employee['bank_name']) ? $employee['bank_name'] : ''), $nigerian_banks)) ? 'selected' : ''; ?>>Other Bank</option>
                            </select>
                        </div>

                        <!-- Other Bank Name -->
                        <div class="form-group" id="otherBankContainer" style="<?php echo (!empty(isset($formData['bank_name']) ? $formData['bank_name'] : (isset($employee['bank_name']) ? $employee['bank_name'] : '')) && !in_array(isset($formData['bank_name']) ? $formData['bank_name'] : (isset($employee['bank_name']) ? $employee['bank_name'] : ''), $nigerian_banks)) ? 'display: block;' : 'display: none;'; ?>">
                            <label for="other_bank_name">Specify Bank Name</label>
                            <input type="text" 
                                   id="other_bank_name" 
                                   name="other_bank_name" 
                                   value="<?php echo htmlspecialchars($formData['other_bank_name'] ?? (!in_array(isset($employee['bank_name']) ? $employee['bank_name'] : '', $nigerian_banks) ? (isset($employee['bank_name']) ? $employee['bank_name'] : '') : '') ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Enter bank name">
                        </div>

                        <!-- Bank Branch -->
                        <div class="form-group">
                            <label for="bank_branch">Bank Branch</label>
                            <input type="text" 
                                   id="bank_branch" 
                                   name="bank_branch" 
                                   value="<?php echo htmlspecialchars($formData['bank_branch'] ?? $employee['bank_branch'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., Gwagwalada Branch">
                        </div>

                        <!-- Account Number -->
                        <div class="form-group">
                            <label for="account_number">Account Number</label>
                            <input type="text" 
                                   id="account_number" 
                                   name="account_number" 
                                   value="<?php echo htmlspecialchars($formData['account_number'] ?? $employee['account_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="10-20 digits"
                                   pattern="[0-9]{10,20}"
                                   title="10-20 digit account number">
                        </div>

                        <!-- Account Name -->
                        <div class="form-group">
                            <label for="account_name">Account Name</label>
                            <input type="text" 
                                   id="account_name" 
                                   name="account_name" 
                                   value="<?php echo htmlspecialchars($formData['account_name'] ?? $employee['account_name'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Account holder's name">
                        </div>

                        <!-- Pension Fund Administrator -->
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
                                foreach ($pension_administrators as $pfa) { ?>
                                <option value="<?php echo htmlspecialchars($pfa); ?>"
                                    <?php echo (isset($formData['pension_fund_admin']) ? $formData['pension_fund_admin'] : (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : '')) === $pfa ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($pfa); ?>
                                </option>
                                <?php } ?>
                                <option value="Other" <?php echo (!empty(isset($formData['pension_fund_admin']) ? $formData['pension_fund_admin'] : (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : '')) && !in_array(isset($formData['pension_fund_admin']) ? $formData['pension_fund_admin'] : (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : ''), $pension_administrators)) ? 'selected' : ''; ?>>Other PFA</option>
                            </select>
                        </div>

                        <!-- Other PFA -->
                        <div class="form-group" id="otherPFAContainer" style="<?php echo (!empty(isset($formData['pension_fund_admin']) ? $formData['pension_fund_admin'] : (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : '')) && !in_array(isset($formData['pension_fund_admin']) ? $formData['pension_fund_admin'] : (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : ''), $pension_administrators)) ? 'display: block;' : 'display: none;'; ?>">
                            <label for="other_pension_fund_admin">Specify PFA</label>
                            <input type="text" 
                                   id="other_pension_fund_admin" 
                                   name="other_pension_fund_admin" 
                                   value="<?php echo htmlspecialchars($formData['other_pension_fund_admin'] ?? (!in_array(isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : '', $pension_administrators) ? (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : '') : '') ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Enter PFA name">
                        </div>

                        <!-- Pension Number -->
                        <div class="form-group">
                            <label for="pension_number">Pension Number</label>
                            <input type="text" 
                                   id="pension_number" 
                                   name="pension_number" 
                                   value="<?php echo htmlspecialchars($formData['pension_number'] ?? $employee['pension_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Pension Registration Number">
                        </div>

                        <!-- TIN Number -->
                        <div class="form-group">
                            <label for="tin_number">Tax Identification No (TIN)</label>
                            <input type="text" 
                                   id="tin_number" 
                                   name="tin_number" 
                                   value="<?php echo htmlspecialchars($formData['tin_number'] ?? $employee['tin_number'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="10-12 digit TIN">
                        </div>

                        <!-- Salary Structure -->
                        <div class="form-group">
                            <label for="salary_structure">Salary Structure</label>
                            <select id="salary_structure" name="salary_structure" class="form-control">
                                <option value="">Select Salary Structure</option>
                                <option value="CONMESS" <?php echo (isset($formData['salary_structure']) ? $formData['salary_structure'] : (isset($employee['salary_structure']) ? $employee['salary_structure'] : '')) === 'CONMESS' ? 'selected' : ''; ?>>CONMESS</option>
                                <option value="CONTISS" <?php echo (isset($formData['salary_structure']) ? $formData['salary_structure'] : (isset($employee['salary_structure']) ? $employee['salary_structure'] : '')) === 'CONTISS' ? 'selected' : ''; ?>>CONTISS</option>
                                <option value="CONHESS" <?php echo (isset($formData['salary_structure']) ? $formData['salary_structure'] : (isset($employee['salary_structure']) ? $employee['salary_structure'] : '')) === 'CONHESS' ? 'selected' : ''; ?>>CONHESS</option>
                                <option value="CONPSS" <?php echo (isset($formData['salary_structure']) ? $formData['salary_structure'] : (isset($employee['salary_structure']) ? $employee['salary_structure'] : '')) === 'CONPSS' ? 'selected' : ''; ?>>CONPSS</option>
                                <option value="Others" <?php echo (isset($formData['salary_structure']) ? $formData['salary_structure'] : (isset($employee['salary_structure']) ? $employee['salary_structure'] : '')) === 'Others' ? 'selected' : ''; ?>>Others</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 4b: Emergency Contacts & Next of Kin -->
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-user-friends"></i> Emergency Contacts & Next of Kin</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <!-- Emergency Contact Name -->
                        <div class="form-group">
                            <label for="emergency_contact_name">Emergency Contact Name</label>
                            <input type="text" 
                                   id="emergency_contact_name" 
                                   name="emergency_contact_name" 
                                   value="<?php echo htmlspecialchars($formData['emergency_contact_name'] ?? $employee['emergency_contact_name'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Full name of emergency contact">
                        </div>

                        <!-- Emergency Contact Phone -->
                        <div class="form-group">
                            <label for="emergency_contact_phone">Emergency Contact Phone</label>
                            <input type="tel" 
                                   id="emergency_contact_phone" 
                                   name="emergency_contact_phone" 
                                   value="<?php echo htmlspecialchars($formData['emergency_contact_phone'] ?? $employee['emergency_contact_phone'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., 08012345678"
                                   pattern="[0-9]{11}"
                                   title="11 digit Nigerian phone number">
                        </div>

                        <!-- Emergency Contact Relationship -->
                        <div class="form-group">
                            <label for="emergency_contact_relationship">Emergency Contact Relationship</label>
                            <select id="emergency_contact_relationship" name="emergency_contact_relationship" class="form-control">
                                <option value="">Select Relationship</option>
                                <option value="Spouse" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Spouse' ? 'selected' : ''; ?>>Spouse</option>
                                <option value="Parent" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Parent' ? 'selected' : ''; ?>>Parent</option>
                                <option value="Sibling" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Sibling' ? 'selected' : ''; ?>>Sibling</option>
                                <option value="Child" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Child' ? 'selected' : ''; ?>>Child</option>
                                <option value="Relative" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Relative' ? 'selected' : ''; ?>>Relative</option>
                                <option value="Friend" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Friend' ? 'selected' : ''; ?>>Friend</option>
                                <option value="Other" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <!-- Next of Kin Name -->
                        <div class="form-group">
                            <label for="next_of_kin_name">Next of Kin Name</label>
                            <input type="text" 
                                   id="next_of_kin_name" 
                                   name="next_of_kin_name" 
                                   value="<?php echo htmlspecialchars($formData['next_of_kin_name'] ?? $employee['next_of_kin_name'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="Full name of next of kin">
                        </div>

                        <!-- Next of Kin Phone -->
                        <div class="form-group">
                            <label for="next_of_kin_phone">Next of Kin Phone</label>
                            <input type="tel" 
                                   id="next_of_kin_phone" 
                                   name="next_of_kin_phone" 
                                   value="<?php echo htmlspecialchars($formData['next_of_kin_phone'] ?? $employee['next_of_kin_phone'] ?? ''); ?>"
                                   class="form-control"
                                   placeholder="e.g., 08012345678"
                                   pattern="[0-9]{11}"
                                   title="11 digit Nigerian phone number">
                        </div>

                        <!-- Next of Kin Relationship -->
                        <div class="form-group">
                            <label for="next_of_kin_relationship">Next of Kin Relationship</label>
                            <select id="next_of_kin_relationship" name="next_of_kin_relationship" class="form-control">
                                <option value="">Select Relationship</option>
                                <option value="Spouse" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Spouse' ? 'selected' : ''; ?>>Spouse</option>
                                <option value="Parent" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Parent' ? 'selected' : ''; ?>>Parent</option>
                                <option value="Sibling" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Sibling' ? 'selected' : ''; ?>>Sibling</option>
                                <option value="Child" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Child' ? 'selected' : ''; ?>>Child</option>
                                <option value="Relative" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Relative' ? 'selected' : ''; ?>>Relative</option>
                                <option value="Other" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <!-- Next of Kin Address -->
                        <div class="form-group">
                            <label for="next_of_kin_address">Next of Kin Address</label>
                            <textarea id="next_of_kin_address" 
                                      name="next_of_kin_address" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Full address of next of kin"><?php echo htmlspecialchars($formData['next_of_kin_address'] ?? $employee['next_of_kin_address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 5: Passport Photo - FIXED -->
        <div class="form-row">
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-camera"></i> Passport Photo</h3>
                </div>
                <div class="card-body">
                    <div class="photo-section">
                        <!-- Current Photo -->
                        <div class="current-photo">
                            <label>Current Photo</label>
                            <?php if (!empty($employee['passport_photo'])) { ?>
                                <div class="photo-preview">
                                    <img src="<?php echo $baseUrl; ?>/admin/nominal-roll/passport-photo/<?php echo $employee['id']; ?>" 
                                         alt="Passport Photo" 
                                         class="current-photo-img"
                                         style="max-width: 200px; border: 1px solid #ddd; border-radius: 4px;"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\'><rect width=\'100%\' height=\'100%\' fill=\'%23f0f0f0\'/><text x=\'50%\' y=\'50%\' font-family=\'Arial\' font-size=\'14\' fill=\'%23666\' text-anchor=\'middle\' dominant-baseline=\'middle\'>Photo Missing</text></svg>';">
                                    <div class="photo-info">
                                        <small>Current passport photo</small>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="no-photo">
                                    <i class="fas fa-user-circle"></i>
                                    <p>No passport photo uploaded</p>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Upload New Photo -->
                        <div class="upload-new-photo">
                            <div class="form-group">
                                <label for="passport_photo">Upload New Photo</label>
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
                                        <span>New Photo Preview</span>
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
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Update Employee Record
            </button>
            <button type="button" id="saveDraft" class="btn btn-secondary btn-lg">
                <i class="fas fa-save"></i> Save as Draft
            </button>
            <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/view/<?php echo $employee['id']; ?>" class="btn btn-outline btn-lg">
                <i class="fas fa-times"></i> Cancel
            </a>
            
            <?php if ($isSuperAdmin) { ?>
            <button type="button" 
                    class="btn btn-danger btn-lg" 
                    onclick="openDeleteModal()">
                <i class="fas fa-trash"></i> Delete Employee
            </button>
            <?php } ?>
        </div>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<?php if ($isSuperAdmin) { ?>
<div class="modal" id="deleteModal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Confirm Delete</h5>
            <button type="button" class="close" onclick="closeDeleteModal()">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Warning!</strong> This action cannot be undone.
            </div>
            <p>Are you sure you want to delete the employee record for:</p>
            <div class="employee-delete-info">
                <h4><?php echo htmlspecialchars($employee['surname'] . ', ' . $employee['first_name']); ?></h4>
                <p>Employee No: <strong><?php echo htmlspecialchars($employee['employee_number']); ?></strong></p>
                <p>Rank: <strong><?php echo htmlspecialchars($employee['rank']); ?></strong></p>
            </div>
            <p class="text-danger">All associated data will be permanently deleted from the system.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/delete/<?php echo $employee['id']; ?>" style="display: inline;">
                <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                <button type="submit" class="btn btn-danger">Delete Permanently</button>
            </form>
        </div>
    </div>
</div>
<?php } ?>

<!-- Template for Qualification Entry -->
<template id="qualification-template">
    <div class="qualification-entry">
        <div class="qualification-row">
            <input type="text" 
                   name="additional_qualifications[__INDEX__][qualification]" 
                   class="form-control qualification-name"
                   placeholder="Qualification (e.g., BSc Nursing)">
            <select name="additional_qualifications[__INDEX__][year]" class="form-control qualification-year">
                <option value="">Year</option>
                <?php for ($year = date('Y'); $year >= 1960; $year--) { ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php } ?>
            </select>
            <button type="button" class="btn btn-danger remove-qualification" title="Remove">
                <i class="fas fa-trash"></i>
                <span class="btn-text">Remove</span>
            </button>
        </div>
    </div>
</template>

<!-- JavaScript for Edit Form -->
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
    
    // Get current values
    const currentState = '<?php echo isset($employee["state"]) ? $employee["state"] : ""; ?>';
    const currentLGA = '<?php echo isset($employee["local_govt_area"]) ? $employee["local_govt_area"] : ""; ?>';

    if (stateSelect && lgaSelect) {
        // Function to populate LGAs
        function populateLGAs(selectedState, selectedLGA = '') {
            if (selectedState && nigerianLGAs[selectedState]) {
                lgaSelect.disabled = false;
                lgaSelect.innerHTML = '<option value="">Select LGA</option>';
                
                nigerianLGAs[selectedState].forEach(lga => {
                    const option = document.createElement('option');
                    option.value = lga;
                    option.textContent = lga;
                    if (lga === selectedLGA) {
                        option.selected = true;
                    }
                    lgaSelect.appendChild(option);
                });
            } else {
                lgaSelect.disabled = true;
                lgaSelect.innerHTML = '<option value="">Select State first</option>';
            }
        }
        
        // Set current state value
        if (currentState) {
            stateSelect.value = currentState;
        }
        
        // Populate LGAs on page load
        populateLGAs(currentState, currentLGA);
        
        // Handle state change
        stateSelect.addEventListener('change', function() {
            populateLGAs(this.value, '');
        });
    }

    // Multiple Qualifications with Year - FIXED
    const qualificationsContainer = document.getElementById('qualifications-container');
    const addQualificationBtn = document.getElementById('add-qualification-btn');
    const qualificationTemplate = document.getElementById('qualification-template');

    // Function to get the next index for new qualification
    function getNextQualificationIndex() {
        const entries = document.querySelectorAll('.qualification-entry');
        return entries.length;
    }

    // Function to add a new qualification field
    function addQualificationField(name = '', year = '') {
        const templateContent = qualificationTemplate.content.cloneNode(true);
        const entry = templateContent.querySelector('.qualification-entry');
        const nextIndex = getNextQualificationIndex();
        
        // Update the name attributes with the correct index
        const nameInput = entry.querySelector('.qualification-name');
        const yearSelect = entry.querySelector('.qualification-year');
        
        nameInput.name = nameInput.name.replace('__INDEX__', nextIndex);
        yearSelect.name = yearSelect.name.replace('__INDEX__', nextIndex);
        
        // Set values if provided
        if (name) nameInput.value = name;
        if (year) yearSelect.value = year;
        
        // Add remove functionality
        const removeBtn = entry.querySelector('.remove-qualification');
        removeBtn.addEventListener('click', function() {
            entry.remove();
        });
        
        qualificationsContainer.appendChild(entry);
    }
    
    // Add more qualifications button
    addQualificationBtn.addEventListener('click', function() {
        addQualificationField();
    });

    // Initialize remove functionality for existing qualification entries
    document.querySelectorAll('.remove-qualification').forEach(button => {
        button.addEventListener('click', function() {
            const entry = this.closest('.qualification-entry');
            entry.remove();
        });
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
    
    // Bank Name - Show other bank input
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
    }
    
    // PFA - Show other PFA input
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
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields (*) correctly');
            }
        });
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
    
    // Show/hide button text on mobile
    function updateButtonTextForMobile() {
        const isMobile = window.innerWidth <= 768;
        const btnTexts = document.querySelectorAll('.btn-text');
        
        btnTexts.forEach(btnText => {
            if (isMobile) {
                btnText.style.display = 'inline !important';
            } else {
                btnText.style.display = 'inline';
            }
        });
    }
    
    // Initial update
    updateButtonTextForMobile();
    
    // Update on resize
    window.addEventListener('resize', updateButtonTextForMobile);
});

// Delete modal functions
function openDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (modal && event.target === modal) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

<style>
.edit-employee-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Page Header */
.page-header {
    margin-bottom: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    padding: 25px;
    color: white;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 20px;
}

.header-title h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: white;
}

.subtitle {
    font-size: 16px;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 15px;
}

.employee-badge {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.badge {
    display: inline-block;
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 20px;
    border: none;
}

.badge-info {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: white;
}

.badge-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.badge-light {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
}

.header-actions {
    display: flex;
    gap: 10px;
}

.header-actions .btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.header-actions .btn-info {
    background: white;
    color: #667eea;
    border: 2px solid white;
}

.header-actions .btn-info:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
}

.header-actions .btn-outline {
    background: transparent;
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.header-actions .btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: white;
    transform: translateY(-2px);
}

/* Photo Section */
.photo-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.current-photo, .upload-new-photo {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e2e8f0;
}

.current-photo label, .upload-new-photo label {
    display: block;
    margin-bottom: 15px;
    font-weight: 600;
    color: #2d3748;
    font-size: 16px;
}

.photo-preview {
    text-align: center;
}

.current-photo-img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    border: 3px solid #e2e8f0;
    margin-bottom: 10px;
    object-fit: cover;
    width: 100%;
    height: auto;
}

.photo-info {
    font-size: 12px;
    color: #718096;
}

.no-photo {
    text-align: center;
    padding: 40px 20px;
    color: #a0aec0;
    background: #edf2f7;
    border-radius: 8px;
    border: 2px dashed #cbd5e0;
}

.no-photo i {
    font-size: 60px;
    margin-bottom: 10px;
    opacity: 0.5;
}

.no-photo p {
    margin: 0;
    font-size: 14px;
}

.file-upload {
    margin-bottom: 15px;
}

.upload-area {
    border: 2px dashed #cbd5e0;
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.upload-area:hover {
    border-color: #667eea;
    background: #edf2f7;
}

.upload-area i {
    font-size: 40px;
    color: #a0aec0;
    margin-bottom: 10px;
}

.upload-area p {
    margin: 0 0 8px 0;
    color: #4a5568;
    font-weight: 500;
}

.upload-area small {
    color: #a0aec0;
    font-size: 12px;
}

.upload-preview {
    margin-top: 20px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.preview-header span {
    font-weight: 600;
    color: #4a5568;
}

#previewImage {
    width: 100%;
    max-height: 200px;
    object-fit: contain;
    display: block;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
}

.modal.show {
    display: block;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    margin: 100px auto;
    z-index: 1;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #2d3748;
}

.close {
    background: none;
    border: none;
    font-size: 24px;
    color: #a0aec0;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.close:hover {
    background: #f7fafc;
    color: #718096;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.employee-delete-info {
    padding: 15px;
    background: #f8fafc;
    border-radius: 8px;
    margin: 15px 0;
    border-left: 4px solid #e53e3e;
}

.employee-delete-info h4 {
    margin: 0 0 10px 0;
    color: #2d3748;
    font-size: 18px;
}

.employee-delete-info p {
    margin: 5px 0;
    color: #4a5568;
    font-size: 14px;
}

.employee-delete-info strong {
    color: #2d3748;
}

/* Form Styles */
.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 25px;
    margin-bottom: 25px;
}

@media (min-width: 768px) {
    .form-row {
        grid-template-columns: 1fr 1fr;
    }
}

.form-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.form-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h3 i {
    color: #667eea;
}

.card-body {
    padding: 25px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

@media (min-width: 640px) {
    .form-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* FIX: Full-width group for Additional Qualifications */
@media (min-width: 640px) {
    .form-grid .full-width-group {
        grid-column: span 2;  /* Make this group span both columns for full width */
    }
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
}

.form-group.required label::after {
    content: " *";
    color: #e53e3e;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
    min-height: 42px; /* FIXED: Consistent field height */
    box-sizing: border-box; /* FIXED: Proper box sizing */
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control.is-invalid {
    border-color: #e53e3e;
}

.form-text {
    display: block;
    margin-top: 4px;
    font-size: 12px;
    color: #718096;
}

/* Qualification Styles - FIXED: Increased width for qualification name input */
.qualification-entry {
    margin-bottom: 12px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.qualification-entry:hover {
    background: #edf2f7;
    border-color: #cbd5e0;
}

.qualification-row {
    display: grid;
    grid-template-columns: 3fr 1fr auto;  /* Increased width for qualification name input */
    gap: 10px;
    align-items: start;
}

.qualification-name {
    min-width: 0; /* Prevents overflow */
}

.qualification-year {
    min-width: 120px;
}

.remove-qualification {
    padding: 10px 16px;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 42px;
    flex-shrink: 0;
}

.remove-qualification i {
    font-size: 14px;
}

.remove-qualification .btn-text {
    display: inline;
}

/* Mobile Media Query - Updated */
@media (max-width: 768px) {
    .qualification-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .qualification-year {
        min-width: 100%;
    }
    
    .remove-qualification {
        width: 100%;
        justify-content: center;
    }
    
    .remove-qualification .btn-text {
        display: inline !important;
    }
    
    /* Adjust photo section for mobile */
    .photo-section {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .current-photo-img {
        max-width: 150px;
        max-height: 150px;
    }
}

/* Desktop-specific styling */
@media (min-width: 769px) {
    .remove-qualification .btn-text {
        display: inline;
    }
}

/* Add qualification button styling */
#add-qualification-btn {
    margin-top: 10px;
    background: white;
    border: 2px dashed #cbd5e0;
    color: #667eea;
    font-weight: 600;
}

#add-qualification-btn:hover {
    background: #f8fafc;
    border-color: #667eea;
    border-style: solid;
}

/* Form Actions */
.form-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    padding: 25px;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    margin-top: 30px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
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
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #4a5568;
    color: white;
}

.btn-secondary:hover {
    background: #2d3748;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(45, 55, 72, 0.3);
}

.btn-outline {
    background: white;
    color: #4a5568;
    border: 2px solid #e2e8f0;
}

.btn-outline:hover {
    background: #f8fafc;
    border-color: #cbd5e0;
    transform: translateY(-2px);
}

.btn-danger {
    background: #e53e3e;
    color: white;
}

.btn-danger:hover {
    background: #c53030;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(229, 62, 62, 0.3);
}

.btn-sm {
    padding: 8px 16px;
    font-size: 14px;
}

/* Alert Styles */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid transparent;
}

.alert-success {
    background: #f0fff4;
    border-color: #c6f6d5;
    color: #22543d;
}

.alert-danger {
    background: #fff5f5;
    border-color: #fed7d7;
    color: #742a2a;
}

.alert i {
    font-size: 18px;
}

.alert-success i {
    color: #38a169;
}

.alert-danger i {
    color: #e53e3e;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
    }
    
    .header-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-lg {
        width: 100%;
        justify-content: center;
    }
    
    .modal-content {
        margin: 20px;
        width: calc(100% - 40px);
    }
}

@media (max-width: 640px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .form-actions {
        padding: 20px;
    }
    
    .header-title h1 {
        font-size: 24px;
    }
}
</style>