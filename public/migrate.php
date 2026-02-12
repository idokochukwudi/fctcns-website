<?php
/**
 * Migration Runner Script
 * Access via: http://yourdomain.com/migrate.php
 * 
 * Usage:
 * - http://yourdomain.com/migrate.php - Show help
 * - http://yourdomain.com/migrate.php?action=migrate - Run pending migrations
 * - http://yourdomain.com/migrate.php?action=rollback - Rollback last batch
 * - http://yourdomain.com/migrate.php?action=fresh - Fresh migrate (rollback + migrate)
 * - http://yourdomain.com/migrate.php?action=create&name=create_users_table - Create new migration
 */

// Define base paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Load configuration
require_once APP_PATH . '/config/constants.php';
require_once APP_PATH . '/config/database.php';

// Only allow in development mode
if (($_ENV['APP_ENV'] ?? 'development') !== 'development') {
    die("Migrations can only be run in development mode.");
}

require_once APP_PATH . '/core/Migration.php';

$migration = new Migration();
$action = $_GET['action'] ?? 'help';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Migration</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e2e; color: #d4d4d4; }
        .container { max-width: 800px; margin: 0 auto; }
        .success { color: #98c379; }
        .error { color: #e06c75; }
        .info { color: #61afef; }
        pre { background: #282c34; padding: 15px; border-radius: 5px; color: #abb2bf; }
        h1 { color: #61afef; }
        hr { border-color: #3e4452; }
        a { color: #98c379; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .menu { margin-bottom: 20px; }
        .menu a { margin-right: 15px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Database Migration Tool</h1>
        <div class='menu'>
            <a href='?action=migrate'>▶️ Migrate</a>
            <a href='?action=rollback'>⬅️ Rollback</a>
            <a href='?action=fresh'>🔄 Fresh</a>
            <a href='?action=status'>📊 Status</a>
            <a href='?action=help'>❓ Help</a>
        </div>
        <hr>";

switch ($action) {
    case 'migrate':
        echo "<h2>Running Migrations...</h2><pre>";
        $migration->migrate();
        echo "</pre>";
        break;
        
    case 'rollback':
        echo "<h2>Rolling Back Migrations...</h2><pre>";
        $migration->rollback();
        echo "</pre>";
        break;
        
    case 'fresh':
        echo "<h2>Fresh Migrations...</h2><pre>";
        $migration->fresh();
        echo "</pre>";
        break;
        
    case 'create':
        $name = $_GET['name'] ?? '';
        if ($name) {
            echo "<h2>Creating Migration: $name</h2><pre>";
            $migration->create($name);
            echo "</pre>";
        } else {
            echo "<h2 class='error'>Error: Migration name is required</h2>";
            echo "<p>Usage: migrate.php?action=create&name=your_migration_name</p>";
        }
        break;
        
    case 'status':
        echo "<h2>Migration Status</h2><pre>";
        $migration->createMigrationsTable();
        $executed = $migration->getExecutedMigrations();
        $batch = $migration->getCurrentBatch();
        
        echo "Current Batch: " . ($batch ?: 0) . "\n\n";
        echo "Executed Migrations:\n";
        if (empty($executed)) {
            echo "  No migrations executed yet.\n";
        } else {
            foreach ($executed as $m) {
                echo "  • $m\n";
            }
        }
        echo "</pre>";
        break;
        
    case 'help':
    default:
        echo "<h2>Migration Commands</h2>
        <pre>
Available Commands:
  migrate.php?action=migrate              Run all pending migrations
  migrate.php?action=rollback             Rollback the last batch of migrations
  migrate.php?action=fresh               Drop all tables and re-run all migrations
  migrate.php?action=status             Show the status of each migration
  migrate.php?action=create&name=NAME   Create a new migration file

Examples:
  <a href='?action=create&name=add_reply_email_settings'>Create reply email settings migration</a>
  <a href='?action=migrate'>Run all migrations</a>
  <a href='?action=status'>Check migration status</a>
        </pre>";
        break;
}

echo "</div></body></html>";