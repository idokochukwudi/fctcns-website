<?php
// test_upload.php - Minimal test
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Minimal Upload Test</h1>";

// Test without any session or CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    echo "<h3>Upload Results:</h3>";
    echo "File name: " . htmlspecialchars($_FILES['photo']['name']) . "<br>";
    echo "File size: " . $_FILES['photo']['size'] . " bytes<br>";
    echo "File error: " . $_FILES['photo']['error'] . "<br>";
    
    // Try to move it
    $dest = __DIR__ . '/test_upload_' . time() . '.jpg';
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
        echo "SUCCESS: File moved to $dest<br>";
    } else {
        echo "FAILED to move file<br>";
        echo "Temp file exists: " . (file_exists($_FILES['photo']['tmp_name']) ? 'Yes' : 'No') . "<br>";
    }
    
    exit;
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="photo" required>
    <input type="submit" value="Upload Test">
</form>