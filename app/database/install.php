<?php
/**
 * Database Installation Script
 * 
 * WARNING: This script should only be run once during initial setup.
 * Delete or protect this file after installation.
 * 
 * @package FCT_CNS
 */

// Security check - only allow access from localhost
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    die('<h1>Access Denied</h1><p>This script can only be run from localhost.</p>');
}

// Load environment
require_once __DIR__ . '/../app/config/database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>FCT CNS Database Installation</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 40px; background: #F8F9FA; color: #2C3E50; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h1 { color: #6B4E9B; border-bottom: 2px solid #7FB285; padding-bottom: 10px; }
        .step { margin: 20px 0; padding: 15px; background: #F8F9FA; border-left: 4px solid #6B4E9B; }
        .success { color: #7FB285; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        pre { background: #2C3E50; color: white; padding: 15px; border-radius: 5px; overflow: auto; }
        .btn { background: #6B4E9B; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #5A3F8A; }
        .warning { background: #fff3cd; border-left-color: #ffc107; color: #856404; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>FCT College of Nursing Sciences - Database Installation</h1>
        
        <div class='warning'>
            <strong>⚠️ IMPORTANT:</strong> This script will create or overwrite your database. 
            Backup any existing data before proceeding. Run this only once.
        </div>
";

try {
    $db = Database::getInstance()->getConnection();
    
    // Step 1: Read schema file
    echo "<div class='step'>";
    echo "<h3>Step 1: Reading Schema File</h3>";
    
    $schemaFile = __DIR__ . '/schema.sql';
    if (!file_exists($schemaFile)) {
        throw new Exception("Schema file not found: " . $schemaFile);
    }
    
    $schemaSql = file_get_contents($schemaFile);
    echo "<p class='success'>✓ Schema file loaded (" . strlen($schemaSql) . " bytes)</p>";
    echo "</div>";
    
    // Step 2: Execute schema
    echo "<div class='step'>";
    echo "<h3>Step 2: Creating Database Tables</h3>";
    
    // Split SQL by semicolons (simple approach - for complex SQL use a proper parser)
    $queries = explode(';', $schemaSql);
    $tableCount = 0;
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query) && !preg_match('/^--/', $query)) {
            try {
                $db->exec($query . ';');
                if (preg_match('/CREATE TABLE (\w+)/i', $query, $matches)) {
                    $tableCount++;
                    echo "<p>✓ Created table: <code>" . $matches[1] . "</code></p>";
                }
            } catch (PDOException $e) {
                // Ignore "table already exists" errors during development
                if (strpos($e->getMessage(), 'already exists') === false) {
                    throw $e;
                }
            }
        }
    }
    
    echo "<p class='success'>✓ Created {$tableCount} tables successfully</p>";
    echo "</div>";
    
    // Step 3: Insert sample data
    echo "<div class='step'>";
    echo "<h3>Step 3: Inserting Sample Data</h3>";
    
    $seedsFile = __DIR__ . '/seeds.sql';
    if (!file_exists($seedsFile)) {
        throw new Exception("Seeds file not found: " . $seedsFile);
    }
    
    $seedsSql = file_get_contents($seedsFile);
    $seedQueries = explode(';', $seedsSql);
    $insertCount = 0;
    
    foreach ($seedQueries as $query) {
        $query = trim($query);
        if (!empty($query) && !preg_match('/^--/', $query) && !preg_match('/^SELECT/i', $query)) {
            try {
                $stmt = $db->prepare($query . ';');
                $stmt->execute();
                $insertCount++;
                echo "<p>✓ Executed query</p>";
            } catch (PDOException $e) {
                // Ignore duplicate entry errors
                if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                    echo "<p class='error'>✗ Query error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
        }
    }
    
    echo "<p class='success'>✓ Executed {$insertCount} insert queries</p>";
    echo "</div>";
    
    // Step 4: Verify installation
    echo "<div class='step'>";
    echo "<h3>Step 4: Verification</h3>";
    
    $tables = [
        'carousel_slides',
        'news_articles', 
        'contact_messages',
        'users',
        'settings'
    ];
    
    foreach ($tables as $table) {
        try {
            $stmt = $db->query("SELECT COUNT(*) as count FROM {$table}");
            $result = $stmt->fetch();
            echo "<p>✓ Table <code>{$table}</code>: {$result['count']} records</p>";
        } catch (PDOException $e) {
            echo "<p class='error'>✗ Table <code>{$table}</code>: ERROR - " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    echo "</div>";
    
    // Step 5: Test data
    echo "<div class='step'>";
    echo "<h3>Step 5: Testing Data Retrieval</h3>";
    
    // Test carousel slides
    $stmt = $db->query("SELECT id, title, is_active FROM carousel_slides ORDER BY display_order");
    $slides = $stmt->fetchAll();
    
    echo "<p>Carousel Slides (" . count($slides) . "):</p>";
    echo "<ul>";
    foreach ($slides as $slide) {
        $status = $slide['is_active'] ? '✅ Active' : '❌ Inactive';
        echo "<li>#{$slide['id']}: {$slide['title']} ({$status})</li>";
    }
    echo "</ul>";
    
    // Test admin user
    $stmt = $db->query("SELECT username, email, role FROM users WHERE username = 'admin'");
    $admin = $stmt->fetch();
    
    echo "<p>Admin User:</p>";
    echo "<ul>";
    echo "<li>Username: {$admin['username']}</li>";
    echo "<li>Email: {$admin['email']}</li>";
    echo "<li>Role: {$admin['role']}</li>";
    echo "</ul>";
    
    echo "</div>";
    
    // Final message
    echo "<div class='step' style='background: #d4edda; border-left-color: #7FB285;'>";
    echo "<h3>🎉 Installation Complete!</h3>";
    echo "<p>Database has been successfully installed with sample data.</p>";
    echo "<p><strong>IMPORTANT:</strong> For security, please:</p>";
    echo "<ol>
        <li>Delete this installation script: <code>database/install.php</code></li>
        <li>Change the default admin password immediately after first login</li>
        <li>Update the database credentials in <code>.env</code> for production</li>
    </ol>";
    echo "<p><a href='/' class='btn'>Go to Website</a> <a href='/admin' class='btn' style='background: #7FB285;'>Go to Admin</a></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='step' style='background: #f8d7da; border-left-color: #e74c3c;'>";
    echo "<h3>❌ Installation Failed</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Check that:</p>";
    echo "<ul>
        <li>MySQL is running in XAMPP</li>
        <li>Database 'fctcns_main' exists (create it in phpMyAdmin)</li>
        <li>Database user has correct permissions</li>
        <li><code>.env</code> file has correct database credentials</li>
    </ul>";
    echo "<pre>Debug Info:\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}

echo "
    </div>
</body>
</html>";
?>