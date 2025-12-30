#!/bin/bash

# rollback.sh - Rollback to original structure if needed
# Run from: /c/xampp/htdocs/fctcns-website/scripts/

echo "↩️  Rolling back migration..."
echo "=========================================="

cd ../app/views || { echo "❌ Failed to navigate to views directory"; exit 1; }

# Find latest backup
LATEST_BACKUP=$(ls -td ../../backups/final_* 2>/dev/null | head -1)

if [ -z "$LATEST_BACKUP" ]; then
    # Try to find any backup
    LATEST_BACKUP=$(ls -td ../../backups/*.backup_* 2>/dev/null | head -1 | xargs dirname 2>/dev/null)
fi

if [ -z "$LATEST_BACKUP" ]; then
    echo "❌ No backups found! Cannot rollback."
    exit 1
fi

echo "Found backup: $LATEST_BACKUP"
echo ""
ls -la "$LATEST_BACKUP/" 2>/dev/null | grep "\.php$"
echo ""

read -p "Restore from this backup? (y/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Rollback cancelled."
    exit 1
fi

echo "Restoring files..."
# Copy all php files from backup to current directory
cp "$LATEST_BACKUP"/*.php . 2>/dev/null

echo "Cleaning up new structure..."
# Remove the new directories (but keep placeholders)
rm -f admin/applications/*.php 2>/dev/null
rm -f admin/news/*.php 2>/dev/null
rm -f admin/research/*.php 2>/dev/null
rm -f admin/users/*.php 2>/dev/null

echo ""
echo "✅ Rollback completed!"
echo "Original files restored from: $LATEST_BACKUP"
echo ""
echo "⚠️  Note: Directory structure remains. Run 01-setup-directories.sh to rebuild."