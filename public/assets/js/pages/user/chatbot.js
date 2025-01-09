"use strict";

$('.reply_type').on('change',function(){
		
		if ($(this).val() == 'text') {
			$('.text-area').show();
			$('.templates').hide();
			$('.header-parameters').hide();
			$('.message-parameters').hide();
		}
		else{
			$('.text-area').hide();
			$('.templates').show();
			$('.header-parameters').show();
			$('.message-parameters').show();
		}
});

$('.edit-reply').on('click',function(){

		const action = $(this).data('action');
		const templateid = $(this).data('templateid');
		const reply = $(this).data('reply');
		const matchtype = $(this).data('matchtype');
		const replytype = $(this).data('replytype');
		const keyword = $(this).data('keyword');
		const cloudapi = $(this).data('device');

		$('.edit-reply-form').attr('action',action);
		$('#templateid').val(templateid);
		$('#reply').val(reply);
		$('#matchtype').val(matchtype);
		$('#replytype').val(replytype);
		$('#keyword').val(keyword);
		$('#device').val(cloudapi);

		if (replytype == 'text') {
			$('#reply-area').show();
			$('#templates-area').hide();

		}
		else{
			$('#reply-area').hide();
			$('#templates-area').show();
		}

});

function updateInputFields(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var templateData = JSON.parse(selectedOption.getAttribute("data-template-raw"));
        

        // Clear existing input fields
        document.getElementById('header-variable').innerHTML = '';
        document.getElementById('body-variable').innerHTML = '';
        document.getElementById('imagePrev').innerHTML = ''; 
        document.getElementById('videoPrev').innerHTML = ''; 
        document.getElementById('imagePrev').innerHTML = '';
        document.getElementById('headertext').innerHTML = '';
        document.getElementById('combody').innerHTML = '';

        // Generate input fields for header parameters
        templateData.components.forEach(function (component) {
            if (component.type === "HEADER" && component.format === "IMAGE") {
                var imagePrev = document.createElement('img');
    imagePrev.src = component.example.header_handle[0];
    imagePrev.classList.add('card-img-top');
    imagePrev.style = ""; 
    
    document.getElementById('imagePrev').appendChild(imagePrev);
                
                document.getElementById('header-variable').innerHTML += '<label>Header:</label><input type="file" class="form-control" name="header_image" accept="image/*">';
                    }else if (component.type === "HEADER" && component.format === "VIDEO") {
            // Create video preview element
            var videoPrev = document.createElement('video');
            videoPrev.src = component.example.header_handle[0];
            videoPrev.classList.add('card-img-top');
            videoPrev.controls = true;
            videoPrev.style = ""; 
            document.getElementById('videoPrev').appendChild(videoPrev);
        
            // Display media uploader tag for video
            document.getElementById('header-variable').innerHTML += '<label>Header:</label><input type="file" class="form-control" name="header_video" accept="video/*"><br><input type="hidden" name="type" value="VIDEO"><input type="hidden" name="video_example" value="'+ component.example.header_handle[0] +'">';
        }else if (component.type === 'HEADER' && component.format === 'DOCUMENT'){
                var docPrev = document.createElement('img');
                docPrev.src = 'http://localhost/uploads/default/wpbox/pdf.png';
                docPrev.alt = 'Card image cap';
                docPrev.style = '';
                docPrev.classList.add('card-img-top');
                document.getElementById('documentPrev').appendChild(docPrev);
                document.getElementById('header-variable').innerHTML += '<label>Header:</label><input type="file" class="form-control" name="header_image" accept=".doc, .docx, .pdf, .csv, .xlsx, .txt"><small>Supported file type:</small> <small class="text-danger">doc,docx,pdf,csv,xlxs,txt</small>';
            } else if (component.type === "HEADER" && component.format === "TEXT" && component.example && component.example.header_text && component.example.header_text[0]) {
                console.log(component.example.header_text[0]);
                document.getElementById('headertext').append(component.text);
                // Display input tag for text
                document.getElementById('header-variable').innerHTML += '<label>Header:</label><input type="text" class="form-control" name="header_param" placeholder="'+component.example.header_text[0]+'">';
            }
            
            if(component.type==="BODY"){
                document.getElementById('combody').append(component.text);
            }
            
            if (component.type === "BODY" && component.example && component.example.body_text && component.example.body_text[0]) {
    // Display input tags for body text array
    component.example.body_text[0].forEach(function (bodyText, index) {
        var inputField = '<label>Body Parameter ' + (index + 1) + ':</label><input type="text" class="form-control" name="body_param[]" placeholder="' + bodyText + '"><br>';
        document.getElementById('body-variable').insertAdjacentHTML('beforeend', inputField);
    });
}
        });
    }
    
