#!/bin/bash
# fix-symlinks.sh - Fix symlink creation issues
cd ../app/views || exit 1

echo "🔧 Fixing symlink issues..."
echo "=========================="

# Remove placeholder files that were incorrectly created
echo "Removing placeholder files..."
rm -f admin/applications/*.php
rm -f admin/news/*.php
rm -f admin/research/*.php
rm -f admin/users/*.php

echo "Checking if source files exist..."
for file in applications.php news.php research.php users.php applications_create.php applications_view.php news_create.php research_create.php research_edit.php users_create.php; do
    if [ -f "$file" ]; then
        echo "  ✅ $file exists"
    else
        echo "  ❌ $file MISSING"
    fi
done

echo ""
echo "Creating symlinks..."

# Create symlinks for Applications
echo "Creating Application symlinks..."
if [ -f "applications.php" ]; then
    ln -sf ../applications.php admin/applications/index.php
    echo "  Created: applications.php → admin/applications/index.php"
fi
if [ -f "applications_create.php" ]; then
    ln -sf ../applications_create.php admin/applications/create.php
    echo "  Created: applications_create.php → admin/applications/create.php"
fi
if [ -f "applications_view.php" ]; then
    ln -sf ../applications_view.php admin/applications/show.php
    echo "  Created: applications_view.php → admin/applications/show.php"
fi

# Create symlinks for News
echo "Creating News symlinks..."
if [ -f "news.php" ]; then
    ln -sf ../news.php admin/news/index.php
    echo "  Created: news.php → admin/news/index.php"
fi
if [ -f "news_create.php" ]; then
    ln -sf ../news_create.php admin/news/create.php
    echo "  Created: news_create.php → admin/news/create.php"
fi

# Create symlinks for Research
echo "Creating Research symlinks..."
if [ -f "research.php" ]; then
    ln -sf ../research.php admin/research/index.php
    echo "  Created: research.php → admin/research/index.php"
fi
if [ -f "research_create.php" ]; then
    ln -sf ../research_create.php admin/research/create.php
    echo "  Created: research_create.php → admin/research/create.php"
fi
if [ -f "research_edit.php" ]; then
    ln -sf ../research_edit.php admin/research/edit.php
    echo "  Created: research_edit.php → admin/research/edit.php"
fi

# Create symlinks for Users
echo "Creating User symlinks..."
if [ -f "users.php" ]; then
    ln -sf ../users.php admin/users/index.php
    echo "  Created: users.php → admin/users/index.php"
fi
if [ -f "users_create.php" ]; then
    ln -sf ../users_create.php admin/users/create.php
    echo "  Created: users_create.php → admin/users/create.php"
fi

echo ""
echo "Verifying symlinks..."
find admin -type l -exec ls -la {} \; 2>/dev/null

echo ""
echo "✅ Symlinks fixed! Now run: ./scripts/03-run-tests.sh"