<?php
// Available columns for export
$availableColumns = [
    'employee_number' => 'Employee Number',
    'surname' => 'Surname',
    'first_name' => 'First Name',
    'middle_name' => 'Middle Name',
    'sex' => 'Sex',
    'date_of_birth' => 'Date of Birth',
    'marital_status' => 'Marital Status',
    'rank' => 'Rank',
    'grade_level' => 'Grade Level',
    'state' => 'State of Origin',
    'local_govt_area' => 'Local Government Area',
    'telephone_number' => 'Telephone',
    'email' => 'Email',
    'date_of_first_appointment' => 'Date of 1st Appointment',
    'pf_number' => 'PF Number',
    'bank_name' => 'Bank Name',
    'account_number' => 'Account Number',
];
?>

<div class="export-modal" id="exportModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Select Columns to Export</h3>
                <button class="close-btn" onclick="closeExportModal()">&times;</button>
            </div>
            
            <div class="modal-body">
                <div class="column-selection">
                    <div class="selection-controls">
                        <button onclick="selectAllColumns()">Select All</button>
                        <button onclick="deselectAllColumns()">Deselect All</button>
                    </div>
                    
                    <div class="columns-grid">
                        <?php foreach ($availableColumns as $key => $label): ?>
                        <label class="column-item">
                            <input type="checkbox" 
                                   name="export_columns[]" 
                                   value="<?php echo $key; ?>"
                                   <?php echo in_array($key, ['employee_number', 'surname', 'first_name']) ? 'checked' : ''; ?>>
                            <span><?php echo $label; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="export-options">
                    <label>
                        <strong>Export Format:</strong>
                        <select id="exportFormat">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeExportModal()">Cancel</button>
                <button class="btn btn-primary" onclick="processExport()">Export</button>
            </div>
        </div>
    </div>
</div>

<script>
function openExportModal() {
    document.getElementById('exportModal').style.display = 'flex';
}

function closeExportModal() {
    document.getElementById('exportModal').style.display = 'none';
}

function selectAllColumns() {
    document.querySelectorAll('input[name="export_columns[]"]')
        .forEach(cb => cb.checked = true);
}

function deselectAllColumns() {
    document.querySelectorAll('input[name="export_columns[]"]')
        .forEach(cb => cb.checked = false);
}

function processExport() {
    const selectedColumns = Array.from(
        document.querySelectorAll('input[name="export_columns[]"]:checked')
    ).map(cb => cb.value);
    
    const format = document.getElementById('exportFormat').value;
    
    if (selectedColumns.length === 0) {
        alert('Please select at least one column');
        return;
    }
    
    // Build export URL with selected columns
    const params = new URLSearchParams();
    params.append('format', format);
    selectedColumns.forEach(col => params.append('columns[]', col));
    
    // Add current filters
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.forEach((value, key) => {
        if (key !== 'format' && key !== 'columns[]') {
            params.append(key, value);
        }
    });
    
    // Redirect to export URL
    window.location.href = '<?php echo $baseUrl; ?>/admin/nominal-roll/export?' + params.toString();
}
</script>