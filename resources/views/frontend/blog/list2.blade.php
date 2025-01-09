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
    <section class="waf3 paddwat30">
	<div class="container">
		<div class="wow fadeIn" >
			<h2 class="h2 text-center default"><strong>{{__('Read latest story') }}</strong></h2>
			<p class="prgh text-center default">{{__('All Insight related to WhatsApp API') }} </p>
		</div>
		<div class="row adjustbxflex ">
		    @foreach($blogs ?? [] as $blog)
		    <div class="col-lg-4 col-md-4 col-sm-12 wow fadeIn" data-wow-delay="0s">
		        <div class="content">
						<img class="img-widthh line lazy_a" src="{{ asset($blog->preview->value ?? '') }}" alt="image" />
				<div class=" default">
					<h4 class="h4">{{ $blog->title }}</h4>
					<p class="prghsp">{{ Str::limit($blog->shortDescription->value ?? '',200) }}</p>
					<a class="btn_nrml hvrbrdr" href="{{ url('/blog',$blog->slug) }}">READ MORE</a>
				</div>
				</div>
			</div>
			@endforeach
		</div>
			@if(count($blogs) == 0)
                     <div class="alert alert-warning" role="alert">
                      {{ __('Opps there is no blog post available') }}
                     </div>
                     @endif

                     <div class="pagination_block">
                       {{ $blogs->appends($request->all())->links('vendor.pagination.bootstrap-5') }}
                     </div>
    </div>
</section>
		
    </section>
</main>