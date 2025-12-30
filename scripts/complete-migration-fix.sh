#!/bin/bash
# complete-migration-fix.sh - Complete migration fix
cd /c/xampp/htdocs/fctcns-website || exit 1

echo "🚀 Completing Migration Fix"
echo "=========================="

echo "1. Ensuring all view symlinks are correct..."
cd app/views || exit 1

# Make sure view.php points to show.php for ApplicationController compatibility
if [ -f "admin/applications/show.php" ] && [ -f "admin/applications/view.php" ]; then
    # Check if view.php is a regular file (placeholder)
    if [ ! -L "admin/applications/view.php" ]; then
        echo "   Converting view.php to symlink..."
        rm admin/applications/view.php
        ln -s show.php admin/applications/view.php
        echo "   ✅ admin/applications/view.php → show.php"
    else
        echo "   ✅ view.php is already a symlink"
    fi
fi

# Make sure edit.php has content
if [ -f "admin/applications/edit.php" ] && [ ! -s "admin/applications/edit.php" ]; then
    echo "   Copying create.php to edit.php (forms are similar)..."
    cp admin/applications/create.php admin/applications/edit.php
    echo "   ✅ Filled edit.php with create form"
fi

echo ""
echo "2. Creating essential missing admin views..."
# Create admin/error.php if it doesn't exist
if [ ! -f "admin/error.php" ]; then
    cat > admin/error.php << 'EOF'
<?php
/**
 * Admin Error Page
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error - Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; text-align: center; }
        .error { background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="error">
        <h1>Error</h1>
        <p><?php echo htmlspecialchars($error ?? 'An error occurred'); ?></p>
        <p><a href="<?php echo $baseUrl ?? ''; ?>/admin/dashboard">Return to Dashboard</a></p>
    </div>
</body>
</html>
EOF
    echo "   ✅ Created admin/error.php"
fi

echo ""
echo "3. Final structure check:"
echo "   Applications: $(find admin/applications -name "*.php" | wc -l) files"
echo "   News: $(find admin/news -name "*.php" | wc -l) files"
echo "   Research: $(find admin/research -name "*.php" | wc -l) files"
echo "   Users: $(find admin/users -name "*.php" | wc -l) files"
echo "   Pages: $(find pages -name "*.php" | wc -l) files"

echo ""
echo "🎉 Migration fix completed!"
echo ""
echo "📋 Final testing instructions:"
echo "1. Test http://localhost/fctcns-website/admin/applications"
echo "2. Test http://localhost/fctcns-website/admin/applications/create"
echo "3. Test http://localhost/fctcns-website/admin/applications/1 (view single)"
echo "4. If #3 fails with 'view not found', check if it's looking for 'view' or 'show'"
echo ""
echo "If view.php/show.php issue persists, we may need to update the controller."