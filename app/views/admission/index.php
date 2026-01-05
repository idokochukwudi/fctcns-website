<?php
// app/views/admission/index.php
?>
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end border-bottom pb-3 mb-4">
            <div>
                <h1 class="h2 fw-bold text-dark mb-2">2025/2026 Admission List</h1>
                <p class="text-muted mb-0">FCT College of Nursing Sciences - ND Nursing Programme</p>
            </div>
            <div class="text-end">
                <div class="text-muted small">Last Updated: <?php echo date('F j, Y'); ?></div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="card border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Total Candidates</h6>
                            <h2 class="fw-bold text-dark"><?php echo $statistics['total']; ?></h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users text-primary fa-2x"></i>
                        </div>
                    </div>
                    <p class="text-muted small mb-0 mt-2">Provisional admission offered for 2025/2026 session</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-start border-success border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Admission Accepted</h6>
                            <h2 class="fw-bold text-dark"><?php echo $statistics['accepted_count']; ?></h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                    </div>
                    <p class="text-muted small mb-0 mt-2">Admission accepted on JAMB CAPS</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Pending Acceptance</h6>
                            <h2 class="fw-bold text-dark"><?php echo $statistics['approved_count']; ?></h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-clock text-warning fa-2x"></i>
                        </div>
                    </div>
                    <p class="text-muted small mb-0 mt-2">Awaiting acceptance on JAMB CAPS</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Deadline Notice -->
    <div class="alert alert-warning bg-warning bg-opacity-10 border-warning mb-5">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle text-warning fa-lg me-3"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-2">Important Deadline Notice</h5>
                <p class="mb-2">All prospective students must accept or reject their admission on <strong>JAMB CAPS</strong> by <strong class="text-danger">January 9, 2025</strong>.</p>
                <p class="mb-0"><strong>Note:</strong> Admissions not accepted by this date will be automatically withdrawn.</p>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="card shadow-sm mb-5">
        <div class="card-body p-4">
            <h5 class="card-title mb-4 text-dark">Check Your Admission Status</h5>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <label class="form-label fw-medium mb-2">Search by Registration Number</label>
                        <form action="<?php echo BASE_URL; ?>/admission/check" method="GET" class="input-group">
                            <input type="text" name="reg" class="form-control form-control-lg" 
                                   placeholder="Enter your registration number (e.g., 202551998000BF)" required>
                            <button type="submit" class="btn btn-dark btn-lg px-4">
                                Check Status
                            </button>
                        </form>
                        <div class="form-text mt-2">Enter your registration number exactly as provided</div>
                    </div>
                    
                    <div>
                        <label class="form-label fw-medium mb-2">Search by Name or Registration Number</label>
                        <form action="<?php echo BASE_URL; ?>/admission/search" method="GET" class="input-group">
                            <input type="text" name="q" class="form-control" 
                                   placeholder="Search by name or registration number">
                            <button type="submit" class="btn btn-outline-dark">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-4 border-start ps-4">
                    <h6 class="mb-3">Records Display</h6>
                    <div class="d-flex gap-2 mb-3">
                        <a href="<?php echo BASE_URL; ?>/admission?per_page=10" 
                           class="btn btn-outline-dark <?php echo $perPage == 10 ? 'active' : ''; ?>">
                            10 per page
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admission?per_page=25" 
                           class="btn btn-outline-dark <?php echo $perPage == 25 ? 'active' : ''; ?>">
                            25 per page
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admission?view_all=1" 
                           class="btn btn-outline-dark">
                            View All
                        </a>
                    </div>
                    <p class="text-muted small">Showing <?php echo $perPage; ?> records per page by default</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admission List Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 text-dark">Admission List</h5>
                    <p class="text-muted small mb-0 mt-1">
                        Showing records <?php echo (($currentPage - 1) * $perPage) + 1; ?> - 
                        <?php echo min($currentPage * $perPage, $totalRecords); ?> of <?php echo $totalRecords; ?>
                    </p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>/admission?per_page=<?php echo $perPage == 10 ? 25 : 10; ?>" 
                       class="btn btn-outline-dark btn-sm">
                        Show <?php echo $perPage == 10 ? 'More' : 'Less'; ?>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="border-bottom">
                            <th class="ps-4" style="width: 70px;">S/N</th>
                            <th>Registration Number</th>
                            <th>Candidate Name</th>
                            <th class="text-center">Status</th>
                            <th class="pe-4 text-end" style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($admissions)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No admission records found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($admissions as $student): ?>
                            <tr class="border-bottom">
                                <td class="ps-4 fw-medium text-muted"><?php echo htmlspecialchars($student['serial_number']); ?></td>
                                <td>
                                    <div class="fw-medium font-monospace"><?php echo htmlspecialchars($student['registration_number']); ?></div>
                                </td>
                                <td>
                                    <div class="fw-normal"><?php echo htmlspecialchars($student['candidate_name']); ?></div>
                                </td>
                                <td class="text-center">
                                    <?php if ($student['admission_status'] == 'Accepted'): ?>
                                        <span class="badge bg-success">
                                            Accepted
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">
                                            Approved
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="<?php echo BASE_URL; ?>/admission/check?reg=<?php echo urlencode($student['registration_number']); ?>" 
                                       class="btn btn-sm btn-outline-dark">
                                        View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?>
                </div>
                <nav>
                    <ul class="pagination mb-0">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo BASE_URL; ?>/admission?page=<?php echo $currentPage - 1; ?>&per_page=<?php echo $perPage; ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php 
                        // Show limited page numbers
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        
                        for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo BASE_URL; ?>/admission?page=<?php echo $i; ?>&per_page=<?php echo $perPage; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo BASE_URL; ?>/admission?page=<?php echo $currentPage + 1; ?>&per_page=<?php echo $perPage; ?>">
                                    Next
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Instructions Section -->
    <div class="row mt-5">
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-start border-success border-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 text-dark">For Accepted Candidates</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex">
                            <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div>
                                <p class="mb-1 fw-medium">Print your JAMB Admission Letter</p>
                                <p class="text-muted small mb-0">Print both Institution and Personal copies</p>
                            </div>
                        </li>
                        <li class="mb-3 d-flex">
                            <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-building text-success"></i>
                            </div>
                            <div>
                                <p class="mb-1 fw-medium">Report to the College</p>
                                <p class="text-muted small mb-0">Proceed to the college for documentation</p>
                            </div>
                        </li>
                        <li class="d-flex">
                            <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-file-alt text-success"></i>
                            </div>
                            <div>
                                <p class="mb-1 fw-medium">Bring Required Documents</p>
                                <p class="text-muted small mb-0">Bring the Institution copy of your admission letter</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-start border-warning border-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 text-dark">For Approved Candidates</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-laptop text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-1 fw-medium">Accept on JAMB CAPS</p>
                                <p class="text-muted small mb-0">Log in to JAMB CAPS immediately to accept admission</p>
                            </div>
                        </li>
                        <li class="mb-3 d-flex">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-print text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-1 fw-medium">Print Admission Letter</p>
                                <p class="text-muted small mb-0">Print both Institution and Personal copies</p>
                            </div>
                        </li>
                        <li class="d-flex">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-1 fw-medium">Deadline: January 9, 2025</p>
                                <p class="text-muted small mb-0">Admissions not accepted by this date will be withdrawn</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumption Details -->
    <div class="card border-0 bg-light mt-4">
        <div class="card-body">
            <h6 class="mb-3 text-dark">Academic Calendar</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="bg-white p-3 rounded border">
                        <div class="text-muted small mb-2">Resumption Date</div>
                        <div class="fw-medium">January 6, 2025</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="bg-white p-3 rounded border">
                        <div class="text-muted small mb-2">Orientation Programme</div>
                        <div class="fw-medium">January 6-9, 2025</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="bg-white p-3 rounded border">
                        <div class="text-muted small mb-2">Lectures Commence</div>
                        <div class="fw-medium">January 12, 2025</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="text-center mt-5 pt-4 border-top">
        <h6 class="text-muted mb-3">For Further Inquiries</h6>
        <p class="mb-2">Admissions Office - FCT College of Nursing Sciences</p>
        <div class="text-muted small">
            <span class="me-3">Email: admissions@fctcns.edu.ng</span>
            <span>Phone: [Your Contact Number]</span>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 8px;
}

.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.badge {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.page-item.active .page-link {
    background-color: #495057;
    border-color: #495057;
}

.page-link {
    color: #495057;
    border: 1px solid #dee2e6;
}

.page-link:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.btn-outline-dark.active {
    background-color: #495057;
    color: white;
    border-color: #495057;
}
</style>