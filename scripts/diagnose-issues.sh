#!/bin/bash
# diagnose-issues.sh - Diagnose any remaining issues
cd /c/xampp/htdocs/fctcns-website || exit 1

echo "🔍 Diagnosing Issues"
echo "==================="

echo "1. Checking Apache error log for clues..."
echo "   Run this in another terminal:"
echo "   tail -f /c/xampp/apache/logs/error_log"
echo "   Then visit the failing URL"
echo ""

echo "2. Checking view file permissions..."
find app/views -name "*.php" -exec ls -la {} \; | head -20

echo ""
echo "3. Checking symlink health..."
if [ -L "app/views/admin/applications/view.php" ]; then
    echo "✅ view.php is a symlink"
    echo "   Target: $(readlink app/views/admin/applications/view.php)"
    if [ -e "app/views/admin/applications/view.php" ]; then
        echo "✅ Symlink is valid (target exists)"
    else
        echo "❌ Symlink is broken (target missing)"
    fi
else
    echo "❌ view.php is not a symlink"
fi

echo ""
echo "4. Quick controller check..."
echo "   ApplicationController render calls:"
grep -n "render.*admin/applications" app/controllers/ApplicationController.php | head -5
echo ""
echo "   ApplicationsController render calls:"
grep -n "render.*admin/applications" app/controllers/ApplicationsController.php | head -5

echo ""
echo "📋 Next steps based on findings:"
echo "• If symlink is broken: Recreate it"
echo "• If wrong view path: Update controller"
echo "• If permission issues: Fix permissions"
echo "• If still stuck: Share the exact error message"