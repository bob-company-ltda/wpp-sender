@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=> __('Cron Jobs')])
@endsection
@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>{{ __('Execute Campaigns') }}</h4>
			</div>
			<div class="card-body">
				<div class="code"><p class="text-white">curl -s {{ url('/cron/execute-campaign') }}</p></div>
				<br>
				<strong>{{ __('Every Minute') }}</strong>
			</div>
		</div>
	</div>
	
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>{{ __('Execute Bulk Shoot') }}</h4>
			</div>
			<div class="card-body">
				<div class="code"><p class="text-white">curl -s {{ url('/run-queue-worker') }}</p></div>
				<br>
				<strong>{{ __('Every Minute') }}</strong>
			</div>
		</div>
	</div>
	
</div>
@if(isset($pluginConfigs['webhooks_payload']) && $pluginConfigs['webhooks_payload']['status'] === 1)
@dynamicInclude('Webhooks', 'cron')
@endif
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>{{ __('Notify to customer before expire the subscription') }}</h4>
			</div>
			<div class="card-body">
				<div class="code"><p class="text-white">curl -s {{ url('/cron/notify-to-user') }}</p></div>
				<br>
				<strong>{{ __('Everyday') }}</strong>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>{{ __('Remove Junk Conversations') }}</h4>
			</div>
			<div class="card-body">
				<div class="code"><p class="text-white">curl -s {{ url('/cron/remove-junk-conversation') }}</p></div>
				<br>
				<strong>{{ __('Everyday') }}</strong>
			</div>
		</div>
	</div>
</div>
@endsection