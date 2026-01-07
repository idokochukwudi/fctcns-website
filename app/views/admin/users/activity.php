<?php include(APP_PATH . '/views/admin/includes/header.php'); ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">User Activity Logs</h1>
        <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td>
                                <?php if ($activity['user_name']): ?>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px; font-size: 14px;">
                                            <?php echo strtoupper(substr($activity['user_name'], 0, 1)); ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($activity['user_name']); ?></div>
                                        <small class="text-muted">ID: <?php echo $activity['user_id']; ?></small>
                                    </div>
                                </div>
                                <?php else: ?>
                                <span class="text-muted">System</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo htmlspecialchars($activity['action']); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($activity['description']); ?></td>
                            <td>
                                <code><?php echo htmlspecialchars($activity['ip_address']); ?></code>
                            </td>
                            <td>
                                <?php echo date('M d, Y', strtotime($activity['created_at'])); ?><br>
                                <small class="text-muted"><?php echo date('H:i:s', strtotime($activity['created_at'])); ?></small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include(APP_PATH . '/views/admin/includes/footer.php'); ?>