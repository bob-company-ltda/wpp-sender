"use strict";

$('.select2').select2();
$('.save-template').on('change',function(){
   if ($(this).is(':checked')) {
       $('.receivers').hide();
       $('.bulk_send_form').addClass('wa_instant_reload');
       $('.bulk_send_form').removeClass('ajaxform');
   }else{

       $('.bulk_send_form').removeClass('wa_instant_reload');
       $('.bulk_send_form').addClass('ajaxform');
       $('.receivers').show()
   }  

});



$(document).on('submit', '.wa_instant_reload', function (e) {
    e.preventDefault();

    let $this = $(this);
    let $submitBtn = $this.find('.submit-btn');
    let $oldSubmitBtn = $submitBtn.html();
    
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $submitBtn.html($savingLoader).addClass('disabled').attr('disabled', true);
            },
            success: function (res) {
                $submitBtn.html($oldSubmitBtn).removeClass('disabled').attr('disabled', false);
                window.sessionStorage.hasPreviousMessage = true;
                window.sessionStorage.previousMessage = res.message ?? null;

                if (res.redirect) {
                    location.href = res.redirect;
                }
            },
            error: function (xhr) {
                $submitBtn.html($oldSubmitBtn).removeClass('disabled').attr('disabled', false);
                NotifyAlert('error', xhr.responseJSON);
                showInputErrors(xhr.responseJSON);
            }
        });
    
});

function updateInputFields(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var templateData = JSON.parse(selectedOption.getAttribute("data-template-raw"));
        

        // Clear existing input fields
        document.getElementById('header-variable').innerHTML = '';
        document.getElementById('body-variable').innerHTML = '';
        document.getElementById('imagePrev').innerHTML = ''; 
        document.getElementById('imagePrev').innerHTML = '';
        document.getElementById('videoPrev').innerHTML = '';
        document.getElementById('headertext').innerHTML = '';
        document.getElementById('combody').innerHTML = '';

        // Generate input fields for header parameters
        templateData.components.forEach(function (component) {
            if (component.type === "HEADER" && component.format === "IMAGE") {
                var imagePrev = document.createElement('img');
                imagePrev.src = component.example.header_handle[0];
                imagePrev.classList.add('card-img-top');
                imagePrev.style = ""; // Set your styles here
    
                document.getElementById('imagePrev').appendChild(imagePrev);
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
               
                document.getElementById('headertext').append(component.text);
                // Display input tag for text
                document.getElementById('header-variable').innerHTML += '<label>Header:</label><input type="text" class="form-control" name="header_param" placeholder="'+component.example.header_text[0]+'"><small>Add {param1},{param2},...., If you have set the parameters in Contacts</small>';
            }
            
            if(component.type==="BODY"){
                
                document.getElementById('combody').append(component.text);
            }
            if (component.type === "BODY" && component.example && component.example.body_text && component.example.body_text[0]) {
                
    // Display input tags for body text array
    component.example.body_text[0].forEach(function (bodyText, index) {
        var inputField = '<label>Body Parameter ' + (index + 1) + ':</label><input type="text" class="form-control" name="body_param[]" placeholder="' + bodyText + '"><small>Add {param1},{param2},...., If you have set the parameters in Contacts</small><br>';
        document.getElementById('body-variable').insertAdjacentHTML('beforeend', inputField);
    });
}
        });
    }