"use strict";

const cloudapi_id = $('#uuid').val();
const base_url = $('#base_url').val();
const whatsappicon = base_url + '/assets/img/whatsapp.png';


function formatTimestamp2(timestamp) {
  var date = new Date(timestamp);
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // Handle midnight (0 hours)
  var formattedTime = hours + ':' + (minutes < 10 ? '0' : '') + minutes + ' ' + ampm;
  return formattedTime;
}

function updateChatMessages(messageHistoryData) {
  var messageHistoryContainer = $('.chat');
  var currentDate = new Date();
  var currentDay = currentDate.toLocaleString('en-US', { weekday: 'long' });
  var currentYear = currentDate.getFullYear();
  var currentMonth = currentDate.toLocaleString('en-US', { month: 'long' });
var currentDayOfMonth = currentDate.getDate();
  messageHistoryContainer.empty();
  var lastMessageDay = '';
  $.each(messageHistoryData, function(index, message) {
    var messageDate = new Date(message.timestamp);
    var messageDay = messageDate.toLocaleString('en-US', { weekday: 'long' });
    var messageYear = messageDate.getFullYear();
    var messageMonth = messageDate.toLocaleString('en-US', { month: 'long' });
  var messageDayOfMonth = messageDate.getDate();
    if (messageDay !== lastMessageDay) {
      if (messageDay === currentDay && 
        messageYear === currentYear &&
        messageMonth === currentMonth &&
        messageDayOfMonth === currentDayOfMonth ) {
        var conversationStartHTML = '<div class="conversation-start">';
        conversationStartHTML += '<span>Today, ' + formatTimestamp2(message.timestamp) + '</span>';
        conversationStartHTML += '</div>';
        messageHistoryContainer.append(conversationStartHTML);
      } else {
        var conversationStartHTML = '<div class="conversation-start">';
        conversationStartHTML += '<span>' + messageDayOfMonth  + ' ' + messageMonth + ', ' + formatTimestamp2(message.timestamp) + '</span>';
        conversationStartHTML += '</div>';
        messageHistoryContainer.append(conversationStartHTML);
      }
      lastMessageDay = messageDay;
    }
    var messageClass = message.type === 'received' ? 'you' : 'me';
    var html = '<div class="bubble ' + messageClass + '">';
    
    if (message.status === 'sent') {
    html += '<i class="fa fa-check-circle" title="sent" style="color:orange;position: absolute;left: -8%;bottom: 33%;"></i>';
} else if (message.status === 'delivered') {
    html += '<i class="fa fa-check-circle" title="delivered" style="color:blue;position: absolute;left: -8%;bottom: 33%;"></i>';
} else if (message.status === 'read') {
    html += '<i class="fa fa-check-circle" title="read" style="color:green; position: absolute;left: -8%;bottom: 33%;"></i>';
}
    
    // Check if the message is an image URL
    if (isImageUrl(message.message)) {
var match = message.message.match(/(https?:\/\/\S+)\s*\\n\s*Caption:\s*(.+)/); // Match image URL and caption
  if (!match) {
    // If the initial match failed, try with a single backslash for newline
    match = message.message.match(/(https?:\/\/\S+)\s*\n\s*Caption:\s*(.+)/);
  }

  if (match && match.length === 3) {
    var imageUrl = match[1];
    var caption = match[2];
    html += '<div class="image-container">' +
              '<img style="width:250px;" src="' + imageUrl + '" />' +
              '<p>' + caption + '</p>' +
            '</div>';
  } else {
    // No caption found, display image only
    html += '<img style="width:250px;" src="' + message.message + '" />';
  }
    } else if (isAudioUrl(message.message)) {
      html += '<audio controls><source src="' + message.message + '" type="audio/mpeg"></audio>';
    } else if (isVideoUrl(message.message)) {
      html += '<video style="width: -webkit-fill-available;" controls><source src="' + message.message + '" type="video/mp4"></video>';
    } else if (isPdfOrDocUrl(message.message)) {
        var match = message.message.match(/(https?:\/\/\S+)\s*\\n\s*Caption:\s*(.+)/); 
        if (!match) {
    // If the initial match failed, try with a single backslash for newline
    match = message.message.match(/(https?:\/\/\S+)\s*\n\s*Caption:\s*(.+)/);
    }
    if (match && match.length === 3) {
    var pdfUrl = match[1];
    var caption = match[2];
    html += '<div class="pdf-container">' +
              '<a href="'+pdfUrl+'"><img style="width:250px;" src="../../../assets/img/pdf.png" /></a>' +
              '<p>' + caption + '</p>' +
            '</div>';
  } else {
    
      html += '<a href="' + message.message + '" target="_blank">View Document</a>';
    }
        
    } else {
      html += message.message; // Display text message if not recognized
    }
    
    html += '<span class="armish">'+formatTimestamp2(message.timestamp)+'</span></div>';
    
    messageHistoryContainer.append(html);
  });
  var lastMessage = messageHistoryContainer.find('.bubble:last')[0];
if (lastMessage) {
  lastMessage.scrollIntoView();
}


  // Additional code to handle styling and showing the chat
  messageHistoryContainer.addClass('archat');
  messageHistoryContainer.addClass('active-chat');
  messageHistoryContainer.parents('.chat-system').find('.chat-box .chat-not-selected').hide();
}

