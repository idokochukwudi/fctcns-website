#!/bin/bash
# fix-path-issue.sh - Fix the view directory path issue
echo "🔧 Fixing path issue..."
echo "======================"

# Go to project root
cd /c/xampp/htdocs/fctcns-website || exit 1

echo "1. Checking current directory structure..."
pwd
ls -la app/views/

echo ""
echo "2. The script is looking for views in wrong location."
echo "   It should look in: $(pwd)/app/views"
echo ""

# Update the check-controller-views.sh script
echo "3. Fixing the script path..."
sed -i "s|VIEWS_DIR=.*|VIEWS_DIR=\"$(pwd)/app/views\"|" scripts/06-check-controller-views.sh

echo "✅ Path fixed!"
echo ""
echo "Now run the verification:"
./scripts/test-critical-views.sh