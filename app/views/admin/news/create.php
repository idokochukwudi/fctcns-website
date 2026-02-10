<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if $data array exists and extract variables
$baseUrl = isset($data['baseUrl']) ? $data['baseUrl'] : '';

// If baseUrl is empty, try to get it from BASE_URL constant
if (empty($baseUrl) && defined('BASE_URL')) {
    $baseUrl = BASE_URL;
}

// Fallback to empty string if still empty
$baseUrl = $baseUrl ?? '';

$categories = isset($data['categories']) ? $data['categories'] : [];
$news = isset($data['news']) ? $data['news'] : [];
$error = isset($data['error']) ? $data['error'] : '';

// Generate CSRF token using Session class
require_once APP_PATH . '/config/session.php';
$csrfToken = Session::generateCSRFTokenMulti();

// Define standard nursing college categories if none are provided
$standardCategories = [
    'Academic News',
    'Research & Publications',
    'Clinical Updates',
    'Student Achievements',
    'Faculty News',
    'Continuing Education',
    'Community Outreach',
    'Health Policy',
    'Nursing Education',
    'Patient Care',
    'Technology in Nursing',
    'International Nursing',
    'Alumni News',
    'Events & Conferences',
    'Accreditation Updates',
    'Scholarships & Awards',
    'Mental Health Nursing',
    'Pediatric Nursing',
    'Geriatric Nursing',
    'Emergency Nursing',
    'Public Health Nursing',
    'Nursing Leadership',
    'Simulation Training',
    'Interprofessional Education'
];

// Use provided categories or standard ones
$displayCategories = !empty($categories) ? $categories : $standardCategories;

// Get form data from session if available (for restoring after error)
$sessionFormData = [];
if (isset($_SESSION['form_data'])) {
    $sessionFormData = $_SESSION['form_data'];
} elseif (isset($_SESSION['old'])) {
    $sessionFormData = $_SESSION['old'];
}

// Get flash errors using Session class
$flashError = Session::getFlash('error');
if ($flashError) {
    $error = $flashError;
}

// If we have session form data, merge it with existing news data
if (!empty($sessionFormData)) {
    $news = array_merge($news, $sessionFormData);
}

// Ensure all expected fields exist with default values
$news = array_merge([
    'id' => 0,
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'category' => '',
    'tags' => '',
    'featured_image' => '',
    'is_published' => 1,
    'is_featured' => 0,
    'is_breaking' => 0,
    'meta_title' => '',
    'meta_description' => '',
    'meta_keywords' => '',
    'type' => isset($news['type']) ? $news['type'] : 'news',
    'event_date' => date('Y-m-d'),
    'event_end_date' => '',
    'event_time' => '',
    'event_location' => ''
], $news);

