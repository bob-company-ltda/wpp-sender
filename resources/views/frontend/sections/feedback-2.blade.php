<section class="waf6 paddwat30 borderround yelloww">
	<div class="container">
		<div class="wow fadeIn">
			<h2 class="h2 text-center default"><strong>{{__('Over thousands customers') }}</strong></h2>
			<p class="prgh text-center default">{{__('What some of our thousands customers across multiple countries think.') }} </p>
	</div>
	
		<div class="row center">
		    
		  <ul class="adjustbxflex centerm">
		      
		       @foreach($testimonials as $testimonial)
				<li class="adjustbxflex wow fadeIn" >
					<div class="default">
						<p class="prghsp"> {{ Str::limit($testimonial->excerpt->value ?? '',150) }}</p>
						<span><b>{{ $testimonial->title ?? '' }}</b><br> {{ $testimonial->slug ?? '' }}</span>
					</div>
				</li>
				@endforeach
			</ul>
		</div>
	</div>
</section>