<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Import JAMB Data</h1>
            <p class="text-muted">Upload JAMB candidate records for the current admission cycle</p>
        </div>
    </div>

    <?php if (!empty($flash_success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($flash_success); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if (!empty($flash_error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($flash_error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <!-- Import Form -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-upload me-2"></i>Upload JAMB Records
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/applications/jamb-import" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="mb-3">
                            <label for="jamb_file" class="form-label">Select File (CSV, TXT, or JSON)</label>
                            <input type="file" class="form-control" id="jamb_file" name="jamb_file" accept=".csv,.txt,.json" required>
                            <div class="form-text">
                                Maximum file size: 5MB. 
                                <a href="/admin/applications/jamb-template" class="text-primary">Download template</a>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle"></i> File Format Requirements:</h6>
                            <p class="mb-0">CSV file must have these columns in the first row:</p>
                            <code>jamb_number,first_name,last_name,other_names,gender,state_of_origin,lga,aggregate_score,program_applied,email,phone,date_of_birth,exam_year</code>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Upload and Import
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recent Imports -->
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Import History
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($imports)): ?>
                        <p class="text-muted text-center py-3">No imports yet</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Filename</th>
                                        <th>Total</th>
                                        <th>Success</th>
                                        <th>Failed</th>
                                        <th>Imported By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($imports as $import): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y H:i', strtotime($import['import_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($import['filename']); ?></td>
                                        <td><?php echo $import['total_records']; ?></td>
                                        <td class="text-success"><?php echo $import['successful_imports']; ?></td>
                                        <td class="text-danger"><?php echo $import['failed_imports']; ?></td>
                                        <td><?php echo htmlspecialchars($import['imported_by']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Statistics Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted d-block">Total Candidates</label>
                        <h3><?php echo number_format($stats['total'] ?? 0); ?></h3>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted d-block">Available (Unused)</label>
                        <h3 class="text-success"><?php echo number_format(($stats['total'] ?? 0) - ($stats['used'] ?? 0)); ?></h3>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted d-block">Used</label>
                        <h3 class="text-warning"><?php echo number_format($stats['used'] ?? 0); ?></h3>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted d-block">Imported</label>
                        <h3 class="text-info"><?php echo number_format($stats['imported'] ?? 0); ?></h3>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="/admin/applications/jamb-template" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Download CSV Template
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Tips - Updated Section -->
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Import Tips
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">Use the template to ensure correct format</li>
                        <li class="mb-2">Required columns: jambId, lastName, firstName, gender, state, lga, aggregateScore</li>
                        <li class="mb-2">Gender must be 'M' or 'F'</li>
                        <li class="mb-2">Duplicate JAMB numbers are skipped</li>
                        <li class="mb-2">Email and phone will be collected during application</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>