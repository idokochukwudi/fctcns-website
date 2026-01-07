<?php include(APP_PATH . '/views/admin/includes/header.php'); ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Create New User</h1>
        <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/store">
                <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($formData['full_name'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text">Minimum 6 characters</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $key => $label): ?>
                                <option value="<?php echo $key; ?>" 
                                    <?php echo ($formData['role'] ?? '') == $key ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department" 
                                   value="<?php echo htmlspecialchars($formData['department'] ?? ''); ?>" 
                                   list="department-list">
                            <datalist id="department-list">
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept); ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                   <?php echo isset($formData['is_active']) && $formData['is_active'] ? 'checked' : 'checked'; ?>>
                            <label class="form-check-label" for="is_active">Active Account</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="must_change_password" name="must_change_password" value="1"
                                   <?php echo isset($formData['must_change_password']) && $formData['must_change_password'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="must_change_password">User must change password on first login</label>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Permissions</h5>
                        <p class="text-muted small mb-0">Select permissions for this user</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php 
                            $groupedPermissions = [
                                'Nominal Roll' => [],
                                'User Management' => [],
                                'Applications' => [],
                                'System' => []
                            ];
                            
                            foreach ($permissions as $key => $label) {
                                if (strpos($key, 'nominal_roll') === 0) {
                                    $groupedPermissions['Nominal Roll'][$key] = $label;
                                } elseif (strpos($key, 'user_') === 0) {
                                    $groupedPermissions['User Management'][$key] = $label;
                                } elseif (strpos($key, 'application_') === 0) {
                                    $groupedPermissions['Applications'][$key] = $label;
                                } else {
                                    $groupedPermissions['System'][$key] = $label;
                                }
                            }
                            ?>
                            
                            <?php foreach ($groupedPermissions as $group => $perms): ?>
                            <?php if (!empty($perms)): ?>
                            <div class="col-md-6 mb-3">
                                <h6><?php echo $group; ?></h6>
                                <?php foreach ($perms as $key => $label): ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" 
                                           id="perm_<?php echo $key; ?>" 
                                           name="permissions[]" 
                                           value="<?php echo $key; ?>"
                                           <?php echo (isset($formData['permissions']) && in_array($key, $formData['permissions'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="perm_<?php echo $key; ?>">
                                        <?php echo $label; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create User
                    </button>
                    <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include(APP_PATH . '/views/admin/includes/footer.php'); ?>