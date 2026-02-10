<?php
/**
 * Test script that works on both local dev and shared hosting
 */

// Check if this is local dev or shared hosting
$isLocal = ($_SERVER['SERVER_NAME'] ?? '') === 'localhost' || 
           strpos($_SERVER['SERVER_NAME'] ?? '', '127.0.0.1') !== false ||
           strpos($_SERVER['DOCUMENT_ROOT'] ?? '', 'htdocs') !== false;

echo "<h1>" . ($isLocal ? "🏠 LOCAL DEVELOPMENT" : "🌐 SHARED HOSTING") . "</h1>";

// Test upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $type = $_POST['type'] ?? 'news';
    
    // Determine correct upload directory
    if ($isLocal) {
        // Local dev - try project structure
        $uploadDir = dirname(__DIR__) . '/public/uploads/' . $type . '/';
    } else {
        // Shared hosting - use document root
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $type . '/';
    }
    
    // Create directory
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $filename = 'test_' . ($isLocal ? 'local_' : 'shared_') . time() . '_' . basename($_FILES['image']['name']);
    $destination = $uploadDir . $filename;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        chmod($destination, 0644);
        
        $relativePath = '/uploads/' . $type . '/' . $filename;
        $baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
        $fullUrl = $baseUrl . $relativePath;
        
        echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px;'>";
        echo "<h3 style='color: #155724;'>✅ SUCCESS!</h3>";
        echo "<p><strong>Environment:</strong> " . ($isLocal ? 'Local' : 'Shared Hosting') . "</p>";
        echo "<p><strong>Upload Dir:</strong> $uploadDir</p>";
        echo "<p><strong>Filename:</strong> $filename</p>";
        echo "<p><strong>Relative Path:</strong> $relativePath</p>";
        echo "<p><strong>Full URL:</strong> <a href='$fullUrl' target='_blank'>$fullUrl</a></p>";
        echo "<img src='$relativePath' style='max-width: 300px; border: 1px solid #ccc;'>";
        echo "</div>";
    }
    
    echo "<hr>";
}
?>

<h2>Test Upload Form</h2>
<form method="POST" enctype="multipart/form-data">
    <div style="margin: 10px 0;">
        <label>Type:</label>
        <select name="type">
            <option value="news">News</option>
            <option value="events">Events</option>
        </select>
    </div>
    
    <div style="margin: 10px 0;">
        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required>
    </div>
    
    <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px;">
        Test Upload
    </button>
</form>

<h2>Environment Info</h2>
<pre>
Server: <?php echo $_SERVER['SERVER_NAME'] ?? 'N/A'; ?>
Document Root: <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?>
Script: <?php echo __FILE__; ?>
Detected: <?php echo $isLocal ? 'Local Development' : 'Shared Hosting'; ?>
</pre>