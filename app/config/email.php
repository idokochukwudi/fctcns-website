<?php
/**
 * Email Configuration - GO54 Shared Hosting Optimized
 * Uses PHP mail() function - works perfectly on GO54
 */

// Auto-detect environment
define('IS_LOCALHOST', in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']));

return [
    // Development settings (local XAMPP - logs emails)
    'development' => [
        'from_email' => 'noreply@localhost.local',
        'from_name' => 'FCT Nursing College (TEST)',
        'use_smtp' => false,
    ],
    
    // Production settings (GO54 shared hosting - uses mail())
    'production' => [
        'from_email' => 'newsletter@fctcns.edu.ng',
        'from_name' => 'FCT College of Nursing Sciences',
        'use_smtp' => false,
    ]
];