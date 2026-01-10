<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nominal Roll Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #2c3e50;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 10px;
            --box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            --transition: all 0.3s ease;
            
            /* Enhanced contrast colors */
            --text-primary: #212529;
            --text-secondary: #495057;
            --text-light: #6c757d;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --border-color: #dee2e6;
        }
        
        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* High contrast utility classes */
        .high-contrast-text {
            color: #212529 !important;
            font-weight: 600 !important;
        }
        
        .dark-bg-text {
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        .light-bg-text {
            color: #212529 !important;
            font-weight: 500;
        }
        
        /* Enhanced badge contrast */
        .badge-contrast {
            padding: 4px 8px;
            font-weight: 600;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .badge-male {
            background-color: rgba(44, 90, 160, 0.15) !important;
            color: var(--primary-color) !important;
            border: 1px solid rgba(44, 90, 160, 0.3);
        }
        
        .badge-female {
            background-color: rgba(220, 53, 69, 0.15) !important;
            color: var(--danger-color) !important;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        .badge-active {
            background-color: rgba(40, 167, 69, 0.15) !important;
            color: var(--success-color) !important;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .badge-inactive {
            background-color: rgba(255, 193, 7, 0.15) !important;
            color: #856404 !important;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        .badge-grade {
            background-color: rgba(23, 162, 184, 0.15) !important;
            color: var(--info-color) !important;
            border: 1px solid rgba(23, 162, 184, 0.3);
            font-weight: 700;
        }
        
        /* Progressive Loading Styles */
        .loading-skeleton {
            animation: skeleton-loading 1.5s infinite ease-in-out;
        }

        @keyframes skeleton-loading {
            0% { opacity: 0.5; }
            50% { opacity: 0.8; }
            100% { opacity: 0.5; }
        }

        .skeleton-row {
            height: 40px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .main-container {
            padding: 15px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }
        
        /* Enhanced Header Styles with better contrast */
        .page-header {
            background: var(--bg-white);
            border-radius: var(--border-radius);
            padding: 20px 25px;
            margin-bottom: 20px;
            box-shadow: var(--box-shadow);
            border-left: 4px solid var(--primary-color);
        }
        
        .page-header h1 {
            color: var(--secondary-color);
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.6rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .page-header .subtitle {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0;
            font-weight: 500;
        }
        
        /* Enhanced Stats Cards with better responsiveness */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 12px;
            }
        }
        
        @media (max-width: 480px) {
            .stats-container {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }
        
        .stat-card {
            background: var(--bg-white);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            border-top: 4px solid var(--primary-color);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .stat-card:nth-child(2) { border-top-color: var(--success-color); }
        .stat-card:nth-child(3) { border-top-color: var(--warning-color); }
        .stat-card:nth-child(4) { border-top-color: var(--secondary-color); }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--primary-color), #1e3a6f);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 768px) {
            .stat-icon {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }
        }
        
        .stat-card:nth-child(2) .stat-icon {
            background: linear-gradient(135deg, var(--success-color), #1e7e34);
        }
        
        .stat-card:nth-child(3) .stat-icon {
            background: linear-gradient(135deg, var(--warning-color), #d39e00);
        }
        
        .stat-card:nth-child(4) .stat-icon {
            background: linear-gradient(135deg, var(--secondary-color), #1c2833);
        }
        
        .stat-card h3 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 5px;
            color: var(--secondary-color);
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
            line-height: 1;
        }
        
        @media (max-width: 768px) {
            .stat-card h3 {
                font-size: 1.6rem;
            }
        }
        
        .stat-card p {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
            overflow: hidden;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
            margin-top: auto;
        }
        
        .progress-bar {
            background: linear-gradient(to right, var(--primary-color), #2980b9);
            transition: width 0.5s ease;
        }
        
        .stat-card small {
            font-size: 0.75rem;
        }
        
        /* Enhanced Main Content Layout */
        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 25px;
        }
        
        @media (max-width: 992px) {
            .content-wrapper {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        /* Enhanced Configuration Panel */
        .config-panel {
            background: var(--bg-white);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        @media (max-width: 992px) {
            .config-panel {
                position: static;
                margin-bottom: 20px;
            }
        }
        
        .config-panel h2 {
            color: var(--secondary-color);
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Enhanced Field Categories with better visibility */
        .field-categories-container {
            margin-bottom: 25px;
        }
        
        .category-header {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border-color);
        }
        
        .category-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .category-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .category-actions .btn {
            padding: 6px 10px;
            font-size: 0.8rem;
            border-radius: 6px;
            font-weight: 600;
        }
        
        .field-category {
            margin-bottom: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            background: var(--bg-white);
        }
        
        .category-title {
            background: #f8f9fa;
            padding: 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
            border-bottom: 1px solid var(--border-color);
        }
        
        .category-title:hover {
            background: #e9ecef;
        }
        
        .category-title span {
            font-weight: 700;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
        }
        
        .category-toggle {
            font-size: 0.85rem;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .category-fields {
            padding: 15px;
            background: var(--bg-white);
            display: none;
        }
        
        .category-fields.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .field-item {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 6px;
            transition: var(--transition);
            border: 1px solid transparent;
        }
        
        .field-item:hover {
            background: #f8f9fa;
            border-color: var(--primary-color);
        }
        
        .field-item:last-child {
            margin-bottom: 0;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #adb5bd;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(44, 90, 160, 0.25);
            border-color: var(--primary-color);
        }
        
        .form-check-label {
            font-size: 0.85rem;
            color: var(--text-primary);
            cursor: pointer;
            flex: 1;
            font-weight: 500;
        }
        
        .default-badge {
            background: var(--success-color);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-left: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Enhanced Filters Section */
        .filters-section {
            margin-bottom: 25px;
        }
        
        .filters-section h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-group {
            margin-bottom: 15px;
        }
        
        .filter-group label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 8px;
            display: block;
        }
        
        .filter-group .form-control,
        .filter-group .form-select {
            border: 2px solid #ced4da;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 0.85rem;
            transition: var(--transition);
            color: var(--text-primary);
            font-weight: 500;
        }
        
        .filter-group .form-control:focus,
        .filter-group .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(44, 90, 160, 0.25);
        }
        
        /* Enhanced Sorting Section */
        .sorting-section {
            margin-bottom: 25px;
        }
        
        .sorting-section h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Enhanced Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .btn {
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: var(--transition);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 768px) {
            .btn {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #1e3a6f);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1e3a6f, var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(44, 90, 160, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: var(--secondary-color);
            border: 2px solid var(--border-color);
            font-weight: 600;
        }
        
        .btn-secondary:hover {
            background: #f8f9fa;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        /* Enhanced Preview Panel */
        .preview-panel {
            background: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            display: none;
            margin-bottom: 25px;
            animation: slideIn 0.5s ease-out;
            border: 1px solid var(--border-color);
        }
        
        .preview-panel.show {
            display: block;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .preview-header {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        @media (max-width: 768px) {
            .preview-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .preview-header h3 {
                width: 100%;
            }
            
            .preview-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
        
        .preview-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .preview-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .preview-actions .btn {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        
        .preview-content {
            padding: 20px;
            min-height: 300px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        @media (max-width: 768px) {
            .preview-content {
                padding: 15px;
                max-height: 400px;
            }
        }
        
        /* Enhanced Preview Table with better contrast */
        .preview-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .preview-table {
                font-size: 0.8rem;
            }
        }
        
        .preview-table thead th {
            background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
            padding: 12px 10px;
            text-align: left;
            font-weight: 700;
            color: var(--secondary-color);
            border-bottom: 2px solid var(--primary-color);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .preview-table tbody td {
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            font-weight: 500;
        }
        
        .preview-table tbody tr:nth-child(even) {
            background-color: rgba(248, 249, 250, 0.5);
        }
        
        .preview-table tbody tr:hover {
            background-color: rgba(44, 90, 160, 0.1);
            transform: translateX(2px);
            transition: var(--transition);
        }
        
        /* Enhanced gender cell styles */
        .gender-cell {
            text-align: center;
            min-width: 80px;
        }
        
        .gender-cell .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 5px 10px;
            font-weight: 700;
            min-width: 65px;
        }
        
        /* Status cell styles */
        .status-cell .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 5px 10px;
            font-weight: 700;
            min-width: 75px;
        }
        
        .empty-preview {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-light);
        }
        
        .empty-preview i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #adb5bd;
        }
        
        .empty-preview h4 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }
        
        /* Enhanced Saved Reports */
        .saved-reports-section {
            background: var(--bg-white);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            border: 1px solid var(--border-color);
        }
        
        .saved-reports-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .saved-reports-header h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .saved-reports-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .saved-report-item {
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid var(--primary-color);
            transition: var(--transition);
            border: 1px solid var(--border-color);
        }
        
        .saved-report-item:hover {
            background: white;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            transform: translateX(5px);
            border-left-color: var(--success-color);
        }
        
        .report-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        @media (max-width: 576px) {
            .report-info {
                flex-direction: column;
            }
            
            .report-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
        
        .report-main-info h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }
        
        .report-meta {
            display: flex;
            gap: 15px;
            font-size: 0.8rem;
            color: var(--text-light);
            flex-wrap: wrap;
        }
        
        .report-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
        }
        
        .report-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .report-actions .btn {
            padding: 5px 10px;
            font-size: 0.8rem;
            border-radius: 6px;
            font-weight: 600;
        }
        
        .report-fields {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        
        .field-tag {
            background: white;
            border: 1px solid var(--border-color);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        /* Enhanced Badges */
        .badge {
            padding: 5px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.75rem;
        }
        
        .badge-primary {
            background: rgba(44, 90, 160, 0.15);
            color: var(--primary-color);
            border: 1px solid rgba(44, 90, 160, 0.3);
        }
        
        .badge-success {
            background: rgba(40, 167, 69, 0.15);
            color: var(--success-color);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .badge-warning {
            background: rgba(255, 193, 7, 0.15);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        /* Loading Animation */
        .loading {
            display: none;
            text-align: center;
            padding: 40px;
        }
        
        .loading.show {
            display: block;
        }
        
        /* Mobile-specific optimizations */
        @media (max-width: 576px) {
            .main-container {
                padding: 10px;
            }
            
            .page-header {
                padding: 15px;
            }
            
            .page-header h1 {
                font-size: 1.4rem;
            }
            
            .config-panel,
            .saved-reports-section {
                padding: 15px;
            }
            
            .category-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .category-actions .btn {
                width: 100%;
                justify-content: center;
            }
            
            .preview-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            .preview-table thead th,
            .preview-table tbody td {
                padding: 8px;
                font-size: 0.8rem;
            }
            
            .action-buttons .btn {
                width: 100%;
                text-align: center;
            }
        }
        
        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .stat-card,
            .config-panel,
            .preview-panel,
            .saved-reports-section {
                border: 2px solid var(--secondary-color);
            }
            
            .preview-table {
                border: 2px solid var(--secondary-color);
            }
            
            .preview-table th {
                background-color: var(--secondary-color);
                color: white;
            }
            
            .btn {
                border: 2px solid currentColor;
            }
        }
        
        /* Print styles */
        @media print {
            body {
                background-color: white !important;
                color: black !important;
            }
            
            .btn,
            .preview-actions,
            .config-panel,
            .saved-reports-section,
            .stats-container,
            .page-header .btn {
                display: none !important;
            }
            
            .preview-panel {
                box-shadow: none !important;
                border: 1px solid #000 !important;
                display: block !important;
                page-break-inside: avoid;
            }
            
            .preview-table {
                border: 1px solid #000 !important;
                font-size: 10pt !important;
            }
            
            .preview-table th {
                background-color: #ddd !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
            }
            
            .badge {
                border: 1px solid #000 !important;
                color: #000 !important;
                background-color: #fff !important;
            }
            
            .preview-content {
                max-height: none !important;
                overflow: visible !important;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1><i class="fas fa-chart-bar text-primary me-2"></i> Nominal Roll Reports</h1>
                    <p class="subtitle">Generate custom reports with selected fields and filters</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="/admin/nominal-roll" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards - Made Responsive -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-columns"></i>
                </div>
                <h3 id="selectedFieldsCount">0</h3>
                <p>Fields Selected</p>
                <div class="progress">
                    <div class="progress-bar" id="fieldProgress" style="width: 0%"></div>
                </div>
                <small class="text-muted mt-2 d-block" id="fieldProgressText">0% selected</small>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-filter"></i>
                </div>
                <h3 id="activeFiltersCount">0</h3>
                <p>Active Filters</p>
                <div class="mt-3">
                    <small class="text-muted" id="activeFiltersPreview">No filters applied</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-save"></i>
                </div>
                <h3><?php echo count($savedReports); ?></h3>
                <p>Saved Reports</p>
                <?php if (!empty($savedReports)): ?>
                <div class="mt-3">
                    <small class="text-muted">Last: <?php echo date('M d, Y', strtotime($savedReports[0]['created_at'])) ?></small>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-list-alt"></i>
                </div>
                <h3 id="totalFieldsCount"><?php echo $totalFields ?? '--'; ?></h3>
                <p>Available Fields</p>
                <div class="mt-3">
                    <small class="text-muted">In database</small>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-wrapper">
            <!-- Left Column: Configuration -->
            <div class="config-panel">
                <h2><i class="fas fa-sliders-h"></i> Report Configuration</h2>
                
                <form id="reportForm" method="POST" action="/admin/nominal-roll/generate-report" target="_blank">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    <input type="hidden" name="csrf_token_time" value="<?php echo $_SESSION['csrf_token_time'] ?? ''; ?>">
                    
                    <!-- Field Selection -->
                    <div class="field-categories-container">
                        <div class="category-header">
                            <h3>
                                <i class="fas fa-columns"></i>
                                Select Fields
                                <span class="badge badge-primary ms-2" id="selectedFieldsBadge">0 selected</span>
                            </h3>
                            <div class="category-actions">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllFields()">
                                    <i class="fas fa-check-double"></i> All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success" onclick="selectDefaultFields()">
                                    <i class="fas fa-star"></i> Default
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAllFields()">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                        
                        <div class="field-categories-list">
                            <?php 
                            $totalFields = 0;
                            foreach ($availableFields as $categoryKey => $category): 
                                $totalFields += count($category['fields']);
                            ?>
                            <div class="field-category">
                                <div class="category-title" onclick="toggleCategory('<?= $categoryKey ?>')">
                                    <span>
                                        <i class="fas fa-chevron-right me-2 category-icon" id="icon_<?= $categoryKey ?>"></i>
                                        <?= htmlspecialchars($category['label']) ?>
                                        <span class="badge badge-secondary ms-2" id="count_<?= $categoryKey ?>">0</span>
                                    </span>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input category-checkbox" 
                                               type="checkbox" 
                                               id="cat_<?= $categoryKey ?>"
                                               onclick="event.stopPropagation(); toggleCategoryCheckbox('<?= $categoryKey ?>')">
                                    </div>
                                </div>
                                <div class="category-fields" id="fields_<?= $categoryKey ?>">
                                    <?php foreach ($category['fields'] as $fieldKey => $fieldLabel): ?>
                                    <div class="field-item">
                                        <div class="form-check">
                                            <input class="form-check-input field-checkbox" 
                                                   type="checkbox" 
                                                   name="selected_fields[]" 
                                                   value="<?= htmlspecialchars($fieldKey) ?>" 
                                                   id="field_<?= htmlspecialchars($fieldKey) ?>"
                                                   data-category="<?= htmlspecialchars($categoryKey) ?>"
                                                   <?= in_array($fieldKey, $defaultFields) ? 'checked' : '' ?>
                                                   onchange="updateCounts()">
                                            <label class="form-check-label" for="field_<?= htmlspecialchars($fieldKey) ?>">
                                                <?= htmlspecialchars($fieldLabel) ?>
                                                <?php if (in_array($fieldKey, $defaultFields)): ?>
                                                <span class="default-badge ms-2">Default</span>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Filters Section -->
                    <div class="filters-section">
                        <h3><i class="fas fa-filter"></i> Filter Results</h3>
                        
                        <div class="row g-3">
                            <!-- Search -->
                            <div class="col-12">
                                <div class="filter-group">
                                    <label for="searchField" class="form-label">Search Records</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" 
                                               id="searchField" 
                                               name="search" 
                                               class="form-control" 
                                               placeholder="Search by name, employee number..."
                                               oninput="updateFilterCount()">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- State -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_state" class="form-label">State</label>
                                    <select name="filter_state" id="filter_state" class="form-select filter-select" onchange="updateFilterCount()">
                                        <option value="">All States</option>
                                        <?php
                                        // You should populate this from your database
                                        $states = ['Lagos', 'Abuja', 'Rivers', 'Kano', 'Oyo', 'Kaduna', 'Edo', 'Delta', 'Ogun', 'Enugu'];
                                        foreach ($states as $state): ?>
                                        <option value="<?= htmlspecialchars($state) ?>"><?= htmlspecialchars($state) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Department -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_department" class="form-label">Department</label>
                                    <select name="filter_department" id="filter_department" class="form-select filter-select" onchange="updateFilterCount()">
                                        <option value="">All Departments</option>
                                        <?php
                                        $departments = ['IT', 'HR', 'Finance', 'Operations', 'Marketing', 'Academic', 'Administration'];
                                        foreach ($departments as $dept): ?>
                                        <option value="<?= htmlspecialchars($dept) ?>"><?= htmlspecialchars($dept) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Grade Level -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_grade_level" class="form-label">Grade Level</label>
                                    <select name="filter_grade_level" id="filter_grade_level" class="form-select filter-select" onchange="updateFilterCount()">
                                        <option value="">All Grades</option>
                                        <?php for ($i = 1; $i <= 17; $i++): ?>
                                        <option value="<?= $i ?>">GL <?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Sex -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_sex" class="form-label">Gender</label>
                                    <select name="filter_sex" id="filter_sex" class="form-select filter-select" onchange="updateFilterCount()">
                                        <option value="">All</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Rank -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_rank" class="form-label">Rank</label>
                                    <select name="filter_rank" id="filter_rank" class="form-select filter-select" onchange="updateFilterCount()">
                                        <option value="">All Ranks</option>
                                        <?php
                                        $ranks = ['Director', 'Manager', 'Supervisor', 'Officer', 'Assistant', 'Lecturer', 'Professor'];
                                        foreach ($ranks as $rank): ?>
                                        <option value="<?= htmlspecialchars($rank) ?>"><?= htmlspecialchars($rank) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_status" class="form-label">Status</label>
                                    <select name="filter_status" id="filter_status" class="form-select filter-select" onchange="updateFilterCount()">
                                        <option value="active">Active Only</option>
                                        <option value="inactive">Inactive Only</option>
                                        <option value="">All Status</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sorting Section -->
                    <div class="sorting-section">
                        <h3><i class="fas fa-sort-amount-down"></i> Sort Results</h3>
                        <select name="sort_order" class="form-select">
                            <option value="surname_asc">Surname (A to Z)</option>
                            <option value="surname_desc">Surname (Z to A)</option>
                            <option value="employee_number_asc">Employee Number (Ascending)</option>
                            <option value="employee_number_desc">Employee Number (Descending)</option>
                            <option value="grade_level_asc">Grade Level (Low to High)</option>
                            <option value="grade_level_desc">Grade Level (High to Low)</option>
                            <option value="state_asc">State (A to Z)</option>
                            <option value="state_desc">State (Z to A)</option>
                            <option value="date_of_first_appointment_asc">Date of Appointment (Oldest First)</option>
                            <option value="date_of_first_appointment_desc">Date of Appointment (Newest First)</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="button" class="btn btn-primary" id="generateBtn" onclick="generatePreview()">
                            <i class="fas fa-eye me-2"></i> Generate Preview
                        </button>
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-external-link-alt me-2"></i> Open in New Tab
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="showSaveModal()">
                            <i class="fas fa-save me-2"></i> Save Configuration
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Preview & Saved Reports -->
            <div>
                <!-- Preview Panel -->
                <div class="preview-panel" id="previewPanel">
                    <div class="preview-header">
                        <h3><i class="fas fa-file-alt"></i> Report Preview</h3>
                        <div class="preview-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="exportExcel()">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                            <button class="btn btn-sm btn-outline-primary" onclick="exportCSV()">
                                <i class="fas fa-file-csv me-1"></i> CSV
                            </button>
                            <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="hidePreview()">
                                <i class="fas fa-times me-1"></i> Close
                            </button>
                        </div>
                    </div>
                    
                    <!-- Loading State -->
                    <div class="loading" id="previewLoading">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5>Generating Preview...</h5>
                        <p class="text-muted">Please wait while we process your report</p>
                    </div>
                    
                    <!-- Preview Content -->
                    <div class="preview-content" id="previewContent">
                        <div class="empty-preview">
                            <i class="fas fa-chart-bar"></i>
                            <h4>No Preview Available</h4>
                            <p class="text-muted">Configure your report and click "Generate Preview" to see results</p>
                        </div>
                    </div>
                </div>

                <!-- Saved Reports -->
                <div class="saved-reports-section">
                    <div class="saved-reports-header">
                        <h3><i class="fas fa-history"></i> Saved Reports</h3>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge badge-primary"><?php echo count($savedReports); ?> total</span>
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshSavedReports()" title="Refresh">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    
                    <?php if (empty($savedReports)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted mb-2">No Saved Reports</h5>
                            <p class="text-muted mb-4">Save your report configurations for future use</p>
                            <button class="btn btn-primary" onclick="selectDefaultFields()">
                                <i class="fas fa-magic me-2"></i> Use Default Settings
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="saved-reports-list">
                            <?php foreach ($savedReports as $report): ?>
                            <div class="saved-report-item">
                                <div class="report-info">
                                    <div class="report-main-info">
                                        <h4><?= htmlspecialchars($report['report_name']) ?></h4>
                                        <div class="report-meta">
                                            <span>
                                                <i class="fas fa-user"></i>
                                                <?= htmlspecialchars($report['created_by_name'] ?? 'Unknown') ?>
                                            </span>
                                            <span>
                                                <i class="fas fa-calendar"></i>
                                                <?= date('M d, Y', strtotime($report['created_at'])) ?>
                                            </span>
                                            <span>
                                                <i class="fas fa-columns"></i>
                                                <?= count($report['selected_fields']) ?> fields
                                            </span>
                                            <?php if ($report['is_public']): ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-globe"></i> Public
                                            </span>
                                            <?php else: ?>
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-lock"></i> Private
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="report-actions">
                                        <a href="/admin/nominal-roll/load-report/<?= $report['id'] ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-play me-1"></i> Load
                                        </a>
                                        <a href="/admin/nominal-roll/delete-report/<?= $report['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirmDeleteReport('<?= htmlspecialchars($report['report_name']) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="report-fields">
                                    <?php 
                                    $fieldLabels = [];
                                    foreach ($availableFields as $category) {
                                        foreach ($category['fields'] as $key => $label) {
                                            $fieldLabels[$key] = $label;
                                        }
                                    }
                                    $displayFields = array_slice($report['selected_fields'], 0, 5);
                                    foreach ($displayFields as $field): ?>
                                    <span class="field-tag"><?= htmlspecialchars($fieldLabels[$field] ?? $field) ?></span>
                                    <?php endforeach; ?>
                                    <?php if (count($report['selected_fields']) > 5): ?>
                                    <span class="badge badge-primary">+<?= count($report['selected_fields']) - 5 ?> more</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Save Report Modal -->
    <div class="modal fade" id="saveReportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="saveReportForm" method="POST" action="/admin/nominal-roll/save-report">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-save me-2"></i> Save Report Configuration
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        <input type="hidden" name="csrf_token_time" value="<?php echo $_SESSION['csrf_token_time'] ?? ''; ?>">
                        <input type="hidden" name="selected_fields" id="saveSelectedFields">
                        <input type="hidden" name="filters" id="saveFilters">
                        <input type="hidden" name="sort_order" id="saveSortOrder">
                        
                        <div class="mb-3">
                            <label for="report_name" class="form-label">Report Name</label>
                            <input type="text" class="form-control" id="report_name" name="report_name" 
                                   placeholder="e.g., Active Staff by Department" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_public" value="1" id="isPublic">
                            <label class="form-check-label" for="isPublic">
                                <i class="fas fa-globe me-1"></i> Make this report public (visible to all users)
                            </label>
                        </div>
                        <div class="alert alert-light">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            This saves your current field selection, filters, and sort order for future use.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap & JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let totalFields = <?= $totalFields ?>;
        let currentReportData = null; // Store current report data for export
        let fieldCache = {}; // Cache for field data
        let lastRequestTime = 0;
        const REQUEST_DELAY = 500; // Debounce delay in ms
        let previewGenerationInProgress = false;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize counts
            updateCounts();
            updateFilterCount();
            document.getElementById('totalFieldsCount').textContent = totalFields;
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Check if there's a preview in session
            checkForPreview();
            
            // Set up save report form
            document.getElementById('saveReportForm').addEventListener('submit', function(e) {
                const reportName = document.getElementById('report_name').value.trim();
                if (!reportName) {
                    e.preventDefault();
                    showAlert('Please enter a report name', 'warning');
                    return false;
                }
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
                submitBtn.disabled = true;
                
                return true;
            });
        });
        
        // Check if preview data exists
        function checkPreviewDataExists() {
            // Check if we have preview data in localStorage or if preview panel is visible
            const previewPanel = document.getElementById('previewPanel');
            const hasLocalPreview = localStorage.getItem('lastPreviewData');
            
            return previewPanel.classList.contains('show') || hasLocalPreview;
        }
        
        // Check if there's preview data in session
        function checkForPreview() {
            // Check if we have preview data from a previous generation
            if (localStorage.getItem('lastPreviewData')) {
                try {
                    const previewData = JSON.parse(localStorage.getItem('lastPreviewData'));
                    showPreviewWithData(previewData);
                } catch (e) {
                    console.log('No valid preview data found');
                }
            }
        }
        
        // Field selection functions
        function selectAllFields() {
            document.querySelectorAll('.field-checkbox').forEach(cb => {
                cb.checked = true;
            });
            updateCounts();
            showAlert('All fields selected', 'success');
        }
        
        function selectDefaultFields() {
            const defaults = <?= json_encode($defaultFields) ?>;
            document.querySelectorAll('.field-checkbox').forEach(cb => {
                cb.checked = defaults.includes(cb.value);
            });
            updateCounts();
            showAlert('Default fields selected', 'success');
        }
        
        function clearAllFields() {
            document.querySelectorAll('.field-checkbox').forEach(cb => {
                cb.checked = false;
            });
            updateCounts();
            showAlert('All fields cleared', 'info');
        }
        
        function toggleCategory(categoryId) {
            const fieldsDiv = document.getElementById('fields_' + categoryId);
            const icon = document.getElementById('icon_' + categoryId);
            
            if (fieldsDiv.classList.contains('show')) {
                fieldsDiv.classList.remove('show');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            } else {
                fieldsDiv.classList.add('show');
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            }
        }
        
        function toggleCategoryCheckbox(categoryId) {
            const categoryCheckbox = document.getElementById('cat_' + categoryId);
            const checkboxes = document.querySelectorAll('.field-checkbox[data-category="' + categoryId + '"]');
            
            checkboxes.forEach(cb => {
                cb.checked = categoryCheckbox.checked;
            });
            updateCounts();
        }
        
        // Optimize updateCounts function
        function updateCounts() {
            // Debounce rapid updates
            const now = Date.now();
            if (now - lastRequestTime < 100) {
                return;
            }
            lastRequestTime = now;
            
            // Use requestAnimationFrame for smooth UI updates
            requestAnimationFrame(() => {
                const selectedCount = document.querySelectorAll('.field-checkbox:checked').length;
                
                // Update counts
                document.getElementById('selectedFieldsCount').textContent = selectedCount;
                document.getElementById('selectedFieldsBadge').textContent = selectedCount + ' selected';
                
                // Update progress
                const progressPercent = totalFields > 0 ? Math.round((selectedCount / totalFields) * 100) : 0;
                document.getElementById('fieldProgress').style.width = progressPercent + '%';
                document.getElementById('fieldProgressText').textContent = progressPercent + '% selected';
                
                // Update category counts efficiently
                const categories = <?= json_encode(array_keys($availableFields)) ?>;
                categories.forEach(categoryId => {
                    const checkboxes = document.querySelectorAll('.field-checkbox[data-category="' + categoryId + '"]');
                    const selectedInCategory = Array.from(checkboxes).filter(cb => cb.checked).length;
                    document.getElementById('count_' + categoryId).textContent = selectedInCategory;
                    
                    // Update category checkbox state
                    const categoryCheckbox = document.getElementById('cat_' + categoryId);
                    if (selectedInCategory === 0) {
                        categoryCheckbox.checked = false;
                        categoryCheckbox.indeterminate = false;
                    } else if (selectedInCategory === checkboxes.length) {
                        categoryCheckbox.checked = true;
                        categoryCheckbox.indeterminate = false;
                    } else {
                        categoryCheckbox.checked = true;
                        categoryCheckbox.indeterminate = true;
                    }
                });
            });
        }
        
        // Update filter count
        function updateFilterCount() {
            let activeCount = 0;
            const activeFilters = [];
            
            // Check search
            const searchValue = document.querySelector('[name="search"]').value.trim();
            if (searchValue) {
                activeCount++;
                activeFilters.push('Search');
            }
            
            // Check filter selects
            document.querySelectorAll('.filter-select').forEach(select => {
                if (select.value) {
                    activeCount++;
                    const filterName = select.name.replace('filter_', '').replace('_', ' ');
                    activeFilters.push(filterName.charAt(0).toUpperCase() + filterName.slice(1));
                }
            });
            
            // Update count
            document.getElementById('activeFiltersCount').textContent = activeCount;
            
            // Update preview
            const preview = document.getElementById('activeFiltersPreview');
            if (activeFilters.length > 0) {
                preview.textContent = activeFilters.slice(0, 3).join(', ');
                if (activeFilters.length > 3) {
                    preview.textContent += ` +${activeFilters.length - 3} more`;
                }
            } else {
                preview.textContent = 'No filters applied';
            }
        }
        
        // Optimize generatePreview with debouncing
        async function generatePreview() {
            if (previewGenerationInProgress) {
                showAlert('Please wait for the current preview to finish', 'info');
                return;
            }
            
            const selectedCount = document.querySelectorAll('.field-checkbox:checked').length;
            
            if (selectedCount === 0) {
                showAlert('Please select at least one field to include in the report.', 'warning');
                return false;
            }
            
            previewGenerationInProgress = true;
            const submitBtn = document.getElementById('generateBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generating...';
            submitBtn.disabled = true;
            
            try {
                // Collect data efficiently
                const formData = new FormData(document.getElementById('reportForm'));
                
                // Show loading state
                document.getElementById('previewPanel').classList.add('show');
                document.getElementById('previewLoading').classList.add('show');
                
                // Use AbortController for timeout
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
                
                const response = await fetch('/admin/nominal-roll/generate-preview', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                    signal: controller.signal
                });
                
                clearTimeout(timeoutId);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    currentReportData = result;
                    // Store preview data in localStorage
                    localStorage.setItem('lastPreviewData', JSON.stringify(result));
                    localStorage.setItem('lastPreviewId', Date.now()); // Store timestamp as ID
                    
                    showPreviewWithData(result);
                    updatePreviewStats(result);
                    showAlert(`Preview generated: Showing ${result.previewRecords || 0} of ${result.totalRecords || 0} records`, 'success');
                } else {
                    throw new Error(result.error || 'Failed to generate preview');
                }
                
            } catch (error) {
                console.error('Preview generation error:', error);
                if (error.name === 'AbortError') {
                    showAlert('Preview generation timed out. Please try with fewer filters.', 'warning');
                } else {
                    showAlert('Error generating preview: ' + error.message, 'danger');
                    fallbackToSampleData();
                }
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                document.getElementById('previewLoading').classList.remove('show');
                previewGenerationInProgress = false;
                prepareSaveData();
            }
        }
        
        // Fallback to sample data if AJAX fails
        function fallbackToSampleData() {
            // Get selected fields
            const selectedFields = [];
            document.querySelectorAll('.field-checkbox:checked').forEach(cb => {
                selectedFields.push(cb.value);
            });
            
            // Get field labels
            const fieldLabels = {};
            selectedFields.forEach(field => {
                const label = document.querySelector(`label[for="field_${field}"]`)?.textContent || 
                             field.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                fieldLabels[field] = label.replace('Default', '').trim();
            });
            
            // Get filter values
            const filters = {
                search: document.querySelector('[name="search"]').value,
                state: document.querySelector('[name="filter_state"]').value,
                department: document.querySelector('[name="filter_department"]').value,
                grade_level: document.querySelector('[name="filter_grade_level"]').value,
                sex: document.querySelector('[name="filter_sex"]').value,
                rank: document.querySelector('[name="filter_rank"]').value,
                status: document.querySelector('[name="filter_status"]').value || 'active'
            };
            
            const sortOrder = document.querySelector('[name="sort_order"]').value || 'surname_asc';
            
            // Create sample data as fallback
            const sampleData = [];
            for (let i = 1; i <= 10; i++) {
                const row = { id: i };
                selectedFields.forEach(field => {
                    // Try to get real field types based on field name
                    if (field.includes('date')) {
                        row[field] = '2024-01-' + (i < 10 ? '0' + i : i);
                    } else if (field === 'sex') {
                        row[field] = i % 2 === 0 ? 'Male' : 'Female';
                    } else if (field === 'employee_number') {
                        row[field] = 'EMP' + (20240000 + i);
                    } else if (field.includes('status')) {
                        row[field] = i % 3 === 0 ? 'Active' : (i % 3 === 1 ? 'Inactive' : 'Pending');
                    } else if (field === 'grade_level') {
                        row[field] = Math.floor(Math.random() * 17) + 1;
                    } else if (field === 'rank') {
                        const ranks = ['Director', 'Manager', 'Supervisor', 'Officer', 'Assistant', 'Lecturer'];
                        row[field] = ranks[i % ranks.length];
                    } else if (field === 'state') {
                        const states = ['Lagos', 'Abuja', 'Rivers', 'Kano', 'Oyo', 'Kaduna'];
                        row[field] = states[i % states.length];
                    } else {
                        row[field] = 'Sample ' + field.charAt(0).toUpperCase() + field.slice(1) + ' ' + i;
                    }
                });
                sampleData.push(row);
            }
            
            const previewData = {
                fields: selectedFields,
                fieldLabels: fieldLabels,
                data: sampleData,
                fullData: sampleData,
                totalRecords: 100,
                previewRecords: 10,
                config: {
                    selected_fields: selectedFields,
                    sort_order: sortOrder,
                    filters: filters
                }
            };
            
            // Store in global variable for export
            currentReportData = previewData;
            // Store in localStorage
            localStorage.setItem('lastPreviewData', JSON.stringify(previewData));
            localStorage.setItem('lastPreviewId', Date.now());
            
            showPreviewWithData(previewData);
            showAlert('Showing sample data. Real data could not be loaded.', 'warning');
        }
        
        // Show preview with data
        function showPreviewWithData(previewData) {
            const previewContent = document.getElementById('previewContent');
            
            // Create table HTML
            let tableHTML = `
                <div class="table-responsive">
                    <table class="preview-table">
                        <thead>
                            <tr>
                                <th width="50" class="text-center">#</th>
            `;
            
            // Add headers
            previewData.fields.forEach(field => {
                tableHTML += `<th>${previewData.fieldLabels[field] || field}</th>`;
            });
            
            tableHTML += `</tr></thead><tbody>`;
            
            // Check if we have real data
            if (previewData.data && previewData.data.length > 0) {
                // Add data rows
                previewData.data.forEach((row, index) => {
                    tableHTML += `<tr><td class="text-muted text-center fw-bold">${index + 1}</td>`;
                    
                    previewData.fields.forEach(field => {
                        let value = row[field] || '';
                        
                        // Format dates
                        if (field.includes('date')) {
                            if (value && value !== '0000-00-00' && value !== '0000-00-00 00:00:00') {
                                try {
                                    const date = new Date(value);
                                    value = date.toLocaleDateString('en-GB');
                                } catch (e) {
                                    value = value;
                                }
                            } else {
                                value = '<span class="text-muted fw-bold">-</span>';
                            }
                        }
                        
                        // Format gender - ENHANCED for better visibility
                        else if (field === 'sex') {
                            if (value === 'M' || value.toLowerCase() === 'male') {
                                value = '<span class="badge badge-male badge-contrast"><i class="fas fa-mars me-1"></i>Male</span>';
                            } else if (value === 'F' || value.toLowerCase() === 'female') {
                                value = '<span class="badge badge-female badge-contrast"><i class="fas fa-venus me-1"></i>Female</span>';
                            } else {
                                value = '<span class="text-muted fw-bold">-</span>';
                            }
                        }
                        
                        // Format status - ENHANCED for better visibility
                        else if (field.includes('status')) {
                            if (value.toLowerCase() === 'active') {
                                value = '<span class="badge badge-active badge-contrast"><i class="fas fa-check-circle me-1"></i>Active</span>';
                            } else if (value.toLowerCase() === 'inactive') {
                                value = '<span class="badge badge-inactive badge-contrast"><i class="fas fa-times-circle me-1"></i>Inactive</span>';
                            } else if (value.toLowerCase() === 'pending') {
                                value = '<span class="badge badge-warning badge-contrast"><i class="fas fa-clock me-1"></i>Pending</span>';
                            }
                        }
                        
                        // Format employee number
                        else if (field === 'employee_number') {
                            value = `<strong class="text-primary">${value}</strong>`;
                        }
                        
                        // Format grade level - ENHANCED for better visibility
                        else if (field === 'grade_level') {
                            value = `<span class="badge badge-grade badge-contrast">GL ${value}</span>`;
                        }
                        
                        // Format rank
                        else if (field === 'rank') {
                            value = `<span class="fw-bold">${value}</span>`;
                        }
                        
                        // Truncate long text
                        else if (value && value.length > 30) {
                            value = `<span title="${value}" class="text-truncate d-inline-block" style="max-width: 200px;">${value}</span>`;
                        }
                        
                        // Handle empty values
                        else if (!value) {
                            value = '<span class="text-muted fw-bold">-</span>';
                        }
                        
                        // Add CSS classes based on field type
                        let cellClass = '';
                        if (field === 'sex') cellClass = 'gender-cell';
                        if (field.includes('status')) cellClass = 'status-cell';
                        
                        tableHTML += `<td class="${cellClass}">${value}</td>`;
                    });
                    
                    tableHTML += `</tr>`;
                });
            } else {
                // No data rows
                tableHTML += `
                    <tr>
                        <td colspan="${previewData.fields.length + 1}" class="text-center py-5">
                            <i class="fas fa-database fa-3x text-muted mb-3"></i>
                            <p class="text-muted fw-bold">No records found with current filters</p>
                            <button class="btn btn-primary mt-2" onclick="clearFilters()">
                                <i class="fas fa-filter me-1"></i> Clear Filters
                            </button>
                        </td>
                    </tr>
                `;
            }
            
            tableHTML += `</tbody></table>`;
            
            // Add summary with better visibility
            tableHTML += `
                <div class="mt-4">
                    <div class="alert ${previewData.totalRecords > 0 ? 'alert-info' : 'alert-warning'} border-0 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Preview Summary:</strong> Showing ${previewData.previewRecords || 0} of ${previewData.totalRecords || 0} records.
                                ${previewData.totalRecords > 10 ? 
                                    '<span class="text-muted ms-2">(Showing first 10 records only)</span>' : 
                                    ''}
                            </div>
                            <div class="mt-2 mt-md-0">
                                ${previewData.totalRecords > 10 ? 
                                    '<button class="btn btn-primary btn-sm" onclick="submitFullReport()">' +
                                    '<i class="fas fa-external-link-alt me-1"></i> Generate Full Report' +
                                    '</button>' : 
                                    ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            previewContent.innerHTML = tableHTML;
            
            // Show the preview panel if it's not already shown
            document.getElementById('previewPanel').classList.add('show');
            
            // Scroll to preview panel on mobile
            if (window.innerWidth < 768) {
                document.getElementById('previewPanel').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
        
        // Helper function to clear filters
        function clearFilters() {
            document.querySelectorAll('.filter-select').forEach(select => {
                select.value = '';
            });
            document.querySelector('[name="search"]').value = '';
            updateFilterCount();
            showAlert('Filters cleared', 'info');
        }
        
        // Update preview statistics
        function updatePreviewStats(previewData) {
            if (previewData.statistics) {
                // Update the stats cards with real data
                const statsContainer = document.querySelector('.stats-container');
                if (statsContainer && previewData.totalRecords !== undefined) {
                    // Update total records count
                    const totalRecordsEl = statsContainer.querySelector('.stat-card:nth-child(1) h3');
                    if (totalRecordsEl) {
                        totalRecordsEl.textContent = previewData.totalRecords;
                    }
                    
                    // Update other stats if available
                    if (previewData.statistics.summary && previewData.statistics.summary.by_sex) {
                        const genderStats = previewData.statistics.summary.by_sex;
                        // You can update gender-specific stats here
                    }
                }
            }
        }
        
        // Submit full report form
        function submitFullReport() {
            document.getElementById('reportForm').submit();
        }
        
        // Prepare data for saving report
        function prepareSaveData() {
            // Get selected fields
            const selectedFields = [];
            document.querySelectorAll('.field-checkbox:checked').forEach(cb => {
                selectedFields.push(cb.value);
            });
            document.getElementById('saveSelectedFields').value = JSON.stringify(selectedFields);
            
            // Get filters
            const filters = {
                search: document.querySelector('[name="search"]').value,
                state: document.querySelector('[name="filter_state"]').value,
                department: document.querySelector('[name="filter_department"]').value,
                grade_level: document.querySelector('[name="filter_grade_level"]').value,
                sex: document.querySelector('[name="filter_sex"]').value,
                rank: document.querySelector('[name="filter_rank"]').value,
                status: document.querySelector('[name="filter_status"]').value
            };
            document.getElementById('saveFilters').value = JSON.stringify(filters);
            
            // Get sort order
            document.getElementById('saveSortOrder').value = document.querySelector('[name="sort_order"]').value;
        }
        
        // Export to Excel - UPDATED VERSION to use preview export routes
        function exportExcel() {
            try {
                const exportBtn = document.querySelector('[onclick="exportExcel()"]');
                const originalBtnText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
                exportBtn.disabled = true;

                // Check if preview has been generated
                const previewPanel = document.getElementById('previewPanel');
                if (!previewPanel.classList.contains('show')) {
                    showAlert('Please generate a preview first before exporting', 'warning');
                    exportBtn.innerHTML = originalBtnText;
                    exportBtn.disabled = false;
                    return;
                }

                // Check if preview data exists
                if (!checkPreviewDataExists()) {
                    showAlert('No preview data available. Please generate a preview first.', 'warning');
                    exportBtn.innerHTML = originalBtnText;
                    exportBtn.disabled = false;
                    return;
                }

                // Simple redirect to export endpoint
                window.location.href = '/admin/nominal-roll/export-preview-excel';
                
                // Reset button after delay
                setTimeout(() => {
                    exportBtn.innerHTML = originalBtnText;
                    exportBtn.disabled = false;
                }, 3000);

            } catch (error) {
                console.error('Excel export error:', error);
                showAlert('Error exporting to Excel: ' + error.message, 'danger');
                
                // Reset button
                const exportBtn = document.querySelector('[onclick="exportExcel()"]');
                exportBtn.innerHTML = '<i class="fas fa-file-excel me-1"></i> Excel';
                exportBtn.disabled = false;
            }
        }

        // Export to CSV - UPDATED VERSION to use preview export routes
        function exportCSV() {
            try {
                const exportBtn = document.querySelector('[onclick="exportCSV()"]');
                const originalBtnText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
                exportBtn.disabled = true;

                // Check if preview has been generated
                const previewPanel = document.getElementById('previewPanel');
                if (!previewPanel.classList.contains('show')) {
                    showAlert('Please generate a preview first before exporting', 'warning');
                    exportBtn.innerHTML = originalBtnText;
                    exportBtn.disabled = false;
                    return;
                }

                // Check if preview data exists
                if (!checkPreviewDataExists()) {
                    showAlert('No preview data available. Please generate a preview first.', 'warning');
                    exportBtn.innerHTML = originalBtnText;
                    exportBtn.disabled = false;
                    return;
                }

                // Simple redirect to export endpoint
                window.location.href = '/admin/nominal-roll/export-preview-csv';
                
                // Reset button after delay
                setTimeout(() => {
                    exportBtn.innerHTML = originalBtnText;
                    exportBtn.disabled = false;
                }, 3000);

            } catch (error) {
                console.error('CSV export error:', error);
                showAlert('Error exporting to CSV: ' + error.message, 'danger');
                
                // Reset button
                const exportBtn = document.querySelector('[onclick="exportCSV()"]');
                exportBtn.innerHTML = '<i class="fas fa-file-csv me-1"></i> CSV';
                exportBtn.disabled = false;
            }
        }
        
        // Hide preview
        function hidePreview() {
            document.getElementById('previewPanel').classList.remove('show');
        }
        
        // Modal functions
        function showSaveModal() {
            const selectedCount = document.querySelectorAll('.field-checkbox:checked').length;
            
            if (selectedCount === 0) {
                showAlert('Please select at least one field before saving.', 'warning');
                return;
            }
            
            prepareSaveData();
            
            // Set default report name
            const now = new Date();
            const reportName = `Report ${now.toLocaleDateString()} ${now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`;
            document.getElementById('report_name').value = reportName;
            
            // Show modal
            new bootstrap.Modal(document.getElementById('saveReportModal')).show();
        }
        
        // Report functions
        function confirmDeleteReport(reportName) {
            return confirm(`Are you sure you want to delete the report "${reportName}"? This action cannot be undone.`);
        }
        
        function refreshSavedReports() {
            const btn = document.querySelector('[onclick="refreshSavedReports()"]');
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            setTimeout(() => {
                btn.innerHTML = originalIcon;
                showAlert('Saved reports refreshed', 'success');
            }, 1000);
        }
        
        // Alert function
        function showAlert(message, type = 'info') {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 1050;
                min-width: 300px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border: 2px solid;
            `;
            
            // Set icon based on type
            let icon = 'info-circle';
            if (type === 'success') icon = 'check-circle';
            if (type === 'warning') icon = 'exclamation-triangle';
            if (type === 'danger') icon = 'times-circle';
            
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${icon} me-3 fs-5"></i>
                    <div class="flex-grow-1 fw-bold">${message}</div>
                    <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Add to body
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
        
        // Handle window resize for better responsiveness
        window.addEventListener('resize', function() {
            // Update stats layout on resize
            const statsContainer = document.querySelector('.stats-container');
            if (statsContainer) {
                if (window.innerWidth < 768) {
                    statsContainer.style.gridTemplateColumns = '1fr';
                } else if (window.innerWidth < 992) {
                    statsContainer.style.gridTemplateColumns = 'repeat(auto-fit, minmax(180px, 1fr))';
                } else {
                    statsContainer.style.gridTemplateColumns = 'repeat(auto-fit, minmax(200px, 1fr))';
                }
            }
        });
    </script>
</body>
</html>