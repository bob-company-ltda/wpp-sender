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
  var messageHistoryContainer = $('.chatting-container');
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
        var conversationStartHTML = '<div class="chatting-time">';
        conversationStartHTML += '<span>Today, ' + formatTimestamp2(message.timestamp) + '</span>';
        conversationStartHTML += '</div>';
        messageHistoryContainer.append(conversationStartHTML);
      } else {
        var conversationStartHTML = '<div class="chatting-time">';
        conversationStartHTML += '<span>' + messageDayOfMonth  + ' ' + messageMonth + ', ' + formatTimestamp2(message.timestamp) + '</span>';
        conversationStartHTML += '</div>';
        messageHistoryContainer.append(conversationStartHTML);
      }
      lastMessageDay = messageDay;
    }
    var messageClass = message.type === 'received' ? 'other-chat-container' : 'own-chat-container';
    var messageClass2 = message.type === 'received' ? 'other-chat' : 'own-chat';
    var messageClass3= message.type === 'received' ? 'other-time' : 'own-time';
    var html = '<div class="bubble ' + messageClass + '">';
    var html = '<div class="' + messageClass + '"><div class="' + messageClass2 + '">'
    
    
    if (message.status === 'sent') {
    html += '<i class="fa fa-check-circle" title="sent" style="color:orange;position: relative;left: -3%; font-size:12px; bottom: 33%;"></i>';
} else if (message.status === 'delivered') {
    html += '<i class="fa fa-check-circle" title="delivered" style="color:blue;position: relative;left: -3%; font-size:12px; bottom: 33%;"></i>';
} else if (message.status === 'read') {
    html += '<i class="fa fa-check-circle" title="read" style="color:green; position: relative;left: -3%; font-size:12px; bottom: 33%;"></i>';
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
      html += '<p>'+message.message+'</p>'; // Display text message if not recognized
    }
    
    html += '</div><span class="'+messageClass3+'">'+formatTimestamp2(message.timestamp)+'</span></div>';
    
    messageHistoryContainer.append(html);
  });
  var lastMessage = messageHistoryContainer.find('.other-chat-container, .own-chat-container').last()[0];
if (lastMessage) {
  lastMessage.scrollIntoView();
}


  // Additional code to handle styling and showing the chat
 messageHistoryContainer.addClass('archat');
 messageHistoryContainer.addClass('active-chat');

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

