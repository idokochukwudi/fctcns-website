#!/bin/bash

echo "Ì¥ß Fixing Admin View Structure"
echo "=============================="

# Check what's in applications directory
echo "1. Checking app/views/admin/applications/ directory:"
ls -la app/views/admin/applications/ 2>/dev/null || echo "Directory doesn't exist or is empty"

echo ""
echo "2. Creating proper MVC view structure..."

# Create the directory if it doesn't exist
mkdir -p app/views/admin/applications/

# Check what files we have at root level
echo "Root level files:"
ls -la app/views/admin/applications_*.php 2>/dev/null | grep -v " 0 "

echo ""
echo "3. Creating/Moving view files..."

# Create index.php view (for applications list)
if [ ! -f "app/views/admin/applications/index.php" ]; then
    if [ -f "app/views/admin/applications.php" ]; then
        echo "Moving applications.php to applications/index.php"
        mv app/views/admin/applications.php app/views/admin/applications/index.php
    else
        echo "Creating applications/index.php (empty)"
        echo "<?php echo 'Applications index view'; ?>" > app/views/admin/applications/index.php
    fi
fi

# Create view.php (for single application)
if [ ! -f "app/views/admin/applications/view.php" ]; then
    if [ -f "app/views/admin/applications_view.php" ]; then
        echo "Moving applications_view.php to applications/view.php"
        mv app/views/admin/applications_view.php app/views/admin/applications/view.php
    else
        echo "Creating applications/view.php (empty)"
        echo "<?php echo 'Application view'; ?>" > app/views/admin/applications/view.php
    fi
fi

# Create create.php
if [ ! -f "app/views/admin/applications/create.php" ]; then
    if [ -f "app/views/admin/applications_create.php" ]; then
        echo "Moving applications_create.php to applications/create.php"
        mv app/views/admin/applications_create.php app/views/admin/applications/create.php
    else
        echo "Creating applications/create.php (empty)"
        echo "<?php echo 'Create application form'; ?>" > app/views/admin/applications/create.php
    fi
fi

# Create edit.php
if [ ! -f "app/views/admin/applications/edit.php" ]; then
    echo "Creating applications/edit.php (empty)"
    cat > app/views/admin/applications/edit.php << 'EDITPHP'
<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 3);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Application - FCT CNS Admin</title>
</head>
<body>
    <h1>Edit Application #<?php echo htmlspecialchars($application['id'] ?? ''); ?></h1>
    <p>Edit form for: <?php echo htmlspecialchars($application['first_name'] ?? '') . ' ' . htmlspecialchars($application['last_name'] ?? ''); ?></p>
    <a href="<?php echo BASE_URL; ?>/admin/applications">‚Üê Back to Applications</a>
</body>
</html>
EDITPHP
fi

echo ""
echo "‚úÖ View structure fixed!"
echo ""
echo "Ì≥ã Now test:"
echo "1. http://localhost/fctcns-website/admin/applications (should show list)"
echo "2. http://localhost/fctcns-website/admin/applications/view/1 (should show single)"
echo "3. http://localhost/fctcns-website/admin/applications/edit/1 (should show edit form)"
