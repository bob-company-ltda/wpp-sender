@extends('layouts.main.app')
@push('css')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('head')
@include('layouts.main.headersection',['buttons'=>[
[
'name'=>'Back',
'url'=> route('user.schedule-message.index'),
]
]])
@endsection
@section('content')
<style>
    @media only screen and (min-width: 768px) {
        .mgh{
            width:50%;
        }
    }
    video{
        width:100%;
    }
</style>
<div class="row justify-content-center">
	<div class="col-12">
		<div class="card">
			<div class="card-header row">
				<h4 class="text-left col-6">{{ __('Create New Campaign') }}</h4>
				
			</div>
			<div class="card-body">
				<form method="POST" action="{{ route('user.schedule-message.store') }}" class="ajaxform_reset_form" enctype="multipart/form-data">
					@csrf
					
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>{{ __('Campaign Name') }}</label>
								<input type="text" name="title" class="form-control" required="">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>{{ __('Select WhatsApp Number') }}</label>
								<select class="form-control"  name="cloudapi" required="">
									@foreach($cloudapis as $cloudapi)
									<option value="{{ $cloudapi->id }}">{{ $cloudapi->phone }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>{{ __('Receiver Group') }}</label>								
								<select class="form-control"  name="group" >
									@foreach($groups as $group)
									<option value="{{$group->id}}">{{$group->name}} - ({{ $group->contacts_count }} {{ __('Contacts') }})</option>
									@endforeach
								</select>
								
							</div>
						</div>
					</div>
					<div class="row">
					<div class="col-sm-6">
							<label>{{ __('Delivery date and time') }}</label>
							<div class="input-group">							

								
								<input type="datetime-local" name="date" class="form-control" required="" min="{{ now()->format('Y-m-d') }}">
								<div class="input-group-append">
									<select name="timezone" class="form-control" required="">
										<option value="" selected="" disabled="">{{ __('Select TImezone') }}</option>
										@foreach (timezone_identifiers_list() as $timezone)
										<option value="{{ $timezone }}" >
											{{ $timezone }}
										</option>
										@endforeach
									</select>
								</div>						


							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>{{ __('Messaging Type') }}</label>
								<select class="form-control message_type"  name="message_type" required="">
									<option value="text">{{ __('Text Message') }}</option>
									<option value="template">{{ __('Template Message') }}</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row plain-text">
						<div class="col-sm-12 plain-text">
							<div class="form-group">
								<label>{{ __('Message') }}</label>
								<textarea class="form-control h-200" name="message" required="" maxlength="1000" placeholder="{{ __('note : Write your Campaign') }}"></textarea>
							</div>
						</div>
						
					</div>
					<div class="templates-list none">
						<div class="form-group">
							<label>{{ __('Select Template') }}</label>
							<select  class="form-control" name="template" id="templateid" onchange="updateInputFields(this)">
							    <option>Choose a template</option>
                     @foreach($templates as $template)
        <option value="{{ $template->id }}" data-template-raw="{{ json_encode($template['body']) }}">
                        {{ $template->title }} - @if($template->type == 'meta-template') (meta) @elseif ($template->type == 'text-with-list') (List) @else Others  @endif
                    </option>
@endforeach

                  </select>
							<small class="text-danger">Currently, only static templates are functional. The availability of parameters is expected soon.</small>
						</div>
						<div class="justify-content-center" style="display:flex;">
						<div class="card shadow mgh">
						<div class="card-body" style="justify-content: flex-end; text-align: right; background: url('{{ asset('assets/img/bg.png') }}');">
           <div class="card" id="previewElement" style="text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
               <div id="documentPrev"></div>
               <div id ="imagePrev"></div>
               <div id ="videoPrev"></div>
               <div class="card-body" style="">
                   <h4 id="headertext" class="card-title mb-2"></h4>
                   
                   <p id="combody" class="card-text"></p>
                   <span id="footerPrev" class="text-muted text-xs"></span>
                </div>
                   
           </div>
            <div id ="buttonsPrev"></div>
       </div>
       </div>
       </div>

        <div class="form-group header-parameters" id="header-variable">
            <!-- Input fields for header parameters will be added here -->
        </div>

        <div class="form-group message-parameters" id="body-variable">
            <!-- Input fields for message parameters will be added here -->
        </div>	
					</div>
					<div class="row">
						<div class="col-sm-12">
							<button type="submit" class="btn btn-outline-primary submit-button">{{ __('Create Campaign') }}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/user/schedule-create.js') }}"></script>
@endpush