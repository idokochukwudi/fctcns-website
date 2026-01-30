<?php
// ============================================================================
// Bulk Upload View - Nominal Roll (Admin)
// ============================================================================

// Check user authorization
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /login');
    exit();
}

$page_title = "Bulk Upload - Nominal Roll";

// ==============================
// FIX: Get CSRF token properly
// ==============================
// Try to get from controller data first, then session, then generate new
$csrfToken = $this->data['csrfToken'] ?? 
            ($_SESSION['csrf_tokens'] ?? false ? array_key_last($_SESSION['csrf_tokens']) : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - FCTCNS</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" id="csrf-token-meta" content="<?php echo htmlspecialchars($csrfToken); ?>">
    
    <!-- Hidden input as backup -->
    <input type="hidden" id="csrf_token_hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .main-container {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .back-button {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease;
        }
        
        .back-button:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover:not(:disabled) {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
        }
        
        .btn-success {
            background-color: var(--success-color);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Upload Area Styles */
        .upload-area {
            border: 3px dashed #ddd;
            border-radius: 10px;
            padding: 40px 20px;
            text-align: center;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 20px;
        }
        
        .upload-area:hover {
            border-color: var(--secondary-color);
            background: #f8fbff;
        }
        
        .upload-area.active {
            border-color: var(--success-color);
            background: #f0fff4;
        }
        
        .upload-icon {
            font-size: 48px;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }
        
        .file-info {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            margin-top: 15px;
            text-align: left;
        }
        
        /* Validation Stats */
        .validation-stats {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .stat-card {
            flex: 1;
            min-width: 120px;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .stat-card.total { border-left: 4px solid #2196f3; }
        .stat-card.valid { border-left: 4px solid #4caf50; }
        .stat-card.errors { border-left: 4px solid #f44336; }
        .stat-card.duplicates { border-left: 4px solid #ff9800; }
        
        .stat-card h5 {
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        
        .stat-card p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }
        
        /* Progress Bar */
        .progress-container {
            margin: 20px 0;
        }
        
        .progress-bar {
            height: 10px;
            border-radius: 5px;
            background: #e9ecef;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary-color), var(--success-color));
            width: 0%;
            transition: width 0.5s ease;
        }
        
        .progress-text {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            font-size: 14px;
            color: #666;
        }
        
        /* Error List */
        .error-list {
            max-height: 300px;
            overflow-y: auto;
            margin-top: 20px;
        }
        
        .error-item {
            padding: 10px 15px;
            border-left: 4px solid #f44336;
            background: #ffebee;
            margin-bottom: 8px;
            border-radius: 0 8px 8px 0;
        }
        
        .error-item .row-info {
            font-weight: bold;
            color: #d32f2f;
        }
        
        .error-item .field-info {
            color: #666;
            font-size: 14px;
        }
        
        /* Column Groups */
        .column-group {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary-color);
        }
        
        .column-group h6 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .column-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 10px;
        }
        
        .column-item {
            background: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            border-left: 3px solid #dee2e6;
        }
        
        .required {
            border-left-color: var(--danger-color);
        }
        
        .optional {
            border-left-color: var(--warning-color);
        }
        
        @media (max-width: 768px) {
            .main-container {
                padding: 15px;
            }
            
            .validation-stats {
                flex-direction: column;
            }
            
            .stat-card {
                min-width: 100%;
            }
            
            .column-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Main Container -->
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1><i class="fas fa-users"></i> Bulk Nominal Roll Upload</h1>
                    <p class="mb-0">Upload multiple employee records at once using CSV format</p>
                </div>
                <a href="/admin/nominal-roll" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to Nominal Roll
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success_msg']) && $_SESSION['success_msg']): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success_msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_msg']) && $_SESSION['error_msg']): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error_msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>

        <!-- Upload Section -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-cloud-upload-alt"></i> Upload CSV File</h4>
                    </div>
                    <div class="card-body">
                        <!-- Upload Area -->
                        <div class="upload-area" id="uploadArea">
                            <div class="upload-icon">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            <h4>Drag & Drop your CSV file here</h4>
                            <p class="text-muted">or click to browse files</p>
                            <input type="file" class="d-none" id="csvFile" name="file" accept=".csv">
                            <div class="file-info d-none" id="fileInfo">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong id="fileName">No file selected</strong>
                                        <br>
                                        <small id="fileSize">0 KB</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger" id="removeFile">
                                        <i class="fas fa-times"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Validation Stats (Hidden by default) -->
                        <div class="validation-stats d-none" id="validationStats">
                            <div class="stat-card total">
                                <h5 id="statTotal">0</h5>
                                <p>Total Records</p>
                            </div>
                            <div class="stat-card valid">
                                <h5 id="statValid">0</h5>
                                <p>Valid Records</p>
                            </div>
                            <div class="stat-card errors">
                                <h5 id="statErrors">0</h5>
                                <p>Errors Found</p>
                            </div>
                            <div class="stat-card duplicates">
                                <h5 id="statDuplicates">0</h5>
                                <p>Duplicates</p>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress-container d-none" id="progressContainer">
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressFill"></div>
                            </div>
                            <div class="progress-text">
                                <span id="progressText">Validating...</span>
                                <span id="progressPercent">0%</span>
                            </div>
                        </div>

                        <!-- Error List -->
                        <div class="error-list d-none" id="errorList">
                            <!-- Errors will be populated here -->
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-warning" id="validateBtn" disabled>
                                <i class="fas fa-search"></i> Validate File
                            </button>
                            <button type="button" class="btn btn-primary" id="uploadBtn" disabled>
                                <i class="fas fa-upload"></i> Confirm Upload
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CSV Format Info -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4><i class="fas fa-info-circle"></i> CSV Format Requirements</h4>
                    </div>
                    <div class="card-body">
                        <p>Your CSV file must include the following columns in this exact order:</p>
                        
                        <!-- Required Columns -->
                        <div class="column-group">
                            <h6><i class="fas fa-asterisk text-danger"></i> Required Fields</h6>
                            <div class="column-list">
                                <div class="column-item required">
                                    <code>employee_number</code> - Employee Number (e.g., EMP20260001)
                                </div>
                                <div class="column-item required">
                                    <code>surname</code> - Surname
                                </div>
                                <div class="column-item required">
                                    <code>first_name</code> - First Name
                                </div>
                                <div class="column-item optional">
                                    <code>middle_name</code> - Middle Name
                                </div>
                                <div class="column-item required">
                                    <code>sex</code> - Sex (Male/Female)
                                </div>
                                <div class="column-item required">
                                    <code>date_of_birth</code> - Date of Birth (YYYY-MM-DD)
                                </div>
                                <div class="column-item required">
                                    <code>marital_status</code> - Marital Status
                                </div>
                                <div class="column-item required">
                                    <code>rank</code> - Rank/Position
                                </div>
                                <div class="column-item required">
                                    <code>department</code> - Department
                                </div>
                                <div class="column-item required">
                                    <code>email</code> - Email Address
                                </div>
                                <div class="column-item required">
                                    <code>telephone_number</code> - Telephone Number
                                </div>
                            </div>
                        </div>

                        <!-- Employment Details -->
                        <div class="column-group">
                            <h6><i class="fas fa-briefcase text-info"></i> Employment Details</h6>
                            <div class="column-list">
                                <div class="column-item optional">
                                    <code>grade_level</code> - Grade Level (GL)
                                </div>
                                <div class="column-item optional">
                                    <code>step</code> - Step
                                </div>
                                <div class="column-item optional">
                                    <code>cadre</code> - Cadre
                                </div>
                                <div class="column-item optional">
                                    <code>staff_type</code> - Staff Type (Academic/Non-Academic)
                                </div>
                                <div class="column-item optional">
                                    <code>employment_type</code> - Employment Type (Permanent/Contract)
                                </div>
                                <div class="column-item optional">
                                    <code>appointment_type</code> - Appointment Type (Confirmed/Acting)
                                </div>
                                <div class="column-item optional">
                                    <code>date_of_first_appointment</code> - Date of First Appointment
                                </div>
                                <div class="column-item optional">
                                    <code>date_of_confirmation</code> - Date of Confirmation
                                </div>
                                <div class="column-item optional">
                                    <code>rank_on_first_appointment</code> - Rank on First Appointment
                                </div>
                                <div class="column-item optional">
                                    <code>date_of_present_appointment</code> - Date of Present Appointment
                                </div>
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="column-group">
                            <h6><i class="fas fa-user text-primary"></i> Personal Details</h6>
                            <div class="column-list">
                                <div class="column-item optional">
                                    <code>nationality</code> - Nationality
                                </div>
                                <div class="column-item optional">
                                    <code>religion</code> - Religion
                                </div>
                                <div class="column-item optional">
                                    <code>blood_group</code> - Blood Group
                                </div>
                                <div class="column-item optional">
                                    <code>genotype</code> - Genotype
                                </div>
                                <div class="column-item optional">
                                    <code>state</code> - State of Origin
                                </div>
                                <div class="column-item optional">
                                    <code>local_govt_area</code> - Local Government Area
                                </div>
                                <div class="column-item optional">
                                    <code>state_of_residence</code> - State of Residence
                                </div>
                                <div class="column-item optional">
                                    <code>residential_address</code> - Residential Address
                                </div>
                                <div class="column-item optional">
                                    <code>contact_address</code> - Contact Address
                                </div>
                            </div>
                        </div>

                        <!-- Financial Details -->
                        <div class="column-group">
                            <h6><i class="fas fa-money-bill text-success"></i> Financial Details</h6>
                            <div class="column-list">
                                <div class="column-item optional">
                                    <code>bank_name</code> - Bank Name
                                </div>
                                <div class="column-item optional">
                                    <code>bank_branch</code> - Bank Branch
                                </div>
                                <div class="column-item optional">
                                    <code>account_number</code> - Account Number
                                </div>
                                <div class="column-item optional">
                                    <code>account_name</code> - Account Name
                                </div>
                                <div class="column-item optional">
                                    <code>pf_number</code> - PF Number
                                </div>
                                <div class="column-item optional">
                                    <code>nhf_number</code> - NHF Number
                                </div>
                                <div class="column-item optional">
                                    <code>pension_fund_admin</code> - Pension Fund Admin
                                </div>
                                <div class="column-item optional">
                                    <code>pension_number</code> - Pension Number
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="column-group">
                            <h6><i class="fas fa-phone-alt text-warning"></i> Emergency & Next of Kin</h6>
                            <div class="column-list">
                                <div class="column-item optional">
                                    <code>emergency_contact_name</code> - Emergency Contact Name
                                </div>
                                <div class="column-item optional">
                                    <code>emergency_contact_phone</code> - Emergency Contact Phone
                                </div>
                                <div class="column-item optional">
                                    <code>emergency_contact_relationship</code> - Relationship
                                </div>
                                <div class="column-item optional">
                                    <code>next_of_kin_name</code> - Next of Kin Name
                                </div>
                                <div class="column-item optional">
                                    <code>next_of_kin_phone</code> - Next of Kin Phone
                                </div>
                                <div class="column-item optional">
                                    <code>next_of_kin_address</code> - Next of Kin Address
                                </div>
                                <div class="column-item optional">
                                    <code>next_of_kin_relationship</code> - Relationship
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-lightbulb"></i> <strong>Important Notes:</strong>
                            <ul class="mb-0 mt-2">
                                <li>First row must be column headers exactly as shown above</li>
                                <li>Save file as UTF-8 encoded CSV</li>
                                <li>Dates must be in YYYY-MM-DD format (e.g., 1995-12-24)</li>
                                <li>JSON arrays for additional_qualifications should be valid JSON</li>
                                <li>Employee numbers must be unique</li>
                                <li>Text fields containing commas should be enclosed in double quotes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <!-- Download Template -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4><i class="fas fa-download"></i> Download Template</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-file-csv fa-4x text-success mb-3"></i>
                            <h5>CSV Template File</h5>
                            <p class="text-muted">Use our pre-formatted template with sample data</p>
                        </div>
                        
                        <button type="button" class="btn btn-success btn-lg w-100 mb-3" id="downloadTemplateBtn">
                            <i class="fas fa-download"></i> Download Template
                        </button>
                        
                        <div class="alert alert-light">
                            <h6><i class="fas fa-lightbulb"></i> Quick Start Guide:</h6>
                            <ol class="mb-0 ps-3">
                                <li>Download the template</li>
                                <li>Fill in your data</li>
                                <li>Save as CSV file</li>
                                <li>Upload and validate</li>
                                <li>Confirm upload</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Upload Guidelines -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-clipboard-check"></i> Validation Rules</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Required Fields:</strong> Employee Number, Surname, First Name
                            </div>
                            <div class="list-group-item">
                                <i class="fas fa-calendar-alt text-info me-2"></i>
                                <strong>Date Format:</strong> Must be YYYY-MM-DD
                            </div>
                            <div class="list-group-item">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                <strong>Duplicate Check:</strong> Employee numbers must be unique
                            </div>
                            <div class="list-group-item">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <strong>Email Format:</strong> Must be valid email address
                            </div>
                            <div class="list-group-item">
                                <i class="fas fa-phone text-secondary me-2"></i>
                                <strong>Phone Numbers:</strong> Must be valid Nigerian format
                            </div>
                            <div class="list-group-item">
                                <i class="fas fa-venus-mars text-danger me-2"></i>
                                <strong>Gender:</strong> Must be "Male" or "Female"
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-bar"></i> Current Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-users fa-3x text-primary mb-2"></i>
                            <h5>Total Employees</h5>
                            <h3 class="text-primary">
                                <?php 
                                // You'll need to get this count from your controller
                                // For now, display a placeholder
                                echo "Loading...";
                                ?>
                            </h3>
                        </div>
                        <small class="text-muted">Last upload: 
                            <?php 
                            // Get last upload date
                            echo date('Y-m-d');
                            ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("=== BULK UPLOAD INIT ===");
    
    // Elements
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('csvFile');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFileBtn = document.getElementById('removeFile');
    const validateBtn = document.getElementById('validateBtn');
    const uploadBtn = document.getElementById('uploadBtn');
    const validationStats = document.getElementById('validationStats');
    const errorList = document.getElementById('errorList');
    
    let currentFile = null;
    let validationResult = null;
    
    // ======================
    // UPLOAD HANDLER
    // ======================
    function handleUploadClick() {
        console.log("Upload button clicked");
        
        // Basic validation
        if (!currentFile) {
            alert('Please select a file first.');
            return;
        }
        
        if (!validationResult || validationResult.error_count > 0 || validationResult.duplicate_count > 0) {
            alert('Please validate the file and fix all errors and duplicates first.');
            return;
        }
        
        // Get CSRF token
        const csrfToken = document.querySelector('#csrf_token_hidden')?.value || 
                         document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            alert('Security token missing. Please refresh the page.');
            return;
        }
        
        // Confirm
        if (!confirm(`Upload ${validationResult.valid_records} records?`)) {
            return;
        }
        
        // Prepare form data
        const formData = new FormData();
        formData.append('file', currentFile);
        formData.append('csrf_token', csrfToken);
        
        // Update UI
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        
        // Send request
        fetch('/admin/nominal-roll/bulk-upload-process', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Upload response:", data);
            if (data.success) {
                alert('Upload successful!');
                window.location.href = '/admin/nominal-roll?upload_success=true';
            } else {
                alert('Upload failed: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error("Upload error:", error);
            alert('Upload error: ' + error.message);
        })
        .finally(() => {
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Confirm Upload';
        });
    }
    
    // ======================
    // FILE HANDLING
    // ======================
    function handleFile(file) {
        if (!file.name.toLowerCase().endsWith('.csv')) {
            alert('Please select a CSV file.');
            return;
        }
        
        if (file.size > 10 * 1024 * 1024) {
            alert('File size exceeds 10MB limit.');
            return;
        }
        
        currentFile = file;
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('d-none');
        uploadArea.classList.add('active');
        validateBtn.disabled = false;
        uploadBtn.disabled = true;
        
        // Clear previous validation
        clearValidation();
    }
    
    function clearFile() {
        currentFile = null;
        fileInput.value = '';
        fileInfo.classList.add('d-none');
        uploadArea.classList.remove('active');
        validateBtn.disabled = true;
        uploadBtn.disabled = true;
        clearValidation();
    }
    
    // ======================
    // VALIDATION FUNCTION
    // ======================
    function validateFile() {
        if (!currentFile) {
            alert('Please select a file first.');
            return;
        }
        
        // Get CSRF token
        const csrfToken = document.querySelector('#csrf_token_hidden')?.value || 
                         document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            alert('Security token missing. Please refresh the page.');
            return;
        }
        
        const formData = new FormData();
        formData.append('file', currentFile);
        formData.append('csrf_token', csrfToken);
        formData.append('validate_duplicates', 'true');
        
        // Update UI
        validateBtn.disabled = true;
        validateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Validating...';
        
        fetch('/admin/nominal-roll/validate-bulk-upload', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Validation response:", data);
            
            if (data.success) {
                validationResult = data;
                
                // Update stats
                document.getElementById('statTotal').textContent = data.total_records || 0;
                document.getElementById('statValid').textContent = data.valid_records || 0;
                document.getElementById('statErrors').textContent = data.error_count || 0;
                document.getElementById('statDuplicates').textContent = data.duplicate_count || 0;
                
                // Show stats
                document.getElementById('validationStats').classList.remove('d-none');
                
                // Show errors if any
                if (data.errors && data.errors.length > 0) {
                    errorList.innerHTML = '';
                    data.errors.forEach(error => {
                        errorList.innerHTML += `
                            <div class="error-item">
                                <div class="row-info">Row ${error.row}: ${error.message}</div>
                                <div class="field-info">Field: ${error.field}, Value: ${error.value}</div>
                            </div>
                        `;
                    });
                    errorList.classList.remove('d-none');
                } else {
                    errorList.classList.add('d-none');
                }
                
                // Enable upload if no errors and no duplicates
                if (data.error_count === 0 && data.duplicate_count === 0) {
                    uploadBtn.disabled = false;
                    uploadBtn.classList.remove('btn-warning');
                    uploadBtn.classList.add('btn-success');
                    alert('Validation complete: ' + data.valid_records + ' valid records found.');
                } else {
                    uploadBtn.disabled = true;
                    alert('Validation complete with ' + data.error_count + ' errors and ' + data.duplicate_count + ' duplicates.');
                }
            } else {
                alert('Validation failed: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error("Validation error:", error);
            alert('Validation error: ' + error.message);
        })
        .finally(() => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="fas fa-search"></i> Validate File';
        });
    }
    
    function clearValidation() {
        validationResult = null;
        document.getElementById('validationStats').classList.add('d-none');
        errorList.classList.add('d-none');
        uploadBtn.disabled = true;
        uploadBtn.classList.remove('btn-success');
        uploadBtn.classList.add('btn-primary');
    }
    
    // ======================
    // EVENT LISTENERS
    // ======================
    uploadArea.addEventListener('click', function(e) {
        if (e.target !== removeFileBtn && !removeFileBtn.contains(e.target)) {
            fileInput.click();
        }
    });
    
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFile(this.files[0]);
        }
    });
    
    removeFileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        clearFile();
    });
    
    validateBtn.addEventListener('click', validateFile);
    uploadBtn.addEventListener('click', handleUploadClick);
    
    // Template download handler
    document.getElementById('downloadTemplateBtn').addEventListener('click', function() {
        // Complete template with ALL columns in the exact order
        const headers = 'employee_number,surname,first_name,middle_name,sex,date_of_birth,marital_status,rank,grade_level,step,cadre,staff_type,employment_type,appointment_type,date_of_first_appointment,date_of_confirmation,rank_on_first_appointment,date_of_present_appointment,department,email,telephone_number,nationality,religion,blood_group,genotype,state,local_govt_area,state_of_residence,residential_address,contact_address,bank_name,bank_branch,account_number,account_name,pf_number,nhf_number,pension_fund_admin,pension_number,emergency_contact_name,emergency_contact_phone,emergency_contact_relationship,next_of_kin_name,next_of_kin_phone,next_of_kin_address,next_of_kin_relationship';
        
        // Sample data rows
        const row1 = 'EMP20260001,Doe,John,Michael,Male,1990-05-15,Married,Senior Lecturer,15,5,Academic,Academic,Permanent,Confirmed,2015-06-01,2017-06-01,Lecturer I,2023-01-15,Anatomy,john.doe@example.com,08012345678,Nigerian,Christian,O+,AA,FCT,Gwagwalada,FCT,123 Main Street Gwagwalada,Same,First Bank,Gwagwalada,1234567890,John Doe,PF00123,NHF00123,PENCOM,PEN123456,James Doe,08012345678,Brother,Mary Doe,08023456789,456 Family Street Abuja,Wife';
        
        const row2 = 'EMP20260002,Smith,Jane,,Female,1985-08-22,Single,Manager,14,4,Non-Academic,Non-Academic,Permanent,Acting,2018-03-15,,Manager,2022-08-20,HR,jane.smith@example.com,08023456789,Nigerian,Christian,O+,AS,Lagos,Ikeja,Lagos,456 Oak Avenue Ikeja,Same,Zenith Bank,Ikeja,0987654321,Jane Smith,PF00234,NHF00234,PENCOM,PEN234567,Peter Smith,08023456789,Father,Robert Smith,08034567890,789 Kin Street Lagos,Father';
        
        const row3 = 'EMP20260003,Johnson,Robert,James,Male,1978-12-10,Married,Professor,16,7,Academic,Academic,Permanent,Confirmed,2005-09-01,2007-09-01,Lecturer II,2021-07-10,Nursing,robert.j@example.com,08034567890,Nigerian,Christian,B+,AS,Rivers,Port-Harcourt,Rivers,789 River Road Port Harcourt,Same,UBA,Port-Harcourt,5678901234,Robert Johnson,PF00345,NHF00345,PENCOM,PEN345678,Sarah Johnson,08034567890,Wife,David Johnson,08045678901,123 Next Street Port Harcourt,Son';
        
        const templateContent = headers + '\n' + row1 + '\n' + row2 + '\n' + row3;
        const blob = new Blob([templateContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', 'nominal_roll_complete_template.csv');
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        alert('Complete template downloaded successfully! The file includes all 44 columns with sample data.');
    });
    
    // ======================
    // HELPER FUNCTIONS
    // ======================
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    console.log("=== BULK UPLOAD INIT COMPLETE ===");
});
    </script>
</body>
</html>