function isImageUrl(url) {
  var imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.svg', '.webp'];
  var lowerCaseUrl = url.toLowerCase();
  
  var lines = lowerCaseUrl.split(/\\n|\n|\r\n|\r/);
    var ar = lines.length > 0 ? lines[0] : '';

  var isImage = imageExtensions.some(function(extension) {
    return ar.endsWith(extension);
  });
  
  if (isImage) {
  var hasCaption = /https?:\/\/\S+(\\n|\n)Caption:\s*.+/.test(url);
   return isImage|| hasCaption;
  }
}

function isAudioUrl(url) {
  var audioExtensions = ['.mp3', '.ogg', '.amr', '.wav', '.aac'];
  var lowerCaseUrl = url.toLowerCase();
  
  var lines = lowerCaseUrl.split(/\\n|\n|\r\n|\r/);
    var ar = lines.length > 0 ? lines[0] : '';

  var isAudio = audioExtensions.some(function(extension) {
    return ar.endsWith(extension);
  });

  if (isAudio) {
  var hasCaption = /https?:\/\/\S+(\\n|\n)Caption:\s*.+/.test(url);
   return isAudio || hasCaption;
  }
}

function isVideoUrl(url) {
  var videoExtensions = ['.mp4', '.avi', '.mov', '.mkv'];
  var lowerCaseUrl = url.toLowerCase();
  
  var lines = lowerCaseUrl.split(/\\n|\n|\r\n|\r/);
    var ar = lines.length > 0 ? lines[0] : '';

  var isVideo = videoExtensions.some(function(extension) {
    return ar.endsWith(extension);
  });

  if (isVideo) {
  var hasCaption = /https?:\/\/\S+(\\n|\n)Caption:\s*.+/.test(url);
   return isVideo || hasCaption;
  }

}

function isPdfOrDocUrl(url) {
  var docExtensions = ['.pdf', '.docx', '.doc', '.xls', '.xlsx', '.csv'];
  var lowerCaseUrl = url.toLowerCase();
  
  var lines = lowerCaseUrl.split(/\\n|\n|\r\n|\r/);
    var ar = lines.length > 0 ? lines[0] : '';

  var isDocument = docExtensions.some(function(extension) {
    return ar.endsWith(extension);
  });

  if (isDocument) {
  var hasCaption = /https?:\/\/\S+(\\n|\n)Caption:\s*.+/.test(url);
   return isDocument || hasCaption;
  }
  
  
}

var currentPhoneNumber;
  var page = 1;
