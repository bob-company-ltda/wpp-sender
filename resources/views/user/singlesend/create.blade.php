@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=>__('Single Send')])
@endsection
@push('topcss')
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/css/uikit.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">
@endpush
@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 scrollbar-inner scroll-content scroll-scrolly_visible" style="overflow-x:auto;">
                        <ul class="nav nav-pills" style="flex-wrap: nowrap;" id="myTab4" role="tablist">
                            <li class="nav-ar nav-item mt-2">
                                <a class="nav-link active" style="width: max-content;" id="home-tab4" data-toggle="tab" href="#mode_1" role="tab" aria-controls="home" aria-selected="true">{{ __('Plain Text') }}</a>
                            </li>
                            <li class="nav-ar nav-item mt-2">
                                <a class="nav-link" style="width: max-content;" id="profile-tab4" data-toggle="tab" href="#mode_2" role="tab" aria-controls="profile" aria-selected="false">{{ __('Text With Documents') }}</a>
                            </li>
                            <li class="nav-ar nav-item mt-2">
                                <a class="nav-link" style="width: max-content;" id="profile-tab4" data-toggle="tab" href="#mode_3" role="tab" aria-controls="profile" aria-selected="false">{{ __('Text With Image') }}</a>
                            </li>
                            <li class="nav-ar nav-item mt-2">
                                <a class="nav-link" style="width: max-content;" id="profile-tab4" data-toggle="tab" href="#mode_4" role="tab" aria-controls="profile" aria-selected="false">
                                    {{ __('Template Message ') }} <small class="text-danger">{{ __('(Meta Approved)') }}</small>
                                </a>
                            </li>
                            <li class="nav-ar nav-item mt-2">
                                <a class="nav-link" style="width: max-content;" id="profile-tab4" data-toggle="tab" href="#mode_10" role="tab" aria-controls="profile" aria-selected="false">
                                    {{ __('Button Message ') }}
                                </a>
                            </li>
                            <li class="nav-ar nav-item mt-2">
                                <a class="nav-link" style="width: max-content;" id="profile-tab4" data-toggle="tab" href="#mode_5" role="tab" aria-controls="profile" aria-selected="false">{{ __('List Message') }}</a>
                            </li>
                            <li class="nav-ar nav-item mt-2">
                                <a class="nav-link" style="width: max-content;" id="profile-tab4" data-toggle="tab" href="#mode_6" role="tab" aria-controls="profile" aria-selected="false">{{ __('Audio Message') }}
                                </a>
                            </li>
                            <li class="nav-ar nav-item mt-2">
                                <a class="nav-link" style="width: max-content;" id="profile-tab4" data-toggle="tab" href="#mode_7" role="tab" aria-controls="profile" aria-selected="false">{{ __('Send Location') }}</a>
                            </li>
                           

                            <li class="nav-ar nav-item mt-2">
                                <a class="nav-link" style="width: max-content;" id="profile-tab4" data-toggle="tab" href="#mode_9" role="tab" aria-controls="profile" aria-selected="false">{{ __('Send Video') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-content no-padding" id="myTab2Content">
    

<div class="tab-pane fade show active" id="mode_1" role="tabpanel" aria-labelledby="home-tab4">
<form method="POST" action="{{ route('user.sent.customtext','plain-text') }}" class="ajaxform_reset_form">
@csrf
@foreach($cloudapis as $cloudapi)
<input type="hidden" name="cloudapi" value="{{ $cloudapi->id }}">
@endforeach
<div class="row justify-content-center">
    <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('Recipient')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="campign_elements">
                        <div id="form-group-contact_id" class="form-group focused">
        <label class="form-control-label">Message To</label><br> 
        <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
            </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Messages')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea1', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea1', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea1', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm" style="padding:8px;">Emoji</button>
                                                <textarea id="myTextarea1" class="form-control one mt-1" name="message" required="" maxlength="1000" oninput="updatePreview('myTextarea1', 'previewText1')"></textarea>
                                            </div>
                                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Preview')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body" style="justify-content: flex-end; text-align: right; background: url('{{ asset('assets/img/bg.png') }}');">
                            <div class="card" style="text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
                                <div class="card-body" style="">
                                    <p class="card-text" id="previewText1">This is the example text messages to demonstrate the Preview panel of whatsApp Api</p>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-sm-12" style="text-align:center;margin-bottom: 10px;">
        <button id="submitBtn" type="submit" class="btn btn-outline-primary submit-button">{{ __('Send Message') }}</button>
    </div>
                    </div>
                </div>
</div>
</form>
</div>
<!------------------------>
<!------------------------>
<div class="tab-pane fade" id="mode_2" role="tabpanel" aria-labelledby="profile-tab4">
<form method="POST" action="{{ route('user.sent.customtext','text-with-media') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
@csrf
@foreach($cloudapis as $cloudapi)
<input type="hidden" name="cloudapi" value="{{ $cloudapi->id }}">
@endforeach
<div class="row justify-content-center">
    <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('Recipient')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="campign_elements">
                        <div id="form-group-contact_id" class="form-group focused">
        <label class="form-control-label">Message To</label><br> 
        <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
            </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Messages')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Select File') }}</label>
                                                <input id="pdfInput" type="file" class="form-control" name="file" required="" onchange="previewFile('pdfInput', 'filePreviewPdf', 'pdf')" />
                                               <small>{{__(' Supported file type:')}}</small> <small class="text-danger">{{ __('pdf,docx,xlsx,csv,txt') }}</small>
                                            </div>
                                            

                                        </div>
                                        
                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea2', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea2', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea2', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm" style="padding:8px;">Emoji</button>
                                                <textarea id="myTextarea2" class="form-control one mt-1" name="message" required="" maxlength="1000" oninput="updatePreview('myTextarea2', 'previewText2')"></textarea>
                                            </div>
                                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Preview')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body" style="justify-content: flex-end; text-align: right; background: url('{{ asset('assets/img/bg.png') }}');">
                            <div class="card" style="text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
                                <img id="filePreviewPdf" class="card-img-top" style="" src="{{ asset('assets/img/pdf.png') }}" alt="Card image cap">
                                <div class="card-body" style="">
                                    <p  class="card-text" id="previewText2">This is the example Documents messages to demonstrate the Preview panel of whatsApp Api</p>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-sm-12" style="text-align:center;margin-bottom: 10px;">
        <button id="submitBtn" type="submit" class="btn btn-outline-primary submit-button">{{ __('Send Message') }}</button>
    </div>
                    </div>
                </div>
                
