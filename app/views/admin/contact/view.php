<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 4);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
$currentUserId = $_SESSION['user_id'] ?? 0;
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Submission - FCT CNS Admin</title>
    <style>
        :root {
            --admin-primary: #2c5282;
            --admin-primary-dark: #1a365d;
            --admin-success: #38a169;
            --admin-warning: #d69e2e;
            --admin-danger: #e53e3e;
            --admin-info: #3182ce;
            --admin-gray-50: #f7fafc;
            --admin-gray-100: #edf2f7;
            --admin-gray-200: #e2e8f0;
            --admin-gray-600: #718096;
            --admin-gray-700: #4a5568;
            --admin-gray-800: #2d3748;
        }
        
        .submission-view {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-header h1 {
            color: var(--admin-gray-800);
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
            background: var(--admin-primary);
            color: white;
        }
        
        .btn-secondary {
            background: var(--admin-gray-600);
            color: white;
        }
        
        .btn-success {
            background: var(--admin-success);
            color: white;
        }
        
        .btn-warning {
            background: var(--admin-warning);
            color: white;
        }
        
        .btn-danger {
            background: var(--admin-danger);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        /* Submission Info */
        .submission-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--admin-primary);
        }
        
        .info-card.sender { border-left-color: var(--admin-info); }
        .info-card.details { border-left-color: var(--admin-success); }
        .info-card.metadata { border-left-color: var(--admin-gray-600); }
        
        .info-card h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--admin-gray-800);
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-item {
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--admin-gray-100);
        }
        
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--admin-gray-700);
            font-size: 0.875rem;
            margin-bottom: 4px;
        }
        
        .info-value {
            color: var(--admin-gray-800);
            font-size: 0.95rem;
        }
        
        /* Message Container */
        .message-container {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .message-container h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--admin-gray-800);
            border-bottom: 2px solid var(--admin-gray-200);
            padding-bottom: 10px;
        }
        
        .message-content {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--admin-gray-800);
            white-space: pre-wrap;
            padding: 15px;
            background: var(--admin-gray-50);
            border-radius: 6px;
            border: 1px solid var(--admin-gray-200);
        }
        
        /* Status Badge */
        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-pending { 
            background: rgba(214, 158, 46, 0.1); 
            color: var(--admin-warning); 
            border: 1px solid rgba(214, 158, 46, 0.2);
        }
        
        .status-responded { 
            background: rgba(56, 161, 105, 0.1); 
            color: var(--admin-success); 
            border: 1px solid rgba(56, 161, 105, 0.2);
        }
        
        .status-archived { 
            background: rgba(113, 128, 150, 0.1); 
            color: var(--admin-gray-600); 
            border: 1px solid rgba(113, 128, 150, 0.2);
        }
        
        /* Admin Actions */
        .admin-actions {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .admin-actions h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--admin-gray-800);
            border-bottom: 2px solid var(--admin-gray-200);
            padding-bottom: 10px;
        }
        
        .action-form {
            display: grid;
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--admin-gray-700);
            font-size: 0.875rem;
        }
        
        .form-group select,
        .form-group textarea {
            padding: 12px;
            border: 1px solid var(--admin-gray-200);
            border-radius: 6px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
            font-family: inherit;
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }
        
        /* Admin Notes */
        .admin-notes {
            background: var(--admin-gray-50);
            border-radius: 6px;
            padding: 15px;
            margin-top: 10px;
            border: 1px solid var(--admin-gray-200);
        }
        
        .admin-notes h4 {
            margin-top: 0;
            margin-bottom: 10px;
            color: var(--admin-gray-700);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .notes-content {
            font-size: 0.9rem;
            line-height: 1.5;
            color: var(--admin-gray-700);
            white-space: pre-wrap;
        }
        
        .notes-empty {
            color: var(--admin-gray-500);
            font-style: italic;
        }
        
        /* Quick Reply */
        .quick-reply {
            background: #f0f9ff;
            border: 1px solid #bee3f8;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .quick-reply h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--admin-primary);
            font-size: 1rem;
        }
        
        .reply-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .reply-btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 0.875rem;
            cursor: pointer;
            border: 1px solid var(--admin-gray-300);
            background: white;
            transition: all 0.2s;
        }
        
        .reply-btn:hover {
            background: var(--admin-gray-50);
            border-color: var(--admin-gray-400);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header { flex-direction: column; align-items: stretch; }
            .btn-group { justify-content: center; }
            .submission-info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="submission-view">
        <div class="page-header">
            <h1>📨 Contact Submission #<?php echo htmlspecialchars($submission['id']); ?></h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/contact" class="btn btn-secondary">
                    ← Back to List
                </a>
                <a href="mailto:<?php echo htmlspecialchars($submission['email']); ?>" class="btn btn-primary">
                    ✉️ Reply via Email
                </a>
                <?php if ($submission['status'] === 'pending'): ?>
                <button class="btn btn-success" onclick="markAsResponded()">
                    ✅ Mark as Responded
                </button>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Submission Information -->
        <div class="submission-info-grid">
            <!-- Sender Information -->
            <div class="info-card sender">
                <h3>👤 Sender Information</h3>
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($submission['name']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">
                        <a href="mailto:<?php echo htmlspecialchars($submission['email']); ?>">
                            <?php echo htmlspecialchars($submission['email']); ?>
                        </a>
                    </div>
                </div>
                <?php if (!empty($submission['phone'])): ?>
                <div class="info-item">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">
                        <a href="tel:<?php echo htmlspecialchars($submission['phone']); ?>">
                            <?php echo htmlspecialchars($submission['phone']); ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Submission Details -->
            <div class="info-card details">
                <h3>📋 Submission Details</h3>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <?php if ($submission['status'] === 'pending'): ?>
                        <span class="status-badge status-pending">Pending</span>
                        <?php elseif ($submission['status'] === 'responded'): ?>
                        <span class="status-badge status-responded">Responded</span>
                        <?php else: ?>
                        <span class="status-badge status-archived">Archived</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Department</div>
                    <div class="info-value"><?php echo ucfirst(htmlspecialchars($submission['department'])); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Subject</div>
                    <div class="info-value"><?php echo htmlspecialchars($submission['subject']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Submitted</div>
                    <div class="info-value">
                        <?php echo date('F j, Y \a\t g:i A', strtotime($submission['created_at'])); ?>
                    </div>
                </div>
                <?php if ($submission['responded_at']): ?>
                <div class="info-item">
                    <div class="info-label">Responded</div>
                    <div class="info-value">
                        <?php echo date('F j, Y \a\t g:i A', strtotime($submission['responded_at'])); ?>
                        <?php if (!empty($submission['responder_name'])): ?>
                        <br><small>by <?php echo htmlspecialchars($submission['responder_name']); ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Technical Metadata -->
            <div class="info-card metadata">
                <h3>🔧 Technical Details</h3>
                <div class="info-item">
                    <div class="info-label">IP Address</div>
                    <div class="info-value"><?php echo htmlspecialchars($submission['ip_address'] ?? 'Not recorded'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">User Agent</div>
                    <div class="info-value" style="font-size: 0.8rem; color: var(--admin-gray-600);">
                        <?php echo htmlspecialchars(substr($submission['user_agent'] ?? 'Unknown', 0, 80)); ?>...
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Submission ID</div>
                    <div class="info-value"><?php echo htmlspecialchars($submission['id']); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Message Content -->
        <div class="message-container">
            <h2>📝 Message</h2>
            <div class="message-content">
                <?php echo nl2br(htmlspecialchars($submission['message'])); ?>
            </div>
        </div>
        
        <!-- Admin Actions Section -->
        <div class="admin-actions" id="respond">
            <h2>⚡ Admin Actions</h2>
            
            <!-- Current Admin Notes -->
            <?php if (!empty($submission['admin_notes'])): ?>
            <div class="admin-notes">
                <h4>Current Admin Notes</h4>
                <div class="notes-content">
                    <?php echo nl2br(htmlspecialchars($submission['admin_notes'])); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Update Form -->
            <form method="POST" action="<?php echo BASE_URL; ?>/admin/contact/update/<?php echo $submission['id']; ?>" 
                  class="action-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="status">Update Status</label>
                    <select id="status" name="status" required>
                        <option value="pending" <?php echo $submission['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="responded" <?php echo $submission['status'] === 'responded' ? 'selected' : ''; ?>>Responded</option>
                        <option value="archived" <?php echo $submission['status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="admin_notes">Admin Notes</label>
                    <textarea id="admin_notes" name="admin_notes" 
                              placeholder="Add internal notes about this submission (not visible to sender)..."><?php 
                        echo htmlspecialchars($submission['admin_notes'] ?? ''); 
                    ?></textarea>
                    <div style="font-size: 0.75rem; color: var(--admin-gray-600); margin-top: 5px;">
                        These notes are for internal use only.
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        💾 Update Submission
                    </button>
                </div>
            </form>
            
            <!-- Quick Reply Actions -->
            <?php if ($submission['status'] === 'pending'): ?>
            <div class="quick-reply">
                <h3>💬 Quick Reply Templates</h3>
                <div class="reply-buttons">
                    <button class="reply-btn" onclick="useTemplate('acknowledge')">
                        Acknowledgment
                    </button>
                    <button class="reply-btn" onclick="useTemplate('admissions')">
                        Admissions Info
                    </button>
                    <button class="reply-btn" onclick="useTemplate('schedule')">
                        Schedule Meeting
                    </button>
                    <button class="reply-btn" onclick="useTemplate('thanks')">
                        Thank You
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Danger Zone -->
        <?php if (in_array($userRole, ['admin', 'editor'])): ?>
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 20px; margin-top: 30px;">
            <h3 style="margin-top: 0; color: #dc2626;">⚠️ Danger Zone</h3>
            <p style="color: #7f1d1d; margin-bottom: 15px;">
                These actions cannot be undone. Please proceed with caution.
            </p>
            <form method="POST" 
                  action="<?php echo BASE_URL; ?>/admin/contact/delete/<?php echo $submission['id']; ?>" 
                  onsubmit="return confirm('Are you sure you want to delete this submission? This action cannot be undone.')">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <button type="submit" class="btn btn-danger">
                    🗑️ Delete Submission
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Mark as responded quickly
        function markAsResponded() {
            document.getElementById('status').value = 'responded';
            document.querySelector('.action-form').submit();
        }
        
        // Quick reply templates
        function useTemplate(type) {
            const templates = {
                acknowledge: "Dear <?php echo htmlspecialchars($submission['name']); ?>,\n\nThank you for contacting FCT College of Nursing Sciences. We have received your inquiry and will review it shortly. A member of our team will respond to you within 24-48 hours during working days.\n\nBest regards,\nThe FCT CNS Team",
                
                admissions: "Dear <?php echo htmlspecialchars($submission['name']); ?>,\n\nThank you for your interest in our nursing programs. Our admissions requirements and application process are detailed on our website. You can find more information here: <?php echo BASE_URL; ?>/admissions\n\nFor specific questions about your eligibility, please provide your academic qualifications and we'll be happy to assist you further.\n\nBest regards,\nAdmissions Office",
                
                schedule: "Dear <?php echo htmlspecialchars($submission['name']); ?>,\n\nThank you for your inquiry. We would be happy to schedule a meeting or campus tour for you. Please let us know your preferred dates and times, and we'll coordinate accordingly.\n\nBest regards,\nThe FCT CNS Team",
                
                thanks: "Dear <?php echo htmlspecialchars($submission['name']); ?>,\n\nThank you for your message and your interest in FCT College of Nursing Sciences. We appreciate you taking the time to reach out to us.\n\nBest regards,\nThe FCT CNS Team"
            };
            
            // Open email client with template
            const subject = "RE: <?php echo rawurlencode($submission['subject']); ?>";
            const body = templates[type];
            const mailtoLink = `mailto:<?php echo rawurlencode($submission['email']); ?>?subject=${subject}&body=${encodeURIComponent(body)}`;
            
            window.open(mailtoLink);
            
            // Auto-mark as responded
            if (confirm('Mark this submission as responded?')) {
                document.getElementById('status').value = 'responded';
                document.getElementById('admin_notes').value = `Responded using ${type} template on ${new Date().toLocaleString()}`;
                document.querySelector('.action-form').submit();
            }
        }
        
        // Auto-save notes
        let notesTimeout;
        document.getElementById('admin_notes').addEventListener('input', function() {
            clearTimeout(notesTimeout);
            notesTimeout = setTimeout(function() {
                // In a real implementation, this would auto-save via AJAX
                console.log('Auto-saving notes...');
            }, 2000);
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + Enter to submit
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                document.querySelector('.action-form').submit();
            }
            
            // Esc to go back
            if (e.key === 'Escape') {
                window.location.href = '<?php echo BASE_URL; ?>/admin/contact';
            }
            
            // Ctrl/Cmd + M to mark as responded
            if ((e.ctrlKey || e.metaKey) && e.key === 'm' && <?php echo $submission['status'] === 'pending' ? 'true' : 'false'; ?>) {
                e.preventDefault();
                markAsResponded();
            }
        });
        
        // Print functionality
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>