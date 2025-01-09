@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.header')
<main>

<div class="bred_crumb">
      <div class="container">
        <!-- shape animation  -->
        <span class="banner_shape1"> <img src="{{ asset('assets/frontend/images/banner-shape1.png')}}" alt="image" > </span>
        <span class="banner_shape2"> <img src="{{ asset('assets/frontend/images/banner-shape2.png')}}" alt="image" > </span>
        <span class="banner_shape3"> <img src="{{ asset('assets/frontend/images/banner-shape3.png')}}" alt="image" > </span>

        <div class="bred_text">
          <h1>{{ __('Recent Blogs') }}</h1>
          <ul>
            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
            <li><span>»</span></li>
            <li>{{ __('Blog') }}</li>
          </ul>
        </div>
      </div>
    </div>

<section class="row_am latest_story" id="blog">
     <!-- container start -->
      <div class="container">
      <div class="section_title" data-aos="fade-in" data-aos-duration="1500" data-aos-delay="100">
              <h2>Read latest <span>story</span></h2>
              <p><br></p>
          </div>
          <div class="row">

          @foreach($blogs ?? [] as $blog)
          <div class="col-md-4">
                <div class="story_box" data-aos="fade-up" data-aos-duration="1500">
                    <div class="story_img">
                      <img src="{{ asset($blog->preview->value ?? '') }}" alt="image" >
                      <span>45 min ago</span>
                    </div>
                    <div class="story_text">
                        <h3>{{ $blog->title }}</h3>
                        <p>{{ Str::limit($blog->shortDescription->value ?? '',200) }}</p>
                        <a href="{{ url('/blog',$blog->slug) }}">READ MORE</a>
                    </div>
                </div>
            </div>
            @endforeach
            @if(count($blogs) == 0)
                     <div class="alert alert-warning" role="alert">
                      {{ __('Opps there is no blog post available') }}
                     </div>
                     @endif

                     <div class="pagination_block">
                       {{ $blogs->appends($request->all())->links('vendor.pagination.bootstrap-5') }}
                     </div>

                     </div>
      <!-- container end -->
    </section>
</main>