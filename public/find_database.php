<?php
// find_database.php - Save in public folder
echo "<h1>Finding database.php</h1>";

// Define ROOT_PATH manually
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

echo "ROOT_PATH: " . ROOT_PATH . "<br>";
echo "APP_PATH: " . APP_PATH . "<br><br>";

// Search for database.php
function searchFile($dir, $filename) {
    $results = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && strtolower($file->getFilename()) === strtolower($filename)) {
            $results[] = $file->getPathname();
        }
    }
    return $results;
}

echo "<h3>Searching for database.php in " . ROOT_PATH . ":</h3>";
$found = searchFile(ROOT_PATH, 'database.php');

if (empty($found)) {
    echo "NOT FOUND<br>";
    echo "Searching for Database.php (with capital D):<br>";
    $found = searchFile(ROOT_PATH, 'Database.php');
}

if (!empty($found)) {
    echo "FOUND " . count($found) . " files:<br>";
    foreach ($found as $path) {
        echo "- " . $path . "<br>";
    }
} else {
    echo "No database.php or Database.php found!<br>";
}

// Also check common locations
echo "<h3>Common locations:</h3>";
$common = [
    ROOT_PATH . '/app/database.php',
    ROOT_PATH . '/app/Database.php',
    ROOT_PATH . '/app/core/database.php',
    ROOT_PATH . '/app/core/Database.php',
    ROOT_PATH . '/app/classes/database.php',
    ROOT_PATH . '/app/classes/Database.php',
    ROOT_PATH . '/app/config/database.php',
    ROOT_PATH . '/app/config/Database.php',
    ROOT_PATH . '/database.php',
    ROOT_PATH . '/Database.php',
];

foreach ($common as $path) {
    echo $path . " - " . (file_exists($path) ? 'EXISTS' : 'not found') . "<br>";
}
?>