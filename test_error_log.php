<?php
// Quick test script to check error log location
echo "Error log path: " . ini_get('error_log') . "<br>";
echo "Display errors: " . ini_get('display_errors') . "<br>";
echo "Error reporting: " . error_reporting() . "<br>";

// Test error logging
error_log("🔴 TEST: This is a test error log message");
echo "Test error logged. Check your error log file.<br>";