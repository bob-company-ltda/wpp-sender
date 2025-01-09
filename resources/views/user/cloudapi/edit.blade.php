@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['buttons'=>[
	[
		'name'=>'<i class="ni ni-curved-next"></i> Back',
		'url'=> route('user.cloudapi.index'),
	]
]])
@endsection
@section('content')


<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        
        <div class="col-lg-12 layout-spacing">
                                <div class="statbox widget box box-shadow mb-4">
                                    <form method="POST" class="ajaxform_reset_form" action="{{ route('user.cloudapi.update',$cloudapi->uuid) }}" enctype="multipart/form-data">
                                        @csrf
					@method('PUT')
                                    <div class="widget-content widget-content-area">
                                        <div class="w-100">
                                            <div class="form-group row">
                                                <div class="col-lg-4">
						                        <label>{{__('Profile Picture')}}</label>
						                        <input type="file" name="image" class="form-control-file">
						                        <span class="form-text text-muted">{{__('Required')}}</span>
						                        </div>
                                                <div class="col-lg-4">
                                                    <label>{{ __('CloudApi Name') }}</label>
                                                     <div class="input-group">
                                                    <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fab fa-whatsapp"></i>
																	</span>
                                                        </div>
                                                        
                                                    <input type="text" name="name" placeholder="Ionfirm Corporation" value="{{ $cloudapi->name }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label>{{ __('About') }}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fa fa-book"></i>
																	</span>
                                                        </div>
                                                        <textarea name="about" class="form-control scroll" rows="1">{{ $cloudapi->about ?? $response['data'][0]['about'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-4">
                                                    <label>{{ __('Address') }}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fa fa-map-marker"></i>
																	</span>
                                                        </div>
                                                    <input type="text" name="address" placeholder="123 Main St" value="{{ $cloudapi->address ?? $response['data'][0]['address'] ?? '' }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label>{{ __('Description') }}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fa fa-info-circle"></i>
																	</span>
                                                        </div>
                                                        <textarea name="description" class="form-control scroll" rows="1">{{ $cloudapi->description ?? $response['data'][0]['description'] ?? '' }}</textarea>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-lg-4">
                                                    <label>{{__('Industry')}}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fa fa-cog"></i>
																	</span>
                                                        </div>
                                                        <select name="industry" class="form-control">
            @foreach(['OTHER', 'AUTO', 'BEAUTY', 'APPAREL', 'EDU', 'ENTERTAIN', 'EVENT_PLAN', 'FINANCE', 'GROCERY', 'GOVT', 'HOTEL', 'HEALTH', 'NONPROFIT', 'PROF_SERVICES', 'RETAIL', 'TRAVEL', 'RESTAURANT', 'NOT_A_BIZ'] as $industry)
                <option value="{{ $industry }}" @if($cloudapi->industry === $industry || (isset($response['data'][0]['vertical']) && $response['data'][0]['vertical'] === $industry)) selected @endif>{{ $industry }}</option>
            @endforeach
        </select>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-4">
                                                    <label>{{ __('Email') }}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fa fa-inbox"></i>
																	</span>
                                                        </div>
                                                    <input type="email" name="email" placeholder="example@example.com" value="{{ $cloudapi->email ?? $response['data'][0]['email'] ?? '' }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label>{{__('Website')}}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fa fa-globe"></i>
																	</span>
                                                        </div>
                                                    <input type="text" name="website" placeholder="https://www.example.com" value="{{ $cloudapi->website ?? $response['data'][0]['websites'][0] ?? '' }}" class="form-control">
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-lg-4">
                                                    <label>{{__('Meta App ID')}}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fab fa-facebook"></i>
																	</span>
                                                        </div>
                                                        <input type="text" name="meta_app_id" value="{{ $cloudapi->meta_app_id }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-4">
                                                    <label>{{__('WhatsApp Phone ID')}}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fab fa-whatsapp"></i>
																	</span>
                                                        </div>
                                                   <input type="text" name="phone_number_id" value="{{ $cloudapi->phone_number_id }}" class="form-control">
                                                   </div>
                                                    
                                                </div>
                                                <div class="col-lg-4">
                                                    <label>{{__('WhatsApp Business Account ID')}}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fab fa-whatsapp"></i>
																	</span>
                                                        </div>
                                                        <input type="text" name="wa_business_id" value="{{ $cloudapi->wa_business_id }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label>{{ __('Permanent/Temporary Access Token') }}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fab fa-whatsapp"></i>
																	</span>
                                                        </div>
                                                    <input type="text" name="access_token" value="{{ $cloudapi->access_token }}" class="form-control">
                                                    </div>
                                                    
                                                </div>
                                                @if(isset($pluginConfigs['webhooks_payload']) && $pluginConfigs['webhooks_payload']['status'] === 1)
@if(getUserPlanData('webhooks_payload') == true)
                                                <div class="mt-4 col-lg-12">
                                                    <label>{{ __('Hook URL') }}<small> (Send The Payload to other Application or Leave it blank) </small></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="fab fa-web"></i>
																	</span>
                                                        </div>
                                                    <input type="text" name="webhook_url" value="{{ $cloudapi->hook_url }}" class="form-control">
                                                    </div>
                                                    
                                                </div>
                                                @endif
                                                @endif
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="widget-footer text-right">
                                        <button type="submit" class="btn btn-primary mr-2">{{__('Save Now')}}</button>
                                        
                                    </div>
                                </form>
                                </div>
                            </div>
    </div>
</div>



@endsection
