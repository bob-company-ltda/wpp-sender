"use strict";

const cloudapi_id = $('#uuid').val();
const base_url  = $('#base_url').val();
const whatsappicon = base_url+'/assets/img/whatsapp.png';

checkSession();

function checkSession() {

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: base_url+'/user/check-session/'+cloudapi_id,
		dataType: 'json',
		success: function(response) {
			if (response.connected === true) {
				$('.server_disconnect').remove();
				$('.qr-area').remove();

				NotifyAlert('success', null, response.message);
				getChatList();

			}
			else{
				NotifyAlert('error', null, 'cloudapi not ready for sending message');
			}
		},
		error: function(xhr, status, error) {
			if (xhr.status == 500) {
				
				$('.server_disconnect').show();
				$('.main-area').hide();

				
				
			}

		}
	});
}

function getChatList() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: base_url+'/user/get-groups/'+cloudapi_id,
		dataType: 'json',
		success: function(response) {
			
			$('.qr-area').remove();

			$.each(response.chats, function (key, item) {

				console.log(item)

				var html = `<li class="list-group-item px-0 contact contact${key}">
				<div class="row align-items-center">
				<div class="col-auto">
				<a href="javascript:void(0)" data-active=".contact${key}" data-name="${item.name}"  data-id="${item.id}" class="avatar rounded-circle wa-link ml-2">
				<img alt="" src="${whatsappicon}">
				</a>
				</div>
				<div class="col ml--2">
				<h4 class="mb-0">
				<a href="javascript:void(0)" data-active=".contact${key}" class="wa-link" data-name="${item.name}"  data-id="${item.id}">${item.name}</a>
				</h4>
				
				</div>
				</div>
				</li>`;

				$('.contact-list').append(html);
			});


		},
		error: function(xhr, status, error) {
			if (xhr.status == 500) {

			}

		}
	});
}


function successCallBack() {
	$('#plain-text').val('');
}


$(document).on('click','.wa-link',function(){
	const name = $(this).data('name');
	const id = $(this).data('id');

	const activeTarget = $(this).data('active');


	$('.contact').removeClass('active');
	$(activeTarget).addClass('active');

	
	$('.sendble-row').removeClass('none');
	$('.reciver-group').val(name);
	$('.reciver-id').val(id);

});

$(document).on('change','#select-type',function(){
	var type = $(this).val();


	if (type == 'plain-text') {
		$('#plain-text').show();
		$('#templates').hide();
	}
	else{
		$('#plain-text').hide();
		$('#templates').show();
	}


});

function sortByKey(array, key) {
	return array.sort(function(a, b) {
		var x = a[key]; var y = b[key];
		return ((x > y) ? -1 : ((x < y) ? 1 : 0));
	});
}

function formatTimestamp(unixTimestamp) {

	    var d=new Date();  // Gets the current time
	    var nowTs = Math.floor(d.getTime()/1000); //
	    var seconds = nowTs-unixTimestamp;

	    // more that two days
	    if (seconds > 2*24*3600) {
	    	return "a few days ago";
	    }
	    // a day
	    if (seconds > 24*3600) {
	    	return "yesterday";
	    }

	    if (seconds > 3600) {
	    	return "a few hours ago";
	    }
	    if (seconds > 1800) {
	    	return "Half an hour ago";
	    }
	    if (seconds > 60) {
	    	return Math.floor(seconds/60) + " minutes ago";
	    }

	    return "Few seconds ago";

	}