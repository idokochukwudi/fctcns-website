#!/bin/bash

echo "í´ Router Capabilities Check"
echo "============================"

echo "1. Checking Router class for HTTP method support:"
if grep -q "get\|post\|put\|delete" app/core/Router.php; then
    echo "âœ… Router appears to support HTTP methods"
    grep -n "get\|post\|put\|delete" app/core/Router.php | head -10
else
    echo "âš ï¸ Router may not support HTTP methods"
fi

echo ""
echo "2. Current route definitions pattern:"
sed -n '/^\$router->add/,/);/p' public/index.php | head -20

echo ""
echo "3. Recommended MVC-compliant fix:"
echo "---------------------------------"
echo "Option A: Add missing show route"
echo "  \$router->add('/admin/applications/{id}', 'ApplicationsController@show');"
echo ""
echo "Option B: Full RESTful routes (if router supports HTTP methods)"
echo "  \$router->get('/admin/applications', 'ApplicationsController@index');"
echo "  \$router->get('/admin/applications/create', 'ApplicationsController@create');"
echo "  \$router->post('/admin/applications', 'ApplicationsController@store');"
echo "  \$router->get('/admin/applications/{id}', 'ApplicationsController@show');"
echo "  \$router->get('/admin/applications/{id}/edit', 'ApplicationsController@edit');"
echo "  \$router->put('/admin/applications/{id}', 'ApplicationsController@update');"
echo "  \$router->delete('/admin/applications/{id}', 'ApplicationsController@destroy');"
