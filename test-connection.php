<?php
/**
 * Direct Database Connection Test
 * This bypasses all our code to test raw database connection
 */

echo "<h1>Database Connection Test</h1>";
echo "<p>Testing with XAMPP default credentials...</p>";

// Test 1: Try connecting with root (no password)
echo "<h3>Test 1: Connecting as 'root' with no password</h3>";
try {
    $pdo = new PDO('mysql:host=localhost;port=3306;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Connected to MySQL server successfully</p>";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE 'fctcns_main'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Database 'fctcns_main' exists</p>";
        
        // Try to use the database
        $pdo->exec("USE fctcns_main");
        
        // Check tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "<p>Tables in database: " . count($tables) . "</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠ Database 'fctcns_main' does NOT exist</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Connection failed: " . $e->getMessage() . "</p>";
}

// Test 2: Try connecting with fctcns_admin
echo "<h3>Test 2: Connecting as 'fctcns_admin' with password</h3>";
try {
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=fctcns_main;charset=utf8mb4', 'fctcns_admin', 'FctCnsAdmin2024');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Connected to 'fctcns_main' as 'fctcns_admin' successfully</p>";
    
    // Check tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Found " . count($tables) . " tables</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Connection failed: " . $e->getMessage() . "</p>";
}

// Test 3: Check current .env file
echo "<h3>Test 3: Current .env File Contents</h3>";
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    echo "<pre>" . htmlspecialchars($envContent) . "</pre>";
} else {
    echo "<p style='color: red;'>✗ .env file not found at: $envPath</p>";
}

// Test 4: Manual override option
echo "<h3>Test 4: Quick Fix Options</h3>";
echo "<p><strong>Option A:</strong> Use phpMyAdmin to check users and permissions</p>";
echo "<p><a href='http://localhost/phpmyadmin' target='_blank' style='background: #6B4E9B; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Open phpMyAdmin</a></p>";

echo "<p><strong>Option B:</strong> Create a fresh database</p>";
echo "<pre>
-- Run this in phpMyAdmin SQL tab:
DROP DATABASE IF EXISTS fctcns_main;
CREATE DATABASE fctcns_main CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON fctcns_main.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
</pre>";

echo "<p><strong>Option C:</strong> Update .env to use fctcns_admin</p>";
echo "<pre>
# In .env file, change to:
DB_USERNAME=fctcns_admin
DB_PASSWORD=FctCnsAdmin2024
</pre>";

echo "<hr><h2>📋 What to Do Next</h2>";
echo "<p>1. Run this test: <a href='http://localhost/fctcns-website/test-connection.php'>test-connection.php</a></p>";
echo "<p>2. Tell me which connection worked (Test 1 or Test 2)</p>";
echo "<p>3. We'll update the .env file based on what works</p>";
?>