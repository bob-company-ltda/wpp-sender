@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',[
'title' => __('Chat List'),
'buttons'=>[
[
'name'=> __('CloudApis List'),
'url'=> route('user.cloudapi.index'),
]
]])
@endsection
@push('css')
<style>.header-body{display:none;}</style>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cloudapi.css') }}">
@endpush
@section('content')

<div class="layout-px-spacing">

<div class="chat-section layout-top-spacing">
<div class="row">
    @if(getUserPlanData('access_chat_list') == true)
        <div class="col-xl-12 col-lg-12 col-md-12">

        <div class="chat-system">
    <div class="hamburger">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </div>
    <div class="hamburger2">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-resize-full-screen">
  <rect x="1" y="1" width="22" height="22" rx="2" ry="2"></rect>
  <line x1="6" y1="12" x2="18" y2="12"></line>
  <line x1="12" y1="6" x2="12" y2="18"></line>
</svg>
    </div>
    <div class="user-list-box">
        <div class="search">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" class="form-control" placeholder="Search" />
        </div>

        <div class="people">           
        </div>
    </div>
    <div class="chat-box">
        <div class="chat-not-selected">
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                Click User To Chat
            </p>
        </div>
        <div class="overlay-phone-call">
                                <div class="">
                                    <div class="calling-user-info">
                                        <div class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle go-back-chat"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                            <span class="user-name"></span>
                                            <span class="call-status">IVR Features are coming soon....</span>
                                        </div>
                                    </div>

                                    <div class="calling-user-img">
                                        <div class="">
                                            <img src="" alt="dynamic-image">
                                        </div>

                                        <div class="timer"><label class="minutes">00</label> : <label class="seconds">00</label></div>
                                    </div>

                                    <div class="calling-options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video switch-to-video-call"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic switch-to-microphone"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus add-more-caller"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-off cancel-call"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7 2 2 0 0 1 1.72 2v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.33-2.67m-2.67-3.34a19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91"></path><line x1="23" y1="1" x2="1" y2="23"></line></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="overlay-video-call">
                                <img src="{{asset('/assets/img/whatsapp.png')}}" class="video-caller" alt="video-chat">
                                <div class="">
                                    <div class="calling-user-info">
                                        <div class="d-flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle go-back-chat"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                            <div class="">
                                                <span class="user-name"></span>
                                                <div class="timer"><label class="minutes">00</label> : <label class="seconds">00</label></div>
                                            </div>
                                            <span class="call-status">IVR Features are coming soon....</span>
                                        </div>
                                    </div>

                                    <div class="calling-user-img">
                                        <div class="">
                                            <img src="" alt="dynamic-image">
                                        </div>

                                    </div>
                                    <div class="calling-options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone switch-to-phone-call"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic switch-to-microphone"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus add-more-caller"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off cancel-call"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                    </div>

                                </div>
                            </div>
        <div class="chat-box-inner">
        <div class="chat-meta-user">
                            <div class="current-chat-user-name"><span><img src="{{asset('/assets/img/whatsapp.png')}}" alt="dynamic-image"><span class="name"></span></span></div>
                            
                            <div class="chat-action-btn align-self-center">
                                <div class="dropdown d-inline-block">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="TagLabel" id="TagLabel" style="padding: 7px;margin-right: 16px;border-radius: 15px 0px 0px 15px;font-size: 11px;">Add Label</span>
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-3">
                                               <a class="dropdown-item label-group-item dropdown-item label-new-lead position-relative g-dot-new-lead" href="javascript:void(0);" data-taglabel="1"> New Lead</a>
                                               <a class="dropdown-item label-group-item label-pending dropdown-item position-relative g-dot-pending" href="javascript:void(0);" data-taglabel="2"> Pending</a>
                                               <a class="dropdown-item label-group-item label-positive dropdown-item position-relative g-dot-positive" href="javascript:void(0);" data-taglabel="3"> Positive</a>
                                               <a class="dropdown-item label-group-item label-negative dropdown-item position-relative g-dot-negative" href="javascript:void(0);" data-taglabel="4"> Negative</a>
                                               <a class="dropdown-item label-group-item label-converted dropdown-item position-relative g-dot-converted" href="javascript:void(0);" data-taglabel="5"> Converted</a>
                                                
                                            </div>
                            </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone  phone-call-screen"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video video-call-screen"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-2">
                                                <button style="font-size: 12px; font-weight: 700; color: #888ea8; padding: 11px 8px;" class="dropdown-item" id="downloadButton"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> Download Chat</button>
                                                <a class="dropdown-item" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg> Share</a>
                                            </div>
                                        </div>
                                    </div>
                        </div>
            <div class="chat-conversation-box">
                <div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">
                    <div class="loading" style="display:none;">Loading&#8230;</div>
                    <div class="chat" data-chat="person" data-last-timestamp="0">
                    </div>
                </div>
            </div>
            
            <div class="chat-footer">
                <div class="chat-input">
                    <div class="float-btn top">
                        <a class="main-btn">+</a>
                        <ul class="art" style="background: #83838300;width: fit-content;border-radius: 68%;padding-top: 1px;">
                            <li><a><img style="width: 31px;position: relative; top: -2px;border-radius: 100%;" id="imageOption" src="{{asset('/assets/img/attachment.png')}}" alt=""></a></li>
                            <li><a><img style="width: 31px;position: relative; top: -2px;border-radius: 100%;" id="videoOption" src="{{asset('/assets/img/img-vid.png')}}" alt=""></a></li>
                            <li><a><img style="width: 31px;position: relative; top: -2px;border-radius: 100%;" id="audioOption" src="{{asset('/assets/img/voice.png')}}" alt=""></a></li>
                            <!--li><a><img style="width: 17px;position: relative; top: -2px;" id="locationOption" src="{{asset('/assets/img/location.png')}}" alt=""></a></li-->
                        </ul>
                    </div>
    
                    <form id="chatForm" class="chat-form" method="post" action="javascript:void(0);" enctype="multipart/form-data">
                        @csrf
                    
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <input type="file" id="fileInput" name="fileInput" style="display: none;">
                        <input type="hidden" readonly="" id="receiver" name="receiver" value="" class="form-control bg-white receiver-number">
                        
                        <div class="loading-input" style="display: none;"></div>
                        <input type="text" name="message" id="plain-text" class=" form-control" placeholder="Message" value=""/>
                    </form>
                    
                    <script>
                        // Get the message input element
                        var messageInput = document.getElementById('plain-text');
                        messageInput.addEventListener('input', function() {
                        messageInput.setAttribute('value', messageInput.value);
                        });
                        
                        var fileInput = document.getElementById('fileInput');
                        var imageOption = document.getElementById('imageOption');
                        var videoOption = document.getElementById('videoOption');
                        var audioOption = document.getElementById('audioOption');
                        //var locationOption = document.getElementById('locationOption');

                    function clearOptions() {
                    // Reset all options to their default state
                    imageOption.src = "{{asset('/assets/img/attachment.png')}}";
                    videoOption.src = "{{asset('/assets/img/img-vid.png')}}";
                    audioOption.src = "{{asset('/assets/img/voice.png')}}";
                    //locationOption.src = "{{asset('/assets/img/location.png')}}";

                    // Remove 'selected' class from all options
                    imageOption.classList.remove('selected');
                    videoOption.classList.remove('selected');
                    audioOption.classList.remove('selected');
                    //locationOption.classList.remove('selected');
                    }
                    
                  handleOption(imageOption, "{{asset('/assets/img/attachment.png')}}");
                  handleOption(videoOption, "{{asset('/assets/img/img-vid.png')}}");
                  handleOption(audioOption, "{{asset('/assets/img/voice.png')}}");

                function handleOption(optionElement, imagePath) {
                    optionElement.addEventListener('click', function() {
                    // Clear all options first
                    clearOptions();

                    // Set the selected image for the clicked option
                    optionElement.src = imagePath;
                    optionElement.classList.add('selected');

                    // Trigger a click on the file input when an option is clicked
                    fileInput.click();
                    });
                }
                  fileInput.addEventListener('change', function() {
    if (fileInput.files.length > 0) {
        var selectedFile = fileInput.files[0];
        var objectURL = URL.createObjectURL(selectedFile);

        // Set the source of the selected option to the selected file
        if (imageOption.classList.contains('selected')) {
            if (isValidImage(selectedFile.type)) {
                imageOption.src = objectURL;
            } else {
                // If it's not an image, show the green tick
                imageOption.src = "{{ asset('/assets/img/green-tick.png') }}";
                imageOption.classList.add('green-tick');
            }
        } else if (videoOption.classList.contains('selected')) {
            videoOption.src = objectURL;
        } else if (audioOption.classList.contains('selected')) {
            audioOption.src = objectURL;
        }
    }
});

// Function to check if the file type is a valid image
function isValidImage(fileType) {
    return fileType.startsWith('image/');
}
                        var mainBtn = document.querySelector('.main-btn');
                        var floatBtnUl = document.querySelector('.float-btn ul');
                        mainBtn.addEventListener('click', function() {
                            floatBtnUl.classList.toggle('toggled');
                        });
                        
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
@else
			<div class="col-sm-12">
				<div class="alert bg-gradient-primary text-white alert-dismissible fade show" role="alert">
					<span class="alert-icon"><i class="fi  fi-rs-info text-white"></i></span>
					<span class="alert-text">
						<strong>{{ __('!Opps ') }}</strong> 

						{{ __('Chat list access features is not available in your subscription plan') }}

					</span>
				</div>
			</div>
@endif
</div>
       
    </div>
</div>

</div>
<input type="hidden" id="uuid" value="{{$cloudapi->uuid}}">
<input type="hidden" id="base_url" value="{{ url('/') }}">

@endsection
