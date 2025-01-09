<section class="waf8 paddwat60">
 <div class="container">
	<div class="row adjustbxflex center">
		<div class="col-md-12 col-sm-12 sec">
			<h2 class="h2 int default">{{__('Discover More WhatsApp, Facebook and Instagram’s share of social media messaging is unrivaled. Harness it with one of the fastest growing WhatsApp Business Solution Providers.')}}</h2>
				<div class="btns  space default">
					<a href="{{ url('/pricing') }}" target="_blank"  rel="noopener" class="btn brdr wht">{!! filterXss($banner->btnfirst ?? null) !!}</a>
					<a href="{{ !Auth::check() ? url('/login') : url('/login') }}" class="btn_nrml brr"> {{ !Auth::check() ? __('Sign In') : __('Dashboard') }} </a>
				</div>
		</div>
	</div>
  </div>
</section>