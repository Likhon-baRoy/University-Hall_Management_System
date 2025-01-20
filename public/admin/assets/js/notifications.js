// Initialize notification handling when document is ready
$(document).ready(function() {
    // Configure CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Configure toastr notifications
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 3000
    };

    // Handle notification click and marking as read
    $(document).on('click', '.notification-message a', function(e) {
        e.preventDefault();
        const notificationId = $(this).data('notification-id');
        const href = $(this).attr('href');

        if (!notificationId) {
            if (href && href !== '#') {
                window.location.href = href;
            }
            return;
        }

        // Mark notification as read
        // Updated URL with admin prefix
        $.post(`/admin/notifications/${notificationId}/mark-as-read`)
            .done(function(response) {
                if (response.success) {
                    $(e.currentTarget).closest('.notification-message').removeClass('unread');
                    updateNotificationCount();
                    refreshNotifications();

                    if (href && href !== '#') {
                        window.location.href = href;
                    }
                }
            })
            .fail(handleAjaxError);
    });

    // Handle "Mark All as Read"
    $('#mark-all-read').click(function(e) {
        e.preventDefault();
        // Updated URL with admin prefix
        $.post('/admin/notifications/mark-all-read')
            .done(function(response) {
                if (response.success) {
                    $('.notification-message').removeClass('unread');
                    updateNotificationCount();
                    refreshNotifications();
                    toastr.success('All notifications marked as read');
                }
            })
            .fail(handleAjaxError);
    });

    // Update notification count
    function updateNotificationCount() {
        // Updated URL with admin prefix
        $.get('/admin/notifications/count')
            .done(function(response) {
                if (response.success) {
                    const count = response.count;
                    const badge = $('#notification-count');
                    badge.text(count);
                    badge.toggle(count > 0);
                }
            })
            .fail(handleAjaxError);
    }

    // Format relative time for notifications
    function formatRelativeTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return 'just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
        return `${Math.floor(diffInSeconds / 86400)}d ago`;
    }

    // Refresh notification list
    function refreshNotifications() {
        // Updated URL with admin prefix
        $.get('/admin/notifications/list')
            .done(function(response) {
                // Rest of the function remains the same
            })
            .fail(handleAjaxError);
    }

    // Handle AJAX errors
    function handleAjaxError(error) {
        console.error('Ajax Error:', error);
        toastr.error('An error occurred while processing your request');
    }

    // Initialize notifications
    updateNotificationCount();
    refreshNotifications();

    // Set up periodic updates (every 30 seconds)
    setInterval(function() {
        updateNotificationCount();
        refreshNotifications();
    }, 30000);
});
