<?php
/**
 * Resources Page View
 * 
 * @package FCT_CNS
 */

// Extract data passed from controller
extract($data ?? []);

// Helper function for safe output
if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// Set default values
$page_title = $page_title ?? 'Resources - FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Educational resources, research materials, and publications';
$estimated_launch = $estimated_launch ?? 'Q2 2026';
$features = $features ?? [
    'Study Materials',
    'Lab Manuals',
    'Research Papers',
    'Video Tutorials'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e($page_description); ?>">
    <title><?php echo e($page_title); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    /* EMERGENCY FULL WIDTH OVERRIDE */
body .main-content {
    padding: 0 !important;
    max-width: 100vw !important;
}

.hero-section {
    width: 100vw !important;
    position: relative !important;
    left: 50% !important;
    right: 50% !important;
    margin-left: -50vw !important;
    margin-right: -50vw !important;
}
    </style>
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f5f0ff 0%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1A1F2E;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
        }
        
        /* Card Styles */
        .resources-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(108, 48, 130, 0.25);
            overflow: hidden;
            position: relative;
        }
        
        /* Header with Purple & Gold */
        .card-header {
            background: linear-gradient(135deg, #6C3082 0%, #8A4FA0 100%);
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(201, 164, 74, 0.2) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }
        
        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #C9A44A, #D8B86C, #C9A44A);
        }
        
        .card-header i {
            font-size: 4rem;
            color: #C9A44A;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
            animation: float 3s ease-in-out infinite;
        }
        
        .card-header h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .card-header p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.9);
            position: relative;
            z-index: 2;
        }
        
        /* Body Content */
        .card-body {
            padding: 50px 40px;
            text-align: center;
        }
        
        /* Construction Animation */
        .construction-icon {
            margin-bottom: 30px;
        }
        
        .construction-icon i {
            font-size: 5rem;
            color: #6C3082;
            opacity: 0.8;
            animation: hammer 2s ease-in-out infinite;
        }
        
        .progress-container {
            max-width: 400px;
            margin: 30px auto;
        }
        
        .progress-bar {
            height: 8px;
            background: #E9EDF2;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .progress-fill {
            height: 100%;
            width: 65%;
            background: linear-gradient(90deg, #6C3082, #C9A44A);
            border-radius: 10px;
            animation: progress 2s ease-in-out infinite alternate;
        }
        
        .progress-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            color: #6C3082;
            font-weight: 600;
        }
        
        /* Message */
        .message {
            background: #F8F0FC;
            border-radius: 20px;
            padding: 30px;
            margin: 30px 0;
            border: 1px solid rgba(108, 48, 130, 0.2);
        }
        
        .message h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            color: #6C3082;
            margin-bottom: 15px;
        }
        
        .message p {
            color: #4a5568;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
        
        .message p:last-child {
            margin-bottom: 0;
        }
        
        /* Feature Grid */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }
        
        .feature-item {
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px -10px rgba(108, 48, 130, 0.1);
            border: 1px solid rgba(201, 164, 74, 0.2);
            transition: transform 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateY(-5px);
        }
        
        .feature-item i {
            font-size: 2rem;
            color: #C9A44A;
            margin-bottom: 10px;
        }
        
        .feature-item h3 {
            font-size: 1.1rem;
            color: #6C3082;
            margin-bottom: 5px;
        }
        
        .feature-item p {
            font-size: 0.9rem;
            color: #718096;
        }
        
        /* Buttons */
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #6C3082, #8A4FA0);
            color: white;
            box-shadow: 0 10px 20px -5px rgba(108, 48, 130, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(108, 48, 130, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #6C3082;
            border: 2px solid #6C3082;
        }
        
        .btn-secondary:hover {
            background: #6C3082;
            color: white;
            transform: translateY(-2px);
        }
        
        /* Footer */
        .card-footer {
            background: #F9F3FC;
            padding: 20px 40px;
            border-top: 1px solid rgba(108, 48, 130, 0.1);
            text-align: center;
        }
        
        .card-footer p {
            color: #718096;
            font-size: 0.95rem;
        }
        
        .card-footer a {
            color: #6C3082;
            text-decoration: none;
            font-weight: 500;
        }
        
        .card-footer a:hover {
            color: #C9A44A;
        }
        
        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes hammer {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(10deg); }
        }
        
        @keyframes progress {
            0% { width: 60%; }
            100% { width: 75%; }
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .card-header {
                padding: 30px 20px;
            }
            
            .card-header h1 {
                font-size: 2.2rem;
            }
            
            .card-body {
                padding: 30px 20px;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #718096;
            font-size: 0.9rem;
        }
        
        .breadcrumb a {
            color: #6C3082;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            color: #C9A44A;
        }
        
        .breadcrumb i {
            font-size: 0.8rem;
            color: #C9A44A;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="resources-card">
            <!-- Header -->
            <div class="card-header">
                <i class="fas fa-graduation-cap"></i>
                <h1>Resources Center</h1>
                <p>Your gateway to learning materials and publications</p>
            </div>
            
            <!-- Body -->
            <div class="card-body">
                <!-- Construction Animation -->
                <div class="construction-icon">
                    <i class="fas fa-hammer"></i>
                </div>
                
                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <div class="progress-text">Coming Soon</div>
                </div>
                
                <!-- Main Message -->
                <div class="message">
                    <h2>🚧 Under Construction 🚧</h2>
                    <p>We're working hard to bring you a comprehensive resources center. Our team is curating high-quality educational materials, research publications, and learning tools specifically designed for nursing students and healthcare professionals.</p>
                    <p><strong>Expected Launch:</strong> <?php echo e($estimated_launch); ?></p>
                </div>
                
                <!-- Feature Preview -->
                <h3 style="color: #6C3082; margin-bottom: 20px;">What You'll Find Here</h3>
                
                <div class="feature-grid">
                    <?php foreach ($features as $feature): ?>
                    <div class="feature-item">
                        <?php 
                            $icon = 'fa-book-open';
                            if ($feature == 'Lab Manuals') $icon = 'fa-flask';
                            elseif ($feature == 'Research Papers') $icon = 'fa-file-pdf';
                            elseif ($feature == 'Video Tutorials') $icon = 'fa-video';
                        ?>
                        <i class="fas <?php echo $icon; ?>"></i>
                        <h3><?php echo e($feature); ?></h3>
                        <p>
                            <?php 
                                $description = 'Comprehensive learning resources';
                                if ($feature == 'Study Materials') $description = 'Lecture notes, past questions, and study guides';
                                elseif ($feature == 'Lab Manuals') $description = 'Clinical procedure guides and simulation resources';
                                elseif ($feature == 'Research Papers') $description = 'Published research and academic journals';
                                elseif ($feature == 'Video Tutorials') $description = 'Step-by-step nursing procedure videos';
                                echo $description;
                            ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Action Buttons -->
                <div class="btn-group">
                    <a href="<?php echo BASE_URL; ?>/contact" class="btn btn-primary">
                        <i class="fas fa-envelope"></i> Get Notified When Live
                    </a>
                    <a href="<?php echo BASE_URL; ?>/" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Return Home
                    </a>
                </div>
                
                <!-- Alternative Options -->
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E9EDF2;">
                    <p style="color: #718096; margin-bottom: 10px;">In the meantime, you can:</p>
                    <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                        <a href="<?php echo BASE_URL; ?>/research" style="color: #6C3082; text-decoration: none;">
                            <i class="fas fa-microscope"></i> Research Center
                        </a>
                        <span style="color: #C9A44A;">|</span>
                        <a href="<?php echo BASE_URL; ?>/contact" style="color: #6C3082; text-decoration: none;">
                            <i class="fas fa-question-circle"></i> FAQ
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="card-footer">
                <p>For inquiries about resources, contact our <a href="mailto:library@fctcns.edu.ng">Library Services</a> or call <strong><a href="tel:+2347054464289" style="color: #6C3082; text-decoration: none;">+234 705 446 4289</a></strong></p>
            </div>
        </div>
    </div>
</body>
</html>