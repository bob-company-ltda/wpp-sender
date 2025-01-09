@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',[
'title' => __('Contact List'),
'buttons' =>[
	[
		'name'=>__('Create Contact'),
		'url'=> route('user.contact.create'),
	],
	[
		'name'=>__('Contact Groups'),
		'url'=> route('user.group.index'),
	],
	[
		'name'=>__('Import Contacts'),
		'url'=>'#',
		'components'=>'data-toggle="modal" data-target="#import-contact"', 
		'is_button'=>true
	]
]])
@endsection
@push('topcss')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">

@endpush
@section('content')
<div class="row justify-content-center">
	<div class="col-12">
		<div class="armi">
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="armi">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 total-transfers" id="total-device">
									{{ $total_contacts }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class="fi  fi-rs-address-book mt-2"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Total Contacts') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="armi">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 total-transfers" id="total-active">
								  {{ $limit }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class="fas fa-signal"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Contacts statics') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
			
		</div>

		@if(count($contacts ?? []) > 0)
		<div class="card">
			
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table class="table col-12">
							<thead>
								<tr>
									<th class="col-3">{{ __('Contact Name') }}</th>
									<th class="col-4">{{ __('Group') }}</th>
									<th class="col-7 text-right">{{ __('Whatsapp Number') }}</th>
									
									<th class="col-2 text-right">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody class="tbody">
								@foreach($contacts ?? [] as $contact)
								<tr>
									<td>
										{{ $contact->name }}
									</td>
									<td>
										@foreach($contact->groupcontact as $groupcontact)
										<span class="badge badge-primary">{{ $groupcontact->name }}</span>
										@endforeach
									</td>
									<td class="text-right">
										{{ $contact->phone }}
									</td>									
									<td>
										<div class="btn-group mb-2 float-right">
											<button class="btn btn-neutral btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												{{ __('Action') }}
											</button>
											<div class="dropdown-menu">
												<a class="dropdown-item has-icon edit-contact" href="#" 
												data-action="{{ route('user.contact.update',$contact->id) }}" 
												data-name="{{ $contact->name }}"  
												data-phone="{{ $contact->phone }}"
												data-groupid="{{ $contact->groupcontact[0]->id ?? '' }}"
												data-param = "{{ implode(',', array_filter([$contact->param1, $contact->param2, $contact->param3, $contact->param4, $contact->param5, $contact->param6, $contact->param7])) }}"
												data-toggle="modal" 
												data-target="#editModal"
												>
												<i class="ni ni-align-left-2"></i>{{ __('Edit') }}</a>
												<a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-action="{{ route('user.contact.destroy',$contact->id) }}"><i class="fas fa-trash"></i>{{ __('Remove Number') }}</a>
											</div>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="d-flex justify-content-center">{{ $contacts->links('vendor.pagination.bootstrap-4') }}</div>
					</div>
				</div>
			</div>
		</div>
		@else
		<div class="alert  bg-gradient-primary text-white"><span class="text-left">{{ __('Opps There Is No Contact Found....') }}</span></div>
		@endif
	</div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form type="POST" action="" class="edit-modal ajaxform_instant_reload">
				@csrf
				@method('PUT')

				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Contact') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>{{ __('User Name') }}</label>
						<input type="text" name="name" id="user_name" placeholder="Jhone Doe" maxlength="50" class="form-control" required="">
					</div>
					<div class="form-group">
						<label>{{ __('Whatsapp Number') }}</label>
						<input type="number" name="phone" id="user_phone" placeholder="{{ __('Enter Phone Number With Country Code') }}" maxlength="15" class="form-control">
					</div>
					<div class="form-group">
						<label>{{ __('Parameters') }}</label>
						<input type="text" name="param" id="paramInput" placeholder="{{ __('param1, param2, param3, param4 ......') }}" class="form-control" oninput="limitParameters()">
                        <small class="text-danger" id="paramErrorMessage">You can add up to 7 parameters here with a comma</small>
					</div>
					<div class="form-group">
						<label>{{ __('Select Group') }}</label>
						<select name="group" class="form-control" id="group-list">
							@foreach($groups as $group)
							<option value="{{ $group->id }}">{{ $group->name }}</option>
							@endforeach
						</select>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
					<button type="submit" class="btn btn-primary submit-btn">{{ __('Save changes') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="import-contact" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form type="POST" action="{{ route('user.contact.import') }}" class="ajaxform" enctype="multipart/form-data">
				@csrf
				

				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Import Contact') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>{{ __('Select CSV') }} <a href="{{ asset('uploads/demo-contact.csv') }}" download="">{{ __('(Download Sample)')  }}</a></label>
						<input type="file" accept=".csv" name="file"  class="form-control" required="">
					</div>	

					<div class="form-group">
						<label>{{ __('Select Group') }}</label>
						<select name="group" class="form-control" >
							@foreach($groups as $group)
							<option value="{{ $group->id }}">{{ $group->name }}</option>
							@endforeach
						</select>
					</div>									
				</div>

				

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
					<button type="submit" class="btn btn-primary submit-btn">{{ __('Import') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
@push('topjs')
<script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>
@endpush
@push('js')
<script>
    // Submit the form when it's ready
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.bulk_send_form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // Submit the form using AJAX
            sendFormWithAjax();
        });
    });

    function sendFormWithAjax() {
        const form = document.querySelector('.bulk_send_form');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Redirecting to bulk sending page' && data.redirect) {
                // Redirect to the URL provided in the JSON response
                window.location.href = data.redirect;
            } else {
                // Handle other responses or errors here
                console.error('Unexpected response:', data);
            }
        })
        .catch(error => {
            // Handle fetch errors here
            console.error('Fetch error:', error);
        });
    }
</script>
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
<script src="{{ asset('assets/js/pages/user/contact.js') }}"></script>
@endpush
