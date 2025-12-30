#!/bin/bash

echo "Ì¥ß Fixing All Application Routes"
echo "================================"

# Create a backup
cp app/views/admin/index.php app/views/admin/index.php.backup3

# Find the line number for applications routes
START_LINE=$(grep -n "'applications'" app/views/admin/index.php | head -1 | cut -d: -f1)
END_LINE=$(grep -n "'applications/update-status'" app/views/admin/index.php | head -1 | cut -d: -f1)

if [ ! -z "$START_LINE" ] && [ ! -z "$END_LINE" ]; then
    echo "Found applications routes from line $START_LINE to $END_LINE"
    
    # Create a new routes section
    cat > temp-routes.php << 'PHPROUTES'
    // Applications
    'applications' => ['controller' => 'ApplicationController', 'method' => 'index'],
    'applications/create' => ['controller' => 'ApplicationController', 'method' => 'create'],
    'applications/view' => ['controller' => 'ApplicationController', 'method' => 'view'],
    'applications/edit' => ['controller' => 'ApplicationController', 'method' => 'edit'],
    'applications/store' => ['controller' => 'ApplicationController', 'method' => 'store'],
    'applications/update' => ['controller' => 'ApplicationController', 'method' => 'update'],
    'applications/update-status' => ['controller' => 'ApplicationController', 'method' => 'updateStatus'],
    'applications/delete' => ['controller' => 'ApplicationController', 'method' => 'destroy'],
    'applications/export' => ['controller' => 'ApplicationController', 'method' => 'export'],
    'applications/search' => ['controller' => 'ApplicationController', 'method' => 'search'],
PHPROUTES
    
    # Replace the applications routes section
    sed -i "${START_LINE},${END_LINE}c\\
$(cat temp-routes.php)" app/views/admin/index.php
    
    rm temp-routes.php
    echo "‚úÖ Updated applications routes with all methods"
else
    echo "‚ùå Could not find applications routes section"
fi

echo ""
echo "Ì≥ã Now test these URLs:"
echo "1. http://localhost/fctcns-website/admin/applications"
echo "2. http://localhost/fctcns-website/admin/applications/view/1"
echo "3. http://localhost/fctcns-website/admin/applications/edit/1"
echo "4. http://localhost/fctcns-website/admin/applications/create"
