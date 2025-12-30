#!/bin/bash

# check-controller-views.sh - Check what views each controller is trying to load
# Run from: /c/xampp/htdocs/fctcns-website/scripts/

echo "🔍 Checking controller view references..."
echo "=========================================="

CONTROLLERS_DIR="../app/controllers"
VIEWS_DIR="/c/xampp/htdocs/fctcns-website/app/views"
MISSING_VIEWS=()
FOUND_VIEWS=()

echo "📋 Analyzing controllers in: $CONTROLLERS_DIR"
echo ""

# Check each controller file
for controller_file in "$CONTROLLERS_DIR"/*.php; do
    if [ -f "$controller_file" ]; then
        controller_name=$(basename "$controller_file" .php)
        echo "🔎 Checking $controller_name..."
        
        # Extract all render() calls from the controller
        render_calls=$(grep -n "render(" "$controller_file" | grep -v "//.*render(" | grep -v "\*.*render(")
        
        if [ -n "$render_calls" ]; then
            echo "   Render calls found:"
            while IFS= read -r line; do
                # Extract the view path from render('path', ...) or render("path", ...)
                # Using simpler regex that works in bash
                view_path=$(echo "$line" | sed -n "s/.*render[[:space:]]*([\"']\([^\"']*\)[\"'].*/\1/p")
                
                if [ -n "$view_path" ]; then
                    # Remove .php if present
                    view_path="${view_path%.php}"
                    
                    # Check if view exists
                    view_file="$VIEWS_DIR/${view_path}.php"
                    
                    if [ -f "$view_file" ]; then
                        line_num=$(echo "$line" | cut -d: -f1)
                        echo "   ✅ $view_path.php (Line: $line_num)"
                        FOUND_VIEWS+=("$view_path.php")
                    else
                        line_num=$(echo "$line" | cut -d: -f1)
                        echo "   ❌ $view_path.php (Line: $line_num) - MISSING"
                        MISSING_VIEWS+=("$view_path.php")
                    fi
                fi
            done <<< "$render_calls"
        else
            echo "   No render() calls found"
        fi
        echo ""
    fi
done

# Also check Base Controller
echo "🔎 Checking base Controller..."
BASE_CONTROLLER="../app/core/Controller.php"
if [ -f "$BASE_CONTROLLER" ]; then
    echo "   Base controller uses: views/pages/ and views/layouts/"
fi

echo ""
echo "📊 Summary:"
echo "==========="
echo "Found views: ${#FOUND_VIEWS[@]}"
echo "Missing views: ${#MISSING_VIEWS[@]}"

if [ ${#MISSING_VIEWS[@]} -gt 0 ]; then
    echo ""
    echo "❌ Missing views that need to be created:"
    printf '  - %s\n' "${MISSING_VIEWS[@]}" | sort -u
    
    # Offer to create missing views
    echo ""
    read -p "Create placeholder files for missing views? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        cd "$VIEWS_DIR" || exit 1
        for view in $(printf '%s\n' "${MISSING_VIEWS[@]}" | sort -u); do
            mkdir -p "$(dirname "$view")" 2>/dev/null
            touch "$view"
            echo "  Created: $view"
        done
    fi
else
    echo "✅ All controller views have corresponding files!"
fi

echo ""
echo "📁 Current view structure:"
echo "=========================="
find "$VIEWS_DIR" -name "*.php" | sed "s|$VIEWS_DIR/||" | sort | head -50