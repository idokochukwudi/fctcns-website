<?php
/**
 * Database Test Script
 * 
 * @package FCT_CNS
 */

// Security - only allow local access
if (!in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1', 'localhost'])) {
    header('HTTP/1.0 403 Forbidden');
    die('Access denied. This script can only be run from localhost.');
}

// Load database configuration
require_once __DIR__ . '/../app/config/database.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #F8F9FA; color: #2C3E50; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .card { background: white; border-radius: 10px; padding: 25px; margin: 20px 0; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
        h1 { color: #6B4E9B; margin-bottom: 10px; }
        h2 { color: #5A6C7D; margin: 20px 0 10px; border-bottom: 1px solid #E1E8ED; padding-bottom: 5px; }
        .success { color: #7FB285; }
        .error { color: #e74c3c; }
        .info { color: #3498db; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #E1E8ED; }
        th { background: #F8F9FA; font-weight: 600; color: #2C3E50; }
        tr:hover { background: #F8F9FA; }
        pre { background: #2C3E50; color: white; padding: 15px; border-radius: 5px; overflow: auto; margin: 10px 0; }
        .btn { display: inline-block; background: #6B4E9B; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin: 5px; }
        .btn:hover { background: #5A3F8A; }
        .status { display: inline-block; padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .status-success { background: #d4edda; color: #155724; }
        .status-error { background: #f8d7da; color: #721c24; }
        .status-warning { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>FCT CNS Database Connection Test</h1>
            <p>Testing database connectivity and structure for FCT College of Nursing Sciences</p>
        </div>
        
        <?php
        try {
            echo "<div class='card'>";
            echo "<h2>📊 Connection Test</h2>";
            
            // Test database connection
            $db = Database::getInstance();
            $connection = $db->getConnection();
            
            echo "<p><span class='status status-success'>✓ CONNECTED</span> to database successfully</p>";
            
            // Get database info
            $stmt = $connection->query("SELECT DATABASE() as db, VERSION() as version, USER() as user");
            $info = $stmt->fetch();
            
            echo "<table>";
            echo "<tr><th>Database</th><td>" . htmlspecialchars($info['db']) . "</td></tr>";
            echo "<tr><th>MySQL Version</th><td>" . htmlspecialchars($info['version']) . "</td></tr>";
            echo "<tr><th>Connection User</th><td>" . htmlspecialchars($info['user']) . "</td></tr>";
            echo "<tr><th>PDO Driver</th><td>" . $connection->getAttribute(PDO::ATTR_DRIVER_NAME) . "</td></tr>";
            echo "</table>";
            
            echo "</div>";
            
            // Check required tables
            echo "<div class='card'>";
            echo "<h2>📋 Table Verification</h2>";
            
            $requiredTables = [
                'carousel_slides' => 'Homepage carousel',
                'news_articles' => 'News articles',
                'contact_messages' => 'Contact form submissions',
                'users' => 'Admin users',
                'settings' => 'Site settings'
            ];
            
            $tableStatus = [];
            
            foreach ($requiredTables as $table => $description) {
                try {
                    $stmt = $connection->query("SHOW TABLES LIKE '$table'");
                    $exists = $stmt->rowCount() > 0;
                    
                    if ($exists) {
                        $stmt = $connection->query("SELECT COUNT(*) as count FROM $table");
                        $count = $stmt->fetch()['count'];
                        $tableStatus[$table] = [
                            'status' => 'exists',
                            'count' => $count,
                            'class' => 'status-success',
                            'text' => "✓ {$count} records"
                        ];
                    } else {
                        $tableStatus[$table] = [
                            'status' => 'missing',
                            'class' => 'status-error',
                            'text' => '✗ Missing table'
                        ];
                    }
                } catch (Exception $e) {
                    $tableStatus[$table] = [
                        'status' => 'error',
                        'class' => 'status-error',
                        'text' => '✗ Error: ' . htmlspecialchars($e->getMessage())
                    ];
                }
            }
            
            echo "<table>";
            echo "<tr><th>Table Name</th><th>Description</th><th>Status</th></tr>";
            
            foreach ($requiredTables as $table => $description) {
                $status = $tableStatus[$table];
                echo "<tr>";
                echo "<td><code>{$table}</code></td>";
                echo "<td>{$description}</td>";
                echo "<td><span class='status {$status['class']}'>{$status['text']}</span></td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
            // Check for sample data
            echo "<h3 style='margin-top: 25px;'>Sample Data Check</h3>";
            
            $checks = [
                'Active Carousel Slides' => "SELECT COUNT(*) as count FROM carousel_slides WHERE is_active = TRUE",
                'Admin User' => "SELECT COUNT(*) as count FROM users WHERE username = 'admin'",
                'Published News' => "SELECT COUNT(*) as count FROM news_articles WHERE is_published = TRUE",
                'Site Settings' => "SELECT COUNT(*) as count FROM settings"
            ];
            
            echo "<table>";
            echo "<tr><th>Data Type</th><th>Count</th><th>Query</th></tr>";
            
            foreach ($checks as $label => $query) {
                try {
                    $stmt = $connection->query($query);
                    $result = $stmt->fetch();
                    $count = $result['count'];
                    $statusClass = $count > 0 ? 'status-success' : 'status-warning';
                    $statusText = $count > 0 ? "✓ {$count}" : "⚠️ {$count}";
                    
                    echo "<tr>";
                    echo "<td>{$label}</td>";
                    echo "<td><span class='status {$statusClass}'>{$statusText}</span></td>";
                    echo "<td><code>" . htmlspecialchars(substr($query, 0, 50)) . "...</code></td>";
                    echo "</tr>";
                } catch (Exception $e) {
                    echo "<tr>";
                    echo "<td>{$label}</td>";
                    echo "<td><span class='status status-error'>✗ Error</span></td>";
                    echo "<td><code>" . htmlspecialchars($e->getMessage()) . "</code></td>";
                    echo "</tr>";
                }
            }
            
            echo "</table>";
            echo "</div>";
            
            // Display carousel slides
            echo "<div class='card'>";
            echo "<h2>🖼️ Carousel Slides Preview</h2>";
            
            $stmt = $connection->query("
                SELECT id, title, subtitle, image_path, button_text, button_link, display_order, is_active 
                FROM carousel_slides 
                ORDER BY display_order
            ");
            $slides = $stmt->fetchAll();
            
            if (count($slides) > 0) {
                echo "<table>";
                echo "<tr>
                    <th>Order</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Button</th>
                    <th>Link</th>
                    <th>Status</th>
                </tr>";
                
                foreach ($slides as $slide) {
                    $status = $slide['is_active'] ? 
                        '<span class="status status-success">Active</span>' : 
                        '<span class="status status-warning">Inactive</span>';
                    
                    echo "<tr>";
                    echo "<td>{$slide['display_order']}</td>";
                    echo "<td><strong>{$slide['title']}</strong></td>";
                    echo "<td>" . htmlspecialchars(substr($slide['subtitle'], 0, 50)) . "...</td>";
                    echo "<td>{$slide['button_text']}</td>";
                    echo "<td><code>{$slide['button_link']}</code></td>";
                    echo "<td>{$status}</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            } else {
                echo "<p class='status status-warning'>No carousel slides found. Run the installation script.</p>";
            }
            
            echo "</div>";
            
            // Test database operations
            echo "<div class='card'>";
            echo "<h2>⚡ Performance Test</h2>";
            
            $startTime = microtime(true);
            
            // Simple query performance test
            $stmt = $connection->query("SELECT 1 as test");
            $testResult = $stmt->fetch();
            
            $queryTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
            
            echo "<p>Simple query execution time: <strong>" . number_format($queryTime, 2) . " ms</strong></p>";
            
            if ($queryTime < 10) {
                echo "<p><span class='status status-success'>✓ Excellent performance</span></p>";
            } elseif ($queryTime < 50) {
                echo "<p><span class='status status-success'>✓ Good performance</span></p>";
            } else {
                echo "<p><span class='status status-warning'>⚠️ Slow query performance</span></p>";
            }
            
            echo "</div>";
            
            // Final status
            echo "<div class='card' style='background: #d4edda;'>";
            echo "<h2>✅ Database Test Complete</h2>";
            echo "<p>All database checks completed successfully. Your database is ready for development.</p>";
            
            $missingTables = array_filter($tableStatus, function($status) {
                return $status['status'] === 'missing';
            });
            
            if (count($missingTables) > 0) {
                echo "<p class='status status-warning'>⚠️ Some tables are missing. Run the installation script:</p>";
                echo "<p><a href='/database/install.php' class='btn'>Run Database Installation</a></p>";
            } else {
                echo "<p><strong>Next Steps:</strong></p>";
                echo "<ol>
                    <li>Delete test scripts: <code>database/test.php</code> and <code>database/install.php</code></li>
                    <li>Proceed to Stage 3: Core Backend Architecture</li>
                    <li>Test admin login with: username: <code>admin</code>, password: <code>Admin@123</code></li>
                </ol>";
            }
            
            echo "<p style='margin-top: 20px;'>
                <a href='/' class='btn'>Go to Website</a>
                <a href='/admin' class='btn' style='background: #7FB285;'>Test Admin Login</a>
            </p>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='card' style='background: #f8d7da;'>";
            echo "<h2>❌ Database Connection Failed</h2>";
            echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            
            echo "<h3>Troubleshooting Steps:</h3>";
            echo "<ol>
                <li>Check if MySQL is running in XAMPP Control Panel</li>
                <li>Verify database credentials in <code>.env</code> file</li>
                <li>Create database manually in phpMyAdmin:
                    <pre>CREATE DATABASE fctcns_main CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</pre>
                </li>
                <li>Check XAMPP error logs: <code>C:/xampp/mysql/data/*.err</code></li>
            </ol>";
            
            echo "<p><strong>Current .env Database Settings:</strong></p>";
            echo "<pre>";
            echo "DB_HOST = " . ($_ENV['DB_HOST'] ?? 'not set') . "\n";
            echo "DB_PORT = " . ($_ENV['DB_PORT'] ?? 'not set') . "\n";
            echo "DB_DATABASE = " . ($_ENV['DB_DATABASE'] ?? 'not set') . "\n";
            echo "DB_USERNAME = " . ($_ENV['DB_USERNAME'] ?? 'not set') . "\n";
            echo "DB_PASSWORD = " . (str_repeat('*', strlen($_ENV['DB_PASSWORD'] ?? ''))) . "\n";
            echo "</pre>";
            
            echo "<p><a href='/database/install.php' class='btn'>Try Installation Script</a></p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>