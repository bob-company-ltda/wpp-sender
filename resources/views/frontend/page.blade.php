@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.header')
<main>
<div class="bred_crumb">
    <div class="container">
        @for ($i = 1; $i <= 3; $i++)
            <span class="banner_shape{{ $i }}">
                <img src="{{ asset("assets/frontend/images/banner-shape{$i}.png") }}" alt="shape image {{ $i }}">
            </span>
        @endfor
        <div class="bred_text">
            <h1>{{ $page->title ?? 'Page Title' }}</h1>
        </div>
    </div>
</div>

<section class="blog_detail_section">
    <div class="container">
        <div class="blog_inner_panel">
            <div class="section_title">
                {{ $page->title ?? 'Page Title' }}
            </div>                 
            <div class="info">
                <p>{!! filterXss($page->description->value ?? 'Description not available.') !!}</p>
            </div>
        </div>
    </div>
</section>
</main>
@endsection