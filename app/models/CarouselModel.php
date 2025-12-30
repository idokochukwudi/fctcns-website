<?php
/**
 * Carousel Model - Simple version for testing
 */
class CarouselModel {
    
    public function __construct() {
        // Try to load database, but don't fail if it doesn't exist
        try {
            require_once __DIR__ . '/../config/database.php';
        } catch (Exception $e) {
            // Silently continue
        }
    }
    
    public function getActiveSlides($limit = 5) {
        // Return fallback slides for testing
        return [
            [
                'title' => 'Welcome to FCT College of Nursing Sciences',
                'subtitle' => 'Testing Mode - Database integration pending',
                'image_path' => '/assets/images/carousel/slide1.jpg',
                'button_text' => 'Explore',
                'button_link' => '/programs'
            ]
        ];
    }
}
