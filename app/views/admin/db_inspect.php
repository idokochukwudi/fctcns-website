<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 3);
require_once $rootPath . '/app/config/constants.php';

// Require authentication
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

// Include database
require_once APP_PATH . '/config/database.php';
$db = Database::getInstance();
$conn = $db->getConnection();

// Only allow admin users
if ($_SESSION['user_role'] !== 'admin') {
    echo "Access denied. Admin privileges required.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Inspection - FCT CNS Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #2c5282;
            padding-bottom: 10px;
        }
        h2 {
            color: #2c5282;
            margin-top: 30px;
        }
        .btn {
            background: #2c5282;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #f4f4f4;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .danger {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .section {
            margin: 30px 0;
        }
        .sql-box {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            margin: 10px 0;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Inspection Tool</h1>
        <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn">Back to Dashboard</a>
        
        <?php
        try {
            // 1. Show all tables
            echo '<div class="section">';
            echo '<h2>📊 Database Tables</h2>';
            
            // MySQL query to get all tables
            $stmt = $conn->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (empty($tables)) {
                echo '<div class="warning">No tables found in the database.</div>';
            } else {
                echo '<table>';
                echo '<tr><th>Table Name</th><th>Row Count</th></tr>';
                
                foreach ($tables as $table) {
                    // Get row count for each table
                    $countStmt = $conn->query("SELECT COUNT(*) as count FROM `$table`");
                    $count = $countStmt->fetch()['count'];
                    
                    echo '<tr>';
                    echo '<td><strong>' . htmlspecialchars($table) . '</strong></td>';
                    echo '<td>' . number_format($count) . ' rows</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            echo '</div>';
            
            // 2. Show structure for each table
            foreach ($tables as $table) {
                echo '<div class="section">';
                echo '<h2>📋 Table: ' . htmlspecialchars($table) . '</h2>';
                
                // Get table structure
                $stmt = $conn->query("DESCRIBE `$table`");
                $structure = $stmt->fetchAll();
                
                if (!empty($structure)) {
                    echo '<table>';
                    echo '<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>';
                    
                    foreach ($structure as $column) {
                        echo '<tr>';
                        echo '<td><strong>' . htmlspecialchars($column['Field']) . '</strong></td>';
                        echo '<td>' . htmlspecialchars($column['Type']) . '</td>';
                        echo '<td>' . htmlspecialchars($column['Null']) . '</td>';
                        echo '<td>' . htmlspecialchars($column['Key']) . '</td>';
                        echo '<td>' . htmlspecialchars($column['Default'] ?? 'NULL') . '</td>';
                        echo '<td>' . htmlspecialchars($column['Extra']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
                
                // Show sample data (first 5 rows)
                echo '<h3>Sample Data (First 5 rows)</h3>';
                $sampleStmt = $conn->query("SELECT * FROM `$table` LIMIT 5");
                $sampleData = $sampleStmt->fetchAll();
                
                if (!empty($sampleData)) {
                    echo '<table>';
                    // Header
                    echo '<tr>';
                    foreach (array_keys($sampleData[0]) as $key) {
                        if (!is_numeric($key)) {
                            echo '<th>' . htmlspecialchars($key) . '</th>';
                        }
                    }
                    echo '</tr>';
                    
                    // Data rows
                    foreach ($sampleData as $row) {
                        echo '<tr>';
                        foreach ($row as $key => $value) {
                            if (!is_numeric($key)) {
                                echo '<td>';
                                if ($value === null) {
                                    echo '<em>NULL</em>';
                                } else {
                                    // Truncate long values
                                    $displayValue = htmlspecialchars($value);
                                    if (strlen($displayValue) > 50) {
                                        $displayValue = substr($displayValue, 0, 50) . '...';
                                    }
                                    echo $displayValue;
                                }
                                echo '</td>';
                            }
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<div class="warning">No data in this table.</div>';
                }
                
                echo '</div>';
            }
            
            // 3. Show database info
            echo '<div class="section">';
            echo '<h2>🔧 Database Information</h2>';
            
            // Get database name
            $dbStmt = $conn->query("SELECT DATABASE() as db_name");
            $dbInfo = $dbStmt->fetch();
            
            // Get MySQL version
            $versionStmt = $conn->query("SELECT VERSION() as version");
            $versionInfo = $versionStmt->fetch();
            
            echo '<div class="sql-box">';
            echo 'Database: <strong>' . htmlspecialchars($dbInfo['db_name']) . '</strong><br>';
            echo 'MySQL Version: <strong>' . htmlspecialchars($versionInfo['version']) . '</strong><br>';
            echo 'Total Tables: <strong>' . count($tables) . '</strong>';
            echo '</div>';
            echo '</div>';
            
            // 4. Create missing tables (if needed)
            echo '<div class="section">';
            echo '<h2>🔨 Common Missing Tables</h2>';
            
            $commonTables = ['users', 'applications', 'news', 'research_publications', 'activity_logs'];
            $missingTables = array_diff($commonTables, $tables);
            
            if (!empty($missingTables)) {
                echo '<div class="warning">The following common tables are missing:</div>';
                echo '<ul>';
                foreach ($missingTables as $missingTable) {
                    echo '<li><strong>' . htmlspecialchars($missingTable) . '</strong></li>';
                }
                echo '</ul>';
                
                echo '<h3>Create Missing Tables</h3>';
                echo '<p>Click the button below to create missing tables:</p>';
                echo '<form method="POST" action="' . BASE_URL . '/admin/db/create-tables" style="margin: 20px 0;">';
                echo '<button type="submit" class="btn" style="background: #38a169;">Create Missing Tables</button>';
                echo '</form>';
            } else {
                echo '<div class="success">All common tables are present in the database.</div>';
            }
            echo '</div>';
            
        } catch (Exception $e) {
            echo '<div class="danger">';
            echo '<h3>Database Error</h3>';
            echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
            echo '</div>';
        }
        ?>
        
        <div class="section">
            <h2>📝 SQL Queries for Reference</h2>
            <p>Use these queries to check your database:</p>
            
            <div class="sql-box">
                -- Show all tables<br>
                SHOW TABLES;
            </div>
            
            <div class="sql-box">
                -- Show structure of a specific table<br>
                DESCRIBE table_name;
            </div>
            
            <div class="sql-box">
                -- Show all data from a table (limit to 10 rows)<br>
                SELECT * FROM table_name LIMIT 10;
            </div>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>