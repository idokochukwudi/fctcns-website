<?php
/**
 * CONTACT SUCCESS PAGE
 * File: /app/views/pages/contact/contact-success.php
 * 
 * Purpose: Displayed after successful form submission
 * Shows: Reference number, confirmation message, next steps
 * Accessed via: /contact/success
 * 
 * @package FCTCNS
 */

// Extract data passed from controller
extract($data ?? []);

// Set defaults
$baseUrl = $baseUrl ?? '/';
$submission = $submission ?? null;
$reference = $submission['id'] ?? date('Ymd') . rand(100, 999);
$name = $submission['name'] ?? $_SESSION['contact_name'] ?? 'there';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Thank you for contacting FCT College of Nursing Sciences. Your message has been received.">
    <title>Message Sent - FCT College of Nursing Sciences</title>
    
    <!-- Font Awesome 6 (CDN - works everywhere) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ---------- DESIGN SYSTEM ---------- */
        :root {
            --primary: #2A5C7D;
            --primary-dark: #1E4560;
            --primary-light: #EEF5F9;
            --accent: #E9B741;
            --accent-dark: #DAA520;
            --success: #10B981;
            --success-dark: #059669;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-600: #4B5563;
            --gray-800: #1F2937;
            --gray-900: #111827;
            --white: #FFFFFF;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(145deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            line-height: 1.5;
            color: var(--gray-800);
        }
        
        /* ---------- MAIN CONTAINER ---------- */
        .success-wrapper {
            max-width: 900px;
            width: 100%;
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* ---------- CARD COMPONENT ---------- */
        .success-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }
        
        /* ---------- HEADER SECTION ---------- */
        .success-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 48px 32px;
            text-align: center;
            position: relative;
        }
        
        .success-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--accent), var(--accent-dark));
        }
        
        .checkmark-circle {
            width: 96px;
            height: 96px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: var(--shadow-md);
            animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        .checkmark-circle i {
            font-size: 52px;
            color: var(--success);
        }
        
        .success-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }
        
        .success-header p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 400;
            max-width: 500px;
            margin: 0 auto;
        }
        
        /* ---------- BODY SECTION ---------- */
        .success-body {
            padding: 48px;
        }
        
        .greeting {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--gray-800);
            font-weight: 500;
        }
        
        .greeting span {
            color: var(--primary);
            font-weight: 700;
            border-bottom: 3px solid var(--accent);
            padding-bottom: 4px;
        }
        
        .message-confirmation {
            background: var(--primary-light);
            padding: 24px;
            border-radius: var(--radius);
            border-left: 6px solid var(--primary);
            margin-bottom: 32px;
            font-size: 1.05rem;
            color: var(--gray-700);
        }
        
        /* ---------- INFO GRID (REFERENCE, DATE, RESPONSE TIME) ---------- */
        .info-highlight {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin: 32px 0;
            padding: 24px;
            background: var(--gray-50);
            border-radius: var(--radius);
            border: 1px solid var(--gray-200);
        }
        
        .info-item {
            text-align: center;
        }
        
        .info-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--gray-600);
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .info-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            font-family: 'Inter', sans-serif;
        }
        
        .info-value small {
            font-size: 0.9rem;
            color: var(--gray-600);
            font-weight: 400;
        }
        
        /* ---------- TIMELINE / PROCESS ---------- */
        .process-timeline {
            margin: 40px 0;
            padding: 28px;
            background: var(--white);
            border: 2px dashed var(--accent);
            border-radius: var(--radius);
        }
        
        .process-timeline h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .timeline-steps {
            display: flex;
            justify-content: space-between;
            gap: 16px;
        }
        
        .step {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .step-number {
            width: 44px;
            height: 44px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-900);
            font-weight: 800;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .step-content {
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .step-content small {
            display: block;
            font-size: 0.8rem;
            color: var(--gray-600);
            font-weight: 400;
            margin-top: 4px;
        }
        
        /* ---------- NEXT STEPS / RESOURCES ---------- */
        .next-steps {
            margin: 40px 0 32px;
            padding: 28px;
            background: linear-gradient(145deg, var(--primary-light), var(--white));
            border-radius: var(--radius);
            border: 1px solid var(--gray-200);
        }
        
        .next-steps h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .resources-list {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        
        .resources-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
        }
        
        .resources-list i {
            color: var(--accent-dark);
            width: 20px;
            text-align: center;
        }
        
        .resources-list a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        
        .resources-list a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        /* ---------- ACTION BUTTONS ---------- */
        .action-group {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: var(--primary);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-secondary {
            background: var(--white);
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .btn-secondary:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
        }
        
        .btn-accent {
            background: var(--accent);
            color: var(--gray-900);
            border: 2px solid var(--accent);
        }
        
        .btn-accent:hover {
            background: var(--accent-dark);
            border-color: var(--accent-dark);
            transform: translateY(-3px);
        }
        
        /* ---------- FOOTER NOTE ---------- */
        .email-confirmation {
            margin-top: 40px;
            padding: 20px;
            background: var(--gray-50);
            border-radius: var(--radius);
            text-align: center;
            color: var(--gray-600);
            font-size: 0.95rem;
            border: 1px solid var(--gray-200);
        }
        
        /* ---------- ANIMATIONS ---------- */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.1);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                transform: scale(1);
            }
        }
        
        /* ---------- RESPONSIVE DESIGN ---------- */
        @media (max-width: 768px) {
            .success-header {
                padding: 40px 24px;
            }
            
            .success-header h1 {
                font-size: 2rem;
            }
            
            .success-body {
                padding: 32px 24px;
            }
            
            .info-highlight {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .timeline-steps {
                flex-direction: column;
            }
            
            .step {
                width: 100%;
            }
            
            .resources-list {
                grid-template-columns: 1fr;
            }
            
            .action-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .success-header h1 {
                font-size: 1.75rem;
            }
            
            .greeting {
                font-size: 1.1rem;
            }
            
            .info-value {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="success-wrapper">
        <div class="success-card">
            <!-- HEADER: Success Icon & Title -->
            <div class="success-header">
                <div class="checkmark-circle">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Message Sent!</h1>
                <p>We've received your inquiry</p>
            </div>
            
            <!-- BODY: Confirmation & Details -->
            <div class="success-body">
                <!-- Personalized Greeting -->
                <div class="greeting">
                    <i class="fas fa-user-check" style="color: var(--primary); margin-right: 8px;"></i>
                    Dear <span><?php echo htmlspecialchars($name); ?></span>,
                </div>
                
                <!-- Confirmation Message -->
                <div class="message-confirmation">
                    <i class="fas fa-check-circle" style="color: var(--success); margin-right: 10px;"></i>
                    Thank you for contacting FCT College of Nursing Sciences. 
                    Your message has been successfully delivered to our administrative team.
                </div>
                
                <!-- Key Information: Reference, Date, Response Time -->
                <div class="info-highlight">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-hashtag"></i> Reference
                        </div>
                        <div class="info-value">#<?php echo htmlspecialchars($reference); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar"></i> Date
                        </div>
                        <div class="info-value"><?php echo date('M d, Y'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-clock"></i> Response
                        </div>
                        <div class="info-value">24-48 <small>hours</small></div>
                    </div>
                </div>
                
                <!-- Process Timeline: What Happens Next -->
                <div class="process-timeline">
                    <h3>
                        <i class="fas fa-tasks" style="color: var(--accent);"></i>
                        Our Response Process
                    </h3>
                    <div class="timeline-steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                Review
                                <small>Quality check & department routing</small>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                Assign
                                <small>Directed to specialist</small>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                Respond
                                <small>Reply sent to your email</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Resources: What To Do While Waiting -->
                <div class="next-steps">
                    <h4>
                        <i class="fas fa-compass" style="color: var(--accent);"></i>
                        Explore While You Wait
                    </h4>
                    <ul class="resources-list">
                        <li>
                            <i class="fas fa-graduation-cap"></i>
                            <a href="<?php echo $baseUrl; ?>programs">Academic Programs</a>
                        </li>
                        <li>
                            <i class="fas fa-file-signature"></i>
                            <a href="<?php echo $baseUrl; ?>admissions">Admission Requirements</a>
                        </li>
                        <li>
                            <i class="fas fa-calendar-alt"></i>
                            <a href="<?php echo $baseUrl; ?>events">Upcoming Events</a>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <a href="<?php echo $baseUrl; ?>campus-tour">Schedule Campus Tour</a>
                        </li>
                        <li>
                            <i class="fas fa-question-circle"></i>
                            <a href="<?php echo $baseUrl; ?>faq">Frequently Asked Questions</a>
                        </li>
                        <li>
                            <i class="fas fa-dollar-sign"></i>
                            <a href="<?php echo $baseUrl; ?>financial-aid">Financial Aid</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Call to Action Buttons -->
                <div class="action-group">
                    <a href="<?php echo $baseUrl; ?>" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Home
                    </a>
                    <a href="<?php echo $baseUrl; ?>admissions/apply" class="btn btn-accent">
                        <i class="fas fa-pencil-alt"></i> Apply Now
                    </a>
                    <a href="<?php echo $baseUrl; ?>contact" class="btn btn-primary">
                        <i class="fas fa-envelope"></i> New Message
                    </a>
                </div>
                
                <!-- Email Confirmation Note -->
                <div class="email-confirmation">
                    <i class="fas fa-envelope-open-text" style="margin-right: 8px; color: var(--primary);"></i>
                    A confirmation has been sent to your email. 
                    Please check your spam folder if not received within 15 minutes.
                </div>
            </div>
        </div>
        
        <!-- Brand Signature -->
        <div style="text-align: center; margin-top: 24px; color: rgba(255,255,255,0.8); font-size: 0.85rem;">
            FCT College of Nursing Sciences, Gwagwalada — Excellence in Nursing Education
        </div>
    </div>
</body>
</html>