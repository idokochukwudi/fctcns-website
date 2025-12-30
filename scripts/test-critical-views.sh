#!/bin/bash
# test-critical-views.sh - Test the most critical views
cd /c/xampp/htdocs/fctcns-website/app/views || exit 1

echo "🎯 Testing Critical Views"
echo "========================"

# List of critical views that must exist
CRITICAL_VIEWS=(
    "admin/applications/index.php"
    "admin/news/index.php"
    "admin/research/index.php"
    "admin/users/index.php"
    "admin/applications/create.php"
    "admin/news/create.php"
    "admin/research/create.php"
    "admin/users/create.php"
    "admin/applications/show.php"
    "admin/applications/view.php"
    "pages/home.php"
    "pages/about.php"
    "pages/programs.php"
    "pages/admissions.php"
    "pages/research.php"
    "pages/contact.php"
    "pages/404.php"
)

echo "Checking critical view files..."
echo ""

ALL_EXIST=1
for view in "${CRITICAL_VIEWS[@]}"; do
    if [ -f "$view" ]; then
        lines=$(wc -l < "$view" 2>/dev/null || echo "0")
        if [ "$lines" -gt 10 ]; then
            echo "✅ $view ($lines lines)"
        elif [ "$lines" -gt 0 ]; then
            echo "⚠️  $view ($lines lines) - EXISTS but short"
            ALL_EXIST=0
        else
            echo "❌ $view (0 lines) - EMPTY"
            ALL_EXIST=0
        fi
    else
        echo "❌ $view - MISSING"
        ALL_EXIST=0
    fi
done

echo ""
echo "Checking symlinks..."
if [ -L "admin/applications/view.php" ]; then
    target=$(readlink "admin/applications/view.php")
    echo "✅ admin/applications/view.php → $target (SYMLINK)"
else
    echo "❌ admin/applications/view.php is not a symlink"
    if [ -f "admin/applications/view.php" ]; then
        echo "   It's a regular file. Converting to symlink..."
        rm "admin/applications/view.php"
        ln -s show.php "admin/applications/view.php"
        echo "   ✅ Converted to symlink"
    fi
fi

echo ""
if [ $ALL_EXIST -eq 1 ]; then
    echo "🎉 All critical views exist!"
    echo ""
    echo "📋 Manual Testing Required:"
    echo "1. Open http://localhost/fctcns-website/"
    echo "2. Open http://localhost/fctcns-website/admin/applications"
    echo "3. Open http://localhost/fctcns-website/admin/applications/1"
    echo "4. Open http://localhost/fctcns-website/admin/news"
    echo "5. Open http://localhost/fctcns-website/admin/research"
    echo "6. Open http://localhost/fctcns-website/admin/users"
else
    echo "⚠️  Some views are missing or empty."
    echo "Run ./scripts/06-check-controller-views.sh for complete analysis."
fi