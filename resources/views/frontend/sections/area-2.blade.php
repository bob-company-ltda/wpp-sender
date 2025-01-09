<section class="waf3 paddwat30">
	<div class="container">
		<div class="wow fadeIn" >
			<h2 class="h2 text-center default"><strong>{{ optional($overview)->overview_header }}</strong></h2>
			<p class="prgh text-center default">{{ optional($overview)->overview_subheader }} </p>
		</div>
		<div class="row adjustbxflex ">
			<div class="col-lg-4 col-md-4 col-sm-12 wow fadeIn" data-wow-delay="0s">
				<div class="content">
						<img class="img-widthh lazy_a" src="{{ asset(optional($overview)->overview_image_1) }}" alt="{{ optional($overview)->overview_title_1 }}" />
				<div class=" default">
					<h4 class="h4">{{ optional($overview)->overview_title_1 }}</h4>
					<p class="prghsp"> {{ optional($overview)->overview_subtitle_1 }}</p>
				</div>
				</div>
			</div>	
			<div class="col-lg-4 col-md-4 col-sm-12 wow fadeIn" data-wow-delay="0s">
			 <div class="content ">
				   <img class="img-widthh lazy_a" src="{{ asset(optional($overview)->overview_image_2) }}" alt="whatsapp communication"/>
				<div class=" default">
					<h4 class="h4">{{ optional($overview)->overview_title_2 }}</h4>
					<p class="prghsp">{{ optional($overview)->overview_subtitle_2 }}</p>
				</div>
				</div>
			</div>	
			<div class="col-lg-4 col-md-4 col-sm-12 wow fadeIn" data-wow-delay="0s">
			 <div class="content ">
				<img class="img-widthh lazy_a"   src="{{ asset(optional($overview)->overview_image_3) }}" alt="whatsapp communication"/>
				<div class=" default">
				 
					<h4 class="h4">{{ optional($overview)->overview_title_3 }}</h4>
					<p class="prghsp">{{ optional($overview)->overview_subtitle_3 }}</p>
				</div>
			</div>
			</div>	
		</div>
	</div>
</section>