<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 3);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details - FCT CNS Admin</title>
    <style>
        :root {
            --primary: #2c5282;
            --primary-dark: #1a365d;
            --success: #38a169;
            --warning: #d69e2e;
            --danger: #e53e3e;
            --info: #4299e1;
            --gray-50: #f7fafc;
            --gray-100: #edf2f7;
            --gray-200: #e2e8f0;
            --gray-600: #718096;
            --gray-700: #4a5568;
            --gray-800: #2d3748;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-100);
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .header h1 {
            color: var(--gray-800);
            margin: 0;
            font-size: 1.75rem;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-secondary {
            background: var(--gray-600);
            color: white;
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-warning {
            background: var(--warning);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-info {
            background: var(--info);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary:hover { background: var(--gray-700); }
        
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .application-container {
            background: white;
            border-radius: 8px;
            padding: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .application-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px;
            position: relative;
        }
        
        .application-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .applicant-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .applicant-id {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .status-badge-large {
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .application-body {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .section-title {
            color: var(--primary);
            font-size: 1.25rem;
            margin-top: 0;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--gray-200);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            margin-bottom: 15px;
        }
        
        .info-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
            display: block;
        }
        
        .info-value {
            font-size: 1rem;
            color: var(--gray-800);
            font-weight: 500;
        }
        
        .document-preview {
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            background: var(--gray-50);
        }
        
        .document-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
        
        .document-name {
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .document-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }
        
        .personal-statement {
            background: var(--gray-50);
            border-radius: 6px;
            padding: 20px;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--gray-200);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid white;
            box-shadow: 0 0 0 3px var(--primary);
        }
        
        .timeline-date {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-bottom: 5px;
        }
        
        .timeline-content {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            padding: 15px;
        }
        
        .action-panel {
            background: var(--gray-50);
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .action-panel h3 {
            margin-top: 0;
            color: var(--gray-700);
        }
        
        .status-selector {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .status-btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: 2px solid transparent;
            background: white;
            color: var(--gray-700);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .status-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .status-btn.active {
            border-color: currentColor;
        }
        
        .status-btn.pending { color: var(--warning); }
        .status-btn.reviewed { color: var(--info); }
        .status-btn.accepted { color: var(--success); }
        .status-btn.rejected { color: var(--danger); }
        
        @media (max-width: 768px) {
            .application-header-content {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        
        .note-editor {
            margin-top: 20px;
        }
        
        .note-editor textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            font-size: 14px;
            min-height: 100px;
            resize: vertical;
            margin-bottom: 10px;
        }
        
        .existing-notes {
            margin-top: 30px;
        }
        
        .note-item {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .note-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .note-author {
            font-weight: 600;
            color: var(--primary);
        }
        
        .note-date {
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        
        .note-content {
            line-height: 1.5;
            color: var(--gray-800);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📄 Application Details</h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/applications" class="btn btn-secondary">
                    ← Back to Applications
                </a>
                <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                <a href="<?php echo BASE_URL; ?>/admin/applications/edit/<?php echo $application['id']; ?>" class="btn btn-primary">
                    ✏️ Edit Application
                </a>
                <?php endif; ?>
                <button class="btn btn-info" onclick="window.print()">
                    🖨️ Print
                </button>
            </div>
        </div>
        
        <div class="application-container">
            <!-- Application Header -->
            <div class="application-header">
                <div class="application-header-content">
                    <div>
                        <h1 class="applicant-name">
                            <?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?>
                        </h1>
                        <div class="applicant-id">
                            Application ID: #<?php echo str_pad($application['id'], 6, '0', STR_PAD_LEFT); ?>
                        </div>
                        <div style="margin-top: 10px; font-size: 0.875rem;">
                            📧 <?php echo htmlspecialchars($application['email']); ?> | 
                            📱 <?php echo htmlspecialchars($application['phone']); ?>
                        </div>
                    </div>
                    <div class="status-badge-large">
                        <?php if ($application['status'] == 'pending'): ?>
                            ⏳ Pending Review
                        <?php elseif ($application['status'] == 'reviewed'): ?>
                            🔍 Under Review
                        <?php elseif ($application['status'] == 'accepted'): ?>
                            ✅ Accepted
                        <?php elseif ($application['status'] == 'rejected'): ?>
                            ❌ Rejected
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Application Body -->
            <div class="application-body">
                <!-- Personal Information Section -->
                <div class="section">
                    <h2 class="section-title">👤 Personal Information</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <div class="info-value">
                                <?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email Address</span>
                            <div class="info-value">
                                <?php echo htmlspecialchars($application['email']); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone Number</span>
                            <div class="info-value">
                                <?php echo htmlspecialchars($application['phone']); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Application Date</span>
                            <div class="info-value">
                                <?php echo date('F d, Y', strtotime($application['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Program Information Section -->
                <div class="section">
                    <h2 class="section-title">🎓 Program Information</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Program Applied For</span>
                            <div class="info-value">
                                <?php echo htmlspecialchars($application['program']); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Entry Year</span>
                            <div class="info-value">
                                <?php echo htmlspecialchars($application['entry_year']); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Highest Qualification</span>
                            <div class="info-value">
                                <?php echo htmlspecialchars($application['highest_qualification']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Qualification Document Section -->
                <div class="section">
                    <h2 class="section-title">📄 Qualification Document</h2>
                    <?php if ($application['qualification_file']): ?>
                    <div class="document-preview">
                        <div class="document-icon">📎</div>
                        <div class="document-name">
                            <?php echo basename($application['qualification_file']); ?>
                        </div>
                        <div class="document-actions">
                            <a href="<?php echo BASE_URL . '/' . $application['qualification_file']; ?>" 
                               target="_blank" class="btn btn-primary">
                                👁️ View Document
                            </a>
                            <a href="<?php echo BASE_URL . '/' . $application['qualification_file']; ?>" 
                               download class="btn btn-secondary">
                                ⬇️ Download
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: var(--gray-600);">
                        No qualification document uploaded.
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Personal Statement Section -->
                <div class="section">
                    <h2 class="section-title">💭 Personal Statement</h2>
                    <div class="personal-statement">
                        <?php echo nl2br(htmlspecialchars($application['personal_statement'])); ?>
                    </div>
                </div>
                
                <!-- Application Timeline -->
                <div class="section">
                    <h2 class="section-title">⏰ Application Timeline</h2>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date">
                                <?php echo date('F d, Y', strtotime($application['created_at'])); ?> at <?php echo date('H:i', strtotime($application['created_at'])); ?>
                            </div>
                            <div class="timeline-content">
                                <strong>Application Submitted</strong>
                                <p>Application was submitted through the online portal.</p>
                            </div>
                        </div>
                        
                        <?php if ($application['status'] != 'pending'): ?>
                        <div class="timeline-item">
                            <div class="timeline-date">
                                <?php echo date('F d, Y', strtotime($application['updated_at'])); ?> at <?php echo date('H:i', strtotime($application['updated_at'])); ?>
                            </div>
                            <div class="timeline-content">
                                <strong>Status Updated</strong>
                                <p>Application status changed to <strong><?php echo ucfirst($application['status']); ?></strong>.</p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($application['notes']): ?>
                        <div class="timeline-item">
                            <div class="timeline-date">
                                Last updated
                            </div>
                            <div class="timeline-content">
                                <strong>Admin Notes</strong>
                                <p><?php echo nl2br(htmlspecialchars($application['notes'])); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Admin Actions (for admins and editors) -->
                <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                <div class="action-panel">
                    <h3>🔧 Update Application Status</h3>
                    
                    <div class="status-selector">
                        <button class="status-btn pending <?php echo $application['status'] == 'pending' ? 'active' : ''; ?>" 
                                onclick="updateStatus('pending')">
                            ⏳ Pending
                        </button>
                        <button class="status-btn reviewed <?php echo $application['status'] == 'reviewed' ? 'active' : ''; ?>" 
                                onclick="updateStatus('reviewed')">
                            🔍 Under Review
                        </button>
                        <button class="status-btn accepted <?php echo $application['status'] == 'accepted' ? 'active' : ''; ?>" 
                                onclick="updateStatus('accepted')">
                            ✅ Accepted
                        </button>
                        <button class="status-btn rejected <?php echo $application['status'] == 'rejected' ? 'active' : ''; ?>" 
                                onclick="updateStatus('rejected')">
                            ❌ Rejected
                        </button>
                    </div>
                    
                    <div class="note-editor">
                        <h4>Add Note</h4>
                        <textarea id="adminNote" placeholder="Add notes about this application..."></textarea>
                        <div class="btn-group">
                            <button class="btn btn-primary" onclick="saveStatusWithNote()">
                                💾 Update Status with Note
                            </button>
                            <button class="btn btn-secondary" onclick="saveNoteOnly()">
                                💬 Add Note Only
                            </button>
                        </div>
                    </div>
                    
                    <?php if ($application['notes']): ?>
                    <div class="existing-notes">
                        <h4>Existing Notes</h4>
                        <div class="note-item">
                            <div class="note-header">
                                <span class="note-author">Admin</span>
                                <span class="note-date"><?php echo date('M d, Y', strtotime($application['updated_at'])); ?></span>
                            </div>
                            <div class="note-content">
                                <?php echo nl2br(htmlspecialchars($application['notes'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 30px;">
            <a href="<?php echo BASE_URL; ?>/admin/applications" class="btn btn-secondary">
                ← Back to All Applications
            </a>
            <button class="btn btn-info" onclick="sendEmail()">
                📧 Send Email to Applicant
            </button>
            <button class="btn btn-success" onclick="generateOfferLetter()">
                📝 Generate Offer Letter
            </button>
            <?php if (in_array($userRole, ['admin', 'editor'])): ?>
            <button class="btn btn-danger" onclick="deleteApplication()">
                🗑️ Delete Application
            </button>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Update application status
        function updateStatus(newStatus) {
            const statusText = {
                'pending': 'Pending Review',
                'reviewed': 'Under Review',
                'accepted': 'Accepted',
                'rejected': 'Rejected'
            }[newStatus];
            
            if (confirm(`Change application status to "${statusText}"?`)) {
                fetch(`<?php echo BASE_URL; ?>/admin/api/applications/<?php echo $application['id']; ?>/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        status: newStatus,
                        note: document.getElementById('adminNote').value 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Status updated successfully!');
                        location.reload();
                    } else {
                        alert('Error updating status: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating status. Please try again.');
                });
            }
        }
        
        // Save status with note
        function saveStatusWithNote() {
            const note = document.getElementById('adminNote').value.trim();
            if (!note) {
                alert('Please add a note before updating status.');
                return;
            }
            
            // Get current status or prompt for new one
            const newStatus = prompt('Enter new status (pending/reviewed/accepted/rejected):', '<?php echo $application['status']; ?>');
            if (newStatus && ['pending', 'reviewed', 'accepted', 'rejected'].includes(newStatus)) {
                updateStatus(newStatus);
            }
        }
        
        // Save note only
        function saveNoteOnly() {
            const note = document.getElementById('adminNote').value.trim();
            if (!note) {
                alert('Please enter a note.');
                return;
            }
            
            fetch(`<?php echo BASE_URL; ?>/admin/api/applications/<?php echo $application['id']; ?>/note`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ note: note })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Note added successfully!');
                    location.reload();
                } else {
                    alert('Error adding note: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding note. Please try again.');
            });
        }
        
        // Send email to applicant
        function sendEmail() {
            const subject = prompt('Email subject:', 'Regarding Your Application to FCT College of Nursing Sciences');
            if (subject) {
                const body = prompt('Email body:', 'Dear ' + <?php echo json_encode($application['first_name']); ?> + ',\n\n');
                if (body) {
                    // In a real implementation, this would send an email
                    alert('Email would be sent to: ' + <?php echo json_encode($application['email']); ?>);
                }
            }
        }
        
        // Generate offer letter
        function generateOfferLetter() {
            if (confirm('Generate offer letter for this applicant?')) {
                // In a real implementation, this would generate a PDF
                alert('Offer letter generation would be implemented here');
            }
        }
        
        // Delete application
        function deleteApplication() {
            if (confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
                fetch(`<?php echo BASE_URL; ?>/admin/applications/delete/<?php echo $application['id']; ?>`, {
                    method: 'POST',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Application deleted successfully!');
                        window.location.href = '<?php echo BASE_URL; ?>/admin/applications';
                    } else {
                        alert('Error deleting application: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting application. Please try again.');
                });
            }
        }
        
        // Print styles
        const printStyle = document.createElement('style');
        printStyle.textContent = `
            @media print {
                body * {
                    visibility: hidden;
                }
                .application-container, .application-container * {
                    visibility: visible;
                }
                .application-container {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    box-shadow: none;
                }
                .btn, .action-panel, .status-selector {
                    display: none !important;
                }
            }
        `;
        document.head.appendChild(printStyle);
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + P to print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            
            // Ctrl/Cmd + E to edit
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                window.location.href = '<?php echo BASE_URL; ?>/admin/applications/edit/<?php echo $application['id']; ?>';
            }
            
            // Ctrl/Cmd + B to go back
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                window.location.href = '<?php echo BASE_URL; ?>/admin/applications';
            }
            
            // Escape key to go back
            if (e.key === 'Escape') {
                window.location.href = '<?php echo BASE_URL; ?>/admin/applications';
            }
        });
        
        // Auto-save note as draft
        let noteSaveTimer;
        const noteTextarea = document.getElementById('adminNote');
        if (noteTextarea) {
            noteTextarea.addEventListener('input', function() {
                clearTimeout(noteSaveTimer);
                noteSaveTimer = setTimeout(function() {
                    localStorage.setItem('application_note_draft_<?php echo $application['id']; ?>', noteTextarea.value);
                }, 1000);
            });
            
            // Load saved draft
            const savedDraft = localStorage.getItem('application_note_draft_<?php echo $application['id']; ?>');
            if (savedDraft) {
                noteTextarea.value = savedDraft;
            }
        }
    </script>
</body>
</html>