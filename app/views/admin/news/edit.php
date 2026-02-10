<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure we have required variables with defaults
$news = $news ?? [];
$categories = $categories ?? [];
$error = $error ?? '';
$csrfToken = $csrf_token ?? bin2hex(random_bytes(32));
$baseUrl = $baseUrl ?? '/';

// Set defaults for any missing fields in news
$defaultNews = [
    'id' => 0,
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'category' => '',
    'tags' => '',
    'featured_image' => '',
    'is_published' => 0,
    'is_featured' => 0,
    'is_breaking' => 0,
    'meta_title' => '',
    'meta_description' => '',
    'meta_keywords' => '',
    'type' => 'news',
    'event_date' => date('Y-m-d'),
    'event_end_date' => '',
    'event_time' => '',
    'event_location' => '',
    'author_name' => 'Unknown',
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
    'views_count' => 0
];

// Merge defaults with actual data
$news = array_merge($defaultNews, $news);

// Get the correct image URL using the fixed helper function
$featuredImageUrl = !empty($news['featured_image']) ? getImageUrl($news['featured_image'], $news['type']) : '';

// Format dates
if (!empty($news['event_date']) && $news['event_date'] != '0000-00-00') {
    $news['event_date'] = date('Y-m-d', strtotime($news['event_date']));
}

if (!empty($news['event_end_date']) && $news['event_end_date'] != '0000-00-00') {
    $news['event_end_date'] = date('Y-m-d', strtotime($news['event_end_date']));
}

if (!empty($news['event_time'])) {
    $news['event_time'] = date('H:i', strtotime($news['event_time']));
}

