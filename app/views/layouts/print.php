<?php
/**
 * Fixed Print Layout
 * Clean A4 Document Template
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Print Document'); ?></title>
    
    <style>
        /* Base Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Screen Preview Styles */
        @media screen {
            body {
                background: #f5f5f5;
                padding: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                min-height: 100vh;
                font-family: Arial, sans-serif;
            }
            
            .print-actions {
                background: white;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 3px 10px rgba(0,0,0,0.1);
                margin-bottom: 20px;
                display: flex;
                gap: 10px;
                max-width: 800px;
                width: 100%;
                justify-content: center;
            }
            
            .print-btn, .close-btn {
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 500;
                transition: all 0.3s ease;
                min-width: 120px;
            }
            
            .print-btn {
                background: #007bff;
                color: white;
            }
            
            .print-btn:hover {
                background: #0056b3;
            }
            
            .close-btn {
                background: #6c757d;
                color: white;
            }
            
            .close-btn:hover {
                background: #545b62;
            }
            
            .document-container {
                background: white;
                width: 210mm;
                min-height: 297mm;
                box-shadow: 0 5px 25px rgba(0,0,0,0.15);
                border-radius: 4px;
                overflow: hidden;
                position: relative;
            }
            
            .preview-watermark {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 72px;
                color: rgba(0,0,0,0.05);
                font-weight: bold;
                pointer-events: none;
                z-index: 0;
            }
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 210mm;
                height: 297mm;
            }
            
            .print-actions,
            .preview-watermark {
                display: none !important;
            }
            
            .document-container {
                width: 100% !important;
                min-height: 100% !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .page-break {
                page-break-before: always;
            }
            
            .avoid-break {
                page-break-inside: avoid;
            }
        }
        
        /* Document Content */
        .document-content {
            padding: 15mm;
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            position: relative;
            z-index: 1;
            background: white;
        }
        
        /* Header */
        .print-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #003366;
        }
        
        .print-header h1 {
            font-size: 18pt;
            color: #003366;
            margin-bottom: 5px;
        }
        
        .print-header h2 {
            font-size: 16pt;
            margin-bottom: 10px;
        }
        
        .header-info {
            font-size: 10pt;
            color: #666;
            margin-top: 10px;
        }
        
        /* Footer */
        .print-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 10pt;
            color: #666;
            text-align: center;
        }
        
        /* Content Tables */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .content-table th,
        .content-table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: top;
        }
        
        .content-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 30%;
        }
        
        /* Page Numbers */
        .page-number {
            position: fixed;
            bottom: 10mm;
            right: 15mm;
            font-size: 10pt;
            color: #666;
        }
        
        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .italic { font-style: italic; }
        
        .mt-20 { margin-top: 20px; }
        .mb-20 { margin-bottom: 20px; }
        .pt-10 { padding-top: 10px; }
        .pb-10 { padding-bottom: 10px; }
        
        /* Grid Layout */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <!-- Preview Watermark -->
    <div class="preview-watermark">PREVIEW</div>
    
    <!-- Print Actions -->
    <div class="print-actions">
        <button class="print-btn" onclick="window.print()">🖨️ Print Document</button>
        <button class="close-btn" onclick="window.close()">✕ Close Preview</button>
    </div>
    
    <!-- Main Document Container -->
    <div class="document-container">
        <div class="document-content">
            <!-- Document Header -->
            <div class="print-header">
                <h1>FCT COLLEGE OF NURSING SCIENCES</h1>
                <h2><?php echo htmlspecialchars($documentTitle ?? 'OFFICIAL DOCUMENT'); ?></h2>
                <div class="header-info">
                    Document Reference: <?php echo htmlspecialchars($documentId ?? date('Ymd-His')); ?> | 
                    Generated: <?php echo date('F j, Y H:i'); ?>
                </div>
            </div>
            
            <!-- Main Content -->
            <?php echo $content ?? '<p style="text-align: center; color: #999; padding: 50px;">No content available for printing.</p>'; ?>
            
            <!-- Document Footer -->
            <div class="print-footer">
                <p><strong>OFFICIAL DOCUMENT</strong> - FCT College of Nursing Sciences, Abuja, Nigeria</p>
                <p>Generated by: <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'System'); ?> | 
                   Date: <?php echo date('F j, Y'); ?> | 
                   Time: <?php echo date('H:i:s'); ?></p>
            </div>
            
            <!-- Page Number -->
            <div class="page-number">
                Page <span class="current-page">1</span>
            </div>
        </div>
    </div>
    
    <script>
        // Initialize document
        document.addEventListener('DOMContentLoaded', function() {
            // Update page numbers
            updatePageNumbers();
            
            // Auto-print if requested
            <?php if (isset($autoPrint) && $autoPrint): ?>
            setTimeout(function() {
                window.print();
            }, 1500);
            <?php endif; ?>
            
            // Add keyboard shortcuts
            setupKeyboardShortcuts();
        });
        
        // Update page numbers
        function updatePageNumbers() {
            const pageNumElements = document.querySelectorAll('.current-page');
            pageNumElements.forEach(element => {
                element.textContent = '1';
            });
        }
        
        // Keyboard shortcuts
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + P to print
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    window.print();
                }
                
                // Escape to close preview
                if (e.key === 'Escape') {
                    if (confirm('Close print preview?')) {
                        window.close();
                    }
                }
            });
        }
        
        // Handle print events
        window.addEventListener('beforeprint', function() {
            // Show loading message
            console.log('Preparing document for print...');
        });
        
        window.addEventListener('afterprint', function() {
            console.log('Print completed.');
            
            <?php if (isset($autoPrint) && $autoPrint): ?>
            // Auto-close after printing
            setTimeout(function() {
                if (window.opener && !window.opener.closed) {
                    window.close();
                }
            }, 1000);
            <?php endif; ?>
        });
    </script>
</body>
</html>