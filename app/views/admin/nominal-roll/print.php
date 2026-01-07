<?php
/**
 * Print Employee View
 * Print-friendly version of employee details
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Record: <?php echo htmlspecialchars($employee['employee_number']); ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }
        
        .header .subtitle {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 16px;
        }
        
        .employee-info {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            align-items: flex-start;
        }
        
        .photo-section {
            flex: 0 0 150px;
        }
        
        .photo-section img {
            width: 150px;
            height: 150px;
            border: 2px solid #333;
            border-radius: 4px;
            object-fit: cover;
        }
        
        .details-section {
            flex: 1;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #333;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #000;
            margin-bottom: 3px;
            font-size: 13px;
        }
        
        .info-value {
            color: #333;
            font-size: 14px;
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        @page {
            margin: 20mm;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FCT COLLEGE OF NURSING SCIENCES</h1>
        <p class="subtitle">EMPLOYEE NOMINAL ROLL RECORD</p>
        <p class="subtitle">Generated on: <?php echo date('F j, Y'); ?></p>
    </div>
    
    <div class="employee-info">
        <div class="photo-section">
            <?php if (!empty($employee['passport_photo'])): ?>
            <img src="<?php echo $baseUrl . '/' . $employee['passport_photo']; ?>" alt="Passport Photo">
            <?php else: ?>
            <div style="width: 150px; height: 150px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center;">
                <span style="color: #999;">No Photo</span>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="details-section">
            <h2 style="margin: 0 0 10px 0; font-size: 20px;">
                <?php echo htmlspecialchars($employee['surname'] . ', ' . $employee['first_name']); ?>
                <?php if (!empty($employee['middle_name'])): ?>
                <span style="font-weight: normal;">(<?php echo htmlspecialchars($employee['middle_name']); ?>)</span>
                <?php endif; ?>
            </h2>
            
            <div style="margin-bottom: 15px;">
                <strong style="font-size: 16px;"><?php echo htmlspecialchars($employee['rank']); ?></strong>
                <span style="margin-left: 10px; background: #f0f0f0; padding: 3px 8px; border-radius: 3px;">
                    GL <?php echo htmlspecialchars($employee['grade_level']); ?>
                </span>
            </div>
            
            <div class="info-grid" style="grid-template-columns: repeat(3, 1fr);">
                <div class="info-item">
                    <div class="info-label">Employee No.</div>
                    <div class="info-value"><?php echo htmlspecialchars($employee['employee_number']); ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">PF Number</div>
                    <div class="info-value"><?php echo htmlspecialchars($employee['pf_number'] ?? 'N/A'); ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Sex</div>
                    <div class="info-value"><?php echo htmlspecialchars($employee['sex']); ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Basic Information -->
    <div class="section-title">PERSONAL INFORMATION</div>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Date of Birth</div>
            <div class="info-value"><?php echo !empty($employee['date_of_birth']) ? date('M d, Y', strtotime($employee['date_of_birth'])) : 'N/A'; ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Age</div>
            <div class="info-value">
                <?php 
                if (!empty($employee['date_of_birth'])) {
                    $birthDate = new DateTime($employee['date_of_birth']);
                    $today = new DateTime();
                    echo $birthDate->diff($today)->y . ' years';
                } else {
                    echo 'N/A';
                }
                ?>
            </div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Marital Status</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['marital_status'] ?? 'N/A'); ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Telephone</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['telephone_number'] ?? 'N/A'); ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Email</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['email'] ?? 'N/A'); ?></div>
        </div>
    </div>
    
    <!-- Employment Information -->
    <div class="section-title">EMPLOYMENT DETAILS</div>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Date of 1st Appointment</div>
            <div class="info-value"><?php echo !empty($employee['date_of_first_appointment']) ? date('M d, Y', strtotime($employee['date_of_first_appointment'])) : 'N/A'; ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Years of Service</div>
            <div class="info-value">
                <?php 
                if (!empty($employee['date_of_first_appointment'])) {
                    $firstAppointment = new DateTime($employee['date_of_first_appointment']);
                    $today = new DateTime();
                    echo $firstAppointment->diff($today)->y . ' years';
                } else {
                    echo 'N/A';
                }
                ?>
            </div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Date of Confirmation</div>
            <div class="info-value"><?php echo !empty($employee['date_of_confirmation']) ? date('M d, Y', strtotime($employee['date_of_confirmation'])) : 'N/A'; ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Rank on 1st Appointment</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['rank_on_first_appointment'] ?? 'N/A'); ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Date of Present Appointment</div>
            <div class="info-value"><?php echo !empty($employee['date_of_present_appointment']) ? date('M d, Y', strtotime($employee['date_of_present_appointment'])) : 'N/A'; ?></div>
        </div>
    </div>
    
    <!-- Location Information -->
    <div class="section-title">LOCATION INFORMATION</div>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">State of Origin</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['state']); ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Local Government Area</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['local_govt_area']); ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">State of Residence</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['state_of_residence'] ?? 'Same as Origin'); ?></div>
        </div>
        
        <div class="info-item full-width">
            <div class="info-label">Residential Address</div>
            <div class="info-value"><?php echo nl2br(htmlspecialchars($employee['residential_address'] ?? 'N/A')); ?></div>
        </div>
    </div>
    
    <!-- Qualifications -->
    <div class="section-title">QUALIFICATIONS</div>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Highest Qualification</div>
            <div class="info-value">
                <?php echo htmlspecialchars($employee['highest_qualification'] ?? 'N/A'); ?>
                <?php if (!empty($employee['year_of_highest_qualification'])): ?>
                (<?php echo htmlspecialchars($employee['year_of_highest_qualification']); ?>)
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Additional Qualifications -->
    <?php
    $additional_qualifications = [];
    if (!empty($employee['additional_qualifications'])) {
        if (is_string($employee['additional_qualifications'])) {
            $additional_qualifications = json_decode($employee['additional_qualifications'], true);
        }
    }
    
    if (!empty($additional_qualifications) && is_array($additional_qualifications)):
    ?>
    <div style="margin-top: 10px;">
        <div class="info-label">Additional Qualifications:</div>
        <div style="margin-left: 20px;">
            <?php foreach ($additional_qualifications as $qual): ?>
            <div style="margin-bottom: 5px;">
                • <?php echo htmlspecialchars($qual['qualification'] ?? $qual ?? ''); ?>
                <?php if (!empty($qual['year'])): ?>
                (<?php echo htmlspecialchars($qual['year']); ?>)
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Financial Information -->
    <div class="section-title">FINANCIAL INFORMATION</div>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Bank Name</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['bank_name'] ?? 'N/A'); ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Bank Branch</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['bank_branch'] ?? 'N/A'); ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Account Number</div>
            <div class="info-value"><?php echo !empty($employee['account_number']) ? '****' . substr($employee['account_number'], -4) : 'N/A'; ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">NHF Number</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['nhf_number'] ?? 'N/A'); ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Pension Fund Admin</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['pension_fund_admin'] ?? 'N/A'); ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Pension Number</div>
            <div class="info-value"><?php echo htmlspecialchars($employee['pension_number'] ?? 'N/A'); ?></div>
        </div>
    </div>
    
    <div class="footer">
        <p>This is an official document from FCT College of Nursing Sciences</p>
        <p>Generated on <?php echo date('F j, Y \a\t H:i:s'); ?> | Page 1 of 1</p>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
            Print This Document
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; margin-left: 10px;">
            Close Window
        </button>
    </div>
</body>
</html>