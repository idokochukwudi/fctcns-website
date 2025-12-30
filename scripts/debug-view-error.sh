#!/bin/bash
# debug-view-error.sh - Debug view not found errors
cd /c/xampp/htdocs/fctcns-website || exit 1

echo "🐛 Debugging View Errors"
echo "======================="

echo "1. Checking symlink..."
ls -la app/views/admin/applications/view.php
echo "Target: $(readlink app/views/admin/applications/view.php 2>/dev/null || echo 'Not a symlink')"

echo ""
echo "2. Checking both controllers..."
echo "ApplicationController::view() method:"
grep -A5 "function view" app/controllers/ApplicationController.php

echo ""
echo "ApplicationsController::show() method:"
grep -A5 "function show" app/controllers/ApplicationsController.php

echo ""
echo "3. Checking if show.php exists and has content:"
if [ -f "app/views/admin/applications/show.php" ]; then
    echo "✅ show.php exists ($(wc -l < app/views/admin/applications/show.php) lines)"
    echo "First 3 lines:"
    head -3 app/views/admin/applications/show.php
else
    echo "❌ show.php doesn't exist!"
fi

echo ""
echo "📋 Most likely scenarios:"
echo "1. If /admin/applications/1 shows 'admin/applications/view.php not found':"
echo "   - The symlink should fix this"
echo "   - Verify: ls -la app/views/admin/applications/view.php"
echo ""
echo "2. If /admin/applications/1 shows 'admin/applications/show.php not found':"
echo "   - ApplicationsController is active"
echo "   - show.php exists, so this shouldn't happen"
echo ""
echo "3. If /admin/applications works but /admin/applications/1 doesn't:"
echo "   - Check Apache error log: tail -f /c/xampp/apache/logs/error_log"
echo "   - Visit the URL and check the log"