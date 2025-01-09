@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=> __('CloudApis')])
@endsection
@section('content')
<div class="row justify-content-center">
	<div class="col-12">
		<div class="row d-flex justify-content-between flex-wrap">
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 total-transfers" id="total-device">
									{{ $totalCloudApis }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class="fi fi-rs-devices mt-2"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Total CloudApis') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 total-transfers" id="total-active">
									{{ $totalActiveCloudApis }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class="fi fi-rs-badge-check mt-2"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Active CloudApis') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 completed-transfers" id="total-inactive">
									{{ $totalInactiveCloudApis }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class="fi  fi-rs-exclamation mt-2"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Inactive CloudApis') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>  


<div class="row">
	<div class="col">
		<div class="card">
			<!-- Card header -->
			<div class="card-header border-0">
				<h3 class="mb-0">{{ __('CloudApis') }}</h3>
				<form action="" class="card-header-form">
					<div class="input-group">
						<input type="text" name="search" value="{{ $request->search ?? '' }}" class="form-control" placeholder="Search......">
						<select class="form-control" name="type">
							<option value="email" @if($type == 'email') selected="" @endif>{{ __('User Email') }}</option>
							<option value="name" @if($type == 'name') selected="" @endif>{{ __('CloudApi Name') }}</option>
							<option value="uuid" @if($type == 'uuid') selected="" @endif>{{ __('CloudApi Id') }}</option>
							<option value="phone" @if($type == 'phone') selected="" @endif>{{ __('Phone Number') }}</option>
							
						</select>
						<div class="input-group-btn">
							<button class="btn btn-neutral btn-icon"><i class="fas fa-search"></i></button>
						</div>
					</div>
				</form>
			</div>
			<!-- Light table -->
			<div class="table-responsive">
				<table class="table align-items-center table-flush">
					<thead class="thead-light">
						<tr>
							<th class="col-2">{{ __('CloudApi Name') }}</th>
							<th class="col-4">{{ __('User') }}</th>
							<th class="col-2">{{ __('Phone') }}</th>
							<th class="col-1">{{ __('Transactions') }}</th>
							<th class="col-1">{{ __('Status') }}</th>
							<th class="col-1 text-left">{{ __('Created At') }}</th>
							<th class="col-1 text-left">{{ __('Action') }}</th>
						</tr>
					</thead>
					@if(count($cloudapis) != 0)
					<tbody class="list">
						@foreach($cloudapis ?? [] as $cloudapi)
						<tr>
							<td class="text-left">
								{{ $cloudapi->name }}
							</td>
							<td>
								<a class="text-dark" href="{{ route('admin.customer.show',$cloudapi->user_id) }}">
									{{ Str::limit($cloudapi->user->name ?? '',15) }}
								</a>
							</td>
							<td>
	       						{{ $cloudapi->phone }}
							</td>

							<td class="text-center">
								{{ number_format($cloudapi->smstransaction_count) }}
							</td>
							
							<td>
								<span class="badge badge-{{ $cloudapi->status == 1 ? 'success' : 'danger' }}">
									{{ $cloudapi->status == 1 ? 'Active' : 'Inactive' }}
								</span>
							</td>
							
							<td class="text-center">
								{{ \Carbon\Carbon::parse($cloudapi->created_at)->format('d-F-Y') }}
							</td>
							<td>
								
								<div class="dropdown">
									<a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										
										<a class="dropdown-item delete-confirm" href="#" data-action="{{ route('admin.cloudapis.destroy',$cloudapi->id) }}">{{ __('Remove') }}</a>
										
									</div>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
					@endif
				</table>
				@if(count($cloudapis) == 0)
				<div class="text-center mt-2">
					<div class="alert  bg-gradient-primary text-white">
						<span class="text-left">{{ __('!Opps no records found') }}</span>
					</div>
				</div>
				@endif
			</div>
			<div class="card-footer py-4">
				{{ $cloudapis->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
			</div>	
		</div>
	</div>
</div>
@endsection