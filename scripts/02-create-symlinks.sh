#!/bin/bash

# create-symlinks.sh - Create symlinks for testing before moving files
# Run from: /c/xampp/htdocs/fctcns-website/scripts/

echo "🔗 Creating symbolic links for testing..."
echo "=========================================="

cd ../app/views || { echo "❌ Failed to navigate to views directory"; exit 1; }

# Backup current files (just in case)
echo "📦 Creating backup of original files..."
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
mkdir -p ../../backups
cp applications.php ../../backups/applications.php.backup_$TIMESTAMP 2>/dev/null
cp news.php ../../backups/news.php.backup_$TIMESTAMP 2>/dev/null
cp research.php ../../backups/research.php.backup_$TIMESTAMP 2>/dev/null
cp users.php ../../backups/users.php.backup_$TIMESTAMP 2>/dev/null

# Remove existing symlinks if any
echo "🧹 Cleaning up old symlinks..."
find admin -type l -delete 2>/dev/null

# Create symlinks for Applications
echo "Creating Application symlinks..."
ln -sf ../applications.php admin/applications/index.php
ln -sf ../applications_create.php admin/applications/create.php
ln -sf ../applications_view.php admin/applications/show.php

# Create symlinks for News
echo "Creating News symlinks..."
ln -sf ../news.php admin/news/index.php
ln -sf ../news_create.php admin/news/create.php

# Create symlinks for Research
echo "Creating Research symlinks..."
ln -sf ../research.php admin/research/index.php
ln -sf ../research_create.php admin/research/create.php
ln -sf ../research_edit.php admin/research/edit.php

# Create symlinks for Users
echo "Creating User symlinks..."
ln -sf ../users.php admin/users/index.php
ln -sf ../users_create.php admin/users/create.php

# Create placeholder files for missing views
echo "📝 Creating placeholder files for missing views..."
touch admin/debug.php
touch admin/db_create_tables.php
touch admin/error.php
touch admin/404.php

# Admin module placeholders
touch admin/news/show.php
touch admin/news/edit.php
touch admin/news/search.php
touch admin/research/show.php
touch admin/research/search.php
touch admin/users/edit.php
touch admin/users/search.php

# Pages placeholders
touch pages/news.php
touch pages/faculty/index.php
touch pages/alumni/index.php
touch pages/student-life/index.php
touch pages/library/index.php
touch pages/gallery/index.php
touch pages/events/index.php
touch pages/news-article/index.php
touch pages/search/index.php
touch pages/maintenance/index.php
touch pages/terms/index.php
touch pages/privacy/index.php
touch pages/sitemap/index.php

# Verify symlinks were created
echo ""
echo "✅ Symlinks created successfully!"
echo ""
echo "Symlink verification:"
echo "====================="
find admin -type l -exec ls -la {} \; 2>/dev/null | head -20

echo ""
echo "📋 Testing checklist:"
echo "  1. Test /admin/applications"
echo "  2. Test /admin/news"
echo "  3. Test /admin/research"
echo "  4. Test /admin/users"
echo "  5. Test create forms for each module"
echo ""
echo "✅ Run next: ./scripts/03-run-tests.sh"