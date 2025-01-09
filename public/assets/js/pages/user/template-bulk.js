"use strict";

let totalSent = 0; // Initialize the total sent counter
let timeoutOccurred = false; // Flag to track if a timeout has occurred
let pendingForms = 0; // Track the number of pending forms
let showSuccessNotification = true; // Flag to control success notification

$(document).on('click', '.send_now', function () {
    timeoutOccurred = false; // Reset timeout flag on button click
    showSuccessNotification = true; // Reset success notification flag
    var forms = $('.bulk_form').length;
    pendingForms = forms; // Set the number of pending forms

    if (forms > 0) {
        $('.send_now').attr('disabled', 'disabled');

        // Iterate over each form with class .bulk_form and submit them
        $('.bulk_form').each(function () {
            if (!timeoutOccurred) { // Only submit if no timeout has occurred
                $(this).submit();
            }
        });
    } else {
        $('.send_now').removeAttr('disabled');
        ToastAlert('error', 'No Record Available For Sending');
    }
});

$(document).on('click', '.delete-form', function () {
    const row = $(this).data('action');
    $(row).remove();

    // Update total records count
    var totalRecords = $('#total_records').text();
    totalRecords = parseInt(totalRecords) - 1;
    $('.total_records').html(totalRecords);
});

$('.send-message').on('click', function () {
    var formclass = $(this).data('form');
    $(formclass).submit();
});

$('.bulk_form').on('submit', function (e) {
    e.preventDefault();
    var $form = $(this);
    var key = $form.data('key');

    if (timeoutOccurred) {
        return; // If a timeout has occurred, do not proceed with submission
    }

    // Setup CSRF token for AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let $savingLoader = 'Please wait...';
    let $submitBtn = $form.find('.submit-button');
    let $oldSubmitBtn = $submitBtn.html();

    // AJAX request to send message
    $.ajax({
        type: 'POST',
        url: this.action,
        data: new FormData(this),
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        timeout: 10000, // Set timeout to 10 seconds
        beforeSend: function () {
            $submitBtn.html($savingLoader).attr('disabled', true);
            $('.badge_' + key).html('Sending...');
        },
        success: function (res) {
            $submitBtn.html($oldSubmitBtn).attr('disabled', false);
            $('.badge_' + key).html('Sent 🚀').removeClass('badge-warning').addClass('badge-success').removeClass('sendable').addClass('msg-sent');
            $('.badge_' + key).removeClass('faild-form');

            // Remove form row from table upon successful send
            $form.closest('tr').remove();

            // Increment total sent counter
            totalSent++;
            $('.total_sent').html(totalSent);

            pendingForms--; // Decrease the count of pending forms
            if (pendingForms === 0 && showSuccessNotification) {
                NotifyAlert('success', 'All messages have been sent successfully!');
                showSuccessNotification = false; // Ensure the notification is shown only once
            }
        },
        error: function (xhr, status) {
            $submitBtn.html($oldSubmitBtn).attr('disabled', false);
            if (status === "timeout") {
                if (!timeoutOccurred) {
                    timeoutOccurred = true; // Set timeout flag
                    ToastAlert('error', 'Request timed out. Please click "Send To All" button again.');
                    $('.send_now').removeAttr('disabled'); // Enable the send button again
                }
            } else {
                $('.badge_' + key).html('Sending Failed').addClass('badge-danger').addClass('faild-form');
                var totalFailed = $('.faild-form').length;
                $('.total-faild').html(totalFailed);
                NotifyAlert('error', xhr);
            }
        }
    });
});
