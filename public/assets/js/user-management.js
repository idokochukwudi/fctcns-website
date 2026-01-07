/**
 * User Management JavaScript
 * Handles user management specific functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate password
    const generatePasswordBtn = document.getElementById('generatePassword');
    if (generatePasswordBtn) {
        generatePasswordBtn.addEventListener('click', function() {
            const password = generatePassword(12);
            document.getElementById('password').value = password;
            document.getElementById('confirm_password').value = password;
            
            // Trigger strength check
            if (typeof checkPasswordStrength === 'function') {
                checkPasswordStrength(password);
            }
        });
    }
    
    // Role-based permission presets
    const roleSelect = document.getElementById('role');
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            applyRolePermissions(this.value);
        });
        
        // Apply on page load if role is selected
        if (roleSelect.value) {
            applyRolePermissions(roleSelect.value);
        }
    }
    
    // Bulk select/deselect all
    const selectAllCheckbox = document.getElementById('selectAllUsers');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButton();
        });
    }
    
    // Update bulk action button state
    const userCheckboxes = document.querySelectorAll('input[name="user_ids[]"]');
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionButton);
    });
    
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            updatePasswordStrength(this.value);
        });
    }
    
    // Profile picture preview
    const profilePictureInput = document.getElementById('profile_picture');
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function(e) {
            previewImage(this, 'profilePicturePreview');
        });
    }
    
    // Export filters
    const exportBtn = document.getElementById('exportUsersBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            const filters = getCurrentFilters();
            const url = `${BASE_URL}/admin/users/export?${new URLSearchParams(filters).toString()}`;
            window.location.href = url;
        });
    }
    
    // Initialize tooltips
    initTooltips();
    
    // Initialize date pickers
    initDatePickers();
    
    // Confirm delete actions
    initConfirmDialogs();
});

/**
 * Generate a random password
 */
function generatePassword(length = 12) {
    const lowercase = 'abcdefghijklmnopqrstuvwxyz';
    const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numbers = '0123456789';
    const symbols = '!@#$%^&*';
    
    const allChars = lowercase + uppercase + numbers + symbols;
    let password = '';
    
    // Ensure at least one of each type
    password += lowercase[Math.floor(Math.random() * lowercase.length)];
    password += uppercase[Math.floor(Math.random() * uppercase.length)];
    password += numbers[Math.floor(Math.random() * numbers.length)];
    password += symbols[Math.floor(Math.random() * symbols.length)];
    
    // Fill the rest
    for (let i = 4; i < length; i++) {
        password += allChars[Math.floor(Math.random() * allChars.length)];
    }
    
    // Shuffle the password
    return password.split('').sort(() => Math.random() - 0.5).join('');
}

/**
 * Apply role-based permission presets
 */
function applyRolePermissions(role) {
    const permissionPresets = {
        'admin': [
            'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit', 'nominal_roll_delete',
            'nominal_roll_bulk_upload', 'nominal_roll_export', 'nominal_roll_settings', 'nominal_roll_approve',
            'user_view', 'user_create', 'user_edit', 'user_delete',
            'application_view', 'application_edit', 'application_delete',
            'system_settings', 'system_backup', 'system_reports'
        ],
        'editor': [
            'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit',
            'nominal_roll_bulk_upload', 'nominal_roll_export',
            'application_view', 'application_edit'
        ],
        'viewer': ['nominal_roll_view', 'application_view'],
        'moderator': [
            'nominal_roll_view', 'nominal_roll_edit', 'nominal_roll_approve',
            'application_view', 'application_edit'
        ],
        'supervisor': [
            'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit',
            'application_view', 'system_reports'
        ]
    };
    
    // Clear all checkboxes first (optional, you might not want this)
    // document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
    //     cb.checked = false;
    // });
    
    // Check preset permissions
    if (permissionPresets[role]) {
        permissionPresets[role].forEach(perm => {
            const checkbox = document.getElementById(`perm_${perm}`);
            if (checkbox) {
                checkbox.checked = true;
                checkbox.parentElement.classList.add('text-primary', 'fw-bold');
            }
        });
    }
}