</div>
</form>
</div>
<!------------------------>
<!------------------------>
<div class="tab-pane fade" id="mode_3" role="tabpanel" aria-labelledby="profile-tab4">
<form method="POST" action="{{ route('user.sent.customtext','text-with-image') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
@csrf
@foreach($cloudapis as $cloudapi)
<input type="hidden" name="cloudapi" value="{{ $cloudapi->id }}">
@endforeach
<div class="row justify-content-center">
    <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('Recipient')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="campign_elements">
                        <div id="form-group-contact_id" class="form-group focused">
        <label class="form-control-label">Message To</label><br> 
        <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
            </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Messages')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Select Image') }}</label>
                                                <input id="imageInput" type="file" class="form-control" name="image" required="" onchange="previewFile('imageInput', 'filePreviewImage', 'image')"/>
                                               <small>{{__(' Supported image type:')}}</small> <small class="text-danger">{{ __('jpg,jpeg,png') }}</small>
                                            </div>
                                        </div>
                                        
                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea3', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea3', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea3', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm" style="padding:8px;">Emoji</button>
                                                <textarea id="myTextarea3" class="form-control one mt-1" name="message" required="" maxlength="1000" oninput="updatePreview('myTextarea3', 'previewText3')"></textarea>
                                            </div>
                                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Preview')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body" style="justify-content: flex-end; text-align: right; background: url('{{ asset('assets/img/bg.png') }}');">
                            <div class="card" style="text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
                                <img id="filePreviewImage" class="card-img-top" style="" src="{{ asset('assets/img/img.jpg') }}" alt="Card image cap">
                                <div class="card-body" style="">
                                    <p class="card-text" id="previewText3">This is the example of Image messages to demonstrate the Preview panel of whatsApp Api</p>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-sm-12" style="text-align:center;margin-bottom: 10px;">
        <button id="submitBtn" type="submit" class="btn btn-outline-primary submit-button">{{ __('Send Message') }}</button>
    </div>
                    </div>
                </div>
