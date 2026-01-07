<?php
/**
 * Bulk Upload View
 * Upload multiple employees via CSV/Excel file
 */
?>
<div class="bulk-upload-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1>Bulk Upload Employees</h1>
                <p class="subtitle">Upload multiple employee records via CSV/Excel file</p>
            </div>
            <div class="header-actions">
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Upload Stats -->
    <div class="upload-stats">
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($totalEmployees ?? 0); ?></h3>
                    <p>Total Employees</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-file-upload"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($lastUploadCount ?? 0); ?></h3>
                    <p>Last Upload</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fas fa-history"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo !empty($lastUploadDate) ? date('M d', strtotime($lastUploadDate)) : 'Never'; ?></h3>
                    <p>Last Upload Date</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($duplicateCount ?? 0); ?></h3>
                    <p>Possible Duplicates</p>
                </div>
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

    <!-- Upload Section -->
    <div class="upload-section">
        <div class="upload-container">
            <!-- Left: Upload Form -->
            <div class="upload-form-card">
                <div class="card-header">
                    <h3><i class="fas fa-cloud-upload-alt"></i> Upload File</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/process-bulk-upload" enctype="multipart/form-data" id="uploadForm">
                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="upload-area" id="uploadArea">
                            <div class="upload-icon">
                                <i class="fas fa-file-excel"></i>
                            </div>
                            <h4>Drag & drop your file here</h4>
                            <p class="upload-subtitle">or click to browse</p>
                            <input type="file" 
                                   id="uploadFile" 
                                   name="upload_file" 
                                   accept=".csv,.xlsx,.xls"
                                   class="file-input">
                            <p class="file-types">Supported formats: CSV, Excel (.xlsx, .xls)</p>
                            <p class="file-size">Max file size: 10MB</p>
                        </div>
                        
                        <div class="selected-file" id="selectedFile" style="display: none;">
                            <div class="file-info">
                                <i class="fas fa-file-excel text-success"></i>
                                <div class="file-details">
                                    <span class="file-name" id="fileName">No file selected</span>
                                    <span class="file-size" id="fileSize">0 KB</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline" id="removeFile">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="upload-options">
                            <div class="form-group">
                                <label for="upload_mode">
                                    <input type="checkbox" id="upload_mode" name="update_existing" value="1">
                                    Update existing records
                                </label>
                                <small class="form-text">If checked, will update existing employees with matching employee numbers</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="skip_duplicates">
                                    <input type="checkbox" id="skip_duplicates" name="skip_duplicates" value="1" checked>
                                    Skip duplicates
                                </label>
                                <small class="form-text">Skip records with duplicate employee numbers</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="send_notifications">
                                    <input type="checkbox" id="send_notifications" name="send_notifications" value="1">
                                    Send email notifications
                                </label>
                                <small class="form-text">Send email notifications for new employees (if email is provided)</small>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg" id="uploadBtn" disabled>
                                <i class="fas fa-upload"></i> Process Upload
                            </button>
                            <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/download-template" class="btn btn-success btn-lg">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Right: Instructions -->
            <div class="instructions-card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Instructions</h3>
                </div>
                <div class="card-body">
                    <div class="instructions">
                        <div class="instruction-step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h5>Download Template</h5>
                                <p>Download the Excel template to ensure correct formatting</p>
                            </div>
                        </div>
                        
                        <div class="instruction-step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h5>Fill Data</h5>
                                <p>Fill in employee data. Required fields are marked with *</p>
                            </div>
                        </div>
                        
                        <div class="instruction-step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h5>Upload File</h5>
                                <p>Upload the completed file. System will validate and process</p>
                            </div>
                        </div>
                        
                        <div class="instruction-step">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h5>Review Results</h5>
                                <p>Check upload summary and fix any errors if needed</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="required-fields">
                        <h5>Required Fields (*)</h5>
                        <div class="fields-list">
                            <span class="badge badge-primary">employee_number</span>
                            <span class="badge badge-primary">surname</span>
                            <span class="badge badge-primary">first_name</span>
                            <span class="badge badge-primary">sex</span>
                            <span class="badge badge-primary">date_of_birth</span>
                            <span class="badge badge-primary">marital_status</span>
                            <span class="badge badge-primary">rank</span>
                            <span class="badge badge-primary">grade_level</span>
                            <span class="badge badge-primary">highest_qualification</span>
                            <span class="badge badge-primary">year_of_highest_qualification</span>
                            <span class="badge badge-primary">date_of_first_appointment</span>
                            <span class="badge badge-primary">state</span>
                            <span class="badge badge-primary">local_govt_area</span>
                        </div>
                    </div>
                    
                    <div class="tips">
                        <h5><i class="fas fa-lightbulb"></i> Tips</h5>
                        <ul>
                            <li>Use YYYY-MM-DD format for dates</li>
                            <li>Sex should be "Male" or "Female"</li>
                            <li>Grade Level should be a number (1-17)</li>
                            <li>For additional qualifications, separate with semicolons</li>
                            <li>Save file as CSV for best compatibility</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload History -->
    <?php if (!empty($uploadHistory)): ?>
    <div class="upload-history">
        <div class="section-header">
            <h3><i class="fas fa-history"></i> Recent Uploads</h3>
        </div>
        
        <div class="history-table">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>File Name</th>
                        <th>Records</th>
                        <th>Success</th>
                        <th>Errors</th>
                        <th>Status</th>
                        <th>Uploaded By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($uploadHistory as $history): ?>
                    <tr>
                        <td><?php echo date('M d, Y H:i', strtotime($history['created_at'])); ?></td>
                        <td><?php echo htmlspecialchars($history['file_name']); ?></td>
                        <td><?php echo $history['total_records']; ?></td>
                        <td>
                            <span class="text-success">
                                <i class="fas fa-check"></i> <?php echo $history['success_count']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($history['error_count'] > 0): ?>
                            <span class="text-danger">
                                <i class="fas fa-times"></i> <?php echo $history['error_count']; ?>
                            </span>
                            <?php else: ?>
                            <span class="text-success">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($history['status'] === 'completed'): ?>
                            <span class="badge badge-success">Completed</span>
                            <?php elseif ($history['status'] === 'processing'): ?>
                            <span class="badge badge-info">Processing</span>
                            <?php elseif ($history['status'] === 'failed'): ?>
                            <span class="badge badge-danger">Failed</span>
                            <?php else: ?>
                            <span class="badge badge-warning"><?php echo ucfirst($history['status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($history['user_name']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Validation Results Modal -->
    <div class="modal fade" id="validationModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Validation Results</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="validationResults">
                        <!-- Results will be displayed here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirmUpload">Confirm Upload</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('uploadFile');
    const selectedFile = document.getElementById('selectedFile');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFileBtn = document.getElementById('removeFile');
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadForm = document.getElementById('uploadForm');
    
    // File upload area click
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });
    
    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        uploadArea.classList.add('highlight');
    }

    function unhighlight() {
        uploadArea.classList.remove('highlight');
    }

    uploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFileSelect(files[0]);
    }
    
    // File input change
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            handleFileSelect(this.files[0]);
        }
    });
    
    function handleFileSelect(file) {
        // Check file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            resetFileInput();
            return;
        }
        
        // Check file type
        const validTypes = [
            'text/csv',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
        const validExtensions = ['.csv', '.xls', '.xlsx'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!validTypes.includes(file.type) && !validExtensions.includes(fileExtension)) {
            alert('Only CSV and Excel files are allowed');
            resetFileInput();
            return;
        }
        
        // Update UI
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        selectedFile.style.display = 'block';
        uploadBtn.disabled = false;
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Remove file
    removeFileBtn.addEventListener('click', function() {
        resetFileInput();
    });
    
    function resetFileInput() {
        fileInput.value = '';
        selectedFile.style.display = 'none';
        uploadBtn.disabled = true;
        fileName.textContent = 'No file selected';
        fileSize.textContent = '0 KB';
    }
    
    // Form submission with validation
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!fileInput.files || !fileInput.files[0]) {
            alert('Please select a file to upload');
            return;
        }
        
        // Show loading state
        const originalText = uploadBtn.innerHTML;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        uploadBtn.disabled = true;
        
        // Create FormData for AJAX upload
        const formData = new FormData(this);
        
        // Send AJAX request for validation
        fetch('<?php echo $baseUrl; ?>/admin/nominal-roll/validate-bulk-upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Reset button
            uploadBtn.innerHTML = originalText;
            uploadBtn.disabled = false;
            
            if (data.success) {
                // Show validation results
                showValidationResults(data);
            } else {
                alert(data.message || 'Validation failed');
            }
        })
        .catch(error => {
            // Reset button
            uploadBtn.innerHTML = originalText;
            uploadBtn.disabled = false;
            console.error('Error:', error);
            alert('An error occurred during validation');
        });
    });
    
    function showValidationResults(data) {
        const resultsContainer = document.getElementById('validationResults');
        const modal = new bootstrap.Modal(document.getElementById('validationModal'));
        
        let html = `
            <div class="validation-summary">
                <div class="summary-cards">
                    <div class="summary-card total">
                        <h4>${data.total_records}</h4>
                        <p>Total Records</p>
                    </div>
                    <div class="summary-card valid">
                        <h4>${data.valid_records}</h4>
                        <p>Valid Records</p>
                    </div>
                    <div class="summary-card errors">
                        <h4>${data.error_count}</h4>
                        <p>Errors Found</p>
                    </div>
                </div>
        `;
        
        if (data.errors && data.errors.length > 0) {
            html += `
                <div class="errors-list">
                    <h5><i class="fas fa-exclamation-triangle text-danger"></i> Errors Found:</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Row</th>
                                    <th>Field</th>
                                    <th>Error</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            
            data.errors.forEach(error => {
                html += `
                    <tr>
                        <td>${error.row}</td>
                        <td>${error.field}</td>
                        <td><span class="text-danger">${error.message}</span></td>
                        <td><code>${error.value || ''}</code></td>
                    </tr>
                `;
            });
            
            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
        
        if (data.duplicates && data.duplicates.length > 0) {
            html += `
                <div class="duplicates-list">
                    <h5><i class="fas fa-clone text-warning"></i> Possible Duplicates:</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Employee Number</th>
                                    <th>Name</th>
                                    <th>Existing Record</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            
            data.duplicates.forEach(dup => {
                html += `
                    <tr>
                        <td>${dup.employee_number}</td>
                        <td>${dup.name}</td>
                        <td>${dup.exists ? 'Yes' : 'No'}</td>
                    </tr>
                `;
            });
            
            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
        
        if (data.error_count === 0) {
            html += `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    All records are valid and ready for upload!
                </div>
            `;
        } else {
            html += `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Found ${data.error_count} errors. Please fix them before proceeding.
                </div>
            `;
        }
        
        html += `</div>`;
        resultsContainer.innerHTML = html;
        
        // Show modal
        modal.show();
        
        // Handle confirm upload button
        const confirmBtn = document.getElementById('confirmUpload');
        if (data.error_count === 0) {
            confirmBtn.disabled = false;
            confirmBtn.addEventListener('click', function() {
                // Submit the form for real upload
                uploadForm.removeEventListener('submit', arguments.callee);
                uploadForm.submit();
            });
        } else {
            confirmBtn.disabled = true;
            confirmBtn.title = 'Fix errors before uploading';
        }
    }
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltips = document.querySelectorAll('[data-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    }
});
</script>

