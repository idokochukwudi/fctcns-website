<?php
// Create /public/debug-simple.php
define('ROOT_PATH', dirname(__DIR__));

// Start session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>Debug Status Update Issue</h1>";

// Connect directly to database
$host = 'localhost';
$dbname = 'fctcns_main';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check current status
    $stmt = $db->prepare("SELECT * FROM nominal_roll_employees WHERE id = 24");
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h2>Current Employee Record (ID 24)</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    foreach ($employee as $key => $value) {
        echo "<tr><td><strong>$key</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
    }
    echo "</table>";
    
    // Check if status field exists in table structure
    echo "<h2>Table Structure Check</h2>";
    $stmt = $db->query("SHOW COLUMNS FROM nominal_roll_employees LIKE 'status'");
    $statusColumn = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    print_r($statusColumn);
    echo "</pre>";
    
    // Try to update status directly
    echo "<h2>Test Direct Update</h2>";
    
    // Simulate the update
    $updateData = [
        'id' => 24,
        'employee_number' => $employee['employee_number'],
        'surname' => $employee['surname'],
        'first_name' => $employee['first_name'],
        'status' => 'inactive',
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Try to update
    $sql = "UPDATE nominal_roll_employees SET 
            status = :status,
            updated_at = :updated_at
            WHERE id = :id";
    
    $updateStmt = $db->prepare($sql);
    $result = $updateStmt->execute([
        ':status' => 'inactive',
        ':updated_at' => date('Y-m-d H:i:s'),
        ':id' => 24
    ]);
    
    echo "<p>Direct update result: " . ($result ? "SUCCESS" : "FAILED") . "</p>";
    
    // Check again
    $stmt = $db->prepare("SELECT status FROM nominal_roll_employees WHERE id = 24");
    $stmt->execute();
    $updated = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<p>Updated status: " . ($updated['status'] ?? 'NOT FOUND') . "</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database Error: " . $e->getMessage() . "</p>";
}
?>