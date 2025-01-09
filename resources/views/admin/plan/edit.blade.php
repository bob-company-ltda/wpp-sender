@extends('layouts.main.app')
@section('head')
@push('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css') }}"/>
@endpush
@include('layouts.main.headersection',[
'title'=> __('Edit Plan'),
'buttons'=>[
	[
		'name'=>__('Back'),
		'url'=>route('admin.plan.index'),
	]
]
])
@endsection
@section('content')
<div class="row ">
	<div class="col-lg-5 mt-5">
        <strong>{{ __('Plan') }}</strong>
        <p>{{ __('Edit subscription plan for charging from the customer') }}</p>
    </div>
    <div class="col-lg-7 mt-5">
        <form class="ajaxform_instant_reload" action="{{ route('admin.plan.update',$plan->id) }}">
        	@csrf
        	@method('PUT')
        	<div class="card">
            <div class="card-body">
                <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('Plan Name') }}</label>
                    <div class="col-lg-12">
                        <input type="text" name="title" required="" class="form-control" value="{{ $plan->title }}">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('Select Duration') }}</label>
                    <div class="col-lg-12">
                        <select class="form-control" name="days">
                        	<option value="30" {{ $plan->days == 30 ? 'selected' : ''  }}>{{ __('Monthly') }}</option>
                        	<option value="90" {{ $plan->days == 90 ? 'selected' : ''  }}>{{ __('Quarterly') }}</option>
                        	<option value="180" {{ $plan->days == 180 ? 'selected' : ''  }}>{{ __('Half Yearly') }}</option>
                        	<option value="365" {{ $plan->days == 365 ? 'selected' : ''  }}>{{ __('yearly') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('Price') }}</label>
                    <div class="col-lg-12">
                         <input type="number" name="price" step="any" required="" class="form-control" value="{{ $plan->price }}">
                    </div>
                </div>
                
                @if(isset($pluginConfigs['whatsapp_web']) && $pluginConfigs['whatsapp_web']['status'] === 1)
                 @dynamicInclude('WhatsAppWeb', 'editselector')
                 @endif
                 
                <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('Monthly Messages Limit') }}</label>
                    <div class="col-lg-12">
                         <input type="number" name="plan_data[messages_limit]" value="{{ $plan->data['messages_limit'] ?? '' }}" required="" class="form-control">
                    </div>
                </div>
                 <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('Contacts Limit') }}</label>
                    <div class="col-lg-12">
                         <input type="number" name="plan_data[contact_limit]" value="{{ $plan->data['contact_limit'] ?? '' }}" required="" class="form-control">
                    </div>
                </div>

                <input type="hidden" name="plan_data[cloudapi_limit]"  value="1" required="" class="form-control">
               
                <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('Template Limit') }}</label>
                    <div class="col-lg-12">
                         <input type="number" name="plan_data[template_limit]"  value="{{ $plan->data['template_limit'] ?? '' }}" required="" class="form-control">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('App Limit') }}</label>
                    <div class="col-lg-12">
                         <input type="number" name="plan_data[apps_limit]"  value="{{ $plan->data['apps_limit'] ?? '' }}" required="" class="form-control">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('Auto Reply') }}</label>
                    <div class="col-lg-12">
                        <select class="form-control" name="plan_data[chatbot]">
                        	<option value="true" {{ $chatbot == 'true' ? 'selected' : ''  }}>{{ __('Enabled') }}</option>
                        	<option value="false" {{ $chatbot == 'false' ? 'selected' : ''  }}>{{ __('Disabled') }}</option>
                        </select>
                    </div>
                </div>
                
                
                
                <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('Bulk Message') }}</label>
                    <div class="col-lg-12">
                        <select class="form-control" name="plan_data[bulk_message]">
                        	<option value="true" {{ $bulk_message == 'true' ? 'selected' : ''  }}>{{ __('Enabled') }}</option>
                        	<option value="false" {{ $bulk_message == 'false' ? 'selected' : ''  }}>{{ __('Disabled') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('Campaign Schedule') }}</label>
                    <div class="col-lg-12">
                        <select class="form-control" name="plan_data[run_campaign]">
                        	<option value="true" {{ $run_campaign == 'true' ? 'selected' : ''  }}>{{ __('Enabled') }}</option>
                        	<option value="false" {{ $run_campaign == 'false' ? 'selected' : ''  }}>{{ __('Disabled') }}</option>
                        </select>
                    </div>
                </div>
                
               
                <div class="form-group row mt-2">
                    <label class="col-lg-12">{{ __('Chat List Access') }}</label>
                    <div class="col-lg-12">
                        <select class="form-control" name="plan_data[access_chat_list]">
                            <option value="true" {{ $access_chat_list == 'true' ? 'selected' : ''  }}>{{ __('Enabled') }}</option>
                            <option value="false" {{ $access_chat_list == 'false' ? 'selected' : ''  }}>{{ __('Disabled') }}</option>
                        </select>
                    </div>
                </div>
                @foreach($plugins as $plugin)
    <div class="form-group row mt-2">
        <label class="col-lg-12">{{ $plugin['name'] }}</label>
        <div class="col-lg-12">
            <select class="form-control" name="plan_data[{{ $plugin['id'] }}]" id="{{ $plugin['id'] }}Select">
                <option value="false" {{ $plugin['value'] === false ? 'selected' : '' }}>{{ __('Disabled') }}</option>
                <option value="true" {{ $plugin['value'] === true ? 'selected' : '' }}>{{ __('Enabled') }}</option>
            </select>
            <small class="form-text text-muted">{{ $plugin['desc'] }}</small>
        </div>
    </div>
