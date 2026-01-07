<?php
/**
 * Nominal Roll Settings View
 * Configure system settings for nominal roll management
 * 
 * This page uses separate pages for each tab section
 */

// Get the current section from URL parameter or default to 'general'
$current_section = $_GET['section'] ?? 'general';

// Validate section to prevent invalid values
$valid_sections = ['general', 'permissions', 'export', 'backup', 'system'];
if (!in_array($current_section, $valid_sections)) {
    $current_section = 'general';
}
?>
<div class="settings-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1>Nominal Roll Settings</h1>
                <p class="subtitle">Configure system settings and preferences</p>
            </div>
            <div class="header-actions">
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Nominal Roll
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

    <!-- Settings Navigation -->
    <div class="settings-navigation">
        <nav class="settings-nav">
            <ul>
                <li>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/settings?section=general" 
                       class="nav-link <?php echo $current_section === 'general' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i> General
                    </a>
                </li>
                <li>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/settings?section=permissions" 
                       class="nav-link <?php echo $current_section === 'permissions' ? 'active' : ''; ?>">
                        <i class="fas fa-lock"></i> Permissions
                    </a>
                </li>
                <li>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/settings?section=export" 
                       class="nav-link <?php echo $current_section === 'export' ? 'active' : ''; ?>">
                        <i class="fas fa-file-export"></i> Export
                    </a>
                </li>
                <li>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/settings?section=backup" 
                       class="nav-link <?php echo $current_section === 'backup' ? 'active' : ''; ?>">
                        <i class="fas fa-database"></i> Backup
                    </a>
                </li>
                <li>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/settings?section=system" 
                       class="nav-link <?php echo $current_section === 'system' ? 'active' : ''; ?>">
                        <i class="fas fa-server"></i> System
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Settings Content -->
    <div class="settings-content">
        <?php if ($current_section === 'general'): ?>
            <!-- General Settings -->
            <div class="settings-section active">
                <div class="section-header">
                    <h2><i class="fas fa-cog"></i> General Settings</h2>
                    <p class="section-description">Configure basic system preferences and defaults</p>
                </div>
                
                <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/update-settings" class="settings-form">
                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="section" value="general">
                    
                    <div class="form-section">
                        <h3><i class="fas fa-toggle-on"></i> Editing Control</h3>
                        <div class="form-group">
                            <div class="form-item">
                                <div class="form-label">
                                    <label for="editing_enabled">Enable Editing</label>
                                    <p class="form-description">
                                        Allow editors to modify employee records. When disabled, only Super Admin can edit.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <div class="toggle-switch">
                                        <input type="checkbox" 
                                               id="editing_enabled" 
                                               name="settings[editing_enabled]" 
                                               value="1"
                                               <?php echo ($settings['editing_enabled'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                        <label for="editing_enabled" class="toggle-label">
                                            <span class="toggle-handle"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-item">
                                <div class="form-label">
                                    <label for="auto_approve_new">Auto-approve New Employees</label>
                                    <p class="form-description">
                                        Automatically approve new employee entries without manual review.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <div class="toggle-switch">
                                        <input type="checkbox" 
                                               id="auto_approve_new" 
                                               name="settings[auto_approve_new]" 
                                               value="1"
                                               <?php echo ($settings['auto_approve_new'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                        <label for="auto_approve_new" class="toggle-label">
                                            <span class="toggle-handle"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-image"></i> Photo Upload</h3>
                        <div class="form-group">
                            <div class="form-item">
                                <div class="form-label">
                                    <label for="photo_required">Passport Photo Required</label>
                                    <p class="form-description">
                                        Require passport photo when adding new employees.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <div class="toggle-switch">
                                        <input type="checkbox" 
                                               id="photo_required" 
                                               name="settings[photo_required]" 
                                               value="1"
                                               <?php echo ($settings['photo_required'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                        <label for="photo_required" class="toggle-label">
                                            <span class="toggle-handle"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-item">
                                <div class="form-label">
                                    <label for="max_photo_size">Max Photo Size (MB)</label>
                                    <p class="form-description">
                                        Maximum allowed file size for passport photos.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <select id="max_photo_size" name="settings[max_photo_size]" class="form-input">
                                        <?php 
                                        $maxSize = $settings['passport_max_size'] ?? '2097152';
                                        $maxSizeMB = (int)$maxSize / 1024 / 1024;
                                        ?>
                                        <option value="1048576" <?php echo $maxSizeMB == 1 ? 'selected' : ''; ?>>1 MB</option>
                                        <option value="2097152" <?php echo $maxSizeMB == 2 ? 'selected' : ''; ?>>2 MB</option>
                                        <option value="5242880" <?php echo $maxSizeMB == 5 ? 'selected' : ''; ?>>5 MB</option>
                                        <option value="10485760" <?php echo $maxSizeMB == 10 ? 'selected' : ''; ?>>10 MB</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-item">
                                <div class="form-label">
                                    <label for="photo_dimensions">Photo Dimensions</label>
                                    <p class="form-description">
                                        Recommended dimensions for passport photos.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <select id="photo_dimensions" name="settings[photo_dimensions]" class="form-input">
                                        <option value="300x300" <?php echo ($settings['photo_dimensions'] ?? '300x300') == '300x300' ? 'selected' : ''; ?>>300x300 pixels</option>
                                        <option value="400x400" <?php echo ($settings['photo_dimensions'] ?? '300x300') == '400x400' ? 'selected' : ''; ?>>400x400 pixels</option>
                                        <option value="500x500" <?php echo ($settings['photo_dimensions'] ?? '300x300') == '500x500' ? 'selected' : ''; ?>>500x500 pixels</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-bell"></i> Notifications</h3>
                        <div class="form-group">
                            <div class="form-item">
                                <div class="form-label">
                                    <label for="email_notifications">Email Notifications</label>
                                    <p class="form-description">
                                        Send email notifications for system activities.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <div class="toggle-switch">
                                        <input type="checkbox" 
                                               id="email_notifications" 
                                               name="settings[email_notifications]" 
                                               value="1"
                                               <?php echo ($settings['email_notifications'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                        <label for="email_notifications" class="toggle-label">
                                            <span class="toggle-handle"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-item">
                                <div class="form-label">
                                    <label for="notification_email">Notification Email</label>
                                    <p class="form-description">
                                        Email address to receive system notifications.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <input type="email" 
                                           id="notification_email" 
                                           name="settings[notification_email]" 
                                           value="<?php echo htmlspecialchars($settings['notification_email'] ?? ''); ?>"
                                           class="form-input"
                                           placeholder="admin@example.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-search"></i> Search & Filter</h3>
                        <div class="form-group">
                            <div class="form-item">
                                <div class="form-label">
                                    <label for="records_per_page">Records Per Page</label>
                                    <p class="form-description">
                                        Number of employees to display per page.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <select id="records_per_page" name="settings[records_per_page]" class="form-input">
                                        <option value="10" <?php echo ($settings['records_per_page'] ?? '20') == '10' ? 'selected' : ''; ?>>10</option>
                                        <option value="20" <?php echo ($settings['records_per_page'] ?? '20') == '20' ? 'selected' : ''; ?>>20</option>
                                        <option value="50" <?php echo ($settings['records_per_page'] ?? '20') == '50' ? 'selected' : ''; ?>>50</option>
                                        <option value="100" <?php echo ($settings['records_per_page'] ?? '20') == '100' ? 'selected' : ''; ?>>100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save General Settings
                        </button>
                        <button type="button" class="btn btn-outline" onclick="resetSection('general')">
                            <i class="fas fa-undo"></i> Reset to Defaults
                        </button>
                    </div>
                </form>
            </div>

        <?php elseif ($current_section === 'permissions'): ?>
            <!-- Permissions Settings -->
            <div class="settings-section active">
                <div class="section-header">
                    <h2><i class="fas fa-lock"></i> Permission Settings</h2>
                    <p class="section-description">Configure user roles and access controls</p>
                </div>
                
                <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/update-settings" class="settings-form">
                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="section" value="permissions">
                    
                    <div class="form-section">
                        <h3><i class="fas fa-user-shield"></i> Role Permissions</h3>
                        <div class="permissions-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Permission</th>
                                        <th>Super Admin</th>
                                        <th>Admin</th>
                                        <th>Editor</th>
                                        <th>Viewer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>View Employees</td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Add Employees</td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td>
                                            <div class="toggle-switch small">
                                                <input type="checkbox" 
                                                       id="editor_add" 
                                                       name="settings[editor_add]" 
                                                       value="1"
                                                       <?php echo ($settings['editor_add'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                                <label for="editor_add" class="toggle-label">
                                                    <span class="toggle-handle"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td><i class="fas fa-times text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Edit Employees</td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td>
                                            <div class="toggle-switch small">
                                                <input type="checkbox" 
                                                       id="editor_edit" 
                                                       name="settings[editor_edit]" 
                                                       value="1"
                                                       <?php echo ($settings['editor_edit'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                                <label for="editor_edit" class="toggle-label">
                                                    <span class="toggle-handle"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td><i class="fas fa-times text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Delete Employees</td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td>
                                            <div class="toggle-switch small">
                                                <input type="checkbox" 
                                                       id="admin_delete" 
                                                       name="settings[admin_delete]" 
                                                       value="1"
                                                       <?php echo ($settings['admin_delete'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                                <label for="admin_delete" class="toggle-label">
                                                    <span class="toggle-handle"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td><i class="fas fa-times text-danger"></i></td>
                                        <td><i class="fas fa-times text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Bulk Upload</td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td>
                                            <div class="toggle-switch small">
                                                <input type="checkbox" 
                                                       id="editor_bulk" 
                                                       name="settings[editor_bulk]" 
                                                       value="1"
                                                       <?php echo ($settings['editor_bulk'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                                <label for="editor_bulk" class="toggle-label">
                                                    <span class="toggle-handle"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td><i class="fas fa-times text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Export Data</td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td>
                                            <div class="toggle-switch small">
                                                <input type="checkbox" 
                                                       id="viewer_export" 
                                                       name="settings[viewer_export]" 
                                                       value="1"
                                                       <?php echo ($settings['viewer_export'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                                <label for="viewer_export" class="toggle-label">
                                                    <span class="toggle-handle"></span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Access Settings</td>
                                        <td><i class="fas fa-check text-success"></i></td>
                                        <td>
                                            <div class="toggle-switch small">
                                                <input type="checkbox" 
                                                       id="admin_settings" 
                                                       name="settings[admin_settings]" 
                                                       value="1"
                                                       <?php echo ($settings['admin_settings'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                                <label for="admin_settings" class="toggle-label">
                                                    <span class="toggle-handle"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td><i class="fas fa-times text-danger"></i></td>
                                        <td><i class="fas fa-times text-danger"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-user-plus"></i> User Management</h3>
                        <div class="form-group">
                            <div class="form-item">
                                <div class="form-label">
                                    <label for="auto_assign_role">Auto-assign Editor Role</label>
                                    <p class="form-description">
                                        Automatically assign Editor role to new admin users.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <div class="toggle-switch">
                                        <input type="checkbox" 
                                               id="auto_assign_role" 
                                               name="settings[auto_assign_role]" 
                                               value="1"
                                               <?php echo ($settings['auto_assign_role'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                        <label for="auto_assign_role" class="toggle-label">
                                            <span class="toggle-handle"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-item">
                                <div class="form-label">
                                    <label for="default_role">Default Role for New Users</label>
                                    <p class="form-description">
                                        Default role assigned to new system users.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <select id="default_role" name="settings[default_role]" class="form-input">
                                        <option value="viewer" <?php echo ($settings['default_role'] ?? 'viewer') == 'viewer' ? 'selected' : ''; ?>>Viewer</option>
                                        <option value="editor" <?php echo ($settings['default_role'] ?? 'viewer') == 'editor' ? 'selected' : ''; ?>>Editor</option>
                                        <option value="admin" <?php echo ($settings['default_role'] ?? 'viewer') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save Permission Settings
                        </button>
                        <button type="button" class="btn btn-outline" onclick="resetSection('permissions')">
                            <i class="fas fa-undo"></i> Reset to Defaults
                        </button>
                    </div>
                </form>
            </div>

        <?php elseif ($current_section === 'export'): ?>
            <!-- Export Settings -->
            <div class="settings-section active">
                <div class="section-header">
                    <h2><i class="fas fa-file-export"></i> Export Settings</h2>
                    <p class="section-description">Configure data export formats and print settings</p>
                </div>
                
                <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/update-settings" class="settings-form">
                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="section" value="export">
                    
                    <div class="form-section">
                        <h3><i class="fas fa-file-export"></i> Export Configuration</h3>
                        <div class="form-group">
                            <div class="form-item">
                                <div class="form-label">
                                    <label for="export_format">Default Export Format</label>
                                    <p class="form-description">
                                        Default file format for data exports.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <select id="export_format" name="settings[export_format]" class="form-input">
                                        <option value="csv" <?php echo ($settings['export_format'] ?? 'csv') == 'csv' ? 'selected' : ''; ?>>CSV</option>
                                        <option value="excel" <?php echo ($settings['export_format'] ?? 'csv') == 'excel' ? 'selected' : ''; ?>>Excel</option>
                                        <option value="pdf" <?php echo ($settings['export_format'] ?? 'csv') == 'pdf' ? 'selected' : ''; ?>>PDF</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-item">
                                <div class="form-label">
                                    <label for="export_filename">Export File Name Pattern</label>
                                    <p class="form-description">
                                        Pattern for naming exported files. Use {date} for current date.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <input type="text" 
                                           id="export_filename" 
                                           name="settings[export_filename]" 
                                           value="<?php echo htmlspecialchars($settings['export_filename'] ?? 'employees_{date}.csv'); ?>"
                                           class="form-input"
                                           placeholder="employees_{date}.csv">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-print"></i> Print Configuration</h3>
                        <div class="form-group">
                            <div class="form-item">
                                <div class="form-label">
                                    <label for="print_header">Print Header</label>
                                    <p class="form-description">
                                        Header text to display on printed reports.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <input type="text" 
                                           id="print_header" 
                                           name="settings[print_header]" 
                                           value="<?php echo htmlspecialchars($settings['print_header'] ?? 'FCT College of Nursing Sciences'); ?>"
                                           class="form-input"
                                           placeholder="Institution Name">
                                </div>
                            </div>

                            <div class="form-item">
                                <div class="form-label">
                                    <label for="print_footer">Print Footer</label>
                                    <p class="form-description">
                                        Footer text for printed reports.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <input type="text" 
                                           id="print_footer" 
                                           name="settings[print_footer]" 
                                           value="<?php echo htmlspecialchars($settings['print_footer'] ?? 'Confidential - For Official Use Only'); ?>"
                                           class="form-input"
                                           placeholder="Footer text">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save Export Settings
                        </button>
                        <button type="button" class="btn btn-outline" onclick="resetSection('export')">
                            <i class="fas fa-undo"></i> Reset to Defaults
                        </button>
                    </div>
                </form>
            </div>

        <?php elseif ($current_section === 'backup'): ?>
            <!-- Backup Settings -->
            <div class="settings-section active">
                <div class="section-header">
                    <h2><i class="fas fa-database"></i> Backup Settings</h2>
                    <p class="section-description">Configure automatic backups and manage backup history</p>
                </div>
                
                <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/update-settings" class="settings-form">
                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="section" value="backup">
                    
                    <div class="form-section">
                        <h3><i class="fas fa-database"></i> Backup Configuration</h3>
                        <div class="form-group">
                            <div class="form-item">
                                <div class="form-label">
                                    <label for="auto_backup">Automatic Backups</label>
                                    <p class="form-description">
                                        Enable automatic daily backups of employee data.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <div class="toggle-switch">
                                        <input type="checkbox" 
                                               id="auto_backup" 
                                               name="settings[auto_backup]" 
                                               value="1"
                                               <?php echo ($settings['auto_backup'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                        <label for="auto_backup" class="toggle-label">
                                            <span class="toggle-handle"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-item">
                                <div class="form-label">
                                    <label for="backup_time">Backup Time</label>
                                    <p class="form-description">
                                        Daily time to perform automatic backups.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <input type="time" 
                                           id="backup_time" 
                                           name="settings[backup_time]" 
                                           value="<?php echo htmlspecialchars($settings['backup_time'] ?? '02:00'); ?>"
                                           class="form-input">
                                </div>
                            </div>

                            <div class="form-item">
                                <div class="form-label">
                                    <label for="backup_retention">Backup Retention (days)</label>
                                    <p class="form-description">
                                        Number of days to keep backup files before deletion.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <select id="backup_retention" name="settings[backup_retention]" class="form-input">
                                        <option value="7" <?php echo ($settings['backup_retention'] ?? '30') == '7' ? 'selected' : ''; ?>>7 days</option>
                                        <option value="30" <?php echo ($settings['backup_retention'] ?? '30') == '30' ? 'selected' : ''; ?>>30 days</option>
                                        <option value="90" <?php echo ($settings['backup_retention'] ?? '30') == '90' ? 'selected' : ''; ?>>90 days</option>
                                        <option value="365" <?php echo ($settings['backup_retention'] ?? '30') == '365' ? 'selected' : ''; ?>>1 year</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-cloud-upload-alt"></i> Manual Backup</h3>
                        <div class="backup-actions">
                            <div class="backup-info">
                                <p><strong>Last Backup:</strong> 
                                    <?php 
                                    if (!empty($backups)) {
                                        echo date('M d, Y H:i', strtotime($backups[0]['created_at']));
                                    } else {
                                        echo 'Never';
                                    }
                                    ?>
                                </p>
                                <p><strong>Total Backups:</strong> <?php echo count($backups ?? []); ?></p>
                            </div>
                            
                            <div class="backup-buttons">
                                <button type="button" class="btn btn-primary" onclick="createBackup()">
                                    <i class="fas fa-database"></i> Create Backup Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-history"></i> Backup History</h3>
                        <?php if (!empty($backups)): ?>
                        <div class="backup-history">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>File Name</th>
                                        <th>Size</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($backups as $backup): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y H:i', strtotime($backup['created_at'])); ?></td>
                                        <td><?php echo htmlspecialchars($backup['file_name']); ?></td>
                                        <td><?php echo $backup['file_size']; ?> MB</td>
                                        <td><?php echo ucfirst($backup['backup_type']); ?></td>
                                        <td>
                                            <?php if ($backup['status'] === 'success'): ?>
                                            <span class="badge badge-success">Success</span>
                                            <?php else: ?>
                                            <span class="badge badge-danger">Failed</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/download-backup/<?php echo $backup['id']; ?>" 
                                               class="btn btn-sm btn-outline" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline btn-danger" 
                                                    title="Delete"
                                                    onclick="deleteBackup(<?php echo $backup['id']; ?>, '<?php echo htmlspecialchars($backup['file_name']); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-database"></i>
                            <p>No backup history found</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save Backup Settings
                        </button>
                    </div>
                </form>
            </div>

        <?php elseif ($current_section === 'system'): ?>
            <!-- System Settings -->
            <div class="settings-section active">
                <div class="section-header">
                    <h2><i class="fas fa-server"></i> System Settings</h2>
                    <p class="section-description">System information and maintenance options</p>
                </div>
                
                <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/update-settings" class="settings-form">
                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="section" value="system">
                    
                    <div class="form-section">
                        <h3><i class="fas fa-server"></i> System Information</h3>
                        <div class="system-info">
                            <div class="info-item">
                                <label>PHP Version</label>
                                <span><?php echo phpversion(); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Total Employees</label>
                                <span><?php echo $stats['total_employees'] ?? 0; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Male Employees</label>
                                <span><?php echo $stats['male_count'] ?? 0; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Female Employees</label>
                                <span><?php echo $stats['female_count'] ?? 0; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Draft Employees</label>
                                <span><?php echo $stats['draft_count'] ?? 0; ?></span>
                            </div>
                            <div class="info-item">
                                <label>System Version</label>
                                <span>1.0.0</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-wrench"></i> Maintenance</h3>
                        <div class="form-group">
                            <div class="form-item">
                                <div class="form-label">
                                    <label for="maintenance_mode">Maintenance Mode</label>
                                    <p class="form-description">
                                        Put the system in maintenance mode (accessible only to Super Admin).
                                    </p>
                                </div>
                                <div class="form-control">
                                    <div class="toggle-switch">
                                        <input type="checkbox" 
                                               id="maintenance_mode" 
                                               name="settings[maintenance_mode]" 
                                               value="1"
                                               <?php echo ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                        <label for="maintenance_mode" class="toggle-label">
                                            <span class="toggle-handle"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-item">
                                <div class="form-label">
                                    <label>Clear Activity Logs</label>
                                    <p class="form-description">
                                        Clear activity logs older than 90 days.
                                    </p>
                                </div>
                                <div class="form-control">
                                    <button type="button" class="btn btn-warning" onclick="clearActivityLogs()">
                                        <i class="fas fa-trash"></i> Clear Old Logs
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-exclamation-triangle"></i> Dangerous Actions</h3>
                        <div class="danger-zone">
                            <div class="danger-item">
                                <div class="danger-label">
                                    <h4><i class="fas fa-trash text-danger"></i> Reset All Settings</h4>
                                    <p>Reset all settings to factory defaults. This will clear all custom configurations.</p>
                                </div>
                                <div class="danger-control">
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            onclick="showResetConfirmation()">
                                        <i class="fas fa-undo"></i> Reset Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save System Settings
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmAction">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modal
    const modalElement = document.getElementById('confirmationModal');
    if (modalElement) {
        window.confirmationModal = new bootstrap.Modal(modalElement);
    }

    // Toggle switch initialization
    const toggleSwitches = document.querySelectorAll('.toggle-switch input[type="checkbox"]');
    toggleSwitches.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.checked) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
        });
        
        // Initialize state
        if (toggle.checked) {
            toggle.nextElementSibling.classList.add('checked');
        }
    });

    // Form submission handling
    const forms = document.querySelectorAll('.settings-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveSettings(this);
        });
    });
});

// Save settings function
function saveSettings(form) {
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    submitBtn.disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Settings saved successfully!', 'success');
            // Reload page after a short delay
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(data.error || 'Failed to save settings', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Section reset function
function resetSection(section) {
    const defaultSettings = {
        'general': {
            'editing_enabled': '1',
            'auto_approve_new': '1',
            'photo_required': '0',
            'max_photo_size': '2097152',
            'photo_dimensions': '300x300',
            'email_notifications': '0',
            'notification_email': '',
            'records_per_page': '20'
        },
        'permissions': {
            'editor_add': '1',
            'editor_edit': '1',
            'admin_delete': '0',
            'editor_bulk': '1',
            'viewer_export': '0',
            'admin_settings': '0',
            'auto_assign_role': '1',
            'default_role': 'viewer'
        },
        'export': {
            'export_format': 'csv',
            'export_filename': 'employees_{date}.csv',
            'print_header': 'FCT College of Nursing Sciences',
            'print_footer': 'Confidential - For Official Use Only'
        },
        'backup': {
            'auto_backup': '0',
            'backup_time': '02:00',
            'backup_retention': '30'
        },
        'system': {
            'maintenance_mode': '0'
        }
    };
    
    if (confirm('Are you sure you want to reset ' + section + ' settings to defaults?')) {
        const form = document.querySelector('.settings-form');
        const defaults = defaultSettings[section];
        
        // Reset form values
        for (const [key, value] of Object.entries(defaults)) {
            const input = form.querySelector(`[name="settings[${key}]"]`);
            if (input) {
                if (input.type === 'checkbox') {
                    input.checked = value === '1';
                    // Trigger change event
                    input.dispatchEvent(new Event('change'));
                } else {
                    input.value = value;
                }
            }
        }
        
        showToast('Settings reset to defaults. Click Save to apply.', 'info');
    }
}

// Backup functions
function createBackup() {
    if (confirm('Create a new backup of all employee data?')) {
        showLoading('Creating backup...');
        
        fetch('<?php echo $baseUrl; ?>/admin/nominal-roll/create-backup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: '_csrf_token=<?php echo urlencode($csrf_token); ?>&type=manual'
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('Backup created successfully!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.error || 'Failed to create backup', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showToast('Error creating backup', 'error');
        });
    }
}

function deleteBackup(backupId, fileName) {
    if (confirm('Delete backup file: ' + fileName + '?')) {
        showLoading('Deleting backup...');
        
        fetch('<?php echo $baseUrl; ?>/admin/nominal-roll/delete-backup/' + backupId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: '_csrf_token=<?php echo urlencode($csrf_token); ?>'
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('Backup deleted successfully!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.error || 'Failed to delete backup', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showToast('Error deleting backup', 'error');
        });
    }
}

function clearActivityLogs() {
    if (confirm('Clear activity logs older than 90 days?')) {
        showLoading('Clearing logs...');
        
        fetch('<?php echo $baseUrl; ?>/admin/nominal-roll/clear-activity-logs', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: '_csrf_token=<?php echo urlencode($csrf_token); ?>'
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('Activity logs cleared successfully!', 'success');
            } else {
                showToast(data.error || 'Failed to clear logs', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showToast('Error clearing logs', 'error');
        });
    }
}

function showResetConfirmation() {
    const modalBody = document.getElementById('modalBody');
    const confirmBtn = document.getElementById('confirmAction');
    
    modalBody.innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>WARNING: This action cannot be undone!</strong>
        </div>
        <p>You are about to reset <strong>ALL</strong> system settings to factory defaults.</p>
        <p>This will reset:</p>
        <ul>
            <li>General settings</li>
            <li>Permission settings</li>
            <li>Export settings</li>
            <li>Backup settings</li>
            <li>System settings</li>
        </ul>
        <p class="text-danger"><strong>Are you absolutely sure?</strong></p>
        <div class="form-group">
            <label for="confirmReset">Type "RESET ALL SETTINGS" to confirm:</label>
            <input type="text" id="confirmReset" class="form-control" placeholder="RESET ALL SETTINGS">
        </div>
    `;
    
    confirmBtn.onclick = function() {
        const confirmText = document.getElementById('confirmReset');
        if (confirmText && confirmText.value === 'RESET ALL SETTINGS') {
            window.confirmationModal.hide();
            resetAllSettings();
        } else {
            alert('Please type "RESET ALL SETTINGS" to confirm.');
        }
    };
    
    window.confirmationModal.show();
}

function resetAllSettings() {
    showLoading('Resetting all settings...');
    
    fetch('<?php echo $baseUrl; ?>/admin/nominal-roll/reset-all-settings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: '_csrf_token=<?php echo urlencode($csrf_token); ?>'
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('All settings reset successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.error || 'Failed to reset settings', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Error resetting settings', 'error');
    });
}

// Utility functions
function showToast(message, type = 'info') {
    // Remove existing toast
    const existingToast = document.querySelector('.toast-container');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `toast-container toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Hide after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

function showLoading(message) {
    // Create loading overlay
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.innerHTML = `
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>${message}</p>
        </div>
    `;
    document.body.appendChild(overlay);
}

function hideLoading() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}
</script>

<style>
.settings-container {
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

/* Settings Navigation */
.settings-navigation {
    margin-bottom: 30px;
    border-bottom: 2px solid #e2e8f0;
}

.settings-nav ul {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    overflow-x: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}

.settings-nav li {
    margin-right: 2px;
}

.settings-nav .nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 15px 25px;
    color: #718096;
    text-decoration: none;
    font-weight: 500;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
    background: none;
    border: none;
    cursor: pointer;
}

.settings-nav .nav-link:hover {
    color: #4a5568;
    border-bottom-color: #cbd5e0;
}

.settings-nav .nav-link.active {
    color: #3490dc;
    border-bottom-color: #3490dc;
    background: linear-gradient(to bottom, #f8fafc, #ffffff);
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(52, 144, 220, 0.1);
}

.settings-nav .nav-link i {
    font-size: 16px;
}

/* Settings Content */
.settings-content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.settings-section {
    display: none;
    animation: fadeIn 0.3s ease;
}

.settings-section.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.section-header {
    padding: 30px 30px 20px;
    border-bottom: 2px solid #e2e8f0;
    background: #f8fafc;
}

.section-header h2 {
    font-size: 24px;
    color: #2d3748;
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-description {
    color: #718096;
    font-size: 16px;
    margin: 0;
}

/* Form Styles */
.settings-form {
    padding: 0;
}

.form-section {
    padding: 30px;
    border-bottom: 1px solid #e2e8f0;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section h3 {
    font-size: 18px;
    color: #2d3748;
    margin: 0 0 25px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-item {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    align-items: center;
    padding-bottom: 25px;
    border-bottom: 1px solid #e2e8f0;
}

.form-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

@media (max-width: 768px) {
    .form-item {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}

.form-label label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    font-size: 16px;
    margin-bottom: 8px;
}

.form-description {
    margin: 0;
    font-size: 14px;
    color: #718096;
    line-height: 1.5;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
}

.toggle-switch input[type="checkbox"] {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}

.toggle-label {
    display: block;
    width: 60px;
    height: 30px;
    background: #cbd5e0;
    border-radius: 15px;
    position: relative;
    cursor: pointer;
    transition: background 0.3s;
}

.toggle-label.checked {
    background: #3490dc;
}

.toggle-handle {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 24px;
    height: 24px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s;
}

.toggle-label.checked .toggle-handle {
    transform: translateX(30px);
}

.toggle-switch.small .toggle-label {
    width: 40px;
    height: 20px;
}

.toggle-switch.small .toggle-handle {
    width: 14px;
    height: 14px;
    top: 3px;
    left: 3px;
}

.toggle-switch.small .toggle-label.checked .toggle-handle {
    transform: translateX(20px);
}

/* Form Controls */
.form-input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
    background: #f8fafc;
}

.form-input:focus {
    outline: none;
    border-color: #3490dc;
    box-shadow: 0 0 0 3px rgba(52, 144, 220, 0.1);
    background: white;
}

/* Permissions Table */
.permissions-table {
    overflow-x: auto;
}

.permissions-table .table {
    width: 100%;
    border-collapse: collapse;
}

.permissions-table .table th {
    padding: 15px 20px;
    text-align: left;
    font-weight: 600;
    color: #4a5568;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #f7fafc;
    border-bottom: 2px solid #e2e8f0;
}

.permissions-table .table td {
    padding: 15px 20px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
    color: #4a5568;
}

.permissions-table .table tbody tr:hover {
    background: #f8fafc;
}

.permissions-table .table td:first-child {
    font-weight: 500;
    color: #2d3748;
}

.permissions-table .text-success {
    color: #38a169;
}

.permissions-table .text-danger {
    color: #e53e3e;
}

/* Backup Actions */
.backup-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
    background: #f0fff4;
    border-radius: 8px;
    border: 1px solid #9ae6b4;
}

.backup-info p {
    margin: 0 0 5px 0;
    font-size: 14px;
    color: #22543d;
}

.backup-info strong {
    font-weight: 600;
}

.backup-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* Backup History */
.backup-history {
    overflow-x: auto;
}

.backup-history .table {
    width: 100%;
    border-collapse: collapse;
}

.backup-history .table th {
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #4a5568;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #f7fafc;
    border-bottom: 2px solid #e2e8f0;
}

.backup-history .table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
    color: #4a5568;
}

.backup-history .table tbody tr:hover {
    background: #f8fafc;
}

/* System Info */
.system-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item label {
    font-size: 12px;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    font-size: 16px;
    color: #2d3748;
    font-weight: 500;
}

/* Danger Zone */
.danger-zone {
    border: 2px solid #fed7d7;
    border-radius: 8px;
    overflow: hidden;
}

.danger-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #fed7d7;
}

.danger-item:last-child {
    border-bottom: none;
}

.danger-label h4 {
    margin: 0 0 8px 0;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.danger-label p {
    margin: 0;
    font-size: 14px;
    color: #718096;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #a0aec0;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
}

.empty-state p {
    margin: 0;
    font-size: 14px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 15px;
    padding: 30px;
    border-top: 1px solid #e2e8f0;
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

.btn-danger:hover {
    background: #c53030;
}

.btn-warning {
    background: #d69e2e;
    color: white;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 14px;
}

/* Badges */
.badge {
    display: inline-block;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 4px;
}

.badge-success {
    background: #f0fff4;
    color: #38a169;
    border: 1px solid #9ae6b4;
}

.badge-danger {
    background: #fff5f5;
    color: #e53e3e;
    border: 1px solid #fed7d7;
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

.alert i {
    font-size: 18px;
}

/* Toast Notifications */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    max-width: 400px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    padding: 15px 20px;
    transform: translateX(400px);
    transition: transform 0.3s ease;
}

.toast-container.show {
    transform: translateX(0);
}

.toast-success {
    border-left: 4px solid #38a169;
}

.toast-error {
    border-left: 4px solid #e53e3e;
}

.toast-info {
    border-left: 4px solid #3490dc;
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toast-content i {
    font-size: 20px;
}

.toast-success .toast-content i {
    color: #38a169;
}

.toast-error .toast-content i {
    color: #e53e3e;
}

.toast-info .toast-content i {
    color: #3490dc;
}

.toast-content span {
    font-size: 14px;
    color: #2d3748;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.loading-content p {
    margin: 15px 0 0 0;
    color: #4a5568;
}

/* Modal Styles */
.modal {
    z-index: 99999;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .settings-container {
        padding: 15px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-actions {
        justify-content: flex-start;
    }
    
    .settings-nav ul {
        flex-wrap: nowrap;
    }
    
    .settings-nav .nav-link {
        padding: 12px 15px;
        font-size: 14px;
    }
    
    .section-header {
        padding: 20px;
    }
    
    .form-section {
        padding: 20px;
    }
    
    .backup-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .backup-buttons {
        justify-content: center;
    }
    
    .danger-item {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .form-actions {
        flex-direction: column;
        padding: 20px;
    }
    
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    .modal-dialog {
        margin: 10px;
    }
}

@media (max-width: 480px) {
    .header-title h1 {
        font-size: 24px;
    }
    
    .section-header h2 {
        font-size: 20px;
    }
    
    .settings-nav .nav-link {
        padding: 10px 12px;
        font-size: 13px;
    }
    
    .permissions-table {
        font-size: 12px;
    }
}
</style>