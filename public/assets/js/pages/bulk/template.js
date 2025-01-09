"use strict";

$('#add-more').on('click', function() {

    var exist_buttons = $('.plain_button_message_text').length;

    exist_buttons == 2 ? $('#add-more').hide() : $('#add-more').show();

    exist_buttons = exist_buttons + 1;

    var button_html = `<div class="plain_button_message_text exist_button${exist_buttons}">
											<div class="form-group">
												<div class="row">
													<div class="col-6">
														<label>Button ${exist_buttons} Text</label>
													</div>
													<div class="col-6">
														<a href="javascript:void(0)" data-target=".exist_button${exist_buttons}" class="btn btn-sm btn-danger float-right mb-1 remove-button"><i class="fa fa-trash"></i></a>
													</div>
												</div>
												<input type="text" class="form-control" name="buttons[]"  required="" maxlength="50">
											</div>
										</div>`;

    $('#list-button-appendarea-2').append(button_html);

});

//remove button from message with button
$(document).on('click', '.remove-button', function() {
    var target = $(this).data('target');
    $(target).remove();

    var exist_buttons = $('.plain_button_message_text').length;
    exist_buttons >= 3 ? $('#add-more').hide() : $('#add-more').show();

});
$('.save-template').on('change',function(){
        var target_action = $(this).data('target');
       
        $(this).is(':checked') ? $(target_action).show() : $(target_action).hide();
    });
$('#add-more-action').on('click', function() {


    var exist_buttons = $('.call-to-action-area').length;

    exist_buttons == 2 ? $('#add-more-action').hide() : $('#add-more-action').show();

    exist_buttons = exist_buttons + 1;

    var button_html = `<div class="card card-primary mt-2 call-to-action-area exist-action${exist_buttons}">
                                                		<div class="card-header">
                                                			<h4>Button ${exist_buttons}</h4>
                                                			<div class="card-header-action">
                                                			<a href="javascript:void(0)" data-target=".exist-action${exist_buttons}" class="btn btn-sm btn-danger remove-action">
                                                				<i class="fas fa-trash"></i>
                                                			</a>
                                                			</div>
                                                		</div>
                                                		<div class="card-body">
                                                			<div class="form-row">
                                                				<div class="form-group col-sm-4">
                                                					<label>
                                                						Select Action Type
                                                					</label>
                                                					<select class="form-control action-type" name="buttons[${exist_buttons}][type]" required="" >
                                                						<option value="urlButton" data-key="${exist_buttons}">Url Button</option>
                                                						<option value="callButton" data-key="${exist_buttons}">Phone number (Call Button)</option>
                                                						<option value="quickReplyButton" data-key="${exist_buttons}">Quick Reply Button</option>
                                                					</select>
                                                				</div>
                                                				<div class="form-group col-sm-4">
                                                					<label>
                                                						Button Display Text
                                                					</label>
                                                					<input type="text" class="form-control" name="buttons[${exist_buttons}][displaytext]"  maxlength="50" placeholder="Button Display Text" />
                                                				</div>
                                                				<div class="form-group col-sm-4 action-area${exist_buttons}">
                                                					<label>
                                                						Button Click To Action Value
                                                					</label>
                                                					<input type="text" class="form-control input_action${exist_buttons}" name="buttons[${exist_buttons}][action]" maxlength="50" placeholder="https://www.google.com/" />
                                                				</div>
                                                			</div>
                                                		</div>
                                                	</div>`;

    $('#action-body').append(button_html);

});

$(document).on('click', '.remove-action', function() {
    var target = $(this).data('target');
    $(target).remove();

    var exist_buttons = $('.button_message_text').length;
    exist_buttons >= 3 ? $('#add-more-action').hide() : $('#add-more-action').show();
});

