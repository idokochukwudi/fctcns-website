#!/bin/bash
# check-routes.sh - Check registered routes
cd /c/xampp/htdocs/fctcns-website || exit 1

echo "🔍 Checking Routes Configuration"
echo "================================"

echo "1. Checking public/index.php for route definitions..."
if [ -f "public/index.php" ]; then
    echo "Routes found in public/index.php:"
    grep -n "get.*applications\|post.*applications\|admin.*applications" public/index.php
fi

echo ""
echo "2. Checking if route 'applications' exists in the 404 error list..."
echo "From your error: 'applications' IS in the list, so route exists"
echo "But '/admin/applications/1' gives 404 - means route pattern doesn't match"
echo ""
echo "3. Likely issue: Route pattern expects /admin/applications/{id}"
echo "   But your router might expect /admin/applications/view/{id}"
echo ""
echo "Check your ApplicationController methods:"
grep -n "function.*show\|function.*view" app/controllers/ApplicationController.php
grep -n "function.*show\|function.*view" app/controllers/ApplicationsController.php