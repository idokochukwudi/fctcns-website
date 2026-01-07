<?php include(APP_PATH . '/views/admin/includes/header.php'); ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">User Details</h1>
        <div>
            <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/users/edit/<?php echo $user['id']; ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: User Info -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <?php if ($user['profile_picture']): ?>
                    <img src="<?php echo BASE_URL . '/uploads/profiles/' . htmlspecialchars($user['profile_picture']); ?>" 
                         alt="<?php echo htmlspecialchars($user['full_name']); ?>" 
                         class="rounded-circle mb-3" width="120" height="120">
                    <?php else: ?>
                    <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 120px; height: 120px; font-size: 48px;">
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
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Status:</span>
                            <span>
                                <?php if ($user['is_active']): ?>
                                <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Department:</span>
                            <span><?php echo $user['department'] ? htmlspecialchars($user['department']) : '—'; ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Email:</span>
                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Phone:</span>
                            <span><?php echo $user['phone'] ? htmlspecialchars($user['phone']) : '—'; ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Last Login:</span>
                            <span>
                                <?php if ($user['last_login']): ?>
                                <?php echo date('M d, Y H:i', strtotime($user['last_login'])); ?>
                                <?php else: ?>
                                Never
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Login Count:</span>
                            <span><?php echo $user['login_count'] ?? 0; ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Created:</span>
                            <span><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/toggle-status/<?php echo $user['id']; ?>" 
                          class="mb-2" onsubmit="return confirm('Are you sure?');">
                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" name="value" value="<?php echo $user['is_active'] ? '0' : '1'; ?>">
                        <button type="submit" class="btn btn-<?php echo $user['is_active'] ? 'warning' : 'success'; ?> w-100">
                            <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?>"></i>
                            <?php echo $user['is_active'] ? 'Deactivate User' : 'Activate User'; ?>
                        </button>
                    </form>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/reset-password/<?php echo $user['id']; ?>" 
                          class="mb-2" onsubmit="return confirm('Reset password for this user?');">
                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                        <button type="submit" class="btn btn-outline-info w-100">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-envelope"></i> Send Email
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Details -->
        <div class="col-md-8">
            <!-- Permissions -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Permissions</h5>
                    <span class="badge bg-primary"><?php echo count($permissions); ?> permissions</span>
                </div>
                <div class="card-body">
                    <?php if (empty($permissions)): ?>
                    <p class="text-muted">No permissions assigned</p>
                    <?php else: ?>
                    <div class="row">
                        <?php 
                        $grouped = [
                            'Nominal Roll' => [],
                            'User Management' => [],
                            'Applications' => [],
                            'System' => []
                        ];
                        
                        foreach ($permissions as $perm) {
                            $key = $perm['permission'];
                            if (strpos($key, 'nominal_roll') === 0) {
                                $grouped['Nominal Roll'][] = $availablePermissions[$key] ?? $key;
                            } elseif (strpos($key, 'user_') === 0) {
                                $grouped['User Management'][] = $availablePermissions[$key] ?? $key;
                            } elseif (strpos($key, 'application_') === 0) {
                                $grouped['Applications'][] = $availablePermissions[$key] ?? $key;
                            } else {
                                $grouped['System'][] = $availablePermissions[$key] ?? $key;
                            }
                        }
                        ?>
                        
                        <?php foreach ($grouped as $group => $perms): ?>
                        <?php if (!empty($perms)): ?>
                        <div class="col-md-6 mb-3">
                            <h6><?php echo $group; ?></h6>
                            <ul class="list-unstyled">
                                <?php foreach ($perms as $perm): ?>
                                <li class="mb-1">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <?php echo htmlspecialchars($perm); ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($activities)): ?>
                    <p class="text-muted">No recent activity</p>
                    <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($activities as $activity): ?>
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($activity['action']); ?></h6>
                                    <small class="text-muted">
                                        <?php echo date('M d, H:i', strtotime($activity['created_at'])); ?>
                                    </small>
                                </div>
                                <p class="mb-1 small"><?php echo htmlspecialchars($activity['description']); ?></p>
                                <?php if ($activity['ip_address']): ?>
                                <small class="text-muted">IP: <?php echo htmlspecialchars($activity['ip_address']); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Nominal Roll Records -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Nominal Roll Records Created</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($nominalRecords)): ?>
                    <p class="text-muted">No nominal roll records created</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Employee No.</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nominalRecords as $record): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($record['employee_number']); ?></td>
                                    <td><?php echo htmlspecialchars($record['name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $record['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($record['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($record['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/admin/nominal-roll/view/<?php echo $record['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 6px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #4e73df;
}
.timeline-content {
    padding-bottom: 10px;
    border-bottom: 1px solid #e3e6f0;
}
.timeline-item:last-child .timeline-content {
    border-bottom: none;
}
</style>

<?php include(APP_PATH . '/views/admin/includes/footer.php'); ?>