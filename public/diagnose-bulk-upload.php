<?php
/**
 * Bulk Upload Diagnostic Tool
 * Place this file in your project root and access via browser
 */

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Bulk Upload Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border-color: #c3e6cb; }
        .warning { background: #fff3cd; color: #856404; border-color: #ffeaa7; }
        .error { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
        pre { background: #f8f9fa; padding: 10px; border: 1px solid #e9ecef; }
    </style>
</head>
<body>
    <h1>Bulk Upload Diagnostic Tool</h1>";

// Load environment
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/app/core/Database.php';

// ========== 1. PHP CONFIGURATION ==========
echo "<div class='section info'>
        <h2>1. PHP Configuration</h2>
        <table border='1' cellpadding='5'>
            <tr><th>Setting</th><th>Value</th><th>Status</th></tr>";

$phpChecks = [
    'upload_max_filesize' => ['min' => '10M', 'current' => ini_get('upload_max_filesize')],
    'post_max_size' => ['min' => '10M', 'current' => ini_get('post_max_size')],
    'max_execution_time' => ['min' => '300', 'current' => ini_get('max_execution_time')],
    'max_input_time' => ['min' => '300', 'current' => ini_get('max_input_time')],
    'memory_limit' => ['min' => '256M', 'current' => ini_get('memory_limit')],
];

foreach ($phpChecks as $key => $check) {
    $current = $check['current'];
    $min = $check['min'];
    $status = $this->compareSize($current, $min) ? '✅ OK' : '⚠️ LOW';
    $class = $status === '✅ OK' ? 'success' : 'warning';
    
    echo "<tr class='$class'>
            <td>$key</td>
            <td>$current</td>
            <td>$status (Min: $min)</td>
          </tr>";
}

echo "</table></div>";

// ========== 2. REQUIRED FUNCTIONS ==========
echo "<div class='section info'>
        <h2>2. Required PHP Functions</h2>
        <table border='1' cellpadding='5'>
            <tr><th>Function</th><th>Status</th><th>Purpose</th></tr>";

$functionChecks = [
    'fgetcsv' => 'CSV Parsing',
    'move_uploaded_file' => 'File Upload',
    'finfo_open' => 'MIME Detection',
    'json_encode' => 'JSON Processing',
    'mysqli_connect' => 'Database',
    'session_start' => 'Sessions',
    'mb_detect_encoding' => 'Encoding Detection',
];

foreach ($functionChecks as $func => $purpose) {
    $exists = function_exists($func);
    $status = $exists ? '✅ Available' : '❌ Missing';
    $class = $exists ? 'success' : 'error';
    
    echo "<tr class='$class'>
            <td>$func()</td>
            <td>$status</td>
            <td>$purpose</td>
          </tr>";
}

echo "</table></div>";

// ========== 3. FILE PERMISSIONS ==========
echo "<div class='section info'>
        <h2>3. File System Permissions</h2>
        <table border='1' cellpadding='5'>
            <tr><th>Directory/File</th><th>Exists</th><th>Writable</th><th>Permissions</th></tr>";

$dirChecks = [
    ROOT_PATH . '/storage/uploads/nominal-roll/' => 'Upload Directory',
    ROOT_PATH . '/storage/uploads/passports/' => 'Passport Photos',
    ROOT_PATH . '/storage/logs/' => 'Logs Directory',
    ROOT_PATH . '/app/core/' => 'Core Directory',
];

foreach ($dirChecks as $path => $name) {
    $exists = file_exists($path);
    $writable = $exists ? is_writable($path) : false;
    $perms = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';
    
    $statusClass = ($exists && $writable) ? 'success' : ($exists ? 'warning' : 'error');
    
    echo "<tr class='$statusClass'>
            <td>$name<br><small>$path</small></td>
            <td>" . ($exists ? '✅ Yes' : '❌ No') . "</td>
            <td>" . ($writable ? '✅ Yes' : '❌ No') . "</td>
            <td>$perms</td>
          </tr>";
}

echo "</table></div>";

// ========== 4. DATABASE CONNECTION ==========
echo "<div class='section info'>
        <h2>4. Database Connection & Permissions</h2>";

try {
    $db = Database::getInstance();
    echo "<p class='success'>✅ Database connection successful</p>";
    
    // Test table exists
    $tables = ['nominal_roll_employees', 'nominal_roll_bulk_uploads', 'nominal_roll_activity_logs'];
    echo "<table border='1' cellpadding='5'>
            <tr><th>Table</th><th>Exists</th><th>Row Count</th></tr>";
    
    foreach ($tables as $table) {
        $result = $db->query("SHOW TABLES LIKE '$table'");
        $exists = $result->num_rows > 0;
        
        if ($exists) {
            $countResult = $db->query("SELECT COUNT(*) as count FROM $table");
            $count = $countResult->fetch_assoc()['count'];
            echo "<tr class='success'>
                    <td>$table</td>
                    <td>✅ Yes</td>
                    <td>$count rows</td>
                  </tr>";
        } else {
            echo "<tr class='error'>
                    <td>$table</td>
                    <td>❌ No</td>
                    <td>N/A</td>
                  </tr>";
        }
    }
    
    echo "</table>";
    
    // Test INSERT permission
    echo "<h3>INSERT Permission Test</h3>";
    $testData = [
        'employee_number' => 'DIAG_' . time(),
        'surname' => 'Diagnostic',
        'first_name' => 'Test',
        'sex' => 'Male',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $columns = implode(', ', array_keys($testData));
    $values = "'" . implode("', '", array_values($testData)) . "'";
    
    $insert = $db->query("INSERT INTO nominal_roll_employees ($columns) VALUES ($values)");
    
    if ($insert) {
        $id = $db->insert_id;
        echo "<p class='success'>✅ INSERT successful (ID: $id)</p>";
        
        // Clean up
        $db->query("DELETE FROM nominal_roll_employees WHERE id = $id");
        echo "<p class='success'>✅ Cleanup successful</p>";
    } else {
        echo "<p class='error'>❌ INSERT failed: " . $db->error . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "</div>";

// ========== 5. CSV PROCESSING TEST ==========
echo "<div class='section info'>
        <h2>5. CSV Processing Test</h2>";

$testCSV = "S/N,Employee Number,Surname,First Name,Sex,Date of Birth
1,TEST001,Doe,John,Male,1990-01-01
2,TEST002,Smith,Jane,Female,1992-02-02";

$tempFile = tempnam(sys_get_temp_dir(), 'csv_test_');
file_put_contents($tempFile, $testCSV);

echo "<h3>Test CSV Content:</h3>
      <pre>$testCSV</pre>";

// Test CSV parsing
if (($handle = fopen($tempFile, 'r')) !== false) {
    $headers = fgetcsv($handle);
    $rows = [];
    while (($row = fgetcsv($handle)) !== false) {
        $rows[] = $row;
    }
    fclose($handle);
    
    echo "<p class='success'>✅ CSV parsed successfully</p>";
    echo "<p>Headers found: " . implode(', ', $headers) . "</p>";
    echo "<p>Rows parsed: " . count($rows) . "</p>";
    
    // Display parsed data
    echo "<h4>Parsed Data:</h4>
          <table border='1' cellpadding='5'>
          <tr><th>S/N</th><th>Employee Number</th><th>Name</th><th>Sex</th><th>DOB</th></tr>";
    
    foreach ($rows as $row) {
        echo "<tr>
                <td>{$row[0]}</td>
                <td>{$row[1]}</td>
                <td>{$row[2]}, {$row[3]}</td>
                <td>{$row[4]}</td>
                <td>{$row[5]}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>❌ Failed to open CSV file</p>";
}

unlink($tempFile);
echo "</div>";

// ========== 6. RECOMMENDATIONS ==========
echo "<div class='section'>
        <h2>6. Recommendations & Next Steps</h2>
        <ol>
            <li><strong>If any ❌ errors appear above:</strong> Contact your hosting provider</li>
            <li><strong>If file permissions are wrong:</strong> Run: <code>chmod -R 755 storage/</code></li>
            <li><strong>If database tables missing:</strong> Run your database setup script</li>
            <li><strong>If PHP limits are too low:</strong> Add to .htaccess:<br>
                <pre>
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value max_execution_time 300
php_value max_input_time 300
                </pre>
            </li>
        </ol>
    </div>";

echo "</body></html>";

// Helper function
function compareSize($current, $min) {
    $units = ['K' => 1, 'M' => 1024, 'G' => 1048576];
    $currentVal = intval($current) * ($units[substr($current, -1)] ?? 1);
    $minVal = intval($min) * ($units[substr($min, -1)] ?? 1);
    return $currentVal >= $minVal;
}
?>