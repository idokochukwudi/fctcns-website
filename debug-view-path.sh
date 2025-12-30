#!/bin/bash

echo "í´ Debugging View Path"
echo "======================"

# Add debug to Controller::render()
sed -i "/\$viewPath = \$this->findViewFile(\$this->view);/a\\
        // DEBUG: Show what path we're looking for\\
        error_log('DEBUG: Looking for view: ' . \$this->view);\\
        error_log('DEBUG: Possible paths: ' . print_r(\$this->getViewPaths(\$this->view), true));\\
        if (\$viewPath) {\\
            error_log('DEBUG: Found at: ' . \$viewPath);\\
        } else {\\
            error_log('DEBUG: View not found');\\
        }" app/core/Controller.php

echo "âœ… Added debug logging to Controller::render()"
echo ""
echo "Now visit: http://localhost/fctcns-website/admin/applications/view/1"
echo "Then check your PHP error log to see what paths are being searched."
echo ""
echo "On XAMPP, error log is usually at:"
echo "C:\\xampp\\php\\logs\\php_error_log"
echo "Or check Apache error log"
