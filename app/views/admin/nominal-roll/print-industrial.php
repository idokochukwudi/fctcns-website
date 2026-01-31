<?php
/**
 * Clean Print View for Employee Record
 * 
 * @package FCT_CNS
 */

// Extract data
$employee = $employee ?? [];
$baseUrl = $baseUrl ?? BASE_URL;

// Configure print layout
$pageTitle = 'Employee Record - ' . ($employee['employee_number'] ?? 'N/A');
$documentId = 'EMP-' . ($employee['id'] ?? '') . '-' . date('YmdHis');
$autoPrint = $_GET['autoprint'] ?? false;

// Calculate age and service years
$age = 'N/A';
$serviceYears = 'N/A';

if (!empty($employee['date_of_birth'])) {
    $birthDate = new DateTime($employee['date_of_birth']);
    $today = new DateTime();
    $age = $birthDate->diff($today)->y;
}

if (!empty($employee['date_of_first_appointment'])) {
    $firstAppointment = new DateTime($employee['date_of_first_appointment']);
    $today = new DateTime();
    $serviceYears = $firstAppointment->diff($today)->y;
}

// STATUS FIX: Define status for print view
$status = $employee['status'] ?? 'active';
$status_text = [
    'active' => 'ACTIVE',
    'inactive' => 'INACTIVE',
    'retired' => 'RETIRED',
    'draft' => 'DRAFT'
];
$display_text = $status_text[$status] ?? strtoupper($status);

