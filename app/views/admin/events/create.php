<?php
$baseUrl = $data['baseUrl'] ?? '';
$categories = $data['categories'] ?? [];
$event = $data['event'] ?? [];
$error = $data['error'] ?? '';
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrfToken;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #6b7280;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
            --text-dark: #111827;
            --text-light: #6b7280;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            line-height: 1.5;
        }
        
        .admin-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        /* Status Info */
        .status-info {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .status-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        .status-label {
            font-weight: 500;
            color: var(--text-light);
        }
        
        .status-value {
            font-weight: 600;
        }
        
        /* Form */
        .form-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .form-tabs {
            display: flex;
            background: var(--light-bg);
            border-bottom: 1px solid var(--border-color);
        }
        
        .tab-btn {
            padding: 16px 24px;
            background: none;
            border: none;
            font-weight: 500;
            color: var(--text-light);
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .tab-btn:hover {
            color: var(--text-dark);
            background: rgba(255,255,255,0.5);
        }
        
        .tab-btn.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: white;
        }
        
        .tab-content {
            display: none;
            padding: 30px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-dark);
        }
        
        .form-label .required {
            color: var(--danger-color);
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        /* Editor */
        .editor-container {
            height: 400px;
            margin-bottom: 20px;
        }
        
        /* Checkboxes */
        .checkbox-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }
        
        /* Image Upload */
        .image-upload {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .image-upload:hover {
            border-color: var(--primary-color);
            background: rgba(79, 70, 229, 0.05);
        }
        
        .upload-icon {
            font-size: 48px;
            color: var(--text-light);
            margin-bottom: 16px;
        }
        
        .image-preview {
            max-width: 300px;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .image-preview img {
            width: 100%;
            height: auto;
        }
        
        /* Event Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-upcoming { background: #dcfce7; color: #166534; }
        .status-ongoing { background: #fef3c7; color: #92400e; }
        .status-completed { background: #e5e7eb; color: #374151; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        /* Buttons */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            background: var(--light-bg);
            border-top: 1px solid var(--border-color);
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-outline {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--light-bg);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        /* Error */
        .error-message {
            background-color: #fee2e2;
            color: var(--danger-color);
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        /* Registration Fields */
        .registration-fields {
            background: var(--light-bg);
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        /* Gallery Preview */
        .gallery-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }
        
        .gallery-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        
        .remove-image {
            position: absolute;
            top: 8px;
            right: 8px;
            background: var(--danger-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Error Message -->
        <?php if ($error): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>
        
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Edit Event</h1>
            <a href="<?php echo $baseUrl; ?>/admin/events" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Events
            </a>
        </div>
        
        <!-- Status Information -->
        <div class="status-info">
            <div class="status-item">
                <span class="status-label">Status:</span>
                <span class="status-value">
                    <?php
                    $status = 'upcoming';
                    if (isset($event['status'])) {
                        $status = $event['status'];
                    } elseif (isset($event['start_date'])) {
                        $startDate = new DateTime($event['start_date']);
                        $endDate = new DateTime($event['end_date']);
                        $now = new DateTime();
                        if ($now > $endDate) {
                            $status = 'completed';
                        } elseif ($now >= $startDate && $now <= $endDate) {
                            $status = 'ongoing';
                        } elseif ($event['is_cancelled'] ?? false) {
                            $status = 'cancelled';
                        }
                    }
                    ?>
                    <span class="status-badge status-<?php echo $status; ?>">
                        <?php echo ucfirst($status); ?>
                    </span>
                </span>
            </div>
            
            <div class="status-item">
                <span class="status-label">Event ID:</span>
                <span class="status-value">#<?php echo htmlspecialchars($event['id'] ?? ''); ?></span>
            </div>
            
            <div class="status-item">
                <span class="status-label">Created:</span>
                <span class="status-value"><?php echo isset($event['created_at']) ? date('M d, Y', strtotime($event['created_at'])) : ''; ?></span>
            </div>
            
            <div class="status-item">
                <span class="status-label">Last Updated:</span>
                <span class="status-value"><?php echo isset($event['updated_at']) ? date('M d, Y', strtotime($event['updated_at'])) : 'Never'; ?></span>
            </div>
        </div>
        
        <!-- Form -->
        <form method="POST" action="<?php echo $baseUrl; ?>/admin/events/update/<?php echo htmlspecialchars($event['id'] ?? ''); ?>" class="form-container" id="eventForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id'] ?? ''); ?>">
            
            <!-- Tabs -->
            <div class="form-tabs">
                <button type="button" class="tab-btn active" data-tab="basic">Basic Info</button>
                <button type="button" class="tab-btn" data-tab="details">Event Details</button>
                <button type="button" class="tab-btn" data-tab="registration">Registration</button>
                <button type="button" class="tab-btn" data-tab="media">Media</button>
                <button type="button" class="tab-btn" data-tab="seo">SEO</button>
            </div>
            
            <!-- Basic Info Tab -->
            <div class="tab-content active" id="basic-tab">
                <div class="form-group">
                    <label class="form-label">Event Title <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($event['title'] ?? ''); ?>" 
                           required placeholder="Enter event title">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" 
                           value="<?php echo htmlspecialchars($event['slug'] ?? ''); ?>" 
                           placeholder="Auto-generated from title">
                    <small style="color: var(--text-light); margin-top: 4px; display: block;">
                        URL-friendly version of the title (auto-generates if left blank)
                    </small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Short Description</label>
                    <textarea name="excerpt" class="form-control" 
                              placeholder="Brief summary of the event (shown in listings)"
                              rows="3"><?php echo htmlspecialchars($event['excerpt'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Full Description <span class="required">*</span></label>
                    <div id="editor" class="editor-container"></div>
                    <textarea name="content" id="content" style="display: none;" required>
                        <?php echo htmlspecialchars($event['content'] ?? ''); ?>
                    </textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>" 
                                <?php echo ($event['category'] ?? '') === $category ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Event Type</label>
                        <select name="event_type" class="form-control">
                            <option value="conference" <?php echo ($event['event_type'] ?? '') === 'conference' ? 'selected' : ''; ?>>Conference</option>
                            <option value="workshop" <?php echo ($event['event_type'] ?? '') === 'workshop' ? 'selected' : ''; ?>>Workshop</option>
                            <option value="seminar" <?php echo ($event['event_type'] ?? '') === 'seminar' ? 'selected' : ''; ?>>Seminar</option>
                            <option value="webinar" <?php echo ($event['event_type'] ?? '') === 'webinar' ? 'selected' : ''; ?>>Webinar</option>
                            <option value="training" <?php echo ($event['event_type'] ?? '') === 'training' ? 'selected' : ''; ?>>Training</option>
                            <option value="networking" <?php echo ($event['event_type'] ?? '') === 'networking' ? 'selected' : ''; ?>>Networking</option>
                            <option value="other" <?php echo ($event['event_type'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Event Details Tab -->
            <div class="tab-content" id="details-tab">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date & Time <span class="required">*</span></label>
                        <input type="datetime-local" name="start_date" class="form-control" 
                               value="<?php echo isset($event['start_date']) ? date('Y-m-d\TH:i', strtotime($event['start_date'])) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">End Date & Time <span class="required">*</span></label>
                        <input type="datetime-local" name="end_date" class="form-control" 
                               value="<?php echo isset($event['end_date']) ? date('Y-m-d\TH:i', strtotime($event['end_date'])) : ''; ?>" 
                               required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Venue/Location</label>
                    <input type="text" name="venue" class="form-control" 
                           value="<?php echo htmlspecialchars($event['venue'] ?? ''); ?>" 
                           placeholder="e.g., Main Auditorium, Virtual Event">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" 
                               value="<?php echo htmlspecialchars($event['address'] ?? ''); ?>" 
                               placeholder="Street address">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" 
                               value="<?php echo htmlspecialchars($event['city'] ?? ''); ?>" 
                               placeholder="City">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">State/Province</label>
                        <input type="text" name="state" class="form-control" 
                               value="<?php echo htmlspecialchars($event['state'] ?? ''); ?>" 
                               placeholder="State or Province">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control" 
                               value="<?php echo htmlspecialchars($event['country'] ?? ''); ?>" 
                               placeholder="Country">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Google Maps Embed Code</label>
                    <textarea name="map_embed" class="form-control" 
                              placeholder="Paste Google Maps embed code here"
                              rows="3"><?php echo htmlspecialchars($event['map_embed'] ?? ''); ?></textarea>
                    <small style="color: var(--text-light); margin-top: 4px; display: block;">
                        Optional: Embed code for location map
                    </small>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Organizer Name</label>
                        <input type="text" name="organizer" class="form-control" 
                               value="<?php echo htmlspecialchars($event['organizer'] ?? ''); ?>" 
                               placeholder="Event organizer or host">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Organizer Email</label>
                        <input type="email" name="organizer_email" class="form-control" 
                               value="<?php echo htmlspecialchars($event['organizer_email'] ?? ''); ?>" 
                               placeholder="contact@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Organizer Phone</label>
                        <input type="tel" name="organizer_phone" class="form-control" 
                               value="<?php echo htmlspecialchars($event['organizer_phone'] ?? ''); ?>" 
                               placeholder="+1234567890">
                    </div>
                </div>
            </div>
            
            <!-- Registration Tab -->
            <div class="tab-content" id="registration-tab">
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="registration_required" value="1" 
                               <?php echo isset($event['registration_required']) && $event['registration_required'] ? 'checked' : 'checked'; ?>
                               id="reg-required">
                        <span>Registration Required</span>
                    </label>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" name="limited_seats" value="1" 
                               <?php echo isset($event['limited_seats']) && $event['limited_seats'] ? 'checked' : ''; ?>
                               id="limited-seats">
                        <span>Limited Seats Available</span>
                    </label>
                </div>
                
                <div id="registration-fields" class="registration-fields">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Registration Deadline</label>
                            <input type="datetime-local" name="registration_deadline" class="form-control" 
                                   value="<?php echo isset($event['registration_deadline']) ? date('Y-m-d\TH:i', strtotime($event['registration_deadline'])) : ''; ?>">
                        </div>
                        
                        <div class="form-group" id="max-attendees-field" style="display: none;">
                            <label class="form-label">Maximum Attendees</label>
                            <input type="number" name="max_attendees" class="form-control" 
                                   value="<?php echo htmlspecialchars($event['max_attendees'] ?? '100'); ?>" 
                                   min="1">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Registration Link/URL</label>
                        <input type="url" name="registration_url" class="form-control" 
                               value="<?php echo htmlspecialchars($event['registration_url'] ?? ''); ?>" 
                               placeholder="https://example.com/register">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Registration Fee Type</label>
                            <select name="fee_type" class="form-control">
                                <option value="free" <?php echo ($event['fee_type'] ?? 'free') === 'free' ? 'selected' : ''; ?>>Free</option>
                                <option value="paid" <?php echo ($event['fee_type'] ?? '') === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                <option value="donation" <?php echo ($event['fee_type'] ?? '') === 'donation' ? 'selected' : ''; ?>>Donation</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="fee-amount-field" style="display: none;">
                            <label class="form-label">Fee Amount</label>
                            <div style="display: flex; align-items: center;">
                                <span style="padding: 12px 16px; background: var(--light-bg); border: 1px solid var(--border-color); border-right: none; border-radius: 6px 0 0 6px;">$</span>
                                <input type="number" name="fee_amount" class="form-control" 
                                       value="<?php echo htmlspecialchars($event['fee_amount'] ?? ''); ?>" 
                                       min="0" step="0.01" 
                                       style="border-radius: 0 6px 6px 0;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Registration Instructions</label>
                        <textarea name="registration_instructions" class="form-control" 
                                  placeholder="Special instructions for registration"
                                  rows="3"><?php echo htmlspecialchars($event['registration_instructions'] ?? ''); ?></textarea>
                    </div>
                    
                    <!-- Registration Stats -->
                    <?php if (isset($event['registration_count'])): ?>
                    <div class="form-group" style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px;">
                        <h3 style="margin-bottom: 16px; font-size: 18px;">Registration Statistics</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Total Registrations:</label>
                                <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);">
                                    <?php echo htmlspecialchars($event['registration_count']); ?>
                                </div>
                            </div>
                            
                            <?php if (isset($event['max_attendees'])): ?>
                            <div class="form-group">
                                <label class="form-label">Available Seats:</label>
                                <div style="font-size: 24px; font-weight: bold; color: var(--success-color);">
                                    <?php echo max(0, $event['max_attendees'] - $event['registration_count']); ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Fill Percentage:</label>
                                <div style="font-size: 24px; font-weight: bold; color: var(--info-color);">
                                    <?php echo round(($event['registration_count'] / $event['max_attendees']) * 100, 1); ?>%
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div style="margin-top: 16px;">
                            <a href="<?php echo $baseUrl; ?>/admin/events/registrations/<?php echo htmlspecialchars($event['id']); ?>" class="btn btn-outline">
                                <i class="fas fa-users"></i> View All Registrations
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Media Tab -->
            <div class="tab-content" id="media-tab">
                <div class="form-group">
                    <label class="form-label">Featured Image</label>
                    <div class="image-upload" onclick="document.getElementById('image-input').click()">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <p>Click to upload featured image</p>
                        <p style="color: var(--text-light); font-size: 14px;">
                            Recommended size: 1200x630px (Max 5MB)
                        </p>
                    </div>
                    <input type="file" id="image-input" accept="image/*" style="display: none;" 
                           onchange="previewImage(event)">
                    <input type="hidden" name="featured_image" id="featured-image" 
                           value="<?php echo htmlspecialchars($event['featured_image'] ?? ''); ?>">
                    
                    <?php if (!empty($event['featured_image'])): ?>
                    <div class="image-preview" id="image-preview">
                        <img src="<?php echo htmlspecialchars($event['featured_image']); ?>" alt="Current Image">
                        <button type="button" onclick="removeImage()" style="margin-top: 10px; padding: 8px 16px; background: var(--danger-color); color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Remove Image
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Event Gallery (Multiple Images)</label>
                    <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*">
                    <small style="color: var(--text-light); margin-top: 4px; display: block;">
                        Upload additional images for event gallery (Max 10 images, 5MB each)
                    </small>
                    
                    <?php if (!empty($event['gallery_images'])): ?>
                    <div class="gallery-preview" id="gallery-preview">
                        <?php 
                        $galleryImages = is_array($event['gallery_images']) ? $event['gallery_images'] : json_decode($event['gallery_images'] ?? '[]', true);
                        foreach ($galleryImages as $index => $image): ?>
                        <div class="gallery-item">
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="Gallery Image <?php echo $index + 1; ?>">
                            <button type="button" class="remove-image" onclick="removeGalleryImage(<?php echo $index; ?>)">
                                <i class="fas fa-times"></i>
                            </button>
                            <input type="hidden" name="existing_gallery_images[]" value="<?php echo htmlspecialchars($image); ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tags</label>
                    <input type="text" name="tags" class="form-control" 
                           value="<?php echo htmlspecialchars($event['tags'] ?? ''); ?>" 
                           placeholder="e.g., nursing, conference, healthcare (comma separated)">
                </div>
            </div>
            
            <!-- SEO Tab -->
            <div class="tab-content" id="seo-tab">
                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" 
                           value="<?php echo htmlspecialchars($event['meta_title'] ?? ''); ?>" 
                           placeholder="Title for search engines (optional)">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" 
                              placeholder="Description for search engines (optional)"
                              rows="3"><?php echo htmlspecialchars($event['meta_description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" 
                           value="<?php echo htmlspecialchars($event['meta_keywords'] ?? ''); ?>" 
                           placeholder="Keywords for search engines (optional)">
                </div>
                
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_published" value="1" 
                               <?php echo isset($event['is_published']) && $event['is_published'] ? 'checked' : ''; ?>>
                        <span>Published</span>
                    </label>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" value="1" 
                               <?php echo isset($event['is_featured']) && $event['is_featured'] ? 'checked' : ''; ?>>
                        <span>Featured event</span>
                    </label>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_cancelled" value="1" 
                               <?php echo isset($event['is_cancelled']) && $event['is_cancelled'] ? 'checked' : ''; ?>>
                        <span>Cancelled</span>
                    </label>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <div style="display: flex; gap: 12px;">
                    <a href="<?php echo $baseUrl; ?>/admin/events" class="btn btn-outline">
                        Cancel
                    </a>
                    <button type="button" onclick="confirmDelete()" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Event
                    </button>
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" name="save_draft" value="1" class="btn btn-secondary">
                        Save as Draft
                    </button>
                    <button type="submit" name="update" value="1" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Event
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Quill Editor
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video', 'blockquote', 'code-block'],
                    ['clean']
                ]
            },
            placeholder: 'Write detailed event description here...'
        });
        
        // Set initial content
        const contentField = document.getElementById('content');
        quill.root.innerHTML = contentField.value || '';
        
        // Update hidden content field when editor changes
        quill.on('text-change', function() {
            contentField.value = quill.root.innerHTML;
        });
        
        // Initialize date time pickers
        flatpickr("input[name='start_date']", {
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });
        
        flatpickr("input[name='end_date']", {
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });
        
        flatpickr("input[name='registration_deadline']", {
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });
        
        // Tab switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Update active tab button
                tabBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Show active tab content
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    if (content.id === `${tabId}-tab`) {
                        content.classList.add('active');
                    }
                });
            });
        });
        
        // Image upload preview
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const imageUrl = document.getElementById('featured-image');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (!preview) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'image-preview';
                        previewDiv.id = 'image-preview';
                        previewDiv.innerHTML = `
                            <img src="${e.target.result}" alt="Preview">
                            <button type="button" onclick="removeImage()" style="margin-top: 10px; padding: 8px 16px; background: var(--danger-color); color: white; border: none; border-radius: 4px; cursor: pointer;">
                                Remove Image
                            </button>
                        `;
                        input.parentNode.appendChild(previewDiv);
                    } else {
                        preview.querySelector('img').src = e.target.result;
                        preview.style.display = 'block';
                    }
                    imageUrl.value = e.target.result;
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function removeImage() {
            const preview = document.getElementById('image-preview');
            const imageUrl = document.getElementById('featured-image');
            const fileInput = document.getElementById('image-input');
            
            if (preview) {
                preview.remove();
            }
            imageUrl.value = '';
            fileInput.value = '';
        }
        
        function removeGalleryImage(index) {
            const galleryItems = document.querySelectorAll('.gallery-item');
            if (galleryItems[index]) {
                galleryItems[index].remove();
            }
        }
        
        // Registration fields toggle
        const regRequiredCheckbox = document.getElementById('reg-required');
        const limitedSeatsCheckbox = document.getElementById('limited-seats');
        const regFields = document.getElementById('registration-fields');
        const maxAttendeesField = document.getElementById('max-attendees-field');
        const feeTypeSelect = document.querySelector('select[name="fee_type"]');
        const feeAmountField = document.getElementById('fee-amount-field');
        
        regRequiredCheckbox.addEventListener('change', function() {
            if (this.checked) {
                regFields.style.display = 'block';
            } else {
                regFields.style.display = 'none';
            }
        });
        
        limitedSeatsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                maxAttendeesField.style.display = 'block';
            } else {
                maxAttendeesField.style.display = 'none';
            }
        });
        
        feeTypeSelect.addEventListener('change', function() {
            if (this.value === 'paid') {
                feeAmountField.style.display = 'block';
            } else {
                feeAmountField.style.display = 'none';
            }
        });
        
        // Initialize visibility
        if (regRequiredCheckbox.checked) {
            regFields.style.display = 'block';
        }
        if (limitedSeatsCheckbox.checked) {
            maxAttendeesField.style.display = 'block';
        }
        if (feeTypeSelect.value === 'paid') {
            feeAmountField.style.display = 'block';
        }
        
        // Auto-generate slug from title
        const titleInput = document.querySelector('input[name="title"]');
        const slugInput = document.querySelector('input[name="slug"]');
        
        titleInput.addEventListener('blur', function() {
            if (!slugInput.value) {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');
                slugInput.value = slug;
            }
        });
        
        // Form validation
        const form = document.getElementById('eventForm');
        form.addEventListener('submit', function(e) {
            // Validate content
            if (quill.getText().trim().length < 10) {
                e.preventDefault();
                alert('Please provide event description (at least 10 characters)');
                document.querySelector('[data-tab="basic"]').click();
                quill.focus();
                return false;
            }
            
            // Validate dates
            const startDate = new Date(document.querySelector('input[name="start_date"]').value);
            const endDate = new Date(document.querySelector('input[name="end_date"]').value);
            
            if (endDate <= startDate) {
                e.preventDefault();
                alert('End date must be after start date');
                document.querySelector('[data-tab="details"]').click();
                return false;
            }
            
            // If registration required, validate registration deadline
            if (regRequiredCheckbox.checked) {
                const regDeadline = document.querySelector('input[name="registration_deadline"]').value;
                if (regDeadline) {
                    const deadlineDate = new Date(regDeadline);
                    if (deadlineDate > startDate) {
                        e.preventDefault();
                        alert('Registration deadline must be before the event start date');
                        document.querySelector('[data-tab="registration"]').click();
                        return false;
                    }
                }
            }
            
            return true;
        });
        
        // Delete confirmation
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
                window.location.href = '<?php echo $baseUrl; ?>/admin/events/delete/<?php echo htmlspecialchars($event["id"] ?? ""); ?>';
            }
        }
        
        // Auto-save
        let autoSaveTimer;
        const formInputs = form.querySelectorAll('input, textarea, select');
        
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(saveDraft, 3000);
            });
        });
        
        function saveDraft() {
            const formData = new FormData();
            formInputs.forEach(input => {
                if (input.name) {
                    if (input.type === 'file') {
                        if (input.files.length > 0) {
                            for (let i = 0; i < input.files.length; i++) {
                                formData.append(input.name, input.files[i]);
                            }
                        }
                    } else if (input.type === 'checkbox') {
                        formData.append(input.name, input.checked);
                    } else {
                        formData.append(input.name, input.value);
                    }
                }
            });
            
            formData.append('auto_save', '1');
            formData.append('id', '<?php echo htmlspecialchars($event["id"] ?? ""); ?>');
            formData.append('content', quill.root.innerHTML);
            
            fetch('<?php echo $baseUrl; ?>/admin/events/auto-save', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Event auto-saved');
                }
            })
            .catch(error => {
                console.error('Auto-save error:', error);
            });
        }
    </script>
</body>
</html>