/**
 * Update password strength indicator
 */
function updatePasswordStrength(password) {
    const meter = document.getElementById('passwordStrength');
    const text = document.getElementById('passwordStrengthText');
    
    if (!meter || !text) return;
    
    let score = 0;
    let feedback = [];
    
    // Length check
    if (password.length >= 8) score += 1;
    if (password.length >= 12) score += 2;
    
    // Character variety
    if (/[a-z]/.test(password)) score += 1;
    if (/[A-Z]/.test(password)) score += 1;
    if (/[0-9]/.test(password)) score += 1;
    if (/[^a-zA-Z0-9]/.test(password)) score += 2;
    
    // Check for common patterns (negative scoring)
    if (/(.)\1{2,}/.test(password)) score -= 1; // Repeated characters
    if (/^(123|abc|password|admin)/i.test(password)) score -= 2; // Common patterns
    
    let strength;
    if (password.length === 0) {
        strength = { percent: 0, color: 'secondary', text: 'Enter password' };
    } else if (score <= 2) {
        strength = { percent: 25, color: 'danger', text: 'Weak' };
        feedback.push('Add more characters');
        feedback.push('Use uppercase letters');
        feedback.push('Add numbers or symbols');
    } else if (score <= 4) {
        strength = { percent: 50, color: 'warning', text: 'Fair' };
        feedback.push('Make it longer');
        feedback.push('Add special characters');
    } else if (score <= 6) {
        strength = { percent: 75, color: 'info', text: 'Good' };
        feedback.push('Consider adding more complexity');
    } else {
        strength = { percent: 100, color: 'success', text: 'Strong' };
        feedback.push('Excellent password!');
    }
    
    meter.className = `strength-bar strength-${strength.text.toLowerCase()}`;
    meter.style.width = `${strength.percent}%`;
    text.textContent = strength.text;
    text.className = `text-${strength.color}`;
    
    // Update feedback
    const feedbackElement = document.getElementById('passwordFeedback');
    if (feedbackElement) {
        feedbackElement.innerHTML = feedback.map(f => `<small class="d-block">• ${f}</small>`).join('');
    }
}

/**
 * Preview image before upload
 */
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                preview.style.backgroundImage = `url(${e.target.result})`;
                preview.style.backgroundSize = 'cover';
                preview.style.backgroundPosition = 'center';
            }
            preview.classList.remove('d-none');
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * Update bulk action button state
 */
function updateBulkActionButton() {
    const selectedCount = document.querySelectorAll('input[name="user_ids[]"]:checked').length;
    const bulkActionBtn = document.getElementById('bulkActionBtn');
    const selectedCountBadge = document.getElementById('selectedCountBadge');
    
    if (bulkActionBtn) {
        bulkActionBtn.disabled = selectedCount === 0;
    }
    
    if (selectedCountBadge) {
        if (selectedCount > 0) {
            selectedCountBadge.textContent = selectedCount;
            selectedCountBadge.classList.remove('d-none');
        } else {
            selectedCountBadge.classList.add('d-none');
        }
    }
}

/**
 * Get current filter values
 */
function getCurrentFilters() {
    const filters = {};
    
    // Get search input
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && searchInput.value) {
        filters.search = searchInput.value;
    }
    
    // Get role select
    const roleSelect = document.querySelector('select[name="role"]');
    if (roleSelect && roleSelect.value) {
        filters.role = roleSelect.value;
    }
    
    // Get status select
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect && statusSelect.value) {
        filters.status = statusSelect.value;
    }
    
    // Get department select
    const deptSelect = document.querySelector('select[name="department"]');
    if (deptSelect && deptSelect.value) {
        filters.department = deptSelect.value;
    }
    
    return filters;
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize date pickers
 */
function initDatePickers() {
    // If you're using a date picker library like flatpickr
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.datepicker', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });
    }
}

