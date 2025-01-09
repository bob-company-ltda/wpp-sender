@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',[
'title'=> __('Add New Customer'),
'buttons'=>[
	[
		'name'=>__('Back'),
		'url'=>route('admin.customer.index'),
	]
]])
@endsection
@section('content')
<div class="row ">
	<div class="col-lg-5 mt-5">
		<strong>{{ __('Add New Customer') }}</strong>
		<p>{{ __('Manually Add new Customer') }}</p>
	</div>
	<div class="col-lg-7 mt-5">
		<form class="ajaxform_instant_reload" action="{{ route('admin.customer.store') }}" enctype="multipart/form-data">
			@csrf
			<div class="card">
				<div class="card-body">
					<div class="form-group mb-4">
						<label class="col-form-label text-md-right required" for="name">{{ __('Name') }}</label>
						<input type="text" class="form-control" name="name" id="name"  required>
					</div>
					<div class="form-group mb-4">
						<label class="col-form-label text-md-right required" for="email">{{ __('Email') }}</label>
						<input type="email" class="form-control" name="email" id="email"  required>
					</div>
					
					<div class="form-group mb-4">
						<label class="col-form-label text-md-right required" for="password">{{ __('Password') }}</label>
						<input type="password" class="form-control" name="password" id="password"  required>
					</div>
					<div class="form-group mb-4">
                        <label class="col-form-label text-md-right required" for="plan">{{ __('Assign Plan') }}</label>
                        <select class="form-control selectric" name="plan">
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->title }}</option>
                            @endforeach
                        </select>
                    </div>

					<div class="form-group mb-4">
						<button class="btn btn-neutral submit-button">{{ __('Update') }}</button>
					</div>	
				</div>
			</div>
		</form>
	</div>
</div>


@endsection