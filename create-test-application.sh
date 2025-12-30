#!/bin/bash

echo "í³ CREATING TEST APPLICATION"
echo "============================"

echo "1. First, check if applications table is accessible..."
cat > check-applications.php << 'CHECK'
<?php
require_once 'app/config/database.php';
try {
    $database = Database::getInstance();
    $db = $database->getConnection();
    
    echo "âœ… Database connected\n";
    
    // Count applications
    $stmt = $db->query("SELECT COUNT(*) as count FROM applications");
    $result = $stmt->fetch();
    echo "Current applications: " . $result['count'] . "\n";
    
    if ($result['count'] == 0) {
        echo "âŒ No applications found!\n";
        echo "You need to create one first at:\n";
        echo "http://localhost/fctcns-website/admin/applications/create\n";
    } else {
        echo "âœ… Applications exist. Listing them:\n";
        $stmt = $db->query("SELECT id, first_name, last_name, email FROM applications ORDER BY id");
        while ($row = $stmt->fetch()) {
            echo "   ID: {$row['id']}, Name: {$row['first_name']} {$row['last_name']}, Email: {$row['email']}\n";
        }
    }
} catch (Exception $e) {
    echo "âŒ Error: " . $e->getMessage() . "\n";
}
CHECK

php check-applications.php

echo ""
echo "2. If no applications exist, you have two options:"
echo ""
echo "   OPTION A: Create via web interface (RECOMMENDED):"
echo "   Visit: http://localhost/fctcns-website/admin/applications/create"
echo ""
echo "   OPTION B: Create test data via SQL:"
cat > create-test-data.sql << 'SQL'
-- Create a test application
INSERT INTO applications (
    first_name, last_name, email, phone, program, entry_year,
    highest_qualification, personal_statement, status
) VALUES (
    'John',
    'Doe',
    'john.doe@example.com',
    '+2348012345678',
    'B.Sc Nursing',
    2025,
    'WASSCE',
    'I am passionate about nursing and want to make a difference in healthcare.',
    'pending'
);

-- Check the ID that was created
SELECT LAST_INSERT_ID() as new_id;
SQL

echo "   SQL commands saved to: create-test-data.sql"
echo "   Run these in phpMyAdmin or MySQL client"

echo ""
echo "3. After creating an application, test these URLs:"
echo "   A. List: http://localhost/fctcns-website/admin/applications"
echo "   B. View: http://localhost/fctcns-website/admin/applications/view/[ID]"
echo "            (replace [ID] with the actual application ID)"
