<?php
// Test router directly
$_SERVER['REQUEST_URI'] = '/fctcns-website/about';
$_SERVER['REQUEST_METHOD'] = 'GET';

// Include your router setup
require_once '../app/core/Router.php';

$router = new Router();
$router->get('/about', function() {
    echo "About route works!";
});

$match = $router->match();
if ($match) {
    echo "Route matched!";
    $router->dispatch($match);
} else {
    echo "No route matched";
    echo "<pre>";
    print_r($router->getRoutes());
    echo "</pre>";
}
?>