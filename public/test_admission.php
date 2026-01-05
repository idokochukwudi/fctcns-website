<?php
// test_admission.php - Place in web root temporarily
require_once 'app/database.php';
require_once 'app/models/AdmissionListModel.php';

echo "<h1>Testing Admission List Integration</h1>";

try {
    // Test database connection
    $db = Database::getInstance()->getConnection();
    echo "<p style='color:green'>✓ Database connected successfully</p>";
    
    // Test model
    $model = new AdmissionListModel();
    $stats = $model->getStatistics();
    echo "<p style='color:green'>✓ Model created successfully</p>";
    
    echo "<h3>Statistics:</h3>";
    echo "<pre>";
    print_r($stats);
    echo "</pre>";
    
    // Test getting some data
    $admissions = $model->getAllAdmissions(1, 5);
    echo "<p>Found " . count($admissions) . " admission records</p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
}
?>