// Store CSRF token in session
$_SESSION['csrf_token'] = $csrfToken;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?php echo $news['type'] === 'event' ? 'Event' : 'News Article'; ?> - Admin Dashboard</title>
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
            flex-wrap: wrap;
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
        
        /* Editor Styles */
        .editor-wrapper {
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }
        
        #editor {
            height: 400px;
        }
        
        .ql-toolbar {
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
            border: none !important;
            border-bottom: 1px solid var(--border-color) !important;
            background: var(--light-bg) !important;
        }
        
        .ql-container {
            border: none !important;
            font-size: 16px;
            font-family: inherit;
        }
        
        .ql-editor {
            min-height: 300px;
            padding: 20px !important;
        }
        
        /* Image positioning classes */
        .ql-editor img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        
        .ql-editor .image-left {
            float: left;
            margin: 10px 20px 10px 0;
            max-width: 50%;
        }
        
        .ql-editor .image-right {
            float: right;
            margin: 10px 0 10px 20px;
            max-width: 50%;
        }
        
        .ql-editor .image-center {
            display: block;
            margin: 10px auto;
            text-align: center;
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
        
        .image-preview-container {
            margin-top: 20px;
            display: <?php echo !empty($featuredImageUrl) ? 'block' : 'none'; ?>;
        }
        
        .image-preview {
            max-width: 300px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .image-preview img {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: cover;
        }
        
        .image-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
            max-width: 300px;
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
        
        .article-info {
            background: var(--light-bg);
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .info-label {
            font-size: 12px;
            color: var(--text-light);
        }
        
        .info-value {
            font-weight: 500;
            color: var(--text-dark);
        }
        
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
            
            .article-info {
                flex-direction: column;
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
            <h1 class="page-title">Edit <?php echo $news['type'] === 'event' ? 'Event' : 'News Article'; ?></h1>
            <a href="<?php echo $baseUrl; ?>/admin/news" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
        
        <!-- Article Info -->
        <div class="article-info">
            <div class="info-item">
                <span class="info-label">ID</span>
                <span class="info-value">#<?php echo !empty($news['id']) ? $news['id'] : 'New'; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Author</span>
                <span class="info-value"><?php echo htmlspecialchars($news['author_name'] ?? 'Unknown'); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Created</span>
                <span class="info-value"><?php echo !empty($news['created_at']) ? date('M d, Y H:i', strtotime($news['created_at'])) : 'Just now'; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Views</span>
                <span class="info-value"><?php echo !empty($news['views_count']) ? number_format($news['views_count']) : '0'; ?></span>
            </div>
        </div>
        
        <!-- Form -->
        <form method="POST" action="<?php echo $baseUrl; ?>/admin/news/update/<?php echo !empty($news['id']) ? $news['id'] : '0'; ?>" class="form-container" id="newsForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            
            <!-- Hidden fields for base64 image data -->
            <input type="hidden" name="featured_image_data" id="featured-image-data" value="">
            <input type="hidden" name="featured_image_filename" id="featured-image-filename" value="">
            <input type="hidden" name="content" id="content" value="<?php echo htmlspecialchars($news['content'] ?? ''); ?>">
            
            <!-- Tabs -->
            <div class="form-tabs">
                <button type="button" class="tab-btn active" data-tab="content">Content</button>
                <button type="button" class="tab-btn" data-tab="media">Media</button>
                <button type="button" class="tab-btn" data-tab="settings">Settings</button>
            </div>
            
            <!-- Content Tab -->
            <div class="tab-content active" id="content-tab">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($news['title'] ?? ''); ?>" 
                           required placeholder="Enter article title">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" 
                              placeholder="Brief summary of the article"
                              rows="3"><?php echo htmlspecialchars($news['excerpt'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Content</label>
                    <div class="editor-wrapper">
                        <div id="editor"></div>
                    </div>
                </div>
            </div>
            
            <!-- Media Tab -->
            <div class="tab-content" id="media-tab">
                <div class="form-group">
                    <label class="form-label">Featured Image</label>
                    
                    <!-- Image Upload Area -->
                    <div class="image-upload" onclick="document.getElementById('image-input').click()" 
                         style="<?php echo !empty($featuredImageUrl) ? 'display: none;' : ''; ?>" id="upload-area">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <p>Click to upload featured image</p>
                        <p style="color: var(--text-light); font-size: 14px;">
                            Recommended: 1200x630px<br>
                            Max file size: 5MB
                        </p>
                    </div>
                    
                    <input type="file" id="image-input" accept="image/*" style="display: none;" 
                           onchange="previewImage(event)" name="featured_image_upload">
                    <input type="hidden" name="featured_image" id="featured-image" 
                           value="<?php echo htmlspecialchars($news['featured_image'] ?? ''); ?>">
                    
                    <!-- Image Preview Container -->
                    <div class="image-preview-container" id="image-preview-container">
                        <?php if (!empty($featuredImageUrl)): ?>
                            <div class="image-preview">
                                <img src="<?php echo htmlspecialchars($featuredImageUrl); ?>" 
                                     alt="Featured Image Preview"
                                     onerror="this.style.display='none'; document.getElementById('image-error').style.display='block';"
                                     id="preview-image">
                                <p id="image-error" style="color: red; display: none;">
                                    Image failed to load
                                </p>
                            </div>
                            <div class="image-info">
                                <p><strong>Current Featured Image</strong></p>
                                <button type="button" onclick="removeImage()" class="remove-image-btn">
                                    <i class="fas fa-trash"></i> Remove Image
                                </button>
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
                           placeholder="nursing, education, research (comma separated)">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" 
                           value="<?php echo htmlspecialchars($news['meta_title'] ?? ''); ?>" 
                           placeholder="Title for search engines">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" 
                              placeholder="Description for search engines"
                              rows="3"><?php echo htmlspecialchars($news['meta_description'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <!-- Settings Tab -->
            <div class="tab-content" id="settings-tab">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>" 
                                <?php echo ($news['category'] ?? '') === $category ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Article Type</label>
                        <select name="type" class="form-control" id="type-select">
                            <option value="news" <?php echo ($news['type'] ?? 'news') === 'news' ? 'selected' : ''; ?>>News Article</option>
                            <option value="event" <?php echo ($news['type'] ?? '') === 'event' ? 'selected' : ''; ?>>Event</option>
                        </select>
                    </div>
                </div>
                
                <!-- Event Fields -->
                <div id="event-fields" style="<?php echo ($news['type'] ?? 'news') === 'event' ? '' : 'display: none;'; ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Event Date</label>
                            <input type="date" name="event_date" class="form-control" 
                                   value="<?php echo htmlspecialchars($news['event_date'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Event Time</label>
                            <input type="time" name="event_time" class="form-control" 
                                   value="<?php echo htmlspecialchars($news['event_time'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Event Location</label>
                        <input type="text" name="event_location" class="form-control" 
                               value="<?php echo htmlspecialchars($news['event_location'] ?? ''); ?>" 
                               placeholder="e.g., Main Auditorium, Online">
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_published" value="1" 
                               <?php echo isset($news['is_published']) && $news['is_published'] ? 'checked' : ''; ?>>
                        <span>Published</span>
                    </label>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" value="1" 
                               <?php echo isset($news['is_featured']) && $news['is_featured'] ? 'checked' : ''; ?>>
                        <span>Featured article</span>
                    </label>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_breaking" value="1" 
                               <?php echo isset($news['is_breaking']) && $news['is_breaking'] ? 'checked' : ''; ?>>
                        <span>Breaking news</span>
                    </label>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" 
                           value="<?php echo htmlspecialchars($news['slug'] ?? ''); ?>" 
                           placeholder="auto-generated-from-title">
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <div>
                    <a href="<?php echo $baseUrl; ?>/admin/news" class="btn btn-outline">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
                
                <div>
                    <button type="submit" name="save_draft" value="1" class="btn btn-secondary">
                        Save as Draft
                    </button>
                    <button type="submit" name="publish" value="1" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Article
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
                    ['link', 'image', 'blockquote'],
                    [{ 'align': [] }],
                    ['clean']
                ]
            },
            placeholder: 'Write your article content here...'
        });
        
        // Set initial content - FIXED: This was missing
        const contentField = document.getElementById('content');
        const initialContent = contentField.value;
        if (initialContent) {
            quill.root.innerHTML = initialContent;
        }
        
        // Update hidden content field when editor changes
        quill.on('text-change', function() {
            contentField.value = quill.root.innerHTML;
        });
        
        // Tab switching - FIXED: This was broken
        document.addEventListener('DOMContentLoaded', function() {
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
        });
        
        // Image upload preview
        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('image-preview-container');
            const uploadArea = document.getElementById('upload-area');
            const imageUrl = document.getElementById('featured-image');
            const imageDataField = document.getElementById('featured-image-data');
            const imageFilenameField = document.getElementById('featured-image-filename');
            
            if (input.files && input.files[0]) {
                // Validation
                if (input.files[0].size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Create preview
                    previewContainer.innerHTML = `
                        <div class="image-preview">
                            <img src="${e.target.result}" alt="New Image Preview" id="preview-image">
                            <p id="image-error" style="color: red; display: none;">
                                Image failed to load
                            </p>
                        </div>
                        <div class="image-info">
                            <p><strong>New Featured Image</strong></p>
                            <button type="button" onclick="removeImage()" class="remove-image-btn">
                                <i class="fas fa-trash"></i> Remove Image
                            </button>
                        </div>`;
                    
                    // Show preview, hide upload area
                    previewContainer.style.display = 'block';
                    uploadArea.style.display = 'none';
                    
                    // Store the base64 data
                    imageDataField.value = e.target.result;
                    imageFilenameField.value = input.files[0].name;
                    imageUrl.value = ''; // Clear old path
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function removeImage() {
            const previewContainer = document.getElementById('image-preview-container');
            const uploadArea = document.getElementById('upload-area');
            const imageUrl = document.getElementById('featured-image');
            const fileInput = document.getElementById('image-input');
            const imageDataField = document.getElementById('featured-image-data');
            const imageFilenameField = document.getElementById('featured-image-filename');
            
            // Hide preview, show upload area
            previewContainer.style.display = 'none';
            uploadArea.style.display = 'block';
            
            // Clear all image-related fields
            imageUrl.value = '';
            fileInput.value = '';
            imageDataField.value = '';
            imageFilenameField.value = '';
        }
        
        // Show/hide event fields based on type
        const typeSelect = document.getElementById('type-select');
        const eventFields = document.getElementById('event-fields');
        
        typeSelect.addEventListener('change', function() {
            if (this.value === 'event') {
                eventFields.style.display = 'block';
            } else {
                eventFields.style.display = 'none';
            }
        });
        
        // Confirm delete
        function confirmDelete() {
            const newsId = <?php echo !empty($news['id']) ? $news['id'] : '0'; ?>;
            if (newsId === 0) {
                alert('Cannot delete a new article. Please cancel instead.');
                return;
            }
            
            if (confirm('Are you sure you want to delete this article?')) {
                const deleteForm = document.createElement('form');
                deleteForm.method = 'POST';
                deleteForm.action = '<?php echo $baseUrl; ?>/admin/news/delete/' + newsId;
                deleteForm.style.display = 'none';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = '<?php echo $csrfToken; ?>';
                
                deleteForm.appendChild(csrfInput);
                document.body.appendChild(deleteForm);
                deleteForm.submit();
            }
        }
        
        // Form validation
        const form = document.getElementById('newsForm');
        form.addEventListener('submit', function(e) {
            // Update content field before submit
            contentField.value = quill.root.innerHTML;
            
            // Basic validation
            const title = document.querySelector('input[name="title"]').value.trim();
            if (!title) {
                e.preventDefault();
                alert('Please enter a title for the article.');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>