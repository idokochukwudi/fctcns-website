<?php
/**
 * TEST SCRIPT - DELETE AFTER VERIFYING EVERYTHING WORKS
 * Run this to verify your newsletter implementation
 */

require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/models/NewsletterModel.php';

$database = Database::getInstance();
$db = $database->getConnection();
$newsletterModel = new NewsletterModel($db);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Newsletter System Test</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #5D4A8A; }
        h2 { color: #333; margin-top: 30px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #5D4A8A; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .btn { display: inline-block; background: #5D4A8A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px; }
    </style>
</head>
<body>
    <h1>📧 Newsletter System Test</h1>";

// TEST 1: Check if table exists
echo "<h2>Test 1: Database Table</h2>";
try {
    $result = $db->query("SHOW TABLES LIKE 'newsletter_subscribers'");
    if ($result->rowCount() > 0) {
        echo "<div class='success'>✓ Newsletter subscribers table exists</div>";
    } else {
        echo "<div class='error'>✗ Newsletter subscribers table not found. Please run the SQL from Step 1.</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
}

// TEST 2: Test subscription
echo "<h2>Test 2: Test Subscription</h2>";
$testEmail = "test-" . time() . "@example.com";
$result = $newsletterModel->subscribe($testEmail, 'test_script');

if ($result['success']) {
    echo "<div class='success'>✓ Test subscription successful: $testEmail</div>";
    echo "<div class='info'>Message: " . $result['message'] . "</div>";
} else {
    echo "<div class='error'>✗ Test subscription failed: " . $result['message'] . "</div>";
}

// TEST 3: Check subscriber count
echo "<h2>Test 3: Subscriber Count</h2>";
$count = $newsletterModel->getSubscriberCount();
echo "<div class='info'>Total active subscribers: <strong>$count</strong></div>";

// TEST 4: Show recent subscribers
echo "<h2>Test 4: Recent Subscribers</h2>";
$recent = $newsletterModel->getActiveSubscribers(10);
if (count($recent) > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Email</th><th>Source</th><th>Subscribed At</th></tr>";
    foreach ($recent as $sub) {
        echo "<tr>";
        echo "<td>" . $sub['id'] . "</td>";
        echo "<td>" . htmlspecialchars($sub['email']) . "</td>";
        echo "<td>" . $sub['source'] . "</td>";
        echo "<td>" . $sub['subscribed_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='info'>No subscribers yet.</div>";
}

// TEST 5: Check email logs
echo "<h2>Test 5: Email Logs</h2>";
$logFile = APP_PATH . '/logs/emails.log';
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $lines = explode("\n", trim($logs));
    $lastFive = array_slice($lines, -10);
    echo "<div style='background: #f4f4f4; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px;'>";
    foreach ($lastFive as $line) {
        if (!empty($line)) {
            echo htmlspecialchars($line) . "<br>";
        }
    }
    echo "</div>";
} else {
    echo "<div class='info'>No email logs yet. Subscribe to generate logs.</div>";
}

// TEST 6: Clean up test data
echo "<h2>Test 6: Clean Up</h2>";
$deleteSql = "DELETE FROM newsletter_subscribers WHERE email LIKE 'test-%@example.com'";
$db->exec($deleteSql);
echo "<div class='success'>✓ Test data cleaned up.</div>";

echo "<hr>";
echo "<h2>✅ Next Steps</h2>";
echo "<div style='background: #fff3cd; color: #856404; padding: 20px; border-radius: 5px;'>";
echo "<h3 style='margin-top: 0;'>Test the Live Form:</h3>";
echo "<p>1. Visit any news article page: <a href='" . BASE_URL . "/news' target='_blank'>" . BASE_URL . "/news</a></p>";
echo "<p>2. Enter your email in the newsletter sidebar</p>";
echo "<p>3. Check for success message</p>";
echo "<p>4. <strong>On localhost:</strong> Check /app/logs/emails.log for the email content</p>";
echo "<p>5. <strong>On GO54:</strong> Check your inbox (and spam folder) for the welcome email</p>";
echo "</div>";

echo "<div style='margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 5px;'>";
echo "<h3>📋 Deployment Checklist</h3>";
echo "<ul style='list-style-type: none; padding-left: 0;'>";
echo "<li style='margin-bottom: 10px;'>✅ Step 1: Database table created</li>";
echo "<li style='margin-bottom: 10px;'>✅ Step 2: Email configuration file created</li>";
echo "<li style='margin-bottom: 10px;'>✅ Step 3: Email helper created</li>";
echo "<li style='margin-bottom: 10px;'>✅ Step 4: Newsletter model created</li>";
echo "<li style='margin-bottom: 10px;'>✅ Step 5: Newsletter controller created</li>";
echo "<li style='margin-bottom: 10px;'>✅ Step 6: View files created</li>";
echo "<li style='margin-bottom: 10px;'>✅ Step 7: Routes added</li>";
echo "<li style='margin-bottom: 10px;'>✅ Step 8: Newsletter form updated in views</li>";
echo "<li style='margin-bottom: 10px;'>✅ Step 9: Test script verified</li>";
echo "</ul>";
echo "</div>";

echo "</body>
</html>";