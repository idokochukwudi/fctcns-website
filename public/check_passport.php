<?php
// check_passport.php - Save in your public directory
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Check Passport Photo in Database</h1>";

try {
    // Connect to database
    require_once __DIR__ . '/../app/config/database.php';
    
    // Use your database configuration
    $host = 'localhost';
    $dbname = 'fctcnsed_fctcns_db';
    $username = 'fctcnsed_fctcns_user';
    $password = 'your_password'; // You'll need to fill this in
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check employee ID 2 (from your logs)
    $stmt = $pdo->prepare("SELECT id, employee_number, surname, first_name, passport_photo FROM nominal_roll_employees WHERE id = 2");
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($employee) {
        echo "<h3>Employee Details:</h3>";
        echo "ID: " . $employee['id'] . "<br>";
        echo "Employee Number: " . $employee['employee_number'] . "<br>";
        echo "Name: " . $employee['surname'] . ", " . $employee['first_name'] . "<br>";
        echo "Passport Photo in DB: " . ($employee['passport_photo'] ?? 'NULL') . "<br>";
        
        // Check if file exists on disk
        if (!empty($employee['passport_photo'])) {
            $fullPath = __DIR__ . '/../' . $employee['passport_photo'];
            echo "Full path: " . $fullPath . "<br>";
            echo "File exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "<br>";
            
            if (file_exists($fullPath)) {
                echo "File size: " . filesize($fullPath) . " bytes<br>";
                echo "Last modified: " . date('Y-m-d H:i:s', filemtime($fullPath)) . "<br>";
                
                // Display image if it exists
                echo "<h4>Image Preview:</h4>";
                echo '<img src="../' . $employee['passport_photo'] . '" style="max-width: 200px; border: 1px solid #ccc;">';
            }
        }
    } else {
        echo "Employee not found!";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>