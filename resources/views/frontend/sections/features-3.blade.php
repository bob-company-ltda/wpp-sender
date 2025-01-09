<section class="waf4  item2 borderround bluecolr">
  <div class="wow fadeIn feat">
    <h2 class="h3 text-center default">
      <strong>Features!</strong>
    </h2>
  </div>
  <div class="container">
       @foreach($features as $feature)
    <div class="row adjustbxflex center paddwat30">
        @if($loop->iteration % 2 == 0)
        <div class="col-lg-6 col-md-6 col-sm-12 @if($loop->iteration % 2 == 0) order-2 @else text-center @endif">
            <img class="home-width nopdd carauselimg" src="{{ asset($feature->banner->value ?? '') }}" alt="wacloud Home" width="400" height="100%" />
        </div>
        @endif
        <div class="col-lg-6 col-md-6 col-sm-12 @if($loop->iteration % 2 == 0) text-center @else order-2 @endif">
            <div class="wcontent default">
                <h3 class="h3">
                    <strong>{{ $feature->title }}</strong>
                </h3>
                <div class="circledot">
                    <span>{{ $feature->longDescription->value ?? '' }}</span>
                </div>
                <div class="btns default">
                    <a href="/features" target="_self" class="btn brdr green">Read More</a>
                </div>
            </div>
        </div>
        @if($loop->iteration % 2 != 0)
        <div class="col-lg-6 col-md-6 col-sm-12 @if($loop->iteration % 2 == 0) order-2 @else text-center @endif">
            <img class="home-width nopdd carauselimg" src="{{ asset($feature->banner->value ?? '') }}" alt="wacloud Home" width="400" height="100%" />
        </div>
        @endif
    </div>
@endforeach

  </div>
  
  
  <div class="row center space_btm text-center">
    <div class="btns text-center space default">
      <a href="{{ !Auth::check() ? url('/login') : url('/login') }}" class="btn brdr green">{{ !Auth::check() ? __('Get free trial') : __('Dashboard') }}</a>
      <a href="{{ url('/pricing') }}" class="btn_nrml hvrbrdr">{{__('Pricing') }} </a>
    </div>
  </div>
</section>