<?php
/**
 * View Employee Details
 * Display complete employee information
 */
?>
<div class="view-employee-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1>Employee Details</h1>
                <p class="subtitle">Complete employee record</p>
                <div class="employee-badge">
                    <span class="badge badge-primary">Employee No: <?php echo htmlspecialchars($employee['employee_number'] ?? 'N/A'); ?></span>
                    <span class="badge <?php echo ($employee['sex'] ?? '') === 'Male' ? 'badge-info' : 'badge-pink'; ?>">
                        <?php echo htmlspecialchars($employee['sex'] ?? 'N/A'); ?>
                    </span>
                    <span class="badge badge-secondary">GL <?php echo htmlspecialchars($employee['grade_level'] ?? 'N/A'); ?></span>
                    <?php if (!empty($employee['pf_number'])): ?>
                    <span class="badge badge-success">
                        <i class="fas fa-id-badge"></i> PF: <?php echo htmlspecialchars($employee['pf_number']); ?>
                    </span>
                    <?php endif; ?>
                    <span class="badge badge-light">
                        <?php echo !empty($employee['status']) && $employee['status'] === 'draft' ? 'DRAFT' : 'ACTIVE'; ?>
                    </span>
                </div>
            </div>
            <div class="header-actions">
                <?php if (isset($isEditor) && $isEditor && (($editingEnabled ?? false) || ($isSuperAdmin ?? false))): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/edit/<?php echo $employee['id']; ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <?php endif; ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <button type="button" class="btn btn-success" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/export?format=pdf&id=<?php echo $employee['id']; ?>" 
                   class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Employee Profile Card -->
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-photo">
                <?php 
                // FIXED PHOTO DISPLAY LOGIC FOR VIEW.PHP
                // Replaced lines 70-130 with fixed version
                
                $photoUrl = '';
                $defaultAvatar = $baseUrl . '/assets/img/default-avatar.png';

                if (!empty($employee['passport_photo'])) {
                    // Clean the path
                    $cleanPath = trim($employee['passport_photo'], '/');
                    
                    // Build URL - expecting path like: storage/uploads/passports/passport_123.jpg
                    if (strpos($cleanPath, 'storage/') === 0) {
                        // Path already has storage prefix - use directly
                        $photoUrl = $baseUrl . '/' . $cleanPath;
                    } else if (strpos($cleanPath, 'uploads/passports/') === 0) {
                        // Path missing storage prefix
                        $photoUrl = $baseUrl . '/storage/' . $cleanPath;
                    } else {
                        // Assume it's just the filename
                        $photoUrl = $baseUrl . '/storage/uploads/passports/' . basename($cleanPath);
                    }
                    
                    error_log("Photo Display - DB Path: " . $employee['passport_photo']);
                    error_log("Photo Display - Final URL: " . $photoUrl);
                }
                ?>
                
                <?php if (!empty($photoUrl)): ?>
                    <img src="<?php echo htmlspecialchars($photoUrl); ?>" 
                         alt="Passport Photo" 
                         class="passport-photo"
                         onerror="console.error('Failed to load photo:', this.src); this.onerror=null; this.src='<?php echo htmlspecialchars($defaultAvatar); ?>';">
                <?php else: ?>
                    <div class="no-photo">
                        <i class="fas fa-user-circle"></i>
                        <p>No Photo</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars(($employee['surname'] ?? '') . ', ' . ($employee['first_name'] ?? '')); ?></h2>
                <?php if (!empty($employee['middle_name'])): ?>
                <p class="middle-name"><?php echo htmlspecialchars($employee['middle_name']); ?></p>
                <?php endif; ?>
                <p class="rank"><strong><?php echo htmlspecialchars($employee['rank'] ?? 'N/A'); ?></strong></p>
                
                <!-- PF Number Display Prominently -->
                <?php if (!empty($employee['pf_number'])): ?>
                <div class="pf-number-display">
                    <i class="fas fa-id-badge"></i> 
                    <strong>Personal File (PF) Number:</strong> 
                    <span class="pf-number-value"><?php echo htmlspecialchars($employee['pf_number']); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="profile-meta">
                    <span><i class="fas fa-id-card"></i> <?php echo htmlspecialchars($employee['employee_number'] ?? 'N/A'); ?></span>
                    <span><i class="fas fa-venus-mars"></i> <?php echo htmlspecialchars($employee['sex'] ?? 'N/A'); ?></span>
                    <span><i class="fas fa-birthday-cake"></i> <?php echo !empty($employee['date_of_birth']) ? date('M d, Y', strtotime($employee['date_of_birth'])) : 'N/A'; ?></span>
                    <span><i class="fas fa-ring"></i> <?php echo htmlspecialchars($employee['marital_status'] ?? 'N/A'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Details Tabs -->
    <div class="details-tabs">
        <!-- Tab buttons with simplified structure -->
        <div class="tabs-header">
            <button class="tab-button active" data-tab="basic">
                <i class="fas fa-user"></i> Basic Info
            </button>
            <button class="tab-button" data-tab="employment">
                <i class="fas fa-briefcase"></i> Employment
            </button>
            <button class="tab-button" data-tab="location">
                <i class="fas fa-map-marker-alt"></i> Location
            </button>
            <button class="tab-button" data-tab="financial">
                <i class="fas fa-file-invoice-dollar"></i> Financial
            </button>
            <button class="tab-button" data-tab="qualifications">
                <i class="fas fa-graduation-cap"></i> Qualifications
            </button>
            <button class="tab-button" data-tab="audit">
                <i class="fas fa-history"></i> Audit Trail
            </button>
        </div>

        <!-- Tab content -->
        <div class="tabs-content">
            <!-- Basic Information Tab -->
            <div id="basic" class="tab-pane active">
                <div class="tab-card">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Employee Number</label>
                            <p class="value"><?php echo htmlspecialchars($employee['employee_number'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Full Name</label>
                            <p class="value">
                                <?php echo htmlspecialchars(($employee['surname'] ?? '') . ', ' . ($employee['first_name'] ?? '')); ?>
                                <?php if (!empty($employee['middle_name'])): ?>
                                <br><span class="text-muted"><?php echo htmlspecialchars($employee['middle_name']); ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="info-item">
                            <label>Sex</label>
                            <p class="value">
                                <span class="badge <?php echo ($employee['sex'] ?? '') === 'Male' ? 'badge-info' : 'badge-pink'; ?>">
                                    <?php echo htmlspecialchars($employee['sex'] ?? 'N/A'); ?>
                                </span>
                            </p>
                        </div>
                        <div class="info-item">
                            <label>Date of Birth</label>
                            <p class="value"><?php echo !empty($employee['date_of_birth']) ? date('M d, Y', strtotime($employee['date_of_birth'])) : 'N/A'; ?></p>
                        </div>
                        <div class="info-item">
                            <label>Age</label>
                            <p class="value">
                                <?php 
                                if (!empty($employee['date_of_birth'])) {
                                    $birthDate = new DateTime($employee['date_of_birth']);
                                    $today = new DateTime();
                                    $age = $birthDate->diff($today)->y;
                                    echo $age . ' years';
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="info-item">
                            <label>Marital Status</label>
                            <p class="value"><?php echo htmlspecialchars($employee['marital_status'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Telephone</label>
                            <p class="value"><?php echo htmlspecialchars($employee['telephone_number'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Email</label>
                            <p class="value"><?php echo htmlspecialchars($employee['email'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Nationality</label>
                            <p class="value"><?php echo htmlspecialchars($employee['nationality'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Religion</label>
                            <p class="value"><?php echo htmlspecialchars($employee['religion'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Details Tab -->
            <div id="employment" class="tab-pane">
                <div class="tab-card">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Rank</label>
                            <p class="value"><?php echo htmlspecialchars($employee['rank'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Grade Level</label>
                            <p class="value">GL <?php echo htmlspecialchars($employee['grade_level'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Step</label>
                            <p class="value"><?php echo htmlspecialchars($employee['step'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Date of 1st Appointment</label>
                            <p class="value"><?php echo !empty($employee['date_of_first_appointment']) ? date('M d, Y', strtotime($employee['date_of_first_appointment'])) : 'N/A'; ?></p>
                        </div>
                        <div class="info-item">
                            <label>Years of Service</label>
                            <p class="value">
                                <?php 
                                if (!empty($employee['date_of_first_appointment'])) {
                                    $firstAppointment = new DateTime($employee['date_of_first_appointment']);
                                    $today = new DateTime();
                                    $years = $firstAppointment->diff($today)->y;
                                    echo $years . ' years';
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="info-item">
                            <label>Date of Confirmation</label>
                            <p class="value"><?php echo !empty($employee['date_of_confirmation']) ? date('M d, Y', strtotime($employee['date_of_confirmation'])) : 'N/A'; ?></p>
                        </div>
                        <div class="info-item">
                            <label>Rank on 1st Appointment</label>
                            <p class="value"><?php echo htmlspecialchars($employee['rank_on_first_appointment'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Date of Present Appointment</label>
                            <p class="value"><?php echo !empty($employee['date_of_present_appointment']) ? date('M d, Y', strtotime($employee['date_of_present_appointment'])) : 'N/A'; ?></p>
                        </div>
                        <div class="info-item">
                            <label>PF Number</label>
                            <p class="value"><?php echo htmlspecialchars($employee['pf_number'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Cadre</label>
                            <p class="value"><?php echo htmlspecialchars($employee['cadre'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Staff Type</label>
                            <p class="value"><?php echo htmlspecialchars($employee['staff_type'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Department</label>
                            <p class="value"><?php echo htmlspecialchars($employee['department'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Information Tab -->
            <div id="location" class="tab-pane">
                <div class="tab-card">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>State of Origin</label>
                            <p class="value"><?php echo htmlspecialchars($employee['state'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Local Government Area</label>
                            <p class="value"><?php echo htmlspecialchars($employee['local_govt_area'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>State of Residence</label>
                            <p class="value"><?php echo htmlspecialchars($employee['state_of_residence'] ?? ($employee['state'] ?? 'N/A')); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Geopolitical Zone</label>
                            <p class="value"><?php echo htmlspecialchars($employee['geopolitical_zone'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item full-width">
                            <label>Residential Address</label>
                            <p class="value"><?php echo nl2br(htmlspecialchars($employee['residential_address'] ?? 'N/A')); ?></p>
                        </div>
                        <div class="info-item full-width">
                            <label>Contact Address</label>
                            <p class="value"><?php echo nl2br(htmlspecialchars($employee['contact_address'] ?? 'N/A')); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information Tab -->
            <div id="financial" class="tab-pane">
                <div class="tab-card">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Bank Name</label>
                            <p class="value"><?php echo htmlspecialchars($employee['bank_name'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Bank Branch</label>
                            <p class="value"><?php echo htmlspecialchars($employee['bank_branch'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Account Number</label>
                            <p class="value"><?php echo !empty($employee['account_number']) ? '****' . substr($employee['account_number'], -4) : 'N/A'; ?></p>
                        </div>
                        <div class="info-item">
                            <label>Account Name</label>
                            <p class="value"><?php echo htmlspecialchars($employee['account_name'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>NHF Number</label>
                            <p class="value"><?php echo htmlspecialchars($employee['nhf_number'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Pension Fund Admin</label>
                            <p class="value"><?php echo htmlspecialchars($employee['pension_fund_admin'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Pension Number</label>
                            <p class="value"><?php echo htmlspecialchars($employee['pension_number'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Tax Identification No (TIN)</label>
                            <p class="value"><?php echo htmlspecialchars($employee['tin_number'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Salary Structure</label>
                            <p class="value"><?php echo htmlspecialchars($employee['salary_structure'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Qualifications Tab -->
            <div id="qualifications" class="tab-pane">
                <div class="tab-card">
                    <div class="qualifications-section">
                        <div class="highest-qualification">
                            <h4>Highest Qualification</h4>
                            <div class="qualification-card">
                                <div class="qualification-info">
                                    <h5><?php echo htmlspecialchars($employee['highest_qualification'] ?? 'N/A'); ?></h5>
                                    <?php if (!empty($employee['year_of_highest_qualification'])): ?>
                                    <span class="year"><?php echo htmlspecialchars($employee['year_of_highest_qualification']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="additional-qualifications">
                            <h4>Additional Qualifications</h4>
                            <?php
                            $additional_qualifications = [];
                            if (!empty($employee['additional_qualifications'])) {
                                if (is_string($employee['additional_qualifications'])) {
                                    $additional_qualifications = json_decode($employee['additional_qualifications'], true);
                                    if (json_last_error() !== JSON_ERROR_NONE) {
                                        $additional_qualifications = [$employee['additional_qualifications']];
                                    }
                                } elseif (is_array($employee['additional_qualifications'])) {
                                    $additional_qualifications = $employee['additional_qualifications'];
                                }
                            }
                            
                            if (!empty($additional_qualifications) && is_array($additional_qualifications)):
                            ?>
                            <div class="qualifications-list">
                                <?php foreach ($additional_qualifications as $qual): ?>
                                <div class="qualification-item">
                                    <div class="qualification-name">
                                        <?php echo htmlspecialchars($qual['qualification'] ?? $qual ?? ''); ?>
                                    </div>
                                    <?php if (!empty($qual['year'])): ?>
                                    <div class="qualification-year">
                                        <?php echo htmlspecialchars($qual['year']); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <p class="text-muted">No additional qualifications</p>
                            <?php endif; ?>
                        </div>

                        <div class="professional-certifications">
                            <h4>Professional Certifications</h4>
                            <?php if (!empty($employee['professional_certifications'])): ?>
                            <p class="value"><?php echo nl2br(htmlspecialchars($employee['professional_certifications'])); ?></p>
                            <?php else: ?>
                            <p class="text-muted">No professional certifications</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit Trail Tab -->
            <div id="audit" class="tab-pane">
                <div class="tab-card">
                    <?php if (!empty($auditTrail) && is_array($auditTrail)): ?>
                    <div class="audit-trail">
                        <h4>Activity Log</h4>
                        <div class="timeline">
                            <?php foreach ($auditTrail as $audit): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <?php if (($audit['action_type'] ?? '') === 'created'): ?>
                                    <i class="fas fa-plus-circle text-success"></i>
                                    <?php elseif (($audit['action_type'] ?? '') === 'updated'): ?>
                                    <i class="fas fa-edit text-primary"></i>
                                    <?php elseif (($audit['action_type'] ?? '') === 'deleted'): ?>
                                    <i class="fas fa-trash text-danger"></i>
                                    <?php else: ?>
                                    <i class="fas fa-info-circle text-info"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <span class="action"><?php echo ucfirst($audit['action_type'] ?? 'unknown'); ?> by <?php echo htmlspecialchars($audit['user_name'] ?? 'System'); ?></span>
                                        <span class="time"><?php echo !empty($audit['created_at']) ? date('M d, Y H:i', strtotime($audit['created_at'])) : 'N/A'; ?></span>
                                    </div>
                                    <div class="timeline-body">
                                        <?php if (!empty($audit['details'])): ?>
                                        <p class="details"><?php echo htmlspecialchars($audit['details']); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($audit['ip_address'])): ?>
                                        <small class="text-muted">IP: <?php echo htmlspecialchars($audit['ip_address']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            </div>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-history"></i>
                        <h4>No Audit Trail</h4>
                        <p>No activity recorded for this employee</p>
                    </div>
                    <?php endif; ?>

                    <div class="record-info">
                        <h4>Record Information</h4>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Created At</label>
                                <p class="value"><?php echo !empty($employee['created_at']) ? date('M d, Y H:i', strtotime($employee['created_at'])) : 'N/A'; ?></p>
                            </div>
                            <div class="info-item">
                                <label>Created By</label>
                                <p class="value"><?php echo htmlspecialchars($employee['created_by_name'] ?? 'System'); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Updated At</label>
                                <p class="value"><?php echo !empty($employee['updated_at']) ? date('M d, Y H:i', strtotime($employee['updated_at'])) : 'Never'; ?></p>
                            </div>
                            <div class="info-item">
                                <label>Updated By</label>
                                <p class="value"><?php echo htmlspecialchars($employee['updated_by_name'] ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <?php if (isset($isEditor) && $isEditor && (($editingEnabled ?? false) || ($isSuperAdmin ?? false))): ?>
        <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/edit/<?php echo $employee['id']; ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Record
        </a>
        <?php endif; ?>
        
        <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/export?format=pdf&id=<?php echo $employee['id']; ?>" 
           class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Export as PDF
        </a>
        
        <button type="button" class="btn btn-info" onclick="window.print()">
            <i class="fas fa-print"></i> Print Record
        </button>
        
        <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/export?format=excel&id=<?php echo $employee['id']; ?>" 
           class="btn btn-success" target="_blank">
            <i class="fas fa-file-excel"></i> Export as Excel
        </a>
        
        <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        
        <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
        <button type="button" 
                class="btn btn-danger" 
                data-toggle="modal" 
                data-target="#deleteModal"
                onclick="event.preventDefault();">
            <i class="fas fa-trash"></i> Delete Record
        </button>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeDeleteModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning!</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete the employee record for:</p>
                <div class="employee-delete-info">
                    <h4><?php echo htmlspecialchars(($employee['surname'] ?? '') . ', ' . ($employee['first_name'] ?? '')); ?></h4>
                    <p>Employee No: <strong><?php echo htmlspecialchars($employee['employee_number'] ?? 'N/A'); ?></strong></p>
                    <p>Rank: <strong><?php echo htmlspecialchars($employee['rank'] ?? 'N/A'); ?></strong></p>
                </div>
                <p class="text-danger">All associated data will be permanently deleted from the system.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeDeleteModal()">Cancel</button>
                <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/delete/<?php echo $employee['id']; ?>" style="display: inline;">
                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                    <button type="submit" class="btn btn-danger">Delete Permanently</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing view page');
    
    // FIX FOR TAB FUNCTIONALITY
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Add active class to clicked button and corresponding pane
            this.classList.add('active');
            const targetPane = document.getElementById(targetTab);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
    
    // Activate first tab by default if none is active
    if (!document.querySelector('.tab-button.active')) {
        tabButtons[0]?.click();
    }
    
    // Delete Modal functionality
    const deleteButtons = document.querySelectorAll('[data-target="#deleteModal"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openDeleteModal();
        });
    });

    // Ensure modal is hidden on page load
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.style.display = 'none';
        deleteModal.classList.remove('show');
        deleteModal.setAttribute('aria-hidden', 'true');
    }

    // Copy employee number to clipboard
    const copyEmployeeNumber = function() {
        const employeeNumber = '<?php echo $employee["employee_number"] ?? ""; ?>';
        if (employeeNumber) {
            navigator.clipboard.writeText(employeeNumber).then(() => {
                alert('Employee number copied to clipboard: ' + employeeNumber);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    };

    // Add copy button for employee number
    const employeeNumberElement = document.querySelector('.employee-badge .badge-primary');
    if (employeeNumberElement) {
        employeeNumberElement.style.cursor = 'pointer';
        employeeNumberElement.title = 'Click to copy';
        employeeNumberElement.addEventListener('click', copyEmployeeNumber);
    }
    
    // Handle image errors - fixed version
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('error', function() {
            console.log('Image failed to load:', this.src);
            // Check if this is a passport photo
            if (this.classList.contains('passport-photo')) {
                // Use default avatar
                const defaultAvatar = '<?php echo $baseUrl ?? ""; ?>/assets/img/default-avatar.png';
                console.log('Setting default avatar:', defaultAvatar);
                this.src = defaultAvatar;
            }
        });
        
        img.addEventListener('load', function() {
            console.log('Image loaded successfully:', this.src);
        });
    });
});

// Function to open delete modal
function openDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'block';
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        
        // Add backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'modalBackdrop';
        document.body.appendChild(backdrop);
    }
}

// Function to close delete modal
function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        
        // Remove backdrop
        const backdrop = document.getElementById('modalBackdrop');
        if (backdrop) {
            backdrop.remove();
        }
    }
}

// Close modal when clicking outside or with Escape key
document.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (modal && event.target === modal) {
        closeDeleteModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
    }
});

// Print functionality
window.printRecord = function() {
    window.print();
};
</script>

<style>
.view-employee-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Page Header */
.page-header {
    margin-bottom: 30px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 20px;
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
    margin: 0 0 15px 0;
}

.employee-badge {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 4px;
}

.badge-primary {
    background: #3490dc;
    color: white;
}

.badge-info {
    background: #6cb2eb;
    color: white;
}

.badge-pink {
    background: #ed64a6;
    color: white;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

.badge-light {
    background: #f8f9fa;
    color: #6c757d;
    border: 1px solid #dee2e6;
}

.badge-success {
    background: #38a169;
    color: white;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.2s;
}

.btn-primary {
    background: #3490dc;
    color: white;
    border-color: #3490dc;
}

.btn-primary:hover {
    background: #2779bd;
    border-color: #2779bd;
}

.btn-outline {
    background: white;
    color: #4a5568;
    border-color: #e2e8f0;
}

.btn-outline:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
}

.btn-success {
    background: #38a169;
    color: white;
    border-color: #38a169;
}

.btn-danger {
    background: #e53e3e;
    color: white;
    border-color: #e53e3e;
}

.btn-info {
    background: #4299e1;
    color: white;
    border-color: #4299e1;
}

/* Profile Card */
.profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
    overflow: hidden;
}

.profile-header {
    display: flex;
    padding: 30px;
    gap: 30px;
    align-items: center;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
}

.profile-photo {
    flex-shrink: 0;
}

.passport-photo {
    width: 150px;
    height: 150px;
    border-radius: 8px;
    object-fit: cover;
    border: 3px solid #e2e8f0;
}

.no-photo {
    width: 150px;
    height: 150px;
    border-radius: 8px;
    background: #f8fafc;
    border: 3px dashed #cbd5e0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #a0aec0;
}

.no-photo i {
    font-size: 48px;
    margin-bottom: 10px;
}

.no-photo p {
    margin: 0;
    font-size: 14px;
}

.profile-info {
    flex: 1;
}

.profile-info h2 {
    font-size: 24px;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 5px 0;
}

.middle-name {
    color: #718096;
    font-size: 16px;
    margin: 0 0 15px 0;
}

.rank {
    font-size: 18px;
    color: #4a5568;
    margin: 0 0 15px 0;
}

/* PF Number Display */
.pf-number-display {
    background: #f0fff4;
    border: 2px solid #9ae6b4;
    border-radius: 8px;
    padding: 15px;
    margin: 15px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.pf-number-display i {
    color: #38a169;
    font-size: 20px;
}

.pf-number-display strong {
    color: #2f855a;
}

.pf-number-value {
    font-size: 18px;
    font-weight: bold;
    color: #276749;
    background: white;
    padding: 5px 10px;
    border-radius: 4px;
    border: 1px solid #9ae6b4;
}

.profile-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 15px;
}

.profile-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #718096;
    font-size: 14px;
}

.profile-meta i {
    color: #a0aec0;
}

/* FIX FOR TAB FUNCTIONALITY */
.tabs-header {
    display: flex;
    border-bottom: 2px solid #e2e8f0;
    overflow-x: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 20px;
}

.tabs-header::-webkit-scrollbar {
    height: 4px;
}

.tabs-header::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.tabs-header::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 2px;
}

.tab-button {
    padding: 12px 20px;
    border: none;
    background: none;
    color: #718096;
    font-weight: 500;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    white-space: nowrap;
    font-size: 14px;
}

.tab-button:hover {
    color: #4a5568;
    border-bottom-color: #cbd5e0;
}

.tab-button.active {
    color: #3490dc;
    font-weight: 700;
    border-bottom-color: #3490dc;
    background: none;
}

.tabs-content {
    margin-top: 20px;
}

.tab-pane {
    display: none;
    animation: fadeIn 0.3s ease;
}

.tab-pane.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.tab-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    padding: 30px;
    margin-bottom: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}

.info-item {
    margin-bottom: 0;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item .value {
    margin: 0;
    color: #2d3748;
    font-size: 16px;
    line-height: 1.5;
    min-height: 24px;
}

.text-muted {
    color: #718096 !important;
}

/* Qualifications Section */
.qualifications-section {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.highest-qualification h4,
.additional-qualifications h4,
.professional-certifications h4 {
    font-size: 18px;
    color: #4a5568;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
}

.qualification-card {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
    border-left: 4px solid #3490dc;
    margin-bottom: 20px;
}

.qualification-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.qualification-info h5 {
    margin: 0;
    font-size: 18px;
    color: #2d3748;
    flex: 1;
}

.qualification-info .year {
    background: #3490dc;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
}

.qualifications-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.qualification-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8fafc;
    border-radius: 6px;
    border-left: 3px solid #38c172;
    flex-wrap: wrap;
    gap: 10px;
}

.qualification-name {
    font-weight: 500;
    color: #2d3748;
    flex: 1;
}

.qualification-year {
    background: #e6fffa;
    color: #234e52;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

/* Audit Trail */
.audit-trail {
    margin-bottom: 30px;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.timeline-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline-marker i {
    font-size: 16px;
}

.timeline-content {
    margin-left: 0;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    flex-wrap: wrap;
    gap: 10px;
}

.timeline-header .action {
    font-weight: 600;
    color: #4a5568;
}

.timeline-header .time {
    color: #718096;
    font-size: 14px;
}

.timeline-body {
    color: #4a5568;
}

.timeline-body .details {
    margin: 0 0 5px 0;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #718096;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 20px;
    color: #cbd5e0;
}

.empty-state h4 {
    margin: 0 0 10px 0;
    color: #4a5568;
}

.record-info {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #e2e8f0;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 15px;
    padding: 20px 0;
    border-top: 1px solid #e2e8f0;
    justify-content: center;
    flex-wrap: wrap;
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
    overflow: hidden;
    outline: 0;
}

.modal.show {
    display: block;
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
}

@media (min-width: 576px) {
    .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
    }
}

.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.3rem;
    outline: 0;
}

.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #dee2e6;
    border-top-left-radius: calc(0.3rem - 1px);
    border-top-right-radius: calc(0.3rem - 1px);
}

.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
}

.modal-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    padding: 0.75rem;
    border-top: 1px solid #dee2e6;
    border-bottom-right-radius: calc(0.3rem - 1px);
    border-bottom-left-radius: calc(0.3rem - 1px);
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #000;
    z-index: 1040;
}

.modal-backdrop.show {
    opacity: 0.5;
}

/* Alert styles */
.alert {
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.alert-danger {
    background-color: #fed7d7;
    border: 1px solid #fc8181;
    color: #c53030;
}

/* Print Styles */
@media print {
    .view-employee-container {
        padding: 0;
        max-width: none;
    }
    
    .header-actions,
    .action-buttons,
    .btn,
    .tabs-header,
    .modal,
    .no-print {
        display: none !important;
    }
    
    .profile-card,
    .tab-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
        page-break-inside: avoid;
        margin-bottom: 20px !important;
    }
    
    .profile-header {
        display: flex;
        align-items: flex-start;
        padding: 20px;
    }
    
    .pf-number-display {
        background: none !important;
        border: 2px solid #000 !important;
        padding: 10px;
        margin: 10px 0;
    }
    
    .pf-number-value {
        background: none !important;
        border: none !important;
        font-size: 16px !important;
    }
    
    .tab-pane {
        display: block !important;
        opacity: 1 !important;
        page-break-inside: avoid;
    }
    
    .info-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    
    /* Print-specific spacing */
    .page-header {
        margin-bottom: 20px !important;
    }
    
    .header-title h1 {
        font-size: 24px !important;
    }
    
    .header-title .subtitle {
        font-size: 14px !important;
    }
    
    /* Ensure proper page breaks */
    .section-title {
        page-break-after: avoid;
    }
    
    .info-item {
        page-break-inside: avoid;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .view-employee-container {
        padding: 15px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-actions {
        justify-content: flex-start;
    }
    
    .profile-header {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }
    
    .profile-meta {
        justify-content: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
        justify-content: center;
    }
    
    .modal-dialog {
        margin: 10px;
    }
    
    .pf-number-display {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    /* Mobile tabs */
    .tabs-header {
        flex-wrap: nowrap;
    }
    
    .tab-button {
        padding: 10px 15px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .header-title h1 {
        font-size: 24px;
    }
    
    .profile-info h2 {
        font-size: 20px;
    }
    
    .passport-photo,
    .no-photo {
        width: 120px;
        height: 120px;
    }
    
    .tab-button {
        padding: 8px 12px;
        font-size: 12px;
    }
    
    .btn {
        padding: 8px 16px;
        font-size: 14px;
    }
}
</style>