</div>
</form>
</div>
<!--------------------------------------------->
<!--------------------------------------------->

<div class="tab-pane fade" id="mode_5" role="tabpanel" aria-labelledby="profile-tab4">
<form method="POST" action="{{ route('user.sent.customtext','text-with-list') }}" class="ajaxform_reset_form">
@csrf
@foreach($cloudapis as $cloudapi)
<input type="hidden" name="cloudapi" value="{{ $cloudapi->id }}">
@endforeach
<div class="row justify-content-center">
    <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('Recipient')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="campign_elements">
                        <div id="form-group-contact_id" class="form-group focused">
        <label class="form-control-label">Message To</label><br> 
        <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
            </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Messages')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                       <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea4', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea4', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea4', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm" style="padding:8px;">Emoji</button>
                                                 <textarea id="myTextarea4" class="form-control" required="" name="message" placeholder="{{ __('Example: This is a list') }}" oninput="updatePreview('myTextarea4', 'previewText4')"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Title (Header)') }}</label>
                                                <input id="heading4"  type="text" class="form-control" name="header_title" placeholder="{{ __('Example: Amazing boldfaced list title') }}" value="" required=""  maxlength="50" oninput="updatePreview('heading4', 'previewheader4')" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Footer Text') }}</label>
                                                <input id="footerText4"  type="text" class="form-control" name="footer_text" placeholder="{{ __('Example: Thank you') }}" value="" required=""  maxlength="50" oninput="updatePreview('footerText4', 'previewFooter4')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Button Text for select option') }}</label>
                                                <input id="buttonText4"  type="text" class="form-control" name="button_text" placeholder="{{ __('Example: Required, text on the button to view the list') }}" value="" required=""  maxlength="50" oninput="updatePreview('buttonText4', 'buttonlist4')"/>
                                            </div>
                                        </div>
                                        
                    </div>
                </div>
                
                                        
                                        <div class="col-sm-12">
                                            <div class="list-option-area">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h4 class="mt-2">{{ __('List Options') }}</h4>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="javascript:void(0)" id="add-more-option" class="btn btn-sm btn-primary btn-neutral float-right mb-1"><i class="fa fa-plus"></i>&nbsp; {{ __('Add More Card') }}</a>
                                                    </div>
                                                </div>
                                                <div class="list-area">
                                                    <div class="card card-primary card-item" style="flex: 0 0 100%;margin: 6px;">
                                                        <div class="card-header">
                                                            <h4>{{ __('List 1') }}</h4>
                                                          
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label>{{ __('List Section Title') }}</label>
                                                                        <input  type="text" class="form-control" name="section[1][title]" placeholder="{{ __('Example: Select a fruit') }}" value="" required=""  maxlength="50" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row list-item-area1">

                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>{{ __('Enter List Value Name') }}</label>
                                                                        <input  type="text" class="form-control itemval-1" name="section[1][value][1][title]" placeholder="{{ __('Example: Banana') }}" value="" required=""  maxlength="50" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>{{ __('Enter List Value Description') }}</label>

                                                                        <input  type="text" class="form-control" name="section[1][value][1][description]" placeholder="{{ __('Example: Banana is a healthly food') }}" value=""   maxlength="50" />
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="row">

                                                                <div class="col-12 text-right">
                                                                    <a href="javascript:void(0)" class="text-right btn btn-sm btn-neutral add-more-option-item option-item-btn1" data-target=".list-item-area1" data-key="1"><i class="fas fa-plus"></i>&nbsp{{ __('Add More Item') }}</a>
                                                                </div>    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Preview')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body" style="justify-content: flex-end; text-align: right; background: url('{{ asset('assets/img/bg.png') }}');">
                            <div class="card" style="text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
                                
                                <div class="card-body" style="">
                                    <h2 class="heading" id="previewheader4">This is the heading</h2>
                                    <p class="card-text" id="previewText4">This is the example of List messages to demonstrate the Preview panel of whatsApp Api</p>
                                    <span id="previewFooter4" class="text-muted text-xs">This is footer example</span>
                                    </div>
                                </div>
                                <div class="card" style="text-align: center; margin-bottom: 5px;">
                                    <div class="card-body" style="padding:1rem">
                                        <span id="buttonlist4" class="" style="color: #00a5f4" >Required Button</span>
                                    </div>
                            </div>
                            </div>
                            <div class="col-sm-12" style="text-align:center;margin-bottom: 10px;">
        <button id="submitBtn" type="submit" class="btn btn-outline-primary submit-button">{{ __('Send Message') }}</button>
    </div>
                    </div>
                </div>
            </div>
         
