#!/bin/bash
# check-router-patterns.sh - Check router patterns
cd /c/xampp/htdocs/fctcns-website || exit 1

echo "🔍 Checking Router Patterns"
echo "==========================="

echo "1. Looking at the 404 error from your test:"
echo "Available routes include: 'applications' and 'applications/view'"
echo "But NOT 'applications/{id}' pattern"
echo ""
echo "2. Checking public/index.php route definitions..."
if [ -f "public/index.php" ]; then
    echo "Searching for applications routes:"
    grep -B2 -A2 "applications" public/index.php | head -20
fi

echo ""
echo "3. Common patterns in your router:"
echo "   /admin/applications           -> ApplicationsController@index"
echo "   /admin/applications/create    -> ApplicationsController@create"
echo "   /admin/applications/view/{id} -> ApplicationsController@view OR ApplicationsController@show"
echo ""
echo "4. Your URL /admin/applications/1 doesn't match any route"
echo "   Should be: /admin/applications/view/1"
echo ""
echo "📋 To fix:"
echo "1. Visit: http://localhost/fctcns-website/admin/applications/view/1"
echo "2. OR update router to also accept /admin/applications/{id}"