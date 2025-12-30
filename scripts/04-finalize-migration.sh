#!/bin/bash

# finalize-migration.sh - Final migration after successful testing
# Run from: /c/xampp/htdocs/fctcns-website/scripts/

echo "🚀 Finalizing migration..."
echo "=========================================="

cd ../app/views || { echo "❌ Failed to navigate to views directory"; exit 1; }

# Safety check - ensure we have symlinks setup
if [ ! -L "admin/applications/index.php" ]; then
    echo "❌ ERROR: Symlinks not found. Run 02-create-symlinks.sh first."
    exit 1
fi

echo "📦 Creating final backup..."
BACKUP_DIR="../../backups/final_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

# Backup all original files
cp *.php "$BACKUP_DIR/" 2>/dev/null
echo "Backup saved to: $BACKUP_DIR"

echo "📁 Moving files to new structure..."

# Move Applications files
echo "Moving Applications files..."
if [ -f "applications.php" ]; then
    mv applications.php admin/applications/index.php
    echo "  Moved: applications.php → admin/applications/index.php"
fi
if [ -f "applications_create.php" ]; then
    mv applications_create.php admin/applications/create.php
    echo "  Moved: applications_create.php → admin/applications/create.php"
fi
if [ -f "applications_view.php" ]; then
    mv applications_view.php admin/applications/show.php
    echo "  Moved: applications_view.php → admin/applications/show.php"
fi

# Move News files
echo "Moving News files..."
if [ -f "news.php" ]; then
    mv news.php admin/news/index.php
    echo "  Moved: news.php → admin/news/index.php"
fi
if [ -f "news_create.php" ]; then
    mv news_create.php admin/news/create.php
    echo "  Moved: news_create.php → admin/news/create.php"
fi

# Move Research files
echo "Moving Research files..."
if [ -f "research.php" ]; then
    mv research.php admin/research/index.php
    echo "  Moved: research.php → admin/research/index.php"
fi
if [ -f "research_create.php" ]; then
    mv research_create.php admin/research/create.php
    echo "  Moved: research_create.php → admin/research/create.php"
fi
if [ -f "research_edit.php" ]; then
    mv research_edit.php admin/research/edit.php
    echo "  Moved: research_edit.php → admin/research/edit.php"
fi

# Move Users files
echo "Moving Users files..."
if [ -f "users.php" ]; then
    mv users.php admin/users/index.php
    echo "  Moved: users.php → admin/users/index.php"
fi
if [ -f "users_create.php" ]; then
    mv users_create.php admin/users/create.php
    echo "  Moved: users_create.php → admin/users/create.php"
fi

# Remove symlinks
echo "🧹 Removing symlinks..."
find admin -type l -delete 2>/dev/null
echo "  Removed all symlinks"

echo ""
echo "✅ Migration completed successfully!"
echo ""
echo "📋 Post-migration verification:"
echo "==============================="
echo "File counts in new structure:"
echo "  Applications: $(find admin/applications -name "*.php" | wc -l) files"
echo "  News: $(find admin/news -name "*.php" | wc -l) files"
echo "  Research: $(find admin/research -name "*.php" | wc -l) files"
echo "  Users: $(find admin/users -name "*.php" | wc -l) files"
echo ""
echo "✅ Run final check: ./scripts/06-check-controller-views.sh"