function getChatList() {
  // Function to fetch chat messages
  var lastUpdateTimestamp = 0;
  var lastReceivedTimestamp = 0;

function updatePreview(key, newMessage, timestamp) {
  const previewSpan = $(`[data-chat="person${key}"] .preview`);
  const timePrev = $(`[data-chat="person${key}"] .user-meta-time`);
  // Update the preview message
  var lastTimestamp = formatTimestamp2(timestamp);
  previewSpan.text(newMessage);
  timePrev.text(lastTimestamp);
}

function fetchChatMessages() {
    $.ajax({
      type: 'GET',
      url: base_url + '/user/get-chats/' + cloudapi_id,
      dataType: 'json',
      data: {
            last_received_timestamp: lastReceivedTimestamp,
            page: page
        },
      success: function(response) {
        const chats = sortByKey(response, 'updated_at');
        $.each(chats, function(index, item) {
          var key = item.id;
          var lastMessage = item.lastmessage;
          var lastTimestamp = formatTimestamp2(lastMessage.timestamp);
          lastReceivedTimestamp = lastMessage.timestamp;
          if (item.timestamp > 0) {
            var time = formatTimestamp2(item.timestamp);
            time = `<span class="text-success">${time}</span>`;
          } else {
            var time = '';
          }
          var nameOrPhoneNumber = item.name !== null ? item.name : item.phone_number;
          var TagLabel = item.follow_up ?? 'New';
          var html = `<div class="person" data-chat="person${key}">
            <div class="user-info">
              <div class="f-head">
                <img src="${whatsappicon}" alt="avatar">
              </div>
              <div class="f-body">
                <div class="meta-info">
                  <span class="user-name" data-name="${item.phone_number}" data-number="${item.phone_number}">${nameOrPhoneNumber}</span>
                  <span class="user-meta-time">${lastTimestamp}</span>
                  <span class="taglabel" data-taglabel="${TagLabel}"></span>
                </div>
                <span class="preview">${lastMessage.message}</span>
              </div>
              ${time}
            </div>
          </div>`;
          var messageHistoryContainer = $(`[data-chat="person${key}"]`)
          var messageList = $(`[data-chat="person${key}"].person`);
        if ((item.updated_at && new Date(item.updated_at).getTime() > lastUpdateTimestamp)) {
          updatePreview(key, lastMessage.message, lastMessage.timestamp);
          if (messageHistoryContainer.hasClass('active-chat')) {
            fetchmessage(item.phone_number);
          }
          if (item.updated_at && new Date(item.updated_at).getTime() > lastUpdateTimestamp) {
            lastUpdateTimestamp = new Date(item.updated_at).getTime();
          }
          if (messageList.length > 0) {
    messageList.remove();
        }
          $('.people').prepend(html);
          var flashColor = '#7fffd436';
$('.person:first').css('background', flashColor)
    .delay(500)
    .queue(function (next) {
        $(this).css('background', '');
        next();
    });
          
        }
        
        if ($(`[data-chat="person${key}"]`).length === 0) {
            $('.people').append(html);
          }
        
        });
        
        $('.chat-box-inner').css('height', '100%');
        $('.search > input').on('keyup', function() {
          var rex = new RegExp($(this).val(), 'i');
            $('.people .person').hide();
            $('.people .person').filter(function() {
                return rex.test($(this).text());
            }).show();
        });
        $('.user-list-box .person').on('click', function(event) {
            
            
            if ($(this).hasClass('.active')) {
                 const ps = new PerfectScrollbar('.chat-conversation-box', {
            suppressScrollX : true
          });
        
          const getScrollContainer = document.querySelector('.chat-conversation-box');
          getScrollContainer.scrollTop = 0;
                return false;
            } else {
                var findChat = $(this).attr('data-chat');
                var personName = $(this).find('.user-name').text();
                var number = $(this).find('.user-name').data('number');
                var tag = $(this).find('.taglabel').data('taglabel');
                updateLabelUI(tag);
                currentPhoneNumber = number;
                var personImage = $(this).find('img').attr('src');
                var hideTheNonSelectedContent = $(this).parents('.chat-system').find('.chat-box .chat-not-selected').hide();
                var showChatInnerContent = $(this).parents('.chat-system').find('.chat-box .chat-box-inner').show();
                if (window.innerWidth <= 767) {
                   $('.chat-box .current-chat-user-name .name').html(personName.split(' ')[0] + ' (' + number + ')');
                } else if (window.innerWidth > 767) {
                  $('.chat-box .current-chat-user-name .name').html(personName + ' (' + number + ')');
                }
                $('.chat-box .current-chat-user-name img').attr('src', personImage);
                $('.chat').removeClass('active-chat');
                $('.user-list-box .person').removeClass('active');
                $('.chat-box .chat-box-inner').css('height', '100%');
                $(this).addClass('active');
                $('.chat[data-chat = '+findChat+']').addClass('active-chat');
            }
            if ($(this).parents('.user-list-box').hasClass('user-list-box-show')) {
              $(this).parents('.user-list-box').removeClass('user-list-box-show');
            }
            $('.chat-meta-user').addClass('chat-active');
            //$('.chat-box').css('height', 'calc(100vh - 100px)');
            $('.chat-footer').addClass('chat-active');
        
         
        });
        function callOnConnect() {
          var getCallStatusText = $('.overlay-phone-call .call-status');
          var getCallTimer = $('.overlay-phone-call .timer');
          var setCallStatusText = getCallStatusText.text('IVR Features are coming soon....');
          var setCallTimerDiv = getCallTimer.css('visibility', 'visible');
        }
        $('.hamburger, .chat-system .chat-box .chat-not-selected p').on('click', function(event) {
          $(this).parents('.chat-system').find('.user-list-box').toggleClass('user-list-box-show');
        });
        
      },
      
      error: function(xhr, status, error) {
            // Handle errors if needed
            console.error("Error fetching chat messages:", error);
        },
      complete: function() {
            // Initiate the next long poll after processing the current response
            fetchChatMessages();
        },
        timeout: 30000,
    });
  }

  // Call the fetchChatMessages function initially
  fetchChatMessages();
}