function updateInputFieldsDefault(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var templateData = JSON.parse(selectedOption.getAttribute("data-template-raw"));
        

        // Clear existing input fields
        document.getElementById('header-variable1').innerHTML = '';
        document.getElementById('body-variable1').innerHTML = '';
        document.getElementById('imagePrev1').innerHTML = ''; 
        document.getElementById('videoPrev1').innerHTML = ''; 
        document.getElementById('headertext1').innerHTML = '';
        document.getElementById('combody1').innerHTML = '';

        // Generate input fields for header parameters
        templateData.components.forEach(function (component) {
            if (component.type === "HEADER" && component.format === "IMAGE") {
                var imagePrev = document.createElement('img');
    imagePrev.src = component.example.header_handle[0];
    imagePrev.classList.add('card-img-top');
    imagePrev.style = ""; // Set your styles here
    
    document.getElementById('imagePrev1').appendChild(imagePrev);
                // Display media uploader tag for image
                document.getElementById('header-variable').innerHTML += '<label>Header:</label><input type="file" class="form-control" name="header_image" accept="image/*">';
            }else if (component.type === "HEADER" && component.format === "VIDEO") {
    // Create video preview element
    var videoPrev = document.createElement('video');
    videoPrev.src = component.example.header_handle[0];
    videoPrev.classList.add('card-img-top');
    videoPrev.controls = true; // Enable video controls
    videoPrev.style = ""; // Set your styles here if any

    // Append the video preview element to the videoPrev container
    document.getElementById('videoPrev1').appendChild(videoPrev);

    // Display media uploader tag for video
    document.getElementById('header-variable').innerHTML += '<label>Header:</label><input type="file" class="form-control" name="header_video" accept="video/*"><br><input type="hidden" name="type" value="VIDEO"><input type="hidden" name="video_example" value="'+ component.example.header_handle[0] +'">';
}else if (component.type === 'HEADER' && component.format === 'DOCUMENT'){
                var docPrev = document.createElement('img');
                docPrev.src = 'http://localhost/uploads/default/wpbox/pdf.png';
                docPrev.alt = 'Card image cap';
                docPrev.style = '';
                docPrev.classList.add('card-img-top');
                document.getElementById('documentPrev1').appendChild(docPrev);
                document.getElementById('header-variable1').innerHTML += '<label>Header:</label><input type="file" class="form-control" name="header_image" accept=".doc, .docx, .pdf, .csv, .xlsx, .txt"><small>Supported file type:</small> <small class="text-danger">doc,docx,pdf,csv,xlxs,txt</small>';
            } else if (component.type === "HEADER" && component.format === "TEXT" && component.example && component.example.header_text && component.example.header_text[0]) {
                document.getElementById('headertext1').append(component.text);
                // Display input tag for text
                document.getElementById('header-variable1').innerHTML += '<label>Header:</label><input type="text" class="form-control" name="header_param" placeholder="'+component.example.header_text[0]+'">';
            }
            
            if(component.type==="BODY"){
                document.getElementById('combody1').append(component.text);
            }
            
            if (component.type === "BODY" && component.example && component.example.body_text && component.example.body_text[0]) {
    component.example.body_text[0].forEach(function (bodyText, index) {
        var inputField = '<label>Body Parameter ' + (index + 1) + ':</label><input type="text" class="form-control" name="body_param[]" placeholder="' + bodyText + '"><br>';
        document.getElementById('body-variable1').insertAdjacentHTML('beforeend', inputField);
    });
}
        });
    }
    
    function updateInputFieldsEdit(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var templateData = JSON.parse(selectedOption.getAttribute("data-template-raw"));
        

        // Clear existing input fields
        document.getElementById('header-variable2').innerHTML = '';
        document.getElementById('body-variable2').innerHTML = '';
        document.getElementById('imagePrev2').innerHTML = ''; 
        document.getElementById('videoPrev2').innerHTML = '';
        document.getElementById('headertext2').innerHTML = '';
        document.getElementById('combody2').innerHTML = '';

        // Generate input fields for header parameters
        templateData.components.forEach(function (component) {
            if (component.type === "HEADER" && component.format === "IMAGE") {
                var imagePrev = document.createElement('img');
    imagePrev.src = component.example.header_handle[0];
    imagePrev.classList.add('card-img-top');
    imagePrev.style = ""; // Set your styles here
    
    document.getElementById('imagePrev2').appendChild(imagePrev);
                // Display media uploader tag for image
                document.getElementById('header-variable2').innerHTML += '<label>Header:</label><input type="file" class="form-control" name="header_image" accept="image/*">';
            }else if (component.type === "HEADER" && component.format === "VIDEO") {
            // Create video preview element
            var videoPrev = document.createElement('video');
            videoPrev.src = component.example.header_handle[0];
            videoPrev.classList.add('card-img-top');
            videoPrev.controls = true;
            videoPrev.style = ""; 
            document.getElementById('videoPrev2').appendChild(videoPrev);
        
            // Display media uploader tag for video
            document.getElementById('header-variable').innerHTML += '<label>Header:</label><input type="file" class="form-control" name="header_video" accept="video/*"><br><input type="hidden" name="type" value="VIDEO"><input type="hidden" name="video_example" value="'+ component.example.header_handle[0] +'">';
        }else if (component.type === 'HEADER' && component.format === 'DOCUMENT'){
                var docPrev = document.createElement('img');
                docPrev.src = 'http://localhost/uploads/default/wpbox/pdf.png';
                docPrev.alt = 'Card image cap';
                docPrev.style = '';
                docPrev.classList.add('card-img-top');
                document.getElementById('documentPrev2').appendChild(docPrev);
                document.getElementById('header-variable2').innerHTML += '<label>Header:</label><input type="file" class="form-control" name="header_image" accept=".doc, .docx, .pdf, .csv, .xlsx, .txt"><small>Supported file type:</small> <small class="text-danger">doc,docx,pdf,csv,xlxs,txt</small>';
            } else if (component.type === "HEADER" && component.format === "TEXT" && component.example && component.example.header_text && component.example.header_text[0]) {
                console.log(component.example.header_text[0]);
                document.getElementById('headertext2').append(component.text);
                // Display input tag for text
                document.getElementById('header-variable2').innerHTML += '<label>Header:</label><input type="text" class="form-control" name="header_param" placeholder="'+component.example.header_text[0]+'">';
            }
            
            if(component.type==="BODY"){
                document.getElementById('combody2').append(component.text);
            }
            
            if (component.type === "BODY" && component.example && component.example.body_text && component.example.body_text[0]) {
    // Display input tags for body text array
    component.example.body_text[0].forEach(function (bodyText, index) {
        var inputField = '<label>Body Parameter ' + (index + 1) + ':</label><input type="text" class="form-control" name="body_param[]" placeholder="' + bodyText + '"><br>';
        document.getElementById('body-variable2').insertAdjacentHTML('beforeend', inputField);
    });
}
        });
    }
