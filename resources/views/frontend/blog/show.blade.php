@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.header')
<main>
	<div class="bred_crumb blog_detail_bredcrumb">
      <div class="container">
        <div class="bred_text">
          <h1>Blog details</h1>
          <ul>
            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
            <li><span>»</span></li>
            <li>{{ __('Blog Details') }}</li>
          </ul>
        </div>
      </div>
    </div>
	<!-- breadcrumb area start -->
<!-- breadcrumb area end -->


<section class="blog_detail_section">
      <div class="container">
        <div class="blog_inner_pannel">
		<div class="section_title">
              <h2>{{ $blog->title }}</h2>
            </div>

			<div class="main_img">
              <img src="{{ asset($blog->preview->value ?? '') }}" alt="image">
            </div>

			<div class="info">
			<p>{!!  filterXss($blog->longDescription->value ?? '') !!}</p>
			</div>
	</div>
</div>
</section>

<!-- postbox area start -->