$(document).on('change','.action-type',function(){
	var key = $(this).children('option:selected').data('key');
	var val = $(this).val();

	val == 'quickReplyButton' ?  $('.action-area'+key).hide() : $('.action-area'+key).show();

	if (val == 'urlButton') {
		$('.input_action'+key).attr('placeholder','https://www.google.com/');
	}
	else if(val == 'callButton'){
		$('.input_action'+key).attr('placeholder','Enter Phone Number With Country Code');
	}
	else{
		$('.input_action'+key).attr('placeholder','Exanple Text');
	}

});


//add more list card
$(document).on('click','#add-more-option',function(){
    const max_card=20;
    const total_exisit_card=$('.card-item').length+1;

    total_exisit_card >= max_card ? $('#add-more-option').hide() : $('#add-more-option').show();


    var card_html=`<div class="card card-primary card-item card-area${total_exisit_card}" style="flex: 0 0 100%;margin: 6px;">
   <div class="card-header">
      <h4>List ${total_exisit_card}</h4>
      <div class="card-header-action">
         <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-card" data-target=".card-area${total_exisit_card}">
         <i class="fas fa-trash"></i>
         </a>
      </div>
   </div>
   <div class="card-body">
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group">
               <label>List Section Title</label>
               <input  type="text" class="form-control" name="section[${total_exisit_card}][title]" placeholder="Example: Select a fruit" value="" required=""  maxlength="50" />
            </div>
         </div>
      </div>
      <div class="row list-item-area${total_exisit_card}">
         <div class="col-6">
            <div class="form-group">
               <label>Enter List Value Name</label>
               <input  type="text" class="form-control itemval-${total_exisit_card}" name="section[${total_exisit_card}][value][1][title]" placeholder="Example: Banana" value="" required=""  maxlength="50" />
            </div>
         </div>
         <div class="col-6">
            <div class="form-group">
               <label>Enter List Value Description</label>
               <input  type="text" class="form-control" name="section[${total_exisit_card}][value][1][description]" placeholder="Example: Banana is a healthly food" value=""   maxlength="50" />
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-12 text-right">
            <a href="javascript:void(0)" class="text-right btn btn-sm btn-neutral add-more-option-item option-item-btn${total_exisit_card}" data-target=".list-item-area${total_exisit_card}" data-key="${total_exisit_card}"><i class="fas fa-plus"></i>&nbspAdd More Item</a>
         </div>
      </div>
   </div>
</div>`;


$('.list-area').append(card_html);
});

//add more list item to the card
$(document).on('click','.add-more-option-item',function(){
    var target = $(this).data('target');
    var key    = $(this).data('key');
    var check_option_item=$('.itemval-'+key).length;
    var option_item_btn=$('.option-item-btn'+key);
    var list_exist_item=$('.item-'+key+'-'+key+1).length;
    if (check_option_item >= 20) {
        $('.option-item-btn'+key).hide();
    }

    var html=`<div class="col-6 item-${key}-${check_option_item+1}">
    <div class="form-group">
        <label>Enter List Value Name</label>
        <input  type="text" class="form-control itemval-${key}" name="section[${key}][value][${check_option_item+1}][title]" placeholder="Example: Banana" value="" required=""  maxlength="50" />
    </div>
    </div>
    <div class="col-6 item-${key}-${check_option_item+1}">
        <div class="form-group">
            <label>Enter List Value Description</label>
            <a href="javascript:void(0)" class="float-right btn btn-sm btn-danger remove-option-item" data-addbutton=".option-item-btn${key}" data-target=".item-${key}-${check_option_item+1}">X</a>
            <input  type="text" class="form-control" name="section[${key}][value][${check_option_item+1}][description]" placeholder="Example: Banana is a healthly food" value=""   maxlength="50" />
        </div>
    </div>`;

    $(target).append(html);


});

$(document).on('click','.remove-option-item',function(){
    const target_option_item = $(this).data('target');
    const option_item_btn    = $(this).data('addbutton');

    $(target_option_item).remove();
    $(option_item_btn).show();


});

