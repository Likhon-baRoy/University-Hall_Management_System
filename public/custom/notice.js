// Add this to your public/js/admin/notices.js file
document.addEventListener('DOMContentLoaded', function() {
    // Handle status toggles
    const statusToggles = document.querySelectorAll('.status-toggle input[type="checkbox"]');

    statusToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const noticeId = this.id.replace('status_', '');
            const status = this.checked;

            // Show loading state
            this.disabled = true;

            fetch(`/admin/notices/${noticeId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        toastr.success('Notice status updated successfully');
                    } else {
                        // Revert toggle if failed
                        this.checked = !status;
                        toastr.error('Failed to update notice status');
                    }
                })
                .catch(error => {
                    // Revert toggle if failed
                    this.checked = !status;
                    toastr.error('An error occurred while updating status');
                    console.error('Error:', error);
                })
                .finally(() => {
                    this.disabled = false;
                });
        });
    });
});
