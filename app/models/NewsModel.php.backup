<?php
/**
 * News Model
 * Handles news data operations
 */
class NewsModel {
    private $db;
    
    public function __construct($database = null) {
        if ($database) {
            $this->db = $database;
        } else {
            require_once __DIR__ . '/../config/database.php';
            $database = Database::getInstance();
            $this->db = $database->getConnection();
        }
    }
    
    public function getLatestNews($limit = 3) {
        // Return empty array for now to avoid errors
        return [];
    }
    
    public function findAll() {
        return [];
    }
}