$(document).on('click','.delete-card',function(){

    const selectedTarget=$(this).data('target');
    $(selectedTarget).remove();
});


const base_url = $('#base_url').val();

$('#template_text').change(function () {
    var updatedParameters = [];
    var selectedTemplate = $(this).val();
    $.ajax({
        url: base_url + '/user/template-details',
        type: 'POST',
        data: {
            template: selectedTemplate
        },
        success: function (response) {
            updatedParameters = [];
            $('#templateFields').html('');

            // Iterate through components and add dynamic fields
            $.each(response.body.components, function (index, component) {
                $('#language').val(response.body.language);
    if (component.type === 'HEADER' && component.format === 'IMAGE') {
        // Add media uploader for image in header
        var imagePrev = $('<img src="'+ component.example.header_handle[0]+'" class="card-img-top" style="">');
        $('#imagePrev').html(imagePrev);
         $('#documentPrev').empty();
        var headerImageInput = $('<label>Add Image:</label><input type="file" class="header_image form-control" name="header_image" accept="image/*"><small>Supported file type:</small> <small class="text-danger">jpg,jpeg,png,webp</small><br><input type="hidden" name="type" value="IMAGE"><input type="hidden" name="image_example" value="'+ component.example.header_handle[0]+'">');
         $('#templateFields').append(headerImageInput);
    } else if (component.type === 'HEADER' && component.format === 'DOCUMENT'){
        var docPrev = $('<img class="card-img-top" style="" src="/public/assets/img/pdf.png" alt="Card image cap">');
        $('#documentPrev').html(docPrev);
        $('#imagePrev').empty(); 
        var headerDocInput = $('<label>Add Documents:</label><input type="file" class="header_document form-control" name="header_document" accept=".doc, .docx, .pdf, .csv, .xlsx, .txt"><small>Supported file type:</small> <small class="text-danger">doc,docx,pdf,csv,xlxs,txt</small><br><input type="hidden" name="type" value="DOCUMENT"><input type="hidden" name="doc_example" value="'+ component.example.header_handle[0]+'">');
        $('#templateFields').append(headerDocInput);
    }else if(component.type === 'HEADER' && component.format === 'VIDEO'){
        var videoPrev = $('<video autoplay muted loop><source src="'+ component.example.header_handle[0]+'" class="card-img-top" style=""></video>');
        $('#videoPrev').html(videoPrev);
        $('#documentPrev').empty();
        $('#imagePrev').empty(); 
        
        var headerVideoInput = $('<label>Add Video:</label><input type="file" class="header_video form-control" name="header_video" accept="video/*"><small>Supported file type:</small> <small class="text-danger">mp4</small><br><input type="hidden" name="type" value="VIDEO"><input type="hidden" name="video_example" value="'+ component.example.header_handle[0]+'">');
         $('#templateFields').append(headerVideoInput);
    }
    
    else if (component.type === 'HEADER' && component.format === 'TEXT') {
        // Add input field for header text parameter
        
        
        if (component.example && component.example.header_text && component.example.header_text.length > 0) {
        var headerText = component.text.replace('{{1}}', component.example.header_text[0] );
        var headerTextInput = $('<label>Header:</label><input type="text" class="form-control header-text-parameter" required="" placeholder="'+component.example.header_text[0]+'" name="header_text_parameter">');
    }else{
        var headerText = component.text;
    }
    $('#headertext').text(component.text);
    $('#imagePrev').empty(); 
    $('#documentPrev').empty();
        $('#templateFields').append(headerTextInput);
    }
    
    
    if (component.type === 'BODY') {
        
        $('#combody').text(component.text);
        if (component.example && component.example.body_text && component.example.body_text.length > 0) {
    // Iterate through body parameters and add input fields
    var bodyTextArray = Array.isArray(component.example.body_text[0])
        ? component.example.body_text[0]
        : [component.example.body_text[0]];


    $.each(bodyTextArray, function (paramIndex, paramValue) {
        var bodyTextInput = $('<label>Body:{{'+ (paramIndex + 1) + '}}</label><input type="text" class="form-control body-text-parameter" name="body_text_parameter[]" placeholder="' + paramValue + '">');
        $('#templateFields').append(bodyTextInput);
    });
    }
}

    
    else if (component.type === 'BUTTONS') {
         $('#buttonsPrev').empty();
        // Iterate through buttons and add input fields
        $.each(component.buttons, function (buttonIndex, button) {
            if (button.type === 'QUICK_REPLY') {
                var quickReplyInput = $('<div class="card" style="min-width: 18rem; text-align: center; margin-bottom: 5px;"><div class="card-body" style="padding:1rem"><img style="height: 18px" src="/public/assets/img/reply.png"/><span class="" style="color: #00a5f4" >' + button.text + '</span></div>');
                $('#buttonsPrev').append(quickReplyInput);
            } else if (button.type === 'URL') {
                var urlButtonInput = $('<div class="card" style="min-width: 18rem; text-align: center; margin-bottom: 5px;"><div class="card-body" style="padding:1rem"><img style="height: 18px" src="/public/assets/img/open.png"/><span class="" style="color: #00a5f4" >' + button.text + '</span></div>');
                $('#buttonsPrev').append(urlButtonInput);
            } else if (button.type === 'PHONE_NUMBER') {
                var phoneNumberButtonInput = $('<div class="card" style="min-width: 18rem; text-align: center; margin-bottom: 5px;"><div class="card-body" style="padding:1rem"><span class="" style="color: #00a5f4" >' + button.phone_number + '</span></div>');
                $('#buttonsPrev').append(phoneNumberButtonInput);
            }
            // Add similar conditions for other button types if needed
        });
    }
    
    else if (component.type === 'FOOTER'){
        $('#footerPrev').text(component.text);
    }
    
    
    // Add similar conditions for other component types (e.g., VIDEO, DOCUMENT)
});



// Event listener to track changes in input values
$('#templateFields').on('change', '.header_image, .header_video, .header_document, .header-text-parameter, .body-text-parameter, .currency-code, .currency-amount, .date-time, .location-latitude, .location-longitude, .location-name, .location-address, .url-button, .phone-number-button, .quick-reply', function () {
    
    
    var existingField;
    var paramType = '';
    var paramValue = '';
    existingField = [];
    var fieldType; var ar;
    if ($(this).hasClass('header_image')) {
        fieldType = 'HEADER';
        ar = 'file_image';
    } else if($(this).hasClass('header_document')){
        fieldType = 'HEADER';
        ar = 'file_doc';
    }else if($(this).hasClass('header_video')){
        fieldType = 'HEADER';
        ar = 'file_video';
    }
    else if ($(this).hasClass('header-text-parameter')) {
        fieldType = 'HEADER';
    } else if ($(this).hasClass('body-text-parameter')) {
        fieldType = 'BODY';
    } else if ($(this).hasClass('currency-code') || $(this).hasClass('currency-amount')) {
        fieldType = 'CURRENCY';
    } else if ($(this).hasClass('date-time')) {
        fieldType = 'DATE_TIME';
    } else if ($(this).hasClass('location-latitude') || $(this).hasClass('location-longitude') || $(this).hasClass('location-name') || $(this).hasClass('location-address')) {
        fieldType = 'LOCATION';
    } else if ($(this).hasClass('url-button')) {
        fieldType = 'URL';
    } else if ($(this).hasClass('phone-number-button')) {
        fieldType = 'PHONE_NUMBER';
    } else if ($(this).hasClass('quick-reply')) {
        fieldType = 'QUICK_REPLY';
    }

    var paramName = $(this).attr('name');
    
    

    // Set paramType and paramValue based on the field type
    if (fieldType === 'HEADER' && ar === 'file_image') {
        paramType = 'image';
        paramValue = { link: $(this).val() };
    } else if (fieldType === 'HEADER' && ar === 'file_doc') {
        paramType = 'document';
        paramValue = { link: $(this).val() };
    }else if (fieldType === 'HEADER' && ar === 'file_video') {
        paramType = 'video';
        paramValue = { link: $(this).val() };
    }else if (fieldType === 'HEADER' || fieldType === 'BODY' || fieldType === 'URL' || fieldType === 'PHONE_NUMBER') {
        paramType = 'text';
        paramValue = $(this).val();
    } else if (fieldType === 'CURRENCY') {
        paramType = 'currency';
        paramValue = { code: $('#templateFields .currency-code').val(), amount_1000: $('#templateFields .currency-amount').val() };
    } else if (fieldType === 'DATE_TIME') {
        paramType = 'date_time';
        paramValue = { fallback_value: $(this).val() };
    } else if (fieldType === 'LOCATION') {
        paramType = 'location';
        paramValue = {
            latitude: $('#templateFields .location-latitude').val(),
            longitude: $('#templateFields .location-longitude').val(),
            name: $('#templateFields .location-name').val(),
            address: $('#templateFields .location-address').val()
        };
    } else if (fieldType === 'QUICK_REPLY') {
        paramType = 'payload';
        paramValue = $(this).val();
    }
    
    

    // Update the stored parameters array
    
    var existingParam = updatedParameters.find(param => param.type === fieldType);
    
    if (existingParam) {
        // Update existing parameter
        if (fieldType === 'QUICK_REPLY' || fieldType === 'URL' || fieldType === 'PHONE_NUMBER') {
            existingParam.parameters.push({ type: paramType, payload: paramValue });
        } else {
            var existingField = existingParam.parameters.find(field => field.name === paramName);
           // existingField = [];
            if (existingField) {
                existingField.value = paramValue;
            } else {
                
    
                existingParam.parameters.push({ type: paramType, [paramType]: paramValue });
                
                
            }
        }
    } else {
        // Add new parameter
        if (fieldType === 'QUICK_REPLY' || fieldType === 'URL' || fieldType === 'PHONE_NUMBER') {
            updatedParameters.push({ type: fieldType, parameters: [{ type: paramType, payload: paramValue }] });
        } else {
            updatedParameters.push({ type: fieldType, parameters: [{ type: paramType, [paramType]: paramValue }] });
        }
    }
    

    // Update the hidden input field with the JSON representation of updatedParameters
    
    $('#updatedParametersInput').val(JSON.stringify(updatedParameters));
    
});
        },
        error: function (error) {
            console.error('Error:', error);
        }
    });
});




