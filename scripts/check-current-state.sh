#!/bin/bash
# check-current-state.sh - Check current file state
cd ../app/views || exit 1

echo "📊 Current State Analysis"
echo "========================"

echo "1. Files in root directory:"
ls -la *.php 2>/dev/null || echo "No PHP files in root"

echo ""
echo "2. Files in admin/applications:"
ls -la admin/applications/

echo ""
echo "3. Files in admin/news:"
ls -la admin/news/

echo ""
echo "4. Files in admin/research:"
ls -la admin/research/

echo ""
echo "5. Files in admin/users:"
ls -la admin/users/

echo ""
echo "6. Checking if files are symlinks:"
for dir in admin/applications admin/news admin/research admin/users; do
    echo "  $dir:"
    for file in "$dir"/*.php; do
        if [ -e "$file" ]; then
            if [ -L "$file" ]; then
                echo "    $(basename "$file") -> $(readlink "$file") (SYMLINK)"
            else
                echo "    $(basename "$file") (REGULAR FILE)"
            fi
        fi
    done
done