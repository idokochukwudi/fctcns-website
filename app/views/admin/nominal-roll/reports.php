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
        
        /* Enhanced High Contrast Text Classes */
        .high-contrast-text {
            color: #212529 !important;
            font-weight: 700 !important;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
            letter-spacing: 0.02em;
        }
        
        /* Universal badge styling for maximum visibility */
        .preview-badge {
            padding: 6px 12px !important;
            font-weight: 700 !important;
            border-radius: 4px;
            font-size: 12px !important;
            border: 2px solid rgba(0, 0, 0, 0.25) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
            min-width: 85px;
            text-align: center;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 5px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            font-size: 0.75rem !important;
        }
        
        /* Enhanced Status Badges */
        .badge-status {
            composes: preview-badge;
        }
        
        .badge-status-active {
            background: linear-gradient(135deg, #28a745, #1e7e34) !important;
            color: #ffffff !important;
            border-color: #155724 !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }
        
        .badge-status-inactive {
            background: linear-gradient(135deg, #ffc107, #d39e00) !important;
            color: #000000 !important;
            border-color: #856404 !important;
            font-weight: 800 !important;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
        }
        
        .badge-status-retired {
            background: linear-gradient(135deg, #6c757d, #545b62) !important;
            color: #ffffff !important;
            border-color: #343a40 !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }
        
        .badge-status-draft {
            background: linear-gradient(135deg, #17a2b8, #117a8b) !important;
            color: #ffffff !important;
            border-color: #0c525d !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }
        
        /* License Status Badges */
        .badge-license {
            composes: preview-badge;
        }
        
        .badge-license-active {
            background: linear-gradient(135deg, #28a745, #1e7e34) !important;
            color: #ffffff !important;
            border-color: #155724 !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }
        
        .badge-license-expired {
            background: linear-gradient(135deg, #dc3545, #bd2130) !important;
            color: #ffffff !important;
            border-color: #721c24 !important;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.5);
        }
        
        .badge-license-pending {
            background: linear-gradient(135deg, #ffc107, #d39e00) !important;
            color: #000000 !important;
            border-color: #856404 !important;
            font-weight: 800 !important;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
        }
        
        .badge-license-not-applicable {
            background: linear-gradient(135deg, #6c757d, #545b62) !important;
            color: #ffffff !important;
            border-color: #343a40 !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }
        
        /* Gender Badges */
        .badge-gender {
            composes: preview-badge;
            min-width: 75px;
        }
        
        .badge-gender-male {
            background: linear-gradient(135deg, #2c5aa0, #1e3a6f) !important;
            color: #ffffff !important;
            border-color: #0d1b2a !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }
        
        .badge-gender-female {
            background: linear-gradient(135deg, #dc3545, #bd2130) !important;
            color: #ffffff !important;
            border-color: #721c24 !important;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.5);
        }
        
        /* Grade Level Badges */
        .badge-grade {
            background: linear-gradient(135deg, #17a2b8, #117a8b) !important;
            color: #ffffff !important;
            border: 2px solid #0c525d !important;
            font-weight: 800 !important;
            padding: 5px 10px !important;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            min-width: 60px;
            text-align: center;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }
        
        /* Employee Number - High contrast */
        .employee-number {
            color: #000000 !important;
            font-weight: 800 !important;
            background: linear-gradient(135deg, #e9ecef, #dee2e6);
            padding: 4px 8px;
            border-radius: 3px;
            border: 1px solid #adb5bd;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }
        
        /* Regular text cells with enhanced visibility */
        .preview-table td {
            color: #212529 !important;
            font-weight: 500;
            position: relative;
        }
        
        /* Add subtle background to all cells for better contrast */
        .preview-table tbody td {
            background-color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .preview-table tbody tr:nth-child(even) td {
            background-color: rgba(248, 249, 250, 0.9) !important;
        }
        
        /* Status and license cells with stronger backgrounds */
        .status-cell, .license-cell {
            background-color: rgba(0, 0, 0, 0.05) !important;
            position: relative;
        }
        
        .preview-table tbody tr:nth-child(even) .status-cell,
        .preview-table tbody tr:nth-child(even) .license-cell {
            background-color: rgba(0, 0, 0, 0.08) !important;
        }
        
        /* Date cells with distinct styling */
        .date-cell {
            font-weight: 600 !important;
            color: #2c3e50 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 4px 8px;
            border-radius: 3px;
            border: 1px solid #dee2e6;
        }
        
        /* Empty/placeholder cells */
        .empty-cell {
            color: #6c757d !important;
            font-weight: 600 !important;
            font-style: italic;
            background-color: rgba(108, 117, 125, 0.1) !important;
            padding: 4px 8px;
            border-radius: 3px;
            border: 1px dashed #adb5bd;
        }
        
        /* Column headers with better contrast */
        .preview-table thead th {
            background: linear-gradient(135deg, #2c3e50, #1c2833) !important;
            color: #ffffff !important;
            border-bottom: 3px solid #2c5aa0 !important;
            font-weight: 700 !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 12px 10px !important;
        }
        
        /* Table row hover with visibility */
        .preview-table tbody tr:hover td {
            background-color: rgba(44, 90, 160, 0.15) !important;
            transform: translateX(2px);
            transition: var(--transition);
        }
        
        .preview-table tbody tr:hover .status-cell,
        .preview-table tbody tr:hover .license-cell {
            background-color: rgba(44, 90, 160, 0.2) !important;
        }
        
        .main-container {
            padding: 15px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }
        
        /* Enhanced Header Styles */
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
        
        /* Enhanced Stats Cards */
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
        
        .stat-card h3 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 5px;
            color: var(--secondary-color);
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
            line-height: 1;
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
        
        /* Main Content Layout */
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
        
        /* Configuration Panel */
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
        
        /* Field Categories */
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
        
        /* Filters Section */
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
        
        /* Sorting Section */
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
        
        /* Action Buttons */
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
        
        /* Preview Panel */
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
        
        .preview-content {
            padding: 20px;
            min-height: 300px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        /* Enhanced Preview Table */
        .preview-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
            background: white;
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
        
        /* Saved Reports */
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
        
        /* Badges */
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
        
        /* Quick Actions */
        .quick-actions {
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid var(--border-color);
        }
        
        .quick-actions h6 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .quick-actions .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        /* Mobile-specific optimizations */
        @media (max-width: 576px) {
            .main-container {
                padding: 10px;
            }
            
            .page-header {
                padding: 15px;
            }
            
            .config-panel,
            .saved-reports-section {
                padding: 15px;
            }
            
            .category-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .preview-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
        }
        
        /* Print styles */
        @media print {
            .btn,
            .preview-actions,
            .config-panel,
            .saved-reports-section,
            .stats-container,
            .page-header .btn,
            .quick-actions {
                display: none !important;
            }
            
            .preview-panel {
                box-shadow: none !important;
                border: 1px solid #000 !important;
                display: block !important;
                page-break-inside: avoid;
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

        <!-- Statistics Cards -->
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
                
                <!-- UPDATED FORM: Using the correct CSRF token variable -->
                <form id="reportForm" method="POST" action="/admin/nominal-roll/generate-report" target="_blank">
                    <input type="hidden" name="csrf_token" value="<?php echo $this->data['csrf_token'] ?? ''; ?>">
                    
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
                    
                    <!-- UPDATED: Filters Section with Qualification Filters -->
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
                            
                            <!-- Employment Status Filter -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_status" class="form-label">Employment Status</label>
                                    <select name="filter_status" id="filter_status" class="form-select filter-select" onchange="updateFilterCount()">
                                        <option value="">All Employment Status</option>
                                        <?php if (isset($filterOptions['status_options']) && is_array($filterOptions['status_options'])): ?>
                                            <?php foreach ($filterOptions['status_options'] as $status_option): ?>
                                                <option value="<?= htmlspecialchars($status_option) ?>">
                                                    <?= htmlspecialchars($status_option) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="draft">Draft</option>
                                            <option value="retired">Retired</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- NMCN License Status Filter -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_nmcn_status" class="form-label">NMCN License Status</label>
                                    <select name="filter_nmcn_status" id="filter_nmcn_status" class="form-select filter-select" onchange="updateFilterCount()">
                                        <option value="">All NMCN Status</option>
                                        <?php if (isset($filterOptions['nmcn_status_options']) && is_array($filterOptions['nmcn_status_options'])): ?>
                                            <?php foreach ($filterOptions['nmcn_status_options'] as $nmcn_option): ?>
                                                <option value="<?= htmlspecialchars($nmcn_option) ?>">
                                                    <?= htmlspecialchars($nmcn_option) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="Active">Active</option>
                                            <option value="Expired">Expired</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Not Applicable">Not Applicable</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- TRCN License Status Filter -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_trcn_status" class="form-label">TRCN License Status</label>
                                    <select name="filter_trcn_status" id="filter_trcn_status" class="form-select filter-select" onchange="updateFilterCount()">
                                        <option value="">All TRCN Status</option>
                                        <?php if (isset($filterOptions['trcn_status_options']) && is_array($filterOptions['trcn_status_options'])): ?>
                                            <?php foreach ($filterOptions['trcn_status_options'] as $trcn_option): ?>
                                                <option value="<?= htmlspecialchars($trcn_option) ?>">
                                                    <?= htmlspecialchars($trcn_option) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="Active">Active</option>
                                            <option value="Expired">Expired</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Not Applicable">Not Applicable</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- NEW: Highest Qualification Filter -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_highest_qualification" class="form-label">Highest Qualification</label>
                                    <select name="filter_highest_qualification[]" id="filter_highest_qualification" 
                                            class="form-select filter-select" multiple onchange="updateFilterCount()"
                                            data-placeholder="Select qualification(s)" style="height: auto;">
                                        <option value="">Any Qualification</option>
                                        <?php if (isset($filterOptions['highest_qualification_options']) && is_array($filterOptions['highest_qualification_options'])): ?>
                                            <?php foreach ($filterOptions['highest_qualification_options'] as $qual): ?>
                                                <option value="<?= htmlspecialchars($qual) ?>">
                                                    <?= htmlspecialchars($qual) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="PhD">PhD</option>
                                            <option value="MSc">MSc</option>
                                            <option value="BSc">BSc</option>
                                            <option value="HND">HND</option>
                                            <option value="ND">ND</option>
                                            <option value="PGD">PGD</option>
                                            <option value="FSLC">FSLC</option>
                                        <?php endif; ?>
                                    </select>
                                    <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                                </div>
                            </div>

                            <!-- NEW: Professional Certification Filter -->
                            <div class="col-md-6">
                                <div class="filter-group">
                                    <label for="filter_professional_certification" class="form-label">Professional Certification</label>
                                    <select name="filter_professional_certification" id="filter_professional_certification" 
                                            class="form-select filter-select" onchange="updateFilterCount()">
                                        <option value="">Any Certification</option>
                                        <?php if (isset($filterOptions['professional_certification_options']) && is_array($filterOptions['professional_certification_options'])): ?>
                                            <?php foreach ($filterOptions['professional_certification_options'] as $cert): ?>
                                                <option value="<?= htmlspecialchars($cert) ?>">
                                                    <?= htmlspecialchars($cert) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="TRCN">TRCN</option>
                                            <option value="RN">RN</option>
                                            <option value="RM">RM</option>
                                            <option value="RPHN">RPHN</option>
                                            <option value="NMCN">NMCN</option>
                                        <?php endif; ?>
                                    </select>
                                    <small class="text-muted">e.g., TRCN, RN, RM, RPHN</small>
                                </div>
                            </div>

                            <!-- NEW: Additional Qualification Filter -->
                            <div class="col-12">
                                <div class="filter-group">
                                    <label for="filter_additional_qualification" class="form-label">Additional Qualification Contains</label>
                                    <input type="text" 
                                           id="filter_additional_qualification" 
                                           name="filter_additional_qualification" 
                                           class="form-control" 
                                           placeholder="e.g., PGDE, MSC, PGD"
                                           oninput="updateFilterCount()">
                                </div>
                            </div>
                            
                            <!-- NEW: Qualification Quick Filters Section -->
                            <div class="col-12 mt-3">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <i class="fas fa-graduation-cap me-2"></i> Quick Qualification Filters
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Highest Qualification:</label>
                                                <div class="btn-group-vertical w-100" role="group">
                                                    <button type="button" class="btn btn-outline-info text-start" onclick="applyQualificationFilter('highest_qualification', 'PhD')">
                                                        <i class="fas fa-user-graduate me-2"></i> PhD Holders
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info text-start" onclick="applyQualificationFilter('highest_qualification', 'MSc')">
                                                        <i class="fas fa-user-graduate me-2"></i> MSc Holders
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info text-start" onclick="applyQualificationFilter('highest_qualification', 'BSc')">
                                                        <i class="fas fa-user-graduate me-2"></i> BSc Holders
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info text-start" onclick="applyQualificationFilter('highest_qualification', 'HND')">
                                                        <i class="fas fa-user-graduate me-2"></i> HND Holders
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Professional Certifications:</label>
                                                <div class="btn-group-vertical w-100" role="group">
                                                    <button type="button" class="btn btn-outline-success text-start" onclick="applyQualificationFilter('professional_certifications', 'TRCN')">
                                                        <i class="fas fa-id-card me-2"></i> TRCN Licensed
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success text-start" onclick="applyQualificationFilter('professional_certifications', 'RN')">
                                                        <i class="fas fa-user-nurse me-2"></i> Registered Nurses
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success text-start" onclick="applyQualificationFilter('professional_certifications', 'RM')">
                                                        <i class="fas fa-user-nurse me-2"></i> Registered Midwives
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success text-start" onclick="applyQualificationFilter('professional_certifications', 'RPHN')">
                                                        <i class="fas fa-user-nurse me-2"></i> RPHN Certified
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Additional Qualification Search -->
                                        <div class="mt-3">
                                            <label class="form-label fw-bold">Search Additional Qualifications:</label>
                                            <div class="input-group">
                                                <input type="text" 
                                                       id="additionalQualSearch" 
                                                       class="form-control" 
                                                       placeholder="e.g., PGDE, Certificate, Diploma..."
                                                       onkeypress="if(event.keyCode==13) searchAdditionalQualifications()">
                                                <button class="btn btn-info" type="button" onclick="searchAdditionalQualifications()">
                                                    <i class="fas fa-search"></i> Search
                                                </button>
                                            </div>
                                            <small class="text-muted">Search in additional qualifications field</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions for Common Reports -->
                    <div class="quick-actions mt-3">
                        <h6><i class="fas fa-bolt"></i> Quick Reports:</h6>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="generateQualificationReport('professional_certification', 'TRCN')">
                                <i class="fas fa-id-card"></i> TRCN Holders
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="generateQualificationReport('professional_certification', 'RPHN')">
                                <i class="fas fa-user-nurse"></i> RPHN Holders
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="generateQualificationReport('highest_qualification', 'PhD')">
                                <i class="fas fa-graduation-cap"></i> PhD Holders
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="generateQualificationReport('highest_qualification', 'MSc')">
                                <i class="fas fa-graduation-cap"></i> MSc Holders
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="generateQualificationReport('professional_certification', 'RN')">
                                <i class="fas fa-user-nurse"></i> RN Holders
                            </button>
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
                            <option value="nmcn_status_asc">NMCN Status (A to Z)</option>
                            <option value="nmcn_status_desc">NMCN Status (Z to A)</option>
                            <option value="trcn_status_asc">TRCN Status (A to Z)</option>
                            <option value="trcn_status_desc">TRCN Status (Z to A)</option>
                            <option value="highest_qualification_asc">Highest Qualification (A to Z)</option>
                            <option value="highest_qualification_desc">Highest Qualification (Z to A)</option>
                        </select>
                    </div>
                    
                    <!-- Preview Limit Selector -->
                    <div class="mb-3">
                        <label for="previewLimit" class="form-label">Preview Records Limit</label>
                        <select name="preview_limit" id="previewLimit" class="form-select">
                            <option value="10">10 records</option>
                            <option value="20" selected>20 records</option>
                            <option value="30">30 records</option>
                            <option value="50">50 records</option>
                            <option value="100">100 records</option>
                            <option value="0">All records</option>
                        </select>
                        <small class="text-muted">Number of records to show in preview</small>
                    </div>
                    
                    <!-- Export Options -->
                    <div class="mb-3">
                        <h3><i class="fas fa-download"></i> Export Options</h3>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="auto_format" id="autoFormat" checked>
                            <label class="form-check-label" for="autoFormat">
                                Apply professional formatting
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_summary" id="includeSummary" checked>
                            <label class="form-check-label" for="includeSummary">
                                Include summary information
                            </label>
                        </div>
                        <small class="text-muted">Exports will be formatted for Excel with proper styling</small>
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
                            <button class="btn btn-sm btn-outline-primary" onclick="exportPDF()">
                                <i class="fas fa-file-pdf me-1"></i> PDF
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
                        <input type="hidden" name="csrf_token" value="<?php echo $this->data['csrf_token'] ?? ''; ?>">
                        <input type="hidden" name="selected_fields" id="saveSelectedFields">
                        <input type="hidden" name="filters" id="saveFilters">
                        <input type="hidden" name="sort_order" id="saveSortOrder">
                        <input type="hidden" name="excel_options" id="saveExcelOptions">
                        
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
                            This saves your current field selection, filters, sort order, and Excel format options for future use.
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
        let currentReportData = null;
        let previewGenerationInProgress = false;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize counts
            updateCounts();
            updateFilterCount();
            document.getElementById('totalFieldsCount').textContent = totalFields;
            
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
            const previewPanel = document.getElementById('previewPanel');
            const hasLocalPreview = localStorage.getItem('lastPreviewData');
            return previewPanel.classList.contains('show') || hasLocalPreview;
        }
        
        // Check if there's preview data in session
        function checkForPreview() {
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
        
        function updateCounts() {
            requestAnimationFrame(() => {
                const selectedCount = document.querySelectorAll('.field-checkbox:checked').length;
                
                // Update counts
                document.getElementById('selectedFieldsCount').textContent = selectedCount;
                document.getElementById('selectedFieldsBadge').textContent = selectedCount + ' selected';
                
                // Update progress
                const progressPercent = totalFields > 0 ? Math.round((selectedCount / totalFields) * 100) : 0;
                document.getElementById('fieldProgress').style.width = progressPercent + '%';
                document.getElementById('fieldProgressText').textContent = progressPercent + '% selected';
                
                // Update category counts
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
        
        // NEW: Function to apply qualification filter
        function applyQualificationFilter(type, value) {
            // Clear other filters first
            clearFilters();
            
            // Set the specific qualification filter
            if (type === 'highest_qualification') {
                const select = document.getElementById('filter_highest_qualification');
                if (select) {
                    Array.from(select.options).forEach(opt => opt.selected = false);
                    const option = Array.from(select.options).find(opt => opt.value === value);
                    if (option) option.selected = true;
                }
            } else if (type === 'professional_certifications') {
                document.getElementById('filter_professional_certification').value = value;
            }
            
            // Update counts and generate preview
            updateFilterCount();
            showAlert(`Filter applied: ${value} holders`, 'info');
            
            // Auto-generate preview
            setTimeout(() => generatePreview(), 500);
        }
        
        // NEW: Function to search in additional qualifications
        function searchAdditionalQualifications() {
            const searchTerm = document.getElementById('additionalQualSearch').value.trim();
            
            if (!searchTerm) {
                showAlert('Please enter a search term', 'warning');
                return;
            }
            
            // Clear other filters first
            clearFilters();
            
            // Set additional qualification filter
            document.getElementById('filter_additional_qualification').value = searchTerm;
            
            // Update counts and generate preview
            updateFilterCount();
            showAlert(`Searching for: ${searchTerm}`, 'info');
            
            // Auto-generate preview
            setTimeout(() => generatePreview(), 500);
        }
        
        // Update filter count function with new qualification filters
        function updateFilterCount() {
            let activeCount = 0;
            const activeFilters = [];
            
            // Check search
            const searchValue = document.querySelector('[name="search"]').value.trim();
            if (searchValue) {
                activeCount++;
                activeFilters.push('Search');
            }
            
            // Check main filter selects
            const filterSelects = [
                'filter_state',
                'filter_department', 
                'filter_grade_level',
                'filter_sex',
                'filter_rank',
                'filter_status',
                'filter_nmcn_status',
                'filter_trcn_status',
                'filter_professional_certification'
            ];
            
            filterSelects.forEach(filterName => {
                const select = document.querySelector(`[name="${filterName}"]`);
                if (select && select.value) {
                    activeCount++;
                    const displayName = filterName.replace('filter_', '')
                        .replace('_', ' ')
                        .replace(/\b\w/g, l => l.toUpperCase());
                    activeFilters.push(displayName);
                }
            });
            
            // Check highest qualification filter (multiple select)
            const highestQualSelect = document.getElementById('filter_highest_qualification');
            if (highestQualSelect) {
                const selectedOptions = Array.from(highestQualSelect.selectedOptions)
                    .filter(opt => opt.value)
                    .map(opt => opt.value);
                if (selectedOptions.length > 0) {
                    activeCount++;
                    activeFilters.push('Qualification: ' + selectedOptions.join(', '));
                }
            }
            
            // Check additional qualification filter
            const addQualInput = document.getElementById('filter_additional_qualification');
            if (addQualInput && addQualInput.value.trim()) {
                activeCount++;
                activeFilters.push('Add. Qual: ' + addQualInput.value.trim());
            }
            
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
        
        // Function to generate qualification-specific reports
        function generateQualificationReport(qualificationType, value) {
            const form = document.getElementById('reportForm');
            
            // Clear existing filters
            clearFilters();
            
            // Set the specific qualification filter
            if (qualificationType === 'highest_qualification') {
                const select = document.getElementById('filter_highest_qualification');
                Array.from(select.options).forEach(opt => opt.selected = false);
                const option = Array.from(select.options).find(opt => opt.value === value);
                if (option) option.selected = true;
            } else if (qualificationType === 'professional_certification') {
                document.getElementById('filter_professional_certification').value = value;
            }
            
            // Update counts and generate preview
            updateFilterCount();
            showAlert(`Filtered by ${qualificationType}: ${value}`, 'info');
            generatePreview();
        }
        
        // generatePreview function
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
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generating...';
            submitBtn.disabled = true;
            
            try {
                const form = document.getElementById('reportForm');
                
                if (!form) {
                    throw new Error('Report form not found');
                }
                
                const formData = new FormData(form);
                
                // Show loading state
                document.getElementById('previewPanel').classList.add('show');
                document.getElementById('previewLoading').classList.add('show');
                
                // Send AJAX request
                const response = await fetch('/admin/nominal-roll/generate-preview', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    currentReportData = result;
                    
                    // Store in localStorage for persistence
                    localStorage.setItem('lastPreviewData', JSON.stringify(result));
                    localStorage.setItem('lastPreviewId', Date.now());
                    
                    showPreviewWithData(result);
                    updatePreviewStats(result);
                    
                    // Show success message with record count
                    const totalRecords = result.totalRecords || 0;
                    const previewRecords = result.previewRecords || 0;
                    const previewLimit = result.previewLimit || 20;
                    
                    let message = `Preview generated: Showing ${previewRecords} of ${totalRecords} records`;
                    if (totalRecords > previewLimit) {
                        message += ` (First ${previewLimit} records shown)`;
                    }
                    showAlert(message, 'success');
                } else {
                    throw new Error(result.error || 'Failed to generate preview');
                }
                
            } catch (error) {
                console.error('Preview generation error:', error);
                
                if (error.message.includes('CSRF')) {
                    showAlert('CSRF token error. Please refresh the page and try again.', 'danger');
                } else if (error.message.includes('Network') || error.message.includes('Failed to fetch')) {
                    showAlert('Network error. Please check your connection.', 'danger');
                } else {
                    showAlert('Error generating preview: ' + error.message, 'danger');
                    fallbackToSampleData();
                }
            } finally {
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
                document.getElementById('previewLoading').classList.remove('show');
                previewGenerationInProgress = false;
                prepareSaveData();
            }
        }
        
        // Fallback to sample data function
        function fallbackToSampleData() {
            const selectedFields = [];
            document.querySelectorAll('.field-checkbox:checked').forEach(cb => {
                selectedFields.push(cb.value);
            });
            
            const fieldLabels = {};
            selectedFields.forEach(field => {
                const label = document.querySelector(`label[for="field_${field}"]`)?.textContent || 
                             field.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                fieldLabels[field] = label.replace('Default', '').trim();
            });
            
            const filters = {
                search: document.querySelector('[name="search"]').value,
                state: document.querySelector('[name="filter_state"]').value,
                department: document.querySelector('[name="filter_department"]').value,
                grade_level: document.querySelector('[name="filter_grade_level"]').value,
                sex: document.querySelector('[name="filter_sex"]').value,
                rank: document.querySelector('[name="filter_rank"]').value,
                status: document.querySelector('[name="filter_status"]').value || '',
                nmcn_status: document.querySelector('[name="filter_nmcn_status"]').value || '',
                trcn_status: document.querySelector('[name="filter_trcn_status"]').value || '',
                highest_qualification: Array.from(document.querySelector('[name="filter_highest_qualification[]"]')?.selectedOptions || []).map(opt => opt.value),
                professional_certification: document.querySelector('[name="filter_professional_certification"]').value || '',
                additional_qualification: document.querySelector('[name="filter_additional_qualification"]').value || ''
            };
            
            const sortOrder = document.querySelector('[name="sort_order"]').value || 'surname_asc';
            
            // Create sample data as fallback
            const sampleData = [];
            for (let i = 1; i <= 10; i++) {
                const row = { id: i };
                selectedFields.forEach(field => {
                    if (field.includes('date')) {
                        row[field] = '2024-01-' + (i < 10 ? '0' + i : i);
                    } else if (field === 'sex') {
                        row[field] = i % 2 === 0 ? 'Male' : 'Female';
                    } else if (field === 'employee_number') {
                        row[field] = 'EMP' + (20240000 + i);
                    } else if (field === 'nmcn_status') {
                        const nmcnFilter = filters.nmcn_status || 'Active';
                        row[field] = nmcnFilter;
                    } else if (field === 'trcn_status') {
                        const trcnFilter = filters.trcn_status || 'Active';
                        row[field] = trcnFilter;
                    } else if (field === 'status') {
                        const statusFilter = filters.status || 'active';
                        row[field] = statusFilter.charAt(0).toUpperCase() + statusFilter.slice(1);
                    } else if (field === 'grade_level') {
                        row[field] = Math.floor(Math.random() * 17) + 1;
                    } else if (field === 'rank') {
                        const ranks = ['Director', 'Manager', 'Supervisor', 'Officer', 'Assistant', 'Lecturer'];
                        row[field] = ranks[i % ranks.length];
                    } else if (field === 'state') {
                        const states = ['Lagos', 'Abuja', 'Rivers', 'Kano', 'Oyo', 'Kaduna'];
                        row[field] = states[i % states.length];
                    } else if (field === 'highest_qualification') {
                        const qualifications = ['PhD', 'MSc', 'BSc', 'HND', 'PGD'];
                        row[field] = qualifications[i % qualifications.length];
                    } else if (field === 'professional_certifications' || field === 'professional_certification') {
                        const certs = ['TRCN', 'RN', 'RM', 'RPHN', 'NMCN'];
                        row[field] = certs[i % certs.length];
                    } else if (field === 'additional_qualifications') {
                        row[field] = '[{"qualification":"Certificate in Nursing","year":"2010"},{"qualification":"Advanced Diploma","year":"2015"}]';
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
            
            currentReportData = previewData;
            localStorage.setItem('lastPreviewData', JSON.stringify(previewData));
            localStorage.setItem('lastPreviewId', Date.now());
            
            showPreviewWithData(previewData);
            showAlert('Showing sample data. Real data could not be loaded.', 'warning');
        }
        
        // showPreviewWithData function with enhanced formatting
        function showPreviewWithData(previewData) {
            const previewContent = document.getElementById('previewContent');
            
            const totalRecords = previewData.totalRecords || 0;
            const previewRecords = previewData.previewRecords || 0;
            const previewLimit = previewData.previewLimit || 20;
            
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
                                    value = `<span class="date-cell">${value}</span>`;
                                } catch (e) {
                                    value = `<span class="empty-cell">${value}</span>`;
                                }
                            } else {
                                value = '<span class="empty-cell">-</span>';
                            }
                        }
                        
                        // Format gender
                        else if (field === 'sex') {
                            if (value === 'M' || value.toLowerCase() === 'male') {
                                value = '<span class="preview-badge badge-gender badge-gender-male"><i class="fas fa-mars me-1"></i>Male</span>';
                            } else if (value === 'F' || value.toLowerCase() === 'female') {
                                value = '<span class="preview-badge badge-gender badge-gender-female"><i class="fas fa-venus me-1"></i>Female</span>';
                            } else {
                                value = '<span class="empty-cell">-</span>';
                            }
                        }
                        
                        // Format NMCN and TRCN license status
                        else if (field === 'nmcn_status' || field === 'trcn_status') {
                            const valueLower = value.toString().toLowerCase();
                            if (valueLower.includes('active')) {
                                value = '<span class="preview-badge badge-license badge-license-active"><i class="fas fa-id-card me-1"></i>Active</span>';
                            } else if (valueLower.includes('expired')) {
                                value = '<span class="preview-badge badge-license badge-license-expired"><i class="fas fa-calendar-times me-1"></i>Expired</span>';
                            } else if (valueLower.includes('pending')) {
                                value = '<span class="preview-badge badge-license badge-license-pending"><i class="fas fa-clock me-1"></i>Pending</span>';
                            } else if (valueLower.includes('not') && valueLower.includes('applicable')) {
                                value = '<span class="preview-badge badge-license badge-license-not-applicable"><i class="fas fa-ban me-1"></i>Not Applicable</span>';
                            } else if (valueLower.includes('renew')) {
                                value = '<span class="preview-badge badge-license" style="background: linear-gradient(135deg, #ffc107, #d39e00); color: #000; border-color: #856404;"><i class="fas fa-sync-alt me-1"></i>Renewal</span>';
                            } else {
                                value = `<span class="preview-badge badge-license" style="background: #6c757d; color: white; border-color: #545b62;">${value}</span>`;
                            }
                        }
                        
                        // Format employment status
                        else if (field === 'status') {
                            const valueLower = value.toString().toLowerCase();
                            if (valueLower.includes('active')) {
                                value = '<span class="preview-badge badge-status badge-status-active"><i class="fas fa-check-circle me-1"></i>Active</span>';
                            } else if (valueLower.includes('inactive')) {
                                value = '<span class="preview-badge badge-status badge-status-inactive"><i class="fas fa-times-circle me-1"></i>Inactive</span>';
                            } else if (valueLower.includes('pending')) {
                                value = '<span class="preview-badge badge-status badge-license-pending"><i class="fas fa-clock me-1"></i>Pending</span>';
                            } else if (valueLower.includes('retired')) {
                                value = '<span class="preview-badge badge-status badge-status-retired"><i class="fas fa-user-clock me-1"></i>Retired</span>';
                            } else if (valueLower.includes('draft')) {
                                value = '<span class="preview-badge badge-status badge-status-draft"><i class="fas fa-file-alt me-1"></i>Draft</span>';
                            } else if (valueLower.includes('suspended')) {
                                value = '<span class="preview-badge badge-status" style="background: linear-gradient(135deg, #dc3545, #bd2130); color: white; border-color: #721c24;"><i class="fas fa-ban me-1"></i>Suspended</span>';
                            } else {
                                value = `<span class="preview-badge badge-status" style="background: #6c757d; color: white; border-color: #545b62;">${value}</span>`;
                            }
                        }
                        
                        // Format employee number
                        else if (field === 'employee_number') {
                            value = `<span class="employee-number">${value}</span>`;
                        }
                        
                        // Format grade level
                        else if (field === 'grade_level') {
                            value = `<span class="badge-grade">GL ${value}</span>`;
                        }
                        
                        // Format highest qualification with styling
                        else if (field === 'highest_qualification') {
                            if (value) {
                                // Color code based on qualification level
                                let bgColor, textColor, borderColor;
                                
                                switch(value.toUpperCase()) {
                                    case 'PHD':
                                        bgColor = 'rgba(155, 89, 182, 0.15)'; // Purple
                                        textColor = '#9B59B6';
                                        borderColor = 'rgba(155, 89, 182, 0.3)';
                                        break;
                                    case 'MSC':
                                    case 'M.SC':
                                    case 'MASTERS':
                                        bgColor = 'rgba(52, 152, 219, 0.15)'; // Blue
                                        textColor = '#3498DB';
                                        borderColor = 'rgba(52, 152, 219, 0.3)';
                                        break;
                                    case 'BSC':
                                    case 'B.SC':
                                    case 'BACHELORS':
                                        bgColor = 'rgba(46, 204, 113, 0.15)'; // Green
                                        textColor = '#2ECC71';
                                        borderColor = 'rgba(46, 204, 113, 0.3)';
                                        break;
                                    case 'HND':
                                        bgColor = 'rgba(241, 196, 15, 0.15)'; // Yellow
                                        textColor = '#F1C40F';
                                        borderColor = 'rgba(241, 196, 15, 0.3)';
                                        break;
                                    default:
                                        bgColor = 'rgba(149, 165, 166, 0.15)'; // Gray
                                        textColor = '#95A5A6';
                                        borderColor = 'rgba(149, 165, 166, 0.3)';
                                }
                                
                                value = `<span style="font-weight: 700; color: ${textColor}; background: ${bgColor}; padding: 4px 8px; border-radius: 3px; border: 1px solid ${borderColor};">
                                            <i class="fas fa-graduation-cap me-1"></i>${value}
                                        </span>`;
                            } else {
                                value = '<span class="empty-cell">-</span>';
                            }
                        }
                        
                        // Format professional certifications with styling
                        else if (field === 'professional_certifications' || field === 'professional_certification') {
                            if (value) {
                                // Color code based on certification type
                                let bgColor, textColor, borderColor, icon;
                                
                                if (value.includes('TRCN')) {
                                    bgColor = 'rgba(231, 76, 60, 0.15)'; // Red
                                    textColor = '#E74C3C';
                                    borderColor = 'rgba(231, 76, 60, 0.3)';
                                    icon = 'fas fa-chalkboard-teacher';
                                } else if (value.includes('RN')) {
                                    bgColor = 'rgba(52, 152, 219, 0.15)'; // Blue
                                    textColor = '#3498DB';
                                    borderColor = 'rgba(52, 152, 219, 0.3)';
                                    icon = 'fas fa-user-nurse';
                                } else if (value.includes('RM')) {
                                    bgColor = 'rgba(155, 89, 182, 0.15)'; // Purple
                                    textColor = '#9B59B6';
                                    borderColor = 'rgba(155, 89, 182, 0.3)';
                                    icon = 'fas fa-baby';
                                } else if (value.includes('RPHN')) {
                                    bgColor = 'rgba(46, 204, 113, 0.15)'; // Green
                                    textColor = '#2ECC71';
                                    borderColor = 'rgba(46, 204, 113, 0.3)';
                                    icon = 'fas fa-user-md';
                                } else {
                                    bgColor = 'rgba(149, 165, 166, 0.15)'; // Gray
                                    textColor = '#95A5A6';
                                    borderColor = 'rgba(149, 165, 166, 0.3)';
                                    icon = 'fas fa-certificate';
                                }
                                
                                value = `<span style="font-weight: 700; color: ${textColor}; background: ${bgColor}; padding: 4px 8px; border-radius: 3px; border: 1px solid ${borderColor};">
                                            <i class="${icon} me-1"></i>${value}
                                        </span>`;
                            } else {
                                value = '<span class="empty-cell">-</span>';
                            }
                        }
                        
                        // Format additional qualifications
                        else if (field === 'additional_qualifications') {
                            try {
                                if (value && value !== '') {
                                    const quals = JSON.parse(value);
                                    if (Array.isArray(quals) && quals.length > 0) {
                                        const formattedQuals = quals.map((q, index) => {
                                            const qualName = q.qualification || 'Unknown Qualification';
                                            const year = q.year ? ` (${q.year})` : '';
                                            const badgeColor = index % 2 === 0 ? 'primary' : 'info';
                                            
                                            return `<span class="badge bg-${badgeColor} me-1 mb-1" style="font-size: 0.75rem;">
                                                        <i class="fas fa-award me-1"></i>${qualName}${year}
                                                    </span>`;
                                        }).join('');
                                        
                                        value = `<div class="d-flex flex-wrap">${formattedQuals}</div>`;
                                    } else {
                                        value = `<span style="font-weight: 500; color: #495057;">${value}</span>`;
                                    }
                                } else {
                                    value = '<span class="empty-cell">-</span>';
                                }
                            } catch (e) {
                                if (value && value !== '') {
                                    if (value.includes(',') || value.includes(';')) {
                                        const items = value.split(/[,;]/).map(item => item.trim()).filter(item => item);
                                        const formattedItems = items.map((item, index) => {
                                            const badgeColor = index % 2 === 0 ? 'secondary' : 'light text-dark';
                                            return `<span class="badge bg-${badgeColor} me-1 mb-1">${item}</span>`;
                                        }).join('');
                                        
                                        value = `<div class="d-flex flex-wrap">${formattedItems}</div>`;
                                    } else {
                                        value = `<span style="font-weight: 500; color: #495057;">${value}</span>`;
                                    }
                                } else {
                                    value = '<span class="empty-cell">-</span>';
                                }
                            }
                        }
                        
                        // Format rank with styling
                        else if (field === 'rank') {
                            value = `<span style="font-weight: 700; color: #2c3e50; background: #f8f9fa; padding: 4px 8px; border-radius: 3px; border: 1px solid #dee2e6;">${value}</span>`;
                        }
                        
                        // Format department with styling
                        else if (field === 'department') {
                            value = `<span style="font-weight: 600; color: #2c5aa0; background: rgba(44, 90, 160, 0.1); padding: 4px 8px; border-radius: 3px; border: 1px solid rgba(44, 90, 160, 0.2);">${value}</span>`;
                        }
                        
                        // Truncate long text
                        else if (value && value.length > 30 && !value.includes('<')) {
                            value = `<span title="${value}" class="text-truncate d-inline-block" style="max-width: 200px; font-weight: 500;">${value}</span>`;
                        }
                        
                        // Handle empty values
                        else if (!value || value === '') {
                            value = '<span class="empty-cell">-</span>';
                        }
                        
                        let cellClass = '';
                        if (field === 'sex') cellClass = 'gender-cell';
                        if (field.includes('status') || field.includes('license')) cellClass += ' status-cell license-cell';
                        if (field.includes('date')) cellClass += ' date-cell';
                        if (field.includes('qualification') || field.includes('certification')) cellClass += ' qualification-cell';
                        
                        cellClass = cellClass.trim();
                        
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
            
            // Add summary with correct preview limit info
            tableHTML += `
                <div class="mt-4">
                    <div class="alert ${totalRecords > previewLimit ? 'alert-info' : 'alert-success'} border-0 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Preview Summary:</strong> Showing ${previewRecords} of ${totalRecords} records.
                                ${totalRecords > previewLimit ? 
                                    `<span class="text-muted ms-2">(First ${previewLimit} records shown)</span>` : 
                                    ''}
                            </div>
                            <div class="mt-2 mt-md-0">
                                ${totalRecords > previewLimit ? 
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
            
            // Show the preview panel
            document.getElementById('previewPanel').classList.add('show');
            
            // Scroll to preview panel on mobile
            if (window.innerWidth < 768) {
                document.getElementById('previewPanel').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
        
        // Helper function to clear filters (updated with new filters)
        function clearFilters() {
            // Clear all filter inputs
            document.querySelector('[name="search"]').value = '';
            
            // Clear all select filters
            const filterSelects = [
                'filter_state',
                'filter_department', 
                'filter_grade_level',
                'filter_sex',
                'filter_rank',
                'filter_status',
                'filter_nmcn_status',
                'filter_trcn_status',
                'filter_professional_certification'
            ];
            
            filterSelects.forEach(filterName => {
                const select = document.querySelector(`[name="${filterName}"]`);
                if (select) {
                    select.value = '';
                }
            });
            
            // Clear highest qualification (multiple select)
            const highestQualSelect = document.getElementById('filter_highest_qualification');
            if (highestQualSelect) {
                Array.from(highestQualSelect.options).forEach(opt => opt.selected = false);
            }
            
            // Clear additional qualification
            document.getElementById('filter_additional_qualification').value = '';
            
            // Clear additional qualification search
            document.getElementById('additionalQualSearch').value = '';
            
            updateFilterCount();
            showAlert('All filters cleared', 'info');
        }
        
        // Update preview statistics
        function updatePreviewStats(previewData) {
            if (previewData.statistics) {
                const statsContainer = document.querySelector('.stats-container');
                if (statsContainer && previewData.totalRecords !== undefined) {
                    const totalRecordsEl = statsContainer.querySelector('.stat-card:nth-child(1) h3');
                    if (totalRecordsEl) {
                        totalRecordsEl.textContent = previewData.totalRecords;
                    }
                }
            }
        }
        
        // Submit full report form
        function submitFullReport() {
            document.getElementById('reportForm').submit();
        }
        
        // Prepare data for saving report (updated with new filters)
        function prepareSaveData() {
            // Get selected fields
            const selectedFields = [];
            document.querySelectorAll('.field-checkbox:checked').forEach(cb => {
                selectedFields.push(cb.value);
            });
            document.getElementById('saveSelectedFields').value = JSON.stringify(selectedFields);
            
            // Get filters (including new qualification filters)
            const filters = {
                search: document.querySelector('[name="search"]').value,
                state: document.querySelector('[name="filter_state"]').value,
                department: document.querySelector('[name="filter_department"]').value,
                grade_level: document.querySelector('[name="filter_grade_level"]').value,
                sex: document.querySelector('[name="filter_sex"]').value,
                rank: document.querySelector('[name="filter_rank"]').value,
                status: document.querySelector('[name="filter_status"]').value,
                nmcn_status: document.querySelector('[name="filter_nmcn_status"]').value,
                trcn_status: document.querySelector('[name="filter_trcn_status"]').value,
                highest_qualification: Array.from(document.querySelector('[name="filter_highest_qualification[]"]')?.selectedOptions || []).map(opt => opt.value),
                professional_certification: document.querySelector('[name="filter_professional_certification"]').value,
                additional_qualification: document.querySelector('[name="filter_additional_qualification"]').value
            };
            document.getElementById('saveFilters').value = JSON.stringify(filters);
            
            // Get sort order
            document.getElementById('saveSortOrder').value = document.querySelector('[name="sort_order"]').value;
            
            // Get export options
            const exportOptions = {
                auto_format: document.getElementById('autoFormat').checked,
                include_summary: document.getElementById('includeSummary').checked
            };
            document.getElementById('saveExcelOptions').value = JSON.stringify(exportOptions);
        }
        
        // Professional Excel Export
        function exportExcel() {
            try {
                const exportBtn = document.querySelector('[onclick="exportExcel()"]');
                const originalBtnText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creating Report...';
                exportBtn.disabled = true;

                const previewPanel = document.getElementById('previewPanel');
                if (!previewPanel.classList.contains('show')) {
                    showAlert('Please generate a preview first before exporting', 'warning');
                    exportBtn.innerHTML = originalBtnText;
                    exportBtn.disabled = false;
                    return;
                }

                const autoFormat = document.getElementById('autoFormat').checked;
                const includeSummary = document.getElementById('includeSummary').checked;
                
                const form = document.getElementById('reportForm');
                if (!form) {
                    throw new Error('Form not found');
                }
                
                const formData = new FormData(form);
                formData.append('export_type', 'excel');
                formData.append('auto_format', autoFormat ? '1' : '0');
                formData.append('include_summary', includeSummary ? '1' : '0');
                
                showAlert('Creating professionally formatted Excel report...', 'info');
                
                const exportForm = document.createElement('form');
                exportForm.method = 'POST';
                exportForm.action = '/admin/nominal-roll/export-excel';
                exportForm.target = '_blank';
                exportForm.style.display = 'none';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = document.querySelector('input[name="csrf_token"]').value;
                exportForm.appendChild(csrfInput);
                
                for (let [key, value] of formData.entries()) {
                    if (key !== 'csrf_token') {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        exportForm.appendChild(input);
                    }
                }
                
                document.body.appendChild(exportForm);
                exportForm.submit();
                document.body.removeChild(exportForm);
                
                setTimeout(() => {
                    exportBtn.innerHTML = originalBtnText;
                    exportBtn.disabled = false;
                    showAlert('Excel report is being generated. It will open in a new tab.', 'success');
                }, 2000);

            } catch (error) {
                console.error('Excel export error:', error);
                showAlert('Error creating Excel report: ' + error.message, 'danger');
                
                const exportBtn = document.querySelector('[onclick="exportExcel()"]');
                exportBtn.innerHTML = '<i class="fas fa-file-excel me-1"></i> Excel';
                exportBtn.disabled = false;
            }
        }

        // CSV Export
        function exportCSV() {
            try {
                const exportBtn = document.querySelector('[onclick="exportCSV()"]');
                const originalBtnText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creating CSV...';
                exportBtn.disabled = true;

                const previewPanel = document.getElementById('previewPanel');
                if (!previewPanel.classList.contains('show')) {
                    showAlert('Please generate a preview first before exporting', 'warning');
                    exportBtn.innerHTML = originalBtnText;
                    exportBtn.disabled = false;
                    return;
                }

                const form = document.getElementById('reportForm');
                if (!form) {
                    throw new Error('Form not found');
                }
                
                const formData = new FormData(form);
                formData.append('export_type', 'csv');
                
                const exportForm = document.createElement('form');
                exportForm.method = 'POST';
                exportForm.action = '/admin/nominal-roll/export-csv';
                exportForm.target = '_blank';
                exportForm.style.display = 'none';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = document.querySelector('input[name="csrf_token"]').value;
                exportForm.appendChild(csrfInput);
                
                for (let [key, value] of formData.entries()) {
                    if (key !== 'csrf_token') {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        exportForm.appendChild(input);
                    }
                }
                
                document.body.appendChild(exportForm);
                exportForm.submit();
                document.body.removeChild(exportForm);
                
                setTimeout(() => {
                    exportBtn.innerHTML = originalBtnText;
                    exportBtn.disabled = false;
                }, 2000);

            } catch (error) {
                console.error('CSV export error:', error);
                showAlert('Error creating CSV: ' + error.message, 'danger');
                
                const exportBtn = document.querySelector('[onclick="exportCSV()"]');
                exportBtn.innerHTML = '<i class="fas fa-file-csv me-1"></i> CSV';
                exportBtn.disabled = false;
            }
        }

        // PDF Export function
        function exportPDF() {
            showAlert('PDF export is currently unavailable. Please use Excel or CSV export.', 'info');
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
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
        
        // Handle window resize for better responsiveness
        window.addEventListener('resize', function() {
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