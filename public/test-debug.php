<?php
// Test if error logging works
error_log("=== TEST DEBUG MESSAGE ===");
error_log("Testing if error logging works");

// Also output to screen
echo "<h1>Debug Test</h1>";
echo "<p>Check C:\\xampp\\apache\\logs\\error.log for debug messages</p>";
echo "<p>Also check C:\\xampp\\php\\logs\\php_error.log if it exists</p>";

// Test a simple form
echo '<form method="POST">
    <select name="status">
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>
    <button type="submit">Test</button>
</form>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST received!");
    error_log("Status from POST: " . ($_POST['status'] ?? 'NOT SET'));
    echo "<p>Status submitted: " . htmlspecialchars($_POST['status'] ?? 'None') . "</p>";
}
?>