#!/bin/bash

echo "í´§ Testing Route Fix for applications/view/{id}"
echo "================================================"

# Check if the router supports dynamic segments
echo "1. Checking router pattern matching..."
if grep -q "'applications/view/'" public/index.php; then
    echo "   âœ… Route 'applications/view/' exists in index.php"
else
    echo "   âŒ Route 'applications/view/' NOT found in index.php"
fi

# Check the controller method
echo ""
echo "2. Checking ApplicationsController for view() method..."
if grep -q "public function view" app/controllers/ApplicationsController.php; then
    echo "   âœ… view() method exists"
    grep -n "public function view" app/controllers/ApplicationsController.php
else
    echo "   âŒ view() method NOT found"
fi

# Check for show() method as alternative
echo ""
echo "3. Checking for show() method..."
if grep -q "public function show" app/controllers/ApplicationsController.php; then
    echo "   âœ… show() method exists"
    grep -n "public function show" app/controllers/ApplicationsController.php
fi

# Quick fix: Add route if missing
echo ""
echo "4. Checking route definitions..."
if ! grep -q "'applications/view/'" public/index.php 2>/dev/null; then
    echo "   Adding missing route pattern..."
    # Create a backup first
    cp public/index.php public/index.php.backup
    
    # Add the route pattern (simplified example)
    echo "   You need to ensure the route is defined as:"
    echo "   '/admin/applications/view/{id}' => 'ApplicationsController@view'"
fi

echo ""
echo "í³‹ TEST THE FOLLOWING URLS:"
echo "1. List: http://localhost/fctcns-website/admin/applications"
echo "2. View: http://localhost/fctcns-website/admin/applications/view/1"
echo "3. Create: http://localhost/fctcns-website/admin/applications/create"