</div>   
</form>
</div>

<!------------------------------------------------------------>
<!------------------------------------------------------------>
<div class="tab-pane fade" id="mode_10" role="tabpanel" aria-labelledby="profile-tab4">
<form method="POST" action="{{ route('user.sent.customtext','text-with-button') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
@csrf
@foreach($cloudapis as $cloudapi)
<input type="hidden" name="cloudapi" value="{{ $cloudapi->id }}">
@endforeach
<div class="row justify-content-center">
    <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('Recipient')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="campign_elements">
                        <div id="form-group-contact_id" class="form-group focused">
        <label class="form-control-label">Message To</label><br> 
        <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
            </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Messages')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea9', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea9', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea9', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm" style="padding:8px;">Emoji</button>
                                                <textarea id="myTextarea9" class="form-control one mt-1" name="message" required="" maxlength="1000" oninput="updatePreview('myTextarea9', 'previewText9')"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Header Text (Optional)') }}</label>
                                                <input id="headerButton9" type="text" class="form-control" name="header_text" autofocus="" maxlength="100" oninput="updatePreview('headerButton9', 'headerPreview9')" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Footer Text (Optional)') }}</label>
                                                <input id="footerButton9" type="text" class="form-control" name="footer_text" autofocus="" maxlength="100" oninput="updatePreview('footerButton9', 'footerPreview9')" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12" id="list-button-appendarea">
                                            <div class="form-group plain_button_message_text">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label>{{ __('Button 1 Text') }}</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="javascript:void(0)" id="add-more" class="btn btn-sm btn-primary btn-neutral float-right mb-1"><i class="fa fa-plus"></i>&nbsp{{ __('Add More') }}</a>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="buttons[]" required="" autofocus="" maxlength="50" />
                                            </div>
                                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Preview')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body" style="justify-content: flex-end; text-align: right; background: url('{{ asset('assets/img/bg.png') }}');">
                            <div class="card" style="text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
                                
                                <div class="card-body" style="">
                                    <h2 class="heading" id="headerPreview9">This is the heading</h2>
                                    <p class="card-text" id="previewText9">This is the example of Button messages to demonstrate the Preview panel of whatsApp Api</p>
                                    <span id="footerPreview9" class="text-muted text-xs">This is footer example</span>
                                    </div>
                                </div>
                                <div class="card" style="text-align: center; margin-bottom: 5px;">
                                    <div class="card-body" style="padding:1rem">
                                        <span class="" style="color: #00a5f4" >Buttons</span>
                                    </div>
                            </div>
                            </div>
                            </div>
                            <div class="col-sm-12" style="text-align:center;margin-bottom: 10px;">
        <button id="submitBtn" type="submit" class="btn btn-outline-primary submit-button">{{ __('Send Message') }}</button>
    </div>
                    </div>
                </div>
</div>
</form>
</div>

<!---------------------------------------------------------------->
<!---------------------------------------------------------------->

<div class="tab-pane fade" id="mode_6" role="tabpanel" aria-labelledby="profile-tab4">
<form method="POST" action="{{ route('user.sent.customtext','text-with-audio') }}" class="ajaxform_reset_form">
@csrf
@foreach($cloudapis as $cloudapi)
<input type="hidden" name="cloudapi" value="{{ $cloudapi->id }}">
@endforeach
<div class="row justify-content-center">
    <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('Recipient')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="campign_elements">
                        <div id="form-group-contact_id" class="form-group focused">
        <label class="form-control-label">Message To</label><br> 
        <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
            </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Messages')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Select Audio File') }}</label>
                                                <input id="phone" type="file" class="form-control" name="audio" required="" />
                                               <small>{{__(' Supported image type:')}}</small> <small class="text-danger">{{ __('mp3,wav,ogg') }}</small>
                                            </div>
                                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Preview')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body" style="justify-content: flex-end; text-align: right; background: url('{{ asset('assets/img/bg.png') }}');">
                            <div class="card" style="text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
                                <audio controls style="width:auto">
    <!-- Specify the audio file source -->
    <source src="example.mp3" type="audio/mp3">
