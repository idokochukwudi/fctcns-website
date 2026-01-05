<?php
// app/views/admission/search.php
?>
<div class="container-fluid px-4 py-5">
    <div class="card border-primary shadow-lg">
        <div class="card-header bg-primary text-white py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0"><i class="fas fa-search me-2"></i>Search Results</h4>
                    <p class="mb-0 mt-1 opacity-75">Admission List Search</p>
                </div>
                <a href="<?php echo BASE_URL; ?>/admission" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i>Back to Full List
                </a>
            </div>
        </div>
        
        <div class="card-body p-4">
            <!-- Search Stats -->
            <div class="alert alert-info border-info border-3 mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-2">Search Results for "<?php echo htmlspecialchars($searchKeyword); ?>"</h5>
                        <p class="mb-0">Found <strong class="fs-4"><?php echo $resultCount; ?></strong> candidate(s) matching your search criteria.</p>
                    </div>
                </div>
            </div>
            
            <!-- New Search Form -->
            <div class="card border-secondary shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>/admission/search" method="GET" class="row g-3">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="q" class="form-control" 
                                       value="<?php echo htmlspecialchars($searchKeyword); ?>"
                                       placeholder="Search by Registration Number or Candidate Name...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i>Search Again
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <?php if (!empty($searchResults)): ?>
                <!-- Results Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">S/N</th>
                                <th>Registration Number</th>
                                <th>Candidate Name</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($searchResults as $student): ?>
                            <tr class="<?php echo $student['admission_status'] == 'Approved' ? 'table-warning' : ''; ?>">
                                <td class="text-center fw-bold"><?php echo htmlspecialchars($student['serial_number']); ?></td>
                                <td>
                                    <code class="bg-light p-2 rounded"><?php echo htmlspecialchars($student['registration_number']); ?></code>
                                </td>
                                <td class="fw-bold"><?php echo htmlspecialchars($student['candidate_name']); ?></td>
                                <td class="text-center">
                                    <?php if ($student['admission_status'] == 'Accepted'): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Accepted
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>Approved
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo BASE_URL; ?>/admission/check?reg=<?php echo urlencode($student['registration_number']); ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <!-- No Results -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-search fa-4x text-muted"></i>
                    </div>
                    <h3 class="text-muted mb-3">No Results Found</h3>
                    <p class="text-muted mb-4">We couldn't find any candidates matching "<?php echo htmlspecialchars($searchKeyword); ?>"</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?php echo BASE_URL; ?>/admission" class="btn btn-primary">
                            <i class="fas fa-list me-1"></i>View Full Admission List
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admission/search" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i>Try Another Search
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.table tbody tr:hover {
    background-color: rgba(107, 78, 155, 0.05);
}
</style>