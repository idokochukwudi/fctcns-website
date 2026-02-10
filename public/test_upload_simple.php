<?php
// test_upload_simple.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>Upload Results:</h3>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    if (isset($_FILES['test_image']) && $_FILES['test_image']['error'] == 0) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/test/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        $destination = $uploadDir . $_FILES['test_image']['name'];
        if (move_uploaded_file($_FILES['test_image']['tmp_name'], $destination)) {
            echo "Success! File saved to: " . $destination;
        } else {
            echo "Failed to move file.";
        }
    }
}
?>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="test_image">
    <input type="submit" value="Upload Test">
</form>