</audio>
                                
                                </div>
                            </div>
                            </div>
                            <div class="col-sm-12" style="text-align:center;margin-bottom: 10px;">
        <button id="submitBtn" type="submit" class="btn btn-outline-primary submit-button">{{ __('Send Message') }}</button>
    </div>
                    </div>
                </div>
</div>
</form>
</div>

<!---------------------------------------------------------------->
<!---------------------------------------------------------------->

<div class="tab-pane fade" id="mode_7" role="tabpanel" aria-labelledby="profile-tab4">
<form method="POST" action="{{ route('user.sent.customtext','text-with-location') }}" class="ajaxform_reset_form">
@csrf
@foreach($cloudapis as $cloudapi)
<input type="hidden" name="cloudapi" value="{{ $cloudapi->id }}">
@endforeach
<div class="row justify-content-center">
    <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('Recipient')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="campign_elements">
                        <div id="form-group-contact_id" class="form-group focused">
        <label class="form-control-label">Message To</label><br> 
        <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
            </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Messages')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>{{ __('Latitude') }}</label>
                                                    <input type="number" step="any" name="degreesLatitude" required="" class="form-control" placeholder="Example: 24.121231">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>{{ __('Longitude') }}</label>
                                                    <input type="number" step="any" name="degreesLongitude" required="" class="form-control" placeholder="Example: 55.1121221">
                                                </div>
                                            </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Preview')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body" style="justify-content: flex-end; text-align: right; background: url('{{ asset('assets/img/bg.png') }}');">
                            <div class="card"  style="text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
                                <iframe
    src="https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q=latitude,longitude"
    allowfullscreen
></iframe>
                                
                                </div>
                            </div>
                            </div>
                            <div class="col-sm-12" style="text-align:center;margin-bottom: 10px;">
        <button id="submitBtn" type="submit" class="btn btn-outline-primary submit-button">{{ __('Send Message') }}</button>
    </div>
                    </div>
                </div>
</div>
</form>
</div>
<!----------------------------------------------------------------------->
<!----------------------------------------------------------------------->

<div class="tab-pane fade" id="mode_9" role="tabpanel" aria-labelledby="profile-tab4">
<form method="POST" action="{{ route('user.sent.customtext','text-with-video') }}" class="ajaxform_reset_form">
@csrf
@foreach($cloudapis as $cloudapi)
<input type="hidden" name="cloudapi" value="{{ $cloudapi->id }}">
@endforeach
<div class="row justify-content-center">
    <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('Recipient')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="campign_elements">
                        <div id="form-group-contact_id" class="form-group focused">
        <label class="form-control-label">Message To</label><br> 
        <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
            </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Messages')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Select video File') }}</label>
                                                <input id="videoInput" type="file" class="form-control" name="video" required="" onchange="previewFile('videoInput', 'filePreviewVideo', 'video')"/>
                                               <small>{{__(' Supported Video type:')}}</small> <small class="text-danger">{{ __('mp4 | max : 6mb') }}</small>
                                            </div>
                                        </div> 
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea5', '*', '*')">Bold</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea5', '_', '_')">Italic</button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" style="padding:8px;" onclick="insertTag('myTextarea5', '~', '~')">Strike</button>
                                                <button type="button" class="emojipick btn btn-outline-primary btn-sm" style="padding:8px;">Emoji</button>
                                                <textarea id="myTextarea5" class="form-control one mt-1" name="message" required="" maxlength="1000" oninput="updatePreview('myTextarea5', 'previewText5')"></textarea>
                                            </div>
                                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Preview')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body" style="justify-content: flex-end; text-align: right; background: url('{{ asset('assets/img/bg.png') }}');">
                            <div class="card" style="text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
                                <video id="filePreviewVideo" controls>
    <source src="example.mp4" type="video/mp4">
