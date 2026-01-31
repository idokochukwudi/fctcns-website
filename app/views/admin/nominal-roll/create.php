<?php
/**
 * Create Employee View - TRUE TABBED INTERFACE
 * Form to add new employee to nominal roll
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee | Nominal Roll</title>
    
    <!-- ============================================
    CRITICAL CSS - Inlined for immediate rendering
    ============================================ -->
    <style>
    /* Critical CSS - Loads immediately */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        color: #333;
        line-height: 1.6;
        min-height: 100vh;
    }
    
    .create-employee-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .page-header {
        margin-bottom: 30px;
        animation: slideDown 0.4s ease-out;
    }
    
    @keyframes slideDown {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .header-title h1 {
        font-size: 28px;
        color: #2c3e50;
        margin-bottom: 5px;
        font-weight: 700;
    }
    
    .header-title .subtitle {
        color: #7f8c8d;
        font-size: 15px;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 14px;
    }
    
    .btn-outline {
        background: transparent;
        border: 2px solid #3498db;
        color: #3498db;
    }
    
    .btn-outline:hover {
        background: #3498db;
        color: white;
    }
    
    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideInLeft 0.3s ease-out;
    }
    
    @keyframes slideInLeft {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .alert-success {
        background: #d4edda;
        border-left: 4px solid #28a745;
        color: #155724;
    }
    
    .alert-danger {
        background: #f8d7da;
        border-left: 4px solid #dc3545;
        color: #721c24;
    }
    
    /* ============================================
    TAB NAVIGATION SYSTEM - TRUE TABBED INTERFACE
    ============================================ */
    .tab-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 30px;
    }
    
    .tab-navigation {
        display: flex;
        background: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        overflow-x: auto;
        scrollbar-width: thin;
    }
    
    .tab-navigation::-webkit-scrollbar {
        height: 6px;
    }
    
    .tab-navigation::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .tab-navigation::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .tab-btn {
        flex: 0 0 auto;
        padding: 20px 25px;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 600;
        color: #666;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 12px;
        white-space: nowrap;
        position: relative;
        min-width: 180px;
        justify-content: center;
    }
    
    .tab-btn:hover {
        background: #f0f7ff;
        color: #3498db;
    }
    
    .tab-btn.active {
        background: white;
        color: #3498db;
        border-bottom: 3px solid #3498db;
    }
    
    .tab-btn i {
        font-size: 18px;
    }
    
    .tab-content {
        display: none;
        padding: 40px;
        animation: fadeInUp 0.4s ease-out;
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .tab-content.active {
        display: block;
    }
    
    /* Form structure within tabs */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }
    
    .form-group {
        margin-bottom: 0;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #2c3e50;
        font-size: 14px;
    }
    
    .form-group.required label::after {
        content: ' *';
        color: #e74c3c;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s;
        background: #f8f9fa;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #3498db;
        background: white;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }
    
    .form-control:disabled {
        background: #f5f5f5;
        cursor: not-allowed;
        opacity: 0.7;
    }
    
    /* Full width groups */
    .full-width-group {
        grid-column: 1 / -1;
    }
    
    /* Tab navigation buttons */
    .tab-navigation-buttons {
        display: flex;
        justify-content: space-between;
        padding: 30px 0 0 0;
        margin-top: 30px;
        border-top: 1px solid #eee;
    }
    
    .nav-btn {
        padding: 12px 30px;
        font-weight: 600;
        min-width: 150px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }
    
    .btn-secondary {
        background: #95a5a6;
        color: white;
        border: none;
    }
    
    .btn-secondary:hover {
        background: #7f8c8d;
        transform: translateY(-2px);
    }
    
    /* Form actions - Fixed at bottom */
    .form-actions {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 20px;
        border-top: 1px solid #e0e0e0;
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    }
    
    /* Skeleton loading for deferred tabs */
    .skeleton-tab {
        padding: 40px;
        min-height: 500px;
    }
    
    .skeleton-field {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        height: 40px;
        border-radius: 6px;
        margin-bottom: 25px;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Mobile responsive base */
    @media (max-width: 768px) {
        .create-employee-container {
            padding: 10px;
        }
        
        .tab-navigation {
            flex-wrap: nowrap;
            overflow-x: auto;
        }
        
        .tab-btn {
            min-width: 140px;
            padding: 15px 20px;
            font-size: 14px;
            gap: 8px;
        }
        
        .tab-btn i {
            font-size: 16px;
        }
        
        .tab-content {
            padding: 25px 20px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .tab-navigation-buttons {
            flex-direction: column;
            gap: 15px;
        }
        
        .nav-btn {
            width: 100%;
            justify-content: center;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    /* Small mobile */
    @media (max-width: 480px) {
        .header-title h1 {
            font-size: 22px;
        }
        
        .header-title .subtitle {
            font-size: 14px;
        }
        
        .tab-btn {
            min-width: 120px;
            padding: 12px 15px;
            font-size: 13px;
        }
        
        .form-control {
            padding: 10px 12px;
        }
    }
    </style>
    
    <!-- ============================================
    DEFERRED RESOURCES - Load after critical content
    ============================================ -->
    <!-- Preload Font Awesome -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    
    <!-- Deferred CSS for non-critical styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          crossorigin="anonymous" media="print" onload="this.media='all'">
    
    <!-- Prefetch back navigation -->
    <link rel="prefetch" href="<?php echo $baseUrl; ?>/admin/nominal-roll">
</head>
<body>
<div class="create-employee-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1>Add New Employee</h1>
                <p class="subtitle">Complete all sections using the tabs below</p>
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

    <!-- Form Validation Errors -->
    <?php if (!empty($formErrors)): ?>
    <div class="alert alert-danger" id="formValidationErrors">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <i class="fas fa-exclamation-circle"></i> <strong>Please fix the following errors:</strong>
            </div>
            <button type="button" class="btn-close" onclick="document.getElementById('formValidationErrors').remove()" style="background: none; border: none; font-size: 18px; cursor: pointer;">×</button>
        </div>
        <ul style="margin: 10px 0 0 0; padding-left: 20px;">
            <?php foreach ($formErrors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Employee Form -->
    <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/store" enctype="multipart/form-data" class="employee-form" id="employeeForm">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        
        <!-- ============================================
        TRUE TABBED INTERFACE
        Only ONE tab visible at a time
        ============================================ -->
        <div class="tab-container">
            <!-- Tab Navigation -->
            <div class="tab-navigation" id="tabNavigation" role="tablist">
                <button type="button" class="tab-btn active" data-tab="basic">
                    <i class="fas fa-id-card"></i> Basic Info
                </button>
                <button type="button" class="tab-btn" data-tab="employment">
                    <i class="fas fa-briefcase"></i> Employment
                </button>
                <button type="button" class="tab-btn" data-tab="education">
                    <i class="fas fa-graduation-cap"></i> Education
                </button>
                <button type="button" class="tab-btn" data-tab="location">
                    <i class="fas fa-map-marker-alt"></i> Location
                </button>
                <button type="button" class="tab-btn" data-tab="medical">
                    <i class="fas fa-heartbeat"></i> Medical
                </button>
                <button type="button" class="tab-btn" data-tab="financial">
                    <i class="fas fa-file-invoice-dollar"></i> Financial
                </button>
                <button type="button" class="tab-btn" data-tab="contacts">
                    <i class="fas fa-user-friends"></i> Contacts
                </button>
                <button type="button" class="tab-btn" data-tab="photo">
                    <i class="fas fa-camera"></i> Photo
                </button>
            </div>

            <!-- ============================================
            TAB 1: BASIC INFORMATION (Loaded Immediately)
            ============================================ -->
            <div class="tab-content active" id="tab-basic">
                <div class="form-grid">
                    <div class="form-group required">
                        <label for="employee_number">Employee Number *</label>
                        <input type="text" 
                               id="employee_number" 
                               name="employee_number" 
                               value="<?php echo htmlspecialchars($formData['employee_number'] ?? $employeeNumber ?? ''); ?>"
                               class="form-control"
                               required
                               placeholder="EMP20240001"
                               autofocus>
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
                
                <!-- Tab Navigation Buttons -->
                <div class="tab-navigation-buttons">
                    <button type="button" class="btn btn-outline prev-tab" disabled>
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary next-tab">
                        Next: Employment <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ============================================
            DEFERRED TABS (Will load when clicked)
            ============================================ -->
            <div id="tab-employment" class="tab-content skeleton-tab">
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
            </div>
            
            <div id="tab-education" class="tab-content skeleton-tab">
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
            </div>
            
            <div id="tab-location" class="tab-content skeleton-tab">
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
            </div>
            
            <div id="tab-medical" class="tab-content skeleton-tab">
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
            </div>
            
            <div id="tab-financial" class="tab-content skeleton-tab">
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
            </div>
            
            <div id="tab-contacts" class="tab-content skeleton-tab">
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
            </div>
            
            <div id="tab-photo" class="tab-content skeleton-tab">
                <div class="skeleton-field"></div>
                <div class="skeleton-field"></div>
            </div>
        </div>

        <!-- ============================================
        FORM ACTIONS (Always visible at bottom)
        ============================================ -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Employee Record
            </button>
            <button type="button" id="saveDraft" class="btn btn-secondary">
                <i class="fas fa-file-alt"></i> Save as Draft
            </button>
            <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<!-- ============================================
TEMPLATES FOR DEFERRED TABS
Loaded via JavaScript when needed
============================================ -->

<!-- Template for Employment Details (Tab 2) -->
<template id="template-employment">
    <div class="form-grid">
        <!-- MOVED Personal File Number to Employment Section -->
        <div class="form-group">
            <label for="pf_number">Personal File (PF) Number</label>
            <input type="text" 
                   id="pf_number" 
                   name="pf_number" 
                   value="<?php echo htmlspecialchars($formData['pf_number'] ?? ''); ?>"
                   class="form-control"
                   placeholder="e.g., FCTCNS/PF/001">
        </div>

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
    
    <!-- Tab Navigation Buttons -->
    <div class="tab-navigation-buttons">
        <button type="button" class="btn btn-outline prev-tab">
            <i class="fas fa-arrow-left"></i> Previous: Basic Info
        </button>
        <button type="button" class="btn btn-primary next-tab">
            Next: Education <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</template>

<!-- Template for Educational Qualifications (Tab 3) -->
<template id="template-education">
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

        <!-- Additional Qualifications -->
        <div class="form-group full-width-group">
            <label>Additional Qualifications</label>
            <div id="qualifications-container">
                <!-- Will be populated by JavaScript -->
            </div>
            <button type="button" id="add-qualification-btn" class="btn btn-sm btn-outline">
                <i class="fas fa-plus"></i> Add Qualification
            </button>
            <small class="form-text">Add other qualifications with year obtained</small>
        </div>
    </div>
    
    <!-- Tab Navigation Buttons -->
    <div class="tab-navigation-buttons">
        <button type="button" class="btn btn-outline prev-tab">
            <i class="fas fa-arrow-left"></i> Previous: Employment
        </button>
        <button type="button" class="btn btn-primary next-tab">
            Next: Location <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</template>

<!-- Template for Location (Tab 4) -->
<template id="template-location">
    <div class="form-grid">
        <?php 
        $nigerian_states = [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
            'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT', 'Gombe',
            'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara',
            'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau',
            'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'
        ];
        ?>
        
        <div class="form-group required">
            <label for="state">State of Origin *</label>
            <select id="state" name="state" class="form-control" required>
                <option value="">Select State</option>
                <?php foreach ($nigerian_states as $state): ?>
                <option value="<?php echo htmlspecialchars($state); ?>"
                    <?php echo ($formData['state'] ?? '') === $state ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($state); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group required">
            <label for="local_govt_area">Local Government Area *</label>
            <select id="local_govt_area" name="local_govt_area" class="form-control" required>
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
    
    <!-- Tab Navigation Buttons -->
    <div class="tab-navigation-buttons">
        <button type="button" class="btn btn-outline prev-tab">
            <i class="fas fa-arrow-left"></i> Previous: Education
        </button>
        <button type="button" class="btn btn-primary next-tab">
            Next: Medical <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</template>

<!-- Template for Medical Information (Tab 5) -->
<template id="template-medical">
    <div class="form-grid">
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
    
    <!-- Tab Navigation Buttons -->
    <div class="tab-navigation-buttons">
        <button type="button" class="btn btn-outline prev-tab">
            <i class="fas fa-arrow-left"></i> Previous: Location
        </button>
        <button type="button" class="btn btn-primary next-tab">
            Next: Financial <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</template>

<!-- Template for Financial Information (Tab 6) -->
<template id="template-financial">
    <div class="form-grid">
        <?php 
        $nigerian_banks = [
            'Access Bank', 'Citibank', 'Ecobank', 'Fidelity Bank', 'First Bank',
            'First City Monument Bank', 'Guaranty Trust Bank', 'Heritage Bank',
            'Keystone Bank', 'Polaris Bank', 'Providus Bank', 'Stanbic IBTC Bank',
            'Standard Chartered Bank', 'Sterling Bank', 'Suntrust Bank',
            'Union Bank', 'United Bank for Africa', 'Unity Bank', 'Wema Bank',
            'Zenith Bank'
        ];
        ?>
        
        <div class="form-group">
            <label for="bank_name">Bank Name</label>
            <select id="bank_name" name="bank_name" class="form-control">
                <option value="">Select Bank</option>
                <?php foreach ($nigerian_banks as $bank): ?>
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
        ?>
        
        <div class="form-group">
            <label for="pension_fund_admin">Pension Fund Administrator (PFA)</label>
            <select id="pension_fund_admin" name="pension_fund_admin" class="form-control">
                <option value="">Select PFA</option>
                <?php foreach ($pension_administrators as $pfa): ?>
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
    
    <!-- Tab Navigation Buttons -->
    <div class="tab-navigation-buttons">
        <button type="button" class="btn btn-outline prev-tab">
            <i class="fas fa-arrow-left"></i> Previous: Medical
        </button>
        <button type="button" class="btn btn-primary next-tab">
            Next: Contacts <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</template>

<!-- Template for Emergency Contacts (Tab 7) -->
<template id="template-contacts">
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
    
    <!-- Tab Navigation Buttons -->
    <div class="tab-navigation-buttons">
        <button type="button" class="btn btn-outline prev-tab">
            <i class="fas fa-arrow-left"></i> Previous: Financial
        </button>
        <button type="button" class="btn btn-primary next-tab">
            Next: Photo <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</template>

<!-- Template for Passport Photo (Tab 8) -->
<template id="template-photo">
    <div class="form-grid">
        <div class="form-group full-width-group">
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
    
    <!-- Tab Navigation Buttons -->
    <div class="tab-navigation-buttons">
        <button type="button" class="btn btn-outline prev-tab">
            <i class="fas fa-arrow-left"></i> Previous: Contacts
        </button>
        <button type="button" class="btn btn-primary" id="finalSubmit">
            <i class="fas fa-save"></i> Complete & Save Employee
        </button>
    </div>
</template>

<!-- Template for Qualification Entry -->
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
                <i class="fas fa-trash"></i> Remove
            </button>
        </div>
    </div>
</template>

<!-- ============================================
OPTIMIZED JAVASCRIPT - FIXED TAB NAVIGATION
============================================ -->
<script>
// Load immediately critical functions
(function() {
    'use strict';
    
    // ============================================
    // GLOBAL VARIABLES
    // ============================================
    const tabs = ['basic', 'employment', 'education', 'location', 'medical', 'financial', 'contacts', 'photo'];
    let currentTabIndex = 0;
    const loadedTabs = new Set(['basic']);
    
    // ============================================
    // TAB MANAGEMENT SYSTEM
    // ============================================
    function switchTab(tabName) {
        // Update current tab index
        currentTabIndex = tabs.indexOf(tabName);
        
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
            content.style.display = 'none';
        });
        
        // Show selected tab content
        const targetTab = document.getElementById('tab-' + tabName);
        if (targetTab) {
            targetTab.style.display = 'block';
            setTimeout(() => {
                targetTab.classList.add('active');
            }, 10);
        }
        
        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.tab === tabName) {
                btn.classList.add('active');
            }
        });
        
        // Update navigation buttons
        updateNavigationButtons();
        
        // Focus on first input in tab
        setTimeout(() => {
            const firstInput = targetTab.querySelector('.form-control');
            if (firstInput) firstInput.focus();
        }, 100);
        
        // Save current tab to localStorage
        localStorage.setItem('last_active_tab', tabName);
    }
    
    function updateNavigationButtons() {
        const currentTab = document.querySelector('.tab-content.active');
        if (!currentTab) return;
        
        const prevButtons = currentTab.querySelectorAll('.prev-tab');
        const nextButtons = currentTab.querySelectorAll('.next-tab');
        
        prevButtons.forEach(btn => {
            btn.disabled = currentTabIndex === 0;
            if (currentTabIndex > 0) {
                btn.innerHTML = `<i class="fas fa-arrow-left"></i> Previous: ${getTabName(tabs[currentTabIndex - 1])}`;
            }
        });
        
        nextButtons.forEach(btn => {
            if (currentTabIndex === tabs.length - 1) {
                btn.style.display = 'none';
            } else {
                btn.style.display = 'inline-flex';
                btn.innerHTML = `Next: ${getTabName(tabs[currentTabIndex + 1])} <i class="fas fa-arrow-right"></i>`;
            }
        });
    }
    
    function getTabName(tabKey) {
        const tabNames = {
            'basic': 'Basic Info',
            'employment': 'Employment',
            'education': 'Education',
            'location': 'Location',
            'medical': 'Medical',
            'financial': 'Financial',
            'contacts': 'Contacts',
            'photo': 'Photo'
        };
        return tabNames[tabKey] || tabKey;
    }
    
    // ============================================
    // FORM VALIDATION & SUBMISSION - FIXED
    // ============================================
    function initializeFormValidation() {
        const form = document.getElementById('employeeForm');
        
        // Main form submit button (Save Employee Record)
        const mainSubmitBtn = form.querySelector('button[type="submit"]');
        if (mainSubmitBtn) {
            mainSubmitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Validate all tabs before submission
                if (validateAllTabs()) {
                    // Remove draft flag if present
                    const existingDraftInput = form.querySelector('input[name="save_as_draft"]');
                    if (existingDraftInput) {
                        existingDraftInput.remove();
                    }
                    
                    // Set the regular save flag
                    const saveInput = document.createElement('input');
                    saveInput.type = 'hidden';
                    saveInput.name = 'regular_save';
                    saveInput.value = '1';
                    form.appendChild(saveInput);
                    
                    // Submit the form
                    form.submit();
                }
            });
        }
        
        // Save draft button
        const saveDraftBtn = document.getElementById('saveDraft');
        if (saveDraftBtn) {
            saveDraftBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove regular save flag if present
                const existingSaveInput = form.querySelector('input[name="regular_save"]');
                if (existingSaveInput) {
                    existingSaveInput.remove();
                }
                
                // Add draft flag
                const draftInput = document.createElement('input');
                draftInput.type = 'hidden';
                draftInput.name = 'save_as_draft';
                draftInput.value = '1';
                form.appendChild(draftInput);
                
                // Validate all tabs even for draft
                if (validateAllTabs()) {
                    form.submit();
                }
            });
        }
        
        // Also intercept normal form submit
        form.addEventListener('submit', function(e) {
            if (!validateAllTabs()) {
                e.preventDefault();
                return false;
            }
        });
    }
    
    // ============================================
    // FINAL SUBMIT BUTTON HANDLER - FIXED
    // ============================================
    function attachFinalSubmitHandler() {
        const finalSubmitBtn = document.getElementById('finalSubmit');
        if (finalSubmitBtn) {
            // Remove any existing event listeners
            finalSubmitBtn.replaceWith(finalSubmitBtn.cloneNode(true));
            const newFinalSubmitBtn = document.getElementById('finalSubmit');
            
            newFinalSubmitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Final submit button clicked');
                
                // Validate all tabs before submission
                if (validateAllTabs()) {
                    // Remove draft flag if present
                    const existingDraftInput = document.querySelector('input[name="save_as_draft"]');
                    if (existingDraftInput) {
                        existingDraftInput.remove();
                    }
                    
                    // Remove regular save flag if present
                    const existingSaveInput = document.querySelector('input[name="regular_save"]');
                    if (existingSaveInput) {
                        existingSaveInput.remove();
                    }
                    
                    console.log('Submitting form...');
                    document.getElementById('employeeForm').submit();
                }
            });
        }
    }
    
    function validateAllTabs() {
        let isValid = true;
        const errorMessages = [];
        const errorFields = [];
        
        tabs.forEach(tabName => {
            const tab = document.getElementById('tab-' + tabName);
            if (tab) {
                const requiredFields = tab.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        const fieldName = field.labels?.[0]?.textContent || field.placeholder || 'This field';
                        errorMessages.push(`${fieldName.replace('*', '').trim()} in ${getTabName(tabName)} section`);
                        errorFields.push({
                            field: field,
                            tab: tabName,
                            label: fieldName.replace('*', '').trim()
                        });
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
            }
        });
        
        if (!isValid) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger';
            errorDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 10000; min-width: 300px;';
            errorDiv.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <strong><i class="fas fa-exclamation-circle"></i> Validation Error</strong>
                        <p style="margin: 5px 0 0 0; font-size: 13px;">Please complete all required fields.</p>
                    </div>
                    <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; font-size: 18px; cursor: pointer;">×</button>
                </div>
                <div style="margin-top: 10px; max-height: 200px; overflow-y: auto;">
                    <ul style="margin: 0; padding-left: 20px; font-size: 12px;">
                        ${errorMessages.slice(0, 5).map(msg => `<li>${msg}</li>`).join('')}
                    </ul>
                </div>
                <div style="margin-top: 10px;">
                    <button type="button" class="btn btn-sm btn-primary" onclick="focusFirstError()" style="padding: 5px 10px; font-size: 12px;">
                        <i class="fas fa-arrow-right"></i> Go to first error
                    </button>
                </div>
            `;
            
            document.querySelectorAll('.alert.alert-danger[style*="position: fixed"]').forEach(el => el.remove());
            document.body.appendChild(errorDiv);
            
            setTimeout(() => {
                if (errorDiv.parentElement) {
                    errorDiv.remove();
                }
            }, 10000);
            
            window.errorFields = errorFields;
            
            window.focusFirstError = function() {
                if (window.errorFields && window.errorFields.length > 0) {
                    const firstError = window.errorFields[0];
                    loadTab(firstError.tab);
                    setTimeout(() => {
                        switchTab(firstError.tab);
                        setTimeout(() => {
                            firstError.field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstError.field.focus();
                            firstError.field.classList.add('error-highlight');
                            
                            setTimeout(() => {
                                firstError.field.classList.remove('error-highlight');
                            }, 3000);
                        }, 100);
                    }, 50);
                    
                    errorDiv.remove();
                }
            };
        } else {
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        }
        
        return isValid;
    }
    
    function nextTab() {
        if (currentTabIndex < tabs.length - 1) {
            if (!validateCurrentTab()) {
                return;
            }
            
            const nextTabName = tabs[currentTabIndex + 1];
            loadTab(nextTabName);
            setTimeout(() => switchTab(nextTabName), 50);
        }
    }
    
    function prevTab() {
        if (currentTabIndex > 0) {
            const prevTabName = tabs[currentTabIndex - 1];
            loadTab(prevTabName);
            setTimeout(() => switchTab(prevTabName), 50);
        }
    }
    
    function validateCurrentTab() {
        const currentTab = document.querySelector('.tab-content.active');
        if (!currentTab) return true;
        
        let isValid = true;
        const requiredFields = currentTab.querySelectorAll('[required]');
        const currentTabName = tabs[currentTabIndex];
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                field.classList.add('error-highlight');
                
                setTimeout(() => {
                    field.classList.remove('error-highlight');
                }, 3000);
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            const notification = document.createElement('div');
            notification.className = 'alert alert-danger';
            notification.style.cssText = 'position: fixed; top: 80px; right: 20px; z-index: 10000; min-width: 250px; padding: 10px; font-size: 13px;';
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Please complete required fields in ${getTabName(currentTabName)} tab</span>
                    <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()" style="margin-left: auto;">×</button>
                </div>
            `;
            
            document.querySelectorAll('.alert.alert-danger[style*="position: fixed"]').forEach(el => el.remove());
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
        
        return isValid;
    }
    
    // ============================================
    // LAZY LOADING SYSTEM - FIXED VERSION
    // ============================================
    function loadTab(tabName) {
        if (loadedTabs.has(tabName)) {
            updateNavigationButtons();
            
            // Re-attach final submit handler if this is the photo tab
            if (tabName === 'photo') {
                attachFinalSubmitHandler();
            }
            
            return;
        }
        
        const templateMap = {
            'employment': 'template-employment',
            'education': 'template-education',
            'location': 'template-location',
            'medical': 'template-medical',
            'financial': 'template-financial',
            'contacts': 'template-contacts',
            'photo': 'template-photo'
        };
        
        const templateId = templateMap[tabName];
        if (!templateId) return;
        
        const template = document.getElementById(templateId);
        if (!template) return;
        
        const tabContent = document.getElementById('tab-' + tabName);
        if (!tabContent) return;
        
        const content = template.content.cloneNode(true);
        tabContent.innerHTML = '';
        tabContent.appendChild(content);
        
        loadedTabs.add(tabName);
        
        initializeTabFunctionality(tabName);
        
        // Attach event listeners to this tab's navigation buttons
        attachNavigationListeners(tabContent);
        
        // Attach final submit handler if this is the photo tab
        if (tabName === 'photo') {
            attachFinalSubmitHandler();
        }
        
        console.log(`Tab ${tabName} loaded successfully`);
    }
    
    // ============================================
    // ATTACH NAVIGATION LISTENERS
    // ============================================
    function attachNavigationListeners(tabElement) {
        const nextButtons = tabElement.querySelectorAll('.next-tab');
        const prevButtons = tabElement.querySelectorAll('.prev-tab');
        
        nextButtons.forEach(btn => {
            // Remove any existing listeners
            btn.replaceWith(btn.cloneNode(true));
            const newBtn = tabElement.querySelector('.next-tab');
            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                nextTab();
            });
        });
        
        prevButtons.forEach(btn => {
            // Remove any existing listeners
            btn.replaceWith(btn.cloneNode(true));
            const newBtn = tabElement.querySelector('.prev-tab');
            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                prevTab();
            });
        });
        
        updateNavigationButtons();
    }
    
    // ============================================
    // TAB-SPECIFIC FUNCTIONALITY
    // ============================================
    function initializeTabFunctionality(tabName) {
        switch(tabName) {
            case 'education':
                initializeQualificationsSystem();
                break;
            case 'location':
                initializeStateLGASystem();
                break;
            case 'medical':
                initializeDisabilitySystem();
                break;
            case 'financial':
                initializeBankPFASystem();
                break;
            case 'photo':
                initializePassportUpload();
                break;
        }
    }
    
    // ============================================
    // STATE & LGA SYSTEM
    // ============================================
    function initializeStateLGASystem() {
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
                       'Ayamelum', 'Dunukofia', 'Ekwusigo', 'Idemili North', 'Idemili South', 
                       'Ihiala', 'Njikoka', 'Nnewi North', 'Nnewi South', 'Ogbaru', 'Onitsha North', 
                       'Onitsha South', 'Orumba North', 'Orumba South', 'Oyi'],
            'Bauchi': ['Alkaleri', 'Bauchi', 'Bogoro', 'Damban', 'Darazo', 'Dass', 'Gamawa', 'Ganjuwa', 
                      'Giade', 'Itas/Gadau', 'Jama\'are', 'Katagum', 'Kirfi', 'Misau', 'Ningi', 
                      'Shira', 'Tafawa Balewa', 'Toro', 'Warji', 'Zaki'],
            'Bayelsa': ['Brass', 'Ekeremor', 'Kolokuma/Opokuma', 'Nembe', 'Ogbia', 'Sagbama', 'Southern Ijaw', 
                       'Yenagoa'],
            'Benue': ['Ado', 'Agatu', 'Apa', 'Buruku', 'Gboko', 'Guma', 'Gwer East', 'Gwer West', 
                     'Katsina-Ala', 'Konshisha', 'Kwande', 'Logo', 'Makurdi', 'Obi', 'Ogbadibo', 
                     'Ohimini', 'Oju', 'Okpokwu', 'Oturkpo', 'Tarka', 'Ukum', 'Ushongo', 'Vandeikya'],
            'Borno': ['Abadam', 'Askira/Uba', 'Bama', 'Bayo', 'Biu', 'Chibok', 'Damboa', 'Dikwa', 
                     'Gubio', 'Guzamala', 'Gwoza', 'Hawul', 'Jere', 'Kaga', 'Kala/Balge', 'Konduga', 
                     'Kukawa', 'Kwaya Kusar', 'Mafa', 'Magumeri', 'Maiduguri', 'Marte', 'Mobbar', 
                     'Monguno', 'Ngala', 'Nganzai', 'Shani'],
            'Cross River': ['Abi', 'Akamkpa', 'Akpabuyo', 'Bakassi', 'Bekwarra', 'Biase', 'Boki', 
                           'Calabar Municipal', 'Calabar South', 'Etung', 'Ikom', 'Obanliku', 
                           'Obubra', 'Obudu', 'Odukpani', 'Ogoja', 'Yakuur', 'Yala'],
            'Delta': ['Aniocha North', 'Aniocha South', 'Bomadi', 'Burutu', 'Ethiope East', 'Ethiope West', 
                     'Ika North East', 'Ika South', 'Isoko North', 'Isoko South', 'Ndokwa East', 
                     'Ndokwa West', 'Okpe', 'Oshimili North', 'Oshimili South', 'Patani', 'Sapele', 
                     'Udu', 'Ughelli North', 'Ughelli South', 'Ukwuani', 'Uvwie', 'Warri North', 
                     'Warri South', 'Warri South West'],
            'Ebonyi': ['Abakaliki', 'Afikpo North', 'Afikpo South', 'Ebonyi', 'Ezza North', 'Ezza South', 
                      'Ikwo', 'Ishielu', 'Ivo', 'Izzi', 'Ohaozara', 'Ohaukwu', 'Onicha'],
            'Edo': ['Akoko-Edo', 'Egor', 'Esan Central', 'Esan North-East', 'Esan South-East', 
                   'Esan West', 'Etsako Central', 'Etsako East', 'Etsako West', 'Igueben', 
                   'Ikpoba Okha', 'Orhionmwon', 'Oredo', 'Ovia North-East', 'Ovia South-West', 
                   'Owan East', 'Owan West', 'Uhunmwonde'],
            'Ekiti': ['Ado Ekiti', 'Efon', 'Ekiti East', 'Ekiti South-West', 'Ekiti West', 'Emure', 
                     'Gbonyin', 'Ido Osi', 'Ijero', 'Ikere', 'Ikole', 'Ilejemeje', 'Irepodun/Ifelodun', 
                     'Ise/Orun', 'Moba', 'Oye'],
            'Enugu': ['Aninri', 'Awgu', 'Enugu East', 'Enugu North', 'Enugu South', 'Ezeagu', 
                     'Igbo Etiti', 'Igbo Eze North', 'Igbo Eze South', 'Isi Uzo', 'Nkanu East', 
                     'Nkanu West', 'Nsukka', 'Oji River', 'Udenu', 'Udi', 'Uzo Uwani'],
            'FCT': ['Abaji', 'Bwari', 'Gwagwalada', 'Kuje', 'Kwali', 'Municipal Area Council'],
            'Gombe': ['Akko', 'Balanga', 'Billiri', 'Dukku', 'Funakaye', 'Gombe', 'Kaltungo', 
                     'Kwami', 'Nafada', 'Shongom', 'Yamaltu/Deba'],
            'Imo': ['Aboh Mbaise', 'Ahiazu Mbaise', 'Ehime Mbano', 'Ezinihitte', 'Ideato North', 
                   'Ideato South', 'Ihitte/Uboma', 'Ikeduru', 'Isiala Mbano', 'Isu', 'Mbaitoli', 
                   'Ngor Okpala', 'Njaba', 'Nkwerre', 'Nwangele', 'Obowo', 'Oguta', 'Ohaji/Egbema', 
                   'Okigwe', 'Orlu', 'Orsu', 'Oru East', 'Oru West', 'Owerri Municipal', 'Owerri North', 
                   'Owerri West', 'Unuimo'],
            'Jigawa': ['Auyo', 'Babura', 'Biriniwa', 'Birnin Kudu', 'Buji', 'Dutse', 'Gagarawa', 
                      'Garki', 'Gumel', 'Guri', 'Gwaram', 'Gwiwa', 'Hadejia', 'Jahun', 'Kafin Hausa', 
                      'Kazaure', 'Kiri Kasama', 'Kiyawa', 'Kaugama', 'Maigatari', 'Malam Madori', 
                      'Miga', 'Ringim', 'Roni', 'Sule Tankarkar', 'Taura', 'Yankwashi'],
            'Kaduna': ['Birnin Gwari', 'Chikun', 'Giwa', 'Igabi', 'Ikara', 'Jaba', 'Jema\'a', 
                      'Kachia', 'Kaduna North', 'Kaduna South', 'Kagarko', 'Kajuru', 'Kaura', 
                      'Kauru', 'Kubau', 'Kudan', 'Lere', 'Makarfi', 'Sabon Gari', 'Sanga', 
                      'Soba', 'Zangon Kataf', 'Zaria'],
            'Kano': ['Ajingi', 'Albasu', 'Bagwai', 'Bebeji', 'Bichi', 'Bunkure', 'Dala', 'Dambatta', 
                    'Dawakin Kudu', 'Dawakin Tofa', 'Doguwa', 'Fagge', 'Gabasawa', 'Garko', 'Garun Mallam', 
                    'Gaya', 'Gezawa', 'Gwale', 'Gwarzo', 'Kabo', 'Kano Municipal', 'Karaye', 'Kibiya', 
                    'Kiru', 'Kumbotso', 'Kunchi', 'Kura', 'Madobi', 'Makoda', 'Minjibir', 'Nasarawa', 
                    'Rano', 'Rimin Gado', 'Rogo', 'Shanono', 'Sumaila', 'Takai', 'Tarauni', 'Tofa', 
                    'Tsanyawa', 'Tudun Wada', 'Ungogo', 'Warawa', 'Wudil'],
            'Katsina': ['Bakori', 'Batagarawa', 'Batsari', 'Baure', 'Bindawa', 'Charanchi', 'Dandume', 
                       'Danja', 'Dan Musa', 'Daura', 'Dutsi', 'Dutsin Ma', 'Faskari', 'Funtua', 
                       'Ingawa', 'Jibia', 'Kafur', 'Kaita', 'Kankara', 'Kankia', 'Katsina', 'Kurfi', 
                       'Kusada', 'Mai\'Adua', 'Malumfashi', 'Mani', 'Mashi', 'Matazu', 'Musawa', 
                       'Rimi', 'Sabuwa', 'Safana', 'Sandamu', 'Zango'],
            'Kebbi': ['Aleiro', 'Arewa Dandi', 'Argungu', 'Augie', 'Bagudo', 'Birnin Kebbi', 'Bunza', 
                     'Dandi', 'Fakai', 'Gwandu', 'Jega', 'Kalgo', 'Koko/Besse', 'Maiyama', 'Ngaski', 
                     'Sakaba', 'Shanga', 'Suru', 'Wasagu/Danko', 'Yauri', 'Zuru'],
            'Kogi': ['Adavi', 'Ajaokuta', 'Ankpa', 'Bassa', 'Dekina', 'Ibaji', 'Idah', 'Igalamela Odolu', 
                    'Ijumu', 'Kabba/Bunu', 'Kogi', 'Lokoja', 'Mopa Muro', 'Ofu', 'Ogori/Magongo', 
                    'Okehi', 'Okene', 'Olamaboro', 'Omala', 'Yagba East', 'Yagba West'],
            'Kwara': ['Asa', 'Baruten', 'Edu', 'Ekiti', 'Ifelodun', 'Ilorin East', 'Ilorin South', 
                     'Ilorin West', 'Irepodun', 'Isin', 'Kaiama', 'Moro', 'Offa', 'Oke Ero', 'Oyun', 
                     'Pategi'],
            'Lagos': ['Agege', 'Ajeromi-Ifelodun', 'Alimosho', 'Amuwo-Odofin', 'Apapa', 'Badagry', 
                     'Epe', 'Eti Osa', 'Ibeju-Lekki', 'Ifako-Ijaiye', 'Ikeja', 'Ikorodu', 'Kosofe', 
                     'Lagos Island', 'Lagos Mainland', 'Mushin', 'Ojo', 'Oshodi-Isolo', 'Shomolu', 
                     'Surulere'],
            'Nasarawa': ['Akwanga', 'Awe', 'Doma', 'Karu', 'Keana', 'Keffi', 'Kokona', 'Lafia', 
                        'Nasarawa', 'Nasarawa Egon', 'Obi', 'Toto', 'Wamba'],
            'Niger': ['Agaie', 'Agwara', 'Bida', 'Borgu', 'Bosso', 'Chanchaga', 'Edati', 'Gbako', 
                     'Gurara', 'Katcha', 'Kontagora', 'Lapai', 'Lavun', 'Magama', 'Mariga', 'Mashegu', 
                     'Mokwa', 'Moya', 'Paikoro', 'Rafi', 'Rijau', 'Shiroro', 'Suleja', 'Tafa', 'Wushishi'],
            'Ogun': ['Abeokuta North', 'Abeokuta South', 'Ado-Odo/Ota', 'Egbado North', 'Egbado South', 
                    'Ewekoro', 'Ifo', 'Ijebu East', 'Ijebu North', 'Ijebu North East', 'Ijebu Ode', 
                    'Ikenne', 'Imeko Afon', 'Ipokia', 'Obafemi Owode', 'Odeda', 'Odogbolu', 'Ogun Waterside', 
                    'Remo North', 'Shagamu', 'Yewa North', 'Yewa South'],
            'Ondo': ['Akoko North-East', 'Akoko North-West', 'Akoko South-East', 'Akoko South-West', 
                    'Akure North', 'Akure South', 'Ese Odo', 'Idanre', 'Ifedore', 'Ilaje', 'Ile Oluji/Okeigbo', 
                    'Irele', 'Odigbo', 'Okitipupa', 'Ondo East', 'Ondo West', 'Ose', 'Owo'],
            'Osun': ['Aiyedaade', 'Aiyedire', 'Atakunmosa East', 'Atakunmosa West', 'Boluwaduro', 
                    'Boripe', 'Ede North', 'Ede South', 'Egbedore', 'Ejigbo', 'Ife Central', 
                    'Ife East', 'Ife North', 'Ife South', 'Ifedayo', 'Ifelodun', 'Ila', 'Ilesa East', 
                    'Ilesa West', 'Irepodun', 'Irewole', 'Isokan', 'Iwo', 'Obokun', 'Odo Otin', 
                    'Ola Oluwa', 'Olorunda', 'Oriade', 'Orolu', 'Osogbo'],
            'Oyo': ['Afijio', 'Akinyele', 'Atiba', 'Atisbo', 'Egbeda', 'Ibadan North', 'Ibadan North-East', 
                   'Ibadan North-West', 'Ibadan South-East', 'Ibadan South-West', 'Ibarapa Central', 
                   'Ibarapa East', 'Ibarapa North', 'Ido', 'Irepo', 'Iseyin', 'Itesiwaju', 'Iwajowa', 
                   'Kajola', 'Lagelu', 'Ogbomosho North', 'Ogbomosho South', 'Ogo Oluwa', 'Olorunsogo', 
                   'Oluyole', 'Ona Ara', 'Orelope', 'Ori Ire', 'Oyo East', 'Oyo West', 'Saki East', 
                   'Saki West', 'Surulere'],
            'Plateau': ['Barkin Ladi', 'Bassa', 'Bokkos', 'Jos East', 'Jos North', 'Jos South', 
                       'Kanam', 'Kanke', 'Langtang North', 'Langtang South', 'Mangu', 'Mikang', 
                       'Pankshin', 'Qua\'an Pan', 'Riyom', 'Shendam', 'Wase'],
            'Rivers': ['Abua/Odual', 'Ahoada East', 'Ahoada West', 'Akuku-Toru', 'Andoni', 'Asari-Toru', 
                      'Bonny', 'Degema', 'Eleme', 'Emohua', 'Etche', 'Gokana', 'Ikwerre', 'Khana', 
                      'Obio/Akpor', 'Ogba/Egbema/Ndoni', 'Ogu/Bolo', 'Okrika', 'Omuma', 'Opobo/Nkoro', 
                      'Oyigbo', 'Port Harcourt', 'Tai'],
            'Sokoto': ['Binji', 'Bodinga', 'Dange Shuni', 'Gada', 'Goronyo', 'Gudu', 'Gwadabawa', 
                      'Illela', 'Isa', 'Kebbe', 'Kware', 'Rabah', 'Sabon Birni', 'Shagari', 'Silame', 
                      'Sokoto North', 'Sokoto South', 'Tambuwal', 'Tangaza', 'Tureta', 'Wamako', 
                      'Wurno', 'Yabo'],
            'Taraba': ['Ardo Kola', 'Bali', 'Donga', 'Gashaka', 'Gassol', 'Ibi', 'Jalingo', 'Karim Lamido', 
                      'Kurmi', 'Lau', 'Sardauna', 'Takum', 'Ussa', 'Wukari', 'Yorro', 'Zing'],
            'Yobe': ['Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 'Gujba', 'Gulani', 
                    'Jakusko', 'Karasuwa', 'Machina', 'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 
                    'Yunusari', 'Yusufari'],
            'Zamfara': ['Anka', 'Bakura', 'Birnin Magaji/Kiyaw', 'Bukkuyum', 'Bungudu', 'Chafe', 'Gummi', 
                       'Gusau', 'Kaura Namoda', 'Maradun', 'Maru', 'Shinkafi', 'Talata Mafara', 'Tsafe', 'Zurmi']
        };
        
        const stateSelect = document.getElementById('state');
        const lgaSelect = document.getElementById('local_govt_area');
        
        if (stateSelect && lgaSelect) {
            const currentState = stateSelect.value;
            if (currentState && nigerianLGAs[currentState]) {
                populateLGAs(currentState);
                lgaSelect.disabled = false;
                
                const formLGA = '<?php echo $formData["local_govt_area"] ?? ""; ?>';
                if (formLGA) {
                    lgaSelect.value = formLGA;
                }
            }
            
            stateSelect.addEventListener('change', function() {
                const selectedState = this.value;
                
                if (selectedState && nigerianLGAs[selectedState]) {
                    populateLGAs(selectedState);
                    lgaSelect.disabled = false;
                } else {
                    lgaSelect.innerHTML = '<option value="">Select State first</option>';
                    lgaSelect.disabled = true;
                }
            });
            
            function populateLGAs(state) {
                lgaSelect.innerHTML = '<option value="">Select LGA</option>';
                nigerianLGAs[state].forEach(lga => {
                    const option = document.createElement('option');
                    option.value = lga;
                    option.textContent = lga;
                    lgaSelect.appendChild(option);
                });
            }
        }
        
        // Auto-calculate geopolitical zone
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
    }
    
    // ============================================
    // QUALIFICATIONS SYSTEM
    // ============================================
    function initializeQualificationsSystem() {
        const qualificationsContainer = document.getElementById('qualifications-container');
        const addQualificationBtn = document.getElementById('add-qualification-btn');
        const qualificationTemplate = document.getElementById('qualification-template');
        
        if (!qualificationsContainer || !addQualificationBtn || !qualificationTemplate) return;
        
        function addQualificationField(name = '', year = '') {
            const templateContent = qualificationTemplate.content.cloneNode(true);
            const entry = templateContent.querySelector('.qualification-entry');
            
            const nameInput = entry.querySelector('.qualification-name');
            const yearSelect = entry.querySelector('.qualification-year');
            
            nameInput.value = name;
            yearSelect.value = year;
            
            const removeBtn = entry.querySelector('.remove-qualification');
            removeBtn.addEventListener('click', function() {
                entry.remove();
            });
            
            qualificationsContainer.appendChild(entry);
        }
        
        <?php if (!isset($formData['additional_qualifications']) || empty($formData['additional_qualifications'])): ?>
        if (qualificationsContainer.children.length === 0) {
            addQualificationField();
        }
        <?php endif; ?>
        
        addQualificationBtn.addEventListener('click', function() {
            addQualificationField();
        });
    }
    
    // ============================================
    // DISABILITY SYSTEM
    // ============================================
    function initializeDisabilitySystem() {
        const disabilitySelect = document.getElementById('disability');
        const disabilityTypeContainer = document.getElementById('disabilityTypeContainer');
        
        if (disabilitySelect && disabilityTypeContainer) {
            disabilitySelect.addEventListener('change', function() {
                if (this.value === 'Yes') {
                    disabilityTypeContainer.style.display = 'block';
                } else {
                    disabilityTypeContainer.style.display = 'none';
                    const disabilityTypeInput = document.getElementById('disability_type');
                    if (disabilityTypeInput) disabilityTypeInput.value = '';
                }
            });
            
            if (disabilitySelect.value === 'Yes') {
                disabilityTypeContainer.style.display = 'block';
            }
        }
    }
    
    // ============================================
    // BANK & PFA SYSTEM
    // ============================================
    function initializeBankPFASystem() {
        const bankSelect = document.getElementById('bank_name');
        const otherBankContainer = document.getElementById('otherBankContainer');
        
        if (bankSelect && otherBankContainer) {
            bankSelect.addEventListener('change', function() {
                if (this.value === 'Other') {
                    otherBankContainer.style.display = 'block';
                } else {
                    otherBankContainer.style.display = 'none';
                    const otherBankInput = document.getElementById('other_bank_name');
                    if (otherBankInput) otherBankInput.value = '';
                }
            });
            
            if (bankSelect.value === 'Other') {
                otherBankContainer.style.display = 'block';
            }
        }
        
        const pfaSelect = document.getElementById('pension_fund_admin');
        const otherPFAContainer = document.getElementById('otherPFAContainer');
        
        if (pfaSelect && otherPFAContainer) {
            pfaSelect.addEventListener('change', function() {
                if (this.value === 'Other') {
                    otherPFAContainer.style.display = 'block';
                } else {
                    otherPFAContainer.style.display = 'none';
                    const otherPFAInput = document.getElementById('other_pension_fund_admin');
                    if (otherPFAInput) otherPFAInput.value = '';
                }
            });
            
            if (pfaSelect.value === 'Other') {
                otherPFAContainer.style.display = 'block';
            }
        }
    }
    
    // ============================================
    // PASSPORT UPLOAD
    // ============================================
    function initializePassportUpload() {
        const passportPhotoInput = document.getElementById('passport_photo');
        const previewImage = document.getElementById('previewImage');
        const uploadPreview = document.getElementById('uploadPreview');
        const removeImageBtn = document.getElementById('removeImage');
        const fileUploadArea = document.querySelector('.upload-area');
        
        if (!passportPhotoInput || !fileUploadArea) return;
        
        fileUploadArea.addEventListener('click', function() {
            passportPhotoInput.click();
        });
        
        fileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        
        fileUploadArea.addEventListener('dragleave', function() {
            this.classList.remove('dragover');
        });
        
        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            if (e.dataTransfer.files.length) {
                passportPhotoInput.files = e.dataTransfer.files;
                passportPhotoInput.dispatchEvent(new Event('change'));
            }
        });
        
        passportPhotoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                handleImageUpload(this.files[0]);
            }
        });

        function handleImageUpload(file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                passportPhotoInput.value = '';
                return;
            }
            
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Only JPG, JPEG, and PNG files are allowed');
                passportPhotoInput.value = '';
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                if (previewImage) {
                    previewImage.src = e.target.result;
                }
                if (uploadPreview) {
                    uploadPreview.style.display = 'block';
                }
            }
            
            reader.readAsDataURL(file);
        }
        
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', function() {
                if (passportPhotoInput) {
                    passportPhotoInput.value = '';
                }
                if (uploadPreview) {
                    uploadPreview.style.display = 'none';
                }
                if (previewImage) {
                    previewImage.src = '#';
                }
            });
        }
    }
    
    // ============================================
    // KEYBOARD NAVIGATION
    // ============================================
    function initializeKeyboardNavigation() {
        document.addEventListener('keydown', function(e) {
            const currentTab = document.querySelector('.tab-content.active');
            
            if (e.key === 'Tab' && currentTab) {
                const focusableElements = currentTab.querySelectorAll(
                    'input, select, textarea, button, [href], [tabindex]:not([tabindex="-1"])'
                );
                
                if (focusableElements.length > 0) {
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];
                    
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
            
            if (e.ctrlKey) {
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    nextTab();
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    prevTab();
                }
            }
        });
        
        document.querySelectorAll('.tab-btn').forEach((btn, index) => {
            btn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const tabName = this.dataset.tab;
                    loadTab(tabName);
                    setTimeout(() => switchTab(tabName), 50);
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    const nextBtn = document.querySelectorAll('.tab-btn')[index + 1];
                    if (nextBtn) nextBtn.focus();
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    const prevBtn = document.querySelectorAll('.tab-btn')[index - 1];
                    if (prevBtn) prevBtn.focus();
                }
            });
        });
    }
    
    // ============================================
    // AUTO-SAVE DRAFT
    // ============================================
    function initializeAutoSave() {
        const form = document.getElementById('employeeForm');
        if (!form) return;
        
        let saveTimeout;
        const saveDelay = 10000;
        
        function saveDraft() {
            const formData = new FormData(form);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                if (!(form.querySelector(`[name="${key}"]`)?.type === 'file')) {
                    data[key] = value;
                }
            }
            
            data['_current_tab'] = tabs[currentTabIndex];
            
            localStorage.setItem('employee_form_draft', JSON.stringify(data));
            console.log('Draft saved automatically');
        }
        
        form.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(saveDraft, saveDelay);
        });
        
        const draft = localStorage.getItem('employee_form_draft');
        if (draft) {
            try {
                const data = JSON.parse(draft);
                
                Object.keys(data).forEach(key => {
                    if (key !== '_current_tab') {
                        const field = form.querySelector(`[name="${key}"]`);
                        if (field && field.type !== 'file') {
                            field.value = data[key];
                        }
                    }
                });
                
                if (data['_current_tab'] && tabs.includes(data['_current_tab'])) {
                    setTimeout(() => {
                        loadTab(data['_current_tab']);
                        switchTab(data['_current_tab']);
                    }, 100);
                }
                
                console.log('Draft restored from localStorage');
            } catch(e) {
                console.error('Error loading draft:', e);
            }
        }
    }
    
    // ============================================
    // INITIALIZE BASIC TAB NAVIGATION
    // ============================================
    function initializeBasicTabNavigation() {
        const basicTab = document.getElementById('tab-basic');
        if (basicTab) {
            const nextButtons = basicTab.querySelectorAll('.next-tab');
            const prevButtons = basicTab.querySelectorAll('.prev-tab');
            
            nextButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    nextTab();
                });
            });
            
            prevButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    prevTab();
                });
            });
        }
    }
    
    // ============================================
    // MAIN INITIALIZATION
    // ============================================
    function init() {
        // Initialize tab navigation
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabName = this.dataset.tab;
                loadTab(tabName);
                setTimeout(() => switchTab(tabName), 50);
            });
        });
        
        // Initialize basic tab navigation
        initializeBasicTabNavigation();
        
        // Initialize keyboard navigation
        initializeKeyboardNavigation();
        
        // Initialize form validation
        initializeFormValidation();
        
        // Initialize auto-save
        initializeAutoSave();
        
        // Preload next tab
        setTimeout(() => {
            loadTab('employment');
        }, 1000);
        
        // Load last active tab
        const lastTab = localStorage.getItem('last_active_tab');
        if (lastTab && tabs.includes(lastTab) && lastTab !== 'basic') {
            loadTab(lastTab);
            setTimeout(() => switchTab(lastTab), 100);
        }
        
        // Update navigation buttons
        updateNavigationButtons();
        
        // Manually handle the main save button
        const mainSaveBtn = document.querySelector('#employeeForm button[type="submit"]');
        if (mainSaveBtn) {
            mainSaveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = document.getElementById('employeeForm');
                if (validateAllTabs()) {
                    const existingDraftInput = form.querySelector('input[name="save_as_draft"]');
                    if (existingDraftInput) {
                        existingDraftInput.remove();
                    }
                    
                    form.submit();
                }
            });
        }
    }
    
    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();
