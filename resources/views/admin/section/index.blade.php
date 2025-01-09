@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=> __('Edit Section Page')])
@endsection
@section('content')
<div class="row">
	<div class="col-12 col-sm-12 col-lg-12">
		<div class="card">
			<div class="card-header">
				<h4>{{ __('Section') }}</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-3">
						<ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="home-tab4" data-toggle="tab" href="#home4" role="tab" aria-controls="home" aria-selected="true">{{ __('Banner Section') }}</a>
							</li>
							<li class="nav-item mt-2">
								<a class="nav-link" id="profile-tab4" data-toggle="tab" href="#profile4" role="tab" aria-controls="profile" aria-selected="false">{{ __('Features Section') }}</a>
							</li>
							<li class="nav-item mt-2">
								<a class="nav-link" id="about-tab5" data-toggle="tab" href="#about5" role="tab" aria-controls="about" aria-selected="false">{{ __('About Section') }}</a>
							</li>
							<li class="nav-item mt-2">
								<a class="nav-link" id="overview-tab6" data-toggle="tab" href="#overview6" role="tab" aria-controls="overview" aria-selected="false">{{ __('Overview Section') }}</a>
							</li>
							<li class="nav-item mt-2">
								<a class="nav-link" id="howitworks-tab7" data-toggle="tab" href="#howitworks7" role="tab" aria-controls="overview" aria-selected="false">{{ __('How It Works Section') }}</a>
							</li>
							<li class="nav-item mt-2">
								<a class="nav-link" id="download-tab8" data-toggle="tab" href="#download8" role="tab" aria-controls="download" aria-selected="false">{{ __('Download Section') }}</a>
							</li>
						</ul>
					</div>
					<div class="col-12 col-sm-12 col-md-9">
						<div class="tab-content no-padding" id="myTab2Content">
							<div class="tab-pane fade show active" id="home4" role="tabpanel" aria-labelledby="home-tab4">
								<form method="post" action="{{ route('admin.section.store') }}" enctype="multipart/form-data" class="ajaxform">
									@csrf
									<input type="hidden" name="type" value="banner">
									<input type="hidden" name="lang" value="en">
									<div class="form-group">
										<label>{{ __('Banner Header:') }}</label>
										<input type="text" name="banner_header" required="" class="form-control" value="{{ $banner->banner_header ?? '' }}" placeholder="">
									</div>
									<div class="form-group">
										<label>{{ __('Button  First:') }}</label>
										<input type="text" name="btnfirst"  class="form-control" value="{{ $banner->btnfirst ?? '' }}" placeholder="Explore">
									</div>
									<div class="form-group">
										<label>{{ __('Button Second') }}</label>
										<input type="text" name="btnsecond"  class="form-control" value="{{ $banner->btnsecond ?? '' }}" placeholder="Sign In">
									</div>
									<div class="form-group">
										<label>{{ __('Used This App') }}</label>
										<input type="text" name="usedthis"  class="form-control" value="{{ $banner->usedthis ?? '' }}" placeholder="">
									</div>
									<div class="form-group">
										<label>{{ __('Phone Image First:') }}</label>
										<input type="file" name="phone_image_1" accept="image/*"  class="form-control" >
									</div>
									<div class="form-group">
										<label>{{ __('Phone Image Second:') }}</label>
										<input type="file" name="phone_image_2" accept="image/*"  class="form-control" >
									</div>
									<div class="form-group">
										<label>{{ __('Phone Image Third:') }}</label>
										<input type="file" name="phone_image_3" accept="image/*"  class="form-control" >
									</div>
									<div class="form-group">
										<button class="btn btn-neutral submit-button" type="submit">{{ __('Update') }}</button>
									</div>
								</form>
							</div>
							<div class="tab-pane fade" id="profile4" role="tabpanel" aria-labelledby="profile-tab4">
								<form method="post" action="{{ route('admin.section.store') }}" class="ajaxform">
									@csrf
									<input type="hidden" name="type" value="features">
									<input type="hidden" name="lang" value="en">

									<div class="form-group">
										<label>{{ __('Features Header') }}</label>
										<input type="text" name="feature_header"  class="form-control" value="{{ $features->feature_header ?? '' }}" placeholder="Features that makes app different!" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Features Sub Header') }}</label>
										<input type="text" name="feature_subheader"  class="form-control" value="{{ $features->feature_subheader ?? '' }}" placeholder="Features that makes app different!" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Feature Image :') }}</label>
										<input type="file" name="feature_image" accept="image/*"  class="form-control" >
									</div>

									<div class="form-group">
										<label>{{ __('Features 1') }}</label>
										<input type="text" name="feature_1"  class="form-control" value="{{ $features->feature_1 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Features 1 details') }}</label>
										<input type="text" name="feature_1_details"  class="form-control" value="{{ $features->feature_1_details ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Features 2') }}</label>
										<input type="text" name="feature_2"  class="form-control" value="{{ $features->feature_2 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Features 2 details') }}</label>
										<input type="text" name="feature_2_details"  class="form-control" value="{{ $features->feature_2_details ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Features 3') }}</label>
										<input type="text" name="feature_3"  class="form-control" value="{{ $features->feature_3 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Features 3 details') }}</label>
										<input type="text" name="feature_3_details"  class="form-control" value="{{ $features->feature_3_details ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Features 4') }}</label>
										<input type="text" name="feature_4"  class="form-control" value="{{ $features->feature_4 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Features 4 details') }}</label>
										<input type="text" name="feature_4_details"  class="form-control" value="{{ $features->feature_4_details ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<button class="btn btn-neutral submit-button" type="submit">{{ __('Update') }}</button>
									</div>
								</form>
							</div>

							<div class="tab-pane fade" id="about5" role="tabpanel" aria-labelledby="about-tab5">
								<form method="post" action="{{ route('admin.section.store') }}" class="ajaxform">
									@csrf
									<input type="hidden" name="type" value="about">
									<input type="hidden" name="lang" value="en">

									<div class="form-group">
										<label>{{ __('About Header') }}</label>
										<input type="text" name="about_header"  class="form-control" value="{{ $about->about_header ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('About Sub Header') }}</label>
										<input type="text" name="about_subheader"  class="form-control" value="{{ $about->about_subheader ?? '' }}" placeholder="" required="">
									</div>

									<div class="form-group">
										<label>{{ __('Frame Image :') }}</label>
										<input type="file" name="frame_image" accept="image/*"  class="form-control" >
									</div>

									<div class="form-group">
										<label>{{ __('frame image 2 :') }}</label>
										<input type="file" name="frame_image_2" accept="image/*"  class="form-control" >
									</div>

									<div class="form-group">
										<label>{{ __('Connected Api') }}</label>
										<input type="number" name="about_api"  class="form-control" value="{{ $about->about_api ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Satisfied User') }}</label>
										<input type="number" name="satisfied_user"  class="form-control" value="{{ $about->satisfied_user ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Customer Review') }}</label>
										<input type="number" name="customer_review"  class="form-control" value="{{ $about->customer_review ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Countries') }}</label>
										<input type="number" name="about_countries"  class="form-control" value="{{ $about->about_countries ?? '' }}" placeholder="" required="">
									</div>


									
									<div class="form-group">
										<button class="btn btn-neutral submit-button" type="submit">{{ __('Update') }}</button>
									</div>
								</form>
							</div>

							<div class="tab-pane fade" id="overview6" role="tabpanel" aria-labelledby="overview-tab6">
								<form method="post" action="{{ route('admin.section.store') }}" class="ajaxform">
									@csrf
									<input type="hidden" name="type" value="overview">
									<input type="hidden" name="lang" value="en">

									<div class="form-group">
										<label>{{ __('Overview Header') }}</label>
										<input type="text" name="overview_header"  class="form-control" value="{{ $overview->overview_header ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Overview Sub Header') }}</label>
										<input type="text" name="overview_subheader"  class="form-control" value="{{ $overview->overview_subheader ?? '' }}" placeholder="" required="">
									</div>

									<div class="form-group">
										<label>{{ __('overview Image 1 :') }}</label>
										<input type="file" name="overview_image_1" accept="image/*"  class="form-control" >
									</div>

									<div class="form-group">
										<label>{{ __('overview image 2 :') }}</label>
										<input type="file" name="overview_image_2" accept="image/*"  class="form-control" >
									</div>

									<div class="form-group">
										<label>{{ __('overview image 3 :') }}</label>
										<input type="file" name="overview_image_3" accept="image/*"  class="form-control" >
									</div>

									<div class="form-group">
										<label>{{ __('Overview Title 1') }}</label>
										<input type="text" name="overview_title_1"  class="form-control" value="{{ $overview->overview_title_1 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Overview Subtitle 1') }}</label>
										<input type="text" name="overview_subtitle_1"  class="form-control" value="{{ $overview->overview_subtitle_1 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Overview Title 2') }}</label>
										<input type="text" name="overview_title_2"  class="form-control" value="{{ $overview->overview_title_2 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Overview Subtitle 2') }}</label>
										<input type="text" name="overview_subtitle_2"  class="form-control" value="{{ $overview->overview_subtitle_2 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Overview Title 3') }}</label>
										<input type="text" name="overview_title_3"  class="form-control" value="{{ $overview->overview_title_3 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Overview Subtitle 3') }}</label>
										<input type="text" name="overview_subtitle_3"  class="form-control" value="{{ $overview->overview_subtitle_3 ?? '' }}" placeholder="" required="">
									</div>


									
									<div class="form-group">
										<button class="btn btn-neutral submit-button" type="submit">{{ __('Update') }}</button>
									</div>
								</form>
							</div>


							<div class="tab-pane fade" id="howitworks7" role="tabpanel" aria-labelledby="howitworks-tab7">
								<form method="post" action="{{ route('admin.section.store') }}" class="ajaxform">
									@csrf
									<input type="hidden" name="type" value="how-it-works">
									<input type="hidden" name="lang" value="en">

									<div class="form-group">
										<label>{{ __('Work Header') }}</label>
										<input type="text" name="work_header"  class="form-control" value="{{ $work->work_header ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Work Sub Header') }}</label>
										<input type="text" name="work_subheader"  class="form-control" value="{{ $work->work_subheader ?? '' }}" placeholder="" required="">
									</div>

									<div class="form-group">
										<label>{{ __('Step Image 1') }}</label>
										<input type="file" name="step_image_1" accept="image/*"  class="form-control" >
									</div>

									<div class="form-group">
										<label>{{ __('Step 1 Title') }}</label>
										<input type="text" name="step_title_1"  class="form-control" value="{{ $work->step_title_1 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Step 1 Subtitle') }}</label>
										<input type="text" name="step_subtitle_1"  class="form-control" value="{{ $work->step_subtitle_1 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Step 1 Description') }}</label>
										<input type="text" name="step_description_1"  class="form-control" value="{{ $work->step_description_1 ?? '' }}" placeholder="" required="">
									</div>

									<div class="form-group">
										<label>{{ __('Step Image 2') }}</label>
										<input type="file" name="step_image_2" accept="image/*"  class="form-control" >
									</div>

									<div class="form-group">
										<label>{{ __('Step 2 Title') }}</label>
										<input type="text" name="step_title_2"  class="form-control" value="{{ $work->step_title_2 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Step 2 Subtitle') }}</label>
										<input type="text" name="step_subtitle_2"  class="form-control" value="{{ $work->step_subtitle_2 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Step 2 Description') }}</label>
										<input type="text" name="step_description_2"  class="form-control" value="{{ $work->step_description_2 ?? '' }}" placeholder="" required="">
									</div>

									<div class="form-group">
										<label>{{ __('Step Image 3') }}</label>
										<input type="file" name="step_image_3" accept="image/*"  class="form-control" >
									</div>

									<div class="form-group">
										<label>{{ __('Step 3 Title') }}</label>
										<input type="text" name="step_title_3"  class="form-control" value="{{ $work->step_title_3 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Step 3 Subtitle') }}</label>
										<input type="text" name="step_subtitle_3"  class="form-control" value="{{ $work->step_subtitle_3 ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Step 3 Description') }}</label>
										<input type="text" name="step_description_3"  class="form-control" value="{{ $work->step_description_3 ?? '' }}" placeholder="" required="">
									</div>

									<div class="form-group">
										<label>{{ __('Video Image') }}</label>
										<input type="file" name="video_image" accept="image/*"  class="form-control" >
									</div>
									<div class="form-group">
										<label>{{ __('Viedo Header') }}</label>
										<input type="text" name="video_header"  class="form-control" value="{{ $work->video_header ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Video Url') }}</label>
										<input type="text" name="video_url"  class="form-control" value="{{ $work->video_url ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<button class="btn btn-neutral submit-button" type="submit">{{ __('Update') }}</button>
									</div>
								</form>
							</div>


							<div class="tab-pane fade" id="download8" role="tabpanel" aria-labelledby="download-tab8">
								<form method="post" action="{{ route('admin.section.store') }}" class="ajaxform">
									@csrf
									<input type="hidden" name="type" value="download">
									<input type="hidden" name="lang" value="en">

									<div class="form-group">
										<label>{{ __('Download Header') }}</label>
										<input type="text" name="download_header"  class="form-control" value="{{ $download->download_header ?? '' }}" placeholder="" required="">
									</div>
									<div class="form-group">
										<label>{{ __('Download Sub Header') }}</label>
										<input type="text" name="download_subheader"  class="form-control" value="{{ $download->downlaod_subheader ?? '' }}" placeholder="" required="">
									</div>

									<div class="form-group">
										<label>{{ __('Hero Image 1') }}</label>
										<input type="file" name="hero_image_1" accept="image/*"  class="form-control" >
									</div>

									<div class="form-group">
										<label>{{ __('Hero Image 2') }}</label>
										<input type="file" name="hero_image_2" accept="image/*"  class="form-control" >
									</div>

									
									<div class="form-group">
										<button class="btn btn-neutral submit-button" type="submit">{{ __('Update') }}</button>
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