<?php
// Simulate going to edit page
$_SERVER['REQUEST_URI'] = '/admin/research/1/edit';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once 'app/config/constants.php';
require_once 'app/core/Router.php';

$router = new Router();
$match = $router->match();

echo "<h1>Router Test</h1>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Matched: " . ($match ? 'YES' : 'NO') . "<br>";

if ($match) {
    echo "Handler: " . $match['handler'] . "<br>";
    echo "Params: " . print_r($match['params'], true) . "<br>";
    
    // Try to call it
    $router->dispatch($match);
}