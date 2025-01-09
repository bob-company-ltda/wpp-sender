@extends('layouts.main.app')
@section('head')
    @php
        $hideDefaultReplyButton = false;
        $hideChatgptButton = false;
        $hideNameFetcher = false;
        foreach ($replies ?? [] as $reply) {
            if ($reply->keyword == 'default' && $reply->match_type == 'like' && isset($reply->cloudapi)) {
                $hideDefaultReplyButton = true;
            }
            if($reply->reply_type == 'ChatGPT' && $reply->match_type == 'like' && isset($reply->cloudapi)){
            $hideChatgptButton = true;
            }
            
            
            
        }
        
    @endphp

    @php
    $buttons = [
        [
            'name' => '<i class="fas fa-plus"></i> &nbspCreate Reply',
            'url' => '#',
            'components' => 'data-toggle="modal" data-target="#send-template-bulk" id="send-template-bulks"',
            'is_button' => true,
        ]
    ];

    if (!$hideDefaultReplyButton) {
        $buttons[] = [
            'name' => '<i class="fas fa-circle"></i> &nbspDefault Reply',
            'url' => '#',
            'components' => 'data-toggle="modal" data-target="#send-template-default" id="send-template-defaults"',
            'is_button' => true,
        ];
    }
    if(isset($pluginConfigs['chatgpt_reply']) && $pluginConfigs['chatgpt_reply']['status'] === 1){
    if (getUserPlanData('chatgpt_reply') == true){
    if(!$hideChatgptButton){
    $buttons[] = [
            'name' => '<i class="fas fa-robot"></i> &nbspChat GPT',
            'url' => '#',
            'components' => 'data-toggle="modal" data-target="#chat-gpt" id="chat-gpts"',
            'is_button' => true,
        ];
    }
    }
    }
@endphp

@include('layouts.main.headersection', [
    'title' => 'Automatic Replies',
    'buttons' => $buttons
])


@endsection

