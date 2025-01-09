<section class="clients paddwat30 wow fadeIn grncolr">
	<div class="container">
				<p class="h3 text-center  default q"><strong>Trusted by the <span class="color">fastest growing brands</span> in rapidly developing economies</strong></p>
				
		<div class="row slider">
			  <div class="slide-track" id="wacloudclients">  
			  @foreach($brands as $brandKey => $brand)
               @if($brand->lang == 'partner')
               
			 		<div class="slide">
						<img width="150" height="50"  alt="tik" class="integrations lazy_a" src="{{ asset($brand->slug) }}">
					</div>
				@endif
            @endforeach
			</div>
      </div>
   </div>
</section>