$('.hamburger2').on('click', function(event) {
    // Toggle the visibility of .navbar-top
    $('.main-content .navbar-top').toggle();
        $('.col-xl-12, .col-lg-12, .col-md-12').css({ 'padding-left': 0, 'padding-right': 0 });
        $('.chat-box').css('height', 'calc(-100px + 109vh)');
        $('.feather-phone').css('display','none');
        $('.feather-video').css('display','none');
        

    // Prevent the default behavior (e.g., following a link)
    event.preventDefault();
});


function downloadMessageHistory(messageHistoryData) {
  // Check if messageHistoryData is defined
  if (messageHistoryData) {
    // Convert the message history data to JSON
    const jsonData = JSON.stringify(messageHistoryData, null, 2); // null, 2 adds indentation for better readability

    // Create a Blob (Binary Large Object) from the JSON data
    const blob = new Blob([jsonData], { type: 'application/json' });

    // Create a download link
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);

    // Set the filename for the downloaded file
    link.download = 'message_history.json';

    // Append the link to the document
    document.body.appendChild(link);

    // Trigger a click on the link to start the download
    link.click();

    // Remove the link from the document
    document.body.removeChild(link);
  } else {
    console.error('No message history data available.');
  }
}
// Attach the download function to the button click event



$(document).ready(function() {
  // Flag to check if AJAX request is in progress
  var isSendingMessage = false;

  // Function to handle message form submission
  function sendMessage(event) {
    event.preventDefault(); // Prevent the default form submission
    $('.loading-input').show();

    // Check if the previous AJAX request is still in progress
    if (isSendingMessage) {
      return;
    }

    // Retrieve the form values
    var receiver = $('#receiver').val();
    var messageInput = $('input[name="message"]');
    var fileInput = $('#fileInput')[0].files[0];
    
    // Disable the input field during the AJAX request
    messageInput.prop('readonly', true);

    var formData = new FormData();
    formData.append('receiver', receiver);
    formData.append('message', messageInput.val());
    formData.append('fileInput', fileInput);

    // Set the flag to indicate that the AJAX request is in progress
    isSendingMessage = true;

    // Perform the AJAX request
    $.ajax({
      url: base_url + '/user/send-message/' + cloudapi_id,
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        // Handle the success response
        console.log('Message sent successfully');
        // Reset the message input field
        messageInput.val('');
        $('#fileInput').val('');
        $('.art').removeClass('toggled');
        
        resetFilePreview();
      },
      error: function(xhr, status, error) {
        // Handle the error response
        console.error(error);
      },
      complete: function() {
        // Set the flag to indicate that the AJAX request is complete
        isSendingMessage = false;
        // Enable the input field after the request is complete
        messageInput.prop('readonly', false);
         $('.loading-input').hide();
      }
    });
  }

  // Attach event listener to the message form
  $('#chatForm').on('submit', sendMessage);
});


