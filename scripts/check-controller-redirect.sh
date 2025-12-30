#!/bin/bash
# check-controller-redirect.sh - Check for redirects in controllers
cd /c/xampp/htdocs/fctcns-website || exit 1

echo "🔍 Checking for Redirects in Controllers"
echo "========================================"

echo "1. Checking ApplicationController constructor..."
if [ -f "app/controllers/ApplicationController.php" ]; then
    echo "First 30 lines of ApplicationController.php:"
    head -30 app/controllers/ApplicationController.php
fi

echo ""
echo "2. Checking ApplicationsController constructor..."
if [ -f "app/controllers/ApplicationsController.php" ]; then
    echo "First 30 lines of ApplicationsController.php:"
    head -30 app/controllers/ApplicationsController.php
fi

echo ""
echo "3. Checking for any redirect() or header() calls..."
for controller in app/controllers/*.php; do
    if grep -q "redirect\|header.*Location" "$controller"; then
        echo "Found in $(basename $controller):"
        grep -n "redirect\|header.*Location" "$controller" | head -3
    fi
done

echo ""
echo "📋 If ApplicationController redirects to /admin in constructor,"
echo "comment it out temporarily for testing:"
echo "  // header('Location: ' . BASE_URL . '/admin');"
echo "  // exit;"