// Status badge colors for print
$status_colors = [
    'active' => '#d4edda', // Green
    'inactive' => '#fff3cd', // Yellow
    'retired' => '#e2e3e5', // Gray
    'draft' => '#f8f9fa' // Light
];
$status_color = $status_colors[$status] ?? '#e2e3e5';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            width: 210mm;
            min-height: 297mm;
            padding: 20px;
            margin: 0 auto;
            background: #fff;
            line-height: 1.4;
        }
        
        /* Document Header */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #003366;
        }
        
        .college-name {
            font-size: 20px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .document-title {
            font-size: 18px;
            margin: 10px 0;
            font-weight: bold;
        }
        
        .document-meta {
            font-size: 11px;
            color: #666;
            margin-top: 15px;
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        /* Employee Profile Container */
        .profile-container {
            display: flex;
            gap: 25px;
            margin-bottom: 30px;
            align-items: flex-start;
        }
        
        /* Photo Section */
        .photo-section {
            width: 180px;
            flex-shrink: 0;
        }
        
        .photo-box {
            width: 150px;
            height: 180px;
            border: 2px solid #ccc;
            background: #f8f9fa;
            margin: 0 auto 15px;
            overflow: hidden;
            position: relative;
        }
        
        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
            font-size: 11px;
            text-align: center;
            padding: 10px;
        }
        
        .photo-placeholder div {
            margin: 2px 0;
        }
        
        .id-box {
            text-align: center;
            border: 1px solid #ddd;
            padding: 10px;
            background: #f0f8ff;
        }
        
        .emp-number {
            font-size: 14px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 3px;
        }
        
        .emp-label {
            font-size: 10px;
            color: #666;
        }
        
        /* Details Section */
        .details-section {
            flex: 1;
        }
        
        .name-row {
            margin-bottom: 15px;
        }
        
        .employee-name {
            font-size: 22px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }
        
        .employee-title {
            font-size: 16px;
            color: #003366;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        /* Badges Row */
        .badges-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .badge {
            padding: 5px 12px;
            background: #e9ecef;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            color: #495057;
            border: 1px solid #dee2e6;
        }
        
        .badge.blue {
            background: #e7f1ff;
            color: #004085;
            border-color: #b8daff;
        }
        
        .badge.green {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .badge.purple {
            background: #e2e3e5;
            color: #383d41;
            border-color: #d6d8db;
        }
        
        .badge.status {
            /* Status color will be set inline */
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge.yellow {
            background: #fff3cd;
            color: #856404;
            border-color: #ffeaa7;
        }
        
        .badge.gray {
            background: #e2e3e5;
            color: #383d41;
            border-color: #d6d8db;
        }
        
        .badge.light {
            background: #f8f9fa;
            color: #495057;
            border-color: #dee2e6;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 25px;
        }
        
        .stat-card {
            text-align: center;
            border: 1px solid #dee2e6;
            padding: 12px;
            border-radius: 6px;
            background: #f8f9fa;
        }
        
        .stat-label {
            display: block;
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .stat-value {
            display: block;
            font-size: 18px;
            font-weight: bold;
            color: #003366;
        }
        
        /* Information Sections */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #003366;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        /* Info Tables */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table tr {
            border-bottom: 1px solid #eee;
        }
        
        .info-table td {
            padding: 10px 12px;
            vertical-align: top;
        }
        
        .info-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .info-table td:first-child {
            width: 35%;
            font-weight: bold;
            color: #333;
            background: #f5f7fa;
        }
        
        .info-table td:nth-child(2) {
            width: 30%;
        }
        
        .info-table td:nth-child(3) {
            width: 35%;
            font-weight: bold;
            color: #333;
            background: #f5f7fa;
        }
        
        .info-table td:nth-child(4) {
            width: 30%;
        }
        
        /* Status Highlight */
        .status-highlight {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px 15px;
            margin: 15px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .status-label {
            font-weight: bold;
            color: #333;
            font-size: 13px;
        }
        
        .status-value {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
        }
        
        /* Address Boxes */
        .address-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }
        
        .address-box {
            border: 1px solid #dee2e6;
            padding: 12px;
            border-radius: 4px;
            background: #f8f9fa;
        }
        
        .address-title {
            font-weight: bold;
            color: #003366;
            margin-bottom: 8px;
            font-size: 13px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .address-content {
            font-size: 12px;
            line-height: 1.5;
            min-height: 50px;
        }
        
        /* QR Code */
        .qr-section {
            text-align: center;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            max-width: 180px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        #qr-code {
            display: block;
            margin: 0 auto 15px;
        }
        
        .qr-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .qr-ref {
            font-family: monospace;
            font-size: 9px;
            color: #999;
            margin-top: 8px;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .footer-note {
            font-style: italic;
            margin-bottom: 10px;
        }
        
        .footer-info {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        
        /* Print Controls */
        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 12px;
            border-radius: 6px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.15);
            z-index: 1000;
            display: flex;
            gap: 8px;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            min-width: 100px;
        }
        
        .btn-print {
            background: #007bff;
            color: white;
        }
        
        .btn-close {
            background: #6c757d;
            color: white;
        }
        
        /* Print Media */
        @media print {
            body {
                padding: 15px;
            }
            
            .print-controls {
                display: none;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
        
        @media screen {
            body {
                background: #f0f0f0;
                padding: 20px;
            }
            
            .document-wrapper {
                background: white;
                box-shadow: 0 5px 25px rgba(0,0,0,0.1);
                border-radius: 8px;
                overflow: hidden;
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="document-wrapper">
        <!-- Header -->
        <div class="header">
            <div class="college-name">FCT College of Nursing Sciences</div>
            <div class="document-title">Employee Official Record</div>
            <div class="document-meta">
                Document ID: <?php echo $documentId; ?> | 
                Generated: <?php echo date('F j, Y H:i'); ?> | 
                Page 1 of 1
            </div>
        </div>
        
        <!-- Employee Profile -->
        <div class="profile-container">
            <!-- Left Column: Photo & ID -->
            <div class="photo-section">
                <div class="photo-box">
                    <?php if (!empty($employee['passport_photo'])): ?>
                    <img src="<?php echo $baseUrl; ?>/admin/nominal-roll/passport-photo/<?php echo $employee['id']; ?>" 
                         alt="Passport Photo"
                         class="photo-img"
                         onerror="this.style.display='none';">
                    <?php endif; ?>
                    
                    <?php if (empty($employee['passport_photo'])): ?>
                    <div class="photo-placeholder">
                        <div>PASSPORT</div>
                        <div>PHOTO</div>
                        <div>Not Available</div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="id-box">
                    <div class="emp-number"><?php echo htmlspecialchars($employee['employee_number'] ?? 'N/A'); ?></div>
                    <div class="emp-label">Employee ID Number</div>
                </div>
            </div>
            
            <!-- Right Column: Details -->
            <div class="details-section">
                <div class="name-row">
                    <div class="employee-name">
                        <?php echo strtoupper(htmlspecialchars($employee['surname'] ?? '')); ?>, 
                        <?php echo htmlspecialchars($employee['first_name'] ?? ''); ?>
                        <?php if (!empty($employee['middle_name'])): ?>
                        <?php echo htmlspecialchars($employee['middle_name']); ?>
                        <?php endif; ?>
                    </div>
                    <div class="employee-title"><?php echo htmlspecialchars($employee['rank'] ?? 'N/A'); ?></div>
                </div>
                
                <div class="badges-row">
                    <span class="badge blue">Grade Level: <?php echo htmlspecialchars($employee['grade_level'] ?? 'N/A'); ?></span>
                    <span class="badge blue">Step: <?php echo htmlspecialchars($employee['step'] ?? 'N/A'); ?></span>
                    <span class="badge green"><?php echo htmlspecialchars($employee['sex'] ?? 'N/A'); ?></span>
                    <span class="badge purple"><?php echo htmlspecialchars($employee['staff_type'] ?? 'N/A'); ?></span>
                    <!-- FIXED: STATUS BADGE ADDED -->
                    <span class="badge status" style="background: <?php echo $status_color; ?>; 
                        color: <?php echo $status === 'active' ? '#155724' : 
                                     ($status === 'inactive' ? '#856404' : 
                                     ($status === 'retired' ? '#383d41' : '#495057')); ?>;">
                        <?php echo $display_text; ?>
                    </span>
                </div>
                
                <!-- Status Highlight Box -->
                <div class="status-highlight">
                    <span class="status-label">Current Employment Status:</span>
                    <span class="status-value" style="background: <?php echo $status_color; ?>; 
                        color: <?php echo $status === 'active' ? '#155724' : 
                                     ($status === 'inactive' ? '#856404' : 
                                     ($status === 'retired' ? '#383d41' : '#495057')); ?>;">
                        <?php echo ucfirst($status); ?>
                    </span>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="stat-label">Department</span>
                        <span class="stat-value"><?php echo htmlspecialchars($employee['department'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Years of Service</span>
                        <span class="stat-value"><?php echo $serviceYears; ?></span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Age</span>
                        <span class="stat-value"><?php echo $age; ?></span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Status</span>
                        <span class="stat-value" style="color: <?php echo $status === 'active' ? '#28a745' : 
                                                                   ($status === 'inactive' ? '#ffc107' : 
                                                                   ($status === 'retired' ? '#6c757d' : '#adb5bd')); ?>;">
                            <?php echo $display_text; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Personal Information -->
        <div class="section">
            <div class="section-title">Personal Information</div>
            <table class="info-table">
                <tr>
                    <td>Date of Birth:</td>
                    <td><?php echo !empty($employee['date_of_birth']) ? date('F j, Y', strtotime($employee['date_of_birth'])) : 'N/A'; ?></td>
                    <td>Marital Status:</td>
                    <td><?php echo htmlspecialchars($employee['marital_status'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td>Nationality:</td>
                    <td><?php echo htmlspecialchars($employee['nationality'] ?? 'N/A'); ?></td>
                    <td>Religion:</td>
                    <td><?php echo htmlspecialchars($employee['religion'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td>Phone Number:</td>
                    <td><?php echo htmlspecialchars($employee['telephone_number'] ?? 'N/A'); ?></td>
                    <td>Email Address:</td>
                    <td><?php echo htmlspecialchars($employee['email'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td>State of Origin:</td>
                    <td><?php echo htmlspecialchars($employee['state'] ?? 'N/A'); ?></td>
                    <td>Local Government:</td>
                    <td><?php echo htmlspecialchars($employee['local_govt_area'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td>Geopolitical Zone:</td>
                    <td><?php echo htmlspecialchars($employee['geopolitical_zone'] ?? 'N/A'); ?></td>
                    <td>State of Residence:</td>
                    <td><?php echo htmlspecialchars($employee['state_of_residence'] ?? $employee['state'] ?? 'N/A'); ?></td>
                </tr>
                <!-- ADDED: STATUS FIELD -->
                <tr>
                    <td>Employment Status:</td>
                    <td colspan="3">
                        <strong style="color: <?php echo $status === 'active' ? '#28a745' : 
                                                  ($status === 'inactive' ? '#ffc107' : 
                                                  ($status === 'retired' ? '#6c757d' : '#adb5bd')); ?>;">
                            <?php echo ucfirst($status); ?> (<?php echo $display_text; ?>)
                        </strong>
                    </td>
                </tr>
            </table>
            
            <div class="address-container">
                <div class="address-box">
                    <div class="address-title">Residential Address</div>
                    <div class="address-content">
                        <?php echo nl2br(htmlspecialchars($employee['residential_address'] ?? 'Not provided')); ?>
                    </div>
                </div>
                <div class="address-box">
                    <div class="address-title">Contact Address</div>
                    <div class="address-content">
                        <?php echo nl2br(htmlspecialchars($employee['contact_address'] ?? 'Not provided')); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Employment Details -->
        <div class="section">
            <div class="section-title">Employment Details</div>
            <table class="info-table">
                <tr>
                    <td>Date of First Appointment:</td>
                    <td><?php echo !empty($employee['date_of_first_appointment']) ? date('F j, Y', strtotime($employee['date_of_first_appointment'])) : 'N/A'; ?></td>
                    <td>Date of Confirmation:</td>
                    <td><?php echo !empty($employee['date_of_confirmation']) ? date('F j, Y', strtotime($employee['date_of_confirmation'])) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td>Date of Present Appointment:</td>
                    <td><?php echo !empty($employee['date_of_present_appointment']) ? date('F j, Y', strtotime($employee['date_of_present_appointment'])) : 'N/A'; ?></td>
                    <td>Rank on First Appointment:</td>
                    <td><?php echo htmlspecialchars($employee['rank_on_first_appointment'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td>Cadre:</td>
                    <td><?php echo htmlspecialchars($employee['cadre'] ?? 'N/A'); ?></td>
                    <td>Salary Structure:</td>
                    <td><?php echo htmlspecialchars($employee['salary_structure'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td>PF Number:</td>
                    <td><?php echo htmlspecialchars($employee['pf_number'] ?? 'N/A'); ?></td>
                    <td>Staff Category:</td>
                    <td><?php echo htmlspecialchars($employee['staff_type'] ?? 'N/A'); ?></td>
                </tr>
                <!-- ADDED: STATUS IN EMPLOYMENT SECTION -->
                <tr>
                    <td>Current Status:</td>
                    <td colspan="3">
                        <span style="display: inline-block; 
                                     padding: 3px 10px; 
                                     border-radius: 3px; 
                                     background: <?php echo $status_color; ?>; 
                                     color: <?php echo $status === 'active' ? '#155724' : 
                                                 ($status === 'inactive' ? '#856404' : 
                                                 ($status === 'retired' ? '#383d41' : '#495057')); ?>; 
                                     font-weight: bold; 
                                     text-transform: uppercase;">
                            <?php echo $display_text; ?>
                        </span>
                        <?php if ($status === 'inactive'): ?>
                        <span style="color: #856404; margin-left: 10px; font-size: 11px; font-style: italic;">
                            (Employee currently not active)
                        </span>
                        <?php elseif ($status === 'retired'): ?>
                        <span style="color: #6c757d; margin-left: 10px; font-size: 11px; font-style: italic;">
                            (Employee retired from service)
                        </span>
                        <?php elseif ($status === 'draft'): ?>
                        <span style="color: #6c757d; margin-left: 10px; font-size: 11px; font-style: italic;">
                            (Record in draft status - not finalized)
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Financial Information -->
        <div class="section">
            <div class="section-title">Financial Information</div>
            <table class="info-table">
                <tr>
                    <td>Bank Name:</td>
                    <td><?php echo htmlspecialchars($employee['bank_name'] ?? 'N/A'); ?></td>
                    <td>Account Name:</td>
                    <td><?php echo htmlspecialchars($employee['account_name'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td>Account Number:</td>
                    <td><?php echo !empty($employee['account_number']) ? '****' . substr($employee['account_number'], -4) : 'N/A'; ?></td>
                    <td>Bank Branch:</td>
                    <td><?php echo htmlspecialchars($employee['bank_branch'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td>Pension Number:</td>
                    <td><?php echo htmlspecialchars($employee['pension_number'] ?? 'N/A'); ?></td>
                    <td>Pension Fund Admin:</td>
                    <td><?php echo htmlspecialchars($employee['pension_fund_admin'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td>NHF Number:</td>
                    <td><?php echo htmlspecialchars($employee['nhf_number'] ?? 'N/A'); ?></td>
                    <td>TIN Number:</td>
                    <td><?php echo htmlspecialchars($employee['tin_number'] ?? 'N/A'); ?></td>
                </tr>
            </table>
        </div>
        
        <!-- Qualifications -->
        <div class="section">
            <div class="section-title">Qualifications & Certifications</div>
            <table class="info-table">
                <tr>
                    <td style="width: 25%;">Highest Qualification:</td>
                    <td style="width: 75%;" colspan="3">
                        <?php echo htmlspecialchars($employee['highest_qualification'] ?? 'N/A'); ?>
                        <?php if (!empty($employee['year_of_highest_qualification'])): ?>
                        (Year: <?php echo htmlspecialchars($employee['year_of_highest_qualification']); ?>)
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if (!empty($employee['professional_certifications'])): ?>
                <tr>
                    <td>Professional Certifications:</td>
                    <td colspan="3">
                        <?php echo nl2br(htmlspecialchars($employee['professional_certifications'])); ?>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        
        <!-- QR Code -->
        <div class="qr-section">
            <div id="qr-code"></div>
            <div class="qr-label">Digital Verification Code</div>
            <div style="font-size: 10px; color: #555;">Scan to verify document authenticity</div>
            <div class="qr-ref">Ref: <?php echo $documentId; ?></div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-note">
                This is an official document of FCT College of Nursing Sciences. 
                Unauthorized duplication or distribution is strictly prohibited.
            </div>
            <div class="footer-info">
                <div>Generated: <?php echo date('F j, Y H:i:s'); ?></div>
                <div>Generated by: <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'HR System'); ?></div>
                <div>Document ID: <?php echo $documentId; ?></div>
                <div style="font-weight: bold; color: <?php echo $status === 'active' ? '#28a745' : 
                                                          ($status === 'inactive' ? '#ffc107' : 
                                                          ($status === 'retired' ? '#6c757d' : '#adb5bd')); ?>;">
                    Status: <?php echo $display_text; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Print Controls -->
    <div class="print-controls">
        <button class="btn btn-print" onclick="window.print()">Print Document</button>
        <button class="btn btn-close" onclick="window.close()">Close Preview</button>
    </div>
    
    <!-- QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate QR Code
        document.addEventListener('DOMContentLoaded', function() {
            // Generate verification URL
            const employeeId = '<?php echo $employee['id'] ?? ''; ?>';
            const docId = '<?php echo $documentId; ?>';
            const baseUrl = '<?php echo $baseUrl; ?>';
            
            // Use the new verification route
            const verificationUrl = baseUrl + '/verify/employee/' + employeeId + '?ref=' + docId;
            
            // Create QR Code
            const qrContainer = document.getElementById('qr-code');
            if (qrContainer) {
                qrContainer.innerHTML = '';
                try {
                    new QRCode(qrContainer, {
                        text: verificationUrl,
                        width: 120,
                        height: 120,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                } catch (error) {
                    qrContainer.innerHTML = '<div style="color:#999;font-size:11px;">QR Code unavailable</div>';
                }
            }
            
            // Auto-print if requested
            <?php if ($autoPrint): ?>
            setTimeout(function() {
                window.print();
            }, 1000);
            <?php endif; ?>
        });
        
        // Handle print events
        window.addEventListener('afterprint', function() {
            <?php if ($autoPrint): ?>
            setTimeout(function() {
                if (window.opener && !window.opener.closed) {
                    window.close();
                }
            }, 500);
            <?php endif; ?>
        });
    </script>
</body>
</html>