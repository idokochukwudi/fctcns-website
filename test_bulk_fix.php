<?php
// test_bulk_fix.php
require_once __DIR__ . '/config/database.php';

echo "<h2>Testing Bulk Upload Fix</h2>";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // 1. Check table structure
    echo "<h3>1. Table Structure Check</h3>";
    $structure = $conn->query("DESCRIBE `nominal_roll_bulk_uploads`")->fetchAll(PDO::FETCH_ASSOC);
    
    $hasSkippedImports = false;
    foreach ($structure as $col) {
        $status = $col['Field'] === 'skipped_imports' ? "✅" : "";
        if ($col['Field'] === 'skipped_imports') $hasSkippedImports = true;
        echo "<p>{$status} {$col['Field']} ({$col['Type']}) - Default: {$col['Default']}</p>";
    }
    
    if (!$hasSkippedImports) {
        echo "<p style='color: red;'>❌ MISSING: skipped_imports column</p>";
    } else {
        echo "<p style='color: green;'>✅ skipped_imports column exists</p>";
    }
    
    // 2. Test insert
    echo "<h3>2. Test Database Insert</h3>";
    try {
        $testData = [
            'filename' => 'test_fix.csv',
            'import_type' => 'create',
            'total_rows' => 5,
            'uploaded_by' => 1,
            'status' => 'processing'
        ];
        
        // Create test record
        $sql = "INSERT INTO `nominal_roll_bulk_uploads` (filename, import_type, total_rows, uploaded_by, status) 
                VALUES (:filename, :import_type, :total_rows, :uploaded_by, :status)";
        $stmt = $conn->prepare($sql);
        $stmt->execute($testData);
        $testId = $conn->lastInsertId();
        
        echo "<p style='color: green;'>✅ Test record created (ID: $testId)</p>";
        
        // 3. Test update with all fields
        echo "<h3>3. Test Update with All Fields</h3>";
        $updateData = [
            'id' => $testId,
            'successful_imports' => 3,
            'failed_imports' => 1,
            'skipped_imports' => 1,
            'error_log' => json_encode(['Test error 1', 'Test error 2']),
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        $updateFields = [];
        $updateParams = [':id' => $testId];
        foreach ($updateData as $key => $value) {
            if ($key !== 'id') {
                $updateFields[] = "`$key` = :$key";
                $updateParams[":$key"] = $value;
            }
        }
        
        $updateSql = "UPDATE `nominal_roll_bulk_uploads` SET " . implode(', ', $updateFields) . " WHERE `id` = :id";
        $updateStmt = $conn->prepare($updateSql);
        $updateResult = $updateStmt->execute($updateParams);
        
        if ($updateResult) {
            echo "<p style='color: green;'>✅ Test update successful!</p>";
            
            // Verify the update
            $verify = $conn->query("SELECT * FROM `nominal_roll_bulk_uploads` WHERE id = $testId")->fetch(PDO::FETCH_ASSOC);
            echo "<p>Verified data:</p>";
            echo "<pre>" . print_r($verify, true) . "</pre>";
        } else {
            echo "<p style='color: red;'>❌ Test update failed</p>";
            $error = $updateStmt->errorInfo();
            echo "<p>Error: " . print_r($error, true) . "</p>";
        }
        
        // Cleanup
        $conn->exec("DELETE FROM `nominal_roll_bulk_uploads` WHERE id = $testId");
        echo "<p>✅ Test cleanup completed</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Test failed: " . $e->getMessage() . "</p>";
    }
    
    echo "<h3 style='color: green;'>✅ All tests completed!</h3>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Setup failed: " . $e->getMessage() . "</h3>";
}
?>