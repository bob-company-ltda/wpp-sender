"use strict";

$('.show-id').on('click',function(){
	const uuid= $(this).data('uuid');
	const templateName= $(this).data('templatename');

	$('#templateid').val(uuid);
	$('#templateName').html(templateName);
});

function showSuccessAlert(message) {
    $('#alertPopup').text(message);
    $('#alertPopup').show();
}

// Close the alert popup
$('.close').on('click', function() {
	$(this).closest('.alert').hide();
});
const base_url = $('#base_url').val();
var cloudapiId = $('#cloudapi').val();
$('.check-status').on('click', function() {
	
	$.ajax({
		url: base_url + '/user/get-template',
		method: 'POST',
		data: {
			cloudapi_id: cloudapiId
		},
		success: function(response) {
			// Handle the response data
			console.log(response); // Replace with your code to process the response

			// Update the template ID in the modal
			showSuccessAlert(response.message);
		},
		error: function(xhr, status, error) {
			// Handle any errors
			console.error(error);
		}
	});
});