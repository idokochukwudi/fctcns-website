<?php
/**
 * JAMB Test File - Complete Test Suite
 * 
 * Place this file in your website root directory (same folder as index.php)
 * Access it at: https://fctcns.edu.ng/jamb-test.php
 */

// Start session to check session data
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set JSON header
header('Content-Type: application/json');

// Get request information
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$input = file_get_contents('php://input');
$jsonInput = json_decode($input, true);

// Build response
$response = [
    'success' => true,
    'test_name' => 'JAMB Test Suite',
    'timestamp' => date('Y-m-d H:i:s'),
    'request' => [
        'method' => $method,
        'uri' => $uri,
        'query_string' => $_SERVER['QUERY_STRING'] ?? '',
    ],
    'session' => [
        'id' => session_id(),
        'applicant_id' => $_SESSION['applicant_id'] ?? null,
        'has_csrf_tokens' => isset($_SESSION['csrf_tokens']),
        'csrf_tokens_count' => isset($_SESSION['csrf_tokens']) ? count($_SESSION['csrf_tokens']) : 0,
    ],
    'post_data' => $_POST,
    'get_data' => $_GET,
    'raw_input' => $input,
    'json_input' => $jsonInput,
    'files' => $_FILES ? array_keys($_FILES) : [],
    'headers' => [
        'content_type' => $_SERVER['CONTENT_TYPE'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    ]
];

// Log the response for debugging
error_log("=== JAMB TEST RESPONSE ===");
error_log("Method: " . $method);
error_log("Session ID: " . session_id());

// Output JSON
echo json_encode($response, JSON_PRETTY_PRINT);
?>