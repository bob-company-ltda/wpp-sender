"use strict";

// Set up CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Play notification sound function
function playNotificationSound() {
    const audio = document.getElementById('notificationSound');
    if (audio) {
        audio.play();
    }
}

// Fetch notifications from the server
function fetchNotifications() {
    $.ajax({
        type: 'POST',
        url: '/user/notifications',
        dataType: 'json',
        cache: false,
        success: function(res) {
            // Update notification count
            $('.notification-count').html(res.notifications_unread);

            // Clear existing notifications
            $('.notifications-list').empty();

            // Render new notifications
            $(res.notifications).each(function(index, row) {
                const notificationHtml = `
                    <a href="${row.url}" class="list-group-item list-group-item-action">
                        <div class="row align-items-center">
                            <div class="col ml-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0 text-sm" style="color: ${row.seen === 0 ? 'green' : 'black'}">${row.title}</h4>
                                    </div>
                                    <div class="text-right text-muted">
                                        <small>${row.updated_at}</small>
                                    </div>
                                </div>
                                <p class="text-sm mb-0">${row.comment || ''}</p>
                            </div>
                        </div>
                    </a>`;
                
                $('.notifications-list').prepend(notificationHtml);
            });

            // Play sound if there are new unread notifications
            if (res.notifications_unread > 0) {
                playNotificationSound();
            }

            // Toggle notification area visibility
            res.notifications.length > 0 ? $('.notifications-area').show() : $('.notifications-area').hide();
        },
        error: function(xhr) {
            console.error("Failed to fetch notifications:", xhr);
        }
    });
}

// Set polling interval and visibility checking
let notificationInterval;

// Start polling notifications
function startNotificationPolling() {
    notificationInterval = setInterval(fetchNotifications, 15000); // Poll every 15 seconds
}

// Stop polling notifications
function stopNotificationPolling() {
    clearInterval(notificationInterval);
}

// Monitor page visibility to control polling
document.addEventListener("visibilitychange", function() {
    if (document.visibilityState === 'visible') {
        startNotificationPolling();
        fetchNotifications(); // Fetch immediately when tab becomes active
    } else {
        stopNotificationPolling();
    }
});

// Start polling initially if the page is visible
if (document.visibilityState === 'visible') {
    startNotificationPolling();
}
