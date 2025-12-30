#!/bin/bash

echo "Ì¥ß Simple Fix for Admin Router"
echo "=============================="

# Backup
cp app/views/admin/index.php app/views/admin/index.php.backup2

echo "1. Adding dynamic routes for applications..."

# Find the line with "'applications/view'" and add dynamic routes after it
LINE=$(grep -n "'applications/view'" app/views/admin/index.php | head -1 | cut -d: -f1)

if [ ! -z "$LINE" ]; then
    echo "Found applications/view at line $LINE"
    
    # Insert dynamic routes after applications/view
    sed -i "${LINE}a\\
    'applications/view/{id}' => ['controller' => 'ApplicationController', 'method' => 'view'],\\
    'applications/edit/{id}' => ['controller' => 'ApplicationController', 'method' => 'edit'],\\
    'applications/delete/{id}' => ['controller' => 'ApplicationController', 'method' => 'destroy']," app/views/admin/index.php
    
    echo "‚úÖ Added dynamic routes"
else
    echo "‚ùå Could not find applications/view route"
fi

echo ""
echo "2. Updating route matching logic..."

# We need to update the route matching logic to handle {id} patterns
# Let me find where $route_key is created
ROUTE_LOGIC_START=$(grep -n "// Find matching route" app/views/admin/index.php | head -1 | cut -d: -f1)

if [ ! -z "$ROUTE_LOGIC_START" ]; then
    echo "Found route logic at line $ROUTE_LOGIC_START"
    
    # Replace from "// Find matching route" to "// Check if route exists"
    sed -i "${ROUTE_LOGIC_START},/if (isset(\$routes\[\$route_key\]))/c\\
// Find matching route with parameter support\\
\$route_key = \$action;\\
\$route_params = [];\\
\\
// Check for patterns with {id}\\
if (\$param1) {\\
    // Try exact match first\\
    \$exact_key = \$action . '/' . \$param1;\\
    if (isset(\$routes[\$exact_key])) {\\
        \$route_key = \$exact_key;\\
    } else if (\$param2) {\\
        // Try pattern with id: applications/view/{id}\\
        \$pattern_key = \$action . '/' . \$param1 . '/{id}';\\
        if (isset(\$routes[\$pattern_key])) {\\
            \$route_key = \$pattern_key;\\
            \$route_params['id'] = \$param2;\\
        }\\
    }\\
}\\
\\
// Debug: Show what route is being looked for\\
if (defined('APP_DEBUG') && APP_DEBUG) {\\
    error_log(\"Admin Router: Looking for route key: '\$route_key'\");\\
    error_log(\"Admin Router: Route params: \" . print_r(\$route_params, true));\\
}\\
\\
// Check if route exists" app/views/admin/index.php
    
    echo "‚úÖ Updated route matching logic"
else
    echo "‚ùå Could not find route matching logic"
fi

echo ""
echo "3. Updating controller method call to pass parameters..."

# Find where the controller method is called
CONTROLLER_CALL=$(grep -n "if (class_exists(\$controller_class) && method_exists(\$controller_class, \$method))" app/views/admin/index.php | head -1 | cut -d: -f1)

if [ ! -z "$CONTROLLER_CALL" ]; then
    echo "Found controller call at line $CONTROLLER_CALL"
    
    # Replace 10 lines after this point
    sed -i "$((CONTROLLER_CALL)),$((CONTROLLER_CALL+10))c\\
            if (class_exists(\$controller_class) && method_exists(\$controller_class, \$method)) {\\
                \$controller = new \$controller_class();\\
                \\
                // Pass parameters to method\\
                if (!empty(\$route_params)) {\\
                    if (\$method == 'view' || \$method == 'edit' || \$method == 'destroy') {\\
                        // For methods that expect an ID parameter\\
                        \$controller->\$method(\$route_params['id']);\\
                    } else {\\
                        \$controller->\$method();\\
                    }\\
                } else {\\
                    \$controller->\$method();\\
                }\\
                exit;\\
            } else {" app/views/admin/index.php
    
    echo "‚úÖ Updated controller method call"
else
    echo "‚ùå Could not find controller call"
fi

echo ""
echo "Ì≥ã Fix applied! Test these URLs:"
echo "1. http://localhost/fctcns-website/admin/applications/view/1"
echo "2. http://localhost/fctcns-website/admin/applications/edit/1"
echo "3. http://localhost/fctcns-website/admin/applications"
