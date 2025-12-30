#!/bin/bash

# setup-directories.sh - Create the new directory structure
# Run from: /c/xampp/htdocs/fctcns-website/scripts/

echo "📁 Creating new directory structure..."
echo "=========================================="

# Navigate to views directory from scripts directory
cd ../app/views || { echo "❌ Failed to navigate to views directory"; exit 1; }

# Create admin subdirectories
echo "Creating admin directories..."
mkdir -p admin/applications
mkdir -p admin/news
mkdir -p admin/research
mkdir -p admin/users

# Create missing admin files directory
mkdir -p admin/_missing

# Create pages subdirectories
echo "Creating pages directories..."
mkdir -p pages/faculty pages/alumni pages/student-life pages/library
mkdir -p pages/gallery pages/events pages/news-article pages/search
mkdir -p pages/maintenance pages/terms pages/privacy pages/sitemap

# Create layouts directory if it doesn't exist
mkdir -p layouts

echo "✅ Directory structure created successfully!"
echo ""
echo "New structure:"
find admin pages layouts -type d 2>/dev/null | sort
echo ""
echo "✅ Run next: ./scripts/02-create-symlinks.sh"