<?php
/**
 * Online Application Form View
 * 
 * @package FCT_CNS
 * @version 1.0
 */

// Extract data passed from controller
extract($data ?? []);

// Helper function for escaping output
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// Set defaults
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '/fctcns-website');
$page_title = $page_title ?? 'Online Application - FCT College of Nursing Sciences';
$currentPage = $currentPage ?? 'apply';
$errors = $errors ?? [];
$formData = $formData ?? [];
$programs = $programs ?? [];
$qualifications = $qualifications ?? [];
$entryYears = $entryYears ?? [];
$states = $states ?? [];
$csrf_token = $csrf_token ?? '';

// Pre-fill form data if available
$first_name = $formData['first_name'] ?? '';
$last_name = $formData['last_name'] ?? '';
$email = $formData['email'] ?? '';
$phone = $formData['phone'] ?? '';
$program = $formData['program'] ?? '';
$entry_year = $formData['entry_year'] ?? date('Y');
$highest_qualification = $formData['highest_qualification'] ?? '';
$personal_statement = $formData['personal_statement'] ?? '';
$date_of_birth = $formData['date_of_birth'] ?? '';
$gender = $formData['gender'] ?? '';
$state_of_origin = $formData['state_of_origin'] ?? '';
$lga = $formData['lga'] ?? '';
$address = $formData['address'] ?? '';

// Set current year
$currentYear = date('Y');
?>

<style>
/* Application Form Styles */
.application-container {
    font-family: var(--font-body);
    line-height: 1.6;
    color: var(--color-gray-700);
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* Hero Header */
.application-hero {
    background: linear-gradient(135deg, 
        rgba(44, 82, 130, 0.95) 0%, 
        rgba(26, 54, 93, 0.9) 100%);
    color: var(--color-white);
    padding: var(--spacing-2xl) 0 var(--spacing-xl) 0;
    margin-top: 0;
    position: relative;
    overflow: hidden;
}

.application-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.hero-inner {
    position: relative;
    z-index: 1;
    max-width: 48rem;
    margin: 0 auto;
    text-align: center;
}

.hero-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2.5rem;
    line-height: 1.2;
    margin-bottom: var(--spacing-md);
    margin-top: 0;
}

.hero-subtitle {
    font-size: 1.125rem;
    opacity: 0.95;
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
}

/* Breadcrumb */
.breadcrumb {
    background-color: var(--color-gray-50);
    padding: var(--spacing-md) 0;
    border-bottom: 1px solid var(--color-gray-200);
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: 0.875rem;
}

.breadcrumb-nav a {
    color: var(--color-gray-600);
    text-decoration: none;
    transition: color var(--transition-fast);
}

.breadcrumb-nav a:hover {
    color: var(--color-primary);
    text-decoration: underline;
}

.breadcrumb-separator {
    color: var(--color-gray-400);
}

.breadcrumb-current {
    color: var(--color-primary);
    font-weight: 600;
}

/* Form Container */
.form-section {
    padding: var(--spacing-2xl) 0;
    background-color: var(--color-gray-50);
}

.form-container {
    max-width: 800px;
    margin: 0 auto;
}

.form-card {
    background-color: var(--color-white);
    border-radius: var(--radius-xl);
    padding: var(--spacing-2xl);
    box-shadow: var(--shadow-lg);
}

/* Form Header */
.form-header {
    text-align: center;
    margin-bottom: var(--spacing-2xl);
    padding-bottom: var(--spacing-lg);
    border-bottom: 2px solid var(--color-gray-200);
}

.form-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2rem;
    color: var(--color-gray-800);
    margin-bottom: var(--spacing-sm);
}

.form-description {
    color: var(--color-gray-600);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
}

.form-notice {
    background-color: var(--color-gray-100);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    border-left: 4px solid var(--color-warning);
    margin-top: var(--spacing-md);
}

.form-notice p {
    margin: 0;
    font-size: 0.875rem;
    color: var(--color-gray-700);
}

/* Form Styles */
.application-form {
    margin-top: var(--spacing-lg);
}

