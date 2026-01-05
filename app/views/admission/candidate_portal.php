<?php
// app/views/admission/candidate_portal.php
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="Check your 2025/2026 ND Nursing Programme admission status at FCT College of Nursing Sciences">
    <meta name="keywords" content="admission status, nursing programme, FCT College of Nursing Sciences, verification">
    <title>Admission Status Verification | FCT College of Nursing Sciences</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #1a5fb4;
            --secondary-color: #26a269;
            --accent-color: #e5a50a;
            --danger-color: #c01c28;
            --success-color: #26a269;
            --text-dark: #1e1e1e;
            --text-light: #666666;
            --background-light: #f6f8fa;
            --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--background-light);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header Styles */
        .header-container {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d4a9e 100%);
            color: white;
            padding: 2rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .logo-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        .page-title {
            font-weight: 700;
            font-size: clamp(1.5rem, 4vw, 2rem);
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .page-subtitle {
            opacity: 0.95;
            font-size: clamp(0.9rem, 3vw, 1rem);
            text-align: center;
        }

        /* Main Container */
        .main-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Status Alert (Always at Top) */
        .status-container {
            margin-bottom: 2rem;
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0, 0, 0, 0.06);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* Alert Styles */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1.25rem;
            margin-bottom: 0;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }

        .alert h5 {
            font-weight: 700;
            margin-bottom: 0.75rem;
            font-size: 1.15rem;
        }

        .alert-icon {
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.accepted {
            background: #d4edda;
            color: #155724;
            border: 2px solid #28a745;
        }

        .status-badge.approved {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffc107;
        }

        /* Detail Table */
        .detail-table {
            width: 100%;
            margin: 1.5rem 0;
        }

        .detail-table th,
        .detail-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .detail-table th {
            font-weight: 600;
            color: var(--text-light);
            width: 40%;
            background: rgba(0, 0, 0, 0.02);
        }

        .detail-table tr:last-child th,
        .detail-table tr:last-child td {
            border-bottom: none;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .input-group {
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
        }

        .input-group-text {
            background: white;
            border: none;
            color: var(--primary-color);
            padding: 1rem 1.25rem;
        }

        .form-control {
            border: none;
            padding: 1rem;
            font-size: 1rem;
        }

        .form-control:focus {
            box-shadow: none;
            outline: none;
        }

        /* Button Styles */
        .btn {
            padding: 0.875rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: #0d4a9e;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26, 95, 180, 0.3);
        }

        .btn-outline-primary {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-outline-secondary {
            background: white;
            border: 2px solid #dee2e6;
            color: var(--text-dark);
        }

        .btn-outline-secondary:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
        }

        /* Action Buttons Container */
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 1.5rem;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .info-item {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .info-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .info-content h6 {
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }

        .info-content p {
            color: var(--text-light);
            margin: 0;
            font-size: 0.875rem;
        }

        /* Contact Section */
        .contact-section {
            text-align: center;
            padding: 2rem 0;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            margin-top: 2rem;
        }

        .contact-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .contact-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .contact-link:hover {
            background: rgba(26, 95, 180, 0.1);
        }

        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }
            
            .print-section,
            .print-section * {
                visibility: visible;
            }
            
            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            
            .btn,
            .no-print,
            .header-container,
            .contact-section {
                display: none !important;
            }
            
            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }

        /* Animation */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: slideDown 0.4s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
            
            .detail-table {
                font-size: 0.9rem;
            }
            
            .detail-table th,
            .detail-table td {
                padding: 0.75rem;
            }
        }

        /* Loading State */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Focus visible for accessibility */
        *:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header-container">
        <div class="container">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <h1 class="page-title">Admission Status Portal</h1>
            <p class="page-subtitle">2025/2026 ND Nursing Programme • FCT College of Nursing Sciences</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Status Container (Always appears first when there's a result/error) -->
        <div class="status-container" id="statusContainer">
            <?php if ($searchPerformed && !empty($result)): ?>
                <!-- Success Result -->
                <div class="card animate-in print-section">
                    <div class="alert alert-success d-flex align-items-start">
                        <i class="fas fa-check-circle alert-icon"></i>
                        <div class="flex-grow-1">
                            <h5 class="mb-2">
                                <i class="fas fa-check-circle me-2"></i>Admission Status Verified Successfully
                            </h5>
                            <div class="text-center my-3">
                                <?php if ($result['admission_status'] == 'Accepted'): ?>
                                    <div class="status-badge accepted">
                                        <i class="fas fa-check-circle"></i>
                                        Admission Accepted
                                    </div>
                                <?php else: ?>
                                    <div class="status-badge approved">
                                        <i class="fas fa-clock"></i>
                                        Admission Approved
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Candidate Information</h5>
                        <div class="table-responsive">
                            <table class="detail-table">
                                <tbody>
                                    <tr>
                                        <th>Registration Number</th>
                                        <td class="fw-bold"><?php echo htmlspecialchars($result['registration_number']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Candidate Name</th>
                                        <td class="fw-bold"><?php echo htmlspecialchars($result['candidate_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Serial Number</th>
                                        <td class="fw-bold"><?php echo htmlspecialchars($result['serial_number']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Admission Status</th>
                                        <td>
                                            <?php if ($result['admission_status'] == 'Accepted'): ?>
                                                <span class="badge bg-success px-3 py-2">
                                                    <i class="fas fa-check-circle me-1"></i>Accepted
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning px-3 py-2">
                                                    <i class="fas fa-clock me-1"></i>Approved
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Verification Date</th>
                                        <td><?php echo date('F j, Y \a\t g:i A'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Instructions -->
                        <?php if ($result['admission_status'] == 'Accepted'): ?>
                            <div class="alert alert-info mt-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Next Steps for Accepted Candidates
                                </h6>
                                <ol class="mb-0 ps-3">
                                    <li class="mb-2">Print your JAMB Admission Letter (Institution & Personal copies)</li>
                                    <li class="mb-2">Report to the College for documentation and registration</li>
                                    <li>Bring the Institution copy of your admission letter with you</li>
                                </ol>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning mt-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Important Action Required
                                </h6>
                                <ol class="mb-3 ps-3">
                                    <li class="mb-2">Log in to JAMB CAPS to accept your admission immediately</li>
                                    <li class="mb-2">Print your JAMB Admission Letter (Institution & Personal copies)</li>
                                    <li>Report to the College with the Institution copy for further action</li>
                                </ol>
                                <div class="alert alert-danger mt-3 mb-0">
                                    <i class="fas fa-calendar-times me-2"></i>
                                    <strong>Deadline: January 9, 2025</strong> - Admissions not accepted by this date will be withdrawn
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Important Dates for Students -->
                        <div class="mt-4 pt-4" style="border-top: 1px solid rgba(0, 0, 0, 0.06);">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>Important Dates
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="alert alert-info mb-0">
                                        <h6 class="fw-bold mb-2">
                                            <i class="fas fa-door-open me-2"></i>Resumption
                                        </h6>
                                        <p class="mb-0">January 6, 2025</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-info mb-0">
                                        <h6 class="fw-bold mb-2">
                                            <i class="fas fa-users me-2"></i>Orientation
                                        </h6>
                                        <p class="mb-0">January 6-9, 2025</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-info mb-0">
                                        <h6 class="fw-bold mb-2">
                                            <i class="fas fa-chalkboard-teacher me-2"></i>Lectures Start
                                        </h6>
                                        <p class="mb-0">January 12, 2025</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-warning mb-0">
                                        <h6 class="fw-bold mb-2">
                                            <i class="fas fa-calendar-times me-2"></i>Acceptance Deadline
                                        </h6>
                                        <p class="mb-0">January 9, 2025</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons no-print">
                            <button onclick="window.print()" class="btn btn-outline-secondary">
                                <i class="fas fa-print"></i>Print Status
                            </button>
                            <button onclick="checkAnotherCandidate()" class="btn btn-primary">
                                <i class="fas fa-search"></i>Check Another Candidate
                            </button>
                        </div>
                    </div>
                </div>
            <?php elseif (!empty($error) && $searchPerformed): ?>
                <!-- Error Result -->
                <div class="card animate-in">
                    <div class="alert alert-danger d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle alert-icon"></i>
                        <div class="flex-grow-1">
                            <h5>Record Not Found</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6 class="fw-bold mb-3">Possible Reasons:</h6>
                            <ul class="mb-0">
                                <li class="mb-2">The registration number may be incorrect</li>
                                <li class="mb-2"><strong>Also check JAMB CAPS for your admission status</strong></li>
                                <li>Contact the admissions office if you believe this is an error</li>
                            </ul>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button onclick="checkAnotherCandidate()" class="btn btn-primary">
                                <i class="fas fa-search"></i>Try Another Search
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Search Form -->
        <div class="card" id="searchFormCard">
            <div class="card-header">
                <i class="fas fa-search me-2"></i>
                <?php echo $searchPerformed ? 'Check Another Candidate' : 'Check Your Admission Status'; ?>
            </div>
            <div class="card-body">
                <form method="POST" id="statusCheckForm">
                    <div class="mb-4">
                        <label for="regNumberInput" class="form-label">Registration Number</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            <input type="text" 
                                   name="reg_number" 
                                   id="regNumberInput"
                                   class="form-control" 
                                   placeholder="Example: 202551998000BF" 
                                   required
                                   autocomplete="off"
                                   aria-label="Registration Number">
                        </div>
                        <small class="text-muted mt-2 d-block">
                            Enter your registration number exactly as provided in your application
                        </small>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" style="min-width: 250px;">
                            <i class="fas fa-search"></i>Verify Admission Status
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Important Information Grid -->
        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon" style="background: #28a745;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="info-content">
                    <h6>Acceptance Deadline</h6>
                    <p>January 9, 2025</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon" style="background: var(--primary-color);">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="info-content">
                    <h6>Resumption Date</h6>
                    <p>January 6, 2025</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon" style="background: #17a2b8;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="info-content">
                    <h6>Orientation Period</h6>
                    <p>January 6-9, 2025</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon" style="background: #6c757d;">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="info-content">
                    <h6>Lectures Begin</h6>
                    <p>January 12, 2025</p>
                </div>
            </div>
        </div>
        
        <!-- Contact Section -->
        <div class="contact-section no-print">
            <h6 class="fw-bold mb-3">Need Assistance?</h6>
            <div class="contact-links">
                <a href="mailto:admissions@fctcns.edu.ng" class="contact-link">
                    <i class="fas fa-envelope"></i>
                    admissions@fctcns.edu.ng
                </a>
                <a href="tel:08082775076" class="contact-link">
                    <i class="fas fa-phone"></i>
                    08082775076 (WhatsApp Only)
                </a>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Scroll to status immediately when page loads with results
        document.addEventListener('DOMContentLoaded', function() {
            const statusContainer = document.getElementById('statusContainer');
            const inputField = document.getElementById('regNumberInput');
            
            <?php if ($searchPerformed && (!empty($result) || !empty($error))): ?>
                // Scroll to status container immediately
                setTimeout(() => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                    statusContainer.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }, 100);
            <?php else: ?>
                // Focus on input for new search
                if (inputField) {
                    inputField.focus();
                }
            <?php endif; ?>
        });

        // Check another candidate function
        function checkAnotherCandidate() {
            const inputField = document.getElementById('regNumberInput');
            const searchCard = document.getElementById('searchFormCard');
            
            // Clear and focus input
            inputField.value = '';
            inputField.focus();
            
            // Scroll to search form
            searchCard.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }

        // Form submission handler
        document.getElementById('statusCheckForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const inputField = document.getElementById('regNumberInput');
            
            // Validate input
            if (!inputField.value.trim()) {
                e.preventDefault();
                inputField.focus();
                return false;
            }
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Verifying...';
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
        });

        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Handle offline state
        window.addEventListener('offline', function() {
            alert('You are offline. Please check your internet connection.');
        });
    </script>
</body>
</html>