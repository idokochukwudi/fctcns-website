<?php
$slipNumber = $_GET['slip'] ?? $_GET['slipNumber'] ?? 'Not provided';
echo "<h1>Test Verification Page</h1>";
echo "<p>Slip Number: " . htmlspecialchars($slipNumber) . "</p>";
echo "<p>This is a test verification page.</p>";