@endforeach
                
                <div class="row mt-2">
                	<div class="col-sm-12 d-flex">
                		<label class="custom-toggle custom-toggle-primary">
                			<input  type="checkbox"  name="is_featured" id="is-featured"  {{ $plan->is_featured == 1 ? 'checked' : ''  }}>
                			<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                		</label>
                		<label class="mt-1 ml-1" for="is-featured"><h4>&nbsp {{ __('Featured in home page?') }}</h4></label>
                	</div>
                </div>
                <div class="row mt-2">
                	<div class="col-sm-12 d-flex">
                		<label class="custom-toggle custom-toggle-primary">
                			<input type="checkbox"  name="is_recommended" id="is-recommended" {{ $plan->is_recommended == 1 ? 'checked' : ''  }}>
                			<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                		</label>
                		<label class="mt-1 ml-1" for="is-recommended"><h4>&nbsp {{ __('Is recommended?') }}</h4></label>
                	</div>
                </div>
                <div class="row mt-2">
                	<div class="col-sm-12 d-flex">
                		<label class="custom-toggle custom-toggle-primary">
                			<input type="checkbox"  name="is_trial" id="is-trial"  data-target=".trial-days" {{ $plan->is_trial == 1 ? 'checked' : ''  }}>
                			<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                		</label>
                		<label class="mt-1 ml-1" for="is-trial"><h4>&nbsp {{ __('Accept Trial?') }}</h4></label>
                	</div>
                </div>
                <div class="from-group row mt-2 trial-days {{ $plan->is_trial == 0 ? 'none' : ''  }}">
                    <label class="col-lg-12">{{ __('Trial days') }}</label>
                    <div class="col-lg-12">
                         <input type="number" name="trial_days" value="{{ $plan->trial_days }}" class="form-control">
                    </div>
                </div>
                <div class="row mt-2">
                	<div class="col-sm-12 d-flex">
                		<label class="custom-toggle custom-toggle-primary">
                			<input type="checkbox"  name="status" id="status" {{ $plan->status == 1 ? 'checked' : ''  }}>
                			<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                		</label>
                		<label class="mt-1 ml-1" for="status"><h4>&nbsp {{ __('Activate This Plan?') }}</h4></label>
                	</div>
                </div>

                 <div class="from-group row mt-3">
                    <div class="col-lg-12">
                       <button class="btn btn-neutral submit-button btn-sm float-left"> {{ __('Update') }}</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection
@push('js')
<script  src="{{ asset('assets/plugins/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js') }}"></script>
<script  src="{{ asset('assets/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js') }}"></script>
<script  src="{{ asset('assets/js/pages/admin/plan-edit.js') }}"></script>
@endpush