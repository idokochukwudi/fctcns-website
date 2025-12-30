<?php
require_once 'app/config/database.php';
try {
    $database = Database::getInstance();
    $db = $database->getConnection();
    
    echo "✅ Database connected\n";
    
    // Count applications
    $stmt = $db->query("SELECT COUNT(*) as count FROM applications");
    $result = $stmt->fetch();
    echo "Current applications: " . $result['count'] . "\n";
    
    if ($result['count'] == 0) {
        echo "❌ No applications found!\n";
        echo "You need to create one first at:\n";
        echo "http://localhost/fctcns-website/admin/applications/create\n";
    } else {
        echo "✅ Applications exist. Listing them:\n";
        $stmt = $db->query("SELECT id, first_name, last_name, email FROM applications ORDER BY id");
        while ($row = $stmt->fetch()) {
            echo "   ID: {$row['id']}, Name: {$row['first_name']} {$row['last_name']}, Email: {$row['email']}\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