<style>
.bulk-upload-container {
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
    margin: 0;
}

/* Upload Stats */
.upload-stats {
    margin-bottom: 30px;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon.bg-primary { background: #3490dc; }
.stat-icon.bg-success { background: #38a169; }
.stat-icon.bg-info { background: #4299e1; }
.stat-icon.bg-warning { background: #d69e2e; }

.stat-content h3 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: #2d3748;
}

.stat-content p {
    font-size: 14px;
    color: #718096;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Upload Section */
.upload-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    margin-bottom: 40px;
}

@media (max-width: 1024px) {
    .upload-container {
        grid-template-columns: 1fr;
    }
}

.upload-form-card,
.instructions-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    overflow: hidden;
}

.card-header {
    padding: 20px 30px;
    background: linear-gradient(135deg, #3490dc 0%, #2779bd 100%);
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

/* Upload Area */
.upload-area {
    border: 3px dashed #cbd5e0;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 20px;
    position: relative;
}

.upload-area:hover {
    border-color: #3490dc;
    background: #edf2f7;
}

.upload-area.highlight {
    border-color: #3490dc;
    background: #e6fffa;
}

.upload-icon {
    font-size: 48px;
    color: #3490dc;
    margin-bottom: 15px;
}

.upload-area h4 {
    font-size: 18px;
    color: #4a5568;
    margin: 0 0 8px 0;
}

.upload-subtitle {
    color: #718096;
    font-size: 14px;
    margin: 0 0 15px 0;
}

.file-types,
.file-size {
    font-size: 12px;
    color: #a0aec0;
    margin: 5px 0 0 0;
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

/* Selected File */
.selected-file {
    margin-bottom: 20px;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f0fff4;
    border: 1px solid #9ae6b4;
    border-radius: 8px;
}

.file-info i {
    font-size: 24px;
}

.file-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.file-name {
    font-weight: 600;
    color: #22543d;
}

.file-size {
    font-size: 12px;
    color: #38a169;
}

/* Upload Options */
.upload-options {
    margin-bottom: 30px;
}

.upload-options .form-group {
    margin-bottom: 15px;
}

.upload-options label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    color: #4a5568;
    cursor: pointer;
    margin: 0;
}

.upload-options input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.upload-options .form-text {
    margin-left: 26px;
    font-size: 12px;
    color: #718096;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 15px;
}

.form-actions .btn {
    flex: 1;
}

/* Instructions */
.instructions {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.instruction-step {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.step-number {
    width: 30px;
    height: 30px;
    background: #3490dc;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

.step-content h5 {
    margin: 0 0 5px 0;
    font-size: 14px;
    color: #2d3748;
}

.step-content p {
    margin: 0;
    font-size: 13px;
    color: #718096;
    line-height: 1.5;
}

.required-fields {
    margin-bottom: 30px;
}

.required-fields h5 {
    font-size: 14px;
    color: #4a5568;
    margin: 0 0 10px 0;
}

.fields-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 4px;
}

.badge-primary {
    background: #ebf8ff;
    color: #2b6cb0;
    border: 1px solid #bee3f8;
}

.tips h5 {
    font-size: 14px;
    color: #4a5568;
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tips h5 i {
    color: #d69e2e;
}

.tips ul {
    margin: 0;
    padding-left: 20px;
}

.tips li {
    font-size: 13px;
    color: #718096;
    margin-bottom: 5px;
    line-height: 1.4;
}

/* Upload History */
.upload-history {
    margin-top: 40px;
}

.section-header {
    margin-bottom: 20px;
}

.section-header h3 {
    font-size: 20px;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.history-table {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    overflow: hidden;
}

.history-table table {
    width: 100%;
    border-collapse: collapse;
}

.history-table thead {
    background: #f7fafc;
}

.history-table th {
    padding: 15px 20px;
    text-align: left;
    font-weight: 600;
    color: #4a5568;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
}

.history-table td {
    padding: 15px 20px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
    color: #4a5568;
}

.history-table tbody tr:hover {
    background: #f8fafc;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1050;
}

.modal.show {
    display: block;
}

.modal-dialog {
    max-width: 800px;
    margin: 30px auto;
}

.modal-content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-header {
    padding: 20px 30px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
}

.modal-body {
    padding: 30px;
    max-height: 60vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 20px 30px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* Validation Results */
.validation-summary {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.summary-card {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    border-top: 4px solid #e2e8f0;
}

.summary-card.total {
    border-top-color: #3490dc;
}

.summary-card.valid {
    border-top-color: #38a169;
}

.summary-card.errors {
    border-top-color: #e53e3e;
}

.summary-card h4 {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 5px 0;
}

.summary-card.total h4 { color: #3490dc; }
.summary-card.valid h4 { color: #38a169; }
.summary-card.errors h4 { color: #e53e3e; }

.summary-card p {
    margin: 0;
    font-size: 12px;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.errors-list h5,
.duplicates-list h5 {
    font-size: 16px;
    color: #4a5568;
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th {
    padding: 10px 12px;
    text-align: left;
    font-weight: 600;
    color: #4a5568;
    font-size: 12px;
    background: #f7fafc;
    border-bottom: 2px solid #e2e8f0;
}

.table td {
    padding: 10px 12px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 13px;
}

.table-sm th,
.table-sm td {
    padding: 8px 10px;
}

.table tbody tr:hover {
    background: #f8fafc;
}

/* Buttons */
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
    background: linear-gradient(135deg, #3490dc 0%, #2779bd 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px rgba(52, 144, 220, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
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
    background: #e53e3e;
    color: white;
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

.alert-warning {
    background: #fffaf0;
    border: 2px solid #feebc8;
    color: #9c4221;
}

.alert i {
    font-size: 18px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .bulk-upload-container {
        padding: 15px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .stats-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
    
    .summary-cards {
        grid-template-columns: 1fr;
    }
    
    .modal-dialog {
        margin: 10px;
    }
}

@media (max-width: 480px) {
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .header-title h1 {
        font-size: 24px;
    }
    
    .upload-area {
        padding: 30px 15px;
    }
    
    .upload-area h4 {
        font-size: 16px;
    }
}
</style>