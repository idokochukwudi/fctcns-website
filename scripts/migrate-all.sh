#!/bin/bash

# migrate-all.sh - Complete migration automation (master script)
# Run from: /c/xampp/htdocs/fctcns-website/scripts/

echo "🔄 Complete Migration Automation"
echo "================================"
echo "This will run all migration steps in sequence."
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

run_step() {
    local step_num=$1
    local step_name=$2
    local script_name=$3
    
    echo -e "${YELLOW}Step $step_num: $step_name${NC}"
    echo "================================"
    
    if [ -f "./$script_name" ]; then
        if bash "./$script_name"; then
            echo -e "${GREEN}✅ Step $step_num completed successfully${NC}"
            echo ""
            return 0
        else
            echo -e "${RED}❌ Step $step_num failed${NC}"
            echo "Migration stopped."
            exit 1
        fi
    else
        echo -e "${RED}❌ Script not found: $script_name${NC}"
        exit 1
    fi
}

# Confirm with user
echo "📋 Migration Steps:"
echo "1. Setup directory structure"
echo "2. Create symlinks for testing"
echo "3. Run automated tests"
echo "4. Check controller view references"
echo "5. Finalize migration"
echo ""
read -p "Proceed with migration? (y/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Migration cancelled."
    exit 0
fi

# Run all steps
run_step "01" "Setting up directories" "01-setup-directories.sh"
run_step "02" "Creating symlinks" "02-create-symlinks.sh"
run_step "03" "Running tests" "03-run-tests.sh"
run_step "04" "Checking controller views" "06-check-controller-views.sh"

echo -e "${YELLOW}⚠️  IMPORTANT: Manual Testing Required${NC}"
echo "===================================="
echo "Please manually test these URLs in your browser:"
echo "1. http://localhost/fctcns-website/admin/applications"
echo "2. http://localhost/fctcns-website/admin/news"
echo "3. http://localhost/fctcns-website/admin/research"
echo "4. http://localhost/fctcns-website/admin/users"
echo "5. Test create/edit forms for each module"
echo ""
read -p "Have you manually tested ALL URLs successfully? (y/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${RED}❌ Manual testing required before finalization${NC}"
    exit 1
fi

run_step "05" "Finalizing migration" "04-finalize-migration.sh"

echo -e "${GREEN}🎉 Migration completed successfully!${NC}"
echo ""
echo "✅ Final structure:"
cd ../app/views && find . -name "*.php" | sed 's|^\./||' | sort | head -30
echo "..."
echo ""
echo "⚠️  Remember to test your application thoroughly after migration."