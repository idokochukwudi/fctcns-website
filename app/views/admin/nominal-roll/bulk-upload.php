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
    console.log("[INIT] Starting bulk upload script...");
    
    var uploadArea = document.getElementById('uploadArea');
    var fileInput = document.getElementById('csvFile');
    var fileInfo = document.getElementById('fileInfo');
    var fileName = document.getElementById('fileName');
    var fileSize = document.getElementById('fileSize');
    var removeFileBtn = document.getElementById('removeFile');
    var validateBtn = document.getElementById('validateBtn');
    var uploadBtn = document.getElementById('uploadBtn');
    var validationStats = document.getElementById('validationStats');
    var progressContainer = document.getElementById('progressContainer');
    var progressFill = document.getElementById('progressFill');
    var progressText = document.getElementById('progressText');
    var progressPercent = document.getElementById('progressPercent');
    var errorList = document.getElementById('errorList');
    var downloadTemplateBtn = document.getElementById('downloadTemplateBtn');
    
    var statTotal = document.getElementById('statTotal');
    var statValid = document.getElementById('statValid');
    var statErrors = document.getElementById('statErrors');
    var statDuplicates = document.getElementById('statDuplicates');
    
    var currentFile = null;
    var validationResult = null;
    
    // Debug: Confirm elements exist
    console.log("[INIT] uploadBtn found?", !!uploadBtn);
    console.log("[INIT] validateBtn found?", !!validateBtn);
    
    // ====================== DEBUG: Test CSRF Token ======================
    function testCsrfToken() {
        console.log("=== CSRF TOKEN DIAGNOSTICS ===");
        
        // Check meta tag
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        console.log("Meta tag exists:", !!metaToken);
        console.log("Meta token content:", metaToken?.content || "EMPTY");
        console.log("Meta token content length:", metaToken?.content?.length || 0);
        
        // Check hidden inputs
        const hiddenInputs = document.querySelectorAll('input[type="hidden"]');
        console.log("Total hidden inputs:", hiddenInputs.length);
        
        hiddenInputs.forEach((input, index) => {
            console.log(`Hidden input ${index + 1}:`, {
                name: input.name,
                id: input.id,
                valueLength: input.value.length,
                valuePreview: input.value.substring(0, 20) + '...'
            });
        });
        
        // Check session data from PHP
        console.log("PHP Session check (if embedded):");
        try {
            // Check if any PHP session data is embedded
            const bodyHTML = document.body.innerHTML;
            if (bodyHTML.includes('csrf') || bodyHTML.includes('CSRF')) {
                console.log("Found 'csrf' in page HTML");
            }
        } catch(e) {
            console.log("Could not search HTML:", e.message);
        }
        
        console.log("=== END DIAGNOSTICS ===");
    }
    
    // Call diagnostics
    setTimeout(testCsrfToken, 1000);
    // ====================== END DEBUG ======================
    
    // ====================== FIX 2: Safe event listener attachment ======================
    // Safe event listener attachment with retry
    function attachUploadListener() {
        if (!uploadBtn) {
            console.error("[ATTACH FAIL] uploadBtn element still not found!");
            return;
        }

        // Remove any old listeners to prevent duplicates
        uploadBtn.removeEventListener('click', handleUploadClick);
        
        // Attach fresh listener
        uploadBtn.addEventListener('click', handleUploadClick);
        console.log("[ATTACH SUCCESS] Upload listener attached to button");
    }

    function handleUploadClick() {
        console.log("╔════════════════════════════════════╗");
        console.log("║ CONFIRM UPLOAD CLICKED ║");
        console.log("╚════════════════════════════════════╝");
        console.log("Time:", new Date().toISOString());
        console.log("Button disabled?", uploadBtn.disabled);
        console.log("currentFile:", currentFile ? currentFile.name : "MISSING");
        console.log("validationResult:", validationResult ? "EXISTS" : "MISSING");
        
        if (uploadBtn.disabled) {
            console.warn("BLOCKED: Button is still disabled!");
            showAlert('Upload button is disabled. Please validate first.', 'warning');
            return;
        }

        if (!currentFile) {
            console.error("BLOCKED: No file");
            showAlert('No file selected.', 'warning');
            return;
        }

        if (!validationResult) {
            console.error("BLOCKED: No validationResult");
            showAlert('Please validate the file again.', 'danger');
            return;
        }

        if (validationResult.error_count > 0) {
            console.warn("BLOCKED: Errors remain");
            showAlert('Fix errors first.', 'danger');
            return;
        }

        console.log("→ Proceeding to upload...");
        
        // Confirm dialog
        var confirmMessage = `Upload ${validationResult.valid_records} records?`;
        if (!confirm(confirmMessage)) {
            console.log("User cancelled upload");
            return;
        }

        console.log("→ User confirmed. Building request...");
        // ==============================
        // FIX: Get CSRF token with multiple fallbacks
        // ==============================
        function getCsrfToken() {
            // Try meta tag first
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (tokenMeta && tokenMeta.content) {
                console.log("Got CSRF from meta tag");
                return tokenMeta.content;
            }
            
            // Try hidden input
            const tokenInput = document.querySelector('input[name="csrf_token"]');
            if (tokenInput && tokenInput.value) {
                console.log("Got CSRF from input[name='csrf_token']");
                return tokenInput.value;
            }
            
            // Try by ID
            const tokenById = document.querySelector('#csrf_token_hidden');
            if (tokenById && tokenById.value) {
                console.log("Got CSRF from #csrf_token_hidden");
                return tokenById.value;
            }
            
            // Try any input with csrf
            const anyToken = document.querySelector('input[type="hidden"][name*="csrf"], input[type="hidden"][id*="csrf"]');
            if (anyToken && anyToken.value) {
                console.log("Got CSRF from any csrf input");
                return anyToken.value;
            }
            
            console.error("No CSRF token found!");
            return '';
        }
        
        const csrfToken = getCsrfToken();
        console.log("CSRF Token length:", csrfToken.length);
        console.log("CSRF Token first 20 chars:", csrfToken.substring(0, 20));
        
        if (!csrfToken) {
            showAlert('Security token missing. Please refresh the page.', 'danger');
            return;
        }

        var formData = new FormData();
        formData.append('file', currentFile);  // consistent with validate
        formData.append('csrf_token', csrfToken);

        if (csrfToken) {
            console.log("→ CSRF added");
        } else {
            console.error("→ CSRF MISSING!");
        }

        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';

        console.log("→ Sending fetch to /admin/nominal-roll/bulk-upload-process");

        // ==============================
        // MAIN FIX: ADD DEBUG CHECK FOR SERVER RESPONSE
        // ==============================
        fetch('/admin/nominal-roll/bulk-upload-process', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log("→ Server responded - Status:", response.status);
            console.log("→ Response headers:", Object.fromEntries(response.headers.entries()));
            
            if (!response.ok) {
                console.error("→ Server returned error status:", response.status);
                throw new Error(`Server error ${response.status}`);
            }
            
            // DEBUG: Get raw text to see what server actually returned
            return response.text().then(text => {
                console.log("→ RAW SERVER RESPONSE (first 500 chars):", text.substring(0, 500));
                
                // Check if it's HTML
                if (text.includes('<!DOCTYPE') || text.includes('<html') || text.includes('<!doctype')) {
                    console.error("❌ SERVER RETURNED HTML INSTEAD OF JSON!");
                    
                    // Try to extract any error message from HTML
                    if (text.includes('Fatal error') || text.includes('Parse error')) {
                        throw new Error('PHP error on server. Check server logs.');
                    }
                    
                    if (text.includes('Admin Login') || text.includes('login')) {
                        throw new Error('Session expired. Please refresh and login again.');
                    }
                    
                    if (text.includes('Access denied') || text.includes('Permission denied')) {
                        throw new Error('Access denied. You do not have permission.');
                    }
                    
                    throw new Error('Server returned HTML instead of JSON. Check server error logs.');
                }
                
                // Check if it's empty
                if (text.trim() === '') {
                    console.error("❌ SERVER RETURNED EMPTY RESPONSE!");
                    throw new Error('Server returned empty response. Check server logs.');
                }
                
                // Try to parse as JSON
                try {
                    const data = JSON.parse(text);
                    console.log("✅ JSON parse successful:", data);
                    return data;
                } catch (e) {
                    console.error("❌ JSON PARSE FAILED. Full raw response:");
                    console.error("--- START RAW RESPONSE ---");
                    console.error(text);
                    console.error("--- END RAW RESPONSE ---");
                    throw new Error('Invalid JSON from server: ' + text.substring(0, 200));
                }
            });
        })
        .then(data => {
            console.log("→ Server JSON parsed successfully:", data);
            if (data.success) {
                showAlert('Upload successful! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = window.location.origin + '/admin/nominal-roll?upload_success=true&limit=5&page=1';
                }, 2000);
            } else {
                console.error("→ Server reported failure:", data.error);
                showAlert('Upload failed: ' + (data.error || 'Unknown error'), 'danger');
            }
        })
        .catch(err => {
            console.error("→ Upload fetch failed:", err);
            showAlert('Upload error: ' + err.message, 'danger');
        })
        .finally(() => {
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Confirm Upload';
            console.log("→ Upload process finished (success or fail)");
        });
    }

    // Attach listener immediately + retry after 1 second (handles late DOM)
    attachUploadListener();
    setTimeout(attachUploadListener, 1000);
    // ====================== END FIX 2 ======================
    
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
    
    function handleFile(file) {
        console.log("[HANDLE FILE] Selected:", file.name);
        if (!file.name.toLowerCase().endsWith('.csv')) {
            showAlert('Please select a CSV file.', 'warning');
            return;
        }
        
        if (file.size > 10 * 1024 * 1024) {
            showAlert('File size exceeds 10MB limit.', 'warning');
            return;
        }

        currentFile = file;
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('d-none');
        uploadArea.classList.add('active');
        validateBtn.disabled = false;
        uploadBtn.disabled = true;
        clearValidation();

        setTimeout(function() {
            validateFile();
        }, 500);
    }
    
    removeFileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        clearFile();
    });
    
    function clearFile() {
        currentFile = null;
        fileInput.value = '';
        fileInfo.classList.add('d-none');
        uploadArea.classList.remove('active');
        validateBtn.disabled = true;
        uploadBtn.disabled = true;
        clearValidation();
    }
    
    validateBtn.addEventListener('click', validateFile);
    
    function validateFile() {
        console.log("[VALIDATE CLICK] Starting validation...");
        
        if (!currentFile) {
            showAlert('Please select a file first.', 'warning');
            return;
        }

        // ==============================
        // FIX: Get CSRF token with multiple fallbacks
        // ==============================
        function getCsrfToken() {
            // Try meta tag first
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (tokenMeta && tokenMeta.content) {
                console.log("Got CSRF from meta tag");
                return tokenMeta.content;
            }
            
            // Try hidden input
            const tokenInput = document.querySelector('input[name="csrf_token"]');
            if (tokenInput && tokenInput.value) {
                console.log("Got CSRF from input[name='csrf_token']");
                return tokenInput.value;
            }
            
            // Try by ID
            const tokenById = document.querySelector('#csrf_token_hidden');
            if (tokenById && tokenById.value) {
                console.log("Got CSRF from #csrf_token_hidden");
                return tokenById.value;
            }
            
            // Try any input with csrf
            const anyToken = document.querySelector('input[type="hidden"][name*="csrf"], input[type="hidden"][id*="csrf"]');
            if (anyToken && anyToken.value) {
                console.log("Got CSRF from any csrf input");
                return anyToken.value;
            }
            
            console.error("No CSRF token found!");
            return '';
        }
        
        const csrfToken = getCsrfToken();
        console.log("CSRF Token length:", csrfToken.length);
        console.log("CSRF Token first 20 chars:", csrfToken.substring(0, 20));
        
        if (!csrfToken) {
            showAlert('Security token missing. Please refresh the page.', 'danger');
            return;
        }

        validationStats.classList.remove('d-none');
        progressContainer.classList.remove('d-none');
        errorList.classList.add('d-none');
        updateProgress(10, 'Checking file format...');

        var formData = new FormData();
        formData.append('file', currentFile);
        formData.append('csrf_token', csrfToken);

        console.log("Sending validation request with CSRF token...");

        var progressInterval = setInterval(function() {
            var current = parseInt(progressFill.style.width) || 10;
            if (current < 70) updateProgress(current + 10, 'Analyzing data...');
        }, 500);

        fetch('/admin/nominal-roll/validate-bulk-upload', {
            method: 'POST',
            body: formData,
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest' // Add this header
            }
        })
        .then(response => {
            clearInterval(progressInterval);
            console.log('[VALIDATE] Status:', response.status);
            console.log('[VALIDATE] Content-Type:', response.headers.get('content-type'));
            
            return response.text().then(text => {
                console.log('[VALIDATE] Raw response (first 500 chars):', text.substring(0, 500));
                
                // Check if response is HTML (error page)
                if (text.includes('<!DOCTYPE') || text.includes('<html') || text.includes('<!doctype')) {
                    console.error('❌ SERVER RETURNED HTML INSTEAD OF JSON!');
                    
                    if (text.includes('Admin Login') || text.includes('login')) {
                        throw new Error('Session expired. Please refresh and login again.');
                    }
                    
                    if (text.includes('Access denied') || text.includes('Permission denied')) {
                        throw new Error('Access denied. You do not have permission.');
                    }
                    
                    throw new Error('Server returned HTML instead of JSON. Check server logs.');
                }
                
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                
                return JSON.parse(text);
            });
        })
        .then(data => {
            console.log('[VALIDATE] Parsed data:', data);
            
            if (data.success) {
                validationResult = data;
                updateProgress(100, 'Validation complete!');

                statTotal.textContent = data.total_records || 0;
                statValid.textContent = data.valid_records || 0;
                statErrors.textContent = data.error_count || 0;
                statDuplicates.textContent = data.duplicate_count || 0;

                if (data.errors?.length > 0) showErrors(data.errors);

                // ====================== FIX 1: Force re-check and enable button ======================
                if (data.error_count === 0) {
                    console.log("[VALIDATE SUCCESS] Enabling upload button - attempt 1");
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Confirm Upload';
                    uploadBtn.classList.remove('btn-warning');
                    uploadBtn.classList.add('btn-success');

                    // Extra force-enable (timing fix)
                    setTimeout(() => {
                        if (uploadBtn) {
                            uploadBtn.disabled = false;
                            console.log("[VALIDATE FORCE] Button now enabled after delay");
                            uploadBtn.style.pointerEvents = 'auto'; // extra CSS fix
                            uploadBtn.style.opacity = '1';
                        }
                    }, 300);
                } else {
                    uploadBtn.disabled = true;
                    uploadBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Fix Errors First';
                    uploadBtn.classList.remove('btn-success');
                    uploadBtn.classList.add('btn-warning');
                }
                // ====================== END FIX 1 ======================

                // ====================== FIX 3: Final safety net ======================
                // Final safety net: re-attach listener after validation
                setTimeout(attachUploadListener, 500);
                // ====================== END FIX 3 ======================
            } else {
                updateProgress(0, 'Validation failed');
                showAlert(data.error || 'Validation failed.', 'danger');
            }
        })
        .catch(error => {
            clearInterval(progressInterval);
            console.error('[VALIDATE ERROR]', error);
            updateProgress(0, 'Validation error');
            showAlert('Error validating file: ' + error.message, 'danger');
        });
    }
    
    function updateProgress(percent, text) {
        progressFill.style.width = percent + '%';
        progressText.textContent = text;
        progressPercent.textContent = percent + '%';
    }
    
    function showErrors(errors) {
        errorList.classList.remove('d-none');
        errorList.innerHTML = '';
        
        var displayErrors = errors.slice(0, 10);
        for (var i = 0; i < displayErrors.length; i++) {
            var error = displayErrors[i];
            var errorDiv = document.createElement('div');
            errorDiv.className = 'error-item';
            
            var rowInfo = document.createElement('div');
            rowInfo.className = 'row-info';
            rowInfo.textContent = 'Row ' + error.row + ': ' + error.message;
            
            var fieldInfo = document.createElement('div');
            fieldInfo.className = 'field-info';
            fieldInfo.textContent = 'Field: ' + error.field + ' | Value: "' + (error.value || 'N/A') + '"';
            
            errorDiv.appendChild(rowInfo);
            errorDiv.appendChild(fieldInfo);
            errorList.appendChild(errorDiv);
        }
        
        if (errors.length > 10) {
            var moreDiv = document.createElement('div');
            moreDiv.className = 'text-center mt-2';
            var moreText = document.createElement('small');
            moreText.className = 'text-muted';
            moreText.textContent = '... and ' + (errors.length - 10) + ' more errors';
            moreDiv.appendChild(moreText);
            errorList.appendChild(moreDiv);
        }
    }
    
    function clearValidation() {
        validationResult = null;
        validationStats.classList.add('d-none');
        progressContainer.classList.add('d-none');
        errorList.classList.add('d-none');
        errorList.innerHTML = '';
        
        statTotal.textContent = '0';
        statValid.textContent = '0';
        statErrors.textContent = '0';
        statDuplicates.textContent = '0';
        
        progressFill.style.width = '0%';
        progressText.textContent = 'Validating...';
        progressPercent.textContent = '0%';
        
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Confirm Upload';
        uploadBtn.classList.remove('btn-success', 'btn-warning');
        uploadBtn.classList.add('btn-primary');
    }
    
    downloadTemplateBtn.addEventListener('click', function() {
        var headers = 'employee_number,surname,first_name,middle_name,sex,date_of_birth,marital_status,rank,grade_level,department,email,telephone_number,state,local_govt_area';
        var row1 = 'EMP20260001,Doe,John,Michael,Male,1990-05-15,Married,Senior Lecturer,15,Anatomy,john.doe@example.com,08012345678,FCT,Gwagwalada';
        var row2 = 'EMP20260002,Smith,Jane,,Female,1985-08-22,Single,Manager,14,HR,jane.smith@example.com,08023456789,Lagos,Ikeja';
        var row3 = 'EMP20260003,Johnson,Robert,James,Male,1978-12-10,Married,Professor,16,Nursing,robert.j@example.com,08034567890,Rivers,Port-Harcourt';
        
        var templateContent = headers + '\n' + row1 + '\n' + row2 + '\n' + row3;
        var blob = new Blob([templateContent], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement('a');
        var url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', 'nominal_roll_template.csv');
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showAlert('Template downloaded successfully!', 'success');
    });
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function showAlert(message, type) {
        type = type || 'info';
        
        var existingAlerts = document.querySelectorAll('.alert:not(.alert-info):not(.alert-light)');
        for (var i = 0; i < existingAlerts.length; i++) {
            var alert = existingAlerts[i];
            if (alert.parentNode) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }
        
        var iconClass = 'exclamation-circle';
        if (type === 'success') {
            iconClass = 'check-circle';
        } else if (type === 'warning') {
            iconClass = 'exclamation-triangle';
        }
        
        var alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-' + type + ' alert-dismissible fade show';
        
        var icon = document.createElement('i');
        icon.className = 'fas fa-' + iconClass;
        
        var messageText = document.createTextNode(' ' + message + ' ');
        
        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'btn-close';
        closeBtn.setAttribute('data-bs-dismiss', 'alert');
        
        alertDiv.appendChild(icon);
        alertDiv.appendChild(messageText);
        alertDiv.appendChild(closeBtn);
        
        var pageHeader = document.querySelector('.page-header');
        if (pageHeader && pageHeader.nextElementSibling) {
            pageHeader.parentNode.insertBefore(alertDiv, pageHeader.nextElementSibling);
        } else {
            var mainContainer = document.querySelector('.main-container');
            if (mainContainer) {
                mainContainer.insertBefore(alertDiv, mainContainer.firstChild);
            }
        }
        
        setTimeout(function() {
            if (alertDiv.parentNode) {
                var bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }
        }, 5000);
    }
});
</script>
</body>
</html>