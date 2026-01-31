<?php
// Test pagination and filtering
session_start();

// Simulate controller behavior
$filters = [
    'search' => $_GET['search'] ?? '',
    'state' => $_GET['state'] ?? '',
    'page' => $_GET['page'] ?? 1,
    'limit' => $_GET['limit'] ?? 10
];

// Build test URLs
function buildTestUrl($page, $filters) {
    $params = $filters;
    $params['page'] = $page;
    return '/admin/nominal-roll?' . http_build_query($params);
}

echo "<h1>Pagination Test Results</h1>";
echo "<p>Current Filters: " . json_encode($filters) . "</p>";

// Test links
echo "<h3>Test Pagination Links:</h3>";
echo "<ul>";
for ($i = 1; $i <= 5; $i++) {
    $url = buildTestUrl($i, $filters);
    echo "<li><a href='$url'>Page $i</a></li>";
}
echo "</ul>";

// Show current URL
echo "<p>Current URL: " . $_SERVER['REQUEST_URI'] . "</p>";
?>