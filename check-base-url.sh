#!/bin/bash
echo "Ì¥ç Checking Base URL Configuration"
echo "=================================="

# Check config files for BASE_URL
echo "1. Looking for BASE_URL definitions..."
find . -name "*.php" -type f -exec grep -l "BASE_URL" {} \; | head -10

echo ""
echo "2. Checking public/index.php for BASE_URL..."
grep -n "define.*BASE_URL" public/index.php || echo "Not found in index.php"

echo ""
echo "3. Checking .htaccess files..."
find . -name ".htaccess" -type f | head -5

echo ""
echo "Ì≥ã If BASE_URL is not set correctly, routes won't work properly."
echo "Make sure BASE_URL includes '/fctcns-website'"
