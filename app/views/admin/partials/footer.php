    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript for research module
        document.addEventListener('DOMContentLoaded', function() {
            // Bulk actions
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.publication-checkbox');
            const bulkActions = document.querySelector('.bulk-actions');
            
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActions();
                });
            }
            
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkActions);
            });
            
            function updateBulkActions() {
                const checked = document.querySelectorAll('.publication-checkbox:checked');
                if (bulkActions) {
                    bulkActions.style.display = checked.length > 0 ? 'block' : 'none';
                }
            }
            
            // Delete confirmation
            const deleteButtons = document.querySelectorAll('.delete-publication');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    if (confirm(`Are you sure you want to delete "${title}"?`)) {
                        // AJAX delete request
                        fetch(`/admin/research/${id}/delete`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': '<?php echo $_SESSION['csrf_token'] ?? ''; ?>'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.message || 'Failed to delete publication');
                            }
                        });
                    }
                });
            });
            
            // Toggle status
            const toggleButtons = document.querySelectorAll('.toggle-status');
            toggleButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const currentStatus = this.getAttribute('data-status');
                    
                    fetch(`/admin/research/${id}/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': '<?php echo $_SESSION['csrf_token'] ?? ''; ?>'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Failed to update status');
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>