@extends('frontend.layouts.main2')
@section('content')
<style>
    .row{
        display:block !important;
    }
    .nav-link{
        padding:0 !important;
    }
</style>
<main>
    <section class="waf1">
	<div class="container">
	    <div class="wow fadeIn" >
			<h2 class="h2 text-center default"><strong>{{ $blog->title }}</strong></h2>
		</div>
		<div class="content_inner">
    <div class="main_img">
        <img src="{{ asset($blog->preview->value ?? '') }}" alt="image">
    </div>
    <div class="info">
        <p>{!! filterXss($blog->longDescription->value ?? '') !!}</p>
    </div>
</div>

	</div>


</section>
</main>
<!-- postbox area start -->
