<?php include(APP_PATH . '/views/admin/includes/header.php'); ?>

<div class="admin-content">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <?php if ($user['profile_picture']): ?>
                    <img src="<?php echo BASE_URL . '/uploads/profiles/' . htmlspecialchars($user['profile_picture']); ?>" 
                         alt="<?php echo htmlspecialchars($user['full_name']); ?>" 
                         class="rounded-circle mb-3" width="150" height="150">
                    <?php else: ?>
                    <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 150px; height: 150px; font-size: 60px;">
                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    </div>
                    <?php endif; ?>
                    
                    <h4><?php echo htmlspecialchars($user['full_name']); ?></h4>
                    <p class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></p>
                    
                    <div class="mb-3">
                        <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'editor' ? 'warning' : 'info'); ?> fs-6">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </div>
                    
                    <div class="list-group list-group-flush text-start">
                        <div class="list-group-item">
                            <i class="fas fa-envelope me-2"></i>
                            <?php echo htmlspecialchars($user['email']); ?>
                        </div>
                        <div class="list-group-item">
                            <i class="fas fa-phone me-2"></i>
                            <?php echo $user['phone'] ? htmlspecialchars($user['phone']) : 'Not set'; ?>
                        </div>
                        <div class="list-group-item">
                            <i class="fas fa-calendar me-2"></i>
                            Member since <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                        </div>
                        <div class="list-group-item">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Last login: <?php echo $user['last_login'] ? date('M d, Y H:i', strtotime($user['last_login'])) : 'Never'; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistics -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Your Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="stat-number"><?php echo $user['login_count'] ?? 0; ?></div>
                            <div class="stat-label">Total Logins</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-number">
                                <?php 
                                // You would need to query this from database
                                echo '0';
                                ?>
                            </div>
                            <div class="stat-label">Nominal Records</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Profile</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($flash_success)): ?>
                    <div class="alert alert-success"><?php echo $flash_success; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/update-profile">
                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" 
                                       value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                                <div class="form-text">Username cannot be changed</div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Change Password</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                                <div class="form-text">Leave blank to keep current password</div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Security Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Security Information</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Password Last Changed:</span>
                            <span>
                                <?php if ($user['password_changed_at']): ?>
                                <?php echo date('M d, Y H:i', strtotime($user['password_changed_at'])); ?>
                                <?php else: ?>
                                <span class="text-muted">Never changed</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Account Status:</span>
                            <span>
                                <?php if ($user['is_active']): ?>
                                <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Two-Factor Authentication:</span>
                            <span>
                                <?php if ($user['two_factor_enabled']): ?>
                                <span class="badge bg-success">Enabled</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Disabled</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Last Login IP:</span>
                            <span class="text-muted"><?php echo $user['last_login_ip'] ?? '—'; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(APP_PATH . '/views/admin/includes/footer.php'); ?>