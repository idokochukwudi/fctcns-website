#!/bin/bash

# run-tests.sh - Automated tests for the new structure
# Run from: /c/xampp/htdocs/fctcns-website/scripts/

echo "🧪 Running automated tests..."
echo "=========================================="

VIEWS_DIR="../app/views"
TEST_RESULTS=()
PASS_COUNT=0
FAIL_COUNT=0

echo "📁 File Structure Tests:"
echo "------------------------"

# Check key files exist via symlinks
check_file() {
    local file=$1
    local name=$2
    
    echo -n "Checking $name... "
    
    if [ -L "$VIEWS_DIR/$file" ] || [ -f "$VIEWS_DIR/$file" ]; then
        echo "✅ EXISTS"
        TEST_RESULTS+=("✅ $name: EXISTS")
        ((PASS_COUNT++))
        return 0
    else
        echo "❌ MISSING"
        TEST_RESULTS+=("❌ $name: MISSING")
        ((FAIL_COUNT++))
        return 1
    fi
}

check_file "admin/applications/index.php" "Applications Index"
check_file "admin/news/index.php" "News Index"
check_file "admin/research/index.php" "Research Index"
check_file "admin/users/index.php" "Users Index"
check_file "admin/applications/create.php" "Applications Create"
check_file "admin/news/create.php" "News Create"
check_file "admin/research/create.php" "Research Create"
check_file "admin/users/create.php" "Users Create"

# Check symlinks are valid
echo ""
echo "🔗 Symlink Integrity Tests:"
echo "---------------------------"
check_symlink() {
    local symlink=$1
    local name=$2
    
    echo -n "Checking $name symlink... "
    
    if [ -L "$VIEWS_DIR/$symlink" ] && [ -e "$VIEWS_DIR/$symlink" ]; then
        echo "✅ VALID"
        TEST_RESULTS+=("✅ $name symlink: VALID")
        ((PASS_COUNT++))
        return 0
    elif [ -L "$VIEWS_DIR/$symlink" ]; then
        echo "❌ BROKEN"
        TEST_RESULTS+=("❌ $name symlink: BROKEN")
        ((FAIL_COUNT++))
        return 1
    else
        echo "⚠️  NOT A SYMLINK"
        TEST_RESULTS+=("⚠️  $name: NOT A SYMLINK")
        return 2
    fi
}

check_symlink "admin/applications/index.php" "Applications Index"
check_symlink "admin/news/index.php" "News Index"

echo ""
echo "📊 Test Results Summary:"
echo "========================"
printf '%s\n' "${TEST_RESULTS[@]}"
echo ""
echo "📈 Summary: $PASS_COUNT passed, $FAIL_COUNT failed"

if [ $FAIL_COUNT -eq 0 ]; then
    echo ""
    echo "🎉 All tests passed!"
    echo "✅ Run next: ./scripts/04-finalize-migration.sh"
    exit 0
else
    echo ""
    echo "⚠️  Some tests failed. Check the issues before finalizing migration."
    echo "❌ Do not proceed to finalization until all tests pass."
    exit 1
fi