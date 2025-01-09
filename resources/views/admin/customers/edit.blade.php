@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',[
'title'=> __('Edit User'),
'buttons'=>[
    [
        'name'=>__('Back'),
        'url'=>route('admin.customer.index'),
    ]
]

])
@endsection
@section('content')
<div class="row ">
	<div class="col-lg-5 mt-5">
        <strong>{{ __('Edit User') }}</strong>
        <p>{{ __('Edit user profile information') }}</p>
    </div>
    <div class="col-lg-7 mt-5">
        <form class="ajaxform ajaxform_instant_reload" action="{{ route('admin.customer.update',$customer->id) }}">
        	@csrf
        	@method('PUT')
        	<div class="card">
            <div class="card-body">
                <div class="from-group row mt-2">
                    <label class="col-lg-12">{{ __('Name') }}</label>
                    <div class="col-lg-12">
                        <input type="text" name="name" required="" class="form-control" value="{{ $customer->name }}">
                    </div>
                </div>
                <div class="from-group row mt-2">
                    <label class="col-lg-12">{{ __('Email') }}</label>
                    <div class="col-lg-12">
                        <input type="email" name="email" required="" class="form-control" value="{{ $customer->email }}">
                    </div>
                </div>
                <div class="from-group row mt-2">
                    <label class="col-lg-12">{{ __('Phone') }}</label>
                    <div class="col-lg-12">
                        <input type="text" name="phone"  class="form-control" value="{{ $customer->phone }}">
                    </div>
                </div>
                <div class="from-group row mt-2">
                    <label class="col-lg-12">{{ __('Address') }}</label>
                    <div class="col-lg-12">
                        <input type="text" name="address"  class="form-control" value="{{ $customer->address }}">
                    </div>
                </div>
                <div class="from-group row mt-2">
                    <label class="col-lg-12">{{ __('New Password') }}</label>
                    <div class="col-lg-12">
                        <input type="text" name="password"  class="form-control" value="">
                    </div>
                </div>
                
                <div class="from-group row mt-2">
                    <label class="col-lg-12">{{ __('Status') }}</label>
                    <div class="col-lg-12">
                       <select class="form-control" name="status">
                       	 <option value="1" {{ $customer->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                       	 <option value="0" {{ $customer->status == 0 ? 'selected' : '' }}>{{ __('Deactive') }}</option>
                       </select>
                    </div>
                </div>
                
                <div class="form-group mb-4">
    <label class="col-form-label" for="plan">{{ __('Assign Plan') }}</label>
    <select class="form-control selectric" name="plan" id="plan">
        @foreach ($plans as $plan)
            <option value="{{ $plan->id }}" {{ $plan->id == $assignedPlanId ? 'selected' : '' }}>
                {{ $plan->title }}
            </option>
        @endforeach
    </select>
</div>
                
                <div class="from-group row mt-2">
                    <label class="col-lg-12">{{ __('Will Expire ') }}</label>
                    <div class="col-lg-12">
                        <input type="date" name="expiry"  class="form-control" value="">
                        <small style="color:red">{{__('Generally, avoid using this method. It is reserved for occasions when you wish to extend the plan for a customer through cash payment or for personal reasons. ') }}</small>
                        <small>{{__('This process will not be recorded as a sale because it disrupts the system standard operational flow. ') }}</small>
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