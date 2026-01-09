<?php
// test_update.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Employee Update</h1>";

// Start session with same config
session_name('fcns_app_sess');
ini_set('session.save_path', '/tmp');
session_start();

// Simulate logged in user (TEMPORARY FOR TESTING)
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'admin';
$_SESSION['username'] = 'testuser';
$_SESSION['csrf_token'] = 'testtoken';
$_SESSION['csrf_token_time'] = time();

echo "Session ID: " . session_id() . "<br>";
echo "User ID in session: " . ($_SESSION['user_id'] ?? 'NOT SET') . "<br><br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>POST Results:</h3>";
    echo "POST data: " . print_r($_POST, true) . "<br>";
    echo "FILES data: " . print_r($_FILES, true) . "<br>";
    echo "Session after POST: " . print_r($_SESSION, true) . "<br>";
    exit;
}
?>

<form action="test_update.php" method="post" enctype="multipart/form-data">
    <h3>Required Fields:</h3>
    Surname: <input type="text" name="surname" value="Test" required><br>
    First Name: <input type="text" name="first_name" value="User" required><br>
    Employee Number: <input type="text" name="employee_number" value="EMP999" required><br>
    
    <h3>Optional Passport Photo:</h3>
    <input type="file" name="passport_photo"><br><br>
    
    <h3>CSRF Tokens:</h3>
    <input type="hidden" name="csrf_token" value="testtoken">
    <input type="hidden" name="csrf_token_time" value="<?php echo time(); ?>"><br>
    
    <input type="submit" value="Test Update">
</form>