@section('content')
<div class="row justify-content-center">
	<div class="col-12">
		<div class="armi">
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="armi">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 total-transfers" >
									{{ number_format($total_replies) }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class="fas  fa-robot"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Total Replies') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="armi">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 total-transfers" >
									{{ number_format($template_replies) }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class="fi fi-rs-test mt-2"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Template Replies') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="armi">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 completed-transfers" >
									{{ number_format($text_replies) }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class=" fi-rs-text-check mt-2"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Text Replies') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
		</div>
		@if(getUserPlanData('chatbot') == false)
		<div class="row">
			<div class="col-sm-12">
				<div class="alert bg-gradient-primary text-white alert-dismissible fade show" role="alert">
					<span class="alert-icon"><i class="fi  fi-rs-info text-white"></i></span>
					<span class="alert-text">
						<strong>{{ __('!Opps ') }}</strong> 

						{{ __('Chatbot features is not available in your subscription plan') }}
						
					</span>
				</div>
			</div>
		</div>
		@endif
		@if(count($replies ?? []) == 0)
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<center>
							<img src="{{ asset('assets/img/404.jpg') }}" height="500">
							<h3 class="text-center">{{ __('!Opps You Have Not Created Automatic Reply') }}</h3>
							<a href="#" data-toggle="modal" data-target="#send-template-bulk" id="send-template-bulks" class="btn btn-neutral"><i class="fas fa-plus"></i> {{ __('Create a reply') }}</a>
							<a href="#" data-toggle="modal" data-target="#send-template-default" id="send-template-defaults" class="btn btn-neutral"><i class="fas fa-star"></i> {{ __('Create a Default Reply') }}</a>
							@if(isset($pluginConfigs['chatgpt_reply']) && $pluginConfigs['chatgpt_reply']['status'] === 1)
							@if (getUserPlanData('chatgpt_reply') == true)
							<a href="#" data-toggle="modal" data-target="#chat-gpt" id="chat-gpts" class="btn btn-neutral"><i class="fas fa-star"></i> {{ __('Create a ChatGPT Relpy') }}</a>
							@endif
							@endif
						</center>
					</div>
				</div>
			</div>
		</div>
		@else
		<div class="card">
			
			<div class="card-body">
			<div class="card-header pb-0">
              <h3 style="font-weight:700;">Auto Responder</h6>
            </div>
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table class="table col-12">
							<thead>
								<tr>
									<th>{{ __('Keyword') }}</th>
									<!--th>{{ __('Device') }}</th-->
									<th>{{ __('Reply Type') }}</th>
									<th>{{ __('Keyword Match Type') }}</th>
									<th class="text-right">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody class="tbody">
								@foreach($replies ?? [] as $reply)
								@if ($reply->keyword != 'default' && $reply->match_type != 'like' && $reply->reply_type != 'NameFetcher' )
								<tr>
									<td>{{ $reply->keyword }}</td>
									<!--td>{{ $reply->cloudapi->phone ?? '' }}</td-->
									<td><span class="badge badge-sm @if($reply->reply_type=='template') bg-gradient-success @else bg-gradient-secondary @endif">{{ $reply->reply_type }}</span></td>
									<td>{{ $reply->match_type == 'equal' ? 'Whole Word' : 'Similar Word' }}</td>
									<td>
										<div class="btn-group mb-2 float-right">
											<button class="btn btn-ar btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												{{ __('Action') }}
											</button>
											<div class="dropdown-menu">
												<a class="dropdown-item has-icon edit-reply" href="#" 
												data-action="{{ route('user.chatbot.update',$reply->id) }}" 
												data-templateid="{{ $reply->template_id }}"
												data-keyword="{{ $reply->keyword }}"  
												data-reply="{{ $reply->reply }}"
												data-matchtype="{{ $reply->match_type }}"
												data-replytype="{{ $reply->reply_type }}"
												data-device="{{ $reply->cloudapi_id }}"
												data-toggle="modal" 
												data-target="#editModal"
												>
												<i class="ni ni-align-left-2"></i>{{ __('Edit') }}</a>
												<a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-action="{{ route('user.chatbot.destroy',$reply->id) }}"><i class="fas fa-trash"></i>{{ __('Remove Reply') }}</a>
											</div>
										</div>
									</td>
								</tr>
								@endif
								@endforeach
							</tbody>
						</table>
						<div class="d-flex justify-content-center">{{ $replies->links('vendor.pagination.bootstrap-4') }}</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			
			<div class="card-body">
			<div class="card-header pb-0">
              <h3 style="font-weight:700;">Other Functions</h6>
            </div>
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table class="table col-12">
							<thead>
								<tr>
									<th>{{ __('Function') }}</th>
									<th>{{ __('Reply Type') }}</th>
									<th class="text-right">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody class="tbody">
    @foreach($replies ?? [] as $reply)
        @if ($reply->keyword == 'default' && $reply->match_type == 'like' || $reply->keyword == 'automatic' && $reply->match_type == 'like' || $reply->reply_type == 'NameFetcher')
            <tr>
                <td>
                    @if ($reply->reply_type == 'NameFetcher') {{ $reply->reply_type }} @else {{ $reply->keyword }} @endif</td>
                <td><span class="badge badge-sm @if($reply->reply_type=='template') bg-gradient-success @else bg-gradient-secondary @endif">{{ $reply->reply_type }}</span></td>
                
                <td>
                    <div class="btn-group mb-2 float-right">
                        <button class="btn btn-ar btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                        </button>
                        <div class="dropdown-menu">
                            
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-action="{{ route('user.chatbot.destroy',$reply->id) }}"><i class="fas fa-trash"></i>{{ __('Remove Reply') }}</a>
                        </div>
                    </div>
                </td>
            </tr>
        @endif
    @endforeach
</tbody>

						</table>
						<div class="d-flex justify-content-center">{{ $replies->links('vendor.pagination.bootstrap-4') }}</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>

<div class="modal fade" id="name-fetcher" tabindex="-1" aria-labelledby="editModal2" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form type="POST" action="{{ route('user.chatbot.store') }}" class="ajaxform_instant_reload">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Create Name Fetcher Function') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<input type="hidden" name="reply_type" class="form-control" value="NameFetcher">
				<div class="modal-body">
							@foreach($cloudapis as $cloudapi)
							<input type="hidden" name="cloudapi" value="{{  $cloudapi->id }}">
							@endforeach
					
					<div class="form-group">
						<label>{{ __('Enter Name Prompt Message') }}</label>
						<input type="text" name="keyword" class="form-control" value="" placeholder="Example: Enter your Name Only" maxlength="50">
					</div>
					
					<div class="form-group">
						<label>{{ __('Name Group') }}</label>
						<select  class="form-control" name="match_type">
						    @foreach($groups ?? [] as $group)
							<option value="{{ $group->id }}">{{ $group->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group text-area">
						<label>{{ __('Success Message ') }}</label>
						<textarea class="form-control" name="reply" placeholder="Example: Start with keyword *Hello*" maxlength="1000"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-neutral submit-btn float-right">{{ __('Create Now') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>
@if(isset($pluginConfigs['chatgpt_reply']) && $pluginConfigs['chatgpt_reply']['status'] === 1)
@if(getUserPlanData('chatgpt_reply') == true)
@dynamicInclude('ChatGPTReply', 'models')
@endif
@endif
<div class="modal fade" id="send-template-default" tabindex="-1" aria-labelledby="editModal2" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form type="POST" action="{{ route('user.chatbot.store') }}" class="ajaxform_instant_reload">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Create Default Reply') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<input type="hidden" name="keyword" class="form-control" value="default" maxlength="50">
				<div class="modal-body">
					@foreach($cloudapis as $cloudapi)
							<input type="hidden" name="cloudapi" value="{{  $cloudapi->id }}">
							@endforeach
					<div class="form-group">
						<label>{{ __('Reply Type') }}</label>
						<select  class="form-control reply_type" name="reply_type">
							<option value="text">{{ __('Plain Text') }}</option>
							<option value="template">{{ __('Template') }}</option>
						</select>
					</div>
					<div class="form-group">
						<label>{{ __('Reply Function') }}</label>
						<select  class="form-control" name="match_type">
							<option value="default-reply">{{ __('Default') }}</option>
							
						</select>
					</div>
					<div class="form-group text-area">
						<label>{{ __('Reply') }}</label>
						<textarea class="form-control" name="reply" maxlength="1000"></textarea>
					</div>			
					<div class="form-group templates none" id="templates-area">
            <label>{{ __('Select Template') }}</label>
            <select class="form-control" name="template" id="templateid" onchange="updateInputFieldsDefault(this)">
                <option value="">Select a Template</option>
               @foreach($templates ?? [] as $template)
                    <option value="{{ $template->id }}" data-template-raw="{{ json_encode($template['body']) }}">
                        {{ $template->title }} - @if($template->type == 'meta-template') (meta) @elseif ($template->type == 'text-with-list') (List) @else Others  @endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="card-body" style="justify-content: flex-end; text-align: right; background-image:url({{ asset('assets/img/bg.png') }});">
           <div class="card" id="previewElement" style="min-width: 18rem; text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
               <div id="documentPrev1"></div>
               <div id ="imagePrev1"></div>
               <div id ="videoPrev1"></div>
               <div class="card-body" style="">
                   <h4 id="headertext1" class="card-title mb-2"></h4>
                   <p id="combody1" class="card-text"></p>
                   <span id="footerPrev" class="text-muted text-xs"></span>
                </div>
                   
           </div>
            <div id ="buttonsPrev"></div>
       </div>

        <div class="form-group header-parameters" id="header-variable1">
            <!-- Input fields for header parameters will be added here -->
        </div>

        <div class="form-group message-parameters" id="body-variable1">
            <!-- Input fields for message parameters will be added here -->
        </div>			
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-neutral submit-btn float-right">{{ __('Create Now') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="send-template-bulk" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form type="POST" action="{{ route('user.chatbot.store') }}" class="ajaxform_instant_reload">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Create Reply') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>{{ __('Keyword') }}</label>
						<input type="text" name="keyword" class="form-control" name="keyword" maxlength="50">
					</div>
					
							@foreach($cloudapis as $cloudapi)
							<input type="hidden" name="cloudapi" value="{{  $cloudapi->id }}">
							@endforeach
						
					<div class="form-group">
						<label>{{ __('Reply Type') }}</label>
						<select  class="form-control reply_type" name="reply_type">
							<option value="text">{{ __('Plain Text') }}</option>
							<option value="template">{{ __('Template') }}</option>
						</select>
					</div>
					<div class="form-group">
						<label>{{ __('Keyword Match Type') }}</label>
						<select  class="form-control" name="match_type">
							<option value="equal">{{ __('Whole Words') }}</option>
							
						</select>
					</div>
					<div class="form-group text-area">
						<label>{{ __('Reply') }}</label>
						<textarea class="form-control" name="reply" maxlength="1000"></textarea>
					</div>			
					<div class="form-group templates none" id="templates-area">
            <label>{{ __('Select Template') }}</label>
            <select class="form-control" name="template" id="templateid" onchange="updateInputFields(this)">
                <option value="none">Select a template</option>
                @foreach($templates ?? [] as $template)
                    <option value="{{ $template->id }}" data-template-raw="{{ json_encode($template['body']) }}">
                        {{ $template->title }} - @if($template->type == 'meta-template') (meta) @elseif ($template->type == 'text-with-list') (List) @else Others  @endif
                    </option>
                    
                @endforeach
            </select>
        </div>
       <div class="card-body" style="justify-content: flex-end; text-align: right; background-image:url({{ asset('assets/img/bg.png') }});">
           <div class="card" id="previewElement" style="min-width: 18rem; text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
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

        <div class="form-group header-parameters" id="header-variable">
            <!-- Input fields for header parameters will be added here -->
        </div>

        <div class="form-group message-parameters" id="body-variable">
            <!-- Input fields for message parameters will be added here -->
        </div>				
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-neutral submit-btn float-right">{{ __('Create Now') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		<form type="POST" action="" class="ajaxform_instant_reload edit-reply-form">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Reply') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>{{ __('Keyword') }}</label>
            <input type="text" name="keyword" class="form-control" id="keyword" required="" maxlength="50" name="keyword">
        </div>
        @foreach($cloudapis as $cloudapi)
							<input type="hidden" name="cloudapi" value="{{  $cloudapi->id }}">
							@endforeach

        <div class="form-group">
            <label>{{ __('Reply Type') }}</label>
            <select class="form-control reply_type" name="reply_type" id="replytype">
                <option value="text">{{ __('Plain Text') }}</option>
                <option value="template">{{ __('Template') }}</option>
            </select>
        </div>
        <div class="form-group">
            <label>{{ __('Keyword Match Type') }}</label>
            <select class="form-control" name="match_type" id="matchtype">
                <option value="equal">{{ __('Whole Words') }}</option>
            </select>
        </div>
        <div class="form-group text-area" id="reply-area">
            <label>{{ __('Reply') }}</label>
            <textarea class="form-control" name="reply" maxlength="1000" id="reply"></textarea>
        </div>

        <div class="form-group templates none" id="templates-area">
            <label>{{ __('Select Template') }}</label>
            <select class="form-control" name="template" id="templateid" onchange="updateInputFieldsEdit(this)">
                @foreach($templates ?? [] as $template)
                    <option value="{{ $template->id }}" data-template-raw="{{ json_encode($template['body']) }}">
                        {{ $template->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="card-body" style="justify-content: flex-end; text-align: right; background-image:url({{ asset('assets/img/bg.png') }}) }}');">
           <div class="card" id="previewElement2" style="min-width: 18rem; text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
               <div id="documentPrev2"></div>
               <div id ="imagePrev2"></div>
               <div class="card-body" style="">
                   <h4 id="headertext2" class="card-title mb-2"></h4>
                   <p id="combody2" class="card-text"></p>
                   <span id="footerPrev2" class="text-muted text-xs"></span>
                </div>
                   
           </div>
            <div id ="buttonsPrev2"></div>
       </div>

        <div class="form-group header-parameters" id="header-variable2">
            <!-- Input fields for header parameters will be added here -->
        </div>

        <div class="form-group message-parameters" id="body-variable2">
            <!-- Input fields for message parameters will be added here -->
        </div>	
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-neutral submit-btn float-right">{{ __('Update Reply') }}</button>
    </div>
</form>

		</div>
	</div>
</div>

<div class="modal fade" id="editModal2" tabindex="-1" aria-labelledby="editModal2" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		<form type="POST" action="" class="ajaxform_instant_reload edit-reply-form">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Default Reply') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
	<input type="hidden" name="keyword" class="form-control" id="keyword" required="" maxlength="50" value="default">
        <div class="form-group">
            <label>{{ __('Select CloudApi') }}</label>
            <select class="form-control" name="cloudapi" id="device">
                @foreach($cloudapis as $cloudapi)
                    <option value="{{ $cloudapi->id }}">{{ $cloudapi->name . ' - ' . $cloudapi->phone }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('Reply Type') }}</label>
            <select class="form-control reply_type" name="reply_type" id="replytype">
                <option value="text">{{ __('Plain Text') }}</option>
                <option value="template">{{ __('Template') }}</option>
            </select>
        </div>
        <div class="form-group">
            <label>{{ __('Reply Function') }}</label>
            <select class="form-control" name="match_type" id="matchtype">
                <option value="default-reply">{{ __('Default') }}</option>
            </select>
        </div>
        <div class="form-group text-area" id="reply-area">
            <label>{{ __('Reply') }}</label>
            <textarea class="form-control" name="reply" maxlength="1000" id="reply"></textarea>
        </div>

        <div class="form-group templates none" id="templates-area">
            <label>{{ __('Select Template') }}</label>
            <select class="form-control" name="template" id="templateid">
                <option value="">Select a Template</option>
                @foreach($templates ?? [] as $template)
				<option value="{{ $template->id }}"
                            data-header-parameters="{{ isset($template['body']['headerParameters']) ? json_encode($template['body']['headerParameters']) : '' }}"
                            data-message-parameters="{{ isset($template['body']['messageParameters']) ? json_encode($template['body']['messageParameters']) : '' }}">
                        {{ $template->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group header-parameters" id="header-parameters">
            <!-- Input fields for header parameters will be added here -->
        </div>

        <div class="form-group message-parameters" id="message-parameters">
            <!-- Input fields for message parameters will be added here -->
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-neutral submit-btn float-right">{{ __('Update Default Reply') }}</button>
    </div>
</form>

		</div>
	</div>
</div>
<script>
    
</script>
@endsection
@push('js')
<script src="{{ asset('assets/js/pages/user/chatbot.js') }}"></script>
@endpush