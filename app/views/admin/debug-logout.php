<?php
// Debug Logout Script
session_start();

echo "<h1>Admin Logout Debug</h1>";

echo "<h3>Current Session:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'Not defined') . "</h3>";

echo "<h3>Test Links:</h3>";
echo "<p><a href='/admin/logout'>/admin/logout (via router)</a></p>";
echo "<p><a href='" . (defined('BASE_URL') ? BASE_URL : '') . "/admin/logout'>Full URL logout</a></p>";

echo "<h3>Manual Logout Test:</h3>";
echo '<form method="POST">
        <button type="submit" name="manual_logout">Manual Session Destroy</button>
      </form>';

if (isset($_POST['manual_logout'])) {
    $_SESSION = [];
    session_destroy();
    echo "<p>Session destroyed. <a href='/admin'>Go to login</a></p>";
}
?>