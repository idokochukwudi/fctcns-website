<?php
// View file for creating a new research publication
// All data is passed from the controller
?>
<!-- Flash Messages -->
<?php if ($flash_success || $flash_error): ?>
<div class="flash-messages">
    <?php if ($flash_success): ?>
    <div class="alert alert-success">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <?php echo htmlspecialchars($flash_success); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($flash_error): ?>
    <div class="alert alert-error">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <?php echo htmlspecialchars($flash_error); ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Progress Indicator -->
<div class="progress-indicator" id="progressIndicator" style="display: none;">
    <div class="spinner"></div>
    <span>Saving publication...</span>
</div>

<!-- Main Form -->
<form id="publicationForm" method="POST" action="<?php echo BASE_URL; ?>/admin/research/store" enctype="multipart/form-data">
    <!-- CSRF Token -->
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
    
    <div class="form-layout">
        <!-- Left Column - Main Content -->
        <div>
            <!-- Basic Information -->
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Basic Information</h2>
                </div>
                
                <!-- Title -->
                <div class="form-group">
                    <label for="title" class="form-label required">Title</label>
                    <input type="text" class="form-control <?php echo isset($flash_errors['title']) ? 'is-invalid' : ''; ?>" 
                           id="title" name="title" 
                           value="<?php echo htmlspecialchars($defaults['title'] ?? ''); ?>"
                           required maxlength="500">
                    <?php if (isset($flash_errors['title'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($flash_errors['title']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-text">Maximum 500 characters</div>
                </div>
                
                <!-- Authors -->
                <div class="form-group">
                    <label for="authors" class="form-label required">Authors</label>
                    <textarea class="form-control <?php echo isset($flash_errors['authors']) ? 'is-invalid' : ''; ?>" 
                              id="authors" name="authors" rows="3" required><?php echo htmlspecialchars($defaults['authors'] ?? ''); ?></textarea>
                    <?php if (isset($flash_errors['authors'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($flash_errors['authors']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-text">Enter authors separated by commas. Format: Lastname, Firstname</div>
                </div>
                
                <!-- Abstract -->
                <div class="form-group">
                    <label for="abstract" class="form-label required">Abstract</label>
                    <textarea class="form-control <?php echo isset($flash_errors['abstract']) ? 'is-invalid' : ''; ?>" 
                              id="abstract" name="abstract" rows="6" required
                              minlength="200"><?php echo htmlspecialchars($defaults['abstract'] ?? ''); ?></textarea>
                    <?php if (isset($flash_errors['abstract'])): ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($flash_errors['abstract']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-text">Minimum 200 characters. Current: <span id="abstractCount">0</span> characters</div>
                </div>
                
                <!-- Keywords -->
                <div class="form-group">
                    <label for="keywords" class="form-label">Keywords</label>
                    <input type="text" class="form-control" id="keywords" name="keywords"
                           value="<?php echo htmlspecialchars($defaults['keywords'] ?? ''); ?>">
                    <div class="form-text">Separate keywords with commas</div>
                </div>
            </div>
            
            <!-- Publication Details -->
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Publication Details</h2>
                </div>
                
                <div class="grid-2">
                    <!-- Publication Type -->
                    <div class="form-group">
                        <label for="publication_type" class="form-label required">Publication Type</label>
                        <select class="form-control" id="publication_type" name="publication_type" required>
                            <option value="">Select Type</option>
                            <option value="journal" <?php echo ($defaults['publication_type'] ?? '') == 'journal' ? 'selected' : ''; ?>>Journal Article</option>
                            <option value="conference" <?php echo ($defaults['publication_type'] ?? '') == 'conference' ? 'selected' : ''; ?>>Conference Paper</option>
                            <option value="book" <?php echo ($defaults['publication_type'] ?? '') == 'book' ? 'selected' : ''; ?>>Book/Chapter</option>
                            <option value="thesis" <?php echo ($defaults['publication_type'] ?? '') == 'thesis' ? 'selected' : ''; ?>>Thesis/Dissertation</option>
                            <option value="report" <?php echo ($defaults['publication_type'] ?? '') == 'report' ? 'selected' : ''; ?>>Technical Report</option>
                        </select>
                    </div>
                    
                    <!-- Research Area -->
                    <div class="form-group">
                        <label for="research_area" class="form-label required">Research Area</label>
                        <select class="form-control <?php echo isset($flash_errors['research_area']) ? 'is-invalid' : ''; ?>" 
                                id="research_area" name="research_area" required>
                            <option value="">Select Research Area</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['slug']; ?>" 
                                <?php echo ($defaults['research_area'] ?? '') == $category['slug'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($flash_errors['research_area'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($flash_errors['research_area']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Journal/Conference Details -->
                <div id="journalDetails" style="display: none; margin-bottom: 1.5rem;">
                    <div class="grid-3">
                        <div class="form-group">
                            <label for="journal_name" class="form-label">Journal/Conference Name</label>
                            <input type="text" class="form-control" id="journal_name" name="journal_name"
                                   value="<?php echo htmlspecialchars($defaults['journal_name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="volume" class="form-label">Volume</label>
                            <input type="text" class="form-control" id="volume" name="volume"
                                   value="<?php echo htmlspecialchars($defaults['volume'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="issue" class="form-label">Issue</label>
                            <input type="text" class="form-control" id="issue" name="issue"
                                   value="<?php echo htmlspecialchars($defaults['issue'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Pages, Publisher, Date -->
                <div class="grid-3">
                    <div class="form-group">
                        <label for="pages" class="form-label">Pages</label>
                        <input type="text" class="form-control" id="pages" name="pages"
                               value="<?php echo htmlspecialchars($defaults['pages'] ?? ''); ?>"
                               placeholder="e.g., 123-145">
                    </div>
                    
                    <div class="form-group">
                        <label for="publisher" class="form-label">Publisher</label>
                        <input type="text" class="form-control" id="publisher" name="publisher"
                               value="<?php echo htmlspecialchars($defaults['publisher'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="publication_date" class="form-label required">Publication Date</label>
                        <input type="date" class="form-control <?php echo isset($flash_errors['publication_date']) ? 'is-invalid' : ''; ?>" 
                               id="publication_date" name="publication_date" 
                               value="<?php echo htmlspecialchars($defaults['publication_date'] ?? date('Y-m-d')); ?>" required>
                        <?php if (isset($flash_errors['publication_date'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($flash_errors['publication_date']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- DOI and URL -->
                <div class="grid-2">
                    <div class="form-group">
                        <label for="doi" class="form-label">DOI</label>
                        <input type="text" class="form-control <?php echo isset($flash_errors['doi']) ? 'is-invalid' : ''; ?>" 
                               id="doi" name="doi"
                               value="<?php echo htmlspecialchars($defaults['doi'] ?? ''); ?>"
                               placeholder="e.g., 10.1000/182">
                        <?php if (isset($flash_errors['doi'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($flash_errors['doi']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="url" class="form-label">URL</label>
                        <input type="url" class="form-control" id="url" name="url"
                               value="<?php echo htmlspecialchars($defaults['url'] ?? ''); ?>"
                               placeholder="https://example.com">
                    </div>
                </div>
                
                <!-- Citations and Impact Factor -->
                <div class="grid-2">
                    <div class="form-group">
                        <label for="citations" class="form-label">Citations</label>
                        <input type="number" class="form-control" id="citations" name="citations"
                               value="<?php echo htmlspecialchars($defaults['citations'] ?? 0); ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="impact_factor" class="form-label">Impact Factor</label>
                        <input type="number" class="form-control" id="impact_factor" name="impact_factor"
                               value="<?php echo htmlspecialchars($defaults['impact_factor'] ?? ''); ?>"
                               step="0.001" min="0" placeholder="e.g., 3.456">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Sidebar -->
        <div>
            <!-- Publication Status -->
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Publication Status</h2>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1"
                        <?php echo ($defaults['is_published'] ?? 0) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_published">
                        Publish this publication
                    </label>
                </div>
                <div class="form-text" style="margin-top: 0.5rem; margin-bottom: 1rem;">
                    Published articles will appear on the public research page
                </div>
                
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1"
                        <?php echo ($defaults['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_featured">
                        Feature this publication
                    </label>
                </div>
                <div class="form-text" style="margin-top: 0.5rem;">
                    Featured articles will be highlighted on the public research page
                </div>
            </div>
            
            <!-- Research File -->
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Research File</h2>
                </div>
                
                <div class="file-upload">
                    <label for="research_file" class="file-upload-label">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Click to upload research file</span>
                        <span style="font-size: 0.75rem;">(PDF, DOC, DOCX - Max: 10MB)</span>
                    </label>
                    <input type="file" id="research_file" name="research_file"
                           accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                </div>
            </div>
            
            <!-- Thumbnail Image -->
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Thumbnail Image</h2>
                </div>
                
                <div class="file-upload">
                    <label for="thumbnail" class="file-upload-label">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        <span>Click to upload thumbnail</span>
                        <span style="font-size: 0.75rem;">(JPG, PNG, GIF, WebP - Max: 2MB)</span>
                    </label>
                    <input type="file" id="thumbnail" name="thumbnail"
                           accept="image/jpeg,image/png,image/gif,image/webp">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="form-card">
                <div class="action-buttons">
                    <button type="submit" name="save" class="btn btn-primary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Save Publication
                    </button>
                    
                    <button type="submit" name="save_and_view" class="btn btn-outline">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        Save & View
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    /* FORM SPECIFIC STYLES */
    .form-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: start;
    }
    
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .form-card-header {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .form-card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-800);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-700);
    }
    
    .form-label.required::after {
        content: " *";
        color: var(--danger-color);
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: var(--danger-color);
    }
    
    .invalid-feedback {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: var(--danger-color);
    }
    
    .form-text {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: var(--gray-600);
    }
    
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
    
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
    }
    
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .form-check-input {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 1px solid var(--gray-300);
        cursor: pointer;
    }
    
    .form-check-label {
        font-size: 0.875rem;
        cursor: pointer;
    }
    
    .file-upload {
        border: 2px dashed var(--gray-300);
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        background: var(--gray-50);
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .file-upload:hover {
        border-color: var(--primary-color);
        background: var(--gray-100);
    }
    
    .file-upload input[type="file"] {
        display: none;
    }
    
    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        color: var(--gray-600);
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--gray-200);
    }
    
    .action-buttons .btn {
        flex: 1;
    }
    
    .progress-indicator {
        background: rgba(49, 130, 206, 0.1);
        border: 1px solid rgba(49, 130, 206, 0.2);
        border-radius: 8px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid var(--gray-300);
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .flash-messages {
        margin-bottom: 1.5rem;
    }
    
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .alert-success {
        background: rgba(56, 161, 105, 0.1);
        border: 1px solid rgba(56, 161, 105, 0.2);
        color: var(--success-color);
    }
    
    .alert-error {
        background: rgba(229, 62, 62, 0.1);
        border: 1px solid rgba(229, 62, 62, 0.2);
        color: var(--danger-color);
    }
    
    @media (max-width: 1024px) {
        .form-layout {
            grid-template-columns: 1fr;
        }
        
        .grid-3 {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .grid-2,
        .grid-3 {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
    
    @media (max-width: 640px) {
        .form-card {
            padding: 1rem;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('publicationForm');
        const progressIndicator = document.getElementById('progressIndicator');
        const abstractTextarea = document.getElementById('abstract');
        const abstractCount = document.getElementById('abstractCount');
        const publicationType = document.getElementById('publication_type');
        const journalDetails = document.getElementById('journalDetails');
        const publicationDate = document.getElementById('publication_date');
        
        // Abstract character count
        function updateAbstractCount() {
            abstractCount.textContent = abstractTextarea.value.length;
        }
        
        abstractTextarea.addEventListener('input', updateAbstractCount);
        updateAbstractCount(); // Initial count
        
        // Show/hide journal details based on publication type
        function toggleJournalDetails() {
            if (publicationType.value === 'journal' || publicationType.value === 'conference') {
                journalDetails.style.display = 'block';
            } else {
                journalDetails.style.display = 'none';
            }
        }
        
        publicationType.addEventListener('change', toggleJournalDetails);
        toggleJournalDetails(); // Initial state
        
        // Auto-fill date to today if empty
        if (!publicationDate.value) {
            const today = new Date().toISOString().split('T')[0];
            publicationDate.value = today;
        }
        
        // Form validation and submission
        form.addEventListener('submit', function(e) {
            // Client-side validation
            let isValid = true;
            
            // Required fields
            const requiredFields = ['title', 'authors', 'abstract', 'research_area', 'publication_date'];
            requiredFields.forEach(fieldName => {
                const field = form.elements[fieldName];
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                }
            });
            
            // Abstract minimum length
            if (abstractTextarea.value.length < 200) {
                abstractTextarea.classList.add('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
                return;
            }
            
            // Show progress indicator
            progressIndicator.style.display = 'flex';
            
            // Disable submit buttons to prevent double submission
            const submitButtons = form.querySelectorAll('button[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="animation: spin 1s linear infinite;"><path d="M4 10a6 6 0 1112 0 6 6 0 01-12 0z" fill="none" stroke="currentColor" stroke-width="2"/></svg> Saving...';
            });
        });
        
        // File upload preview
        const researchFileInput = document.getElementById('research_file');
        const thumbnailInput = document.getElementById('thumbnail');
        
        researchFileInput.addEventListener('change', function() {
            const label = this.previousElementSibling;
            if (this.files.length > 0) {
                label.innerHTML = `
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>${this.files[0].name}</span>
                    <span style="font-size: 0.75rem;">(${(this.files[0].size / 1024 / 1024).toFixed(2)} MB)</span>
                `;
            }
        });
        
        thumbnailInput.addEventListener('change', function() {
            const label = this.previousElementSibling;
            if (this.files.length > 0) {
                label.innerHTML = `
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>${this.files[0].name}</span>
                    <span style="font-size: 0.75rem;">(${(this.files[0].size / 1024 / 1024).toFixed(2)} MB)</span>
                `;
            }
        });
    });
</script>