</script>

<!-- ============================================
DEFERRED CSS - Loads after content
============================================ -->
<style media="print" onload="this.media='all'">
/* Deferred CSS - Non-critical styles */

/* Textarea */
textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

/* File upload enhancements */
.file-upload {
    margin: 20px 0;
}

.upload-area {
    border: 3px dashed #ccc;
    border-radius: 8px;
    padding: 60px 40px;
    text-align: center;
    background: #f9f9f9;
    cursor: pointer;
    transition: all 0.3s;
}

.upload-area:hover {
    border-color: #3498db;
    background: #f0f8ff;
}

.upload-area.dragover {
    border-color: #2ecc71;
    background: #e8f8f0;
}

.upload-area i {
    font-size: 64px;
    color: #999;
    margin-bottom: 20px;
    opacity: 0.7;
}

.upload-area p {
    font-size: 18px;
    color: #555;
    margin: 0 0 12px 0;
    font-weight: 500;
}

.upload-area small {
    font-size: 14px;
    color: #888;
}

.file-upload input[type="file"] {
    display: none;
}

.upload-preview {
    display: none;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 25px;
    background: white;
    margin-top: 25px;
    animation: fadeIn 0.3s ease-out;
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #eee;
}

.preview-header span {
    font-weight: 600;
    color: #333;
    font-size: 16px;
}

