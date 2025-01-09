@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=> 'Create Template','buttons'=>[
    [
        'name'=>'<i class="fas fa-step-backward"></i> &nbspBack',
        'url'=> route('user.template.index'),
    ]
]])
@endsection
@push('topcss')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">
@endpush
@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header row">
                <h4 class="text-left col-6">{{ __('Create Messages Template') }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-3">
                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab4" data-toggle="tab" href="#mode_1" role="tab" aria-controls="home" aria-selected="true">{{ __('Plain Text') }}</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_2" role="tab" aria-controls="profile" aria-selected="false">{{ __('Text With Documents') }}</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_3" role="tab" aria-controls="profile" aria-selected="false">{{ __('Message With Image') }}</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_4" role="tab" aria-controls="profile" aria-selected="false">
                                    {{ __('Template Message ') }} <small class="text-danger">{{ __('(Meta Approved)') }}</small>
                                </a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_6" role="tab" aria-controls="profile" aria-selected="false">
                                    {{ __('Button Message ') }}<small class="text-danger">{{ __('(Under Development)') }}</small>
                                </a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_5" role="tab" aria-controls="profile" aria-selected="false">{{ __('List Message') }}</a>
                            </li>
                            
                            <li class="nav-item mt-2">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mode_7" role="tab" aria-controls="profile" aria-selected="false">{{ __('Send Location') }}</a>
                            </li>
                           

                            <li class="nav-item mt-2 none">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#mo" role="tab" aria-controls="profile" aria-selected="false"></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-12 col-md-9">
                        <div class="tab-content no-padding" id="myTab2Content">
                            <div class="tab-pane fade show active" id="mode_1" role="tabpanel" aria-labelledby="home-tab4">
                                <form method="POST" action="{{ route('user.template.store-now','plain-text') }}" class="ajaxform_reset_form">
                                    @csrf
                                    <div class="row">
                                         <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Name') }}</label>
                                                <input type="text" name="template_name" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-row mb-1">
                                                <label class="col-6">{{ __('Message:') }}</label>
                                                
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control h-200" name="message" required="" maxlength="1000"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-outline-primary submit-button">{{ __('Save Template') }}</button>
                                    </div>
                                </div>
                            </form>
                            </div>
                            <div class="tab-pane fade" id="mode_2" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.template.store-now','text-with-media') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Name:') }}</label>
                                                <input type="text" name="template_name" required="" placeholder="{{ __('Enter Your Template Name') }}" class="form-control">
                                            </div>
                                        </div>   
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Select File') }}</label>
                                                <input id="phone" type="file" class="form-control" name="file" required="" />
                                               <small>{{__(' Supported file type:')}}</small> <small class="text-danger">{{ __('jpg,jpeg,png,webp,pdf,docx,xlsx,csv,txt') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-row mb-1">
                                                <label class="col-12 text-left">{{ __('Media Caption:') }}</label>
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" name="message" id="message" required="" maxlength="1000"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-outline-primary submit-button">{{ __('Save Template') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="mode_3" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.template.store-now','text-with-image') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Name:') }}</label>
                                                <input type="text" name="template_name" required="" placeholder="{{ __('Enter Your Template Name') }}" class="form-control">
                                            </div>
                                        </div>   
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Select Image') }}</label>
                                                <input id="phone" type="file" class="form-control" name="image" required="" />
                                               <small>{{__(' Supported image type:')}}</small> <small class="text-danger">{{ __('jpg,jpeg,png') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-row mb-1">
                                                <label class="col-12 text-left">{{ __('Image Caption:') }}</label>
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control h-200" name="message" id="message" required="" maxlength="1000"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-outline-primary submit-button">{{ __('Save Template') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="tab-pane fade" id="mode_4" role="tabpanel" aria-labelledby="profile-tab4">
    <form method="POST" action="{{ route('user.template.store-now','meta-template') }}" class="ajaxform_reset_form"  enctype="multipart/form-data">
        @csrf
         @foreach($cloudapis as $cloudapi)
        <input type="hidden" name="cloudapi" value="{{ $cloudapi->id }}">
        @endforeach
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>{{ __('Template Name:') }}</label>
                    <input type="text" name="template_name" required="" placeholder="{{ __('Enter message template name') }}" class="form-control">
                    <small>{{__(' Name Should be in This format:')}}</small> <small class="text-danger">{{ __('Ex - hello_world') }}</small>
                </div>
            </div>

    <div class="col-sm-6">
    <div class="form-group">
        <label>{{ __('Select Language') }}</label>
        <select name="language" id="language" class="form-control">
        </select>
    </div>
    </div>
    <div class="col-sm-6">
    <div class="form-group">
        <label>{{ __('Select Category') }}</label>
        <select name="category" id="category" class="form-control">
            <!--option value="authentication">{{ __('Authentication') }}</option-->
            <option value="marketing">{{ __('Marketing') }}</option>
            <option value="utility">{{ __('Utility') }}</option>
        </select>
    </div>
    </div>
    
    <div class="col-sm-12">
        <label style="font-size: 16px; font-weight: bold;">{{ __('Header') }}<span style="font-size: 12px; font-weight: 600; background: #fbfbfb; padding: 8px; border-radius: 10px;">(Optional)</span></label>
        <p>Add a title or choose which type of media you'll use for this header</p>
        <br>
    </div>
            <!-- Header Selection -->
    <div class="col-sm-3">
    <div class="form-group">
        <label></label>
        <select name="header_type" id="header_type" class="form-control">
            <option value="none">{{ __('None') }}</option>
            <option value="text">{{ __('Text Header') }}</option>
            <option value="media">{{ __('Media Header') }}</option>
            
        </select>
    </div>
</div>

<!-- Text Header -->
<div class="col" id="text_header_div">
    <div class="form-group">
        <input type="text" name="text_header" id="text_header" placeholder="Enter text in English (US)" class="form-control">
    </div>
    <!-- Additional Input for Text Header Variables if Needed -->
    <div class="arp"><p>To help us review your content, provide examples of the variables or media in the header. Do not include any customer information. Cloud API hosted by Meta reviews templates and variable parameters to protect the security and integrity of our services.</p>
    <div id="text_header_params" class="form-group">
        <!-- Dynamic parameter input fields will be appended here -->
    </div>
    </div>
</div>


            <!-- Media Header -->
    <div class="col-sm-12" id="media_header_div">
        <div class="form-group">
        <div class="radio-group">
        <label for="image" class="radio-label">
            
            <input type="radio" id="image" name="media_type" class="radio-input" value="image">
            <div class="radio-indicator"></div><img src="https://static.xx.fbcdn.net/rsrc.php/v3/yP/r/UL8I81pzwLA.png"><span style="display:block">Image</span>
        </label>

        <label for="video" class="radio-label">
            <input type="radio" id="video" name="media_type" class="radio-input" value="video">
            <div class="radio-indicator"></div><img src="https://static.xx.fbcdn.net/rsrc.php/v3/yy/r/WbGV1ImQmlh.png"><span style="display:block">Video</span>
        </label>

        <label for="document" class="radio-label">
            <input type="radio" id="document" name="media_type" class="radio-input" value="document">
            <div class="radio-indicator"></div><img src="https://static.xx.fbcdn.net/rsrc.php/v3/yp/r/FgGwlb24b_H.png"><span style="display:block">Document</span>
        </label>
    </div>
    </div>
    <div class="arp"><p>To help us review your content, provide examples of the variables or media in the header. Do not include any customer information. Cloud API hosted by Meta reviews templates and variable parameters to protect the security and integrity of our services.</p>
    <div class="col-sm-6">
        <div class="form-group">
        <input id="phone" type="file" class="form-control" name="file"/>
        <small>{{__(' Supported file type:')}}</small> <small class="text-danger">{{ __('jpg,png,mp4,pdf') }}</small>
        </div>
        </div>
        </div>
    <!-- Additional Input for Media Header Variables if Needed -->
</div>

<div class="col-sm-12">
        <label style="font-size: 16px; font-weight: bold;">{{ __('Body') }}</label>
        <p>Enter the text for your message in the language that you've selected.</p>
        <br>
    </div>
            <!-- Body Text -->
            <div class="col-sm-12 mb-5">
    <div class="form-group">
        <textarea class="form-control h-200" name="message" id="message2" required="" maxlength="1000"></textarea>
    </div>
    <div class="arp">
    <p>To help us review your message template, please add an example for each variable in your body text. Do not use real customer information. Cloud API hosted by Meta reviews templates and variable parameters to protect the security and integrity of our services.</p>
    <div id="parameter_fields" class="form-group">
        <!-- Additional Input for Parameters if Needed -->
    </div>
    </div>
</div>


            <!-- Footer Text -->
            <div class="col-sm-12">
        <label style="font-size: 16px; font-weight: bold;">{{ __('Footer') }}<span style="font-size: 12px; font-weight: 600; background: #fbfbfb; padding: 8px; border-radius: 10px;">(Optional)</span></label>
        <p>Add a short line of text to the bottom of your message template.</p>
        <br>
    </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="text" class="form-control" name="footer_text" placeholder="Enter text in English (US)" autofocus="" maxlength="100" />
                </div>
            </div>

            <!-- Buttons -->
            <div class="col-sm-12" id="list-button-appendarea">
                <div class="form-group button_message_text">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="mt-2">{{ __('Call To Action Buttons') }}</h4>
                            <p>Buttons that let customers respond to your message.</p>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0)" id="add-more-action" class="btn btn-sm btn-primary btn-neutral float-right mb-1"><i classclass="fa fa-plus"></i>&nbsp{{ __('Add More') }}</a>
                        </div>
                    </div>
                    <div id="action-body">

                        <div class="card card-primary mt-2 call-to-action-area">
                            <div class="card-header">
                                <h4>{{ __('Button 1') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-sm-4">
                                        <label>{{ __('Select Action Type') }}</label>
                                        <select class="form-control action-type" name="buttons[0][type]" required="">
                                            <option value="none" data-key="0">{{ __('None') }}</option>
                                            <option value="urlButton" data-key="0">{{ __('URL Button') }}</option>
                                            <option value="callButton" data-key="0">{{ __('Phone number (Call Button)') }}</option>
                                            <option value="quickReplyButton" data-key="0">{{ __('Quick Reply Button') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>{{ __('Button Display Text') }}</label>
                                        <input type="text" class="form-control" name="buttons[0][displaytext]" required=""  maxlength="50" placeholder="{{ __('Button Display Text') }}" />
                                    </div>
                                    <div class="form-group col-sm-4 action-area0">
                                        <label>{{ __('Button Click To Action Value') }}</label>
                                        <input type="text" class="form-control input_action0" name="buttons[0][action]" maxlength="50" placeholder="https://www.google.com/" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <button type="submit" class="btn btn-outline-primary submit-button float-left">{{ __('Save Template') }}</button>
            </div>

        </div>
    </form>
</div>

                            <div class="tab-pane fade" id="mode_6" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.template.store-now','text-with-button') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                       <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Template Name:') }}</label>
                                            <input type="text" name="template_name" required="" placeholder="{{ __('Enter Your Template Name') }}" class="form-control">
                                        </div>
                                        </div>   
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Message Caption') }}</label>
                                                <textarea class="form-control h-100" name="message" required="" maxlength="1000"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('header Text (Optional)') }}</label>
                                                <input type="text" class="form-control" name="footer_text" required="" autofocus="" maxlength="100" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Footer Text (Optional)' ) }}</label>
                                                <input type="text" class="form-control" name="footer_text" required="" autofocus="" maxlength="100" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12" id="list-button-appendarea-2">
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
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-outline-primary submit-button float-left">{{ __('Save Template') }}</button>
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="mode_5" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.template.store-now','text-with-list') }}" class="ajaxform_reset_form">
                                     @csrf
                                      <div class="row">
                                       <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Template Name:') }}</label>
                                            <input type="text" name="template_name" required="" placeholder="{{ __('Enter Your Template Name') }}" class="form-control">
                                        </div>
                                    </div>   
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Title (Header)') }}</label>
                                                <input  type="text" class="form-control" name="header_title" placeholder="{{ __('Example: Amazing boldfaced list title') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-row mb-1">
                                                <label class="col-6">{{ __('Message:') }}</label>
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control h-200" name="message" required="" maxlength="1000"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Footer Text') }}</label>
                                                <input  type="text" class="form-control" name="footer_text" placeholder="{{ __('Example: Thank you') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Button Text for select option') }}</label>
                                                <input  type="text" class="form-control" name="button_text" placeholder="{{ __('Example: Required, text on the button to view the list') }}" value="" required=""  maxlength="50" />
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
                                                    <div class="card card-primary card-item">
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

                                        
                                        
                                       <div class="col-sm-12">
                                            <button type="submit" class="btn btn-outline-primary submit-button float-left">{{ __('Save Template') }}</button>
                                        </div>
                                  </div>
                              </form>
                            </div>
                             <div class="tab-pane fade" id="mode_7" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.template.store-now','text-with-location') }}" class="ajaxform_instant_reload">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Template Name:') }}</label>
                                                <input type="text" name="template_name" required="" placeholder="{{ __('Enter Your Template Name') }}" class="form-control">
                                            </div>
                                        </div>   
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Latitude') }}</label>
                                                <input type="number" step="any" name="degreesLatitude" required="" class="form-control" placeholder="Example: 24.121231">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Longitude') }}</label>
                                                <input type="number" step="any" name="degreesLongitude" required="" class="form-control" placeholder="Example: 55.1121221">
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-outline-primary submit-button float-left">{{ __('Save Template') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="mode_6" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="POST" action="{{ route('user.template.store-now','text-with-vcard') }}" class="ajaxform_reset_form">
                                    @csrf
                                    <div class="row">
                                       
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Display Name') }}</label>
                                                <input  type="text" class="form-control" name="display_name" placeholder="{{ __('Enter Display Name') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="card card-primary">
                                            <div class="card-body">
                                                <div class="row">
                                                <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Full Name (VCARD)') }}</label>
                                                <input  type="text" class="form-control" name="full_name" placeholder="{{ __('Enter Full Name') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('Organization of the contact (VCARD)') }}</label>
                                                <input  type="text" class="form-control" name="org_name" placeholder="{{ __('Enter Organization Name') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('User Contact Number (VCARD)') }}</label>
                                                <input  type="text" class="form-control" name="contact_number" placeholder="{{ __('Enter Contact Full Number') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{ __('User Whatsapp Number (VCARD)') }}</label>
                                                <input  type="text" class="form-control" name="wa_number" placeholder="{{ __('Enter Whatsapp Full Number') }}" value="" required=""  maxlength="50" />
                                            </div>
                                        </div>
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="custom-control custom-checkbox mb-3">
                                            <input class="custom-control-input" id="plain-text-vcard" type="checkbox" name="templatestatus">
                                            <label class="custom-control-label pt-1" for="plain-text-vcard">{{ __('Save this as a template') }}</label>
                                        </div>
                                            <button type="submit" class="btn btn-outline-primary submit-button">{{ __('Save Template') }}</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="help-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Shortcode') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-flush">

          <li class="list-group-item"><p><b>Note: </b>If you want custom parameter for API. Use <strong>{1}</strong> maximum parameter limit is 5. Like  <strong>{1},{2},{3},{4},{5}</strong> <br> Example: You made a purchase for <b>{1}</b> using a credit card ending in <b>{2}</b></p></li>

        </ul>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-neutral" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/pages/bulk/template.js') }}"></script>
<script type="text/javascript" src ="{{ asset('assets/js/pages/user/template-create.js') }}"></script>
@endpush


