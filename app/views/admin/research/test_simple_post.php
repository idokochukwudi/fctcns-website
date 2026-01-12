<?php
// Simple test form that bypasses all JavaScript
require_once APP_PATH . '/config/session.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Test Form</title>
</head>
<body>
    <h1>Simple POST Test</h1>
    
    <form method="POST" action="<?php echo BASE_URL; ?>/admin/research/store">
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCSRFToken(); ?>">
        
        <h3>Required Fields:</h3>
        <div>
            <label>Title: *</label><br>
            <input type="text" name="title" value="Test Publication" required>
        </div>
        
        <div>
            <label>Authors: *</label><br>
            <textarea name="authors" required>John Doe, Jane Smith</textarea>
        </div>
        
        <div>
            <label>Abstract: *</label><br>
            <textarea name="abstract" required>This is a test abstract with more than 50 characters to ensure validation passes. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</textarea>
        </div>
        
        <div>
            <label>Research Area: *</label><br>
            <select name="research_area" required>
                <option value="nursing">Nursing</option>
                <option value="midwifery">Midwifery</option>
            </select>
        </div>
        
        <div>
            <label>Publication Date: *</label><br>
            <input type="date" name="publication_date" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        
        <h3>Checkboxes:</h3>
        <div>
            <input type="checkbox" name="is_published" value="1" checked>
            <label>Publish this publication</label>
        </div>
        
        <div>
            <input type="checkbox" name="is_featured" value="1" checked>
            <label>Feature this publication</label>
        </div>
        
        <h3>Optional Fields:</h3>
        <div>
            <label>Publication Type:</label><br>
            <select name="publication_type">
                <option value="journal">Journal Article</option>
            </select>
        </div>
        
        <div>
            <label>Keywords:</label><br>
            <input type="text" name="keywords" value="test, nursing">
        </div>
        
        <hr>
        <div>
            <button type="submit" name="save">Submit Test Form</button>
            <button type="submit" name="save_and_view" value="1">Submit & View</button>
        </div>
    </form>
    
    <p><a href="<?php echo BASE_URL; ?>/admin/research">Back to Research List</a></p>
    
    <script>
        // NO JavaScript validation - pure HTML form
    </script>
</body>
</html>