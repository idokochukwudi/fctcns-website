#!/bin/bash
# final-verification.sh - Final verification of migration
cd /c/xampp/htdocs/fctcns-website || exit 1

echo "🎯 Final Migration Verification"
echo "=============================="

echo "1. Checking view directory structure..."
if [ -d "app/views" ]; then
    echo "✅ Views directory exists"
    echo "   Total PHP files: $(find app/views -name "*.php" | wc -l)"
    
    echo ""
    echo "   Applications module:"
    echo "     - index.php: $(test -f "app/views/admin/applications/index.php" && echo "✅" || echo "❌")"
    echo "     - create.php: $(test -f "app/views/admin/applications/create.php" && echo "✅" || echo "❌")"
    echo "     - show.php: $(test -f "app/views/admin/applications/show.php" && echo "✅" || echo "❌")"
    echo "     - view.php: $(test -f "app/views/admin/applications/view.php" && echo "✅" || echo "❌")"
    echo "     - edit.php: $(test -f "app/views/admin/applications/edit.php" && echo "✅" || echo "❌")"
    echo "     - search.php: $(test -f "app/views/admin/applications/search.php" && echo "✅" || echo "❌")"
    
    echo ""
    echo "   Checking for duplicate controllers..."
    if [ -f "app/controllers/ApplicationController.php" ] && [ -f "app/controllers/ApplicationsController.php" ]; then
        echo "⚠️  WARNING: Both ApplicationController and ApplicationsController exist"
        echo "   Only one should be active. Check which one your router uses."
    fi
else
    echo "❌ Views directory not found!"
    exit 1
fi

echo ""
echo "2. Testing controller-view mapping..."
# Quick test of the most important mappings
echo "   ApplicationsController::show() → admin/applications/show.php: $(test -f "app/views/admin/applications/show.php" && echo "✅" || echo "❌")"
echo "   ApplicationController::view() → admin/applications/view.php: $(test -f "app/views/admin/applications/view.php" && echo "✅" || echo "❌")"

echo ""
echo "3. Manual testing required:"
echo "   Please test these URLs in your browser:"
echo ""
echo "   ADMIN PAGES:"
echo "   - http://localhost/fctcns-website/admin/applications"
echo "   - http://localhost/fctcns-website/admin/applications/create"
echo "   - http://localhost/fctcns-website/admin/applications/1 (if you have an application with ID=1)"
echo "   - http://localhost/fctcns-website/admin/news"
echo "   - http://localhost/fctcns-website/admin/research"
echo "   - http://localhost/fctcns-website/admin/users"
echo ""
echo "   PUBLIC PAGES:"
echo "   - http://localhost/fctcns-website/"
echo "   - http://localhost/fctcns-website/about"
echo "   - http://localhost/fctcns-website/contact"
echo ""
echo "4. If any page shows 'View not found':"
echo "   - Check the error message for the exact view path"
echo "   - Run: ./scripts/06-check-controller-views.sh"
echo "   - Ensure the view file exists in app/views/"
echo ""
echo "🎉 If all pages work, your migration is COMPLETE!"