function updateMessageCount(key, currentCount) {
    var mcount = currentCount + 1;
    $(`[data-chat="person${key}"] .chat-count`).text(mcount);
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
            var mcount = item.counts;
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
              <div class="user-head mr-2">
                <img src="${whatsappicon}" alt="avatar">
              </div>
                <div class="user-body">
                    <h5 class="text-truncate strong mb-0 mt-1 chat-user-name" data-name="${item.phone_number}" data-number="${item.phone_number}">${nameOrPhoneNumber}</span>
                    <p class="text-muted font-11 text-truncate mb-0 preview">${lastMessage.message}</p>
                </div><span class="taglabel" data-taglabel="${TagLabel}"></span>
                ${item.pinned == 1 ? '<span class="pinned"><i class="fa fa-thumbtack"></i></span>' : ''}
                
                ${mcount > 0 ? `<span class="chat-count" id="chat-count-${key}">${mcount}</span>` : ''}
                
                <div class="user-footer ml-2 text-right">
                    <span class="chat-time font-10 text-success-teal user-meta-time ">${lastTimestamp}</span>
                </div>
              ${time}
            </div>
          </div>`;
          
          var messageHistoryContainer = $(`[data-chat="person${key}"]`)
          var messageList = $(`[data-chat="person${key}"].person`);
          
        if ((item.updated_at && new Date(item.updated_at).getTime() > lastUpdateTimestamp)) {
          updatePreview(key, lastMessage.message, lastMessage.timestamp);
          updateMessageCount(key, mcount);
          
          if (messageHistoryContainer.hasClass('active-chat')) {
            fetchmessage(item.phone_number);
            messageHistoryContainer.find('.chat-count').remove();
            
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
        
        $( document ).ready(function() {
            $('.user-list-box .search > input').on('keyup', function() {
                var rex = new RegExp($(this).val(), 'i');
                $('.people .person').hide();
                $('.people .person').filter(function() {
                    return rex.test($(this).text());
                }).show();
            });
        });
        
        $('.user-list-box .person, .team-container .single-team').on('click', function(event) {
            if ($(this).hasClass('.active')) {
                return false;
            }
            else {
                
                var findChat = $(this).attr('data-chat');
                var personName = $(this).find('.chat-user-name').text();
                var number = $(this).find('.chat-user-name').data('number');
                var tag = $(this).find('.taglabel').data('taglabel');
                updateLabelUI(tag);
                currentPhoneNumber = number;
                var personImage = $(this).find('img').attr('src');
                $('.user-list-box .person').removeClass('active');
                $(this).addClass('active');
                $('.chat-details').fadeIn();
                $('.chat-user-details').fadeIn();
                $('.chat-details.empty').addClass('d-none');
                $('.chatting-container[data-chat = '+findChat+']').addClass('active-chat');
                const getScrollContainer = document.querySelector('.chatting-container'); //Scroll bottom when chat initiate
                getScrollContainer.scrollTop = getScrollContainer.scrollHeight;
                
                if (window.innerWidth <= 767) {
                    $('.chat-with-name').html(personName.split(' ')[0]);
                }else if (window.innerWidth > 767) {
                    $('.chat-with-name').html(personName.split(' ')[0]);
                    $('.chat-number').html(number);
                }
            }
            
            $('.hamburger').children().removeClass('la-times');
            $('.hamburger').children().addClass('la-bars');
            $('.chat-container .user-container').removeClass('opened');
            toggleHumburger = !toggleHumburger;
        });
        
        $('.chat-with').on('click', function(event) {
            $('.chat-user-details').addClass('visible');
        });
        $('.hide-chat-user-details').on('click', function(event) {
            $(this).parents('.chat-user-details').removeClass('visible');
        });
        
        var toggleHumburger;
        $('.hamburger').on('click', function(event) {
            toggleHumburger = !toggleHumburger;
            if(toggleHumburger){
                $(this).children().removeClass('la-bars');
                $(this).children().addClass('la-times');
                $('.chat-container .user-container').addClass('opened');
            } else {
                $(this).children().removeClass('la-times');
                $(this).children().addClass('la-bars');
                $('.chat-container .user-container').removeClass('opened');
            }
        })
         
        
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
function downloadMessageHistory(phone) {
    $.ajax({
        type: 'GET',
        url: base_url + '/user/get-message/' + phone + '/' + cloudapi_id,
        dataType: 'json',
        success: function(response) {
            if (response.message && response.message.length > 0) {
                // Assuming the message history is in the first element of the array
                const messageHistory = response.message[0];

                // Convert the message history data to JSON
                const jsonData = JSON.stringify(messageHistory, null, 2); // null, 2 adds indentation for better readability

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
        },
        error: function(xhr, status, error) {
            console.error('Error fetching message history:', error);
        }
    });
}


$(document).ready(function() {
    // Flag to check if AJAX request is in progress
    var isSendingMessage = false;

    // Attach event listener for sending messages when "Enter" is pressed in the input field
    $('.chat-text-input').on('keydown', function(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    });

    // Attach event listener for sending messages when the send button is clicked
    $('.chat-send').on('click', function(event) {
        sendMessage();
    });

    // Function to send a message
    function sendMessage() {
        // Check if the previous AJAX request is still in progress
        if (isSendingMessage) {
            return;
        }
        var receiver = $('#receiver').val();
        var dt = new Date();
        var time = dt.getHours() + ":" + dt.getMinutes();
        var chatInput = $('.chat-text-input');
        var chatInputMessage = chatInput.val();
        var fileInput = $('#file-input')[0].files[0];
        
        var formData = new FormData();
    formData.append('receiver', receiver);
    formData.append('message', chatInputMessage);
    formData.append('fileInput', fileInput);
        if (chatInputMessage === '') {
            chatInput.addClass('border border-danger');
            return;
        } else {
            var $messageHtml = '<div class="own-chat-container slideInRight"><div class="own-chat"><p>' + chatInputMessage + '</p></div><span class="own-time">' + time + '</span></div>';
            $('.chatting-container').append($messageHtml);
            const getScrollContainer = document.querySelector('.chatting-container');
            getScrollContainer.scrollTop = getScrollContainer.scrollHeight;

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
                },
                error: function(xhr, status, error) {
                    // Handle the error response
                    console.error(error);
                },
                complete: function() {
                    // Set the flag to indicate that the AJAX request is complete
                    isSendingMessage = false;
                }
            });

            if (chatInput.hasClass('border border-danger')) {
                chatInput.removeClass('border border-danger');
            }
            chatInput.val('');
            $('#file-input').val('');
        }
    }
});


var downloadHandler;
$(document).on('click', '.person', function() {
    var preview = $(this).find('.preview');
    preview.css('color', '#888ea8');
  var chatId = $(this).data('chat');
  var phone = $(this).find('.chat-user-name').data('number');
  $('#phone').val(phone);
  $('#phone2').val(phone);
  var messageHistoryContainer = $('.chatting-container');
  messageHistoryContainer.attr('data-chat', chatId);
  fetchmessage(phone)
  $(this).find('.chat-count').remove();
  $('.receiver-number').val(phone);
  if (downloadHandler) {
    document.getElementById('downloadButton').removeEventListener('click', downloadHandler);
  }
  downloadHandler = function() {
    downloadMessageHistory(phone);
  };
  document.getElementById('downloadButton').addEventListener('click', downloadHandler);
  
  const ps = new PerfectScrollbar('.people', {
            suppressScrollX : true
        });
        const cs = new PerfectScrollbar('.chatting-container', {
            suppressScrollX : true
        });
  
});
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

 $('#addCustomTagBtn').on('click', function() {
        var customTag = $('#customTagInput').val();
        if (customTag) {
            var phone = currentPhoneNumber; // Replace with your logic to get the current phone number
            updateCustomLabel(customTag, phone);
            // Clear the input field after submission
            $('#customTagInput').val('');
        } else {
            alert('Please enter a custom tag.');
        }
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
function updateCustomLabel(customTag, phone) {
        $.ajax({
            url: base_url + '/user/update-label/' + cloudapi_id,
            type: 'POST',
            data: { tag: customTag, phone: phone },
            success: function(response) {
                updateLabelUI(customTag, true);
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
    if (typeof tag === 'string') {
        // Custom text tag
        label.text(tag).css({ background: '#FFD700', color: '#000', 'border-radius': '10px', padding: '6px' }); // Customize the styles as needed
    } else {
        // Numeric tags
        switch (tag) {
            case 1:
                label.text('New').css({ background: '#000', color: '#fff', 'border-radius': '10px', padding: '6px' });
                break;
            case 2:
                label.text('Pending').css({ background: 'orange', color: '#fff', 'border-radius': '10px', padding: '6px' });
                break;
            case 3:
                label.text('Positive').css({ background: 'blue', color: '#fff', 'border-radius': '10px', padding: '6px' });
                break;
            case 4:
                label.text('Negative').css({ background: 'red', color: '#fff', 'border-radius': '10px', padding: '6px' });
                break;
            case 5:
                label.text('Converted').css({ background: 'green', color: '#fff', 'border-radius': '10px', padding: '6px' });
                break;
            default:
                label.text('Add Label').css({ background: 'grey', color: '#fff', 'border-radius': '10px', padding: '6px' });
                break;
        }
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


function updatePin(value, phone) {
    $.ajax({
        url: base_url + '/user/update-pinned/' + cloudapi_id,
        type: 'POST',
        data: { value: value, phone: phone },
        success: function(response) {
            updatePinned(value);
        },
        error: function(xhr) {
            console.log(xhr.responseJSON.message);
        }
    });
}

function updateMute(value, phone) {
    $.ajax({
        url: base_url + '/user/update-mute/' + cloudapi_id,
        type: 'POST',
        data: { value: value, phone: phone },
        success: function(response) {
            updateMutes(value);
        },
        error: function(xhr) {
            console.log(xhr.responseJSON.message);
        }
    });
}

function updateMutes(value) {
    var pinButton = $('#mute-notification');
    if (value == 1) {
        pinButton.css('color', '#ffce76');
    } else {
        pinButton.css('color', '');
    }
}

function updatePinned(value) {
    var pinButton = $('#pin-to-top');
    if (value == 1) {
        pinButton.css('color', '#ffce76');
    } else {
        pinButton.css('color', '');
    }
}

function togglePin() {
    var pinButton = $('#pin-to-top');
    var currentColor = pinButton.css('color');
    var newValue = (currentColor === 'rgb(255, 206, 118)') ? 0 : 1; // 'rgb(0, 128, 0)' is green in RGB
    updatePin(newValue, currentPhoneNumber); // Assuming 'phone' is defined elsewhere in your script
}
function toggleMute() {
    var muteButton = $('#mute-notification');
    var currentColor = muteButton.css('color');
    var newValue = (currentColor === 'rgb(255, 206, 118)') ? 0 : 1; // 'rgb(0, 128, 0)' is green in RGB
    updateMute(newValue, currentPhoneNumber);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('clear-chat').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear all messages except the first one?')) {
            $.ajax({
                url: base_url + '/user/clear-messages/' + cloudapi_id,
                type: 'POST',
                data: {
                    phone: currentPhoneNumber
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Messages cleared successfully, except the first one.');
                        // Add logic to update the chat UI if necessary
                    } else {
                        alert('Failed to clear messages: ' + response.message);
                    }
                },
                error: function(xhr) {
                    console.error('Error clearing messages:', xhr.responseJSON.message);
                    alert('An error occurred while clearing messages.');
                }
            });
        }
    });
});


