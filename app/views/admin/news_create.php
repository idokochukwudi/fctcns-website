<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 3);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
$error = $error ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create News Article - FCT CNS Admin</title>
    <style>
        :root {
            --primary: #2c5282;
            --primary-dark: #1a365d;
            --success: #38a169;
            --warning: #d69e2e;
            --danger: #e53e3e;
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
            padding: 12px 30px;
            font-size: 1rem;
        }
        
        .btn-warning {
            background: var(--warning);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary:hover { background: var(--gray-700); }
        
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .required:after {
            content: " *";
            color: var(--danger);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.2s;
            box-sizing: border-box;
            font-family: inherit;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }
        
        .form-help {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-top: 6px;
        }
        
        .error-message {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            border: 1px solid rgba(229, 62, 62, 0.2);
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        
        .checkbox-group label {
            margin-bottom: 0;
            cursor: pointer;
        }
        
        .checkbox-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .editor-toolbar {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-bottom: none;
            border-radius: 6px 6px 0 0;
            padding: 10px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .editor-toolbar button {
            padding: 8px 12px;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        
        .editor-toolbar button:hover {
            background: var(--gray-100);
            border-color: var(--gray-300);
        }
        
        #editor {
            min-height: 400px;
            border: 1px solid var(--gray-200);
            border-radius: 0 0 6px 6px;
            padding: 20px;
            font-size: 16px;
            line-height: 1.6;
            outline: none;
        }
        
        #editor:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid var(--gray-200);
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        .character-count {
            text-align: right;
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-top: 8px;
        }
        
        .slug-preview {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            padding: 10px 15px;
            margin-top: 10px;
            font-family: monospace;
            font-size: 14px;
            word-break: break-all;
        }
        
        .preview-area {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            padding: 20px;
            margin-top: 20px;
            display: none;
        }
        
        .preview-area h3 {
            margin-top: 0;
            color: var(--gray-800);
        }
        
        .image-upload {
            border: 2px dashed var(--gray-300);
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }
        
        .image-upload:hover {
            border-color: var(--primary);
            background: rgba(44, 82, 130, 0.05);
        }
        
        .image-upload input[type="file"] {
            display: none;
        }
        
        .upload-prompt {
            color: var(--gray-600);
            margin-bottom: 10px;
        }
        
        .upload-icon {
            font-size: 48px;
            margin-bottom: 15px;
            color: var(--gray-400);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Create News Article</h1>
            <div>
                <a href="<?php echo BASE_URL; ?>/admin/news" class="btn btn-secondary">
                    ← Back to News
                </a>
            </div>
        </div>
        
        <?php if ($error): ?>
        <div class="error-message">
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo BASE_URL; ?>/admin/news/store" class="form-container" id="newsForm">
            <!-- Title and Slug -->
            <div class="form-group">
                <label for="title" class="required">Article Title</label>
                <input type="text" id="title" name="title" required 
                    placeholder="Enter a compelling title for your article"
                    onkeyup="generateSlug(this.value)"
                    maxlength="300">
                <div class="character-count">
                    <span id="titleCount">0</span>/300 characters
                </div>
            </div>
            
            <div class="form-group">
                <label for="slug">URL Slug</label>
                <input type="text" id="slug" name="slug" 
                    placeholder="auto-generated-slug"
                    pattern="[a-z0-9-]+"
                    title="Only lowercase letters, numbers, and hyphens allowed">
                <div class="slug-preview" id="slugPreview">
                    <strong>Preview URL:</strong> <?php echo BASE_URL; ?>/news/<span id="slugPreviewText">your-slug-here</span>
                </div>
                <div class="form-help">
                    Optional. Use lowercase letters, numbers, and hyphens only. Leave blank for auto-generation.
                </div>
            </div>
            
            <!-- Category and Tags -->
            <div class="form-row">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">Select Category</option>
                        <option value="Announcements">Announcements</option>
                        <option value="Research">Research</option>
                        <option value="Events">Events</option>
                        <option value="General">General</option>
                        <option value="Student Life">Student Life</option>
                        <option value="Faculty">Faculty</option>
                        <option value="Alumni">Alumni</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tags">Tags (comma separated)</label>
                    <input type="text" id="tags" name="tags" 
                        placeholder="nursing, education, research, healthcare">
                    <div class="form-help">Separate tags with commas</div>
                </div>
            </div>
            
            <!-- Excerpt -->
            <div class="form-group">
                <label for="excerpt" class="required">Article Excerpt</label>
                <textarea id="excerpt" name="excerpt" rows="3" required 
                    placeholder="Write a brief summary of your article (this appears in article lists)"
                    maxlength="500"
                    onkeyup="updateCharacterCount('excerpt', 'excerptCount', 500)"></textarea>
                <div class="character-count">
                    <span id="excerptCount">0</span>/500 characters
                </div>
            </div>
            
            <!-- Featured Image -->
            <div class="form-group">
                <label for="featured_image">Featured Image</label>
                <div class="image-upload" onclick="document.getElementById('imageUpload').click()">
                    <div class="upload-icon">📷</div>
                    <div class="upload-prompt">Click to upload featured image</div>
                    <div style="font-size: 0.875rem; color: var(--gray-500);">
                        Recommended: 1200×630 pixels
                    </div>
                </div>
                <input type="file" id="imageUpload" accept="image/*" onchange="handleImageUpload(this)">
                <input type="hidden" id="featured_image" name="featured_image">
                <div id="imagePreview" style="display: none; margin-top: 15px;">
                    <img src="" alt="Preview" style="max-width: 200px; border-radius: 6px;">
                    <button type="button" onclick="removeImage()" class="btn btn-danger" style="margin-left: 10px; padding: 5px 10px;">
                        Remove
                    </button>
                </div>
            </div>
            
            <!-- Content Editor -->
            <div class="form-group">
                <label for="content" class="required">Article Content</label>
                <div class="editor-toolbar">
                    <button type="button" onclick="formatText('bold')"><b>B</b></button>
                    <button type="button" onclick="formatText('italic')"><i>I</i></button>
                    <button type="button" onclick="formatText('underline')"><u>U</u></button>
                    <div style="width: 1px; background: var(--gray-200); margin: 0 5px;"></div>
                    <button type="button" onclick="formatText('h2')">H2</button>
                    <button type="button" onclick="formatText('h3')">H3</button>
                    <button type="button" onclick="formatText('paragraph')">¶</button>
                    <div style="width: 1px; background: var(--gray-200); margin: 0 5px;"></div>
                    <button type="button" onclick="formatText('ul')">• List</button>
                    <button type="button" onclick="formatText('ol')">1. List</button>
                    <button type="button" onclick="formatText('link')">🔗 Link</button>
                    <button type="button" onclick="formatText('image')">🖼️ Image</button>
                    <div style="flex: 1;"></div>
                    <button type="button" onclick="togglePreview()">👁️ Preview</button>
                </div>
                <div id="editor" contenteditable="true" oninput="updateContentField()"></div>
                <textarea id="content" name="content" style="display: none;" required></textarea>
                <div class="character-count">
                    <span id="contentCount">0</span> characters
                </div>
            </div>
            
            <!-- Publish Options -->
            <div class="form-group">
                <h3 style="margin-top: 0; margin-bottom: 15px; color: var(--gray-800);">🚀 Publish Options</h3>
                <div class="checkbox-container">
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_published" name="is_published" value="1" checked>
                        <label for="is_published">Publish immediately</label>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1">
                        <label for="is_featured">Feature this article</label>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_breaking" name="is_breaking" value="1">
                        <label for="is_breaking">Breaking news</label>
                    </div>
                </div>
            </div>
            
            <!-- SEO Options -->
            <div class="form-group">
                <h3 style="margin-top: 0; margin-bottom: 15px; color: var(--gray-800);">🔍 SEO Options</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" 
                            placeholder="Optional meta title for SEO"
                            maxlength="300">
                        <div class="character-count">
                            <span id="metaTitleCount">0</span>/300 characters
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="2"
                            placeholder="Optional meta description for SEO"
                            maxlength="500"
                            onkeyup="updateCharacterCount('meta_description', 'metaDescCount', 500)"></textarea>
                        <div class="character-count">
                            <span id="metaDescCount">0</span>/500 characters
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <textarea id="meta_keywords" name="meta_keywords" rows="2"
                        placeholder="Optional keywords for SEO (comma separated)"></textarea>
                </div>
            </div>
            
            <!-- Preview Area -->
            <div class="preview-area" id="previewArea">
                <h3>Article Preview</h3>
                <div id="articlePreview"></div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-success">
                    💾 Save & Publish
                </button>
                <button type="button" class="btn btn-warning" onclick="saveDraft()">
                    💾 Save as Draft
                </button>
                <a href="<?php echo BASE_URL; ?>/admin/news" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="button" class="btn btn-primary" onclick="togglePreview()">
                    👁️ Toggle Preview
                </button>
            </div>
        </form>
        
        <!-- Writing Tips -->
        <div style="background: #f0f9ff; border: 1px solid #bee3f8; border-radius: 8px; padding: 20px;">
            <h3 style="margin-top: 0; color: var(--primary);">💡 Writing Tips</h3>
            <ul style="margin: 10px 0; padding-left: 20px; color: var(--gray-700);">
                <li><strong>Title:</strong> Keep it under 60 characters for best SEO results</li>
                <li><strong>Excerpt:</strong> Write a compelling summary that encourages clicks</li>
                <li><strong>Content:</strong> Use headings, bullet points, and images for readability</li>
                <li><strong>Images:</strong> Always include relevant, high-quality images</li>
                <li><strong>SEO:</strong> Include keywords naturally in title and content</li>
                <li><strong>Tags:</strong> Use 5-10 relevant tags for better categorization</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Character counters
        function updateCharacterCount(fieldId, countId, max) {
            const field = document.getElementById(fieldId);
            const count = document.getElementById(countId);
            const length = field.value.length;
            count.textContent = length;
            
            if (length > max * 0.9) {
                count.style.color = 'var(--danger)';
            } else if (length > max * 0.75) {
                count.style.color = 'var(--warning)';
            } else {
                count.style.color = 'var(--gray-600)';
            }
        }
        
        // Initialize counters
        document.addEventListener('DOMContentLoaded', function() {
            updateCharacterCount('title', 'titleCount', 300);
            updateCharacterCount('excerpt', 'excerptCount', 500);
            updateCharacterCount('meta_title', 'metaTitleCount', 300);
            updateCharacterCount('meta_description', 'metaDescCount', 500);
            
            // Auto-update content count
            setInterval(() => {
                const content = document.getElementById('editor').textContent;
                document.getElementById('contentCount').textContent = content.length;
            }, 1000);
        });
        
        // Slug generation
        function generateSlug(title) {
            const slugInput = document.getElementById('slug');
            const previewText = document.getElementById('slugPreviewText');
            
            if (!slugInput.value) {
                let slug = title.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                
                slugInput.value = slug;
                previewText.textContent = slug;
            }
            
            updateCharacterCount('title', 'titleCount', 300);
        }
        
        // Update slug preview
        document.getElementById('slug').addEventListener('input', function() {
            const previewText = document.getElementById('slugPreviewText');
            previewText.textContent = this.value || 'your-slug-here';
        });
        
        // Content editor functions
        function formatText(command) {
            const editor = document.getElementById('editor');
            document.execCommand(command, false, null);
            editor.focus();
            updateContentField();
        }
        
        function updateContentField() {
            const editor = document.getElementById('editor');
            const contentField = document.getElementById('content');
            contentField.value = editor.innerHTML;
        }
        
        // Image upload handling
        function handleImageUpload(input) {
            const file = input.files[0];
            if (!file) return;
            
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) { // 5MB limit
                alert('Image size must be less than 5MB.');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                // In a real implementation, you would upload to server
                // For now, we'll just show a preview
                const preview = document.getElementById('imagePreview');
                const img = preview.querySelector('img');
                img.src = e.target.result;
                preview.style.display = 'block';
                
                document.getElementById('featured_image').value = 'uploaded-image-path.jpg';
            };
            reader.readAsDataURL(file);
        }
        
        function removeImage() {
            document.getElementById('imageUpload').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('featured_image').value = '';
        }
        
        // Preview toggle
        function togglePreview() {
            const previewArea = document.getElementById('previewArea');
            const articlePreview = document.getElementById('articlePreview');
            
            if (previewArea.style.display === 'none') {
                // Generate preview
                const title = document.getElementById('title').value || 'Untitled Article';
                const excerpt = document.getElementById('excerpt').value || 'No excerpt provided';
                const content = document.getElementById('editor').innerHTML || '<p>No content yet.</p>';
                const category = document.getElementById('category').value || 'Uncategorized';
                
                articlePreview.innerHTML = `
                    <div style="margin-bottom: 30px;">
                        <h2 style="color: var(--gray-800); margin-bottom: 15px;">${title}</h2>
                        <div style="color: var(--gray-600); margin-bottom: 20px;">${excerpt}</div>
                        <div style="display: flex; gap: 15px; font-size: 0.875rem; color: var(--gray-500);">
                            <span>Category: ${category}</span>
                            <span>•</span>
                            <span>Published: Just now</span>
                        </div>
                    </div>
                    <hr style="border: none; border-top: 1px solid var(--gray-200); margin: 20px 0;">
                    <div style="line-height: 1.8;">
                        ${content}
                    </div>
                `;
                
                previewArea.style.display = 'block';
            } else {
                previewArea.style.display = 'none';
            }
        }
        
        // Save draft
        function saveDraft() {
            document.getElementById('is_published').checked = false;
            document.getElementById('newsForm').submit();
        }
        
        // Form validation
        document.getElementById('newsForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const excerpt = document.getElementById('excerpt').value.trim();
            const content = document.getElementById('content').value.trim();
            
            if (!title) {
                e.preventDefault();
                alert('Please enter a title for the article.');
                document.getElementById('title').focus();
                return;
            }
            
            if (!excerpt) {
                e.preventDefault();
                alert('Please enter an excerpt for the article.');
                document.getElementById('excerpt').focus();
                return;
            }
            
            if (!content || content === '<br>') {
                e.preventDefault();
                alert('Please enter content for the article.');
                document.getElementById('editor').focus();
                return;
            }
            
            // Validate slug format
            const slug = document.getElementById('slug').value;
            if (slug && !/^[a-z0-9-]+$/.test(slug)) {
                e.preventDefault();
                alert('Slug can only contain lowercase letters, numbers, and hyphens.');
                document.getElementById('slug').focus();
                return;
            }
        });
        
        // Auto-save draft every 30 seconds
        let autoSaveTimer;
        function startAutoSave() {
            autoSaveTimer = setInterval(() => {
                if (document.getElementById('title').value || 
                    document.getElementById('editor').textContent) {
                    console.log('Auto-saving draft...');
                    // In a real implementation, this would make an AJAX call
                }
            }, 30000);
        }
        
        // Warn before leaving unsaved changes
        let hasUnsavedChanges = false;
        
        document.querySelectorAll('input, textarea, #editor').forEach(element => {
            element.addEventListener('input', () => {
                hasUnsavedChanges = true;
            });
        });
        
        window.addEventListener('beforeunload', (e) => {
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            }
        });
        
        // Start auto-save
        startAutoSave();
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                if (document.getElementById('is_published').checked) {
                    document.getElementById('newsForm').submit();
                } else {
                    saveDraft();
                }
            }
            
            // Ctrl/Cmd + P to preview
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                togglePreview();
            }
            
            // Ctrl/Cmd + Enter to publish
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('is_published').checked = true;
                document.getElementById('newsForm').submit();
            }
        });
    </script>
</body>
</html>