.form-group {
    margin-bottom: var(--spacing-lg);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--color-gray-700);
    margin-bottom: var(--spacing-sm);
    font-size: 0.875rem;
}

.required {
    color: var(--color-danger);
}

.form-control {
    width: 100%;
    padding: var(--spacing-md);
    border: 1px solid var(--color-gray-300);
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-size: 1rem;
    transition: all var(--transition-fast);
    background-color: var(--color-white);
}

.form-control:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
}

.form-control::placeholder {
    color: var(--color-gray-400);
}

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%234a5568' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 40px;
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

/* Error Styles */
.error-message {
    color: var(--color-danger);
    font-size: 0.875rem;
    margin-top: var(--spacing-xs);
    display: block;
}

.has-error .form-control {
    border-color: var(--color-danger);
}

.has-error .form-control:focus {
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: var(--spacing-md);
    margin-top: var(--spacing-xl);
    padding-top: var(--spacing-xl);
    border-top: 2px solid var(--color-gray-200);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-md) var(--spacing-xl);
    border-radius: var(--radius-md);
    font-weight: 600;
    text-decoration: none;
    transition: all var(--transition-base);
    border: 2px solid transparent;
    cursor: pointer;
    font-family: var(--font-heading);
    font-size: 1rem;
    min-width: 120px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--color-success), #2f855a);
    color: var(--color-white);
    flex: 1;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2f855a, #276749);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background-color: transparent;
    color: var(--color-gray-600);
    border-color: var(--color-gray-300);
}

.btn-secondary:hover {
    background-color: var(--color-gray-100);
    border-color: var(--color-gray-400);
}

.btn svg {
    width: 20px;
    height: 20px;
    margin-right: var(--spacing-sm);
}

/* Form Steps */
.form-steps {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-xl);
    position: relative;
}

.form-steps::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 0;
    right: 0;
    height: 2px;
    background-color: var(--color-gray-300);
    z-index: 1;
}

.step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
}

.step-number {
    width: 32px;
    height: 32px;
    background-color: var(--color-white);
    border: 2px solid var(--color-gray-300);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: var(--color-gray-500);
    margin-bottom: var(--spacing-sm);
}

.step.active .step-number {
    background-color: var(--color-primary);
    border-color: var(--color-primary);
    color: var(--color-white);
}

.step.completed .step-number {
    background-color: var(--color-success);
    border-color: var(--color-success);
    color: var(--color-white);
}

.step-label {
    font-size: 0.75rem;
    color: var(--color-gray-600);
    font-weight: 600;
}

.step.active .step-label {
    color: var(--color-primary);
}

.step.completed .step-label {
    color: var(--color-success);
}

/* Required Fields Note */
.required-note {
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--color-gray-200);
    text-align: right;
    font-size: 0.875rem;
    color: var(--color-gray-600);
}

.required-note span {
    color: var(--color-danger);
}

