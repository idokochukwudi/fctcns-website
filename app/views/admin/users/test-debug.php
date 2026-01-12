<?php
// app/views/admin/users/test-debug.php
echo '<h1>Debug: Testing User Management</h1>';
echo '<p>If you see this, PHP is working.</p>';

// Check if controller is accessible
$controllerPath = __DIR__ . '/../../controllers/UserManagementController.php';
echo '<p>Controller exists: ' . (file_exists($controllerPath) ? 'YES' : 'NO') . '</p>';

// Check session
session_start();
echo '<p>User role in session: ' . ($_SESSION['user_role'] ?? 'NOT SET') . '</p>';

// Check if user is admin
if (($_SESSION['user_role'] ?? '') !== 'admin') {
    echo '<p style="color: red;">ERROR: User is not admin. You need admin role to access user management.</p>';
} else {
    echo '<p style="color: green;">User has admin role - should be able to access.</p>';
}