// Get the correct image URL using the fixed helper function
$featuredImageUrl = !empty($news['featured_image']) ? getImageUrl($news['featured_image'], $news['type']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create <?php echo $news['type'] === 'event' ? 'Event' : 'News Article'; ?> - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
        
        /* Form */
        .form-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .tab-content {
            display: none;
            padding: 30px;
            min-height: 500px;
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
            display: none;
        }
        
        .image-preview img {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: cover;
        }
        
        /* Tab Navigation Buttons */
        .tab-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
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
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .btn-outline {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--light-bg);
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
        
        .field-error {
            color: var(--danger-color);
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
        }
        
        .form-control.error {
            border-color: var(--danger-color);
        }
        
        /* Progress Indicator */
        .tab-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
        }
        
        .tab-progress::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border-color);
            transform: translateY(-50%);
            z-index: 1;
        }
        
        .progress-step {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex: 1;
        }
        
        .progress-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-light);
        }
        
        .progress-step.active .progress-dot {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .progress-step.completed .progress-dot {
            background: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }
        
        .progress-label {
            font-size: 12px;
            color: var(--text-light);
            text-align: center;
        }
        
        .progress-step.active .progress-label {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        /* Image Preview Container */
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        
        .image-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            font-size: 12px;
            color: #666;
        }
        
        /* Remove Image Button */
        .remove-image-btn {
            margin-top: 10px;
            padding: 8px 16px;
            background: var(--danger-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .remove-image-btn:hover {
            background: #dc3545;
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
            <h1 class="page-title">Create <?php echo $news['type'] === 'event' ? 'Event' : 'News Article'; ?></h1>
            <a href="<?php echo $baseUrl; ?>/admin/news" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
        
        <!-- Progress Indicator -->
        <div class="tab-progress">
            <div class="progress-step active" data-step="content">
                <div class="progress-dot">1</div>
                <div class="progress-label">Content</div>
            </div>
            <div class="progress-step" data-step="media">
                <div class="progress-dot">2</div>
                <div class="progress-label">Media</div>
            </div>
            <div class="progress-step" data-step="seo">
                <div class="progress-dot">3</div>
                <div class="progress-label">SEO</div>
            </div>
            <div class="progress-step" data-step="settings">
                <div class="progress-dot">4</div>
                <div class="progress-label">Settings</div>
            </div>
        </div>
        
        <!-- Form -->
        <form method="POST" action="<?php echo $baseUrl; ?>/admin/news/store" class="form-container" id="newsForm" enctype="multipart/form-data">
            <!-- CSRF Token Field -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            <input type="hidden" name="type" id="form-type" value="<?php echo htmlspecialchars($news['type']); ?>">
            
            <!-- Content Tab -->
            <div class="tab-content active" id="content-tab">
                <div class="form-group">
                    <label class="form-label">Title <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($news['title']); ?>" 
                           required placeholder="Enter article title" id="title-input">
                    <div class="field-error" id="title-error" style="display: none;"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" 
                           value="<?php echo htmlspecialchars($news['slug']); ?>" 
                           placeholder="Auto-generated from title" id="slug-input">
                    <small style="color: var(--text-light); margin-top: 4px; display: block;">
                        URL-friendly version of the title (auto-generates if left blank)
                    </small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" 
                              placeholder="Brief summary of the article (optional)"
                              rows="3"><?php echo htmlspecialchars($news['excerpt']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Content <span class="required">*</span></label>
                    <div id="editor" class="editor-container"></div>
                    <textarea name="content" id="content" style="display: none;" required>
                        <?php echo htmlspecialchars($news['content']); ?>
                    </textarea>
                    <div class="field-error" id="content-error" style="display: none;"></div>
                </div>
                
                <div class="tab-navigation">
                    <div></div>
                    <button type="button" class="btn btn-primary next-btn" data-next="media-tab">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
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
                            Recommended size: 1200x630px (Max 5MB)<br>
                            Images will be saved to: /uploads/news/ directory
                        </p>
                    </div>
                    <input type="file" id="image-input" accept="image/*" style="display: none;" 
                           onchange="previewImage(event)" name="featured_image_upload">
                    <input type="hidden" name="featured_image" id="featured-image" 
                           value="<?php echo htmlspecialchars($news['featured_image']); ?>">
                    
                    <!-- Hidden fields for base64 image data -->
                    <input type="hidden" name="featured_image_data" id="featured-image-data" value="">
                    <input type="hidden" name="featured_image_filename" id="featured-image-filename" value="">
                    
                    <!-- Image Preview -->
                    <div class="image-preview" id="image-preview" style="<?php echo !empty($featuredImageUrl) ? 'display: block;' : 'display: none;'; ?>">
                        <?php if (!empty($featuredImageUrl)): ?>
                            <div class="preview-container">
                                <div>
                                    <img src="<?php echo htmlspecialchars($featuredImageUrl); ?>" alt="Preview" 
                                         onerror="this.style.display='none'; document.getElementById('image-error').style.display='block';">
                                    <p id="image-error" style="color: red; display: none;">Image failed to load. It may have been moved or deleted.</p>
                                </div>
                                <div class="image-info">
                                    <p><strong>Current Image:</strong></p>
                                    <p><small>Path: <?php echo htmlspecialchars($news['featured_image']); ?></small></p>
                                    <p><small>URL: <?php echo htmlspecialchars($featuredImageUrl); ?></small></p>
                                    <button type="button" onclick="removeImage()" class="remove-image-btn">
                                        <i class="fas fa-trash"></i> Remove Image
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tags</label>
                    <input type="text" name="tags" class="form-control" 
                           value="<?php 
                           $tagsValue = $news['tags'] ?? '';
                           if (!empty($tagsValue) && $tagsValue[0] === '[') {
                               $tagsArray = json_decode($tagsValue, true);
                               echo htmlspecialchars(is_array($tagsArray) ? implode(', ', $tagsArray) : $tagsValue);
                           } else {
                               echo htmlspecialchars($tagsValue);
                           }
                           ?>" 
                           placeholder="e.g., nursing, education, research (comma separated)">
                </div>
                
                <div class="tab-navigation">
                    <button type="button" class="btn btn-outline prev-btn" data-prev="content-tab">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary next-btn" data-next="seo-tab">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            
            <!-- SEO Tab -->
            <div class="tab-content" id="seo-tab">
                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" 
                           value="<?php echo htmlspecialchars($news['meta_title']); ?>" 
                           placeholder="Title for search engines (optional)">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" 
                              placeholder="Description for search engines (optional)"
                              rows="3"><?php echo htmlspecialchars($news['meta_description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" 
                           value="<?php echo htmlspecialchars($news['meta_keywords']); ?>" 
                           placeholder="Keywords for search engines (optional)">
                </div>
                
                <div class="tab-navigation">
                    <button type="button" class="btn btn-outline prev-btn" data-prev="media-tab">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary next-btn" data-next="settings-tab">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            
            <!-- Settings Tab -->
            <div class="tab-content" id="settings-tab">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Category <span class="required">*</span></label>
                        <select name="category" class="form-control" required id="category-select">
                            <option value="">Select Category</option>
                            <?php foreach ($displayCategories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>" 
                                <?php echo ($news['category'] === $category) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="field-error" id="category-error" style="display: none;"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Article Type</label>
                        <select name="article_type" class="form-control" id="type-select">
                            <option value="news" <?php echo $news['type'] === 'news' ? 'selected' : ''; ?>>News Article</option>
                            <option value="event" <?php echo $news['type'] === 'event' ? 'selected' : ''; ?>>Event</option>
                        </select>
                    </div>
                </div>
                
                <!-- Event Fields (hidden by default) -->
                <div id="event-fields" style="<?php echo $news['type'] === 'event' ? 'display: block;' : 'display: none;'; ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Event Date <span class="required">*</span></label>
                            <input type="date" name="event_date" class="form-control" 
                                   value="<?php echo htmlspecialchars($news['event_date']); ?>" 
                                   id="event-date-input">
                            <div class="field-error" id="event-date-error" style="display: none;"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Event End Date</label>
                            <input type="date" name="event_end_date" class="form-control" 
                                   value="<?php echo htmlspecialchars($news['event_end_date']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Event Time</label>
                            <input type="time" name="event_time" class="form-control" 
                                   value="<?php echo htmlspecialchars($news['event_time']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Event Location</label>
                        <input type="text" name="event_location" class="form-control" 
                               value="<?php echo htmlspecialchars($news['event_location']); ?>" 
                               placeholder="e.g., Main Auditorium, Online">
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_published" value="1" 
                               <?php echo $news['is_published'] ? 'checked' : 'checked'; ?>>
                        <span>Publish immediately</span>
                    </label>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" value="1" 
                               <?php echo $news['is_featured'] ? 'checked' : ''; ?>>
                        <span>Featured article</span>
                    </label>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_breaking" value="1" 
                               <?php echo $news['is_breaking'] ? 'checked' : ''; ?>>
                        <span>Breaking news</span>
                    </label>
                </div>
                
                <div class="tab-navigation">
                    <button type="button" class="btn btn-outline prev-btn" data-prev="seo-tab">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <div style="display: flex; gap: 12px;">
                        <button type="submit" name="save_draft" value="1" class="btn btn-secondary">
                            <i class="fas fa-save"></i> Save as Draft
                        </button>
                        <button type="submit" name="publish" value="1" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Publish
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Initialize Quill editor
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
            placeholder: 'Write your article content here...'
        });
        
        // Set initial content
        const contentField = document.getElementById('content');
        const initialContent = contentField.value.trim();
        if (initialContent && initialContent !== '') {
            quill.root.innerHTML = initialContent;
        }
        
        // Update hidden content field
        quill.on('text-change', function() {
            contentField.value = quill.root.innerHTML;
        });
        
        // Image upload functions
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const imageUrl = document.getElementById('featured-image');
            
            if (input.files && input.files[0]) {
                if (input.files[0].size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    input.value = '';
                    return;
                }
                
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(input.files[0].type)) {
                    alert('Please select a valid image file (JPEG, PNG, GIF, WebP)');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Create preview container if it doesn't exist
                    if (!preview.querySelector('img')) {
                        preview.innerHTML = `
                            <div class="preview-container">
                                <div>
                                    <img src="" alt="Preview">
                                    <p id="image-error" style="color: red; display: none;">Image failed to load</p>
                                </div>
                                <div class="image-info">
                                    <p><strong>New Image Preview:</strong></p>
                                    <p><small>File: ${input.files[0].name}</small></p>
                                    <p><small>Size: ${Math.round(input.files[0].size / 1024)} KB</small></p>
                                    <button type="button" onclick="removeImage()" class="remove-image-btn">
                                        <i class="fas fa-trash"></i> Remove Image
                                    </button>
                                </div>
                            </div>`;
                    }
                    
                    // Set the image source
                    const img = preview.querySelector('img');
                    img.src = e.target.result;
                    img.onload = function() {
                        document.getElementById('image-error').style.display = 'none';
                    };
                    img.onerror = function() {
                        document.getElementById('image-error').style.display = 'block';
                    };
                    
                    preview.style.display = 'block';
                    imageUrl.value = '';
                    
                    // Store the base64 data and filename for form submission
                    document.getElementById('featured-image-data').value = e.target.result;
                    document.getElementById('featured-image-filename').value = input.files[0].name;
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function removeImage() {
            const preview = document.getElementById('image-preview');
            const imageUrl = document.getElementById('featured-image');
            const fileInput = document.getElementById('image-input');
            const imageDataField = document.getElementById('featured-image-data');
            const imageFilenameField = document.getElementById('featured-image-filename');
            
            preview.style.display = 'none';
            preview.innerHTML = '';
            imageUrl.value = '';
            fileInput.value = '';
            imageDataField.value = '';
            imageFilenameField.value = '';
        }
        
        // Tab navigation
        document.addEventListener('DOMContentLoaded', function() {
            const nextButtons = document.querySelectorAll('.next-btn');
            const prevButtons = document.querySelectorAll('.prev-btn');
            
            // Event type toggle
            const typeSelect = document.getElementById('type-select');
            const formType = document.getElementById('form-type');
            const eventFields = document.getElementById('event-fields');
            
            typeSelect.addEventListener('change', function() {
                formType.value = this.value;
                if (this.value === 'event') {
                    eventFields.style.display = 'block';
                    // Set event date to today if empty
                    const eventDateInput = document.getElementById('event-date-input');
                    if (!eventDateInput.value) {
                        const today = new Date().toISOString().split('T')[0];
                        eventDateInput.value = today;
                    }
                } else {
                    eventFields.style.display = 'none';
                }
            });
            
            // Next buttons
            nextButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const currentTab = this.closest('.tab-content');
                    const nextTabId = this.getAttribute('data-next');
                    const nextTab = document.getElementById(nextTabId);
                    
                    if (validateTab(currentTab)) {
                        currentTab.classList.remove('active');
                        nextTab.classList.add('active');
                        updateProgressIndicator(nextTabId);
                    }
                });
            });
            
            // Previous buttons
            prevButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const currentTab = this.closest('.tab-content');
                    const prevTabId = this.getAttribute('data-prev');
                    const prevTab = document.getElementById(prevTabId);
                    
                    currentTab.classList.remove('active');
                    prevTab.classList.add('active');
                    updateProgressIndicator(prevTabId);
                });
            });
        });
        
        // Form validation before submission
        const newsForm = document.getElementById('newsForm');
        newsForm.addEventListener('submit', function(e) {
            // Validate all tabs
            const tabs = ['content-tab', 'settings-tab'];
            let allValid = true;
            
            tabs.forEach(tabId => {
                const tab = document.getElementById(tabId);
                if (!validateTab(tab)) {
                    allValid = false;
                    // Show the tab with errors
                    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    updateProgressIndicator(tabId);
                }
            });
            
            if (!allValid) {
                e.preventDefault();
                alert('Please fix all errors before submitting.');
                return false;
            }
            
            // Ensure content is updated
            contentField.value = quill.root.innerHTML;
            
            // Update type field
            const typeSelect = document.getElementById('type-select');
            const hiddenType = document.getElementById('form-type');
            hiddenType.value = typeSelect.value;
            
            return true;
        });
        
        // Helper functions
        function validateTab(tab) {
            if (!tab) return true;
            
            let isValid = true;
            const requiredFields = tab.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    let errorDiv = field.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('field-error')) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'field-error';
                        errorDiv.style.color = 'red';
                        errorDiv.style.fontSize = '0.8rem';
                        errorDiv.style.marginTop = '5px';
                        errorDiv.textContent = 'This field is required';
                        field.parentNode.insertBefore(errorDiv, field.nextSibling);
                    } else {
                        errorDiv.style.display = 'block';
                    }
                } else {
                    field.classList.remove('error');
                    const errorDiv = field.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('field-error')) {
                        errorDiv.style.display = 'none';
                    }
                }
            });
            
            return isValid;
        }
        
        function updateProgressIndicator(tabId) {
            const stepMap = {
                'content-tab': 'content',
                'media-tab': 'media',
                'seo-tab': 'seo',
                'settings-tab': 'settings'
            };
            
            const currentStep = stepMap[tabId];
            const progressSteps = document.querySelectorAll('.progress-step');
            
            progressSteps.forEach(step => {
                const stepName = step.getAttribute('data-step');
                step.classList.remove('active', 'completed');
                
                if (stepName === currentStep) {
                    step.classList.add('active');
                } else if (
                    (stepName === 'content' && ['media', 'seo', 'settings'].includes(currentStep)) ||
                    (stepName === 'media' && ['seo', 'settings'].includes(currentStep)) ||
                    (stepName === 'seo' && currentStep === 'settings')
                ) {
                    step.classList.add('completed');
                }
            });
        }
        
        // Auto-generate slug
        const titleInput = document.getElementById('title-input');
        const slugInput = document.getElementById('slug-input');
        
        titleInput.addEventListener('blur', function() {
            if (!slugInput.value) {
                const slug = this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
                slugInput.value = slug;
            }
        });
    </script>
</body>
</html>