var downloadHandler;
$(document).on('click', '.person', function() {
    var preview = $(this).find('.preview');
    preview.css('color', '#888ea8');
  var chatId = $(this).data('chat');
  var phone = $(this).find('.user-name').data('number');
  var messageHistoryContainer = $('.chat');
  messageHistoryContainer.attr('data-chat', chatId);
  fetchmessage(phone)
  $('.receiver-number').val(phone);
  
  if (downloadHandler) {
    document.getElementById('downloadButton').removeEventListener('click', downloadHandler);
  }
  downloadHandler = function() {
    downloadMessageHistory(messageHistoryData);
  };
  document.getElementById('downloadButton').addEventListener('click', downloadHandler);
});

// Call the getChatList function initially
getChatList();

function sortByKey(array, key) {
	return array.sort(function(a, b) {
		var x = a[key]; var y = b[key];
		return ((x > y) ? -1 : ((x < y) ? 1 : 0));
	});
}

$('.label-group-item').on('click', function() {
    var tag = $(this).data('taglabel');
    var phone = $(this).find('.user-name').data('number');
    updateLabel(tag,currentPhoneNumber);
});

function updateLabel(tag, phone) {
    
    $.ajax({
        url: base_url + '/user/update-label/' + cloudapi_id,
        type: 'POST',
        data: { tag: tag,phone:phone},
        success: function(response) {
            updateLabelUI(tag);
        },
        error: function(xhr) {
            console.log(xhr.responseJSON.message);
        }
    });
}

function fetchmessage(phone) {
    $('.loading').show();
    $.ajax({
        type: 'GET',
        url: base_url + '/user/get-message/' + phone + '/' + cloudapi_id,
        dataType: 'json',
        success: function(response) {
            var messages = response.message[0]; // Extract the messages array
            updateChatMessages(messages);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching messages:", error);
        },
        complete: function() {
            $('.loading').hide();
        }
    });
}


function updateLabelUI(tag) {
    var label = $('#TagLabel');
    switch (tag) {
        case 1:
            label.text('New').css({ background: '#000', color: '#fff' });
            break;
        case 2:
            label.text('Pending').css({ background: 'orange', color: '#fff' });
            break;
        case 3:
            label.text('Positive').css({ background: 'blue', color: '#fff' });
            break;
        case 4:
            label.text('Negative').css({ background: 'red', color: '#fff' });
            break;
        case 5:
            label.text('Converted').css({ background: 'green', color: '#fff' });
            break;
        default :
            label.text('Add Label').css({ background: 'grey', color: '#fff' });
            break;
    }
}

function getTimeValue(timeString) {
        const [time, period] = timeString.split(' ');
        const [hours, minutes] = time.split(':');
        return (period === 'AM' ? 0 : 12) + parseInt(hours, 10) + parseInt(minutes, 10) / 60;
    }

function resetFilePreview() {
  imageOption.src = '../../../assets/img/attachment.png'; 
  videoOption.src = '../../../assets/img/img-vid.png';
  audioOption.src = '../../../assets/img/voice.png'; 
}


function sendPageNumberToController() {
    $.ajax({
        type: 'GET',
        url: base_url + '/user/get-chats/' + cloudapi_id,
        data: {
            page: page
        },
        success: function (response) {
        },
        error: function (xhr, status, error) {
            console.error("Error sending page number:", error);
        }
    });
}

var lastScrollTop = 0;
var resumepage = 1;

$('.people').scroll(function () {
    var container = $(this);
    var st = container.scrollTop();

    if (st < lastScrollTop) {
        page = 1; 
    } else if (st + container.outerHeight() === container[0].scrollHeight) {
        if (page >= resumepage) {
             resumepage = page;
             
            page++;
        }else{
            page = resumepage + 1;
            page++;
            resumepage = page-1;
        }
    }
    sendPageNumberToController();

    lastScrollTop = st;
});
