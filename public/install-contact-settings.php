<?php
/**
 * One-time installation script for contact reply settings
 * 
 * Run this script once to set up the database tables and settings
 * Access via: http://yourdomain.com/install-contact-settings.php
 */

// Define base paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Load configuration
require_once APP_PATH . '/config/constants.php';
require_once APP_PATH . '/config/database.php';

// Only allow in development mode or with admin authentication
$isDev = ($_ENV['APP_ENV'] ?? 'development') === 'development';
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

if (!$isDev && !$isAdmin) {
    die("Access denied. This script can only be run in development mode or by an admin.");
}

// Load migration class
require_once APP_PATH . '/core/Migration.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Install Contact Settings</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e2e; color: #d4d4d4; }
        .container { max-width: 800px; margin: 0 auto; }
        .success { color: #98c379; }
        .error { color: #e06c75; }
        .info { color: #61afef; }
        pre { background: #282c34; padding: 15px; border-radius: 5px; }
        h1 { color: #61afef; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>📧 Contact Reply Settings Installation</h1>";

try {
    echo "<h2>Step 1: Running Migration...</h2>";
    echo "<pre>";
    
    $migration = new Migration();
    $migration->createMigrationsTable();
    
    // Include and run the migration
    require_once APP_PATH . '/database/migrations/2024_01_01_000001_add_reply_email_settings.php';
    $migrationFile = new AddReplyEmailSettingsMigration();
    
    $connection = Database::getInstance()->getConnection();
    $migrationFile->up($connection);
    
    // Record the migration
    $migration->addMigration('2024_01_01_000001_add_reply_email_settings', 1);
    
    echo "</pre>";
    echo "<p class='success'>✓ Migration completed successfully!</p>";
    
    echo "<h2>Step 2: Verifying Settings...</h2>";
    echo "<pre>";
    
    // Verify settings were added
    $stmt = $connection->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ('reply_to_email', 'support_email', 'billing_email', 'admissions_email')");
    $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($settings) > 0) {
        echo "Current settings:\n";
        foreach ($settings as $setting) {
            echo "  • {$setting['setting_key']} = {$setting['setting_value']}\n";
        }
        echo "\n✓ Settings verified!\n";
    } else {
        echo "⚠ No settings found. Please check the migration.\n";
    }
    
    echo "</pre>";
    
    echo "<h2>✅ Installation Complete!</h2>";
    echo "<p>You can now:</p>";
    echo "<ul style='color: #d4d4d4;'>";
    echo "<li>Go to <a href='" . BASE_URL . "/admin/contact/settings' style='color: #98c379;'>Contact Settings</a> to configure your reply-to emails</li>";
    echo "<li>View <a href='" . BASE_URL . "/admin/contact' style='color: #98c379;'>Contact Submissions</a> and test the Reply button</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<pre class='error'>";
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    echo "</pre>";
}

echo "</div></body></html>";