/**
 * Copy to clipboard
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy: ', err);
        showToast('Failed to copy', 'error');
    });
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    // Add to toast container
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.appendChild(toast);
    
    // Initialize and show
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 3000
    });
    bsToast.show();
    
    // Remove from DOM after hide
    toast.addEventListener('hidden.bs.toast', function () {
        toast.remove();
    });
}

/**
 * Initialize confirm dialogs for delete actions
 */
function initConfirmDialogs() {
    // For delete buttons with confirm-delete class
    document.querySelectorAll('.confirm-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // For form submissions with confirm-submit class
    document.querySelectorAll('form.confirm-submit').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to perform this action?')) {
                e.preventDefault();
                return false;
            }
        });
    });
}

/**
 * Check password strength (simple version)
 */
function checkPasswordStrength(password) {
    let score = 0;
    
    // Length check
    if (password.length >= 8) score += 1;
    if (password.length >= 12) score += 1;
    
    // Character variety
    if (/[a-z]/.test(password)) score += 1;
    if (/[A-Z]/.test(password)) score += 1;
    if (/[0-9]/.test(password)) score += 1;
    if (/[^a-zA-Z0-9]/.test(password)) score += 1;
    
    if (score <= 2) return { percent: 25, color: 'danger', text: 'Weak' };
    if (score <= 4) return { percent: 50, color: 'warning', text: 'Fair' };
    if (score <= 5) return { percent: 75, color: 'info', text: 'Good' };
    return { percent: 100, color: 'success', text: 'Strong' };
}

/**
 * Toggle password visibility
 */
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.querySelector(`[data-toggle="${inputId}"] i`);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

/**
 * Validate email format
 */
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

/**
 * Format phone number
 */
function formatPhoneNumber(phone) {
    // Remove all non-digits
    const cleaned = ('' + phone).replace(/\D/g, '');
    
    // Check if the number is valid
    const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
    if (match) {
        return '(' + match[1] + ') ' + match[2] + '-' + match[3];
    }
    
    return phone;
}

/**
 * Debounce function for performance
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Export users data as CSV
 */
function exportUsersAsCSV(users, filename = 'users.csv') {
    // Convert users array to CSV
    const headers = ['ID', 'Username', 'Email', 'Full Name', 'Role', 'Status', 'Created At'];
    const csvRows = [];
    
    // Add headers
    csvRows.push(headers.join(','));
    
    // Add data rows
    for (const user of users) {
        const values = headers.map(header => {
            const value = user[header.toLowerCase().replace(' ', '_')] || '';
            // Escape quotes and wrap in quotes if contains comma
            const escaped = ('"' + String(value).replace(/"/g, '""') + '"');
            return escaped;
        });
        csvRows.push(values.join(','));
    }
    
    // Create download link
    const csvString = csvRows.join('\n');
    const blob = new Blob([csvString], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', filename);
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

/**
 * Bulk action handler
 */
function handleBulkAction(action) {
    const selectedUsers = Array.from(document.querySelectorAll('input[name="user_ids[]"]:checked'))
        .map(cb => cb.value);
    
    if (selectedUsers.length === 0) {
        showToast('Please select at least one user.', 'warning');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'activate':
            confirmMessage = `Activate ${selectedUsers.length} user(s)?`;
            break;
        case 'deactivate':
            confirmMessage = `Deactivate ${selectedUsers.length} user(s)?`;
            break;
        case 'delete':
            confirmMessage = `Delete ${selectedUsers.length} user(s) permanently?`;
            break;
        default:
            confirmMessage = `Perform ${action} on ${selectedUsers.length} user(s)?`;
    }
    
    if (confirm(confirmMessage)) {
        // Submit the bulk action form
        document.getElementById('bulkAction').value = action;
        document.getElementById('bulkActionForm').submit();
    }
}

// Make functions available globally if needed
window.generatePassword = generatePassword;
window.checkPasswordStrength = checkPasswordStrength;
window.togglePasswordVisibility = togglePasswordVisibility;
window.validateEmail = validateEmail;
window.formatPhoneNumber = formatPhoneNumber;
window.exportUsersAsCSV = exportUsersAsCSV;
window.handleBulkAction = handleBulkAction;