.upload-preview img {
    display: block;
    max-width: 250px;
    max-height: 250px;
    border-radius: 8px;
    margin: 0 auto;
    border: 2px solid #ddd;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

/* Qualifications styling */
.qualification-entry {
    margin-bottom: 20px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from { transform: translateX(20px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.qualification-row {
    display: grid;
    grid-template-columns: 3fr 1fr auto;
    gap: 15px;
    align-items: center;
}

.qualification-name {
    min-width: 0;
}

.qualification-year {
    min-width: 140px;
}

.remove-qualification {
    padding: 12px 20px;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-height: 46px;
    flex-shrink: 0;
    background: #e74c3c !important;
    color: white !important;
    border: none !important;
    border-radius: 6px !important;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 500;
}

.remove-qualification:hover {
    background: #c0392b !important;
    transform: translateY(-1px);
}

/* Form text and validation */
.form-text {
    font-size: 13px;
    color: #666;
    margin-top: 6px;
    display: block;
    font-style: italic;
}

/* Error highlighting */
.error-highlight {
    border-color: #e74c3c !important;
    background-color: #fff5f5 !important;
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.3) !important;
    animation: pulseError 1s ease-in-out;
}

@keyframes pulseError {
    0% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(231, 76, 60, 0); }
    100% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0); }
}

