@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',[
'title'=> __('Update Addons'),
'buttons'=>[
	 [
            'name' => '<i class="fas fa-plus"></i> &nbspInstall Plugin',
            'url' => '#',
            'components' => 'data-toggle="modal" data-target="#installPluginModal" id="installPluginModals"',
            'is_button' => true,
        ]
 ]
])
@endsection
@section('content')

	<div class="row justify-content-center">
		<div class="col-lg-10 card-wrapper">	

		   
			<div class="container mt-4">
    <div class="card-header">
					<div class="row w-100">
						<div class="col-6">
							<h3 class="mb-0">{{ __('Actiavte Addons') }}</h3>
						</div>
					

						
					</div>
				</div>
    <div class="row">
        @foreach ($plugins as $plugin)
    <div class="col-md-4">
        <div class="card mb-4">
            <img src="{{ $plugin['photo_url'] }}" class="card-img-top" alt="{{ $plugin['name'] }}">
            <div class="card-body">
                <h5 class="card-title">{{ $plugin['name'] }}</h5>
                <p class="card-text">{{ $plugin['description'] }}</p>
                <p class="card-text mb-2 mt-2">
                    <strong>Price:</strong> 
                    {{ $plugin['price'] == 0 ? 'Free' : '₹' . number_format($plugin['price'], 2) }}
                </p>
               @if ($plugin['price'] == 0)
    <a href="{{ $plugin['download_link'] }}" class="btn btn-dark">{{__('Download')}}</a>
@elseif(isset($pluginConfigs[$plugin['product_id']]) && $pluginConfigs[$plugin['product_id']]['status'] === 1)
    <a href="#" class="btn btn-success">{{__('Installed')}}</a>
@else
    <a href="{{ $plugin['purchase_link'] }}" class="btn btn-primary">{{__('Purchase')}}</a>
@endif
<a href="{{ $plugin['documents'] }}"><p class="text-right"><i class="fas fa-info-circle"></i> Read More</p></a>
            </div>
        </div>
    </div>
@endforeach

    </div>
</div>
		</div>		
	</div>
	
	<div class="modal fade" id="installPluginModal" tabindex="-1" role="dialog" aria-labelledby="installPluginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="installPluginModalLabel">{{__('Install Plugin')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="installPluginForm" action="{{ route('admin.addons.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="plugin_zip">{{__('Select ZIP file')}}</label>
                        <input type="file" class="form-control" id="plugin_zip" name="plugin_zip" required>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Install Plugin')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="toast-container" class="toast-container"></div>
@if(session('success'))
    <script>
        toastr.success("{{ session('success') }}", "Success", {
            timeOut: 5000
        });
    </script>
@endif

@if(session('error'))
    <script>
        toastr.error("{{ session('error') }}", "Error", {
            timeOut: 5000
        });
    </script>
@endif
@endsection
@push('js')
@endpush