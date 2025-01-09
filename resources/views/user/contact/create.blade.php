@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['buttons'=>[
	[
		'name'=>'Back',
		'url'=> route('user.contact.index'),
	]
]])
@endsection
@section('content')
<div class="row justify-content-center">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>{{ __('Create Contact') }}</h4>
			</div>
			<div class="card-body">
				<form method="POST" class="ajaxform_reset_form" action="{{ route('user.contact.store') }}">
					@csrf				
				<div class="form-group row mb-4">
					<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('User Name') }}</label>
					<div class="col-sm-12 col-md-7">
						<input type="text" name="name" placeholder="Jhone Doe" maxlength="50" class="form-control">
					</div>
				</div>
				<div class="form-group row mb-4">
					<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Whatsapp Number') }}</label>
					<div class="col-sm-12 col-md-7">
						<input type="number" name="phone" placeholder="{{ __('Enter Phone Number With Country Code') }}" maxlength="15" class="form-control">
					</div>
					</div>
					<div class="form-group row mb-4">
					<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Parameters(Optional)') }}</label>
					<div class="col-sm-12 col-md-7">
						<input type="text" name="param" id="paramInput" placeholder="{{ __('param1, param2, param3, param4 ......') }}" class="form-control" oninput="limitParameters()">
                        <small class="text-danger" id="paramErrorMessage">You can add up to 7 parameters here with a comma</small>
                    </div>
					</div>
				<div class="form-group row mb-4">
					<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Select Group') }}</label>
					<div class="col-sm-12 col-md-7">
						<select name="group" class="form-control">
							@foreach($groups as $group)
							<option value="{{ $group->id }}">{{ $group->name }}</option>
							@endforeach
						</select>
					</div>
				</div>	
											
				<div class="form-group row mb-4">
					<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
					<div class="col-sm-12 col-md-7">
						<button type="submit" class="btn btn-outline-primary submit-btn">{{ __('Create Now') }}</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
    function limitParameters() {
        var input = document.getElementById("paramInput");
        var errorMessage = document.getElementById("paramErrorMessage");
        
        // Splitting the input value by commas and counting the number of parameters
        var parameterCount = input.value.split(",").length;
        
        // Display an error message if more than 7 parameters are entered
        if (parameterCount > 7) {
            errorMessage.style.display = "block";
            input.value = input.value.substring(0, input.value.lastIndexOf(","));
        } else {
            errorMessage.style.display = "none";
        }
    }
</script>

@endsection