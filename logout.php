<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: http://localhost/fctcns-website/admin');
exit;
?>