// Function to add input fields for parameters in message textarea
function addMessageParameterInputFields() {
	var messageTextarea = document.getElementById('message');
	var messageValue = messageTextarea.value;
	var matches = messageValue.match(/\{\d+}/g);
	var parameterFieldsContainer = document.getElementById('parameter_fields');

	// Clear previous parameter input fields
	parameterFieldsContainer.innerHTML = '';

	if (matches && matches.length > 0) {
		for (var i = 0; i < matches.length; i++) {
			var parameterInput = document.createElement('input');
			parameterInput.type = 'text';
			parameterInput.name = 'message_parameter_' + (i + 1); // Unique name for message parameter input
			parameterInput.id = 'parameter_input_message_' + (i + 1); // Unique id for parameter input
			parameterInput.placeholder = 'Parameter ' + (i + 1);
			parameterInput.classList.add('form-control');
			parameterFieldsContainer.appendChild(parameterInput);
		}

		// Disable further editing of the textarea
		messageTextarea.value = messageValue;
		//messageTextarea.disabled = true;
	} else {
		// Enable the textarea if there are no loaded messages
		messageTextarea.disabled = false;
	}
}

// Event listener for message textarea
var messageTextarea = document.getElementById('message');
messageTextarea.addEventListener('input', addMessageParameterInputFields);