</video>
<div class="card-body" style="">
                                <p class="card-text" id="previewText5">This is the example of Caption and text related to Video file. </p>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-sm-12" style="text-align:center;margin-bottom: 10px;">
        <button id="submitBtn" type="submit" class="btn btn-outline-primary submit-button">{{ __('Send Message') }}</button>
    </div>
                    </div>
                </div>
</div>
</form>
</div>

<!--------------------------------------------------------------------------->
<!--------------------------------------------------------------------------->

<div class="tab-pane fade" id="mode_4" role="tabpanel" aria-labelledby="profile-tab4">
<form id="metatemplate" method="POST" action="{{ route('user.sent.customtext','meta-template') }}" class="ajaxform_reset_form"  enctype="multipart/form-data">
@csrf
@foreach($cloudapis as $cloudapi)
<input type="hidden" name="cloudapi" value="{{ $cloudapi->id }}">
@endforeach
<input type ="hidden" name="language" id="language" value="">
<div class="row justify-content-center">
    <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('📢 Meta Approved')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('user.singlesend.new.campaign')
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Variables')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('user.singlesend.new.variables')
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">{{__('Preview')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('user.singlesend.new.preview')
                    </div>
                </div>
            </div>
</div>
</form>
</div>
<!-------------------------------------------------------------->
<!-------------------------------------------------------------->


<!------------------------------------------------------------->
<!------------------------------------------------------------->

</div>
</div>
@foreach($cloudapis as $cloudapi)
<input type="hidden" id="ccuuid" value="{{ $cloudapi->uuid }}">
@endforeach
<input type="hidden" id="base_url" value="{{ url('/') }}">
@endsection
@push('js')
<script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="https://woody180.github.io/vanilla-javascript-emoji-picker/vanillaEmojiPicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/js/uikit-icons.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/pages/bulk/template.js') }}"></script>
       <script>

        new EmojiPicker({
            trigger: [
                {
                    selector: '.emojipick',
                    insertInto: ['.one'] // '.selector' can be used without array
                }
            ],
            closeButton: true,
            //specialButtons: green
        });

   
        function insertTag(textareaId, openTag, closeTag) {
            var textarea = document.getElementById(textareaId);
            var startPos = textarea.selectionStart;
            var endPos = textarea.selectionEnd;
            var selectedText = textarea.value.substring(startPos, endPos);
            var newText = openTag + selectedText + closeTag;
            textarea.value = textarea.value.substring(0, startPos) + newText + textarea.value.substring(endPos, textarea.value.length);
            textarea.focus();
            textarea.setSelectionRange(endPos + openTag.length + closeTag.length, endPos + openTag.length + closeTag.length);
        }
        
        function saveToLocalStorage(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
}
function getFromLocalStorage(key) {
    const storedValue = localStorage.getItem(key);
    
    // Parse the stored JSON value
    return storedValue ? JSON.parse(storedValue) : null;
}

const exampleKey ='cloudapi';
const exampleValue = document.getElementById('ccuuid').value ;

// Save the key-value pair
saveToLocalStorage(exampleKey, exampleValue);
const retrievedValue = getFromLocalStorage(exampleKey);

console.log(retrievedValue); 

    </script>
    
    <script>
        function updatePreview(textareaId, previewId) {
            // Get the value from the corresponding textarea
            var message = document.getElementById(textareaId).value;

            // Update the content of the corresponding preview element
            document.getElementById(previewId).textContent = message;
        }
    </script>
    
    <script>
        function previewFile(inputId, previewId, fileType) {
            var input = document.getElementById(inputId);
            var preview = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    if (fileType === 'image') {
                        preview.src = e.target.result;
                    } else if (fileType === 'video') {
                        preview.src = e.target.result;
                    } else if (fileType === 'pdf') {
                        // Handle PDF preview, e.g., use a PDF viewer or convert pages to images
                        preview.innerHTML = 'PDF Preview: ' + input.files[0].name;
                    }
                }

                if (fileType !== 'pdf') {
                    reader.readAsDataURL(input.files[0]);
                } else {
                    // Handle PDF preview, e.g., use a PDF viewer or convert pages to images
                    preview.innerHTML = 'PDF Preview: ' + input.files[0].name;
                }
            } else {
                if (fileType !== 'pdf') {
                    preview.src = "";
                } else {
                    preview.innerHTML = 'No PDF selected';
                }
            }
        }
    </script>

@endpush