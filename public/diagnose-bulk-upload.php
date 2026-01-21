<?php
/**
 * Bulk Upload Diagnostic Tool
 * Corrected for your shared hosting structure - FIXED VERSION
 */

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Determine correct paths for your shared hosting
$rootPath = dirname(__FILE__); // Should be /home2/fctcnsed/public_html
$appPath = dirname($rootPath) . '/fctcns-app'; // Should be /home2/fctcnsed/fctcns-app

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

// Helper function to compare sizes (K, M, G)
function compareSize($current, $min) {
    // Convert to bytes
    $units = ['K' => 1024, 'M' => 1048576, 'G' => 1073741824];
    
    // Parse current value
    $currentNum = floatval($current);
    $currentUnit = strtoupper(substr($current, -1));
    $currentBytes = $currentNum * ($units[$currentUnit] ?? 1);
    
    // Parse min value
    $minNum = floatval($min);
    $minUnit = strtoupper(substr($min, -1));
    $minBytes = $minNum * ($units[$minUnit] ?? 1);
    
    return $currentBytes >= $minBytes;
}

// ========== 0. PATH DETECTION ==========
echo "<div class='section info'>
        <h2>0. Path Detection</h2>";

echo "<p><strong>Script Location:</strong> " . __FILE__ . "</p>";
echo "<p><strong>Root Path:</strong> " . $rootPath . "</p>";
echo "<p><strong>App Path:</strong> " . $appPath . "</p>";

// Check if app directory exists
if (file_exists($appPath)) {
    echo "<p class='success'>✅ App directory exists</p>";
    
    // List important directories
    $dirs = [
        $appPath . '/app/core/' => 'Core Directory',
        $appPath . '/storage/uploads/' => 'Storage Directory',
        $appPath . '/storage/logs/' => 'Logs Directory',
    ];
    
    foreach ($dirs as $path => $name) {
        $exists = file_exists($path);
        echo "<p>" . ($exists ? "✅" : "❌") . " $name: " . $path . "</p>";
    }
} else {
    echo "<p class='error'>❌ App directory not found at: " . $appPath . "</p>";
}

echo "</div>";

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
    $status = compareSize($current, $min) ? '✅ OK' : '⚠️ LOW';
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

// Check important directories
$dirChecks = [
    $appPath . '/storage/uploads/nominal-roll/' => 'Upload Directory',
    $appPath . '/storage/uploads/passports/' => 'Passport Photos',
    $appPath . '/storage/logs/' => 'Logs Directory',
    $appPath . '/app/core/' => 'Core Directory',
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

// ========== 4. CSV PROCESSING TEST ==========
echo "<div class='section info'>
        <h2>4. CSV Processing Test</h2>";

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

// ========== 5. BULK UPLOAD SPECIFIC CHECKS ==========
echo "<div class='section info'>
        <h2>5. Bulk Upload Specific Checks</h2>";

// Check if NominalRollController exists
$controllerPath = $appPath . '/app/controllers/NominalRollController.php';
if (file_exists($controllerPath)) {
    echo "<p class='success'>✅ NominalRollController found</p>";
    
    // Check file size
    $controllerSize = filesize($controllerPath);
    echo "<p>Controller file size: " . $controllerSize . " bytes</p>";
    
    // Check if processBulkUpload method exists
    $content = file_get_contents($controllerPath);
    if (strpos($content, 'processBulkUpload') !== false) {
        echo "<p class='success'>✅ processBulkUpload() method exists</p>";
    } else {
        echo "<p class='error'>❌ processBulkUpload() method not found</p>";
    }
} else {
    echo "<p class='error'>❌ NominalRollController not found at: " . $controllerPath . "</p>";
}

echo "</div>";

// ========== 6. RECOMMENDATIONS ==========
echo "<div class='section'>
        <h2>6. Recommendations & Next Steps</h2>
        <h3>Issues Found:</h3>
        <ul>";

// Generate issue list
$issues = [];

// Check PHP limits
foreach ($phpChecks as $key => $check) {
    if (!compareSize($check['current'], $check['min'])) {
        $issues[] = "<li><strong>$key</strong> is too low: {$check['current']} (should be {$check['min']}+)</li>";
    }
}

// Check missing functions
foreach ($functionChecks as $func => $purpose) {
    if (!function_exists($func)) {
        $issues[] = "<li><strong>$func()</strong> is not available (required for: $purpose)</li>";
    }
}

// Check directories
foreach ($dirChecks as $path => $name) {
    if (!file_exists($path)) {
        $issues[] = "<li><strong>$name</strong> does not exist: $path</li>";
    } elseif (!is_writable($path)) {
        $issues[] = "<li><strong>$name</strong> is not writable: $path</li>";
    }
}

if (empty($issues)) {
    echo "<li class='success'>✅ No critical issues found!</li>";
} else {
    foreach ($issues as $issue) {
        echo $issue;
    }
}

echo "</ul>
        <h3>Action Items:</h3>
        <ol>
            <li><strong>Fix any ❌ errors above</strong> before proceeding</li>
            <li><strong>Update directory permissions:</strong><br>
                SSH command: <code>chmod -R 755 " . $appPath . "/storage/</code></li>
            <li><strong>Increase PHP limits</strong> in .htaccess:<br>
                <pre>
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value max_execution_time 300
                </pre>
            </li>
            <li><strong>Test bulk upload</strong> with a small CSV file (5 records)</li>
            <li><strong>Check error logs</strong> after upload attempt: " . $appPath . "/storage/logs/</li>
        </ol>
    </div>";

echo "</body></html>";
?>