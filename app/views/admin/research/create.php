<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 4);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
if (!in_array($userRole, ['admin', 'editor'])) {
    header("Location: " . BASE_URL . "/admin/dashboard");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Research Publication - FCT CNS Admin</title>
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
        
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .form-group .required::after {
            content: " *";
            color: var(--danger);
        }
        
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="url"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group input[type="file"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
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
        
        .file-upload {
            border: 2px dashed var(--gray-200);
            border-radius: 6px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .file-upload:hover {
            border-color: var(--primary);
            background: var(--gray-50);
        }
        
        .file-upload input[type="file"] {
            display: none;
        }
        
        .file-upload-label {
            color: var(--primary);
            font-weight: 500;
            cursor: pointer;
            display: block;
        }
        
        .file-upload-label:hover {
            text-decoration: underline;
        }
        
        .file-info {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-top: 8px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-200);
        }
        
        .error-message {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid var(--danger);
        }
        
        .success-message {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid var(--success);
        }
        
        .form-help {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-top: 6px;
        }
        
        .authors-input-container {
            position: relative;
        }
        
        .authors-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        
        .author-tag {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .remove-author {
            cursor: pointer;
            font-weight: bold;
            font-size: 1.2em;
        }
        
        .checkbox-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .checkbox-item input[type="checkbox"] {
            width: auto;
        }
        
        .publication-details {
            background: var(--gray-50);
            border-radius: 6px;
            padding: 20px;
            margin-top: 10px;
        }
        
        .tab-container {
            margin-bottom: 20px;
        }
        
        .tabs {
            display: flex;
            border-bottom: 2px solid var(--gray-200);
            margin-bottom: 20px;
        }
        
        .tab {
            padding: 12px 24px;
            background: none;
            border: none;
            font-weight: 500;
            color: var(--gray-600);
            cursor: pointer;
            position: relative;
        }
        
        .tab.active {
            color: var(--primary);
        }
        
        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔬 Create Research Publication</h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/research" class="btn btn-secondary">
                    ← Back to Research
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-secondary">
                    🏠 Dashboard
                </a>
            </div>
        </div>
        
        <?php if (isset($error) && $error): ?>
        <div class="error-message">
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <?php if (isset($success) && $success): ?>
        <div class="success-message">
            <strong>Success:</strong> <?php echo htmlspecialchars($success); ?>
        </div>
        <?php endif; ?>
        
        <div class="form-container">
            <form action="<?php echo BASE_URL; ?>/admin/research/store" method="POST" enctype="multipart/form-data" id="researchForm">
                
                <!-- Tabs for different sections -->
                <div class="tab-container">
                    <div class="tabs">
                        <button type="button" class="tab active" onclick="switchTab('basic')">Basic Info</button>
                        <button type="button" class="tab" onclick="switchTab('publication')">Publication Details</button>
                        <button type="button" class="tab" onclick="switchTab('files')">Files & Media</button>
                        <button type="button" class="tab" onclick="switchTab('metadata')">Metadata & Settings</button>
                    </div>
                    
                    <!-- Basic Information Tab -->
                    <div id="tab-basic" class="tab-content active">
                        <h3 style="color: var(--primary); margin-top: 0; margin-bottom: 20px;">Basic Information</h3>
                        
                        <div class="form-group">
                            <label class="required">Research Title</label>
                            <input type="text" name="title" required 
                                   placeholder="Enter research title" 
                                   value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="required">Authors</label>
                            <div class="authors-input-container">
                                <input type="text" id="authorInput" 
                                       placeholder="Enter author name and press Enter or Tab">
                                <div class="authors-tags" id="authorsTags">
                                    <!-- Author tags will be added here -->
                                </div>
                                <input type="hidden" name="authors" id="authorsHidden" required>
                            </div>
                            <div class="form-help">Add authors one by one. Press Enter or Tab after each author.</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="required">Abstract</label>
                            <textarea name="abstract" required 
                                      placeholder="Enter research abstract..."
                                      rows="8"><?php echo htmlspecialchars($_POST['abstract'] ?? ''); ?></textarea>
                            <div class="form-help">Provide a comprehensive abstract of the research (minimum 200 characters).</div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="required">Publication Type</label>
                                <select name="publication_type" required>
                                    <option value="">Select type</option>
                                    <option value="journal" <?php echo isset($_POST['publication_type']) && $_POST['publication_type'] == 'journal' ? 'selected' : ''; ?>>Journal Article</option>
                                    <option value="conference" <?php echo isset($_POST['publication_type']) && $_POST['publication_type'] == 'conference' ? 'selected' : ''; ?>>Conference Paper</option>
                                    <option value="book" <?php echo isset($_POST['publication_type']) && $_POST['publication_type'] == 'book' ? 'selected' : ''; ?>>Book</option>
                                    <option value="thesis" <?php echo isset($_POST['publication_type']) && $_POST['publication_type'] == 'thesis' ? 'selected' : ''; ?>>Thesis</option>
                                    <option value="report" <?php echo isset($_POST['publication_type']) && $_POST['publication_type'] == 'report' ? 'selected' : ''; ?>>Report</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="required">Research Area</label>
                                <select name="research_area" required>
                                    <option value="">Select research area</option>
                                    <?php foreach ($research_categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category['name']); ?>"
                                            <?php echo isset($_POST['research_area']) && $_POST['research_area'] == $category['name'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Keywords</label>
                            <input type="text" name="keywords" 
                                   placeholder="Enter keywords separated by commas"
                                   value="<?php echo htmlspecialchars($_POST['keywords'] ?? ''); ?>">
                            <div class="form-help">Example: nursing, healthcare, education, research</div>
                        </div>
                        
                        <div class="btn-group" style="justify-content: flex-end; margin-top: 20px;">
                            <button type="button" class="btn btn-primary" onclick="switchTab('publication')">
                                Next: Publication Details →
                            </button>
                        </div>
                    </div>
                    
                    <!-- Publication Details Tab -->
                    <div id="tab-publication" class="tab-content">
                        <h3 style="color: var(--primary); margin-top: 0; margin-bottom: 20px;">Publication Details</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Journal/Conference Name</label>
                                <input type="text" name="journal_name" 
                                       placeholder="Journal or conference name"
                                       value="<?php echo htmlspecialchars($_POST['journal_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Publisher</label>
                                <input type="text" name="publisher" 
                                       placeholder="Publisher name"
                                       value="<?php echo htmlspecialchars($_POST['publisher'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Volume</label>
                                <input type="text" name="volume" 
                                       placeholder="Volume number"
                                       value="<?php echo htmlspecialchars($_POST['volume'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Issue</label>
                                <input type="text" name="issue" 
                                       placeholder="Issue number"
                                       value="<?php echo htmlspecialchars($_POST['issue'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Pages</label>
                                <input type="text" name="pages" 
                                       placeholder="e.g., 45-56"
                                       value="<?php echo htmlspecialchars($_POST['pages'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Publication Date</label>
                                <input type="date" name="publication_date" 
                                       value="<?php echo htmlspecialchars($_POST['publication_date'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>DOI</label>
                                <input type="text" name="doi" 
                                       placeholder="Digital Object Identifier"
                                       value="<?php echo htmlspecialchars($_POST['doi'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>URL</label>
                                <input type="url" name="url" 
                                       placeholder="https://example.com"
                                       value="<?php echo htmlspecialchars($_POST['url'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Citations Count</label>
                                <input type="number" name="citations" 
                                       placeholder="Number of citations"
                                       value="<?php echo htmlspecialchars($_POST['citations'] ?? 0); ?>"
                                       min="0">
                            </div>
                            
                            <div class="form-group">
                                <label>Impact Factor</label>
                                <input type="number" step="0.001" name="impact_factor" 
                                       placeholder="Journal impact factor"
                                       value="<?php echo htmlspecialchars($_POST['impact_factor'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="btn-group" style="justify-content: space-between; margin-top: 20px;">
                            <button type="button" class="btn btn-secondary" onclick="switchTab('basic')">
                                ← Back to Basic Info
                            </button>
                            <button type="button" class="btn btn-primary" onclick="switchTab('files')">
                                Next: Files & Media →
                            </button>
                        </div>
                    </div>
                    
                    <!-- Files & Media Tab -->
                    <div id="tab-files" class="tab-content">
                        <h3 style="color: var(--primary); margin-top: 0; margin-bottom: 20px;">Files & Media</h3>
                        
                        <div class="form-group">
                            <label>Research File (PDF/DOC)</label>
                            <div class="file-upload" onclick="document.getElementById('file_path').click()">
                                <div class="file-upload-label">
                                    📁 Click to upload research file
                                </div>
                                <div class="file-info">
                                    Supported formats: PDF, DOC, DOCX (Max 10MB)
                                </div>
                                <input type="file" id="file_path" name="file_path" 
                                       accept=".pdf,.doc,.docx" onchange="showFileName(this, 'file_name')">
                                <div id="file_name" style="margin-top: 10px; font-size: 0.875rem; color: var(--gray-700);"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Thumbnail Image</label>
                            <div class="file-upload" onclick="document.getElementById('thumbnail_path').click()">
                                <div class="file-upload-label">
                                    🖼️ Click to upload thumbnail
                                </div>
                                <div class="file-info">
                                    Supported formats: JPG, PNG, GIF (Max 5MB)
                                </div>
                                <input type="file" id="thumbnail_path" name="thumbnail_path" 
                                       accept=".jpg,.jpeg,.png,.gif" onchange="showFileName(this, 'thumbnail_name')">
                                <div id="thumbnail_name" style="margin-top: 10px; font-size: 0.875rem; color: var(--gray-700);"></div>
                            </div>
                        </div>
                        
                        <div class="btn-group" style="justify-content: space-between; margin-top: 20px;">
                            <button type="button" class="btn btn-secondary" onclick="switchTab('publication')">
                                ← Back to Publication Details
                            </button>
                            <button type="button" class="btn btn-primary" onclick="switchTab('metadata')">
                                Next: Metadata & Settings →
                            </button>
                        </div>
                    </div>
                    
                    <!-- Metadata & Settings Tab -->
                    <div id="tab-metadata" class="tab-content">
                        <h3 style="color: var(--primary); margin-top: 0; margin-bottom: 20px;">Metadata & Settings</h3>
                        
                        <div class="form-group">
                            <label>Additional Notes</label>
                            <textarea name="notes" 
                                      placeholder="Any additional notes about this publication..."
                                      rows="4"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Publication Status</label>
                                <div class="publication-details">
                                    <div class="checkbox-group">
                                        <div class="checkbox-item">
                                            <input type="checkbox" name="is_published" id="is_published" value="1" checked>
                                            <label for="is_published">Published</label>
                                        </div>
                                        <div class="checkbox-item">
                                            <input type="checkbox" name="is_featured" id="is_featured" value="1">
                                            <label for="is_featured">Featured Publication</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Visibility Settings</label>
                                <div class="publication-details">
                                    <div class="checkbox-group">
                                        <div class="checkbox-item">
                                            <input type="checkbox" name="allow_download" id="allow_download" value="1" checked>
                                            <label for="allow_download">Allow Download</label>
                                        </div>
                                        <div class="checkbox-item">
                                            <input type="checkbox" name="allow_comments" id="allow_comments" value="1" checked>
                                            <label for="allow_comments">Allow Comments</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Meta Title (SEO)</label>
                            <input type="text" name="meta_title" 
                                   placeholder="Meta title for SEO"
                                   value="<?php echo htmlspecialchars($_POST['meta_title'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Meta Description (SEO)</label>
                            <textarea name="meta_description" 
                                      placeholder="Meta description for SEO"
                                      rows="3"><?php echo htmlspecialchars($_POST['meta_description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Meta Keywords (SEO)</label>
                            <input type="text" name="meta_keywords" 
                                   placeholder="Meta keywords for SEO"
                                   value="<?php echo htmlspecialchars($_POST['meta_keywords'] ?? ''); ?>">
                        </div>
                        
                        <div class="btn-group" style="justify-content: space-between; margin-top: 20px;">
                            <button type="button" class="btn btn-secondary" onclick="switchTab('files')">
                                ← Back to Files & Media
                            </button>
                            <button type="submit" class="btn btn-success">
                                💾 Save Research Publication
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Quick Tips -->
        <div style="background: #f0f9ff; border: 1px solid #bee3f8; border-radius: 8px; padding: 20px; margin-top: 30px;">
            <h3 style="margin-top: 0; color: var(--primary);">💡 Research Publication Guidelines</h3>
            <ul style="margin: 0; padding-left: 20px; color: var(--gray-700);">
                <li><strong>Title:</strong> Should be clear, descriptive, and under 500 characters</li>
                <li><strong>Authors:</strong> List all authors in the correct order of contribution</li>
                <li><strong>Abstract:</strong> Should be comprehensive (200+ words) and summarize the entire research</li>
                <li><strong>Keywords:</strong> Use relevant keywords for better discoverability</li>
                <li><strong>DOI:</strong> Include Digital Object Identifier if available</li>
                <li><strong>Files:</strong> Upload both PDF document and thumbnail image for best presentation</li>
                <li><strong>SEO:</strong> Fill meta fields for better search engine visibility</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Authors management
        const authors = [];
        const authorInput = document.getElementById('authorInput');
        const authorsTags = document.getElementById('authorsTags');
        const authorsHidden = document.getElementById('authorsHidden');
        
        authorInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === 'Tab') {
                e.preventDefault();
                addAuthor();
            }
        });
        
        authorInput.addEventListener('blur', addAuthor);
        
        function addAuthor() {
            const authorName = authorInput.value.trim();
            if (authorName && !authors.includes(authorName)) {
                authors.push(authorName);
                updateAuthorsDisplay();
                authorInput.value = '';
            }
        }
        
        function removeAuthor(index) {
            authors.splice(index, 1);
            updateAuthorsDisplay();
        }
        
        function updateAuthorsDisplay() {
            authorsTags.innerHTML = '';
            authors.forEach((author, index) => {
                const tag = document.createElement('div');
                tag.className = 'author-tag';
                tag.innerHTML = `
                    ${author}
                    <span class="remove-author" onclick="removeAuthor(${index})">×</span>
                `;
                authorsTags.appendChild(tag);
            });
            authorsHidden.value = JSON.stringify(authors);
        }
        
        // File upload display
        function showFileName(input, displayId) {
            const displayDiv = document.getElementById(displayId);
            if (input.files && input.files[0]) {
                displayDiv.innerHTML = `<strong>Selected file:</strong> ${input.files[0].name} (${formatBytes(input.files[0].size)})`;
            } else {
                displayDiv.innerHTML = '';
            }
        }
        
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }
        
        // Tab switching
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(`tab-${tabName}`).classList.add('active');
            document.querySelectorAll('.tab').forEach(tab => {
                if (tab.textContent.toLowerCase().includes(tabName)) {
                    tab.classList.add('active');
                }
            });
            
            // Validate before moving to next tab
            if (tabName === 'publication') {
                if (!validateBasicTab()) {
                    return false;
                }
            } else if (tabName === 'files') {
                if (!validatePublicationTab()) {
                    return false;
                }
            }
            
            return true;
        }
        
        // Tab validation
        function validateBasicTab() {
            const title = document.querySelector('input[name="title"]').value.trim();
            const authors = JSON.parse(authorsHidden.value || '[]');
            const abstract = document.querySelector('textarea[name="abstract"]').value.trim();
            
            if (!title) {
                alert('Please enter a research title.');
                return false;
            }
            
            if (authors.length === 0) {
                alert('Please add at least one author.');
                authorInput.focus();
                return false;
            }
            
            if (abstract.length < 200) {
                alert('Abstract must be at least 200 characters long.');
                return false;
            }
            
            return true;
        }
        
        function validatePublicationTab() {
            // Basic publication validation
            return true;
        }
        
        // Form validation
        document.getElementById('researchForm').addEventListener('submit', function(e) {
            // Validate authors
            const authors = JSON.parse(authorsHidden.value || '[]');
            if (authors.length === 0) {
                e.preventDefault();
                alert('Please add at least one author.');
                switchTab('basic');
                authorInput.focus();
                return false;
            }
            
            // Validate abstract length
            const abstract = document.querySelector('textarea[name="abstract"]').value.trim();
            if (abstract.length < 200) {
                e.preventDefault();
                alert('Abstract must be at least 200 characters long.');
                switchTab('basic');
                return false;
            }
            
            // Show saving indicator
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '⏳ Saving...';
            submitBtn.disabled = true;
            
            return true;
        });
        
        // Character counter for abstract
        const abstractTextarea = document.querySelector('textarea[name="abstract"]');
        if (abstractTextarea) {
            const counter = document.createElement('div');
            counter.style.cssText = 'font-size: 0.875rem; color: var(--gray-600); margin-top: 5px;';
            abstractTextarea.parentNode.insertBefore(counter, abstractTextarea.nextSibling);
            
            function updateAbstractCounter() {
                const length = abstractTextarea.value.length;
                counter.textContent = `${length} characters (minimum 200)`;
                counter.style.color = length < 200 ? 'var(--danger)' : 'var(--success)';
            }
            
            abstractTextarea.addEventListener('input', updateAbstractCounter);
            updateAbstractCounter();
        }
        
        // Auto-save draft
        let autoSaveTimer;
        function autoSaveDraft() {
            const formData = new FormData(document.getElementById('researchForm'));
            const data = Object.fromEntries(formData.entries());
            
            // Save authors separately
            data.authors = JSON.parse(authorsHidden.value || '[]');
            
            // In a real implementation, this would send to server
            console.log('Auto-saving research draft...', data);
            
            // Show notification
            showNotification('💾 Research draft auto-saved');
        }
        
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: var(--success);
                color: white;
                padding: 10px 20px;
                border-radius: 6px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                z-index: 1000;
                animation: fadeInOut 2s ease-in-out;
            `;
            notification.innerHTML = message;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.remove(), 2000);
        }
        
        // Start auto-save
        document.addEventListener('DOMContentLoaded', function() {
            // Load any saved authors from draft
            const savedAuthors = localStorage.getItem('research_draft_authors');
            if (savedAuthors) {
                const parsedAuthors = JSON.parse(savedAuthors);
                parsedAuthors.forEach(author => {
                    if (!authors.includes(author)) {
                        authors.push(author);
                    }
                });
                updateAuthorsDisplay();
            }
            
            // Start auto-save after 30 seconds of inactivity
            let timeout;
            document.getElementById('researchForm').addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(autoSaveDraft, 30000);
                
                // Save authors to localStorage
                localStorage.setItem('research_draft_authors', authorsHidden.value);
            });
        });
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                document.getElementById('researchForm').submit();
            }
            
            // Ctrl/Cmd + 1-4 to switch tabs
            if ((e.ctrlKey || e.metaKey) && e.key >= '1' && e.key <= '4') {
                e.preventDefault();
                const tabNames = ['basic', 'publication', 'files', 'metadata'];
                const tabIndex = parseInt(e.key) - 1;
                switchTab(tabNames[tabIndex]);
            }
        });
        
        // Add fadeInOut animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translateY(20px); }
                20% { opacity: 1; transform: translateY(0); }
                80% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(-20px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>