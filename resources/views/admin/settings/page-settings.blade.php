@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=> __('Page Settings')])
@endsection
@section('content')
<div class="row">
	<div class="col-12 col-sm-12 col-lg-12">
		<div class="card">
			<div class="card-header">
				<h4>{{ __('Page settings') }}</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-4">
						<ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="home-tab4" data-toggle="tab" href="#home4" role="tab" aria-controls="home" aria-selected="true">{{ __('Primary Settings') }}</a>
							</li>
							
							<li class="nav-item mt-2">
								<a class="nav-link" id="profile-tab4" data-toggle="tab" href="#contact-page" role="tab" aria-controls="profile" aria-selected="false">{{ __('Contact Page') }}</a>
							</li>
							
							<li class="nav-item mt-2 none">
								<a class="nav-link" id="contact-tab4" data-toggle="tab" href="#contact4" role="tab" aria-controls="contact" aria-selected="false"></a>
							</li>
						</ul>
					</div>
					<div class="col-12 col-sm-12 col-md-8">
						<div class="tab-content no-padding" id="myTab2Content">
							<div class="tab-pane fade show active" id="home4" role="tabpanel" aria-labelledby="home-tab4">
								<form method="POST" action="{{ route('admin.page-settings.update','primary_data') }}" class="ajaxform" enctype="multipart/form-data">
									@method('PUT')
									<div class="form-group">
										<label>{{ __('Site Logo - Deep Colour') }}</label>
										<input type="file" accept="image/*" name="logo" class="form-control">
									</div>
									<div class="form-group">
										<label>{{ __('Site Logo - light colour') }}</label>
										<input type="file" accept="image/*" name="footer_logo" class="form-control">
									</div>
									<div class="form-group">
										<label>{{ __('Favicon') }}</label>
										<input type="file" accept="image/*" name="favicon" class="form-control">
									</div>
									<div class="form-group">
										<label>{{ __('Theme') }}</label>
										<select class="form-control" name="theme_path">
											<option value="frontend.index" {{ $theme_path == 'frontend.index' ? 'selected' : '' }}>{{ __('Theme-1') }}</option>
											<option value="frontend.index-1" {{ $theme_path == 'frontend.index-1' ? 'selected' : '' }}>{{ __('Theme-2') }}</option>
											
										</select>
									</div>
									
									<div class="form-group">
										<label>{{ __('Contact Email address') }}</label>
										<input type="email" name="contact_email" value="{{ $primary_data->contact_email ?? '' }}" class="form-control" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Contact Phone') }}</label>
										<input type="number" name="contact_phone"  class="form-control" required value="{{ $primary_data->contact_phone ?? '' }}">
									</div>
									<div class="form-group">
										<label>{{ __('Office Location') }}</label>
										<input type="text" name="address" class="form-control" required="" value="{{ $primary_data->address ?? '' }}">
									</div>
									<div class="form-group">
										<label>{{ __('Facbook  Profile Link') }}</label>
										<input type="url" name="socials[facebook]" class="form-control" value="{{ $primary_data->socials->facebook ?? '' }}">
									</div>
									<div class="form-group">
										<label>{{ __('Youtube  Profile Link') }}</label>
										<input type="url" name="socials[youtube]" class="form-control" value="{{ $primary_data->socials->youtube ?? '' }}">
									</div>
									<div class="form-group">
										<label>{{ __('Twitter  Profile Link') }}</label>
										<input type="url" name="socials[twitter]" class="form-control" value="{{ $primary_data->socials->twitter ?? '' }}">
									</div>
									<div class="form-group">
										<label>{{ __('Instagram  Profile Link') }}</label>
										<input type="url" name="socials[instagram]" class="form-control" value="{{ $primary_data->socials->instagram ?? '' }}">
									</div>
									<div class="form-group">
										<label>{{ __('Linkedin Profile  Link') }}</label>
										<input type="url" name="socials[linkedin]" class="form-control" value="{{ $primary_data->socials->linkedin ?? '' }}">
									</div>
									<div class="form-group">
										<button class="btn btn-neutral submit-button">{{ __('Update') }}</button>
									</div>

								</form>
							</div>
							<div class="tab-pane fade" id="contact-page" role="tabpanel" aria-labelledby="profile-tab4">
								<form method="POST" action="{{ route('admin.page-settings.update','contact-page') }}" class="ajaxform" enctype="multipart/form-data">
									@method('PUT')
									<div class="form-group">
										<label>{{ __('Office Address') }}</label>
										<input type="text" name="data[address]" class="form-control" value="{{ $contact_page->address ?? '' }}" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Country Name') }}</label>
										<input type="text" name="data[country]" class="form-control" value="{{ $contact_page->country ?? '' }}" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Google Map Link') }}</label>
										<input type="text" name="data[map_link]" class="form-control" value="{{ $contact_page->map_link ?? '' }}" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Contact Number 1') }}</label>
										<input type="text" name="data[contact1]" class="form-control" value="{{ $contact_page->contact1 ?? '' }}" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Contact Number 2') }}</label>
										<input type="text" name="data[contact2]" class="form-control" value="{{ $contact_page->contact2 ?? '' }}" required="">
									</div>

									<div class="form-group">
										<label>{{ __('Contact Email 1') }}</label>
										<input type="email" name="data[email1]" class="form-control" value="{{ $contact_page->email1 ?? '' }}" required="">
									</div>

									<div class="form-group">
										<label>{{ __('Contact Email 2') }}</label>
										<input type="email" name="data[email2]" class="form-control" value="{{ $contact_page->email2 ?? '' }}" required="">
									</div>

									<div class="form-group">
										<button class="btn btn-neutral submit-button">{{ __('Update') }}</button>
									</div>
								</form>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection