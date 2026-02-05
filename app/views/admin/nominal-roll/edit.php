<?php
/**
 * Edit Employee View - Optimized Complete Solution
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Edit Employee Record | Nominal Roll</title>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ===== VARIABLES ===== */
        :root {
            --primary-color: #2c3e50;
            --primary-light: #34495e;
            --secondary-color: #3498db;
            --accent-color: #1abc9c;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --success-color: #27ae60;
            --light-color: #f8f9fa;
            --dark-color: #2c3e50;
            --gray-light: #ecf0f1;
            --gray-medium: #bdc3c7;
            --gray-dark: #7f8c8d;
            --border-color: #dfe6e9;
            --shadow-light: 0 2px 10px rgba(0,0,0,0.08);
            --shadow-medium: 0 4px 20px rgba(0,0,0,0.12);
            --radius: 8px;
            --radius-sm: 6px;
        }
        
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            color: var(--dark-color);
            background: #f5f7fa;
            padding: 15px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1800px;
            margin: 0 auto;
        }
        
        /* ===== HEADER ===== */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: var(--radius);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-medium);
            border-left: 5px solid var(--accent-color);
        }
        
        .header-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
        }
        
        .header-title h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }
        
        .subtitle {
            font-size: 0.95rem;
            opacity: 0.85;
            margin-bottom: 1rem;
        }
        
        .employee-badge {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
        }
        
        .badge {
            padding: 0.4rem 1rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(10px);
        }
        
        .badge i {
            font-size: 0.9rem;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
            white-space: nowrap;
            user-select: none;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: var(--gray-dark);
            color: white;
        }
        
        .btn-secondary:hover {
            background: #6c7d8c;
        }
        
        .btn-success {
            background: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background: #229954;
        }
        
        .btn-danger {
            background: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }
        
        /* ===== TABS ===== */
        .tab-navigation {
            background: white;
            border-radius: var(--radius);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-light);
            overflow: hidden;
            position: relative;
        }
        
        .tab-progress {
            height: 3px;
            background: var(--accent-color);
            position: absolute;
            bottom: 0;
            left: 0;
            transition: all 0.3s ease;
        }
        
        .tab-buttons {
            display: flex;
            overflow-x: auto;
            padding: 0.5rem;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        
        .tab-buttons::-webkit-scrollbar {
            display: none;
        }
        
        .tab-btn {
            flex: 1;
            min-width: 140px;
            padding: 1rem 1.25rem;
            border: none;
            background: none;
            font-weight: 600;
            color: var(--gray-dark);
            cursor: pointer;
            white-space: nowrap;
            border-radius: var(--radius-sm);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        
        .tab-btn i {
            font-size: 1.1rem;
        }
        
        .tab-btn:hover {
            background: var(--gray-light);
            color: var(--primary-color);
        }
        
        .tab-btn.active {
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
            font-weight: 700;
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* ===== FORM LAYOUT - LANDSCAPE OPTIMIZED ===== */
        .form-card {
            background: white;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .form-card:hover {
            box-shadow: var(--shadow-medium);
            transform: translateY(-2px);
        }
        
        .card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .card-header h3 {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin: 0;
            font-weight: 600;
        }
        
        .card-header i {
            color: var(--secondary-color);
            font-size: 1.2rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* ===== FORM GRID - LANDSCAPE OPTIMIZED ===== */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 992px) {
            .form-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (min-width: 1200px) {
            .form-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        /* Full width group for textareas and special fields */
        .full-width {
            grid-column: 1 / -1;
        }
        
        /* License Grid Layout */
        .licenses-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .licenses-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        .license-card {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .license-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .license-expired {
            border-left: 4px solid #fc8181;
        }
        
        .license-active {
            border-left: 4px solid #68d391;
        }
        
        .section-title {
            color: #2c5282;
            border-bottom: 3px solid #4299e1;
            padding-bottom: 12px;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .license-fields {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        @media (min-width: 640px) {
            .license-fields {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* ===== FORM CONTROLS ===== */
        .form-group {
            margin-bottom: 0;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--primary-light);
            font-size: 0.95rem;
        }
        
        .form-group.required label::after {
            content: " *";
            color: var(--danger-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 0.95rem;
            transition: all 0.25s ease;
            background: white;
            min-height: 48px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%237f8c8d' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-text {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--gray-dark);
        }
        
        /* Qualification Styles */
        .qualification-entry {
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .qualification-entry:hover {
            background: #edf2f7;
            border-color: #cbd5e0;
        }
        
        .qualification-row {
            display: grid;
            grid-template-columns: 3fr 1fr auto;
            gap: 0.75rem;
            align-items: start;
        }
        
        @media (max-width: 768px) {
            .qualification-row {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
        }
        
        /* ===== ALERTS ===== */
        .alert {
            padding: 1.25rem 1.5rem;
            border-radius: var(--radius-sm);
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .alert-success {
            background: #d5edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .alert i {
            font-size: 1.2rem;
        }
        
        /* ===== NAVIGATION CONTROLS ===== */
        .nav-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 0;
            margin-top: 2rem;
            border-top: 1px solid var(--border-color);
        }
        
        .nav-buttons {
            display: flex;
            gap: 1rem;
        }
        
        /* ===== FORM ACTIONS ===== */
        .form-actions {
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-light);
            margin-top: 3rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .action-info {
            font-size: 0.9rem;
            color: var(--gray-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .action-info i {
            color: var(--secondary-color);
        }
        
        /* ===== SAVE NOTIFICATION ===== */
        .save-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--success-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 9999;
            box-shadow: var(--shadow-medium);
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }
        
        .save-notification.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        /* ===== MODAL ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 1rem;
            animation: fadeIn 0.3s ease;
        }
        
        .modal-content {
            background: white;
            border-radius: var(--radius);
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h3 {
            margin: 0;
            color: var(--primary-color);
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gray-dark);
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        
        .modal-close:hover {
            background: var(--gray-light);
            color: var(--danger-color);
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
        
        /* ===== PHOTO SECTION ===== */
        .photo-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        @media (min-width: 768px) {
            .photo-section {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .current-photo, .upload-new-photo {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
        }
        
        .photo-preview {
            border: 2px dashed var(--border-color);
            border-radius: var(--radius);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            transition: all 0.3s ease;
        }
        
        .photo-preview.drag-over {
            border-color: var(--secondary-color);
            background: rgba(52, 152, 219, 0.05);
        }
        
        .photo-preview img {
            max-width: 100%;
            max-height: 250px;
            border-radius: var(--radius-sm);
            object-fit: cover;
        }
        
        .upload-instructions {
            text-align: center;
            color: var(--gray-dark);
        }
        
        .upload-instructions i {
            font-size: 3rem;
            color: var(--gray-medium);
            margin-bottom: 1rem;
        }
        
        .no-photo {
            text-align: center;
            padding: 2rem;
            color: #a0aec0;
            background: #edf2f7;
            border-radius: 8px;
            border: 2px dashed #cbd5e0;
        }
        
        .no-photo i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        /* File Upload Area */
        .upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .upload-area:hover {
            border-color: #667eea;
            background: #edf2f7;
        }
        
        /* ===== FORM STATUS INDICATORS ===== */
        .field-modified {
            border-color: var(--warning-color) !important;
        }
        
        .field-error {
            border-color: var(--danger-color) !important;
        }
        
        .form-saving {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--secondary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            display: none;
            align-items: center;
            gap: 0.5rem;
            z-index: 9998;
            box-shadow: var(--shadow-light);
        }
        
        .form-saving.show {
            display: flex;
        }
        
        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1200px) {
            .container {
                max-width: 1200px;
            }
        }
        
        @media (max-width: 992px) {
            .header-content {
                flex-direction: column;
                align-items: stretch;
            }
            
            .header-actions {
                justify-content: flex-start;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .page-header {
                padding: 1.25rem;
            }
            
            .card-body {
                padding: 1.25rem;
            }
            
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .action-buttons {
                justify-content: stretch;
            }
            
            .btn {
                flex: 1;
                justify-content: center;
            }
            
            .tab-btn {
                min-width: 120px;
                padding: 0.875rem 1rem;
                font-size: 0.9rem;
            }
            
            .nav-controls {
                flex-direction: column;
                gap: 1.5rem;
                align-items: stretch;
            }
            
            .nav-buttons {
                justify-content: space-between;
            }
            
            .licenses-grid {
                grid-template-columns: 1fr;
            }
            
            .license-fields {
                grid-template-columns: 1fr;
            }
            
            .photo-section {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .tab-btn span {
                display: none;
            }
            
            .tab-btn i {
                font-size: 1.3rem;
            }
            
            .btn {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
            
            .save-notification {
                top: 10px;
                right: 10px;
                left: 10px;
                transform: translateY(-100%);
            }
            
            .save-notification.show {
                transform: translateY(0);
            }
        }
        
        /* ===== LOADING STATES ===== */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Add qualification button */
        #add-qualification-btn {
            margin-top: 1rem;
            background: white;
            border: 2px dashed #cbd5e0;
            color: #667eea;
            font-weight: 600;
        }
        
        #add-qualification-btn:hover {
            background: #f8fafc;
            border-color: #667eea;
            border-style: solid;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <h1><i class="fas fa-user-edit"></i> Edit Employee Record</h1>
                    <p class="subtitle">Update and manage employee information in the nominal roll system</p>
                    <div class="employee-badge">
                        <span class="badge"><i class="fas fa-id-badge"></i> <?php echo htmlspecialchars($employee['employee_number']); ?></span>
                        <span class="badge"><i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($employee['rank']); ?></span>
                        <span class="badge"><i class="fas fa-calendar-alt"></i> Last updated: <?php echo !empty($employee['updated_at']) ? date('M d, Y', strtotime($employee['updated_at'])) : 'Never'; ?></span>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/view/<?php echo $employee['id']; ?>" class="btn btn-outline">
                        <i class="fas fa-eye"></i> View Record
                    </a>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (!empty($flash_success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div><?php echo htmlspecialchars($flash_success); ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($flash_error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div><?php echo htmlspecialchars($flash_error); ?></div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div><?php echo htmlspecialchars($error); ?></div>
        </div>
        <?php endif; ?>

        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <div class="tab-progress" id="tabProgress"></div>
            <div class="tab-buttons" id="tabButtons">
                <button class="tab-btn active" data-tab="basic">
                    <i class="fas fa-user"></i> <span>Basic Info</span>
                </button>
                <button class="tab-btn" data-tab="employment">
                    <i class="fas fa-briefcase"></i> <span>Employment</span>
                </button>
                <button class="tab-btn" data-tab="education">
                    <i class="fas fa-graduation-cap"></i> <span>Education</span>
                </button>
                <button class="tab-btn" data-tab="licenses">
                    <i class="fas fa-id-card"></i> <span>Licenses</span>
                </button>
                <button class="tab-btn" data-tab="location">
                    <i class="fas fa-map-marker-alt"></i> <span>Location</span>
                </button>
                <button class="tab-btn" data-tab="medical">
                    <i class="fas fa-heartbeat"></i> <span>Medical</span>
                </button>
                <button class="tab-btn" data-tab="financial">
                    <i class="fas fa-file-invoice-dollar"></i> <span>Financial</span>
                </button>
                <button class="tab-btn" data-tab="emergency">
                    <i class="fas fa-user-friends"></i> <span>Emergency</span>
                </button>
                <button class="tab-btn" data-tab="photo">
                    <i class="fas fa-camera"></i> <span>Photo</span>
                </button>
            </div>
        </div>

        <!-- Main Form -->
        <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/update/<?php echo $employee['id']; ?>" 
              enctype="multipart/form-data" id="employeeForm">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="_method" value="PUT">
            
            <!-- Tab 1: Basic Information -->
            <div id="tab-basic" class="tab-content active">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-id-card"></i> Basic Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <!-- Employee Number -->
                            <div class="form-group required">
                                <label for="employee_number">Employee Number *</label>
                                <input type="text" id="employee_number" name="employee_number" 
                                       value="<?php echo htmlspecialchars($formData['employee_number'] ?? $employee['employee_number'] ?? ''); ?>"
                                       class="form-control" required placeholder="EMP20240001">
                                <small class="form-text">Unique identifier for the employee</small>
                            </div>

                            <!-- IPPIS Number -->
                            <div class="form-group">
                                <label for="ippis_number">IPPIS Number</label>
                                <input type="text" id="ippis_number" name="ippis_number" 
                                       value="<?php echo htmlspecialchars($formData['ippis_number'] ?? $employee['ippis_number'] ?? ''); ?>"
                                       class="form-control" maxlength="50" placeholder="Enter IPPIS number">
                            </div>

                            <!-- Surname -->
                            <div class="form-group required">
                                <label for="surname">Surname *</label>
                                <input type="text" id="surname" name="surname" 
                                       value="<?php echo htmlspecialchars($formData['surname'] ?? $employee['surname'] ?? ''); ?>"
                                       class="form-control" required>
                            </div>

                            <!-- First Name -->
                            <div class="form-group required">
                                <label for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($formData['first_name'] ?? $employee['first_name'] ?? ''); ?>"
                                       class="form-control" required>
                            </div>

                            <!-- Middle Name -->
                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" 
                                       value="<?php echo htmlspecialchars($formData['middle_name'] ?? $employee['middle_name'] ?? ''); ?>"
                                       class="form-control">
                            </div>

                            <!-- Sex -->
                            <div class="form-group required">
                                <label for="sex">Sex *</label>
                                <select id="sex" name="sex" class="form-control" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male" <?php echo (isset($formData['sex']) ? $formData['sex'] : (isset($employee['sex']) ? $employee['sex'] : '')) === 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo (isset($formData['sex']) ? $formData['sex'] : (isset($employee['sex']) ? $employee['sex'] : '')) === 'Female' ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>

                            <!-- Date of Birth -->
                            <div class="form-group required">
                                <label for="date_of_birth">Date of Birth *</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" 
                                       value="<?php echo htmlspecialchars($formData['date_of_birth'] ?? $employee['date_of_birth'] ?? ''); ?>"
                                       class="form-control" required>
                            </div>

                            <!-- Marital Status -->
                            <div class="form-group required">
                                <label for="marital_status">Marital Status *</label>
                                <select id="marital_status" name="marital_status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="Single" <?php echo (isset($formData['marital_status']) ? $formData['marital_status'] : (isset($employee['marital_status']) ? $employee['marital_status'] : '')) === 'Single' ? 'selected' : ''; ?>>Single</option>
                                    <option value="Married" <?php echo (isset($formData['marital_status']) ? $formData['marital_status'] : (isset($employee['marital_status']) ? $employee['marital_status'] : '')) === 'Married' ? 'selected' : ''; ?>>Married</option>
                                    <option value="Divorced" <?php echo (isset($formData['marital_status']) ? $formData['marital_status'] : (isset($employee['marital_status']) ? $employee['marital_status'] : '')) === 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                                    <option value="Widowed" <?php echo (isset($formData['marital_status']) ? $formData['marital_status'] : (isset($employee['marital_status']) ? $employee['marital_status'] : '')) === 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="form-group required">
                                <label for="status">Status *</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <?php 
                                    $statusOptions = isset($filterOptions['status_options']) ? $filterOptions['status_options'] : 
                                                    ['active', 'inactive', 'retired', 'transferred', 'deceased', 'suspended'];
                                    foreach ($statusOptions as $status_option): ?>
                                        <option value="<?php echo htmlspecialchars($status_option); ?>" 
                                            <?php echo (isset($formData['status']) ? $formData['status'] : (isset($employee['status']) ? $employee['status'] : '')) == $status_option ? 'selected' : ''; ?>>
                                            <?php echo ucfirst(htmlspecialchars($status_option)); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Nationality -->
                            <div class="form-group required">
                                <label for="nationality">Nationality *</label>
                                <select id="nationality" name="nationality" class="form-control" required>
                                    <option value="">Select Nationality</option>
                                    <option value="Nigerian" <?php echo (isset($formData['nationality']) ? $formData['nationality'] : (isset($employee['nationality']) ? $employee['nationality'] : '')) === 'Nigerian' ? 'selected' : ''; ?>>Nigerian</option>
                                    <option value="Ghanaian" <?php echo (isset($formData['nationality']) ? $formData['nationality'] : (isset($employee['nationality']) ? $employee['nationality'] : '')) === 'Ghanaian' ? 'selected' : ''; ?>>Ghanaian</option>
                                    <option value="Other" <?php echo (isset($formData['nationality']) ? $formData['nationality'] : (isset($employee['nationality']) ? $employee['nationality'] : '')) === 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <!-- Religion -->
                            <div class="form-group">
                                <label for="religion">Religion</label>
                                <select id="religion" name="religion" class="form-control">
                                    <option value="">Select Religion</option>
                                    <option value="Christianity" <?php echo (isset($formData['religion']) ? $formData['religion'] : (isset($employee['religion']) ? $employee['religion'] : '')) === 'Christianity' ? 'selected' : ''; ?>>Christianity</option>
                                    <option value="Islam" <?php echo (isset($formData['religion']) ? $formData['religion'] : (isset($employee['religion']) ? $employee['religion'] : '')) === 'Islam' ? 'selected' : ''; ?>>Islam</option>
                                    <option value="Traditional" <?php echo (isset($formData['religion']) ? $formData['religion'] : (isset($employee['religion']) ? $employee['religion'] : '')) === 'Traditional' ? 'selected' : ''; ?>>Traditional Religion</option>
                                    <option value="Other" <?php echo (isset($formData['religion']) ? $formData['religion'] : (isset($employee['religion']) ? $employee['religion'] : '')) === 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 2: Employment Details -->
            <div id="tab-employment" class="tab-content">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-briefcase"></i> Employment Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <!-- Rank -->
                            <div class="form-group required">
                                <label for="rank">Rank *</label>
                                <input type="text" id="rank" name="rank" 
                                       value="<?php echo htmlspecialchars($formData['rank'] ?? $employee['rank'] ?? ''); ?>"
                                       class="form-control" required placeholder="e.g., Senior Lecturer">
                            </div>

                            <!-- Grade Level -->
                            <div class="form-group required">
                                <label for="grade_level">Grade Level (GL) *</label>
                                <select id="grade_level" name="grade_level" class="form-control" required>
                                    <option value="">Select Grade Level</option>
                                    <?php for ($i = 1; $i <= 17; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($formData['grade_level']) ? $formData['grade_level'] : (isset($employee['grade_level']) ? $employee['grade_level'] : '')) == $i ? 'selected' : ''; ?>>
                                        GL <?php echo $i; ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Step -->
                            <div class="form-group">
                                <label for="step">Step</label>
                                <select id="step" name="step" class="form-control">
                                    <option value="">Select Step</option>
                                    <?php for ($i = 1; $i <= 15; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($formData['step']) ? $formData['step'] : (isset($employee['step']) ? $employee['step'] : '')) == $i ? 'selected' : ''; ?>>
                                        Step <?php echo $i; ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Cadre -->
                            <div class="form-group">
                                <label for="cadre">Cadre</label>
                                <input type="text" id="cadre" name="cadre" 
                                       value="<?php echo htmlspecialchars($formData['cadre'] ?? $employee['cadre'] ?? ''); ?>"
                                       class="form-control" placeholder="e.g., Academic, Non-Academic">
                            </div>

                            <!-- Staff Type -->
                            <div class="form-group">
                                <label for="staff_type">Staff Type</label>
                                <select id="staff_type" name="staff_type" class="form-control">
                                    <option value="">Select Staff Type</option>
                                    <option value="Academic" <?php echo (isset($formData['staff_type']) ? $formData['staff_type'] : (isset($employee['staff_type']) ? $employee['staff_type'] : '')) === 'Academic' ? 'selected' : ''; ?>>Academic</option>
                                    <option value="Non-Academic" <?php echo (isset($formData['staff_type']) ? $formData['staff_type'] : (isset($employee['staff_type']) ? $employee['staff_type'] : '')) === 'Non-Academic' ? 'selected' : ''; ?>>Non-Academic</option>
                                    <option value="Administrative" <?php echo (isset($formData['staff_type']) ? $formData['staff_type'] : (isset($employee['staff_type']) ? $employee['staff_type'] : '')) === 'Administrative' ? 'selected' : ''; ?>>Administrative</option>
                                    <option value="Technical" <?php echo (isset($formData['staff_type']) ? $formData['staff_type'] : (isset($employee['staff_type']) ? $employee['staff_type'] : '')) === 'Technical' ? 'selected' : ''; ?>>Technical</option>
                                </select>
                            </div>

                            <!-- Employment Type -->
                            <div class="form-group">
                                <label for="employment_type">Employment Type</label>
                                <select id="employment_type" name="employment_type" class="form-control">
                                    <option value="">Select Employment Type</option>
                                    <option value="Permanent" <?php echo (isset($formData['employment_type']) ? $formData['employment_type'] : (isset($employee['employment_type']) ? $employee['employment_type'] : '')) === 'Permanent' ? 'selected' : ''; ?>>Permanent</option>
                                    <option value="Contract" <?php echo (isset($formData['employment_type']) ? $formData['employment_type'] : (isset($employee['employment_type']) ? $employee['employment_type'] : '')) === 'Contract' ? 'selected' : ''; ?>>Contract</option>
                                    <option value="Adjunct" <?php echo (isset($formData['employment_type']) ? $formData['employment_type'] : (isset($employee['employment_type']) ? $employee['employment_type'] : '')) === 'Adjunct' ? 'selected' : ''; ?>>Adjunct</option>
                                    <option value="Visiting" <?php echo (isset($formData['employment_type']) ? $formData['employment_type'] : (isset($employee['employment_type']) ? $employee['employment_type'] : '')) === 'Visiting' ? 'selected' : ''; ?>>Visiting</option>
                                </select>
                            </div>

                            <!-- Appointment Type -->
                            <div class="form-group">
                                <label for="appointment_type">Appointment Type</label>
                                <select id="appointment_type" name="appointment_type" class="form-control">
                                    <option value="">Select Appointment Type</option>
                                    <option value="Confirmed" <?php echo (isset($formData['appointment_type']) ? $formData['appointment_type'] : (isset($employee['appointment_type']) ? $employee['appointment_type'] : '')) === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="Acting" <?php echo (isset($formData['appointment_type']) ? $formData['appointment_type'] : (isset($employee['appointment_type']) ? $employee['appointment_type'] : '')) === 'Acting' ? 'selected' : ''; ?>>Acting</option>
                                    <option value="Secondment" <?php echo (isset($formData['appointment_type']) ? $formData['appointment_type'] : (isset($employee['appointment_type']) ? $employee['appointment_type'] : '')) === 'Secondment' ? 'selected' : ''; ?>>Secondment</option>
                                    <option value="Deputation" <?php echo (isset($formData['appointment_type']) ? $formData['appointment_type'] : (isset($employee['appointment_type']) ? $employee['appointment_type'] : '')) === 'Deputation' ? 'selected' : ''; ?>>Deputation</option>
                                </select>
                            </div>

                            <!-- Department -->
                            <div class="form-group">
                                <label for="department">Department</label>
                                <input type="text" id="department" name="department" 
                                       value="<?php echo htmlspecialchars($formData['department'] ?? $employee['department'] ?? ''); ?>"
                                       class="form-control" placeholder="e.g., Nursing Sciences">
                            </div>

                            <!-- Date of First Appointment -->
                            <div class="form-group required">
                                <label for="date_of_first_appointment">Date of 1st Appointment *</label>
                                <input type="date" id="date_of_first_appointment" name="date_of_first_appointment" 
                                       value="<?php echo htmlspecialchars($formData['date_of_first_appointment'] ?? $employee['date_of_first_appointment'] ?? ''); ?>"
                                       class="form-control" required>
                            </div>

                            <!-- Date of Confirmation -->
                            <div class="form-group">
                                <label for="date_of_confirmation">Date of Confirmation</label>
                                <input type="date" id="date_of_confirmation" name="date_of_confirmation" 
                                       value="<?php echo htmlspecialchars($formData['date_of_confirmation'] ?? $employee['date_of_confirmation'] ?? ''); ?>"
                                       class="form-control">
                            </div>

                            <!-- Rank on First Appointment -->
                            <div class="form-group">
                                <label for="rank_on_first_appointment">Rank on 1st Appointment</label>
                                <input type="text" id="rank_on_first_appointment" name="rank_on_first_appointment" 
                                       value="<?php echo htmlspecialchars($formData['rank_on_first_appointment'] ?? $employee['rank_on_first_appointment'] ?? ''); ?>"
                                       class="form-control" placeholder="Rank at first appointment">
                            </div>

                            <!-- Date of Present Appointment -->
                            <div class="form-group">
                                <label for="date_of_present_appointment">Date of Present Appointment</label>
                                <input type="date" id="date_of_present_appointment" name="date_of_present_appointment" 
                                       value="<?php echo htmlspecialchars($formData['date_of_present_appointment'] ?? $employee['date_of_present_appointment'] ?? ''); ?>"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 3: Education -->
            <div id="tab-education" class="tab-content">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-graduation-cap"></i> Educational Qualifications</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <!-- Highest Qualification -->
                            <div class="form-group required">
                                <label for="highest_qualification">Highest Qualification *</label>
                                <select id="highest_qualification" name="highest_qualification" class="form-control" required>
                                    <option value="">Select Highest Qualification</option>
                                    <option value="PhD" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'PhD' ? 'selected' : ''; ?>>PhD</option>
                                    <option value="MSc" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'MSc' ? 'selected' : ''; ?>>MSc/M.A</option>
                                    <option value="BSc" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'BSc' ? 'selected' : ''; ?>>BSc/B.A/B.Ed</option>
                                    <option value="HND" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'HND' ? 'selected' : ''; ?>>HND</option>
                                    <option value="OND" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'OND' ? 'selected' : ''; ?>>OND</option>
                                    <option value="NCE" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'NCE' ? 'selected' : ''; ?>>NCE</option>
                                    <option value="SSCE" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'SSCE' ? 'selected' : ''; ?>>SSCE/WASC</option>
                                    <option value="FSLC" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'FSLC' ? 'selected' : ''; ?>>FSLC</option>
                                    <option value="Others" <?php echo (isset($formData['highest_qualification']) ? $formData['highest_qualification'] : (isset($employee['highest_qualification']) ? $employee['highest_qualification'] : '')) === 'Others' ? 'selected' : ''; ?>>Others</option>
                                </select>
                            </div>

                            <!-- Year of Highest Qualification -->
                            <div class="form-group required">
                                <label for="year_of_highest_qualification">Year of Highest Qualification *</label>
                                <select id="year_of_highest_qualification" name="year_of_highest_qualification" class="form-control" required>
                                    <option value="">Select Year</option>
                                    <?php for ($year = date('Y'); $year >= 1960; $year--): ?>
                                    <option value="<?php echo $year; ?>" <?php echo (isset($formData['year_of_highest_qualification']) ? $formData['year_of_highest_qualification'] : (isset($employee['year_of_highest_qualification']) ? $employee['year_of_highest_qualification'] : '')) == $year ? 'selected' : ''; ?>>
                                        <?php echo $year; ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Institution Attended -->
                            <div class="form-group">
                                <label for="institution_attended">Institution Attended</label>
                                <input type="text" id="institution_attended" name="institution_attended" 
                                       value="<?php echo htmlspecialchars($formData['institution_attended'] ?? $employee['institution_attended'] ?? ''); ?>"
                                       class="form-control" placeholder="e.g., University of Nigeria, Nsukka">
                            </div>

                            <!-- Course of Study -->
                            <div class="form-group">
                                <label for="course_of_study">Course of Study</label>
                                <input type="text" id="course_of_study" name="course_of_study" 
                                       value="<?php echo htmlspecialchars($formData['course_of_study'] ?? $employee['course_of_study'] ?? ''); ?>"
                                       class="form-control" placeholder="e.g., Nursing Science">
                            </div>

                            <!-- Class of Degree -->
                            <div class="form-group">
                                <label for="class_of_degree">Class of Degree</label>
                                <select id="class_of_degree" name="class_of_degree" class="form-control">
                                    <option value="">Select Class</option>
                                    <option value="First Class" <?php echo (isset($formData['class_of_degree']) ? $formData['class_of_degree'] : (isset($employee['class_of_degree']) ? $employee['class_of_degree'] : '')) === 'First Class' ? 'selected' : ''; ?>>First Class</option>
                                    <option value="Second Class Upper" <?php echo (isset($formData['class_of_degree']) ? $formData['class_of_degree'] : (isset($employee['class_of_degree']) ? $employee['class_of_degree'] : '')) === 'Second Class Upper' ? 'selected' : ''; ?>>Second Class Upper</option>
                                    <option value="Second Class Lower" <?php echo (isset($formData['class_of_degree']) ? $formData['class_of_degree'] : (isset($employee['class_of_degree']) ? $employee['class_of_degree'] : '')) === 'Second Class Lower' ? 'selected' : ''; ?>>Second Class Lower</option>
                                    <option value="Third Class" <?php echo (isset($formData['class_of_degree']) ? $formData['class_of_degree'] : (isset($employee['class_of_degree']) ? $employee['class_of_degree'] : '')) === 'Third Class' ? 'selected' : ''; ?>>Third Class</option>
                                    <option value="Pass" <?php echo (isset($formData['class_of_degree']) ? $formData['class_of_degree'] : (isset($employee['class_of_degree']) ? $employee['class_of_degree'] : '')) === 'Pass' ? 'selected' : ''; ?>>Pass</option>
                                </select>
                            </div>

                            <!-- Professional Certifications -->
                            <div class="form-group full-width">
                                <label for="professional_certifications">Professional Certifications</label>
                                <textarea id="professional_certifications" 
                                          name="professional_certifications" 
                                          class="form-control" 
                                          rows="3"
                                          placeholder="List professional certifications separated by commas"><?php echo htmlspecialchars($formData['professional_certifications'] ?? $employee['professional_certifications'] ?? ''); ?></textarea>
                            </div>

                            <!-- Additional Qualifications -->
                            <div class="form-group full-width">
                                <label>Additional Qualifications</label>
                                <div id="qualifications-container">
                                    <?php
                                    // Parse additional qualifications if they exist
                                    $additional_qualifications = [];
                                    
                                    if (!empty($employee['additional_qualifications'])) {
                                        if (is_string($employee['additional_qualifications'])) {
                                            $additional_qualifications = json_decode($employee['additional_qualifications'], true);
                                            if (json_last_error() !== JSON_ERROR_NONE) {
                                                // If it's not valid JSON, treat it as a simple string
                                                $additional_qualifications = [['qualification' => $employee['additional_qualifications'], 'year' => '']];
                                            }
                                        } elseif (is_array($employee['additional_qualifications'])) {
                                            $additional_qualifications = $employee['additional_qualifications'];
                                        }
                                    }
                                    
                                    // Display existing qualifications
                                    if (!empty($additional_qualifications) && is_array($additional_qualifications)) {
                                        foreach ($additional_qualifications as $index => $qual) {
                                            $qualName = $qual['qualification'] ?? $qual['name'] ?? $qual ?? '';
                                            $qualYear = $qual['year'] ?? '';
                                    ?>
                                    <div class="qualification-entry">
                                        <div class="qualification-row">
                                            <input type="text" 
                                                   name="qualification_name[]" 
                                                   class="form-control qualification-name"
                                                   value="<?php echo htmlspecialchars($qualName); ?>"
                                                   placeholder="Qualification (e.g., BSc Nursing)">
                                            <select name="qualification_year[]" class="form-control qualification-year">
                                                <option value="">Year</option>
                                                <?php for ($year = date('Y'); $year >= 1960; $year--) { ?>
                                                <option value="<?php echo $year; ?>" <?php echo $qualYear == $year ? 'selected' : ''; ?>>
                                                    <?php echo $year; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <button type="button" class="btn btn-danger remove-qualification" title="Remove">
                                                <i class="fas fa-trash"></i>
                                                <span class="btn-text">Remove</span>
                                            </button>
                                        </div>
                                    </div>
                                    <?php 
                                        }
                                    } else {
                                    ?>
                                    <!-- Default empty entry when no qualifications exist -->
                                    <div class="qualification-entry">
                                        <div class="qualification-row">
                                            <input type="text" 
                                                   name="qualification_name[]" 
                                                   class="form-control qualification-name"
                                                   placeholder="Qualification (e.g., BSc Nursing)">
                                            <select name="qualification_year[]" class="form-control qualification-year">
                                                <option value="">Year</option>
                                                <?php for ($year = date('Y'); $year >= 1960; $year--) { ?>
                                                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                                <?php } ?>
                                            </select>
                                            <button type="button" class="btn btn-danger remove-qualification" title="Remove">
                                                <i class="fas fa-trash"></i>
                                                <span class="btn-text">Remove</span>
                                            </button>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <button type="button" id="add-qualification-btn" class="btn btn-sm btn-outline">
                                    <i class="fas fa-plus"></i> Add Qualification
                                </button>
                                <small class="form-text">Add other qualifications with year obtained</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 4: Professional Licenses -->
            <div id="tab-licenses" class="tab-content">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-id-badge"></i> Professional Licenses</h3>
                    </div>
                    <div class="card-body">
                        <div class="licenses-grid">
                            <!-- NMCN License Information -->
                            <div class="license-card <?php 
                                if (!empty($employee['nmcn_status'])) {
                                    echo $employee['nmcn_status'] === 'Expired' ? 'license-expired' : 
                                         ($employee['nmcn_status'] === 'Active' ? 'license-active' : '');
                                }
                            ?>">
                                <h4 class="section-title">
                                    <i class="fas fa-hospital-user"></i> NMCN License Information
                                </h4>
                                <div class="license-fields">
                                    <div class="form-group">
                                        <label for="nmcn_license_number">NMCN License Number *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nmcn_license_number" 
                                               name="nmcn_license_number" 
                                               value="<?php echo htmlspecialchars($employee['nmcn_license_number'] ?? ''); ?>"
                                               placeholder="e.g., 01490/22/F, NMCN12345, or plain 01490">
                                        <small class="form-text text-muted">
                                            Accepts formats: 01490/22/F, NMCN01490, or plain numbers
                                        </small>
                                    </div>
                                    <div class="form-group">
                                        <label for="nmcn_status">NMCN Status</label>
                                        <select class="form-control" id="nmcn_status" name="nmcn_status">
                                            <option value="">Select Status</option>
                                            <option value="Active" <?php echo ($employee['nmcn_status'] ?? '') == 'Active' ? 'selected' : ''; ?>>Active</option>
                                            <option value="Expired" <?php echo ($employee['nmcn_status'] ?? '') == 'Expired' ? 'selected' : ''; ?>>Expired</option>
                                            <option value="Pending" <?php echo ($employee['nmcn_status'] ?? '') == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Not Applicable" <?php echo ($employee['nmcn_status'] ?? '') == 'Not Applicable' ? 'selected' : ''; ?>>Not Applicable</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="nmcn_issued_date">NMCN Issued Date</label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="nmcn_issued_date" 
                                               name="nmcn_issued_date" 
                                               value="<?php echo htmlspecialchars($employee['nmcn_issued_date'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="nmcn_expiry_date">NMCN Expiry Date</label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="nmcn_expiry_date" 
                                               name="nmcn_expiry_date" 
                                               value="<?php echo htmlspecialchars($employee['nmcn_expiry_date'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- TRCN License Information -->
                            <div class="license-card <?php 
                                if (!empty($employee['trcn_status'])) {
                                    echo $employee['trcn_status'] === 'Expired' ? 'license-expired' : 
                                         ($employee['trcn_status'] === 'Active' ? 'license-active' : '');
                                }
                            ?>">
                                <h4 class="section-title">
                                    <i class="fas fa-chalkboard-teacher"></i> TRCN License Information
                                </h4>
                                <div class="license-fields">
                                    <div class="form-group">
                                        <label for="trcn_license_number">TRCN License Number *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="trcn_license_number" 
                                               name="trcn_license_number" 
                                               value="<?php echo htmlspecialchars($employee['trcn_license_number'] ?? ''); ?>"
                                               placeholder="e.g., CT/R/01490, TRCN01490, or plain numbers">
                                        <small class="form-text text-muted">
                                            Accepts formats: CT/R/01490, TRCN01490, or plain numbers
                                        </small>
                                    </div>
                                    <div class="form-group">
                                        <label for="trcn_status">TRCN Status</label>
                                        <select class="form-control" id="trcn_status" name="trcn_status">
                                            <option value="">Select Status</option>
                                            <option value="Active" <?php echo ($employee['trcn_status'] ?? '') == 'Active' ? 'selected' : ''; ?>>Active</option>
                                            <option value="Expired" <?php echo ($employee['trcn_status'] ?? '') == 'Expired' ? 'selected' : ''; ?>>Expired</option>
                                            <option value="Pending" <?php echo ($employee['trcn_status'] ?? '') == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Not Applicable" <?php echo ($employee['trcn_status'] ?? '') == 'Not Applicable' ? 'selected' : ''; ?>>Not Applicable</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="trcn_issued_date">TRCN Issued Date</label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="trcn_issued_date" 
                                               name="trcn_issued_date" 
                                               value="<?php echo htmlspecialchars($employee['trcn_issued_date'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="trcn_expiry_date">TRCN Expiry Date</label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="trcn_expiry_date" 
                                               name="trcn_expiry_date" 
                                               value="<?php echo htmlspecialchars($employee['trcn_expiry_date'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 5: Location -->
            <div id="tab-location" class="tab-content">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-map-marker-alt"></i> Location & Origin</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <!-- State of Origin -->
                            <div class="form-group required">
                                <label for="state">State of Origin *</label>
                                <select id="state" name="state" class="form-control" required>
                                    <option value="">Select State</option>
                                    <?php 
                                    $nigerian_states = [
                                        'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
                                        'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT', 'Gombe',
                                        'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara',
                                        'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau',
                                        'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'
                                    ];
                                    foreach ($nigerian_states as $state): ?>
                                    <option value="<?php echo htmlspecialchars($state); ?>"
                                        <?php echo (isset($formData['state']) ? $formData['state'] : (isset($employee['state']) ? $employee['state'] : '')) === $state ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($state); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Local Government Area -->
                            <div class="form-group required">
                                <label for="local_govt_area">Local Government Area *</label>
                                <select id="local_govt_area" name="local_govt_area" class="form-control" required>
                                    <option value="">Select LGA</option>
                                    <!-- Will be populated by JavaScript based on state -->
                                </select>
                            </div>

                            <!-- Geopolitical Zone -->
                            <div class="form-group">
                                <label for="geopolitical_zone">Geopolitical Zone</label>
                                <select id="geopolitical_zone" name="geopolitical_zone" class="form-control">
                                    <option value="">Select Zone</option>
                                    <option value="North Central" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'North Central' ? 'selected' : ''; ?>>North Central</option>
                                    <option value="North East" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'North East' ? 'selected' : ''; ?>>North East</option>
                                    <option value="North West" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'North West' ? 'selected' : ''; ?>>North West</option>
                                    <option value="South East" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'South East' ? 'selected' : ''; ?>>South East</option>
                                    <option value="South South" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'South South' ? 'selected' : ''; ?>>South South</option>
                                    <option value="South West" <?php echo (isset($formData['geopolitical_zone']) ? $formData['geopolitical_zone'] : (isset($employee['geopolitical_zone']) ? $employee['geopolitical_zone'] : '')) === 'South West' ? 'selected' : ''; ?>>South West</option>
                                </select>
                            </div>

                            <!-- State of Residence -->
                            <div class="form-group">
                                <label for="state_of_residence">State of Residence</label>
                                <select id="state_of_residence" name="state_of_residence" class="form-control">
                                    <option value="">Same as State of Origin</option>
                                    <?php foreach ($nigerian_states as $state): ?>
                                    <option value="<?php echo htmlspecialchars($state); ?>"
                                        <?php echo (isset($formData['state_of_residence']) ? $formData['state_of_residence'] : (isset($employee['state_of_residence']) ? $employee['state_of_residence'] : '')) === $state ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($state); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Residential Address -->
                            <div class="form-group full-width">
                                <label for="residential_address">Residential Address</label>
                                <textarea id="residential_address" 
                                          name="residential_address" 
                                          class="form-control" 
                                          rows="3"
                                          placeholder="Full residential address"><?php echo htmlspecialchars($formData['residential_address'] ?? $employee['residential_address'] ?? ''); ?></textarea>
                            </div>

                            <!-- Contact Address -->
                            <div class="form-group full-width">
                                <label for="contact_address">Contact Address</label>
                                <textarea id="contact_address" 
                                          name="contact_address" 
                                          class="form-control" 
                                          rows="3"
                                          placeholder="Contact address if different from residential"><?php echo htmlspecialchars($formData['contact_address'] ?? $employee['contact_address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 6: Medical -->
            <div id="tab-medical" class="tab-content">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-tie"></i> Medical & Identification</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <!-- PF Number -->
                            <div class="form-group">
                                <label for="pf_number">Personal File (PF) Number</label>
                                <input type="text" id="pf_number" name="pf_number" 
                                       value="<?php echo htmlspecialchars($formData['pf_number'] ?? $employee['pf_number'] ?? ''); ?>"
                                       class="form-control" placeholder="e.g., FCTCNS/PF/001">
                            </div>

                            <!-- NHF Number -->
                            <div class="form-group">
                                <label for="nhf_number">NHF Number</label>
                                <input type="text" id="nhf_number" name="nhf_number" 
                                       value="<?php echo htmlspecialchars($formData['nhf_number'] ?? $employee['nhf_number'] ?? ''); ?>"
                                       class="form-control" placeholder="e.g., NHF/12345/001">
                            </div>

                            <!-- NIN -->
                            <div class="form-group">
                                <label for="nin">NIN (National Identity Number)</label>
                                <input type="text" id="nin" name="nin" 
                                       value="<?php echo htmlspecialchars($formData['nin'] ?? $employee['nin'] ?? ''); ?>"
                                       class="form-control" placeholder="11-digit NIN">
                            </div>

                            <!-- Telephone Number -->
                            <div class="form-group">
                                <label for="telephone_number">Telephone Number</label>
                                <input type="tel" id="telephone_number" name="telephone_number" 
                                       value="<?php echo htmlspecialchars($formData['telephone_number'] ?? $employee['telephone_number'] ?? ''); ?>"
                                       class="form-control" placeholder="e.g., 08012345678"
                                       pattern="[0-9]{11}"
                                       title="11 digit Nigerian phone number">
                            </div>

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($formData['email'] ?? $employee['email'] ?? ''); ?>"
                                       class="form-control" placeholder="e.g., john.doe@example.com">
                            </div>

                            <!-- Blood Group -->
                            <div class="form-group">
                                <label for="blood_group">Blood Group</label>
                                <select id="blood_group" name="blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="O+" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'O+' ? 'selected' : ''; ?>>O Positive (O+)</option>
                                    <option value="O-" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'O-' ? 'selected' : ''; ?>>O Negative (O-)</option>
                                    <option value="A+" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'A+' ? 'selected' : ''; ?>>A Positive (A+)</option>
                                    <option value="A-" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'A-' ? 'selected' : ''; ?>>A Negative (A-)</option>
                                    <option value="B+" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'B+' ? 'selected' : ''; ?>>B Positive (B+)</option>
                                    <option value="B-" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'B-' ? 'selected' : ''; ?>>B Negative (B-)</option>
                                    <option value="AB+" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'AB+' ? 'selected' : ''; ?>>AB Positive (AB+)</option>
                                    <option value="AB-" <?php echo (isset($formData['blood_group']) ? $formData['blood_group'] : (isset($employee['blood_group']) ? $employee['blood_group'] : '')) === 'AB-' ? 'selected' : ''; ?>>AB Negative (AB-)</option>
                                </select>
                            </div>

                            <!-- Genotype -->
                            <div class="form-group">
                                <label for="genotype">Genotype</label>
                                <select id="genotype" name="genotype" class="form-control">
                                    <option value="">Select Genotype</option>
                                    <option value="AA" <?php echo (isset($formData['genotype']) ? $formData['genotype'] : (isset($employee['genotype']) ? $employee['genotype'] : '')) === 'AA' ? 'selected' : ''; ?>>AA</option>
                                    <option value="AS" <?php echo (isset($formData['genotype']) ? $formData['genotype'] : (isset($employee['genotype']) ? $employee['genotype'] : '')) === 'AS' ? 'selected' : ''; ?>>AS</option>
                                    <option value="SS" <?php echo (isset($formData['genotype']) ? $formData['genotype'] : (isset($employee['genotype']) ? $employee['genotype'] : '')) === 'SS' ? 'selected' : ''; ?>>SS</option>
                                    <option value="AC" <?php echo (isset($formData['genotype']) ? $formData['genotype'] : (isset($employee['genotype']) ? $employee['genotype'] : '')) === 'AC' ? 'selected' : ''; ?>>AC</option>
                                </select>
                            </div>

                            <!-- Disability -->
                            <div class="form-group">
                                <label for="disability">Disability</label>
                                <select id="disability" name="disability" class="form-control">
                                    <option value="No" <?php echo (isset($formData['disability']) ? $formData['disability'] : (isset($employee['disability']) ? $employee['disability'] : 'No')) === 'Yes' ? '' : 'selected'; ?>>No</option>
                                    <option value="Yes" <?php echo (isset($formData['disability']) ? $formData['disability'] : (isset($employee['disability']) ? $employee['disability'] : 'No')) === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>

                            <!-- Disability Type -->
                            <div class="form-group" id="disabilityTypeContainer" style="<?php echo (isset($formData['disability']) ? $formData['disability'] : (isset($employee['disability']) ? $employee['disability'] : 'No')) === 'Yes' ? 'display: block;' : 'display: none;'; ?>">
                                <label for="disability_type">Type of Disability</label>
                                <input type="text" 
                                       id="disability_type" 
                                       name="disability_type" 
                                       value="<?php echo htmlspecialchars($formData['disability_type'] ?? $employee['disability_type'] ?? ''); ?>"
                                       class="form-control"
                                       placeholder="Specify disability type">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 7: Financial -->
            <div id="tab-financial" class="tab-content">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-file-invoice-dollar"></i> Financial Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <!-- Bank Name -->
                            <div class="form-group">
                                <label for="bank_name">Bank Name</label>
                                <select id="bank_name" name="bank_name" class="form-control">
                                    <option value="">Select Bank</option>
                                    <?php 
                                    $nigerian_banks = [
                                        'Access Bank', 'Citibank', 'Ecobank', 'Fidelity Bank', 'First Bank',
                                        'First City Monument Bank', 'Guaranty Trust Bank', 'Heritage Bank',
                                        'Keystone Bank', 'Polaris Bank', 'Providus Bank', 'Stanbic IBTC Bank',
                                        'Standard Chartered Bank', 'Sterling Bank', 'Suntrust Bank',
                                        'Union Bank', 'United Bank for Africa', 'Unity Bank', 'Wema Bank',
                                        'Zenith Bank'
                                    ];
                                    foreach ($nigerian_banks as $bank): ?>
                                    <option value="<?php echo htmlspecialchars($bank); ?>"
                                        <?php echo (isset($formData['bank_name']) ? $formData['bank_name'] : (isset($employee['bank_name']) ? $employee['bank_name'] : '')) === $bank ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($bank); ?>
                                    </option>
                                    <?php endforeach; ?>
                                    <option value="Other" <?php echo (!empty(isset($formData['bank_name']) ? $formData['bank_name'] : (isset($employee['bank_name']) ? $employee['bank_name'] : '')) && !in_array(isset($formData['bank_name']) ? $formData['bank_name'] : (isset($employee['bank_name']) ? $employee['bank_name'] : ''), $nigerian_banks)) ? 'selected' : ''; ?>>Other Bank</option>
                                </select>
                            </div>

                            <!-- Other Bank Name -->
                            <div class="form-group" id="otherBankContainer" style="<?php echo (!empty(isset($formData['bank_name']) ? $formData['bank_name'] : (isset($employee['bank_name']) ? $employee['bank_name'] : '')) && !in_array(isset($formData['bank_name']) ? $formData['bank_name'] : (isset($employee['bank_name']) ? $employee['bank_name'] : ''), $nigerian_banks)) ? 'display: block;' : 'display: none;'; ?>">
                                <label for="other_bank_name">Specify Bank Name</label>
                                <input type="text" 
                                       id="other_bank_name" 
                                       name="other_bank_name" 
                                       value="<?php echo htmlspecialchars($formData['other_bank_name'] ?? (!in_array(isset($employee['bank_name']) ? $employee['bank_name'] : '', $nigerian_banks) ? (isset($employee['bank_name']) ? $employee['bank_name'] : '') : '') ?? ''); ?>"
                                       class="form-control"
                                       placeholder="Enter bank name">
                            </div>

                            <!-- Bank Branch -->
                            <div class="form-group">
                                <label for="bank_branch">Bank Branch</label>
                                <input type="text" 
                                       id="bank_branch" 
                                       name="bank_branch" 
                                       value="<?php echo htmlspecialchars($formData['bank_branch'] ?? $employee['bank_branch'] ?? ''); ?>"
                                       class="form-control"
                                       placeholder="e.g., Gwagwalada Branch">
                            </div>

                            <!-- Account Number -->
                            <div class="form-group">
                                <label for="account_number">Account Number</label>
                                <input type="text" 
                                       id="account_number" 
                                       name="account_number" 
                                       value="<?php echo htmlspecialchars($formData['account_number'] ?? $employee['account_number'] ?? ''); ?>"
                                       class="form-control"
                                       placeholder="10-20 digits"
                                       pattern="[0-9]{10,20}"
                                       title="10-20 digit account number">
                            </div>

                            <!-- Account Name -->
                            <div class="form-group">
                                <label for="account_name">Account Name</label>
                                <input type="text" 
                                       id="account_name" 
                                       name="account_name" 
                                       value="<?php echo htmlspecialchars($formData['account_name'] ?? $employee['account_name'] ?? ''); ?>"
                                       class="form-control"
                                       placeholder="Account holder's name">
                            </div>

                            <!-- Pension Fund Administrator -->
                            <div class="form-group">
                                <label for="pension_fund_admin">Pension Fund Administrator (PFA)</label>
                                <select id="pension_fund_admin" name="pension_fund_admin" class="form-control">
                                    <option value="">Select PFA</option>
                                    <?php 
                                    $pension_administrators = [
                                        'Access Pensions', 'AIICO Pension Managers', 'APT Pension Fund Managers',
                                        'ARM Pension Managers', 'Crusader Sterling Pensions', 'Fidelity Pension Managers',
                                        'First Guarantee Pension', 'Future Unity Glanvills Pensions', 'IEI-Anchor Pension Managers',
                                        'Investment One Pension Managers', 'Leadway Pensure PFA', 'Nigerian University Pension Management Co.',
                                        'NPF Pensions', 'Oak Pensions', 'OAK Pensions', 'PAL Pensions', 'Premium Pension',
                                        'Radix Pension Managers', 'Sigma Pensions', 'Stanbic IBTC Pension Managers',
                                        'Trustfund Pensions', 'Veritas Glanvills Pensions'
                                    ];
                                    foreach ($pension_administrators as $pfa): ?>
                                    <option value="<?php echo htmlspecialchars($pfa); ?>"
                                        <?php echo (isset($formData['pension_fund_admin']) ? $formData['pension_fund_admin'] : (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : '')) === $pfa ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($pfa); ?>
                                    </option>
                                    <?php endforeach; ?>
                                    <option value="Other" <?php echo (!empty(isset($formData['pension_fund_admin']) ? $formData['pension_fund_admin'] : (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : '')) && !in_array(isset($formData['pension_fund_admin']) ? $formData['pension_fund_admin'] : (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : ''), $pension_administrators)) ? 'selected' : ''; ?>>Other PFA</option>
                                </select>
                            </div>

                            <!-- Other PFA -->
                            <div class="form-group" id="otherPFAContainer" style="<?php echo (!empty(isset($formData['pension_fund_admin']) ? $formData['pension_fund_admin'] : (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : '')) && !in_array(isset($formData['pension_fund_admin']) ? $formData['pension_fund_admin'] : (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : ''), $pension_administrators)) ? 'display: block;' : 'display: none;'; ?>">
                                <label for="other_pension_fund_admin">Specify PFA</label>
                                <input type="text" 
                                       id="other_pension_fund_admin" 
                                       name="other_pension_fund_admin" 
                                       value="<?php echo htmlspecialchars($formData['other_pension_fund_admin'] ?? (!in_array(isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : '', $pension_administrators) ? (isset($employee['pension_fund_admin']) ? $employee['pension_fund_admin'] : '') : '') ?? ''); ?>"
                                       class="form-control"
                                       placeholder="Enter PFA name">
                            </div>

                            <!-- Pension Number -->
                            <div class="form-group">
                                <label for="pension_number">Pension Number</label>
                                <input type="text" 
                                       id="pension_number" 
                                       name="pension_number" 
                                       value="<?php echo htmlspecialchars($formData['pension_number'] ?? $employee['pension_number'] ?? ''); ?>"
                                       class="form-control"
                                       placeholder="Pension Registration Number">
                            </div>

                            <!-- TIN Number -->
                            <div class="form-group">
                                <label for="tin_number">Tax Identification No (TIN)</label>
                                <input type="text" 
                                       id="tin_number" 
                                       name="tin_number" 
                                       value="<?php echo htmlspecialchars($formData['tin_number'] ?? $employee['tin_number'] ?? ''); ?>"
                                       class="form-control"
                                       placeholder="10-12 digit TIN">
                            </div>

                            <!-- Salary Structure -->
                            <div class="form-group">
                                <label for="salary_structure">Salary Structure</label>
                                <select id="salary_structure" name="salary_structure" class="form-control">
                                    <option value="">Select Salary Structure</option>
                                    <option value="CONMESS" <?php echo (isset($formData['salary_structure']) ? $formData['salary_structure'] : (isset($employee['salary_structure']) ? $employee['salary_structure'] : '')) === 'CONMESS' ? 'selected' : ''; ?>>CONMESS</option>
                                    <option value="CONTISS" <?php echo (isset($formData['salary_structure']) ? $formData['salary_structure'] : (isset($employee['salary_structure']) ? $employee['salary_structure'] : '')) === 'CONTISS' ? 'selected' : ''; ?>>CONTISS</option>
                                    <option value="CONHESS" <?php echo (isset($formData['salary_structure']) ? $formData['salary_structure'] : (isset($employee['salary_structure']) ? $employee['salary_structure'] : '')) === 'CONHESS' ? 'selected' : ''; ?>>CONHESS</option>
                                    <option value="CONPSS" <?php echo (isset($formData['salary_structure']) ? $formData['salary_structure'] : (isset($employee['salary_structure']) ? $employee['salary_structure'] : '')) === 'CONPSS' ? 'selected' : ''; ?>>CONPSS</option>
                                    <option value="Others" <?php echo (isset($formData['salary_structure']) ? $formData['salary_structure'] : (isset($employee['salary_structure']) ? $employee['salary_structure'] : '')) === 'Others' ? 'selected' : ''; ?>>Others</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 8: Emergency Contacts -->
            <div id="tab-emergency" class="tab-content">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-friends"></i> Emergency Contacts & Next of Kin</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <!-- Emergency Contact Name -->
                            <div class="form-group">
                                <label for="emergency_contact_name">Emergency Contact Name</label>
                                <input type="text" 
                                       id="emergency_contact_name" 
                                       name="emergency_contact_name" 
                                       value="<?php echo htmlspecialchars($formData['emergency_contact_name'] ?? $employee['emergency_contact_name'] ?? ''); ?>"
                                       class="form-control"
                                       placeholder="Full name of emergency contact">
                            </div>

                            <!-- Emergency Contact Phone -->
                            <div class="form-group">
                                <label for="emergency_contact_phone">Emergency Contact Phone</label>
                                <input type="tel" 
                                       id="emergency_contact_phone" 
                                       name="emergency_contact_phone" 
                                       value="<?php echo htmlspecialchars($formData['emergency_contact_phone'] ?? $employee['emergency_contact_phone'] ?? ''); ?>"
                                       class="form-control"
                                       placeholder="e.g., 08012345678"
                                       pattern="[0-9]{11}"
                                       title="11 digit Nigerian phone number">
                            </div>

                            <!-- Emergency Contact Relationship -->
                            <div class="form-group">
                                <label for="emergency_contact_relationship">Emergency Contact Relationship</label>
                                <select id="emergency_contact_relationship" name="emergency_contact_relationship" class="form-control">
                                    <option value="">Select Relationship</option>
                                    <option value="Spouse" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Spouse' ? 'selected' : ''; ?>>Spouse</option>
                                    <option value="Parent" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Parent' ? 'selected' : ''; ?>>Parent</option>
                                    <option value="Sibling" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Sibling' ? 'selected' : ''; ?>>Sibling</option>
                                    <option value="Child" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Child' ? 'selected' : ''; ?>>Child</option>
                                    <option value="Relative" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Relative' ? 'selected' : ''; ?>>Relative</option>
                                    <option value="Friend" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Friend' ? 'selected' : ''; ?>>Friend</option>
                                    <option value="Other" <?php echo (isset($formData['emergency_contact_relationship']) ? $formData['emergency_contact_relationship'] : (isset($employee['emergency_contact_relationship']) ? $employee['emergency_contact_relationship'] : '')) === 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <!-- Next of Kin Name -->
                            <div class="form-group">
                                <label for="next_of_kin_name">Next of Kin Name</label>
                                <input type="text" 
                                       id="next_of_kin_name" 
                                       name="next_of_kin_name" 
                                       value="<?php echo htmlspecialchars($formData['next_of_kin_name'] ?? $employee['next_of_kin_name'] ?? ''); ?>"
                                       class="form-control"
                                       placeholder="Full name of next of kin">
                            </div>

                            <!-- Next of Kin Phone -->
                            <div class="form-group">
                                <label for="next_of_kin_phone">Next of Kin Phone</label>
                                <input type="tel" 
                                       id="next_of_kin_phone" 
                                       name="next_of_kin_phone" 
                                       value="<?php echo htmlspecialchars($formData['next_of_kin_phone'] ?? $employee['next_of_kin_phone'] ?? ''); ?>"
                                       class="form-control"
                                       placeholder="e.g., 08012345678"
                                       pattern="[0-9]{11}"
                                       title="11 digit Nigerian phone number">
                            </div>

                            <!-- Next of Kin Relationship -->
                            <div class="form-group">
                                <label for="next_of_kin_relationship">Next of Kin Relationship</label>
                                <select id="next_of_kin_relationship" name="next_of_kin_relationship" class="form-control">
                                    <option value="">Select Relationship</option>
                                    <option value="Spouse" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Spouse' ? 'selected' : ''; ?>>Spouse</option>
                                    <option value="Parent" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Parent' ? 'selected' : ''; ?>>Parent</option>
                                    <option value="Sibling" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Sibling' ? 'selected' : ''; ?>>Sibling</option>
                                    <option value="Child" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Child' ? 'selected' : ''; ?>>Child</option>
                                    <option value="Relative" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Relative' ? 'selected' : ''; ?>>Relative</option>
                                    <option value="Other" <?php echo (isset($formData['next_of_kin_relationship']) ? $formData['next_of_kin_relationship'] : (isset($employee['next_of_kin_relationship']) ? $employee['next_of_kin_relationship'] : '')) === 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <!-- Next of Kin Address -->
                            <div class="form-group full-width">
                                <label for="next_of_kin_address">Next of Kin Address</label>
                                <textarea id="next_of_kin_address" 
                                          name="next_of_kin_address" 
                                          class="form-control" 
                                          rows="3"
                                          placeholder="Full address of next of kin"><?php echo htmlspecialchars($formData['next_of_kin_address'] ?? $employee['next_of_kin_address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 9: Photo -->
            <div id="tab-photo" class="tab-content">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-camera"></i> Passport Photo</h3>
                    </div>
                    <div class="card-body">
                        <div class="photo-section">
                            <!-- Current Photo -->
                            <div class="current-photo">
                                <label style="display: block; margin-bottom: 1rem; font-weight: 600;">Current Photo</label>
                                <?php if (!empty($employee['passport_photo'])): ?>
                                    <div style="text-align: center; border: 2px solid var(--border-color); border-radius: var(--radius); padding: 2rem;">
                                        <img src="<?php echo $baseUrl; ?>/admin/nominal-roll/passport-photo/<?php echo $employee['id']; ?>" 
                                             alt="Passport Photo" 
                                             style="max-width: 200px; border-radius: var(--radius-sm); margin-bottom: 1rem;">
                                        <p style="color: var(--gray-dark); font-size: 0.9rem;">Current passport photo</p>
                                    </div>
                                <?php else: ?>
                                    <div class="no-photo">
                                        <i class="fas fa-user-circle"></i>
                                        <p>No passport photo uploaded</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Upload New Photo -->
                            <div class="upload-new-photo">
                                <div class="form-group">
                                    <label for="passport_photo">Upload New Photo</label>
                                    <div class="upload-area" id="photoDropArea">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Drag & drop or click to browse</p>
                                        <small>Max size: 2MB. Allowed: JPG, JPEG, PNG</small>
                                        <input type="file" id="passport_photo" name="passport_photo" 
                                               class="d-none" accept=".jpg,.jpeg,.png">
                                    </div>
                                    
                                    <div class="photo-preview" id="uploadPreview" style="display: none; margin-top: 1rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                            <span style="font-weight: 600;">New Photo Preview</span>
                                            <button type="button" id="removeImage" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i> Remove
                                            </button>
                                        </div>
                                        <img id="previewImage" src="#" alt="Preview" style="max-width: 100%; border-radius: var(--radius-sm);">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="nav-controls">
                <div class="nav-buttons">
                    <button type="button" class="btn btn-secondary" id="prevBtn">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-secondary" id="nextBtn">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                <div class="nav-info">
                    <span class="badge" id="currentTabIndicator">Step 1 of 9</span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div class="action-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Changes are auto-saved locally every 30 seconds</span>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                        <i class="fas fa-save"></i> Update Employee Record
                    </button>
                    <button type="button" class="btn btn-success" id="saveDraftBtn">
                        <i class="fas fa-save"></i> Save Draft Now
                    </button>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/view/<?php echo $employee['id']; ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <?php if ($isSuperAdmin): ?>
                    <button type="button" class="btn btn-danger" id="deleteBtn">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Silent Save Notification -->
    <div class="save-notification" id="saveNotification">
        <i class="fas fa-check-circle"></i>
        <span>Draft saved successfully</span>
    </div>

    <!-- Saving Indicator -->
    <div class="form-saving" id="formSaving">
        <i class="fas fa-spinner fa-spin"></i>
        <span>Saving changes...</span>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> Confirm Deletion</h3>
                <button class="modal-close" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Warning!</strong> This action is irreversible. All associated data will be permanently removed from the system.
                    </div>
                </div>
                <p class="mb-3">Are you sure you want to delete the following employee record?</p>
                <div style="padding: 1.5rem; background: var(--light-color); border-radius: var(--radius-sm);">
                    <h4 style="margin-bottom: 1rem;"><?php echo htmlspecialchars($employee['surname'] . ', ' . $employee['first_name']); ?></h4>
                    <p><strong>Employee No:</strong> <?php echo htmlspecialchars($employee['employee_number']); ?></p>
                    <p><strong>Rank:</strong> <?php echo htmlspecialchars($employee['rank']); ?></p>
                    <p><strong>Department:</strong> <?php echo htmlspecialchars($employee['department'] ?? 'N/A'); ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
                <form method="POST" action="<?php echo $baseUrl; ?>/admin/nominal-roll/delete/<?php echo $employee['id']; ?>" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="btn btn-danger">Delete Permanently</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Template for Qualification Entry -->
    <template id="qualification-template">
        <div class="qualification-entry">
            <div class="qualification-row">
                <input type="text" 
                       name="qualification_name[]" 
                       class="form-control qualification-name"
                       placeholder="Qualification (e.g., BSc Nursing)">
                <select name="qualification_year[]" class="form-control qualification-year">
                    <option value="">Year</option>
                    <?php for ($year = date('Y'); $year >= 1960; $year--) { ?>
                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                    <?php } ?>
                </select>
                <button type="button" class="btn btn-danger remove-qualification" title="Remove">
                    <i class="fas fa-trash"></i>
                    <span class="btn-text">Remove</span>
                </button>
            </div>
        </div>
    </template>

    <script>
        // ====================
        // GLOBAL VARIABLES
        // ====================
        const tabs = ['basic', 'employment', 'education', 'licenses', 'location', 'medical', 'financial', 'emergency', 'photo'];
        let currentTabIndex = 0;
        let autoSaveTimeout;
        let autoSaveInterval;
        let saveNotificationTimeout;
        let isSaving = false;
        let hasUnsavedChanges = false;
        let formInitialized = false;
        
        // Performance optimization - debounce timers
        let inputDebounceTimer;
        let saveDebounceTimer;
        
        // COMPLETE State-LGA Data for Nigeria
        const nigerianLGAs = {
            'Abia': ['Aba North', 'Aba South', 'Arochukwu', 'Bende', 'Ikwuano', 'Isiala Ngwa North', 'Isiala Ngwa South', 
                    'Isuikwuato', 'Obi Ngwa', 'Ohafia', 'Osisioma', 'Ugwunagbo', 'Ukwa East', 'Ukwa West', 
                    'Umuahia North', 'Umuahia South', 'Umu Nneochi'],
            'Adamawa': ['Demsa', 'Fufure', 'Ganye', 'Girei', 'Gombi', 'Guyuk', 'Hong', 'Jada', 'Lamurde', 
                       'Madagali', 'Maiha', 'Mayo Belwa', 'Michika', 'Mubi North', 'Mubi South', 
                       'Numan', 'Shelleng', 'Song', 'Toungo', 'Yola North', 'Yola South'],
            'Akwa Ibom': ['Abak', 'Eastern Obolo', 'Eket', 'Esit Eket', 'Essien Udim', 'Etim Ekpo', 'Etinan', 
                         'Ibeno', 'Ibesikpo Asutan', 'Ibiono-Ibom', 'Ika', 'Ikono', 'Ikot Abasi', 
                         'Ikot Ekpene', 'Ini', 'Itu', 'Mbo', 'Mkpat-Enin', 'Nsit-Atai', 'Nsit-Ibom', 
                         'Nsit-Ubium', 'Obot Akara', 'Okobo', 'Onna', 'Oron', 'Oruk Anam', 'Udung-Uko', 
                         'Ukanafun', 'Uruan', 'Urue-Offong/Oruko', 'Uyo'],
            'Anambra': ['Aguata', 'Anambra East', 'Anambra West', 'Anaocha', 'Awka North', 'Awka South', 
                       'Ayamelum', 'Dunukofia', 'Ekwusigo', 'Idemili North', 'Idemili South', 'Ihiala', 
                       'Njikoka', 'Nnewi North', 'Nnewi South', 'Ogbaru', 'Onitsha North', 'Onitsha South', 
                       'Orumba North', 'Orumba South', 'Oyi'],
            'Bauchi': ['Alkaleri', 'Bauchi', 'Bogoro', 'Damban', 'Darazo', 'Dass', 'Gamawa', 'Ganjuwa', 
                      'Giade', 'Itas/Gadau', 'Jama\'are', 'Katagum', 'Kirfi', 'Misau', 'Ningi', 
                      'Shira', 'Tafawa Balewa', 'Toro', 'Warji', 'Zaki'],
            'Bayelsa': ['Brass', 'Ekeremor', 'Kolokuma/Opokuma', 'Nembe', 'Ogbia', 'Sagbama', 'Southern Ijaw', 'Yenagoa'],
            'Benue': ['Agatu', 'Apa', 'Ado', 'Buruku', 'Gboko', 'Guma', 'Gwer East', 'Gwer West', 
                     'Katsina-Ala', 'Konshisha', 'Kwande', 'Logo', 'Makurdi', 'Obi', 'Ogbadibo', 
                     'Ohimini', 'Oju', 'Okpokwu', 'Oturkpo', 'Tarka', 'Ukum', 'Ushongo', 'Vandeikya'],
            'Borno': ['Abadam', 'Askira/Uba', 'Bama', 'Bayo', 'Biu', 'Chibok', 'Damboa', 'Dikwa', 
                     'Gubio', 'Guzamala', 'Gwoza', 'Hawul', 'Jere', 'Kaga', 'Kala/Balge', 'Konduga', 
                     'Kukawa', 'Kwaya Kusar', 'Mafa', 'Magumeri', 'Maiduguri', 'Marte', 'Mobbar', 
                     'Monguno', 'Ngala', 'Nganzai', 'Shani'],
            'Cross River': ['Abi', 'Akamkpa', 'Akpabuyo', 'Bakassi', 'Bekwarra', 'Biase', 'Boki', 
                           'Calabar Municipal', 'Calabar South', 'Etung', 'Ikom', 'Obanliku', 'Obubra', 
                           'Obudu', 'Odukpani', 'Ogoja', 'Yakuur', 'Yala'],
            'Delta': ['Aniocha North', 'Aniocha South', 'Bomadi', 'Burutu', 'Ethiope East', 'Ethiope West', 
                     'Ika North East', 'Ika South', 'Isoko North', 'Isoko South', 'Ndokwa East', 
                     'Ndokwa West', 'Okpe', 'Oshimili North', 'Oshimili South', 'Patani', 'Sapele', 
                     'Udu', 'Ughelli North', 'Ughelli South', 'Ukwuani', 'Uvwie', 'Warri North', 
                     'Warri South', 'Warri South West'],
            'Ebonyi': ['Abakaliki', 'Afikpo North', 'Afikpo South', 'Ebonyi', 'Ezza North', 'Ezza South', 
                      'Ikwo', 'Ishielu', 'Ivo', 'Izzi', 'Ohaozara', 'Ohaukwu', 'Onicha'],
            'Edo': ['Akoko-Edo', 'Egor', 'Esan Central', 'Esan North-East', 'Esan South-East', 'Esan West', 
                   'Etsako Central', 'Etsako East', 'Etsako West', 'Igueben', 'Ikpoba Okha', 'Orhionmwon', 
                   'Oredo', 'Ovia North-East', 'Ovia South-West', 'Owan East', 'Owan West', 'Uhunmwonde'],
            'Ekiti': ['Ado Ekiti', 'Efon', 'Ekiti East', 'Ekiti South-West', 'Ekiti West', 'Emure', 
                     'Gbonyin', 'Ido Osi', 'Ijero', 'Ikere', 'Ikole', 'Ilejemeje', 'Irepodun/Ifelodun', 
                     'Ise/Orun', 'Moba', 'Oye'],
            'Enugu': ['Aninri', 'Awgu', 'Enugu East', 'Enugu North', 'Enugu South', 'Ezeagu', 'Igbo Etiti', 
                     'Igbo Eze North', 'Igbo Eze South', 'Isi Uzo', 'Nkanu East', 'Nkanu West', 
                     'Nsukka', 'Oji River', 'Udenu', 'Udi', 'Uzo Uwani'],
            'FCT': ['Abaji', 'Bwari', 'Gwagwalada', 'Kuje', 'Kwali', 'Municipal Area Council'],
            'Gombe': ['Akko', 'Balanga', 'Billiri', 'Dukku', 'Funakaye', 'Gombe', 'Kaltungo', 'Kwami', 'Nafada', 'Shongom', 'Yamaltu/Deba'],
            'Imo': ['Aboh Mbaise', 'Ahiazu Mbaise', 'Ehime Mbano', 'Ezinihitte', 'Ideato North', 'Ideato South', 
                   'Ihitte/Uboma', 'Ikeduru', 'Isiala Mbano', 'Isu', 'Mbaitoli', 'Ngor Okpala', 
                   'Njaba', 'Nkwerre', 'Nwangele', 'Obowo', 'Oguta', 'Ohaji/Egbema', 'Okigwe', 'Orlu', 
                   'Orsu', 'Oru East', 'Oru West', 'Owerri Municipal', 'Owerri North', 'Owerri West', 'Unuimo'],
            'Jigawa': ['Auyo', 'Babura', 'Biriniwa', 'Birnin Kudu', 'Buji', 'Dutse', 'Gagarawa', 'Garki', 
                      'Gumel', 'Guri', 'Gwaram', 'Gwiwa', 'Hadejia', 'Jahun', 'Kafin Hausa', 'Kazaure', 
                      'Kiri Kasama', 'Kiyawa', 'Kaugama', 'Maigatari', 'Malam Madori', 'Miga', 'Ringim', 
                      'Roni', 'Sule Tankarkar', 'Taura', 'Yankwashi'],
            'Kaduna': ['Birnin Gwari', 'Chikun', 'Giwa', 'Igabi', 'Ikara', 'Jaba', 'Jema\'a', 'Kachia', 
                      'Kaduna North', 'Kaduna South', 'Kagarko', 'Kajuru', 'Kaura', 'Kauru', 'Kubau', 
                      'Kudan', 'Lere', 'Makarfi', 'Sabon Gari', 'Sanga', 'Soba', 'Zangon Kataf', 'Zaria'],
            'Kano': ['Ajingi', 'Albasu', 'Bagwai', 'Bebeji', 'Bichi', 'Bunkure', 'Dala', 'Dambatta', 
                    'Dawakin Kudu', 'Dawakin Tofa', 'Doguwa', 'Fagge', 'Gabasawa', 'Garko', 'Garun Mallam', 
                    'Gaya', 'Gezawa', 'Gwale', 'Gwarzo', 'Kabo', 'Kano Municipal', 'Karaye', 'Kibiya', 
                    'Kiru', 'Kumbotso', 'Kunchi', 'Kura', 'Madobi', 'Makoda', 'Minjibir', 'Nasarawa', 
                    'Rano', 'Rimin Gado', 'Rogo', 'Shanono', 'Sumaila', 'Takai', 'Tarauni', 'Tofa', 
                    'Tsanyawa', 'Tudun Wada', 'Ungogo', 'Warawa', 'Wudil'],
            'Katsina': ['Bakori', 'Batagarawa', 'Batsari', 'Baure', 'Bindawa', 'Charanchi', 'Dan Musa', 
                       'Dandume', 'Danja', 'Daura', 'Dutsi', 'Dutsin Ma', 'Faskari', 'Funtua', 'Ingawa', 
                       'Jibia', 'Kafur', 'Kaita', 'Kankara', 'Kankia', 'Katsina', 'Kurfi', 'Kusada', 
                       'Mai\'Adua', 'Malumfashi', 'Mani', 'Mashi', 'Matazu', 'Musawa', 'Rimi', 'Sabuwa', 
                       'Safana', 'Sandamu', 'Zango'],
            'Kebbi': ['Aleiro', 'Arewa Dandi', 'Argungu', 'Augie', 'Bagudo', 'Birnin Kebbi', 'Bunza', 
                     'Dandi', 'Fakai', 'Gwandu', 'Jega', 'Kalgo', 'Koko/Besse', 'Maiyama', 'Ngaski', 
                     'Sakaba', 'Shanga', 'Suru', 'Wasagu/Danko', 'Yauri', 'Zuru'],
            'Kogi': ['Adavi', 'Ajaokuta', 'Ankpa', 'Bassa', 'Dekina', 'Ibaji', 'Idah', 'Igalamela Odolu', 
                    'Ijumu', 'Kabba/Bunu', 'Kogi', 'Lokoja', 'Mopa Muro', 'Ofu', 'Ogori/Magongo', 
                    'Okehi', 'Okene', 'Olamaboro', 'Omala', 'Yagba East', 'Yagba West'],
            'Kwara': ['Asa', 'Baruten', 'Edu', 'Ekiti', 'Ifelodun', 'Ilorin East', 'Ilorin South', 
                     'Ilorin West', 'Irepodun', 'Isin', 'Kaiama', 'Moro', 'Offa', 'Oke Ero', 'Oyun', 'Pategi'],
            'Lagos': ['Agege', 'Ajeromi-Ifelodun', 'Alimosho', 'Amuwo-Odofin', 'Apapa', 'Badagry', 'Epe', 
                     'Eti Osa', 'Ibeju-Lekki', 'Ifako-Ijaiye', 'Ikeja', 'Ikorodu', 'Kosofe', 'Lagos Island', 
                     'Lagos Mainland', 'Mushin', 'Ojo', 'Oshodi-Isolo', 'Shomolu', 'Surulere'],
            'Nasarawa': ['Akwanga', 'Awe', 'Doma', 'Karu', 'Keana', 'Keffi', 'Kokona', 'Lafia', 'Nasarawa', 'Nasarawa Egon', 'Obi', 'Toto', 'Wamba'],
            'Niger': ['Agaie', 'Agwara', 'Bida', 'Borgu', 'Bosso', 'Chanchaga', 'Edati', 'Gbako', 'Gurara', 
                     'Katcha', 'Kontagora', 'Lapai', 'Lavun', 'Magama', 'Mariga', 'Mashegu', 'Mokwa', 
                     'Moya', 'Paikoro', 'Rafi', 'Rijau', 'Shiroro', 'Suleja', 'Tafa', 'Wushishi'],
            'Ogun': ['Abeokuta North', 'Abeokuta South', 'Ado-Odo/Ota', 'Egbado North', 'Egbado South', 
                    'Ewekoro', 'Ifo', 'Ijebu East', 'Ijebu North', 'Ijebu North East', 'Ijebu Ode', 
                    'Ikenne', 'Imeko Afon', 'Ipokia', 'Obafemi Owode', 'Odeda', 'Odogbolu', 'Ogun Waterside', 
                    'Remo North', 'Shagamu', 'Yewa North', 'Yewa South'],
            'Ondo': ['Akoko North-East', 'Akoko North-West', 'Akoko South-East', 'Akoko South-West', 
                    'Akure North', 'Akure South', 'Ese Odo', 'Idanre', 'Ifedore', 'Ilaje', 'Ile Oluji/Okeigbo', 
                    'Irele', 'Odigbo', 'Okitipupa', 'Ondo East', 'Ondo West', 'Ose', 'Owo'],
            'Osun': ['Atakunmosa East', 'Atakunmosa West', 'Aiyedaade', 'Aiyedire', 'Boluwaduro', 'Boripe', 
                    'Ede North', 'Ede South', 'Ife Central', 'Ife East', 'Ife North', 'Ife South', 
                    'Egbedore', 'Ejigbo', 'Ifedayo', 'Ifelodun', 'Ila', 'Ilesa East', 'Ilesa West', 
                    'Irepodun', 'Irewole', 'Isokan', 'Iwo', 'Obokun', 'Odo Otin', 'Ola Oluwa', 'Olorunda', 
                    'Oriade', 'Orolu', 'Osogbo'],
            'Oyo': ['Afijio', 'Akinyele', 'Atiba', 'Atisbo', 'Egbeda', 'Ibadan North', 'Ibadan North-East', 
                   'Ibadan North-West', 'Ibadan South-East', 'Ibadan South-West', 'Ibarapa Central', 
                   'Ibarapa East', 'Ibarapa North', 'Ido', 'Irepo', 'Iseyin', 'Itesiwaju', 'Iwajowa', 
                   'Kajola', 'Lagelu', 'Ogbomosho North', 'Ogbomosho South', 'Ogo Oluwa', 'Olorunsogo', 
                   'Oluyole', 'Ona Ara', 'Orelope', 'Ori Ire', 'Oyo East', 'Oyo West', 'Saki East', 
                   'Saki West', 'Surulere'],
            'Plateau': ['Barkin Ladi', 'Bassa', 'Bokkos', 'Jos East', 'Jos North', 'Jos South', 'Kanam', 
                       'Kanke', 'Langtang North', 'Langtang South', 'Mangu', 'Mikang', 'Pankshin', 
                       'Qua\'an Pan', 'Riyom', 'Shendam', 'Wase'],
            'Rivers': ['Abua/Odual', 'Ahoada East', 'Ahoada West', 'Akuku-Toru', 'Andoni', 'Asari-Toru', 
                      'Bonny', 'Degema', 'Eleme', 'Emuoha', 'Etche', 'Gokana', 'Ikwerre', 'Khana', 
                      'Obio/Akpor', 'Ogba/Egbema/Ndoni', 'Ogu/Bolo', 'Okrika', 'Omuma', 'Opobo/Nkoro', 
                      'Oyigbo', 'Port Harcourt', 'Tai'],
            'Sokoto': ['Binji', 'Bodinga', 'Dange Shuni', 'Gada', 'Goronyo', 'Gudu', 'Gwadabawa', 'Illela', 
                      'Isa', 'Kebbe', 'Kware', 'Rabah', 'Sabon Birni', 'Shagari', 'Silame', 'Sokoto North', 
                      'Sokoto South', 'Tambuwal', 'Tangaza', 'Tureta', 'Wamako', 'Wurno', 'Yabo'],
            'Taraba': ['Ardo Kola', 'Bali', 'Donga', 'Gashaka', 'Gassol', 'Ibi', 'Jalingo', 'Karim Lamido', 
                      'Kurmi', 'Lau', 'Sardauna', 'Takum', 'Ussa', 'Wukari', 'Yorro', 'Zing'],
            'Yobe': ['Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 'Gujba', 'Gulani', 
                    'Jakusko', 'Karasuwa', 'Machina', 'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari'],
            'Zamfara': ['Anka', 'Bakura', 'Birnin Magaji/Kiyaw', 'Bukkuyum', 'Bungudu', 'Chafe', 'Gummi', 
                       'Gusau', 'Kaura Namoda', 'Maradun', 'Maru', 'Shinkafi', 'Talata Mafara', 'Tsafe', 'Zurmi']
        };
        
        // ====================
        // CORE FUNCTIONS
        // ====================
        
        // Initialize form
        function initForm() {
            if (formInitialized) return;
            
            console.log('Initializing employee form...');
            
            // Set up tab system
            setupTabs();
            
            // Set up auto-save
            setupAutoSave();
            
            // Load any saved draft
            setTimeout(loadDraft, 100);
            
            // Set up state-LGA selection - FIXED: Called immediately
            setupStateLga();
            
            // Set up photo upload
            setTimeout(setupPhotoUpload, 200);
            
            // Set up form submission
            setupFormSubmission();
            
            // Set up modals
            setupModals();
            
            // Set up qualifications
            setupQualifications();
            
            // Set up conditional fields
            setupConditionalFields();
            
            // Set up license validation
            setupLicenseValidation();
            
            formInitialized = true;
            console.log('Form initialized successfully');
        }
        
        // ====================
        // TAB MANAGEMENT
        // ====================
        function setupTabs() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            if (!tabButtons.length || !prevBtn || !nextBtn) {
                console.error('Tab elements not found');
                return;
            }
            
            // Tab button click handlers
            tabButtons.forEach((btn, index) => {
                btn.addEventListener('click', () => showTab(index));
            });
            
            // Navigation button handlers
            prevBtn.addEventListener('click', prevTab);
            nextBtn.addEventListener('click', nextTab);
            
            // Load saved tab position
            const savedTab = localStorage.getItem(`employee_<?php echo $employee['id']; ?>_current_tab`);
            if (savedTab !== null) {
                const tabIndex = parseInt(savedTab);
                if (!isNaN(tabIndex) && tabIndex >= 0 && tabIndex < tabs.length) {
                    currentTabIndex = tabIndex;
                }
            }
            
            // Show initial tab
            showTab(currentTabIndex);
        }
        
        function showTab(index) {
            if (index < 0 || index >= tabs.length) return;
            
            currentTabIndex = index;
            
            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach((btn, i) => {
                btn.classList.toggle('active', i === index);
            });
            
            // Show/hide tab content
            document.querySelectorAll('.tab-content').forEach((content, i) => {
                content.classList.toggle('active', i === index);
            });
            
            // Update progress bar
            const progress = ((index + 1) / tabs.length) * 100;
            const progressBar = document.getElementById('tabProgress');
            if (progressBar) {
                progressBar.style.width = `${progress}%`;
            }
            
            // Update navigation buttons
            updateNavButtons();
            
            // Update step indicator
            const indicator = document.getElementById('currentTabIndicator');
            if (indicator) {
                indicator.textContent = `Step ${index + 1} of ${tabs.length}`;
            }
            
            // Save current tab
            localStorage.setItem(`employee_<?php echo $employee['id']; ?>_current_tab`, index);
            
            // Scroll to top for better UX
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function nextTab() {
            if (currentTabIndex < tabs.length - 1) {
                showTab(currentTabIndex + 1);
            } else {
                // Last tab - validate all
                if (validateForm()) {
                    showNotification('Form is complete! You can now submit.', 'success');
                }
            }
        }
        
        function prevTab() {
            if (currentTabIndex > 0) {
                showTab(currentTabIndex - 1);
            }
        }
        
        function updateNavButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            if (!prevBtn || !nextBtn) return;
            
            prevBtn.disabled = currentTabIndex === 0;
            prevBtn.style.opacity = currentTabIndex === 0 ? '0.5' : '1';
            
            nextBtn.innerHTML = currentTabIndex === tabs.length - 1 
                ? 'Review <i class="fas fa-check"></i>' 
                : 'Next <i class="fas fa-arrow-right"></i>';
        }
        
        // ====================
        // FORM VALIDATION
        // ====================
        function validateCurrentTab() {
            const currentTab = document.getElementById(`tab-${tabs[currentTabIndex]}`);
            if (!currentTab) return true;
            
            const requiredFields = currentTab.querySelectorAll('[required]');
            let isValid = true;
            let firstErrorField = null;
            
            requiredFields.forEach(field => {
                field.classList.remove('field-error');
                
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('field-error');
                    
                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                }
            });
            
            if (!isValid && firstErrorField) {
                // Scroll to first error
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstErrorField.focus();
                
                // Show error message
                const fieldName = firstErrorField.previousElementSibling?.textContent || 'Field';
                showNotification(`Please fill in required field: ${fieldName.replace('*', '').trim()}`, 'error');
            }
            
            return isValid;
        }
        
        function validateForm() {
            let allValid = true;
            let errorFields = [];
            
            for (let i = 0; i < tabs.length; i++) {
                const tab = document.getElementById(`tab-${tabs[i]}`);
                if (!tab) continue;
                
                const requiredFields = tab.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        allValid = false;
                        const label = field.closest('.form-group')?.querySelector('label');
                        errorFields.push(label?.textContent?.replace('*', '').trim() || 'Unknown field');
                    }
                });
            }
            
            // Validate qualification entries
            const qualificationEntries = document.querySelectorAll('.qualification-entry');
            qualificationEntries.forEach(entry => {
                const nameInput = entry.querySelector('.qualification-name');
                const yearSelect = entry.querySelector('.qualification-year');
                
                if (nameInput.value.trim() && !yearSelect.value) {
                    allValid = false;
                    yearSelect.classList.add('field-error');
                    errorFields.push('Qualification year');
                } else {
                    yearSelect.classList.remove('field-error');
                }
            });
            
            // Validate license dates
            if (!validateLicenseDates()) {
                allValid = false;
                errorFields.push('License dates');
            }
            
            if (!allValid) {
                const errorMsg = `Missing required fields: ${errorFields.slice(0, 3).join(', ')}${errorFields.length > 3 ? '...' : ''}`;
                showNotification(errorMsg, 'error');
                
                // Go to first tab with errors
                for (let i = 0; i < tabs.length; i++) {
                    const tab = document.getElementById(`tab-${tabs[i]}`);
                    const requiredFields = tab.querySelectorAll('[required]');
                    const hasError = Array.from(requiredFields).some(field => !field.value.trim());
                    
                    if (hasError) {
                        showTab(i);
                        break;
                    }
                }
            }
            
            return allValid;
        }
        
        // ====================
        // AUTO-SAVE FUNCTIONALITY (SILENT)
        // ====================
        function setupAutoSave() {
            const form = document.getElementById('employeeForm');
            if (!form) return;
            
            // Track changes
            form.addEventListener('input', handleFormChange);
            form.addEventListener('change', handleFormChange);
            
            // Periodic auto-save (every 30 seconds)
            autoSaveInterval = setInterval(silentAutoSave, 30000);
            
            // Save before page unload
            window.addEventListener('beforeunload', (e) => {
                if (hasUnsavedChanges) {
                    silentAutoSave();
                }
            });
        }
        
        function handleFormChange(e) {
            // Mark field as modified
            if (e.target.classList.contains('form-control')) {
                e.target.classList.add('field-modified');
            }
            
            // Debounce auto-save
            clearTimeout(inputDebounceTimer);
            inputDebounceTimer = setTimeout(() => {
                hasUnsavedChanges = true;
                scheduleAutoSave();
            }, 1000);
        }
        
        function scheduleAutoSave() {
            clearTimeout(saveDebounceTimer);
            saveDebounceTimer = setTimeout(silentAutoSave, 5000); // Save after 5 seconds of inactivity
        }
        
        function silentAutoSave() {
            if (isSaving || !hasUnsavedChanges) return;
            
            saveDraft(true); // Silent save
        }
        
        function saveDraft(isSilent = false) {
            if (isSaving) {
                if (!isSilent) {
                    showNotification('Already saving, please wait...', 'info');
                }
                return;
            }
            
            const form = document.getElementById('employeeForm');
            if (!form) return;
            
            isSaving = true;
            
            if (!isSilent) {
                showSavingIndicator(true);
            }
            
            // Collect form data
            const formData = new FormData(form);
            const draftData = {};
            
            for (let [key, value] of formData.entries()) {
                draftData[key] = value;
            }
            
            // Collect qualification data
            const qualifications = [];
            document.querySelectorAll('.qualification-entry').forEach(entry => {
                const name = entry.querySelector('.qualification-name').value;
                const year = entry.querySelector('.qualification-year').value;
                if (name || year) {
                    qualifications.push({ name, year });
                }
            });
            
            const draft = {
                data: draftData,
                qualifications: qualifications,
                timestamp: new Date().toISOString(),
                currentTab: currentTabIndex,
                employeeId: <?php echo $employee['id']; ?>,
                version: '1.0'
            };
            
            // Simulate save delay (remove in production)
            setTimeout(() => {
                try {
                    localStorage.setItem(`employee_draft_<?php echo $employee['id']; ?>`, JSON.stringify(draft));
                    hasUnsavedChanges = false;
                    
                    if (!isSilent) {
                        showNotification('Draft saved successfully!', 'success');
                    } else {
                        // Silent notification (brief)
                        showSilentNotification();
                    }
                    
                    console.log('Draft saved at', new Date().toLocaleTimeString());
                } catch (error) {
                    console.error('Error saving draft:', error);
                    if (!isSilent) {
                        showNotification('Error saving draft. Storage might be full.', 'error');
                    }
                } finally {
                    isSaving = false;
                    if (!isSilent) {
                        showSavingIndicator(false);
                    }
                }
            }, 800); // Simulated network delay
        }
        
        function loadDraft() {
            try {
                const saved = localStorage.getItem(`employee_draft_<?php echo $employee['id']; ?>`);
                if (!saved) return;
                
                const draft = JSON.parse(saved);
                
                // Check if draft is for this employee
                if (draft.employeeId !== <?php echo $employee['id']; ?>) {
                    localStorage.removeItem(`employee_draft_<?php echo $employee['id']; ?>`);
                    return;
                }
                
                // Check draft version/age (optional)
                const draftAge = new Date() - new Date(draft.timestamp);
                const maxAge = 7 * 24 * 60 * 60 * 1000; // 7 days
                
                if (draftAge > maxAge) {
                    if (confirm('Found an old draft (over 7 days). Do you want to load it?')) {
                        restoreDraft(draft);
                    } else {
                        localStorage.removeItem(`employee_draft_<?php echo $employee['id']; ?>`);
                    }
                } else {
                    restoreDraft(draft);
                }
                
            } catch (error) {
                console.error('Error loading draft:', error);
                localStorage.removeItem(`employee_draft_<?php echo $employee['id']; ?>`);
            }
        }
        
        function restoreDraft(draft) {
            // Restore form data
            for (const [name, value] of Object.entries(draft.data)) {
                const field = document.querySelector(`[name="${name}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = value;
                    } else if (field.type === 'radio') {
                        const radio = document.querySelector(`[name="${name}"][value="${value}"]`);
                        if (radio) radio.checked = true;
                    } else {
                        field.value = value;
                    }
                }
            }
            
            // Restore qualifications
            if (draft.qualifications && draft.qualifications.length > 0) {
                const container = document.getElementById('qualifications-container');
                if (container) {
                    container.innerHTML = '';
                    draft.qualifications.forEach(qual => {
                        addQualificationField(qual.name, qual.year);
                    });
                }
            }
            
            // Restore tab position
            if (draft.currentTab !== undefined) {
                showTab(draft.currentTab);
            }
            
            // Update conditional fields
            updateConditionalFields();
            
            showNotification('Draft loaded from ' + new Date(draft.timestamp).toLocaleString(), 'info');
        }
        
        // ====================
        // FORM SUBMISSION
        // ====================
        function setupFormSubmission() {
            const form = document.getElementById('employeeForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (!form || !submitBtn) return;
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    return false;
                }
                
                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                submitBtn.disabled = true;
                submitBtn.classList.add('btn-loading');
                
                try {
                    // Clear draft before submission
                    localStorage.removeItem(`employee_draft_<?php echo $employee['id']; ?>`);
                    
                    // Submit form
                    const formData = new FormData(this);
                    
                    // Simulate submission delay
                    setTimeout(() => {
                        // Remove loading state
                        submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Employee Record';
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('btn-loading');
                        
                        // Submit the form
                        this.submit();
                    }, 1000);
                    
                } catch (error) {
                    console.error('Form submission error:', error);
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Employee Record';
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-loading');
                    showNotification('Error submitting form. Please try again.', 'error');
                }
            });
            
            // Manual save draft button
            const saveDraftBtn = document.getElementById('saveDraftBtn');
            if (saveDraftBtn) {
                saveDraftBtn.addEventListener('click', () => saveDraft(false));
            }
        }
        
        // ====================
        // QUALIFICATIONS MANAGEMENT
        // ====================
        function setupQualifications() {
            const addBtn = document.getElementById('add-qualification-btn');
            const container = document.getElementById('qualifications-container');
            
            if (!addBtn || !container) return;
            
            // Add qualification button
            addBtn.addEventListener('click', () => addQualificationField());
            
            // Initialize remove functionality for existing qualification entries
            document.querySelectorAll('.remove-qualification').forEach(button => {
                button.addEventListener('click', function() {
                    const entry = this.closest('.qualification-entry');
                    entry.remove();
                    hasUnsavedChanges = true;
                });
            });
            
            // Update button text for mobile
            updateButtonTextForMobile();
            window.addEventListener('resize', updateButtonTextForMobile);
        }
        
        function addQualificationField(name = '', year = '') {
            const template = document.getElementById('qualification-template');
            const container = document.getElementById('qualifications-container');
            
            if (!template || !container) return;
            
            const templateContent = template.content.cloneNode(true);
            const entry = templateContent.querySelector('.qualification-entry');
            
            // Set values if provided
            const nameInput = entry.querySelector('.qualification-name');
            const yearSelect = entry.querySelector('.qualification-year');
            
            nameInput.value = name;
            yearSelect.value = year;
            
            // Add remove functionality
            const removeBtn = entry.querySelector('.remove-qualification');
            removeBtn.addEventListener('click', function() {
                entry.remove();
                hasUnsavedChanges = true;
            });
            
            // Add change listener
            nameInput.addEventListener('input', () => hasUnsavedChanges = true);
            yearSelect.addEventListener('change', () => hasUnsavedChanges = true);
            
            container.appendChild(entry);
            hasUnsavedChanges = true;
        }
        
        function updateButtonTextForMobile() {
            const isMobile = window.innerWidth <= 768;
            const btnTexts = document.querySelectorAll('.btn-text');
            
            btnTexts.forEach(btnText => {
                btnText.style.display = isMobile ? 'none' : 'inline';
            });
        }
        
        // ====================
        // CONDITIONAL FIELDS
        // ====================
        function setupConditionalFields() {
            // Disability field
            const disabilitySelect = document.getElementById('disability');
            const disabilityTypeContainer = document.getElementById('disabilityTypeContainer');
            
            if (disabilitySelect && disabilityTypeContainer) {
                disabilitySelect.addEventListener('change', function() {
                    disabilityTypeContainer.style.display = this.value === 'Yes' ? 'block' : 'none';
                    hasUnsavedChanges = true;
                });
            }
            
            // Bank Name - Show other bank input
            const bankSelect = document.getElementById('bank_name');
            const otherBankContainer = document.getElementById('otherBankContainer');
            
            if (bankSelect && otherBankContainer) {
                bankSelect.addEventListener('change', function() {
                    otherBankContainer.style.display = this.value === 'Other' ? 'block' : 'none';
                    hasUnsavedChanges = true;
                });
            }
            
            // PFA - Show other PFA input
            const pfaSelect = document.getElementById('pension_fund_admin');
            const otherPFAContainer = document.getElementById('otherPFAContainer');
            
            if (pfaSelect && otherPFAContainer) {
                pfaSelect.addEventListener('change', function() {
                    otherPFAContainer.style.display = this.value === 'Other' ? 'block' : 'none';
                    hasUnsavedChanges = true;
                });
            }
        }
        
        function updateConditionalFields() {
            // Update all conditional fields based on current values
            const disabilitySelect = document.getElementById('disability');
            const disabilityTypeContainer = document.getElementById('disabilityTypeContainer');
            if (disabilitySelect && disabilityTypeContainer) {
                disabilityTypeContainer.style.display = disabilitySelect.value === 'Yes' ? 'block' : 'none';
            }
            
            const bankSelect = document.getElementById('bank_name');
            const otherBankContainer = document.getElementById('otherBankContainer');
            if (bankSelect && otherBankContainer) {
                otherBankContainer.style.display = bankSelect.value === 'Other' ? 'block' : 'none';
            }
            
            const pfaSelect = document.getElementById('pension_fund_admin');
            const otherPFAContainer = document.getElementById('otherPFAContainer');
            if (pfaSelect && otherPFAContainer) {
                otherPFAContainer.style.display = pfaSelect.value === 'Other' ? 'block' : 'none';
            }
        }
        
        // ====================
        // LICENSE VALIDATION
        // ====================
        function setupLicenseValidation() {
            // License number pattern validation
            function normalizeLicenseNumber(value) {
                if (!value) return '';
                
                // Remove spaces and convert to uppercase
                value = value.trim().toUpperCase();
                
                // Extract numbers from the string
                var numbers = value.replace(/\D/g, '');
                
                // For NMCN: Check if it's in format 01490/22/F
                if (value.includes('/')) {
                    // Keep original format but clean up spaces
                    return value.replace(/\s+/g, '');
                }
                
                // For TRCN: Check if it's in format CT/R/01490
                if (value.includes('CT/R/')) {
                    // Keep original format but clean up spaces
                    return value.replace(/\s+/g, '');
                }
                
                // If it's just numbers, return them
                if (numbers) {
                    return numbers;
                }
                
                // Otherwise return original value cleaned
                return value.replace(/\s+/g, '');
            }
            
            // Auto-format license numbers on blur
            const nmcnInput = document.getElementById('nmcn_license_number');
            const trcnInput = document.getElementById('trcn_license_number');
            
            if (nmcnInput) {
                nmcnInput.addEventListener('blur', function() {
                    const normalized = normalizeLicenseNumber(this.value);
                    if (this.value !== normalized) {
                        this.value = normalized;
                        hasUnsavedChanges = true;
                    }
                });
            }
            
            if (trcnInput) {
                trcnInput.addEventListener('blur', function() {
                    const normalized = normalizeLicenseNumber(this.value);
                    if (this.value !== normalized) {
                        this.value = normalized;
                        hasUnsavedChanges = true;
                    }
                });
            }
            
            // Set expiry date based on issued date
            const nmcnIssued = document.getElementById('nmcn_issued_date');
            const nmcnExpiry = document.getElementById('nmcn_expiry_date');
            const trcnIssued = document.getElementById('trcn_issued_date');
            const trcnExpiry = document.getElementById('trcn_expiry_date');
            
            function setExpiryDate(issuedInput, expiryInput, years = 3) {
                if (issuedInput && expiryInput && issuedInput.value) {
                    const issuedDate = new Date(issuedInput.value);
                    if (!isNaN(issuedDate.getTime())) {
                        const expiryDate = new Date(issuedDate);
                        expiryDate.setFullYear(expiryDate.getFullYear() + years);
                        const expiryString = expiryDate.toISOString().split('T')[0];
                        
                        // Only set if expiry is empty or before issued date
                        if (!expiryInput.value || new Date(expiryInput.value) < issuedDate) {
                            expiryInput.value = expiryString;
                            hasUnsavedChanges = true;
                        }
                    }
                }
            }
            
            if (nmcnIssued) {
                nmcnIssued.addEventListener('change', function() {
                    setExpiryDate(nmcnIssued, nmcnExpiry, 3); // 3 years for NMCN
                    hasUnsavedChanges = true;
                });
            }
            
            if (trcnIssued) {
                trcnIssued.addEventListener('change', function() {
                    setExpiryDate(trcnIssued, trcnExpiry, 3); // 3 years for TRCN
                    hasUnsavedChanges = true;
                });
            }
            
            // Set maximum dates to today for issued dates
            const today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('input[type="date"][id$="_issued_date"]').forEach(input => {
                input.max = today;
            });
            
            // Auto-update status based on expiry dates
            function updateLicenseStatus() {
                const today = new Date();
                
                // NMCN
                const nmcnExpiry = document.getElementById('nmcn_expiry_date');
                const nmcnStatus = document.getElementById('nmcn_status');
                
                if (nmcnExpiry && nmcnExpiry.value && nmcnStatus) {
                    const expiryDate = new Date(nmcnExpiry.value);
                    const daysUntilExpiry = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
                    
                    if (daysUntilExpiry < 0 && nmcnStatus.value !== 'Expired') {
                        nmcnStatus.value = 'Expired';
                        hasUnsavedChanges = true;
                    } else if (daysUntilExpiry >= 0 && nmcnStatus.value !== 'Active') {
                        nmcnStatus.value = 'Active';
                        hasUnsavedChanges = true;
                    }
                }
                
                // TRCN
                const trcnExpiry = document.getElementById('trcn_expiry_date');
                const trcnStatus = document.getElementById('trcn_status');
                
                if (trcnExpiry && trcnExpiry.value && trcnStatus) {
                    const expiryDate = new Date(trcnExpiry.value);
                    const daysUntilExpiry = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
                    
                    if (daysUntilExpiry < 0 && trcnStatus.value !== 'Expired') {
                        trcnStatus.value = 'Expired';
                        hasUnsavedChanges = true;
                    } else if (daysUntilExpiry >= 0 && trcnStatus.value !== 'Active') {
                        trcnStatus.value = 'Active';
                        hasUnsavedChanges = true;
                    }
                }
            }
            
            // Attach event listeners for license status updates
            document.querySelectorAll('#nmcn_expiry_date, #trcn_expiry_date').forEach(input => {
                input.addEventListener('change', updateLicenseStatus);
                input.addEventListener('change', () => hasUnsavedChanges = true);
            });
            
            // Initial license status update on page load
            updateLicenseStatus();
        }
        
        function validateLicenseDates() {
            const nmcnIssued = document.getElementById('nmcn_issued_date');
            const nmcnExpiry = document.getElementById('nmcn_expiry_date');
            const trcnIssued = document.getElementById('trcn_issued_date');
            const trcnExpiry = document.getElementById('trcn_expiry_date');
            
            let isValid = true;
            let message = '';
            
            // Validate NMCN dates
            if (nmcnIssued && nmcnExpiry && nmcnIssued.value && nmcnExpiry.value) {
                const issued = new Date(nmcnIssued.value);
                const expiry = new Date(nmcnExpiry.value);
                
                if (expiry <= issued) {
                    isValid = false;
                    message = 'NMCN expiry date must be after issue date.';
                }
            }
            
            // Validate TRCN dates
            if (trcnIssued && trcnExpiry && trcnIssued.value && trcnExpiry.value) {
                const issued = new Date(trcnIssued.value);
                const expiry = new Date(trcnExpiry.value);
                
                if (expiry <= issued) {
                    isValid = false;
                    message = message ? message + '\nTRCN expiry date must be after issue date.' : 
                                       'TRCN expiry date must be after issue date.';
                }
            }
            
            if (!isValid) {
                showNotification(message, 'error');
                return false;
            }
            
            return true;
        }
        
        // ====================
        // STATE-LGA SELECTION
        // ====================
        function setupStateLga() {
            const stateSelect = document.getElementById('state');
            const lgaSelect = document.getElementById('local_govt_area');
            
            if (!stateSelect || !lgaSelect) {
                console.error('State or LGA select elements not found');
                return;
            }
            
            console.log('Setting up State-LGA selection...');
            
            // Get current values from PHP
            const currentState = '<?php echo isset($employee["state"]) ? addslashes($employee["state"]) : ""; ?>';
            const currentLGA = '<?php echo isset($employee["local_govt_area"]) ? addslashes($employee["local_govt_area"]) : ""; ?>';
            
            console.log('Current state:', currentState, 'Current LGA:', currentLGA);
            
            // Set current state value if it exists
            if (currentState) {
                stateSelect.value = currentState;
                // Populate LGAs immediately for current state
                setTimeout(() => populateLGAs(currentState, currentLGA), 100);
            }
            
            // Handle state change
            stateSelect.addEventListener('change', function() {
                const state = this.value;
                console.log('State changed to:', state);
                if (state && nigerianLGAs[state]) {
                    populateLGAs(state);
                } else {
                    lgaSelect.innerHTML = '<option value="">Select LGA</option>';
                    lgaSelect.disabled = true;
                }
                hasUnsavedChanges = true;
            });
            
            // Also populate LGAs on page load for any selected state
            if (stateSelect.value) {
                setTimeout(() => populateLGAs(stateSelect.value, currentLGA), 200);
            }
            
            // Auto-update geopolitical zone based on state
            const zoneMapping = {
                'North Central': ['FCT', 'Nasarawa', 'Kogi', 'Kwara', 'Niger', 'Plateau', 'Benue'],
                'North East': ['Adamawa', 'Bauchi', 'Borno', 'Gombe', 'Taraba', 'Yobe'],
                'North West': ['Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Sokoto', 'Zamfara'],
                'South East': ['Abia', 'Anambra', 'Ebonyi', 'Enugu', 'Imo'],
                'South South': ['Akwa Ibom', 'Bayelsa', 'Cross River', 'Delta', 'Edo', 'Rivers'],
                'South West': ['Ekiti', 'Lagos', 'Ogun', 'Ondo', 'Osun', 'Oyo']
            };
            
            stateSelect.addEventListener('change', function() {
                const selectedState = this.value;
                const zoneSelect = document.getElementById('geopolitical_zone');
                
                if (zoneSelect && selectedState) {
                    for (const [zone, states] of Object.entries(zoneMapping)) {
                        if (states.includes(selectedState)) {
                            zoneSelect.value = zone;
                            hasUnsavedChanges = true;
                            break;
                        }
                    }
                }
            });
        }
        
        function populateLGAs(state, selectedLGA = '') {
            const lgaSelect = document.getElementById('local_govt_area');
            if (!lgaSelect) return;
            
            console.log('Populating LGAs for state:', state);
            
            // Clear existing options
            lgaSelect.innerHTML = '<option value="">Select Local Government</option>';
            lgaSelect.disabled = false;
            
            if (state && nigerianLGAs[state]) {
                // Sort LGAs alphabetically
                const sortedLGAs = nigerianLGAs[state].sort();
                
                sortedLGAs.forEach(lga => {
                    const option = document.createElement('option');
                    option.value = lga;
                    option.textContent = lga;
                    if (lga === selectedLGA) {
                        option.selected = true;
                    }
                    lgaSelect.appendChild(option);
                });
                
                // If selectedLGA wasn't found in the list, try to find a match
                if (selectedLGA && !lgaSelect.value) {
                    const matchingOption = Array.from(lgaSelect.options).find(option => 
                        option.value.toLowerCase().includes(selectedLGA.toLowerCase()) || 
                        selectedLGA.toLowerCase().includes(option.value.toLowerCase())
                    );
                    if (matchingOption) {
                        matchingOption.selected = true;
                    }
                }
                
                console.log(`Loaded ${sortedLGAs.length} LGAs for ${state}`);
            } else {
                console.warn(`No LGAs found for state: ${state}`);
                lgaSelect.innerHTML = '<option value="">No LGAs available</option>';
                lgaSelect.disabled = true;
            }
        }
        
        // ====================
        // PHOTO UPLOAD
        // ====================
        function setupPhotoUpload() {
            const dropArea = document.getElementById('photoDropArea');
            const fileInput = document.getElementById('passport_photo');
            const uploadPreview = document.getElementById('uploadPreview');
            const previewImage = document.getElementById('previewImage');
            const removeImageBtn = document.getElementById('removeImage');
            
            if (!dropArea || !fileInput) return;
            
            // Click to select file
            dropArea.addEventListener('click', () => fileInput.click());
            
            // File input change
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    handleImageUpload(this.files[0]);
                }
            });

            function handleImageUpload(file) {
                // Check file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    showNotification('File size must be less than 2MB', 'error');
                    fileInput.value = '';
                    return;
                }
                
                // Check file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    showNotification('Only JPG, JPEG, and PNG files are allowed', 'error');
                    fileInput.value = '';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    uploadPreview.style.display = 'block';
                    hasUnsavedChanges = true;
                }
                
                reader.readAsDataURL(file);
            }
            
            // Remove image button
            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function() {
                    fileInput.value = '';
                    uploadPreview.style.display = 'none';
                    previewImage.src = '#';
                    hasUnsavedChanges = true;
                });
            }
        }
        
        // ====================
        // NOTIFICATIONS
        // ====================
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('saveNotification');
            if (!notification) return;
            
            // Update message and style
            notification.querySelector('span').textContent = message;
            notification.style.background = type === 'error' ? 'var(--danger-color)' : 
                                          type === 'success' ? 'var(--success-color)' : 
                                          'var(--secondary-color)';
            
            // Show notification
            notification.classList.add('show');
            
            // Auto-hide after 3 seconds
            clearTimeout(saveNotificationTimeout);
            saveNotificationTimeout = setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
        
        function showSilentNotification() {
            const notification = document.getElementById('saveNotification');
            if (!notification) return;
            
            notification.querySelector('span').textContent = 'Auto-saved';
            notification.style.background = 'var(--success-color)';
            notification.classList.add('show');
            
            // Hide quickly for silent save
            clearTimeout(saveNotificationTimeout);
            saveNotificationTimeout = setTimeout(() => {
                notification.classList.remove('show');
            }, 1500);
        }
        
        function showSavingIndicator(show) {
            const indicator = document.getElementById('formSaving');
            if (!indicator) return;
            
            if (show) {
                indicator.classList.add('show');
            } else {
                indicator.classList.remove('show');
            }
        }
        
        // ====================
        // MODAL FUNCTIONS
        // ====================
        function setupModals() {
            // Delete modal
            const deleteBtn = document.getElementById('deleteBtn');
            const deleteModal = document.getElementById('deleteModal');
            const closeDeleteModalBtn = document.getElementById('closeDeleteModal');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            
            if (deleteBtn && deleteModal) {
                deleteBtn.addEventListener('click', openDeleteModal);
            }
            
            if (closeDeleteModalBtn) {
                closeDeleteModalBtn.addEventListener('click', closeDeleteModal);
            }
            
            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener('click', closeDeleteModal);
            }
            
            // Close modal on outside click
            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('modal-overlay')) {
                    closeDeleteModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        }
        
        function openDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }
        
        // ====================
        // INITIALIZATION
        // ====================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing form...');
            
            // Initialize with a slight delay to ensure DOM is ready
            setTimeout(() => {
                initForm();
            }, 50);
            
            // Clean up on page unload
            window.addEventListener('unload', function() {
                clearInterval(autoSaveInterval);
                clearTimeout(inputDebounceTimer);
                clearTimeout(saveDebounceTimer);
                clearTimeout(saveNotificationTimeout);
            });
        });
        
        // Make functions available globally for inline onclick handlers
        window.addQualificationField = addQualificationField;
    </script>
</body>
</html>