<?php
// Ensure all mailto array keys exist with defaults
$mailto = $mailto ?? [];
$mailto['link'] = $mailto['link'] ?? '#';
$mailto['gmail_link'] = $mailto['gmail_link'] ?? '#';
$mailto['outlook_link'] = $mailto['outlook_link'] ?? '#';
$mailto['yahoo_link'] = $mailto['yahoo_link'] ?? '#';
$mailto['to'] = $mailto['to'] ?? '';
$mailto['subject'] = $mailto['subject'] ?? '';
$mailto['body'] = $mailto['body'] ?? '';
$mailto['reply_to'] = $mailto['reply_to'] ?? $reply_to_email ?? 'noreply@fctcns.edu.ng';
$mailto['department'] = $mailto['department'] ?? $submission['department'] ?? 'general';

// Get the absolute path to the root
$rootPath = dirname(__DIR__, 4);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
$currentUserId = $_SESSION['user_id'] ?? 0;
$csrf_token = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Submission #<?php echo htmlspecialchars($submission['id'] ?? '0'); ?> - FCT CNS Admin</title>
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
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: var(--admin-gray-100);
            margin: 0;
            padding: 0;
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
            font-size: 14px;
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
        
        .btn-gmail {
            background: #EA4335;
            color: white;
        }
        
        .btn-gmail:hover {
            background: #d33426;
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
            word-break: break-word;
        }
        
        /* Reply Info Banner */
        .reply-info-banner {
            background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
            border-left: 4px solid var(--admin-primary);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .reply-info-content {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .reply-email-badge {
            background: var(--admin-primary);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .reply-department {
            background: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            color: var(--admin-gray-700);
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        /* Email Preview */
        .email-preview {
            background: white;
            border: 1px solid var(--admin-gray-200);
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            font-size: 0.875rem;
        }
        
        .email-preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed var(--admin-gray-200);
        }
        
        .email-preview-label {
            font-weight: 600;
            color: var(--admin-gray-700);
        }
        
        .email-preview-content {
            background: var(--admin-gray-50);
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            white-space: pre-wrap;
            font-size: 0.8125rem;
            max-height: 200px;
            overflow-y: auto;
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
        
        /* Quick Reply Templates */
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
            display: flex;
            align-items: center;
            gap: 8px;
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
        
        /* Copy Notification */
        .copy-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--admin-success);
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: none;
            animation: slideIn 0.3s ease;
            z-index: 1000;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        /* Danger Zone */
        .danger-zone {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header { flex-direction: column; align-items: stretch; }
            .btn-group { justify-content: flex-start; }
            .submission-info-grid { grid-template-columns: 1fr; }
            .reply-info-banner { flex-direction: column; align-items: flex-start; }
            .reply-info-content { width: 100%; }
            .reply-email-badge { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="submission-view">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                📨 Contact Submission #<?php echo htmlspecialchars($submission['id'] ?? '0'); ?>
                <?php 
                $status = $submission['status'] ?? 'pending';
                if ($status === 'pending'): ?>
                    <span class="status-badge status-pending" style="margin-left: 15px;">Pending</span>
                <?php elseif ($status === 'responded'): ?>
                    <span class="status-badge status-responded" style="margin-left: 15px;">Responded</span>
                <?php else: ?>
                    <span class="status-badge status-archived" style="margin-left: 15px;">Archived</span>
                <?php endif; ?>
            </h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/contact" class="btn btn-secondary">
                    ← Back to List
                </a>
                <?php if (($submission['status'] ?? 'pending') !== 'archived'): ?>
                    <!-- Top Gmail Button - FIXED: Now calls replyViaGmail() -->
                    <?php $primaryGmailLink = isset($mailto['gmail_link']) ? $mailto['gmail_link'] : '#'; ?>
                    <a href="<?php echo htmlspecialchars($primaryGmailLink ?? '#', ENT_QUOTES, 'UTF-8'); ?>" 
                       target="_blank"
                       class="btn btn-gmail"
                       onclick="replyViaGmail(<?php echo (int)($submission['id'] ?? 0); ?>); return true;">
                        📧 Reply via Gmail
                    </a>
                    
                    <!-- Email Client Dropdown -->
                    <div style="position: relative; display: inline-block;">
                        <button class="btn btn-primary" id="replyDropdownBtn" onclick="toggleReplyDropdown()" style="display: flex; align-items: center; gap: 8px;">
                            ✉️ Reply via Email <span style="font-size: 12px;">▼</span>
                        </button>
                        
                        <div id="replyDropdown" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 5px; background: white; border: 1px solid var(--admin-gray-200); border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 280px; z-index: 1000;">
                            
                            <!-- Gmail (Primary) -->
                            <?php $gmailLink = isset($mailto['gmail_link']) ? $mailto['gmail_link'] : '#'; ?>
                            <a href="<?php echo htmlspecialchars($gmailLink ?? '#', ENT_QUOTES, 'UTF-8'); ?>" 
                               target="_blank"
                               style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--admin-gray-800); border-bottom: 1px solid var(--admin-gray-100); transition: background 0.2s; background: #fef3c7; border-radius: 8px 8px 0 0;"
                               onmouseover="this.style.background='#fde68a'"
                               onmouseout="this.style.background='#fef3c7'"
                               onclick="trackReply('gmail', <?php echo (int)($submission['id'] ?? 0); ?>); toggleReplyDropdown();">
                                <span style="font-size: 22px;">📧</span>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #92400e;">Gmail (Recommended)</div>
                                    <div style="font-size: 12px; color: #6b7280;">Open in Gmail web</div>
                                </div>
                            </a>
                            
                            <!-- Outlook.com -->
                            <?php $outlookLink = isset($mailto['outlook_link']) ? $mailto['outlook_link'] : '#'; ?>
                            <a href="<?php echo htmlspecialchars($outlookLink ?? '#', ENT_QUOTES, 'UTF-8'); ?>" 
                               target="_blank"
                               style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; color: var(--admin-gray-800); border-bottom: 1px solid var(--admin-gray-100); transition: background 0.2s;"
                               onmouseover="this.style.background='var(--admin-gray-50)'"
                               onmouseout="this.style.background='white'"
                               onclick="trackReply('outlook', <?php echo (int)($submission['id'] ?? 0); ?>); toggleReplyDropdown();">
                                <span style="font-size: 20px;">📨</span>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600;">Outlook.com</div>
                                    <div style="font-size: 12px; color: var(--admin-gray-600);">Open in Outlook web</div>
                                </div>
                            </a>
                            
                            <!-- Yahoo Mail -->
                            <?php $yahooLink = isset($mailto['yahoo_link']) ? $mailto['yahoo_link'] : '#'; ?>
                            <a href="<?php echo htmlspecialchars($yahooLink ?? '#', ENT_QUOTES, 'UTF-8'); ?>" 
                               target="_blank"
                               style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; color: var(--admin-gray-800); border-bottom: 1px solid var(--admin-gray-100); transition: background 0.2s;"
                               onmouseover="this.style.background='var(--admin-gray-50)'"
                               onmouseout="this.style.background='white'"
                               onclick="trackReply('yahoo', <?php echo (int)($submission['id'] ?? 0); ?>); toggleReplyDropdown();">
                                <span style="font-size: 20px;">📫</span>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600;">Yahoo Mail</div>
                                    <div style="font-size: 12px; color: var(--admin-gray-600);">Open in Yahoo web</div>
                                </div>
                            </a>
                            
                            <!-- Default Email App -->
                            <?php $defaultLink = isset($mailto['link']) ? $mailto['link'] : '#'; ?>
                            <a href="<?php echo htmlspecialchars($defaultLink ?? '#', ENT_QUOTES, 'UTF-8'); ?>" 
                               style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; color: var(--admin-gray-800); transition: background 0.2s; border-radius: 0 0 8px 8px;"
                               onmouseover="this.style.background='var(--admin-gray-50)'"
                               onmouseout="this.style.background='white'"
                               onclick="trackReply('default', <?php echo (int)($submission['id'] ?? 0); ?>); toggleReplyDropdown(); setTimeout(() => checkReplySent(<?php echo (int)($submission['id'] ?? 0); ?>), 1000);">
                                <span style="font-size: 20px;">💻</span>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600;">Default Email App</div>
                                    <div style="font-size: 12px; color: var(--admin-gray-600);">Outlook, Thunderbird, Windows Mail</div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Reply Information Banner -->
        <?php $replyToEmail = $mailto['reply_to'] ?? 'noreply@fctcns.edu.ng'; ?>
        <div class="reply-info-banner">
            <div class="reply-info-content">
                <span class="reply-email-badge">
                    📧 Reply-To: <?php echo htmlspecialchars($replyToEmail); ?>
                </span>
                <span class="reply-department">
                    🏢 Department: <?php echo ucfirst(htmlspecialchars($submission['department'] ?? 'General')); ?>
                </span>
            </div>
            <button class="btn btn-secondary" onclick="copyReplyEmail()" style="background: white; color: var(--admin-primary);">
                📋 Copy Reply Email
            </button>
        </div>
        
        <!-- Submission Information Grid -->
        <div class="submission-info-grid">
            <!-- Sender Information -->
            <div class="info-card sender">
                <h3>👤 Sender Information</h3>
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($submission['name'] ?? 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">
                        <a href="mailto:<?php echo htmlspecialchars($submission['email'] ?? ''); ?>">
                            <?php echo htmlspecialchars($submission['email'] ?? 'N/A'); ?>
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
                    <div class="info-label">Department</div>
                    <div class="info-value">
                        <span style="background: var(--admin-gray-100); padding: 4px 12px; border-radius: 16px;">
                            <?php echo ucfirst(htmlspecialchars($submission['department'] ?? 'General')); ?>
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Subject</div>
                    <div class="info-value"><?php echo htmlspecialchars($submission['subject'] ?? 'No Subject'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Submitted</div>
                    <div class="info-value">
                        <?php echo isset($submission['created_at']) ? date('F j, Y \a\t g:i A', strtotime($submission['created_at'])) : 'N/A'; ?>
                    </div>
                </div>
                <?php if (!empty($submission['responded_at'])): ?>
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
                    <div class="info-label">Reference ID</div>
                    <div class="info-value" style="font-family: monospace;">#<?php echo htmlspecialchars($submission['id'] ?? '0'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">User Agent</div>
                    <div class="info-value" style="font-size: 0.8rem; color: var(--admin-gray-600);">
                        <?php echo htmlspecialchars(substr($submission['user_agent'] ?? 'Unknown', 0, 80)); ?>...
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Message Content -->
        <div class="message-container">
            <h2>
                📝 Message
                <button class="btn btn-secondary" onclick="copyMessage()" style="padding: 6px 12px; font-size: 0.875rem;">
                    📋 Copy Message
                </button>
            </h2>
            <div class="message-content" id="messageContent">
                <?php echo nl2br(htmlspecialchars($submission['message'] ?? 'No message content')); ?>
            </div>
        </div>
        
        <!-- Email Preview -->
        <div style="margin-bottom: 20px;">
            <button class="btn btn-secondary" onclick="toggleEmailPreview()" style="width: 100%; justify-content: center;">
                📧 Show/Hide Email Preview
            </button>
            <div id="emailPreview" class="email-preview" style="display: none;">
                <div class="email-preview-header">
                    <span class="email-preview-label">📤 Email that will be sent</span>
                    <button class="btn btn-secondary" onclick="copyEmailPreview()" style="padding: 4px 12px; font-size: 0.75rem;">
                        Copy Preview
                    </button>
                </div>
                <div class="email-preview-content">
To: <?php echo htmlspecialchars($submission['email'] ?? ''); ?>

Subject: <?php echo htmlspecialchars($mailto['subject'] ?? ''); ?>

<?php echo htmlspecialchars($mailto['body'] ?? ''); ?>
                </div>
                <div style="margin-top: 10px; font-size: 0.75rem; color: var(--admin-gray-600);">
                    <strong>Reply-To:</strong> <?php echo htmlspecialchars($replyToEmail); ?><br>
                    <strong>CC:</strong> <?php echo htmlspecialchars($replyToEmail); ?> (for tracking)
                </div>
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
            <form method="POST" action="<?php echo BASE_URL; ?>/admin/contact/update/<?php echo $submission['id'] ?? 0; ?>" 
                  class="action-form" id="updateForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="status">Update Status</label>
                    <select id="status" name="status" required>
                        <option value="pending" <?php echo ($submission['status'] ?? 'pending') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="responded" <?php echo ($submission['status'] ?? '') === 'responded' ? 'selected' : ''; ?>>Responded</option>
                        <option value="archived" <?php echo ($submission['status'] ?? '') === 'archived' ? 'selected' : ''; ?>>Archived</option>
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
                    <?php if (($submission['status'] ?? 'pending') === 'pending'): ?>
                    <button type="button" class="btn btn-success" onclick="markAsRespondedAndReply()">
                        ✅ Reply & Mark Responded
                    </button>
                    <?php endif; ?>
                </div>
            </form>
            
            <!-- Quick Reply Templates -->
            <?php if (($submission['status'] ?? 'pending') === 'pending'): ?>
            <div class="quick-reply">
                <h3>
                    💬 Quick Reply Templates
                    <span style="font-size: 0.75rem; color: var(--admin-gray-600); font-weight: normal;">
                        (Opens in Gmail)
                    </span>
                </h3>
                <div class="reply-buttons">
                    <button class="reply-btn" onclick="useTemplate('acknowledge')">
                        📥 Acknowledgment
                    </button>
                    <button class="reply-btn" onclick="useTemplate('admissions')">
                        🎓 Admissions Info
                    </button>
                    <button class="reply-btn" onclick="useTemplate('schedule')">
                        📅 Schedule Meeting
                    </button>
                    <button class="reply-btn" onclick="useTemplate('thanks')">
                        🙏 Thank You
                    </button>
                    <button class="reply-btn" onclick="useTemplate('technical')">
                        🔧 Technical Support
                    </button>
                </div>
                <div style="margin-top: 15px; font-size: 0.75rem; color: var(--admin-gray-600);">
                    💡 After sending your email, you'll be prompted to mark this as responded.
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Danger Zone -->
        <?php if (in_array($userRole, ['admin', 'editor'])): ?>
        <div class="danger-zone">
            <h3 style="margin-top: 0; color: #dc2626;">⚠️ Danger Zone</h3>
            <p style="color: #7f1d1d; margin-bottom: 15px;">
                These actions cannot be undone. Please proceed with caution.
            </p>
            <form method="POST" 
                  action="<?php echo BASE_URL; ?>/admin/contact/delete/<?php echo $submission['id'] ?? 0; ?>" 
                  onsubmit="return confirm('Are you sure you want to delete this submission? This action cannot be undone.')">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <button type="submit" class="btn btn-danger">
                    🗑️ Delete Submission
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Copy Notification Toast -->
    <div id="copyNotification" class="copy-notification">
        ✓ Copied to clipboard!
    </div>
    
    <!-- FIXED: Complete JavaScript with proper functions -->
    <script>
        const CONFIG = {
            submissionId:  <?php echo json_encode($submission['id'] ?? 0); ?>,
            userEmail:     <?php echo json_encode($submission['email'] ?? ''); ?>,
            userName:      <?php echo json_encode($submission['name'] ?? ''); ?>,
            replyToEmail:  <?php echo json_encode($replyToEmail); ?>,
            department:    <?php echo json_encode($submission['department'] ?? 'general'); ?>,
            baseUrl:       <?php echo json_encode(BASE_URL); ?>,
            csrfToken:     <?php echo json_encode($csrf_token); ?>,
            gmailLink:     <?php echo json_encode($mailto['gmail_link'] ?? '#'); ?>,
            subject:       <?php echo json_encode($submission['subject'] ?? ''); ?>,
            currentStatus: <?php echo json_encode($submission['status'] ?? 'pending'); ?>
        };

        // ── Dropdown ──────────────────────────────────────────────────────────────
        function toggleReplyDropdown() {
            const d = document.getElementById('replyDropdown');
            if (d) {
                d.style.display = (d.style.display === 'block') ? 'none' : 'block';
            }
        }

        document.addEventListener('click', function(e) {
            const d = document.getElementById('replyDropdown');
            const b = document.getElementById('replyDropdownBtn');
            if (d && b && !b.contains(e.target) && !d.contains(e.target)) {
                d.style.display = 'none';
            }
        });

        // ── Mark as responded via AJAX ────────────────────────────────────────────
        function markAsResponded(submissionId, notes) {
            fetch(CONFIG.baseUrl + '/admin/contact/quick-update/' + submissionId, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    status: 'responded',
                    admin_notes: notes || ('Replied via email on ' + new Date().toLocaleString())
                })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    // Update the status select dropdown
                    var sel = document.getElementById('status');
                    if (sel) sel.value = 'responded';

                    // Update the badge next to the heading
                    var badge = document.querySelector('.status-badge');
                    if (badge) {
                        badge.className   = 'status-badge status-responded';
                        badge.textContent = 'Responded';
                    }

                    showNotification('✓ Marked as responded');
                } else {
                    alert('Could not update status: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(function(err) {
                console.error('AJAX error:', err);
                alert('Network error. Please update the status manually using the form below.');
            });
        }

        // ── Reply tracking ────────────────────────────────────────────────────────
        function trackReply(client, submissionId) {
            sessionStorage.setItem('reply_client_' + submissionId, client);
            sessionStorage.setItem('replied_to_' + submissionId, 'true');
        }

        // ── Gmail button function ────────────────────────────────────────────
        function replyViaGmail(submissionId) {
            trackReply('gmail', submissionId);
            window.open(CONFIG.gmailLink, '_blank');

            setTimeout(function() {
                if (confirm('Did you send your email? Click OK to mark this submission as responded.')) {
                    markAsResponded(submissionId, 'Replied via Gmail on ' + new Date().toLocaleString());
                }
            }, 3000);
        }

        // ── Reply & Mark Responded button ─────────────────────────────────────────
        function markAsRespondedAndReply() {
            trackReply('gmail', CONFIG.submissionId);
            window.open(CONFIG.gmailLink, '_blank');

            setTimeout(function() {
                markAsResponded(CONFIG.submissionId, 'Replied via Gmail on ' + new Date().toLocaleString());
            }, 2000);
        }

        // ── Quick reply templates ─────────────────────────────────────────────────
        function useTemplate(type) {
            var templates = {
                acknowledge: 'Dear ' + CONFIG.userName + ',\n\nThank you for contacting FCT College of Nursing Sciences. We have received your inquiry and will review it shortly. A member of our team will respond to you within 24-48 hours during working days.\n\nReference ID: #' + CONFIG.submissionId + '\n\nBest regards,\nThe FCT CNS Team',
                admissions:  'Dear ' + CONFIG.userName + ',\n\nThank you for your interest in our nursing programs. Please visit ' + CONFIG.baseUrl + '/admissions for full details.\n\nReference ID: #' + CONFIG.submissionId + '\n\nBest regards,\nAdmissions Office\n' + CONFIG.replyToEmail,
                schedule:    'Dear ' + CONFIG.userName + ',\n\nThank you for your inquiry. We would be happy to schedule a meeting. Please let us know your preferred dates and times.\n\nReference ID: #' + CONFIG.submissionId + '\n\nBest regards,\nThe FCT CNS Team',
                thanks:      'Dear ' + CONFIG.userName + ',\n\nThank you for your message and your interest in FCT College of Nursing Sciences.\n\nReference ID: #' + CONFIG.submissionId + '\n\nBest regards,\nThe FCT CNS Team',
                technical:   'Dear ' + CONFIG.userName + ',\n\nThank you for contacting our support team. We are looking into the issue you reported.\n\nReference ID: #' + CONFIG.submissionId + '\n\nBest regards,\nSupport Team\n' + CONFIG.replyToEmail
            };

            var body     = templates[type] || templates.acknowledge;
            var subject  = 'RE: ' + CONFIG.subject;
            var link     = 'https://mail.google.com/mail/?view=cm&fs=1'
                         + '&to='      + encodeURIComponent(CONFIG.userEmail)
                         + '&su='      + encodeURIComponent(subject)
                         + '&body='    + encodeURIComponent(body)
                         + '&cc='      + encodeURIComponent(CONFIG.replyToEmail)
                         + '&replyto=' + encodeURIComponent(CONFIG.replyToEmail);

            trackReply('gmail-template', CONFIG.submissionId);
            window.open(link, '_blank');

            // Pre-fill notes
            var notesField = document.getElementById('admin_notes');
            if (notesField) {
                notesField.value = '[' + new Date().toLocaleString() + '] Replied using '
                                 + type + ' template via Gmail\n\n' + notesField.value;
            }

            setTimeout(function() {
                if (confirm('Did you send your email? Click OK to mark this submission as responded.')) {
                    markAsResponded(CONFIG.submissionId, 'Replied using ' + type + ' template via Gmail on ' + new Date().toLocaleString());
                }
            }, 3000);
        }

        // ── Clipboard helpers ─────────────────────────────────────────────────────
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text)
                .then(function() { showNotification('✓ Copied to clipboard!'); })
                .catch(function() {
                    var t = document.createElement('textarea');
                    t.value = text;
                    document.body.appendChild(t);
                    t.select();
                    document.execCommand('copy');
                    document.body.removeChild(t);
                    showNotification('✓ Copied to clipboard!');
                });
        }

        function copyReplyEmail()   { copyToClipboard(CONFIG.replyToEmail); }
        function copyMessage()      { copyToClipboard(<?php echo json_encode($submission['message'] ?? ''); ?>); }
        function copyEmailPreview() {
            var p = document.querySelector('.email-preview-content');
            if (p) copyToClipboard(p.innerText);
        }

        function toggleEmailPreview() {
            var p = document.getElementById('emailPreview');
            if (p) p.style.display = (p.style.display === 'block') ? 'none' : 'block';
        }

        // ── Notification toast ────────────────────────────────────────────────────
        function showNotification(message, duration) {
            duration = duration || 2500;
            var n = document.getElementById('copyNotification');
            if (n) {
                n.textContent    = message;
                n.style.display  = 'block';
                setTimeout(function() { n.style.display = 'none'; }, duration);
            }
        }

        // ── Keyboard shortcuts ────────────────────────────────────────────────────
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('updateForm').submit();
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 'm') {
                e.preventDefault();
                markAsResponded(CONFIG.submissionId, 'Marked as responded via keyboard shortcut on ' + new Date().toLocaleString());
            }
            if (e.key === 'Escape') {
                if (confirm('Go back to contact list? Unsaved changes will be lost.')) {
                    window.location.href = CONFIG.baseUrl + '/admin/contact';
                }
            }
        });

        // ── On load: check if user just came back from sending email ──────────────
        document.addEventListener('DOMContentLoaded', function() {
            var replied = sessionStorage.getItem('replied_to_' + CONFIG.submissionId);

            if (replied === 'true' && CONFIG.currentStatus === 'pending') {
                var client = sessionStorage.getItem('reply_client_' + CONFIG.submissionId) || 'email client';

                sessionStorage.removeItem('replied_to_'   + CONFIG.submissionId);
                sessionStorage.removeItem('reply_client_' + CONFIG.submissionId);

                setTimeout(function() {
                    if (confirm('You recently replied to this submission. Mark it as responded?')) {
                        markAsResponded(CONFIG.submissionId, 'Replied via ' + client + ' on ' + new Date().toLocaleString());
                    }
                }, 500);
            }
        });
    </script>
</body>
</html>