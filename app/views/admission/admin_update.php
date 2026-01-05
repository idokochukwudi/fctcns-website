<?php
// app/views/admission/admin_update.php
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="mb-5">
                <h1 class="h3 fw-bold text-dark mb-3">Update Admission Status</h1>
                <p class="text-muted">Upload CSV file to update admission status from Approved to Accepted</p>
            </div>

            <!-- Messages -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-success border-success mb-4">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger border-danger mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Upload Form -->
            <div class="card shadow-sm mb-5">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0 text-dark">Upload CSV File</h6>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label fw-medium mb-3">Select CSV File</label>
                            <input type="file" class="form-control" name="csv_file" accept=".csv" required>
                            <div class="form-text mt-2">Upload CSV file containing updated admission statuses</div>
                        </div>

                        <div class="mb-4">
                            <h6 class="mb-3">CSV Format Requirements</h6>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0">registration_number,admission_status
202551212909HA,Accepted
202551265935IF,Accepted
202550521330GA,Accepted</pre>
                            </div>
                            <div class="form-text mt-2">
                                The system will automatically detect changes from Approved to Accepted and update accordingly.
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-upload me-2"></i>Upload & Update
                            </button>
                            <a href="<?php echo BASE_URL; ?>/admission" class="btn btn-outline-dark">
                                Back to Admission List
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Results -->
            <?php if (isset($updateResult) && $updateResult['success']): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-light py-3">
                        <h6 class="mb-0 text-dark">Update Results</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                    <div class="h4 fw-bold text-success mb-1"><?php echo $updateResult['updated']; ?></div>
                                    <div class="text-muted small">Records Updated</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="h4 fw-bold text-dark mb-1"><?php echo $updateResult['unchanged']; ?></div>
                                    <div class="text-muted small">Records Unchanged</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                                    <div class="h4 fw-bold text-danger mb-1"><?php echo count($updateResult['errors']); ?></div>
                                    <div class="text-muted small">Errors</div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($updateResult['errors'])): ?>
                            <div class="mt-4">
                                <h6 class="mb-3">Update Errors</h6>
                                <div class="bg-light p-3 rounded">
                                    <?php foreach ($updateResult['errors'] as $error): ?>
                                        <div class="text-danger small mb-1"><?php echo htmlspecialchars($error); ?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>