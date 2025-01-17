// public/admin/assets/js/notifications.js
$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Mark single notification as read
    $('.notification-message a').click(function(e) {
        const notificationId = $(this).data('notification-id');
        if (!notificationId) return;

        $.post(`/admin/notifications/${notificationId}/mark-as-read`)
            .done(function(response) {
                if (response.success) {
                    $(e.currentTarget).closest('.notification-message').removeClass('unread');
                    updateNotificationCount();
                }
            })
            .fail(function(error) {
                console.error('Error marking notification as read:', error);
            });
    });

    // Mark all notifications as read
    $('#mark-all-read').click(function(e) {
        e.preventDefault();

        $.post('/admin/notifications/mark-all-read')
            .done(function(response) {
                if (response.success) {
                    $('.notification-message').removeClass('unread');
                    updateNotificationCount();
                    // Update the notification list to reflect the changes
                    $('.noti-content .notification-list').empty();
                }
            })
            .fail(function(error) {
                console.error('Error marking all notifications as read:', error);
            });
    });

    // Function to update notification count with error handling
    function updateNotificationCount() {
        $.get('/admin/notifications/count')
            .done(function(response) {
                if (response.success) {
                    const count = response.count;
                    $('#notification-count').text(count);

                    // Toggle badge visibility based on count
                    if (count > 0) {
                        $('#notification-count').show();
                    } else {
                        $('#notification-count').hide();
                    }
                }
            })
            .fail(function(error) {
                console.error('Error getting notification count:', error);
            });
    }

    // Function to refresh notification list
    function refreshNotifications() {
        $.get('/admin/notifications/list')
            .done(function(response) {
                if (response.success && response.notifications) {
                    const notificationList = $('.noti-content .notification-list');
                    notificationList.empty();

                    response.notifications.forEach(function(notification) {
                        // Add each notification to the list
                        const notificationHtml = createNotificationHtml(notification);
                        notificationList.append(notificationHtml);
                    });
                }
            })
            .fail(function(error) {
                console.error('Error refreshing notifications:', error);
            });
    }

    // Helper function to create notification HTML
    function createNotificationHtml(notification) {
        return `
            <li class="notification-message ${notification.read_at ? '' : 'unread'}">
                <a href="${notification.data.problem_id ? '/problems/' + notification.data.problem_id : '#'}"
                   data-notification-id="${notification.id}">
                    <div class="media">
                        <span class="avatar avatar-sm">
                            <img class="avatar-img rounded-circle" alt="User Image"
                                 src="${notification.data.user_photo || 'admin/assets/img/profiles/default.jpg'}">
                        </span>
                        <div class="media-body">
                            <p class="noti-details">
                                <span class="noti-title">${notification.data.message}</span>
                                ${notification.data.title}
                            </p>
                            <p class="noti-time">
                                <span class="notification-time">${notification.created_at}</span>
                            </p>
                        </div>
                    </div>
                </a>
            </li>
        `;
    }

    // Initial updates
    updateNotificationCount();
    refreshNotifications();

    // Set up periodic updates
    setInterval(function() {
        updateNotificationCount();
        refreshNotifications();
    }, 30000); // Update every 30 seconds
});