/* Form Sections */
.form-section-title {
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--color-gray-800);
    font-size: 1.25rem;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-sm);
    border-bottom: 2px solid var(--color-gray-200);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.form-section-title svg {
    width: 24px;
    height: 24px;
    color: var(--color-primary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-card {
        padding: var(--spacing-lg);
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .form-title {
        font-size: 1.5rem;
    }
    
    .form-steps {
        flex-wrap: wrap;
        gap: var(--spacing-md);
    }
    
    .step {
        flex: none;
        width: calc(50% - var(--spacing-md));
    }
}

@media (max-width: 480px) {
    .form-card {
        padding: var(--spacing-md);
    }
    
    .step {
        width: 100%;
    }
    
    .hero-title {
        font-size: 1.75rem;
    }
}

/* Field Help Text */
.field-help {
    font-size: 0.75rem;
    color: var(--color-gray-500);
    margin-top: var(--spacing-xs);
    display: block;
}
</style>

<!-- Main Content -->
<main id="main-content" class="application-container" role="main" aria-label="Online application form">
    <!-- Skip to Content Link -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>

    <!-- Hero Header -->
    <header class="application-hero" role="banner">
        <div class="container">
            <div class="hero-inner">
                <h1 class="hero-title">Online Application Form</h1>
                <p class="hero-subtitle">
                    Complete this form to apply for admission to FCT College of Nursing Sciences. 
                    Please fill in all required fields accurately.
                </p>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <div class="breadcrumb-nav">
                <a href="<?php echo $baseUrl; ?>/">Home</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <a href="<?php echo $baseUrl; ?>/admissions">Admissions</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <span class="breadcrumb-current" aria-current="page">Online Application</span>
            </div>
        </div>
    </nav>

    <!-- Form Section -->
    <section class="form-section" id="application-form">
        <div class="container">
            <div class="form-container">
                <div class="form-card">
                    <!-- Form Header -->
                    <div class="form-header">
                        <h2 class="form-title">Admission Application Form</h2>
                        <p class="form-description">
                            Please complete all sections of this form. Fields marked with 
                            <span class="required">*</span> are required.
                        </p>
                        
                        <!-- Display Errors -->
                        <?php if (!empty($errors)): ?>
                        <div class="form-notice" role="alert">
                            <p><strong>Please correct the following errors:</strong></p>
                            <ul style="margin: var(--spacing-sm) 0 0 var(--spacing-md);">
                                <?php foreach ($errors as $error): ?>
                                <li><?php echo e($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Display General Error -->
                        <?php if (isset($error) && !empty($error)): ?>
                        <div class="form-notice" style="border-left-color: var(--color-danger);" role="alert">
                            <p><strong>Error:</strong> <?php echo e($error); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Application Form -->
                    <form action="<?php echo $baseUrl; ?>/apply/submit" method="POST" class="application-form" id="applicationForm">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                        
                        <!-- Step 1: Personal Information -->
                        <div class="form-section-title">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            Personal Information
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group <?php echo (isset($errors) && (in_array('First name is required', $errors) || in_array('First name is required', array_values($errors)))) ? 'has-error' : ''; ?>">
                                <label for="first_name" class="form-label">
                                    First Name <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       id="first_name" 
                                       name="first_name" 
                                       class="form-control" 
                                       value="<?php echo e($first_name); ?>"
                                       required
                                       aria-required="true"
                                       placeholder="Enter your first name">
                                <?php if (isset($errors) && (in_array('First name is required', $errors) || in_array('First name is required', array_values($errors)))): ?>
                                <span class="error-message">First name is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group <?php echo (isset($errors) && (in_array('Last name is required', $errors) || in_array('Last name is required', array_values($errors)))) ? 'has-error' : ''; ?>">
                                <label for="last_name" class="form-label">
                                    Last Name <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       id="last_name" 
                                       name="last_name" 
                                       class="form-control" 
                                       value="<?php echo e($last_name); ?>"
                                       required
                                       aria-required="true"
                                       placeholder="Enter your last name">
                                <?php if (isset($errors) && (in_array('Last name is required', $errors) || in_array('Last name is required', array_values($errors)))): ?>
                                <span class="error-message">Last name is required</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group <?php echo (isset($errors) && (in_array('Email is required', $errors) || in_array('Valid email is required', $errors) || in_array('An application with this email already exists', $errors))) ? 'has-error' : ''; ?>">
                                <label for="email" class="form-label">
                                    Email Address <span class="required">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control" 
                                       value="<?php echo e($email); ?>"
                                       required
                                       aria-required="true"
                                       placeholder="Enter your email address">
                                <?php if (isset($errors) && (in_array('Email is required', $errors) || in_array('Valid email is required', $errors))): ?>
                                <span class="error-message">
                                    <?php echo in_array('Valid email is required', $errors) ? 'Valid email is required' : 'Email is required'; ?>
                                </span>
                                <?php elseif (isset($errors) && in_array('An application with this email already exists', $errors)): ?>
                                <span class="error-message">An application with this email already exists</span>
                                <?php endif; ?>
                                <span class="field-help">We'll send application updates to this email</span>
                            </div>
                            
                            <div class="form-group <?php echo (isset($errors) && (in_array('Phone number is required', $errors) || in_array('Please enter a valid phone number', $errors))) ? 'has-error' : ''; ?>">
                                <label for="phone" class="form-label">
                                    Phone Number <span class="required">*</span>
                                </label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-control" 
                                       value="<?php echo e($phone); ?>"
                                       required
                                       aria-required="true"
                                       placeholder="e.g., 08012345678 or +2348012345678">
                                <?php if (isset($errors) && (in_array('Phone number is required', $errors) || in_array('Please enter a valid phone number', $errors))): ?>
                                <span class="error-message">
                                    <?php echo in_array('Please enter a valid phone number', $errors) ? 'Please enter a valid phone number' : 'Phone number is required'; ?>
                                </span>
                                <?php endif; ?>
                                <span class="field-help">Include country code if international</span>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group <?php echo (isset($errors) && in_array('Date of birth is required', $errors)) ? 'has-error' : ''; ?>">
                                <label for="date_of_birth" class="form-label">
                                    Date of Birth <span class="required">*</span>
                                </label>
                                <input type="date" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       class="form-control" 
                                       value="<?php echo e($date_of_birth); ?>"
                                       required
                                       aria-required="true"
                                       max="<?php echo date('Y-m-d', strtotime('-16 years')); ?>">
                                <?php if (isset($errors) && in_array('Date of birth is required', $errors)): ?>
                                <span class="error-message">Date of birth is required</span>
                                <?php endif; ?>
                                <?php if (isset($errors) && in_array('You must be at least 16 years old to apply', $errors)): ?>
                                <span class="error-message">You must be at least 16 years old to apply</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group <?php echo (isset($errors) && in_array('Gender is required', $errors)) ? 'has-error' : ''; ?>">
                                <label for="gender" class="form-label">
                                    Gender <span class="required">*</span>
                                </label>
                                <select id="gender" name="gender" class="form-control" required aria-required="true">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="other" <?php echo ($gender === 'other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                                <?php if (isset($errors) && in_array('Gender is required', $errors)): ?>
                                <span class="error-message">Gender is required</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Step 2: Program Details -->
                        <div class="form-section-title" style="margin-top: var(--spacing-2xl);">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            Program Information
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group <?php echo (isset($errors) && in_array('Program selection is required', $errors)) ? 'has-error' : ''; ?>">
                                <label for="program" class="form-label">
                                    Program of Study <span class="required">*</span>
                                </label>
                                <select id="program" name="program" class="form-control" required aria-required="true">
                                    <option value="">Select a Program</option>
                                    <?php foreach ($programs as $programOption): ?>
                                    <option value="<?php echo e($programOption); ?>" 
                                        <?php echo ($program === $programOption) ? 'selected' : ''; ?>>
                                        <?php echo e($programOption); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors) && in_array('Program selection is required', $errors)): ?>
                                <span class="error-message">Program selection is required</span>
                                <?php endif; ?>
                                <span class="field-help">Choose the nursing program you wish to apply for</span>
                            </div>
                            
                            <div class="form-group">
                                <label for="entry_year" class="form-label">
                                    Preferred Entry Year <span class="required">*</span>
                                </label>
                                <select id="entry_year" name="entry_year" class="form-control" required aria-required="true">
                                    <option value="">Select Year</option>
                                    <?php foreach ($entryYears as $year): ?>
                                    <option value="<?php echo e($year); ?>" 
                                        <?php echo ($entry_year == $year) ? 'selected' : ''; ?>>
                                        <?php echo e($year); ?> Academic Session
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group <?php echo (isset($errors) && in_array('Highest qualification is required', $errors)) ? 'has-error' : ''; ?>">
                            <label for="highest_qualification" class="form-label">
                                Highest Educational Qualification <span class="required">*</span>
                            </label>
                            <select id="highest_qualification" name="highest_qualification" class="form-control" required aria-required="true">
                                <option value="">Select Qualification</option>
                                <?php foreach ($qualifications as $qual): ?>
                                <option value="<?php echo e($qual); ?>" 
                                    <?php echo ($highest_qualification === $qual) ? 'selected' : ''; ?>>
                                    <?php echo e($qual); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors) && in_array('Highest qualification is required', $errors)): ?>
                            <span class="error-message">Highest qualification is required</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Step 3: Address Information -->
                        <div class="form-section-title" style="margin-top: var(--spacing-2xl);">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Address Information
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group <?php echo (isset($errors) && in_array('State of origin is required', $errors)) ? 'has-error' : ''; ?>">
                                <label for="state_of_origin" class="form-label">
                                    State of Origin <span class="required">*</span>
                                </label>
                                <select id="state_of_origin" name="state_of_origin" class="form-control" required aria-required="true">
                                    <option value="">Select State</option>
                                    <?php foreach ($states as $state): ?>
                                    <option value="<?php echo e($state); ?>" 
                                        <?php echo ($state_of_origin === $state) ? 'selected' : ''; ?>>
                                        <?php echo e($state); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors) && in_array('State of origin is required', $errors)): ?>
                                <span class="error-message">State of origin is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="lga" class="form-label">
                                    Local Government Area (LGA)
                                </label>
                                <input type="text" 
                                       id="lga" 
                                       name="lga" 
                                       class="form-control" 
                                       value="<?php echo e($lga); ?>"
                                       placeholder="Enter your LGA">
                            </div>
                        </div>
                        
                        <div class="form-group <?php echo (isset($errors) && in_array('Address is required', $errors)) ? 'has-error' : ''; ?>">
                            <label for="address" class="form-label">
                                Residential Address <span class="required">*</span>
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      class="form-control" 
                                      required
                                      aria-required="true"
                                      placeholder="Enter your complete residential address"><?php echo e($address); ?></textarea>
                            <?php if (isset($errors) && in_array('Address is required', $errors)): ?>
                            <span class="error-message">Address is required</span>
                            <?php endif; ?>
                            <span class="field-help">Include street, city, and postal code</span>
                        </div>
                        
                        <!-- Step 4: Personal Statement -->
                        <div class="form-section-title" style="margin-top: var(--spacing-2xl);">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Personal Statement
                        </div>
                        
                        <div class="form-group">
                            <label for="personal_statement" class="form-label">
                                Why do you want to study nursing? <span class="required">*</span>
                                <span class="field-help">(Minimum 100 characters, maximum 500 words)</span>
                            </label>
                            <textarea id="personal_statement" 
                                      name="personal_statement" 
                                      class="form-control" 
                                      required
                                      aria-required="true"
                                      minlength="100"
                                      maxlength="2500"
                                      placeholder="Tell us about your motivation to study nursing, your career goals, and why you chose FCT College of Nursing Sciences..."><?php echo e($personal_statement); ?></textarea>
                            <div style="display: flex; justify-content: space-between; margin-top: var(--spacing-xs);">
                                <span class="field-help" id="charCount">0 characters</span>
                                <span class="field-help">Required: 100-2500 characters</span>
                            </div>
                        </div>
                        
                        <!-- Declaration -->
                        <div class="form-notice" style="margin-top: var(--spacing-xl);">
                            <p>
                                <strong>Declaration:</strong> I certify that the information provided in this 
                                application is true and complete to the best of my knowledge. I understand that 
                                any false or misleading information may result in disqualification or withdrawal 
                                of admission.
                            </p>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="<?php echo $baseUrl; ?>/admissions" class="btn btn-secondary">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                </svg>
                                Back to Admissions
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Submit Application
                            </button>
                        </div>
                        
                        <div class="required-note">
                            <span>*</span> Indicates required field
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- JavaScript Enhancements -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Character counter for personal statement
    const personalStatement = document.getElementById('personal_statement');
    const charCount = document.getElementById('charCount');
    
    if (personalStatement && charCount) {
        // Update character count on input
        personalStatement.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length + ' characters';
            
            // Add warning for minimum length
            if (length < 100) {
                charCount.style.color = 'var(--color-danger)';
            } else if (length > 2400) {
                charCount.style.color = 'var(--color-warning)';
            } else {
                charCount.style.color = 'var(--color-success)';
            }
        });
        
        // Trigger initial count
        personalStatement.dispatchEvent(new Event('input'));
    }
    
    // Form validation
    const form = document.getElementById('applicationForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                    
                    // Add error class
                    field.closest('.form-group')?.classList.add('has-error');
                } else {
                    field.closest('.form-group')?.classList.remove('has-error');
                }
            });
            
            // Validate email format
            const emailField = form.querySelector('input[type="email"]');
            if (emailField && emailField.value.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailField.value)) {
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = emailField;
                    
                    // Show email error
                    let errorSpan = emailField.parentElement.querySelector('.error-message');
                    if (!errorSpan) {
                        errorSpan = document.createElement('span');
                        errorSpan.className = 'error-message';
                        emailField.parentElement.appendChild(errorSpan);
                    }
                    errorSpan.textContent = 'Please enter a valid email address';
                    emailField.closest('.form-group')?.classList.add('has-error');
                }
            }
            
            // Validate personal statement length
            if (personalStatement) {
                const length = personalStatement.value.length;
                if (length < 100) {
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = personalStatement;
                    
                    let errorSpan = personalStatement.parentElement.querySelector('.error-message');
                    if (!errorSpan) {
                        errorSpan = document.createElement('span');
                        errorSpan.className = 'error-message';
                        personalStatement.parentElement.appendChild(errorSpan);
                    }
                    errorSpan.textContent = 'Personal statement must be at least 100 characters';
                    personalStatement.closest('.form-group')?.classList.add('has-error');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll to first invalid field
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
                
                // Show error message
                const errorContainer = document.querySelector('.form-notice');
                if (!errorContainer) {
                    const header = document.querySelector('.form-header');
                    if (header) {
                        const notice = document.createElement('div');
                        notice.className = 'form-notice';
                        notice.style.borderLeftColor = 'var(--color-danger)';
                        notice.innerHTML = '<p><strong>Please correct the errors in the form before submitting.</strong></p>';
                        header.appendChild(notice);
                    }
                }
            } else {
                // Add loading state to submit button
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="spinner" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <style>
                                .spinner {
                                    animation: spin 1s linear infinite;
                                }
                                @keyframes spin {
                                    0% { transform: rotate(0deg); }
                                    100% { transform: rotate(360deg); }
                                }
                            </style>
                            <path d="M12 22c5.421 0 10-4.579 10-10h-2c0 4.337-3.663 8-8 8s-8-3.663-8-8c0-4.336 3.663-8 8-8V2C6.579 2 2 6.58 2 12c0 5.421 4.579 10 10 10z"/>
                        </svg>
                        Submitting...
                    `;
                }
            }
        });
    }
    
    // Phone number formatting
    const phoneField = document.getElementById('phone');
    if (phoneField) {
        phoneField.addEventListener('input', function() {
            // Remove all non-digit characters except +
            let value = this.value.replace(/[^\d+]/g, '');
            
            // If starts with 0, format as local
            if (value.startsWith('0')) {
                // Limit to 11 digits max
                value = value.substring(0, 11);
            } 
            // If starts with +, format as international
            else if (value.startsWith('+')) {
                // Keep + and digits only
                value = '+' + value.substring(1).replace(/\D/g, '');
                // Limit to 15 digits max (including +)
                value = value.substring(0, 16);
            }
            // If starts with country code without +
            else if (value.startsWith('234')) {
                value = '+' + value;
                value = value.substring(0, 14); // +234 followed by max 10 digits
            }
            
            this.value = value;
        });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href === '#' || href.startsWith('#application')) {
                e.preventDefault();
                
                const target = document.querySelector(href);
                if (target) {
                    const headerHeight = document.querySelector('.navbar')?.offsetHeight || 80;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset;
                    const offsetPosition = targetPosition - headerHeight - 20;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
});
</script>