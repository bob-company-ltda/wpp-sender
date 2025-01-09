"use strict";

const cloudapi_id = $('#cloudapi_id').val();
const base_url = $('#base_url').val();
let checkSessionInterval;

checkSession();

function checkSession() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$.ajax({
		type: 'POST',
		url: base_url + '/user/check-session/' + cloudapi_id,
		dataType: 'json',
		success: function(response) {
			if (response.message === 'CloudApi Connected Successfully') {
				// CloudApi connected
				NotifyAlert('success', null, response.message);
				$('.loggout_area').show();
				$('.hook-area').html(`<img src="${base_url}/uploads/connected.png" class="w-50"><br>`);
				$('.logged-alert').show();
				$('.progress').hide();
				$('.server_disconnect').hide();
				$('.helper-box').show();
				clearInterval(checkSessionInterval); // Stop the loop
			} else {
				// CloudApi disconnected
				$('.hook-area').html(`<img src="${base_url}/uploads/disconnect.webp" class="w-50"><br>`);
				$('.server_disconnect').show();
				$('.logged-alert').hide();
			}
		},
		error: function(xhr, status, error) {
			$('.hook-area').html(`<img src="${base_url}/uploads/disconnect.webp" class="w-50"><br>`);
			$('.server_disconnect').show();
			$('.logged-alert').hide();
		}
	});
}

checkSessionInterval = setInterval(checkSession, 5000);