.is-invalid {
    border-color: #e74c3c !important;
    background-color: #fff5f5 !important;
}

.btn-close {
    color: #999;
    cursor: pointer;
    transition: color 0.2s;
    background: none;
    border: none;
    font-size: 18px;
}

.btn-close:hover {
    color: #333;
}

/* Select dropdown styling */
select.form-control { 
    appearance: none; 
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-position: right 15px center;
    background-repeat: no-repeat;
    padding-right: 40px;
    cursor: pointer;
}

/* Progress indicator (optional) */
.tab-progress {
    height: 4px;
    background: #e0e0e0;
    margin: 0 40px 20px 40px;
    border-radius: 2px;
    overflow: hidden;
}

.tab-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #3498db, #2ecc71);
    width: 12.5%;
    transition: transform 0.3s;
    transform: translateX(0%);
}

/* Mobile adjustments */
@media (max-width: 768px) {
    .qualification-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .qualification-year {
        min-width: 100%;
    }
    
    .remove-qualification {
        width: 100%;
        justify-content: center;
    }
    
    .upload-area {
        padding: 40px 20px;
    }
    
    .upload-area i {
        font-size: 48px;
    }
    
    .upload-area p {
        font-size: 16px;
    }
    
    .tab-progress {
        margin: 0 20px 15px 20px;
    }
}

/* Print styles */
@media print {
    .tab-navigation, .tab-navigation-buttons, .file-upload, .form-actions {
        display: none !important;
    }
    
    .tab-content {
        display: block !important;
        page-break-inside: avoid;
        border: 1px solid #000 !important;
        margin-bottom: 20px !important;
    }
    
    .form-control {
        background: transparent !important;
        border: 1px solid #000 !important;
    }
    
    body {
        background: white !important;
    }
}
</style>

<!-- ============================================
NOSCRIPT FALLBACK - Basic form without tabs
============================================ -->
<noscript>
    <style>
        .tab-navigation, .tab-navigation-buttons, .skeleton-tab { 
            display: none !important; 
        }
        
        .tab-content {
            display: block !important;
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
            padding-bottom: 40px;
        }
        
        .tab-content:last-child {
            border-bottom: none;
        }
        
        template { 
            display: none !important; 
        }
    </style>
    
    <script>
        // For non-JS users, show all content
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('template').forEach(template => {
                const content = template.content.cloneNode(true);
                const targetId = template.id.replace('template-', 'tab-');
                const target = document.getElementById(targetId);
                if (target) {
                    target.innerHTML = '';
                    target.appendChild(content);
                }
            });
        });
    </script>
</noscript>
</body>
</html>