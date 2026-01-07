<?php include(APP_PATH . '/views/admin/includes/header.php'); ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">User Management</h1>
        <div>
            <a href="<?php echo BASE_URL; ?>/admin/users/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add User
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/users/export<?php echo !empty($filters) ? '?' . http_build_query($filters) : ''; ?>" 
               class="btn btn-outline-secondary">
                <i class="fas fa-download"></i> Export
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Total Users</h6>
                            <h3 class="mb-0"><?php echo $stats['total']; ?></h3>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Active Users</h6>
                            <h3 class="mb-0"><?php echo $stats['active']; ?></h3>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Admins</h6>
                            <h3 class="mb-0"><?php echo $stats['admin_count']; ?></h3>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Today Logins</h6>
                            <h3 class="mb-0"><?php echo $stats['today_logins']; ?></h3>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." 
                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-control">
                        <option value="">All Roles</option>
                        <?php foreach ($roles as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($filters['role'] ?? '') == $key ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" <?php echo ($filters['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($filters['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="department" class="form-control">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo htmlspecialchars($dept); ?>" 
                                <?php echo ($filters['department'] ?? '') == $dept ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dept); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No users found</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <?php if ($user['profile_picture']): ?>
                                        <img src="<?php echo BASE_URL . '/uploads/profiles/' . htmlspecialchars($user['profile_picture']); ?>" 
                                             alt="<?php echo htmlspecialchars($user['full_name']); ?>" 
                                             class="rounded-circle" width="40" height="40">
                                        <?php else: ?>
                                        <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>
                                        <div class="text-muted small">@<?php echo htmlspecialchars($user['username']); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($user['email']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'editor' ? 'warning' : 'info'); ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                                <div class="small text-muted mt-1">
                                    <?php echo $user['permission_count']; ?> permissions
                                </div>
                            </td>
                            <td>
                                <?php echo $user['department'] ? htmlspecialchars($user['department']) : '<span class="text-muted">—</span>'; ?>
                            </td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                                <?php if ($user['must_change_password']): ?>
                                <div class="small text-warning mt-1">
                                    <i class="fas fa-exclamation-triangle"></i> Must change password
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['last_login']): ?>
                                <div><?php echo date('M d, Y', strtotime($user['last_login'])); ?></div>
                                <div class="small text-muted"><?php echo date('H:i', strtotime($user['last_login'])); ?></div>
                                <?php else: ?>
                                <span class="text-muted">Never</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo BASE_URL; ?>/admin/users/view/<?php echo $user['id']; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/admin/users/edit/<?php echo $user['id']; ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/toggle-status/<?php echo $user['id']; ?>" 
                                          class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                                        <input type="hidden" name="value" value="<?php echo $user['is_active'] ? '0' : '1'; ?>">
                                        <button type="submit" class="btn btn-sm btn-<?php echo $user['is_active'] ? 'warning' : 'success'; ?>" 
                                                title="<?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                            <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?>"></i>
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/reset-password/<?php echo $user['id']; ?>" 
                                          class="d-inline" onsubmit="return confirm('Reset password for this user?');">
                                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-info" title="Reset Password">
                                            <i class="fas fa-key"></i>
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/delete/<?php echo $user['id']; ?>" 
                                          class="d-inline" onsubmit="return confirm('Delete this user permanently?');">
                                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($pagination['total'] > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $pagination['current'] == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo BASE_URL; ?>/admin/users?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['current'] - 1])); ?>">
                            Previous
                        </a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                    <li class="page-item <?php echo $pagination['current'] == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo BASE_URL; ?>/admin/users?<?php echo http_build_query(array_merge($filters, ['page' => $i])); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?php echo $pagination['current'] == $pagination['total'] ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo BASE_URL; ?>/admin/users?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['current'] + 1])); ?>">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include(APP_PATH . '/views/admin/includes/footer.php'); ?>