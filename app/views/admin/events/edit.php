<?php
$baseUrl = $data['baseUrl'] ?? '';
$categories = $data['categories'] ?? [];
$error = $data['error'] ?? '';
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrfToken;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        <!-- Same CSS styles as edit.php (copy all CSS from edit.php) -->
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
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 40px;
        }
        
        .editor-container {
            height: 400px;
            margin-bottom: 20px;
        }
        
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
            max-height: 200px;
            object-fit: cover;
        }
        
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
        
        .registration-details {
            background-color: #f0f9ff;
            border-left: 4px solid var(--info-color);
            padding: 16px;
            border-radius: 6px;
            margin-top: 16px;
        }
        
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
            <h1 class="page-title">Create New Event</h1>
            <a href="<?php echo $baseUrl; ?>/admin/events" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
        
        <!-- Form -->
        <form method="POST" action="<?php echo $baseUrl; ?>/admin/events/store" class="form-container" id="eventForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            
            <!-- Tabs -->
            <div class="form-tabs">
                <button type="button" class="tab-btn active" data-tab="content">Content</button>
                <button type="button" class="tab-btn" data-tab="event-details">Event Details</button>
                <button type="button" class="tab-btn" data-tab="registration">Registration</button>
                <button type="button" class="tab-btn" data-tab="media">Media</button>
                <button type="button" class="tab-btn" data-tab="seo">SEO</button>
            </div>
            
            <!-- Content Tab -->
            <div class="tab-content active" id="content-tab">
                <div class="form-group">
                    <label class="form-label">Event Title <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" 
                           required placeholder="Enter event title">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" 
                           value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>" 
                           placeholder="Auto-generated from title">
                    <small style="color: var(--text-light); margin-top: 4px; display: block;">
                        URL-friendly version of the title
                    </small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Event Excerpt</label>
                    <textarea name="excerpt" class="form-control" 
                              placeholder="Brief summary of the event (appears in event listings)"
                              rows="3"><?php echo htmlspecialchars($_POST['excerpt'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Event Description <span class="required">*</span></label>
                    <div id="editor" class="editor-container"></div>
                    <textarea name="content" id="content" style="display: none;" required>
                        <?php echo htmlspecialchars($_POST['content'] ?? ''); ?>
                    </textarea>
                </div>
            </div>
            
            <!-- Event Details Tab -->
            <div class="tab-content" id="event-details-tab">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>" 
                                <?php echo ($_POST['category'] ?? '') === $category ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Event Type</label>
                        <select name="event_type" class="form-control">
                            <option value="conference" <?php echo ($_POST['event_type'] ?? 'conference') === 'conference' ? 'selected' : ''; ?>>Conference</option>
                            <option value="workshop" <?php echo ($_POST['event_type'] ?? '') === 'workshop' ? 'selected' : ''; ?>>Workshop</option>
                            <option value="seminar" <?php echo ($_POST['event_type'] ?? '') === 'seminar' ? 'selected' : ''; ?>>Seminar</option>
                            <option value="webinar" <?php echo ($_POST['event_type'] ?? '') === 'webinar' ? 'selected' : ''; ?>>Webinar</option>
                            <option value="training" <?php echo ($_POST['event_type'] ?? '') === 'training' ? 'selected' : ''; ?>>Training</option>
                            <option value="meeting" <?php echo ($_POST['event_type'] ?? '') === 'meeting' ? 'selected' : ''; ?>>Meeting</option>
                            <option value="networking" <?php echo ($_POST['event_type'] ?? '') === 'networking' ? 'selected' : ''; ?>>Networking</option>
                            <option value="other" <?php echo ($_POST['event_type'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date <span class="required">*</span></label>
                        <input type="date" name="event_date" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['event_date'] ?? ''); ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="date" name="event_end_date" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['event_end_date'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="event_time" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['event_time'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Venue/Location</label>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="venue_name" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['venue_name'] ?? ''); ?>" 
                                   placeholder="Venue name (e.g., Grand Hall)">
                        </div>
                        <div class="form-group">
                            <input type="text" name="venue_address" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['venue_address'] ?? ''); ?>" 
                                   placeholder="Full address">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="venue_city" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['venue_city'] ?? ''); ?>" 
                                   placeholder="City">
                        </div>
                        <div class="form-group">
                            <input type="text" name="venue_country" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['venue_country'] ?? ''); ?>" 
                                   placeholder="Country">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Event Status</label>
                    <select name="status" class="form-control">
                        <option value="draft" <?php echo ($_POST['status'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="upcoming" <?php echo ($_POST['status'] ?? '') === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Organizer Details</label>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="organizer" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['organizer'] ?? ''); ?>" 
                                   placeholder="Organizer name">
                        </div>
                        <div class="form-group">
                            <input type="email" name="organizer_email" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['organizer_email'] ?? ''); ?>" 
                                   placeholder="Organizer email">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="organizer_phone" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['organizer_phone'] ?? ''); ?>" 
                               placeholder="Organizer phone">
                    </div>
                </div>
            </div>
            
            <!-- Registration Tab -->
            <div class="tab-content" id="registration-tab">
                <div class="form-group">
                    <label class="form-label">Registration Type</label>
                    <select name="registration_type" class="form-control" id="registration-type">
                        <option value="none" <?php echo ($_POST['registration_type'] ?? 'none') === 'none' ? 'selected' : ''; ?>>No Registration</option>
                        <option value="free" <?php echo ($_POST['registration_type'] ?? '') === 'free' ? 'selected' : ''; ?>>Free Registration</option>
                        <option value="paid" <?php echo ($_POST['registration_type'] ?? '') === 'paid' ? 'selected' : ''; ?>>Paid Registration</option>
                        <option value="invite" <?php echo ($_POST['registration_type'] ?? '') === 'invite' ? 'selected' : ''; ?>>Invite Only</option>
                    </select>
                </div>
                
                <div id="registration-details" style="<?php echo ($_POST['registration_type'] ?? 'none') !== 'none' ? '' : 'display: none;'; ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Registration Deadline</label>
                            <input type="date" name="registration_deadline" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['registration_deadline'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Maximum Attendees</label>
                            <input type="number" name="max_attendees" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['max_attendees'] ?? ''); ?>" 
                                   min="1" placeholder="Leave empty for unlimited">
                        </div>
                    </div>
                    
                    <div id="paid-registration" style="<?php echo ($_POST['registration_type'] ?? '') === 'paid' ? '' : 'display: none;'; ?>">
                        <div class="form-group">
                            <label class="form-label">Price Information</label>
                            <div class="form-row">
                                <div class="form-group">
                                    <input type="number" name="price" class="form-control" 
                                           value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" 
                                           step="0.01" min="0" placeholder="Price amount">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="currency" class="form-control" 
                                           value="<?php echo htmlspecialchars($_POST['currency'] ?? 'USD'); ?>" 
                                           placeholder="Currency (e.g., USD)">
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea name="price_details" class="form-control" 
                                          placeholder="Price details (early bird discounts, group rates, etc.)"
                                          rows="3"><?php echo htmlspecialchars($_POST['price_details'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Registration Form Fields (Optional)</label>
                        <textarea name="registration_fields" class="form-control" 
                                  placeholder="Add custom fields for registration (one per line, format: field_name|Field Label|type)"
                                  rows="4"><?php echo htmlspecialchars($_POST['registration_fields'] ?? ''); ?></textarea>
                        <small style="color: var(--text-light); margin-top: 4px; display: block;">
                            Example: phone|Phone Number|tel, company|Company|text
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirmation Message</label>
                        <textarea name="confirmation_message" class="form-control" 
                                  placeholder="Message to show after registration"
                                  rows="3"><?php echo htmlspecialchars($_POST['confirmation_message'] ?? 'Thank you for registering! We look forward to seeing you at the event.'); ?></textarea>
                    </div>
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
                        <p>Click to upload event banner/image</p>
                        <p style="color: var(--text-light); font-size: 14px;">
                            Recommended size: 1200x630px (Max 5MB)
                        </p>
                    </div>
                    
                    <div class="image-preview" style="display: none;">
                        <img src="" alt="Featured Image">
                        <button type="button" onclick="removeImage()" style="margin-top: 10px; padding: 8px 16px; background: var(--danger-color); color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Remove Image
                        </button>
                    </div>
                    
                    <input type="file" id="image-input" accept="image/*" style="display: none;" 
                           onchange="previewImage(event)">
                    <input type="hidden" name="featured_image" id="featured-image">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Gallery Images (Optional)</label>
                    <div class="image-upload" onclick="document.getElementById('gallery-input').click()">
                        <div class="upload-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <p>Click to add gallery images</p>
                        <p style="color: var(--text-light); font-size: 14px;">
                            You can select multiple images
                        </p>
                    </div>
                    <input type="file" id="gallery-input" accept="image/*" multiple style="display: none;">
                    
                    <div id="gallery-preview" style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                        <!-- Gallery images will be previewed here -->
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tags</label>
                    <input type="text" name="tags" class="form-control" 
                           value="<?php echo htmlspecialchars($_POST['tags'] ?? ''); ?>" 
                           placeholder="e.g., nursing, conference, education (comma separated)">
                </div>
            </div>
            
            <!-- SEO Tab -->
            <div class="tab-content" id="seo-tab">
                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" 
                           value="<?php echo htmlspecialchars($_POST['meta_title'] ?? ''); ?>" 
                           placeholder="Title for search engines (optional)">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" 
                              placeholder="Description for search engines (optional)"
                              rows="3"><?php echo htmlspecialchars($_POST['meta_description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" 
                           value="<?php echo htmlspecialchars($_POST['meta_keywords'] ?? ''); ?>" 
                           placeholder="Keywords for search engines (optional)">
                </div>
                
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" value="1" 
                               <?php echo isset($_POST['is_featured']) ? 'checked' : ''; ?>>
                        <span>Featured Event</span>
                    </label>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" name="allow_comments" value="1" 
                               <?php echo isset($_POST['allow_comments']) ? 'checked' : ''; ?>>
                        <span>Allow Comments</span>
                    </label>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <div>
                    <a href="<?php echo $baseUrl; ?>/admin/events" class="btn btn-outline">
                        Cancel
                    </a>
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" name="save_draft" value="1" class="btn btn-secondary">
                        Save as Draft
                    </button>
                    <button type="submit" name="publish" value="1" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Create Event
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
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
            }
        });
        
        // Set initial content
        const contentField = document.getElementById('content');
        quill.root.innerHTML = contentField.value || '';
        
        // Update hidden content field when editor changes
        quill.on('text-change', function() {
            contentField.value = quill.root.innerHTML;
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
            const preview = document.querySelector('.image-preview');
            const uploadArea = document.querySelector('.image-upload');
            const imageUrl = document.getElementById('featured-image');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.querySelector('img').src = e.target.result;
                    preview.style.display = 'block';
                    uploadArea.style.display = 'none';
                    imageUrl.value = e.target.result;
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function removeImage() {
            const preview = document.querySelector('.image-preview');
            const uploadArea = document.querySelector('.image-upload');
            const imageUrl = document.getElementById('featured-image');
            const fileInput = document.getElementById('image-input');
            
            preview.style.display = 'none';
            uploadArea.style.display = 'block';
            imageUrl.value = '';
            fileInput.value = '';
        }
        
        // Gallery image upload
        const galleryInput = document.getElementById('gallery-input');
        const galleryPreview = document.getElementById('gallery-preview');
        
        galleryInput.addEventListener('change', function() {
            const files = this.files;
            galleryPreview.innerHTML = '';
            
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.style.width = '100px';
                    imgContainer.style.height = '100px';
                    imgContainer.style.position = 'relative';
                    imgContainer.style.borderRadius = '8px';
                    imgContainer.style.overflow = 'hidden';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '×';
                    removeBtn.style.position = 'absolute';
                    removeBtn.style.top = '5px';
                    removeBtn.style.right = '5px';
                    removeBtn.style.background = 'var(--danger-color)';
                    removeBtn.style.color = 'white';
                    removeBtn.style.border = 'none';
                    removeBtn.style.borderRadius = '50%';
                    removeBtn.style.width = '24px';
                    removeBtn.style.height = '24px';
                    removeBtn.style.cursor = 'pointer';
                    removeBtn.onclick = function() {
                        galleryPreview.removeChild(imgContainer);
                    };
                    
                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    galleryPreview.appendChild(imgContainer);
                };
                reader.readAsDataURL(files[i]);
            }
        });
        
        // Show/hide registration details based on registration type
        const registrationType = document.getElementById('registration-type');
        const registrationDetails = document.getElementById('registration-details');
        const paidRegistration = document.getElementById('paid-registration');
        
        registrationType.addEventListener('change', function() {
            if (this.value === 'none') {
                registrationDetails.style.display = 'none';
            } else {
                registrationDetails.style.display = 'block';
                
                if (this.value === 'paid') {
                    paidRegistration.style.display = 'block';
                } else {
                    paidRegistration.style.display = 'none';
                }
            }
        });
        
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
        
        // Validate date ranges
        const startDateInput = document.querySelector('input[name="event_date"]');
        const endDateInput = document.querySelector('input[name="event_end_date"]');
        const regDeadlineInput = document.querySelector('input[name="registration_deadline"]');
        
        endDateInput.addEventListener('change', function() {
            if (startDateInput.value && this.value) {
                if (new Date(this.value) < new Date(startDateInput.value)) {
                    alert('End date cannot be before start date');
                    this.value = '';
                }
            }
        });
        
        regDeadlineInput.addEventListener('change', function() {
            if (startDateInput.value && this.value) {
                if (new Date(this.value) > new Date(startDateInput.value)) {
                    alert('Registration deadline cannot be after the event start date');
                    this.value = '';
                }
            }
        });
        
        // Form validation
        const form = document.getElementById('eventForm');
        form.addEventListener('submit', function(e) {
            // Ensure content is filled
            if (quill.getText().trim().length < 10) {
                e.preventDefault();
                alert('Please provide event description (at least 10 characters)');
                document.querySelector('[data-tab="content"]').click();
                quill.focus();
                return false;
            }
            
            // Validate dates
            if (!startDateInput.value) {
                e.preventDefault();
                alert('Please select an event start date');
                document.querySelector('[data-tab="event-details"]').click();
                startDateInput.focus();
                return false;
            }
            
            return true;
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                form.querySelector('button[name="publish"]').click();
            }
            
            // Ctrl/Cmd + 1-5 to switch tabs
            if ((e.ctrlKey || e.metaKey) && e.key >= '1' && e.key <= '5') {
                e.preventDefault();
                const tabIndex = parseInt(e.key) - 1;
                if (tabBtns[tabIndex]) {
                    tabBtns[tabIndex].click();
                }
            }
        });
        
        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        startDateInput.min = today;
        if (!startDateInput.value) {
            startDateInput.value = today;
        }
    </script>
</body>
</html>