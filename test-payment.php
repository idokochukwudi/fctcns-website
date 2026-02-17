<?php
// Simple test file for payment initiation
require_once 'app/config/constants.php';
require_once 'app/config/database.php';
require_once 'app/controllers/PaymentController.php';

session_start();

// Set a test session
$_SESSION['applicant_id'] = 18; // Use your actual applicant ID

echo "<h2>Testing Payment Initiate</h2>";

try {
    $controller = new PaymentController();
    
    // Mock the request
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $GLOBALS['HTTP_RAW_POST_DATA'] = json_encode(['csrf_token' => 'test']);
    
    // Call the method
    $controller->initiate();
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}