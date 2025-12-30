<?php
// Simple test to check routing
$_SERVER['REQUEST_URI'] = '/fctcns-website/about';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once 'public/index.php';
?>