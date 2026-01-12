<?php
/**
 * 404 Not Found Page - MVC Version
 * 
 * This is a pure view file with no header/footer includes
 * The layout handles header/footer includes
 * 
 * @package FCTCNS
 * @version 2.0
 */

// Extract data passed from controller
extract($data ?? []);

// Set defaults - REMOVED the e() function declaration as it's in url_helper.php
$page_title = $page_title ?? '404 - Page Not Found';
$page_description = $page_description ?? 'The page you requested could not be found.';
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '/fctcns-website');
$currentPage = $currentPage ?? '404';
?>
<!-- Page-specific styles -->
<style>
:root {
    --color-primary: #6B4E9B;
    --color-primary-dark: #5a4185;
    --color-gray-600: #6c757d;
    --color-gray-800: #343a40;
    --color-white: #ffffff;
    --font-heading: 'Poppins', sans-serif;
    --transition-base: all 0.3s ease;
}

.error-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    text-align: center;
    background: linear-gradient(135deg, rgba(107, 78, 155, 0.05), rgba(127, 178, 133, 0.05));
}

.error-content {
    max-width: 600px;
    background: white;
    padding: 3rem;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.error-code {
    font-size: 8rem;
    font-weight: 700;
    color: var(--color-primary);
    line-height: 1;
    margin-bottom: 1rem;
    font-family: var(--font-heading);
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.error-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--color-gray-800);
}

.error-message {
    font-size: 1.125rem;
    color: var(--color-gray-600);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 0.875rem 2rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: 6px;
    transition: var(--transition-base);
    display: inline-block;
    border: 2px solid transparent;
    cursor: pointer;
    font-size: 1rem;
}

.btn-primary {
    background: var(--color-primary);
    color: var(--color-white);
}

.btn-primary:hover {
    background: var(--color-primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(107, 78, 155, 0.25);
}

.btn-outline {
    background: transparent;
    color: var(--color-primary);
    border-color: var(--color-primary);
}

.btn-outline:hover {
    background: var(--color-primary);
    color: var(--color-white);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .error-code {
        font-size: 6rem;
    }
    
    .error-title {
        font-size: 1.75rem;
    }
    
    .error-content {
        padding: 2rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .error-code {
        font-size: 4rem;
    }
    
    .error-title {
        font-size: 1.5rem;
    }
    
    .error-message {
        font-size: 1rem;
    }
    
    .error-content {
        padding: 1.5rem;
    }
}
</style>

<div class="error-container">
    <div class="error-content">
        <div class="error-code">404</div>
        <h1 class="error-title">Oops! Page Not Found</h1>
        <p class="error-message">
            The page you are looking for might have been removed, 
            had its name changed, or is temporarily unavailable.
            Please check the URL or navigate using the links below.
        </p>
        <div class="error-actions">
            <a href="<?php echo $baseUrl; ?>/" class="btn btn-primary">
                <i class="fas fa-home"></i> Return to Homepage
            </a>
            <a href="<?php echo $baseUrl; ?>/programs" class="btn btn-outline">
                <i class="fas fa-graduation-cap"></i> Explore Programs
            </a>
            <a href="<?php echo $baseUrl; ?>/contact" class="btn btn-outline">
                <i class="fas fa-envelope"></i> Contact Support
            </a>
        </div>
        <div style="margin-top: 2rem; font-size: 0.875rem; color: var(--color-gray-600);">
            <p>If you believe this is an error, please <a href="<?php echo $baseUrl; ?>/contact" style="color: var(--color-primary); text-decoration: underline;">contact our support team</a>.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation to error code
    const errorCode = document.querySelector('.error-code');
    if (errorCode) {
        errorCode.style.opacity = '0';
        errorCode.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            errorCode.style.transition = 'all 0.8s ease';
            errorCode.style.opacity = '1';
            errorCode.style.transform = 'translateY(0)';
        }, 100);
    }
});
</script>