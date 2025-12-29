<?php
/**
 * API Endpoint for Carousel Slides
 * Returns JSON data for homepage carousel
 */

// Allow CORS for local development
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Load database configuration
require_once dirname(__DIR__, 2) . '/app/config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Fetch active carousel slides
    $stmt = $db->query("
        SELECT id, title, subtitle, image_path, button_text, button_link, display_order
        FROM carousel_slides 
        WHERE is_active = TRUE 
        ORDER BY display_order
        LIMIT 10
    ");
    
    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'slides' => $slides,
        'count' => count($slides)
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}